<!DOCTYPE html>
<html>
<head>
<title><?php echo $page_title.PAGE_TITLE; ?></title>
<?php  
  $users_data = $this->session->userdata('auth_users'); 
  $child_test_ids = $this->session->userdata('child_test_ids');
  //print_r($child_test_ids);die;
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
  
  <form name="pathology_gst_setting_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
  <input type="hidden" name="data_id" id="data_id" value="<?php echo $form_data['data_id']; ?>">
  <div class="content-inner">

 <!-- top section -->
 <div class="row">
   <div class="col-md-4">
     

     
     

      <div class="row mb-5">
        <div class="col-md-5">
          <label>Select For<span class="star">*</span></label>
        </div>
        <div class="col-md-7">
        
        <input type="radio"  class="" name="highlight" onclick="return check_test_box(0);" <?php if($form_data['highlight']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> No for all Test/Profile
        
          <input type="radio"  class="" name="highlight" onclick="return check_test_box(1);" <?php if($form_data['highlight']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Yes for all Test/Profile
          
          <input type="radio"  class="" name="highlight" onclick="return check_test_box(2);" <?php if($form_data['highlight']==2){ echo 'checked="checked"'; } ?> id="login_status" value="2" /> Yes for selected Test/Profile
        </div>
      </div> 
      
       <div class="row mb-5">
        <div class="col-md-5">
          <label for="">GST (%) </label>
        </div>
        <div class="col-md-7">
          <input type="text" name="gst_vals" class="price_float numeric" value="<?php echo $form_data['gst_vals']; ?>" placeholder="%">
          <?php if(!empty($form_error)){ echo form_error('gst_vals'); } ?>
        </div>
      </div>
 

   </div>
   <!-- col-md-4 -->
   <div class="col-md-7" style="padding-right:5px;">
     
     

   </div>
   <!-- col-md-7 -->

 </div>
 <!-- row -->



<!-- bottom section -->
<div class="profile-master2" id="test_box" <?php if($form_data['highlight']=='0' || $form_data['highlight']==1){ ?> style="display: none;" <?php } ?>>
  
  <div class="row mb-5">
    <div class="col-md-4">
      <div class="row mb-5">
        <div class="col-md-5">
          <label>Department<span class="star">*</span></label>
        </div>
        <div class="col-md-7">
          <select name="departments_id" id="departments_id" class="pat-select1" onChange="getTestHeads(this.value);">
               <option value="">Select Department</option>
               <?php
                    if(!empty($dept_list)){
                         foreach($dept_list as $departments)
                         {   
                              $selected_dept = '';
                              if($form_data['departments_id']==$departments->id)
                              {
                                $selected_dept = 'selected="selected"';
                              }
                              echo '<option '.$selected_dept.' value="'.$departments->id.'"  >'.$departments->department.'</option>';
                         }
                    }
               ?> 
           </select>
        </div>
      </div>

      <div class="row mb-5">
        <div class="col-md-5"></div>
        <div class="col-md-7">
          <div class="de_vals_box">
            <select name="profile_test_id" id="profile_test_id" size ="12" class="pat-select1" onChange="getChildTestList(this.value);">
                              
                            
            </select>
          </div>
        </div>
      </div>
    </div>
    <!-- col-md-4 -->
    <div class="col-md-7" style="padding-right: 5px;">
        
        <ul class="list-inline">
              <li>
                <select name="profile_id" id="profile_id">
                     <option value="">Select Profile</option>
                     <?php
                      if(!empty($profile_list))
                      {
                        foreach ($profile_list as $profile) 
                        {
                          echo '<option value="'.$profile['id'].'">'.$profile['profile_name'].'</option>';
                        }
                      }
                     ?>
                </select>

              </li>
              <li><a class="btn-new" onclick="add_profile();"> <i class="fa fa-plus"></i> Add </a> </li>
               <li>
                
            
            </li>
            </ul>

      <div class="de_vals_box_right">
        <table class="table table-bordered table-striped"  id="test_child_list">
            <thead class="bg-theme">
                <tr>
                    <th align="center" width="40">
                      <input type="checkbox" name="gettestselectAll" class="" onclick="checkall_box()" id="gettestselectAll" value="">
                    </th>
                    <!-- <th>Sr.No.</th>
                    <th>ID</th>
                    <th>Head Name</th> -->
                    <th class="p-l-4">Test Name</th>
                  <!--   <th>Rate</th> -->
                </tr>
            </thead>
            <tbody id="test_child">
              <tr>
                 <td align="center" colspan="2">
                    Test not found
                 </td> 
          </tr> 
        </tbody>
        </table>
      </div>


  </div>


   <div class="col-md-1 pl-0">
     <a href="javascript:void(0);" class="btn-custom" id="addAll" onClick="return checkboxValues();"><i class="fa fa-plus"></i> Add</a> 
   </div>
   <!-- col-md-1 -->




</div>



  <div class="row m-t-5">
    <div class="col-md-11 pr-0">
      
        <div class="de_vals_box_right">
<script>
function add_profile()
  {  
     var profile_id = $('#profile_id').val(); 
     var prof_id = $('#data_id').val(); 
     
     $.ajax({
      url: "<?php echo base_url(); ?>pathology_gst_setting/set_profile/"+profile_id+'/'+prof_id, 
      success: function(result)
      {
         $("#test_child_add").html(result);
          
      
      } 
    }); 
  }

$('#addtestchildselectAll').on('click', function () { 
if ($(this).hasClass('allChecked')) {
   $('.childtestchecklist').prop('checked', false);
} 
else 
{
    $('.childtestchecklist').prop('checked', true);
}
   $(this).toggleClass('allChecked');
})
</script>

            <table class="table table-bordered table-striped" id="test_child_add_list">
                <thead class="bg-theme">
                    <tr>
                        <th align="center" width="40">
                         <input type="checkbox" name="addtestchildselectAll" class="" id="addtestchildselectAll" value="">
                        </th>
                        <th>Test Name</th>
                    </tr>
                </thead>
                <tbody id="test_child_add">
                  <?php
                  if(!empty($added_test_child))
                  {
                    echo $added_test_child;
                  }
                  else
                  {
                  ?>
                 <tr>
                  <td class="text-danger" colspan="2">
                  <div class="text-center">No Records Founds</div>
                 </td>
                 </tr>
                 <?php
                  }
                 ?>
                </tbody>
            </table>
      </div> <!-- de_vals_box_right -->

  </div>
  <!-- col-md-11 -->
    <div class="col-md-1" style="padding-left: 5px;">
      
      <div class="btns">
           
        <a href="javascript:void(0);" class="btn-custom"   onClick="return deleteChildCheckboxValues();"><i class="fa fa-trash"></i> Delete</a>
       

          
      </div> 
    </div>
    <!-- col-md-1 -->
  </div>
  <!-- row -->

</div>




 
  <div class="row m-t-5">
    <div class="col-md-4">
      

        <div class="row">
          <div class="col-md-4 m-t-5">
            
            <!-- <button class="btn-update"> <i class="fa fa-trash"></i> Delete  </button> -->
            <button class="btn-save" type="submit"><i class="fa fa-floppy-o"></i> Save</button>
            <button  onclick="window.location.href='<?php echo base_url('/'); ?>';" class="btn-save" type="button"><i class="fa fa-sign-out"></i> Exit</button>

            
          <!-- </div>  -->
        </div>     
       </div>

      

    </div>
    <!-- col-md-4 -->
    <div class="col-md-8"></div>
    <!-- col-md-8 -->
  </div>

    

    
    

   

  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>  
</div><!--container-fluid -->
<script type="text/javascript">

 <?php
 $flash_success = $this->session->flashdata('success');
 if(isset($flash_success) && !empty($flash_success))
 {
   echo 'flash_session_msg("'.$flash_success.'");';
 }
 ?>
 

  <?php
  if(!empty($form_data['departments_id']) && $form_data['departments_id']>0)
  {
    echo 'getTestHeads('.$form_data['departments_id'].')';
  }
  ?> 
  function getTestHeads(departments_id='')
  {
    $.post('<?php echo base_url(); ?>test_profile/get_test_heads/',{"departments_id":departments_id},function(result){
              $("#profile_test_id").html(result);
              
     });
  }

  function getChildTestList(parent_test_id='')
  { 
     var getChildCount = document.getElementById("test_child_list").rows.length;
      
     if(getChildCount>1)
     {
          $("#test_child tr").remove();
     } 
     if($("#gettestselectAll").prop("checked")==true)
     {
          $("#gettestselectAll").attr("checked",false);
     }

     $.post('<?php echo base_url(); ?>test_profile/get_child_test_list/',{"parent_test_id":parent_test_id},function(result){

              $("#test_child").html(result);
              
     });
  
     

   }

function checkall_box()
{
   if($("#gettestselectAll").prop("checked")==true)
    {
      $('.checklist').prop('checked', true);
    } 
    else
    {
      $('.checklist').prop('checked', false);
    }
    //$('#gettestselectAll').toggleClass('allChecked'); 
}

function checkboxValues() 
{    
    $('tbody#test_child_add tr#nodata').remove();
     var allVals = [];

     $('.checklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });
     alltestadd(allVals);
}
//for delete the selected child test list
function deleteChildCheckboxValues() 
{    
  
     var allVals = [];
          $('.childtestchecklist:checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
    
    /* var allVals = [];

     $('.childtestchecklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });*/
     remove_test(allVals);
}


function remove_test(allVals)
  {  
      
       // alert(allVals);
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('pathology_gst_setting/remove_test');?>",
              data: {row_id: allVals},
              success: function(result) 
              {
                $('#test_child_add').html(result); 
 
              }
              
          });
   }
  }

function alltestdelete(allVals){   
     
     if(allVals!=""){
          $.post('<?php echo base_url(); ?>pathology_gst_setting/deletealllistedchildtest/',{row_id: allVals},function(result){
               var testHeadId = $("#profile_test_id option:selected").val();
            
              getChildTestList(testHeadId);
             $("#test_child_add").html(result);
          });
          
     }
}
function addtestcheckboxValues() 
{    
    
     var allVals = [];

     $('.childtestchecklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });
     save_added_child_test(allVals);
}
var testChildTable;
//add the child test list into selected child test list 
function alltestadd(allVals){   
    
     if(allVals!=""){
          $.post('<?php echo base_url(); ?>pathology_gst_setting/listaddallchildtest/',{row_id: allVals},function(result){
             var testHeadId = $("#profile_test_id option:selected").val();

                    getChildTestList(testHeadId);
    
             $("#test_child_add").append(result);
          });
          
     }
}
//delete the selected child list


function check_test_box(vals)
{
  if(vals==2 || vals==3)
  {
    $('#test_box').show();
  }
  else
  {
    $('#test_box').hide();
  }
}
</script>
</body>
</html>