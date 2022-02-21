<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please choose your new password:</p>

    <div class="row">



                            



        <div class="col-lg-12">


            <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i>Saved!</h4>
                        <?= Yii::$app->session->getFlash('success') ?>

                         <?= Html::a('Login',['./site/login'],['class' => '', 'name' => 'login-button']) ?>
                    </div>
                <?php endif; ?>


                <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i>Error!</h4>
                        <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                  <?php endif; ?>
            
            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                 <?php //$form->field($model, 'token')->passwordInput(['autofocus' => true,'placeholder'=>'Password Reset Code'])->label('Reset Code  (Sent to your Email)') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'passwordconfirm')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
