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

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('1088',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('sales_vaccination/ajax_list')?>",
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
                      url: "<?php echo base_url('sales_vaccination/total_calc_return');?>",
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
 form_submit();

}); 
<?php } ?>



function edit_sales_medicine(id)
{
  
  window.location.href='<?php echo base_url().'sales_vaccination/edit/';?>'+id
  
  
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'sales_vaccination/view/' ?>'+id,
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
                      url: "<?php echo base_url('sales_vaccination/deleteall');?>",
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
    <?php
if(in_array('1088',$users_data['permission']['action'])) 
{
?>
    <?php if(isset($user_role) && $user_role==4 || $user_role==3)
    {
    }
    else
      {?>
<form id="new_search_form">
    
    <table class="ptl_tbl m-b-5">
      <tr>
        <td><label>From Date</label>
            <input type="text" name="start_date" id="start_date_s" class="datepicker m_input_defa"  onkeyup="return form_submit();" value="<?php echo $form_data['start_date'];?>"></td>
        <td><label>To Date</label>
            <input type="text" name="end_date" id="end_date_s" value="<?php echo $form_data['end_date'];?>" class="datepicker"  onkeyup="return form_submit();"></td>
        <td>
            <input value="Reset" class="btn-custom" onclick="reset_search(this.form)" type="button">
            <a href="javascript:void(0)" class="btn-custom" id="adv_search_sale"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a> 

        

        </td>
      </tr>
      <tr>
        <td><label>Billing No.</label>
            <input type="text" name="sale_no" class=""  id="sale_no" value="<?php echo $form_data['sale_no'];?>" onkeyup="return form_submit();" autofocus=""></td>
        
            <?php //print_r($doctors_list);?>
            <!-- <label>Referred By</label>
            <select class=" m5" name="refered_by" id="refered_by" onChange="return form_submit();">
            <option value="">Select</option>
            <?php foreach($doctors_list as $doctors) {?>
            <option value="<?php echo $doctors->id;?>" <?php if(isset($form_dataT['refered_by'])&& $form_data['refered_by']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
            <?php }?>
            </select>     -->
<?php if(in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])) { ?>
          <td>
            <div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="col-md-7" id="referred_by">
                   <label><input type="radio" name="referred_by" id="referredby" value="0" <?php if($form_data['referred_by']==0){ echo 'checked="checked"'; } ?>> Doctor</label> &nbsp;
                    <label><input type="radio" name="referred_by" id="referredby" value="1" <?php if($form_data['referred_by']==1){ echo 'checked="checked"'; } ?>> Hospital</label>
                    <?php if(!empty($form_error)){ echo form_error('referred_by'); } ?>
            </div>
        </div>      
        </td>
       <td>
        <div class="row m-b-5" id="doctor_div" <?php if($form_data['referred_by']==0){  }else{ ?> style="display: none;" <?php  } ?> >
            <div class="">
                <label>Referred By</label>
            </div>
            <div class="col-md-7">
                <select class="w-150px" name="refered_id" id="refered_id" onchange="return form_submit();">
                    <option value="">Select Doctor</option>
                    <?php foreach($doctors_list as $doctors) {?>
                    <option value="<?php echo $doctors->id;?>" <?php if($form_data['refered_id']==$doctors->id){ echo 'selected';}?>><?php echo $doctors->doctor_name;?></option>
                    <?php }?>
                </select>
                   

            </div>
        </div> <!-- innerrow -->

        <div class="row m-b-5" id="hospital_div" <?php if($form_data['referred_by']==1){  }else{ ?> style="display: none;" <?php  } ?>>
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="">
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
        </div> <!-- innerrow -->

    
        </td>

        <?php } else if(in_array('38',$users_data['permission']['section']) && !in_array('174',$users_data['permission']['section']))
        { 

          ?>

          <td>
        

        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="">
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
        </div> <!-- innerrow -->
<input type="hidden" name="referred_by" value="0">
        <input type="hidden" name="referral_hospital" value="0">
    
        </td>

          <?php 
          }else if(!in_array('38',$users_data['permission']['section']) && in_array('174',$users_data['permission']['section'])){

          
            ?>
            <td>
        

        <div class="row m-b-5">
            <div class="col-md-5">
                <label>Referred By</label>
            </div>
            <div class="">
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
        </div> <!-- innerrow -->

        <input type="hidden" name="referred_by" value="1">
        <input type="hidden" name="refered_id" value="0">
        </td>
            <?php 
           } 

           ?>
      </tr>
      <tr>
        <td>
            <label>Paid Amount</label>
            <input type="text" name="paid_amount_to" class="w-90px"  id="paid_amount_to" value="<?php echo $form_data['paid_amount_to'];?>" onkeyup="return form_submit();"> To
            <input type="text" name="paid_amount_from" id="paid_amount_from" value="<?php echo $form_data['paid_amount_from'];?>" class="w-90px"  onkeyup="return form_submit();">
        </td>
        <td>
            <label>Balance</label>
            <input type="text" name="balance_to" id="balance_to" value="<?php echo $form_data['balance_to'];?>" class="w-90px"  onkeyup="return form_submit();"> To
            <input type="text" name="balance_from" id="balance_from" value="<?php echo $form_data['balance_from'];?>" class="w-90px" onKeyUp="return form_submit();">
        </td>
        <td><?php  
              $users_data = $this->session->userdata('auth_users'); 

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
              <label>Select Branch</label>
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
            <?php endif;?></td>
      </tr>

    </table>

 <!-- sale_fields -->
    </form>
    <?php } }?>
       
    <form>
       <?php if(in_array('1088',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered sales_medicine_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Billing No.</th> 
                    <th> Patient Name </th> 
                    <th> Referred By</th>  
                   <!-- <th> Total Amount</th> -->
                    <th> Net Amount </th> 
                    <th>Paid Amount</th> 
                    <th>Balance</th> 
                    <th> Billing Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>

    </form>


   </div> <!-- close -->





    <div class="userlist-right">
      <div class="btns">
               <?php if(in_array('1089',$users_data['permission']['action'])) {
               ?>
             <button class="btn-update" onClick="window.location.href='<?php echo base_url('sales_vaccination/add'); ?>'">
              <i class="fa fa-plus"></i> New
             </button>
               <?php } ?>
               <?php if(in_array('1088',$users_data['permission']['action'])) {
               ?>

                <a href="<?php echo base_url('sales_vaccination/vaccination_sales_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>

                <a href="<?php echo base_url('sales_vaccination/vaccination_sales_csv'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-word-o"></i> CSV
                </a>

                <a href="<?php echo base_url('sales_vaccination/pdf_vaccination_sales'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-pdf-o"></i> PDF
                </a>

               <!--  <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintnewWindow('< ?php echo base_url("sales_vaccination/print_medicine_sales"); ?>', 'windowTitle', 'width=820,height=600');">
                <i class="fa fa-print"></i> Print
                </a> -->

                <!--  <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintnewWindow('< ?php echo base_url("sales_vaccination/print_medicine_sales"); ?>', 'windowTitle', 'width=820,height=600');">
                <i class="fa fa-print"></i> Print
                </a> -->

                 <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("sales_vaccination/print_vaccination_sales"); ?>');"> <i class="fa fa-print"></i> Print</a>

             <button class="btn-update" id="deleteAll" onClick="return checkboxValues();">
              <i class="fa fa-trash"></i> Delete
             </button>
               <?php } ?>
               <?php if(in_array('1088',$users_data['permission']['action'])) {
               ?>

                    <button class="btn-update" onClick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('1094',$users_data['permission']['action'])) {
               ?>
             <button class="btn-exit" onClick="window.location.href='<?php echo base_url('sales_vaccination/archive'); ?>'">
              <i class="fa fa-archive"></i> Archive
             </button>
               <?php } ?>
                
        <button class="btn-exit" onClick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->
   <!-- cbranch-rslt close -->
  <!-- cbranch-rslt close -->
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

function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>sales_vaccination/reset_search/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                case 'select-multiple':
                case 'select-one':
                case 'text':
                case 'textarea':
                $(this).val('');
                break;
                case 'checkbox':
                case 'radio':
                this.checked = false;
                }
          });
        reload_table();
      } 
  }); 

}

