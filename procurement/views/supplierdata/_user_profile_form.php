<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model frontend\models\Supplierdata */
/* @var $form yii\widgets\ActiveForm */

$baseUrl = Yii::$app->params['baseUrl'];

// print_r('<pre>');
// print_r($baseUrl); exit;
$this->title = 'Update Prifile Information';
$name = Yii::$app->user->identity->FirstName.' '.Yii::$app->user->identity->MiddleName;
$this->params['breadcrumbs'][] = ['label'=>'Profile: '.$name,'url'=>['/supplierdata/user-profile']];
$this->params['breadcrumbs'][] = $this->title;

?>

<section id="number-tabs">
    <div class="row justify-content-md-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><?=$this->title;?></h2>                    
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
                                'action' => '/supplierdata/update-user-profile',
                                'options'=>["enctype"=>"multipart/form-data"]
                            ]); ?>
                            <h4 style="padding: 5px; color: #fff;" class="bg-info">Business Details</h4>
                            <?= $form->field($model, 'CompanyName')->textInput(['disabled' => true]) ?>
                            <?= $form->field($model, 'KRA_PIN')->textInput(['placeholder' => 'A123456789B', 'disabled' => true])->label('KRA PIN.') ?>
                            <div class="row">
                            <div class="col-md-6">
                                <?= $form->field($model, 'Cell')->textInput()->label('Telephone No.') ?>
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
                            <div class="form-group">
                            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                            <?= Html::a('Cancel', ['supplierdata/profile'], ['class' => 'btn btn-danger pull-right']) ?>                            
                            </div>

                        <!-- </form> -->
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

