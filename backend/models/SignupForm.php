<?php
namespace backend\models;

use Yii;
use yii\base\Model;
use backend\models\User;
//use yii\validators\CompareValidator;

/**
 * Signup form
 */
class SignupForm extends Model
{
    //public $username;
   // public $email;
    //public $password;
    //public $confirm_password;

    public $forgotpassword;
    public $pwd;
    public $confirmpassword;
    public $FirstName;
    public $MiddleName;
    public $LastName;
    public $Gender;
    public $Phone;
    public $Cell;
    public $Extension;
    public $Email;
    public $RecoveryPassword;
    
 

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['Email', 'trim'],
            ['Email', 'required'],
           // ['Email', 'unique', 'targetClass' => '\backend\models\User', 'message' => 'This username has already been taken.'],
            ['Email', 'string', 'min' => 2, 'max' => 255],
            [['FirstName','MiddleName','LastName'],'string','max'=>45],
            ['Gender','string'],
            ['Cell','string'],
           

            ['pwd', 'required'],
            ['pwd', 'string', 'min' => 6],
           

            ['confirmpassword','required'],
            ['confirmpassword','compare','compareAttribute'=>'pwd','message'=>'Passwords do not match, try again'],

        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        //$user->username = $this->Email;
        $user->FirstName = $this->FirstName;
        $user->MiddleName = $this->MiddleName;
        $user->LastName = $this->LastName;
        $user->Gender = $this->Gender;
        $user->Cell = $this->Cell;
        

        $user->Email = $this->Email;
        $user->setPassword($this->pwd);
        $user->generateAuthKey();


        {

        }
        
        // if ($user->save() &&  $this->sendEmail($user)){
        if ($user->save()){

            return $user;
        }
        return null;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->Email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
