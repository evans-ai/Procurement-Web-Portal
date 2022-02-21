<?php

namespace backend\controllers;

use backend\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\PasswordResetRequestForm;
use backend\models\ResetPasswordForm;
use backend\models\SignupForm;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error', 'index', 'request-password-reset', 'reset-password', 'verify-email', 'signup'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index', 'request-password-reset', 'reset-password', 'signup'],
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
    public function actionTest()
    {
        print 'ok am here';
        exit;
        //return $this->render('index');
    }

    public function actionIndex()
    {
        //return $this->render('index');
        return $this->redirect(['recruitment/vacancies']);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {


        $this->layout = 'loginwithimage';
        if (!Yii::$app->user->isGuest) {

            return $this->goHome();
        }


        $model = new LoginForm();


        if ($model->load(Yii::$app->request->post())) {

            //print_r($model); exit;
            //return $this->goBack();
            if ($model->login()) {
                return $this->redirect(['recruitment/index']);
            } else {
                Yii::$app->session->setFlash('error', "Incorrect username or password");
            }
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
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

    public function actionRequestPasswordReset()
    {
        //exit('we are here');
        $this->layout = 'loginwithimage';
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {

                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');


            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }
        $model->Email = "";
        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token = "")
    {

        $this->layout = 'loginwithimage';

        $model = new ResetPasswordForm();

        /*print '<pre>';
        print_r($model); exit;*/

        $model = new ResetPasswordForm($token);
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {


            try {
                if (!strlen($token)) {
                    $token = Yii::$app->request->post('token');
                }

                $model = new ResetPasswordForm($token);

            } catch (InvalidParamException $e) {
                throw new BadRequestHttpException($e->getMessage());
            }


            Yii::$app->session->setFlash('success', 'Your password has been successfully reset.');

            return $this->redirect(['site/login']);
        } else {
            // print_r($model->getErrors());

        }


        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        if (Yii::$app->user->isGuest) {
            //$this->layout = 'guest';
            $this->layout = 'guesttest';
        }
        $model = new SignupForm();


        if ($model->load(Yii::$app->request->post())) {
            //print '<pre>';
            //print_r(Yii::$app->request->post()); exit;
            $userCount = User::find()->where(array("Email" => $model->Email))->count();
            if ($userCount == 0) {
                if ($user = $model->signup()) {

                    Yii::$app->session->setFlash('success', 'Please check your email for account verification.');

                    return $this->redirect(['site/login']);

                } else {

                    var_dump($model->getErrors());
                    exit;
                    Yii::$app->session->setFlash('error', 'Could not save details,please try again');

                }
            } else {
                Yii::$app->session->setFlash('error', 'Email already exists,please use another email');
            }

        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionVerifyEmail($token)
    {
        $user = User::find()->where(array("auth_key" => $token, "status" => 10))->one();
        if ($user) {
            $user->status = $user::STATUS_ACTIVE;
            $user->save();

            Yii::$app->session->setFlash('success', 'Your account has been verified,please log in to proceed.');
        } else {
            Yii::$app->session->setFlash('error', 'Invalid URL,please recheck your email.If you are sure of the email,please try to reset your password for a new link');
        }

        return $this->redirect(['site/login']);
    }
}
