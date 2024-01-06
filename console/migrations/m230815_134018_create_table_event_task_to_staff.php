<?php

use yii\db\Migration;

class m230815_134018_create_table_event_task_to_staff extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%event_task_to_staff}}',
            [
                'task_to_staff_id' => $this->primaryKey()->unsigned(),
                'task_id' => $this->integer()->unsigned()->notNull(),
                'staff_id' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('event_id', '{{%event_task_to_staff}}', ['task_id']);
        $this->createIndex('position_id', '{{%event_task_to_staff}}', ['staff_id']);

        $this->addForeignKey(
            'event_task_to_staff_ibfk_4',
            '{{%event_task_to_staff}}',
            ['staff_id'],
            '{{%staff}}',
            ['staff_id'],
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'event_task_to_staff_ibfk_5',
            '{{%event_task_to_staff}}',
            ['staff_id'],
            '{{%staff}}',
            ['staff_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%event_task_to_staff}}');
    }
}
