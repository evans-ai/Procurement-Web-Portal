<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
$url = $baseUrl . '/site/validatetoken';
?>

<div class="app-content content">

  <div class="content-wrapper">
    <div class="content-body">
      <!-- Form wizard with number tabs section start -->
      <div class="row justify-content-md-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Validate Token</h4>                
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

                <div class="card-content collpase show">
                      <div class="card-body">
                          <div class="card-text"><p>Enter the Token from your Email to Validate</p></div>                          
                          <?php $form = ActiveForm::begin([
                              'id' => 'dataForm',
                              'class'=>'form form-control',
                              'action' =>'/site/validatetoken',
                              'options'=>["class"=>""]
                          ]); ?>
                              <div class="form-body">
                                  <div class="form-group row">
                                    <div class="col-md-12 mx-auto">
                                      <?= $form->field($model, 'Email')->textInput(['readonly'=>true])->label('Email'); ?>
                                    </div>
                                  </div>
                                  <div class="form-group row">
                                    <div class="col-md-12 mx-auto">
                                      <?= $form->field($model, 'password_reset_token')->textInput()->label('Validation Token'); ?>
                                    </div>
                                  </div>                                                
                              </div>

                              <div class="form-actions text-center">
                                   <?= Html::submitButton('Validate', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                                    <?= Html::a('Back',['./site/index'],['class' => 'btn btn-info', 'name' => 'login-button']) ?>
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
  </div>
</div>

