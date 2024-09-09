<?php 
//echo "<pre>";print_r($all_detail['booking_list']);die;
$users_data = $this->session->userdata('auth_users');

$signature = ucwords($users_data['user_name']);
if(!empty($all_detail['booking_list'][0]->attended_doctor))
{
   $dr = 'Dr. ';
}
else
{
    $dr = '';
}
$refund_amount =0;


        if(!empty($all_detail['booking_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['booking_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['booking_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

        if(!empty($all_detail['booking_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['booking_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['booking_list'][0]->relation_name,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }
        
// insurance_name
$insurance_name=$all_detail['booking_list'][0]->insurance_name;
$template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
// insurance_name

// insurance_company name
$insurance_company=$all_detail['booking_list'][0]->insurance_company;
$template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
// insurance_company name

$template_data->template = str_replace("{transaction_no}",$transaction_id,$template_data->template);

$template_data->template = str_replace('Discount :',"",$template_data->template);

$template_data->template = str_replace('Discount:',"",$template_data->template);

if($template_data->printer_id==2)
{

        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{salesman}",$signature,$template_data->template);

        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->created_date)),$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);

if(in_array('218',$users_data['permission']['section']))
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['booking_list'][0]->reciept_prefix.$all_detail['booking_list'][0]->reciept_suffix,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
        }

        
        $template_data->template = str_replace("{address}",$all_detail['booking_list'][0]->address,$template_data->template);
        $template_data->template = str_replace("{doctor_name}",$dr.get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);
        $template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template);
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);     
       // $payment_mode_arr = array('0'=>'', '1'=>'Cash', '2'=>'Card', '3'=>'Cheque', '4'=>'NEFT'); 
        $template_data->template = str_replace("{payment_mode}",$all_detail['booking_list'][0]->payment_modes,$template_data->template);

        $barcode="";
        if(!empty($all_detail['booking_list'][0]->barcode_image))
        {
            $barcode_image = $all_detail['booking_list'][0]->barcode_image;
            
             if($all_detail['booking_list'][0]->barcode_type=='vertical')
            {
                $barcode = '<img width="90px" src="'.BARCODE_FS_PATH.$barcode_image.'" style="max-height:100px;">';
            }
            else
            {
                $barcode = '<img width="90px" src="'.BARCODE_FS_PATH.$barcode_image.'">';
            }
        }
        $template_data->template = str_replace("{bar_code}",$barcode,$template_data->template);

        if(!empty($all_detail['booking_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['booking_list'][0]->remarks,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks:",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
        }
        if(!empty($all_detail['booking_list'][0]->tube_no))
        {
           $template_data->template = str_replace("{tube_no}",$all_detail['booking_list'][0]->tube_no,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{tube_no}",' ',$template_data->template);
           $template_data->template = str_replace("Tube No. :",' ',$template_data->template);
           $template_data->template = str_replace("Tube No.",' ',$template_data->template);
        }
        $pos_start = strpos($template_data->template, '{start_loop}');
        $pos_end = strpos($template_data->template, '{end_loop}');
        $row_last_length = $pos_end-$pos_start;
        $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
        // Replace looping row//
        $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
        $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
        //////////////////////// 
        $tr_html = "";
        $i=1;
         
        $tr = $row_loop;
             $tr = str_replace("{s_no}",'1',$tr);
             $tr = str_replace("{test_id}",'Balance Clearance',$tr);
                 $tr = str_replace("{test_name}",'',$tr);
             $tr = str_replace("{amount}",$all_detail['booking_list'][0]->total_amount,$tr);
             $tr_html .= $tr; 

        $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
        $template_data->template = str_replace("{total_discount}",'',$template_data->template);
        $template_data->template = str_replace("{total_net}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
        $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->debit,$template_data->template);   

        
        $template_data->template = str_replace("{balance}",$all_detail['booking_list'][0]->balance,$template_data->template);
         echo $template_data->template; 

}
/* end thermal printing */


/* start dot printing */
if($template_data->printer_id==3)
{
        $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->created_date)),$template_data->template);
        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{address}",$all_detail['booking_list'][0]->address,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);

if(in_array('218',$users_data['permission']['section']))
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['booking_list'][0]->reciept_prefix.$all_detail['booking_list'][0]->reciept_suffix,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
        }

        $template_data->template = str_replace("{doctor_name}",$dr.get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);
        $template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template);
        //$payment_mode_arr = array('0'=>'', '1'=>'Cash', '2'=>'Card', '3'=>'Cheque', '4'=>'NEFT'); 
        $template_data->template = str_replace("{payment_mode}",$all_detail['booking_list'][0]->payment_modes,$template_data->template); 
        if(!empty($all_detail['booking_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['booking_list'][0]->remarks,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks:",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
        }

        if(!empty($all_detail['booking_list'][0]->tube_no))
        {
           $template_data->template = str_replace("{tube_no}",$all_detail['booking_list'][0]->tube_no,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{tube_no}",' ',$template_data->template);
           $template_data->template = str_replace("Tube No. :",' ',$template_data->template);
           $template_data->template = str_replace("Tube No.",' ',$template_data->template);
        }

        $barcode="";
        if(!empty($all_detail['booking_list'][0]->barcode_image))
        {
            $barcode_image = $all_detail['booking_list'][0]->barcode_image;
            
             if($all_detail['booking_list'][0]->barcode_type=='vertical')
            {
                $barcode = '<img width="90px" src="'.BARCODE_FS_PATH.$barcode_image.'" style="max-height:100px;">';
            }
            else
            {
                $barcode = '<img width="90px" src="'.BARCODE_FS_PATH.$barcode_image.'">';
            }
        }
        $template_data->template = str_replace("{bar_code}",$barcode,$template_data->template);


    $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['booking_list'][0]->gender];
        $age_y = $all_detail['booking_list'][0]->age_y; 
        $age_m = $all_detail['booking_list'][0]->age_m;
        $age_d = $all_detail['booking_list'][0]->age_d;
        $age_h = $all_detail['booking_list'][0]->age_h;

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

        if($age_h>0)
        {
        $hours = 'H';
        if($age_h==1)
        {
          $hours = 'H';
        }
        $age .= ", ".$age_h." ".$hours;
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
    $i=1;
    $tr = $row_loop;
         $tr = str_replace("{s_no}",$i,$tr); 
             $tr = str_replace("{test_name}",'Balance Clearance',$tr);
         $tr = str_replace("{amount}",$all_detail['booking_list'][0]->total_amount,$tr);
         $tr_html .= $tr;

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{salesman}",$signature,$template_data->template);
    $template_data->template = str_replace("{total_discount}",' ',$template_data->template);
    $template_data->template = str_replace("{total_net}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{net_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
   // $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->paid_amount,$template_data->template);
   
        $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->debit,$template_data->template);   
    $template_data->template = str_replace("{total_gross}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{total_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{balance}",$all_detail['booking_list'][0]->balance,$template_data->template);
      
      echo $template_data->template;
}
/* end dot printing */



if($template_data->printer_id==1)
{
        //echo '<pre>'; print_r($all_detail);die;
        $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->created_date)),$template_data->template);


