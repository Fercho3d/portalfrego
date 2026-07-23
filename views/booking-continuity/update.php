<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingContinuity */

$this->title = 'Update Booking Continuity: ' . $booking->booking_number;
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['/booking/']];
$this->params['breadcrumbs'][] = ['label' => $booking->booking_number, 'url' => ['view', 'id' => $booking->booking_id]];
?>
<div class="booking-continuity-update" >

<?= \yii\helpers\Html::a( 'Back', Yii::$app->request->referrer, ['class' => 'btn btn-success']) ?>




    <?= $this->render('_form', [
        'model' => $model,
        'booking' =>  $booking,
        'checkList' =>  $checkList,
        'isAdmin' =>  $isAdmin
    ]) ?>

</div>
