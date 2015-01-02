<?php
namespace chiliec\vote;

use yii\base\Widget;
use Yii;

/**
 * Class Display
 * @author Vladimir Babin <vovababin@gmail.com>
 */
class Display extends Widget {

    public function init(){
        parent::init();
        VoteAsset::register($this->view);
    }

    public function run(){
        return "Заготовка виджета";
    }
}