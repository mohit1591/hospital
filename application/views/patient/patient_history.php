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

 <!--new css-->
    <link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>

    <!--new css-->

<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('415',$users_data['permission']['action']) || in_array('523',$users_data['permission']['action']) || in_array('872',$users_data['permission']['action']) || in_array('734',$users_data['permission']['action']) || in_array('400',$users_data['permission']['action']) || in_array('915',$users_data['permission']['action'])) {
?>
$(document).ready(function() { 
  form_submit();
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,
        "searching": false,
        "bLengthChange": false , 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('patient/patient_history_ajax')?>",
            "type": "POST",
            "data": function(d){
                d.patient_id = getUrlData('id');
                d.type = getUrlData('type');
                d.branch_id = getUrlData('branch_id');
                return d;

            },
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
function getUrlData(name) { 
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
    var regexS = "[\\?&]"+name+"=([^&#]*)";
    var regex = new RegExp( regexS );
    var results = regex.exec( window.location.href );
    if( results == null )
          return "";
   else
      return results[1];
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
                      url: "<?php echo base_url('medicine_stock/deleteall');?>",
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

 function reset_search()
  { 
    $('#start_date').val('');
    $('#end_date').val('');
    
    $('#batch_no').val('');
    $.ajax({url: "<?php echo base_url(); ?>medicine_stock?>/reset_search/", 
      success: function(result)
      { 
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
    <div class="userlist-box">
   
    <div class="row m-b-5">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-12">
<?php if(in_array('523',$users_data['permission']['action'])) 
                    { ?>
         <input type="radio" name="types" class="types" value="1" onclick="return type_set(1,'<?php echo $branch_id; ?>')" <?php if($type==1){ echo 'checked=""'; } ?> /> OPD 
<?php }
if(in_array('387',$users_data['permission']['section'])) 
                    { ?>
         <input type="radio" name="types" class="types" value="15" onclick="return type_set(15,'<?php echo $branch_id; ?>')" <?php if($type==15){ echo 'checked=""'; } ?> /> Day Care 
<?php }
if(in_array('915',$users_data['permission']['action'])) 
                    {
 ?>
         <input type="radio" name="types" <?php if($type==2){ echo 'checked=""'; } ?> class="types" onclick="return type_set(2,'<?php echo $branch_id; ?>')" /> OPD Billing
<?php } if(in_array('400',$users_data['permission']['action'])) 
                    { ?>
         <input type="radio" name="types" <?php if($type==3){ echo 'checked=""'; } ?> class="types"  onclick="return type_set(3,'<?php echo $branch_id; ?>')" /> Medicine

<?php } if(in_array('734',$users_data['permission']['action'])) 
                    { ?>
         <input type="radio" name="types" <?php if($type==4){ echo 'checked=""'; } ?> class="types" onclick="return type_set(4,'<?php echo $branch_id; ?>')" /> IPD

<?php } if(in_array('872',$users_data['permission']['action']))
                    { ?>
         <input type="radio" name="types" <?php if($type==5){ echo 'checked=""'; } ?> class="types" onclick="return type_set(5,'<?php echo $branch_id; ?>')" /> Pathology

<?php } ?>
 <!--    addon history op     -->

           <?php if(in_array('2086',$users_data['permission']['action'])) 
                              { ?>
                   <input type="radio" name="types" class="types" value="6" onclick="return type_set(6,'<?php echo $branch_id; ?>')" <?php if($type==6){ echo 'checked=""'; } ?> /> Ambulance 
          <?php }
          if(in_array('1193',$users_data['permission']['action'])) 
                              {
           ?>
                   <input type="radio" name="types" <?php if($type==7){ echo 'checked=""'; } ?> class="types"onclick="return type_set(7,'<?php echo $branch_id; ?>')" /> Dialysis
          <?php } if(in_array('969',$users_data['permission']['action'])) 
                              { ?>
                   <input type="radio" name="types" <?php if($type==8){ echo 'checked=""'; } ?> class="types"  onclick="return type_set(8,'<?php echo $branch_id; ?>')" /> Inventory

          <?php } if(in_array('1088',$users_data['permission']['action'])) 
                              { ?>
                   <input type="radio" name="types" <?php if($type==9){ echo 'checked=""'; } ?> class="types" onclick="return type_set(9,'<?php echo $branch_id; ?>')" /> Vaccination

          <?php } if(in_array('807',$users_data['permission']['action']))
                              { ?>
                   <input type="radio" name="types" <?php if($type==10){ echo 'checked=""'; } ?> class="types" onclick="return type_set(10,'<?php echo $branch_id; ?>')" /> O.T.

          <?php } if(in_array('1409',$users_data['permission']['action'])) 
                              { ?>
                   <input type="radio" name="types" class="types" value="1" onclick="return type_set(11,'<?php echo $branch_id; ?>')" <?php if($type==11){ echo 'checked=""'; } ?> /> EYE 
          <?php }
          if(in_array('1622',$users_data['permission']['action'])) 
                              {
           ?>
                   <input type="radio" name="types" <?php if($type==12){ echo 'checked=""'; } ?> class="types" value="1" onclick="return type_set(12,'<?php echo $branch_id; ?>')" /> Pediatrician
          <?php } if(in_array('277',$users_data['permission']['section'])) 
                              { ?>
                   <input type="radio" name="types" <?php if($type==13){ echo 'checked=""'; } ?> class="types"  onclick="return type_set(13,'<?php echo $branch_id; ?>')" /> Dental

          <?php } if(in_array('1889',$users_data['permission']['action'])) 
                              { ?>
                   <input type="radio" name="types" <?php if($type==14){ echo 'checked=""'; } ?> class="types" onclick="return type_set(14,'<?php echo $branch_id; ?>')" /> Gynecology

          <?php } ?>

        

        </div>
      
      </div>
    </div>
  </div>
       
    <form>
       <?php if(in_array('415',$users_data['permission']['action']) || in_array('523',$users_data['permission']['action']) || in_array('872',$users_data['permission']['action']) || in_array('734',$users_data['permission']['action']) || in_array('400',$users_data['permission']['action']) || in_array('915',$users_data['permission']['action'])|| in_array('387',$users_data['permission']['section'])) {
       $type = $this->input->get('type');
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered table-responsive medicine_stock_list" cellspacing="0" width="100%">
            <thead>
                <tr>
                 <?php if($type==1)
                 { 
                 ?>
                      <th> OPD No.</th>
                      <th> Date</th>
                      <th> Doctor </th>
                      <th> Amount</th>
                      <th>Action</th>
                  <?php }elseif($type==2){ ?>    
                  
                      <th> Bill No.</th>
                      <th> Date</th>
                      <th> Amount</th>
                      <th> Action</th>   

                <?php }elseif($type==3){ ?>    
                  
                      <th> Sale No.</th>
                      <th> Date</th>
                      <th> Amount</th>
                      <th> Action</th>
                     
                   
                  <?php }elseif($type==4){ ?>    
                  
                      <th> IPD No.</th>
                      <th> Date </th>
                      <th> Doctor </th>
                      <th> Amount</th>
                      <th> Action</th>
                     
                    <?php }else if($type==5){
                      ?>
                          <th width="150px;">Lab Ref. No.</th>
                          <th>Booking Date</th>
                          <th>Attended Doctor</th>
                          <th>Total Amount</th>
                          <th>Action</th>
                      <?php
                    } else if($type==6){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>
                      
                      <th>Driver Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php }
                 else if($type==7){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Remark</th>
                      <th>Action</th>
                    
                 <?php }
                  else if($type==8){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Remark</th>
                      <th>Action</th>
                    
                 <?php }
                  else if($type==9){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php }
                  else if($type==10){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php }
                  else if($type==11){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php }
                  else if($type==12){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php }
                  else if($type==13){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php } 
                 else if($type==14){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php }else if($type==15){ 
                      ?>    
                      <th>Booking No.</th>
                      <th>Date</th>                      
                      <th>Doctor Name</th>
                      <th>Amount</th>
                      <th>Action</th>
                    
                 <?php } ?>
                  </tr>
            </thead>  
        </table>
        <?php } ?>
      
    </form>


   </div> <!-- close -->
   <div class="userlist-right">
      <div class="btns">
              
               <?php if(in_array('415',$users_data['permission']['action']) || in_array('523',$users_data['permission']['action']) || in_array('872',$users_data['permission']['action']) || in_array('734',$users_data['permission']['action']) || in_array('400',$users_data['permission']['action']) || in_array('915',$users_data['permission']['action']) || in_array('387',$users_data['permission']['section'])) {
               ?>
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               <?php } ?>
             
        <button class="btn-exit" onclick="window.location.href='<?php echo base_url('patient');?>'">
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
//medicine_allot_history?id=1571&batch_no=2334455
function type_set(vals,branch_id)
{ 
   window.location.href='<?php echo base_url('patient/patient_history').'/?id='.$id; ?>&branch_id='+branch_id+'&type='+vals;
} 

function load_allot_model()
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
     var $modal = $('#load_allot_to_branch_modal_popup');
     $modal.load('<?php echo base_url('medicine_stock/allotement_to_branch/'); ?>',{'medicine_ids':allVals},function(){
          $modal.modal('show');
     });

   
}
 function delete_medicine_stock(id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('medicine_stock/delete/'); ?>"+id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
 
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

<!-- Confirmation Box end -->
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_allot_to_branch_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

</div>
<script>
/*var $modal = $('#load_add_modal_popup');
  $('#adv_search_stock').on('click', function(){
  //  alert();
$modal.load('< ?php echo base_url().'medicine_stock/advance_search/' ?>',
{ 
},
function(){
$modal.modal('show');
});

});*/


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
};
*/

function form_submit()
{
  $('#new_search_form').delay(200).submit();
}

$("#new_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
   
  $.ajax({
    url: "<?php echo base_url(); ?>medicine_stock/advance_search/",
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

/*$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,  
  }).on("change", function(selectedDate) 
  {
      form_submit();
  });*/

  var today =new Date();
    $('.datepicker_from').datepicker({
    dateFormat: 'dd-mm-yy',
    maxDate : "+0d",
    onSelect: function (selected) {
       //alert(selected);
            var dt = new Date(selected);
          
            dt.setDate(dt.getDate() + 1);
           
            $(".datepicker_to").datepicker("option", "minDate", selected);
              form_submit();
      }
    })

    $(".datepicker_to").datepicker({
    dateFormat: 'dd-mm-yy',
    onSelect: function (selected) {
      form_submit();
          var dt = new Date(selected);
          dt.setDate(dt.getDate() - 1);
          //$('.datepicker').datepicker("option", "maxDate", selected);
      }
    })
</script>
<!-- container-fluid -->
</body>
</html>