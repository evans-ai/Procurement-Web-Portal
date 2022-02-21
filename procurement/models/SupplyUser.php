<?php
namespace procurement\models;

use Yii;
use yii\base\Model;
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
class SupplyUser extends \yii\db\ActiveRecord
{
    public $forgotpassword;
    public $pwd;
    public $confirmpassword;
    public $Key;    
    public $FirstName;
    public $MiddleName;
    public $LastName;
    public $Gender;
    public $Phone;
    public $Cell;
    public $Extension;
    public $Password;
    public $Company_Name;
    public $Email;
    public $RecoveryPassword;
    public $DateCreated;
    public $DateUpdated;
    public $id;
    public $ApplicantId;
    public $auth_key;
    public $RecoveryToken;
    public $ProfilePhoto;
    public $KRA_PIN;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
       return 'SupplyUser';
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
    public function rules()
    {
        return [
            [['FirstName', 'MiddleName', 'LastName', 'Gender', 'Phone', 'Cell', 'Extension', 'Password', 'Email', 'RecoveryPassword','Key','CompanyName'], 'string'],
             [['FirstName', 'MiddleName', 'LastName', 'Gender','Phone','Cell','Extension','Password','KRA_PIN'], 'required'],
              ['confirmpassword', 'Passwordtest'],
            ['Password', 'string', 'min' => 6],
            [
                'confirmpassword','compare','compareAttribute' => 'Password',
                'message' => "Passwords do not match.",
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'FirstName' => 'First Name',
            'CompanyName' => 'Company Name',
            'MiddleName' => 'Middle Name',
            'LastName' => 'Last Name',
            'Gender' => 'Gender',
            'Phone' => 'Phone',
            'Cell' => 'Cell',
            'Extension' => 'Extension',
            'Password' => 'Password',
            'Email' => 'Email',
            'RecoveryPassword' => 'Recovery Password',
            'DateCreated' => 'Date Created',
            'DateUpdated' => 'Date Updated',
            'forgotpassword'=>'',//no label
            'confirmpassword'=>'Confirm Password',
        ];
    }

    public function getCompanyName()
    {
        return $this['Company Name'];
    }
    
    public function Passwordtest($attribute, $params)
    {
        if (!$this->hasErrors()) 
        {
            
            if ($this->Password !== $this->confirmpassword) {
                $this->addError($attribute, 'Password field needs to Match Confirm Password Field.');
            }
        }
    }
    public function setPassword($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
      //return Yii::$app->security->validatePassword($password, $this->password);
      return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    public function generateAuthKey()
    {
        return Yii::$app->security->generateRandomString();
    }
}
