<?php

use yii\db\Migration;

class m230815_134010_create_table_auth_assignment extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%auth_assignment}}',
            [
                'item_name' => $this->string(64)->notNull(),
                'user_id' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('PRIMARYKEY', '{{%auth_assignment}}', ['item_name', 'user_id']);

        $this->createIndex('idx-auth_assignment-user_id', '{{%auth_assignment}}', ['user_id']);

        $this->addForeignKey(
            'auth_assignment_ibfk_2',
            '{{%auth_assignment}}',
            ['user_id'],
            '{{%user}}',
            ['user_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%auth_assignment}}');
    }
}
