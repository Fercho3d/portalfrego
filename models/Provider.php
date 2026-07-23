<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\base\Security;
use yii\web\IdentityInterface;

use Yii;

/**
 * This is the model class for table "provider".
 *
 * @property int $provider_id
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


class Provider extends \yii\db\ActiveRecord implements IdentityInterface
{
    private $security ;
    const SCENARIO_LOGIN = 'login';
    public $password_field;
    public $number;
    public $verification_code;


    /**
     * {@inheritdoc}
     */

        /**
     * All the set of functions previous to rules come form user.php and are deployed for the login
     */

    public static function primaryKey()
    {
        return ["provider_id"];
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    function __construct(){

        $this->security = new Security();
    }

    public static function tableName()
    {
        return 'provider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['phone', 'created_by', 'modified_by'], 'integer'],
            [['created_at', 'modified_at'], 'safe'],
            [['rfc', 'state', 'city', 'postal_code'], 'string', 'max' => 25],
            [['address'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['fullName'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'provider_id' => 'Provider ID',
            'rfc' => 'Rfc',
            'fullName' => 'Full Name',
            'address' => 'Address',
            'state' => 'State',
            'city' => 'City',
            'email' => 'Email',
            'postal_code' => 'Postal Code',
            'phone' => 'Phone',
            'created_by' => 'Cretated By',
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


   /* public static function find()
    {
        return new UserQuery(get_called_class());
    }
    */


    public static function getList($type = null ){
        $query =  STATIC::find()->select(['provider_id', 'fullName']);
        if($type !== null){
             $query->where(['type_id'=> $type] );
         }
        return !empty($query)? \yii\helpers\ArrayHelper::map($query->all(), 'provider_id', 'fullName'): array();
    }
    

}
