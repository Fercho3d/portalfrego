<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PaymentRequest;

/**
 * PaymentRequestSearch represents the model behind the search form of `app\models\PaymentRequest`.
 */
class PaymentRequestSearch extends PaymentRequest
{
    public $groupBy = ['payment_request.request_id'];
    public $noExchange = false;
    public $noNegative = false;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'number', 'paid', 'created_by', 'modified_by','provider_id','paid'], 'integer'],
            [['created_at', 'modified_at','dates'], 'safe'],
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
   
        $negative = $this->noNegative  ? ' ' : ' * (CASE WHEN type = 1 THEN 1 ELSE -1 END )' ;
        $exchange_req = $this->noExchange  ? ' ' : ' * (CASE WHEN custom_tc = 1 THEN payment_request.tc_value ELSE exchange.exchange_value END )' ;

        $stringPercent = ' ( IFNULL(payments_by_transaction.amount,0) / (  IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0) )   )  ';

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
        ->groupBy(['transaction']);
            
        $subquery_0  = Charge::find()->
        Select([
            'transaction',
            'SUM(IFNULL(price, 0) * IFNULL(quantity, 0) ) subtotal',
            'SUM(IFNULL(price, 0) * 
            IFNULL(quantity,0) * 
            IFNULL(charge_type.tax_rate, 0) ) taxAmount',
        ])
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

         $query = PaymentRequest::find()->select([
             'payment_request.request_id',
             'payment_request.amount',
             'payment_request.date',
             'payment_request.number',
             'payment_request.client_id',
             'payment_request.provider_id',
             'payment_request.paid',
             'payment_request.type',
             'VAT16.subtotal',
             'ROUND(SUM( ( IFNULL(nonDec.subtotal,0) '.  $exchange_req  .' ) * ' .  $stringPercent  .')  ' . $negative . ',4) non_dec',
              $stringPercent, 
             'SUM( ( IFNULL(VAT16.subtotal,0) '.   $exchange_req  .' ) * ' .  $stringPercent  .')  ' . $negative . ' sub_16_paid',
             'SUM( ( IFNULL(VAT0.subtotal, 0) '.   $exchange_req  .' ) * ' .  $stringPercent  .')  ' . $negative . ' sub_0_paid',
             'SUM( ( IFNULL(VAT16.taxAmount, 0) '.  $exchange_req .' ) * ' .  $stringPercent .')  ' .  $negative . ' tax_16_paid',
             'SUM( ( IFNULL(VAT16.taxtRet, 0)  '.   $exchange_req   .' ) * ' .$stringPercent   .')  ' . $negative . ' tax_ret_paid',
             'SUM( ( (IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0)) '.  $exchange_req   .' ) * ' .  $stringPercent   .')  ' . $negative . ' total_paid',
             'SUM( ( (IFNULL(VAT16.subtotal,0) + IFNULL(VAT0.subtotal,0) + IFNULL(VAT16.taxAmount,0) - IFNULL(VAT16.taxtRet,0)) '.  $exchange_req   .' ) * ' .  $stringPercent   .')  ' . $negative . ' amount_original_paid',
         ])

        ->joinWith('payments')
        ->leftJoin(['VAT16'=> $subquery_16 ], 'VAT16.transaction = payments_by_transaction.transc_id')
        ->leftJoin(['VAT0'=> $subquery_0 ], 'VAT0.transaction = payments_by_transaction.transc_id')
        ->leftJoin(['nonDec'=> $non_deductible ], 'nonDec.transaction = payments_by_transaction.transc_id')
        ->joinWith('account')
        ->leftJoin('exchange', 
        '(account.account_id = exchange.account  AND `payment_request`.date = exchange.date_exchange) OR
         (account.account_id  = exchange.account AND account.`default` = 1 )  
        ');
            
        $query->groupBy($this->groupBy);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if(!empty($this->dates)){
            $dates = explode(' - ', $this->dates);
            $startArray = explode('/', $dates[0]);
            $endArray = explode('/', $dates[1]);
            $startDate = $startArray[2]."-".$startArray[1]. "-". $startArray[0]; 
            $endDate =  $endArray[2]."-".$endArray[1]."-".$endArray[0]; 
            $query->andfilterWhere(['between', 'DATE(date)', $startDate, $endDate]);
        }   


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // echo $query->createCommand()->getRawSql();

        // grid filtering conditions
        $query->andFilterWhere([
            'provider_id' => \Yii::$app->user->identity->provider->provider_id,
            'request_id' => $this->request_id,
            'number' => $this->number,
            'amount' => $this->amount,
            'payment_request.paid' => $this->paid,
            'created_at' => $this->created_at,
            'modified_at' => $this->modified_at,
            'created_by' => $this->created_by,
            'modified_by' => $this->modified_by,
        ]);

        return $dataProvider;
    }
}
