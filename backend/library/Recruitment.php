<?php
namespace backend\library;
use yii;
use yii\base\Component;
use app\models\Services;
use app\models\Employee;
use app\models\User;
use app\models\Department;

class Recruitment extends Component{

	public function Applicationstatus(){//is the application submitted or not
		$session = \Yii::$app->session;
		if($session->has('Communication')){
			$empdata = $session->get('Communication');
			$Application_no = $empdata->No;

			$creds = (object)[];
			if(Yii::$app->user->isGuest){
	            $creds->UserName = 'RBSS\Branton.Kiprono';
	            $creds->PassWord = 'Pass@123';
	        }else{
	            $identity = \Yii::$app->user->identity;
	            $creds->UserName = $identity->Id;
	            $creds->PassWord = $identity->Pass;
	        }

	        $url = new Services('Applicant_List');//('Applicant');
			$soapWsdl = $url->getUrl();
			if (!Yii::$app->navision->isUp($soapWsdl)) {
				throw new \yii\web\HttpException(503, 'Service unavailable');
			}
            //get job id, which is a query param 
             $jobid = $_GET['id'];//$this->getJobid($Application_no);            
			//reading data
			$filter = [];
        	$filter = [
                ['Field' => 'No', 'Criteria' => '='.$Application_no],
                ['Field' => 'Job_ID', 'Criteria' => $jobid ]
            ];
        	$result = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);

            

        	if(is_object($result) && isset($result->ReadMultiple_Result->Applicant_List)){
        		
        		$status = $result->ReadMultiple_Result->Applicant_List[0]->Submitted;
               // print '<pre>'; print_r($status); exit;
        		return $status;
        	}else{
                $this->clearsessions();
        		return false;
        	}

		}else{
			return false;
		}
	}//end status method
	public function getName($empno){
		$employee = Employee::find()
            ->select(['[First Name]', '[Middle Name]', '[Last Name]'])
            ->where(['No_' => $empno])
            ->asArray()
            ->one();
        $name = $employee['First Name'] . " " . $employee['Middle Name'] . " " . $employee['Last Name'];
        return $name;
	}
	public function getUsername($empno){
		$user = User::find()->select('[User ID]')->where(['[Employee No_] '=> $empno])->one();
		return isset($user->{'User ID'})?$user->{'User ID'}: false;
	}
	public function getDirectoratecode($empno){
        @$user = Employee::find()->where(['[No_]'=> $empno])->one();
        return isset($user->{'Global Dimension 1 Code'})?$user->{'Global Dimension 1 Code'}:'Not Set';
    }
	public function getDirectorate($empno){
		$dimensionvalue = $this->getDirectoratecode($empno);
		$department = Department::find()->select(['Name'])->where(['Code' =>$dimensionvalue])->one();

    	$directorate = isset($department->{'Name'})?$department->{'Name'}:'Directorate Not Set';

    	return $directorate; 
	}
	//if the recruitment need has a rec panel
	public function hasPanel($needid){
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
         $result = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);

        if (property_exists($result, 'ReadMultiple_Result')) {//classy error forestalling
             if(isset($result->ReadMultiple_Result->RecruitmentPanel)){
                    return true;
                 }else{
                    return false;
                 }
        }
        
	}
    public function ispanelmember($empno,$applicationid){
        $username = Yii::$app->user->identity->Id;
        $password = Yii::$app->user->identity->Pass;
        $jobid = $this->getJobid($applicationid);



        $url = new Services('recruitmentpanel');
        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $soapWsdl = $url->getUrl();         
         if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
         $filter=[];
         $filter = [
            ['Field' => 'Employee_Number', 'Criteria' =>$empno],
            ['Field' => 'Recruitment_Need', 'Criteria' =>$jobid],
        ];
        
         $result = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);

         if(isset($result->ReadMultiple_Result->RecruitmentPanel)){
            return true;
         }else{
            return false;
         }
    }

	//get recruitment panel
	public function getPanel($needid){
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
         $result = Yii::$app->navision->readEntries($creds, $soapWsdl, $filter);

         if(isset($result->ReadMultiple_Result->RecruitmentPanel)){
         	return $result->ReadMultiple_Result->RecruitmentPanel;
         }else{
         	return false;
         }
	}

	//get emails
	public function getEmail($empno){
		@$user = User::find()->where(['[Employee No_]'=> $empno])->one();
        return isset($user->{'E-Mail'})?$user->{'E-Mail'}:'Not Set';
	}

    //encrypt password
    public function encrypt($detail)
    {
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $key = YII::$app->params['key'];
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($detail, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        return base64_encode($iv . $hmac . $ciphertext_raw);
    }

    //descrypt password
    public function decrypt($c)
    {
        $key = YII::$app->params['key'];
        $c = base64_decode($c);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($c, $ivlen + $sha2len);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary = true);
        return openssl_decrypt($ciphertext_raw, $cipher, $key, $options = OPENSSL_RAW_DATA, $iv);
    }

    //hash password

    public function hash($text){
        return openssl_digest($text, "sha224", false);
    }
    public function getRequisitionNature($applicationId){
        $identity = \Yii::$app->user->identity;
        $username = 'FNjambi';//$identity->Id;
        $password = 'Pass20$Cis';//$identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'No', 'Criteria' => $applicationId];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $jobid =  $results->ReadMultiple_Result->Applicant_List[0]->{'Job_ID'};


        //get recruitment type: irs or nrs
        $url= new Services('recruitment');
        $soapWsdl=$url->getUrl();
        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');
        }
        $filter = [];
        $filter = ['Field' => 'No', 'Criteria' => $jobid];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $rec_type = NULL;

        if(property_exists($results->ReadMultiple_Result,'Recruitment_Request')){
             $rec_type =  $results->ReadMultiple_Result->Recruitment_Request[0]->{'Recruitment_Type'};
        }
       

        return $rec_type;


    }
    public function checknrsrefereeresponse($applicationid){
        //return bool
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('nrscheck');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'Application_ID', 'Criteria' => $applicationid];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        if(property_exists($results->ReadMultiple_Result, 'NRSReferenceCheck')){
            return true;
        }else{
            return false;
        }

    }
    public function checkirsrefereeresponse($applicationid){
        //return bool
        $identity = \Yii::$app->user->identity;
        $username = $identity->Id;
        $password = $identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('irscheck');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'Application_ID', 'Criteria' => $applicationid];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        if(property_exists($results->ReadMultiple_Result, 'IRSReferenceCheck')){
            return true;
        }else{
            return false;
        }
    }
    public function hasRefereeresponse($applicationid){
        $responsetype = $this->getRequisitionNature($applicationid);
        if($responsetype == 'Nationally_Recruited_Staff_NRS'){
            //use nrs check
            return $this->checknrsrefereeresponse($applicationid);

        }else{
            //use irs check
            return $this->checkirsrefereeresponse($applicationid);
        }
    }
    public function getRefereeresponse($applicationid){
        $responsetype = $this->getRequisitionNature($applicationid);
        if($responsetype == 'Nationally_Recruited_Staff_NRS'){
            //use nrs check
                if($this->checknrsrefereeresponse($applicationid)){
                            $identity = \Yii::$app->user->identity;
                    $username = $identity->Id;
                    $password = $identity->Pass;

                    $creds = (object)[];
                    $creds->UserName = $username;
                    $creds->PassWord = $password;
                    $url= new Services('nrscheck');
                    $soapWsdl=$url->getUrl();

                    if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
                        throw new \yii\web\HttpException(503, 'Service unavailable');

                    }
                    $filter = [];
                    $filter = ['Field' => 'Application_ID', 'Criteria' => $applicationid];
                    $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
                    if(property_exists($results->ReadMultiple_Result, 'NRSReferenceCheck')){
                        return $results->ReadMultiple_Result->NRSReferenceCheck;
                   }
            }//response check

        }//else Irs responses
        else{
            //use irs check
            if($this->checkirsrefereeresponse($applicationid)){
                        $identity = \Yii::$app->user->identity;
                $username = $identity->Id;
                $password = $identity->Pass;

                $creds = (object)[];
                $creds->UserName = $username;
                $creds->PassWord = $password;
                $url= new Services('irscheck');
                $soapWsdl=$url->getUrl();

                if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
                    throw new \yii\web\HttpException(503, 'Service unavailable');

                }
                $filter = [];
                $filter = ['Field' => 'Application_ID', 'Criteria' => $applicationid];
                $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
                if(property_exists($results->ReadMultiple_Result, 'IRSReferenceCheck')){
                    return $results->ReadMultiple_Result->IRSReferenceCheck;
                }
            }//check if
        }//irs responses
    }
    public function getJobid($applicationId){
       //replace credentials here with those od service account on production server
       //exit('we are here');
        $username = 'RBSS\Branton.Kiprono';//$identity->Id;
        $password = 'Pass@123';//$identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant_List');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'No', 'Criteria' => $applicationId];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);
        $jobid =  $results->ReadMultiple_Result->Applicant_List[0]->{'Job_ID'};

        return $jobid;

    }

    //get job description/ Recruitment Need descriptive Name

    public function getJobdescription($applicationId){

        $jobid = $this->getJobid($applicationId);
        $username = \Yii::$app->user->identity->Id;
        $password = \Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('recruitment');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'No', 'Criteria' => $jobid];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        if(property_exists($results->ReadMultiple_Result, 'Recruitment_Request')){
            $description = $results->ReadMultiple_Result->Recruitment_Request[0]->{'Description'};
        }
    else{
        $description = 'Undefined Position';
    }

        return $description;
    }

    public function getApplicant($applicationId){

        //$jobid = $this->getJobid($applicationId);
        $username = \Yii::$app->user->identity->Id;
        $password = \Yii::$app->user->identity->Pass;

        $creds = (object)[];
        $creds->UserName = $username;
        $creds->PassWord = $password;
        $url= new Services('Applicant');
        $soapWsdl=$url->getUrl();

        if(!Yii::$app->navision->isUp($soapWsdl,$creds)) {
            throw new \yii\web\HttpException(503, 'Service unavailable');

        }
        $filter = [];
        $filter = ['Field' => 'No', 'Criteria' => $applicationId];
        $results = Yii::$app->navision->readEntries($creds, $soapWsdl,$filter);

        if(property_exists($results->ReadMultiple_Result, 'Applicant_Card')){
            $applicant = $results->ReadMultiple_Result->Applicant_Card[0];
        }
    else{
        $applicant = 'Undefined Applicant';
    }

        return $applicant;
    }

    public function clearsessions(){
        $session = Yii::$app->session;
        $session->remove('Key');
        $session->remove('General');
        $session->remove('Personal');
        $session->remove('Communication');
        $session->remove('Academic');//Qualifications
        $session->remove('Experience');
        $session->remove('Referees');
        $session->remove('Attachments');//Attachments
        $session->remove('Qualification');
    }

    //get a duty station
    public function getstation($code)
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
//display users applications
    public function myapplications(){
        $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];
        if(Yii::$app->user->identity->ApplicantId){
             $filter = ['ApplicantID'=> Yii::$app->user->identity->ApplicantId ];

             $result = Yii::$app->navhelper->getData($service,$filter);
             if(is_array($result)){
                 return count($result);
             }else{
                return 0;
             }
            
          }else{
            return 0;
          }
         
              
      }

      public function jobapplications(){
        $baseUrl = Yii::$app->request->baseUrl;
        $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];
        if(Yii::$app->user->identity->ApplicantId){
             $filter = ['ApplicantID'=> Yii::$app->user->identity->ApplicantId ];

             $result = Yii::$app->navhelper->getData($service,$filter);
             if(is_array($result)){
                foreach($result as $app){
                    $desc = (isset($app->JobDescription)?$app->JobDescription:'Not Set');
                    print '<a href="'.$baseUrl.'/recruitment/jobapplication/?jobapplicantid='.$app->ApplicationNo.'">
                                                    <div class="media">
                                                        <div class="media-left align-self-center"><i class="material-icons icon-bg-circle bg-cyan">add_box</i></div>
                                                        <div class="media-body">
                                                            <h6 class="media-heading">'.$desc.'</h6>
                                                            <small>
                                                                    <time class="media-meta text-muted" datetime=""> Applied on:'.$app->Application_Date.'</time></small>
                                                            
                                                        </div>
                                                    </div>
                                            </a>';
                }
             }else{
                return 0;
             }
            
          }else{
            return 0;
          }
         
              
      }

