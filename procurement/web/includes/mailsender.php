<?php
use common\models\Smtpmail;

require_once 'PHPMailer-master/PHPMailerAutoload.php';

function SendMail($EmailArray,$Subject,$Message)
{
	// Send mail
	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP

	// this is temporary. until mail server works
	$mail->SMTPAuth = true;                  // enable SMTP authentication
	//$mail->Host = "smtp.gmail.com:587"; // SMTP server
	// print('<pre>');
	// print_r(Yii::$app->components['mailer']['transport']); exit;
	$creds=Yii::$app->components['mailer']['transport'];
	$mail->Host = $creds['host']; 
	$mail->Username = $creds['username']; 
	$mail->Password = $creds['password']; 
	$mail->From = Yii::$app->params['supportEmail']; 

	$mail->SMTPOptions = array(
	   'ssl' => array(
	     'verify_peer' => true,
	     'verify_peer_name' => true,
	     'allow_self_signed' => true
	    )
	);
	           
	//$mail->Port = 25; // optional if you don't want to use the default 	
	$mail->Port = 587;		
	// $mail->From = $smtpmailer['User ID'];
	$mail->FromName = "Procurement";
	$mail->Subject = $Subject;
	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	//$mail->MsgHTML($issue . "<br /><br />" . $comment);
	$mail->MsgHTML($Message);
			 
	// Add as many as you want
	foreach($EmailArray AS $key => $row)
	{
		extract($row);
		//$Email='cngeno11@gmail.com';
		$mail->AddAddress($Email, $Name);
	}		
	// If you want to attach a file, relative path to it
	//$mail->AddAttachment("images/phpmailer.gif");             // attachment
			
	$response= NULL;
	if(!$mail->Send()) 
	{
		$msg = "Mailer Error: " . $mail->ErrorInfo;
		$Sent = 0;
	} else {
		$msg = "Message sent!";
		$Sent = 1;
	}
	return $Sent;
	

   }

 // function mailsend($from, $subject,$message){
 //         $mail=Yii::$app->Smtpmail;
 //        $mail->SetFrom($from, 'procument');
 //        $mail->Subject    = $subject; 
 //        $mail->MsgHTML($message);
 //        $mail->AddAddress($to, " ");
 //        if(!$mail->Send()) {
 //            echo "Mailer Error: " . $mail->ErrorInfo;
 //        }else {
 //            echo "Message sent!";
 //        }
 //    }

function Sendattachment($EmailArray,$Subject,$Message, $Filename)
{
	// Send mail
	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
			
	// SMTP Configuration
	//$mail->Port = 465; // optional if you don't want to use the default 
	//$mail->SMTPDebug = 3;
	
	$smtpmailer = Smtpmail::find()
		->where(['User ID' => 'hr.payroll@act.or.ke'])
		->one();

	$mail->SMTPAuth = true;                  // enable SMTP authentication
	$mail->Host = $smtpmailer['SMTP Server'];// SMTP server
	$mail->Username = $smtpmailer['User ID'];
	$mail->Password = $smtpmailer['Password']; 
	           
	//$mail->Port = 25; // optional if you don't want to use the default 
			
$mail->From = $smtpmailer['User ID'];
	$mail->FromName = "HR";
	$mail->Subject = $Subject;
	$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
	//$mail->MsgHTML($issue . "<br /><br />" . $comment);
	$mail->MsgHTML($Message);
			 
	// Add as many as you want
	foreach($EmailArray AS $key => $row)
	{
		extract($row);
		$mail->AddAddress($Email, $Name);
	}		
	// If you want to attach a file, relative path to it
	$mail->AddAttachment($Filename);  // attachment
			
	$response= NULL;
	if(!$mail->Send()) 
	{
		$msg = "Mailer Error: " . $mail->ErrorInfo;
		$Sent = 0;
	} else {
		$msg = "Message sent!";

		$Sent = 1;
	}
	return $Sent;
}	




function getGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        return $uuid;
    }
}