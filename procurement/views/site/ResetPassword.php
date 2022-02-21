<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Reset password';

?>

<div class="app-content content">

  <div class="content-wrapper">
    <div class="content-body">
      <!-- Form wizard with number tabs section start -->
      <div class="row justify-content-md-center">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">RESET PASSWORD</h4>                
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
                          <div class="card-text"><p>Type your new password below</p></div>                          
                          <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>
                              <div class="form-body">                                  
                                  <div class="form-group row">
                                    <div class="col-md-12 mx-auto">
                                      <?= $form->field($model, 'password')->passwordInput() ?>
                                    </div>
                                  </div> 
                                  <div class="form-group row">
                                    <div class="col-md-12 mx-auto">
                                      <?= $form->field($model, 'passwordconfirm')->passwordInput() ?>
                                    </div>
                                  </div>                                                
                              </div>

                              <div class="form-actions text-center">
                                   <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
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

