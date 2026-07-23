<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\Vessel;
use app\models\LoadingPorts;
use app\models\ContainerTypes;
use app\models\DichargePort;
use app\models\Carrier;
use app\models\Client;
use app\models\Provider;

use app\models\PickupPlace;
use kartik\number\NumberControl;
use kartik\file\FileInput;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\Booking */
/* @var $form yii\widgets\ActiveForm 

<?= //echo $form->field($model, 'carrier')->textInput(['maxlength' => true]) ?>*/


if($model->isNewRecord){
    $model->loading_EDT = date("Y-m-d");
    $model->dicharge_ETA = $model->loading_EDT;
}

$vesselList = Vessel::getList();
$loadingPortList = LoadingPorts::getList($model->loading_port);
$containerList = ContainerTypes::getList();
$pickupList = PickupPlace::getList();
$carrierList = Provider::getList(1);
$transportList = Provider::getList(2);
$brockerList = Provider::getList(3);
$dichargePortList = DichargePort::getList($model->dicharge_port_id);

// $vesselList[-1]      = 'Add New Vessel' ;
// $loadingPortList[-1] = 'Add New Loading Port' ;
// $carrierList[-1]     = 'Add New Carrier' ;
// $transportList[-1]     = 'Add New Transport' ;
// $pickupList[-1]  = 'Add New Pick Up Place' ;
// $dichargePortList[-1]  = 'Add New Discharge Port';

// $vesselSpanNewIndex  = -1;
// $portSpanNewIndex    = -1;
// $carrierSpanNewIndex = -1;
// $transportSpanNewIndex = -1;
// $clientSpanNewIndex  = -1;
// $dichargeSpanNewIndex  = -1;
// $pickupPlaceSpanNewIndex = -1;


$fileLabels = $model->attributeLabels();
$sysUrl = Yii::getAlias('@web');

foreach ($model->DocsFields  as $key => $field) {
   $preview[$field]   =  [ $sysUrl .  '/uploads/bookings/'. $model->booking_id .'/docs/'. $model[$field] ];
}



$dispOptions = ['class' => 'form-control kv-monospace' ];

$saveOptions = [
    'type' => 'hidden', 
    'label'=> false, 
    'class' => 'kv-saved',
    'readonly' => true, 
    'tabindex' => 1000
];

 $saveCont = ['class' => 'kv-saved-cont' ];

 $model->booking_type = isset($_GET['booking_type']) ? $_GET['booking_type']: $model->booking_type ;

 $disabled = boolval($model->locked);

?>




  <div class="booking-form  col-md-8" >

  <?php  if($error): ?>

<div class="row">
  <div class="alert alert-danger col-md-1">
    ?= $error ?>
</div<>

<?php  endif; ?>

<?php  if($_GET['invoice'] == 'yes'): ?>

<div class="row">
  <div class="alert alert-success col-md-12">
    Invoice generated
  </div>
</div>

<?php  endif; ?>


<?php  if($_GET['carrier'] == 'yes'): ?>

<div class="row">
  <div class="alert alert-success col-md-12">
    Bill Carrier generated
  </div>
</div>

<?php  endif; ?>

<?php  if($_GET['transport'] == 'yes'): ?>

<div class="row">
  <div class="alert alert-success col-md-12">
    Bill Transport generated
  </div>
   
</div>

