<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\models;

use Yii;

/**
 * This is the model class for table "{{%aggregate_rating}}".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $target_id
 * @property integer $likes
 * @property integer $dislikes
 * @property double $rating
 */
class AggregateRating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%aggregate_rating}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'target_id', 'likes', 'dislikes', 'rating'], 'required'],
            [['model_id', 'target_id', 'likes', 'dislikes'], 'integer'],
            [['rating'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('vote', 'ID'),
            'model_id' => Yii::t('vote', 'Model ID'),
            'target_id' => Yii::t('vote', 'Target ID'),
            'likes' => Yii::t('vote', 'Likes'),
            'dislikes' => Yii::t('vote', 'Dislikes'),
            'rating' => Yii::t('vote', 'Rating'),
        ];
    }
}
