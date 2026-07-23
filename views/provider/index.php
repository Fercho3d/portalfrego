<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ProviderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Providers for' .$booking->booking_number;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-index">

<?= \yii\helpers\Html::a( 'Back', ['/booking'], ['class' => 'btn btn-success tran-back']) ?>
<br style="margin-top: 10px">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Provider', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'provider_id',
            'rfc',
            'fullName',
            'address',
            'state',
            //'city',
            //'email:email',
            //'postal_code',
            //'phone',
            //'cretated_by',
            //'modified_by',
            //'created_at',
            //'modified_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
