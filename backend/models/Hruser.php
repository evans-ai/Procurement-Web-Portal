<?php
namespace backend\models;

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
class Hruser extends \yii\db\ActiveRecord
{
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
     * @inheritdoc
     */
    public static function tableName()
    {
       //return Yii::$app->params['CompanyName'].'$HRUser ';
        return 'HRUser';
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
            [['FirstName', 'MiddleName', 'LastName', 'Gender', 'Phone', 'Cell', 'Extension', 'Email', 'RecoveryPassword','pwd','ApplicantId'], 'string'],
             [['FirstName', 'MiddleName', 'LastName', 'Gender','Cell','pwd','confirmpassword'], 'required'],
            ['pwd', 'string', 'min' => 6],
            ['Cell', 'integer'],
            [
                'confirmpassword','compare','compareAttribute' => 'pwd',
                'message' => "Passwords do not match.",
            ],
           // ['confirmpassword','confirm'],
            ['Email', 'email'],
            //[['DateCreated', 'DateUpdated'], 'safe'],
        ];
    }






    public function confirm($attribute, $params)
    {
       
          
            if($this->pwd !== $this->confirmpassword){
                $this->addError($attribute, 'Passwords do not match.');
            }
            

            
        
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'FirstName' => 'First Name',
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

    
    public function Passwordtest($attribute, $params)
    {
        if (!$this->hasErrors()) {
            
            if ($this->Password !== $this->confirmpassword) {
                $this->addError($attribute, 'Password field needs to Match Confirm Password Field.');
            }
        }
    }
}
