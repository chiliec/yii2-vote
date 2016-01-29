# Vote for Yii2

[![Latest Stable Version](https://poser.pugx.org/chiliec/yii2-vote/v/stable.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Total Downloads](https://poser.pugx.org/chiliec/yii2-vote/downloads.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Build Status](https://travis-ci.org/Chiliec/yii2-vote.svg?branch=master)](https://travis-ci.org/Chiliec/yii2-vote) [![Test Coverage](https://codeclimate.com/github/Chiliec/yii2-vote/badges/coverage.svg)](https://codeclimate.com/github/Chiliec/yii2-vote/coverage) [![Code Climate](https://codeclimate.com/github/Chiliec/yii2-vote/badges/gpa.svg)](https://codeclimate.com/github/Chiliec/yii2-vote) [![License](https://poser.pugx.org/chiliec/yii2-vote/license.svg)](https://packagist.org/packages/chiliec/yii2-vote)

Next steps will guide you through the process of installing yii2-vote using **composer**. Installation is a quick and easy three-step process.

## Step 1: Install component via composer

Run command

```
php composer.phar require --prefer-dist chiliec/yii2-vote "~2.0"
```

or add

```
"chiliec/yii2-vote": "~2.0"
```

to the require section of your `composer.json` file.


## Step 2: Configuring your application

Add following lines to your main configuration file:

```php
'modules' => [
    'vote' => [
        'class' => 'chiliec\vote\Module',
        // global values for all models
        // 'allowGuests' => true,
        // 'allowChangeVote' => true,
        'models' => [
        	// example declaration of models
            // \common\models\Post::className(),
            // 'backend\models\Post',
            // 2 => 'frontend\models\Story',
            // 3 => [
            //     'modelName' => \backend\models\Mail::className(),
            //     you can rewrite global values for specific model
            //     'allowGuests' => false,
            //     'allowChangeVote' => false,
            // ],
        ],      
    ],
],
```

And add widget in view:

```php
<?php echo \chiliec\vote\widgets\Vote::widget([
    'modelName' => $model->className(),
    'targetId' => $model->id,
    // optional fields
    // 'showAggregateRating' => false,
    // 'classGeneral' => 'text-center', // class for general widget div
    // 'classLike' => 'glyphicon glyphicon-thumbs-up', // class for like button
    // 'classDislike' => 'glyphicon glyphicon-thumbs-down', // class for dislike button
    // 'separator' => '&nbsp;', // separator between like and dislike button
]); ?>
```

## Step 3: Updating database schema

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/chiliec/yii2-vote/migrations
```

That's all! 

-------------------------------

## How to store rating in database

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
            'rating' => [
                'class' => \chiliec\vote\behaviors\RatingBehavior::className(),
            ],
        ];
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
    'modelName' => $model->className(),
    'targetId' => $model->id,
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

## License

yii2-vote is released under the BSD 3-Clause License. See the bundled [LICENSE.md](https://github.com/Chiliec/yii2-vote/blob/master/LICENSE.md) for details.

## List of contributors

* [Chiliec](https://github.com/Chiliec) - Maintainer
* [loveorigami](https://github.com/loveorigami) - Ideological inspirer
* [fourclub](https://github.com/fourclub) - PK name fix in behavior
* [yurkinx](https://github.com/yurkinx) - Duplication js render fix
* [n1k88](https://github.com/n1k88) - German translation

## How to contribute 

See [CONTRIBUTING.md](https://github.com/Chiliec/yii2-vote/blob/master/CONTRIBUTING.md) for details.

Enjoy and don't hesitate to send issues and pull requests :)
