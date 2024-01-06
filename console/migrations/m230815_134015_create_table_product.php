<?php

use yii\db\Migration;

class m230815_134015_create_table_product extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%product}}',
            [
                'product_id' => $this->primaryKey()->unsigned(),
                'category_id' => $this->integer()->unsigned()->notNull(),
                'name' => $this->string(128)->notNull(),
                'status' => $this->boolean()->unsigned()->notNull()->defaultValue('1'),
                'quantity' => $this->decimal(8, 2)->unsigned(),
                'comment' => $this->string(),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->addForeignKey(
            'product_ibfk_1',
            '{{%product}}',
            ['category_id'],
            '{{%product_category}}',
            ['category_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
