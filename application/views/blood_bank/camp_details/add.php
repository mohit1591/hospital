<style>
  .caret{
    margin-top: 8px !important;
  }
</style>
<div class="modal-dialog emp-add-add modal-80">
<!--<div class="overlay-loader" id="overlay-loader">-->
<div>
       <!-- <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">-->
    </div>
  <div class="modal-content"> 
  <form  id="camp_type" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      <?php
      //print_r($form_data);
      //die;
      ?>
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Camp Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                <input type="text" name="camp_name" class="inputFocus" value="<?php echo $form_data['camp_name']; ?>">
                   <?php if(!empty($form_error)){ echo form_error('camp_name'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Camp Date <sup class="info"><a href="javascript:void(null)" class="small info"> ?<span>It is for the single day camp. For multiple days enetr start date and end date.</span></a></sup></label>
                </div>
                <div class="col-md-7">
                 <input type="text" class="datepicker datepickerto m_input_default" readonly=""  name="camp_date" id="to_date" value="<?php echo $form_data['camp_date']; ?>"/>
                   <?php if(!empty($form_error)){ echo form_error('camp_date'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Start Date</label>
                    <?php 
                       $start_date='';
                    if(!empty($form_data['start_date']))
                    {
                      if($form_data['start_date']!='0000-00-00')
                      {
                        $start_date=date('d-M-Y',strtotime($form_data['start_date']));
                      }


                    }
                    else
                    {
                       $start_date='';
                    }
                    ?>
                </div>
                <div class="col-md-7">
                 <input type="text" class="datepicker datepickerto m_input_default" readonly=""  name="start_date" id="to_date" value="<?php echo $start_date; ?>"/>
                   <?php //if(!empty($form_error)){ echo form_error('camp_date'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>End Date</label>
                    <?php 
                       $end_date='';
                    if(!empty($form_data['end_date']))
                    {
                      if($form_data['end_date']!='0000-00-00')
                      {
                        $end_date=date('d-M-Y',strtotime($form_data['end_date']));
                      }


                    }
                    else
                    {
                       $end_date='';
                    }
                    ?>
                </div>
                <div class="col-md-7">
                 <input type="text" class="datepicker datepickerto m_input_default" readonly=""  name="end_date" id="to_date" value="<?php echo $end_date; ?>"/>
                   <?php //if(!empty($form_error)){ echo form_error('camp_date'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Country</label>
                </div>
                <div class="col-md-7">
                 <select name="country_id" id="countrys_id" class="m_input_default" onchange="return get_state(this.value);">
              <option value="">Select Country</option>
              <?php
              if(!empty($country_list))
              {
                
                foreach($country_list as $country)
                {
                    if($form_data!='empty')
                    { 
                        if($form_data['country_id']==$country->id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$country->id.'" '.$select.'>'.$country->country.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$country->id.'" >'.$country->country.'</option>';
                    }
                }
              }
              ?> 
            </select> 
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 

          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>State</label>
                </div>
                <div class="col-md-7">
               <select name="state_id" id="states_id" class="m_input_default" onchange="return get_city(this.value)">
            <option value="">Select State</option>
            <?php
              $state_list = state_list(99); 
              if(!empty($state_list))
              {   
                  foreach($state_list as $state)
                  {  
                    if($form_data!="empty")
                    {
                      if($form_data['state_id']==$state->id)
                          $selected="selected=selected";
                        else
                          $selected="";
                      echo '<option value='.$state->id.' '.$selected.' >'.$state->state.'</option>';
                    }
                    else
                    {  
                     echo '<option value='.$state->id.'>'.$state->state.'</option>';
                    }
                  }
              }
            ?>
          </select>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>City</label>
                </div>
                <div class="col-md-7">
             <select name="citys_id" class="m_input_default" id="citys_id">
              <option value="">Select City</option>
              <?php
                  $city_list = city_list($form_data['state_id']);
                  //print_r($city_list);
                  foreach($city_list as $city)
                  {

                    if($form_data!="empty")
                    {
                      if($form_data['citys_id']==$city->id)
                          $select="selected=selected";
                        else
                          $select="";
                      echo '<option value='.$city->id.' '.$select.' >'.$city->city.'</option>';  
                    }
                    else
                    {
                      echo '<option value='.$city->id.'>'.$city->city.'</option>';
                    }
                    
                  }
              ?>
            </select>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row --> 
      
      
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Employee Involved In Camp</label>
                </div>
                <div class="col-md-7">
              <select name="camp_involved[]" class="multi_type" multiple="multiple">
              <?php
                   if(!empty($emp_list))
                   {
                   //print_r($emp_list);
                      
                     foreach($emp_list as $emp_list_id)
                     {
                     

                       
                      ?>
                      <?php?>
                      
                         <option  value='<?php echo $emp_list_id->id; ?>'><?php echo $emp_list_id->name; ?></option> 
                      <?php  
                     }
                   }
                  ?> 
                </select>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->    
           
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row">
                <div class="col-md-5">
                    <label>Camp Address</label>
                </div>
                <div class="col-md-7">
                
                 <textarea type="text" name="camp_address" class="inputFocus"><?php echo $form_data['camp_address']; ?></textarea>
                   <?php //if(!empty($form_error)){ echo form_error('camp_address'); } ?>
                </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
           
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-5">
                    <label>Status</label>
                </div>
                <div class="col-md-7">
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?> id="status" value="1" /> Active  
                     <input type="radio"  class="" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?> id="login_status" value="0" /> Inactive   
                 </div>
              </div> <!-- innerrow -->
            </div> <!-- 12 -->
          </div> <!-- row -->  
      </div> <!-- modal-body --> 

      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-dismiss="modal" data-number="1">Close</button>
      </div>
</form>     

<?php
if($form_data['data_id']!="")
{
  $x_arr=array();
  $explode=explode(',', $form_data['camp_involved']);
  $data_arr=json_encode($explode);
}
?>

 <script type="text/javascript">
 
 <?php if($form_data['data_id']!=""){ ?>
  var ot= <?php echo $data_arr; ?>;


  console.log(ot);
      $(document).ready(function() {
        $('.multi_type').multiselect('select',ot);

      });

  <?php } else { ?>

       $(document).ready(function() {
        $('.multi_type').multiselect({
          'includeSelectAllOption':true,
          'maxHeight':200,
          'enableFiltering':true
        });
    });
 <?php } ?>
    </script>
<script>
function get_state(country_id)
{
    $.ajax({url: "<?php echo base_url(); ?>general/state_list/"+country_id, 
      success: function(result)
      {
        $('#states_id').html(result); 
      } 
    });
    get_city(); 
}

function get_city(state_id)
{
  $.ajax({url: "<?php echo base_url(); ?>general/city_list/"+state_id, 
    success: function(result)
    {
      $('#citys_id').html(result); 
    } 
  }); 
}

$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    //endDate : new Date(), 
  });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#camp_type").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Camp Details  successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Camp Details successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('blood_bank/camp_details/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_camp_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_deferral_reason();
        reload_table();
      } 
      else
      {
        $("#load_add_camp_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_camp_modal_popup').modal('hide');
});

function get_deferral_reason()
{
   $.ajax({url: "<?php echo base_url(); ?>blood_bank/bag_type/deferral_reason_dropdown/", 
    success: function(result)
    {
      $('#deferral_reason_id').html(result); 
    } 
  });
}


</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->