<?php
$vaccination_kit_data = $this->session->userdata('vaccination_kit_data');
//echo "<pre>"; print_r($vaccination_kit_data);
?>
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
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script> 
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
            <input type="hidden" name="pack_id" id="pack_id" value="<?php echo $form_data['pack_id']; ?>" />
          
                <div class="row m-b-5 m-t-6">
                    <div class="col-md-5">
                        <strong>Vaccination Kit Name <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">

                        <input type="text"  name="package_title" id="title" value="<?php echo $form_data['package_title']; ?>" class="alpha_numeric_space" autofocus="">
                                  <?php if(!empty($form_error)){ echo form_error('package_title'); } ?>
                    </div>
                </div> <!-- row -->

                <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Amount <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"   name="amount" id="amount" class="price_float" value="<?php echo $form_data['amount']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('amount'); } ?>
                    </div>
                </div> <!-- row -->
                 <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Kit Quantity <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"   name="package_quantity" id="package_quantity" class="numeric" value="<?php echo $form_data['package_quantity']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('package_quantity'); } ?>
                    </div>
                </div> <!-- row -->
            
               
               <div class="row">
                    <div class="col-md-5">
                         <strong>Status  <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                         <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"';} ?> id="status" value="1" /> Active 
                         <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"';} ?> id="status" value="0" /> Inactive  
                    </div>
               </div>
             
                
        </div> <!-- pro-left -->

        <div class="pro-right">
               <div class="grp">
                    <div class="row m-b-5">
                         <div class="col-xs-5">
                              <label>Search Vaccination</label>
                         </div>
                         <div class="col-xs-7">
                              <input type="text" autocomplete="off" name="vaccination_name" onkeyup="return get_vaccination_search_data(this.value);">
                         </div>
                    </div> 
               </div>

            <div class="pframe2">
                <table class="table table-bordered table-striped" id="vaccination_list">
                    <thead class="bg-theme">
                        <tr>
                    <th align="center" width="40"><input type="checkbox"  name="getvaccinationselEctAll" class="" id="getvaccinationselEctAll" value=""></th>
                            <th>Vaccination Code</th>
                            <th>Vaccination Name</th>
                            <th>Vaccination Company</th>
                            
                        </tr>
                    </thead>
                    <tbody id="vaccination">
                       <?php echo $vaccination_list; ?>
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

          
     


         
          <table class="table table-bordered table-striped" id="medicine_add_list">
               <thead class="bg-theme">
                    <tr>
                         <th align="center" width="40"><input type="checkbox" onclick="med_check()" name="addvaccinationselEctAll" class="" id="addvaccinationselEctAll" value=""></th>
                         <th>Vaccination Code</th>
                         <th>Vaccination Name</th>
                         <th>Vaccination Company</th>
                         <th>Total Quantity</th>
                         <th>Conversion</th>
                         <th>Quantity</th>
                       <!--   <th>qty1</th>
                         <th>qty2</th> -->
                    </tr>
               </thead>
               <tbody id="vaccination_add">
                    <?php echo $added_vaccination; ?>
               </tbody>
          </table>
                <?php if(!empty($form_error)){ echo form_error('test'); } ?>
    </div> <!-- proleft -->

    <div class="proright">
        <div class="btns">
            <button class="btn-new" id="deleteAll" onclick="return deleteChildCheckboxValues();">
                    <i class="fa fa-trash"></i> Delete
               </button>
            <button type="submit" class="btn-save" name="" id="" onclick="return addvaccinationcheckBoxvalues();"><i class="fa fa-floppy-o"></i> Save</button>
            <button type="button" class="btn-save" name="" id="" onclick="window.location.href='<?php echo base_url('vaccination_packages'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>

        </div>
    </div> <!-- proright -->
</div> <!-- profile-master2 -->



