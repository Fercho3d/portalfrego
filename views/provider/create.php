<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Provider */

/*$this->title = 'Create Provider';
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;*/

$this->title = 'Create Providers';
$this->params['breadcrumbs'][] = ['label' => 'Booking', 'url' => ['/booking' ]];
$this->params['breadcrumbs'][] = ['label' => 'Providers', 'url' => ['index','booking' =>$_GET['booking'] ] ];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="provider-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
