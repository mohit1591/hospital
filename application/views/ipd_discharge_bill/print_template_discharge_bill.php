<?php 
 $user_detail = $this->session->userdata('auth_users');
 
    if(empty($address_setting_list))
    {
        $address = $get_ipd_patient_details['address'];
        //$all_detail['ipd_list'][0]->address;
        $pincode = $get_ipd_patient_details['pincode'];
        //$all_detail['ipd_list'][0]->pincode;    
        $country = $get_ipd_patient_details['country_name'];
        
        //$all_detail['ipd_list'][0]->country_name;    
        $state = $get_ipd_patient_details['state_name'];
        //$all_detail['ipd_list'][0]->state_name;    
        $city = $get_ipd_patient_details['city_name'];
        //$all_detail['ipd_list'][0]->city_name;    
        $patient_address = $address.' '.$country.','.$state.' '.$city.' - '.$pincode;
        $template_data->setting_value = str_replace("{patient_address}",$patient_address,$template_data->setting_value);
    }
    else
    {
        $address='';
        if($address_setting_list[0]->address1)
        {
           $address .= $get_ipd_patient_details['address1'].' '; 
        }
        if($address_setting_list[0]->address2)
        {
           $address .= $get_ipd_patient_details['address2'].' '; 
        }
       
        if($address_setting_list[0]->address3)
        {
           $address .=  $get_ipd_patient_details['address3'].' '; 
        }
      
        if($address_setting_list[0]->city)
        {
           $address .=  $get_ipd_patient_details['city_name'].' ';  //$all_detail['ipd_list'][0]->city_name.' '; 
        }
       
        if($address_setting_list[0]->state)
        {
           $address .= $get_ipd_patient_details['state_name'].' '; 
           //$all_detail['ipd_list'][0]->state_name.' '; 
        }
        if($address_setting_list[0]->country)
        {
           $address .=  $get_ipd_patient_details['country_name'].' '; 
           //$all_detail['ipd_list'][0]->country_name.' '; 
        }
        if($address_setting_list[0]->pincode)
        {
           $address .= $get_ipd_patient_details['pincode'];
           //$all_detail['ipd_list'][0]->pincode; 
        }
       $template_data->setting_value = str_replace("{patient_address}",$address,$template_data->setting_value);
    }
    
    $admission_time='';
    if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
    {
    $admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
    }
    
    $template_data->setting_value = str_replace("{admission_time}",$admission_time,$template_data->setting_value);
    
    if($get_ipd_patient_details['discharge_date']=="0000-00-00" || $get_ipd_patient_details['discharge_date']=='0000-00-00 00:00:00')
    {
    $discharge_time = date('h:i A', strtotime($get_ipd_patient_details['discharge_date'])); 
    }
    $template_data->setting_value = str_replace("{discharge_time}",$discharge_time,$template_data->setting_value);
    
    
    
   
    $admission_time='';
    if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
    {
        $admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
    }

     
    if(!empty($time_setting[0]->ipd) && !empty($time_setting[0]->ipd))
    {
        // admission date time
         $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).' '.$admission_time,$template_data->setting_value);  
        // admission date time
        //discharge date 
         $template_data->setting_value = str_replace("{discharge_date}",date('d-m-Y h:i A',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value);
        //discharge date 
    }
    else
    {
        // admission date time
        $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$template_data->setting_value);
       // admission date time
       
       //discharge date 
        $template_data->setting_value = str_replace("{discharge_date}",date('d-m-Y',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value); 
       //end of discharge date 
       
    }
  //for date and time
    $template_data->setting_value = str_replace("{discharge_date_time}",date('d-m-Y h:i A',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value);
          
 $payment_mode="";
 $disc_payment_mode = $get_ipd_patient_details['disc_payment_mode']; 
 $card_no='';
if(!empty($get_ipd_patient_details['card_no']))
{
  $card_no = ' ('. $get_ipd_patient_details['card_no'].')';
}

 $payment_mode="";

$disc_payment_mode = $get_ipd_patient_details['disc_payment_mode']; 

if(!empty($disc_payment_mode))
{
    $payment_mode = $disc_payment_mode.$card_no; 
}
else
{
    $payment_mode=$get_ipd_patient_details['payment_mode'].$card_no;
   
}
  $template_data->setting_value = str_replace("{payment_mode}",$payment_mode,$template_data->setting_value);
  $template_data->setting_value = str_replace("{bank_name}",$get_ipd_patient_details['bank_name'],$template_data->setting_value);

  $template_data->setting_value = str_replace("{transaction_no}",$get_ipd_patient_details['transaction_no'],$template_data->setting_value);
  if($get_ipd_patient_details['cheque_date']=="0000-00-00")
  {
    $template_data->setting_value = str_replace("{transaction_date}",'',$template_data->setting_value);
  }
  else
  {

     $template_data->setting_value = str_replace("{transaction_date}",date('d-m-Y',strtotime($get_ipd_patient_details['cheque_date'])),$template_data->setting_value);
  }

    
  
  
  
  $admission_time='';
        if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
        {
            $admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
        }

        
        $template_data->setting_value = str_replace("{booking_date_time}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).' '.$admission_time,$template_data->setting_value);
        
        
  
  
  //insurance_type
        $insurance_type= ucwords($get_ipd_patient_details['insurance_type']);
        $template_data->setting_value = str_replace("{insurance_type}", $insurance_type, $template_data->setting_value);  
        //insurance_type

        //insurance_type_name
        $insurance_type_name= ucwords($get_ipd_patient_details['insurance_type_name']);
        $template_data->setting_value = str_replace("{insurance_type_name}", $insurance_type_name, $template_data->setting_value);  
        //insurance_type_name

        //insurance_company_name
        $insurance_company_name= ucwords($get_ipd_patient_details['insurance_company_name']);
        $template_data->setting_value = str_replace("{insurance_company_name}", $insurance_company_name, $template_data->setting_value);  
        //insurance_company_name

        //insurance_policy_no
        $insurance_policy_no= ucwords($get_ipd_patient_details['insurance_policy_no']);
        $template_data->setting_value = str_replace("{insurance_policy_no}", $insurance_policy_no, $template_data->setting_value);  
  
  
  if(!empty($get_ipd_patient_details['discharge_remarks']))
    {
       $template_data->setting_value = str_replace("{remarks}",$get_ipd_patient_details['discharge_remarks'],$template_data->setting_value);
    }
    else
    {
       $template_data->setting_value = str_replace("{remarks}",' ',$template_data->setting_value);
       $template_data->setting_value = str_replace("Remarks :",' ',$template_data->setting_value);
       
    }
    
    
  if(!empty($get_ipd_patient_details['relation']))
    {
    
        $template_data->setting_value = str_replace("{parent_relation_type}",$get_ipd_patient_details['relation'],$template_data->setting_value);
    }
    else
    {
        $template_data->setting_value = str_replace("{parent_relation_type}",'',$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['relation_name']))
    {
        $rel_simulation = get_simulation_name($get_ipd_patient_details['relation_simulation_id']);
        $template_data->setting_value = str_replace("{parent_relation_name}",$rel_simulation.' '.$get_ipd_patient_details['relation_name'],$template_data->setting_value);
    }
    else
    {
        $template_data->setting_value = str_replace("{parent_relation_name}",'',$template_data->setting_value);
    }
        

    $simulation = get_simulation_name($get_ipd_patient_details['simulation_id'])
    ;
    
    $template_data->setting_value = str_replace("{patient_name}",$simulation.' '.$get_ipd_patient_details['patient_name'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{patient_reg_no}",$get_ipd_patient_details['patient_code'],$template_data->setting_value);
    
    $template_data->setting_value = str_replace("{room_type}",$get_ipd_patient_details['room_category'],$template_data->setting_value);
    /* $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$template_data->setting_value);*/
     
     
     if($template_data->date_time_formate==1)
      {
        $admission_time='';
        if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
        {
            $admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
        }

        
        $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).' '.$admission_time,$template_data->setting_value);

      }
      else
      {
        $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$template_data->setting_value);
      }
    

    $tot_amount='<div style="width:100%;border-top:1px solid #111;">
                        <div style="float:left;font-weight:bold;">Total Amount:</div>
                        <div style="float:right;font-weight:bold;">'.$get_ipd_patient_details['total_amount_dis_bill'].'</div>
                    </div>';
   
  
    $bill_discount = $get_ipd_patient_details['discount_amount_dis_bill'];  
    
    $new_paid = $total_paid_amount+$get_ipd_patient_details['advance_payment_dis_bill'];
   if($get_ipd_patient_details['total_amount_dis_bill'] > $new_paid)
   {
    $balance_final = ($get_ipd_patient_details['total_amount_dis_bill']-$new_paid)-$bill_discount;
    if($balance_final>0)
    {
        $balance_final = number_format($balance_final,2); 
        $refund_final ='0.00';
    }
    else
    {
        
        $refund_final =number_format(abs($balance_final),2); 
        $balance_final = '0.00';
    }
    
   }
   else
   {
        $refund_final = ($new_paid-$get_ipd_patient_details['total_amount_dis_bill'])+$bill_discount;
        $refund_final = number_format($refund_final,2);
        $balance_final ='0.00';
   }

   $paid_amount_final = $total_paid_amount;
    $template_data->setting_value = str_replace("{total_amount}",$tot_amount,$template_data->setting_value);
    
    $template_data->setting_value = str_replace("{amount}",$get_ipd_patient_details['total_amount_dis_bill'],$template_data->setting_value);
    
    
    $template_data->setting_value = str_replace("{net_amount}",$get_ipd_patient_details['net_amount_dis_bill'],$template_data->setting_value);
    
    

   $template_data->setting_value = str_replace("{advance_amount}",$get_ipd_patient_details['advance_payment_dis_bill'],$template_data->setting_value);
     $template_data->setting_value = str_replace("{discount}",$get_ipd_patient_details['discount_amount_dis_bill'],$template_data->setting_value);

if(!empty($get_ipd_patient_details['paid_amount_dis_bill']))
{
$paid_amount_dis_bill = $get_ipd_patient_details['paid_amount_dis_bill'];
}
else
{
$paid_amount_dis_bill = '0.00';
}
//echo $paid_amount_dis_bill;
 //$paid_amount_dis_bill //on 24 sep balance_final
     $template_data->setting_value = str_replace("{received}",$paid_amount_final,$template_data->setting_value); //total_amount_dis_bill 02-12-2017

     //$get_ipd_patient_details['refund_amount_dis_bill']
     $template_data->setting_value = str_replace("{refund}",$refund_final,$template_data->setting_value);
     if($get_ipd_patient_details['balance_amount_dis_bill'] < 0)
     {
        $paid_balance = '0.00';
     }
     else
     {
        $paid_balance = $get_ipd_patient_details['balance_amount_dis_bill'];
     }
     
         $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
        $template_data->setting_value = str_replace("{specialization}",$get_ipd_patient_details['specialization'],$template_data->setting_value);
  

    if(!empty($get_ipd_patient_details['doctor_name']))
    {
        $template_data->setting_value = str_replace("{consultant}",'Dr. '.$get_ipd_patient_details['doctor_name'],$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{consultant}",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("Consultant:",'',$template_data->setting_value);
    }
    
    $template_data->setting_value = str_replace("{bill_no}",$get_ipd_patient_details['discharge_bill_no'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{mobile_no}",$get_ipd_patient_details['mobile_no'],$template_data->setting_value);


    if(!empty($get_ipd_patient_details['ipd_no']))
    {
        /*$receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['ipd_no'].'</div>
            </div>';*/
        $template_data->setting_value = str_replace("{ipd_no}",$get_ipd_patient_details['ipd_no'],$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['admission_date']))
    {
        $booking_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD Reg. Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).'</div>
            </div>';
        $template_data->setting_value = str_replace("{booking_date}",$booking_date,$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['created_date']))
    {
        $receipt_date = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Receipt Date:</div>

            <div style="width:60%;line-height:19px;">'.date('d-m-Y h:i A',strtotime($get_ipd_patient_details['created_date'])).'</div>
            </div>';
        $template_data->setting_value = str_replace("{receipt_date}",$receipt_date,$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['room_no']))
    {
        $room_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['room_no'].'</div>
            </div>';
        $template_data->setting_value = str_replace("{room_no}",$room_no,$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{room_no}",'',$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['bad_name']))
    {
        $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['bad_name'].'</div>
            </div>';
        $template_data->setting_value = str_replace("{bed_no}",$bed_no,$template_data->setting_value);
    }
    else if(!empty($get_ipd_patient_details['bad_no']))
    {
        $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['bad_no'].'</div>
            </div>';
        $template_data->setting_value = str_replace("{bed_no}",$bed_no,$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{bed_no}",'',$template_data->setting_value);
    }

    if(!empty($get_ipd_patient_details['mlc']) && $get_ipd_patient_details['mlc']==1)
    {
        $mlc = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">MLC:</div>

            <div style="width:60%;line-height:19px;">Yes</div>
            </div>';
        $template_data->setting_value = str_replace("{mlc}",$mlc,$template_data->setting_value);
    }
    else
    {
         
        $template_data->setting_value = str_replace("{mlc}",'',$template_data->setting_value);
        $template_data->setting_value = str_replace("MLC:",' ',$template_data->setting_value);
        $template_data->setting_value = str_replace("MLC :",' ',$template_data->setting_value);
        $template_data->setting_value = str_replace("MLC",' ',$template_data->setting_value);
    }
    
            $table_data='<div style="float:left;width:100%;margin-top:20px;">
                                <div style="float:left;width:100%;display:inline-flex;">
                                <div style="width:10%;font-weight:bold;padding-bottom:10px;padding-left:5px;">Sr. No.</div>
                                <div style="width:30%;font-weight:bold;padding-bottom:10px;">Particulars</div>
                                <div style="width:30%;font-weight:bold;padding-bottom:10px;">Date</div>
                                <div style="width:10%;font-weight:bold;padding-bottom:10px;">Qty</div>
                                <div style="width:10%;font-weight:bold;padding-bottom:10px;">Rate</div>
                                <div style="width:10%;font-weight:bold;padding-bottom:10px;">Amount</div>
                                </div>';
                                //echo "<pre>"; print_r($all_detail['CHARGES']);

                        $i=1;
                        $heading_of_particular='';
                        $array_data=array();
                        $total_amount=0;
                        $v=1;
                        $type_one = 0;
                        $type_two = 0;
                        $perticuler_charge = [];
                        
                        
                        
                        if(!empty($all_detail['CHARGES'])){
                        foreach($all_detail['CHARGES'] as  $details_data)
                        {  
                            
                            $table_data.='';
                            if($details_data['type']==1 && $type_one==0 && $details_data['type']!=5)
                            {
                                $table_data.='<div style="float:left;width:100%;padding:4px;"><span style="border-bottom:1px solid #111;font-weight:bold;">Registration Charge</span> </div>';  
                                $type_one = 1;
                            }
                            else if(($details_data['type']==3 || $details_data['type']==5) && $type_two==0)
                            {
                                $i=1;
                               $heading="Particulars charge";
                                $table_data.='<div style="float:left;width:100%;padding:4px;"><span style="border-bottom:1px solid #111;font-weight:bold;">'.$heading.'</span> </div>';
                                $type_two = 1;

                            }
                            
                            if(!empty($details_data['room_category']) && $template_data->display_room==1)
                            {
                              $room_category = ' ('.$details_data['room_category'].')';
                            }
                            else
                            {
                              $room_category='';
                            }
                            
                            if(!empty($details_data['doctor_name']))
                            {
                              $doctor_name = ' ('.$details_data['doctor_name'].')';
                            }
                            else
                            {
                              $doctor_name='';
                            }
                        
                        $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$i.'</div>
                                            <div style="width:30%;line-height:17px;">'.$details_data['particular'].$room_category.$doctor_name.'</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($details_data['start_date'])).'</div>
                                            <div style="width:10%;line-height:17px;">'.$details_data['quantity'].'</div>
                                            <div style="width:10%;line-height:17px;">'.$details_data['price'].'</div>
                                            <div style="width:10%;line-height:17px;">'.$details_data['net_price'].'</div>
                                        </div>
                                 </div>';
                                $i ++; 
                                
                                
                        //$j++;
                                $total_amount=$total_amount+$details_data['net_price'];
                        }
                        } 

                    //echo $total_amount;
                    $k=1;
                    $medi_type=0;
                    if(!empty($all_detail['medicine_payment']))
                    {
                            $net_medicine_payment_data=array();
                            foreach($all_detail['medicine_payment'] as $payment )
                            {
                            if($medi_type ==0)
                            {
                                $heading="Medicine Charge";
                                $table_data.='<div style="float:left;width:100%;padding:4px;"><span style="border-bottom:1px solid #111;font-weight:bold;">'.$heading.'</span> </div>';
                                $medi_type = 1;
                            }
                            $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$k.'</div>
                                            <div style="width:30%;line-height:17px;">'.$payment->particular.'</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($payment->start_date)).'</div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                            </div>
                            </div>';
                            $k ++; 
                            $net_medicine_payment_data[]= $payment->net_price;
                            }
                    }


                    $k=1;
                    $pathology_type=0;
                    if(!empty($all_detail['pathology_payment']))
                    {
                            $net_pathology_payment_data=array();
                            foreach($all_detail['pathology_payment'] as $payment )
                            {
                            if($pathology_type ==0)
                            {
                                $heading="Pathology Test";
                                $table_data.='<div style="float:left;width:100%;padding:4px;"><span style="border-bottom:1px solid #111;font-weight:bold;">'.$heading.'</span> </div>';
                                $pathology_type = 1;
                            }
                            $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$k.'</div>
                                            <div style="width:30%;line-height:17px;">'.$payment->particular.'</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($payment->start_date)).'</div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                            </div>
                            </div>';
                            $k ++; 
                            $net_pathology_payment_data[]= $payment->net_price;
                            
                            $total_amount=$total_amount+$payment->net_price;
                            }
                    }

                        if(isset($total_amount) && isset($net_medicine_payment_data[0]))
                        {
                                 $balance= $total_amount-$net_medicine_payment_data[0];
                        }
                        
                        
                    /*$s=1; //5 august 2020
                    if(!empty($all_detail['advance_payment']))
                    {
                            $net_advance_data=array();
                            foreach($all_detail['advance_payment'] as $payment )
                            {
                            
                            if($s==1)
                            {
                                $table_data.='<div style="float:left;width:100%;padding:4px;">';
                            $table_data.='<span style="border-bottom:1px solid #111;font-weight:bold;">Advance Payment</span> </div>'; 
                            }
                            $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$s.'</div>
                                            <div style="width:30%;line-height:17px;">'.$payment->particular.'</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($payment->start_date)).'</div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                            </div>
                            </div>';
                            $s ++; 
                            $net_advance_data[]= $payment->net_price;
                            }
                    }*/
                    
                    if(!empty($all_detail['advance_payment']))
                    {
                            $net_advance_data=array();
                            foreach($all_detail['advance_payment'] as $payment )
                            {
                            
                            /*if($s==1)
                            {
                                $table_data.='<div style="float:left;width:100%;padding:4px;">';
                            $table_data.='<span style="border-bottom:1px solid #111;font-weight:bold;">Advance Payment</span> </div>'; 
                            }
                            $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$s.'</div>
                                            <div style="width:30%;line-height:17px;">'.$payment->particular.'</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($payment->start_date)).'</div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                            </div>
                            </div>';
                            $s ++; */
                            $net_advance_data[]= $payment->net_price;
                            }
                    }
                    $s=1;
                    if(!empty($all_detail['row_wise_advance_payment']))
                    {
                            //$row_wise_advance_payment=0;
                            
                            foreach($all_detail['row_wise_advance_payment'] as $payment )
                            {
                            
                            if($s==1)
                            {
                                $table_data.='<div style="float:left;width:100%;padding:4px;">';
                            $table_data.='<span style="border-bottom:1px solid #111;font-weight:bold;">Advance Payment</span> </div>'; 
                            }
                            $table_data.='<div style="float:left;width:100%;display:inline-flex;font-weight:bold;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$s.'</div>
                                            <div style="width:30%;line-height:17px;">'.$payment->particular.' ('.$payment->reciept_prefix.
$payment->reciept_suffix.')</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($payment->start_date)).'</div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;"></div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                            </div>
                            </div>';
                            $s ++; 
                            //$row_wise_advance_payment= $row_wise_advance_payment+$payment->net_price;
                            }
                    }

                   
                    
    if(isset($total_amount) && (isset($net_advance_data[0]) || isset($paid_amount_dis_bill) ) )
    {
       
              $balance= $total_amount-$net_advance_data[0]-$paid_amount_dis_bill;
             if(!empty($get_ipd_patient_details['discount_amount_dis_bill']) && isset($get_ipd_patient_details['discount_amount_dis_bill']))
             {
               $balance= $balance-$get_ipd_patient_details['discount_amount_dis_bill'];
             }
             if($balance>0)
             {
                $balance = number_format($balance,2);    
             }
             else
             {
                 $balance = '0.00';
             }
             
    }
    else
    {
             $balance='0.00';
    }

                    
                 //print_r(array_unique($array_data));
                    
    $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
   //echo $total_amount;
    $template_data->setting_value = str_replace("{received_amount}",$total_amount,$template_data->setting_value);
    //$balance
   // echo $balance_final;
   $template_data->setting_value = str_replace("{balance}",$balance_final,$template_data->setting_value);
   
   
   if(!empty($get_ipd_patient_details['user_name_disch']))
   {
       $sig = $get_ipd_patient_details['user_name_disch'];
   }
   else
   {
        $sig = $get_ipd_patient_details['user_name'];
   }
  
    $template_data->setting_value = str_replace("{signature}",ucfirst($sig),$template_data->setting_value);
    $genders = array('0'=>'F','1'=>'M');
    $gender = $genders[$get_ipd_patient_details['gender']];
    $age_y = $get_ipd_patient_details['age_y']; 
    $age_m = $get_ipd_patient_details['age_m'];
    $age_d = $get_ipd_patient_details['age_d'];

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

    $template_data->setting_value = str_replace("{patient_age}",$gender_age,$template_data->setting_value);

    $template_data->setting_value = str_replace("{Quantity_level}",'',$template_data->setting_value);

$admission_time='';
if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
{
$admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
}

$template_data->setting_value = str_replace("{admission_time}",$admission_time,$template_data->setting_value);

if($get_ipd_patient_details['discharge_date']=="0000-00-00" || $get_ipd_patient_details['discharge_date']=='0000-00-00 00:00:00')
{
$discharge_time = date('h:i A', strtotime($get_ipd_patient_details['discharge_date'])); 
}
$template_data->setting_value = str_replace("{discharge_time}",$discharge_time,$template_data->setting_value);
    echo $template_data->setting_value; 


/* end leaser printing*/
?>