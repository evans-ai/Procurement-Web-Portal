<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Supplierdata */

$this->title = $model->Name;

$baseUrl = Yii::$app->request->baseUrl;

$this->params['breadcrumbs'][] = $this->title;

?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase">DETAILS PAGE</h4>
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

                        <!-- <h1><?= Html::encode($this->title) ?>
                            <?= Html::a('Next Step <i class="fa fa-angle-right"></i>', ['supervision', 'id' => $model->No], ['class' => 'btn btn-success pull-right']) ?>
                            </h1> -->

                            <p>
                                <?= Html::a('Edit Information', ['updateprofile', 'id' => $model->No], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('Home', ['/home'], ['class' => 'btn btn-danger']) ?>                                               
                            </p>
                            <?php $SupplirerTypes = array('Not Indicated', 'Youth', 'Women', 'Persons With Disabilities', 'Normal'); ?>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'No',
                                    'Name',
                                    'Address',
                                    'PlotNo',
                                    'Street_Road',
                                    'PostalAddress',
                                    'NatureOfBusiness',
                                    'LicenseNo',
                                    'LicenseExpiry:date',
                                    [
                                        'attribute' => 'MaxBusinessValue',
                                        'format' => 'decimal'
                                    ],
                                    'Banker',
                                    'Branch',
                                    [
                                        'attribute' => 'SoleProprietor',
                                        'value' => $model->SoleProprietor == 1 ? 'Yes' : 'No'
                                    ],
                                    'Proprietor',
                                    [
                                        'attribute' => 'ProprietorDOB',
                                        'value' => $model->ProprietorDOB == '1753-01-01 00:00:00.000' ? 'N/A' : date('d/m/Y', strtotime($model->ProprietorDOB))
                                    ],
                                    'ProprietorNationality',
                                    'ProprietorOrigin',
                                    'ProprietorCitizedID',
                                    [
                                        'attribute' => 'Partnership',
                                        'value' => $model->Partnership == 1 ? 'Yes' : 'No'
                                    ],
                                    [
                                        'attribute' => 'Company',
                                        'value' => $model->Company == 1 ? 'Yes' : 'No'
                                    ],
                                    [
                                        'attribute' => 'PublicCompany',
                                        'value' => $model->PublicCompany == 1 ? 'Yes' : 'No'
                                    ],
                                    [
                                        'attribute' => 'NorminalCapital',
                                        'format' => 'decimal'
                                    ],
                                    [
                                        'attribute' => 'IssuedCapital',
                                        'format' => 'decimal'
                                    ],
                                    // [
                                    //     'attribute' => 'SupplierType',
                                    //     'value' => $SupplirerTypes[$model->SupplierType]
                                    // ],
                                ],
                            ]) ?>
                                               
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
<!-- Form wizard with vertical tabs section end -->
