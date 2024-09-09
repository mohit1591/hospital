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
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>

<script type="text/javascript">
var save_method; 





 
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
 <form>
  <input type="radio" name="type" value="1" href="javascript:void(0)" > Intimate
  <input type="radio" name="type" value="2" onClick="return show_auto()" href="javascript:void(0)"> Auto
  <input type="radio" name="type" value="3" onClick="return show_auto()" href="javascript:void(0)"> None
  </div> <!-- close -->
  <input type="submit" name="submit" value="Save">
</form>
<div id="birthday_list" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
            
          	<div class="modal-header">
	          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
	          <h4><h4>Today Birthday</h4></h4> 
	      </div>


    <div class="userlist-box">
            <?php 
            if(!empty($birthday_list['patient_list']) || !empty($birthday_list['doctor_list']) || !empty($birthday_list['employees_list']))
        			{
        				if(!empty($birthday_list['patient_list']))
        				{ 
        					?>	
        		<table id="table" class="table table-striped table-bordered employee_list" cellspacing="0" width="100%">
                    <thead class="bg-theme">
                            <tr>
                            <th> Name </th><th> Email </th><th> Mobile No. </th><th> Action </th>
                            </tr>
                        </thead>
        				<tbody>
        				<?php 
        				if(!empty($birthday_list['doctor_list']))
        				{
        				?>
        					<tr role="row" class="odd">
        					<td colspan="4"><h4>Doctor</h4></td>
        					</tr>
        					<?php 
        					foreach ($birthday_list['doctor_list'] as $doctor) 
        					{	
        						?>
        						<tr role="row" class="odd">
        						<td><?php echo $doctor->doctor_name; ?></td><td><?php echo $doctor->email; ?></td><td><?php echo $doctor->mobile_no; ?></td><td><?php if(!empty($doctor->mobile_no) && $doctor->birth_sms_year!=date('Y')){ ?>
                      <a href="javascript:void(0)" class="btn-sms" onclick="send_sms(<?php echo $doctor->id;?>,1);"><i class="fa fa-cog"></i> Sms</a> 

        						 <?php } if(!empty($doctor->email) && $doctor->birth_email_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_email(<?php echo $doctor->id;?>,1);">Send Email</a> <?php } ?> </td></tr>

        						<?php 
        					}
        				}
        					if(!empty($birthday_list['patient_list']))
        					{
        					?>
        					<tr role="row" class="odd">
        					<td colspan="4"><h4>Patient</h4></td>
        					</tr>
        					<?php

        					foreach ($birthday_list['patient_list'] as $patient) 
        					{	
        						?>
        						<tr role="row" class="odd">
        						<td><?php echo $patient->patient_name; ?></td><td><?php echo $patient->patient_email; ?></td><td><?php echo $patient->mobile_no; ?></td><td><?php if(!empty($patient->mobile_no) && $patient->birth_sms_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_sms(<?php echo $patient->id ?>,2);"><i class="fa fa-cog"></i> Sms</a> <?php } if(!empty($patient->patient_email) && $patient->birth_email_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_email(<?php echo $patient->id ?>,2);">Send Email</a> <?php } ?> </td></tr>

        						<?php 
        					}
        					}
        					if(!empty($birthday_list['employees_list']))
        					{	
        					 ?>
        					<tr role="row" class="odd">
        					<td colspan="4"><h4>Employees</h4></td>
        					</tr>
        					<?php
        					foreach ($birthday_list['employees_list'] as $employees) 
        					{	
        						?>
        						<tr role="row" class="odd">
        						<td><?php echo $employees->name; ?></td><td><?php echo $employees->email; ?></td><td><?php echo $employees->contact_no; ?></td><td><?php if(!empty($employees->contact_no) && $employees->birth_sms_year!=date('Y') ){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_sms(<?php echo $employees->id; ?>,3);"><i class="fa fa-cog"></i> Sms</a> <?php } if(!empty($employees->email) && $employees->birth_email_year!=date('Y')){ ?><a  href="javascript:void(0)" class="btn-sms" onclick="send_email(<?php echo $employees->id; ?>,3);">Send Email</a> <?php } ?> </td></tr>

        						<?php 
        					}
        				}
        					?>	
        					</tbody>
        				  </table>

        					<?php 
        				}
        			}

              if(!empty($anniversary_list['patient_list']) || !empty($anniversary_list['doctor_list']) || !empty($anniversary_list['employees_list']))
              {
                if(!empty($anniversary_list['patient_list']))
                { 
                  ?>  
            <table id="table" class="table table-striped table-bordered employee_list" cellspacing="0" width="100%">
                    <thead class="bg-theme">
                            <tr>
                            <td colspan="4">Today Anniversary</td>
                            </tr>
                        </thead>
                <tbody>
                <?php 
                if(!empty($anniversary_list['doctor_list']))
                {
                ?>
                  <tr role="row" class="odd">
                  <td colspan="4"><h4>Doctor</h4></td>
                  </tr>
                  <?php 
                  foreach ($anniversary_list['doctor_list'] as $doctor) 
                  { 
                    ?>
                    <tr role="row" class="odd">
                    <td><?php echo $doctor->doctor_name; ?></td><td><?php echo $doctor->email; ?></td><td><?php echo $doctor->mobile_no; ?></td><td><?php if(!empty($doctor->mobile_no) && $doctor->anni_sms_year!=date('Y')){ ?>
                                <a href="javascript:void(0)" class="btn-sms" onclick="send_sms(<?php echo $doctor->id;?>,1);"><i class="fa fa-cog"></i> Sms</a> 

                     <?php } if(!empty($doctor->email) && $doctor->anni_email_send_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_email(<?php echo $doctor->id;?>,4);">Send Email</a> <?php } ?> </td></tr>

                    <?php 
                  }
                }
                  if(!empty($anniversary_list['patient_list']))
                  {
                  ?>
                  <tr role="row" class="odd">
                  <td colspan="4"><h4>Patient</h4></td>
                  </tr>
                  <?php

                  foreach ($anniversary_list['patient_list'] as $patient) 
                  { 
                    ?>
                    <tr role="row" class="odd">
                    <td><?php echo $patient->patient_name; ?></td><td><?php echo $patient->patient_email; ?></td><td><?php echo $patient->mobile_no; ?></td><td><?php if(!empty($patient->mobile_no) && $patient->anni_sms_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_sms(<?php echo $patient->id ?>,2);"><i class="fa fa-cog"></i> Sms</a> <?php } if(!empty($patient->patient_email) && $patient->anni_email_send_year!=date('Y') ){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_email(<?php echo $patient->id ?>,5);">Send Email</a> <?php } ?> </td></tr>

                    <?php 
                  }
                  }
                  if(!empty($anniversary_list['employees_list']))
                  { 
                   ?>
                  <tr role="row" class="odd">
                  <td colspan="4"><h4>Employees</h4></td>
                  </tr>
                  <?php
                  foreach ($anniversary_list['employees_list'] as $employees) 
                  { 
                    ?>
                    <tr role="row" class="odd">
                    <td><?php echo $employees->name; ?></td><td><?php echo $employees->email; ?></td><td><?php echo $employees->contact_no; ?></td><td><?php if(!empty($employees->contact_no) && $employees->anni_sms_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_sms(<?php echo $employees->id; ?>,3);"><i class="fa fa-cog"></i> Sms</a> <?php } if(!empty($employees->email) && $employees->anni_email_send_year!=date('Y')){ ?><a href="javascript:void(0)" class="btn-sms" onclick="send_email(<?php echo $employees->id; ?>,6);">Send Email</a> <?php }  ?> </td></tr>

                    <?php 
                  }
                }
                  ?>  
                  </tbody>
                  </table>

                  <?php 
                }
              }

              /*else
              {
                ?>
                <table id="table" class="table  "  width="100%">
                   
                            <tr>
                            <td colspan="4">No record found. </td>
                            </tr>
                        
               
                </table>
                <?php 
              }*/
            ?>
            </div> <!-- close -->
				 <div class="modal-footer"> 
         
         <!-- <button type="button" class="btn-cancel" data-number="1">Close</button> -->
          <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
      </div>
          </div>
        </div>
  </div>

<form id="template_form">
  <div id="template_list" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
            
          	<div class="modal-header">
	          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
	          <h4><h4>Set Template</h4></h4> 
	      </div>


			<div class="userlist-box">

			<div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Birthday SMS</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="sms" value="1" <?php if($sms_email_setting['sms_birthday']==1){ echo 'checked';} ?> id="birth_message_yes" class="birth_sms">Yes
					<input type="radio" name="sms" <?php if($sms_email_setting['sms_birthday']==2){ echo 'checked';} ?> value="2" id="birth_message_no" class="birth_sms">No
                   
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
           
          </div> <!-- row -->
          <div class="row" <?php if($sms_email_setting['sms_birthday']==1){ }else{ ?> class="row" style="display:none;" <?php } ?> id="birthday_message">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Birthday SMS</label>
                </div>
                <div class="col-md-8">  
          		<textarea   type="text" name="birthday_message" /><?php echo $sms_email_setting['birthday_sms_template']; ?></textarea>
           </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->
			<div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Anniversary SMS</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="anni_sms" value="1"  <?php if($sms_email_setting['sms_anniversary']==1){ echo 'checked';} ?> id="anni_message_yes" class="anni_sms">Yes
					<input type="radio" name="anni_sms" value="2"  <?php if($sms_email_setting['sms_anniversary']==2){ echo 'checked';} ?> id="anni_message_no" class="anni_sms">No
                   
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->

          <div class="row" <?php if($sms_email_setting['sms_anniversary']==1){ }else{ ?> class="row" style="display:none;" <?php } ?> id="anni_message">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Anniversary Message</label>
                </div>
                <div class="col-md-8">  
          		<textarea   type="text" name="anni_message" /><?php echo $sms_email_setting['anniversary_sms_template']; ?></textarea>
           </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row --> 


          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Birthday Email</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="birth_email" <?php if($sms_email_setting['email_bithday']==1){ echo 'checked';} ?> id="birth_email_yes" value="1" class="opt">Yes
					<input type="radio" name="birth_email" <?php if($sms_email_setting['email_bithday']==2){ echo 'checked';} ?> id="birth_email_no" value="2" class="opt">No
                   
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 

          <div  <?php if($sms_email_setting['email_bithday']==1){ }else{ ?> class="row" style="display:none;" <?php } ?>  id="birth_email_template">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Birthday Email Template</label>
                </div>
                <div class="col-md-8">  
          		<textarea type="text" name="birth_email_template"  id="message_birthday" /><?php echo $sms_email_setting['email_birthday_template']; ?></textarea>
          		<span>Short Code:{Name}</span>
           </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->  

			<div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Anniversary Email</label>
                </div>
                <div class="col-md-8">
                    <input type="radio" name="anni_email" <?php if($sms_email_setting['email_anniversary']==1){ echo 'checked';} ?> value="1" id="anni_email_yes" class="anni_email">Yes
					<input type="radio" name="anni_email" <?php if($sms_email_setting['email_anniversary']==2){ echo 'checked';} ?> value="2" id="anni_email_no" class="anni_email">No
                   
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  	
			
			<div class="row" <?php if($sms_email_setting['email_anniversary']==1){ }else{ ?> style="display:none;" <?php } ?> id="anni_email_template">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-4">
                    <label>Anniversary Email Template</label>
                </div>
                <div class="col-md-8">
                
          		<textarea type="text" name="anni_email_template"  id="message_anniversary" /><?php  echo $sms_email_setting['anniversary_email_template']; ?></textarea>
          		<span>Short Code:{Name}</span>
           </div>
          </div> <!-- innerrow -->
        </div> <!-- 12 -->
      </div> <!-- row -->  
			
			
			</div> <!-- close -->
			<div class="modal-footer"> 
			 <button class="btn-update"  name="submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
			<!-- <button type="button" class="btn-cancel" data-number="1">Close</button> -->
       <button type="button" data-dismiss="modal" class="btn-cancel">Close</button>
			<input type="hidden" name="data_id" value="<?php echo $sms_email_setting['id']; ?>">
			<input type="hidden" name="submit" value="submit">
			</div>
          </div>
        </div>
  </div>
  </form>
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>

<script>
$("button[data-number=1]").click(function(){
    $('#load_send_sms_modal_popup').modal('hide');
});

$("button[data-number=2]").click(function(){
    $('#load_send_sms_modal_popup').modal('hide');
});


$("#template_form").on("submit", function(event) 
{

for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    } 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>birthday_anniversary/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
       
       var msg = 'Template successfully updated.';   
       flash_session_msg(msg);
       //$('.overlay-loader').hide();    
    }
  });
});

