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

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

<script type="text/javascript">

// Function to load list by ajax


// Function to open add page
function add(id)
{
  window.location.href='<?php echo base_url().'blood_bank/donor/add'; ?>';
}
// Function to open add page

function view_stock_details(id,blood_grp_id,component_id)
{
  window.location.href='<?php echo base_url().'blood_bank/stock/view/'?>'+id+'/'+blood_grp_id+'/'+component_id;
 
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
                      url: "<?php echo base_url('advance_payment/deleteall');?>",
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

</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
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
          <div class="col-xs-5"><label>Component</label></div>
          <div class="col-xs-7">
          <select name="component_id" class="m_input_default" id="component_id" onchange="return form_submit();">
              <option value="">Select Component</option>
                 <?php
                  if($component_list!="empty")
                  {
                    foreach($component_list as $component)
                    {
                     
                     
                        echo '<option value='.$component->id.'>'.$component->component.'</option>'; 
                      
                    }
                  }
                ?>
            </select>
          

          </div>
        </div>
        
         

<?php //} else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php //} ?>
      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
          <div class="col-xs-5"><label>To Date</label></div>
          <div class="col-xs-7">
            <input name="end_date" id="end_date_patient" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo $form_data['end_date']?>" type="text">
          </div>
        </div>
        <!-- <div class="row m-b-5">
          <div class="col-xs-5"><label>Bar Code</label></div>
          <div class="col-xs-7">
            <input name="bar_code" value="<?php //echo $form_data['bar_code']?>" id="bar_code" onkeyup="return form_submit();" class="m_input_default" maxlength="10" value="" type="text">
          </div>
        </div> -->
         <div class="row m-b-5">
          <div class="col-xs-5"><label>Blood Group</label></div>
          <div class="col-xs-7">
              
       
     
            <select name="blood_group" class="m_input_default" id="blood_group" onchange="return form_submit();">
              <option value="">Select Blood Group</option>
                  <?php
                  //print_r($blood_groups);
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
          <div class="col-xs-5"><label>Stock</label></div>
          <div class="col-xs-7">
            <input name="stock_value" value="1" id="stock_value" <?php if($form_data['stock_value']=='1'){ echo 'selected="selected"';} ?> onClick="return check_stock_value();"    type="radio"> All
            <input name="stock_value" value="2" id="stock_value" <?php if($form_data['stock_value']=='2'){ echo 'selected="selected"';} ?> onClick="return check_stock_value();"   type="radio"> In-stock
            
            <input name="stock_value" value="3" id="stock_value" <?php if($form_data['stock_value']=='3'){ echo 'selected="selected"';} ?> onClick="return check_stock_value();"   type="radio"> Issued
            
          </div>
        </div> 
      </div> 
      
     

        <!-- 4 -->

      <div class="col-sm-4 text-right">
        <a class="btn-custom" id="reset_date" onclick="reset_search(this.form);">Reset</a>
          <a class="btn-custom" id="adv_search"><i class="fa fa-cubes"></i> Advance Search</a>
          
      </div> <!-- 4 -->
    </div> <!-- row -->
  
         
    </form>
    <div class="userlist-box">
       <?php 

       if(in_array('1542',$users_data['permission']['action'])) {
        ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered donor_list" cellspacing="0" width="100%">
            <thead>
               <!--  <tr>
                    
                    <th> Sr.No. </th>
                    <th> Blood Group </th>
                    <th> Component  </th>
                    <th> Quantity </th>
                    <th> Action </th>
                </tr> -->

                <tr>
                   
                    <th> Select </th>
                    <th> Donor ID </th>
                    <th> Barcode </th>
                    <th> Blood Group </th>
                    
                    <th> Component Type </th>
                    <th> Bag Type </th>
                    <th> Volumne </th>
                    <th> Created Date </th>
                    <th> Expiry Date </th>
                    
                    <th> Status </th>
                     <th> Qty </th>
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>
    
   </div> <!-- close -->

    <div class="userlist-right relative">
      <div class="fixed">
      <div class="btns">
      
            <a href="<?php echo base_url().'blood_bank/stock/stock_dashboard'?>"><button class="btn-update" id="modal_add">
          <i class="fa fa-plus"></i> Stock Dashboard
        </button></a>
        
         <a href="<?php echo base_url('blood_bank/stock/stock_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

   
         <a data-toggle="tooltip"  title="Sample export in excel" href="<?php echo base_url('blood_bank/stock/sample_stock_import_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample1(.xls)
                </a>
       <a data-toggle="tooltip"  title="Import Stock list" onclick="return add_stock_item();" id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import1(.xls)
                </a>

<a data-toggle="tooltip"  title="Sample export in excel" href="<?php echo base_url('blood_bank/opening_stock/sample_stock_import_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample2(.xls)
                </a>
       <a data-toggle="tooltip"  title="Import Stock list" onclick="return add_opening();" id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import2(.xls)
                </a>
                
     
          
      </div>
      </div>
    </div> 
  
 
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
          <!-- <div class="modal-body"></div> -->
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

 function add_opening()
    {    
         var $modal = $('#load_stock_import_modal_popup');
      $modal.load('<?php echo base_url().'blood_bank/opening_stock/import_stock_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

      }
      
    function add_stock_item()
    {    
         var $modal = $('#load_stock_import_modal_popup');
      $modal.load('<?php echo base_url().'blood_bank/stock/import_stock_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

    }
      
      
$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#adv_search').on('click', function(){
$modal.load('<?php echo base_url().'blood_bank/stock/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});
});
function form_submit(vals)
{   
    if(vals!='1')
    {
      $('#overlay-loader').show(); 
    }
   // alert();
    $.ajax({
      url: "<?php echo base_url('blood_bank/stock/advance_search/'); ?>",
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
if(in_array('1542',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('blood_bank/stock/ajax_list')?>",
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

  function reset_search(ele)
{ 

    $('#start_date_patient').val('');
    $('#end_date_patient').val('');
    $('#donor_id').val('');
    $('#bar_code').val('');
    $('#blood_group').val('');
    $('#component_id').val('');
    $('#status').val('');
    $.ajax({url: "<?php echo base_url(); ?>blood_bank/stock/reset_search/", 
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

$('#load_add_modal_popup').on('shown.bs.modal', function(e){
   $(this).find(".inputFocus").focus();
});

function check_stock_value()
{
    var stock_value = document.getElementById('stock_value').value;
    if(stock_value.length == 1)
    {
       form_submit();
    }
    else
    {
        form_submit();
    }
}
</script>

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
