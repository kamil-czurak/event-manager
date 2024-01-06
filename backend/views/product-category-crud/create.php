<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ProductCategory $model */

$this->title = 'Utwórz kategorie';
$this->params['breadcrumbs'][] = ['label' => 'Kategorie', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
