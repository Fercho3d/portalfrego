<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "file_fields".
 *
 * @property int $field_id
 * @property string|null $field
 * @property string|null $label
 *
 * @property FieldsByClient[] $fieldsByClients
 * @property FilesByBooking[] $filesByBookings
 */
class FileFields extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_fields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['field', 'label'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'field_id' => 'Field ID',
            'field' => 'Field',
            'label' => 'Label',
        ];
    }

    /**
     * Gets query for [[FieldsByClients]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFieldsByClients()
    {
        return $this->hasMany(FieldsByClient::className(), ['field_id' => 'field_id']);
    }

    /**
     * Gets query for [[FilesByBookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFilesByBookings()
    {
        return $this->hasMany(FilesByBooking::className(), ['field_id' => 'field_id']);
    }


    public static function getList(){
        $query =  STATIC::find()->select(['field_id', 'label'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'field_id', 'label'): array();
    }
}
