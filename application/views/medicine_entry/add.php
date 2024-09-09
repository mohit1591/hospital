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
                              <label>Medicine Name <span class="star">*</span></label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="medicine_name" id="medicine_name" value="<?php echo $form_data['medicine_name']; ?>" class=" inputFocus m_input_default Cap_medicine_entry" placeholder="Enter Medicine Name" tabindex="1">
                              <?php if(!empty($form_error)){ echo form_error('medicine_name'); } ?>
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
                            <label>Medicine code</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <div class="dcode"><b><?php echo $form_data['medicine_code']; ?></b></div>
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
                            <label>Unit 1 <span class="star">*</span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a bunddle of medicine</span>
                              </a>
                              </sup></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="unit_id" class="m_select_btn" id="unit_id" tabindex="2">
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
                            <label>Unit 2<span class="star">*</span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is a medicine tablet from open bunddle</span>
                              </a>
                              </sup></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="unit_second_id" class="m_select_btn" id="unit_second_id" tabindex="3">
                              <option value=""> Select Unit  </option>
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

              <!-- medicine type and barcode -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Medicine Type </label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <select name="medicine_type" class="m_select_btn" id="medicine_type" tabindex="4">
                              <option value="-1"> Select Type </option>
                              <option <?php if(empty($form_data['medicine_type'])){ echo 'selected="selected"'; } ?> value="0">Normal</option>
                              <?php
                              if(!empty($medicine_type_list))
                              {
                                foreach($medicine_type_list as $type_list)
                                {
                                 ?>
                                   <option value="<?php echo $type_list->id; ?>" <?php if($type_list->id==$form_data['medicine_type']){ echo 'selected="selected"'; } ?>><?php echo $type_list->medicine_type_name; ?></option>
                                 <?php  
                                }
                              }
                              ?>
                            </select>

                              <?php if(in_array('44',$users_data['permission']['action'])) {
                              ?>
                                   <a href="javascript:void(0)" onclick=" return add_medicine_type();"  class="btn-new">
                                        <i class="fa fa-plus"></i> New
                                   </a>
                              <?php } ?>
                            <?php if(!empty($form_error)){ echo form_error('medicine_type'); } ?>
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
                            <label>Barcode</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                          
                          <input type="text" class="m_input_default" name="bar_code" id="bar_code" value="<?php echo $form_data['bar_code'] ?>"  placeholder="Enter Barcode" tabindex="5"> 
                          <?php if(!empty($form_error)){ echo form_error('bar_code'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>
              <!-- end medicine type and barcode-->
              <!-- // third row -->
              <div class="row">
                <div class="col-md-6">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Conversion<span class="star">*</span><sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Conversion is number of unit in a strip. If a strp of 10 tablet then conversion will be 10.</span>
                              </a>
                              </sup></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" tabindex="6" class="m_input_default" name="conversion" id="conversion" value="<?php echo $form_data['conversion'] ?>" onkeypress="return isNumberKey(event);" placeholder="Enter Conversion"> 
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
                            <label>Min.Alert <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is the quantity on which a notification in red color will generated in stock when the stock quantity reached to this min alert quantity.</span>
                              </a>
                              </sup></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" tabindex="7" class="m_input_default" maxlength="10" name="min_alrt" id="min_alrt" value="<?php echo $form_data['min_alrt'] ?>" placeholder="Enter Min Alert Qty for Unit 2"> 
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
                            <label>Packing <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>Packing Consist of the the dose. Eg. 250 mg Tablet or 300 ml Syrup.</span></a></label>
                          </div> <!-- 4 --> 
                          <div class="col-md-8">
                            <input type="text" class="m_input_default" maxlength="10" tabindex="8" name="packing" id="packing" value="<?php echo $form_data['packing']; ?>" placeholder="Enter Packing">
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
                            <select name="rack_no" class="m_select_btn" id="rack_no" tabindex="9">
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
                            <input type="text" class="m_input_default" name="salt" id="medicine_salt" value="<?php echo $form_data['salt']; ?>" tabindex="10" placeholder="Enter Salt">

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
                            <select name="manuf_company" class="m_select_btn" id="manuf_company" tabindex="11">
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
                            <label>MRP <span class="star">*</span></label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="mrp" id="mrp" value="<?php echo $form_data['mrp']; ?>" class="price_float m_input_default" placeholder="Enter MRP" tabindex="12">
                            <?php if(!empty($form_error)){ echo form_error('mrp'); } ?>
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
                            <label>Purchase Rate <span class="star">*</span> </label>

                          </div> <!-- 4 -->

                          <div class="col-md-8">
                            <input type="text" maxlength="10" name="purchase_rate" id="purchase_rate" class="price_float m_input_default" value="<?php echo $form_data['purchase_rate']; ?>" placeholder="Enter Purchase Rate" tabindex="13">
                             <?php 
                              if(!empty($form_error)){ echo form_error('purchase_rate'); }
                              ?>  
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
                      <label>Discount <?php if($discount_setting==1){ ?>(₹) <?php  }else{ ?>(%) <?php } ?></label>
                    </div> <!-- 4 -->
                    <div class="col-md-8">
                      <input type="text" maxlength="10" name="discount" id="discount" value="<?php echo $form_data['discount']; ?>" class="price_float m_input_default" placeholder="Enter Discount (Only Numeric)" tabindex="14">
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
                              <input type="text" name="hsn_no" id="medicine_hsn_no" value="<?php echo $form_data['hsn_no']; ?>" class="alpha_numeric_space m_input_default" placeholder="Enter HSN No." tabindex="15">
                             
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
                          <input type="text" maxlength="10" name="cgst" id="cgst" value="<?php echo $form_data['cgst']; ?>" placeholder="Enter CGST (Only Numeric)" class="price_float m_input_default" tabindex="16">
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
								<input type="text" maxlength="10" name="sgst" id="sgst" value="<?php echo $form_data['sgst']; ?>" placeholder="Enter SGST (Only Numeric)" class="price_float m_input_default" tabindex="17">
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
                          <input type="text" maxlength="10" name="igst" id="igst" value="<?php echo $form_data['igst']; ?>" placeholder="Enter IGST (Only Numeric)" class="price_float m_input_default" tabindex="18">
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
               <button type="submit" tabindex="19"  class="btn-update" name="submit">Save</button>
               <button type="button" tabindex="20" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>   
$(document).ready(function(){
  //get_unit();

})

  $(function () {
    var getData = function (request, response) { 
      $.getJSON(
      "<?php echo base_url('medicine_entry/get_salt_vals/'); ?>" + request.term,
      function (data) {
      response(data);
      });
    };

    var selectItem = function (event, ui) { 

        $("#medicine_salt").val(ui.item.value);
        //$("#medicine_salt_id").val(ui.item.id);
        return false;
    }

    $("#medicine_salt").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
        //$("#medicine_types").val("").css("display", 2);
        }
    });
  });


