<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\widgets;

use chiliec\vote\models\Rating;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\web\JsExpression;
use yii\helpers\Json;
use Yii;

class Vote extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $voteUrl;

    /**
     * @var bool
     */
    public $showAggregateRating = true;

    /**
     * @var string
     */
    public $jsBeforeVote;

    /**
     * @var string
     */
    public $jsAfterVote;

    /**
     * @var string
     */
    public $jsCodeKey = 'vote';

    /**
     * @var string
     */
    public $jsErrorVote = "
        jQuery('#vote-response-' + model + '-' + target).html(errorThrown);
    ";

    /**
     * @var string
     */
    public $jsShowMessage = "
        jQuery('#vote-response-' + model + '-' + target).html(data.content);
    ";

    /**
     * @var string
     */
    public $jsChangeCounters = "
        if (typeof(data.success) !== 'undefined') {
            var idUp = '#vote-up-' + model + '-' + target;
            var idDown = '#vote-down-' + model + '-' + target;
            if (act === 'like') {
                jQuery(idUp).text(parseInt(jQuery(idUp).text()) + 1);
            } else {
                jQuery(idDown).text(parseInt(jQuery(idDown).text()) + 1);
            }
            if (typeof(data.changed) !== 'undefined') {
                if (act === 'like') {
                    jQuery(idDown).text(parseInt(jQuery(idDown).text()) - 1);
                } else {
                    jQuery(idUp).text(parseInt(jQuery(idUp).text()) - 1);
                }
            }
        }
    ";

    public function init()
    {
        parent::init();
        if (!isset($this->model)) {
            throw new InvalidParamException(Yii::t('vote', 'Model not configurated'));
        }

        if (!isset($this->voteUrl)) {
            $this->voteUrl = Yii::$app->getUrlManager()->createUrl(['vote/default/vote']);
        }

        $js = new JsExpression("
            function vote(model, target, act) {
                jQuery.ajax({ 
                    url: '$this->voteUrl', type: 'POST', dataType: 'json', cache: false,
                    data: { modelId: model, targetId: target, act: act },
                    beforeSend: function(jqXHR, settings) { $this->jsBeforeVote },
                    success: function(data, textStatus, jqXHR) { $this->jsChangeCounters $this->jsShowMessage },
                    complete: function(jqXHR, textStatus) { $this->jsAfterVote },
                    error: function(jqXHR, textStatus, errorThrown) { $this->jsErrorVote }
                });
            }
        ");
        $this->view->registerJs($js, View::POS_END, $this->jsCodeKey);
    }

    public function run()
    {
        return $this->render('vote', [
            'modelId' => Rating::getModelIdByName($this->model->className()),
            'targetId' => $this->model->{$this->model->primaryKey()[0]},
            'likes' => isset($this->model->aggregate->likes) ? $this->model->aggregate->likes : 0,
            'dislikes' => isset($this->model->aggregate->dislikes) ? $this->model->aggregate->dislikes : 0,
            'rating' => isset($this->model->aggregate->rating) ? $this->model->aggregate->rating : 0.0,
            'showAggregateRating' => $this->showAggregateRating,
        ]);
    }
}
