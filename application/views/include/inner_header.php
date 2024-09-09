<?php
$users_data = $this->session->userdata('auth_users'); 
$company_data = $this->session->userdata('company_data');

if($users_data['parent_id']=='75')
{
   redirect(base_url('/msc')); 
}

if($users_data['parent_id']=='174')
{ 
  redirect('https://hospitalms.co.in/delhi-derma/');
}
                
if($users_data['parent_id']=='55')
{
  redirect(base_url('/astha'));
}
elseif($users_data['parent_id']=='78')
{
   redirect(base_url('/msc'));
}
elseif($users_data['parent_id']=='87' || $users_data['parent_id']=='91')
{ 
     
  redirect(base_url('jeewanmala'));
}
elseif($users_data['parent_id']=='97')
{
  redirect(base_url('/marlin'));
}
elseif($users_data['parent_id']=='25')
{
  redirect(base_url('/rcl'));
}

elseif($users_data['parent_id']=='102')
{
  redirect(base_url('/kumarpharma'));
}

elseif($users_data['parent_id']=='47')
{
  redirect(base_url('/mudri'));
}
elseif($users_data['parent_id']=='115')
{
  redirect(base_url('/hemrajhospital'));
}
elseif($users_data['parent_id']=='134')
{
  redirect(base_url('/metrosda'));
}
elseif($users_data['parent_id']=='13')
{
  redirect(base_url('https://hospitalms.co.in/mouli/'));
}
elseif($users_data['parent_id']=='14')
{
  redirect(base_url('https://hospitalms.co.in/mouli/'));
}
elseif($users_data['parent_id']=='164')
{
  redirect(base_url('/gynaecology/'));
}
elseif($users_data['parent_id']=='144')
{
  redirect(base_url('https://hospitalms.co.in/ambulance/'));
}
elseif($users_data['parent_id']=='170')
{
  redirect(base_url('/eyestd'));
}
elseif($users_data['parent_id']=='179')
{
  redirect(base_url('/mtbloodbank'));
}
elseif($users_data['parent_id']=='181')
{
  redirect(base_url('/mtbloodbank'));
}





if($users_data['users_role']!=1)
{
  get_under_maintenance();
} 
$user_role= $users_data['users_role'];
$doctor_data = get_doctor($users_data['parent_id']);
if (array_key_exists("permission",$users_data))
{
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];
}
else
{
     $permission_section = array();
     $permission_action = array();
}
?>
  <!-- Dashboard start -->


<div class="software-title">
  <div><a href="<?php echo base_url('dashboard'); ?>" class="homeBtn"><i class="fa fa-home home-icon"></i></a></div>
  <div class="s-title"><?php echo $page_title; ?></div>
  <div class="text-right">[ <?php echo date('d-m-Y h:i:s A'); ?> ]</div>
</div>
<!-- <div class="row">
 <div class="col-md-12 dashboardBoxx"> -->
