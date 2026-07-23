<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ChargeType;
use app\models\TaxCode;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\Charge */
/* @var $form yii\widgets\ActiveForm */

$charcheTypeList = ChargeType::getList() ;
$taxCodeList = TaxCode::getList() ;

$dispOptions = ['class' => 'form-control kv-monospace'];

$saveOptions = [
    'type' => 'hidden', 
    'label'=> false, 
    'class' => 'kv-saved',
    'readonly' => true, 
    'tabindex' => 1000
];

 $saveCont = ['class' => 'kv-saved-cont'];

?>

<div class="modal-body" >

<div class="charge-form col-md-12" >

    <?php $form = ActiveForm::begin(['id' => 'charForm' ]); ?>

    <div class="hidden" >
        <?php if($model->isNewRecord): ?>
            <?= $form->field($model, 'transaction')
            ->hiddenInput(['value'=> $model->isNewRecord ? $transaction : $model->transaction ])
            ->label(false); ?>  
        <?php endif; ?>
        <?= $form->field($model, 'price') ->hiddenInput()->label(false); ?>
    </div>

    <div class="row" >
        <div class="col-md-4" >    
            <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4" >
            <?= $form->field($model, 'quantity')->widget(NumberControl::classname(), [
            'disabled'=> true ,
            'maskedInputOptions' => [
                'allowMinus' => false,
            ],
            'options' => $saveOptions,
            'displayOptions' => $dispOptions,
            'saveInputContainer' => $saveCont
            ]);
            ?>  
        </div> 
        <div class="col-md-4" >
            <?= $form->field($model, 'price_confirmation')->widget(NumberControl::classname(), [
            'maskedInputOptions' => [
                'prefix' => '$ ',
                'allowMinus' => false
            ],
            'options' => $saveOptions,
            'displayOptions' => $dispOptions,
            'saveInputContainer' => $saveCont
            ]);
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

</div>


<div class="modal-footer">
    

    <div class="form-group text-right">

        <?=  Html::button( 'Cancel' , ['class' => 'btn btn-default', 'data-dismiss'=>'modal' ]) ?>

        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'form'=>"charForm" ]) ?>

    </div>

</div>

<script type="text/javascript" >


$(document).ready(function(){

    var okIcon = '<span class="text-success glyphicon glyphicon-ok"></span>';
    var charge_id = '<?= $model->charge_id ?>';

        $("#charForm").unbind().bind("submit", function(e){
            e.preventDefault();
            //$('#form').modal('hide'); 
            var f = $(this);
            var formData = new FormData(document.getElementById("charForm"));
            formData.append("dato", "valor");
            //formData.append(f.attr("name"), $(this)[0].files[0]);
            $.ajax({
                url: "/web/charge/<?= $model->isNewRecord ? 'create': 'update?id='. $model->charge_id ?>",
                type: "post",
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            })
            .done(function(res){
                if(res.success){ 
                    $("#charge-form").modal('hide');
                        $('tr[data-key='+ charge_id +'] td[data-col-seq=7]').html(okIcon);
                        $('tr[data-key='+ charge_id +'] td[data-col-seq=3]').html(res.price);
                        $('tr[data-key='+ charge_id +'] td[data-col-seq=6]').html(res.total);
                }else{
                    $("#charge-form .modal-dialog .modal-content").html(res);
                }
            });
        });

});


</script>




