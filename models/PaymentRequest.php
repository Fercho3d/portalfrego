<?php

namespace app\models;

use Yii;
use numberFormated;

/**
 * This is the model class for table "payment_request".
 *
 * @property int $id
 * @property int $number
 * @property float $amount
 * @property int|null $paid
 * @property string|null $created_at
 * @property string|null $modified_at
 * @property int|null $created_by
 * @property int|null $modified_by
 */
class PaymentRequest extends \yii\db\ActiveRecord
{
    public $dates;
    public $amount_original_paid;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_request';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number', 'amount'], 'required'],
            [['temp_number', 'bank_id', 'paid', 'created_by', 'modified_by', 'provider_id', 'type', 'currency_id' ], 'integer' ],
            [['amount',], 'number'],
            [['number',], 'string'],
            [['created_at', 'modified_at','date'], 'safe'],
        ];
    }


    public function beforeSave($insert){

           if ($this->isNewRecord) { 
                $this->date =  date("Y-m-d H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;

           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }
                                                     
         return parent::beforeSave($insert);  
    }

    public function getProvider(){
        return $this->hasOne(Provider::className(), ['provider_id'=> 'provider_id' ]);
    }

    public function getAccount(){
        return $this->hasOne(Account::className(), [ 'account_id' => 'currency_id'   ]);
    }   

    public function attributeLabels()
    {
        return [
            'id' => 'Request ID',
            'number' => 'Number',
            'amount' => 'Amount',
            'paid' => 'Paid',
            'created_at' =>  'Date',
            'modified_at' => 'Modified At',
            'created_by' =>  'Created By',
            'modified_by' => 'Modified By',
            'provider_id' => 'Provider',
        ];
    }


    public function generateCss(){

        return '
        html{
            width: 21cm;
            height: 29.7cm;
            padding: 1cm;
            border: .5px solid;
            font-family: arial;
        }

     

        body{
            border: .5px solid;
            width: 100%;
            height: 100%;
            padding: 0px;
            margin:0px;
            float: left;
        }

        .paycheck{ 
            border: .5px solid;
            height: 7.5cm;
            padding: 0.5cm;
            width: 100%;
            float: left;
            width: 20cm;
            position: relative;
            background-color: #b6f0cf;
        }

        .company-name{
            font-size: 18pt;
            font-weight: bolder;
            width: 10cm;
            height:1cm;
        }

        .address{
            font-size: 11pt;
            margin-top: 1cm;
            width:8cm;
            float:left;
        }

         .date{
            width: 4cm;
            float: right;
            text-align: right;
            position: relative;
            maring-top: 10cm;
            marging-right: .5cm;
            border-bottom: 2px solid;
        }


        .number{ 
            float: right;
            text-align: right;
            position: relative !important;
            width: 2cm;
            font-weight: bold;
            color:red;
            margin-top:-1cm;
        }

       
        .pay{
            margin-top: .1cm;
            width: 20cm;
            float:left;
            position: relative;
        }

        .vendor-name{
             width: 14cm;
             float: left;
             position: relative;
             border-bottom: 2px solid;
        }

        .pay .label{
            width: 1.5cm;
            float: left;
            position: relative;
            font-weight: bold;
        }

        .pay .amount{
             width: 3.5cm;
             float: left;
             position: relative;
             border-bottom: 2px solid;
             margin-left: .4cm;
        }


        .pay .amount-text{
           width: 3cm;
           float: left;
           position: relative;
           border-bottom: 2px solid;
        }
        .amount-text span{
            float: left;
            position:relative;
        }

         .amount-text{
           width: 20cm;
           float: left;
           position: relative;
           margin-top: 10pt;
           border-bottom: 2px solid;
        }
        .to-the span{
            font-weight: bold;
        }

         .to-the{
           width: 20cm;
           float: left;
           position: relative;
           margin-top: 20pt;
        }

        .memo .label{
            font-weight: bold;
            width: 1.5cm;
            position: relative;
            float: left;
        }

        .memo{
           width: 19cm;
           float: left;
           position: relative;
           margin-top: 20pt;
           
        }

        .memo .col-1{
           width: 8.5cm;
           float: left;
           position: relative;
           border-bottom: 2px solid;
          
           display: inline-block;
        }

        .memo .col-2{
           width: 8.75cm;
           float: left;
           position: relative;
           border-bottom: 2px solid;
           margin-left: .5cm;
        }

        .last-number{
           width: 8.75cm;
           float: left;
           position: relative;
           margin-top: 5pt;
           margin-left:2cm;
           text-align: center;
        }

        .bills{
            margin-top: 1cm;
            float: left;
            font-size: 12px;
            border-spacing: 0px;
            border-collapse: separate;
            width: 100%;
        }

        .bills td{
            border:.5pt solid #d0d0d0;
            margin-left: 10px;
            padding: 4px;
        }

        .bills td, .bills tr{
            border-collapse: collapse;
            border-spacing: 0;
        }

        .total{
            text-align: right;
        }
        ';
    }

    public function getNumberFormated(){
        return str_pad($this->request_id,3,'0',STR_PAD_LEFT);
    }

    public function getAmountFormated(){
        return number_format($this->amount,2);
    }

    public function getAmountText(){
        
        return str_pad(Yii::$app->formatter->asSpellout(floor($this->amount)).' ', 116,'-',STR_PAD_RIGHT ).' ';

    }


    public function getCents(){
        $cents = $this->amount - floor($this->amount);
        return number_format($cents,2);
    }


    public function getTransactions(){
        $searchModel = new TransactionSearch();
        $searchModel->request_id = $this->request_id;
        $searchModel->noExchange = $this->request_id;
        $searchModel->noNegative = false;
        $searchModel->paymentMode = true;  
        return $searchModel->search(array(''))->models;
    }


    public function generateDocument(){

        $formatter = \Yii::$app->formatter;
        $date =  $formatter->format($this->created_at, 'date');
        $number  = str_pad($this->number,10,'0', STR_PAD_LEFT);

        $html = '<!DOCTYPE html>
        <html>
        <head>  
        </head>
            <body>
                <div class="paycheck" >
                    <div class="company-name">Freight Global Operator</div>
                    <div class="number" >'.$this->numberFormated.'</div>
                    <div class="address" >
                        Av Mariano Otero 2347-112 <br />
                        Col. Verde Valle<br />
                        RFC:FTM1507038V6<br />
                        Guadalajara, JALISCO 44550.<br />
                    </div>
                    <div class="date">'.$date.'</div>
                    <div class="pay" > 
                        <div class="label" >PAY</div>
                        <div class="vendor-name" >'.$this->provider->fullName.'</div>
                        
                        <div class="amount" >$ '.$this->amountFormated.'</div>
                    </div>
                    <div class="amount-text" >
                    <span>'.$this->amountText.'</span>
                    <span>'.$this->cents.'/100'. $this->transactions[0]['currency'].'</span>
                    </div>
                    <div class="to-the" ><span>To the order</span> '.$this->provider->fullName.'</div>
                    <div class="memo" >
                        <div class="label">Memo</div>
                        <div class="col-1">&nbsp;</div>
                        <div class="col-2">&nbsp;</div>
                    </div>
                    <div class="last-number">'.$number.'</div>
                </div>
            </body>
            <table class="bills" >
                    <tr>
                    <th>Number</th>
                    <th>Booking</th>
                    <th>Amount</th>
                    <th>Subtotal %0</th>
                    <th>Subtotal %16</th>
                    <th>VAT %16</th>
                    <th>Ret VAT</th>
                    <th>Non Deduc</th>
                    <th>Total Amount</th>
                    <th>Total Paid</th>
             </tr>';

          foreach($this->transactions as $model){ 
                    $total += $model->total_amount;
                    $total_paid += $model->tran_paid_amount;
                    
                    $html .='<tr>
                        <td>'.$model->tran_number.'</td>
                        <td>'.$model->bookingModel->booking_number.'</td>
                        <td style="text-align:right" >'.number_format($model->amount_original, 2).'</td>
                        <td style="text-align:right" >'.number_format($model->sub_0_mxn, 2).'</td>
                        <td style="text-align:right" >'.number_format($model->sub_16_mxn, 2).'</td>
                        <td style="text-align:right" >'.number_format($model->tax_16_mxn,2).'</td>
                        <td style="text-align:right" >'.number_format($model->tax_ret_mxn,2).'</td>
                        <td style="text-align:right" >'.number_format($model->non_dec,2).'</td>
                        <td style="text-align:right" >'.number_format($model->total_amount,2).'</td>
                        <td style="text-align:right" >'.number_format($model->tran_paid_amount,2).'</td>
                    </tr>';  
                }

                $html .='<tr>
                    <td colspan="8" class="total" >Total</td>
                    <td  class="total" ><strong>$ '.number_format($total ,2).'</strong></td>
                    <td  class="total" ><strong>$ '.number_format($total_paid ,2).'</strong></td>
                </tr>
            </table>
        </html>';

        return $html;

        
    }


    public function getPayments(){
        return $this->hasMany(PaymentsByTransaction::className(),['request_id'=>'request_id']);
    }   

    public function saveError(){
         return implode(' ', array_map(function ($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
    }

}
