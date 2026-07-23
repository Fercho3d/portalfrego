<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionSearch extends Transaction
{
    public $dates;
    public $vendorName;
    public $noExchange;
    public $request_paid;
    public $request_type;
    public $paymentMode =  false;
    public $noNegative = false;
    public $unInvoicedBookings = false;

    /**
     * {@inheritdoc}
     */

    public function rules()
    {
        return [
            [['transc_id', 'account', 'booking','request_id', 'payment_request', 'paid'], 'integer'],
            [['tran_date',  'tran_number', 'pdf_attach', 'bill_address', 'dates', 'booking_number' ], 'safe'],
            [['dates','booking_number','payTerms','creator','modifier'], 'string'],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);

        $this->currency = empty($this->currency)? 1 : $this->currency ;

        $this->currency  = 1;
        
        $exchange = (!empty($this->transc_id_in) || ($this->noExchange) ) ? ' ' : ' * exchange.exchange_value ' ;
        $negative = $this->noNegative  && (!$this->paymentMode) ? ' ' : ' * -1 ' ;
        $negativeCondition = $this->paymentMode ? ' (tran_type <> 2 ) ': ' (tran_type = 0 OR tran_type = 2)';
        
        $subquery_16 = Charge::find()
        ->Select([
            'transaction', 
            'SUM(IFNULL(price, 0) * IFNULL(quantity, 0) ) subtotal',
            'SUM(IFNULL(price,0) * 
            IFNULL(quantity,0) * 
            IFNULL(charge_type.tax_rate , 0) ) taxAmount',
            'SUM(IFNULL(price, 0) * 
            IFNULL(quantity,0)  * 
            IFNULL(charge_type.tax_retention , 0) ) taxtRet' ]
        )
        ->leftJoin('charge_type', 'charge_type.charge_type_id = charge.type' )
        ->andWhere(['tax_rate'=> 0.16 ])
        ->andWhere(['non_deductible'=> 0 ])
        ->groupBy(['transaction']);

        $subquery_0  = Charge::find()->
        Select([
            'transaction',
            'SUM(IFNULL(price, 0) * IFNULL(quantity, 0) ) subtotal',
            'SUM(IFNULL(price, 0) * 
            IFNULL(quantity,0) * 
            IFNULL(charge_type.tax_rate, 0) ) taxAmount']
        )
        ->leftJoin('charge_type', 'charge_type.charge_type_id = charge.type' )
        ->andWhere(['tax_rate'=> 0 ])
        ->andWhere(['non_deductible'=> 0 ])
        ->GroupBy(['transaction']);

        $non_deductible  = Charge::find()->
        Select([
            'transaction',
            'SUM(IFNULL(price, 0) * IFNULL(quantity, 0) ) subtotal',
            'SUM(IFNULL(price, 0) * 
            IFNULL(quantity,0) * 
            IFNULL(charge_type.tax_rate, 0) ) taxAmount'
        ])
        ->leftJoin('charge_type', 'charge_type.charge_type_id = charge.type' )
        ->andWhere(['tax_rate'=> 0 ])
        ->andWhere(['non_deductible'=> 1 ])
        ->GroupBy(['charge.transaction']);
        
                    
        $stringPercent = ' ( IFNULL(payments_by_transaction.amount,0) / ( IFNULL(nonDec.subtotal,0) +   IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) )   )  ';
            
        $subquery_payments  = PaymentsByTransaction::find()->
        Select([
            'transc_id',
            'SUM( IFNULL(payments_by_transaction.amount,0)  ) tran_paid_amount',
            'VAT16.subtotal',  
            $stringPercent, 
            'SUM( ( IFNULL(VAT16.subtotal,0)   ) * ' .  $stringPercent  .') sub_16_paid',
            'SUM( ( IFNULL(VAT0.subtotal, 0)   ) * ' .  $stringPercent  .') sub_0_paid',
            'SUM( ( IFNULL(VAT16.taxAmount, 0) ) * ' .  $stringPercent  .') tax_16_paid',
            'SUM( ( IFNULL(VAT16.taxtRet, 0)   ) * ' .  $stringPercent  .') tax_ret_paid',
            'SUM(  (IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0)) * ' .  $stringPercent  .' ) total_paid',
            'SUM(  (IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0)) * ' .  $stringPercent   .') amount_original_paid',
        ])
        ->leftJoin(
            'payment_request',
            'payment_request.request_id = payments_by_transaction.request_id',
        )
        ->leftJoin('account',  'payment_request.currency_id = account.account_id ')
        ->leftJoin('exchange as exchange_req', 
        '(payment_request.currency_id = exchange_req.account AND `payment_request`.date = exchange_req.date_exchange) OR  
         (payment_request.currency_id = exchange_req.account AND account.`default` = 1 )
        ')
        ->leftJoin(['VAT16'=> $subquery_16 ], 'VAT16.transaction = payments_by_transaction.transc_id')
        ->leftJoin(['VAT0'=> $subquery_0 ], 'VAT0.transaction = payments_by_transaction.transc_id')
        ->leftJoin(['nonDec'=> $non_deductible ], 'nonDec.transaction = payments_by_transaction.transc_id')
        ->andFilterWhere(
            
            ['payment_request.type'=>$this->request_type],
            ['payment_request.paid' => $this->request_paid]
        );

        if(!empty($this->request_id)){
            $subquery_payments->andWhere(['payments_by_transaction.request_id'=>$this->request_id]);
        }

        $subquery_payments->GroupBy(['payments_by_transaction.transc_id']);
        
      
     


        $selectArray = [
            'account.prefix as currency',
            'IFNULL(payments.tran_paid_amount,0) tran_paid_amount',
            'IFNULL(payments.sub_16_paid,0) sub_16_paid',
            'IFNULL(payments.sub_0_paid,0) sub_0_paid',
            'IFNULL(payments.tax_16_paid,0) tax_16_paid',
            'IFNULL(payments.tax_ret_paid,0) tax_ret_paid',
            'transaction.request_id',
            'transaction.invoice',
            'transaction.cancelled',
            'transaction.payment_request',
            'transaction.paid',
            'transaction.pdf_attach',
            'transaction.xml_attach',
            'transaction.processed',
            'transaction.seal',
            'transaction.transc_id',
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
            'creator.username AS creator',
            'modifier.username AS modifier',
            'vendor.fullName AS vendorName',
            'account.account_name AS accountName',
            'booking.booking_number',
            'SUM(
               CASE WHEN '. $negativeCondition .'THEN IFNULL(nonDec.subtotal, 0) '. $exchange .' 
               ELSE IFNULL(nonDec.subtotal , 0) '. $exchange .' '.$negative.' END
            ) non_dec',

            'SUM(
               CASE WHEN '.$negativeCondition.' THEN IFNULL(VAT16.subtotal, 0) '. $exchange .' 
               ELSE IFNULL(VAT16.subtotal , 0) '. $exchange .' '.$negative.' END
            ) sub_16_mxn ',
                
           'SUM( 
               CASE WHEN '.$negativeCondition.' THEN IFNULL(VAT0.subtotal, 0) '. $exchange .' 
               ELSE IFNULL(VAT0.subtotal, 0) '. $exchange .' '.$negative.' END
               ) sub_0_mxn',
           'SUM(
               CASE WHEN '.$negativeCondition.' THEN IFNULL(VAT16.taxAmount, 0) '. $exchange . ' 
               ELSE IFNULL(VAT16.taxAmount, 0) '. $exchange .' '.$negative.' END
           ) tax_16_mxn',
           'SUM(
               CASE WHEN '.$negativeCondition.'THEN IFNULL(VAT16.taxtRet, 0) '. $exchange .' 
               ELSE IFNULL(VAT16.taxtRet, 0) '. $exchange .' '.$negative.' END
               ) tax_ret_mxn',
           'SUM(
               CASE WHEN '.$negativeCondition.'THEN IFNULL(VAT0.taxAmount, 0) '. $exchange .' 
               ELSE IFNULL(VAT0.taxAmount, 0) '. $exchange .' '.$negative.' END
               ) tax_0_mxn',
           'SUM( 
                IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal, 0) + IFNULL(VAT0.subtotal,0)
               ) amount_original',  
           'SUM( 
               CASE WHEN '.$negativeCondition.' THEN 
                    ( IFNULL(nonDec.subtotal,0) +  IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ) '. $exchange .'
               ELSE 
                    ( IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ) '. $exchange .' '. $negative.' 
               END
            )  total_amount', 
            
             'CASE WHEN '.$negativeCondition.' THEN SUM( IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ) 
             ELSE
             SUM( IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ) '. $negative.' 
             END
             total_natural_amount', 

           'CASE WHEN '.$negativeCondition.' THEN SUM( IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ) - ABS(SUM( IFNULL( payments.tran_paid_amount, 0 ) ) )  

           ELSE

            ( SUM( IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ) - ABS(SUM( IFNULL( payments.tran_paid_amount, 0 ) ) ) )'. $negative.' 

           END

           left_to_pay', 
           
           ];


        if(!$this->unInvoicedBookings){

            
        $query = Transaction::find()->select($selectArray)
                ->joinWith('booking')
                ->joinWith('vendorModel')
                ->joinWith('customerModel')
                ->joinWith('account')
                ->joinWith('modifier')
                ->joinWith('creator');
            
        }else{

            $query = Booking::find()->select($selectArray)
            ->leftJoin('transaction', "booking.booking_id = transaction.booking AND transaction.customer = :customer AND transaction.tran_type = '0' ")
            ->leftJoin('account', 'transaction.account = account.account_id')
            ->leftJoin('provider vendor', 'transaction.vendor = vendor.provider_id')
            ->leftJoin('client', 'transaction.customer = client.client_id')
            ->leftJoin('users modifier', 'transaction.modified_by = modifier.usr_id')
            ->leftJoin('users creator', 'transaction.created_by = creator.usr_id')
            ->addParams([':customer' => \Yii::$app->user->identity->client->client_id  ])->andWhere(['booking.is_draft'=> 0]);
        }
        
        


        
        $query->leftJoin(['VAT16'=> $subquery_16 ], 'VAT16.transaction = transaction.transc_id')
        ->leftJoin(['VAT0'=> $subquery_0 ], 'VAT0.transaction = transaction.transc_id')
        ->leftJoin(['nonDec'=> $non_deductible ], 'nonDec.transaction = transaction.transc_id');
        if(!empty($this->request_id)){ 
            $query->innerJoin(['payments'=> $subquery_payments ], ' payments.transc_id = transaction.transc_id' );
        }else{ 
            $query->leftJoin(['payments'=> $subquery_payments ], ' payments.transc_id = transaction.transc_id' );
        }
        //para pagos esto tiene que cambiar por la fecha de pago del request
        $query->leftJoin('exchange', 
        '(account.account_id = exchange.account AND `transaction`.tran_date = exchange.date_exchange  ) OR  
         (account.account_id = exchange.account AND  account.`default` = 1 )
        '); 

        if(\Yii::$app->user->identity->access == 10 ){
            // $query->andWhere(['or', [ 'tran_type' => '0'], ['is', 'tran_type',  new \yii\db\Expression('null')]  ]);
            $query->andWhere(['booking.client'=> \Yii::$app->user->identity->client->client_id ]); 
            $query->groupBy(['booking.booking_id','transaction.transc_id']);
        
        }elseif(\Yii::$app->user->identity->access == 11){
             $query->andWhere(['<>','tran_type', '0']);
             $query->andWhere(['transaction.vendor'=> \Yii::$app->user->identity->provider->provider_id ]); 
             $query->groupBy(['transc_id']);
        }
        

        if(!empty($this->booking)){
            $query->andFilterWhere(['booking' => $this->booking]);
        }    

        $query->andWhere(['booking.mode' => 10]);
        
       

        $this->load($params);


    
        if(!empty($this->dates)){
            $dates = explode(' - ', $this->dates);
            $startArray = explode('/', $dates[0]);
            $endArray = explode('/', $dates[1]);
            $startDate = $startArray[2]."-".$startArray[1]."-".$startArray[0]; 
            $endDate =  $endArray[2]."-".$endArray[1]."-".$endArray[0];         
            $query->andWhere(['between', 'tran_date', $startDate, $endDate]);
        }

            
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $left_to_pay_field = 'ABS(SUM(IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0)  )) - ABS(SUM(  IFNULL(payments.tran_paid_amount,0)))';
        $total_amount_field = ' ABS(SUM(IFNULL(nonDec.subtotal,0) + IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) ))';
        
        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        if( isset($this->paid) && $this->paid != "" ){
                
            if($this->paid == 0 ){
            
                $query->andHaving([ 'tran_paid_amount' => 0 ]);

            }elseif($this->paid == 2 ){ 

                $query->andHaving("$left_to_pay_field > 0 AND $left_to_pay_field < $total_amount_field ");
                
            }elseif($this->paid == 1 ){ 

                $query->andHaving(" $left_to_pay_field = 0 AND $total_amount_field > 0" );
            
            }

         
    }




        // grid filtering conditions
        $query->andFilterWhere([
            'tran_date' => $this->tran_date,
            'tran_number' => $this->tran_number,
            'payment_request' =>$this->payment_request,
            'paid' => $this->paid
        ]);

        $query->andFilterWhere(['like', 'booking.booking_number', $this->booking_number]);
            
     

        


        
            
        //echo $query->createCommand()->getRawSql(); exit;

        

        return $dataProvider;
    }



}
