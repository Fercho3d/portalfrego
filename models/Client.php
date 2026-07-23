<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "client".
 *
 * @property int $client_id
 * @property string|null $rfc
 * @property string|null $fullName
 * @property string|null $address
 * @property string|null $state
 * @property string|null $city
 * @property string|null $email
 * @property string|null $postal_code
 * @property int|null $phone
 * @property int|null $created_by
 * @property int|null $modified_by
 * @property string|null $created_at
 * @property string|null $modified_at
 */
class Client extends \yii\db\ActiveRecord implements IdentityInterface
{

    private $security ;
    const SCENARIO_LOGIN = 'login';
    public $password_field;
    public $number;
    public $verification_code;

    const ROLE_SPECIAL = 10;
    const ROLE_STANDAR = 9;

    /**
     * All the set of functions previous to rules come form user.php and are deployed for the login
     */

    public static function primaryKey()
    {
        return ["client_id"];
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
    public static function tableName()
    {
        return 'client';
    }

     public function scenarios()
    {
      $scenarios = parent::scenarios();
      $scenarios[self::SCENARIO_LOGIN] = ['email', 'password_field','rol'];
      return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['phone', 'created_by', 'modified_by'], 'integer'],
            //[['created_at', 'modified_at'], 'safe'],
            
            [['email', 'fullName', 'role' ], 'required' ],
            [['created_by', 'modified_by','role','activate_processed'], 'integer' ],
            [['role'], 'integer'],
            //[['rfc', 'state', 'city',  'postal_code', 'country' ], 'string', 'max' => 25 ],
            [['email_notification'], 'string', 'max' => 1000], 
            [['email'], 'string', 'max' => 50],
            [['password', 'auth_key', 'password_reset_token'], 'string', 'max' => 64],
            [['fullName'], 'string', 'max' => 100],
            [['email'], 'unique' ],
            [['fullName'], 'unique' ],
            [['notification_notes'], 'string', 'max' => 1000 ],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'Client ID',
            'rfc' => 'Rfc',
            'fullName' => 'Full Name',
            'address' => 'Address',
            'address2' => 'Address 2',
            'state' => 'State',
            'city' => 'City',
            'email' => 'Email',
            'password' => 'Password',
            'role' => 'Rol',
            'auth_key' => 'Auth Token',
            'password_reset_token' => 'Recover Token',
            'postal_code' => 'Postal Code',
            'phone' => 'Phone',
            'created_by' => 'Created By',
            'modified_by' => 'Modified By',
            'created_at' => 'Created At',
            'modified_at' => 'Modified At',
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
        
       // return true;
        return $this->security->validatePassword($password,$this->password); 
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

    public static function isSpecial()
    {
          if (static::findOne(['client_id' => Yii::$app->client->identity->id, 'role' => self::ROLE_SPECIAL])){
                 return true;
          } else {
                 return false;
          }
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }


    /*public function beforeSave($insert){
           if ($this->isNewRecord) { 
                      
                $this->created_at = date("Y-m-d H:i:s");
                $this->modified_at = date("Y-m-d H:i:s");
                $this->created_by =  Yii::$app->user->identity->id;
                $this->modified_by = Yii::$app->user->identity->id;

           }else{
                $this->modified_at = date("Y-m-d H:i:s");
                $this->modified_by =  Yii::$app->user->identity->id;  
           }

         return parent::beforeSave($insert);  
    }*/

     /*public static function find()
    {
        return new UserQuery(get_called_class());
    }*/

    public static function getList(){
        $query =  STATIC::find()->select(['client_id', 'email'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'client_id', 'email'): array();
    }

}
