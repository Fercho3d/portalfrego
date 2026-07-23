<?php

use yii\helpers\Html;
use app\models\User;
use app\models\PaymentTerms;
use app\models\Account;
use app\models\Provider;
use app\models\Client;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\file\FileInput;

$disabled = $model->payment_request == 1  || $booking->locked ? true : false;

/* @var $this yii\web\View */
/* @var $model app\models\Transaction */
/* @var $form yii\widgets\ActiveForm */

if($showPreview){ 
  $pdf_attach_preview   =  [ $model->pdFLink ];
  $xml_attach_preview   =  [ $model->xmlLink ];
}

//print_r( $pdf_attach_preview ) ;

$userList = User::getList();
$accountList = Account::getList();
$providerList = Provider::getList();
$clientList = Client::getList();

$model->tran_date = $model->isNewRecord ?  date('Y-m-d') : $model->tran_date ;


?>


<div class="transaction-form" >

    <?php $form = ActiveForm::begin(
    [
      'id'=> 'tranForm',
      'enableAjaxValidation'=> false,
      'enableClientValidation'=> true, 
    ]); ?>

    <div class="hidden" >
      <?= $form->field($model, 'tran_type')->hiddenInput()->label(false); ?>
     
    </div>

<div class="row" >
    <div class="col-md-4" >
          <div class="form-group" >
            <label>Bill Date</label>
                <?= DatePicker::widget([
                'name' => 'Transaction[tran_date]', 
                'disabled' => $disabled,
                'value' => empty($model->tran_date)? '' : date("d-m-Y", strtotime($model->tran_date)),
                    'options' => ['placeholder' => 'Transaction Date'],
                    'pluginOptions' => [
                        'autoclose'=>true,
                        'format' => 'dd-mm-yyyy',
                        'todayHighlight' => true,
                        'convertFormat'  => true,
                    ]
                ]); 
                ?>
          </div>
    </div>
 
    <div class="col-md-4" >
        <?= $form->field($model, 'tran_number')->textInput(['disabled' => $disabled ]) ?>
    </div>
    <div class="col-md-4" >
        <?= $form->field($model, 'account')->dropDownList($accountList, ['prompt' => 'Select Account','disabled' => true ] ); ?>
    </div> 

</div>

<div class="row" >

  <div class="col-md-6" >
       <?= $form->field($model, 'pdf_attach_file')
       ->widget(FileInput::classname(), 
        [
            'options' => ['accept' => 'application/pdf', 'multiple'=>false ],
            //'readonly' => $disabled,
            'pluginOptions'=> 
              ['allowedFileExtensions'=> ['pdf'],
              'showUpload' => true,
              'disabled' => $disabled,
              'initialPreviewAsData'=>true , 
              'initialPreviewFileType'=> 'pdf',
              'initialPreview'=>  [$showPreview && !empty($model->pdf_attach) ? $pdf_attach_preview : false] ,
              'initialPreviewConfig' => [
                ['filename' => $model->pdf_attach, 'size' => '1024', 'type' => 'pdf' , 'url'  => $pdf_attach_preview, 'downloadUrl' => $pdf_attach_preview, 'showRemove' => false ]
              ],
              
              'dropZoneEnabled' => false, 
              'preferIconicPreview' => false,
              'overwriteInitial'=> true,
              'maxFileSize'=>2048,
              'maxFileCount' => 1,
              'showCaption' => false,
              'showRemove' => false,
              'showUpload' => false,
              'browseClass' => "btn btn-primary btn-block $hidden",
              'browseIcon' => '<i class="glyphicon glyphicon-file-o"></i> ',
              'browseLabel' =>  'Select'
            ],
            'options'=>[
              'multiple'=> false
            ],
          ]);   
        ?>
    </div>

    <div class="col-md-6" >
       <?= $form->field($model, 'xml_attach_file')
       ->widget(FileInput::classname(), 
        [
            'options' => ['accept' => 'application/xml', 'multiple'=>false ],
            //'readonly' => $disabled,
            'pluginOptions'=> 
              ['allowedFileExtensions'=> ['xml'],
              'initialPreviewAsData' =>true , 
              'initialPreview'=>  $showPreview && !empty($model->xml_attach) ? $xml_attach_preview : false ,
              'initialPreviewConfig' => [
                ['filename' => $model->xml_attach, 'size' => '512', 'type' => 'pdf' , 'url'  => $xml_attach_preview, 'downloadUrl' => $xml_attach_preview,  'width' =>"120px", 'showRemove' => false ]
              ],
              'previewFileType' => 'any',
              'dropZoneEnabled' => false, 
              'preferIconicPreview' => true,
              'overwriteInitial'=> true,
              'maxFileSize'=>2048,
              'maxFileCount' => 1,
              'showCaption' => false,
              'showRemove' => false,
              'showUpload' => false,
              'showDownload' =>  true,
              'browseClass' => "btn btn-primary btn-block $hidden",
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



    <?php ActiveForm::end(); ?>

</div>


<script type="text/javascript">

       $(document).on("click", '#addCharge', function(e) { 
          $.pjax.reload({container:'#w0-pjax'});
       });  

       $(document).on("click", '#addCharge', function(e) { 
                e.preventDefault();
                var url = $(this).attr('href');
                $("#form .modal-dialog .modal-content").load(url,function(){
                    $('#form').modal('show');
                });
        });

        $("#tranForm").unbind().bind("beforeSubmit", function(e){
            e.preventDefault();
            var form = $(this);
          if (form.find('.has-error').length){ 
                return false;
            }
            var formData = new FormData(document.getElementById("tranForm"));
            console.log(formData);
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "/web/transaction/<?= $model->isNewRecord ? 'create?tran_type='. $model->tran_type : 'update?id='. $model->transc_id ?>",
                type: "post",
                dataType: "html",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
                .done(function(res){
                    ///$("#form .modal-dialog .modal-content").html('');
                    $("#form .modal-dialog .modal-content").html(res);
                    $('#form ').modal('show');

                    $.pjax.reload({container:'#w0-pjax'});
 
                });
        }).on('submit', function(e){
            e.preventDefault();
        });

</script>