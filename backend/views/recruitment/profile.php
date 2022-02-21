<?php
 use yii\helpers\Html;
$this->title = 'Applicant - Profile';
$baseUrl = Yii::$app->request->baseUrl;
/*print '<pre>';
print_r($profile); exit;*/
?>

<div class="app-content content">
	<div class="content-wrapper">
        <div class="content-body">
        	<section id="ticket-detail">
			 	 <div class="row">
			 	 	<div class="col-lg-12 col-md-12">
			 	 		 <div class="card activated">
			                                <div class="card-header">
                                                <div class="row">
                                                    <div class="col-lg-8 col-md-8">

                                                             <h2 class="card-title" style="margin-top: 10px !important">
                                                                    <i class="ft-lock"></i> My Profile 
                                                             </h2>

                                                    </div>

                                                    <div class="col-lg-4 col-md-4">
                                                            <?php
                                                                if(Yii::$app->request->get('action')){
                                                                   echo Html::a('<i class="ft-file"></i> Submit Application',['apply','id'=>$_GET['action']],['class'=>'btn btn-primary pull-right']); 
                                                                }
                                                 
                                                                ?>
                                                    </div>




                                                </div>
                                                <div class="row">
                                                  <div class="col-md-12" style="margin: 2px;">                                                
                                                      <?php if (Yii::$app->session->hasFlash('error')):  // display error message ?>
                                                          <div class="alert alert-danger alert-dismissable">
                                                              <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                                                              <h4><i class="icon fa fa-check"></i>Error!</h4>
                                                              <?= Yii::$app->session->getFlash('error') ?>
                                                          </div>
                                                        <?php endif; ?>
                                                  </div>
                                                </div>
			                                   

                                                
			                                </div>			                                
                        </div>
                        <div class="card">
                        	<div class="card-body">
                        		<div class="media">
                        				<div class="media-body">
                        				<div class="media-list media-bordered">



                                            <div class="row">
                                                 <div class="col-lg-8 col-md-8">
                                                    <h2 class="mt-0 " style="margin-top: 10px !important; text-transform: capitalize!important;"> 
                                                        <i class="ft-user"></i><?= '  '.strtolower($profile[0]->First_Name.' '.$profile[0]->Middle_Name.' '.$profile[0]->Last_Name) ?><span class="text-muted font-medium-1"></span>
                                                     </h2>
                                                 </div>
                                                  <div class="col-lg-4 col-md-4">


                                                     <?php
                                                    if(Yii::$app->recruitment->hasprofile()){
                                                             echo Html::a('<i class="ft-edit"></i>&nbsp; Update',['updategeneral'],['class'=>'btn btn-info pull-right','target'=>'_blank']);
                                                    }else{
                                                                    echo Html::a('Create Profile',['index','create'=>1],['class'=>'btn btn-info pull-right','target'=>'_blank']);
                                                    }
                                                    ?>

                                                  </div>
                                            </div>


		                        			

                                           
                                             

	                        			</div><!---end media list--->
	                        		
                        			</div>
	                        		
	                        	</div>
	                        	<table class="table table-bordered table-striped">
	                        		<tbody>

                                <tr>
                                            <th >Applicant No.</th>
                                            <td><?= isset($profile[0]->No)?$profile[0]->No:'' ?></td>
                                </tr>
	                        			<tr>
	                        				<th >ID Number</th>
	                        				<td><?= isset($profile[0]->ID_Number)?$profile[0]->ID_Number:'' ?></td>
	                        			</tr>

                                        
	                        			<tr >
	                        				<th>Gender</th>
	                        				<td><?= $profile[0]->Gender ?></td>
	                        			</tr> 

	                        			<tr>
	                        				<th>Citizenship</th>
	                        				<td><?= isset($profile[0]->Citizenship)?$profile[0]->Citizenship:'' ?></td>
	                        			</tr> 

	                        			<tr>
	                        				<th>Marital_Status</th>
	                        				<td><?= isset($profile[0]->Marital_Status)?$profile[0]->Marital_Status:'' ?></td>
	                        			</tr> 

	                        			<tr>
	                        				<th>Ethnic_Origin</th>
	                        				<td><?= isset($profile[0]->Ethnic_Origin)?$profile[0]->Ethnic_Origin:'' ?></td>
	                        			</tr> 

	                        			<tr>
	                        				<th>Disabled</th>
	                        				<td><?= isset($profile[0]->Disabled)?$profile[0]->Disabled:'' ?></td>
	                        			</tr> 

	                        			<tr>
	                        				<th>Date_Of_Birth</th>
	                        				<td><?= isset($profile[0]->Date_Of_Birth)?$profile[0]->Date_Of_Birth:'' ?></td>
	                        			</tr>
	                        			<tr>
	                        				<th colspan="2" class="bg-gray bg-darken-2">Communication Information</th>
	                        			</tr>
	                        			<tr>
	                        				<td colspan="2">
	                        					<table class="table table-column">

	                        						<tr>
	                        							<th>Mobile Number</th>
	                        							<th>Email</th>
	                        							<th>Post Code</th>
	                        							<th>Postal Address</th>
	                        						</tr>
	                        						<tr>
	                        								<td><?= $profile[0]->Cellular_Phone_Number ?></td>
	                        								<td><?= $profile[0]->E_Mail ?></td>
	                        								<td><?= isset($profile[0]->Post_Code)?$profile[0]->Post_Code:'' ?></td>
	                        								<td><?= isset($profile[0]->Postal_Address)?$profile[0]->Postal_Address:'' ?></td>
	                        						</tr>
	                        					</table>
	                        				</td>
	                        			</tr>


	                        		</tbody>
	                        	</table>
                			</div>
                        </div><!--end card2 -->

                        <div class="card"><!--Qualifications card--->
                        	<div class="card-header">
                                <div class="row">

                                     <div class="col-lg-8 col-md-8">
                                        <h2 class="card-title" style="margin-top: 10px !important; text-transform: capitalize!important;">
                                        <i class="ft-award"></i> Qualifications
                                    </h2>
                                     </div>
                                     <div class="col-lg-4 col-md-4">
                                        <?= Html::a('<i class="ft-edit"></i>&nbsp;Update ',['qualifications'],['class'=>'btn btn-info pull-right','target'=>'_blank']);?>
                                     </div>

                                </div>
                        		

                                
                        	</div><!--end card-header -->
                        	<div class="card-body">
                        		<table class="table table-bordered table-bordered">
                        			<thead>
                        				<tr>
                        					<th>Education Level </th>
                        					<th>Course Name</th>
                        					<th>Grade Name</th>
                        					<th width="5%">From Date</th>
                        					<th width="5%">To Date</th>
                        					<th>Institution</th>
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php
                  if(isset($profile[0]->Applicant_Qualification) && property_exists($profile[0]->Applicant_Qualification,'Applicant_Qualification')){

                                                
                                                 foreach($profile[0]->Applicant_Qualification->Applicant_Qualification as $q){

                                                       echo '<tr>
                                                            <td>'.$q->Education_Level_Name.'</td>
                                                            <td>'.$q->Course_Name.'</td>
                                                            <td>'.$q->Grade_Name.'</td>
                                                            <td>'.$q->From_Date.'</td>
                                                            <td>'.$q->To_Date.'</td>
                                                            <td>'.$q->Institution_Company.'</td>
                                                      </tr>';
                                                      

                                                 }

                        				}else{
                                          print '<tr>
                                                <td colspan="6">You have not added any Qualifications yet.</td>
                                          </tr>';
                                     }	
                        				?>
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end qualifications Card-->


                         <div class="card"><!--Experience card--->
                        	<div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <h2 class="card-title" style="margin-top: 10px !important; text-transform: capitalize!important;"> <i class="ft-clock"></i>&nbsp;&nbsp;Experience</h2>
                                    </div>
                                     <div class="col-lg-4 col-md-4">
                                        <?= Html::a('<i class="ft-edit"></i>&nbsp;Update',['experience'],['class'=>'btn btn-info pull-right','target'=>'_blank']);?>
                                     </div>
                                </div>
                        		

                                
                        	</div><!--end card-header-->
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>Institution Company </th>
                        					<th>Responsibility</th>
                        					<th width="5%">To Date</th>
                        					<th width="5%">From Date</th>
                        					<th>Salary</th>
                        					
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php

                        if(isset($profile[0]->Applicant_Work_Experience) && property_exists($profile[0]->Applicant_Work_Experience, 'Applicant_Work_Experience')){
                                                foreach($profile[0]->Applicant_Work_Experience->Applicant_Work_Experience as $x){
                                                       print '<tr>
                                                            <td>'.$x->Institution_Company.'</td>
                                                            <td>'.$x->Responsibility.'</td> 
                                                            <td>'.$x->To_Date.'</td>                                                
                                                                  <td>'.$x->From_Date.'</td>                                                          
                                                            <td>'.$x->Salary.'</td>
                                                      </tr>';
                                                 }
                                                }else{
                                          print '<tr>
                                                <td colspan="5">You have not added any relevant experience yet.</td>
                                          </tr>';
                                     }
                                                 

                        				?>	
                        					

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end Experience Card-->


                        <div class="card"><!--Referee card--->
                        	<div class="card-header">


                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <h2 class="card-title"><i class="ft-check-square"></i>&nbsp;&nbsp;Referee(s)</h2>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                         <?= Html::a('<i class="ft-edit"></i>&nbsp;Update',['referee'],['class'=>'btn btn-info pull-right','target'=>'_blank']);?>
                                    </div>
                                </div>

                        		

                               
                        	</div><!--end card header-->
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>Names </th>
                        					<th>Designation</th>
                        					<th>Company</th>
                        					<th>Telephone No</th>
                        					<th>E Mail</th>
                        					
                        				</tr>
                        			</thead>
                        			<tbody>


                        				<?php

                                    if(isset($profile[0]->Applicant_Referees) && property_exists($profile[0]->Applicant_Referees, 'Applicant_Referees')){

                                                       foreach($profile[0]->Applicant_Referees->Applicant_Referees as $ref){
                                                       echo '<tr>
                                                            <td>'.$ref->Names.'</td>
                                                            <td>'.$ref->Designation.'</td> 
                                                            <td>'.$ref->Company.'</td>                                                    
                                                            <td>'.$ref->Telephone_No.'</td>                                                           
                                                            <td>'.$ref->E_Mail.'</td>
                                                      </tr>';
                                                 }

                                     }else{
                                          print '<tr>
                                                <td colspan="5">You have not attached any relevant referees yet.</td>
                                          </tr>';
                                     }
                                         

                        				?>        
                        					

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end referee Card-->


                        <div class="card"><!--Attachments card--->
                        	<div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <h2 class="card-title" style="margin-top: 10px !important; text-transform: capitalize!important;"> <i class="ft-file-text"></i> My Attached Documents(s)</h2>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                         <?= Html::a('<i class="ft-edit"></i>&nbsp;Update',['attachments'],['class'=>'btn btn-info pull-right','target'=>'_blank']);?>
                                    </div>
                                </div>
                        		
                               
                        	</div><!--end card-header-->
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>#</th>
                        					<th>Document Description </th>
                                            <th>Preview Action</th>                      					                      					
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php $counter = 0;
                                    if(isset($profile[0]->Applicant_Documents) && property_exists($profile[0]->Applicant_Documents, 'Applicant_Documents')){

                                     foreach($profile[0]->Applicant_Documents->Applicant_Documents as $d){
                                                       ++$counter;
                                                        echo '<tr>
                                                                        <td>'.$counter.'</td>
                                                                        <td>'.$d->Document_Description.'</td>
                                                                        <th>'.Html::a('<i class="ft-eye">&nbsp;</i>View',['viewdoc','path'=> $d->Document_Link],['class'=>'btn btn-info pull-right']).'</th>
                                                            </tr>';
                                                } 
                                          }else{
                                                print '<tr>
                                                      <td colspan="2">You have not attached any documents yet.</td>
                                                </tr>';
                                          }
                        		
                                    
                        		?>		 	
                        					
                        					

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end Attachments Card-->

                        <div class="card"><!--Comments card--->
                        	<div class="card-header">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8">
                                        <h2 class="card-title"  style="margin-top: 10px !important; text-transform: capitalize!important;"><i class="ft-message-square"></i> &nbsp;&nbsp; Comments (About MySelf)</h2>
                                    </div>
                                    <div class="col-lg-4 col-md-4">
                                        <?= Html::a('<i class="ft-edit">&nbsp;</i>Update',['comments'],['class'=>'btn btn-info pull-right','target'=>'_blank']);?>
                                    </div>
                                </div>
                        		

                            

                        	</div>
                        	<div class="card-body">
                        		<table class="table table-bordered table-striped">
                        			<thead>
                        				<tr>
                        					<th width="10%">#</th>
                        					<th width="50%">Comment </th>
                        					<th width="5%">Date</th>                      					                      					
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php $counter = 0;
                                                if(isset($profile[0]->Applicant_Comments_Views) && property_exists($profile[0]->Applicant_Comments_Views, 'Applicant_Comments_Views')){
                                                      foreach($profile[0]->Applicant_Comments_Views->Applicant_Comments_Views as $c){
                                                       ++$counter;

                                                       echo '<tr>
                                                            <td>'.$counter.'</td>
                                                            <td>'.$c->Views_Comments.'</td>
                                                            <td>'.$c->Date.'</td>
                                                            
                                                      </tr>';

                                                 }
                                                }else{
                                                      print '<tr>
                                                            <td colspan="3">You have not given a brief comment about yourself.</td>
                                                      </tr>';
                                                }
                        				 
                        				 	
                        				?>	 
                        					

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end Comments Card-->







			 	 	</div>

			 	 	

			 	 </div>
			 </section>
        </div>
    </div>
</div>
 