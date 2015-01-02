<?php

namespace chiliec\vote\controllers;

use Yii;
use chiliec\vote\models\Rating;
use yii\web\MethodNotAllowedHttpException;

class DefaultController extends \yii\web\Controller
{
    public function actionIndex()
    {
        if(Yii::$app->request->getIsAjax()) { //todo: дополнительно проверить что запрос с нащего домена (или проверить токен)
            $user_ip = Yii::$app->request->getUserIP();
            $model_name = Yii::$app->request->getQueryParam('model_name');
            $target_id = Yii::$app->request->getQueryParam('target_id');
            $act = Yii::$app->request->getQueryParam('act');

            if($user_ip==null) {
                return 'IP-адрес не распознан';
            }

            $models = Rating::matchingModels();
            if(in_array($model_name, $models)) {
                $model_id = $models[$model_name];
            } else {
                return 'Модель не зарегистрирована!';
            }

            if($target_id==null) {
                return 'Цель не определена';
            }

            if($act=='like'){
                $act = 1;
            } elseif($act=='dislike') {
                $act = 0;
            } else {
                return 'Неправильное действие!';
            }

            $isVoted = Rating::findOne(["model_id"=>$model_id, "target_id"=>$target_id, "user_ip"=>$user_ip]);

            if(is_null($isVoted)) {
                $newVote = new Rating;
                $newVote->model_id = $model_id;
                $newVote->target_id = $target_id;
                $newVote->user_ip = $user_ip;
                $newVote->value = $act;
                if($newVote->save()) {
                    if($act==1) {
                        Yii::$app->cache->delete('likes'.$model_name.$target_id);
                        return 'Голос принят. Не забудьте поделиться с друзьями!';
                    } else {
                        Yii::$app->cache->delete('dislikes'.$model_name.$target_id);
                        return 'Вы правы, действительно паршивая история...';
                    }
                } else {
                    return 'Ошибка валидации!';
                }
            } else {
                return 'Вы уже голосовали!';
            }
        } else {
            throw new MethodNotAllowedHttpException('Попытка обмана', 405);
        }
    }
}
