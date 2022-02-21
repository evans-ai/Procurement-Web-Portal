<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use common\models\LoginForm;
use backend\models\Services;
use backend\models\Recruitment;
use backend\models\Qualification;
use backend\models\Experience;
use backend\models\Referee;
use backend\models\Attachment;
use backend\models\Comment;
use backend\models\User;
use backend\models\Hruser;
use yii\base\NotSupportedException;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
//use yii\web\UploadedFile;

use Office365\PHP\Client\Runtime\Auth\NetworkCredentialContext;
use Office365\PHP\Client\SharePoint\ClientContext;
use Office365\PHP\Client\Runtime\Auth\AuthenticationContext;
use Office365\PHP\Client\Runtime\Utilities\RequestOptions;

use Office365\PHP\Client\SharePoint\ListCreationInformation;
use Office365\PHP\Client\SharePoint\SPList;
use Office365\PHP\Client\SharePoint\Web;
/**
 * Site controller
 */
class RecruitmentController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error','test','register','registeruser','vacancies','jobcard'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','user','general','communication','qualifications','experience',
                'attachments','referee','comments','educationlevels','academicgrades','academiccerts','rneeds','apply','countries','ethnic','jobcard','profile','jobapplication','applications','updategeneral','viewdoc','purgedoc','deletecomment','deletereferee','deleteexperience','deletequalification','general','index','success','help'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                'actions' => ['communication','qualifications','experience',
                'attachments','referee','comments','educationlevels','academicgrades','academiccerts','rneeds','vacancies','countries','ethnic','jobcard','index','help','vacancytest'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
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
     

    public function actionIndex()
    {

        //return $this->render('index');
        if(isset($_GET['create'])){
        $countries = json_decode($this->actionCountries());
        $ethnic = json_decode($this->actionEthnic());
        $levels = json_decode($this->actionEducationlevels());
        $grades = json_decode($this->actionAcademicgrades());
        $certificates = json_decode($this->actionAcademiccerts());

        //print '<pre>'; print_r($countries); exit;
        $model = new Recruitment();
        $modelq = new Qualification();
        $modelexperience = new Experience();
        $referee = new Referee();
        $attachment = new Attachment();
        $comment = new Comment();
        

        return $this->render('_general',[
            'countries'=>$countries,
            'ethnic'=>$ethnic,
            'levels'=>$levels,
            'grades'=>$grades,
            'certs'=>$certificates,
            'model'=>$model,//applicant
            'modelqualification'=>$modelq,
            'modelexperience' => $modelexperience,
            'modelreferee'=> $referee,
            'modelattachment' => $attachment,
            'modelcomment' => $comment,
        ]);

        }
        
        return $this->redirect(['vacancies']);
    }

    public function actionUpdategeneral(){
        $service = Yii::$app->params['ServiceName']['Applicant_Card'];
        $filter = ['No'=> Yii::$app->user->identity->ApplicantId ];
        $countries = json_decode($this->actionCountries());
         $ethnic = json_decode($this->actionEthnic());
        $model = new Recruitment();
        $datamodel = Yii::$app->navhelper->getData($service,$filter);


        if(Yii::$app->request->post('Recruitment')){
                $result = Yii::$app->navhelper->updateData($service,$_POST['Recruitment']);
               if(is_object($result)){
                   
                    Yii::$app->session->setFlash('success', " General Information Updated Successfully .");
                    //return json_encode($update);
                }else{
                     Yii::$app->session->setFlash('error', "Error Saving Applicant General Information :".$result);
                     
                }
                return $this->redirect(Yii::$app->request->referrer);//boom!
                
        }
        if(Yii::$app->recruitment->hasprofile()){
                foreach($datamodel[0] as $key => $val){
                        if(!is_object($val)){
                            //print $val.'<br>';

                            $model->$key = $val;

                        }
                }
        }
        

        return $this->render('_updategeneral',[
            'model'=>$model,
            'datamodel'=>$datamodel[0],
            'update'=>1,
            'countries'=>$countries,
            'ethnic'=>$ethnic,
        ]);  
    }
    public function actionVacancies(){
        if(Yii::$app->user->isGuest){
            //$this->layout = 'guest';
            $this->layout = 'guesttest';
        }
        
        $jobs = json_decode($this->actionRneeds());
        $model = [];
        $id = 0;
        foreach($jobs as $job){
            ++$id;
            $model[] =[

                'Key'=>$job->Key,
                'id'=>$id,
                'No' => $job->No,
                'Job_ID' => isset($job->Job_ID)?$job->Job_ID:'Not Set',
                'Date' => isset($job->Date)?$job->Date:'Not Set',
                'Description' => isset($job->Description)?$job->Description:'Not Set',
                'Global_Dimension_1_Code'=>isset($job->Global_Dimension_1_Code)?$job->Global_Dimension_1_Code:'Not Set',
                'Reason_for_Recruitment' => isset($job->Reason_for_Recruitment)?$job->Reason_for_Recruitment:'Not Set',
                'Requested_By' => isset($job->Requested_By)?$job->Requested_By:'Not Set',
                'Type' => isset($job->Appointment_Type)?$job->Appointment_Type:'Not Set',
                'Expected_Reporting_Date' => isset($job->Expected_Reporting_Date)?$job->Expected_Reporting_Date:'Not Set',
                'Status' => isset($job->Status)?$job->Status:'Not Set',
                'Start Date' => isset($job->Start_Date)?$job->Start_Date:'Not Set',
                'Application Deadline' => isset($job->End_Date)?date('d/m/Y',strtotime($job->End_Date)):'Not Set',
            ];
        }

        $vacanciesProvider = new ArrayDataProvider([
        'allModels' => $model,
        'pagination' => [
            'pageSize' => 10,
        ],
            'sort' => [
                'attributes' => ['No', 'Description','Type','Application Deadline'],
            ],
        ]);
       
        return $this->render('vacancies',[
            'jobs'=>$jobs,
            'provider' => $vacanciesProvider,
        ]);
    }


    public function actionVacancytest(){
        /*if(Yii::$app->user->isGuest){
            $this->layout = 'guest';
        }*/
        $this->layout = 'guesttest';
        $jobs = json_decode($this->actionRneeds());
        $model = [];
        $id = 0;
        foreach($jobs as $job){
            ++$id;
            $model[] =[

                'Key'=>$job->Key,
                'id'=>$id,
                'No' => $job->No,
                'Job_ID' => isset($job->Job_ID)?$job->Job_ID:'Not Set',
                'Date' => isset($job->Date)?$job->Date:'Not Set',
                'Description' => isset($job->Description)?$job->Description:'Not Set',
                'Global_Dimension_1_Code'=>isset($job->Global_Dimension_1_Code)?$job->Global_Dimension_1_Code:'Not Set',
                'Reason_for_Recruitment' => isset($job->Reason_for_Recruitment)?$job->Reason_for_Recruitment:'Not Set',
                'Requested_By' => isset($job->Requested_By)?$job->Requested_By:'Not Set',
                'Type' => isset($job->Appointment_Type)?$job->Appointment_Type:'Not Set',
                'Expected_Reporting_Date' => isset($job->Expected_Reporting_Date)?$job->Expected_Reporting_Date:'Not Set',
                'Status' => isset($job->Status)?$job->Status:'Not Set',
                'Start Date' => isset($job->Start_Date)?$job->Start_Date:'Not Set',
                'Application Deadline' => isset($job->End_Date)?date('d/m/Y',strtotime($job->End_Date)):'Not Set',
            ];
        }

        $vacanciesProvider = new ArrayDataProvider([
        'allModels' => $model,
        'pagination' => [
            'pageSize' => 10,
        ],
            'sort' => [
                'attributes' => ['No', 'Description'],
            ],
        ]);
       
        return $this->render('vacancies',[
            'jobs'=>$jobs,
            'provider' => $vacanciesProvider,
        ]);
    }

    public function actionGeneral()
    { //Applicant_Card  

        $service = Yii::$app->params['ServiceName']['Applicant_Card'];  
        $userservice = Yii::$app->params['ServiceName']['HRUser'];
        $currentuser = json_decode($this->actionUser());
        $userdata = ['Key'=>$currentuser[0]->Key,'ApplicantId'=>''];
        //print('<pre>');
        //print_r($_POST['Recruitment']);  exit;
        //exit("KKKKKKKKKKKKKKKKK");
        //initiaize session g array
        $session = \Yii::$app->session; 
        $model = new Recruitment();   
       
        if(Yii::$app->request->post())
        {
       
            if(Yii::$app->request->post('Recruitment')){
                $result = Yii::$app->navhelper->postData($service,$_POST['Recruitment']);//Just pass service Name and form data
               //print '<pre>'; print_r($result); exit;
                if(is_object($result)){
                    $session->set('Applicantid',$result->No);
                     $session->set('Key',$result->Key);
                     Yii::$app->session->setFlash('success', "General Information for Applicant:  <b>".$result->No." </b> Saved successfully.");
                      $userdata = ['Key'=>$currentuser[0]->Key,'ApplicantId'=>$result->No];
                       $update = Yii::$app->navhelper->updateData($userservice,$userdata);

                    //return json_encode($update);
                }else{
                     Yii::$app->session->setFlash('error', "Error Saving Applicant General Information :".$result);
                     //exit($result);
                }
                return $this->redirect(Yii::$app->request->referrer);//boom!

            }
                //display the result on users form
                //return $this->redirect(Yii::$app->request->referrer);//boom!  
        }
        if(!Yii::$app->request->isAjax){
                         return $this->render('index');
        }
    }
    //communication

    public function actionCommunication(){//update -->Applicant_Card

        $service = Yii::$app->params['ServiceName']['Applicant_Card']; 

        if(Yii::$app->request->post()){
            $result = Yii::$app->navhelper->updateData($service,$_POST);//Just pass service Name and form data

            if(is_object($result)){
                 Yii::$app->session->setFlash('success', "Communication Information for Applicant:  <b>".$result->No." </b> Created successfully.");
            }else{
                 Yii::$app->session->setFlash('error', "Error Saving Applicant Communication Information :".$result);
            }
            //display the result on users form
            return $this->redirect(Yii::$app->request->referrer);//boom!

        }
        return $this->render('_communication');
        
    }

    //Qualifications

    public function actionQualifications(){//appQualifications

        $service = Yii::$app->params['ServiceName']['Qualifications'];
        $modelq = new Qualification();

        if(Yii::$app->request->post()){

            //print '<pre>';
            //print_r($_POST['Qualification']); exit;
            $result = Yii::$app->navhelper->postData($service,$_POST['Qualification']);//Just pass service Name and form data

            if(is_object($result)){
                 Yii::$app->session->setFlash('success', "Qualification for Applicant:  <b>".$result->Applicant_No." </b> Created successfully.");
            }else{
                 Yii::$app->session->setFlash('error', "Error Saving Applicant Qualification :".$result);
            }
            //display the result on users form
            return $this->redirect(Yii::$app->request->referrer);//boom!

        }
        //fetch drop down data and pass it to view
        $levels = json_decode($this->actionEducationlevels());
        $grades = json_decode($this->actionAcademicgrades());
        $certificates = json_decode($this->actionAcademiccerts());

        return $this->render('_qualifications',[
            'levels'=>$levels,
            'grades'=>$grades,
            'certs'=>$certificates,
            'modelqualification'=>$modelq,
        ]);
        
    }


    //Experience

     public function actionExperience(){//appExperience
        $service = Yii::$app->params['ServiceName']['experience'];

        if(Yii::$app->request->post()){
            $result = Yii::$app->navhelper->postData($service,$_POST);//Just pass service Name and form data

            if(is_object($result)){
                 Yii::$app->session->setFlash('success', "Experience for Applicant:  <b>".$result->Applicant_No." </b> Saved successfully.");
            }else{
                 Yii::$app->session->setFlash('error', "Error Saving Applicant Experience :".$result);
            }
            //display the result on users form
            return $this->redirect(Yii::$app->request->referrer);//boom!

        }
       
        return $this->render('_experience');
        
    }


    //referee

    public function actionReferee(){//appReferees
         $service = Yii::$app->params['ServiceName']['referees'];

         if(Yii::$app->request->post()){
            $result = Yii::$app->navhelper->postData($service,$_POST);//Just pass service Name and form data

            if(is_object($result)){
                 Yii::$app->session->setFlash('success', "Referee for Applicant:  <b>".$result->No." </b> Saved successfully.");
                 return $this->redirect(Yii::$app->request->referrer);//boom!
            }else{
                 Yii::$app->session->setFlash('error', "Error Saving Applicant Referee :".$result);
                 return $this->redirect(Yii::$app->request->referrer);//boom!
            }
            //display the result on users form
            return $this->redirect(Yii::$app->request->referrer);//boom!

        }
        
        return $this->render('_referees');
    }


    //Applicant Documents
    public function actionAttachments(){
        $service = Yii::$app->params['ServiceName']['Attachments'];
         if(Yii::$app->request->post()){
           

           
                

                 //upload attachments
                 if(sizeof($_FILES)){
                    $path_local = '../web/attachments/';

                    //temp file
                     $temp = $_FILES['attachment']['tmp_name'];

                   
                    //image path
                    $actual_upload_file = $_FILES['attachment']['name'];


                    $file_parts = pathinfo($actual_upload_file);

                    $ext = @$file_parts['extension'];

                    if(!isset($ext) || $ext == " " || $ext == NULL){
                         $size_err = 'File Extension Error: The file you attached appears to have no extension. e.g .pdf, .xls, .doc etc.., Verify the file integrity and re-upload. ';

                         Yii::$app->session->setFlash('error', $size_err);
                        
                         return $this->redirect(Yii::$app->request->referrer);//boom!
                     }
                     //path to upload the file--> the basename solves uploading inconsistencies
                    $target_file = $path_local.basename($actual_upload_file);
                    $file_size     = round(($_FILES["attachment"]["size"])/(1024*1024)); 

                     if($file_size > 3){
                         $size_err = 'File Size Error: You have exceeded file size limit of 3MB, your file size:  '.$file_size.' MBs update leave request below accordingly. ';

                         Yii::$app->session->setFlash('error', $size_err);                         
                        
                         return $this->redirect(Yii::$app->request->referrer);//boom!
                     }
                     //files upload errors

                     $error_messages = array(
                            UPLOAD_ERR_OK         => 'There is no error, the file uploaded with success',
                            UPLOAD_ERR_INI_SIZE   => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
                            UPLOAD_ERR_FORM_SIZE  => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                            UPLOAD_ERR_PARTIAL    => 'The uploaded file was only partially uploaded',
                            UPLOAD_ERR_NO_FILE    => 'No file was uploaded',
                            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the file upload',
                        );






                     if(move_uploaded_file($temp,$target_file)){
                        $_POST['Document_Link'] = $target_file;
                        $_POST['Line_No'] = time();//this was a fix after nav failed on this table

                       // print '<pre>'; print_r($_POST); exit;
                         $result = Yii::$app->navhelper->postData($service,$_POST);//Just pass service Name and form data

                        //upload to sharepoint
                         $desc = $result->Document_Description;
                         $applicantno = $result->Applicant_No;
                         $docno = $result->Line_No;
                         $key = $result->Key;//for sp path update

                         $metadata = [
                            $desc,
                            $applicantno,
                            $docno
                         ];

                         $session = \Yii::$app->session;
                         $session->set('metadata',$metadata);

                        $sp = $this->actionShrpnt_attach($target_file); 

                        /*Update Navision with new sharepoint link*/
                        $filename = basename($actual_upload_file);
                        $sp_path = Yii::$app->params['sharepointUrl'].'/'.Yii::$app->params['library'].'/'.$filename;
                        $data = ['Key'=>$key,'Sharepoint_Link'=>$sp_path];
                        $update = Yii::$app->navhelper->updateData($service,$data);

                           
                    


                       


                         if(is_object($result)){
                            Yii::$app->session->setFlash('success', "Document for Applicant:  <b>".$result->Applicant_No." </b> Created successfully.");
                            return $this->redirect(Yii::$app->request->referrer);//boom!
                         }
                         else{
                            Yii::$app->session->setFlash('error', "Error Saving Applicant Document :".$result);
                            return $this->redirect(Yii::$app->request->referrer);//boom!
                        }
                          
                        
                    }else{
                       
                        $error =  $error_messages[$_FILES['attachment']['error']];
                         Yii::$app->session->setFlash('error', "Error Saving Applicant Document :".$error);
                        return $this->redirect(Yii::$app->request->referrer);//boom!
                       
                        
                    }

                 }

            
            //display the result on users form
            

        }
        return $this->render('_attachments');
    }

//comments ---> assumed to be used as applicants brief commenents on themselves

    public function actionComments(){

        if(Yii::$app->request->post()){
            $result = Yii::$app->navhelper->postData('appComments',$_POST);//Just pass service Name and form data

            if(is_object($result)){
                 Yii::$app->session->setFlash('success', "Applicant Comments <b>".$result->Applicant_No." </b> Created successfully."
                    . Html::a('Apply Job',['./recruitment/vacancies'],['class' => 'btn btn-primary', 'name' => 'login-button']) );
                 return $this->redirect(Yii::$app->request->referrer);//boom!
            }else{
                 Yii::$app->session->setFlash('error', "Error Creatin Refereee Details in Application :".$result);
            }
            //display the result on users form

            return $this->redirect(Yii::$app->request->referrer);//boom!

        }
        return $this->render('_comments');
    }

    //get  edu levels
    public function actionEducationlevels(){
        $result = Yii::$app->navhelper->getData('Education_Levels');
        return json_encode($result); //Array of objects
    }

    //get Academic Certificates
    public function actionAcademiccerts($educationlevelid=""){
        if($educationlevelid){
            $filter = ['EducationLevelID' => $educationlevelid ];
            $result = Yii::$app->navhelper->getData('Academic_Certificates',$filter);
            $html = [];
            if(sizeof($result)){
                    $html[] = '<option>Select Certificate</option>';
                foreach($result as $r){
                        $html[] ='<option value="'.$r->CertificateID.'">'.$r->CertificateName.'</option>';
                }

                print_r($html);
                
                   
            }else{

                return '<option></option>';
                    //exit('ll');
            }

           
        }
        $result = Yii::$app->navhelper->getData('Academic_Certificates');
       return json_encode($result); //Array of objects

    }

    //get Academic Grades

    public function actionAcademicgrades($certificateid = ""){


            if($certificateid){
                $filter = ['CertificateID' => $certificateid ];
                $result = Yii::$app->navhelper->getData('Academic_Grades_List',$filter);
                $html = [];
                if(sizeof($result)){
                    $html[] = '<option>Select Grade</option>';
                    foreach($result as $r){
                            $html[] = '<option value="'.$r->GradeID.'">'.$r->GradeName.'</option>';
                    }
                     print_r($html);    
                }else{

                    return '<option></option>';
                       
                }

           
        }


        $result = Yii::$app->navhelper->getData('Academic_Grades_List');
        return json_encode($result); //Array of objects
        
    }



    //get recruitment Needs

     public function actionRneeds(){
       $service = Yii::$app->params['ServiceName']['Recruitment_Needs'];
        $result = Yii::$app->navhelper->getData($service);
        //print '<pre>';
        return json_encode($result); //Array of objects
        
        
    }
    public function actionUser(){
       $service = Yii::$app->params['ServiceName']['HRUser'];
        $result = Yii::$app->navhelper->getData($service,['Email'=>Yii::$app->user->identity->Email]);
        //print '<pre>';
        return json_encode($result); //Array of objects
        
        
    }

    //get Country/Regions

    public function actionCountries(){
        $service = Yii::$app->params['ServiceName']['CountryRegions'];
        $result = Yii::$app->navhelper->getData($service);
        //print '<pre>';
        return json_encode($result); //Array of objects
    }

    //get Ethnic groups

    public function actionEthnic(){
        $service = Yii::$app->params['ServiceName']['Ethnic'];
        $result = Yii::$app->navhelper->getData($service);
        //print '<pre>';
        return json_encode($result); //Array of objects
    }

//get job description form job card

    public function actionJobcard($id){

        //print (Yii::$app->recruitment->jobid($id)); exit;
        $jobid = Yii::$app->recruitment->jobid($id);
        $rescategories = Yii::$app->recruitment->jobresponsibilitiescategories($jobid);

        //print_r($responsibilities);
        //exit;
        if(Yii::$app->user->isGuest){
            //$this->layout = 'guest';
            $this->layout = 'guesttest';
        }
        $service = Yii::$app->params['ServiceName']['Recruitment_Request'];
        $filter = ['No'=>$id];
        $result = Yii::$app->navhelper->getData($service,$filter);
        return $this->render('jobcard',['card'=>$result,'categories'=>json_decode($rescategories)]);
    }

    //Get Applicant Profile
    public function actionProfile(){
        /*print '<pre>';
			print_r(Yii::$app->user->identity->FirstName);
        exit();*/
        $identity = Yii::$app->user->identity;
		//var_dump(Yii::$app->user->identity->ApplicantId);exit;
        $profile = [];
        $id = new Class{};//anonymous class
        if(Yii::$app->recruitment->hasprofile()){
            $service = Yii::$app->params['ServiceName']['Applicant_Card'];
            $filter = ['No'=> Yii::$app->user->identity->ApplicantId ];
             $result = Yii::$app->navhelper->getData($service,$filter);
			 //print '<pre>';
			  //print_r($result); exit;
            $profile =  $result;
        }
        else{
                    $id->First_Name = $identity->FirstName;
                    $id->Middle_Name = $identity->MiddleName;
                    $id->Last_Name = $identity->LastName;
                    $id->E_Mail =  $identity->Email;
                    $id->Cellular_Phone_Number =  $identity->Cell;
                    $id->Gender = $identity->Gender;
            
                        $profile[] = $id;
                    
                
                    
                
        }

        
        
        //print '<pre>';
        

        return $this->render('profile',['profile'=>$profile]);
       
    }

    //Get Applicant Profile

    public function actionJobapplication($jobapplicantid){
         $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];
          $filter = ['ApplicationNo'=> $jobapplicantid ];

          $result = Yii::$app->navhelper->getData($service,$filter);
            return $this->render('application',['application'=>$result]);            

        
    }

    //get All my applications

    public function actionApplications(){
        $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];
        $applicantid = Yii::$app->user->identity->ApplicantId;
        //print_r($applicantid); exit;
        $filter = ['ApplicantID'=> $applicantid];

        $result = Yii::$app->navhelper->getData($service,$filter);
        $model = [];//initialize model as an empty array
        $count = 0;

        /*print '<pre>';
        print_r($result);  exit;*/
     if($applicantid){
       if(is_array($result) && count($result)){
            foreach($result as $app){

                if(property_exists($app,'ApplicantID')){
                    ++$count;
                    $model[] = [
                        'id'=> $count,
                        'ApplicantID'=>$app->ApplicantID,
                        'ApplicationNo'=>$app->ApplicationNo,
                        'Application_Date'=>$app->Application_Date,
                        'Recruitment_Need_Code'=>$app->Recruitment_Need_Code,
                        'ApplicantID' => $app->ApplicantID,
                        'JobDescription' => $app->JobDescription,

                    ];
                }
            }
        }
     }   
        



        //create a data provider here
         $Provider = new ArrayDataProvider([
        'allModels' => $model,
        'pagination' => [
            'pageSize' => 10,
        ],
            'sort' => [
                'attributes' => ['ApplicationNo', 'JobDescription'],
            ],
        ]);

        return $this->render('applications',['applications'=>$result, 'provider'=> $Provider]);  
    }




    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    //Apply for the position

    public function actionApply($id){
        Yii::$app->controller->enableCsrfValidation = false;
        $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];
        $session = \Yii::$app->session;
        $data = [];
        if($id){
            $data['Recruitment_Need_Code'] = $id;
            $data['ApplicantID'] = Yii::$app->user->identity->ApplicantId;//'APPL011';//$session->get('Applicantid');
            $data['Application_Date'] = date('Y-m-d');



            //check if user has applied for job
            if(Yii::$app->recruitment->hasapplied(Yii::$app->user->identity->ApplicantId,$id)){
                 Yii::$app->session->setFlash('error', "Sorry, you have already applied for this job. ");
                 return $this->redirect(Yii::$app->request->referrer);//boom!
            }
            



            //post the data to nav
            $result = Yii::$app->navhelper->postData($service,$data);//Just pass service Name and form data

            if(is_object($result)){//$result->ApplicationNo


                return $this->render('success');



                if($this->commitapplication($result->ApplicationNo,$result->Key)){
                    Yii::$app->session->setFlash('success', "Job <b>".$result->ApplicationNo." </b> Applied successfully.");
                     return $this->redirect(Yii::$app->request->referrer);//boom!
                }else{
                    //Yii::$app->session->setFlash('error', "Error (stage 2) Applying Job :".$result);
                    Yii::$app->session->setFlash('error', "Error applying for the job: ".$result);
                     return $this->redirect(Yii::$app->request->referrer);//boom!
                }
                 
                 $session->set('ApplicationNo',$result->ApplicationNo);
                 $session->set('ApplicationKey',$result->Key);
            }else{
                 Yii::$app->session->setFlash('error', "Error Applying Job :".$result);
            }
            //display the result on users form

            return $this->redirect(Yii::$app->request->referrer);//boom!

        }
    }

    public function commitapplication($application_no,$key){
        $data = [];
        $data['ApplicantID'] = $application_no; //$session->get('Applicantid');
        $data['Application_Date'] = date('Y-m-d');

            $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];
            $result = Yii::$app->navhelper->updateData($service,$data);//Just pass service Name and form data

            if(is_object($result)){
                return true;
            }else{
                return false;
            }
    }
    public function actionRegister(){
       // $this->layout = 'external2';
        if(Yii::$app->user->isGuest){
            //$this->layout = 'guest';
            $this->layout = 'guesttest';
        }
         $model = new Hruser();
         $countries = json_decode($this->actionCountries());
        return $this->render('adduser',['model'=>$model,'countries'=>$countries]);
    }
