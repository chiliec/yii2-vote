<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\components;

use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use chiliec\vote\behaviors\RatingBehavior;
use chiliec\vote\models\Rating;
 
class VoteBootstrap implements BootstrapInterface
{
    /**
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
		$models = Yii::$app->getModule('vote')->models;
		foreach ($models as $value) {
			$modelId = Rating::getModelIdByName($value);
			$modelName = Rating::getModelNameById($modelId);
			Event::on($modelName::className(), $modelName::EVENT_INIT, function ($event) {
			    if (null === $event->sender->getBehavior('rating')) {
					$event->sender->attachBehavior('rating', [
						'class' => RatingBehavior::className(),
					]);
				}
			});
		}
    }
}
