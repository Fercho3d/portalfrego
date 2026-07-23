<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\BookingContinuity */

$this->title = 'Booking Continuity';
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['/booking/']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-continuity-create">
	
    <?= $this->render('_form', [
        'model' => $model,
        'booking' => $booking,
        'checkList' => $checkList,
        'isAdmin' => $isAdmin
    ]) ?>

</div>
