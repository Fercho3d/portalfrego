<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChargeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Charges';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="charge-index col-md-4">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Charge', ['create', 'transaction' => $_GET['transaction' ] ], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'charge_id',
            'transaction',
            'type',
            'tax_code',
            'description',
            'quantity:decimal',
            'unit:decimal',
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

            //'price:currency',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