<section class="software-menu">
     <!-- first part -->
  <div class="navmain">
    <div class="menu">

     <ul class="ul_01">
      
        <?php
		if($user_role==4)
		{
		?>
			<li><a href='<?php echo base_url('opd'); ?>'>OPD</a>
			  <ul class="ul2"> 
			     <li class="ul2-arro"><a href='<?php echo base_url('appointment'); ?>'>Appointment List</a></li>
			     
			  </ul>
			</li>
      <!--- 7-11-2019 --->
      <li><a href='<?php echo base_url('ambulance'); ?>'>Ambulance</a>
        <ul class="ul2"> 
           <li class="ul2-arro"><a href='<?php echo base_url('ambulance/driver'); ?>'>Ambulance Driver</a></li>
           
        </ul>
      </li>
      <!--- 7-11-2019 --->
			<li><a href='<?php echo base_url('sales_medicine'); ?>'>Medicine</a>

			</li>
			<li><a href='<?php echo base_url('ipd_booking'); ?>'>IPD Booking</a>

			</li>
			<li><a href='<?php echo base_url('ot_booking'); ?>'>OT Booking</a>

			</li>

		<?php
		}
        elseif($users_data['users_role']==3)
        {
         ?>
         <li><a href='<?php echo base_url('opd'); ?>'>OPD</a>
                <ul class="ul2"> 
                   <li class="ul2-arro"><a href='<?php echo base_url('appointment'); ?>'>Appointment List</a></li>
                   <li class="ul2-arro"><a href='<?php echo base_url('opd'); ?>'>OPD Booking List</a></li>
                   
                </ul>
                  </li>


                  <!--- 7-11-2019 --->
      <li><a href='<?php echo base_url('ambulance'); ?>'>Ambulance</a>
        <ul class="ul2"> 
           <li class="ul2-arro"><a href='<?php echo base_url('ambulance/driver'); ?>'>Ambulance Driver</a></li>
           
        </ul>
      </li>
      <!--- 7-11-2019 --->
                  <li><a href='<?php echo base_url('sales_medicine'); ?>'>Medicine</a></li>
                  <li><a href='<?php echo base_url('ipd_booking'); ?>'>IPD Booking</a></li>
                  <li><a href='<?php echo base_url('ot_booking'); ?>'>OT Booking</a></li>
          
        <?php
         if($doctor_data->doctor_pay_type==1)
         {
	         ?>
	         <li><a href=<?php echo base_url('billing/doctor_commission'); ?>> Doctor Commission </a></li>

	         <li><a href="javascript:void(0)"  onclick="comission(<?php echo $users_data['parent_id']; ?>);">Share Details </a></li>
	         <?php 
         }
         ?>
         <?php
         if($doctor_data->doctor_pay_type==2)
         {
         ?>
         <li><a href="<?php echo base_url('billing'); ?>">  Doctor Payment </a></li>
          <li><a href="javascript:void(0)"> Price </a>
               <ul class="ul2">
                 <li class="ul2-arrow"><a href='<?php echo base_url("test_master"); ?>'>Test Price </a></li>
                 <li class="ul2-arrow"><a href='<?php echo base_url('test_profile'); ?>'>Profile Price
                   </a></li>
               </ul>  
          </li>
         <?php 
         }
        }
        else if($users_data['users_role']=='5')  // HR menu
        {
            $emp_id=$users_data['emp_id'];
            ?>
               
             <li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo base_url(); ?>payroll/employee/view_reporting_manager/<?php echo $emp_id?>"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Profile</a></li>
           
           <li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-list"></i> Service Request<span class="caret"></span></a>

						
			<span id="menu_2">
			    <ul class="child_ul parent_2">
						<li><a>E-mails<span class="caret"></span></a>
                          <span id="menu_2">
                            <ul class="child_ul parent_2">
                               	<li><a href="<?php echo base_url(); ?>payroll/employee/mail_reporting/<?php echo $emp_id?>";>E-Mail Application Form</a></li>
								
								<li><a href="<?php echo base_url().'payroll/employee/mail_application_list_emp_reporting/'.$emp_id;?>" id="aa1">E-Mail Application List</a></li>
                        </ul>
                        </span>
						</li>	
						<li><a>Leaves<span class="caret"></a>
                            <span id="menu_2">
                              	<ul class="child_ul parent_2">
									<li><a href="<?php echo base_url(); ?>payroll/employee/leave_application_reporting_manager/<?php echo $emp_id?>";>Leaves Application Form</a></li>
									
									<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/leave_application_list/<?php echo $emp_id?>" id="aa1">Leaves Application List</a></li>
								</ul>
							</span>
						</li>						
								

				</ul>
			</span>
			
		</li>

		
		<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-file-text-o"></i> Appraisal<span class="caret"></span></a>
		
				<span id="menu_2">
					<ul class="child_ul parent_2">
		
					<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/appraisel_form_rm/<?php echo $emp_id?>" id="aa1">Performance Review Form</a>
						
					</li>
					<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/probation_form_rm/<?php echo $emp_id?>" id="aa1">Probation Form</a>
						
					</li>							
				</ul>
				</span>
			
		</li>
		<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-envelope"></i> Recruitment<span class="caret"></span></a>
		
				<span id="menu_2">
					<ul class="child_ul parent_2">
		            <li><a href="<?php echo base_url(); ?>payroll/referral/index/<?php echo $emp_id?>";>Openings</a></li>
					<li><a href="<?php echo base_url(); ?>payroll/new_candidate_for_rm/index/<?php echo $emp_id?>" id="aa1">Interview</a>
						
					</li>
												
				</ul>
				</span>
			
		</li>
					
					
				<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-users"></i> Team<span class="caret"></span></a>
			
					<span id="menu_2">
						<ul class="child_ul parent_2">
			
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/employee_list/<?php echo $emp_id?>" id="aa1">Employee List</a>
							
						</li>
					
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/attendance_list/<?php echo $emp_id?>";>Attendance List</a></li>
					
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/roster_list/<?php echo $emp_id?>">Roster List</a></li>
					
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/leave_details/<?php echo $emp_id?>"> Leave Details</a></li>
				
			
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/appraisel_form/<?php echo $emp_id?>">Performance Review Form</a></li>
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/probation_form/<?php echo $emp_id?>">Probation Form</a></li>

					
					</ul>
					</span>
				
			</li>  
                    
            
            <?php 
            
        }
        else if($users_data['users_role']=='6') //employee menu
        {
            $emp_id=$users_data['emp_id'];
            ?>
               
              

            <li><a href="<?php echo base_url(); ?>payroll/dashboard_employee"><span class="glyphicon glyphicon-home"></span></a></li>
            
            <li><a href="<?php echo base_url(); ?>payroll/employee/view/<?php echo $emp_id?>"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Profile</a></li>
            
            <li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-list"></i> Service Request<span class="caret"></span></a>
            
                <!--<ul class="ul2">-->
                    <span id="menu_2"><ul class="child_ul parent_2">
                    <li><a href="javascript:void(0);">E-Mails </a>
                        <span id="menu_2"><ul class="child_ul parent_2">
                        <li><a href="<?php echo base_url(); ?>payroll/employee/mail/<?php echo $emp_id?>";>E-Mail Application Form</a></li>
                        
                        <li><a href="<?php echo base_url().'payroll/employee/mail_application_list_emp/'.$emp_id;?>" id="aa1">E-Mail Application List</a>
                        
                        </li>
                        </ul>
                        </span>
                    <li><a href="javascript:void(0);">Leaves </a>
                        <span id="menu_2"><ul class="child_ul parent_2">
                        
                        
                        
                        <li><a href="<?php echo base_url(); ?>payroll/employee/leave_application/<?php echo $emp_id?>";>Leaves Application Form</a></li>
                        
                        <li><a href="<?php echo base_url().'payroll/employee/leave_application_list_emp/'.$emp_id;?>" id="aa1">Leaves Application List</a>
                        
                        </li>
                        </ul>
                        </span>
                    </li>
                    
                    </li>
                				
                
                
                    </ul>
                </span>	
            </li>
            
            <li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-file-text"></i> Appraisal<span class="caret"></span></a>
            
            <span id="menu_2"><ul class="child_ul parent_2">
            <li><a href="<?php echo base_url(); ?>payroll/employee_appraisel_form/appraisel_form/<?php echo $emp_id?>";>Performance Review Form</a></li>
            <li><a href="<?php echo base_url(); ?>payroll/employee_probation_form/probation_form/<?php echo $emp_id?>";>Probation Form</a></li> 
            
            
            </ul>
            </span>
            
            </li>
            <li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-user"></i> Openings<span class="caret"></span></a>
            
            <span id="menu_2"><ul class="child_ul parent_2">
            <li><a href="<?php echo base_url(); ?>payroll/referral/index/<?php echo $emp_id?>";>Referral</a></li>
            
            
            </ul>
            </span>
            </li>
        
            <?php 
            
        }
        else if($users_data['users_role']=='7')  //RM for payrol
        {
            $emp_id=$users_data['emp_id'];
            ?>
               
             

			<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager"><span class="glyphicon glyphicon-home"></span></a></li>
            <li><a href="<?php echo base_url(); ?>payroll/employee/view_reporting_manager/<?php echo $emp_id?>"><span class="glyphicon glyphicon-th-list"></span>&nbsp;Profile</a></li>
           
           <li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-list"></i> Service Request<span class="caret"></span></a>

						
			<span id="menu_2">
			    <ul class="child_ul parent_2">
						<li><a>E-mails<span class="caret"></span></a>
                          <span id="menu_2">
                            <ul class="child_ul parent_2">
                               	<li><a href="<?php echo base_url(); ?>payroll/employee/mail_reporting/<?php echo $emp_id?>";>E-Mail Application Form</a></li>
								
								<li><a href="<?php echo base_url().'payroll/employee/mail_application_list_emp_reporting/'.$emp_id;?>" id="aa1">E-Mail Application List</a></li>
                        </ul>
                        </span>
						</li>	
						<li><a>Leaves<span class="caret"></a>
                            <span id="menu_2">
                              	<ul class="child_ul parent_2">
									<li><a href="<?php echo base_url(); ?>payroll/employee/leave_application_reporting_manager/<?php echo $emp_id?>";>Leaves Application Form</a></li>
									
									<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/leave_application_list/<?php echo $emp_id?>" id="aa1">Leaves Application List</a></li>
								</ul>
							</span>
						</li>						
								

				</ul>
			</span>
			
		</li>

		
		<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-file-text-o"></i> Appraisal<span class="caret"></span></a>
		
				<span id="menu_2">
					<ul class="child_ul parent_2">
		
					<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/appraisel_form_rm/<?php echo $emp_id?>" id="aa1">Performance Review Form</a>
						
					</li>
					<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/probation_form_rm/<?php echo $emp_id?>" id="aa1">Probation Form</a>
						
					</li>							
				</ul>
				</span>
			
		</li>
		<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-envelope"></i> Recruitment<span class="caret"></span></a>
		
				<span id="menu_2">
					<ul class="child_ul parent_2">
		            <li><a href="<?php echo base_url(); ?>payroll/referral/index/<?php echo $emp_id?>";>Openings</a></li>
					<li><a href="<?php echo base_url(); ?>payroll/new_candidate_for_rm/index/<?php echo $emp_id?>" id="aa1">Interview</a>
						
					</li>
												
				</ul>
				</span>
			
		</li>
					
					
				<li><a href="javascript:void(0);" onclick="ok(1);" id="ok1"><i class="fa fa-users"></i> Team<span class="caret"></span></a>
			
					<span id="menu_2">
						<ul class="child_ul parent_2">
			
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/employee_list/<?php echo $emp_id?>" id="aa1">Employee List</a>
							
						</li>
					
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/attendance_list/<?php echo $emp_id?>";>Attendance List</a></li>
					
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/roster_list/<?php echo $emp_id?>">Roster List</a></li>
					
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/leave_details/<?php echo $emp_id?>"> Leave Details</a></li>
				
			
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/appraisel_form/<?php echo $emp_id?>">Performance Review Form</a></li>
						<li><a href="<?php echo base_url(); ?>payroll/dashboard_reporting_manager/probation_form/<?php echo $emp_id?>">Probation Form</a></li>

					
					</ul>
					</span>
				
			</li>
		

	
            
            <?php 
            
        }
        else
        { 
        
	       $menu_list = get_sub_menu('0');
        // print_r($menu_list);die;
	       if(!empty($menu_list))
			    {
			      foreach($menu_list as $menu)
			      {
			        $url_1 = 'javascript:void(0);';
			        if(!empty($menu->url))
			        {
			          $url_1= base_url('/').$menu->url;
			        }
			        $pop_up_id = '';
			        if(!empty($menu->pop_up_id))
			        {
			          //$pop_up_id= $menu->pop_up_id;
                $pop_up_id= 'onclick="return '.$menu->pop_up_id.'()"';
			        }
			        
			        $menu_permission_1 = explode('|', $menu->section_id);
			        $active_child_li_1 = array_intersect(array_values($permission_section),$menu_permission_1);
			        $total_active_menu_1=1;
			        if(count($active_child_li_1)!='0')
			        {
			          $total_active_menu_1  = count($active_child_li_1);  
			        }
			        if(count(array_intersect(array_values($permission_section),$menu_permission_1)) == $total_active_menu_1)
			        {

			  		?>
			        <li><a <?php echo $pop_up_id; ?> href="<?php echo $url_1; ?>" <?php  if(empty($pop_up_id) && $url_1=='javascript:void(0);') { ?> onmouseover="sub_menu(<?php echo $menu->id; ?>)" <?php  } ?>><?php echo $menu->name;?></a>
			         <span id="menu_<?php echo $menu->id; ?>"></span>
			        </li>
			   <?php

			 }

			}

			} 
		}
        ?>
    </ul>
    </div><!-- menu close -->
    </div> <!-- navmain1 -->
	<!-- second part -->
   <!--  <div class="navmain">
      <div class="dash"><?php // echo $page_title; ?></div>
   </div>  -->
	<!-- third part -->
    <div class="navmain">
        <div class="allframe">
           <div class="allframe1">
              <!-- <span class="alltime"><?php echo date('d-m-Y h:i:s A'); ?></span> -->
              <div class="allHoverbox">
                 <div class="all-1">
                    <img src="<?php echo base_url('assets/images/photo.png'); ?>" width="20" height="20" class="user_icon">
                    <span class="uname"><?php print_r($users_data['username']); ?></span>
                    <i class="fa fa-caret-down"></i>
                 </div>

                 <div class="allbox">
                    <img src="<?php echo base_url('assets/images/photo.png'); ?>" class="img-responsive">
                    <div class="profile_name"><?php print_r($users_data['username']); ?></div>
                    <ul class="allul">
           <?php if($users_data['users_role']=='2' || in_array('150',$permission_section) || in_array('218',$permission_section))
           {
           ?>
            <?php if($users_data['users_role']=='2'){?>  
                    <li onclick="$('.allul-child').slideToggle();"><i class="fa fa-caret-right"></i><a >Settings</a>  
          			<ul class="allul-child">
                    
            			<li><a href="<?php echo base_url('company_settings'); ?>"><i class="fa fa-angle-right"></i> Company Setting</a></li>
                         <li><a href="javascript:void(0)" id="default_search_setting"><i class="fa fa-angle-right"></i> Default Search Setting</a></li>
                          <?php if(in_array('85',$permission_section) || in_array('121',$permission_section)) { ?>
                         <li><a href="javascript:void(0)" id="opd_print_setting"><i class="fa fa-angle-right"></i> OPD Print Setting</a></li>
                      <?php 
                          }
                      if(in_array('150',$permission_section)){ 
                      ?> 
                       <li><a href="<?php echo base_url("website_setting");?>"><i class="fa fa-angle-right"></i> Config Setting</a></li> 
                      <?php
                      }
                      
                       if(in_array('150',$permission_section)){ 
                      ?> 
                       <li><a href="<?php echo base_url("address_print_setting");?>"><i class="fa fa-angle-right"></i> Address Print Setting</a></li> 
                       
                       <li><a href="<?php echo base_url("time_print_setting");?>"><i class="fa fa-angle-right"></i> Print Time Setting</a></li> 
                       
                      <?php
                      }
                      
                        if(in_array('218',$permission_section)) {
                          ?>  
            			<li><a href="<?php echo base_url("receipt_no_setting"); ?>"><i class="fa fa-angle-right"></i> Receipt No. Setting</a></li>
                          <?php
                           } if(in_array('85',$permission_section) || in_array('121',$permission_section)) {
                          ?> 
                           <li><a href="<?php echo base_url("mlc_no_setting"); ?>"><i class="fa fa-angle-right"></i> MLC No. Setting</a></li>
                          
                           <li><a href="<?php echo base_url("default_doc_setting"); ?>"><i class="fa fa-angle-right"></i> OPD Default Doctor</a></li>
                           
                           <?php
                           }
                         if(in_array('145',$permission_section)) {?>  
                             <li><a href="<?php echo base_url("default_path_doc_setting"); ?>"><i class="fa fa-angle-right"></i>Pathology Default Doctor</a></li>
                        <?php } if(in_array('165',$permission_section) || in_array('60',$permission_section)) { ?>
                        <li><a href="javascript:void(0)" id="inventory_discount_setting"><i class="fa fa-angle-right"></i> Discount Setting</a></li>
                          <?php }
                          if(in_array('60',$permission_section)) { ?>
                        <li><a href="javascript:void(0)" id="medicine_gst_setting"><i class="fa fa-angle-right"></i> Medicine Sale GST Setting</a></li>
                          <?php }
                          if(in_array('60',$permission_section)) { ?>
                        <li><a href="javascript:void(0)" id="medicine_order_setting"><i class="fa fa-angle-right"></i> Medicine Sale Order Setting</a></li>
                          <?php } ?>
                          
                          <li><a href="javascript:void(0)" id="commission_setting"><i class="fa fa-angle-right"></i> Commission Setting</a></li> 
                          
          			</ul>
                </li>
               <?php  } ?> 
	               <li><i class="fa fa-caret-right"></i><a href="javascript:void(0)" id="change_password">Change Password</a></li>
		          	<?php if(in_array('6',$permission_section) && ($users_data['parent_id']!='165')) {
		          	echo '<li><i class="fa fa-caret-right"></i><a href="'.base_url("unique_ids").'">Unique IDs</a></li>';
				} 

        	} 

          if($user_role==1)
          {
              echo '
           <li>
              <i class="fa fa-caret-right"></i>
              <a href="javascript:void(0)" id="popup_maintenance_page">Maintenance Redirection</a>
           </li>';
           echo '<li>
              <i class="fa fa-caret-right"></i>
              <a href=" '.base_url("admin_add_features").'" id="popup_add_features">Add Features</a>
           </li>';
           
            echo '<li>
              <i class="fa fa-caret-right"></i>
              <a href=" '.base_url("banner").'" id="popup_add_features">Add Banner</a>
           </li>';
           
           echo '<li>
              <i class="fa fa-caret-right"></i>
              <a href=" '.base_url("renewal_mail").'" id="renewal_mail">Renewal Mail</a>
           </li>';
           
          }
                       ?>
                       <li>
                          <i class="fa fa-power-off"></i> <a href="<?php echo base_url().'login/logout';?>">Logout</a>
                       </li>
                    </ul>
                 </div>
              </div> <!-- allhoverbox -->
           </div> <!-- allframe1 -->
        </div> <!-- allframe -->

    </div> <!-- navmain3 -->

