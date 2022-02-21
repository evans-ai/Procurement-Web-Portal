<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\captcha\Captcha;
?>

<div class="row justify-content-md-center">
  <br>
  <div class="col-md-7">
    <div class="card">
        <div class="card-header">
          <h4 style="text-align: center; font-weight: 600;">Create Supplier Account</h4>
        </div>
        <div class="card-content collpase show">
          <div class="card-body">
            <?php if (Yii::$app->session->hasFlash('success')){ ?>
              <div class="alert alert-success alert-dismissable">
                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                  <h4><i class="icon fa fa-check"></i>Saved!</h4>
                  <?= Yii::$app->session->getFlash('success') ?>
              </div>
            <?php } ?>
            <?php if (Yii::$app->session->hasFlash('error')){ ?>
              <div class="alert alert-danger alert-dismissable">
                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                  <h4><i class="icon fa fa-check"></i>Error!</h4>
                  <?= Yii::$app->session->getFlash('error') ?>
              </div>
            <?php } ?>
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <h4 style="padding: 5px; color: #fff;" class="bg-info">Business Details</h4>
            <?= $form->field($model, 'CompanyName')->textInput() ?>
            <?= $form->field($model, 'KRA_PIN')->textInput(['placeholder' => 'A123456789B']) ?>
            <div class="row">
              <div class="col-md-6">
                <?= $form->field($model, 'Cell')->textInput() ?>
              </div>
              <div class="col-md-6">
                <?= $form->field($model, 'Email')->textInput() ?>
              </div>
            </div>
            <h4 style="padding: 5px; color: #fff;" class="bg-info">Contact Person</h4>
            <div class="row">
              <div class="col-md-6">
                <?= $form->field($model, 'FirstName')->textInput() ?>
              </div>
              <div class="col-md-6">
                <?= $form->field($model, 'MiddleName')->textInput(['required'=>true])->label('Other Names') ?>
              </div>
            </div>
            <h4 style="padding: 5px; color: #fff;" class="bg-info">Login Details</h4>
            <div class="row">
                <div class="col-md-6">
                <?= $form->field($model, 'pwd')->passwordInput(['required'=>true])->label('Password') ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'confirmpassword')->passwordInput(['required'=>true])->label('Confirm Password') ?>
                </div>
            </div>
            <h4 style="padding: 5px; color: #fff;" class="bg-info">Security Verification</h4>
            <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                'template' => '<div class="">{image} <button type="button" class="btn btn-default btn-sm" id = "refresh-captcha"><i class="fa fa-history"></i> Refresh </button></div><div class="">{input}</div>',
                'options' => ['placeholder' => 'Please Type the Security Code above', 'class' => 'form-control'],
                'imageOptions' => [ 'id' => 'my-captcha-image'],
            ])->label(false) ?>
            <?php $this->registerJs("
                $('#refresh-captcha').on('click', function(e){
                    e.preventDefault();
                    $('#my-captcha-image').yiiCaptcha('refresh');
                })");
            ?>   
            <div>
                <?= Html::submitButton('Sign Up', ['class' => 'btn btn-info', 'name' => 'login-button']) ?>
                <?= Html::a('Cancel', ['site/index'], ['class' => 'btn btn-danger']) ?>

            </div>
            <span class="clearfix"></span>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
    </div>
  </div>
</div>
