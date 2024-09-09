<?php
$users_data = $this->session->userdata('auth_users');
$profile_print_status = get_profile_print_status('print_booking');
$profile_status = $profile_print_status->profile_status;
$print_status = $profile_print_status->print_status;
$print_report_config = get_setting_value('PATHOLOGY_REPORT_PRINT');
if(empty($print_report_config))
{
  $print_report_config='0';
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">

<!-- bootstrap -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>  

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-select.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-select.min.css">
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript"> 


 
function checkboxValues() 
{         
    $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
     });
     allbranch_delete(allVals);
}
 
function view_diagnose(patient_id)
{
	  var $modal = $('#load_add_inventory_modal_popup');
	  $modal.load('<?php echo base_url().'test/view_diagnose/' ?>'+patient_id,
	  { 
	    },
	  function(){
	  $modal.modal('show');
	  });
}

function default_vals(test_id)
 { //alert(test_id);
    	var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('test_master/get_default_vals/'); ?>"+ test_id+'/' + request.term,
            function (data) {
                response(data);
            });
    	};
	/*var selectItem = function (event, ui) {
        $("#type"+row_id).val(ui.item.value);
        return false;
    }
*/

    var selectItem = function (event, ui) {
        //$(this).val(ui.item.value);
        $("#result-"+test_id).val(ui.item.value);
		//var test_id = $(this).attr('alt'); 
		check_result_range(ui.item.value,test_id);
        return false;
    }


    $("#result-"+test_id).autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {
            //$("#test_val").val("").css("display", 2);
        }
    });
    
    
 }

	
	function check_result_range(vals,test_id,type)
	{
		var vals = encodeURI(vals); 
		$.ajax({
			  type: "POST",
			  url: "<?php echo base_url('test/check_result_range/'.$booking_id.'/'); ?>"+test_id+"/?vals="+vals, 
			  success: function(result) 
			  {  
			     if(result==2)
				 {
				    $('.result-'+test_id).attr('style','background-color:red; color:#fff;');
				 }
				 else
				 {
				   $('.result-'+test_id).removeAttr('style');
				 }
				 if(type!="1")
				 {
				    /*var interval = setInterval(function(){ 
				    	 //flash_session_msg('Test result successfully updated.');
				    	 clearInterval(interval);  
				    	}, 2000);*/
					result_input();
					//set_formula(vals,test_id,type);  
					
				 }
				 set_condition(test_id,vals);   	 
			  }
		  });
	}
	
	
	function apply_formula(result,test_id,type)
	{
	   var result = encodeURI(result); 	
	   $.ajax({ 
			  url: "<?php echo base_url('test/apply_formula/'.$booking_id.'/'); ?>", 
			  data:{test_id:test_id,result:result},
			  type: "POST",
			  dataType: 'JSON',
			  success: function(res) 
			  { 
			     if(res.id != "")
			     { 
			        $('#result-'+res.id).val(res.result);
                 }  
			  }
		  });
	}
	
	
	function set_formula(result,test_id,type)
	{
	   var result = encodeURI(result); 	
	    //$('.overlay-loader').css('display','block');
	   $.ajax({ 
			  url: "<?php echo base_url('test/set_formula/'.$booking_id.'/'); ?>", 
			  data:{test_id:test_id, result:result},
			  type: "POST",
			  dataType: 'JSON',
			  success: function(res) 
			  { 
			     if(res.id != "")
			     { 
			         
                                $('#result-'+res.id).val(res.result);
                               // $('.overlay-loader').css('display','none');
			     }  
			  }
		  });
	}

	function set_condition(test_id,result)
	{
	   var result = encodeURI(result); 	
	   $.ajax({ 
			  url: "<?php echo base_url('test/set_condition/'.$booking_id.'/'); ?>"+test_id+"/?result="+result, 
			  type: "POST",
			  dataType: 'JSON',
			  success: function(res) 
			  {  
                 if(res.result==2)
			     { 
                    //$('#result-'+res.id).attr('style','background-color:red; color:#fff;');
                    alert('Invalid Result!');
			     } 

			     if(res.result==1)
			     {
			     	$(':text').each(function() {
			     		var ids = $(this).attr('id');
			     		var test_id = ids.replace('result-', "");
			     		var result = $(this).val();
			     		//alert(test_id+","+result);
			     		recur_set_condition(test_id,result);

			     	});
			     } 
			  }
		  });
	}

	function recur_set_condition(test_id,result)
	{
	   var result = encodeURI(result); 		
	   $.ajax({ 
			  url: "<?php echo base_url('test/set_condition/'.$booking_id.'/'); ?>"+test_id+"/?result="+result, 
			  type: "POST",
			  dataType: 'JSON',
			  success: function(res) 
			  {  
                 if(res.result==2)
			     { 
                    $('#result-'+res.id).attr('style','background-color:red; color:#fff;');
			     }  
			     if(res.result==1)
			     {
			     	$('#result-'+res.id).removeAttr('style');
			     }
			  }
		  });
	}
</script>
 </head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->


