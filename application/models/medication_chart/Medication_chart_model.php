<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medication_chart_model extends CI_Model {

	var $table = 'hms_ipd_medication_chart_table';
	var $column = array('hms_ipd_medication_chart_table.id','hms_ipd_medication_chart_table.booking_id','hms_ipd_medication_chart_table.patient_id','hms_ipd_medication_chart_table.ipd_no', 'hms_ipd_medication_chart_table.ipd_id','hms_ipd_medication_chart_table.created_date','hms_patient.patient_code','hms_patient.patient_name');  
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
		$this->db->select("hms_ipd_medication_chart_table.*, hms_ipd_booking.ipd_no,
		hms_ipd_booking.patient_id,hms_ipd_booking.patient_no,
		hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.mobile_no
		"); 
		$this->db->from($this->table); 
        // $this->db->where('hms_ipd_medication_chart_table.is_deleted','0');
        $this->db->where('hms_ipd_medication_chart_table.branch_id',$users_data['parent_id']);
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_medication_chart_table.ipd_id'); 
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
    
    public function religion_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('religion','ASC'); 
    	$query = $this->db->get('hms_ipd_medication_chart_table');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_ipd_medication_chart_table.*');
		$this->db->from('hms_ipd_medication_chart_table'); 
		$this->db->where('hms_ipd_medication_chart_table.id',$id);
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			
			$this->db->where('id',$id);
			$this->db->delete('hms_ipd_medication_chart_table');
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
			$this->db->delete('hms_ipd_medication_chart_table');
			//echo $this->db->last_query();die;
    	} 
    }
  

     public function check_unique_value($branch_id, $religion, $id='')
    {
    	$this->db->select('hms_ipd_medication_chart_table.*');
		$this->db->from('hms_ipd_medication_chart_table'); 
		$this->db->where('hms_ipd_medication_chart_table.branch_id',$branch_id);
		$this->db->where('hms_ipd_medication_chart_table.religion',$religion);
		if(!empty($id))
		$this->db->where('hms_ipd_medication_chart_table.id !=',$id);
		$this->db->where('hms_ipd_medication_chart_table.is_deleted','0');
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
        // echo "<pre>";print_r($_POST); exit;
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
			'created_date' => date('Y-m-d H:i:s'),
			'created_by' => $user_data['id'],
			// 'diagnosis_id' => $post['diagnosis_id']
		);
		if(!empty($post['diagnosis_id'])) {
			$diagnosis_ids = $post['diagnosis_id'];// Array of diagnosis IDs
			$this->db->where_in('id', $diagnosis_ids);
			$result = $this->db->get('hms_opd_diagnosis')->result_array();
			$d = [];
			foreach($result as $dig) {
				$d[] = $dig['diagnosis'];
			}
			$data['diagnosis_id'] = implode('/', $d);
		}

		$this->db->insert('hms_ipd_medication_chart_table',$data);
		$insert_id = $this->db->insert_id();
		$this->session->set_userdata('madication_chart_id',$insert_id);
        
            if($post['medicine_name'][0]!="")
            {

                $this->db->where(array('medication_chart_table_id'=>$insert_id));
                $this->db->delete('hms_ipd_medication_chart');
                //echo $this->db->last_query(); exit;
            
                $post_medicine_name= $post['medicine_name'];

                $counter_name= count($post_medicine_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                {
                    if(!empty($post['medicine_name'][$i])){
                        $data_medicine= array(
                        'medicine_name'=>$post['medicine_name'][$i],
                        'medicine_dose'=>$post['medicine_dose'][$i],
                        'medicine_duration'=>$post['medicine_duration'][$i],
                        'medicine_frequency'=>$post['medicine_frequency'][$i],
                        'ipd_id'=>$ipd_booking_data['id'],
                        'patient_id'=>$ipd_booking_data['patient_id'],
                        'ipd_no' => $ipd_booking_data['ipd_no'],
                        'booking_id' => $ipd_booking_data['id'],
                        'date' => $post['date'][$i],
						'medication_chart_table_id' => $insert_id,
                        );
                        $this->db->insert('hms_ipd_medication_chart',$data_medicine);
                    }
                }
            }

          
    
    }

	public function update($id="")
    {
        // echo "<pre>";print_r($_POST); exit;
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
			// 'diagnosis_id' => $post['diagnosis_id']
		);
		if(!empty($post['diagnosis_id'])) {
			$diagnosis_ids = $post['diagnosis_id'];// Array of diagnosis IDs
			$this->db->where_in('id', $diagnosis_ids);
			$result = $this->db->get('hms_opd_diagnosis')->result_array();
			
			$d = [];
			foreach($result as $dig) {
				$d[] = $dig['id'];
			}
			$data['diagnosis_id'] = implode(',', $d);
		}
		$this->db->where('id',$id);
		$this->db->update('hms_ipd_medication_chart_table',$data);
		
        
            if($post['medicine_name'][0]!="")
            {

                $this->db->where(array('medication_chart_table_id'=>$id));
                $this->db->delete('hms_ipd_medication_chart');
                //echo $this->db->last_query(); exit;
            
                $post_medicine_name= $post['medicine_name'];

                $counter_name= count($post_medicine_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                {
                    if(!empty($post['medicine_name'][$i])){
                        $data_medicine= array(
                        'medicine_name'=>$post['medicine_name'][$i],
                        'medicine_dose'=>$post['medicine_dose'][$i],
                        'medicine_duration'=>$post['medicine_duration'][$i],
                        'medicine_frequency'=>$post['medicine_frequency'][$i],
                        'ipd_id'=>$ipd_booking_data['id'],
                        'patient_id'=>$ipd_booking_data['patient_id'],
                        'ipd_no' => $ipd_booking_data['ipd_no'],
                        'booking_id' => $ipd_booking_data['id'],
                        'date' => $post['date'][$i],
						'medication_chart_table_id' => $id,
                        );
                        $this->db->insert('hms_ipd_medication_chart',$data_medicine);
                    }
                }
            }

          
    
    }

	
}
?>