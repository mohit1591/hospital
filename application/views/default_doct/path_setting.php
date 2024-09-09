<?php
$users_data = $this->session->userdata('auth_users');
//print_r($receipt_list[0]);die();
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
 



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
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
     <form id="receipt_no_form">
      <?php
    
           ?>
      <div class="row">
      <input type="hidden" name="data_id" value="<?php if(isset($receipt_list[0]->id)){echo $receipt_list[0]->id;} ?>"/>
        <div class="col-xs-7 br-h-small">
          <div class="row">

           <div class="col-md-3"><b>Specialization</b>
         </div>
         <div class="col-md-5" id="specilizationid">
           <select name="specialization" class="w-150px m_select_btn" id="specilization_id" onChange="return get_doctor_specilization(this.value);">
            <option value="">Select Specialization</option>
            <?php
            if(!empty($specialization_list))
            {
              foreach($specialization_list as $specializationlist)
              {
                ?>
                <option <?php if($receipt_list[0]->specialize_id==$specializationlist->id){ echo 'selected="selected"'; } ?> value="<?php echo $specializationlist->id; ?>"><?php echo $specializationlist->specialization; ?></option>
                <?php
              }
            }
            ?>
          </select> 
      </div>
    </div>
<?php $doctor_list = doctor_specilization_list($receipt_list[0]->specialize_id, $receipt_list[0]->branch_id);   ?>

    <div class="row">
     <div class="col-md-3"><b>Consultant</b>
       <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>This is a doctor type which have two forms one is attended other is referral it may be both. </span></a></sup></div>
       <div class="col-md-5">
         <select name="attended_doctor" class="w-150px m_select_btn" id="attended_doctor" >
          <option value="">Select Consultant</option>
          <?php
          if(!empty($receipt_list[0]->specialize_id))
          {            
            if(!empty($doctor_list))
            {
             foreach($doctor_list as $doctor)
                   { 
                    ?>   
                    <option value="<?php echo $doctor->id; ?>" <?php if(!empty($receipt_list[0]->doctor_id) && $receipt_list[0]->doctor_id === $doctor->id){ echo 'selected="selected"'; } ?>><?php echo $doctor->doctor_name; ?></option>
                    <?php
                     //}
                  }
                }
              }
              ?>
            </select>
          </div>
        </div>

            <div class="row mt-5">
                <div class="col-xs-3"></div>
                <div class="col-xs-8">
                    <button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
                    <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
                </div>
              </div>




  </div>
</div> <!-- row -->

   </form>


   </div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  
function get_doctor_specilization(specilization_id,branch_id)
{   

    if(typeof branch_id === "undefined" || branch_id === null)
    {
        $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
    }
    else
    {

      $.ajax({url: "<?php echo base_url(); ?>general/doctor_specilization_list/"+specilization_id+"/"+branch_id, 
      success: function(result)
      {
        $('#attended_doctor').html(result); 
      } 
    });
   }
}
 
$("#receipt_no_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>default_path_doc_setting/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
       flash_session_msg(result);    
       $('.overlay-loader').hide();    
    }
  });
});

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
</script>   
</div> <!-- container_fluid -->
</body>
</html>