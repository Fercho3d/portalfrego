<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Provider */

/*$this->title = $model->provider_id;
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/

$this->title = 'Provider: ' . $model->booking;
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['/booking' ]];
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index', 'booking' => $model->booking ]];
$this->params['breadcrumbs'][] = ['label' => $model->provider_id, 'url' => ['view', 'id' => $model->provider_id]];
$this->params['breadcrumbs'][] = 'View';
\yii\web\YiiAsset::register($this);
?>
<div class="provider-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->provider_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->provider_id], [
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
            'provider_id',
            'rfc',
            'fullName',
            'address',
            'state',
            'city',
            'email:email',
            'postal_code',
            'phone',
            'cretated_by',
            'modified_by',
            'created_at',
            'modified_at',
        ],
    ]) ?>

</div>