/* auto complete code for hsn no */
  $(function () {
    var getData = function (request, response) { 
      $.getJSON(
      "<?php echo base_url('medicine_entry/get_hsn_vals/'); ?>" + request.term,
      function (data) {
      response(data);
      });
    };

    var selectItem = function (event, ui) { 

        $("#medicine_hsn_no").val(ui.item.value);
        //$("#medicine_salt_id").val(ui.item.id);
        return false;
    }

    $("#medicine_hsn_no").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
        //$("#medicine_types").val("").css("display", 2);
        }
    });
  });

/* auto complete code for hsn no */


/* $("input").keyup(function() {
    var purchas_value=$('#purchase_rate').val();
    var mrp=$('#mrp').val();
      if(mrp!=''&& purchas_value!=''){
      if(mrp<purchas_value || purchas_value.length>mrp.length){
      $('#mrp_error').html('MRP value is greater and equal to purchase rate');
      }else if(purchas_value>mrp || mrp.length<purchas_value.length) {
      $('#purchase_error').html('Purchase rate must be less and equal to MRP');
      }else{
      $('#purchase_error').html('');
      $('#mrp_error').html('');
      }
    }


}); */

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
    var msg = 'Medicine updated successfully.';
  }
  else
  {
    var path = 'add/';
    var msg = 'New Medicine added successfully.';
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

$("button[data-number=1]").click(function(){
  $('#load_add_medicine_type_modal_popup').modal('hide');
});

function add_medicine_type()
{

  var $modal = $('#load_add_medicine_type_modal_popup');
  $modal.load('<?php echo base_url().'medicine_type/add/' ?>',
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
$(".Cap_medicine_entry").on('keyup', function(){

   var str = $('.Cap_medicine_entry').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.Cap_medicine_entry').val(part_val.join(" "));
  
  });
</script>  
<div id="load_add_medicine_type_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 
<div id="load_add_manuf_company_modal_popup" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false"></div>
 </div><!-- /.modal-content -->     
<div id="load_add_medicine_unit_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

<div id="load_add_medicine_rack_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div> 

</div><!-- /.modal-dialog -->