<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "carrier".
 *
 * @property int $carrier_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $created_at
 * @property string|null $modified_at
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 */
class Carrier extends \yii\db\ActiveRecord implements IdentityInterface
{
    private $security;
    const SCENARIO_EDITCARRIER = 'edit_user';
    const SCENARIO_CREATE_CARRIER = 'create_user';
    public $password_field;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carrier';
    }

    public static function primaryKey()
    {
        return ["carrier_id"];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    function __construct(){
        $this->security = new Security();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['name', 'email', 'password'], 'required'],
            [['name'], 'required'],
            [['created_at', 'modified_at'], 'safe'],
            [['created_by', 'modified_by'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['password', 'auth_key'], 'string', 'max' => 128],
            [['password_reset_token'], 'string', 'max' => 64],
            [['email'], 'unique'],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'carrier_id' => 'Carrier ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'password_field' => 'Password',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'auth_key' => 'Auth Key',
            'password_reset_token' => 'Password Reset Token',
        ];
    }


    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password );
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = $this->security->generateRandomKey() . '_' . time();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function setPassword($password){
        
        $this->password = $this->security->generatePasswordHash($password);
    }


    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }


    public function generateAuthKey()
    {
        $this->auth_key = $this->security->generateRandomString(50) . '_' . time();
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByName($name)
    {
        return static::findOne(['name' => $name]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function beforeSave($insert){
           if ($this->isNewRecord) { 
                      
                $this->created_at = date("Y-m-d H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;

           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

            if(!empty($this->password_field)){
                $this->password = $this->security->generatePasswordHash($this->password_field);
            }

         return parent::beforeSave($insert);  
    }

    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }

            if(!empty($this->password_field)){ 
                $this->password = $this->security->generatePasswordHash($this->password_field);
            }
            
            return true;
        }
        return false;
    }*/

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

     public static function getList(){
        $query =  STATIC::find()->select(['carrier_id', 'name'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'carrier_id', 'name'): array();
    }

       public function saveError(){
         return implode(' ', array_map(function ($errors) { return implode(' ', $errors);}, $this->getErrors() ) );
    }


}
