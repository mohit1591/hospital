<?php 
 $user_detail = $this->session->userdata('auth_users');
$users_data = $this->session->userdata('auth_users');
/* start thermal printing */
$payment_mode=$all_detail['ipd_list'][0]->payment_mode;
 //$template_data->template = str_replace("{reciept_no}",$re_number,$template_data->template);
 $recipt = $all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix;
 $template_data->template = str_replace("{reciept_no}",$recipt,$template_data->template);
 
 
 

 
 
 $template_data->template = str_replace("{transaction_no}",$transaction_id,$template_data->template);
 $insurance_company_name= ucwords($all_detail['ipd_list'][0]->insurance_company_name);
        $template_data->template = str_replace("{insurance_company_name}", $insurance_company_name, $template_data->template);  

        $template_data->template = str_replace("{insurance_company}", $insurance_company_name, $template_data->template);
        
         if(!empty($all_detail['ipd_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ipd_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

    if(!empty($all_detail['ipd_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ipd_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ipd_list'][0]->relation_name,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }
        
if($template_data->printer_id==2)
{
    
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['ipd_list'][0]->patient_name,$template_data->template);
    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
    $patient_address = $address.' - '.$pincode;
    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data->template);
      $template_data->template = str_replace("{balance}",'0.00',$template_data->template);
    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $template_data->template = str_replace("{booking_level}",'IPD No. :',$template_data->template);
        $template_data->template = str_replace("{ipd_no}",$all_detail['ipd_list'][0]->ipd_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{booking_level}",'',$template_data->template);
         $template_data->template = str_replace("{ipd_no}",'',$template_data->template);
    }


     $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
    

    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        
        $template_data->template = str_replace("{booking_date_level}",'IPD Reg. Date:',$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date)),$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{booking_date_level}",'',$template_data->template);
         $template_data->template = str_replace("{booking_date}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        
        $template_data->template = str_replace("{Consultant_level}",'Assigned Doctor :',$template_data->template);
        $template_data->template = str_replace("{Consultant}",'Dr.'.$all_detail['ipd_list'][0]->doctor_name,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant_level}",'',$template_data->template);
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->mlc) && $all_detail['ipd_list'][0]->mlc==1)
    {
        
        $template_data->template = str_replace("{mlc_level}",'MLC :',$template_data->template);
        $template_data->template = str_replace("{mlc}",'Yes',$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{mlc_level}",'',$template_data->template);
         $template_data->template = str_replace("{mlc}",'',$template_data->template);
         $template_data->template = str_replace("MLC:",' ',$template_data->template);
         $template_data->template = str_replace("MLC :",' ',$template_data->template);
         $template_data->template = str_replace("MLC",' ',$template_data->template);
    }

   
    
    /*if(!empty($all_detail['ipd_list'][0]->specialization_id))
    {
        
        $template_data->template = str_replace("{specialization_level}",'Spec. :',$template_data->template);
        $template_data->template = str_replace("{specialization}",get_specilization_name($all_detail['ipd_list'][0]->specialization_id),$template_data->template);
    }
    else
    {*/
         $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    //}

    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        $template_data->template = str_replace("{room_no}",$all_detail['ipd_list'][0]->room_no,$template_data->template);

    }
    else
    {
         $template_data->template = str_replace("Room NO.:",'',$template_data->template);
         $template_data->template = str_replace("{room_no}",'',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
        $template_data->template = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_name,$template_data->template);
    }
    else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        $template_data->template = str_replace("{bed_no}",$all_detail['ipd_list'][0]->bad_no,$template_data->template);
    }
    else
    {
          $template_data->template = str_replace("Bed NO.:",'',$template_data->template);
         $template_data->template = str_replace("{bed_no}",'',$template_data->template);
    }

        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['ipd_list'][0]->gender];
        $age_y = $all_detail['ipd_list'][0]->age_y; 
        $age_m = $all_detail['ipd_list'][0]->age_m;
        $age_d = $all_detail['ipd_list'][0]->age_d;

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
    $template_data->template = str_replace("{Quantity_level}",'',$template_data->template);
    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));

    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

    //////////////////////// 
    $tr_html = "";
    $tr = $row_loop;
    $tr = str_replace("{s_no}",1,$tr);
    $tr = str_replace("{particular}",$all_detail['ipd_list'][0]->particular,$tr); //'Advance Payment'
    $tr = str_replace("{quantity}","",$tr);
    $tr = str_replace("{amount}",$all_detail['ipd_list'][0]->advance_payment,$tr);
    $tr_html .= $tr;
        
    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{total_net}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
   
  

     $template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

     
    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
       
    }
    //echo "<pre>";print_r($template_data); exit;

    echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
    
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['ipd_list'][0]->patient_name,$template_data->template);
  $template_data->template = str_replace("{balance}",'0.00',$template_data->template);
    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
    
    $patient_address = $address.' - '.$pincode;

    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['ipd_list'][0]->patient_code,$template_data->template);
    
    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $receipt_code = '<br><b>IPD No.:</b>'.$all_detail['ipd_list'][0]->ipd_no.'</b>';
        $template_data->template = str_replace("{ipd_no}",$receipt_code,$template_data->template);
    }

     $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
    
    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        $booking_date = '<br><b>IPD Reg. Date :</b>'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date));
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->created_date))
    {
        
        /* 23rd Feb 2019 code by neha */
        
        //$created_date = '<br><b>Receipt Date :</b>'.date('d-m-Y h:i A',strtotime($all_detail['ipd_list'][0]->created_date));
        
        /* 23rd Feb 2019 code by neha */
        
        $created_date = '<br><b>Receipt Date :</b>'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->payment_date));
        //$created_date = '<br><b>Receipt Date :</b>'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->created_date));
        $template_data->template = str_replace("{receipt_date}",$created_date,$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        $consultant_new = '<br><b>Assigned Doctor :</b>'.'Dr.'. $all_detail['ipd_list'][0]->doctor_name;
        $template_data->template = str_replace("{Consultant}",$consultant_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }


    if(!empty($all_detail['ipd_list'][0]->mlc) && $all_detail['ipd_list'][0]->mlc==1)
    {
        $mlc = '<br><b>MLC :</b>'.$all_detail['ipd_list'][0]->doctor_name;
        $template_data->template = str_replace("{mlc}",$mlc,$template_data->template);
    }
    else
    {
         
        $template_data->template = str_replace("{mlc}",'',$template_data->template);
        $template_data->template = str_replace("MLC:",' ',$template_data->template);
        $template_data->template = str_replace("MLC :",' ',$template_data->template);
        $template_data->template = str_replace("MLC",' ',$template_data->template);
    }
   /* if(!empty($all_detail['ipd_list'][0]->specialization_id))
    {
        $specialization_new = '<br><b>Spec.</b>'.get_specilization_name($all_detail['ipd_list'][0]->specialization_id);
        $template_data->template = str_replace("{specialization}",$specialization_new,$template_data->template);
    }
    else
    {*/ $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    //}

     if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        
        $room_type = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->room_category;
        $template_data->template = str_replace("{room_type}",$room_type,$template_data->template);
             
        
    }
    else
    {
         $template_data->template = str_replace("{room_type}",'',$template_data->template);
    }

     if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        
        $room_type = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->room_category;
        $template_data->template = str_replace("{room_type}",$room_type,$template_data->template);
             
        
    }
    else
    {
         $template_data->template = str_replace("{room_type}",'',$template_data->template);
    }     

    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        
        $room_no = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->room_no;
        $template_data->template = str_replace("{room_no}",$room_no,$template_data->template);

    }
    else
    {
         $template_data->template = str_replace("{room_no}",'',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
        
        $bed_no = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->bad_name;
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);

    }
    else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        
        $bed_no = '<br><b>Room No.:</b>'.$all_detail['ipd_list'][0]->bad_no;
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);

    }
    else
    {
         $template_data->template = str_replace("{bed_no}",'',$template_data->template);
    }

        $genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['ipd_list'][0]->gender];
        $age_y = $all_detail['ipd_list'][0]->age_y; 
        $age_m = $all_detail['ipd_list'][0]->age_m;
        $age_d = $all_detail['ipd_list'][0]->age_d;

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
    $template_data->template = str_replace("{Quantity_level}",'',$template_data->template);

    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);


    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
    //////////////////////// 
            
        $tr_html = "";
        $tr = $row_loop;
        $tr = str_replace("{s_no}",1,$tr);
        $tr = str_replace("{particular}",$all_detail['ipd_list'][0]->particular,$tr); //'Advance Payment'
        $tr = str_replace("{quantity}",'',$tr);
        $tr = str_replace("{amount}",$all_detail['ipd_list'][0]->advance_payment,$tr);
        $tr_html .= $tr;

   

 $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    
    $template_data->template = str_replace("{salesman}",ucfirst($user_detail['user_name']),$template_data->template);

    $template_data->template = str_replace("{total_net}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);

    $template_data->template = str_replace("{paid_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);
    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
       
    }
    echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['ipd_list']);die;
