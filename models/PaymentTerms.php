<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_terms".
 *
 * @property int $pay_terms_id
 * @property string|null $pay_terms
 */
class PaymentTerms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_terms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pay_terms'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pay_terms_id' => 'Pay Terms ID',
            'pay_terms' => 'Pay Terms',
        ];
    }

    
    public static function getList(){
        $query =  STATIC::find()->select(['pay_terms_id', 'pay_terms'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'pay_terms_id', 'pay_terms'): array();
    } 
}
