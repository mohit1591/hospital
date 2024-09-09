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
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
 
<?php
$users_data = $this->session->userdata('auth_users');
?>
</head>

<body id="hello">


<div class="container-fluid">
 <?php
  $this->load->view('include/header');
  $this->load->view('include/inner_header');
 
 ?>
<!-- ============================= Main content start here ===================================== -->

<section class="userlist">

    <div class="profile-master">
    
        <div class="pro-left">
            <input type="hidden" name="prof_id" id="prof_id" value="<?php echo $form_data['prof_id']; ?>" />
            <input type="hidden" name="interpretation_pro" id="interpretationpro" value="<?php echo $form_data['interpretation']; ?>"/>
                <div class="row m-b-5 m-t-6">
                    <div class="col-md-5">
                        <strong> Profile Name <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">

                        <input type="text"  name="profile_name" class="" id="profile_name" value="<?php echo $form_data['profile_name']; ?>" autofocus="">
                                  <?php if(!empty($form_error)){ echo form_error('profile_name'); } ?>
                    </div>
                </div> <!-- row -->

                <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Print Name </strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"  name="print_name" class="alpha_space" id="print_name" value="<?php echo $form_data['print_name']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('print_name'); } ?>
                    </div>
                </div> <!-- row -->
           
            
                 <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong> Patient Rate <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"  class="price_float" name="master_rate" id="master_rate" value="<?php echo $form_data['master_rate']; ?>">
                                  <?php if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                    </div>
                </div>
                <?php
        $branch_attribute = get_permission_attr(1,2); 
        if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) && $branch_attribute>0)
        {
        ?>
        <div class="row m-b-5">
           <div class="col-md-5">
            <strong>Branch Rate <span class="star">*</span></strong>
           </div>
           <div class="col-md-7">
              <input type="text"  class="price_float" name="base_rate" id="base_rate" value="<?php echo $form_data['base_rate']; ?>">
            <?php if(!empty($form_error)){ echo form_error('base_rate'); } ?>
           </div>
        </div>
        <?php
        }
        else
        {
         ?>
         <input type="hidden"  name="base_rate" value="<?php echo $form_data['base_rate']; ?>" />
         <?php 
        }
        ?>
            
               <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong><!-- Base Rate <span class="star">*</span> --></strong>
                    </div>
                    <div class="col-md-7">
                         <select name="departments_id" id="departments_id" class="pat-select1" onChange="getTestHeads(this.value);">
                             <option value="">Select Department</option>
                             <?php
                                  if(!empty($departments_list)){
                                       foreach($departments_list as $departments){
                                            $selected_department = '';
                                            if($departments->id==$form_data['departement_id']){
                                                 $selected_department = 'selected="selected"';
                                            }
                                            echo '<option value="'.$departments->id.'" '.$selected_department.'>'.$departments->department.'</option>';
                                       }
                                  }
                             ?> 
                         </select>
                    </div>
               </div> <!-- row -->
                <div class="row m-b-5">
                      <div class="col-md-5">
                        <strong><!-- Base Rate <span class="star">*</span> --></strong>
                      </div>
                      <div class="col-md-7">
                        <select name="profile_test_id" id="profile_test_id" size ="12" class="pat-select1" onChange="getChildTestList(this.value);">
                              
                             <?php if(!empty($test_head_list)){
                                         print_r($test_head_list);
                                  }
                                  else if(!empty($parent_test_list)){
                                       foreach($parent_test_list as $parent_test){
                                            $selected_profile_test = '';
                                            if($parent_test->id==$form_data['parent_test_id']){
                                            

                                                 $selected_profile_test = 'selected="selected"';
                                            }
                                  
                                            echo '<option value="'.$parent_test->id.'" '.$selected_profile_test.'>'.$parent_test->test_name.'</option>';
                                       }
                                  }
                             ?> 
                        </select>
                      </div>
                </div> <!-- row -->
               <div class="row">
                    <div class="col-md-5">
                         <strong>Status  <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                         <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active 
                         <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Inactive  
                    </div>
               </div>
               <div class="row">
                    <div class="col-md-5"></div>
                    <div class="col-md-7">
                       <button type="button" class="btn-custom" name="" id="" onClick="add_interpretation();"><i class="fa fa-plus"></i> Add Interpretation</button>
                    </div>
               </div>
                
                   
                
           

           
              
                     
                  
            

        </div> <!-- pro-left -->

        <div class="pro-right">
      <?php //echo "<pre>";print_r($profile_list); ?>
                
            
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
              <li><a class="btn-new" onclick="add_multi_profile();"> <i class="fa fa-plus"></i> Add </a> </li>
              <li><div class="text-danger" id="profile_error"></div></li>
              <li>
                
             <label>Search </label>
             <input type="text" class="m_input_default" name="test_search" id="test_search" value=""> 
            </li>
            </ul>


            <div class="pframe2">
                <table class="table table-bordered table-striped" id="test_child_list">
                    <thead class="bg-theme">
                        <tr>
                            <th align="center" width="40"><input type="checkbox" name="gettestselectAll" class="" onclick="checkall_box()" id="gettestselectAll" value=""></th>
                            <!-- <th>Sr.No.</th>
                            <th>ID</th>
                            <th>Head Name</th> -->
                            <th class="p-l-4">Test Name</th>
                          <!--   <th>Rate</th> -->
                        </tr>
                    </thead>
                    <tbody id="test_child">
                       <?php echo $child_test_list; ?>
                    </tbody>
                </table>
            </div> <!-- pframe2 -->

            <div class="pframe-right">
                <div class="btns">
                    <button class="btn-custom" id="addAll" onClick="return checkboxValues();">
                    <i class="fa fa-plus"></i> Add
               </button>
                </div>
            </div>

        </div> <!-- pro-right -->


    </div> <!-- profile-master -->




