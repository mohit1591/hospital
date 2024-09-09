<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ucva_model extends CI_Model 
{
	public function delete_dva_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_ucva_dva";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_dva_branch_data($data_array)
	{
		$table_name="hms_eye_ucva_dva";
		$this->db->insert($table_name,$data_array);
	}

	public function get_dva_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_ucva_dva";
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('branch_id',$users_data['parent_id']);
		if($last_rec==1)
		{
			$this->db->order_by('id','desc');
			$this->db->limit(1);
			$res=$this->db->get();
			if($res->num_rows() > 0)
				return $res->result();
			else
				return "empty";
		}
		else
		{
			$res=$this->db->get();
			if($res->num_rows() > 0)
			{	
				return $res->result();
			}
			else
			{
				return "empty";
			}	
		}

		
	}
	// for NVA


	public function delete_nva_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_ucva_nva";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_nva_branch_data($data_array)
	{
		$table_name="hms_eye_ucva_nva";
		$this->db->insert($table_name,$data_array);
	}

	public function get_nva_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_ucva_nva";
		$this->db->select('*');
		$this->db->from($table_name);
		$this->db->where('branch_id',$users_data['parent_id']);
		if($last_rec==1)
		{
			$this->db->order_by('id','desc');
			$this->db->limit(1);
			$res=$this->db->get();
			if($res->num_rows() > 0)
				return $res->result();
			else
				return "empty";
		}
		else
		{
			$res=$this->db->get();
			if($res->num_rows() > 0)
			{	
				return $res->result();
			}
			else
			{
				return "empty";
			}	
		}

		
	}
	// For NVA





// Please write code above
}
?>