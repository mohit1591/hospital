<?php  $users_data=$this->session->userdata('auth_users');
  $sess_examin_id=$this->session->userdata('sess_examin_id');
  if(isset($examination_id) && $examination_id!='')
  {
    $examination_id=$examination_id;
  }
  else
  {
    $examination_id=$this->session->userdata('sess_examin_id');
  }

 ?>
<form method="post" id="examination_form" enctype="multipart/form-data">
  <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>" id="donor_id">
  <input type="hidden" name="examination_id"  value="<?php echo $examination_id; ?>" id="examination_id_ex">
  <div class="">
    <div class="row mb-5">
      <div class="col-md-2"><b>Examiner <span class="star">*</span></b></div>
      <div class="col-md-4">
      
      <select name="examiner_id" id="examiner_id" class="m_input_default">
              <option value="">Select Examiner</option>
              <?php
              if(!empty($examiner_data))
              {
                
                foreach($examiner_data as $examiner_data_list)
                {
                    if($examiner_data_list!='empty')
                    { 
                        if($examination_data['examiner_id']==$examiner_data_list->emp_id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$examiner_data_list->emp_id.'" '.$select.'>'.$examiner_data_list->name.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$examiner_data_list->emp_id.'" >'.$examiner_data_list->name.'</option>';
                    }
                }
              }
              ?> 
            </select> 
             <span id="examiner_error" ></span>   
                  
      </div>
    </div>

    <div class="row mb-5">
      <div class="col-md-2"><b>Illness</b></div>
      <div class="col-md-4">
        <input type="text" name="illness" id="illness" value="<?php if($examination_data!="empty") { echo $examination_data['illness'];}?>" >               
      </div>
    
      <div class="col-md-2"><b>Haemoglobin(gms%)</b></div>
      <div class="col-md-4">
        <input type="text" name="haemoglobin" id="haemoglobin" value="<?php if($examination_data!="empty") { echo $examination_data['haemoglobin']; } ?>">               
      </div>  
    </div>

    <div class="row mb-5">
      <div class="col-md-2"><b>Blood Pressure</b></div>
      <div class="col-md-4">
        <input type="text" name="blood_pressure" id="blood_pressure" value="<?php if($examination_data!="empty") { echo $examination_data['blood_pressure']; } ?>">               
      </div>
    
      <div class="col-md-2"><b>Temperature(°F)</b></div>
      <div class="col-md-4">
        <input type="text" name="temperature" id="temperature" value="<?php if($examination_data!="empty") { echo $examination_data['temperature']; } ?>">               
      </div>  
    </div>

    <div class="row mb-5">
      <div class="col-md-2"><b>Pulse Rate(/min)</b></div>
      <div class="col-md-4">
        <input type="text" name="pulse" id="pulse" value="<?php if($examination_data!="empty") { echo $examination_data['pulse']; } ?>">               
      </div>
      <div class="col-md-2"><b>Blood Group</b><span class="star">*</span></div>
      <div class="col-md-4">
        <select name="blood_group_id" id="blood_group_id">
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
        <span id="blood_group_error" ></span>     
      </div>  
    </div>

  <div class="row mb-5">
      <div class="col-md-2"><b>Respiratory rate</b></div>
      <div class="col-md-4">
        <input type="text" name="respiratory_rate" class='price_float' id="respiratory_rate" value="<?php if($examination_data!="empty") { echo $examination_data['respiratory_rate']; } ?>">               
      </div>
         
    </div>

    <?php
     $option_one="checked=checked";   $option_two="";   $option_three="";
      if($examination_data!="empty")
      {
        if($examination_data['outcome']==1)
          $option_one="checked=checked";
        else
          $option_one="";
        if($examination_data['outcome']==2)
          $option_two="checked=checked";
        if($examination_data['outcome']==3)
          $option_three="checked=checked";
      }
    ?>

    <div class="row mb-5">
      <div class="col-md-2"><b>Outcome</b></div>
      <div class="col-md-4">
         <input type="radio" name="outcome" value="1" <?php echo $option_one; ?> onchange="check_deferral(this.value);"> Accepted 
         <input type="radio" name="outcome" value="2" <?php echo $option_two; ?> onchange="check_deferral(this.value);"> Temporary Deferral 
         <input type="radio" name="outcome" value="3" <?php echo $option_three; ?> onchange="check_deferral(this.value);"> Permanent Deferral 
      </div>

      <div class="col-md-2"><b>Remarks</b></div>
      <div class="col-md-4">
        <textarea name="remark" id="remark"><?php if($examination_data!="empty") { echo $examination_data['remark']; } ?></textarea>         
      </div>
    </div>
    <!--<div class="row mb-5">
      <div class="col-md-2"><b>Start Time</b></div>
      <div class="col-md-4">
        <input class="numeric " type="text" readonly name="start_time" id="start_time" placeholder="Start Time" value="<?php if(($examination_data!="empty") &&($examination_data['start_time']!='00:00:00')) { echo $examination_data['start_time']; } ?>" readonly>              
      </div>
         
    </div>-->

 
        
       

    <?php
      if($examination_data!="empty")
      {
        if($examination_data['outcome']==1)
        {
          $style="display:none";
          $style1="display:none";
        }
        else if($examination_data['outcome']==2)
        {
          $style="display:block";
          $style1="display:block";
        }
        else if($examination_data['outcome']==3)
        {
          $style="display:block";
          $style1="display:none";
        }
        else
        {
          $style="display:none";
          $style1="display:none"; 
        }
      }
      else
      {
          $style="display:none";
          $style1="display:none"; 
      }
    ?>


    <div class="row mb-5">
      <div style="<?php echo $style; ?>" id="deferral_reason_div" >  
        <div class="col-md-2"><b>Deferral Reason</b></div>
        <div class="col-md-4">
          <select name="deferral_reason" id="deferral_reason" >
              <option value="">Select Deferral Reason</option>
              <?php
                if($deferral_reason!="empty")
                {
                    foreach($deferral_reason as $reasons)
                    {
                      if($examination_data!="empty")
                      {  
                        if($reasons->id==$examination_data['deferral_reason'])
                          $selected_reason ="selected=selecetd";
                        else 
                          $selected_reason ="";
                      }
                      else
                      {
                          $selected_reason =""; 
                      }  
                        echo "<option ".$selected_reason." value=".$reasons->id." >".$reasons->deferral_reason."</option>";
                    }
                }
              ?>
          </select>
        </div>  
      </div>

      <div style="<?php echo $style; ?>" id="elligible_by_div">
        <div class="col-md-2"><b>Eligible By</b></div>
        <div class="col-md-4">
          <input type="text" class="datepicker" name="eligible_by" id="eligible_by" value="<?php if($examination_data!="empty") { echo (strtotime($examination_data['deferral_eligiblity']) > 0) ? date('d-m-Y',strtotime($examination_data['deferral_eligiblity'])) : '' ; } ?>" >               
        </div>
      </div> 
    </div>


       <div class="row mb-2">
          <div class="col-md-2"><b>Physical Examination Form</b></div>
         
          <div class="col-md-4">
            <input type="hidden" id="capture_img_right_image" name="capture_img_right_image" value="" />
          <input type="hidden" name="old_examination_form"  value="<?php if($examination_data!='empty') {echo $examination_data['examination_form']; }?>"/>
            <input type="file" id="img-input2" accept="image/*" name="general_form" id="general_form"  onclick="set_current_time('start_time');">
              <?php
             
              ?>
          </div>

          
        </div>
         <div class="row mb-2" id="printdiv">

         <div class="col-md-2">
         </div>
         <div class="col-md-4">
           <div class="col-md-9 frm_s">
           <div class="rec-box">
          <?php
          //print '<pre>'; print_r($pat_data);
          $img_path = base_url('assets/images/photo.png');
           if($examination_data!='empty' && isset($examination_data['examination_form'])&& $examination_data['examination_form']!=''){
           $img_path = ROOT_UPLOADS_PATH.'blood_bank/donor_profile/'.$examination_data['examination_form'];
          } 
         // $print_url = "'".base_url('sales_medicine/print_sales_report/'.$sales_medicine->id)."'"; 
          ?>

          <img id="pimg2" src="<?php echo $img_path; ?>" class="img-responsive" >


          <a class="btn-custom" id="print_id" style="float:right;" href="javascript:void(0)" onClick="hide_div(); printDiv('printdiv')" title="Print" ><i class="fa fa-print"></i> Print</a>
          </div>

          </div>
          <div class="col-md-3"></div>
        </div>


        <div class="col-md-2"></div>
            <div class="col-md-4">
            <input type="submit" id="data_handler" style="margin-bottom: 20px;margin-top: 18px;"  name="examination_form" value="Submit" class="btn-update" >
            </div>
            <div class="col-md-1"></div> 
        </div>
    
  </div>
</form>



<script type="text/javascript">
function set_current_time(ref_id)
{
 
   
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/calc_times');?>",
            data: {'flag':1},
            success: function(result) 
            {
              $("#"+ref_id).val(result);
            }
        });

  setTimeout(function()
  { 
    var start_time=$("#start_time").val();    
  }, 1500);
}

  function hide_div()
{
 $('#print_id').css('display','none');
}
  function printDiv(divId) {
      var divContents = $("#printdiv").html();
      var printWindow = window.open('', '', 'height=400,width=800');
      // var style_s ="<style>#printdiv { background: white;display: block; margin: 1em auto 0;margin-bottom: 0.5cm;box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);}#printdiv{ width: 21cm;height: 25.7cm;  padding: 3em;font-size:13px; }    size: auto;   /* auto is the initial value */margin: 0;}</style>";
   $('#print_id').css('display','none');
      printWindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head><meta http-equiv="Content-Type" content="text/html; charset=euc-kr"><title>Physical Examination Form</title>');
        //var scr= $('#print_id').css('display','none');
      printWindow.document.write('</head><body onLoad="style_css();" >');
      printWindow.document.write(divContents);
    
      printWindow.document.write('</body></html>');
      printWindow.document.close();
      printWindow.print();
  }
  function show_div()
  {
   $('#print_id').css('display','block');
  }
  function readURL2(input) {
    if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
    $('#pimg2').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
  }
  }
    $("#img-input2").change(function(){
  readURL2(this);
  });

