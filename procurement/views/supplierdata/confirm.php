<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Directors List';
$baseUrl = Yii::$app->params['baseUrl'];

// print '<pre>';
// print_r($businesstype); exit;

?>
   
<section id="basic-form-layouts" class="container ">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: #fff">              
                    <h1 class="card-title">Business Profile</h1>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?=$this->render('steps', ['active' => 'submit']) ?>
                        <br><br>
                        <br>
                        <hr/>
                        <p>You are applying to be registered as a supplier under the following categories:</p>
                        <ol>
                            <?php foreach($OpenCategories as $OpenCat){ ?>
                            <li><?=$OpenCat->Category_Name ?></li>
                            <?php } ?>
                        </ol>
                        <p>Please see below the information that you have supplied. Verify that it is correct then check the confirmation box and submit your application for review</p>
                        <div id="accordionWrap2" role="tablist" aria-multiselectable="true">
                            <div class="card collapse-icon accordion-icon-rotate left">
                                <div id="heading21" class="card-header">
                                    <a style="text-transform: none;" data-toggle="collapse" href="#accordion21" aria-expanded="false" aria-controls="accordion21" class="card-title lead collapsed">Business Particulars</a>
                                </div>
                                <div id="accordion21" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="heading21" class="collapse" style="">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th style="text-align: left;" width="20%">Supplier Name</th>
                                                    <td><?=@$Supplier->Name ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left;">Address</th>
                                                    <td><?=@$Supplier->Address ?><br><?=@$Supplier->PostalAddress?></td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left;">Contacts</th>
                                                    <td><b>Tel:</b> <?=@$Supplier->Telephone ?>, <b>Email: </b><?=@$Supplier->Email?></td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left;">Registration No.</th>
                                                    <td><?=@$Supplier->LicenseNo ?></td>
                                                </tr>  
                                                <tr>
                                                    <th style="text-align: left;">PIN No.</th>
                                                    <td><?=@$Supplier->KRA_PIN ?></td>
                                                </tr>  
                                                <tr>
                                                    <th style="text-align: left;">Supplier Type</th>
                                                    <td><?=@$Supplier->SupplierType ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left;">Nature of Business</th>
                                                    <td><?=@$Supplier->NatureOfBusiness ?></td>
                                                </tr>
                                                <tr>
                                                    <th style="text-align: left;">Form of Business Unit</th>
                                                    <td><?=str_replace('_', ' ', @$Supplier->Form_Of_Business) ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="heading22" class="card-header">
                                    <a style="text-transform: none;" data-toggle="collapse" href="#accordion22" aria-expanded="false" aria-controls="accordion22" class="card-title lead collapsed">Business Management Information</a>
                                </div>
                                <div id="accordion22" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="heading22" class="collapse" aria-expanded="false">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <table class="table table-bordered table-striped">
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Nationality</th>
                                                    <th>ID/Passport Number</th>
                                                    <th>KRA PIN</th>
                                                    <th>Role</th>
                                                    <th>No. of Shares</th>
                                                </tr>
                                                <?php $relArray = ['Not Indicated', 'Proprietor', 'Partner', 'Director', 'Shareholder']; foreach($Personnel as $Person){ ?>
                                                    <tr>
                                                        <td><?=@$Person->DirectorName?></td>
                                                        <td><?=@$Person->Nationality?></td>
                                                        <td><?=@$Person->ID_Passport?></td>
                                                        <td><?=@$Person->KRA_PIN?></td>
                                                        <td><?=@$relArray[@$Person->Relationship]?></td>
                                                        <td><?=@$Person->Shares?>%</td>
                                                    </tr>
                                                <?php } ?>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div id="heading23" class="card-header">
                                    <a style="text-transform: none;" data-toggle="collapse" href="#accordion23" aria-expanded="false" aria-controls="accordion23" class="card-title lead collapsed">Documents Attached</a>
                                </div>
                                <div id="accordion23" role="tabpanel" data-parent="#accordionWrap2" aria-labelledby="heading23" class="collapse" aria-expanded="false">
                                    <div class="card-content">
                                        <div class="card-body">
                                            <ol>
                                                <?php foreach($DocumentsList as $Attached => $DocumentID){ ?>
                                                   
                                                    
                                                    <li><?= Html::a($Attached, ['supplierdata/view-document', 'ref' => $DocumentID], ['target' => '_blank']) ?></li>
                                                <?php } ?>
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php ActiveForm::begin(); ?>
                            <div class="form-group">
                            <?= Html::checkbox('iconcur', false, ['label' => 'I confirm that the information provided herein and the documents attached herewith are correct and accurate to the best of my knowledge.', 'required' => true]); ?>
                            </div>                        
                            <?=Html::a('Previous', 'documents', ['class' => 'btn btn-danger']) ?>
                            <?= Html::submitButton('Submit Application', ['class' => 'btn btn-primary']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>                              
<!-- Form wizard with vertical tabs section end -->
