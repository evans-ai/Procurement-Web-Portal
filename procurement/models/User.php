<?php

namespace procurement\models;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use procurement\models\User;

use Yii;
/**
 * This is the model class for table "external".
 *
 * @property int $id
 * @property string $FirstName
 * @property string $MiddleName
 * @property string $LastName
 * @property string $Gender
 * @property string $Phone
 * @property string $Cell
 * @property string $Extension
 * @property string $Password
 * @property string $Email
 * @property string $RecoveryPassword
 * @property string $DateCreated
 * @property string $DateUpdated
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 7;
    const STATUS_INACTIVE = 0; // Should be 1
    const STATUS_ACTIVE = 1;

    public $forgotpassword;
    public $pwd;
    public $confirmpassword;

    /**
     * {@inheritdoc}
     */

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'SupplyUser';
    }

    public static function findByUsername($username,$password="")
    {
       //exit($username);
        return static::findOne(['Email' => $username]);
    }

    public static function findByPin($pin,$password="")
    {
       //exit($username);
        return static::findOne(['KRA_PIN' => $pin]);
    }
    
    public static function findIdentity($id)
    {
       return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');

    }
    public function setPassword($password)
    {
        $this->Password = Yii::$app->security->generatePasswordHash($password);
    }

    public static function findByPasswordResetToken($token) {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

     public function getId()
    {
        return (int)$this->{'id'}; 
    }

    public function getTimestamp(){
         throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return (int)$this->{'timestamp'}; 
    }

    public function getPass()
    {
        return $this->decrypt(Yii::$app->session->get('detail'));

    }

    
      /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '180000') + 6);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        //exit($token);
        //echo "$timestamp <<<>>> $expire <<<>>>> ".time();exit;

        return $timestamp + $expire >= time();
    }

    public function getAuthKey()
    {
        return $this->Password;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function validateAuthKey($authKey)
    {
        //exit($authKey);
        //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {

      if(is_null($this->Password)) 
            return false;
          //exit('validatePassword');
      return Yii::$app->security->validatePassword($password, $this->Password);
    }
   

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '180000' . time();
    }

    /**
     * Removes password reset token

     */

    public function generateLoginToken()
    {
      $this->password_reset_token = mt_rand(100000, 999999);
      return $this->password_reset_token;
        //$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removePasswordResetToken()
    {
        $this->password_reset_token = '';
    }


    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {        
        return Yii::$app->get('db_auth');
    }

    /**
     * @inheritdoc

     */
   

    /**
     * @inheritdoc
     */
    
}
