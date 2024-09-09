<?php
$users_data = $this->session->userdata('auth_users');

?>
 <?php $new_branch_data=array();
            $users_data = $this->session->userdata('auth_users');
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            $parent_branch_details = $this->session->userdata('parent_branches_data');
            $p_ids=array();
            if(isset($parent_branch_details))
            {
                foreach($parent_branch_details as $parent_ids)
                {
                   $p_ids[]=$parent_ids['parent_id'];
                }
                $branch_ids = implode(',', $p_ids); 
            }
            else
            {
                $branch_ids=$users_data['parent_id'];
            }
            
           
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script> 

<!--new css-->
  <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
  rel = "stylesheet">
  <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

     <!--new css-->

     
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('367',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 

  $.ajax({
    url: "<?php echo base_url(); ?>medicine_entry/advance_search/",
    type: "post",
    data: $('#new_search_form').serialize(),
    success: function(result) 
    {
        table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('medicine_entry/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

       });
    }
  }); 
    
}); 
<?php } ?>


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#medicine_entry_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'medicine_entry/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'medicine_entry/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_medicine_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'medicine_entry/view/' ?>'+id,
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

function checkboxbranchtypeValues() 
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
     allbranch_typedata(allVals);
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
                      url: "<?php echo base_url('medicine_entry/deleteall');?>",
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

 function allbranch_typedata(allVals)
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
                      url: "<?php echo base_url('medicine_entry/allbranch_typedata');?>",
                      data: {row_id: allVals,'branch_id':'<?php echo $branch_ids ?>','self_id':'<?php echo $users_data['parent_id'];?>'},
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
    <div class="userlist-box">
  <form id="new_search_form">
     

    <div class="row">
      <div class="col-sm-4">
        <div class="row m-b-5">
           <div class="col-xs-5"><label>From Date</label></div>
           <div class="col-xs-7">
              <input type="text" name="start_date" value="<?php echo $form_data['start_date'];?>" id="start_date_p" class="datepicker m_input_default"  onkeyup="return form_submit();">
           </div>
        </div>
        
        <div class="row m-b-5">
           <div class="col-xs-5"><label>Medicine Name</label></div>
           <div class="col-xs-7">
              <input type="text" class="m_input_default" name="medicine_name" value="<?php echo $form_data['medicine_name'];?>" onkeyup="return form_submit();" autofocus="">
           </div>
        </div> 
          
         <div class="row m-b-5">
           <div class="col-xs-5"><label>Branches</label></div>
           <div class="col-xs-7">
              <select name="branch_type" onchange="return form_submit();" id="branch_type">
               <option value="self">Self</option>
               <option value="<?php echo $branch_ids;?>">Inherited</option>
              </select>
           </div>
        </div> 
      </div> <!-- 4 -->

      <div class="col-sm-4">
        <div class="row m-b-5">
           <div class="col-xs-5"><label>To Date</label></div>
           <div class="col-xs-7">
              <input type="text" name="end_date" id="end_date_p" value="<?php echo $form_data['end_date'];?>" class="datepicker m_input_default"  onkeyup="return form_submit();">
           </div>
        </div>
        
        <div class="row m-b-5">
           <div class="col-xs-5"><label>Medicine Company</label></div>
           <div class="col-xs-7">
              <input type="text" class="m_input_default" name="medicine_company" value="<?php echo $form_data['medicine_company'];?>" onkeyup="return form_submit();" id = "automplete-1" value="">
           </div>
        </div> 
      </div> <!-- 4 -->

      <div class="col-sm-4"> 
         
        <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
        <a href="javascript:void(0)" class="btn-a-search" id="adv_search_medicine"><i class="fa fa-cubes" aria-hidden="true"></i> Advance Search</a>
      </div> <!-- 4 -->
    </div> <!-- row -->



 </form>
       
    <form>
       <?php if(in_array('367',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_entry_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Medicine Code </th> 
                    <th> Medicine Name </th> 
                    <th> Medicine Company </th> 
                    <th> Packing </th> 
                    <th> Rack No. </th> 
                    <th> MRP</th>  
                    <th> Purchase Rate</th> 
                   <!-- <th>Discount</th> -->
                    <th> Status </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        <?php } ?>
          
    </form>


    

     
   </div> <!-- close -->





    <div class="userlist-right">
      <div class="btns">
               <?php if(in_array('368',$users_data['permission']['action'])) { ?>

                <div id="for_brach_type" class="hide">
                <button class="btn-update" id="deleteAll" onclick="return checkboxbranchtypeValues();">
                <i class="fa fa-plus"></i> Download
                </button>
                </div>
            

               
             <div id="for_other">
             <button class="btn-update" id="medicine_entry_add_modal">
              <i class="fa fa-plus"></i> New
             </button>
              <a href="<?php echo base_url('medicine_entry/medicine_entry_excel'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-excel-o"></i> Excel
              </a>

              <a href="<?php echo base_url('medicine_entry/medicine_entry_csv'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-word-o"></i> CSV
              </a>

              <a href="<?php echo base_url('medicine_entry/pdf_medicine_entry'); ?>" class="btn-anchor m-b-2">
              <i class="fa fa-file-pdf-o"></i> PDF
              </a>

             <!--  <a href="javascript:void(0)" class="btn-anchor m-b-2" id="deleteAll" onClick="return openPrintWindow('< ?php echo base_url("medicine_entry/print_medicine_entry"); ?>', 'windowTitle', 'width=820,height=600');">
              <i class="fa fa-print"></i> Print
              </a> -->

              <a href="javascript:void(0)" class="btn-anchor m-b-2"  onClick="return print_window_page('<?php echo base_url("medicine_entry/print_medicine_entry"); ?>');"> <i class="fa fa-print"></i> Print</a>

               <?php } ?>
               
               <?php 
            if(in_array('368',$users_data['permission']['action'])) { ?>
                <a href="<?php echo base_url('medicine_entry/sample_import_excel'); ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Sample(.xls)
                </a>
              <?php } if(in_array('368',$users_data['permission']['action'])) { ?>
                 <a id="open_model" href="javascript:void(0)" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Import(.xls)
                </a>
                <?php } ?>
                
               <?php if(in_array('370',$users_data['permission']['action'])) {
               ?>
             <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
              <i class="fa fa-trash"></i> Delete
             </button>
               <?php } ?>
               </div>

              
               <?php if(in_array('367',$users_data['permission']['action'])) {
               ?>

            <button class="btn-update" onclick="reload_table()">
            <i class="fa fa-refresh"></i> Reload
            </button>
              <?php } ?>
              <?php if(in_array('371',$users_data['permission']['action'])) {
              ?>
              <div id="for_archive">
            <button class="btn-exit" onclick="window.location.href='<?php echo base_url('medicine_entry/archive'); ?>'">
            <i class="fa fa-archive"></i> Archive
            </button>
            </div>
            <?php } ?>
            <button class="btn-exit" onclick="window.location.href='<?php echo base_url('medicine_entry'); ?>'">
            <i class="fa fa-sign-out"></i> Exit
            </button>
      </div>
    </div> 
    <!-- right -->
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>  
function clear_form_elements(ele) {
   $.ajax({url: "<?php echo base_url(); ?>medicine_entry?>/reset_search/", 
      success: function(result)
      { 
        $(ele).find(':input').each(function() {
                switch(this.type) {

                //case 'select-multiple':
                //case 'select-one':
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
 function delete_medicine_entry(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('medicine_entry/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 /*function openPrintWindow(url, name, specs) {
  var printWindow = window.open(url, name, specs);
    var printAndClose = function() {
        if (printWindow.document.readyState == 'complete') {
            clearInterval(sched);
            printWindow.print();
            printWindow.close();
        }
    }
    var sched = setInterval(printAndClose, 200);
};*/
$(document).ready(function() {
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
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
<script>

</script>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_add_ipd_label_summary_print_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_medicine_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div>
<script>
var $modal = $('#load_add_modal_popup');
  $('#adv_search_medicine').on('click', function(){
  // alert();
$modal.load('<?php echo base_url().'medicine_entry/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});

function form_submit()
{

  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   var branch_type= $('#branch_type').val();
  if(branch_type=="self")
  {
     $('#for_other').removeClass('hide');
     $('#for_brach_type').addClass('hide');
     $('#for_archive').removeClass('hide');
    
  }
  else
  {
    $('#for_other').addClass('hide');
    $('#for_brach_type').removeClass('hide');
    $('#for_archive').addClass('hide');
  }
  $.ajax({
    url: "<?php echo base_url(); ?>medicine_entry/advance_search/",
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
     maxDate : "+0d",
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
  $(document).ready(function (){
   var all_manufacturingcompany  =  
             [
              <?php
              $company_list= manuf_company_list();
              if(!empty($company_list))
              { 
                 foreach($company_list as $company)
                  { 
                    echo '"'.trim($company->company_name).'"'.',';  
                  }
              }   
              ?> 
            ];
            $( "#automplete-1" ).autocomplete({
               source: all_manufacturingcompany
            });
  })
function reset_search()
  { 
    $('#start_date_p').val('');
    $('#end_date_p').val('');
    $.ajax({url: "<?php echo base_url(); ?>medicine_entry?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }
var $modal = $('#load_medicine_import_modal_popup');
        $('#open_model').on('click', function(){
        //  alert();
      $modal.load('<?php echo base_url().'medicine_entry/import_medicine_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

      });
  function print_label(id)
  {
    var $modal = $('#load_add_ipd_label_summary_print_modal_popup');
    $modal.load('<?php echo base_url().'medicine_entry/print_template/'; ?>'+id,
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
  }

</script>
<!-- container-fluid -->
</body>
</html>