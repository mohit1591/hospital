<?php
$users_data = $this->session->userdata('auth_users');
 if(isset($examination_id) && $examination_id!='')
  {
    $examination_id=$examination_id;
  }
  else
  {
    $examination_id=$this->session->userdata('sess_examin_id');
  }

  if(isset($blood_detail_id) && $blood_detail_id!='')
  {
    $blood_detail_id=$blood_detail_id;
  }
  else
  {
    $blood_detail_id=$this->session->userdata('sess_blood_detail_id');
  }


  if(isset($qc_id) && $qc_id!='')
  {
    $qc_id=$qc_id;
  }
  else
  {
    $qc_id=$this->session->userdata('sess_qc_rec_id');
  }

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
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  

 
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.min.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>locales/bootstrap-datetimepicker.fr.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>

<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>





<!--
<script src="<?php //echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>

<link href = "<?php //echo ROOT_CSS_PATH; ?>jquery-ui.css"
    rel = "stylesheet">
    <script src = "<?php //echo ROOT_JS_PATH; ?>jquery-ui.js"></script>-->
    
    <body onload="tab_navigation('examination_details','<?php echo $examination_id; ?>','');">


<div class="container-fluid">

 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>

<?php //print_r($donor_data); ?>

<!-- ======================= Main content start here ========================== -->
<section class="userlist">
<div class="userlist-box">
<div class="col-md-12">
    <div class="row mb-5"><b>Donor Details</b></div>
 <div class="">
  <div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Donor ID</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $donor_data['donor_code']; ?>" >
               
                <input type="hidden"  class="m_input_default" value="<?php echo $donor_data['donor_code']; ?>" >
                <span id=""></span>
              </div>
            </div>
        </div>   <!-- col-4 -->

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Donor Name</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $donor_data['simulation_donor']." ".$donor_data['donor_name']; ?>" >
               
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Gender</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $donor_data['donor_gender']; ?>" >
               
              
              </div>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Age</b></div>
              <div class="col-md-7">
              <?php
                $data= hms_patient_age_calulator($donor_data['age_y'],$donor_data['age_m'],$donor_data['age_d']);
                ?>
               <input type="text" readonly class="m_input_default" value="<?php echo $data; ?>" >
               
               
                <span id=""></span>
              </div>
            </div>
        </div>   <!-- col-4 -->

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Blood Group</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo ($donor_data['blood_group_id']>0 ? $donor_data['blood_group'] : ' ') ; ?>" >
               
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Mobile No.</b></div>
              <div class="col-md-7">
               <?php 
               if(!empty($donor_data['mobile_no']))
        {
          $mobile_no=$donor_data['mobile_no'];
        }
        else
        {
          $mobile_no='No';
        }
        ?>
               <input type="text" readonly class="m_input_default" value="<?php echo $mobile_no; ?>" >
               
              
              </div>
            </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Previous Donation Date</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo (strtotime($donor_data['previous_donation_date']) > 0 ? date('d-m-Y',strtotime($donor_data['previous_donation_date'])) : '');  ?>" >
               
                
             
              </div>
            </div>
        </div>   <!-- col-4 -->

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Reminder Service</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $donor_data['preferred_reminder_service']; ?>" >
               
              </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="row mb-5">
              <div class="col-md-5"><b>Remarks</b></div>
              <div class="col-md-7">
               <input type="text" readonly class="m_input_default" value="<?php echo $donor_data['remark']; ?>" >
               
              
              </div>
            </div>
        </div>
      </div>
      <!-- /////////////////// -->
      
</div>      
</div>

<input type="hidden" name="donor_id" id="donor_id" value="<?php echo $donor_data['id']; ?>">

