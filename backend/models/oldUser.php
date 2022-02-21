<?php

namespace backend\models;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use backend\models\User;

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
        //return Yii::$app->params['CompanyName'].'$HRUser';
		return 'RBATraining$HRUser ';
    }




     public static function findByUsername($username,$password="")
    {

        return static::findOne(['Email' => strtoupper($username)]);
    }


      public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

     public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

     public static function findByPasswordResetToken($token)
    {
        ///exit($token);
         //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            //'status' => self::STATUS_ACTIVE,
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

    public static function getPassword($username){
        if(isset($username)){
            $model = Self::find()->where(['[Email]'=>$username])->one();
            //print_r($model->Password); exit;
            if(is_object($model)){
                return $model->Password;
            }else{
                return null;
            }
        }
        
        
    }

      /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
       // throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        //exit($token);

        return $timestamp + $expire >= time();
    }

     public function getAuthKey()
    {        
        
        return $this->Password;
    }

    public function validateAuthKey($authKey)
    {
		return true;
         //throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
        //return $this->getAuthKey() === $authKey;
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }


    /********Added methods for pwd reset purposes************/

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        $this->Password = md5($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
   

    /**
     * @inheritdoc
     */
    
}
