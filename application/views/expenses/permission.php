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

<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>  
<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
  $users_data = $this->session->userdata('auth_users');
 ?>
<!-- ============================= Main content start here ===================================== -->
<form id="permission_form">
<input type="hidden" value="<?php echo $branch_id; ?>" name="bid" />
<section class="userlist">
    
    <div class="userlist-box">  
      <div id="permission_section">
           <div class="permission">
           <div class="pheader">
                   <div class="p-1"><strong>Section</strong></div>
                   <div class="p-2"><strong>Permission Action</strong></div>
                   <div class="p-3"><strong>Permission Status</strong>
                     <input type="radio" name="all_radio" id="all_active" value="1" /> All Active
                      <input type="radio" name="all_radio" id="all_inactive" value="0" /> All Active
                   </div>
                   <div class="p-4"><strong>Attribute</strong></div>
           </div> <!-- pheader -->
          <?php
          if(!empty($section_list))
          {
             foreach($section_list as $section)
             {
              ?>
                <div class="pbody">
                   <div class="p-1"><strong><?php echo $section->section; ?></strong></div> <!-- p-1 -->
                   <div class="p-2">
                   <?php
                   $action_list = branch_permission_action_list($section->id,$branch_id);
                   if(!empty($action_list))
                   {
                     foreach($action_list as $action)
                     {
                      ?>
                        <div class="action"><?php echo $action->action; ?></div> 
                      <?php
                     }
                   }
                   else
                   {
                     echo '<div class="action text-danger">N/A</div>';
                   }
                   ?> 
                   </div> <!-- p-2 --> 
                   <div class="p-3">  
                   <?php
                    if(!empty($action_list))
                   { 
                     foreach($action_list as $action)
                     {   
                      ?>    
                            <div class="per">
                                <span><input type="radio" class="active" name="active[<?php echo $action->id; ?>]" value="<?php echo $section->id.'-'.$action->id; ?>-1" <?php if($action->user_permission_status==1){ echo 'checked=""'; } ?> /> Active</span> 
                                <span><input type="radio" class="inactive" name="active[<?php echo $action->id; ?>]"  value="<?php echo $section->id.'-'.$action->id; ?>-2" <?php if($action->user_permission_status!=1){ echo 'checked=""'; } ?>> Inactive</span> 
                            </div> 
                          
                      <?php
                     }
                   }
                   else
                   {
                    echo '<div class="action  text-danger">N/A</div>';
                   }
                   ?>  
                   </div> <!-- p-3 -->

                   <div class="p-4">  
                   <?php
                    if(!empty($action_list))
                   {
                     foreach($action_list as $action)
                     {   
                        if($action->attribute==1)
                        {  
                           ?> 
                            <div class="per">
                              <?php 
                              if($users_data['users_role']==1)
                              {
                                ?>
                                <input type="text"  name="attribute_val-<?php echo $action->id; ?>" id="attribute_val-<?php echo $action->id; ?>" value="<?php echo $action->attribute_val; ?>"   />
                                <?php 
                              }
                              else
                              {
                                if(!empty($action->attribute_val) && $action->attribute_val>0)
                                {
                                  if($section->id==1 && $action->id==2)
                                  {
                                    $total_attribute = get_permission_attr(1,2);
                                  }
                                  else
                                  {
                                    $total_attribute = $action->attribute_val;
                                  }
                                ?>
                                <select name="attribute_val-<?php echo $action->id; ?>" id="attribute_val-<?php echo $action->id; ?>">
                                   <option value="">Select total branch</option>
                                   <?php
                                   for($i=1;$i<=$total_attribute;$i++)
                                   {
                                     echo '<option value="'.$i.'">'.$i.'</option>';
                                   }
                                   ?>
                                </select> 
                                <?php
                                }
                                else
                                {
                                  echo $action->attribute_val;
                                }
                              }
                              ?> 
                               
                            </div>  
                           <?php
                        } 
                        else
                        {
                          ?>
                           <div class="per text-danger">
                                N/A
                            </div>  
                          <?php
                        }
                     }
                   }
                   else
                   {
                    echo '<div class="action text-danger">N/A</div>';
                   }
                   ?>  
                   </div> <!-- p-3 -->       

                 </div> <!-- pbody --> 
              <?php
             }
          }
          ?>
        
      </div> <!-- permission -->
      </div>



     <div class="permission-btns">
      <div class="btns">
        <button type="submit"  class="btn-update" name="submit" value="Save"><i class="fa fa-floppy-o"></i> Save</button>
        <button class="btn-update" onclick="window.location.href='<?php echo base_url(); ?>'"><i class="fa fa-sign-out"></i> Exit</button> 
      </div>
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
    url: "<?php echo base_url(); ?>branch/permission/<?php echo $branch_id; ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    { 
      $('.overlay-loader-full').hide(); 
      flash_session_msg(result);      
    }
  });
}); 
 
$("#all_active").click(function() {
    if ($(this).is(':checked'))
    {
        $("input:radio.active").attr("checked", "checked");
    } 
});

$("#all_inactive").click(function() {
    if ($(this).is(':checked'))
    {
        $("input:radio.inactive").attr("checked", "checked");
    } 
});
</script>


</div><!-- container-fluid -->
</body>
</html>