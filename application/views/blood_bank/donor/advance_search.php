 <?php  $users_data = $this->session->userdata('auth_users'); ?>
 <div class="modal-dialog advance-search-modal">
  <div class="overlay-loader" id="overlay-loader">
    <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
  </div>
  <div class="modal-content"> 
    <form  id="advance_search_form" class="form-inline"> 
      
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4><?php echo $page_title; ?></h4> 
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-xs-12">
            <!-- ===============start================ -->
            <div class="advance-search">
              <div class="inner">
                
                <div class="grp">
                  <label>From Date</label>
                  <input type="text" class="datepicker start_datepicker" name="start_date" value="<?php echo $form_data['start_date']; ?>" id="" >
                </div>
                
                <div class="grp">
                  <label>Donor Id</label>
                  <input type="text" class="inputFocus" name="donor_id"value="<?php echo $form_data['donor_id']; ?>">
                </div>
                
                <div class="grp">
                  <label>Donor Name </label>
                  <div class="rslt">
                    <select name="simulation_id" id="simulation_id" class="mr" onchange="find_gender(this.value)">
                      <option value="">Select</option>
                      <?php
                      if(!empty($simulation_list))
                      {
                        foreach($simulation_list as $simulation)
                        {
                          $selected_simulation = '';
                          if(in_array($simulation->simulation,$simulations_array)){

                            $selected_simulation = 'selected="selected"';
                            
                          }
                          else{
                            if($simulation->id==$form_data['simulation_id'])
                            {
                             $selected_simulation = 'selected="selected"';
                           }
                         }
                         echo '<option value="'.$simulation->id.'" '.$selected_simulation.'>'.$simulation->simulation.'</option>';
                       }
                     }
                     ?> 
                   </select> 
                   <input type="text" name="donor_name" id="donor_name" value="<?php echo $form_data['donor_name']; ?>" class="p-name inputFocus" autofocus="">
                 </div>
               </div>



               <div class="grp">
                <label>Qc Result</label>
                <div class="col-md-7">
                  <label><input type="radio" id='qc_result' name="qc_result" value="1" > Accepted</label> &nbsp;
                  <label> <input type="radio" id='qc_result' name="qc_result" value="2"> Rejected</label>

                </div>
              </div>

              
              
              
              <div class="grp">
                <label>Blood Group</label>
                <select name="blood_group" class="m_input_default" id="blood_group">
                  <option value="">Select Blood Group</option>
                  <?php
                  if($blood_groups!="empty")
                  {
                    foreach($blood_groups as $bg)
                    {
                      if($donor_data!="empty")
                      {  
                        if($donor_data['blood_group_id']==$bg->id)
                          $bgselect="selected=selected";
                        else
                          $bgselect="";

                        echo '<option value='.$bg->id.' '.$bgselect.' >'.$bg->blood_group.'</option>';
                      }
                      else
                      {
                        echo '<option value='.$bg->id.'>'.$bg->blood_group.'</option>'; 
                      }
                    }
                  }
                  ?>
                </select>
              </div>

              

              

            </div> <!-- inner -->

            <div class="inner">
              
              <div class="grp">
                <label>To Date</label>
                <input type="text" name="end_date" class="end_datepicker" value="<?php echo $form_data['end_date']; ?>" id="">
              </div>
              


              <div class="grp">
               <label>Outcome</label>
               <div class="rslt">
                
                <div class="gen">
                  <input type="radio" name="outcome" value="1" <?php if(isset($form_data['outcome']) &&  $form_data['outcome']!="" && $form_data['outcome']==1){ echo 'checked="checked"'; } ?>> Accepted
                </div>
                <div class="gen">
                  <input type="radio" name="outcome" value="2" <?php if(isset($form_data['outcome']) &&  $form_data['outcome']!="" && $form_data['outcome']==2){ echo 'checked="checked"'; } ?>> Temporary deferral
                </div>
                <div class="gen">
                  <input type="radio" name="outcome" value="3" <?php if(isset($form_data['outcome']) &&  $form_data['outcome']==3){ echo 'checked="checked"'; } ?>> Permanent deferral
                </div>
              </div>
            </div>









            
          </div> <!-- inner -->
        </div> <!-- advance-search -->

        <!-- ==================== ends ============= -->
      </div> <!-- 12 -->
    </div> <!-- row -->
  </div> <!-- modal-body -->  
  
  
  <div class="modal-footer"> 
   <input type="submit"  class="btn-update" name="submit" value="Search" />
   <!-- <input type="reset" onclick="return reset_search();" class="btn-reset" name="reset" value="Reset" /> -->
   <input value="Reset" class="btn-reset"  onclick="reset_search(this.form)" type="button">
   <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
 </div>
</form>     

<script>  



 $(document).ready(function(){
   var simulation_id = $("#simulation_id :selected").val();
   find_gender(simulation_id);
 });
 //function to find gender according to selected simulation
 function find_gender(id){
   if(id!==''){
    $.post('<?php echo base_url().'simulation/find_gender' ?>',{'simulation_id':id},function(result){
     if(result!==''){
      $("#gender").html(result);
    }
  })
  }
}


function reset_search(ele)
{

 $.ajax({url: "<?php echo base_url(); ?>blood_bank/donor/reset_search/", 
  success: function(result)
  { 
    $(ele).find(':input').each(function() {
      switch(this.type) {

        case 'select-multiple':
        case 'select-one':
        case 'text':
        case 'textarea':
        $(this).val('');
        break;
        case 'checkbox':
        case 'radio':
        this.checked = false;
      }
    });
   // reload_table();
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

$("#advance_search_form").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  
  $.ajax({
    url: "<?php echo base_url('blood_bank/donor/advance_search/'); ?>",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
      $('#load_add_modal_popup').modal('hide'); 
      reload_table();       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
  $('#load_add_specialization_modal_popup').modal('hide');
});


$('.start_datepicker').datepicker({
  format: 'dd-mm-yyyy', 
  autoclose: true, 
  endDate : new Date(), 
}).on("change", function(selectedDate) 
{ 
  var start_data = $('.start_datepicker').val();
  $('.end_datepicker').datepicker('setStartDate', start_data); 
});

$('.end_datepicker').datepicker({
  format: 'dd-mm-yyyy',     
  autoclose: true,  
}).on("change", function(selectedDate) 
{   
});

$(document).ready(function() {
  $('#load_add_modal_popup').on('shown.bs.modal', function(e){
    $('.inputFocus').focus();
  });
});
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->     
</div><!-- /.modal-dialog -->