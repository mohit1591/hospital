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
?>
<form method="post" id="blood_detail_form" enctype="multipart/form-data">
  <input type="hidden" name="donor_id" value="<?php echo $donor_id; ?>" id="donor_id">
  <input type="hidden" name="collection_time" value="<?php echo $start_time; ?>" id="collection_time">
  <input type="hidden" name="examination_id" value="<?php echo $examination_id; ?>" id="examination_id">
  <input type="hidden" name="blood_detail_id" value="<?php echo $blood_detail_id; ?>" id="blood_detail_id">
  <div class="">
    <div class="row mb-5">
       <div class="col-md-2"><b>Phlebotomist</b></div>
          <div class="col-md-4">
            
         
      <select name="phlebotomist" id="phlebotomist" class="m_input_default">
              <option value="">Select Phlebotomist</option>
              <?php
              if(!empty($phlebotomist_data))
              {
                
                foreach($phlebotomist_data as $phlebotomist_data_list)
                {
                    if($phlebotomist_data_list!='empty')
                    { 
                        if($blood_details['phlebotomist']==$phlebotomist_data_list->emp_id)
                          $select="selected=selected";
                        else
                          $select="";

                          echo '<option value="'.$phlebotomist_data_list->emp_id.'" '.$select.'>'.$phlebotomist_data_list->name.'</option>';

                    }
                    else
                    {
                      echo '<option value="'.$phlebotomist_data_list->emp_id.'" >'.$phlebotomist_data_list->name.'</option>';
                    }
                }
              }
              ?> 
            </select> 

          </div> 
    </div>
    <div class="row mb-5">
      <div class="col-md-2"><b>Blood Bag Type <span class="star">*</span></b></div>
      <div class="col-md-4">
        <select name="blood_bag_type" id="blood_bag_type" >
            <option value="">Select Blood Bag</option>
            <?php
                if($blood_bags!="empty")
                {
                  foreach($blood_bags as $bags)
                  {
                      if($blood_details!="empty")
                      {
                          if($blood_details['blood_bag_type_id']==$bags->id)
                            $bg_select="selected=selected";
                          else
                            $bg_select="";
                      }
                      else
                      {
                        $bg_select="";
                      }
                    echo '<option '.$bg_select.' value='.$bags->id.'>'.$bags->bag_type.'</option>';
                  }
                }
            ?>
        </select>
        <span id="blood_bag_error"></span>
      </div>
      <div class="col-md-2"><b>Quantity</b></div>
      <div class="col-md-4">
      
        <input type="text" name="quantity" id="quantity" class='numeric' value="<?php if($blood_details!="empty") { echo $blood_details['quantity']; } ?>" onkeyup="open_text_box();"> Unit

          <div id="bardiv_id" style="margin-top: 5px;">
            
          <?php $i=0;
          if(!empty($bar_code_details))
          {
            foreach($bar_code_details as $bar_codes)
            {  
            ?>
          <input type='text'  name='bar_code_detail[<?php echo $i; ?>][bar_code]' id='bar_code_id_<?php echo $i;?>' value="<?php echo $bar_codes->bar_code; ?>" class='' placeholder='Bag Barcode' style='margin-right: 2px;width: 81px;margin-top: 2px;' />
          <div id='bag_bar_code_error_<?php echo $i;?>' class='text-danger'></div>
          <?php $i++;
            }
          }?>

           

          </div>   
      </div>
    </div>

    <div class="row mb-5">
      <div class="col-md-2"><b>Venipuncture</b></div>
        <div class="col-md-4">
          <input type="radio" name="venipuncture" value="1" id="venipuncture" checked=checked >Right
          <input type="radio" name="venipuncture" value="2" id="venipuncture" <?php if($blood_details!="empty") { if($blood_details['venipuncture']==2){ echo "checked=checked"; } } ?> >Left
        </div>
      </div>

    <div class="row mb-5">
      <div class="col-md-2"><b>Collection Date</b></div>
      <div class="col-md-4">
      <?php //echo $blood_details['collection_date'];
              ?>
        <input type="text"  name="collection_date" class="collection_date" id="collection_date" value="<?php if($blood_details!="empty" && $blood_details['collection_date']!="1970-01-01") { echo date('d-m-Y',strtotime($blood_details['collection_date'])).' '. date('h:i A',strtotime($start_time)); }else {echo date('d-m-Y') .' '. date('h:i A',strtotime($start_time));} ?>" onchange="set_blood_expiry();return false;">
      </div>
      <div class="col-md-2"><b>End Time</b></div>
      <div class="col-md-4">
       
        <input style="margin-top:2px;" class="datepicker3"  type="text" name="end_time" id="end_time" placeholder="End Time" value="<?php if($blood_details!="empty") { echo $blood_details['end_time']; } ?> ">  
        <input class="btn-new move1" type="button" value="End Time" onclick="set_current_time('end_time');">
      </div>  
    </div>

    <!--<div class="row mb-5">
      <div class="col-md-2"></div>
      <div class="col-md-4">
        
      </div>  

      <div class="col-md-2"><b>Collection Duration</b></div>
      <div class="col-md-4">
        <input type="text" readonly name="collection_duration" id="collection_duration" value="<?php if($blood_details!="empty") { echo $blood_details['collection_duration']; } ?>">               
      </div>
    </div>-->

    <?php
      $setting=get_general_settings('BLOOD_EXP_FROM_DATE_COLL');
      if($setting!="empty")
      {
        $value=$setting[0]->setting_value1;
        $expiry_time= date("d-m-Y", strtotime(" +".$value." months"));
      }
      else
      {
        $expiry_time="";
      }
    ?>

    <div class="row mb-5">
      <div class="col-md-2"><b>Blood Expiry Date</b></div>
      <div class="col-md-4">
         <input type="text" name="expiry_date" class="expiry_date" id="expiry_date" value="<?php if($blood_details!="empty") { echo date('d-m-Y',strtotime($blood_details['expiry_date'])).' '. date('h:i A',strtotime($start_time)); } else { echo $expiry_time .' '. date('h:i A',strtotime($start_time)); } ?>" >
      </div>

      <div class="col-md-2"><b>Post Complication</b></div>
      <div class="col-md-4">
        <select name="post_compilation" id="post_compilation" onchange="get_other_val(this.value);">
              <option value="">Select Post Complication </option>
              <?php
              //print_r($donor_data);
                if($post_complication!="empty")
                {
                  foreach($post_complication as $post_complication_id)
                  {
                    if($blood_details!="empty")
                    {
                      if($blood_details['post_compilations']==$post_complication_id->id)
                        $serv="selected=seleceted";
                      else
                        $serv="";
                      echo '<option value='.$post_complication_id->id.' '.$serv.' >'.$post_complication_id->post_name.'</option>';
                    }
                    else
                    {
                      echo '<option value='.$post_complication_id->id.'>'.$post_complication_id->post_name.'</option>';  
                    }
                    
                  }
                }
              ?>
            </select>
            <input type="hidden" class='' id='other_id' value="<?php if($donor_data!='empty'){ echo $donor_data['other_id']; } ?>" name="other_id">
        
        
   



        <!-- <textarea name="post_compilation" id="post_compilation"><?php //if($blood_details!="empty") { echo $blood_details['post_compilations']; } ?> </textarea>      -->    
      </div>



        </div>


         <?php
        $display='';
        if(!empty($blood_details['other_id']))
        {
        $display='display:block';
        }
        else
        {
        $display='display:none';
        }
        ?>
        <div class="row mb-5" id='other_div' style='<?php echo $display ;?>'>

        <div class="col-md-2"><b></b></div>
        <div class="col-md-4">
        </div>
        <div class="col-md-2"><b>Others</b></div>
        <div class="col-md-4">
        <input type="text" class='' value="<?php if($blood_details!='empty'){ echo $blood_details['other_post']; } ?>" name="other_post">


        </div>
        </div>



    <div class="row mb-5">
      <div class="col-md-2"><b>Remarks</b></div>
      <div class="col-md-4">
         <textarea name="remark" id="remark"><?php if($blood_details!="empty") { echo $blood_details['remark']; } ?></textarea>
      </div>
    </div>




    <div class="row mb-2">
          <div class="col-md-2"><b>Donor Under Taking  Form</b></div>
         
          <div class="col-md-4">
            <input type="hidden" id="capture_img_right_image" name="capture_img_right_image" value="" />
            <input type="hidden" name="old_taking_form"  value="<?php if($blood_details!='empty') {echo $blood_details['taking_form']; }?>"/>
           
            <input type="file" id="img-input3" accept="image/*" name="taking_form" id="taking_form">
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
           if($blood_details!='empty' && isset($blood_details['taking_form'])&& $blood_details['taking_form']!=''){
           $img_path = ROOT_UPLOADS_PATH.'blood_bank/donor_profile/'.$blood_details['taking_form'];
          } 
         // $print_url = "'".base_url('sales_medicine/print_sales_report/'.$sales_medicine->id)."'"; 
          ?>

          <img id="pimg3" src="<?php echo $img_path; ?>" class="img-responsive" >


          <a class="btn-custom" id="print_id" style="float:right;" href="javascript:void(0)" onClick="hide_div(); printDiv('printdiv')" title="Print" ><i class="fa fa-print"></i> Print</a>
          </div>

          </div>
          <div class="col-md-3"></div>
        </div>

         <div class="col-md-2"></div>
          <div class="col-md-4">
          <input type="submit" id="data_handler" style="margin-bottom: 20px;margin-top: 18px;"  name="examination_submit" value="Submit" class="btn-update" >
          </div>
          <div class="col-md-1"></div>    
        
        </div>

    
   
  </div>
