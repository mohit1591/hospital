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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
    <form id="certificate_list"> 
    <div class="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
        
            
               <!-- bootstrap data table 145-->
              
                <div class="row"> 
                <div class="col-xs-3"> <label>Module Type </label></div>
                <div class="col-xs-3">
                    <?php if(in_array('85',$users_data['permission']['section'])) { ?> 
                    <input type="radio"  class="radioBtnClass" onclick ="get_data(this.value);" id="module_type" name="type"  value="0" checked="checked"/> OPD   &nbsp;&nbsp; 
                    <?php } ?>  
                    <?php if(in_array('121',$users_data['permission']['section'])) { ?> 
                    <input type="radio"  onclick ="get_data(this.value);" id="module_type" class="radioBtnClass" name="type"   value="1" /> IPD &nbsp;&nbsp;
                    <?php } ?> 
                    <?php if(in_array('134',$users_data['permission']['section'])) { ?>  
                    <input type="radio"  onclick ="get_data(this.value);" id="module_type" class="radioBtnClass" name="type"   value="2" /> OT &nbsp;&nbsp; 
                    <?php } ?>
                     <?php if(in_array('145',$users_data['permission']['section'])) { ?> 
                     <input type="radio"  onclick ="get_data(this.value);" id="module_type" class="radioBtnClass" name="type"   value="3" /> Pathology 
                     <?php } ?>
              </div>
            </div>
               
               
               
               <div class="row m-b-5"> 
                <div class="col-xs-3"> <label>Patient Name </label></div>
                <div class="col-xs-3">
                <input name="patient_name" class="m_input_default" id="patient_name" onkeyup="return form_submit();" value="" type="text" placeholder="Search By Patient Name">
              </div>
              </div>
              
              <div class="row m-b-5"> 
              <div class="col-xs-3"> <label>Patient Reg. No. </label></div>
                <div class="col-xs-3">
                <input name="patient_reg_no" class="m_input_default" id="patient_reg_no" onkeyup="return form_submit();" value="" type="text" placeholder="Search By Patient Reg. No.">
              </div>
              </div>
              <div class="row m-b-5"> 
                <div class="col-xs-3"> <label>Mobile No. </label></div>
                <div class="col-xs-3">
                <input name="mobile_no" class="m_input_default" id="mobile_no" onkeyup="return form_submit();" value="" type="text" placeholder="Search By Mobile No.">
              </div>
              </div>
              
              
              
              
               
               
               <div class="row m-b-5"> 
                <div class="grp" id="patient_list"> </div>
              </div>
              
              <div class="row m-b-5"> 
                <div class="col-xs-3">
                    <label> Certificate Type <span class="star">*</span></label>
                  </div>
    
				  <div class="col-xs-3">

					 <select class="" name="certificate_id" id="certificate_id">
						<option value="">Select Certificate</option>
					   <?php foreach($template_data as $key=>$type) {?>
						<option value="<?php echo $type['id']; ?>"><?php echo $type['title']; ?></option>
						<?php }?>
					   
					  </select>
					 <?php if(!empty($form_error)){ echo form_error('title'); } ?>

				  </div>
              </div>
               <!-- 10 -->
               <div class="row"> 
				<div class="col-xs-3 "></div>
                <div class="col-xs-3">
                    <button href="javascript:void(0)" class="btn-anchor" id="" onclick="return list_patient_certificate(); " >
                    <i class="fa fa-save"></i> Generate</button>
                   <a class="btn-anchor" onclick="window.location.href='<?php echo base_url(); ?>'"> <i class="fa fa-sign-out"></i> Exit
                   </a>
              </div>
          </div> <!-- row -->
         
          
</form>
     
</div> <!-- close -->

  
</section> <!-- cbranch -->
<?php
$this->load->view('include/footer');
?>
<script>

$(document).ready(function(){ 
     $.post("<?php echo base_url('certificate/get_opd_patient_list/'); ?>",{},function(result){
          $("#patient_list").html(result);
     }); 
 });

 
  
 
 function get_data(value){
    
    
   form_submit();
     
     /*if(value==0)
     {
         
          $.post('< ?php echo base_url('certificate/get_opd_patient_list/'); ?>',{},function(result){
               if(result!=''){
                    $("#patient_list").html(result);
               }

          })
         
          
     
     }
     else if(value==1)
     {

         
          $.post('< ?php echo base_url('certificate/get_ipd_patient_list/'); ?>',{},function(result){
               if(result!=''){
                    $("#patient_list").html(result);
               }

          })
          
     }
       else if(value==2)
     {
          
          $.post('< ?php echo base_url('certificate/get_ot_patient_list/'); ?>',{},function(result){
               if(result!=''){
                    $("#patient_list").html(result);
               }

          })
          
     }
       else if(value==3)
     {
          
          $.post('< ?php echo base_url('certificate/get_pathology_patient_list/'); ?>',{},function(result){
               if(result!=''){
                    $("#patient_list").html(result);
               }

          })
          
     }*/

 }
 
function form_submit(vals)
{   
   
    //var module_type = $('#module_type').val();
    var module_type = $("input[type='radio'].radioBtnClass:checked").val();
    var patient_name = $('#patient_name').val();
    var patient_reg_no = $('#patient_reg_no').val();
    var mobile_no = $('#mobile_no').val();
   
    $.ajax({
      url: "<?php echo base_url('certificate/get_module_patient_list/'); ?>",
      type: "post",
      //data: $('#certificate_list').serialize(),
      data: { module_type: module_type, patient_name : patient_name,patient_reg_no:patient_reg_no,mobile_no:mobile_no} ,
      success: function(result) 
      {
        $("#patient_list").html(result);
      }
    }); 
}

 
function list_patient_certificate()
{
  var patient_id = $('#patientid').val();
  var certificate_id = $('#certificate_id').val();
  //var certificate_id = $('#certificate_id').val();
 var type = $("input[type=radio]:checked").val();
  window.open('<?php echo base_url('certificate/list_patient_certificate?') ?>patient_id='+patient_id+'&certificate_id='+certificate_id+'&type='+type,'mywin','width=800,height=600');
}
 
$("#certificate_id").select2();
</script>

</div>

<div id="load_add_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</body>
</html>