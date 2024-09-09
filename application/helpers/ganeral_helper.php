<?php
function auth_users()
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	if(!isset($auth_users) || empty($auth_users))
	{
        redirect(base_url('login'));
	} 	
}

function get_adv_id($paid_date='',$paid_amount='',$ipd_id='')
{
	$CI =& get_instance();
   	$users_data = $CI->session->userdata('auth_users');
   		$CI->load->model('general/general_model','general');
	$result = $CI->general->get_adv_id($paid_date,$paid_amount,$ipd_id);
	return $result;

}

function get_component_unite($recipient_id,$component_id)
{
	$CI =& get_instance();
   	$CI->load->model('blood_bank/recipient/recipient_model','reci');
	$result = $CI->reci->get_component_unite($recipient_id,$component_id);
	return $result;

}

function getProcedureNoteTabSetting($var_title){
	$CI =& get_instance();
    $CI->load->model('procedure_note_tab_setting/procedure_note_tab_setting_model','procedure_note');
    $result = $CI->procedure_note->procedure_note->get_setting_single($var_title);
    return $result;
}



function unauthorise_permission($section="",$action="")
{
   $CI =& get_instance();
   $users_data = $CI->session->userdata('auth_users');
   if(!empty($section) && !empty($action))
   {
        if(in_array($section,$users_data['permission']['section']) && in_array($action,$users_data['permission']['action']))
        {
        	return true;
        }
        else
        {
        	redirect('401');
        }
   }
}
function mandatory_section_field_list($section_id='')
{
	$CI =& get_instance();
	$CI->load->model('mandatory_field_manage/Mandatory_field_manage_model','mandatory_field_manage');
	$result = $CI->mandatory_field_manage->mandatory_section_field_list($section_id);
	return $result;
    
}
function auth_dashboard()
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	if(!isset($auth_users) || empty($auth_users))
	{
        redirect(base_url('login'));
	} 
}
function get_particular($particular_id="")
{
	$CI =& get_instance();
	$CI->load->model('ipd_perticular/ipd_perticular_model','ipd_perticular');
	$result = $CI->ipd_perticular->get_by_id($particular_id);
	return $result;
}
 function get_particular_list($particular_ids=array())
    {
        $CI =& get_instance();
        $CI->load->model('general/general_model','general');
        $result = $CI->general->get_particular_list($particular_ids);
        return $result;
    }
function get_medicine($medicine_id="")
{
	$CI =& get_instance();
	$CI->load->model('medicine_entry/medicine_entry_model','medicine_entry');
	$result = $CI->medicine_entry->get_by_id($medicine_id);
	return $result;
}
function get_medicine_company($medicine_comp_id="",$branch_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_medicine_company($medicine_comp_id,$branch_id);
	return $result;
}


function get_permission_attr($section_id="",$action_id="")
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$total_branch = $CI->general->total_branch($auth_users['parent_id']); 
	$result = $CI->general->get_permission_attr($section_id,$action_id);  
	$remaining_branch = $result['attribute_val'] - $total_branch;
	return $remaining_branch;
}

function users_role_list()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->user_role_list();
	return $result;
}

function country_list()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->country_list();
	return $result;
}

function doctor_specilization_list($specilization_id="",$branch_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->doctor_specilization_list($specilization_id,$branch_id);
	return $result;
}

function particular_list($particular_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->particular_list($particular_id);
	return $result;
}

function state_list($country_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->state_list($country_id);
	return $result;
}

function city_list($state_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->city_list($state_id);
	return $result;
}
function credit_package_quantity_to_stock($branch_id="",$package_id="",$quantity="",$patient_id="",$section_id=""){
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->credit_package_quantity_to_stock($branch_id,$package_id,$quantity,$patient_id,$section_id);
    return $result;
}
function action_list($section_id="")
{
	$CI =& get_instance();
	$CI->load->model('permission/permission_model','permission');
	$result = $CI->permission->action_list($section_id);
	return $result;
}

function permission_action_list($section_id="",$role="")
{
	$CI =& get_instance();
	$CI->load->model('permission/permission_model','permission');
	$result = $CI->permission->action_list($section_id,$role);
	return $result;
}

function user_permission_action_list($section_id="",$users_id="")
{
	$CI =& get_instance();
	$CI->load->model('users/users_model','users');
	$result = $CI->users->user_permission_action_list($section_id,$users_id);
	return $result;
}

function branch_permission_action_list($section_id="",$branch_id="")
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model','branch');
	$result = $CI->branch->branch_permission_action_list($section_id,$branch_id);
	return $result;
}

function get_branch_permission_status($users_id="",$section_id="")
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model','branch');
	$result = $CI->branch->get_branch_permission_status($users_id,$section_id);
	return $result;
}
function branch_permission_section_list($branch_id="")
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model','branch');
	$result = $CI->branch->branch_permission_section_list($branch_id);
	return $result;
}

function generate_unique_id($type="",$other_branch_id="")
{
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
    $table_name = "";
    $field_name = "";
    $booking_type='';
    $opd_type ='1'; //emergency opd booking
	if(isset($other_branch_id) && !empty($other_branch_id))
	{
		$branch_id = $other_branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	
	$result = $CI->general->get_branch_format($type,$branch_id); 
	$total_record = 0;
	$prefix ='';
	$suffix='';
	if(!empty($result->prefix))
	{
		$prefix = $result->prefix; 	
		$suffix = $result->start_num; 	
	}
	

	if($type==1)
	{
		$table_name = 'hms_branch';
		$field_name = 'branch_code';
		$total_record = $CI->general->total_branch($branch_id,$prefix);
	}
	else if($type==2)
	{
		$table_name = 'hms_employees';
		$field_name = 'reg_no';
		$total_record = $CI->general->total_employee($branch_id,$prefix);
	}
	else if($type==3)
	{
		$table_name = 'hms_doctors';
		$field_name = 'doctor_code';
		$total_record = $CI->general->total_doctors($branch_id,$prefix);
	}
	else if($type==4)
	{
		$table_name = 'hms_patient';
		$field_name = 'patient_code';
		$total_record = $CI->general->total_patient($branch_id,$prefix);
	}
	else if($type==5)
	{
		$table_name = 'path_test_booking';
		$field_name = 'lab_reg_no';
		$total_record = $CI->general->total_test($branch_id,$prefix);
	}
	
	else if($type==8)
	{
		$table_name = 'path_item';
		$field_name = 'item_code';
		$total_record = $CI->general->total_item($branch_id,$prefix);
	}
	else if($type==9)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'booking_code';
		$booking_type = '2';
		$opd_type ='normal';//for normal opd in case of emergency the value will be 1
		$total_record = $CI->general->total_booking($branch_id,$prefix);
	}
	else if($type==10)
	{
		$table_name = 'hms_medicine_entry';
		$field_name = 'medicine_code';
		$total_record = $CI->general->total_medicine_entry($branch_id,$prefix);
	}

	else if($type==11)
	{
		$table_name = 'hms_medicine_vendors';
		$field_name = 'vendor_id';
		$total_record = $CI->general->total_vendor($branch_id,$prefix);
	}
	//else if($type==12)
	//{
		
		//$total_record = $CI->general->total_purchase($branch_id,$prefix);
	//}
	else if($type==13)
	{
		$table_name = 'hms_medicine_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase($branch_id,$prefix);
	}
	else if($type==14)
	{
		$table_name = 'hms_medicine_return';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase_return($branch_id,$prefix);
	}
	/*else if($type==15)
	{
		$table_name = 'hms_medicine_return';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase_return($branch_id,$prefix);
	}*/
	else if($type==16)
	{
		$table_name = 'hms_medicine_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_sales($branch_id,$prefix);
	}
	else if($type==17)
	{
		$table_name = 'hms_medicine_sale_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_sales_return($branch_id,$prefix);
		//echo $total_record;
	}

	else if($type==18)
	{
		$table_name = 'hms_opd_billing';
		$field_name = 'reciept_no';
		$total_record = $CI->general->total_opd_billing($branch_id,$prefix);
	}
	else if($type==19)
	{
		$table_name = 'hms_expenses';
		$field_name = 'vouchar_no';
		$total_record = $CI->general->total_bills($branch_id,$prefix);
	}
	else if($type==20)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'appointment_code';
		$booking_type = '1';
		$total_record = $CI->general->total_appointment($branch_id,$prefix);
	}
	else if($type==21)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'reciept_code';
		$booking_type = '3';
		$total_record = $CI->general->total_billing($branch_id,$prefix);
	}
	else if($type==22)
	{
		$table_name = 'hms_ipd_booking';
		$field_name = 'ipd_no';
		$total_record = $CI->general->total_ipd_booking($branch_id,$prefix);
	//	echo $CI->db->last_query(); exit;
	}
	else if($type==23)
	{
		$table_name = 'hms_operation_booking';
		$field_name = 'booking_code';
		$total_record = $CI->general->total_ot_booking($branch_id,$prefix);
	}
	else if($type==24)
	{
		$table_name = 'hms_ipd_booking';
		$field_name = 'discharge_bill_no';
		$total_record = $CI->general->total_ipd_booking_discharged($branch_id,$prefix);
	}

	else if($type==25)
	{
		$table_name = 'path_test';
		$field_name = 'test_code';
		$total_record = $CI->general->total_test($branch_id,$prefix);
	}
	else if($type==26)
	{
		$table_name = 'path_test_booking';
		$field_name = 'lab_reg_no';
		$total_record = $CI->general->path_total_booking($branch_id,$prefix);
	}
	else if($type==27)
	{
		$table_name = 'path_purchase_item';
		$field_name = 'purchase_no';
		$total_record = $CI->general->tot_inventory_purchase_item($branch_id,$prefix);
	}
	else if($type==28)
	{
		$table_name = 'path_purchase_return_item';
		$field_name = 'return_no';
		$total_record = $CI->general->tot_inventory_purchase_return_item($branch_id,$prefix);
	}
	else if($type==29)
	{
		$table_name = 'hms_hospital';
		$field_name = 'hospital_code';
		$total_record = $CI->general->tot_hospital($branch_id,$prefix);
	}
	else if($type==30)
	{
		$table_name = 'hms_stock_issue_allotment';
		$field_name = 'issue_no';
		$total_record = $CI->general->tot_inventory_stock_issue_allotment_item($branch_id,$prefix);
	}
	else if($type==31)
	{
		$table_name = 'hms_stock_issue_allotment_return_item';
		$field_name = 'return_no';
		$total_record = $CI->general->tot_inventory_stock_issue_allotment_return_item($branch_id,$prefix);
	}
	else if($type==32)
	{
		$table_name = 'hms_garbage_stock_item';
		$field_name = 'garbage_no';
		$total_record = $CI->general->tot_inventory_garbage_stock_item($branch_id,$prefix);
	}
	else if($type==33)
	{
		$table_name = 'path_item';
		$field_name = 'item_code';
		$total_record = $CI->general->tot_inventory_stock_item($branch_id,$prefix);
	}

	else if($type==34)
	{
		$table_name = 'hms_dialysis_booking';
		$field_name = 'booking_code';
		$total_record = $CI->general->tot_dialysis_book($branch_id,$prefix);
	}
	else if($type==74)
	{
		$table_name = 'hms_dialysis_appointment';
		$field_name = 'booking_code';
		$total_record = $CI->general->tot_dialysis_appointment($branch_id,$prefix);
	}
	
	else if($type==35)
	{
		$table_name = 'hms_vaccination_entry';
		$field_name = 'vaccination_code';
		$total_record = $CI->general->total_vaccination_entry($branch_id,$prefix);
	}
	// else if($type==36)
	// {
	// 	$total_record = $CI->general->total_vaccination_vendor($branch_id);
	// }
	else if($type==37)
	{
		$table_name = 'hms_vaccination_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_vaccination_purchase($branch_id,$prefix);
	}
	else if($type==38)
	{
		$table_name = 'hms_vaccination_purchase_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_vaccination_purchase_return($branch_id,$prefix);
	}
	else if($type==39)
	{
		$table_name = 'hms_vaccination_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_vaccination_billing($branch_id,$prefix);
	}
	else if($type==40)
	{
		$table_name = 'hms_vaccination_sale_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_vaccination_billing_return($branch_id,$prefix);
	}
	else if($type==41)
	{
		$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
		$table_name = 'hms_blood_donor';
		$field_name = 'donor_code';
		$total_record = $CI->blood_general_model->total_donors($branch_id,$prefix);
	}
	else if($type==43)
	{
		$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
		$table_name = 'hms_blood_patient_to_recipient';
		$field_name = 'issue_code';
		$total_record = $CI->blood_general_model->total_issued($branch_id,$prefix);
	}
	
	else if($type==45)
	{
		$CI->load->model('eye/general/eye_general_model','eye_general_model');
		$table_name = 'hms_opd_booking';
		$field_name = 'booking_code';
		$booking_type = '4';
		$total_record = $CI->eye_general_model->total_camp_booking($branch_id,$prefix);
	}
	else if($type==48)
	{
		$table_name = 'hms_estimate_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_sale_estimate($branch_id,$prefix);
	}
	
	else if($type==49)
	{
		$table_name = 'hms_estimate_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase_estimate($branch_id,$prefix);
	}
	else if($type==51)
	{
		$table_name = 'hms_indent_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_indent_sale($branch_id,$prefix);
	}
	else if($type==52)
	{
		$table_name = 'hms_indent_sale_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_indent_sale_return($branch_id,$prefix);
	}
	else if($type==53)
	{
		$table_name = 'hms_ambulance_booking';
		$field_name = 'booking_no';
		
		$total_record = $CI->general->total_ambulance_booking($branch_id,$prefix);
	}
	else if($type==54)
	{
		$table_name = 'hms_custom_icds';
		// $field_name = 'new_icd';
		
		$total_record = $CI->general->total_eye_new_icd($branch_id,$prefix);
	}
	else if($type==55)
	{
		$table_name = 'hms_day_care_booking';
		$field_name = 'booking_code';
		$booking_type = '5';
		$total_record = $CI->general->total_day_care_booking($branch_id,$prefix);
		//echo $CI->db->last_query(); exit;
	}
	else if($type==56)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'booking_code';
		$booking_type = '2';
		$opd_type ='emergency';
		$total_record = $CI->general->total_opd_emergency_booking($branch_id,$prefix);
	}
	else if($type==60)
	{
		$table_name = 'hms_day_care_booking';
		$field_name = 'day_discharge_bill_no';
		$total_record = $CI->general->total_opd_booking_discharged($branch_id,$prefix);
	}
	
	else if($type==61)
	{
		$table_name = 'hms_customers';
		$field_name = 'customer_code';
		$total_record = $CI->general->total_customer($branch_id,$prefix);
	}	
	else if($type==62)
	{
		$table_name = 'hms_canteen_garbage_stock_item';
		$field_name = 'garbage_no';
		$total_record = $CI->general->tot_canteen_garbage_stock_item($branch_id,$prefix);
	}
	
	else if($type==63)
	{
		$table_name = 'hms_canteen_vendors';
		$field_name = 'vendor_id';
		$total_record = $CI->general->total_canteen_vendor($branch_id,$prefix);
	}
	
   else if($type==64)
	{
		$table_name = 'hms_canteen_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_canteen_purchase($branch_id,$prefix);
	}
	else if($type==65)
	{
		$table_name = 'hms_canteen_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_canteen_purchase_return($branch_id,$prefix);
	}	
	
	else if($type==66)
	{
		$table_name = 'hms_canteen_estimate_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_canteen_purchase_estimate($branch_id,$prefix);
	}
	else if($type==67)
	{
		$table_name = 'hms_canteen_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_canteen_sale($branch_id,$prefix);
	}
	else if($type==68)
	{
		$table_name = 'hms_canteen_master_entry';
		$field_name = 'product_code';
		$total_record = $CI->general->total_canteen_master_entry($branch_id,$prefix);
	}
	else if($type==69)
	{
		$table_name = 'hms_canteen_products';
		$field_name = 'product_code';
		$total_record = $CI->general->total_canteen_products($branch_id,$prefix);
	}
	
	else if($type==71)
	{
		$table_name = 'hms_ambulance_gda_staff';
		$field_name = 'reg_no';
		$total_record = $CI->general->total_ambulance_gda_staff($branch_id,$prefix);
	}
	
		/* ambulance  enquiry*/
   else if($type==70)
	{
		$table_name = 'hms_ambulance_enquiry';
		$field_name = 'enquiry_no';
		$total_record = $CI->general->total_ambulance_enquiry($branch_id,$prefix);
	}
	else if($type==72)
	{
		$table_name = 'crm_leads';
		$field_name = 'crm_code'; 
		$total_record = $CI->general->total_crm_lead($branch_id,$prefix);
	}
	else if($type==73) //pathology invoice number
	{
		$table_name = 'path_test_booking_invoice';
		$field_name = 'lab_invoice_no';
		$total_record = $CI->general->path_total_invoice_booking($branch_id,$prefix);
	}
	
	
	
	/*if($result)
	{  

        $prefix = $result->prefix;
        if(empty($total_record) || $total_record==0)
        {
           $counter = $total_record+$result->start_num;
        }
        else
        {
        	$counter = $total_record+1+$result->start_num;
        }
    }
    else
    {
    	$prefix='';
    	$counter=$total_record+1;
    }*/
    //echo $total_record;die;
    if(!empty($result))
	{  
		
		if($result->start_num>1)
		{
	       //commented on 10 Jan 2022
	       //$start_num = $result->start_num; 
	       //Added
	       if($total_record==0)
	       {
	           $start_num = $result->start_num-1; 
	       }
	       else
	       {
	         $start_num = $result->start_num;   
	       }
	       //Add End
	       
		}
		else if($result->start_num==1)
		{
		   $start_num = $result->start_num-1;
		}
		else
		{
			$start_num = 0;
		}
		//
        if($user_data['parent_id']==208)
        {
            $counter = $total_record+$start_num;
        }
        else
        {
            $counter = $total_record+1+$start_num;
        }
		
		//echo $counter;
		//echo $start_num;
	}
	else
	{
	   $prefix='';
	   $counter=$total_record+1;
	}
    
    $current_year = date('Y');
    $current_month = date('m');
    $current_date = date('Y-m-d');
    $current_year_pre = date('y');
    $current_day_pre = date('d');
    
    //$prefix = str_replace('{year}', $current_year, $prefix);
    $prefix = str_replace('{small_year}', $current_year_pre, $prefix);
    $prefix = str_replace('{small_month}', $current_month, $prefix);
    $prefix = str_replace('{small_day}', $current_day_pre, $prefix);
    
    
    //$prefix = str_replace('{year}', $current_year, $prefix);
    $prefix = str_replace('{year}', $current_year, $prefix);
    $prefix = str_replace('{month}', $current_month, $prefix);
    $prefix = str_replace('{day}', $current_day_pre, $prefix);
    $prefix = str_replace('{YEAR}', $current_year, $prefix);
    $prefix = str_replace('{MONTH}', $current_month, $prefix);
    $prefix = str_replace('{DAY}', $current_day_pre, $prefix);
    
    $prefix = str_replace('{currentdate}', $current_date, $prefix);
    $prefix = str_replace('{CURRENTDATE}', $current_date, $prefix);
    
    $response = strtoupper($prefix).$counter;
    //echo $response;
    //echo $response; die;
    //echo $booking_type; die;
    if($user_data['parent_id']!='0')
    {
    	$response = check_unique_id($response,$table_name,$field_name,$prefix,$suffix,$booking_type,$result->modified_date,$opd_type);	
    }
     
	return $response;
}

 function check_unique_id($code="",$table_name="",$field_name="",$prefix="",$suffix="",$booking_type='',$updated_date='',$opd_type='')
 {
 	$CI =& get_instance();  //echo $code;die;
    if(!empty($code) && !empty($table_name) && !empty($field_name) && !empty($prefix) && !empty($suffix))
    {
		$user_data = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
		$result = $CI->general->check_unique_id($code,$table_name,$field_name,$booking_type,$opd_type);
	//	echo $CI->db->last_query();
	//print_r($result);die;
		if(!empty($result))
		{   
            $last_unique_id = $CI->general->get_last_unique_id($table_name,$field_name,$booking_type,$opd_type);
           // echo $CI->db->last_query(); //exit;
            //print_r($last_unique_id);die;
		    $count_prefix = strlen($prefix);  
		    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
		    $code = $prefix.($start_part+1);
		   // echo $code;
		    return $code;
		}
		else
		{
		    if(strtotime(date('d-m-Y',strtotime($updated_date)))!= strtotime(date('Y-m-d')))
			{
				 $last_unique_id = $CI->general->get_last_unique_id($table_name,$field_name,$booking_type,$opd_type);
				 	
	            
			    /*$count_prefix = strlen($prefix);  
			    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
			    $code = $prefix.($start_part+1); 
				return $code;*/
				if(!empty($last_unique_id))
	            {
	                 $count_prefix = strlen($prefix);  
    			    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
    			    $code = $prefix.($start_part+1); 
    				return $code;
	            }
	            else
	            {
	                return $code;
	            }
			}
			else
			{
			    //echo $code;
				return $code;
			}
			
		    /*$last_unique_id = $CI->general->get_last_unique_id($table_name,$field_name,$booking_type);
            //print_r($last_unique_id);die;
		    $count_prefix = strlen($prefix);  
		    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
		    $code = $prefix.($start_part+1); 
			return $code;*/
		}
    }
   
    return $code;
 }


