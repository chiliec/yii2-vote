<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\actions;

use chiliec\vote\models\Rating;
use yii\base\Action;
use yii\web\MethodNotAllowedHttpException;
use yii\web\Response;
use Yii;

class VoteAction extends Action
{
    public function run()
    {
        if (Yii::$app->request->getIsAjax()) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            if (null === $model_name = Yii::$app->request->post('model_name')) {
                return ['content' => Yii::t('vote', 'Model name has not been sent')];
            }

            if (null === $target_id = Yii::$app->request->post('target_id')) {
                return ['content' => Yii::t('vote', 'The purpose is not defined')];
            }

            $act = Yii::$app->request->post('act');
            if (!in_array($act, ['like','dislike'], true)) {
                return ['content' => Yii::t('vote', 'Wrong action')];
            }
            $value = $act==='like' ? 1 : 0;

            $user_id = Yii::$app->user->getId();
            if ($user_id === null && !Rating::getIsAllowGuests($model_name)) {
                return ['content' => Yii::t('vote', 'Guests are not allowed to vote')];
            }

            if (!$user_ip = Rating::compressIp(Yii::$app->request->getUserIP())) {
                return ['content' => Yii::t('vote', 'The user is not recognized')];
            }

            $model_id = Rating::getModelIdByName($model_name);
            if (!is_int($model_id)) {
                return ['content' => Yii::t('vote', 'The model is not registered')];
            }

            if (Rating::getIsAllowGuests($model_name)) {
                $isVoted = Rating::findOne(['model_id'=>$model_id, 'target_id'=>$target_id, 'user_ip'=>$user_ip]);
            } else {
                $isVoted = Rating::findOne(['model_id'=>$model_id, 'target_id'=>$target_id, 'user_id'=>$user_id]);
            }
            if (is_null($isVoted)) {
                $newVote = new Rating;
                $newVote->model_id = $model_id;
                $newVote->target_id = $target_id;
                $newVote->user_id = $user_id;
                $newVote->user_ip = $user_ip;
                $newVote->value = $value;
                if ($newVote->save()) {
                    Yii::$app->cache->delete('rating'.$model_name.$target_id);
                    if ($value === 1) {
                        return ['content' => Yii::t('vote', 'Your vote is accepted. Thanks!'), 'success' => true];
                    } else {
                        return ['content' => Yii::t('vote', 'Thanks for your opinion'), 'success' => true];
                    }
                } else {
                    return ['content' => Yii::t('vote', 'Validation error')];
                }
            } else {
                if ($isVoted->value !== $value && Rating::getIsAllowChangeVote($model_name)) {
                    $isVoted->value = $value;
                    if ($isVoted->save()) {
                        return ['content' => Yii::t('vote', 'Your vote has been changed. Thanks!'), 'success' => true, 'changed' => true];
                    } else {
                        return ['content' => Yii::t('vote', 'Validation error')];
                    }
                }
                return ['content' => Yii::t('vote', 'You have already voted!')];
            }
        } else {
            throw new MethodNotAllowedHttpException(Yii::t('vote', 'Forbidden method'), 405);
        }
    }

}
