<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "container_types".
 *
 * @property int $contType_id
 * @property string|null $container_name
 *
 * @property Booking[] $bookings
 */
class ContainerTypes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'container_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['container_name'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'contType_id' => 'Cont Type ID',
            'container_name' => 'Container Name',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['container_type' => 'contType_id']);
    }

    public static function getList(){
        $query =  STATIC::find()->select(['contType_id', 'container_name'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'contType_id', 'container_name'): array();
    } 
}
