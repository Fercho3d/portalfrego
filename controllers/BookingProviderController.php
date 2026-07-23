<?php

namespace app\controllers;

use Yii;
use app\models\Booking;
use app\models\BookingProviderSearch;
//use app\models\Client;
use app\models\Containers;
use app\models\ContainersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\HtmlPurifier; 
use yii\web\UploadedFile;
use yii\filters\AccessControl;
//use \kartik\mpdf\Pdf;

/**
 * BookingController implements the CRUD actions for Booking model.
 */
class BookingProviderController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => 'provider',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'pdf' , 'provider' ],
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Booking models.
     * @return mixed
     */
  public function actionIndex()
   {
       $searchModel = new BookingProviderSearch();
       $dataProvider  = $searchModel->search(Yii::$app->request->queryParams);
       return $this->render('index', [
           'searchModel' => $searchModel, 
           'dataProvider' => $dataProvider,
       ]);
   } 

    public function actionProvider()
       {
           $searchModel = new BookingProviderSearch();
           $dataProvider  = $searchModel->search(Yii::$app->request->queryParams);
           return $this->render('index', [
               'searchModel' => $searchModel, 
               'dataProvider' => $dataProvider,
           ]);
       }

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Booking();
        
        if($_FILES){ 
            foreach ($model->DocsFields as $key => $file) {
                $file_attach = $file.'_attach';
                $model[$file_attach] =  UploadedFile::getInstance( $model, $file_attach );
                if(!$model[$file_attach]->baseName == ""){
                    $model[$file] = $model[$file_attach]->baseName . '.' . $model[$file_attach]->extension;
                }
                Yii::debug($model[$file]);
                Yii::debug($model[$file_attach]->baseName);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            $model->uploadDocs();

            $this->sendEmail($model);

            return $this->redirect(['update', 'id' => $model->booking_id]);
        }else{
           Yii::debug($model->saveError());
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }
 
    /**
     * Displays a single Booking model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $searchModel = new ContainersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams,$model->booking_id);

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpload($id)
    {
        $model = $this->findModel($id);

        if($_FILES){ 
            $model->pdf_attach_file =  UploadedFile::getInstance( $model, 'pdf_attach_file');
            //$model->xml_attach_file =  UploadedFile::getInstance( $model, 'xml_attach_file');
              
            $model->pdf_attach = empty($model->pdf_attach_file->name) ? '' : $model->pdf_attach_file->baseName.'.' . $model->pdf_attach_file->extension;
            //$model->xml_attach = empty($model->xml_attach_file->name) ? '' : $model->xml_attach_file->baseName.'.' . $model->xml_attach_file->extension;

            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        
                $model->pdf_attach_file = UploadedFile::getInstance( $model, 'pdf_attach_file');
               // $model->xml_attach_file = UploadedFile::getInstance( $model, 'xml_attach_file');

                if($_FILES){ 
                    $model->upload();
                }

                return $this->redirect(['view', 'id' => $model->transc_id]);
            }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->upload();
             return $this->redirect(['view', 'id' => $model->transc_id]);

        }
        
        echo 'An error has been ocurred';
        //return $this->redirect(['view`', 'id' => $model->transc_id]);
    
    }

     /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    /*public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        //Charge Data
        $searchModel = new ChargeSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $model->transc_id
        );


        if($_FILES){ 
            $model->pdf_attach_file =  UploadedFile::getInstance( $model, 'pdf_attach_file');
            //$model->xml_attach_file =  UploadedFile::getInstance( $model, 'xml_attach_file');

                $model->pdf_attach = empty($model->pdf_attach_file->name) ? '' : $model->pdf_attach_file->baseName.'.' . $model->pdf_attach_file->extension    ;
                //$model->xml_attach = empty($model->xml_attach_file->name) ? '' : $model->xml_attach_file->baseName.'.' . $model->xml_attach_file->extension    ;

            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                        
                $model->pdf_attach_file = UploadedFile::getInstance( $model, 'pdf_attach_file');
                //$model->xml_attach_file = UploadedFile::getInstance( $model, 'xml_attach_file');

                if($_FILES){ 
                    $model->upload();
                }

                return $this->redirect(['update', 'id' => $model->transc_id]);
            }
        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
             $model->upload();

            return $this->redirect(['update', 'id' => $model->transc_id]);

        }

        return $this->render('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }*/

        public function actionPdf($booking){
                    
                $model = $this->findModel($booking);
                            
                /* Generate  PDF */   
                $pdfContent = $model->generateBokingConfirmation();
                $pdf_FileName = $model->booking_number; 
                $full_pathFile = Yii::getAlias('@webfolder').'/web/uploads/bookings/booking_'.$pdf_FileName.'.pdf';
                $pdf = new Pdf([
                'content' => $pdfContent,
                'marginTop' => 7,
                'marginBottom' => 7,
                'marginLeft' => 7,
                'marginRight' => 7, 
                'destination' => Pdf::DEST_DOWNLOAD, 
                //'destination' => Pdf::DEST_FILE , 
                // set to use core fonts only
                //'mode' => Pdf::MODE_CORE,
                // A4 paper format
                'format' => Pdf::FORMAT_LETTER,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                //'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                'cssInline' => $model->generateCss(),
                // set mPDF properties on the fly
                'options' => ['title' => $model->booking_number ],
                // call mPDF methods on the fly
                'methods' => [
                'SetHeader'=>[''],
                //'SetFooter'=>['{PAGENO}'],
                ]
                ]);
                
                $mpdf = $pdf->api;
                $mpdf->WriteHTML($model->generateCss(),1); //pdf is a name of view file responsible for this pdf document
                $mpdf->WriteHTML($pdfContent,2); //pdf is a name of view file responsible for this pdf document
                $path = $mpdf->Output($full_pathFile , 'F');// THIS WILL SAVE THE FILE IN PATH

                return Yii::$app->response->sendFile($full_pathFile, $pdf_FileName,  ['inline'=>true] );


            }


    /**
     * Finds the Booking model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Booking the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Booking::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
