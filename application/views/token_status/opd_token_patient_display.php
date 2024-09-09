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
if(in_array('2073',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
      "bPaginate": false,
      "bLengthChange": false,
      "bFilter": false,
      "processing": false, 
      "serverSide": true, 
      "bInfo" : false,
      "order": [], 
      "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('token_status/ajax_list_display')?>",
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


<div class="container-fluid" style="padding: 0px!important;padding-top: -10px!important;">

    <form>
       <!-- bootstrap data table -->
       <div >
        <table id="table" class="table table-striped table-bordered opd_booking_list " cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                     <th width="20%">Token No.</th>
                     <th>Patient Name</th>  
                      <?php if($branch_type==2){ ?>
                     <th>Specialization Name</th> 
                    <?php } else{ ?>
                      <th>Doctor Name</th> 
                    <?php } ?>  
                     <th>Status</th>
                </tr>
            </thead>  
        </table>
        </div>
    </form>




<script>
function form_submit(vals)
{ 
  var specialization_id = $('#specialization_id').val();
  var doctor_id = $('#doctor_id').val();
  var search_type=$('#search_type').val();
  $.ajax({
         url: "<?php echo base_url('token_status/advance_search/'); ?>", 
         type: 'POST',
         data: { doctor_id: doctor_id, specialization_id : specialization_id, search_type: search_type} ,
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


  
 $(document).ready(function() {
    setInterval(function(){ 
      reload_table(); 
    }, 60000); 
  });
</script> 


</body>
</html>