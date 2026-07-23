<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tax_code".
 *
 * @property int $tax_code_id
 * @property string|null $tax_code
 */
class TaxCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tax_code';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tax_code'], 'string', 'max' => 255],
            [['tax_rate','tax_retention'], 'number', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tax_code_id' => 'Id Tax Code',
            'tax_code' => 'Tax Code',
            'tax_retention' => 'Tax Retention',
        ];
    }

    public static function getList(){
        $query =  STATIC::find()->select(['tax_code_id', 'tax_code'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'tax_code_id', 'tax_code'): array();
    } 
}
