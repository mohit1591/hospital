<?php
// print_r($this->session->userdata('net_values_all'));
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
    <!--new css-->
<script type="text/javascript">

function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('ipd_booking/advance_search/'); ?>",
      type: "post",
      data: $('#search_form_list').serialize(),
      success: function(result) 
      {
        if(vals!='1')
        {
          $('#load_add_modal_popup').modal('hide'); 
          reload_table();       
          $('#overlay-loader').hide();
        } 
      }
    }); 
}
form_submit('1');


var save_method; 
var table;
<?php
if(in_array('733',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true,
        
        "pageLength" : 10,
        "serverSide": true,
        "sortable" : true,
        
        "order": [], 
        "ajax": {
            "url": "<?php echo base_url('ipd_booking/ajax_list')?>",
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
            /*$.ajax({
                      dataType: "json",
                      url: "<?php echo base_url('ipd_booking/total_calc_return');?>",
                      success: function(result) 
                      {
                        $('#total_net_amount').val(result.net_amount);
                        $('#total_discount').val(result.discount);
                        $('#total_balance').val(result.balance);
                        $('#total_vat').val(result.vat);
                        $('#total_paid_amount').val(result.paid_amount);
                      }
                  });*/
        },

    });

       $('.tog-col').on( 'click', function (e) 
      {
        var column = table.column( $(this).attr('data-column') );
        column.visible( ! column.visible() );
      });

});  
<?php } ?>



