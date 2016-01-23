<?php
use chiliec\vote\models\Rating;

class RatingModelTest extends \yii\codeception\TestCase
{
    public $appConfig = '@tests/unit/_config.php';

    public $firstModelId = 255;
    public $secondModelId = 256;

    public $firstModelName = 'common\models\Story';
    public $secondModelName = 'common\models\Trololo';

    public function testGetModelIdByName()
    {
    	$firstModelId = Rating::getModelIdByName($this->firstModelName);
    	$this->assertEquals($firstModelId, $this->firstModelId);

    	$secondModelId = Rating::getModelIdByName($this->secondModelName);
    	$this->assertEquals($secondModelId, $this->secondModelId);

    }

    public function testGetModelNameById()
    {
    	$firstModelName = Rating::getModelNameById($this->firstModelId);
    	$this->assertEquals($firstModelName, $this->firstModelName);

    	$secondModelName = Rating::getModelNameById($this->secondModelId);
    	$this->assertEquals($secondModelName, $this->secondModelName);
    }

    public function testGetIsAllowGuests()
    {
    	$firstIsAllow = Rating::getIsAllowGuests($this->firstModelId);
    	$this->assertEquals($firstIsAllow, true);

    	$secondIsAllow = Rating::getIsAllowGuests($this->secondModelId);
    	$this->assertEquals($secondIsAllow, false);
    }

    public function testGetIsAllowChangeVote()
    {
    	$firstIsAllow = Rating::getIsAllowChangeVote($this->firstModelId);
    	$this->assertEquals($firstIsAllow, true);

    	$secondIsAllow = Rating::getIsAllowChangeVote($this->secondModelId);
    	$this->assertEquals($secondIsAllow, false);
    }
}
