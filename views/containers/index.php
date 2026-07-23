<?php

use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ContainersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Containers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="containers-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Containers', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'container_ID',
            'quantity',
            'comodity',
            'container_type',
            'booking',
            'number',
            'seal',
            //'created_by',
            //'modified_by',
            //'created_at',
            //'modified_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
