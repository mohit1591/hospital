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

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<script type="text/javascript">
function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#patient_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');

  $.ajax({url: "<?php echo base_url(); ?>prescription/reset_search/", 
    success: function(result)
    { 
      reload_table();
    } 
  }); 
} 
$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'prescription/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#adv_search').on('click', function(){
$modal.load('<?php echo base_url().'prescription/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

});

function edit_prescription(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'prescription/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_prescription(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'prescription/view/' ?>'+id,
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
                      url: "<?php echo base_url('prescription/deleteall');?>",
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


 function upload_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'prescription/upload_prescription/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
function upload_eye_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'eye/add_prescription/upload_eye_prescription/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
 function view_files(prescription_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'prescription/view_files/' ?>'+prescription_id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

  function upload_pediatrician_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'pediatrician/add_prescription/upload_pediatrician_prescription/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

 
  
  function upload_dental_prescription(id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'dental/dental_prescription/upload_dental_prescription/' ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }
  
  
  function share_video_link(id,booking_id)
 {
    var $modal = $('#load_add_modal_popup');
    $modal.load('<?php echo base_url().'prescription/share_video_link/' ?>'+id+'/'+booking_id,
    {
    },
    function(){
    $modal.modal('show');
    });
  }
  // Added By Nitin Sharma 05/02/2024
    function sendWhatsApp(id,no,branch_id){
        $.ajax({
            url: '<?php echo base_url(); ?>prescription/download_prescription/'+id+'/'+branch_id+'/send',
            type: 'POST',
            data : {
                mobile : no
            },
            success: function (data)
                {
                    console.log(data);
                    let res = JSON.parse(data);
                    if(res.msg == "SUCCESSFULLY SEND"){
                        flash_session_msg("Whatsapp Send Successfull");
                    }else{
                        flash_session_msg("Whatsapp Send Fail");
                    }
    
                }
    
            });
    }
    // Added By Nitin Sharma 05/02/2024
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
   <form name="search_form"  id="search_form"> 

    <div class="row">
      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>From Date</label></div>
          <div class="col-xs-7">
            <input id="start_date_patient" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo $form_data['start_date']?>">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label><?php echo $data= get_setting_value('PATIENT_REG_NO');?></label></div>
          <div class="col-xs-7">
            <input name="patient_code" class="m_input_default" id="patient_code" onkeyup="return form_submit();"  value="<?php echo $form_data['patient_code']?>" type="text" autofocus>
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Mobile No.</label></div>
          <div class="col-xs-7">
            <input name="mobile_no" value="<?php echo $form_data['mobile_no']?>" id="mobile_no" onkeyup="return form_submit();" class="numeric m_input_default" maxlength="10" value="" type="text">
          </div>
        </div>
        
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Specialization</label></div>
          <div class="col-xs-7">
            <select name="specialization_id" id="specialization_id" onChange="return form_submit();">
              <option value="">Select Specialization</option>
              <?php
              if(!empty($specialization_list))
              {
                foreach($specialization_list as $specializationlist)
                {
                  ?>
                    <option <?php if($form_data['specialization_id']==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                  <?php
                }
              }
              ?>
            </select>
          </div>
        </div>




      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Patient Name</label></div>
          <div class="col-xs-7">
            <input name="patient_name" value="<?php echo $form_data['patient_name']?>" id="patient_name" onkeyup="return form_submit();" class="alpha_space m_input_default" value="" type="text">
          </div>
        </div>

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
             <?php if(in_array('1',$permission_section)){ ?> 
          <div class="row m-b-5">
          <div class="col-xs-5"><label>Branch</label></div>
          <div class="col-xs-7">
              
       
       
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
        </div>

<?php } else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php } ?>
              
      </div> <!-- 4 -->

      <div class="col-sm-4">
        
          <a class="btn-custom" id="reset_date" onclick="reset_search();">Reset</a>
      </div> <!-- 4 -->

    
    </div> <!-- row -->
  
         
    </form>
    <form>
       <?php if(in_array('539',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered prescription_list_tbl" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> OPD No. </th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th>  
                    <th> Name </th> 
                    <th> Mobile </th> 
                    
                    <th> Status </th> 
                    <?php if($users_data['parent_id']!='111')
            { ?>
                    <th> Created Date </th>
                    <?php } ?>
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>


    </form>


   </div> <!-- close -->





  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns">
               <?php if(in_array('552',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('opd'); ?>'">
  				     <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
               <?php if(in_array('535',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				     <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php } ?>
               <button class="btn-update h-auto" onclick="consolidate_model()"> <i class="fa fa-refresh"></i> Consolidate Print </button>
               <?php if(in_array('539',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('536',$users_data['permission']['action'])) {
                ?>
  			     <button class="btn-exit" onclick="window.location.href='<?php echo base_url('prescription/archive'); ?>'">
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
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>
function consolidate_model()
{
     $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
          if($(this).prop('checked')==true)
          {
              if($(this).val()!='')
              {
                    allVals.push($(this).val());  
              }
              
          }
     });
     
     if(allVals=='')
     {
        alert("Please select atleast a patient prescription."); 
        return false;
     }
          
     $.ajax({
         url: "<?php echo base_url('prescription/check_patient_ids/'); ?>"+allVals, 
         dataType: "json",
         success: function(result)
         {
            
             if(result.patient_status=='1')
             {
                window.open('<?php echo base_url('prescription/print_consolidate_prescription?') ?>pres_ids='+allVals,'mywin','width=800,height=600');  
             }
             else
             {
                 alert("You have selected different patient please select same patient.");
                 return false;
             }
            
         }
        
      });
            
   
}
/*$(document).ready(function(){
   form_submit();
 });
 */

/*$('.start_datepicker').datepicker({
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

function form_submit()
{
  $('#search_form').delay(200).submit();
}
$("#search_form").on("submit", function(event) { 
  event.preventDefault();  
   
  $.ajax({
    url: "< ?php echo base_url('prescription/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      reload_table();        
    }
  });

});*/


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
  var start_date = $('#start_date_patient').val();
  var end_date = $('#end_date_patient').val();
  var patient_code = $('#patient_code').val();
  var patient_name = $('#patient_name').val();
  var mobile_no = $('#mobile_no').val();
  var branch_id = $('#branch_id').val();
  var specialization_id = $('#specialization_id').val();
  $.ajax({
         url: "<?php echo base_url('prescription/advance_search/'); ?>", 
         type: 'POST',
         data: { start_date: start_date, end_date : end_date,branch_id:branch_id,patient_code:patient_code,patient_name:patient_name,mobile_no:mobile_no,specialization_id:specialization_id} ,
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
if(in_array('539',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('prescription_test')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });
}); 
<?php } ?>

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
 function delete_prescription(prescription_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('prescription/delete/'); ?>"+prescription_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
 function delete_gynecology_prescription(prescription_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('gynecology/gynecology_prescription/delete_gynic/'); ?>"+prescription_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

//$('#print-btn').click(function(){

  /*function print_prescription(id)
  {

    var printWindow = openPrintWindow('< ?php echo base_url(); ?>prescription/print_prescriptions/'+id, 'windowTitle', 'width=820,height=600');
     var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);

  }

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
      return printWindow;
    }

 function print_prescription_old(id){ 
  $.ajax({
      url: "< ?php echo base_url();?>prescription/print_prescription_pdf/"+id, 
      type: 'post',
      dataType: 'json',
      
      success: function(response){
        if(response.success)
        { 
          printdiv(response.pdf_template);
         }
         else
         {
          alert(response.msg);   
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
document.body.innerHTML = headstr+newstr+footstr;
window.print();

return false;
}*/
 
function delete_eye_prescription(prescription_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('eye/add_prescription/delete_eye/'); ?>"+prescription_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 $('document').ready(function(){
 <?php if(isset($_GET['status']) && $_GET['status']=='print' && !isset($_GET['type'])) { ?>
  $('#confirm_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('prescription');?>'; 
    }); 
       
  <?php }?>
  
   <?php if(isset($_GET['status']) && $_GET['status']=='print_eye' && !isset($_GET['type'])) { ?>
  $('#confirm_print_eye').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
        window.location.href='<?php echo base_url('prescription');?>'; 
    }); 
       
  <?php }?>
 });
</script> 
<!-- Confirmation Box -->
  
  <div id="confirm_print" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("prescription/print_prescriptions"); ?>');">Print</a>

           
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
     <div id="confirm_print_eye" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
          <!-- <div class="modal-body"></div> -->
          <div class="modal-footer">
            <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("eye/add_prescription/print_prescriptions"); ?>');">Print</a>

           
            <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
          </div>
        </div>
      </div>  
    </div>


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
</div><!-- container-fluid -->
</body>
</html>