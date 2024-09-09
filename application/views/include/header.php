<?php
$users_data = $this->session->userdata('auth_users'); 
if($users_data['users_role']!=1)
{
   get_under_maintenance();
} 
?>	
<!-- // header -->
	<div class="row">
		<div class="col-md-12 header">
			<div class="row">
					<?php
                $company_data = $this->session->userdata('company_data');
                //print_r($this->session->all_userdata());
                if(isset($company_data))
                { 
                ?>
				<div class="col-md-4 col-sm-4">
				
	                <div class="title"><?php echo $company_data['branch_name']; ?></div> 
						<div class="add">
							<div class="add-txt"><?php echo $company_data['address']; ?> </div>
							<div class="add-txt">Phone:- <?php echo $company_data['contact_no']; ?></div>
							<div class="add-txt">Email: <?php echo $company_data['email']; ?></div> 
							
					    </div>
					</div> <!-- 4 -->    
                <?php	
                }
                else
                {
                	?>	

                	<div class="col-md-4 col-sm-4">
					<div class="title">Sara Clinic</div>
					<div class="title2">Sara Technologies Pvt. Ltd.</div>
					<div class="add">
						<!--<div class="add-txt">A-40, Tower B, 2nd Floor (208),
I-Thum, Sector-62, Noida 201301</div>-->
						<div class="add-txt">Phone:- +91-8506080374</div>
						
					</div>
				</div> <!-- 4 -->
                	<?php 
                }
				?> 
				 <!-- 4 -->
				<div class="col-md-4 col-sm-4 text-center hidden-xs">
					<!-- // logo -->
					<div class="logo"><!--<img src="<?php //echo ROOT_IMAGES_PATH; ?>logo.png" class="img-responsive">--></div>
				</div> <!-- 4 -->
				<div class="col-md-4 col-sm-4 hidden-xs">
					<div class="t1">Sara Technologies Pvt. Ltd.</div>
				<!--	<div class="t2">A-40, Tower B, 2nd Floor (208),
I-Thum, Sector-62, Noida 201301</div>-->
					<div class="t2">Phone: +91-8506080374</div>
					<div class="t2">support@sarasolutions.in</div>
					<div class="t2">www.sarasolutions.in</div>
				</div> <!-- 4 -->
			</div> <!-- innerRow -->
		</div>
	</div> <!-- row -->
