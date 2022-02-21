<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Directors List';
$baseUrl = Yii::$app->params['baseUrl'];

// print '<pre>';
// print_r($businesstype); exit;

?>
   
<section id="basic-form-layouts" class="container ">
    <div class="row match-height">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header" style="background: #fff">              
                    <h1 class="card-title">Business Profile</h1>
                </div>
                <div class="card-content collapse show">
                    <div class="card-body">
                        <?=$this->render('steps', ['active' => 'docs']) ?>
                        <br><br>
                        <br>
                        <hr/>
                        <p>You are applying to be registered as a supplier under the following categories:</p>
                        <ol>
                            <?php foreach($OpenCategories as $OpenCat){ ?>
                            <li><?=$OpenCat->Category_Name ?></li>
                            <?php } ?>
                        </ol>
                        <p>Please attach the following documents to continue:</p>
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
                        <table class="table table-sm">
                            <tr>
                                <th width="30%">Document</th>
                                <th width="20%">Options</th>
                                <th width="10%">Attachment Area</th>
                                <th width="20%">Document No.</th>
                                <th width="20%">Expiry Date</th>
                            </tr>
                            <?php foreach($DocumentsList as $DocumentID => $Document){ ?>
                                <tr>
                                    <td><?=$Document['name'] ?></td>
                                    <td>
                                        <?php if(array_key_exists($DocumentID, $ExistingDocuments)){ ?>
                                            <?= Html::checkbox($DocumentID.'[useexisting]', false, ['label' => 'Use existing', 'value' => $ExistingDocuments[$DocumentID]]); ?>
                                            <br><?= Html::a('[View document]', ['view-document', 'ref' => $ExistingDocuments[$DocumentID]], ['style' => 'font-size: xx-small', 'target' => '_blank']) ?>
                                        <?php } ?>
                                    </td>
                                    <td><input clas="btn btn-default" type="file" accept=".pdf" name="<?=$DocumentID?>[file]" <?=array_key_exists($DocumentID, $ExistingDocuments) ? '' : 'required'?> /></td>
                                    <td>
                                        <?php if($Document['number']){ ?>
                                            <div class="form-group">
                                                <?= Html::input('text', $DocumentID.'[number]', '', ['class' => 'form-control']) ?>
                                            </div>                                            
                                        <?php } ?>
                                    </td>                                    
                                    <td>
                                        <?php if($Document['expires']){ ?>
                                            <div class="form-group">
                                                <?= Html::input('date', $DocumentID.'[expiry]', '', ['class' => 'form-control']) ?>
                                            </div>                                            
                                        <?php } ?>
                                        <?= Html::input('hidden', $DocumentID.'[categories]', @implode('###', @$DocumentiCategories[$DocumentID]), ['class' => 'form-control']) ?>
                                        <?= Html::input('hidden', $DocumentID.'[name]', $Document['name'], ['class' => 'form-control']) ?>
                                    </td>                                    
                                </tr>
                            <?php } ?>
                        </table>
                        <?=Html::a('Previous', 'personnel', ['class' => 'btn btn-danger']) ?>
                        <?= Html::submitButton('Next', ['class' => 'btn btn-primary']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>                              
<!-- Form wizard with vertical tabs section end -->
