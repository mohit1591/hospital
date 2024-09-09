<?php
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

<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('2410',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('medicine_sets/ajax_list')?>",
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

function allbranch_delete(allVals)
 {    
  console.log(allVals);
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
                      url: "<?php echo base_url('medicine_sets/deleteall');?>",
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

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo '<script> flash_session_msg("'.$flash_success.'");</script>';
 }
 ?>

<section class="userlist">
  <div class="userlist-box">
    
    <form>
       <?php
          if(in_array('2410',$users_data['permission']['action'])) 
          {
          ?>
       <div class="hr-scroll">
        <table id="table" class="table table-striped table-bordered opd_booking_list " cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="40" align="center"> <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> 
                     <th>S. No.</th>
                     <th> Medicine Set </th> 
                     <th> Create Date </th> 
                     <th> Action </th>
                </tr>
            </thead>  
        </table>
        </div>
        <?php } ?>


    </form>


   </div> <!-- close -->





  	<div class="userlist-right relative">
        <div class="fixed">
  		<div class="btns opd_booking_list_right_btns">
               <?php if(in_array('2410',$users_data['permission']['action'])) {
               ?>
  			     <button class="btn-update" title="Add" onclick="window.location.href='<?php echo base_url('medicine_sets/add_medicine_set'); ?>'">
  				    <i class="fa fa-plus"></i> New
  			     </button>
               <?php } ?>
  			
               <?php if(in_array('2410',$users_data['permission']['action'])) {
               ?>
                    <button class="btn-update" title="Delete" id="deleteAll" onclick="return checkboxValues();">
                         <i class="fa fa-trash"></i> Delete
                    </button>
               <?php } ?> 
             
                    <button class="btn-update" title="Reload" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               
               <?php // if(in_array('2122',$users_data['permission']['action'])) {
               ?>
  			     <!-- <button class="btn-update" title="Archive" onclick="window.location.href='<?php // echo base_url('medicine_sets/archive'); ?>'">
                                      <i class="fa fa-archive"></i> Archive
                                 </button> -->
               <?php // } ?>
        
        <button class="btn-update" title="Exit" onclick="window.location.href='<?php echo base_url(); ?>'">
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

function reset_search()
{ 
  $('#start_date_patient').val('');
  $('#end_date_patient').val('');
  $('#booking_code').val('');
  $('#patient_name').val('');
  $('#mobile_no').val('');
  $.ajax({url: "<?php echo base_url(); ?>medicine_sets/reset_search/", 
    success: function(result)
    { 
      reload_table();
    } 
  }); 
}


 function delete_medicine_set_booking(booking_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('medicine_sets/delete_set/'); ?>"+booking_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }

</script> 


   

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
        url: "<?php echo base_url(); ?>medicine_sets/checkbox_list_save",
        type: "POST",
        data: {rec_id:sList, module_id:module_id},
        success: function(result) 
        { 
            flash_session_msg(result); 
            setTimeout(function () {
            window.location = "<?php echo base_url(); ?>medicine_sets";
        }, 1300); 
        }
      });
    }); 




</script>


<!-- function to reload columns -->
<script type="text/javascript">

$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })
                              
 function reset_coloumn_record()
    {
        $('#confirm').modal({
            backdrop: 'static',
            keyboard: false, 
            })
        .one('click', '#delete', function(e)
        {
          $.ajax({
                url: '<?php echo base_url(); ?>medicine_sets/reset_coloumn_record',
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
                            window.location = "<?php echo base_url(); ?>opd";
                            }, 1300); 
                        }

                    }

                });
        }); 
    }


</script>
<!-- function to reload columns -->

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
    
</body>
</html>