$(document).ready(function(){
  reload_table();
  $('#selectAll').on('click', function () {
 // alert(); 
    if ($("#selectAll").hasClass('allChecked')) {

    $('.checklist').prop('checked', false);
    } else {
    $('.checklist').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
  });
});  

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
    $.ajax({url: "<?php echo base_url(); ?>sales_vaccination?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }
 function delete_sales_medicine(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('sales_vaccination/delete/'); ?>"+id, 
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
        window.location.href='<?php echo base_url('sales_vaccination');?>'; 
    }) ;
   
       
  <?php }?>
 });

/*function openPrintWindow(url, name, specs) {
   if(url==123){
    url='< ?php echo base_url("sales_vaccination/print_sales_report"); ?>/'+name;
    name='windowTitle';
    specs='width=820,height=600';
     var printWindow = window.open(url, name, specs);
  }else{
     var printWindow = window.open(url, name, specs);
  }
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
            window.location.href='< ?php echo base_url('sales_vaccination');?>';
           // alert('< ?php echo $this->session->userdata('sales_id');?>');
        }
    }
    var sched = setInterval(printAndClose, 200);
};

 function openPrintnewWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
};*/
$(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
    $('.searchFocus').focus();
  });
});

</script>

<!-- Confirmation Box -->

<div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <!-- <a type="button" data-dismiss="modal" class="btn-anchor" id="delete" onClick="return openPrintWindow('< ?php echo base_url("sales_vaccination/print_sales_report"); ?>', 'windowTitle', 'width=820,height=600');" target="_blank">Print</a> -->

            <a  type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("sales_vaccination/print_sales_report"); ?>');">Print</a>

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


    
<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_sale').on('click', function(){
$modal.load('<?php echo base_url().'sales_vaccination/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 

  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>/sales_vaccination/advance_search/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
});

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
      form_submit();
  });*/

   var today =new Date();
    $('#start_date_s').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_s").datepicker("option", "minDate", selected);
      }
    })

    $('#end_date_s').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_s").datepicker("option", "maxDate", selected);
      }
    })

</script>


<!----container-fluid--->
</body>
</html>

