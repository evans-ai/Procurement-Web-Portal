<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\models\Supplierdata */

$this->title = 'Profile';

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
                <div class="card-header">
					<div class="float-right">
						<?= Html::a('Edit Information', ['updateprofile', 'id' => $model->No], ['class' => 'btn btn-primary']) ?>
						
					</div>
                    <h4 class="card-title text-uppercase"><?=$this->title;?></h4>
					<span class="clearfix"></span>
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
                                
                                <!-- <?= Html::a('Home', ['/supplierdata/pro'], ['class' => 'btn btn-danger-rba float-right']) ?> -->                                      
                            </p>
                            <?php $SupplirerTypes = array('Not Indicated', 'Youth', 'Women', 'Persons With Disabilities', 'Normal'); ?>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    'No',
                                    'Name',
                                    'Address',
                                    // 'PlotNo',
                                    // 'Street_Road',
                                    'NatureOfBusiness',
                                    'LicenseNo',                                    
                                    'Banker',
                                    'Branch',
                                    'SupplierType',
                                    'Form_Of_Business',
                                    ['attribute'=>'MaxBusinessValue',
                                     'format'=>['decimal',2],]
                                ],
                            ]) ?>
                                               
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
<!-- Form wizard with vertical tabs section end -->

<!-- END: Content-->