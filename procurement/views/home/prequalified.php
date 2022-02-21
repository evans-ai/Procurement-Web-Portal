<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = "RBA Procurement";
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
                    <h4 class="card-title text-uppercase">MY PREQUALIFIED CATEGORIES</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $Provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'Ref_No',
                            'Category_Name',
                            'Fiscal_Year',
                           ['class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model) use ($id)
                                {
                                    // $baseUrl = Yii::$app->request->baseUrl;
                                    // return Html::a('<span class="fa fa-eye"></span> Apply', $baseUrl.'/responses/apply?item=preq&id='.$model['id'].'', [
                                    //             'title' => Yii::t('app', 'View'),
                                    //             'class'=>' btn-primary btn-xs',                                  
                                    //             ]);
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