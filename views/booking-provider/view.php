<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Booking 
Html::encode($this->title = $model->loading_EDT=date("j F, Y", strtotime($date)))?></h5>
*/

//$this->title = $model->booking_number; 
$this->params['breadcrumbs'][] = ['label' => 'My Bookings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



$fileLabels = $model->attributeLabels();
$sysUrl = Yii::getAlias('@sysUrl');


foreach ($model->DocsFields  as $key => $field) {
   $preview[$field]   =  [ $sysUrl .  '/web/uploads/bookings/'. $model->booking_id .'/docs/'. $model[$field] ];
}


?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="container" > 
    <div class="row"> 
      <div class="col-md-4" >
        <h4>Booking <strong> <?= Html::encode($this->title = $model->booking_number)?> </strong></h4>
      </div>
      <div class="col-md-3" >
        <h5>Loading on  <?= date("j F, Y", strtotime($model->loading_EDT )) ?></h5> 
        <h2><?= $model->loadingPort->port_name ?></h2>  
      </div>
      <div class="col-md-3" >
        <h5>Dicharge on <?= date("j F, Y", strtotime($model->dicharge_ETA )  )?></h5>
        <h2><?= $model->dicharge_port ?></h2>
      </div>
    </div>


   <ul class="nav nav-tabs" >
    <li class="active"><a data-toggle="tab" href="#View">General View</a></li>
    <li><a href="#Containers" data-toggle="tab" >Containers</a></li>
  </ul>

<div class="container-fluid">
<div class="tab-content" >

<div id="View" class="tab-pane fade in active" >
    <div class="row">
      <div class="container" > 
        <div class="col-md-3" > 
          <h5>Origin</h5>
          <h2><?= empty($this->title = $model->pickupPlace->name) ? 'N/A' : $model->pickupPlace->name; ?></h2>
        </div>
        <div class="col-md-3" >
          <h5>Loading Port</h5> 
          <h2><?= Html::encode(empty($model->pickupPlace->portname) ? 'N/A' : $this->title = $model->portname)?></h2>
          <h5><?= date("j F, Y", strtotime($model->loading_EDT) ) ?></h5>
          <h5><?= $model->loading_EDT ?></h5>
        </div>
        <div class="col-md-3" >
          <h5>Discharge Port</h5>
          <h2><?= $model->dicharge_port ?></h2>
          <h5><?= date("j F, Y", strtotime($model->dicharge_ETA) )  ?></h5>
        </div>
        <div class="col-md-3" >
          <h5>Destiny </h5>
          <h2><?= $model->dicharge_port ?></h2>
          <h5><?= date("j F, Y", strtotime($model->dicharge_ETA )  )?></h5>
        </div>
     </div>
    </div>
    <div class="row" >
      <div class="progress" style="width:100%" >
             <div class="progress-bar" role="progressbar" aria-valuenow="60"
                  aria-valuemin="0" aria-valuemax="100" style="width:<?= $model->progress ?>%">
               <span ><?= $model->progress ?>%</span>
             </div>
        </div>
    </div>
</div>

<hr />

<div id="Containers" class="tab-pane" >
  <div class="row">
    <div class="col-md-12" >
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => null,

            'options' => [ 'class' => 'table' ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
               // 'container_ID',
                'comodity',
                [
                  'label' => 'Container Type', 
                  'value' => function ($model, $key, $index, $widget) { 
                      return $model->quantity.'x'.$model->type->container_name; 
                  },
                ],
                //'booking',
                'seal',
                'number',
                //'created_by',
                //'modified_by',
                //'created_at',
                //'modified_at',

                /*[
                'class' => 'yii\grid\ActionColumn',
                'template' => '<div class="btn-group" style="width:100px" >{update}{delete}</div>',
                'buttons' => [
                    'update' =>  function ($url, $model) {                        
                        return Html::a(
                            '<span class="glyphicon glyphicon-pencil" ></span>',
                            [
                                '/containers/update', 
                                'id' => $model->container_ID ],
                            [
                                'title' => 'Update',
                                'class' => 'btn btn-success'
                            ]
                        );
                    },                  
                    'delete' =>  function ($url, $model) {                        
                        return Html::a(
                            '<span class="glyphicon glyphicon-trash" ></span>',
                            [
                                '/containers/delete', 
                                'id' => $model->container_ID ],
                            [
                                'title' => 'Continuity',
                                'class' => 'btn btn-danger'
                            ]
                        );
                    },  
                ],
            ],*/

            ],
        ]); ?>
    </div>
   </div> 
 </div>   


</div>   
</div> 

<div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <h4>Booking Information</h4>
        <?= DetailView::widget([
            'model' => $model,
             'options' => [ 'class' => 'table' ],
            'attributes' => [
                [
                'label'=>'Vessel',
                'value'=>$model->vesselname,
                ], 
                'booking_number',
                //'client',
                'pick_up_place',
                /*[
                'label'=>'Loadin Port',
                'value'=>$model->portname,
                ], 
                'loading_EDT:date',
                'dicharge_port',
                'dicharge_ETA:date',*/
                //'commodity',
                'set_point',
                'created_at:date',
                'modified_at:date',
                [
                'label'=>'Created by',
                'value'=>$model->creatorname,
                ],    
                [
                'label'=>'Created by',
                'value'=>$model->modifiername,
                ],
            ],
        ]) ?>
      </div>
    </div>
    <div class="row" >
      <div class="col-md-12">
        <h4>Documents <small>Select the documents and click on the button at the end to save all files.</small></h4>

          <?php $form = ActiveForm::begin(); ?>

          <?php  foreach($model->DocsFields  as $key => $field) : ?>
          <div class="row" >
          <div class="col-md-6">  
          <label><?= $fieldLabels[$field.'_attach'] ?></label>

            <?= $form->field($model, $field.'_attach')->widget(FileInput::classname(), [
                'options' => ['accept' => 'pdf'],
                'pluginOptions' => [
                      'showPreview' => false,
                      'showCaption' => true,
                      'showRemove' => true,
                      'showUpload' => false,
                       'initialPreview'=> !empty($model[$field]) ? $preview[$field]  : false ,
                        'initialPreviewConfig' => [
                          ['caption' => $model[$field], 'size' => '1024', 'type' => 'pdf' , 'url'  => $xml_attach_preview, 'downloadUrl' => $preview[$file]  ]
                        ],
                ]
            ]); 
            ?>
          </div>
          <?php if($model[$field] !== "" ): ?>
            <div class="col-md-6" > 
              <div class="alert alert-success" style="margin-top: 35px">
                <strong>File  is already set</strong> <a target="_blank" href="<?= $preview[$field][0] ?>" >View</a>
              </div> 
           </div>
          <?php endif;  ?>
          </div>
          <?php endforeach; ?>
          <br />

          <p><?=  Html::submitButton('Save & Update files', ['class' => 'btn btn-success']) ?></p>

          <?php ActiveForm::end(); ?>
        </div>   
      </div>
    </div>
</div>

</div>