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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-multiselect.css" type="text/css">
<script type="text/javascript">
var save_method; 
var table;
<?php
if(in_array('1605',$users_data['permission']['action'])) 
{
?>
$(document).ready(function() { 
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true, 
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": "<?php echo base_url('pediatrician/Pediatrician_age_chart/ajax_list')?>",
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


$(document).ready(function(){
var $modal = $('#load_add_age_vaccination_modal_popup');
$('#modal_add').on('click', function(){
$modal.load('<?php echo base_url().'pediatrician/age_vaccination/add/' ?>',
{
  //'id1': '1',
  //'id2': '2'
  },
function(){
$modal.modal('show');
});

});

});

function edit_age_vaccination(id)
{
  var $modal = $('#load_add_age_vaccination_modal_popup');
  $modal.load('<?php echo base_url().'pediatrician/age_vaccination/edit/' ?>'+id,
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
                      url: "<?php echo base_url('pediatrician/pediatrician_age_chart/deleteall');?>",
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
<style>

*{transition:all 0.3s ease;padding:0px;margin:0px;box-sizing:box;}
body,html {font-size:14px;font-family:arial;}
body{padding-right:0px !important;}
a{font-size:12px;}
.form-control{border-radius:0;}
.container-fluid{overflow-x:hidden;padding:0px;margin:0px;}


.header{padding:1em;}

/*naviagtion*/
.navigation{background:#df8000;}
ul.menu{list-style: none;padding:0 2em;margin:0px;}
ul.menu li{padding:5px 12px;float: left;margin-right: 10px;}
ul.menu li a{color:#fff;text-decoration: none;}
.table-box{margin:2% 0;padding:1em 3em;min-height:450px;}
.table-box thead th{background:#fad099;color:#333;}




.quote-box{padding:10px;margin:2% 0;color:#df8000;font-size:15px;*height:128px;}
.copy-box a{color:#fff;text-decoration: none;}
.copy-box {background: #df8000;padding:5px;}

.theme-color{color:#df8000;}

.font th{text-align:center;}
.table-box tbody td:nth-child(1){background:#fad099;color:#333;text-align: center;}


.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th{border:1px solid #777;}

ul.chart-menu{list-style: disc;}
.blue_td{color:#fff;background:#3595db;}
.yellow_td{color:#333;background: #fdf585;}
.green_td{color:#333;background: #d1e48a;}
  
.silders_c
{
  float: left;
  width: 99%;
  overflow: auto;
}

</style>
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
    <div class="silders_c"> 
    	
    <form>
       <?php if(in_array('1605',$users_data['permission']['action'])) {
       ?>
       

        <table class="table table-striped table-bordered" cellspacing="0" width="100%" >
				<thead class="bg-theme font">
					<tr role="row">
						<th align="center" style="color:#fff;background: #e72b26;">Age /Vaccine</th>
                     <?php
                     if(!empty($age_list)) 
                     {
                     	foreach ($age_list as $age_list_data)
                     	 {

                      ?>
						<th align="center" id="<?php echo $age_list_data->id ?>"><?php echo $age_list_data->title ?></th>

						<?php
							}
                        }
						?>
						
							
						
					</tr>
				</thead>
					
				<tbody>
				<?php 
				
				foreach($vaccination_list as $vaccination_li)
				{
					 $recommend_vaccination_age= get_recommended_age_according_to_vaccine($vaccination_li->id);
					$data_recomend_age=[];
					 foreach($recommend_vaccination_age as $recomend_age)
					 {
					 	$data_recomend_age[]=$recomend_age->age_id;
					 }

					$immuniation_vaccination_age= get_catchup_immuniation_age_according_to_vaccine($vaccination_li->id);

					$data_immuniation_age=[];
					 foreach($immuniation_vaccination_age as $immuniation_age)
					 {
					 	$data_immuniation_age[]=$immuniation_age->age_id;
					 }

					 $risk_vaccination_age= get_catchup_risk_age_according_to_vaccine($vaccination_li->id);

					$data_risk_age=[];
					 foreach($risk_vaccination_age as $risk_age)
					 {
					 	$data_risk_age[]=$risk_age->age_id;
					 }
					 
					 
				 ?>
					<tr>
						<td valign="top"><?php echo $vaccination_li->vaccination_name;?></td>
						 <?php
                     if(!empty($age_list)) 
                     {
					foreach ($age_list as $age_list_data)
					{
						    $yellow_td='';
						    $re_vaccination_name='';
							if(in_array($age_list_data->id,$data_recomend_age))
							{
								$yellow_td="yellow_td";
								$re_vaccination_name=$vaccination_li->vaccination_name;
							}
							else
							{
								$yellow_td='';
								$re_vaccination_name='';
							}

							$green_td='';
						    $ca_vaccination_name='';
							if(in_array($age_list_data->id,$data_immuniation_age))
							{
								$green_td="green_td";
								$ca_vaccination_name=$vaccination_li->vaccination_name;
							}
							else
							{
								$green_td='';
								$ca_vaccination_name='';
							}

							$blue_td='';
						    $ri_vaccination_name='';
							if(in_array($age_list_data->id,$data_risk_age))
							{
								$blue_td="blue_td";
								$re_vaccination_name='';
								$ri_vaccination_name=$vaccination_li->vaccination_name;
							}
							else
							{
								$blue_td='';
								$ri_vaccination_name='';
							}

							
					?>

					<td valign="top"  class="<?php echo $yellow_td;?> <?php echo $green_td;?> <?php echo $blue_td;?>" ><?php echo $re_vaccination_name;?> <?php echo $ri_vaccination_name;?></td>
					<?php 
					} 

					}?>
					</tr>
				<?php } ?>

				</tbody>
			</table >
        <?php } ?>
    </form>

     <div style="border: 1px solid #ccc; float: right; padding:5px; font-weight: bold;">
        <table align="right" cellpadding="0" cellspacing="0" width="400px">
           <tbody><tr>
               <td><div class="m_alert_red_mark" style="background: #FDF585 none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Range of recommended ages for all children</td>
           </tr>
           <tr>
               <td><div class="m_alert_orange_mark" style="background: #D1E48A none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Range of recommended ages for catchup-immuniation</td>
           </tr>
            <tr>
               <td><div class="m_alert_orange_mark" style="background: #3595DB none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Range of recommended ages for certain high-risk group</td>
           </tr>
           <tr>
               <td><div class="m_alert_orange_mark" style="background: #fff none repeat scroll 0% 0%;border:1px solid #888"></div></td>
               <td>Normal</td>
           </tr>
            
        </tbody></table>
       </div>
       </div>
   </div> <!-- close -->



  	<div class="userlist-right">
  		<div class="btns">
          
  		
        <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'">
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
$('#selectAll').on('click', function () { 
  if ($(this).hasClass('allChecked')) {
      $('.checklist').prop('checked', false);
  } else {
      $('.checklist').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
});
 function delete_age_vaccine(rate_id)
 {    
    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .one('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pediatrician/age_vaccination/delete/'); ?>"+rate_id, 
                 success: function(result)
                 {
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
 }
$(document).ready(function() {
   $('#load_add_age_vaccination_modal_popup').on('shown.bs.modal', function(e) {
      $('.inputFocus').focus();
   })
}); 
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
<div id="load_add_age_vaccination_modal_popup" class="modal fade modal-50" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div> <!-- container-fluid -->
</body>
</html>