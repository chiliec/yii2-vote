# Extended description for yii2-vote

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
