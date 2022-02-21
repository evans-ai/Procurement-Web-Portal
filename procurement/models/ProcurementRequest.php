<?php
namespace procurement\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class ProcurementRequest extends \yii\db\ActiveRecord
{
   
    public static function tableName() {
        return Yii::$app->params['CompanyName'].'$Procurement Request';
    }

    public static function getDb()
    {        
        return Yii::$app->get('db_auth');
    }

   
}