//old function
function generate_unique_id20211129($type="",$other_branch_id="")
{
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
    $table_name = "";
    $field_name = "";
    $booking_type='';
	if(isset($other_branch_id) && !empty($other_branch_id))
	{
		$branch_id = $other_branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	
	$result = $CI->general->get_branch_format($type,$branch_id); 
	$total_record = 0;
	$prefix ='';
	$suffix='';
	if(!empty($result->prefix))
	{
		$prefix = $result->prefix; 	
		$suffix = $result->start_num; 	
	}
	

	if($type==1)
	{
		$table_name = 'hms_branch';
		$field_name = 'branch_code';
		$total_record = $CI->general->total_branch($branch_id,$prefix);
	}
	else if($type==2)
	{
		$table_name = 'hms_employees';
		$field_name = 'reg_no';
		$total_record = $CI->general->total_employee($branch_id,$prefix);
	}
	else if($type==3)
	{
		$table_name = 'hms_doctors';
		$field_name = 'doctor_code';
		$total_record = $CI->general->total_doctors($branch_id,$prefix);
	}
	else if($type==4)
	{
		$table_name = 'hms_patient';
		$field_name = 'patient_code';
		$total_record = $CI->general->total_patient($branch_id,$prefix);
	}
	else if($type==5)
	{
		$table_name = 'path_test_booking';
		$field_name = 'lab_reg_no';
		$total_record = $CI->general->total_test($branch_id,$prefix);
	}
	
	else if($type==8)
	{
		$table_name = 'path_item';
		$field_name = 'item_code';
		$total_record = $CI->general->total_item($branch_id,$prefix);
	}
	else if($type==9)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'booking_code';
		$booking_type = '2';
		$total_record = $CI->general->total_booking($branch_id,$prefix);
	}
	else if($type==10)
	{
		$table_name = 'hms_medicine_entry';
		$field_name = 'medicine_code';
		$total_record = $CI->general->total_medicine_entry($branch_id,$prefix);
	}

	else if($type==11)
	{
		$table_name = 'hms_medicine_vendors';
		$field_name = 'vendor_id';
		$total_record = $CI->general->total_vendor($branch_id,$prefix);
	}
	//else if($type==12)
	//{
		
		//$total_record = $CI->general->total_purchase($branch_id,$prefix);
	//}
	else if($type==13)
	{
		$table_name = 'hms_medicine_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase($branch_id,$prefix);
	}
	else if($type==14)
	{
		$table_name = 'hms_medicine_return';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase_return($branch_id,$prefix);
	}
	/*else if($type==15)
	{
		$table_name = 'hms_medicine_return';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase_return($branch_id,$prefix);
	}*/
	else if($type==16)
	{
		$table_name = 'hms_medicine_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_sales($branch_id,$prefix);
	}
	else if($type==17)
	{
		$table_name = 'hms_medicine_sale_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_sales_return($branch_id,$prefix);
		//echo $total_record;
	}

	else if($type==18)
	{
		$table_name = 'hms_opd_billing';
		$field_name = 'reciept_no';
		$total_record = $CI->general->total_opd_billing($branch_id,$prefix);
	}
	else if($type==19)
	{
		$table_name = 'hms_expenses';
		$field_name = 'vouchar_no';
		$total_record = $CI->general->total_bills($branch_id,$prefix);
	}
	else if($type==20)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'appointment_code';
		$booking_type = '1';
		$total_record = $CI->general->total_appointment($branch_id,$prefix);
	}
	else if($type==21)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'reciept_code';
		$booking_type = '3';
		$total_record = $CI->general->total_billing($branch_id,$prefix);
	}
	else if($type==22)
	{
		$table_name = 'hms_ipd_booking';
		$field_name = 'ipd_no';
		$total_record = $CI->general->total_ipd_booking($branch_id,$prefix);
	//	echo $CI->db->last_query(); exit;
	}
	else if($type==23)
	{
		$table_name = 'hms_operation_booking';
		$field_name = 'booking_code';
		$total_record = $CI->general->total_ot_booking($branch_id,$prefix);
	}
	else if($type==24)
	{
		$table_name = 'hms_ipd_booking';
		$field_name = 'discharge_bill_no';
		$total_record = $CI->general->total_ipd_booking_discharged($branch_id,$prefix);
	}

	else if($type==25)
	{
		$table_name = 'path_test';
		$field_name = 'test_code';
		$total_record = $CI->general->total_test($branch_id,$prefix);
	}
	else if($type==26)
	{
		$table_name = 'path_test_booking';
		$field_name = 'lab_reg_no';
		$total_record = $CI->general->path_total_booking($branch_id,$prefix);
	}
	else if($type==27)
	{
		$table_name = 'path_purchase_item';
		$field_name = 'purchase_no';
		$total_record = $CI->general->tot_inventory_purchase_item($branch_id,$prefix);
	}
	else if($type==28)
	{
		$table_name = 'path_purchase_return_item';
		$field_name = 'return_no';
		$total_record = $CI->general->tot_inventory_purchase_return_item($branch_id,$prefix);
	}
	else if($type==29)
	{
		$table_name = 'hms_hospital';
		$field_name = 'hospital_code';
		$total_record = $CI->general->tot_hospital($branch_id,$prefix);
	}
	else if($type==30)
	{
		$table_name = 'hms_stock_issue_allotment';
		$field_name = 'issue_no';
		$total_record = $CI->general->tot_inventory_stock_issue_allotment_item($branch_id,$prefix);
	}
	else if($type==31)
	{
		$table_name = 'hms_stock_issue_allotment_return_item';
		$field_name = 'return_no';
		$total_record = $CI->general->tot_inventory_stock_issue_allotment_return_item($branch_id,$prefix);
	}
	else if($type==32)
	{
		$table_name = 'hms_garbage_stock_item';
		$field_name = 'garbage_no';
		$total_record = $CI->general->tot_inventory_garbage_stock_item($branch_id,$prefix);
	}
	else if($type==33)
	{
		$table_name = 'path_item';
		$field_name = 'item_code';
		$total_record = $CI->general->tot_inventory_stock_item($branch_id,$prefix);
	}

	else if($type==34)
	{
		$table_name = 'hms_dialysis_booking';
		$field_name = 'booking_code';
		$total_record = $CI->general->tot_dialysis_book($branch_id,$prefix);
	}
	else if($type==74)
	{
		$table_name = 'hms_dialysis_appointment';
		$field_name = 'booking_code';
		$total_record = $CI->general->tot_dialysis_appointment($branch_id,$prefix);
	}
	
	else if($type==35)
	{
		$table_name = 'hms_vaccination_entry';
		$field_name = 'vaccination_code';
		$total_record = $CI->general->total_vaccination_entry($branch_id,$prefix);
	}
	// else if($type==36)
	// {
	// 	$total_record = $CI->general->total_vaccination_vendor($branch_id);
	// }
	else if($type==37)
	{
		$table_name = 'hms_vaccination_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_vaccination_purchase($branch_id,$prefix);
	}
	else if($type==38)
	{
		$table_name = 'hms_vaccination_purchase_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_vaccination_purchase_return($branch_id,$prefix);
	}
	else if($type==39)
	{
		$table_name = 'hms_vaccination_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_vaccination_billing($branch_id,$prefix);
	}
	else if($type==40)
	{
		$table_name = 'hms_vaccination_sale_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_vaccination_billing_return($branch_id,$prefix);
	}
	else if($type==41)
	{
		$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
		$table_name = 'hms_blood_donor';
		$field_name = 'donor_code';
		$total_record = $CI->blood_general_model->total_donors($branch_id,$prefix);
	}
	else if($type==43)
	{
		$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
		$table_name = 'hms_blood_patient_to_recipient';
		$field_name = 'issue_code';
		$total_record = $CI->blood_general_model->total_issued($branch_id,$prefix);
	}
	
	else if($type==45)
	{
		$CI->load->model('eye/general/eye_general_model','eye_general_model');
		$table_name = 'hms_opd_booking';
		$field_name = 'booking_code';
		$booking_type = '4';
		$total_record = $CI->eye_general_model->total_camp_booking($branch_id,$prefix);
	}
	else if($type==48)
	{
		$table_name = 'hms_estimate_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_sale_estimate($branch_id,$prefix);
	}
	
	else if($type==49)
	{
		$table_name = 'hms_estimate_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_purchase_estimate($branch_id,$prefix);
	}
	else if($type==51)
	{
		$table_name = 'hms_indent_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_indent_sale($branch_id,$prefix);
	}
	else if($type==52)
	{
		$table_name = 'hms_indent_sale_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_indent_sale_return($branch_id,$prefix);
	}
	else if($type==53)
	{
		$table_name = 'hms_ambulance_booking';
		$field_name = 'booking_no';
		
		$total_record = $CI->general->total_ambulance_booking($branch_id,$prefix);
	}
	else if($type==55)
	{
		$table_name = 'hms_day_care_booking';
		$field_name = 'booking_code';
		$booking_type = '5';
		$total_record = $CI->general->total_day_care_booking($branch_id,$prefix);
		//echo $CI->db->last_query(); exit;
	}
	else if($type==56)
	{
		$table_name = 'hms_opd_booking';
		$field_name = 'booking_code';
		$booking_type = '2';
		$opd_type ='1';
		$total_record = $CI->general->total_opd_emergency_booking($branch_id,$prefix);
	}
	else if($type==60)
	{
		$table_name = 'hms_day_care_booking';
		$field_name = 'day_discharge_bill_no';
		$total_record = $CI->general->total_opd_booking_discharged($branch_id,$prefix);
	}
	
	else if($type==61)
	{
		$table_name = 'hms_customers';
		$field_name = 'customer_code';
		$total_record = $CI->general->total_customer($branch_id,$prefix);
	}	
	else if($type==62)
	{
		$table_name = 'hms_canteen_garbage_stock_item';
		$field_name = 'garbage_no';
		$total_record = $CI->general->tot_canteen_garbage_stock_item($branch_id,$prefix);
	}
	
	else if($type==63)
	{
		$table_name = 'hms_canteen_vendors';
		$field_name = 'vendor_id';
		$total_record = $CI->general->total_canteen_vendor($branch_id,$prefix);
	}
	
   else if($type==64)
	{
		$table_name = 'hms_canteen_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_canteen_purchase($branch_id,$prefix);
	}
	else if($type==65)
	{
		$table_name = 'hms_canteen_return';
		$field_name = 'return_no';
		$total_record = $CI->general->total_canteen_purchase_return($branch_id,$prefix);
	}	
	
	else if($type==66)
	{
		$table_name = 'hms_canteen_estimate_purchase';
		$field_name = 'purchase_id';
		$total_record = $CI->general->total_canteen_purchase_estimate($branch_id,$prefix);
	}
	else if($type==67)
	{
		$table_name = 'hms_canteen_sale';
		$field_name = 'sale_no';
		$total_record = $CI->general->total_canteen_sale($branch_id,$prefix);
	}
	else if($type==68)
	{
		$table_name = 'hms_canteen_master_entry';
		$field_name = 'product_code';
		$total_record = $CI->general->total_canteen_master_entry($branch_id,$prefix);
	}
	else if($type==69)
	{
		$table_name = 'hms_canteen_products';
		$field_name = 'product_code';
		$total_record = $CI->general->total_canteen_products($branch_id,$prefix);
	}
	
	else if($type==71)
	{
		$table_name = 'hms_ambulance_gda_staff';
		$field_name = 'reg_no';
		$total_record = $CI->general->total_ambulance_gda_staff($branch_id,$prefix);
	}
	
		/* ambulance  enquiry*/
   else if($type==70)
	{
		$table_name = 'hms_ambulance_enquiry';
		$field_name = 'enquiry_no';
		$total_record = $CI->general->total_ambulance_enquiry($branch_id,$prefix);
	}
	else if($type==72)
	{
		$table_name = 'crm_leads';
		$field_name = 'crm_code'; 
		$total_record = $CI->general->total_crm_lead($branch_id,$prefix);
	}
	else if($type==73) //pathology invoice number
	{
		$table_name = 'path_test_booking_invoice';
		$field_name = 'lab_invoice_no';
		$total_record = $CI->general->path_total_invoice_booking($branch_id,$prefix);
	}
	
	
	
	/*if($result)
	{  

        $prefix = $result->prefix;
        if(empty($total_record) || $total_record==0)
        {
           $counter = $total_record+$result->start_num;
        }
        else
        {
        	$counter = $total_record+1+$result->start_num;
        }
    }
    else
    {
    	$prefix='';
    	$counter=$total_record+1;
    }*/
    //echo $total_record;die;
    if(!empty($result))
	{  
		
		if($result->start_num>1)
		{
	       //$start_num = $result->start_num-1; 
	       $start_num = $result->start_num; 
		}
		else if($result->start_num==1)
		{
		   $start_num = $result->start_num-1;
		}
		else
		{
			$start_num = 0;
		}

		$counter = $total_record+1+$start_num;
		//echo $start_num;
	}
	else
	{
	   $prefix='';
	   $counter=$total_record+1;
	}
    
    $current_year = date('Y');
    $current_month = date('m');
    $current_date = date('Y-m-d');
    $current_year_pre = date('y');
    $current_day_pre = date('d');
    
    //$prefix = str_replace('{year}', $current_year, $prefix);
    $prefix = str_replace('{small_year}', $current_year_pre, $prefix);
    $prefix = str_replace('{small_month}', $current_month, $prefix);
    $prefix = str_replace('{small_day}', $current_day_pre, $prefix);
    
    
    //$prefix = str_replace('{year}', $current_year, $prefix);
    $prefix = str_replace('{year}', $current_year, $prefix);
    $prefix = str_replace('{month}', $current_month, $prefix);
    $prefix = str_replace('{day}', $current_day_pre, $prefix);
    $prefix = str_replace('{YEAR}', $current_year, $prefix);
    $prefix = str_replace('{MONTH}', $current_month, $prefix);
    $prefix = str_replace('{DAY}', $current_day_pre, $prefix);
    
    $prefix = str_replace('{currentdate}', $current_date, $prefix);
    $prefix = str_replace('{CURRENTDATE}', $current_date, $prefix);
    
    $response = strtoupper($prefix).$counter;
    //echo $response;
   // echo $response; die;
    //echo $booking_type; die;
    if($user_data['parent_id']!='0')
    {
    	$response = check_unique_id($response,$table_name,$field_name,$prefix,$suffix,$booking_type,$result->modified_date);	
    }
     
	return $response;
}

 function check_unique_id20211129($code="",$table_name="",$field_name="",$prefix="",$suffix="",$booking_type='',$updated_date='')
 {
 	$CI =& get_instance();  //echo $code;die;
    if(!empty($code) && !empty($table_name) && !empty($field_name) && !empty($prefix) && !empty($suffix))
    {
		$user_data = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
		$result = $CI->general->check_unique_id($code,$table_name,$field_name,$booking_type);
	//	echo $CI->db->last_query();
		if(!empty($result))
		{   
            $last_unique_id = $CI->general->get_last_unique_id($table_name,$field_name,$booking_type);
           // echo $CI->db->last_query(); //exit;
            //print_r($last_unique_id);die;
		    $count_prefix = strlen($prefix);  
		    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
		    $code = $prefix.($start_part+1);
		   // echo $code;
		    return $code;
		}
		else
		{
		    if(strtotime(date('d-m-Y',strtotime($updated_date)))!= strtotime(date('Y-m-d')))
			{
				 $last_unique_id = $CI->general->get_last_unique_id($table_name,$field_name,$booking_type);
				 	
	            
			    /*$count_prefix = strlen($prefix);  
			    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
			    $code = $prefix.($start_part+1); 
				return $code;*/
				if(!empty($last_unique_id))
	            {
	                 $count_prefix = strlen($prefix);  
    			    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
    			    $code = $prefix.($start_part+1); 
    				return $code;
	            }
	            else
	            {
	                return $code;
	            }
			}
			else
			{
			    //echo $code;
				return $code;
			}
			
		    /*$last_unique_id = $CI->general->get_last_unique_id($table_name,$field_name,$booking_type);
            //print_r($last_unique_id);die;
		    $count_prefix = strlen($prefix);  
		    $start_part = substr($last_unique_id[$field_name], $count_prefix,100000000);
		    $code = $prefix.($start_part+1); 
			return $code;*/
		}
    }
   
    return $code;
 }

 function check_hospital_receipt_no()
 {
 		$CI =& get_instance();  //echo $code;die;
		$user_data = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
		$table_name='hospital_reciept_setting';
		$result = $CI->general->check_hospital_receipt_no($table_name);
	     $data=array();
		if(!empty($result))
		{   
			$table_name="hms_branch_hospital_no";
			$pr_fix=$result[0]->prefix;
            $tot_unique_id = $CI->general->get_tot_hospital_receipt_no($table_name,$pr_fix);

            if(empty($tot_unique_id))
            {
             $prefix= $result[0]->prefix;
             $start_part=$result[0]->suffix;
             $code = $prefix.($start_part);	
             $data= array('prefix'=>$prefix,'suffix'=>$result[0]->suffix);


            }
            else
            {
            
               	//$count_data = count($tot_unique_id);
               
               	 $last_unique_id = $CI->general->get_last_hospital_receipt_no($table_name,$pr_fix);
               	 
               	 $section_id=$last_unique_id['section_id'];
    			 //$parent_id=$last_unique_id['parent_id'];

    			 /*$check_deleted = $CI->general->check_receipt_number($section_id,$parent_id);
                  if($check_deleted==1)
                  {
                  	 $start_part=$last_unique_id['reciept_suffix']+1;
                  }
                  else
                  {
                  	$start_part=$last_unique_id['reciept_suffix'];
                  }*/
               	 
                  $start_part=$last_unique_id['reciept_suffix']+1;
            	  $code = $last_unique_id['reciept_prefix'].($start_part);	
            	  $data= array('prefix'=>$last_unique_id['reciept_prefix'],'suffix'=>$start_part);
             }
           
		     return $data;
		}
		else
		{
			return $data;
		}


 }

