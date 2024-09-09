<?php
//print_r($patient_detail);

if(empty($address_setting_list))
    {
        $address = $patient_detail['address'];
        $pincode = $patient_detail['pincode'];    
        $country = $patient_detail['country_name'];    
        $state = $patient_detail['state_name'];    
        $city = $patient_detail['city_name'];    
        $patient_address = $address.' '.$country.','.$state.' '.$city.' - '.$pincode;
        $template_data->setting_value = str_replace("{patient_address}",$patient_address,$template_data->setting_value);
    }
    else
    {
        $address='';
        if($address_setting_list[0]->address1)
        {
           $address .= $patient_detail['address1'].' '; 
        }
        if($address_setting_list[0]->address2)
        {
           $address .= $patient_detail['address2'].' '; 
        }
       
        if($address_setting_list[0]->address3)
        {
           $address .=  $patient_detail['address3'].' '; 
        }
      
        if($address_setting_list[0]->city)
        {
           $address .=  $patient_detail['city_name'].' '; 
        }
       
        if($address_setting_list[0]->state)
        {
           $address .= $patient_detail['state_name'].' '; 
        }
        if($address_setting_list[0]->country)
        {
           $address .=  $patient_detail['country_name'].' '; 
        }
        if($address_setting_list[0]->pincode)
        {
           $address .= $patient_detail['pincode']; 
        }
        $template_data->setting_value = str_replace("{patient_address}",$address,$template_data->setting_value);
    }
$del = ','; 
$address_n='';
$address_re='';

  $template_data->setting_value = str_replace("{patient_reg_no}",$patient_detail['patient_code'],$template_data->setting_value);

$template_data->setting_value = str_replace("{ipd_no}",$patient_detail['ipd_no'],$template_data->setting_value);
$template_data->setting_value = str_replace("{room_no}",$patient_detail['room_no'],$template_data->setting_value);
$template_data->setting_value = str_replace("{bed_no}",$patient_detail['bed_no'],$template_data->setting_value);
$template_data->setting_value = str_replace("{note}",$patient_detail['remarks'],$template_data->setting_value);

$template_data->setting_value = str_replace("{operation}",'',$template_data->setting_value);

/*
    if($patient_detail['address']!='' || $patient_detail['address2']!='' || $patient_detail['address3']!='')
    {
        $address_n = array_merge(explode ( $del , $patient_detail['address']),explode ( $del , $patient_detail['address2']),explode ( $del , $patient_detail['address3']));
    }
     if(!empty($address_n))
     {
         $address_re = array();
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
    $template_data->setting_value = str_replace("{patient_address}",$patient_address,$template_data->setting_value);*/

    $template_data->setting_value = str_replace("{patient_name}",$patient_detail['simulation'].' '.$patient_detail['patient_name'],$template_data->setting_value);
    //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
    $template_data->setting_value = str_replace("{mobile_no}",$patient_detail['mobile_no'],$template_data->setting_value);
    // if($date_time_status==1)
    // {
          if($patient_detail['operation_time']=="00:00:00")
                {
                    $operation_time='';
                }
                else
                {
                    $operation_time=date('h:i A',strtotime($patient_detail['operation_time']));
                }
        $template_data->setting_value = str_replace("{reciept_date}",date('d-m-Y',strtotime($patient_detail['operation_date'])).' '.$operation_time,$template_data->setting_value);
    // }
    // else
    // {
    //     $template_data->setting_value = str_replace("{reciept_date}",date('d-m-Y',strtotime($patient_detail['operation_date'])),$template_data->setting_value);
    // }




    $genders = array('0'=>'F','1'=>'M','2'=>'O');
    $gender = $genders[ $patient_detail['gender']];
    $age_y = $patient_detail['age_y']; 
    $age_m = $patient_detail['age_m']; ;
    $age_d =  $patient_detail['age_d'];

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
    if($patient_age!=''){
    $patient1_age = '/'.$patient_age;
    }
    if($patient_age==''){
    $patient1_age=$patient_age;
    }
    $gender_age = $gender.$patient1_age ;
    $doctor_name=array();
    foreach($all_detail['operation_list']['doctor_list']as $doctor_list){
    $doctor_name[]=$doctor_list->doctor_name;

    }
    $doctor_name= implode(',',$doctor_name);

    $template_data->setting_value = str_replace("{patient_age}",$gender_age,$template_data->setting_value);

    if($user_detail['users_role']==4)
    {
    $template_data->setting_value = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->setting_value);   
    }
    else
    {
    $template_data->setting_value = str_replace("{signature}",ucfirst($patient_detail['user_name']),$template_data->setting_value);    
    }
    $template_data->setting_value = str_replace("{doctor_name}",$doctor_name,$template_data->setting_value);

   $table_data=''; 
    //echo $patient_detail['amount'];die;
   //print '<pre>'; print_r($all_detail['operation_list']);die;
   $total_amount = "0.00";
    $table_data.='<div style="display:flex;">
                       
                        <div style="flex:1;margin:5px;font-weight:bold;">
                        Particulars
                        </div> 
                        <div style="flex:1;margin:5px;font-weight:bold;">Charge(%)
                        </div>
                        <div style="flex:1;margin:5px;font-weight:bold;">Charge(Rs)
                        </div>
                        
                        <div style="flex:1;margin:5px;">

                        </div>
                    </div>
                </div>'; 
   foreach($all_detail['operation_list'] as $key=>$details)
   { 
      
    if(is_numeric($key))
    {

    $title = "";
    ////////////// 
    if(isset($details->particular) && !empty($details->particular))
    {
        $title = $details->particular;
    }
    if(isset($details->doctor_name) && !empty($details->doctor_name))
    {
        $title= 'Dr. '.$details->doctor_name;
    }


    $row_percent = "-";
    $row_rupees = "-";
    if($details->master_type==1)
    {
        $row_percent = $details->master_rate.'%';
        $calc_rupess = $details->master_rate*($patient_detail['amount']/100);
        $row_rupees = 'Rs. '.$calc_rupess;
    }
    else
    { 
        $calc_rupess = $details->master_rate;
        $row_rupees = 'Rs. '.$calc_rupess;
    } 

    $total_amount = $total_amount + $calc_rupess;
     //print '<pre>'; print_r($details->master_rate);die;
                $table_data.='<div style="display:flex;">
                       
                        <div style="flex:1;margin:5px;font-weight:bold;">
                        '.$title.'
                        </div> 
                        <div style="flex:1;margin:5px;">'.$row_percent.'
                        </div>
                        <div style="flex:1;margin:5px;">'.$row_rupees.'
                        </div>
                        
                        <div style="flex:1;margin:5px;">

                        </div>
                    </div>
                </div>'; 
    }
}

  $table_data.='<div style="display:flex;">
                          <div style="flex:1;margin:5px;font-weight:bold;">

                        </div> 
                        <div style="flex:1;float:right;margin:5px;padding-top:10px;font-size:14px;font-weight:bold;">
                          Total Amount
                        </div>
                           <div style="flex:1;margin:5px; float:right;border-top:1px solid #666666;padding:10px;font-size:14px;font-weight:bold;">
                        '.$total_amount.'
                        </div>
                        <div style="flex:1;margin:5px;">

                        </div>
                        </div> ';
              
   $template_data->setting_value = str_replace('{table_data}',$table_data,$template_data->setting_value);

	echo $template_data->setting_value;


/* end leaser printing*/
?>


