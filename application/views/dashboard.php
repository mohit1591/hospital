<?php //print_r($today_booking_patient); 
$users_data = $this->session->userdata('auth_users'); 
//echo "<pre>";print_r($users_data);die;
$user_role= $users_data['users_role'];
$doctor_data = get_doctor($users_data['parent_id']);
if (array_key_exists("permission",$users_data))
{
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];
}
else
{
     $permission_section = array();
     $permission_action = array();
}
$company_data = $this->session->userdata('company_data');

function thousandsCurrencyFormat($num) {
  if($num>1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
  }
  return $num;
}
//echo "<pre>";print_r($counts); //die;
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title><?php echo $page_title; ?></title>

<meta name="viewport" content="width=1024">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css"> 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css"> 
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>new_menu.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>angular.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>


<style type="text/css">
	table.table thead,
	table.table thead th,
	table.table tbody,
	table.table tbody tr,
	table.table tbody tr td{border:none!important; padding: 4px;}
	.modal-header { padding: 0.5em; }

	.blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

.ul_listing {padding:0 0 0 18px;margin:0px;}
.ul_listing li {position:relative;list-style:none;margin-bottom:5px;}
.ul_listing li.red:before {content:'';position:absolute;left:-20px;top:3px;width:16px;height:16px;background:url(https://www.reachyourdoctors.com/assets/images/arrow-right-red.png) no-repeat center center;} 
</style>
<style type="text/css">
	.header_top1 {padding:0 15px;margin:0;}
	.header_top {padding:0;margin:0;}
	.dashSideBar {position:absolute;right:-100%;top:0;width:280px;height:100%;background:#dfffdf;padding:15px;overflow:auto;transition:0.5s;}
	.dashSideBar.active {right:0;}

	.jumbotron._dashcard {color: #fff; font-weight: 600; padding: 30px; border-radius: 3px; display: flex; justify-content: space-between; align-items: center; }
	.jumbotron._dashcard img {width:70px;opacity:0.4;transition: all 0.4s ease;}
	.jumbotron._dashcard:hover img {opacity:0.7;}
	.jumbotron._dashcard h4 {margin:0;font-size:4vw;font-weight:700}
	.jumbotron._dashcard._blue {background: linear-gradient(45deg, #2661f9, #07b3ff);box-shadow: 0 6px 10px rgba(11, 169, 254, 0.2); }
	.jumbotron._dashcard._green {background: linear-gradient(45deg, #0f8650, #12dd81);box-shadow: 0 6px 10px rgba(17, 202, 118, 0.25); }
	.jumbotron._dashcard._brown {background: linear-gradient(45deg, #c0892c, #eabc71);box-shadow: 0 6px 10px rgba(192, 137, 44, 0.26); }

	._dashpanel {}
	._dashpanel .panel-heading,
	._dashpanel .panel-body {padding:0;}
	._dashpanel .panel-heading {background:transparent;}
	._dashpanel .panel-heading h4 {margin:0;padding:15px;font-size:15px;font-weight:700;}
	._dashpanel .list-group {margin-top:1em;}
	._dashpanel .list-group-item {border:0;padding:4px 10px;}
	._dashpanel .list-group-item a {font-size:13px; font-weight:600;color:#333;}
	._dashpanel .list-group-item:hover a {color:green;}
	._dashpanel .list-group-item span {float:right;}
  ._dashTable td {border-top:1px solid #fbfbfb !important;}
  [data-listing]>li>a {position: relative;padding-left:20px;}
  [data-listing]>li>a::before {content:'';position:absolute;left:0;top:4px;width:8px;height:8px;border-top:2px solid #333;border-right:2px solid #333;transform:rotate(45deg);}
  [data-listing]>li:hover>a::before {border-color:green;}

  table.table thead,
  table.table thead th,
  table.table tbody,
  table.table tbody tr,
  table.table tbody tr td{border:none!important; padding: 4px;}
  .modal-header { padding: 0.5em; }

  .blink_me {
  animation: blinker 1s linear infinite;
}

@keyframes blinker {
  50% {
    opacity: 0;
  }
}

.ul_listing {padding:0 0 0 18px;margin:0px;}
.ul_listing li {position:relative;list-style:none;margin-bottom:5px;}
.ul_listing li.red:before {content:'';position:absolute;left:-20px;top:3px;width:16px;height:16px;background:url(https://www.reachyourdoctors.com/assets/images/arrow-right-red.png) no-repeat center center;} 



.mypets{ /*header of 1st demo*/
color: #333 !important;
    display: block;
    font-family: Roboto,arial !important;
    font-size: 16px;
    font-weight: 300;
    line-height: 20px;
    padding: 0px 0px 10px;
    text-decoration: none; cursor:pointer;

background: #e9e8e8;  border-bottom: 0px solid #464646;
    border-top: 0px solid #757575;	 margin-bottom:5px;
}



.openpet{ /*class added to contents of 1st demo when they are open*/
background:#e9e8e8;
}
#list-of-committeee .mypets{background: url(https://www.hospitalms.in/assets/images/acordination-up.png) right center no-repeat #e9e8e800;}
#list-of-committeee .openpet{background: url(https://www.hospitalms.in/images/acordination-down.png) right center no-repeat #e9e8e800;}
</style>


<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>ddaccordion.js"></script>
</head>
<body id="list-of-committeee">

<div class="container-fluid">
<?php
//echo phpinfo();
$this->load->view('include/header'); 
$this->load->view('include/dashboard-left-menu');
?>

	<!-- Main -->
	<div class="row">
	<div class="col-md-12">

	<?php 
	$marquee = '';
	
	if(!empty($start_end_dates))
	{
		/*$marquee.= 
		'<div style="float:left;width:100%;background:#f9edbe;border:1px solid #f0c36d;display:flex;color:#000;font-weight:bold;font-size:13px;letter-spacing:0.5px;border-radius:20px;padding:1px 10px 1px 2px;margin-top:5px;overflow:hidden;">
		<div style="width:190px;border:1px solid #f0c36d;padding:2px 5px;background:#f0c36d;color:#111;border-radius:20px 0 0 20px" class="blink_me">New Feature Added</div>
		<marquee scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();" behavior="alternate" style="color:#333;line-height:2">';*/
	
		$marquee.= 
		'<div style="float:left;width:100%;background:#cc3030a8;border:1px solid #f0c36d;display:flex;color:#000;font-weight:bold;font-size:13px;letter-spacing:0.5px;border-radius:20px;padding:1px 10px 1px 2px;margin-top:5px;overflow:hidden;">
		<div style="width:190px;border:1px solid #f0c36d;padding:2px 5px;background:#f0c36d;color:#111;border-radius:20px 0 0 20px" class="blink_me">Notice</div>
		<marquee scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();" behavior="alternate" style="color:#333;line-height:2">';
		
		$sections='';
		foreach ($start_end_dates as $vals) 
		{
			
			if(!empty($vals->section))
			{
				if($vals->section==1)
				{
					$sections='OPD';
				}
				if($vals->section==2)
				{
					$sections='IPD';
				}
				if($vals->section==3)
				{
					$sections='Pathology';
				}
				if($vals->section==4)
				{
					$sections='Medicine';
				}
				if($vals->section==5)
				{
					$sections='Vaccination';
				}
				if($vals->section==6)
				{
					$sections='Dialysis';
				}
				if($vals->section==7)
				{
					$sections='OT';
				}
				if($vals->section==8)
				{
					$sections='Inventory';
				}
				if($vals->section==9)
				{
					$sections='Common';
				}
				$marquee.= ucfirst($vals->features);

			}
		
		?>
      <?php  ?>

	<?php
		}
		echo rtrim($marquee,',');
		echo '	</marquee>
		</div>';
    }
	?>
	</div>
		<div class="col-md-12 mainBox hmasDashboard">
			<div class="row" style="margin-top: 10px;margin-left:3px;">
				<main class="main_page">
   <div class="main_wrapper">
      <div class="row" style="width:100%">
      	<div class="col-lg-9">
      	    <?php if($users_data['parent_id']!=65){ ?>
      		<div class="row">
      			<div class="col-md-4">
      				<a href="<?php echo base_url('patient/add'); ?>">
      				 <div class="jumbotron _dashcard _blue">
      					<div>
      						<div>New Patient</div>
      						<h2 id="total_patient"><?php echo $counts['patient_count']->total_patient; ?></h2>
      					</div>
      					<img src="<?php echo base_url('assets/images/001-medical.png');?>" alt="">
      				</div>
      			</a>
      			</div>
      			<div class="col-md-4">
      			
	      				<div class="jumbotron _dashcard _green">
	      					<div>
	      						<div>Total Earning</div>
	      						<h2 id="total_earn"><?php if(!empty($counts['collection']['debit'])){ echo number_format($counts['collection']['debit'],2,'.','');} else{ echo '0.00';} ?></h2>
	      					</div>
	      					<img src="<?php echo base_url('assets/images/002-profit.png');?>" alt="">
	      				</div>
	      			
      			</div>
      			<div class="col-md-4">
      			  <a href="<?php echo base_url('balance_clearance'); ?>">
      				<div class="jumbotron _dashcard _brown">
      					<div>
      						<div>Due Amount</div>
      						<h2 id="total_bal"><?php if(!empty($counts['collection']['balance']) && $counts['collection']['balance'] >1){ echo number_format($counts['collection']['balance'],2,'.','');} else{ echo '0.00';} ?></h2>
      					</div>
      					<img src="<?php echo base_url('assets/images/002-profit.png');?>" alt="">
      				</div>
      			</a>
      			</div>
      		</div>
      		<?php } ?>
		


      		<div class="panel panel-success _dashpanel">
      			<div class="panel-heading">
      				<h4>Chart Area </h4>
      			</div>
      			<div class="panel-body">
      				 <canvas height="12vh" width="40vw" id="chartContainer"></canvas>
      			</div>
      		</div>

      		<div class="panel panel-success _dashpanel">
      			<div class="panel-body">
      				<table class="table _dashTable">
      					<thead style="background:none;color:inherit;">
      						<tr>
      							<th>UHID No.</th>
      							<th>Patient Name</th>
      							<th>Department</th>
      							<th>Payment Received</th>
      						</tr>
      					</thead>
      					<tbody>
      						<?php  if(!empty($patient_data['self_opd_coll'])){ 
      						 foreach ($patient_data['self_opd_coll'] as $key => $opd) {?>
	      						<tr>
	      							<td><?php echo $opd->patient_code;?></td>
	      							<td><?php echo $opd->patient_name;?></td>
	      							<td><?php echo $opd->module;?></td>
	      							<td>Rs. <?php echo $opd->debit;?></td>
	      						</tr>
      					   <?php }}else{?>
      					   	<tr>
      							<td colspan="4" class="text-danger text-center">No record found...</td>
      						</tr>
      					   <?php } ?>
      					</tbody>
      				</table>
      			</div>
      		</div>
      	</div>
      	<div class="col-lg-3">
      	    
      	    
      	    <?php if(in_array('377',$users_data['permission']['section'])){ 
      	    
      	    if($users_data['users_role']>4)
      	    {
      	        $payrol_url=base_url('payroll/dashboard_reporting_manager');
      	    }
      	    else
      	    {
      	        $payrol_url=base_url('payroll/employee');
      	    }
      	    ?>
      	    <div class="form-group">
      	    <div class="">
      			  <a href="<?php echo $payroll_url; ?>">
      				<div class="jumbotron _dashcard _blue">
      					<div>
      						<div>Payroll</div>
      						<h2>&nbsp;</h2>
      					</div>
      					<img src="<?php echo base_url('assets/images/001-medical.png');?>" alt="">
      				</div>
      			</a>
      			</div>
      			</div>	
      			<?php } ?>
      		<div class="form-group">
	      		<div class="input-group" style="display:grid;grid-template-columns:1fr 1fr;grid-gap:1em;padding-right:2em">
	      			 <input style="width:100%;" id="start_date" name="start_date" class="datepicker start_datepicker m_input_default" type="text" value="<?php echo date('d-m-Y');?>" placeholder="From Date">
	      			 <input style="width:100%;" name="end_date" id="end_date" class="datepicker datepicker_to end_datepicker m_input_default" value="<?php echo date('d-m-Y');?>"  type="text" placeholder="To Date">
	      			
	      		</div>
      		</div>

      		<div class="panel panel-success _dashpanel">
      			<div class="panel-heading">
      				<h4>Patient Report </h4>
      			</div>
      			<div class="panel-body">
      				<ul class="list-group">
              <?php if(in_array('85',$users_data['permission']['section']) && in_array('529',$users_data['permission']['action'])){ ?>
                   
                    <li class="list-group-item">
                        <a href="<?php echo base_url('opd');?>">OPD</a><div class="mypets"> <span class="badge-success" id="opd_count"><?php echo $module['opd_count'];?></span></div>
                        <div class="thepet" id="thepetd">
                            <ul class="list-group" id="opd_spci_count"></ul>
                         </div>
                    </li>
                   
                   
               <?php } if(in_array('121',$users_data['permission']['section']) && in_array('733',$users_data['permission']['action'])){ ?>      					
      					   <li class="list-group-item"><a href="<?php echo base_url('ipd_booking'); ?>">IPD <span class="badge-success" id="ipd_count"><?php echo $module['ipd_count'];?></span></a></li>
                <?php } if(in_array('145',$users_data['permission']['section']) && in_array('871',$users_data['permission']['action'])){ ?>  
      					   <li class="list-group-item"><a href="<?php echo base_url('test'); ?>">Pathology <span class="badge-success" id="path_count"><?php echo $module['path_count'];?></span></a></li>
                <?php } if(in_array('134',$users_data['permission']['section']) && in_array('807',$users_data['permission']['action'])){ ?> 
      					   <li class="list-group-item"><a href="<?php echo base_url('ot_booking'); ?>">OT <span class="badge-success" id="ot_count"><?php echo $module['ot_count'];?></span></a></li>
                <?php } if(in_array('60',$users_data['permission']['section']) && in_array('399',$users_data['permission']['action'])){ ?> 
      					<li class="list-group-item"><a href="<?php echo base_url('sales_medicine'); ?>">Pharmacy <span class="badge-success" id="phar_count"><?php echo $module['phar_count'];?></span></a></li>
                <?php } if(in_array('151',$users_data['permission']['section']) && in_array('909',$users_data['permission']['action'])){ ?> 
      					<li class="list-group-item"><a href="<?php echo base_url('opd_billing'); ?>">Billing <span class="badge-success" id="bill_count"><?php echo $module['bill_count'];?></span></a></li>
              <?php } ?>             
      				</ul>
      			</div>
      		</div>

      		<div class="panel panel-success _dashpanel">
      			<div class="panel-heading">
      				<h4>You can opt more services </h4>
      			</div>
      			<div class="panel-body">
      				<ul class="list-group" data-listing="">
      					<!-- <li class="list-group-item"><a href="https://www.sarasolutions.in/hospital-managemnt-advance-software">Hospital Management Advance Software</a></li>
      					<li class="list-group-item"><a href="https://www.sarasolutions.in/opd-management-software
      						">OPD Management Software</a></li>
      						<li class="list-group-item"><a href="https://www.sarasolutions.in/pms">Pathology Management Software</a></li>
      						<li class="list-group-item"><a href="https://www.sarasolutions.in/pharmacy-management-software">Pharmacy Management Software</a></li>
      						<li class="list-group-item"><a href="https://www.sarasolutions.in/ophthalmology-management-software">Ophthalmology Management Software</a></li>
      						<li class="list-group-item"><a href="https://www.sarasolutions.in/dental-management-software">Dental Management Software</a></li>
      						<li class="list-group-item"><a href="https://www.sarasolutions.in/paediatric-management-software
      							">Paediatric Management Software</a></li>
      							<li class="list-group-item"><a href="https://www.sarasolutions.in/gynecology-management-software">Gynecology Management Software</a></li>
      							 --><li class="list-group-item"><a href="https://www.sarasolutions.in/ambulance-management-software" target="_black">Ambulance Management Software</a></li>
      							<li class="list-group-item"><a href="https://www.sarasolutions.in/blood-bank-management" target="_black">Blood Bank Management Software</a></li>
      							<li class="list-group-item"><a href="https://www.sarasolutions.in/payroll-management-software" target="_black">Payroll Management Software</a></li>
      							<li class="list-group-item"><a href="https://www.sarasolutions.in/queue-management-software" target="_black">Queque Management Software</a></li>
      							<li class="list-group-item"><a href="https://www.sarasolutions.in/interfaces" target="_black">Interfaces</a></li>
      							<li class="list-group-item"><a href="https://www.sarasolutions.in/ivr-services" target="_black">IVR Services</a></li>
      						</ul>
      			</div>
      		</div>
      	</div>
      </div>
   </div>
   <footer>
      <?php $this->load->view('include/footer'); ?>
   </footer>



   <div class="dashSideBar">
   	
   </div>
</main>
			</div> <!-- innerRow -->
		</div> <!-- 12 -->
	</div> <!-- row -->
<?php
$this->load->view('include/footer');
?>
</div> <!-- mainContainerFluid -->
<script type="text/javascript">
 var $modal2 = $('#load_add_collection_modal_popup');
 $('#collection_report').on('click', function(){  
     $modal2.load('<?php echo base_url().'reports/collections/' ?>',{/*'id1': '1',//'id2': //'2'*/},
     function(){ 
          $modal2.modal('show');
     });

});
</script>
<script type="text/javascript">
$('document').ready(function(){

	/* 17-03-2020 */

<?php if($expiry_notice){ ?>
 
  $('#expiry_notice').modal({
      backdrop: 'static',
      keyboard: false
        })
<?php } ?>
	/* 17-03-2020 */


	/*document expiry*/
<?php if($expiry_docs &&  in_array('405',$users_data['permission']['section'])){ ?>
 
  $('#expiry_document').modal({
      backdrop: 'static',
      keyboard: false
        })
<?php } ?>
  /* document expiry */
  
  	/*DL expiry*/
<?php if($expiry_driving_license && in_array('405',$users_data['permission']['section'])){ ?>
 
  $('#expiry_driving_license').modal({
      backdrop: 'static',
      keyboard: false
        })
<?php } ?>
  /*DL expiry */
  
  <?php if(!empty($inventory_purchase_due) && in_array('165',$permission_section)){ ?>

  $('#inventory_purchase_due_payment').modal({
      backdrop: 'static',
      keyboard: false
        })
<?php } ?>

<?php 
if(!empty($birthday_list['patient_list']) || !empty($birthday_list['doctor_list']) || !empty($birthday_list['employees_list']))
{
	
?>	

 
  $('#birthday_list').modal({
      backdrop: 'static',
      keyboard: false
        })
<?php 

}
?>
$('#app_notification').modal({
      backdrop: 'static',
      keyboard: false
  })
<?php 
if(!empty($notice_list) && count($notice_list)>0)
{
 ?>	

  $('#notice_list').modal({
      backdrop: 'static',
      keyboard: false
        })
<?php 

}
?>

});

function send_sms(id,type)
{ 
  var $modal = $('#load_send_sms_modal_popup');
  $modal.load('<?php echo base_url().'dashboard/send_sms/' ?>',
  {
    'id': id,
    'type':type
  },
  function(){
  $modal.modal('show');
  });
} 
$(document).ready(function() {
  $('#load_send_sms_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

function send_email(id,type)
{ 
  var $modal = $('#load_send_email_modal_popup');
  $modal.load('<?php echo base_url().'dashboard/send_email/' ?>',
  {
    'id': id,
    'type':type
  },
  function(){
  $modal.modal('show');
  });
} 
$(document).ready(function() {
  $('#load_send_email_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});

function get_today_booking()
{
$.ajax({url: "<?php echo base_url(); ?>dashboard/today_booking_list/", 
  success: function(result)
  {
    $('#booking_box').html(result); 
  } 
});
get_city(); 
}
</script>
</body>
</html>


<div id="notice_list" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
           
          <div class="modal-header" style="padding: 0px 10px;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4>Notice Board</h4>
        </div>

    <div class="modal-body">
    	 
			 
			
            <?php 
            if(!empty($notice_list))
			{
				if(!empty($notice_list))
				{ 
					?>	

			<table id="table" class="table table-striped table-bordered notice_list" cellspacing="0" width="100%">
            
				<tbody>
				<?php 
				if(!empty($notice_list))
				{
				?> 
					<?php 
					foreach ($notice_list as $notice) 
					{	
						?>
						<div class="dash_chat"> 
							<div class="dchat_body">
								<span class="arrow"></span>
								<p><?php echo $notice->msg; ?></p> 
								<div class="time"> <?php echo $notice->user_name; ?> <small><?php echo date('d-m-Y h:i:A',strtotime($notice->created_date)); ?></small></div>
							</div>
						</div> 
						 

						<?php 
					}
				  }
				
					?>	
				</tbody>
			</table>
				  
				  
				  <?php } }?>

            </div> <!-- close -->
				
   
        </div>
      </div>
      </div>
      <!-- featured popup -->
      <?php 
      $close_popup = $this->session->userdata('close_popup');
      ?>
     <div <?php if($close_popup=='1'){ }else{ ?>id="app_notification33"  <?php } ?> class="modal fade">
           <div class="modal-dialog" style="width:820px;">
       <div class="modal-content">
          
         <div class="modal-header" style="padding: 0px 0px 0px 0px; text-align: center;border-bottom:0px;">
          <button type="button" class="close" data-dismiss="modal" onclick="return set_close_popup();" aria-label="Close"><span aria-hidden="true">&times;</span></button>
         </div>
           <div class="modal-body" style="padding:0px;">
          <img src="<?php echo base_url('assets/images/');?>Sara-Software.jpg" class="img-responsive">
          </div>
       </div>
           
           
           </div>
           </div> 

           <div  class="modal fade enjoy-the-prelaunching" id="app_notification22">
           	<div class="modal-dialog" style="max-width:70%;margin-top:5px;">
           		<div class="modal-content">
           			<!-- Modal body -->
           			<div class="modal-body text-center">
           				<img src="https://www.reachyourdoctors.com/assets/images/ryd_logo.png" alt="RYD logo" class="img-fluid bg-white" width="120">
           				<h5 class="display-5 font-weight-bold mt-3 ml-2">A COMPLETE MEDICAL PLATFORM</h5>

           			</div>
           			<div class="modal-body" style="max-height:550px;overflow-y:auto;">
           				<div class="row">
           					<div class="col-lg-6 mb-2">
           						<div class="alert alert-danger bg-white">
           							<strong>Benefits for Medical Stores</strong>
           							<ul class="ul_listing small">
           								<li class="red"> Online Presence at nominal Cost. </li>
           								<li class="red"> Get direct Prescription from Patients. </li>
           								<li class="red"> Get Online Order of Medicines &amp; delivered at their doorsteps. </li>
           								<li class="red"> Direct Payments in your Account. </li>
           								<li class="red"> All Previous Records at one place. </li>
           								<li class="red"> Direct Bill send to your Patients. </li>
           								<li class="red"> A complete m-Wallet for your Patients &amp; their family. </li>
           							</ul>
           						</div>
           					</div>
           					<div class="col-lg-6 mb-2">
           						<div class="alert alert-danger bg-white">
           							<strong>Benefits for PathLabs</strong>
           							<ul class="ul_listing small">
           								<li class="red"> Online Presence at nominal Cost. </li>
           								<li class="red"> Get direct Prescription or Tests from Patients. </li>
           								<li class="red"> Get Online Order For Home Collection of Sample.. </li>
           								<li class="red"> Direct Payments in your Account. </li>
           								<li class="red"> All Previous Records at one place. </li>
           								<li class="red"> Direct Bill send to your Patients. </li>
           								<li class="red"> A complete m-Wallet for your Patients &amp; their family. </li>
           							</ul>
           						</div>
           					</div>
           					<div class="col-lg-6 mb-2">
           						<div class="alert alert-danger bg-white">
           							<strong>Benefits for Doctors</strong>
           							<ul class="ul_listing small">
           								<li class="red"> Online Presence at nominal Cost. </li>
           								<li class="red"> Saving of yours &amp; your Patients time. </li>
           								<li class="red"> Online Appointments according to available Time Slots. </li>
           								<li class="red"> Prescribed them on the basis of their saved previous History(both Online/Offline). </li>
           								<li class="red"> Get their Lab Reports online. </li>
           								<li class="red"> Direct Payments in your Account. </li>
           								<li class="red"> All Previous Records at one place. </li>
           								<li class="red"> A complete m-Wallet for your Patients &amp; their family. </li>
           							</ul>
           						</div>
           					</div>
           					<div class="col-lg-6 mb-2">
           						<div class="alert alert-danger bg-white">
           							<strong>Benefits for Hospitals</strong>
           							<ul class="ul_listing small">
           								<li class="red"> Online Presence at nominal Cost. </li>
           								<li class="red"> Saving of yours &amp; your Patients time. </li>
           								<li class="red"> Online Appointments according to available Time Slots of Doctors. </li>
           								<li class="red"> Doctors can prescribed them on the basis of their saved previous History (both Online/ Offline). </li>
           								<li class="red"> Get their Lab Reports online. </li>
           								<li class="red"> Direct Payments in your Account. </li>
           								<li class="red"> All Previous Records at one place. </li>
           								<li class="red"> Complete Database of your patients to be used for Discounts/Camps etc. </li>
           								<li class="red"> A complete m-Wallet for your Patients &amp; their family. </li>
           							</ul>
           						</div>
           					</div>
           				</div>



           			</div>
           			<div class="modal-footer">
           				<a href="https://www.reachyourdoctors.com/benefits" target="_blank" class="btn btn-danger">View more</a>
           				<button type="button" class="btn btn-default" style="width:auto;" data-dismiss="modal" onclick="$('.enjoy-the-prelaunching').hide();">Close</button>
           			</div>
           		</div>
           	</div>
           </div>


           <div id="birthday_list" class="modal fade">
           	<div class="modal-dialog">
           		<div class="modal-content">

           			<div class="modal-header" style="padding: 0px 10px;">
           				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           				<h4>Notification Birthday/Anniversary</h4>
           			</div>

           			<div class="userlist-box">
<!-- <table id="table" class="table table-striped table-bordered employee_list" cellspacing="0" width="100%">
<thead class="bg-theme">
<tr>
<th>&nbsp; </th><th> Name </th><th> Email </th><th> Mobile No. </th><th> Action </th>
</tr>
</thead>
</table> -->
<div class="modal-header" style="background: #13b96e;"><b>Today's Birthday</b></div>
<?php 
if(!empty($birthday_list['patient_list']) || !empty($birthday_list['doctor_list']) || !empty($birthday_list['employees_list']))
{
	if(!empty($birthday_list['patient_list']))
		{ ;
			?>	

			<table id="table" class="table table-striped table-bordered birthday_list" cellspacing="0" width="100%">

				<tbody>
					<?php 
					if(!empty($birthday_list['doctor_list']))
					{
						?>
						<tr role="row" class="odd">
							<th colspan="6" class="text-center">Doctors</th>
						</tr>
						<?php 
						foreach ($birthday_list['doctor_list'] as $doctor) 
						{	
							?>
							<tr role="row" class="odd">
								<td>&nbsp;</td><td><?php echo $doctor->doctor_name; ?></td><td><?php echo $doctor->email; ?></td><td><?php echo $doctor->mobile_no; ?></td><td><?php if(!empty($doctor->mobile_no)){ ?>
									<a href="javascript:void(0)" class="btn-custom" onclick="send_sms(<?php echo $doctor->id;?>,1);"><i class="fa fa-envelope-o" aria-hidden="true"></i>
									SMS</a> 

									<?php } if(!empty($doctor->email)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_email(<?php echo $doctor->id;?>,1);"><i class="fa fa-envelope" aria-hidden="true"></i>
									Email</a> <?php } ?> </td></tr>

									<?php 
								}
							}
							if(!empty($birthday_list['patient_list']))
							{
								?>
								<tr role="row" class="odd">
									<th colspan="6" class="text-center">Patients</th>
								</tr>
								<?php

								foreach ($birthday_list['patient_list'] as $patient) 
								{	
									?>
									<tr role="row" class="odd">
										<td>&nbsp;</td><td><?php echo $patient->patient_name; ?></td><td><?php echo $patient->patient_email; ?></td><td><?php echo $patient->mobile_no; ?></td><td><?php if(!empty($patient->mobile_no)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_sms(<?php echo $patient->id ?>,2);"><i class="fa fa-envelope-o" aria-hidden="true"></i>
										SMS</a> <?php } if(!empty($patient->patient_email)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_email(<?php echo $patient->id ?>,2);"><i class="fa fa-envelope" aria-hidden="true"></i>
										Email</a> <?php } ?> </td></tr>

										<?php 
									}
								}
								if(!empty($birthday_list['employees_list']))
								{	
									?>
									<tr role="row" class="odd">
										<th colspan="6" class="text-center">Employees</th>
									</tr>
									<?php
									foreach ($birthday_list['employees_list'] as $employees) 
									{	
										?>
										<tr role="row" class="odd">
											<td>&nbsp;</td><td><?php echo $employees->name; ?></td><td><?php echo $employees->email; ?></td><td><?php echo $employees->contact_no; ?></td><td><?php if(!empty($employees->contact_no)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_sms(<?php echo $employees->id; ?>,3);"><i class="fa fa-envelope-o" aria-hidden="true"></i>
											SMS</a> <?php } if(!empty($employees->email)){ ?><a  href="javascript:void(0)" class="btn-custom" onclick="send_email(<?php echo $employees->id; ?>,3);"><i class="fa fa-envelope" aria-hidden="true"></i>
											Email</a> <?php } ?> </td></tr>

											<?php 
										}
									}
									?>	
								</tbody>
							</table>

							<?php 
						}
					}
					?>
				</div> <!-- close -->

				<div class="modal-content">
					<div class="modal-header" style="background: #13b96e;"><b>Today's Anniversary</b></div>


					<div class="userlist-box">
						<?php 
						if(!empty($anniversary_list['patient_list']) || !empty($anniversary_list['doctor_list']) || !empty($anniversary_list['employees_list']))
						{
							if(!empty($anniversary_list['patient_list']))
								{ ;
									?>	
									<table id="table" class="table table-striped table-bordered birthday_list" cellspacing="0" width="100%">

										<tbody>
											<?php 
											if(!empty($anniversary_list['doctor_list']))
											{
												?>
												<tr role="row" class="odd">
													<th colspan="6" class="text-center">Doctors</th>
												</tr>
												<?php 
												foreach ($anniversary_list['doctor_list'] as $doctor) 
												{	
													?>
													<tr role="row" class="odd">
														<td>&nbsp;</td><td><?php echo $doctor->doctor_name; ?></td><td><?php echo $doctor->email; ?></td><td><?php echo $doctor->mobile_no; ?></td><td><?php if(!empty($doctor->mobile_no)){ ?>
															<a href="javascript:void(0)" class="btn-custom" onclick="send_sms(<?php echo $doctor->id;?>,4);"> <i class="fa fa-envelope-o" aria-hidden="true"></i>
															SMS</a> 

															<?php } if(!empty($doctor->email)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_email(<?php echo $doctor->id;?>,4);"><i class="fa fa-envelope" aria-hidden="true"></i>
															Email</a> <?php } ?> </td></tr>

															<?php 
														}
													}
													if(!empty($anniversary_list['patient_list']))
													{
														?>
														<tr role="row" class="odd">
															<th colspan="6" class="text-center">Patients</th>
														</tr>
														<?php

														foreach ($anniversary_list['patient_list'] as $patient) 
														{	
															?>
															<tr role="row" class="odd">
																<td>&nbsp;</td><td><?php echo $patient->patient_name; ?></td><td><?php echo $patient->patient_email; ?></td><td><?php echo $patient->mobile_no; ?></td><td><?php if(!empty($patient->mobile_no)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_sms(<?php echo $patient->id ?>,5);"><i class="fa fa-envelope-o" aria-hidden="true"></i>
																SMS</a> <?php } if(!empty($patient->patient_email)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_email(<?php echo $patient->id ?>,5);"><i class="fa fa-envelope" aria-hidden="true"></i>
																Email</a> <?php } ?> </td></tr>

																<?php 
															}
														}
														if(!empty($anniversary_list['employees_list']))
														{	
															?>
															<tr role="row" class="odd">
																<td colspan="6"><h4>Employees</h4></td>
															</tr>
															<?php
															foreach ($anniversary_list['employees_list'] as $employees) 
															{	
																?>
																<tr role="row" class="odd">
																	<td>&nbsp;</td><td><?php echo $employees->name; ?></td><td><?php echo $employees->email; ?></td><td><?php echo $employees->contact_no; ?></td><td><?php if(!empty($employees->contact_no)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_sms(<?php echo $employees->id; ?>,6);"><i class="fa fa-envelope-o" aria-hidden="true"></i>
																	SMS</a> <?php } if(!empty($employees->email)){ ?><a href="javascript:void(0)" class="btn-custom" onclick="send_email(<?php echo $employees->id; ?>,6);"><i class="fa fa-envelope" aria-hidden="true"></i>
																	Email</a> <?php } ?> </td></tr>

																	<?php 
																}
															}
															?>	
														</tbody>
													</table>

													<?php 
												}
											}
											?>
										</div> <!-- close -->
										<div class="modal-footer">

											<button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
										</div>
									</div>
								</div>
							</div>  
						</div>

      <!--- 17-03-2020 --->
      <div id="expiry_notice" class="modal">
      	<div class="modal-dialog">


      		<div class="modal-content">
      			<div class="modal-header" style="background: #13b96e;"></div>


      			<div class="modal-body"><b>Your Branch validity going to expire on <?php  echo date('d-m-Y',strtotime($expiry_notice->end_date));?>. To continue service kindly recharge.</b></div>
      			<div class="modal-footer">

      				<button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      			</div>
      		</div>
      	</div>
      </div> 
      <!--- 17-03-2020-->
      
      
      <div id="expiry_document" class="modal">
        <div class="modal-dialog">


          <div class="modal-content">
            <div class="modal-header" style="background: #13b96e;"></div>
            <div class="modal-body"  style="height: calc(100vh - 140px);
    overflow-y: auto;">
              <h4>These Ambulance Document Goes To Expiry Date</h4>
              <table class="table table-responsive">
                <thead>
                  <tr>
                    <th width="15%">Document Name</th>
                    <th>Vehicle No.</th>
                    <th>Chassis No.</th>
                    <th>Expiry date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($expiry_docs)) { 
                    foreach ($expiry_docs as $key => $docs) { ?>                    
                    <tr>
                      <td><?php echo $docs->document; ?></td>
                      <td><?php echo $docs->vehicle_no; ?></td>
                      <td><?php echo $docs->chassis_no; ?></td>
                      <td><?php echo date('d-m-Y',strtotime($docs->expiry_date)); ?></td>
                    </tr>
                  <?php }}?>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">

              <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
          </div>
        </div>
      </div> 
      <!--- 23-04-2020-->
      
      
      
      <!---29-04-202---->
      <div id="expiry_driving_license" class="modal">
        <div class="modal-dialog">


          <div class="modal-content">
            <div class="modal-header" style="background: #13b96e;"></div>
            <div class="modal-body">
              <h4>These Driver License Goes To Expire</h4>
              <table class="table table-responsive">
                <thead>
                  <tr>
                      <th width="15%">Driver Name</th>
                    <th width="15%">License No</th>
                    <th>Expiry date</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(!empty($expiry_driving_license)) { 
                    foreach ($expiry_driving_license as $key => $license) { ?>                    
                    <tr>
                        <td><?php echo $license->driver_name; ?></td>
                      <td><?php echo $license->licence_no; ?></td>
                      <td><?php echo date('d-m-Y',strtotime($license->dl_expiry_date)); ?></td>
                    </tr>
                  <?php }}?>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">

              <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
          </div>
        </div>
      </div> 
      <!---29-04-202---->
      
      <!-- Inventory payment due -->
     <?php if(!empty($inventory_purchase_due)) { ?>
       <!-- <div id="inventory_purchase_due_payment" class="modal">
        <div class="modal-dialog">


         <div class="modal-content">
            <div class="modal-header" style="background: #13b96e;"></div>
            <div class="modal-body"  style="height: calc(100vh - 140px);overflow-y: auto;">
              <h4>Inventory Payment Due</h4>
              <table class="table table-responsive">
                <thead>
                  <tr>
                      <th width="25%">Vendor Name</th>
                    <th>Due Date</th>
                    <th>Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                    foreach ($inventory_purchase_due as $key =>$payment_due) { 
                    if(!empty($payment_due->payment_due_date) && $payment_due->due_amount>0){
                    ?>                    
                    <tr>
                        <td><?php echo $payment_due->name; ?></td>
                      
                      <td><?php echo date('d-m-Y',strtotime($payment_due->	payment_due_date)); ?></td>
                      <td><?php echo $payment_due->due_amount; ?></td>
                    </tr>
                  <?php } } ?>
                </tbody>
              </table>
            </div>
            <div class="modal-footer">

              <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
            </div>
          </div>
        </div>
      </div>  -->
      <?php } ?>
      <!-- end of payment due -->
   
<div id="load_change_password_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_expense_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_send_sms_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_send_email_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>Chart.min.js"></script>
<script> 
  $(document).ready(function() { 
     $.ajax({
     url: "<?php echo base_url('dashboard/graph_ajax/'); ?>", 
     type: 'POST',
     data: {},
     success: function(result)
     { 
       var obj=JSON.parse(result);
      
       var patints=[];
       var collections=[];
      if(obj.collection.length > 0)
      {
         for (var i =0; i < obj.collection[0].m-1; i++) {
            collections.push('0')
         }
       }
      if(obj.patient_count.length > 0)
      {
          for (var i =0; i < obj.patient_count[0].m-1; i++) {
            patints.push('0')
         }
     }
          $.each(obj.collection, function (key, val1) {
          	var debits=val1.debit/100000;
              collections.push(debits)
          });
           $.each(obj.patient_count, function (key, val) {
              patints.push(val.total_patient);
          });
        new Chart(document.getElementById("chartContainer"), {
          type: 'line',
          data: {
            labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
            datasets: [{ 
                data: collections,
                label: "Earning (In Lac)",
                borderColor: "#12dd81",
                fill: false
              }, { 
                data: patints,
                label: "Patient Registration",
                borderColor: "#07b3ff",
                fill: false
              },
            ]
          },
          options: {
            title: {
              display: true,
              text: 'HMAS Patient Registration and Collection'
            }
          }
        });  
      }

      });
   });   


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

function set_close_popup()
{
  $.ajax({
	   url: "<?php echo base_url('dashboard/close_popup/'); ?>", 
	   type: 'POST',
	  
	   success: function(result)
	   { 
	   	 
	   }
   });   
}
function form_submit(vals)
{ 
  var start_date = $('#start_date').val();
  var end_date = $('#end_date').val();
  var debit='0';
  var balance='0';
 $.ajax({
	   url: "<?php echo base_url('dashboard/ajax_change/'); ?>", 
	   type: 'POST',
	   data: {start_date: start_date, end_date : end_date},
	   success: function(result)
	   { 
	   	 var obj=JSON.parse(result);
       console.log(obj);
	   	 $('#total_patient').text(obj.counts.patient_count.total_patient);
	   	 if(obj.counts.collection.debit !='' || obj.counts.collection.debit !=null)
	   	 {
	   	 	debit=obj.counts.collection.debit;
	   	 }
	   	 if(obj.counts.collection.balance !='' && obj.counts.collection.balance>1)
	   	 {
	   	 	balance=obj.counts.collection.balance;
	   	 }
	   	 $('#total_earn').text(debit.toFixed(2));
	   	 $('#total_bal').text(balance.toFixed(2));
	   	 $('#opd_count').text(obj.module.opd_count);
		 $('#ipd_count').text(obj.module.ipd_count);
		 $('#path_count').text(obj.module.path_count);
		 $('#ot_count').text(obj.module.ot_count);
		 $('#phar_count').text(obj.module.phar_count);
		 $('#bill_count').text(obj.module.bill_count);
		 
		 $("#thepetd").css("display", "block");
		 //alert(obj.module_specialization);
		 $('#opd_spci_count').empty();
		 $("#opd_spci_count").append(obj.module_specialization);
		 
		 
		 
		 
		 
	   }
   });      
 }

 var SI_SYMBOL = ["", "K", "M", "G", "T", "P", "E"];

    function abbreviateNumber(number){

    // what tier? (determines SI symbol)
    var tier = Math.log10(number) / 3 | 0;

    // if zero, we don't need a suffix
    if(tier == 0) return number;

    // get suffix and determine scale
    var suffix = SI_SYMBOL[tier];
    var scale = Math.pow(10, tier * 3);

    // scale the number
    var scaled = number / scale;

    // format number and add suffix
    return scaled.toFixed(1) + suffix;
}


</script>
<script type="text/javascript">

//Initialize first demo:
ddaccordion.init({
	headerclass: "mypets", //Shared CSS class name of headers group
	contentclass: "thepet", //Shared CSS class name of contents group
	revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
	mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
	collapseprev: false, //Collapse previous content (so only one open at any time)? true/false 
	defaultexpanded: [], //index of content(s) open by default [index1, index2, etc]. [] denotes no content.
	onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
	animatedefault: false, //Should contents open by default be animated into view?
	scrolltoheader: false, //scroll to header each time after it's been expanded by the user?
	persiststate: false, //persist state of opened contents within browser session?
	toggleclass: ["", "openpet"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
	togglehtml: ["none", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
	animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
	oninit:function(expandedindices){ //custom code to run when headers have initalized
		//do nothing
	},
	onopenclose:function(header, index, state, isuseractivated){ //custom code to run whenever a header is opened or closed
		//do nothing
	}
})


</script>
