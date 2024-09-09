<?php 
//echo "<pre>"; print_r($vitals_list); exit;

$genders = array('0'=>'F','1'=>'M','2'=>'O');
        $gender = $genders[$recepeint_detail['gender']];
        $age_y = $recepeint_detail['age_y']; 
        $age_m =$recepeint_detail['age_m'];
        $age_d = $recepeint_detail['age_d'];

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
        	$patient1_age = $patient_age;
        }
        if($patient_age==''){
        	$patient1_age=$patient_age;
        }
        $gender_age = $gender.$patient1_age ;
        


$template_data->template = str_replace("{cross_match_by}",$patient_detail['technician_name'],$template_data->template);

$template_data->template = str_replace("{patient_name}",$recepeint_detail['patient_name'],$template_data->template);

$template_data->template = str_replace("{patient_reg_no}",$recepeint_detail['patient_code'],$template_data->template);

$template_data->template = str_replace("{recipient_uid}",$recepeint_detail['recipient_uid'],$template_data->template);

$template_data->template = str_replace("{issue_code}",$recepeint_detail['patient_code'],$template_data->template);

$template_data->template = str_replace("{age}",$patient1_age,$template_data->template);

 $template_data->template = str_replace("{verified_by}",$patient_detail['verified_by'],$template_data->template);
$template_data->template = str_replace("{gender}",$gender,$template_data->template);

  $template_data->template = str_replace("{blood_group}",$all_detail['blood_print_list'][0]->blood_group,$template_data->template);
  
  $template_data->template = str_replace("{hospital_name}",$all_detail['blood_print_list'][0]->doctor_hospital_name,$template_data->template);
  
  $template_data->template = str_replace("{doctor_name}",$patient_detail['verified_by'],$template_data->template);
  
  $template_data->template = str_replace("{doctors_name}",$pat_data_edit['doctors_name'],$template_data->template);
  
   $template_data->template = str_replace("{billing_for}",$patient_detail['billing_for'],$template_data->template);
  
  
   $template_data->template = str_replace("{transfustion_date}",$form_data['transfustion_date'],$template_data->template);
   
    $template_data->template = str_replace("{transfustion_time}",$form_data['transfustion_time'],$template_data->template);
  
  $template_data->template = str_replace("{issue_date}",$issue_dates,$template_data->template);
  
  
  
  
  
  $vital_table ='<table width="100%" border="1" cellpadding="0" cellspacing="0">
            <thead>
                    <tr>
                       <th>Vitals</th> 
                      
                      <th> Pre-Transfusion</th> 
                      <th>Start Time</th> 
                      <th>After15 Min.</th> 
                      <th>After15 Min.</th> 
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      <th>After15 Min.</th>
                      
                    </tr>
                    <tbody>';
                    
                      $vl_first='';
                      $vl_second='';
                      $vl_third='';
                      $vl_fourth='';
                      
                      
                      $vl_fifth='';
                      
                      $vl_six='';
                      $vl_seven='';
                      $vl_eight='';
                      $vl_nine='';
                      $vl_ten='';
                      $vl_eleven='';
                      
                      
                     // print '<pre>'; print_r($vitals_list); die;
                    $i=1;
                    if(!empty($vitals_list))
                    {


                    foreach($vitals_list as $vital)
                    {

                      if(isset($vital->pre_trans) && $vital->pre_trans!='')
                      {
                        $pre_trans_val= $vital->pre_trans;
                      }
                      else
                      {
                        $pre_trans_val='';
                      }
                      
                      if(isset($vital->value_first) && $vital->value_first!='')
                      {
                        $vl_first= $vital->value_first;
                      }
                      else
                      {
                        $vl_first='';
                      }
                      if(isset($vital->value_second) && $vital->value_second!='')
                      {
                        $vl_second= $vital->value_second;
                      }
                      else
                      {
                        $vl_second='';
                      }
                      if(isset($vital->value_third) && $vital->value_third!='')
                      {
                        $vl_third= $vital->value_third;
                      }
                      else
                      {
                        $vl_third='';
                      }
                      if(isset($vital->value_fourth) && $vital->value_fourth!='')
                      {
                        $vl_fourth= $vital->value_fourth;
                      }
                      else
                      {
                        $vl_fourth='';
                      }
                      
                      if(isset($vital->value_fifth) && $vital->value_fifth!='')
                      {
                        $vl_five= $vital->value_fifth;
                      }
                      else
                      {
                        $vl_five='';
                      }
                      if(isset($vital->value_six) && $vital->value_six!='')
                      {
                        $vl_six= $vital->value_six;
                      }
                      else
                      {
                        $vl_six='';
                      }
                      
                      if(isset($vital->value_seven) && $vital->value_seven!='')
                      {
                        $vl_seven= $vital->value_seven;
                      }
                      else
                      {
                        $vl_seven='';
                      }
                      
                      if(isset($vital->value_eight) && $vital->value_eight!='')
                      {
                        $vl_eight= $vital->value_eight;
                      }
                      else
                      {
                        $vl_eight='';
                      }
                      
                      if(isset($vital->value_nine) && $vital->value_nine!='')
                      {
                        $vl_nine= $vital->value_nine;
                      }
                      else
                      {
                        $vl_nine='';
                      }
                      
                      if(isset($vital->value_ten) && $vital->value_ten!='')
                      {
                        $vl_ten= $vital->value_ten;
                      }
                      else
                      {
                        $vl_ten='';
                      }
                      
                      if(isset($vital->value_eleven) && $vital->value_eleven!='')
                      {
                        $vl_eleven= $vital->value_eleven;
                      }
                      else
                      {
                        $vl_eleven='';
                      }
                      
                    
                    $vital_table .='<tr>
                      <td><b>'.$vital->vitals_name.'</b></td>
                      
                      <td>'.$pre_trans_val.'</td>
                      
                      <td>'.$vl_first.'</td>
                      <td>'.$vl_second.'</td>
                      <td>'.$vl_third.'</td>
                      
                      <td>'.$vl_fourth.'</td>
                      
                      
                      <td>'.$vl_five.'</td>
                      
                      <td>'.$vl_six.'</td>
                      
                      
                      <td>'.$vl_seven.'</td>
                      
                      <td>'.$vl_eight.'</td>
                      
                      
                      <td>'.$vl_nine.'</td>
                      
                      <td>'.$vl_ten.'</td>
                      
                      <td>'.$vl_eleven.'</td>
                      </tr>';
                    }

                  }
                  else
                  {
                    $vital_table.='<tr>
                       <td> &nbsp;  </td> 
                      
                      <td> &nbsp;  </td> 
                      <td>  &nbsp; </td> 
                     <td>  &nbsp; </td> 
                      <td>  &nbsp; </td> 
                      <td> &nbsp;  </td> 
                     <td>  &nbsp; </td> 
                      <td> &nbsp;  </td> 
                      <td>  &nbsp; </td> 
                      <td>  &nbsp; </td> 
                      <td>  &nbsp; </td> 
                      <td>  &nbsp; </td> 
                      <td>  &nbsp; </td> 
                      
                    </tr>';
                  }


                    $vital_table.='</tbody>
                
            </thead>  
        </table>';
  
  
  
  
  