<div class="profile-master2">
    <div class="proleft">


            <table class="table table-bordered table-striped" id="test_child_add_list">
                    <thead class="bg-theme">
                        <tr>
                            <th align="center" width="40"><input type="checkbox" name="selectAll" class="" id="selectAll" value="" onclick="checkall_box_added()"></th>
                            <th>Test Name</th>
                            <th>Sort Order</th>
                        </tr>
                    </thead>
                    <tbody id="test_child_add">
                         <?php //echo $added_test_child; 

                         $profile_data = $this->session->userdata('set_multi_profile');
       
        $booked_test_list = $this->session->userdata('child_test_ids');
        usort($booked_test_list, 'compareByName');
        //echo "<pre>";print_r($booked_test_list);die;
        $total_test = count($booked_test_list);
        $profile_row = "";
        $p_order = 1;
        $profile_order = 1;
        if(isset($profile_data) && !empty($profile_data))
        {
          $pi = 1;
          foreach($profile_data as $profile_dat)
          {
             $profile_row .= '<tr>
                              <td width="40" align="center">
                                 <input type="checkbox" name="test_id[]" class="booked_checkbox testlist" value="p_'.$profile_dat['id'].'" >
                              </td>
                              
                              <td>'.$profile_dat['name'].'</td>
                              <td></td>
                              
                          </tr>';
          
           
            $pi++;
          }                 
        }
        if(isset($booked_test_list) && !empty($booked_test_list))
        {
          $i = 1;          
          foreach($booked_test_list as $booked_test)
          { 
            
            
            ?>
              <tr>
                  <td width="40" align="center">
                     <input type="checkbox" name="test_id[]" class="booked_checkbox testlist" value="<?php echo $booked_test['id']; ?>" >
                  </td>
                  
                  <td><?php echo $booked_test['name']; ?></td>
                  <td width="50px"><input type="text" size="20" class="numeric" value="<?php echo $booked_test['sort_order']; ?>" onkeyup="return set_ptest_order('<?php echo $booked_test['id']; ?>',this.value);" /></td>
                 
              </tr>       
            <?php
            if(isset($profile_data) && !empty($profile_data))
            { 
               if($i==1)
               {
                 echo $profile_row;
               }
            }
          $i++;  
          }
        }
        else
        {
          if(isset($profile_data) && !empty($profile_data))
          {
            echo $profile_row;
          }
          else
          {
             ?> 
            <tr>
              <td colspan="4">
                 <div class="text-danger p-l-half">Test not added.</div>
              </td> 
            </tr>
            <?php
          } 
        }

                         ?>
                       
                       

                    </tbody>
                </table>
                <?php if(!empty($form_error)){ echo form_error('test'); } ?>
    </div> <!-- proleft -->

    <div class="proright">
        <div class="btns">
            <button class="btn-update" id="deleteAll" onClick="return test_list_vals();">
                    <i class="fa fa-trash"></i> Delete
               </button>
            <button type="submit" class="btn-save" name="" id="" onClick="return addtestcheckboxValues();"><i class="fa fa-floppy-o"></i> Save</button>
            <button type="button" class="btn-save" name="" id="" onClick="window.location.href='<?php echo base_url('test_profile'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>

        </div>
    </div> <!-- proright -->