</form>



<script type="text/javascript">
<?php ?>
function open_text_box()
{
  var qty=  $('#quantity').val();
  $('#bardiv_id').html('');
  $('.ba_div_id').html('');
 
  var i;
  for (i = 0; i < qty; i++) 
  {
    $('#bardiv_id').append("<input type='text'  name='bar_code_detail["+i+"][bar_code]' id='bar_code_id_"+i+"' class='' placeholder='Bag Barcode' style='margin-right: 2px;width: 81px;margin-top: 2px;' /><div id='bag_bar_code_error_"+i+"' class='text-danger'></div>");
  }
}
function get_other_val(val)
{
  //alert(val);
  var name= $("#post_compilation option:selected" ).text();
 
  
if(name=='other')
{
   $("#other_div").css("display", "block");
    $("#other_id").val(name);
}
else
{
 $("#other_div").css("display", "none");
 $("#other_id").val('');
}

  
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
      printWindow.document.write('<html moznomarginboxes mozdisallowselectionprint><head><title>Donor Under Taking  Form</title>');
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
    $('#pimg3').attr('src', e.target.result);
  }

  reader.readAsDataURL(input.files[0]);
  }
  }
    $("#img-input3").change(function(){
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


$("#blood_detail_form").on("submit", function(event) { 
  var qty=  $('#quantity').val();

  var i;
  for (i = 0; i < qty; i++) 
  {
    var data= $('#bar_code_id_'+i).val();

     /*if(data=='')
     {

       $('#bag_bar_code_error_'+i).html('Barcode field is required');
       var data_error=1;
       event.preventDefault(); 
       
     }*/
  }
  //if(data_error!=1)
  //{
   event.preventDefault(); 
    $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/save_blood_details');?>",
            dataType:'json',
            data: new FormData(this), 
             contentType: false,      
                cache: false,            
                processData:false, 
            success: function(result) 
            {
              if(result.st==0)
              {
                 $("#blood_bag_error").html(result.bag_type);
                 //$("#bag_bar_code_error").html(result.bar_code);
              }
              else if(result.st==1)
              {
                //location.reload();
                $("#blood_bag_error").html('');
                //$("#bag_bar_code_error").html('');
                flash_session_msg(result.msg);
              tab_navigation('component_details',$("#component_id").val(),'');
                $(".t1").removeClass('active');
                $(".t2").removeClass('active');
                $(".t3").addClass('active');
              }
            }
        });
  //}

});


