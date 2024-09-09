<?php 
/* start thermal printing */

if($template_data->printer_id==2){
$template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
$template_data->template = str_replace("{patient_name}",$all_detail['opd_list'][0]->patient_name,$template_data->template);

$template_data->template = str_replace("{mobile_no}",$all_detail['opd_list'][0]->mobile_no,$template_data->template);



$template_data->template = str_replace("{aadhaar_no}",$all_detail['prescription_list'][0]->adhar_no,$template_data->template);
        
        if($all_detail['prescription_list'][0]->dob!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($all_detail['prescription_list'][0]->dob));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }

//$template_data->template = str_replace("{patient_reg_no}",$all_detail['opd_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{booking_code}",$all_detail['opd_list'][0]->booking_code,$template_data->template);
$template_data->template = str_replace("{opd_reg_date}",date('d-m-Y',strtotime($all_detail['opd_list'][0]->booking_date)),$template_data->template);
$template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['opd_list'][0]->attended_doctor),$template_data->template);
$template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['opd_list'][0]->attended_doctor),$template_data->template);
 $spec_name='';
        $specialization = get_specilization_name($all_detail['opd_list'][0]->specialization_id);
        if(!empty($specialization))
        {
            $spec_name= str_replace('(Default)','',$specialization);
        }
$template_data->template = str_replace("{specialization}",$spec_name,$template_data->template);

		$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['opd_list'][0]->gender];
        $age_y = $all_detail['opd_list'][0]->age_y; 
        $age_m = $all_detail['opd_list'][0]->age_m;
        $age_d = $all_detail['opd_list'][0]->age_d;

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
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
        }
        $age .= ", ".$age_d." ".$day;
        }
        $patient_age =  $age;
        $gender_age = $gender.'/'.$patient_age;

//$template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

$pos_start = strpos($template_data->template, '{start_loop}');
$pos_end = strpos($template_data->template, '{end_loop}');
$row_last_length = $pos_end-$pos_start;
$row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);



// Replace looping row//
$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));

$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

    //////////////////////// 

	
    $tr_html = "";
    foreach(range(1, 20)  as $i)
    { 
         $tr = $row_loop;
         $tr = str_replace("{s_no}",'',$tr);
         
         $tr = str_replace("{particular}",'',$tr);

         $tr = str_replace("{quantity}",'',$tr);
         
         $tr = str_replace("{amount}",'',$tr);
         $tr_html .= $tr;
         $i++;
                    
    }


$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

/*$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

$template_data->template = str_replace("{sn}",1,$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['opd_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{particular_name}",'Consultant Charges',$template_data->template);
$tr_html="";
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);*/

$template_data->template = str_replace("{total_discount}",$all_detail['opd_list'][0]->discount,$template_data->template);

$template_data->template = str_replace("{total_net}",$all_detail['opd_list'][0]->net_amount,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['opd_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{balance}",$all_detail['opd_list'][0]->balance,$template_data->template);
$template_data->template = str_replace("{validity_date}",'',$template_data->template);

//echo "<pre>";print_r($template_data); exit;

 echo $template_data->template; die;

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3){
    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
	$template_data->template = str_replace("{patient_name}",$all_detail['opd_list'][0]->patient_name,$template_data->template);

$template_data->template = str_replace("{mobile_no}",$all_detail['opd_list'][0]->mobile_no,$template_data->template);
$template_data->template = str_replace("{patient_reg_no}",$all_detail['opd_list'][0]->patient_code,$template_data->template);


$template_data->template = str_replace("{booking_code}",$all_detail['opd_list'][0]->booking_code,$template_data->template);
$template_data->template = str_replace("{opd_reg_date}",date('d-m-Y',strtotime($all_detail['opd_list'][0]->booking_date)),$template_data->template);
$template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['opd_list'][0]->attended_doctor),$template_data->template);

$spec_name='';
        $specialization = get_specilization_name($all_detail['opd_list'][0]->specialization_id);
        if(!empty($specialization))
        {
            $spec_name= str_replace('(Default)','',$specialization);
        }
