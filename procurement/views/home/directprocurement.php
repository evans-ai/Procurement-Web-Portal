<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Expression Of Interest';
$baseUrl = Yii::$app->request->baseUrl;



?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <?php if($item=='rfq'){?>
                        <h4 class="card-title text-uppercase">RFQ</h4>
                    <?php }else {?>
                    <h4 class="card-title text-uppercase">DIRECT PROCUREMENT</h4>
                <?php } ?>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                    <?= GridView::widget([
                        'dataProvider' => $Provider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            'No',
                            'Title',
                            ['class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                            'contentOptions' => ['style' => 'text-align:center'],
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model)
                                {
                                    $baseUrl = Yii::$app->request->baseUrl;
                                    return Html::a('<span class="fa fa-eye"></span> View', $baseUrl.'/home/viewtender?id='.$model['No'].'&myService=rfq', [
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