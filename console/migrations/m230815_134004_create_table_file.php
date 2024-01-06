<?php

use yii\db\Migration;

class m230815_134004_create_table_file extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%file}}',
            [
                'file_id' => $this->primaryKey()->unsigned(),
                'name' => $this->string()->notNull(),
                'alt' => $this->string(),
                'type' => $this->string(128)->notNull(),
                'size' => $this->integer()->unsigned()->notNull(),
                'status' => $this->boolean()->notNull()->defaultValue('1'),
                'created_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}
