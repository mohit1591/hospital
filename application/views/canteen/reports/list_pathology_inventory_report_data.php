<?php
$users_data = $this->session->userdata('auth_users');
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
<link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-multiselect.css" type="text/css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-multiselect.js"></script>


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
         url: "<?php echo base_url('pathology_inventory_reports/reset_date_search/'); ?>",  
         success: function(result)
         { 
          reload_table(); 
         }
      });  
  }

$(document).ready(function(){
var $modal = $('#load_advance_search_modal_popup');
$('#advance_search').on('click', function(){
$modal.load('<?php echo base_url().'pathology_inventory_reports/advance_search/' ?>',
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
var $modal = $('#load_advance_search_modal_popup');
$('#advance_report').on('click', function(){
$modal.load('<?php echo base_url().'pathology_inventory_reports/advance_report/' ?>',
{
//'id1': '1',
//'id2': '2'
},
function(){
$modal.modal('show');
});

});

});
</script>

</head>

<body onload="form_submit()">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box "> 
    <form> 

    		<div class="grp_box m-b-5">
    					<div class="grp" id="selection_criteria">Searching Criteria 
  				        <select id="item_name" name="item_name" onchange="form_submit();">
                  <option>Select Item Name</option>
                    <?php if(!empty($item_list))
                    { 
                      foreach($item_list as $item_name)
                      {
                       ?>
                        <option value="<?php echo $item_name->id;?>"><?php echo $item_name->item;?></option>

                        <?php  
                      } 
                    } ?>
                    </select>
    					</div>
         			<div class="grp" id="search_box_patient_name" style="display:none;"><input  onkeyup="return form_submit();" type="text" name="patient_name"  id="patient_name" /></div>
                     
                     <!-- <div class="grp" id="search_box_mobile_no" onkeypress="return isNumberKey(event);" style="display:none;"><input type="text" name="mobile_no" id="mobile_no" onkeyup="return form_submit();"/>
                     </div> 
                     <div class="grp" id="search_box_uhid_no" onkeypress="return isNumberKey(event);" style="display:none;"><input type="text" name="uhid_no" id="uhid_no" onkeyup="return form_submit();"/>
                     </div> 
                     <div class="grp" id="search_box_lab_ref_no" onkeypress="return isNumberKey(event);" style="display:none;"><input type="text" name="lab_ref_no" id="lab_ref_no" onkeyup="return form_submit();"/>
                     </div>  -->
                      
				      <div class="grp">

				            <label><b>From Date:</b></label>
				            <input type="text" id="start_date" name="start_date" class="datepicker m_input_default w-80px" value="<?php echo $form_data['start_date']; ?>">
				      </div>

			      <div class="grp">
			            <label><b>To Date:</b></label>
			            <input type="text" name="end_date" id="end_date" class="datepicker_to m_input_default w-80px" value="<?php echo $form_data['end_date']; ?>">
			      </div>
			      <div class="grp">
			          <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
			      </div>
    		</div> <!-- //row -->






       <?php
          if(in_array('1936',$users_data['permission']['action'])){?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered pathology_report_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>  
                    <th> Lab Ref. No. </th> 
                    <th> Booking Date </th>  
                    <th> UHID No. </th>
                    <th> Patient Name </th>
                    <th> Referred By </th>
                    <th> Item Name </th>
                    <th> Qty </th>
              
                </tr>
            </thead>  
        </table> 
        <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative" id="example_wrapper">
      <div class="fixed">
  		<div class="btns media_btns"> 
      

          <?php if(in_array('1937',$users_data['permission']['action'])){?>
               <a href="<?php echo base_url('pathology_inventory_reports/path_report_excel'); ?>" class="btn-anchor">
               <i class="fa fa-file-excel-o"></i> Excel
               </a>
          <?php } ?>
          <?php if(in_array('1938',$users_data['permission']['action'])){?>
               <a href="<?php echo base_url('pathology_inventory_reports/path_report_csv'); ?>" class="btn-anchor">
                    <i class="fa fa-file-word-o"></i> CSV
               </a>
          <?php } ?>
          <?php if(in_array('1939',$users_data['permission']['action'])){?>
               <a href="<?php echo base_url('pathology_inventory_reports/pdf_path_report'); ?>" class="btn-anchor">
                    <i class="fa fa-file-pdf-o"></i> PDF
               </a>
          <?php } ?>
          <?php if(in_array('1940',$users_data['permission']['action'])){?>
               <a href="javascript:void(0)" class="btn-anchor" id="deleteAll" onClick="return print_window_page('<?php echo base_url("pathology_inventory_reports/print_path_report"); ?>');">
               <i class="fa fa-print"></i> Print
               </a>
          <?php } ?>
          <?php if(in_array('1936',$users_data['permission']['action'])){?>
               <button class="btn-update" onClick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button>
          <?php } ?> 
         
        <button class="btn-update" onClick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
      </div>
  	</div> 
  	<!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?> 
<script type="text/javascript">

function form_submit()
{
	var start_date = $('#start_date').val();
	var end_date = $('#end_date').val();
	var item_name = $('#item_name').val();
	
  	$.ajax({
	         url: "<?php echo base_url('pathology_inventory_reports/search_data/'); ?>", 
	         type: 'POST',
	         data: { item_name: item_name,start_date: start_date, end_date : end_date} ,
	         success: function(result)
	         { 
	            reload_table(); 
	         }
	      });    
}
form_submit('1');
var save_method; 
var table; 
<?php
  if(in_array('1936',$users_data['permission']['action']))
  {
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,  
        "pageLength": '20', 
        "ajax": {
            "url": "<?php echo base_url('pathology_inventory_reports/ajax_list')?>",
            "type": "POST"
        }, 
        "columnDefs": [
        { 
            "targets": [-1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
});
<?php } ?>
// function get_selected_value_by_user(value)
// { 
      
// 	  if(value=='patient_name')
// 	  {
// 			document.getElementById("search_box_patient_name").style.display="block";
// 			document.getElementById("search_box_mobile_no").style.display="none";
// 			document.getElementById("search_box_uhid_no").style.display="none";
// 			document.getElementById("search_box_lab_ref_no").style.display="none";
// 			$('#patient_name').val('');
// 	  }
// 	  else if(value=='p_mobile_no')
// 	  {
// 			document.getElementById("search_box_mobile_no").style.display="block";
// 			document.getElementById("search_box_patient_name").style.display="none";
// 			document.getElementById("search_box_uhid_no").style.display="none";
// 			document.getElementById("search_box_lab_ref_no").style.display="none";
// 			$('#mobile_no').val('');
// 	  }
// 	  else if(value=='uhid_no')
// 	  {
// 			document.getElementById("search_box_uhid_no").style.display="block";
// 			document.getElementById("search_box_patient_name").style.display="none";
// 			document.getElementById("search_box_mobile_no").style.display="none";
// 			document.getElementById("search_box_lab_ref_no").style.display="none";
// 			$('#uhid_no').val('');
// 	  }
// 	  else if(value=='lab_ref_no')
// 	  {
// 			document.getElementById("search_box_lab_ref_no").style.display="block";
// 			document.getElementById("search_box_patient_name").style.display="none";
// 			document.getElementById("search_box_mobile_no").style.display="none";
// 			document.getElementById("search_box_uhid_no").style.display="none";
// 			$('#lab_ref_no').val('');
// 	  }
// }
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>pathology_inventory_reports/reset_date_search/", 
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


function get_selected_value(value)
{ 
      var branch_id ='<?php echo $users_data['parent_id']; ?>';
      if(value==branch_id)
      {
        document.getElementById("search_box_user").style.display="block";
      }
      else
      {
        document.getElementById("search_box_user").style.display="none";
      }
      
} 


$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.datepicker').val();
      $('.datepicker_to').datepicker('setStartDate', start_data);
      form_submit();
  });

  $('.datepicker_to').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {  
      form_submit();
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
};

</script>
<div id="load_advance_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

  <div id="confirm_email" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="email_now">Confirm</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
            </div>
        </div>
      </div>  
  </div> 
</body>
</html>