</section>


<!--   </div>
</div> -->
<!-- Dashboard close -->
<div id="flash_msg"  class="booked_session_flash">
     <i class="fa fa-check-circle"></i>
     <span id="flash_msg_text"></span>
</div>
<div id="flash_msg_n"  class="booked_session_flash_red">
     <i class="fa fa-check-circle"></i>
     <span id="flash_msg_text_n"></span>
</div>






<script>
function sub_menu(id)
{
  
  $.ajax({url: "<?php echo base_url(); ?>general/branch_menu/"+id, 
      success: function(result)
      { 
        $('#menu_'+id).html(result);  
      } 
    }); 
}

function collection_add(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'reports/collections/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function authorize_persone_reports_add(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'discount_authrize_report/authorize_persone_reports/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function doctor_commission_collection_reports_add(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'doctor_commission_collection_report/doctor_comm_coll_report/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function send_login_credential(id)
{
  var $modal = $('#load_send_login_credential_modal_popup');
  $modal.load('<?php echo base_url().'send_login/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function get_medicine_quantity_report(id)
{
  var $modal = $('#load_add_medicine_quantity_report_modal_popup');
  $modal.load('<?php echo base_url().'medicinequantityreport/medicine_quantity_report/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function medicine_profit_loss(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'medicine_profit_loss/medicine_report/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function test_report_verify()
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'test_report_verify/index/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function expense_add(id)
{
  var $modal1 = $('#load_add_expense_collection_modal_popup');
  $modal1.load('<?php echo base_url().'reports/expenses/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal1.modal('show');
  });
}

function doctor_opd_summary_report(id)
{
  var $modal1 = $('#load_add_expense_collection_modal_popup');
  $modal1.load('<?php echo base_url().'doctor_opd_summary_report/reports/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal1.modal('show');
  });
}

function enquiry_source_add(id)
{ 
  var $modal2 = $('#load_add_enquiry_source_modal_popup');
  $modal2.load('<?php echo base_url().'reports/enquiry_source/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal2.modal('show');
  });
}

function banking_report(id)
{
  var $modal11 = $('#load_add_banking_report_modal_popup');
  $modal11.load('<?php echo base_url().'banking_report/banking/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal11.modal('show');
  });
}

function next_appoitment(id)
{
  var $modal_next = $('#load_add_next_appoitment_report_modal_popup');
  $modal_next.load('<?php echo base_url().'reports/next_appoitment/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_next.modal('show');
  });
}

function referral_report(id)
{
  var $modal_ref = $('#load_add_referral_report_modal_popup');
  $modal_ref.load('<?php echo base_url().'referral_reports/reports/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_ref.modal('show');
  });
}

function inventory_report_add(id)
{
  var $modal_inventory = $('#load_inventory_purchamodal_popup');
  $modal_inventory.load('<?php echo base_url().'inventory_report/inventory_purchase/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_inventory.modal('show');
  });
}

