<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = "Registration Of Suppliers";
$baseUrl = Yii::$app->request->baseUrl;

//print_r($id); exit;

?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase">REGISTRATION OF SUPPLIERS-Open Categories</h4>

                </div>

                <div class="card-content collapse show">
                    <div class="card-header">
                        <DIV class="buttons-group float-right">
                            <?= Html::a('Home',['./supplierdata/profile'],['class' => 'btn btn-danger-rba mr-1', 'name' => 'applications']) ?>                                            
                        </DIV>
                        <h4 class="card-title text-uppercase">DASHBOARD</h4>
                    </div>
                    <div class="card-body">
                            <?= Yii::$app->session->getFlash('$msg') ?>
                    <?= GridView::widget([
                        'dataProvider' => $Provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['width'=>'5%'],
                            ],
                            ['attribute'=>'Document_No',
                              'headerOptions' => ['width'=>'10%'],
                            ],
                            ['attribute'=>'Category_Name',
                              'headerOptions' => ['width'=>'10%'],
                            ],
                            ['attribute'=>'StartDate',
                             'headerOptions' => ['width'=>'12%'],
                             'format'=>['date', 'php:d/m/Y'], 
                            ],
                            [
                                'attribute'=>'EndDate',         
                                'headerOptions' => ['width'=>'12%'],
                                'format'=>['date', 'php:d/m/Y'],
                            ],
                            ['attribute'=>'TOR_File_Name',
                             'label'=>'TOR FILE',
                             'format' => 'raw',
                             'value'=>function ($model) {
                                return Html::a(Html::encode("Download"),'view-tor?id='.$model['id'].'&myService=preq');
                                },
                            ],
                           ['class' => 'yii\grid\ActionColumn',
                           'header' => 'Actions',
                            'headerOptions' => [ 'style'=>'color:black; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model)
                                {
                                    $baseUrl = Yii::$app->request->baseUrl;
                                    return Html::a('<span class="btn btn-primary btn-sm"></i> Apply</span>', $baseUrl.'/responses/apply?item=preq&id='.$model['id'].'', [
                                                                               
                                                ]);
                                },
                                'fevourite' => function ($url, $model)
                                {
                                    $baseUrl = Yii::$app->request->baseUrl;
                                    return Html::a('<span class="btn btn-secondary btn-sm" style="margin-left:5px"><i class="ft-danger"></i> Fevourite</span>', $baseUrl.'/home/favourite?id='.$model['id'].'&myService=preq', [
                                                                    
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