//display users qualifications

      public function myqualifications(){
         $service = Yii::$app->params['ServiceName']['Qualifications'];
         if(Yii::$app->user->identity->ApplicantId){
                $filter = ['Applicant_No'=> Yii::$app->user->identity->ApplicantId ];
                $result = Yii::$app->navhelper->getData($service,$filter);
                return $result;

         }else{
            return 'You have not yet attached any qualifications';
         }
      }


//display user experience

      public function myexperinces(){
         $service = Yii::$app->params['ServiceName']['experience'];
         if(Yii::$app->user->identity->ApplicantId){
                $filter = ['Applicant_No'=> Yii::$app->user->identity->ApplicantId ];
                $result = Yii::$app->navhelper->getData($service,$filter);
                return $result;

         }else{
            return 'You have not yet attached any experience';
         }
      } 


//display user referees

      public function myreferees(){
          $service = Yii::$app->params['ServiceName']['referees'];
         if(Yii::$app->user->identity->ApplicantId){
                $filter = ['No'=> Yii::$app->user->identity->ApplicantId ];
                $result = Yii::$app->navhelper->getData($service,$filter);
                return $result;

         }else{
            return 'You have not yet attached any referees';
         }
      } 
    

//display user referees

      public function myattachments(){

        $service = Yii::$app->params['ServiceName']['Attachments'];
         if(Yii::$app->user->identity->ApplicantId){
                $filter = ['Applicant_No'=> Yii::$app->user->identity->ApplicantId ];
              
                
                $result = Yii::$app->navhelper->getData($service,$filter);
                return $result;

         }else{
            return 'You have not yet attached any Documents.';
         }
      } 


