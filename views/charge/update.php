<?php

use yii\helpers\Html;

$this->title = 'Edit: <strong>' . $model->typeModel->charge_type_name .'</strong> for Transaction <strong>' . $model->transaction->tran_number .'</strong>';

?>

<div class="modal-header" >
  <h4><?= $this->title ?></h4>
</div>

<div class="modal-body" >

	<div class="charge-update">

	    <?= $this->render('_form', [
	        'model' => $model,
	        'error' => $error
	    ]) ?>

	</div>


</div>


