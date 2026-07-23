<?php

use yii\helpers\Html;
use app\models\Client;
use app\models\Vessel;
use app\models\LoadingPorts;
use app\models\ContainerTypes;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Booking */
/* @var $form yii\widgets\ActiveForm */

$disabled = true ;

/*$sysUrl = Yii::getAlias('@sysUrl');

if( $showPreview ){ 
  $pdf_attach_preview   =  [ $sysUrl .  '/web/uploads/Bookings/'.   $model->transc_id .'/pdf/'. $model->pdf_attach ];
  //$xml_attach_preview   =  [ $sysUrl.   '/web/uploads/transactions/'.   $model->transc_id .'/pdf/'. $model->xml_attach ];
}*/


if($model->isNewRecord){
    $model->loading_EDT = date("Y-m-d");
    $model->dicharge_ETA = $model->loading_EDT;
}

$vesselList =  Vessel::getList();
$loadingPortList =  LoadingPorts::getList();
$containerList =  ContainerTypes::getList();
$clientList =  Client::getList();

 /*<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data' ]]); ?>*/

 /* <?php $form = ActiveForm::begin(['action' => 'upload?id='.$model->booking_id ]); ?> */

?>

<div class="booking-form col-md-4" >

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);

    <?= $form->field($model, 'vessel')->dropDownList($vesselList, ['prompt' => 'Select vessel' ])->label(\yii\helpers\Html::a( 'Vessel', ['/vessel'], [ 'target' => '_blank' ] )); ?>


    <?= $form->field($model, 'booking_number')->textInput() ?>

    <?= $form->field($model, 'client')->dropDownList($clientList, ['prompt' => 'Select Client' ]); ?>
   
    <?= $form->field($model, 'loading_port')->dropDownList($loadingPortList, ['prompt' => 'Select Loading Port' ]); ?>


    <div class="form-group  required" >
    <label>EDT</label>
        <?= DatePicker::widget([
        'name' => 'Booking[loading_EDT]', 
        'value' => date("d/m/Y", strtotime($model->loading_EDT)),
            'options' => ['placeholder' => 'EDT'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd/mm/yyyy',
                'todayHighlight' => true,
                'convertFormat' => true,
                ]
        ]); 
        ?>
    </div>
    <br />
   <?= $form->field($model, 'dicharge_port')->textInput(['maxlength' => true]) ?>

   <div class="form-group  required" >
    <label>ETA</label>
        <?= DatePicker::widget([
        'name' => 'Booking[dicharge_ETA]',
        'value' => date("d/m/Y", strtotime($model->dicharge_ETA)),
            'options' => ['placeholder' => 'ETA'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'dd/mm/yyyy',
                'todayHighlight' => true,
                'convertFormat' => true,
                ]
        ]); 
        ?>
    </div>
    <br/>

    <?= $form->field($model, 'set_point')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pick_up_place')->textArea(array("style" => "resize:none; word-wrap:break-word;")) ?>

    <?= $form->field($model, 'carrier')->textInput(['maxlength' => true]) ?>


    <div class="row" style="margin-top: 24px" >
   <div class="col-md-6" >

       <?= $form->field($model, 'pdf_attach_file')
       ->widget(FileInput::classname(), 
        [
            'options' => ['accept' => 'application/pdf', 'multiple'=>false ],
            //'disabled' => $disabled,
            'pluginOptions'=> 
              ['allowedFileExtensions'=> ['pdf'],
              'showUpload' => true,
              'initialPreviewAsData'=>true , 
              'initialPreview'=>  $showPreview && !empty($model->pdf_attach) ? $pdf_attach_preview : false ,
              'initialPreviewConfig' => [
                ['caption' => $model->pdf_attach, 'size' => '1024', 'type' => 'pdf' , 'url'  => $pdf_attach_preview, 'downloadUrl' => $pdf_attach_preview ]
              ],
              'previewFileType' => 'any',
              //'preferIconicPreview' => true,
              'overwriteInitial'=> true,
              'maxFileSize'=>1024,
              'maxFileCount' => 1,
              'showCaption' => false,
              'showRemove' => false,
              'showUpload' => false,
              'browseClass' => 'btn btn-primary btn-block',
              'browseIcon' => '<i class="glyphicon glyphicon-file-o"></i> ',
              'browseLabel' =>  'Select'
            ],
            'options'=>[
              'multiple'=> false
            ],
          ]);   
        ?>
      </div>
      </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