</div> <!-- profile-master2 -->



<script type="text/javascript">

function add_multi_profile()
  {  
     var profile_id = $('#profile_id').val(); 
     $.ajax({
      url: "<?php echo base_url(); ?>test_profile/set_multi_profile/"+profile_id, 
      success: function(result)
      {
         $("#test_child_add").html(result);
          
      
      } 
    }); 
  }

$(document).ready(function(){
     <?php  $array = $this->session->userdata('child_test_ids');
          $js_array = json_encode($array);
     ?>
     var addedchildtestids = '<?php print_r($js_array); ?>';
  
   
     //alltestadd(addedchildtestids);
     resetAllSessionData();
     var profId = $("#prof_id").val();
     if(profId){
          <?php $departement_id = $this->session->userdata('departement_id'); ?>
          var departementId = "<?php echo $departement_id; ?>";
          if(departementId){
               getTestHeads(departementId);
          }
     }
});


  
 var save_method; 
var table;
  
  function resetAllSessionData(){
     var profId = $("#prof_id").val();
     var url;
     if(profId){
         url ='<?php echo base_url(); ?>test_profile/reset_all_session_data/'+profId;
     }else{
          url='<?php echo base_url(); ?>test_profile/reset_all_session_data/';
     }
     $.post(url,{},function(){
         
     });
}

  function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#pimg').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $("#img-input").change(function(){
        readURL(this);
    });
  function getTestHeads(departments_id){
      $.post('<?php echo base_url(); ?>test_profile/get_test_heads/',{"departments_id":departments_id},function(result){
                $("#profile_test_id").html(result);
                
       });
  }

     //get the child test list according to parent_id on change
  function getChildTestList(parent_test_id)
  {
     var getChildCount = document.getElementById("test_child_list").rows.length;
      
     if(getChildCount>1){
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

     $('.testlist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });
     alltestdelete(allVals);
}
function add_interpretation()
{
  var $modal = $('#load_add_interpretation_modal_popup');
  $modal.load('<?php echo base_url().'test_profile/add_interpretation/'; ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}


function addtestcheckboxValues() 
{    
     var allVals = [];
     $('.childtestchecklist:checkbox').each(function() 
     {
       if($(this).prop('checked')==true)
       {
            var str = $(this).val();
            var res = str.replace("'", "`");
            allVals.push(res);
       } 
   
     });
     save_added_child_test(allVals);
}
var testChildTable;
//add the child test list into selected child test list 
function alltestadd(allVals)
{

    
     if(allVals!=""){
          $.post('<?php echo base_url(); ?>test_profile/set_multi_test/',{row_id: allVals},function(result){
             var testHeadId = $("#profile_test_id option:selected").val();
             
              getChildTestList(testHeadId);
    
             $("#test_child_add").html(result);
          });
          
     }
}

 function test_list_vals() 
  {          
       var allVals = [];
       $('.booked_checkbox').each(function() 
       {
         if($(this).prop('checked')==true)
         {
              allVals.push($(this).val());
         } 
       });
       remove_test(allVals);
  }

  function remove_test(allVals)
  {     
   if(allVals!="")
   {
      $.ajax({
              type: "POST",
              url: "<?php echo base_url('test_profile/remove_test');?>",
              data: {row_id: allVals},
              success: function(result) 
              {
                $('#test_child_add').html(result); 

                var head_id = $('#departments_id').val();
                  $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+head_id, 
                    success: function(result)
                    {
                       $('#test_list').html(result); 
                      
                       return false;
                    } 
                  });  
              }
          });
   }
  }
//delete the selected child list
function alltestdelete(allVals)
{   
     
     if(allVals!="")
     {
        $.ajax({
                type: "POST",
                url: "<?php echo base_url('test_profile/deletealllistedchildtest');?>",
                data: {row_id: allVals},
                success: function(result) 
                {
                  $('#child_test_ids').html(result); 
                  var head_id = $('#departments_id').val();
                  $.ajax({url: "<?php echo base_url(); ?>test/test_list/"+head_id, 
                    success: function(result)
                    {
                       $('#test_list').html(result); 
                      
                       return false;
                    } 
                  });  
                }
            });
     } 
     
     /*if(allVals!=""){
          $.post('<?php echo base_url(); ?>test_profile/deletealllistedchildtest/',{row_id: allVals},function(result){
               var testHeadId = $("#profile_test_id option:selected").val();
            
              getChildTestList(testHeadId);
             $("#test_child_add").html(result);
          });
          
     }*/
}
function save_added_child_test(allVals){

     var profileName = $("#profile_name").val();
     var profilePrintName = $("#print_name").val();
     var masterRate = $("#master_rate").val();
     var baseRate = $("#base_rate").val();
     var interpretation = $("#interpretation").val();
     var interpreta;
     if(interpretation){
           interpreta = interpretation;
     }else{
          interpreta='';
     }
     
     var status = $("#status").val();
     var ids = $('#prof_id').val();
      

     if(ids!="" && !isNaN(ids)){ 
          var path = 'edit/'+ids;
          var msg = 'Profile successfully updated.';
     }else{
          var path = 'add/';
          var msg = 'Profile successfully created.';
     }   
    
     var data = new FormData();
     data.append("profile_name",profileName);
     data.append("print_name",profilePrintName);
     data.append("master_rate",masterRate);
     data.append("base_rate",baseRate);
     data.append("row_id",allVals);
     data.append("prof_id",ids);
     data.append("interpretation",interpreta);
     data.append("status",status);
     $.ajax({
          url: "<?php echo base_url(); ?>test_profile/"+path,
          data:data,
          type:"POST",
          crossDomain: true,
          processData: false,
          contentType: false,
          
          success: function(data){

                if(data!='1'){
                    $('#hello').html(data);
               }else{
                    window.location.href='<?php echo base_url('test_profile'); ?>';
               }              
          }

     });
     
    
}
 
   

  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : event.keyCode;
      if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
          return false;
      } else {
          return true;
      }      
  }
  
  
