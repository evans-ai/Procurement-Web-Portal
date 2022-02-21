<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class BusinessExperience extends Model 
{    
    public $Key;
    public $No;
    public $BusinessID;
    public $ClientName;
    public $Address;
    public $Contact_Person;
    public $Telephone_Number;
    public $Contract_Value;
    public $Start_Date;
    public $End_Date;
    public $DocumentURL;
    public $WorkDone;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['No'], 'required'],

        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   
}
