<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>jquery-ui.css"> 
<script src="<?php echo ROOT_JS_PATH; ?>jquery-ui.min.js"></script>
<div class="modal-dialog">
<div class="overlay-loader" id="overlay-loader">
        <img src="<?php echo ROOT_IMAGES_PATH; ?>loader.gif" class="aj-loader">
    </div>
  <div class="modal-content"> 
  <form  id="ot_pacakge_form" class="form-inline">
  <input type="hidden" name="data_id" id="type_id" value="<?php echo $form_data['data_id']; ?>" /> 
      <div class="modal-header">
          <button type="button" class="close"  data-number="1" aria-label="Close"><span aria-hidden="true">×</span></button>
          <h4><?php echo $page_title; ?></h4> 
      </div>
     
      <div class="modal-body">   
          <div class="row">
            <div class="col-md-12 m-b1">
              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Operation Package Name<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="name"  class="alpha_numeric_space inputFocus" value="<?php echo $form_data['name']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('name'); } ?>
                </div>
              </div> <!-- innerrow -->
              <div class="row m-b-5">
               <!--  <div class="col-md-4">
                    <label>Operation Type<span class="star">*</span></label>
                </div>
                 <div class="col-md-8">
                    <input type="text" name="type" id="ot_pacakge_types" class="alpha_numeric_space" value="<?php echo $form_data['type']; ?>">
                     <input type="hidden" name="ot_type_id" value="" id="ot_type_id">
                    <?php if(!empty($form_error)){ echo form_error('type'); } ?>
                </div> -->
                  <div class="col-xs-5">
                        <strong>Operation Type<span class="star">*</span></strong>
                      </div>
                      <div class="col-xs-7">
                        <select name="ot_pacakge_type_id" id="ot_pacakge_type_id" class="m10 w-145px">
                          <option value=""> Select Operation Type</option>
                          <?php
                          if(!empty($ot_type_list))
                          {
                            foreach($ot_type_list as $ot_type)
                            {

                             ?>
                               <option value="<?php echo $ot_type->id; ?>" <?php if($ot_type->id==$form_data['ot_pacakge_type_id']){ echo 'selected="selected"'; } ?>><?php echo $ot_type->operation_type; ?></option>
                             <?php  
                            }
                          }
                          ?>
                        </select>
                        
                      
                      <?php //if(in_array('44',$users_data['permission']['action'])) {
                      ?>
                           <a href="javascript:void(0)" onclick=" return add_operation_type();"  class="btn-new">
                                <i class="fa fa-plus"></i> Add
                           </a>
                      <?php //} ?>
                      <?php if(!empty($form_error)){ echo form_error('ot_pacakge_type_id'); } ?>
                      </div>
      



              </div> <!-- innerrow -->

              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Operation Days<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="days"   class="price_float" value="<?php echo $form_data['days']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('days'); } ?>
                </div>
              </div> <!-- innerrow -->

              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Operation Hours</label>
                </div>
                <div class="col-md-7">

                    <input type="text" name="hours" class="price_float" value="<?php echo $form_data['hours']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('hours'); } ?>
                </div>
              </div> <!-- innerrow -->


              <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Amount<span class="star">*</span></label>
                </div>
                <div class="col-md-7">
                    <input type="text" name="amount" id="stotal_amount" class="price_float" value="<?php echo $form_data['amount']; ?>">
                    
                    <?php if(!empty($form_error)){ echo form_error('amount'); } ?>
                </div>
              </div> <!-- innerrow -->

              <div class="row m-b-5">
                  <div class="col-md-5"> <label>OT Details<span class="star">*</span></label></div>
                 
                </div>
             
              
               <div class="add_more_filed">
               <?php $i=0; 
               if(isset($form_data['ot_details']) && !empty($form_data['ot_details']))

               {  
                //print_r($form_data['ot_details']);
                foreach ($form_data['ot_details']  as $ot_details)
                 {
                  
                  ?>
              <div class="" id="filed_name_new_<?php echo $i; ?>">
                 <div class="row m-b-5" >

                 <div class="col-md-5">
                      <label><input name="doctor_wise[<?php echo $i; ?>][type_ot][]" id="type_ot_<?php echo $i; ?>" value="0"  type="radio" onclick="check_type('<?php echo $i; ?>',0);" <?php if($ot_details->ot_type==0){echo 'checked="checked"';}?>> Doctor &nbsp;</label>
                      <label><input name="doctor_wise[<?php echo $i; ?>][type_ot][]" id="type_ot_<?php echo $i; ?>" value="1" type="radio" onclick="check_type('<?php echo $i; ?>',1);" <?php if($ot_details->ot_type==1){echo 'checked="checked"';}?>> Particulars </label>
                 
                  </div>
                  <?php if(isset($ot_details->doctor_name) && $ot_details->doctor_name!='')
                  { //echo 'dfdf';?>
                    <div id="doctor_div_<?php echo $i; ?>" >
                      <div class="col-md-7">
                      <input type="text" name="doctor_wise[<?php echo $i; ?>][code][]" value="<?php if(isset($ot_details->code)){ echo $ot_details->code;}?>" id="code_<?php echo $i; ?>" class="w-90px code" placeholder="Code"/>


                      <input type="text" name="doctor_wise[<?php echo $i; ?>][doctor_name][]" value="<?php if(isset($ot_details->doctor_name)){ echo $ot_details->doctor_name;}?>" id="doctor_name_<?php echo $i; ?>" class="w-90px doctor_name" placeholder="Name"/>
                        <input type="hidden" name="doctor_wise[<?php echo $i; ?>][doctor_id][]" id="doctor_id" value="<?php if(isset($ot_details->doctor_id)){ echo $ot_details->doctor_id;}?>"/>
                          <select name="doctor_wise[<?php echo $i; ?>][master_type][]" class="w-50px master_type"> 
                              <option value="0" <?php if($ot_details->master_type==0){echo 'selected';}?>> Rs </option>
                              <option value="1" <?php if($ot_details->master_type==1){echo 'selected';}?>> % </option>
                          </select>
                       <input type="text" name="doctor_wise[<?php echo $i; ?>][master_rate][]"  class="w-50px rate_plan" value="<?php if(isset($ot_details->master_rate)){ echo $ot_details->master_rate;}?>">
                          
                             <?php //if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                             <a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(0);">
                             <i class="fa fa-plus"></i>
                             </a>
                              <a href="javascript:void(0)" class="btn-new" onclick="remove_field('<?php echo $i; ?>',0);">
                             <i class="fa fa-minus"></i>
                             </a>
                        </div>
                    </div>
                    <div id="particular_div_<?php echo $i; ?>">
                    </div>
                 <?php } elseif(isset($ot_details->particular_name) && $ot_details->particular_name!='')
                  {?>


                  <div id="particular_div_<?php echo $i; ?>">
                    <div class="col-md-7">
                       <input type="text" name="doctor_wise[<?php echo $i; ?>][code_p][]" value="<?php if(isset($ot_details->code)){ echo $ot_details->code;}?>" id="code_<?php echo $i; ?>" class="w-90px code_p" placeholder="Code"/>

                      <input type="text" name="doctor_wise[<?php echo $i; ?>][particular_name][]" value="<?php if(isset($ot_details->particular)){ echo $ot_details->particular;}?>" id="particular_name_<?php echo $i; ?>" class="w-90px particular_name" placeholder="Name"/>
                      <input type="hidden" name="doctor_wise[<?php echo $i; ?>][particular_id][]" id="particular_id" value="<?php if(isset($ot_details->particular_id)){ echo $ot_details->particular_id;}?>"/>
                      <select name="doctor_wise[<?php echo $i; ?>][master_type][]" class="w-50px master_type"> 
                          <option value="0" <?php if($ot_details->master_type==0){echo 'selected';}?>> Rs </option>
                          <option value="1" <?php if($ot_details->master_type==1){echo 'selected';}?>> % </option>
                      </select>
                        <input type="text" name="doctor_wise[<?php echo $i; ?>][master_rate][]"  class="w-50px rate_plan" value="<?php if(isset($ot_details->master_rate)){ echo $ot_details->master_rate;}?>">

                        <a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(1);">
                      <i class="fa fa-plus"></i>
                      </a>
                      <a href="javascript:void(0)" class="btn-new" onclick="remove_field('<?php echo $i; ?>',1);">
                             <i class="fa fa-minus"></i>
                             </a>
                      <?php //if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                    </div>
                </div>
                <div id="doctor_div_<?php echo $i; ?>" >
                </div>



                <?php }?> </div></div> <?php $i++;} ?>
             
                 


             <?php } else{ ?>
             <div class="row m-b-5" >
             <div class="col-md-5">
                    <label><input name="doctor_wise[0][type_ot][]" id="type_ot_0" value="0" checked type="radio" onclick="check_type(0,0);"> Doctor &nbsp;</label>
                    <label><input name="doctor_wise[0][type_ot][]" id="type_ot_0" value="1" type="radio" onclick="check_type(0,1);" > Particulars </label>
               
                </div>
                
                <div id="doctor_div_0" >
                      <div class="col-md-7">
                       <input type="text" name="doctor_wise[0][code][]" value="" id="code" class="w-90px code" placeholder="Code"/>
                      <input type="text" name="doctor_wise[0][doctor_name][]" value="" id="doctor_name" class="w-90px doctor_name"/>
                        <input type="hidden" name="doctor_wise[0][doctor_id][]" id="doctor_id" value=""/>
                          <select name="doctor_wise[0][master_type][]" class="w-50px master_type"> 
                              <option value="0"> Rs </option>
                              <option value="1"> % </option>
                            </select>
                       <input type="text" name="doctor_wise[0][master_rate][]"  class="w-50px rate_plan" value="">
                          
                             <?php //if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                             <a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(0);">
                             <i class="fa fa-plus"></i>
                             </a>
                        </div>
                  </div>


                <div id="particular_div_0" style="display:none">
                    <div class="col-md-7">
                    <input type="text" name="doctor_wise[0][code_p][]" value="" id="code_p" class="w-90px code_p" placeholder="Code"/>
                      <input type="text" name="doctor_wise[0][particular_name][]" value="" id="" class="w-90px particular_name"/>
                      <input type="hidden" name="doctor_wise[0][particular_id][]" id="particular_id" value=""/>
                      <select name="doctor_wise[0][master_type][]" class="w-50px master_type"> 
                          <option value="0" > Rs </option>
                          <option value="1" > % </option>
                      </select>
                        <input type="text" name="doctor_wise[0][master_rate][]"  class="w-50px rate_plan" value="">

                        <a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(1);">
                      <i class="fa fa-plus"></i>
                      </a>
                      <?php //if(!empty($form_error)){ echo form_error('master_rate'); } ?>
                    </div>
                </div>

             <?php $i++ ;?>  </div> <?php } 
             ?>
             
              
           <!-- --> 
           
         

           
               
           </div>

           <div class="row m-b-5">
                <div class="col-md-5">
                    <label></label>
                </div>
                <div class="col-md-7 text-danger" id="valid_error_msg">
                      
                </div>
              </div> <!-- innerrow -->

           <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Operation Procedure</label>
                </div>
                <div class="col-md-7">
                    <textarea name="ot_procedure" id="ot_procedure"><?php echo $form_data['ot_procedure']; ?></textarea>
                    <?php if(!empty($form_error)){ echo form_error('ot_procedure'); } ?>
                </div>
              </div> <!-- innerrow -->    

          <div class="row m-b-5">
                <div class="col-md-5">
                    <label>Remarks</label>
                </div>
                <div class="col-md-7">
                    <textarea type="text" name="remarks" class="alpha_numeric_space" ><?php echo $form_data['remarks']; ?></textarea>
                    
                    <?php if(!empty($form_error)){ echo form_error('remarks'); } ?>
                </div>
              </div> <!-- innerrow -->



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
<div id="load_add_operation_type_modal_popup" class="modal fade modal-top" role="dialog" data-backdrop="static" data-keyboard="false"></div>
      <div class="modal-footer"> 
         <input type="submit"  class="btn-update" name="submit" value="Save" />
         <button type="button" class="btn-cancel" data-number="1">Close</button>
      </div>
