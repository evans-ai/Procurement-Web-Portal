<?php

namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $url;

    //private $_user;
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['url','string'],
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
            //$user = $this->getUser();
            //print_r($params);
            //$user = User::findOne(['Email'=>$this->username]);
            print '<pre>'; print_r($user); exit;
            print 'pwd: '.Yii::$app->recruitment->hash($this->password);
            print 'hashed: '.$this->getPassword($this->username);
            exit;
            if(!$user){
                $this->addError($attribute, 'Incorrect Username : hint: registered email.');
            }
            

            if(Yii::$app->recruitment->hash($this->password) !== $this->getPassword($this->username)){
                $this->addError($attribute, 'Incorrect  password.');
            }
            if (!$user || (Yii::$app->recruitment->hash($this->password) !== ($this->getPassword($this->username)))) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {

        
        $session = \Yii::$app->session;
        $session->set('ini',\Yii::$app->request->referrer);
        //print_r($this->getUser());exit;
        if ($this->validate()) {
            //attempt login
            $temp = $this->getUser($this->username);
        /*print '<pre>';
        print_r($temp); exit;*/
            return Yii::$app->user->login($this->getUser($this->username), $this->rememberMe ? 3600 * 24 * 30 : 0);
            //return Yii::$app->user->login($this->getUser());
        } else {
            //print_r($this->getErrors()); exit;
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     * @return User|null
     * cannot be hacked, 2017 security
     */

    protected function getUser($user="")
    {
        if($user){
            $HRLogin = $this->loginHr(strtoupper($user));
        }
        else{
            $HRLogin = $this->loginHr(strtoupper($this->username));
        }
        
        //print '<pre>'; print_r($HRLogin); exit;
        $this->_user = false;
        
        if($HRLogin){

             $this->_user = User::findByUsername($this->username);
        }
           
                /*Yii::$app->session->set('detail', $this->encrypt($this->password));*/               
                 //print '<pre>'; print_r( $this->_user); exit;
        return $this->_user;
            
    }
       
    

protected function loginHr($username, $password=""){
     $user = User::findByUsername($username);
    
    if(is_object($user)){
        //print '<pre>'; print_r($user); exit;
        $this->_user = User::findByUsername($this->username);
        return true;
    }else{
        return 'wrong HR username/password';
    }
}
public function getPassword($username){
    return User::getPassword(strtoupper($username));
}
protected

function loginToAD($username, $password)
{
    /*if ($username !== 'FNjambi' || $username !== 'SNgugi' ){
            return false;
    }*/
    
    //exit($username);

    //$me=['ye'=>'ds'];//replace this hack for go live, this hack is for dev env only
    //return $me;//replace this hack for go live

    //$adServer = "ldap://ERC-SVRV7.erc.go.ke";
    //phpinfo(); exit;
    //$adServer = "KRB-SVR7.KRBHQS.GO.KE";
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
        //$result = ldap_search($ldap, "CN=KRBHQS,DC=GO, DC=KE", $filter);
        //setBaseDn('OU=IITAHUB,DC=CGIARAD,DC=ORG')
        $result = ldap_search($ldap, "OU=IITAHUB,DC=CGIARAD, DC=ORG", $filter);


        //$result = ldap_search($ldap,"dc=KRBHQS,dc=GO, DC=KE",$filter);
        // ldap_sort($ldap,$result,"sn");
        $info = ldap_get_entries($ldap, $result);
        //var_dump($info); exit;
        /*print '<pre>';
        print_r($info);exit;
           for ($i=0; $i<$info["count"]; $i++)
           {
               if($info['count'] > 1)
                   break;
               return $info[$i];
           }
exit;*/
        return $info;
        @ldap_close($ldap);
    } else {
        //notify incorrect login
        return 'wrong username/password';
    }
    return null;
}

    private
    function encrypt($detail)
    {
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $key = YII::$app->params['key'];
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($detail, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        return base64_encode($iv . $hmac . $ciphertext_raw);
    }
}
