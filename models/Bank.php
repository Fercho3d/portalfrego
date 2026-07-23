<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property int $bank_id
 * @property string|null $bank_name
 * @property string|null $created_at
 * @property int|null $created_by
 * @property string|null $modified_at
 * @property int|null $modified_by
 *
 * @property Users $createdBy
 * @property Users $modifiedBy
 * @property BankEntry[] $bankEntries
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by','active', 'default' ], 'integer'],
            [['bank_name'], 'string', 'max' => 100],
            [['account_number'], 'string', 'max' => 64],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['created_by' => 'usr_id']],
            [['modified_by'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['modified_by' => 'usr_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bank_id' => 'Bank ID',
            'bank_name' => 'Bank Name',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'modified_at' => 'Modified At',
            'modified_by' => 'Modified By',
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreated()
    {
        return $this->hasOne(Users::className(), ['usr_id' => 'created_by']);
    }

    public function getTotal(){

        $searchModel = new BankEntrySearch();
        $searchModel->bank_id = $this->bank_id;
        $ops = $searchModel->search(array())->models;
        $total = 0;
        // print_r($models);
        foreach($ops as $op){
            $total = $total + $op['amount'];
            echo $model->amount;
        }
        
        return $total;
    }


    public function beforeSave($insert){
            
           if ($this->isNewRecord) { 
                $this->created_at = date("Y-m-d  H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;
           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

         return parent::beforeSave($insert);  
    }


    /**
     * Gets query for [[ModifiedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModified()
    {
        return $this->hasOne(Users::className(), ['usr_id' => 'modified_by']);
    }

    /**
     * Gets query for [[BankEntries]].
     *
     * @return \yii\db\ActiveQuery
     */
    
    public function getBankEntries(){
        return $this->hasMany(BankEntry::className(), ['bank_id' => 'bank_id']);
    }

    public static function getList(){
        $query =  STATIC::find()->select(['bank_id', 'bank_name'])->andWhere(['active' =>1])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'bank_id', 'bank_name'): array();
    }
}
