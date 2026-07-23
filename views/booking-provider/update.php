<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */

$this->title = 'Booking: ' . $model->booking_number;
$this->params['breadcrumbs'][] = ['label' => 'Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->booking_number, 'url' => ['view', 'id' => $model->booking_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<?= \yii\helpers\Html::a( 'Back', ['/booking'], ['class' => 'btn btn-success']) ?>

<ul class="nav nav-tabs" style="margin-top: 15px" >
  <li class="active"  ><a data-toggle="tab" href="#booking">Bookign</a></li>
  <li><a href="#containers" data-toggle="tab">Containers</a></li>
</ul>

<div class="tab-content" >
	<div id="booking"  class="tab-pane fade in active" >
		<div class="booking-update" >
		    <h1><?= Html::encode($this->title) ?></h1>
		  	  	
		    <?= $this->render('_form', [
		        'model' => $model,
		        'showPreview' => true
		    ]) ?>
		    
		</div>
	</div>

	<div id="containers" class="tab-pane" >

	<div class="col-md-4 ">
		<p style="margin-top: 15px" > 
		<?= Html::a('Create Containers', ['/containers/create', 'booking' => $model->booking_id ], ['class' => 'btn btn-success']) ?>
		</p>
	 
	    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

	    <?= GridView::widget([
	        'dataProvider' => $dataProvider,
	        'filterModel' => null,
	        'columns' => [
	            ['class' => 'yii\grid\SerialColumn'],
	           // 'container_ID',
	            'comodity',
	            [
	              'label' => 'Container Type', 
	              'value' => function ($model, $key, $index, $widget) { 
	                  return $model->quantity.'x'.$model->type->container_name; 
	              },
	            ],
	            //'booking',
	            'seal',
	            'number',
	            //'created_by',
	            //'modified_by',
	            //'created_at',
	            //'modified_at',

	            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group" style="width:100px" >{update}{delete}</div>',
                'buttons' => [
                    'update' =>  function ($url, $model) {                        
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil" ></span>',
                            [
                                '/containers/update', 
                                'id' => $model->container_ID ],
                            [
                                'title' => 'Update',
                                'class' => 'btn btn-success'
                            ]
                        );
                    },                  
                    'delete' =>  function ($url, $model) {                        
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash" ></span>',
                            [
                                '/containers/delete', 
                                'id' => $model->container_ID ],
                            [
                                'title' => 'Continuity',
                                'class' => 'btn btn-danger'
                            ]
                        );
                    },  
                ],
            ],
	        ],
	    ]); ?>
	    </div>	
	</div>
</div>

<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/transaction.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>