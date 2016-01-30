<?php

use yii\console\controllers;

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
		$name = $model::className();
		$this->assertEquals($name, 'tests\unit\mocks\FakeModel');
		
		$this->assertEquals(count($model->likes), 0);
		$this->assertEquals(count($model->dislikes), 0);
		$this->assertEquals($model->likesCount, 0);
		$this->assertEquals($model->dislikesCount, 0);
		$this->assertEquals($model->rating, 0);
	}

}
