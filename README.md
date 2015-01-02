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

In the above `pathMap` means that every view in @chiliec/vote/views will be first searched under `@app/views/vote`. After that create file `display.php` and decorate these code as you wish:
```php
<div id="vote<?=$target_id;?>">
    <span onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'like'); return false;"><?=$rating['likes'];?></span>&nbsp;
    <span onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'dislike'); return false;"><?=$rating['dislikes'];?></span>
    <div id="vote-response<?=$target_id;?>"><?=$rating['aggregate_rating'];?></div>
</div>
```

