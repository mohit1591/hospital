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
<form id="patient_form" name="ptaient_form" action="<?php echo current_url(); ?>" method="post" enctype="multipart/form-data">
    <div class="profile-master">
    
        <div class="pro-left">
            <input type="hidden" name="pack_id" id="pack_id" value="<?php echo $form_data['pack_id']; ?>" />
          
                <div class="row m-b-5 m-t-6">
                    <div class="col-md-5">
                        <strong> Package Name <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">

                        <input type="text"  name="package_name" id="package_name" value="<?php echo $form_data['package_name']; ?>" class="alpha_numeric_space">
                                  <?php if(!empty($form_error)){ echo form_error('package_name'); } ?>
                    </div>
                </div> <!-- row -->

                <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Package Cost <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                        <input type="text"  readonly=""  name="amount" id="amount" class="price_float" value="<?php echo $form_data['amount']; ?>">
                        <input type="hidden"  name="hidden_amount" id="hidden_amount" class="price_float">
                        <?php if(!empty($form_error)){ echo form_error('amount'); } ?>
                    </div>
                </div> <!-- row -->
                 <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Particulars <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                         <select name="particular_id" id="particular_id" class="w-150px" onchange="return get_particular(this.value)">
                              <option value="">Select Particular</option>
                              <?php
                                 
                                  
                                   $particular_ids = $this->session->userdata('particular_ids');
                                   if(empty($particular_ids)){
                                        $particular_ids = array();
                                   }
                                   if(!empty($particulars))
                                   {
                                        foreach($particulars as $particular)
                                        {  
                                             if(!in_array($particular->id,$particular_ids))
                                             {
                                               
                              ?>   
                                                  <option value="<?php echo $particular->id; ?>" '.$selected_particular.'><?php echo $particular->particular; ?></option>
                              <?php          }
                                        }
                                     
                                   }
                                   
                              ?>
                              </select>
                              <a href="javascript:void(0)" onclick="particular_modal()" class="btn-new"> New</a>
                              <?php if(!empty($form_error)){ echo form_error('particular_id'); } ?>
                              <a href="javascript:void(0)" class="btn-new m-b-2" id="addAll" disabled="disabled" onclick="return get_selected_particular();" > <i class="fa fa-plus"></i> Add </a>
                    </div>
                </div> <!-- row -->
                <div class="row m-b-5">
                    <div class="col-md-5">
                        <strong>Amount <span class="star">*</span></strong>
                    </div>
                    <div class="col-md-7">
                         <input type="text"   name="particular_amount" id="particular_amount" class="price_float" value="<?php echo $form_data['particular_amount']; ?>">
                        <?php if(!empty($form_error)){ echo form_error('particular_amount'); } ?>
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
             
           </div> <!-- pro-left -->

        <div class="pro-right">

            <div class="pframe2">
                <table class="table table-bordered table-striped" id="particular_list">
                    <thead class="bg-theme">
                        <tr>
                            <th align="center" width="40"><input type="checkbox"  name="addparticularselectAll" class="" id="addparticularselectAll" value=""></th>
                            <th>S.No</th>
                            <th>Particulars</th>
                            <th>Amount</th>
                            
                        </tr>
                    </thead>

                    <tbody id="selected_particular">
                          <tr><td colspan="4" ><?php if(!empty($form_error)){ echo form_error('test'); } ?></td></tr>                    
                    </tbody>

                </table>

            </div> <!-- pframe2 -->

            <div class="pframe-right">
                <div class="btns">
                
                  <a href="javascript:void(0);" class="btn-new m-b-2" id="deleteAll" onclick="return deleteChildCheckboxValues();"> <i class="fa fa-trash"></i> Delete </a>
                  <button type="submit" class="btn-save m-b-2" name="" id="" ><i class="fa fa-floppy-o"></i> Save</button>
                  <button type="button" class="btn-save m-b-2" name="" id="" onclick="window.location.href='<?php echo base_url('ipd_packages'); ?>'"><i class="fa fa-sign-out"></i> Exit</button>
                </div>
            </div>

        </div> <!-- pro-right onclick="return addparticularcheckboxValues();"-->

