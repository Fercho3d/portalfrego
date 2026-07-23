<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */

$this->title = '+ Create new Booking';
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'bookingForm','options' => ['enctype' => 'multipart/form-data' ]  ]); ?>

<div class="row"  >
	<div class="col-md-12" > 

		<ul class="nav nav-tabs" style="margin-top: 15px" >
		  <li class="active"  ><a data-toggle="tab" href="#booking">Booking</a></li>
		  <li><a onclick="$('#bookingForm').attr('action', 'create?tab=containers' ).submit()"  ><strong class="text-danger">+ Add Containers before send</strong></a></li>
		  <li><a onclick="$('#bookingForm').attr('action', 'create?tab=files' ).submit()"  >Add Files</a></li>
		</ul>

		<div class="tab-content" >
			<div id="booking"  class="tab-pane fade in active" >
				<div class="booking-update" >
				    <?= $this->render('_form', [
				        'model' => $model,
				        'form' => $form,
				        'error' => $error
				    ]) ?>
				    
				</div>
			</div>
		</div>
	</div>
</div>
<?php ActiveForm::end(); ?>

<div class="row bottom-fixed" >
		<div class="btn-group col-md-6">
        <?= Html::a('Cancel', ['/booking'] ,  ['class' => 'btn btn-danger btn-md col-md-4']) ?>
        <?= Html::a('+ Add Containers before send', '#' ,  ['class' => 'btn btn-primary btn-md  col-md-4', 'onclick'=> "$('#bookingForm').attr('action', 'create?tab=containers' ).submit()" ]) ?>
        <?= Html::a('Save & send confirmation', '#' ,['class' => 'confirm btn btn-success  btn-md col-md-4'  ]) ?>
		</div>
</div>	


<script type="text/javascript">

	$('.confirm').click(function(event) {

		 event.preventDefault();	

		$('#bookingForm').attr('action', 'create?isDraf=0' ).submit()
		
	});

</script>

<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/transaction.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>