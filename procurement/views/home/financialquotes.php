<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = "Supplier Portal";
$baseUrl = Yii::$app->request->baseUrl;

//print_r($list); exit;
 // print('<pre>');
 //                            print_r($SearchModel); exit;
?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-header">
                        <DIV class="buttons-group float-right">
                            <?= Html::a('Home',['./home/dashboard'],['class' => 'btn btn-danger-rba mr-1', 'name' => 'applications']) ?>                                            
                        </DIV>
                        <h4 class="card-title text-uppercase">OPENED RFQs</h4>
                    </div>                    
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i>Success</h4>
                            <?= Yii::$app->session->getFlash('success') ?>
                            </div>
                        <?php endif; ?>
                        <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                            <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i>Error! </h4>
                            <?= Yii::$app->session->getFlash('error') ?>
                            </div>
                        <?php endif; ?>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-8">
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" name="" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>

                        
                    <?= GridView::widget([
                            'dataProvider' => $Provider,
                           'filterModel' => $SearchModel,
                            'columns' => [
                            ['class' => 'yii\grid\SerialColumn',
                            'contentOptions' => ['width'=>'5%'],
                            ],
                            ['attribute'=>'Tender_No',
                            'contentOptions' => ['width'=>'10%'],
                            ],
                            ['attribute'=>'Supplier_Name',                            
                            ],
                            ['attribute'=>'Description',                            
                            ],
                            ['attribute'=>'Quantity',                            
                            ],
                            ['attribute'=>'Unit_Price',                            
                            ],
                            ['attribute'=>'Amount',
                            'label'=>'Total',                            
                            ],
                            ['class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'template' => '',
                            'buttons' => 
                            [
                                'view' => function ($url, $model) 
                                {
                                    $baseUrl = Yii::$app->request->baseUrl;
                                    return Html::a('<span class="btn btn-primary btn-sm"> View</span>', $baseUrl.'/home/viewitem?id='.$model['No'].'&myService='. $list .'', [
                                                'title' => Yii::t('app', 'View'),                                 
                                                ]);
                                },
                                'fevourite' => function ($url, $model) 
                                {
                                    $baseUrl = Yii::$app->request->baseUrl;
                                    return Html::a('<span class="btn btn-secondary btn-sm"><i class="ft-danger"></i> Favourite</span>', $baseUrl.'/home/favourite?id='.$model['No'].'&myService='. $list .'', [
                                      'title' => Yii::t('app', 'View'),
                                      'class'=>'gridbtn btn-primary btn-xs',                                  
                                      ]);                                                
                                },
                            ],
                            ],
                            ],
                            ]);
                            ?>
                       
                                                   
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
                <!-- Form wizard with vertical tabs section end -->

    <!-- END: Content-->