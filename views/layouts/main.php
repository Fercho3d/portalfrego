<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);



if(Yii::$app->user->identity->access == 10 ){

    $company = Yii::$app->user->identity->client->fullName; 

}elseif(Yii::$app->user->identity->access == 11 ){

    $company = Yii::$app->user->identity->provider->fullName; 

}


$items = array();

$homeUrl = ['/'];

if(!Yii::$app->user->isGuest ){

    if(Yii::$app->user->identity->access == 10 ){

        $items[] =  ['label' => 'My Bookings','url' => ['/booking/']]; 
       

        $homeUrl =  ['/booking/'];
    }

     if(Yii::$app->user->identity->access == 10 && Yii::$app->user->identity->role != 16){ 

     $items[] =  ['label' => 'Invoice', 'url' => ['/transaction/invoice'] ]; 

     }

}

if(Yii::$app->user->identity->access == 11){
    
        $items[] =  ['label' => 'Request for payment', 'url' => ['/transaction/all'] ];  
        $items[] =  ['label' => 'Check Requested payments', 'url' => ['/payment-request'] ]; 
}


if(!Yii::$app->user->isGuest  ){

      $items[] =  '<li>'
                    . Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Logout (' . Yii::$app->user->identity->name . ') ('. $company .')' ,
                        ['class' => 'btn btn-link logout']
                    )
                    . Html::endForm()
                . '</li>';
}


?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>



<div class="wrap">

    <?php

    NavBar::begin([
        'brandLabel' => Html::img('@web/img/logo-light.png', ['alt'=>Yii::$app->name] ),
        'brandUrl' => $homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ], 
    ]);

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items
    ]);
    
    NavBar::end();
    ?>
  

    <div class="container full-width" >
            <?php if(!Yii::$app->user->isGuest): ?>
                <?= Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    'homeLink' => ['label'=> 'Bookings', 'url'=> ['/'] ]
                ]) ?>
            <?php endif ?>        
            <?= Alert::widget() ?>
            <?= $content ?>
    </div>
</div>


<div class="modal remote fade" id="form" data-keyboard="false" data-backdrop="static"  >
        <div class="modal-dialog" >
            <div class="modal-content" >
            </div>
      </div>
</div>


<div class="modal remote fade" id="charge-form" data-keyboard="false" data-backdrop="static" >
        <div class="modal-dialog" >
            <div class="modal-content" >
            </div>
      </div>
</div>

<div class="modal remote fade" id="request-form" data-keyboard="true" data-backdrop="static" >
        <div class="modal-dialog" >
            <div class="modal-content" >
            </div>
      </div>
</div>


<div id="pdf-viz-modal" class="modal medium fade" data-keyboard="false" data-backdrop="static" >
     <div class="modal-dialog" >
        <div class="modal-content" >
            <div class="modal-body" >
            
            </div>
            <div class="modal-footer" >
                <button type="button" class="btn btn-default" data-dismiss="modal" >Close</button>
            </div>
        </div>
      </div>
</div>



<footer class="footer" >
    <div class="container" >
        <p class="pull-left">&copy; Frego <?= date('Y') ?></p>
        <p class="pull-right">Powered by <?= Html::a('Juancker', 'https://juancker.com', [ 'target' => '_blank' ] ) ?></p>
    </div>
</footer>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>





