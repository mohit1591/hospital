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
<?php 
  $checkbox_list = get_checkbox_coloumns('5');
  $module_id = $checkbox_list[0]->module;
?>

<!-- //////////////////////[ Left side bar ]////////////////////// -->
<?php if($users_data['emp_id']==0){  ?>
<div class="toggleBtn"><i class="fa fa-angle-right"></i></div>
<div class="toggleBox">
  <a>Exit <i class="fa fa-sign-out"></i></a>
  <form id="checkbox_data_form">
      <table class="table table-bordered table-striped table-hover">
        <tbody>
            <?php  //echo "<pre>"; print_r($checkbox_list); exit;
                 $unchecked_column = [];
                foreach ($checkbox_list as $checkbox_list_data ) 
                {
                ?>
                    <tr>
                        <td><input type="checkbox" class="tog-col" <?php if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status))
                   {  ?> checked="checked" <?php }else { $unchecked_column[] = $checkbox_list_data->coloum_id; } ?> value="<?php echo $checkbox_list_data->coloum_id; ?>" data-column="<?php echo $checkbox_list_data->coloum_id; ?>"></td>

                        <td><?php echo $checkbox_list_data->coloum_name; ?></td>
                    </tr>
                <?php
                }

                ?>
          <tr>
            <td colspan="2">
              <button type="submit" class="btn-save m-t-5"><i class="fa fa-floppy-o"></i> Save</button>
              <button onclick="reset_coloumn_record();" type="button" class="btn-save m-t-5"><i class="fa fa-refresh"></i> Reload</button>
            </td>
          </tr>
        </tbody>
      </table>
       </form>
</div>
<?php } else { 

  $unchecked_column = [];
  foreach ($checkbox_list as $checkbox_list_data ) 
  {

    //if($checkbox_list_data->checked_status==0) 
      //{ $unchecked_column[] = $checkbox_list_data->coloum_id; } 
      
      if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status)) 
      { 
      
      } 
      else
      {
        $unchecked_column[] = $checkbox_list_data->coloum_id;
      }
  }

 } ?>




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
              if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) && $branch_attribute>0 && ($users_data['users_role']==1 || $users_data['users_role']==2)) 
              {
              ?>
                   <div class="col-md-3">
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
                   <div class="col-md-3 text-right m3">
                        <label><b>From Date:</b></label>
                        <input type="text" id="start_date" name="from_date" class="datepicker m_input_default" value="<?php echo $form_data['start_date']; ?>">
                   </div> <!-- 3 -->
                   <div class="col-md-3 text-right m3">
                        <label><b>To Date:</b></label>
                        <input type="text" name="to_date" id="end_date" class="datepicker_to m_input_default" value="<?php echo $form_data['end_date']; ?>">
                   </div> <!-- 3 -->
                   <div class="col-md-3 m3"> 
                   <a class="btn-custom" onclick="clear_form_elements(this.form);">
                          <i class="fa fa-refresh"></i> Reset
                        </a>
                        <a class="btn-custom" id="adv_search">
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
    <div class="col-md-6" style="margin-left: 432px;margin-top: 0;">
        <input class="btn btn-sm btn_booked" type="button" onclick="change_status('1');" value="Booked">
        <input class="btn btn-sm btn_pending" type="button" onclick="change_status('2');" value="Pending">
        <input class="btn btn-sm btn_completed" type="button" onclick="change_status('3');"  value="Completed">
        <input class="btn btn-sm btn_verified" type="button" onclick="change_status('4');" value="Verified">
        <input class="btn btn-sm btn_delivered" type="button" onclick="change_status('5');" value="Delivered">
        <input type="hidden" value="" name="rec_status">
    </div>
    <div class="col-md-3"></div>
  </div>
  </form>
    <form> 
    <div class="hr-scroll">
       <!-- bootstrap data table -->
          <?php if(in_array('871',$users_data['permission']['action'])): ?>
        <table id="table" class="table table-striped table-bordered test_booking_list tbl_alert" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
                    <th> Lab Ref. No. </th> 
                    <th> Patient Name </th>
					          <th> Department </th>
                    <th> Mobile No. </th>
                    <th> Token No.</th>
                    <th> Panel Type </th>
                    <th> Insurance Name </th>
                    <th> Company Name </th>
                    <th> Policy No </th>
                    <th> TPA ID </th>
                    <th> Insurance Amount </th>
                    <th> Authorization No. </th>
					
                    <th> Gender </th>
                    <th> Age </th>
                    <th> Address </th>
                    <th> Form F </th>
                    <th> Tube No. </th>
                    <th> Referred By </th>  
                    <th> Sample Collected By </th>
                    <th> Staff Reference </th>
                    <th> Booking Date </th> 
                    <th> Remark </th>
                    <th> Payment Mode</th>
                    <th> Total Amount</th>
                    <th> Home Collection</th>
                    <th> Discount </th>
                    <th> Net Amount </th> 
                    <th> Paid Amount </th>  
                    <th> Balance </th>   
                    <th> Created By </th> 
					<th> Relation Name</th>
					<th>  Email </th>
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
              <?php if(in_array('872',$users_data['permission']['action'])): ?>
                       <button class="btn-update"  onclick="window.location.href='<?php echo base_url("pathology_appointment/booking") ?>'">
                         <i class="fa fa-plus"></i> New
                       </button>
              <?php endif; ?>
              <a href="<?php echo base_url('pathology_appointment/export_excel'); ?>" class="btn-anchor m-b-2">
                 <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('pathology_appointment/export_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('pathology_appointment/export_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("pathology_appointment/export_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a>
              <?php if(in_array('874',$users_data['permission']['action'])): ?>

                  <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
                    <i class="fa fa-trash"></i> Delete
                  </button>
             <?php endif; ?>
             <?php if(in_array('871',$users_data['permission']['action'])): ?>

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
             url: "<?php echo base_url('pathology_appointment/search_date/'); ?>", 
             type: 'POST',
             data: { start_date: start_date, end_date : end_date} ,
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
<?php if(in_array('871',$users_data['permission']['action'])): ?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('pathology_appointment/ajax_list')?>",
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
  $modal.load('<?php echo base_url().'pathology_appointment/view/' ?>'+id,
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
                      url: "<?php echo base_url('pathology_appointment/deleteall');?>",
                      data: {row_id: allVals},
                      success: function(result) 
                      {
                            flash_session_msg(result);
                            reload_table();  
                      }
                  });
        });
   }      
 } 


