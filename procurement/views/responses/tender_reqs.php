
<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

$this->title = 'Expression Of Interest';
$baseUrl = Yii::$app->request->baseUrl;
$baseUrl = Yii::$app->request->baseUrl;
$Tender_No = Yii::$app->request->get('id');
$this->params['breadcrumbs'][] = ['label'=>'Open RFX','url'=>['/home/list']];
$this->params['breadcrumbs'][] = ['label'=>$Tender->Title,'url'=>['/home/viewref','ref'=>$Tender_No]];

$this->params['breadcrumbs'][] = $this->title;

?>

<!-- Form wizard with number tabs section start -->
<!-- Custom Listgroups start -->
<section id="custom-listgroup">                    
    <div class="row match-height">
        <div class="col-lg-12 col-md-12">
		
            <div class="card">
			<div class="card-header">
			<h4><?=$this->title;?></h4>
			</div>
			<br>
                <div class="card-content collapse show">
                    <form class="form" action="./submit" method="POST"  enctype="multipart/form-data">
                    <div class="card-body">
                        <?php if (Yii::$app->session->hasFlash('success')): ?>
                            <div class="alert alert-success alert-dismissable">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                <h4><i class="icon fa fa-check"></i>Saved!</h4>
                                <?= Yii::$app->session->getFlash('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                          <div class="alert alert-danger alert-dismissable">
                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                              <h4><i class="icon fa fa-check"></i>Error!</h4>
                              <?= Yii::$app->session->getFlash('error') ?>
                          </div>
                        <?php endif; ?>
                            <table class="table table-bordered table-condensed">
                                <tr>
                                    <th width="20%">Reference No.</th>
                                    <td><?=$Tender->Document_No ?></td>
                                </tr>
                                <tr>
                                    <th width="20%">Title</th>
                                    <td><?=$Tender->Title ?></td>
                                </tr>
                            </table>
							<br>
                            <h4>Responses <a href="/responses/no-quote?ref=<?=$Tender_No?>" class="btn btn-danger pull-right">Submit a No Quote Response</a></h4>
							<hr>
                            <input type="hidden" name="Document_No" value=<?= sizeof($DataArray)>0?$DataArray[0]['Document_No']:'' ?>>
                            <table class="table table-sm table-striped">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="20%">Description</th>
                                    <th width="15%">Response</th>
                                    <th width="15%">Options</th>
                                    <th width="15%">Document No</th>
                                    <th width="15%">Expiry Date</th>
                                    <th width="15%">Attachment</th>
                                </tr>
                                <hr>
                                <?php
                                    foreach($DataArray as $i => $Data) {
                                        if($Data['Evaluation_Guide']) continue;
                                        $Properties =  @$DocumentProperties[$Data['DocumentType']];
                                      ?>
                                        <tr>
                                            <td>
                                                <?= $i+1; ?>.
                                            </td>
                                            <td>
                                                <?= $Data['Description']; ?>
                                                    <input type="hidden" class="form-control"   name=  "Tender_No" value="<?= $_GET['id'] ?>" required="true" placeholder="cert">
                                                <input type="hidden" class="form-control"  name ="Responses[<?= $i ?>][Key]"value="<?= $Data['Key'] ?>"> 
                                                <input type="hidden" class="form-control"  name ="Responses[<?= $i ?>][RequirementID]"value="<?= $Data['Requirement_ID'] ?>"> 
                                                <input type="hidden" class="form-control"  name ="Responses[<?= $i ?>][ResponseHeaderNo]" value="<?= $Data['Response_Header_No'] ?>"> 
                                                <input type="hidden" class="form-control"  name ="Responses[<?= $i ?>][DocumentDescription]" value="<?= $Data['Document_Description'] ?>"> 
                                            </td>
                                            <td>
                                                <?= 
                                                Html::dropDownList("Responses[$i][Response]", $Data['Response'], 
                                                    @ArrayHelper::map($Data['ResponseType'], 'Response', 'Response'), ['class'=>'form-control','id'=> 'response['. $i .']' ]) ?>                    
                                            </td>
                                            <td>
                                                <?php  if($Data['Requires_Attachment']) { 
                                                    if(@$Properties['exists']){
                                                ?>
                                                <?= Html::checkbox("Responses[<?= $i ?>][useexisting]", false, ['label' => 'Use existing', 'disabled' => true, 'value' => $Properties['exists']]); ?>
                                                <br><?= Html::a('[View document]', ['#'], ['style' => 'font-size: xx-small']) ?>
                                                <?php  } } ?>
                                            </td>
                                            <td>
                                                <?php  if($Data['Document_No_Mandatory']) { ?>
                                                <input type="text" class="form-control" name="Responses[<?= $i ?>][DocumentNo]" value="<?=$Data['Document_No'] ?>" required="true" placeholder="cert">
                                                <?php  } ?>                                    
                                            </td>
                                            <td>
                                                <?php  if($Data['Requires_Attachment']) { 
                                                    if(@$Properties['expires']){    
                                                ?>
                                                    <input type="date" class="form-control" name="Responses[<?= $i ?>][Expiry]" value="<?=@$Data['Expiry_Date'] ?>" type="date" required="true" placeholder="cert">
                                                <?php  } }?>
                                            </td> 
                                            <td>
                                                <?php  if($Data['Requires_Attachment']) { ?>
                                                <label id="projectinput8" class="file center-block">
                                                    <input type="file" accept="application/pdf"  required="true" name="files[<?= $i ?>][files]" name="Responses[<?= $i ?>][files]">            
                                                    <span class="file-custom"></span>
                                                </label>
                                                <?php  } ?>                                         
                                            </td>                           
                                        </tr>
                                    <?php
                                    } 
                                ?> 
                            </table>                                                                                                       
                    </div>
                </div>
                <?php if($FinancialData){ ?>
                <div class="card-body">                     
                    <div class="card-header">
                        <div class="card-title">
                            FINANCIAL RESPONSE 
                            <span class="required" >*</span> 
                             (All Prices MUST be Inclusive of Taxes) 
                        </div>
                    </div>                    
                    <div class="repeater-default">
                        <div data-repeater-item>
                            <div class="form row">
                                <div class="form-group mb-1 col-sm-12 col-md-1">
                                    #
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-5">
                                    Description
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-2">
                                    Unit Of Measure
                                </div>          
                                <div class="form-group mb-1 col-sm-12 col-md-2">
                                    Quantity
                                </div>
                                <div class="form-group mb-1 col-sm-12 col-md-2">
                                    Unit Price
                                     
                                </div>   
                                                  
                            </div>
                        </div>
                        <hr>
                                    
                        <?php $FinCount = 1; foreach($FinancialData as $FinData){  ?>
                            <?php                  
                            ?>
                            <div data-repeater-item>
                                <div class="form row">
                                    <div class="form-group mb-1 col-sm-12 col-md-1">
                                        <?= $FinCount++; ?>
                                           
                                    </div>
                                    <div class="form-group mb-1 col-sm-12 col-md-5">
                                        <?= $FinData['Description'] ?>
                                        <input type="hidden" class="form-control"name ="Unit_Price[<?=$FinCount?>][Key]" value="<?= $FinData['Key']?>" required="true" placeholder="cert"> 
                                    </div>
                                    <div class="form-group mb-1 col-sm-12 col-md-2">
                                        <?= @$FinData['Unit_of_Measure'] ?>
                                    </div> 
                                    <div class="form-group mb-1 col-sm-12 col-md-2">
                                        <?= @$FinData['Quantity'] ?>
                                    </div>  
                                  
                                    <div class="form-group mb-1 col-sm-12 col-md-2"> 
                                        <input type="text" class="form-control" name="Unit_Price[<?=$FinCount?>][Unit_Price]" value="<?=$FinData['Unit_Price'] ?>" min="0" step="0.01" required="true" placeholder="Unit Price">
                                    </div>                                                       
                                </div>                               
                            </div>
                        <?php } ?> 
                    </div>                                                                                                  
                </div>
                <?php } ?>
				<div class="col-md-12">
                <div class="form-group overflow-hidden">
                    <h4 class="form-section">Tender Document</h4>
                        <div class="form-group">
                            <input type="file" name="tenderdocx" required />
                        </div>
                        <div class="buttons-group float-right">                                
                            <button type="submit" class="btn btn-primary">
                               Submit
                            </button>
                        </div>                                
                    </div>
                </div>
				<input type="hidden" class="form-control"   name=  "Tender_No" value="<?= $_GET['id'] ?>" required="true" placeholder="cert">
                </form>
                </div>
            </div>
        </div>                        
    </div>
</section>                
                <!-- Form wizard with vertical tabs section end -->

    <!-- END: Content-->