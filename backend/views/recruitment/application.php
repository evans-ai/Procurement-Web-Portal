<?php
/*print '<pre>';
print_r($application); exit;*/
?>

<?php
 use yii\helpers\Html;
$this->title = 'Applicant - Job Application';
$baseUrl = Yii::$app->request->baseUrl;
/*print '<pre>';
print_r($application); exit;*/
?>

<div class="app-content content">
	<div class="content-wrapper">
        <div class="content-body">
        	<section id="ticket-detail">
			 	 <div class="row">
			 	 	<div class="col-lg-12 col-md-12">
			 	 		 <div class="card activated">
			                                <div class="card-header">
			                                    <h2 class="card-title">
			                                        <i class="ft-lock"></i> JOB Application 
			                                    </h2>
			                                </div>			                                
                        </div>
                        <div class="card">
                        	<div class="card-body">
                        		<div class="media">
                        				<div class="media-body">
                        				<div class="media-list media-bordered">
		                        			<h2 class="mt-0" style="margin-top: 10px !important; text-transform: capitalize!important;"> 
                                                            <?= strtolower($application[0]->ApplicantName) ?>
                                                            <span class="text-muted font-medium-1"></span>
		                        			</h2>
	                        			</div>
	                        		
                        			</div>
	                        		
	                        	</div>
	                        	<table class="table table-bordered">
	                        		<tbody>
	                        			<tr>
	                        				<th >Applied Position</th>
	                        				<td><?= $application[0]->JobDescription ?></td>
	                        			</tr>

	                        			
	                        			<!-- <tr>
	                        				<th colspan="2" class="bg-red bg-lighten-2">Communication Information</th>
	                        			</tr>
                                                <tr>
                                                      <td colspan="2">
                                                            <table class="table table-bordered">

                                                                  <tr>
                                                                        <th>Mobile Number</th>
                                                                        <th>Email</th>
                                                                        <th>Post Code</th>
                                                                        <th>Postal Address</th>
                                                                  </tr>
                                                                  <tr>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                              <td></td>
                                                                  </tr>
                                                            </table>
                                                      </td>
                                                </tr> -->
	                        			


	                        		</tbody>
	                        	</table>
                			</div>
                        </div><!--end card2 -->

                        <div class="card"><!--Qualifications card--->
                        	<div class="card-header">
                        		<h2 class="card-title">Qualifications</h2>
                        	</div>
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>Education Level </th>
                        					<th>Course Name</th>
                        					<th>Grade Name</th>
                        					<th>From Date</th>
                        					<th>To Date</th>
                        					<th>Institution Company</th>
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php
                                                if(property_exists($application[0]->Job_Application_Qualification, 'Job_Application_Qualification')){

                                                      foreach($application[0]->Job_Application_Qualification->Job_Application_Qualification as $q){
                                                            echo '<tr>
                                                            <td>'.$q->Education_Level_Name.'</td>
                                                            <td>'.$q->Course_Name.'</td>
                                                            <td>'.$q->Grade_Name.'</td>
                                                            <td>'.$q->From_Date.'</td>
                                                            <td>'.$q->To_Date.'</td>
                                                            <td>'.$q->Institution_Company.'</td>
                                                      </tr>';
                                                 }

                                                }
                                                else{
                                                      print '<tr>
                                                      <td colspan="6">You have not added any qualifications.</td>
                                                      </tr>';
                                                }

?>
                        					 
                        					

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end qualifications Card-->


                         <div class="card"><!--Experience card--->
                        	<div class="card-header">
                        		<h2 class="card-title">Experience</h2>
                        	</div>
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>Institution Company </th>
                        					<th>Responsibility</th>
                        					<th>To Date</th>
                        					<th>From Date</th>
                        					<th>Salary</th>
                        					
                        				</tr>
                        			</thead>
                        			<tbody>


                        				<?php

                        if(property_exists($application[0]->Job_App_Work_Experience, 'Job_App_Work_Experience')){
                              foreach($application[0]->Job_App_Work_Experience->Job_App_Work_Experience as $x){
                                          echo '<tr>
                                                            <td>'.$x->Institution_Company.'</td>
                                                            <td>'.$x->Responsibility.'</td> 
                                                            <td>'.$x->To_Date.'</td>                                                
                                                                  <td>'.$x->From_Date.'</td>                                                          
                                                            <td>'.$x->Salary.'</td>
                                                      </tr>';
                                     }
                              }else{
                                    print '<tr>
                                                <td colspan="5">No experience added yet.</td>
                                    </tr>';
                              }




                        					 
                        					?>

                 
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end Experience Card-->


                        <div class="card"><!--Referee card--->
                        	<div class="card-header">
                        		<h2 class="card-title">Referee(s)</h2>
                        	</div>
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
                        				if(property_exists($application[0]->Job_Application_Referees, 'Job_Application_Referees')){

                        					foreach($application[0]->Job_Application_Referees->Job_Application_Referees as $ref){

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
                        						<td colspan="5">No Referees Attached.</td>
                        					</tr>';
                        				}
                        				 

                        					 
                        					?>

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end referee Card-->


                        <div class="card"><!--Attachments card--->
                        	<div class="card-header">
                        		<h2 class="card-title">My Attached Documents(s)</h2>
                        	</div>
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>#</th>
                        					<th>Document Description </th>                      					                      					
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php $counter = 0;
			                        			if(property_exists($application[0]->Job_App_Documents, 'Job_App_Documents')){
			                        					foreach($application[0]->Job_App_Documents->Job_App_Documents as $d){
			                        						++$counter; 
			                        					
			                        				 	
			                        					echo '<tr>
			                        						<td>'.$counter.'</td>
			                        						<td>'.$d->Document_Description.'</td>
			                        						
			                        					</tr>';
			                        					}
			                        					 
			                        			}else{
			                        				print '<tr>
			                        					<td colspan="2">No Documents Attached</td>
			                        				</tr>';
			                        			}
                        				 
                        					?>

                        				
                        			</tbody>
                        		</table>
                        	</div>
                        </div><!--end Attachments Card-->

                        <div class="card"><!--Comments card--->
                        	<div class="card-header">
                        		<h2 class="card-title">Comments (About MySelf)</h2>
                        	</div>
                        	<div class="card-body">
                        		<table class="table table-bordered">
                        			<thead>
                        				<tr>
                        					<th>#</th>
                        					<th>Comment </th>
                        					<th>Date</th>                      					                      					
                        				</tr>
                        			</thead>
                        			<tbody>
                        				<?php $counter = 0;
                        				if(property_exists($application[0]->Job_App_Comments_Views, 'Job_App_Comments_Views')){


                        						 foreach($application[0]->Job_App_Comments_Views->Job_App_Comments_Views as $c){
                        						 		++$counter;

                        						 		 echo '<tr>
                        						<td>'.$counter.'</td>
                        						<td>'.$c->Views_Comments.'</td>
                        						<td>'.$c->Date.'</td>
                        						
                        					</tr>';
                        						 }  
                        				 	
                        					
                        				}else{
                        					print '<tr>
                        						<td colspan="3">No Comments Added.</td>
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
