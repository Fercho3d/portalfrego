<?php

namespace app\models;
use Yii;
use yii\httpclient\Client;


/**
 * This is the model class for table "exchange".
 *
 * @property int $exchange_id
 * @property float $exchange_value
 * @property string $date_exchange
 * @property int|null $account
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property string|null $created_at
 * @property string|null $modified_at
 */
class Exchange extends \yii\db\ActiveRecord
{
    public $banxicoToken = 'ec6fa840c008f53a7ddeeeffeee8a8c16f8050560d2584239dc3a7221f5ba322';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'exchange';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exchange_value', 'date_exchange'], 'required'],
            [['exchange_value'], 'number'],
            [['date_exchange', 'created_at', 'modified_at'], 'safe'],
            [['account', 'created_by', 'modified_by'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'exchange_id' => 'Exchange ID',
            'exchange_value' => 'Exchange Value',
            'date_exchange' => 'Date Exchange',
            'account' => 'Account',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }


    public function getAccountrel()
    {
        return $this->hasOne(Account::className(), [ 'account_id' =>   'account'   ]);
    }

    public function beforeSave($insert){

           if ($this->isNewRecord) { 

                $this->created_at =  date("Y-m-d H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;

           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

            $this->date_exchange = date("Y-m-d",  strtotime(str_replace('/', '-',$this->date_exchange)));

         return parent::beforeSave($insert);  
    }

    public function check($date){
        // Si el dia es mayor que hoy, no se puede obtener el dia y se termina el proceso
        // O Se verifica que la fecha no este actualmente ya registrada    
        if(strtotime($date) > strtotime('+1 days') || Exchange::findOne(['date_exchange' => $date ])) {
             return true;
        }   

        $dateTime = strtotime($date);

        $startTime = strtotime('-30 days', $dateTime);
        $endTime = strtotime($date.' -1 day');
        $startDate = date('d-m-Y', $startTime);
        $endDate = date('d-m-Y', $endTime);
        
        $url  =  "https://sidofqa.segob.gob.mx/dof/sidof/indicadores/158/$startDate/$endDate";

        $json = file_get_contents($url);
        $json = json_decode($json,true);

        $indicadores = $json['ListaIndicadores'];
        
        $c = count($indicadores);        

        $indicador = $indicadores[$c-1];

        // echo '<br />'.$c . '<br />';
        // echo '<pre>';
        // print_r($indicador);
        // echo '</pre>';

        $exchange_value = $indicador['valor'];
        $taken_date = $indicador['fecha'];
        
        // echo '<br />'.$exchange_value . '<br />';
        // echo '<br />'.$taken_date . '<br />';
        // return;

        if ($exchange_value != null) {
                     
                $this->exchange_value = $exchange_value;
                $this->date_exchange = $date;
                $this->taken_date = date('Y-m-d',strtotime($taken_date));
                $this->account = 2;
                $this->url = $url;

                if($this->save()){
                    return true;
                }else{
                    return false;
                }
        }
    }

}
