<?php
	use yii\grid\GridView;
	use yii\helpers\Html;
?>
<div class="col-md-10" >

	<p style="margin-top: 15px" > 
		<?=  $booking->locked ? '' : Html::a('Add Container', ['/containers/create', 'booking' => $booking->booking_id ], ['class' => 'btn btn-success']) ?>
	</p>
			    
	<?= GridView::widget([
	    'dataProvider' => $dataProvider,
	    'filterModel' => null,
	    'columns' => [
	        ['class' => 'yii\grid\SerialColumn'],
	        'comodity',
	        [
	          'label' => 'Container Type', 
	          'value' => function ($model, $key, $index, $widget) {  
	              return $model->quantity.'x'.$model->type->container_name; 
	          },
	        ],
	        'seal',
	        'number',
	        [
	        'class' => 'yii\grid\ActionColumn',
	        'template' => $booking->locked ? '' : '<div class="btn-group" style="width:100px" >{update}{delete}</div>',
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