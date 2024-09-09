<?php  $users_data=$this->session->userdata('auth_users'); 
 if(isset($examination_id) && $examination_id!='')
  {
    $examination_id=$examination_id;
  }
  else
  {
    $examination_id=$this->session->userdata('sess_examin_id');
  }

  if(isset($blood_detail_id) && $blood_detail_id!='')
  {
    $blood_detail_id=$blood_detail_id;
  }
  else
  {
    $blood_detail_id=$this->session->userdata('sess_blood_detail_id');
  }

  if(isset($blood_detail_id) && $blood_detail_id!='')
  {
    $blood_detail_id=$blood_detail_id;
  }
  else
  {
    $blood_detail_id=$this->session->userdata('sess_blood_detail_id');
  }

  if(isset($qc_id) && $qc_id!='')
  {
    $qc_id=$qc_id;
  }
  else
  {
    $qc_id=$this->session->userdata('sess_qc_rec_id');
  }

?>
<form method="post" id="blood_detail_form">
  <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>" id="donor_id">
  <input type="hidden" name="examination_id" value="<?php echo $examination_id; ?>" id="examination_id">
  <input type="hidden" name="blood_detail_id" value="<?php echo $blood_detail_id; ?>" id="blood_detail_id">
  <input type="hidden" name="qc_id" id="qc_id" value="<?php echo $qc_id; ?>" >

<?php
 // print_r($qc_data);