<?php  endif; ?>

    <div class="row">

        <div class="col-md-6" >
          <?= 
          $form->field($model, 'booking_type')
          ->widget(
            Select2::classname(), 
            [
            'data' => $model->BookingType, 
            'options' => ['placeholder' => 'Select Type'],
            'disabled' => $disabled
            ]); ?>
        </div>

        <div class="col-md-6" >
            <?=  $form->field($model, 'vessel')
            ->widget(Select2::classname(), 
            ['data' => $vesselList,
             'options' =>  ['placeholder' => 'Select Vessel' ],
             'disabled' => $disabled
            ])
            ->label(\yii\helpers\Html::a( 'Vessel', ['/vessel'], [ 'target' => '_blank' ] ) ); ?> 
            <span class="hidden" id="vessel_new_span" ><?= $form->field($model, 'vessel_new' )->textInput() ?></span>
        </div>
       
        <div class="col-md-12" ><?= $form->field($model, 'booking_number')->textInput( ['disabled' => $disabled]); ?></div>
        
        <div class="col-md-6" ><?= $form->field($model, 'HB')->textInput(['disabled' => $disabled]); ?></div>

        <div class="col-md-6" ><?= $form->field($model, 'customer_reference')->textInput(['disabled' => $disabled]) ?></div>

  </div>

 

    <div class="hidden row separated"  id="client_new_span" >
      

    </div>

    <div class="row" >
      
        <div class="col-md-4" >
            <?= $form->field($model, 'loading_port')->widget(Select2::classname(), ['data' => $loadingPortList, 'disabled' => $disabled ]); ?>
                <span class="hidden" id="port_new_span" ><?= $form->field($model, 'port_new')->textInput() ?></span>
          </div>
          
          <?php if($model->isNewRecord == false && empty($model->carrier_id)): ?>
          <div class="col-md-3"  >
            <?= $form->field($model, 'carrier')->widget(Select2::classname(), ['data' => $carrierOldList, 'disabled' => 'disabled' ]); ?>
          </div>
          <?php endif ?>

        
          <div class="<?= empty($model->carrier_id) && $model->isNewRecord == false ? 'col-md-4' : 'col-md-4' ;  ?>"  >
            <?= $form->field($model, 'carrier_id')->widget(Select2::classname(), ['data' => $carrierList, 'disabled' => $disabled ]); ?>
               <span class="hidden" id="carrier_new_span" ><?= $form->field($model, 'carrier_new')->textInput(['disabled' => $disabled]) ?></span>   
          </div>

          <div class="col-md-4"  >
            <?= $form->field($model, 'transport_id')->widget(Select2::classname(), 
            ['data' => $transportList, 'disabled' => $disabled, 
            'options' => ['placeholder' => 'Empty Transport'] ,
            'pluginOptions' => [
              'allowClear' => true
            ],
            ]); ?>
               <span class="hidden" id="transport_new_span" ><?= $form->field($model, 'transport_new')->textInput(['disabled' => $disabled]) ?></span>   
          </div>

          <div class="col-md-4" >
            <?= $form->field($model, 'custom_brocker_id')->widget(Select2::classname(), ['data' => $brockerList, 'options' => ['placeholder' => 'Select'], 'disabled' => $disabled ]); ?>
          </div>

    </div>

    <div class="row">

      <div class="col-md-6" >
        <div class="form-group required" >
            <label>EDT</label>
            <?= DatePicker::widget([
            'name' => 'Booking[loading_EDT]', 
            'value' => date("d/m/Y", strtotime($model->loading_EDT)),
                'options' => ['placeholder' => 'EDT'],
                'disabled' => $disabled,
                'pluginOptions' => [
                    'autoclose'=>true,
                    'format' => 'dd/mm/yyyy',
                    'todayHighlight' => true,
                    'convertFormat' => true,
                    ]
            ]); 
            ?>
        </div>
      </div>
      
      <?php if(!empty($model->dicharge_port)): ?>
          <div class="col-md-3" > <?= $form->field($model, 'dicharge_port')->textInput(['maxlength' => true, 'disabled' => true]) ?></div>  
      <?php endif; ?>

      <div class="<?= empty($model->dicharge_port) ? 'col-md-6' : 'col-md-3' ;  ?>" >
      <?= $form->field($model, 'dicharge_port_id')
      ->dropDownList($dichargePortList, ['prompt' => 'Dicharge Port','disabled' => $disabled ]); ?>

      <span class="hidden" id="dicharge_new_span" ><?= $form->field($model, 'dicharge_new')->textInput() ?></span>

     </div>


    </div>

  <div class="row" >
      
      <div class="col-md-6" > <?= $form->field($model, 'final_destination')->textInput(['maxlength' => true, 'disabled' => $disabled]) ?></div>   

        <div class="col-md-6"  >
          <div class="form-group" >
              <label>ETA</label>
              <?= DatePicker::widget([
              'name' => 'Booking[dicharge_ETA]',
              'value' => date("d/m/Y", strtotime($model->dicharge_ETA)),
                  'options' => ['placeholder' => 'ETA'],
                  'disabled' => $disabled,
                  'pluginOptions' => [
                      'autoclose'=>true,
                      'format' => 'dd/mm/yyyy',
                      'todayHighlight' => true,
                      'convertFormat' => true,
                      ]
              ]); 
              ?>
          </div>
        </div> 

    <div class="col-md-6" ><?= $form->field($model, 'set_point')->textInput(['maxlength' => true, 'disabled' => $disabled ]) ?></div>
      
  
  </div>

  <div class="row" >
    
      <div class="col-md-12" ><?= $form->field($model, 'pick_up_place_id')->dropDownList($pickupList, ['prompt' => 'Select Pickup Place','disabled' => $disabled ]); ?></div>

  </div>


  <div class="row hidden separated" id="pick_up_place_new_span" >


  </div>

  <div class="row" >


  </div>

     <?= $form->field($model, 'remarks')->textArea([ 'disabled' => $disabled ]); ?>

