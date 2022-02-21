<?php

use yii\helpers\Html;

$this->title = 'Recruitment  - Job Description';
$baseUrl = \Yii::$app->request->baseUrl;
/*print '<pre>';
print_r($card); exit;*/

?>

<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-body">
            <section id="ticket-detail">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="card activated">
                            <div class="card-header" style="padding-bottom:10px">

                                <div class="row">
                                    <div class="col-lg-8 col-md-8">

                                        <h2 class="card-title"
                                            style="margin-top: 0px !important; text-transform: capitalize!important;">
                                            <i class="ft-lock"></i> <?= isset($card[0]->Description) ? strtolower($card[0]->Description) : 'Not Set' ?>
                                        </h2>

                                        <p style="margin: 2px 10px 0 23px !important; text-transform: capitalize; !important;"><?= isset($card[0]->Appointment_Type) ? strtolower($card[0]->Appointment_Type) : 'Not Set' ?></p>

                                    </div>

                                    <div class="col-lg-4 col-md-4" style="text-align: right;">
                                        <?php
                                        if (count($missingDocuments) == 0 || !Yii::$app->recruitment->hasprofile()) {
                                            echo Html::a('<i class="ft-file"></i> Apply', ['profile', 'action' => $_GET['id']],
                                                ['class' => 'btn btn-primary pull-right']
                                            );
                                        } else {
                                            echo '<button class="btn btn-default pull-right " style="cursor: not-allowed" disabled></i> Apply</button>';
                                        }

                                        ?>
                                    </div>
                                </div>
                                <?php
                                if (count($missingDocuments) > 0) {
                                    echo "<div class='alert alert-danger col-xs-12'>
                                      <div>Please correct below issue before you can apply for this job</div>";
                                    foreach ($missingDocuments as $key => $item) {
                                        echo ($key + 1) . ". Missing document " . $item;

                                    }

                                    echo "<a href='/recruitment/attachments' class='pull-right text-white'><i class='fa fa-exclamation-circle'></i> Upload missing documents</a> </div>";
                                }
                                ?>

                                <?php
                                if (!Yii::$app->recruitment->hasprofile()) {
                                    echo "<div class='alert alert-danger col-xs-12'>
                                      <div>We could not find your profile profile";
                                    echo "<a href='/recruitment/create' class='pull-right text-white'>Create profile</a> </div>";
                                }
                                ?>

                            </div>

                        </div>
                        <!--  <div class="card">
                        	<div class="card-body">
                        		<div class="media">
                        				<div class="media-body">
                        				<div class="media-list media-bordered">
		                        			<h2 class="mt-0"> <?= isset($card[0]->Description) ? $card[0]->Description : 'Not Set' ?><span class="text-muted font-medium-1"></span>
		                        			</h2>
	                        			</div>
	                        		
                        			</div>
	                        		
	                        	</div>
	                        	<table class="table table-borderless">
	                        		<tbody>
	                        			<tr>
	                        				<th >Appointment Type</th>
	                        				<td><?= isset($card[0]->Appointment_Type) ? $card[0]->Appointment_Type : 'Not Set' ?></td>
	                        			</tr>

	                                </tbody>
	                        	</table>
                			</div>
                        </div> --><!--end card2 -->

                        <div class="card"><!--Qualifications card--->
                            <div class="card-header">
                                <h2 class="card-title">Job Requirements</h2>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped table-bordered responsive ">
                                    <thead>
                                    <tr>
                                        <th width="10%">#</th>
                                        <th width="45%">Qualification_Type</th>
                                        <th width="45%">Description</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    if (property_exists($card[0]->Needs_Requirements, 'Needs_Requirements')) {

                                        $i = 0;
                                        foreach ($card[0]->Needs_Requirements->Needs_Requirements as $q) {
                                            $i++;
                                            $desc = property_exists($q, 'Education_Level_Name') ? $q->Education_Level_Name : 'Not Set';

                                            $qtype = property_exists($q, 'Qualification_Type') ? $q->Qualification_Type : 'Not Set';


                                            echo '<tr>
                                                            <td>' . $i . '</td>
                                                            <td>' . $qtype . '</td>
                                                            <td>' . $desc . '</td>
                                                            
                                                      </tr>';
                                        }
                                    } else {
                                        print '<tr>
                                                                  <td colspan="3">No Qualifications Assigned Yet.</td>
                                                      </tr>';
                                    }

                                    ?>


                                    </tbody>
                                </table>
                            </div>
                        </div><!--end qualifications Card-->


                        <div class="card"><!--Experience card--->
                            <div class="card-header">
                                <h2 class="card-title">Job Responsibilities</h2>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-striped">
                                    <tbody>

                                    <?php

                                    foreach ($categories as $k => $v) {
                                       print '<tr>
                                          <th><b>' . $v . ' : ' . Yii::$app->recruitment->responsibilitycategory($v) . '</b></th>
                                         <tr>' . //Yii::$app->recruitment->jobresponsibilities($v, $_GET['id']) . '</tr>

                                             '</tr>';
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <!-- <table class="table table-striped table-bordered responsive">
                        			<thead>
                        				<tr>
                        					<th width="10%">#</th>
                        					<th width="45%">Responsibility</th>
                        					<th width="45%">Remarks</th>
                        					
                        					
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php

                                        /*print'<pre>';
                                        print_r($card); exit;*/

                                if (property_exists($card[0]->KPA, 'Job_Responsiblities')) {
                                    $i = 0;
                                    foreach ($card[0]->KPA->Job_Responsiblities as $x) {
                                        ++$i;
                                        $resp = property_exists($x, 'Responsibility') ? $x->Responsibility : 'Not Set';
                                        $rem = property_exists($x, 'Remarks') ? $x->Remarks : 'Not Set';

                                        echo '<tr>
                                                            <td>' . $i . '</td>
                                                            <td>' . $resp . '</td> 
                                                            <td>' . $rem . '</td>                                                 
                                                                                                                                          
                                                                                    
                                                            </tr>';
                                    }

                                } else {
                                    print '<tr>
                                                                  <td colspan="3">No Responsobilities Assigned Yet.</td>
                                                      </tr>';
                                }
                                ?>


                        				  

                        		
                        					

                        				
                        			</tbody>
                        		</table> -->
                            </div>
                        </div><!--end Experience Card-->


                    </div>


                </div>
            </section>
        </div>
    </div>
</div>
