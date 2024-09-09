<?php 
$user_detail = $this->session->userdata('auth_users');
$users_data = $this->session->userdata('auth_users');
/* start thermal printing */
//print_r($all_detail['ambulance_list']);die;
  if(!empty($all_detail['ambulance_list'][0]->booking_no)) 
    {
        $template_data->template = str_replace("{booking_code}",$all_detail['ambulance_list'][0]->booking_no,$template_data->template);
    }

    if(!empty($all_detail['ambulance_list'][0]->vehicle_no))
    {
        $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->vehicle_no,$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->driver_name))
    {
        $template_data->template = str_replace("{driver_name}",$all_detail['ambulance_list'][0]->driver_name,$template_data->template);
    }
     if(!empty($all_detail['ambulance_list'][0]->location_name))
    {
        $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->location_name,$template_data->template);
    }
if($template_data->printer_id==2)
{

    if(!empty($all_detail['ambulance_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ambulance_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

    if(!empty($all_detail['ambulance_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ambulance_list'][0]->relation_name,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }


       $template_data->template = str_replace("{booking_code}",$all_detail['ambulance_list'][0]->booking_code,$template_data->template);
           if(!empty($all_detail['ambulance_list'][0]->vehicle_no))
            {
                $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->vehicle_no,$template_data->template);
            }
            if(!empty($all_detail['ambulance_list'][0]->driver_name))
            {
                $template_data->template = str_replace("{driver_name}",$all_detail['ambulance_list'][0]->driver_name,$template_data->template);
            }
             
                $template_data->template = str_replace("{location}",$all_detail['ambulance_list'][0]->location_name,$template_data->template);
            

    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
    $simulation = get_simulation_name($all_detail['ambulance_list'][0]->simulation_id);
    
    $template_data->template = str_replace("{patient_name}",$simulation.' '.$all_detail['ambulance_list'][0]->patient_name,$template_data->template);

    $address = $all_detail['ambulance_list'][0]->address;
    $pincode = $all_detail['ambulance_list'][0]->pincode;  
    $hospital_code = $all_detail['ambulance_list'][0]->hospital_id;  
   // $patient_address = $address.' - '.$pincode;



    // added on 06-Feb-2018 

        // OPD Fields Starts Here
            $country = $all_detail['ambulance_list'][0]->country_name;    
            $state = $all_detail['ambulance_list'][0]->state_name;    
            $city = $all_detail['ambulance_list'][0]->city_name;    
            $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;
            $email_address = $all_detail['ambulance_list'][0]->patient_email;
            $template_data->template = str_replace("{email_address}",$email_address,$template_data->template);

            // source name
            $source_name= ucwords($all_detail['ambulance_list'][0]->source_name);    
            $template_data->template = str_replace("{source_from}",$source_name,$template_data->template);
            // Disease Name
            $disease_name= ucwords($all_detail['ambulance_list'][0]->disease_name);    
            $template_data->template = str_replace("{diseases}",$disease_name,$template_data->template);
            // Validity Date
            $validity_date = $all_detail['ambulance_list'][0]->validity_date;    
            $template_data->template = str_replace("{validity_date}",date('d-m-Y' , strtotime($validity_date)), $template_data->template);

            // Booking Time
            //echo $all_detail['ambulance_list'][0]->booking_time; die;
            $booking_time ='';
            if(!empty($all_detail['ambulance_list'][0]->booking_time) && $all_detail['ambulance_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['ambulance_list'][0]->booking_time)>0)
            {
                $booking_time = date('h:i A', strtotime($all_detail['ambulance_list'][0]->booking_time));    
            }
            
            $template_data->template = str_replace("{booking_time}", $booking_time, $template_data->template);

            // Remarks
            $remarks = ucwords($all_detail['ambulance_list'][0]->remarks);    
            $template_data->template = str_replace("{remarks}", $remarks, $template_data->template);

            // Remarks
            $remarks = ucwords($all_detail['ambulance_list'][0]->remarks);    
            $template_data->template = str_replace("{remarks}", $remarks, $template_data->template);        
        // OPD Fields Ends Here   

        // Patient Fields starts here

            // adhar no
            $adhar_no=$all_detail['ambulance_list'][0]->adhar_no;
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            // marital status
            $marital_status=$all_detail['ambulance_list'][0]->marital_status;
            $template_data->template = str_replace("{marital_status}", $marital_status, $template_data->template);   

            // marriage anniversary
            if($all_detail['ambulance_list'][0]->anniversary)
            {
                $anniversary=$all_detail['ambulance_list'][0]->anniversary;
                $template_data->template = str_replace("{anniversary}", date('d-m-Y', strtotime($anniversary) ), $template_data->template);       
            }
            else
            {
                $template_data->template = str_replace("{anniversary}",'-', $template_data->template);          
            }
            
            
            // Religion Name
            $religion_name= ucwords($all_detail['ambulance_list'][0]->religion_name);
            $template_data->template = str_replace("{religion}", $religion_name, $template_data->template);
            if($all_detail['ambulance_list'][0]->dob!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($all_detail['ambulance_list'][0]->dob));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }


            // father Wife husband name
                $father_husband=ucwords($all_detail['ambulance_list'][0]->father_husband);
                $template_data->template = str_replace( "{father_husband}", $all_detail['ambulance_list'][0]->f_simulation." ".$father_husband, $template_data->template );     
            // father Wife husband name


             // Mother name
                $mother=ucwords($all_detail['ambulance_list'][0]->mother);
                $template_data->template = str_replace( "{mother}", $mother, $template_data->template );     
            // Mother name

            // Guardian name
                $guardian_name=ucwords($all_detail['ambulance_list'][0]->guardian_name);
                $template_data->template = str_replace( "{guardian_name}", $guardian_name, $template_data->template );     
            // Guardian name   

            // Guardian Email
                $guardian_email=ucwords($all_detail['ambulance_list'][0]->guardian_email);
                $template_data->template = str_replace( "{guardian_email}", $guardian_email, $template_data->template );     
            // Guardian Email   

            // Guardian Mobile
                $guardian_phone=ucwords($all_detail['ambulance_list'][0]->guardian_phone);
                $template_data->template = str_replace( "{guardian_phone}", $guardian_phone, $template_data->template );     
            // Guardian Mobile 

            // Relation
                $relation=ucwords($all_detail['ambulance_list'][0]->relation);
                $template_data->template = str_replace( "{relation}", $relation, $template_data->template );     
            // Relation

            // Monthly Income
                $monthly_income=number_format($all_detail['ambulance_list'][0]->monthly_income,2, '.', '');
                
                
                $template_data->template = str_replace( "{monthly_income}", $monthly_income, $template_data->template);     
            // Monthly Income


            // Occupation
                $occupation=$all_detail['ambulance_list'][0]->occupation;
                $template_data->template = str_replace( "{occupation}", $occupation, $template_data->template);     
            // Occupation

            // insurance_type
            $insurance_type_name=$all_detail['ambulance_list'][0]->insurance_type_name;
            $template_data->template = str_replace( "{insurance_type}", $insurance_type_name, $template_data->template);     
            // insurance_type

            // insurance_name
            $insurance_name=$all_detail['ambulance_list'][0]->insurance_name;
            $template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
            // insurance_name

            // insurance_company name
            $insurance_company=$all_detail['ambulance_list'][0]->insurance_company;
            $template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
            // insurance_company name

            // policy number
            $polocy_no=$all_detail['ambulance_list'][0]->polocy_no;
            $template_data->template = str_replace( "{policy_no}", $polocy_no, $template_data->template);     
            // policy number

            // TPA ID
            $tpa_id=$all_detail['ambulance_list'][0]->tpa_id;
            $template_data->template = str_replace( "{tpa_id}", $tpa_id, $template_data->template);// TPA ID

            // insurance Amoumnt
            $ins_amount=number_format($all_detail['ambulance_list'][0]->ins_amount,2, '.', '');
            $template_data->template = str_replace( "{ins_amount}", $ins_amount, $template_data->template);
            // Insurance Amount


            // insurance Authorization no
            $ins_authorization_no=$all_detail['ambulance_list'][0]->ins_authorization_no;
            $template_data->template = str_replace( "{ins_authorization_no}", $ins_authorization_no, $template_data->template);
            // insurance Authorization no

        // Patient Fields ends here
    // added on 06-Feb-2018    
   /*hospital Code*/
 if(!empty($hospital_code)){
     $template_data->template = str_replace("{hospital_code}",$hospital_code,$template_data->template);
 }
 else{
     $template_data->template = str_replace("{hospital_code}"," ",$template_data->template);
 }
    /*hospital Code */
    

    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);

    if(!empty($all_detail['ambulance_list'][0]->opd_type) && $all_detail['ambulance_list'][0]->opd_type=='1')
    {
        $opd_type = $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');
        
    }
    else
    {
        $opd_type = 'Normal';
    }
    $template_data->template = str_replace("{opd_type}",$opd_type,$template_data->template);
    
    if(!empty($all_detail['ambulance_list'][0]->pannel_type) && $all_detail['ambulance_list'][0]->pannel_type=='1')
    {
        $pannel_type = 'Panel';
        
    }
    else
    {
        $pannel_type = 'Normal';
    }

    if(in_array('218',$users_data['permission']['section']))
    {
        if($all_detail['ambulance_list'][0]->paid_amount>0)
        {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ambulance_list'][0]->reciept_prefix.$all_detail['ambulance_list'][0]->reciept_suffix,$template_data->template);
        }
    }
    else
    {
        $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
    }
    
    if($all_detail['ambulance_list'][0]->referral_doctor_name)
    {
     $reff_doc = '<div><b>Referred By: </b>'.$all_detail['ambulance_list'][0]->referral_doctor_name.'</div>';
     $template_data->template = str_replace("{referral_by}",$reff_doc,$template_data->template);
    }
     else{
         $template_data->template = str_replace("{referral_by}",'',$template_data->template);
    }
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    if(!empty($all_detail['ambulance_list'][0]->token_no))
    {
        $template_data->template = str_replace("{token_no}",$all_detail['ambulance_list'][0]->token_no,$template_data->template);    
    }
    else
    {
        $template_data->template = str_replace("{token_no}",'',$template_data->template);
        $template_data->template = str_replace("Token No.:",'',$template_data->template);
        $template_data->template = str_replace("Token No.",'',$template_data->template);
    }
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);


    if(!empty($all_detail['ambulance_list'][0]->next_app_date) && $all_detail['ambulance_list'][0]->next_app_date!='0000-00-00' && $all_detail['ambulance_list'][0]->next_app_date!='1970-01-01')
    {
         $next_app_date = date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->next_app_date));
        $next_appdate = '<tr>
                        <td align="left" style="font-size:12px;" valign="top"><b>Next App. Date :</b></td>
                        <td align="left" style="font-size:12px;" valign="top">'.$next_app_date.'</td>
                    </tr>';
        $template_data->template = str_replace("{next_app_date}",$next_appdate,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{next_app_date}",'',$template_data->template);
    }
    

    


    $template_data->template = str_replace("{mobile_no}",$all_detail['ambulance_list'][0]->mobile_no,$template_data->template);
   
    if(!empty($all_detail['ambulance_list'][0]->booking_no))
    {
        $template_data->template = str_replace("{booking_level}",'Booking No. :',$template_data->template);
        $template_data->template = str_replace("{booking_code}",$all_detail['ambulance_list'][0]->booking_no,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{booking_level}",'',$template_data->template);
         $template_data->template = str_replace("{booking_code}",'',$template_data->template);
    }

    

    if(!empty($all_detail['ambulance_list'][0]->booking_date))
    {
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->booking_date)),$template_data->template);
    }
   
    if(!empty($all_detail['ambulance_list'][0]->attended_doctor))
    {
        
        $template_data->template = str_replace("{Consultant_level}",'Consultant:',$template_data->template);
        $template_data->template = str_replace("{Consultant}",'Dr.'.get_doctor_name($all_detail['ambulance_list'][0]->attended_doctor),$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant_level}",'',$template_data->template);
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }

    

    //$template_data->template = str_replace("{doctor_name}",get_doctor_name($all_detail['ambulance_list'][0]->attended_doctor),$template_data->template);
    //$template_data->template = str_replace("{specialization}",get_specilization_name($all_detail['ambulance_list'][0]->specialization_id),$template_data->template);
    
    if(!empty($all_detail['ambulance_list'][0]->specialization_id))
    {
        $spec_name='';
        $specialization = get_specilization_name($all_detail['ambulance_list'][0]->specialization_id);
        if(!empty($specialization))
        {
           $spec_name= str_replace('(Default)','',$specialization);
        }
        
        $template_data->template = str_replace("{specialization_level}",'Spec. :',$template_data->template);
        $template_data->template = str_replace("{specialization}",$spec_name,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{specialization_level}",'',$template_data->template);
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    }

    	$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['ambulance_list'][0]->gender];
        $age_y = $all_detail['ambulance_list'][0]->age_y; 
        $age_m = $all_detail['ambulance_list'][0]->age_m;
        $age_d = $all_detail['ambulance_list'][0]->age_d;

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
    
      /*payment Mode*/
     $pos_start1 = strpos($template_data->template, '{start_pay_loop}');
    $pos_end1 = strpos($template_data->template, '{end_pay_loop}');
    $row_last_length1 = $pos_end1-$pos_start1;
    $row_loop1 = substr($template_data->template,$pos_start1+12,$row_last_length1-12);
    $rplc_row = trim(substr($template_data->template,$pos_start1,$row_last_length1+10));
    /*payment Mode*/

    //////////////////////// 
    if(!empty($all_detail['ambulance_list']['particular_list']))
    {
    $i=1;
    $tr_html = "";
    foreach($all_detail['ambulance_list']['particular_list'] as $particular_list)
    { 
    	 $tr = $row_loop;
    	 $tr = str_replace("{s_no}",$i,$tr);
    	 $tr = str_replace("{particular}",$particular_list->particulars,$tr);
    	 $tr = str_replace("{quantity}",$particular_list->quantity,$tr);
    	 $tr = str_replace("{amount}",$particular_list->amount,$tr);
    	 $tr_html .= $tr;
    	 $i++;
      	 	 	 	
    }

        if(!empty($all_detail['ambulance_list'][0]->package_id))
        {
            $tr = $row_loop;
            $tr = str_replace("{s_no}",$i,$tr);
            $tr = str_replace("{particular}",$all_detail['ambulance_list'][0]->package_name . " <b>(1)</b>",$tr);
            $tr = str_replace("{quantity}",'',$tr);
            $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->package_amount,$tr);
            $tr_html .= $tr;
        }
    }
    else
    {		
    	 $i=1;	
    	$tr_html = "";
    	$tr = $row_loop;
    	$tr = str_replace("{s_no}",$i,$tr);
    	$tr = str_replace("{particular}",'Ambulance Charges1',$tr);
    	$tr = str_replace("{quantity}","",$tr);
    	 $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->consultant_charge,$tr);
        $tr_html .= $tr;

          if(!empty($particulars))
            {    foreach($particulars as $particular_list)
                { 
                     $i++;
                     $tr = $row_loop;
                     $tr = str_replace("{s_no}",$i,$tr);
                     
                     $tr = str_replace("{particular}",$particular_list->particulars,$tr);
                     $tr = str_replace("{quantity}",$particular_list->quantity,$tr);
                     $tr = str_replace("{amount}",$particular_list->amount,$tr);
                     $tr_html .= $tr;
                }
            }
            
          


        if(!empty($all_detail['ambulance_list'][0]->package_id))
        {
            $i++;
            $tr = $row_loop;
            $tr = str_replace("{s_no}",$i,$tr);
            $tr = str_replace("{particular}",$all_detail['ambulance_list'][0]->package_name . " <b>(1)</b>",$tr);
            $tr = str_replace("{quantity}",'',$tr);
            $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->package_amount,$tr);
            $tr_html .= $tr;
        }

    }

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);


    $template_data->template = str_replace("{total_discount}",$all_detail['ambulance_list'][0]->discount,$template_data->template);
    $template_data->template = str_replace("{total_amount}",$all_detail['ambulance_list'][0]->total_amount,$template_data->template);
    
    $template_data->template = str_replace("{net_amount}",$all_detail['ambulance_list'][0]->net_amount,$template_data->template);
    $template_data->template = str_replace("{total_amount}",$all_detail['ambulance_list'][0]->total_amount,$template_data->template);

    $template_data->template = str_replace("{paid_amount}",$all_detail['ambulance_list'][0]->paid_amount,$template_data->template);
    
     if($all_detail['ambulance_list'][0]->refund_amount=='0')
    {
       $refund_amt =  '0.00';
    }
    else
    {
      $refund_amt =  number_format($all_detail['ambulance_list'][0]->total_refund,2,'.','');
    }
    $template_data->template = str_replace("{refund_amount}",number_format($refund_amt,2, '.', ''),$template_data->template);

    $card_no='';
    if(!empty($all_detail['ambulance_list'][0]->card_no))
    {
      $card_no = '('. $all_detail['ambulance_list'][0]->card_no.')';
    }
    
     /*Payment Mode */
         $total_paid_amount=0.00;
 
          if(!empty($payment_modes))
            {
              
                $i=1;
                $ref_data = "";
                 $new_payment_amount=0;
                foreach($payment_modes as $mode)
                { 
                    $new_payment_amount = $new_payment_amount+$mode['paid_amount'];
                   if($mode['paid_amount']=='0')
                {
                   $payment_amountt =  '0.00';
                }
                else
                {
                  $payment_amountt =  number_format($mode['paid_amount'],2,'.','');
                }
                    $total_paid_amount = $total_paid_amount+$mode['paid_amount'];
                           $payment_date= date('Y-m-d',strtotime($mode['created_date']));
                          $pay_mode=$mode['pay_mode'];
                           $pay_data .='<div style="float:left;width:100%;display:inline-flex;">
                <div style="width:10%;line-height:17px;padding-left:15px;">'.$i.'</div>
                
                <div style="width:50%;line-height:17px;">'.$pay_mode.'</div>
                
                <div style="width:25%;line-height:17px;">'.$payment_date.'</div>
                
                <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">'.$payment_amountt.'</div> 
                </div>
                ';
                $i++;}
                
                
                $pay_data .='<div style="float:left;width:100%;display:inline-flex;">
                <div style="width:10%;line-height:17px;padding-left:15px;">&nbsp;</div>
                <div style="width:25%;line-height:17px;">&nbsp;</div>
                
                <div style="width:50%;line-height:17px;;border-top:1px solid #111;font-weight:bold;">Total:</div>
                
                
                
                <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;;border-top:1px solid #111;font-weight:bold;">'.number_format($new_payment_amount,2, '.', '').'</div> 
                </div>
                ';
                
            }
          if(!empty($payment_modes)){
   $template_data->template = str_replace("{payment_data}",$pay_data,$template_data->template);
          }
          else{
              $template_data->template = str_replace("{payment_data}","",$template_data->template);
          }
    /*Payment Mode*/

    /* $template_data->template = str_replace("{payment_mode}",ucfirst($all_detail['ambulance_list'][0]->payment_mode).$card_no,$template_data->template);*/
