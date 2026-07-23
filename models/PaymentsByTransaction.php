<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payments_by_transaction".
 *
 * @property int $request_id
 * @property int $transc_id
 * @property float|null $amount
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $modified_at
 * @property int|null $modified_by
 */
class PaymentsByTransaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments_by_transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['request_id', 'transc_id'], 'required'] ,
            [['request_id', 'transc_id', 'modified_by' ], 'integer' ],
            [['amount'], 'number'],
            [['created_at', 'modified_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'request_id' => 'Request ID',
            'transc_id' => 'Transc ID',
            'amount' => 'Amount',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
        ];
    }


    public function beforeSave($insert){

        if ($this->isNewRecord) { 
            $this->created_at =  date("Y-m-d H:i:s");
            $this->modified_at = date("Y-m-d H:i:s");
            $this->created_by =  Yii::$app->user->identity->id;
            $this->modified_by = Yii::$app->user->identity->id;
        }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
        }           

        
        return parent::beforeSave($insert);  
    }

    public function saveError(){
        return implode(' ', array_map(function($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
   }



}