public function actionUpdateuser(){
        //$this->layout = 'external2';
        return $this->render('updateuser');
    }

    public function actionRegisteruser(){

         $service = Yii::$app->params['ServiceName']['HRUser'];  
        if(Yii::$app->request->post()){

            //print '<pre>'; print_r(\Yii::$app->request->post('Hruser')); exit;

            $p = (object)Yii::$app->request->post('Hruser');
            $data = [];
            $data = [
            'FirstName' => ucfirst($p->FirstName),
            'MiddleName' => ucfirst($p->MiddleName),
            'LastName' => ucfirst($p->LastName),
           // 'Phone' => isset(),
            'Cell' => $p->Cell,
            'Gender' => $p->Gender,
            //'Extension' => $p->Extension,
            'Password' => md5($p->pwd),//get message digest of the chosen password
            'Email' => $p->Email,
            'RecoveryPassword' => Yii::$app->recruitment->encrypt($p->pwd),//Hash this password -> 2 way enc
            'DateCreated' => date('Y-m-d'),
            ];
            
            $result = Yii::$app->navhelper->postData($service,$data);//Just pass 

			//print '<pre>'; var_dump($result); exit;            
            
            if(is_object($result)){
                \Yii::$app->session->setFlash('success', "Account for <b>".$result->FirstName." ".$result->LastName."</b> Created successfully.");  
                 return $this->redirect(['register']);
            }
            else{
                \Yii::$app->session->setFlash('error', "Error creating account, try later.".$result);
                return $this->redirect(['register']);
            }

        }
    

    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        $this->layout = 'login';
        Yii::$app->user->logout();
        Yii::$app->session->destroy();
        return $this->redirect(['site/login']);
        //return $this->goHome();
    }



    

    public function actionViewdoc(){
       // list($f,$f1,$f2,$appno,$docname) = explode('/',$path);

         if(Yii::$app->request->get('path')) {
            $path = Yii::$app->request->get('path');

             return \Yii::$app->response->sendFile($path,'Document',['inline'=>true]);
         } else{
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
         } 
        

    }

    //delete a doc record from Nav, still remains in our web server

    public function actionPurgedoc(){
        $service = Yii::$app->params['ServiceName']['Attachments'];
        if(Yii::$app->request->get('serial')) {
            $key = Yii::$app->request->get('serial');
            $result = Yii::$app->navhelper->deleteData($service,$key);

            /*print '<pre>';
            var_dump($result); exit;*/
                if(is_object($result)){
                        Yii::$app->session->setFlash('success', " Document Purged Successfully .");
                }else{
                        Yii::$app->session->setFlash('error', " Error Purging Document : ".$result);
                }
             return $this->redirect(Yii::$app->request->referrer);
            }
            
          else{
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
         } 
    }

    //delete comments

    public function actionDeletecomment(){
        $service = Yii::$app->params['ServiceName']['appComments'];
        if(Yii::$app->request->get('serial')) {
            $key = Yii::$app->request->get('serial');
            $result = Yii::$app->navhelper->deleteData($service,$key);

                if(is_object($result)){
                        Yii::$app->session->setFlash('success', " Comment Purged Successfully .");
                }else{
                        Yii::$app->session->setFlash('error', " Error Purging Comment : ".$result);
                }
             return $this->redirect(Yii::$app->request->referrer);
            }
            
          else{
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
         } 
    }