//  print_r($qc_data_fields);
?>

  <div class="">
    <div class="row mb-5">
       <div class="col-md-2"><b>Technician Name</b></div>
          <div class="col-md-4">
      <select name="technician_id" id="technician_id" class="m_input_default">
              <option value="">Select Technician</option>
              <?php
              if(!empty($technician_data))
              {
                
                foreach($technician_data as $technician_data_list)
                {
                    if($technician_data_list!='empty')
                    { 
                        if($qc_data['technician_id']==$technician_data_list->emp_id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$technician_data_list->emp_id.'" '.$select.'>'.$technician_data_list->name.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$technician_data_list->emp_id.'" >'.$technician_data_list->name.'</option>';
                    }
                }
              }
              ?> 
            </select> 
          
          </div> 
    </div>


    <?php 
    if($qc_id==0)
    {
//print_r("hello");
      if($qc_fields!="empty")
      {
        echo '<div class="row mb-5">
        <div class="col-md-2"><b>Bag QC Test </b></div>
        <div class="col-md-3"><b>Method</b></div>
        <div class="col-md-3"><b>Result</b></div>
        </div>';
        $i=1;
        $html='';
        foreach($qc_fields as $fd)
        {
          //print_r($fd);

          echo '<div class="row mb-5"><div class="col-md-2"><b>'.$fd->qc_field.'</b><input type="hidden" name="qc_field_id['.$i.']" value='.$fd->id.' ></div>';
          $get_id=$fd->id;
          $get_sub_category=bag_qc_subcategory_name($fd->id);
          if($get_sub_category!='empty')
          {
             echo $html= '<div class="col-md-10"><select name="qc_method['.$i.']" id="qc_method_'.$fd->id.'"><option value="">Select Categories</option>';
           foreach ($get_sub_category as $value) {
              
           echo $html='<option value='.$value->id.'>'.$value->qc_field.'</option>';
           
           }
           echo $html='</select>';
           //echo $html;
           }
           else
           {
            echo $html= '<div class="col-md-10"><select name="qc_method['.$i.']" id="qc_method_'.$fd->id.'"><option value="">Select Categories</option></select>';
           }

           echo '<input type="hidden" name="'.$i.'" value="" class="get_type_field_id" id="hidden_id_'.$i.'"><select name="qc_result['.$i.']" id="res_type_id_'.$i.'" onchange="get_res_type(this.value,'.$i.')"><option value="">Select Result</option><option value="1">Positive</option><option value="2">Negative</option></select><div id="qc_result_get_'.$i.'"></div></div></div>';
        $i++;            
        }

      }
    }
    else if($qc_id > 0)
    {
     // print_r("hellodd");
      $i=1;  
      $ex_array=array();
      if($qc_data_fields!="empty")
      {

        $i=1;
        $select='';
        echo '<div class="row mb-5">
        <div class="col-md-2"><b>Bag QC Test </b></div>
        <div class="col-md-3"><b>Method</b></div>
        <div class="col-md-3"><b>Result</b></div>
        </div>';
        foreach($qc_data_fields as $qc_cat_data)
        {

          //print_r($qc_cat_data);
          array_push($ex_array,$qc_cat_data->qc_field_id);
          echo '<div class="row mb-5"><div class="col-md-2"><b>'.$qc_cat_data->qc_field.'</b><input type="hidden" name="qc_field_id['.$i.']" value='.$qc_cat_data->qc_field_id.' ></div>';
          $get_id=$qc_cat_data->qc_field_id;
          $get_sub_category_data=bag_qc_subcategory_name($get_id);
           
          if($get_sub_category_data!='empty')
          {

             echo $html= '<div class="col-md-10"><select name="qc_method['.$i.']" id="qc_method_'.$qc_cat_data->qc_field_id.'"><option value="">Select Categories</option>';
           foreach ($get_sub_category_data as $value) {

            //print_r($value);
               
               if($value->id==$qc_cat_data->method)
               {
                $select='selected';
               }
               else{
                $select='';
               }
           echo $html='<option '.$select.' value="'.$value->id.'">'.$value->qc_field.'</option>';
           
           }
           echo $html='</select>';
           
           }
           else
           {
            echo $html= '<div class="col-md-10"><select name="qc_method['.$i.']" id="qc_method_'.$qc_cat_data->id.'"><option value="">Select Categories</option></select>';
           }

           $select_res='';
           $select_sec='';
                  if($qc_cat_data->result==1) 
                    {
                      $select_res='selected';
                    }
                    elseif($qc_cat_data->result==2)                   
                    {
                      $select_sec='selected';
                    }
                    else
                    {
                      $select_res='';
                      $select_sec='';
                    }
                  //}


           echo '<input type="hidden" name="'.$i.'" value="" class="get_type_field_id" id="hidden_id_'.$i.'"><select name="qc_result['.$i.']" id="res_type_id_'.$i.'" onchange="get_res_type(this.value,'.$i.')"><option value="">Select Result</option><option '.$select_res.' value="1">Positive</option><option '.$select_sec.' value="2">Negative</option></select><div id="qc_result_get_'.$i.'"></div></div></div>';
        $i++;
        }  
      }
    
      $x=$i;
      if($qc_fields!="empty")
      {

        foreach($qc_fields as $fd_edit)
        {
         
          if(!in_array($fd_edit->id, $ex_array))
          {
              echo '<div class="row mb-5"><div class="col-md-2"><b>'.$fd_edit->qc_field.'</b><input type="hidden" name="qc_field_id['.$x.']" value='.$fd_edit->id.' ></div>';
          $get_id=$fd_edit->id;
          $get_sub_category=bag_qc_subcategory_name($fd_edit->id);
          if($get_sub_category!='empty')
          {
             echo $html= '<div class="col-md-10"><select name="qc_method['.$x.']" id="qc_method_'.$fd_edit->id.'"><option value="">Select Categories</option>';
           foreach ($get_sub_category as $value) 
           {
             //print"<pre>"; print_r($value);
           echo $html='<option value='.$value->id.'>'.$value->qc_field.'</option>';
           
           }
           echo $html='</select>';
           //echo $html;
           }
           else
           {
            echo $html= '<div class="col-md-10"><input type="hidden" name="qc_field_id['.$x.']" value='.$fd_edit->id.' ><select name="qc_method['.$x.']" id="qc_method_'.$fd_edit->id.'"><option value="">Select Categories</option></select>';
           }
            

            
           echo '<input type="hidden" name="'.$x.'" value="" class="get_type_field_id" id="hidden_id_'.$x.'"><select name="qc_result['.$x.']" id="res_type_id_'.$x.'" onchange="get_res_type(this.value,'.$x.')"><option value="">Select Result</option><option value="1">Positive</option><option value="2">Negative</option></select><div id="qc_result_get_'.$x.'"></div></div></div>';
           
                     
          }
        $x++; 
        }
      }
      
    }
    ?>
 
    <div class="row mb-5">
      <div class="col-md-2"><b>Final Result</b></div>
      <div class="col-md-4">
        <input type="text" name="final_result" id="final_result" value="<?php if($qc_data!="empty") { echo $qc_data['final_result']; } ?>">
        <span id="bag_bar_code_error"></span>        
      </div>  
    </div>

    <div class="row mb-5">
    <div class="col-md-2"><b>Blood Condition</b></div>
      <div class="col-md-4">
        <input type="radio" name="bld_cndtn" value="1" id="bld_cndtn" checked=checked >Accepted
        <input type="radio" name="bld_cndtn" value="2" id="bld_cndtn_re" <?php if($qc_data!="empty") { if($qc_data['blood_condition']==2){ echo "checked=checked"; } } ?> >Rejected
      </div>
    </div>

    <div class="row mb-5">
    <div class="col-md-2"><b>Status</b></div>
      <div class="col-md-5">
        <input type="radio" name="donor_status" value="1" id="donor_status" checked=checked >In Stock
        <input type="radio" name="donor_status" value="2" id="donor_status" <?php if($qc_data!="empty") { if($qc_data['donor_status']==2){ echo "checked=checked"; } } ?> >Awaiting Results
        <input type="radio" name="donor_status" value="3" id="donor_status" <?php if($qc_data!="empty") { if($qc_data['donor_status']==3){ echo "checked=checked"; } } ?> >Unfit to Donate
        <input type="radio" name="donor_status" value="4" id="donor_status" <?php if($qc_data!="empty") { if($qc_data['donor_status']==4){ echo "checked=checked"; } } ?> >
        Discarded (Test Failed)
      </div>
    </div>

    <div class="row m-b-5">
    <div class="col-md-2"><label>QC Date/Time</label>
    </div>
        <div class="col-md-10">

               <input type="text" name="qc_date" class="w-100px datepicker" placeholder="dd-mm-yyyy" value="<?php if($qc_data!="empty" && $qc_data['qc_date']!='0000-00-00') { echo date('d-m-Y',strtotime($qc_data['qc_date'])); }else{ echo date('d-m-Y');} ?>">
               <input type="text" name="qc_time" class="w-95px datepicker3" placeholder="" value="<?php if($qc_data!="empty") { echo $qc_data['qc_time']; } else{echo date('H:i:s');} ?>">
              <!-- <select class="w-50px">
                  <option>AM</option>
                  <option>PM</option>
               </select>-->
              <?php if(!empty($form_error)){ echo form_error('operation_date'); } ?>
            </div>
          </div>


    <div class="row mb-12">
      <div class="col-md-2"><b>Remarks</b></div>
      <div class="col-md-10">
         <textarea name="remark" id="remark" style="width:746px;height:90px;"><?php if($qc_data!="empty") { echo $qc_data['remark']; } ?></textarea>
      </div>
    </div>

    
    <?php //if($qc_fields!="empty")  { ?>
    <div class="row mb-5">
      <div class="col-md-10"></div>
          <div class="col-md-3">
              <input type="button" style="margin-bottom: 20px;margin-top: 18px;" class="btn-update" name="examination_submit" value="Submit"  onclick="sub_blood_qc_frm();return false;">
               <button type="button" class="btn-update" onclick="window.location.href='<?php echo base_url('blood_bank/donor'); ?>'">
          <i class="fa fa-sign-out"></i> Exit
        </button>
          </div>

      <div class="col-md-1"></div>    
    </div>
    <?php //} ?>

  </div>
</form>



<script type="text/javascript">

function get_res_type(val,name)
{
  $("#hidden_id_"+name).val(val);

  if($("#res_type_id_"+name).val()==1)
  {
    $("#bld_cndtn_re").prop('checked', true);
    $("#bld_cndtn").prop('checked', false);
    $("#res_type_id_"+name).css('border', '2px solid red');
  }
  else
  {
    if($("#bld_cndtn").val==2)
    {

    }
    else
    {
      $("#bld_cndtn").prop("checked", true);
      $("#bld_cndtn_re").prop("checked", false);

    }
    $("#res_type_id_"+name).css('border', '2px solid green');
     $("#qc_result_get_"+'<?php echo $i; ?>').html("");
    
  }
}

function sub_blood_qc_frm()
{
 // $(".get_type_field_id").each(function(){

  <?php $i=1;

  foreach ($qc_fields as $fd)
  {
    ?>
     if($("#res_type_id_"+'<?php echo $i; ?>').val()==1)
      {


      //$("#res_type_id_"+'<?php echo $i; ?>').css('border:1px solid red');
      //$("#bld_cndtn_re").attr('checked', true);
      // $("#bld_cndtn").attr('checked', false);
       //var error= 1;
       
      }
      else

      {
         //$("#bld_cndtn").attr('checked', true);
        //$("#bld_cndtn_re").attr('checked', false);
         //$("#qc_result_get_"+'<?php echo $i; ?>').html("");

      }
        
  <?php $i++;} ?>
      
       

  //if(error!=1)
  //{
    $.ajax({
          type: "POST",
          url: "<?php echo base_url('blood_bank/donor_examinations/save_blood_qc_details');?>",
          data: $("#blood_detail_form").serialize(),
          dataType:'json',
          success: function(result) 
          {
            if(result.st==0)
            {
              $("#blood_bag_error").html(result.bag_type);
              $("#bag_bar_code_error").html(result.bar_code);
            }
            else if(result.st==1)
            {

             // location.reload();
/*
              $("#blood_bag_error").html('');
              $("#bag_bar_code_error").html('');
                flash_session_msg(result.msg);*/

                 $("#blood_bag_error").html('');
              $("#bag_bar_code_error").html('');
                flash_session_msg(result.msg);
                if(result.st==1)
                {
                  
                  window.setTimeout(function(){

                    // Move to a new location or you can do something else
                    window.location.href='<?php echo base_url('blood_bank/donor'); ?>';

                }, 2000);
                }

                 //location.reload();
              
              
            }
          }
      });
  //}
  
}
 $('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true 
  });

   $('.datepicker3').datetimepicker({
     format: 'LT'
  });
</script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap-datetimepicker.css">
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>bootstrap-datetimepicker.js"></script>
