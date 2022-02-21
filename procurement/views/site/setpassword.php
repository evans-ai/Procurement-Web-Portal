<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
$url = $baseUrl . '/site/setpassword';
?>

<div class="app-content content">

  <div class="content-wrapper">
    <div class="content-body">
      <!-- Form wizard with number tabs section start -->
      <section id="number-tabs">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Reset Password</h4>                
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

                  <!-- <form id="file-form" action="<?= $url; ?>" method="POST"  enctype="multipart/form-data" class="form form-horizontal"> -->
                  <?php $form = ActiveForm::begin([
                            'id' => 'dataForm',
                            'action' => $baseUrl.'/site/setpassword',
                            'options'=>["class"=>""]
                        ]); ?>
                    <input name="Email" type="hidden" id="Email" value="<?= $Email ?>" />
                  <fieldset class="form-group">
                      <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <?= $form->field($model, 'newpassword1')->passwordInput()->label('New Password'); ?>
                        </div>
                      </div> 
                      <div class="row">
                        <div class="col-md-4 col-lg-4 col-sm-4">
                          <?= $form->field($model, 'newpassword2')->passwordInput()->label('Confirm Password'); ?>
                        </div>
                      </div>                     
                  </fieldset>
                  
                  <div class="form-group">
                      <div class="button-group">
                        <?= Html::submitButton('Set Password', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                        <?= Html::a('Back',['./site/index'],['class' => 'btn btn-info', 'name' => 'login-button']) ?>
                      </div>                    
                  </div>
                 <?php ActiveForm::end(); ?> 
                  <!-- </form> -->
                </div>
              </div>
            </div>
          </div>
        </section>
    </div>
  </div>
</div>

