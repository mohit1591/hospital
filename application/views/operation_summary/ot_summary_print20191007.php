
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>my_layout.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_style.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>menu_for_all.css">
<link rel="stylesheet" type="text/css" href="<?php echo ROOT_CSS_PATH; ?>withoutresponsive.css">


<?php
if($template!="" && !empty($template))
{
	$template_data=$template[0]->setting_value;
	$template=str_replace('{patient_reg_no}',$data['patient_code'], $template_data);
	$template=str_replace('{patient_name}',$data['patient_name'], $template);
	$template=str_replace('{mobile_no}',$data['mobile_no'], $template);

	if($data['gender']==0)
		$gender="Male";
	else
		$gender="Female";

	if($data['age_m']!="" && $data['age_y']!="" && $data['age_d']!="")
	{
		$gender=$gender."/".$data['age_y']."y ".$data['age_m']."m ".$data['age_d']."d";
	}
	$template=str_replace('{patient_age}',$gender, $template);
	$template=str_replace('{patient_address}',$data['address']." ".$data['address2']." ".$data['address3'], $template);
	$template=str_replace('{operation_package}',$data['ot_pack_name'], $template);
	$template=str_replace('{reciept_date}',date('d-m-y',strtotime($data['created_date'])), $template);
	$template=str_replace('{ipd_no}',$data['ipd_no'], $template);

	if($summary_data!="empty")
	{
		$template=str_replace('{post_observations}',$summary_data['post_observations'], $template);
		$template=str_replace('{ot_procedure}',$summary_data['ot_procedure'], $template);
	}
	else
	{
		$template=str_replace('{ot_procedure}','', $template);		
		$template=str_replace('{post_observations}','', $template);		
	}
	$table_data='';
	if($medicine_data!="empty" && !empty($medicine_data) && $medicine_data!="")
	{

		$table_data.='<div style="margin-top:15px;"><b>Medicine</b></div><table style="width:100%;border-color:#aaa;border-collapse:collapse;font:13px arial;" cellpadding="0" cellspacing="0" border="1"><tbody><tr>';
		if($data['booking_type']==1)
		{ 
            $table_data.='<th>Eye Drop</th>';
		}
		else
		{
			$table_data.='';	
		}                        
        $table_data.='<th>Medicine</th><th>Medicine Unit</th><th>Medicine Company</th><th>Salt</th><th>Dose</th><th>Duration (Days)</th><th>Frequency</th><th>Advice</th><th>Date</th>';
        if($data['booking_type']==1)
		{ 
            $table_data.='<th>R</th><th>L</th>';
		}
        $table_data.='</tr>';
       	foreach($medicine_data as $medicines)
		{
			$table_data.='<tr>';
			if($data['booking_type']==1)
			{ 
                if($medicines->is_eye_drop==1)
                { 
                	$table_data.='<td><i class="fa fa-check"></td>';	
                }
            }
            else
            {
              	$table_data.='';	
            }

                $table_data.="<td>".$medicines->medicine_name."</td>";
                $table_data.="<td>".$medicines->medicine_unit."</td>";
                $table_data.="<td>".$medicines->medicine_company."</td>";
                $table_data.="<td>".$medicines->medicine_salt."</td>";
                $table_data.="<td>".$medicines->medicine_dose."</td>";
                $table_data.="<td>".$medicines->medicine_duration."</td>";
                $table_data.="<td>".$medicines->medicine_frequency."</td>";
                $table_data.="<td>".$medicines->medicine_advice."</td>";
                if(strtotime($medicines->medicine_date) >0)
                	$table_data.="<td>".date('d-m-y',strtotime($medicines->medicine_date))."</td>";
                else
                	$table_data.="<td> </td>";
                if($medicines->is_eye_drop==1)
                { 
                	if($medicines->left_eye==1)
                		$table_data.="<td><i class='fa fa-check'></td>";
                	else
                		$table_data.="<td></td>";

                	if($medicines->right_eye==1)
                		$table_data.="<td><i class='fa fa-check'></td>";
                	else
                		$table_data.="<td></td>";
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
	//echo $table_data;die;
	$template=str_replace('{table_data}',$table_data, $template);	
		$template=str_replace('{remarks}',$summary_data['remark'], $template);
	$template=str_replace('{diagnosis}',$summary_data['diagnosis'], $template);
	$template=str_replace('{op_findings}',$summary_data['op_findings'], $template);
	$template=str_replace('{procedures}',$summary_data['procedures'], $template);
	$template=str_replace('{pos_op_orders}',$summary_data['pos_op_orders'], $template);
	echo $template;
}
?>