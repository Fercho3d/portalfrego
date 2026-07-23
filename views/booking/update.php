<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */

$this->title = 'Booking: ';
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->booking_number, 'url' => ['view', 'id' => $model->booking_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?php if(!empty($_GET['success'])): ?>
<div class="alert alert-success fade in">
	 <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
	<?= $_GET['success'] ?>
</div>
<?php endif; ?>

<?php $form = ActiveForm::begin(['id' => 'bookingForm','options' => ['enctype' => 'multipart/form-data' ]  ]); ?>
          
<div class="row tabs-content" >
	<div class="col-md-12" >
		
	<ul class="nav nav-tabs" style="margin-top: 15px" >
	  <li class="active"  >
	  	<a data-toggle="tab" href="#booking">
	  		<?= Html::encode($this->title) ?> <strong class="text-danger"><?= $model->booking_number ?></strong></a>
	  	</li>
	  <li><a href="#containers" data-toggle="tab" >Containers</a></li>
	  <li><a href="#files" data-toggle="tab" >Files</a></li>
	  <li><a href="#instructions" data-toggle="tab" >Shipment Instructions</a></li>
	  <li><a href="#checkList" data-toggle="tab" >Tracking</a></li>
	</ul>

	<div class="tab-content " >
		<div id="booking"  class="tab-pane fade in active" >
		
			<div class="booking-update" style="margin-top: 15px" >
			    <?= $this->render('_form', [
			        'model' => $model,
			        'client' => $client,
			        'form' => $form,
			        'pickupPlace' => $pickupPlace,
			        'showPreview' => true
			    ]) ?>
			</div>
		</div>

		<div id="files"  class="tab-pane" >
			<?= $this->render('_files', [
		        'fileFields' => $fileFields,
		        'booking' => $model,
			]) ?>
		</div>

		<div id="containers" class="tab-pane" >
			
			<?= $this->render('_edit_containers', [
		        'dataProvider' => $dataProvider_contrs,
		        'booking' => $model
			]) ?>

		</div>

		<div id="instructions" class="tab-pane" >
			  <?= $this->render('_instructions', [
	            'booking' => $model,
	            'showButton' => false,
	            'form' => $form,
	        ]) ?>
		</div>		
			<div id="checkList" class="tab-pane" >
				  <?= $this->render('_checklist', [
		            'booking' => $model,
		            'showButton' => false,
		            'form' => $form,
		        ]) ?>
			</div>
		</div>

	</div>
</div>
</div>
<?php ActiveForm::end(); ?>

<div class="row bottom-fixed" >
		<div class="btn-group col-md-6">
            <?= Html::a('Back', ['/booking'] , ['class' => 'btn btn-danger btn-md col-md-4']) ?>
            <?php if($model->is_draft == 1): ?>
            <?= Html::a('+ Add Containers before send', "javascript:$('.nav-tabs a[href=\"#containers\"]').tab('show');" ,  
            ['class' => 'btn btn-primary btn-md col-md-4' ]) ?>
            <?php endif; ?>

	        <?= $model->locked ? '' : html::submitButton( $model->is_draft == 1 ? 'Save & send confirmation' : 'Update Booking Data', ['class' => 'btn btn-success  btn-md col-md-4', 'form'=>"bookingForm", 'name' => 'Booking[is_draf]' , 'value' => 0 ]) ?>
		</div>
</div>	



<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/transaction.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>