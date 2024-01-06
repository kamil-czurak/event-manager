<?php

use yii\db\Migration;

class m230815_134001_create_table_event extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%event}}',
            [
                'event_id' => $this->primaryKey()->unsigned(),
                'client_id' => $this->integer()->unsigned(),
                'name' => $this->string(128)->notNull(),
                'status' => $this->boolean()->unsigned()->notNull()->defaultValue('0'),
                'contact_coordinator_name' => $this->string(64),
                'contact_coordinator_phone' => $this->string(16),
                'city' => $this->string(32),
                'street' => $this->string(32),
                'street_number' => $this->string(8),
                'zipcode' => $this->string(16),
                'ready_at' => $this->dateTime(),
                'start_at' => $this->dateTime(),
                'end_at' => $this->dateTime(),
                'comment' => $this->string(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('client_id', '{{%event}}', ['client_id']);

        $this->addForeignKey(
            'event_ibfk_1',
            '{{%event}}',
            ['client_id'],
            '{{%client}}',
            ['client_id'],
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%event}}');
    }
}
