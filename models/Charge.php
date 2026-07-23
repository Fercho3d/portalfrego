<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "charge".
 *
 * @property int $charge_id
 * @property int|null $transaction
 * @property int|null $type
 * @property string|null $tax_code
 * @property string|null $description
 * @property float|null $quantity
 * @property float|null $unit
 * @property float|null $price
 *
 * @property ChargeType $type0
 * @property Transaction $transaction0
 */
class Charge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $ChargeType;
    public $taxName;
    public $taxAmount;
    public $amount;
    public $totalAmount;
    public $tax_retention;
    public $retentionAmount;
    public $tax_rate;
    public static function tableName()
    {
        return 'charge';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['transaction', 'type' , 'quantity', 'price',  'price_confirmation'], 'required'],
            [ 'price_confirmation' , 'validatePrice' ],
            [ 'price_confirmation' , 'validateRange' ],
            [['transaction', 'type','prepaid','service_id'], 'integer'],
            [['quantity',  'price','price_confirmation'], 'number'],
            [['tax_code', 'unit' ], 'string', 'max' => 25],
            [['description'], 'string', 'max' => 100],
            [['type'], 'exist', 'skipOnError' => true, 'targetClass' => ChargeType::className(), 'targetAttribute' => ['type' => 'charge_type_id'] ],
            [['transaction'], 'exist', 'skipOnError' => true, 'targetClass' => Transaction::className(), 'targetAttribute' => ['transaction' => 'transc_id']],
         ];
    }

    /**
     * {@inheritdoc}
     */

    public function scenarios(){

        $scenarios['vendor'] =  ['description',  'price_confirmation', 'price' ];
        $scenarios['invoice'] = ['description', 'price', 'transaction', 'description', 'quantity' ];
        $scenarios['auto'] = ['description', 'price', 'transaction', 'description', 'quantity','service_id' ];

        return $scenarios;

    }
    public function attributeLabels()
    {
        return [
            'charge_id' => 'Charge ID',
            'transaction' => 'Transaction',
            'type' => 'Type',
            'tax_code' => 'Tax Code',
            'description' => 'Description',
            'quantity' => 'Quantity',
            'unit' => 'Unit',
            'price' => 'Price',
            'price_confirmation' => 'Price',
        ];
    }


    public function validatePrice($attribute, $params){
        
        if($this->service->price != 0 && floatval($this->service->price) !== floatval($this->price_confirmation) && floatval($this->price) !== floatval($this->price_confirmation) ){
           $this->addError(
                $attribute, 
                "Price don't match with assinged  (" . number_format($this->service->price,2) .' '. $this->transactionModel->accountModel->prefix .") " . $this->service->price
           );
        }
    }

    public function validateRange($attribute, $params){

        if( $this->service->price == 0 && ( floatval($this->price_confirmation) > floatval($this->service->max) || floatval($this->price_confirmation) < floatval($this->service->min) ) ){
           $this->addError(
                $attribute, 
                "Price don't match with range assinged (" . number_format( $this->service->min, 2 ) .' - '. number_format( $this->service->max, 2 ) .")" 
           );
        }
    }

    /**
     * Gets query for [[Type0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getType()
    {
        return $this->hasOne(ChargeType::className(), ['charge_type_id' => 'type']);
    }    
      
    public function getService(){
    
        return $this->hasOne(Service::className(), ['service_id' => 'service_id']);
    }    
   
    public function getTypeModel()
    {
        return $this->hasOne(ChargeType::className(), ['charge_type_id' => 'type']);
    }    

    public function getTax()
    {
        return $this->hasOne(TaxCode::className(), ['tax_code_id' => 'tax_code']);
    }

    /**
     * Gets query for [[Transaction0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTransaction()
    {
        return $this->hasOne(Transaction::className(), ['transc_id' => 'transaction']);
    }  

    public function getTransactionModel()
    {
        return $this->hasOne(Transaction::className(), ['transc_id' => 'transaction']);
    }

    public function getPrepaidlist(){
       return  [ 1 => 'Yes', 0 =>  'No'];
    }

    public function getPrepaidText(){
        $prepaid  = [ 1 => 'Yes', 0 =>  'No'];
        return $prepaid[$this->prepaid];
    }
}
