<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dicharge_port".
 *
 * @property int $dicharge_port_id
 * @property string|null $name
 */
class DichargePort extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dicharge_port';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'dicharge_port_id' => 'Dicharge Port ID',
            'name' => 'Name',
        ];
    }

    public static function getList($dicharge_port_id = null){
        if(empty($dicharge_port_id)){
            $query =  STATIC::find()->select(['dicharge_port_id', 'name'])->andWhere(['deleted'=> 0])->all();
        }else{
            $query1 =  STATIC::find()->select(['dicharge_port_id', 'name'])->andWhere(['dicharge_port_id' => $dicharge_port_id]);
            $query =  STATIC::find()->select(['dicharge_port_id', 'name'])->andWhere(['<>', 'dicharge_port_id', $dicharge_port_id])
            ->andWhere(['<>','dicharge_port_id', $dicharge_port_id])
            ->andWhere(['deleted'=> 0])
            ->union($query1)
            ->all();
        }
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'dicharge_port_id', 'name'): array();
    }
}
