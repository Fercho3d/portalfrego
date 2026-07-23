<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Containers */

$this->title = 'Add Container';
$this->params['breadcrumbs'][] = ['label' => 'bookings', 'url' => [ '/booking/update', 'id' => $_GET['booking']  ] ];
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="containers-create" style="margin-top:50px" >

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
