<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
<div class="password-reset">
    <p>Hello <?= Html::encode($user->FirstName) ?>,</p>
    <p>We heard that you forgot your password to our portal. Sorry about that.</p>

    <p>Click on the the link below to reset your password. If the link doesnt open, please copy and paste on your browser</p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
    <p>Kind regards<br>Procurement Team<br>Financial Reporting Center(FRC</p>
</div>
