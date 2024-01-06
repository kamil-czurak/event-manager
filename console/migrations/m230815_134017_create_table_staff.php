<?php

use yii\db\Migration;

class m230815_134017_create_table_staff extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%staff}}',
            [
                'staff_id' => $this->primaryKey()->unsigned(),
                'position_id' => $this->tinyInteger()->unsigned()->notNull(),
                'user_id' => $this->integer()->unsigned()->notNull(),
                'first_name' => $this->string(64),
                'last_name' => $this->string(64),
                'status' => $this->boolean()->unsigned()->notNull()->defaultValue('1'),
                'phone' => $this->string(32),
                'comment' => $this->string(),
                'bid' => $this->string(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('user_id', '{{%staff}}', ['user_id']);
        $this->createIndex('position_id', '{{%staff}}', ['position_id']);

        $this->addForeignKey(
            'staff_ibfk_1',
            '{{%staff}}',
            ['position_id'],
            '{{%staff_position}}',
            ['position_id'],
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'staff_ibfk_2',
            '{{%staff}}',
            ['user_id'],
            '{{%user}}',
            ['user_id'],
            'CASCADE',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%staff}}');
    }
}
