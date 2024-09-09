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
                            <label>Medicine code</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <div class="dcode"><?php echo $form_data['medicine_code']; ?></div>
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
                              <label>Medicine Name<span class="star">*</span></label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="medicine_name" id="medicine_name" value="<?php echo $form_data['medicine_name']; ?>">
                              <?php if(!empty($form_error)){ echo form_error('medicine_name'); } ?>
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>


              <!-- // second row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Unit <span class="star">*</span></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="unit_id" id="unit_id">
                              <option value=""> Select Unit </option>
                              <?php
                              if(!empty($unit_list))
                              {
                                foreach($unit_list as $unit)
                                {
                                 ?>
                                   <option value="<?php echo $unit->id; ?>" <?php if($unit->id==$form_data['unit_id']){ echo 'selected="selected"'; } ?>><?php echo $unit->medicine_unit; ?></option>
                                 <?php  
                                }
                              }
                              ?>
                            </select>

                              <?php if(in_array('44',$users_data['permission']['action'])) {
                              ?>
                                   <a href="javascript:void(0)" onclick=" return add_unit();"  class="btn-new">
                                        <i class="fa fa-plus"></i> Add
                                   </a>
                              <?php } ?>
                            <?php if(!empty($form_error)){ echo form_error('unit_id'); } ?>
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
                            <label>Unit 2nd<span class="star">*</span></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="unit_second_id" id="unit_second_id">
                              <option value=""> Select Unit 2nd </option>
                              <?php
                              if(!empty($unit_list))
                              {
                                foreach($unit_list as $unit_second)
                                {
                                 ?>
                                   <option value="<?php echo $unit_second->id; ?>" <?php if($unit_second->id==$form_data['unit_second_id']){ echo 'selected="selected"'; } ?>><?php echo $unit_second->medicine_unit; ?></option>
                                 <?php  
                                }
                              }
                              ?>
                            </select>

                              <?php if(in_array('44',$users_data['permission']['action'])) {
                              ?>
                                   <a href="javascript:void(0)" onclick=" return add_unit();"  class="btn-new">
                                        <i class="fa fa-plus"></i> Add
                                   </a>
                              <?php } ?>

                            <?php if(!empty($form_error)){ echo form_error('unit_second_id'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>


              <!-- // third row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Conversion<span class="star">*</span></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" name="conversion" id="conversion" value="<?php echo $form_data['conversion'] ?>"> 
                            <?php if(!empty($form_error)){ echo form_error('conversion'); } ?>
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
                            <label>Min.Alrt</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="min_alrt" id="min_alrt" value="<?php echo $form_data['min_alrt'] ?>"> 
                            <?php //if(!empty($form_error)){ echo form_error('min_alrt'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>


              <!-- // forth row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Packing</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>">
                            <?php //if(!empty($form_error)){ echo form_error('packing'); } ?>
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
                            <label>Rack No</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="rack_no" id="rack_no">
                              <option value=""> Select Rack Number </option>
                              <?php
                                if(!empty($rack_list))
                                {
                                  foreach($rack_list as $rack)
                                  {
                                    ?>
                                    <option value="<?php echo $rack->id; ?>" <?php if($rack->id==$form_data['rack_no']){ echo 'selected="selected"'; } ?>><?php echo $rack->rack_no; ?></option>
                                    <?php
                                  }
                                }
                               ?> 
                            </select>
                            <?php //if(!empty($form_error)){ echo form_error('rack_no'); } ?>
                            <a href="javascript:void(0)" onclick=" return add_rack_no();"  class="btn-new">
                              <i class="fa fa-plus"></i> Add
                            </a>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>





              <!-- // fifth row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Salt</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="salt" id="salt" value="<?php echo $form_data['salt']; ?>">
                            <?php if(!empty($form_error)){ echo form_error('salt'); } ?>
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
                            <label>Manufacturing comp.</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="manuf_company" id="manuf_company">
                              <option value=""> Select Manufacturing Company </option>
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
                              <a href="javascript:void(0)" onclick=" return add_manuf_company();"  class="btn-new">
                                <i class="fa fa-plus"></i> Add
                              </a>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>





              <!-- // sixth row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>MRP</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="mrp" id="mrp" value="<?php echo $form_data['mrp']; ?>">
                            <?php //if(!empty($form_error)){ echo form_error('mrp'); } ?>
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
                            <label>Purchase Rate 
                                  <?php if(!empty($field_list)){
               if($field_list[0]['mandatory_field_id']==29 && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){?>         
                         <span class="star">*</span>
                    <?php 
                    }
               } ?>
                            </label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="purchase_rate" id="purchase_rate" value="<?php echo $form_data['purchase_rate']; ?>">
                            <?php if(!empty($field_list)){
                         if($field_list[0]['mandatory_field_id']=='29' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('purchase_rate'); }
                              }
                              } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>


            <div class="row">
              <div class="col-md-6">
              <!-- / -->
              <div class="row m-b-5">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-4">
                      <label>Discount(%)</label>
                    </div> <!-- 4 -->
                    <div class="col-md-8">
                      <input type="text" maxlength="10" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>">
                      <?php //if(!empty($form_error)){ echo form_error('mrp'); } ?>
                    </div> <!-- 8 -->
                  </div> <!-- innerRow -->
                </div> <!-- 12 -->
              </div> <!-- row -->
            <!-- / -->
            </div>
            </div>

             <?php //$setting_data= get_setting_value('MEDICINE_VAT'); 
             //print_r($setting_data);?>
              <!-- // sixth row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4">
                          <label><?php echo  get_setting_value('MEDICINE_VAT_NAME'); ?></label>
                        </div> <!-- 4 -->
                        <div class="col-md-8">
                          <input type="text" maxlength="10" name="vat" id="vat" value="<?php echo $form_data['vat']; ?>">
                          <?php //if(!empty($form_error)){ echo form_error('vat'); } ?>
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
                          <label>Status</label>
                        </div> <!-- 4 -->
                        <div class="col-md-8">
                          <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active 
                          <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="status" value="0" /> Inactive 
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
function get_state(country_id)
{ 
  var city_id = $('#city_id').val();
  $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
    success: function(result)
    {
      $('#state_id').html(result); 
    } 
  });
  get_city(city_id); 
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
 
