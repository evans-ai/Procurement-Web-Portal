<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Password Reset';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
   //print_r($Email); exit;
$url = $baseUrl . '/site/resetpassword';
?>

<div class="app-content content">
  <div class="content-wrapper">
    <div class="content-body">
      <div class="row justify-content-md-center">
          <div class="col-md-6">
              <div class="card">
                  <div class="card-header">
                      <h4 class="card-title text-center" id="horz-layout-card-center">PASSWORD RESET</h4>
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

                  </div>
                  <div class="card-content collpase show">
                      <div class="card-body">
                          <div class="card-text"><p>Enter your Email that you used to log in</p></div>                          
                          <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form','class'=>'form form-control']); ?>
                              <div class="form-body">
                                  <div class="form-group row">
                                    <div class="col-md-12 mx-auto">
                                      <?= $form->field($model, 'Email')->textInput(['type'=>'email','required'=>true,'autofocus' => true]) ?>
                                    </div>
                                  </div>                                                
                              </div>

                              <div class="pull-right">
                                  <?= Html::submitButton('Send', ['class' => 'btn btn-primary']) ?>
                              </div>
							  <span class="clearfix"></span>
                         <?php ActiveForm::end(); ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

