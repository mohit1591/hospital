<?php
class Doctors_panel_model extends CI_Model 
{
	var $table = 'hms_insurance_company';
	var $column = array('hms_insurance_company.id','hms_insurance_company.charge','hms_insurance_company.emergency_charge');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$panel_adv_search = $this->session->userdata('panel_doctor_search');
		$doctor_id='';
		if(!empty($panel_adv_search))
		{
			 $doctor_id = $panel_adv_search['doctor_id']; 
		}
		$this->db->select('hms_insurance_company.*,(CASE WHEN hms_doctor_panel_charge.charge > 0 THEN hms_doctor_panel_charge.charge ELSE  (select doctor_charge.consultant_charge from hms_doctors as doctor_charge where doctor_charge.id ="'.$doctor_id.'" AND doctor_charge.is_deleted=0 AND doctor_charge.branch_id ="'.$user_data['parent_id'].'")  END) as charge,(CASE WHEN hms_doctor_panel_charge.emergency_charge > 0 THEN hms_doctor_panel_charge.emergency_charge ELSE (select doctor_emergency_charge.emergency_charge from hms_doctors as doctor_emergency_charge where doctor_emergency_charge.id ="'.$doctor_id.'" AND doctor_emergency_charge.is_deleted=0 AND doctor_emergency_charge.branch_id ="'.$user_data['parent_id'].'") END) as charge_emergency,hms_doctor_panel_charge.id as charge_id');
		$this->db->from($this->table);	


		$this->db->join('hms_doctor_panel_charge','hms_doctor_panel_charge.panel_id=hms_insurance_company.id and hms_doctor_panel_charge.doctor_id = "'.$doctor_id.'"','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_doctor_panel_charge.doctor_id AND hms_doctors.id = "'.$doctor_id.'"','left'); 
		//$this->db->where('hms_doctors.id',$doctor_id);
		$this->db->where('hms_insurance_company.is_deleted','0');
		$this->db->where('hms_insurance_company.status',1);
		$this->db->where('hms_insurance_company.branch_id',$user_data['parent_id']); 
		$this->db->order_by('hms_insurance_company.insurance_company','ASC');
		//$query = $this->db->get(); 
		
		 
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
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

	

    /*public function get_panel_list($doctor_id="",$panel_id='')
	{	
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_insurance_company.*,(CASE WHEN hms_doctor_panel_charge.charge > 0 THEN hms_doctor_panel_charge.charge ELSE  (select doctor_charge.consultant_charge from hms_doctors as doctor_charge where doctor_charge.id ="'.$doctor_id.'" AND doctor_charge.is_deleted=0 AND doctor_charge.branch_id ="'.$user_data['parent_id'].'")  END) as charge,(CASE WHEN hms_doctor_panel_charge.emergency_charge > 0 THEN hms_doctor_panel_charge.emergency_charge ELSE (select doctor_emergency_charge.emergency_charge from hms_doctors as doctor_emergency_charge where doctor_emergency_charge.id ="'.$doctor_id.'" AND doctor_emergency_charge.is_deleted=0 AND doctor_emergency_charge.branch_id ="'.$user_data['parent_id'].'") END) as charge_emergency,hms_doctor_panel_charge.id as charge_id');
		$this->db->from('hms_insurance_company');
		$this->db->join('hms_doctor_panel_charge','hms_doctor_panel_charge.panel_id=hms_insurance_company.id and hms_doctor_panel_charge.doctor_id = "'.$doctor_id.'"','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_doctor_panel_charge.doctor_id AND hms_doctors.id = "'.$doctor_id.'"','left'); 
		//$this->db->where('hms_doctors.id',$doctor_id);
		$this->db->where('hms_insurance_company.is_deleted','0');
		$this->db->where('hms_insurance_company.status',1);
		$this->db->where('hms_insurance_company.branch_id',$user_data['parent_id']); 
		$this->db->order_by('hms_insurance_company.insurance_company','ASC');
		$query = $this->db->get(); 
		//echo $this->db->last_query(); exit;
		$result = $query->result_array();
		
		return $result;
	}*/

	public function save_panel_charge()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		if(!empty($post['doctor_id']))
		{ 
			$this->db->where('doctor_id',$post['doctor_id']);
			$this->db->delete('hms_doctor_panel_charge');
			//print_r($post['charge']); exit;
			foreach($post['charge'] as $key=>$val)
			{
				$doctor_panel_charge  = array(
												"branch_id"=>$user_data['parent_id'],
												'doctor_id'=>$post['doctor_id'],
												'panel_id'=>$key,
												'charge'=>$val,
												'emergency_charge'=>$post['emergency_charge'][$key],
										     ); 
				//print_r($doctor_panel_charge); exit;
				$this->db->insert('hms_doctor_panel_charge',$doctor_panel_charge);
				//echo $this->db->last_query();
				
			}
		}

	}


	public function doctor_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		//$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
	
} 
?>