<input type="hidden" name="blood_detail_id" id="blood_detail_id" value="<?php echo $blood_detail_id; ?>">
<input type="hidden" name="qc_id" id="qc_id" value="<?php echo $qc_id; ?>">
<input type="hidden" name="component_id" id="component_id" value="<?php echo $component_id; ?>">
<input type="hidden" name="examination_id" id="examination_id" value="<?php echo $examination_id; ?>">
  <div class="col-md-11 content">
    <div class="tabs">
        <ul>
          <li class="t1 tabing active "><a onclick="tab_navigation('examination_details','<?php echo $examination_id; ?>',this);" href="javascript:void(0);">Examination Details</a></li>
          
          <?php if($examination_id > 0 ){ 
              if($examination_data!="empty" && $examination_data['outcome']==1) {
            ?>
          <li class="t2 tabing" id="bd"><a onclick="tab_navigation('blood_details','<?php echo $blood_detail_id; ?>',this);" href="javascript:void(0);">Blood details</a></li>
          <?php  } else { ?>
            <li class="t2 tabing"><a  href="javascript:void(0);">Blood details</a></li>  
          <?php  } }  else { ?>
          <li class="t2 tabing"><a  href="javascript:void(0);">Blood details</a></li>  
          <?php } ?>

          <?php if($blood_detail_id > 0 && $examination_data['outcome']==1){ ?>
        <li class="t3 tabing"><a onclick="tab_navigation('component_details','0',this);" href="javascript:void(0);">Component Extraction</a></li>
          <?php } else { ?>
          <li class="t3 tabing"><a href="javascript:void(0);">Component Extraction</a></li>
          <?php } ?>
      
          <?php

          if($blood_detail_id > 0 && $examination_data['outcome']==1){ ?>
         

             <li class="t4 tabing"><a onclick="tab_navigation('bag_qc','<?php echo $qc_id; ?>',this);" href="javascript:void(0);">Bag QC</a></li>
          <?php } else {?>
       <li class="t4 tabing"><a href="javascript:void(0);">Bag QC</a></li>
          <?php } ?>
        </ul>
    </div> <!-- tabs -->
    
    <div id="overlay-loader" class="overlay-loader">
    <img class="aj-loader" src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif">
      </div>
    <div class="tab_html" id="inner_html">
        
    </div>
  </div>   <!-- content -->
</div>
 <!--  -->
  </section> <!-- cbranch -->
</div>

<?php $this->load->view('include/footer'); ?>
<script type="text/javascript">

function tab_navigation(parameter,rec_id,ref)
{

  $('.tabing').removeClass('active');
  if(ref=="")
    $(".t1").addClass('active');
  else  
    $(ref).parent('li').addClass('active');  
  $('#overlay-loader').show();


  if(parameter=="examination_details")
  {
    $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_examination_form/');?>"+rec_id,
            data: {'donor_id': $("#donor_id").val()},
            success: function(result) 
            {
              if(result)
              {
                
                $('#overlay-loader').hide();
                $("#inner_html").html(result);
              }
            }
          });
  }
  else if(parameter=="blood_details")
  {
    $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_blood_details_form/');?>"+rec_id,
            data: {'donor_id': $("#donor_id").val(), 'examination_id':$("#examination_id").val(),'blood_detail_id':$("#blood_detail_id").val(),},
            success: function(result) 
            {
              if(result)
              {
                $('#overlay-loader').hide();
                $("#inner_html").html(result);
              }
            }
          });
  }
  else if(parameter=="component_details")
  {
    $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_component_details_form/');?>"+rec_id,
            data: {'donor_id': $("#donor_id").val(),'blood_detail_id':'<?php echo $blood_detail_id; ?>'},
            success: function(result) 
            {
              if(result)
              {
                $('#overlay-loader').hide();
                $("#inner_html").html(result);
              }
            }
          });
  }
  else if(parameter=="bag_qc")
  {
    $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/get_bag_qc_form/');?>"+rec_id,
            data: {'donor_id': $("#donor_id").val()},
            success: function(result) 
            {
              if(result)
              {
                $('#overlay-loader').hide();
                $("#inner_html").html(result);
              }
            }
          });
  }
}

</script>


<!---Div to load popups -->
<div id="load_add_simulation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!--Div to load popups -->

<style type="text/css">
  
  .tabs {
    border-bottom: 2px solid #0e854f;
    display: flex;
    margin-bottom: 10px;
}
.tabs ul {
    display: inline-flex;
    float: left;
    height: 22px;
    list-style: outside none none;
    margin: 0;
    padding: 0;
}
.tabs ul > li {
    display: inline-block;
    float: left;
}
.tabs ul > li > a {
    background: #0e854f none repeat scroll 0 0;
    border-radius: 5px 15px 0 0;
    border-right: 3px ridge #000;
    color: #000;
    cursor: pointer;
    font-weight: 600;
    padding: 6px 1em;
    text-decoration: none;
    text-transform: capitalize;
}
.tabs ul > li.active a {
    background: #50bc8b none repeat scroll 0 0;
    color: #fff;
}
</style>

</body>
</html>
