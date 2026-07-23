<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentRequest */

$this->title = 'Update Payment Request: ' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Payment Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->request_id, 'url' => ['view', 'id' => $model->request_id]];
$this->params['breadcrumbs'][] = 'Update';


?>
	<div class="modal-header" >
	  	<h4><?= $this->title ?></h4>
	</div>

	<div class="modal-body" > 


		<div class="row" >
			<div class="col-md-12" >  
		    <?= $this->render('_form', [
		        'model' => $model,
		    ]) ?>
		   </div>
		</div>		

		<div class="row" >
			<div class="col-md-12">  
		    <?= $this->render('_transactions', [
		        'dataProvider' => $dataProvider,
		        'request' => $model
		    ]) ?>
		   </div>
		</div>
	
	</div>

</div>

<div class="modal-footer" >
    <div class="form-group text-right">
        <?=  Html::button( 'Close' , ['class' => 'btn btn-default', 'data-dismiss'=>'modal' ]) ?>
    </div>
</div>
