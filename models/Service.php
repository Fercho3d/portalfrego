<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "service".
 *
 * @property int $service_id
 * @property int $name
 * @property int|null $charge_type_id
 * @property int|null $provider_id
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $modified_at
 * @property string|null $modified_by
 *
 * @property Provider $provider
 */
class Service extends \yii\db\ActiveRecord
{
    public $date_range;
    public $prices;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'charge_type_id','account_id' ], 'required'],
            [['price', 'min','max'], 'number' ],
            [['description'], 'string', 'max' => 255 ],
            [['date_range','start_date','end_date'], 'safe' ],
            [[ 'charge_type_id', 'provider_id', 'created_by', 'active', 'account_id','loading_port_id', 'dicharge_port_id', 'type'], 'integer'],
            [['created_at', 'modified_at', 'modified_by'], 'safe'],
            [['provider_id'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::className(), 'targetAttribute' => ['provider_id' => 'provider_id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'Service ID',
            'name' => 'Name',
            'charge_type_id' => 'Charge/Service',
            'account_id' => 'Currency',
            'provider_id' => 'Provider ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
            'date_range' => 'Contract Duration',
        ];
    }


    public function beforeSave($insert){
            
           if ($this->isNewRecord) { 
                $this->created_at = date("Y-m-d  H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;
           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

      if(!empty($this->date_range)){
            $dates = explode(' - ', $this->date_range);
            $startArray = explode('/', $dates[0]);
            $endArray = explode('/', $dates[1]);
            $this->start_date = $startArray[2]."-".$startArray[1]."-".$startArray[0]; 
            $this->end_date = $endArray[2]."-".$endArray[1]."-".$endArray[0];         
        } 

           return parent::beforeSave($insert);
    }

    /**
     * Gets query for [[Provider]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProvider()
    {
        return $this->hasOne(Provider::className(), ['provider_id' => 'provider_id']);
    }        

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['usr_id'  => 'created_by' ]);
    }    
    
    public function getModifier(){
    
        return $this->hasOne(User::className(), ['usr_id'  => 'created_by' ]);
    }    

    public function getTypeModel(){
    
        return $this->hasOne(ChargeType::className(), ['charge_type_id' => 'charge_type_id']);
    }

    public function getAccount(){
    
        return $this->hasOne(Account::className(), ['account_id' => 'account_id']);
    }

    public function getStatusList(){
        return [
           1 => 'Active',
           0 => 'Inactive'
        ];
    }     

    public function saveError(){
         return implode(' ', array_map(function ($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
    }


    public static function getListFilter($charge_id,$id,$type){

        $field = $type == 1 ? 'client_id' : 'provider_id';

        $query =  STATIC::find()
        ->select([
            'service_id',  
            'price', 
            "CONCAT(IFNULL(service.description,'No description'),  ' - $', FORMAT(service.price,2) ) description" ]
        )
        ->andWhere(['charge_type_id'=> $charge_id])
        ->andWhere([$field => $id , 'service.type' => $type])
        ->andWhere(['active'=> 1 ])
        ->all();

        foreach($query as $key => $model){
            $services[] = [ 'id'=>$model->service_id, 'text'=> $model->description,  'data-price' => $model->price ];
        }


        return count($services) ? $services: array();
         
    } 


}