$("#emp_type").on("submit", function(event) { 
 event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Medicine successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Medicine successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('medicine_entry/'); ?>"+path,
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
  $('#load_add_unit_modal_popup').modal('hide');
});
 

function add_unit()
{

  var $modal = $('#load_add_medicine_unit_modal_popup');
  $modal.load('<?php echo base_url().'medicine_unit/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}

function add_rack_no()
{
  var $modal = $('#load_add_medicine_rack_modal_popup');
  $modal.load('<?php echo base_url().'medicine_rack/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function add_manuf_company()
{
  var $modal = $('#load_add_manuf_company_modal_popup');
  $modal.load('<?php echo base_url().'manuf_company/add/' ?>',
  {
    //alert();
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
function get_unit()
{
   $.ajax({url: "<?php echo base_url(); ?>medicine_unit/medicine_unit_dropdown/", 
    success: function(result)
    {
     
      $('#unit_id').html(result); 
      $('#unit_second_id').html(result);
    } 
  });
}
function get_rack()
{
   $.ajax({url: "<?php echo base_url(); ?>medicine_rack/medicine_rack_dropdown/", 
    success: function(result)
    {
      $('#rack_no').html(result); 
    } 
  });
}
function get_company()
{
   $.ajax({url: "<?php echo base_url(); ?>manuf_company/manuf_company_dropdown/", 
    success: function(result)
    {
      $('#manuf_company').html(result); 
    } 
  });
}

function get_specilization()
{
   $.ajax({url: "<?php echo base_url(); ?>specialization/specialization_dropdown/", 
    success: function(result)
    {
      $('#specilization_id').html(result); 
    } 
  });
}


</script>  
<div id="load_add_manuf_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
 </div><!-- /.modal-content -->     
<div id="load_add_medicine_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_medicine_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

</div><!-- /.modal-dialog -->