<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $booking app\models\Booking 
Html::encode($this->title = $booking->loading_EDT=date("j F, Y", strtotime($date)))?></h5>
*/

//$this->title = $booking->booking_number; 
$this->params['breadcrumbs'][] = ['label' => 'My Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$fileLabels = $booking->attributeLabels();
$sysUrl = Yii::getAlias('@sysUrl');


foreach ($booking->DocsFields  as $key => $field) {
   $preview[$field]   =  [ $sysUrl .  '/web/uploads/bookings/'. $booking->booking_id .'/docs/'. $booking[$field] ];
}

$access = \Yii::$app->user->identity->access;


$readOnly = $access  == 11 ? true :  false ;


?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="container" > 

  <div class="row"> 
        <div class="col-md-4" >
          <h4>Booking <strong> <?= Html::encode($this->title = $booking->booking_number)?> </strong></h4>
        </div>
        <div class="col-md-3" >
          <h5>Loading on  <?= date("j F, Y", strtotime( $booking->loading_EDT )) ?></h5> 
          <h2><?= $booking->loadingPortModel->port_name ?></h2>  
        </div>
        <div class="col-md-3" >
          <h5>Dicharge on <?= date("j F, Y", strtotime($booking->dicharge_ETA )  )?></h5>
          <h2><?= $model->dichargePort->name ?></h2>
        </div>
  </div>

  <ul class="nav nav-tabs" >
      <li class="active"><a data-toggle="tab" href="#View">General View</a></li>
      <li><a href="#containers" data-toggle="tab" >Containers</a></li>
      <li><a href="#attachments" data-toggle="tab" >File Attachments</a></li>
      <li><a href="#tracking" data-toggle="tab" >Tracking</a></li>
      <li><a href="#shipment_instructions" data-toggle="tab" >Shipment Instructions</a></li>
  </ul>

  <div class="tab-content" >

    <div id="View" class="tab-pane fade in active" >

         <?= $this->render('_general_view', [
                'booking' => $booking,
         ]) ?>
    </div>

    <div id="containers" class="tab-pane" >
      <?= $this->render('_containers', [
                'dataProvider' => $dataProvider,
       ]) ?>
    </div>
     
    <div id="tracking" class="tab-pane" >
         <?= $this->render('_checklist', [
              'booking' => $booking,
              'contModel' => $contModel,
              'checkList' => $checkList,
              'showPreview' => true
          ]) ?>
    </div>  

    <div id="attachments" class="tab-pane" >
        <?= $this->render('_files', [
            'fileFields' => $fileFields,
            'booking' => $booking,
            'readOnly' => $readOnly
        ]) ?>
    </div>

    <div id="shipment_instructions" class="tab-pane" >
         <?= $this->render('_instructions', [
                'booking' => $booking,
                'showButton' => true,
                'readOnly' => $readOnly 

         ])?>
    </div>
    
  </div>  

<hr />

<div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <h4><strong>Booking Information</strong></h4>
        <?= DetailView::widget([
             'model' => $booking,
             
             'attributes' => [
                [
                'label'=> 'Type',
                'value'=> function ($model) {
                   return  $model->typeText;
                  }
                ],  
                [
                'label'=>'Vessel',
                  'value'=> function ($model) {
                     return  $model->vesselModel->vessel_name;
                  }
                ],
                'HB',
                'booking_number',
                'customer_reference',
                [   
                  'label'=> 'Customer',
                  'value'=> function($model){
                      return  $model->clientModel->fullName;
                  }
                ],
                [
                 'label'=>'Pickup Place',
                 'value'=> function ($model){ return $model->pickupPlace->name; },
                ],  
                'set_point',
                'created_at:date',
                'modified_at:date',
                [
                'label'=>'Created by',
                'value'=> function ($model){ return $model->creatorModel->name; },
                ],    
                [
                 'label'=>'Modified by',
                 'value'=> function($model){ return $model->modifierModel->name; },
                ],
            ],
        ]) ?>
      </div>
    </div>
</div>

</div>