<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "modality".
 *
 * @property int $modality_id
 * @property string|null $modality_name
 *
 * @property BookingContinuity[] $bookingContinuities
 */
class Modality extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'modality';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['modality_id'], 'integer'],
            [['modality_name'], 'string', 'max' => 15],
            [['modality_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'modality_id' => 'Modality ID',
            'modality_name' => 'Modality Name',
        ];
    }

    /**
     * Gets query for [[BookingContinuities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookingContinuities()
    {
        return $this->hasMany(BookingContinuity::className(), ['modality' => 'modality_id']);
    }

    public static function getList(){
        $query =  STATIC::find()->select(['modality_id', 'modality_name'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'modality_id', 'modality_name'): array();
    } 
}
