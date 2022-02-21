<?php
namespace procurement\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use procurement\models\PrequalificationResponse;
use procurement\models\ResponseCard;
use procurement\models\UploadFiles;

/**
 * Site controller
 */
class ResponsesController extends Controller
{
    public $layout='main';
   
    /**

     * {@inheritdoc}
     */
    public function behaviors()
    {
        return 
        [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => 
                [
                    [
                        'actions' => ['error', 'index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index','create','update','view','delete','no-quote',
                        'viewapplicationitems','apply','submit','submitpreq','viewprequalification',
                        'delete','view-file','viewdocs', 'view_tender','Viewref','response', 'view-response'],
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
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(){ 

       if (Yii::$app->user->isGuest) {
            return Yii::$app->getResponse()->redirect(Yii::$app->homeUrl);
        } 
        return $this->redirect(['/home/applications']);
    }


    public function actionViewapplicationitems($id){
        $service=Yii::$app->params['ServiceName']['ResponseList']; 
        // $filter=['Tender_No'=>$id];
        $header1=Yii::$app->navhelper->getData($service);
        $service=Yii::$app->params['ServiceName']['FinancialResponseLines']; 
        // $filter=['Tender_No'=>$id];
        $FinancialData=Yii::$app->navhelper->getData($service);
        
        foreach($header1 as $v) {      
            $DataArray[]= 
            [                
                'Response_Id' => $v->Response_Id,           
                'Status' => isset($v->Status)?$v->Status:'', 
                'Date_Submitted'=>isset($v->Date_Submitted)?$v->Date_Submitted:'', 
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

        return $this->render('tender_view',['Provider'=>$Provider,
            'DataArray'=>$DataArray,
            // 'FinancialData'=>(array)$FinancialData,'FinancialProvider'=>$FinancialProvider,
        ]);
    }

    public function actionResponse($id){        
        $responseService = Yii::$app->params['ServiceName']['ResponseCard'];
        //test if has already responded
        $RespondedFilter = [
            'Tender_No'  => $id,
            'Supplier_ID' => strtoupper(Yii::$app->user->identity->{'CompanyName'})
        ];
        $ResponseEntry= Yii::$app->navhelper->getdata($responseService ,$RespondedFilter);
        $TenderService = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        $filter = array('No' => $id);
        $TenderDetails = Yii::$app->navhelper->getData($TenderService, $filter);
        if(!$TenderDetails) throw new \yii\web\HttpException(412, 'Invalid reference number');
        
        if($ResponseEntry){
            //confiirm if supplier can still edit
            $service = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
            $filter = array('No' => $id);
            $TenderDetails = Yii::$app->navhelper->getData($service,$filter);
            if($TenderDetails){
                if(@$TenderDetails[0]->Closing_Date_Time < date('Y-m-d H:i:s')){ 
                    //redirect to view app
                    return $this->redirect(['view-response', 'ref' => $id]);
                }
            }
            else{
                throw new \yii\web\HttpException(412, 'Invalid reference number');
            }
            //edit only the first One
            $ResponseEntry =  $ResponseEntry[0];
        }
        else{
            if(@$TenderDetails[0]->Closing_Date_Time < date('Y-m-d H:i:s')) throw new \yii\web\HttpException(403, 'Responses to this tender are not being accepted at the moment');
            $data = [];
            $result = Yii::$app->navhelper->postData($responseService ,$data );   
            //print_r($result); exit;        
            $filter = [
                'Response_Id' =>@$result->Response_Id, 
                'Tender_No'  => $id,
                'Supplier_ID' => Yii::$app->user->identity->{'CompanyName'},
                'Vendor_ID' => Yii::$app->user->identity->ApplicantId,
                'Key' => @$result->Key
            ];
            $ResponseEntry = Yii::$app->navhelper->updateData($responseService ,$filter );
        }
        $FinancialResponse = @$ResponseEntry->Financial_Response_Lines->Financial_Response_Lines;
        $ResponseLines = @$ResponseEntry->Response_Lines->Response_Lines;  
        // $result3 =@$result2 ->Financial_Response_Lines->Financial_Response_Lines;int_r()
        if(!$ResponseLines) $ResponseLines = array();
        $DataArray=[];
        $DocumentTypesFilter = '';    
        foreach($ResponseLines as $Line){      
            if(@$Line->Requires_Attachment){
                if($DocumentTypesFilter && @$Line->Document_Type) $DocumentTypesFilter .= '|';
                $DocumentTypesFilter .= @$Line->Document_Type;            
            }
            $DataArray[] = [                           
                'Key' => $Line->Key, 
                'DocumentType' => @$Line->Document_Type, 
                'Requirement_ID'=>$Line->Requirement_ID,                
                'Tender_No' =>@$Line->Tender_No,
                'Question' =>@$Line->Question,
                'Document_No' =>@$Line->Document_No,
                'Requires_Attachment'=>@$Line->Requires_Attachment, 
                'Document_No_Mandatory'=>@$Line->Document_No_Mandatory, 
                'Document_Description'=>@$Line->Document_Type_Description, 
                'Description'=>@$Line->Description,              
                'Response_Answer_Type' => @$Line->Response_Answer_Type,
                'Response_Header_No'=>$Line->Response_Header_No,
                'ResponseType'=>$this->getAnswers(@$Line->Response_Answer_Type, @$Line->Description),
                'Financial_Proposal' => @$Line->Financial_Proposal,
                'Response' => @$Line->Response,
                'Evaluation_Guide' => @$Line->Evaluation_Guide
            ];
        }
        $FinancialData=[];
        if($FinancialResponse){
            foreach($FinancialResponse as $Response){            
                $FinancialData[] = [                           
                    'Key' => $Response->Key,              
                    'Response_Header_No' => @$Response->Response_Header_No, 
                    'Tender_No' => @$Response->Tender_No,
                    'Question' => @$Response->Question,
                    'Document_No' => @$Response->Document_No,
                    'Requires_Attachment' => @$Response->Requires_Attachment, 
                    'Description' => @$Response->Description, 
                    'Unit_of_Measure' => @$Response->Unit_of_Measure,            
                    'Quantity' => @$Response->Quantity,
                    'Unit_Price'=> @$Response->Unit_Price,
                    'Amount' => $Response->Amount,
                    'Financial_Proposal' => @$Response->Financial_Proposal,   
                ];
            }
        }
        
        //get the details of all the required documets
        $DocumentProperties = array();
        $DocumentTypesSvc = Yii::$app->params['ServiceName']['DocumentTypes'];
        $DocumentTypes = Yii::$app->navhelper->getData($DocumentTypesSvc, ['ID' => $DocumentTypesFilter]);
        foreach($DocumentTypes as $DocumentType){
            $DocumentProperties[$DocumentType->ID] = array(
                'expires' => @$DocumentType->Document_Expires,
                'number' => @$DocumentType->Requires_Document_Number,
                'name' => @$DocumentType->Document_Type
            );  
        }

        $SupDocsService = Yii::$app->params['ServiceName']['SupplierDocuments'];
        $ExistingFilter = array(
            'Supplier' => Yii::$app->user->identity->ApplicantId,
            'DocumentType' => $DocumentTypesFilter,
            'Deleted' => 0               
        );
        $ExistingDocuments =  Yii::$app->navhelper->getData($SupDocsService, $ExistingFilter);
        $ExistingArray = array();
        foreach($ExistingDocuments as $ExistingDocument){
            $DocumentProperties[$ExistingDocument->DocumentType]['exists'] = $ExistingDocument->No;               
        }
        
        return $this->render('tender_reqs',[
            'DataArray' => $DataArray,
            'FinancialData' => $FinancialData,
            'Tender' => @$TenderDetails[0],
            'DocumentProperties' => $DocumentProperties,
        ]);
    }

    public function actionViewResponse($ref){
        $responseService = Yii::$app->params['ServiceName']['ResponseCard'];
        //test if has already responded
        $RespondedFilter = [
            'Tender_No'  => $ref,
            'Supplier_ID' => strtoupper(Yii::$app->user->identity->{'CompanyName'})
        ];
        $Response = Yii::$app->navhelper->getdata($responseService ,$RespondedFilter);        
        if(!$Response) throw new \yii\web\HttpException(404, 'A page with this reference number could not be found');
        //confiirm if supplier can still edit_r()
        $TenderService = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        $filter = array('No' => $ref);
        $TenderDetails = Yii::$app->navhelper->getData($TenderService, $filter);
        if(!$TenderDetails) throw new \yii\web\HttpException(412, 'Invalid reference number');
        return $this->render('_application', ['Tender' => $TenderDetails[0], 'Response' => $Response[0]]);     
    }

    public function actionApply($id){
        $Supplier_ID=Yii::$app->user->identity->ApplicantId;   
        $Quotationservice = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        //QuotationsOnPortalCard 
        $data = [ 
            'No' => $id,//tender no
            //'Supplier_ID' => $Supplier_ID
        ];
        $result = Yii::$app->navhelper->getData($Quotationservice, $data );
        $responseline = Yii::$app->params['ServiceName']['ResponseLines'];
        $datalines = [               
            'Tender_No' => $id,
        ];
        $navData = Yii::$app->navhelper->getData($responseline, $datalines );
        $servicess=Yii::$app->params['ServiceName']['FinancialResponseLines']; 
        $data = [ 
            'Tender_No' => $id,//tender no
            // 'Response_Header_No' => $Response_Id 
        ];
        $FinancialData=Yii::$app->navhelper->postData($servicess,$data);
        $DataArray=[];
        foreach($result as $v){            
            $DataArray[] = [            
                'Key' => $v->Key, 
                'No'=>$v->No,                
                'Requirement_ID' =>@$v->Requirement_ID, 
                'Document_No'=>@$v->Document_No,              
                'Question' => @$v->Question,
                // 'Requires_Attachment'=>$v->Requires_Attachment,
                'ResponseType'=>$this->getAnswers(@$v->Response_Answer_Type, @$v->Description),
                'Response'=>isset($v->Response)?$v->Response:'',   
            ];
        }
        $Provider = new ArrayDataProvider([
            'allModels' => $DataArray,
            'pagination' => [
                'pageSize' => 1,
            ],
            'sort' => [
                'attributes' => ['No', 'Title'],
            ],
        ]);

        $FinancialProvider = new ArrayDataProvider([
            'allModels' => (array)$FinancialData,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => ['No', 'Title'],
            ],
        ]);
        
        return $this->render(
            'tender_reqs',[
            'DataArray'=>$DataArray,
            'FinancialData'=>(array)$FinancialData,
        ]);
    }
    
    public function actionNoQuote($ref){
        $responseService = Yii::$app->params['ServiceName']['ResponseCard'];
        $RespondedFilter = [
            'Tender_No'  => $ref,
            'Supplier_ID' => strtoupper(Yii::$app->user->identity->{'CompanyName'})
        ];
        $Response = Yii::$app->navhelper->getdata($responseService ,$RespondedFilter);  
        $Response = @$Response[0];
        if(!$Response) throw new \yii\web\HttpException(404, 'A page with this reference number could not be found');
        $UpdateCard = [
            'Key'=> @$Response->Key,            
            'Status' => 'Submitted',
            'No_Quote' => 1
        ];
        Yii::$app->navhelper->updateData($responseService, $UpdateCard);
        return $this->redirect(['/home/list']);
    }

    public function actionSubmit() {
        $params = Yii::$app->request->post();
        $responseService = Yii::$app->params['ServiceName']['ResponseCard'];
        $RespondedFilter = [
            'Tender_No'  => $params['Tender_No'],
            'Supplier_ID' => strtoupper(Yii::$app->user->identity->{'CompanyName'})
        ];
		//print_r($RespondedFilter);exit;
        $Response = Yii::$app->navhelper->getdata($responseService ,$RespondedFilter);   
        if(!$Response) throw new \yii\web\HttpException(404, 'A page with this reference number could not be found');
        $ErrorMessage='';
        $service = Yii::$app->params['ServiceName']['ResponseLines']; 
       
        //loop with responses only
        foreach ($params['Responses'] as $count => $value) {
            $FilesModel = new UploadFiles();
            $FilesModel->uploadFile = UploadedFile::getInstanceByName('files['.$count.'][files]');
            $DocumentMetadata = array(
                'RFX' => $params['Tender_No'],
                'TenderNumber' => $params['Tender_No'],
                'SupplierNumber' => strtoupper(Yii::$app->user->identity->{'CompanyName'}),
                'DocumentType' => @$value['DocumentDescription'],
                'DocumentNumber' => @$value['DocumentNo'],
                'ExpiryDate' => date('Y-m-d')
            );
            $UploadedFileName = $FilesModel->uploadfile($DocumentMetadata);
            //print_r($params); exit;
            //Create nav update payload
            $Updatedata = [
                'Key'=>$value['Key'],
                'Tender_No' => $params['Tender_No'],
                'Document_No' => isset($value['DocumentNo'])?$value['DocumentNo']:'',
                'Response' => @$value['Response']               
            ];
            if($UploadedFileName){
                $Updatedata[ 'File_Link'] = $UploadedFileName;
                $Updatedata['Document_URL'] = Yii::$app->params['sharepointUrl'].'/'.Yii::$app->params['SupplierDocumentsURL'].'/'.$UploadedFileName;
                //create an entry in SupplierDocuments
                $DocsService = Yii::$app->params['ServiceName']['SupplierDocuments'];
                $DocRecord = array(
                    'Supplier' => Yii::$app->user->identity->ApplicantId,
                    'DocumentType' => @$value['DocumentDescription'],
                    'DucumentURL' => Yii::$app->params['sharepointUrl'].'/'.Yii::$app->params['SupplierDocumentsURL'].'/'.$UploadedFileName,
                    'Deleted' => 0,
                    'DateCreated' => date('Y-m-d'),
                    'Document_Number' => @$value['DocumentNo'],
                    'Expiry_Date' => date('Y-m-d')
                );
                Yii::$app->navhelper->PostData($DocsService,$DocRecord);
            }
            //update
            $result[] = Yii::$app->navhelper->updateData($service, $Updatedata);
        }
        //upload the tender document
        $FilesModel = new UploadFiles();
        $FilesModel->uploadFile = UploadedFile::getInstanceByName('tenderdocx');
        $DocumentMetadata = array(
            'RFX' => $params['Tender_No'],
            'TenderNumber' => $params['Tender_No'],
            'SupplierNumber' => strtoupper(Yii::$app->user->identity->{'CompanyName'}),
            'DocumentType' => 'TENDER RESPONSE DOCUMENTS',
            'DocumentNumber' => '',
            'ExpiryDate' => date('Y-m-d')
        );
        $UploadedFileName = $FilesModel->uploadfile($DocumentMetadata);
        //print_r($UploadedFileName); exit;
        //update tender document's URL
        $Response = $Response[0];
        $UpdateCard = [
            'Key'=> $Response->Key,
            'Tender_Document_Path'=> Yii::$app->params['sharepointUrl'].'/'.Yii::$app->params['SupplierDocumentsURL'].'/'.$UploadedFileName,
            'Status' => 'Submitted',
        ];
        $UpdatedHeader = Yii::$app->navhelper->updateData($responseService, $UpdateCard);
        //print_r($UpdatedHeader); exit;
        //financeline update       
        if(Yii::$app->request->post('Unit_Price')){
            $FinancialLineSVC = Yii::$app->params['ServiceName']['FinancialResponseLines'];
            foreach (Yii::$app->request->post('Unit_Price') as $Financials){
                $Updatefinance = [
                    'Key'=> $Financials['Key'],
                    'Unit_Price'=> $Financials['Unit_Price'],
                ];
                $Financeline = Yii::$app->navhelper->updateData($FinancialLineSVC, $Updatefinance);
            }
        }
        //send acknowledgement
        $responseService = Yii::$app->params['ServiceName']['ResponseCard'];
        $RespondedFilter = [
            'Tender_No'  => $params['Tender_No'],
            'Supplier_ID' => strtoupper(Yii::$app->user->identity->{'CompanyName'})
        ];
        $Response = Yii::$app->navhelper->getdata($responseService ,$RespondedFilter);  
        $TenderService = Yii::$app->params['ServiceName']['QuotationsOnPortalCard'];
        $filter = array('No' => $params['Tender_No']);
        $Tender = Yii::$app->navhelper->getData($TenderService, $filter);
        Yii::$app->session->setFlash('success', 'Your response has been received. Kindly check your email for an acknowledgement'); 
        
        Yii::$app->mailer
        ->compose(
            ['html' => '_response'],
            ['User' => Yii::$app->user->identity, 'Response' => @$Response[0], 'Tender' => @$Tender[0]]
        )
        ->setFrom([Yii::$app->params['adminEmail'] => 'Financial Reporting Center(FRC'])
        ->setTo(Yii::$app->user->identity->Email)
        ->setSubject('RESPONSE TO REQUEST NO. '.$Tender[0]->Document_No)
        ->send();

        return $this->redirect(['view-response', 'ref' => Yii::$app->request->post('Tender_No')]);
    }

    public function actionViewprequalification($id)
    {
        $service = Yii::$app->params['ServiceName']['PrequalificationVendorLines'];
        $Data = Yii::$app->navhelper->getData($service,$filter);

        $DataArray=[];

        foreach($navData as $v)
        {            
            $DataArray[]= 
            [                
                'Key' => $v->Key,                 
                'Procurement_Plan' => $v->Procurement_Plan,               
                'Description' => $v->Description,
                'Quantity'=>$v->Quantity,                
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

        return $this->render('view',['data'=>$DataArray,
            'Provider'=> $Provider,
        ]);      
    }

    private function actionUser(){
        $service = Yii::$app->params['ServiceName']['SupplyUser'];
        $result = Yii::$app->navhelper->getData($service,['Email'=>Yii::$app->user->identity->Email]);
        //print '<pre>';
        return json_encode($result); //Array of objects
    }

    public function actionViewFile($ref, $rec) {
        $filter=['Requirement_ID' => $ref, 'Response_Header_No' => $rec];
        $service = Yii::$app->params['ServiceName']['ResponseLines'];
        $navData = Yii::$app->navhelper->getData($service,$filter);
        if($navData){           
            $navData = $navData[0];
            $DocumentPath =  Yii::getAlias('@webroot/attachments/'. $navData->File_Link);
            return \Yii::$app->response->sendFile($DocumentPath,'Document',['inline' => true]);
        }
        return false;
    }

    public function actionViewdocd($id)
    {        
        $ids=explode(":",$id);
        $path='';

        if($myService=='tender')
        {
            $filter=['Response_Header_No'=>$ids[0],'Requirement_ID'=>$ids[1]];
            $service=Yii::$app->params['ServiceName']['ResponseLines']; 
            $navData=Yii::$app->navhelper->getData($service,$filter);
            $path=isset($navData[0]->File_Link)?$navData[0]->File_Link:'';
        }elseif($myService='preq')
        {
            $filter=['Response_No'=>$ids[0],'Requirement_No'=>$ids[1]];
            $service = Yii::$app->params['ServiceName']['PrequalificationRequestLines'];
            $navData=Yii::$app->navhelper->getData($service,$filter);
            $path=isset($navData[0]->Sharepoint_Link)?$navData[0]->Sharepoint_Link:'';
            //$navData[0]->Sharepoint_Link;
        }
       $path = str_replace('../web', '', $path);

        //print_r($path); exit;

        if($path!='') 
        {
             $path = Yii::getAlias('@webroot'.$path);
            return \Yii::$app->response->sendFile($path,'Document',['inline'=>true]);
        }else
        {
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
        } 
    }

    private function getAnswers($type, $Question)
    {

        $filter=['Response_Type'=>$type];
        if($Question) $filter=['Response_Type'=>$type, 'Question' => $Question];
        //print_r($filter); exit;
        $service = Yii::$app->params['ServiceName']['ResponseAnswers'];
        $NavData = Yii::$app->navhelper->getData($service,$filter); //Prequalifications

        return $NavData;       
    }  

}
