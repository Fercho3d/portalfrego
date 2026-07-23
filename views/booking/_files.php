<?php 

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;

\yii\bootstrap\BootstrapAsset::register($this);


$sysUrl = Yii::getAlias('@sysUrl');


$disabled = Yii::$app->user->identity->access == 11 OR Yii::$app->user->identity->access == 14 ? true : false ;

if($booking->locked) { 
  $disabled = true;
}

$types= [
  'jpg' =>'image',
  'png' =>'image',
  'jpge' =>'image',
  'pdf' => 'pdf',
  'zip' => 'file',
  'jfif' => 'file',
  'xml' => 'file',
  'err' => 'file',
  '163' => 'file'
];

foreach($fileFields  as $key => $field) {

    $AllFiles = explode(' / ', $field->value);

    foreach ($AllFiles as $key => $value) {

      if(!empty($value) ){
        $url = $sysUrl . '/web/uploads/bookings/'. $booking->booking_id .'/docs/'. $value ;
        $fileElements = explode('.', $value);
        $type = $types[end($fileElements)];
        $initialPreview[$field->field->field][] = $url;
        $initialPreviewConfig[ $field->field->field ][]   =  [
          'caption' => $value  , 
          'type'=> $type , 
          'downloadUrl' => $url,  
          'url'=>'delete-file',
          'key'=> $value, 
          'extra'=>[ 'id' => $field->booking_file_id  ]
        ];   
      }

    }
}


$dispOptions = ['class' => 'form-control kv-monospace' ];

$saveOptions = [
    'type' => 'hidden', 
    'label'=> false, 
    'class' => 'kv-saved',
    'readonly' => true, 
    'tabindex' => 1000
];


?>
<div class="col-md-10" >


<h4>Documents <small>Select the documents and click on "Upload" button to save all files.</small></h4>

  <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data'] , 'action'=> 'upload?id='. $booking->booking_id  ]); ?>


  <div class="row" style="margin-top: 15px;" >
      <?php  foreach($fileFields  as $key => $field): ?>
        <?php 

            $pluginOptions = [
                        'allowedFileExtensions' => ['pdf','jpg','png', 'zip', 'xml', 'xls', 'xlsx', 'doc', 'docx', 'err', '163' ],
                        'showRemove'     => true,
                        'showUpload'     => true,
                        'showDownload' =>  true,
                        'initialPreviewAsData'=>true,
                        'previewFileType' => 'any',
                        //'uploadAsync' => false,
                        'maxFileCount' => 5,
                        'uploadUrl' => 'upload?id='.$booking->booking_id.'&field_id=' . $field->field_id,
                        'deleteUrl' => 'delete-file',
                        'overwriteInitial'=>false,
                        'maxFileSize'=> 2800
                  ];

                  if(isset($initialPreview[$field->field->field]) ){ 
                     $pluginOptions['initialPreview' ] =  $initialPreview[$field->field->field];
                     $pluginOptions['initialPreviewConfig'] =  $initialPreviewConfig[$field->field->field];
                  }

         ?>
        <div class="col-md-12"   >  
          <div class="form-group" >
            <label><?= $field->field->label ?></label>       
              <?= FileInput::widget( [
                  'name' => $field->field->field.'[]', 
                  'options' => [
                    'accept' => ['pdf','jpg','png', 'zip', 'xml', 'xls', 'xlsx', 'doc', 'docx', 'err', '163' ],
                    'multiple' => true,
                  ],
                  'disabled' => $disabled,
                  'pluginOptions' => $pluginOptions
              ]); 
              ?>
          </div>
        </div>

    <?php endforeach; ?>
  </div>

  <?php ActiveForm::end(); ?>


</div>

<script type="text/javascript">
    

   $("input").on("filepredelete", function(jqXHR) {
        var abort = true;
        if (confirm("Are you sure you want to delete this item?")) {
            abort = false;
        }
        return abort; // you can also send any data/object that you can receive on `filecustomerror` event
    });

</script>