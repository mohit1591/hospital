<?php 
 $user_detail = $this->session->userdata('auth_users');
/* start thermal printing */
$payment_mode='';
/*
if($all_detail['ipd_list'][0]->payment_mode==1){
    $payment_mode='Cash';
}
if($all_detail['ipd_list'][0]->payment_mode==2){
    $payment_mode='Card';
}
if($all_detail['ipd_list'][0]->payment_mode==3){
    $payment_mode='Cheque';
}
if($all_detail['ipd_list'][0]->payment_mode==4){
    $payment_mode='NEFT';
}
*/
// echo "<pre>"; print_r($get_ipd_patient_details); exit;


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


    $simulation = get_simulation_name($get_ipd_patient_details['simulation_id']);
    $template_data->setting_value = str_replace("{patient_name}",$simulation.''.$get_ipd_patient_details['patient_name'],$template_data->setting_value);
    $template_data->setting_value = str_replace("{patient_reg_no}",$get_ipd_patient_details['patient_code'],$template_data->setting_value);
    $address = $get_ipd_patient_details['address'];
    $pincode = $get_ipd_patient_details['pincode'];         
    
    $patient_address = $address.' - '.$pincode;

    $template_data->setting_value = str_replace("{patient_address}",$patient_address,$template_data->setting_value);
    
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
        
        
    $template_data->setting_value = str_replace("{bill_no}",'',$template_data->setting_value);
     $template_data->setting_value = str_replace("{room_type}",$get_ipd_patient_details['room_category'],$template_data->setting_value);
     $template_data->setting_value = str_replace("{booking_date}",date('d-m-Y',strtotime($get_ipd_patient_details['admission_date'])),$template_data->setting_value);
    
    
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
         $template_data->setting_value = str_replace("{specialization_level}",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("{specialization}",'',$template_data->setting_value);
    //}

    if(!empty($get_ipd_patient_details['doctor_name']))
    {
        $consultant_new = '<div style="width:100%;display:inline-flex;">
            <div style="width:40%;line-height:17px;font-weight:600;">Assigned Doctor :</div>

            <div style="width:60%;line-height:17px;">'.'Dr. '. $get_ipd_patient_details['doctor_name'].'</div>
            </div>';
        $template_data->setting_value = str_replace("{Consultant}",$consultant_new,$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{Consultant}",'',$template_data->setting_value);
    }
    
    $template_data->setting_value = str_replace("{mobile_no}",$get_ipd_patient_details['mobile_no'],$template_data->setting_value);


    if(!empty($get_ipd_patient_details['ipd_no']))
    {
        $receipt_code = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">IPD No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['ipd_no'].'</div>
            </div>';
        $template_data->setting_value = str_replace("{ipd_no}",$receipt_code,$template_data->setting_value);
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
        /*$room_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Room No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['room_no'].'</div>
            </div>';*/
        $template_data->setting_value = str_replace("{room_no}",$get_ipd_patient_details['room_no'],$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{room_no}",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("Room No.:",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("Room No",'',$template_data->setting_value);
         
    }

    if(!empty($get_ipd_patient_details['bad_no']))
    {
       /* $bed_no = '<div style="width:100%;display:inline-flex;">
                        <div style="width:40%;line-height:19px;font-weight:600;">Bed No.:</div>

            <div style="width:60%;line-height:19px;">'.$get_ipd_patient_details['bad_no'].'</div>
            </div>';*/
        $template_data->setting_value = str_replace("{bed_no}",$get_ipd_patient_details['bad_no'],$template_data->setting_value);
    }
    else
    {
         $template_data->setting_value = str_replace("{bed_no}",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("Bed No.:",'',$template_data->setting_value);
         $template_data->setting_value = str_replace("Bed No.",'',$template_data->setting_value);
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

                        // $i=1;
                        // $heading_of_particular='';
                        // $array_data=array();
                        // $total_amount='';
                        // $v=1;
                        // $type_one = 0;
                        // $type_two = 0;
                        // $perticuler_charge = [];
                        // foreach($all_detail['CHARGES'] as $charges)
                        // { 
                        //    $perticuler_charge[] = array('particular'=>$charges['particular'],'start_date'=>$charges['start_date'],'price'=>$charges['price'],'quantity'=>$charges['quantity'],'net_price'=>$charges['net_price'],'type'=>$charges['type']);
                        // } 
 
                        // $unique_arr = array_unique(array_column($perticuler_charge,'particular'));
                        // $uni_arr = [];
                        // foreach($unique_arr as $unique)
                        // {
                        //     $uni_arr[str_replace(" ","",$unique)] = array('particular'=>$unique,'start_date'=>'','price'=>0,'quantity'=>0,'net_price'=>0,'type'=>0);
                        // }
                        
                        // $array_final_perticuler = []; 
                        // foreach($perticuler_charge as $final_charge)
                        // {  
                        //     $key = str_replace(" ","",$final_charge['particular']);
                        //    if(array_key_exists($key,$uni_arr))
                        //    {  
                        //       $quantity = $uni_arr[$key]['quantity']+$final_charge['quantity'];
                        //       $net_amount = number_format($uni_arr[$key]['net_price']+$final_charge['net_price'],2);
                        //       $uni_arr[$key] = array('particular'=>$final_charge['particular'],'start_date'=>$final_charge['start_date'],'price'=>number_format($final_charge['price'],2),'quantity'=>$quantity,'net_price'=>$net_amount,'type'=>$final_charge['type']);
                        //    }
                           
                        // }

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
                        foreach($all_detail['CHARGES'] as $charges)
                        { 
                           if(!empty($charges['room_category']))
                            {
                              $room_category = ' ('.$charges['room_category'].')';
                            }
                            else
                            {
                                $room_category = '';
                            }
                            
                            if(!empty($charges['doctor_name']))
                            {
                              $doctor_name = ' ('.$charges['doctor_name'].')';
                            }
                            else
                            {
                                $doctor_name = '';
                            }
                            
                           $perticuler_charge[] = array('particular'=>$charges['particular'].$room_category.$doctor_name,'start_date'=>$charges['start_date'],'price'=>$charges['price'],'quantity'=>$charges['quantity'],'net_price'=>$charges['net_price'],'type'=>$charges['type']);
                        } 
 
                        $unique_arr = array_unique(array_column($perticuler_charge,'particular'));
                       
                        foreach($unique_arr as $unique)
                        {
                            $uni_arr[str_replace(" ","",$unique)] = array('particular'=>$unique,'start_date'=>'','price'=>0,'quantity'=>0,'net_price'=>0,'type'=>0);
                        }
                        
                        $array_final_perticuler = [];

                        $column_all = array_column($perticuler_charge,'particular');
                        $unique_perticuller = array_unique($column_all);
                        $summurise_arr = [];
                        $i=1;
                        //print '<pre>'; print_r($unique_perticuller);die; 
                        foreach($perticuler_charge as $final_charge)
                        {  
                           //print '<pre>'; print_r($final_charge);die; 
                           if(!empty($unique_perticuller))
                            { 
                                foreach($unique_perticuller as $uni_per)
                                { 
                                    if(trim($final_charge['particular'])==trim($uni_per))
                                    {  
                                          if(isset($summurise_arr[$final_charge['particular']]))
                                          {
                                              $summurise_arr[$final_charge['particular']] = array('particular'=>$final_charge['particular'],'type'=>$final_charge['type'], 'quantity'=>$summurise_arr[$uni_per]['quantity']+$final_charge['quantity'], 'price'=>$summurise_arr[$uni_per]['price'], 'net_price'=>$summurise_arr[$uni_per]['net_price']+$final_charge['net_price'],'start_date'=>$final_charge['start_date'],'type'=>$final_charge['type']);
                                          
                                              //+$final_charge['price']
                                          } 
                                          else
                                          {
                                              $summurise_arr[$final_charge['particular']] = array('particular'=>$final_charge['particular'],'type'=>$final_charge['type'], 'quantity'=>$final_charge['quantity'], 'price'=>$final_charge['price'], 'net_price'=>$final_charge['net_price'],'start_date'=>$final_charge['start_date'],'type'=>$final_charge['type']);
                                          } 
                                       
                                    }  
                                }
                                
 
                            }  
                            $i++;
                            
                          
                        } 
                     
 array_sort_by_column($summurise_arr, 'start_date');
$i=1;
                        foreach($summurise_arr as $details_data)
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
                        } $s=1;

                       /* medicine data */
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
                        
                        $total_amount=$total_amount+$net_medicine_payment_data[0];

                       /* medicine data */

                       /* pathalogy charges */
                            $k=1;
                            $pathology_type=0;
                            $net_pathology_payment_amt=0;
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
                                    $net_pathology_payment_amt = $net_pathology_payment_amt+$payment->net_price;
                                }
                            }
                       /* pathalogy charges */
                        //echo $net_pathology_payment_data[0];
                         $total_amount=$total_amount+$net_pathology_payment_amt;
                        
                        $net_advance_data=array();
                        foreach($all_detail['advance_payment'] as $payment )
                        {
                        $table_data.='<div style="float:left;width:100%;padding:4px;">';
                        $table_data.='<span style="border-bottom:1px solid #111;font-weight:bold;">Advance Payment</span> </div>'; 
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
                        if(isset($total_amount) && isset($net_advance_data[0]))
                        {
                                 $balance= $total_amount-$net_advance_data[0];
                        }
                        else
                        {
                                 $balance=$total_amount;
                        }

                        if(isset($net_advance_data[0]))
                        {
                            $net_advance = $net_advance_data[0];
                        }
                        else
                        {
                            $net_advance='0.00';
                        }
                 //print_r(array_unique($array_data));
                    
    $template_data->setting_value = str_replace("{table_data}",$table_data,$template_data->setting_value);
    $template_data->setting_value = str_replace("{total_amount}",$total_amount,$template_data->setting_value);
    $template_data->setting_value = str_replace("{received_amount}",$net_advance,$template_data->setting_value);
    $template_data->setting_value = str_replace("{balance}",$balance,$template_data->setting_value);
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

   
    echo $template_data->setting_value; 


/* end leaser printing*/
?>

