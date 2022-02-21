<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $model frontend\models\Supplierdata */
/* @var $form yii\widgets\ActiveForm */

$baseUrl = Yii::$app->params['baseUrl'];
$drCounter = 0;
$ptnCounter=0;
$partners=0;
$newPartner=0;
?>

<section id="number-tabs">
    <div class="row justify-content-md-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Create/Update Profile</h4>                    
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


                            <?php $form = ActiveForm::begin([
                                'id' => 'dataForm',
                                'action' => $baseUrl.'/supplierdata/create',
                                'options'=>["class"=>"form form-horizontal"]
                            ]); ?>

                            <!-- Step 1 -->
                            <h6>BUSINESS INFORMATION</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-4 col-lg4 col-sm-4">
                                        <?= $form->field($model, 'Key')->hiddenInput()->label(false); ?>
                                        <?= $form->field($model, 'No')->hiddenInput()->label(false); ?>
                                        <?= $form->field($model, 'Name')->textInput()->label('Business Name'); ?>
                                    </div>
                                </div>
                                 
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'PostalAddress')->textInput()->Label('Postal Address') ?>
                                    </div>
                                    <div class="col-md-8 col-lg-8 col-sm-8">
                                        <?= $form->field($model, 'Address')->textInput()->label('Physical Address') ?>
                                         
                                    </div>
                                    
                                </div>
                                 <div class="row">                                   
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Telephone')->textInput()->Label('Company Mobile') ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Email')->textInput() ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'NatureOfBusiness')->textInput() ?>              
                                        </div>
                                    </div>
                                </div>                          
                              
                                <div class="row">
                                     <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'SupplierType')->label('AGPO Category')
                                            ->dropDownList(['Youth'=>'Youth','Women'=>'Women','Disabled'=>'Person With Disability','general'=>'General',],['prompt'=>'Select...']) ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'LicenseNo')->textInput()->label('Registration Number') ?>               
                                        </div>                                       
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'KRA_PIN')->textInput()->label('KRA PIN') ?>
                                         <!-- <?= $form->field($model, 'Street_Road')->textInput()->label('Street/Road') ?>         -->   
                                    </div>
                                </div>
                                <div class="row">
                                    
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                           
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        
                                    </div>
                                </div>
                                

                                <?= $form->field($model, 'Company')->hiddenInput()->label(false) ?>

                            </fieldset>  

                            <div class="form-group">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                            <?= Html::a('Next', ['personel/index', 'id' => $model->No], ['class' => 'btn btn-info']) ?>
                            
                            </div>

                        <!-- </form> -->
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

