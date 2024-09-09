<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Birthday_anniversary extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	
      auth_users();  
    }

    
	public function index()
	{
		$this->load->model('branch/branch_model');
		$this->load->model('general/general_model');
		$this->load->model('birthday_anniversary/birthday_anniversary_model'); 
		$users_data = $this->session->userdata('auth_users'); 
		$data['setting_data'] = $this->birthday_anniversary_model->get_setting('BIRTHDAY_ANNIVERSARY_EMAIL',$users_data['parent_id']);
		$company_data = $this->branch_model->get_by_id($users_data['parent_id']); 
		$this->session->set_userdata('company_data',$company_data);
		$data['page_title']='Birthday Anniversary';
		$data['sms_email_setting'] = $this->birthday_anniversary_model->get_birthday_anniversary();

		$post = $this->input->post();
		//print_r($post); exit;
		if(isset($post['submit']) && $post['submit']=='submit')
		{
			$this->birthday_anniversary_model->save_birthday_anniversary();
			echo 1;
            return false;
		}
		$data['birthday_list']=$this->general_model->birthday_list();
		$data['anniversary_list']=$this->general_model->anniversary_list();
		//print_r($data['birthday_list']);
		$this->load->view('birthday_anniversary/birth_anni',$data);
	}
	public function save_setting()
	{
		$post = $this->input->post();
		$this->load->model('birthday_anniversary/birthday_anniversary_model'); 
		$this->birthday_anniversary_model->save_birthday_setting();
		echo 1;
        return false;
	}
	public function send_sms()
	{
		$data['page_title'] = "Send SMS";
		$this->load->model('general/general_model');
		$post = $this->input->post();

		if(isset($post['id']) && !empty($post['id']) && $post['type']=='1')
		{
			$doctor_data = $this->general_model->doctors_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 1;
			$data['name'] = $doctor_data[0]->doctor_name;
			$data['email'] = $doctor_data[0]->email;
			$data['mobile'] = $doctor_data[0]->mobile_no;
			
		}
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='2')
		{
			$patient_data = $this->general_model->patient_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 2;
			$data['name'] = $patient_data[0]->patient_name;
			$data['email'] = $patient_data[0]->patient_email;
			$data['mobile'] = $patient_data[0]->mobile_no;
		}
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='3')
		{
			$employee_data = $this->general_model->employee_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 2;
			$data['name'] = $employee_data[0]->name;
			$data['email'] = $employee_data[0]->email;
			$data['mobile'] = $employee_data[0]->contact_no;
		} 
		       
		if(isset($post['person_id']) && !empty($post['person_id']))
		{ 
			//echo "<pre>";print_r($post); exit;
			$mobile_no = $post['mobile'];
			$message = $post['message'];
			send_birthday_sms('','',$mobile_no,'',$message);
			$this->general_model->update_message($post['type'],$post['person_id']);

			echo '1'; return false;
		}
		$this->load->view('send_sms',$data);
	}
}