</script>
<script>
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>pathology_appointment/reset_date_search/", 
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
 
$(document).ready(function(){
  var $modal = $('#load_adv_search_modal_popup');
  $('#adv_search').on('click', function()
  {
    $modal.load('<?php echo base_url(); ?>pathology_appointment/advance_search/',
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
    $('#confirm_edit').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#edit_booking', function(e)
    { 

      window.location.href ="<?php echo base_url('pathology_appointment/edit_booking/'); ?>"+booking_id;
      
    });     
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
                 url: "<?php echo base_url('pathology_appointment/delete/'); ?>"+rate_id, 
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
                 url: "<?php echo base_url('pathology_appointment/delivered_test/'); ?>"+test_id+'/'+status, 
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
           url: "<?php echo base_url('pathology_appointment/reset_date_search/'); ?>",  
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
        window.location.href='<?php echo base_url('test');?>'; 
    }); 
       
  <?php }?>

  <?php if(isset($_GET['form_f_status']) && $_GET['form_f_status']=='1'){ ?>
  $('#confirm_print_form_f').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('test');?>'; 
    }); 
       
  <?php }?>
 });
 
 


function sample_collected(testid)
 {
   $.ajax({
        url: "<?php echo base_url('pathology_appointment/open_sample_collected'); ?>/"+testid,
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
        url: "<?php echo base_url('pathology_appointment/open_sample_received'); ?>/"+testid,
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
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("pathology_appointment/print_test_booking_report"); ?>');">Print</a>
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
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("pathology_appointment/form_f_print"); ?>');">Print</a>
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
            <a  data-dismiss="modal" class="btn-anchor" id="delete" onClick="return print_window_page('<?php echo base_url("pathology_appointment/print_test_bill"); ?>');">Print</a>
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
    
    <div id="confirm_edit" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure to Edit this Booking?</h4></div>
           <div class="modal-body">The Report filling for this Booking has been Done. If you Edit/Update the Booking then you need to re-fill the Report. </div>
          <div class="modal-footer">
            <button type="button" data-dismiss="modal" class="btn-update" id="edit_booking">Confirm</button>
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
           url: "<?php echo base_url('pathology_appointment/status_filter/'); ?>", 
           type: 'POST',
           data: {val:val} ,
           success: function(result)
           { 
              reload_table(); 
           }
        });    
 }
</script>




<?php
if(!empty($unchecked_column))
{
$implode_checked_column = implode(',', $unchecked_column);

?>
<script type="text/javascript">
$( document ).ready(function(e) {  
table.columns([<?php echo $implode_checked_column; ?>]).visible(false);
} );
</script>
<?php    
}
?>





<script type="text/javascript">
    
     $("#checkbox_data_form").on("submit", function(event) { 
      event.preventDefault(); 
      var module_id = '<?php echo $module_id; ?>'
      var sList = [];
        $('.tog-col').each(function () 
        {
            if(this.checked)
                sList.push($(this).attr("value"));
        });
        if(sList=="")
        {
            $('#no_rec').modal();
            setTimeout(function(){
            $("#no_rec").modal('hide');
            }, 1500);
        }
       $.ajax({
        url: "<?php echo base_url(); ?>opd/checkbox_list_save",
        type: "POST",
        data: {rec_id:sList, module_id:module_id},
        success: function(result) 
        { 
            flash_session_msg(result); 
            setTimeout(function () {
            window.location = "<?php echo base_url(); ?>test";
        }, 1300); 
        }
      });
    }); 
  </script>

  <script type="text/javascript">
 function reset_coloumn_record()
    {
        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false, 
            })
        .one('click', '#delete', function(e)
        {
          $.ajax({
                url: '<?php echo base_url(); ?>opd/reset_coloumn_record',
                data: { 'module_id':'<?php echo $module_id ?>' },
                type: 'POST',
                success: function (data)
                    {
                        if(data.status==200)
                        {
                            flash_session_msg("Record Updated Successfully"); 
                            setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>test";
                            }, 1300); 
                        }
                        else
                        {
                            flash_session_msg("Record Updated Successfully");
                           setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>test";
                            }, 1300); 
                        }

                    }

                });
        }); 
    }
/*********** Letter head *************/
  function print_summary(id)
{
  var $modal = $('#load_add_ipd_discharge_summary_print_modal_popup');
  $modal.load('<?php echo base_url().'pathology_appointment/print_template/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
/*********** Letter head *************/


 function result_times(id)
 {
    var $modal = $('#load_sample_modal_popup');
    $modal.load('<?php echo base_url().'pathology_appointment/expect_result_time/' ?>'+id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }

function result_status_update(id)
 {
    $.ajax({
             url: "<?php echo base_url('pathology_appointment/expect_result_update/'); ?>"+id, 
             success: function(result)
             {
                flash_session_msg(result);
                reload_table(); 
             }
          });
  }


</script>
<!---- Letter head ----->
<div id="load_add_ipd_discharge_summary_print_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!---- Letter head ----->

</body>
</html>