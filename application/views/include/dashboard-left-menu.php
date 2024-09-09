
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
    $user_role= $users_data['users_role'];
?>

	<!-- Dashboard start -->
	<div class="row">
		<div class="col-md-12 dashboardBox">
			<div class="row">
				<div class="col-sm-4">
					<!-- // -->
					<?php if($users_data['users_role']==1 || $users_data['users_role']==2)
					{?>
					 <button class="button-nav-toggle" onclick="$('.navContainer').animate({width:'toggle'});"><img src="<?php echo ROOT_IMAGES_PATH; ?>m_icon.png" class="img-responsiv">
					</button>
					<?php } ?>
					
					<script>

					</script>

					<div class="menu">
					<nav class="nav-main">
					<div class="navContainer">
					<ul class="sideBarMenu">
						<li><a href="<?php echo base_url('dashboard'); ?>"><i class="fa fa-home"></i> Home</a></li>
						<?php 
							$menu_list = get_sub_menu('0');
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
										$total_active_menu_1 = count($active_child_li_1);  
									}

									if(count(array_intersect(array_values($permission_section),$menu_permission_1)) == $total_active_menu_1)
									{

									?>
										
									<li   ><a <?php echo $pop_up_id; ?> href="<?php echo $url_1; ?>"   <?php  if(empty($pop_up_id) && $url_1=='javascript:void(0);') { ?> onclick="sub_menu_left(<?php echo $menu->id; ?>);" class="plus" <?php  } ?> ><?php if($menu->name=='Patients'){ ?> <i class="fa fa-heartbeat"></i> <?php }elseif($menu->name=='Doctors'){ ?> <i class="fa fa-user-md"></i> <?php }elseif($menu->name=='Medicine'){ ?> <img src="<?php echo ROOT_IMAGES_PATH; ?>medi.png"><?php }elseif($menu->name=='Billing'){ ?> <i class="fa fa-file-text"></i> <?php }elseif($menu->name=='Report'){ ?> <i class="fa fa-file-text-o"></i> <?php }elseif($menu->name=='Utilities'){ ?> <i class="fa fa-database"></i> <?php }else{ ?><i class="fa fa-h-square"></i> <?php } ?> <?php echo $menu->name;?></a>

									<span id="left_menu_<?php echo $menu->id; ?>" class="dBox"></span>
									</li>

									<?php
									} 
								}
							} 
							?>
						<li><a href="javascript:void(0);" onclick="$('.settingsBox').slideToggle();" class="plus"><i class="fa fa-cog"></i> Settings</a></li>
							<div class="dBox settingsBox">
								<ul class="d_ul">
									
									<?php 
									if(in_array('101',$permission_section)||
	                                              in_array('102',$permission_section)||
	                                              in_array('103',$permission_section))
	                                            {
									?>
									<li><a href="javascript:void(0)" onclick="$('.emailSettings').slideToggle();" class="plus"> Email</a></li>
									<div class="dBox emailSettings">
										<ul class="d_ul">
											<li><a href="<?php echo base_url('email_template'); ?>"> Email Template</a></li>
											<li><a href="<?php echo base_url('email_settings'); ?>"> Email Config</a></li>
											<li><a href="<?php echo base_url('email_setting'); ?>"> Email Setting</a></li>
										</ul>
									</div>
									<?php 
									}
									if(in_array('101',$permission_section)||
	                                              in_array('102',$permission_section)||
	                                              in_array('103',$permission_section))
	                                            {
	                                     ?>
									<li> <a href="#" onclick="$('.smsSettings').slideToggle();" class="plus"> SMS</a></li>
									<div class="dBox smsSettings">
										 <?php
	                                    if(in_array('101',$permission_section))
	                                    {
	                                    ?>
	                                    <ul class="d_ul">
											<li><a href="<?php echo base_url('sms_template'); ?>"> SMS Template</a></li>
											<?php } if(in_array('102',$permission_section))
		                                      { ?>
											<li><a href="<?php echo base_url('sms_setting'); ?>"> SMS Setting</a></li>
											<?php } if(in_array('103',$permission_section))
		                                            { ?>
											<li><a href="<?php echo base_url('sms_config'); ?>"> SMS Config</a></li>
											 <?php } ?>
										</ul>
									</div>
									<?php } ?>
								<?php  if(in_array('150',$permission_section))
	                                   { ?>	
								<li><a href="<?php echo base_url('company_settings'); ?>" class=""> Company Settings</a></li>
								<?php } ?>
								<li><a href="javascript:void(0)" id="change_password" class=""> Change Password</a></li>
								<?php  if(in_array('6',$permission_section)) { ?>
								<li><a href="<?php echo base_url('unique_ids'); ?>" class=""> Unique IDs</a></li>
								<?php } ?>

								<li><a href="javascript:void(0)" id="default_search_setting"> Default Search Setting</a></li>
		                          <?php 
		                          if(in_array('150',$permission_section)){ 
		                          ?> 
		                            <li><a href="<?php echo base_url("website_setting");?>"> Config Setting</a></li> 
		                          <?php
		                          }
		                          if(in_array('218',$permission_section)) {
		                          ?>  
		            				<li><a href="<?php echo base_url("receipt_no_setting"); ?>"> Receipt No. Setting</a></li>
		                          <?php
		                           }
		                          ?>
								<li><a href="javascript:void(0)" id="change_password">Change Password</a></li>

								<?php 
								  if($user_role==1)
			                      {
			                          echo '
			                       <li><a href="javascript:void(0)" id="popup_maintenance_page">Maintenance Redirection</a>
			                       </li>';
			                      }
								?>
						       </ul>
							</div>
					 
						<li><a href="<?php echo base_url('logout'); ?>"><i class="fa fa-sign-out"></i> Logout</a></li>
					</ul>
					</div>
					</nav>
					</div>

				<!-- // -->
				</div>
				<div class="col-sm-4 col-xs-4 text-center">
					<div class="dash" style="margin-left:5em;">Dashboard</div>
				</div>
				<div class="col-sm-4 col-xs-6">
					<div class="dsh">
						<span><?php echo date('d M Y h:i A'); ?> <i class="fa fa-power-off"></i></span>
						<a href="<?php echo base_url('logout'); ?>"><i class="fa fa-power-off"></i> Logout</a>
					</div>
				</div>
			</div> <!-- innerRow -->
		</div>
	</div> <!-- row -->
	<!-- Dashboard close -->

	<script>
	function sub_menu_left(id)
	{ 
	  //alert($("#left_menu_"+id).length);	
	  if($("#left_menu_"+id).length<2)
	  {
         $.ajax({url: "<?php echo base_url(); ?>general/branch_left_menu/"+id, 
	      success: function(result)
	      { 
	        $('#left_menu_'+id).html(result);  
	        $('#left_menu_'+id).slideToggle();
	      } 
	    });
	  } 
	  else
	  {
	  	 $('#left_menu_'+id).slideToggle();
	  }


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

function change_password(id)
{
  var $modal = $('#load_change_password_popup');
  $modal.load('<?php echo base_url("change-password") ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


	


	</script>
