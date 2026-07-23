<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */


?>


<h1>Booking Confirmation: <?= $model->booking_number  ?> </h1>

<p>For more details visit <a href="http://portal.frego.com.mx" >http://portal.frego.com.mx</a></p>


<?php  if(!empty($model->clientModel->notification_notes)): ?>

    <p>
        <?= nl2br(Html::encode($model->clientModel->notification_notes));  ?>
    </p>

<?php endif; ?>

<div class="container"  >

<?= DetailView::widget([
            'options' =>[ 'class' => 'details center', 'style' => 'width:60%' ],
            'model' => $model,
            'attributes' => [
            'booking_id',
            'booking_number',
            ['label'=>'Vessel',  'value' => $model->vesselModel->vessel_name ],
            ['label'=>'Carrier', 'value' => $model->carrierModel->name ],
            'HB',
            ['label'=>'Customer', 'value'=>  $model->clientModel->fullName ],
            'customer_reference',
            ['label'=>'POL', 'value'=>  $model->loadingPortModel->port_name ],
            'loading_EDT:date',
            'dicharge_port', 
            'dicharge_ETA:date',
            'set_point',
            'final_destination',
            'created_at:date',
            [ 'label'=> 'Pick Up Place', 'value' => $model->pickupPlaceModel->name ],
        ],
]) ?>


<?php if($containers){  ?>

<h1><?= Html::encode($this->title) ?>Containers</h1>

    <?= GridView::widget([
                'options' =>[ 'class' => 'details center cien', 'style' => 'width:80%' ],
                'dataProvider' => $containers,
                'layout' => '{items}',
                'filterModel' => null,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'comodity',
                    [
                      'label' => 'Container Type', 
                      'value' => function ($model, $key, $index, $widget) { 
                          return $model->quantity.'x'.$model->type->container_name; 
                      },
                    ],
                    'seal',
                    'number',
                ],
    ]); ?>

 <?php } ?>


</div>