<?php

namespace app\models;


use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;


use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $usr_id
 * @property string $email
 * @property string $username
 * @property string $password
 * @property string $name
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $country
 * @property string $state
 * @property string $city
 * @property string $genere
 * @property int $created_by
 * @property int $modified_by
 * @property string $created_at
 * @property string $last_login
 * @property string $modified_at
 * @property string $timezone
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    private $security ;
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_LOGIN_APP = 'login_app';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_EDITUSER = 'editUser';
    const SCENARIO_LAST_LOGIN = 'last_login';
    const ROLE_ADMIN = 10;
    const ROLE_USER = 9;
    public $password_field;
    public $number;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    public static function primaryKey()
    {
        return ["usr_id"];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    function __construct(){

        $this->security = new Security();
    }


    public function scenarios()
    {
      $scenarios = parent::scenarios();
      $scenarios[self::SCENARIO_LAST_LOGIN] = ['last_login'];
      $scenarios[self::SCENARIO_LOGIN] = ['username', 'password','rol'];
      $scenarios[self::SCENARIO_LOGIN_APP] = ['username'];
      $scenarios[self::SCENARIO_EDITUSER] =  ['username', 'email','full_name','profile_img','cover_img'];
      return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function rules() 
    {
        return [
         
            [['created_by', 'modified_by','access'], 'integer'],
            [['email','username'], 'unique'],
            [['role'], 'number'],
            [['client_id'], 'number'],
            [['email', 'username', 'password'], 'string', 'max' => 45],
            [['auth_key', 'password_reset_token'], 'string', 'max' => 64],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'usr_id' => Yii::t('app', 'Usr ID'),
            'email' => Yii::t('app', 'Correo'),
            'username' => Yii::t('app', 'Usuario'),
            'password' => Yii::t('app', 'Contraseña'),
            'password_field' => Yii::t('app', 'Contraseña'),
            'role' => Yii::t('app', 'Rol'),
            'auth_key' => Yii::t('app', 'Auth Token'),
            'password_reset_token' => Yii::t('app', 'Recover Token'),
            'country' => Yii::t('app', 'País'),
            'state' => Yii::t('app', 'Estado'),
            'city' => Yii::t('app', 'Ciudad'),
            'created_by' => Yii::t('app', 'Created By'),
            'modified_by' => Yii::t('app', 'Modified By'),
            'created_at' => Yii::t('app', 'Created At'),
            'last_login' => Yii::t('app', 'Last Login'),
            'modified_at' => Yii::t('app', 'Modified At'),
            'unit' => Yii::t('app', 'Unidad'),
            'number' => Yii::t('app', 'Asignado a la unidad'),
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

    public static function isUserAdmin($username)
    {
          if (static::findOne(['username' => $username, 'role' => self::ROLE_ADMIN])){

                return true;

          } else {

                 return false;
          }
    }

    public function getClient(){
             
        return $this->hasOne(Client::className(), ['client_id'  =>  'client_id']); 

    }

    public function getProvider(){
             
        return $this->hasOne(Provider::className(), ['provider_id' => 'provider_id']); 

    }


    public function validatePassword($password)
    {
        // Valida la clave contra el hash bcrypt guardado (igual que el sistema Frego).
        // Antes hasheaba la clave tecleada y la comparaba consigo misma => SIEMPRE true (bypass).
        return $this->security->validatePassword($password, $this->password);
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

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => 1 ]);
    }

    public static function findIdentity($id)
    {
        return static::findOne([ 'usr_id' => $id, 'status'=> 1 ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['auth_key' => $token]);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }

            // Solo re-hashea cuando llega una clave nueva en texto (password_field),
            // nunca sobre $this->password ya hasheado (evita el doble-hash).
            if(!empty($this->password_field)){
                $this->password = $this->security->generatePasswordHash($this->password_field);
            }
            
            return true;
        }
        return false;
    }

      public function afterLogin($event){

         $this->scenario =  'last_login';
         $this->last_login = gmdate("Y-m-d H:i:s");

          $this->save();
     }   



    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    public static function getList(){
        $query =  STATIC::find()->select(['usr_id', 'username'])->all();
        return !empty($query)? \yii\helpers\ArrayHelper::map($query, 'usr_id', 'username'): array();
    } 

}
