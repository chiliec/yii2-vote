<div id="vote<?=$target_id;?>" class="center-block" style="text-align: center; font-size: 1.4em;">
    <span class="glyphicon glyphicon-thumbs-up" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'like'); return false;" style="cursor:pointer;"><?=$rating['likes'];?></span>&nbsp;
    <span class="glyphicon glyphicon-thumbs-down" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'dislike'); return false;" style="cursor:pointer;"><?=$rating['dislikes'];?></span>
    <div id="vote-response<?=$target_id;?>">Рейтинг: <?=$rating['aggregate_rating'];?></div>
</div>