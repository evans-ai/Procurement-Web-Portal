<!-- BEGIN: Content-->
<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'Applicant - Document Attachments';
$baseUrl = Yii::$app->request->baseUrl;
$applicant_no = $session->get('Applicantid');
?>

<div class="app-content content">


    <?= $this->render('_steps'); ?>


    <div class="content-wrapper">
        <div class="content-body">
            <!-- Form wizard with number tabs section start -->
            <section id="number-tabs">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Attachments</h4>

                            </div>
                            <div class="card-content collapse show">
                                <div class="card-body">
                                    <?php if (Yii::$app->session->hasFlash('success')): ?>
                                        <div class="alert alert-success alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">
                                                ×
                                            </button>
                                            <h4><i class="icon fa fa-check"></i>Saved!</h4>
                                            <?= Yii::$app->session->getFlash('success') ?>
                                        </div>
                                    <?php endif; ?>


                                    <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                                        <div class="alert alert-danger alert-dismissable">
                                            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">
                                                ×
                                            </button>
                                            <h4><i class="icon fa fa-check"></i>Error!</h4>
                                            <?= Yii::$app->session->getFlash('error') ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" action="./attachments" enctype="multipart/form-data" class="">

                                        <h6>Attachments</h6>
                                        <fieldset>
                                            <input type="hidden" name="_csrf-backend"
                                                   value="<?= Yii::$app->request->getCsrfToken() ?>"/>
                                            <input type="hidden" class="form-control" name="Applicant_No" readonly
                                                   value="<?= Yii::$app->user->identity->ApplicantId ?>"/>
                                            <input type="hidden" class="form-control" name="Attached" readonly
                                                   value="1"/>

                                            <div class="form-group row">
                                                <div class="col-sm-6  hidden" >
                                                    <label for="From_Date">Document_Description <span style="color:red">*</span></label>
                                                    <input type="text" value="DOC" name="Document_Description" class="form-control">
                                                </div>

                                                <div class="col-sm-6">
                                                    <label for="From_Date">Document <span style="color:red">*</span></label>
                                                    <select type="text" name="DocumentNo" class="form-control">
                                                        <option SELECTED DISABLED>--SELECT DOCUMENT--</option>
                                                        <?php foreach ($documents as $key => $val) { ?>

                                                            <option value="<?php echo $val->Code ?>"><?php echo $val->Description ?></option>

                                                        <?php } ?>
                                                    </select>
                                                </div>


                                                <div class="col-sm-6">
                                                    <label for="To_Date">File Attachment <span
                                                                style="color:red">*</span></label>
                                                    <input type="file" name="attachment" class="form-control"
                                                           required="">
                                                </div>


                                            </div>

                                            <div class="form-group">
                                                <input type="submit" class="btn btn-success" value="Save">

                                                <a href="<?= $baseUrl ?>./comments" class="btn btn-primary">Next</a>
                                            </div>


                                        </fieldset>

                                        <h6>Comments</h6>
                                        <fieldset></fieldset>


                                    </form>

                                    <?php
                                    $documents = Yii::$app->recruitment->myattachments();


                                    ?>


                                    <table class="table table-column">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Document NO.</th>
                                            <th>Document Description</th>
                                            <th>Preview Action</th>
                                            <th>Remove Document Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 0;


                                        if (is_array($documents) && count($documents)) {

                                            foreach ($documents as $d) {
                                                ++$counter;
                                                echo '<tr>
                                                                        <td>' . $counter . '</td>
                                                                        <td>' . $d->DocumentNo . '</td>
                                                                        <td>' . $d->Document_Description . '</td>
                                                                        <th>' . Html::a('View Document', ['viewdoc', 'path' => $d->Document_Link], ['class' => 'btn btn-info']) . '</th>
 <th>' . Html::a('Delete Document', ['purgedoc', 'serial' => $d->Key], [
                                                        'class' => 'btn btn-danger'
                                                    ]) . '</th>
                                                                        
                                                            </tr>';
                                            }
                                        } else {
                                            print '<tr>
                                                      <td colspan="2">You have not attached any documents yet.</td>
                                                </tr>';
                                        }


                                        ?>


                                        </tbody>
                                    </table>


                                </div><!--end card body-->
                            </div><!--end card content-->
                        </div><!--end card-->
                    </div><!--end col-12-->
                </div><!--end row-->
            </section>
        </div><!--end content body-->
    </div><!--end content body-->
</div><!--end app content-->

<!-- <li role="tab" class="first current" aria-disabled="false" aria-selected="true"><a id="steps-uid-0-t-0" href="#steps-uid-0-h-0" aria-controls="steps-uid-0-p-0"><span class="current-info audible">current step: </span><span class="step">1</span> Step 1</a></li> -->