<div class="text-center">
	<span id="vote-up-<?=$modelId?>-<?=$targetId?>" class="glyphicon glyphicon-thumbs-up" onclick="vote(<?=$modelId?>, <?=$targetId?>, 'like'); return false;" style="cursor: pointer;"><?=$likes?></span>
	&nbsp;
	<span id="vote-down-<?=$modelId?>-<?=$targetId?>" class="glyphicon glyphicon-thumbs-down" onclick="vote(<?=$modelId?>, <?=$targetId?>, 'dislike'); return false;" style="cursor: pointer;"><?=$dislikes?></span>
	<div id="vote-response-<?=$modelId?>-<?=$targetId?>">
		<?php if ($showAggregateRating) { ?>
			<?=Yii::t('vote', 'Aggregate rating')?>: <?=$rating?>
		<?php } ?>
	</div>
</div>
<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
	<meta itemprop="interactionCount" content="UserLikes:<?=$likes?>"/>
	<meta itemprop="interactionCount" content="UserDislikes:<?=$dislikes?>"/>
	<meta itemprop="ratingValue" content="<?=$rating?>"/>
	<meta itemprop="ratingCount" content="<?=$likes+$dislikes?>"/>
	<meta itemprop="bestRating" content="10"/>
	<meta itemprop="worstRating" content="0"/>
</div>
