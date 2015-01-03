<div id="vote-<?=$model_name.$target_id;?>" style="text-align: center;">
    <span id="vote-up-<?=$model_name.$target_id;?>" class="glyphicon glyphicon-thumbs-up" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'like'); return false;" style="cursor:pointer;"><?=$rating['likes'];?></span>&nbsp;
    <span id="vote-down-<?=$model_name.$target_id;?>" class="glyphicon glyphicon-thumbs-down" onclick="vote('<?=$model_name;?>',<?=$target_id;?>,'dislike'); return false;" style="cursor:pointer;"><?=$rating['dislikes'];?></span>
    <div id="vote-response-<?=$model_name.$target_id;?>">Aggregate rating: <?=$rating['aggregate_rating'];?></div>
</div>