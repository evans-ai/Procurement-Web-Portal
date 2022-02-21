<?php

namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;
    public $captcha;
    public $url;

    //private $_user;
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'captcha'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
            ['url','string'],
            ['captcha', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Email or Mobile',
            'password' => 'Pasword',
            'captcha' => 'Verification Code',
            
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
        if (!$this->hasErrors()) 
        {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**_
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        if ($this->validate())
        {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }        
        return false;
    }

    protected function getUser()
    {
        //print_r($this->username); exit;
        if ($this->_user === null) 
        {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }

   
    

    protected function loginHr($username, $password=""){
         $user = User::findByUsername($username);
        
        if(is_object($user)){
            //print '<pre>'; print_r($user); exit;
            $this->_user = User::findByUsername($this->username);
            return true;
        }else{
            return 'wrong username/password';
        }
    }
    public function getPassword($username){
        return User::getPassword($username);
    }

    protected function loginToAD($username, $password)
    {
        $adServer = '172.30.1.37';
        $ldap = ldap_connect($adServer, 389);//connect
        $ldaprdn = \Yii::$app->params['ldPrefix'] . "\\" . strtoupper($username);//put the username in a way specific to the domain

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
        $bind = @ldap_bind($ldap, $ldaprdn, $password);

        /*print '<pre>';
        print_r($bind); exit;*/
        
        if ($bind) {
            $filter = "(sAMAccountName=$username)";

            $result = ldap_search($ldap, "OU=IITAHUB,DC=CGIARAD, DC=ORG", $filter);

            $info = ldap_get_entries($ldap, $result);
       
            return $info;
            @ldap_close($ldap);
        } else {
            //notify incorrect login
            return 'wrong username/password';
        }
        return null;
    }

    private function encrypt($detail)
    {
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $key = YII::$app->params['key'];
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($detail, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        return base64_encode($iv . $hmac . $ciphertext_raw);
    }
}
