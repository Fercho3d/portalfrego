<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "charge_type".
 *
 * @property int $charge_type_id
 * @property string|null $charge_type_name
 *
 * @property Charge[] $charges
 */
class ChargeType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'charge_type';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['charge_type_name'], 'required'],
            [['charge_type_id'], 'integer'],
            [['charge_type_name','tax_name'], 'string', 'max' => 25],
            [['charge_type_id'], 'unique'],
            [['tax_rate','tax_retention'], 'number', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'charge_type_id' => 'Charge Type ID',
            'charge_type_name' => 'Charge Type Name',
            'charge_type' => 'Type',
            'tax_rate' => 'VAT',
            'tax_name' => 'Tax',
            'tax_retention' => 'Withholding',
        ];
    }

    /**
     * Gets query for [[Charges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCharges()
    {
        return $this->hasMany(Charge::className(), ['type' => 'charge_type_id']);
    }

    public static function getList(){
        $query =  STATIC::find()->select(['charge_type_id', "CONCAT(charge_type_name, ' - ' , tax_name ) charge_type_name" ])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'charge_type_id', 'charge_type_name'): array();
    } 


}
