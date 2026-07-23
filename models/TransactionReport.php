<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Transaction;

/**
 * TransactionSearch represents the model behind the search form of `app\models\Transaction`.
 */
class TransactionReport extends Transaction
{
    public $type;
    public $dates;
    public $vendorName;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transc_id', 'tran_number', 'account', 'booking', 'vendor','tran_type'], 'integer'],
            [['tran_type','tran_date', 'pdf_attach', 'bill_address','type','dates'], 'safe'],
            [['dates','booking_number','vendorName','payTerms','creator','modifier'], 'string'],
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
        $query = 
        Transaction::find()
        ->select([ 
            'transaction.transc_id',
            'transaction.tran_date',
            'transaction.tran_number',
            'transaction.tran_type',
            'transaction.booking',
            'transaction.account',
            'transaction.vendor',
            'transaction.created_at',
            'transaction.modified_at',
            'transaction.modified_by',
            'booking.booking_number',
            'creator.username AS creator',
            'modifier.username AS modifier',
            'terms.pay_terms as payTerms',
            'vendor.username AS vendorName',
            'account.account_name AS accountName',
            'booking.booking_number',
            'SUM(IFNULL(price, 0)  * IFNULL(quantity,0)) amount',
            'SUM(IFNULL(price, 0)  * IFNULL(quantity,0) * IFNULL(tax_code.tax_rate,0) ) taxAmount',
            'SUM(IFNULL(price, 0)  * IFNULL(quantity,0) * IFNULL(tax_code.tax_retention,0) ) retentionAmount',
            /*Bill = 1 Invoice 0*/
            'CASE
             WHEN tran_type = 1 THEN 

            SUM((IFNULL(price, 0) * IFNULL(quantity,0) * IFNULL(tax_code.tax_rate,0) + IFNULL(price, 0) * IFNULL(quantity,0) ) -
            (IFNULL(price,  0) * IFNULL(quantity,0) * IFNULL(tax_code.tax_retention,0) ) ) 
            ELSE
            SUM((IFNULL(price, 0) * IFNULL(quantity,0) * IFNULL(tax_code.tax_rate,0) + IFNULL(price, 0) * IFNULL(quantity,0) ) -
            (IFNULL(price,  0) * IFNULL(quantity,0) * IFNULL(tax_code.tax_retention,0) ) ) * -1
            END

            totalAmount'  
        ])
        ->joinWith('booking')
        ->joinWith('vendor')
        ->joinWith('account')
        ->joinWith('terms')
        ->joinWith('modifier')
        ->joinWith('creator')
        ->joinWith('charges')
        ->leftJoin('tax_code', 'tax_code.tax_code_id = charge.tax_code')
        ->groupBy(['transc_id'])
        //->where(['booking' => $booking])
        ;
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $this->load($params);

        
        if(!empty($this->dates)){
            $dates = explode(' - ', $this->dates);
            $startArray = explode('/', $dates[0]);
            $endArray = explode('/', $dates[1]);
            $startDate = $startArray[2]."-".$startArray[1]."-".$startArray[0]; 
            $endDate =  $endArray[2]."-".$endArray[1]."-".$endArray[0];         
            $query->filterWhere(['between', 'tran_date', $startDate, $endDate]);
        } 
    

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'transc_id' => $this->transc_id,
            'tran_date' => $this->tran_date,
            'tran_number' => $this->tran_number,
            'account' => $this->account
        ]);
        
        $query->andFilterWhere(['like', 'xml_attach', $this->xml_attach])
            ->andFilterWhere(['like', 'pdf_attach', $this->pdf_attach])
            ->andFilterWhere(['like', 'bill_address', $this->bill_address])
            ->andFilterWhere(['like', 'tran_type', $this->type])
            ->andFilterWhere(['like', 'booking_number', $this->booking_number])
            ->andFilterWhere(['like', 'terms.pay_terms', $this->payTerms])
            ->andFilterWhere(['like', 'creator.username', $this->creator])
            ->andFilterWhere(['like', 'modifier.username', $this->modifier])
            ->andFilterWhere(['like', 'vendor.username', $this->vendorName]);

        return $dataProvider;
    }



}
