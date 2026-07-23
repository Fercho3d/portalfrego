<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="client-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Customer', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'client_id',
            //'rfc',
            'email:email',
            //'fullName',
            //'address',
            //'state',
            //'city',
            //'password',
            //'auth_key',
            //'password_reset_token',
            //'postal_code',
            //'phone',
            //'created_by',
            //'modified_by',
            //'created_at',
            //'modified_at',

            ['class' => 'yii\grid\ActionColumn', 
                'template' => '<div class="btn-group">{update}  {delete} </div>',
                'contentOptions' => ['style' => 'width: 8.7%'],
                'visible'=> Yii::$app->user->isGuest ? false : true,
                'buttons'=>[
                'Guardar'=>function ($url, $model) {
                    $t = 'index.php?r=site/Guardar&id='.$model->id;
                    return Html::button('<span class="fa fa-eye"></span>', ['value'=>Url::to($t), 'class' => 'btn btn-success btn-xs custom_button']);
                        },
                'Cancelar'=>function ($url, $model) {
                    $t = 'index.php?r=site/Cancelar&id='.$model->id;
                    return Html::button('<span class="fa fa-pencil"></span>', ['value'=>Url::to($t), 'class' => 'btn btn-danger btn-xs custom_button']);
                        },
            ],
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
