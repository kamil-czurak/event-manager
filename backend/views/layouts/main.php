<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\helpers\Rbac;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yiister\gentelella\assets\Asset as LayoutAsset;

AppAsset::register($this);
LayoutAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>">
<?php $this->beginBody() ?>
<div class="container body">

    <div class="main_container">

        <div class="col-md-3 left_col">
            <div class="left_col scroll-view">

                <div class="navbar nav_title" style="border: 0;">
                    <a href="/" class="site_title"><i class="fa fa-cogs"></i> <span><?= Yii::$app->user->getIsGuest() ? 'ADM' : Yii::$app->name ?></span></a>
                </div>
                <div class="clearfix"></div>

                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                    <div class="menu_section">
                        <?= $this->render('//_partials/menu.php') ?>
                    </div>
                </div>

            </div>
        </div>

        <?php if (!Yii::$app->user->getIsGuest()): ?>
            <div class="top_nav">
                <div class="nav_menu">
                    <nav class="" role="navigation">
                        <div class="nav toggle">
                            <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                        </div>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="">
                                <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-user-circle-o" style="font-size: 25px; display: inline-block; vertical-align: middle;"></i>
                                    <?= Yii::$app->user->identity->username ?>
                                    <span class="fa fa-angle-down"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-usermenu pull-right">
                                    <li><?= Html::a('Zmień hasło', ['/site/password-change']) ?></li>
                                    <li><?= Html::a(Yii::t('lbl', 'Logout ({0})', Yii::$app->user->identity->getDisplayName()), ['/site/logout'], ['data' => ['method' => 'post']]) ?></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        <?php endif; ?>

        <div class="right_col" role="main">
            <div class="clearfix"></div>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
        <footer>
            <div class="pull-left">&copy; <?= Yii::$app->user->getIsGuest() ? 'ADM' : Html::encode(Yii::$app->name) ?> <?= date('Y') ?></div>

            <div class="clearfix"></div>
        </footer>
    </div>

</div>
<div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
