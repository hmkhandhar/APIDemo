<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginFormUser extends Model
{
    
    public $email_id;
    public $password;
    public $rememberMe = false;    
    

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {

        return [
            // email_id and password are both required
            [['email_id'],'email'],
            [['email_id', 'password'], 'required'],
            ['password', 'validatePassword'],
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

            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError('user_id', 'Incorrect email id or password.');
            }
            //print_r($user); die;
        }
    }

    /**
     * Logs in a user using the provided email_id and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        
        if ($this->validate()) {
            //echo 'asd'; print_r($this->getUser()); die;
            return Yii::$app->user->login($this->getUser()); //, $this->rememberMe ? 3600*24*30 : 0
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[email_id]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false)
        {
            $this->_user = User::findByUsername($this->email_id);
        }
        
        return $this->_user;
    }
}
