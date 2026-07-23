<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingContinuitySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Booking Continuities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-continuity-index">
  <div class="col-md-10">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Booking Continuity', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'booking',
            'pickup_date',
            'modality',
            'vacuum_maneuver',
            //'doc_cut_of',
            //'SI_date',
            //'draf_client',
            //'gated_IN',
            //'cleared',
            //'departure',
            //'bl_payment',
            //'swb',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

   </div>
</div>