<?php

use yii\db\Migration;

class m230815_134007_create_table_setting extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%setting}}',
            [
                'id' => $this->primaryKey(),
                'type' => $this->string(10)->notNull(),
                'section' => $this->string()->notNull(),
                'key' => $this->string()->notNull(),
                'value' => $this->text(),
                'status' => $this->smallInteger()->notNull()->defaultValue('1'),
                'description' => $this->string(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
