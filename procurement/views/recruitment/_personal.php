<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
$baseUrl = Yii::$app->request->baseUrl;
$session = \Yii::$app->session;

$this->title = 'General';
$data  = $session->get('Communication');
/*print '<pre>';
print_r($data);
exit;*/

$submissionStatus = \Yii::$app->recruitment->Applicationstatus();

//$this->params['breadcrumbs'][] = ['label'=>$_GET['desc'].' : '.$this->title,'url'=>['apply','id'=>$_GET['id'],'desc'=>$_GET['desc']]];
/*$this->params['breadcrumbs'][] = ['label'=>'Bio Data','url'=>['apply','id'=>$_GET['id'],'desc'=>$_GET['desc']],'class'=>'current'];*/
 /*if($session->has('Communication')): 

    if(isset($_GET['type'])){//for the external layout
        $this->params['breadcrumbs'][] = ['label'=>'Qualifications','url'=>['qualifications','id'=>$_GET['id'],'desc'=>$_GET['desc'],'type'=>'external']];
    }
    else{
        $this->params['breadcrumbs'][] = ['label'=>'Qualifications','url'=>['qualifications','id'=>$_GET['id'],'desc'=>$_GET['desc']]];
    }
    
 endif;
*/

?>

<div class="site-page">
    <?php
    $session = Yii::$app->session;
    ?>
    <!-- <?php if($session->has('Communication')): ?>
        <input type="hidden" name="setcitizenship" value="<?= $data->Citizenship ?>">
        <input type="hidden" name="setpostcode" value="<?= $data->Post_Code ?>">
    <?php endif; ?> -->
    <h2 style="font-size: 25px; font-weight: bold">Job Application</h2>


    <?php //$this->render('_steps'); ?>



    <?php if(isset($_GET['type'])): ?>
        
    <?php else: ?>
      
    <?php endif; ?>
   

    <?php $general = $session->get('General'); $comm = $session->get('Communication'); ?>
    <?php
    /*print '<pre>';
    print_r($comm); exit;*/
    if (Yii::$app->session->hasFlash('success')): ?>
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

    <?php $session = Yii::$app->session; ?>
    <form id="personal" action="./communication" name="personal">
        <input type="hidden"  name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
        <input type="hidden" name="jobid" value="">
        <input type="hidden" name="Applicant_Type" value="<?= isset($_GET['type'])?'External':'Internal'?>">
        <input type="hidden"  name="personal" value="1" />
        <input type="hidden" name="submitted" value="<?= $submissionStatus ?>">
        <?php if($session->get('Key')){ print '<input type="hidden" name="key" value="'.$session->get('Key').'">'; } ?>
    </form>

        <div class="content-wrapper">
            <div class="card mb-0">
                <div class="card-header collapsed"  href="#collapseOne">
                    <a class="card-title">
                        General information
                    </a>

                </div>
                <div id="collapseOne" class="card-body " >
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <div class="form-group row">
                                <div class="form-group col-sm-4">
                                    <label for="code">First Name <span style="color:red">*</span></label>
                                    <input type="text" form="personal" name="First_Name" value="<?= isset($comm->First_Name)? ucfirst($comm->First_Name):'' ?>" placeholder="First Name" class="form-control" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="code">Middle Name <span style="color:red">*</span></label>
                                    <input type="text" form="personal" name="Middle_Name" value="<?= isset($comm->Middle_Name)? ucfirst($comm->Middle_Name):'' ?>" placeholder="Middle_Name" class="form-control input-normal" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="code">Last Name <span style="color:red">*</span></label>
                                    <input form="personal" type="text" name="Last_Name" value="<?= isset($comm->Last_Name)? ucfirst($comm->Last_Name):'' ?>" placeholder="Last Name"  class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10 mx-auto">
                            <div class="form-group row">
                                <div class="col-sm-4">
                                    <label for="code">ID /Passport Number <span style="color:red">*</span></label>
                                    <input type="text" form="personal" name="ID_Number" value="<?= isset($comm->ID_Number)? $comm->ID_Number:'' ?>" placeholder="ID Number" class="form-control input-normal" required>
                                </div>
                                <div class="col-sm-4">
                                    <label for="code">Gender</label>
                                    <select form="personal" name="Gender" class="form-control">
                                        <option>Select Your Gender</option>
                                        <option value="Male" <?= (isset($comm->Gender) && ($comm->Gender == 'Male' ))?'selected':'' ?>>Male</option>
                                        <option value="Female" <?= (isset($comm->Gender) && ($comm->Gender == 'Female' ))?'selected':'' ?>>Female</option>
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <label for="code">Citizenship <span style="color:red">*</span></label>
                                    <select name="Citizenship" form="personal" id="citizenship"  class="form-control input-normal" required>
                                        <option>Select your Citizenship</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!--<input form="personal" type="submit" value="Save" class="btn btn-success pull-left">-->
                </div>

            </div>
            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
            <input type="hidden" name="key" value="" />

</div>
<br>
</div>

