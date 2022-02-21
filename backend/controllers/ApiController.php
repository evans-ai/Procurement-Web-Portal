<?php
/**
 * Helper functions for views
 * all return json.
 */

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Itemdetail;
use app\models\Itemledger;
use app\models\Recruitment;
use phpDocumentor\Reflection\DocBlock\Description;
use yii\helpers\Html;
use yii\web\Controller;
use yii\filters\ContentNegotiator;
use yii\web\Response;
use app\models\Requisition;
use app\models\Leavetypes;
use app\models\Imprestapplication;
use app\models\LeaveApplication;
use app\models\Jobs;
use app\models\Departments;
use app\models\Vendor;
use app\models\Employee;
use app\models\Training;
use app\models\Services;
use app\models\Department;
use yii\helpers\ArrayHelper;
use app\models\Companyjobs;
use app\models\Employmentcontract;
use app\models\User;



class ApiController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::className(),
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['updateleavebalance', 'leavebalance', 'getstorequisitions', 'getvacant','getvacancies','getapprovalss', 'getapprovals','payperiod',
                            'gettraining', 'getrecruitment', 'getcontrattypes', 'getvendor', 'getemployees', 'getemployeespermanent','getdepartments', 'getpurchaserequisition',
                            'getleaves', 'getleavetypes', 'getimpresthistory', 'getunsurrenderedimprest', 'getitemdetails', 'getitemledger', 'getdepartments',
                            'getjobs', 'getdepartment','getaccounts','country',
                            'applicants','applicantprofile','getloantypes',
                            'loanhistory','benefits','beneficiaries','benefitlines','dstations','casualslines',
                            'internalvacancies','Employeelist','benefithistory','selectleave','addcomment','leavedetails','recallvalidation','recallcomment','units','employeepayment','overtimelines','issupervisedbyme','leavesforrecall','consultancypaymentlines','casualspaymentlines','overtimelist','casualspaymentlist','consultantspaymentlist','casuals','rleaves','panel','panelists','mandatorydocs','otapprovallines'],
                'rules' => [
                    [
                        'actions' => ['updateleavebalance', 'leavebalance', 'getstorequisitions', 'getvacant','getvacancies','getapprovalss', 'getapprovals',
                            'gettraining', 'getrecruitment', 'getcontrattypes', 'getvendor', 'getemployees', 'getemployeespermanent','getdepartments', 'getpurchaserequisition',
                            'getleaves', 'getleavetypes', 'getimpresthistory', 'getunsurrenderedimprest', 'getitemdetails', 'getitemledger', 'getdepartments',
                            'getjobs', 'getdepartment','getaccounts','country',
                            'applicants','applicantprofile','getloantypes',
                            'loanhistory','benefits','beneficiaries','benefitlines','dstations','casualslines',
                            'internalvacancies','benefithistory','selectleave','addcomment','leavedetails','recallvalidation','recallcomment','payperiod','units','employeepayment','overtimelines','issupervisedbyme','leavesforrecall','consultancypaymentlines','casualspaymentlines','overtimelist','casualspaymentlist','consultantspaymentlist','casuals','rleaves','panel','panelists','mandatorydocs','otapprovallines'
                        ],
                        'allow' => true,
                        'roles' => ['@'],//allow only logged in users use of these methods
                    ],
                    [
                        'actions'=>['jobresponsibilitieslines','externaljobs','jobrequirementslines','externaladvertisement','getqualifications','getpostalcodes','qualifications','experience','referees','attachments'],//allow non-logged in users
                        'allow'=> true,
                        'roles' =>['?'],
                    ]
                ],
            ],
        ];
    }
    public function actionGetaccounts(){
        //CustomerList
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
         $url = new Services('CustomerList');
         $creds = (object)[];
         $creds->UserName = $username;
        $creds->PassWord = $password;
         $soapWsdl = $url->getUrl();
         $entryID = 'Leave_application';
         $filter=[];
         $account=[];
         $resutls=Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
         if($resutls->ReadMultiple_Result->CustomerList){
             foreach( $resutls->ReadMultiple_Result->CustomerList as $customer)
             {
                 $account[]=array(
                    'id'=>$customer->No,
                    'name'=>$customer->Name
                 );
             }
         }
         return $account;
    }
    public function actionCountry(){

        $username = 'System Support Eng';
        $password = '@francis123';
        //$password = Yii::$app->user->identity->Pass;
        $url = new Services('Countries');//FMN: class taking arguments , thats how you instruct it wheather it's dealing with a page or code unit
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $soapWsdl = $url->getUrl();
            //print_r($soapWsdl); exit;
        //$soapWsdl = 'http://Francis:7047/DynamicsNAV110/WS/PUBLIC/Page/Countries';
        $entryID = 'Leave_application';
        $filter=[];

        //$account=[];
        $results=Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
        /*print '<pre>';
        print_r($results); exit;*/
        if($results->ReadMultiple_Result->Countries){
            $id = 0;
            foreach( $results->ReadMultiple_Result->Countries as $k=>$v)
            {
                ++$id;
                $account[]= [
                    $v->Key,
                    $id,
                    $v->Code,
                    $v->Name,
                    '<a href="/country/update/?key='.$v->Key.'&code='.$v->Code.'&name='.$v->Name.'" title="Update Record"  class="update-country btn btn-outline btn-success"><i class="fa fa-edit"></i>
</a>',
                    '<a href="/country/delete/?key='.$v->Key.'&country='.$v->Name.'" title="Delete Record"  class="update-country btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>'

                ];
            }
        }
        return $account;
    }

    public function actionGetdepartment($jobid)
    {
       /* $departments = Department::find()
            ->select(['Code', 'Name'])
            ->where(['Dimension Code' => 'DEPERT'])
            ->asArray()
            ->all();*/
        //return $departments;

            $departments = Companyjobs::find()
            ->select(['[Dimension 1]'])
            ->where(['Job ID' => $jobid])
            ->asArray()
            ->one();



            $dimvalue = $departments['Dimension 1'];

             $department = Department::find()->select(['Name'])->where(['Code' =>$dimvalue])->one(); 

             /*print '<pre>';
            var_dump( $department); exit;*/

            $directorate = isset($department->{'Name'})?$department->{'Name'}:'Directorate Not Set';

            return $directorate ;

    }
    public function getcontract($contractid){// fetch employment contract
        $contract = Employmentcontract::find()->where(['Code'=>$contractid])->one();

        if(is_object($contract)){
            return $contract->Description;
        }
        else{
            return Null;
        }
    }
    public function actionUpdateleavebalance($leaveCode)
    {
        $this->enableCsrfValidation = false;
        $params = Yii::$app->request->get();
        $LeaveApplication = (object)[];
        $LeaveApplication->{'Key'} = $params['key'];
        $LeaveApplication->{'Leave_Code'} = $params['leaveCode'];
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services('leave');
        $soapWsdl = $url->getUrl();
        // $soapWsdl = 'http://David:7077/DynamicsNAV130/WS/Kenya%20Roads%20Board/Page/Leave_application';
        $entry = $LeaveApplication;
        $entryID = 'Leave_application';
        $result = Yii::$app->navision->updateEntry($creds, $soapWsdl, $entry, $entryID);
        if (is_object($result)) {
            return $result;
        } else {

            //$msg = "False";
            $msg = $result;

            return $msg;
        }

    }

    public function actionLeavebalance($leaveCode)
    {
        $this->enableCsrfValidation = false;
        $params = Yii::$app->request->get();
        $LeaveApplication = (object)[];
        $LeaveApplication->{'Leave_Code'} = $params['leaveCode'];
        $LeaveApplication->{'Start_Date'} =  date('Y-m-d', strtotime($params['start']));///$params['start'];
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services('leave');
        $soapWsdl = $url->getUrl();
        //$soapWsdl = 'http://David:7077/DynamicsNAV130/WS/Kenya%20Roads%20Board/Page/Leave_application';
        $entry = $LeaveApplication;
        $entryID = 'Leave_application';
        $result = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);

        if (is_object($result)) {
            foreach ($result as $key => $res) {
                    $header = $res;
                }

                $session = \Yii::$app->session;
                $session->set('bal_leave',$header);
            return $result;
        } else {

           // $msg = "False";
            $msg = $result;
            return $msg;
        }

    }

    public function actionGetstorequisitions()
    {
        //Find purchase requisition history
        $baseUrl = Yii::$app->request->baseUrl;
        $User = Yii::$app->user->identity->No;
        $No_series = 'S-REQ';
        $storeRequisitionHistory = Requisition::find()
            ->select(['[No_]', '[Employee Name]', 'Reason', '[Requisition Date]', 'Status'])
            ->where(['Employee Code' => $User])
            ->andWhere(['No_ Series' => $No_series])
            ->andWhere(['Posted' => '0'])
            ->asArray()
            ->all();
        $rss = array();
        foreach ($storeRequisitionHistory as $key => $row) {
            $enddate = date_create($row['Requisition Date']);
            $row['Requisition Date'] = date_format($enddate, "d-m-Y");
            $id = '<a href=' . $baseUrl . '/site/newstorerequisition?id=' . $row['No_'] . '&action=view>' . $row['No_'] . '</a>';
            $row['No_'] = $id;
            if ($row['Status'] == '1') {
                $row['Status'] = 'Released';
            } else if ($row['Status'] == '0') {
                $row['Status'] = 'Open';
            }
            $rss[] = array(
                $row['No_'],
                $row['Employee Name'],
                $row['Reason'],
                $row['Requisition Date'],
                $row['Status']
            );
        }
        return $rss;
    }

    public function actionGetvacant()//get recruitment needs requests
    {
        //Find purchase requisition history
        /*$User = Yii::$app->user->identity->Id;
        $baseUrl = Yii::$app->request->baseUrl;
        $recruitment = Recruitment::find()
            ->select(['[No_]', '[Description]', '[Appointment Type]','[Requester Name]', '[Expected Reporting Date]','Positions', '[Reason for Recruitment]', 'Status'])
            ->where(['Status' => '0'])
            ->asArray()
            ->all();
        $rss = array();
        /*print '<pre>';
        print_r($recruitment); exit;*/
        /*foreach ($recruitment as $key => $row) {
            $enddate = date_create($row['Expected Reporting Date']);
            $row['Expected Reporting Date'] = date_format($enddate, "d-m-Y");
            $detialsurl = '<a href=' . $baseUrl . '/recruitment/details?id=' . $row['No_'] . '>View details</a>';
            $applyurl = '<a href=' . $baseUrl . '/recruitment/apply?id=' . $row['No_'] . '>Apply</a>';
            $rss[] = array(
                $row['Key'],
                $row['No_'],
                $row['Description'],
                $row['Positions'],
                $row['Appointment Type'],
                $row['Expected Reporting Date'],
                $detialsurl,
                $applyurl
            );
        }
        return $rss;*/

        //New By Francis Njambi ==>Add an update link please
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('recruitment');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Duty_Station', 'Criteria' =>'>0'];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Recruitment_Request); exit;*/
        //$data[] = $results->ReadMultiple_Result->Recruitment_Request;
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Recruitment_Request)){
           
            foreach($results->ReadMultiple_Result->Recruitment_Request as $k=>$v){
                ++$counter;



              if(isset($v->Description)) {

                $Requisition_Nature = isset($v->Requisition_Nature)?$v->Requisition_Nature:'not set';
                $Duty_Station = isset($v->Duty_Station)?$v->Duty_Station:'not set';
                $Job_ID = isset($v->Job_ID)?$v->Job_ID:'not set';
                $Description = isset($v->Description)?$v->Description:'not set';





                $desc_link = '<a href="./needcard/?id='.$v->No.'">'.$Description.'</a>'; 

                if($v->Status == 'Open'){
                    $link = Html::a('Send for Approval',['/service/approvalrequest',
                        'app'=>$v->No,
                        'type'=>1,//recruitment
                    ],['class'=>'btn btn-sm btn-primary approval']);
                }
                else{
                    $link = 'Approval Request Sent';
                }

                if($v->Status == 'Pending_Approval'){
                    $cancel_link = Html::a('Cancel Request',['/service/cancelapprovalrequest',
                        'app'=>$v->No,
                        'type'=>1,//recruitment
                        'ajax'=>1,
                    ],['class'=>'btn btn-sm btn-warning cancel']);
                }
                else if($v->Status == 'Released'){
                    //$cancel_link = Html::a('Leave Approved',['/leave'],['class'=>'btn btn-sm btn-success','disabled'=>'disabled']);
                    $cancel_link = '<a href="#" class="btn btn-sm btn-success" disabled>Approved</a>';
                }
                else if($v->Status == 'Open'){
                        //$cancel_link = Html::a('Open Request',['/leave'],['class'=>'btn btn-sm btn-info','disabled'=>'disabled']);
                    $cancel_link = '<a href="#" class="btn btn-sm btn-info" disabled>Open Request</a>';
                }
                else if($v->Status == 'Rejected'){
                    //$cancel_link = Html::a('Rejected',['/leave'],['class'=>'btn btn-sm btn-danger','disabled'=>'disabled']);
                    $cancel_link = '<a href="#" class="btn btn-sm btn-danger" disabled>Rejected</a>';
                }

                //$stationname = $this->getStation($Duty_Station);
                $stationname = $this->getStation($v->Duty_Station);
                $data[] =  [
                    $v->Key,
                    $Job_ID,
                    $counter,
                    $desc_link,
                    //$v->Requested_By,
                    $v->Positions,
                    isset($v->Appointment_Type)?$v->Appointment_Type:'Not Set',
                    $v->Reason_for_Recruitment,
                    $v->Expected_Reporting_Date,
                    $v->Status,
                    $v->Recruitment_Type,
                    $Duty_Station,
                    $link,
                    $cancel_link,
                    $stationname,

                    

                ];
                }
            }

        }


        return $data;
    }
    public function actionInternalvacancies()
    {
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $baseUrl = Yii::$app->request->baseUrl;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('JobAdverts');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve

        //Also add a filter for Status Released
        
    $filter = [
        ['Field' => 'Status', 'Criteria' =>'Released'],
                   ['Field'=>'Requisition_Nature','Criteria'=>'Internal'],
                   //['Field'=>'End_Date','Criteria'=>'<='.date('d/m/Y')],

        ];
 /*print '<pre>';
        print_r($filter); exit;*/
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
       
        $counter = 0;
        if(isset($results->ReadMultiple_Result->JobAdverts)){
           
            foreach($results->ReadMultiple_Result->JobAdverts as $k=>$v){
                ++$counter;
              //if(isset($v->Requisition_Nature) && isset($v->Duty_Station) && isset($v->Job_ID)) {
                    $description = isset($v->Description)?$v->Description:'Not Set';
                         $job_ID = isset($v->Job_ID)?$v->Job_ID:'Not Set';



                    $detailsurl = '<a href=' . $baseUrl . '/recruitment/internaldetails?id=' . $v->No. '>View details</a>';
                $applyurl = '<a href=' . $baseUrl . '/recruitment/apply?id=' . $v->No. '&desc='.urlencode($description).'>Apply</a>';
                $dutystation = isset($v->Duty_Station)?$v->Duty_Station:'Not Set';
                 $stationname = $this->getStation($v->Duty_Station);
                    $data[] =  [
                        $v->Key,
                        $job_ID,
                        $dutystation,
                        $counter,
                        $v->No,
                        isset($v->Description)?$v->Description:'Not Set',
                        $v->Positions,
                        $v->Requisition_Nature,
                        $v->Start_Date,
                        $v->End_Date,
                        $v->Expected_Reporting_Date,
                        isset($v->Appointment_Type)?$this->getcontract($v->Appointment_Type):'Not Set',
                        $detailsurl,
                        $applyurl,
                        $stationname

        
                    ];
                //}
            }

        }


        return $data;
    }
    protected function getRneed($id){//Rudisha description ya recruitment need
        $rneed = Recruitment::find()
                                  ->select(['[No_]', '[Description]', 'Status'])
                                  ->where(['[No_]'=>$id])
                                  ->one();

                                  /*var_dump($rneed);
                                  exit;*/
        return isset($rneed->Description)?$rneed->Description:'Unknown Job';
    }
    public function actionGetapprovalss()
    {
        $url = new Services('approvals');
        $soapWsdl = $url->getUrl();
        $this->enableCsrfValidation = false;
        $this->layout = 'app';
        $imprest = (object)[];
        $RequestType = YII::$app->request->get();
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $rss = array();
        $list = array();
        if ($RequestType['action'] == 'open') {
            $filter = ['Field' => 'Status', 'Criteria' => 'Open'];
            $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
            //Check if 1st objects has a key
            //print_r(empty((array)$results->ReadMultiple_Result)) ;exit;
            if (!empty((array)$results->ReadMultiple_Result)) {
                foreach ($results as $key => $result) {
                    foreach ($result as $res) {
                        $list = $res;
                    }
                }
            }
            return $list;
        }
    }

    public function actionGetapprovals()
    {
        $url = new Services('approvals');
        $soapWsdl = $url->getUrl();
        //print_r($soapWsdl); PHP_EOL;
        //var_dump($url->isUp()); exit;
        $this->enableCsrfValidation = false;
        $this->layout = 'app';
        $RequestType = Yii::$app->request->get();
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;

        $rss = array();
        if ($RequestType['action'] == 'open') {
            $filter = ['Field' => 'Status', 'Criteria' => 'Open'];
            $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
            
            if (!empty((array)$results->ReadMultiple_Result)) {
                foreach ($results as $key => $result) {
                    foreach ($result as $res) {
                        $list = $res;
                    }
                }
                $baseUrl = Yii::$app->request->baseUrl;
//           $approve = '<a  href=' . $baseUrl . '/site/approvals?id=' . $list[$i]->Entry_No . '&action=approve>Approve</a>';
//           $reject = '<a href=' . $baseUrl . '/site/details?id=' . $list[$i]->Entry_No . '&action=reject>Reject</a>';
//           $delegate = '<a href=' . $baseUrl . '/recruitment/apply?id=' . $list[$i]->Entry_No . '&action=delegate>Delegate</a>';
//           $actions


                for ($i = 0; $i < count($list); $i++) {
                    $approve = '<a  href=' . $baseUrl . '/service/approverequest?id=' . $list[$i]->Entry_No . '&action=approve class="btn btn-sm btn-success">Approve</a>';
                    $reject = '<a class="reject btn btn-sm btn-danger" href=' . $baseUrl . '/service/rejectrequest?id=' . $list[$i]->Entry_No . '&action=reject title="'.$list[$i]->Workflow_Step_Instance_ID.'" rev="'.$list[$i]->Document_No.'" rel="'.$list[$i]->Entry_No.'" value="'.$list[$i]->Table_ID.'" name="'.$list[$i]->Record_ID_to_Approve.'" >Reject</a>';

                    $comment = '<a href="#" class="comment" title="'.$list[$i]->Workflow_Step_Instance_ID.'" rev="'.$list[$i]->Document_No.'" rel="'.$list[$i]->Entry_No.'" value="'.$list[$i]->Table_ID.'" name="'.$list[$i]->Record_ID_to_Approve.'"> Comment</a>';
                    //$delegate = '<a href=' . $baseUrl . '/site/aprrovals?id=' . $list[$i]->Entry_No . '&action=delegate>Delegate</a>';
                    $HiddenInput = '<input hidden id="quantityinStore"  type="text" value=>;
                         />';
                    if (!isset($list[$i]->ToApprove) or !isset($list[$i]->Details)) {
                        $toapprove = 'Missing Record';
                        $actions = 'N/A';
                        $details = 'Missing Record';
                    } else {
                        $actions = $approve . '|' . $reject ;
                        $toapprove = $list[$i]->ToApprove;
                        $details = $list[$i]->Details;
                    }



                    //link to approval entries cards

                    /*Leave*/
                    if(strpos($details, 'Leave')){
                        $card = '<a href="/leave/leavecard/?id='.$list[$i]->Document_No.'" target="_blank" disabled="disabled">'.$details.'</a>';
                    }
                    /*Benefits*/
                    elseif(strpos($toapprove, 'MREQ')){

                        $card = '<a href="/benefit/benefitcard/?req='.$list[$i]->Document_No.'" target="_blank" disabled="disabled">'.$details.'</a>';
                    }
                    /*Loans*/
                    elseif(strpos($toapprove, 'CLREQ')){
                        $card = '<a href="/loan/loancard/?req='.$list[$i]->Document_No.'" target="_blank" disabled="disabled">'.$details.'</a>';
                    }
                    /*Other undefined */
                    else{
                        $card = $toapprove;
                    }
                    $sender = isset($list[$i]->Sender_Name)?$list[$i]->Sender_Name:$list[$i]->Sender_ID;

                    //$actions = $approve . '|' . $reject . '|' . $delegate;
                    $rss[] = array(
                        $list[$i]->Entry_No,
                        $card,
                        $details,
                        $sender,
                        $list[$i]->Due_Date,
                        $list[$i]->Approver_ID,
                        $list[$i]->Status,
                        $actions
                    );
                }
            }
            return $rss;
        }
        $filter = ['Field' => 'Status', 'Criteria' => '<>Open'];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
        if (is_object($results)) {

            if (property_exists($results->ReadMultiple_Result, 'RequeststoApprove')) {
                foreach ($results as $key => $result) {
                    foreach ($result as $res) {
                        $list = $res;
                    }
                }
                $rss = array();
                for ($i = 0; $i < count($list); $i++) {
//          $approve = '<a href=' . $baseUrl . '/site/approvals?id=' . $row['No_'] . '&action=approve>Approve</a>';
//          $reject = '<a href=' . $baseUrl . '/recruitment/details?id=' . $row['No_'] . '&action=reject>Reject</a>';
//          $delegate = '<a href=' . $baseUrl . '/recruitment/apply?id=' . $row['No_'] . '&action=delegate>Delegate</a>';
//          $actions = $approve . '|' . $reject . '|' . $delegate;
                    $toapprove = isset($list[$i]->ToApprove) ? $list[$i]->ToApprove : 'Missing Record';
                    $details = isset($list[$i]->Details) ? $list[$i]->Details : 'Missing Record';

                    //dynamicaly add card links here for other approval entries
                     if(strpos($details, 'Leave')){
                        $card = '<a href="/leave/leavecard/?id='.$list[$i]->Document_No.'" target="_blank" disabled="disabled">'.$details.'</a>';
                    }elseif(strpos($toapprove, 'MREQ')){

                        $card = '<a href="/benefit/benefitcard/?req='.$list[$i]->Document_No.'" target="_blank" disabled="disabled">'.$details.'</a>';
                    }
                    elseif(strpos($toapprove, 'CLREQ')){
                        $card = '<a href="/loan/loancard/?req='.$list[$i]->Document_No.'" target="_blank" disabled="disabled">'.$details.'</a>';
                    }
                    else{
                        $card =  $toapprove;
                    }
                    $sender = isset($list[$i]->Sender_Name)?$list[$i]->Sender_Name:$list[$i]->Sender_ID;

                    $rss[] = array(
                        $card,
                        $details,
                        $sender,
                        $list[$i]->Due_Date,
                        $list[$i]->Approver_ID,
                        $list[$i]->Status
                    );
                }


            }
            //print_r($list[0]->ToApprove); exit;
        }
        return $rss;
    }


    public function actionGetotapprovals(){
        $url = new Services('ot_approval_list');
        $soapWsdl = $url->getUrl();
        /*print "<pre>";
        print_r(Yii::$app->user->identity->{'Employee No_'}); exit;*/
        
        $this->enableCsrfValidation = false;
        $this->layout = 'app';
        $RequestType = Yii::$app->request->get();
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        //$empno = \Yii::$app->user->identity->{'Employee No_'};
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $baseUrl = Yii::$app->request->baseUrl;


        $rss = array();
        if ($RequestType['action'] == 'open') {

            $filter = [
                ['Field' => 'Status', 'Criteria' => 'Pending_Approval'],
                ['Field' => 'Current_Approver', 'Criteria' => $username],
               // ['Field' => 'Approver_ID', 'Criteria' => $username] use Current_Approver
            ];
            $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);


            /*print '<pre>';
            print_r($results); 
            exit; */          
            if (property_exists($results, 'ReadMultiple_Result')) {
                
                
                if(property_exists($results->ReadMultiple_Result, 'OT_Approval_List')){
                    foreach($results->ReadMultiple_Result->OT_Approval_List as $ot){
                    $approve = '<a  href="' . $baseUrl . '/service/approveot?id=' . $ot->Code . '&emp='.$ot->Employee_ID.'&action=approve" class="btn btn-sm btn-success">Approve</a>';
                    $reject = '<a href="' . $baseUrl . '/service/rejectot?id=' . $ot->Code . '&emp='.$ot->Employee_ID.'" class="btn btn-sm btn-danger">Reject</a>'; 

                    $cancel = '<a href="' . $baseUrl . '/service/cancelotapproval?id=' . $ot->Code . '" class="btn btn-sm btn-danger">Reject</a>';
                    if($ot->Status == 'Pending_Approval'){
                        $action = $approve.'  '.$reject;
                    }else{
                        $action = 'N/A';
                    }
                    
                    $card = '<a href="' . $baseUrl . '/service/otcard?code=' . $ot->Code . '&emp='.$ot->Employee_ID.'" >'.$ot->Code.'</a>';


                    $sender = isset($ot->Sender_Name)?$ot->Sender_Name:' Name not set';
                    $rss[] = array(

                        $card,
                        $ot->Pay_Period,
                        strlen($ot->Description)?$ot->Description:'Description not Set',
                        $sender,                        
                        $ot->Approver_ID,
                        $ot->Status,
                        $ot->Step,
                        $action
                    );
                    }
                }

               
            }
            return $rss;
        }
        $filter = [
            ['Field' => 'Status', 'Criteria' => '<>Open'],
             ['Field' => 'Current_Approver', 'Criteria' => $username],
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
        if (is_object($results)) {

            if(property_exists($results->ReadMultiple_Result, 'OT_Approval_List')){
                    foreach($results->ReadMultiple_Result->OT_Approval_List as $ot){
                    
                    
                    $card = '<a href="' . $baseUrl . '/service/otcard?code=' . $ot->Code . '" >'.$ot->Code.'</a>';


                    $sender = isset($ot->Sender_Name)?$ot->Sender_Name:' Name not set';
                    $rss[] = array(

                        $card,
                        $ot->Pay_Period,
                        strlen($ot->Description)?$ot->Description:'Description not Set',
                        $sender,                        
                        $ot->Approver_ID,
                        $ot->Status,
                        $ot->Step,
                        
                    );
                    }
                }

            
        }
        return $rss;
    }

    public function actionGettraining()
    {
        //Find training histort
        $User = Yii::$app->user->identity->No;
        $training = Training::find()
            ->select(['[Request No_]', '[Description]', '[Employee No]', '[No_ Of Days]', '[Planned Start Date]', '[[Planned End Date]]', 'Status'])
            ->where(['User ID' => $User])
            ->asArray()
            ->all();
        $rss = array();
        $baseUrl = Yii::$app->request->baseUrl;
        foreach ($training as $key => $row) {
            $id = '<a href=' . $baseUrl . '/service/newtraining?id=' . $row['Request No_'] . '&action=view>' . $row['Request No_'] . '</a>';
            $row['Request No_'] = $id;
            if ($row['Status'] == '0') {
                $row['Status'] = 'Pending';
            } elseif ($row['Status'] == '1') {
                $row['Status'] = 'Released';
            } elseif ($row['Status'] == '2') {
                $row['Status'] = 'Pending Approval';
            } elseif ($row['Status'] == '3') {
                $row['Status'] = 'Pending Prepayment';
            } else {
                $row['Status'] = 'Rejected';
            }
            // $no=truncate($row['No_ Of Days']);
            $rss[] = array(
                $row['Request No_'],
                $row['Description'],
                $row['Employee No'],
                (int)$row['No_ Of Days'],
                $row['Planned Start Date'],
                $row['Planned End Date'],
                $row['Status']
            );
        }
        return $rss;
    }

    public
    function actionGetrecruitment()
    {
        //Find purchase requisition history
        $baseUrl = Yii::$app->request->baseUrl;
        $User = Yii::$app->user->identity->id;
        $recruitment = Recruitment::find()
            ->select(['[No_]', '[Description]', '[Appointment Type]', '[Expected Reporting Date]', '[Reasons for Recruitment]', 'Status'])
            ->where(['Requested By' => $User])
            ->asArray()
            ->all();
        $rss = array();
        foreach ($recruitment as $key => $row) {
            if ($row['Status'] == '0') {
                $row['Status'] = 'Open';
            } elseif ($row['Status'] == '1') {
                $row['Status'] = 'Released';
            } elseif ($row['Status'] == '2') {
                $row['Status'] = 'Pending Approval';
            } elseif ($row['Status'] == '3') {
                $row['Status'] = 'Pending Prepayment';
            } else {
                $row['Status'] = 'Rejected';
            }
            $id = '<a href=' . $baseUrl . '/service/newrecruitment?id=' . $row['No_'] . '&action=view>' . $row['No_'] . '</a>';
            $row['No_'] = $id;
            $rss[] = array(
                $row['No_'],
                $row['Description'],
                $row['Appointment Type'],
                $row['Expected Reporting Date'],
                $row['Reasons for Recruitment'],
                $row['Status']
            );
        }
        return $rss;
    }

    public function actionGetcontrattypes()
    {
        $jobs = Jobs::find()
            ->select(['Code', 'Description'])
            ->asArray()
            ->all();
        return $jobs;
    }

    public
    function actionGetvendor()
    {
        //Return vendor
        $jobs = Vendor::find()
            ->select(['No_', 'Name'])
            //->where(['Name'=>'Pacandy Enterprise'])
            ->asArray()
            ->all();
        return $jobs;
    }

    public
    function actionGetemployees()
    {
        //Return Employees
        $employee = Employee::find()
            ->select(['No_', '[First Name]', '[Middle Name]', '[Last Name]'])
            //->where(['Name'=>'Pacandy Enterprise'])
            ->asArray()
            ->all();
        //print_r(Yii::$app->user->identity->Details); exit;
        foreach ($employee as $key => $row) {
            if (!empty($row['First Name'])) {
                $row['Name'] = $row['First Name'] . " " . $row['Middle Name'] . " " . $row['Last Name'];
                $rss[] = array(
                    $row['No_'],
                    $row['Name'],
                );
            };
        }
        return $rss;
    }
    public 
    function actionGetemployeespermanent()
    {
        $identity = \Yii::$app->user->identity;
        $current_employee = $identity->No;
        $posting_grp = $identity->details->{'Posting Group'};
        //Return Employees
        $employee = Employee::find()
            ->select(['No_', '[First Name]', '[Middle Name]', '[Last Name]'])
            //->where(['[Global Dimension 1 Code]'=>$identity->directoratecode,'[Status]'=> 0])
            //->where(['[Posting Group]'=>$posting_grp,'[Status]'=> 0])
            ->where(['[Status]'=> 0])
            ->andWhere(['NOT IN','No_',[$current_employee]])
            ->asArray()
            ->all();
        //print_r(Yii::$app->user->identity->Details); exit;
        foreach ($employee as $key => $row) {
            if (!empty($row['First Name'])) {
                $row['Name'] = $row['First Name'] . " " . $row['Middle Name'] . " " . $row['Last Name'];
                $rss[] = array(
                    $row['No_'],
                    $row['Name'],
                );
            };
        }
        return $rss;
    }
    //for recruitment panels
    public function actionPanelists()
    {
        $identity = \Yii::$app->user->identity;
        $current_employee = $identity->No;
        //Return Employees
        $employee = Employee::find()
            ->select(['No_', '[First Name]', '[Middle Name]', '[Last Name]'])
            ->where(['[Status]'=> 0])
            //->andWhere(['NOT IN','No_',[$current_employee]])
            ->asArray()
            ->all();
        //print_r(Yii::$app->user->identity->Details); exit;
        foreach ($employee as $key => $row) {
            if (\Yii::$app->recruitment->getUsername($row['No_'])) {
                $row['Name'] = $row['First Name'] . " " . $row['Middle Name'] . " " . $row['Last Name'];
                $rss[] = array(
                    $row['No_'],
                    $row['Name'],
                );
            };
        }
        return $rss;
    }
    public function actionEmployeepayment(){//later filter by global dimension 7 code for employees within a certain unit
        $identity = \Yii::$app->user->identity;
        $current_employee = $identity->No;
        //Return Employees
        $employee = Employee::find()
            ->select(['No_', '[First Name]', '[Middle Name]', '[Last Name]'])
            ->where(['[Global Dimension 1 Code]'=>$identity->directoratecode,'[Status]'=> 0])
            ->andWhere(['NOT IN','No_',[$current_employee]])
            ->asArray()
            ->all();
        //print_r(Yii::$app->user->identity->Details); exit;
        foreach ($employee as $key => $row) {
            if (!empty($row['First Name'])) {
                $row['Name'] = $row['First Name'] . " " . $row['Middle Name'] . " " . $row['Last Name'];
                $rss[] = array(
                    $row['No_'],
                    $row['No_'].' - '.$row['Name'],
                );
            };
        }
        return $rss;
    }
    public function actionCasuals(){//later filter by global dimension 7 code for employees within a certain unit
        $identity = \Yii::$app->user->identity;
        $current_employee = $identity->No;
        //Return Employees
        $employee = Employee::find()
            ->select(['No_', '[First Name]', '[Middle Name]', '[Last Name]'])
            ->where(['[Status]'=> 0,'[Posting Group]'=>'CASUALS'])
            ->andWhere(['NOT IN','No_',[$current_employee]])
            ->asArray()
            ->all();
        //print_r(Yii::$app->user->identity->Details); exit;
        foreach ($employee as $key => $row) {
            if (!empty($row['First Name'])) {
                $row['Name'] = $row['First Name'] . " " . $row['Middle Name'] . " " . $row['Last Name'];
                $rss[] = array(
                    $row['No_'],
                    $row['No_'].' - '.$row['Name'],
                );
            };
        }
        return $rss;
    }
    public
    function actionGetjobs()//51511190
    {

        $jobs = Departments::find()
            ->select(['[Job ID] Code', '[Dimension 1] Dimensions', '[Department Name] Name', '[Job Description] Description'])->Where(['<>','Grade',' '])
            ->asArray()
            ->all();

        /*print '<pre>';
        print_r($jobs); exit;*/
        return $jobs;
    }
