<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">
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


// Function to load list by ajax


// Function to open add page
function add(id)
{
  window.location.href='<?php echo base_url().'blood_bank/donor/add'; ?>';
}
// Function to open add page
$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#adv_search').on('click', function(){
$modal.load('<?php echo base_url().'blood_bank/donor/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});
});



function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}


  //////
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
        .on('click', '#delete', function(e)
        {
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('blood_bank/donor/deleteall');?>",
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
<!-- advance search -->

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
          <div class="col-xs-5"><label>Donor Id</label></div>
          <div class="col-xs-7">
            <input name="donor_id" class="m_input_default" id="donor_id" onkeyup="return form_submit();"  value="<?php echo $form_data['donor_id']?>" type="text" autofocus="">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Donor Name</label></div>
          <div class="col-xs-7">
            <input name="donor_name" value="<?php echo $form_data['donor_name']?>" id="donor_name" onkeyup="return form_submit();" class="alpha_space  m_input_default" maxlength="10" value="" type="text">
          </div>
        </div>
         

<?php //} else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php //echo $users_data['parent_id']; ?>">
<?php //} ?>
      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
        <div class="row m-b-5">
          <div class="col-xs-5"><label>Blood Group</label></div>
          <div class="col-xs-7">
              
       
     
            <select name="blood_group" class="m_input_default" id="blood_group" onchange="return form_submit();">
              <option value="">Select Blood Group</option>
                <?php
                  if($blood_groups!="empty")
                  {
                    foreach($blood_groups as $bg)
                    {
                      if($donor_data!="empty")
                      {  
                        if($donor_data['blood_group_id']==$bg->id)
                            $bgselect="selected=selected";
                          else
                            $bgselect="";

                        echo '<option value='.$bg->id.' '.$bgselect.' >'.$bg->blood_group.'</option>';
                      }
                      else
                      {
                        echo '<option value='.$bg->id.'>'.$bg->blood_group.'</option>'; 
                      }
                    }
                  }
                ?>
            </select>
          

          </div>
        </div>
        <div class="row m-b-5">
      
          <div class="col-xs-5"><label>QC result</label></div>
            <div class="col-md-7">
             
   
              <label><input type="radio" id='qc_result' name="qc_result" value="1" onchange="return form_submit();"> Accepted</label> &nbsp;
            <label> <input type="radio" id='qc_result' name="qc_result" value="2"  onchange="return form_submit();"> Rejected</label>
       
            </div>
          </div>
          <div class="row m-b-5">
        
           <div class="col-xs-5"><label>Outcome</label></div>
            <div class="col-md-7">
              <label><input type="radio" id='outcome' name="outcome" value="1" <?php if(isset($form_data['outcome']) &&  $form_data['outcome']!="" && $form_data['outcome']==1){ echo 'checked="checked"'; } ?> onchange="return form_submit();"> Accepted &nbsp;
            <label><input type="radio" id='outcome' name="outcome" value="2" <?php if(isset($form_data['outcome']) &&  $form_data['outcome']!="" && $form_data['outcome']==2){ echo 'checked="checked"'; } ?> onchange="return form_submit();"> Temporary</label>
        <label><input type="radio" id='outcome' name="outcome" value="3" <?php if(isset($form_data['outcome']) &&  $form_data['outcome']==3){ echo 'checked="checked"'; } ?> onchange="return form_submit();"> Permanent</label>
            </div>
          </div>
        


      </div> <!-- 4 -->

      <div class="col-sm-4 text-right">
        <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
          <a class="btn-custom" id="adv_search"><i class="fa fa-cubes"></i> Advance Search</a>
          <!--<input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">-->
      </div> <!-- 4 -->
    </div> <!-- row -->
  
         
    </form>

<!--advance search -->



    <div class="userlist-box">
       <?php 

       if(in_array('1532',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
       <div class="table-responsive">
        <table id="table" class="table table-striped table-bordered donor_list" >
            <thead>
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    

                    <th> Donor Id </th> 
                    <th> Donor Name </th> 
                    <th> Mobile No. </th> 
                    <th> Gender </th>  
                    <th> Age </th> 
                    <th> Blood Group </th>
                    <!--<th> Donation Mode </th>
                    <th> Previous Donation Date </th> 
                    <th> Reminder Service </th> 
                    <th> Height (cm) </th> -->
                    <th> Weight (kg) </th> 
                    <th> Remarks </th> 
                    <th> Status </th>
                    <th> Created Date </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
      </div>
        <?php } ?>
    
   </div> <!-- close -->
    <div class="userlist-right relative">
      <div class="fixed">
      <div class="btns">
      <?php if(in_array('1533',$users_data['permission']['action'])) { ?>
            <a href="<?php echo base_url().'blood_bank/donor/add' ?>"><button class="btn-update" id="modal_add">
          <i class="fa fa-plus"></i> New
        </button></a>
      <?php } ?>

      <?php if(in_array('1535',$users_data['permission']['action'])) { ?>
        <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
          <i class="fa fa-trash"></i> Delete
        </button>
      <?php } ?>

      <?php if(in_array('1536',$users_data['permission']['action'])) { ?>
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('blood_bank/donor/archive'); ?>'">
          <i class="fa fa-archive"></i> Archive
        </button>
      <?php } ?>
       <?php if(in_array('1540',$users_data['permission']['action'])) { ?>
       <a href="<?php echo base_url('blood_bank/donor/donor_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel</a>
      <?php } ?>
      
      
      <a data-toggle="tooltip"  title="Sample export in excel" href="<?php echo base_url('blood_bank/donor/sample_donor_import_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample1(.xls)
                </a>
       <a data-toggle="tooltip"  title="Import Stock list" onclick="return add_donor_sample();" id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import1(.xls)
                </a>
                
                
      
      
      
      <a data-toggle="tooltip"  title="Sample export in excel" href="<?php echo base_url('blood_bank/opening_stock/sample_donor_import_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample2(.xls)
                </a>
       <a data-toggle="tooltip"  title="Import Stock list" onclick="return add_donor();" id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import2(.xls)
                </a>
                

            <button class="btn-update" onclick="reload_table()">
              <i class="fa fa-refresh"></i> Reload
            </button>
         <button class="btn-exit" onclick="window.location.href='<?php echo base_url('dashboard'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
      </div>
      </div>
    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->
</section> <!-- cbranch -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<?php
$this->load->view('include/footer');
?>


<!-- Confirmation Box -->

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

<!-- Confirmation Box end -->
<div id="load_add_donor_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->


<script>
    function add_donor()
    {    
         var $modal = $('#load_stock_import_modal_popup');
        $modal.load('<?php echo base_url().'blood_bank/opening_stock/import_donor_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

    }
    
     function add_donor_sample()
    {    
         var $modal = $('#load_stock_import_modal_popup');
        $modal.load('<?php echo base_url().'blood_bank/donor/import_donor_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

    }
      
function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }
   // alert();
    $.ajax({
      url: "<?php echo base_url('blood_bank/donor/advance_search/'); ?>",
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

// Function to load list by ajax
<?php
if(in_array('1532',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('blood_bank/donor/ajax_list')?>",
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

// function to open edit donor page
function edit_donor_details(donor_id)
{
  window.location.href="<?php echo base_url(); ?>blood_bank/donor/add/"+donor_id;
}
// function to open edit donor page


function add_examination_details(donor_id)
{
  window.location.href="<?php echo base_url(); ?>blood_bank/donor_examinations/add/"+donor_id;
}


function view_donor_details(donor_id)
{
  window.location.href="<?php echo base_url(); ?>blood_bank/donor/view_donor/"+donor_id;
}


function delete_donor(id)
{
  $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
        })
        .one('click', '#delete', function(e)
        {
            $.ajax({
                  type: "POST",
                  url: "<?php echo base_url('blood_bank/donor/delete_donor');?>",
                  data: {row_id: id},
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

function clear_form_elements(ele)
{ 
    //$('#outcome').val('');
    // $('#start_date_patient').val('');
    // $('#end_date_patient').val('');
    // //$('#qc_result').val('');
    // $('#blood_group').val('');
    // $('#donor_name').val('');
    // $('#donor_id').val('');
    $.ajax({url: "<?php echo base_url(); ?>blood_bank/donor/reset_search/", 
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
            <!-- <div class="modal-body"></div> -->
            <div class="modal-footer">
               <button type="button" data-dismiss="modal" class="btn-update" id="delete">Confirm</button>
               <button type="button" data-dismiss="modal" class="btn-cancel">Cancel</button>
            </div>
        </div>
      </div>  
    </div> <!-- modal -->

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_stock_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</body>
</html>