$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message_anniversary', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
})

$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message_birthday', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
})
$(document).ready(function() 
{
    //$("#birth_email_template").hide();
    $(".opt").change(function(){
        var id=$(this).attr("id");
       	if(id=="birth_email_yes")
        {
            $("#birth_email_template").show();
        }
        else
        {
        	$("#birth_email_template").hide();
        }
        });


    //$("#anni_message").hide();
    $(".anni_sms").change(function(){
        var id=$(this).attr("id");
       	if(id=="anni_message_yes")
        {
            $("#anni_message").show();
        }
        else
        {
            
            $("#anni_message").hide();
        }
        });

    //$("#birthday_message").hide();
    $(".birth_sms").change(function(){
        var id=$(this).attr("id");
       	if(id=="birth_message_yes")
        {
            $("#birthday_message").show();
        }
        else
        {
            
            $("#birthday_message").hide();
        }
        });

    //$("#anni_email_template").hide();
    $(".anni_email").change(function(){
        var id=$(this).attr("id");
       	if(id=="anni_email_yes")
        {
            $("#anni_email_template").show();
        }
        else
        {
            
            $("#anni_email_template").hide();
        }
        });

     

});

function show()
{    
$('#birthday_list').modal({
  backdrop: 'static',
  keyboard: false
})
     
}
function show_auto()
{    
$('#template_list').modal({
  backdrop: 'static',
  keyboard: false
})
     
}
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
 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>

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
<!-- Confirmation Box -->

    

<!-- Confirmation Box end -->
<div id="load_send_sms_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<div id="load_send_email_modal_popup" class="fgdfgdf modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</div><!-- container-fluid -->
</body>
</html>