/*if($all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount=='0')
    {
       $balance =  '0.00';
    }
    else
    {
      $balance =  number_format($all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount,2,'.','');
    }*/
    $balance_data = $all_detail['ambulance_list'][0]->net_amount-$total_paid_amount;
    
   /* if($balance_data>0)
    {
        $balance_data=number_format($balance_data,2);
    }
    else
    {
        $balance_data='0.00';
    }*/
    
    /* if($balance_data>0)
    {
        $balance_data=number_format($balance_data,2);
    }else
    {*/
          $balance_data = $balance_data+$all_detail['ambulance_list'][0]->refund_amount;  
    //}
            
    
    $template_data->template = str_replace("{balance}",number_format($balance_data,2, '.', ''),$template_data->template);
     
    if(!empty($all_detail['ambulance_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ambulance_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
       
    }
   /* $template_data->template = str_replace("{total_paid_amount}",number_format(($total_paid_amount-$new_total_refund),2, '.', ''),$template_data->template);*/
   $template_data->template = str_replace("{total_paid_amount}",number_format(($total_paid_amount),2, '.', ''),$template_data->template);
    //echo "<pre>";print_r($template_data); exit;
    $this->session->unset_userdata('opd_booking_id');
    echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
if($template_data->printer_id==3)
{
    

   // if(!empty($all_detail['ambulance_list'][0]->relation_name))
   //  {
   //       $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
   //       $template_data->template = str_replace("{parent_relation_type}",$all_detail['ambulance_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['ambulance_list'][0]->relation_name,$template_data->template);
   //  }
   //  else
   //  {
   //      $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
   //  }


       if(!empty($all_detail['ambulance_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ambulance_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

        if(!empty($all_detail['ambulance_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ambulance_list'][0]->relation_name,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }



    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);

    $simulation = get_simulation_name($all_detail['ambulance_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['ambulance_list'][0]->patient_name,$template_data->template);

    $address = $all_detail['ambulance_list'][0]->address;
    $pincode = $all_detail['ambulance_list'][0]->pincode;
    $hospital_code = $all_detail['ambulance_list'][0]->hospital_id;
    
    //$patient_address = $address.' - '.$pincode;

 /*hospital Code*/
 if(!empty($hospital_code)){
     $template_data->template = str_replace("{hospital_code}",$hospital_code,$template_data->template);
 }
 else{
     $template_data->template = str_replace("{hospital_code}"," ",$template_data->template);
 }
    /*hospital Code */

    // added on 06-Feb-2018 

        // OPD Fields Starts Here
            $country = $all_detail['ambulance_list'][0]->country_name;    
            $state = $all_detail['ambulance_list'][0]->state_name;    
            $city = $all_detail['ambulance_list'][0]->city_name;    
            $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;
            $email_address = $all_detail['ambulance_list'][0]->patient_email;
            $template_data->template = str_replace("{email_address}",$email_address,$template_data->template);

            // source name
            $source_name= ucwords($all_detail['ambulance_list'][0]->source_name);    
            $template_data->template = str_replace("{source_from}",$source_name,$template_data->template);
            // Disease Name
            $disease_name= ucwords($all_detail['ambulance_list'][0]->disease_name);    
            $template_data->template = str_replace("{diseases}",$disease_name,$template_data->template);
            // Validity Date
            $validity_date = $all_detail['ambulance_list'][0]->validity_date;    
            $template_data->template = str_replace("{validity_date}",date('d-m-Y' , strtotime($validity_date)), $template_data->template);

            // Booking Time
            $booking_time ='';
            if(!empty($all_detail['ambulance_list'][0]->booking_time) &&  $all_detail['ambulance_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['ambulance_list'][0]->booking_time)>0)
            {
                $booking_time = date('h:i A', strtotime($all_detail['ambulance_list'][0]->booking_time));    
            } 
            $template_data->template = str_replace("{booking_time}", $booking_time, $template_data->template);

            // Remarks
            $remarks = ucwords($all_detail['ambulance_list'][0]->remarks);    
            $template_data->template = str_replace("{remarks}", $remarks, $template_data->template);

            // Remarks
            $remarks = ucwords($all_detail['ambulance_list'][0]->remarks);    
            $template_data->template = str_replace("{remarks}", $remarks, $template_data->template);        
        // OPD Fields Ends Here   

        // Patient Fields starts here

            // adhar no
            $adhar_no=$all_detail['ambulance_list'][0]->adhar_no;
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            // marital status
            $marital_status=$all_detail['ambulance_list'][0]->marital_status;
            $template_data->template = str_replace("{marital_status}", $marital_status, $template_data->template);   

            // marriage anniversary
            if($all_detail['ambulance_list'][0]->anniversary)
            {
                $anniversary=$all_detail['ambulance_list'][0]->anniversary;
                $template_data->template = str_replace("{anniversary}", date('d-m-Y', strtotime($anniversary) ), $template_data->template);       
            }
            else
            {
                $template_data->template = str_replace("{anniversary}",'-', $template_data->template);          
            }
            
            
            // Religion Name
            $religion_name= ucwords($all_detail['ambulance_list'][0]->religion_name);
            $template_data->template = str_replace("{religion}", $religion_name, $template_data->template);
            if($all_detail['ambulance_list'][0]->dob!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($all_detail['ambulance_list'][0]->dob));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }


            // father Wife husband name
                $father_husband=ucwords($all_detail['ambulance_list'][0]->father_husband);
                $template_data->template = str_replace( "{father_husband}", $all_detail['ambulance_list'][0]->f_simulation." ".$father_husband, $template_data->template );     
            // father Wife husband name


             // Mother name
                $mother=ucwords($all_detail['ambulance_list'][0]->mother);
                $template_data->template = str_replace( "{mother}", $mother, $template_data->template );     
            // Mother name

            // Guardian name
                $guardian_name=ucwords($all_detail['ambulance_list'][0]->guardian_name);
                $template_data->template = str_replace( "{guardian_name}", $guardian_name, $template_data->template );     
            // Guardian name   

            // Guardian Email
                $guardian_email=ucwords($all_detail['ambulance_list'][0]->guardian_email);
                $template_data->template = str_replace( "{guardian_email}", $guardian_email, $template_data->template );     
            // Guardian Email   

            // Guardian Mobile
                $guardian_phone=ucwords($all_detail['ambulance_list'][0]->guardian_phone);
                $template_data->template = str_replace( "{guardian_phone}", $guardian_phone, $template_data->template );     
            // Guardian Mobile 

            // Relation
                $relation=ucwords($all_detail['ambulance_list'][0]->relation);
                $template_data->template = str_replace( "{relation}", $relation, $template_data->template );     
            // Relation

            // Monthly Income
                $monthly_income=number_format($all_detail['ambulance_list'][0]->monthly_income,2, '.', '');
                $template_data->template = str_replace( "{monthly_income}", $monthly_income, $template_data->template);     
            // Monthly Income


            // Occupation
                $occupation=$all_detail['ambulance_list'][0]->occupation;
                $template_data->template = str_replace( "{occupation}", $occupation, $template_data->template);     
            // Occupation

            // insurance_type
            $insurance_type_name=$all_detail['ambulance_list'][0]->insurance_type_name;
            $template_data->template = str_replace( "{insurance_type}", $insurance_type_name, $template_data->template);     
            // insurance_type

            // insurance_name
            $insurance_name=$all_detail['ambulance_list'][0]->insurance_name;
            $template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
            // insurance_name

            // insurance_company name
            $insurance_company=$all_detail['ambulance_list'][0]->insurance_company;
            $template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
            // insurance_company name

            // policy number
            $polocy_no=$all_detail['ambulance_list'][0]->polocy_no;
            $template_data->template = str_replace( "{policy_no}", $polocy_no, $template_data->template);     
            // policy number

            // TPA ID
            $tpa_id=$all_detail['ambulance_list'][0]->tpa_id;
            $template_data->template = str_replace( "{tpa_id}", $tpa_id, $template_data->template);// TPA ID

            // insurance Amoumnt
            $ins_amount=number_format($all_detail['ambulance_list'][0]->ins_amount,2, '.', '');
            $template_data->template = str_replace( "{ins_amount}", $ins_amount, $template_data->template);
            // Insurance Amount


            // insurance Authorization no
            $ins_authorization_no=$all_detail['ambulance_list'][0]->ins_authorization_no;
            $template_data->template = str_replace( "{ins_authorization_no}", $ins_authorization_no, $template_data->template);
            // insurance Authorization no

        // Patient Fields ends here
    // added on 06-Feb-2018    


    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);

     if($all_detail['ambulance_list'][0]->referral_doctor_name)
    {
     $reff_doc = '<div><b>Referred By: </b>'.$all_detail['ambulance_list'][0]->referral_doctor_name.'</div>';
     $template_data->template = str_replace("{referral_by}",$reff_doc,$template_data->template);
    }
    else{
         $template_data->template = str_replace("{referral_by}",'',$template_data->template);
    }
    

    if(!empty($all_detail['ambulance_list'][0]->token_no))
    {
        $template_data->template = str_replace("{token_no}",$all_detail['ambulance_list'][0]->token_no,$template_data->template);    
    }
    else
    {
        $template_data->template = str_replace("{token_no}",'',$template_data->template);
        $template_data->template = str_replace("Token No.:",'',$template_data->template);
        $template_data->template = str_replace("Token No.",'',$template_data->template);
    }

      if(in_array('218',$users_data['permission']['section']))
      {

            if($all_detail['ambulance_list'][0]->paid_amount>0)
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ambulance_list'][0]->reciept_prefix.$all_detail['ambulance_list'][0]->reciept_suffix,$template_data->template);
            }
      }
      else
      {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);

    if(!empty($all_detail['ambulance_list'][0]->next_app_date) && $all_detail['ambulance_list'][0]->next_app_date!='0000-00-00' && $all_detail['ambulance_list'][0]->next_app_date!='1970-01-01')
    {
        $next_app_date = date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->next_app_date));
    
       $next_appdate = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Next App. Date :</div>

            <div style="width:60%;line-height:17px;">'.$next_app_date.'</div>
            </div>';
        $template_data->template = str_replace("{next_app_date}",$next_appdate,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{next_app_date}",'',$template_data->template);
    }
    
    $template_data->template = str_replace("{mobile_no}",$all_detail['ambulance_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['ambulance_list'][0]->patient_code,$template_data->template);

    $email_address = $all_detail['ambulance_list'][0]->patient_email;
    $template_data->template = str_replace("{email_address}",$email_address,$template_data->template);




    if(!empty($all_detail['ambulance_list'][0]->booking_code))
    {
        $receipt_code = '<div><br><b>Booking Code.</b>'.$all_detail['ambulance_list'][0]->booking_code.'</div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
    }
        if(!empty($all_detail['ambulance_list'][0]->vehicle_no))
    {
        $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->vehicle_no,$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->driver_name))
    {
        $template_data->template = str_replace("{driver_name}",$all_detail['ambulance_list'][0]->driver_name,$template_data->template);
    }
     
        $template_data->template = str_replace("{location}",$all_detail['ambulance_list'][0]->location_name,$template_data->template);
    

    if(!empty($all_detail['ambulance_list'][0]->booking_date))
    {
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->booking_date)),$template_data->template);
    }
    
      if(!empty($all_detail['ambulance_list'][0]->booking_code))
    {
        $template_data->template = str_replace("{booking_code}",$all_detail['ambulance_list'][0]->booking_code,$template_data->template);
    }
   if(!empty($all_detail['ambulance_list'][0]->vehicle_no))
    {
        $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->vehicle_no,$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->driver_name))
    {
        $template_data->template = str_replace("{driver_name}",$all_detail['ambulance_list'][0]->driver_name,$template_data->template);
    }
     
        $template_data->template = str_replace("{location}",$all_detail['ambulance_list'][0]->location_name,$template_data->template);
    
   
    $template_data->template = str_replace("{next_app_date}",'',$template_data->template);
    if(!empty($all_detail['ambulance_list'][0]->attended_doctor))
    {
        $consultant_new = '<br><b>Consultant</b>'.'Dr.'. get_doctor_name($all_detail['ambulance_list'][0]->attended_doctor);
        $template_data->template = str_replace("{Consultant}",$consultant_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->specialization_id))
    {
        $spec_name='';
        $specialization = get_specilization_name($all_detail['ambulance_list'][0]->specialization_id);
        if(!empty($specialization))
        {
            $spec_name= str_replace('(Default)','',$specialization);
        }
        
        $specialization_new = '<br><b>Spec.</b>'.$spec_name;
        $template_data->template = str_replace("{specialization}",$specialization_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    }

    	$genders = array('0'=>'F','1'=>'M');
        $gender = $genders[$all_detail['ambulance_list'][0]->gender];
        $age_y = $all_detail['ambulance_list'][0]->age_y; 
        $age_m = $all_detail['ambulance_list'][0]->age_m;
        $age_d = $all_detail['ambulance_list'][0]->age_d;

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
    
      /*payment Mode*/
     $pos_start1 = strpos($template_data->template, '{start_pay_loop}');
    $pos_end1 = strpos($template_data->template, '{end_pay_loop}');
    $row_last_length1 = $pos_end1-$pos_start1;
    $row_loop1 = substr($template_data->template,$pos_start1+12,$row_last_length1-12);
    $rplc_row = trim(substr($template_data->template,$pos_start1,$row_last_length1+10));
    /*payment Mode*/
    //////////////////////// 
    if(!empty($all_detail['ambulance_list']['particular_list']))
    {
    $i=1;
    $tr_html = "";
    foreach($all_detail['ambulance_list']['particular_list'] as $particular_list)
    { 
    	 $tr = $row_loop;
    	 $tr = str_replace("{s_no}",$i,$tr);
    	 $tr = str_replace("{particular}",$particular_list->particulars,$tr);
    	 $tr = str_replace("{quantity}",$particular_list->quantity,$tr);
    	 $tr = str_replace("{amount}",$particular_list->amount,$tr);
    	 $tr_html .= $tr;
    	 $i++;
      	 	 	 	
    }
        if(!empty($all_detail['ambulance_list'][0]->package_id))
        {
            $tr = $row_loop;
            $tr = str_replace("{s_no}",$i,$tr);
            $tr = str_replace("{particular}",$all_detail['ambulance_list'][0]->package_name . " <b>(1)</b>",$tr);
            $tr = str_replace("{quantity}",'',$tr);
            $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->package_amount,$tr);
            $tr_html .= $tr;
        }

    }
       
    else
    {		
    	 $i=1;	
    	$tr_html = "";
    	$tr = $row_loop;
    	$tr = str_replace("{s_no}",$i,$tr);
    	$tr = str_replace("{particular}",'Ambulance Charges2',$tr);
    	$tr = str_replace("{quantity}","",$tr);
    	 $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->consultant_charge,$tr);
        $tr_html .= $tr;

          if(!empty($particulars))
            {    foreach($particulars as $particular_list)
                { 
                     $i++;
                     $tr = $row_loop;
                     $tr = str_replace("{s_no}",$i,$tr);
                     
                     $tr = str_replace("{particular}",$particular_list->particulars,$tr);
                     $tr = str_replace("{quantity}",$particular_list->quantity,$tr);
                     $tr = str_replace("{amount}",$particular_list->amount,$tr);
                     $tr_html .= $tr;
                }
            }

        if(!empty($all_detail['ambulance_list'][0]->package_id))
        {
            $i++;
            $tr = $row_loop;
            $tr = str_replace("{s_no}",$i,$tr);
            $tr = str_replace("{particular}",$all_detail['ambulance_list'][0]->package_name . " <b>(1)</b>",$tr);
            $tr = str_replace("{quantity}",'',$tr);
            $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->package_amount,$tr);
            $tr_html .= $tr;
        }

    }
    
   

 $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    
    $template_data->template = str_replace("{salesman}",ucfirst($user_detail['user_name']),$template_data->template);

    $template_data->template = str_replace("{total_discount}",$all_detail['ambulance_list'][0]->discount,$template_data->template);

    $template_data->template = str_replace("{net_amount}",$all_detail['ambulance_list'][0]->net_amount,$template_data->template);
    $template_data->template = str_replace("{total_amount}",$all_detail['ambulance_list'][0]->total_amount,$template_data->template);

    $template_data->template = str_replace("{paid_amount}",$all_detail['ambulance_list'][0]->paid_amount,$template_data->template);

    $template_data->template = str_replace("{total_gross}",$all_detail['ambulance_list'][0]->total_amount,$template_data->template);

    //$template_data->template = str_replace("{balance}",$all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount,$template_data->template);
    
    
     if($all_detail['ambulance_list'][0]->refund_amount=='0')
    {
       $refund_amt =  '0.00';
    }
    else
    {
      $refund_amt =  number_format($all_detail['ambulance_list'][0]->total_refund,2,'.','');
    }
    $template_data->template = str_replace("{refund_amount}",number_format($refund_amt,2, '.', ''),$template_data->template);

     $card_no='';
    if(!empty($all_detail['ambulance_list'][0]->card_no))
    {
      $card_no = '('. $all_detail['ambulance_list'][0]->card_no.')';
    }
     /*Payment Mode */
      $total_paid_amount=0.00;
 
     
          if(!empty($payment_modes))
            {
              
            $i=1;
            
            $new_payment_amount=0;
            foreach($payment_modes as $mode)
            { 
                $new_payment_amount=$new_payment_amount+$mode['paid_amount'];
               if($mode['paid_amount']=='0')
                {
                   $payment_amountt =  '0.00';
                }
                else
                {
                  $payment_amountt =  number_format($mode['paid_amount'],2,'.','');
                }
                $total_paid_amount = $total_paid_amount+$mode['paid_amount'];
                       $payment_date= date('Y-m-d',strtotime($mode['created_date']));
                      $pay_mode=$mode['pay_mode'];
                       $pay_data .='<div style="float:left;width:100%;display:inline-flex;">
                    <div style="width:10%;line-height:17px;padding-left:15px;">#</div>
                    <div style="width:25%;line-height:17px;">'.$payment_date.'</div>
                    
                    <div style="width:50%;line-height:17px;">'.$pay_mode.'</div>
                    
                    
                    
                    <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">'.$payment_amountt.'</div> 
                    </div>
                    ';
                    $i++;
                
            }
                
                $pay_data .='<div style="float:left;width:100%;display:inline-flex;">
                <div style="width:10%;line-height:17px;padding-left:15px;">&nbsp;</div>
                <div style="width:25%;line-height:17px;">&nbsp;</div>
                
                <div style="width:50%;line-height:17px;border-top:1px solid #111;font-weight:bold;">Total:</div>
                
                
                
                <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;;border-top:1px solid #111;font-weight:bold;">'.number_format($new_payment_amount,2, '.', '').'</div> 
                </div>
                ';
                
            }
          if(!empty($payment_modes))
          {
            $template_data->template = str_replace("{payment_data}",$pay_data,$template_data->template);
          }
          else
          {
              $template_data->template = str_replace("{payment_data}","",$template_data->template);
          }
    /*Payment Mode*/

    
    /*if($all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount=='0')
    {
       $balance =  '0.00';
    }
    else
    {
      $balance =  number_format($all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount,2,'.','');
    }*/
    
    //$total_paid_amount-$new_total_refund
    $balance_data = $all_detail['ambulance_list'][0]->net_amount-$total_paid_amount;
    
    /*if($balance_data>0)
    {
        $balance_data=number_format($balance_data,2);
    }
    else
    {
        $balance_data='0.00';
    }*/
    /* if($balance_data>0)
    {
        $balance_data=number_format($balance_data,2);
    }else
    {*/
          $balance_data = $balance_data+$all_detail['ambulance_list'][0]->refund_amount;  
    //}
    $template_data->template = str_replace("{balance}",number_format($balance_data,2, '.', ''),$template_data->template);
    
    if(!empty($all_detail['ambulance_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ambulance_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
       $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
       
    }
    /*$template_data->template = str_replace("{total_paid_amount}",number_format(($total_paid_amount-$new_total_refund),2, '.', ''),$template_data->template);*/
    
    $template_data->template = str_replace("{total_paid_amount}",number_format(($total_paid_amount),2, '.', ''),$template_data->template);
    
    $this->session->unset_userdata('opd_booking_id');
    echo $template_data->template;
}
/* end dot printing */


/* start leaser printing */
//print '<pre>';print_r($all_detail['ambulance_list']);die;
if($template_data->printer_id==1)
{
    

   // if(!empty($all_detail['ambulance_list'][0]->relation_name))
   //  {
   //      $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
   //       $template_data->template = str_replace("{parent_relation_type}",$all_detail['ambulance_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['ambulance_list'][0]->relation_name,$template_data->template);
   //  }
   //  else
   //  {
   //     $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template); 
   //  }

        if(!empty($all_detail['ambulance_list'][0]->relation))
        {
        $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['ambulance_list'][0]->relation,$template_data->template);
        }
        else
        {
         $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
        }

        if(!empty($all_detail['ambulance_list'][0]->relation_name))
        {
        $rel_simulation = get_simulation_name($all_detail['ambulance_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['ambulance_list'][0]->relation_name,$template_data->template);
        }
        else
        {
        $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
        }


    $template_data->template = str_replace("{page_type}",$page_type,$template_data->template);
    $simulation = get_simulation_name($all_detail['ambulance_list'][0]->simulation_id);
    $template_data->template = str_replace("{patient_name}",$simulation.''.$all_detail['ambulance_list'][0]->patient_name,$template_data->template);
    $template_data->template = str_replace("{patient_reg_no}",$all_detail['ambulance_list'][0]->patient_code,$template_data->template);
    $address = $all_detail['ambulance_list'][0]->address;
    $pincode = $all_detail['ambulance_list'][0]->pincode;
    $hospital_code = $all_detail['ambulance_list'][0]->hospital_id;
   
    if($all_detail['ambulance_list'][0]->referral_doctor_name)
    {
     $reff_doc = '<div><b>Referred By: </b>'.$all_detail['ambulance_list'][0]->referral_doctor_name.'</div>';
     $template_data->template = str_replace("{referral_by}",$reff_doc,$template_data->template);
    }
     else{
         $template_data->template = str_replace("{referral_by}",'',$template_data->template);
    }
 /*hospital Code*/
 if(!empty($hospital_code)){
     $template_data->template = str_replace("{hospital_code}",$hospital_code,$template_data->template);
 }
 else{
     $template_data->template = str_replace("{hospital_code}"," ",$template_data->template);
 }
    /*hospital Code */

    // added on 06-Feb-2018 

        // OPD Fields Starts Here
            $country = $all_detail['ambulance_list'][0]->country_name;    
            $state = $all_detail['ambulance_list'][0]->state_name;    
            $city = $all_detail['ambulance_list'][0]->city_name;    
            $patient_address = $address.'<br/>'.$country.','.$state.'<br/>'.$city.' - '.$pincode;
            $email_address = $all_detail['ambulance_list'][0]->patient_email;
            $template_data->template = str_replace("{email_address}",$email_address,$template_data->template);

            // source name
            $source_name= ucwords($all_detail['ambulance_list'][0]->source_name);    
            $template_data->template = str_replace("{source_from}",$source_name,$template_data->template);
            // Disease Name
            $disease_name= ucwords($all_detail['ambulance_list'][0]->disease_name);    
            $template_data->template = str_replace("{diseases}",$disease_name,$template_data->template);
            // Validity Date
            $validity_date = $all_detail['ambulance_list'][0]->validity_date;    
            $template_data->template = str_replace("{validity_date}",date('d-m-Y' , strtotime($validity_date)), $template_data->template);

            // Booking Time
            $booking_time ='';
            if(!empty($all_detail['ambulance_list'][0]->booking_time) &&  $all_detail['ambulance_list'][0]->booking_time!='00:00:00' && strtotime($all_detail['ambulance_list'][0]->booking_time)>0)
            {
                $booking_time = date('h:i A', strtotime($all_detail['ambulance_list'][0]->booking_time));    
            }   
            $template_data->template = str_replace("{booking_time}", $booking_time, $template_data->template);

            // Remarks
            $remarks = ucwords($all_detail['ambulance_list'][0]->remarks);    
            $template_data->template = str_replace("{remarks}", $remarks, $template_data->template);

            // Remarks
            $remarks = ucwords($all_detail['ambulance_list'][0]->remarks);    
            $template_data->template = str_replace("{remarks}", $remarks, $template_data->template);        
        // OPD Fields Ends Here   

        // Patient Fields starts here

            // adhar no
            $adhar_no=$all_detail['ambulance_list'][0]->adhar_no;
            $template_data->template = str_replace("{adhar_no}", $adhar_no, $template_data->template);   

            // marital status
            $marital_status=$all_detail['ambulance_list'][0]->marital_status;
            $template_data->template = str_replace("{marital_status}", $marital_status, $template_data->template);   

            // marriage anniversary
            if($all_detail['ambulance_list'][0]->anniversary)
            {
                $anniversary=$all_detail['ambulance_list'][0]->anniversary;
                $template_data->template = str_replace("{anniversary}", date('d-m-Y', strtotime($anniversary) ), $template_data->template);       
            }
            else
            {
                $template_data->template = str_replace("{anniversary}",'-', $template_data->template);          
            }
            
            
            // Religion Name
            $religion_name= ucwords($all_detail['ambulance_list'][0]->religion_name);
            $template_data->template = str_replace("{religion}", $religion_name, $template_data->template);
            if($all_detail['ambulance_list'][0]->dob!='0000-00-00')
            {
                $dob=date('d-m-Y' ,strtotime($all_detail['ambulance_list'][0]->dob));
                $template_data->template = str_replace("{dob}", $dob, $template_data->template);
            }
            else
            {
                $template_data->template = str_replace("{dob}",'-', $template_data->template);   
            }


            // father Wife husband name
                $father_husband=ucwords($all_detail['ambulance_list'][0]->father_husband);
                $template_data->template = str_replace( "{father_husband}", $all_detail['ambulance_list'][0]->f_simulation." ".$father_husband, $template_data->template );     
            // father Wife husband name


             // Mother name
                $mother=ucwords($all_detail['ambulance_list'][0]->mother);
                $template_data->template = str_replace( "{mother}", $mother, $template_data->template );     
            // Mother name

            // Guardian name
                $guardian_name=ucwords($all_detail['ambulance_list'][0]->guardian_name);
                $template_data->template = str_replace( "{guardian_name}", $guardian_name, $template_data->template );     
            // Guardian name   

            // Guardian Email
                $guardian_email=ucwords($all_detail['ambulance_list'][0]->guardian_email);
                $template_data->template = str_replace( "{guardian_email}", $guardian_email, $template_data->template );     
            // Guardian Email   

            // Guardian Mobile
                $guardian_phone=ucwords($all_detail['ambulance_list'][0]->guardian_phone);
                $template_data->template = str_replace( "{guardian_phone}", $guardian_phone, $template_data->template );     
            // Guardian Mobile 

            // Relation
                $relation=ucwords($all_detail['ambulance_list'][0]->relation);
                $template_data->template = str_replace( "{relation}", $relation, $template_data->template );     
            // Relation

            // Monthly Income
                $monthly_income=number_format($all_detail['ambulance_list'][0]->monthly_income,2, '.', '');
                $template_data->template = str_replace( "{monthly_income}", $monthly_income, $template_data->template);     
            // Monthly Income


            // Occupation
                $occupation=$all_detail['ambulance_list'][0]->occupation;
                $template_data->template = str_replace( "{occupation}", $occupation, $template_data->template);     
            // Occupation

            // insurance_type
            $insurance_type_name=$all_detail['ambulance_list'][0]->insurance_type_name;
            $template_data->template = str_replace( "{insurance_type}", $insurance_type_name, $template_data->template);     
            // insurance_type

            // insurance_name
            $insurance_name=$all_detail['ambulance_list'][0]->insurance_name;
            $template_data->template = str_replace( "{insurance_name}", $insurance_name, $template_data->template);     
            // insurance_name

            // insurance_company name
            $insurance_company=$all_detail['ambulance_list'][0]->insurance_company;
            $template_data->template = str_replace( "{insurance_company}", $insurance_company, $template_data->template);     
            // insurance_company name

            // policy number
            $polocy_no=$all_detail['ambulance_list'][0]->polocy_no;
            $template_data->template = str_replace( "{policy_no}", $polocy_no, $template_data->template);     
            // policy number

            // TPA ID
            $tpa_id=$all_detail['ambulance_list'][0]->tpa_id;
            $template_data->template = str_replace( "{tpa_id}", $tpa_id, $template_data->template);// TPA ID

            // insurance Amoumnt
            $ins_amount=number_format($all_detail['ambulance_list'][0]->ins_amount,2, '.', '');
            $template_data->template = str_replace( "{ins_amount}", $ins_amount, $template_data->template);
            // Insurance Amount


            // insurance Authorization no
            $ins_authorization_no=$all_detail['ambulance_list'][0]->ins_authorization_no;
            $template_data->template = str_replace( "{ins_authorization_no}", $ins_authorization_no, $template_data->template);
            // insurance Authorization no

        // Patient Fields ends here
    // added on 06-Feb-2018    




    $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);

    
    if(!empty($all_detail['ambulance_list'][0]->opd_type) && $all_detail['ambulance_list'][0]->opd_type=='1')
    {
        $opd_type = $charge_name = get_setting_value('DOCTOR_CHARGE_NAME');
    }
    else
    {
        $opd_type = 'Normal';
    }
    $template_data->template = str_replace("{opd_type}",$opd_type,$template_data->template);

    if(!empty($all_detail['ambulance_list'][0]->pannel_type) && $all_detail['ambulance_list'][0]->pannel_type=='1')
    {
        $pannel_type = 'Panel';
        
    }
    else
    {
        $pannel_type = 'Normal';
    }

      if(in_array('218',$users_data['permission']['section']))
      {

            if($all_detail['ambulance_list'][0]->paid_amount>0)
            {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['ambulance_list'][0]->reciept_prefix.$all_detail['ambulance_list'][0]->reciept_suffix,$template_data->template);
            }
      }
      else
      {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    
    $template_data->template = str_replace("{panel_type}",$pannel_type,$template_data->template);
    if(!empty($all_detail['ambulance_list'][0]->token_no))
    {
        $template_data->template = str_replace("{token_no}",$all_detail['ambulance_list'][0]->token_no,$template_data->template);    
    }
    else
    {
        $template_data->template = str_replace("{token_no}",'',$template_data->template);
        $template_data->template = str_replace("Token No.:",'',$template_data->template);
        $template_data->template = str_replace("Token No.",'',$template_data->template);
    }     

    if(!empty($all_detail['ambulance_list'][0]->next_app_date) && $all_detail['ambulance_list'][0]->next_app_date!='0000-00-00' && $all_detail['ambulance_list'][0]->next_app_date!='1970-01-01')
    {
        $next_app_date = date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->next_app_date));
    
        $next_appdate = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Next App. Date:</div>

            <div style="width:60%;line-height:17px;">'.$next_app_date.'</div>
            </div>';
        $template_data->template = str_replace("{next_app_date}",$next_appdate,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{next_app_date}",'',$template_data->template);
    }

    //$template_data->template = str_replace("{specialization}",get_specilization_name($all_detail['ambulance_list'][0]->specialization_id),$template_data->template);

    if(!empty($all_detail['ambulance_list'][0]->specialization_id))
    {
        $spec_name='';
        $specialization = get_specilization_name($all_detail['ambulance_list'][0]->specialization_id);
        if(!empty($specialization))
        {  
            $spec_name= str_replace('(Default)','',$specialization);
        }
        $specialization_new = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Spec. :</div>

            <div style="width:60%;line-height:17px;">'.$spec_name.'</div>
            </div>';
        $template_data->template = str_replace("{specialization}",$specialization_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{specialization}",'',$template_data->template);
    }

     


    if(!empty($all_detail['ambulance_list'][0]->attended_doctor))
    {
        $consultant_new = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Consultant :</div>

            <div style="width:60%;line-height:17px;">'.'Dr. '. get_doctor_name($all_detail['ambulance_list'][0]->attended_doctor).'</div>
            </div>';
        $template_data->template = str_replace("{Consultant}",$consultant_new,$template_data->template);
    }
    else
    {
         $template_data->template = str_replace("{Consultant}",'',$template_data->template);
    }


    
    
    $template_data->template = str_replace("{mobile_no}",$all_detail['ambulance_list'][0]->mobile_no,$template_data->template);

    if(!empty($all_detail['ambulance_list'][0]->booking_code))
    {
        $receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">OPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$all_detail['ambulance_list'][0]->booking_code.'</div>
            </div>';
        $template_data->template = str_replace("{booking_code}",$receipt_code,$template_data->template);
    }
        if(!empty($all_detail['ambulance_list'][0]->vehicle_no))
    {
        $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->vehicle_no,$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->driver_name))
    {
        $template_data->template = str_replace("{driver_name}",$all_detail['ambulance_list'][0]->driver_name,$template_data->template);
    }
    
        $template_data->template = str_replace("{location}",$all_detail['ambulance_list'][0]->location_name,$template_data->template);
    

    

    if(!empty($all_detail['ambulance_list'][0]->booking_date))
    {
        $template_data->template = str_replace("{booking_date}",date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->booking_date)),$template_data->template);
    }

    if(!empty($all_detail['ambulance_list'][0]->booking_code))
    {
        $template_data->template = str_replace("{booking_code}",$all_detail['ambulance_list'][0]->booking_code,$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->vehicle_no))
    {
        $template_data->template = str_replace("{vehicle_no}",$all_detail['ambulance_list'][0]->vehicle_no,$template_data->template);
    }
    if(!empty($all_detail['ambulance_list'][0]->driver_name))
    {
        $template_data->template = str_replace("{driver_name}",$all_detail['ambulance_list'][0]->driver_name,$template_data->template);
    }
     
        $template_data->template = str_replace("{location}",$all_detail['ambulance_list'][0]->location_name,$template_data->template);
   
    $genders = array('0'=>'F','1'=>'M');
    $gender = $genders[$all_detail['ambulance_list'][0]->gender];
    $age_y = $all_detail['ambulance_list'][0]->age_y; 
    $age_m = $all_detail['ambulance_list'][0]->age_m;
    $age_d = $all_detail['ambulance_list'][0]->age_d;

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
    
     /*payment Mode*/
     $pos_start1 = strpos($template_data->template, '{start_pay_loop}');
    $pos_end1 = strpos($template_data->template, '{end_pay_loop}');
    $row_last_length1 = $pos_end1-$pos_start1;
    $row_loop1 = substr($template_data->template,$pos_start1+12,$row_last_length1-12);
    $rplc_row = trim(substr($template_data->template,$pos_start1,$row_last_length1+10));
    /*payment Mode*/

    //////////////////////// 
    if(!empty($all_detail['ambulance_list']['particular_list']))
    {
    $i=1;
    $tr_html = "";
    foreach($all_detail['ambulance_list']['particular_list'] as $particular_list)
    { 
         $tr = $row_loop;
         $tr = str_replace("{s_no}",$i,$tr);
         
         $tr = str_replace("{particular}",$particular_list->particulars,$tr);
         $tr = str_replace("{quantity}",$particular_list->quantity,$tr);
         $tr = str_replace("{amount}",$particular_list->amount,$tr);
         $tr_html .= $tr;
         $i++;
    	 	 	 	
    }
        if(!empty($all_detail['ambulance_list'][0]->package_id))
        {
            $tr = $row_loop;
            $tr = str_replace("{s_no}",$i,$tr);
            $tr = str_replace("{particular}",$all_detail['ambulance_list'][0]->package_name . " <b>(1)</b>",$tr);
            $tr = str_replace("{quantity}",'',$tr);
            $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->package_amount,$tr);
            $tr_html .= $tr;
        }
    }
    else
    {	
         $charge_date= date('d-m-Y',strtotime($all_detail['ambulance_list'][0]->mod_date));
        $i=1;	
    	$tr_html = "";
    	$tr = $row_loop;
    	$tr = str_replace("{s_no}",$i,$tr);
    	$tr = str_replace("{particular}",'Ambulance Charges',$tr);
    	$tr = str_replace("{charge_date}",$charge_date,$tr);
    	 $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->consultant_charge,$tr);
        $tr_html .= $tr;

          if(!empty($particulars))
            {    foreach($particulars as $particular_list)
                { 
                     $i++;
                     $tr = $row_loop;
                     $tr = str_replace("{s_no}",$i,$tr);
                     
                     $tr = str_replace("{particular}",$particular_list->particulars,$tr);
                     $tr = str_replace("{charge_date}",' ',$tr);
                     $tr = str_replace("{quantity}",$particular_list->quantity,$tr);
                     $tr = str_replace("{amount}",$particular_list->amount,$tr);
                     $tr_html .= $tr;
                }
            }

        if(!empty($all_detail['ambulance_list'][0]->package_id))
        {
            $i++;
            $tr = $row_loop;
            $tr = str_replace("{s_no}",$i,$tr);
            $tr = str_replace("{particular}",$all_detail['ambulance_list'][0]->package_name . " <b>(1)</b>",$tr);
            $tr = str_replace("{charge_date}",'',$tr);
            $tr = str_replace("{quantity}",'',$tr);
            $tr = str_replace("{amount}",$all_detail['ambulance_list'][0]->package_amount,$tr);
            $tr_html .= $tr;
        }

    }
    
   // echo $tr_html; exit;
    
    if(!empty($refund_data))
    {
    
        $i=1;
        $ref_data = '<div style="float:left;width:100%;display:inline-flex;">
<div style="width:10%;font-weight:bold;padding-bottom:10px;">S.No.</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;">Payment Date</div>

<div style="width:50%;font-weight:bold;padding-bottom:10px;">Payment Mode</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;text-align:right;padding-right:1em;">Amount</div>
</div>';
        $new_total_refund = 0;
        foreach($refund_data as $refund_list)
        { 
            $new_total_refund =$new_total_refund+$refund_list['refund_amount'];
            if($refund_list['refund_amount']=='0')
            {
            $refund_amountt =  '0.00';
            }
            else
            {
            $refund_amountt =  number_format($refund_list['refund_amount'],2,'.','');
            }
            $ref_date= date('d-m-Y',strtotime($refund_list['refund_date']));
            $ref_data .='<div style="float:left;width:100%;display:inline-flex;">
            <div style="width:10%;line-height:17px;padding-left:15px;">'.$i.'</div>
            
            <div style="width:25%;line-height:17px;">'.$ref_date.'</div>
            
            <div style="width:50%;line-height:17px;">'.$refund_list['pay_mode'].'</div>
            
            <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">'.$refund_amountt.'</div> 
            </div>
        ';
        $i++;
            
        }
        
        $ref_data .='<div style="float:left;width:100%;display:inline-flex;">
            <div style="width:10%;line-height:17px;padding-left:15px;">&nbsp;</div>
            
            <div style="width:25%;line-height:17px;">&nbsp;</div>
            
            <div style="width:50%;line-height:17px;;border-top:1px solid #111;font-weight:bold;">Total: </div>
            
            <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;;border-top:1px solid #111;font-weight:bold;">'.number_format($new_total_refund,2, '.', '').'</div> 
            </div>
        ';
        
        
    }
    if(!empty($refund_data))
    {
        $template_data->template = str_replace("{refund_data}",$ref_data,$template_data->template);
    }
    else
    {
      $template_data->template = str_replace("{refund_data}","",$template_data->template);
      $template_data->template = str_replace("Refund Details","",$template_data->template);
      
    }
    if(!empty($all_detail['ambulance_list'][0]->discount))
            {
            $i=1;
            $disc_data = "";
               if($all_detail['ambulance_list'][0]->discount=='0')
            {
               $discount_amountt =  '0.00';
            }
            else
            {
              $discount_amountt =  number_format($all_detail['ambulance_list'][0]->discount,2,'.','');
            }
            $disc_date= date('Y-m-d',strtotime($all_detail['ambulance_list'][0]->created_date));
           
        $i++;}
   
    if($all_detail['ambulance_list'][0]->discount=='0'){
         $template_data->template = str_replace("{discount_data}","",$template_data->template);
       
   }else{
   
     $template_data->template = str_replace("{discount_data}",$disc_data,$template_data->template);
   }

    $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
    
    $template_data->template = str_replace("{sales_name}",ucfirst($all_detail['ambulance_list'][0]->user_name),$template_data->template);

    $template_data->template = str_replace("{total_discount}",$all_detail['ambulance_list'][0]->discount,$template_data->template);

    $template_data->template = str_replace("{net_amount}",$all_detail['ambulance_list'][0]->net_amount,$template_data->template);
    $template_data->template = str_replace("{total_amount}",$all_detail['ambulance_list'][0]->total_amount,$template_data->template);

    $template_data->template = str_replace("{paid_amount}",$all_detail['ambulance_list'][0]->paid_amount,$template_data->template);

    $template_data->template = str_replace("{gross_total_amount}",$all_detail['ambulance_list'][0]->total_amount,$template_data->template);

  

    
     if($all_detail['ambulance_list'][0]->refund_amount=='0')
    {
       $refund_amt =  '0.00';
    }
    else
    {
      $refund_amt =  number_format($all_detail['ambulance_list'][0]->total_refund,2,'.','');
    }
    $template_data->template = str_replace("{refund_amount}",number_format($refund_amt,2, '.', ''),$template_data->template);

 $card_no='';

    if(!empty($all_detail['ambulance_list'][0]->card_no))
    {
      $card_no = '('. $all_detail['ambulance_list'][0]->card_no.')';
    }
    
    /*Payment Mode */
         $total_paid_amount=0.00;
          if(!empty($payment_modes))
            {
              
            $i=1;
           
            $new_payment_amount=0;
            foreach($payment_modes as $mode)
            { 
                $new_payment_amount = $new_payment_amount+$mode['paid_amount'];
               if($mode['paid_amount']=='0')
                {
                   $payment_amountt =  '0.00';
                }
                else
                {
                  $payment_amountt =  number_format($mode['paid_amount'],2,'.','');
                }
    
                $total_paid_amount = $total_paid_amount+$mode['paid_amount'];
                 $payment_date= date('d-m-Y',strtotime($mode['created_date']));
              $pay_mode=$mode['pay_mode'];
               $pay_data .='<div style="float:left;width:100%;display:inline-flex;">
                <div style="width:10%;line-height:17px;padding-left:15px;">'.$i.'</div>
                <div style="width:25%;line-height:17px;">'.$payment_date.'</div>
                
                <div style="width:50%;line-height:17px;">'.$pay_mode.'</div>
                
                
                
                <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">'.$payment_amountt.'</div> 
                </div>
                ';
                $i++;
                
            }
            
                $pay_data .='<div style="float:left;width:100%;display:inline-flex;">
                <div style="width:10%;line-height:17px;padding-left:15px;">&nbsp;</div>
                <div style="width:25%;line-height:17px;">&nbsp;</div>
                
                <div style="width:50%;line-height:17px;;border-top:1px solid #111;font-weight:bold;">Total:</div>
                
                
                
                <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;;border-top:1px solid #111;font-weight:bold;">'.number_format($new_payment_amount,2, '.', '').'</div> 
                </div>
                ';
                
            }



    if(!empty($payment_modes))
    {
        $template_data->template = str_replace("{payment_data}",$pay_data,$template_data->template);
    }
    else{
        $template_data->template = str_replace("{payment_data}","",$template_data->template);
    }
   
   
   
   /*  refund   */

    if(!empty($refund_data))
    {
    
        $i=1;
        $ref_data = '<div style="float:left;width:100%;display:inline-flex;">
<div style="width:10%;font-weight:bold;padding-bottom:10px;">S.No.</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;">Payment Date</div>

<div style="width:50%;font-weight:bold;padding-bottom:10px;">Payment Mode</div>

<div style="width:25%;font-weight:bold;padding-bottom:10px;text-align:right;padding-right:1em;">Amount</div>
</div>';
        $new_total_refund=0;
        foreach($refund_data as $refund_list)
        { 
            $new_total_refund=$new_total_refund+$refund_list['refund_amount'];
            if($refund_list['refund_amount']=='0')
            {
            $refund_amountt =  '0.00';
            }
            else
            {
            $refund_amountt =  number_format($refund_list['refund_amount'],2,'.','');
            }
            $ref_date= date('d-m-Y',strtotime($refund_list['refund_date']));
            $ref_data .='<div style="float:left;width:100%;display:inline-flex;">
            <div style="width:10%;line-height:17px;padding-left:15px;">'.$i.'</div>
            
            <div style="width:25%;line-height:17px;">'.$ref_date.'</div>
            
            <div style="width:40%;line-height:17px;">'.$refund_list['pay_mode'].'</div>
            
            <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;">'.$refund_amountt.'</div> 
            </div>
        ';
        $i++;
            
        }
        
        $ref_data .='<div style="float:left;width:100%;display:inline-flex;">
            <div style="width:10%;line-height:17px;padding-left:15px;">&nbsp;</div>
            
            <div style="width:25%;line-height:17px;">&nbsp;</div>
            
            <div style="width:50%;line-height:17px;;border-top:1px solid #111;font-weight:bold;">Total: </div>
            
            <div style="width:25%;line-height:17px;text-align:right;padding-right:1em;;border-top:1px solid #111;font-weight:bold;">'.number_format($new_total_refund,2, '.', '').'</div> 
            </div>
        ';
        
    }
    if(!empty($refund_data))
    {
        $template_data->template = str_replace("{refund_data}",$ref_data,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{refund_data}","",$template_data->template);
        $template_data->template = str_replace("Refund Details","",$template_data->template);
    }

/* refund */
   
   
    
    
    /*if($all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount=='0')
    {
       $balance =  '0.00';
    }
    else
    {
      $balance =  number_format($all_detail['ambulance_list'][0]->net_amount-$all_detail['ambulance_list'][0]->paid_amount,2,'.','');
    }*/
    $balance_data = $all_detail['ambulance_list'][0]->net_amount-$total_paid_amount;
    /*if($balance_data>0)
    {
        $balance_data=number_format($balance_data,2);
    }
    else
    {
        $balance_data='0.00';
    }*/
    
     /*if($balance_data>0)
    {
        $balance_data=number_format($balance_data,2);
    }else
    {*/
          $balance_data = $balance_data+$all_detail['ambulance_list'][0]->refund_amount;  
    //}
    $template_data->template = str_replace("{balance}",number_format($balance_data,2, '.', ''),$template_data->template);

    if(!empty($all_detail['ambulance_list'][0]->remarks))
    {
       $template_data->template = str_replace("{remarks}",$all_detail['ambulance_list'][0]->remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{remarks}",' ',$template_data->template);
       $template_data->template = str_replace("Remarks:",' ',$template_data->template);
        $template_data->template = str_replace("Remarks :",' ',$template_data->template);
       $template_data->template = str_replace("Remarks",' ',$template_data->template);
      
    }
    /*$template_data->template = str_replace("{total_paid_amount}",number_format(($total_paid_amount-$new_total_refund),2, '.', ''),$template_data->template);*/
    $template_data->template = str_replace("{total_paid_amount}",number_format(($total_paid_amount),2, '.', ''),$template_data->template);
    $this->session->unset_userdata('opd_booking_id');
    echo $template_data->template; 
}
 
/* end leaser printing*/
?>