</div> <!-- profile-master2 -->



<script type="text/javascript"> 
function get_selected_particular(ids)
{
    var particuler_id = $('#particular_id').val();  
    var particuler_text = $("#particular_id option:selected").text();  
    var particular_amount = $('#particular_amount').val();  
     $.ajax({  
      type: "POST",
      url: "<?php echo base_url('ipd_packages/add_package_particuler') ?>",
      data: 'particular_id='+particuler_id+'&particuler_text='+ particuler_text+'&particular_amount='+particular_amount,
      success: function (result) 
      {
         selected_particular_list();
         clc_package_price(); 
      }
 });
}


function selected_particular_list()
{
  $.ajax({ 
      url: "<?php echo base_url('ipd_packages/get_selected_particular') ?>", 
      success: function (result) 
      {
         $('#particular_list tbody').html(result); 
         
      }
    });  
}

function clc_package_price()
{
  $.ajax({ 
      url: "<?php echo base_url('ipd_packages/clc_package_price') ?>", 
      success: function (result) 
      {
         $('#amount').val(result);  
      }
    });  
}

function get_particular(val)
{
     if(val!='')
     {  
          $("#addAll").removeAttr("disabled");
          $.post('<?php echo base_url(); ?>ipd_perticular/get_particulars/',{'particular_id':val},function(result){
               if(result!='')
               {
                    data = JSON.parse(result);
                    var charge = data[0].charge; 
                    $("#particular_amount").val(charge);
               }
               else
               {
                    $("#particular_amount").val("");
               }
             
          });
     }
     else
     {
          $("#particular_amount").val("");
          $("#addAll").attr("disabled");
     }
} 

 
function checkboxValues() 
{    
    $('tbody#particular_add tr#nodata').remove();
     var allVals = [];

     $('.checklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });
     allparticularadd(allVals);
}
//for delete the selected child test list
function deleteChildCheckboxValues() 
{    
    
     var allVals = [];

     $('.particularchecklist:checkbox').each(function() 
     {
         
       if($(this).prop('checked')==true)
       {
            allVals.push($(this).val());
       } 
   
     });

     allparticulardelete(allVals);
}

function particular_modal()
{
    var $modal = $('#load_add_ipd_perticular_modal_popup');
    $modal.load('<?php echo base_url().'ipd_perticular/add/' ?>',
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
} 
//delete the selected child list
function allparticulardelete(allVals)
{  
      if(allVals!=""){
          $.post('<?php echo base_url(); ?>ipd_packages/deletealllistedparticular/',{row_id: allVals},function(result)
            {
              selected_particular_list();
              clc_package_price(); 
            });
          
     }
}

selected_particular_list();

function save_added_particular(allVals){

     var packageName = $("#package_name").val();
     var amount = $("#amount").val();
     //var status = $("#status").val();
     var checked_val = $('input[name=status]');
     var status = checked_val.filter(':checked').val();
     var ids = $('#pack_id').val();
   

     if(ids!="" && !isNaN(ids)){ 
          var path = 'edit/'+ids;
          var msg = 'Package successfully updated.';
     }else{
          var path = 'add/';
          var msg = 'Package successfully created.';
     }   
    
     var data = new FormData();
                data.append("package_name",packageName);
                data.append("amount",amount);
                data.append("row_id",allVals);
                data.append("pack_id",ids);
                data.append("status",status);
     $.ajax({
          url: "<?php echo base_url(); ?>ipd_packages/"+path,
          data:data,
          type:"POST",
          crossDomain: true,
          processData: false,
          contentType: false,
          
          success: function(data){

                if(data!=='1'){

                    $('#hello').html(data);

               }else{
                    window.location.href='<?php echo base_url('ipd_packages'); ?>';
               }              
          }

     });
     
    
}

 
  
  $('#form_submit').on("click",function(){
       $('#patient_form').submit();
  })
  
  
$(document).ready(function(){
  $('#load_add_ipd_perticular_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});
</script> 








<div id="load_add_ipd_perticular_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
</form>

</section> <!-- section close -->
<?php
$this->load->view('include/footer');
?>
 
</div><!-- container-fluid -->
</body>
</html>