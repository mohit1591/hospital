<?php
class Pathology_report_print_setting_model extends CI_Model 
{
	var $table = 'path_print_report_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_master_unique($temp_id='') //doctor_signature_position
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('path_print_report_setting.*, path_test_report_print_setting.setting_name, path_test_report_print_setting.setting_value, path_test_report_print_setting.page_header, path_test_report_print_setting.page_details, path_test_report_print_setting.page_middle, path_test_report_print_setting.page_footer,
            path_test_report_print_setting.header_print,
            path_test_report_print_setting.header_pdf,
            path_test_report_print_setting.details_print,
            path_test_report_print_setting.details_pdf,
            path_test_report_print_setting.middle_print,
            path_test_report_print_setting.middle_pdf,
            path_test_report_print_setting.footer_print,
            path_test_report_print_setting.footer_pdf,path_test_report_print_setting.doctor_signature_position,
            path_test_report_print_setting.doctor_signature_text
			,path_test_report_print_setting.method,path_test_report_print_setting.sample_type,path_test_report_print_setting.pixel_value,path_test_report_print_setting.header_pixel_value');
		$this->db->join('path_test_report_print_setting', 'path_test_report_print_setting.unique_id=path_print_report_setting.id AND path_test_report_print_setting.branch_id = "'.$user_data['parent_id'].'"','left');
		$this->db->from('path_print_report_setting');  
		if(!empty($temp_id))
		{
		    $this->db->where('path_test_report_print_setting.temp_id',$temp_id);
		}
		else
		{
		    $this->db->where('path_test_report_print_setting.temp_id',0);
		}
		
		$query = $this->db->get(); 
		//echo $this->db->last_query(); exit;
		return $query->result();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
   
		if(!empty($post['data']))
		{    
			$current_date = date('Y-m-d H:i:s');

			$this->db->where('branch_id',$user_data['parent_id']);
			if(!empty($post['temp_id']))
			{
			    $this->db->where('temp_id',$post['temp_id']);
			}
			else
			{
			    $this->db->where('temp_id',0);
			}
			$this->db->delete('path_test_report_print_setting');
			//$message = $post['message']; 
            $header_print = 0;
            $header_pdf= 0;
            $details_print = 0;
            $details_pdf= 0;
            $middle_print = 0;
            $middle_pdf= 0;
            $footer_print = 0;
            $footer_pdf= 0;
            $doctor_signature_position= 0;
            $doctor_signature_text= 0;
            $method= 0;
            $sample_type= 0;
            $pixel_value= 0;
            $header_pixel_value=0;

			if(isset($post['header_print']))
			{
				$header_print = $post['header_print']; 
			}
			if(isset($post['header_pdf']))
			{
				$header_pdf = $post['header_pdf']; 
			}
			if(isset($post['details_print']))
			{
				$details_print = $post['details_print']; 
			}
			if(isset($post['details_pdf']))
			{
				$details_pdf = $post['details_pdf']; 
			}
			if(isset($post['middle_print']))
			{
				$middle_print = $post['middle_print']; 
			}
			if(isset($post['middle_pdf']))
			{
				$middle_pdf = $post['middle_pdf']; 
			}
			if(isset($post['footer_print']))
			{
				$footer_print = $post['footer_print']; 
			}
			if(isset($post['footer_pdf']))
			{
				$footer_pdf = $post['footer_pdf']; 
			}
			if(isset($post['doctor_signature_position']))
			{
				$doctor_signature_position = $post['doctor_signature_position']; 
			}
			if(isset($post['doctor_signature_text']))
			{
				$doctor_signature_text = $post['doctor_signature_text']; 
			}
			if(isset($post['method']))
			{
				$method = $post['method']; 
			}
			if(isset($post['sample_type']))
			{
				$sample_type = $post['sample_type']; 
			}
			if(isset($post['pixel_value']))
			{
				$pixel_value = $post['pixel_value']; 
			}
			
			
			if(isset($post['header_pixel_value']))
			{
				$header_pixel_value = $post['header_pixel_value']; 
			}
			if(isset($post['temp_id']))
			{
				$temp_id = $post['temp_id']; 
			}
			else
			{
			    $temp_id=0;
			}

            foreach($post['data'] as $key=>$val)
            {
            	$data = array(
                               "branch_id"=>$user_data['parent_id'],
                               "unique_id"=>$key,
                               'temp_id'=>$temp_id,
                               "setting_name"=>$val['setting_name'],
                               "page_header"=>$post['messageh'],
                               "page_details"=>$post['messaged'],
                               "page_middle"=>$post['messagem'],
                               "page_footer"=>$post['messagef'],
                               "header_print"=>$header_print,
                               "doctor_signature_position"=>$doctor_signature_position,
                               "doctor_signature_text"=>$doctor_signature_text,
                               "method"=>$method,
                               "header_pdf"=>$header_pdf,
                               "sample_type"=>$sample_type,
                               "pixel_value"=>$pixel_value,
                               "header_pixel_value"=>$header_pixel_value,
                               "details_print"=>$details_print,
                               "details_pdf"=>$details_pdf,
                               "middle_print"=>$middle_print,
                               "middle_pdf"=>$middle_pdf,
                               "footer_print"=>$footer_print,
                               "footer_pdf"=>$footer_pdf,
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$user_data['id'], 
                               "created_date"=>$current_date
            		         );
            	$this->db->insert('path_test_report_print_setting',$data);
      
            } //print_r($val['setting_value']); die;
            //echo $this->db->last_query(); die;
		} 
	}
 
    
} 
?>