<?php

namespace common\tests\unit;

use common\models\Product;
use common\models\ProductCategory;
use Faker\Factory;

class ProductTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;

    /** @var Factory $faker */
    protected $faker;

    protected function _before()
    {
        $this->faker = Factory::create();
    }

    public function testCreateProductWithCategory()
    {
        $productCategory = new ProductCategory();
        $productCategory->name = $this->faker->sentence(3);
        $productCategory->status = ProductCategory::STATUS_ACTIVE;
        $productCategory->save();

        $product = new Product();
        $product->name = $this->faker->sentence(3);
        $product->comment = $this->faker->text;
        $product->quantity = $this->faker->numberBetween(1, 1000);

        $product->category_id = $productCategory->category_id;

        try {
            $product->save();
        } catch (InvalidConfigException | IntegrityException $e) {
            $this->fail('Nie można zapisać produktu: ' . $e->getMessage());
        }

        $this->assertNotNull($product->product_id);
        $this->assertEquals($productCategory->category_id, $product->category_id);
    }

    public function testCreateProductWithoutCategory()
    {
        $product = new Product();
        $product->name = $this->faker->sentence(3);
        $product->comment = $this->faker->text;
        $product->quantity = $this->faker->numberBetween(1, 1000);
        $product->save();

        $this->assertNull($product->product_id);
    }

    public function testCreateProductWithoutRequiredData()
    {
        $product = new Product();

        $this->assertFalse($product->validate());
    }

    public function testAssignProductToNonexistentCategory()
    {
        $product = new Product();
        $product->name = $this->faker->sentence(3);
        $product->comment = $this->faker->text;
        $product->quantity = $this->faker->numberBetween(1, 1000);

        $product->category_id = -1;

        $product->save();
        $this->assertNull($product->product_id);
    }

    public function testDeleteProduct()
    {
        $productCategory = new ProductCategory();
        $productCategory->name = $this->faker->sentence(3);
        $productCategory->status = ProductCategory::STATUS_ACTIVE;
        $productCategory->save();

        $product = new Product();
        $product->name = $this->faker->sentence(3);
        $product->comment = $this->faker->text;
        $product->quantity = $this->faker->numberBetween(1, 1000);

        $product->category_id = $productCategory->category_id;
        $product->save();

        $this->assertNotNull($product->product_id);

        $product->delete();

        $this->assertNull(Product::findOne($product->product_id));
    }
}
