<?php

use yii\helpers\Html;
use app\models\User;
use kartik\select2\Select2;
use kartik\grid\GridView;
use kartik\editable\Editable;
use kartik\daterange\DateRangePicker;

use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use app\models\Account;


/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */



/*Admin Settings*/
$adminLayout = '<div class="action" >{update}</div>';


$accountList = Account::getList();

?>



<div class="transaction-index col-md-12" >

    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true,
        'toolbar' => [],
        'columns' => [
          [ 
            'label' => 'Booking #',
            'attribute' => 'booking_number',
            'vAlign' => 'middle',
            'width' => '125px',
            'value' => function($model, $key, $index) {
              return $model->bookingModel->booking_number; 
            },
          ],
          [
            'attribute' => 'type',
            'vAlign' => 'middle',
            'width' => '50px',
            'value' => function($model, $key, $index) {
                return $model->typeText; 
            },
            'filterType' => GridView::FILTER_SELECT2,
            'filter'=> ['' => 'All',  1 => 'Bill', 0 =>  'Invoice', 2 =>  'Credit Bill', 3 =>  'Credit Note' ],
            'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true ],
              ],
          ],
          [
              'attribute' => 'dates', 
              'label' => 'Date', 
              'vAlign' => 'middle',
              'width' => '50px',
              'value' => function ($model, $key, $index, $widget) { 
                  return date('d/m/Y', strtotime($model->tran_date) ); 
              },
              'filterType'=>GridView::FILTER_DATE_RANGE,
              'filterWidgetOptions' => [
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
              'attribute' => 'tran_number',
              'pageSummary' => 'Summary & Revenue',
              'pageSummaryOptions' => ['class' => 'text-right'],
            ],
            [
              'attribute' =>'currency',
              'width' => '30px',
            ],
            [
              'label' => 'Amount',
              'attribute' => 'amount_original',
              'format' => 'raw',
              'value'=> function($model){
                return Yii::$app->formatter->asCurrency($model->amount_original,'USD');
              }
            ],
            [ 
              'label' => 'TDC',
              'attribute' => 'exchange_value',
              'format' => ['decimal', 2],
            ],
            [
              'label' => 'Sub 0%', 
              'attribute' => 'sub_0_mxn',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->sub_0_mxn;
               },
            ],             
            [
              'label' => 'Sub 16%',
              'attribute' => 'sub_16_mxn',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->sub_16_mxn;
              },
            ],         
            [
              'label' => 'VAT 16%',
              'attribute' => 'tax_16_mxn',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->tax_16_mxn;
               },
            ],
            [
              'attribute' => 'non_dec',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->non_dec;
               },
            ],        
            [
              'label' => 'Ret VAT',
              'attribute' => 'tax_ret_mxn',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->tax_ret_mxn;
               },
            ],     
             [
              'label' => 'Total',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'pageSummaryFunc' => GridView::F_SUM,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->total_amount;
                },
              ],            
              [
              'label' => 'Total Natural Amount',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'pageSummaryFunc' => GridView::F_SUM,
              'value' => function ($model, $key, $index, $widget) { 
                    return $model->total_natural_amount;
                },
              ],
              [
                'label'=>'PDF',
                'format' =>'raw',
                'value'=>function ($model, $key, $index, $widget){
                  return !empty($model->pdf_attach) ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                }
              ],         
              [
                'label'=>'XML',
                'format' =>'raw',
                'value'=> function ($model, $key, $index, $widget){
                  return !empty($model->pdf_attach) ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                }
              ],         
              [
                'label'=>'Requested',
                'format' =>'raw',
                'value'=> function ($model, $key, $index, $widget){
                  return $model->payment_request == 1 ? 
                  '<span class="text-success glyphicon glyphicon-ok"></span>'
                  :'<span class="text-danger glyphicon glyphicon glyphicon-remove" >'

                  ;
                }
              ],        
              [
                'label'=>'Paid',
                'format' =>'raw',
                'value'=> function ($model, $key, $index, $widget){
                  return $model->paid == 1 ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                }
              ],
             [
              'class' => 'yii\grid\ActionColumn',
               'template' => $adminLayout,
               'buttons' =>[
                    'update' =>  function ($url, $model) {
                     return Html::a(
                        '<span class="glyphicon glyphicon-eye-open" ></span>',
                            [
                                'transaction/update', 
                                'id' => $model->transc_id ],
                            [
                                'title' => 'View',
                                'class' => 'btn btn-primary tranForm',
                            ]
                        );
                    },       
               ]  
            ],
     ],
    'pjax' => true,
    'pjaxSettings' => [
      'neverTimeout' => true,
    ],
    'bordered' => true,
    'striped' => true,
    'condensed' => true,
    'responsive' => true,
    'hover' => true,
    'showPageSummary' => true,
    'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading'=> '<i class="fa fa-book"></i> Selected Transactions',
        ],
    ]); ?>

    

</div>