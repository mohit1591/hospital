<?php 
$users_data = $this->session->userdata('auth_users');
$del = ','; 
$address_n='';
$address_re='';

      if($template_data->printer_id==2){

      if(!empty($all_detail['prescription_print_list'][0]->relation))
      {
      $rel_simulation = get_simulation_name($all_detail['prescription_print_list'][0]->relation_simulation_id);
      $template_data->template = str_replace("{parent_relation_type}",$all_detail['prescription_print_list'][0]->relation,$template_data->template);
      }
      else
      {
      $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
      }
      if(!empty($all_detail['prescription_print_list'][0]->relation_name))
      {
      $rel_simulation = get_simulation_name($all_detail['prescription_print_list'][0]->relation_simulation_id);
      $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['prescription_print_list'][0]->relation_name,$template_data->template);
      }
      else
      {
      $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
      }

      $template_data->template = str_replace("{patient_name}",$all_detail['prescription_print_list'][0]->simulation.' '.$all_detail['prescription_print_list'][0]->patient_name,$template_data->template);
      $template_data->template = str_replace("{pateint_reg_no}",$all_detail['prescription_print_list'][0]->patient_code,$template_data->template);

      $template_data->template = str_replace("{mobile_no}",$all_detail['prescription_print_list'][0]->mobile_no,$template_data->template);
      if($all_detail['prescription_print_list'][0]->address!='' || $all_detail['prescription_print_list'][0]->address2!='' || $all_detail['prescription_print_list'][0]->address3!='')
      {
      $address_n = array_merge(explode ( $del , $all_detail['prescription_print_list'][0]->address),explode ( $del , $all_detail['prescription_print_list'][0]->address2),explode ( $del , $all_detail['prescription_print_list'][0]->address3));
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
      $patient_address = implode(',',$address_re).' - '.$all_detail['prescription_print_list'][0]->pincode;
      }
      else
      {
      $patient_address='';
      }
      $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);

      $genders = array('0'=>'F','1'=>'M','2'=>'O');
      $gender = $genders[$all_detail['prescription_print_list'][0]->gender];
      $age_y = $all_detail['prescription_print_list'][0]->age_y; 
      $age_m = $all_detail['prescription_print_list'][0]->age_m;
      $age_d = $all_detail['prescription_print_list'][0]->age_d;

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

      $template_data->template = str_replace("{address}",$patient_address,$template_data->template);
      $template_data->template = str_replace("{patient_code}",$all_detail['prescription_print_list'][0]->patient_code,$template_data->template);
      $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);


      $template_data->template = str_replace("{date}",date('d-m-Y H:i:s',strtotime($all_detail['prescription_print_list'][0]->created_date)),$template_data->template);

      $i=1;
      if(!empty($payment_mode['balance']))
      {
      $balance='';
      if($payment_mode['balance']=='1')
      {
      $balance=0;
      }
      else
      {
      $balance=$payment_mode['balance'];
      }
      }
      $pos_start = strpos($template_data->template, '{start_loop}');
      $pos_end = strpos($template_data->template, '{end_loop}');
      $row_last_length = $pos_end-$pos_start;
      $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);
      // Replace looping row//
      $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+10));
      $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);

      $i=1;
      $tr_html = "";
      foreach($all_detail['prescription_print_list']['vaccine_list'] as $vaccine_list)
      { 
      $tr = $row_loop;
      $tr = str_replace("{s_no}",$i,$tr);
      $tr = str_replace("{vaccine_name}",$vaccine_list->vaccination_name,$tr);
      $tr = str_replace("{qty}",'',$tr);
      $tr = str_replace("{mrp}",$vaccine_list->mrp,$tr);
      $tr_html .= $tr;
      $i++;

      }
      $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
      //$template_data->template = str_replace("{age}",$all_detail['prescription_print_list'][0]->title,$template_data->template);

      //$template_data->template = str_replace("{age}",'',$template_data->template);

      $template_data->template = str_replace("{paid_amount}",number_format($payment_mode['paid_amount'],2),$template_data->template);
      $template_data->template = str_replace("{balance}",number_format($balance,2),$template_data->template);
      $template_data->template = str_replace("{total_amount_full}",number_format($payment_mode['total_amount'],2),$template_data->template);



      if(in_array('276',$users_data['permission']['section']))
      {
      if($all_detail['prescription_print_list'][0]->paid_amount>0 && (!empty($all_detail['prescription_print_list'][0]->reciept_prefix) || !empty($all_detail['prescription_print_list'][0]->reciept_suffix)) )
      {
      $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['prescription_print_list'][0]->reciept_prefix.$all_detail['prescription_print_list'][0]->reciept_suffix,$template_data->template);
      }
      }
      else
      {
      $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
      }


      $template_data->template = str_replace("{tot_discount}",$payment_mode['discount_amount'],$template_data->template);
      $template_data->template = str_replace("{net_amount}",number_format($payment_mode['net_amount'],2),$template_data->template);

      $template_data->template = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$template_data->template);
      $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);
        $template_data->template = str_replace("{tot_cgst}",$all_detail['prescription_print_list'][0]->cgst,$template_data->template);
        $template_data->template = str_replace("{tot_sgst}",$all_detail['prescription_print_list'][0]->sgst,$template_data->template);
        $template_data->template = str_replace("{tot_igst}",$all_detail['prescription_print_list'][0]->igst,$template_data->template);

      if(!empty($all_detail['prescription_print_list'][0]->remarks))
      {
      $template_data->template = str_replace("{remarks}",$all_detail['prescription_print_list'][0]->remarks,$template_data->template);
      }
      else
      {
      $template_data->template = str_replace("{remarks}",' ',$template_data->template);
      $template_data->template = str_replace("Remarks :",' ',$template_data->template);
      $template_data->template = str_replace("Remarks",' ',$template_data->template);


      }
      $template_data->template = str_replace("Age",' ',$template_data->template);
      //$template_data->template = str_replace("QTY",' ',$template_data->template);
      echo $template_data->template; 

}
/* end thermal printing */





