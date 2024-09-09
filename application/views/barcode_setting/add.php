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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>script.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>custom.js"></script>

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
     <form id="barcode_form">


     
      
          <div class="row m-b-5">
              <div class="col-xs-3">
                  <strong>Total Receipt</strong> <!-- text-uppercase -->
              </div>
              <div class="col-xs-9">
                  <input type="text" name="total_receipt" value="<?php echo $total_receipt; ?>">
              </div>
          </div>
          
          <div class="row m-b-5">
            <div class="col-xs-3">
             <strong>Barcode Type</strong> <!-- text-uppercase -->
            </div>
            <div class="col-xs-9">
                <select name="type" >
                  <option <?php if($type=='horizontal'){ echo 'selected="selected"';} ?> value="horizontal">Horizontal</option>
                  <option <?php if($type=='vertical'){ echo 'selected="selected"';} ?>  value="vertical">Vertical</option>
              </select>
               
            </div>
          </div>
          
          <div class="row m-b-5">
            <div class="col-xs-3">
              <strong>Size</strong> <!-- text-uppercase -->
            </div>
            <div class="col-xs-9">
              <input type="text" name="size" value="<?php echo $size; ?>">
              <input name="id" value="<?php echo $id; ?>" type="hidden">
            </div>
          </div> <!-- row -->
      
        
          <div class="row">
            <div class="col-xs-3"></div>
            <div class="col-xs-9">
              <button class="btn-update" name="submit" value="Save" type="submit"><i class="fa fa-floppy-o"></i>  Save</button>
              <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
            </div>
          </div>
      
        </form>


</div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>  

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

 function onlyAlphabets(e, t) {

      try {

          if (window.event) 
          {
            var charCode = window.event.keyCode;
          }
          else if (e) 
          {
            var charCode = e.which;
          }
          else 
          { return true; }

          if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))

              return true;

          else

              return false;

      }

      catch (err) {

          alert(err.Description);

      }

  } 
 
$("#barcode_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>barcode_setting/",
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
</div><!----container-fluid--->
</body>
</html>