<?php
return [
    'id' => 'vote-test-app',
    'class' => 'yii\console\Application',
    'basePath' => \Yii::getAlias('@tests'),
    'runtimePath' => \Yii::getAlias('@tests/_output'),
    'bootstrap' => [],
    'components' => [
        'db' => [
            'class' => '\yii\db\Connection',
            'dsn' => 'sqlite:'.\Yii::getAlias('@tests/_output/temp.db'),
            'username' => '',
            'password' => '',
        ],
        'cache' => [
            'class' => 'yii\caching\DummyCache',
        ],
    ],
    'modules' => [
        'vote' => [
            'class' => 'chiliec\vote\Module',
            'allowGuests' => true,
            'allowChangeVote' => true,
            'models' => [
                255 => 'common\models\Story',
                256 => [
                    'modelName' => 'common\models\Trololo', 
                    'allowGuests' => false,
                    'allowChangeVote' => false,
                ]
            ],      
        ],
    ],
];
