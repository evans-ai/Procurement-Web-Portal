<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class BusinessDirectors extends Model 
{
    public $Key;
    public $No;
    public $DirectorName;
    public $Nationality;
    public $Citizenship;
    public $Shares;
    public $ID_Passport;
    public $Relationship;
    public $KRA_PIN;
    public $Line_No;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['DirectorName', 'Nationality', 'ID_Passport', 'KRA_PIN'], 'required'],
            [['DirectorName', 'Key', 'No', 'Nationality', 'Citizenship', 'KRA_PIN', 'ID_Passport'], 'string'],
            [['Shares', 'Relationship', 'Line_No'], 'integer'],
            ['KRA_PIN', 'match', 'pattern' => '/^[A-Z]{1}\d{9}[A-Z]{1}$/'],
        ];
    }

    public function getRelationship(){
        $relArray = ['Not Indicated', 'Proprietor', 'Partner', 'Director', 'Shareholder'];
        return $relArray[(int)$this->Relationship];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   
}
