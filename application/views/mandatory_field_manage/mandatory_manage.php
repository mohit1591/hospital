<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
<?php
$users_data = $this->session->userdata('auth_users');
?>

<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');

 ?>

<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    
	<table class="table table-bordered table-striped p_new_tbl">
		<thead class="bg-theme">
			<tr>
				<th>Section</th>
				<th>Mandatory</th>
			</tr>
		</thead>
		<tbody>
			<!-- <tr> -->
			<?php 
			
			//echo "<pre>"; print_r($mandatory_sections); exit;
			if(!empty($mandatory_sections)){
				$j=1;
				foreach($mandatory_sections as $mandatory_section)
				{  
					//echo $mandatory_section;
				if($mandatory_section=='Doctors' && in_array('20',$users_data['permission']['section']))
				{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(1);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_1 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="1" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_1" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="1" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 
			}


			

			else if($mandatory_section=='Patients' && in_array('19',$users_data['permission']['section']))
				{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(2);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_2 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="2" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_2" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="2" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}

			else if($mandatory_section=='OPD Booking' && in_array('85',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(3);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_3 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="3" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_3" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="3" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}

			else if($mandatory_section=='OPD Billing' && in_array('151',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(4);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_4 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="4" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_4" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="4" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}

			else if($mandatory_section=='Medicine Entry' && in_array('56',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(5);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_5 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="5" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_5" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="5" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}

			else if($mandatory_section=='IPD Booking' && in_array('121',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(6);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_6 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="6" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_6" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="6" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}

			else if($mandatory_section=='Test Master' && in_array('143',$users_data['permission']['section']))
			{
					
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(7);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_7 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="7" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_7" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="7" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}

		else if($mandatory_section=='Test Booking' && in_array('145',$users_data['permission']['section']))
			{ 
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(8);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_8 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="8" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_8" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="8" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}
			else if($mandatory_section=='Medicine purchase' && in_array('56',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(10);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_10 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="10" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_10" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="10" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}
			else if($mandatory_section=='Medicine purchase return' && in_array('56',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(11);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_11 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="11" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_11" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="11" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}
			else if($mandatory_section=='Medicine Sale' && in_array('60',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(13);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_13 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="13" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_13" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="13" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}
			else if($mandatory_section=='Medicine Sale return' && in_array('61',$users_data['permission']['section']))
			{
					?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list(14);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_14 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="14" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_14" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="14" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php //$j++; 


			}
			else if($mandatory_section=='Ambulance' && in_array('349',$users_data['permission']['section']))
			{
				?>
				
			 	<tr>
				   
				    <td id="section_"><?php echo $mandatory_section; ?></td>
				    <td>
			       	    <div class="grp_box">
			                <?php  $field_list = mandatory_section_field_list(9);
			                    
			                if(!empty($field_list)){?>
			       	        <?php   
			       	            
			                    foreach($field_list as $field_lists){
			                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
			                        	$mandatory_checked = 'checked="checked"';?>
			                            <div class="grp">
			                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_2 fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="9" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
			                            </div>
			                        <?php
			                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
			                        	$mandatory_checked = '';?>
			                            <div class="grp">
			                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_2" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="9" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
			                            </div>
			                        <?php }
			                    }
			                }?>
			            </div>
			        </td>
				</tr>
			<?php //$j++; 


		}

		}
				}
			?>
				
		</tbody>
	</table>
	<button class=" btn-save" onclick="saveMandatoryFields();">Save</button>
	<button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
	
	
    
	
</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
<script>
	function saveMandatoryFields(){
		//var section_ids = [1,2,3,4,5,6,7,8];
		var section_ids = [1,2,3,4,5,6,7,8,9,10,11,13,14];
         var mandatoryFields = [];
		for(i=0;i<section_ids.length;i++){
			$('input:checkbox.section_'+section_ids[i]).each(function () {
                var sThisVal = (this.checked ? $(this).val() : "");
                if(sThisVal!=''){
                    mandatoryFields.push(sThisVal);
                }
            });
            
		}
		$.post('<?php echo base_url(); ?>mandatory_field_manage/save_mandatory_fields?>',{'mandatory_fields_ids':mandatoryFields},function(result){
			msg ="mandatory fields saved successfully";
			flash_session_msg(msg);



		})
	}
</script>
</html>