<section class="userlist">
<form name="test_booking" id="test_booking">
   <div class="test_report">
			<div class="test_report_left">

			<div class="row">
					<div class="col-md-6">
						<!-- //////////////////////////// -->
						<div class="row m-b-5">
							<div class="col-md-4">
								<label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label>
							</div>
							<div class="col-md-8">
								<?php echo $patient_code; ?>
							</div>
						</div>
						
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Patient Name</label>
							</div>
							<div class="col-md-8">
								<?php echo $patient_name; ?>
							</div>
						</div>
						
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Gender/Age</label>
							</div>
							<div class="col-md-8">
								<?php echo $gender_age; ?>
							</div>
						</div>
						
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Mobile No.</label>
							</div>
							<div class="col-md-8">
								<?php echo $mobile_no; ?>
							</div>
						</div>
						<?php if(!empty($address)){  ?>
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Address</label>
							</div>
							<div class="col-md-8">
								<?php echo $address; ?>
							</div>
						</div>
						<?php }  ?>
						
					</div> <!-- 6 ColumnLeft -->
					<div class="col-md-6">
						<!-- //////////////////////////// -->
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Booking Date</label>
							</div>
							<div class="col-md-8">
								<?php echo $booking_date; ?>
							</div>
						</div>
						
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Lab Ref. No.</label>
							</div>
							<div class="col-md-8">
								<?php echo $lab_reg_no; ?>
							</div>
						</div>
						<?php if(!empty($doctor_name)){ ?>
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Doctor Name</label>
							</div>
							<div class="col-md-8">
								<?php echo $doctor_name; ?>
							</div>
						</div>
						<?php } ?>
						<div class="row m-b-5">
							<div class="col-md-4">
								<label>Referrerd By </label>
							</div>
							<div class="col-md-8">
								<?php echo $referred_by; ?>
							</div>
						</div>
                            <?php if(isset($tube_no) && !empty($tube_no))
							{
							?>
								<div class="row m-b-5">
								<div class="col-md-4">
								<label>Tube No. </label>
								</div>
								<div class="col-md-8">
								<?php echo $tube_no; ?>
								</div>
								</div>
						 <?php } ?>
						
					</div> <!-- 6 ColumnRight -->
					<div class="col-md-6">
					    
								<div class="row m-b-5">
								<div class="col-md-4">
								<label>Booking Type </label>
								</div>
								<div class="col-md-8">
							<?php if(isset($ipd_id) && !empty($ipd_id))
							{
							?>	IPD Patient  <?php }else{ ?>
							Registred Patient
							<?php } ?>
								</div>
								</div>
						
					    </div>
				</div>
			
			   <table class="table table-bordered table-striped test_report_tbl">
					<thead class="bg-theme">
					   <tr>
						 <th>Test Name</th>
						 <th width="100" >
						   <input type="checkbox" checked="" value="1" onClick="final_toggle(this,1);"/> Status 
						 </th>
						 <th width="210">Result</th>
						 <th width="60">
						   <input type="checkbox" value="2"  onClick="final_toggle(this,2);"/> Print 
						 </th>
						 <th width="60">
						   <input type="checkbox" value="3"  onClick="final_toggle(this,3);"/> Interpretation
						 </th>
						 <th width="200">Advance</th>
					   </tr>
					</thead>
					<tbody> 
					<?php 
					

					  if($booking_data[0]->profile_id>0)
					{
						
				/*print and name*/
	                    if($profile_status==1 && $print_status==1)
	                    {
	                    if(!empty($booking_data[0]->print_name))
	                    {
	                    $profile_name = $booking_data[0]->profile_name.' ('.$booking_data[0]->print_name.')';
	                    }
	                    else
	                    {
	                    $profile_name = $booking_data[0]->profile_name;
	                    }
	                    }
	                    elseif($profile_status==1 && $print_status==0)
	                    {
	                    $profile_name = $booking_data[0]->profile_name;
	                    }
	                    elseif($profile_status==0 && $print_status==1)
	                    {
	                    if(!empty($booking_data[0]->print_name))
	                    {
	                    $profile_name = $booking_data[0]->print_name;
	                    }
	                    else
	                    {
	                    $profile_name = $booking_data[0]->profile_name;
	                    }

	                    }
	                    elseif($profile_status==0 && $print_status==0)
	                    {
	                    $profile_name = $booking_data[0]->profile_name;
	                    }
	                    /*print and name*/
?>
                         <tr>
							  <th colspan="5" style="text-align: center; font-size: 14px; text-decoration: underline;"><?php echo $profile_name; ?></th>
							  <th><a href="javascript:void(0)"  onClick="add_profile_interpretation(<?php echo $booking_id; ?>,<?php echo $booking_data[0]->profile_id; ?>)" class="btn-custom"><i class="fa fa-plus"></i>  Add Interpretation</a></th>
						 </tr>	  
						<?php
					  
					if(!empty($profile_test_list))
					{ 

					  $test_data_list = [];	
					  $not_in_array = [];
					  
					  $r = 1; 
					  foreach($profile_test_list as $test)
					  {  
					     if(!empty($test->result))
						 {
						?>
						    <!--<script>check_result_range('<?php echo $test->result; ?>',<?php echo $test->test_id; ?>,1);</script>-->
							
					    <?php
						}
						?>	
							<tr>
							  <td>
							    <?php 
							    if($test->test_type_id==1)
							    {
							      echo '<b>'.$test->test_name.'</b>'; 
							    }
							    else
							    {
							      echo $test->test_name; 
							    }
							    ?>  
							    </td>
							  <td align="left"><input type="checkbox" <?php if($test->status==1 || !empty($test->result)){ ?> checked="checked" <?php } ?> value="<?php echo $test->test_id; ?>" class="status_checkbox status_<?php echo $r; ?>" name="status[]"></td>
							  <td>
							  	<?php if($test->test_type_id==1){ ?> <input type="hidden"  name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="report_result result-box <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?> result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>"  value="<?php echo $test->result; ?>" onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>'); " /> 

				  		<?php  }else{


				  			if($test->test_result_type==1)
							{
								$sample_test_data = get_sample_type_for_test($test->test_id);
								//echo "<pre>"; print_r($sample_test_data); exit;
							?>
					     
								<select name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="selectpicker report_result result-box  <?php if($test->result_type==1){ echo 'numeric'; }else if($test->result_type==2){ echo 'alpha'; } ?> <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>" onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>');" data-live-search="true">

								<?php 
								if(!empty($sample_test_data))
								{
								foreach($sample_test_data as $sample_data)
								{
								?>
								<option <?php if($test->result==$sample_data){ echo 'selected="selected"';} ?> value="<?php echo $sample_data; ?>"><?php echo $sample_data; ?></option>
								<?php
								}
								}
								?>
								</select>
							<?php 
							}
							else
							{

							
				  		 ?>
				  		<input type="text"  name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="report_result result-box <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?> result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>"  value="<?php echo $test->result; ?>"  onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>');" />
				  		<?php
				  		} } ?>

							  </td>
							  <td align="left"><input type="checkbox" value="<?php echo $test->test_id; ?>" <?php  if($test->print==1){ ?> checked="checked" <?php } ?>  name="print[]" class="print_checkbox print_profile_checkbox_<?php echo $profile_record->profile_id; ?>"></td>
							  <td align="left"><input type="checkbox" value="<?php echo $test->test_id; ?>" <?php  if($test->interpretation==1){ ?> checked="checked" <?php } ?> name="interpretation[]" class="inter_checkbox"></td>
							  <td>
								  <a onClick="advance_result(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="result-advance btn-custom"><i class="fa fa-cubes"></i> Advance</a>
								  <?php if(in_array('953',$users_data['permission']['action'])){ ?>
								   <a onClick="inventory_result(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-anchor"><i class="fa fa-cubes"></i> Inventory</a>
								   
								   	<a href="javascript:void(0)"  onClick="add_widal_value(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-custom"><i class="fa fa-plus"></i> Table </a>

								   <?php } ?>
							  </td>
							  <input type="hidden" name="booked_test_id[]" value="<?php echo $test->id; ?>">
							</tr>
						<?php
						$r++;
					  } 
					}
                  }

				///////// Multi Profile Filling Start ///////////////////
                  
				if(!empty($booked_profile_list))
				{ 
					foreach($booked_profile_list as $profile_record)
					{ 	
						/*print and name*/
	                    if($profile_status==1 && $print_status==1)
	                    {
		                    if(!empty($profile_record->print_name))
		                    {
		                       $profile_name = $profile_record->profile_name.' ('.$profile_record->print_name.')';
		                    }
		                    else
		                    {
		                       $profile_name = $profile_record->profile_name;
		                    }
	                    }
	                    elseif($profile_status==1 && $print_status==0)
	                    {
	                        $profile_name = $profile_record->profile_name;
	                    }
	                    elseif($profile_status==0 && $print_status==1)
	                    {
		                    if(!empty($profile_record->print_name))
		                    {
		                       $profile_name = $profile_record->print_name;
		                    }
		                    else
		                    {
		                       $profile_name = $profile_record->profile_name;
		                    }

	                    }
	                    elseif($profile_status==0 && $print_status==0)
	                    {
	                       $profile_name = $profile_record->profile_name;
	                    }
	                    /*print and name*/ 
        ?>
         <tr>
			  <th colspan="3" style="text-align: center; font-size: 14px; text-decoration: underline;"><?php echo $profile_name; ?></th><th>
			   <input type="checkbox"  value="1" onClick="final_toggle_profiles(this,<?php echo $profile_record->profile_id; ?>);"/> Print 
			  </th>
			  <th></th>
			  <th><a href="javascript:void(0)"  onClick="add_profile_interpretation(<?php echo $booking_id; ?>,<?php echo $profile_record->profile_id; ?>)" class="btn-custom result-advance"><i class="fa fa-plus"></i>  Add Interpretation</a></th>
		 </tr>	  
		<?php
		
        $profile_test_list = $this->test->report_test_list($booking_id,'','1,2',$profile_record->profile_id);
          //echo "<pre>"; print_r($profile_test_list); exit;
          if(!empty($profile_test_list))
			{  				  	
			  $r = 1;   
			  foreach($profile_test_list as $test)
			  { 
			  //echo "<pre>"; print_r($profile_test_list); exit; 
			     if(!empty($test->result))
				 {

				?>
				    <!--<script>check_result_range('<?php echo $test->result; ?>',<?php echo $test->test_id; ?>,1);</script>-->
					
			    <?php
				}
				?>	
				<tr>
				  <td>
				    <?php 
				    if($test->test_type_id==1)
				    {
				      echo '<b>'.$test->test_name.'</b>'; 
				    }
				    else
				    {
				      echo $test->test_name; 
				    }
				    ?>  
				    </td>
	<td align="left"><input type="checkbox" <?php if($test->status==1 || !empty($test->result)){ ?> checked="checked" <?php } ?> value="<?php echo $test->test_id; ?>" class="status_checkbox status_<?php echo $r; ?>" name="status[]"></td>
	<td>
	<?php if($test->test_type_id==1){ ?> <input type="hidden"  name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="report_result result-box  <?php if($test->result_type==1){ echo 'numeric'; }else if($test->result_type==2){ echo 'alpha'; } ?> <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>"  value="<?php echo $test->result; ?>"  onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>'); " /><?php  }else{ 


		if($test->test_result_type==1)
		{
					$sample_test_data = get_sample_type_for_test($test->test_id);
					//echo "<pre>"; print_r($sample_test_data); exit;
				?>
		     
					<select name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="selectpicker report_result result-box  <?php if($test->result_type==1){ echo 'numeric'; }else if($test->result_type==2){ echo 'alpha'; } ?> <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>" onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>');" data-live-search="true">

					<?php 
					if(!empty($sample_test_data))
					{
					foreach($sample_test_data as $sample_data)
					{
					?>
					<option <?php if($test->result==$sample_data){ echo 'selected="selected"';} ?> value="<?php echo $sample_data; ?>"><?php echo $sample_data; ?></option>
					<?php
					}
					}
					?>
					</select>
				<?php 
				}
		else
		{


		?>
     <input type="text"  name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="report_result result-box  <?php if($test->result_type==1){ echo 'numeric'; }else if($test->result_type==2){ echo 'alpha'; } ?> <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>"  value="<?php echo $test->result; ?>"  onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>');" />  
         <?php 
         }
     } ?> </td>
	<td align="left"><input type="checkbox" value="<?php echo $test->test_id; ?>" <?php  if($test->print==1){ ?> checked="checked" <?php } ?>  name="print[]" class="print_checkbox print_profile_checkbox_<?php echo $profile_record->profile_id; ?>"></td>
	<td align="left"><input type="checkbox" value="<?php echo $test->test_id; ?>" <?php  if($test->interpretation==1){ ?> checked="checked" <?php } ?> name="interpretation[]" class="inter_checkbox"></td>
	<td><a onClick="advance_result(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-custom result-advance"><i class="fa fa-cubes"></i> Advance</a>
		<?php if(in_array('953',$users_data['permission']['action'])){ ?>
								   <a onClick="inventory_result(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-anchor"><i class="fa fa-cubes"></i> Inventory</a>
								   <a href="javascript:void(0)"  onClick="add_widal_value(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-custom"><i class="fa fa-plus"></i> Table </a>

								   <?php } ?>
	</td>
	<input type="hidden" name="booked_test_id[]" value="<?php echo $test->id; ?>">
	</tr>
		<?php
		$r++;
	  } 
	}

	/////////// Start Child Profile List //////////////////
                    $child_profile_list = $child_profile_data = $this->test->get_booking_profile($booking_id,'',$profile_record->profile_id);
                    if(!empty($child_profile_list))
                    {
                    	foreach ($child_profile_list as $child_profile) 
                    	{
                    		 //echo "<pre>";print_r($child_profile);die;
                    		/*print and name*/
		                    if($profile_status==1 && $print_status==1)
		                    {
			                    if(!empty($child_profile->print_name))
			                    {
			                       $c_profile_name = $child_profile->profile_name.' ('.$child_profile->print_name.')';
			                    }
			                    else
			                    {
			                       $c_profile_name = $child_profile->profile_name;
			                    }
		                    }
		                    elseif($profile_status==1 && $print_status==0)
		                    {
		                        $c_profile_name = $child_profile->profile_name;
		                    }
		                    elseif($profile_status==0 && $print_status==1)
		                    {
			                    if(!empty($child_profile->print_name))
			                    {
			                       $c_profile_name = $child_profile->print_name;
			                    }
			                    else
			                    {
			                       $c_profile_name = $child_profile->profile_name;
			                    }

		                    }
		                    elseif($profile_status==0 && $print_status==0)
		                    {
		                       $c_profile_name = $child_profile->profile_name;
		                    }
		                    /*print and name*/ 
		                    ?>
					         <tr>
								  <th colspan="5" style="text-align: center; font-size: 14px; text-decoration: underline;"><?php echo $c_profile_name; ?></th>
								  <th><a href="javascript:void(0)"  onClick="add_profile_interpretation(<?php echo $booking_id; ?>,<?php echo $profile_record->profile_id; ?>)" class="btn-custom result-advance"><i class="fa fa-plus"></i>  Add Interpretation</a></th>
							 </tr>	  
							<?php
							$cprofile_test_list = $this->test->report_test_list($booking_id,'','1,2',$child_profile->profile_id,'1,2');
							//echo "<pre>"; print_r($cprofile_test_list); exit;
                            if(!empty($cprofile_test_list))
							{  				  	
							  $r = 1;
							  foreach($cprofile_test_list as $cp_test)
							  {  
							     if(!empty($cp_test->result))
								 {
								?>
								    <!--<script>check_result_range('<?php echo $cp_test->result; ?>',<?php echo $cp_test->test_id; ?>,1);</script>-->
									
							    <?php
								}
								?>	
								<tr>
								  <td>
								    <?php 
								    if($cp_test->test_type_id==1)
								    {
								      echo '<b>'.$cp_test->test_name.'</b>'; 
								    }
								    else
								    {
								      echo $cp_test->test_name; 
								    }
								    ?>  
								    </td>
									<td align="left"><input type="checkbox" <?php if($cp_test->status==1 || !empty($cp_test->result)){ ?> checked="checked" <?php } ?> value="<?php echo $cp_test->test_id; ?>" class="status_checkbox status_<?php echo $r; ?>" name="status[]"></td>
									<td>
									<?php if($cp_test->test_type_id==1){ }else{  
         
		if($cp_test->test_result_type==1)
		{
					$sample_test_data = get_sample_type_for_test($cp_test->test_id);
					//echo "<pre>"; print_r($sample_test_data); exit;
				?>
		     
					<select name="result[<?php echo $cp_test->test_id; ?>]" id="result-<?php echo $cp_test->test_id; ?>" class="selectpicker report_result result-box  <?php if($cp_test->result_type==1){ echo 'numeric'; }else if($cp_test->result_type==2){ echo 'alpha'; } ?> <?php if($cp_test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $cp_test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $cp_test->test_id; ?>" onchange="default_vals('<?php echo $cp_test->test_id; ?>');check_result_range(this.value, '<?php echo $cp_test->test_id; ?>');" data-live-search="true">

					<?php 
					if(!empty($sample_test_data))
					{
					foreach($sample_test_data as $sample_data)
					{
					?>
					<option <?php if($cp_test->result==$sample_data){ echo 'selected="selected"';} ?> value="<?php echo $sample_data; ?>"><?php echo $sample_data; ?></option>
					<?php
					}
					}
					?>
					</select>
				<?php 
				}
		else
		{


										?>
								     <input type="text"  name="result[<?php echo $cp_test->test_id; ?>]" id="result-<?php echo $cp_test->test_id; ?>" class="report_result result-box <?php if($cp_test->result_type==1){ echo 'numeric'; }else if($cp_test->result_type==2){ echo 'alpha'; } ?> <?php if($cp_test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $cp_test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $cp_test->test_id; ?>"  value="<?php echo $cp_test->result; ?>" onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $cp_test->test_id; ?>'); " />  <?php }} ?>
								          </td>
									<td align="left"><input type="checkbox" value="<?php echo $cp_test->test_id; ?>" <?php  if($cp_test->print==1){ ?> checked="checked" <?php } ?>  name="print[]" class="print_checkbox print_profile_checkbox_<?php echo $profile_record->profile_id; ?>"></td>
									<td align="left"><input type="checkbox" value="<?php echo $cp_test->test_id; ?>" <?php  if($cp_test->interpretation==1){ ?> checked="checked" <?php } ?> name="interpretation[]" class="inter_checkbox"></td>
									<td><a onClick="advance_result(<?php echo $booking_id; ?>,<?php echo $cp_test->test_id; ?>)" class="btn-custom result-advance"><i class="fa fa-cubes"></i> Advance</a>
										<?php if(in_array('953',$users_data['permission']['action'])){ ?>
								   <a onClick="inventory_result(<?php echo $booking_id; ?>,<?php echo $cp_test->test_id; ?>)" class="btn-anchor"><i class="fa fa-cubes"></i> Inventory</a>
								   
								   <a href="javascript:void(0)"  onClick="add_widal_value(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-custom"><i class="fa fa-plus"></i> Table </a>

								   <?php } ?>
									</td>
									<input type="hidden" name="booked_test_id[]" value="<?php echo $cp_test->id; ?>">
									</tr>
						<?php
						$r++;
					  } 
                    	}
                    }
                }
					/////////// End Child profile List ////////////// 

}
}
/////////// Multi Profile Filling End /////////////////

////////////////////////////////PATH TEST////////////////////////////////		
					if(!empty($test_list))
					{
					  
					  $r = 1; 
					  foreach($test_list as $test)
					  {  
					     if(!empty($test->result))
						 {
						?>
						    <!--<script>check_result_range('<?php echo $test->result; ?>',<?php echo $test->test_id; ?>,1);</script>-->
							
					    <?php
						}
						?>	
							<tr>
							  <td>
							    <?php 
							    if($test->test_type_id==1)
							    {
							      echo '<b>'.$test->test_name.'</b>'; 
							    }
							    else
							    {
							      echo $test->test_name; 
							    }
							    ?>  
							    </td>
							  <td align="left"><input type="checkbox" <?php if($test->status==1 || !empty($test->result)){ ?> checked="checked" <?php } ?> value="<?php echo $test->test_id; ?>" class="status_checkbox status_<?php echo $r; ?>" name="status[]"></td>
							  <td><?php if($test->test_type_id==1){ ?> 

							  <input type="hidden"  name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="report_result result-box  <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?> result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>"  value="<?php echo $test->result; ?>"  onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>');" />

				<?php }else{  

				if($test->test_result_type==1)
				{
					$sample_test_data = get_sample_type_for_test($test->test_id);
					//echo "<pre>"; print_r($sample_test_data); exit;
				?>
		     
					<select name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class="selectpicker report_result result-box  <?php if($test->result_type==1){ echo 'numeric'; }else if($test->result_type==2){ echo 'alpha'; } ?> <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?>  result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?>" alt="<?php echo $test->test_id; ?>" onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>');" data-live-search="true">

					<?php 
					if(!empty($sample_test_data))
					{
					foreach($sample_test_data as $sample_data)
					{
					?>
					<option <?php if($test->result==$sample_data){ echo 'selected="selected"';} ?> value="<?php echo $sample_data; ?>"><?php echo $sample_data; ?></option>
					<?php
					}
					}
					?>
					</select>
				<?php 
				}
				else
				{
							   	?><input type="text"  name="result[<?php echo $test->test_id; ?>]" id="result-<?php echo $test->test_id; ?>" class=" result-box  <?php if($test->formula_avl==1){ echo 'is_formula ';}else{ echo 'result_keyup'; } ?> result_input result-<?php echo $test->test_id; ?> result_<?php echo $r; ?> report_result" alt="<?php echo $test->test_id; ?>"  value="<?php echo $test->result; ?>" onchange="default_vals('<?php echo $test->test_id; ?>');check_result_range(this.value, '<?php echo $test->test_id; ?>'); " /> 

							   <?php } 
							   	}

							   	?></td>
							  <td align="left"><input type="checkbox" value="<?php echo $test->test_id; ?>" <?php  if($test->print==1){ ?> checked="checked" <?php } ?>  name="print[]" class="print_checkbox"></td>
							  <td align="left"><input type="checkbox" value="<?php echo $test->test_id; ?>" <?php  if($test->interpretation==1){ ?> checked="checked" <?php } ?> name="interpretation[]" class="inter_checkbox"></td>
							  <td>
								  <a onClick="advance_result(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-custom result-advance"><i class="fa fa-cubes"></i> Advance</a>
								   <?php if(in_array('953',$users_data['permission']['action'])){ ?>
								   <a onClick="inventory_result(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-anchor"><i class="fa fa-cubes"></i> Inventory</a>
								   
								   <a href="javascript:void(0)"  onClick="add_widal_value(<?php echo $booking_id; ?>,<?php echo $test->test_id; ?>)" class="btn-custom"><i class="fa fa-plus"></i> Table </a>

								   <?php } ?>
							  </td>
							  <input type="hidden" name="booked_test_id[]" value="<?php echo $test->id; ?>">
							</tr>
						<?php
						$r++;
					  } 
					}
					//echo '<pre>';print_r($booking_data[0]->profile_name);die;
					 



					/////////// Path Test End /////////////////
					?> 
					</tbody>
				  </table> 
				  
			</div> <!-- test_frame -->
			<div class="test_report_right" style="position: relative">
			    
			    
			<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
			<input type="hidden" name="booking_date" value="<?php echo $booking_date; ?>">
			
			<input type="text" name="complation_date" class="w-130 datepicker" value="<?php echo $complation_date; ?>" placeholder="Completion Date"  style="width:110px;">
			<input type="text" name="complation_time" class="w-65px datepicker3 m_input_default" placeholder="Time" value="<?php echo $complation_time; ?>">
			<br>
			    <?php if($booking_data[0]->verify_status!='1'){ ?>
			   <div class="">
    			 <select name="diagnose" class="m-b-3">
                      <option value="">Select Diagnose</option>
                      <?php
                        if(!empty($diagnose_list))
                        {
                        	foreach($diagnose_list as $diagnose_val)
                        	{
                        	?>
                        	 <option <?php if($diagnose==$diagnose_val->id){ echo 'selected="selected"'; } ?> value="<?php echo $diagnose_val->id; ?>"><?php echo $diagnose_val->diagnose; ?></option>
                        	<?php	
                        	}
                        }
                      ?>
    			</select>
			    </div> 
			    <div class="">
			        <input type="text" name="diagnose_text" id="diagnose_text" class="remarks m_input_default"  placeholder="Diagnose Comment" value="<?php echo $diagnose_text; ?>">
			    </div>
			    <?php } ?>
			    
			  <div class="btns">  
				 <select name="signature_id" class="m-b-3">
                  <option value="">Select Signature</option>
                  <?php
                    if(!empty($signature_list))
                    {
                    	foreach($signature_list as $signature)
                    	{
                    	?>
                    	 <option value="<?php echo $signature->id; ?>"><?php echo $signature->signature; ?></option>
                    	<?php	
                    	}
                    }
                  ?>
				 </select>
				<a onClick="view_diagnose(<?php echo $patient_id; ?>)" class="btn-anchor"><i class="fa fa-cubes"></i> Diagnosed</a>
				  <?php   
				  //echo $booking_data[0]->verify_status;
				  //echo '</br>';
				  //echo $booking_data[0]->verify_status;
				     $comp_print = 0;
					 if($print_report_config==0 && $booking_data[0]->complation_status=='1')
					 {
					 	$comp_print = 1;
					 } 
					 else if($print_report_config==1)
					 {
	                   if($dept_status==2 && $booking_data[0]->verify_status=='1')
	                 	{
	                 		$comp_print = 1;
	                 	}
	                 	else if($booking_data[0]->complation_status=='1' && $dept_status==1)
	                 	{
	                 		$comp_print = 1;
	                 	}
					 }

					 ///////// Report Setting ////////////// 
                      if(!empty($report_setting_data))
                        { 
                            //print_r($report_setting_data);
                        	$balance = test_booking_balance($booking_id); 
                            if($report_setting_data->report_print==1)
                            {
                            	if($balance>0)
                            	{
                                  $balance_status = 0;
                            	}
                            	else
                            	{
                            		$balance_status = 1;
                            	}
                                
                            }
                            else
                            {
                              //$balance_status = 0;
                              
                              if($balance>0)
                            	{
                                  $balance_status = 0;
                            	}
                            	else
                            	{
                            		$balance_status = 1;
                            	}
                            }

                        }
                        else
                        {
                          $balance_status = 1;
                        } 
                      //////////////////////////////////////
				 
				 if($comp_print==1 && $balance_status==1)
				 {
				 ?> 
	                 <a class="btn-anchor" target="_blank" href="<?php echo base_url(); ?>test/print_test_report/<?php echo $booking_id; ?>/0">
						<i class="fa fa-print"></i> Print
					 </a>  
				     <a class="btn-anchor" onClick="return send_test_report(<?php echo $booking_id; ?>,'send')">
							  <i class="fa fa-envelope"></i> Email
					 </a>  
					 <script type="text/javascript">
					 	$('.result-box').prop('readonly','readonly');
					 	$('.result-advance').removeAttr('onClick');
					 </script>
					 <?php 
                  }
                  ?>	
                   <div id="report_button" style="display: none;">
                  <?php  
                if(in_array('1239',$users_data['permission']['action']) && $booking_data[0]->verify_status==0)
				 {
                   if($booking_data[0]->complation_status=='0') 
                   	{ 
                   		?>
						 <a id="pending_status" <?php if($booking_data[0]->complation_status=='1') { ?> style="display:none;" <?php } ?> class="btn-anchor h-auto" onClick="return complete_test_report(<?php echo $booking_id; ?>,1)">
							  <i class="fa fa-list"></i> Comp. Report
						 </a>
						 
						 <?php  //echo $balance_status;
						if($balance_status==1)
						{ //previously preview here 
						?>
						

						<?php 
						}
						?>
				 <?php } 
				 else{  
				 ?>
						 <a id="complete_status" <?php if($booking_data[0]->complation_status=='0') { ?> style="display:none;" <?php } ?> class="btn-anchor h-auto" onClick="return complete_test_report(<?php echo $booking_id; ?>,0)">
							  <i class="fa fa-list"></i> Pending Report
						 </a>

				 <?php
                  } 
                } 
                
                
                ?>

                <?php
                $verify_status = 0;
                 if(!empty($branch_verify_dept))
                 {
                 	foreach($branch_verify_dept as $verify_dept)
                 	{
                 		if(in_array($verify_dept,$all_dept))
                 		{
                 			$verify_status=1;
                 		}
                 	}
                 } 
                 
                 if($booking_data[0]->complation_status=='1')
                 {
                 	if($booking_data[0]->verify_status==0)
                 	{
                 		if(in_array('1422',$users_data['permission']['action']))
                 		{ 
							?>
							<a id="verify_status"   class="btn-anchor h-auto" onClick="return verify_status(<?php echo $booking_id; ?>,1)">
							<i class="fa fa-check-circle"></i> Verify
							</a>
							<?php
                      }
                 	}
                 	else
                 	{
						if(in_array('1439',$users_data['permission']['action']))
						{
						?>
							<a id="verify_status"   class="btn-anchor h-auto" onClick="return verify_status(<?php echo $booking_id; ?>,0)">
							<i class="fa fa-ban"></i> Un-Verify
							</a>   
							<?php
						}
                 	}        
                 }
                 
                 //if(in_array('1239',$users_data['permission']['action']))
                
                    
                
                ?>
				</div>
				<a class="btn-anchor" target="_blank" href="<?php echo base_url(); ?>test/print_test_report/<?php echo $booking_id; ?>/0"><i class="fa fa-eye"></i> Preview</a>
				<button type="submit"  class="btn-update" name="submit"><i class="fa fa-floppy-o"></i> Save </button>
				<a class="btn-anchor" onClick="window.location.href='<?php echo base_url('test') ?>'">
					  <i class="fa fa-sign-out"></i> Exit
				 </a>

			  </div>
		
			  
		   </div> <!-- test_report_right -->
   </div> <!-- test_report_left -->
   </form>
</section> <!-- close -->

  

 
<?php
$this->load->view('include/footer');
?>
<div id="load_add_inventory_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script> 

$(document).ready(function(){
  //get_dept_common_data('<?php echo $booking_id; ?>');
});

// function get_dept_common_data(booking_id)
// { 
// 	$.ajax({
// 			url: "<?php echo base_url();?>test/get_dept_common_data/"+booking_id, 
// 			type: 'post',
// 			dataType: 'json',
// 			async: false,
// 			success: function(result){ 
// 				if(result==1)
// 		      	{ 
					
					 
// 				}
			
// 			},
// 	}); 
// }

$("#test_booking").on("submit", function(event) { 
  event.preventDefault(); 
 
  var ids = <?php echo $booking_id; ?>;
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'report/'+ids;
    var msg = 'Test successfully updated.';
  }

	$.ajax({
	    url: "<?php echo base_url('test/'); ?>"+path,
	    type: "post",
	    data: $(this).serialize(),
	    async: false,
	    success: function(result) {
	    	
	      if(result==1)
	      { 
	        flash_session_msg('Report successfully saved.'); 
	        
	        $("#report_button").css("display", "block");
	      } 
	            
	      
	    }
  });
}); 



function complete_test_report(booking_id,status)
{ 
	$.ajax({
			url: "<?php echo base_url();?>test/complete_test_report/"+booking_id+'/'+status, 
			type: 'post',
			dataType: 'json',
			async: false,
			success: function(result){ 
			if(result==1)
	      	{ 
				if(status==1)
				{
					
					flash_session_msg('Report status completed successfully.');
					 window.setTimeout(function(){location.reload()},1000)
				}
				else
				{
					
					flash_session_msg('Report status pending successfully.');
					 window.setTimeout(function(){location.reload()},1000)
				}
				 
			}
			
			},
	}); 
}

function verify_status(booking_id,status)
{ 
	$.ajax({
			url: "<?php echo base_url();?>test/verify_status/"+booking_id+'/'+status, 
			type: 'post',
			dataType: 'json',
			async: false,
			success: function(result){ 
			if(result==1)
				{
					
					flash_session_msg('Report verify status completed successfully.');
					 window.setTimeout(function(){location.reload()},1000)
				}
				else
				{
					
					flash_session_msg('Report un-verify status pending successfully.');
					 window.setTimeout(function(){location.reload()},1000)
				}
			
			},
	}); 
}

function inventory_result(booking_id,test_id)
{
	  var $modal = $('#load_add_inventory_modal_popup');
	  $modal.load('<?php echo base_url().'test/inventory_report_result/' ?>'+booking_id+'/'+test_id,
	  { 
	    },
	  function(){
	  $modal.modal('show');
	  });
}
	

  function final_toggle(source,types) 
  {  
      if(types==1)
	  {
	    checkboxes = document.getElementsByClassName('status_checkbox');
	  }
	  if(types==2)
	  {
	    checkboxes = document.getElementsByClassName('print_checkbox');
	  }
	  if(types==3)
	  {
	    checkboxes = document.getElementsByClassName('inter_checkbox');
	  }
      
      for(var i=0, n=checkboxes.length;i<n;i++) {
      checkboxes[i].checked = source.checked;
      }
  } 
  
  function final_toggle_profiles(source,profile_id) 
  {  
      
	   checkboxes = document.getElementsByClassName('print_profile_checkbox_'+profile_id);
	  
      
      for(var i=0, n=checkboxes.length;i<n;i++) {
        checkboxes[i].checked = source.checked;
      }
  } 
   
  function isNumberKey(evt) 
  {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }


  
  $("#test_search").keyup(function(){ 
        var input, filter, table, tr, td, i;
        input = document.getElementById("test_search");
        filter = input.value.toUpperCase();
        table = document.getElementById("test_list");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
        tdid = tr[i].getElementsByTagName("td")[1];
        tdnm = tr[i].getElementsByTagName("td")[2];
        tdrt = tr[i].getElementsByTagName("td")[3];
        if (tdid || tdnm || tdrt) {
          if (tdid.innerHTML.toUpperCase().indexOf(filter) > -1 || tdnm.innerHTML.toUpperCase().indexOf(filter) > -1 || tdrt.innerHTML.toUpperCase().indexOf(filter) > -1) {
            tr[i].style.display = "";
          } 
           else {
            tr[i].style.display = "none";
          }
        }
        }
  }); 
  
  function test_list_vals() 
  {          
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       remove_test(allVals);
  } 

   


  function child_test_vals() 
  {          
       var allVals = [];
       $('.child_checkbox').each(function() 
       {
         if($(this).prop('checked')==true && !isNaN($(this).val()))
         {
              allVals.push($(this).val());
         } 
       });
       send_test(allVals);
  }

  	function send_test_report(booking_id,type)
	{
	  var $modal = $('#load_add_test_modal_popup');
	  $modal.load('<?php echo base_url().'test/send_email/' ?>'+booking_id+"/"+type,
	  {
	    //'id1': '1',
	    //'id2': '2'
	    },
	  function(){
	  $modal.modal('show');
	  });
	}

	function print_test_report(booking_id,type)
	{ 
		$.ajax({
				url: "<?php echo base_url();?>test/print_test_report/"+booking_id+"/"+type, 
				type: 'post',
				dataType: 'json',
				async: false,
				success: function(response){
				if(response.success)
				{ 
					printdiv(response.pdf_template);
				}
				else
				{
					//alert(response.msg);   
				}
				},
		}); 
	}



	function printdiv(printpage)
	{
	var headstr = "<html><head><title></title></head><body>";
	var footstr = "</body>";
	var newstr = printpage
	var oldstr = document.body.innerHTML;
	//document.getElementById('header').style.display = 'none';
	//document.getElementById('footer').style.display = 'none';

	document.body.innerHTML = headstr+newstr+footstr;
	window.print();
	//window.location.reload();
	return;
	} 
 
    

