<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users'); ?>
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

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
function reset_search(ele)
{ 
    $('#start_date').val('');
    $('#end_date').val('');
    $.ajax({url: "<?php echo base_url(); ?>inventory_vendor_report/reset_search/", 
      success: function(result)
      { 
        //reload_table();
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
</script>




</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>

<section class="userlist">

    <div class="userlist-box">
    <form name="search_form_list"  id="search_form_list"> 

    <div class="row">
        
       
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
              //print_r($permission_action);

            $new_branch_data=array();
           $users_data = $this->session->userdata('auth_users');
            $sub_branch_details = $this->session->userdata('sub_branches_data');
           
            
             ?>
             <?php //if(in_array('1',$permission_section)){ ?> 
          <div class="col-sm-3">
        
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Branch</label></div>
          <div class="col-xs-7">
              <select name="branch_id" id="branch_id" class="w-150px m_input_default" onchange="return form_submit();">
           
            <option value="">Select Branch</option>
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
        </div>
        </div>
        
<?php /*} else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php } */ ?>
        
        
        <div class="col-sm-3">
         <div class="row m-b-5">
          <div class="col-xs-5"><label>From Date</label></div>
          <div class="col-xs-7">
            <input id="start_date" name="start_date" class="w-150px datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div>
             
      </div> <!-- 4 -->

      <div class="col-sm-3">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date" class="w-150px datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
        

      </div> <!-- 4 -->

      <div class="col-sm-3">
        <a class="btn-custom" id="reset_date" onclick="reset_search(this.form);"><i class="fa fa-refresh"></i> Reset</a>
      </div> <!-- 4 -->
    </div> <!-- row -->
 </form>



    <form>
    <div class="hr-scroll">
      
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered patient_list_tbl" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center">S.No.</th> 
                    <th>Branch</th>
                    <th>Item Code</th>
                    <th>Item Name</th>
                    <th>Item Serial Number</th>
                    <th>Quantity</th>
                    <th>Vendor Name </th> 
                    
                    <th>Date</th>
                    
                </tr>

            </thead> 
             
        </table>
       
        </div>
    </form>


   </div> <!-- close -->





    <div class="userlist-right relative">
      <div class="fixed">
      <div class="btns">
        <a href="<?php echo base_url('inventory_vendor_report/vendor_rate_comparison_excel'); ?>" class="btn-anchor m-b-2"><i class="fa fa-file-excel-o"></i> Excel</a>
        <a href="<?php echo base_url('inventory_vendor_report/vendor_rate_comparison_pdf'); ?>" class="btn-anchor m-b-2"><i class="fa fa-file-pdf-o"></i> PDF</a>
        <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("inventory_vendor_report/vendor_rate_comparison_print"); ?>');"><i class="fa fa-print"></i> Print</a> 
        <button class="btn-update" onclick="reload_table()"><i class="fa fa-refresh"></i> Reload</button>
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
      </div>

    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script> 
function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('inventory_vendor_report/advance_search/'); ?>",
      type: "post",
      data: $('#search_form_list').serialize(),
      success: function(result) 
      {
        if(vals!='1')
        {
          
          reload_table();       
          $('#overlay-loader').hide();
        } 
      }
    }); 
}
form_submit('1');

var save_method; 
var table;
     $(document).ready(function() { 
         table = $('#table').DataTable({  
             "processing": true, 
             "serverSide": true,
             "bLengthChange": false, 
             "bFilter":true,
             "order": [], 
             "pageLength": '100',
             "ajax": {
                 "url": "<?php echo base_url('inventory_vendor_report/ajax_list')?>",
                 "type": "POST",
                 
             }, 
             "columnDefs": [
             { 
                 "targets": [ 0 , -1 ], //last column
                 "orderable": false, //set not orderable

             },
             ],
         });
        $('.tog-col').on( 'click', function (e) 
        {
          var column = table.column( $(this).attr('data-column') );
          column.visible( ! column.visible() );
        });

     }); 

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>

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
</script> 
<!-- Confirmation Box -->
</body>
</html>