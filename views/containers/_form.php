<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use app\models\booking;
use app\models\ContainerTypes;
use kartik\number\NumberControl;

/* @var $this yii\web\View */
/* @var $model app\models\Containers */
/* @var $form yii\widgets\ActiveForm */

$dispOptions = ['class' => 'form-control kv-monospace'];

$saveOptions = [
    'type' => 'hidden', 
    'label'=> false, 
    'class' => 'kv-saved',
    'readonly' => true, 
    'tabindex' => 1000
];

$saveCont = ['class' => 'kv-saved-cont'];

$containerList =  ContainerTypes::getList();

?>
<div class="containers-form col-md-4">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'quantity')->widget(NumberControl::classname(), [
    'maskedInputOptions' => [
        'allowMinus' => false
    ],
    'options' => $saveOptions,
    'displayOptions' => $dispOptions,
    'saveInputContainer' => $saveCont
    ]);

    ?>
    <?= $form->field($model, 'comodity')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'seal')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'container_type')->dropDownList($containerList, ['prompt' => 'Select Container Type' ]); ?>

    <div class="form-group">
        <div class="btn-group">
        <?= \yii\helpers\Html::a( 'Cancel', [ '/booking/update', 'id' => $_GET['booking'], 'tab' => 'container' ] , ['class' => 'btn btn-danger']) ?>
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

  </div>
    <?php ActiveForm::end(); ?>

</div>
