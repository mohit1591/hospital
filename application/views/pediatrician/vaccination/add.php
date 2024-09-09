<?php
$users_data = $this->session->userdata('auth_users');
$field_list = mandatory_section_field_list(5);
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="emp_type" class="form-inline">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">


              <!-- // first row -->
              <div class="row">
                <div class="col-md-6">
                <!-- / -->
                    <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Vaccination code</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <div class="dcode"><b><?php echo $form_data['vaccination_code']; ?></b></div>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                <!-- / -->
                </div>


                <div class="col-md-6">
                  <!-- / -->
                      <div class="row m-b-5">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Vaccination Name <span class="star">*</span></label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="vaccination_name" id="vaccination_name" value="<?php echo $form_data['vaccination_name']; ?>" class="alpha_numeric_space inputFocus m_input_default" placeholder="Enter Vaccination Name">
                              <?php if(!empty($form_error)){ echo form_error('vaccination_name'); } ?>
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>


              
              <!-- // fifth row -->
              <div class="row">
                <!--<div class="col-md-6">
                
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Salt</label>
                          </div> 
                          <div class="col-md-8">
                            <input type="text" class="m_input_default" name="salt" id="salt" value="<?php echo $form_data['salt']; ?>" placeholder="Enter Salt">
                            < ?php if(!empty($form_error)){ echo form_error('salt'); } ?>
                          </div>
                        </div>
                      </div> 
                    </div> 
                </div>-->
 <input type="hidden" class="m_input_default" name="salt" id="salt" value="<?php echo $form_data['salt']; ?>" placeholder="Enter Salt">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Mfg. Company</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="manuf_company" class="m_select_btn" id="vaccination_manuf_company">
                              <option value=""> Select Mfg.Company</option>
                              <?php
                                if(!empty($manuf_company_list))
                                {
                                  foreach($manuf_company_list as $manuf_company)
                                  {
                                    ?>
                                    <option value="<?php echo $manuf_company->id; ?>" <?php if($manuf_company->id==$form_data['manuf_company']){ echo 'selected="selected"'; } ?>><?php echo $manuf_company->company_name; ?></option>
                                    <?php
                                  }
                                }
                               ?> 
                            </select>
                             <?php //if(!empty($form_error)){ echo form_error('manuf_company'); } ?>
                              <?php //if(!empty($form_error)){ echo form_error('manuf_company'); } ?>
                              <a href="javascript:void(0)" onclick=" return add_manuf_company();"  class="btn-new">
                                <i class="fa fa-plus"></i> New
                              </a>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>





            


               
         <!-- // seventh row -->
           
                <div class="row">

                <div class="col-md-6">
                  <!-- / -->

                  <!-- / -->
                </div>
            
              </div>

              

                   


                  
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>   
$(document).ready(function(){
  //get_unit();

})

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}

$('input#conversion').keypress(function(e){ 
  if (this.value.length == 0 && e.which == 48 ){
    $('#error').html('Zero not allowed');
   //return false;
   }else{
     $('#error').html('');
   }
});

 
$("#emp_type").on("submit", function(event) { 
 event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Vaccination successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Vaccination successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('pediatrician/vaccination/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        flash_session_msg(msg); 
        reload_table();
      } 
      else
      {
        $("#load_add_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
  $('#load_add_vaccination_manuf_company_modal_popup').modal('hide');
});
 

function add_manuf_company()
{
  var $modal = $('#load_add_vaccination_manuf_company_modal_popup');
  $modal.load('<?php echo base_url().'vaccination_manuf_company/add/' ?>',
  {
    //alert();
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function get_company()
{
   $.ajax({url: "<?php echo base_url(); ?>vaccination_manuf_company/manuf_company_dropdown/", 
    success: function(result)
    {
      $('#vaccination_manuf_company').html(result); 
    } 
  });
}


</script>  

 </div><!-- /.modal-content -->     
<div id="load_add_vaccination_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_vaccination_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

</div><!-- /.modal-dialog -->