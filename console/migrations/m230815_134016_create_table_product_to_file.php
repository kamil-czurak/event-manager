<?php

use yii\db\Migration;

class m230815_134016_create_table_product_to_file extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%product_to_file}}',
            [
                'product_id' => $this->integer()->unsigned()->notNull(),
                'file_id' => $this->integer()->unsigned()->notNull(),
                'relation' => $this->tinyInteger()->unsigned()->notNull(),
                'sequence' => $this->tinyInteger()->unsigned()->notNull()->defaultValue('0'),
                'created_at' => $this->integer()->unsigned()->notNull(),
                'updated_at' => $this->integer()->unsigned()->notNull(),
            ],
            $tableOptions
        );

        $this->createIndex('product_id_file_id_relation', '{{%product_to_file}}', ['product_id', 'file_id', 'relation'], true);

        $this->addForeignKey(
            'product_to_file_ibfk_1',
            '{{%product_to_file}}',
            ['product_id'],
            '{{%product}}',
            ['product_id'],
            'CASCADE',
            'CASCADE'
        );
        $this->addForeignKey(
            'product_to_file_ibfk_2',
            '{{%product_to_file}}',
            ['file_id'],
            '{{%file}}',
            ['file_id'],
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%product_to_file}}');
    }
}
