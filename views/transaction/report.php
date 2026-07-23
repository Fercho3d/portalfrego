<?php

use yii\helpers\Html;
use app\models\Transaction;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use \kartik\editable\Editable;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\TransactionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = 'Transactions Report ';
    $this->params['breadcrumbs'][] = $this->title;

?>



<div class="transaction-index" >

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showFooter' => true, 
        'columns' => [
        [
              'attribute' => 'tran_type', 
              'width' => '250px',
              'value' => function ($model, $key, $index, $widget) { 
                  return  $model->getTypeText();
              },
              'filterType' => GridView::FILTER_SELECT2,
              'filter'=>[ "1"=>"Bill", "0"=>"Invoice" ],
              'filterWidgetOptions' => [
                  'pluginOptions' => ['allowClear' => true ],
              ],
              'filterInputOptions' => ['placeholder' => 'All'],
              'group' => true,  // enable grouping
              'subGroupOf' => 0, // supplier column index is the parent group,
              'groupFooter' => function ($model, $key, $index, $widget) { // Closure method
                  return [
                      //'mergeColumns' => [ [0, 2] ], // columns to merge in summary
                      'content' => [              // content to show in each summary cell
                          8 => 'Summary (' . $model->getTypeText() . 's)',
                          9 => GridView::F_SUM,
                          10 => GridView::F_SUM,
                          11 => GridView::F_SUM,
                          //6 => GridView::F_SUM,
                      ],
                      'contentFormats' => [      // content reformatting for each summary cell
                          9 =>  ['format' => 'number', 'decimals' => 2],
                          10 =>  ['format' => 'number', 'decimals' => 0],
                          11 => ['format' => 'number', 'decimals' => 2],
                      ],
                      'contentOptions' => [      // content html attributes for each summary cell
                          9 => ['style' => 'text-align:right'],
                          10 => ['style' => 'text-align:right'],
                          11 => ['style' => 'text-align:right'],
                      ],
                      // html attributes for group summary row
                      'options' => ['class' => 'success table-success','style' => 'font-weight:bold;']
                  ];
              },
          ],
          [
              'attribute' => 'dates', 
              'vAlign' => 'middle',
              'width' => '240px',
              'value' => function ($model, $key, $index, $widget) { 
                  return date('d/m/Y', strtotime($model->tran_date) ); 
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
            'vendorName',
            'payTerms', 
            'creator',
            'modifier',
            //'tran_date:date',
            'booking_number',
            [
              'attribute' => 'tran_number',
              'pageSummary' => 'Summary & Revenue',
              'pageSummaryOptions' => ['class' => 'text-right'],
            ],
            [
              'attribute' => 'amount',
              'format' => ['decimal', 2],
              'pageSummary' => true,
              'pageSummaryFunc' => GridView::F_SUM
              //'pageSummary' => '<strong>$'. Transaction::getTotal($dataProvider->models, 'amount').'</strong>'
            ],
            [
               'attribute' => 'taxAmount',
               'format' => ['decimal', 2],
               'pageSummary' => true,
               'pageSummaryFunc' => GridView::F_SUM
              //'pageSummary' => '<strong>$'.Transaction::getTotal($dataProvider->models, 'taxAmount').'</strong>'
            ],            
            [
            'attribute' => 'retentionAmount',
               'format' => ['decimal', 2],
               'pageSummary' => true,
               'pageSummaryFunc' => GridView::F_SUM
               //'pageSummary' => '<strong>$'.Transaction::getTotal($dataProvider->models, 'retentionAmount').'</strong>'
            ],           
            [
            'attribute' => 'totalAmount',
            'format' => ['decimal', 2],
            'pageSummary' => true,
            'pageSummaryFunc' => GridView::F_SUM
                //'pageSummary' => '<strong>$'.Transaction::getTotal($dataProvider->models, 'totalAmount').'</strong>'
            ],
            //['class' => 'yii\grid\ActionColumn'],
        ],
    'containerOptions' => ['style'=>'overflow: auto'], // only set when $responsive = false
    'pjax' => true,
    'bordered' => true,
    'striped' => true,
    'condensed' => false,
    'responsive' => true,
    'hover' => true,
    'floatHeader' => true,
    'floatHeaderOptions' => ['scrollingTop' => $scrollingTop],
    'showPageSummary' => true,
    'panel' => [
        'type' => GridView::TYPE_PRIMARY
    ],
    ]); ?>


</div>
