<?php

use yii\helpers\Html;
use app\models\User;
use kartik\select2\Select2;
use kartik\grid\GridView;
use kartik\editable\Editable;
use kartik\daterange\DateRangePicker;
use kartik\grid\EditableColumn;
use kartik\checkbox\CheckboxX;

use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use app\models\Account;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transactions for '.$booking->booking_number ;
$this->params['Breadcrumbs'][] = $this->title ;

/*Admin Settings*/
$actionLayout = User::isUserAdmin(Yii::$app->user->identity->username) ? $adminLayout : $operatorLayout;

$addBtn = User::isUserAdmin(Yii::$app->user->identity->username) ?   
                Html::a(
                '<i class="glyphicon glyphicon-bell"></i> Seal', 
                '#export' ,
                  [
                  'type'=>'button', 
                  'title'=>'Add Bill', 
                  'class'=>'btn btn-primary',
                  'id' => 'exportXML'
                ]).
                Html::a(
                '<i class="fa fa-times"></i> Cancel', 
                '#export',[
                    'type'=>'button', 
                    'title'=>'Add Bill', 
                    'class'=>'btn btn-danger',
                    'id' => 'cancelCFDI'
              ]).         
              Html::a(
                '<i class="fa fa-dollar" ></i> Pay', 
                '#export' ,
                  [
                      'type'=>'button', 
                      'class'=>'btn btn-success',
                      'id' => 'create_payment_request_invoice'
                 ]). 

              Html::a(
                '<i class="fa fa-envelope" ></i> Send Docs', 
                '#reesend' ,
                  [
                      'type'=>'button', 
                      'class'=>'btn btn-warning',
                      'id' => 'reesend'
                 ])
  : '';

$accountList = Account::getList();

?>

<div class="transaction-index col-md-12" >
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => false,
         'toolbar' => [ 
        [
          'content'=> $addBtn
        ],
          '{export}',
          '{toggleData}'
        ], 
        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => '---'],
        'columns' => [
       [ 
            'label' => 'Booking #',
            'attribute' => 'booking_number',
            'vAlign' => 'middle',
            'width' => '125px', 
             'format' => 'raw',
            'value' => function($model, $key, $index) {
              return $model->booking_number;
            }
          ],
          [
              'attribute' => 'dates', 
              'label' => 'Invoice Date', 
              'vAlign' => 'middle',
              'width' => '180px',
              'format' => 'date',
              'value' => function ($model, $key, $index, $widget) { 
                  return $model->tran_date; 
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
               'width' => '40px',
               'value'=> function ($model, $key, $index, $widget) {
                 return $model->tran_number;
               }
            ],  
            // [
            //   'label'=>'Applied to',
            //   'attribute'=>'appliedTo',
            //   'value'=> function($model, $key, $index, $widget){
            //     return $model->customerModel->fullName ; 
            //   }
            // ],         
            // [
            //   'label'=>'Email',
            //   'attribute'=>'email',
            //   'value'=> function($model, $key, $index, $widget){
            //      return $model->customerModel->email ;
            //   }
            // ],
            [
              'attribute' =>'currency',
              'width' => '30px',
            ],
            [
              'label' => 'Amount Original',
              'attribute' => 'amount_original',
              'format' => ['decimal', 2],
              'value'=> function($model){
               return $model->amount_original;
              }
            ],
            [ 
              'label' => 'TDC',
              'attribute' => 'exchange_value',
              'format' => ['decimal', 4],
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
                'label' => 'CFDI',
                'attribute' =>'seal',
                'format' => 'raw',
                'value' => function ($model, $key, $index){
                  return $model->cancelled == 1 ? 
                  '<span class="text-danger" >' . $model->seal . '(cancelled)</span>':
                  '<span class="text-success">' . $model->seal . '</span>';
                }
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
              // [
              //   'label'=>'Paid',
              //   'format' =>'raw',
              //   'value'=> function ($model, $key, $index, $widget){
              //     return $model->getPaidStatus();
              //   }
              // ],
              [
                'label'=>'Processed',
                'attribute'=> 'processed', 
                'format' => 'raw',
                'visible' =>  \Yii::$app->user->identity->client->activate_processed,
                'value' => function ($model, $key, $index){
                  if(!empty($model->transc_id)){
                    return CheckboxX::widget([
                      'name'=> 'processed_'.$model->transc_id,
                      'value' => $model->processed,
                      'disabled' =>  $model->processed,
                      'options'=>['id'=> 'processed_'.$model->transc_id, 'data-tran'=> $model->transc_id, 'class'=>'processed'],
                      'pluginOptions'=>[ 'threeState'=> false ] 
                  ]);
                } 
                }
              ],
             
              [
                'class' => 'yii\grid\ActionColumn',
                 'template' => '<div class="btn-group" style="width:70px" >{files}</div>',
                 'buttons' => [
                    'files' => function($url,  $model){
                        
                        return !empty($model->pdf_attach && $model->xml_attach)? 
                            HTML::a(
                              '<i class="glyphicon glyphicon-save-file" ></i>',
                              $model->getPdfLink(),
                              [
                                'class' => 'btn btn-danger btn-xs document',
                                'target' =>'_blank',
                                'title' => 'Download PDF',
                                'data-pjax'=> 0,
                               
                              ]
                          ).
                          HTML::a(
                              '<i class="glyphicon glyphicon-save-file" ></i>',
                              $model->getXmlLink(),
                              [
                                'class' => 'btn btn-primary btn-xs download',
                                'target' =>'_blank',
                                'title' => 'Download XML',
                                'data-pjax'=> 0,
                              ]
                          ) : null;
                    }
                 ]  
              ],
     ],
    'pjax' => true,
    'pjaxSettings' => [
      'neverTimeout' => true,
    ],
    'bordered' => true,
    'striped' => true,
    'bootstrap' =>true,
    'condensed' => true,
    'responsive' => true,
    'hover' => true,
    'showPageSummary' => true,
    'panel' => [
            'type' => GridView::TYPE_PRIMARY,
            'heading'=> '<i class="fas fa-book"></i> Invoice Transactions',
        ],
    ]); ?>
</div>

<script type="text/javascript">

    $('.processed').each(function(){
      var  element = $(this); 
      element.change(function(){
      var $id = $(this).data('tran');

        $.ajax({
          method: 'GET',
          url:'/web/transaction/set-processed',
          data:{'id': $id },
        }).done(function(data){
          console.log(data);
          if(data.success){
            element.prev().addClass('cbx-disabled');
            element.prev().unbind();
            element.prop( "disabled", true );
          }else{
            alert('There was an error saving this field, contact to the administrator')
          }
        });

      });

    })

 

    </script>