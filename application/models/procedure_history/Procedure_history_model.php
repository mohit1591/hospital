<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Procedure_history_model extends CI_Model {

	var $table = 'hms_operation_procedure_note_summary';
	var $column = array(
		'hms_operation_procedure_note_summary.id',
		'hms_patient.patient_code',
		'hms_patient.patient_name',
		'hms_patient.mobile_no',
		'hms_patient.gender',
		'hms_operation_booking.booking_code',
		'hms_procedure_data.name',
		'hms_operation_procedure_note_summary.created_date
		');  
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
		$this->db->select("hms_operation_procedure_note_summary.*, hms_procedure_data.name,
			hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender,hms_operation_booking.booking_code
		"); 
		$this->db->from($this->table); 
		$this->db->join('hms_procedure_data','hms_procedure_data.id = hms_operation_procedure_note_summary.procedure_data','left');
		$this->db->join('hms_patient','hms_patient.id = hms_operation_procedure_note_summary.patient_id','left');
		$this->db->join('hms_operation_booking','hms_operation_booking.id = hms_operation_procedure_note_summary.ot_booking_id','left');
        // $this->db->where('hms_operation_procedure_note_summary.is_deleted','0');
    
      
            $this->db->where('hms_operation_procedure_note_summary.branch_id',$users_data['parent_id']);
	
 
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
    
    public function procedures_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('id','ASC'); 
    	$query = $this->db->get('hms_operation_procedure_note_summary');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_operation_procedure_note_summary.*');
		$this->db->from('hms_operation_procedure_note_summary'); 
		$this->db->where('hms_operation_procedure_note_summary.id',$id);
		// $this->db->where('hms_operation_procedure_note_summary.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$this->load->model('ot_booking/ot_booking_model', 'otbooking');
		$this->load->model('general/general_model');

		$postData = $this->input->post();
		$otBooking = $this->otbooking->get_by_id("", $postData['booking_code']);
		// dd($otBooking);
		if (empty($otBooking)) {
			// Handle the case where the booking is not found
			show_error('Booking not found.');
			return;
		}

		$users_data = $this->session->userdata('auth_users');
		$ot_summary_id = $otBooking['id'];
		$patient_id = $otBooking['patient_id'];
		$ot_booking_id = $otBooking['id'];

		// Prepare data array for inserting into hms_operation_procedure_note_summary
		$data_array = [
			'branch_id' => $users_data['parent_id'],
			'patient_id' => $patient_id,
			'ot_booking_id' => $ot_booking_id,
			'ot_procedure_id' => $postData['ot_procedure'],
			'post_observation_id' => $postData['post_observations'],
			'remark' => $postData['remark'],
			'diagnosis' => $postData['diagnosis'],
			'op_findings' => $postData['op_findings'],
			'procedures' => $postData['procedures'],
			'pos_op_orders' => $postData['pos_op_orders'],
			'indication_of_surgery' => $postData['indication_of_surgery'],
			'type_of_anaesthesia' => $postData['type_of_anaesthesia'],
			'name_of_anaesthetist' => $postData['name_of_anaesthetist'],
			'operation_start_time' => $this->formatDate($postData['operation_start_time']),
			'operation_finish_time' => $this->formatDate($postData['operation_finish_time']),
			'surgeon_name' => $postData['surgeon_name'],
			'post_operative_period' => $postData['post_operative_period'],
			'blood_transfusion' => $postData['blood_transfusion'],
			'recovery_time' => $this->formatDate($postData['recovery_time']),
			'blood_loss' => $postData['blood_loss'],
			'drain' => $postData['drain'],
			'histopathological' => $postData['histopathological'],
			'microbiological' => $postData['microbiological'],
			'created_date' => date('Y-m-d'),
			'created_by' => $users_data['id'],
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'status' => 1,
			'procedure_data' => $postData['procedure_data']
		];

		// Insert data into hms_operation_procedure_note_summary
		$this->db->insert('hms_operation_procedure_note_summary', $data_array);
		$inserted_id = $this->db->insert_id();

		// Insert doctor names into hms_operation_summary_procedure_note_to_doctors
		foreach ($postData['doctor_names'] as $key => $value) {
			$doctor_array = [
				'ot_procedure_id' => $inserted_id,
				'summary_id' => $ot_booking_id,
				'doctor_id' => $key,
				'doctor_name' => $value[0]
			];
			$this->db->insert('hms_operation_summary_procedure_note_to_doctors', $doctor_array);
		}

		// Insert medicine data into hms_operation_procedure_note_summary_to_medicine
		if (!empty($postData['medicine'])) {
			$this->db->where('ot_procedure_id', $inserted_id);
			$this->db->where('branch_id', $users_data['parent_id']);
			$this->db->delete('hms_operation_procedure_note_summary_to_medicine');

			foreach ($postData['medicine'] as $data) {
				if (!empty($data['medicine_name'])) {
					$medicine_array = [
						'summary_id' => $ot_summary_id,
						'ot_procedure_id' => $inserted_id,
						'branch_id' => $users_data['parent_id'],
						'is_eye_drop' => $data['is_eyedrop'],
						'medicine_name' => $data['medicine_name'],
						'medicine_unit' => $data['medicine_unit'],
						'medicine_company' => $data['medicine_company'],
						'medicine_salt' => $data['medicine_salt'],
						'medicine_dose' => $data['medicine_dosage'],
						'medicine_duration' => $data['medicine_duration'],
						'medicine_frequency' => $data['medicine_frequency'],
						'medicine_advice' => $data['medicine_advice'],
						'medicine_date' => date('Y-m-d', strtotime($data["medicine_date"])),
						'left_eye' => $data['left_eye'],
						'right_eye' => $data['right_eye'],
						'created_date' => date('Y-m-d H:i:s'),
						'created_by' => $users_data['id'],
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'status' => 1
					];
					$this->db->insert('hms_operation_procedure_note_summary_to_medicine', $medicine_array);
				}
			}
		}
	}

	public function update($id)
	{
		$this->load->model('ot_booking/ot_booking_model', 'otbooking');
		$this->load->model('general/general_model');

		$postData = $this->input->post();
		$otBooking = $this->otbooking->get_by_id("", $postData['booking_code']);
		
		if (empty($otBooking)) {
			// Handle the case where the booking is not found
			show_error('Booking not found.');
			return;
		}

		$users_data = $this->session->userdata('auth_users');
		$ot_summary_id = $otBooking['id'];
		$patient_id = $otBooking['patient_id'];
		$ot_booking_id = $otBooking['id'];
		// Prepare data array for inserting into hms_operation_procedure_note_summary
		$data_array = [
			'branch_id' => $users_data['parent_id'],
			'patient_id' => $patient_id,
			'ot_booking_id' => $ot_booking_id,
			'ot_procedure_id' => $postData['ot_procedure'],
			'post_observation_id' => $postData['post_observations'],
			'remark' => $postData['remark'],
			'diagnosis' => $postData['diagnosis'],
			'op_findings' => $postData['op_findings'],
			'procedures' => $postData['procedures'],
			'pos_op_orders' => $postData['pos_op_orders'],
			'indication_of_surgery' => $postData['indication_of_surgery'],
			'type_of_anaesthesia' => $postData['type_of_anaesthesia'],
			'name_of_anaesthetist' => $postData['name_of_anaesthetist'],
			'operation_start_time' => $this->formatDate($postData['operation_start_time']),
			'operation_finish_time' => $this->formatDate($postData['operation_finish_time']),
			'surgeon_name' => $postData['surgeon_name'],
			'post_operative_period' => $postData['post_operative_period'],
			'blood_transfusion' => $postData['blood_transfusion'],
			'recovery_time' => $this->formatDate($postData['recovery_time']),
			'blood_loss' => $postData['blood_loss'],
			'drain' => $postData['drain'],
			'histopathological' => $postData['histopathological'],
			'microbiological' => $postData['microbiological'],
			'created_date' => date('Y-m-d'),
			'created_by' => $users_data['id'],
			'ip_address' => $_SERVER['REMOTE_ADDR'],
			'status' => 1,
			'procedure_data' => $postData['procedure_data']
		];

		// Insert data into hms_operation_procedure_note_summary
		$this->db->where('id',$id);
		$this->db->update('hms_operation_procedure_note_summary', $data_array);
		$inserted_id = $id;

		// Insert doctor names into hms_operation_summary_procedure_note_to_doctors
		$this->db->where('ot_procedure_id', $id);
		$this->db->delete('hms_operation_summary_procedure_note_to_doctors');
		foreach ($postData['doctor_names'] as $key => $value) {
			$doctor_array = [
				'ot_procedure_id' => $inserted_id,
				'summary_id' => $ot_booking_id,
				'doctor_id' => $key,
				'doctor_name' => $value[0]
			];
			$this->db->insert('hms_operation_summary_procedure_note_to_doctors', $doctor_array);
		}

		// Insert medicine data into hms_operation_procedure_note_summary_to_medicine
		if (!empty($postData['medicine'])) {
			$this->db->where('ot_procedure_id', $inserted_id);
			$this->db->where('branch_id', $users_data['parent_id']);
			$this->db->delete('hms_operation_procedure_note_summary_to_medicine');

			foreach ($postData['medicine'] as $data) {
				if (!empty($data['medicine_name'])) {
					$medicine_array = [
						'summary_id' => $ot_summary_id,
						'ot_procedure_id' => $inserted_id,
						'branch_id' => $users_data['parent_id'],
						'is_eye_drop' => $data['is_eyedrop'],
						'medicine_name' => $data['medicine_name'],
						'medicine_unit' => $data['medicine_unit'],
						'medicine_company' => $data['medicine_company'],
						'medicine_salt' => $data['medicine_salt'],
						'medicine_dose' => $data['medicine_dosage'],
						'medicine_duration' => $data['medicine_duration'],
						'medicine_frequency' => $data['medicine_frequency'],
						'medicine_advice' => $data['medicine_advice'],
						'medicine_date' => date('Y-m-d', strtotime($data["medicine_date"])),
						'left_eye' => $data['left_eye'],
						'right_eye' => $data['right_eye'],
						'created_date' => date('Y-m-d H:i:s'),
						'created_by' => $users_data['id'],
						'ip_address' => $_SERVER['REMOTE_ADDR'],
						'status' => 1
					];
					$this->db->insert('hms_operation_procedure_note_summary_to_medicine', $medicine_array);
				}
			}
		}
	}

	public function formatDate($date)
	{
		if ($date != '00-00-0000 00:00:00' && $date != '01-01-1970') {
			return date('Y-m-d H:i:s', strtotime($date));
		}
		return '';
	}


    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->where('ot_procedure_id',$id)->delete('hms_operation_summary_procedure_note_to_doctors');
			$this->db->where('ot_procedure_id',$id)->delete('hms_operation_procedure_note_summary_to_medicine');
			$this->db->where('id',$id)->delete('hms_operation_procedure_note_summary');
			
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
    			$this->db->where('ot_procedure_id',$id)->delete('hms_operation_summary_procedure_note_to_doctors');
				$this->db->where('ot_procedure_id',$id)->delete('hms_operation_procedure_note_summary_to_medicine');
				$this->db->where('id',$id)->delete('hms_operation_procedure_note_summary');
    		}
    	} 
    }
  

     public function check_unique_value($branch_id, $religion, $id='')
    {
    	$this->db->select('hms_operation_procedure_note_summary.*');
		$this->db->from('hms_operation_procedure_note_summary'); 
		$this->db->where('hms_operation_procedure_note_summary.branch_id',$branch_id);
		$this->db->where('hms_operation_procedure_note_summary.religion',$religion);
		if(!empty($id))
		$this->db->where('hms_operation_procedure_note_summary.id !=',$id);
		$this->db->where('hms_operation_procedure_note_summary.is_deleted','0');
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

	function procedure_doctor_list_by_otids($id){
        $this->db->select('hms_operation_summary_procedure_note_to_doctors.*');
        $this->db->from('hms_operation_summary_procedure_note_to_doctors'); 
        $this->db->where('hms_operation_summary_procedure_note_to_doctors.ot_procedure_id',$id);
        
        $query = $this->db->get()->result();
        $data=array(); 
        foreach($query as $res){
            $data[$res->doctor_id][]=$res->doctor_name;
        }
        return $data;
    
    }

	public function get_procedure_summary_medicine_data($id, $branch_id)
    {
    	$this->db->select('hms_operation_procedure_note_summary_to_medicine.*');
    	$this->db->from('hms_operation_procedure_note_summary_to_medicine');
    	$this->db->where('hms_operation_procedure_note_summary_to_medicine.branch_id',$branch_id);
    	$this->db->where('hms_operation_procedure_note_summary_to_medicine.ot_procedure_id',$id);
    	$res=$this->db->get();
    	if($res->num_rows() > 0)
    		return $res->result();
    	else
    		return "empty";
    }
}
?>