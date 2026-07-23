<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Account;

use kartik\number\NumberControl;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use app\models\Bank;
use app\models\Provider;
use app\models\Client;

$disabled = true;

/* @var $this yii\web\View */
/* @var $model app\models\PaymentRequest */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace'];

$saveOptions = [ 
    'type' => 'hidden', 
    'label'=> false, 
    'class' => 'kv-saved',
    'readonly' => true, 
    'tabindex' => 1000
];
$bankList = Bank::getList();
$accountList = Account::getList();
$providerList = Provider::getList();
$clientList = Client::getList();


?>


<div class="payment-request-form">

    <?php $form = ActiveForm::begin(['id' =>'requestForm' ]); ?>

    <div class="row" >

 <!--        <div class="col-md-3" >
        <?= $form->field($model, 'bank_id')->dropDownList($bankList, ['prompt' => 'Select Bank Account','disabled' => $disabled ] ); ?>
        </div> 
 -->
        <div class="col-md-3" >
            <?= $form->field($model,'number')->textInput(['disabled' => $disabled]) ?>
        </div>

        <div class="col-md-3" >
                <?= $form->field($model, 'amount' )
                ->widget(NumberControl::classname(), [
                'maskedInputOptions' => [
                    'prefix' => '$ ',
                    'allowMinus' => false
                ],
                'disabled' => $disabled,
                'options' => [
                        'type' => 'hidden', 
                        'label'=> false, 
                        'class' => 'kv-saved',
                        'tabindex' => 1000
                ],
                'displayOptions' => $dispOptions,
                'saveInputContainer' => $saveOptions
                ]);
                ?>
        </div>

        <div class="col-md-3" >
            <div class="form-group" >
              <label>Payment Date</label>
                  <?= DatePicker::widget([
                  'name' => 'PaymentRequest[date]', 
                  'disabled' => $disabled,
                  'value' => empty($model->date) ? '' : date("d-m-Y", strtotime($model->date) ),
                      'options' => ['placeholder' => 'Payment Date'],
                      'pluginOptions' => [
                          'autoclose'=>true,
                          'format' => 'dd-mm-yyyy',
                          'todayHighlight' => true,
                          'convertFormat' => true,
                      ]
                  ]); 
                  ?>
            </div>
        </div>
     
    <?php ActiveForm::end(); ?>

</div>

<div class="row">
    
    <div class="col-md-3" >
      <?= $form->field($model, 'currency_id')->dropDownList($accountList, ['prompt' => 'Select Account','disabled' => true ] ); ?>
    </div>

    <div class="col-md-9" >
      <?php  if($model->type == 1 ): ?>
        <?= $form->field($model, 'client_id' )->dropDownList($clientList, ['prompt' => 'Select customer', 'disabled' => true]); ?>
      <?php  elseif($model->type == 2): ?>
        <?= $form->field($model, 'provider_id' )->dropDownList($providerList, ['prompt' => 'Select Vendor', 'disabled' => true ]); ?>
      <?php  endif; ?>
    </div>    

</div>

