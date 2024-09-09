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

                        <input type="text"  name="profile_name" id="profile_name" value="<?php echo $form_data['profile_name']; ?>">
                                  <?php if(!empty($form_error)){ echo form_error('profile_name'); } ?>
                    </div>
                </div> <!-- row -->

                <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Print Name <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"  name="print_name" id="print_name" value="<?php echo $form_data['print_name']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('print_name'); } ?>
                    </div>
                </div> <!-- row -->
           
            
                 <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong> Master Rate <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"  name="master_rate" id="master_rate" value="<?php echo $form_data['master_rate']; ?>"onkeypress="return isNumberKey(event);">
                                  <?php if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                    </div>
                </div>
                <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Base Rate <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"  name="base_rate" id="base_rate" value="<?php echo $form_data['base_rate']; ?>"onkeypress="return isNumberKey(event);">
                                  <?php if(!empty($form_error)){ echo form_error('base_rate'); } ?>
                    </div>
                </div> <!-- row -->
            
               <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong><!-- Base Rate <span class="star">*</span> --></strong>
                    </div>
                    <div class="col-md-7">
                         <select name="departments_id" id="departments_id" class="pat-select1" onchange="getTestHeads(this.value);">
                             <option value="">Select Departements</option>
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
                        <select name="profile_test_id" id="profile_test_id" size ="12" class="pat-select1" onchange="getChildTestList(this.value);">
                              
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
                       <button type="button" class="btn-commission " name="" id="" onclick="add_interpretation();"><i class="fa fa-plus"></i> Add Interpretation</button>
                    </div>
               </div>
                
                   
                
           

           
              
                     
                  
            

        </div> <!-- pro-left -->

        <div class="pro-right">
            <div class="grp">
                
            </div>

            <div class="pframe2">
                <table class="table table-bordered table-striped" id="test_child_list">
                    <thead class="bg-theme">
                        <tr>
                            <th align="center" width="40"><input type="checkbox" name="gettestselectAll" class="" id="gettestselectAll" value=""></th>
                            <!-- <th>Sr.No.</th>
                            <th>ID</th>
                            <th>Head Name</th> -->
                            <th class="p-l-0">Test Name</th>
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
                    <button class="btn-new" id="addAll" onclick="return checkboxValues();">
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
                            <th align="center" width="40"><input type="checkbox" name="addtestchildselectAll" class="" id="addtestchildselectAll" value=""></th>
                            <th>Test Name</th>
                        </tr>
                    </thead>
                    <tbody id="test_child_add">
                         <?php echo $added_test_child; ?>
                    </tbody>
                </table>
                <?php if(!empty($form_error)){ echo form_error('test'); } ?>
    </div> <!-- proleft -->

    <div class="proright">
        <div class="btns">
            <button class="btn-update" id="deleteAll" onclick="return deleteChildCheckboxValues();">
                    <i class="fa fa-trash"></i> Delete
               </button>
            <button type="submit" class="btn-save" name="" id="" onclick="return addtestcheckboxValues();"><i class="fa fa-floppy-o"></i> Save</button>
            <button type="button" class="btn-save" name="" id="" onclick="window.location.href='<?php echo base_url('test_profile'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>

        </div>
    </div> <!-- proright -->
</div> <!-- profile-master2 -->



<script type="text/javascript">
$(document).ready(function(){
     <?php  $array = $this->session->userdata('child_test_ids');
          $js_array = json_encode($array);
     ?>
     var addedchildtestids = '<?php print_r($js_array); ?>';
  
   
     alltestadd(addedchildtestids);
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
function getTestHeads(departments_id=''){
    $.post('<?php echo base_url(); ?>test_profile/get_test_heads/',{"departments_id":departments_id},function(result){
              $("#profile_test_id").html(result);
              
     });
}
 var save_method; 
var table;

     //get the child test list according to parent_id on change
  function getChildTestList(parent_test_id=''){
     var getChildCount = document.getElementById("test_child_list").rows.length;
      
     if(getChildCount>1){
          $("#test_child tr").remove();
     }
     
     if($("#gettestselectAll").prop("checked")==true){
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

     $('.childtestchecklist:checkbox').each(function() 
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
            allVals.push($(this).val());
       } 
   
     });
     save_added_child_test(allVals);
}
var testChildTable;
//add the child test list into selected child test list 
function alltestadd(allVals){   
    
     if(allVals!=""){
          $.post('<?php echo base_url(); ?>test_profile/listaddallchildtest/',{row_id: allVals},function(result){
             var testHeadId = $("#profile_test_id option:selected").val();

                    getChildTestList(testHeadId);
    
             $("#test_child_add").append(result);
          });
          
     }
}
//delete the selected child list
function alltestdelete(allVals){   
     
     if(allVals!=""){
          $.post('<?php echo base_url(); ?>test_profile/deletealllistedchildtest/',{row_id: allVals},function(result){
               var testHeadId = $("#profile_test_id option:selected").val();
            
              getChildTestList(testHeadId);
             $("#test_child_add").html(result);
          });
          
     }
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

                if(data!=='1'){
                    $('#hello').html(data);
               }else{
                    window.location.href='<?php echo base_url('test_profile'); ?>';
               }              
          }

     });
     
    
}
  function simulation_modal()
  {
      var $modal = $('#load_add_simulation_modal_popup');
      $modal.load('<?php echo base_url().'simulation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  }

  function relation_modal()
  {
      var $modal = $('#load_add_relation_modal_popup');
      $modal.load('<?php echo base_url().'relation/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function religion_modal()
  {
      var $modal = $('#load_add_religion_modal_popup');
      $modal.load('<?php echo base_url().'religion/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function insurance_type_modal()
  {
      var $modal = $('#load_add_insurance_type_modal_popup');
      $modal.load('<?php echo base_url().'insurance_type/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function insurance_company_modal()
  {
      var $modal = $('#load_add_insurance_company_modal_popup');
      $modal.load('<?php echo base_url().'insurance_company/add/' ?>',
      {
        //'id1': '1',
        //'id2': '2'
        },
      function(){
      $modal.modal('show');
      });
  } 

  function get_state(country_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
      success: function(result)
      {
        $('#state_id').html(result); 
      } 
    });
    get_city(); 
  }

  function get_city(state_id)
  {
    $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
      success: function(result)
      {
        $('#city_id').html(result); 
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
  
  $('#form_submit').on("click",function(){
       $('#patient_form').submit();
  })
 
 function set_tpa(val)
 {
    if(val==0)
    {
      $('#insurance_type_id').attr("disabled", true);
      $('#insurance_type_id').val('');
      $('#ins_company_id').attr("disabled", true);
      $('#ins_company_id').val('');
      $('#polocy_no').attr("readonly", "readonly");
      $('#polocy_no').val('');
      $('#tpa_id').attr("readonly", "readonly");
      $('#tpa_id').val('');
      $('#ins_amount').attr("readonly", "readonly");
      $('#ins_amount').val('');
    }
    else
    {
      $('#insurance_type_id').attr("disabled", false);
      $('#ins_company_id').attr("disabled", false);
      $('#polocy_no').removeAttr("readonly", "readonly");
      $('#tpa_id').removeAttr("readonly", "readonly");
      $('#ins_amount').removeAttr("readonly", "readonly");
    }
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