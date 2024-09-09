<?php 
$del = ','; 
$address_n='';
$address_re='';
//echo '<pre>'; print_r($transaction_id[0]->field_value);die;
$barcode_text = $booking_id.$all_detail['booking_list'][0]->patient_id;
$users_data = $this->session->userdata('auth_users');
$signature = ucwords($all_detail['booking_list'][0]->signature_name);
//$signature = ucwords($users_data['user_name']);
if(!empty($all_detail['booking_list'][0]->attended_doctor))
{
   $dr = 'Dr. ';
}
else
{
    $dr = '';
}

if($all_detail['booking_list'][0]->ref_doctor_type==2)
{
   $balance = '0.00';
}
else
{
   $balance = $all_detail['booking_list'][0]->patient_balance;
}

if($users_data['parent_id']=='66' || $users_data['parent_id']=='129')
{
    if($all_detail['booking_list'][0]->dept_id=='60' || $all_detail['booking_list'][0]->dept_id=='29')
    $template_data->template = str_replace("{onbehalf_text}",'Received On Behalf of Unicare Speech &amp; Hearing Clinic',$template_data->template); 
}





$template_data->template = str_replace("{transaction_no}",$transaction_id[0]->field_value,$template_data->template);


$template_data->template = str_replace("{doctor_hospital_name}",$all_detail['booking_list'][0]->doctor_hospital_name,$template_data->template);
if($template_data->printer_id==2)
{
    
    
    
            if($all_detail['booking_list'][0]->address!='' || $all_detail['booking_list'][0]->address2!='' || $all_detail['booking_list'][0]->address3!='')
            {
            $address_n = array_merge(explode ( $del , $all_detail['booking_list'][0]->address),explode ( $del , $all_detail['booking_list'][0]->address2),explode ( $del , $all_detail['booking_list'][0]->address3));
            }
            if(!empty($address_n))
            {
                $address_re=array();
            foreach($address_n as $add_re)
            {
                if(!empty($add_re))
                {
                $address_re[]=$add_re;  
                }

            }
            $patient_address = implode(',',$address_re);
            }
            else
            {
            $patient_address='';
            }
         
        $template_data->template = str_replace("{address}",$patient_address,$template_data->template);
        
         $template_data->template = str_replace("{salesman}",$signature,$template_data->template);
         
         if($home_collection!="empty" && in_array('221',$users_data['permission']['section']) )
              {
                if($home_collection[0]->status==1)
                { 
                   $home_collection_data = '<div style="width:100%;border-top:1px solid #111;"> <div style="float:left;font-weight:bold;">Home Collection:</div><div style="float:right;font-weight:bold;">'.$all_detail['booking_list'][0]->home_collection_amount.'</div></div>'; 
                   $template_data->template = str_replace("{home_collection}",$home_collection_data,$template_data->template); 
                   
                    $template_data->template = str_replace("{home_collection_amount}",$all_detail['booking_list'][0]->home_collection_amount,$template_data->template); 
                    
                }
                else
                {
                    $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                    $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
                }
              }
              else
              {
                $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
              }
              
        
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
        
        
    $rel_simulation = get_simulation_name($all_detail['booking_list'][0]->relation_simulation_id);

   // if(!empty($all_detail['booking_list'][0]->relation_name))
   //  {
   //       $template_data->template = str_replace("{parent_relation_type}",$all_detail['booking_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['booking_list'][0]->relation_name,$template_data->template);
   //  }

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

        if(in_array('218',$users_data['permission']['section']))
        {
             
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['booking_list'][0]->reciept_prefix.$all_detail['booking_list'][0]->reciept_suffix,$template_data->template);
             
        }
        else
        {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
        }

        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->created_date)),$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);


        $booking_time ='';
        if(!empty($all_detail['booking_list'][0]->booking_time) && $all_detail['booking_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['booking_list'][0]->booking_time)>0)
        {
            $booking_time = date('h:i A', strtotime($all_detail['booking_list'][0]->booking_time));    
        }
        
        $template_data->template = str_replace("{booking_time}", $booking_time, $template_data->template);
        

        $template_data->template = str_replace("{doctor_name}",$dr.get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);
        if(!empty($all_detail['booking_list'][0]->ref_by_other))
        {
           $template_data->template = str_replace("{referral_doctor}",'Dr. '.$all_detail['booking_list'][0]->ref_by_other,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template); 
        }
        
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);     
       // $payment_mode_arr = array('0'=>'', '1'=>'Cash', '2'=>'Card', '3'=>'Cheque', '4'=>'NEFT'); 
        $template_data->template = str_replace("{payment_mode}",$all_detail['booking_list'][0]->payment_mode,$template_data->template);

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
        
        // insurance_name
        $insurance_name=$all_detail['booking_list'][0]->insurance_name;
        $template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
        // insurance_name

        // insurance_company name
        $insurance_company=$all_detail['booking_list'][0]->insurance_company;
        $template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
        // insurance_company name


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
        
        
        if(!empty($all_detail['booking_list']['test_booking_list']))
        {
        	
        	
        	foreach($all_detail['booking_list']['test_booking_list'] as $test_booking_list)
        	{ 
        		 $tr = $row_loop;
        		 $tr = str_replace("{s_no}",$i,$tr);
        		 $tr = str_replace("{test_id}",test_list_name($test_booking_list->test_id,'test_code'),$tr);
                 $tr = str_replace("{test_name}",test_list_name($test_booking_list->test_id,'test_name'),$tr);
        		 $tr = str_replace("{amount}",$test_booking_list->amount,$tr);
        		 $tr_html .= $tr;
        		 $i++;
        	  	 	 	 	
        	}
        }
 
        
        if(isset($all_detail['profile_data']['test_booking_list']) && !empty($all_detail['profile_data']['test_booking_list']))
        {
           $tr = $row_loop;
                 $tr = str_replace("{s_no}",'1',$tr);
                 $tr = str_replace("{test_id}",'',$tr);
                 $tr = str_replace("{test_name}",$all_detail['profile_data']['test_booking_list'][0]->profile_name,$tr);
                 $tr = str_replace("{amount}",$all_detail['booking_list'][0]->profile_amount,$tr);
                 $tr_html .= $tr;
                 $i=2;
        }

        else if(!empty($all_profile))
        {
            $i=1;
            foreach($all_profile as $pro_data)
            {
                $tr = $row_loop;
                $tr = str_replace("{s_no}",$i,$tr);
                $tr = str_replace("{test_id}",'',$tr);
                $tr = str_replace("{test_name}",$pro_data->profile_name,$tr);
                $tr = str_replace("{amount}",$pro_data->master_price,$tr);
                $tr_html .= $tr;
                $i++;
            }
        }

        

        if($home_collection!="empty" && in_array('221',$users_data['permission']['section']) )
              {
                if($home_collection[0]->status==1)
                { 
                   $home_collection_data = '<div style="width:100%;border-top:1px solid #111;"> <div style="float:left;font-weight:bold;">Home Collection:</div><div style="float:right;font-weight:bold;">'.$all_detail['booking_list'][0]->home_collection_amount.'</div></div>'; 
                   $template_data->template = str_replace("{home_collection}",$home_collection_data,$template_data->template); 
                   
                    $template_data->template = str_replace("{home_collection_amount}",$all_detail['booking_list'][0]->home_collection_amount,$template_data->template); 
                    
                }
                else
                {
                    $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                    $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
                }
              }
              else
              {
                $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
              }
    // added on 07-Feb-2018

        $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
        $template_data->template = str_replace("{total_discount}",$all_detail['booking_list'][0]->discount,$template_data->template);
        $template_data->template = str_replace("{total_net}",$all_detail['booking_list'][0]->net_amount,$template_data->template);
        $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->paid_amount,$template_data->template);
        $template_data->template = str_replace("{balance}",$balance,$template_data->template);
        $this->session->unset_userdata('test_booking_id');
         echo $template_data->template; 

}
/* end thermal printing */


