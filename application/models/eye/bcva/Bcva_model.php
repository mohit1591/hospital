<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bcva_model extends CI_Model 
{
	public function delete_sphere_plus_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_sphere_plus";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_sphere_plus_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_sphere_plus";
		$this->db->insert($table_name,$data_array);
	}

	public function get_sphere_plus_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_sphere_plus";
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


	// for sphere minus

	public function delete_sphere_minus_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_sphere_minus";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_sphere_minus_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_sphere_minus";
		$this->db->insert($table_name,$data_array);
	}

	public function get_sphere_minus_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_sphere_minus";
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
	// For sphere minus



	// For Cylinder Plus
	public function delete_cylinder_plus_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_cylinder_plus";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_cylinder_plus_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_cylinder_plus";
		$this->db->insert($table_name,$data_array);
	}

	public function get_cylinder_plus_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_cylinder_plus";
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
	// For cylinder Plus


	// for cylinder minus

	public function delete_cylinder_minus_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_cylinder_minus";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_cylinder_minus_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_cylinder_minus";
		$this->db->insert($table_name,$data_array);
	}

	public function get_cylinder_minus_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_cylinder_minus";
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
	// For cylinder minus


	// for add

	public function delete_add_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_add";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_add_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_add";
		$this->db->insert($table_name,$data_array);
	}

	public function get_add_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_add";
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
	// For add



	// for axis

	public function delete_axis_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_axis";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_axis_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_axis";
		$this->db->insert($table_name,$data_array);
	}

	public function get_axis_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_axis";
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
	// For Axis

	// for dva

	public function delete_dva_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_dva";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_dva_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_dva";
		$this->db->insert($table_name,$data_array);
	}

	public function get_dva_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_dva";
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
	// For dva


	// for nva

	public function delete_nva_branch_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_nva";
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->delete($table_name);
	}

	public function save_nva_branch_data($data_array)
	{
		$table_name="hms_eye_bcva_nva";
		$this->db->insert($table_name,$data_array);
	}

	public function get_nva_data($last_rec)
	{
		$users_data = $this->session->userdata('auth_users');
		$table_name="hms_eye_bcva_nva";
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
	// For nva



// Please write code above
}
?>