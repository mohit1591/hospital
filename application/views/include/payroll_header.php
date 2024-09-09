
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>new_menu.js"></script> 
<!-- ||===\\//\\//===|| -->

<?php
$users_data = $this->session->userdata('auth_users'); 
$user_role= $users_data['users_role']; 
//echo "<pre>";print_r($users_data); exit;

$emp_id=$users_data['emp_id'];
if (array_key_exists("permission",$users_data)){
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];

}

else{
     $permission_section = array();
     $permission_action = array();
    
   }

?>

<div class="container-fluid bg-payroll payroll_header">
	<section class="row">
		<div class="col-md-12">
			
			<div class="topHeader">
                <div class="header_div">
                <?php
                $company_data = $this->session->userdata('company_data');
                if(isset($company_data))
                { 
                ?>
                    <div class="title"><?php echo $company_data['branch_name']; ?></div>
                    <div class="header_txt"><?php echo $company_data['address']; ?></div>
                    <div class="header_txt">Phone:- <?php echo $company_data['contact_no']; ?></div>
                    <div class="header_txt">Email: <span class="header_email"><?php echo $company_data['email']; ?></span></div>
                <?php  }
                else
                { ?>
                    <div class="title">Sara Technologies Pvt. Ltd.</div>
                    <div class="header_txt">A-40, Tower B, 2nd floor(208), <br> i-Thum, Sector-62, Noida 201301</div>
                    <div class="header_txt">Phone: +91-8506080374</div>
                    <div class="header_txt"><span class="header_email">support@sarasolutions.in</span></div>
                <?php }    ?>    
                </div> <!-- header_div_left -->

                

                <div class="header_div text-right">
                    <div class="title">Sara Technologies Pvt. Ltd.</div>
                    <div class="header_txt">A-40, Tower B, 2nd floor(208), <br> i-Thum, Sector-62, Noida 201301</div>
                    <div class="header_txt">Phone: +91-8506080374</div>
                    <div class="header_txt"><span class="header_email">support@sarasolutions.in</span></div>
                    <div class="header_txt"><span class="header_email">www.sarasolutions.in</span></div>
                </div> <!-- header_div_right -->
            </div>

		</div>
	</section>
</div>

