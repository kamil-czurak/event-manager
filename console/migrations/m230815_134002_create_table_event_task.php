<?php

use yii\db\Migration;

class m230815_134002_create_table_event_task extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%event_task}}',
            [
                'task_id' => $this->primaryKey()->unsigned(),
                'event_id' => $this->integer()->unsigned()->notNull(),
                'after_task_id' => $this->integer()->unsigned(),
                'name' => $this->string()->notNull(),
                'start_at' => $this->dateTime(),
                'planned_start_at' => $this->dateTime()->notNull(),
                'finished_at' => $this->dateTime(),
                'planned_finished_at' => $this->dateTime()->notNull(),
                'status' => $this->tinyInteger()->unsigned()->notNull(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('after_task_id', '{{%event_task}}', ['after_task_id']);
        $this->createIndex('event_id', '{{%event_task}}', ['event_id']);

        $this->addForeignKey(
            'event_task_ibfk_2',
            '{{%event_task}}',
            ['event_id'],
            '{{%event}}',
            ['event_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%event_task}}');
    }
}