function edit_ipd_booking(id)
{
  
  window.location.href='<?php echo base_url().'ipd_booking/edit/';?>'+id
  
  
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'ipd_booking/view/' ?>'+id,
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
                      url: "<?php echo base_url('ipd_booking/deleteall');?>",
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
  $checkbox_list = get_checkbox_coloumns('2');
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
            <?php  
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

   // if($checkbox_list_data->checked_status==0) 
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
    <?php if(isset($user_role) && $user_role==4 || $user_role==3)
    {
    }
    else
    {?>
  <form name="search_form"  id="search_form"> 
  <div class="row">
  <div class="col-md-12">

    <div class="row m-b-2">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
              <label> <input type="radio" value="0" name="running" id="running" class="" onClick="return check_status();"   <?php if($form_data['running']=='0'){ ?> checked="checked" <?php  } ?> > Running</label>
            </div>
            <div class="col-md-7">
              <label> <input type="radio" value="1"  name="running" id="running"  onClick="return check_status();" <?php if($form_data['running']=='1'){ ?> checked="checked" <?php } ?>> Discharge</label>
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
               <label>  Patient Type </label>
            </div>
            <div class="col-md-7">
              <select name="patient_type" class="m_input_default" id="patient_type" onchange="return form_submit();">
              <option value="-1" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']=='-1'){ echo 'selected="selected"'; } ?> >All</option>
              <option  value="1" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']==1){ echo 'selected="selected"'; } ?> >Normal</option>
              <option value="2" <?php if(isset($_POST['patient_type'])&& $_POST['patient_type']==2){ echo 'selected="selected"'; } ?> >Panel</option></select>
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <a href="javascript:void(0)" class="btn-a-search" id="adv_search_ipd_booking"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a>
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div> <!-- inner row -->


    <div class="row m-b-5">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
              <label> From Date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="start_date" id="start_date_p"  value="<?php echo $form_data['start_date'];?>" class="datepicker m_input_default"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <label>To date</label>
            </div>
            <div class="col-md-7">
              <input type="text" name="end_date" id="end_date_p" value="<?php echo $form_data['end_date']?>" class="datepicker m_input_default"  onkeyup="return form_submit();">
            </div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              <!--<a href="javascript:void(0)" class="btn-custom" id="reset_date" onclick="reset_search();"><i class="fa fa-refresh"></i> Reset</a>-->
              <input value="Reset" class="btn-custom" onclick="reset_ipd_search(this.form)" type="button">
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div> <!-- inner row -->

    <!-- <div class="row m-b-5">
      <div class="col-md-4">
          <div class="row">
            <div class="col-md-5">
             
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
      <div class="col-md-4">
        <div class="row">
            <div class="col-md-5">
              
            </div>
            <div class="col-md-7"></div>
          </div>
      </div>
    </div>  --><!-- inner row -->


  </div> <!-- 12 -->
</div> <!-- row -->
    


</form>

<?php } ?>

    <form>
       <?php if(in_array('733',$users_data['permission']['action'])) {
       ?>

       <!-- bootstrap data table -->
       <div class="hr-scroll">
        <table id="table" class="table table-striped table-bordered purchase_list "  cellspacing="0" width="100%">

            <thead class="bg-theme">

                <tr>
                    <th align="center" width="40"> <input onclick="selectall();" type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                        <th>IPD No.</th> 
                        <th><?php echo $data= get_setting_value('PATIENT_REG_NO');?></th> 
                        <th>Patient Name </th> 
                        <th>Mobile No.</th> 
                        <th>Admission Date</th>
                        <th>Doctor Name</th> 
                        <th>Room No.</th> 
                        <th>Bed No.</th> 
                        <th>Address</th> 
                        <th>Remarks</th> 
                       

                        <th>Father Name</th>
                        <th>Gender</th>
                        <th>Patient Email</th>
                        <th>Insurance Type</th>
                        <th>Insurance Company</th>

                        <th>MLC</th>
                        <th>Package Name</th>
                        <th>Room Type</th>
                        <th>Referred By</th>
                        
                        <th>Advance Deposit</th>
                        <th>Registration Charge</th>
                        <th>Policy No.</th>

                        <th>Discharge Date </th>
                        <th>Action </th>
                </tr>
            </thead>  
        </table>
        </div>
        <?php } ?>

    </form>


   </div> <!-- close -->


<div class="userlist-right relative">
  <div class="fixed">
  		<div class="btns">

               <?php if(in_array('734',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_booking/add'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
               <?php if(in_array('733',$users_data['permission']['action'])) {
               ?>

                <a href="<?php echo base_url('ipd_booking/ipd_booking_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>

                <a href="<?php echo base_url('ipd_booking/ipd_booking_csv'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-word-o"></i> CSV
                </a>

                <a href="<?php echo base_url('ipd_booking/pdf_ipd_booking'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-pdf-o"></i> PDF
                </a>

                
                <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_booking"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 
  			     
               <?php } ?>
               <?php if(in_array('736',$users_data['permission']['action'])) {
                ?>
               <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
              <i class="fa fa-trash"></i> Delete
             </button>
             <?php } ?>
              

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               
               <?php if(in_array('737',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('ipd_booking/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               <?php } ?>
               
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
  		</div>
      </div>
  	</div> 
  	<!-- right -->
 


  


  
</section> <!-- cbranch -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<?php
$this->load->view('include/footer');
?>

<script>
function check_status()
{
    var running = document.getElementById('running').value;
    if(running.length == 0)
    {
       form_submit();
    }
    else
    {
        form_submit();
    }
}

$(document).ready(function(){
   //form_submit();
 }); 

$(document).ready(function(){
  //reload_table();
   $('#selectAll').on('click', function () { //alert('test');
                                 
         if ($("#selectAll").hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});
 

 function delete_ipd_booking(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('ipd_booking/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 

</script> 

<script>
$('documnet').ready(function(){

 <?php if(isset($_GET['status']) && $_GET['status']=='print'){?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_card"); ?>')
    }) ;
   
       
  <?php }?>

  <?php if(isset($_GET['mlc_status']) && $_GET['mlc_status']==1){?>
  $('#confirm_mlc').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>

<?php if(isset($_GET['admission_form']) && $_GET['admission_form']=='print_admission'){?>
  $('#confirm_admission_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('ipd_booking');?>'; 
        //print_window_page('<?php echo base_url("ipd_booking"); ?>')
    }) ;
   
       
  <?php }?>
 });


function confirmation_box(ipd_id,patient_id){
   $('#confirm_discharge').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#yes', function(e)
    { 
       window.location.href='<?php echo base_url('ipd_booking/update_discharge_data') ?>/'+ipd_id+'/'+patient_id;
    }) ;

}

function generate_discharge_bill(booking_id,patient_id,type)
 {
   $.ajax({
        url: "<?php echo base_url('ipd_booking/select_discharge_date'); ?>/"+booking_id+"/"+patient_id+"/"+type,
        success: function(output){
          
          $('#load_discharge_modal_popup').html(output).modal('show');
        },
        error:function(){
            alert("failure");
        }
    });
 }
 
 function generate_gipsa_discharge_bill(booking_id,patient_id,type)
 {
   $.ajax({
        url: "<?php echo base_url('ipd_booking/gipsa_select_discharge_date'); ?>/"+booking_id+"/"+patient_id+"/"+type,
        success: function(output){
          
          $('#load_discharge_modal_popup').html(output).modal('show');
        },
        error:function(){
            alert("failure");
        }
    });
 }

function confirmation_readmit(ipd_id,patient_id){
   $('#confirm_readmit').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#yes', function(e)
    { 
       window.location.href='<?php echo base_url('ipd_booking/readmit') ?>/'+ipd_id+'/'+patient_id;
    }) ;

}



</script>

<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_ipd_booking').on('click', function(){
$modal.load('<?php echo base_url().'ipd_booking/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});



</script>

<script>
function form_submit()
{
  $('#search_form').delay(200).submit();
}

function reset_ipd_search(ele) { 
   $.ajax({url: "<?php echo base_url(); ?>ipd_booking/reset_search/", 
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
function reset_search()
{ 
  $('#start_date_p').val('');
  $('#end_date_p').val('');
  $('#ipd_no').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>ipd_booking/reset_search/", 
    success: function(result)
    { 
          
      //document.getElementById("search_form").reset(); 
      reload_table();
//for check all on reset 
      /* $('#selectAll').on('click', function () { //alert('yttt');
                                 
         if ($("#selectAll").hasClass('allChecked')) 
         {
             $('.checklist').prop('checked', false);
         } 
         else 
         {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    }); */
    } 
  }); 
}

$("#search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>/ipd_booking/advance_search/",
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

    var today =new Date();
    $('#start_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $("#end_date_p").datepicker("option", "minDate", selected);
      }
    })

    $('#end_date_p').datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          $("#start_date_p").datepicker("option", "maxDate", selected);
      }
    })


 $(document).ready(function(){
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
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
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_booking_recipt"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>

    <div id="confirm_mlc" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/mlc_print"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>
    <div id="confirm_admission_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_card"); ?>');" >Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>

    <div id="confirm_discharge" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure For Discharge?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  id="yes" onClick="" >Yes</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">No</button>
          </div>
        </div>
      </div>  
    </div>

    <div id="confirm_readmit" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure For Re-admit?</h4></div>
          
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor"  id="yes" onClick="" >Yes</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">No</button>
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

</div>

<!-- Code for insert and update list columns -->



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
            window.location = "<?php echo base_url(); ?>ipd_booking";
        }, 1300); 
        }
      });
    }); 




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


