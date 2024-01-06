<?php

use yii\db\Migration;

class m230815_134013_create_table_event_attachment extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%event_attachment}}',
            [
                'attachment_id' => $this->primaryKey()->unsigned(),
                'event_id' => $this->integer()->unsigned()->notNull(),
                'file_id' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('event_id', '{{%event_attachment}}', ['event_id']);
        $this->createIndex('file_id', '{{%event_attachment}}', ['file_id']);

        $this->addForeignKey(
            'event_attachment_ibfk_1',
            '{{%event_attachment}}',
            ['event_id'],
            '{{%event}}',
            ['event_id'],
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'event_attachment_ibfk_2',
            '{{%event_attachment}}',
            ['file_id'],
            '{{%file}}',
            ['file_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%event_attachment}}');
    }
}
