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
    public $model_name;

    /**
     * @var string Field for rating in database
     */
    public $rating_field = 'rating';

    /**
     * @var string Field for aggregate_rating in database
     */
    public $aggregate_rating_field = 'aggregate_rating';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function afterFind($event)
    {
        if ($received_rating = Rating::getRating($this->model_name, $this->owner->{$this->owner->primaryKey()[0]})) {
            $rating = $received_rating['likes'] - $received_rating['dislikes'];
            $aggregate_rating = $received_rating['aggregate_rating'];
            if (($this->owner->{$this->rating_field} != $rating) or ($this->owner->{$this->aggregate_rating_field} != $aggregate_rating)) {
                \Yii::$app->db->createCommand()->update(
                    $this->owner->tableName(),
                    [$this->rating_field => $rating, $this->aggregate_rating_field => $aggregate_rating],
                    [$this->owner->primaryKey()[0] => $this->owner->{$this->owner->primaryKey()[0]}]
                )->execute();
            }
        }
    }
}