<!-- function to reload columns -->
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
                            window.location = "<?php echo base_url(); ?>opd";
                            }, 1300); 
                        }
                        else
                        {
                            flash_session_msg("Record Updated Successfully");
                           setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>ipd_booking";
                            }, 1300); 
                        }

                    }

                });
        }); 
    }
</script>
<!-- function to reload columns -->

<!-- Code to insert and update list columns -->

<!-- function to reload columns -->
<script type="text/javascript">
function print_add_consent(book_id)
{
  $('#book_id_reg').val(book_id);
  $('#confirm_admission_consent').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    {
    }) ; 
}

function nabh_print_ipd_booking(book_id)
{
  $('#ipd_book_id').val(book_id);
  $('#nabh_print_form').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    {
    }) ; 
}

function print_mlc_print(book_id)
{
  $('#book_id_reg').val(book_id);
    print_window_page('<?php echo base_url("ipd_booking/mlc_print/"); ?>'+book_id);
}
function print_options_report()
{    
  var book_id=$('#book_id_reg').val();
   var print_option = $('#print_opt').val();
   print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_consent/"); ?>'+print_option+'/'+book_id);
} 

function print_nabh_print_report()
{    
  var book_id=$('#ipd_book_id').val();
   var form_type = $('#form_type').val();
   print_window_page('<?php echo base_url("ipd_booking/print_ipd_adminssion_consent_new/"); ?>'+book_id+'/'+form_type);
}

function new_born_baby(ipd_id)
 {
   $.ajax({
        url: "<?php echo base_url('ipd_booking/new_born_baby'); ?>/"+ipd_id,
        success: function(output){
          
          $('#load_add_modal_popup').html(output).modal('show');
        },
        error:function(){
            alert("failure");
        }
    });
 }
