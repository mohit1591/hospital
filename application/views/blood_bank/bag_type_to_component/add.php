<div class="modal-dialog emp-add-add modal-80" style="margin-top:20px;">
  <div class="overlay-loader" id="overlay-loader">
    <div><img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader"></div>
  </div>
  <div class="modal-content"> 
  <form  id="bag_component" class="form-inline">
  <input type="hidden" name="btc_id" id="btc_id" value="<?php echo $btc_id; ?>" /> 
  <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      <div class="modal-body">  
        <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Bag Type  <?php if($btc_id==0){  ?><span class="star">*</span> <?php } ?></label>
                </div>
                <div class="col-md-7">
                <?php if($btc_id==0) {  ?>
                    <select name="bag_type_id" id="bag_type_id"  >
                      <option value="">Select Bag Type</option>
                      <?php foreach($bag_type_list as $bag_type) {  ?>

                      <option <?php if($btc_data!="empty"){ if($btc_data[0]->bag_type_id==$bag_type->id){ echo "selected=selected"; } } ?> value="<?php echo $bag_type->id;?>" ><?php echo $bag_type->bag_type; ?></option>
                      
                      <?php }?>
                    </select>
                <?php } else { ?>
                  <?php  echo $btc_data[0]->bag_type; ?>
                    <input type="hidden" value="<?php echo $btc_data[0]->bag_type_id; ?>" name="bag_type_id" id="bag_type_id">
                <?php } ?>    

                    <span id="bag_type_error" ></span>  
                </div>
                  
              </div>
              
            </div> <!-- 12 -->
          </div> <!-- row --> 
      
<?php
if($btc_data!="empty")
{
  $dt=array();
  foreach($btc_data as $data)
  {
    array_push($dt, $data->component_id);
  }
  
}
else
{
  $dt=array();
}
?>

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Component <span class="star">*</span></label>
                </div>
                <div class="col-md-7 bag-multiselect">
              <select name="component_id[]" class="multi_type" multiple="multiple" onchange="validate_components(this.value); ">
                <?php
                    $component_array=array();
                   if(!empty($component_list))
                   {
                     foreach($component_list as $component_list_id)
                      {
                        $component_array[$component_list_id->id]=$component_list_id->component;

                        if(in_array($component_list_id->id, $dt))
                          echo '<option  selected  value='.$component_list_id->id.'>'.$component_list_id->component.'</option> ';
                        else
                          echo '<option   value='.$component_list_id->id.'>'.$component_list_id->component.'</option> ';
                      }
                   }
                ?> 
                </select>
                <?php if($btc_id > 0) { $vl=1;} else {$vl='';} ?>
                <input type="hidden" value="<?php echo $vl; ?>" name="validate_component" id="validate_component">
                <span id="component_error" ></span> 
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->    
         
          <div class="row" id="compo_label_div">
            <div class="col-md-12" id="compo_labelss">
              
                    
                    <table  border=0 style="border-color:#ccc;border:1px solid #ccc;" ><tr><td style="padding:4px;border:1px solid #ccc;"><b>Component</b></td><td style="padding:4px;border:1px solid #ccc;"><b>Duration</b></td><td style="padding:4px;border:1px solid #ccc;"><b>Interval</b></td></tr><tbody id="compo_label">
                    <?php if($btc_data!="empty") {  ?>
                    <?php foreach($btc_data as $dat){ ?>
                      <tr id="tr_row_<?php echo $dat->component_id; ?>" ><td style="padding:4px;border:1px solid #ccc;text-align:left;"><b><?php echo $component_array[$dat->component_id]; ?></b></td><td style="padding:4px;border:1px solid #ccc;"><input type="text" class='price_float w-40px' name="expiry_date[<?php echo $dat->component_id; ?>]" id="expiry_time_<?php echo $dat->component_id; ?>" value="<?php echo $dat->expiry_time; ?>"  ></td><td style="padding:4px;border:1px solid #ccc;"><select name="interval[<?php echo $dat->component_id; ?>]" id="interval_<?php echo $dat->component_id; ?>">
                  <option value="">Select Interval</option>
               <option <?php if(!empty($dat->interval_time)) if($dat->interval_time == 1 ) echo 'selected'  ?> value="1">Hours</option>
               <option <?php if(!empty($dat->interval_time)) if($dat->interval_time == 2 ) echo 'selected'  ?> value="2">Days</option>
               <option <?php if(!empty($dat->interval_time)) if($dat->interval_time == 3 ) echo 'selected'  ?> value="3">Week</option>
               <option <?php if(!empty($dat->interval_time)) if($dat->interval_time == 4 ) echo 'selected'  ?> value="4">Month</option>
               </select></td></tr>
                    <?php } }?>
                    </tbody></table>
                
            </div> <!-- 12 -->
          </div> <!-- row --> 
           
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                    <label></label>
                </div>
                <div class="col-md-7">
                     <input type="hidden"  <?php if(isset($btc_data[0]->status) && $btc_data[0]->status==1){echo 'checked'; }?> class="" name="status"  id="status" checked="checked" value="1" /> 
                     <input type="hidden"  class=""   <?php if(isset($btc_data[0]->status) && $btc_data[0]->status==0){echo 'checked'; }?> name="status"  id="status" value="0" />    
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
      <?php if($btc_id==0){ ?>
        <input type="submit"  class="btn-update" name="submit" value="Save"  onclick="save_data();return false;"/>
      <?php } else if($btc_id > 0) { ?>
        <input type="submit"  class="btn-update" name="submit" value="Update"  onclick="update_data();return false;"/>
      <?php } ?>  
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
</form>     
<script type="text/javascript">
  $(document).ready(function() {
        $('.multi_type').multiselect({
          'includeSelectAllOption':true,
          'maxHeight':200,
          'includeSelectAllOption':false,
          'enableFiltering':true,
          onChange: function(option, checked, select) {
            var id = $(option).val();
            var text=$(option).text();
              if(checked==true)
              {
                option_selected(id,text);
              }
              else
              {
                option_unselected(id,text); 
              }
         }
          
        });
    });