</script> 





<script>
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



function checkall_box_added()
{
   if($("#selectAll").prop("checked")==true)
    {
      $('.booked_checkbox').prop('checked', true);
    } 
    else
    {
      $('.booked_checkbox').prop('checked', false);
    }
    //$('#gettestselectAll').toggleClass('allChecked'); 
}

$("#test_search").keyup(function(){ 
      var search_text = $("#test_search").val();  
      var dept_id = $("#departments_id").val();  
      var test_head_id = $("#profile_test_id").val();
      if(test_head_id=='' || test_head_id==null)
      {
        test_head_id = 0;
      }  
      if(dept_id=='')
      {
        dept_id = 0;
      } 
      if(search_text=='')
      {
        search_text = 0;
      }  
     /* $.ajax({
               type: "POST",
               url: "<?php echo base_url(); ?>test_profile/test_list/"+test_head_id+"/0/"+search_text+"/"+dept_id, 
               success: function(msg)
               {
                  $("#test_child").html(msg);
               }
          });*/
       
       var url ="<?php echo base_url(); ?>test_profile/test_list";
      
       $.post(url,
        { test_head_id: test_head_id, profile_id: 0, search_text: search_text, dept_id: dept_id},
        function (msg) {
          $("#test_child").html(msg);

        });
  }); 


  function set_ptest_order(t_id,vals)
  {
    $.ajax({
               type: "POST",
               url: "<?php echo base_url(); ?>test_profile/set_ptest_order/"+t_id+"/"+vals, 
               success: function(msg)
               {

               }
          });
  } 
</script>


<div id="load_add_interpretation_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>


</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>