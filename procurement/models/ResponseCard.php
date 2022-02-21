<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ResponseCard extends Model 
{
    public $Key;
    public $Response_Id;
    public $Tender_No;
    public $Supplier_ID;
    public $Status;
    //public $No_Series;
    public $Date_Openned;
    //public $Date_Submitted;
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
