<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\widgets\DetailView;
use app\models\Modality;
use kartik\checkbox\CheckboxX;
use \kartik\datetime\DateTimePicker;


/* @var $this yii\web\View */
/* @var $contModel app\models\Booking */

\yii\web\YiiAsset::register($this);

$template = '<td style="width: 50%;" >{label}</td><td style="width: 50%;" >{input}{error}{hint}</td>';
$modalityList = Modality::getList();


if($contModel){
    $contLabels =  $contModel->attributeLabels() ;
}

$bookingFields = [

  ['label' => 'Booking Number' ,   'field' => 'booking_number', 'value' => 'booking_number'],
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

$contFields = 
[
'vacuum_maneuver',
'pickup_date',
'gated_out',
'doc_cut_of',
'SI_date',
'draf_client',
'gated_IN',
'departure',
'bl_payment',
'swb',
'delivered'
];



//print_r($checkList);

?>
<script type="text/javascript">
  var isAdmin = false;
</script>

<?php if($contModel): ?>
 

<?php $form = ActiveForm::begin(); ?>
<?= $form->field($contModel, 'booking')->textInput()->hiddenInput(['value'=> $booking->booking_id])->label(false) ?>

  <?php if(!empty($error)): ?>
    <div class="alert alert-danger" ><?= $error ?></div>
  <?php endif; ?>     

<div class="row">
    <div class="booking-view col-md-12" >
        <table class="table table-striped  detail-view" >
            <thead>
                <tr>
                    <th>Task</th>
                    <th></th>    
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
                  <td><?= $item['date'] ?  date("d/m/Y",strtotime($booking[$item['value']])) : $booking[$item['value']] ?></td>
                  <td class="text-center" >
                      <?php if (!empty($checkList[$item['field'].'_chk_date'])):  ?>
                      <span class="glyphicon glyphicon-ok text-success" ></span>
                      <?php endif ?>
                
                    </td>
                    <td  >
                        <?= Yii::$app->formatter->format($checkList[$item['field'].'_chk_date'], 'date'); ?> 
                  </td>
                  <td></td>  
              </tr>
            <?php endforeach; ?>

             <!--------------------------------  ELEMENT ------------------------------->

        <tr>
        <th>Modality</th> 
        <td><?= $contModel->modality0->modality_name ?></td> 
         <?php $item['field'] = 'modality' ?>
                  <td class="text-center">
                      <?php if (!empty($checkList[$item['field'].'_chk_date'])):  ?>
                                          <span class="glyphicon glyphicon-ok text-success" ></span>
                      <?php endif ?>
                    </td>
                    <td  >
                    <?= Yii::$app->formatter->format($checkList[$item['field'].'_chk_date'], 'date'); ?> 
              </td>
              <td>
              </td> 
        </tr>

        <!--------------------------------  ELEMENT ------------------------------->  
        <?php  foreach ($contFields as $key => $field): ?>
        <?php $item['field'] = $field ?>
        <tr>
          <td>
          <label class="control-label"  ><?= $contLabels[$field] ?></label>  
          </td>
            <td>
            <?= Yii::$app->formatter->format($contModel[$field], 'date'); ?> 
            </td>
            <td class="text-center">
              <?php if (!empty($checkList[$item['field'].'_chk_date'])):  ?>
                      <span class="glyphicon glyphicon-ok text-success" ></span>
              <?php endif ?>
            </td>
            <td  >
              <?= Yii::$app->formatter->format($checkList[$item['field'].'_chk_date'], 'date'); ?> 
            </div>
            </td> 
        <td><?php if($checkList): ?><span class="delivery" ><?= $checkList->calcDeliveryTime($contModel->vacuum_maneuver, $checkList[$item['field'].'_chk_date']) ?><?php endif; ?></span>
        </tr>
        <?php endforeach;?>

        
        <!--------------------------------  ELEMENT ------------------------------->

      </tbody>
    </table>
</div>
</div>

<?php ActiveForm::end(); ?>

<?php else: ?>
   <div class="alert alert-warning">
      No Tracking information has been submited
   </div> 
<?php endif; ?> 