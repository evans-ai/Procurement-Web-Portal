<table class="table table-striped table-bordered auto-fill" id="dt">
                                		<thead>
                                			<th>Job Id</th>
                                			<th>Job Description</th>
                                			<th>Appointment Type</th>
                                			<th>Application Start Date</th>
                                			<th>Application End Date</th>
                                      <?php if(!Yii::$app->user->isGuest): ?>
                                			   <th>Apply</th>
                                      <?php endif; ?>
                                		</thead>
                                		<tbody>
                                    <?php 
	                                    foreach($jobs as $j){
	                                    	$desc = isset($j->Description)?$j->Description:'Description Not Set';
	                                    	$apptype = isset($j->Appointment_Type)?$j->Appointment_Type:'Not Set';
	                                    	print
	                                    	 '<tr>
	                                    	 	<td>'.$j->No.'</td>
	                                    	 	<td>'.$desc.'</td>
	                                    	 	<td>'.$apptype.'</td>
	                                    	 	<td>'.$j->Start_Date.'</td>
	                                    	 	<td>'.$j->End_Date.'</td>';
                                          if(!Yii::$app->user->isGuest){
                                            print'<td><a href="./apply?need='.$j->No.'" class="btn btn-primary">Apply</a></td>';

                                          }
	                                    	 	

                                          print '</tr>';
	                                    }
                                     ?>
                                 </tbody>
                                 </table>