<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class  Submit extends Model 
{
    public $Key;
    public $Response_Id;
    public $Tender_No;
    public $Supplier_ID;
    public $Status;
    public $Document_No;
    public $Unit_Price;

    
    /**
     * {@inheritdoc}
     */
public function rules()

    {
        return [
        [['Unit_Price','Document_No','message'], 'required'],
           ['Document_No', 'Document_No'],
           [['Document_No'],'string', ''],
           [['Unit_Price'], 'string', ''],
           [['message'], 'string', ''],
          
       ];
    }

   public static function tableName()
    {
        return Yii::$app->params[''].'$Document_No ';
    }
   

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   
}
