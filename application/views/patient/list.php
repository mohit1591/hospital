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



$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'patient/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});
 

$('#adv_search').on('click', function(){
$modal.load('<?php echo base_url().'patient/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

 //form_submit();
});

function edit_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/view/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function print_barcode_page(url)
{
  var printWindow = window.open( url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
  printWindow.addEventListener('load', function(){
  printWindow.print();
  //printWindow.close();
  }, true);

  
}

function barcode_patient(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/barcode/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_certificate(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'patient/doctor_certificate/' ?>'+id,
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
     $('.checklist').each(function() 
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
                      url: "<?php echo base_url('patient/deleteall');?>",
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


function reset_search(ele)
{ 
    $('#start_date_patient').val('');
    $('#end_date_patient').val('');
    $('#mobile_no').val('');
    $('#address').val('');
    $('#patient_name').val('');
    $('#patient_code').val('');
    $.ajax({url: "<?php echo base_url(); ?>patient/reset_search/", 
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
  $checkbox_list = get_checkbox_coloumns('4');
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
                        <td><input type="checkbox" class="tog-col" <?php  if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status))
                   { ?> checked="checked" <?php }else { $unchecked_column[] = $checkbox_list_data->coloum_id; } ?> value="<?php echo $checkbox_list_data->coloum_id; ?>" data-column="<?php echo $checkbox_list_data->coloum_id; ?>"></td>

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
    <form name="search_form_list"  id="search_form_list"> 

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
            <input name="patient_code" class="m_input_default" id="patient_code" onkeyup="return form_submit();"  value="<?php echo $form_data['patient_code']?>" type="text" autofocus="">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Mobile No.</label></div>
          <div class="col-xs-7">
            <input name="mobile_no" value="<?php echo $form_data['mobile_no']?>" id="mobile_no" onkeyup="return form_submit();" class="numeric m_input_default" maxlength="10" value="" type="text">
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
              
       
       
            <select name="branch_id" class="m_input_default" id="branch_id" onchange="return form_submit();">
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
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Address</label></div>
          <div class="col-xs-7">
            <input style=" margin-top: 5px;
    height: 40px;" name="address" value="<?php echo $form_data['address']?>" id="address" onkeyup="return form_submit();" class="alpha_space m_input_default" value="<?php echo $form_data['address']; ?>" type="text">
          </div>
        </div>


      </div> <!-- 4 -->

      <div class="col-sm-4 text-right">
          <!--Added By Nitin Sharma 04/02/2024-->
          <a class="btn-custom" id="scan_finger" onclick="captureFP()">Scan Finger</a>
          <!--Added By Nitin Sharma 04/02/2024-->
          <a class="btn-custom" id="reset_date" onclick="reset_search(this.form);">Reset</a>
          <a class="btn-custom" id="adv_search"><i class="fa fa-cubes"></i> Advance Search</a>
          
      </div> <!-- 4 -->
    </div> <!-- row -->
  
         
    </form>



    <form>
    <div class="hr-scroll">
       <?php if(in_array('113',$users_data['permission']['action'])): ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered patient_list_tbl" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="40" align="center"> <input onclick="selectall();" type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> <?php echo $data= get_setting_value('PATIENT_REG_NO');?> </th> 
                    <th> Patient Name </th> 
                    <th> Patient Relation </th>
                    <th> Mobile No. </th> 
                    <th> Gender </th>  
                    <th> Age </th> 
                    <th> Address </th> 
                    <th> Aadhaar No. </th>
                    <th> Marital Status </th>
                    <th> Anniversary </th>
                    <th> Religion </th>  
                    <th> DOB </th>
                    <th> Mother Name</th>
                    <th> Guardian Name </th>
                    <th> Guardian Email </th>
                    <th> Guardian Phone </th>
                    <th> Relation </th>
                    <th> Patient Email </th>
                    <th> Monthly Income </th>
                    <th> Occupation </th>
                    <th> Insurance Type </th>
                    <th> Insurance Name </th>
                    <th> Company Name </th>
                    <th> Policy No </th>
                    <th> TPA ID </th>
                    <th> Insurance Amount </th>
                    <th> Authorization No. </th>
                    <th> Created Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php endif;?>
        </div>
    </form>


   </div> <!-- close -->





  	<div class="userlist-right relative">
      <div class="fixed">
  		<div class="btns">
           <?php if(in_array('114',$users_data['permission']['action'])): ?>
  			<button class="btn-update" data-toggle="tooltip"  title="Add New patient" onclick="window.location.href='<?php echo base_url('patient/add'); ?>'">
  				<i class="fa fa-plus"></i> New
  			</button>

        <a data-toggle="tooltip"  title="Download list in excel" href="<?php echo base_url('patient/patient_excel'); ?>" class="btn-anchor m-b-2">
        <i class="fa fa-file-excel-o"></i> Excel
        </a>

        <a data-toggle="tooltip"  title="Download list in csv" href="<?php echo base_url('patient/patient_csv'); ?>" class="btn-anchor m-b-2">
        <i class="fa fa-file-word-o"></i> CSV
        </a>

        <a data-toggle="tooltip"  title="Download list in pdf" href="<?php echo base_url('patient/patient_pdf'); ?>" class="btn-anchor m-b-2">
        <i class="fa fa-file-pdf-o"></i> PDF
        </a>

        <a data-toggle="tooltip"  title="Print patient list" href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("patient/patient_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 

          <?php endif;?>
          <?php if(in_array('116',$users_data['permission']['action'])): ?>
  			<button data-toggle="tooltip"  title="Delete patient list" class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				<i class="fa fa-trash"></i> Delete
  			</button>
          <?php endif;?>

          <?php 
            if(in_array('627',$users_data['permission']['action'])) { ?>
                <a data-toggle="tooltip"  title="Sample export in excel" href="<?php echo base_url('patient/sample_import_patient_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample(.xls)
                </a>
          <?php } if(in_array('628',$users_data['permission']['action'])) { ?>
                 <a data-toggle="tooltip"  title="Import patient list" id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import(.xls)
                </a>
                <?php }

          ?>
          
          <button data-toggle="tooltip"  title="Archive patient list" class="btn-exit" onclick="window.location.href='<?php echo base_url('patient/feedback'); ?>'">
  				<i class="fa fa-comments-o"></i> Feedback
  			</button>
          
          <?php if(in_array('113',$users_data['permission']['action'])): ?>
               <button data-toggle="tooltip"  title="Page reload" class="btn-update" onclick="reload_table()">
                   <i class="fa fa-refresh"></i> Reload
               </button>
          <?php endif;?>
          <?php if(in_array('118',$users_data['permission']['action'])): ?>
  			<button data-toggle="tooltip"  title="Archive patient list" class="btn-exit" onclick="window.location.href='<?php echo base_url('patient/archive'); ?>'">
  				<i class="fa fa-archive"></i> Archive
  			</button>
          <?php endif;?>
        <button data-toggle="tooltip"  title="Exit from patient list" class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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
function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }

    $.ajax({
      url: "<?php echo base_url('patient/advance_search/'); ?>",
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
<?php if(in_array('113',$users_data['permission']['action'])): ?>
     $(document).ready(function() { 
         table = $('#table').DataTable({  
             "processing": true, 
             "serverSide": true, 
             "order": [], 
             "pageLength": '20',
             "ajax": {
                 "url": "<?php echo base_url('patient/ajax_list')?>",
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
<?php endif;?>

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
$(document).ready(function(){
   
   $('#selectAll').on('click', function () { 
                                 
         if ($("#selectAll").hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
    });
});
// function selectall()
// {

// }
 function delete_patient(patient_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('patient/delete/'); ?>"+patient_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 


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

$('#load_add_modal_popup').on('shown.bs.modal', function(e){
   $(this).find(".inputFocus").focus();
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

    <div id="confirm" class="modal fade dlt-modal">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
            <div class="modal-body" style="font-size:8px;">*Data that have been in Archive more than 60 days will be automatically deleted.</div> 
            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
            </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_patient_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->


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
            window.location = "<?php echo base_url(); ?>patient";
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
                            window.location = "<?php echo base_url(); ?>patient";
                            }, 1300); 
                        }
                        else
                        {
                            flash_session_msg("Record Updated Successfully");
                           setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>patient";
                            }, 1300); 
                        }

                    }

                });
        }); 
    }

      var $modal = $('#load_patient_import_modal_popup');
        $('#open_model').on('click', function(){
        //  alert();
      $modal.load('<?php echo base_url().'patient/import_patient_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

      });
</script>
<!--Added By Nitin Sharma 04/02/2024-->
<script type="text/javascript">

    function captureFP() {
      document.getElementById("scan_finger").textContent = "Processing";
      CallSGIFPGetData(SuccessFunc, ErrorFunc);
    }
    function SuccessFunc(result) {
      if (result.ErrorCode == 0) {
        getPatientLists($('#branch_id').val(), function (res1) {
          const outputArray = [];
          res1.map(e=> {
            // console.log(e.capture_finger, result.TemplateBase64);
            matchScore(e.capture_finger, result.TemplateBase64, function (res2) {
               if (res2.MatchingScore >= 100) { outputArray.push(e); }
            })
          })
          if(outputArray.length > 0) {
            console.log(outputArray[0]['id'],$('#branch_id').val());
            view_patient(outputArray[0]['id'],$('#branch_id').val());
          } else {
            alert('No data found.')
          }
        });
      }
      else {
        alert("Fingerprint Not Captured Properly. Error Code:  " + result.ErrorCode);
      }
      document.getElementById("scan_finger").textContent = "Scan Finger";
    }
    function ErrorFunc(status) {
      alert("Check If Fingerprint Device Is Connected?");
      document.getElementById("scan_finger").textContent = "Scan Finger";
    }

    function CallSGIFPGetData(successCall, failCall) {
      var uri = "https://localhost:8443/SGIFPCapture";

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          fpobject = JSON.parse(xmlhttp.responseText);
          successCall(fpobject);
        } else if (xmlhttp.status == 404) {
          failCall(xmlhttp.status);
        }
      };
      var params = "Timeout=" + "10000";
      params += "&Quality=" + "50";
      params += "&licstr=" + "";
      params += "&templateFormat=" + "ISO";
      params += "&imageWSQRate=" + "0.75";
      console.log;
      xmlhttp.open("POST", uri, true);
      xmlhttp.send(params);

      xmlhttp.onerror = function () {
        failCall(xmlhttp.statusText);
      };
    }

    function getPatientLists(branchId, callback) {
      $.ajax({
        url: "<?php echo base_url(); ?>patient/patientFingerTemplateBase64Lists",
        type: "POST",
        data: { branch_id: branchId },
        success: function (response) {
          var parsedResponse = JSON.parse(response);
          callback(parsedResponse); 
        },
        error: function (xhr, status, error) {
          var errorMessage = xhr.status + ': ' + xhr.statusText;
          console.error('Request failed with error: ' + errorMessage);
          callback([]); 
        }
      });
    }

    function matchScore(template_1, template_2, succFunction, failFunction) {
      var uri = "https://localhost:8443/SGIMatchScore";

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function () {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          fpobject = JSON.parse(xmlhttp.responseText);
          succFunction(fpobject);
        }
        else if (xmlhttp.status == 404) {
          failFunction(xmlhttp.status)
        }
      }

      xmlhttp.onerror = function () {
        failFunction(xmlhttp.status);
      }
      var params = "template1=" + encodeURIComponent(template_1);
      params += "&template2=" + encodeURIComponent(template_2);
      params += "&licstr=" + "";
      params += "&templateFormat=" + "ISO";
      xmlhttp.open("POST", uri, false);
      xmlhttp.send(params);
    }



  </script>
<!--Added By Nitin Sharma 04/02/2024-->
</body>
</html>