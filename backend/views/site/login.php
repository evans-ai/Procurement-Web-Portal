<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'RBA - Recruitment Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="alert alert-success alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4><i class="icon fa fa-check"></i>Saved!</h4>
        <?= Yii::$app->session->getFlash('success') ?>
    </div>
<?php endif; ?>
<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert alert-danger alert-dismissable">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
        <h4 class="text-white"><i class="icon fa fa-check"></i>Error!</h4>
        <?= Yii::$app->session->getFlash('error') ?>
    </div>
<?php endif; ?>
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

<?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('Email or Mobile') ?>

<?= $form->field($model, 'password')->passwordInput() ?>








<?= $form->field($model, 'rememberMe')->checkbox() ?>


<div class="form-group">
    <?= Html::submitButton('<i class="ft-log-in"></i> Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

    <?php Html::a('Sign Up', ['./recruitment/register'], ['class' => 'btn btn-info', 'name' => 'login-button']) ?>
</div>


<?php ActiveForm::end(); ?>
										