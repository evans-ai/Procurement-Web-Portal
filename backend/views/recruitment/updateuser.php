<?php
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Update User';
 $baseUrl = Yii::$app->request->baseUrl;
 $applicant_no = $session->get('Applicantid');


 $this->render('_userform');


 ?>