//get all compnay jobs without filtering
    public function actionCompanyjobs(){
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('companyjobs');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

       
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);
        $data = [];
        $index = 0;
        if(is_object($results)){
             
                foreach($results->ReadMultiple_Result->CompanyJobs as $k=>$v){
                    $link = Html::a('Update ',['/service/update','key'=>$v->Job_ID,'action'=>'update'                       
                    ],['class'=>'btn btn-xs btn-primary update']);
                    $qualification = Html::a('Add Qualification',['/service/createjobrequirement',
                        'docNo'=>$v->Job_ID                    
                    ],['class'=>'btn btn-xs btn-primary qualification']);

                     $responsibility = Html::a('Add Responsibility',['/service/createjobresponsibility',
                        'docNo'=>$v->Job_ID                       
                    ],['class'=>'btn btn-xs btn-primary responsibility']);
                    ++$index;
                        $data[]=[
                            $v->Key,
                            $index,
                            $v->Job_ID,
                            isset($v->Job_Description)?$v->Job_Description:'Not Set',
                            $v->No_of_Posts,
                            isset($v->Grade)?$v->Grade:'Not Set',
                            $v->Occupied_Establishments,
                            $v->Vacant_Establishments,
                            isset($v->Notice_Period)?$v->Notice_Period:'Not Set',
                            isset($v->Probation_Period)?$v->Probation_Period:'Not Set',
                            $v->Status,
                            $v->Date_Active,
                            $link,
                            $qualification,
                            $responsibility
                        ];
                }
        }
        /*print '<pre>';
        print_r($results);*/
        return $data;
    }
    //get no of posts for particular company job
    public function actionPosts($jobid){
        //exit($jobid);
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('companyjobs');
        $soapWsdl=$url->getUrl();
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Job_ID', 'Criteria' => "=".$jobid];
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

       
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        if(is_object($results) && isset($results->ReadMultiple_Result->CompanyJobs)){
                 return ['posts'=>$results->ReadMultiple_Result->CompanyJobs[0]];
        }
        else{
             return ['posts'=>'undefined: Unpdate on Company Jobs']; 
        }
       
    }
    public function actionGetpurchaserequisition()
    {
        // find store requisition history
        $User = Yii::$app->user->identity->No;
        $No_series = 'P-REQ';
        $rss = [];
        $baseUrl = Yii::$app->request->baseUrl;
        $RequisitioHistory = Requisition::find()
            ->select(['[No_]', '[Employee Name]', 'Reason', '[Requisition Date]', 'Status'])
            ->where(['Employee Code' => $User])
            ->andWhere(['No_ Series' => $No_series])
            ->asArray()
            ->all();
        foreach ($RequisitioHistory as $key => $row) {
            $enddate = date_create($row['Requisition Date']);
            $row['Requisition Date'] = date_format($enddate, "d-m-Y");
            $id = '<a href=' . $baseUrl . '/site/newpurchaserequisition?id=' . $row['No_'] . '&action=view>' . $row['No_'] . '</a>';
            if ($row['Status'] == '1') {
                $row['Status'] = 'Approved';
            } else {
                $row['Status'] = 'Pending';
            }
            $row['No_'] = $id;
            $rss[] = array(
                $row['No_'],
                $row['Employee Name'],
                $row['Reason'],
                $row['Requisition Date'],
                $row['Status']
            );
        }
        return $rss;
    }

    public
    function actionGetleaves()
    {
        $baseUrl = Yii::$app->request->baseUrl;
        //Get employee leave hitstory
        //$User = Yii::$app->user->identity->No;
        //print_r($User); exit;
        /*$leaveHistory = LeaveApplication::find()
            ->select(['[Application No]', '[Leave Code]', '[Days Applied]', '[Start Date]', '[End Date]', '[Name]', 'Status'])
            ->where(['Employee No' => $User])
            ->asArray()
            ->all();
        $rss = [];
        $baseUrl = Yii::$app->request->baseUrl;
        foreach ($leaveHistory as $key => $row) {
            if ($row['Status'] == '1') {
                $row['Status'] = 'Released';
            } else {
                $row['Status'] = 'Open';
            }
            $row['Days Applied'] = rtrim($row['Days Applied'], '.0');

            $date = date_create($row['Start Date']);
            $row['Start Date'] = date_format($date, "d-m-Y");
            $enddate = date_create($row['End Date']);
            $row['End Date'] = date_format($enddate, "d-m-Y");
            $id = '<a href=' . $baseUrl . '/leave/newleave?id=' . $row['Application No'] . '&action=view>' . $row['Application No'] . '</a>';
            //$withdraw = '<a href=' . $baseUrl . '/leave/withdraw?key=' . $row['Key'] . '&action=view>'. $row['Application No'] . '</a>';
            $row['Application No'] = $id;
            $rss[] = array(
                $row['Application No'],
                $row['Leave Code'],
                $row['Days Applied'],
                $row['Start Date'],
                $row['End Date'],
                $row['Name'],
                $row['Status'],

            );
        }
        return $rss;*/
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $employee = $identity->No;
       
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('leave');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Employee_No', 'Criteria' =>$employee ];


        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);//add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Leave_application)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            $cancel_link = '<a></a>';
            foreach($results->ReadMultiple_Result->Leave_application as $k=>$v){
                ++$counter;
                $link ='';

                $view_leave = '<a href=/leave/newleave?key='.$v->Key.'&id=' . $v->Application_No . '&action=view>' . $v->Application_No . '</a>';



                if($v->Status == 'Open'){
                    $link = Html::a('Send for Approval',['/service/approvalrequest',
                        'app'=>$v->Application_No,
                        'type'=>0,//leave request from option string in code unit
                    ],['class'=>'btn btn-sm btn-primary sendforapproval']);
                }
                else{
                    $link = 'Approval Request Sent';
                }

                if($v->Status == 'Pending_Approval'){
                    $cancel_link = Html::a('Cancel Request',['/service/cancelapprovalrequest',
                        'app'=>$v->Application_No,
                        'type'=>0,
                    ],['class'=>'btn btn-sm btn-warning']);
                }
                else if($v->Status == 'Approved'){
                    //$cancel_link = Html::a('Leave Approved',['/leave'],['class'=>'btn btn-sm btn-success','disabled'=>'disabled']);
                    //$cancel_link = '<a href="#" class="btn btn-sm btn-success" disabled>Approved</a>';
                    $cancel_link = '<a>Approved</a>';
                }
                else if($v->Status == 'Open'){
                        //$cancel_link = Html::a('Open Request',['/leave'],['class'=>'btn btn-sm btn-info','disabled'=>'disabled']);
                    if($v->Requires_Attachment){
                            $cancel_link = '<a href="'.$baseUrl.'/leave/update/?file=1&id='.$v->Application_No.'" class="btn btn-sm btn-info" >Update Request</a>';
                    }else{//doesn't require an attachment update
                            $cancel_link = '<a href="'.$baseUrl.'/leave/update/?id='.$v->Application_No.'" class="btn btn-sm btn-info" >Update Request</a>';
                    }
                    
                }
                else if($v->Status == 'Rejected'){
                    //$cancel_link = Html::a('Rejected',['/leave'],['class'=>'btn btn-sm btn-danger','disabled'=>'disabled']);
                    //$cancel_link = '<a href="#" class="btn btn-sm btn-danger" disabled>Rejected</a>';
                    $cancel_link = '<a>Rejected</a>';
                }
                $leave_code = isset($v->Leave_Code)?$v->Leave_Code:'Not Set';
                $data[] =  [
                    $v->Key,
                    $counter,
                    $view_leave,//$v->Application_No,
                    $leave_code,
                    $v->Days_Applied,
                    date('d-m-Y',strtotime($v->Start_Date)),
                    date('d-m-Y',strtotime($v->End_Date)),
                    isset($v->Acting_Person)?Employee::getprobeename($v->Acting_Person):'Not Set',
                    $v->Status,
                    $link,
                    $cancel_link,

                ];
            }
        }



        return $data;
    }

    public
    function actionGetleavetypes()
    {
        
        $identity = \Yii::$app->user->identity;

        $session = \Yii::$app->session;
        $gender = $session->get('gender');
        $region = $this->getcountry($identity->region);

        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $posting_grp = $identity->details->{'Employee Posting Group'};

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('leavetypes');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = [
            'Field' => 'Country_Region_Code', 'Criteria' => $region,
            'Field'=>'Gender','Criteria' => 'Both |'.$gender
           
    ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);

        $data = [];
       

        if($results->ReadMultiple_Result->leave_types){
            $ls = ArrayHelper::map($results->ReadMultiple_Result->leave_types,'Code','Description');
        }

        foreach($ls as $k=>$v){
            if($posting_grp == 'NRS' && $k == 'ADOPTION'){
                continue;
            }
            $data[]= ['Code'=>$k,'Description'=>$v];
        }

        return $data;
    }
