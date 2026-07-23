<?php

use yii\helpers\Html;
use yii\grid\GridView;


/* @var $this yii\web\View */
/* @var $model app\models\Transaction */

$this->title = 'Transaction: ' . $model->tran_number;
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['/booking' ]];
$this->params['breadcrumbs'][] = ['label' => 'Transactions', 'url' => ['index', 'booking' => $model->booking ]];
$this->params['breadcrumbs'][] = ['label' => $model->tran_number, 'url' => ['view', 'id' => $model->transc_id]];
$this->params['breadcrumbs'][] = 'View';
?>
<h1><?= Html::encode($this->title) ?></h1>

<ul class="nav nav-tabs">
  <li class="active"  ><a data-toggle="tab" href="#transaction">Transaction</a></li>
  <li><a href="#charges" data-toggle="tab">Charges</a></li>
</ul>

<div class="tab-content" >
<div id="transaction"  class="tab-pane fade in active" >
  <div class="col-md-4">
      <?= $this->render('_form', [
          'model' => $model,
          'showPreview'=> true
      ]) ?>
  </div>
</div>
<div id="charges" class="tab-pane" >
	  
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn' ],
            'ChargeType',
            'taxName',
            [
               'label' => 'Pre-Paid',
               'value' => function ($model) {
                   return $model->getPrepaidText();
               }
            ],
            'description',
            'quantity:decimal',
            [
               'attribute' => 'unit',
               'format' => [
                   'decimal'
                ],
            ],
            [
               'attribute' => 'quantity',
               'format' => [
                   'decimal'
                ],
            ],
            [
               'attribute' => 'price',
               'format' => [
                   'currency',
                   'USD',
                   [
                       \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                       \NumberFormatter::MAX_FRACTION_DIGITS => 0,
                   ]
                ],
            ],
            [
             'attribute' => 'tax_rate',
             'format' => [
                 'decimal'
              ],
            ],
            [
             'attribute' => 'tax_retention',
             'format' => [
                 'decimal'
              ],
            ],         
            [
               'attribute' => 'retentionAmount',
               'format' => [
                   'currency',
                   'USD',
                    [
                       \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                       \NumberFormatter::MAX_FRACTION_DIGITS => 0,
                    ]
                ],
            ],           [
               'attribute' => 'taxAmount',
               'format' => [
                   'currency',
                   'USD',
                    [
                       \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                       \NumberFormatter::MAX_FRACTION_DIGITS => 0,
                    ]
                ],
            ],
            [
            'attribute' => 'totalAmount',
               'format' => [
                   'currency',
                   'USD',
                    [
                       \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                       \NumberFormatter::MAX_FRACTION_DIGITS => 0,
                    ]
                ],
            ]
        ],
    ]); ?>
</div>
</div>
<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/transaction.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>

