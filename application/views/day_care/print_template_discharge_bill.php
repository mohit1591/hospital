<?php 
//echo "<pre>";print_r($get_opd_patient_details);die;
 $user_detail = $this->session->userdata('auth_users');
/* start thermal printing */
 $template_data->setting_value = str_replace("{payment_mode}",$payment_mode,$template_data->setting_value);
  
  if($get_opd_patient_details['daycare_discharge_date']=="0000-00-00" || $get_opd_patient_details['daycare_discharge_date']=='0000-00-00 00:00:00')
  {
    $template_data->setting_value = str_replace("{daycare_discharge_date}",'',$template_data->setting_value);
  }
  else
  {
      
      if($template_data->date_time_formate==1)
    {
        

        $template_data->setting_value = str_replace("{daycare_discharge_date}",date('d-m-Y h:i A',strtotime($get_opd_patient_details['daycare_discharge_date'])),$template_data->setting_value);

    }
    else
    {
        $template_data->setting_value = str_replace("{daycare_discharge_date}",date('d-m-Y',strtotime($get_opd_patient_details['daycare_discharge_date'])),$template_data->setting_value); 
    }
 
  }




    $simulation = get_simulation_name($get_opd_patient_details['simulation_id'])
    ;
    $template_data->setting_value = str_replace("{patient_name}",$simulation.''.$get_opd_patient_details['patient_name'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{patient_reg_no}",$get_opd_patient_details['patient_code'],$template_data->setting_value);
    
    $address2=$address3=$pincode='';
     
    if(!empty($get_opd_patient_details['address2']))
    {
        $address2= ', '.$get_opd_patient_details['address2'];
    }
    if(!empty($get_opd_patient_details['address3']))
    {
        $address3=', '.$get_opd_patient_details['address3'];
    }
    if(!empty($get_opd_patient_details['pincode']))
    {
        $pincode=' - '.$get_opd_patient_details['pincode'];
    }

    $patient_address = $get_opd_patient_details['address'].$address2.$address3.$pincode;

    $template_data->setting_value = str_replace("{patient_address}",$patient_address,$template_data->setting_value);
    $template_data->setting_value = str_replace("{room_type}",$get_opd_patient_details['room_category'],$template_data->setting_value);
   
     
     if($template_data->date_time_formate==1)
      {
        $booking_time='';
        if(!empty($get_opd_patient_details['booking_time']) && $get_opd_patient_details['booking_time']!='00:00:00' && strtotime($get_opd_patient_details['booking_time'])>0)
        {
            $booking_time = date('h:i A', strtotime($get_opd_patient_details['booking_time'])); 
        }

        
        $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_opd_patient_details['booking_date'])).' '.$booking_time,$template_data->setting_value);
        

      }
      else
      {
        $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_opd_patient_details['booking_date'])),$template_data->setting_value);
      }

      $booking_time='';
        if(!empty($get_opd_patient_details['booking_time']) && $get_opd_patient_details['booking_time']!='00:00:00' && strtotime($get_opd_patient_details['booking_time'])>0)
        {
            $booking_time = date('h:i A', strtotime($get_opd_patient_details['booking_time'])); 
        }
    
     $template_data->setting_value = str_replace("{booking_time}",$booking_time,$template_data->setting_value);

     if($get_opd_patient_details['daycare_discharge_date']=="0000-00-00" || $get_opd_patient_details['daycare_discharge_date']=='0000-00-00 00:00:00')
      {
        $discharge_time = date('h:i A', strtotime($get_opd_patient_details['daycare_discharge_date'])); 
      }
    $template_data->setting_value = str_replace("{discharge_time}",$discharge_time,$template_data->setting_value);
    

  
   
   // $template_data->setting_value = str_replace("{total_amount}",$get_opd_patient_details['daycare_total_amount'],$template_data->setting_value);

   //$template_data->setting_value = str_replace("{advance_amount}",$get_opd_patient_details['advance_payment_dis_bill'],$template_data->setting_value);
    // $template_data->setting_value = str_replace("{discount}",$get_opd_patient_details['daycare_discount_amount'],$template_data->setting_value);

