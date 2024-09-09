<?php
$users_data = $this->session->userdata('auth_users');
$package_id = $this->uri->segment(3); 
$type = $this->uri->segment(4); 
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
<script type="text/javascript">
var save_method; 
var table; 
$(document).ready(function() 
{  
    table = $('#table1').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('medicine_kit_history/ajax_list/').$package_id.'/'.$type; ?>",
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function type_set(vals)
{ 
   window.location.href='<?php echo base_url('medicine_kit_history/index/').$package_id.'/'; ?>'+vals;
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
        <div class="col-xs-6">
         <input type="radio" name="types" class="types" value="0" onclick="return type_set(0)" <?php if($type==0){ echo 'checked=""'; } ?> /> OPD 
         <input type="radio" name="types" <?php if($type==1){ echo 'checked=""'; } ?> class="types" value="1" onclick="return type_set(1)" /> Billing 
         <input type="radio" name="types" <?php if($type==2){ echo 'checked=""'; } ?> class="types" value="2" onclick="return type_set(2)" /> Branch Allotment 
          <!-- <input type="radio" name="types" <?php if($type==3){ echo 'checked=""'; } ?> class="types" value="3" onclick="return type_set(3)" /> Manage Kit Quantity  -->
        </div>
        <div class="col-xs-6"></div>
      </div>
    </div>
  </div>

    <form> 
       <!-- bootstrap data table -->
        <table id="table1" class="table table-striped table-bordered simulation_list" cellspacing="0" width="100%">
            <thead class="tbl_head bg-theme"> 
            <?php if($type==1){  ?>
                <tr> 
                    <th> Billing No.</th> 
                    <th> Patient Reg. No. </th> 
                    <th> Patient Name </th> 
                    <th> Package Name </th> 
                    <th> Quantity </th> 
                    <th> Date </th> 
                </tr>
            <?php } ?>
            <?php if($type==2){ ?>
                <tr> 
                    <th> S.No.</th> 
                    <th> From Branch </th> 
                    <th> To Branch </th> 
                    <th> Package Name </th> 
                    <th> Quantity </th> 
                    <th> Date </th> 
                </tr>
            <?php }?>
             <?php if(empty($type) || $type==0){ ?>
            <tr> 
                    <th> OPD No.</th> 
                    <th> Patient Reg. No. </th> 
                    <th> Patient Name </th> 
                    <th> Package Name </th> 
                    <th> Quantity </th> 
                    <th> Date </th> 
                </tr>
            <?php } ?>      
              <?php if($type==3){ ?>
                    <tr> 
                         <th> S.No.</th> 
                        
                         <th> Medicine Kit</th> 
                         <th> Amount </th> 
                         <th> Quantity </th> 
                         <th> Created Date </th> 
                         <th> Action </th> 
                        <!--  <th> Date </th>  -->
                    </tr>
            <?php } ?>  
            </thead>  
        </table> 
    </form>
   </div> <!-- close -->
  	<div class="userlist-right">
  		<div class="btns">


                <a href="<?php echo base_url('medicine_kit_history/medicine_kit_history_excel/').$package_id.'/'.$dept_type; ?>" class="btn-anchor m-b-2">
                <i class="fa fa-file-excel-o"></i> Excel
                </a>
               
                    <a href="<?php echo base_url('medicine_kit_history/medicine_kit_history_csv/').$package_id.'/'.$dept_type;; ?>" class="btn-anchor m-b-2">
                         <i class="fa fa-file-word-o"></i> CSV
                    </a>
               
                    <a href="<?php echo base_url('medicine_kit_history/pdf_medicine_kit_history/').$package_id.'/'.$dept_type;; ?>" class="btn-anchor m-b-2">
                    <i class="fa fa-file-pdf-o"></i> PDF
                    </a>
                     
                    <a href="javascript:void(0)" class="btn-anchor m-b-2" id="" onClick="return print_window_page('<?php echo base_url("medicine_kit_history/print_medicine_kit_history/").$package_id.'/'.$dept_type;; ?>');">
                    <i class="fa fa-print"></i> Print
                    </a>
               
                    <button class="btn-update" onclick="reload_table()">
                         <i class="fa fa-refresh"></i> Reload
                    </button>
               
              <button class="btn-exit" onclick="window.location.href='<?php echo base_url('packages/medicine_stock_list/'); ?>'">
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
function package_quantity_edit(id,row_id){
     var opt="edit";
    
     var $modal1 = $('#load_add_quantity_add_modal_popup');
     $modal1.load('<?php echo base_url().'medicine_kit_history/add_medicine_kit_quantity/' ?>'+id+'/'+opt+'/'+row_id,
     {
          //'id1': '1',
          //'id2': '2'
    },
    function(){
          $modal1.modal('show');
    });
}
</script>
  <div id="load_add_quantity_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>