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
			<?php if(!empty($mandatory_sections)){
				$j=1;
				foreach($mandatory_sections as $mandatory_section){  ?>
					
				 	<tr>
					   
					    <td id="section_"><?php echo $mandatory_section; ?></td>
					    <td>
				       	    <div class="grp_box">
				                <?php  $field_list = mandatory_section_field_list($j);
				                    
				                if(!empty($field_list)){?>
				       	        <?php   
				       	            
				                    foreach($field_list as $field_lists){
				                        if($field_lists['none_mandatory_field_id']==$field_lists['mandatory_field_id'] && $field_lists['mandatory_branch_id']==$users_data['parent_id']){
				                        	$mandatory_checked = 'checked="checked"';?>
				                            <div class="grp">
				                                <label><?php echo $field_lists['required_field_name']; ?> <input class="section_<?php echo $j; ?> fa fa-check-square" type="checkbox" value = "<?php echo $field_lists['mandatory_field_id']; ?>"  <?php echo $mandatory_checked; ?> id="<?php echo $j; ?>" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php
				                        }else if($field_lists['none_mandatory_field_id']!==$field_lists['mandatory_field_id']){
				                        	$mandatory_checked = '';?>
				                            <div class="grp">
				                                <label><?php print_r($field_lists['required_field_name']); ?> <input class="section_<?php echo $j; ?>" type="checkbox" value = "<?php echo $field_lists['none_mandatory_field_id']; ?>" <?php echo $mandatory_checked; ?> id="<?php echo $j; ?>" name="<?php echo $field_lists['required_field_name']; ?>"> </label>
				                            </div>
				                        <?php }
				                    }
				                }?>
				            </div>
				        </td>
					</tr>
				<?php $j++; }
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
		var section_ids = [1,2,3,4,5,6];
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