function inventory_report_advance(id)
{
  var $modal_inventory = $('#load_inventory_purchamodal_popup');
  $modal_inventory.load('<?php echo base_url().'inventory_report/inventory_allotment_advance_report/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_inventory.modal('show');
  });
}
function inventory_dialysis_report(id)
{
  var $modal_inventory = $('#load_inventory_purchamodal_popup');
  $modal_inventory.load('<?php echo base_url().'inventory_report/inventory_dialysis_report/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_inventory.modal('show');
  });
}
function inventory_report_purchase_return_add(id)
{
  var $modal_inventory_purchase_return = $('#load_inventory_purchase_return_modal_popup');
  $modal_inventory_purchase_return.load('<?php echo base_url().'inventory_report/inventory_purchase_return/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_inventory_purchase_return.modal('show');
  });
}

function inventory_report_allotment_add(id)
{
  var $modal_allotment = $('#load_inventory_allotment_modal_popup');
  $modal_allotment.load('<?php echo base_url().'inventory_report/inventory_allotment/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_allotment.modal('show');
  });
}

function inventory_report_allotment_return_add(id)
{
  var $modal_allotment_return = $('#load_inventory_allotment_return_modal_popup');
  $modal_allotment_return.load('<?php echo base_url().'inventory_report/inventory_allotment_return/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_allotment_return.modal('show');
  });
}

