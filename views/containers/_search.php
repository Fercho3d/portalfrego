<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ContainersSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="containers-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'container_ID') ?>

    <?= $form->field($model, 'quantity') ?>

    <?= $form->field($model, 'comodity') ?>

    <?= $form->field($model, 'type_data') ?>

    <?= $form->field($model, 'booking_data') ?>

    <?= $form->field($model, 'number') ?>

    <?= $form->field($model, 'seal') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'modified_by') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