$template_data->template = str_replace("{specialization}",$spec_name,$template_data->template);

		$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['opd_list'][0]->gender];
        $age_y = $all_detail['opd_list'][0]->age_y; 
        $age_m = $all_detail['opd_list'][0]->age_m;
        $age_d = $all_detail['opd_list'][0]->age_d;

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
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
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
    foreach(range(1, 20)  as $i)
    { 
         $tr = $row_loop;
         $tr = str_replace("{s_no}",'',$tr);
         
         $tr = str_replace("{particular}",'',$tr);

         $tr = str_replace("{quantity}",'',$tr);
         
         $tr = str_replace("{amount}",'',$tr);
         $tr_html .= $tr;
         $i++;
                    
    }

$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);



// Replace looping row//
/*$rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
$template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
$template_data->template = str_replace("{s_no}",1,$template_data->template);

$template_data->template = str_replace("{total_amount}",$all_detail['opd_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{particular_name}",'Consultant Charges',$template_data->template);
$tr_html="";
$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);*/

$template_data->template = str_replace("{salesman}",$all_detail['opd_list'][0]->username,$template_data->template);

$template_data->template = str_replace("{total_discount}",'',$template_data->template);

$template_data->template = str_replace("{total_net}",'',$template_data->template);

$template_data->template = str_replace("{paid_amount}",'',$template_data->template);

$template_data->template = str_replace("{total_gross}",'',$template_data->template);

$template_data->template = str_replace("{balance}",'',$template_data->template);
	$template_data->template = str_replace("{validity_date}",'',$template_data->template);
	echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['opd_list']);die;
if($template_data->printer_id==1){

    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
	
    $template_data->template = str_replace("{patient_name}",$all_detail['opd_list'][0]->patient_name,$template_data->template);
 $template_data->template = str_replace("{patient_reg_no}",$all_detail['opd_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{mobile_no}",$all_detail['opd_list'][0]->mobile_no,$template_data->template);

$template_data->template = str_replace("{booking_code}",$all_detail['opd_list'][0]->booking_code,$template_data->template);
$template_data->template = str_replace("{opd_reg_date}",date('d-m-Y',strtotime($all_detail['opd_list'][0]->booking_date)),$template_data->template);
$template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['opd_list'][0]->attended_doctor),$template_data->template);
$spec_name='';
        $specialization = get_specilization_name($all_detail['opd_list'][0]->specialization_id);
        if(!empty($specialization))
        {
            $spec_name= str_replace('(Default)','',$specialization);
        }
$template_data->template = str_replace("{specialization}",$spec_name,$template_data->template);

		$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['opd_list'][0]->gender];
        $age_y = $all_detail['opd_list'][0]->age_y; 
        $age_m = $all_detail['opd_list'][0]->age_m;
        $age_d = $all_detail['opd_list'][0]->age_d;

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
        if($age_m>0)
        {
        $month = 'M';
        if($age_m==1)
        {
          $month = 'M';
        }
        $age .= ", ".$age_m." ".$month;
        }
        if($age_d>0)
        {
        $day = 'D';
        if($age_d==1)
        {
          $day = 'D';
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

	$i=1;
	$tr_html = "";
	foreach(range(1, 20)  as $i)
	{ 
		 $tr = $row_loop;
		 $tr = str_replace("{s_no}",'',$tr);
         
		 $tr = str_replace("{particular}",'',$tr);

		 $tr = str_replace("{quantity}",'',$tr);
		 
         $tr = str_replace("{amount}",'',$tr);
		 $tr_html .= $tr;
		 $i++;
	  	 	 	 	
	}

$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);

$template_data->template = str_replace("{sales_name}",$all_detail['opd_list'][0]->username,$template_data->template);
//echo "<pre>";print_r($template_data->template); exit;

$template_data->template = str_replace("{total_discount}",'',$template_data->template);

$template_data->template = str_replace("{net_amount}",'',$template_data->template);

$template_data->template = str_replace("{total_net}",'',$template_data->template);


$template_data->template = str_replace("{paid_amount}",'',$template_data->template);

$template_data->template = str_replace("{gross_total_amount}",'',$template_data->template);

$template_data->template = str_replace("{balance}",'',$template_data->template);
$template_data->template = str_replace("{validity_date}",'',$template_data->template);	
	echo $template_data->template;
}

/* end leaser printing*/
?>

