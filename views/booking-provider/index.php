<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\ButtonDropdown;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'My Bookings';
$this->params['breadcrumbs'][] = $this->title  ;
?>
<div class="booking-index col-md-12" >

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'toolbar' => [
        '{export}',
        '{toggleData}'
        ],
        'columns' => [
            'booking_id',
            'booking_number',
            //'client_name',
            'vessel_name',
            'port_name',
            'loading_EDT:date',
            'dicharge_port',
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
                'class' => 'yii\grid\ActionColumn',
                'headerOptions' => ['style' => 'width:100px' ],
                'template' => '{shipment}',
                'buttons' => [
                    'shipment' =>  function ($url, $model) {
                     return Html::a(
                        '<span class="glyphicon glyphicon-duplicate" ></span> Add Bills',
                            [
                                '/transaction/index', 
                                'booking' => $model->booking_id ],
                            [
                                'title' => 'Edit Booking',
                                'class' => 'btn btn-danger btn-sm' 
                            ]
                        );
                    },           
                ],
            ],
        ],

        'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
        'pjax' => true,
        'bordered' => false,
        'striped' => false,
        'condensed' => true,
        'responsive' => false,
        'hover' => true,
        'floatHeader' => true,
        'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
        'showPageSummary' => false,
        'panel' => [
            //'type' => GridView::TYPE_PRIMARY,
           // 'heading'=> '<i class="fas fa-book"></i> My Bookings',
        ],

    ]); ?>


</div>
