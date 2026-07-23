<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "containers".
 *
 * @property int $container_ID
 * @property int $quantity
 * @property string $comodity
 * @property int $container_type
 * @property int $booking
 * @property string $created_at
 * @property string $created_by
 * @property string $modified_by
 *
 * @property ContainerTypes $typeData
 * @property Booking $bookingData
 */
class Containers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'containers';
    }

    /**
     * {@inheritdoc}
     */
public function rules()
    {
        return [
            [['quantity', 'comodity', 'container_type' ], 'required'],
            [['quantity', 'container_type', 'booking', 'created_by', 'modified_by' ], 'integer'],
            [['created_at','modified_at'], 'safe'],
            [['comodity'], 'string', 'max' => 25],
            [['number', 'seal'], 'string', 'max' => 50],
            [['container_type'], 'exist', 'skipOnError' => true, 'targetClass' => ContainerTypes::className(), 'targetAttribute' => ['container_type' => 'contType_id']],
            [['booking'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking' => 'booking_id']],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'container_ID' => 'Container ID',
            'quantity' => 'Quantity',
            'comodity' => 'Comodity',
            'container_type' => 'Type Data',
            'booking' => 'Booking',
            'number' => 'Number',
            'seal' => 'Seal',
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
    public function getTypeData(){
    
        return $this->hasOne(ContainerTypes::className(), ['contType_id' => 'container_type']);
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
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking']);
    }
}