//show comments
       public function mycomments(){
        $service = Yii::$app->params['ServiceName']['appComments'];
         if(Yii::$app->user->identity->ApplicantId){
                $filter = ['Applicant_No'=> Yii::$app->user->identity->ApplicantId ];
                $result = Yii::$app->navhelper->getData($service,$filter);
                return $result;

         }else{
            return 'You have not yet attached any Documents.';
         }
      } 

      //validate account

public function hasprofile(){
        $service = Yii::$app->params['ServiceName']['HRUser'];
         if(!Yii::$app->user->isGuest && Yii::$app->user->identity->ApplicantId){
                /*$filter = ['Email'=> Yii::$app->user->identity->Email ];
                $result = Yii::$app->navhelper->getData($service,$filter);
                if(is_array($result) && count($result)){
                    return true;
                }else{
                    return false;
                }*/
                return true;

         }else{
            return false;
         }
      } 

//has already applied for a job

      public function hasapplied($applicantid, $recruitmentid){
        $service = Yii::$app->params['ServiceName']['Job_Applicant_Card'];

         $data = [];
         $data['Recruitment_Need_Code'] = $recruitmentid ;
         $data['ApplicantID'] = $applicantid;

        $result = Yii::$app->navhelper->getData($service,$data);//Just pass service Name and form data

        if(is_array($result) && count($result)){
            return true;
        }
        else{
            return false;
        }

      }

      public function jobid($rneedid){
         $service = Yii::$app->params['ServiceName']['Recruitment_Needs'];
         $filter['No']= $rneedid;   //exit($rneedid);

         $result = Yii::$app->navhelper->getData($service,$filter);

         if(is_array($result)){
            return $result[0]->Job_ID;
         }
         else{
            return false;
         }

      }

      public function jobresponsibilitiescategories($jobid){//filter all categories and return unique values
         $service = Yii::$app->params['ServiceName']['JobResponsibilities'];
         $filter['Job_ID']= $jobid;

         $result = Yii::$app->navhelper->getData($service,$filter);
         /*print('<pre>');
         print_r($result);
         exit();*/
         $cats = [];
         if(is_array($result)){
            foreach($result as $res){
                if(property_exists($res,'Remarks')){
                $cats[] = $res->Job_Responsibility_Category ;
                }
            }
           
            $un = array_unique($cats);
            asort($un);//sort value in asc order
            $cats =[];
            foreach($un as $k=>$v){
                $cats[$k] = $v ;
            }
            
            return json_encode(array_unique($cats));
         }
         else{
            return false;
         }
      }

       public function jobresponsibilities($catid,$recneedid){//filter all categories and return unique values

         $service = Yii::$app->params['ServiceName']['JobResponsibilities'];
         $jobid = $this->jobid($recneedid);
         $filter['Job_Responsibility_Category'] = $catid;
         $filter['Job_ID'] = $jobid;
         $result = Yii::$app->navhelper->getData($service,$filter);

         
         if(is_array($result)){ 
            $resp = '<table class="table">';           
           foreach($result as $res){

            $responsibility = property_exists($res,'Responsibility')?$res->Responsibility:'Responsibility Not Set';
                $resp .='<tr>
                    <td>'.$responsibility.'</td>
                </tr>';
           }

           $resp .='</table>';
           return $resp;
         }
         else{
            return false;
         }
      }

      public function responsibilitycategory($categoryid){
        $service = Yii::$app->params['ServiceName']['JobResponsibilityCategory'];
        $filter['Job_Responsibility_Category'] = $categoryid;
        $result = Yii::$app->navhelper->getData($service,$filter);
        if(is_array($result)){
            return $result[0]->Description;
         }
         else{
            return 'Description Not Set';
         }
      }

    public function internshipspresent(){
        $service = Yii::$app->params['ServiceName']['Recruitment_Needs'];
        $filter['Appointment_Type'] = 'INTERNSHIP';
        $result = Yii::$app->navhelper->getData($service,$filter);
        if(is_array($result) and count($result)){
            return true;
         }
         else{
            return false;
         }
      }






}

?>