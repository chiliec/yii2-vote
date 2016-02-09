<?php
/**
 * @link https://github.com/Chiliec/yii2-vote
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

use yii\db\Schema;
use yii\db\Migration;

class m160209_074651_add_indexes_for_aggregate_rating_table extends Migration
{
    protected $tableName = '{{%aggregate_rating}}';

    public function up()
    {
        $this->createIndex('aggregate_model_id_target_id', $this->tableName, ['model_id','target_id'], true);
    }

    public function down()
    {
        $this->dropIndex('aggregate_model_id_target_id', $this->tableName);
    }
}
