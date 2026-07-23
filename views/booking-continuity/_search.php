<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\BookingContinuitySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="booking-continuity-search" >

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'cont_id') ?>

    <?= $form->field($model, 'booking') ?>

    <?= $form->field($model, 'pickup_date') ?>

    <?= $form->field($model, 'modality') ?>

    <?= $form->field($model, 'vacuum_maneuver') ?>

    <?php // echo $form->field($model, 'doc_cut_of') ?>

    <?php // echo $form->field($model, 'SI_date') ?>

    <?php // echo $form->field($model, 'draf_client') ?>

    <?php // echo $form->field($model, 'gated_IN') ?>

    <?php // echo $form->field($model, 'cleared') ?>

    <?php // echo $form->field($model, 'departure') ?>

    <?php // echo $form->field($model, 'bl_payment') ?>

    <?php // echo $form->field($model, 'swb') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
