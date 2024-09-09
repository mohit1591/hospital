<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Opdprescriptionhistory_model extends CI_Model 
{
	var $table = 'hms_opd_prescription';
	var $column = array('hms_opd_prescription.id','hms_opd_prescription.booking_code','hms_opd_prescription.patient_name', 'hms_opd_prescription.mobile_no','hms_opd_prescription.patient_code', 'hms_opd_prescription.status', 'hms_opd_prescription.created_date', 'hms_opd_prescription.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('patient_search');
		$this->db->select("hms_opd_prescription.*"); 
		//,hms_opd_prescription_patient_test.test_name,hms_opd_prescription_patient_pres.medicine_name,hms_opd_prescription_patient_pres.medicine_type,hms_opd_prescription_patient_pres.medicine_dose,hms_opd_prescription_patient_pres.medicine_duration,hms_opd_prescription_patient_pres.medicine_frequency,hms_opd_prescription_patient_pres.medicine_advice
		//$this->db->join('hms_opd_prescription_patient_test','hms_opd_prescription_patient_test.prescription_id=hms_opd_prescription.id','left');
        //$this->db->join('hms_opd_prescription_patient_pres','hms_opd_prescription_patient_pres.prescription_id=hms_opd_prescription.id','left');
		
		$this->db->where('hms_opd_prescription.is_deleted','0'); 
		$this->db->where('hms_opd_prescription.branch_id = "'.$user_data['parent_id'].'"'); 

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
				$this->db->where('created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
				$this->db->where('created_date >= "'.$end_date.'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('patient_code',$search['patient_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('gender',$search['gender']);
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('status',$search['status']);
			}


		}

		/////// Search query end //////////////

		$this->db->from($this->table); 
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
		 $this->db->group_by('hms_opd_prescription.id');
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

	function get_datatables($patient_id='')
	{
		$this->_get_datatables_query();
		$this->db->where('patient_id',$patient_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function count_filtered($patient_id='')
	{
		$this->_get_datatables_query();
		$this->db->where('patient_id',$patient_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($patient_id='')
	{
		$this->db->from($this->table);
		$this->db->where('patient_id',$patient_id);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select("hms_opd_prescription.*,hms_opd_prescription_patient_test.test_name,hms_opd_prescription_patient_pres.medicine_name,hms_opd_prescription_patient_pres.medicine_type,hms_opd_prescription_patient_pres.medicine_dose,hms_opd_prescription_patient_pres.medicine_duration,hms_opd_prescription_patient_pres.medicine_frequency,hms_opd_prescription_patient_pres.medicine_advice"); 
		$this->db->from('hms_opd_prescription'); 
		$this->db->where('hms_opd_prescription.id',$id);
		$this->db->where('hms_opd_prescription.is_deleted','0'); 
		$this->db->join('hms_opd_prescription_patient_test','hms_opd_prescription_patient_test.prescription_id=hms_opd_prescription.id','left');
		$this->db->join('hms_opd_prescription_patient_pres','hms_opd_prescription_patient_pres.prescription_id=hms_opd_prescription.id','left');
		
		$query = $this->db->get(); 
		return $query->row_array();
	}

}	