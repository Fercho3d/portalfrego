<?php 
use yii\helpers\Html;
?>    

<div class="row">
  <div class="container" > 
    <div class="col-md-3" > 
      <h5>Origin</h5>
      <h2><?= empty($this->title = $booking->pickupPlace->name ) ? 'N/A' : $booking->pickupPlace->name; ?></h2>
    </div>
    <div class="col-md-3" >
      <h5>Loading Port</h5> 
      <h2><?= Html::encode(empty($booking->portname) ? 'N/A' : $this->title = $booking->portname)?></h2>
      <h5><?= date("j F, Y", strtotime($booking->loading_EDT) ) ?></h5>
    </div>
    <div class="col-md-3" >
      <h5>Discharge Port</h5>
      <h2><?= $model->dichargePort->name ?></h2>
      <h5><?= date("j F, Y", strtotime($booking->dicharge_ETA) )  ?></h5>
    </div>
    <div class="col-md-3" >
      <h5>Destiny </h5>
      <h2><?= $booking->final_destination ?></h2>
      <h5><?= date("j F, Y", strtotime($booking->dicharge_ETA )  )?></h5>
    </div>
 </div>
</div>
<div class="row" >
  <div class="progress" style="width:100%" >
         <div class="progress-bar" role="progressbar" aria-valuenow="60"
              aria-valuemin="0" aria-valuemax="100" style="width:<?= $booking->progress ?>%">
           <span ><?= $booking->progress ?>%</span>
         </div>
    </div>
</div>