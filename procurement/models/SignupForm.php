<?php
namespace procurement\models;
use Yii;
use yii\base\Model;
use procurement\models\User;
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

    
    public $pwd;
    public $confirmpassword;
    public $FirstName;
    public $MiddleName;
    public $CompanyName;
    public $Cell;
    public $Email;
    public $KRA_PIN;
    public $captcha;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['captcha', 'captcha'],
            ['Email', 'trim'],
            ['Email', 'email'],
            ['Email', 'unique', 'targetClass' => '\procurement\models\User', 'message' => 'This email address has already been registered.'],
            
            ['CompanyName', 'unique', 'targetClass' => '\procurement\models\User', 'message' => 'A business with this name has already been registered.'],
            
            ['KRA_PIN', 'unique', 'targetClass' => '\procurement\models\User', 'message' => 'This PIN no. has already been registered.'],
            ['KRA_PIN', 'match', 'pattern' => '/^[A-Z]{1}\d{9}[A-Z]{1}$/'],

            [['FirstName','MiddleName','CompanyName','Cell','KRA_PIN', 'Email', 'confirmpassword', 'pwd'],'required'],
            [['FirstName','MiddleName','CompanyName', 'Email'],'string', 'max' => 150],
            [['Cell','KRA_PIN'],'string', 'max' => 20],
           
            ['pwd', 'string', 'min' => 6],
            ['confirmpassword','required'],
            ['confirmpassword','compare','compareAttribute'=>'pwd','message'=>'Passwords do not match, try again'],

        ];
    }

    public function attributeLabels()
    {
        return 
        [
            'pwd' => 'Password',
            'FirstName' => 'Contact First Name',
            'MiddleName' => 'Other Name(s)',
            'confirmpassword' => 'Confirm Password',   
            'Cell' => 'Telephone No.',     
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        //if (!$this->validate()) 
        //{
        //  return null;//$this->getErrors();
        //}
       //  print_r('<pre>');
       // print_r($this); exit;
        $user = new User();       
        $user->FirstName = $this->FirstName;
        $user->MiddleName = $this->MiddleName;
        $user->Cell = @$this->Cell.'';
        $user->Email = strtolower($this->Email);
        $user->KRA_PIN = $this->KRA_PIN;
        $user->password_reset_token = '';
        $user->status = 0;
        $user->DateCreated = date('Y-m-d H:i:s');
        $user->DateUpdated = date('Y-m-d H:i:s');
        $user->CompanyName = $this->CompanyName;
        $user->ProfilePhoto = '';
        $user->ApplicantId = '';
        $user->AppliedForReg = 0;
        $user->setPassword($this->pwd);
        $user->generateAuthKey();
        return $user->save() ? $user : null;
    }

    public function sendEmail(){
        /* @var $user User */
        $user = User::findOne([
          //  'status' => User::STATUS_ACTIVE,
            'Email' => strtolower($this->Email),
        ]);
        return Yii::$app
			->mailer
			->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
			)
			->setFrom([Yii::$app->params['supportEmail'] => 'FRC Supplier Portal'])
			->setTo($user->Email)
			->setSubject('FRC Supplier Portal - Account Verification')
			->send();
    }
}
        

