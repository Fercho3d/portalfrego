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
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment request' ;
$this->params['breadcrumbs'][] = $this->title ;

/*Admin Settings*/
$actionLayout = '<div class="btn-group" cl >{bill}</div>';

$addBtn =    
  Html::a(
    '<i class="glyphicon glyphicon-bell" ></i> Request for Payment', 
    '#request' ,
      [
          'type'=>'button', 
          'title'=>'Request for Payment', 
          'class'=>'btn btn-primary',
          'id' => 'requestForPayment',
          'data-pjax'=> 0 
      ]
    ); 
    
    $accountList = Account::getList();

?>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <div class="row"  >
        <div class="col-md-12" >
        <?= GridView::widget([
          'dataProvider' => $dataProvider,
          'filterModel' => $searchModel,
          'showFooter' => false,
           'toolbar' => [ 
            [
              'content'=> $addBtn
            ],
          ], 
          'columns' => [
            [
               'label' =>'Booking',
               'attribute' =>'booking_number',
               'format' =>'raw',
               'contentOptions'=>['style'=>'vertical-align: middle ;'],
               'value' => function ($model, $key, $index, $widget) {
                 return $model->booking_number;
               }
            ],
            /*[
              'attribute' => 'type',
              'vAlign' => 'middle',
              'width' => '125px',
              'value' => function($model, $key, $index) {
                  return $model->typeText; 
              },
              'filterType' => GridView::FILTER_SELECT2,
              'filter'=> ['' => 'All',  1 => 'Bill', 0 =>  'Invoice', 2 =>  'Credit Bill', 3 =>  'Credit Note' ],
              'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true ],
                ],
             ],*/
            [
                'attribute' => 'dates', 
                'label' => 'Date', 
                'vAlign' => 'middle',
                'width' => '230px',
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'value' => function($model, $key, $index, $widget) { 
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
                'pageSummary' => 'Summary',
                'pageSummaryOptions' => ['class' => 'text-right'],
                'contentOptions'=>['style'=>'vertical-align: middle;'],
              ],
              // [
              //   'label'=>'Applied to',
              //   'attribute'=>'appliedTo',
              //   'value'=> function($model, $key, $index, $widget){
              //     return $model->tran_type == "0" ? $model->customerModel->fullName : $model->vendorModel->fullName;  
              //   }
              // ],
              [
                'label' =>'Ccy',
                'attribute' =>'currency',
                'width' => '30px',
              ],
              [
                'label' => 'Amount',
                'attribute' => 'amount_original',
                'format' => 'raw',
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'value'=> function($model){
                  return Yii::$app->formatter->asCurrency($model->amount_original, 'USD');
                }
              ],
              [ 
                'label' => 'TDC',
                'attribute' => 'exchange_value',
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'format' => ['currency', 'MXN'],
              ],
              [
                'label' => 'Sub 0%', 
                'attribute' => 'sub_0_mxn',
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'format' => ['currency', 'MXN'],
                'pageSummary' => true,
                'value' => function ($model, $key, $index, $widget) { 
                      return $model->sub_0_mxn;
                 },
              ],             
              [
                'label' => 'Sub 16%',
                'attribute' => 'sub_16_mxn',
                'format' => ['currency', 'MXN'],
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'pageSummary' => true,
                'value' => function ($model, $key, $index, $widget) { 
                      return $model->sub_16_mxn;
                },
              ],         
              [
                'label' => 'VAT 16%',
                'attribute' => 'tax_16_mxn',
                'format' => ['currency', 'MXN'],
                'contentOptions'=>['style'=>'vertical-align: middle;'],
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
                'label' => 'Withholding',
                'attribute' => 'tax_ret_mxn',
                'format' => ['currency', 'MXN'],
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'pageSummary' => true,
                'value' => function ($model, $key, $index, $widget) { 
                      return $model->tax_ret_mxn;
                 },
              ],     
              [
                'label' => 'Total',
                'format' => ['currency', 'MXN'],
                'contentOptions'=>['style'=>'vertical-align: middle;'],
                'pageSummary' => true,
                'pageSummaryFunc' => GridView::F_SUM,
                'value' => function ($model, $key, $index, $widget) { 
                      return $model->total_amount;
                  },
                ],
                [
                  'label'=>'Docs',
                  'format' =>'raw',
                  'contentOptions'=>['style'=>'vertical-align: middle;'],
                  'value'=>function ($model, $key, $index, $widget){
                    return !empty($model->pdf_attach) && empty($model->xml) ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                  },
                ],
                       
                [
                  'label'=>'Request',
                  'format' =>'raw',
                  'attribute' =>'payment_request',
                  'contentOptions'=>['style'=>'vertical-align: middle;'],
                  'value'=> function ($model, $key, $index, $widget){
                    return $model->payment_request == 1 ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                  },
                  'filterType' => GridView::FILTER_SELECT2,
                  'filter'=>["0"=>"No requested", "1"=> "Requested" ],
                  'filterWidgetOptions' => [
                      'pluginOptions' => ['allowClear' => true ],
                  ],
                  'filterInputOptions' => ['placeholder' => 'All' ],
                  'group' => false,  // enable grouping
                  'subGroupOf' => 0, // supplier column index is the parent group,,
                ],        
                [
                  'label'=>'Paid Status',
                  'attribute' => 'paid',
                  'format' =>'raw',
                  'value'=> function ($model, $key, $index, $widget){
                      return $model->paidStatus;
                    },
                  'filterType' => GridView::FILTER_SELECT2,
                  'filter'=>["0"=>"Unpaid", "2"=> 'Partial Paid', "1"=> "Full Paid" ],
                  'filterWidgetOptions' => [
                      'pluginOptions' => ['allowClear' => true ],
                  ],
                  'filterInputOptions' => ['placeholder' => 'All' ],
                  'group' => false,  // enable grouping
                  'subGroupOf' => 0, // supplier column index is the parent group,
               ],
                [
                'class' => 'yii\grid\ActionColumn',
                 'template' => $actionLayout,
                 'buttons' =>[
                      'bill' =>  function ($url, $model) {
                       return Html::a(
                          '<span class="glyphicon glyphicon-upload" ></span> Upload Docs',
                              [
                                  'update', 
                                  'id' => $model->transc_id ],
                              [
                                  'title' => 'Upload Bill',
                                  'class' => 'btn btn-success btn-xs tranForm',
                                  'data-pjax'=>0 
                                  //'data-toggle'=>'modal',
                                  //'data-target'=>'#form',
                              ]
                          );
                      },       
                 ]  
              ],
              [
                'class' => 'kartik\grid\CheckboxColumn',
                'attribute'=>'transc_id',
                'name' => 'selected',
                'rowSelectedClass'=> 'success',
                'checkboxOptions' => function ($model, $key, $index, $column) {
                 if($model->paid == 0) {
                       return ['value' => $model->transc_id ];
                  }
                  return ['disabled' => true ];
                }
              ],
       ],
      'containerOptions' => ['style'=>'overflow: hidden'], // only set when $responsive = false
      'pjax' => true,
      'pjaxSettings' => [
        'neverTimeout' => true,
      ],
      'bordered' => true,
      'striped' => true,
      'condensed' => true,
      'responsive' => false,
      'hover' => true,
      'headerRowOptions' => ['class' => 'kartik-sheet-style'],
      'filterRowOptions' => ['class' => 'kartik-sheet-style'],
      'showPageSummary' => true,
      'panel' => [
          'type' => GridView::TYPE_DEFAULT,
          'heading'=> '<strong><i class="fa fa-book" ></i> All Bills</strong>',
       ],
      ]); ?>
  </div>
</div>
<div class="hidden" >
<div id="request" title="Request for payment..."  >
  <div class="row" >
    <div class="col-md-12" >
    <div class="text-success" >
      <h6>loading</h6>
       <img src="/web/img/ajax-loader.gif" />
    </div>
  </div>
</div>
</div>
</div>

