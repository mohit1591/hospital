<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();	
      auth_users();  
    }

    
	public function index()
	{  //dashboard_count
		$this->load->model('branch/branch_model');
		$this->load->model('dashboard/dashboard_model');
		$users_data = $this->session->userdata('auth_users'); 
		$company_data = $this->branch_model->get_by_id($users_data['parent_id']); 
		$this->session->set_userdata('company_data',$company_data);
		$main_count=$this->dashboard_model->dashboard_count();
		$data['counts']=$main_count;
		$array_data=array('start_date'=>date('Y-m-d'), 'end_date'=>date('Y-m-d'), 'order_by'=>'DSC');
		$data['patient_data']=$this->print_opd_collection_reports($array_data);

		$module_count=$this->dashboard_model->dashboard_module_count();		
		$data['module']=$module_count;

		$main_graph=$this->dashboard_model->dashboard_graph_count();
		$data['graph']=$main_graph;
		$data['start_end_dates']=$this->branch_model->get_data_new_feature();
		$this->load->model('general/general_model'); 
		$data['expiry_notice']=$this->general_model->expiry_notice();
		
		$data['expiry_docs']=$this->dashboard_model->ambulance_doc_expiry($vehical_expiry);
        $data['expiry_driving_license']=$this->general_model->driving_license_expiry($driving_license_expiry);
        
        
        if(in_array('165',$users_data['permission']['section']))
        {
            $inventory_purchase_due = $this->dashboard_model->get_inventory_purchase_due();
            $data['inventory_purchase_due'] = $inventory_purchase_due;
        }
        else
        {
            $data['inventory_purchase_due'] = '';
        }
		
		if($users_data['users_role']==4)
		{
			$patient_id = $users_data['parent_id'];
			$patient_data= $this->branch_model->get_patient_branch($patient_id);  
			$permission_section = branch_permission_section_list($patient_data[0]->branch_id); 
			$permission_section = array_column($permission_section, 'section_id'); 
			$data['permission_data_latest']=$permission_section;
			$data['page_title'] = "Dashboard";
			$this->load->view('dashboard',$data);
		}
		elseif($users_data['users_role']==3)
		{
			
			$doctor_id = $users_data['parent_id'];
			$doctor_data= $this->branch_model->get_doctor_branch($doctor_id);  
			$permission_section = branch_permission_section_list($doctor_data[0]->branch_id); 
			$permission_section = array_column($permission_section, 'section_id');
			$data['permission_data_latest']=$permission_section; 
			$data['page_title'] = "Dashboard";
			$this->load->view('dashboard',$data);
			
		}
		elseif(in_array('377',$users_data['permission']['section']) && !in_array('19',$users_data['permission']['section']))
		{
		    	$this->load->model('branch/branch_model');
        		$users_data = $this->session->userdata('auth_users'); 
        		$company_data = $this->branch_model->get_payroll_by_id($users_data['parent_id']); 
        		$data['employee_data'] = $this->branch_model->get_employee_data_birthday();
        		$data['holiday_full_data'] = get_holiday_month_count_data();
        
        		//echo $this->db->last_query();
        		$holiday_count=0;
        		foreach($data['holiday_full_data'] as $vals)
        		{
        			 $start= $vals->from_date;
        			$end= $vals->end_date;
        
        			$date1=date('Y-m-d',strtotime($start));
        			$date2=date('Y-m-d',strtotime($end));
        	        $diff = abs(strtotime($date2) - strtotime($date1));
                    $years = floor($diff / (365*60*60*24)); $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        
                   $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        
                   $holiday_count=$holiday_count+$days;
        		}
        		
        		   $this->session->set_userdata('company_data',$company_data);
        
        			$data['page_title'] = "Dashboard";
        			$data['birthday_list']='';
        			$data['anniversary_list']='';
        			if (array_key_exists("permission",$users_data))
        			{
        			     $permission_section = $users_data['permission']['section'];
        			     $permission_action = $users_data['permission']['action'];
        			}
        			else{
        			     $permission_section = array();
        			     $permission_action = array();
        			}
        			
        			$users_data = $this->session->userdata('auth_users'); 
		$company_data = $this->branch_model->get_by_id($users_data['parent_id']); 
		//print_r($company_data); exit;
		$this->session->set_userdata('company_data',$company_data);
        			
        			$this->load->view('payroll_dashboard',$data);
        		
        	
		}
		else
		{
			$this->load->model('general/general_model'); 
			$data['page_title'] = "Dashboard";
			//$data['today_booking_patient']=$this->general_model->today_booking_list();
			//print  '<pre>';print_r($data['today_booking_patient']);
			//$data['pending_report']=$this->general_model->remaining_balance();
			$intimation_setting = get_setting_value('BIRTHDAY_ANNIVERSARY_EMAIL');
			$data['birthday_list']='';
			$data['anniversary_list']='';
            $data['notice_list']='';
			if (array_key_exists("permission",$users_data))
			{
			     $permission_section = $users_data['permission']['section'];
			     $permission_action = $users_data['permission']['action'];
			}
			else{
			     $permission_section = array();
			     $permission_action = array();
			}
			if($intimation_setting==1 && (in_array('102',$permission_section) || in_array('46',$permission_section) ))
			{
				$data['birthday_list']=$this->general_model->birthday_list();
				$data['anniversary_list']=$this->general_model->anniversary_list();	
			}
			
			if(in_array('219',$permission_section))
			{
				$data['notice_list']=$this->general_model->notice_list();	
			}
            
                 //print_r($data['notice_list']);die;
			
			
			$this->load->view('dashboard',$data);
		}
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
			$data['type'] = 3;
			$data['name'] = $employee_data[0]->name;
			$data['email'] = $employee_data[0]->email;
			$data['mobile'] = $employee_data[0]->contact_no;
		} 
		//anniversary
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='4')
		{
			$doctor_data = $this->general_model->doctors_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 4;
			$data['name'] = $doctor_data[0]->doctor_name;
			$data['email'] = $doctor_data[0]->email;
			$data['mobile'] = $doctor_data[0]->mobile_no;
			
		}
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='5')
		{
			$patient_data = $this->general_model->patient_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 5;
			$data['name'] = $patient_data[0]->patient_name;
			$data['email'] = $patient_data[0]->patient_email;
			$data['mobile'] = $patient_data[0]->mobile_no;
		}
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='6')
		{
			$employee_data = $this->general_model->employee_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 6;
			$data['name'] = $employee_data[0]->name;
			$data['email'] = $employee_data[0]->email;
			$data['mobile'] = $employee_data[0]->contact_no;
		}
		       
		if(isset($post['person_id']) && !empty($post['person_id']))
		{ 
			//echo "<pre>";print_r($post); exit;
			$mobile_no = $post['mobile'];
			$message = $post['message'];
			send_birthday_sms('','',$mobile_no,'',$message,$post['type'],$post['person_id']);
			echo '1'; return false;
		}
		$this->load->view('send_sms',$data);
	}


	public function send_email()
	{
		$data['page_title'] = "Send Email";
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
			$data['type'] = 3;
			$data['name'] = $employee_data[0]->name;
			$data['email'] = $employee_data[0]->email;
			$data['mobile'] = $employee_data[0]->contact_no;
		} 
		//anniversary
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='4')
		{
			$doctor_data = $this->general_model->doctors_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 4;
			$data['name'] = $doctor_data[0]->doctor_name;
			$data['email'] = $doctor_data[0]->email;
			$data['mobile'] = $doctor_data[0]->mobile_no;
			
		}
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='5')
		{
			$patient_data = $this->general_model->patient_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 5;
			$data['name'] = $patient_data[0]->patient_name;
			$data['email'] = $patient_data[0]->patient_email;
			$data['mobile'] = $patient_data[0]->mobile_no;
		}
		if(isset($post['id']) && !empty($post['id']) && $post['type']=='6')
		{
			$employee_data = $this->general_model->employee_list($post['id']);
			$data['person_id'] = $post['id'];
			$data['type'] = 6;
			$data['name'] = $employee_data[0]->name;
			$data['email'] = $employee_data[0]->email;
			$data['mobile'] = $employee_data[0]->contact_no;
		}
		       
		if(isset($post['person_id']) && !empty($post['person_id']))
		{ 
			//echo "<pre>";print_r($post); exit;
			$email = $post['email'];
			$message = $post['message'];
			$subject = $post['subject'];
			$this->load->library('general_functions');
			
			$this->general_functions->send_birh_anni_email($email,$subject,$message,'','',$post['type'],$post['person_id']);
			echo '1'; return false;
		}
		$this->load->view('send_email',$data);
	}
	
	
	public function ajax_change()
	{
		$this->load->model('dashboard/dashboard_model');
		$post=$this->input->post();
		$main_count=$this->dashboard_model->dashboard_count();		
		$data['counts']=$main_count;
		$module_count=$this->dashboard_model->dashboard_module_count();	
		$data['module']=$module_count;
		$module_special_count=$this->dashboard_model->opd_specilization_count();
		$data['module_specialization']=$module_special_count;
		echo json_encode($data);
		exit();
	}

	public function graph_ajax()
	{
		$this->load->model('dashboard/dashboard_model');
		$main_graph=$this->dashboard_model->dashboard_graph_count();
		echo json_encode($main_graph);
		exit();
	}

	public function print_opd_collection_reports($get='')
    { 
      //unauthorise_permission('42','245');
      $data= $this->dashboard_model->collection_report($get);
      return $data;
    }

	public function close_popup()
	{
	 $this->session->set_userdata('close_popup',1);
	  
	}



}
