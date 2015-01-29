Vote for Yii2
======================

[![Latest Stable Version](https://poser.pugx.org/chiliec/yii2-vote/v/stable.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Total Downloads](https://poser.pugx.org/chiliec/yii2-vote/downloads.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Latest Unstable Version](https://poser.pugx.org/chiliec/yii2-vote/v/unstable.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![License](https://poser.pugx.org/chiliec/yii2-vote/license.svg)](https://packagist.org/packages/chiliec/yii2-vote)

Next steps will guide you through the process of installing yii2-vote using **composer**. Installation is a quick and easy three-step process.

Step 1: Install component via composer
------------------------------------

Run command

```
php composer.phar require --prefer-dist chiliec/yii2-vote "~1.3"
```

or add

```
"chiliec/yii2-vote": "~1.3"
```

to the require section of your `composer.json` file.


Step 2: Configuring your application
------------------------------------

Add following lines to your main configuration file:

Attention: this configuration works only with dev-master version. Configuration for last stable version [see here](https://github.com/Chiliec/yii2-vote/tree/1.4).

```php
'modules' => [
	'vote' => [
		'class' => 'chiliec\vote\Module',
		'allow_guests' => true, // if true will check IP, otherwise - UserID. Can be changed at any time
		'allow_change_vote' => true, // if true vote can be changed
		'matchingModels' => [ // matching model names with whatever unique integer ID
			'article' => 0, // may be just integer value
			'audio' => ['id'=>1], // or array with 'id' key
			'video' => ['id'=>2, 'allow_guests'=>true], // or with own value of 'allow_guests' for any models
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
	'separator' = '&nbsp;', // separator between like and dislike button
	'js_before_vote' => 'alert("before_vote")', // your javascript before vote
	'js_after_vote' => 'alert("after_vote")', // your javascript after vote
	'js_result' => '', // for overwrite js functional
]); ?>
```

Step 3: Updating database schema
--------------------------------

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/chiliec/yii2-vote/migrations
```

That's all! 



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
        // ...
        ];
    }
```


Enjoy and don't forget to send me your [Issues](https://github.com/Chiliec/yii2-vote/issues) and Pull Requests :)
