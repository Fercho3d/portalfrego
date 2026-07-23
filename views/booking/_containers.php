<?php  
use yii\grid\GridView;


?> <div class="row">
    <div class="col-md-12" >
        <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => null,
            
            'options' => [ 'class' => 'table' ],
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                //'container_ID',
                'comodity',
                [
                  'label' => 'Container Type', 
                  'value' => function ($model, $key, $index, $widget) { 
                      return $model->quantity.'x'.$model->type->container_name; 
                  },
                ],
                'seal',
                'number',
            ],
        ]); ?>
    </div>
   </div> 