$template_data->template = str_replace("{vital_table}",$vital_table,$template_data->template);


$transfusion = '<p class="text-center"><strong><u>ADVERSE TRANSFUSION EFFECT / TRANSFUSION REACTION DETAILS</u></strong></p>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>';
	
	
	$i=1; $donor_code='';
                    foreach($issue_data as $issue)
                    {
                        
                        

                      if(strtotime($issue->expiry_date)>86400)
                      {
                        $expiry_date=date('d-m-Y',strtotime($issue->expiry_date));
                      }
                      else
                      {
                        $expiry_date=date('d-m-Y'); 
                      }
                      if(strtotime($issue->registration_date)>86400)
                      {
                        $issue_date=date('d-m-Y',strtotime($issue->registration_date));
                      }
                      else
                      {
                      $issue_date=date('d-m-Y'); 
                      }
                      
                    if($i==1)
                    {
                         $transfusion .='Implicated Unit No <tr> ';
                    }
                 $transfusion .='<tr> ';
                 //if($i==1)
                 //{
                     $transfusion .='<th>'.$issue->donor_code.'</th>';
                 //}
                 /*else
                 {
                   $transfusion .='<td>&nbsp;</td>';  
                 }*/
                     
                  $transfusion .='<td>&nbsp;</td>
                      <td><b>'.$issue->component_name.'</b></td>
                      <td><b>'.$issue->blood_group.'</b></td>
                      
                      <td>'.$issue_date.'</td>
                      <td>'.$expiry_date.'</td>
                     </tr>';
                      $i++;
                    }
	$transfusion .=	'</tbody>
</table>';


$template_data->template = str_replace("{transfusion_table}",$transfusion,$template_data->template);


  echo $template_data->template;

?>