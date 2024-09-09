<?php
function get_recommended_age_according_to_vaccine($vaccine_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
		
	}

	$result = $CI->general_pediatrician->get_recommended_age_according_to_vaccine($vaccine_id,$branch_id); 
	return $result;
}

function get_catchup_immuniation_age_according_to_vaccine($vaccine_id='')
{
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->general_pediatrician->get_catchup_immuniation_age_according_to_vaccine($vaccine_id,$branch_id); 
	return $result;
}

function get_catchup_risk_age_according_to_vaccine($vaccine_id='')
{
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->general_pediatrician->get_catchup_risk_age_according_to_vaccine($vaccine_id,$branch_id); 
	return $result;
}

function get_vaccine_already_exits($age_id="",$vaccine_id="",$booking_id="")
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->general_pediatrician->get_vaccine_already_exits($age_id,$vaccine_id,$booking_id,$branch_id); 
	return $result;
}

function get_age_according_to_limit($start_age_type="",$end_age_type="",$start_age="",$end_age="",$title="",$vaccination_date="")
{

	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->general_pediatrician->get_age_according_to_limit($start_age_type,$end_age_type,$start_age,$end_age,$title,$vaccination_date); 
	return $result;
}

function get_pediatrician_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->general_pediatrician->get_pediatrician_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}
function get_pediatrician_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('pediatrician/general/pediatrician_general_model','general_pediatrician');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general_pediatrician->get_pediatrician_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}
	

?>