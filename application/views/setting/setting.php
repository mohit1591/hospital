<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<meta name="viewport" content="width=1024">


<!---bootstrap---->
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
     <form id="unique_id_form">

     <div class="row">
       <div class="col-xs-4 br-h-small">
            <div class="row">
                <div class="col-xs-4"><strong>Title</strong></div>  
                <div class="col-xs-8"><input type="text" name=""></div>  
            </div> <!-- row -->
       </div>
       <div class="col-xs-4"></div> <!-- blank -->
     </div> <!-- mainrow -->

     <div class="row">
       <div class="col-xs-4 br-h-small">
            <div class="row">
                <div class="col-xs-4"><strong>Title</strong></div>  
                <div class="col-xs-8"><input type="text" name=""></div>  
            </div> <!-- row -->
       </div>
       <div class="col-xs-4"></div> <!-- blank -->
     </div> <!-- mainrow -->

     <div class="row">
       <div class="col-xs-4 br-h-small">
            <div class="row">
                <div class="col-xs-4"><strong>Title</strong></div>  
                <div class="col-xs-8"><input type="text" name=""></div>  
            </div> <!-- row -->
       </div>
       <div class="col-xs-4"></div> <!-- blank -->
     </div> <!-- mainrow -->

     <div class="row">
       <div class="col-xs-4 br-h-small">
            <div class="row">
                <div class="col-xs-4"><strong>Title</strong></div>  
                <div class="col-xs-8"><input type="text" name=""></div>  
            </div> <!-- row -->
       </div>
       <div class="col-xs-4"></div> <!-- blank -->
     </div> <!-- mainrow -->
     





      
        
      <div class="row">
        <div class="col-xs-4 media2">
              <div class="row">
                <div class="col-sm-4"></div>
                <div class="col-sm-8 media3">
                    <input class="btn-update" name="submit" value="Save" type="submit">
                    <input class="btn-cancel" name="cancel" value="Close" type="button">
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
</div><!----container-fluid--->
</body>
</html>