/* start dot printing */
  if($template_data->printer_id==3){ 
    

      if(!empty($all_detail['prescription_print_list'][0]->relation))
      {
      $rel_simulation = get_simulation_name($all_detail['prescription_print_list'][0]->relation_simulation_id);
      $template_data->template = str_replace("{parent_relation_type}",$all_detail['prescription_print_list'][0]->relation,$template_data->template);
      }
      else
      {
      $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
      }
      if(!empty($all_detail['prescription_print_list'][0]->relation_name))
      {
      $rel_simulation = get_simulation_name($all_detail['prescription_print_list'][0]->relation_simulation_id);
      $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['prescription_print_list'][0]->relation_name,$template_data->template);
      }
      else
      {
      $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
      }

    $template_data->template = str_replace("{patient_name}",$all_detail['prescription_print_list'][0]->simulation.' '.$all_detail['prescription_print_list'][0]->patient_name,$template_data->template);
    $template_data->template = str_replace("{pateint_reg_no}",$all_detail['prescription_print_list'][0]->patient_code,$template_data->template);

    $template_data->template = str_replace("{mobile_no}",$all_detail['prescription_print_list'][0]->mobile_no,$template_data->template);
    if($all_detail['prescription_print_list'][0]->address!='' || $all_detail['prescription_print_list'][0]->address2!='' || $all_detail['prescription_print_list'][0]->address3!='')
      {
          $address_n = array_merge(explode ( $del , $all_detail['prescription_print_list'][0]->address),explode ( $del , $all_detail['prescription_print_list'][0]->address2),explode ( $del , $all_detail['prescription_print_list'][0]->address3));
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
          $patient_address = implode(',',$address_re).' - '.$all_detail['prescription_print_list'][0]->pincode;
       }
       else
       {
          $patient_address='';
       }
  $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);

       $genders = array('0'=>'F','1'=>'M','2'=>'O');
          $gender = $genders[$all_detail['prescription_print_list'][0]->gender];
          $age_y = $all_detail['prescription_print_list'][0]->age_y; 
          $age_m = $all_detail['prescription_print_list'][0]->age_m;
          $age_d = $all_detail['prescription_print_list'][0]->age_d;

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

  $template_data->template = str_replace("{address}",$patient_address,$template_data->template);
   $template_data->template = str_replace("{patient_code}",$all_detail['prescription_print_list'][0]->patient_code,$template_data->template);
   $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
 $template_data->template = str_replace("{tot_cgst}",$all_detail['prescription_print_list'][0]->cgst,$template_data->template);
        $template_data->template = str_replace("{tot_sgst}",$all_detail['prescription_print_list'][0]->sgst,$template_data->template);
        $template_data->template = str_replace("{tot_igst}",$all_detail['prescription_print_list'][0]->igst,$template_data->template);
    
  $template_data->template = str_replace("{date}",date('d-m-Y H:i:s',strtotime($all_detail['prescription_print_list'][0]->created_date)),$template_data->template);
  //////////////////////// 
  $i=1;
  if(!empty($payment_mode['balance']))
  {
    $balance='';
    if($payment_mode['balance']=='1')
    {
      $balance=0;
    }
    else
    {
      $balance=$payment_mode['balance'];
    }
  }

  $i=1;
  $j=0;
  $tr_html = "";
  $total_quantity_amt=0;
  $total_discount_amt=0;
  $total_vat_amt=0;
  $total_mrp=0;
  $tot_medicine=0;
  $total_cgst=0;
  $total_sgst=0;
  $total_igst=0;
  $pos_start = strpos($template_data->template, '{start_loop}');
  $pos_end = strpos($template_data->template, '{end_loop}');
  $row_last_length = $pos_end-$pos_start;
  $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

  // Replace looping row//
  $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));
  $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
  foreach($all_detail['prescription_print_list']['vaccine_list'] as $medicine_list)
  { 
    $exp_date='';
      
      if($medicine_list->m_expiry_date!='1970-01-01' && $medicine_list->m_expiry_date!='0000-00-00') 
      {
          $exp_date = date('d-m-Y',strtotime($medicine_list->m_expiry_date));
      }
      
      $tr = $row_loop;
    $tot_medicine=$tot_medicine+$i;
    $tr = str_replace("{s_no}",$i,$tr);
    $total_quantity_amt=$total_quantity_amt+$medicine_list->qty;
    $total_discount_amt=$total_discount_amt+$medicine_list->discount;
    $total_vat_amt=$total_vat_amt+$medicine_list->vat;
      $total_vat_amt=$total_vat_amt+$medicine_list->vat;
      $total_cgst=$total_cgst+$medicine_list->m_cgst;
      $total_sgst=$total_sgst+$medicine_list->m_sgst;
      $total_igst=$total_igst+$medicine_list->m_igst;
    $total_mrp=$total_mrp+$medicine_list->mrp;
    $tr = str_replace("{vaccine_qty}",$medicine_list->qty,$tr);
    $tr = str_replace("{vaccine_per_discount}",$medicine_list->m_discount,$tr);
    $tr = str_replace("{vaccine_per_vat}",$medicine_list->vat,$tr);
    $tr = str_replace("{vaccine_per_price}",$medicine_list->per_pic_price,$tr);
    $tr = str_replace("{batch_no}",$medicine_list->m_batch_no,$tr);
     $tr = str_replace("{exp_date}",$exp_date,$tr);
      $tr = str_replace("{hsn_no}",$medicine_list->m_hsn_no,$tr);
       $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
        $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
        $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
    $tr = str_replace("{vaccine_name}",$medicine_list->vaccination_name,$tr);
    $tr = str_replace("{vaccine_per_net_amount}",$medicine_list->mrp,$tr);
    $tr_html .= $tr;
    $i++;
    $j++;

  }

  $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
  // $template_data->template = str_replace("{s_no}",$i,$template_data->template);
  // $template_data->template = str_replace("{vaccine_name}",$patient_detail['vaccination_name'],$template_data->template);

  // //$template_data->template = str_replace("{qty}",$patient_detail['qty'],$template_data->template);

  // $template_data->template = str_replace("{qty}",'',$template_data->template);

  // $template_data->template = str_replace("{total_amount}",number_format($patient_detail['total_amount'],2),$template_data->template);

  //$template_data->template = str_replace("{age}",$all_detail['prescription_print_list'][0]->title,$template_data->template);

  $template_data->template = str_replace("{age}",'',$template_data->template);



  //$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);


  $template_data->template = str_replace("{paid_amount}",number_format($payment_mode['paid_amount'],2),$template_data->template);
  $template_data->template = str_replace("{balance}",number_format($balance,2),$template_data->template);
  $template_data->template = str_replace("{total_amount_full}",number_format($payment_mode['total_amount'],2),$template_data->template);



   if(in_array('276',$users_data['permission']['section']))
        {
        if($all_detail['prescription_print_list'][0]->paid_amount>0 && (!empty($all_detail['prescription_print_list'][0]->reciept_prefix) || !empty($all_detail['prescription_print_list'][0]->reciept_suffix)) )
        {
            $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['prescription_print_list'][0]->reciept_prefix.$all_detail['prescription_print_list'][0]->reciept_suffix,$template_data->template);
        }
        }
        else
        {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
        }


  $template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);
  $template_data->template = str_replace("{total_net}",number_format($payment_mode['net_amount'],2),$template_data->template);

  $template_data->template = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$template_data->template);
  $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

  if(!empty($all_detail['prescription_print_list'][0]->remarks))
          {
             $template_data->template = str_replace("{remarks}",$all_detail['prescription_print_list'][0]->remarks,$template_data->template);
          }
          else
          {
             $template_data->template = str_replace("{remarks}",' ',$template_data->template);
             $template_data->template = str_replace("Remarks :",' ',$template_data->template);
             $template_data->template = str_replace("Remarks",' ',$template_data->template);

             
          }

          $template_data->template = str_replace("Age",' ',$template_data->template);

          //$template_data->template = str_replace("QTY",' ',$template_data->template);



          //$template_data->template = str_replace("Discount (%):",' ',$template_data->template);
           //$template_data->template = str_replace("discount_percent:",' ',$template_data->template);
          
    echo $template_data->template;
    //exit;
}


