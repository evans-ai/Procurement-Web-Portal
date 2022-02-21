<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

$this->title = 'My Profile';
$baseUrl = Yii::$app->request->baseUrl;
$this->params['breadcrumbs'][] = $this->title;

?>
<!-- BEGIN: Content-->

<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height justify-content-md-center">
        <div class="col-lg-8 col-md-8">
            <div class="card">
                <div class="card-header" style="background: #fff">
                    <DIV class="buttons-group float-right">
                        <?= Html::a('Edit Information', ['update-user-profile'], ['class' => 'btn btn-primary']) ?>                                          
                    </DIV>
                    <h4 class="card-title text-uppercase"><?=$this->title;?></h4>
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
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th width="20%">Supplier No.</th>
                                            <td><?=$model->ApplicantId?></td>
                                        </tr>
                                        <tr>
                                            <th>Supplier Name Name</th>
                                            <td><?=$model->CompanyName?></td>
                                        </tr>
                                        <tr>
                                            <th>KRA PIN</th>
                                            <td><?=$model->KRA_PIN?></td>
                                        </tr>
                                        <tr>
                                            <th>Name of Contact Person</th>
                                            <td><?=$model->FirstName.' '.$model->MiddleName?></td>
                                        </tr>
                                        <tr>
                                            <th>Telephone Number</th>
                                            <td><?=$model->Cell?></td>
                                        </tr>
                                        <tr>
                                            <th>Email</th>
                                            <td><?=$model->ApplicantId?></td>
                                        </tr>
                                        <tr>
                                            <th>Applied for Registration</th>
                                            <td>
                                            <?=$model->AppliedForReg ? 'Yes' : 'No <a href="/supplierdata/updateprofile" class="btn btn-danger">Apply Now</a>'?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
<!-- Form wizard with vertical tabs section end -->

<!-- END: Content-->