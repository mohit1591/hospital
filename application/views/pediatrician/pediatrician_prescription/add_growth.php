<?php
$users_data = $this->session->userdata('auth_users');
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title; ?></title>
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
<script src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>
<body>
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  $booking_id=$booking_id;
 ?>
 <script type="text/javascript">

$('document').ready(function(){
 <?php
 $growth_ids=$this->session->userdata('growth_book_id'); 
 $this->session->unset_userdata('growth_book_id'); 
 if(isset($growth_ids)){ ?>
  $('#confirm_billing_print').modal({
      backdrop: 'static',
      keyboard: false
        })
  
  .one('click', '#cancel', function(e)
    { 
    }); 
       
  <?php } ?>
 });

</script>

 <script type="text/javascript">
   var save_method; 
var table;

// Function to load list by ajax
<?php
if(in_array('1629',$users_data['permission']['action'])) 
{
?>

$(document).ready(function() { 
  var book_id= document.getElementById('booking_id').value;
   var patient_id= document.getElementById('patient_id').value;
    table = $('#table').DataTable({  
        "processing": true, 
        "serverSide": true,
        searching: false,
        paging: false ,
        "bInfo": false,
        "order": [], 
        "pageLength": '20',
        "ajax": {
            "url": '<?php echo base_url("pediatrician/pediatrician_prescription/ajax_list")?>/'+patient_id,
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

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

 function delete_growth_type(id)
 {    

    $('#confirm').modal({
      backdrop: 'static',
      keyboard: false
    })
    .on('click', '#delete', function(e)
    { 
        $.ajax({
                 url: "<?php echo base_url('pediatrician/pediatrician_prescription/delete/'); ?>"+id, 
                 success: function(result)
                 {
                  //alert(result);
                    flash_session_msg(result);
                    reload_table(); 
                 }
              });
    });     
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
        .on('click', '#delete', function(e)
        {
            $.ajax({
                      type: "POST",
                      url: "<?php echo base_url('pediatrician/pediatrician_prescription/deleteall');?>",
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

<!-- ============================= Main content start here ===================================== -->
<section class="userlist">
<div class="userlist-box">
<div class="row">
<form method="post" name="growth_reg_form" id="growth_reg_form">
  <input type="hidden" name="growth_id" id="growth_id" value="<?php echo $growth_id; ?>">
  <input type="hidden" name="booking_id" id="booking_id" value="<?php echo $booking_id; ?>">
  <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $patient_id; ?>">
   <div class="col-md-12">
                   
                    <div class="col-lg-4">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>OPD No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="booking_code" value="<?php echo $growth_prescription_ipd_data['booking_code']; ?>" readonly>
                            </div>
                        </div>
                        <div class="row m-b-5 m-b-5">
                            <div class="col-xs-4"><strong><?php echo $data= get_setting_value('PATIENT_REG_NO');?></strong></div>
                            <div class="col-xs-8">
                            <input type="text"  name="patient_code" value="<?php echo $growth_prescription_ipd_data['patient_code']; ?>" readonly>
                            </div>
                        </div>
                       
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Mobile No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="mobile_no" value="<?php echo $growth_prescription_ipd_data['mobile_no']; ?>" readonly>
                            </div>
                        </div>
                        
                         <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Child Name</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="patient_name" value="<?php echo $growth_prescription_ipd_data['patient_name']; ?>" readonly>
                            </div>
                        </div>


                       
                    </div> <!-- 5 -->
                   <div class="col-lg-4">
                        <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Gender</strong></div>
                            <div class="col-xs-8">
                               <input type="radio"  value="1" <?php if($growth_prescription_ipd_data['gender']==1){ echo 'checked="checked"'; } ?>> Male &nbsp;
                                <input type="radio"  value="0" <?php if($growth_prescription_ipd_data['gender']==0){ echo 'checked="checked"'; } ?>> Female
                               
                            </div>
                        </div>
                         <div class="row m-b-5">
                            <div class="col-xs-4"><strong>Aadhaar No.</strong></div>
                            <div class="col-xs-8">
                                <input type="text" name="aadhaar_no" value="<?php echo $growth_prescription_ipd_data['adhar_no']; ?>" readonly>
                                 
                            </div>
                        </div>
                </div>
                
                
                
                      
    </div>
            <!-- row -->
             <div class="col-md-12">
 <!--<h2>Add Pediatrician Growth Prescription</h2>-->
 <br>
  <div class="col-lg-4">
    <div class="">  
     <div class="row mb-5">
        <div class="col-md-4"><b>Date Of Visit<span class="star">*</span></b></div>
         <div class="col-md-8">
              <input type="text" class="datepicker" readonly="" name="date_to_visit" id="date_to_visit" value="<?php if($growth_prescription_data!='empty' && $growth_prescription_data['date_to_visit']!='0000-00-00' && $growth_prescription_data['date_to_visit']!='1970-01-01'){ echo date('d-m-Y',strtotime($growth_prescription_data['date_to_visit'])); } ?>">
              <span id="date_to_visit_error" ></span>  
        </div>
      </div>

      <div class="row mb-5">
        <div class="col-md-4"><b>DOB<span class="star">*</span></b></div>
         <div class="col-md-8">
              <input type="text" class="datepicker" readonly="" name="dob" id="dob" value="<?php if($growth_prescription_ipd_data!='empty' && $growth_prescription_ipd_data['dob']!='0000-00-00' && $growth_prescription_ipd_data['dob']!='1970-01-01' && !empty($growth_prescription_ipd_data['dob'])){ echo date('d-m-Y',strtotime($growth_prescription_ipd_data['dob'])); } ?>"  onchange="showAge(this.value);"/>
              <span id="dob_error" ></span> 
        </div>
      </div>
      <div class="row mb-5">
        <div class="col-md-4"><b>Age<span class="star">*</span></b></div>
          <div class="col-md-8">
              <input type="text" name="age_y" id="age_y" class="input-tiny m_tiny numeric"  maxlength="3" value="<?php if($growth_prescription_ipd_data!='empty'){ //echo $growth_prescription_ipd_data['age_y']; 
              } ?>"> Y &nbsp;
              <input type="text" name="age_m" id="age_m"  class="input-tiny m_tiny numeric" maxlength="2" value="<?php if($growth_prescription_ipd_data!='empty'){ 
              } ?>"> M &nbsp;
              <input type="text" name="age_d" id="age_d"  class="input-tiny m_tiny numeric"  maxlength="2" value="<?php if($growth_prescription_data!='empty'){ //echo $growth_prescription_ipd_data['age_d']; 
              } ?>"> D
              <br/><span id="age_error" ></span>
          </div>
          
      </div>
       <div class="row mb-5">
          <div class="col-md-4"><b>Notes</b></div>
           <div class="col-md-8">
              <textarea name="notes" id="notes" ><?php if($growth_prescription_data!="empty") 
              {  echo $growth_prescription_data['notes'];  } ?></textarea>
           </div>
          
        </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="">
                <?php
                 $display='';
                if(!empty($growth_prescription_data['oedema']))
                {
                 
                    if($growth_prescription_data['oedema']=='2')
                    {
                      $display="display:block;";
          

                    }
                    if($growth_prescription_data['oedema']=='1')
                    {
                      $display="display:none;";
                      

                    }
                }
            ?>
        <div class="row mb-5" id="textboxes" style="<?php echo $display ?>">
          <div class="col-md-4"><b>Weight(kg)</b></div>
           <div class="col-md-8">
              <input type="text"  class='price_float' id='weight' value="<?php if($growth_prescription_data!='empty' && $growth_prescription_data['weight'] > 0 ){ echo $growth_prescription_data['weight']; } ?>" name="weight" numeric onKeyUp="myFunction()">
            
           </div>
        </div>
         <div class="row mb-5">
          <div class="col-md-4"><b>Length/Height(cm)<span class="star">*</span></b></div>
           <div class="col-md-8">
              <input type="text" id="height" class='price_float' value="<?php if($growth_prescription_data!='empty'){ echo $growth_prescription_data['height']; } ?>" name="height" numeric onKeyUp="myFunction()">
              <span id="height_error"></span>  

            <b>BMI <span id="bmi_calculate"><?php if($growth_prescription_data!='empty'){?> <?php echo $bmi=number_format($growth_prescription_data['weight'] / ($growth_prescription_data['height'] * $growth_prescription_data['height']),2);}?> </span> </b>
           </div>


        </div>


    <div class="row mb-5">
        <div class="col-md-4"><b>Measured <span class="star">*</span></b></div>
          <div class="col-md-8" id="measured">
              <?php
                $recumbent=""; $standing="";
                if($growth_prescription_data!='empty')
                { 
                    if($growth_prescription_data['measured']==1)
                      $recumbent="checked"; 
                    else if($growth_prescription_data['measured']==2)
                      $standing="checked"; 
                 
                }
                
              ?>
              <input type="radio" id="one" name="measured" value="1" <?php echo $recumbent ?>> Recumbent &nbsp;
              <input type="radio" id="two" name="measured" value="2" <?php echo $standing ?>> Standing
            <span id="measured_error"></span> 
          </div>
      </div>
         <div class="row mb-5">
        <div class="col-md-4"><b>Oedema <span class="star">*</span></b></div>
          <div class="col-md-8" id="oedema">
              <?php
                $yes=""; $no="";
                if($growth_prescription_data!='empty')
                { 
                    if($growth_prescription_data['oedema']==1)
                      $yes="checked"; 
                    else if($growth_prescription_data['oedema']==2)
                      $no="checked"; 
                 
                }
                else
                {
                         $no="checked"; 
                }
              ?>
              <input type="radio" name="oedema" id="one" value="1" <?php echo $yes; ?> > Yes &nbsp;
              <input type="radio" name="oedema" id="two" value="2" <?php echo $no; ?> > No
              <span id="oedema_error"></span> 
          </div>
      </div>
       

<div class="row mb-5">
          <div class="col-md-4"><b>Head Circumference(cm)</b></div>
           <div class="col-md-8">
              <input type="text" name="head_circumference" class='price_float' value="<?php if($growth_prescription_data!='empty' && $growth_prescription_data['head_circumference'] > 0 ){ echo $growth_prescription_data['head_circumference']; } ?>"  numeric>
          
           </div>
        </div>
       
 <div class="row mb-5">
          <div class="col-md-4"><b>MUAC(cm)</b></div>
           <div class="col-md-8">
              <input type="text" class='price_float' value="<?php if($growth_prescription_data!='empty' && $growth_prescription_data['muac'] > 0 ){ echo $growth_prescription_data['muac']; } ?>" name="muac" numeric>
             
           </div>
        </div>   
    </div>
  </div>
  <div class="col-lg-4">
    <div class="">
      <div class="row mb-5">
          <div class="col-md-4"><b>Triceps Skinfold(mm)</b></div>
           <div class="col-md-8">
              <input type="text" class='price_float' value="<?php if($growth_prescription_data!='empty' && $growth_prescription_data['triceps_skinfold'] > 0 ){ echo $growth_prescription_data['triceps_skinfold']; } ?>" name="triceps_skinfold" numeric>
           </div>
        </div>
         <div class="row mb-5">
          <div class="col-md-4"><b>Subscapular Skinfold(mm)</b></div>
           <div class="col-md-8">
              <input type="text" class='price_float' value="<?php if($growth_prescription_data!='empty' && $growth_prescription_data['subscapular_skinfold'] > 0 ){ echo $growth_prescription_data['subscapular_skinfold']; } ?>" name="subscapular_skinfold">
           
           </div>
        </div>
  </div>

      <input type="submit" id="data_handler" name="submit" value="Submit" class="btn-update" onclick="save_growth_prescription();return false;">
      <!--<button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('opd'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>-->
  </div></div>
  </form>
  <div class="col-lg-4"></div> <!-- blank -->
</div>
    
    <!-- //////////// -->
  
         <form>
       <?php if(in_array('1629',$users_data['permission']['action'])) {
       ?>
       <!-- bootstrap data table -->
       <div class="table-responsive">
         <table class="table table-bordered table-striped advice_list" id="table">
            <thead>
                <tr>
                    <th width="40" align="center"> 
                    <input type="checkbox" name="selectall" class="" id="selectAll" value=""> </th> <th>OPD No.</th> 
                    <th>Date of Visit </th> 
                    <th>Patient Name </th> 
                    <th> Weight (kg) </th> 
                    <th> Oedema </th> 
                    <th> Measured</th>
                   
                    <th> Ln/Ht (cm) </th> 
                    <th> MUAC (cm) </th> 
                    <th> HC (cm) </th> 
                    <th> Triceps Skinfold </th>
                    <th> Subscapular Skinfold </th> 
                    <th> Gender </th> 
                    <th> Dob </th>  
                    <th> Age </th> 
                   
                    <th> Status </th>
                    <th> Created Date </th> 
                  <th> Actions </th> 
                </tr>
            </thead>  
        </table>
        </div>
        <?php } ?>
       
    </form>

    </div>
    <div class="userlist-right">
      <div class="btns">
       <!--   <button class="btn-update" id="deleteAll" onclick="return checkboxValues();">
          <i class="fa fa-trash"></i> Delete
        </button>
    -->
      
        <button class="btn-update h-auto" onclick="consolidate_model()"> <i class="fa fa-refresh"></i> Consolidate Print </button>
        
            <button class="btn-update" onclick="reload_table()">
              <i class="fa fa-refresh"></i> Reload
            </button>
         <button class="btn-exit" onclick="window.location.href='<?php echo base_url('opd'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>      </div>
    </div>  <!-- close -->
</section>   </div>
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
function consolidate_model()
{
     $('#table').dataTable();
     var allVals = [];
     $(':checkbox').each(function() 
     {
          if($(this).prop('checked')==true)
          {
              if($(this).val()!='')
              {
                    allVals.push($(this).val());  
              }
              
          }
     });
     
     if(allVals=='')
     {
        alert("Please select atleast a patient prescription."); 
        return false;
     }
          
    window.open('<?php echo base_url('pediatrician/pediatrician_prescription/print_consolidate_prescription?') ?>pres_ids='+allVals,'mywin','width=800,height=600');  
            
            
   
}
function myFunction() 
  {
    var weight= $('#weight').val();
    var height= $('#height').val();
    var newheight= height/100;
    if(weight!='' && newheight!='')
    {

      var bmi=parseFloat(weight/(newheight*newheight)).toFixed(2);
    }
    else
    {
      var bmi='';
    }
    

    $('#bmi_calculate').text(bmi);

  }

$('#selectAll').on('click', function () { 
  if ($(this).hasClass('allChecked')) {
      $('.checklist').prop('checked', false);
  } else {
      $('.checklist').prop('checked', true);
  }
  $(this).toggleClass('allChecked');
});
$(document).ready(function(){
 // alert('<?php echo $growth_prescription_ipd_data['dob']; ?>');
showAge('<?php if(strtotime($growth_prescription_ipd_data['dob'])> 86400){echo date('d-m-Y',strtotime($growth_prescription_ipd_data['dob']));} else{echo '';}?>');
});
function showAge(dob_birth) 
{
 if(dob_birth!='')
 {
   var now = new Date(); //Todays Date   
  var birthday = dob_birth
  birthday=birthday.split("-");   

  var dobMonth= birthday[1]; 
  var dobDay= birthday[0];
  var dobYear= birthday[2];

  var nowDay= now.getDate();
  var nowMonth = now.getMonth() + 1;  //jan=0 so month+1
  var nowYear= now.getFullYear();

  var ageyear = nowYear- dobYear;
  var agemonth = nowMonth - dobMonth;
  var ageday = nowDay- dobDay;
  if (agemonth < 0) {
       ageyear--;
       agemonth = (12 + agemonth);
        }
  if (nowDay< dobDay) {
      agemonth--;
      ageday = 30 + ageday;
      }
  var val = ageyear + "-" + agemonth + "-" + ageday;
  //alert(val);
    $('#age_y').val(ageyear);
    $('#age_m').val(agemonth);
    $('#age_d').val(ageday);
     if(ageyear!='NULL')
       {
         var age = document.getElementById("age_y").value;
        if((age>2)||(age==2))
       {
          $('#two').attr("checked", "checked");
          
      }
     else 
      {
            $('#one').attr("checked", "checked");
           
      }
        
       }
 }
}

$(function() 
{
    $('input[name="measured"]').on('click', function() {
       var age = document.getElementById("age_y").value;
        if(age!='NULL')
          {
            if(age>=2)
            {
              
              if ($(this).val() == '1') 
                  {

                  var height = document.getElementById("height").value;
                  var total=parseFloat(height)-parseFloat('0.7');
                   $("#height").val(total);
                   //alert(total);
                   
                  }
            }
         if(age<2)
          { 
              if ($(this).val() == '2') 
              {
                var height = document.getElementById("height").value;
                var total=parseFloat(height)+parseFloat('0.7');
                $("#height").val(total);
                //alert(total);
              }
          }
        }
    });
});
function edit_growth_type(growth_id)
{
  window.location.href='<?php echo base_url('pediatrician/pediatrician_prescription/add_growth_prescription/'); ?>'+<?php echo $booking_id;?>+'/'+<?php echo $patient_id;?>+'/'+growth_id;
}

function print_growth_type(growth_id)
{
  window.location.href='<?php echo base_url('pediatrician/pediatrician_prescription/print_growth/'); ?>'+<?php echo $patient_id;?>+'/'+growth_id+"/"+<?php echo $booking_id; ?>+'/'+<?php echo $users_data['parent_id'];?>
}
function save_growth_prescription()
{ 
    $.ajax({
              type: "POST",
              dataType:'json',
              url: "<?php echo base_url(); ?>pediatrician/pediatrician_prescription/save_growth_prescription", 
              data: $("#growth_reg_form").serialize(),
              beforeSend: function() {
                $("#data_handler").prop('disabled', true);
               },
              success: function(result)
              {   
                if(result.st==0)
                {

                 $("#age_error").html(result.age_error);
                  $("#date_to_visit_error").html(result.date_to_visit_error);
                  $("#dob_error").html(result.dob_error);
                  $("#height_error").html(result.height_error);
                  $("#measured_error").html(result.measured_error);
                  $("#oedema_error").html(result.oedema_error);
                }
                else if(result.st==1)
                {

                  $("#age_error").html('');
                  $("#date_to_visit_error").html('');
                  $("#dob_error").html('');
                  $("#height_error").html('');
                  $("#measured_error").html('');
                  $("#oedema_error").html('');
                  flash_session_msg(result.msg);
                  setTimeout(function () {
                  window.location.href = "<?php echo base_url(); ?>pediatrician/pediatrician_prescription/add_growth_prescription/"+<?php echo $booking_id;?>;
                  }, 900); 

                  
                }

              }
          });
}


// function to open datepicker


$(function() {
    $('input[name="oedema"]').on('click', function() {
        if ($(this).val() == '2') 
        {
            $('#textboxes').show();
        }
        else 
        {
            $('#textboxes').hide();

        }
    });
});
// Function to open datepicker
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true,
     startView: 2 
  
  });


</script>

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
    </div>
<!---Div to load popups -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!--Div to load popups -->
<div id="confirm_billing_print" class="modal fade dlt-modal">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header bg-theme"><h4>Are You Sure?</h4></div>
    
      <div class="modal-footer">
        <a  data-dismiss="modal" class="btn-anchor"  onClick="return print_window_page('<?php echo base_url("pediatrician/pediatrician_prescription/print_growth/".$patient_id."/".$growth_ids."/".$booking_id."/".$users_data['parent_id']); ?>');">Print</a>

       <button type="button" data-dismiss="modal" class="btn-cancel" id="cancel">Close</button>
      </div>
    </div>
  </div>  
</div>
</body>
</html>
