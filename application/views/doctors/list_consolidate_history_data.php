<!DOCTYPE html>
<html>
<head>
<title></title>
<style>
	*{margin:0;padding:0;box-sizing:border-box;-webkit-box-sizing:border-box;}
	page {
	  background: white;
	  display: block;
	  margin: 1em auto 0;
	  margin-bottom: 0.5cm;
	  box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
	}
	page[size="A4"] {  
	  width: 21cm;
	  height: 27.7cm;  
	  padding: 3em;
	  font-size:13px;
	}
@page {
    size: auto;   /* auto is the initial value */
    margin: 0;  /* this affects the margin in the printer settings */
}
</style>
</head>
<body style="background: rgb(204,204,204);font-family:sans-serif, Arial;color:#333;">

	<page size="A4">
		
		<table cellpadding="0" cellspacing="0" border="0" style="width:100%;font-family:Arial;">
			<tr>
			 <?php if($company_data['photo']!='') {if(file_exists(DIR_UPLOAD_PATH.'logo/'.$company_data['photo'])){?>
					<td valign="top" width="20%" style="height:100px;">
						<img src='<?php echo base_url().'assets/uploads/logo/'.$company_data['photo'];?>' style="width:100%;background-size:cover;">
					</td>

					<?php }}?>

				<td valign="top" width="60%" style="text-align:center;">
					<h2><?php echo ucfirst($branch_detail['user_name']);?></h2>
					<p>
						<?php echo $company_data['address'];?><br>
						Phone No.(s) :+91<?php echo $company_data['contact_no']; ?><br>
						Email ID. :<?php echo $company_data['email']; ?>
					</p>
				</td>
				<td valign="top" width="20%"></td>
			</tr>
		</table>
		<!-- Branch list start -->




	<div style="float:left;width:100%;border:1px solid #111;margin-bottom:10px;">

	<?php


		
		 $users_data = $this->session->userdata('auth_users'); 

          if (array_key_exists("permission",$users_data)){
               $permission_section = $users_data['permission']['section'];
               $permission_action = $users_data['permission']['action'];
          }
          else{
               $permission_section = array();
               $permission_action = array();
          }
			if(!empty($self_opd_collection_list) || !empty($self_pathology_collection_list) || !empty($self_billing_collection_list) || !empty($self_medicine_collection_list) || !empty($self_ambulance_collection_list) ||	!empty($self_dialysis_collection_list) || !empty($self_inventory_collection_list) ||	!empty($self_vacci_collection_list) || !empty($self_ot_collection_list) || !empty($self_eye_collection_list) || !empty($self_pedit_collection_list) ||	!empty($self_dental_collection_list) ||	!empty($self_gyni_collection_list) |	!empty($self_day_care_collection_list)  )
                {
                	$grand_total=0;

		 ?>
			<div style="float:left;width:100%;border:1px solid #111;">


                <div style="float:left; width:100%;font-size:13px;">				
				
					<div style="float:left;width:100%;border-bottom:1px solid #111;font-size:13px;">
						<div style="float:left;width:10%;font-weight:600;padding:4px;"><u>S.No.</u></div>
						<div style="float:left;width:30%;font-weight:600;padding:4px;"><u><?php echo $data= get_setting_value('PATIENT_REG_NO');?></u></div>
						<div style="float:left;width:30%;font-weight:600;padding:4px;"><u>Receipt Date</u></div> 
						<!-- <div style="float:left;width:25%;font-weight:600;padding:4px;"><u>Pay mode</u></div> -->
						<div style="float:left;width:30%;font-weight:600;padding:4px;"><u>Paid Amount</u></div>
					</div>
					
			 

				<?php
				
				if(!empty($self_day_care_collection_list) && in_array('387',$permission_section))
                {

                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Day Care</span>
					</div>
                	<?php
                
                $j = 1 ;
                $self_day_care_total = 0;
                $self_day_care_counter = count($self_day_care_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_day_care_collection_list as $daycare_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $j; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($daycare_collection->created_date)).'/'.$daycare_collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($daycare_collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_ipd_counter==$j){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $daycare_collection->debit; ?></div>
						</div> 
                 <?php
                 $j++;	
                 $self_day_care_total = $self_day_care_total+$daycare_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_day_care_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_day_care_total;
                }
				
				if(!empty($self_opd_collection_list) && in_array('85',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">OPD</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_opd_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_opd_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php
                $grand_total += $self_total;	
                }
				?> 

				<?php
                if(!empty($self_billing_collection_list) && in_array('151',$permission_section))
                {

                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Billing</span>
					</div>
                	<?php
                
                $l = 1 ;
                $self_billing_total = 0;
                $self_billing_counter = count($self_billing_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_billing_collection_list as $billing_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $l; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($billing_collection->created_date)).'/'.$billing_collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($billing_collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_billing_counter==$l){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $billing_collection->debit; ?></div>
						</div> 
                 <?php
                 $l++;	
                 $self_billing_total = $self_billing_total+$billing_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_billing_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_billing_total;
                }
				?>


				<?php
                if(!empty($self_ipd_collection_list) && in_array('121',$permission_section))
                {

                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">IPD</span>
					</div>
                	<?php
                
                $j = 1 ;
                $self_ipd_total = 0;
                $self_ipd_counter = count($self_ipd_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_ipd_collection_list as $ipd_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $j; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($ipd_collection->created_date)).'/'.$ipd_collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($ipd_collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_ipd_counter==$j){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $ipd_collection->debit; ?></div>
						</div> 
                 <?php
                 $j++;	
                 $self_ipd_total = $self_ipd_total+$ipd_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_ipd_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_ipd_total;
                }
				?>


				<?php
                if(!empty($self_pathology_collection_list) && in_array('145',$permission_section))
                {

                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;">
						<span style="border-bottom:1px solid #111;">Pathology</span>
					</div>
                	<?php
                
                $j = 1 ;
                $self_path_total = 0;
                $self_path_counter = count($self_pathology_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_pathology_collection_list as $path_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $j; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($path_collection->created_date)).'/'.$path_collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($path_collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_path_counter==$j){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $path_collection->debit; ?></div>
						</div> 
                 <?php
                 $j++;	
                 $self_path_total = $self_path_total+$path_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_path_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_path_total;
                }
				?>


				<?php
                if(!empty($self_medicine_collection_list) && in_array('60',$permission_section))
                { ?>
            	<div style="float:left;width:100%;font-weight:600;padding:4px;">
					<span style="border-bottom:1px solid #111;">Medicine Sale</span>
				</div>
            <?php
                $m = 1 ;
                $self_med_total = 0;
                $self_med_counter = count($self_medicine_collection_list);
                //echo "<pre>";print_r($self_collection_list);
                foreach($self_medicine_collection_list as $med_collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $m; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($med_collection->created_date)).'/'.$med_collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($med_collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_med_counter==$m){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $med_collection->debit; ?></div>
						</div> 
                 <?php
                 $m++;	
                 $self_med_total = $self_med_total+$med_collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_med_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_med_total;
                }
				?>
			<!-- consolidate collection op -->

			<?php
				if(!empty($self_ambulance_collection_list) && in_array('350',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Ambulance</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_ambulance_collection_list);
                foreach($self_ambulance_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->paid_amount; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->paid_amount;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 


			<?php
				if(!empty($self_dialysis_collection_list) && in_array('207',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Dialysis</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_dialysis_collection_list);
                foreach($self_dialysis_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->paid_amount; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->paid_amount;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 


			<?php
				if(!empty($self_inventory_collection_list) && in_array('173',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Inventory</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_inventory_collection_list);
                foreach($self_inventory_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->paid_amount; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->paid_amount;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 


			<?php
				if(!empty($self_vacci_collection_list) && in_array('179',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Vaccination</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_vacci_collection_list);
                foreach($self_vacci_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->paid_amount; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->paid_amount;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 


			<?php
				if(!empty($self_ot_collection_list) && in_array('134',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">O.T.</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_ot_collection_list);
                foreach($self_ot_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->paid_amount; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->paid_amount;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 


				<?php
				if(!empty($self_eye_collection_list) && in_array('319',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">EYE</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_opd_collection_list);
                foreach($self_opd_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 



				<?php
				if(!empty($self_pedit_collection_list) && in_array('332',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Pediatrician</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_pedit_collection_list);
                foreach($self_pedit_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 



				<?php
				if(!empty($self_dental_collection_list) && in_array('321',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Dental</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_dental_collection_list);
                foreach($self_dental_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 


				<?php
				if(!empty($self_gyni_collection_list) && in_array('315',$permission_section))
                {
                	?>
                	<div style="float:left;width:100%;font-weight:600;padding:4px;margin-top:10px;">
						<span style="border-bottom:1px solid #111;">Gynecology</span>
					</div>
                	<?php 
                $k = 1 ;
                $self_total = 0;
                $self_counter = count($self_gyni_collection_list);
                foreach($self_gyni_collection_list as $collection)
                { 
                 ?>
						<div style="float:left;width:100%;">
							<div style="float:left;width:10%;padding:1px 4px;text-indent:15px;"><?php echo $k; ?></div>
							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('Y', strtotime($collection->created_date)).'/'.$collection->patient_code; ?></span></div>

							<div style="float:left;width:30%;padding:1px 4px;"><span style="padding-left:5px;"><?php echo date('d/m/Y', strtotime($collection->created_date)); ?></span></div>
							<div style="float:left;width:30%;padding:1px 4px;text-align:right; <?php if($self_counter==$k){ echo ' border-bottom:1px solid #111'; } ?>"><?php echo $collection->debit; ?></div>
						</div> 
                 <?php
                 $k++;	
                 $self_total = $self_total+$collection->debit;
                } 
                ?>
					<div style="float:left;width:100%;padding:4px;text-align:right;">
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:left;">Total:</span>
							<span style="float:right;"><?php echo number_format($self_total,2); ?></span>
						</div>
					</div> 
                <!-- </div> -->
                <?php	
                $grand_total += $self_total;
                }
				?> 
				<hr>

				<div style="float:left;width:100%;padding:4px;text-align:right;">
					<h3><span style="float:left;">  Grand Total: </span>
						<div style="float:right;width:20%; font-weight: bold;">
							<span style="float:right;"><?php echo number_format($grand_total,2); ?></span>
						</div>
					</h3>
					</div> 

				</div>
				<?php	
                }
				?>
	</page>

</body>
</html>