//kindly hardcode credentials for this api method for the sake of external applicants without ad login(you can use one of the hr people creds!!!)
    function actionGetcountries()
    {
        $creds = (object)[];
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }

        
       
        $url= new Services('Countries');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        //$filter = ['Field' => 'Countries_Filter', 'Criteria' => ''];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Countries); exit;*/

        if($results->ReadMultiple_Result->Countries){
            $ls = ArrayHelper::map($results->ReadMultiple_Result->Countries,'Code','Name');
        }

        foreach($ls as $k=>$v){
            $data[]= ['Code'=>$k,'Description'=>$v];
        }

        return $data;
    }
    public function actionGetqualifications($qualificationtype=""){
        


        $creds = (object)[];
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        $url= new Services('Qualifications');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter= [];
        if(strlen($qualificationtype) && isset($qualificationtype)){
             $filter = ['Field' => 'Qualification_Type', 'Criteria' => $qualificationtype];
        }
       
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Qualifications); exit;*/

        if(property_exists($results->ReadMultiple_Result,'Qualifications')){

            $ls = ArrayHelper::map($results->ReadMultiple_Result->Qualifications,'Code','Description');
             foreach($ls as $k=>$v){
                    $data[]= ['Code'=>$k,'Description'=>$v];
                }
        }

       

        return $data;
    }
    public function actionGetpostalcodes(){
        

        $creds = (object)[];
       if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        $url= new Services('postalcodes');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        //$filter = ['Field' => 'Countries_Filter', 'Criteria' => ''];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Qualifications); exit;*/

        if($results->ReadMultiple_Result->PostalCodes){
            $ls = ArrayHelper::map($results->ReadMultiple_Result->PostalCodes,'Code','City');
        }

        foreach($ls as $k=>$v){
            $data[]= ['Code'=>$k,'Description'=>$v];
        }

        return $data;
    }
    function actionGetleavebalance($LeaveNo)
    {
        //Get leave type details
        //Nav

    }

    public
    function actionGettraininghistory()
    {
        //Get training history request history
    }

    public
    function actionGetrecruitmenthistory()
    {
        //Get recruitment history
    }

    public
    function actionGetimpresthistory()
    {
        //Get status of all application made
        $baseUrl = YII::$app->request;
         $Employee = Yii::$app->user->identity->No;
        //?request=claim
        $ImprestHistorys = Imprestapplication::find()
            ->select(['No_', '[Employee Name]', '[Request Date]', '[Status]'])
            ->where(['Employee No' => $Employee])
            ->andWhere(['Surrendered' => '0'])
            ->andWhere(['No_ Series' => Yii::$app->request->get('request') == 'claim' ? 'CLAIM' : 'IMPREST'])
            ->all();
        $rss = array();
        //$id='<a href=' . $baseUrl . '/site/newpurchaserequisition?id=' . $row['No_'] . '&action=view>'. $row['No_'].'</a>';
        $baseUrl = Yii::$app->request->baseUrl;
        $type = Yii::$app->request->get('request') == 'claim' ? 'CLAIM' : 'IMPREST';
        foreach ($ImprestHistorys as $key => $row) {
            $enddate = date_create($row['Request Date']);
            $row['Request Date'] = date_format($enddate, "d-m-Y");
            if ($row['Status'] == '0') {
                $row['Status'] = 'Pending';
            } else {
                $row['Status'] = 'Approved';
            }

            $id = '<a href=' . $baseUrl . '/imprest/newimprest?id=' . $row['No_'] . '&action=view&type=' . $type . '>' . $row['No_'] . '</a>';
            $row['No_'] = $id;
            $rss[] = array(
                $row['No_'],
                $row['Employee Name'],
                $row['Request Date'],
                $row['Status']
            );
        } //Cue extra resourcem, project upgrade, ad.
        return $rss;
        //Get imprest not surrendered.
    }

    public
    function actionGetunsurrenderedimprest()
    {
        //Employee number to be global.
        $Employee = Yii::$app->user->identity->No;
        //$userID=Yii::$app->params['userID'];
        // $userID=Yii::$app->user->identity-id;
        $ImprestHistorys = Imprestapplication::find()
            ->select(['No_', '[Employee Name]', '[Request Date]', '[Status]'])
            ->where(['Employee No' => $Employee])
            ->andWhere(['Transaction Type' => 'IMPREST'])
            ->andWhere(['No_ Series' => 'IMPREST'])
            ->andWhere(['Posted' => '1'])
            ->andWhere(['Surrendered' => '0'])
            ->all();
        $rss = array();
        $baseUrl = Yii::$app->request->baseUrl;
        foreach ($ImprestHistorys as $key => $row) {
            if ($row['Status'] == '0') {
                $row['Status'] = 'Pending';
            } else {
                $row['Status'] = 'Released';
            }
            $imprestUrl = '<a href=' . $baseUrl . '/imprest/surrenderimprest?id=' . $row['No_'] . '> ' . $row['No_'] . '</a>';
            $row['No_'] = $imprestUrl;
            $rss[] = array(
                $row['No_'],
                $row['Employee Name'],
                $row['Request Date'],
                $row['Status']
            );
        }
        return $rss;
        //Get imprest not surrendered.
    }


    public
    function actionGetimprest()
    {
        //Get imprest not surrendered.
    }

    public
    function actionGetitemdetails()
    {
        // find details of an item from item entries
        $items = Itemdetail::find()
            ->select(['No_', 'Description'])
            ->asArray()->all();
        return $items;
    }

    public
    function actionGetitemledger($itemNo)
    {
        $Instore = Itemledger::find()->select(['Remaining Quantity Quantity'])
            ->where(['Item No_' => $itemNo])
            ->one();
        $Instore = (int)$Instore['Quantity'];
        return $Instore;
    }

    public
    function actionGetProcumenttype()
    {
        $procumentTy = '';
    }
 public function actionNotAllowed()
    {
        // throw new \yii\web\HttpException(403, 'Unauthor');
    }

    //Recruitment temporary data retrieval for applicant
    public function actionQualifications(){

        $session = \Yii::$app->session;
        $data =  $session->get('Communication');

        

        $creds = (object)[];
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        $url= new Services('Applicant_Qualifications');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
            /*print '<pre>';
        print_r($data); exit;*/
        $filter = ['Field' => 'Applicant_No', 'Criteria' =>$data->No];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Applicant_Qualification); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Applicant_Qualification)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Applicant_Qualification as $k=>$v){
                ++$counter;
                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Applicant_No,
                    $v->Description,
                    $v->From_Date,
                    $v->To_Date,
                    $v->Institution_Company,
                    '<a href="/recruitment/deletequalification/?key='.$v->Key.'&application='.$v->Applicant_No.'" title="Delete Record"  class="del-qualification btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>'
                ];
            }
        }



        return $data;
    }
    //Attachments

    public function actionAttachments(){

        $session = \Yii::$app->session;
        $data =  $session->get('Communication');
        //$submissionStatus = \Yii::$app->recruitment->Applicationstatus();

        

        $creds = (object)[];
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        $url= new Services('appDocuments');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        /*print '<pre>';
    print_r($data); exit;*/
        $filter = ['Field' => 'Applicant_No', 'Criteria' =>$data->No];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Attachments); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Attachments)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Attachments as $k=>$v){
                ++$counter;
                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Applicant_No,
                    $v->Document_Description,
                    $v->Document_Link,
                    Html::a('View Document',['/recruitment/viewdoc','app'=>$v->Applicant_No,'link'=>$v->Document_Link],['target'=>'_blank','class'=>'btn btn-xs btn-primary']),
                    '<a href="/recruitment/deleteattachment/?key='.$v->Key.'&application='.$v->Applicant_No.'" title="Delete Record"  class="del-qualification btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>',
                    

                ];
            }
        }



        return $data;
    }
    public function actionReferees(){

        $session = \Yii::$app->session;
        $data =  $session->get('Communication');

        $creds = (object)[];
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        $url= new Services('appReferees');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        /*print '<pre>';
    print_r($data); exit;*/
        $filter = ['Field' => 'No', 'Criteria' =>$data->No];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->referees); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->referees)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->referees as $k=>$v){
                ++$counter;
                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->No,
                    $v->Names,
                    $v->Designation,
                    $v->Company,
                    $v->Telephone_No,
                    $v->E_Mail,
                    //$v->Notes,

                    '<a href="/recruitment/deletereferee/?key='.$v->Key.'&application='.$v->No.'" title="Delete Record"  class="del-qualification btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>'
                ];
            }
        }



        return $data;
    }

    //Get Experience
    public function actionExperience(){

        $session = \Yii::$app->session;
        $data =  $session->get('Communication');

        

        $creds = (object)[];
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        $url= new Services('appExperience');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        /*print '<pre>';
            print_r($data); exit;*/
        $filter = ['Field' => 'Applicant_No', 'Criteria' =>$data->No];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
       /* print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->experience)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->experience as $k=>$v){
                ++$counter;
                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Applicant_No,
                    $v->Institution_Company,
                    $v->Responsibility,
                    //$v->Position_Description,
                    $v->From_Date,
                    $v->To_Date,
                    
                    //$v->Notes,

                    '<a href="/recruitment/deleteexperience/?key='.$v->Key.'&application='.$v->Applicant_No.'" title="Delete Record"  class="del-qualification btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>'
                ];
            }
        }



        return $data;
    }
    //get external advertised positions
    public function actionExternaladvertisement(){
        $baseUrl = Yii::$app->request->baseUrl;
        /**/
        //exit($username);
        $creds = (object)[];
        //Hard code credentials since external applicants do not have AD credentials
        if(Yii::$app->user->isGuest){
            $creds->UserName = 'CGIARAD\FNjambi';
            $creds->PassWord = 'Pass20$Cis';
        }else{
            $identity = \Yii::$app->user->identity;
            $creds->UserName = $identity->Id;
            $creds->PassWord = $identity->Pass;
        }
        
        //$url= new Services('JobAdverts');
        $url = new Services('JobAdverts');
        $soapWsdl=$url->getUrl();
//exit($soapWsdl);
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        /*print '<pre>';
            print_r($data); exit;*/
        $filter = [
            ['Field' => 'Status', 'Criteria' =>'Released'],
                   ['Field'=>'Requisition_Nature','Criteria'=>'External'],

        ];



        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Recruitment_Request); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->JobAdverts)){


            foreach($results->ReadMultiple_Result->JobAdverts as $k=>$v){
                if(isset($v->Description) && isset($v->Job_ID)):

                    $detailsurl = '<a href=' . $baseUrl . '/recruitment/details?type=external&id=' . $v->No . '>View details</a>';
                    $applyurl = '<a href=' . $baseUrl . '/recruitment/apply?type=external&id=' . $v->No . '&desc='.urlencode($v->Description).'>Apply</a>';
                    $dutystation = isset($v->Duty_Station)?$v->Duty_Station:'Not Set';
                    $stationname = $this->getStation($dutystation);

                    ++$counter;
                    $data[] =  [
                        $v->Key,
                        $v->Job_ID,
                        $dutystation,
                        $counter,
                        $v->No,
                        $v->Description,
                        $v->Positions,
                        isset($v->Requisition_Nature)?$v->Requisition_Nature:'Not Set',
                        $v->Start_Date,
                        $v->End_Date,
                        isset($v->Appointment_Type)?$this->getcontract($v->Appointment_Type):'Not Set',
                        $detailsurl,
                        $applyurl,
                        $stationname


                    ];

                endif;
            }
        }



        return $data;
    }
    //simple to use api method for publishing external job vacancies => IITA TEAM HELPER METHOD
     public function actionExternaljobs(){
        $baseUrl = Yii::$app->request->baseUrl;
        /*$identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;*/
        //exit($username);
        $creds = (object)[];
        //Hard code credentials since external applicants do not have AD credentials
        $creds->UserName = 'CGIARAD\FNjambi';
        $creds->PassWord = 'Pass20$Cis';
        $url= new Services('JobAdverts');
        //$url = new Services('recruitment');
        $soapWsdl=$url->getUrl();
//exit($soapWsdl);
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        /*print '<pre>';
            print_r($data); exit;*/
        $filter = ['Field' => 'Posted', 'Criteria' => 1,
                   'Field'=>'Requisition_Nature','Criteria'=>'External',
                   //'Field'=>'End_Date','Criteria'=>'<='.date('m/d/Y'),

        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Recruitment_Request); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->JobAdverts)){


            foreach($results->ReadMultiple_Result->JobAdverts as $k=>$v){
                if(isset($v->Description) && isset($v->Job_ID)):

                    $detailsurl = '<a href=' . $baseUrl . 'http://selfservice.iita.org/recruitment/details?id=' . $v->No . '&type=external>View details</a>';
                    $applyurl = '<a href=' . $baseUrl . 'http://selfservice.iita.org/recruitment/apply?type=external&id=' . $v->No . '&desc='.$v->Description.'>Apply</a>';
                    $dutystation = isset($v->Duty_Station)?$v->Duty_Station:'Not Set';

                    ++$counter;
                    $data['jobs'][] =  [
                        //$v->Key,
                        'jobId'=>$v->Job_ID,
                        'dutyStation'=>$dutystation,
                        'index'=>$counter,
                        'needId'=>$v->No,
                        'jobDescription'=>$v->Description,
                        'positions'=>$v->Positions,
                        'requisitionNature'=>$v->Requisition_Nature,
                        'startDate'=>$v->Start_Date,
                        'endDate'=>$v->End_Date,
                        'expectedReportingDate'=>$v->Expected_Reporting_Date,
                        'contractType'=>isset($v->Appointment_Type)?$this->getcontract($v->Appointment_Type):'Not Set',

                        'detailsLink'=>$detailsurl,
                        'applicationLink'=>$applyurl


                    ];

                endif;
            }
        }



        return $data;
    }
    //get job applicants
    public function actionApplicants(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
         /*print '<pre>';
         print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Applicant_List)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Applicant_List as $k=>$v){
                ++$counter;
                if(isset($v->Job_ID) && isset($v->First_Name) && isset($v->Last_Name)){

                $reqnature = (\Yii::$app->recruitment->getRequisitionNature($v->No) !== null)?\Yii::$app->recruitment->getRequisitionNature($v->No): 'Not Set';

                switch ($reqnature) {
                    case 'Internationally_Recruited_Staff_IRS':
                        # code...
                         $requisition = 'IRS';
                        break;
                    case 'Nationally_Recruited_Staff_NRS':
                        $requisition = 'NRS';
                        break;
                    default:
                        $requisition = 'Not Set';
                        break;
                }

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->No,
                    $this->getRneed($v->Job_ID),//from db query
                    ucfirst($v->First_Name),
                    ucfirst($v->Middle_Name),
                    ucfirst($v->Last_Name),
                    isset($v->Marital_Status)?$v->Marital_Status:'Not Set',
                    isset($v->ID_Number)?$v->ID_Number:'Not Set',
                    $this->getcountry($v->Citizenship),//from service
                    $v->Date_Of_Birth,
                    Html::a('View Application',['/recruitment/appdetails','app'=>$v->No,'job'=>$this->getRneed($v->Job_ID)]),
                    $requisition,


                ];
            }
            }
        }



        return $data;

    }
    public function actionSubmittedapplications(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter = [];
        $filter = ['Field' => 'Submitted', 'Criteria' => 1];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
         /*print '<pre>';
         print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Applicant_List)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Applicant_List as $k=>$v){
                ++$counter;
                if(isset($v->Job_ID) && isset($v->First_Name) && isset($v->Last_Name)){

                    //Initialize panel member status pending validation
                $ispanelmember = 'Non Member';

                if($this->getRneed($v->Job_ID) !== 'Unknown Job'){
                    $ispanelmember = (\Yii::$app->recruitment->ispanelmember(\Yii::$app->user->identity->no,$v->No))?'Panelist':'Non Member';
                }
                

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->No,
                    $this->getRneed($v->Job_ID),//from db query
                    ucfirst($v->First_Name),
                    ucfirst($v->Middle_Name),
                    ucfirst($v->Last_Name),
                    isset($v->Marital_Status)?$v->Marital_Status:'Not Set',
                    isset($v->ID_Number)?$v->ID_Number:'Not Set',
                    $this->getcountry($v->Citizenship),//from service
                    $v->Application_Date,
                    Html::a('View Application',['/recruitment/appdetails','app'=>$v->No,'job'=>$this->getRneed($v->Job_ID)]),
                    $v->Job_ID,
                    $ispanelmember

                ];
            }
            }
        }

        return $data;

    }

     public function actionShortlisted(){
        
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter = [];
        $filter = [
            ['Field' => 'Submitted', 'Criteria' => 1],
            ['Field' => 'Shortlisting_Total_Score','Criteria' => '>0'],
           
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
         /*print '<pre>';
         print_r($results->ReadMultiple_Result->Applicant_List); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Applicant_List)){
            
            foreach($results->ReadMultiple_Result->Applicant_List as $k=>$v){
                ++$counter;
                $link = Html::a('Interview Candidate',['/recruitment/interview',
                        'app'=>$v->No,
                        'job'=>$this->getRneed($v->Job_ID),                       
                        
                    ],['class'=>'btn btn-sm btn-primary']);
                if(isset($v->Job_ID)){
                        $data[] =  [
                        $v->Key,
                        $counter,
                        $v->No,
                        $this->getRneed($v->Job_ID),//from db query
                        ucfirst($v->First_Name),
                        ucfirst($v->Middle_Name),
                        ucfirst($v->Last_Name),
                        $this->getcountry($v->Citizenship),//from service
                        $v->Application_Date,
                        round($v->Shortlisting_Total_Score),
                        $v->Job_ID,
                        $link

                        /*Html::a('View Application',['/recruitment/appdetails','app'=>$v->No,'job'=>$this->getRneed($v->Job_ID)]),
                        $v->Job_ID,*/

                    ];
                }
                
            
            }
        }

        return $data;

    }
    //Interview candidates list

    public function actionInterviewed(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter = [];
        $filter = [
            ['Field' => 'Submitted', 'Criteria' => 1],
            ['Field' => 'Shortlisting_Total_Score','Criteria' => '>0'],
            ['Field' => 'Oral_Interview_Total_Score','Criteria' => '>0'],
            ['Field' => 'Tech_Interview_Total_Score','Criteria' => '>=0'],
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
         /*print '<pre>';
         print_r($results->ReadMultiple_Result->Applicant_List); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Applicant_List)){
            
            foreach($results->ReadMultiple_Result->Applicant_List as $k=>$v){
                ++$counter;
                
                if(isset($v->Job_ID)){
                        $data[] =  [
                        $v->Key,
                        $counter,
                        $v->No,
                        $this->getRneed($v->Job_ID),//from db query
                        ucfirst($v->First_Name),
                        ucfirst($v->Middle_Name),
                        ucfirst($v->Last_Name),
                        $this->getcountry($v->Citizenship),//from service
                        $v->Application_Date,
                        round($v->Shortlisting_Total_Score),
                        round($v->Oral_Interview_Total_Score),
                        round($v->Tech_Interview_Total_Score),
                        $v->Job_ID,

                        /*Html::a('View Application',['/recruitment/appdetails','app'=>$v->No,'job'=>$this->getRneed($v->Job_ID)]),
                        $v->Job_ID,*/

                    ];
                }
                
            
            }
        }

        return $data;

    }







    //My Applications-->internal staff
    public function actionMyapplications($empno){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $myemail = \Yii::$app->recruitment->getEmail($empno);


        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter = [];
        $filter = ['Field' => 'Employee_No', 'Criteria' => $empno];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
         /*print '<pre>';
         print_r($results); exit;*/
        $counter = 0;
        $baseUrl = Yii::$app->request->baseUrl;
        if(isset($results->ReadMultiple_Result->Applicant_List)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Applicant_List as $k=>$v){
                ++$counter;
                $email = isset($v->E_Mail)?$v->E_Mail:'notset@gmail.com';
                $job_desc = $this->getRneed($v->Job_ID);
                $candidate = ucwords($v->First_Name.' '.$v->Middle_Name.' '.$v->Last_Name);
                if($v->Submitted){
                        $submitted = '<a href="#" class="btn btn-success submitted">Submitted</a>';
                }else{
                        $submitted = '<a href="'.$baseUrl.'./acknowledge/?applicantid='.$v->No.'&job='.$job_desc.'&email='.$email.'&applicant='.$candidate.'&js=1" class="btn btn-warning submit_application"> Submit</a>';
                }
                if(isset($v->Job_ID) && isset($v->First_Name) && isset($v->Last_Name)){

                    $data[] =  [
                        $v->Key,
                        $counter,
                        $v->No,
                        $this->getRneed($v->Job_ID),//from db query
                        ucfirst($v->First_Name),
                        ucfirst($v->Middle_Name),
                        ucfirst($v->Last_Name),
                        isset($v->Marital_Status)?$v->Marital_Status:'Not Set',
                        isset($v->ID_Number)?$v->ID_Number:'Not Set',
                        $this->getcountry($v->Citizenship),//from service
                        $v->Application_Date,
                        $submitted,
                        Html::a('View Application',['/recruitment/appdetails','app'=>$v->No,'job'=>$this->getRneed($v->Job_ID)]),
                        $v->Job_ID,

                    ];
                }
            }
        }

        return $data;

    }
    public function actionApplicantprofile($appno="APP-00000025"){//test method--> moved it to recruitment controller
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'No', 'Criteria' => $appno];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        print '<pre>';
        print_r($results->ReadMultiple_Result);
        exit;

    }
    //get loan types
    function actionGetloantypes()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('loanTypes');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        //$filter = ['Field' => 'Countries_Filter', 'Criteria' => ''];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->loanTypeProduct); exit;*/

        foreach($results->ReadMultiple_Result->loanTypeProduct as $loan){
             if(isset($loan->Description)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                 $data[]= [
                    'Code'=>$loan->Code,
                     'Description'=>$loan->Description
                 ];
            }

        }
        return $data;
    }
    //get loan history: written by Njambi Francis in oyo state, Nigeria Jan 24th 2019, @ 0114... damn
    public function actionLoanhistory(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $employee = $identity->No;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('loanhistory');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Employee_No', 'Criteria' =>$employee];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);//add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->loanRequestList)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->loanRequestList as $k=>$v){
                ++$counter;

                $loancard = '<a href="/loan/loancard?req='.$v->Request_No.'" target="_blank">'.$v->Request_No.'</a>';


               


                if($v->Status == 'Open'){
                    $link = Html::a('Send for Approval',['/service/approvalrequest',
                        'app'=>$v->Request_No,
                        'type'=>\Yii::$app->params['RequestType']['Loan'],//Loan
                    ],['class'=>'btn btn-sm btn-primary']);
                }
                else{
                    $link = 'Approval Request Sent';
                }

                if($v->Status == 'Pending_Approval'){
                    $cancel_link = Html::a('Cancel Request',['/service/cancelapprovalrequest',
                        'app'=>$v->Request_No,
                        'type'=>\Yii::$app->params['RequestType']['Loan'],//Loan
                    ],['class'=>'btn btn-sm btn-warning']);
                }
                else if($v->Status == 'Released'){
                   
                    $cancel_link = '<a href="#" class="btn btn-sm btn-success" disabled>Released</a>';
                }
                else if($v->Status == 'Open'){
                        
                    $cancel_link = '<a href="#" class="btn btn-sm btn-info" disabled>Open Request</a>';
                }
                else if($v->Status == 'Rejected'){
                    
                    $cancel_link = '<a href="#" class="btn btn-sm btn-danger" disabled>Rejected</a>';
                }


                $data[] =  [
                    $v->Key,
                    $counter,
                    $loancard,
                    $v->Request_Date,
                    ucfirst($v->Employee_Name),
                    number_format($v->Amount,2),
                    $v->Repayment_Period,
                    $v->Status,
                    $link,
                    $cancel_link,

                ];
            }
        }



        return $data;
    }
    //get benefit types
    function actionBenefits()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('employeeBenefits');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        //$filter = ['Field' => 'Countries_Filter', 'Criteria' => ''];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->loanTypeProduct); exit;*/

        foreach($results->ReadMultiple_Result->employmentBenefits as $benefit){
            if(isset($benefit->Description)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$benefit->Code,
                    'Description'=>$benefit->Description
                ];
            }

        }
        return $data;
    }


     public function actionBenefithistory(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $employee = $identity->No;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('benefithistory');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Employee_No', 'Criteria' =>$employee];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);//add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Benefits_Request)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Benefits_Request as $k=>$v){
                ++$counter;

                $benefitcard = '<a href="/benefit/benefitcard?req='.$v->Request_No.'" target="_blank">'.$v->Request_No.'</a>';


               


                if($v->Status == 'Open'){
                    $link = Html::a('Send for Approval',['/service/approvalrequest',
                        'app'=>$v->Request_No,
                        'type'=>\Yii::$app->params['RequestType']['Benefit'],//Benefit
                    ],['class'=>'btn btn-sm btn-primary']);
                }
                else{
                    $link = 'Approval Request Sent';
                }

                if($v->Status == 'Pending_Approval'){
                    $cancel_link = Html::a('Cancel Request',['/service/cancelapprovalrequest',
                        'app'=>$v->Request_No,
                        'type'=>\Yii::$app->params['RequestType']['Benefit'],//Loan
                    ],['class'=>'btn btn-sm btn-warning']);
                }
                else if($v->Status == 'Released'){
                   
                    $cancel_link = '<a href="#" class="btn btn-sm btn-success" disabled>Released</a>';
                }
                else if($v->Status == 'Open'){
                        
                    $cancel_link = '<a href="#" class="btn btn-sm btn-info" disabled>Open Request</a>';
                }
                else if($v->Status == 'Rejected'){
                    
                    $cancel_link = '<a href="#" class="btn btn-sm btn-danger" disabled>Rejected</a>';
                }


                $data[] =  [
                    $v->Key,
                    $counter,
                    $benefitcard,
                    $v->Request_Date,
                    ucfirst($v->Employee_Name),
                    $v->Status,
                    $link,
                    $cancel_link,

                ];
            }
        }



        return $data;
    }
    //duty stations
    function actionDstations()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('dimensionvalues');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = ['Field' => 'Global_Dimension_No', 'Criteria' => '6'];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
       /* print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/

        foreach($results->ReadMultiple_Result->dutystations as $d){
            if(isset($d->Name)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->Code,
                    'Description'=>$d->Name
                ];
            }

        }
        return $data;
    }
    //get a duty station
    function getStation($code)
    {
        /*if(!is_int($code)){
            var_dump($code);
        }*/
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('dimensionvalues');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = [
            ['Field' => 'Global_Dimension_No', 'Criteria' => '6'],
            ['Field' => 'Code', 'Criteria' => $code]

        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
      if(property_exists($results->ReadMultiple_Result, 'dutystations')){
        $result = $results->ReadMultiple_Result->dutystations[0];
     }

        $ds = property_exists($results->ReadMultiple_Result, 'dutystations')?$result->Name:'Duty Station Not Set';
        
        return $ds;
    }
    //get units
    function actionUnits()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('dimensionvalues');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = ['Field' => 'Global_Dimension_No', 'Criteria' => '7'];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->loanTypeProduct); exit;*/

        foreach($results->ReadMultiple_Result->dutystations as $d){
            if(isset($d->Name)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->Code,
                    'Description'=>$d->Code.' - '.$d->Name
                ];
            }

        }
        return $data;
    }
    //get Directorates ->dim 1
    function actionDirectorates()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('dimensionvalues');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = ['Field' => 'Global_Dimension_No', 'Criteria' => '1'];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->loanTypeProduct); exit;*/

        foreach($results->ReadMultiple_Result->dutystations as $d){
            if(isset($d->Name)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->Code,
                    'Description'=>$d->Code.' - '.$d->Name
                ];
            }

        }
        return $data;
    }
    //get costcenters ->dim-> 2
    function actionCenters()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('dimensionvalues');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = ['Field' => 'Global_Dimension_No', 'Criteria' => '2'];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
       /* print '<pre>';
        print_r($results->ReadMultiple_Result->dutystations); exit;*/

        foreach($results->ReadMultiple_Result->dutystations as $d){
            if(isset($d->Name)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->Code,
                    'Description'=>$d->Code.' - '.$d->Name
                ];
            }

        }
        return $data;
    }
    //get employee beneficiaries
    function actionBeneficiaries()
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $empcode = Yii::$app->user->identity->No;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('employee_beneficiaries');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = ['Field' => 'Employee_Code', 'Criteria' => $empcode];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->beneficiaries); exit;*/

        foreach($results->ReadMultiple_Result->beneficiaries as $benefitiary){
            if(isset($benefitiary->SurName)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$benefitiary->SurName,
                    'Description'=>$benefitiary->Other_Names.' '.$benefitiary->SurName.'( '.$benefitiary->Relationship.' )',
                ];
            }

        }
        return $data;
    }
    //Get Benefit Lines
    public function actionBenefitlines($doc){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $doc;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('benefit_lines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Doc_No', 'Criteria' =>$doc_no];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);//add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->benefitLines)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->benefitLines as $k=>$v){
                ++$counter;
                $currency = isset($v->Currency)?$v->Currency:'Not set';
                $amt_lcy = isset($v->Amount_LCY)?$v->Amount_LCY:'Not set';
                $dependant = isset($v->Dependant)?$v->Dependant:'Not set';

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Doc_No,
                    $v->Description,
                    number_format($v->Amount,2),
                    $v->Entitlement,
                    $dependant,
                    $v->Balance,
                    $v->Pay_To,
                    $currency,
                    $amt_lcy

                ];
            }

        }



        return $data;
    }

    //Get Casual Lines
    public function actionCasualslines($doc){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $doc;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('casualLines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Code', 'Criteria' =>$doc_no];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);//add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->casualLines)){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->casualLines as $k=>$v){
                ++$counter;
                    $casual_name = ucwords($v->First_Name.' '.$v->Middle_Name.' '.$v->Last_Name);
                    $nok = ucwords($v->NOK_First_Name.' '.$v->NOK_Middle_Name.' '.$v->NOK_Last_Name);

                $data[] =  [
                    $v->Key,
                    $counter,
                    $casual_name,
                    $v->Cellular_Phone_Number,
                    $nok,
                    $v->NOK_Cellular_Phone_Number

                ];
            }

        }



        return $data;
    }

    //Get job requirements Lines
    public function actionJobrequirementslines($jobid){


        if(\Yii::$app->request->get('type')){
             $username = 'CGIARAD\FNjambi';
                $password = 'Pass20$Cis';
        }
        else{
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
    }
        

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('JobRequirements');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Job_Id', 'Criteria' =>$jobid];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Job_Requirements)){
           
            foreach($results->ReadMultiple_Result->Job_Requirements as $k=>$v){
                ++$counter;
               
                if(isset($v->Qualification)){
                    $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Qualification_Type,
                    $v->Qualification,
                    $v->Score_ID,
                    $v->Priority,
                    '<a href="/service/deletereq/?key='.$v->Key.'" title="Delete Requirement"  class="del-req btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>'

                ];
                }
                
            }

        }



        return $data;
    }

    //Get job responsibilities Lines
    public function actionJobresponsibilitieslines($jobid){

        if(\Yii::$app->request->get('type')){
             $username = 'CGIARAD\FNjambi';
                $password = 'Pass20$Cis';
        }else{
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
    }
        

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('KeyResponsibilities');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Job_ID', 'Criteria' =>$jobid];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->Job_Responsibilities); exit;*/
        $counter = 0;
        if(property_exists($results->ReadMultiple_Result,'Job_Responsibilities')){
           
            foreach($results->ReadMultiple_Result->Job_Responsibilities as $k=>$v){
                ++$counter;
               

                $data[] =  [
                    $v->Key,
                    $counter,
                    isset($v->Responsibility)?$v->Responsibility:'Not Set',
                    isset($v->Remarks)?$v->Remarks:' Not Set',
                    '<a href="/service/deleteresp/?key='.$v->Key.'" title="Delete Responsibility"  class="del-resp btn btn-outline btn-danger"><i class="fa fa-trash"></i>
</a>'
                    

                ];
            }

        }



        return $data;
    }

    public function actionOvertimelines($code){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('overtimelines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Code', 'Criteria' =>$code];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Overtime_Lines)){
            
            foreach($results->ReadMultiple_Result->Overtime_Lines as $k=>$v){
                ++$counter;
               /* $currency = isset($v->Currency)?$v->Currency:'Not set';
                $amt_lcy = isset($v->Amount_LCY)?$v->Amount_LCY:'Not set';
                $dependant = isset($v->Dependant)?$v->Dependant:'Not set';*/
                $otcode = isset($v->Overttime_Code)?$v->Overttime_Code:'Not Set';
                $cc = isset($v->Cost_Center)?$v->Cost_Center:'Not Set';
                $update = Html::a('Update Line',['./overtime/update','id'=>$v->Line_No],['id'=>$v->Line_No,'class'=>'btn btn-sm btn-info update_line']);
                $delete = '<a href="/overtime/deleteline/?key='.$v->Key.'&name='.$v->Employee_Name.'" title="Remove Line"  class="delete btn btn-danger"><i class="fa fa-trash"></i>';

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Line_No,
                    $v->Employee_No,
                    $v->Employee_Name,
                    number_format($v->Hours_Normal,2),
                    number_format($v->Hours_Double,2),
                    $cc,
                    $v->Amount,
                    $update,
                    $delete,


                ];
            }

        }



        return $data;
    }
    public function actionConsultancypaymentlines($code){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('consultancylines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Code', 'Criteria' =>$code];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Consultancy_Lines)){
            
            foreach($results->ReadMultiple_Result->Consultancy_Lines as $k=>$v){
                ++$counter;
               /* $currency = isset($v->Currency)?$v->Currency:'Not set';
                $amt_lcy = isset($v->Amount_LCY)?$v->Amount_LCY:'Not set';
                $dependant = isset($v->Dependant)?$v->Dependant:'Not set';*/

                $data[] =  [
                    $v->Key,
                    $counter,
                    //$v->Line_No,
                    isset($v->Employee_No)?$v->Employee_No:'Not Set',
                     isset($v->Employee_Name)?$v->Employee_Name:'Not Set',
                    number_format($v->Amount,2),
                    //number_format($v->Hours_Double,2),
                    //$v->Amount,

                ];
            }

        }



        return $data;
    }
    public function actionCasualspaymentlines($code){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('casualpaymentLines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Code', 'Criteria' =>$code];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Casual_Lines)){
            
            foreach($results->ReadMultiple_Result->Casual_Lines as $k=>$v){
                ++$counter;
               /* $currency = isset($v->Currency)?$v->Currency:'Not set';
                $amt_lcy = isset($v->Amount_LCY)?$v->Amount_LCY:'Not set';
                $dependant = isset($v->Dependant)?$v->Dependant:'Not set';*/

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Line_No,
                    isset($v->Employee_No)?$v->Employee_No:'Not Set',
                    isset($v->Name)?$v->Name:'Not Set',
                    isset($v->Calculated_Hours)?$v->Calculated_Hours:'Not Set',
                    isset($v->Actual_Hours)?$v->Actual_Hours:'Not Set',
                    isset($v->Overtime_Hours)?$v->Overtime_Hours:'Not Set',
                    isset($v->Arrears)?$v->Arrears:'No Arrears',
                    //number_format($v->Hours_Double,2),
                    //$v->Amount,

                ];
            }

        }
        return $data;
    }

    public function actionOvertimelist(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        //$doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('overtimelist');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
       // $filter = ['Field' => 'Code', 'Criteria' =>$code];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Overtime_List)){
            
            foreach($results->ReadMultiple_Result->Overtime_List as $k=>$v){
                ++$counter;
               /* $currency = isset($v->Currency)?$v->Currency:'Not set';
                $amt_lcy = isset($v->Amount_LCY)?$v->Amount_LCY:'Not set';
                $dependant = isset($v->Dependant)?$v->Dependant:'Not set';*/

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Code,
                    isset($v->Pay_Period)?$v->Pay_Period:'Not Set',
                    isset($v->Month)?$v->Month:'Not Set',
                    ($v->Computed)?'Computed':'Not Computed',
                    isset($v->Unit)?$v->Unit:'Not Set',
                    isset($v->Unit_Name)?$v->Unit_Name:'Not Set',
                    ($v->Paid)?'Paid':'Not Paid',
                    isset($v->Description)?$v->Description:'Not Set',
                    $v->Status,
                    Html::a('View Details',['/overtime/paymentcard',
                        'code'=>$v->Code,
                        //'type'=>1,//recruitment
                    ],['class'=>'btn btn-sm btn-primary'])

                ];
            }

        }

        return $data;
    }

    public function actionCasualspaymentlist(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        //$doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('casualspaymentlist');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);
        
        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Casualspayment_List)){
            
            foreach($results->ReadMultiple_Result->Casualspayment_List as $k=>$v){
                ++$counter;

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Code,
                    isset($v->Payroll_Period)?$v->Payroll_Period:'Not Set',
                    isset($v->Unit)?$v->Unit:'Not Set',
                    isset($v->Date_Prepared)?$v->Date_Prepared:'Not Computed',
                    isset($v->Unit_Name)?$v->Unit_Name:'Not Set',
                    //isset($v->User_ID)?$v->User_ID:'Not Set',
                    isset($v->Prepared_By)?$v->Prepared_By:'Not Set',
                    isset($v->Status)?$v->Status:'Not Set', 
                    Html::a('View Details',['/paycasuals/paymentcard',
                        'code'=>$v->Code,
                        //'type'=>1,//recruitment
                    ],['class'=>'btn btn-sm btn-primary'])                  
                    
                ];
            }

        }

        return $data;
    }

    public function actionConsultantspaymentlist(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        //$doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('consultancypaymentlist');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);
        
        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->Consultancypayment_List)){
            
            foreach($results->ReadMultiple_Result->Consultancypayment_List as $k=>$v){
                ++$counter;

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Code,
                    isset($v->Pay_Period)?$v->Pay_Period:'Not Set',
                    isset($v->Month)?$v->Month:'Not Set',
                    ($v->Computed)?'Computed':'Not Computed',
                    //isset($v->Unit_Name)?$v->Unit_Name:'Not Set',
                    //isset($v->User_ID)?$v->User_ID:'Not Set',
                    //isset($v->Prepared_By)?$v->Prepared_By:'Not Set',
                    //isset($v->Status)?$v->Status:'Not Set', 
                    Html::a('View Details',['/payconsultants/paymentcard',
                        'code'=>$v->Code,
                        //'type'=>1,//recruitment
                    ],['class'=>'btn btn-sm btn-primary']),                  
                    
                ];
            }

        }

        return $data;
    }
    public function getcountry($code){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Countries');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Code', 'Criteria' =>'='.$code];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        //print '<pre>';
        return $results->ReadMultiple_Result->Countries[0]->Name;
    }
    function actionEmployeelist()// employee list service;;; filter only those on probation
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('employeeList');
        $soapWsdl=$url->getUrl();
       // print_r($soapWsdl); exit;
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        //$filter = ['Field' => 'Global_Dimension_No', 'Criteria' => '2'];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result->EmployeeList); exit;*/

        foreach($results->ReadMultiple_Result->EmployeeList as $d){
            if(isset($d->FullName)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->No,
                    'Description'=>$d->FullName
                ];
            }

        }
        return $data;
    }
    function actionIssupervisedbyme($empno){ //for use with employee leave application
        $userid = \Yii::$app->user->identity->Id;
        $no = \Yii::$app->user->identity->No;
        $eligible = User::find()
                    ->where(['[Employee No_]'=>$empno,'[Approver ID]'=>$userid])
                    ->orWhere(['[Employee No_]'=>$no])
                    ->one();
                    /*print '<pre>';
                    var_dump($eligible);*/
                    if(is_object($eligible)){
                        return true;
                    }else{
                        return false;
                    }
    }
    function actionLeavesforrecall(){//leaves for recall using ordinary query -- perfect shit
        $recallable = $data = [];
        $leaves = LeaveApplication::find()
                    ->select('[Application No],[Employee No],[Leave Code],[Days Applied],[Employee Name],[Start Date],[End Date]')
                    ->Where(['[Leave Code]'=>'ANNUAL','[Status]'=>1])
                    ->orWhere(['[Leave Code]'=>'MATERNITY'])
                     //->andWhere(['<>','[Leave Code]','C-SECTION'])
                     //->andWhere(['[Status]'=>1])    
                     ->asArray()
                     ->all();
                    /* print'<pre>';
                     print_r($leaves);*/
                     foreach($leaves as $l){
                        if($this->actionIssupervisedbyme($l['Employee No'])){
                                $recallable[] = $l;
                         }
                     }

                     //return $recallable;
                    foreach($recallable as $d){
                            $data[] = [                    
                                'Code'=>$d['Application No'],
                                'Description'=>$d['Application No'].' | '.$d['Leave Code'].' | '.$d['Employee No'].' | '.$d['Employee Name'].' | '.date('m-d-Y',strtotime($d['Start Date'])).' | '.date('m-d-Y',strtotime($d['End Date'])).' | '.intval($d['Days Applied'])
                            ];
                    }
                     
                     return $data;
                    
    }
    //get leaves for recall
    public function actionRleaves(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $employee = $identity->No;


        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;

        $trigger = (object)[];
        $trigger->sVEmpNo = $employee;

        //$url= new Services('codeunit');
        $url = "http://iita-navdb.cgiarad.org:7047/DynamicsNAV110/WS/IITA/Codeunit/CUnit_Factory";
        $soapWsdl=$url;//->getUrl();
        $entry = $trigger;

         if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $recall_trigger = Yii::$app->navision->get_recall_leaves($creds, $soapWsdl,$entry);

        /*print '<pre>';
        print_r($recall_trigger);
        exit;*/
        if(is_object($recall_trigger)){
            //print $balance_status->return_value;

                        $url= new Services('recallbuffer');
                        $soapWsdl=$url->getUrl();

                        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
                            throw new \yii\web\HttpException(503, 'Service unavailable');

                        }
                        //$filter = [];
                        //$filter = ['Field' => 'Employee_No', 'Criteria' => '='.$employee];
                        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

                        /*print '<pre>';
                        print_r($results);
                        exit;*/

                        if(!is_object($results->ReadMultiple_Result)){

                            Yii::$app->errorcatcher->catcherror($result);
                        }else{
                            $model = $results->ReadMultiple_Result->Recall_Buffer[0];
                            
                            $ls = ArrayHelper::map($results->ReadMultiple_Result->Recall_Buffer,'Leave_Application_No',
                                function($model) {
                                return $model->Leave_Application_No.' | '.$model->Leave_Code.' | '.$model->Employee_No.' | '.$model->Employee_Name.' | '.date('m-d-Y',strtotime($model->Start_Date)).' | '.date('m-d-Y',strtotime($model->End_Date)).' | '.intval($model->Days_Applied);
                        });
                            //$data = $ls;
                            foreach($ls as $k=>$v){
                                $data[] = [
                                    'Code'=>$k,
                                    'Description'=>$v
                                ];
                            }
                            
                                                      
                        }

                return $data;
                       
        }
        
    }
 public function actionSelectleave(){ 
 //leaves for recall using ws--- bure kabisa due to multi-filters insufficience use leavesforrecall above instead
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('leave');
        $soapWsdl=$url->getUrl();
       // print_r($soapWsdl); exit;
        $filter = [];
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = [
            'Field' => 'Status', 'Criteria' => 'Approved',
            //'Field' => 'Leave_Code','Criteria' => '<> MATERNITY',
            //'Field' => 'Leave_Code','Criteria' => '<> SICK',


    ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
       /* print '<pre>';
        print_r($results->ReadMultiple_Result->Leave_application); exit;*/

        foreach($results->ReadMultiple_Result->Leave_application as $d){

            if(isset($d->Application_No) && isset($d->Leave_Code)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->Application_No,
                    'Description'=>$d->Application_No.' | '.$d->Leave_Code.' | '.$d->Employee_No.' | '.$d->Employee_Name.' | '.$d->Start_Date.' | '.$d->End_Date.' | '.$d->Days_Applied
                ];
            }

        }
        return $data;
    }


    function actionPayperiod()// Select pay periods for patment processing ddown
    {
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('payperiods');
        $soapWsdl=$url->getUrl();
       // print_r($soapWsdl); exit;
        $filter = [];
        $filter = ['Field' => 'Closed', 'Criteria' => '0'];        
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        /*$filter = [
            'Field' => 'Status', 'Criteria' => 'Approved',
            'Field' => 'Leave_Code','Criteria' => '<> MATERNITY',
            
         ];*/
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
       

        foreach($results->ReadMultiple_Result->Payperiods as $d){

            if(isset($d->Starting_Date) && isset($d->Name)){
                //print $loan->Code.' - '.$loan->Description.'<br>';
                $data[]= [
                    'Code'=>$d->Starting_Date,
                    'Description'=>$d->Starting_Date.' - '.$d->Name
                ];
            }

        }
        return $data;
    }

    function actionLeavedetails()// employee list service;;; filter only those on probation
    {
        $p = (object)$_POST;
        $identity = \Yii::$app->user->identity;
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('leave');
        $soapWsdl=$url->getUrl();

        $leaveno = $p->leave_no;
       
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = ['Field' => 'Application_No', 'Criteria' => $leaveno];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

       
        return $results->ReadMultiple_Result->Leave_application[0];
    }


    public function actionRecallvalidation(){// to do now
        $this->enableCsrfValidation = false;
        $p = (object)$_POST;

        //$record = $this->actionRecallentry($p->Leave_Application);

        /*print '<pre>';
        print_r($record->Key);
        exit;*/

        $recall = (object)[];
        $recall->No_of_Off_Days = floatval($p->recalled_days);
        $recall->Leave_Application = $p->Leave_Application;
        $recall->Recalled_From = $p->recall_from;
        $recall->Key = $p->key;
        
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services('leaveRecallCard');
        $soapWsdl = $url->getUrl();
        $entry = $recall;


        $entryID = 'Leave_Recall_Card';
        $result = Yii::$app->navision->updateEntry($creds, $soapWsdl, $entry, $entryID);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (is_object($result)) {

            //$msg = "This leave is not applicable to you. Please check";
            
            return $result;

        } else {
            Yii::$app->errorcatcher->catcherror($result);
            return $result;
            //catch error
                

        }
    }

    //get recall entry

    public function actionRecallentry($leaveno){// to do now
         $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $req = $leaveno;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('leaveRecallCard');

        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'Leave_Application', 'Criteria' => $leaveno];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        if(!is_object($results)){

            return['error'=>$results];
        }else{
            return $results->ReadMultiple_Result->Leave_Recall_Card[0];
        }

        
    }


    public function actionRecallcomment(){
        $this->enableCsrfValidation = false;
        $p = (object)$_POST;

        $recall = (object)[];
        

        $record = $this->getRecallrecord($p->No);

        /*print '<pre>';
        print_r($record); exit;*/

        $recall->Reason_for_Recall = $p->Reason_for_Recall;
        $recall->No = $record->No;
        $recall->Key = $record->Key;
        //$recall->Leave_Application = $p->Leave_Application;
        
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services('leaveRecallCard');
        $soapWsdl = $url->getUrl();
        $entry = $recall;


        $entryID = 'Leave_Recall_Card';
        $result = Yii::$app->navision->updateEntry($creds, $soapWsdl, $entry, $entryID);
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (is_object($result)) {
            
            return ['status'=>1,'note'=>'Leave Recall Reason Submitted Successfully.'];

        } else {
            Yii::$app->errorcatcher->catcherror($result);
            return ['status'=>0,'note'=>'Error Submitting Leave Recall Reason Request : '.$result];
                                      
        }
    }
 
    public function getRecallrecord($no){
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
         $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('leaveRecallCard');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'No', 'Criteria' =>$no];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        if(is_object($results)){
            return $results->ReadMultiple_Result->Leave_Recall_Card[0];
        }else{
            return $results;
        }
        
    }

    //comments on approval entries
    public function actionAddcomment()
    {
        $this->enableCsrfValidation = false;
        $params = Yii::$app->request->post();

        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;


        $Comment = (object)[];
        $Comment->{'Comment'} = $params['Comment'];
       // $Comment->{'Entry_No'} = intval($params['Entry_No']);
        $Comment->{'Document_No'} = $params['Document_No'];
        $Comment->{'Portal'} = true;
        $Comment->{'Table_ID'} = $params['Table_ID'];
        $Comment->{'Workflow_Step_Instance_ID'} = $params['Workflow_Step_Instance_ID'];
        $Comment->{'Record_ID_to_Approve'} = $params['Record_ID_to_Approve'];


        $url = new Services('approvalComments');
        $soapWsdl = $url->getUrl();
        
        $entry = $Comment;
        $entryID = 'ApprovalComments';
        $result = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);

        if (is_object($result)) {
             $msg = ['res'=>1,'note'=>'Comment Added Successfully .'];
            return $msg;
        } else {

           // $msg = "False";
            $msg = ['res'=>0,'note'=>'Error Adding Note: '.$result];
            return $msg;
        }

    }
    //get recruitment panel
    public function actionPanel($needid){
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
         $url = new Services('recruitmentpanel');
         $creds = (object)[];
         $creds->UserName = $username;
        $creds->PassWord = $password;
         $soapWsdl = $url->getUrl();
         
         if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter=[];
         $filter = ['Field' => 'Recruitment_Need', 'Criteria' =>$needid];
         $data=[];
         $index = 0;
         $resutls=Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
         if(isset($resutls->ReadMultiple_Result->RecruitmentPanel)){
             foreach( $resutls->ReadMultiple_Result->RecruitmentPanel as $panel)
             {
                $name = isset($panel->Interviewer_Name)?$panel->Interviewer_Name:'Not Set';
                ++$index;
                 $data[]= [
                    $panel->Key,
                    $index,
                    $panel->Recruitment_Need,
                    $panel->Interview_Type,
                    //$panel->Interviewer,
                    isset($panel->Interviewer_Name)?$panel->Interviewer_Name:'Not Set',
                    isset($panel->Employee_Number)?$panel->Employee_Number:'Not Set',
                    isset($panel->Directorate)?$panel->Directorate:'Not Set',
                    isset($panel->Date_Created)?$panel->Date_Created:'Not Set',
                    '<a href="/recruitment/deletepanelmember/?key='.$panel->Key.'&name='.$name.'" title="Remove Panel Member"  class="delete-panel-member btn btn-danger"><i class="fa fa-trash"></i>',
                 ];
             }
         }
         return $data;
    }
    public function actionMandatorydocs($needid){
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
         $url = new Services('mandatorydocs');
         $creds = (object)[];
         $creds->UserName = $username;
        $creds->PassWord = $password;
         $soapWsdl = $url->getUrl();
         
         if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter=[];
         $filter = ['Field' => 'Recruitment_Req_No', 'Criteria' =>$needid];
         $data=[];
         $index = 0;
         $results=Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);
         if(isset($results->ReadMultiple_Result->MandatoryDocuments)){
             foreach( $results->ReadMultiple_Result->MandatoryDocuments as $panel)
             {
                ++$index;
                 $data[]= [
                    $panel->Key,
                    $index,
                    $panel->Line_No,
                    $panel->Document_Description,
                    
                    '<a href="/recruitment/deletedocument/?key='.$panel->Key.'&name='.$panel->Document_Description.'" title="Mandatory Document Removal"  class="delete-panel-member btn btn-danger"><i class="fa fa-trash"></i>',
                 ];
             }
         }
         return $data;
    }

    public function actionRecruitmentstages()
    {
        
        $identity = \Yii::$app->user->identity;

        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('recruitmentstages');
        $soapWsdl=$url->getUrl();
        //exit($soapWsdl);

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
       //print '';

        if($results->ReadMultiple_Result->RecruitmentStages){
            $ls = ArrayHelper::map($results->ReadMultiple_Result->RecruitmentStages,'Recruitement_Stage','Description');
        }

        foreach($ls as $k=>$v){
            
            $data[]= ['Code'=>$k,'Description'=>$v];
        }

        return $data;
    }
    //get interview types
     public function actionInterviewtypes()
    {
        
        $identity = \Yii::$app->user->identity;

        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('recruitmentstages');
        $soapWsdl=$url->getUrl();
        //exit($soapWsdl);

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl);

        $data = [];
       //print '';

        if($results->ReadMultiple_Result->RecruitmentStages){
            $ls = ArrayHelper::map($results->ReadMultiple_Result->RecruitmentStages,'Recruitement_Stage','Description');
        }

        foreach($ls as $k=>$v){
            
            $data[]= ['Code'=>$k,'Description'=>$v];
        }

        return $data;
    }
    //get shortlisting criterion
    public function actionShortlistingcriterion($applicationid){
        $jobid = \Yii::$app->recruitment->getJobid($applicationid);
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;        
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('shortlistinglines');
        $soapWsdl=$url->getUrl();
        
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = (object)[];
        $filter = [
            ['Field' => 'Recruitment_Code', 'Criteria' => $jobid],
            ['Field' => 'User_ID', 'Criteria' => $username],
            ['Field' => 'Applicant_ID', 'Criteria' => $applicationid]
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

       
        $counter = 0;
        $data = [];
        if(isset($results->ReadMultiple_Result->ShortListingLines)){
            
            foreach($results->ReadMultiple_Result->ShortListingLines as $k=>$v){
                ++$counter;
                if($v->Score_Marks > 0 && !$v->Submitted){
                        $link = Html::a('Update Score',['/recruitment/shortlistscore',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Code'=>$v->Code,
                        
                    ],['class'=>'btn btn-sm btn-primary addscore']);

                }else if($v->Score_Marks < 1 && !$v->Submitted){
                        $link = Html::a('Add Score',['/recruitment/shortlistscore',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Code'=>$v->Code,
                        
                    ],['class'=>'btn btn-sm btn-warning addscore']);
                }
                else if($v->Submitted){
                    $link = Html::a('Submitted',['/recruitment/Submitshortlist',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        
                    ],['class'=>'btn btn-sm btn-info submitted']);
                }
                 
                $data[] =  [                    
                    $counter,
                    isset($v->Description)?$v->Description:'Not Set',
                    isset($v->Maximamum_Score)?$v->Maximamum_Score:'Not Set',
                    $v->Score_Marks,
                    $link,                   
                    
                ];
            }

        }
         /*print '<pre>';
        print_r($results); exit;*/
        return $data;

    }
//Oral Interview Line
    public function actionInterviewlines($applicationid){
        $jobid = \Yii::$app->recruitment->getJobid($applicationid);
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;        
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('ApplicantInterviewLines');
        $soapWsdl=$url->getUrl();
        
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = (object)[];

        ///make this function display oral interview quations by filtering using : Interview_Type
        $filter = [
            ['Field' => 'Recruitment_Code', 'Criteria' => $jobid],
            ['Field' => 'User_ID', 'Criteria' => $username],
            ['Field' => 'Applicant_ID', 'Criteria' => $applicationid],
            ['Field' => 'Interview_Type', 'Criteria' => 'Oral_Interview']
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

       
        $counter = 0;
        $data = [];
        if(isset($results->ReadMultiple_Result->ApplicantInterviewLines)){
            
            foreach($results->ReadMultiple_Result->ApplicantInterviewLines as $k=>$v){
                ++$counter;
                if($v->Score_Marks > 0 && !$v->Submitted){
                        $link = Html::a('Update Score',['/recruitment/interviewscore',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Description'=>$v->Description,
                        'Code'=>$v->Code,
                        'Key'=>$v->Key,
                        
                    ],['class'=>'btn btn-sm btn-primary addscore']);

                }else if($v->Score_Marks < 1 && !$v->Submitted){
                        $link = Html::a('Add Score',['/recruitment/interviewscore',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Description'=>$v->Description,
                        'Code'=>$v->Code,
                        'Key'=>$v->Key,
                        
                    ],['class'=>'btn btn-sm btn-warning addscore']);
                }
                else if($v->Submitted){
                    $link = Html::a('Submitted',['#',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Description'=>$v->Description
                        
                    ],['class'=>'btn btn-sm btn-info submitted']);
                }
                 
                $data[] =  [                    
                    $counter,
                    isset($v->Description)?$v->Description:'Not Set',
                    $v->Score_Marks,
                    isset($v->Maximamum_Score)?$v->Maximamum_Score:'Not Set',
                    $link,                   
                    
                ];
            }

        }
         /*print '<pre>';
        print_r($results); exit;*/
        return $data;

    }
    //tech interview criteria lines
    public function actionTechinterviewlines($applicationid){
        $jobid = \Yii::$app->recruitment->getJobid($applicationid);
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;        
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('ApplicantInterviewLines');
        $soapWsdl=$url->getUrl();
        
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = (object)[];

        ///make this function display oral interview quations by filtering using : Interview_Type
        $filter = [
            ['Field' => 'Recruitment_Code', 'Criteria' => $jobid],
            ['Field' => 'User_ID', 'Criteria' => $username],
            ['Field' => 'Applicant_ID', 'Criteria' => $applicationid],
            ['Field' => 'Interview_Type', 'Criteria' => 'Technical_Interview']
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

       
        $counter = 0;
        $data = [];
        if(isset($results->ReadMultiple_Result->ApplicantInterviewLines)){
            
            foreach($results->ReadMultiple_Result->ApplicantInterviewLines as $k=>$v){
                ++$counter;
                if($v->Score_Marks > 0 && !$v->Submitted){
                        $link = Html::a('Update Score',['/recruitment/interviewscore',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Description'=>$v->Description,
                        'Code'=>$v->Code,
                        'Key'=>$v->Key,
                        
                    ],['class'=>'btn btn-sm btn-primary techaddscore']);

                }else if($v->Score_Marks < 1 && !$v->Submitted){
                        $link = Html::a('Add Tech Score',['/recruitment/interviewscore',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Description'=>$v->Description,
                        'Code'=>$v->Code,
                        'Key'=>$v->Key,
                        
                    ],['class'=>'btn btn-sm btn-warning techaddscore']);
                }
                else if($v->Submitted){
                    $link = Html::a('Submitted',['#',
                        'Maximamum_Score'=>$v->Maximamum_Score,
                        'Line_Number'=>$v->Line_Number,
                        'Description'=>$v->Description
                        
                    ],['class'=>'btn btn-sm btn-info submitted']);
                }
                 
                $data[] =  [                    
                    $counter,
                    isset($v->Description)?$v->Description:'Not Set',
                    $v->Score_Marks,
                    isset($v->Maximamum_Score)?$v->Maximamum_Score:'Not Set',
                    $link,                   
                    
                ];
            }

        }
         /*print '<pre>';
        print_r($results); exit;*/
        return $data;

    }

    //update (Employee Management )nok lines
    public function actionEmpmgtlines($doc=""){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $doc;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('em_lines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        if(!strlen($doc)){
            return 'Document No not Set';
        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Header_No', 'Criteria' =>$doc_no];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        $data = [];
        /*print '<pre>';
        print_r($results->ReadMultiple_Result); exit;*/
        $counter = 0;
        if(property_exists($results->ReadMultiple_Result,'Update_Lines')){
            //$ls = ArrayHelper::map($results->ReadMultiple_Result->Applicant_Qualification,'Key','Applicant_No','Description','From_Date','To_Date','Institution_Company');
            foreach($results->ReadMultiple_Result->Update_Lines as $k=>$v){
                ++$counter;
               

                $data[] =  [
                    isset($v->Key)?$v->Key:'Not set',
                    $counter,
                    isset($v->N_O_K_Name)?$v->N_O_K_Name:'Not set',
                    isset($v->N_O_K_Date_Of_Birth)?$v->N_O_K_Date_Of_Birth:'Not set',
                    isset($v->N_O_K_Phone_Number)?$v->N_O_K_Phone_Number:'Not set',
                    isset($v->N_O_K_Email)?$v->N_O_K_Email:'Not set',
                    isset($v->N_O_K_Relationship)?$v->N_O_K_Relationship:'Not set',
                    isset($v->ID_No_Passport_Non)?$v->ID_No_Passport_No:'Not set',
                    isset($v->Occupation)?$v->OccupatioN:'Not set',
                    isset($v->Remarks)?$v->Remarks:'Not set',
                    isset($v->Percentage_Benefit)?$v->Percentage_Benefit:'Not set',
                    isset($v->Header_No)?$v->Header_No:'Not set',

                ];
            }

        }



        return $data;
    }
    public function actionGetupdatedocno(){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('em_update');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        
       $update = (object)[];
       $update->Employee_No = \Yii::$app->request->get('Employee_No');

        $entry = $update;

        $entryID = 'EM_Update_Header';
        $results = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);
        //return $results;

        if(property_exists($results,'EM_Update_Header')){
            return $results->EM_Update_Header;
        }
    }
    

    //ot approval lines
    public function actionOtapprovallines($code,$hcc){
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services('ot_approval_lines');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = [
            ['Field' => 'Code', 'Criteria' =>$code],
             ['Field' => 'Head_of_CC', 'Criteria' =>$hcc],

        ];

        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        //add the filter so you don't display all loans to all and sundry.... just logic!!!

        $data = [];
        
        $counter = 0;
        if(isset($results->ReadMultiple_Result->OT_Approval_Lines)){
            
            foreach($results->ReadMultiple_Result->OT_Approval_Lines as $k=>$v){
                ++$counter;
               
                $otcode = isset($v->Code)?$v->Code:'Not Set';
                $cc = isset($v->Cost_Center)?$v->Cost_Center:'Not Set';

                $data[] =  [
                    $v->Key,
                    $counter,
                    $v->Employee_No,
                    $v->Employee_Name,
                    $v->Hours,
                    $v->Date,
                    $v->Head_of_CC,
                    $v->Cost_Center,

                ];
            }

        }



        return $data;
    }


    public function actionTest() {

        $filter = [
            ['Field' => 'Code', 'Criteria' =>$code],
             ['Field' => 'Head_of_CC', 'Criteria' =>$hcc],

        ];
        $this->Joseph('ot_approval_lines', $fiter);
    }
    //test 2
    public function actionTest2() {       
       
        $this->Joseph('ot_approval_lines', Yii::$app->request->get());
    }
    //dub
     public static function Joseph($service, $params = []) {
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;
        $doc_no = $code;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url = new Services($service);
        $soapWsdl=$url->getUrl();

        $filter = [];
        foreach($params as $key => $value){
            $filter[] = ['Field' => $key, 'Criteria' =>$value];
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
}
