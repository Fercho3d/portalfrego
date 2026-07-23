<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

if($booking->locked) { 
	$readOnly = true;
  }
  

?>
	<?php if($showButton):?>
	<?php $form = ActiveForm::begin(['id' => 'bookingForm', 'action' => 'save-intructions?id='. $booking->booking_id  ]); ?>
	<?php endif;  ?>

	<div class="row">
		<div class="col-md-6" ><?= $form->field($booking, 'shipper_is')->textArea(['readonly'=>$readOnly ]); ?></div>
		<div class="col-md-6" ><?= $form->field($booking, 'shipper_should')->textArea(['readonly'=>$readOnly ]); ?></div>
	</div>

	<div class="row">
		<div class="col-md-6" ><?= $form->field($booking, 'consignee_is')->textArea(['readonly'=>$readOnly ]); ?></div>
		<div class="col-md-6" ><?= $form->field($booking, 'consignee_should')->textArea(['readonly'=>$readOnly ]); ?></div>
	</div>

	<div class="row" >
		<div class="col-md-6" ><?= $form->field($booking, 'notify_party_is')->textArea(['readonly'=>$readOnly ]); ?></div>
		<div class="col-md-6" ><?= $form->field($booking, 'notify_party_should')->textArea(['readonly'=>$readOnly ]); ?></div>
	</div>

	<div class="row">
		<div class="col-md-6" ><?= $form->field($booking, 'description_is')->textArea(['readonly'=>$readOnly ]); ?></div>
		<div class="col-md-6" ><?= $form->field($booking, 'description_should')->textArea(['readonly'=>$readOnly ]); ?></div>
	</div>

	<?php if($showButton & (!$readOnly) ):?>
		<div class="row" >
			    <?= Html::submitButton('Save & send instrctions', ['class' => 'btn btn-success btn-lg col-md-6']) ?>
		</div>
	<?php endif;  ?>

<?php if($showButton):?>
		<?php ActiveForm::end(); ?>
<?php endif;  ?>

