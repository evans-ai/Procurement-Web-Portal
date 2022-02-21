<?php
namespace backend\library;
use yii;
use yii\base\Component;
use backend\models\Services;

class Navhelper extends Component{
	//read data-> pass filters as get params
	public function getData($service,$params=[]){
		$identity = \Yii::$app->user->identity;
        $username = is_object($identity)?$identity->Id:'rbadev\Francis.Mwangi';
        $password = is_object($identity)?$identity->Id:'Pass@123';
        

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        
        $url = new Services($service);
        //var_dump($url); exit;
        $soapWsdl=$url->getUrl();

        $filter = [];
            if(sizeof($params)){
                 foreach($params as $key => $value){
                $filter[] = ['Field' => $key, 'Criteria' =>$value];
            }
        }
       

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!
        $lv =(array)$results->ReadMultiple_Result;
        //return array of object
        return $lv[$service];
	}
	//create record(s)-----> post data
	public function postData($service,$data){
		$identity = \Yii::$app->user->identity;
        $username = is_object($identity)?$identity->Id:'rbadev\Francis.Mwangi';
        $password = is_object($identity)?$identity->Id:'Pass@123';
        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl=$url->getUrl();
        //print '<pre>';
//var_dump($url); exit;
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
        
       // $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $results = Yii::$app->navision->addEntry($creds, $soapWsdl,$entry, $entryID);

        if(is_object($results)){
            $lv =(array)$results;
        
            return $lv[$service];
        }
        else{
            return $results;
        }

        /*print '<pre>'; print_r($results); exit;
        $lv =(array)$results;
        
        return $lv[$service];*/
	}
	//update data   -->post data
	public    function updateData($service,$data){
		$identity = \Yii::$app->user->identity;
         $username = is_object($identity)?$identity->Id:'rbadev\Francis.Mwangi';
        $password = is_object($identity)?$identity->Id:'Pass@123';
        $post = Yii::$app->request->post();

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl=$url->getUrl();

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
        
       // $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $results = Yii::$app->navision->updateEntry($creds, $soapWsdl,$entry, $entryID);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!
         if(is_object($results)){
            $lv =(array)$results;
        
            return $lv[$service];
        }
        else{
            return $results;
        }
	}
	//purge data --> pass key as get param
	public function deleteData($service,$key){
		$identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
		$url = new Services($service);
		$creds = (object)[];
		$creds->UserName = $username;
		$creds->PassWord = $password;
		$soapWsdl = $url->getUrl();
		$result = Yii::$app->navision->deleteEntry($creds, $soapWsdl, $key);

		return $result;

	}
}


?>