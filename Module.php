<?php

namespace chiliec\vote;

use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'chiliec\vote\controllers';

    /**
     * Разешить голосовать гостям?
     * @var bool
     */
    public $allow_guests = true;

    /**
     * Сопоставление моделей с их id
     * @var array
     */
    public $matchingModels;

    public function init()
    {
        parent::init();
        if(!isset($this->matchingModels)) {
            throw new InvalidConfigException('matchingModels not configurated');
        }

    }
}
