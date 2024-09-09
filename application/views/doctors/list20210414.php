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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>pwdwidget.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>pwdwidget.js"></script>
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  
<script type="text/javascript">
$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#doctor_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'doctors/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
  
$modal.modal('show');
});

});
});

function edit_doctors(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'doctors/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_doctors(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'doctors/view/' ?>'+id,
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
                      url: "<?php echo base_url('doctors/deleteall');?>",
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

<?php

?>

<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">

<!-- for column list filtering -->

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
  $checkbox_list = get_checkbox_coloumns('3');
  $module_id = $checkbox_list[0]->module;
?>

<!-- //////////////////////[ Left side bar ]////////////////////// -->
<?php if($users_data['emp_id']==0)
{?>
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

      if($checkbox_list_data->selected_status > 0 && is_numeric($checkbox_list_data->selected_status)) 
      { 
      
      } 
      else
      {
        $unchecked_column[] = $checkbox_list_data->coloum_id;
      }
  }

 } 
?>

<!-- for column list filtering -->



  <div class="userlist-box">
  <!-- // -->
<div class="row m-b-5">
     <div class="col-md-12">
          <div class="row">
               <div class="col-md-6">
               
               </div> <!-- 6 -->
               <div class="col-md-6 text-right">
                    <!-- <a href="javascript:void(0)" class="btn-custom" id="adv_search">
                     <i class="fa fa-cubes" aria-hidden="true"></i> 
                     Advance Search
                   </a> -->
               </div> <!-- 6 -->
          </div> <!-- innerRow -->
     </div> <!-- 12 -->