</script>
<!-- Code to insert and update list columns -->
 <div id="confirm_admission_consent" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-theme"><h4>Are You Sure consent form?</h4></div>
          <div class="modal-body">   
            <div class="row">
              <div class="col-md-12">
                <div class="row m-b-5">
                  <div class="col-md-5">      
                    <label><b>Template:</b></label>
                  </div>
                  <div class="col-md-7">
                    <select class="form-control" name="print_opt" id="print_opt">
                      <option value="0" selected="selected">English</option>
                      <option value="1">Hindi</option>
                    </select>
                    <input type="hidden" name="book_id_reg" id="book_id_reg">
                  </div>
                </div>

              </div> <!-- 12 -->
            </div> <!-- row -->  

          </div> 
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor" onclick="print_options_report()">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>




<div id="load_discharge_modal_popup" class="modal" role="dialog" data-backdrop="static" data-keyboard="false"></div>


<div id="nabh_print_form" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header bg-theme"><h4>Are You Sure NABH form?</h4></div>
          <div class="modal-body">   
            <div class="row">
              <div class="col-md-12">
                <div class="row m-b-5">
                  <div class="col-md-5">      
                    <label><b>Template Type:</b></label>
                  </div>
                  <div class="col-md-7">
                    <select class="form-control" name="form_type" id="form_type">
                       <option value="1">ADMINSSION & DISCHARGE RECORD</option>
                      <option value="2">PATIENTS RIGHTS</option>
                      <option value="3">DOCTOR'S VISIT</option>
                      <option value="4">EMERGENCY ASSESSMENT SHEET</option>
                      <option value="5">INITIAL CLINICIAN ASSESSMENT </option>
                      <option value="6">NURSING CARE PLAN</option>
                        <option value="7">NURSING ADMISSION ASSESSMENT </option>
                      <option value="8">CARE PLAN </option>
                      <option value="9">PROGRESS NOTES</option>
                      <option value="10">NURSING NOTES</option>
                      <option value="11">MEDICATION CHART</option>
                      <option value="12">INTAKE OUTPUT</option>
                        <option value="13">VITALS</option>
                      <option value="14">NUTRITIONAL ASSESSMENT</option>
                      <option value="15">UNIVERSAL PAIN </option>
                      <option value="16">BILLING SHEET  </option>
                       <option value="37">INVESTIGATION CHART</option>
                      <option value="17">SURGICAL PATIENT FILE </option>
                      <option value="18">PRE OPERATIVE CHECKLIST</option>
                        <option value="19">INFORMED CONSENT SURGERY</option>
                      <option value="20">INFORMED CONSENT ANASTHESIA</option>
                      <option value="21">PRE ANAESTHEIC ASSESSMENT</option>
                      <option value="22">PRE INTRA OPERATIVE EVENTS</option>
                      <option value="23">OPERATIONAL NOTES</option>
                      <option value="24">GULFASA(DISCHARGE SUMMERY)</option>
                        <option value="25">POST OPERATIVE RECOVERY</option>
                      <option value="26">MAST AAHAN(DISCHARGE SUMMERY)</option>
                      <option value="27">SURGICAL SAFETY</option>
                      <option value="28">DOCUMENT CHECKLIST</option>
                      <option value="29">MINOR PROCEDURE</option>
                      <option value="30">MODERATE SEDATION </option>
                        <option value="31">HIV CONSENT</option>
                      <option value="32">BLOOD TRANSFUSION</option>
                      <option value="33">MISS AANCHAL(DISCHARGE SUMMERY)</option>
                      <option value="34">LAMA</option>
                      <option value="35">FEEDBACK</option>
                      <option value="36">RECORD OF DAILY  PATIENT’S</option>
                     
                    </select>
                    <input type="hidden" name="ipd_book_id" id="ipd_book_id">
                  </div>
                </div>

              </div> <!-- 12 -->
            </div> <!-- row -->  


          </div> 
          <div class="modal-footer">
            <a type="button" data-dismiss="modal" class="btn-anchor" onclick="print_nabh_print_report()">Print</a>
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
</div>
</body>
</html>
