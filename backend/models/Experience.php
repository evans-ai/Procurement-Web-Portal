<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Experience extends Model
{
    
    public $Key;
    public $Applicant_No;
    public $From_Date;
    public $To_Date;
    public $Responsibility;
    public $Institution_Company;
    public $Salary;
    public $appid;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['Applicant_No'], 'required'],
            
           
        ];
    }

   
}
