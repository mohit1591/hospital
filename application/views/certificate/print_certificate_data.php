<div class="modal-dialog modal-100 m-0" style="width:785px;">

  
<form action="" name="certificate_form" id="certificate_form">
<div class="modal-content">
	<div class="modal-body"> 
	
<script type="text/javascript" src="<?php echo ROOT_JS_PATH; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo ROOT_PLUGIN_PATH; ?>ckeditor/ckeditor.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>bootstrap.min.css">



		
	
	
	<?php 
  
	$signature_data['sign_img'] ='';
	if(!empty($signature_data['sign_img'] ) && file_exists(ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_data['sign_img'])) 
	{
		$file_name = ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_data['sign_img'];
		$signature_data['sign_img'] = '<img src="'.$file_name.'" width="100px" />';  	
	}
	

	 
	 $genders = array('0'=>'Female','1'=>'Male');
        $gender = $genders[$form_data['gender']];
        $age_y = $form_data['age_y']; 
        $age_m = $form_data['age_m'];
        $age_d = $form_data['age_d'];

        $age = "";
        if($age_y>0)
        {
        $year = 'Year';
        if($age_y==1)
        {
          $year = 'Y';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'Month';
        if($age_m==1)
        {
          $month = 'Month';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'Day';
        if($age_d==1)
        {
          $day = 'Day';
        }
        $age .= ", ".$age_d." ".$day;
        }
        $patient_age =  $age;
        //$gender_age = $gender.'/'.$patient_age;
	 
	  $template_data['template'] = str_replace("{patient_age}",$patient_age,$template_data['template']);
    $simulation = $this->db->where('id',$form_data['relation_simulation_id'])->get('hms_simulation')->row_array()['simulation'];

	   $template_data['template'] = str_replace("{gender}",$gender,$template_data['template']);
     $template_data['template'] = str_replace("{diagnosis}",$diagnosis,$template_data['template']);
     $template_data['template'] = str_replace("{doctor_name}","Dr. ".$form_data['doctor_name'],$template_data['template']);
    //  $template_data['template'] = str_replace("{dob}",date('d-m-Y',strtotime($form_data['dob'])),$template_data['template']);
     $template_data['template'] = str_replace("{relation_name}",$form_data['g_relation'].' '.$simulation.' '.$form_data['relation_name'],$template_data['template']);
     $template_data['template'] = str_replace("{mother_name}",$form_data['mother'],$template_data['template']);
     $template_data['template'] = str_replace("{address}",$form_data['address'],$template_data['template']);
     $template_data['template'] = str_replace("{current_date}",date('d-m-Y'),$template_data['template']);
     
	$template_data['template'] = str_replace("{patient_reg_no}",$form_data['patient_code'],$template_data['template']);
	

	$template_data['template'] = str_replace("{patient_name}",get_simulation_name($form_data['simulation_id'])." ".$form_data['patient_name'],$template_data['template']);

	 $template_data['template'] = str_replace("{mother}",$form_data['mother'],$template_data['template']);

	$template_data['template'] = str_replace("{father_husband}",$form_data['father_husband'],$template_data['template']);

	$template_data['template'] = str_replace("{country}",$form_data['country'],$template_data['template']);

	$template_data['template'] = str_replace("{city}",$form_data['city'],$template_data['template']);

	$template_data['template'] = str_replace("{state}",$form_data['state'],$template_data['template']);

	$template_data['template'] = str_replace("{address}",$form_data['address'],$template_data['template']);




  $template_data['template'] = str_replace("{date_of_admission}",$date_of_admission,$template_data['template']);
  $template_data['template'] = str_replace("{time_of_admission}",$time_of_admission,$template_data['template']);
  $template_data['template'] = str_replace("{date_of_death}",$date_of_death,$template_data['template']);
  $template_data['template'] = str_replace("{time_of_death}",$time_of_death,$template_data['template']);
  $template_data['template'] = str_replace("{cause_of_death}",$cause_of_death,$template_data['template']);
  $his_her = $form_data['gender'] == '0' ? "Her" : "His";
  $template_data['template'] = str_replace("{his_her}",$his_her,$template_data['template']);
  $template_data['template'] = str_replace("{birth_date}",$birth_date,$template_data['template']);
  $template_data['template'] = str_replace("{birth_time}",$birth_time,$template_data['template']);
  $template_data['template'] = str_replace("{birth_weight}",$birth_weight,$template_data['template']);


  if(!empty($form_data['dob']) && $form_data['dob'] != '1970-01-01'){
	  $template_data['template'] = str_replace("{dob}",date('d/m/Y',strtotime($form_data['dob'])),$template_data['template']);
  } else {
    $template_data['template'] = str_replace("{dob}", "",$template_data['template']);
  }
	if(!empty($doctor_data['doctor_name']))
	{
		$template_data['template'] = str_replace("{doctor_name}",$doctor_data['doctor_name'],$template_data['template']);
	}
	else
	{
		$template_data['template'] = str_replace("{doctor_name}",'',$template_data['template']);
	}
	
	
	 if(!empty($form_data['g_relation']))
        {
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);
        $template_data['template'] = str_replace("{parent_relation_type}",$form_data['g_relation'],$template_data['template']);
        }
        else
        {
         $template_data['template'] = str_replace("{parent_relation_type}",'',$template_data['template']);
        }

    if(!empty($form_data['relation_name']))
        {
        $rel_simulation = get_simulation_name($form_data['relation_simulation_id']);
        $template_data['template'] = str_replace("{parent_relation_name}",$rel_simulation.' '.$form_data['relation_name'],$template_data['template']);
        }
        else
        {
         $template_data['template'] = str_replace("{parent_relation_name}",'',$template_data['template']);
        }
	
	
	if(!empty($signature_data['signature']))
	{
		$template_data['template'] = str_replace("{signature}",$signature_data['signature'],$template_data['template']);
	}
	else
	{
		$template_data['template'] = str_replace("{signature}",'',$template_data['template']);
	}
	
	$template_data['template'] = str_replace("{sign_img}",$signature_data['sign_img'],$template_data['template']);
	
	?>
	<div style="*margin:0 0 0 0%;">
	<textarea id="message1" name="template_header" style="" rows="10" cols="50"><?php echo $template_data['template_header']; ?></textarea>
  <textarea id="message" name="template" style="" rows="10" cols="50"><?php echo $template_data['template']; ?></textarea>
	</div>
	<input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
	<input type="hidden" name="type" value="<?php echo $type; ?>">
	<input type="hidden" name="certificate_id" value="<?php echo $certificate_id; ?>">
	

	
</div>

<div class="modal-footer">
   <label for="without_header"><input id="without_header" type="checkbox" name="flag" value="1"> Without Header</label>
	<button class="btn-save" name="submit" type="submit"><i class="fa fa-floppy-o"></i> Print</button>
</div>

</div><!-- /.modal-content -->
	
   </form> 
</div><!-- /.modal-dialog -->
<script>
$(document).ready(function(){
  //get_unit(); 
CKEDITOR.replace( 'message', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );

CKEDITOR.replace( 'message1', {
    fullPage: true, 
    allowedContent: true,
    autoGrow_onStartup: true,
    enterMode: CKEDITOR.ENTER_BR
} );


})
$("#certificate_form").on("submit", function(event) 
{
var flagData = $("#without_header").val();
var flag = 0;
var isChecked = $("#without_header").prop('checked');
if(isChecked) {
  flag = 1;
} else {
  flag = 0;
}
for (instance in CKEDITOR.instances) {
        CKEDITOR.instances[instance].updateElement();
    } 
  event.preventDefault(); 
  $('.overlay-loader').show();
  $.ajax({
    url: "<?php echo base_url(); ?>certificate/print_final_certificate/",
    type: "post",
    data: $(this).serialize(),
    success: function(result) 
    {
    	
       var url_cer = '<?php echo base_url('certificate/prints/'); ?>'+result + '/'+flag;
      
       var printWindow = window.open(url_cer, 'Print', 'left=200, top=200, width=1050, height=600, toolbar=0, resizable=0');
		  printWindow.addEventListener('load', function(){
		  printWindow.print();
		  //printWindow.close();
		  }, true);
      // print_window_page('<?php echo base_url('certificate/print/'); ?>'+result);
       //alert(url);
       //print_window_page(url);
       //alert(result);
       //flash_session_msg(result);    
       //$('.overlay-loader').hide();    
    }
  });
});
</script>