function inventory_garbage_add(id)
{
  var $modal_garbage = $('#load_inventory_garbage_list_modal_popup');
   $modal_garbage.load('<?php echo base_url().'inventory_report/garbage/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_garbage.modal('show');
  });
}

function stock_item_add(id)
{
  var $modal_stock_item = $('#load_stock_item_modal_popup');
  $modal_stock_item.load('<?php echo base_url().'inventory_report/stock_item/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_stock_item.modal('show');
  });
}

function stock_item_add(id)
{
  var $modal_stock_item = $('#load_stock_item_modal_popup');
  $modal_stock_item.load('<?php echo base_url().'inventory_report/stock_item/'?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_stock_item.modal('show');
  });
}

function gst_report_add(id)
{
  var $modal_gst = $('#load_gst_modal_popup');
  $modal_gst.load('<?php echo base_url().'medicine_gst/gst/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_gst.modal('show');
  });
}

/*function change_password(id)
{
  var $modal = $('#load_change_password_popup');
  $modal.load('< ?php echo base_url("change-password") ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}*/

$(document).ready(function(){
  var $modal = $('#load_change_password_popup');
  
    $('#commission_setting').on('click', function(){
    $modal.load('<?php echo base_url("commission_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

  $('#change_password').on('click', function(){
    $modal.load('<?php echo base_url("change-password") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

});

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

$(document).ready(function(){
  var $modal = $('#load_change_password_popup');
  $('#default_search_setting').on('click', function(){
    $modal.load('<?php echo base_url("default_search_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

});  

$(document).ready(function(){
  var $modal = $('#load_change_password_popup');
  $('#inventory_discount_setting').on('click', function(){
    $modal.load('<?php echo base_url("inventory_discount_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

  $('#medicine_gst_setting').on('click', function(){
    $modal.load('<?php echo base_url("medicine_gst_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});



  var $modal = $('#load_change_password_popup');
  $('#medicine_order_setting').on('click', function(){
    $modal.load('<?php echo base_url("medicine_order_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

});


$(document).ready(function(){
  var $modal = $('#load_change_password_popup');
  $('#opd_print_setting').on('click', function(){
    $modal.load('<?php echo base_url("opd_setting") ?>',
    {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
   $modal.modal('show');
  });

});

}); 

$(document).ready(function()
{
var $modal10 = $('#load_add_medicine_kit_modal_popup');
$('#medicine_kit_branch').on('click', function(){ 
     $modal10.load('<?php echo base_url().'packages/medicine_kit_to_branch/' ?>',{/*'id1': '1',//'id2': //'2'*/},
     function(){
          $modal10.modal('show');
     });

});

var $popup_maintenance_page = $('#load_popup_maintenance_page');
$('#popup_maintenance_page').on('click', function(){ 
     $popup_maintenance_page.load('<?php echo base_url().'under_maintenance_page/open_popup_page/'?>',{/*'id1': '1',//'id2': //'2'*/},
     function(){
          $popup_maintenance_page.modal('show');
     });

});

var $modal2 = $('#load_add_collection_modal_popup');
   $('#medicine_collection_add').on('click', function(){ 
     $modal2.load('<?php echo base_url().'reports/medicine_collections/' ?>',{/*'id1': '1',//'id2': //'2'*/},
     function(){ 
          $modal2.modal('show');
     });

});

});

function print_window_page(url)
{
  //location.href = url;
  //alert(url);
  var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
  printWindow.addEventListener('load', function(){
  printWindow.print();
  //printWindow.close();
  }, true);

  
} 
  </script>
  <script type="text/javascript">
function comission(ids)
{ 
  var $modal = $('#load_add_comission_modal_popup');
  $modal.load('<?php echo base_url().'doctors/view_comission/' ?>',
  {
    //'id1': '1',
    'id': ids
    },
  function(){
  $modal.modal('show');
  });
} 
function rate_plan_view(ids)
{ 
  var $modal = $('#load_add_comission_modal_popup');
  $modal.load('<?php echo base_url().'rate/view/' ?>'+ids,
  {
    //'id1': '1',
    'id': ids
    },
  function(){
  $modal.modal('show');
  });
}

function vaccine_billing_report(id)
{
  var $modal = $('#load_add_vaccine_report_modal_popup');
  $modal.load('<?php echo base_url().'vaccine_billing_report/vaccine_bill_report/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


function collection_add_day(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'reports/collections_day/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

// ambulance refferel report
function ambulance_refferal_report(id)
{
  var $modal_ref = $('#load_add_referral_report_modal_popup');
  $modal_ref.load('<?php echo base_url().'ambulance/ambulance_referral_reports/reports/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal_ref.modal('show');
  });
}

// document refferel report
function vehicle_document_report(id)
{
  var $modal_ref = $('#load_add_referral_report_modal_popup');
  $modal_ref.load('<?php echo base_url().'ambulance/amb_document_report/reports/' ?>',
  {
  },
  function(){
  $modal_ref.modal('show');
  });
}


// document refferel report
function ambulance_collection_report(id)
{
  var $modal_ref = $('#load_add_referral_report_modal_popup');
  $modal_ref.load('<?php echo base_url().'ambulance/amb_collection_report/reports/' ?>',
  {
  },
  function(){
  $modal_ref.modal('show');
  });
}

function ledger_report(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'reports/ledger_report/' ?>',
  {
  },
  function(){
  $modal.modal('show');
  });
}

// collection with time
function collection_with_time_report(id)
{
  var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'reports/collections_with_time/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


// doctor wise collection
function doctor_collection_report(id)
{
 var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'reports/doctor_wise_collection_report/' ?>',
  {
    },
  function(){
  $modal.modal('show');
  });
}

// module wise profit and loss
function modul_profit_loss_report(id)
{
 var $modal = $('#load_add_collection_modal_popup');
  $modal.load('<?php echo base_url().'reports/modul_profit_loss_report/' ?>',
  {
    },
  function(){
  $modal.modal('show');
  });
}
  </script>