<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = $DataArray['Title'];
$baseUrl = Yii::$app->request->baseUrl;
$this->params['breadcrumbs'][] = ['label'=>'Open RFX','url'=>['/home/list']];
$this->params['breadcrumbs'][] = $this->title;

?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase"><?=$this->title;?>
                    <?php 
                        if($ClosingDate >= date('Y-m-d H:i:s')){
                            echo Html::a('Apply', ['responses/response', 'id' => $_GET['ref']], ['class'=>'btn btn-primary pull-right']);
                        } 
                    ?>
                   <!-- <?//= Html::a('Download Tender Document', ['/home/tor-download', 'ref' => $_GET['ref']], ['class'=>'btn btn-dark pull-right', 'style' => 'margin-right: 10px;']) ?>-->
                    
                    </h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <form class="form form-horizontal">
                            <div class="form-body">
                                
                                <div class="col-12"> 
									<h4 class="form-section">General</h4>
                                    <?= GridView::widget([
                                        'dataProvider' => $headerProvider,
                                        'showOnEmpty' => false, 
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn',
                                            'headerOptions' => ['width'=>'5%'],
                                            ],
                                            [
                                                'attribute'=>'No',
                                                'label' => 'Ref No.',
                                                'headerOptions' => ['width'=>'10%'],
                                            ],
                                            ['attribute'=>'Title',
                                            'headerOptions' => ['width'=>'10%'],
                                            ],
                                        
                                            [
                                                'attribute'=>'Closing_Date',         
                                                'headerOptions' => ['width'=>'12%'],
                                                'format'=>['date', 'php:d/m/Y h:i a'],
                                            ],
                                                    ],
                                        ]); 
                                    ?>                                                    
                                </div>    
                            </div>
                        </form>                       
                    </div>
                    <div class="card-body">
                        <form class="form form-horizontal">
                            <div class="form-body">
                                <div class="card-body">
								<h4 class="form-section">Requirements</h4>
                                    <?php if($Mandatory){ ?>
                                        <h4>Mandatory</h4>
                                        <ol>
                                            <?php foreach($Mandatory as $Manda){ ?>
                                                <li style="line-height: 2.5"><?=$Manda['Question'] ?></li>
                                            <?php } ?>
                                        </ol>
                                    <?php } ?>                                                                                            
                                    <?php if($Technical){ ?>
                                        <h4>Technical</h4>
                                        <ol style="line-height: 2.5">
                                            <?php foreach($Technical as $Tech){ ?>
                                                <li style="line-height: 2.5"><?=$Tech['Question'] ?></li>
                                            <?php } ?>
                                        </ol>
                                    <?php } ?>                                                                                            
                                </div>    
                            </div>
                        </form>                       
                    </div>
                    <?php if($financedata){ ?>
                    <div class="card-body">
                        <form class="form form-horizontal">
                            <div class="form-body">
                                
                                <div class="card-body">
								<h4 class="form-section">Financial Details</h4>
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th>Description</th>
                                            <th>Unit of Measure</th>
                                            <th>Quantity</th>                                            
                                        </tr>
                                        <?php foreach($financedata as $FinData){ ?>
                                            <tr>
                                                <td><?=@$FinData->Description?></td>                                            
                                                <td><?=@$FinData->Unit_of_Measure?></td>                                            
                                                <td><?=@$FinData->Quantity?></td>                                            
                                            </tr>
                                        <?php } ?>
                                    </table>                                                          
                                </div>    
                            </div>
                        </form>                       
                    </div>
                    <?php } ?>
                                        
                </div>
            </div>
        </div>                        
    </div>
</section>                
                <!-- Form wizard with vertical tabs section end -->

    <!-- END: Content-->