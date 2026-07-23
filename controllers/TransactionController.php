<?php

namespace app\controllers;

use Yii;
use app\models\Transaction;
use app\models\ChargeSearch;
use app\models\Charge;
use app\models\Bank;
use app\models\PaymentRequest;
use app\models\Booking; 
use app\models\User;
use app\models\TransactionSearch;
use app\models\TransactionReport;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\HtmlPurifier; 
use yii\web\UploadedFile;
use yii\filters\AccessControl;

/**
 * TransactionController implements the CRUD actions for Transaction model.
 */
class TransactionController extends Controller
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
                        'allow' => true,
                        'actions' => ['index', 'view', 'update', 'all','request', 'pdf-bill', 'xml-bill'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->access == 11 ;
                        }
                    ],                 
                    [
                        'allow' => true,
                        'actions' => ['invoice', 'pdf-invoice', 'xml-invoice','set-processed'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->access == 10 && Yii::$app->user->identity->role != 16 ;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Transaction models.
     * @return mixed
     */
    public function actionIndex($booking)
    {
        $searchModel = new TransactionSearch();
        $searchModel->booking = $booking;
  
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $booking = Booking::findOne($booking);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'booking'=> $booking
        ]);
    }   

    public function actionPdfInvoice($id){
      $model = Transaction::findOne(['transc_id'=> $id, 'customer' => \Yii::$app->user->identity->client->client_id ]);
        
      return \Yii::$app->response->sendFile($model->pdfFile, $model->pdf_attach, ['inline'=>true]);
    }    

    public function actionXmlInvoice($id){
      $model = Transaction::findOne(['transc_id'=> $id, 'customer' => \Yii::$app->user->identity->client->client_id ]);
      return \Yii::$app->response->sendFile($model->xmlFile);
    }

    public function actionPdfBill($id){
      $model = Transaction::findOne(['transc_id'=> $id, 'vendor' => \Yii::$app->user->identity->provider->provider_id ]);
      return \Yii::$app->response->sendFile($model->pdfFile,$model->pdf_attach, ['inline'=>true]);
    }    

    public function actionXmlBill($id){
      $model = Transaction::findOne(['transc_id'=> $id, 'vendor' => \Yii::$app->user->identity->provider->provider_id ]);
      return \Yii::$app->response->sendFile($model->pdfFile);
    }

    public function actionAll(){
        $searchModel = new TransactionSearch();
        $searchModel->vendor = Yii::$app->user->identity->provider->provider_id;
        $searchModel->noNegative = false;
        $searchModel->paymentMode = true;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $booking = Booking::findOne($booking);
        return $this->render('all', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'booking'=> $booking
        ]
      );
    }        

    public function actionInvoice(){
        $searchModel = new TransactionSearch();
        $searchModel->unInvoicedBookings = true;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('invoice', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]
      );
    }   

    public function actionReport()
    {
        $searchModel = new TransactionReport();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Transaction model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    /**
     * Creates a new Transaction model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Transaction();

        $model->scenario = 'bill';

        if($_FILES){ 
           
            $model->pdf_attach_file =  UploadedFile::getInstance( $model, 'pdf_attach_file');
            $model->xml_attach_file =  UploadedFile::getInstance( $model, 'xml_attach_file');
            
            $model->pdf_attach = empty($model->pdf_attach_file->name) ? '' : $model->pdf_attach_file->baseName.'.' . strtolower($model->pdf_attach_file->extension);
            $model->xml_attach = empty($model->xml_attach_file->name) ? '' : $model->xml_attach_file->baseName.'.' . strtolower($model->xml_attach_file->extension);
        }

         $model->open = 1 ;  
         $model->vendor = \Yii::$app->user->identity->provider_id;

         
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                          
                $model->pdf_attach_file = UploadedFile::getInstance( $model, 'pdf_attach_file');
                $model->xml_attach_file = UploadedFile::getInstance( $model, 'xml_attach_file');
                
                if($_FILES){ 
                    $model->upload();
                }

                $searchModel = new ChargeSearch();
                $dataProvider = $searchModel->search(
                    Yii::$app->request->queryParams,
                    $model->transc_id,
                );

                $chargeModel = new Charge();

                return $this->renderAjax('//charge/create', [
                  'model' => $chargeModel,
                  'transaction' => $model->transc_id
                  //'searchModel' => $searchModel,
                  //'dataProvider' => $dataProvider
                ]);
            
            }
        

        return $this->renderAjax('create', [
                'model' => $model,
        ]);

        /*return $this->render('create', [
            'model' => $model,
        ]);*/
    }

    /**
     * Updates an existing Transaction model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        
        $model = $this->findModel($id);
        $booking = Booking::findOne($model->booking);

        $model->scenario = 'bill';
          
        /*Charge Data*/
        $searchModel = new ChargeSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $model->transc_id
        );

        if($_FILES){ 
            $model->pdf_attach_file =  UploadedFile::getInstance( $model, 'pdf_attach_file');
            $model->xml_attach_file =  UploadedFile::getInstance( $model, 'xml_attach_file');
            
                $model->pdf_attach = empty($model->pdf_attach_file->name) ? $model->pdf_attach : $model->pdf_attach_file->baseName.'.' . $model->pdf_attach_file->extension ;
                $model->xml_attach = empty($model->xml_attach_file->name) ? $model->xml_attach : $model->xml_attach_file->baseName.'.' . $model->xml_attach_file->extension ;
        }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {

                Yii::debug('Saved after files');
                        
                $model->pdf_attach_file = UploadedFile::getInstance( $model, 'pdf_attach_file');
                $model->xml_attach_file = UploadedFile::getInstance( $model, 'xml_attach_file');

                if($_FILES){ 
                    $model->upload();
                }

                  return $this->renderAjax('update', [
                    'model' => $model,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'booking' => $booking,
                    'success'=>'Changes saved successfully at ' . date('h:m:s')
                  ]);

            }else{
              $error = $model->saveError();
            }

        return $this->renderAjax('update', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'booking' => $booking,
            'error' => $error
        ]);
    }

    public function actionUpdateAjax($id){
        
      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['output'=>$model[$_POST['editableAttribute']] , 'message'=>'' ];
        }else{
             return ['output'=>'Error' , 'message'=>''];
        }

    }

    public function actionUpdateType($id){
        
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return ['output'=>$model->statusText , 'message'=>''];

        }else{
             return ['output'=>'Error' , 'message'=>''];
        }

    }

    /**
     * Deletes an existing Transaction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model=  $this->findModel($id);     
        $booking = $model->booking;
        $model->delete();

        return $this->redirect(['index'  , 'booking' => $booking ] );
    }

    public function actionView($id){

        $model = $this->findModel($id);
        /*Charge Data*/
        $searchModel = new ChargeSearch();
        $dataProvider = $searchModel->search(
            Yii::$app->request->queryParams,
            $model->transc_id
        );

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);

    }

    /**
     * Finds the Transaction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Transaction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Transaction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

     public function actionRequest(){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 

        if(empty($_POST['data']) || count($_POST['data']) == 0){

             $status[] = [
                  'success' => false,
                    'tran_number' => 'N/A',
                    'status' => ['No bills were selected'],
                ];
            return ['success'=>false,'status' => $status ];

        }

        $query = Transaction::find()
        ->select([ 
            'transaction.transc_id',
            'account.prefix as currency',
            'transaction.xml_attach',
            'transaction.pdf_attach',
            'transaction.tran_date',
            'transaction.tran_number',
            'transaction.tran_type',
            'transaction.booking',
            'exchange.exchange_value',
            'transaction.account',
            'transaction.vendor',
            'transaction.customer',
            'transaction.created_at',
            'transaction.modified_at',
            'transaction.modified_by',
            'booking.booking_number',
            'creator.username AS creator',
            'modifier.username AS modifier',
            'terms.pay_terms as payTerms',
            'vendor.fullName AS vendorName',
            'account.account_name AS accountName',
            
            'booking.booking_number', 

            'SUM( ROUND(price, 0) * ROUND(quantity,2) ) amount_original',

            'SUM( ROUND(price, 2)  * ROUND(quantity,2) ) * exchange.exchange_value subTotal',

            'SUM( ROUND(price, 2) * ROUND(quantity,2) * ROUND(charge_type.tax_rate,2) )  taxAmount',

            'SUM( ROUND(price, 2) * ROUND(quantity,2) * ROUND(charge_type.tax_retention,2) )  retentionAmount',
            
            'SUM( 
            ( ROUND(price, 2) * ROUND(quantity,2) * ROUND(charge_type.tax_rate,2) + ROUND(price, 2) * ROUND(quantity,2) )   -
            ( ROUND(price, 2) * ROUND(quantity,2) * ROUND(charge_type. tax_retention, 2) ) 
            )  totalAmount'             
        ])
        ->joinWith('booking')
        ->joinWith('vendorModel')
        ->joinWith('customerModel')   
        ->joinWith('account')
        ->joinWith('terms')
        ->joinWith('modifier')
        ->joinWith('creator')
        ->joinWith('charges')
        ->joinWith('payments')
        ->leftJoin('payment_request', 'payment_request.request_id = payments_by_transaction.request_id' )
        ->leftJoin('charge_type', 'charge_type.charge_type_id = charge.type' )
        ->leftJoin('exchange',
        '(account.account_id = exchange.account AND  `transaction`.tran_date = exchange.date_exchange  ) OR  
         (account.account_id = exchange.account AND  account.`default` = 1 )')
                
        ->andWhere(['IN', 'transaction.transc_id' ,  $_POST['data'] ])
        //->andWhere(['paid' => 0 ])
        //->andWhere(['is', 'payment_request.request_id', new \yii\db\Expression('null')]),
        //->andWhere(['payment_request.request_id' => 0 ])
        ->GroupBy(['transaction.transc_id']);
        
        if(!$query->count()){

            $status[] = [
                 'success' => false,
                   'tran_number' => 'N/A',
                   'status' => ['No Bills to be requested found or already requested or paid' ],
            ];

           return ['success'=> false, 'status' => $status ] ;
        }

        $transactions = $query->all();

        $currency = $transactions[0]['account'];

        foreach($transactions as $key => $model){

        if($key != 0 && $model->account !== $currency ){

              $status[] = [
               'success' => false,
                 'tran_number' =>  $model->tran_number,
                 'status' => ['Cannot group in payment request selected bills, They should have the same currency' ],
              ];

              return [ 'success'=> false , 'status' => $status ];
          }
        }

        foreach($transactions as $key => $model){

           if(empty($model->pdf_attach) || empty($model->xml_attach) ){

              $status[] = [
               'success' => false,
                 'tran_number' =>  $model->tran_number,
                 'status' => ['No documents are uploaded yet '],
             ];
              
              return ['success'=> false, 'status' =>$status ];
            }     
        }

         $request = New PaymentRequest();
         $request->amount = $query->sum('totalAmount');
         $request->number = 0;
         $request->provider_id  = Yii::$app->user->identity->provider->provider_id;
         
        $max = PaymentRequest::find()
        ->where(['number' => PaymentRequest::find()->max('temp_number')])
        ->one();

        $bank = Bank::findOne(['default' => 1]);

        $request->bank_id = $bank->bank_id;
        $request->number =  strval($max->number + 1);
        $request->temp_number = $max->number + 1;
        $request->type = 2 ;
        $request->opened = 1 ;
        $request->paid = 0 ;
        $request->currency_id = $currency;

         if(!$request->save()){
            $status[] = [
               'success' => false,
                 'tran_number' =>  $model->tran_number,
                 'status' => ['There was an error to save the request - ' . $request->saveError(). ' '. ($max->number + 1) ] ,
             ];
              return ['success'=> false, 'status' => $status ];
         }    

        foreach($transactions as $model){
              $status[] = [
                'success' => $model->request($request->request_id),
                'tran_number' => $model->tran_number,
                'status' => $model->log
              ];
        }

        return ['success'=>true, 'request_id' => $request->request_id,  'status' =>$status];
     }

     public function actionSetProcessed($id){

        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        $model = Transaction::findOne(['transc_id'=>$id, 'customer'=> Yii::$app->user->identity->client->client_id]);

        if($model==null){ 
            return [ 'output'=> '' , 'message'=> 'Transaction no found' ];
        }

        $model->scenario = 'processed';
        // Removed only set once
        // $model->processed =  $model->processed == 1 ? 0 : 1;
        $model->processed =  1 ;

        if($model->validate()){
            $model->save();
            return ['output'=> ['processed' => 1 ] , 'success'=>true, 'id'=> $model->transc_id ];
        }

        return [ 'output'=> '0' , 'success'=>false, 'message'=> $model->saveError() ];
        
    }


}
