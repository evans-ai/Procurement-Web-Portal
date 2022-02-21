<?php
namespace procurement\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use procurement\models\LoginForm;
use procurement\models\SignupForm;
use procurement\models\User;
use procurement\models\PasswordResetRequestForm;
use procurement\models\ResetPasswordForm;
use yii\data\ArrayDataProvider;
use procurement\models\SupplyUser;
use procurement\models\ChangePass;
use procurement\models\validateAccount;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $layout = 'login';
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return 
        [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['verify-email', 'login', 'error', 'index','captcha','login','register','view','apply','about','reset-password', 'requestpasswordreset','validatetoken'],
                        'allow' => true,
                    ],                    
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['get'],
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
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction'
            ],
        ];
    }

    public function actionError()
    {
       $this->layout = 'main';
        if($error=Yii::app()->errorHandler->error)
            $this->render('error', $error);
    }
  
    public function actionLogin(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['home/dashboard']);
        }     
        $model = new LoginForm();        
        if ($model->load(Yii::$app->request->post()) ){            
            if($model->login())
                return $this->redirect(['home/dashboard']); 
        }
        $model->password = '';
        return $this->render('login', 
            [
            'model' => $model,                
        ]);
    }

    /**
     * Login d.
     *
     * @return string
     */
    public function actionIndex(){
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['home/dashboard']);
        }
        $today = date('Y-m-d');        
        $service = Yii::$app->params['ServiceName']['ApprovedTenderList'];
        $tenders = Yii::$app->navhelper->getData($service);;
        $TENDER=[];         
        $tempFilter = ['EOI', 'OT', 'RFP'];
        
        foreach($tenders as $v){
            //print_r($v); exit;
            if(@$v->EndDate < $today || @$v->StartDate > $today ) continue;
            if(in_array(@$v->Procument_Method, $tempFilter)) continue;       
            $TENDER[]= [                    
                'Key' => $v->Key,
                'No' => $v->No,
                'Title' => isset($v->Title)?$v->Title:'',
                //'Requisition_No' => $v->Requisition_No,                    
                'StartDate'=>isset($v->Return_Date)?$v->Return_Date:'',                
                'EndDate'=>isset($v->Closing_Date)?$v->Closing_Date:'',
            ];
        }
        
        $tenderProvider = new ArrayDataProvider([
            'allModels' => $TENDER,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' => [
                'attributes' => ['No', 'Title'],
            ],
        ]);            
        return $this->render('login_info', [
            'tenderProvider'=>$tenderProvider,
            'preqProvider'=>'',
        ]);
    }
    
    public function actionView($id,$myService){
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

        // print('<pre>');
        // print_r($header); exit;
        
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

        return $this->render('view_tender',['data'=>$DataArray,
            'Provider'=> $Provider,'id'=>$id,
        ]);
    }
    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAbout(){
        //$this->layout = 'external2';
        return $this->render('about');
    }

    public function actionVerifyEmail($token){
        $user = User::find()->where(array("auth_key" => $token, "status" => 0))->one();
        if ($user) {
            $user->status = $user::STATUS_ACTIVE;
            $user->DateUpdated = date('Y-m-d H:i:s');
            $user->save();
            Yii::$app->session->setFlash('success', 'Your account has been verified,please log in to proceed.');
        } else {
            Yii::$app->session->setFlash('error', 'Invalid URL,please recheck your email.If you are sure of the email,please try to reset your password for a new link');
        }
        return $this->redirect(['site/login']);
    }
    
    public function actionRegister(){
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {            
            if ($user = $model->signup()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success', 'Check your email for further instructions.');                    
                } 
                else {
                    Yii::$app->session->setFlash('error', 'Sorry, we are unable to send a verification email to the provided email address.');
                }
                return $this->redirect(['/site/login']);
            }
        }
        return $this->render('_userform', [
            'model' => $model,
        ]);
    }

    public function actionRequestpasswordreset(){
       $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()){                
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();//redirect to password reset form
                //return $this->redirect(['./site/reset-password']);
            } 
            else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        return $this->render('requestPasswordreset', [
            'model' => $model,
        ]);
    }

    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');
            return $this->goHome();
        }
        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
