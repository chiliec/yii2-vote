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
    public $js_result = "
            if(typeof(data.success)!=='undefined') {
                if(act==='like') {
                    jQuery('#vote-up-'+model+target).text(parseInt(jQuery('#vote-up-'+model+target).text()) + 1);
                } else {
                    jQuery('#vote-down-'+model+target).text(parseInt(jQuery('#vote-down-'+model+target).text()) + 1);
                }
                if(typeof(data.changed)!=='undefined') {
                    if(act==='like') {
                        jQuery('#vote-down-'+model+target).text(parseInt(jQuery('#vote-down-'+model+target).text()) - 1);
                    } else {
                        jQuery('#vote-up-'+model+target).text(parseInt(jQuery('#vote-up-'+model+target).text()) - 1);
                    }
                }
            }
            jQuery('#vote-response-'+model+target).html(data.content);
    ";

    public function init()
    {
        parent::init();
        if(!isset($this->model_name) or !isset($this->target_id)) {
            throw new InvalidParamException(Yii::t('vote', 'model_name or target_id not configurated'));
        }

        if(!isset($this->vote_url)) {
            $this->vote_url = Yii::$app->getUrlManager()->createUrl(['vote']);
        }

        $js = "
function vote(model, target, act){
    jQuery.ajax({ url: '$this->vote_url', type: 'POST', dataType: 'json', cache: false,
        data: { model_name: model, target_id: target, act: act},
        beforeSend: function(jqXHR, settings) { $this->js_before_vote },
        complete: function(jqXHR, textStatus) { $this->js_after_vote },
        success: function(data, textStatus, jqXHR) { $this->js_result }
    });
}";
        $this->view->registerJs($js, View::POS_END);
    }

    public function run()
    {
        $rating = new Rating();
        return $this->render('display',[
            'target_id' => $this->target_id,
            'model_name' => $this->model_name,
            'rating' => $rating->getRating($this->model_name, $this->target_id),
        ]);
    }
}
