<?php

use Codeception\Test\Unit;
use yii\console\controllers\MigrateController;
use chiliec\vote\models\Rating;

class BehaviorTest extends Unit
{
    protected function _before()
    {
        require_once __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

        \Yii::setAlias('@tests', __DIR__ . '/..');

        $config = require __DIR__ . '/_config.php';
        $app = new \yii\console\Application($config);

        $migration = new MigrateController('migrate', $app);
        $migration->runAction('up', ['migrationPath' => '@tests/../migrations', 'interactive' => false]);
    }

    public function testBehaviorIsWorks()
    {
        $model = new \tests\unit\mocks\FakeModel();
        $this->assertEquals($model::className(), 'tests\unit\mocks\FakeModel');
        $this->assertEquals(Rating::getModelIdByName($model->className()), 255);
        $this->assertEquals($model->aggregate, null);

        $model->id = 1;
        $model->save();

        Rating::updateRating(Rating::getModelIdByName($model->className()), $model->id);

        $newModel = $model::findOne($model->id);
        $this->assertEquals($newModel->aggregate->likes, 0);
        $this->assertEquals($newModel->aggregate->dislikes, 0);
        $this->assertEquals($newModel->aggregate->rating, 0.0);
    }
}
