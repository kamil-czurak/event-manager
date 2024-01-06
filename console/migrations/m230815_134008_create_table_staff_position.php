<?php

use yii\db\Migration;

class m230815_134008_create_table_staff_position extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%staff_position}}',
            [
                'position_id' => $this->tinyInteger()->unsigned()->notNull()->append('AUTO_INCREMENT PRIMARY KEY'),
                'name' => $this->string(64)->notNull(),
                'status' => $this->boolean()->unsigned()->notNull()->defaultValue('1'),
                'updated_at' => $this->integer()->unsigned()->notNull(),
                'created_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%staff_position}}');
    }
}
