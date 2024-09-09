<!DOCTYPE html>
<html>

<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users');
$user_data = $this->session->userdata('auth_users');
 ?>
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
 
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>


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

<form id="menu_setting_form">


  <div class="row">
  <div class="col-xs-12 br-h-small m12">
  <div class="row">
    <div class="col-xs-3"><strong>Title</strong></div>
    <div class="col-xs-3 p-l-0"><strong>Variable Name </strong></div>
    <div class="col-xs-3 p-l-0"><strong>Sort Order </strong></div>
    
    <div class="col-xs-3"></div>
  </div>
  </div> <!-- 12 -->
  </div> <!-- row -->


    <?php
      //if(in_array('553',$users_data['permission']['action']))
      //{
        if(!empty($menu_list))
        {
          foreach($menu_list as $menu)
          {

            ?>
              <div class="row">
              <div class="col-xs-12 m-b-5 m12">
                <div class="row">
                  <div class="col-xs-3">
                    <label><?php echo $menu->name; ?></label>
                  </div>
                  <div class="col-xs-3" style="padding-left:7px;">
                  <input class="form-control"  name="data[<?php echo $menu->id; ?>][name]" value="<?php echo $menu->name; ?>"  type="text" data-toggle="tooltip">

                  <input class="form-control"  name="data[<?php echo $menu->id; ?>][parent_id]" value="<?php echo $menu->parent_id; ?>"  type="hidden">

                  <input class="form-control"  name="data[<?php echo $menu->id; ?>][id]" value="<?php echo $menu->id; ?>"  type="hidden">
                  </div>

                  <div class="col-xs-4">
                    <input class="form-control m17" onkeypress="return isNumberKey(event);" name="data[<?php echo $menu->id; ?>][sort_order]" value="<?php echo $menu->sort_order; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                </div>
                  
                </div>
              </div> <!-- 12 -->
              </div> <!-- row -->

              <?php
              $submenu = get_sub_menu($menu->id);
              //print_r($submenu);
              if(!empty($submenu))
              {
                foreach($submenu as $sub_menu)
                {
                  
                  ?>
                    <div class="row">
                    <div class="col-xs-12 m-b-5 m12 col-md-push-1">
                      <div class="row">
                        <div class="col-xs-3">
                          <label><?php echo $sub_menu->name; ?></label>
                        </div>
                        <div class="col-xs-3" style="padding-left:7px;">
                        <input class="form-control"  name="data[<?php echo $sub_menu->id; ?>][name]" value="<?php echo $sub_menu->name; ?>"  type="text" data-toggle="tooltip">
                        <input class="form-control"  name="data[<?php echo $sub_menu->id; ?>][parent_id]" value="<?php echo $sub_menu->parent_id; ?>"  type="hidden">

                        <input class="form-control"  name="data[<?php echo $sub_menu->id; ?>][id]" value="<?php echo $sub_menu->id; ?>"  type="hidden">
                        </div>

                        <div class="col-xs-4">
                        <input class="form-control m17" onkeypress="return isNumberKey(event);" name="data[<?php echo $sub_menu->id; ?>][sort_order]" value="<?php echo $sub_menu->sort_order; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                    </div>
                        
                      </div>
                    </div> <!-- 12 -->
                    </div> <!-- row -->

                    <?php



                    $submenu2 = get_sub_menu($sub_menu->id);
                    //print_r($submenu);
                    if(!empty($submenu2))
                    {
                      foreach($submenu2 as $submenu)
                      {
                        
                        ?>
                          <div class="row">
                          <div class="col-xs-12 m-b-5 m12 col-md-push-2">
                            <div class="row">
                              <div class="col-xs-3">
                                <label><?php echo $submenu->name; ?></label>
                              </div>
                              <div class="col-xs-3" style="padding-left:7px;">
                              <input class="form-control"  name="data[<?php echo $submenu->id; ?>][name]" value="<?php echo $submenu->name; ?>"  type="text" data-toggle="tooltip">
                              <input class="form-control"  name="data[<?php echo $submenu->id; ?>][parent_id]" value="<?php echo $submenu->parent_id; ?>"  type="hidden">

                              <input class="form-control"  name="data[<?php echo $submenu->id; ?>][id]" value="<?php echo $submenu->id; ?>"  type="hidden">
                              </div>

                              <div class="col-xs-4">
                              <input class="form-control m17" onkeypress="return isNumberKey(event);" name="data[<?php echo $submenu->id; ?>][sort_order]" value="<?php echo $submenu->sort_order; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                          </div>
                              
                            </div>
                          </div> <!-- 12 -->
                          </div> <!-- row -->

                          <?php
                          


                          $submenu3 = get_sub_menu($submenu->id);
                    //print_r($submenu);
                    if(!empty($submenu3))
                    {
                      foreach($submenu3 as $submenu_3)
                      {
                        
                        ?>
                          <div class="row">
                          <div class="col-xs-12 m-b-5 m12 col-md-push-3">
                            <div class="row">
                              <div class="col-xs-3">
                                <label><?php echo $submenu_3->name; ?></label>
                              </div>
                              <div class="col-xs-3" style="padding-left:7px;">
                              <input class="form-control"  name="data[<?php echo $submenu_3->id; ?>][name]" value="<?php echo $submenu_3->name; ?>"  type="text" data-toggle="tooltip">
                              <input class="form-control"  name="data[<?php echo $submenu_3->id; ?>][parent_id]" value="<?php echo $submenu_3->parent_id; ?>"  type="hidden">

                              <input class="form-control"  name="data[<?php echo $submenu_3->id; ?>][id]" value="<?php echo $submenu_3->id; ?>"  type="hidden">
                              </div>

                              <div class="col-xs-4">
                              <input class="form-control m17" onkeypress="return isNumberKey(event);" name="data[<?php echo $submenu_3->id; ?>][sort_order]" value="<?php echo $submenu_3->sort_order; ?>" type="text" placeholder="Order By" data-toggle="tooltip"  title="Numeric Only" class="tooltip-text">
                          </div>
                              
                            </div>
                          </div> <!-- 12 -->
                          </div> <!-- row -->

                          <?php
                          
                        }


                      }
                        }


                      }
                    
                  }


                }
              


            }
        }

      ?>

      

      
      <div class="row">
      <div class="col-xs-3"></div>
      <div class="col-xs-9 p-l-0">
        <div style="">
            <button class="btn-update" name="submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
            
           
            <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
        </div>
      </div>
      </div><!-- row -->
    <?php //} ?>
</form>


</div> <!-- close -->
 
 
  <!-- cbranch-rslt close -->

  


  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

$('.tooltip-text').tooltip({
    placement: 'right', 
    container: 'body',
    trigger   : 'focus' 
});
})

</script>
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

                if (window.event) {

                    var charCode = window.event.keyCode;

                }

                else if (e) {

                    var charCode = e.which;

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

        } 
 
$("#menu_setting_form").on("submit", function(event) 
{

 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>menu/",
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
</div>
<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
<!-- container-fluid -->
</body>
</html>