</script> 
<!-- Confirmation Box -->

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_test_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>
function add_profile_interpretation(booking_id,profile_id)
{
  var $modal = $('#load_add_test_modal_popup');
  $modal.load('<?php echo base_url().'test/add_profile_interpretation/' ?>'+booking_id+'/'+profile_id,
  { 
    },
  function(){
  $modal.modal('show');
  });
} 

function advance_result(booking_id,test_id)
{
  var $modal = $('#load_add_test_modal_popup');
  $modal.load('<?php echo base_url().'test/advance_report_result/' ?>'+booking_id+'/'+test_id,
  { 
    },
  function(){
  $modal.modal('show');
  });
}

 function openPrintWindow(url, name, specs) {
  var printWindow =  window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
}

$(document).ready(function() {
$('input:text:first').focus();
    
$('input:text').bind("keydown", function(e) {
   var n = $("input:text").length;
   if (e.which == 13) 
   { //Enter key
     e.preventDefault(); //to skip default behavior of the enter key
     var nextIndex = $('input:text').index(this) + 1;
     if(nextIndex < n)
       $('input:text')[nextIndex].focus();
     else
     {
       $('input:text')[nextIndex-1].blur();
       $('#btnSubmit').click();
     }
   }
});
 
}); 


 

function result_input()
{

  $('.is_formula').each(function()
  {
     var ids = $(this).attr('alt'); 
     var result = $('#result-'+ids).val();
     apply_formula(result,ids);
  });
}

function add_widal_value(booking_id,test_id)
{ 
  var $modal = $('#load_add_inventory_modal_popup');
  $modal.load('<?php echo base_url().'test/add_widal_value/'; ?>'+booking_id+'/'+test_id,
  {
  },
  function(){
  $modal.modal('show');
  });
}
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
  });
  
   $('.datepicker3').datetimepicker({
     format: 'LT'
  });
</script>
</div><!-- container-fluid -->
</body>
</html>
<div class="overlay-loader"><img src="<?php echo base_url().'assets/images/loader.gif';?>"></div>