<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vessel".
 *
 * @property int $vessel_id
 * @property string|null $vessel_name
 *
 * @property Booking[] $bookings
 */
class Vessel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vessel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vessel_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vessel_id' => 'Vessel ID',
            'vessel_name' => 'Vessel Name',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['vessel' => 'vessel_id']);
    }


    public static function getList(){
        $query =  STATIC::find()->select(['vessel_id', 'vessel_name'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'vessel_id', 'vessel_name'): array();
    } 
}
