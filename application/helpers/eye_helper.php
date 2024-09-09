<?php
function get_eye_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('eye/general/eye_general_model','general_eye');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->general_eye->get_eye_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}
function get_eye_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('eye/general/eye_general_model','general_eye');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general_eye->get_eye_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}
function get_eye_under_maintenance()
{
	$CI =& get_instance();
	$CI->load->model('eye/general/eye_general_model','general_eye');
	$result = $CI->general_eye->get_eye_under_maintenance();
	if(!empty($result) && $result[0]->status==1)
         { 
           redirect(base_url('under_maintenance_page'));
         }
}

// function to get ot procedures list data
function get_ot_procedure_list()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('ot_procedure/ot_procedure_model','ot_procedure');
	$branch_id = $user_data['parent_id'];
	$result = $CI->ot_procedure->ot_procedure_list(); 
	return $result;
}
// function to get ot procedures list data

// function to get post observations list data
function get_post_operative_observations()
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('post_operative_observations/post_operative_observations_model','observation_model');
	$branch_id = $user_data['parent_id'];
	$result = $CI->observation_model->post_observations_list(); 
	return $result;
}
// function to get post observations list data


	

?>