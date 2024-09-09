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
<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
    <div class="userlist-box">
    <!-- // -->
    <div class="row m-b-5">
         <div class="col-md-12">
              <div class="row">
                   <div class="col-md-6">
                    
                   </div> <!-- 6 -->
                   <div class="col-md-6 text-right">
                        
                   </div> <!-- 6 -->
              </div> <!-- innerRow -->
         </div> <!-- 12 -->
    </div> <!-- row -->
    
    <form>
       <?php if(in_array('946',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
        <table id="table" class="table table-striped table-bordered category_list" cellspacing="0" width="100%">
            <thead class="bg-theme">
                <tr>
                    <th width="10%"> Patient UH ID </th>
                    <th width="10%"> Patient Name </th>
                    <th> Feedback </th>
                    <th width="10%"> Rating </th>
                    <th width="15%"> Feedback Date </th> 
                </tr>
            </thead>  
        </table>
        <?php } ?>
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns"> 
               <button class="btn-update" onclick="reload_table()">
                    <i class="fa fa-refresh"></i> Reload
               </button> 
  		</div>
  	</div> 
  	<!-- right -->
 
  <!-- cbranch-rslt close -->
 
</section>
<?php
$this->load->view('include/footer');
?>

<script> 
var table;
<?php if(in_array('113',$users_data['permission']['action'])): ?>
     $(document).ready(function() { 
         table = $('#table').DataTable({  
             "processing": true, 
             "serverSide": true, 
             "order": [], 
             "pageLength": '20',
             "ajax": {
                 "url": "<?php echo base_url('patient/feedback_ajax_list')?>",
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

</body>
</html>