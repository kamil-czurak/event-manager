<?php

use yii\db\Migration;

class m240113_000001_update_auth_tables extends Migration
{
    public function safeUp()
    {

        $this->dropForeignKey("auth_item_ibfk_1", '{{%auth_item}}');
        $this->dropPrimaryKey('name', "{{%auth_rule}}");
        $this->addColumn("{{%auth_rule}}", 'rule_id', $this->primaryKey(10)->unsigned()->append('AUTO_INCREMENT FIRST'));
        $this->createIndex('name', "{{%auth_rule}}", 'name', true);
        $this->addForeignKey('auth_item_ibfk_1', '{{%auth_item}}', ['rule_name'], '{{%auth_rule}}', ['name'], 'SET NULL', 'CASCADE');

        $this->dropForeignKey("auth_assignment_ibfk_2", '{{%auth_assignment}}');
        $this->dropPrimaryKey('item_name_user_id', "{{%auth_assignment}}");
        $this->addColumn("{{%auth_assignment}}", 'assignment_id', $this->primaryKey(10)->unsigned()->append('AUTO_INCREMENT FIRST'));
        $this->createIndex('name', "{{%auth_assignment}}", ['item_name', 'user_id'], true);
        $this->addForeignKey('auth_assignment_ibfk_2', '{{%auth_assignment}}', ['user_id'], '{{%user}}', ['user_id'], 'CASCADE', 'CASCADE');

        $this->dropForeignKey("auth_item_ibfk_1", '{{%auth_item}}');
        $this->dropForeignKey("auth_item_child_ibfk_1", '{{%auth_item_child}}');
        $this->dropForeignKey("auth_item_child_ibfk_2", '{{%auth_item_child}}');
        $this->dropPrimaryKey('name', "{{%auth_item}}");
        $this->addColumn("{{%auth_item}}", 'item_id', $this->primaryKey(10)->unsigned()->append('AUTO_INCREMENT FIRST'));
        $this->createIndex('name', "{{%auth_item}}", 'name', true);
        $this->addForeignKey('auth_item_ibfk_1', '{{%auth_item}}', ['rule_name'], '{{%auth_rule}}', ['name'], 'SET NULL', 'CASCADE');

        $this->dropPrimaryKey('parent_child', "{{%auth_item_child}}");
        $this->addColumn("{{%auth_item_child}}", 'child_id', $this->primaryKey(10)->unsigned()->append('AUTO_INCREMENT FIRST'));
        $this->createIndex('parent_child', "{{%auth_item_child}}", ['parent', 'child'], true);
        $this->addForeignKey('auth_item_child_ibfk_1', '{{%auth_item_child}}', ['parent'], '{{%auth_item}}', ['name'], 'CASCADE', 'CASCADE');
        $this->addForeignKey('auth_item_child_ibfk_2', '{{%auth_item_child}}', ['child'], '{{%auth_item}}', ['name'], 'CASCADE', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropColumn("{{auth_rule}}", 'rule_id');
        $this->dropColumn("{{auth_assignment}}", 'assigment_id');
        $this->dropColumn("{{auth_item}}", 'item_id');
        $this->dropColumn("{{auth_item_child}}", 'child_id');
    }
}
