<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pickup_place".
 *
 * @property int $pick_up_place_id
 * @property string $name
 * @property string|null $address1
 * @property string|null $address2
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @property string|null $postal_code
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property string|null $created_at
 * @property string|null $modified_at
 */
class PickupPlace extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pickup_place';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['created_by', 'modified_by'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['address1'], 'string', 'max' => 255],
            [['city', 'state', 'country', 'postal_code'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pick_id' => 'Pickup Place ID',
            'name' => 'Name',
            'address1' => 'Address',
            //'address2' => 'Address2',
            'city' => 'City',
            'state' => 'State',
            'country' => 'Country',
            'postal_code' => 'Postal Code',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

     /**
     * Gets query for [[TypeData]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeData()
    {
        return $this->hasOne(ContainerTypes::className(), ['pick_id' => 'name']);
    }

    public function beforeSave($insert){

           if ($this->isNewRecord) {    
                
                $this->created_at =  date("Y-m-d H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;
                
           }else{
              
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by = Yii::$app->user->identity->id;  
           }

         return parent::beforeSave($insert);  
    }


    public function getType(){
         return $this->hasOne(ContainerTypes::className(), ['contType_id'  =>  'container_type']); 
    }    


    /**
     * Gets query for [[BookingData]].
     *
     * @return \yii\db\ActiveQuery
     
    public function getPickupPlace()
    {
        return $this->hasOne(PickupPlace::className(), ['pick_id' => 'pick_id']);
    }*/


    public static function getList(){
        $query =  STATIC::find()->select(['pick_id', 'name'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'pick_id', 'name'): array();
    }

}
