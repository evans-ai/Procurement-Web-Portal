
                                        <!-- <h6>Qualifications.</h6>
                                        <fieldset>
                                            <div class="form-group row">                                                   
                                                        
                                                    <!-- <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" /> -->

                                                    <?= $form->field($modelqualification, 'Applicant_No')->textInput([
                                                        'type'=>'text',
                                                        'class'=> 'ApplicantId form-control'
                                                    ])->label(false) ?>

                                                    <div class="col-sm-2">
                                                        

                                                        <?= $form->field($modelqualification, 'Education_Level_Id')->dropDownList(ArrayHelper::map($levels,'EducationLevelID','EducationLevelName'), ['prompt'=>'Select Level']); ?>

                                                    </div>

                                                    <div class="col-sm-3">
                                                        

                                                       <?= $form->field($modelqualification, 'Course_Id')->dropDownList(ArrayHelper::map($certs,'CertificateID','CertificateName'), ['prompt'=>'Select Level']); ?>


                                                    </div>

                                                    <div class="col-sm-3">
                                                        


                                                       <?= $form->field($modelqualification, 'Grade_Id')->dropDownList(ArrayHelper::map($grades,'GradeID','GradeName'), ['prompt'=>'Select Grade']); ?>

                                                    </div>

                                                    <div class="col-sm-2">
                                                       

                                                         <?= $form->field($modelqualification, 'From_Date')->textInput(['type'=>'date']) ?>

                                                    </div>

                                                    <div class="col-sm-2">
                                                        

                                                        <?= $form->field($modelqualification, 'To_Date')->textInput(['type'=>'date']) ?>

                                                    </div>


                                                    
                                            </div>



                                            <div class="form-group row">
                                                    

                                                    <?= $form->field($modelqualification, 'Institution_Company')->textInput(['type'=>'text']) ?>
                                                    
                                            </div>


                                        </fieldset>

                                        <h6>Experience.</h6>
                                        <fieldset>
                                            <!-- <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" /> -->
                                            

                                            <?= $form->field($modelexperience,'Applicant_No')->textInput([
                                                'type'=>'text',
                                                'value'=> ''
                                            ]) ?>

                                             <div class="form-group row">
                                                    <div class="col-sm-2">
                                                        <?= $form->field($modelexperience, 'From_Date')->textInput(['type'=>'date']) ?>
                                                    </div>

                                                    

                                                    <div class="col-sm-2">
                                                        
                                                         <?= $form->field($modelexperience, 'To_Date')->textInput(['type'=>'date']) ?>

                                                    </div>

                                                    <div class="col-sm-3">
                                                         
                                                         <?= $form->field($modelexperience, 'Responsibility')->textInput(['type'=>'text']) ?>

                                                    </div>

                                                    <div class="col-sm-2">
                                                         
                                                         <?= $form->field($modelexperience, 'Institution_Company')->textInput(['type'=>'text']) ?>

                                                    </div>

                                                    <div class="col-sm-3">

                                                         <?= $form->field($modelexperience, 'Salary')->textInput(['type'=>'number']) ?>

                                                    </div>
                                            </div>
                                        </fieldset>

                                        <h6>Referees</h6>
                                        <fieldset>
                                            <!-- <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" /> -->
                                            <!-- <input type="hidden" class="form-control" name="No" readonly value="<?= $session->get('Applicantid') ?>" /> -->

                                            <?= $form->field($modelreferee, 'No')->textInput([
                                                'type'=>'text',
                                               'value'=>''
                                            ])->label(false) ?>

                                             <div class="form-group row">
                                                    <div class="col-sm-3">
                                                      
                                                         <?= $form->field($modelreferee, 'Names')->textInput(['type'=>'text']) ?>
                                                    </div>

                                                    <div class="col-sm-3">
                                                         

                                                         <?= $form->field($modelreferee, 'Designation')->textInput(['type'=>'text']) ?>
                                                    </div>

                                                    <div class="col-sm-3">
                                                        

                                                         <?= $form->field($modelreferee, 'Company')->textInput(['type'=>'text']) ?>

                                                    </div>

                                                    <div class="col-sm-3">
                                                         
                                                          <?= $form->field($modelreferee, 'Address')->textInput(['type'=>'text']) ?>

                                                    </div>

                                                    
                                            </div>
                                             <div class="form-group row">

                                                    <div class="col-sm-4">
                                                         

                                                         <?= $form->field($modelreferee, 'Telephone_No')->textInput(['type'=>'text']) ?>

                                                    </div>

                                                     <div class="col-sm-4">
                                                         

                                                         <?= $form->field($modelreferee, 'E_Mail')->textInput(['type'=>'email']) ?>

                                                    </div>

                                                     <div class="col-sm-4">
                                                         

                                                          <?= $form->field($modelreferee, 'Notes')->textarea() ?>

                                                    </div>

                                             </div>
                                        </fieldset>

                                         <h6>Attachments</h6>
                                        <fieldset>
                                            <!-- <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" /> -->
                                           <!--  <input type="hidden" class="form-control" name="No" readonly value="<?= $session->get('Applicantid') ?>" /> -->

                                             <?= $form->field($modelattachment, 'Applicant_No')->textInput([
                                                'type'=>'text',
                                                'value'=> ''
                                            ])->label(false) ?>
                                             

                                            <?= $form->field($modelattachment, 'Attached')->textInput(['type'=>'hidden','value'=>1])->label(false) ?>

                                             <div class="form-group row">
                                                    <div class="col-sm-6">
                                                        
                                                        <?= $form->field($modelattachment, 'Document_Description')->textInput(['type'=>'text']) ?>

                                                    </div>

                                                    <div class="col-sm-6">
                                                         


                                                        <?= $form->field($modelattachment, 'attachment')->fileInput() ?>


                                                    </div>

                                                    

                                                    

                                                    
                                            </div>
                                        </fieldset>

                                        <h6>Comments</h6>
                                        <fieldset>
                                            <!-- <input type="hidden" name="_csrf-backend" value="<?=Yii::$app->request->getCsrfToken()?>" /> -->
                                           

                                            <?= $form->field($modelcomment, 'Applicant_No')->textInput([
                                                'type'=>'text',
                                                'value'=> ''
                                            ])->label(false) ?>

                                            <input type="hidden" name="Date" value="<?= date('Y-m-d') ?>">

                                            <?= $form->field($modelcomment, 'Date')->textInput([
                                                'type'=>'hidden',
                                                'value'=> date('Y-m-d')
                                            ])->label(false) ?>

                                             <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        
                                                     <?= $form->field($modelcomment, 'Views_Comments')->textarea() ?>


                                                    </div>
                                                    
                                            </div>

                                        </fieldset>