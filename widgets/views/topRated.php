<?php
use yii\helpers\Html;
use yii\widgets\Menu;

echo Html::tag('h3', $title);
$items = [];
foreach ($models as $model) {
	$item = [];
	$item['label'] = Html::encode($model->title) . ' ' . $model->aggregate->rating;
	$item['url'] = [$path, $model::primaryKey()[0] => $model->{$model::primaryKey()[0]}];
	$items[] = $item;
}
echo Menu::widget(['items' => $items]);