</form>     

<link href = "<?php echo ROOT_CSS_PATH; ?>jquery-ui.css" rel = "stylesheet">
<script src = "<?php echo ROOT_JS_PATH; ?>jquery-ui.js"></script>
<script> 
$(document).ready(function(){

  <?php $i=0;if(isset($form_data['ot_details']) && !empty($form_data['ot_details'])){foreach($form_data['ot_details']  as $ot_details1)
  {?>
   new_doctor_auto_complite('<?php echo $i; ?>','<?php echo $ot_details1->ot_type ;?>');
 <?php $i++;}}?>

});
<?php
if(!empty($form_data['ot_details']))
{
  $total_num = count($form_data['ot_details']);
  echo 'var divSize = '.$total_num.';';
}
else
{
  echo 'var divSize = $(".add_more_filed > div").size()+1;';
}
?>
//var ot_name= 'type_ot_'+divSize'[]';
function check_field_valid(vals,i)
{
  $.ajax({
    url: "<?php echo base_url('ot_pacakge/set_field_valid/'); ?>"+vals+'/'+i, 
    success: function(result) 
    {
       
    }
  });
}

var f = 0;

function add_more_field(parmatersids)
{
        var my_div = '';  
        my_div= '<div id="filed_name_new_'+divSize+'"><div class="row m-b-5" > <div class="col-md-5"><label><input name="doctor_wise['+divSize+'][type_ot][]" id="type_ot_d_'+divSize+'" value="0" type="radio" checked onclick="check_type('+divSize+',0);"> Doctor &nbsp;</label> <label><input name="doctor_wise['+divSize+'][type_ot][]" id="type_ot_p_'+divSize+'" value="1" type="radio" onclick="check_type('+divSize+',1);"> Particulars </label> </div><div id="doctor_div_'+divSize+'" ><div class="col-md-7"><input type="text" name="doctor_wise['+divSize+'][code][]" value="" placeholder="code" id="code_'+divSize+'" class="w-90px code"/> <input type="text" placeholder="Name" name="doctor_wise['+divSize+'][doctor_name][]" value="" id="doctor_name_'+divSize+'" class="w-90px doctor_name"/><input type="hidden" name="doctor_wise['+divSize+'][doctor_id][]" id="doctor_id_'+divSize+'" value=""/> <select onchange="return check_field_valid(this.value,'+f+')" name="doctor_wise['+divSize+'][master_type][]" class="w-50px master_type"><option value="0"> Rs </option><option value="1" > % </option></select> <input type="text" onkeyup="return check_field_valid(this.value,'+f+')" name="doctor_wise['+divSize+'][master_rate][]"  class="w-50px rate_plan" value=""><a href="javascript:void(0)" class="btn-new" onclick=" return add_more_field(1);"><i class="fa fa-plus"></i></a> <a href="javascript:void(0)" class="btn-new" onclick=" return remove_field('+divSize+',0);" ><i class="fa fa-minus"></i></a></div></div><div id="particular_div_'+divSize+'"></div></div></div>';
        
        var fields =[];

        if(divSize=='0'){
        $('.add_more_filed').html(my_div);
        }
        else{
        $('.add_more_filed').append(my_div);
        }
        
        new_doctor_auto_complite(divSize,0);


       divSize++;
       f++;
  

     
} 
function check_type(divsize,type_data)
{
   
      if(type_data==0)
      {
          css_c='';
          if(divsize==0)
          {
          var css_c='fa-plus';
          var onclick_event="return add_more_field();"
          }
          else
          {
          var css_c='fa-minus';
          var onclick_event="remove_field("+divsize+","+type_data+")";
          }
       
         $('#doctor_div_'+divsize).html('<div class="col-md-7"><input type="text" name="doctor_wise['+divsize+'][code][]" placeholder="Code" value="" id="code_'+divsize+'" class="w-90px code"/> <input type="text" placeholder="Name" name="doctor_wise['+divsize+'][doctor_name][]" value="" id="doctor_name_'+divsize+'" class="w-90px doctor_name"/><input type="hidden" name="doctor_wise['+divsize+'][doctor_id][]" id="doctor_id_'+divsize+'" value=""/> <select onchange="return check_field_valid(this.value,'+f+')" name="doctor_wise['+divsize+'][master_type][]" class="w-50px master_type"><option value="0"> Rs </option><option value="1" > % </option></select> <input type="text" onkeyup="return check_field_valid(this.value,'+f+')" name="doctor_wise['+divsize+'][master_rate][]"  class="w-50px rate_plan" value=""> <a href="javascript:void(0)" class="btn-new" onclick="'+onclick_event+'" ><i class="fa '+css_c+'"></i></a></div>');
           $("#particular_div_"+divsize).html('');
            new_doctor_auto_complite(divsize,type_data);
            f++;

     }
    if(type_data==1)
    {
       css_c='';
       if(divsize==0)
       {
        var css_c='fa-plus';
        var onclick_event="return add_more_field();"
       }
       else
       {
        var css_c='fa-minus';
        var onclick_event="remove_field("+divsize+","+type_data+")";
       }
        $('#particular_div_'+divsize).css("display","block"); 
        $('#particular_div_'+divsize).html('<div class="col-md-7"><input type="text" name="doctor_wise['+divsize+'][code_p][]" value="" id="code_p_'+divsize+'" class="w-90px code_p" placeholder="Code"/> <input type="text" name="doctor_wise['+divsize+'][particular_name][]" placeholder="Name" value="" id="particular_name_'+divsize+'" class="w-90px particular_name"/><input type="hidden" name="doctor_wise['+divsize+'][particular_id][]" id="particular_id_'+divsize+'" value=""/> <select onchange="return check_field_valid(this.value,'+f+')" name="doctor_wise['+divsize+'][master_type][]" class="w-50px master_type"><option value="0" > Rs </option><option value="1" > % </option></select> <input type="text" name="doctor_wise['+divsize+'][master_rate][]"  class="w-50px rate_plan" value="" onkeyup="return check_field_valid(this.value,'+f+')"> <a href="javascript:void(0)" class="btn-new" onclick="'+onclick_event+'"><i class="fa '+css_c+'"></i></a></div>');
          $("#doctor_div_"+divsize).html('');
          new_doctor_auto_complite(divsize,type_data);
          //$("#doctor_div_"+divsize).css("display","none"); 
    }

}




