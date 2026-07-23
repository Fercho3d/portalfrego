<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\widgets\DetailView;
use app\models\Modality;
use kartik\checkbox\CheckboxX;
use \kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $model app\models\Booking */

\yii\web\YiiAsset::register($this);

$template = '<td style="width: 50%;" >{label}</td><td style="width: 50%;" >{input}{error}{hint}</td>';
$modalityList = Modality::getList();


$bookingFields = [

  ['label' => 'Booking Number' ,   'field' => 'booking_number', 'value' => 'booking_number'],
  ['label' => 'Customer'       ,   'field' => 'client', 'value' => 'client'            ],
  ['label' => 'Vessel' ,           'field' => 'vessel', 'value' => 'vesselname' ],
  ['label' => 'POL',               'field' => 'loading_port', 'value' => 'portname' ],
  ['label' => 'ETD',               'field' => 'loading_EDT', 'value' => 'loading_EDT',    'date' =>true   ],
  ['label' => 'POD',               'field' => 'dicharge_port', 'value' => 'dicharge_port' ],
  ['label' => 'ETA',               'field' => 'dicharge_ETA', 'value' => 'dicharge_ETA'  , 'date' =>true   ],
  ['label' => 'Container type',    'field' => 'container_type', 'value' => 'containertypename'    ],
  ['label' => 'Commodity',         'field' => 'commodity', 'value' => 'commodity'         ],
  ['label' => 'Set Point',         'field' => 'set_point', 'value' => 'set_point'         ],
  ['label' => 'Pick Up Place',     'field' => 'pick_up_place', 'value' => 'pick_up_place' ],

];

//print_r($checkList);

?>
<script type="text/javascript">
  var isAdmin = false;
  isAdmin = <?= $isAdmin ?>;  
</script>

 

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'booking')->textInput()->hiddenInput(['value'=> $booking->booking_id])->label(false) ?>

  <?php if(!empty($error)): ?>
    <div class="alert alert-danger" ><?= $error ?></div>
  <?php endif; ?>     

