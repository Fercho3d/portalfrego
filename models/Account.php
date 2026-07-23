<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $account_id
 * @property string|null $acount_name
 */
class Account extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'acount_name' => 'Acount Name',
        ];
    }
    

    public static function getList()
    {
        $query =  STATIC::find()->select(['account_id', 'account_name'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'account_id', 'account_name'): array();
    } 


}
