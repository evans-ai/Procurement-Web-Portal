<?php
namespace procurement\models;
use Yii;
use yii\base\Model;
use procurement\models\User;
use procurement\models\resetpasswordform;
use procurement\models\PasswordResetRequestForm;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $Email;
    public $password_reset_token;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['Email', 'trim'],
            ['Email', 'required'],
            ['Email', 'email'],
            ['password_reset_token','trim']
            /*['Email', 'exist',
                'targetClass' => '\backend\models\User',
                //'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],*/
        ];
    }

    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return bool whether the email was send
     */
    public function sendEmail()
    {
        /* @var $user User */
        $user = User::findOne([
          //'status' => User::STATUS_ACTIVE,
            'Email' => strtoupper($this->Email),
        ]);

        

        if (!$user) {
            //try with lower case
            $user = User::findOne([                   
                'Email' => $this->Email,
            ]);
            if (!$user) {
                return false;
            }            
        }
        
        $user->generatePasswordResetToken();
        if (!$user->save()) 
        {
            return false;
        } 

        return Yii::$app
			->mailer
			->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
			)
			->setFrom([Yii::$app->params['supportEmail'] => 'FRC Supplier Portal'])
			->setTo($user->Email)
			->setSubject('FRC Portal Account Password Reset')
			->send();
    }


}
