<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Attachment extends Model
{
    
public $Key;
public $Applicant_No;
public $Document_Description;
public $Document_Link;
public $Attached;
public $DocumentNo;
public $attachment;
public $appid;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['Applicant_No'], 'required'],
            [['attachment'], 'file'],
            
           
        ];
    }

   
}
