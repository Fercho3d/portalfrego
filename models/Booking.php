<?php

namespace app\models;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;


use yii\helpers\Html;

use Yii;

/**
 * This is the model class for table "booking".
 *
 * @property int $booking_id
 * @property string|null $vessel
 * @property int|null $booking_number
 * @property string|null $client
 * @property string|null $loading_port
 * @property string|null $loading_EDT
 * @property string|null $dicharge_port
 * @property string|null $dicharge_ETA
 * @property string|null $container_type
 * @property string|null $commodity
 * @property string|null $set_point
 * @property string|null $created_at
 * @property string|null $modified_at
 * @property int|null $created_by
 * @property int|null $modified_by

 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $creator;
    public $modifier;
    public $vessel_name;
    public $port_name;
    public $username;
    public $container_name;
    public $client_name;
    
    public $vessel_new;
    public $port_new;
    public $carrier_new;

    public $pickup_date;
    public $vacuum_maneuver;
    public $gated_out;
    public $doc_cut_of;
    public $SI_date;
    public $draf_client;
    public $gated_IN;
    public $cleared;
    public $departure;
    public $bl_payment;
    public $swb;

    public $number;
    public $seal;

    public $pdf_attach_file;  
    public $entrusts_letter_file_attach;
    public $warranty_file_attach;
    public $payments_file_attach;
    public $empty_maneuver_file_attach;
    public $maneuver_full_file_attach;
    public $commercial_bills_file_attach;
    public $petition_file_attach;
    public $swb_file_attach;

    public $payTerms;
    public $vendorName;
    public $customerName;
    public $accountName;
    public $exchange_value;
    public $amount_original;
    
    public $taxAmount;
    public $amount;
    public $totalAmount;
    public $tax_rate;
    public $retentionAmount;
    public $dates;
    public $currency;
    public $transc_id;
    public $tran_type;
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
    public $tran_date;
    public $cancelled;
    public $paidStatus;
    public $pdf_attach;
    public $xml_attach;
    public $booking_num;
    public $tran_number;
    public $processed;

    public $dicharge_new;
    public $transport_new;

    //const SCENARIO_UPLOAD_FILES = 'upload_files';
    //const SCENARIO_UPLOAD_DOCS =  'upload_docs';
        
    public static function tableName()
    {
        return 'booking';
    }

    const SCENARIO_UPLOAD_FILES = 'upload_files';
    const SCENARIO_UPLOAD_DOCS =  'upload_docs';
    const SCENARIO_INSTRUCTIONS =  'instructions';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
             'booking_number',
             'vessel',
             'loading_EDT',
             'dicharge_ETA',
             //'dicharge_port' ,
             'loading_port',
             //'commodity',
             'dicharge_ETA',
             //'set_point',
             'pick_up_place_id',
             'client',
             'carrier_id', 
             //'transport_id',
              ],
             'required'], 

            ['vessel_new','required', 
                'when' => function($model) { return $model->vessel == -1; },
                'whenClient' => "function (attribute, value) {return $('#booking-vessel').val() == '-1' ;} ",
                'message'=>'Please enter a value {attribute} since you selected create new',
            ], 
            [   'carrier_new', 'required', 
                'when' => function($model) { return $model->carrier == -1 ; },
                'whenClient' => "function (attribute, value) {return $('#booking-carrier').val() == '-1' ;} ",
                'message'=>'Please enter a value  {attribute} since you selected create new',
            ],
            ['dicharge_new', 'required',
            'when' => function($model) { return $model->carrier == -1 ; },
            'whenClient' => "function (attribute, value) {return $('#booking-dicharge_port_id').val() == '-1' ;} ",
            'message'=>'Please enter a value  {attribute} since you selected create new',
            ],

            ['port_new', 'required',
            'when' => function($model) { return $model->carrier == -1 ; },
            'whenClient' => "function (attribute, value) {return $('#booking-loading_port').val() == '-1' ;} ",
            'message'=>'Please enter a value  {attribute} since you selected create new',
            ],
            
            [['created_by', 'modified_by', 'pick_up_place_id', 'booking_type','client','vessel','loading_port','carrier', 'is_draft' , 'locked', 'dicharge_port_id', 'custom_brocker_id', 'transport_id' ], 'integer'],
            [['loading_EDT', 'dicharge_ETA', 'created_at', 'modified_at',  'customer_reference'], 'safe'],
            [[
            'entrusts_letter_file',
            'warranty_file',
            'payments_file',
            'empty_maneuver_file',
            'maneuver_full_file',
            'commercial_bills_file',
            'petition_file',
            'swb_file'
            ], 'string', 'max' => 255],
            [['customer_reference'], 'string', 'max' => 64],
            [[ 'vessel_new', 'port_new','carrier_new' ], 'string', 'max' => 100],
            [['booking_number', 'HB' ,'commodity', 'set_point','pick_up_place', 'dicharge_port', 'final_destination' ], 'string', 'max' => 50],
            [['shipper_is','shipper_should', 'consignee_is', 'consignee_should', 'notify_party_is', 'notify_party_should', 'description_is', 'description_should' ], 'string', 'max' => 1000],
            [['arrival','realeased_from_shiping','customs_cleared','truck_service_request','delivered_consigned'], 'safe' ],
            [[
                'entrusts_letter_file_attach',
                'warranty_file_attach',
                'payments_file_attach',
                'empty_maneuver_file_attach',
                'maneuver_full_file_attach',
                'commercial_bills_file_attach',
                'petition_file_attach',
                'swb_file_attach'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf']
        ];
    }


    public function attributeLabels(){
    
         return  [
            'booking_id' => 'Shipment ID',
            'vessel' => 'Vessel',
            'booking_number' => 'Booking Number',
            'booking_type' => 'Booking Type',
            'client' => 'Customer',
            'loading_port' => 'POL',
            'loading_EDT' => 'EDT',
            'dicharge_port' => 'POD',
            'dicharge_ETA' => 'ETA',
            'container_type' => 'Container Type',
            'commodity' => 'Commodity',
             //'set_point' => 'Set Point',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'modifier' => 'Modified by',
            'creator' => 'Created by',
            'container_name' => 'Container Type',
            'pick_up_place_id' => 'Pick Up Place',
            'pick_up_place' => 'Pick Up Place',
            'pickup_date' => 'Dickup Date',
            'port_name' => 'POL',
            'client_name' => 'Customer',
            'numero' => 'numero',
            'customer_reference' => 'Customer Reference',
            'seal' => 'seal',
            'HB' => 'HBL',
            'vessel_new' => 'Add New Vessel',
            'port_new' => 'Add New Port',
            'shipper_is' => 'Shipper is',
            'shipper_should' => 'Shipper should be',
            'consignee_is' => 'Delivered consigned is', 
            'consignee_should' => 'Delivered consigned should be',
            'notify_party_is' => 'Notify party is',
            'notify_party_should' => 'Notify Party should be',
            'description_is'  => 'Description is',
            'description_should' => 'Description should be',
            'dicharge_port_id' => 'POD',
            'carrier_id' => 'Carrier',
            'transport_id' => 'Transport',
            'custom_brocker_id' => 'Custom Brocker'
            ];

             
    }

    public function scenarios(){

      $scenarios = parent::scenarios();

      $scenarios[self::SCENARIO_UPLOAD_FILES] =  ['pdf_attach_file'];

      $scenarios[self::SCENARIO_INSTRUCTIONS] =  [ 'shipper_is','shipper_should', 'consignee_is', 'consignee_should', 'notify_party_is', 'notify_party_should', 'description_is', 'description_should' ];

      return $scenarios;
      
    }

    public function generateInvoiceTran($client,$service){
        $transaction = new Transaction();
        $transaction->scenario  = 'invoice';
        $transaction->customer = $this->client;
        $transaction->tran_date = date('Y-m-d');
        $transaction->account = $service->account_id;
        $transaction->tran_type = 0;
        $transaction->booking = $this->booking_id;
        $transaction->invoice_type = 1;

        // if(!$transaction->save()){
        //     echo $transaction->saveError();
        //     print_r($service);
        //     exit;
        // };

        return $transaction->save() ?  $transaction : false;

    }

    public function generateInvoice(){
        $client = Client::findOne($this->client);
        $services = $this->getInvoiceServices($client);
        $brocker_services =  $this->getBrockerServices($client);
       if($brocker_services){
           $services =  array_merge($services,$brocker_services);
       }
       
        if($services){
            
            $transaction = $this->generateInvoiceTran($client, $services[0]);
            
            if($transaction){
                
                foreach($services as $key => $service){
                 
                    if($key > 0 && $services[$key-1]->account_id !== $service->account_id) { 
                        
                        $transaction = $this->generateInvoiceTran($client, $service);
                    }

                    $quantity = 0;
                    if($service->price_type == 1 || $service->price_type == 3){  
                        //foreach($this->containers as $container){
                            //$quantity += $container->quantity; 
                        //}
                            $quantity = $service->quantity;

                    }elseif($service->price_type == 2 || $service->price_type == 4){
                        $quantity = 1;
                    }
                    $containerName = !empty($service->container_name) ? " - " . $service->container_name : '';
                    $charge = new Charge();
                    $charge->scenario =  'auto';
                    $charge->quantity = $quantity;
                    $charge->price = $service->price;
                    $charge->transaction = $transaction->transc_id;
                    $charge->description = $service->description . $containerName ;
                    $charge->type = $service->charge_type_id; 
                    $service->service_id = $service->service_id;
                    if($charge->save()){ 
                        //return true;
                    }else{
                        echo $charge->saveError();
                        exit();
                    }
                }
            }else{
                return false;
            }
        }else{
            return false;
        }
    
        return true;

    }

    public function getBrockerServices($client){
        if(isset($this->custom_brocker_id)){  
            return Service::find()
            ->andWhere([
                'client_id' => $this->client,
                'auto_include' => 1
                ])
            ->andWhere(['OR', ['price_type' => 3], ['price_type' => 4]])
            ->OrderBy(['account_id'=>SORT_ASC])->all();
            
        }else{
            return false;
        }
}


    public function generateBills($type){
        if($type == 1 ) { 
            $provider_id =  $this->carrier_id;
        }elseif($type == 2){
            $provider_id = $this->transport_id;
        }elseif($type == 3){
            $provider_id = $this->custom_brocker_id;
        }

        $provider = Provider::findOne(['provider_id'=> $provider_id ]);
        $services = $this->getServicesProvider($provider);
        return  $this->generateInvidivualBill($services, $provider,$type);
    }

    function generateBillTran($provider,$service){

        $transaction = new Transaction();
        $transaction->scenario  = 'auto';
        $transaction->vendor = $provider->provider_id;
        $transaction->tran_date = date('Y-m-d');
        $transaction->account = $service->account_id;
        $transaction->tran_type = 1;
        $transaction->booking = $this->booking_id;

        if(!$transaction->save()){
            echo 'Bill '. $transaction->saveError();
            print_r($service);
            exit;
        };

        return $transaction->save() ?  $transaction : false;
        
    }

    public function generateInvidivualBill($services, $provider,$type){
        

        if($services){
                 $transaction = $this->generateBillTran($provider,$services[0]);
                //$transaction->invoice_type = 1;
                if($transaction){
                   // echo "transaction Saved <br />";
                    foreach($services as $key => $service){
                        //Aqui se hace la separacion de moneda
                        if($key > 0 && $services[$key-1]->account_id !== $service->account_id) { 
                            $transaction = $this->generateBillTran($provider, $service);
                        }
                        $quantity = 0;

                        if($type == 1){
                            $quantity = $service->quantity;
                        }elseif($type != 1 && $service->price_type == 1 ){  
                            foreach($this->containers as $container){
                                $quantity += $container->quantity; 
                            }
                        }else{
                            $quantity = 1;
                        }

                        $containerName = !empty($service->container_name) ? " - " . $service->container_name : '';

                        //Si es transporte genera una provicion individual por factura
                        if($type == 2){
                            
                            for($i = 0; $i < $quantity; $i++){ 
                                $charge = new Charge();
                                $charge->quantity = 1;
                                $charge->price = $service->price;
                                $charge->transaction = $transaction->transc_id;
                                $charge->description = $service->description . $containerName ;
                                $charge->type = $service->charge_type_id; 
                                $charge->service_id = $service->service_id;
                                $charge->save();
                            }

                    }else{
                        //si no que genere por la cantidad espesificada en container
                        $charge = new Charge();
                        $charge->scenario  = 'auto';
                        $charge->quantity = $quantity;
                        $charge->price = $service->price;
                        $charge->transaction = $transaction->transc_id;
                        $charge->description = $service->description . $containerName;
                        $charge->type = $service->charge_type_id; 
                        $charge->service_id = $service->service_id;
                        $charge->save();
                    }

                    
                    }
            
            }else{
                return false;
            }

        return true;

    }
}




public function getServicesProvider($provider){
    
    if($provider->type_id == 1 ) { // Carrier
        
    return  Service::find()
    ->select([
        'container_types.container_name',
        'service.service_id',
        'service.name',
        'service.price',
        'service.account_id',
        'service.description',
        'service.start_date',
        'service.end_date',
        'service.charge_type_id',
        'service.provider_id',
        'service.active',
        'service.client_id',
        'service.type',
        'service.max',
        'service.min',
        'service.loading_port_id',
        'service.dicharge_port_id',
        'service.pickup_place_id',
        'service.auto_include',
        'service.price_type',
        'service.final_destination_id',
        'service.deleted',
        'service.container_type_id',
        'service.service_id',
        'SUM(containers.quantity) quantity',
        'service.charge_type_id'
    ])
    ->andWhere([
    'service.loading_port_id'=> $this->loading_port,
    'service.dicharge_port_id' => $this->dicharge_port_id,
    'service.final_destination_id' => $this->final_destination_id,
    'service.pickup_place_id' => $this->pick_up_place_id,
    'service.auto_include' => 1,
    'service.provider_id' => $provider->provider_id,
    'service.active' => 1
    ])
    ->innerJoin('container_types', 'container_types.contType_id  = service.container_type_id' )
    ->innerJoin('containers','containers.container_type = container_types.contType_id AND containers.booking = :booking')
    ->OrderBy(['account_id' => SORT_ASC ])
    ->GroupBy(['container_types.contType_id'])
    ->addParams([':booking' => $this->booking_id ])
    ->all();

    }elseif($provider->type_id == 2){ // Transport

    return Service::find()->andWhere([
        'loading_port_id'=> $this->loading_port,
        'pickup_place_id' => $this->pick_up_place_id,
        'auto_include' => 1,
        'provider_id' => $provider->provider_id,
        'active' => 1
        ])
        ->OrderBy(['account_id'=>SORT_ASC])->all();
            
    }elseif($provider->type_id == 3){ //Custom Brocker
        return Service::find()->andWhere([
            'auto_include' => 1,
            'provider_id' => $provider->provider_id,
            'active' => 1
            ])->OrderBy(['account_id'=>SORT_ASC])->all();
    }
}

public function getInvoiceServices($client){
    if($client->match_pickup_place == 1){ 

        return  Service::find()
        ->select([
            'container_types.container_name',
            'service.service_id',
            'service.name',
            'service.price',
            'service.account_id',
            'service.description',
            'service.start_date',
            'service.end_date',
            'service.charge_type_id',
            'service.provider_id',
            'service.active',
            'service.client_id',
            'service.type',
            'service.max',
            'service.min',
            'service.loading_port_id',
            'service.dicharge_port_id',
            'service.pickup_place_id',
            'service.auto_include',
            'service.price_type',
            'service.final_destination_id',
            'service.deleted',
            'service.container_type_id',
            'service.service_id',
            'SUM(containers.quantity) quantity',
            'service.charge_type_id'
        ])
        ->andWhere([
            'service.loading_port_id'=> $this->loading_port,
            'service.dicharge_port_id' => $this->dicharge_port_id,
            'service.pickup_place_id' => $this->pick_up_place_id,
            'service.final_destination_id' => $this->final_destination_id,
            'service.client_id' => $this->client,
            'service.pickup_place_id' => $this->pick_up_place_id,
            'service.auto_include' => 1,
            'active' => 1
        ])
        ->innerJoin('container_types', 'container_types.contType_id  = service.container_type_id' )
        ->innerJoin('containers','containers.container_type = container_types.contType_id AND containers.booking = :booking')
        ->OrderBy(['account_id' => SORT_ASC ])
        ->GroupBy(['container_types.contType_id'])
        ->addParams([':booking' => $this->booking_id ])
        ->all();


        // return Service::find()->andWhere([
        //     'loading_port_id'=> $this->loading_port,
        //     'dicharge_port_id' => $this->dicharge_port_id,
        //     'pickup_place_id' => $this->pick_up_place_id,
        //     'final_destination_id' => $this->final_destination_id,
        //     'client_id' => $this->client,
        //     'auto_include' => 1,
        //     'active' => 1
        //     ])->OrderBy(['account_id'=> SORT_ASC])->all();
            
        }else{
            
        return  Service::find()
        ->select([
            'container_types.container_name',
            'service.service_id',
            'service.name',
            'service.price',
            'service.account_id',
            'service.description',
            'service.start_date',
            'service.end_date',
            'service.charge_type_id',
            'service.provider_id',
            'service.active',
            'service.client_id',
            'service.type',
            'service.max',
            'service.min',
            'service.loading_port_id',
            'service.dicharge_port_id',
            'service.pickup_place_id',
            'service.auto_include',
            'service.price_type',
            'service.final_destination_id',
            'service.deleted',
            'service.container_type_id',
            'service.service_id',
            'SUM(containers.quantity) quantity',
            'service.charge_type_id'
        ])
        ->andWhere([
            'service.loading_port_id'=> $this->loading_port,
            'service.dicharge_port_id' => $this->dicharge_port_id,
            'service.pickup_place_id' => $this->pick_up_place_id,
            'service.final_destination_id' => $this->final_destination_id,
            'service.client_id' => $this->client,
            'service.auto_include' => 1,
            'active' => 1
        ])
        ->innerJoin('container_types', 'container_types.contType_id  = service.container_type_id' )
        ->innerJoin('containers','containers.container_type = container_types.contType_id AND containers.booking = :booking')
        ->OrderBy(['account_id' => SORT_ASC ])
        ->GroupBy(['container_types.contType_id'])
        ->addParams([':booking' => $this->booking_id ])
        ->all();

        }
    }

    public function uploadFile($file, $name){
        $id = $this->booking_id;
        $ds = DIRECTORY_SEPARATOR; 
        $baseDir =  Yii::getAlias('@webfolder');
        $docsDir = $baseDir . '/web/uploads/bookings/'. $id .'/docs/';
        $this->createDir($docsDir);
        $file->saveAs($docsDir. $name);
        return file_exists($docsDir. $name) ? true : false ;
    }

    public function createDir($dir){
        FileHelper::createDirectory($dir, $mode = 0775, $recursive = true);
        if(!is_writable($dir)){
            Yii::debug("no write permisions :(");
        }
    }



     public function beforeSave($insert){

           if($this->isNewRecord) { 
                $this->created_at =  date("Y-m-d  H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;
           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

            $this->loading_EDT = date("Y-m-d",  strtotime(str_replace('/', '-',$this->loading_EDT)));
            $this->dicharge_ETA = date("Y-m-d", strtotime(str_replace('/', '-',$this->dicharge_ETA))); 

            $this->arrival =!empty($this->arrival) ?  date("Y-m-d H:i:s", strtotime(str_replace('/', '-',$this->arrival))): null; 
            $this->realeased_from_shiping =!empty($this->realeased_from_shiping) ?  date("Y-m-d H:i:s", strtotime(str_replace('/', '-',$this->realeased_from_shiping))): null; 
            $this->customs_cleared = !empty($this->customs_cleared) ?  date("Y-m-d H:i:s", strtotime(str_replace('/', '-',$this->customs_cleared))): null; 
            $this->truck_service_request = !empty($this->truck_service_request) ? date("Y-m-d H:i:s", strtotime(str_replace('/', '-',$this->truck_service_request))): null; 
            $this->delivered_consigned = !empty($this->delivered_consigned) ?  date("Y-m-d H:i:s", strtotime(str_replace('/', '-',$this->delivered_consigned))): null; 
         return parent::beforeSave($insert);  
    }

    public function getCreatorModel(){

        return $this->hasOne(User::className(), ['usr_id'  =>  'created_by']); 

    }


    public function getClient(){
            
        return $this->hasOne(Client::className(), ['client_id'  =>  'client']); 

    }

    public function getDichargePort(){
        return $this->hasOne(DichargePort::className(), ['dicharge_port_id'  =>  'dicharge_port_id']);
    }
    
    public function getPaidStatus(){
      if(abs($this->tran_paid_amount) == 0){
        
        return  Html::a(
          'Unpaid <i class="text-danger fa fa-times-circle text-indicator" alt="" ></i>', 
          ['payment-request/transaction-payments', 'transc_id' => $this->transc_id ] ,
            [
                'type'=>'button', 
                'title'=>'View Connected Payments', 
                'class'=>'text-danger tran-payments',
                //'data-toggle'=>'modal',
                //'data-target'=>'#form',
            ]);

        }elseif($this->left_to_pay > 0 && abs($this->left_to_pay) < abs($this->total_natural_amount) ){

         return  Html::a(
            'Partial <i class="text-warning fa fa-check-circle text-indicator" alt=""></i>', 
            ['payment-request/transaction-payments', 'transc_id' => $this->transc_id ] ,
              [
                  'type'=>'button', 
                  'title'=>'View Connected Payments', 
                  'class'=>'text-warning tran-payments',
                  //'data-toggle'=>'modal',
                  //'data-target'=>'#form',
              ]);

        }elseif( abs($this->left_to_pay) == 0){
             
          return  Html::a(
            'Paid <i class=" fa fa-check-circle text-indicator" ></i>', 
            ['payment-request/transaction-payments', 'transc_id' => $this->transc_id ] ,
              [
                  'type'=>'button', 
                  'title'=>'View Connected Payments', 
                  'class'=>'text-success tran-payments',
                  //'data-toggle'=>'modal',
                  //'data-target'=>'#form',
              ]);


          return '';
      }
    }

    public function getClientModel(){
            
        return $this->hasOne(Client::className(), ['client_id'  =>  'client']); 

    }

    public function getCreatorName(){

        return $this->hasOne(User::className() ,['usr_id' => 'created_by'])->select('username')->scalar();
        
    }    

    public function getModifierName(){
        return $this->hasOne(User::className() ,['usr_id' => 'modified_by'])->select('username')->scalar();
    }


    public function getModifierModel(){
             
         return $this->hasOne(User::className(), ['usr_id'  =>  'created_by']);

    } 


    public function getCustomer(){

         return $this->hasOne(Client::className(), ['client_id'  =>  'client']);

    }

    public function getPortName(){

        return $this->hasOne(LoadingPorts::className(), ['port_id'  =>  'loading_port'])->select('port_name')->scalar();
        
    }    

    public function getVesselName(){

       return $this->hasOne(Vessel::className(), ['vessel_id'  =>  'vessel'])->select('vessel_name')->scalar();

    }

    public function getCarrierModel(){

       return $this->hasOne(Carrier::className(), ['carrier_id'  =>  'carrier']);

    }

    public function getModifier_username(){
            
           return $this->getModifier->username; 
    }


    public function getLoadingPortModel(){
         return $this->hasOne(LoadingPorts::className(), ['port_id'  =>  'loading_port']); 
    }        

    public function getContainerTypeName(){
         return $this->hasOne(ContainerTypes::className(), ['contType_id'  =>  'container_type'])->select('container_name')->scalar();
    }

    public function getContinuity(){
         return $this->hasOne( BookingContinuity::className(), ['booking'  =>  'booking_id'] ); 
    }

    public function getVesselModel(){
         return $this->hasOne(Vessel::className(), [ 'vessel_id'  =>  'vessel']); 
    }

    public function getPickupPlace(){
         return $this->hasOne(PickupPlace::className(), ['pick_id'  =>  'pick_up_place_id']); 
    }  

    public function getTransaction(){
         return $this->hasOne(Transaction::className(), ['booking'  =>  'booking_id']); 
    }  

    public function getPickupPlaceModel(){
         return $this->hasOne(PickupPlace::className(), ['pick_id'  =>  'pick_up_place_id']); 
    }  

    public function ifContinuityExists(){

        $model = BookingContinuity::find()->Where(['booking'=> $this->booking_id ])->One();
        return $model ? $model : false ;

    }

    public function getProviders(){
        return $this->hasMany(ProvidersByBooking::className(), ['booking'  =>  'booking_id']);
    }

    public function getProgress(){


        $taskNumber = 23;
        $query = "
        SELECT 
        booking_number_chk_date + 
        pickup_date_chk_date +
        modality_chk_date +
        doc_cut_of_chk_date +
        SI_date_chk_date +
        cleared_chk_date +
        departure_chk_date +
        bl_payment_chk_date +
        swb_chk_date +
        vessel_chk_date +
        number_chk_date +
        client_chk_date +
        loading_port_chk_date +
        loading_EDT_chk_date +
        dicharge_port_chk_date +
        container_type_chk_date +
        commodity_chk_date +
        set_point_chk_date +
        dicharge_ETA_chk_date +
        vacuum_maneuver_chk_date +
        draf_client_chk_date +
        gated_IN_chk_date +
        gated_out_chk_date +
        delivered_chk_date  as total_completed

        FROM (

                SELECT

                CASE WHEN booking_number_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS booking_number_chk_date,
                CASE WHEN pickup_date_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS pickup_date_chk_date,
                CASE WHEN modality_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS modality_chk_date,
                CASE WHEN doc_cut_of_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS doc_cut_of_chk_date,
                CASE WHEN SI_date_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS SI_date_chk_date,
                CASE WHEN cleared_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS cleared_chk_date,
                CASE WHEN departure_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS departure_chk_date,
                CASE WHEN bl_payment_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS bl_payment_chk_date,
                CASE WHEN swb_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS swb_chk_date,
                CASE WHEN vessel_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS vessel_chk_date,
                CASE WHEN number_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS number_chk_date,
                CASE WHEN client_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS client_chk_date,
                CASE WHEN loading_port_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS loading_port_chk_date,
                CASE WHEN loading_EDT_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS loading_EDT_chk_date,
                CASE WHEN dicharge_port_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS dicharge_port_chk_date,
                CASE WHEN container_type_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS container_type_chk_date,
                CASE WHEN commodity_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS commodity_chk_date,
                CASE WHEN set_point_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS set_point_chk_date,
                CASE WHEN dicharge_ETA_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS dicharge_ETA_chk_date,
                CASE WHEN vacuum_maneuver_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS vacuum_maneuver_chk_date,
                CASE WHEN draf_client_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS draf_client_chk_date,
                CASE WHEN gated_IN_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS gated_IN_chk_date,
                CASE WHEN gated_out_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS gated_out_chk_date,
                CASE WHEN delivered_chk_date  IS NOT NULL THEN 1 ELSE 0 END AS delivered_chk_date

                FROM check_list

                WHERE 

                booking = :booking

                Limit 1

        ) AS subquery

        
        ";


        $connection = Yii::$app->getDb();

        $command = $connection->createCommand($query, ['booking' => $this->booking_id ]);

        $result = $command->queryAll();

        $totalCompleted =$result[0]['total_completed'];


        $progress = round( (100 / $taskNumber ) * $totalCompleted, 2);  

        return $progress;
    }


    public function isDeadLineDay($estimatedDate){

            $estimatedDate = date("Y-m-d" , strtotime( $estimatedDate ) );
            
            $todayTime = strtotime(date("Y-m-d"));
            $estimatedTime =  strtotime($estimatedDate);
            //echo   '|' . $todayTime . '-' .  $estimatedTime  ;

            return $todayTime >= $estimatedTime;

    }

    public function generateCss(){
        return '
            body{
                width: 610px;
            }            
          
            .row {
              
              margin-left:-5px;
              margin-right:-5px;
            }
              
            .column {
              
              float: left;
              width: 365px;
              padding: 5px;
              margin-bottom: 20px;
              position:relative;
            }

            table {
              border-collapse: collapse;
              border-spacing: 0;
              width: 95%;
              border: 1px solid #A9A9A9;
              font-family: sans-serif;
            }

            th, td {
              text-align: left;
              padding: 3px;
              border: 1px solid #A9A9A9;
              font-family: sans-serif;
              font-size:12px;
            }

            .backcolor {
              background-color: #D3D3D3;
            }

            .wrapper {
              background-color: white;
               border: solid 1px green; 
            }

            #table-1{
              float: left;
              width: 95%;
              padding: 5px;
              margin-left:-5px;
              margin-right:-5px;
              margin-bottom: 50px;
              font-family: sans-serif;
            }

            #table-2{
              float: left;
              width: 95%;
              padding: 5px;
              margin-left:-5px;
              margin-right:-5px;
              font-family: sans-serif;
            }

            #zero-table{
              float: right;
              width: 95%;
              padding: 5px;
              margin-left:-5px;
              margin-right:-5px;
              font-family: sans-serif;
            }


            .address{
                width: 200px;
                font-size:16px;
            } ';
          
    }

    
public function generateBokingConfirmation(){

        $pdf = '
        <div class="row" >
              <div class="column"> 
                <div class="address" >
                    Freight Global Operator</br>
                    Av Mariano Otero 2347-112 Col. Verde Valle<br/>
                    RFC:FTM1507038V6</br>
                    Tel: +5233130061992</br>
               </div>
            </div>
            <div class="column" >
                <h1 class="confirm-title" style="font-family: sans-serif" >Booking Confirmation</h1>
                <table>
                    <tr>
                        <td class="backcolor" >Reference</td>
                        <td>
                            '.$this->booking_number. '<br />
                        </td>
                    </tr>
                    <tr>
                    <td class="backcolor">Creation Date</td>
                        <td>
                            '.date("d/m/Y h:i:s A", strtotime($this->created_at) ). '<br/>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    <div class="row">
        <div class="column" >
            <table>
                <tr>
                    <td class="backcolor">Client Information</td>
                </tr>
                <tr>
                    <td style="text-align: left; vertical-align: top; height:125px;">
                        '.$this->customer->fullName .'<br /><br />
                        '.$this->customer->address  .'<br />
                        '.$this->customer->address2 .'<br />
                        '.$this->customer->city.', '.$this->customer->state.' '.$this->customer->postal_code.'.<br />
                        '.$this->customer->country.'<br />
                    </td>   
                </tr>
          </table>
        </div>  
        <div class="column" >
            <table>
                <tr>
                    <td class="backcolor" colspan="2" >
                    Equipment Delivery Address          
                    </td>
                </tr>
                <tr>
                    <td colspan="2" >
                        '.$this->pickupPlace->name .'<br /><br />
                        '.$this->pickupPlace->address1  .'<br />
                        '.$this->pickupPlace->address2 .'<br />
                        '.$this->pickupPlace->city.', '.$this->pickupPlace->state.' '.$this->pickupPlace->postal_code.'.<br />
                        '.$this->pickupPlace->country.'<br />
                    </td>
                </tr>
                <tr>
                    <td class="backcolor" >
                        Spotting Date
                    </td>
                    <td>
                        '. ( empty($this->continuity->pickup_date) ? 'no set': date("d/m/Y h:i:s A", strtotime($this->continuity->pickup_date) ) )   .'
                    </td>
                </tr>               
            </table>
        </div>
    </div>

    <div class="row" >
        <div class="column" >
           <table id="table-1">
            <tr id="row-1" >
              <tr>
                 <td class="backcolor" >Carrier</td>
                   <td>'. $this->carrierModel->name .'</td>
                    </tr>
                    <tr>
                        <td class="backcolor" >Flight/Voyage</td>
                        <td>'. $this->vesselName .'</td>   
                    </tr>
                    <tr>
                        <td class="backcolor" >Origin Port</td>
                        <td>'. $this->portName .'</td>
                    </tr>
                    <tr>
                        <td class="backcolor">Destination Port</td>
                        <td>'. $this->dichargePort->name .'</td>
                    </tr>
                </tr>
         </table>
         <table id="table-2" >
            <tr id="row-2" >
                    <tr>
                      <td class="backcolor" style="width:43.5%">Cut off Date SI</td>
                      <td>'. (empty($this->continuity->SI_date)? 'no set': date("d/m/Y h:i:s A", strtotime($this->continuity->SI_date)) ).'</td>
                    </tr>
                    <tr>
                      <td class="backcolor" style="width:43.5%">Port Closing date</td>
                      <td>'. ( empty($this->continuity->doc_cut_of) ? 'no set': date("d/m/Y h:i:s A", strtotime($this->continuity->doc_cut_of)) ).'</td>
                    </tr>
                    <tr>
                        <td class="backcolor">Departure Date</td>
                        <td>'. (empty($this->loading_EDT) ? 'no set':  date("d/m/Y h:i:s A", strtotime($this->loading_EDT)) ) .'</td>    
                    </tr>
                    <tr>
                        <td class="backcolor">Arrival Date</td>
                        <td>'. ( empty($this->dicharge_ETA) ? 'no set': date("d/m/Y h:i:s A", strtotime($this->dicharge_ETA)) ).'</td>  
                    </tr>
            </tr>
          </table>
        </div>
        <div class="column" >
            <table id="zero-table">
                <tr>
                    <td class="backcolor">Remarks</td>
                </tr>
                <tr>
                    <td style="text-align: left; vertical-align: top; height:182px;">
                        
                    </td>
                </tr>
            </table>
        </div>
    </div>


    <div class="row" >
    <div class="full-col" >
            <table id="lastTable" >
                <tr>
                    <td class="backcolor" >Number</td>
                    <td class="backcolor" >Seal</td>
                    <td class="backcolor">Container Type</td>
                    <td class="backcolor" >Commodity</td>
                    <td class="backcolor">Quantity</td>
                </tr>';
                foreach ($this->containers as $key => $cont) {
                  $pdf .= '
                    <tr>
                     <td   style="text-align: left;  vertical-align: top; height:25px;"  >'.$cont->number.'</td>
                      <td  style="text-align: left;  vertical-align: top; height:25px;"  >'.$cont->seal.'</td>
                      <td  style="text-align: left;  vertical-align: top; height:25px;"  >'.$cont->type->container_name.'</td>
                      <td  style="text-align: left;  vertical-align: top; height:25px;"  >'.$cont->comodity.'</td>
                      <td  style="text-align: left;  vertical-align: top; height:25px;"  >'.$cont->quantity.'</td>
                     
                    </tr>
                  ';
                  $total = $total + $cont->quantity ;
                }
                $pdf .= '
                <tr>
                 
                    <td class="backcolor" ></td>
                    <td class="backcolor" ></td>
                    <td class="backcolor" ></td>
                    <td class="backcolor" >Totals</td>
                    <td class="backcolor" >Pieces</td>
                </tr>
                <tr>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td >'.$total.'</td>
                </tr>
            </table>
       </div>
    </div>

    ';

    return $pdf;
    
    }

    function sanitize_file_name( $unsafeFilename ) {
         // our list of "unsafe characters", add/remove characters if necessary
        $dangerousCharacters = array(" ", '"', "'", "&", "/", "\\", "?", "#");
        // every forbidden character is replaced by an underscore
        $safe_filename = str_replace($dangerousCharacters, '_', $unsafeFilename);
        
        return $safe_filename; 
    }


    function getContainers(){
        return Containers::find()->where(['booking' => $this->booking_id ])->all();  
    }

     function getNumber(){
        return Containers::find(['number' => $this->number ])->all();  
    }

    function getSeal(){
        return Containers::find(['seal' => $this->seal ])->all();  
    }

    public function getDocsFields(){
         return [
            'entrusts_letter_file',
            'warranty_file',
            'payments_file',
            'empty_maneuver_file',
            'maneuver_full_file',
            'commercial_bills_file',
            'petition_file',
            'swb_file'
        ];
    }

    public function saveError(){
         return implode(' ', array_map(function ($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
    }

    public function getBookingType(){
       return  [ 1 => 'Import', 2 =>  'Export' ];
    }   

     public function getTypeText(){
        $types =  $this->getBookingType();
        return $types[$this->booking_type];
    }

    public function getPdfLink(){
        $action = $this->tran_type == 0 ? 'pdf-invoice' : 'pdf-bill';
        return  '/web/transaction/'.$action.'?id='.$this->transc_id;
    }  

    public function getXmlLink(){
        $action = $this->tran_type == 0 ? 'xml-invoice' : 'xml-bill';
        return '/web/transaction/'.$action.'?id='.$this->transc_id;
     }  


}
