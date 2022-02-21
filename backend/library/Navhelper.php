<?php
/*
    Architected by joseph.ngugi@gmail.com
    Written by francnjamb@gmail.com

*/

namespace backend\library;

use yii;
use yii\base\Component;
use backend\models\Services;

//http://app-svr.rbss.com:7047/BC130/WS/RBA UAT/Page/Recruitment_Needs
class Navhelper extends Component
{
    //read data-> pass filters as get params
    public function getData($service, $params = [])
    {
        //exit;
        $identity = \Yii::$app->user->identity;
        $username = 'RBSS\Branton.Kiprono';
        $password = 'Pass@123';


        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        
        $url = new Services($service);
         
      
        $soapWsdl = $url->getUrl();

       
        $filter = [];
        if (sizeof($params)) {
            foreach ($params as $key => $value) {
                $filter[] = ['Field' => $key, 'Criteria' => $value];
            }
        }
//        print '<pre>';
//        print_r($url);
//        exit;

//        echo(Yii::$app->navision->isUp($soapWsdl, $creds));
//        exit;

        if (!Yii::$app->navision->isUp($soapWsdl, $creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
        //print_r($results);
       // exit();

        //add the filter so you don't display all loans to all and sundry.... just logic!!!

        //return array of object
        if (is_object($results->ReadMultiple_Result) && property_exists($results->ReadMultiple_Result, $service)) {
            $lv = (array)$results->ReadMultiple_Result;
            return $lv[$service];
        } else {
            return $results;
        }

    }

    //create record(s)-----> post data
    public function postData($service, $data)
    {
        $identity = \Yii::$app->user->identity;
        $username = 'RBSS\bcadmin';
        $password = 'Upgr@d320!9';
        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl = $url->getUrl();
        //print '<pre>';
//var_dump($url); exit;
        $entry = (object)[];
        $entryID = $service;
        foreach ($data as $key => $value) {
            if ($key !== '_csrf-backend') {
                $entry->$key = $value;
            }

        }
//exit('lll');
   //     var_dump(Yii::$app->navision->isUp($soapWsdl, $creds)); exit;



        if (!Yii::$app->navision->isUp($soapWsdl, $creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

     //   print_r($data);
     //   exit;
        // $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $results = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);

        if (is_object($results)) {
            $lv = (array)$results;

            return $lv[$service];
        } else {
            return $results;
        }

        /*print '<pre>'; print_r($results); exit;
        $lv =(array)$results;
        
        return $lv[$service];*/
    }

    //update data   -->post data
    public function updateData($service, $data)
    {
        $identity = \Yii::$app->user->identity;
        $username = 'RBSS\Francis.Mwangi';
        $password = 'Pass@123';
        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl = $url->getUrl();

        $entry = (object)[];
        $entryID = $service;
        foreach ($data as $key => $value) {
            if ($key !== '_csrf-backend') {
                $entry->$key = $value;
            }

        }


//        print '<pre>';
//        print(Yii::$app->navision->isUp($soapWsdl, $creds));
//        exit;

        if (!Yii::$app->navision->isUp($soapWsdl, $creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        // $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $results = Yii::$app->navision->updateEntry($creds, $soapWsdl, $entry, $entryID);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!
        if (is_object($results)) {
            $lv = (array)$results;

            return $lv[$service];
        } else {
            return $results;
        }
    }

    //purge data --> pass key as get param
    public function deleteData($service, $key)
    {
        $identity = \Yii::$app->user->identity;
        $username = 'RBSS\Francis.Mwangi';
        $password = 'Pass@123';
        $url = new Services($service);
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $soapWsdl = $url->getUrl();
        $result = Yii::$app->navision->deleteEntry($creds, $soapWsdl, $key);
        //just return the damn object
        return $result;

    }
}


?>