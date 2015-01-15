<?php

namespace chiliec\vote;
use yii\web\AssetBundle;

/**
 * VoteAsset
 *
 * @author Vladimir Babin <vovababin@gmail.com>
 */
class VoteAsset extends AssetBundle
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
