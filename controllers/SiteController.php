<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\LoginProvider;
use app\models\ContactForm;

use yii\web\Session;
use app\models\RecoverPass;
use app\models\RessetPass;
use yii\helpers\Url;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
 public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                
                'rules' => [
                    [
                        'actions' => ['login', 'logout', 'index' ],
                        'allow' => true,
                         'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout','index' ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
    */

    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(){
   

      if(!Yii::$app->user->getIsGuest()){

        if(Yii::$app->user->identity->access == 10 ){

           return $this->redirect(['/booking/']);

         }elseif(Yii::$app->user->identity->access == 11){

            return $this->redirect(['/transaction/all']);

        }

      }

      if(!Yii::$app->user->getIsGuest()){
        return $this->redirect(['/booking-provider/']);
      }

        return $this->render('index');
    }
    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
    
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/']);
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }


    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }    

    public function actionLogoutprov()
    {
       return $this->redirect(['/site/login']);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */

    /**
     * Displays about page.
     *
     * @return string
     */

    public function actionRecoverpass()
    {
      $model = new RecoverPass;
      $msg = null;
      
      if ($model->load(Yii::$app->request->post()))
      {
       if ($model->validate())
       {
        $table = client::find()->where("email=:email", [":email" => $model->email]);
        if ($table->count() == 1)
        {
         $session = new Session;
         $session->open();
         $session["recover"] = $this->randKey("abcdef0123456789", 200);
         $recover = $session["recover"];
         $table = client::find()->where("email=:email", [":email" => $model->email])->one();
         $session["id_recover"] = $table->id;
         $verification_code = $this->randKey("abcdef0123456789", 8);
         $table->verification_code = $verification_code;
         $table->save();
         
         $subject = "Recuperar password";
         $body = "<p>Copie el siguiente código para restablecer su password";
         $body .= "<strong>".$verification_code."</strong></p>";
         // Change for a redict with an user friendly URL para site/ressetpass
         //$body .= "<p><a href='http://clientefrego.juancker.com/web/index.php?r=site/ressetpass'>Recuperar password</a></p>";
         //return $this->redirect(['/site/ressetpass']);
         echo Url::to(['site/ressetPass']);

         Yii::$app->mailer->compose()
         ->setTo($model->email)
         ->setFrom([Yii::$app->params["adminEmail"] => Yii::$app->params["title"]])
         ->setSubject($subject)
         ->setHtmlBody($body)
         ->send();
         
         $model->email = null;
         
         $msg = "Le hemos enviado un mensaje a su cuenta de correo para resetear su password";
        }
        else 
        {
         $msg = "Ha ocurrido un error";
        }
       }
       else
       {
        $model->getErrors();
       }
      }
      return $this->render("recoverpass", ["model" => $model, "msg" => $msg]);
     }
     
     public function actionRessetpass()
     {
      $model = new RessetPass;
      $msg = null;
      $session = new Session;
      $session->open();
      if (empty($session["recover"]) || empty($session["id_recover"]))
      {
       return $this->redirect(["site/index"]);
      }
      else
      {
       $recover = $session["recover"];
       $model->recover = $recover;
       $id_recover = $session["id_recover"];
      }
      
      if ($model->load(Yii::$app->request->post()))
      {
       if ($model->validate())
       {
        if ($recover == $model->recover)
        {
         $table = client::findOne(["email" => $model->email, "id" => $id_recover, "verification_code" => $model->verification_code]);
         $table->password = crypt($model->password, Yii::$app->params["salt"]);
         if ($table->save())
         {
          $session->destroy();
          
          $model->email = null;
          $model->password = null;
          $model->password_repeat = null;
          $model->recover = null;
          $model->verification_code = null;
          
          $msg = "Password reseteado correctamente, redireccionando a la página de login";
          $msg .= "<meta http-equiv='refresh' content='5; ".Url::toRoute("site/login")."'>";
         }
         else
         {
          $msg = "Ha ocurrido un error";
         }
         
        }
        else
        {
         $model->getErrors();
        }
       }
      }
      return $this->render("ressetpass", ["model" => $model, "msg" => $msg]);
     }
}