<?php
/**
 * Created by PhpStorm.
 * User: Osoro
 * Date: 10/11/2019
 * Time: 12:13
 */

namespace backend\utilities;
use frontend\models\Documents;
use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\Runtime\ClientRuntimeContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Yii;
use yii\base\Model;

class Utilities
{
    public static function fetchdocument($id)
    {

        // $this->actionShrpnt_attach($target_file,$desc,$applicantno,$docno);
        $Url = Yii::$app->params['sharepointUrl'];//"http://rbadev-shrpnt";
        $username = Yii::$app->params['sharepointUsername'];//'rbadev\administrator';
        $password = Yii::$app->params['sharepointPassword']; //'rba123!!';

        $authCtx = new NetworkCredentialContext($username, $password);

        $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
        $ctx = new ClientContext($Url, $authCtx);

        /* 	print '<pre>';
            print_r($ctx); exit; */

        $model = Documents::findOne($id);
        if (!empty($model)) {
            return self::downloadFile($ctx, '/RBSS/' . $model->FileName);
            // $this->downloadFileAsStream($ctx, '/RBSS/' . $model->FileName, $model->FileName);
        } else {
            return '';
        }
    }

    private static function downloadFile(ClientRuntimeContext $ctx, $fileUrl)
    {
        try {
            $fileContent = \Office365\PHP\Client\SharePoint\File::openBinary($ctx, $fileUrl);
            return 'data:application/pdf;base64,' . base64_encode($fileContent);
        } catch (Exception $e) {
            return '';
        }
    }


    public static function generateRandomString($len = 10)
    {

        $min_lenght = 0;
        $max_lenght = 300;
        $bigL = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $smallL = "abcdefghijklmnopqrstuvwxyz0123456789_";
        $number = "0123456789_";

        $bigB = str_shuffle($bigL);
        $smallS = str_shuffle($smallL);
        $numberS = str_shuffle($number);

        $subA = substr($bigB, 0, 8);
        $subB = substr($bigB, 8, 8);
        $subC = substr($bigB, 16, 10);

        $subD = substr($smallS, 0, 10);
        $subE = substr($smallS, 10, 10);
        $subF = substr($smallS, 20, 20);
        $subG = substr($numberS, 0, 5);
        $subH = substr($numberS, 6, 5);
        $subI = substr($numberS, 10, 5);
        $RandCode1 = str_shuffle($subA . $subD . $subB . $subF . $subC . $subE . $subG . $subH . $subI);
        $RandCode1 .= str_shuffle($subA . $subD . $subB . $subF . $subC . $subE . $subG . $subH . $subI);
        $RandCode2 = str_shuffle($RandCode1);

        $RandCode = $RandCode1 . $RandCode2;
        if ($len > $min_lenght && $len < $max_lenght) {
             $CodeEX = substr($RandCode, 0, $len);
             return strtoupper($CodeEX);
        }
        return strtoupper($RandCode);

    }


}