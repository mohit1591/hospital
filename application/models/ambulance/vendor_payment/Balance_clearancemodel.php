<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Balance_clearance_model extends CI_Model 
{
var //$table = 'path_test_booking';
	var $column = array('hms_payment.credit','hms_payment.debit', 'hms_doctors.doctor_name');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
   

	public function patient_to_balclearlist($id='')
	{
		$post = $this->input->post();
		$users_data = $this->session->userdata('auth_users');
		$result= array();
		$this->db->select('hms_patient.*,(sum(hms_payment.credit)-sum(hms_payment.debit)) as balance');
		$this->db->from('hms_patient');
		$this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id');
		if(isset($post) || !empty($post))
		{
			if(!empty($post['patient_name']))
			{
				$this->db->like('hms_patient.patient_name',$post['patient_name']);
			}
			if(!empty($post['mobile_no']))
			{
				$this->db->like('hms_patient.mobile_no',$post['mobile_no']);
			}
			if(!empty($post['sub_branch_id']))
			{
				$this->db->where('(hms_patient.branch_id='.$post['sub_branch_id'].' and hms_payment.branch_id='.$post['sub_branch_id'].')');
				
			}
			else
			{
				$this->db->where('(hms_patient.branch_id='.$users_data['parent_id'].' and hms_payment.branch_id='.$users_data['parent_id'].')');
			   
			}
			if(!empty($id))
			{
				$this->db->where('hms_payment.patient_id',$id);
			}
			
			$this->db->group_by('hms_patient.id');

			$query = $this->db->get();
			// echo $this->db->last_query();die;
			$result = $query->result_array();

		}
		return $result;

	}
	public function payment_to_branch()
	{
		$post = $this->input->post();
		$users_data = $this->session->userdata('auth_users');
		if(isset($post) && !empty($post))
		{
			$data = array(
				'branch_id'=>$users_data['parent_id'],
				'patient_id'=>$post['data_id'],
				'total_amount'=>$post['balance'],
				'net_amount'=>$post['balance'],
				'debit'=>$post['balance']
			);
			 
			
			$this->db->insert('hms_payment',$data);
			
		}

	}

} 
?>