</div>



<script type="text/javascript" >  
  $(document).ready(function(){

      $('#bookingForm').submit(function(e) {
            var form = $(this);
            if (form.find('.has-error').length){ 
               e.preventDefault();
               alert("Please check and fill all required fields, before to continue");
                return false;
            }
          
     });

    var vesselSpanNewIndex = <?= $vesselSpanNewIndex ?>;

    $('#booking-vessel').change(function(event) {
      if( $(this).val() == vesselSpanNewIndex ){
        $('#vessel_new_span').removeClass('hidden');
        $('#booking-vessel_new').val('');
      }else{
        $('#vessel_new_span').addClass('hidden');
        $('#booking-vessel_new').val('');
      }  
    });    

    var portSpanNewIndex = <?= $portSpanNewIndex ?>;

    $('#booking-loading_port').change(function(event) {
      if( $(this).val() == portSpanNewIndex ){
        $('#port_new_span').removeClass('hidden');
        $('#booking-port_new').val('');
      }else{
        $('#port_new_span').addClass('hidden');
        $('#booking-port_new').val('');
      }  
    });
    
    var dichargeSpanNewIndex = <?= $dichargeSpanNewIndex ?>;

    $('#booking-dicharge_port_id').change(function(event) {
      if( $(this).val() == dichargeSpanNewIndex ){
        $('#dicharge_new_span').removeClass('hidden');
        $('#booking-dicharge_new').val('');
      }else{
        $('#dicharge_new_span').addClass('hidden');
        $('#booking-dicharge_new').val('');
      }  
    });

    var carrierSpanNewIndex = <?= $carrierSpanNewIndex ?>;    

    $('#booking-carrier').change(function(event) {
      if( $(this).val() == carrierSpanNewIndex ){
        $('#carrier_new_span').removeClass('hidden');
        $('#booking-carrier_new').val('');
      }else{
        $('#carrier_new_span').addClass('hidden');
        $('#booking-carrier_new').val('');
      }  
    });

  

    var pickupPlaceSpanNewIndex = <?= $pickupPlaceSpanNewIndex ?>;    

    $('#booking-pick_up_place_id').change(function(event) {
      if( $(this).val() == pickupPlaceSpanNewIndex ){
        $('#pick_up_place_new_span').load('pick-up-place');
        $('#pick_up_place_new_span').removeClass('hidden');
         
      }else{
        $('#pick_up_place_new_span').html('');
        $('#pick_up_place_new_span').addClass('hidden');
      }  
    });

  });
</script>
