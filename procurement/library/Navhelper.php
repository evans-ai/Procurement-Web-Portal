<?php
/*
    Architected by joseph.ngugi@gmail.com
    Written by francnjamb@gmail.com

*/
namespace procurement\library;
use yii;
use yii\base\Component;
use procurement\models\Services;

class Navhelper extends Component{
    //read data-> pass filters as get params
    public function getData($service,$params=[]){
        $identity = \Yii::$app->user->identity;
    
        $username = Yii::$app->params['serviceUsername'];
        $password = Yii::$app->params['servicePassword'];
        
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        
        $url = new Services($service);
        //var_dump($url); exit;
        $soapWsdl=$url->getUrl();
        //var_dump($soapWsdl); exit;
        $filter = [];
            if(sizeof($params)){
                 foreach($params as $key => $value){
                $filter[] = ['Field' => $key, 'Criteria' =>$value];
            }
        }
        //var_dump($filter); exit;

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');
        }
        
        //print_r($soapWsdl); exit;
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        //print_r($results);exit;
        if(is_object($results->ReadMultiple_Result)){
            $lv =(array)$results->ReadMultiple_Result;
			//print_r($lv);exit;
            return (isset($lv[$service])) ? $lv[$service] : [];    
        } else{
            return $results;
        }
    }
    //create record(s)-----> post data
    public function postData($service,$data, $Codeunit = ''){
        $uname = Yii::$app->params['serviceUsername'];
        $password = Yii::$app->params['servicePassword'];

        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $uname;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl = $url->getUrl($Codeunit);
        //print '<pre>';
        //print_r($soapWsdl); exit;
        $entry = (object)[];
        $entryID = $service;
        foreach($data as $key => $value){
            if($key !=='_csrf-backend'){
                $entry->$key = $value;
            }
            
        }
        //print_r($soapWsdl); exit;
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        
       // $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $results = Yii::$app->navision->addEntry($creds, $soapWsdl,$entry, $entryID);

        //print_r($entry); exit;

        if(is_object($results)){
            $lv =(array)$results;
        
            return $lv[$service];
        }
        else{
            return $results;
        }
    }
    
    //create prequalification entries
    public function prequalification($service,$SupplierID, $Codeunit = 1){
        $uname = Yii::$app->params['serviceUsername'];
        $password = Yii::$app->params['servicePassword'];
        $post = Yii::$app->request->post();
        $creds = (object)[];
        $creds->UserName = $uname;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl = $url->getUrl($Codeunit);
        // $entry = (object)[];
        // $entryID = $service;
        // foreach($data as $key => $value){
        //     if($key !=='_csrf-backend'){
        //         $entry->$key = $value;
        //     }            
        // }
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');
        }        
        $results = Yii::$app->navision->addPrequalifications($creds, $soapWsdl,$SupplierID);
        if(is_object($results)){
            $lv =(array)$results;        
            return $lv;
        }
        else{
            return $results;
        }
    }

    //update data   -->post data
    public    function updateData($service,$data){
        
        $identity = \Yii::$app->user->identity;     

       $uname = Yii::$app->params['serviceUsername'];
        $pwd = Yii::$app->params['servicePassword'];

        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $uname;
        $creds->PassWord = $pwd;
        $url = new Services($service);
        $soapWsdl=$url->getUrl();
        //exit($soapWsdl);

        $entry = (object)[];
        
        $entryID = $service;
        
       
        foreach($data as $key => $value){
            if($key !=='_csrf-backend'){
                $entry->$key = $value;
            }
        }      

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        
        $results = Yii::$app->navision->updateEntry($creds, $soapWsdl,$entry, $entryID);
       
         if(is_object($results))
         {

            $lv =(array)$results;        
                return $lv[$service];
            }
        else
        {
            
            return $results;
        }
    }




    public function updateBatchData($service,$data){
        
        $identity = \Yii::$app->user->identity;     

       $uname = Yii::$app->params['serviceUsername'];
        $pwd = Yii::$app->params['servicePassword'];

        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $uname;
        $creds->PassWord = $pwd;
        $url = new Services($service);
        $soapWsdl=$url->getUrl();
        //exit($soapWsdl);

        $entry = (object)[];
        
        $entryID = $service;
        
       
        foreach($data as $key => $value){
            if($key !=='_csrf-backend'){
                $entry->$key = $value;
            }
            
        }

       

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        
        $results = Yii::$app->navision->updateMultiple($creds, $soapWsdl,$entry, $entryID);
       
         if(is_object($results))
         {

            $lv =(array)$results;        
                return $lv[$service];
            }
        else
        {
            
            return $results;
        }
    }



    //purge data --> pass key as get param
    public function deleteData($service,$key){
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->params['serviceUsername'];
        $password = Yii::$app->params['servicePassword'];
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