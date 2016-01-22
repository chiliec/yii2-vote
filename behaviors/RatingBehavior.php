<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\behaviors;

use chiliec\vote\models\Rating;
use yii\db\ActiveRecord;
use yii\base\Behavior;

class RatingBehavior extends Behavior
{
    /**
     * @var string Name of model
     */
    public $modelName;

    /**
     * @var string Field for rating in database
     */
    public $ratingField = 'rating';

    /**
     * @var string Field for aggregate_rating in database
     */
    public $aggregateRatingField = 'aggregate_rating';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function afterFind($event)
    {
        if ($receivedRating = Rating::getRating($this->modelName, $this->owner->{$this->owner->primaryKey()[0]})) {
            $rating = $receivedRating['likes'] - $receivedRating['dislikes'];
            $aggregateRating = $receivedRating['rating'];
            if (($this->owner->{$this->ratingField} !== $rating) || ($this->owner->{$this->aggregateRatingField} !== $aggregateRating)) {
                \Yii::$app->db->createCommand()->update(
                    $this->owner->tableName(),
                    [$this->ratingField => $rating, $this->aggregateRatingField => $aggregateRating],
                    [$this->owner->primaryKey()[0] => $this->owner->{$this->owner->primaryKey()[0]}]
                )->execute();
            }
        }
    }
}
