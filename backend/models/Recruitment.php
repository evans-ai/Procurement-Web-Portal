<?php
namespace backend\models;


use yii\base\Model;

/**
 * Login form
 */
class Recruitment extends Model
{
    /*public $username;
    public $password;
    public $rememberMe = true;

    private $_user;*/

       public $Key;
       public $appid;
        public $No;
        public $Applicant_Type;
        public $Employee_No;
        public $First_Name;
        public $Middle_Name;
        public $Last_Name;
        public $Initials;
        public $ID_Number;
        public $Gender; 
        public $Citizenship;
        public $Employ; 
        //public $Status;
        public $Country;
        public $Subcountry; 
        public $Marital_Status; 
        public $Ethnic_Origin;
        public $Disabled; 
        public $Disability_Details;
        public $Date_Of_Birth; 
        public $Age;
        public $Home_Phone_Number;
        public $Postal_Address;
        public $Postal_Address2;
        public $Postal_Address3;
        public $Post_Code;
        public $Residential_Address;
        public $Residential_Address2;
        public $Residential_Address3;
        public $Post_Code2;
        public $Cellular_Phone_Number;
        public $Work_Phone_Number;
        public $Ext;
        public $E_Mail;
        public $Fax_Number;
        public $Applicant_Qualification; 
        public $Applicant_Work_Experience; 
        public $Applicant_Referees; 
        public $Applicant_Documents; 
        public $Applicant_Comments_Views;
        public $Applicant_No;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['First_Name','Middle_Name','Last_Name','ID_Number','Gender','Citizenship','Marital_Status','Date_Of_Birth','Ethnic_Origin','Disabled','Home_Phone_Number','Mobile','E_Mail','Postal_Address','Post_Code','Residential_Address'], 'required'],
            
           
        ];
    }

     public function attributeLabels()
    {
        return [
            'Home_Phone_Number' => 'Phone Number',
            
        ];
    }

   
}
