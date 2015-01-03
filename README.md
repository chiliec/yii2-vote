Vote for Yii2
======================

Next steps will guide you through the process of installing yii2-vote using **composer**. Installation is a quick and easy three-step process.

Step 1: Install component via composer
------------------------------------

Run command

```
php composer.phar require --prefer-dist chiliec/yii2-vote "*"
```

or add

```
"chiliec/yii2-vote": "*"
```

to the require section of your `composer.json` file.


Step 2: Configuring your application
------------------------------------

Add following lines to your main configuration file:

```php
...
'modules' => [
    ...
	'vote' => [
		'class' => 'chiliec\vote\Module',
		'matchingModels' => [ // matching model names with whatever integer ID
			'article' => 0, 
			'audio' => 1,
			'video' => 2,
			...
		],
		'allow_guests' => true, // if true remember IP for guests, otherwise - UserID
	],
    ...
],
...
```

And add widget in view:

```php
<?php echo \chiliec\vote\Display::widget([
	'model_name' => 'article', // name of current model
	'target_id' => $model->id, // id of current element
]); ?>
```

Step 3: Updating database schema
--------------------------------

After you downloaded and configured Yii2-vote, the last thing you need to do is updating your database schema by applying the migrations:

```bash
$ php yii migrate/up --migrationPath=@vendor/chiliec/yii2-vote/migrations
```

That's all! 

Customize display
-----------------
Although view of these widget are not configurable, Yii2 provides a way to override views using themes. To get started you should configure your view application component as follows:

```php
...
'components' => [
    'view' => [
        'theme' => [
            'pathMap' => [
                '@chiliec/vote/views' => '@app/views/vote'
            ],
        ],
    ],
],
...
```

In the above `pathMap` means that every view in `@chiliec/vote/views` will be first searched under `@app/views/vote`. Because of that create file `display.php` in `app/views/vote`, put these code and decorate it as you wish:

```php
<div id="vote-<?=$model_name.$target_id;?>" style="text-align: center;">
    <span id="vote-up-<?=$model_name.$target_id;?>" class="glyphicon glyphicon-thumbs-up" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'like'); return false;" style="cursor:pointer;"><?=$rating['likes'];?></span>&nbsp;
    <span id="vote-down-<?=$model_name.$target_id;?>" class="glyphicon glyphicon-thumbs-down" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'dislike'); return false;" style="cursor:pointer;"><?=$rating['dislikes'];?></span>
    <div id="vote-response-<?=$model_name.$target_id;?>">Aggregate rating: <?=$rating['aggregate_rating'];?></div>
</div>
```

Identifiers should be left (javascript logic is fastened on them), but you can change name of tags and classes.

For example, you can markup with [schema.org](http://schema.org/AggregateRating) synthax for help search engines recognize it:

```php
<div id="vote-<?=$model_name.$target_id;?>" style="text-align: center;">
    <span id="vote-up-<?=$model_name.$target_id;?>" class="glyphicon glyphicon-thumbs-up" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'like'); return false;" style="cursor:pointer;"><?=$rating['likes'];?></span>&nbsp;
    <span id="vote-down-<?=$model_name.$target_id;?>" class="glyphicon glyphicon-thumbs-down" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'dislike'); return false;" style="cursor:pointer;"><?=$rating['dislikes'];?></span>
    <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" id="vote-response-<?=$model_name.$target_id;?>">
        <meta itemprop="bestRating" content="10" />
        <meta itemprop="worstRating" content="0" />
        Aggregate rating: <span itemprop="ratingValue"><?=$rating['aggregate_rating'];?> based on <span itemprop="ratingCount"><?=$rating['likes']+$rating['dislikes'];?></span> reviews
    </div>
</div>
````

