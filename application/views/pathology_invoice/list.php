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

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>


</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
<script>
  // left side bar
  $(document).ready(function(){
    $('.lsb_btns').click(function(){
      $('.leftSideBar').fadeIn(); 
    });
    $('.lsb_btns').click(function(){ 
      $('.leftSideBar').css('left','0px'); 
    });
  
  $('.toggleBtn').click(function(){
    $('.toggleBox').animate({width:'toggle'});
  });
  $('.toggleBox a').click(function(){
    $('.toggleBox').animate({width:'toggle'});
  });
  });
</script>
 <div class="userlist-box">
    <form name="search_form" action="<?php echo base_url('test'); ?>" method="post" id="search_form">
    
         
    <!-- // -->
  <?php
   if($users_data['users_role']==1 || $users_data['users_role']==2  || $users_data['users_role']==3)
   {
  ?>
    <div class="row m-b-5">
         <div class="col-md-12">
              <div class="row m2">
              <?php
              $branch_attribute = get_permission_attr(1,2);  
              if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) &&  ($users_data['users_role']==1 || $users_data['users_role']==2)) 
              {
              ?>
                   <div class="col-md-3 m3" >
                     <label><b>Branch:</b></label>  
                     <select class="sub_branch" name="branch_id" id="branch_id" onChange="return reload_table();">
                        <option value="<?php echo $users_data['parent_id']; ?>">Self</option> 
                        <?php
                        foreach($branch_list as $branch)
                        {
                         ?>
                          <option value="<?php echo $branch['id']; ?>"><?php echo $branch['branch_name']; ?></option>
                         <?php  
                        }
                        ?>
                    </select>
                   </div> <!-- 3 -->
            <?php
             }
            ?>       
                   <div class="col-md-3 m3">
                        <label><b>From Date:</b></label>
                        <input type="text" id="start_date" name="from_date" class="datepicker m_input_default" value="<?php echo $form_data['start_date']; ?>" placeholder="From Date" style="width: 120px !important;">
                   </div> <!-- 3 -->
                   <div class="col-md-3  m3">
                        <label><b>To Date:</b></label>
                        <input type="text" name="to_date" id="end_date" class="datepicker_to m_input_default" value="<?php echo $form_data['end_date']; ?>" placeholder="To Date" style="width: 120px !important;">
                   </div> <!-- 3 -->
                   <div class="col-md-3 m3">  
                        
                        <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
                        <a href="javascript:void(0);" class="btn-custom" id="adv_search">
                          <i class="fa fa-cubes"></i> Advance Search
                        </a>
                        
                   </div> <!-- 3 -->
                   
              </div> <!-- innerRow -->
         </div> <!-- 12 -->
    </div> <!-- row -->
  <?php
  }
  ?>   

    <div class="col-md-12">
    <div class="col-md-3"></div>
  </div>
  </form>
    <form> 
    <div class="hr-scroll">
       <!-- bootstrap data table -->
          <?php if(in_array('2472',$users_data['permission']['action'])): ?>
        <table id="table" class="table table-striped table-bordered test_booking_list tbl_alert" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
                    <th> Proforma No. </th> 
                    <th> Patient Name </th>
                    <th> Mobile No. </th>
                    <th> Gender </th>
                    <th> Age </th>
                    <th> Referred By </th>  
                    <th> Booking Date </th> 
                    <th> Remark </th>
                    <th> Total Amount</th>
                    <th> Discount </th>
                    <th> Net Amount </th> 
                    <th> Status </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table> 
   <?php endif; ?>
   </div>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns">
              <?php if(in_array('2473',$users_data['permission']['action'])): ?>
                       <button class="btn-update"  onclick="window.location.href='<?php echo base_url("pathology_invoice/booking") ?>'" title="Test Booking">
                         <i class="fa fa-plus"></i> New
                       </button>
              <?php endif; ?>
              <a href="<?php echo base_url('pathology_invoice/export_excel'); ?>" class="btn-anchor m-b-2">
                 <i class="fa fa-file-excel-o"></i> Excel
              </a>

              

              <a href="<?php echo base_url('pathology_invoice/export_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("pathology_invoice/export_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a>
              
             <?php if(in_array('2472',$users_data['permission']['action'])): ?>

                  <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
                  </button>  
             <?php endif; ?>
             
        <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
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
function form_submit(vals)
 {   
      var start_date = $('#start_date').val();
      var end_date = $('#end_date').val();
      $.ajax({
             url: "<?php echo base_url('pathology_invoice/search_date/'); ?>", 
             type: 'POST',
             data: { start_date: start_date, end_date : end_date} ,
             success: function(result)
             { 
                if(vals!="1")
                {
                    /*get_total_booked();
                    get_total_pending();
                    get_total_completed();
                    get_total_verified();
                    get_total_delivered();
                    get_total_home_collection();*/
                   reload_table(); 
                }
             }
          });      
 }
 
 form_submit(1);

var save_method; 
var table; 
<?php if(in_array('2472',$users_data['permission']['action'])): ?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('pathology_invoice/ajax_list')?>",
            "type": "POST",
            "data":function(d){
                              d.branch_id =  $("#branch_id :selected").val(); 
                              return d;
                        }
           
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
 
<?php endif; ?>
 
