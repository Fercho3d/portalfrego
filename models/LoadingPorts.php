<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "loading_ports".
 *
 * @property int $port_id
 * @property string|null $port_name
 *
 * @property Booking[] $bookings
 */
class LoadingPorts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'loading_ports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['port_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'port_id' => 'Port ID',
            'port_name' => 'Port Name',
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::className(), ['loading_port' => 'port_id']);
    }

    public static function getList($port_id = null){
        
        if(empty($port_id)){
            $query =  STATIC::find()->select(['port_id', 'port_name'])->andWhere(['deleted' => 0])->all();
        }else{
            $query1 =  STATIC::find()->select(['port_id', 'port_name'])->andWhere(['port_id' => $port_id]);
            $query =  STATIC::find()->select(['port_id', 'port_name'])->andWhere(['<>', 'port_id', $port_id])
            ->andWhere(['deleted' => 0])
            ->union($query1)->all();
        }
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'port_id', 'port_name'): array();
    } 
}
