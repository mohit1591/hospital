<?php
$users_data = $this->session->userdata('auth_users');
?>
<?php //print_r($form_data['specialization_row']);die; ?>
 <div class="modal-dialog">
    <div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
      <form  id="emp_type" class="form-inline" enctype="multipart/form-data">
        <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" />
      
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4><?php echo $page_title; ?></h4>
                </div>
            <div class="modal-body" style="max-height:calc(100vh - 165px);overflow-y:auto;">

              <!-- // ====== From Here  -->
              <div class="row">
                <div class="col-xs-6">
                    

                    

                   <div class="row m-b-5">
                      <div class="col-xs-4">
                        <strong>Schedule Name <span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-5">
                        <input type="text" name="schedule_name"   value="<?php echo $form_data['schedule_name'] ?>" class="txt_firstCapital inputFocus" autofocus> 
                        <?php if(!empty($form_error)){ echo form_error('schedule_name'); } ?>
                      </div>
                    </div> <!-- row -->


                     
                 

    
                   
                    <div class="row m-b-5">
                         <div class="col-xs-4">
                              <strong>Status</strong>
                         </div>
                         <div class="col-xs-8">
                              <input type="radio" name="status" <?php if($form_data['status']==1){ echo 'checked="checked"'; } ?>   value="1" /> Active 
                              <input type="radio" name="status" <?php if($form_data['status']==0){ echo 'checked="checked"'; } ?>  value="0" /> Inactive 
                         </div>
                    </div>

                




                </div> <!-- 6 // Left -->





                <!-- Right portion from here -->
                <div class="col-xs-6">

                   

                   

                  
                   

<div class="row m-b-5">
            <div class="col-xs-4">
            <strong> Available Days</strong>
            </div>
              <div class="col-xs-8">
              <div id="day_check">
              <?php //print_r($available_day);
              foreach ($days_list as $value) 
              { 

                  ?>
                  <input type="checkbox" class="day_check" name="check_day[]" id="check-<?php echo $value->id; ?>" value="<?php echo $value->id; ?>" onClick="checkdays('<?php echo $value->id; ?>','<?php echo $value->day_name; ?>');" <?php if(isset($available_day[$value->id])){ echo 'checked'; } ?> > <?php echo $value->day_name; ?>
                    <?php

              }
              ?>
              </div>
               <div>
               <input type="checkbox" name="checkall" id="check_all" onclick="clone_rows();" value="1"> All day Time like First Day Selection </div>
                   
            </div>
            </div>
          <?php
          if(!empty($available_day))
          {
            foreach ($available_day as $key=>$value) 
            {
                  ?>
                        <div><div class="row m-b-5">
                          <div class="col-xs-4"><strong><?php  echo $value; ?> </strong></div>
                          <div class="col-xs-8">
                          <table class="schedule_timing" id="doctor_time_table_day_<?php echo $key; ?>">
                          <thead>
                                  <tr>
                                      <td>From </td>
                                      <td>To </td>
                                      <td><a href="javascript:void(0)" class="btn-new addrow_day" onClick="return add_time_row('<?php echo $key; ?>')"><i class="fa fa-plus"></i>  Add</a></td>
                                  </tr>
                          </thead>
                          <tbody>
                          <?php 
                         $dialysis_availablity_time = get_dialysis_schedule_time($key,$form_data['data_id']);
                          if(!empty($dialysis_availablity_time))
                          {
                             $k=0;
                             foreach ($dialysis_availablity_time as $doctor_time) 
                             { //print_r($value);
                          ?>
                              <tr id="row-<?php echo $doctor_time->available_day.$k; ?>">
                                    <td><input  id="from-row-<?php echo $doctor_time->available_day.$k; ?>"  type="text" name="time[<?php echo $doctor_time->available_day; ?>][from][]" value="<?php echo $doctor_time->from_time; ?>" class="datepicker_day w-60px" ></td>
                                    <td><input type="text" name="time[<?php echo $doctor_time->available_day; ?>][to][]" id="to-row-<?php echo $doctor_time->available_day.$k; ?>" class="datepicker_day  w-60px" value="<?php echo $doctor_time->to_time; ?>" ></td>
                                    <td><a class="btn-new" href="javascript:void(0)" onclick="delete_time_row(<?php echo $doctor_time->available_day.$k; ?>)"> <i class="fa fa-trash"></i> Delete </a></td>
                              </tr>
                         <?php 
                          $k++;
                          } 
                        }
                          ?>
                          </tbody>
                          </table>
                          </div>
                          </div>
                          </div>
                      <?php 
            }
          }

          

          ?>


          <div id="schedule_time">
            
          </div>
          
          
                   

          <div class="row m-b-5">

                <div class="col-xs-4">
                    <strong>Per Patient Time</strong>
               </div>
               <div class="col-xs-8">
                    
                    <input type="text"  name="per_patient_timing" class="" id="per_patient_timing" value="<?php echo $form_data['per_patient_timing']; ?>"> Min.

               </div>
          </div>  <!-- row -->

                    <!-- row -->


                  
                </div> <!-- 6 // Right -->



                




              </div> <!-- ROW -->
             


            </div> <!-- modal-body -->  
                 
                 
            <div class="modal-footer"> 
               <input type="submit"  class="btn-update" name="submit" value="Save" id="save-doctor" />
               <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
            </div>
    </form>     
<script>

