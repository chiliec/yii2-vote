<?php
namespace chiliec\vote;

use yii\base\InvalidParamException;
use yii\base\Widget;
use Yii;

/**
 * Class Display
 * @author Vladimir Babin <vovababin@gmail.com>
 */
class Display extends Widget {

    /**
     * @var string
     */
    public $model_name;

    /**
     * @var int
     */
    public $target_id;

    public function init(){
        parent::init();
        if(!isset($this->model_name) or !isset($this->target_id))
            throw new InvalidParamException('model_name or target_id not configurated');
        VoteAsset::register($this->view);
    }

    public function run(){
        $rating = new \chiliec\vote\models\Rating();
        return $this->render('display',[
            'target_id' => $this->target_id,
            'model_name' => $this->model_name,
            'rating' => $rating->getRating($this->model_name, $this->target_id),
        ]);


    }
}