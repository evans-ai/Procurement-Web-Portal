<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Referee extends Model
{
    
    public $Key;
public $No;
public $Names;
public $Designation;
public $Company;
public $Address;
public $Telephone_No;
public $E_Mail;
public $Notes;
public $appid;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['No'], 'required'],
            
            
           
        ];
    }

   
}
