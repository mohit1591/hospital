<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_report_verify_model extends CI_Model {
 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function save()
	{
		$post = $this->input->post();
		$users_data = $this->session->userdata('auth_users');

		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete('path_test_report_verify');

		if(!empty($post['dept_id']))
		{
			foreach($post['dept_id'] as $dept)
			{
				$this->db->set('branch_id',$users_data['parent_id']);
				$this->db->set('dept_id',$dept);
				$this->db->set('created_by',$users_data['id']);
				$this->db->insert('path_test_report_verify');
			}
		}

		//// Save Report Setting //////
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete('path_test_report_setting');

		$this->db->set('branch_id',$users_data['parent_id']);
		$this->db->set('report_print',$post['report_print']);
		$this->db->set('report_break',$post['report_break']);
		$this->db->set('report_lock',$post['report_lock']);
		$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
		$this->db->set('modified_by',$users_data['id']);
		$this->db->insert('path_test_report_setting');
		////////////////////////////////
	}

	public function branch_verify_dept()
	{
		$post = $this->input->post();
		$users_data = $this->session->userdata('auth_users');

		$this->db->select('dept_id');
		$this->db->where('branch_id',$users_data['parent_id']);
		$query = $this->db->get('path_test_report_verify');
        $result =  $query->result_array();
        $data = [];
        if(!empty($result))
        {
        	foreach($result as $dept)
        	{
        		$data[] = $dept['dept_id'];
        	}
        }
        return $data;
	}

	public function branch_report_setting()
	{
		$post = $this->input->post();
		$users_data = $this->session->userdata('auth_users');

		$this->db->select('*');
		$this->db->where('branch_id',$users_data['parent_id']);
		$query = $this->db->get('path_test_report_setting');
        $result =  $query->row(); 
        return $result;
	}
 
     

}
?>