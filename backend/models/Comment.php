<?php
namespace backend\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class Comment extends Model
{
    
public $Key;
public $Applicant_No;
public $Date;
public $Views_Comments;
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
