
<?php 
/* start thermal printing */
    $simulation = get_simulation_name($all_detail['partograph_list'][0]->simulation_id);
    $template_data = str_replace("{patient_name}",$simulation.' '.$all_detail['partograph_list'][0]->patient_name,$template_data);

    $booking_date_time='';
    if(!empty($all_detail['partograph_list'][0]->booking_date) && $all_detail['partograph_list'][0]->booking_date!='0000-00-00')
{
    $booking_date_time = date('d-m-Y',strtotime($all_detail['partograph_list'][0]->booking_date)); 
}

    $booking_time ='';
    if(!empty($all_detail['partograph_list'][0]->booking_time) && $all_detail['partograph_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['partograph_list'][0]->booking_time)>0)
    {
        $booking_time = date('h:i A', strtotime($all_detail['partograph_list'][0]->booking_time));    
    }
    
     $template_data = str_replace("{gravida}",$all_detail['partograph_list'][0]->gravida,$template_data);
     $template_data = str_replace("{para}",$all_detail['partograph_list'][0]->para,$template_data);
   

    $template_data = str_replace("{patient_reg_no}",$all_detail['partograph_list'][0]->patient_code,$template_data);

    $template_data = str_replace("{mobile_no}",$all_detail['partograph_list'][0]->mobile_no,$template_data);
    
    $template_data = str_replace("{booking_code}",$all_detail['partograph_list'][0]->booking_code,$template_data);
    $template_data = str_replace("{booking_date}",$booking_date_time.' '.$booking_time,$template_data);
    $template_data = str_replace("{doctor_name}",'Dr. '.get_doctor_name($all_detail['partograph_list'][0]->attended_doctor),$template_data);

    $template_data = str_replace("{ref_doctor_name}",get_doctor_name($all_detail['partograph_list'][0]->referral_doctor),$template_data);
    
    $spec_name='';
        $specialization = get_specilization_name($all_detail['partograph_list'][0]->specialization_id);
        if(!empty($specialization))
        {
            $spec_name= str_replace('(Default)','',$specialization);
        }
        
    $template_data = str_replace("{specialization}",$spec_name,$template_data);

       if(!empty($all_detail['partograph_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['partograph_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_type}",$all_detail['partograph_list'][0]->relation,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_type}",'',$template_data);
        }

    if(!empty($all_detail['partograph_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['partograph_list'][0]->relation_simulation_id);
        $template_data = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['partograph_list'][0]->relation_name,$template_data);
        }
        else
        {
         $template_data = str_replace("{parent_relation_name}",'',$template_data);
        }               
    

    	$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['partograph_list'][0]->gender];
        $age_y = $all_detail['partograph_list'][0]->age_y; 
        

        $age = "";
        if($age_y>0)
        {
        $year = 'Y';
        if($age_y==1)
        {
          $year = 'Y';
        }
        $age .= $age_y." ".$year;
        }
       
        $patient_age =  $age;
        $gender_age = $gender.'/'.$patient_age;

    $template_data = str_replace("{patient_age}",$gender_age,$template_data);
    $pos_start = strpos($template_data, '{start_loop}');
    $pos_end = strpos($template_data, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data,$pos_start+12,$row_last_length-12);



    // Replace looping row//
    $patient_test_name="";
    $patient_pres = '';
    $prv_history ='';
    $personal_history ='';
    $chief_complaints ='';
    $examination ='';
    $diagnosis ='';
    $suggestion ='';
    $remark ='';
    $next_app = "";
   
    

    $template_data = str_replace("{patient_test_name}",$patient_test_name,$template_data);
    $template_data = str_replace("{patient_pres}",$patient_pres,$template_data);
    $template_data = str_replace("{prv_history}",$prv_history,$template_data);
    $template_data = str_replace("{personal_history}",$personal_history,$template_data);
    $template_data = str_replace("{chief_complaints}",$chief_complaints,$template_data);
    $template_data = str_replace("{examination}",$examination,$template_data);
    $template_data = str_replace("{diagnosis}",$diagnosis,$template_data);
    $template_data = str_replace("{suggestion}",$suggestion,$template_data);
    $template_data = str_replace("{remark}",$remark,$template_data);
    $template_data = str_replace("{appointment_date}",$next_app,$template_data);
    
    $template_data = str_replace("{margin_left}",$template_data_left,$template_data);
    $template_data = str_replace("{margin_right}",$template_format_right,$template_data);
    $template_data = str_replace("{margin_top}",$template_format_top,$template_data);
    $template_data = str_replace("{margin_bottom}",$template_format_bottom,$template_data);
    

    echo $template_data; 

$this->session->unset_userdata('opd_partograph_id');

if(!empty($download_type) && $download_type==2)
{
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>html2canvas.js"></script>
<script>

$(document).ready(function() { 
     html2canvas($("page"), {
        onrendered: function(canvas) {
           var imgsrc = canvas.toDataURL("image/png");
           $.ajax({
                url:'<?php echo base_url('prescription/save_image')?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['partograph_list'][0]->patient_name; ?>",
                    patient_code:"<?php echo $all_detail['partograph_list'][0]->patient_code; ?>"
                    },
                success: function(result)
                {                
                     location.href =result;
                }

                
            });
           
        }
     });
  });   

</script>
<?php } ?>