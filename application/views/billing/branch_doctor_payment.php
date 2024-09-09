<?php
$users_data = $this->session->userdata('auth_users');
$company_data = $this->session->userdata('company_data');
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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>buttons.bootstrap.min.css">

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>



<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script> 

<script type="text/javascript">
 
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function reset_date_search()
  {
      $('#start_date').val('');
      $('#end_date').val('');
      $.ajax({
         url: "<?php echo base_url('billing/reset_date_search/'); ?>",  
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
<form id="branch_doctor_form"> 
    <?php
if($users_data['users_role']!=3)
{
?> 
<!-- // -->
          <div class="grp_box">
               <div class="grp">
                  <?php if(!empty($sub_branch))
                  {
                  ?>
                  <input type="radio"  name="types" value="0" onClick="return type_date(this.value);" <?php if(!empty($sub_branch)){ echo 'checked=""'; } ?>> <b>Branch</b>
                  <?php
                  }
                  ?>
                  <input type="radio"  name="types" value="1"  onclick="return type_date(this.value);" <?php if(empty($sub_branch)){ echo 'checked=""'; } ?>> <b>Doctor</b>
            
               </div> <!-- 2 -->


               <div class="grp" id="type_box">
                  
                    <?php
                     if(!empty($sub_branch))
                     {
                     ?>
                        <label><b>Branch:</b></label>  
                        <select class="sub_branch" name="branch_id" id="branch_id" onChange="return search_record(0,this.value);">
                            <option value="">Select Branch</option> 
                            <?php
                            foreach($sub_branch as $branch)
                            {
                             ?>
                              <option value="<?php echo $branch['id']; ?>"><?php echo $branch['branch_name']; ?></option>
                             <?php  
                            }
                            ?>
                        </select>
                     <?php
                     }
                     else
                     { 
                     echo  '<select name="doctor_id" id="doctor_id" onchange="return search_record(1,this.value);"><option value="">Select Doctor</option>';
                      if(!empty($doctor_list))
                      {
                        foreach($doctor_list as $doctor)
                        {
                           echo '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
                        }
                      }
                     echo '</select>';
                     }
                    ?>
               </div> <!-- 2 -->


               <div class="grp">
                  <label><b>From Date:</b></label>
                  <input type="text" id="start_date" name="from_date" class="datepicker" value="<?php echo $form_data['start_date']; ?>">          
               </div> <!-- 2 -->


               <div class="grp">
                  <label><b>To Date:</b></label>
                  <input type="text" name="to_date" id="end_date" class="datepicker" value="<?php echo $form_data['end_date']; ?>">
                  <a class="btn-custom" id="reset_date" onClick="reset_date_search();">
                    <i class="fa fa-refresh"></i> Reset
                  </a> 
               </div> <!-- 2 -->

           </div> <!-- innerRow -->


<?php
}
?>






           <div class="row m-t-5">
             <div class="col-xs-11 p-0">
                  <!-- bootstrap data table -->
                  <table id="table" class="table table-striped table-bordered branch_doctor_list" cellspacing="0" width="100%">
                      <thead class="bg-theme">
                          <tr>  
                              <th> Doctor/Branch name </th> 
                              <th> Amount To be Received </th>  
                              <th> Recieved Amount </th> 
                          </tr>  
                      </thead>  
                      <tbody>
                          <tr>
                              <td colspan="3" align="center" class="text-danger"><div class="text-center">No record found.</div></td>
                          </tr> 
                      </tbody>
                  </table> 
              
              
                  <table id="table" class="table table-striped table-bordered bdp_tbl1" cellspacing="0" width="100%"> 
                    <tbody>
                      <tr>
                        <td>Amount To be Received <input type="text" name="total_due" id="total_due" value="" readonly="" /></td>
                        <td>Recieved Amount <input type="text" name="rec_amount" id="rec_amount" value=""  readonly="" /></td>
                        <td>Balance <input type="text" name="balance" id="balance" value=""  readonly="" /></td>
                      </tr> 
                      <tr>
                        <td colspan="4">
                          <table id="inner_table" class="table table-striped table-bordered bdp_tbl2 " cellspacing="0" width="100%" style="display:none;">
                            <tr>
                              <td valign="top">Mode of payment 
                                 <select name="pay_mode" id="pay_mode" onChange="return pay_fields(this.value);">
                                      <?php foreach($payment_mode as $payment_mode) 
                                      {?>
                                      <option value="<?php echo $payment_mode->id;?>" ><?php echo $payment_mode->payment_mode;?></option>
                                      <?php }?>
                                    </select>
                              </td>
                              <td valign="top">Amount to receive <input type="text" name="pay_amount" id="pay_amount" value=""/></td>
                              <td valign="top"><div id="pay_box" class="pay_box"></div></td>
                              <td valign="top"><button type="submit" name="submit" id="submit" class="btn-update"> <i class="fa fa-floppy-o"></i> Save</button></td>
                            </tr>
                          </table>
                        </td>
                      </tr> 
                    </tbody>
                  </table> 
             </div> <!-- 10 -->

             <div class="col-xs-1 p-0 p-l-5">
                <div class="b_doc_pay"> 
                  <a href="javascript:void(0)" onClick="return db_comission_list();" class="btn-anchor m-b-2">
                      <i class="fa fa-file-excel-o"></i> Commission
                  </a>

                  <a href="javascript:void(0)" onClick="return db_detail_list();" class="btn-anchor m-b-2">
                      <i class="fa fa-file-word-o"></i> Details
                  </a>
                  <?php
              if($users_data['users_role']!=3)
              {
              ?> 
                  <a href="javascript:void(0)" onClick="return receive_now(1)" class="btn-anchor m-b-2 h-auto">
                      <i class="fa fa-file-pdf-o"></i> Receive Now
                  </a> 
                  <?php
              }
              ?>
                  <button class="btn-exit" onClick="window.location.href='<?php echo base_url(); ?>'">
                      <i class="fa fa-sign-out"></i> Exit
                  </button>
                </div>
             </div> <!-- 2 -->
           </div> <!-- MainRow -->












    






       
      
	 </form>
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?> 
<script type="text/javascript">
function db_detail_list()
{
	
  <?php
  if($users_data['users_role']==3)
  {
    echo "var doctor_id = ".$users_data['parent_id'].";
    var branch_id = 0;
    var start_date = '';
    var end_date = '';";  
  }
  else
  {
  ?>
  var doctor_id = $('#doctor_id').val();
	var branch_id = $('#branch_id').val();
	var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    <?php
  }
  ?>
	if(branch_id>0)
	  {
		var types = 0;
		var ids = branch_id;
	  }
	else if(doctor_id>0)
	  {
		var types = 1;
		var ids = doctor_id;
	  } 
	   
   window.open('<?php echo base_url('billing/db_detail_list?type=') ?>'+types+'&ids='+ids+'&start_date='+start_date+'&end_date='+end_date,'mywin','width=800,height=600');
}

function db_comission_list()
{
  <?php
  if($users_data['users_role']==3)
  {
    echo "var doctor_id = ".$users_data['parent_id'].";
    var branch_id = 0;
    var start_date = '';
    var end_date = '';";  
  }
  else
  {
  ?>

	var doctor_id = $('#doctor_id').val();
	var branch_id = $('#branch_id').val();
	var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  <?php
  }
  ?>
	if(branch_id>0)
	  {
		var types = 0;
		var ids = branch_id;
	  }
	else if(doctor_id>0)
	  {
		var types = 1;
		var ids = doctor_id;
	  } 
	   
   window.open('<?php echo base_url('billing/db_comission_list?type=') ?>'+types+'&ids='+ids+'&start_date='+start_date+'&end_date='+end_date,'mywin','width=800,height=600');
}

$("#branch_doctor_form").on("submit", function(event) { 
  event.preventDefault();  
  $.ajax({
    url: "<?php echo base_url('billing/index'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
	{
	    var doctor_id = $('#doctor_id').val();
		var branch_id = $('#branch_id').val();
	    if(branch_id>0)
		  {
			var types = 0;
			var ids = branch_id;
		  }
		else if(doctor_id>0)
		  {
			var types = 1;
			var ids = doctor_id;
		  } 
	   search_record(types,ids);
	   receive_now(0);
	   flash_session_msg('Payment successfully updated.');
    }
  });
}); 

function receive_now(vals)
{  
  if(vals==1)
  {
    $('#inner_table').slideDown();
  }
  else
  {
    $('#inner_table').attr('style','display:none');
  }
} 

function type_date(vals)
{
  $.ajax({
         url: "<?php echo base_url('billing/bdp_type_html/'); ?>"+vals, 
         type: 'POST', 
         success: function(result)
         { 
            $('#type_box').html(result);
         }
      });    
}
  
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var doctor_id = $('#doctor_id').val();
    var branch_id = $('#branch_id').val();
    $.ajax({
           url: "<?php echo base_url('billing/search_record/'); ?>", 
           type: 'POST',
           data: { start_date: start_date, end_date : end_date, doctor_id : doctor_id, branch_id : branch_id} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
    $("#end_date").datepicker({ minDate: selectedDate });
}); 

function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
}

function search_record(types,vals)
{
  
  <?php
  if($users_data['users_role']==3)
  {
  ?>
  var doctor_id = vals;
  var branch_id = '';
  var start_date = '';
  var end_date = '';
  <?php  
  }
  else
  {
  ?>

  if(types==1)
  {
    var doctor_id = vals;
    var branch_id = '';
  }
  else if(types==0)
  {
    var branch_id = vals;
    var doctor_id = '';
  }
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  <?php  
  }
  ?>


  /*if(types==1)
  {
    var doctor_id = vals;
    var branch_id = '';
  }
  else if(types==0)
  {
    var branch_id = vals;
    var doctor_id = '';
  }

  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();*/

  $.ajax({
           url: "<?php echo base_url('billing/search_record/'); ?>", 
            type: 'POST',
            dataType:'json',
           data: { 'start_date': start_date, 'end_date' : end_date,  'branch_id' : branch_id,  'doctor_id' : doctor_id} ,
           success: function(result)
           { 
              $('#table').html(result.data); 
              $('#total_due').val(result.total_due); 
              $('#rec_amount').val(result.rec_amount); 
              $('#balance').val(result.balance); 
              receive_now();
           }
        }); 
}

function pay_fields(vals)
{
  // if(vals==2)
  // {
  //   $('#pay_box').html('<div style="width:100%; float:left;">Name <input type="text" name="bank_name" value="" /></div> <div style="width:100%; float:left;"> Cheque No. <input type="text" name="cheque_no" value="" /></div> <div style="width:100%; float:left;"> Date <input type="text" name="cheque_date" value="" class="datepicker" /></div>');
  // }
  // else if(vals==3)
  // {
  //   $('#pay_box').html('<div style="width:100%; float:left;">Transection no. <input type="text" name="transection_no" value="" /></div>');
  // }
  // else if(vals==4)
  // {
  //   $('#pay_box').html('<div style="width:100%; float:left;">Transection no. <input type="text" name="transection_no" value="" /></div>');
  // }
  // else  
  // {
  //   $('#pay_box').html(' ');
  // } 
    $('#updated_payment_detail').html('');
    $.ajax({
          type: "POST",
          url: "<?php echo base_url('billing/get_payment_mode_data')?>",
          data: {'payment_mode_id' : vals},
          success: function(msg){
          $('#pay_box').html(msg);
          }
    });
     
  
  $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  });
}
</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>