if($template_data->printer_id==1)
{

    
    $simulation = get_simulation_name($all_detail['ipd_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['ipd_list'][0]->patient_name,$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['ipd_list'][0]->patient_code,$template_data->template);
    $address = $all_detail['ipd_list'][0]->address;
    $pincode = $all_detail['ipd_list'][0]->pincode;         
    
    $patient_address = $address.' - '.$pincode;

    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
      $template_data->template = str_replace("{balance}",'0.00',$template_data->template);


     $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ipd_list'][0]->reciept_prefix.$all_detail['ipd_list'][0]->reciept_suffix,$template_data->template);
   
    
    /*if(!empty($all_detail['ipd_list'][0]->specialization_id))
    {
        $specialization_new = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Spec. :</div>

            <div style="width:60%;line-height:17px;">'.get_specilization_name($all_detail['ipd_list'][0]->specialization_id).'</div>
            </div>';
        $template_data->template = str_replace("{specialization}",$specialization_new,$template_data->template);
    }
    else
    {*/
         $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    //}
    if(!empty($all_detail['ipd_list'][0]->room_category))
    {
        $room_category = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room Type:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->room_category.'</div>
            </div>';
        $template_data->template = str_replace("{room_type}",$room_category,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{room_type}",'',$template_data->template);
    }
    if(!empty($all_detail['ipd_list'][0]->doctor_name))
    {
        $consultant_new = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Assigned Doctor :</div>

            <div style="width:60%;line-height:17px;">'.'Dr. '. $all_detail['ipd_list'][0]->doctor_name.'</div>
            </div>';
        $template_data->template = str_replace("{Consultant}",$consultant_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }
    
    $template_data->template = str_replace("{mobile_no}",$all_detail['ipd_list'][0]->mobile_no,$template_data->template);


    if(!empty($all_detail['ipd_list'][0]->ipd_no))
    {
        $receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->ipd_no.'</div>
            </div>';
        $template_data->template = str_replace("{ipd_no}",$receipt_code,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->admission_date))
    {
        $booking_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD Reg. Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->admission_date)).'</div>
            </div>';
        $template_data->template = str_replace("{booking_date}",$booking_date,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->created_date))
    {
        
        /* 23rd Feb 2019 code by neha */
        
        //$receipt_date = '<div style="width:100%;display:inline-flex;">
            //             <div style="width:40%;line-height:19px;font-weight:600;">Receipt Date:</div>

            // <div style="width:60%;line-height:19px;">'.date('d-m-Y h:i A',strtotime($all_detail['ipd_list'][0]->created_date)).'</div>
            // </div>';
        
        /* 23rd Feb 2019 code by neha */
        
        
        $receipt_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Receipt Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($all_detail['ipd_list'][0]->payment_date)).'</div>
            </div>';
        $template_data->template = str_replace("{receipt_date}",$receipt_date,$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->room_no))
    {
        $room_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->room_no.'</div>
            </div>';
        $template_data->template = str_replace("{room_no}",$room_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{room_no}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->bad_name))
    {
        $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->bad_name.'</div>
            </div>';
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);
    }else if(!empty($all_detail['ipd_list'][0]->bad_no))
    {
        $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ipd_list'][0]->bad_no.'</div>
            </div>';
        $template_data->template = str_replace("{bed_no}",$bed_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{bed_no}",'',$template_data->template);
    }

    if(!empty($all_detail['ipd_list'][0]->mlc) && $all_detail['ipd_list'][0]->mlc==1)
    {
        $mlc = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">MLC:</div>

            <div style="width:60%;line-height:19px;">Yes</div>
            </div>';
        $template_data->template = str_replace("{mlc}",$mlc,$template_data->template);
    }
    else
    {
         
        $template_data->template = str_replace("{mlc}",'',$template_data->template);
        $template_data->template = str_replace("MLC:",' ',$template_data->template);
        $template_data->template = str_replace("MLC :",' ',$template_data->template);
        $template_data->template = str_replace("MLC",' ',$template_data->template);
    }
    

    $genders = array('0'=>'F','1'=>'M');
    $gender = $genders[$all_detail['ipd_list'][0]->gender];
    $age_y = $all_detail['ipd_list'][0]->age_y; 
    $age_m = $all_detail['ipd_list'][0]->age_m;
    $age_d = $all_detail['ipd_list'][0]->age_d;

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

    $template_data->template = str_replace("{Quantity_level}",'',$template_data->template);

    $pos_start = strpos($template_data->template, '{start_loop}');
    $pos_end = strpos($template_data->template, '{end_loop}');
    $row_last_length = $pos_end-$pos_start;
    $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

    // Replace looping row//
    $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
    $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
    //////////////////////// 
    
     
    $tr_html = "";
    $tr = $row_loop;
    $tr = str_replace("{s_no}",1,$tr);
    $tr = str_replace("{particular}",$all_detail['ipd_list'][0]->particular,$tr);//'Advance Payment'
    $tr = str_replace("{quantity}",'',$tr);
    $tr = str_replace("{amount}",$all_detail['ipd_list'][0]->advance_payment,$tr);
    $tr_html .= $tr;
        
    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{sales_name}",ucfirst($all_detail['ipd_list'][0]->user_name),$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{net_amount}",$all_detail['ipd_list'][0]->advance_payment,$template_data->template);
    $template_data->template = str_replace("{payment_mode}",$payment_mode,$template_data->template);

    if(!empty($all_detail['ipd_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ipd_list'][0]->remarks,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{remarks}",' ',$template_data->template);
        $template_data->template = str_replace("Remarks:",' ',$template_data->template);
        $template_data->template = str_replace("Remarks :",' ',$template_data->template);
        $template_data->template = str_replace("Remarks",' ',$template_data->template);
    }

    echo $template_data->template; 
}

/* end leaser printing*/
?>