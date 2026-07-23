<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "providers_by_booking".
 *
 * @property int $prov_by_booking_id
 * @property int|null $booking
 * @property int|null $provider
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property string|null $created_at
 * @property string|null $modified_at
 *
 * @property Booking $booking0
 * @property Provider $provider0
 */
class ProvidersByBooking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'providers_by_booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking', 'provider', 'created_by', 'modified_by'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['booking'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking' => 'booking_id']],
            [['provider'], 'exist', 'skipOnError' => true, 'targetClass' => Provider::className(), 'targetAttribute' => ['provider' => 'provider_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'prov_by_booking_id' => 'Prov By Booking ID',
            'booking' => 'Booking',
            'provider' => 'Provider',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
        ];
    }

    /**
     * Gets query for [[Booking0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking0()
    {
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking']);
    }

    /**
     * Gets query for [[Provider0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProviderModel()
    {
        return $this->hasOne(ProvidersByBooking::className(), ['provider_id' => 'provider']);
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
}
