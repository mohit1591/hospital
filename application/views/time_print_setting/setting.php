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
     <form id="address_print_form">


      <div class="row">
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5"><strong>Title</strong></div>
                <div class="col-xs-4"><strong>Print with Time</strong></div>
                
              </div>
        </div>
      </div> <!-- row -->
      <?php
    
           ?>
      <div class="row">
      <input type="hidden" name="data_id" value="<?php if(isset($address_list[0]->id)){ echo $address_list[0]->id; } ?>"/>
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>OPD</strong>
                </div>
                <div class="col-xs-4">
                    <input  name="opd" value="1"  type="checkbox" <?php if(isset($address_list[0]->opd) && !empty($address_list[0]->opd)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>OPD Billing</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="opd_billing" value="2"  type="checkbox" <?php if(isset($address_list[0]->opd_billing) && !empty($address_list[0]->opd_billing)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Prescription</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="prescription" value="3"  type="checkbox" <?php if(isset($address_list[0]->prescription) && !empty($address_list[0]->prescription)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>IPD</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="ipd" value="4"  type="checkbox" <?php if(isset($address_list[0]->ipd) && !empty($address_list[0]->ipd)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>OT</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="ot" value="5"  type="checkbox" <?php if(isset($address_list[0]->ot) && !empty($address_list[0]->ot)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Pathology</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="pathology" value="6"  type="checkbox" <?php if(isset($address_list[0]->pathology) && !empty($address_list[0]->pathology)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Medicine</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="medicine" value="7"  type="checkbox" <?php if(isset($address_list[0]->medicine) && !empty($address_list[0]->medicine)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
          <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Inventory</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="inventory" value="8"  type="checkbox" <?php if(isset($address_list[0]->inventory) && !empty($address_list[0]->inventory)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
          <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Blood Bank</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="blood_bank" value="9"  type="checkbox" <?php if(isset($address_list[0]->blood_bank) && !empty($address_list[0]->blood_bank)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
          <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Ambulance</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="ambulance" value="10"  type="checkbox" <?php if(isset($address_list[0]->ambulance) && !empty($address_list[0]->ambulance)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Dialysis</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="dialysis" value="11"  type="checkbox" <?php if(isset($address_list[0]->dialysis) && !empty($address_list[0]->dialysis)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
       
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Day Care</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="daycare" value="12"  type="checkbox" <?php if(isset($address_list[0]->daycare) && !empty($address_list[0]->daycare)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        
        
        
        
        
        
        
      </div> <!-- row -->

      <div class="row">
        <div class="col-xs-7">
              <div class="row">
                <div class="col-xs-5"></div>
               
                <div class="col-xs-4">
                    <button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
                    <input class="btn-cancel" name="cancel" value="Exit" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
                </div>
                <div class="col-xs-3"></div>
              </div>
        </div>
      </div> <!-- row -->
      
        </form>


   </div> <!-- close -->
 
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  
$("#address_print_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>time_print_setting/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
       flash_session_msg(result);    
       $('.overlay-loader').hide();    
    }
  });
});

</script>   
</div> <!-- container_fluid -->
</body>
</html>