if(!empty($get_opd_patient_details['daycare_paid_amount']))
{
$paid_amount_dis_bill = $get_opd_patient_details['daycare_paid_amount'];
}
else
{
$paid_amount_dis_bill = '0.00';
}


     $template_data->setting_value = str_replace("{received}",$paid_amount_dis_bill,$template_data->setting_value); //total_amount_dis_bill 02-12-2017
     $template_data->setting_value = str_replace("{refund}",$get_opd_patient_details['refund_amount_dis_bill'],$template_data->setting_value);
     if($get_opd_patient_details['daycare_balance_amount'] < 0)
     {
        $paid_balance = '0.00';
     }
     else
     {
        $paid_balance = $get_opd_patient_details['daycare_balance_amount'];
     }
     
         $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
        $template_data->setting_value = str_replace("{specialization}",$get_opd_patient_details['specialization'],$template_data->setting_value);
   

    if(!empty($get_opd_patient_details['doctor_name']))
    {
        $template_data->setting_value = str_replace("{consultant}",'Dr. '.$get_opd_patient_details['doctor_name'],$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{consultant}",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("Consultant:",'',$template_data->setting_value);
    }
    
    $template_data->setting_value = str_replace("{bill_no}",$get_opd_patient_details['day_discharge_bill_no'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{mobile_no}",$get_opd_patient_details['mobile_no'],$template_data->setting_value);


    if(!empty($get_opd_patient_details['booking_code']))
    {
       
        $template_data->setting_value = str_replace("{booking_code}",$get_opd_patient_details['booking_code'],$template_data->setting_value);
    }

    if(!empty($get_opd_patient_details['booking_date']))
    {
        
        $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_opd_patient_details['booking_date'])),$template_data->setting_value);
    }

    if(!empty($get_opd_patient_details['daycare_discharge_date']))
    {
    
        $template_data->setting_value = str_replace("{receipt_date}",date('d-m-Y h:i A',strtotime($get_opd_patient_details['daycare_discharge_date'])),$template_data->setting_value);
    }

    
   

    
    if(!empty($get_opd_patient_details['mlc']) && $get_opd_patient_details['mlc']==1)
    {
      
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
                        $total_amount='';
                        $v=1;
                        $type_one = 0;
                        $type_two = 0;
                        $perticuler_charge = [];
                        
                        
                        
                        if(!empty($all_detail['CHARGES'])){
                             $table_data.='';
                            $heading="Particulars charge";
                                $table_data.='<div style="float:left;width:100%;padding:4px;"><span style="border-bottom:1px solid #111;font-weight:bold;">'.$heading.'</span> </div>';
                                
                        foreach($all_detail['CHARGES'] as  $details_data)
                        {  
                            
                           
                            
                            
                                $type_two = 1;

                          
                      
                        
                        $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$i.'</div>
                                            <div style="width:30%;line-height:17px;">'.$details_data['particular'].'</div>
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

                    
                    $s=1;
                    if(!empty($all_detail['advance_payment']))
                    {
                            $net_advance_data=array();
                            foreach($all_detail['advance_payment'] as $payment )
                            {
                            $table_data.='<div style="float:left;width:100%;padding:4px;">';
                            $table_data.='<span style="border-bottom:1px solid #111;font-weight:bold;">Registration Charge</span> </div>'; 
                            $table_data.='<div style="float:left;width:100%;display:inline-flex;">
                                            <div style="width:10%;line-height:17px;padding-left:15px;">'.$s.'</div>
                                            <div style="width:30%;line-height:17px;">'.$payment->particular.'</div>
                                            <div style="width:30%;line-height:17px;">'.date('d-m-Y',strtotime($payment->start_date)).'</div>
                                            <div style="width:10%;line-height:17px;">1.00</div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                                            <div style="width:10%;line-height:17px;">'.$payment->net_price.'</div>
                            </div>
                            </div>';
                            $s ++; 
                            $net_advance_data[]= $payment->net_price;
                            }
                    }

                 
                    
    /*if(isset($total_amount) && (isset($net_advance_data[0]) || isset($paid_amount_dis_bill) ) )
    {
             $balance= ($total_amount+$net_advance_data[0])-$paid_amount_dis_bill; //-$net_advance_data[0]
             if(!empty($get_opd_patient_details['daycare_discount_amount']) && isset($get_opd_patient_details['daycare_discount_amount']))
             {
               $balance= $balance-$get_opd_patient_details['daycare_discount_amount'];
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
    }*/
    
     $net_advance = $net_advance_data[0];
                         $grand_total_amount= $total_amount+$net_advance;

                        

                        
                        if($get_opd_patient_details['daycare_discount_amount']>0)
                        {
                            $grand_net_amount = $grand_total_amount-$get_opd_patient_details['daycare_discount_amount'];
                        }
                        else
                        {
                            $grand_net_amount = $grand_total_amount;
                        }
                        //echo $grand_net_amount; exit;
                        if($get_opd_patient_details['daycare_paid_amount']>0)
                        {
                            $grand_net_amount = $grand_net_amount;
                            $balance = $grand_net_amount-$get_opd_patient_details['daycare_paid_amount'];
                            $net_advance = $get_opd_patient_details['daycare_paid_amount'];
                        }
                        else
                        {
                            $grand_net_amount = $grand_net_amount;
                            $net_advance ='0.00';
                            $balance = $grand_net_amount;
                        }
                        

                 //print_r(array_unique($array_data));
                    
    $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
    $template_data->setting_value = str_replace("{total_amount}",number_format($grand_total_amount,2),$template_data->setting_value);
    //echo $grand_total_amount. $template_data->setting_value; exit;
    $template_data->setting_value = str_replace("{discount}",number_format($get_opd_patient_details['daycare_discount_amount'],2),$template_data->setting_value);
    $template_data->setting_value = str_replace("{net_amount}",number_format($grand_net_amount,2),$template_data->setting_value);
    $template_data->setting_value = str_replace("{received_amount}",$get_opd_patient_details['daycare_paid_amount'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{balance}",number_format($balance,2),$template_data->setting_value);


                    
    //print_r(array_unique($array_data));
                    
   /* $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
   
    $template_data->setting_value = str_replace("{received_amount}",$paid_amount_dis_bill,$template_data->setting_value);
   $template_data->setting_value = str_replace("{balance}",$balance,$template_data->setting_value);
   $template_data->setting_value = str_replace("{registration_charge}",$net_advance_data[0],$template_data->setting_value);*/

   
    $template_data->setting_value = str_replace("{signature}",ucfirst($get_opd_patient_details['user_name']),$template_data->setting_value);
    $genders = array('0'=>'F','1'=>'M');
    $gender = $genders[$get_opd_patient_details['gender']];
    $age_y = $get_opd_patient_details['age_y']; 
    $age_m = $get_opd_patient_details['age_m'];
    $age_d = $get_opd_patient_details['age_d'];

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