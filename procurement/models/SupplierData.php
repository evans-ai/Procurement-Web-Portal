<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class SupplierData extends \yii\db\ActiveRecord 
{
    public $Key;
    public $No;
    public $Name;
    public $Address;
    public $PlotNo;
    public $Street_Road;
    public $PostalAddress;
    public $Postal_Code;
    public $NatureOfBusiness;
    public $LicenseNo;
    public $LicenseExpiry;
    public $MaxBusinessValue;
    public $Banker;
    public $Branch;
    public $Bank_Account;   
    public $Business_Location;
    public $Form_Of_Business;

    public $SoleProprietor;
    public $Proprietor;
    public $ProprietorDOB;
    public $ProprietorNationality;
    public $ProprietorOrigin;
    public $ProprietorCitizedID;

    public $Partnership;
    public $Company;
    public $PublicCompany;
    public $NorminalCapital;
    public $IssuedCapital;
    public $UserID;
    public $SupplierType;
    public $Telephone;
    public $Email;
    public $KRA_PIN;
    public $NetWorth;
    public $UnderCurrentManagementSince;
    public $Founded_Incorporated;
    public $BondingCompany;
    public $OrganizationChart;
    public $OtherBranches;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['Name', 'Key', 'No', 'Address','PlotNo','Email','KRA_PIN','NetWorth','SupplierType','NatureOfBusiness','LicenseNo','Banker','Bank_Account'], 'string'],
            ['Email', 'email'],
            [['Name','Form_Of_Business','MaxBusinessValue','PostalAddress','Address','Postal_Code','Telephone',
            'Email','NatureOfBusiness', 'SupplierType','LicenseNo','KRA_PIN','Banker','Branch' => 'Branch',//no label
            'Bank_Account'], 'required'],
            
            ['KRA_PIN', 'match', 'pattern' => '/^[A-Z]{1}\d{9}[A-Z]{1}$/'],
            ['Telephone', 'match', 'pattern' => '/^[0]{1}\d{9}$/'],
        ];
    }

    public static function tableName()
    {
       return Yii::$app->params['CompanyName'].'$Supplier Data';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_auth');
    }

    public function attributeLabels()
    {
        return [
            'Name' => 'Business Name',
            'Form_Of_Business' => 'Form of Business Unit',
            'MaxBusinessValue' => 'Max. Business Value (KSh)',
            'PostalAddress' => 'Postal Address',
            'Address' => 'Physical Address',
            'Postal_Code' => 'Postal Code',
            'Telephone' => 'Telephone No.',
            'Email' => 'Email Address',
            'NatureOfBusiness' => 'Nature of Business',
            'SupplierType' => 'Supplier Type',
            'LicenseNo' => 'Business Registration No.',
            'KRA_PIN' => 'KRA PIN',
            'Banker' => 'Bank',
            'Branch' => 'Branch',//no label
            'Bank_Account'=>'Account No.',
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
