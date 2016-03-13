# Extended description for yii2-vote

## Migration from 2.* to 3.0

If you using `RatingBehavior` in models, after update you must drop `rating` and `aggregate_rating` columns in table:

```sql
ALTER TABLE `YOUR_TARGET_TABLE_NAME` 
	DROP `rating`, 
	DROP `aggregate_rating`;
```

and delete corresponding properties from model.

## Manually add behavior in models

Behavior to models autoincluded by bootstrap in main app configuration:

```php
'bootstrap' => [
    'chiliec\vote\components\VoteBootstrap',
],
```

If it doesn't suit you, you can add behavior in model manually:

```php
    public function behaviors() {
        return [
            'rating' => [
                'class' => \chiliec\vote\behaviors\RatingBehavior::className(),
            ],
        ];
    }
```

but in this case you also need to update rating in after find model event:

```php
use chiliec\vote\models\Rating;

public function afterFind() {
	parent::afterFind();
	$modelId = Rating::getModelIdByName($this->className());
	$targetId = $this->{$this->primaryKey()[0]};
	Rating::updateRating($modelId, $targetId);
}
```

## Sorting by rating in data provider

Go to your Search Model and add `->joinWith('aggregate')` to Query to prevent large number of database queries. After that, add new sort attribute to dataProvider configuration, like this:

```php
$dataProvider->sort->attributes[] = [
    'asc' => [AggregateRating::tableName() . '.rating' => SORT_ASC],
    'desc' => [AggregateRating::tableName() . '.rating' => SORT_DESC],
    'label' => 'By rating',
];
```

Your search model class may look like this:

```php
use chiliec\vote\models\AggregateRating;

/**
 * MySuperModelSearch represents the model behind the search form about `common\models\MySuperModelSearch`.
 */
class MySuperModelSearch extends MySuperModel
{
	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
	    $query = MySuperModel::find()->joinWith('aggregate');

	    $dataProvider = new ActiveDataProvider([
	        'query' => $query,
	    ]);

	    $dataProvider->sort->attributes[] = [
	        'asc' => [AggregateRating::tableName() . '.rating' => SORT_ASC],
	        'desc' => [AggregateRating::tableName() . '.rating' => SORT_DESC],
	        'label' => 'By rating',
	    ];

	    $this->load($params);

	    if (!$this->validate()) {
	        // uncomment the following line if you do not want to return any records when validation fails
	        // $query->where('0=1');
	        return $dataProvider;
	    }

	    return $dataProvider;
	}
}
```

## Overriding views

If you want to override views of widgets, you can rewrite path to your own path in view application component. For example:

```php
'components' => [
    'view' => [
        'theme' => [
            'pathMap' => [
                '@chiliec/vote/widgets/views' => '@app/views/vote'
            ],
        ],
    ],
],
```

After that, copy files from `/vendor/chiliec/yii2-vote/widgets/views/` to `views/vote/` and edit it how you want.

## Customizing JS-events

If you want to customize JS-events, you can rewrite widget properties:

* `jsBeforeVote` by default is not defined. Called before vote.
* `jsChangeCounters` responsible for change counters. Available `data` property (may contains `content`, `success` and `changed` properties).
* `jsShowMessage` responsible for show message. Available `data` property too.
* `jsAfterVote` by default is not defined. Called after vote.
* `jsErrorVote` called if the request fails. Available `errorThrown`, contains error message.

For example, if you want to use [noty jQuery plugin](https://github.com/needim/noty) for show notifications, you may rewrite `jsShowMessage`:

```php
<?php echo \chiliec\vote\widgets\Vote::widget([
    'model' => $model,
	'jsShowMessage' => "
		message = data.content;
		type = 'error';
		if (typeof(data.success) !== 'undefined') { type = 'success'; }
		if (typeof(data.changed) !== 'undefined') { type = 'information'; }
		noty({
			text: message,
			type: type,
			layout: 'topRight',
			timeout: 1500,
			force: true
		});
	",
]);
```

## Rich snippet in search engines

![Aggregate rating in google rich snippet](https://raw.githubusercontent.com/Chiliec/yii2-vote/master/docs/AggregateRatingRS.png)

We already marking up aggregate rating information in `Vote widget` with `Schema.org` format. For use it, you should wrap around widget your item type of content, [compatible with review type](https://schema.org/review). For example:

```php
<span itemscope itemtype="http://schema.org/CreativeWork">
    <?= \chiliec\vote\widgets\Vote::widget(['model' => $model]); ?>
</span>
```
or
```php
<span itemscope itemtype="http://schema.org/Product">
	<span itemprop="name"><?= $model->title; ?></span>
    <?= \chiliec\vote\widgets\Vote::widget(['model' => $model]); ?>
</span>
```

For more information about marking up rating, see [Enabling Rich Snippets for Reviews and Ratings](https://developers.google.com/structured-data/rich-snippets/reviews) in Google help. For testing, you can use [Structured Data Testing Tool](https://developers.google.com/structured-data/testing-tool/).

