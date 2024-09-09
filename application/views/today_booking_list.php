<?php 
$users_data = $this->session->userdata('auth_users'); 
$user_role= $users_data['users_role'];

if($user_role==2 || $user_role==1) 
				  {
				  	//echo "<pre>";print_r($today_booking_patient);die;
				  	if(count($today_booking_patient['appointment'])>0 && in_array('93',$users_data['permission']['section']))
				  	{ 

				  	 ?>
				   <div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Appointment</div>
						<?php if(!empty($today_booking_patient['appointment'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['appointment'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. Apt. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->appointment_no;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>
                         
						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['appointment']) >= 10){   ?>
						<a href="<?php echo base_url('opd'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->
				  	<?php } 

				  	else if(count($today_booking_patient['opd_booking'])>0 && in_array('85',$users_data['permission']['section']))
				  	{
				  		
				  	?>

				  <!-- for OPD booking -->

					<div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						<?php if(!empty($today_booking_patient['opd_booking'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['opd_booking'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. OPD. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->booking_code;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['opd_booking']) >= 10){   ?>
						<a href="<?php echo base_url('opd'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->

					<!-- for OPD booking -->


				  	<?php }
				  	else if(count($today_booking_patient['ipd_booking'])>0 && in_array('121',$users_data['permission']['section']))
				  	{?>

				    <!-- for IPD booking -->

					<div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						<?php if(!empty($today_booking_patient['ipd_booking'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['ipd_booking'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. IPD. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->ipd_no;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['ipd_booking']) >= 10){   ?>
						<a href="<?php echo base_url('opd'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->

					<!-- for IPD booking -->

				  	<?php }
					/* OT Booking  */
				  	else if(count($today_booking_patient['ot_booking'])>0 && in_array('134',$users_data['permission']['section']))
				  	{ ?>

				   <div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						<?php if(!empty($today_booking_patient['ot_booking'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['ot_booking'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. OT. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->booking_code;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['ot_booking']) >= 10){   ?>
						<a href="<?php echo base_url('ot_booking'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->


				  	<?php }
				  	/* OT Booking  */
				  	else if(count($today_booking_patient['dialysis_booking'])>0 && in_array('207',$users_data['permission']['section']))
				  		/* Dialysis Booking  */
				  	{ ?>
				  	<div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						<?php if(!empty($today_booking_patient['dialysis_booking'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['dialysis_booking'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. Dialysis. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->booking_code;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['dialysis_booking']) >= 10){   ?>
						<a href="<?php echo base_url('dialysis_booking'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->


				  	<?php }
					/* Dialysis Booking  */

					/* Test Booking  */
					else if(count($today_booking_patient['test_booking'])>0 && in_array('145',$users_data['permission']['section']))
					{ ?>
					<div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Bookingp</div>
						<?php if(!empty($today_booking_patient['test_booking'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['test_booking'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">Lab.Ref. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->lab_reg_no;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['test_booking']) >= 10){   ?>
						<a href="<?php echo base_url('test_booking'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->
					<?php }
					/* Test Booking  */
					else if(count($today_booking_patient['medicine_sale'])>0 && in_array('60',$users_data['permission']['section'])) 
					{ ?>

				 <div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						<?php if(!empty($today_booking_patient['medicine_sale'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['medicine_sale'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. Sale. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->sale_no;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['medicine_sale']) >= 10){   ?>
						<a href="<?php echo base_url('sale_medicine'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->

					<?php }

					/* vaccination sale  Booking  */
					else if(count($today_booking_patient['vaccination_sale'])>0  && in_array('179',$users_data['permission']['section']))
					{ ?>

					<div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						<?php if(!empty($today_booking_patient['vaccination_sale'])) { ?>
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							<?php $ap=1;
							  foreach($today_booking_patient['vaccination_sale'] as $book_patient){
                                if($ap==1)
                                {
                                	?>
                                     <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">P. Sale. No.</th>
									</tr>
                                	<?php
                                }
                                $ap++;
							  	?>
								<tr>
									<td width="50%" align="left" valign="top"><?php echo ucfirst($book_patient->patient_name);?></td>
									<td width="50%" align="left" valign="top"><?php echo $book_patient->sale_no;?></td>
								</tr>
								<?php }?>
							</tbody>
						</table>

						<?php } ?>
						<!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						<?php if(count($today_booking_patient['vaccination_sale']) >= 10){   ?>
						<a href="<?php echo base_url('sale_vaccination'); ?>" class="rmore btn-sm">Read More</a>
						<?php } ?>
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->

				<?php }
				/* vaccination sale  Booking  */
				else
				{ ?>

					<div class="col-md-2">
					<div class="box1" style="margin-top:9%;">
						<div class="head">Today's Booking</div>
						
						<table class="table dash_tbl">
							<thead class="bg-none text-black">
								
							</thead>
							<tbody>
							
							  
					                 <tr>
										<th width="50%" align="left" valign="top">Patient Name</th>
										<th width="50%" align="left" valign="top">Booking Code</th>
									</tr>
					            
					            
								<tr>
									<td width="50%" align="left" valign="top"></td>
									<td width="50%" align="left" valign="top"></td>
								</tr>
							
							</tbody>
						</table>

					
						<!-- <img src="<?php //echo ROOT_IMAGES_PATH; ?>rmore.png"> -->
						
						 <!-- <a href="<?php //echo base_url('sale_vaccination'); ?>" class="rmore btn-sm">Read More</a> -->
						
					</div>
					</div>
					<div class="col-md-1 col-sm-0 hidden-sm hidden-xs">
						<img src="<?php echo ROOT_IMAGES_PATH; ?>brdr.png" class="h47">
					</div> <!-- 1 -->

           

					<?php } }?>