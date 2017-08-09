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
            if ($this->db->driverName === 'pgsql') {
                $this->execute('ALTER TABLE ' . $this->tableName . ' ALTER COLUMN "date" DROP DEFAULT');
                $this->alterColumn($this->tableName, 'date', Schema::TYPE_INTEGER . ' USING (date::integer)');
                $this->execute('ALTER TABLE ' . $this->tableName . ' ALTER COLUMN "date" SET NOT NULL');

                $this->execute('ALTER TABLE ' . $this->tableName . ' ALTER COLUMN "user_id" DROP NOT NULL');
                $this->alterColumn($this->tableName, 'user_id', Schema::TYPE_INTEGER . ' USING (user_id::integer)');
                $this->execute('ALTER TABLE ' . $this->tableName . ' ALTER COLUMN "user_id" SET DEFAULT NULL');
            } else {
                $this->alterColumn($this->tableName, 'date', Schema::TYPE_INTEGER. ' NOT NULL');
                $this->alterColumn($this->tableName, 'user_id', Schema::TYPE_INTEGER . ' NULL DEFAULT NULL');
            }
        }
        if ($this->db->driverName === 'pgsql') {
            $this->addColumn($this->tableName, 'user_ip', Schema::TYPE_STRING . '( 39 ) NOT NULL DEFAULT \'127.0.0.1\'');
        } else if ($this->db->driverName === 'mysql') {
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
