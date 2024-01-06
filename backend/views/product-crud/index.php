<?php

use common\models\Product;
use common\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use common\models\ProductCategory;

/** @var yii\web\View $this */
/** @var backend\models\ProductSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Produkty';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Stwórz', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Kategorie', ['/product-category-crud/index'], ['class' => 'btn btn-info']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'product_id',
            [
                'attribute' => 'mainImage',
                'format' => 'html',
                'value' => function(Product $model) {
                    return Html::getFilePreview($model->mainImage);
                }
            ],
            'name',
            [
                'attribute' => 'category_id',
                'filter' => ProductCategory::getMap(),
                'value' => function (Product $model) {
                    return $model->category->name;
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Product::getStatusMap(),
                'value' => function (Product $model) {
                    return Product::getStatusMap($model->status);
                }
            ],
            'quantity',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Product $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'product_id' => $model->product_id]);
                 }
            ],
        ],
    ]); ?>


</div>
