<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

use yii\db\Migration;

class m150129_132124_add_indexes_for_rating_table extends Migration
{
    protected $tableName = '{{%rating}}';

    public function up()
    {
        $this->createIndex('rating_model_id_target_id', $this->tableName, ['model_id','target_id'], false);
        $this->createIndex('rating_user_id', $this->tableName, 'user_id', false);
        $this->createIndex('rating_user_ip', $this->tableName, 'user_ip', false);
    }

    public function down()
    {
        $this->dropIndex('rating_model_id_target_id', $this->tableName);
        $this->dropIndex('rating_user_id', $this->tableName);
        $this->dropIndex('rating_user_ip', $this->tableName);
    }
}
