<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">

<?php
$user_detail = $this->session->userdata('auth_users');

$signature_reprt_data='';
if(!empty($signature_data))
{
    $signature_reprt_data .='<table border="0" cellpadding="4px" cellspacing="0" width="100%" style="border-collapse: collapse;font-size:13px;margin-top: 5%;"> 
      ';
      
      $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;"><b>Consultant Doctor </b></div></td>
      </tr>';

	  $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">Dr.'.$signature_data->doctor_name.'</div></td>
      </tr>';

      $signature_reprt_data.='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:140px;">'.$signature_data->qualification.'</div></td>
      </tr>';
      
      if(!empty($signature_data->signature) && file_exists(DIR_UPLOAD_PATH.'doctor_signature/'.$signature_data->signature))
      {
      
      $signature_reprt_data .='<tr>
      <td width="70%"></td>
        <td valign="top" align="" style="padding-right:2em;"><div style="float:right;text-align: left;width:100px;"><img width="90px" src="'.ROOT_UPLOADS_PATH.'doctor_signature/'.$signature_data->signature.'"></div></td>
      </tr>';
      
       }
       
       
      $signature_reprt_data .='</table>';
}
//echo $signature_reprt_data; die;
if($template!="" && !empty($template))
{
    $template_data=$template[0]->setting_value;
	
	
	$simulation = get_simulation_name($data['simulation_id']);
	$template=str_replace('{patient_reg_no}',$data['patient_code'], $template_data);
	$template=str_replace('{patient_name}',$simulation.' '.$data['patient_name'], $template);
	$template=str_replace('{mobile_no}',$data['mobile_no'], $template);
	
	$template=str_replace('{doctor_signature}',$signature_reprt_data, $template);
	
	
	$template=str_replace('{indication_of_surgery}',$summary_data['indication_of_surgery'], $template);
	
	$template=str_replace('{name_of_anaesthetist}',$summary_data['name_of_anaesthetist'], $template);
	
	$template=str_replace('{booking_code}',$data['booking_code'], $template);
	
	$template=str_replace('{type_of_anaesthesia}',$summary_data['type_of_anaesthesia'], $template);
	
	$template=str_replace('{mobile_no}',$data['mobile_no'], $template);


	if(!empty($data['relation']))
        {
        $rel_simulation = get_simulation_name($data['relation_simulation_id']);
        $template = str_replace("{parent_relation_type}",$data['relation'],$template);
        }
        else
        {
         $template = str_replace("{parent_relation_type}",'',$template);
        }

    if(!empty($data['relation_name']))
        {
        $rel_simulation = get_simulation_name($data['relation_simulation_id']);
        $template = str_replace("{parent_relation_name}",$rel_simulation.' '.$data['relation_name'],$template);
        }
        else
        {
         $template = str_replace("{parent_relation_name}",'',$template);
        }


	if($data['gender']==0)
		$gender="Female";
	else
		$gender="Male";

	if(!empty($data['age_y']) && $data['age_y']!="") //&& $data['age_y']!="" && $data['age_d']!=""
	{
		$gender=$gender."/".$data['age_y']."Y "; //.$data['age_m']."m ".$data['age_d']."d";
	}
	if(!empty($data['age_m']))
	{
	    $gender=$gender.$data['age_m']."M ";
	}
	$template=str_replace('{patient_age}',$gender, $template);
	$template=str_replace('{patient_address}',$data['address']." ".$data['address2']." ".$data['address3'], $template);
	$template=str_replace('{operation_package}',$data['ot_pack_name'], $template);
	$template=str_replace('{reciept_date}',date('d-m-y',strtotime($data['created_date'])), $template);
	$template=str_replace('{ipd_no}',$data['ipd_no'], $template);

	if($summary_data!="empty")
	{
		$template=str_replace('{post_observations}',nl2br($summary_data['post_observations']), $template);
		$template=str_replace('{ot_procedure}',nl2br($summary_data['ot_procedure']), $template);
	}
	else
	{
		$template=str_replace('{ot_procedure}','', $template);		
		$template=str_replace('{post_observations}','', $template);		
	}
	$table_data='';
	if($medicine_data!="empty" && !empty($medicine_data) && $medicine_data!="")
	{

		$table_data.='<div style="margin-top:15px;"><b>Medicine</b></div><table style="width:100%;border-color:#ccc;font:12px arial;margin-top:5px;" cellspacing="0" cellpadding="0" border="1"><thead><tr style="border-bottom:1px dashed #ccc;">';
		if($data['booking_type']==1)
		{ 
            $table_data.='<th style="padding:4px;" valign="top" align="left">Eye Drop</th>';
		}
		else
		{
			$table_data.='<th></th>';	
		}                        
        $table_data.='<th style="padding:4px;" valign="top" align="left">Medicine</th><th   style="padding:4px;" valign="top" align="left">Dose</th><th   style="padding:4px;" valign="top" align="left">Duration (Days)</th><th  style="padding:4px;" valign="top" align="left">Frequency</th><th   style="padding:4px;" valign="top" align="left">Advice</th><th   style="padding:4px;" valign="top" align="left">Date</th>';
        if($data['booking_type']==1)
		{ 
            $table_data.='<th   style="padding:4px;" valign="top" align="left">R</th><th   style="padding:4px;" valign="top" align="left">L</th>';
		}
        $table_data.='</tr></thead><tbody>';
       	foreach($medicine_data as $medicines)
		{
			$table_data.='<tr>';
			if($data['booking_type']==1)
			{ 
                if($medicines->is_eye_drop==1)
                { 
                	$table_data.='<td style="padding:4px;" valign="top" align="left"><i class="fa fa-check"></td>';	
                }
                else
                {
                   $table_data.='<td style="padding:4px;" valign="top" align="left">&nbsp;</td>';	 
                }
            }
            else
            {
              	$table_data.='<td style="padding:4px;" valign="top" align="left">&nbsp;</td>';	
            }

                $table_data.="<td style='padding:4px;' valign='top' align='left'>".$medicines->medicine_name."</td>";
                
                $table_data.="<td style='padding:4px;' valign='top' align='left'>".$medicines->medicine_dose."</td>";
                $table_data.="<td style='padding:4px;' valign='top' align='left'>".$medicines->medicine_duration."</td>";
                $table_data.="<td style='padding:4px;' valign='top' align='left'>".$medicines->medicine_frequency."</td>";
                $table_data.="<td style='padding:4px;' valign='top' align='left'>".$medicines->medicine_advice."</td>";
                if(strtotime($medicines->medicine_date) >0)
                	$table_data.="<td style='padding:4px;' valign='top' align='left'>".date('d-m-y',strtotime($medicines->medicine_date))."</td>";
                else
                	$table_data.="<td style='padding:4px;' valign='top' align='left'> </td>";
                if($medicines->is_eye_drop==1)
                { 
                	if($medicines->left_eye==1)
                		$table_data.="<td style='padding:4px;' valign='top' align='left'><i class='fa fa-check'></td>";
                	else
                		$table_data.="<td style='padding:4px;' valign='top' align='left'></td>";

                	if($medicines->right_eye==1)
                		$table_data.="<td style='padding:4px;' valign='top' align='left'><i class='fa fa-check'></td>";
                	else
                		$table_data.="<td style='padding:4px;' valign='top' align='left'></td>";
                }
                else
                {
                	$table_data.="";
                	$table_data.="";
                }


            
            $table_data.="</tr>";
		}
		$table_data.='</tbody></table>';
	}
	//echo "<pre>"; print_r($summary_data); exit;
	//echo nl2br($summary_data['procedures']); die;
	//echo $table_data;die;
	$template=str_replace('{table_data}',$table_data, $template);

	$template=str_replace('{remarks}',nl2br($summary_data['remark']), $template);
	$template=str_replace('{diagnosis}',nl2br($summary_data['diagnosis']), $template);
	$template=str_replace('{op_findings}',nl2br($summary_data['op_findings']), $template);
	$template=str_replace('{procedures}',nl2br($summary_data['procedures']), $template);
	$template=str_replace('{pos_op_orders}',nl2br($summary_data['pos_op_orders']), $template);
	$template= str_replace("{surgeon_name}",$summary_data['surgeon_name'],$template);
	$template= str_replace("{assistant_surgeon_name}",$doctor_name,$template);
	if(!empty($summary_data['recovery_time']) && $summary_data['recovery_time']!='0000-00-00 00:00:00' && date('d-m-Y',strtotime($summary_data['recovery_time']))!='01-01-1970')
    {
        $recovery_time = date('d-m-Y H:i A',strtotime($summary_data['recovery_time']));
    }
    else
    {
        $recovery_time = ''; 
    }
	$template= str_replace("{blood_transfusion}",$summary_data['blood_transfusion'],$template);
	$template= str_replace("{recovery_time}",$recovery_time,$template);
	$template= str_replace("{blood_loss}",$summary_data['blood_loss'],$template);
	$template= str_replace("{drain}",$summary_data['drain'],$template);

	$template= str_replace("{histopathological}",$summary_data['histopathological'],$template);
	$template= str_replace("{microbiological}",$summary_data['microbiological'],$template);
    $operation_date = $patient_ot_detail['operation_date'];
    $operation_booking_date = $patient_ot_detail['operation_booking_date'];
    $operation_time = $patient_ot_detail['operation_time'];
    $operation_end_time = $patient_ot_detail['operation_end_time'];
    
    
    $operation_start_time = $summary_data['operation_start_time'];
    $operation_finish_time = $summary_data['operation_finish_time'];
    $post_operative_period  = $summary_data['post_operative_period'];
    
    $template= str_replace("{operation_date}",date('d-m-Y',strtotime($operation_date)),$template);
    
    $template= str_replace("{operation_booking_date}",date('d-m-Y',strtotime($operation_booking_date)),$template);
    $template= str_replace("{operation_time}",date('H:i A',strtotime($operation_time)),$template);
    
    $template= str_replace("{operation_end_time}",date('H:i A',strtotime($operation_end_time)),$template);
    
    $template= str_replace("{operation_start_time}",date('d-m-Y H:i',strtotime($operation_start_time)),$template);
    $template= str_replace("{operation_finish_time}",date('d-m-Y H:i',strtotime($operation_finish_time)),$template);
    
    $template= str_replace("{post_operative_period}",$post_operative_period,$template);
    
     $template = str_replace("{signature}",ucfirst($summary_data['surgeon_name']),$template);

	
	 $template = preg_replace('/(<br\s*\/?>\s*){2,}/i', '<br>', $template);
	/* $template = preg_replace('/<br\s*\/?>/i', '', $template);  */
	
	echo $template;
}
?>
<style>
	ul {
		list-style-type: disc!important;
		margin-left:2%;
	}
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
            // Select all <li> elements within <ul> elements
            $('ul').each(function() {
                // Find and remove all <br> elements within the current <li> element
                $(this).find('br').remove();
            });
            
            $('p').each(function() {
                if ($(this).next().is('br')) {
                    $(this).next().remove();
                }
            });
            $('br + br').remove();
        });
</script>