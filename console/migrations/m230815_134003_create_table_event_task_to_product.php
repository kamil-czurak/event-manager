<?php

use yii\db\Migration;

class m230815_134003_create_table_event_task_to_product extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%event_task_to_product}}',
            [
                'event_task_to_product_id' => $this->primaryKey()->unsigned(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'product_id' => $this->integer()->unsigned()->notNull(),
                'quantity' => $this->integer()->unsigned(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('product_id', '{{%event_task_to_product}}', ['product_id']);
        $this->createIndex('task_id_product_id', '{{%event_task_to_product}}', ['task_id', 'product_id'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%event_task_to_product}}');
    }
}
