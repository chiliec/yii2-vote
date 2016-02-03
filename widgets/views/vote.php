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
