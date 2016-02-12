<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote\widgets;

use Yii;
use chiliec\vote\models\Rating;
use chiliec\vote\models\AggregateRating;
use yii\base\Widget;
use yii\helpers\Url;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;

class TopRated extends Widget
{
    /**
     * @var string
     */
    public $modelName;

    /**
     * @var string
     */
    public $title = 'Top rated models';

    /**
     * @var string
     */
    public $path = 'site/view';

    /**
     * @var integer
     */
    public $limit = 10;

    /**
     * @var string
     */
    public $titleField = 'title';

    public function init()
    {
        parent::init();
        if ($this->modelName === null) {
        	throw new InvalidParamException(Yii::t('vote', 'modelName is not defined'));
        }
    }

    public function run()
    {
    	$modelName = $this->modelName;
        $ratingArray = AggregateRating::find()
        ->select('target_id, rating')
        ->where('model_id = :modelId', [
            'modelId' => Rating::getModelIdByName($modelName),
        ])
        ->orderBy('rating DESC')
        ->limit($this->limit)
        ->asArray()
        ->all();
        $ids = ArrayHelper::getColumn($ratingArray, 'target_id');
        $models = $modelName::find()
            ->joinWith('aggregate')
            ->where(['in', $modelName::tableName() . '.' . $modelName::primaryKey()[0], $ids])
            ->orderBy('rating DESC')
            ->all();
        return $this->render('topRated', [
            'models' => $models,
            'title' => $this->title,
            'titleField' => $this->titleField,
            'path' => $this->path,
        ]);
     }
}
