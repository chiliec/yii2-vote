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
     * @inheritdoc
     */
    protected function rating()
    {
        $modelId = Rating::getModelIdByName($this->owner->className());
        $targetId = $this->owner->{$this->owner->primaryKey()[0]};
        return Rating::getRating($modelId, $targetId);
    }

    /**
     * @inheritdoc
     */
    public function getLikes()
    {
        return $this->rating()['likes'];
    }

    /**
     * @inheritdoc
     */
    public function getDislikes()
    {
        return $this->rating()['dislikes'];
    }

    /**
     * @inheritdoc
     */
    public function getRating()
    {
        return $this->rating()['rating'];
    }
}
