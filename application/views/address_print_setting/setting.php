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
                <div class="col-xs-4"><strong>Print</strong></div>
                
              </div>
        </div>
      </div> <!-- row -->
      <?php
    
           ?>
      <div class="row">
      <input type="hidden" name="data_id" value="<?php if(isset($address_list[0]->id)){echo $address_list[0]->id;} ?>"/>
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Address1</strong>
                </div>
                <div class="col-xs-4">
                    <input  name="address1" value="1"  type="checkbox" <?php if(isset($address_list[0]->address1)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Address2</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="address2" value="2"  type="checkbox" <?php if(isset($address_list[0]->address2)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Address3</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="address3" value="3"  type="checkbox" <?php if(isset($address_list[0]->address3)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Country</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="country" value="4"  type="checkbox" <?php if(isset($address_list[0]->country)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>State</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="state" value="5"  type="checkbox" <?php if(isset($address_list[0]->state)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
               <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>City</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="city" value="6"  type="checkbox" <?php if(isset($address_list[0]->city)) {echo 'checked';}?>>
                </div>
                
              </div>
        </div>
        
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-3"></div>
                <div class="col-xs-5">
                  <strong>Pincode</strong>
                </div>
                <div class="col-xs-4">
                    <input   name="pincode" value="7"  type="checkbox" <?php if(isset($address_list[0]->pincode)) {echo 'checked';}?>>
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
    url: "<?php echo base_url(); ?>address_print_setting/",
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