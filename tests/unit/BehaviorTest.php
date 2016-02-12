<?php

use yii\console\controllers;
use chiliec\vote\models\Rating;

class BehaviorTest extends \yii\codeception\TestCase
{
	public $appConfig = '@tests/unit/_config.php';

	protected function setUp()
    {
        $app = $this->mockApplication($this->appConfig);
        $migration = new Controllers\MigrateController('migrate', $app);
        $migration->run('up', ['migrationPath' => '@tests/../migrations', 'interactive' => false]);
        $this->unloadFixtures();
        $this->loadFixtures();
    }

	public function testBehaivorIsWorks()
	{
		$model = new \tests\unit\mocks\FakeModel;
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
