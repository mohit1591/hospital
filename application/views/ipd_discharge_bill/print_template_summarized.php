<?php 
//echo $template_data->setting_value;
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
 $payment_mode="";
//$payment_mode=$get_ipd_patient_details['payment_mode'];

$disc_payment_mode = $get_ipd_patient_details['disc_payment_mode']; 

if(!empty($disc_payment_mode))
{
    $payment_mode = $disc_payment_mode; 
}
else
{
    $payment_mode=$get_ipd_patient_details['payment_mode'];
   
}

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
        
  $template_data->setting_value = str_replace("{discharge_date_time}",date('d-m-Y h:i A',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value);
  
  
  $admission_time='';
        if(!empty($get_ipd_patient_details['admission_time']) && $get_ipd_patient_details['admission_time']!='00:00:00' && strtotime($get_ipd_patient_details['admission_time'])>0)
        {
            $admission_time = date('h:i A', strtotime($get_ipd_patient_details['admission_time'])); 
        }

        
        $template_data->setting_value = str_replace("{booking_date_time}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).' '.$admission_time,$template_data->setting_value);

  $template_data->setting_value = str_replace("{bank_name}",$get_ipd_patient_details['bank_name'],$template_data->setting_value);
  $template_data->setting_value = str_replace("Payment Mode :",'',$template_data->setting_value);

  $template_data->setting_value = str_replace("{transaction_no}",$get_ipd_patient_details['transaction_no'],$template_data->setting_value);
  if($get_ipd_patient_details['cheque_date']=="0000-00-00")
  {
    $template_data->setting_value = str_replace("{transaction_date}",'',$template_data->setting_value);
  }
  else
  {

     $template_data->setting_value = str_replace("{transaction_date}",date('d-m-Y',strtotime($get_ipd_patient_details['cheque_date'])),$template_data->setting_value);
  }
  //$template_data->setting_value = str_replace("{discharge_date}",date('d-m-Y',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value);

    if($get_ipd_patient_details['discharge_date']=="0000-00-00" || $get_ipd_patient_details['discharge_date']=='0000-00-00 00:00:00')
  {
    $template_data->setting_value = str_replace("{discharge_date}",'',$template_data->setting_value);
  }
  else
  {
    $template_data->setting_value = str_replace("{discharge_date}",date('d-m-Y',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value);
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
    $template_data->setting_value = str_replace("{patient_name}",$simulation.''.$get_ipd_patient_details['patient_name'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{patient_reg_no}",$get_ipd_patient_details['patient_code'],$template_data->setting_value);
    $address = $get_ipd_patient_details['address'];
    $pincode = $get_ipd_patient_details['pincode'];         
    
    $patient_address = $address.' - '.$pincode;

    $template_data->setting_value = str_replace("{patient_address}",$patient_address,$template_data->setting_value);
    $template_data->setting_value = str_replace("{room_type}",$get_ipd_patient_details['room_category'],$template_data->setting_value);
            $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$template_data->setting_value);
            $template_data->setting_value = str_replace("Total Amount:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{total_amount}",'',$template_data->setting_value);
            //$template_data->setting_value = str_replace("Advance Amount:",'',$template_data->setting_value);
           // $template_data->setting_value = str_replace("{advance_amount}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Discount:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{discount}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Advance Amount:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{advance_amount}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{discount}",'',$template_data->setting_value);

            $template_data->setting_value = str_replace("Received:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{received}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{refund}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Refund:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Balance:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{balance}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{signature}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Signature",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{payment_mode}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Net Amount:",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Remarks:",'',$template_data->setting_value);
            

            
            $template_data->setting_value = str_replace("{amount}",'',$template_data->setting_value);
            
            $template_data->setting_value = str_replace("Discount",'',$template_data->setting_value);
            
            $template_data->setting_value = str_replace("Final Amount",'',$template_data->setting_value);
            
            $template_data->setting_value = str_replace("Due / Balance",'',$template_data->setting_value);
            
            
            $template_data->setting_value = str_replace("Received",'',$template_data->setting_value);
            
            $template_data->setting_value = str_replace("{net_amount}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("Refund",'',$template_data->setting_value);
            
            $template_data->setting_value = str_replace("Advance",'',$template_data->setting_value);
            
            $template_data->setting_value = str_replace("Total",'',$template_data->setting_value);
            
            

            
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

            <div style="width:60%;line-height:19px;">'.date('d-m-Y',strtotime($get_ipd_patient_details['created_date'])).'</div>
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
    }else if(!empty($get_ipd_patient_details['bad_no']))
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
    
            $table_data='<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><tr> <th>Sr. No.</th> <th align="left" style="padding: 4px;">Particulars</th><th>Qty</th><th align="right" style="padding: 4px;">Rate</th><th align="right" width="100" style="padding: 4px;">Amount</th> </tr>
                            

                            ';
                            
                            //echo "<pre>"; print_r($all_detail['CHARGES']); exit;
                        //array_column($all_detail['CHARGES'],'particular');
                        $i=1;
                        $heading_of_particular='';
                        $actual_payment_data=array();
                        $v=1;
                        $perticuler_charge = [];
                        $i=1;
                        $array_data=array();
                        $total_amount='';
                        $v=1;
                        $type_one = 0;
                        $type_two = 0;
                        $perticuler_charge = [];
                        $new_arr=array();
                        $uni_arr = [];
                        $new_array=array();
                        $doctor_visit_arr = [];
                        foreach($all_detail['CHARGES'] as $charges)
                        { 
                           if($charges['particular']=='Doctor Visit')
                           {
                               $doctor_visit_arr[] = $charges;
                           }
                           else
                           {
                           $perticuler_charge[] = array('particular'=>trim($charges['particular']),'start_date'=>$charges['start_date'],'price'=>$charges['price'],'quantity'=>$charges['quantity'],'net_price'=>$charges['net_price'],'type'=>$charges['type']);
                           }
                        } 
 
                        $unique_arr = array_unique(array_column($perticuler_charge,'particular'));
                       //print '<pre>'; print_r($doctor_visit_arr);die;
                       $doctor_perticuller = [];
                       if(!empty($doctor_visit_arr))
                       {
                           
                           foreach($doctor_visit_arr as $doc_key=>$doctor_visit)
                           { 
                               if(array_key_exists ($doctor_visit['price'], $doctor_perticuller))
                               {  
                                  $total_dv_qty = $doctor_perticuller[$doctor_visit['price']]['qty']+1;
                                  $total_price = $doctor_visit['price']*$total_dv_qty;
                                  $doctor_perticuller[$doctor_visit['price']] = array('qty'=>$total_dv_qty,'price'=>$doctor_visit['price'],'particular'=>$doctor_visit['particular'],'total_price'=>$total_price);
                                   
                                  
                               }
                               else
                               {
                                  // echo $doctor_perticuller[$doctor_visit['price']]['price'];
                                  $doctor_perticuller[$doctor_visit['price']] = array('qty'=>$doctor_visit['quantity'],'price'=>$doctor_visit['price'],'particular'=>$doctor_visit['particular'],'total_price'=>$doctor_visit['net_price']);
                               } 
                           }  
                       }
                       
                        foreach($unique_arr as $unique)
                        {
                            $uni_arr[str_replace(" ","",$unique)] = array('particular'=>$unique,'start_date'=>'','price'=>0,'quantity'=>0,'net_price'=>0,'type'=>0);
                        }
                        
                        $array_final_perticuler = [];

                        $column_all = array_column($perticuler_charge,'particular');
                        $unique_perticuller = array_unique($column_all);
                        
                        $column_all_price = array_column($perticuler_charge,'price');
                        $unique_perticuller_price = array_unique($column_all_price);
                        
                         //print '<pre>'; print_r($unique_perticuller_price);die;
                        $summurise_arr = [];
                        $i=1;
                        //print '<pre>'; print_r($perticuler_charge);die; 
                        foreach($perticuler_charge as $final_charge)
                        {  
                           //print '<pre>'; print_r($unique_perticuller);die; 
                           if(!empty($unique_perticuller))
                            { 
                                foreach($unique_perticuller as $uni_per)
                                { 
                                    if(trim($final_charge['particular'])==trim($uni_per))
                                    {  
                                          if(isset($summurise_arr[$final_charge['particular']]))
                                          {
                                              foreach($unique_perticuller_price as $erticuller_price)
                                              {
                                              }
                                              $summurise_arr[$final_charge['particular']] = array('particular'=>$final_charge['particular'],'type'=>$final_charge['type'], 'quantity'=>$summurise_arr[$uni_per]['quantity']+$final_charge['quantity'], 'price'=>$final_charge['price'], 'net_price'=>$summurise_arr[$uni_per]['net_price']+$final_charge['net_price']);
                                              
                                          } 
                                          else
                                          {
                                              $summurise_arr[$final_charge['particular']] = array('particular'=>$final_charge['particular'],'type'=>$final_charge['type'], 'quantity'=>$final_charge['quantity'], 'price'=>$final_charge['price'], 'net_price'=>$final_charge['net_price']);
                                          } 
                                       
                                    }  
                                }
                                
 
                            }  
                            $i++;
                            
                          
                        } 
                     
                        $arr=array();
                        $p_quantity=0;
                        $p_price=0;
                        $p_net_price=0;
                       //echo "<pre>"; print_r($summurise_arr); exit;
                       ///// Doctor visit start ////
                       
                       //
                       /*[qty] => 4
            [price] => 1000.00
            [particular] => Doctor Visit
            [total_price] => 4000*/
                      //echo "<pre>"; print_r($doctor_perticuller); die;
                       $i=1; 
                       if(!empty($doctor_perticuller))
                       {
                           foreach($doctor_perticuller as $doc_part)
                           {
                               
                               $total_amount=$total_amount+$doc_part['price'];
                                $table_data.=' <tr> <th>'.$i.'</th> <th align="left" style="padding: 4px;">'.$doc_part['particular'].'</th><th>'.$doc_part['qty'].'</th><th align="right" style="padding: 4px;">'.$doc_part['price'].'</th><th align="right" width="100" style="padding: 4px;">'.$doc_part['total_price'].'</th> </tr>';  
                                $i++;  
                           }
                       }
                       //// Doctor visit end ////
                        if(!empty($summurise_arr))
                        {
                        $i=$i;    
                        foreach($summurise_arr as $details_data)
                        {  

                             $table_data.='';
                          
                           
                          if($details_data['type']==5)
                          {
                            $arr[]=$details_data['type'];
                            $p_quantity= $p_quantity+$details_data['quantity'];
                            $p_price=$p_price+$details_data['price'];
                            $p_net_price=$p_net_price+$details_data['net_price'];
                          }
                         // if($details_data['type']!=5)
                          //{
                           $table_data.=' <tr> <th>'.$i.'</th> <th align="left" style="padding: 4px;">'.$details_data['particular'].'</th><th>'.$details_data['quantity'].'</th><th align="right" style="padding: 4px;">'.$details_data['price'].'</th><th align="right" width="100" style="padding: 4px;">'.$details_data['net_price'].'</th> </tr>'; 
                        //  }
                      
                                $i ++; 
                                
                                
                        //$j++;
                        $total_amount=$total_amount+$details_data['net_price'];
                        }
                        }
                        
                   /* if(isset($arr))
                    {
                    $new_p_c=array_unique($arr);
                    }
                    if($new_p_c[0]==5)
                    {
                    $table_data.=' <tr> <th>'.$i.'</th> <th>Particular Charges</th><th>'.$p_quantity.'</th><th>'. $p_price.'</th><th align="right" width="100" style="padding: 4px;">'.$p_net_price.'</th> </tr>';
                    }*/

                    
                        $k=1;
                        $medi_type=0;
                        $medicine_amount='';
                        if(!empty($all_detail['medicine_payment']))
                        {
                                $net_medicine_payment_data=array();
                                foreach($all_detail['medicine_payment'] as $payment )
                                {

                         
                               $table_data.=' <tr> <th>'.$i.'</th> <th align="left" style="padding: 4px;">'.$payment->particular.'</th><th></th><th></th><th align="right" width="100" style="padding: 4px;">'.$payment->net_price.'</th> </tr>';
                         
                            $i ++; 
                            $net_medicine_payment_data[]= $payment->net_price;
                           }
                        }
                        if(isset($net_medicine_payment_data) && !empty($net_medicine_payment_data))
                        {
                            $medicine_amount=$net_medicine_payment_data[0];
                        }

                         
                        $k=1;
                        $pathology_type=0;
                        $pathalogy_amount='';
                        $path_total=0;
                        if(!empty($all_detail['pathology_payment']))
                        {
                                $net_pathology_payment_data=array();
                                foreach($all_detail['pathology_payment'] as $payment )
                                {
                           $table_data.=' <tr> <th>'.$i.'</th> <th align="left" style="padding: 4px;">'.$payment->particular.'</th><th>'.$payment->qty.'</th><th align="right" style="padding: 4px;">'.$payment->price.'</th><th align="right" width="100" style="padding: 4px;">'.$payment->net_price.'</th> </tr>';

                           
                            $i ++; 
                            $net_pathology_payment_data[]= $payment->net_price;
                            $path_total = $path_total+$payment->net_price;
                                }
                        }
                        if(isset($net_pathology_payment_data) && !empty($net_pathology_payment_data))
                        {
                            $pathalogy_amount=$net_pathology_payment_data[0];
                        }

                        if(isset($total_amount) && isset($net_medicine_payment_data[0]))
                        {
                                 $balance= $total_amount-$net_medicine_payment_data[0];
                        }
                        else
                        {
                                 $balance='0.00';
                        }
                        
                    $s=1;
                    $advance_amount='';
                    if(!empty($all_detail['advance_payment']))
                    {
                            $net_advance_data=array();
                            foreach($all_detail['advance_payment'] as $payment )
                            {
                             $table_data.=' <tr> <th>'.$i.'</th> <th align="left" style="padding: 4px;">'.$payment->particular.'</th><th></th><th></th><th align="right" width="100" style="padding: 4px;">'.$payment->net_price.'</th> </tr>';

                            $i ++; 
                            $net_advance_data[]= $payment->net_price;
                            }
                    }

                     if(isset($net_advance_data) && !empty($net_advance_data))
                        {
                            $advance_amount=$net_advance_data[0];
                        }

                    /*if(isset($total_amount) && isset($net_advance_data[0]))
                    {
                             $balance= $total_amount-$net_advance_data[0];
                    }
                    else
                    {
                             $balance='';
                    }
*/

                    /*if(isset($total_amount) && (isset($net_advance_data[0]) || isset($get_ipd_patient_details['paid_amount_dis_bill']) ) )
                    {
                     $balance= $total_amount+$path_total-$net_advance_data[0]-$get_ipd_patient_details['paid_amount_dis_bill'];
                     if(!empty($get_ipd_patient_details['discount_amount_dis_bill']) && isset($get_ipd_patient_details['discount_amount_dis_bill']))
                     {
                       $balance= $balance-$get_ipd_patient_details['discount_amount_dis_bill'];
                     }
                     $balance = number_format($balance,2);
                    }
                    else
                    {
                             $balance='0.00';
                    }*/
                   // echo $get_ipd_patient_details['total_amount_dis_bill'];
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
                    
                    
                    if($balance_final>0)
                    {
                        $balance=$balance_final;
                    }
                    else
                    {
                        $balance='0.00';
                    }
                    $actual_payment_data= $total_amount+$medicine_amount+$pathalogy_amount+$advance_amount;

                   //  $table_data.='<tr> <th>3</th> <th>ffdsf</th><th>3</th><th>3</th><th align="right" width="100" style="padding: 4px;">'.$actual_payment_data.'</th> </tr>

                     $table_data.='<tr><th><th><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Total Amount :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$get_ipd_patient_details['total_amount_dis_bill'].'</div></th>
                    </tr>
                    <tr><th><th><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Advance Amount :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$get_ipd_patient_details['advance_payment_dis_bill'].'</div></th>
                    </tr>
                    <tr><th><th><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Discount :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$get_ipd_patient_details['discount_amount_dis_bill'].'</div></th>
                    </tr>
                    <tr><th><th><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Received :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$total_paid_amount.'</div></th>
                        <tr><th><th><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Refund :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$get_ipd_patient_details['refund_amount_dis_bill'].'</div></th>


                    </tr>
                     <tr><th><th><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Balance :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'. $balance.'</div></th>

                    </tr>
                 
                    </table>';
                 //print_r(array_unique($array_data));
           //$get_ipd_patient_details['paid_amount_dis_bill']
                    
    $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
   
    $template_data->setting_value = str_replace("{received_amount}",$total_amount,$template_data->setting_value);
    
    
    
   // $template_data->setting_value = str_replace("{balance}",$balance,$template_data->setting_value);
    $template_data->setting_value = str_replace("{signature}",ucfirst($get_ipd_patient_details['user_name']),$template_data->setting_value);
    
    
    if(!empty($get_ipd_patient_details['discharge_remarks']))
    {
       $template_data->setting_value = str_replace("{remarks}",$get_ipd_patient_details['discharge_remarks'],$template_data->setting_value);
    }
    else
    {
       $template_data->setting_value = str_replace("{remarks}",' ',$template_data->setting_value);
       $template_data->setting_value = str_replace("Remarks :",' ',$template_data->setting_value);
       
    }
    
    
    
    
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

   
    echo $template_data->setting_value; 


/* end leaser printing*/
?>