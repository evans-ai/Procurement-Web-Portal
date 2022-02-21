<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class PrequalificationResponse extends Model 
{    
    public $Key;
    public $ApplicationID;
    public $SupplierID;
    public $CategoryID;
    public $DateOfApplication;
    public $Status;
    public $Comments;
    public $Approved;
    public $Submitted;
    public $Resubmitted;
    public $Items;
    public $Applicant;
    public $Advantage;
    public $ResubmissionDate;
    public $Tender_No;
    public $Description;
    public $Quantity;
    public $Amount;
    public $Unit_Price;
    public $Supplier_ID;
//   /  public $Key;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            //[['No'], 'required'],
             [['Tender_No'], 'SupplierName'],


        ];
    }

    public function search($params)
    {
        $query = Documents::find()
            ->joinWith(['Tender_No', 'Supplier_Name']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
            'pagesize' => 30,
        ],
    ]);

    $dataProvider->sort->attributes['Tender_No'] = [
        'asc' => ['Tender_No' => SORT_ASC],
        'desc' => ['user.name' => SORT_DESC],
    ];

    $dataProvider->sort->attributes['SupplierName'] = [
        'asc' => ['SupplierName' => SORT_ASC],
        'desc' => ['SupplierName' => SORT_DESC],
    ];


    if (!($this->load($params) && $this->validate())) {
        return $dataProvider;
    }


    $query->andWhere('SupplierName LIKE "%' . $this->Tender_No . '%" ');

    $query->andWhere('SupplierName LIKE "%' . $this->Supplier_Name . '%" ');


    return $dataProvider;

    }

}


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   

