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
use Yii;

class Vote extends Widget
{
    /**
     * @var string
     */
    public $modelName;

    /**
     * @var int
     */
    public $targetId;

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
    public $classGeneral = 'text-center';

    /**
     * @var string
     */
    public $classLike = 'glyphicon glyphicon-thumbs-up';

    /**
     * @var string
     */
    public $classDislike = 'glyphicon glyphicon-thumbs-down';

    /**
     * @var string
     */
    public $separator = '&nbsp;';

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
        jQuery('#vote-response-'+model+target).html(errorThrown);
    ";

    /**
     * @var string
     */
    public $jsShowMessage = "
        jQuery('#vote-response-'+model+target).html(data.content);
    ";

    /**
     * @var string
     */
    public $jsChangeCounters = "
        if (typeof(data.success) !== 'undefined') {
            if (act === 'like') {
                jQuery('#vote-up-'+model+target).text(parseInt(jQuery('#vote-up-'+model+target).text()) + 1);
            } else {
                jQuery('#vote-down-'+model+target).text(parseInt(jQuery('#vote-down-'+model+target).text()) + 1);
            }
            if (typeof(data.changed) !== 'undefined') {
                if (act === 'like') {
                    jQuery('#vote-down-'+model+target).text(parseInt(jQuery('#vote-down-'+model+target).text()) - 1);
                } else {
                    jQuery('#vote-up-'+model+target).text(parseInt(jQuery('#vote-up-'+model+target).text()) - 1);
                }
            }
        }
    ";

    public function init()
    {
        parent::init();
        if (!isset($this->modelName) || !isset($this->targetId)) {
            throw new InvalidParamException(Yii::t('vote', 'modelName or targetId not configurated'));
        }

        if (!isset($this->voteUrl)) {
            $this->voteUrl = Yii::$app->getUrlManager()->createUrl(['vote/default/vote']);
        }

        $js = "
            function vote(model, target, act) {
                jQuery.ajax({ 
                    url: '$this->voteUrl', type: 'POST', dataType: 'json', cache: false,
                    data: { modelName: model, targetId: target, act: act },
                    beforeSend: function(jqXHR, settings) { $this->jsBeforeVote },
                    success: function(data, textStatus, jqXHR) { $this->jsChangeCounters $this->jsShowMessage },
                    complete: function(jqXHR, textStatus) { $this->jsAfterVote },
                    error: function(jqXHR, textStatus, errorThrown) { $this->jsErrorVote }
                });
            }
        ";
        $this->view->registerJs($js, View::POS_END, $this->jsCodeKey);
    }

    public function run()
    {
        $rating = Rating::getRating($this->modelName, $this->targetId);
        $id = $this->modelName . $this->targetId;
        $content  = Html::beginTag('div', ['class' => $this->classGeneral]);
        $content .= Html::tag('span', $rating['likes'], ['id' => "vote-up-$id", 'class' => $this->classLike, 'onclick' => "vote('$this->modelName', $this->targetId, 'like'); return false;", 'style' => 'cursor: pointer;']);
        $content .= $this->separator;
        $content .= Html::tag('span', $rating['dislikes'], ['id' => "vote-down-$id", 'class' => $this->classDislike, 'onclick' => "vote('$this->modelName', $this->targetId, 'dislike'); return false;", 'style' => 'cursor: pointer;']);
        $content .= Html::tag('div', $this->showAggregateRating ? Yii::t('vote', 'Aggregate rating') . ': ' . $rating['rating'] : '', ['id' => "vote-response-$id"]);
        $content .= Html::endTag('div');
        return $content;
    }
}
