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
    public $jsPopOverErrorVote = "
        $('#vote-' + model + '-' + target).popover({
            content: function() {
               return errorThrown;
            }
        }).popover('show');
    ";

    /**
     * @var string
     */
    public $jsErrorVote = "
        jQuery('#vote-response-' + model + '-' + target).html(errorThrown);
    ";

    /**
     * @var string
     */
    public $jsPopOverShowMessage = "
        $('#vote-' + model + '-' + target).popover({
            html : true,
            trigger: 'manual',
            content: function() {
               return data.content;
            }
        }).popover('show');
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
    public $jsChangeCounters = <<<JS
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
JS;

    /**
     * @var string
     */
    public $jsPopOver = <<<JS
        $('body').on('click', function (e) {
            $('[data-toggle="popover"]').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });

        var timer;
        $('[data-toggle="popover"]').click(function (e) {
            clearTimeout(timer);
            timer = setTimeout(function () {
                    $('#'+e.currentTarget.id).popover('hide');
            }, 3000);
        });
JS;

    public function init()
    {
        parent::init();
        if (!isset($this->model)) {
            throw new InvalidParamException(Yii::t('vote', 'Model not configurated'));
        }

        if (!isset($this->voteUrl)) {
            $this->voteUrl = Yii::$app->getUrlManager()->createUrl(['vote/default/vote']);
        }

        $showMessage = $this->jsShowMessage;
        $errorMessage = $this->jsErrorVote;
        if (Yii::$app->getModule('vote')->popOverEnabled) {
            $js2 = new JsExpression($this->jsPopOver);
            $this->view->registerJs($js2, View::POS_END);
            $showMessage = $this->jsPopOverShowMessage;
            $errorMessage = $this->jsPopOverErrorVote;
        }

        $js = new JsExpression("
            function vote(model, target, act) {
                jQuery.ajax({ 
                    url: '$this->voteUrl', type: 'POST', dataType: 'json', cache: false,
                    data: { modelId: model, targetId: target, act: act },
                    beforeSend: function(jqXHR, settings) { $this->jsBeforeVote },
                    success: function(result, textStatus, jqXHR) { 
                        data = result;
                        $this->jsChangeCounters
                        $showMessage
                    },
                    complete: function(jqXHR, textStatus) {
                        $this->jsAfterVote
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        errorMessage
                    }
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
