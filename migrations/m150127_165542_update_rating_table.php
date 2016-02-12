<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

use yii\db\Schema;
use yii\db\Migration;

class m150127_165542_update_rating_table extends Migration
{
    protected $tableName = '{{%rating}}';

    public function up()
    {
        if ($this->db->driverName !== 'sqlite') {
            $this->alterColumn($this->tableName, 'date', Schema::TYPE_INTEGER. ' NOT NULL');
            $this->alterColumn($this->tableName, 'user_id', Schema::TYPE_INTEGER . ' NULL DEFAULT NULL');
        }
        if ($this->db->driverName === 'mysql') {
            $this->addColumn($this->tableName, 'user_ip', ' VARBINARY( 39 ) NOT NULL AFTER `user_id`');
        } else {
            $this->addColumn($this->tableName, 'user_ip', Schema::TYPE_STRING . '( 39 ) NOT NULL DEFAULT `127.0.0.1`');
        }
    }

    public function down()
    {
        if ($this->db->driverName !== 'sqlite') {
            $this->alterColumn($this->tableName, 'date', Schema::TYPE_TIMESTAMP . ' NOT NULL');
            $this->alterColumn($this->tableName, 'user_id', Schema::TYPE_STRING . ' NOT NULL');
        }
        $this->dropColumn($this->tableName, 'user_ip');
    }
}
