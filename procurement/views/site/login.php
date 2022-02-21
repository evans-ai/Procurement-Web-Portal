
<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-body">
      <!-- Form wizard with number tabs section start -->
      <div class="row justify-content-md-center">
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h4 class="text-center"><b>Login</b></h4>                
              </div>
              <div class="card-content collapse show">
                <div class="card-body">
                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i>Saved!</h4>
                        <?= Yii::$app->session->getFlash('success') ?>
                    </div>
                  <?php endif; ?>
                  <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <h4><i class="icon fa fa-check"></i>Error!</h4>
                        <?= Yii::$app->session->getFlash('error') ?>
                    </div>
                  <?php endif; ?>

                  <div class="card-text"></div>
                    <?php $form = ActiveForm::begin(['id' => 'login-form',
                          'options'=>["class"=>"form form-horizontal"]
                      ]); ?>
                      <div class="form-body">
                        <?= $form->field($model, 'username')->textInput(['autofocus' => true,'required'=>true]) ?>
                        <?= $form->field($model, 'password')->passwordInput(['required'=>true])->label('Password') ?>
                        <?= $form->field($model, 'rememberMe')->checkbox() ?>
                        <?= $form->field($model, 'captcha')->widget(Captcha::className(), [
                            'template' => '<div class="">{image} <button type="button" class="btn btn-default btn-sm" id = "refresh-captcha"><i class="fa fa-history"></i> Refresh </button></div><div class="">{input}</div>',
                            'options' => ['placeholder' => 'Please Type the Security Code above Here!', 'class' => 'form-control'],
                            'imageOptions' => [ 'id' => 'my-captcha-image'],
                        ]) ?>
                        <?php $this->registerJs("
                            $('#refresh-captcha').on('click', function(e){
                                e.preventDefault();
                                $('#my-captcha-image').yiiCaptcha('refresh');
                            })");
                        ?>
						            <p><a href="requestpasswordreset">Forgot password</a></p>
                        <?= Html::submitButton('Login', ['class' => 'btn btn-danger form-control', 'name' => 'login-button', 'style' => 'color: #fff']) ?>
                        <span class="clearfix"></span>                                               
                      </div>
                     
                      <?php ActiveForm::end(); ?>
                    </div>
                </div>      
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
