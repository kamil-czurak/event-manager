<?php

use yii\db\Migration;

class m230815_134019_create_foreign_keys extends Migration
{
    public function safeUp()
    {
        $this->addForeignKey(
            'event_task_ibfk_5',
            '{{%event_task}}',
            ['after_task_id'],
            '{{%event_task}}',
            ['task_id'],
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'event_task_to_product_ibfk_2',
            '{{%event_task_to_product}}',
            ['task_id'],
            '{{%event_task}}',
            ['task_id'],
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'event_task_to_staff_ibfk_3',
            '{{%event_task_to_staff}}',
            ['task_id'],
            '{{%event_task}}',
            ['task_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('event_task_to_staff_ibfk_3', '{{%event_task_to_staff}}');
        $this->dropForeignKey('event_task_to_product_ibfk_2', '{{%event_task_to_product}}');
        $this->dropForeignKey('event_task_ibfk_5', '{{%event_task}}');
    }
}
