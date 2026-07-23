<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fields_by_client".
 *
 * @property int $customer_field_id
 * @property int|null $client_id
 * @property int|null $field_id
 *
 * @property FileFields $field
 * @property Client $client
 */
class FieldsByClient extends \yii\db\ActiveRecord
{

    public $booking_id;
    public $value;
    public $booking_file_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'fields_by_client';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'field_id'], 'integer'],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => FileFields::className(), 'targetAttribute' => ['field_id' => 'field_id']],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::className(), 'targetAttribute' => ['client_id' => 'client_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_field_id' => 'Customer Field ID',
            'client_id' => 'Client ID',
            'field_id' => 'Field ID',
        ];
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


    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::className(), ['client_id' => 'client_id']);
    }




}
