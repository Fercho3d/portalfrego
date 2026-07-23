<?php

namespace app\models;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\helpers\Html;
use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $transc_id
 * @property string|null $tran_date
 * @property int|null $tran_number
 * @property int|null $account
 * @property int|null $booking
 * @property int|null $vendor
 * @property string|null $xml_attach
 * @property string|null $pdf_attach
 * @property string|null $bill_address
 *\\\\\\\\\\\\\\\\\\\\\\\\\
 * @property Charge[] $charges
 * @property Booking $booking0
 */
class Transaction extends \yii\db\ActiveRecord
{

    public $pdf_attach_file;     
    public $xml_attach_file; 
    public $creator;
    public $modifier;
    public $payTerms;
    public $vendorName;
    public $customerName;
    public $accountName;
    public $exchange_value;
    public $amount_original;
    public $booking_number;
    
    public $taxAmount;
    public $amount;
    public $totalAmount;
    public $tax_rate;
    public $retentionAmount;
    public $dates;
    public $currency;
    
    public $sub_0_mxn;
    public $sub_16_mxn;
    public $tax_16_mxn;
    public $tax_ret_mxn; 
    public $total_amount;
    public $total_natural_amount;
    public $tran_paid_amount;
    public $log;
    public $non_dec;
    public $non_deductible;
    public $left_to_pay;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
    */


