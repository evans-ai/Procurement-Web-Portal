<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = $user->password_reset_token;// Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->FirstName ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