<script type="text/javascript">
$(document).ready(function(){
     <?php  $array = $this->session->userdata('vaccination_kit_data');
          $js_array = json_encode($array);
     ?>
     var addedmedicineids = '<?php print_r($js_array); ?>';
  
     var checkStr ="onload";
     getMedicineList(checkStr);
     get_select_kit_vaccination(checkStr);
     var profId = $("#pack_id").val();
    
});
$(document).ready(function(){
   $('#addvaccinationselEctAll').on('click', function () { 
          if ($(this).hasClass('allChecked')) {
               $('.vaccinationchecklist').prop('checked', false);
          } else {
               $('.vaccinationchecklist').prop('checked', true);
          }
          $(this).toggleClass('allChecked');
     });
     $('#selectAll').on('click', function () { 
         if ($(this).hasClass('allChecked')) {
             $('.checklist').prop('checked', false);
         } else {
             $('.checklist').prop('checked', true);
         }
         $(this).toggleClass('allChecked');
     });
     $('#getvaccinationselEctAll').on('click', function () { 
          if ($(this).hasClass('allChecked')) {
               $('.checklist').prop('checked', false);
          } else {
          $('.checklist').prop('checked', true);
          }
          $(this).toggleClass('allChecked');
     });
});
function checkValue(obj){
     if(obj.value=='')
     {
         obj.value=1;
     }
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

 var save_method; 
var table;

     //get the child test list according to parent_id on change
  function getMedicineList(){
     var getChildCount = document.getElementById("vaccination_list").rows.length;
      
     if(getChildCount>1){
          $("#vaccination tr").remove();
     }
     
     if($("#gettestselectAll").prop("checked")==true){
          $("#gettestselectAll").attr("checked",false);
     }

     $.post('<?php echo base_url(); ?>vaccination_packages/get_added_vaccination_list/',{},function(result){

              $("#vaccination").html(result);
              
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
function checkboxValues() 
{    
    $('tbody#vaccination_add tr#nodata').remove();
     var allVals = [];

     $('.checklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });
     var checkStr ="onadd";
     allvaccinationadd(allVals,checkStr);
}
//for delete the selected child test list
function deleteChildCheckboxValues() 
{    
    
     var allVals = [];

     $('.vaccinationchecklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });
     
     allmvaccinationdelete(allVals);
}
function add_interpretation()
{
  var $modal = $('#load_add_interpretation_modal_popup');
  $modal.load('<?php echo base_url().'vaccination_packages/add_interpretation/'; ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function addvaccinationcheckBoxvalues() 
{    
    
     var allVals = [];

     $('.vaccinationchecklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==false)
       {
          var medicine_id = $(this).val();
          
          var unit1qty_id ="#"+medicine_id+"-unit1qty";

          var unit2qty_id ="#"+medicine_id+"-unit2qty";
          
          var conversion_id ="#"+medicine_id+"-conversion";
          var medicine_qty1 =$(unit1qty_id.trim()).val();

          var medicine_qty2 = $(unit2qty_id.trim()).val();
          var medicine_conversion =$(conversion_id.trim()).val();
          var total_qty = parseInt(medicine_qty1)*parseInt(medicine_conversion)+parseInt(medicine_qty2);
          allVals.push(medicine_id,medicine_qty1,medicine_qty2,total_qty);
       } 
   
     });
     
     
     save_added_vaccination(allVals);
}
var testChildTable;
//add the child test list into selected child test list 
function allvaccinationadd(allVals,checkStr){ 

     if(allVals!=""){
          $.post('<?php echo base_url(); ?>vaccination_packages/listalladdedvaccination/',{row_id: allVals},function(result){
         

                    getMedicineList();
                    $("#vaccination_add").html(result);
          });
          
     }
}
//delete the selected child list
function allmvaccinationdelete(allVals){  
     $('tbody#vaccination_add tr').remove();
     
     if(allVals!=""){
          $.post('<?php echo base_url(); ?>vaccination_packages/deletealllistedmedicine/',{row_id: allVals},function(result){
             
            
              getMedicineList();
             $("#vaccination_add").html(result);
          });
          
     }
}

function set_qty_mkit(mid,type,qty)
{
     if(qty!='')
     {
          $.post('<?php echo base_url(); ?>vaccination_packages/set_qty_mkit/'+mid+'/'+type+'/'+qty,function(result)
          {         
           
             $("#vaccination_add").html(result);
          });
     }

}

function get_select_kit_vaccination(vaccination_kit_data)
{
     $.post('<?php echo base_url(); ?>vaccination_packages/set_row_vaccination_kit',function(result)
     {         
        $("#vaccination_add").html(result);
     });
}

function save_added_vaccination(allVals){
    
     var packageTitle = $("#title").val();
     var amount = $("#amount").val();
     var status = $("#status").val();
     var package_quantity = $("#package_quantity").val();
    
     var ids = $('#pack_id').val();
      

     if(ids!="" && !isNaN(ids)){ 
          var path = 'edit/'+ids;
          var msg = 'Profile successfully updated.';
     }else{
          var path = 'add/';
          var msg = 'Profile successfully created.';
     }   
    
     var data = new FormData();
     data.append("package_title",packageTitle);
     data.append("amount",amount);
     data.append("package_quantity",package_quantity);
      data.append("row_id",allVals);
       data.append("pack_id",ids);
     data.append("status",status);
     $.ajax({
          url: "<?php echo base_url(); ?>vaccination_packages/"+path,
          data:data,
          type:"POST",
          crossDomain: true,
          processData: false,
          contentType: false,
          
          success: function(data){

                if(data!=='1'){
                    $('#hello').html(data);
               }else{
                    window.location.href='<?php echo base_url('vaccination_packages'); ?>';
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
 

 function med_check()
 {
     if($('#addvaccinationselEctAll').is(':checked')) 
     {    
          $('.vaccinationchecklist').prop('checked', false);
     }
     else
     {
          $('.vaccinationchecklist').prop('checked', false);
     }
 }
 function get_vaccination_search_data(val){
     
      var totalRows= $('tbody#vaccination tr').length;
      var emptyRows= $('tbody#vaccination tr#nodata').length;
      if(totalRows!=emptyRows){
            $('tbody#vaccination tr#nodata').remove();
      }
     $.post('<?php echo base_url(); ?>vaccination_packages/get_vaccination_search_data',{'search_data':val},function(result){
            $("#vaccination").html(result);
            if(totalRows!=emptyRows){
                $('tbody#vaccination tr#nodata').remove();
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