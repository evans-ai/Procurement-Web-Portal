
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = "My Applications";
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
                    <h4 class="card-title text-uppercase">MY APPLICATIONS</h4>
                </div>
                <div class="card-content collapse show">                    
                    <div class="card-body card-dashboard">
                        <div class="table-responsive">
                            <table class="table table-striped table-de" id="applist"></table>
                            <?=$this->registerJs(
                                "$(function(){
                                    $('#applist').DataTable({                                    
                                        data: $RfxList,
                                        columns: [
                                            { title: '#' },
                                            { title: 'Reference No.' },
                                            { title: 'Title' },
                                            { title: 'Date Submitted' },
                                            { title: 'Closing Date' },
                                            { title: 'Opening Date' },
                                            { title: 'Successful' },
                                            { title: 'Action', width: '30%'}
                                        ]
                                    });
                                });"); 
                            ?>
                        </div>
                        
                    </div>
                </div>               
            </div>
        </div>
    </div>
</section>                
<!-- Form wizard with vertical tabs section end -->

<!-- END: Content-->