//delete a referee
     public function actionDeletereferee(){
        $service = Yii::$app->params['ServiceName']['referees'];
        if(Yii::$app->request->get('serial')) {
            $key = Yii::$app->request->get('serial');
            $result = Yii::$app->navhelper->deleteData($service,$key);

                if(is_object($result)){
                        Yii::$app->session->setFlash('success', " Referee Purged Successfully .");
                }else{
                        Yii::$app->session->setFlash('error', " Error Purging Referee : ".$result);
                }
             return $this->redirect(Yii::$app->request->referrer);
            }
            
          else{
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
         } 
    }

    //delete a referee
     public function actionDeleteexperience(){
        $service = Yii::$app->params['ServiceName']['experience'];
        if(Yii::$app->request->get('serial')) {
            $key = Yii::$app->request->get('serial');
            $result = Yii::$app->navhelper->deleteData($service,$key);

                if(is_object($result)){
                        Yii::$app->session->setFlash('success', " Experience Purged Successfully .");
                }else{
                        Yii::$app->session->setFlash('error', " Error Purging Experience : ".$result);
                }
             return $this->redirect(Yii::$app->request->referrer);
            }
            
          else{
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
         } 
    }


        //delete qualification

     public function actionDeletequalification(){
        $service = Yii::$app->params['ServiceName']['Qualifications'];
        if(Yii::$app->request->get('serial')) {
            $key = Yii::$app->request->get('serial');
            $result = Yii::$app->navhelper->deleteData($service,$key);

                if(is_object($result)){
                        Yii::$app->session->setFlash('success', " Qualification Purged Successfully .");
                }else{
                        Yii::$app->session->setFlash('error', " Error Purging Qualification : ".$result);
                }
             return $this->redirect(Yii::$app->request->referrer);
            }
            
          else{
            throw new \yii\web\HttpException(503, 'Resource unavailable.');
         } 
    }

    public function actionSuccess(){
        return $this->render('success');
    }

    public function actionHelp(){
        //exit('working on it.......');
        return $this->render('help');
    }







    public function actionShrpnt_attach($filepath)  //read list
    {
        // $this->actionShrpnt_attach($target_file,$desc,$applicantno,$docno); 
        $Url= Yii::$app->params['sharepointUrl'];//"http://rbadev-shrpnt";
        $username= Yii::$app->params['sharepointUsername'];//'rbadev\administrator';
        $password= Yii::$app->params['sharepointPassword']; //'rba123!!';

        $localPath = $filepath;

        $targetLibraryTitle = \Yii::$app->params['library'];

        try 
        {
            $authCtx = new NetworkCredentialContext($username, $password);

            $authCtx->AuthType = CURLAUTH_NTLM; //NTML Auth schema
            $ctx = new ClientContext($Url,$authCtx);
            $site = $ctx->getSite();
            $ctx->load($site); //load site settings
            $ctx->executeQuery();  

            $list = $this->ensureList($ctx->getWeb(),$targetLibraryTitle, \Office365\PHP\Client\SharePoint\ListTemplateType::DocumentLibrary);

            $localFilePath = realpath ($localPath);
            $this->uploadFiles($localFilePath,$list);
        }
        catch (Exception $e) {
            print 'Authentication failed: ' .  $e->getMessage(). "\n";
        }
    }






     public static function ensureList(Web $web, $listTitle, $type, $clearItems = true)
    {
        $ctx = $web->getContext();
        $lists = $web->getLists()->filter("Title eq '$listTitle'")->top(1);
        $ctx->load($lists);
        $ctx->executeQuery();
        if ($lists->getCount() == 1) {
            $existingList = $lists->getData()[0];
            if ($clearItems) {
                //self::deleteListItems($existingList);
            }
            return $existingList;
        }
        return ListExtensions::createList($web, $listTitle, $type);
    }


    /**
     * @param Web $web
     * @param $listTitle
     * @param $type
     * @return SPList
     * @internal param ClientRuntimeContext $ctx
     */
    public static function createList(Web $web, $listTitle, $type)
    {
        $ctx = $web->getContext();
        $info = new ListCreationInformation($listTitle);
        $info->BaseTemplate = $type;
        $list = $web->getLists()->add($info);
        $ctx->executeQuery();
        return $list;
    }


    /**
     * @param \Office365\PHP\Client\SharePoint\SPList $list
     */
    public static function deleteList(\Office365\PHP\Client\SharePoint\SPList $list){
        $ctx = $list->getContext();
        $list->deleteObject();
        $ctx->executeQuery();
    }




     private static function uploadFiles($localFilePath, \Office365\PHP\Client\SharePoint\SPList $targetList)
    {

        $ctx = $targetList->getContext();

        $session = Yii::$app->session;

        if($session->has('metadata')){
            $metadata = $session->get('metadata');
        }


        //Tender_x0020_No

   

        $fileCreationInformation = new \Office365\PHP\Client\SharePoint\FileCreationInformation();
        $fileCreationInformation->Content = file_get_contents($localFilePath);
        $fileCreationInformation->Url = basename($localFilePath);

        //print_r($fileCreationInformation); exit;

        $uploadFile = $targetList->getRootFolder()->getFiles()->add($fileCreationInformation);
        $ctx->executeQuery();
        print "File {$uploadFile->getProperty('Name')} has been uploaded\r\n";

        //print_r($metadata[2]); exit;

        $uploadFile->getListItemAllFields()->setProperty('Title',basename($localFilePath));
        $uploadFile->getListItemAllFields()->setProperty('App_x0020_no_x002e_',$metadata[1]);//Document_x0020_Number
        $uploadFile->getListItemAllFields()->setProperty('Document_x0020_Number',$metadata[2]);//
        $uploadFile->getListItemAllFields()->setProperty('Document_x0020_Description',$metadata[0]);
        $uploadFile->getListItemAllFields()->update();
        $ctx->executeQuery();
    }

}