function get_setting_value($var_title="")
{
	//$setting_data = array();
	//$setting_data='';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_setting_value($var_title,$user_data['parent_id']); 
	//$setting_data = $result->setting_value;
	   if(isset($result)){
	   	$setting_data = $result->setting_value;
	   	return $setting_data;
	   }else{
	   	return false;
	   }
	}

function get_ipd_referral_doctor($ipd_id="")
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_ipd_referral_doctor($ipd_id); 
	//$setting_data = $result->setting_value;
	   if(isset($result))
	   {
	   	$setting_data = $result->referral_doctor;
	   	return $setting_data;
	   }
	   else
	   {
	   	return false;
	   }
}	



function auth_branch_users($user_id="")
{
	$CI =& get_instance();
	$CI->load->model('users/users_model','users');
	$result = $CI->users->auth_branch_users($user_id);
	if(empty($result))
	{
		redirect(base_url('error/401'));
	}
	else
	{
		return true;
	}
}
function unit_list($unit_id="")
{
	//echo $unit_id;
	$CI =& get_instance();
	$CI->load->model('medicine_entry/medicine_entry_model','medicine_entry');
	$result = $CI->medicine_entry->unit_list($unit_id);
	return $result;
}
function vaccination_unit_list($unit_id="")
{
	//echo $unit_id;
	$CI =& get_instance();
	$CI->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
	$result = $CI->vaccination_entry->unit_list($unit_id);
	return $result;
}
function unit_second_list($unit_second_id="")
{
	$CI =& get_instance();
	$CI->load->model('medicine_entry/medicine_entry_model','medicine_entry');
	$result = $CI->medicine_entry->unit_second_list($unit_second_id);
	return $result;
}
function rack_list($rack_id="")
{
	//echo $rack_id;
	$CI =& get_instance();
	$CI->load->model('medicine_entry/medicine_entry_model','medicine_entry');
	$result = $CI->medicine_entry->rack_list($rack_id);
	return $result;
}
function vaccination_rack_list($rack_id="")
{
	//echo $rack_id;
	$CI =& get_instance();
	$CI->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
	$result = $CI->vaccination_entry->rack_list($rack_id);
	return $result;
}

function vendor_list($vendor_id="")
{
	//echo $rack_id;
	$CI =& get_instance();
	$CI->load->model('vendor/vendor_model','vendor');
	$result = $CI->vendor->get_by_id($vendor_id);
	return $result;
}
function manuf_company_list($company_id="")
{
	
	$CI =& get_instance();
	$CI->load->model('medicine_entry/medicine_entry_model','medicine_entry');
	$result = $CI->medicine_entry->manuf_company_list($company_id);
	return $result;
}
function vaccination_manuf_company_list($company_id="")
{
	
	$CI =& get_instance();
	$CI->load->model('vaccination_entry/vaccination_entry_model','vaccination_entry');
	$result = $CI->vaccination_entry->manuf_company_list($company_id);
	return $result;
}

function get_doctor_name($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_doctor_name($doctor_id);
	return $result;
}
function get_hospital_name($hospital_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_hospital_name($hospital_id);
	return $result;
}
function get_test_name($test_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_test_name($test_id);
	return $result;
}
function get_profile_name($profile_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_profile_name($profile_id);
	return $result;
}
function get_medicine_name($medicine_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_medicine_name($medicine_id);
	return $result;
}



function employee_type_name($id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->employee_type_name($id);
	return $result;
}

function get_specilization_name($specilization_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_specilization_name($specilization_id);
	return $result;
}

function getDoctor($id="")
{
  $CI =& get_instance();
  $limit = "";
  $where = "";
 
  if($id!="")
  {
    $where .= " AND `specilization_id` = '".$id."'";
  }
  
  
 $sql = "select * from `hms_doctors` where status = '1' and  	is_deleted='0' ".$where." order by doctor_name asc ".$limit;
  $query = $CI->db->query($sql);
  $data = "";
  if($query->num_rows>0)
  {
	$data = $query->result();
  }
  return $data;
 }


function get_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_prescription_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_prescription_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_prescription_medicine_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_prescription_medicine_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function dd($data)
{
	echo "<pre>";
    print_r($data);
    echo "</pre>";
	die;
}
function get_form_name()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_form_name(); 
	return $result;
}
function get_form_name_edit()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_form_name_edit(); 
	return $result;
}
function get_email_form_name()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_email_form_name(); 
	return $result;
}
function get_email_form_name_edit()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_email_form_name_edit(); 
	return $result;
}

function get_printer_type()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_printer_type(); 
	return $result;
}
function get_printer_paper_type($parent_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_printer_paper_type($parent_id); 
	return $result;
}
function get_exp_category($exp_cat_id="")
{
	$CI =& get_instance();
	$CI->load->model('expense_category/Expense_category_model','expense_category');
	$result = $CI->expense_category->get_by_id($exp_cat_id);
	return $result;
}
function nested_branch($branch_id)
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model','branch');
	$result = $CI->branch->getChildren($branch_id);
	return $result;
}
function nested_parent_branch($branch_id)
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model','branch');
	$result = $CI->branch->getParent($branch_id);
	return $result;
}
function get_branch_name($branch_id)
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model','branch');
	$result = $CI->branch->get_branch_name($branch_id);
	return $result;
}

function get_simulation_name($simulation_id)
{
	$CI =& get_instance();
	$CI->load->model('simulation/simulation_model','simulation');
	$result = $CI->simulation->get_simulation_name($simulation_id);
	return $result;
}

function get_package_price($package_id)
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_package_price($package_id);
	return $result;
}

function get_sales_medicine_list(){

	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_sales_medicine_list();
	return $result;
}

function get_sales_return_medicine_list(){
   $CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_sales_return_medicine_list();
	return $result;
}

function get_purchase_medicine_list(){
   $CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_purchase_medicine_list();
	return $result;
}

function get_purchase_return_medicine_list(){
   $CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_purchase_return_medicine_list();
	return $result;
}

function all_medicine_list()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->all_medicine_list();
	return $result;
	
}
function all_vaccination_list()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->all_vaccination_list();
	//print_r($result);die;
	return $result;
}

