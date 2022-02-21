<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <!-- Form wizard with number tabs section start -->
            <section id="number-tabs">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Supplier Sign Up.</h4>
                                
                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">

                                  <?php if (Yii::$app->session->hasFlash('success')): ?>
                                      <div class="alert alert-success alert-dismissable">
                                          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                          <h4><i class="icon fa fa-check"></i>Saved!</h4>
                                          <?= Yii::$app->session->getFlash('success') ?>

                                           <?= Html::a('Login',['./site/login'],['class' => 'btn btn-info', 'name' => 'login-button']) ?>
                                      </div>
                                  <?php endif; ?>


                              <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                                  <div class="alert alert-danger alert-dismissable">
                                      <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                      <h4><i class="icon fa fa-check"></i>Error!</h4>
                                      <?= Yii::$app->session->getFlash('error') ?>
                                  </div>
                                <?php endif; ?>


                                    <div class="col-md-12">
                      <?php $form = ActiveForm::begin(['id' => 'login-form','action' => './register',]); ?>

                     <div class="form-group col-m-12 row">
                                                    
                          <div class="col-sm-12 col-md-4">
                              <?= $form->field($model, 'FirstName')->textInput(['autofocus' => true]) ?>
                          </div>

                          <div class="col-sm-12 col-md-4">
                              <?= $form->field($model, 'MiddleName')->textInput() ?>
                          </div>

                          <div class="col-sm-12 col-md-4">
                              <?= $form->field($model, 'LastName')->textInput() ?>
                          </div>



                      </div>


                        <div class="form-group col-m-12 row">
                        <div class="col-sm-12 col-md-4">
                              <?= $form->field($model, 'Email')->textInput(['type'=>'email']) ?>
                          </div>

                          <div class="col-sm-12 col-md-4">
                              <?= $form->field($model, 'Cell')->textInput() ?>
                          </div>

                          <div class="col-sm-12 col-md-4">
                              <?= $form->field($model, 'Phone')->textInput() ?>
                          </div>
                      </div>


                      <div class="form-group col-m-12 row">
                         <div class="col-sm-6">                                                     
                                                        <?= $form->field($model, 'Gender')->dropDownList(
                                                            ['Male'=>'Male','Female'=>'Female'],
                                                            ['prompt'=>'Choose Your Gender']); ?>
                          </div>
                           <div class="col-sm-4">
                                                       

                                                    <?= $form->field($model, 'Extension')->textInput(['placeholder'=>'e.g +256']) ?>

                                                    
                              
                          </div>


                            </div>
                      </div>
                      


                      <div class="form-group col-m-12 row">
                        <div class="col-sm-12 col-md-6">
                              <?= $form->field($model, 'pwd')->passwordInput()->label('Account Password') ?>
                          </div>

                          <div class="col-sm-12 col-md-6">
                              <?= $form->field($model, 'confirmpassword')->passwordInput()->label('Confirm Password') ?>
                          </div>
                      </div>
                       

                       

                        <div class="form-group">
                          <?= Html::submitButton('Sign Up', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                         
                        </div>

                        

                      <?php ActiveForm::end(); ?>
                    <div>
                                             

                                   

                                </div><!--end card body-->
                             </div><!--end card content-->
                         </div><!--end card-->
                     </div><!--end col-12-->
                 </div><!--end row-->
            </section>
        </div><!--end content body-->
    </div><!--end content body-->
</div><!--end app content-->
<script>
jQuery.noConflict();
(function( $ ) {
  $(function() {
    // More code using $ as alias to jQuery
    alert('ok');
  });
})(jQuery);
  </script>
