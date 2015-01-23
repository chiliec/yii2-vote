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

    public function init()
    {
        parent::init();
        if(!isset($this->model_name) or !isset($this->target_id)) {
            throw new InvalidParamException('model_name or target_id not configurated');
        }
        VoteAsset::register($this->view);
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
