<?php

use common\helpers\Rbac;
use rmrevin\yii\fontawesome\FA;
use yiister\gentelella\widgets\Menu;

$enabledModules = array_keys(Yii::$app->modules);
$controllerId = Yii::$app->controller->id;
$actionId = Yii::$app->controller->action->id;
$isDashboard = $controllerId === 'site' && $actionId === 'index';

$menuItems = [];

$menuItems[] = [
    'icon' => 'dashboard',
    'label' => 'Strona główna',
    'url' => '/',
    'visible' => true,
    'active' => $controllerId === 'site',
];

$menuItems[] = [
    'icon' => FA::_CLOUD,
    'label' => "Wydarzenia",
    'url' => '/event-crud/index',
    'visible' => Yii::$app->user->can(Rbac::PERMISSION_EVENT_READ),
    'active' => $controllerId === 'event-crud',
];

$menuItems[] = [
    'icon' => FA::_MONEY,
    'label' => "Klienci",
    'url' => '/client-crud/index',
    'visible' => Yii::$app->user->can(Rbac::PERMISSION_CLIENT_READ),
    'active' => $controllerId === 'client-crud',
];

$menuItems[] = [
    'icon' => FA::_PRODUCT_HUNT,
    'label' => "Produkty",
    'url' => '/product-crud/index',
    'visible' => Yii::$app->user->can(Rbac::PERMISSION_PRODUCT_READ),
    'active' => $controllerId === 'product-crud',
];

$menuItems[] = [
    'icon' => FA::_USERS,
    'label' => "Pracownicy",
    'url' => '/staff-crud/index',
    'visible' => Yii::$app->user->can(Rbac::PERMISSION_STAFF_READ),
    'active' => $controllerId === 'staff-crud',
];

$menuItems[] = [
    'icon' => FA::_USER,
    'label' => Yii::t('lbl', 'Users'),
    'url' => '/user-crud/index',
    'visible' => Yii::$app->user->can(Rbac::PERMISSION_USER_READ),
    'active' => $controllerId === 'user-crud',
];

$menuItems[] = [
    'icon' => 'sign-out',
    'label' => Yii::t('lbl', 'Logout ({0})', Yii::$app->getUser()->getIdentity()->username),
    'url' => ['/site/logout'],
    'template' => '<a href="{url}" data-method="post">{icon}<span>{label}</span></a>',
];

echo Menu::widget([
    'items' => $menuItems,
]);
?>