if(in_array('218',$users_data['permission']['section']))
        {
        $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['booking_list'][0]->reciept_prefix.$all_detail['booking_list'][0]->reciept_suffix,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template); 
        }

        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);
        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->simulation.' '.$all_detail['booking_list'][0]->patient_name,$template_data->template); 
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{address}",$all_detail['booking_list'][0]->address,$template_data->template);
        $template_data->template = str_replace("{doctor_name}",$dr.get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);
        $template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template);
        //$payment_mode_arr = array('0'=>'', '1'=>'Cash', '2'=>'Card', '3'=>'Cheque', '4'=>'NEFT'); 
        $template_data->template = str_replace("{payment_mode}",$all_detail['booking_list'][0]->payment_modes,$template_data->template); 
        if(!empty($all_detail['booking_list'][0]->remarks))
        {
           $template_data->template = str_replace("{remarks}",$all_detail['booking_list'][0]->remarks,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{remarks}",' ',$template_data->template);
           $template_data->template = str_replace("Remarks:",' ',$template_data->template);
           $template_data->template = str_replace("Remarks",' ',$template_data->template);
        }

        if(!empty($all_detail['booking_list'][0]->tube_no))
        {
           $template_data->template = str_replace("{tube_no}",$all_detail['booking_list'][0]->tube_no,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{tube_no}",' ',$template_data->template);
           $template_data->template = str_replace("Tube No. :",' ',$template_data->template);
           $template_data->template = str_replace("Tube No.",' ',$template_data->template);
        }
        
        $barcode="";
        if(!empty($all_detail['booking_list'][0]->barcode_image))
        {
            $barcode_image = $all_detail['booking_list'][0]->barcode_image;
            if($all_detail['booking_list'][0]->barcode_type=='vertical')
            {
                $barcode = '<img width="90px" src="'.BARCODE_FS_PATH.$barcode_image.'" style="max-height:100px;">';
            }
            else
            {
                $barcode = '<img width="90px" src="'.BARCODE_FS_PATH.$barcode_image.'">';
            }
            
        }
        $template_data->template = str_replace("{bar_code}",$barcode,$template_data->template);


    $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['booking_list'][0]->gender];
        $age_y = $all_detail['booking_list'][0]->age_y; 
        $age_m = $all_detail['booking_list'][0]->age_m;
        $age_d = $all_detail['booking_list'][0]->age_d;
        $age_h = $all_detail['booking_list'][0]->age_h;

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

        if($age_h>0)
        {
        $hours = 'H';
        if($age_h==1)
        {
          $hours = 'H';
        }
        $age .= ", ".$age_h." ".$hours;
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
    $i=1;
    $tr = $row_loop;
         $tr = str_replace("{s_no}",$i,$tr); 
             $tr = str_replace("{test_name}",'Balance Clearance',$tr);
         $tr = str_replace("{amount}",$all_detail['booking_list'][0]->total_amount,$tr);
         $tr_html .= $tr;

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$signature,$template_data->template);
    $template_data->template = str_replace("{total_discount}",' ',$template_data->template);
    $template_data->template = str_replace("{net_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    //$template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->paid_amount,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->debit,$template_data->template);   

    $template_data->template = str_replace("{gross_total_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{balance}",number_format($all_detail['booking_list'][0]->balance,2),$template_data->template);
    
      echo $template_data->template;
    }

/* end leaser printing*/
?>

