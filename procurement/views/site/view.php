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
                    <h4 class="card-title text-uppercase">DETAILS PAGE</h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">

                        <form class="form form-horizontal">
                            <div class="form-body">
                                <h4 class="form-section"><i class="ft-layers"></i> Request Lines</h4>
                                <div class="form-group-row">
                                    <?= GridView::widget([
                                    'dataProvider' => $Provider,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'Procurement_Plan',
                                        'Description',
                                        'Quantity',
                                        ['class' => 'yii\grid\ActionColumn',
                                        'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
                                        'contentOptions' => ['style' => 'text-align:center'],
                                        'template' => '{view}',  
                                    ],
                                    ],
                                ]); 

                                ?>                                                    
                                </div>    
                            </div>
                        </form>                       
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
                <!-- Form wizard with vertical tabs section end -->

    <!-- END: Content-->