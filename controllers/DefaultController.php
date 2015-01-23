<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\controllers;

use chiliec\vote\actions\VoteAction;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public $defaultAction = 'vote';

    public function actions()
    {
        return [
            'vote' => [
                'class' => VoteAction::className(),
            ]
        ];
    }
}
