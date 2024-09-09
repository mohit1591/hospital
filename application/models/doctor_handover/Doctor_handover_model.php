<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor_handover_model extends CI_Model {

	var $table = 'hms_ipd_doctor_handover';
	var $column = array('hms_ipd_doctor_handover.id','hms_ipd_doctor_handover.booking_id','hms_ipd_doctor_handover.patient_id','hms_ipd_doctor_handover.ipd_no', 'hms_ipd_doctor_handover.ipd_id','hms_ipd_doctor_handover.created_date','hms_patient.patient_code','hms_patient.patient_name');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
          $parent_branch_details = $this->session->userdata('parent_branches_data');
         $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_ipd_doctor_handover.*, hms_ipd_booking.ipd_no,
		hms_ipd_booking.patient_id,hms_ipd_booking.patient_no,
		hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.mobile_no
		"); 
		$this->db->from($this->table); 
        // $this->db->where('hms_ipd_doctor_handover.is_deleted','0');
        $this->db->where('hms_ipd_doctor_handover.branch_id',$users_data['parent_id']);
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_doctor_handover.ipd_id'); 
		$this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id'); 
		

	
 
		$i = 0;
	
		foreach ($this->column as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column) - 1 == $i) //last loop+
					$this->db->group_end(); //close bracket
			}
			$column[$i] = $item; // set column array variable to order processing
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($branch_id='')
	{
		$this->_get_datatables_query($branch_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		// echo $this->db->last_query();die;
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
    
    public function list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('religion','ASC'); 
    	$query = $this->db->get('hms_ipd_doctor_handover');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_ipd_doctor_handover.*');
		$this->db->from('hms_ipd_doctor_handover'); 
		$this->db->where('hms_ipd_doctor_handover.id',$id);
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			
			$this->db->where('id',$id);
			$this->db->delete('hms_ipd_doctor_handover');
			//echo $this->db->last_query();die;
    	} 
    }

    public function deleteall($ids=array())
    {
    	if(!empty($ids))
    	{ 

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$branch_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->delete('hms_ipd_doctor_handover');
			//echo $this->db->last_query();die;
    	} 
    }
  

     public function check_unique_value($branch_id, $religion, $id='')
    {
    	$this->db->select('hms_ipd_doctor_handover.*');
		$this->db->from('hms_ipd_doctor_handover'); 
		$this->db->where('hms_ipd_doctor_handover.branch_id',$branch_id);
		$this->db->where('hms_ipd_doctor_handover.religion',$religion);
		if(!empty($id))
		$this->db->where('hms_ipd_doctor_handover.id !=',$id);
		$this->db->where('hms_ipd_doctor_handover.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$result=$query->row_array();
		if(!empty($result))
		{
			return 1;
		}
		else{
			return 0;
		}
    }

	public function save()
	{
		// Fetch user session data
		$user_data = $this->session->userdata('auth_users');
		if (empty($user_data)) {
			log_message('error', 'User data not found in session.');
			show_error('User data not found.', 500);
			return;
		}

		// Fetch post data
		$post = $this->input->post();
		if (empty($post)) {
			log_message('error', 'No post data received.');
			show_error('No data received.', 400);
			return;
		}

		// Load models
		$this->load->model('ipd_booking/ipd_booking_model');
		$this->load->model('patient/patient_model');

		// Retrieve IPD booking data
		$ipd_booking_data = $this->ipd_booking_model->get_by_id($post['ipd_id']);
		if (empty($ipd_booking_data)) {
			log_message('error', 'No IPD booking data found for ID: ' . $post['ipd_id']);
			show_error('IPD booking not found.', 404);
			return;
		}

		// Prepare data for insertion
		$data = array(
			"ipd_no" => $ipd_booking_data['ipd_no'],
			"booking_id" => $ipd_booking_data['id'],
			'ipd_id' => $ipd_booking_data['id'],
			'branch_id' => $user_data['parent_id'],
			'patient_id' => $ipd_booking_data['patient_id'],
			'created_date' => date('Y-m-d H:i:s'),
			'created_by' => $user_data['id'],
			'modified_by' => $user_data['id'], // Assuming the user modifying is the same as created
			'modified_date' => date('Y-m-d H:i:s'), // Setting current date and time for modification
			'handover_date' => $_POST['handover_date'],

			'shift' => $post['shift'],
			'current_complaints' => $post['current_complaints'],
			'general_condition' => $post['general_condition'],
			'any_changes_in_medication' => $post['any_changes_in_medication'],
			'pending_investigations' => $post['pending_investigations'],
			'care_plan' => $post['care_plan'],
			'discharge_transfer_shifting' => $post['discharge_transfer_shifting'],
			'current_medication' => $post['current_medication'],
			'medication_during_stay' => $post['medication_during_stay'],
			'medication_on_discharge' => $post['medication_on_discharge'],
			'given_by' => $post['given_by'],
			'taken_by' => $post['taken_by'],
			'pain_grade' => $post['pain_grade'],
			'remark' => $post['remark'],
			'pain_assesment_scale' => $post['pain_assesment_scale']
		);
		

		// Insert data into database with error handling
		try {
			$this->db->insert('hms_ipd_doctor_handover', $data);
			if ($this->db->affected_rows() <= 0) {
				throw new Exception('Database insertion failed.');
			}

			// Retrieve insert ID and set session data
			$insert_id = $this->db->insert_id();
			$this->session->set_userdata('doctor_handover_id', $insert_id);

		} catch (Exception $e) {
			// Log the error message and display it to the user
			log_message('error', 'Failed to insert data into hms_ipd_doctor_handover: ' . $e->getMessage());
			show_error('An error occurred while saving data: ' . $e->getMessage(), 500);
			return;
		}
	}


	public function update($id="")
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
		$this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        $ipd_booking_data = $this->ipd_booking_model->get_by_id($post['ipd_id']);
		$data = array(
			"ipd_no" => $ipd_booking_data['ipd_no'],
			"booking_id" => $ipd_booking_data['id'],
			'ipd_id' => $ipd_booking_data['id'],
			'branch_id' => $user_data['parent_id'],
			'patient_id' => $ipd_booking_data['patient_id'],
			'modified_date' => date('Y-m-d H:i:s'),
			'modified_by' => $user_data['id'],
			'handover_date' => $_POST['handover_date'],

			'shift' => $post['shift'],
			'current_complaints' => $post['current_complaints'],
			'general_condition' => $post['general_condition'],
			'any_changes_in_medication' => $post['any_changes_in_medication'],
			'pending_investigations' => $post['pending_investigations'],
			'care_plan' => $post['care_plan'],
			'discharge_transfer_shifting' => $post['discharge_transfer_shifting'],
			'current_medication' => $post['current_medication'],
			'medication_during_stay' => $post['medication_during_stay'],
			'medication_on_discharge' => $post['medication_on_discharge'],
			'given_by' => $post['given_by'],
			'taken_by' => $post['taken_by'],
			'pain_grade' => $post['pain_grade'],
			'remark' => $post['remark'],
			'pain_assesment_scale' => $post['pain_assesment_scale'],
			'morning_shift_date_time' => $post['morning_shift_date_time']
		);
		$this->db->where('id',$id);
		$this->db->update('hms_ipd_doctor_handover',$data);
		$this->session->set_userdata('doctor_handover_id',$id);
    }
}
?>