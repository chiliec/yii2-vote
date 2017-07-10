<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

use yii\db\Schema;
use yii\db\Migration;

class m160126_140022_create_aggregate_rating_table extends Migration
{
    protected $tableName = '{{%aggregate_rating}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        if ($this->db->driverName === 'pgsql') {
            $ratingOptions = '(3,2) NOT NULL';
        } else {
            $ratingOptions = '(3,2) unsigned NOT NULL';
        }

        $this->createTable($this->tableName, [
            'id' => Schema::TYPE_PK,
            'model_id' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'target_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'likes' => Schema::TYPE_INTEGER . ' NOT NULL',
            'dislikes' => Schema::TYPE_INTEGER . ' NOT NULL',
            'rating' => Schema::TYPE_FLOAT . $ratingOptions
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
