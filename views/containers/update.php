<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Containers */

$this->title = 'Update Containers: ' . $model->container_ID;
$this->params['breadcrumbs'][] = ['label' => 'Containers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->container_ID, 'url' => ['view', 'id' => $model->container_ID]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="containers-update" style="margin-top:25px" >

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
