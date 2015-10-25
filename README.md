Vote for Yii2
======================

[![Latest Stable Version](https://poser.pugx.org/chiliec/yii2-vote/v/stable.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Total Downloads](https://poser.pugx.org/chiliec/yii2-vote/downloads.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Latest Unstable Version](https://poser.pugx.org/chiliec/yii2-vote/v/unstable.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![License](https://poser.pugx.org/chiliec/yii2-vote/license.svg)](https://packagist.org/packages/chiliec/yii2-vote)

Next steps will guide you through the process of installing yii2-vote using **composer**. Installation is a quick and easy three-step process.

Step 1: Install component via composer
------------------------------------

Run command

```
php composer.phar require --prefer-dist chiliec/yii2-vote "~2.0"
```

or add

```
"chiliec/yii2-vote": "~2.0"
```

to the require section of your `composer.json` file.


Step 2: Configuring your application
------------------------------------

Add following lines to your main configuration file:

```php
'modules' => [
	'vote' => [
		'class' => 'chiliec\vote\Module',
		'allow_guests' => true, // if true will check IP, otherwise - UserID
		'allow_change_vote' => true, // if true vote can be changed
		'matchingModels' => [ // matching model names with whatever unique integer ID
			'article' => 0, // may be just integer value
			'audio' => ['id'=>1], // or array with 'id' key
			'video' => ['id'=>2, 'allow_guests'=>false], // own value 'allow_guests'
			'photo' => ['id'=>3, 'allow_guests'=>false, 'allow_change_vote'=>false],
		],		
	],
],
```

And add widget in view:

```php
<?php echo \chiliec\vote\Display::widget([
	'model_name' => 'article', // name of current model
	'target_id' => $model->id, // id of current element
	// optional fields
	'view_aggregate_rating' => true, // set true to show aggregate_rating
	'mainDivOptions' => ['class' => 'text-center'], // div options
	'classLike' => 'glyphicon glyphicon-thumbs-up', // class for like button
	'classDislike' => 'glyphicon glyphicon-thumbs-down', // class for dislike button
	'separator' => '&nbsp;', // separator between like and dislike button
]); ?>
```

Step 3: Updating database schema
--------------------------------

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/chiliec/yii2-vote/migrations
```

That's all! 

-------------------------------

How to store rating in database
-------------------------------
Sometimes you need to store rating in the same table (for example, for sorting). 
At first, create new fields `rating` and `aggregate_rating` inside target table: 

```sql
ALTER TABLE `YOUR_TARGET_TABLE_NAME` ADD (
  `rating` smallint(6) NOT NULL,
  `aggregate_rating` float(3,2) unsigned NOT NULL
)
```

After that, add new behavior in target model:

```php
    public function behaviors() {
        return [
            [
                'class' => \chiliec\vote\behaviors\RatingBehavior::className(),
                'model_name' => 'story', // name of this model
            ],
        ];
    }
```

Customizing JS-events
--------------------------
If you want to customize JS-events, you can rewrite widget properties:

* `js_before_vote` by default is not defined. Called before vote.
* `js_change_counters` responsible for change counters. Available `data` property (may contains `content`, `success` and `changed` properties).
* `js_show_message` responsible for show message. Available `data` property too.
* `js_after_vote` by default is not defined. Called after vote.
* `js_error_vote` called if the request fails. Available `errorThrown`, contains error message.

For example, if you want to use [noty jQuery plugin](https://github.com/needim/noty) for show notifications, you may rewrite `js_show_message`:

```php
<?php echo \chiliec\vote\Display::widget([
	'model_name' => 'article',
	'target_id' => $model->id,
	'js_show_message' => "
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

-------------------------------

Enjoy and don't forget to send me your [Issues](https://github.com/Chiliec/yii2-vote/issues) and Pull Requests :)
