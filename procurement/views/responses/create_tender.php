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
        <div class="col-9">\
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tender Response</h4>
                    <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                    <div class="heading-elements">
                        <ul class="list-inline mb-0">
                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                        </ul>
                    </div>
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
                            <h6>Application Information</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Tender_No')->textInput() ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Supplier_ID')->textInput() ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">  
                                        
                                    </div>
                                </div>                                 

                            </fieldset>  

                            <div class="form-group">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                            
                            </div>

                        <!-- </form> -->
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

