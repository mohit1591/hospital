<?php 
 $user_detail = $this->session->userdata('auth_users');
/* start thermal printing */

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

  $template_data->setting_value = str_replace("{bank_name}",$get_ipd_patient_details['bank_name'],$template_data->setting_value);
  $template_data->setting_value = str_replace("Payment Mode :",'',$template_data->setting_value);
  if(!empty($get_ipd_patient_details['insurance_company_name']))
    {
       $template_data->setting_value = str_replace("{payer_name}",$get_ipd_patient_details['insurance_company_name'],$template_data->setting_value);
       $template_data->setting_value = str_replace("{insurance_comapny_lable}",'Insurance Panel :',$template_data->setting_value); 
    }
    else
    {
      $template_data->setting_value = str_replace("{payer_name}",'CASH',$template_data->setting_value);
      $template_data->setting_value = str_replace("{insurance_comapny_lable}",'',$template_data->setting_value);  
    } 
  $template_data->setting_value = str_replace("{insurance_company_name}",$get_ipd_patient_details['insurance_company_name'],$template_data->setting_value);
  $template_data->setting_value = str_replace("{insurance_type_name}",$get_ipd_patient_details['insurance_type_name'],$template_data->setting_value);

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
    $template_data->setting_value = str_replace("{discharge_date}",date('d-m-Y  h:i A',strtotime($get_ipd_patient_details['discharge_date'])),$template_data->setting_value);
  }
 

    $simulation = get_simulation_name($get_ipd_patient_details['simulation_id'])
    ;
    $template_data->setting_value = str_replace("{patient_name}",$simulation.''.$get_ipd_patient_details['patient_name'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{patient_reg_no}",$get_ipd_patient_details['patient_code'],$template_data->setting_value);
    
    $template_data->setting_value = str_replace("{room_type}",$get_ipd_patient_details['room_category'],$template_data->setting_value);
            $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])).' '.date('h:i A',strtotime($get_ipd_patient_details['admission_time'])),$template_data->setting_value);
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
            //$template_data->setting_value = str_replace("{signature}",'',$template_data->setting_value);
            $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
            //$template_data->setting_value = str_replace("Signature",'',$template_data->setting_value);
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

            <div style="width:60%;line-height:19px;">'.date('d-m-Y h:i A',strtotime($get_ipd_patient_details['admission_date'])).'</div>
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

    if(!empty($get_ipd_patient_details['bad_no']))
    {
        $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['bad_no'].'</div>
            </div>';
            
        $bad_no_val = $get_ipd_patient_details['bad_no'];    
        $template_data->setting_value = str_replace("{bed_no}",$bed_no,$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{bed_no}",'',$template_data->setting_value);
         $bad_no_val = '';
    }
    
    $template_data->setting_value = str_replace("{bad_no_val}",$bad_no_val,$template_data->setting_value);

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
    
            $table_data='<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;"><tr> <th>Sr. No.</th> <th>Primary Code</th><th>Particulars</th><th align="right" width="100" style="padding: 4px;">Amount</th> </tr>
                            

                            ';

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
                        $arr=array();
                        $p_quantity=0;
                        $p_price=0;
                        $p_net_price=0;

                        if(!empty($other_charges))
                        {  
                        $i=1; 
                      
                        foreach($other_charges as $other_charge)
                        {  

                           $table_data.='';
                           $table_data.=' <tr> <th>'.$i.'</th> <th></th><th>'.$other_charge['particular'].'</th><th align="right" width="100" style="padding: 4px;">'.$other_charge['net_price'].'</th> </tr>'; 
                        //  }
                      
                                $i ++; 
                                
                                
                        //$j++;
                        $registration_amount=$other_charge['net_price'];
                       // print_r($registration_amount);die;
                        }
                        }


                        if(!empty($all_detail))
                        {  
                        $i=1; 
                       
                        foreach($all_detail_data as $details_data)
                        {  

                           $table_data.='';
                           $table_data.=' <tr> <th>'.$i.'</th> <th>'.$details_data['group_code'].'</th><th>'.$details_data['group_name'].'</th><th align="right" width="100" style="padding: 4px;">'.$details_data['net_price'].'</th> </tr>'; 
                        //  }
                      
                                $i ++; 
                                
                                
                        //$j++;
                        $total_amount=$total_amount+$details_data['net_price'];
                        }
                        }
                        
                    $total_amount=$total_amount+$registration_amount;
                    
                    $actual_payment_data= $total_amount;
                    // $balance= $actual_payment_data-$get_ipd_patient_details['advance_payment_dis_bill'];
                    if($get_ipd_patient_details['paid_amount_dis_bill']>0)
                    { 
                        $balance_new= $total_amount-($get_ipd_patient_details['paid_amount_dis_bill']+$get_ipd_patient_details['advance_payment_dis_bill']+$get_ipd_patient_details['discount_amount_dis_bill']);
                    }
                    else
                    {
                        $balance_new= $actual_payment_data-($get_ipd_patient_details['advance_payment_dis_bill']+$get_ipd_patient_details['discount_amount_dis_bill']);
                    }
                    
                    if($balance_new>0)
                    {
                       $balance_new = number_format($balance_new,2);
                    }
                    else
                    {
                       $balance_new = '0.00';
                    }
                    

                   //  $table_data.='<tr> <th>3</th> <th>ffdsf</th><th>3</th><th>3</th><th align="right" width="100" style="padding: 4px;">'.$actual_payment_data.'</th> </tr>

                    //$get_ipd_patient_details['total_amount_dis_bill'] replace with $actual_payment_data

                    //$get_ipd_patient_details['balance_amount_dis_bill'] replace with $balance variable
                     $total_with_adv = $get_ipd_patient_details['advance_payment_dis_bill']+$get_ipd_patient_details['paid_amount_dis_bill'];
                     $table_data.='<tr>
                     <th><th><th colspan=""><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Total Amount :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$actual_payment_data.'</div></th>
                    </tr>  
                    <tr><th><th><th colspan="1"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Discount :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$get_ipd_patient_details['discount_amount_dis_bill'].'</div></th>
                    </tr>
                    <tr><th><th><th colspan="1"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Received :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'.$total_with_adv.'</div></th>

                        </tr>';
                    if($get_ipd_patient_details['refund_amount_dis_bill']>0)
                    {


                 $table_data.='<tr>
                        <th><th><th colspan="1">
                        <div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Refund :</div>
                        </th>
                        <th align="right">
                        <div style="padding:0 4px;border:1px solid #333;">'.$get_ipd_patient_details['refund_amount_dis_bill'].'</div>
                        </th>


                    </tr>';
                    }
                     $table_data.='<tr><th><th><th colspan="1"><div style="float:right;border:1px solid #333;border-left:1px solid #333;border-right:none;padding:0 4px;width:134px;">Balance :</div></th>
                        <th align="right"><div style="padding:0 4px;border:1px solid #333;">'. $balance_new.'</div></th>

                    </tr>
                 
                    </table>';
                 //print_r(array_unique($array_data));
           
                    
    $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
   
    $template_data->setting_value = str_replace("{received_amount}",$total_amount,$template_data->setting_value);
   // $template_data->setting_value = str_replace("{balance}",$balance,$template_data->setting_value);
    $template_data->setting_value = str_replace("{signature}",$user_detail['user_name'],$template_data->setting_value);
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

   $payment_table ='';    
if(!empty($all_payment_detail) && isset($all_payment_detail))
{
     $payment_table .='<div style="width:60%;line-height:17px;">
    <div style="float:left;width:100%;padding:5px 0;text-align:left;margin-top:72px;">
    <div style="float:left;width:100%;font-weight:bold;">Payment Recieved:</div>

    <div style="float:right;width:100%;"><table style="border-collapse:collapse;" cellspacing="0" cellpadding="0" border="1" width="100%"><tr> <th align="center">Sr. No.</th> <th align="center">Payment Date</th><th align="center">Payment Mode</th><th align="right" width="100" style="padding: 4px;">Amount</th> </tr><tbody>';

        $p=1;
        foreach($all_payment_detail as $payment_data)
        { 
            $payment_date = date('d-m-Y',strtotime($payment_data['payment_date']));
            $payment_table.=' <tr> <th align="center">'.$p.'</th> <th align="center">'.$payment_date.'</th><th align="center">'.ucfirst($payment_data['payment_mode']).'</th><th align="right" width="100" style="padding: 4px;">'.$payment_data['amount'].'</th> </tr>'; 
            $p ++; 
        }

        $payment_table.='</tbody></table></div></div></div>';
   }
    $template_data->setting_value = str_replace("{payment_table}",$payment_table,$template_data->setting_value);
    echo $template_data->setting_value; 


/* end leaser printing*/
?>