function new_doctor_auto_complite(divsize,type_data)
{
  if(type_data==1)
    {
        var i=1;
         var getData2 = function (request, response) { 
              row = i ;
              $.ajax({
              url : "<?php echo base_url('ipd_discharge_bill/get_particular_data/'); ?>" + request.term,
              dataType: "json",
              method: 'post',
             data: {
               name_startsWith: request.term,
               
               row_num : row
            },
             success: function( data ) {
               response( $.map( data, function( item ) {
                var code = item.split("|");
                return {
                  label: code[0],
                  value: code[0],
                  data : item
                }
              }));
            }
            });

             
          };


          var selectItem = function (event, ui) {
              //$(".medicine_val").val(ui.item.value);

              var names = ui.item.data.split("|");

                $('#particular_name_'+divsize).val(names[0]);
                $('#particular_id_'+divsize).val(names[2]);
                

              return false;
          }

        $("#particular_name_"+divsize).autocomplete({
              source: getData2,
              select: selectItem,
              minLength: 1,
              change: function() {  
                  //$("#default_vals").val("").css("display", 2);
              }
          });

    }
    if(type_data==0)
    {
      var i=1;
      var getData_d = function (request, response) { 
          row = i ;
          $.ajax({
          url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term,
          dataType: "json",
          method: 'post',
         data: {
           name_startsWith: request.term,
           
           row_num : row
        },
         success: function( data ) {
           response( $.map( data, function( item ) {
            var code = item.split("|");
            return {
              label: code[0],
              value: code[0],
              data : item
            }
          }));
        }
        });
           };

    var selectItem = function (event, ui) {
          //$(".medicine_val").val(ui.item.value);

          var names = ui.item.data.split("|");

            $('#doctor_name_'+divsize).val(names[0]);
            $('#doctor_id_'+divsize).val(names[1]);
            

          
      }

    $("#doctor_name_"+divsize).autocomplete({
          source: getData_d,
          select: selectItem,
          minLength: 1,
          change: function() {  
           
          }
      });
  }
}
function remove_field(n,type_data)
{
   $('#filed_name_new_'+n).html('');
   $('#filed_name_new_'+n).remove();
}

