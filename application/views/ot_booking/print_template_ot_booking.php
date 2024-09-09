<?php //echo $all_detail['operation_list'][0]->state_name;
if(empty($address_setting_list))
    {
        $address = $all_detail['operation_list'][0]->address;
        $pincode = $all_detail['operation_list'][0]->pincode;    
        $country = $all_detail['operation_list'][0]->country_name;    
        $state = $all_detail['operation_list'][0]->state_name;    
        $city = $all_detail['operation_list'][0]->city_name;    
        $patient_address = $address.' '.$country.','.$state.' '.$city.' - '.$pincode;
        $template_data->template = str_replace("{patient_address}",$patient_address,$template_data->template);
        $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);
    }
    else
    {
        $address_p='';
        if($address_setting_list[0]->address1)
        {
           $address_p .= $all_detail['operation_list'][0]->address.' '; 
        }
        if($address_setting_list[0]->address2)
        {
           $address_p .= $all_detail['operation_list'][0]->address2.' '; 
            
        }
       
        if($address_setting_list[0]->address3)
        {
           $address_p .=  $all_detail['operation_list'][0]->address3.' '; 
        }
    
        if($address_setting_list[0]->city)
        {
           $address_p .=  $all_detail['operation_list'][0]->city_name.' '; 
        }
       
        if($address_setting_list[0]->state)
        {
           $address_p .= $all_detail['operation_list'][0]->state_name.' '; 
            
        }
        if($address_setting_list[0]->country)
        {
           $address_p .=  $all_detail['operation_list'][0]->country_name.' '; 
        }
        if($address_setting_list[0]->pincode)
        {
           $address_p .= $all_detail['operation_list'][0]->pincode; 
        }
        
        $template_data->template = str_replace("{patient_address}",$address_p,$template_data->template);
        $template_data->template = str_replace("{pateint_address}",$address_p,$template_data->template);
    }
$del = ','; 
$address_n='';
$address_re='';
$operation_time='';
//print '<pre>'; print_r($all_detail['operation_list']);die;


