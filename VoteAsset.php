<?php

namespace chiliec\vote;

/**
 * VoteAsset
 *
 * @author Vladimir Babin <vovababin@gmail.com>
 */
class VoteAsset extends \yii\web\AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@chiliec/vote/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'vote.js',
    ];

}