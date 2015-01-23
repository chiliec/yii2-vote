<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\vote;
use yii\web\AssetBundle;

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