<!-- ///////////////////////////[ NAVIGATION ]////////////////////////////// -->
<div class="container-fluid payroll_dashboard_menu">
	<div class="row">
		<div class="col-md-4">
			
			<button class="payroll_dash_menu_button" onclick="$('.payroll_right_side_menubar').animate({width:'toggle'})"><i class="fa fa-list"></i></button>
			
		</div>
		<div class="col-md-4">
			<div class="payroll_dash_title">
				<strong><?php echo $page_title;?></strong>
			</div>
		</div>
		<div class="col-md-4">
			<div class="payroll_right_side_menubar">
				<div class="prsm">
					<div class="exitbtn" onclick="$('.payroll_right_side_menubar').animate({width:'toggle'});">Exit <i class="fa fa-sign-out"></i></div>
					<ul class="ul1">
						<li><a href=""><i class="fa fa-caret-right"></i> Home</a></li>
						<?php if(in_array('2',$permission_section))
                                   { ?>
						<li><a href="<?php echo base_url().'payroll/employee';?>"><i class="fa fa-caret-right"></i> Employee Management</a></li>
						<?php }  if(in_array('172',$permission_section))
                                   { ?>
						<li><a href="#"  onclick="$('.sw').slideToggle();"><i class="fa fa-caret-right"></i> Roster Management</a>
    						<div class="down sw">
    						  <ul class="ul2">
    						      <li><a href="<?php echo base_url().'payroll/roster_management';?>"><i class="fa fa-caret-right"></i> Roster Management</a></li>
    						      <li><a href="<?php echo base_url().'payroll/swap_shift';?>"><i class="fa fa-caret-right"></i> Swap Management</a></li>
    						  </ul>
    						  </div>
						</li>
						<?php } if(in_array('173',$permission_section))
                                   { ?>
						<li><a href="<?php echo base_url().'payroll/attendance';?>"><i class="fa fa-caret-right"></i> Attendance Management</a></li>
						<?php } if(in_array('174',$permission_section))
                                   { ?>
						<li><a href="#" onclick="$('.lm').slideToggle();"><i class="fa fa-caret-right"></i> Salary Management</a>
    						<div class="down lm">
    						  <ul class="ul2">
    						      <li><a href="<?php echo base_url().'payroll/salary_details';?>"><i class="fa fa-caret-right"></i> Salary Details</a></li>
    						      <li><a href="<?php echo base_url().'payroll/loan';?>"><i class="fa fa-caret-right"></i> Loan Management</a></li>
    						  </ul>
    						  </div>
						</li>
						<?php } ?>
						
						
							<li><a href="javascript:void(0);" onclick="$('.hrd').slideToggle();" id="ok1"><i class="fa fa-caret-right"></i> HRD <span class="caret"></span></a>
							<div class="down hrd">
								<ul class="ul2">
								<?php  if(in_array('176',$permission_section)) { ?>
									<li><a href="<?php echo base_url().'payroll/holiday_list';?>">Holiday List</a></li>
									<?php } if(in_array('177',$permission_section)) { ?>
									<li><a href="<?php echo base_url().'payroll/hrd';?>">HR Documents</a></li>
									<?php } ?>
									<li><a href="<?php echo base_url().'payroll/hr_employee_document';?>">HR Employee Documents</a></li>
									
								</ul>
							</div>
						</li>
								<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-caret-right"></i> Utilities <span class="caret"></span></a>
							<div class="down ok1">
								<ul class="ul2">
									<li><a href="javascript:void(0)" onclick="bb(1);" id="bb1">Email<span class="caret"></span></a></li>
									<?php //if(in_array('45',$permission_action)) { ?>
										<div class="down bb1">
											<a href="<?php echo base_url().'payroll/email_template';?>">Email template</a>
										</div>
										<?php //} if(in_array('160',$permission_action)) { ?>
										<div class="down bb1">
											<a href="<?php echo base_url().'payroll/email_settings';?>">Email Config</a>
										</div>
										<?php //} ?>
									
									<li><a href="javascript:void(0)" onclick="bb(2);" id="bb2">SMS<span class="caret"></span></a></li>
									<?php //if(in_array('160',$permission_action)) { ?>
									<div class="down bb2">
											<a href="<?php echo base_url().'payroll/sms_template';?>">SMS Template</a>
										</div>
										<?php //} if(in_array('103',$permission_action)) { ?>
										<div class="down bb2">
											<a href="<?php echo base_url().'payroll/sms_config';?>">SMS Config</a>
										</div>
										<?php //} ?>
									<li><a href="javascript:void(0)" onclick="aa(1);" id="aa1">Field Master<span class="caret"></span></a></li>
									<div class="down aa1">
											<a href="<?php echo base_url().'payroll/qualification';?>">Qualification</a>
										</div>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/relation';?>">Relation</a>
										</div>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/source';?>">Source</a>
										</div>
									<?php if(in_array('7',$permission_section)) { ?>
									<div class="down aa1">
											<a href="<?php echo base_url().'payroll/designation';?>">Designation</a>
										</div>
										<?php } if(in_array('12',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/simulation';?>">Simulation</a>
										</div>
										<?php } if(in_array('162',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/department';?>">Department</a>
										</div>
										<?php } if(in_array('163',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/income_head';?>">Income Head</a>
										</div>
										<?php } if(in_array('7',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/employee_type';?>">Employee type</a>
										</div>
										<?php } ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/mandatory_field_manage/mandatory_fields';?>">Mandatory fields Manager</a>
										</div>
										<?php if(in_array('168',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/shift_master';?>">Shift Master</a>
										</div>
										<?php } if(in_array('166',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/document_checklist';?>">Document Check List</a>
										</div>
										<?php }if(in_array('169',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/leave_master';?>">Leave Master</a>
										</div>
										<?php } if(in_array('170',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/deduction_head';?>">Deduction Head</a>.
										</div>
										<?php } if(in_array('179',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/leave_type';?>">Leave Types</a>
										</div>
										<?php } if(in_array('180',$permission_section)) { ?>
										<div class="down aa1">
											<a href="<?php echo base_url().'payroll/blood_group';?>">Blood Group</a>
										</div>
										<?php } if(in_array('5',$permission_section)) { ?>
										<li><a href="<?php echo base_url().'users';?>">Users</a></li>
										<?php } if(in_array('1',$permission_section)) { ?>
					<li><a href="<?php echo base_url(); ?>branch">Branch</a></li>
					<?php } ?>

										


								</ul>
							</div>
						</li>

						
					</ul>
				</div>
			</div>

			<!-- settings btn -->
			<div class="payroll_setting_btn">
	    		<a href="javascript:void(0)" class=""><i class="fa fa-cog"></i><span class="caret"></span></a>
	    		<div class="payroll_settings_box">
	    			<ul>
						<li class="settings_li"><a href="javascript:void(0)">
							<center class="setting_user">
								<i class="fa fa-user-circle fa-2x"></i>
								<div class="setting_user_name"> <?php echo $users_data['username']; ?></div>
							</center></a>
						</li>
	              
						<li><a href="<?php echo base_url().'/company_settings';?>"><i class="glyphicon glyphicon-lock"></i> Company Settings</a></li>
				
						<li><a id="change_password" href="javascript:void(0);"><i class="glyphicon glyphicon-lock"></i> Change Password</a></li> 

						<?php if(in_array('6',$permission_section)) { ?>
						<li><a href="<?php echo base_url().'/unique_ids';?>"><i class="glyphicon glyphicon-lock"></i> Unique IDs</a></li>
						<?php } ?>
						
						<li><a href="<?php echo base_url().'login/logout';?>"><i class="glyphicon glyphicon-log-out"></i> Logout</a> </li>
					</ul>
	    		</div>
	    	</div>
	    	<div class="payroll_setting_btn">
	    		<!-- <a href="javascript:void(0)" class=""><i class="fa fa-bell"></i></a> -->
	    		<div class="payroll_settings_box">
	    		<ul>

	    		 </ul>
	    		
	    		</div>
	    	</div>

			<!-- =============== -->
		</div>
	</div>
</div>


<div class="container-fluid payroll_mainmenu">
	<section class="row">
		<div class="col-md-12">
			<ul class="payroll_menu">	

			<li><a href="<?php echo base_url(); ?>dashboard"><span class="glyphicon glyphicon-home"></span></a></li>
			
			<?php  if(in_array('2',$permission_section)) { ?>
			<li><a href="<?php echo base_url(); ?>payroll/employee">Employee Management</a></li>
			<li><a href="<?php echo base_url(); ?>payroll/roster_management">Roster Management</a>
              <ul class="ul2">
                   <li><a href="<?php echo base_url().'swap_shift';?>"><i class="fa fa-caret-right"></i> Swap shift</a></li>
               </ul>
			</li>
			<li><a href="<?php echo base_url(); ?>payroll/attendance">Attendance Management</a></li>
			<li><a href="<?php echo base_url(); ?>payroll/salary_details">Salary Management</a>
               <ul class="ul2">
                   <li><a href="<?php echo base_url().'loan';?>"><i class="fa fa-caret-right"></i> Loan And Advance</a></li>
               </ul>
			</li>
			<?php
			if(!empty($user_role))
			{
				if($user_role==3  || $users_data['parent_id']=='1')
				{
			?>
			<li class="drop_nav2"><a href="javascript:void(0);">E-Mails<span class="caret"></span></a>
				<ul>
					
					<li><a href="<?php echo base_url().'payroll/employee/leave_application_list';?>">Leaves Application Details</a></li>
					<li><a href="<?php echo base_url()?>payroll/dashboard/mail_details/<?php echo $emp_id?>">E-mail Details</a></li>
					
				</ul>

			</li>
			<?php }} ?>
			<li class="drop_nav2"><a href="javascript:void(0);">HRD  <span class="caret"></span></a>
				<ul>
					<li><a href="<?php echo base_url(); ?>payroll/birthday_aniversary">Birthdays & Anniversary</a></li>
					<li><a href="<?php echo base_url(); ?>payroll/holiday_list">Holiday List</a></li>
					<li><a href="<?php echo base_url(); ?>payroll/hrd">HR Documents</a></li>
					<li><a href="<?php echo base_url(); ?>payroll/hr_employee_document">HR Employees Documents</a></li>
				<!-- 	<li><a href="<?php echo base_url(); ?>employee_appraisel_form">Employee Appraisel Form</a></li> -->
				<li class="drop_nav2"><a href="javascript:void(0);">Appraisal<span class="caret"></span></a>
						<ul>
						
							<li><a href="<?php echo base_url(); ?>payroll/employee_appraisel_form">Performance Review Form</a></li>	
							<li><a href="<?php echo base_url(); ?>payroll/employee_probation_form">Probation Form</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/employee_appraisel_salary">Appraisal Salary</a></li>
							
					
							
						</ul>
					</li>
					<li class="drop_nav2"><a href="javascript:void(0);">Recruitment<span class="caret"></span></a>
						<ul>
						
							<li><a href="<?php echo base_url(); ?>payroll/recruitment">Open Position</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/new_candidate"> Candidate</a></li>
						<li><a href="<?php echo base_url().'payroll/applied_jobs/index';?>">Applied Jobs</a></li>
							
					
							
						</ul>
					</li>
				</ul>

			</li>
			<?php if($user_role==1 || $user_role==2 || $user_role==3) { ?>
			<li class="drop_nav"><a href="javascript:void(0);">Utilities <span class="caret"></span></a>
				<ul>
					<li class="drop_nav2"><a href="javascript:void(0);">Email  <span class="caret"></span></a>
						<ul>
							<?php  if(in_array('45',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>email_template">Email Template</a></li>
						
						 
							<?php } if(in_array('160',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>email_settings">Email Config</a></li>
							<?php } ?>
						</ul>
					</li> 
					<li class="drop_nav2"><a href="javascript:void(0);">SMS  <span class="caret"></span></a>
						<ul>
							<?php  if(in_array('45',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>sms_template">SMS Template</a></li>
							
						 
							<?php  } if(in_array('160',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>sms_config">SMS Config</a></li>
							<?php  } ?>
						</ul>
					</li> 
					<li class="drop_nav2"><a href="javascript:void(0);">Field Master  <span class="caret"></span></a>
						<ul class="health_pack">
							<li><a href="<?php echo base_url(); ?>payroll/qualification">Qualification</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/relation">Relation</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/source">Source</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/interview_type">Interview Type</a></li>
							<?php  if(in_array('12',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/simulation">Simulation</a></li>
							<?php } if(in_array('162',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/department">Department</a></li>
							<?php } ?>
							<?php if(in_array('7',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/designation">Designation</a></li>
							<?php } ?>
							
							<?php if(in_array('163',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/income_head">Income Head</a></li>
							<?php } ?>
							<?php if(in_array('170',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/deduction_head">Deduction Head</a></li>
							<?php } ?>
							

							<li><a href="<?php echo base_url(); ?>payroll/mandatory_field_manage/mandatory_fields">Mandatory Fields Manager</a></li>
							<?php if(in_array('168',$permission_section)) { ?>
                            <li><a href="<?php echo base_url(); ?>payroll/shift_master">Shift Master</a></li>
                            <?php } ?>
							<!-- <li><a href="<?php echo base_url(); ?>employee_code">Employee Code</a></li> -->
							<?php if(in_array('7',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/employee_type">Employee Type</a></li>
							<?php } ?>
							<?php if(in_array('166',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/document_checklist">Document Checklist</a></li>
							<?php } ?>
							<?php if(in_array('169',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/leave_master">Leave Master</a></li>
							<?php } ?>
							
							<?php if(in_array('171',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/holiday_master">Holiday Master</a></li>
							<?php } ?>
							<li><a href="<?php echo base_url(); ?>payroll/leave_type">Leaves Types</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/blood_group">Blood Group</a></li>
							<li><a href="<?php echo base_url(); ?>payroll/company_working_setting">Company Working Setting</a></li>
							
							
						</ul>
					</li>
					

					<?php } if(in_array('5',$permission_section)) { ?>
					<li><a href="<?php echo base_url(); ?>users">Users</a></li>
					<?php }  if(in_array('1',$permission_section)) { ?>
					<li><a href="<?php echo base_url(); ?>branch">Branch</a></li>
					<?php }  ?>
				    <li class="drop_nav2"><a href="javascript:void(0);">Templates  <span class="caret"></span></a>
						<ul>
							<?php  //if(in_array('45',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/offer_letter_print_setting">Offer Letter Print Template</a></li>
							<?php  //} if(in_array('160',$permission_section)) { ?>
							<li><a href="<?php echo base_url(); ?>payroll/salary_slip_print_setting">Salary Slip Print Template</a></li>
							<?php  //} ?>
						</ul>
					</li> 
				</ul>
			
			<?php } ?>

			
          </li>
		</ul>
		</div> <!-- 12 --> 
	</section>
</div>
<!-- ////////////////////////////////////////////////////////////////////// -->

<div id="flash_msg"  class="booked_session_flash">
     <i class="fa fa-check-circle"></i>
     <span id="flash_msg_text"></span>
</div>
<div id="flash_msg_n"  class="booked_session_flash_red">
     <i class="fa fa-check-circle"></i>
     <span id="flash_msg_text_n"></span>
</div>

<script>
	$(window).scroll(function(){
		if($(document).scrollTop()>0){
			 // $('.btns').css('paddingTop','0px');
		} else {
			 // $('.btns').css('paddingTop','170px');
		}
		
	});

		


	function search_document(branch_id)
	{
		var q= $("#search_doc").val();
		$.ajax({
                      type: "POST",
                      url: "<?php echo base_url('payroll/document/search_d0o.cument');?>",
                      data: {branch_id:branch_id},
                      success: function(result) 
                      {
                         window.location="<?php echo base_url(); ?>document?br="+branch_id+"&doc_name="+q;
                      }
                  });
	}

	function flash_session_msg(val)
	{
	    $('#flash_msg_text').html(val);
	    $('#flash_msg').slideDown('slow').delay(1500).slideUp('slow');
	}
	function error_flash_session_msg(val)
	{
	    $('#flash_msg_text_n').html(val);
	    $('#flash_msg_n').slideDown('slow').delay(1500).slideUp('slow');
	}
function print_window_page(url)
{
  var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
  printWindow.addEventListener('load', function(){
  printWindow.print();
  }, true);
  
} 

</script>