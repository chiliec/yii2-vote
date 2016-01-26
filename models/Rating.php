<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\models;

use Yii;
use yii\base\InvalidParamException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%rating}}".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $target_id
 * @property integer $user_id
 * @property string $user_ip
 * @property integer $value
 * @property integer $date
 */
class Rating extends ActiveRecord
{
    const VOTE_LIKE = 1;
    const VOTE_DISLIKE = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rating}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'date',
                'updatedAtAttribute' => 'date',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'target_id', 'user_ip', 'value'], 'required'],
            [['model_id', 'target_id', 'user_id', 'value'], 'integer'],
            [['user_ip'], 'string', 'max' => 39]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Model ID',
            'target_id' => 'Target ID',
            'user_id' => 'User ID',
            'user_ip' => 'User IP',
            'value' => 'Value',
            'date' => 'Date',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->cache->delete('rating' . $this->attributes['model_id'] . 'target' . $this->attributes['target_id']);

        $modelName = static::getModelNameById($this->attributes['model_id']);
        $model = new $modelName;
        if (null !== $behavor = $model->getBehavior('rating')) {
            $behavor->updateRating();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @param string $modelName Name of model
     * @return integer|false Id corresponding model or false if matches not found
     */
    public static function getModelIdByName($modelName)
    {
        if (null !== $models = Yii::$app->getModule('vote')->models) {
            $modelId = array_search($modelName, $models);
            if (is_int($modelId)) {
                return $modelId;
            }
            foreach ($models as $key => $value) {
                if (!is_array($value)) {
                    continue;
                }
                if ($value['modelName'] === $modelName) {
                    return $key;
                }
            }
        }
        return false;
    }

    /**
     * @param integer $modelId Id of model
     * @return string|false Model name or false if matches not found
     */
    public static function getModelNameById($modelId)
    {
        if (null !== $models = Yii::$app->getModule('vote')->models) {
            if (isset($models[$modelId])) {
                if (is_array($models[$modelId])) {
                    return $models[$modelId]['modelName'];
                } else {
                    return $models[$modelId];
                }
            }
        }
        return false;
    }

    /**
     * @param integer $modelId Id of model
     * @return boolean Checks exists permission for guest voting in model params or return global value
     */
    public static function getIsAllowGuests($modelId)
    {
        $models = Yii::$app->getModule('vote')->models;
        if (isset($models[$modelId]['allowGuests'])) {
            return $models[$modelId]['allowGuests'];
        }
        return Yii::$app->getModule('vote')->allowGuests;
    }

    /**
     * @param string $modelId Id of model
     * @return boolean Checks exists permission for change vote in model params or return global value
     */
    public static function getIsAllowChangeVote($modelId)
    {
        $models = Yii::$app->getModule('vote')->models;
        if (isset($models[$modelId]['allowChangeVote'])) {
            return $models[$modelId]['allowChangeVote'];
        }
        return Yii::$app->getModule('vote')->allowChangeVote;
    }

    /**
     * @param string $modelId Id of model
     * @param integer $targetId Current value of primary key
     * @return array ['likes', 'dislikes', 'rating']
     */
    public static function getRating($modelId, $targetId)
    {
        $cacheKey = 'rating' . $modelId . 'target' . $targetId;
        $result = Yii::$app->cache->get($cacheKey);
        if ($result === false) {
            $likes = static::find()->where(['model_id' => $modelId, 'target_id' => $targetId, 'value' => self::VOTE_LIKE])->count();
            $dislikes = static::find()->where(['model_id' => $modelId, 'target_id' => $targetId, 'value' => self::VOTE_DISLIKE])->count();
            if ($likes + $dislikes !== 0) {
                // Рейтинг = Нижняя граница доверительного интервала Вильсона (Wilson) для параметра Бернулли
                // http://habrahabr.ru/company/darudar/blog/143188/
                $rating = (($likes + 1.9208) / ($likes + $dislikes) - 1.96 * SQRT(($likes * $dislikes)
                            / ($likes + $dislikes) + 0.9604) / ($likes + $dislikes)) / (1 + 3.8416 / ($likes + $dislikes));
            } else {
                $rating = 0;
            }
            $rating = round($rating * 10, 2);
            $result = ['likes' => $likes, 'dislikes' => $dislikes, 'rating' => $rating];
            Yii::$app->cache->set($cacheKey, $result);
        }
        return $result;
    }

    /**
     * Converts a printable IP into an unpacked binary string
     *
     * @author Mike Mackintosh - mike@bakeryphp.com
     * @link http://www.highonphp.com/5-tips-for-working-with-ipv6-in-php
     * @param string $ip
     * @return string $bin
     */
    public static function compressIp($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return current(unpack('A4', inet_pton($ip)));
        }
        elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return current(unpack('A16', inet_pton($ip)));
        }
        return false;
    }

    /**
     * Converts an unpacked binary string into a printable IP
     *
     * @author Mike Mackintosh - mike@bakeryphp.com
     * @link http://www.highonphp.com/5-tips-for-working-with-ipv6-in-php
     * @param string $str
     * @return string $ip
     */
    public static function expandIp($str) 
    {
        if (strlen($str) == 16 OR strlen($str) == 4) {
            return inet_ntop(pack("A".strlen($str), $str));
        }
        return false;
    }

}