<?php //} ?>

function option_selected(id,text)
{
  string ="<tr id='tr_row_"+id+"' ><td style='padding:4px;border:1px solid #ccc;text-align:left;' ><b>"+text+"</b></td><td style='padding:4px;border:1px solid #ccc;' ><input type='text' name='expiry_date["+id+"]' id='expiry_date_"+id+"' class='w-40px price_float' ></td><td style='padding:4px;border:1px solid #ccc;' ><select name='interval["+id+"]' id='interval_"+id+"'><option value=''>Select Interval</option><option value='1'>Hours</option><option value='2'>Days</option><option value='3'>Week</option><option value='4'>Month</option></select></td></tr>";
               
    $("#compo_label").append(string);
}


function option_unselected(id,text)
{
    $("#tr_row_"+id).remove();
}

</script>
<script>

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
// To open simulation popup
function bag_type_modal()
{
  var $modal = $('#load_add_beg_type_modal_popup');
  $modal.load('<?php echo base_url().'blood_bank/bag_type_to_component/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
}
 

function save_data()
{
  $('#overlay-loader').show();
   $.ajax({
    url: "<?php echo base_url('blood_bank/bag_type_to_component/save'); ?>",
    type: "post",
    data: $("#bag_component").serialize(),
    dataType:'json',
    success: function(result) 
    {
      $('#overlay-loader').hide();
        if(result.st==0)
        { 
          $("#component_error").html(result.component);
          $("#bag_type_error").html(result.bag_type);
        } 
        else if(result.st==1)
        {
          $('#overlay-loader').hide();
          $("#load_add_camp_modal_popup").modal('hide');
          $("#load_add_camp_modal_popup").html('');
          $("#component_error").html('');
          $("#bag_type_error").html('');
          flash_session_msg(result.msg);
          reload_table();
        } 
    }
  });
}


function update_data()
{
  $('#overlay-loader').show();
   $.ajax({
    url: "<?php echo base_url('blood_bank/bag_type_to_component/update_data'); ?>",
    type: "post",
    data: $("#bag_component").serialize(),
    dataType:'json',
    success: function(result) 
    {
      $('#overlay-loader').hide();
        if(result.st==0)
        { 
          $("#component_error").html(result.component);
          $("#bag_type_error").html(result.bag_type);
        } 
        else if(result.st==1)
        {
          $('#overlay-loader').hide();
          $("#load_add_camp_modal_popup").html('');
          $("#load_add_camp_modal_popup").modal('hide');
          $("#component_error").html('');
          $("#bag_type_error").html('');
           flash_session_msg(result.msg);
          reload_table();
        } 
    }
  });
}

$("button[data-number=1]").click(function(){
    $('#load_add_camp_modal_popup').modal('hide');
});



function validate_components(val)
{
  $vals=$(".multi_type").val();
  if($vals)
    $("#validate_component").val('1');
  else
    $("#validate_component").val('');
  
}

</script>
</div>
    
</div>

<!-- 3916433 -->