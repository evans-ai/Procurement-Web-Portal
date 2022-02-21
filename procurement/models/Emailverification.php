<?php
namespace procurement\models;

use Yii;
use yii\base\Model;
use procurement\models\User;

/**
 * Password reset request form
 */
class Emailverification extends Model
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
    public function mailsend()
    {
        /* @var $user User */
        $user = User::findOne([
            //'status' => User::STATUS_ACTIVE,
            'Email' => strtoupper($this->Email),
        ]);

        //print_r($user); exit;

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


        $fullname = $user->FirstName. ' ' . $user->MiddleName;
                
        $message = "
            <p> Hi $fullname </p>
            <p> Use this Code (".$user->password_reset_token.") to complete resetting  your password. </p>";

        //exit($message);

        $to      = $user->Email;
        $subject = 'RBA Portal Account Password Reset';
        $message = $message;

        $receipients = array();
        $receipients [] = [ 'Email' => $to, 'Name' => $fullname];
        
        if( mailsend( $receipients, $subject,  $message ) ) 
        {
            return true;
        }
    }


}
