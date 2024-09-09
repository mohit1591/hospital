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

$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('pediatrician/vaccination/ajax_list')?>",
            "type": "POST",
        }, 
        "columnDefs": [
        { 
            "targets": [ 0 , -1 ], //last column
            "orderable": false, //set not orderable

        },
        ],

    });

     form_submit();
}); 


$(document).ready(function(){
var $modal = $('#load_add_modal_popup');
$('#vaccination_entry_add_modal').on('click', function(){
$modal.load('<?php echo base_url().'pediatrician/vaccination/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_vaccination_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'pediatrician/vaccination/edit/' ?>'+id,
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function view_vaccination_entry(id)
{
  var $modal = $('#load_add_modal_popup');
  $modal.load('<?php echo base_url().'pediatrician/vaccination/view/' ?>'+id,
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
                      url: "<?php echo base_url('pediatrician/vaccination/deleteall');?>",
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
    <div class="userlist-box" style="width:100% !important">  
      <div id="permission_section">
           <div class="prescription_permission" style="width:100%;">
           <div> <?php  $uri_url = $this->uri->segment(2); ?>
            <ul class="prescription_menu">
            
              <li class="per_menu_tab <?php if($uri_url=='age_vaccine_master'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('pediatrician/age_vaccine_master');?>';" name="new_patient"> Age Vaccine Master </span></label> </strong>
                
              </li>
              <li class="per_menu_tab <?php if($uri_url=='age_vaccination'){ ?> active <?php } ?>">
                <strong><label><span onClick="window.location='<?php echo base_url('pediatrician/age_vaccination');?>';"> Age Vaccination </span></label> </strong>
                
              </li>
               <li class="per_menu_tab <?php if($uri_url=='pediatrician_age_chart'){ ?> active <?php } ?>">
                <strong><label><span  onClick="window.location='<?php echo base_url('pediatrician/pediatrician_age_chart');?>';"> Pediatrician Chart </span>
                    </label>
                 </strong>
                </li>
              
                
                
                <li class="per_menu_tab <?php if($uri_url=='vaccination'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/vaccination');?>';">Vaccination Master </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='previous_history'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/previous_history');?>';"> Previous History </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='chief_complaints'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/chief_complaints');?>';"> Chief Complaints </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='medicine_company'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/medicine_company');?>';" > Manufacturing Company </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='diagnosis'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/diagnosis');?>';"> Diagnosis </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='test_name'){ ?> active <?php } ?>">
                <strong><label>
                    <span  onClick="window.location='<?php echo base_url('pediatrician/test_name');?>';">Test Name </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='type'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/type');?>';"> Medicine Unit </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='personal_history'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/personal_history');?>';"> Personal History </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='medicine'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/medicine');?>';"> Medicine </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='dosage'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/dosage');?>';"> Dosage </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='duration'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/duration');?>';"> Duration </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='frequency'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/frequency');?>';"> Frequency </span>
                    </label>
                 </strong>
                </li>
                <li class="per_menu_tab <?php if($uri_url=='advice'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/advice');?>';"> Advice </span>
                    </label>
                 </strong>
                </li>
                
                <li class="per_menu_tab <?php if($uri_url=='suggestion'){ ?> active <?php } ?>">
                <strong><label>
                    <span onClick="window.location='<?php echo base_url('pediatrician/suggestion');?>';"> Suggestion </span>
                    </label>
                 </strong>
                </li>
                
                
                
            </ul>
            </div>
        </div>
     </div>
</div>
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
           <div class="col-xs-5"><label>Vaccination Name</label></div>
           <div class="col-xs-7">
              <input type="text" class="m_input_default" name="vaccination_name" value="<?php echo $form_data['vaccination_name'];?>" onkeyup="return form_submit();" autofocus="">
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
           <div class="col-xs-5"><label>Mfg. Company</label></div>
           <div class="col-xs-7">
              <input type="text" class="m_input_default" name="vaccination_company" value="<?php echo $form_data['vaccination_company'];?>" onkeyup="return form_submit();" id = "automplete-1" value="">
           </div>
        </div> 
      </div> <!-- 4 -->

      <div class="col-sm-4"> 
        
        <input value="Reset" class="btn-custom" onclick="clear_form_elements(this.form)" type="button">
      </div> <!-- 4 -->
    </div> <!-- row -->



 </form>
 
    	 
    <form>
      
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_entry_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th align="center" width="40"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                    <th> Vaccination Code </th> 
                    <th> Vaccination Name </th> 
                    <th> Mfg. Company </th> 
                    <th> Status </th> 
                    <th> Action </th>
                </tr>
            </thead>  
        </table>
        
          
    </form>


    

     
   </div> <!-- close -->





  	<div class="userlist-right">
  		<div class="btns">
               
  			     <button class="btn-update" id="vaccination_entry_add_modal">
  				    <i class="fa fa-plus"></i> New
  			     </button>
            
  			     <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
  				    <i class="fa fa-trash"></i> Delete
  			     </button>
             
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
             <button class="btn-exit" onclick="window.location.href='<?php echo base_url('pediatrician/vaccination/archive'); ?>'">
  				    <i class="fa fa-archive"></i> Archive
  			     </button>
               
               

        <button class="btn-exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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
$(document).ready(function(){
  reload_table();
  $('#selectAll').on('click', function () {
 // alert(); 
    if ($("#selectAll").hasClass('allChecked')) {

    $('.checklist').prop('checked', false);
    } else {
    $('.checklist').prop('checked', true);
    }
    $(this).toggleClass('allChecked');
  });
});
 function delete_vaccination_entry(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pediatrician/vaccination/delete/'); ?>"+id, 
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
</script> 
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

<div id="load_vaccination_import_modal_popup" class="modal fade modal-40" role="dialog" data-backdrop="static" data-keyboard="false"></div>


<div id="load_add_vaccination_manuf_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script>
function clear_form_elements(ele) 
{
    $.ajax({url: "<?php echo base_url(); ?>pediatrician/vaccination/reset_search/", 
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


var $modal = $('#load_add_modal_popup');
  $('#adv_search_vaccine').on('click', function(){
$modal.load('<?php echo base_url().'pediatrician/vaccination/advance_search/' ?>',
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
   
  $.ajax({
    url: "<?php echo base_url(); ?>pediatrician/vaccination/advance_search/",
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

function reset_search()
  { 
    $('#start_date_p').val('');
    $('#end_date_p').val('');
    $.ajax({url: "<?php echo base_url(); ?>pediatrician/vaccination?>/reset_search/", 
      success: function(result)
      { 
        reload_table();
      } 
    }); 
  }

$(document).ready(function() {
   $('#load_add_modal_popup').on('shown.bs.modal', function(e) {
      $(this).find('.inputFocus').focus();
   })
}); 
var $modal = $('#load_vaccination_import_modal_popup');
        $('#open_model').on('click', function(){
        //  alert();
      $modal.load('<?php echo base_url().'pediatrician/vaccination/import_vaccination_excel' ?>',
      { 
      },
      function(){
      $modal.modal('show');
      });

      });

</script>
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
<!-- container-fluid -->
</body>
</html>