<div class="row">
    <div class="booking-view col-md-12" >
        <table class="table table-striped table-bordered detail-view" >
            <caption>Booking Continuity <?= $booking->booking_number ?></caption>
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Value</th>    
                    <th class="text-center" >Checked</th>    
                    <th>Cheked Date</th>    
                    <th>Delivery Time</th>    
                </tr>
            <!--------------------------------  ELEMENT ------------------------------->
            </thead>
            <tbody>
                <?php foreach ($bookingFields as $key => $item): ?>
                  <?php $prev = $key-1; ?> 
                <tr>
                  <th><?= $item['label'] ?> </th>
                  <th><?= $item['date'] ?  date("d-m-Y",strtotime($booking[$item['value']])) : $booking[$item['value']] ?></th>
                  <td class="text-center" >
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  data-required="<?=  $bookingFields[$prev]['field'] ?>" >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=> true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?= $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
                  </td>
                  <td></td>  
              </tr>
            <?php endforeach; ?>

             <!--------------------------------  ELEMENT ------------------------------->

         <?= $form->field($model, 'modality', ['template'=> $template])->dropDownList($modalityList, ['prompt' => 'Select Modality' ]); ?>
         <?php $item['field'] = 'modality' ?>
                  <td class="text-center">
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="pick_up_place" >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
              </td>
              <td></td> 
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
          <td>
          <label class="control-label" for="bookingcontinuity-vacuum_maneuver" >Empty Pass</label>  
          </td>
            <td>
                <?= DateTimePicker::widget([
                'name' => 'BookingContinuity[vacuum_maneuver]', 
                'value' =>empty($model->vacuum_maneuver)? '' : date("d-m-Y h:i:s A", strtotime($model->vacuum_maneuver)),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose'=> true,
                        'format' => 'dd-m-yyyy HH:ii:ss P'
                        ]
                ]); 
                ?>
            </td>
            <?php $item['field'] = 'vacuum_maneuver' ?>
                  <td class="text-center">
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="modality"  >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
              </td> 
        <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->vacuum_maneuver, $checkList[$item['field'].'_chk_date']) ?></span>
        </tr>
        <tr>
          <td> 
          <label class="control-label" for="bookingcontinuity-pickup_date" >Pickup Date</label>  
          </td>
            <td>
                <?= DateTimePicker::widget([
                'name' => 'BookingContinuity[pickup_date]', 
                'value' =>empty($model->pickup_date)? '' : date("d-m-Y h:i:s A", strtotime($model->pickup_date)),
                    'options' => ['placeholder' => ''],
                    'pluginOptions' => [
                        'autoclose'=> true,
                        'format' => 'dd-m-yyyy HH:ii:ss P'
                        ]
                ]); 
                ?>
            </td>
            <?php $item['field'] = 'pickup_date' ?>
                  <td class="text-center">
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="modality"  >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
              </td> 
        <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->vacuum_maneuver, $checkList[$item['field'].'_chk_date']) ?></span>
        </tr>
        <!--------------------------------  ELEMENT ------------------------------->
        <tr> 
       <td>
          <label class="control-label" for="bookingcontinuity" >Gated Out</label>  
          </td>
          <td>
              <?= DateTimePicker::widget([
              'name' => 'BookingContinuity[gated_out]', 
              'value' =>empty($model->gated_out)? '' : date("d-m-Y h:i:s A", strtotime($model->gated_out)),
                  'options' => ['placeholder' => ''],
                  'pluginOptions' => [
                      'autoclose'=> true,
                      'format' => 'dd-m-yyyy HH:ii:ss P'
                      ]
              ]); 
              ?>
          </td>
          <?php $item['field'] = 'gated_out' ?>
                  <td class="text-center">
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="vacuum_maneuver" >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
              </td> 
            <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->gated_out, $checkList[$item['field'].'_chk_date']) ?></span>      
        </tr>
        
        <!--------------------------------  ELEMENT ------------------------------->
        
        <tr>
             <td  style="width: 50%;">
                <label class="control-label" for="bookingcontinuity-doc_cut_of">Closing Date</label>
             </td>
             <td>
                 <?= DateTimePicker::widget([
                 'name' => 'BookingContinuity[doc_cut_of]', 
                 'value' =>empty($model->doc_cut_of)? '' : date("d-m-Y h:i:s A", strtotime($model->doc_cut_of)),
                     'options' => ['placeholder' => ''],
                     'pluginOptions' => [
                         'autoclose'=> true,
                         'format' => 'dd-m-yyyy HH:ii:ss P'
                         ]
                 ]); 
                 ?>
             </td>
              <?php $item['field'] = 'doc_cut_of' ?>
                  <td class="text-center">
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="gated_out"   >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
              </td>
          <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->doc_cut_of, $checkList[$item['field'].'_chk_date']) ?></span>
          </tr>

          <!--------------------------------  ELEMENT ------------------------------->

          <tr>
             <td  style="width: 50%;">
                <label class="control-label" for="bookingcontinuity-SI_date">Cut oﬀ Date SI</label>
             </td>
             <td>
                 <?= DateTimePicker::widget([
                 'name' => 'BookingContinuity[SI_date]', 
                 'value' =>empty($model->SI_date)? '' : date("d-m-Y h:i:s A", strtotime($model->SI_date)),
                     'options' => ['placeholder' => ''],
                     'pluginOptions' => [
                         'autoclose'=> true,
                         'format' => 'dd-m-yyyy HH:ii:ss P'
                         ]
                 ]); 
                 ?>
             </td>
              <?php $item['field'] = 'SI_date' ?>
                  <td class="text-center">
                    <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  data-required="doc_cut_of" >
                      <?=  CheckboxX::widget([
                              'name'=> $item['field'] . '_chk',
                              'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                              'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                              'options'=>['id'=> $item['field'] . '_chk'],
                              'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                          ]); 
                      ?>
                    </div>
                    </td>
                    <td style="width:25%" >
                        <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                        <?=  DateTimePicker::widget([
                              'name'=> $item['field'] . '_chk_date',
                              'id'=> $item['field'] . '_chk_date',
                              'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                              'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                              'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                              : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                              'pluginOptions' => [
                                  'autoclose'=>true,
                                  'format' => 'dd-m-yyyy HH:ii:ss P'
                              ]
                          ]);
                         ?>
                    </div>
              </td> 
              <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->SI_date, $checkList[$item['field'].'_chk_date']) ?></span>      
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
         <td  style="width: 50%;">
            <label class="control-label" for="bookingcontinuity-draf_client" >Draft Customer</label>
         </td>
         <td>
             <?= DateTimePicker::widget([
             'name' => 'BookingContinuity[draf_client]', 
             'value' =>empty($model->draf_client)? '' : date("d-m-Y h:i:s A", strtotime($model->draf_client)),
                 'options' => ['placeholder' => ''],
                 'pluginOptions' => [
                     'autoclose'=> true,
                     'format' => 'dd-m-yyyy HH:ii:ss P'
                     ]
             ]); 
             ?>
         </td>
          <?php $item['field'] = 'draf_client' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="SI_date"  >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  >
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                </div>
          </td>
         <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->draf_client, $checkList[$item['field'].'_chk_date']) ?></span>    
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
         <td  style="width: 50%;">
            <label class="control-label" for="bookingcontinuity-gated_IN" >Gated In</label>
         </td>
         <td>
             <?= DateTimePicker::widget([
             'name' => 'BookingContinuity[gated_IN]', 
             'value' =>empty($model->gated_IN)? '' : date("d-m-Y h:i:s A", strtotime($model->gated_IN)),
                 'options' => ['placeholder' => ''],
                 'pluginOptions' => [
                     'autoclose'=> true,
                     'format' => 'dd-m-yyyy HH:ii:ss P'
                     ]
             ]); 
             ?>
         </td>
          <?php $item['field'] = 'gated_IN' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="draf_client" >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>">
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                </div>
              </td>
          <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->gated_IN, $checkList[$item['field'].'_chk_date']) ?></span>        
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
         <td  style="width: 50%;">
            <label class="control-label" for="bookingcontinuity-cleared" >Cleared</label>
         </td>
         <td>
             <?= DateTimePicker::widget([
             'name' => 'BookingContinuity[cleared]', 
             'value' =>empty($model->cleared)? '' : date("d-m-Y h:i:s A", strtotime($model->cleared)),
                 'options' => ['placeholder' => ''],
                 'pluginOptions' => [
                     'autoclose'=> true,
                     'format' => 'dd-m-yyyy HH:ii:ss P'
                     ]
             ]); 
             ?>
         </td> 
          <?php $item['field'] = 'cleared' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  data-required="gated_IN"  >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  >
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                </div>
          </td>
          <td><?= $checkList->calcDeliveryTime($model->cleared, $checkList[$item['field'].'_chk_date']) ?>      
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
         <td  style="width: 50%;">
            <label class="control-label" for="bookingcontinuity-departure" >Departure</label>
         </td>
         <td>
             <?= DateTimePicker::widget([
             'name' => 'BookingContinuity[departure]', 
             'value' =>empty($model->departure)? '' : date("d-m-Y h:i:s A", strtotime($model->departure)),
                 'options' => ['placeholder' => ''],
                 'pluginOptions' => [
                     'autoclose'=> true,
                     'format' => 'dd-m-yyyy HH:ii:ss P'
                     ]
             ]); 
             ?>
         </td> 
          <?php $item['field'] = 'departure' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  data-required="cleared" >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  >
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                </div>
          </td>
        <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->departure, $checkList[$item['field'].'_chk_date']) ?></span>               
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
         <td  style="width: 50%;">
            <label class="control-label" for="bookingcontinuity-bl_payment" >BL Payment</label>
         </td>
         <td>
             <?= DateTimePicker::widget([
             'name' => 'BookingContinuity[bl_payment]', 
             'value' =>empty($model->bl_payment)? '' : date("d-m-Y h:i:s A", strtotime($model->bl_payment)),
                 'options' => ['placeholder' => ''],
                 'pluginOptions' => [
                     'autoclose'=> true,
                     'format' => 'dd-m-yyyy HH:ii:ss P'
                     ]
             ]); 
             ?>
         </td> 
          <?php $item['field'] = 'bl_payment' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"  data-required="departure"   >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                </div>
          </td>
        <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->bl_payment, $checkList[$item['field'].'_chk_date']) ?></span>               
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

        <tr>
         <td  style="width: 50%;" >
            <label class="control-label" for="bookingcontinuity-swb" >SWB</label>
         </td>
         <td>
             <?= DateTimePicker::widget([
             'name' => 'BookingContinuity[swb]', 
             'value' =>empty($model->swb)? '' : date("d-m-Y h:i:s A", strtotime($model->swb)),
                 'options' => ['placeholder' => ''],
                 'pluginOptions' => [
                     'autoclose'=> true,
                     'format' => 'dd-m-yyyy HH:ii:ss P'
                     ]
             ]); 
             ?>
         </td> 
           <?php $item['field'] = 'swb' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>"   data-required="bl_payment"  >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                 </div>
          </td>  
        <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->swb, $checkList[$item['field'].'_chk_date']) ?></span>               
        </tr>
        <!--------------------------------  ELEMENT ------------------------------->
        <tr>
          <td>
            <label class="control-label" for="bookingcontinuity-delivered" >Delivered</label>
          </td>
          <td>
              <?= DateTimePicker::widget([
              'name' => 'BookingContinuity[delivered]', 
              'value' =>empty($model->delivered)? '' : date("d-m-Y h:i:s A", strtotime($model->delivered)),
                  'options' => ['placeholder' => ''],
                  'pluginOptions' => [
                      'autoclose'=> true,
                      'format' => 'dd-m-yyyy HH:ii:ss P'
                      ]
              ]); 
              ?>
          </td> 

           <?php $item['field'] = 'delivered' ?>
              <td class="text-center">
                <div style="width:190px" class="checkList_cont" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" data-required="swb" >
                  <?=  CheckboxX::widget([
                          'name'=> $item['field'] . '_chk',
                          'disabled'=> (!empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || $booking->locked,
                          'value' => (int) !empty($checkList[$item['field'].'_chk_date']),
                          'options'=>['id'=> $item['field'] . '_chk'],
                          'pluginOptions'=>['autoclose'=>true, 'threeState'=> false ] 
                      ]); 
                  ?>
                </div>
                </td>
                <td style="width:25%" >
                    <div style="width:250px" class="checkList_date" data-field="<?=  $item['field'] ?>" data-booking="<?=  $booking->booking_id ?>" >
                    <?=  DateTimePicker::widget([
                          'name'=> $item['field'] . '_chk_date',
                          'id'=> $item['field'] . '_chk_date',
                          'disabled'=> (empty($checkList[$item['field'].'_chk_date']) AND $isAdmin == "false") || ($isAdmin == "false") ,
                          'type' => DateTimePicker::TYPE_COMPONENT_APPEND,
                          'value' =>  empty($checkList[$item['field'].'_chk_date']) ? '' 
                          : date("d-m-Y h:i:s A", strtotime($checkList[$item['field'].'_chk_date'])),
                          'pluginOptions' => [
                              'autoclose'=>true,
                              'format' => 'dd-m-yyyy HH:ii:ss P'
                          ]
                      ]);
                     ?>
                </div>
          </td>
          <td><span class="delivery" ><?= $checkList->calcDeliveryTime($model->delivered, $checkList[$item['field'].'_chk_date']) ?></span>                  
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->

      </tbody>
    </table>
    <div class="form-group">
        <?= Html::submitButton('Save Continuty dates', ['class' => 'btn btn-success']) ?>
    </div>
</div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->registerJsFile(Yii::$app->request->baseUrl.'/js/continuity.js',['depends' => [\yii\web\JqueryAsset::className()]]); ?>