function check_deferral(val)
{
  if(val==1)
  {
    $("#deferral_reason_div").css("display",'none');
    $("#elligible_by_div").css("display",'none');
    $("#deferral_reason").val('');
    $("#eligible_by").val('');
  }
  else if(val==2)
  {
    $("#deferral_reason_div").css("display",'block');
    $("#elligible_by_div").css("display",'block');
    $("#deferral_reason").val('');
    $("#eligible_by").val('');
  }
  else if(val==3)
  {
    $("#deferral_reason_div").css("display",'block');
    $("#elligible_by_div").css("display",'none');
    $("#deferral_reason").val('');
    $("#eligible_by").val('');
  }
}

$("#examination_form").on("submit", function(event) { 
  event.preventDefault();  
//function sub_exa_frm()
//{
  $.ajax({
          type: "POST",
          url: "<?php echo base_url('blood_bank/donor_examinations/save_examination');?>",
          dataType:'json',
           data: new FormData(this),  
              contentType: false,      
              cache: false,            
              processData:false, 
         
          success: function(result) 
          {
            
            if(result.st==1)
            {
              
             
              //location.reload();
              if(result.flag==1)
              {
                
                //location.reload();
                $("#blood_group_error").html('');
                tab_navigation('blood_details',$("#blood_detail_id").val(),'');
                $(".t1").removeClass('active');
                $(".t2").addClass('active');
                
              }
              $('#examination_id_ex').val(result.examination_id);
              flash_session_msg(result.msg);
            }
            else if(result.st==0)
            {
              //alert(result[0].blood_group);
                $("#blood_group_error").html(result[0].blood_group);
                $("#examiner_error").html(result[0].examiner);
            }
          }
      });
});

// Function to open datepicker
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
    startDate : new Date(),  //endDate
});
// function to open datepicker

</script>