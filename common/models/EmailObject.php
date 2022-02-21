<?php
/**
 * Created by PhpStorm.
 * User: Osoro
 * Date: 10/10/2019
 * Time: 18:58
 */

namespace common\models;

use Yii;
use yii\base\Model;

class EmailObject extends Model
{

    public $to = array();

    public $body;
    public $template = ['html' =>'template'];
    public $templateParams = array();
    public $subject;
    public $bcc = array();
    public $cc = array();
    /**
     * list of string path
     * ['uploads/schemes/report.pdf','files/images/image.png']
     */
    public $attachments = [];

    public $emailC;


}