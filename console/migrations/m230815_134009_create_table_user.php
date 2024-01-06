<?php

use yii\db\Migration;

class m230815_134009_create_table_user extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%user}}',
            [
                'user_id' => $this->primaryKey()->unsigned(),
                'username' => $this->string(64),
                'email' => $this->string(64),
                'password_hash' => $this->string(128),
                'password_reset_token' => $this->string(128),
                'auth_key' => $this->string(),
                'status' => $this->tinyInteger()->unsigned()->notNull()->comment('0:inactive,1:active,2:deleted,3:blocked,4:disabled'),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('password_reset_token', '{{%user}}', ['password_reset_token'], true);
        $this->createIndex('email', '{{%user}}', ['email'], true);
        $this->createIndex('username', '{{%user}}', ['username'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
