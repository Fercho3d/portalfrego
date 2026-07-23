<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Containers */

$this->title = $model->container_ID;
$this->params['breadcrumbs'][] = ['label' => 'Containers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="containers-view col-md-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->container_ID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->container_ID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'container_ID',
            'quantity',
            'comodity',
            'type_data',
            'booking_data',
            'number',
            'seal',
            'created_by',
            'modified_by',
            'created_at',
            'modified_at',
        ],
    ]) ?>

</div>
