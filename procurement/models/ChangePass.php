<?php
namespace procurement\models;

use Yii;
use yii\base\Model;
use app\models\Users;
use yii\base\InvalidParamException;
/**
 * Login form
 */
class ChangePass extends Model
{
    public $oldpassword;
    public $newpassword1;
    public $newpassword2;
    Public $UserID;

    public $_user;

    /**
     * @inheritdoc
     */

    public function rules()
    {
        $myRegExp= "'/^[\*a-zA-Z0-9]{6,14}$/'";
        return 
        [
            [['newpassword1','newpassword2'], 'required'],
            [['newpassword1','newpassword2'], 'string', 'min' => 6],
             ['newpassword2', 'compare', 'compareAttribute' => 'newpassword1'],                    
        ];
    }

    public function attributeLabels()
    {
        return [
            'newpassword1' => 'New Password',
            'newpassword2' => 'Confirm Password',
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
            $user = $this->_user;
            if (!$user || !$user->validatePassword($this->oldpassword)) {
                $this->addError($attribute, 'Incorrect Password.');
            }
        }
    }

    public function validateNewPassword($attribute, $params)
    {
        

        if ($this->newpassword1 != $this->$newpassword2)
        {
            $this->addError($attribute, 'Confirmation password Wrong.');
        }
    }


    public function changePassword()
    {
        $user = $this->_user;
        $user->setPassword($this->newpassword1);
 
        return $user->save(false);
    }


    
}
