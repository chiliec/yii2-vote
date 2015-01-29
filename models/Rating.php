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
     * @param string $model_name Name of model
     * @return integer|false Id corresponding model or false if matches not found
     */
    public static function getModelIdByName($model_name)
    {
        $matchingModels = Yii::$app->getModule('vote')->matchingModels;
        if(isset($matchingModels[$model_name]['id'])) {
            return $matchingModels[$model_name]['id'];
        }
        if(isset($matchingModels[$model_name])) {
            return $matchingModels[$model_name];
        }
        return false;
    }

    /**
     * @param string $model_name Name of model
     * @return boolean Checks exists permission for guest voting in model params or return global value
     */
    public static function getIsAllowGuests($model_name)
    {
        $matchingModels = Yii::$app->getModule('vote')->matchingModels;
        if(isset($matchingModels[$model_name]) && is_array($matchingModels[$model_name])) {
            if(array_key_exists('allow_guests', $matchingModels[$model_name])) {
                return $matchingModels[$model_name]['allow_guests'];
            }
        }
        return Yii::$app->getModule('vote')->allow_guests;
    }

    /**
     * @param string $model_name Name of model
     * @param integer $target_id Current value of primary key
     * @return array
     */
    public function getRating($model_name, $target_id)
    {
        $model_id = $this->getModelIdByName($model_name);
        if(!is_int($model_id)) {
            throw new InvalidParamException('Model name not recognized');
        }

        $likes = Yii::$app->cache->get('likes'.$model_name.$target_id);
        if($likes === false) {
            $likes = $this->find()->where(['model_id'=>$model_id, 'target_id'=>$target_id, 'value'=>1])->count();
            Yii::$app->cache->set('likes'.$model_name.$target_id, $likes);
        }

        $dislikes = Yii::$app->cache->get('dislikes'.$model_name.$target_id);
        if($dislikes === false) {
            $dislikes = $this->find()->where(['model_id'=>$model_id, 'target_id'=>$target_id, 'value'=>0])->count();
            Yii::$app->cache->set('dislikes'.$model_name.$target_id, $dislikes);
        }

        $rating = Yii::$app->cache->get('aggregate_rating'.$model_name.$target_id);
        if ($rating === false) {
            if ($likes+$dislikes != 0) {
                // Рейтинг = Нижняя граница доверительного интервала Вильсона (Wilson) для параметра Бернулли
                // http://habrahabr.ru/company/darudar/blog/143188/
                $rating = (($likes + 1.9208) / ($likes + $dislikes) - 1.96 * SQRT(($likes * $dislikes)
                            / ($likes + $dislikes) + 0.9604) / ($likes + $dislikes)) / (1 + 3.8416 / ($likes + $dislikes));
            } else {
                $rating = 0;
            }
            $rating = round($rating*10, 2);
            Yii::$app->cache->set('aggregate_rating'.$model_name.$target_id, $rating);
        }

        return ['likes'=>$likes, 'dislikes'=>$dislikes, 'aggregate_rating'=>$rating];
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
        if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            return current(unpack('A4', inet_pton($ip)));
        }
        elseif(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
            return current(unpack('A16', inet_pton($ip)));
        }
        return false;
    }

}
