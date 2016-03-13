# Vote for Yii2

[![Latest Stable Version](https://poser.pugx.org/chiliec/yii2-vote/v/stable.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Total Downloads](https://poser.pugx.org/chiliec/yii2-vote/downloads.svg)](https://packagist.org/packages/chiliec/yii2-vote) [![Build Status](https://travis-ci.org/Chiliec/yii2-vote.svg?branch=master)](https://travis-ci.org/Chiliec/yii2-vote) [![Test Coverage](https://codeclimate.com/github/Chiliec/yii2-vote/badges/coverage.svg)](https://codeclimate.com/github/Chiliec/yii2-vote/coverage) [![Code Climate](https://codeclimate.com/github/Chiliec/yii2-vote/badges/gpa.svg)](https://codeclimate.com/github/Chiliec/yii2-vote) [![License](https://poser.pugx.org/chiliec/yii2-vote/license.svg)](https://packagist.org/packages/chiliec/yii2-vote)

![How yii2-vote works](https://raw.githubusercontent.com/Chiliec/yii2-vote/master/docs/showcase.gif)

## Installation

Next steps will guide you through the process of installing yii2-vote using **composer**. Installation is a quick and easy three-step process.

### Step 1: Install component via composer

Run command

```
php composer.phar require --prefer-dist chiliec/yii2-vote "^3.0"
```

or add

```
"chiliec/yii2-vote": "^3.0"
```

to the require section of your `composer.json` file.


### Step 2: Configuring your application

Add following lines to your main configuration file:

```php
'bootstrap' => [
    'chiliec\vote\components\VoteBootstrap',
],
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
    'model' => $model,
    // optional fields
    // 'showAggregateRating' => true,
]); ?>
```

Also you can add widget for display top rated models:

```php
<?php echo \chiliec\vote\widgets\TopRated::widget([
    'modelName' => \common\models\Post::className(),
    'title' => 'Top rated models',
    'path' => 'site/view',
    'limit' => 10,
    'titleField' => 'title',
]) ?>
```

### Step 3: Updating database schema

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/chiliec/yii2-vote/migrations
```

## Documentation

Extended information about configuration of this module see in [docs/README.md](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md). There you can find:
* [Migration from 2.* to 3.0](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#migration-from-2-to-30)
* [Manually add behavior in models](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#manually-add-behavior-in-models)
* [Sorting by rating in data provider](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#sorting-by-rating-in-data-provider)
* [Overriding views](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#overriding-views)
* [Customizing JS-events](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#customizing-js-events)
* [Rich snippet in search engines](https://github.com/Chiliec/yii2-vote/blob/master/docs/README.md#rich-snippet-in-search-engines)

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
