<?php

namespace common\tests\integration;

use Yii;
use yii\db\Migration;
use PHPUnit\Framework\TestCase;

class MigrationTest extends TestCase
{
    public $migrationPath = '@app/migrations';

    protected $db;

    protected function setUp(): void
    {
        parent::setUp();

        $this->db = Yii::$app->db;

        $migration = new Migration(['db' => $this->db]);
        $migration->up();
    }

    public function testDatabaseMigration()
    {
        $this->assertTrue($this->db->schema->getTableSchema('user') !== null);
        $this->assertTrue($this->db->schema->getTableSchema('product') !== null);
        $this->assertTrue($this->db->schema->getTableSchema('event')->getColumn('created_at') !== null);
        $this->assertTrue($this->db->schema->getTableSchema('product')->getColumn('name') !== null);
    }

    protected function tearDown(): void
    {
        $migration = new Migration(['db' => $this->db]);
        $migration->down();

        parent::tearDown();
    }
}