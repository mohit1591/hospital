<?php
class Procedure_note_tab_setting_model extends CI_Model 
{
	var $table = 'hms_procedure_note_tab_setting'; 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
 
	public function get_setting()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->from('hms_procedure_note_tab_setting');
		$query = $this->db->get(); 
		return $query->result();
	}

	public function get_setting_single($var_title)
	{
		$this->db->select('*');
		$this->db->from('hms_procedure_note_tab_setting');
		$this->db->where('var_title', $var_title);
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		// Load the database library
		

		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		
		if (!empty($post['data']))
		{
			// Delete all records from the table
			// $this->db->delete('hms_procedure_note_tab_setting');

			// Check if deletion was successful
			if ($this->db->affected_rows() > 0) {
				// Deletion was successful
				echo "Records deleted successfully.";
			} else {
				// Deletion failed or no rows were affected
				echo "No records deleted or deletion failed.";
			}
			
			foreach ($post['data'] as $key => $val)
			{
				$status = !empty($val['status']) && $val['status'] == 1 ? 1 : 0;
				$print_status = !empty($val['print_status']) && $val['print_status'] == 1 ? 1 : 0;

				$data = array(
					"var_title" => $val['setting_name'],
					"var_value" => $val['setting_value'],
					"status" => $status,
					'print_status' => $print_status,
				);

				$this->db->where('var_title', $val['setting_name']);
				$query = $this->db->get('hms_procedure_note_tab_setting');

				if ($query->num_rows() > 0) {
					// Record exists, update it
					$this->db->where('var_title', $val['setting_name']);
					$this->db->update('hms_procedure_note_tab_setting', $data);
				} else {
					// Record does not exist, insert new one
					$this->db->insert('hms_procedure_note_tab_setting', $data);
				}
			}
		}
	} 
} 
?>