function set_current_time(ref_id)
{
  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/calc_diff_times');?>",
            data: {'flag':1},
            success: function(result) 
            {
              $("#"+ref_id).val(result);
            }
        });

  setTimeout(function()
  { 
    var start_time=$("#start_time").val();
    var end_time=$("#end_time").val();
    calculate_difference_between_times(start_time, end_time);
  }, 1500);
}

function calculate_difference_between_times(start_time,end_time)
{
   var start_time=$("#start_time").val();
    var end_time=$("#end_time").val();
  if(end_time!="")
  {

    $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/calc_diff_times');?>",
            data: { 'start':start_time, 'end':end_time, 'flag':2 },
            success: function(result) 
            {
              $("#collection_duration").val(result);
            }
        });
  }
   
}


$(".collection_date").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });
    
    $(".expiry_date").datetimepicker({
        format: "dd-mm-yyyy HH:ii P",
        showMeridian: true,
        autoclose: true,
        todayBtn: true
    });
    
   
   
    $('.datepicker3').datetimepicker({
     format: 'LT'
  }); 
    

// Function to open datepicker
$('.datepicker').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
});

$('.datepicker1').datepicker({
    format: 'dd-mm-yyyy', 
    autoclose: true, 
});
// function to open datepicker



// Function to set blood expiry
function set_blood_expiry()
{

  $.ajax({
            type: "POST",
            url: "<?php echo base_url('blood_bank/donor_examinations/set_blood_expiry');?>",
            data: { 'collection_date':$("#collection_date").val() },
            success: function(result) 
            {
              var string='<input type="text"  name="expiry_date" id="expiry_date" class="expiry_date" value="'+result+'" >';
              //$("#expiry_date").val(result);
               $("#expiry_date").replaceWith(string);
               $('.expiry_date').val(result);
            
             
            }
        });
}
// function to set blood expiry

</script>