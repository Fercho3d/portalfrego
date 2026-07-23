<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */

$this->title = $model->booking_id;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="booking-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'booking_id',
            'vessel',
            'booking_number',
            'client',
            'loading_port',
            'loading_EDT',
            'dicharge_port',
            'dicharge_ETA',
            'container_type',
            'commodity',
            'set_point',
            'created_at:date',
            'modified_at:date',
        ],
    ]) ?>

</div>
