<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->auth_key]);
?>
<div class="verify-email">
    <p>Dear <?= Html::encode($user->FirstName) ?>,</p>

    <p>Thank you for expressing your interest to do business with the Financial Reporting Center(FRC.<br>Kindly  follow the link below to verify your email address:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
    <p>Kind regards<br><b>Procurement Team</b><br>Financial Reporting Center(FRC<br>Rahimtulla Tower, 13th Floor,<br>
Upper Hill Road, Opp UK High Commission.<br>
P.O. Box 57733 - 00200<br>
Nairobi, Kenya </p>
</div>
