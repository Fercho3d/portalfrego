<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Charge */

$this->title = 'Create Charge';
$this->params['breadcrumbs'][] = ['label' => 'Charges', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$transaction = $_GET['transaction'] ? $_GET['transaction'] :  $transaction ; 

?>
<div class="modal-header">

    <h4><?= Html::encode($this->title) ?></h41>
</div>
<div class="modal-body">
    <?= $this->render('_form', [
        'model' => $model,
        'transaction'=> $transaction,
        'error'=> $error
    ]) ?>
</div>
