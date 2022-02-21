<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Personel extends Model 
{
    public $No;
    public $BusinessID;
    public $DirectorName;
    public $Nationality;
    public $Citizenship;
    public $Shares;
    public $Relationship;

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
