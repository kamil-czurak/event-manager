<?php

use yii\db\Migration;

class m230815_133959_create_table_auth_rule extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%auth_rule}}',
            [
                'name' => $this->string(64)->notNull()->append('PRIMARY KEY'),
                'data' => $this->binary(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%auth_rule}}');
    }
}
