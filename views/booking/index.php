<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\Client;
use yii\bootstrap\ButtonDropdown;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;

use app\models\Vessel;
use app\models\LoadingPorts;

$vesselList = Vessel::getList();
$portList = LoadingPorts::getList();

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Bookings';
$this->params['breadcrumbs'][] = $this->title;



$importBtn = Html::a(
    '<i class="glyphicon glyphicon-plus" ></i> Add Import',
        ['create', 
        'booking_type' => 1 ],
         [
          'type'=>'button', 
          'title'=>'Add new booking Import', 
          'class'=>'btn btn-success'
        ]);



$access = \Yii::$app->user->identity->access;
$role =   \Yii::$app->user->identity->role;


$buttons = $role == 13 ? $importBtn: '' ;


$toolbar =[
  [
   'content'=> $importBtn
  ],
  '{export}',
  '{toggleData}'
];

$before = $role == 13 ? null : ['class'=>'grid_panel_remove'];

$toolbar = $role == 13 ? $toolbar: false ;
  
  $actionTemplate[12] = '<div class="btn-group " style="width:80px !important;" >{shipment}</div>' ; // Client Read Only
  $actionTemplate[16] = '<div class="btn-group " style="width:80px !important;" >{shipment}</div>' ; // Client /Custom Brocker
  $actionTemplate[13] = '<div class="btn-group " style="width:160px !important;">{shipment}{update}{delete}</div>' ; // Client Associate / Editor
  $actionTemplate[14] = '<div class="btn-group " style="width:160px !important;" >{transactions}</div>' ; // Custom Brocker( Agente aduanal )
  $actionTemplate[15] = '<div class="btn-group " style="width:160px !important;" >{transactions}</div>' ; // Custom Brocker( Agente aduanal ) 

echo $role;

?>
<div class="row">
<div class="booking-index col-md-12" style="margin-top: -25px" >

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter'=>false,
        'toolbar' => $toolbar,
        'columns' => [
            'booking_id',
            'booking_number',
            [
                'label'=> 'Customer',
                'attribute'=> 'client',
                'width' => '180px', 
                'value' => function($model){
                    return $model->client_name;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter'=> $clientList ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true ],
                ],
                'filterInputOptions' => ['placeholder' => 'All' ],
                'group' => false,  // enable grouping
                'subGroupOf' => 0, // supplier column index is the parent group,
            ],
            'vessel_name',
            'port_name',
            'loading_EDT:date',
            [
                'attribute' =>'dicharge_port_id',
                'value' => function ($model){ 
                    return $model->dichargePort->name;
                }
            ],
            'dicharge_ETA:date',
            [
              'label' => 'Pick Up Date', 
              'attribute' => 'dates', 
              'vAlign' => 'middle',
              'width' => '240px',
              'value' => function ($model, $key, $index, $widget) { 
                
                  return empty($model->pickup_date)? '' : date('d/m/Y', strtotime($model->pickup_date) ); 
              },
              'filterType'=>GridView::FILTER_DATE_RANGE,
              'filterWidgetOptions' =>[
                     'attribute' => 'dates',
                     'presetDropdown' => true,
                     'convertFormat' => false,
                     'pluginOptions' => [
                      'separator' => ' - ',
                      'format' => 'DD/MM/YYYY',
                      'locale' => [
                             'format' => 'DD/MM/YYYY'
                            ],
                       ]
              ],
              'format' => 'raw'
            ],
            [
              'label'=> 'Pickup Place',
              'value' => function ($model, $key, $index, $widget) { 
                  return empty($model->pickupPlace->name ) ? ' update needed or no set' : $model->pickupPlace->name ; 
              },
            ],
            [
                'header'=> '<i class="fas fa-lock"></i>',
                'format' => 'raw',
                'value' => function ($model){
                    return $model->locked ? 
                    '<span class="lock lock-red" ><i class="fa fa-lock"></i></span>' : 
                    '<span class="lock lock-green" ><i class="fa fa-unlock"></i></span>';
                }
            ],
            [ 

              'class' => 'yii\grid\ActionColumn',
              'headerOptions' => ['style' => 'width:100px' ],
              'template' => $actionTemplate[$role],
              'buttons' => [
                    'shipment' =>  function ($url, $model) {
                     return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" ></span>',
                            [
                                '/booking/view', 
                                'id' => $model->booking_id ],
                            [
                                'title' => 'View Booking',
                                'class' => 'btn btn-primary' 
                            ]
                        );
                    },       
                    'update' => function ($url, $model){
                       return Html::a(
                        '<span class="glyphicon glyphicon-pencil" ></span>',
                            [
                                '/booking/update', 
                                'id' => $model->booking_id ],
                            [
                                'title' => 'Edit Booking',
                                'class' => 'btn btn-warning' 
                            ]
                        );
                    }, 
                    'delete' => function ($url, $model){
                       return $model->locked ? '' : Html::a(
                        '<span class="glyphicon glyphicon-trash" ></span>',
                            [
                                '/booking/delete', 
                                'id' => $model->booking_id ],
                            [
                                'title' => 'Delete Booking',
                                'class' => 'btn btn-danger' 
                            ]
                        );
                    }, 
                    'transactions' =>  function ($url, $model) {
                     return Html::a(
                        '<span class="glyphicon glyphicon-duplicate" ></span> Upload Bills',
                            [
                                '/transaction/index', 
                                'booking' => $model->booking_id ],
                            [
                                'title' => 'View Booking',
                                'class' => 'btn btn-danger' 
                            ]
                        );
                    },   
                ],
            ],
        ],

        'containerOptions' => ['style'=>'overflow: hidden'], // only set when $responsive = false
        'pjax' => true,
        'bordered' => false,
        'striped' => false,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'floatHeader' => false,
        'showPageSummary' => false,
        'panel' => [
            //'type' => GridView::TYPE_PRIMARY,
            'heading'=> '<i class="fas fa-book"></i> My Bookings',
             'beforeOptions'=> $before,

        ],

    ]); ?>


</div>
</div>