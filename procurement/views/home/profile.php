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
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">UPDATE PROFILE</h4>
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
                            <h6>Business Information</h6>
                            <fieldset>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                          
                                        <?= $form->field($model, 'Key')->hiddenInput()->label(false); ?>
                                        <?= $form->field($model, 'No')->hiddenInput()->label(false); ?>
                                        <?= $form->field($model, 'Name')->textInput() ?>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'KRA_PIN')->textInput() ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Telephone')->textInput() ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Email')->textInput() ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Address')->textInput() ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                         <?= $form->field($model, 'PlotNo')->textInput() ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                         <?= $form->field($model, 'Street_Road')->textInput() ?>           
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'PostalAddress')->textInput() ?>      
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <?= $form->field($model, 'OtherBranches')->textarea() ?>  
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <?= $form->field($model, 'NatureOfBusiness')->textarea() ?>              
                                        </div>
                                    </div>
                                </div>                                                
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'LicenseNo')->textInput() ?>               
                                        </div>                                       
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <div class="form-group">
                                            <?= $form->field($model, 'LicenseExpiry')->textInput(['type'=>'date']) ?>
                                        </div> 
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'MaxBusinessValue')->textInput(['type'=>'number']) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4"> 
                                            <?= $form->field($model, 'NetWorth')->textInput(['type'=>'number']) ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">                
                                        <?= $form->field($model, 'UnderCurrentManagementSince')->textInput(['type'=>'date']) ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Founded_Incorporated')
                                            ->dropDownList(['Founded'=>'Founded','Incorporated'=>'Incorporated',],['prompt'=>'Select...']) ?>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'BondingCompany')->textInput() ?>     
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Banker')->textInput() ?>      
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'Branch')->textInput() ?>
                                    </div>
                                </div>
                                

                                <!---- Optional Parts-->
                                <?= $form->field($model, 'SoleProprietor')->hiddenInput()->label(false) ?>

                                 <div class="row" id="proprietor-pane" <?=$model->SoleProprietor?'style="display: block"':'style="display: hidden"'?>>
                                    <div class="col-lg-12">
                                        <?= $form->field($model, 'Proprietor')->textInput() ?>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3">
                                        <?= $form->field($model, 'ProprietorDOB')->textInput(['type'=>'date']) ?>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3">
                                        <?= $form->field($model, 'ProprietorNationality')->textInput() ?>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3">
                                        <?= $form->field($model, 'ProprietorOrigin')->textInput() ?>
                                    </div>
                                    <div class="col-md-3 col-lg-3 col-sm-3">
                                        <?= $form->field($model, 'ProprietorCitizedID')->textInput() ?>
                                    </div>
                                 </div>

                                <?= $form->field($model, 'Partnership')->hiddenInput()->label(false) ?>
                                <div  id="partners-list" style="border: 1px solid green; border-radius: 5px; padding: 6px; overflow: hidden; <?= 1==1 && $model->Partnership ? 'display: block; padding: 0px;' : 'display: none; padding: 0px;' ?>">
                                    <?php
                                        $ptnCounter = 0;
                                        if($partners){
                                            foreach($partners as $prtn => $partner){
                                    ?>
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <?= $form->field($partner, "[$prtn]Name")->textInput() ?>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                 <?= $form->field($partner, "[$prtn]Nationality")->textInput() ?>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                <?= $form->field($partner, "[$prtn]Citizenship")->textInput() ?>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3">
                                                 <?= $form->field($partner, "[$prtn]Pasport_ID")->textInput() ?>
                                            </div>
                                            <?=$form->field($partner, "[$prtn]BusinessID")->hiddenInput(['value'=> $partner->BusinessID])->label(false);?>
                                            <?=$form->field($partner, "[$prtn]No_")->hiddenInput(['value'=> $partner->No_])->label(false);?>
                                        </div>
                                    </div>
                                    <?php
                                                $ptnCounter++;
                                            }
                                        }
                                    ?>
                                    <div class="col-lg-12">
                                        
                                        <div class="row">
                                            <div class="col-lg-12 text-right">
                                                <a class="btn btn-default btn-sm add-partners" href="#"><span class="glyphicon glyphicon-plus"></span> Add More Partners</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12" id="other-partners"></div>
                                        </div>
                                    </div>
                                </div>

                                <?= $form->field($model, 'Company')->hiddenInput()->label(false) ?>
                                
                               

                                <div class="row">                                                    
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'SupplierType')
                                            ->dropDownList(['Youth'=>'Youth','Women'=>'Women','Disabled'=>'Person With Disability','general'=>'General',],['prompt'=>'Select...']) ?>
                                    </div>
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                            
                                    </div>                                                    
                                    <div class="col-md-4 col-lg-4 col-sm-4">
                                        <?= $form->field($model, 'OrganizationChart')->fileInput() ?>
                                    </div>
                                </div>

                            </fieldset>  

                            <div class="form-group">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>

                            <!-- <?= Html::a('Next',['./supplierdata/create'],['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                            </div> -->

                        <!-- </form> -->
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
 

<script>
        $(function(){
            $('#form-of-business').on('change', function(){
                var value = $(this).val();
                if(value == 'sole'){
                    $('#supplierdata-soleproprietor').val(1);
                    $('#proprietor-pane').css('display', 'block');

                    $('#supplierdata-company').val(0);
                    $('#directors-list').css('display', 'none');

                    $('#supplierdata-partnership').val(0);
                    $('#partners-list').css('display', 'none');
                }
                else if (value == 'partner') {
                    $('#supplierdata-soleproprietor').val(0);
                    $('#proprietor-pane').css('display', 'none');

                    $('#supplierdata-company').val(0);
                    $('#directors-list').css('display', 'none');

                    $('#supplierdata-partnership').val(1);
                    $('#partners-list').css('display', 'block');
                }
                else if (value == 'company') {
                    $('#supplierdata-soleproprietor').val(0);
                    $('#proprietor-pane').css('display', 'none');

                    $('#supplierdata-company').val(1);
                    $('#directors-list').css('display', 'block');

                    $('#supplierdata-partnership').val(0);
                    $('#partners-list').css('display', 'none');
                }
                else{
                    $('#supplierdata-soleproprietor').val(0);
                    $('#proprietor-pane').css('display', 'none');

                    $('#supplierdata-company').val(0);
                    $('#directors-list').css('display', 'none');

                    $('#supplierdata-partnership').val(0);
                    $('#partners-list').css('display', 'none');
                }
            })
            
            var counter = <?=$drCounter+1?>;
            $('.add-directors').off('click').on('click', function(evt){
                evt.preventDefault();
                var myDirectorsHTML = '<div class="row">'+
                    '<div class="col-lg-3 col-md-3 col-sm-3">'+
                        '<div class="form-group field-businessdirectors-directorname">'+
                            '<label class="control-label" for="businessdirectors-directorname">Director Name</label>'+
                            '<input id="businessdirectors-directorname" class="form-control" name="BusinessDirectors['+counter+'][DirectorName]" value=" " type="text">'+
                            '<div class="help-block"></div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-lg-3 col-md-3 col-sm-3">'+
                        '<div class="form-group field-businessdirectors-nationality">'+
                            '<label class="control-label" for="businessdirectors-nationality">Nationality</label>'+
                            '<input id="businessdirectors-nationality" class="form-control" name="BusinessDirectors['+counter+'][Nationality]" value=" " type="text">'+
                            '<div class="help-block"></div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-lg-3 col-md-3 col-sm-3">'+
                        '<div class="form-group field-businessdirectors-citizenship">'+
                            '<label class="control-label" for="businessdirectors-citizenship">Citizenship</label>'+
                            '<input id="businessdirectors-citizenship" class="form-control" name="BusinessDirectors['+counter+'][Citizenship]" value=" " type="text">'+
                            '<div class="help-block"></div>'+
                        '</div>'+
                    '</div>'+
                    '<div class="col-lg-3 col-md-3 col-sm-3">'+
                        '<div class="form-group field-businessdirectors-shares">'+
                            '<label class="control-label" for="businessdirectors-shares">Shares</label>'+
                            '<input id="businessdirectors-shares" class="form-control" name="BusinessDirectors['+counter+'][Shares]" value="0" type="text">'+
                            '<div class="help-block"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>';
               $('#other-directors').append(myDirectorsHTML);
               counter += 1;
            });
            var Pcounter = <?=$ptnCounter+1;?>;
            $('.add-partners').off('click').on('click', function(evt){
               evt.preventDefault();
               var myPartnersHTML = '<div class="row">'+
               '<div class="col-lg-3 col-md-3 col-sm-3">'+
                     '<div class="form-group field-businesspartners-name">'+
                        '<label class="control-label" for="businesspartners-name">Name</label>'+
                         '<input id="businesspartners-'+Pcounter+'-name" class="form-control" name="BusinessPartners['+Pcounter+'][Name]" value=" " type="text">'+
                         '<div class="help-block"></div>'+
                     '</div>'+
                 '</div>'+
                 '<div class="col-lg-3 col-md-3 col-sm-3">'+
                     '<div class="form-group field-businesspartners-nationality">'+
                         '<label class="control-label" for="businesspartners-nationality">Nationality</label>'+
                         '<input id="businesspartners-'+Pcounter+'-nationality" class="form-control" name="BusinessPartners['+Pcounter+'][Nationality]" value=" " type="text">'+
                         '<div class="help-block"></div>'+
                     '</div>'+
                 '</div>'+
                 '<div class="col-lg-3 col-md-3 col-sm-3">'+
                     '<div class="form-group field-businesspartners-citizenship">'+
                         '<label class="control-label" for="businesspartners-citizenship">Citizenship</label>'+
                         '<input id="businesspartners-'+Pcounter+'-citizenship" class="form-control" name="BusinessPartners['+Pcounter+'][Citizenship]" value=" " type="text">'+
                         '<div class="help-block"></div>'+
                     '</div>'+
                 '</div>'+
                 '<div class="col-lg-3 col-md-3 col-sm-3">'+
                     '<div class="form-group field-businesspartners-pasport_id">'+
                         '<label class="control-label" for="businesspartners-pasport_id">Pasport  Id</label>'+
                         '<input id="businesspartners-'+Pcounter+'-pasport_id" class="form-control" name="BusinessPartners['+Pcounter+'][Pasport_ID]" value=" " type="text">'+
                         '<div class="help-block"></div>'+
                     '</div>'+
                 '</div>'+
             '</div>';
               $('#other-partners').append(myPartnersHTML);
               Pcounter += 1;
            });
        });
    </script>