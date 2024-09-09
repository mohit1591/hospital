<?php
function investigation_cat_name($id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('dental/general/dental_general_model','dental_general');
	$result = $CI->dental_general->get_cat_name($id); 
	return $result;
}
function get_dental_prescription_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('dental/general/dental_general_model','dental_general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->dental_general->get_dental_prescription_tab_setting($branch_id,$order_by); 
	return $result;
}
function get_dental_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('dental/general/dental_general_model','general_dental');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->general_dental->get_dental_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}
function get_dental_investigation_sub_category($category_id="")
{ 
	//echo "$category_id";die;
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('dental/general/dental_general_model','general_dental');
	$result = $CI->general_dental->get_investigation_sub_category_name($category_id); 
	//print_r($result);die;
	return $result;
}
?>