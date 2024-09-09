<?php
function get_qc_status($id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_qc_status($id); 
	return $result;
}

function bag_qc_cat_name($id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_cat_name($id); 
	return $result;
}
function check_beg_qc($donor_id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	
	$CI->load->model('blood_bank/recipient/Recipient_model','recipient');

	$check_beg_qcy= $CI->recipient->check_beg_qc($donor_id);
	return $check_beg_qcy;
}
function get_stock_quantity_twelve_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="",$days='')
{

    $CI =& get_instance();
    $user_data = $CI->session->userdata('auth_users');
    $CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $result = $CI->blood_general_model->get_stock_quantity_twelve_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id,$days); 
    //print_r($result);
    //die;
    return $result;
}

function get_stock_quantity_seven_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="",$days='')
{

    $CI =& get_instance();
    $user_data = $CI->session->userdata('auth_users');
    $CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $result = $CI->blood_general_model->get_stock_quantity_seven_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id,$days); 
    //print_r($result);
    //die;
    return $result;
}

function get_stock_quantity_discarded_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
	{
    	//$setting_data = array();
    	//$setting_data='';
    	$CI =& get_instance();
    	$user_data = $CI->session->userdata('auth_users');
    	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    	$result = $CI->blood_general_model->get_stock_quantity_discarded_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
    	//print_r($result);
    	//die;
    	return $result;
	}
	
	function get_stock_quantity_expired_by_group($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
	{
    	//$setting_data = array();
    	//$setting_data='';
    	$CI =& get_instance();
    	$user_data = $CI->session->userdata('auth_users');
    	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    	$result = $CI->blood_general_model->get_stock_quantity_expired_by_group($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
    	//print_r($result);
    	//die;
    	return $result;
	}
	
	

function get_stock_quantity_qc_failed_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
{
	//$setting_data = array();
	//$setting_data='';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_stock_quantity_qc_failed_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
	//print_r($result);
	//die;
	return $result;
}

function get_stock_quantity_awaiting_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
{
//$setting_data = array();
//$setting_data='';
$CI =& get_instance();
$user_data = $CI->session->userdata('auth_users');
$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
$result = $CI->blood_general_model->get_stock_quantity_awaiting_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
//print_r($result);
//die;
return $result;
}

function get_stock_dashboard_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
{
//$setting_data = array();
//$setting_data='';
$CI =& get_instance();
$user_data = $CI->session->userdata('auth_users');
$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
$result = $CI->blood_general_model->get_stock_dashboard_quantity($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
//print_r($result);
//die;
return $result;
}

function get_stock_dashboard_grand_total_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
{
	
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_stock_dashboard_grand_total_quantity($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
	//print_r($result);
	//die;
	return $result;
}



function hms_patient_age_calulator($age_y="",$age_m="",$age_d="",$age_h="")
{
	$age="";
	if($age_y>0)
	{
		$year = 'Years';
		if($age_y==1)
		{
			$year = 'Year';
		}
		$age .= $age_y." ".$year;
	}
	if($age_m>0)
	{
		$month = 'Months';
		if($age_m==1)
		{
			$month = 'Month';
		}
		$age .= ", ".$age_m." ".$month;
	}
	if($age_d>0)
	{
		$day = 'Days';
		if($age_d==1)
		{
			$day = 'Day';
		}
		$age .= ", ".$age_d." ".$day;
	}
	if($age_h>0)
	{
		$hours = 'Hours';
		$age .= " ".$age_h." ".$hours;
	} 
	return $age;
}


function get_general_settings($title)
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_common_settings($title); 
	return $result;
}

function bag_qc_subcategory_name($id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_subcat_qc_fields($id); 
	return $result;
}

function bag_qc_subcategory_name_field($id='')
{
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->bag_qc_subcategory_name_field($id); 
	return $result;
}


  function get_setting_value_for_donor($var_title="")
{
    //$setting_data = array();
    //$setting_data='';
    $CI =& get_instance();
    $user_data = $CI->session->userdata('auth_users');
    $CI->load->model('general/general_model','general');
    $result = $CI->general->get_setting_value_for_donor($var_title,$user_data['parent_id']); 
    //$setting_data = $result->setting_value;
       if(isset($result)){
        $setting_data = $result->setting_value;
        return $setting_data;
       }else{
        return false;
       }
    }

function get_stock_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
{
    //$setting_data = array();
    //$setting_data='';
    $CI =& get_instance();
    $user_data = $CI->session->userdata('auth_users');
    $CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $result = $CI->blood_general_model->get_stock_quantity($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
    //print_r($result);
    //die;
    return $result;
    }

	function get_bar_code_data($blood_detail_id="")
	{
		
    //$setting_data = array();
    //$setting_data='';
    $CI =& get_instance();
    $user_data = $CI->session->userdata('auth_users');
    $CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
    $result = $CI->blood_general_model->get_bar_code_data($blood_detail_id); 
    //print_r($result);
    //die;
    return $result;
    }
    function get_issued_component_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="")
	{
	//$setting_data = array();
	//$setting_data='';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_issued_component_quantity($bag_type_id,$component_id,$exist_ids,$donor_id); 
	//print_r($result);
	//die;
	return $result;
	}
	function get_stock_quantity_tested_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
	{
	//$setting_data = array();
	//$setting_data='';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_stock_quantity_tested_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
	//print_r($result);
	//die;
	return $result;
	}
	function get_stock_quantity_untested_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
	{
	//$setting_data = array();
	//$setting_data='';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_stock_quantity_untested_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
	//print_r($result);
	//die;
	return $result;
	}
	function get_stock_quantity_expired_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$blood_group_id="")
	{
	//$setting_data = array();
	//$setting_data='';
	$CI =& get_instance();
	$user_data = $CI->session->userdata('auth_users');
	$CI->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');
	$result = $CI->blood_general_model->get_stock_quantity_expired_data($bag_type_id,$component_id,$exist_ids,$donor_id,$blood_group_id); 
	//print_r($result);
	//die;
	return $result;
	}
	
	function AmountInWords($amount)
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
   return ($implode_to_Rupees ? $implode_to_Rupees . 'Only ' : '') . $get_paise;
}


?>