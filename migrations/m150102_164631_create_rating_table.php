<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

use yii\db\Schema;
use yii\db\Migration;

class m150102_164631_create_rating_table extends Migration
{
    protected $tableName = '{{%rating}}';

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable($this->tableName, [
            'id' => Schema::TYPE_PK,
            'model_id' => Schema::TYPE_SMALLINT . ' NOT NULL',
            'target_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'user_id' => Schema::TYPE_STRING . ' NOT NULL',
            'value' => Schema::TYPE_BOOLEAN . ' NOT NULL',
            'date' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable($this->tableName);
    }
}