function checkdays(ids, vals)
{ 
   $('#'+ids+'_day').remove(); 
   if ($('#check-'+ids).is(":checked"))
   {  
      $("#schedule_time").append('<div id="'+ids+'_day" ><div class="row m-b-5"><div class="col-xs-4"><strong>'+vals+' </strong></div><div class="col-xs-8"><table class="schedule_timing" id="doctor_time_table_day_'+ids+'"><thead><tr><td>From </td><td>To </td><td><a href="javascript:void(0)" class="btn-new addrow_day_'+ids+'" onClick="return add_time_row('+ids+')"><i class="fa fa-plus"></i>  Add</a></td></tr></thead><tbody><tr id="row-'+ids+'0"><td><input id="from-row-'+ids+'0" type="text" name="time['+ids+'][from][]" value="" class="datepicker_day datepicker_day_'+ids+'  w-60px" ></td><td><input type="text" value="" name="time['+ids+'][to][]" id="to-row-'+ids+'0" class="datepicker_day datepicker_day_'+ids+'  w-60px" ></td><td>&nbsp;</td></tr></tbody></table></div></div></div>');
       $('.datepicker_day').datetimepicker({
                format: 'LT'
        });
   }
   else
   {
    $('#'+ids+'_day').remove(); 
   }
}

var i = 1;
function add_time_row(ids)
{
  $("#doctor_time_table_day_"+ids+" tbody").append('<tr id="row-'+ids+i+'"><td><input name="time['+ids+'][from][]" class="datepicker_day datepicker_day_1  w-60px" id="from-row-'+ids+i+'"  value="" type="text"></td><td><input name="time['+ids+'][to][]" id="to-row-'+ids+i+'" value="" class="datepicker_day datepicker_day_1  w-60px" type="text"></td><td><a href="javascript:void(0)" class="btn-new" onClick="delete_time_row('+ids+i+')" ><i class="fa fa-trash"></i> Delete</a></td></tr>');
  $('.datepicker_day').datetimepicker({
                format: 'LT'
        });
  i++;
}


function delete_time_row(row_id)
{
  $('#row-'+row_id).remove();
}

function clone_rows()
{ 
  //$( "#schedule_time table" ).first().id();
  var c = 1;
  var first_row_id = '0';
  $("#day_check input:checked").each(function () 
  { 
        if($('#check_all').is(":checked"))
        {
            var id = $(this).attr("id");
            if(c==1)
            {
              var id = id.replace("check-", "");
              first_row_id = id;
            }
            else
            {  
              
              var ti= 0;
              var id = $(this).attr("id");  
              var id = id.replace("check-", "");
              $('#doctor_time_table_day_'+id+' tbody').html(' ');
              $("#doctor_time_table_day_"+first_row_id+" tbody tr").each(function (){ 
                    var row_id = $(this).attr('id');
                    
                    var textbox_from = 'from-'+row_id;
                    var textbox_to = 'to-'+row_id;
                    var from_val = $('#'+row_id+' input:text[id='+textbox_from+']').val(); 
                    var to_val = $('#'+row_id+' input:text[id='+textbox_to+']').val();  
                    var row_html = '<tr id="row-'+id+ti+'"><td><input name="time['+id+'][from][]" value="'+from_val+'" class="datepicker_day datepicker_day_'+id+'  w-60px" style="" type="text"></td><td><input value="'+to_val+'" name="time['+id+'][to][]" class="datepicker_day datepicker_day_'+id+'  w-60px" type="text"></td><td><a href="javascript:void(0)" class="btn-new" onClick="delete_time_row('+id+ti+')" ><i class="fa fa-trash"></i> Delete</a></td></tr>';

                    $("#doctor_time_table_day_"+id+"").append(row_html);
                    ti++;
              });   
              
            }

        } 
        else
        {
            
            var id = $(this).attr("id");
            if(c==1)
            {
              
            }
            else
            { 

              var id = $(this).attr("id");  
              var id = id.replace("check-", "");
              
              $('#doctor_time_table_day_'+id+' tbody').html(' ');  
              $("#doctor_time_table_day_"+id+" tbody").append('<tr id="row-'+id+c+'"><td><input name="time['+id+'][from][]" class="datepicker_day datepicker_day_1  w-60px" id="from-row-'+id+c+'"  value="" type="text"></td><td><input name="time['+id+'][to][]" id="to-row-'+id+c+'" value="" class="datepicker_day datepicker_day_1  w-60px" type="text"></td><td><a href="javascript:void(0)" class="btn-new" onClick="delete_time_row('+id+c+')" ><i class="fa fa-trash"></i> Delete</a></td></tr>');
                $('.datepicker_day').datetimepicker({
                              format: 'LT'
                });
            }
              //$('#doctor_time_table_day_'+id+' tbody').html(' ');
        }    
        c++;
    });


} 
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip({
        placement: 'right', 
        trigger:'focus'
    
    });   
});
$(".txt_firstCapital").on('keyup', function(){

   var str = $('.txt_firstCapital').val();
   var part_val = str.split(" ");
    for ( var i = 0; i < part_val.length; i++ )
    {
      var j = part_val[i].charAt(0).toUpperCase();
      part_val[i] = j + part_val[i].substr(1);
    }
      
   $('.txt_firstCapital').val(part_val.join(" "));
  
  }); 
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
 
$("#emp_type").on("submit", function(event) { 


  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Schedule successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Schedule successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('dialysis_schedule_management/'); ?>"+path,
    type: "post",
    //data: $(this).serialize(),
    data: new FormData(this),  
    contentType: false,      
    cache: false,            
    processData:false,
    beforeSend: function() {
           $('#save-doctor').attr('disabled');
           },
    success: function(result) {
      if(result==1)
      {
        $('#load_add_modal_popup').modal('hide');
        reload_table();
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
    $('#load_add_specialization_modal_popup').modal('hide');
});


</script> 
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
</div><!-- /.modal-content -->   
</div><!-- /.modal-dialog -->  
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>moment-with-locales.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
<script type="text/javascript">
  $('.datepicker_day').datetimepicker({
      format: 'LT'
  });
</script>