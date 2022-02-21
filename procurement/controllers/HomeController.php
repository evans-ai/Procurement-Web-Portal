<?php

namespace procurement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use common\models\Services;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use common\models\LoginForm;
use procurement\models\ExpresionOfInterest;
use procurement\models\SupplierData;
use procurement\models\SearchModel;
use procurement\models\ProcurementRequest;


/**
 * SupplierdataController implements the CRUD actions for Supplierdata model.
 */
class HomeController extends Controller
{
    public function behaviors()
    {
        return 
        [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => 
                [
                    [
                        'actions' => ['login', 'error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'tordoc', 'help', 'index','applications','list','expired','prequalifications','view','viewitem','favourite','favourites','dashboard','tor-download','opened-rfq', 'apply','viewdoc','view_tender','viewref'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Supplierdata models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) 
        {
            return Yii::$app->getResponse()->redirect(Yii::$app->homeUrl);
        }

        return $this->render('landing_page');

        
    }
    public function actionDashboard()
    {
        if (Yii::$app->user->isGuest) 
        {
            return Yii::$app->getResponse()->redirect(Yii::$app->homeUrl);
        }
        if(!Yii::$app->user->identity->AppliedForReg){
            return $this->redirect(['supplierdata/updateprofile']);
        }
        
        $statistics=$this->getStatistices();
        //print_r($statistics); exit;
        return $this->render('dashboard',['statistics'=>$statistics,]);

        
    }

    private function getStatistices(){
        $Statistics=[
            'SupplierApplications'=>$this->GetSupplierApplications(),
            'ExpiredRfx' => $this->GetExpiredrfx(1),
            'OpenRfx' => $this->GetRfx(1),
		];        
        return $Statistics;        
    }
    public function actionFavourites($ref=''){  
        $SupplierID = Yii::$app->user->identity->ApplicantId;
        $Company = $this->companyName();
        $DBCompany = Yii::$app->params['CompanyName'];
        if($ref){
            //mark as favorite, test if already marked
            $service = Yii::$app->params['ServiceName']['SupplierFavorites'];
            $Filter = ['SupplierNo'=>$SupplierID, 'TenderNo' => $ref];            
            $FavRecord = Yii::$app->navhelper->getData($service,$Filter);

            if(!$FavRecord){
                //mark it as favorite, confirm if is allowed to view                
                $Sql = "SELECT RQ.*             			
                FROM [$DBCompany\$Procurement Request] RQ
                WHERE ( RQ.[Procument Method] in (SELECT Code from [$DBCompany\$Procurement Methods] where [Use Prequalified] = 0)
                or (
                    RQ.[Procument Method]  in (SELECT Code from [$DBCompany\$Procurement Methods] where [Use Prequalified] = 1)
                    and RQ.No in (SELECT [Reference No_] from [$DBCompany\$Supplier Selection] where [Supplier Name] = '".strtoupper($Company)."' and Invited = 1)
                ))AND RQ.No = '$ref'";
                $RfxList = ProcurementRequest::findBySql($Sql)->asArray()->all();
                if($RfxList){
                    $data = ['SupplierNo' => $SupplierID, 'TenderNo'  => $ref];
                    $result = Yii::$app->navhelper->postData($service ,$data);
                    
                    if($result){
                        Yii::$app->session->setFlash('success', "The record has been added to your favorites!");
                    }
                    else{
                        Yii::$app->session->setFlash('error', "The record could not be added to your favorites!");
                    }                    
                }
                else{
                    Yii::$app->session->setFlash('error', "This record has cannot been added to your favorites!");
                }
            }
            else{
                Yii::$app->session->setFlash('success', "The record has already been added to your favorites!");
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $Company = $this->companyName();
        $Sql = "SELECT RQ.*,
            CASE 
                WHEN (SELECT COUNT(*) from [$DBCompany\$Response Header] where [Supplier ID] = '$Company' and [Tender No] = RQ.No) > 0
                THEN 'Responded'
                ELSE 'Not Responded'
            END 
            as 'Response Status'			
        FROM [$DBCompany\$Procurement Request] RQ
        WHERE RQ.No in (SELECT TenderNo from [$DBCompany\$SupplierFavourites] where [SupplierNo] = '$SupplierID')";
        //echo $Sql; exit;
        $RfxList = ProcurementRequest::findBySql($Sql)->asArray()->all();
        $DataArray=[];
        $Count = 1;
        foreach($RfxList as $Rfx){            
            $DataArray[]= [  
                $Count++,           
                $Rfx['Document No'],
                $Rfx['Title'],
                date('d/m/Y h:i a', strtotime($Rfx['Closing Date Time'])),
                $Rfx['Response Status'],
                '<a href="/home/viewref?ref='.$Rfx['No'].'" class="btn btn-primary btn-sm">View RFX</a>'                      
            ];
        }
              
        return $this->render('index',[
            'Provider'=> json_encode($DataArray),
        ]);
    }
	
	public function GetSupplierApplications(){
        $DBCompany = Yii::$app->params['CompanyName'];
		$SQL = 'SELECT  PR.No, PR.Title, PR.[Document No], RH.Successfull, RH.[Date Submitted], PR.[Closing Date Time], PR.[Tender Opening Date]  
        FROM ['.$DBCompany.'$Response Header] RH
        INNER JOIN ['.$DBCompany.'$Procurement Request] PR ON PR.No = RH.[Tender No] 
        WHERE RH.[Supplier ID] = \''.$this->companyName().'\'';
        
        return ProcurementRequest::findBySql($SQL)->asArray()->all();
	}

    public function actionApplications(){
        $RfxList = $this->GetSupplierApplications();
        $DataArray = array();
        $Count = 1;
        foreach($RfxList as $Rfx){            
            $DataArray[] = array(
                $Count++,
                $Rfx['Document No'],
                $Rfx['Title'],
                date('d/m/Y', strtotime(@$Rfx['Date Submitted'])),
                date('d/m/Y h:i a', strtotime(@$Rfx['Closing Date Time'])),
                date('d/m/Y', strtotime(@$Rfx['Tender Opening Date'])),
                //date('d/m/Y', strtotime($Rfx['Date Submitted'])),
                $Rfx['Successfull'] ? 'Yes' : 'No',
                '<a href="/home/viewref?ref='.$Rfx['No'].'" class="">View RFX</a> | 
                <a href="/responses/view-response?ref='.$Rfx['No'].'" class="">View Response</a>'                
            );
        }

        return $this->render('applications',[
            'RfxList' => json_encode($DataArray)
        ]);
    }

    private function companyName(){
        $CompamyName = strtoupper(Yii::$app->user->identity->{'CompanyName'});
        return \str_replace("'", "''", $CompamyName);
    }
    /**
     * Creates a new Supplierdata model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {

        if (Yii::$app->user->isGuest) 
        {
            return $this->goHome();
        }

        $model=new SupplierData();

        //print_r($dataUrl); exit;

        if(Yii::$app->request->post())
        {
            $params=Yii::$app->request->post();
            $service = Yii::$app->params['ServiceName']['SupplierDataCard'];  
            $session = \Yii::$app->session;

            //print_r($params); exit; 
            
            if(Yii::$app->request->post('SupplierData') )
            {

                $key=$_POST['SupplierData']['Key'];
                $params=$_POST['SupplierData'];

                if($key=='')
                {                      
                    unset($params['No']);  //Remove the number Property 
                    $params['Partnership']=1;
                    $params['SoleProprietor']=0;
                    $params['Company']=0;                         
                    $result = Yii::$app->navhelper->postData($service,$_POST['SupplierData']);
                }else
                {                      
                    $result = Yii::$app->navhelper->updateData($service,$_POST['SupplierData']);
                }

                //print_r('<pre>');
                //print_r($params); exit;
                //print_r($result); exit;  

                if(is_object($result))
                {
                    Yii::$app->session->setFlash('success', " </b> Profile Updated successfully.");
                }else{
                    Yii::$app->session->setFlash('error', "Error Saving Information :".$result);
                }
                //print_r('<pre>');
                //print_r($params); exit;
                $id=$result->No;
                
                return $this->redirect(['profile','id'=>$id]);
            }            
        }

        return $this->render('_form',['model'=>$model,]);
    }

    /**
     * Displays a single Supplierdata model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id,$myService)
    {
         
        
        $filter = ['No'=>$id];

        switch ($myService) 
        {
            case 'dp':
                $service = Yii::$app->params['ServiceName']['DirectRFQ'];               
                $NavData = Yii::$app->navhelper->getData($service,$filter);               
                
                break;
            case 'rfq':
                $service = Yii::$app->params['ServiceName']['Quotation'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);
                
                break;
            case 'eoi':
                $service = Yii::$app->params['ServiceName']['RequestforEOI'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);
                
                break;
            case 'tender':
                $filter=['Response_Id'=>$id];
                
                return $this->redirect(['responses/viewapplicationitems','id'=>$id,'myService'=>'tender']);
                     
                break;
            case 'rfp':
                $service = Yii::$app->params['ServiceName']['RFPCard'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);  
                             
                break;
            case 'preq':
                $service = Yii::$app->params['ServiceName']['RFPCard'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);  
                          
                break;
            
            default:
                
                break;
        }

        // print('<pre>');
        // print_r($header); exit;
        
        $DataArray=[];
        $HeaderArray=[];

        foreach($NavData as $v)
        {            
            $DataArray[]= 
            [                
                'Key' => $v->Key, 
                'Title'=>$v->Title,
                'Requisition_No'=>$v->Requisition_No,                             
                'Description' => isset($v->Description)?$v->Description:'',
                'Closing_Date'=>isset($v->Closing_Date)?$v->Closing_Date:'',                 
            ];
        }

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);       

        return $this->render('view',[
            'Provider'=> $Provider,'id'=>$id,
        ]);
    }

    public function actionViewitem($id,$myService)
    {
        $filter = ['No'=>$id];

        switch ($myService) 
        {
            case 'dp':
                $service = Yii::$app->params['ServiceName']['DirectRFQ'];               
                $NavData = Yii::$app->navhelper->getData($service,$filter);               
                
                break;
            case 'rfq':
                $service = Yii::$app->params['ServiceName']['Quotation'];
                $Header = Yii::$app->navhelper->getData($service,$filter);

                $service = Yii::$app->params['ServiceName']['ProcurementRequestLines'];
                $NavData = Yii::$app->navhelper->getData($service,['Requisition_No'=>$id]);

                $service = Yii::$app->params['ServiceName']['QuotationEvaluationList'];
                $Requirements = Yii::$app->navhelper->getData($service,['RFP_No'=>$id]);

                //print '<pre>';print_r($Requirements); exit;

               $DataArray=[];

                foreach($NavData as $v)
                {            
                    $DataArray[]= [                
                        'Key' => $v->Key, 
                        'Description'=>isset($v->Description)?$v->Description:'',
                        'Quantity'=>isset($v->Quantity)?$v->Quantity:0,
                        'Unit_of_Measure'=>isset($v->Unit_of_Measure)?$v->Unit_of_Measure:'',
                                       
                    ];
                }

                $REGS=[];
                foreach($Requirements as $v)
                {            
                    $REGS[]= [                
                        'Key' => $v->Key, 
                        'Description'=>isset($v->Question)?$v->Question:'',
                        'Evaluation_Type'=>isset($v->Evaluation_Type)?$v->Evaluation_Type:0,
                        
                                       
                    ];
                }

                $Provider = new ArrayDataProvider([
                'allModels' => $DataArray,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => ['No', 'Title'],
                ],
                ]);


                $RequirementsProvider = new ArrayDataProvider([
                'allModels' => $REGS,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => ['No', 'Title'],
                ],
                ]);

                return $this->render('view_rfq',['data'=>(array)$NavData,
                    'Provider'=> $Provider,'id'=>$id, 'Header'=>$Header[0],'RequirementsProvider'=>$RequirementsProvider,'myService'=>$myService,
                ]);
                
                break;
            case 'eoi':
                $service = Yii::$app->params['ServiceName']['RequestforEOI'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);
                
                break;
            case 'tenders':
                $service = Yii::$app->params['ServiceName']['ApprovedTenderCard'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);
                // $lines=$Data[0]->Procurement_Request_Lines->Procurement_Request_Lines;                 
                break;
            case 'rfp':
                $service = Yii::$app->params['ServiceName']['RFPCard'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);  
                             
                break;
            case 'preq':
                $service = Yii::$app->params['ServiceName']['RFPCard'];
                $NavData = Yii::$app->navhelper->getData($service,$filter);  
                          
                break;
            
            default:
                
                break;
        }

        // print('<pre>');
        // print_r($header); exit;
        
        $DataArray=[];

        foreach($NavData as $v)
        {            
            $DataArray[]= [                
                'Key' => $v->Key, 
                'Title'=>isset($v->Title)?$v->Title:'',
                'Requisition_No'=>isset($v->Requisition_No)?$v->Requisition_No:'',                             
                'Description' => isset($v->Description)?$v->Description:'',
                'Closing_Date'=>isset($v->Closing_Date)?$v->Closing_Date:'',                
            ];
        }

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [   
            'attributes' => ['No', 'Title'],
        ],
        ]);  

        //print_r($id); exit;

        $Status=1;

        return $this->render('view_tender',['data'=>$DataArray,
            'Provider'=> $Provider,'id'=>$id,'Status'=>$Status,'myService'=>$myService,
        ]);
    }
    
public function actionFavourite($ref)
    {   



    }




    public function actionFavourite123($id,$myService)
    {
        $Supplier_ID=Yii::$app->user->identity->ApplicantId;
        $service = Yii::$app->params['ServiceName']['SupplierFavouritesCard']; 

        switch ($myService) 
        {
            case 'dp':
                $TenderType='DP'; 
                break;
            case 'rfq':
                $TenderType='RFX';                 
                
                break;
            case 'eoi':
                $TenderType='EOI';
                
                break;
            case 'tenders':
                $TenderType='Tender';                
                break;
            case 'rfX':
                $TenderType='RFX';                             
                break;
            case 'preq':
                $TenderType='PREQ';                           
                break;            
            default:
                
                break;
        }

        $Data=['SupplierNo'=>$Supplier_ID,'TenderNo'=>$id,'TenderType'=>$TenderType];

        //print_r($Data); exit;

        $result=Yii::$app->navhelper->postData($service,$Data);
// print_r('<pre>');
//         print_r($result); exit;
        if(is_object($result))
        {
            $msg="Item Added to your fevourites!";
            Yii::$app->getSession()->setFlash('success', $msg);
            return $this->redirect(Yii::$app->request->referrer);
        }else{
            $msg="Item Failed to Add to your fevourites! ".$result ;
            Yii::$app->getSession()->setFlash('error', $msg);
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionTordoc($ref){
        $service = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        $filter = array('No' => $ref);
        $navData=Yii::$app->navhelper->getData($service,$filter);
        echo '<pre>';
        print_r( $navData[0]);
    }


    public function actionViewref($ref)
    {
        $service = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        $filter = array('No' => $ref);
        $navData=Yii::$app->navhelper->getData($service,$filter);
        $service = Yii::$app->params['ServiceName']['TenderEvaluationList'];
        $RequirementsFilter = array('RFP_No' => $ref);

        $list=Yii::$app->navhelper->getData($service, $RequirementsFilter);
        $service = Yii::$app->params['ServiceName']['FinancialResponseLines'];
       
        $DataArray=[];
        $Requirements=[];
        $financedata=[];
        $ClosingDate = '';
        //print_r($navData); exit;
        foreach($navData as $v) {
            //print_r($v); exit;    
            $ClosingDate = @$v->Closing_Date_Time;
            $DataArray[]= [                
                'Key' => $v->Key, 
                'No'=> @$v->Document_No,
                'Title'=>isset($v->Title)?$v->Title:'',
                'Requisition_No'=>isset($v->Requisition_No)?$v->Requisition_No:0,
                'Procurement_Plan_No'=>isset($v->Procurement_Plan_No)?$v->Procurement_Plan_No:0,
                'Advert_Start_Date'=>isset($v->Advert_Start_Date)?$v->Advert_Start_Date:0,
                'Closing_Date'=>isset($v->Closing_Date_Time)?$v->Closing_Date_Time:'',
                'Creation_Date'=>isset($v->Creation_Date)?$v->Creation_Date:'',
                //'TOR_File_Name'=>@$v->TOR_File_Name, 
            ];
        }    
        $Technical = array();
        $Mandatory = array();        
        foreach($list as $v){            
            if(@$v->Evaluation_Stage == 'MANDATORY'){
                $Mandatory[] = [                    
                    'Key' => $v->Key, 
                    'Document_Type' => @$v->Document_Type,
                    'Response_Answer_Type'=>isset($v->Response_Answer_Type)?$v->Response_Answer_Type:'',
                    'RFP_No'=>isset($v->RFP_No)?$v->RFP_No:'',
                    'Question'=>isset($v->Question)?$v->Question:'',
                    'Document_Type_Description'=>isset($v->Document_Type_Description)?$v->Document_Type_Description:'',
                    'Mandatory' => @$v->Evaluation_Stage,
                ];
            }  
            if(@$v->Evaluation_Stage == 'TECHNICAL'){
                $Technical[] = [                    
                    'Key' => $v->Key,
                    'Document_Type' => @$v->Document_Type,
                    'Response_Answer_Type'=>isset($v->Response_Answer_Type)?$v->Response_Answer_Type:'',
                    'RFP_No'=>isset($v->RFP_No)?$v->RFP_No:'',
                    'Question'=>isset($v->Question)?$v->Question:'',
                    'Document_Type_Description'=>isset($v->Document_Type_Description)?$v->Document_Type_Description:'',
                    'Mandatory' => @$v->Evaluation_Stage,
                ];
            }            
        }
        $financedata = @$navData[0]->Procurement_Request_Lines->Procurement_Request_Lines;        
        $headerProvider = new ArrayDataProvider([
            'allModels' => $DataArray,
            'pagination' => ['pageSize' => 10],
            'sort' => [
                'attributes' => ['No', 'Title'],
            ],
        ]);
              
        return $this->render('view_rfx',[
            'DataArray'=>$DataArray[0], 'headerProvider'=> $headerProvider, 'Technical'=> $Technical,
            'financedata'=>$financedata, 'Mandatory' => $Mandatory, 'ClosingDate' => $ClosingDate,
        ]);
    }
	
	public function GetRfx($active){
        
            $today = date('Y-m-d');        
            $service = Yii::$app->params['ServiceName']['ApprovedTenderList'];
            $tenders = Yii::$app->navhelper->getData($service);;
            $TENDER=[];         
            $tempFilter = ['EOI', 'OT', 'RFP'];
            
            foreach($tenders as $v){
               // print_r($v); exit;
                if(@$v->EndDate < $today || @$v->StartDate > $today ) continue;
                if(in_array(@$v->Procument_Method, $tempFilter)) continue;       
                $TENDER[]= [                    
                    'Key' => $v->Key,
                    'No' => $v->No,
                    'Title' => isset($v->Title)?$v->Title:'',
                    //'Requisition_No' => $v->Requisition_No,                    
                    'StartDate'=>isset($v->Return_Date)?$v->Return_Date:'',                
                    'EndDate'=>isset($v->Closing_Date)?$v->Closing_Date:'',
                    'Status' => $v->Status,
                ];
            }
            
                      
            return $TENDER;
        }

        public function GetExpiredrfx($active){
        
            $today = date('Y-m-d');        
            $service = Yii::$app->params['ServiceName']['ApprovedTenderList'];
            $tenders = Yii::$app->navhelper->getData($service);;
            $TENDER=[];         
            $tempFilter = ['EOI', 'OT', 'RFP'];
            
            foreach($tenders as $v){
               // print_r($v); exit;
                if(@$v->EndDate > $today || @$v->StartDate > $today ) continue;
                if(in_array(@$v->Procument_Method, $tempFilter)) continue;       
                $TENDER[]= [                    
                    'Key' => $v->Key,
                    'No' => $v->No,
                    'Title' => isset($v->Title)?$v->Title:'',
                    //'Requisition_No' => $v->Requisition_No,                    
                    'StartDate'=>isset($v->Return_Date)?$v->Return_Date:'',                
                    'EndDate'=>isset($v->Closing_Date)?$v->Closing_Date:'',
                    'Status' => $v->Status,
                ];
            }
            
                      
            return $TENDER;
        }

      
        
    public function actionList($active = 1){
        $RfxList = $this->GetRfx($active);     
        //var_dump( $RfxList);exit; 
        $DataArray=[];
        $Count = 1;
        foreach($RfxList as $Rfx){            
            $DataArray[]= [  
                $Count++,           
                $Rfx['No'],
                $Rfx['Title'],
                date('d/m/Y h:i a', strtotime($Rfx['StartDate'])),
                $Rfx['Status'],
                '<a style="text-transform: none;" href="/home/viewref?ref='.$Rfx['No'].'" class="btn btn-primary btn-sm">View</a>
                <a style="text-transform: none;" href="/home/favourites?ref='.$Rfx['No'].'" class="btn btn-danger btn-sm">Favorite</a>
                '                      
            ];
        }                                    
        return $this->render('list', ['Provider'=> json_encode($DataArray), 'active' => $active]);
    }   
    public function actionExpired($active = 1){

        $RfxList = $this->GetExpiredrfx($active);     
       
        $DataArray=[];
        $Count = 1;
        foreach($RfxList as $Rfx){            
            $DataArray[]= [  
                $Count++,           
                $Rfx['No'],
                $Rfx['Title'],
                date('d/m/Y h:i a', strtotime($Rfx['StartDate'])),
                $Rfx['Status'],
                '<a style="text-transform: none;" href="/home/viewref?ref='.$Rfx['No'].'" class="btn btn-primary btn-sm">View</a>
                <a style="text-transform: none;" href="/home/favourites?ref='.$Rfx['No'].'" class="btn btn-danger btn-sm">Favorite</a>
                '                      
            ];
        }                                    
        return $this->render('expiredrfx', ['Provider'=> json_encode($DataArray), 'active' => $active]);
    }   
    private static function myRfqs()
    {

        $identity = Yii::$app->user->identity;
        $id=Yii::$app->user->identity->ApplicantId;

        //$id="PSUP0026";

        $filter=['Supplier_Id'=>$id,'Invited'=>1];

        $service = Yii::$app->params['ServiceName']['SupplierSelection'];
        $Selection = Yii::$app->navhelper->getData($service,$filter); 

        //print '<pre>'; print_r($Selection); exit;

        //QuotationPendingOpening

        
        
        $service = Yii::$app->params['ServiceName']['QuotationsVisibleOnPortal'];
        $rfq = Yii::$app->navhelper->getData($service); 

        //print '<pre>'; print_r($rfq); exit;

        $params=[];

        $DataArray=[];

        foreach($rfq as $v)
        { 
            $Selected=0;
            foreach ($Selection as $s ) 
            {
                //print_r($s); exit;
               if($s->Reference_No == $v->No){
                 $Selected=1;
                 $DataArray[] = $v;                 
               }
            }

            
        }
        
        //print '<pre>';
        // print_r($DataArray); exit;

        return $DataArray;
    }

    public function actionPrequalifications()
    {

        $service = Yii::$app->params['ServiceName']['PreqRequisitionLine'];
        $NavData = Yii::$app->navhelper->getData($service); 

        //print '<pre>'; print_r($NavData); exit;

        $params=[];

        $DataArray=[];

        $today = date('Y-m-d');
        foreach($NavData as $v)
        {
			if($v->EndDate < $today || $v->StartDate > $today ) continue;
            $params=[
				'Requisition_Header_No'=>$v->Requisition_No,
                'Fy'=>$v->Procurement_Plan,
                'Cat'=>isset($v->Category)?$v->Category:'',
            ];

            $idd=implode("|",$params);

            $DataArray[]= 
            [                
            'Key'=>$v->Key,
            'Category_Name'=>isset($v->Category_Name)?$v->Category_Name:'',
            'Description'=>isset($v->Description)?$v->Description:'', 
            'StartDate'=>isset($v->StartDate)?$v->StartDate:'',               
            'EndDate'=>isset($v->EndDate)?$v->EndDate:'',
            'Document_No'=>@$v->Requisition_No,
            'id'=>$idd,
            ];
        }

        

        // print_r($params); exit;

        // print '<pre>'; print_r($id); exit;

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);

       // $Params = http_build_query(array('id' => $id));
        // print '<pre>';
        // print_r($Params); exit;


        return $this->render('prequalifications', 
                [
                'Provider'=>$Provider,
            ]);


    }

    public function actionInvited()
    {

        $identity = Yii::$app->user->identity;
        $id=Yii::$app->user->identity->ApplicantId;

        $filter=['Supplier_Id'=>$id,'Invited'=>1];

        $service = Yii::$app->params['ServiceName']['SupplierSelection'];
        $Selection = Yii::$app->navhelper->getData($service,$filter); 

        //print '<pre>'; print_r($Selection); exit;

        //QuotationPendingOpening

        $service = Yii::$app->params['ServiceName']['QuotationPendingOpening'];
        $rfq = Yii::$app->navhelper->getData($service); 

        //print '<pre>'; print_r($rfq); exit;

        $params=[];

        $DataArray=[];

        foreach($rfq as $v)
        { 
            $Selected=0;
            foreach ($Selection as $s ) 
            {
                //print_r($s); exit;
               if($s->Reference_No != $v->No){
                 $Selected=1;
                 $DataArray[] = $v;
               }
            }
        }


        
        //print '<pre>';
        //print_r($DataArray); exit;

        //print '<pre>'; print_r($NavData); exit;

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);

        $Params = http_build_query(array('id' => $idd));
        // print '<pre>';
        // print_r($Params); exit;


        return $this->render('prequalified', 
                [
                'Provider'=>$Provider,'id'=>$Params
            ]);


    }

    public function actionPrequalified()
    {

        $identity = Yii::$app->user->identity;
        $id=Yii::$app->user->identity->ApplicantId;

        $filter=['Supplier_ID'=>$id];

        $service = Yii::$app->params['ServiceName']['PreQualifiedSuppliers'];
        $NavData = Yii::$app->navhelper->getData($service,$filter); 

        //print '<pre>'; print_r($NavData); exit;

        $params=[];

        $DataArray=[];

        foreach($NavData as $v)
        { 
            $params=[
                'ReqNo'=>$v->Ref_No,
                'Fy'=>$v->Fiscal_Year,
                'Cat'=>isset($v->Category)?$v->Category:'',
            ];

            $idd=implode("|",$params);

            $DataArray[]= 
            [                
            'Key'=>$v->Key,
            'Ref_No'=>$v->Ref_No,
            'Category_Name'=>isset($v->Category)?$v->Category:'', 
            'Fiscal_Year'=>isset($v->Fiscal_Year)?$v->Fiscal_Year:'',               
            'id'=>$idd,
            ];
        }

        

        //print_r($params); exit;

        //print '<pre>'; print_r($NavData); exit;

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);

        $Params = http_build_query(array('id' => $idd));
        // print '<pre>';
        // print_r($Params); exit;


        return $this->render('prequalified', 
                [
                'Provider'=>$Provider,'id'=>$Params
            ]);


    }

    public function actionTenders() {
        $service = Yii::$app->params['ServiceName']['ApprovedTenderList'];
        $NavData = Yii::$app->navhelper->getData($service); //Prequalifications

        // print('<pre>');
        // print_r($NavData); exit;    

         $DataArray=[];

        foreach($NavData as $v)
        {            
            $DataArray[]= 
            [
                'Key' => $v->Key,
                'No' => $v->No,
                'Title' => $v->Title,
                'Requisition_No' => $v->Requisition_No,
            ];
        }

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);


        return $this->render('tenders', 
                [
                'Provider'=>$Provider,
            ]);


    }

    public function actionViewtender($id)
    {
         
        
        $filter = ['No'=>$id];

        $service = Yii::$app->params['ServiceName']['ApprovedTenderCard'];
        $NavData = Yii::$app->navhelper->getData($service,$filter);

        //$lines=$Data[0]->Procurement_Request_Lines->Procurement_Request_Lines;                 
            
        
        // print('<pre>');
        // print_r($Data); exit;
        
        $DataArray=[];

        foreach($NavData as $v)
        {            
            $DataArray[]= [                
                'Key' => $v->Key, 
                'Title'=>$v->Title,
                'Requisition_No'=>$v->Requisition_No,                             
                'Description' => isset($v->Description)?$v->Description:'',
                'Closing_Date'=>isset($v->Closing_Date)?$v->Closing_Date:'',                
            ];
        }

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);  

        //print_r($id); exit;

        return $this->render('view_tender',['data'=>$DataArray,
            'Provider'=> $Provider,'id'=>$id,
        ]);

    }

    public function actionDirectprocurement()
    {

        $service = Yii::$app->params['ServiceName']['DirectProcurementList'];
        $NavData = Yii::$app->navhelper->getData($service); //Prequalifications

        $DataArray=[];

        foreach($NavData as $v)
        {            
            $DataArray[]= [
                
                'Key' => $v->Key,
                'No' => $v->No,
                'Title' => $v->Title,
                'Requisition_No' => $v->Requisition_No,
                'Creation_Date' => $v->Creation_Date,
            ];
        }

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);


        return $this->render('directprocurement', 
        [
            'Provider'=>$Provider,
        ]);
    }

    public function actionRfq()
    {

        $service = Yii::$app->params['ServiceName']['QuotationPendingOpening'];
        $NavData = Yii::$app->navhelper->getData($service); //Prequalifications

        $DataArray=[];

        foreach($NavData as $v)
        {            
            $DataArray[]= [
                
                'Key' => $v->Key,
                'No' => $v->No,
                'Title' => $v->Title,
                'Requisition_No' => $v->Requisition_No,
                'Creation_Date' => $v->Creation_Date,
            ];
        }

        $Provider = new ArrayDataProvider([
        'allModels' => $DataArray,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);


        return $this->render('directprocurement', 
        [
            'Provider'=>$Provider,'item'=>'rfq',
        ]);
    }



    /**
     * Displays a single Supplierdata model.
     * @param string $id
     * @return mixed
     */
    public function actionProfile($id='None')
    {

        $identity=Yii::$app->user->identity;
        if(empty($identity->ApplicantId)){
            $id='None';
        }else{
            $id=$identity->ApplicantId;
        }

        $filter = ['No'=>$id];

        $service = Yii::$app->params['ServiceName']['SupplierDataList'];
        $Data = Yii::$app->navhelper->getData($service,$filter);
        
        $myData=empty($Data)?[]:$Data[0];
        $profile=new SupplierData();


        $profile1=array_merge((array)$profile,(array)$Data);

        foreach ($myData as $name => $value) {
          $profile->$name = $value;
        }       

        return $this->render('view_profile',['model'=>$profile,]);
    }

    /**
     * Displays the directors of a company
     * @param string $id
     * @return mixed
     * @author Muya George <george.muya@attain-es.com>
     */
    public function actionDirectors($id)
    {
        $directors = BusinessDirectors::find()->where(['BusinessID' => $id])->asArray()->all();
        $model = $this->findModel($id);
        return $this->render('viewdirectors', [
            'model' => $model, 'directors' => $directors,
        ]);
    }

    /**
     * Displays the directors of a company
     * @param string $id
     * @return mixed
     * @author Muya George <george.muya@attain-es.com>
     */
    public function actionPartners($id)
    {
        $partners = BusinessPartners::find()->where(['BusinessID' => $id])->asArray()->all();
        $model = $this->findModel($id);
        return $this->render('viewpartners', [
            'model' => $model, 'directors' => $partners,
        ]);
    }

    /**
     * Display business supervision information
     * @param string $id the business ID
     * @return mixed
     * @author Muya George <george.muya@attain-es.com>
     */
    public function actionSupervision($id)
    {
        //SupervisorCertificates
        $supervisors = BusinessSupervisory::find()->where(['BusinessID' => $id])->asArray()->all();
        $model = $this->findModel($id);
        return $this->render('supervisors', [
            'model' => $model, 'supervisors' => $supervisors,
        ]);
    }

    /**
     * Allos creation of business supervision information
     * @param string $id the business ID
     * @return mixed
     * @author Muya George <george.muya@attain-es.com>
     */
    public function actionAddsupervisors($id)
    {        
        return $this->render('create-supervisors', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Display business financial Position
     * @param string $id the business ID
     * @return mixed
     * @author Muya George <george.muya@attain-es.com>
     */
    public function actionFinancials($id)
    {
        //SupervisorCertificates        \
        return $this->render('financials', [
            'model' => $model, 'financials' => $financials,
        ]);
    }

    

    /**
     * Updates an existing Supplierdata model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        return $this->render('_form',['model'=>$model,]);
    }

    public function actionUpdateprofile($id='None')
    {  

        $filter = ['No'=>$id];
        $service = Yii::$app->params['ServiceName']['SupplierDataCard'];

        $service = Yii::$app->params['ServiceName']['SupplierDataList'];
        $Data = Yii::$app->navhelper->getData($service,$filter);

        //print_r($Data); exit;

        $myData=empty($Data)?[]:$Data[0];
        $profile=new SupplierData();
        foreach ($myData as $name => $value) {
          $profile->$name = $value;
        }       

        return $this->render('profile',['model'=>$profile]); 
    }

    /**
     * Deletes an existing Supplierdata model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed


     */
    public function actionSearchModel($params){
      $searchModel = new DocumentsSearch();
      $provider = $searchModel->search(Yii::$app->request->queryParams);

    return $this->render('financialquotes', [
        'provider' => $provider,
        'SearchModel' => $SearchModel
    ]);

     }
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Allows one to upload files to their profile
     * @author Muya George <george.muya@attain-es.com>
     * @param string $id Profile ID, also the business ID
     * @return mixed
     */
    public function actionDocuments($id)
    {        
        //print_r($Documents); exit;
        return $this->render('documents',['model' => $Profile, 'ApprovedApplications' => $ApprovedApplications, 'requiredDocs' => $required, 'myDocs' => $Documents, 'id'=>$id]);
    }

    /**
     * Finds the Supplierdata model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Supplierdata the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletedocument($id, $docid){

        $MyDocuments = SupplierDocuments::find()->where(['Supplier' => $id, 'No_'=>$docid])->one();
        $MyDocuments->Deleted = 1;
        $MyDocuments->save();

        return $this->redirect(['documents', 'id' => $id]);

    }

    protected function findModel($id)
    {
        if (($model = SupplierData::findOne($id)) !== null) 
        {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    private function uploadOrganizationChart($File){
        $UploadsDIR = Yii::$app->params['UploadsDirectory'];
        $newName = Yii::$app->user->identity->CompanyID.'-'.time().'-'.$File['name']['OrganizationChart'];
        $fileName = $UploadsDIR.$newName;
        if(move_uploaded_file($File['tmp_name']['OrganizationChart'],$fileName)){
            return $newName;
        }
        return " ";
    }

    public function actionTorDownload($ref){        
        $filter=['No' => $ref];
        $service = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        $RFX = Yii::$app->navhelper->getData($service, $filter);
        if($RFX){
            $RFX = $RFX[0];
            $Path = @$RFX->TOR_File_Name;
           //echo $Path; exit;
           $Path = str_replace("\\\\APP-SVR-TRN\\", "C:\\", $Path);
            if($Path){
                //update to dowload file from SharePoint, once nav is fully integrated
                return \Yii::$app->response->sendFile($Path,'Terms of Reference.pdf',['inline' => false]);
            }
            throw new \yii\web\HttpException(404, 'File not found.');
        }
        throw new \yii\web\HttpException(417, 'Invalid reference number.');        
    }

    private static function getSupplierName($id)
    {

        $service = Yii::$app->params['ServiceName']['SupplierDataList'];
        $Header = Yii::$app->navhelper->getData($service,['No'=>$id]);
        return isset($Header[0])?$Header[0]->Name:'';
    }

    public function actionOpenedRfq()
    {

        $service = Yii::$app->params['ServiceName']['ResponseList'];
        $Header = Yii::$app->navhelper->getData($service);

        $filter=['Opened'=>1,'Tender_No'=>'RFQ-121'];
        $service = Yii::$app->params['ServiceName']['FinancialResponseLines'];
        $Finance = Yii::$app->navhelper->getData($service,$filter);


          $myArray=array_merge($Header);
        // $searchModel = new DocumentsSearch();


        // print_r('<pre>');
       // print_r($Header); exit;

        $PREQ[]= [];
        foreach($Finance as $finance)
        {
            foreach($Header as $header)
            {
                if($finance->Tender_No==@$header->Tender_No)
                { 
                    $PREQ[]= 
                    [                
                        'Key'=>$finance->Key,
                        'Tender_No'=>@$finance->Tender_No,
                        'Description'=>@$finance->Description,
                        'Quantity'=>@$finance->Quantity,                                       
                        'Amount'=>@$finance->Amount,
                        'Unit_Price'=>@$finance->Unit_Price,
                        'Supplier_ID'=>@$header->Supplier_ID,
                        'Supplier_Name'=>@$header->Supplier_Name,
                        'Supplier_Name'=>$this->getSupplierName(@$header->Supplier_Name),
                        ]; 
                }
            }    
            
        }


       $Provider = new ArrayDataProvider([
        'allModels' => $PREQ,
        'pagination' => [
            'pageSize' => 10,
        ],
        'sort' => [
            'attributes' => ['No', 'Title'],
        ],
        ]);
        
       
        $SearchModel = '';

        return $this->render('financialquotes', 
        [
            'Provider'=>$Provider,
            'SearchModel' => $SearchModel,

           // 'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHelp(){
        return $this->render('help');
    }

}
