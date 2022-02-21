<?php
use yii\helpers\Html;
use yii\grid\GridView;
$this->title = 'Expression Of Interest';
$baseUrl = Yii::$app->request->baseUrl;
?>
<style>
.summary
{
	display:none!important;
}
</style>
<section id="horizontal-form-layouts">
    <div class="row">
        <div class="container">
			<br><br>
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tender Details</h4> 
                </div>
                <div class="card-content collpase show">
                    <div class="card-body"> 
						<div class="pull-right">
							<?= Html::a('Apply',['/site/apply?item=tender&id='.$id.''],['class' => 'btn btn-danger', 'name' => 'apply']) ?>
						</div>
						<span class="clearfix"></span>
						<br>
                        <form class="form form-horizontal">
                            <div class="form-body">
                                <div class="">

                                    <?= GridView::widget([
                                        'dataProvider' => $Provider,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                            'Title',
                                            'Description',
                                            'Closing_Date',
                                            ['class' => 'yii\grid\ActionColumn',
                                            //'headerOptions' => ['width' => '13%', 'style'=>'color:black; text-align:center'],
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