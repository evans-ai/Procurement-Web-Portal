<?php
use yii\helpers\Html;
use yii\grid\GridView;
$this->title = 'My Favourites';
$this->params['breadcrumbs'][] = $this->title;
$baseUrl = Yii::$app->request->baseUrl;
?>
<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase"><?=$this->title;?></h4>
                </div>
                <section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?php if (Yii::$app->session->hasFlash('success')){ ?>
                            <div class="alert alert-success alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i>Success</h4>
                            <?= Yii::$app->session->getFlash('success') ?>
                            </div>
                        <?php } ?>
                        <?php if (Yii::$app->session->hasFlash('error')){  // display error message ?>
                            <div class="alert alert-danger alert-dismissable">
                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                            <h4><i class="icon fa fa-check"></i>Error! </h4>
                            <?= Yii::$app->session->getFlash('error') ?>
                            </div>
                        <?php } ?>
                        <table class="table table-striped table-condensed" id="takataka"></table>
                        
                        <?=$this->registerJs(
                            "$(function(){
                                $('#takataka').DataTable({                                    
                                    data: $Provider,
                                    columns: [
                                        { title: '#' },
                                        { title: 'Reference No.' },
                                        { title: 'Title' },
                                        { title: 'Closing Date' },
                                        { title: 'Response Status' },
                                        { title: 'Action', width: '20%' }
                                    ]
                                });
                            });"); ?>
                    </div>
                </div>
            </div>
        </div>                        
      </div>
 </section>                
        
</div>
</section>