if($template_data->printer_id==1)
  {

    //echo"<pre>";print_r($all_detail);

      if(!empty($all_detail['prescription_print_list'][0]->relation))
      {
        $rel_simulation = get_simulation_name($all_detail['prescription_print_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_type}",$all_detail['prescription_print_list'][0]->relation,$template_data->template);
      }
      else
      {
        $template_data->template = str_replace("{parent_relation_type}",'',$template_data->template);
      }

      if(!empty($all_detail['prescription_print_list'][0]->relation_name))
      {
        $rel_simulation = get_simulation_name($all_detail['prescription_print_list'][0]->relation_simulation_id);
        $template_data->template = str_replace("{parent_relation_name}",$rel_simulation.' '.$all_detail['prescription_print_list'][0]->relation_name,$template_data->template);
      }
      else
      {
        $template_data->template = str_replace("{parent_relation_name}",'',$template_data->template);
      }

     $template_data->template = str_replace("{patient_name}",$all_detail['prescription_print_list'][0]->simulation.' '.$all_detail['prescription_print_list'][0]->patient_name,$template_data->template);
     $template_data->template = str_replace("{pateint_reg_no}",$all_detail['prescription_print_list'][0]->patient_code,$template_data->template);

    $template_data->template = str_replace("{mobile_no}",$all_detail['prescription_print_list'][0]->mobile_no,$template_data->template);

    if($all_detail['prescription_print_list'][0]->address!='' || $all_detail['prescription_print_list'][0]->address2!='' || $all_detail['prescription_print_list'][0]->address3!='')
      {
          $address_n = array_merge(explode ( $del , $all_detail['prescription_print_list'][0]->address),explode ( $del , $all_detail['prescription_print_list'][0]->address2),explode ( $del , $all_detail['prescription_print_list'][0]->address3));
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
          $patient_address = implode(',',$address_re).' - '.$all_detail['prescription_print_list'][0]->pincode;
       }
       else
       {
          $patient_address='';
       }
       
  $template_data->template = str_replace("{pateint_address}",$patient_address,$template_data->template);

          $genders = array('0'=>'F','1'=>'M','2'=>'O');
          $gender = $genders[$all_detail['prescription_print_list'][0]->gender];
          $age_y = $all_detail['prescription_print_list'][0]->age_y; 
          $age_m = $all_detail['prescription_print_list'][0]->age_m;
          $age_d = $all_detail['prescription_print_list'][0]->age_d;

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
          if($patient_age!='')
          {
            $patient1_age = '/'.$patient_age;
          }
          if($patient_age=='')
          {
            $patient1_age=$patient_age;
          }
          $gender_age = $gender.$patient1_age ;

      $template_data->template = str_replace("{address}",$patient_address,$template_data->template);
      $template_data->template = str_replace("{patient_code}",$all_detail['prescription_print_list'][0]->patient_code,$template_data->template);
      $template_data->template = str_replace("{gender_age}",$gender_age,$template_data->template);
      $template_data->template = str_replace("{tot_cgst}",$all_detail['prescription_print_list'][0]->cgst,$template_data->template);
      $template_data->template = str_replace("{tot_sgst}",$all_detail['prescription_print_list'][0]->sgst,$template_data->template);
      $template_data->template = str_replace("{tot_igst}",$all_detail['prescription_print_list'][0]->igst,$template_data->template);
    
  $template_data->template = str_replace("{date}",date('d-m-Y H:i:s',strtotime($all_detail['prescription_print_list'][0]->created_date)),$template_data->template);

  $i=1;
  if(!empty($payment_mode['balance']))
  {
    $balance='';
    if($payment_mode['balance']=='1')
    {
      $balance=0;
    }
    else
    {
      $balance=$payment_mode['balance'];
    }
  }

  $pos_start = strpos($template_data->template, '{start_loop}');
  $pos_end = strpos($template_data->template, '{end_loop}');
  $row_last_length = $pos_end-$pos_start;
  $row_loop = substr($template_data->template,$pos_start+12,$row_last_length-12);

  // Replace looping row//
  $rplc_row = trim(substr($template_data->template,$pos_start,$row_last_length+12));

  $template_data->template = str_replace($rplc_row,"{row_data}",$template_data->template);
  //////////////////////// 
  $i=1;
  $tr_html = "";
  foreach($all_detail['prescription_print_list']['vaccine_list'] as $medicine_list)
  { 

    //print_r($medicine_list);
      $exp_date='';
      if($medicine_list->m_expiry_date!='1970-01-01' && $medicine_list->m_expiry_date!='0000-00-00') 
      {
          $exp_date = date('d-m-Y',strtotime($medicine_list->m_expiry_date));
      }
          $tr = $row_loop;
          $tr = str_replace("{s_no}",$i,$tr);
          $tr = str_replace("{quantity}",$medicine_list->qty,$tr);
          $tr = str_replace("{mrp}",$medicine_list->per_pic_price,$tr);
          $tr = str_replace("{vaccine_name}",$medicine_list->vaccination_name,$tr);
          $tr = str_replace("{cgst}",$medicine_list->m_cgst,$tr);
          $tr = str_replace("{sgst}",$medicine_list->m_sgst,$tr);
          $tr = str_replace("{batch_no}",$medicine_list->m_batch_no,$tr);
          $tr = str_replace("{exp_date}",$exp_date,$tr);
          $tr = str_replace("{hsn_no}",$medicine_list->m_hsn_no,$tr);
          $tr = str_replace("{igst}",$medicine_list->m_igst,$tr);
          $tr = str_replace("{discount}",$medicine_list->m_discount,$tr);
          $tr = str_replace("{total_amount}",$medicine_list->total_amount,$tr);
          $tr_html .= $tr;
          $i++;

  }

  //echo $i;
  $template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);


  //die;
  //$template_data->template = str_replace("{s_no}",$i,$template_data->template);
  //$template_data->template = str_replace("{vaccine_name}",$patient_detail['vaccination_name'],$template_data->template);

  //$template_data->template = str_replace("{qty}",$patient_detail['qty'],$template_data->template);



  //$template_data->template = str_replace("{qty}",'',$template_data->template);

  //$template_data->template = str_replace("{total_amount}",number_format($patient_detail['total_amount'],2),$template_data->template);

  //$template_data->template = str_replace("{age}",$all_detail['prescription_print_list'][0]->title,$template_data->template);

  $template_data->template = str_replace("{age}",'',$template_data->template);

  //$template_data->template = str_replace("{row_data}",$tr_html,$template_data->template);
  $template_data->template = str_replace("{paid_amount}",number_format($payment_mode['paid_amount'],2),$template_data->template);
  $template_data->template = str_replace("{balance}",number_format($balance,2),$template_data->template);
  $template_data->template = str_replace("{total_amount_full}",number_format($payment_mode['total_amount'],2),$template_data->template);

   if(in_array('276',$users_data['permission']['section']))
        {
        if($all_detail['prescription_print_list'][0]->paid_amount>0 && (!empty($all_detail['prescription_print_list'][0]->reciept_prefix) || !empty($all_detail['prescription_print_list'][0]->reciept_suffix)) )
            {
                $template_data->template = str_replace("{hospital_receipt_no}",$all_detail['prescription_print_list'][0]->reciept_prefix.$all_detail['prescription_print_list'][0]->reciept_suffix,$template_data->template);
            }
        }
        else
        {
            $template_data->template = str_replace("{hospital_receipt_no}",'',$template_data->template);
        }

  $template_data->template = str_replace("{total_discount}",$payment_mode['discount_amount'],$template_data->template);
  $template_data->template = str_replace("{net_amount}",number_format($payment_mode['net_amount'],2),$template_data->template);

  $template_data->template = str_replace("{payment_mode}",$payment_mode_by_id[0]->payment_mode,$template_data->template);
  $template_data->template = str_replace("{signature}",ucfirst($user_detail['user_name']),$template_data->template);

  if(!empty($all_detail['prescription_print_list'][0]->remarks))
          {
             $template_data->template = str_replace("{remarks}",$all_detail['prescription_print_list'][0]->remarks,$template_data->template);
          }
          else
          {
             $template_data->template = str_replace("{remarks}",' ',$template_data->template);
             $template_data->template = str_replace("Remarks :",' ',$template_data->template);
             $template_data->template = str_replace("Remarks",' ',$template_data->template);

             
          }
          $template_data->template = str_replace("Age",' ',$template_data->template);

          //$template_data->template = str_replace("QTY",' ',$template_data->template);
          //$template_data->template = str_replace("Discount (%):",' ',$template_data->template);
           //$template_data->template = str_replace("discount_percent:",' ',$template_data->template);
          
    echo $template_data->template;
}

/* end leaser printing*/
?>






