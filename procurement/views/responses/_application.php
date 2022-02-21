<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Tender Response';
?>
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title text-uppercase">
                        Tender Response - #<?=$Response->Response_Id ?>
                        <a href="/home/dashboard" class="btn btn-danger pull-right">Exit</a>
                    </h4>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?php if (Yii::$app->session->hasFlash('success')){ ?>
                              <div class="alert alert-success alert-dismissable">
                                  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                  Success! <?= Yii::$app->session->getFlash('success') ?>
                              </div>
                        <?php } ?>

                        <?php if (Yii::$app->session->hasFlash('error')){ ?>
                          <div class="alert alert-danger alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                              Error! 
                              <?= Yii::$app->session->getFlash('error') ?>
                          </div>
                        <?php } ?>
                        <h5>Tender Details</h5>
                        <table class="table table-bordered table-condensed">
                            <tr>
                                <th width="20%">Reference No.</th>
                                <td><?=$Tender->Document_No ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Title</td>
                                <td><?=$Tender->Title ?></td>
                            </tr>
                            <tr>
                                <td width="20%">Closing Date</td>
                                <td><?=@$Tender->Closing_Date ?></td>
                            </tr>
                        </table>
                        <h5>Response</h5>
                        <table class="table">
                            <tr>
                                <th>#</th>
                                <th>Requirement</th>
                                <th>Response</th>
                                <th>Document No.</th>
                                <th>Action</th>
                            </tr>
                            <?php
                                if(@$Response->Response_Lines->Response_Lines){
                                $count = 1;
                                foreach($Response->Response_Lines->Response_Lines as $Line){ 
                                    if($Line->Evaluation_Guide) continue;
                            ?>                                
                                <tr>
                                    <td><?=$count++ ?></td>
                                    <td><?=@$Line->Description ?></td>
                                    <td><?=@$Line->Response ?></td>
                                    <td><?=@$Line->Document_No ? @$Line->Document_No : '_' ?></td>
                                    <td width="15%">
                                        <?php if(@$Line->Requires_Attachment){?>
                                        <a target="_blank" href="/responses/view-file?ref=<?=@$Line->Requirement_ID?>&rec=<?=@$Line->Response_Header_No?>">View File</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }} ?>
                        </table>
                        <?php 
                            if(@$Response->Financial_Response_Lines){
                        ?>
                        <h5>Financials (Prices Inclusive of Taxes)</h5>  
                        <table class="table table-striped table-condensed table-bordered">
                            <tr>
                                <th>#</th>
                                <th>Description</th>
                                <th>Unit of Measure</th>
                                <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total Amount</th>
                            </tr>
                            <?php
                                if(@$Response->Financial_Response_Lines->Financial_Response_Lines){
                                $count = 1; $Amount = 0;
                                foreach($Response->Financial_Response_Lines->Financial_Response_Lines as $Line){ 
                                    $Amount += @$Line->Amount;
                            ?>                                
                                <tr>
                                    <td><?=$count++ ?></td>
                                    <td><?=$Line->Description ?></td>
                                    <td><?=@$Line->Unit_of_Measure ?></td>
                                    <td><?=@$Line->Quantity ?></td>
                                    <td><?=number_format(@$Line->Unit_Price, 2) ?></td>
                                    <th style="font-weight: 600;"><?=number_format(@$Line->Amount, 2) ?></th>             
                                </tr>
                            <?php }} ?>
                            <tr>
                                <th style="font-weight: 600; text-align: center;" colspan="5">Total</th>
                                <th style="font-weight: 600;"><?=number_format(@$Amount, 2) ?></th>
                            </tr>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>                        
    </div>
</section>                
                <!-- Form wizard with vertical tabs section end -->

    <!-- END: Content-->