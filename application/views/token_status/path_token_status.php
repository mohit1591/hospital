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
if(in_array('2071',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('token_status/path_ajax_list')?>",
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

</script>




</head>

<body>


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>


<?php if(isset($user_role) && $user_role!=4 && $user_role!=3)
 { ?>
    <form name="search_form"  id="search_form" style="margin-top: 10px;"> 
    <div class="row" style="margin-bottom: 15px;">
     <?php if($branch_type==1){ ?>
      <div class="col-sm-4" id="show_special">
       <div class="col-xs-3"><label>Department</label></div>
          <div class="col-xs-8">
           <select name="department_id" id="department_id" class="m_input_default" onchange="return form_submit();">
             <option value="">Select Department</option>
                  <?php
                   if(!empty($dept_list))
                   {
                      foreach($dept_list as $dept)
                      {
                          $dept_select = "";
                          if($dept->id==$form_data['dept_id'])
                          {
                              $dept_select = "selected='selected'";
                          }
                          echo '<option value="'.$dept->id.'" '.$dept_select.'>'.$dept->department.'</option>';
                      }
                   }
                  ?>
            </select>
          </div>
    </div>
    <?php } ?>

      <div class="col-sm-3">
       <div class="col-xs-2"><label>Status</label></div>
          <div class="col-xs-8">
           <select name="search_type" id="search_type" class="m_input_default" onchange="return form_submit();">
             <option value=""> Select Status </option>
             <option value="">Default</option>
             <option value="0">Waiting</option>
             <option value="1">In Progress</option>
             <option value="2">Done</option>
             <option value="3">Emergency</option>
             <option value="4">Cancel</option>
            </select>
          </div>
    </div>
      
<?php } else { ?>
<input type="hidden" name="branch_id" id="branch_id" value="<?php echo $users_data['parent_id']; ?>">
<?php } ?>
    </div> <!-- row -->
    </form>


    <form>
       <!-- bootstrap data table -->
       <div class="hr-scroll">
        <table id="table" class="table table-striped table-bordered opd_booking_list " cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                     <th width="20%">Token No.</th>
                     <th>Lab No.</th> 
                     <?php if($branch_type==1){ ?>
                     <th>Department Name</th>
                    <?php } ?>
                     <th>Patient Name </th>                  
                     <th>Action </th>
                </tr>
            </thead>  
        </table>
        </div>
    </form>


<?php
$this->load->view('include/footer');
?>

<script>



function reset_search() 
{ 
  $('#department_id').val('');
  $.ajax({url: "<?php echo base_url(); ?>token_status/path_reset_search/", 
    success: function(result)
    { 
      reload_table();
    } 
  }); 
}



function form_submit(vals)
{ 
  var department_id = $('#department_id').val();
  var search_type=$('#search_type').val();
  $.ajax({
         url: "<?php echo base_url('token_status/path_advance_search/'); ?>", 
         type: 'POST',
         data: { department_id : department_id, search_type: search_type} ,
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


 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>

 
function update_status(id,val)
{
  var conf=confirm("Do you want to update?");
    if (conf == true) {
       $.ajax({
             url: "<?php echo base_url('token_status/path_update_token_status/'); ?>",
             type: 'POST',
             data: { token_id: id, token_status : val} ,
             success: function(result)
             {
                flash_session_msg(result);
                reload_table(); 
             }
          }); 
    } 
}

 $(document).ready(function() {
    setInterval(function(){ 
      reload_table(); 
    }, 60000); 
  });

</script> 


</body>
</html>