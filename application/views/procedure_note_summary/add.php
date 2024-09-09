<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>dataTables.bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datatable.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">

<!-- links -->
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css">
<!-- js -->
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap.min.js"></script> 

<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datepicker.js"></script>
<!-- datatable js -->
<script src="<?php echo ROOT_JS_PATH; ?>jquery.dataTables.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>dataTables.bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>validation.js"></script>  

 

<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="ot_summary" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
      
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Template Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="name"  class="alpha_numeric_space inputFocus" value="<?php echo $form_data['name']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                </div>
              </div> <!-- innerrow -->
              <?php if(getProcedureNoteTabSetting('diagnosis')['status']){ ?>
              <div class="row m-b-5">
                <div class="col-md-5">
                    <label><?=getProcedureNoteTabSetting('diagnosis')['var_value']?></label>
                </div>
                <div class="col-md-7">
                    
                    <textarea style="height: 72px;width:280px !important;" name="diagnosis" placeholder="Diagnosis" class="m_input_default ckeditor" id="diagnosis"><?php echo $form_data['diagnosis']; ?></textarea>
                    
                    
                    <?php if(!empty($form_error)){ echo form_error('diagnosis'); } ?>
                </div>
              </div> <!-- innerrow -->
              <?php } ?>
              <?php if(getProcedureNoteTabSetting('remarks')['status']){ ?>
        <div class="row m-b-5" id="content">
                  <div class="col-xs-5"><strong><?=getProcedureNoteTabSetting('remarks')['var_value']?></strong></div>
                  <div class="col-xs-7"> 
                    <textarea style="height: 72px;width:280px !important;" placeholder="Remarks" class="ckeditor" id="remark" name="remark"><?php  echo $form_data['remark'];  ?></textarea> 
                    </div>
          </div>
          <?php } ?>

            <div class="row m-b-5">
              <div class="col-md-5">
                <label>Operation Name</label>
              </div>
              <div class="col-md-7">
                <select name="operation" id="ot_name_id" class="w-145px">
                <option value="">Select Operation</option>
                <?php foreach($operation_list as $operation_list){?>
                <option value="<?php echo $operation_list->id;?>" <?php if(isset($form_data['operation']) && $form_data['operation']==$operation_list->id){ echo 'selected';}?>><?php echo $operation_list->name;?></option>

                <?php }?>

                </select>

                <a href="javascript:void(0)" onclick=" return add_operation_name();"  class="btn-new">
                <i class="fa fa-plus"></i> Add
                </a>

              <?php if(!empty($form_error)){ echo form_error('operation'); } ?>
              </div>
            </div> <!-- innerrow -->
            <?php if(getProcedureNoteTabSetting('op_findings')['status']){ ?>
              <div class="row m-b-5">
                <div class="col-md-5">
                    <label><?=getProcedureNoteTabSetting('op_findings')['var_value']?></label>
                </div>
                <div class="col-md-7">
                    <textarea style="height: 72px;width:280px !important;" placeholder="OP. Findings" name="op_findings" class="m_input_default ckeditor" id="op_findings"><?php echo $form_data['op_findings']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('op_findings'); } ?>
                </div>
              </div> <!-- innerrow -->
              <?php } ?>
              <?php if(getProcedureNoteTabSetting('procedures')['status']){ ?>
              <div class="row m-b-5">
                <div class="col-md-5">
                    <label><?=getProcedureNoteTabSetting('procedures')['var_value']?></label>
                </div>
                <div class="col-md-7">
                    <textarea style="height: 72px;width:280px !important;" name="procedures" class="m_input_default ckeditor" placeholder="Procedures" id="procedures"><?php echo $form_data['procedures']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('procedures'); } ?>
                </div>
              </div> <!-- innerrow -->
              <?php } ?>
              <?php if(getProcedureNoteTabSetting('post_op_order')['status']){ ?>

              <div class="row m-b-5">
                <div class="col-md-5">
                    <label><?=getProcedureNoteTabSetting('post_op_order')['var_value']?></label>
                </div>
                <div class="col-md-7">
                    <textarea style="height: 72px;width:280px !important;" placeholder="Post OP. Order" name="pos_op_orders" class="m_input_default ckeditor" id="pos_op_orders"><?php echo $form_data['pos_op_orders']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('pos_op_orders'); } ?>
                </div>
              </div> <!-- innerrow -->
              <?php } ?>
              <?php if(getProcedureNoteTabSetting('blood_transfusion')['status']){ ?>
              <div class="row m-b-5" id="content">
              <div class="col-xs-5"><strong><?=getProcedureNoteTabSetting('blood_transfusion')['var_value']?></strong></div>
              <div class="col-xs-7"> 
                <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Blood Transfusion" id="blood_transfusion" name="blood_transfusion" ><?php echo $form_data['blood_transfusion'];  ?></textarea>
              </div>
            </div>
            <?php } ?>
              <?php if(getProcedureNoteTabSetting('blood_loss')['status']){ ?>
            <div class="row m-b-5" id="content">
              <div class="col-xs-5"><strong><?=getProcedureNoteTabSetting('blood_loss')['var_value']?></strong></div>
              <div class="col-xs-7"> 
                 <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Blood Loss" id="blood_loss" name="blood_loss" ><?php  echo $form_data['blood_loss'];  ?></textarea>
                  </div>
          </div>
          <?php } ?>
          <?php if(getProcedureNoteTabSetting('drain')['status']){ ?>
          <div class="row m-b-5" id="content">
                  <div class="col-xs-5"><strong><?=getProcedureNoteTabSetting('drain')['var_value']?></strong></div>
                  <div class="col-xs-7"> 
                  <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor"  placeholder="Drain" id="drain" name="drain"><?php echo $form_data['drain'];  ?></textarea>
                  </div>
          </div>
        
          <?php } ?>
          <?php if(getProcedureNoteTabSetting('materials_submitted_for_histopathological_exam')['status']){ ?>
           <div class="row m-b-5" id="content">
                  <div class="col-xs-5"><strong><?=getProcedureNoteTabSetting('materials_submitted_for_histopathological_exam')['var_value']?></strong></div>
                  <div class="col-xs-7"> 
                  <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Materials Submitted for Histopathological Exam" id="histopathological" name="histopathological"><?php echo $form_data['histopathological']; ?></textarea>
                  </div>
          </div>
        
          <?php } ?>
          <?php if(getProcedureNoteTabSetting('materials_submitted_for_microbiological_exam')['status']){ ?>
            <div class="row m-b-5" id="content">
                  <div class="col-xs-5"><strong><?=getProcedureNoteTabSetting('materials_submitted_for_microbiological_exam')['var_value']?></strong></div>
                  <div class="col-xs-7"> 
                  <textarea style="height: 72px;width:280px !important;" class="textarea-100 ckeditor" placeholder="Materials Submitted for Microbiological Exam" id="microbiological" name="microbiological"><?php echo $form_data['microbiological'];  ?></textarea>
                  </div>
          </div>
          <?php } ?>
         
          
          
          <div class="row m-b-5">
             <div class="col-xs-12">
                
                  <table class="table table-bordered table-striped" id="prescription_name_table" style="margin-top: 30px; margin-left:0px;">
                    <thead>
                        <tr><th colspan="9">Medication Prescribed  </th></tr>
                    </thead>
                        
                     <tbody> 
                     <tr>
                        
                        <td>Medicine</td>
                        <td>Dose</td>
                        <td>Duration (Days)</td>
                        <td>Frequency</td>
                        <td>Advice</td>
                        <td>Date</td>
                        
                        <td width="80">
                          <a class="btn-w-60" href="javascript:void(0)" onclick="add_rows();">Add</a>
                        </td>
                      </tr>
            <?php if($rec_id==0) {   ?>      
                      <tr id="rec_id_0" >
                      
                      <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                      
                        <td><input style="width:100px;" type="text" value="" id="medicine_name_0" class="w-100px medicine_val" name="medicine[0][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input style="width:100px;" id="medicine_dosage_0" type="text" value="" class="input-small w-100px" name="medicine[0][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_0" name="medicine[0][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_0" name="medicine[0][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine[0][medicine_advice]" id="medicine_advice_0" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        <td><input style="width:100px;" type="text" value="" class="datepicker_m medicine-name date_val1 w-100px" name="medicine[0][medicine_date]" id="medicine_date_0" onmouseover="show_date_time_picker(this);" ></td>
                        
                        <td width="80">
                          
                        </td>
                      </tr>
            <?php } else if($rec_id > 0)  {  if($medicine_data!="empty") { 
              $x=0;
              foreach($medicine_data as $medicines)
              {
                $checked=0;
              ?>                

                    <tr id="rec_id_<?php echo $x; ?>" >
                     
                      <input type="hidden" name="medicine[<?php echo $x; ?>][is_eyedrop]" value="0" id="is_eye_drop_<?php echo $x; ?>">  
                 
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_name ;?>" id="medicine_name_<?php echo $x; ?>" class="w-100px medicine_val" name="medicine[<?php echo $x; ?>][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input style="width:100px;" id="medicine_dosage_<?php echo $x; ?>" type="text" value="<?php echo $medicines->medicine_dose ;?>" class="input-small w-100px" name="medicine[<?php echo $x; ?>][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_duration ;?>" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_frequency ;?>" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_<?php echo $x; ?>" name="medicine[<?php echo $x; ?>][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input style="width:100px;" type="text" value="<?php echo $medicines->medicine_advice ;?>" class="medicine-name advice_val1 w-100px" name="medicine[<?php echo $x; ?>][medicine_advice]" id="medicine_advice_<?php echo $x; ?>" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        <td><input type="text" value="<?php if(strtotime($medicines->medicine_date) > 0) { echo date('d-m-Y',strtotime($medicines->medicine_date)); } ?>" class="datepicker_m medicine-name date_val1 w-100px" name="medicine[<?php echo $x; ?>][medicine_date]" id="medicine_date_<?php echo $x; ?>" onmouseover="show_date_time_picker(this);" ></td>
                        
                           <input type="hidden" name="medicine[<?php echo $x; ?>][right_eye]" id="right_eye_val_<?php echo $x; ?>" value="0" >  
                           <input type="hidden" name="medicine[<?php echo $x; ?>][left_eye]" id="left_eye_val_<?php echo $x; ?>" value="0" > 
                       
                        <?php if($x==0){ ?>
                        <td width="80">
                          
                        </td>
                        <?php } else if($x>0) { ?>
                        <td width="80"><a class="btn-w-60" onclick="delete_prescription_medicine(<?php echo $x; ?>);" href="javascript:void(0)">Delete</a></td>
                        <?php } ?>
                      </tr>

            <?php $x++; } } else {  ?>
                   <tr id="rec_id_0" >
                   
                      <input type="hidden" name="medicine[0][is_eyedrop]" value="0" id="is_eye_drop_0">  
                      
                        <td><input type="text" value="" id="medicine_name_0" class="w-100px medicine_val" name="medicine[0][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>
                        <td><input id="medicine_dosage_0" type="text" value="" class="input-small w-100px" name="medicine[0][medicine_dosage]" onkeyup="get_auto_complete_medicine_dosage(this);" ></td>
                        <td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_0" name="medicine[0][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);" ></td>
                        <td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_0" name="medicine[0][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);" ></td>
                        <td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine[0][medicine_advice]" id="medicine_advice_0" onkeyup="get_auto_complete_medicine_advice(this);" ></td>
                        <td><input type="text" value="" class="datepicker_m medicine-name date_val1 w-100px" name="medicine[0][medicine_date]" id="medicine_date_0" onmouseover="show_date_time_picker(this);" ></td>
                       
                           <input type="hidden" name="medicine[0][right_eye]" id="right_eye_val_0" value="0" >  
                           <input type="hidden" name="medicine[0][left_eye]" id="left_eye_val_0" value="0" > 
                      
                        <td width="80">
                          
                        </td>
                      </tr>

            <?php } } ?>
                    </tbody>
                  </table>
            
             </div>
         </div>
          



            </div> <!-- 12 -->
          </div> <!-- row -->  
          <div class="row m-b-5">
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
         <button type="button" class="btn-cancel" data-dismiss="modal">Close</button>
      </div>
</form>     
<script>  

function check_eye_drop(ref)
{
  chk_val=$(ref).is(":checked"); 
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[3];
  
  if(chk_val==0)
  {
     $(".left_eye_appned_"+cnt).prop('checked',false);
    $(".right_eye_appned_"+cnt).prop('checked',false);
    $(".left_eye_appned_"+cnt).css('display','none');
    $(".right_eye_appned_"+cnt).css('display','none');

  }  
  else
  {
     $(".left_eye_appned_"+cnt).prop('checked',false);
    $(".right_eye_appned_"+cnt).prop('checked',false);
    $(".left_eye_appned_"+cnt).css('display','');
    $(".right_eye_appned_"+cnt).css('display','');
    
  }
}
function add_operation_name()
{
    var $modal = $('#load_add_ot_management_modal_popup');
    $modal.load('<?php echo base_url().'ot_management/add/' ?>',
    {
      //'id1': '1',
      //'id2': '2'
      },
    function(){
    $modal.modal('show');
    });
}

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('procedure_note_summary/get_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) { 

        $("#ot_summary_types").val(ui.item.value);
        return false;
    }

    $("#ot_summary_types").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 2,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });

function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    } else {
        return true;
    }      
}
 
