<?php

namespace chiliec\vote\models;

use Yii;

/**
 * This is the model class for table "{{%rating}}".
 *
 * @property string $id
 * @property integer $model_id
 * @property string $target_id
 * @property string $user_ip
 * @property integer $value
 * @property integer $date
 */
class Rating extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%rating}}';
    }


    public function beforeSave($insert) {
        if(parent::beforeSave($insert)) {
            $this->date = time();
            return true;
        } else {
            return false;
        }
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'target_id', 'user_ip', 'value'], 'required'],
            [['model_id', 'target_id', 'value', 'date'], 'integer'],
            [['user_ip'], 'string', 'max' => 16]
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
            'user_ip' => 'User Ip',
            'value' => 'Value',
            'date' => 'Date',
        ];
    }

    public static function matchingModels() {
        return [
            'story' => 0,
            'video' => 1,
            'film'  => 2,
            'audio' => 3,
        ];
    }

    public function getRating($model_name, $target_id) {
        $models = $this->matchingModels();
        if(in_array($model_name, $models)) {
            $model_id = $models[$model_name];
        } else {
            die('Нет таких сочетаний!');
        }

        $likes = Yii::$app->cache->get('likes'.$model_name.$target_id);
        if($likes === false) {
            $likes = $this->find()->where(["model_id"=>$model_id, "target_id"=>$target_id, "value"=>1])->count();
            Yii::$app->cache->set('likes'.$model_name.$target_id, $likes);
        }

        $dislikes = Yii::$app->cache->get('dislikes'.$model_name.$target_id);
        if($dislikes === false) {
            $dislikes = $this->find()->where(["model_id"=>$model_id, "target_id"=>$target_id, "value"=>0])->count();
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
            Yii::$app->cache->set('aggregate_rating'.$model_name.$target_id, $rating, 60*60); // кешируем на час
        }

        return ['likes'=>$likes, 'dislikes'=>$dislikes, 'aggregate_rating'=>$rating];
    }
}
