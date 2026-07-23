<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\TransactionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="transaction-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'transc_id') ?>

    <?= $form->field($model, 'tran_date') ?>

    <?= $form->field($model, 'tran_number') ?>

    <?= $form->field($model, 'account') ?>

    <?= $form->field($model, 'booking') ?>

    <?php // echo $form->field($model, 'vendor') ?>

    <?php // echo $form->field($model, 'xml_attch') ?>

    <?php // echo $form->field($model, 'pdf_attach') ?>

    <?php // echo $form->field($model, 'bill_address') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
