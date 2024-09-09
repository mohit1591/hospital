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
     <form id="unique_id_form" name="frm">


      <div class="row">
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-4"><strong>Title</strong></div>
                <div class="col-xs-4"><strong>Prefix</strong></div>
                <div class="col-xs-4"><strong>Start Part</strong></div>
              </div>
        </div>
      </div> <!-- row -->
      <?php
        if(!empty($unique_list))
        {
          foreach($unique_list as $unique)
          {
        ?>
      <div class="row">
        <div class="col-xs-7 br-h-small">
              <div class="row">
                <div class="col-xs-4">
                  <strong><?php echo $unique->title; ?></strong>
                </div>
                <div class="col-xs-4">
                    <input class="form-control text-uppercase"  name="data[<?php echo $unique->id; ?>][prefix]" value="<?php echo $unique->prefix; ?>"  onkeyup="this.value=this.value.replace(/[^a-z]/g,'');" type="text" data-toggle="tooltip"  title="Allow only characters." class="tooltip-text" placeholder="Example SARAB">
                </div>
                <div class="col-xs-4">
                    <input class="form-control media5" onkeypress="return isNumberKey(event);" name="data[<?php echo $unique->id; ?>][start_num]" value="<?php echo $unique->start_num; ?>" type="text" placeholder="Example 0001" data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text">
                </div>
              </div>
        </div>
      </div> <!-- row -->
      
        <?php
            }
          }
         ?> 
      <div class="row">
        <div class="col-xs-7">
              <div class="row">
                <div class="col-xs-4"></div>
                <div class="col-xs-8">
                    <button class="btn-update" name="submit" value="Save" type="submit"> <i class="fa fa-floppy-o"></i> Save</button>
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

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}


 /*function onlyAlphabets(e, t) {

            try {

                if (window.event) {

                    var charCode = window.event.keyCode;

                }

                else if (e) {

                    var charCode = e.which;
                   // alert(charCode);

                }

                else { return true; }

                if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123))
                    
                    return true;

                else
                    
                    return false;

            }

            catch (err) {

                alert(err.Description);

            }

        } */

$("#unique_id_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>unique_ids/",
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
</div><!-- container-fluid -->
</body>
</html>