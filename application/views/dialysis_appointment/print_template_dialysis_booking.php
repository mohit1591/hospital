<?php 
$del = ','; 
$address_n='';
$address_re='';
 $dialysis_time='';
 //echo $template_data->printer_id;
/* start thermal printing */
if($template_data->printer_id==2)
{

            if(!empty($all_detail['dialysis_list'][0]->relation))
            {
                $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
                $template_data->template = str_replace("{parent_relation_type}",$all_detail['dialysis_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

        if(!empty($all_detail['dialysis_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['dialysis_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }


        $template_data->template = str_replace("{pateint_reg_no}",$all_detail['dialysis_list'][0]->patient_code,$template_data->template);
        $template_data->template = str_replace("{patient_name}",$all_detail['dialysis_list'][0]->simulation.' '.$all_detail['dialysis_list'][0]->patient_name,$template_data->template);
        $template_data->template = str_replace("{mobile_no}",$all_detail['dialysis_list'][0]->mobile_no,$template_data->template);
        $template_data->template = str_replace("{s_no}",1,$template_data->template);
        //$template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->package_name,$template_data->template);

        if(!empty($all_detail['dialysis_list'][0]->dialysis_type))
    {
        $template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dialysis_type,$template_data->template);
    
    }
    else
    {
        $template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dia_type,$template_data->template); 
    }
    if(!empty($all_detail['dialysis_list'][0]->days))
    {
        $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->days,$template_data->template);  
    }
    else
    {
        $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->hours,$template_data->template);
    }

    if(!empty($all_detail['dialysis_list'][0]->package_name))
    {
        $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->package_name,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->dia_name,$template_data->template);
    
    }

    if(!empty($all_detail['dialysis_list'][0]->package_amount))
    {
        $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->package_amount,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->dia_amount,$template_data->template);
    }

    if(!empty($all_detail['dialysis_list'][0]->pacakge_remarks))
    {
        $template_data->template = str_replace("{package_remarks}",$all_detail['dialysis_list'][0]->pacakge_remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{package_remarks}",'',$template_data->template);
    }
        // if($date_time_status==1)
        // {
            if($all_detail['dialysis_list'][0]->dialysis_time=="00:00:00")
            {
                $dialysis_time='';
            }
            else
            {
                $dialysis_time=date('h:i A',strtotime($all_detail['dialysis_list'][0]->dialysis_time));
            }
            $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)).' '.$dialysis_time,$template_data->template);
        // }
        // else
        // {
        //     $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)),$template_data->template);
        // }
        $genders = array('0'=>'F','1'=>'M','2'=>'O');
        $gender = $genders[$all_detail['dialysis_list'][0]->gender];
        $age_y = $all_detail['dialysis_list'][0]->age_y; 
        $age_m = $all_detail['dialysis_list'][0]->age_m;
        $age_d = $all_detail['dialysis_list'][0]->age_d;

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
          $template_data->template = str_replace("{signature}",ucfirst($all_detail['dialysis_list'][0]->user_name),$template_data->template);    
        }
         echo $template_data->template; 

}
/* end thermal printing */
/* start dot printing */
if($template_data->printer_id==3)
{
    

    if(!empty($all_detail['dialysis_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['dialysis_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

        if(!empty($all_detail['dialysis_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['dialysis_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }

    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['dialysis_list'][0]->patient_code,$template_data->template);
        
    $template_data->template = str_replace("{patient_name}",$all_detail['dialysis_list'][0]->simulation.' '.$all_detail['dialysis_list'][0]->patient_name,$template_data->template);
     //$page = str_replace("{mobile_no}",$get_by_id_data['sales_list'][0]->mobile_no,$template_format->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['dialysis_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{s_no}",1,$template_data->template);
    
    if(!empty($all_detail['dialysis_list'][0]->dialysis_type))
    {
        $template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dialysis_type,$template_data->template);
    
    }
    else
    {
        $template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dia_type,$template_data->template); 
    }
    if(!empty($all_detail['dialysis_list'][0]->days))
    {
        $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->days,$template_data->template);  
    }
    else
    {
        $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->hours,$template_data->template);
    }

    if(!empty($all_detail['dialysis_list'][0]->package_name))
    {
        $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->package_name,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->dia_name,$template_data->template);
    
    }

    if(!empty($all_detail['dialysis_list'][0]->package_amount))
    {
        $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->package_amount,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->dia_amount,$template_data->template);
    }

    if(!empty($all_detail['dialysis_list'][0]->pacakge_remarks))
    {
        $template_data->template = str_replace("{package_remarks}",$all_detail['dialysis_list'][0]->pacakge_remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{package_remarks}",'',$template_data->template);
    }
    
    
    
    // if($date_time_status==1)
    // {
          if($all_detail['dialysis_list'][0]->dialysis_time=="00:00:00")
            {
                $dialysis_time='';
            }
            else
            {
                $dialysis_time=date('h:i A',strtotime($all_detail['dialysis_list'][0]->dialysis_time));
            }
          
        $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)).' '.$dialysis_time,$template_data->template);
    
    $template_data->template = str_replace("{mobile_no}",$all_detail['dialysis_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{dialysis_date_time}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)).' '.$dialysis_time,$template_data->template);
    $genders = array('0'=>'F','1'=>'M','2'=>'O');
            $gender = $genders[$all_detail['dialysis_list'][0]->gender];
            $age_y = $all_detail['dialysis_list'][0]->age_y; 
            $age_m = $all_detail['dialysis_list'][0]->age_m;
            $age_d = $all_detail['dialysis_list'][0]->age_d;

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
            foreach($all_detail['dialysis_list']['doctor_list']as $doctor_list){
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
      $template_data->template = str_replace("{signature}",ucfirst($all_detail['dialysis_list'][0]->user_name),$template_data->template);    
    }
    $template_data->template = str_replace("{doctor_name}",$doctor_name,$template_data->template);
    echo $template_data->template; exit;

}
/* end dot printing */
/* start leaser printing */
if($template_data->printer_id==1)
{
      

   // if(!empty($all_detail['dialysis_list'][0]->relation_name))
   //  {
   //      $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
   //       $template_data->template = str_replace("{parent_relation_type}",$all_detail['dialysis_list'][0]->relation.' '. $rel_simulation.' '.$all_detail['dialysis_list'][0]->relation_name,$template_data->template);
   //  }
   //  else
   //  {
   //     $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
   //  }

    if(!empty($all_detail['dialysis_list'][0]->relation))
            {
            $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_type}",$all_detail['dialysis_list'][0]->relation,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
            }

        if(!empty($all_detail['dialysis_list'][0]->relation_name))
            {
            $rel_simulation = get_simulation_name($all_detail['dialysis_list'][0]->relation_simulation_id);
            $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['dialysis_list'][0]->relation_name,$template_data->template);
            }
            else
            {
             $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
            }

    
    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['dialysis_list'][0]->patient_code,$template_data->template);
    $template_data->template = str_replace("{ipd_no}",$all_detail['dialysis_list'][0]->ipd_no,$template_data->template);
    $template_data->template = str_replace("{room_no}",$all_detail['dialysis_list'][0]->room_no,$template_data->template);
    $template_data->template = str_replace("{bed_no}",$all_detail['dialysis_list'][0]->bed_no,$template_data->template);
    $template_data->template = str_replace("{note}",$all_detail['dialysis_list'][0]->remarks,$template_data->template);
    $template_data->template = str_replace("{dialysis}",$all_detail['dialysis_list'][0]->dia_name,$template_data->template);

    if($all_detail['dialysis_list'][0]->address!='' || $all_detail['dialysis_list'][0]->address2!='' || $all_detail['dialysis_list'][0]->address3!='')
    {
        $address_n = array_merge(explode ( $del , $all_detail['dialysis_list'][0]->address),explode ( $del , $all_detail['dialysis_list'][0]->address2),explode ( $del , $all_detail['dialysis_list'][0]->address3));
    }
     if(!empty($address_n))
     {
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

    $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);


    $template_data->template = str_replace("{patient_name}",$all_detail['dialysis_list'][0]->simulation.' '.$all_detail['dialysis_list'][0]->patient_name,$template_data->template);
    $template_data->template = str_replace("{mobile_no}",$all_detail['dialysis_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{s_no}",1,$template_data->template);
    
    if(!empty($all_detail['dialysis_list'][0]->dialysis_type))
    {
        $template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dialysis_type,$template_data->template);
    
    }
    else
    {
        $template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dia_type,$template_data->template); 
    }
    if(!empty($all_detail['dialysis_list'][0]->days))
    {
        $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->days,$template_data->template);  
    }
    else
    {
        $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->hours,$template_data->template);
    }

    if(!empty($all_detail['dialysis_list'][0]->package_name))
    {
        $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->package_name,$template_data->template);
    }
    else
    {
        $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->dia_name,$template_data->template);
    
    }

    if(!empty($all_detail['dialysis_list'][0]->package_amount))
    {
        $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->package_amount,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->dia_amount,$template_data->template);
    }

    if(!empty($all_detail['dialysis_list'][0]->pacakge_remarks))
    {
        $template_data->template = str_replace("{package_remarks}",$all_detail['dialysis_list'][0]->pacakge_remarks,$template_data->template);
    }
    else
    {
       $template_data->template = str_replace("{package_remarks}",'',$template_data->template);
    }


    /*$template_data->template = str_replace("{package_type}",$all_detail['dialysis_list'][0]->dialysis_type,$template_data->template);
    $template_data->template = str_replace("{package_days}",$all_detail['dialysis_list'][0]->days,$template_data->template);
    $template_data->template = str_replace("{package_name}",$all_detail['dialysis_list'][0]->package_name,$template_data->template);
    $template_data->template = str_replace("{package_amount}",$all_detail['dialysis_list'][0]->package_amount,$template_data->template);
    $template_data->template = str_replace("{package_remarks}",$all_detail['dialysis_list'][0]->pacakge_remarks,$template_data->template);*/
    //$template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)),$template_data->template);

    // if($date_time_status==1)
    // {
        if($all_detail['dialysis_list'][0]->dialysis_time=="00:00:00")
        {
        $dialysis_time='';
        }
        else
        {
        $dialysis_time=date('h:i A',strtotime($all_detail['dialysis_list'][0]->dialysis_time));
        }


        $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)).' '.$dialysis_time,$template_data->template);
    // }
    // else
    // {
    //     $template_data->template = str_replace("{reciept_date}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)),$template_data->template);
    // }
    $template_data->template = str_replace("{mobile_no}",$all_detail['dialysis_list'][0]->mobile_no,$template_data->template);
    $template_data->template = str_replace("{dialysis_date_time}",date('d-m-Y',strtotime($all_detail['dialysis_list'][0]->dialysis_date)).' '.$dialysis_time,$template_data->template);


    $genders = array('0'=>'F','1'=>'M','2'=>'O');
    $gender = $genders[$all_detail['dialysis_list'][0]->gender];
    $age_y = $all_detail['dialysis_list'][0]->age_y; 
    $age_m = $all_detail['dialysis_list'][0]->age_m;
    $age_d = $all_detail['dialysis_list'][0]->age_d;

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
    foreach($all_detail['dialysis_list']['doctor_list']as $doctor_list){
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
    $template_data->template = str_replace("{signature}",ucfirst($all_detail['dialysis_list'][0]->user_name),$template_data->template);    
    }
    $template_data->template = str_replace("{doctor_name}",$doctor_name,$template_data->template);

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
                url:'<?php echo base_url('dialysis_booking/save_image')?>', 
                type:'POST', 
                data:{
                    data:imgsrc,
                    patient_name:"<?php echo $all_detail['dialysis_list'][0]->patient_name; ?>",
                    patient_code:"<?php echo $all_detail['dialysis_list'][0]->patient_code; ?>"
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

