<?php

use yii\helpers\Html;
use kartik\grid\GridView;


/* @var $this yii\web\View */
/* @var $searchModel app\models\PaymentRequestSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row" >
    <div class="payment-request-index col-md-12">
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
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Request',
                    'format' => 'raw',
                     'value' => function ($model){
                        return str_pad($model->request_id,3,'0', STR_PAD_LEFT);
                     }
                ],
                [
                    'label' => 'Number',
                    'format' => 'raw',
                     'value' => function ($model){
                        return str_pad($model->number,10,'0', STR_PAD_LEFT);
                     }
                ],
                'amount',
                 [
                   'label'=>'Paid',
                   'format' =>'raw',
                   'attribute' =>'paid',
                   'contentOptions'=>['style'=>'vertical-align: middle;'],
                   'value'=> function ($model, $key, $index, $widget){
                     return $model->paid == 1 ? '<span class="text-success glyphicon glyphicon-ok"></span>':'<span class="text-danger glyphicon glyphicon glyphicon-remove" >';
                   },
                   'filterType' => GridView::FILTER_SELECT2,
                   'filter'=>["0"=>"Unpaid", "1"=>"Paid" ],
                   'filterWidgetOptions' => [
                       'pluginOptions' => ['allowClear' => true ],
                   ],
                   'filterInputOptions' => ['placeholder' => 'All' ],
                   'group' => false,  // enable grouping
                   'subGroupOf' => 0, // supplier column index is the parent group,
                 ],
                [
                    'attribute' => 'dates', 
                    'label' => 'Date', 
                    'vAlign' => 'middle',
                    'width' => '250px',
                    'format' => 'date',
                    'contentOptions'=>['style'=>'vertical-align: middle;'],
                    'value' => function($model, $key, $index, $widget) { 
                        return $model->date; 
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
                  'class' => 'yii\grid\ActionColumn',
                   'template' => '<div class="btn-group" >{details}{download}</div>',
                   'buttons' =>[
                        'details' =>  function ($url, $model) {
                         return Html::a(
                            '<span class="glyphicon glyphicon-eye-open" ></span> Details',
                                [
                                    'view', 
                                    'id' => $model->request_id ],
                                [
                                    'title' => 'View Details',
                                    'class' => 'btn btn-success open-request',
                                    'data-pjax'=> 0
                                ]
                            );
                        },                    
                        'download' =>  function ($url, $model) {
                         return Html::a(
                            '<span class="glyphicon glyphicon-save-file" ></span> Download',
                                [
                                    'document', 
                                    'id' => $model->request_id ],
                                [
                                    'title' => 'Download Document',
                                    'class' => 'btn btn-danger document',
                                    'data-pjax'=> 0
                                ]
                            );
                        },       
                   ]  
                ],
            ],
            'containerOptions' => ['style'=>'overflow: hidden'], // only set when $responsive = false
            'pjax' => false,
            'pjaxSettings' => [
              'neverTimeout' => true,
            ],
            'bordered' => false,
            'striped' => false,
            'condensed' => true,
            'responsive' => true,
            'hover' => true,
            'headerRowOptions' => ['class' => 'kartik-sheet-style'],
            'filterRowOptions' => ['class' => 'kartik-sheet-style'],
            'showPageSummary' => true,
            'panel' => [
                'type' => GridView::TYPE_DEFAULT,
                'heading'=> '<strong><i class="fa fa-book"></i>Payment Request</strong>',
             ],
        ]); ?>
    </div>
</div>

<?php if(isset($_GET['id']) && empty($_GET['PaymentRequestSearch'])  ): ?>
<div id="pdf" class="modal fade" data-keyboard="false" data-backdrop="static" >
     <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-body" >
                <object data="/web/payment-request/document?id=<?= $open ?>" type="application/pdf" width="100%" height="100%">
                  <p>Paymet Request<a href="/web/payment-request/document?id=<?= $open ?>">to the PDF!</a></p>
                </object>
            </div>
            <div class="modal-footer" >
                <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
            </div>
        </div>
      </div>
</div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('#pdf').modal();
        });
    </script>

<? endif; ?>