</div> <!-- row -->




  
  <form name="search_form"  id="search_form">   
  <table class="ptl_tbl">
    <tr>
      <td><label><b>From Date</b></label><input id="start_date" name="start_date" class="datepicker start_datepicker2 m_input_default" value="<?php echo $form_data['start_date'];?>" type="text"></td>
      <td><label><b>To Date</b></label><input name="end_date" id="end_date" class="datepicker datepicker_to end_datepicker2 m_input_default" value="<?php echo $form_data['end_date'];?>" type="text"></td>
      <td> 
            
          <a class="btn-custom" id="adv_search"><i class="fa fa-cubes"></i> Advance Search</a>
          <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
      </td>
    </tr>
    <tr>
      <td><label><b>Doctor Code</b></label><input name="doctor_code" value="<?php echo $form_data['doctor_code'];?>" id="doctor_code" onkeyup="return list_form_submit();" class="upper_case m_input_default m_input_default"  value="" type="text" autofocus></td>
      <td><label><b>Doctor Name</b></label><input name="doctor_name" id="doctor_name" onkeyup="return list_form_submit();" class="alpha_space m_input_default" value="<?php echo $form_data['doctor_name'];?>"type="text"></td>
    </tr>
    <tr>
      <td>
         <label><b>Specialization</b></label>
        <select name="specialization_id" id="specialization_id" class="m_input_default"  onchange="return list_form_submit();">
            <option value=""> Select Specialization </option>
            <?php
              if(!empty($specialization_list))
              {
                foreach($specialization_list as $specialization)
                {
                ?>
                   <option value="<?php echo $specialization->id; ?>"><?php echo $specialization->specialization; ?></option>
                <?php   
                }
              } 
            ?>
        </select>
      </td>
      <td>
        <label><b>Doctor Type</b></label>
        <select name="doctor_type" id="doctor_type" class="m_input_default" onchange="return list_form_submit();">
            <option value=""> Select Doctor Type </option> 
            <option value="0">Referral</option>
            <option value="1">Attended</option>
            <option value="2">Referral/Attended</option>
        </select>  
      </td>

    </tr>
    <tr>
      <td><label><b>Mobile No.</b></label><input name="mobile_no" id="mobile_no" onkeyup="return list_form_submit();" class="numeric m_input_default" maxlength="10" value="<?php echo $form_data['mobile_no'];?>" type="text"></td>
      <td>
        
        <?php  
        $option='';
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
             <?php if(in_array('1',$permission_section)){ ?> 
          <label>Branch</label>
       
       
            <select name="branch_id" class="m_input_default" id="branch_id" onchange="return list_form_submit();">
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
          

          
<?php } else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php } ?>
      </td>
    </tr>
  </table>
  <!-- // -->
  </form>

    <form>

        <div class="hr-scroll">

          <?php  if(in_array('121',$users_data['permission']['action'])): ?>
               <!-- bootstrap data table -->
               <table id="table" class="table table-striped table-bordered doctor_list" cellspacing="0" width="100%">
                    <thead class="bg-theme">
                         <tr>
                              <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                                
                              <th> Doctor Code </th> 
                              <th> Doctor Name </th> 
                              <th> Specialization </th>
                              <th> Mobile </th> 
                              <th> Doctor Type </th>
                              <th> Sharing Pattern </th>
                              <th> Marketting Person </th>
                              <th> Consultant Charge </th>
                              <th> Emergency Charge </th>
                              <th> DOB </th>
                              <th> Anniversary </th>
                              <th> Doctor Panel Type </th>
                              <th> Doctor Schedule Type </th>
                              <th> Address </th>
                              <th> Email </th>
                              <th> Alternate No. </th>
                              <th> Landline No. </th>
                              <th> Pan No. </th>
                              <th> Reg No. </th>
                              <th> Per Patient Time </th>
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
               <?php if(in_array('122',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="doctor_add_modal">
  				    <i class="fa fa-plus"></i> New
  			     </button>

              <a href="<?php echo base_url('doctors/doctors_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('doctors/doctors_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('doctors/doctors_pdf'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

              <!-- <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return openPrintWindow('< ?php echo base_url("doctors/doctors_print"); ?>', 'windowTitle', 'width=820,height=600');">
              <i class="fa fa-print"></i> Print
              </a> -->

               <a href="javascript:void(0)" class="btn-anchor m-b-2" onClick="return print_window_page('<?php echo base_url("doctors/doctors_print"); ?>');">
              <i class="fa fa-print"></i> Print
              </a> 

               <?php } ?>
                <?php 
            if(in_array('122',$users_data['permission']['action'])) { ?>
                <a href="<?php echo base_url('doctors/sample_import_doctors_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample(.xls)
                </a>
          <?php } if(in_array('122',$users_data['permission']['action'])) { ?>
                 <a id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import(.xls)
                </a>
                <?php }

          ?>
          
               <?php if(in_array('124',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
               <?php } ?>
               <?php if(in_array('121',$users_data['permission']['action'])) {
               ?>

                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
               <?php if(in_array('126',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" onclick="window.location.href='<?php echo base_url('doctors/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               <?php } ?>
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
<script>
function list_form_submit(vals)
{
  $.ajax({
    url: "<?php echo base_url('doctors/advance_search/'); ?>",
    type: "post",
    data: $('#search_form').serialize(),
    success: function(result) 
    { 
      if(vals!='1')
      {
        reload_table();  
      }       
    }
  });
}

function form_submit(vals)
{
  $.ajax({
    url: "<?php echo base_url('doctors/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      if(vals!='1')
      {
        reload_table();  
      }       
    }
  });
}

form_submit(1);

var save_method; 
var table;
<?php if(in_array('121',$users_data['permission']['action'])): ?>
     $(document).ready(function() { 
          table = $('#table').DataTable({  
               "processing": true, 
               "serverSide": true, 
               "order": [], 
               "pageLength": '20',
               "ajax": {
                    "url": "<?php echo base_url('doctors/ajax_list')?>",
                    "type": "POST",
                 
               }, 
               "columnDefs": [{ 
                    "targets": [ 0 , -1 ], //last column
                    "orderable": false, //set not orderable
               },],
          });

      $('.tog-col').on( 'click', function (e) 
      {
        var column = table.column( $(this).attr('data-column') );
        column.visible( ! column.visible() );
      });
  }); 
<?php endif; ?>


function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>doctors/reset_search/", 
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
var $modal_advance = $('#load_add_modal_popup');

$('#adv_search').on('click', function(){

$modal_advance.load('<?php echo base_url().'doctors/advance_search/' ?>',
{ 
},
function(){
$modal_advance.modal('show');
});

});
});


$('#selectAll').on('click', function () { 
    if ($(this).hasClass('allChecked')) {
        $('.checklist').prop('checked', false);
    } else {
        $('.checklist').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
})


 function delete_doctors(doctors_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('doctors/delete/'); ?>"+doctors_id, 
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


  $('.start_datepicker2').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    endDate : new Date(), 
  }).on("change", function(selectedDate) 
  { 
      var start_data = $('.start_datepicker2').val();
      $('.end_datepicker2').datepicker('setStartDate', start_data); 
      list_form_submit();
  });

  $('.end_datepicker2').datepicker({
    format: 'dd-mm-yyyy',     
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {   
     list_form_submit();
  });
 

  function reset_search()
  { 
    $('#start_date').val('');
    $('#end_date').val('');
    $('#mobile_no').val('');
    $('#doctor_code').val('');
    $('#doctor_name').val('');
    $('#specialization_id').val('');
    $('#doctor_type').val('');

    $.ajax({url: "<?php echo base_url(); ?>doctors/reset_search/", 
      success: function(result)
      { 
            
        //document.getElementById("search_form").reset(); 
        reload_table();
      } 
    }); 
  } 

$(document).ready(function() {
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 
</script> 
<!-- Confirmation Box -->

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
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_doctors_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->


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
            window.location = "<?php echo base_url(); ?>doctors";
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
                            window.location = "<?php echo base_url(); ?>doctors";
                            }, 1300); 
                        }
                        else
                        {
                            flash_session_msg("Record Updated Successfully");
                           setTimeout(function () {
                            window.location = "<?php echo base_url(); ?>doctors";
                            }, 1300); 
                        }

                    }

                });
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

 var $modal = $('#load_doctors_import_modal_popup');
        $('#open_model').on('click', function(){
        //  alert();
      $modal.load('<?php echo base_url().'doctors/import_doctors_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

      });
</script>
<?php    
}
?>


</body>
</html>
<div id="load_add_specialization_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_comission_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_rate_modal_popup" class="modal fade modal-60" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_transaction_modal_popup" class="fgdfgdf modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>