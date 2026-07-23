<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "files_by_booking".
 *
 * @property int $booking_file_id
 * @property int|null $booking_id
 * @property int|null $field_id
 * @property string|null $value
 *
 * @property Booking $booking
 * @property FileFields $field
 */
class FilesByBooking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'files_by_booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking_id', 'field_id'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::className(), 'targetAttribute' => ['booking_id' => 'booking_id']],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => FileFields::className(), 'targetAttribute' => ['field_id' => 'field_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'booking_file_id' => 'Booking File ID',
            'booking_id' => 'Booking ID',
            'field_id' => 'Field ID',
            'value' => 'Value',
        ];
    }

    /**
     * Gets query for [[Booking]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::className(), ['booking_id' => 'booking_id']);
    }

    /**
     * Gets query for [[Field]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(FileFields::className(), ['field_id' => 'field_id']);
    }

    public function saveError(){
         return implode(' ', array_map(function ($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
    }
}
