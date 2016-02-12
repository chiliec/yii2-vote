<?php
use chiliec\vote\models\Rating;

class RatingModelTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/unit/_config.php';

    public $firstModelId = 255;
    public $secondModelId = 256;
    public $thirdModelId = 257;

    public $firstModelName = 'tests\unit\mocks\FakeModel';
    public $secondModelName = 'tests\unit\mocks\FakeModel2';
    public $thirdModelName = 'tests\unit\mocks\FakeModel3';
    
    public function testGetModelIdByName()
    {
    	$firstModelId = Rating::getModelIdByName($this->firstModelName);
    	$this->assertEquals($firstModelId, $this->firstModelId);

    	$secondModelId = Rating::getModelIdByName($this->secondModelName);
    	$this->assertEquals($secondModelId, $this->secondModelId);

        $thirdModelId = Rating::getModelIdByName($this->thirdModelName);
        $this->assertEquals($thirdModelId, $this->thirdModelId);

    }

    public function testGetModelNameById()
    {
    	$firstModelName = Rating::getModelNameById($this->firstModelId);
    	$this->assertEquals($firstModelName, $this->firstModelName);

    	$secondModelName = Rating::getModelNameById($this->secondModelId);
    	$this->assertEquals($secondModelName, $this->secondModelName);

        $thirdModelName = Rating::getModelNameById($this->thirdModelId);
        $this->assertEquals($thirdModelName, $this->thirdModelName);
    }

    public function testGetIsAllowGuests()
    {
    	$firstIsAllow = Rating::getIsAllowGuests($this->firstModelId);
    	$this->assertEquals($firstIsAllow, true);

    	$secondIsAllow = Rating::getIsAllowGuests($this->secondModelId);
    	$this->assertEquals($secondIsAllow, true);

        $thirdIsAllow = Rating::getIsAllowGuests($this->thirdModelId);
        $this->assertEquals($thirdIsAllow, false);
    }

    public function testGetIsAllowChangeVote()
    {
    	$firstIsAllow = Rating::getIsAllowChangeVote($this->firstModelId);
    	$this->assertEquals($firstIsAllow, true);

    	$secondIsAllow = Rating::getIsAllowChangeVote($this->secondModelId);
    	$this->assertEquals($secondIsAllow, true);

        $thirdIsAllow = Rating::getIsAllowChangeVote($this->thirdModelId);
        $this->assertEquals($thirdIsAllow, false);
    }
}
