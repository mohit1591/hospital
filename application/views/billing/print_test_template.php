<?php 
/* start thermal printing */
error_reporting(0);
if($template_data->printer_id==2)
{

        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);
        $template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);     

        $pos_start = strpos($template_data->template, '{start_loop}');
        $pos_end = strpos($template_data->template, '{end_loop}');
        $row_last_length = $pos_end-$pos_start;
        $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
        // Replace looping row//
        $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
        $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
        //////////////////////// 
        $tr_html = "";
        if(!empty($all_detail['booking_list']['test_booking_list']))
        {
        	$i=1;
        	
        	foreach($all_detail['booking_list']['test_booking_list'] as $test_booking_list)
        	{ 
        		 $tr = $row_loop;
        		 $tr = str_replace("{s_no}",$i,$tr);
        		 $tr = str_replace("{test_id}",$test_booking_list->test_id,$tr);
                 $tr = str_replace("{test_name}",$test_booking_list->test_name,$tr);
        		 $tr = str_replace("{amount}",$test_booking_list->amount,$tr);
        		 $tr_html .= $tr;
        		 $i++;
        	  	 	 	 	
        	}
        }

        $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
        $template_data->template = str_replace("{total_discount}",'0.00',$template_data->template);
        $template_data->template = str_replace("{total_net}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
        $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list']['test_booking_list'][0]->amount,$template_data->template);
        $template_data->template = str_replace("{balance}",$all_detail['booking_list'][0]->balance,$template_data->template);
         echo $template_data->template; 

}
/* end thermal printing */


/* start dot printing */
if($template_data->printer_id==3)
{
        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);
        $template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);


		$genders = array('0'=>'Female','1'=>'Male');
        $gender = $genders[$all_detail['booking_list'][0]->gender];
        $age_y = $all_detail['booking_list'][0]->age_y; 
        $age_m = $all_detail['booking_list'][0]->age_m;
        $age_d = $all_detail['booking_list'][0]->age_d;

        $age = "";
        if($age_y>0)
        {
        $year = 'Years';
        if($age_y==1)
        {
          $year = 'Year';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'Months';
        if($age_m==1)
        {
          $month = 'Month';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'Days';
        if($age_d==1)
        {
          $day = 'Day';
        }
        $age .= ", ".$age_d." ".$day;
        }
        $patient_age =  $age;
        $gender_age = $gender.'/'.$patient_age;

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
    //////////////////////// 
    $tr_html = "";
    if(!empty($all_detail['booking_list']['test_booking_list']))
    {
    	$i=1;
    	
    	foreach($all_detail['booking_list']['test_booking_list'] as $test_booking_list)
    	{ 
    		 $tr = $row_loop;
    		 $tr = str_replace("{s_no}",$i,$tr);
    		 $tr = str_replace("{test_id}",$test_booking_list->test_id,$tr);
    		 $tr = str_replace("{test_name}",$test_booking_list->test_name,$tr);
    		 $tr = str_replace("{amount}",$test_booking_list->amount,$tr);
    		 $tr_html .= $tr;
    		 $i++;


    	  	 	 	 	
    	}
    }

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{salesman}",$all_detail['booking_list'][0]->username,$template_data->template);
    $template_data->template = str_replace("{total_discount}",'0.00',$template_data->template);
    $template_data->template = str_replace("{total_net}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list']['test_booking_list'][0]->amount,$template_data->template);
    $template_data->template = str_replace("{total_gross}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{balance}",$all_detail['booking_list'][0]->balance,$template_data->template);
    	
    	echo $template_data->template;
}
/* end dot printing */



if($template_data->printer_id==1)
{

        $template_data->template = str_replace("{date}",date('d-m-Y'),$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->simulation.' '.$all_detail['booking_list'][0]->patient_name,$template_data->template); 
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);
        $template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);

		$genders = array('0'=>'Female','1'=>'Male');
        $gender = $genders[$all_detail['booking_list'][0]->gender];
        $age_y = $all_detail['booking_list'][0]->age_y; 
        $age_m = $all_detail['booking_list'][0]->age_m;
        $age_d = $all_detail['booking_list'][0]->age_d;

        $age = "";
        if($age_y>0)
        {
        $year = 'Years';
        if($age_y==1)
        {
          $year = 'Year';
        }
        $age .= $age_y." ".$year;
        }
        if($age_m>0)
        {
        $month = 'Months';
        if($age_m==1)
        {
          $month = 'Month';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'Days';
        if($age_d==1)
        {
          $day = 'Day';
        }
        $age .= ", ".$age_d." ".$day;
        }
        $patient_age =  $age;
        $gender_age = $gender.'/'.$patient_age;

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
    //////////////////////// 
    $tr_html = "";
    if(!empty($all_detail['booking_list']['test_booking_list']))
    {
    	$i=1;
    	
    	foreach($all_detail['booking_list']['test_booking_list'] as $test_booking_list)
    	{ 
    		 $tr = $row_loop;
    		 $tr = str_replace("{s_no}",$i,$tr);
    		 $tr = str_replace("{test_id}",$test_booking_list->test_id,$tr);
             $tr = str_replace("{test_name}",$test_booking_list->test_name,$tr);
    		 $tr = str_replace("{amount}",$test_booking_list->amount,$tr);
    		 $tr_html .= $tr;
    		 $i++;
    	  	 	 	 	
    	}
    }

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$all_detail['booking_list'][0]->username,$template_data->template);
    $template_data->template = str_replace("{total_discount}",'0.00',$template_data->template);
    $template_data->template = str_replace("{net_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list']['test_booking_list'][0]->amount,$template_data->template);
    $template_data->template = str_replace("{gross_total_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{balance}",$all_detail['booking_list'][0]->balance,$template_data->template);
   	
    	echo $template_data->template;
    }

/* end leaser printing*/
?>

