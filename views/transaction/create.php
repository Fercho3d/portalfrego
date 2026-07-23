<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$model->tran_type = $model->isNewRecord && isset($_GET['tran_type']) ?  $_GET['tran_type'] : $model->tran_type;

$this->title = 'Create Bill';

$this->params['breadcrumbs'][] = ['label' => 'Back to My Bookings'  , 'url' => ['/booking-provider' ]];
$this->params['breadcrumbs'][] = ['label' => 'Booking / ' .  $booking->booking_number, 'url' => ['/transaction/', 'booking' => $_GET['booking'] ] ];
$this->params['breadcrumbs'][] = ['label' => 'Transaction Bills', 'url' => ['index','booking' =>$_GET['booking'] ] ];
$this->params['breadcrumbs'][] = $this->title;
?>
    
<div class="modal-header" >
    <h4><?= Html::encode($this->title) ?></h4>
</div>	

<div class="modal-body" >
	<div class="col-md-12 transaction-create" >
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>


<div class="modal-footer">
    <div class="form-group text-right">
        <?=  Html::button( 'Close' , ['class' => 'btn btn-default', 'data-dismiss'=>'modal' ]) ?>
        <?= !$disabled ?  Html::submitButton( $model->isNewRecord? 'Next' : 'Update' , ['class' => 'btn btn-success',  'form'=>"tranForm"]) : '' ?>
    </div>
</div>

