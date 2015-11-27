<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote;

use chiliec\vote\models\Rating;
use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use Yii;

class Display extends Widget
{
    /**
     * @var string
     */
    public $model_name;

    /**
     * @var int
     */
    public $target_id;

    /**
     * @var string
     */
    public $vote_url;

    /**
     * @var bool
     */
    public $view_aggregate_rating = true;

    /**
     * @var array
     */
    public $mainDivOptions = ['class' => 'text-center'];

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
    public $js_before_vote;

    /**
     * @var string
     */
    public $js_after_vote;
    /**
     * @var string
     */
    public $js_code_key='vote';
    /**
     * @var string
     */
    public $js_error_vote = "
            jQuery('#vote-response-'+model+target).html(errorThrown);
    ";

    /**
     * @var string
     */
    public $js_show_message = "
            jQuery('#vote-response-'+model+target).html(data.content);
    ";

    /**
     * @var string
     */
    public $js_change_counters = "
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
        if (!isset($this->model_name) or !isset($this->target_id)) {
            throw new InvalidParamException(Yii::t('vote', 'model_name or target_id not configurated'));
        }

        if (!isset($this->vote_url)) {
            $this->vote_url = Yii::$app->getUrlManager()->createUrl(['vote/default/vote']);
        }

        $js = "
function vote(model, target, act){
    jQuery.ajax({ url: '$this->vote_url', type: 'POST', dataType: 'json', cache: false,
        data: { model_name: model, target_id: target, act: act},
        beforeSend: function(jqXHR, settings) { $this->js_before_vote },
        success: function(data, textStatus, jqXHR) { $this->js_change_counters $this->js_show_message },
        complete: function(jqXHR, textStatus) { $this->js_after_vote },
        error: function(jqXHR, textStatus, errorThrown) { $this->js_error_vote }
    });
}";
        $this->view->registerJs($js, View::POS_END, $this->js_code_key);
    }

    public function run()
    {
        $target_id = $this->target_id;
        $model_name = $this->model_name;
        $rating = Rating::getRating($this->model_name, $this->target_id);
        $id = $model_name . $target_id;
        $content  = Html::beginTag('div', $this->mainDivOptions);
        $content .= Html::tag('span', $rating['likes'], ['id'=>"vote-up-$id", 'class' => $this->classLike, 'onclick' => "vote('$model_name',$target_id,'like');return false;", 'style' => 'cursor:pointer;']);
        $content .= $this->separator;
        $content .= Html::tag('span', $rating['dislikes'], ['id'=>"vote-down-$id", 'class' => $this->classDislike, 'onclick' => "vote('$model_name',$target_id,'dislike');return false;", 'style' => 'cursor:pointer;']);
        $content .= Html::tag('div', $this->view_aggregate_rating ? Yii::t('vote', 'Aggregate rating').': '.$rating['aggregate_rating'] : '', ['id' => "vote-response-$id"]);
        $content .= Html::endTag('div');
        return $content;
    }
}