$template_data->template = str_replace("{booking_code}",$all_detail['operation_list'][0]->booking_code,$template_data->template);
/* start thermal printing */
if($template_data->printer_id==2)
{
        
       if((!empty($all_detail['operation_list'][0]->reciept_suffix) || !empty($all_detail['operation_list'][0]->reciept_prefix)))
          {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['operation_list'][0]->reciept_prefix.$all_detail['operation_list'][0]->reciept_suffix,$template_data->template);
          }
          else
          {
             $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
          }


        if(!empty($all_detail['operation_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['operation_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['operation_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

        if(!empty($all_detail['operation_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['operation_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['operation_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }
    



        $template_data->template = str_replace("{pateint_reg_no}",$all_detail['operation_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{patient_name}",$all_detail['operation_list'][0]->simulation.' '.$all_detail['operation_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{s_no}",1,$template_data->template);
        $template_data->template = str_replace("{package_name}",$all_detail['operation_list'][0]->package_name,$template_data->template);
        // if($date_time_status==1)
        // {
                if($all_detail['operation_list'][0]->operation_time=="00:00:00")
                {
                    $operation_time='';
                }
                else
                {
                    $operation_time=date('h:i A',strtotime($all_detail['operation_list'][0]->operation_time));
                }
                $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.$operation_time,$template_data->template);
        // }
        // else
        // {
        //     $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)),$template_data->template);
        // }
        $template_data->template = str_replace("{operation_date_time}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.$operation_time,$template_data->template);
        $genders = array('0'=>'F','1'=>'M','2'=>'O');
            $gender = $genders[$all_detail['operation_list'][0]->gender];
            $age_y = $all_detail['operation_list'][0]->age_y; 
            $age_m = $all_detail['operation_list'][0]->age_m;
            $age_d = $all_detail['operation_list'][0]->age_d;
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


        $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

        if($user_detail['users_role']==4)
        {
        $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
        }
        else
        {
        $template_data->template = str_replace("{signature}",ucfirst($all_detail['operation_list'][0]->user_name),$template_data->template);    
        }
        $this->session->unset_userdata('ot_book_id');
        echo $template_data->template; 

}
/* end thermal printing */
/* start dot printing */
if($template_data->printer_id==3)
{

        if((!empty($all_detail['operation_list'][0]->reciept_suffix) || !empty($all_detail['operation_list'][0]->reciept_prefix)))
          {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['operation_list'][0]->reciept_prefix.$all_detail['operation_list'][0]->reciept_suffix,$template_data->template);
          }
          else
          {
             $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
          }


            if(!empty($all_detail['operation_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['operation_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['operation_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

            if(!empty($all_detail['operation_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['operation_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['operation_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }


    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['operation_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{ipd_no}",$all_detail['operation_list'][0]->ipd_no,$template_data->template);
$template_data->template = str_replace("{room_no}",$all_detail['operation_list'][0]->ot_room,$template_data->template);
/*$template_data->template = str_replace("{bed_no}",$all_detail['operation_list'][0]->bed_no,$template_data->template);*/
$template_data->template = str_replace("{remark}",$all_detail['operation_list'][0]->remarks,$template_data->template);

$template_data->template = str_replace("{operation_package}",$all_detail['operation_list'][0]->op_name,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['operation_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['operation_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{total_discount}",$all_detail['operation_list'][0]->discount_amount,$template_data->template);
$template_data->template = str_replace("{paid_amount}",$all_detail['operation_list'][0]->paid_amount,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['operation_list'][0]->balance_amount,$template_data->template);

$template_data->template = str_replace("{amount}",$all_detail['operation_list'][0]->op_charge,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$all_detail['operation_list'][0]->payment_mode,$template_data->template);
/*

    if($all_detail['operation_list'][0]->address!='' || $all_detail['operation_list'][0]->address2!='' || $all_detail['operation_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['operation_list'][0]->address),explode ( $del , $all_detail['operation_list'][0]->address2),explode ( $del , $all_detail['operation_list'][0]->address3));
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
    $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);*/

    $template_data->template = str_replace("{patient_name}",$all_detail['operation_list'][0]->simulation.' '.$all_detail['operation_list'][0]->patient_name,$template_data->template);
    //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{s_no}",1,$template_data->template);
    $template_data->template = str_replace("{package_type}",$all_detail['operation_list'][0]->operation_type,$template_data->template);
    $template_data->template = str_replace("{package_days}",$all_detail['operation_list'][0]->days,$template_data->template);
    $template_data->template = str_replace("{package_name}",$all_detail['operation_list'][0]->package_name,$template_data->template);
    $template_data->template = str_replace("{package_amount}",$all_detail['operation_list'][0]->package_amount,$template_data->template);
    $template_data->template = str_replace("{package_remarks}",$all_detail['operation_list'][0]->pacakge_remarks,$template_data->template);
    // if($date_time_status==1)
    // {
        if($all_detail['operation_list'][0]->operation_time=="00:00:00")
                {
                    $operation_time='';
                }
                else
                {
                    $operation_time=date('h:i A',strtotime($all_detail['operation_list'][0]->operation_time));
                }
        $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.$operation_time,$template_data->template);
    // }
    // else
    // {
    //     $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)),$template_data->template);
    // }
    $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{operation_date_time}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.$operation_time,$template_data->template);



$template_data->template = str_replace("{operation_package}",$all_detail['operation_list'][0]->op_name,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['operation_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['operation_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{total_discount}",$all_detail['operation_list'][0]->discount_amount,$template_data->template);
$template_data->template = str_replace("{paid_amount}",$all_detail['operation_list'][0]->paid_amount,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['operation_list'][0]->balance_amount,$template_data->template);

$template_data->template = str_replace("{amount}",$all_detail['operation_list'][0]->op_charge,$template_data->template);


    $genders = array('0'=>'F','1'=>'M','2'=>'O');
    $gender = $genders[$all_detail['operation_list'][0]->gender];
    $age_y = $all_detail['operation_list'][0]->age_y; 
    $age_m = $all_detail['operation_list'][0]->age_m;
    $age_d = $all_detail['operation_list'][0]->age_d;

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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

    if($user_detail['users_role']==4)
    {
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
    }
    else
    {
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['operation_list'][0]->user_name),$template_data->template);    
    }
    $template_data->template = str_replace("{doctor_name}",$doctor_name,$template_data->template);
$this->session->unset_userdata('ot_book_id');
    echo $template_data->template;

    

}
/* end dot printing */


/* start leaser printing */
if($template_data->printer_id==1){
 
          if((!empty($all_detail['operation_list'][0]->reciept_suffix) || !empty($all_detail['operation_list'][0]->reciept_prefix)))
          {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['operation_list'][0]->reciept_prefix.$all_detail['operation_list'][0]->reciept_suffix,$template_data->template);
          }
          else
          {
             $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
          }


            if(!empty($all_detail['operation_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['operation_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['operation_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

            if(!empty($all_detail['operation_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['operation_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['operation_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }


    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['operation_list'][0]->patient_code,$template_data->template);

$template_data->template = str_replace("{ipd_no}",$all_detail['operation_list'][0]->ipd_no,$template_data->template);
$template_data->template = str_replace("{room_no}",$all_detail['operation_list'][0]->ot_room,$template_data->template);
/*$template_data->template = str_replace("{bed_no}",$all_detail['operation_list'][0]->bed_no,$template_data->template);*/
$template_data->template = str_replace("{remark}",$all_detail['operation_list'][0]->remarks,$template_data->template);

$template_data->template = str_replace("{operation_package}",$all_detail['operation_list'][0]->op_name,$template_data->template);

$template_data->template = str_replace("{paid_amount}",$all_detail['operation_list'][0]->paid_amount,$template_data->template);

$template_data->template = str_replace("{net_amount}",$all_detail['operation_list'][0]->net_amount,$template_data->template);
$template_data->template = str_replace("{total_discount}",$all_detail['operation_list'][0]->discount_amount,$template_data->template);
$template_data->template = str_replace("{paid_amount}",$all_detail['operation_list'][0]->paid_amount,$template_data->template);
$template_data->template = str_replace("{balance}",$all_detail['operation_list'][0]->balance_amount,$template_data->template);

$template_data->template = str_replace("{amount}",$all_detail['operation_list'][0]->op_charge,$template_data->template);

$template_data->template = str_replace("{payment_mode}",$all_detail['operation_list'][0]->payment_mode,$template_data->template);


    /*if($all_detail['operation_list'][0]->address!='' || $all_detail['operation_list'][0]->address2!='' || $all_detail['operation_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['operation_list'][0]->address),explode ( $del , $all_detail['operation_list'][0]->address2),explode ( $del , $all_detail['operation_list'][0]->address3));
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
    $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);*/

    $template_data->template = str_replace("{patient_name}",$all_detail['operation_list'][0]->simulation.' '.$all_detail['operation_list'][0]->patient_name,$template_data->template);
    //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{s_no}",1,$template_data->template);
    $template_data->template = str_replace("{package_type}",$all_detail['operation_list'][0]->operation_type,$template_data->template);
    $template_data->template = str_replace("{package_days}",$all_detail['operation_list'][0]->days,$template_data->template);
    $template_data->template = str_replace("{package_name}",$all_detail['operation_list'][0]->package_name,$template_data->template);
    $template_data->template = str_replace("{package_amount}",$all_detail['operation_list'][0]->package_amount,$template_data->template);
    $template_data->template = str_replace("{package_remarks}",$all_detail['operation_list'][0]->pacakge_remarks,$template_data->template);
    // if($date_time_status==1)
    // {
        if($all_detail['operation_list'][0]->operation_time=="00:00:00")
                {
                    $operation_time='';
                }
                else
                {
                    $operation_time=date('h:i A',strtotime($all_detail['operation_list'][0]->operation_time));
                }
        $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.$operation_time,$template_data->template);
    // }
    // else
    // {
    //     $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)),$template_data->template);
    // }
    $template_data->template = str_replace("{mobile_no}",$all_detail['operation_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{operation_date_time}",date('d-m-Y',strtotime($all_detail['operation_list'][0]->operation_date)).' '.$operation_time,$template_data->template);


    $genders = array('0'=>'F','1'=>'M','2'=>'O');
    $gender = $genders[$all_detail['operation_list'][0]->gender];
    $age_y = $all_detail['operation_list'][0]->age_y; 
    $age_m = $all_detail['operation_list'][0]->age_m;
    $age_d = $all_detail['operation_list'][0]->age_d;

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

    $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);

    if($user_detail['users_role']==4)
    {
    $template_data->template = str_replace("{signature}",ucfirst($user_detail['username']),$template_data->template);   
    }
    else
    {
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['operation_list'][0]->user_name),$template_data->template);    
    }
    $template_data->template = str_replace("{doctor_name}",$doctor_name,$template_data->template);
$this->session->unset_userdata('ot_book_id');
	echo $template_data->template;
}

/* end leaser printing*/
?>

<?php
if(!empty($download_type) && $download_type==2)
{
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="<?php echo ROOT_JS_PATH; ?>html2canvas.js"></script>
<script>
 //$(function(){
  //$("#gimg").click(function(){

    //$('#gimg').hide();
$(document).ready(function() { 
     html2canvas($("page"), {
        onrendered: function(canvas) {
           var imgsrc = canvas.toDataURL("image/png");
            $.ajax({
                url:'<?php echo base_url('ot_booking/save_image')?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['operation_list'][0]->patient_name; ?>",
                    patient_code:"<?php echo $all_detail['operation_list'][0]->patient_code; ?>"
                    },
                success: function(result)
                {
                    //alert(result); return 1;
                     location.href =result;
                //var dt = canvas.toDataURL();
               // this.href = dt; //this may not work in the future..
 

                    //var opened = view.open(object_url, "_blank");
                    //view.location.href = object_url;
                    //var dataURL = $canvas[0].toDataURL('image/png');
                    //w.document.write("<img src='" + dataURL + "' alt='from canvas'/>");
                }

                
            });
           
        }
     });
  });   
  //});  
  //});
</script>
<?php } ?>

