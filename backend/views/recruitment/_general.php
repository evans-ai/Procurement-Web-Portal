<!-- BEGIN: Content-->
<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Recruitment;
use kartik\select2\Select2;
use dosamigos\datepicker\DatePicker;

//$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - General Information';
$baseUrl = Yii::$app->request->baseUrl;
$action = Yii::$app->params['dataUrl'];
$identity = Yii::$app->user->identity;

$hasprofile = Yii::$app->recruitment->hasprofile();
//print('<pre>');
//print_r($countries);
//print($hasprofile)?'Has a profile':'Needs a profile';

//exit;

  ?>
  
    <div class="app-content content">

        <?= $this->render('_steps'); ?>
       
        <div class="content-header row">
        </div>
        <div class="content-wrapper">
            <div class="content-body">
                <!-- Revenue, Hit Rate & Deals -->
                <div class="row">
                    <div class="col-xl-12 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">General </h4>
                                <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                <div class="heading-elements">
                                    <ul class="list-inline mb-0">
                                        <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-content">
                                <div class="card-body pt-0">

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

                                    <!-- <form id="dataForm" action="<?= $action ?>/general" class="number-tab-steps wizard-circle" method="post"> -->
                                        <?php $form = ActiveForm::begin
                                        (
                                            [
                                            'id' => 'dataForm',
                                            'action' => './general',
                                            'options' => ['class'=>'','enctype' => 'multipart/form-data']
                                            ]
                                        ); 
                                        ?>

                                        <h6>General Info.</h6>

                                        <fieldset>
                                            <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" />
                                     <div class="row"><!--begin row-->

                                        <div class="col-md-10 mx-auto">
                                            <div class="form-group row">
                                                <div class="form-group col-sm-4">
                                                    <!-- <label for="code">First Name <span style="color:red">*</span></label> -->

                                                    <!-- <input type="text" form="dataForm" name="First_Name" value="" placeholder="First Name" class="form-control" required> -->


                                                <?= $form->field($model, 'First_Name')->textInput([
                                                    'placeholder'=> 'First Name',
                                                    'value'=>$identity->FirstName
                                                ]) ?>

                                                </div>
                                                <div class="col-sm-4">

                                                   <?= $form->field($model, 'Middle_Name')->textInput([
                                                    'placeholder' => 'Middle Name',
                                                    'value'=>$identity->MiddleName
                                                ]) ?>

                                                </div>
                                                <div class="col-sm-4">
                                                   <?= $form->field($model, 'Last_Name')->textInput([
                                                    'placeholder' => 'Last Name',
                                                    'value'=>$identity->LastName
                                                ]) ?>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--end row-->

                                    <!---row 2-->

                                     <div class="row">
                                            <div class="col-md-10 mx-auto">
                                                <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        

                                                         <?= $form->field($model, 'ID_Number')->textInput(['placeholder'=> 'ID Number']) ?>

                                                    </div>
                                                    <div class="col-sm-4">                                                     
                                                        <?= $form->field($model, 'Gender')->dropDownList(
                                                            ['Male'=>'Male','Female'=>'Female'],
                                                            ['prompt'=>'Choose Your Gender']); ?>
                                                    </div>
                                                    <div class="col-sm-4">
                                                       

                                                    <?= $form->field($model, 'Citizenship')->dropDownList(ArrayHelper::map($countries,'Code','Name'), ['prompt'=>'Select Country']); ?>


                                                    </div>
                                                </div>

                                            </div>

                                     </div>
                                     <hr />

                                            <!-- <h4 class="card-title">dataForm </h4> -->



                                    <div class="row">
                                        <div class="col-md-10 mx-auto">
                                             <div class="form-group row">
                                                    
                                                    <div class="col-sm-12">
                                                         
                                                    <?= $form->field($model, 'Disability_Details')->hiddenInput(['placeholder'=> 'Disability Details','value'=>'N/A'])->label(false) ?>

                                                    
                                                        
                                                        

                                                    
                                                      
                                                         

                                                    </div>
                                            </div>



                                            <div class="form-group row">
                                                    <div class="col-sm-6">
                                                       
                                                         <?= $form->field($model, 'Marital_Status')->dropDownList(
                                                            [
                                                                'Single'=>'Single',
                                                                'Married'=>'Married',
                                                                'Separated' => 'Separated',
                                                                'Divorced' => 'Divorced',
                                                                'Widow_er' => 'Widower',
                                                                

                                                        ],
                                                        ['prompt'=>'Select Marital Status']); ?>


                                                    </div>
                                                    <div class="col-sm-6">
                                                         

                                                         <?= $form->field($model, 'Date_Of_Birth')->textInput(['type'=>'date']) ?>



                                                    </div>
                                            </div>


                                             <div class="form-group row">
                                                    <div class="col-sm-6">
                                                       


                                                        <?= $form->field($model, 'Ethnic_Origin')->dropDownList(ArrayHelper::map($ethnic,'Code','Description'), ['prompt'=>'Select Ethnicity']); ?>


                                                    </div>
                                                    <div class="col-sm-6">
                                                        
                                                        <?= $form->field($model, 'Disabled')->dropDownList(
                                                            [
                                                                'No'=>'No',
                                                                'Yes'=>'Yes',
                                                        ],
                                                        ['prompt'=>'Select PWD Status'])->label('Person With Disability'); ?>

                                                    </div>
                                            </div>

                                            <div class="form-group row">
                                                    
                                                   
                                            </div>



                                        </div>
                                    </div>
                                     <div class="form-group">
                                       
                                     </div>
                                        

                                        <h5>Communication Info.</h5>
                                        <hr />
                                       

                                        <div class="form-group row">
                                                    <div class="col-sm-4">
                                                        

                                                     <?= $form->field($model, 'Home_Phone_Number')->textInput(['type'=>'text',
                                                     'placeholder'=>'Home Phone Number',
                                                     'value'=> $identity->Phone
                                                 ]) ?>  

                                                    </div>

                                                    <div class="col-sm-4">
                                                        
                                                         <?= $form->field($model, 'Cellular_Phone_Number')->textInput(['type'=>'text','placeholder'=>' Cell Phone Number',
                                                     'value'=> $identity->Cell])->label('Mobile') ?> 

                                                      
                                                    </div>

                                                    <div class="col-sm-4">
                                                         
                                                         <?= $form->field($model, 'E_Mail')->textInput(['type'=>'text','placeholder'=>'E Mail',
                                                     'value'=> strtolower($identity->Email)]) ?> 
                                                       

                                                    </div>
                                            </div>



                                            <div class="form-group row">
                                                    <div class="col-sm-4">
                                                       

                                                        <?= $form->field($model, 'Postal_Address')->textInput(['type'=>'text','placeholder'=>' Postal Address']) ?> 

                                                    </div>
                                                    <div class="col-sm-4">
                                                        
                                                         <?= $form->field($model, 'Post_Code')->textInput(['type'=>'text','placeholder'=>' Post_Code']) ?> 


                                                    </div>

                                                    <div class="col-sm-4">

                                                        <?= $form->field($model, 'Residential_Address')->textInput(['type'=>'text','placeholder'=>'Residential_Address']) ?>

                                                    </div>
                                            </div>


                                             <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        
                                                        

                                                    </div>
                                                    <div class="col-sm-6">
                                                         
                                                        

                                                    </div>
                                            </div>

                                        </fieldset>
                                        <div class="form-group">
                                            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>

                                            <?= Html::a('Next',['./recruitment/qualifications'],['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                                        </div>
                                        
                                    <!-- </form> --><!--general form-->
                                    <?php ActiveForm::end(); ?>
                                    <!--end row 2-->


                                    
                               </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <!--/ Revenue, Hit Rate & Deals -->



            </div>
        </div>
    </div>



