<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginProvider extends Model
{
    public $email;
    public $password_field;
    public $rememberMe = true;
    private $_provider = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // fullName and password are both required
            [['email', 'password_field'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password_field', 'validatePassword' ],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $client = $this->getProvider();
            if (!$client || !$client->validatePassword($this->password_field)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->provider->login($this->getProvider(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getProvider()
    {
        if ($this->_provider === false) {
            $this->_provider = Provider::findByEmail($this->email);
        }

        return $this->_provider;
    }
}
