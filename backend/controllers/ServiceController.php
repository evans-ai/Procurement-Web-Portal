<?php

namespace backend\controllers;

//use app\models\Recruitmentneeds;
use phpDocumentor\Reflection\DocBlock\Description;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use yii\filters\AccessControl;
use Yii;
use yii\web\Controller;
use app\models\AssignmentMatrix;
use app\models\Employee;
use kartik\mpdf\Pdf;
use app\models\Company;
use app\models\Services;
use app\controllers\BiometricsController;
class ServiceController extends Controller
{

    public function beforeAction($action)
    {
        if (isset(Yii::$app->user->identity->Pass)) {
            if (!Yii::$app->user->identity->Pass) {

                $this->actionLogout();
                return false;

            } 
        }
        if(isset(Yii::$app->user->identity->No)){
            if(Yii::$app->user->identity->No==""){
                throw new \yii\web\HttpException(503, "Your profile is not complete. Contact system admin");
            }

        }

        return true;
    }




    

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                //'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index','insertcasuals'],
                        'allow' => true,
                        'roles' => ['@']
                    ],
                    [
                        'actions' => ['recruitment', 'traininghistory', 'newrecruitment', 'newtraining', 'payslip', 'approve',
                            'approverecruitment'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionRecruitment()
    {
        return $this->render('recruitment');
    }

    public function actionTraininghistory()
    {
        return $this->render('traininghistory');
    }

    public function actionNewrecruitment()
    {
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;

        $session = \Yii::$app->session;
        //$session->remove('rec_header');

        $params = Yii::$app->request->post();
        $getparams = Yii::$app->request->get();
        if ($getparams) {
            //Array ( [id] => KR-00005 [action] => view )
            $url = new Services('recruitment');
            $soapWsdl = $url->getUrl();
            if (!Yii::$app->navision->isUp($soapWsdl)) {
                throw new \yii\web\HttpException(503, 'Service unavailable');
            }
            $filter = ['Field' => 'No', 'Criteria' => '=' . $getparams['id']];
            $results = Yii::$app->navision->readEntry($creds, $soapWsdl, $filter);
            // print_r($results); exit;
            if (is_object($results)) {
                foreach ($results as $key => $result) {
                    //$header = $result;
                    foreach ($result as $key => $lines) {
                        foreach ($lines as $key => $line) {
                            $header = $line;
                        }
                    }
                }
                //print_r($header); exit;
                return @$this->render('_viewrecruitment', ['header' => $header]);
            } else {
                return $this->render('rerror', ['results' => $results]);
            }
        }
        if ($params) {
	        $model = new Recruitmentneeds();
            // print_r($params); exit;
            //[jobid] => yes [existingnewposition] => yes [appointmenttype] => yes [reportingdate] => 2018-01-09 [department] => yes [reason] => yes )
            $recruitment = (object)[];
            $recruitment->{'Job_ID'} = $params['jobid'];
            $recruitment->{'Appointment_Type'} = $params['Appointment_Type'];
            $recruitment->{'Expected_Reporting_Date'} = $params['reportingdate'];
            $recruitment->{'Global_Dimension_1_Code'} = $params['department'];
            $recruitment->{'Reasons_for_Recruitment'} = $params['reason'];
            $recruitment->{'Positions'} = $params['positions'];
            $recruitment->{'Duty_Station'}=$params['Duty_Station'];
            $recruitment->{'Recruitment_Type'}=$params['Recruitment_Type'];
            $recruitment->{'Requisition_Nature'}=$params['Requisition_Nature'];
            $recruitment->{'Start_Date'} = $params['Start_Date'];
            $recruitment->{'End_Date'} = $params['End_Date'];
            //$recruitment->{'Description'} = $params['Description'];

            // $recruitment->{'Appointment_Type'}=$params['existingnewposition'];
            // $recruitment->{'Reason_for_Recruitment'}=$params['reason'];
	        //web service has no create method so we add manually


            $url = new Services('recruitment');
            $soapWsdl = $url->getUrl();
           // echo $soapWsdl; exit;
            if (!Yii::$app->navision->isUp($soapWsdl)) {
                throw new \yii\web\HttpException(503, 'Service unavailable');

            } else {
                $entry = $recruitment;
                $entryID = 'Recruitment_Request';
                $result = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);
                if (is_object($result)) {
                    $session->set('rec_header',$result);
                   // return $this->render('message', ['result' => $result]);
                Yii::$app->session->setFlash('success', " Recruitment Requsition Header <b>".$result->Recruitment_Request->No."</b> Created successfully.");
                 return $this->redirect(\Yii::$app->request->referrer);
                } else {
                    //return $this->render('rerror', ['result' => $result]);
                Yii::$app->session->setFlash('error', " Error Creating Recruitment Requsition Header : ".$result);
                return $this->redirect(\Yii::$app->request->referrer);
                }

            }
        }
        return $this->render('newrecruitment');
    }

    //Add job requirements lines
    public function actionRequirementlines(){
                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                if(\Yii::$app->request->post()){
                            $p = (object)$_POST;
                            $url= new Services('JobRequirements');
                        $soapWsdl=$url->getUrl();

                        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
                            throw new \yii\web\HttpException(503, 'Service unavailable');

                        }
                        $line = (object)[];
                        $line->Job_Id = $p->Job_Id;
                        $line->Qualification_Type = $p->Qualification_Type;
                        $line->Qualification = $p->Qualification;
                        $line->Score_ID = $p->Score_ID;
                        $line->Priority = $p->Priority;
                        $line->Qualification_Code = $p->Qualification_Code;

                        $entry = $line;
                        $entryID = 'Job_Requirements';
                        $result = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);

                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        if(is_object($result)){
                            return['message'=>1,'note'=>'<p class="alert alert-success">Job Requirement saved successfully.</p>'];
                        }else{
                            return['message'=>0,'note'=>'<p class="alert alert-danger">Error saving job requirement.'.$result.'</p>' ];
                        }
                
                }
                
    }

    //Add job Responsibility lines
    public function actionResponsibilitylines(){
                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                if(\Yii::$app->request->post()){
                            $p = (object)$_POST;
                            $url= new Services('KeyResponsibilities');
                        $soapWsdl=$url->getUrl();

                        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
                            throw new \yii\web\HttpException(503, 'Service unavailable');

                        }
                        $line = (object)[];
                        $line->Job_ID = $p->Job_ID;
                        $line->Responsibility = $p->Responsibility;
                        $line->Remarks = $p->Remarks;
                        

                        $entry = $line;
                        /*print '<pre>';
                        var_dump($entry);
                        exit;*/
                        $entryID = 'Job_Responsibilities';
                        $result = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);

                        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        if(is_object($result)){
                            /*print '<pre>';
                            var_dump($result);*/
                            return['message'=>1,'note'=>'<p class="alert alert-success">Job Responsibility saved successfully.</p>'];
                        }else{
                            return['message'=>1,'note'=>'<p class="alert alert-danger">Error saving job responsibility.'.$result.'</p>' ];
                        }
                
                }
                
    }

    public function actionDeletereq($key){
        //$username = 'System Support Eng';
        //$password = '@francis123';
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $url = new Services('JobRequirements');
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $soapWsdl = $url->getUrl();
        $result = Yii::$app->navision->deleteEntry($creds, $soapWsdl, $key);

        //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (is_object($result)) {
            Yii::$app->session->setFlash('success', "Job Requirement deleted successfully.");
            //return['message'=>1,'note'=>'<p class="alert alert-success">Job Requirement deleted successfully.</p>'];
        } else {
            Yii::$app->session->setFlash('error', "Error Deleting Job Requirement : ".$result);
            //return['message'=>1,'note'=>'<p class="alert alert-danger">Error Deleting Job Requirement : '.$result.'</p>' ];

        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteresp($key){
        //$username = 'System Support Eng';
        //$password = '@francis123';
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $url = new Services('KeyResponsibilities');
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $soapWsdl = $url->getUrl();
        $result = Yii::$app->navision->deleteEntry($creds, $soapWsdl, $key);

        if (is_object($result)) {
            Yii::$app->session->setFlash('success', "Job Responsibility deleted successfully.");
           // return['message'=>1,'note'=>'<p class="alert alert-success">Job Responsibility deleted successfully.</p>'];
        } else {
            Yii::$app->session->setFlash('error', "Error Deleting Job Responsibility : ".$result);
            //return['message'=>1,'note'=>'<p class="alert alert-danger">Error Deleting Job Responsibility : '.$result.'</p>' ];

        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleterequest($key){
        //$username = 'System Support Eng';
        //$password = '@francis123';
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $url = new Services('recruitment');
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $soapWsdl = $url->getUrl();
        $result = Yii::$app->navision->deleteEntry($creds, $soapWsdl, $key);

        
       
        if (is_object($result)) {
            Yii::$app->session->setFlash('success', "Job Request  deleted successfully.");
            
        } else {
            Yii::$app->session->setFlash('error', "Error Deleting Job Request : ".$result);

        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionResetform(){
         $session = \Yii::$app->session;
        $session->remove('rec_header');
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionPayslip()
    {
        /*$connection = \Yii::$app->db;
        $sql = "SELECT matrix.Code,matrix.Description, matrix.Amount,earn.[Earning Type],earn.[Reduces Taxable Amount],earn.[Non-Cash Benefit]
            FROM [IITA\$Assignment Matrix] matrix LEFT JOIN [IITA\$Earnings] earn
            ON earn.Code = matrix.Code WHERE matrix.[Employee No] = ".Yii::$app->user->identity->No;
            $model = $connection->createCommand($sql);
            $result = $model->queryAll();

            print '<pre>';
            print_r($result);

            exit;*/

        $params = Yii::$app->request->post();
        $taxRelief = 0;
        $totalDuctions = 0;
        $grossPay = 0;
        $i = 0;
        $deductions = [];
        $earnings = [];
        $Paye = 0;
        $netPay = 0;
        $pension = 0;//to be rewritten.
        if ($params) {
            //Setting payment period to first of this month.
            //Logo
            $image = Company::find()
                ->select('Picture')
                ->one();
//            echo $image['Picture']; EXIT;
//            header("Content-type: image/jpg");
//            echo $image['Picture'];
//return '<img src="data:image/jpeg;base64,'.base64_encode($identity->getappraiseesignature()).'" height="80" width="80" />';

            $empNo = Yii::$app->user->identity->No;
            $month = $params['month'] . '-1';

            $month = date_create($month);
            $payperiod = date_format($month, "Y-m-d H:i:s");//FMJ initializing $payperiod
            $Payperiod = $month->format('F') . ' ' . date_format($month, "Y");
            //Employee details
            if (array_key_exists('payperiod', $params)) {
                $payperiod = $params['payperiod'];
            }
            $rows = AssignmentMatrix::find()
                ->select(['Code', 'Description', 'Amount'])
                ->where(['Employee No' => $empNo])
                ->andWhere(['Payroll Period' => $payperiod])
                ->asArray()
                ->all();
            $empyoyee = Employee::find()
                ->select(['No_', '[First Name]', '[Middle Name]', '[Last Name]', '[N_H_I_F No]', '[PIN Number]', '[NSSF No_]'])
                ->where(['No_' => $empNo])
                ->asArray()
                ->one();
            $Name = $empyoyee['First Name'] . ' ' . $empyoyee['Middle Name'] . ' ' . $empyoyee['Last Name'];
            /*print '<pre>';
                print_r($rows);
                exit;*/


            $consolidated = 0.00;
            foreach ($rows AS $row) {
                //non tax benefit

                if($row['Description'] == 'Consolidated Relief'){
                    $consolidated = $row['Amount'];
                } 
                if($row['Description'] == 'Pension'){
                    $pension = $row['Amount'];
                } 
                if ($row['Code'] == 'RELIEF' ) {
                    $taxRelief = $row['Amount'];
                }
                if ($row['Code'] == 'PAYE') {
                    $Paye = $row['Amount'] * -1;
                }
                if (($row['Amount'] < 0)) {
                    $deductions[] = ['Description' => $row['Description'], 'Amount' => $row['Amount']];
                    $totalDuctions += $row['Amount'];
                }
                if (($row['Amount'] > 0 && $row['Description'] !== 'Consolidated Relief')) {
                    $earnings[] = ['Description' => $row['Description'], 'Amount' => $row['Amount']];
                    $grossPay += $row['Amount'];
                }//To add loans
            }
            $grossPay -= $taxRelief; //less tax relief
            $taxcharged = $Paye + $taxRelief;
            $netPay = $grossPay - (($totalDuctions * -1));

            if (array_key_exists('payperiod', $params)) {
                // print_r($rows); exit;

                $pdf = new Pdf([
                    'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
                    'content' => $this->renderPartial('_payslipdf', ['empyoyee' => $empyoyee, 'earnings' => $earnings, 'rows' => $rows,'pension'=>$pension,
                        'Payperiod' => $Payperiod, 'grossPay' => $grossPay, 'totalDuctions' => $totalDuctions, 'taxRelief' => $taxRelief,
                        'deductions' => $deductions, 'taxcharged' => $taxcharged, 'netPay' => $netPay,'image'=>$image['Picture']]),
                    'options' => [
                        'title' => '',
                        'subject' => '',
                    ],
                    'methods' => [
                        'SetHeader' => [''],
//                        'SetFooter' => [' '],
                    ],
                    'format' => pdf::FORMAT_TABLOID,
                    'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                ]);
                // $pdf->showImageErrors = true;
                return $pdf->render();
            }
            return $this->render('_payslip', ['empyoyee' => $empyoyee, 'earnings' => $earnings, 'rows' => $rows,
                'Payperiod' => $Payperiod, 'grossPay' => $grossPay, 'totalDuctions' => $totalDuctions, 'taxRelief' => $taxRelief,
                'deductions' => $deductions, 'taxcharged' => $taxcharged, 'netPay' => $netPay, 'payperiod' => $payperiod,'pension'=>$pension,'consolidated' => $consolidated]);
        }
        return $this->render('payslip');
    }

    public function actionNewtraining()
    {
        $params = YII::$app->request->post();
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $header = (object)[];
        $Training_Participants = (object)[];
        $getparams = Yii::$app->request->get();
        if ($getparams) {
            $url = new Services('trainingheader');
            $soapWsdl = $url->getUrl();
            if (!Yii::$app->navision->isUp($soapWsdl)) {
                throw new \yii\web\HttpException(503, 'Service unavailable');
            } else {
                $filter = ['Field' => 'Request_No', 'Criteria' => '=' . $getparams['id']];
                $results = Yii::$app->navision->readEntry($creds, $soapWsdl, $filter);
                if (is_object($results)) {
                    foreach ($results as $key => $result) {
                        //$header = $result;
                        foreach ($result as $key => $lines) {
                            foreach ($lines as $key => $line) {
                                $header = $line;
                                foreach ($line as $key => $lin) {
                                    $result = $lin;
                                }
                            }
                        }
                    }
                    //print_r($header); exit;
                    //print_r($result->Training_Participants[0]->Key);
                    return @$this->render('_newtraining', ['header' => $header, 'result' => $result]);
                } else {
                    echo "error";
                }
            }
        }
        /**********Header********/
        //[location] => LOCAL [sourceoffundidng] => Existing_Position [startdate] => 2018-02-02 [enddate] => 2018-02-03
        // [objective] => To prepare for exams [coursetitle] => introduction to exam [trainer] => V00001 [dateoftravel] => 2018-02-03
        // [traininginstitution] => Naivasha training institute [venue] => Naivasha
        if ($params) {
            // if (isset($params['requestypetype'])) {
            if (isset($params['requestypetype'])) {

                //print_r($params); exit;
                //switch ($params['type'])
                // {
                //  case "UPDATE":
                if (count($params['employeeID']) > 1) {
                    $header->{'Group_or_Individual'} = 'Group';
                } else {
                    $header->{'Group_or_Individual'} = 'Individual';
                }
                $header->{'Local_or_Abroad'} = $params['location'];
                $header->{'Source_of_Funding'} = $params['sourceoffundidng'];
                $header->{'Training_Objective'} = $params['objective'];
                //$header->{'Request_No'}=$params['trainingid'];
                $header->Key = $params['headerkey'];
                $header->{'Description'} = $params['coursetitle'];
                $header->{'Planned_Start_Date'} = $params['startdate'];
                $header->{'Planned_End_Date'} = $params['enddate'];
                $header->{'Date_of_Travel'} = $params['dateoftravel'];
                $header->{'Training_Insitution'} = $params['traininginstitution'];
                $header->{'Venue'} = $params['venue'];
                $header->{'Trainer_Code'} = $params['trainer'];
                $url = new Services('TrainingRequest');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');

                } else {
                    $entry = $header;
                    $entryID = 'TrainingRequest';
                    $result = Yii::$app->navision->updateEntry($creds, $soapWsdl, $entry, $entryID);
                    // print_r($result); exit;
                    $results = [];
                    if (is_object($result)) {
                        for ($i = 0; $i < count($params['employeeID']); $i++) {
                            if (!Empty($params['linekey'])) {
                                $Training_Participants->Key = $params['linekey'][$i];
                            } else {
                                $Training_Participants->{'Employee_No'} = $params['employeeID'][$i];
                            }
                            $Training_Participants->{'Need_Source'} = $params['needsource'][$i];
                            $Training_Participants->{'Per_Diem_Distribution'} = $params['perdiemdistribution'][$i];
                            $Training_Participants->{'Tuition_Fee_Distribution'} = $params['tuitionfeedistribution'][$i];
                            $Training_Participants->{'Training_Request'} = $params['trainingid'];
                            //if(!isEmpty($params['participantsid'])){$Training_Participants->Participant=$params['participantsid'];}
                            $Training_Participants->Directorate = 'ICT';
                            $Training_Participants->{'Period_End_Date'} = '2018-06-30';
                            $url = new Services('TrainingParticipants');
                            $soapWsdl = $url->getUrl();
                            $entry = $Training_Participants;
                            $entryID = 'TrainingParticipants';
                            $results[$i] = Yii::$app->navision->updateEntry($creds, $soapWsdl, $entry, $entryID);
                        }
                        if (is_array($results)) {
                            //echo "update";
                            print_r($results);
                            print_r($result);
                            exit;
                            $line = $results;
                            return $this->render('_newtraining', ['header' => $header, 'line' => $line]);
                        } else
                            // echo "eror";
                            echo $results;
                        exit;
                        // }
                        //break;
                    }
                    echo "updated";
                    print_r($results);
                    print_r($result);
                    exit;
                }
            }
            /********* posting header****/
            if (count($params['employeeID']) > 1) {
                $header->{'Group_or_Individual'} = 'Group';
            } else {
                $header->{'Group_or_Individual'} = 'Individual';
            }
            $header->{'Local_or_Abroad'} = $params['location'];
            $header->{'Source_of_Funding'} = $params['sourceoffundidng'];
            $header->{'Training_Objective'} = $params['objective'];
            //Coiurse title
            $header->{'Description'} = $params['coursetitle'];
            $header->{'Planned_Start_Date'} = $params['startdate'];
            $header->{'Planned_End_Date'} = $params['enddate'];
            $header->{'Date_of_Travel'} = $params['dateoftravel'];
            $header->{'Training_Insitution'} = $params['traininginstitution'];
            $header->{'Venue'} = $params['venue'];
            $header->{'Trainer_Code'} = $params['trainer'];
            $url = new Services('trainingheader');
            $soapWsdl = $url->getUrl();
            if (!Yii::$app->navision->isUp($soapWsdl)) {
                throw new \yii\web\HttpException(503, 'Service unavailable');
            } else {
                $entry = $header;
                $entryID = 'TrainingRequest';
                $result = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);
                /********* posting line****/
                /**********LINE********/
                $results = [];
                if (is_object($result)) {
                    $id = 0;
                    foreach ($result as $key => $object) {
                        $id = $object->Request_No;
                    }
                    $url = new Services('trainingLines');
                    $soapWsdl = $url->getUrl();
                    if (!Yii::$app->navision->isUp($soapWsdl)) {
                        throw new \yii\web\HttpException(503, 'Service unavailable');
                    }
                    for ($i = 0; $i < count($params['employeeID']); $i++) {
                        $Training_Participants->{'Employee_No'} = $params['employeeID'][$i];
                        $Training_Participants->{'Need_Source'} = $params['needsource'][$i];
                        $Training_Participants->{'Per_Diem_Distribution'} = $params['perdiemdistribution'][$i];
                        $Training_Participants->{'Tuition_Fee_Distribution'} = $params['tuitionfeedistribution'][$i];
                        $Training_Participants->{'Training_Request'} = $id;
                        $Training_Participants->Directorate = 'ICT';
                        $Training_Participants->{'Period_End_Date'} = '2018-06-30'; //To confirm
                        $entry = $Training_Participants;
                        $entryID = 'TrainingParticipants';
                        $temp = Yii::$app->navision->addEntry($creds, $soapWsdl, $entry, $entryID);
                        $errors = !is_object($temp) ? $temp : Null;
                        $results[$i] = $temp;
                    }
                    //print_r($results[0]->TrainingParticipants->Key); exit;
                    if (!$errors) {
                        foreach ($result as $key => $res) {
                            $header = $res;
                        }
                        return $this->render('_newtraining', ['header' => $header, 'results' => $results]);
                    } else
                        $results = $errors;
                    return $this->render('terror', ['results' => $results]);
                }
                return $this->render('terror', ['results' => $results]);
            }
            //error
        }
        return $this->render('newtraining');
    }

    public function actionApprove()
    {
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $_GetParams = Yii::$app->request->get();
        $url = new Services('codeunit');
        $soapWsdl = $url->getUrl();
        $Returnvalue = (object)[];
        $Returnvalue->applicationNo = $_GetParams['requestNo'];
        //$Returnvalue->Key=$_GetParams['key'];
        $entry = $Returnvalue;
        $result = Yii::$app->navision->ApprovalTraining($creds, $soapWsdl, $entry);
        //print_r($result); exit;
        if (is_object($result)) {
            $message = ['Result' => 'Success', 'Message' => "Approval request for store requisition no: " . $_GetParams['requestNo'] . " sent"];
            $message = json_encode($message);
            return $message;

        } else {
            $message = ['Result' => 'Fail', 'Message' => $result];
            $message = json_encode($message);
            return $message;
        }
    }

    public function actionApproverecruitment()
    {
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $_GetParams = Yii::$app->request->get();
        //print_r($_GetParams); exit;
        $url = new Services('codeunit');
        $soapWsdl = $url->getUrl();
        $Returnvalue = (object)[];
        $Returnvalue->applicationNo = $_GetParams['requestNo'];
        //$Returnvalue->Key=$_GetParams['key'];
        $entry = $Returnvalue;
        $result = Yii::$app->navision->ApprovalRecruitment($creds, $soapWsdl, $entry);
        //print_r($result); exit;
        if (is_object($result)) {
            $message = ['Result' => 'Success', 'Message' => "Approval request for store requisition no: " . $_GetParams['requestNo'] . " sent"];
            $message = json_encode($message);
            return $message;

        } else {
            $message = ['Result' => 'Fail', 'Message' => $result];
            $message = json_encode($message);
            return $message;
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->session->destroy();
        return $this->goHome();
    }
    //Approve requests
    public function actionApproverequest(){//Document Approval
                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->entryNo = \Yii::$app->request->get('id');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->ApproveDocument($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    Yii::$app->session->setFlash('success', "Request Approved Successfully");
                    //return ['status'=>1,'note'=>'Request Approved Successfully'];

                } else {
                    Yii::$app->session->setFlash('error', "Error Approving Request: ".$result);
                    //return ['status'=>0,'note'=>"Error Approving Request: ".$result];
                }

                return $this->redirect(Yii::$app->request->referrer);

    }
    public function actionRejectrequest(){//Reject a leave application

                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->entryNo = \Yii::$app->request->get('id');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->RejectDocument($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    //Yii::$app->session->setFlash('success', "Request Approval Rejected Successfully");
                    return ['note'=>'Request Approval Rejected Successfully'];

                } else {
                    //Yii::$app->session->setFlash('error', "Error Rejecting Request Approval: ".$result);
                    return ['note'=>'Error Rejecting Request Approval: '.$result];
                }

                return $this->redirect(Yii::$app->request->referrer);
    }

    //sending any requests for approval or cancel any requests---> provide request type parameter
    public function actionApprovalrequest(){//send approval request to supervisor
                $this->enableCsrfValidation = false;
                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->applicationCode = \Yii::$app->request->get('app');
                $Returnvalue->requestType = \Yii::$app->request->get('type');//Helps in sending any request for approval
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->send_request_approval($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    

                    return ['status'=>1,'note'=>'Approval Request Sent Successfully'];


                } else {
                    //Yii::$app->session->setFlash('error', "Error Sending Approval Request : ".$result);
                    return ['status'=>0,'note'=>'Error sending approval request: '.$result];
                }

                //return $this->redirect(Yii::$app->request->referrer);

    }
    //cancel any sent approval request --> provide a request type
    public function actionCancelapprovalrequest(){//send approval request to supervisor
                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->applicationCode = \Yii::$app->request->get('app');
                $Returnvalue->requestType = \Yii::$app->request->get('type');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->cancel_request_approval($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                if (is_object($result)) {
                     
                    Yii::$app->session->setFlash('success', "Approval Request no: " .\Yii::$app->request->get('app') . " has been cancelled successfully");
                    if(\Yii::$app->request->get('ajax')){
                         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['status'=>1,'note'=>'Approval Request Withdrawn Successfully.'];
                    }

                } else {
                    Yii::$app->session->setFlash('error', "Error Withdrawing Request : ".$result);
                    if(\Yii::$app->request->get('ajax')){
                         Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                        return ['status'=>0,'note'=>"Error Withdrawing Request. "];
                    }
                }

                return $this->redirect(Yii::$app->request->referrer);

    }

    public function actionInsertcasuals(){
        Yii::$app->cache->flush();
        $max = $this->actionLast_casual_entry(); 

        $casuals = BiometricsController::actionBiousers($max);

        
                $username = 'FNjambi';
                $password = 'Pass20$Cis';
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                //$url = new Services('codeunit_factory');
                $url = 'http://iita-navdb.cgiarad.org:7047/DynamicsNAV110/WS/IITA/Codeunit/CUnit_Factory';
               // $soapWsdl = $url->getUrl();
                $soapWsdl = $url;
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $casual = (object)[];
                $result = [];
                foreach($casuals as $c){
                    $casual->empNo = $c['empNo'] ;
                    $casual->empName = $c['empName'] ;
                    $casual->regDate = $c['regDate'];
                    $casual->idNum = $c['idNum'] ;
                    $casual->dept = $c['department'] ;
                    $casual->comp = $c['company'] ;

                    $entry = $casual;

                    $result[] = Yii::$app->navision->insertcasual($creds, $soapWsdl, $entry);

                    

                }
                $casuals = [];
               // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON; 
                print '<pre>';
                print_r($result) ;
                return 1;
                
                

    }
    public function actionBiotransactions(){
        //Yii::$app->cache->flush();
         $max = $this->actionLast_casual_daily_entry();
        $transaction = BiometricsController::actionIndex($max);

        /*print '<pre>';
        print_r($transaction);
         exit;*/

                /*$username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;*/
                $username = 'FNjambi';
                $password = 'Pass20$Cis';
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                //$url = new Services('codeunit_factory');
                $url = 'http://iita-navdb.cgiarad.org:7047/DynamicsNAV110/WS/IITA/Codeunit/CUnit_Factory';
               // $soapWsdl = $url->getUrl();
                $soapWsdl = $url;
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $transactions = (object)[];
                $result = [];
                foreach($transaction as $c){
                        

                    $transactions->entryNo = $c['entryNo'] ;
                    $transactions->empNo = $c['empNo'] ;
                    $transactions->dept = $c['dept'];
                    $transactions->dateTimeIn = $c['dateTimeIn'] ;
                    $transactions->dateTimeOut = isset($c['dateTimeOut'])?$c['dateTimeOut']:'Not set' ;
                    
                    $entry = $transactions;

                    $result[] = Yii::$app->navision->insert_casuals_transaction($creds, $soapWsdl, $entry);

                    
                }
                /*print '<pre>';
                print_r($result).'<br>';*/
                $transaction = [];

                return 1;

    }

    public function actionLast_casual_entry(){
       // ini_set("soap.wsdl_cache_enabled", 0);
                /*$username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;*/
                 $username = 'FNjambi';
                $password = 'Pass20$Cis';
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                //$url = new Services('codeunit_factory');
                $url = 'http://iita-navdb.cgiarad.org:7047/DynamicsNAV110/WS/IITA/Codeunit/CUnit_Factory';
               // $soapWsdl = $url->getUrl();
                $soapWsdl = $url;
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                
                $result = Yii::$app->navision->last_casual_entry($creds, $soapWsdl);

                return $result->return_value; 

               /* print '<pre>';
                print_r($result->return_value);*/
                
                    
    }

    public function actionLast_casual_daily_entry(){
       
                 $username = 'FNjambi';
                $password = 'Pass20$Cis';
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                //$url = new Services('codeunit_factory');
                $url = 'http://iita-navdb.cgiarad.org:7047/DynamicsNAV110/WS/IITA/Codeunit/CUnit_Factory';
               // $soapWsdl = $url->getUrl();
                $soapWsdl = $url;
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                
                $result = Yii::$app->navision->last_daily_entry($creds, $soapWsdl);

                return $result->return_value; 

                
                    
    }
    public function actionCompanyjobs(){
        return $this->render('companyjobs');
    }
    public function actionUpdate($key="",$action=""){
        $model = $this->getjob($key);        
        $action = ($action=='update')?'update':'create';


        if(\Yii::$app->request->post()){
            //print_r($_POST); exit;
            $p = (object)$_POST;
            $username = Yii::$app->user->identity->Id;
            $password = Yii::$app->user->identity->Pass;

            $creds = (object)[];
            $job = (object)[];
            $creds->UserName = $username;
            $creds->PassWord = $password;
            $url= new Services('companyjobs');
            $soapWsdl=$url->getUrl();

            if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
                throw new \yii\web\HttpException(503, 'Service unavailable');

            }
             $job->Key = $p->Key;
             //$job->Job_ID = $p->Job_ID;
             $job->Job_Description = $p->Job_Description;
             $job->No_of_Posts = $p->No_of_Posts;
             $job->Grade = $p->Grade;
             $job->Occupied_Establishments = $p->Occupied_Establishments;
             //$job->Vacant_Establishments = $p->Vacant_Establishments;
             $job->Dimension_1 = $p->Dimension_1;
             $job->Directorate_Name = $p->Directorate_Name;
             $job->Dimension_2 = $p->Dimension_2;
             $job->Department_Name = $p->Department_Name;
             $job->Notice_Period = $p->Notice_Period;
            // $job->Probation_Period = $p->Probation_Period;
             $job->Status = $p->Status;
             $job->Date_Active = $p->Date_Active;
             $entry = $job;

             $entryID = 'CompanyJobs';          
            $results = Yii::$app->navision->updateEntry($creds, $soapWsdl,$entry, $entryID);
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if(is_object($results)){
                return ['status'=>1,'note'=>'Company Job Updated successfully.'];
            }else{
                return ['status'=>0,'note'=>'Error Updating Company Jobs  : '.$results];
            }
        }

        return $this->renderAjax('update',['model'=>$model,'action'=>$action]);
    }
    public function actionCreate(){
        return $this->render('create');
    }
    public function actionCreatejobrequirement(){
        $docNo = \Yii::$app->request->get('docNo');
        return $this->renderAjax('_formjobrequirementlines',['docNo'=>$docNo]);
    }
    public function actionCreatejobresponsibility(){
        $docNo = \Yii::$app->request->get('docNo');
        return $this->renderAjax('_formjobresponsibilities',['docNo'=>$docNo]);
    }
    public function getjob($key){
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
         $filter = []; //declare the sieve as an array
        //define the sieve
        $filter = ['Field' => 'Job_ID', 'Criteria' =>$key ];
       
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        /*foreach($results as $k=>$v){
            $header = $v;
        }*/
        $header = $results->ReadMultiple_Result->CompanyJobs[0];
        return $header;
    }

    /********************Overtime Approval mgt : by Francis Njambi***********************/

    //send doc for approval
    public function actionSendotapproval(){

                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->docNo = \Yii::$app->request->get('id');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->sendOtApproval($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                //Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    Yii::$app->session->setFlash('success', "Overtime Approval Request sent Successfully");
                    //return ['status'=>1,'note'=>'Overtime Approval Request sent Successfully'];

                } else {
                    Yii::$app->session->setFlash('error', "Error sending Overtime Approval Request: ".$result);
                    //return ['status'=>0,'note'=>'Error sending Overtime Approval Request : '.$result];
                }

                return $this->redirect(Yii::$app->request->referrer);
    }

    //cancel Approval request

    public function actionCancelotapproval(){

                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->docNo = \Yii::$app->request->get('id');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->cancelOtApproval($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    //Yii::$app->session->setFlash('success', "Request Approval Rejected Successfully");
                    return ['status'=>1,'note'=>'Overtime Approval Request Cancelled Successfully'];

                } else {
                    Yii::$app->session->setFlash('error', "Error Cancelling Overtime Approval Request: ".$result);
                    //return ['status'=>0,'note'=>'Error Cancelling Overtime Approval Request: '.$result];
                }

                return $this->redirect(Yii::$app->request->referrer);
    }

//Approve Ot Approval request
    public function actionApproveot(){

                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->docNo = \Yii::$app->request->get('id');
                $Returnvalue->emplNo = \Yii::$app->request->get('emp');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->ApproveOt($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    Yii::$app->session->setFlash('success', "Overtime Document Approved Successfully");
                    //return ['status'=>1,'note'=>'Overtime Document Approved Successfully'];

                } else {
                    Yii::$app->session->setFlash('error', "Error Approving Overtime Document: ".$result);
                    //return ['status'=>0,'note'=>'Error Approving Overtime Document : '.$result];
                }

                return $this->redirect(Yii::$app->request->referrer);
    }
//Reject Ot Approval
public function actionRejectot(){

                $username = Yii::$app->user->identity->Id;
                $password = Yii::$app->user->identity->Pass;
                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;

                $url = new Services('codeunit');
                $soapWsdl = $url->getUrl();
                if (!Yii::$app->navision->isUp($soapWsdl)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');
                }
                $Returnvalue = (object)[];
                $Returnvalue->docNo = \Yii::$app->request->get('id');
                 $Returnvalue->emplNo = \Yii::$app->request->get('emp');
                
                $entry = $Returnvalue;
                 
                $result = Yii::$app->navision->RejectOt($creds, $soapWsdl, $entry);
               /*print('<pre>');
                var_dump($result); exit;*/
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                if (is_object($result)) {
                     
                    Yii::$app->session->setFlash('success', "Overtime Document Rejected Successfully");
                    //return ['status'=>1,'note'=>'Overtime Document Rejected Successfully'];

                } else {
                    Yii::$app->session->setFlash('error', "Error Rejecting Overtime Document: ".$result);
                    //return ['status'=>0,'note'=>'Error Rejecting Overtime Document : '.$result];
                }

                return $this->redirect(Yii::$app->request->referrer);
    }

    //View overtime approval card
    public function actionOtcard($code){

        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $empno = \Yii::$app->request->get('emp');

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('ot_approval_header');
        $soapWsdl=$url->getUrl();
       
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }

        $filter = [
            ['Field' => 'Code', 'Criteria' => '='.$code],
             ['Field' => 'Employee_ID', 'Criteria' => $empno],
        ];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
       
        $details = $results->ReadMultiple_Result->OT_Approval_Header[0];

        /*print '<pre>';
        print_r($details);*/

        return $this->render('otApprovalCard',[
            'details'=>$details
        ]);
    }

    





    /**********************End overtime mgt*****************************************************/

}
  