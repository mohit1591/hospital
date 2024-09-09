<?php 
 $user_detail = $this->session->userdata('auth_users');
/* start thermal printing */
 $payment_mode="";
$payment_mode=$get_ipd_patient_details['payment_mode'];

/*if($get_ipd_patient_details['payment_mode']==1){
    $payment_mode='Cash';
}
if($get_ipd_patient_details['payment_mode']==2){
    $payment_mode='Card';
}
if($get_ipd_patient_details['payment_mode']==3){
    $payment_mode='Cheque';
}
if($get_ipd_patient_details['payment_mode']==4){
    $payment_mode='NEFT';
}*/


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

    if(!empty($get_ipd_patient_details['bad_no']))
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
    
            $table_data='<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><tr> <th>Sr. No.</th> <th>Particulars</th><th align="right" width="100" style="padding: 4px;">Amount</th> </tr>
                            

                            ';

                        $i=1;
                        $heading_of_particular='';
                        $array_data=array();
                     $actual_payment_data=array();
                     $total_amount='';
                        $v=1;
                        $type_one = 0;
                        $type_two = 0;
                        $perticuler_charge = [];
                        if(!empty($all_detail['CHARGES']))
                        {
                        foreach($all_detail['CHARGES'] as  $details_data)
                        {  
                        $total_amount=$total_amount+$details_data['net_price'];
                        }
                        }


                    
                        $k=1;
                        $medi_type=0;
                         $net_medicine_payment_data=array();
                         $medicine_amount='';
                        if(!empty($all_detail['medicine_payment']))
                        {
                               
                                foreach($all_detail['medicine_payment'] as $payment )
                                {
                               
                                $k ++; 
                                $net_medicine_payment_data[]= $payment->net_price;
                                }
                        }
                        if(!empty($net_medicine_payment_data))
                        {
                            $medicine_amount=$net_medicine_payment_data[0];
                        }
                        else
                        {
                         $medicine_amount='';   
                        }

                         
                        $k=1;
                        $pathology_type=0;
                        $pathalogy_amount='';
                        $net_pathology_payment_data=array();
                        if(!empty($all_detail['pathology_payment']))
                        {
                                
                                foreach($all_detail['pathology_payment'] as $payment )
                                {
                              
                                $k ++; 
                                $net_pathology_payment_data[]= $payment->net_price;
                                }
                        }

                         if(!empty($net_pathology_payment_data))
                        {
                            $pathalogy_amount=$net_pathology_payment_data[0];
                        }
                        else
                        {
                         $pathalogy_amount='';   
                        }

                        if(isset($total_amount) && isset($medicine_amount))
                        {
                                 $balance= $total_amount-$medicine_amount;
                        }
                        else
                        {
                                 $balance='';
                        }
                        
                    $s=1;
                     $net_advance_data=array();
                      $advance_amount='';
                    if(!empty($all_detail['advance_payment']))
                    {
                           
                            foreach($all_detail['advance_payment'] as $payment )
                            {
                            
                            $s ++; 
                            $net_advance_data[]= $payment->net_price;
                            }
                    }
                  
                    if(!empty($net_advance_data))
                        {
                            $advance_amount=$net_advance_data[0];
                        }
                        else
                        {
                         $advance_amount='';   
                        }

                    if(isset($total_amount) && isset($advance_amount))
                    {
                             $balance= $total_amount-$advance_amount;
                    }
                    else
                    {
                             $balance='';
                    }

                    $actual_payment_data= $total_amount+$medicine_amount+$pathalogy_amount;//+$advance_amount;

                     $table_data.='<tr> <th>1</th> <th>Hospital Charges</th><th align="right" width="100" style="padding: 4px;">'.$actual_payment_data.'</th> </tr>

                     <tr><th colspan="2"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;">Total Amount :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$actual_payment_data.'</div></th>
                    </tr>
                    </table>';
                 //print_r(array_unique($array_data));
           
                    
    $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
   
    $template_data->setting_value = str_replace("{received_amount}",$total_amount,$template_data->setting_value);
   // $template_data->setting_value = str_replace("{balance}",$balance,$template_data->setting_value);
    $template_data->setting_value = str_replace("{signature}",ucfirst($get_ipd_patient_details['user_name']),$template_data->setting_value);
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

