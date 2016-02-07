<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\behaviors;

use chiliec\vote\models\Rating;
use chiliec\vote\models\AggregateRating;
use yii\db\ActiveRecord;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;

class RatingBehavior extends Behavior
{
    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        if (!$owner instanceof ActiveRecord) {
            throw new InvalidConfigException(Yii::t('vote', 'Please attach this behavior to the instance of the ActiveRecord class'));
        }
        parent::attach($owner);
    }

    /**
     * @inheritdoc
     */
    public function getAggregate()
    {
        return $this->owner
            ->hasOne(AggregateRating::className(), [
                'target_id' => $this->owner->primaryKey()[0],
            ])
            ->onCondition([
                'model_id' => Rating::getModelIdByName($this->owner->className())
            ]);
    }
}
