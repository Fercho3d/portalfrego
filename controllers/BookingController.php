<?php

namespace app\controllers;

use Yii;
use app\models\Booking;
use app\models\BookingContinuity;
use app\models\BookingSearch;
use app\models\Client;
use app\models\Vessel;
use app\models\LoadingPorts;
use app\models\Carrier;
use app\models\FieldsByClient;
use app\models\FilesByBooking;
use app\models\Containers;
use app\models\CheckList;
use app\models\ContainersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\HtmlPurifier; 
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use \kartik\mpdf\Pdf;

//use \kartik\mpdf\Pdf;

/**
 * BookingController implements the CRUD actions for Booking model.
 */

class BookingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'user' => 'user',
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'pdf' , 'provider', 'upload', 'save-intructions', 'delete-file' ],
                        'roles' => ['@']
                    ],   
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete' ],
                        'roles' => ['@'],
                         'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->access == 10 ;
                        }
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
       $searchModel = new BookingSearch();
       $dataProvider  = $searchModel->search(Yii::$app->request->queryParams);
       return $this->render('index', [
           'searchModel' => $searchModel, 
           'dataProvider' => $dataProvider,
       ]);
   } 


    public function actionProvider(){
        
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
     public function actionCreate($tab = "", $isDraf = 1)  
       {
        $model = new Booking();

        $load = $model->load(Yii::$app->request->post());


        if($isDraf==0){

            $model->is_draft = 0;

        }else{

             $model->is_draft = 1;
        }

        
        if( $model->vessel == -1){
            $vessel = new Vessel();
            $vessel->vessel_name = $model->vessel_new;
            $vessel->save();
            $model->vessel = $vessel->vessel_id;
        }         

        if( $model->loading_port == -1 ){
            $port = new LoadingPorts();
            $port->port_name = $model->port_new;
            $port->save();
            $model->loading_port = $port->port_id;
        }  

        if( $model->dicharge_port_id == -1 ){
            $port = new DichargePort();
            $port->name = $model->dicharge_new;
            $port->save();
            $model->dicharge_port_id = $port->dicharge_port_id;
        } 

        if($model->carrier == -1 ){
            $carrier = new Carrier();
            $carrier->name = $model->carrier_new ;
            $carrier->save();
            Yii::debug( $carrier->saveError() );
            $model->carrier = $carrier->carrier_id;
        } 
        
        if($_FILES){ 
            foreach ($model->DocsFields as $key => $file) {
                $file_attach = $file.'_attach';
                $model[$file_attach] =  UploadedFile::getInstance( $model, $file_attach );
                if(!$model[$file_attach]->baseName == ""){
                    $model[$file] = $model[$file_attach]->baseName . '.' . $model[$file_attach]->extension;
                }
            }
        }

        $model->client = Yii::$app->user->identity->client->client_id;
        $model->booking_type = 1;

        if ($load && $model->save()) {
            
            if($model->is_draft == 0){
                $this->sendCreateEmail($model, $dataProvider_contrs);
                // $model->generateInvoice();
            }

            return $this->redirect( ['update', 'id' => $model->booking_id, 'tab' => $tab ] );

        }else{
           Yii::debug($model->saveError());
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $isDraft = $model->is_draft;

        $load = $model->load(Yii::$app->request->post());

        $model->is_draft = ($model->is_draft == 1 && $load) ? 0 : $model->is_draft; 


       if( $model->vessel == -1){
            $vessel = new Vessel();
            $vessel->vessel_name = $model->vessel_new;
            $vessel->save();
            $model->vessel = $vessel->vessel_id;
        }         

        if( $model->loading_port == -1 ){
            $port = new LoadingPorts();
            $port->port_name = $model->port_new;
            $port->save();
            $model->loading_port = $port->port_id;
        }  

        if($model->carrier == -1 ){ 
            $carrier->name = $model->carrier_new ;
            $carrier->save();
            Yii::debug( $carrier->saveError() );
            $model->carrier = $carrier->carrier_id;
        }  

        $searchModel_contrs = new ContainersSearch(); 
        $dataProvider_contrs = $searchModel_contrs->search(Yii::$app->request->queryParams,$model->booking_id);
        
        $fileFields = FieldsByClient::find()
        ->select([
        'fields_by_client.field_id',
        'fields_by_client.client_id',
        'fb.booking_id',
        'fb.booking_file_id',
        'fb.value' ] )
        ->leftJoin('files_by_booking fb', 'fb.booking_id=:booking AND fb.field_id =fields_by_client.field_id' )
        ->where(['client_id'=> $model->client ])
        ->params([ 'booking' => $model->booking_id ])
        ->all();
            

        if($load && $model->save()) {
            $sucessMsg = "Booking information has been successfully updated";
            if($isDraft == 1 && $model->is_draft == 0){
                $this->sendCreateEmail($model, $dataProvider_contrs);
                // $model->generateInvoice();
                // $model->generateBills(1);//for carriers
                // $model->generateBills(2) ;// for transport
                // $model->generateBills(3);  // for custom brocker
                $sucessMsg = "Booking confirmation has been successfully sent";
            }

            return $this->redirect([
                'update', 
                'id' => $model->booking_id,
                'invoice'=> $invoice,
                'carrier' => $carrirer,
                'transport' => $transport,
                'success'=> $sucessMsg,
             ]);
        }

        return $this->render('update', [
            'model' => $model,
            'searchModel_contrs' => $searchModel_contrs,
            'dataProvider_contrs' => $dataProvider_contrs,
            'fileFields'=>$fileFields, 
            //'searchModel_providers' => $searchModel_providers,
            //'dataProvider_providers' => $dataProvider_providers,
        ]);
    }

    public function actionSaveIntructions($id){

            $model = $this->findModel($id);
            $model->scenario = 'instructions';

            if($model->load(Yii::$app->request->post()) && $model->save()){
                
                return $this->redirect(['view', 'id' => $model->booking_id]);
                
            }

    }




    public function actionUpload($id,$field_id) {

            $types= [
              'jpg' =>'image',
              'png' =>'image',
              'jpge' =>'image',
              'pdf' => 'pdf',
              'zip' => 'file',
              'jfif' => 'file',
              'xml' => 'file'
            ];

            $this->enableCsrfValidation = false;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $model = $this->findModel($id);
            $fileFields = FieldsByClient::find()
             ->select([
            'fields_by_client.field_id',
            'fields_by_client.client_id',
            'fb.booking_id',
            'fb.booking_file_id',
            'fb.value' ] )
            ->leftJoin('files_by_booking fb', 'fb.booking_id=:booking AND fb.field_id =fields_by_client.field_id' )
            ->where(['client_id'=> $model->client ])
            ->andWhere(['fields_by_client.field_id'=> $field_id ])
            ->params([ 'booking' => $model->booking_id ])
            ->all();
            
            $bookingUrl = Yii::getAlias('@sysUrl') . '/web/uploads/bookings/'. $model->booking_id .'/docs/';

            if($_FILES){ 
                foreach ($fileFields as $key => $field) {

                    $files =  UploadedFile::getInstancesByName($field->field->field);
                    
                    if(count($files)){ 

                        foreach ($files as $key => $file) {

                                $fileName .= str_replace('/', '-', $file->baseName.'.' .$file->extension );
                                $model->uploadFile($file, $fileName );
                                $values[] = $fileName;
                                $extensions[] = $file->extension;
                                if(!$model->uploadFile($file, $rowModel->value) ){
                                    return [
                                        'sucesss'=> false, 
                                        'error' => 'There was an error to upload the file, contact tech support' 
                                    ];
                                };
                        }
                    
                        if(empty($field->booking_file_id)){
                            $rowModel = new FilesByBooking();
                            $rowModel->booking_id = $model->booking_id;
                            $rowModel->field_id = $field->field_id;
                            $rowModel->value = implode(' / ', $values);
                        } else {
                            $rowModel = FilesByBooking::findOne($field->booking_file_id);
                            $rowModel->value .=' / '. implode(' / ', $values);
                        }   

                        if($rowModel->save()){
                            
                            foreach ($values as $key => $value) {

                                $initialPreview[] = $bookingUrl . $value ;

                                $initialPreviewConfig[] = [
                                  'caption' => $value , 
                                  'type'=> $types[$extensions[$key]] , 
                                  'downloadUrl' => $bookingUrl. $value ,  
                                  'url'=>'delete-file',
                                  'key'=> $value , 
                                  'extra'=>[ 'id' => $rowModel->booking_file_id ]
                                ];
                            }

                              return  [
                                'initialPreview' => $initialPreview,
                                'initialPreviewAsData' => true,
                                'initialPreviewConfig' => $initialPreviewConfig
                             ];
                          
                        }else{
                             \yii::info($rowModel->saveError());

                             return ['sucesss'=> false];
                        };
                    }
                }

            }else{
                \yii::info('no files');
            
            }

        return $this->redirect(['view', 'id' => $model->booking_id]);

    }

    public function actionDeleteFile(){
        $this->enableCsrfValidation = false;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $rowModel = FilesByBooking::findOne($_POST['id']);
        $files = explode(' / ',$rowModel->value);
        $key = array_search($_POST['key'], $files);
         unset($files[$key]);
         $rowModel->value = implode(' / ', $files);
         if($rowModel->save()){
            return ['sucesss'=> true];
         }else{
            return ['sucesss'=> false];
         }
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

      
        $fileFields = FieldsByClient::find()
        ->select([
        'fields_by_client.field_id',
        'fields_by_client.client_id',
        'fb.booking_id',
        'fb.booking_file_id',
        'fb.value' ] )
        ->leftJoin('files_by_booking fb', 'fb.booking_id=:booking AND fb.field_id =fields_by_client.field_id' )
        ->where(['client_id'=> $model->client ])
        ->params([ 'booking' => $model->booking_id ])
        ->orderBy('fields_by_client.customer_field_id ASC')
        ->all();
        
        $contModel = BookingContinuity::findOne(['booking' => $model->booking_id ]);
        $checkList = checkList::findOne([ 'booking'=> $model->booking_id ]);

        return $this->render('view', [
            'booking' => $model,
            'contModel' =>$contModel,
            'checkList' => $checkList,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'fileFields' => $fileFields
        ]);
    }

    public function sendCreateEmail($model,$dataProvider_contrs){

            /* Generate  PDF */   
            $pdfContent = $model->generateBokingConfirmation();
            $pdf_FileName = $model->sanitize_file_name($model->booking_number); 
            $full_pathFile = Yii::getAlias('@webfolder').'/web/uploads/bookings/booking_'.$pdf_FileName.'.pdf';
            $pdf = new Pdf([
            'content' => $pdfContent,
            'marginTop' => 7,
            'marginBottom' => 7,
            'marginLeft' => 7,
            'marginRight' => 7, 
            'destination' => Pdf::DEST_FILE , 
            'format' => Pdf::FORMAT_LETTER,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'cssInline' => $model->generateCss(),
            'options' => ['title' => $model->booking_number ],
            'methods' => [
            'SetHeader'=>[''],
            ]
            ]);
                             
            $mpdf = $pdf->api;
            $mpdf->WriteHTML($model->generateCss(),1); //pdf is a name of view file responsible for this pdf document
            $mpdf->WriteHTML($pdfContent,2); //pdf is a name of view file responsible for this pdf document
            $path = $mpdf->Output($full_pathFile , 'F');// THIS WILL SAVE THE FILE IN PATH     

            $client = Client::findOne($model->client);
            
            if(!empty($client->email_notification)){ 
            $emailList = explode(',', $client->email_notification);
            
            if(count($emailList)>0){ 
                Yii::$app->mailer->compose(
                    '@app/mail/createdBookingMail', 
                    ['model' => $model, 'containers' => $dataProvider_contrs ]
                )
                ->setFrom(['sistema@frego.juancker.com'  => 'Sistema Frego'])
                ->setTo($emailList)
                ->attach($full_pathFile)
                ->setSubject('Booking ['. $model->booking_number. "]")
                ->send();
            }
        }
     }



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
     * Deletes an existing Booking model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
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
        if (($model = Booking::findOne(['booking_id' => $id,'client' => \Yii::$app->user->identity->client->client_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