function get_batch_med_qty($mid="",$batch_no=""){
	$CI =& get_instance();
	$CI->load->model('medicine_stock/medicine_stock_model','medicine_stock');;
	$result = $CI->medicine_stock->get_batch_med_qty($mid,$batch_no);
	return $result;
}

function get_bank_name($bid=""){
	$CI =& get_instance();
	$CI->load->model('bank_account/bank_account_model','bank_account');;
	$result = $CI->bank_account->get_bank_name($bid);
	//print_r($result);die;
	return $result;
}
function get_bank_namewith_account(){
	$CI =& get_instance();
	$CI->load->model('bank_account/bank_account_model','bank_account');;
	$result = $CI->bank_account->get_bank_namewith_account();
	//print_r($result);die;
	return $result;
}

function get_doctor_signature($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_doctor_signature($doctor_id);
	return $result;
}
function get_user_id($branch_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_user_id($branch_id);
	return $result;
}

function get_sms_setting($var_title="",$type='1')
{
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_sms_setting($var_title,$user_data['parent_id']); 
	$setting_data='';
	if(!empty($result))
	{
		if($type==1)
		{
			$setting_data = $result->sms_status;
		}
		elseif($type==2)
		{
			$setting_data = $result->email_status;
		}	
	}	
	
	
	return $setting_data;
}

function sms_template_format($template="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->sms_template_format($template,$users_data['parent_id']);
	return $result;

}

function sms_url($branch_id='')
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->sms_url($branch_id);
	$url = $result->url;
	return $url;
}

function send_sms($module='',$template='',$patient_name='',$mobile_no='',$parameter='')
{ 
    $CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	if(!empty($mobile_no))
	{
		$get_data = get_sms_setting($module,1);
		if($get_data==1)
		{
			$sms_format = sms_template_format($template);
			
			if(!empty($sms_format))
			{
				//echo $sms_format->template; exit;
				//print_r($parameter);die;
				$message_template_formate =  str_replace(array_keys($parameter),$parameter,$sms_format->template);
				//echo $message_template_formate; exit;
				$sms_url = sms_url();
			//	echo $sms_url;die;
				if(!empty($sms_url))
				{
					$url = $sms_url;
					
					 $feedback_url = base_url('feedback/?token=').base64_encode($mobile_no);
                    $message_template_formate = str_replace("{feedback_url}",$feedback_url,$message_template_formate);
                    //echo $message_template_formate;die;
					$url = str_replace("{mobile_no}",$mobile_no,$url);
					$url = str_replace("{message}",rawurlencode($message_template_formate),$url);
					//echo $url;die;
					/* if($users_data['parent_id']=='110')
                  {
					echo $url; exit;
                  }*/
                   if($users_data['parent_id']=='114')
                   {
                       //paras
                       //Your authentication key
                        $authKey = "19177AFKx1nK1q614dcd51P15";
                        //Multiple mobiles numbers separated by comma
                        $mobileNumber = $mobile_no;
                        //Sender ID,While using route4 sender id should be 6 characters long.
                        $senderId = "PARSLB";
                        //Your message to send, Add URL encoding here.
                        //$message = urlencode("Hello {#var#}, Thank you for using service of Paras Pathology Lab. We Wish you good Health. You Sample is under process. cont. 9827439031");
                       $message_template_formate =  str_replace(array_keys($parameter),$parameter,$sms_format->template); 
                       //echo $message_template_formate; die;
                       $message = urlencode($message_template_formate);
                        
                        //Define route 
                        $route = "4";
                        
                        //Define country 
                        $country = "91";
                        
                        //Prepare you post parameters
                        $postData = array(
                            'authkey' => $authKey,
                            'mobiles' => $mobileNumber,
                            'message' => $message,
                            'sender' => $senderId,
                            'route' => $route,
                        	'country' => $country
                        );
                        
                        //API URL
                        $urls="https://sms.hytechsms.com/api/sendhttp.php";
                        
                        // init the resource
                        $ch = curl_init();
                        curl_setopt_array($ch, array(
                            CURLOPT_URL => $urls,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => $postData
                            //,CURLOPT_FOLLOWLOCATION => true
                        ));
                        
                        
                        //Ignore SSL certificate verification
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        
                        
                        //get response
                        $output = curl_exec($ch);
                        
                        //Print error if any
                        if(curl_errno($ch))
                        {
                            echo 'error:' . curl_error($ch);
                        }
                        
                        curl_close($ch);
                        //echo $output;
                   }
                   else
                   {
                      if(!empty($mobile_no) && isset($mobile_no) && !empty($url))
    					{
    						$ch = curl_init($url);
    						curl_setopt($ch, CURLOPT_HEADER, 0);
    						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    						curl_setopt($ch, CURLOPT_VERBOSE, true);
    						$output = curl_exec($ch);      
    						curl_close($ch); 
    						// Display MSGID of the successful sms push
    						//echo $output; exit;
    					} 
                   }
					
				}
				

			}
			
			
		}


		
	}
		
}

function send_birthday_sms($module='',$template='',$mobile_no='',$parameter='',$message,$type='',$person_id='')
{
	$CI =& get_instance();
	if(!empty($mobile_no))
	{
		
				$sms_url = sms_url();
				if(!empty($sms_url))
				{
					$url = $sms_url;
					$url = str_replace("{mobile_no}",$mobile_no,$url);
					$url = str_replace("{message}",rawurlencode($message),$url);
					//echo $url; exit;
					if(!empty($mobile_no) && isset($mobile_no) && !empty($url))
					{
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_VERBOSE, true);
						$output = curl_exec($ch);      
						curl_close($ch); 
						// Display MSGID of the successful sms push
						//echo $output; exit;
					}
				
			
		}
		$CI->load->model('general/general_model','generals');
		$CI->generals->update_message($type,$person_id);
		
	}
		
}


function get_email_template($template="", $branch_id="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	if(!empty($branch_id))
	{
       $branch_id = $branch_id;
	}
	else
	{
		$branch_id = $users_data['parent_id'];
	}  
	$CI->load->model('general/general_model','generals');
	$result = $CI->generals->get_email_template($template,$branch_id);
	return $result;

}
function email_setting($branch_id="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	if(!empty($branch_id))
	{
       $branch_id = $branch_id;
	}
	else
	{
		$branch_id = $users_data['parent_id'];
	}
	$CI->load->model('general/general_model','general_m');
	$result = $CI->general_m->email_setting($branch_id);
	//$url = $result->url;
	return $result;
}
function get_charges_according($room_id="",$room_charge_type="",$panel_company_id='',$types='')
{
    $CI =& get_instance();
	$CI->load->model('ipd_room_list/ipd_room_list_model','ipd_room_list');
	$result = $CI->ipd_room_list->get_charges_according($room_id,$room_charge_type,$panel_company_id,$types);
	return $result;
}

function get_room_charge_according_to_branch($branch_id){
	$CI =& get_instance();
	$CI->load->model('ipd_room_charge_type/ipd_room_charge_type_model','ipd_room_charge_type');
	$result = $CI->ipd_room_charge_type->get_room_charge_according_to_branch($branch_id);
	return $result;
}
function get_room_charge_accordint_to_id($branch_id,$charge_id,$room_type,$room_id){
	$CI =& get_instance();
	$CI->load->model('ipd_room_charge_type/ipd_room_charge_type_model','ipd_room_charge_type');
	$result = $CI->ipd_room_charge_type->get_room_charge_accordint_to_id($branch_id,$charge_id,$room_type,$room_id);
	return $result;

}

function get_panel_room_charge_accordint_to_id($branch_id,$charge_id,$room_type,$room_id,$panel_id){
	$CI =& get_instance();
	$CI->load->model('ipd_room_charge_type/ipd_room_charge_type_model','ipd_room_charge_type');
	$result = $CI->ipd_room_charge_type->get_panel_room_charge_accordint_to_id($branch_id,$charge_id,$room_type,$room_id,$panel_id);
	return $result;

}
function count_bad($room_id){
	$CI =& get_instance();
	$CI->load->model('ipd_room_list/ipd_room_list_model','ipd_room_list');
	$result = $CI->ipd_room_list->count_bad($room_id);
	return $result;

}
function get_ipd_particular($particular_id='')
{
	$CI =& get_instance();
	$CI->load->model('ipd_perticular/ipd_perticular_model','ipd_perticular_list');
	$result = $CI->ipd_perticular_list->get_by_id($particular_id);
	return $result;
}

function get_patient_according_to_ipd($ipd_no="",$patient_id){
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_patient_according_to_ipd($ipd_no,$patient_id);
	//$url = $result->url;
	return $result;
}

function get_discharge_summary_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_discharge_summary_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_progress_report_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_progress_report_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ot_package_charge($ot_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_ot_package_charge($ot_id);
	return $result;
}

function get_particular_charge($particular_id="",$panel_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_particular_charge($particular_id,$panel_id);
	return $result;
}

function get_religion_name($id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_religion_name($id);
	return $result;
}
function get_doctor_branch($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('branch/branch_model');
	
	$result = $CI->branch_model->get_doctor_branch($doctor_id);
	return $result;
}
function get_doctor($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_doctor($doctor_id);
	return $result;
}


//Pathology functions//

/*function auth_users()
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	if(!isset($auth_users) || empty($auth_users))
	{
        redirect(base_url('login'));
	} 	
}*/

/*function unauthorise_permission($section="",$action="")
{
   $CI =& get_instance();
   $users_data = $CI->session->userdata('auth_users');
   if(!empty($section) && !empty($action))
   {
        if(in_array($section,$users_data['permission']['section']) && in_array($action,$users_data['permission']['action']))
        {
        	return true;
        }
        else
        {
        	redirect('401');
        }
   }
}*/
/*function mandatory_section_field_list($section_id='')
{
	$CI =& get_instance();
	$CI->load->model('mandatory_field_manage/Mandatory_field_manage_model','mandatory_field_manage');
	$result = $CI->mandatory_field_manage->mandatory_section_field_list($section_id);
	return $result;
    
}*/


/*function auth_dashboard()
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	if(!isset($auth_users) || empty($auth_users))
	{
        redirect(base_url('login'));
	} 
}*/

function donwload_test($test_id="")
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	$CI->load->model('test_master/test_master_model','test_master');
	$test_id = $CI->test_master->download_test($test_id); 
	return $test_id;
}

/*function get_permission_attr($section_id="",$action_id="")
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$total_branch = $CI->general->total_branch($auth_users['parent_id']);  
	$result = $CI->general->get_permission_attr($section_id,$action_id); 
	$remaining_branch = $result['attribute_val'];// - $total_branch;
	if($auth_users['users_role']==1)
	{
		$remaining_branch = 1;
	}
	return $remaining_branch;
}*/

/*function users_role_list()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->user_role_list();
	return $result;
}*/

/*function country_list()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->country_list();
	return $result;
}*/

/*function state_list($country_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->state_list($country_id);
	return $result;
}

function city_list($state_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->city_list($state_id);
	return $result;
}*/

/*function action_list($section_id="")
{
	$CI =& get_instance();
	$CI->load->model('permission/permission_model','permission');
	$result = $CI->permission->action_list($section_id);
	return $result;
}*/

function get_test($test_id="")
{
	$CI =& get_instance();
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->get_by_id($test_id);
	return $result;
}

/*function get_exp_category($exp_cat_id="")
{
	$CI =& get_instance();
	$CI->load->model('expense_category/Expense_category_model','expense_category');
	$result = $CI->expense_category->get_by_id($exp_cat_id);
	return $result;
}*/

function get_employee($emp_id="")
{
	$CI =& get_instance();
	$CI->load->model('employee/employee_model','employee');
	$result = $CI->employee->get_by_id($emp_id);
	return $result;
}

function test_heads_list($dept_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->test_heads_list($dept_id);
	return $result;
}



function test_list_name($id="",$field='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->test_list_name($id,$field);
	return $result;
}

function unite_name($id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->unite_name($id);
	return $result;
}

/*function get_doctor_signature($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_doctor_signature($doctor_id);
	return $result;
}*/

function get_default_val($highlight="0",$vals="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_default_val($highlight,$vals);
	return $result;
}

function get_default_val_to_test($vals="",$highlight="0",$test_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_default_val_to_test($vals,$highlight,$test_id);
	return $result;
}


function get_all_test_list($booking_id="",$thead_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_all_test_list($booking_id,$thead_id);
	return $result;
}

function get_all_profile_test_list($booking_id="",$thead_id="",$profile_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_all_profile_test_list($booking_id,$thead_id,$profile_id);
	return $result;
}

function get_test_report_postion($range_from="",$range_to='',$result='')
{
		$range_average = ($range_from+$range_to)/2;
		if($result>$range_average && $result < $range_to)
		{
			return;
		}
		elseif($result < $range_average && $result > $range_from)
		{
			return ;
		}
		elseif($result < $range_average && $result > $range_from)
		{
			return;
		}
		elseif($result < $range_average && $result < $range_from)
		{
			return 'Low';
		}
		elseif($result > $range_average && $result > $range_to)
		{
			return 'High';
		}

}
 

function branch_creation_rights()
{
	$CI =& get_instance();
    $users_data = $CI->session->userdata('auth_users');
    $user_data = $CI->session->userdata('auth_users');
	$branch_attribute = get_permission_attr(1,2); 
	if($users_data['users_role']!=1 && in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) && $branch_attribute>0)
	{ 
       return true;
	}
	else
	{
		if($users_data['users_role']!=1)
		{
          redirect(base_url('401'));		
		}
		else
		{
			return true;
		} 
	}
}

/*function get_printer_paper_type($parent_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_printer_paper_type($parent_id); 
	return $result;
}*/

function doctor_test_rate($branch_id="",$doctor_id="",$test_id="")
{
  if(!empty($test_id))
  {
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->doctor_test_rate($branch_id,$doctor_id,$test_id); 
	return $result;
  }
}

function panel_test_rate($branch_id="",$panel_id="",$test_id="")
{
  if(!empty($test_id))
  {
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->panel_test_rate($branch_id,$panel_id,$test_id); 
	return $result;
  }
}

function get_rate_plan($id="")
{
  if(!empty($id))
  {
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('rate/rate_model','rate');
	$result = $CI->rate->get_by_id($id); 
	return $result;
  }
}

function today_booking()
{ 
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->today_booking(); 
	return $result; 
}

function letest_pending_report()
{ 
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->letest_pending_report(); 
	return $result; 
}

/*function get_simulation_name($simulation_id)
{
	$CI =& get_instance();
	$CI->load->model('simulation/simulation_model','simulation');
	$result = $CI->simulation->get_simulation_name($simulation_id);
	return $result;
}*/

/*function employee_type_name($id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->employee_type_name($id);
	return $result;
}*/

/*function get_form_name_edit()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_form_name_edit(); 
	return $result;
}

function get_form_name()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_form_name(); 
	return $result;
}*/

/*function get_sms_setting($var_title="",$type='1')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_sms_setting($var_title,$user_data['parent_id']); 
	
	if($type==1)
	{
		$setting_data = $result->sms_status;
	}
	elseif($type==2)
	{
		$setting_data = $result->email_status;
	}
	
	return $setting_data;
}*/

