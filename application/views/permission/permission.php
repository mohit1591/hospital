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

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>  
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 ?>
<!-- ============================= Main content start here ===================================== -->
<form id="permission_form">

<section class="userlist">
    
    <div class="userlist-box"> 
    <div class="overlay-loader-full" style="width:95%;background:transparent;">
       <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
    	 <div class="row">
        <div class="col-xs-4 br-h media1">  
          <div class="col-xs-4"><strong>Users Role</strong></div>  
          <div class="col-xs-8">
               <select name="user_role" id="user_role">
                    <option value="">Select Users Role</option> 
                    <?php
                      $user_list = users_role_list();
                      foreach($user_list as $user)
                      {
                         echo '<option value="'.$user->id.'">'.$user->role.'</option>';
                      }
                    ?>
               </select> 
          </div>  
       </div>
      </div> 
      <div id="permission_section">

      
      </div>
 

   </div> <!-- userlist-box -->
 
</section> <!-- userlist -->
</form>
<?php
$this->load->view('include/footer');
?>
<script type="text/javascript">
   
$("#permission_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('.overlay-loader-full').show();   
  $.ajax({
    url: "<?php echo base_url(); ?>permission/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      $('.overlay-loader-full').hide(); 
      flash_session_msg(result);      
    }
  });
}); 

$('#user_role').change(function(){
  var user_role_id = $(this).val();
  $('.overlay-loader-full').show();
    $.ajax({
            url: "<?php echo base_url(); ?>permission/permission_section/"+user_role_id,
            type: "post",
            data: $(this).serialize(),
            success: function(result) 
            { 
              $('#permission_section').html(result);
              $('.overlay-loader-full').hide();       
            }
        });
}); 



</script>


</div><!-- container-fluid -->
</body>
</html>