<?php //print_r($today_booking_patient); 
$users_data = $this->session->userdata('auth_users'); 
$user_role= $users_data['users_role'];
?>
<!DOCTYPE html>
<html lang="en">
<head> 
<title><?php echo $page_title; ?></title>
<meta name="viewport" content="width=1024">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dms_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">   
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>  
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
</head>
<body>
</body>
<?php
  $this->load->view('include/payroll_header');
 ?>

<div class="container-fluid">

	<div class="row">
		<div class="col-md-7">


			<div class="row">
				<div class="col-sm-6 m-t-45px">
					<ul class="list-inline dash_ul1">
						<li class="text-center">
							<a href="">
								<div>Total Employee</div>
								<div class="bigTxt"><?php $total_employee_count = get_total_employee_count(); echo count($total_employee_count); ?></div>
							</a>
						</li>
						<li class="text-center">
							<a href=""><i class="fa fa-user"></i></a>
						</li>
					</ul>
				</div>
				<div class="col-sm-6 m-t-45px">
					<ul class="list-inline dash_ul2">
						<li class="text-center">
							<a href="">
								<div>Joined This Month</div>
								<div class="bigTxt"><?php $total_join_count = get_join_current_month_count(); echo count($total_join_count); ?></div>
							</a>
						</li>
						<li class="text-center">
							<a href=""><i class="fa fa-user"></i></a>
						</li>
					</ul>
				</div>
			</div>	 <!-- dash_flex -->

			<div class="row">
				<div class="col-sm-6">
					<ul class="list-inline dash_ul3">
						<li class="text-center">
							<a href="">
							
								<div>Exit's This Month</div>
								<div class="bigTxt"><?php 
									$employee_exit_count_data = get_employee_data_exit(); 
									echo count($employee_exit_count_data);
									?>

								</div>
							</a>
						</li>
						<li class="text-center">
							<a href=""><i class="fa fa-user"></i></a>
						</li>
					</ul>
				</div>
				<div class="col-sm-6">
					<ul class="list-inline dash_ul4">
						<li class="text-center">
							<a href="">
								<div>Holiday This Month</div>
								<div class="bigTxt">

								<?php
									$data['holiday_full_data'] = get_holiday_month_count_data();
       // print_r($data['holiday_full_data']); die;
		//echo $this->db->last_query();
		$holiday_count=0;
		foreach($data['holiday_full_data'] as $vals)
		{
			 $start= $vals->from_date;
			 $end= $vals->end_date;
			$date1= date('Y-m-d', strtotime($start .' -1 day'));
		
			$date2=date('Y-m-d',strtotime($end));
			$diff = strtotime($date2)-strtotime($date1);

			 $diff=$diff / (60 * 60 * 24);
	        $holiday_count=$holiday_count+$diff;
		
		
		}
	

	echo	$holiday_count;
	


									?>
								</div>
							</a>
						</li>
						<li class="text-center">
							<a href=""><i class="fa fa-users"></i></a>
						</li>
					</ul>
				</div>
			</div>	 
		</div> <!-- 7 -->







		<div class="col-md-5 ">

			<div class="today_birthday" style="margin-top:66px;">
				<div class="b_heading">
					<strong class="text-red"><i class="fa fa-birthday-cake"></i> This Month's Birthday </strong>
				</div>
				 
				<marquee behavior="" direction="" onmouseover="this.stop()" onmouseout="this.start()">
					<?php 
				    if(!empty($employee_data))
				     {
				     	echo"<span>";
				     	foreach($employee_data as $employee_data_val)
				     	{
				     		$dob='';
				     		echo ' ';
				     		if((!empty($employee_data_val->dob)) && ($employee_data_val->dob!=''))
				     		{
                                $dob= date("d-m-Y",strtotime($employee_data_val->dob));
				     		}
                          echo $employee_data_val->name; 
                          echo ' ';
                          echo "<strong>'".$dob."'</strong>&nbsp";
                          echo ' ';

				     	}
				     	echo"</span>";

				     }
				     else
				     {
				     	echo "<span>No Birthday this month.</span>";
				     }
					?>
				
				</marquee>
				 
				<div class="text-center" style="display:none;">No Birthday today.</div>
			</div>
		
				<div class="well dash_well">
				<div class="row">
					<div class="col-sm-6">
						<div class="emp_status"><i class="fa fa-user"></i> Employee's Status</div>
					</div>
					<div class="col-sm-6">
						<a href="" class="emp_date"><i class="fa fa-calendar"></i> <?php echo $today = date("j F, Y");   ?></a>
					</div>
				</div> 
				
				<div class="flex d_icon_flex">
					<div class="flexbox">
						<center>
							<a href="" class="d_icon1">
								<img src="assets/images/1.png" alt="">
								<strong><?php $emp_present_count = get_emp_present_absent_count('1'); 
								if($emp_present_count!="empty"){ echo count($emp_present_count); } else { echo "0"; }  ?></strong>
							</a>
							<p><strong>Present</strong></p>
						</center>
					</div>
					<div class="flexbox">
						<center>
							<a href="" class="d_icon1">
								<img src="assets/images/2.png" alt="">
								<strong><?php $emp_present_count = get_emp_present_absent_count('2'); 
								if($emp_present_count!="empty"){ echo count($emp_present_count); } else { echo "0"; }  ?></strong>
							</a>
							<p><strong>On Leave</strong></p>
						</center>
					</div>
					<div class="flexbox">
						<center>
							<a href="" class="d_icon1">
								<img src="assets/images/3.png" alt="">
								<strong><?php $emp_present_count = get_emp_present_absent_count('0'); 
								if($emp_present_count!="empty"){ echo count($emp_present_count); } else { echo "0"; }  ?></strong>
							</a>
							<p><strong>Absent</strong></p>
						</center>
					</div>
				</div>
			</div>

		</div> <!-- 5 -->
	</div>

 

</div>

<?php
$this->load->view('include/payroll_footer');
?>

<script type="text/javascript">
$('document').ready(function(){
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
</script>
</body>
</html>

<div id="birthday_list" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
           
          <div class="modal-header" style="padding: 0px 10px;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
           <h4>Notification Birthday/Anniversary</h4>
        </div>

    <div class="userlist-box">
    <div class="modal-header" style="background: #13b96e;"><b>Today's Birthday</b></div>
            <?php 
            if(!empty($birthday_list['employees_list']))
			{
			
					?>	

		<table id="table" class="table table-striped table-bordered birthday_list" cellspacing="0" width="100%">
            
				<tbody>
				<?php 
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
            ?>
            </div> <!-- close -->
				
      
        </div>
      </div>  
   
<div id="load_change_password_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_expense_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_add_collection_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_send_sms_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>

<div id="load_send_email_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
