<?php

use yii\helpers\Html;

$this->title = "Account Settings";
$this->params['breadcrumbs'][] = ['label' => 'Help Center', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="help-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="">
         <?= Html::a('Policy', ['site/policy']) ?>
    </div>
    <div class="">
         <?= Html::a('Contact Us', ['site/contact-us']) ?>
    </div>
    <div class="">
         <?= Html::a('Home', ['site/index']) ?>
    </div>
</div>