/*function sms_template_format($template="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->sms_template_format($template,$users_data['parent_id']);
	return $result;

}*/

/*function sms_url()
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->sms_url();
	$url = $result->url;
	return $url;
}*/

/*function send_sms($module='',$template='',$mobile_no='',$parameter='')
{
	if(!empty($mobile_no))
	{
		$get_data = get_sms_setting($module,1);
		if($get_data==1)
		{
			$sms_format = sms_template_format($template);
			
			if(!empty($sms_format))
			{
				//echo $sms_format->template; exit;
				//print_r($parameter);die;
				$message_template_formate =  str_replace(array_keys($parameter),$parameter,$sms_format->template);
				//echo $message_template_formate; exit;
				$sms_url = sms_url();
				if(!empty($sms_url))
				{
					$url = $sms_url;
					$url = str_replace("{mobile_no}",$mobile_no,$url);
					$url = str_replace("{message}",rawurlencode($message_template_formate),$url);
					//echo $url; exit;
					if(!empty($mobile_no) && isset($mobile_no) && !empty($url))
					{
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_VERBOSE, true);
						$output = curl_exec($ch);      
						curl_close($ch); 
						// Display MSGID of the successful sms push
						//echo $output; exit;
					}
				}
				

			}
			
			
		}


		
	}
		
}*/


/*function get_email_form_name()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_email_form_name(); 
	return $result;
}*/

/*function get_email_form_name_edit()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_email_form_name_edit(); 
	return $result;
}*/





function get_test_under($id="")
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test/test_model','test');
	$result = $CI->test->get_test_under($id); 
	return $result;
}

function get_profile($id="")
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test_profile/test_profile_model','test_profile');
	$result = $CI->test_profile->get_by_id($id); 
	return $result;
}

function get_birthday_sms_template_format($branch_id="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_birthday_sms_template_format($branch_id);
	return $result;

}
function get_advance_payment_pay_mode_field($branch_id="",$ipd_id="",$section_id="",$type="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_advance_payment_pay_mode_field($branch_id,$ipd_id,$section_id,$type);
	return $result;

}
function get_item_quantity($ids="",$category_ids="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('item_manage/item_manage_model','item_manage');
	$result = $CI->item_manage->get_item_quantity($ids,$category_ids);
	return $result;

}



function generate_barcode($text='',$orientation='',$code_type='',$size='')
{
		error_reporting(0);
		/*$text = 'arvind_hsdhsdsh';//$_POST['str'];
		$orientation = 'horizontal';//$_POST['orientation'];
		$code_type = 'code128';//$_POST['codetype'];
		$size = '45';//$_POST['size'];*/
		$code_string = "";
		
		// Translate the $text into barcode the correct $code_type
		if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
			$chksum = 104;
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211214" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code128a" ) {
			$chksum = 103;
			$text = strtoupper($text); // Code 128A doesn't support lower case
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211412" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code39" ) {
			$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
			}
			$code_string = "1211212111" . $code_string . "121121211";
		} elseif ( strtolower($code_type) == "code25" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
			$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
					if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
						$temp[$X] = $code_array2[$Y];
				}
			}
			for ( $X=1; $X<=strlen($text); $X+=2 ) {
				if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
					$temp1 = explode( "-", $temp[$X] );
					$temp2 = explode( "-", $temp[($X + 1)] );
					for ( $Y = 0; $Y < count($temp1); $Y++ )
						$code_string .= $temp1[$Y] . $temp2[$Y];
				}
			}
			$code_string = "1111" . $code_string . "311";
		} elseif ( strtolower($code_type) == "codabar" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
			$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
					if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
						$code_string .= $code_array2[$Y] . "1";
				}
			}
			$code_string = "11221211" . $code_string . "1122121";
		}
		// Pad the edges of the barcode
		$code_length = 20;
		for ( $i=1; $i <= strlen($code_string); $i++ )
			$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
		if ( strtolower($orientation) == "horizontal" ) {
			$img_width = $code_length;
			$img_height = $size;
		} else {
			$img_width = $size;
			$img_height = $code_length;
		}
		$image = imagecreate($img_width, $img_height);
		$black = imagecolorallocate ($image, 0, 0, 0);
		$white = imagecolorallocate ($image, 255, 255, 255);
		imagefill( $image, 0, 0, $white );
		$location = 10;
		for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
			$cur_size = $location + ( substr($code_string, ($position-1), 1) );
			if ( strtolower($orientation) == "horizontal" )
				imagefilledrectangle( $image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black) );
			else
				imagefilledrectangle( $image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black) );
			$location = $cur_size;
		}
		// Draw barcode to the screen
		$path = DIR_UPLOAD_BARCODE_PATH;
		chmod($path, 0777);
		header ('Content-type: image/png');
		//imagepng($image,$path);	
		imagepng($image, $path . "/$text.png");
		imagedestroy($image);	
		return $text;
		/*echo "<div align='center'>";
	    echo "<img src='image/".$text.".png'>";
	    echo "<br><br>"."<b>".$text."</b>";
	    echo "</div>";
		$dpath = "image/$text.png";
		echo "<br>";
		echo "<div align='center'><a href='download.php?name=$dpath'><b>Click here to download</b></a></div>";*/
}

function generate_barcode1feb2021($text='',$orientation='',$code_type='',$size='')
{
		error_reporting(0);
		/*$text = 'arvind_hsdhsdsh';//$_POST['str'];
		$orientation = 'horizontal';//$_POST['orientation'];
		$code_type = 'code128';//$_POST['codetype'];
		$size = '45';//$_POST['size'];*/
		$code_string = "";
		
		// Translate the $text into barcode the correct $code_type
		if ( in_array(strtolower($code_type), array("code128", "code128b")) ) {
			$chksum = 104;
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211214" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code128a" ) {
			$chksum = 103;
			$text = strtoupper($text); // Code 128A doesn't support lower case
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211412" . $code_string . "2331112";
		} elseif ( strtolower($code_type) == "code39" ) {
			$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
			}
			$code_string = "1211212111" . $code_string . "121121211";
		} elseif ( strtolower($code_type) == "code25" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
			$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
					if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
						$temp[$X] = $code_array2[$Y];
				}
			}
			for ( $X=1; $X<=strlen($text); $X+=2 ) {
				if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
					$temp1 = explode( "-", $temp[$X] );
					$temp2 = explode( "-", $temp[($X + 1)] );
					for ( $Y = 0; $Y < count($temp1); $Y++ )
						$code_string .= $temp1[$Y] . $temp2[$Y];
				}
			}
			$code_string = "1111" . $code_string . "311";
		} elseif ( strtolower($code_type) == "codabar" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
			$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
					if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
						$code_string .= $code_array2[$Y] . "1";
				}
			}
			$code_string = "11221211" . $code_string . "1122121";
		}
		// Pad the edges of the barcode
		$code_length = 20;
		for ( $i=1; $i <= strlen($code_string); $i++ )
			$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
		if ( strtolower($orientation) == "horizontal" ) {
			$img_width = $code_length;
			$img_height = $size;
		} else {
			$img_width = $size;
			$img_height = $code_length;
		}
		$image = imagecreate($img_width, $img_height);
		$black = imagecolorallocate ($image, 0, 0, 0);
		$white = imagecolorallocate ($image, 255, 255, 255);
		imagefill( $image, 0, 0, $white );
		$location = 10;
		for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
			$cur_size = $location + ( substr($code_string, ($position-1), 1) );
			if ( strtolower($orientation) == "horizontal" )
				imagefilledrectangle( $image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black) );
			else
				imagefilledrectangle( $image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black) );
			$location = $cur_size;
		}
		// Draw barcode to the screen
		$path = DIR_UPLOAD_BARCODE_PATH;
		chmod($path, 0777);
		header ('Content-type: image/png');
		//imagepng($image,$path);	
		imagepng($image, $path . "/$text.png");
		imagedestroy($image);	
		return $text;
		/*echo "<div align='center'>";
	    echo "<img src='image/".$text.".png'>";
	    echo "<br><br>"."<b>".$text."</b>";
	    echo "</div>";
		$dpath = "image/$text.png";
		echo "<br>";
		echo "<div align='center'><a href='download.php?name=$dpath'><b>Click here to download</b></a></div>";*/
}

function barcode_setting($branch_id="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	if(!empty($branch_id))
	{
       $branch_id = $branch_id;
	}
	else
	{
		$branch_id = $users_data['parent_id'];
	}
	$CI->load->model('general/general_model','general_m');
	$result = $CI->general_m->barcode_setting($branch_id);
	//$url = $result->url;hms_branch_barcode
	return $result;
}

function get_sub_menu($parent_id="")
{
	$CI =& get_instance();
	
	$CI->load->model('menu/menu_model','menu_m');
	$result = $CI->menu_m->get_master_menu($parent_id);
	//$url = $result->url;hms_branch_barcode
	return $result;
}

function get_vitals_value($id='',$booking_id='',$type='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_vitals_value($id,$booking_id,$type);
	return $result;
}
function get_units_by_item($item_ids='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_units_by_item($item_ids);
	return $result;
}
function get_units_by_id($unit_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_units_by_id($unit_id);
	return $result;
}

/* parent exits or not in unit */
function get_checkparent_unit($unit_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_checkparent_unit($unit_id);
	return $result;
}

function get_patient_address()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_patient_address();
	return $result;
}

function get_branch_address()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_branch_address();
	return $result;
}



function get_doctor_schedule_time($day_id='',$doctor_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_doctor_schedule_time($day_id,$doctor_id);
	return $result;
}
function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) 
{
	$sort_col = array();
	foreach ($arr as $key=> $row) {
    $sort_col[$key] = $row[$col];
	}
	array_multisort($sort_col, $dir, $arr);
}

function get_profile_print_status($var_title="")
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_profile_print_status($var_title,$user_data['parent_id']); 
	if(isset($result))
	{
	   	return $result;
	}
	else
	{
		return false;
	}
}
function get_under_maintenance()
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_under_maintenance();
	if(!empty($result) && $result[0]->status==1)
         { 
           redirect(base_url('under_maintenance_page'));
         }
}
/* child exits or not in unit */


// function to get attented doctors name for IPD booking added on 09-02-2018
function get_ipd_assigned_doctors_name($ipd_bkng_id)
{
	$CI =& get_instance();
	$CI->load->model('ipd_booking/ipd_booking_model','ipd_model');
	$result = $CI->ipd_model->get_ipd_assigned_doctors_name($ipd_bkng_id);
	return $result;
}
// function to get attented doctors name for IPD booking added on 09-02-2018


function get_dialysis_assigned_doctors_name($ipd_bkng_id)
{
	$CI =& get_instance();
	$CI->load->model('dialysis_booking/dialysis_booking_model','dialysis_model');
	$result = $CI->dialysis_model->get_dialysis_assigned_doctors_name($ipd_bkng_id);
	return $result;
}

function get_dialysis_package_name($ipd_bkng_id)
{
	$CI =& get_instance();
	$CI->load->model('dialysis_booking/dialysis_booking_model','dialysis_model');
	$result = $CI->dialysis_model->get_dialysis_package_name($ipd_bkng_id);
	return $result;
}
function get_checkbox_coloumns($module_id)
{
	$CI =& get_instance();
	$CI->load->model('opd/opd_model','opd_model');
	$result = $CI->opd_model->get_checkbox_coloumns($module_id);
	return $result;
}

function get_checkbox_coloumns_excel($module_id)
{
	$CI =& get_instance();
	$CI->load->model('opd/opd_model','opd_model');
	$result = $CI->opd_model->get_checkbox_coloumns_excel($module_id);
	return $result;
}



function test_multi_interpration($test_id="",$condition="")
{
	$CI =& get_instance();
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->get_test_multi_interpration($test_id,$condition);
	return $result;
}

function test_master_list($branch_id="")
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
    $CI->db->select('id,test_code,test_name');  
    $CI->db->where('is_deleted','0');
    if(isset($branch_id) && !empty($branch_id))
    {
        $CI->db->where('branch_id',$branch_id);
    }
    else
    {
        $CI->db->where('branch_id',$users_data['parent_id']);    
    }
    
    $query = $CI->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}

function get_ipd_particular_code($particular_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_ipd_particular_code($particular_id);
	return $result;
}


function test_booking_balance($booking_id="")
{
	if(!empty($booking_id))
	{   
		$CI =& get_instance();
		$CI->load->model('test/test_model','test');
	    $balance = $CI->test->test_booking_balance($booking_id); 
	    return $balance;
	}
}


