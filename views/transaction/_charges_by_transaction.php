<?php 

use kartik\grid\GridView;
use yii\helpers\Html;

?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => null,
            'class' => 'table table-condensed',
            'columns' => [
                // ['class' => 'yii\grid\SerialColumn' ],
                'ChargeType',
                'description',
                'quantity:decimal',
                [
                   'attribute' => 'price',
                  'pageSummary' => true,
                   'format' => [
                       'currency',
                       'USD'
                    ],
                ], 
                [
                   'attribute' => 'taxAmount',
                   'label' => 'Tax',
                   'pageSummary' => true,
                   'format' => [
                       'currency',
                       'USD'
                    ],
                ],
                [
                   'attribute' => 'retentionAmount',
                   'label' => 'Withholding',
                    'pageSummary' => true,
                   'format' => [
                       'currency',
                       'USD'
                    ],
                ],
                [
                'attribute' => 'totalAmount',
                'label' => 'Total',
                  'pageSummary' => true,
                   'format' => [
                       'currency',
                       'USD'
                    ],
                ],
                [
                  'label'=>'Price Confirmed',
                  'format' =>'raw',
                  'attribute' =>'price_confirmation',
                  'contentOptions'=>['style'=>'vertical-align: middle;'],
                  'value'=> function ($model, $key, $index, $widget){
                    return $model->price == $model->price_confirmation ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                  }, 
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '<div class="btn-group" style="width:100px" >{update}</div>',
                    'buttons' => [
                        'update' =>  function ($url, $model) use($disabled) {                        
                            return !$disabled ? Html::a(
                                '<span class="glyphicon glyphicon-pencil" ></span> Confirm Price',
                                [
                                    '/charge/update', 
                                    'id' => $model->charge_id ],
                                [
                                    'title' => 'Update',
                                    'class' => 'btn btn-warning btn-xs charForm',
                                ]
                            ):'';
                        },                  
                    ],
                ],
            ],
            'pjax' => true,
            'bordered' => true,
            'hover' => true,
            'striped' => true,
            'bootstrap' =>true,
            'condensed' => true,
            'responsive' => false,
            'hover' => true,
            'showPageSummary' => true,
        ]); ?>