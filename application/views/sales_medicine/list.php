<?php
$users_data = $this->session->userdata('auth_users');
$user_role= $users_data['users_role'];
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
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
<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript">
function edit_sales_medicine(id)
{
    
     window.open('<?php echo base_url().'sales_medicine/edit/';?>'+id, '_blank');
  //window.location.href='< ?php echo base_url().'sales_medicine/edit/';?>'+id
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'sales_medicine/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
   });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


 
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

function allbranch_delete(allVals)
 {    
   if(allVals!="")
   {
       $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        { 
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('sales_medicine/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   } 
   else{
      $('#confirm-select').modal({
          backdrop: 'static',
          keyboard: false
        });
   }
 }
 
 function reset_search()
  { 
    $('#start_date_s').val('');
    $('#end_date_s').val('');
    $('#paid_amount_from').val('');
    $('#paid_amount_to').val('');
    $('#balance_to').val('');
    $('#balance_from').val('');
    $('#purchase_no').val('');
    $('#refered_by').val('');
    $('#branch_id').val('');
    $('#refered_id').val('');
    $('#referral_hospital').val('');
     $('#referredby').attr('checked', false);
    $.ajax({url: "<?php echo base_url(); ?>sales_medicine/reset_search/", 
      success: function(result)
      { 
        reload_table();
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
    <div class="userlist-box">
    <?php if(isset($user_role) && $user_role==4 || $user_role==3)
    {
    }
    else
      {?>
<form id="new_search_form">
        <div class="row">
				<div class="col-md-4">

				<div class="row m-b-5">
						<div class="col-md-4">
							<label>From Date</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="start_date" id="start_date_s" class="datepicker start_datepicker m_input_defa"  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'];?>">
						</div>
				</div>
					
					<div class="row m-b-5">
						<div class="col-md-4">
							<label>To Date</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="end_date" id="end_date_s" value="<?php echo $form_data['end_date'];?>" class="datepicker end_datepicker"  onkeyup="return form_submit();">
						</div>
					</div>
					
					<div class="row m-b-5">
						<div class="col-md-4">
							<label>Sale No.</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="sale_no" class=""  id="sale_no" value="<?php echo $form_data['sale_no'];?>" onkeyup="return form_submit();" autofocus="">
						</div>
					</div>

          <div class="row m-b-5">
						<div class="col-md-4">
							<label>Diseases</label>
						</div>
						<div class="col-md-8">
                <select name="diseases" id="disease_id" onchange="return form_submit();" class=" m_select_btn">
                      <option value="">Select Diseases</option>
                      <?php
                      if(!empty($diseases_list))
                      {
                        foreach($diseases_list as $diseases)
                        {
                          ?>
                            <option <?php if($form_data['diseases']==$diseases->id){ echo 'selected="selected"'; } ?> value="<?php echo $diseases->id; ?>"><?php echo $diseases->disease; ?></option>
                            
                          <?php
                        }
                      }
                      ?>
                  </select> 
						</div>
					</div>
				
				</div>
			
				
				
				<div class="col-md-4">
				
					<div class="row m-b-5">
					
						<?php  
						  $users_data = $this->session->userdata('auth_users'); 
                            $user_data = $this->session->userdata('auth_users'); 
						  if (array_key_exists("permission",$users_data)){
						  $permission_section = $users_data['permission']['section'];
						  $permission_action = $users_data['permission']['action'];
						  }
						  else{
						  $permission_section = array();
						  $permission_action = array();
						  }
						  //print_r($permission_action);

						  $new_branch_data=array();
						  $users_data = $this->session->userdata('auth_users');
						  $sub_branch_details = $this->session->userdata('sub_branches_data');
						  $parent_branch_details = $this->session->userdata('parent_branches_data');


						  if(!empty($users_data['parent_id'])){
						  $new_branch_data['id']=$users_data['parent_id'];

						  $users_new_data[]=$new_branch_data;
						  $merg_branch= array_merge($users_new_data,$sub_branch_details);

						  $ids = array_column($merg_branch, 'id'); 
						  $branch_id = implode(',', $ids); 
						  $option= '<option value="'.$branch_id.'">All</option>';
						  }

						  ?>
						  <?php if(in_array('1',$permission_section)): ?> 
						<div class="col-md-4">
							<label>Select Branch</label>
						</div>
						<div class="col-md-8">
							<select name="branch_id" id="branch_id" onchange="return form_submit();">
								  <?php echo $option ;?>
								  <option  selected="selected" <?php if(isset($_POST['branch_id']) && $_POST['branch_id']==$users_data['parent_id']){ echo 'selected="selected"'; } ?> value="<?php echo $users_data['parent_id'];?>">Self</option>';
								  <?php 
								  if(!empty($sub_branch_details)){
								  $i=0;
								  foreach($sub_branch_details as $key=>$value){
								  ?>
								  <option value="<?php echo $sub_branch_details[$i]['id'];?>" <?php if(isset($_POST['branch_id'])&& $_POST['branch_id']==$sub_branch_details[$i]['id']){ echo 'selected="selected"'; } ?> ><?php echo $sub_branch_details[$i]['branch_name'];?> </option>
								  <?php 
								  $i = $i+1;
								  }

								  }
								  ?> 
							</select>
						
						</div>
						<?php endif;?>
					</div>
					
					<?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
					<div class="row m-b-5">
				
						<div class="col-md-4">
							<label>Referred By</label>
						</div>
						<div class="col-md-8" id="referred_by">
							<label><input type="radio" name="referred_by" id="referredby" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor</label> &nbsp;
						<label><input type="radio" name="referred_by" id="referredby" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital</label>
						<?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
						</div>
					</div>
					
					<div class="row m-b-5"  id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?>>
						<div class="col-md-4">
							<label>Referred By</label>
						</div>
						<div class="col-md-8">
							<select class="w-200px" name="refered_id" id="refered_id" onchange="return form_submit();">
								<option value="">Select Doctor</option>
								<?php foreach($doctors_list as $doctors) {?>
								<option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
								<?php }?>
							</select>
						</div>
					</div>
          <div class="row m-b-5">
						<div class="col-md-4">
							<label>Category</label>
						</div>
						<div class="col-md-8">
                <select name="patient_category" id="patient_category" onchange="return form_submit();" class=" m_select_btn">
                      <option value="">Select Category</option>
                      <?php
                      if(!empty($patient_category_list))
                      {
                        foreach($patient_category_list as $category_list)
                        {
                          ?>
                            <option <?php if($form_data['patient_category']==$category_list->id){ echo 'selected="selected"'; } ?> value="<?php echo $category_list->id; ?>"><?php echo $category_list->patient_category; ?></option>
                            
                          <?php
                        }
                      }
                      ?>
                  </select> 
						</div>
					</div>
					
					<div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
						<div class="col-md-4">
							<label>Referred By</label>
						</div>
						<div class="col-md-8">
							<select name="referral_hospital" id="referral_hospital" class="m_input_default" onchange="return form_submit();">
								  <option value="">Select Hospital</option>
								  <?php
								  if(!empty($referal_hospital_list))
								  {
									foreach($referal_hospital_list as $referal_hospital)
									{
									  ?>
										<option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
										
									  <?php
									}
								  }
								  ?>

								  
							</select> 
						</div>
					</div>
					<?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section']))
					{ 

					?>
					<div class="row m-b-5">
						<div class="col-md-4">
							<label>Referred By</label>
						</div>
						<div class="col-md-8">
							 <select name="referral_hospital" id="referral_hospital" class="m_input_default" onchange="return form_submit();">
								  <option value="">Select Hospital</option>
								  <?php
								  if(!empty($referal_hospital_list))
								  {
									foreach($referal_hospital_list as $referal_hospital)
									{
									  ?>
										<option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
										
									  <?php
									}
								  }
								  ?>

								  
							</select> 
						</div>
					</div>
					<input type="hidden" name="referred_by" value="0">
					<input type="hidden" name="referral_hospital" value="0">
					
					<?php 
          }else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){

          
            ?>
					
					
					<div class="row m-b-5">
						<div class="col-md-4">
							<label>Referred By</label>
						</div>
						<div class="col-md-8">
							<select name="referral_hospital" id="referral_hospital" class="m_input_default" onchange=		"return form_submit();">
							  <option value="">Select Hospital</option>
							  <?php
							  if(!empty($referal_hospital_list))
							  {
								foreach($referal_hospital_list as $referal_hospital)
								{
								  ?>
									<option <?php if($form_data['referral_hospital']==$referal_hospital->id){ echo 'selected="selected"'; } ?> value="<?php echo $referal_hospital->id; ?>"><?php echo $referal_hospital->hospital_name; ?></option>
									
								  <?php
								}
							  }
							  ?>

							  
							</select> 
						</div>
					</div>
          
					<input type="hidden" name="referred_by" value="1">
					<input type="hidden" name="refered_id" value="0">
		  <?php } ?>
				
				
				
			</div>  
				
				<div class="col-md-4">
					<div class="row m-b-5">
						<div class="col-md-12">
						<a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a>	<a href="javascript:void(0)" class="btn-a-search" id="adv_search_sale"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> 
						</div>
						
					</div>
					
					<div class="row m-b-5">
						<div class="col-md-4">
							<label>Paid Amount</label>
            
						</div>
						<div class="col-md-8">
							<input type="text" name="paid_amount_to" class="w-90px"  id="paid_amount_to" value="<?php echo $form_data['paid_amount_to'];?>" onkeyup="return form_submit();"> To
							<input type="text" name="paid_amount_from" id="paid_amount_from" value="<?php echo $form_data['paid_amount_from'];?>" class="w-90px"  onkeyup="return form_submit();">
						</div>
					</div>
					
					<div class="row m-b-5">
						<div class="col-md-4">
							<label>Balance</label>
            
						</div>
						<div class="col-md-8">
							<input type="text" name="balance_to" id="balance_to" value="<?php echo $form_data['balance_to'];?>" class="w-90px"  onkeyup="return form_submit();"> To
							<input type="text" name="balance_from" id="balance_from" value="<?php echo $form_data['balance_from'];?>" class="w-90px" onKeyUp="return form_submit();">
						</div>
					</div>
				</div>
			</div>
			
			
	
	
	 </form>
    <?php }?>
    	 
    <form>
       <?php if(in_array('399',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered sales_medicine_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Sale No.</th>  
                    <th> Patient Name </th> 
                    <th> Address </th> 
                    <th> Referred By</th>  
                   <!-- <th> Total Amount</th> -->
                    <th> Net Amount </th> 
                    <th>Paid Amount</th> 
                    <th>Balance</th> 
                    <th> Sale Date </th> 
                    <th>Diseases</th>
                    <th> Category </th>
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>

    </form>


   </div> <!-- close -->





  	<div class="userlist-right">
  		<div class="btns">
               <?php if(in_array('400',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onClick="window.location.href='<?php echo base_url('sales_medicine/add'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
               <?php if(in_array('402',$users_data['permission']['action'])) {
               ?>

                <a href="<?php echo base_url('sales_medicine/medicine_sales_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>

                <a href="<?php echo base_url('sales_medicine/medicine_sales_csv'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-word-o"></i> CSV
                </a>

                <a href="<?php echo base_url('sales_medicine/pdf_medicine_sales'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-pdf-o"></i> PDF
                </a>
                 <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("sales_medicine/print_medicine_sales"); ?>');"> <i class="fa fa-print"></i> Print</a>

  			     <button class="btn-update" id="deleteAll" onClick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php } ?>
               <?php if(in_array('399',$users_data['permission']['action'])) {
               ?>

                    <button class="btn-update" onClick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('403',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-exit" onClick="window.location.href='<?php echo base_url('sales_medicine/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               <?php } ?>
                
        <button class="btn-exit" onClick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
  	</div> 
  	<!-- right -->
 
   <div class="sale_medicine_bottom">
        <div class="left">
            <div class="right_box">
                <div class="sale_medicine_mod_of_payment">
                    <label>Total Net Amount</label>
                    <input type="text" id="total_net_amount" value="0.00"  readonly="" />
                </div>
               <div class="sale_medicine_mod_of_payment">
                 <label>Total Paid Amount</label>
                  <input type="text" id="total_paid_amount" value="0.00"  readonly="" />
                </div>
                <div class="sale_medicine_mod_of_payment">
                    <label>Total Balance</label>
                    <input type="text" id="total_balance" value="0.00"  readonly="" />
                </div>
                

            </div> <!-- right_box -->

            
        </div> <!-- left -->
      
    </div> <!-- sale_medicine_bottom -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>  
$('.start_datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker').val();
      $('.end_datepicker').datepicker('setStartDate', start_data); 
      form_submit();
  });

  $('.end_datepicker').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     form_submit();
  });
  

function form_submit(vals)
{
    var start_date = $('#start_date_s').val();
    var end_date = $('#end_date_s').val();
    var branch_id = $('#branch_id').val();
    var sale_no = $('#sale_no').val();
    var refered_id = $('#refered_id').val();
    var referred_by = $("input[name='referred_by']:checked").val();
    var disease_id = $("#disease_id").val();
    var  patient_category = $("#patient_category").val();
 
  $.ajax({
       url: "<?php echo base_url(); ?>sales_medicine/advance_search/",
       type: 'POST',
       data: { start_date: start_date, end_date : end_date,branch_id:branch_id,refered_id:refered_id,sale_no:sale_no,referred_by:referred_by,disease_id:disease_id,patient_category:patient_category} ,
         
    success: function(result) 
    {
      
      if(vals!="1")
      {
        reload_table(); 
      }
      
    }
  });

}

form_submit(1);
var save_method; 
var table;
<?php
if(in_array('399',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('sales_medicine/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
         ],
        "drawCallback": function() 
        {
            $.ajax({
                      dataType: "json",
                      url: "<?php echo base_url('sales_medicine/total_calc_return');?>",
                      success: function(result) 
                      {
                        $('#total_net_amount').val(result.net_amount);
                        $('#total_discount').val(result.discount);
                        $('#total_balance').val(result.balance);
                        $('#total_vat').val(result.vat);
                        $('#total_paid_amount').val(result.paid_amount);
                      }
                  });
        },

    });
 //form_submit();

}); 
<?php } ?>  
  
$(document).ready(function() {
    $("input[name$='referred_by']").click(function() 
    {
      var test = $(this).val();
      if(test==0)
      {
        $("#hospital_div").hide();
        $("#doctor_div").show();
        $('#referral_hospital').val('');
        
      }
      else if(test==1)
      {
          $("#doctor_div").hide();
        
          $("#hospital_div").show();
          $('#refered_id').val('');
          //$("#refered_id :selected").val('');
      }
        
    });
});

 function delete_sales_medicine(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('sales_medicine/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 } 
</script> 
  <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script> ';
   ?>
   <script>  
    //$('.dlt-modal').modal('show'); 
    </script> 
    <?php
 }
?>

<script>
$('documnet').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('sales_medicine');?>'; 
    }) ;
   
       
  <?php }?>
 });

$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
    $('.searchFocus').focus();
  });
});

</script>

<div id="confirm-select" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Please select at-least one record.</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal --> 


<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("sales_medicine/print_sales_report"); ?>');">Print</a>

            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> 

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div> 
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
            <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
          </div>
        </div>
      </div>  
    </div> <!-- modal -->

    
<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_sale').on('click', function(){
$modal.load('<?php echo base_url().'sales_medicine/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

    function print_label(id)
	{
	  var $modal = $('#load_add_ipd_label_summary_print_modal_popup');
	  $modal.load('<?php echo base_url().'sales_medicine/print_template/'; ?>'+id,
	  {
	    //'id1': '1',
	    //'id2': '2'
	    },
	  function(){
	  $modal.modal('show');
	  });
	}

</script>

<div id="load_add_ipd_label_summary_print_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!----container-fluid--->
</body>
</html>