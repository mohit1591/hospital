<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipd_patient_model extends CI_Model 
{
	var $table = 'hms_ipd_booking';
	var $column = array('hms_ipd_booking.id','hms_ipd_booking.patient_id','hms_ipd_booking.ipd_id', 'hms_ipd_booking.patient_id','hms_ipd_booking.patient_id', 'hms_ipd_booking.patient_id','hms_ipd_booking.patient_id');  
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
		$search = $this->session->userdata('patient_search');
		$this->db->select("hms_ipd_booking.*, hms_ipd_booking.patient_id as p_id,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code,hms_patient.status, hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_patient.address"); 
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id');
		$this->db->where('hms_patient.is_deleted','0'); 
	
            
       
        if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_patient.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_patient.branch_id',$users_data['parent_id']);
		}
		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_patient.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_patient.created_date <= "'.$end_date.'"');
			}

			if(isset($search['simulation_id']) && !empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(isset($search['patient_name']) && !empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "%'.trim($search['patient_name']).'%"');
			}

			if(isset($search['patient_code']) && !empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "%'.$search['mobile_no'].'%"');
			}

			if(isset($search['gender'])  && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}

			if(isset($search['address']) && !empty($search['address']))
			{
				$this->db->where('hms_patient.address LIKE "'.$search['address'].'%"');
			}

			if(isset($search['country_id']) && !empty($search['country_id']))
			{
				$this->db->where('hms_patient.country_id',$search['country_id']);
			}

			if(isset($search['state_id']) && !empty($search['state_id']))
			{
				$this->db->where('hms_patient.state_id',$search['state_id']);
			}

			if(isset($search['city_id']) && !empty($search['city_id']))
			{
				$this->db->where('hms_patient.city_id',$search['city_id']);
			}

			if(isset($search['pincode']) && !empty($search['pincode']))
			{
				$this->db->where('hms_patient.pincode LIKE "'.$search['pincode'].'%"');
			}

			if(isset($search['marital_status']) && isset($search['marital_status']) && $search['marital_status']!="")
			{
				$this->db->where('hms_patient.marital_status',$search['marital_status']);
			}

			if(isset($search['religion_id']) && !empty($search['religion_id']))
			{
				$this->db->where('hms_patient.religion_id',$search['religion_id']);
			}

			if(isset($search['father_husband']) && !empty($search['father_husband']))
			{
				$this->db->where('hms_patient.father_husband LIKE "'.$search['father_husband'].'%"');
			}

			if(isset($search['mother']) && !empty($search['mother']))
			{
				$this->db->where('hms_patient.mother LIKE "'.$search['mother'].'%"');
			}

			if(isset($search['guardian_name']) && !empty($search['guardian_name']))
			{
				$this->db->where('hms_patient.guardian_name LIKE "'.$search['guardian_name'].'%"');
			}

			if(isset($search['guardian_email']) && !empty($search['guardian_email']))
			{
				$this->db->where('hms_patient.guardian_email LIKE "'.$search['guardian_email'].'%"');
			}

			if(isset($search['guardian_phone']) && !empty($search['guardian_phone']))
			{
				$this->db->where('hms_patient.guardian_phone LIKE "'.$search['guardian_phone'].'%"');
			}

			if(isset($search['relation_id']) && !empty($search['relation_id']))
			{
				$this->db->where('hms_patient.relation_id',$search['relation_id']);
			}

			if(isset($search['patient_email']) && !empty($search['patient_email']))
			{
				$this->db->where('hms_patient.patient_email LIKE "'.$search['patient_email'].'%"');
			}

			if(isset($search['monthly_income']) && !empty($search['monthly_income']))
			{
				$this->db->where('hms_patient.monthly_income', $search['monthly_income']);
			}

			if(isset($search['occupation']) && !empty($search['occupation']))
			{
				$this->db->where('hms_patient.occupation LIKE "'.$search['occupation'].'%"');
			}

			if(isset($search['insurance_type']) && isset($search['insurance_type']) && $search['insurance_type']!="")
			{
				$this->db->where('hms_patient.insurance_type',$search['insurance_type']);
			}

			if(isset($search['insurance_type_id']) && isset($search['insurance_type_id']) && $search['insurance_type_id']!="")
			{
				$this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
			}

			if(isset($search['ins_company_id']) && isset($search['ins_company_id']) && $search['ins_company_id']!="")
			{
				$this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
			}

			if(isset($search['polocy_no']) && $search['polocy_no']!="")
			{
				$this->db->where('hms_patient.polocy_no',$search['polocy_no']);
			}

			if(isset($search['tpa_id']) && $search['tpa_id']!="")
			{
				$this->db->where('hms_patient.tpa_id',$search['tpa_id']);
			}

			if(isset($search['ins_amount']) && $search['ins_amount']!="")
			{
				$this->db->where('hms_patient.ins_amount',$search['ins_amount']);
			}

			if(isset($search['ins_authorization_no']) && $search['ins_authorization_no']!="")
			{
				$this->db->where('hms_patient.ins_authorization_no',$search['ins_authorization_no']);
			}

			if(isset($search['status']) && isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_patient.status',$search['status']);
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

	

} 
?>