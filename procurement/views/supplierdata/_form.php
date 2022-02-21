<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model frontend\models\Supplierdata */
/* @var $form yii\widgets\ActiveForm */

$baseUrl = Yii::$app->params['baseUrl'];
$drCounter = 0;
$ptnCounter=0;
$partners=0;
$newPartner=0;

 //print_r('<pre>');
 //print_r($agpo); exit;

?>

<div class="container">
    <br>
    <div class="card">
        <div class="card-header" style="background-color: #fff">
            <h1 class="card-title">Business Profile</h1>
        </div>
        <div class="card=content collapse show">
            <div class="card-body">
                <?=$this->render('steps', ['active' => 'particulars']) ?>
                <br><br>
                <br>
                <hr/>
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->field($model, 'Key')->hiddenInput()->label(false); ?>
                <?= $form->field($model, 'No')->hiddenInput()->label(false); ?>
                <?= $form->field($model, 'Company')->hiddenInput()->label(false); ?>
                <div class="row">
                    <div class="col-md-8 col-lg-8">
                        <?= $form->field($model, 'Name')->textInput(['placeholder' => 'Business/Company Name', 'readonly' => true]) ?>
                    </div>                                    
                    <div class="col-md-4 col-lg4 col-sm-4">
                        <?= $form->field($model, 'Form_Of_Business')->dropDownList(
                            ['Sole_Proprietorship'=>'Sole Propriatorship','Partnership'=>'Partnership','Company'=>'Company'],
                            ['prompt'=>'--SELECT--']); 
                        ?>
                    </div>
                </div>                                                 
                <div class="row">
                    <div class="col-md-8 col-lg-8">
                        <?= $form->field($model, 'PostalAddress')->textInput(['placeholder' => 'Postal Address']) ?>
                    </div>                    
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'Postal_Code')->dropDownList(ArrayHelper::map($postcodes,'Code','City'), ['prompt'=>'--SELECT--']); ?>            
                    </div>
                </div>
                <?= $form->field($model, 'Address')->textInput(['placeholder' => 'Physical locaion of your premises/head office']) ?>                                      
                    
                <div class="row">                                   
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'Telephone')->textInput(['type'=>'tel', 'placeholder' => '07XXXXXXXX']) ?>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'Email')->textInput(['placeholder' => 'name@example.com']) ?>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'MaxBusinessValue')->textInput(['placeholder' => 'Max business value you can transact']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'NatureOfBusiness')->textArea(['placeholder' => 'Describe what your business is all about']) ?>
                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'SupplierType')->dropDownList(ArrayHelper::map($agpo,'Code','Name'), 
                            ['prompt'=>'--SELECT--']); 
                        ?>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'LicenseNo')->textInput(['placeholder' => 'Business/Company Reg. No.']) ?>               
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model,'KRA_PIN')->textInput(['placeholder' => 'A123456789K', 'readonly' => true]) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'Banker')->dropDownList(ArrayHelper::map($banks,'Bank_Code','Bank_Name'),
                            ['prompt'=>'--SELECT--', 'id' => 'bankcodes']);
                        ?>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'Branch')->widget(DepDrop::classname(), 
                            [   
                                'data' => $branch,
                                'options'=>['id'=>'Branch'],
                                'pluginOptions'=>[
                                    'depends'=>['bankcodes'],
                                    'placeholder'=>'--SELECT--',
                                    'url'=>Url::to(['branches'])
                                ]
                            ]);
                        ?>
                    </div>
                    <div class="col-md-4 col-lg-4 col-sm-4">
                        <?= $form->field($model, 'Bank_Account')->textInput(['placeholder' => 'Bank Account No.']) ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>
                    <?= Html::a('Cancel', ['supplierdata/profile'], ['class' => 'btn btn-danger']) ?>                            
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>       

