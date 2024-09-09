<?php 
function get_gynecology_patient_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('gynecology/general/gynecology_general_model','gynecology_general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}

	$result = $CI->gynecology_general->get_gynecology_patient_tab_setting($branch_id,$order_by); 
	return $result;
}

function get_gynecology_prescription_medicine_tab_setting($order_by='',$branch_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('gynecology/general/gynecology_general_model','gynecology_general');
	if(!empty($branch_id))
	{
		$branch_id = $branch_id;
	}
	else
	{
		$branch_id = $user_data['parent_id'];
	}
	$result = $CI->gynecology_general->get_gynecology_prescription_medicine_tab_setting($branch_id,$order_by); 
	return $result;
}



?>