function view_test(id)
{
  var $modal = $('#load_add_test_modal_popup');
  $modal.load('<?php echo base_url().'pathology_invoice/view/' ?>'+id,
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
                      url: "<?php echo base_url('pathology_invoice/deleteall');?>",
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
<script>
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/reset_date_search/", 
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
       
       $("#referred_by").attr('checked', 'checked');
        reload_table();
      } 
  }); 

}
function get_total_booked()
{
    $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/get_total_booked/", 
    dataType: "json",
      success: function(result)
      { 
          
          var str1 = "Total Booked (";
          var str2 = result.total_booked;
          var str3 = ")";
          var res = str1.concat(str2, str3);
        $("#total_booked").val(res);
      } 
  }); 
}

function get_total_pending()
{
    $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/get_total_pending/", 
    dataType: "json",
      success: function(result)
      { 
          
          var str1 = "Pending (";
          var str2 = result.total_pending;
          var str3 = ")";
          var res = str1.concat(str2, str3);
        $("#total_pending").val(res);
      } 
  }); 
}

function get_total_completed()
{
    $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/get_total_completed/", 
    dataType: "json",
      success: function(result)
      { 
          
          var str1 = "Completed (";
          var str2 = result.total_completed;
          var str3 = ")";
          var res = str1.concat(str2, str3);
        $("#total_completed").val(res);
      } 
  }); 
}

function get_total_verified()
{
    $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/get_total_verified/", 
    dataType: "json",
      success: function(result)
      { 
          
          var str1 = "Verified (";
          var str2 = result.total_verified;
          var str3 = ")";
          var res = str1.concat(str2, str3);
        $("#total_verified").val(res);
      } 
  }); 
}
function get_total_delivered()
{
    $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/get_total_delivered/", 
    dataType: "json",
      success: function(result)
      { 
          
          var str1 = "Delivered (";
          var str2 = result.total_delivered;
          var str3 = ")";
          var res = str1.concat(str2, str3);
        $("#total_delivered").val(res);
      } 
  }); 
}

function get_total_home_collection()
{
    $.ajax({url: "<?php echo base_url(); ?>pathology_invoice/get_total_home_collection/", 
    dataType: "json",
      success: function(result)
      { 
          
          var str1 = "Home Collection (";
          var str2 = result.total_home_collection;
          var str3 = ")";
          var res = str1.concat(str2, str3);
        $("#total_home_collection").val(res);
      } 
  }); 
}


$(document).ready(function(){
  var $modal = $('#load_adv_search_modal_popup');
  $('#adv_search').on('click', function()
  {
    $modal.load('<?php echo base_url(); ?>pathology_invoice/advance_search/',
    { 
    },
    function()
    {
       $modal.modal('show');
    });
  });
});

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?> 
 
 
 function edit_test(booking_id)
 {    
    

      window.location.href ="<?php echo base_url('pathology_invoice/edit_booking/'); ?>"+booking_id;
      
   
 }

 function delete_test(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pathology_invoice/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

 function delivered_test(test_id,status)
 {    
    $('#confirm_delivered').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delivered', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pathology_invoice/delivered_test/'); ?>"+test_id+'/'+status, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
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

function reset_date_search()
  {
    $('#start_date').val('');
    $('#end_date').val('');
    $.ajax({
           url: "<?php echo base_url('pathology_invoice/reset_date_search/'); ?>",  
           success: function(result)
           { 
            reload_table(); 
           }
        });  
  }
 
 $('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print'){ ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('pathology_invoice');?>'; 
    }); 
       
  <?php }?>

  <?php if(isset($_GET['form_f_status']) && $_GET['form_f_status']=='1'){ ?>
  $('#confirm_print_form_f').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('pathology_invoice');?>'; 
    }); 
       
  <?php }?>
 });
 
 


function sample_collected(testid)
 {
   $.ajax({
        url: "<?php echo base_url('pathology_invoice/open_sample_collected'); ?>/"+testid,
        success: function(output){
          
          $('#load_sample_modal_popup').html(output).modal('show');
        },
        error:function(){
            alert("failure");
        }
    });
 }

 function sample_received(testid)
 {
   $.ajax({
        url: "<?php echo base_url('pathology_invoice/open_sample_received'); ?>/"+testid,
        success: function(output){
          
          $('#load_sample_modal_popup').html(output).modal('show');
        },
        error:function(){
            alert("failure");
        }
    });
 }
</script>
<!--end neha -->
</script> 
<!-- Confirmation Box -->

    <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("pathology_invoice/print_test_booking_report"); ?>');">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

    
    <div id="confirm_print_form_f" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("pathology_invoice/form_f_print"); ?>');">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

    
     <div id="confirm_print_bill" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("pathology_invoice/print_test_bill"); ?>');">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>  

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

      <div id="confirm_delivered" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="delivered">Confirm</button>
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
<div id="load_adv_search_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_sample_modal_popup" class="modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->





<script type="text/javascript">
 function change_status(val)
 {
    $.ajax({
           url: "<?php echo base_url('pathology_invoice/status_filter/'); ?>", 
           type: 'POST',
           data: {val:val} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
 }
</script>




  <script type="text/javascript">

 function upload_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'pathology_invoice/upload_prescription/' ?>'+id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }
</script>

<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>

</html>