    public function rules()
    {
        return [
            [['account', 'booking', 'customer','tran_date' ], 'required', 'on'=>['invoice'] ],
            [['account', 'tran_number', 'booking', 'vendor','tran_date', 'pdf_attach' ],  'required', 'on'=>['bill'] ],
            ['tran_number', 'validateCharges' ], 

            [['tran_date','paid_at', 'request_at'], 'safe'],
            [['account', 'booking', 'vendor', 'customer','payment_terms', 'tran_type', 'open','payment_request','paid', 'request_id' ,'invoice','processed' ], 'integer'],
            [['tran_number'], 'string' , 'max'=> 50 ],
            [['xml_attach', 'pdf_attach', 'bill_address'], 'string', 'max' => 255],
            [['booking'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking' => 'booking_id']],
            [['pdf_attach_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
            [['xml_attach_file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'xml'],
        ];
    }

    public function scenarios(){
    
        $scenarios = parent::scenarios();
        $scenarios['bill'] =  
        [
            'tran_number', 
            'tran_date',
            'xml_attach',
            'pdf_attach',
            'xml_attach_file',
            'xml_attach_file',
         ];   
         $scenarios['request'] =  
            [
                'payment_request', 
                'request_at',
                'request_id',
                'xml_attach',
                'pdf_attach',
                'xml_attach_file',
                'xml_attach_file',
             ];
         $scenarios['auto'] =  
            [
                'vendor',
                'tran_date',
                'account',
                'tran_type',
                'booking'
             ];
         $scenarios['processed'] =  
            [
                'processed', 
             ];
      return $scenarios;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'transc_id' => 'Transc ID',
            'tran_date' => 'Date',
            'tran_number' => 'Number',
            'account' => 'Account',
            'booking' => 'Booking',
            'vendor' => 'Vendor',
            'xml_attach' => 'Xml Attch',
            'pdf_attach' => 'Pdf Attach',
            'bill_address' => 'Bill Address',
            'creator' => 'Creted By',
            'modifier' => 'Modified By',
            'payTerms' => 'Payment Terms',
            'vendorName' => 'Vendor',
            'accountName' => 'Account',
            'booking_number' => 'Booking',
            'tran_type' => 'Type',
            'dates' => 'dates',
            'taxAmount' => 'VAT',
            'retentionAmount' => 'Withholding',
            'amount' => 'Before Tax',
            'totalAmount' => 'Total',
            'customer' => 'Customer'
        ];
    }

    /**
     * Gets query for [[Charges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCharges()
{
        return $this->hasMany(Charge::className(), ['transaction' => 'transc_id']);
    }

    public function getPayments()
    {
        return $this->hasMany(PaymentsByTransaction::className(), ['transc_id' => 'transc_id']);
    }  


    public function getVendorModel()
    {
        return $this->hasOne(Provider::className(), ['provider_id' => 'vendor' ])
        ->from(Provider::tableName() . ' vendor' );
    }

    public function getCustomerModel(){

        return $this->hasOne(Client::className(), ['client_id' => 'customer' ])
        ->from(Client::tableName() . ' customer' );
    }

    public function getPdfLink(){
        $action = $this->tran_type == 0 ? 'pdf-invoice' : 'pdf-bill';
        return  '/web/transaction/'.$action.'?id='.$this->transc_id;
    }  

    public function getXmlLink(){
       $action = $this->tran_type == 0 ? 'xml-invoice' : 'xml-bill';
       return '/web/transaction/'.$action.'?id='.$this->transc_id;
    }  
    
    
    public function getPdfLinkBill(){

        return  '/web/transaction/pdf-bill?id='.$this->transc_id;
    }  

    public function getXmlLinkBill(){

       return '/web/transaction/xml-bill?id='.$this->transc_id;
    }     

    public function getPdfFile(){
        return  Yii::getAlias('@webfolder') . '/web/uploads/transactions/'.$this->transc_id  .'/pdf/' .$this->pdf_attach;
     }     

    public function getXmlFile(){
        return  Yii::getAlias('@webfolder') . '/web/uploads/transactions/'.$this->transc_id  .'/pdf/' .$this->xml_attach;
    } 

    public function getTerms(){
         
        return $this->hasOne(PaymentTerms::className(), ['pay_terms_id'  => 'payment_terms'])
        ->from(PaymentTerms::tableName() . ' terms');;
    }


    public function getAccount()
    {
        return $this->hasOne(Account::className(), [ 'account_id' =>   'account'   ]);
    }

     public function getAccountModel()
    {
        return $this->hasOne(Account::className(), [ 'account_id' =>   'account'   ]);
    }

    public function getCreator(){

        return $this->hasOne(User::className(), ['usr_id'=> 'created_by'   ])
        ->from(User::tableName() . ' creator');

    }    

    public function getModifier(){
            
         return $this->hasOne(User::className(), [ 'usr_id'  => 'created_by'])
         ->from(User::tableName() . ' modifier');

    }

    

    /**
     * Gets query for [[Booking0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking']);
    }    
    
    public function getBookingModel(){
    
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking']);
    }

   public function upload()
    {   
        $id = $this->transc_id;

        $ds = DIRECTORY_SEPARATOR; 
           
        $baseDir =  Yii::getAlias('@webfolder') ;

        $pdfDir  = $baseDir . '/web/uploads/transactions/'.$id .'/pdf/';

        //echo $pdfDir;

        //$imgDir  = $baseDir.$ds.'/web/uploads/transactions/'.$id .'/img/';

        $this->createDir($pdfDir);
        //$this->createDir($imgDir); 
        $pdf_attach = empty($this->pdf_attach_file->name) ? $this->pdf_attach : $this->pdf_attach_file->baseName.'.' . $this->pdf_attach_file->extension ;
        $xml_attach = empty($this->xml_attach_file->name) ? $this->xml_attach : $this->xml_attach_file->baseName.'.' . $this->xml_attach_file->extension ;

        if( !empty($this->pdf_attach_file->name)){ 
                if(!$this->pdf_attach_file->saveAs($pdfDir. $pdf_attach , true)){ return false;  } 
            }

        if(!empty($this->xml_attach_file->name)){ 
                if(!$this->xml_attach_file->saveAs($pdfDir . $xml_attach  , true)){ return false;  
            } 
        }
           
    }

    public function createDir($dir){
        //echo $dir;
        FileHelper::createDirectory($dir, $mode = 0775, $recursive = true);

        if(!is_writable($dir)){
            "no write permisions :(" ;
        }

    }

    public function beforeSave($insert){

           if ($this->isNewRecord) { 

                if($this->tran_type == 0 && $this->invoice_type == 1){
                    $max =  Transaction::find()->max('invoice');
                    $this->invoice = $max + 1;
                    $this->tran_number = 'F-'.$this->invoice;
                }

                $this->created_at =  date("Y-m-d H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;

           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

            $this->tran_date = date("Y-m-d",  strtotime(str_replace('/', '-',$this->tran_date)));

          $exchange = new Exchange();
          $exchange->check($this->tran_date);

         return parent::beforeSave($insert);  
    }


    public function getTypes(){
       return  [ 1 => 'Bill', 0 =>  'Invoice', 2 =>  'Credit Bill', 3 =>  'Credit Note'];
    }

    public function getTypeText(){
        $types  = [ 1 => 'Bill', 0 =>  'Invoice', 2 =>  'Credit Bill', 3 =>  'Credit Note'];
        return $types[$this->tran_type];
    }


    public function getStatus(){
       return  [1 =>  'Open', 0 => 'Closed'];
    }

    public function getStatustext(){
        $types  = [1 =>  'Open', 0 => 'Closed'];
        return $types[$this->closed];
    }

   public static function getTotal($provider, $fieldName)
    { 
        $total = 0;
        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }
        // add number_format() before return
        $total = number_format( $total, 2 );
        return $total;
    }


    public function saveError(){
         return implode(' ', array_map(function ($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
    }

    public function getPaidStatus(){
        if(abs($this->tran_paid_amount) == 0){
          
          return  Html::a(
            '<i class="text-danger fa fa-times-circle text-indicator" ></i>', 
            null ,
              [
                  'type'=>'button', 
                  'title'=>'View Connected Payments', 
                  'class'=>'text-danger tran-payments',
                  //'data-toggle'=>'modal',
                  //'data-target'=>'#form',
              ]);
  
          }elseif($this->left_to_pay > 0 && abs($this->left_to_pay) < abs($this->total_natural_amount) ){
  
           return  Html::a(
              '<i class="text-warning fa fa-check-circle text-indicator "></i>', 
             null ,
                [
                    'type'=>'button', 
                    'title'=>'View Connected Payments', 
                    'class'=>'text-warning tran-payments',
                    //'data-toggle'=>'modal',
                    //'data-target'=>'#form',
                ]);
  
          }elseif( abs($this->left_to_pay) == 0){
               
            return  Html::a(
              '<i class=" fa fa-check-circle text-indicator" ></i>', 
              null ,
                [
                    'type'=>'button', 
                    'title'=>'View Connected Payments', 
                    'class'=>'text-success tran-payments',
                    //'data-toggle'=>'modal',
                    //'data-target'=>'#form',
                ]);
  
  
            return '';
        }
  
        //return $this->left_to_pay;
        
      }

    public function request($request_id){
        
        $payment =  new PaymentsByTransaction();
        $payment->amount = 0;
        $payment->transc_id = $this->transc_id;
        $payment->request_id = $request_id;
        
        $this->scenario = 'paid';

        $this->scenario = 'request';
        $this->payment_request = 1;
        $this->request_at = date('Y-d-m H:i:s');
       
        if($payment->save() && $this->save()){
             $this->log[] = 'Bill payment request successfully';
             return true;
        }else{
            $this->log[] = $this->saveError();
        };
    }


    public function validateCharges($attribute, $params){

        if(!count($this->charges) ) {

              $this->addError(
                    $attribute, 
                    'The current Bill has not charges' 
                );
        };
 
        foreach($this->charges as $charge){

            if(empty($charge->price_confirmation)){
                $this->addError(
                    $attribute, 
                    'Charge Confirmation is not set' 
                );

                return false;

            }elseif(empty($charge->quantity) || $charge->quantity == 0){

             $this->addError(
                $attribute, 
                'Charge quantity is no set'
             );

            }elseif($charge->price != $charge->price_confirmation){

             $this->addError(
                    $attribute, 
                    'Charge price for '. $charge->description  .'  confirmation is not equal to defined price'
             );

             return false;

            }
        }

        return true;
    
    }

}
