<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Qualification extends Model
{
    
    public $Key;
    public $Applicant_No;
    public $Education_Level_Id;
    public $Education_Level_Name;
    public $Course_Id;
    public $Course_Name;
    public $Grade_Id;
    public $Grade_Name;
    public $From_Date;
    public $To_Date;
    public $Institution_Company;
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