$(document).ready(function() {

    $("input[name$='type_ot_0[]']").click(function() 
    {
       $('.add_more_filed').html('');
      var test = $(this).val();
      if(test==0)
      {
        $("#particular_div").hide();
        $("#doctor_div").show();
        $('#type_particular').val('');

        
      }
      else if(test==1)
      {
        
          $("#doctor_div").hide();
          //$("#ref_by_other").css("display","none"); 
          $("#particular_div").css("display","block"); 
          $('#type_doctor').val('');
          //$("#refered_id :selected").val('');
      }
        
    });
});
$(document).ready(function() {
  $('#load_add_operation_type_modal_popup').on('shown.bs.modal', function(e){
    $(this).find('.inputFocus').focus();
  });
});

$(function () {
    var getData = function (request, response) { 
        $.getJSON(
            "<?php echo base_url('ot_pacakge/get_vals/'); ?>" + request.term,
            function (data) {
                response(data);
            });
    };

    var selectItem = function (event, ui) { 
      var names = ui.item.value.split("|");
//alert(names[1]);
       $('#ot_pacakge_types').val(names[0]);
          $('#ot_type_id').val(names[0]);

       // $("#ot_pacakge_types").val(ui.item.value);
        return false;
    }

    $("#ot_pacakge_types").autocomplete({ 
        source: getData,
        select: selectItem,
        minLength: 1,
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
 
 

$("#ot_pacakge_form").on("submit", function(event) { 
  event.preventDefault(); 
  //////// Check Validtion
  var pack_amount = $('#stotal_amount').val();
   if(pack_amount!="")
   {
       var total_val = 0;
       $(".rate_plan").each(function(){
             var vals = $(this).val();
             if(vals!="")
             {
               var val_type = $(this).prev().find('option:selected').val(); 
               if(val_type==1)
               {
                 total_val = parseInt(total_val)+(parseInt(vals)*(parseInt(pack_amount)/100)); 
               }
               else
               {
                total_val = parseInt(total_val)+parseInt(vals);
               }
               
             }
             
        });

       if(pack_amount==total_val)
       {
          $('#valid_error_msg').html(' ');
       } 
       else
       {
         if(total_val!="")
         {
            $('#valid_error_msg').html('Total amount of OT details is not equal to package amount.');
            return false;
         } 
       }
  }
  /////////////////////////
  $('#overlay-loader').show();
  var ids = $('#type_id').val();
  if(ids!="" && !isNaN(ids))
  { 
    var path = 'edit/'+ids;
    var msg = 'Operation package successfully updated.';
  }
  else
  {
    var path = 'add/';
    var msg = 'Operation package successfully created.';
  } 
  //alert('ddd');return false;
  $.ajax({
    url: "<?php echo base_url('ot_pacakge/'); ?>"+path,
    type: "post",
    data: $(this).serialize(),
    success: function(result) {
      if(result==1)
      {
        $('#load_add_ot_pacakge_modal_popup').modal('hide');
        flash_session_msg(msg);
        get_ot_pacakge();
        reload_table();
      } 
      else
      {
        $("#load_add_ot_pacakge_modal_popup").html(result);
      }       
      $('#overlay-loader').hide();
    }
  });
}); 

$("button[data-number=1]").click(function(){
    $('#load_add_ot_pacakge_modal_popup').modal('hide');
});

function add_operation_type()
{
  //alert("hi");
  var $modal = $('#load_add_operation_type_modal_popup');
  $modal.load('<?php echo base_url().'operation_type/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });
  /*var $modal = $('#load_add_specialization_modal_popup');
  $modal.load('<?php echo base_url().'specialization/add/' ?>',
  {
    //'id1': '1',
    //'id2': '2'
    },
  function(){
  $modal.modal('show');
  });*/
}
function get_operation_type()
{
   $.ajax({url: "<?php echo base_url(); ?>operation_type/operation_type_dropdown/", 
    success: function(result)
    {
      $('#ot_pacakge_type_id').html(result); 
    } 
  });
}
function get_ot_pacakge()
{
   $.ajax({url: "<?php echo base_url(); ?>ot_pacakge/ot_summary_dropdown/", 
    success: function(result)
    {
      $('#ot_pacakge_id').html(result); 
    } 
  });
}

$(function () {
   var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ot_booking/get_doctor_name/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
       data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });

       
    };


    var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

          $('#doctor_name').val(names[0]);
          $('#doctor_id').val(names[1]);
          

        return false;
    }


    $("#doctor_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
});

$(function () {
    var i=1;
    var getData = function (request, response) { 
        row = i ;
        $.ajax({
        url : "<?php echo base_url('ipd_discharge_bill/get_particular_data/'); ?>" + request.term,
        dataType: "json",
        method: 'post',
      data: {
         name_startsWith: request.term,
         
         row_num : row
      },
       success: function( data ) {
         response( $.map( data, function( item ) {
          var code = item.split("|");
          return {
            label: code[0],
            value: code[0],
            data : item
          }
        }));
      }
      });

       
    };

    var selectItem = function (event, ui) {
        //$(".medicine_val").val(ui.item.value);

        var names = ui.item.data.split("|");

        $('#particular_name').val(names[0]);
        //$('#charge').val(names[1]);
        $('#particular_id').val(names[2]);
          

        return false;
    }

    $("#particular_name").autocomplete({
        source: getData,
        select: selectItem,
        minLength: 1,
        change: function() {  
            //$("#default_vals").val("").css("display", 2);
        }
    });
    });
</script>  
<!-- Delete confirmation box -->  
<!-- Delete confirmation end --> 
        </div><!-- /.modal-content -->
    
</div><!-- /.modal-dialog -->