$("#ot_summary").on("submit", function(event) { 
  event.preventDefault(); 
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Operation summary successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Operation summary successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('procedure_note_summary/'); ?>"+path,
    type: "post",
    data: function() {
        // Update CKEditor instances before serializing the form
        for (var instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        return $("#ot_summary").serialize();
    }(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ot_summary_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_ot_summary();
        reload_table();
      } 
      else
      {
        $("#load_add_ot_summary_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_ot_summary_modal_popup').modal('hide');
});

function get_ot_summary()
{
   $.ajax({url: "<?php echo base_url(); ?>procedure_note_summary/ot_summary_dropdown/", 
    success: function(result)
    {
      $('#ot_summary_id').html(result); 
    } 
  });
}

function get_ot_name()
{
   $.ajax({url: "<?php echo base_url(); ?>ot_booking/ot_name_dropdown/", 
    success: function(result)
    {
     
      $('#ot_name_id').html(result); 
      
    } 
  });
}


<?php if($medicine_data=="empty") { ?>
var row_count=1;
<?php } else {  ?>
var row_count="<?php echo count($medicine_data); ?>";
<?php } ?>  

function add_rows()
{
  var string='<tr id="rec_id_'+row_count+'">';


    string+='<input type="hidden" name="medicine['+row_count+'][is_eyedrop]" id="is_eye_drop_'+row_count+'" value=0 >';
  

  string+='<td><input type="text" value="" id="medicine_name_'+row_count+'" class="w-100px medicine_val" name="medicine['+row_count+'][medicine_name]" onkeyup="get_auto_complete_medicine(this);" ></td>'+
              
              '<td><input type="text" value="" class="input-small w-100px dosage_val" name="medicine['+row_count+'][medicine_dosage]" id="medicine_dosage_'+row_count+'" onkeyup="get_auto_complete_medicine_dosage(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name duration_val1 w-100px ui-autocomplete-input" id="medicine_duration_'+row_count+'"  name="medicine['+row_count+'][medicine_duration]" onkeyup="get_auto_complete_medicine_duration(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name frequency_val1 w-100px ui-autocomplete-input" id="medicine_frequency_'+row_count+'" name="medicine['+row_count+'][medicine_frequency]" onkeyup="get_auto_complete_medicine_frequency(this);"></td>'+
              '<td><input type="text" value="" class="medicine-name advice_val1 w-100px" name="medicine['+row_count+'][medicine_advice]" id="medicine_advice_'+row_count+'" onkeyup="get_auto_complete_medicine_advice(this);" ></td>'+
              '<td><input type="text" value="" class="datepicker_m medicine-name date_val1 w-100px" name="medicine['+row_count+'][medicine_date]" id="medicine_date_'+row_count+'" onmouseover="show_date_time_picker(this);" ></td>';

  string+='<input type="hidden" value="0" id="right_eye_val_'+row_count+'" name="medicine['+row_count+'][right_eye]">'+
          '<input type="hidden" id="left_eye_val_'+row_count+'" value="0" name="medicine['+row_count+'][left_eye]">';



string+='<td width="80"><a class="btn-w-60" onclick="delete_prescription_medicine('+row_count+');" href="javascript:void(0)">Delete</a></td>';

string+='</tr>';

if(row_count==1)
{
  $(string).insertAfter("#rec_id_0");
}
else
{
  v=row_count-1;
  $(string).insertAfter("#rec_id_"+v);
}

row_count++;

}
// Function to add new rows



// function to autocomplete medicine
function get_auto_complete_medicine(ref)
{
    value =$(ref).val();
    ref_id=$(ref).attr('id');
    extract_count=ref_id.split("_");
    var cnt=extract_count[2];
    $(function () 
    {
      var getData = function (request, response) 
      { 
        $.ajax({
                  url : "<?php echo base_url('eye/prescription_template/get_eye_medicine_auto_vals/'); ?>" + value,
                  dataType: "json",
                  method: 'post',
                  data: {
                          name_startsWith: value,
                          type: 'country_table',
                          //row_num : row
                        },
                  success: function( data ) 
                  {
                    response( $.map( data, function( item ) 
                    {
                      var code = item.split("|");
                      return {
                        label: code[0],
                        value: code[0],
                        data : item,
                          }
                    }));
                  }
              });
      };
      var selectItem = function (event, ui) 
      {
        var names = ui.item.data.split("|");
        $('#'+ref_id).val(names[0]);
        $('#medicine_unit_'+cnt).val(names[1]);
        $('#medicine_company_'+cnt).val(names[3]);
        $('#medicine_salt_'+cnt).val(names[2]);
        return false;
      }
      $("#"+ref_id).autocomplete(
      {
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() 
        {
          //$("#test_val").val("").css("display", 2);
        }
      });
  });
}
// function to autocomplete medicine

// function to autocomplete dosage
function get_auto_complete_medicine_dosage(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_dosage_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_dosage_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_dosage_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete dosage


// function to autocomplete duration
function get_auto_complete_medicine_duration(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_duration_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_duration_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_duration_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete duration

// function to autocomplete frequency

function get_auto_complete_medicine_frequency(ref)
{
  value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_frequency_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_frequency_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_frequency_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });

}
// function to autocomplete frequency


function get_auto_complete_medicine_advice(ref)
{
    value =$(ref).val();
  ref_id=$(ref).attr('id');
  extract_count=ref_id.split("_");
  var cnt=extract_count[2];  
  $(function () {
                  var getData = function (request, response) 
                  { 
                    $.getJSON(
                            "<?php echo base_url('eye/prescription_template/get_eye_advice_vals/'); ?>" + value,
                                  function (data) {
                                      response(data);
                                  });
                  };
                  var selectItem = function (event, ui) 
                  {
                    $("#medicine_advice_"+cnt).val(ui.item.value);
                    return false;
                  }
                  $("#medicine_advice_"+cnt).autocomplete(
                  {
                    source: getData,
                    select: selectItem,
                    minLength: 1,
                    change: function() {
                                           //$("#test_val").val("").css("display", 2);
                                        }
                  });
                });
}
function show_date_time_picker(ref)
{
  var id=$(ref).attr('id');
  $('#'+id).datepicker({
    dateFormat: 'dd-mm-yy',
    startDate : new Date(),
    autoclose: true, 
  });
}

</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 




        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->
<div id="load_add_ot_management_modal_popup" class="modal fade modal-top45 modal-45" role="dialog" data-backdrop="static" data-keyboard="false"></div>

      <script>
        var basicToolbar = [
            { name: 'basicstyles', items: ['Bold', 'Italic'] },
            { name: 'editing', items: ['Scayt'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList'] },
            { name: 'styles', items: ['Styles', 'Format'] },
            { name: 'insert', items: ['Table'] },
            // { name: 'tools', items: ['Maximize'] }
        ];
        var elements = document.querySelectorAll('.ckeditor');
        elements.forEach(function(element) {
            CKEDITOR.replace(element.id, {
                toolbar: basicToolbar
            });
        });

        // var element = document.querySelector('cause_of_death');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }

        // var element = document.querySelector('field_name[]');
        // if (element) {
        //     CKEDITOR.replace(element.id, {
        //         toolbar: basicToolbar
        //     });
        // }
        
      </script>