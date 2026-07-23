<?php

use yii\helpers\Html;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Charge Bill: ' . $model->tran_number;
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['/booking' ]];
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index', 'booking' => $model->booking ]];
$this->params['breadcrumbs'][] = ['label' => $model->tran_number, 'url' => ['view', 'id' => $model->transc_id]];
$this->params['breadcrumbs'][] = 'Update';

$disabled = $model->payment_request || $booking->locked;

?>

<div class="modal-header" >
  <h4><?= Html::encode($this->title) ?></h4>
</div>

<div class="modal-body" >
  
    <?php if(!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>  

    <?php if(!empty($error)): ?>
        <div class="alert alert-danger">
          <ul>
            <?php foreach(explode('. ',$error) as $item ): ?>
               <li> <?= $item ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
    <?php endif; ?>

  <div class="col-md-12" >

   <div class="card col-md-12" >
    <!-- <div class="separator" >Transaction Bill Details</div> -->
          <?= $this->render('_form', [
              'model' => $model,
              'showPreview'=> true,
              'booking' => $booking,
          ]) ?>
    </div>

  <div class="card col-md-12 "  style="margin-bottom: 30px">
             
      <div class="separator" >Charges / Services</div>
          <?= $this->render('_charges_by_transaction', [
              'dataProvider' => $dataProvider,
              'disabled'=> $disabled
          ]) ?>
    </div>
  </div>


</div>

<div class="modal-footer" >
    <div class="form-group text-right">
        <?=  Html::button( 'Close' , ['class' => 'btn btn-light', 'data-dismiss'=>'modal' ]) ?>
        <?= !$disabled ?  Html::submitButton( $model->isNewRecord? 'Next' : 'Save Changes' , ['class' => 'btn btn-success', 'form'=>"tranForm"]) : '' ?>
    </div>
</div>

