<?php
$users_data = $this->session->userdata('auth_users');
//print_r($form_data);
?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="vendor_type_new" class="form-inline">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4> 
                </div>
            <div class="modal-body">


              <!-- // first row -->
              <div class="row">
                <div class="col-md-12">
                <!-- / -->
                    <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Vendor Code</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <div class="dcode"><b><?php echo $form_data['vendor_id']; ?></b></div>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                <!-- / -->
                </div>
                </div>

              <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                      <div class="row m-b-5">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Vendor Name<span class="star">*</span></label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="name"  class="alpha_numeric_space m_name inputFocus txt_firstCap" id="" value="<?php echo $form_data['name']; ?>">
                              <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                      <div class="row m-b-5">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Vendor GST No.</label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="vendor_gst"   id="vendor_gst" value="<?php echo $form_data['vendor_gst']; ?>">
                              <?php if(!empty($form_error)){ echo form_error('vendor_gst'); } ?>
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                      <div class="row m-b-5">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Vendor DL No.1</label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="vendor_dl_1"   id="vendor_dl_1" value="<?php echo $form_data['vendor_dl_1']; ?>">
                             
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                      <div class="row m-b-5">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="col-md-4">
                              <label>Vendor DL No.2</label>
                            </div> <!-- 4 -->
                            <div class="col-md-8">
                              <input type="text" name="vendor_dl_2"   id="vendor_dl_2" value="<?php echo $form_data['vendor_dl_2']; ?>">
                             
                            </div> <!-- 8 -->
                          </div> <!-- innerRow -->
                        </div> <!-- 12 -->
                      </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>
             <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                  <div class="row m-b-5">
                    <div class="col-md-12">
                      <div class="row">
                        <div class="col-md-4">
                          <label>Type <span class="star">*</span></label>
                        </div> <!-- 4 -->
                        <div class="col-md-8">
                          <input type="radio" name="vendor_type" <?php echo 'checked="checked"'; ?> id="vendor_type" value="5" /> Ambulance
                        </div> <!-- 8 -->
                      </div> <!-- innerRow -->
                    </div> <!-- 12 -->
                  </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>


              <!-- // third row -->
              <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Mobile No.</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text" name="" readonly="readonly" value="<?php echo $form_data['country_code'];?>" class="country_code" placeholder="+91"> 
                            <input type="number" maxlength="10"  id="mobile" name="mobile"  data-toggle="tooltip"  title="Allow only numeric." class="tooltip-text tool_tip numeric number" placeholder="eg.9897221234" value="<?php echo $form_data['mobile']; ?>">
                            
                           
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
                </div>

                <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Address1</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text"  name="address" id="address" value="<?php echo $form_data['address'] ?>"> 
                            <?php //if(!empty($form_error)){ echo form_error('min_alrt'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>
              
               <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Address2</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text"  name="address2" id="address" value="<?php echo $form_data['address2'] ?>"> 
                            <?php //if(!empty($form_error)){ echo form_error('min_alrt'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>
              
               <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Address3</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text"  name="address3" id="address" value="<?php echo $form_data['address3'] ?>"> 
                            <?php //if(!empty($form_error)){ echo form_error('min_alrt'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>

               <div class="row">
                <div class="col-md-12">
                  <!-- / -->
                  <div class="row m-b-5">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-4">
                            <label>Email</label>
                          </div> <!-- 4 -->
                          <div class="col-md-8">
                            <input type="text"  name="vendor_email" id="vendor_email" value="<?php echo $form_data['vendor_email'] ?>"> 
                            <?php if(!empty($form_error)){ echo form_error('vendor_email'); } ?>
                          </div> <!-- 8 -->
                        </div> <!-- innerRow -->
                      </div> <!-- 12 -->
                    </div> <!-- row -->
                  <!-- / -->
                </div>
              </div>

       <!-- // seventh row -->
              <div class="row">
                <div class="col-md-12">
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
              

                   


                  
            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <button type="submit"  class="btn-update" name="submit">Save</button>
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     

<script>   



function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#vendor_type_new").on("submit", function(event) { 



  var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>';  
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Vendor successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Vendor successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    /* url: "< ?php echo base_url(); ?>"+uri_seg+'/'+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) { 
      if(result==1)
      {
        $('#load_add_ven_modal_popup').modal('hide');
        flash_session_msg(msg); 
        get_vendor();
        reload_table();*/

    url: "<?php echo base_url(); ?>"+uri_seg+'/'+path+'/'+'<?php echo $type;?>',
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ven_modal_popup').modal('hide');
        flash_session_msg(msg); 
        get_vendor('<?php echo $type;?>');
        reload_table();

      
        
        
      } 
      else
      {
        $("#load_add_ven_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}



  function get_vendor(type_date)
  {

     var uri_seg='ambulance/<?php echo $this->uri->segment(2);?>'; 

      $.ajax({url: "<?php echo base_url(); ?>"+uri_seg+'/vendor_dropdown/'+type_date, 
      success: function(result)
      {
      $('#vendor_id').html(result); 
      } 
      });
  }


$(".txt_firstCap").on('keyup', function(){
   var str = $('.txt_firstCap').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }    
   $('.txt_firstCap').val(part_val.join(" ")); 
  });


$(document).ready(function(){
    $("#name").keypress(function(event){
        var inputValue = event.which;
        if(!(inputValue >= 65 && inputValue <= 120) && (inputValue != 32 && inputValue != 0)) { 
            event.preventDefault(); 
        }
    });
});


</script>  
</div><!-- /.modal-dialog -->