function generate_medicine_barcode($text='',$orientation='',$code_type='',$size='')
{
		error_reporting(0);
		$code_string = "";
		// Translate the $text into barcode the correct $code_type
		if ( in_array(strtolower($code_type), array("code128", "code128b")) ) 
		{
			$chksum = 104;
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","\`"=>"111422","a"=>"121124","b"=>"121421","c"=>"141122","d"=>"141221","e"=>"112214","f"=>"112412","g"=>"122114","h"=>"122411","i"=>"142112","j"=>"142211","k"=>"241211","l"=>"221114","m"=>"413111","n"=>"241112","o"=>"134111","p"=>"111242","q"=>"121142","r"=>"121241","s"=>"114212","t"=>"124112","u"=>"124211","v"=>"411212","w"=>"421112","x"=>"421211","y"=>"212141","z"=>"214121","{"=>"412121","|"=>"111143","}"=>"111341","~"=>"131141","DEL"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","FNC 4"=>"114131","CODE A"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211214" . $code_string . "2331112";
			} 
			elseif ( strtolower($code_type) == "code128a" ) 
			{
			$chksum = 103;
			$text = strtoupper($text); // Code 128A doesn't support lower case
			// Must not change order of array elements as the checksum depends on the array's key to validate final code
			$code_array = array(" "=>"212222","!"=>"222122","\""=>"222221","#"=>"121223","$"=>"121322","%"=>"131222","&"=>"122213","'"=>"122312","("=>"132212",")"=>"221213","*"=>"221312","+"=>"231212",","=>"112232","-"=>"122132","."=>"122231","/"=>"113222","0"=>"123122","1"=>"123221","2"=>"223211","3"=>"221132","4"=>"221231","5"=>"213212","6"=>"223112","7"=>"312131","8"=>"311222","9"=>"321122",":"=>"321221",";"=>"312212","<"=>"322112","="=>"322211",">"=>"212123","?"=>"212321","@"=>"232121","A"=>"111323","B"=>"131123","C"=>"131321","D"=>"112313","E"=>"132113","F"=>"132311","G"=>"211313","H"=>"231113","I"=>"231311","J"=>"112133","K"=>"112331","L"=>"132131","M"=>"113123","N"=>"113321","O"=>"133121","P"=>"313121","Q"=>"211331","R"=>"231131","S"=>"213113","T"=>"213311","U"=>"213131","V"=>"311123","W"=>"311321","X"=>"331121","Y"=>"312113","Z"=>"312311","["=>"332111","\\"=>"314111","]"=>"221411","^"=>"431111","_"=>"111224","NUL"=>"111422","SOH"=>"121124","STX"=>"121421","ETX"=>"141122","EOT"=>"141221","ENQ"=>"112214","ACK"=>"112412","BEL"=>"122114","BS"=>"122411","HT"=>"142112","LF"=>"142211","VT"=>"241211","FF"=>"221114","CR"=>"413111","SO"=>"241112","SI"=>"134111","DLE"=>"111242","DC1"=>"121142","DC2"=>"121241","DC3"=>"114212","DC4"=>"124112","NAK"=>"124211","SYN"=>"411212","ETB"=>"421112","CAN"=>"421211","EM"=>"212141","SUB"=>"214121","ESC"=>"412121","FS"=>"111143","GS"=>"111341","RS"=>"131141","US"=>"114113","FNC 3"=>"114311","FNC 2"=>"411113","SHIFT"=>"411311","CODE C"=>"113141","CODE B"=>"114131","FNC 4"=>"311141","FNC 1"=>"411131","Start A"=>"211412","Start B"=>"211214","Start C"=>"211232","Stop"=>"2331112");
			$code_keys = array_keys($code_array);
			$code_values = array_flip($code_keys);
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				$activeKey = substr( $text, ($X-1), 1);
				$code_string .= $code_array[$activeKey];
				$chksum=($chksum + ($code_values[$activeKey] * $X));
			}
			$code_string .= $code_array[$code_keys[($chksum - (intval($chksum / 103) * 103))]];
			$code_string = "211412" . $code_string . "2331112";
		}
		elseif ( strtolower($code_type) == "code39" ) 
		{
			$code_array = array("0"=>"111221211","1"=>"211211112","2"=>"112211112","3"=>"212211111","4"=>"111221112","5"=>"211221111","6"=>"112221111","7"=>"111211212","8"=>"211211211","9"=>"112211211","A"=>"211112112","B"=>"112112112","C"=>"212112111","D"=>"111122112","E"=>"211122111","F"=>"112122111","G"=>"111112212","H"=>"211112211","I"=>"112112211","J"=>"111122211","K"=>"211111122","L"=>"112111122","M"=>"212111121","N"=>"111121122","O"=>"211121121","P"=>"112121121","Q"=>"111111222","R"=>"211111221","S"=>"112111221","T"=>"111121221","U"=>"221111112","V"=>"122111112","W"=>"222111111","X"=>"121121112","Y"=>"221121111","Z"=>"122121111","-"=>"121111212","."=>"221111211"," "=>"122111211","$"=>"121212111","/"=>"121211121","+"=>"121112121","%"=>"111212121","*"=>"121121211");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				$code_string .= $code_array[substr( $upper_text, ($X-1), 1)] . "1";
			}
			$code_string = "1211212111" . $code_string . "121121211";
		} elseif ( strtolower($code_type) == "code25" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0");
			$code_array2 = array("3-1-1-1-3","1-3-1-1-3","3-3-1-1-1","1-1-3-1-3","3-1-3-1-1","1-3-3-1-1","1-1-1-3-3","3-1-1-3-1","1-3-1-3-1","1-1-3-3-1");
			for ( $X = 1; $X <= strlen($text); $X++ ) {
				for ( $Y = 0; $Y < count($code_array1); $Y++ ) {
					if ( substr($text, ($X-1), 1) == $code_array1[$Y] )
						$temp[$X] = $code_array2[$Y];
				}
			}
			for ( $X=1; $X<=strlen($text); $X+=2 ) {
				if ( isset($temp[$X]) && isset($temp[($X + 1)]) ) {
					$temp1 = explode( "-", $temp[$X] );
					$temp2 = explode( "-", $temp[($X + 1)] );
					for ( $Y = 0; $Y < count($temp1); $Y++ )
						$code_string .= $temp1[$Y] . $temp2[$Y];
				}
			}
			$code_string = "1111" . $code_string . "311";
		} elseif ( strtolower($code_type) == "codabar" ) {
			$code_array1 = array("1","2","3","4","5","6","7","8","9","0","-","$",":","/",".","+","A","B","C","D");
			$code_array2 = array("1111221","1112112","2211111","1121121","2111121","1211112","1211211","1221111","2112111","1111122","1112211","1122111","2111212","2121112","2121211","1121212","1122121","1212112","1112122","1112221");
			// Convert to uppercase
			$upper_text = strtoupper($text);
			for ( $X = 1; $X<=strlen($upper_text); $X++ ) {
				for ( $Y = 0; $Y<count($code_array1); $Y++ ) {
					if ( substr($upper_text, ($X-1), 1) == $code_array1[$Y] )
						$code_string .= $code_array2[$Y] . "1";
				}
			}
			$code_string = "11221211" . $code_string . "1122121";
		}
		// Pad the edges of the barcode
		$code_length = 20;
		for ( $i=1; $i <= strlen($code_string); $i++ )
			$code_length = $code_length + (integer)(substr($code_string,($i-1),1));
		if ( strtolower($orientation) == "horizontal" ) {
			$img_width = $code_length;
			$img_height = $size;
		} else {
			$img_width = $size;
			$img_height = $code_length;
		}
		$image = imagecreate($img_width, $img_height);
		$black = imagecolorallocate ($image, 0, 0, 0);
		$white = imagecolorallocate ($image, 255, 255, 255);
		imagefill( $image, 0, 0, $white );
		$location = 10;
		for ( $position = 1 ; $position <= strlen($code_string); $position++ ) {
			$cur_size = $location + ( substr($code_string, ($position-1), 1) );
			if ( strtolower($orientation) == "horizontal" )
				imagefilledrectangle( $image, $location, 0, $cur_size, $img_height, ($position % 2 == 0 ? $white : $black) );
			else
				imagefilledrectangle( $image, 0, $location, $img_width, $cur_size, ($position % 2 == 0 ? $white : $black) );
			$location = $cur_size;
		}
		// Draw barcode to the screen
		//DIR_UPLOAD_MEDICINE_BARCODE_PATH
		$path = DIR_UPLOAD_MEDICINE_BARCODE_PATH; 
		chmod($path, 0777);
		header('Content-type: image/png');
		imagepng($image, $path . "/$text.png");
		if(!empty($image))
		{
			imagedestroy($image);	
			
		}
		return $text;
	
}

function get_total_user($section_id="",$action_id="")
{
	$CI =& get_instance();
	$auth_users = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$total_branch = $CI->general->total_users($auth_users['parent_id']); 
	$result = $CI->general->get_permission_attr($section_id,$action_id);  
	$remaining_branch = $result['attribute_val'] - $total_branch;
	return $remaining_branch;
}

function branch_alloted_module_permissionwise($branch_id="",$users_id="")
{		

		$CI =& get_instance();
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $balance = $CI->general->branch_alloted_module_permissionwise($branch_id,$users_id); 
	    return $balance;
	
}

function branch_alloted_module_permissionwise_total_log($branch_id="",$users_id="")
{		

		$CI =& get_instance();
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $balance = $CI->general->branch_alloted_module_permissionwise_total_log($branch_id,$users_id); 
	    return $balance;
	
}



function get_total_entry_for_branch($branch_id="",$users_id="",$table='',$type="")
{		

		$CI =& get_instance();
		//$table='';
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $total_count = $CI->general->get_total_entry_for_branch($branch_id,$users_id,$table,$type=""); 
	    return $total_count;
	
}

function get_total_entry_for_branch_opd($branch_id="",$users_id="",$type='',$table='')
{		

		$CI =& get_instance();
		//$table='';
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $total_count = $CI->general->get_total_entry_for_branch_opd($branch_id,$users_id,$type,$table); 
	    return $total_count;
	
}


function get_total_entry_for_branch_users($branch_id="",$users_id="",$table='')
{		

		$CI =& get_instance();
		//$table='';
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $total_count = $CI->general->get_total_entry_for_branch_users($branch_id,$users_id,$table); 
	    return $total_count;
	
}

function get_total_entry_for_branch_users_last($branch_id="",$users_id="",$table='')
{		

		$CI =& get_instance();
		//$table='';
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $total_count = $CI->general->get_total_entry_for_branch_users_last($branch_id,$users_id,$table); 
	    return $total_count;
	
}


function get_total_entry_for_branch_last_login($branch_id="",$users_id="",$table='')
{		

		$CI =& get_instance();
		//$table='';
		$auth_users = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
	    $total_count = $CI->general->get_total_entry_for_branch_last_login($branch_id,$users_id,$table); 
	    return $total_count;
	
}

function get_patient_present_age($dob='',$patient_data='')
 {
    
 	if(!empty($dob))
 	{
 		$today = date("Y-m-d");
		$diff = date_diff(date_create($dob), date_create($today));
		$age_list = [];
		$age_list['age_y']  = $diff->format('%y');
		$age_list['age_m']  = $diff->format('%m');
		$age_list['age_d']  = $diff->format('%d');
		$age_list['age_h']  = $diff->format('%h');
		
		return $age_list;	
 	}
 	else
 	{
 		$age_y = $patient_data['age_y'];
 		$age_m = $patient_data['age_m'];
 		$age_d = $patient_data['age_d'];
 		if(!empty($patient_data['age_h']))
 		{
 			$today = date("Y-m-d H:i:s");
 			$age_h = $patient_data['age_h'];
 			$modified_date = date('Y-m-d H:i:s',strtotime($patient_data['patient_modified_date']));
 		}
 		else
 		{
 			$age_h = '';
 			$today = date("Y-m-d");
 			$modified_date = date('Y-m-d',strtotime($patient_data['patient_modified_date']));
 		}
 		
 		
 		
 		$diff = date_diff(date_create($modified_date), date_create($today));
 		//echo "<pre>";print_r($diff);
 		$age_y_diff = $diff->format('%y');
        $age_m_diff = $diff->format('%m');
        $age_d_diff = $diff->format('%d');
        
        $age_years_cur = $age_y+$age_y_diff;
        
        $age_monthg = $age_m+$age_m_diff;
        
       
        
        //days
        $agedaysss = $age_d+$age_d_diff;
        $z=0;
        while ($agedaysss >= 30) { // shows 0, then 1, then 2
          
          $agedaysss=$agedaysss-30;
          $z++;
        }
        
        //months
        $agemonthss = $age_monthg+$z;
        $y=0;
        while ($agemonthss >= 12) { // shows 0, then 1, then 2
          
          $agemonthss=$agemonthss-12;
          $y++;
        }
        
        
        $age_list = [];
        $age_list['age_y'] = $age_years_cur+$y;
        $age_list['age_m'] = $agemonthss;
        $age_list['age_d'] = $agedaysss;
        if(!empty($patient_data['age_h']))
 		{
        	$age_h_diff = $diff->format('%h');
        	$age_list['age_h'] = $age_h+$age_h_diff;
    	}
        //echo "<pre>";print_r($age_list);	
        return $age_list;
 	}
 	
	
 
 /*
 	if(!empty($dob))
 	{
 		$today = date("Y-m-d");
		$diff = date_diff(date_create($dob), date_create($today));
		$age_list = [];
		$age_list['age_y']  = $diff->format('%y');
		$age_list['age_m']  = $diff->format('%m');
		$age_list['age_d']  = $diff->format('%d');
		$age_list['age_h']  = $diff->format('%h');
		
		return $age_list;	
 	}
 	else
 	{
 		$age_y = $patient_data['age_y'];
 		$age_m = $patient_data['age_m'];
 		$age_d = $patient_data['age_d'];
 		if(!empty($patient_data['age_h']))
 		{
 			$today = date("Y-m-d H:i:s");
 			$age_h = $patient_data['age_h'];
 			$modified_date = date('Y-m-d H:i:s',strtotime($patient_data['patient_modified_date']));
 		}
 		else
 		{
 			$age_h = '';
 			$today = date("Y-m-d");
 			$modified_date = date('Y-m-d',strtotime($patient_data['patient_modified_date']));
 		}
 		
 		
 		
 		$diff = date_diff(date_create($modified_date), date_create($today));
 		//echo "<pre>";print_r($diff);
 		$age_y_diff = $diff->format('%y');
        $age_m_diff = $diff->format('%m');
        $age_d_diff = $diff->format('%d');
        $age_list = [];
        
        $age_list['age_y'] = $age_y+$age_y_diff;
        $age_list['age_m'] = $age_m+$age_m_diff;
        $age_list['age_d'] = $age_d+$age_d_diff;
        if(!empty($patient_data['age_h']))
 		{
        	$age_h_diff = $diff->format('%h');
        	$age_list['age_h'] = $age_h+$age_h_diff;
    	}
        //echo "<pre>";print_r($age_list);	
        return $age_list;
 	}
 	
	
 */}
 
 function compareByName($booked_test_list, $b) {
            return strnatcmp($booked_test_list["sort_order"], $b["sort_order"]);
          }

//Pathology functions//

/* ipd discharge time settings */
function get_ipd_discharge_time_setting_value()
{
	
    $setting_data='0';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_ipd_discharge_time_setting_value('',$user_data['parent_id']); 
	//$setting_data = $result->setting_value;
	if(isset($result)){
	$setting_data = $result->discharge_format;
	return $setting_data;
	}else{
	return false;
	}
}

function get_sample_type_for_test($test_id='')
 {
 	if(!empty($test_id))
 	{
		$CI =& get_instance();
		$users_data = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
		$result = $CI->general->get_sample_type_for_test($test_id);
		return $result;
 	}	

 }
function get_doctor_qualifications($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_doctor_qualifications($doctor_id);
	return $result;
}
function generate_new_barcode($text,$height='22.85') 
{ 
// Part 1, make list of widths
$char128asc=' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~'; 
$char128wid = array(
 '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213', // 0-9 
 '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132', // 10-19 
 '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211', // 20-29 
 '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313', // 30-39 
 '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331', // 40-49 
 '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111', // 50-59 
 '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214', // 60-69 
 '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111', // 70-79 
 '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141', // 80-89 
 '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141', // 90-99
 '114131','311141','411131','211412','211214','211232','23311120' ); // 100-106

 $w = $char128wid[$sum = 104]; // START symbol
 $onChar=1;
 for($x=0;$x<strlen($text);$x++) // GO THRU TEXT GET LETTERS
 if (!( ($pos = strpos($char128asc,$text[$x])) === false )){ // SKIP NOT FOUND CHARS
 $w.= $char128wid[$pos];
 $sum += $onChar++ * $pos;
 } 
 $w.= $char128wid[ $sum % 103 ].$char128wid[106]; //Check Code, then END
 //Part 2, Write rows
 $html="<table cellpadding=0 cellspacing=0 width='100px'><tr>"; 
 for($x=0;$x<strlen($w);$x+=2) // code 128 widths: black border, then white space

 //echo $w[$x+1]; {$w[$x+1]}
 $html .= "<td><div style=\"border-left: 1px black solid;border-left-width:{$w[$x]};width:{$w[$x+1]};height:{$height}mm;\"></div></td>"; 
 return "$html<tr><td colspan=".strlen($w)." align=center><font family=arial size=2>$text</td></tr></table>"; 
}



function get_sales_amount($from_date)
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_sales_amount($from_date);
	return $result;
}

function get_sales_return_amount($from_date)
{
   $CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_sales_return_amount($from_date);
	return $result;
}

function get_purchase_amount($from_date)
{
   $CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_purchase_amount($from_date);
	return $result;
}

function get_purchase_return_amount($from_date)
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_purchase_return_amount($from_date);
	return $result;
}
function get_vendor_payment_amount($from_date)
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_vendor_payment_amount($from_date);
	return $result;
}

///prescription ipd ///

function get_ipd_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_prescription_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_prescription_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_prescription_medicine_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_prescription_medicine_print_tab_setting($branch_id,$order_by); 
	return $result;
}

/// for pathology department 26/07/2019
function get_pathology_department($dept_id='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_pathology_department($dept_id, $branch_id); 
	return $result;
}

// 30/07/2019 
 function check_hospital_mlc_no()
 {
 		$CI =& get_instance();  //echo $code;die;
		$user_data = $CI->session->userdata('auth_users');
		$CI->load->model('general/general_model','general');
		$table_name='hospital_mlc_setting';
		$result = $CI->general->check_hospital_mlc_no($table_name);
	     $data=array();
		if(!empty($result))
		{   
			$table_name="hms_branch_hospital_mlc_no";
			$pr_fix=$result[0]->prefix;
            $tot_unique_id = $CI->general->get_tot_hospital_mlc_no($table_name,$pr_fix);

            if(empty($tot_unique_id))
            {
             $prefix= $result[0]->prefix;
             $start_part=$result[0]->suffix;
             $code = $prefix.($start_part);	
             $data= array('prefix'=>$prefix,'suffix'=>$result[0]->suffix);


            }
            else
            {
               
               	 $last_unique_id = $CI->general->get_last_hospital_mlc_no($table_name,$pr_fix);
               	 
               	 $section_id=$last_unique_id['section_id'];               	 
                  $start_part=$last_unique_id['reciept_suffix']+1;
            	  $code = $last_unique_id['reciept_prefix'].($start_part);	
            	  $data= array('prefix'=>$last_unique_id['reciept_prefix'],'suffix'=>$start_part);
             }
           
		     return $data;
		}
		else
		{
			return $data;
		}


 }
 //partograph
 function get_partograph_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_partograph_tab_setting($branch_id,$order_by); 
	return $result;
}
/* ipd discharge time settings */

function get_ot_charge($ot_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_ot_charge($ot_id);
	return $result;
}

function get_medicine_price($medicine_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_medicine_price($medicine_id);
	return $result;
}

    function get_all_item($id,$branch_id)
    {
        $CI =& get_instance();
	    $CI->load->model('stock_issue_allotment/stock_issue_allotment_model','stock_issue_allotment');
      if(!empty($id) && $id>0)
       {
           $result = $CI->stock_issue_allotment->get_all_item($id,$branch_id);
           return $result;
       }  
    }
    /////////////////
    function get_holiday_month_count_data()
	{
		$CI =& get_instance();
		$CI->load->model('general/general_model');
		$result = $CI->general_model->get_holiday_month_count_data();
		return $result;
	}
	
	function get_total_employee_count()
	{
		$CI =& get_instance();
		$CI->load->model('general/general_model');
		$result = $CI->general_model->get_total_employee_count();
		return $result;
	}
	
	function get_join_current_month_count()
	{
		$CI =& get_instance();
		$CI->load->model('general/general_model');
		$result = $CI->general_model->get_join_current_month_count();
		return $result;
	}
	function get_employee_data_exit()
	{
		$CI =& get_instance();
		$CI->load->model('general/general_model');
		$result = $CI->general_model->get_employee_data_exit();
		return $result;
	}
	
	function get_emp_present_absent_count($type)
	{
		$CI =& get_instance();
		$CI->load->model('general/general_model');
		$result = $CI->general_model->get_emp_present_absent_count($type);
		return $result;
	}
	
//send video link
function send_video_link_sms($mobile_no='',$message)
{
	$CI =& get_instance();
	if(!empty($mobile_no))
	{
		
				//$sms_url = sms_url();
				//$sms_url="http://203.212.70.200/smpp/sendsms?username=rydcom&password=sms12345&to={mobile_no}&from=VESIPL&text={message}";
				$sms_url="http://203.212.70.200/smpp/sendsms?username=rydcom&password=sms12345&to={mobile_no}&from=VESIPL&text={message}"; 
				if(!empty($sms_url))
				{
					$url = $sms_url;
					$url = str_replace("{mobile_no}",$mobile_no,$url);
					$url = str_replace("{message}",rawurlencode($message),$url);
					//echo $url; 
					if(!empty($mobile_no) && isset($mobile_no) && !empty($url))
					{
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_HEADER, 0);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
						curl_setopt($ch, CURLOPT_VERBOSE, true);
						$output = curl_exec($ch);      
						curl_close($ch); 
						// Display MSGID of the successful sms push
						//echo $output; exit;
					}
				
			
		}
	
		
	}
		
}

function get_department_wise_charge($branch_id,$department_id,$booking_id){
	$CI =& get_instance();
	$CI->load->model('department_reports/reports_model','department_charge');
	$result = $CI->department_charge->get_department_wise_charge($branch_id,$department_id,$booking_id);
	//print_r($result); 
	return $result;

}

function generate_ipd_label($text,$patient_name='',$admission_date='',$gender_age='',$patient_code='',$mobile_no='',$height='9.85') 
{ 

// Part 1, make list of widths
	
$char128asc=' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~'; 
$char128wid = array(
 '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213', // 0-9 
 '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132', // 10-19 
 '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211', // 20-29 
 '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313', // 30-39 
 '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331', // 40-49 
 '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111', // 50-59 
 '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214', // 60-69 
 '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111', // 70-79 
 '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141', // 80-89 
 '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141', // 90-99
 '114131','311141','411131','211412','211214','211232','23311120' ); // 100-106

 $w = $char128wid[$sum = 104]; // START symbol
 $onChar=1;
 for($x=0;$x<strlen($text);$x++) // GO THRU TEXT GET LETTERS
 if (!( ($pos = strpos($char128asc,$text[$x])) === false )){ // SKIP NOT FOUND CHARS
 $w.= $char128wid[$pos];
 $sum += $onChar++ * $pos;
 } 
 $w.= $char128wid[ $sum % 103 ].$char128wid[106]; //Check Code, then END
 //Part 2, Write rows
 $html="<table cellpadding=0 cellspacing=0 width='140px'>";

$html .="<tr><td colspan=".strlen($w)."  style='text-align:left;font-size:11px;'><span style='text-align:left;font-size:10px;'> UHID No.: ".$patient_code."</span></td></tr>";

$html .="<tr><td colspan=".strlen($w)."  style='text-align:left;font-size:11px;'><span style='text-align:left;font-size:10px;'> Mob. No.: ".$mobile_no."</span></td></tr>";
 

$html .="<tr><td colspan=".strlen($w)."  style='text-align:left;font-size:11px;'><span style='text-align:left;font-size:10px;'> IPD No.: ".$text."</span></td></tr>";
 
$html .="<tr><td colspan=".strlen($w)."  style='text-align:left;font-size:11px;'><span style='text-align:left;font-size:10px;'> Patient Name: ".$patient_name."</span></td></tr>";
 
$html .="<tr><td colspan=".strlen($w)."  style='text-align:left;font-size:11px;'><span style='text-align:left;font-size:10px;'> Gender/Age: ".$gender_age."</span></td></tr>";
 
$html .="<tr><td colspan=".strlen($w)."  style='text-align:left;font-size:11px;'><span style='text-align:left;font-size:10px;'>Admission Date:".$admission_date."</span></td></tr>";
 



$html .="<tr>"; 
 for($x=0;$x<strlen($w);$x+=2)
 {
 $html .= "<td><div style=\"border-left: 1px black solid;border-left-width:{$w[$x]};width:{$w[$x+1]};height:{$height}mm;\"></div></td>"; 
 
 }
 $html .="</tr><tr><td colspan=".strlen($w)."  style='text-align:center;font-size:11px;'> <br> </td></tr></table>";
 
 //$html .="<table><tr><td style='text-align:center;font-size:11px;'></td></tr></table>";
 
 return $html;
}


function generate_ipd_barcode_label($text,$height='9.85') 
{ 

// Part 1, make list of widths
	
$char128asc=' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~'; 
$char128wid = array(
 '212222','222122','222221','121223','121322','131222','122213','122312','132212','221213', // 0-9 
 '221312','231212','112232','122132','122231','113222','123122','123221','223211','221132', // 10-19 
 '221231','213212','223112','312131','311222','321122','321221','312212','322112','322211', // 20-29 
 '212123','212321','232121','111323','131123','131321','112313','132113','132311','211313', // 30-39 
 '231113','231311','112133','112331','132131','113123','113321','133121','313121','211331', // 40-49 
 '231131','213113','213311','213131','311123','311321','331121','312113','312311','332111', // 50-59 
 '314111','221411','431111','111224','111422','121124','121421','141122','141221','112214', // 60-69 
 '112412','122114','122411','142112','142211','241211','221114','413111','241112','134111', // 70-79 
 '111242','121142','121241','114212','124112','124211','411212','421112','421211','212141', // 80-89 
 '214121','412121','111143','111341','131141','114113','114311','411113','411311','113141', // 90-99
 '114131','311141','411131','211412','211214','211232','23311120' ); // 100-106

 $w = $char128wid[$sum = 104]; // START symbol
 $onChar=1;
 for($x=0;$x<strlen($text);$x++) // GO THRU TEXT GET LETTERS
 if (!( ($pos = strpos($char128asc,$text[$x])) === false )){ // SKIP NOT FOUND CHARS
 $w.= $char128wid[$pos];
 $sum += $onChar++ * $pos;
 } 
 $w.= $char128wid[ $sum % 103 ].$char128wid[106]; //Check Code, then END
 //Part 2, Write rows
 $html="<table cellpadding=0 cellspacing=0 width='140px'>";

$html .="<tr>"; 
 for($x=0;$x<strlen($w);$x+=2)
 {
 $html .= "<td><div style=\"border-left: 1px black solid;border-left-width:{$w[$x]};width:{$w[$x+1]};height:{$height}mm;\"></div></td>"; 
 
 }
 $html .="</tr><tr><td colspan=".strlen($w)."  style='text-align:center;font-size:11px;'> <br> </td></tr></table>";
 
 //$html .="<table><tr><td style='text-align:center;font-size:11px;'></td></tr></table>";
 
 return $html;
}

function get_department_name($department_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_department_name($department_id);
	return $result;
}

function get_opd_particular_name($particular_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_opd_particular_name($particular_id);
	return $result;
}

function get_doc_hos_comission($doctor_id='0',$hospital_id='0',$price='0',$dept_id='0',$balance_clear=0,$discount=0)
{
    $CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$users_data = $CI->session->userdata('auth_users');
	$discount_result = $CI->general->get_discount_setting($users_data['parent_id']);
	$result = $CI->general->get_doc_hos_comission($doctor_id,$hospital_id,$price,$dept_id);
	$data_arr = [];
	if(!empty($result))
	{ 
	        if(!empty($doctor_id))
	        {
	            $doctor_comission = $result['rate'];
	            $hospital_comission = '0';
	        }
	        if(!empty($hospital_id))
	        {
	            $doctor_comission = 0;
	            $hospital_comission = $result['rate'];
	        }
	        
	        if($result['rate_type']==1)
	        {
	            $total_comission = ($price/100)*$result['rate'];
	        }
	        else
	        {
	            $total_comission = $result['rate'];
	            if($balance_clear==1)
	            {
	                $total_comission = 0;
	            }
	        }
	        
	        //doctor commission minus discount
	        if($discount_result==1)
	        {
	            if($total_comission>=$discount)
	            {
	               $total_comission = $total_comission-$discount;
	            }
	            else if($total_comission<$discount)
	            {
	                $total_comission =0;
	            }
	            
	        } // end of commission
	        
	        $data_arr=array('doctor_comission'=>$doctor_comission,'hospital_comission'=>$hospital_comission,'comission_type'=>$result['rate_type'],'total_comission'=>$total_comission);
	} 
	//print_r($data_arr);die;
	return $data_arr;
}

function get_transaction_no($booking_id='',$payment_mode_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_transaction_no($booking_id,$payment_mode_id);
	return $result;
}

function get_attended_doctor_signature($doctor_id="")
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_attended_doctor_signature($doctor_id);
	return $result;
}

function get_medicine_freqdata($mid='', $branch_id='', $patient_id='',$book_id='',$pres_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_medicine_freqdata($mid, $branch_id, $patient_id,$book_id,$pres_id);
	return $result;
}

function get_medicine_set_tapperdata($mid='', $branch_id='', $set_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_medicine_set_tapperdata($mid, $branch_id, $set_id);
	return $result;
}

function get_collecton_department()
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$section = $users_data['permission']['section'];
	$option=array();
	if(in_array('85', $section)) //1
	{
		$option['2']='OPD';
	}
	if(in_array('151', $section)) //2
	{
		$option['4']='OPD Billing';
	}
	if(in_array('85', $section))//387 //3
	{
		$option['14']='Day Care';
	}
	if(in_array('121', $section)) //4
	{
		$option['5']='IPD';
	}
	if(in_array('134', $section)) //5
	{
		$option['8']='OT';
	}
	if(in_array('145', $section))  //6
	{
		$option['1']='Pathology';
	}
	if(in_array('59', $section) || in_array('60', $section))  //7
	{
		$option['3']='Medicine';
	}
	if(in_array('180', $section) || in_array('179', $section)) //8
	{
		$option['7']='Vaccination';
	}	
	if(in_array('166', $section))  //9
	{
		$option['6']='Stock';
	}
	if(in_array('262', $section)) //10
	{
		$option['10']='Blood Bank';
	}
	if(in_array('349', $section))// 11
	{
		$option['13']='Ambulance';
	}
		if(in_array('207', $section))// 11
	{
		$option['16']='Dialysis';
	}
	return $option;
}

function get_expense_department()
{
	$CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$section = $users_data['permission']['section'];
	//print_r($section);die();
	$option=array();
	if(in_array('85', $section))
	{
		$option['7']='OPD';
	}
	if(in_array('151', $section))
	{
		$option['14']='OPD Billing';
	}
	if(in_array('85', $section))//387
	{
		$option['15']='Day Care';
	}
	if(in_array('121', $section))
	{
		$option['10']='IPD';
	}
	if(in_array('134', $section))
	{
		$option['11']='OT';
	}
	if(in_array('145', $section))
	{
		$option['8']='Pathology';
	}
	if(in_array('58', $section))
	{
		$option['2']='Purchase';
	}
	if(in_array('61', $section))
	{
		$option['9']='Medicine Sale Return';
	}
	if(in_array('181', $section))
	{
		$option['5']='Vaccine Purchase';
	}
	if(in_array('178', $section))
	{
		$option['6']='Vaccine Sale Return';
	}
	if(in_array('168', $section))
	{
		$option['4']='Stock Purchase';
	}

	if(in_array('262', $section))
	{
		$option['12']='Blood Bank';
	}
	if(in_array('349', $section))
	{
		$option['13']='Ambulance';
	}
	if(in_array('35', $section))
	{
		$option['0']='Expenses';
	}
	if(in_array('43', $section))
	{
		$option['1']='Employee Salary';
	}
	if(in_array('35', $section))
	{
		$option['16']='Doctor Commission';
	}
	return $option;
}

function AmountInWordsdisplay(float $amount)
{
   $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
   // Check if there is any number after decimal
   $amt_hundred = null;
   $count_length = strlen($num);
   $x = 0;
   $string = array();
   $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
     3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
     7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
     10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
     13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
     16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
     19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
     40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
     70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $x < $count_length ) {
      $get_divider = ($x == 2) ? 10 : 100;
      $amount = floor($num % $get_divider);
      $num = floor($num / $get_divider);
      $x += $get_divider == 10 ? 1 : 2;
      if ($amount) {
       $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
       $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
       $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
       '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
       '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
        }
   else $string[] = null;
   }
   $implode_to_Rupees = implode('', array_reverse($string));
   $get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
   " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
   return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
}

function get_serial_no_item($id,$item_id)
{
    $CI =& get_instance();
    $CI->load->model('stock_issue_allotment/stock_issue_allotment_model','stock_issue_allotment');
    if(!empty($id) && $id>0)
    {
        $result = $CI->stock_issue_allotment->purchase_item_serial_by_id($id,$item_id);
        return $result;
    }  
}

function get_purchase_serial_no_item($id,$item_id)
{
    $CI =& get_instance();
    $CI->load->model('stock_purchase/stock_purchase_model','stock_purchase');
    if(!empty($id) && $id>0)
    {
        $result = $CI->stock_purchase->purchase_item_serial_by_id($id,$item_id);
        return $result;
    }  
}

function get_purchase_return_serial_no_item($id,$item_id)
{
    $CI =& get_instance();
    $CI->load->model('stock_purchase_return/stock_purchase_return_model','stock_purchase_return');
    if(!empty($id) && $id>0)
    {
        $result = $CI->stock_purchase_return->purchase_item_serial_by_id($id,$item_id);
        return $result;
    }  
}

function get_issue_return_serial_no_item($id,$item_id)
{
    $CI =& get_instance();
    $CI->load->model('stock_issue_allotment_return/stock_issue_allotment_return_model','stock_issue_return');
    if(!empty($id) && $id>0)
    {
        $result = $CI->stock_issue_return->purchase_item_serial_by_id($id,$item_id);
        return $result;
    }  
}

function get_dialysis_schedule_time($day_id='',$schedule_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->get_dialysis_schedule_time($day_id,$schedule_id);
	return $result;
}

function dialysis_schedule_list($branch_id='')
{
	$CI =& get_instance();
	$CI->load->model('general/general_model','general');
	$result = $CI->general->dialysis_schedule_list($branch_id);
	return $result;
}

function get_dialysis_room_charge_according_to_branch($branch_id){
	$CI =& get_instance();
	$CI->load->model('dialysis_room_charge_type/dialysis_room_charge_type_model','dialysis_room_charge_type');
	$result = $CI->dialysis_room_charge_type->get_dialysis_room_charge_according_to_branch($branch_id);
	return $result;
}

function get_dialysis_charges_according($room_id="",$room_charge_type="",$panel_company_id='',$types='')
{
    $CI =& get_instance();
	$CI->load->model('dialysis_room_list/dialysis_room_list_model','dialysis_room_list');
	$result = $CI->dialysis_room_list->get_charges_according($room_id,$room_charge_type,$panel_company_id,$types);
	return $result;
}

function get_dialysis_room_charge_accordint_to_id($branch_id,$charge_id,$room_type,$room_id){
	$CI =& get_instance();
	$CI->load->model('dialysis_room_charge_type/dialysis_room_charge_type_model','dialysis_room_charge_type');
	$result = $CI->dialysis_room_charge_type->get_dialysis_room_charge_accordint_to_id($branch_id,$charge_id,$room_type,$room_id);
	return $result;

}

function get_dialysis_package_price($package_id)
{
	$CI =& get_instance();
	$CI->load->model('dialysis_booking/dialysis_booking_model','dialysisbooking');
	$result = $CI->dialysisbooking->get_package_charge($package_id);
	return $result;
}

function get_dialysis_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_dialysis_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_dialysis_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_dialysis_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_dialysis_prescription_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_dialysis_prescription_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_dialysis_prescription_medicine_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_dialysis_prescription_medicine_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_prescription_test($prescription_id="")
{
	$CI =& get_instance();
	$CI->load->model('prescription/prescription_model');
	$result = $CI->prescription_model->get_prescription_test($prescription_id);
	return $result;
}

function get_prescription_medicine($prescription_id="")
{
	$CI =& get_instance();
	$CI->load->model('prescription/prescription_model');
	$result = $CI->prescription_model->get_prescription_medicine($prescription_id);
	return $result;
}
function get_doctors($branch_id){
	$CI =& get_instance();
	$CI->load->model('doctors/doctors_model','doctor');
	$result = $CI->doctor->get_doctors($branch_id);
	return $result;
}
function get_doctors_details($appointment_date,$appointment_time,$doctor_id='')
{
	$CI =& get_instance();
	$CI->load->model('dialysis_appointment/dialysis_appointment_model','appointment');
	$result = $CI->appointment->get_doctors_details($appointment_date,$appointment_time,$doctor_id);
	return $result;
}
function hoursRange( $lower = 0, $upper = 86400, $step = 3600, $format = '' ) {
    $times = array();

    if ( empty( $format ) ) {
        $format = 'g:i a';
    }

    foreach ( range( $lower, $upper, $step ) as $increment ) {
        $increment = gmdate( 'H:i', $increment );

        list( $hour, $minutes ) = explode( ':', $increment );

        $date = new DateTime( $hour . ':' . $minutes );

        $times[(string) $increment] = $date->format( $format );
    }

    return $times;
}

function get_schedule($branch_id){
	$CI =& get_instance();
	$CI->load->model('dialysis_appointment/dialysis_appointment_model','dialysis');
	$result = $CI->dialysis->get_schedule($branch_id);
	return $result;
}

function get_schedule_details($appointment_date,$appointment_time,$slot_id='')
{
	$CI =& get_instance();
	$CI->load->model('dialysis_appointment/dialysis_appointment_model','appointment');
	$result = $CI->appointment->get_schedule_details($appointment_date,$appointment_time,$slot_id);
	return $result;
}
////consolidate presc
function get_detail_by_prescription_gynic_consolidate($prescription_id)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->get_detail_by_prescription_id($prescription_id);
	return $result;

}

function gynic_template_format($data,$branch_id)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->template_format($data,$branch_id);
	return $result;

}

function get_gynic_opd_by_id($opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('opd/opd_model','opd');
	$result = $CI->opd->get_by_id($opd_booking_id);
	return $result;

}
function gynic_vitals_list()
{
	$CI =& get_instance();
   	$CI->load->model('general/general_model');
	$result = $CI->general_model->vitals_list();
	return $result;

}

function gpla_list($prescription_id)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->gpla_list($prescription_id);
	return $result;

}
function get_gynecology_right_ovary_list($prescription_id,$opd_booking_id,$type)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->get_gynecology_right_ovary_list($prescription_id,$opd_booking_id,$type);
	return $result;

}
function get_gynecology_left_ovary_list($prescription_id,$opd_booking_id,$type)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->get_gynecology_left_ovary_list($prescription_id,$opd_booking_id,$type);
	return $result;

}
function get_patient_icsilab_list($prescription_id,$opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->get_patient_icsilab_list($prescription_id,$opd_booking_id);
	return $result;

}

function get_patient_antenatal_care_list($prescription_id,$opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->get_patient_antenatal_care_list($prescription_id,$opd_booking_id);
	return $result;

}
function get_fertility_list2($prescription_id,$opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('gynecology/gynecology_prescription/gynecology_prescription_model','gyc');
	$result = $CI->gyc->get_fertility_list2($prescription_id,$opd_booking_id);
	return $result;

}
///pedi
function pediatrician_template_format($data,$branch_id)
{
	$CI =& get_instance();
   	$CI->load->model('pediatrician/add_prescription/Pediatrician_file_model','pediatrician_files_model');
	$result = $CI->pediatrician_files_model->template_format($data,$branch_id);
	return $result;

}

function get_pediatrician_detail_by_prescription_id($prescription_id)
{
	$CI =& get_instance();
   	$CI->load->model('pediatrician/add_prescription/Pediatrician_file_model','pediatrician_files_model');
	$result = $CI->pediatrician_files_model->get_detail_by_prescription_id($prescription_id);
	return $result;

}
function get_dental_detail_by_prescription_id($prescription_id)
{
	$CI =& get_instance();
   	$CI->load->model('dental/dental_prescription/Dental_prescription_model','dental_prescription');
	$result = $CI->dental_prescription->get_detail_by_prescription_id($prescription_id);
	return $result;

}
function dental_template_format($data,$branch_id)
{
	$CI =& get_instance();
   	$CI->load->model('dental/dental_prescription/Dental_prescription_model','dental_prescription');
	$result = $CI->dental_prescription->template_format($data,$branch_id);
	return $result;

}
function get_dental_opd_by_id($opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('opd/opd_model','opd');
	$result = $CI->opd->get_by_id($opd_booking_id);
	return $result;

}
function get_eye_prescription_by_ids($opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye_prescription/prescription_model','prescription');
	$result = $CI->prescription->get_by_ids($opd_booking_id);
	return $result;

}

function get_prescription_by_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_prescription_by_id($booking_id,$pres_id);
	return $result;

}

function get_prescription_new_by_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_prescription_new_by_id($booking_id,$pres_id);
	return $result;

}
function get_drawing($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_drawing($booking_id,$pres_id);
	return $result;

}
function get_prescription_refraction_new_by_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_prescription_refraction_new_by_id($booking_id,$pres_id);
	return $result;

}

function get_prescription_examination_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_prescription_examination_id($booking_id,$pres_id);
	return $result;

}
function get_advice_by_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_advice_by_id($booking_id,$pres_id);
	return $result;

}

function get_investigation_by_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_investigation_by_id($booking_id,$pres_id);
	return $result;

}
function get_diagnosis_data_by_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_diagnosis_data_by_id($booking_id,$pres_id);
	return $result;

}
function get_prescription_biometry_id($booking_id,$pres_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->get_prescription_biometry_id($booking_id,$pres_id);
	return $result;

}
function prescription_html_template($presc,$branch_id)
{
	$CI =& get_instance();
   	$CI->load->model('eye/add_prescription/add_new_prescription_model','add_prescript');
	$result = $CI->add_prescript->prescription_html_template('',$branch_id);
	return $result;

}

function get_pedic_opd_by_id($opd_booking_id)
{
	$CI =& get_instance();
   	$CI->load->model('pediatrician/pediatrician_prescription/Pediatrician_prescription_model','prescription_model');
	$result = $CI->prescription_model->get_by_opd_id($opd_booking_id);
	return $result;

}

function get_items_allot($parent_id,$created_date)
{
	$CI =& get_instance();
   	$CI->load->model('advance_stock_allotment_report/advance_stock_allotment_model','stock_allotment');
	$result = $CI->stock_allotment->get_items_allot($parent_id,$created_date);
	return $result;

}

function get_serial_no_by_stock($id,$item_id,$branchids)
{
    $CI =& get_instance();
    $CI->load->model('advance_stock_allotment_report/advance_stock_allotment_model','stockpurchase');
    
    if(!empty($id) && $id>0)
    {
        $result = $CI->stockpurchase->get_serial_no_by_stock($id,$item_id,$branchids);
        return $result;
    }  
}

function rate_plan_test_rate($branch_id="",$rate_plan_id="",$test_id="")
{
  if(!empty($test_id))
  {
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->rate_plan_test_rate($branch_id,$rate_plan_id,$test_id); 
	return $result;
  }
}

function doctor_plan_test_rate($branch_id="",$doctor_id="",$test_id="")
{
  if(!empty($test_id))
  {
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->doctor_plan_test_rate($branch_id,$doctor_id,$test_id); 
	return $result;
  }
}
function doctor_rate_plan_test_rate($branch_id="",$doctor_id="",$test_id="")
{
  if(!empty($test_id))
  {
  	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('test_master/test_master_model','test_master');
	$result = $CI->test_master->doctor_rate_plan_test_rate($branch_id,$doctor_id,$test_id); 
	return $result;
  }
}

function display_test_advance_range($booking_id="",$test_id="",$result="")
 { 
     $CI =& get_instance();
	$users_data = $CI->session->userdata('auth_users');
	$CI->load->model('test/test_model','test');
    if(!empty($booking_id) && !empty($test_id))
    {  
      $booking_data = $CI->test->get_by_id($booking_id);
      $gender = $booking_data['gender'];
      $patient_age_days = ($booking_data['age_y']*365)+($booking_data['age_m']*30)+$booking_data['age_d']; 
      $advance_range = $CI->test->get_all_test_advance_range($test_id,$gender);
      //echo "<pre>"; print_r($advance_range); exit;
      if(!empty($advance_range))
      {
        foreach($advance_range as $range_value)
        {
            
            if($range_value->age_type==0) //days
            {
                if($patient_age_days >=$range_value->start_age && $patient_age_days <=$range_value->end_age)
                 {
                    return $range_value;
                 }
         
                
            }
            else if($range_value->age_type==1) //month
            {
                $start_age_day = $range_value->start_age*30;
                $end_age_day = $range_value->end_age*30;
                if($patient_age_days >= $start_age_day && $patient_age_days <= $end_age_day)
                {
                    return $range_value;
                } 
                
            }
            else if($range_value->age_type==2) //years
            {
                $start_age_by_day = $range_value->start_age*365;
                $end_age_by_day = $range_value->end_age*365;
                if($patient_age_days >= $start_age_by_day && $patient_age_days <= $end_age_by_day)
                {
                    return $range_value;
                } 
                
            }
            else
            {
                //echo "gg"; die;
               check_main_test_advance_result_range($booking_id,$test_id,$result); 
            }
            //echo "<pre>"; print_r($range_value); exit; 
            
        }
        
      
      }
     
    }
    
  }
  
  function check_main_test_advance_result_range($booking_id,$test_id,$result)
  {
     $test_data = get_test($test_id);
     //print_r($test_data); exit;
     if(!empty($test_data->range_from) && is_numeric($test_data->range_from) && !empty($test_data->range_to) && is_numeric($test_data->range_to))
     {
       if($result>=$test_data->range_from && $result<=$test_data->range_to)
       {
         return $result; 
       }
       else
       {
         return;
       }
     }
     
     
  }
  
  function get_daycare_medicine_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_daycare_medicine_print_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_treatment_type_name($treat_ids=array())
{
    $CI =& get_instance();
    $CI->load->model('general/general_model','general');
    $result = $CI->general->get_treatment_type_name($treat_ids);
    return $result;
}

function get_ipd_admission_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_admission_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_admission_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_admission_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_nursing_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_nursing_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_nursing_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_nursing_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_ipd_admission_prescription_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_admission_prescription_print_tab_setting($branch_id,$order_by); 
	return $result;
}
function get_ipd_admission_prescription_medicine_print_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('general/general_model','general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general->get_ipd_admission_prescription_medicine_print_tab_setting($branch_id,$order_by); 
	return $result;
}

    function CallAPI($method,$url, $data = false)
    {
        $curl = curl_init();
        $option = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
        );
        if($data){
            $option[CURLOPT_POSTFIELDS] = $data;
        }
        curl_setopt_array($curl, $option);
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }





?>