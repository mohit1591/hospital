<!DOCTYPE html>
<html>

<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  $users_data = $this->session->userdata('auth_users');
$user_data = $this->session->userdata('auth_users');

if (array_key_exists("permission",$users_data)){
     $permission_section = $users_data['permission']['section'];
     $permission_action = $users_data['permission']['action'];

    
    
}
else{
     $permission_section = array();
     $permission_action = array();
    
   }
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
  
  <table class="table table-bordered table-striped menu_tbl">
    <thead>
      <tr>
        <th>Title</th>
        <th>Variable Name</th>
        <th>Sort Order</th>
      </tr>
    </thead>
    <tbody>
    <?php
        
        if(!empty($menu_list))
        {
          foreach($menu_list as $menu)
          {
            $submenu = get_sub_menu($menu->id);

            $menu_permission_1 = explode('|', $menu->section_id);
             //if(count(array_intersect(array_values($permission_section),$menu_permission_1)) == count($menu_permission_1))
            //{
            $active_child_li_1 = array_intersect(array_values($permission_section),$menu_permission_1);
            $total_active_menu_1=1;
            if(count($active_child_li_1)!='0')
            {
              $total_active_menu_1  = count($active_child_li_1);  
            }
            if(count(array_intersect(array_values($permission_section),$menu_permission_1)) == $total_active_menu_1)
            {

            ?>
      <tr>
        <td align="left"><i class="fa <?php if(!empty($submenu)){ ?> fa-folder-open-o <?php } else{ ?> fa-folder-o <?php } ?> "></i> <?php echo $menu->name; ?></td>
        <td><input class=""  name="data[<?php echo $menu->id; ?>][name]" value="<?php echo $menu->name; ?>"  type="text">

        <input class=""  name="data[<?php echo $menu->id; ?>][parent_id]" value="<?php echo $menu->parent_id; ?>"  type="hidden">

        <input class=""  name="data[<?php echo $menu->id; ?>][id]" value="<?php echo $menu->id; ?>"  type="hidden">
        </td>
        <td><input class="" name="data[<?php echo $menu->id; ?>][sort_order]" value="<?php echo $menu->sort_order; ?>" type="text" placeholder="Order By"></td>
      </tr>
      
      <?php
          
          //print_r($submenu);
          if(!empty($submenu))
          {
            ?>
            <tr>
            <td></td>
            <td colspan="2">
              <table class="table table-bordered table-striped menu_tbl2">
            <?php 
            foreach($submenu as $sub_menu)
            {
              $submenu2 = get_sub_menu($sub_menu->id);
              $menu_permission_2 = explode('|', $sub_menu->section_id);

              //$menu_permission_2 = explode('|', $sub_menu->section_id);
                //echo count(array_intersect(array_values($permission_section),$menu_permission_2));
                $active_child_li_2 = array_intersect(array_values($permission_section),$menu_permission_2);
                $total_active_menu_2=1;
                if(count($active_child_li_2)!='0')
                {
                  $total_active_menu_2  = count($active_child_li_2);  
                }
                if(count(array_intersect(array_values($permission_section),$menu_permission_2)) == $total_active_menu_2)
                { 
              //if(count(array_intersect(array_values($permission_section),$menu_permission_2)) == count($menu_permission_2))
             // {
            ?>
                <tr>
                <td><i class="fa <?php if(!empty($submenu2)){ ?> fa-folder-open-o <?php } else{ ?> fa-folder-o <?php } ?>"></i> <?php echo $sub_menu->name; ?></td>
                <td>
                <input class=""  name="data[<?php echo $sub_menu->id; ?>][name]" value="<?php echo $sub_menu->name; ?>"  type="text">
                <input class=""  name="data[<?php echo $sub_menu->id; ?>][parent_id]" value="<?php echo $sub_menu->parent_id; ?>"  type="hidden">
                <input class=""  name="data[<?php echo $sub_menu->id; ?>][id]" value="<?php echo $sub_menu->id; ?>"  type="hidden"></td>
                <td><input class="" onkeypress="return isNumberKey(event);" name="data[<?php echo $sub_menu->id; ?>][sort_order]" value="<?php echo $sub_menu->sort_order; ?>" type="text" placeholder="Order By"></td>
                </tr>
            

            <?php
              
              //print_r($submenu);
              if(!empty($submenu2))
              {
                ?>
                <tr>
               <td></td>
               <td colspan="2">
                   <table class="table table-bordered table-striped menu_tbl3">
                <?php 
                foreach($submenu2 as $submenu)
                {
                  $submenu4 = get_sub_menu($submenu->id);
                  $menu_permission_3 = explode('|', $submenu->section_id);

                  $active_child_li_3 = array_intersect(array_values($permission_section),$menu_permission_3);
                  $total_active_menu_3=1;
                  if(count($active_child_li_3)!='0')
                  {
                    $total_active_menu_3  = count($active_child_li_3);  
                  }
                  
                  if(count(array_intersect(array_values($permission_section),$menu_permission_3)) == $total_active_menu_3)
                  {
                  //if(count(array_intersect(array_values($permission_section),$menu_permission_3)) == count($menu_permission_3))
                  //{
                    //print_r($submenu4);
                    ?>
                    <tr>
                       <td><i class="fa <?php if(!empty($submenu4)){ ?> fa-folder-open-o <?php } else{ ?> fa-folder-o <?php } ?>"></i> <?php echo $submenu->name; ?></td>
                       <td><input class=""  name="data[<?php echo $submenu->id; ?>][name]" value="<?php echo $submenu->name; ?>"  type="text" >
                      <input class="form-control"  name="data[<?php echo $submenu->id; ?>][parent_id]" value="<?php echo $submenu->parent_id; ?>"  type="hidden">

                      <input class=""  name="data[<?php echo $submenu->id; ?>][id]" value="<?php echo $submenu->id; ?>"  type="hidden">
                       </td>
                       <td><input class="" onkeypress="return isNumberKey(event);" name="data[<?php echo $submenu->id; ?>][sort_order]" value="<?php echo $submenu->sort_order; ?>" type="text" placeholder="Order By"></td>
                     </tr>
                    <?php

                    if(!empty($submenu4))
                    {

                      ?>
                      <tr>
                           <td></td>
                           <td colspan="2">
                              <table class="table table-bordered table-striped menu_tbl4">
                      <?php
                      foreach ($submenu4 as $submenu_4) 
                      {

                        $menu_permission_4 = explode('|', $submenu_4->section_id);

                        $active_child_li_4 = array_intersect(array_values($permission_section),$menu_permission_4);
                        $total_active_menu_4=1;
                        if(count($active_child_li_4)!='0')
                        {
                          $total_active_menu_4  = count($active_child_li_4);  
                        }

                        if(count(array_intersect(array_values($permission_section),$menu_permission_4)) == $total_active_menu_4)
                        {
                        //if(count(array_intersect(array_values($permission_section),$menu_permission_4)) == count($menu_permission_4))
                        //{
                        ?>

                        <tr>
                            <td><i class="fa fa-folder-o"></i> <?php echo $submenu_4->name; ?></td>
                            <td><input class=""  name="data[<?php echo $submenu_4->id; ?>][name]" value="<?php echo $submenu_4->name; ?>"  type="text" >
                      <input class="form-control"  name="data[<?php echo $submenu_4->id; ?>][parent_id]" value="<?php echo $submenu_4->parent_id; ?>"  type="hidden">

                      <input class=""  name="data[<?php echo $submenu_4->id; ?>][id]" value="<?php echo $submenu_4->id; ?>"  type="hidden"></td>
                            <td><input class="" onkeypress="return isNumberKey(event);" name="data[<?php echo $submenu_4->id; ?>][sort_order]" value="<?php echo $submenu_4->sort_order; ?>" type="text" placeholder="Order By"></td>
                         </tr> 
                        <?php 
                      }
                      }
                      ?>
                      </table>
                      </td>
                      </tr>
                      <?php 
                    }
                  }

                }
                ?>  
                </table>
                </td>
                </tr>

                <?php 
              }  

            ?>
            <?php 
          }
            }
            ?>
            </table>
            </td>
            </tr>
            <?php 
          }
            ?>

      <?php 
          }  
        }
}
      ?>
    
     </tbody>
  </table>
</div>


      <div class="userlist-right relative">
      <div class="fixed">
      <button class="btn-update" name="submit" type="submit"><i class="fa fa-floppy-o"></i> Save</button>


      <input class="btn-cancel" name="cancel" value="Close" type="button" onclick="window.location.href='<?php echo base_url(); ?>'">
      </div>
      </div>
        
</form>



 
 
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