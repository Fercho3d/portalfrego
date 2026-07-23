<?php

namespace app\controllers;

use Yii;
use app\models\PaymentRequest;
use app\models\PaymentRequestSearch;
use app\models\TransactionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use \kartik\mpdf\Pdf;
use yii\helpers\FileHelper;


/**
 * PaymentRequestController implements the CRUD actions for PaymentRequest model.
 */
class PaymentRequestController extends Controller
{
    /**
     * {@inheritdoc}
     */
  public function behaviors(){
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'document', 'view'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->access == 11 ;
                        }
                    ],
                ],
            ],
        ];
    }
    /**
     * Lists all PaymentRequest models.
     * @return mixed
     */

      public function actionIndex($id = false){
             
          $searchModel = new PaymentRequestSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

          return $this->render('index', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
              'open' => $id
          ]);
       }

      public function actionView($id){
         $error = false;
         $success = false;
         $model = $this->findModel($id);
         $searchModel = new TransactionSearch();
         $searchModel->request_id = $model->request_id;
         $searchModel->noNegative = false;
         $searchModel->paymentMode = true;
         $dataProvider = $searchModel->search(null);
         return $this->renderAjax('update', [
           'model' => $model,
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
           'success' => $success,
           'error' => $error
         ]);

       }



    protected function findModel($id)
    {
        if (($model = PaymentRequest::findOne(['request_id' => $id, 'provider_id' => Yii::$app->user->identity->provider->provider_id ]) ) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function createDir($dir){
        //echo $dir;
        FileHelper::createDirectory($dir, $mode = 0775, $recursive = true);

        if(!is_writable($dir)){
            "no write permisions :(" ;
        }
    }

      public function actionDocument($id){

          $model = $this->findModel($id);
          $dir = Yii::getAlias('@webfolder').'/web/uploads/request/'.$model->request_id.'/';
          $this->createDir($dir);
          $pdf_FileName = 'request_'.str_pad($model->request_id, 3, '0', STR_PAD_LEFT); 
          $full_pathFile = $dir.'request_'.$pdf_FileName.'.pdf';
          $pdf = new Pdf([
          'content' => $model->generateDocument(),
          'marginTop' => 7,
          'marginBottom' => 7,
          'marginLeft' => 7,
          'marginRight' => 7, 
          'destination' => Pdf::DEST_DOWNLOAD, 
          'format' => Pdf::FORMAT_LETTER,
          'orientation' => Pdf::ORIENT_PORTRAIT,
          'cssInline' => $model->generateCss(),
          'options' => ['title' => 'Payment Request'. str_pad($request->number, 3, '0', STR_PAD_LEFT) ]
           ]);

      $mpdf = $pdf->api;
      $mpdf->WriteHTML($model->generateCss(),1); //pdf is a name of view file responsible for this pdf document
      $mpdf->WriteHTML($model->generateDocument(),2); //pdf is a name of view file responsible for this pdf document
      $path = $mpdf->Output($full_pathFile , 'F');// THIS WILL SAVE THE FILE IN PATH

      return Yii::$app->response->sendFile($full_pathFile, $pdf_FileName.'.pdf',  ['inline'=>true] );

     }
}