/* start dot printing */
if($template_data->printer_id==3)
{
     $rel_simulation = get_simulation_name($all_detail['booking_list'][0]->relation_simulation_id);

   // if(!empty($all_detail['booking_list'][0]->relation_name))
   //  {
   //       $template_data->template = str_replace("{parent_relation_type}",$all_detail['booking_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['booking_list'][0]->relation_name,$template_data->template);
   //  }


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


	    if(in_array('218',$users_data['permission']['section']))
        {
             
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['booking_list'][0]->reciept_prefix.$all_detail['booking_list'][0]->reciept_suffix,$template_data->template);
             
        }
        else
        {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
        }

        $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->created_date)),$template_data->template);
        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);

        $booking_time ='';
        if(!empty($all_detail['booking_list'][0]->booking_time) && $all_detail['booking_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['booking_list'][0]->booking_time)>0)
        {
            $booking_time = date('h:i A', strtotime($all_detail['booking_list'][0]->booking_time));    
        }
        
        $template_data->template = str_replace("{booking_time}", $booking_time, $template_data->template);

        $template_data->template = str_replace("{doctor_name}",$dr.get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);
        //$template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template);
        if(!empty($all_detail['booking_list'][0]->ref_by_other))
        {
           $template_data->template = str_replace("{referral_doctor}",'Dr. '.$all_detail['booking_list'][0]->ref_by_other,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template); 
        }
        //$payment_mode_arr = array('0'=>'', '1'=>'Cash', '2'=>'Card', '3'=>'Cheque', '4'=>'NEFT'); 
        $template_data->template = str_replace("{payment_mode}",$all_detail['booking_list'][0]->payment_mode,$template_data->template); 
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

        // insurance_name
        $insurance_name=$all_detail['booking_list'][0]->insurance_name;
        $template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
        // insurance_name

        // insurance_company name
        $insurance_company=$all_detail['booking_list'][0]->insurance_company;
        $template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
        // insurance_company name

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
    if(!empty($all_detail['booking_list']['test_booking_list']))
    {
    	
    	
    	foreach($all_detail['booking_list']['test_booking_list'] as $test_booking_list)
    	{ 
    		 $tr = $row_loop;
    		 $tr = str_replace("{s_no}",$i,$tr);
    		 $tr = str_replace("{test_id}",test_list_name($test_booking_list->test_id,'test_code'),$tr);
    		 $tr = str_replace("{test_name}",test_list_name($test_booking_list->test_id,'test_name'),$tr);
    		 $tr = str_replace("{amount}",$test_booking_list->amount,$tr);
    		 $tr_html .= $tr;
    		 $i++;


    	  	 	 	 	
    	}
    }
    if(isset($all_detail['profile_data']['test_booking_list']) && !empty($all_detail['profile_data']['test_booking_list']))
        {
           $tr = $row_loop;
                 $tr = str_replace("{s_no}",'1',$tr);
                 $tr = str_replace("{test_id}",'',$tr);
                 $tr = str_replace("{test_name}",$all_detail['profile_data']['test_booking_list'][0]->profile_name,$tr);
                 $tr = str_replace("{amount}",$all_detail['booking_list'][0]->profile_amount,$tr);
                 $tr_html .= $tr;
                 $i=2;
        }

    else if(!empty($all_profile))
        {
            $i=1;
            foreach($all_profile as $pro_data)
            {
                $tr = $row_loop;
                $tr = str_replace("{s_no}",$i,$tr);
                $tr = str_replace("{test_id}",'',$tr);
                $tr = str_replace("{test_name}",$pro_data->profile_name,$tr);
                $tr = str_replace("{amount}",$pro_data->master_price,$tr);
                $tr_html .= $tr;
                $i++;
            }
        }
        

    

    // added on 07-Feb-2018
    if($home_collection!="empty" && in_array('221',$users_data['permission']['section']) )
              {
                if($home_collection[0]->status==1)
                { 
                   $home_collection_data = '<div style="width:100%;border-top:1px solid #111;"> <div style="float:left;font-weight:bold;">Home Collection:</div><div style="float:right;font-weight:bold;">'.$all_detail['booking_list'][0]->home_collection_amount.'</div></div>'; 
                   $template_data->template = str_replace("{home_collection}",$home_collection_data,$template_data->template); 
                   
                   $template_data->template = str_replace("{home_collection_amount}",$all_detail['booking_list'][0]->home_collection_amount,$template_data->template); 
                   
                }
                else
                {
                    $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                    $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
                }
              }
              else
              {
                $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
              }
    // added on 07-Feb-2018

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{salesman}",$signature,$template_data->template);
    $template_data->template = str_replace("{total_discount}",$all_detail['booking_list'][0]->discount,$template_data->template);
    $template_data->template = str_replace("{total_net}",$all_detail['booking_list'][0]->net_amount,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->paid_amount,$template_data->template);
    $template_data->template = str_replace("{total_gross}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{balance}",$balance,$template_data->template);
    	$this->session->unset_userdata('test_booking_id');
    	echo $template_data->template;
}
/* end dot printing */



if($template_data->printer_id==1)
{
        $rel_simulation = get_simulation_name($all_detail['booking_list'][0]->relation_simulation_id);

        // if(!empty($all_detail['booking_list'][0]->relation_name))
        // {
        // $template_data->template = str_replace("{parent_relation_type}",$all_detail['booking_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['booking_list'][0]->relation_name,$template_data->template);
        // }

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




        //echo '<pre>'; print_r($all_detail);die;
	    if(in_array('218',$users_data['permission']['section']))
        {
             
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['booking_list'][0]->reciept_prefix.$all_detail['booking_list'][0]->reciept_suffix,$template_data->template);
             
        }
        else
        {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
        }
        $template_data->template = str_replace("{date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->created_date)),$template_data->template);

        $template_data->template = str_replace("{mobile_no}",$all_detail['booking_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['booking_list'][0]->booking_date)),$template_data->template);

        $booking_time ='';
        if(!empty($all_detail['booking_list'][0]->booking_time) && $all_detail['booking_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['booking_list'][0]->booking_time)>0)
        {
            $booking_time = date('h:i A', strtotime($all_detail['booking_list'][0]->booking_time));    
        }
        
        $template_data->template = str_replace("{booking_time}", $booking_time, $template_data->template);
        
        $template_data->template = str_replace("{patient_name}",$all_detail['booking_list'][0]->simulation.' '.$all_detail['booking_list'][0]->patient_name,$template_data->template); 
        $template_data->template = str_replace("{patient_reg_no}",$all_detail['booking_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['booking_list'][0]->lab_reg_no,$template_data->template);


            if($all_detail['booking_list'][0]->address!='' || $all_detail['booking_list'][0]->address2!='' || $all_detail['booking_list'][0]->address3!='')
            {
            $address_n = array_merge(explode ( $del , $all_detail['booking_list'][0]->address),explode ( $del , $all_detail['booking_list'][0]->address2),explode ( $del , $all_detail['booking_list'][0]->address3));
            }
            if(!empty($address_n))
            {
                $address_re=array();
            foreach($address_n as $add_re)
            {
                if(!empty($add_re))
                {
                $address_re[]=$add_re;  
                }

            }
            $patient_address = implode(',',$address_re);
            }
            else
            {
            $patient_address='';
            }
         
        $template_data->template = str_replace("{address}",$patient_address,$template_data->template);
        $template_data->template = str_replace("{doctor_name}",$dr.get_doctor_name($all_detail['booking_list'][0]->attended_doctor),$template_data->template);


        // insurance_name
        $insurance_name=$all_detail['booking_list'][0]->insurance_name;
        $template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
        // insurance_name

        // insurance_company name
        $insurance_company=$all_detail['booking_list'][0]->insurance_company;
        $template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
        // insurance_company name
        
        //$template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template);
        if(!empty($all_detail['booking_list'][0]->ref_by_other))
        {
           $template_data->template = str_replace("{referral_doctor}",'Dr. '.$all_detail['booking_list'][0]->ref_by_other,$template_data->template);
        }
        else
        {
           $template_data->template = str_replace("{referral_doctor}",'Dr. '.get_doctor_name($all_detail['booking_list'][0]->referral_doctor),$template_data->template); 
        }
        //$payment_mode_arr = array('0'=>'', '1'=>'Cash', '2'=>'Card', '3'=>'Cheque', '4'=>'NEFT'); 
        $template_data->template = str_replace("{payment_mode}",$all_detail['booking_list'][0]->payment_mode,$template_data->template); 
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
        
        /*$barcode="";
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
            
        }*/
        $img_barcode = '<img class="barcode" alt="'.$barcode_text.'" src="'.base_url('barcode.php').'?text='.$barcode_text.'&codetype=code128&orientation=horizontal&size=20&print=true"/>'; ;
        $template_data->template = str_replace("{bar_code}",$img_barcode,$template_data->template);


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
    
    if(!empty($all_detail['booking_list']['test_booking_list']))
    { 
    	foreach($all_detail['booking_list']['test_booking_list'] as $test_booking_list)
    	{  
    		 $tr = $row_loop;
    		 $tr = str_replace("{s_no}",$i,$tr);
    		 $tr = str_replace("{test_id}",test_list_name($test_booking_list->test_id,'test_code'),$tr);
             $tr = str_replace("{test_name}",test_list_name($test_booking_list->test_id,'test_name'),$tr);
    		 $tr = str_replace("{amount}",$test_booking_list->amount,$tr);
    		 $tr_html .= $tr;
    		 $i++;
    	  	 	 	 	
    	}
    }
    if(isset($all_detail['profile_data']['test_booking_list']) && !empty($all_detail['profile_data']['test_booking_list']))
        {
           $tr = $row_loop;
                 $tr = str_replace("{s_no}",'1',$tr);
                 $tr = str_replace("{test_id}",'',$tr);
                 $tr = str_replace("{test_name}",$all_detail['profile_data']['test_booking_list'][0]->profile_name,$tr);
                 $tr = str_replace("{amount}",$all_detail['booking_list'][0]->profile_amount,$tr);
                 $tr_html .= $tr;
                 $i=2;
        }

    else if(!empty($all_profile))
        {
            $i=$i;
            foreach($all_profile as $pro_data)
            {
                $tr = $row_loop;
                $tr = str_replace("{s_no}",$i,$tr);
                $tr = str_replace("{test_id}",'',$tr);
                $tr = str_replace("{test_name}",$pro_data->profile_name,$tr);
                $tr = str_replace("{amount}",$pro_data->master_price,$tr);
                $tr_html .= $tr;
                $i++;
            }
        }
        
            
 

// added on 07-Feb-2018
    if($home_collection!="empty" && in_array('221',$users_data['permission']['section']) )
              {
                if($home_collection[0]->status==1)
                { 
                   $home_collection_data = '<div style="width:100%;border-top:1px solid #111;"> <div style="float:left;font-weight:bold;">Home Collection:</div><div style="float:right;font-weight:bold;">'.$all_detail['booking_list'][0]->home_collection_amount.'</div></div>'; 
                   $template_data->template = str_replace("{home_collection}",$home_collection_data,$template_data->template); 
                   
                   $template_data->template = str_replace("{home_collection_amount}",$all_detail['booking_list'][0]->home_collection_amount,$template_data->template); 
                }
                else
                {
                    $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                    $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
                }
              }
              else
              {
                $template_data->template = str_replace("{home_collection}","",$template_data->template); 
                
                $template_data->template = str_replace("{home_collection_amount}",'',$template_data->template); 
                
              }
// added on 07-Feb-2018

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    $template_data->template = str_replace("{sales_name}",$signature,$template_data->template);
    $template_data->template = str_replace("{total_discount}",$all_detail['booking_list'][0]->discount,$template_data->template);
    $template_data->template = str_replace("{net_amount}",$all_detail['booking_list'][0]->net_amount,$template_data->template);
    $template_data->template = str_replace("{paid_amount}",$all_detail['booking_list'][0]->paid_amount,$template_data->template);
    $template_data->template = str_replace("{gross_total_amount}",$all_detail['booking_list'][0]->total_amount,$template_data->template);
    $template_data->template = str_replace("{balance}",number_format($balance,2),$template_data->template);
   	$this->session->unset_userdata('test_booking_id');
    	echo $template_data->template;
    }

/* end leaser printing*/
?>

