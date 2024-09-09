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


              <!-- // second row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Unit 1 <span class="star">*</span>
                               <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a bunddle of vaccine</span>
                              </a>
                              </sup>
                            </label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="unit_id" class="m_select_btn" id="unit_id">
                              <option value=""> Select Unit </option>
                              <?php
                              if(!empty($unit_list))
                              {
                                foreach($unit_list as $unit)
                                {
                                ?>
                                <option value="<?php echo $unit->id; ?>" <?php if($unit->id==$form_data['unit_id']){ echo 'selected="selected"'; } ?>><?php echo $unit->vaccination_unit; ?>
                                </option>
                                   <?php  
                                  }
                              }
                              ?>
                            </select>

                              <?php if(in_array('44',$users_data['permission']['action'])) {
                              ?>
                                   <a href="javascript:void(0)" onclick=" return add_unit();"  class="btn-new">
                                        <i class="fa fa-plus"></i> New
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
                            <label>Unit 2<span class="star">*</span>
                            <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a vaccine tablet from open bunddle</span>
                              </a>
                              </sup>
                            </label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="unit_second_id" class="m_select_btn" id="unit_second_id">
                              <option value=""> Select Unit  </option>
                              <?php
                              if(!empty($unit_list))
                              {
                                foreach($unit_list as $unit_second)
                                {
                                 ?>
                                   <option value="<?php echo $unit_second->id; ?>" <?php if($unit_second->id==$form_data['unit_second_id']){ echo 'selected="selected"'; } ?>><?php echo $unit_second->vaccination_unit; ?></option>
                                 <?php  
                                }
                              }
                              ?>
                            </select>

                              <?php if(in_array('44',$users_data['permission']['action'])) {
                              ?>
                                   <a href="javascript:void(0)" onclick=" return add_unit();"  class="btn-new">
                                       <i class="fa fa-plus"></i> New
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
                            <input type="text" class="m_input_default" name="conversion" id="conversion" value="<?php echo $form_data['conversion'] ?>" onkeypress="return isNumberKey(event);" placeholder="Enter Conversion"> 
                            <?php if(!empty($form_error)){ echo form_error('conversion'); } ?>
                            <div id="error" class="text-danger"></div>
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
                            <input type="text" class="m_input_default" maxlength="10" name="min_alrt" id="min_alrt" value="<?php echo $form_data['min_alrt'] ?>" placeholder="Enter Min Alrt"> 
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
                            <input type="text" class="m_input_default" maxlength="10" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>" placeholder="Enter Packing">
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
                            <label>Rack No.</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="rack_no" class="m_select_btn" id="rack_no">
                              <option value=""> Select Rack No. </option>
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
                              <i class="fa fa-plus"></i> New
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
                            <input type="text" class="m_input_default" name="salt" id="salt" value="<?php echo $form_data['salt']; ?>" placeholder="Enter Salt">
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
                            <label>Mfg.Company</label>
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
                            <input type="text" maxlength="10" name="mrp" id="mrp" value="<?php echo $form_data['mrp']; ?>" class="price_float m_input_default" placeholder="Enter MRP">
                            <?php //if(!empty($form_error)){ echo form_error('mrp'); } ?>
                            <div class="text-danger" id="mrp_error"></div>
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
                            <input type="text" maxlength="10" name="purchase_rate" id="purchase_rate" class="price_float m_input_default" value="<?php echo $form_data['purchase_rate']; ?>" placeholder="Enter Purchase Rate">
                        <?php if(!empty($field_list)){
                            //  echo 'dd';
                         if($field_list[0]['mandatory_field_id']=='29' && $field_list[0]['mandatory_branch_id']==$users_data['parent_id']){
                              if(!empty($form_error)){ echo form_error('purchase_rate'); }
                              }
                            else
                            {
                            echo form_error('purchase_rate');
                            }
                              } ?>
                               <div class="text-danger" id="purchase_error"></div>
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
                      <label>Discount (%)</label>
                    </div> <!-- 4 -->
                    <div class="col-md-8">
                      <input type="text" maxlength="10" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>" class="price_float m_input_default" placeholder="Enter Discount (%)">
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
                              <label>HSN No.</label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="hsn_no" id="hsn_no" value="<?php echo $form_data['hsn_no']; ?>" class="alpha_numeric_space m_input_default" placeholder="Enter HSN No.">
                             
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
                <!--<div class="col-md-6">
                  <!-- / -->
                 <!-- <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4">
                          <label><?php //echo  get_setting_value('MEDICINE_VAT_NAME'); ?></label>
                        </div> <!-- 4 -->
                       <!-- <div class="col-md-8">
                          <input type="text" maxlength="10" name="vat" id="vat" value="<?php //echo $form_data['vat']; ?>" placeholder="Enter gst" class="price_float">
                          <?php //if(!empty($form_error)){ echo form_error('vat'); } ?>
                        </div> <!-- 8 -->
                     <!-- </div> <!-- innerRow -->
                    <!--</div> <!-- 12 -->
                 <!-- </div> <!-- row -->
                  <!-- / -->
                <!--</div>-->

                  <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4">
                          <label>CGST (%)</label>
                        </div> <!-- 4 -->
                        <div class="col-md-8">
                          <input type="text" maxlength="10" name="cgst" id="cgst" value="<?php echo $form_data['cgst']; ?>" placeholder="Enter CGST (%)" class="price_float m_input_default">
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


			<div class="row">
				<div class="col-md-6">
				<!-- / -->
					<div class="row m-b-5">
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-4">
								<label>SGST (%)</label>
								</div> <!-- 4 -->
								<div class="col-md-8">
								<input type="text" maxlength="10" name="sgst" id="sgst" value="<?php echo $form_data['sgst']; ?>" placeholder="Enter SGST (%)" class="price_float m_input_default">
								<?php //if(!empty($form_error)){ echo form_error('vat'); } ?>
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
                          <label>IGST (%)</label>
                        </div> <!-- 4 -->
                        <div class="col-md-8">
                          <input type="text" maxlength="10" name="igst" id="igst" value="<?php echo $form_data['igst']; ?>" placeholder="Enter IGST (%)" class="price_float m_input_default">
                          <?php //if(!empty($form_error)){ echo form_error('vat'); } ?>
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
//  $("input").keyup(function() {
//     var purchas_value=$('#purchase_rate').val();
//     var mrp=$('#mrp').val();
//       if(mrp!=''&& purchas_value!=''){
//       if(mrp<purchas_value || purchas_value.length>mrp.length){
//       $('#mrp_error').html('MRP value is greater and equal to purchase rate');
//       }else if(purchas_value>mrp || mrp.length<purchas_value.length) {
//       $('#purchase_error').html('Purchase rate must be less and equal to MRP');
//       }else{
//       $('#purchase_error').html('');
//       $('#mrp_error').html('');
//       }
//     }


// });

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
    url: "<?php echo base_url('vaccination_entry/'); ?>"+path,
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
 

function add_unit()
{

  var $modal = $('#load_add_vaccination_unit_modal_popup');
  $modal.load('<?php echo base_url().'vaccination_unit/add/' ?>',
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
  var $modal = $('#load_add_vaccination_rack_modal_popup');
  $modal.load('<?php echo base_url().'vaccination_rack/add/' ?>',
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
function get_unit()
{
   $.ajax({url: "<?php echo base_url(); ?>vaccination_unit/vaccination_unit_dropdown/", 
    success: function(result)
    {
     
      $('#unit_id').html(result); 
      $('#unit_second_id').html(result);
    } 
  });
}
function get_rack()
{
   $.ajax({url: "<?php echo base_url(); ?>vaccination_rack/vaccination_rack_dropdown/", 
    success: function(result)
    {
      $('#rack_no').html(result); 
    } 
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

function get_specilization()
{
   $.ajax({url: "<?php echo base_url(); ?>specialization/specialization_dropdown/", 
    success: function(result)
    {
      $('#specilization_id').html(result); 
    } 
  });
}

$('#vat').keyup(function(){
  if ($(this).val() > 100){
      alert('<?php echo get_setting_value('MEDICINE_VAT_NAME');?> should be less then 100' );
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});

$('#discount').keyup(function(){
  if ($(this).val() > 100){
      alert('Discount should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});

$('#cgst').keyup(function(){
  if ($(this).val() > 100){
      alert('CGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
$('#sgst').keyup(function(){
  if ($(this).val() > 100){
      alert('SGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
$('#igst').keyup(function(){
  if ($(this).val() > 100){
      alert('IGST should be less then 100');
    //$('#error_msg_vat').html('Gst should be less then 100');
  }
});
</script>  

 </div><!-- /.modal-content -->     
<div id="load_add_vaccination_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_vaccination_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

</div><!-- /.modal-dialog -->