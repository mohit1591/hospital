<!DOCTYPE html>
<?php //print_r($result);die; ?>
<html>
<head>
  
  <title>Hospital Management Advanced Software</title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
  <link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
  <link rel="stylesheet" href="<?php echo ROOT_CSS_PATH; ?>hmas.css">
  

  <!-- <script src="js/jquery.min.js"></script> -->
  <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
  <script src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script>
  
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 header-box">
      <div class="container">
        <div class="row">
          <div class="col-md-4 ">
            <a href="<?php echo base_url('login'); ?>"><img src="<?php echo ROOT_IMAGES_PATH; ?>logo.png" alt="logo" width="100" ></a>
          </div>
          <div class="col-md-8">
            <p class="header-text"><a href="mailto:sales@sarasolutions.in"><i class="fa fa-envelope-o"></i> sales@sarasolutions.in</a>&nbsp;&nbsp; &nbsp;<i class="fa fa-phone"></i> 8506080373 </p>
          </div>
          
        </div>
      </div>
    </div>
  </div>
  <div class="row banner">
    <div class="col-md-12 banner-cover">
      
      <h1 class="text-center text">UNDER CONSTRUCTION</h1>
      <h4 class="text-center text">We are working hard to bring to you our great project very soon.</h4>
    </div>
  </div>
  
  <div class="row ">
  
    <div class="col-md-12 main-content">
     <?php  if(isset($result) && $result[0]->msg!='')
        {?>
      <div class="inner-msg-box">
       <h4 class="text-center" style="color:#df8000;"><?php echo $result[0]->msg;?>.</h4>
        <p></p>
      </div>
      <?php }?>
    </div>
    
  </div>
  
  <div class="row">
    <div class="col-md-12 social-icon">
      
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12 copy-box">
      <span>
      <a href="https://www.facebook.com/saratechnologies" target="_blank">
      <img src="<?php echo ROOT_IMAGES_PATH; ?>fb.png"> </a>
     <a href="https://plus.google.com/+SarasolutionsIn" target="_blank">
      <img src="<?php echo ROOT_IMAGES_PATH; ?>gplus.png">
      </a>
      <a href="https://www.linkedin.com/company/sara-technologies-pvt-ltd" target="_blank">
      <img src="<?php echo ROOT_IMAGES_PATH; ?>lin.png">
      </a>
      <a href="https://twitter.com/saratechnology" target="_blank">
      <img src="<?php echo ROOT_IMAGES_PATH; ?>twitter.png">
      </a>

      </span>
      <p>Copyrights &copy; Sara Technologies Pvt. Ltd, All rights Reserved.</p>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
</div> <!-- container-fluid -->
</body>
</html>