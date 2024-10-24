<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Project $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="project-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'label' => Yii::t('app', 'Images'),
                'format' => 'raw',
                'value' => function($model){
                    /**
                     * @var $model common\models\Project
                     */
                    if(!$model->hasImages()){
                        return "No images";
                    }
                    $imagesHtml = "";
                    foreach($model->images as $image){
                        $imagesHtml.= Html::img($image->file->absoluteUrl(), [
                            'alt' => $image->file->name,
                            'height' => '100',
                            'class' => 'project-view__image'
                        ]);
                    }   
                    return $imagesHtml;
                }
            ],
            'tech_tass:raw',
            'description:ntext',
            'start_date',
            'end_date',
        ],
    ]) ?>

</div>
