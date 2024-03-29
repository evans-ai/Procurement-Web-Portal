<?php
namespace backend\models;

use yii\base\Model;
use yii\base\InvalidParamException;
use backend\models\User;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model
{
    public $password;
    public $token;
    public $passwordconfirm;

    /**
     * @var \common\models\User
     */
    private $_user;


    /**
     * Creates a form model given a token.
     *
     * @param string $token
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($token="", $config = [])
    {
        if($token){
            if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Password reset token cannot be blank.');
        }
        //exit('valid'.$token);
        $this->_user = User::findByPasswordResetToken($token);

       /* print '<pre>';
        print_r($this->_user); exit;*/
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token.');
        }
        parent::__construct($config);
        }
        
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            [
                'passwordconfirm','compare','compareAttribute' => 'password',
                'message' => "Passwords do not match.",
            ]
        ];
    }

    /**
     * Resets password.
     *
     * @return bool if password was reset.
     */
    public function resetPassword()
    {
        $user = $this->_user;
        $user->setPassword($this->password);
        //$user->removePasswordResetToken();

        return $user->save();
    }
}
