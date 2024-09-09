<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Patient_model extends CI_Model 
{
	var $table = 'hms_patient';
	var $column = array('hms_patient.id','hms_patient.patient_code','hms_patient.patient_name', 'hms_patient.relation_name','hms_patient.mobile_no','hms_patient.gender','hms_patient.age_y', 'hms_patient.address', 'hms_patient.adhar_no', 'hms_patient.marital_status', 'hms_patient.anniversary', 'hms_religion.religion', 'hms_patient.dob', 'hms_patient.mother', 'hms_patient.guardian_name','hms_patient.guardian_email', 'hms_patient.guardian_phone', 'hms_relation.relation', 'hms_patient.patient_email','hms_patient.monthly_income', 'hms_patient.occupation', 'hms_patient.insurance_type','hms_insurance_type.insurance_type' , 'hms_insurance_company.insurance_company','hms_patient.polocy_no', 'hms_patient.tpa_id','hms_patient.ins_amount', 'hms_patient.ins_authorization_no','hms_patient.created_date');  
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
        // changes by Nitin sharma 04/02/2024
		$this->db->select("hms_patient.id,hms_patient.capture_finger,hms_patient.branch_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.first_name,hms_patient.last_name,hms_patient.adhar_no,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.anniversary,hms_patient.age,hms_patient.age_y,hms_patient.age_d,hms_patient.age_m,hms_patient.age_h,hms_patient.marital_status,hms_patient.height,hms_patient.weight,hms_patient.religion_id,hms_patient.pincode,hms_patient.other_city,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.f_h_simulation,hms_patient.father_husband,hms_patient.mother,hms_patient.guardian_name,hms_patient.guardian_email,hms_patient.guardian_phone,hms_patient.relation_id,hms_patient.camp_id,hms_patient.patient_email,hms_patient.monthly_income,hms_patient.occupation,hms_patient.photo,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.remark,hms_patient.status,hms_patient.anni_sms_year,hms_patient.anni_email_send_year,hms_patient.birth_sms_year,hms_patient.birth_email_year,hms_patient.is_deleted,hms_patient.created_date,hms_cities.city, hms_state.state,hms_gardian_relation.relation, (CASE When hms_patient.relation_type=1 THEN 'Son/o' When hms_patient.relation_type=2 THEN 'Husband/o' When hms_patient.relation_type=3 THEN 'Baby/o' When hms_patient.relation_type=4 THEN 'Father/o' When hms_patient.relation_type=5 THEN 'Daughter/o' When hms_patient.relation_type=6 THEN 'Wife/o'When hms_patient.relation_type=14 THEN 'Brother' Else '' END) as patient_relation,concat_ws(', ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, (Case When hms_patient.marital_status=0 Then 'Unmarried' ELSE 'Married' END) as marital_status, hms_religion.religion, hms_relation.relation, (CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' Else 'Normal' END ) as ins_type, hms_insurance_company.insurance_company, hms_insurance_type.insurance_type,(select id from hms_ipd_booking where hms_ipd_booking.patient_id=hms_patient.id AND hms_ipd_booking.is_deleted=0 AND hms_ipd_booking.discharge_status=0 ORDER BY hms_ipd_booking.id DESC LIMIT 1) as running 
			"); 
		// changes by Nitin sharma 04/02/2024
		$this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
		$this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
		$this->db->join('hms_religion', 'hms_religion.id=hms_patient.religion_id', 'left');
		$this->db->join('hms_relation', 'hms_relation.id=hms_patient.relation_id', 'left');
		 $this->db->join('hms_insurance_type ','hms_insurance_type.id=hms_patient.insurance_type_id', 'left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id', 'left');
    
       $this->db->where('hms_patient.is_deleted','0'); 
        if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_patient.branch_id',$search['branch_id']);
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
				$this->db->where('hms_patient.patient_code LIKE "%'.$search['patient_code'].'%"');
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
				
$this->db->where('CONCAT(hms_patient.address,hms_patient.address2,hms_patient.address3) like "%'.trim($search['address']).'%"');
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

			if(isset($search['start_age_y']) && !empty($search['start_age_y']))
			{
				$this->db->where('hms_patient.age_y >= "'.$search['start_age_y'].'"');
			}

			if(isset($search['end_age_y']) && !empty($search['end_age_y']))
			{
				$this->db->where('hms_patient.age_y <= "'.$search['end_age_y'].'"');
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

			if(isset($search['adhar_no']) && isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}
				if(!empty($search['relation_name']))
			{
				$this->db->where('hms_patient.relation_name LIKE "'.$search['relation_name'].'%"');
			}
			if(!empty($search['relation_type']) && !empty($search['relation_name']))
			{
				$this->db->where('hms_patient.relation_type',$search['relation_type']);
			}

			if(!empty($search['relation_simulation_id']))
			{
				$this->db->where('hms_patient.relation_simulation_id ',$search['relation_simulation_id']);
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
		//echo $this->db->last_query();die;
		return $query->num_rows();
	}

	public function count_all()
	{
	    $users_data = $this->session->userdata('auth_users');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
		$search = $this->session->userdata('patient_search');
		
		
		$this->db->from($this->table);
		
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
				$this->db->where('hms_patient.patient_code LIKE "%'.$search['patient_code'].'%"');
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
				
$this->db->where('CONCAT(hms_patient.address,hms_patient.address2,hms_patient.address3) like "%'.trim($search['address']).'%"');
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

			if(isset($search['start_age_y']) && !empty($search['start_age_y']))
			{
				$this->db->where('hms_patient.age_y >= "'.$search['start_age_y'].'"');
			}

			if(isset($search['end_age_y']) && !empty($search['end_age_y']))
			{
				$this->db->where('hms_patient.age_y <= "'.$search['end_age_y'].'"');
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

			if(isset($search['adhar_no']) && isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}
				if(!empty($search['relation_name']))
			{
				$this->db->where('hms_patient.relation_name LIKE "'.$search['relation_name'].'%"');
			}
			if(!empty($search['relation_type']) && !empty($search['relation_name']))
			{
				$this->db->where('hms_patient.relation_type',$search['relation_type']);
			}

			if(!empty($search['relation_simulation_id']))
			{
				$this->db->where('hms_patient.relation_simulation_id ',$search['relation_simulation_id']);
			}
		}
		
		 $query = $this->db->get(); 
        $data= $query->result();
        //echo $this->db->last_query(); exit;
        return count($data);
		//return $this->db->count_all_results();
	}
	
	

	public function get_by_id($id)
	{ 
		/*$this->db->select("hms_patient.*, hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation,hms_sim.simulation as rel_simulation, hms_religion.religion, hms_relation.relation as gardian_relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation,hms_patient.modified_date as patient_modified_date");*/
        //changed by Nitin Sharma 04/02/2024 		
		$this->db->select("hms_patient.capture_finger,hms_patient.fingerprint_photo,hms_patient.id,hms_patient.branch_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.first_name,hms_patient.last_name,hms_patient.adhar_no,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.anniversary,hms_patient.age,hms_patient.age_y,hms_patient.age_d,hms_patient.age_m,hms_patient.age_h,hms_patient.marital_status,hms_patient.height,hms_patient.weight,hms_patient.religion_id,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_patient.other_city,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.f_h_simulation,hms_patient.father_husband,hms_patient.mother,hms_patient.guardian_name,hms_patient.guardian_email,hms_patient.guardian_phone,hms_patient.relation_id,hms_patient.camp_id,hms_patient.patient_email,hms_patient.monthly_income,hms_patient.occupation,hms_patient.photo,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.remark,hms_patient.status,hms_patient.anni_sms_year,hms_patient.anni_email_send_year,hms_patient.birth_sms_year,hms_patient.birth_email_year,hms_patient.is_deleted,hms_patient.created_date,hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation,hms_sim.simulation as rel_simulation, hms_religion.religion, hms_relation.relation as gardian_relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation,hms_patient.modified_date as patient_modified_date"); 
		//changed by Nitin Sharma 04/02/2024
		$this->db->from('hms_patient'); 
		$this->db->where('hms_patient.id',$id);
		// $this->db->where('hms_patient.is_deleted','0'); 
		$this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4','left');
		$this->db->join('hms_religion','hms_religion.id=hms_patient.religion_id','left');
		$this->db->join('hms_relation','hms_relation.id=hms_patient.relation_id','left');
		$this->db->join('hms_countries','hms_countries.id=hms_patient.country_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_simulation','hms_simulation.id=hms_patient.simulation_id','left');

        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
         $this->db->join('hms_simulation as hms_sim','hms_sim.id=hms_patient.relation_simulation_id','left');
        $this->db->join('hms_insurance_type','hms_insurance_type.id=hms_patient.insurance_type_id','left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id','left');
		$query = $this->db->get(); 
		$result  = $query->row_array();
		//echo $this->db->last_query(); exit;
		return $result;
	}
	
	function get_doctor_data($id)
	{ //echo $id;die;

		 $this->db->select("*");
		 $this->db->from('hms_doctors'); 
		 $this->db->where('hms_doctors.id',$id);
		 $query = $this->db->get(); 
		 return $query->row_array();
	   
	}
	function get_doctor_name($id)
	{
	     $this->db->select("*");
		 $this->db->from('hms_doctors'); 
		 $this->db->where('hms_doctors.branch_id',$id);
		 $query = $this->db->get(); 
		 return $query->result_array();

	}
	function get_doctor_signature_data($id,$branch_id)
	{
		 $this->db->select("*");
		 $this->db->from('hms_signature'); 
		 $this->db->where('hms_signature.doctor_id',$id);
		 $this->db->where('hms_signature.branch_id',$branch_id);
		 $query = $this->db->get(); 
		 return $query->row_array();

	
	}
	function get_template_data()
        {
                 $user_data = $this->session->userdata('auth_users');
                 $parent_id = $user_data['parent_id'];
                 $this->db->select("*");
		 $this->db->from('hms_doctor_certificate'); 
                 $this->db->where('hms_doctor_certificate.is_deleted',0);
		 $this->db->where('hms_doctor_certificate.branch_id',$parent_id);
		 $query = $this->db->get(); 
		 return $query->result_array();

   	
       }
   function get_template_data_by_branch_id($branch_id,$template_id)
   {

      	$this->db->select("*");
		 $this->db->from('hms_doctor_certificate'); 
		 $this->db->where('hms_doctor_certificate.branch_id',$branch_id);
		  $this->db->where('hms_doctor_certificate.id',$template_id);
		 $query = $this->db->get(); 
		 return $query->row_array();

   }
	public function save($filename="")
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		 
			if($post['insurance_type']==0){
			$insurance_type_id='';
			$insurance_company='';
		}else{
			$insurance_type_id=$post['insurance_type_id'];
			$insurance_company =$post['ins_company_id'];
		}

		$anniversary='';
		if(!empty($post['anniversary']) && isset($post['anniversary']))
		{
			$anniversary = date('Y-m-d', strtotime($post['anniversary'])); 	
		}
		$data = array(     
					"patient_name"=>$post['patient_name'], 
					"simulation_id"=>$post['simulation_id'],
					//'patient_code'=>$post['patient_code'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'],
					"adhar_no"=>$post['adhar_no'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"age_h"=>$post['age_h'],
					'dob'=>date('Y-m-d', strtotime($post['dob'])),
					'anniversary'=>$anniversary,
					"city_id"=>$post['city_id'],
					"state_id"=>$post['state_id'],
					"country_id"=>$post['country_id'],
					"pincode"=>$post['pincode'],
					"marital_status"=>$post['marital_status'],
					"religion_id"=>$post['religion_id'],
					//'f_h_simulation'=>$post['f_h_simulation'],
					//"father_husband"=>$post['father_husband'],
					"mother"=>$post['mother'],
					"guardian_name"=>$post['guardian_name'],
					"guardian_email"=>$post['guardian_email'],
					"guardian_phone"=>$post['guardian_phone'],
					"relation_id"=>$post['relation_id'],
					"patient_email"=>$post['patient_email'],
					"monthly_income"=>$post['monthly_income'],
					"occupation"=>$post['occupation'],
					'relation_type'=>$post['relation_type'],
					'relation_name'=>$post['relation_name'],
					'relation_simulation_id'=>$post['relation_simulation_id'],
					"insurance_type"=>$post['insurance_type'],
					"insurance_type_id"=>$insurance_type_id,
					"ins_company_id"=>$insurance_company,
					"polocy_no"=>$post['polocy_no'],
					"tpa_id"=>$post['tpa_id'],
					"ins_amount"=>$post['ins_amount'],
					"ins_authorization_no"=>$post['ins_authorization_no'], 
					"status"=>$post['status'],
					"remark"=>$post['remark'],
				    // 	Added By Nitin Sharma 04/02/2024
				    "capture_finger"=>$post['capture_finger'],
					"fingerprint_photo"=>$post['fingerprint_photo'],
				    // 	Added By Nitin Sharma 04/02/2024
					"created_date"=>date('Y-m-d H:i:s', strtotime($post['created_date'])) 
				         ); 
		if(empty($post['address']))
		{
           $data['address'] = "";
		}
		else
		{
			$data['address'] = $post['address'];
		}

		if(empty($post['address_second']))
		{
           $data['address2'] = "";
		}
		else
		{
			$data['address2'] = $post['address_second'];
		}
		if(empty($post['address_third']))
		{
           $data['address3'] = "";
		}
		else
		{
			$data['address3'] = $post['address_third'];
		}
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
				$this->db->set('photo',$filename);
			}
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_patient',$data); 
            
            $data_id = $post['data_id']; 
			$data = array(      
					"email"=>$post['patient_email'],  
				         ); 
			$this->db->where('parent_id',$data_id);
			$this->db->where('users_role','4');			 
			$this->db->update('hms_users',$data);	

			if(!empty($post['data_id']) && !empty($post['password']))
			{
				$this->db->set('password',md5($post['password']));
				$this->db->where('users_role',4);
				$this->db->where('parent_id',$post['data_id']);
				$this->db->update('hms_users');
			}
		}
		else
		{    
            if(!empty($filename))
			{
				$this->db->set('photo',$filename);
			}
			$reg_no = generate_unique_id(4);
			$this->db->set('patient_code',$reg_no);
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('branch_id',$user_data['parent_id']);
			//$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_patient',$data); 
			//echo $this->db->last_query();die;
			$data_id = $this->db->insert_id();   

			$data = array(     
					"users_role"=>4,
					"parent_id"=>$data_id,
					"username"=>'PAT000'.$data_id,
					"password"=>md5('PASS'.$data_id),
					"email"=>$post['patient_email'], 
					"status"=>'1',
					"ip_address"=>$_SERVER['REMOTE_ADDR'],
					"created_by"=>$user_data['id'],
					"created_date" =>date('Y-m-d H:i:s')
				         ); 
			$this->db->insert('hms_users',$data);	
            $users_id = $this->db->insert_id(); 

			/*$this->db->select('*');
			$this->db->where('users_role','4');
			$query = $this->db->get('hms_permission_to_role');		 
			$permission_list = $query->result();
			if(!empty($permission_list))
			{
				foreach($permission_list as $permission)
				{
					$data = array(
					        'users_role' =>4,
					        'users_id' => $users_id,
					        'master_id' => $data_id,
					        'section_id' => $permission->section_id,
					        'action_id' => $permission->action_id, 
					        'permission_status' => '1',
					        'ip_address' => $_SERVER['REMOTE_ADDR'],
					        'created_by' =>$user_data['id'],
					        'created_date' =>date('Y-m-d H:i:s'),
					     );
					$this->db->insert('hms_permission_to_users',$data);
				}
			} */            
		}
		return $data_id;  	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_patient');
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
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_patient');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        return $result; 
    } 


    function search_patient_data()
    {

		$search = $this->session->userdata('patient_search');
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_patient.id,hms_patient.branch_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.first_name,hms_patient.last_name,hms_patient.adhar_no,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.anniversary,hms_patient.age,hms_patient.age_y,hms_patient.age_d,hms_patient.age_m,hms_patient.age_h,hms_patient.marital_status,hms_patient.height,hms_patient.weight,hms_patient.religion_id,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_patient.other_city,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.f_h_simulation,hms_patient.father_husband,hms_patient.mother,hms_patient.guardian_name,hms_patient.guardian_email,hms_patient.guardian_phone,hms_patient.relation_id,hms_patient.camp_id,hms_patient.patient_email,hms_patient.monthly_income,hms_patient.occupation,hms_patient.photo,hms_patient.insurance_type,hms_patient.insurance_type_id,hms_patient.ins_company_id,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.remark,hms_patient.status,hms_patient.anni_sms_year,hms_patient.anni_email_send_year,hms_patient.birth_sms_year,hms_patient.birth_email_year,hms_patient.is_deleted,hms_patient.created_date, hms_cities.city, hms_state.state,hms_gardian_relation.relation,(CASE When hms_patient.relation_type=1 THEN 'Son/o' When hms_patient.relation_type=2 THEN 'Husband/o' When hms_patient.relation_type=3 THEN 'Baby/o' When hms_patient.relation_type=4 THEN 'Father/o' When hms_patient.relation_type=5 THEN 'Daughter/o' When hms_patient.relation_type=6 THEN 'Wife/o'When hms_patient.relation_type=14 THEN 'Brother' Else '' END) as patient_relation"); 
		$this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
		$this->db->where('hms_patient.is_deleted','0'); 
		//$this->db->where('hms_patient.branch_id',$users_data['parent_id']);
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_patient.branch_id',$search['branch_id']);
		}else{
		$this->db->where('hms_patient.branch_id',$users_data['parent_id']);
		}
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_patient.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']))." 23:59:59";
				$this->db->where('hms_patient.created_date <= "'.$end_date.'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('LOWER(hms_patient.patient_name) LIKE "%'.strtolower($search['patient_name']).'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
			if(!empty($search['relation_name']))
			{
				$this->db->where('hms_patient.relation_name LIKE "'.$search['relation_name'].'%"');
			}
			if(!empty($search['relation_type']))
			{
				$this->db->where('hms_patient.relation_type',$search['relation_type']);
			}
			

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}

			if(!empty($search['address']))
			{
				//$this->db->where('hms_patient.address LIKE "'.$search['address'].'%"');
                                //$this->db->where('hms_patient.address LIKE "'.$search['address'].'%"');
				//$this->db->or_where('hms_patient.address2 LIKE "'.$search['address'].'%"');
				//$this->db->or_where('hms_patient.address3 LIKE "'.$search['address'].'%"');
$this->db->where('CONCAT(hms_patient.address,hms_patient.address2,hms_patient.address3) like "%'.trim($search['address']).'%"');
			}

			if(!empty($search['country_id']))
			{
				$this->db->where('hms_patient.country_id',$search['country_id']);
			}

			if(!empty($search['state_id']))
			{
				$this->db->where('hms_patient.state_id',$search['state_id']);
			}

			if(!empty($search['city_id']))
			{
				$this->db->where('hms_patient.city_id',$search['city_id']);
			}

			if(!empty($search['pincode']))
			{
				$this->db->where('hms_patient.pincode LIKE "'.$search['pincode'].'%"');
			}

			if(isset($search['marital_status']) && $search['marital_status']!="")
			{
				$this->db->where('hms_patient.marital_status',$search['marital_status']);
			}

			if(!empty($search['religion_id']))
			{
				$this->db->where('hms_patient.religion_id',$search['religion_id']);
			}

			if(!empty($search['father_husband']))
			{
				$this->db->where('hms_patient.father_husband LIKE "'.$search['father_husband'].'%"');
			}

			if(!empty($search['mother']))
			{
				$this->db->where('hms_patient.mother LIKE "'.$search['mother'].'%"');
			}

			if(!empty($search['guardian_name']))
			{
				$this->db->where('hms_patient.guardian_name LIKE "'.$search['guardian_name'].'%"');
			}

			if(!empty($search['guardian_email']))
			{
				$this->db->where('hms_patient.guardian_email LIKE "'.$search['guardian_email'].'%"');
			}

			if(!empty($search['guardian_phone']))
			{
				$this->db->where('hms_patient.guardian_phone LIKE "'.$search['guardian_phone'].'%"');
			}

			if(!empty($search['relation_id']))
			{
				$this->db->where('hms_patient.relation_id',$search['relation_id']);
			}

			if(!empty($search['patient_email']))
			{
				$this->db->where('hms_patient.patient_email LIKE "'.$search['patient_email'].'%"');
			}

			if(!empty($search['monthly_income']))
			{
				$this->db->where('hms_patient.monthly_income', $search['monthly_income']);
			}

			if(!empty($search['occupation']))
			{
				$this->db->where('hms_patient.occupation LIKE "'.$search['occupation'].'%"');
			}

			if(isset($search['insurance_type']) && $search['insurance_type']!="")
			{
				$this->db->where('hms_patient.insurance_type',$search['insurance_type']);
			}

			if(isset($search['insurance_type_id']) && $search['insurance_type_id']!="")
			{
				$this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
			}

			if(isset($search['ins_company_id']) && $search['ins_company_id']!="")
			{
				$this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
			}

			if($search['polocy_no']!="")
			{
				$this->db->where('hms_patient.polocy_no',$search['polocy_no']);
			}

			if($search['tpa_id']!="")
			{
				$this->db->where('hms_patient.tpa_id',$search['tpa_id']);
			}

			if($search['ins_amount']!="")
			{
				$this->db->where('hms_patient.ins_amount',$search['ins_amount']);
			}

			if($search['ins_authorization_no']!="")
			{
				$this->db->where('hms_patient.ins_authorization_no',$search['ins_authorization_no']);
			}

			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('status',$search['status']);
			}

			if(isset($search['adhar_no']) && isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}
			if(!empty($search['relation_simulation_id']))
			{
				$this->db->where('hms_patient.relation_simulation_id ',$search['relation_simulation_id']);
			}


		}
		$this->db->order_by('hms_patient.id','desc');
	    $query = $this->db->get(); 
        //echo $this->db->last_query();die;
		$data= $query->result();
		
		return $data;
	}

/* consolidate history */
	public function self_opd_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id AND hms_opd_booking.is_deleted=0');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		      
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    //$this->db->where('hms_opd_booking.type',3);
		    $this->db->where('hms_payment.section_id IN (2)');
		    
		    $this->db->order_by('hms_payment.created_date','DESC');
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		    //echo $this->db->last_query(); exit;
		    return $query->result();
		} 
	}

	public function self_billing_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    $this->db->where('hms_opd_booking.type',3); //billing type in hms_opd_booking 3
		    $this->db->where('hms_opd_booking.is_deleted',0);
		    $this->db->where('hms_payment.section_id=4');  //billing section id 4
		    $this->db->order_by('hms_payment.created_date','DESC');
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		    //echo $this->db->last_query(); exit;
		    return $query->result();
		} 
	}

	 public function self_medicine_consolidate_history_list($get="")
	    {
	        $users_data = $this->session->userdata('auth_users'); 
	        if(!empty($get))
	        {  
	             $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
	            $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
	            $this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id');
	            $this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left');   
	            //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
	            if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }
	            $this->db->where('hms_payment.patient_id',$get['patient_id']);
	            $this->db->where('hms_payment.section_id IN (3)'); 
	            $this->db->order_by('hms_payment.created_date','DESC');
	            $this->db->from('hms_payment');
	            $query = $this->db->get();  
	             //echo $this->db->last_query();die;
	            return $query->result();
	        } 
	    }

	 public function self_medicine_return_collection_list($get="")
	    {
	        $users_data = $this->session->userdata('auth_users'); 
	        if(!empty($get))
	        {  
	             $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
	              $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
	             $this->db->join('hms_medicine_return','hms_medicine_return.id=hms_payment.parent_id');
	            //$this->db->join('hms_doctors','hms_doctors.id=hms_medicine_sale.refered_id','left'); 
	            $this->db->where('hms_payment.type','3') ;
	            //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);
	            if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }   
	            $this->db->where('hms_payment.patient_id',$get['patient_id']);
	            $this->db->where('hms_payment.section_id IN (3)'); 
	            $this->db->from('hms_payment');
	            $query = $this->db->get()->result();  
	          // print_r($query);die;
	            return $query;
	        } 
	    }

  		
  		public function self_ipd_consolidate_history_list($get="")
		{
			$users_data = $this->session->userdata('auth_users'); 
			if(!empty($get))
			{  
			     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
			    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
			    $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id');
			    $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');   
			    //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
			    if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }
			    $this->db->where('hms_payment.patient_id',$get['patient_id']);
			    $this->db->where('hms_payment.section_id IN (5)');  //billing section id 4
			    $this->db->order_by('hms_payment.created_date','DESC');
			    $this->db->from('hms_payment');
			    $query = $this->db->get();  
			    return $query->result();
			} 
		}
		public function self_pathology_consolidate_history_list($get="")
		{
			$users_data = $this->session->userdata('auth_users'); 
			if(!empty($get))
			{  
			     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
			    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
			    $this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id');
			    $this->db->join('hms_doctors','hms_doctors.id=path_test_booking.attended_doctor','left');   
			    //$this->db->where('hms_payment.branch_id',$users_data['parent_id']);   
			    if(!empty($get['branch_id']))
			    {
			    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
			    }
			    else
			    {
			    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
			    }
			    $this->db->where('hms_payment.patient_id',$get['patient_id']);
			    $this->db->where('hms_payment.section_id IN (1)');  //billing section id 4
			    $this->db->order_by('hms_payment.created_date','DESC');
			    $this->db->from('hms_payment');
			    $query = $this->db->get();  
			    return $query->result();
			} 
		}
		
  /* consolidate history */
public function template_list($data="")
  {
		$this->db->select("hms_doctor_certificate.*"); 
		$this->db->from('hms_doctor_certificate'); 
		$this->db->where($data);
		$query = $this->db->get(); 
		return $query->row_array();
  }


	function get_patient_history_datatables($patient_id="",$type="",$branch_id='')
    {
    	
	    $this->_get_patient_history_datatables_query($patient_id,$type,$branch_id);
	    if($_POST['length'] != -1)
	    $this->db->limit($_POST['length'], $_POST['start']);
	    /*if(!empty($medicine_id) && !empty($batch_no))
	    {*/
	    $query = $this->db->get(); 
	    //echo $this->db->last_query();die;
	    return $query->result();
	    /*}
	    else
	    {
	    	$result = array();
	    	return $result;
	    }*/
    }
    
    private function _get_patient_history_datatables_query($patient_id="0",$type="1",$branch_id='')
    {

		$users_data = $this->session->userdata('auth_users');
		if(!empty($type) && $type==1) //purchase
		{
			$this->db->select("hms_opd_booking.id,hms_opd_booking.booking_code as number,hms_opd_booking.booking_date as date,hms_doctors.doctor_name,hms_opd_booking.net_amount as amount,hms_opd_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left'); 
			$this->db->from('hms_opd_booking');  
			if(!empty($branch_id))
			{
				$this->db->where('hms_opd_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
			}
			
			$this->db->where('hms_opd_booking.patient_id',$patient_id);
			$this->db->where('hms_opd_booking.type',2);
			$this->db->where('hms_opd_booking.booking_type',0);
			
			$this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');			
		}
		if(!empty($type) && $type==2) //purchase return
		{
			$this->db->select("hms_opd_booking.id,hms_opd_booking.reciept_code as number,hms_opd_booking.booking_date as date,hms_opd_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name,hms_opd_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left'); 
			$this->db->from('hms_opd_booking');  
			if(!empty($branch_id))
			{
				$this->db->where('hms_opd_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
			}
			$this->db->where('hms_opd_booking.patient_id',$patient_id);
			$this->db->where('hms_opd_booking.type',3);
			
			$this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
		}
		if(!empty($type) && $type==3) //Sale purchase_id  patient_id 	
		{
			$this->db->select("hms_medicine_sale.sale_no as number,hms_medicine_sale.id,hms_medicine_sale.sale_date as date,hms_medicine_sale.net_amount as amount,hms_medicine_sale.branch_id"); 
			$this->db->from('hms_medicine_sale');   
			
			$this->db->join('hms_branch','hms_medicine_sale.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_medicine_sale.patient_id=hms_patient.id','left'); 
			if(!empty($branch_id))
			{
				$this->db->where('hms_medicine_sale.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
			}
			$this->db->where('hms_medicine_sale.patient_id',$patient_id);
			
			$this->db->where('hms_medicine_sale.is_deleted',0);
			$this->db->order_by('hms_medicine_sale.id','ASC');
		}

		if(!empty($type) && $type==4) //IPD  	
		{
			$this->db->select("hms_ipd_booking.id,hms_ipd_booking.ipd_no as number,hms_ipd_booking.admission_date as date,hms_ipd_booking.advance_payment as amount,hms_doctors.doctor_name as doctor_name,hms_ipd_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_ipd_booking.attend_doctor_id','left'); 
			$this->db->from('hms_ipd_booking');  
			if(!empty($branch_id))
			{
				$this->db->where('hms_ipd_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']);
			}
			
			$this->db->where('hms_ipd_booking.patient_id',$patient_id);
			$this->db->where('hms_ipd_booking.is_deleted',0);
			$this->db->order_by('hms_ipd_booking.id','ASC');
		}

		if(!empty($type) && $type==5) //pathology
		{
			$this->db->select('path_test_booking.id,path_test_booking.branch_id,path_test_booking.lab_reg_no as number, path_test_booking.booking_date as date,path_test_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =path_test_booking.attended_doctor','left');
	        $this->db->from('path_test_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('path_test_booking.patient_id',$patient_id);
	        }
	        $this->db->order_by('path_test_booking.id','desc');
	        
	        $this->db->where('path_test_booking.is_deleted',0);
			$this->db->order_by('path_test_booking.id','ASC');
		}

		if(!empty($type) && $type==6) //Ambulance
		{
			$this->db->select('hms_ambulance_booking.id,hms_ambulance_booking.branch_id,hms_ambulance_booking.booking_no as number, hms_ambulance_booking.booking_date as date,hms_ambulance_booking.net_amount as amount,hms_ambulance_driver.driver_name as doctor_name');  
	        
	        $this->db->join('hms_ambulance_driver','hms_ambulance_driver.id =hms_ambulance_booking.driver_id','left');
	        $this->db->from('hms_ambulance_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_ambulance_booking.patient_id',$patient_id);
	        }
	        $this->db->order_by('hms_ambulance_booking.id','desc');
	        
	        $this->db->where('hms_ambulance_booking.is_deleted',0);
			$this->db->order_by('hms_ambulance_booking.id','ASC');
		}
		if(!empty($type) && $type==7) //Dialysis
		{
			$this->db->select('hms_dialysis_booking.id,hms_dialysis_booking.branch_id,hms_dialysis_booking.booking_code as number, hms_dialysis_booking.dialysis_date as date,hms_dialysis_booking.remarks,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_dialysis_booking.referral_doctor','left');
	        $this->db->from('hms_dialysis_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_dialysis_booking.patient_id',$patient_id);
	        }
	        $this->db->order_by('hms_dialysis_booking.id','desc');
	        
	        $this->db->where('hms_dialysis_booking.is_deleted',0);
			$this->db->order_by('hms_dialysis_booking.id','ASC');
		}

		if(!empty($type) && $type==8) //Inventory
		{
			$this->db->select('hms_stock_issue_allotment.id,hms_stock_issue_allotment.branch_id,hms_stock_issue_allotment.issue_no as number, hms_stock_issue_allotment.issue_date as date,hms_stock_issue_allotment.total_amount as amount');  
	        $this->db->from('hms_stock_issue_allotment');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_stock_issue_allotment.user_type_id',$patient_id);
	        }
	        $this->db->order_by('hms_stock_issue_allotment.id','desc');
	        
	        $this->db->where('hms_stock_issue_allotment.is_deleted',0);
			$this->db->order_by('hms_stock_issue_allotment.id','ASC');
		}

		if(!empty($type) && $type==9) //Vaccination
		{
			$this->db->select('hms_vaccination_sale.id,hms_vaccination_sale.branch_id,hms_vaccination_sale.sale_no as number, hms_vaccination_sale.sale_date as date,hms_vaccination_sale.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_vaccination_sale.refered_id','left');
	        $this->db->from('hms_vaccination_sale');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_vaccination_sale.patient_id',$patient_id);
	        }
	        $this->db->order_by('hms_vaccination_sale.id','desc');
	        
	        $this->db->where('hms_vaccination_sale.is_deleted',0);
			$this->db->order_by('hms_vaccination_sale.id','ASC');
		}
		if(!empty($type) && $type==10) //O.T.
		{
			$this->db->select('hms_operation_booking.id,hms_operation_booking.branch_id,hms_operation_booking.booking_code as number, hms_operation_booking.operation_booking_date as date,hms_operation_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_operation_booking.referral_doctor','left');
	        $this->db->from('hms_operation_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_operation_booking.patient_id',$patient_id);
	        }
	        $this->db->order_by('hms_operation_booking.id','desc');
	        
	        $this->db->where('hms_operation_booking.is_deleted',0);
			$this->db->order_by('hms_operation_booking.id','ASC');
		}
		if(!empty($type) && $type==11) //EYE
		{
			$this->db->select('hms_opd_booking.id,hms_opd_booking.branch_id,hms_opd_booking.booking_code as number, hms_opd_booking.booking_date as date,hms_opd_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left');
	        $this->db->from('hms_opd_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_opd_booking.patient_id',$patient_id);
	        }
	        
	        $this->db->where('hms_opd_booking.booking_type',1);
	        $this->db->order_by('hms_opd_booking.id','desc');
	        
	        $this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
		}
		if(!empty($type) && $type==12) //pediatrician
		{
			$this->db->select('hms_opd_booking.id,hms_opd_booking.branch_id,hms_opd_booking.booking_code as number, hms_opd_booking.booking_date as date,hms_opd_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left');
	        $this->db->from('hms_opd_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_opd_booking.patient_id',$patient_id);
	        }
	          $this->db->where('hms_opd_booking.booking_type',2);
	        $this->db->order_by('hms_opd_booking.id','desc');
	        
	        $this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
		}
		if(!empty($type) && $type==13) //Dental
		{
			$this->db->select('hms_opd_booking.id,hms_opd_booking.branch_id,hms_opd_booking.booking_code as number, hms_opd_booking.booking_date as date,hms_opd_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left');
	        $this->db->from('hms_opd_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_opd_booking.patient_id',$patient_id);
	        }
	         $this->db->where('hms_opd_booking.booking_type',3);
	        $this->db->order_by('hms_opd_booking.id','desc');
	        
	        $this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
		}
		if(!empty($type) && $type==14) //gynecology
		{
			$this->db->select('hms_opd_booking.id,hms_opd_booking.branch_id,hms_opd_booking.booking_code as number, hms_opd_booking.booking_date as date,hms_opd_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left');
	        $this->db->from('hms_opd_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('hms_opd_booking.patient_id',$patient_id);
	        }
	         $this->db->where('hms_opd_booking.booking_type',4);
	        $this->db->order_by('hms_opd_booking.id','desc');
	        
	        $this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
		}
		
		if(!empty($type) && $type==15) //day care  	
		{
			$this->db->select("hms_day_care_booking.id,hms_day_care_booking.booking_code as number,hms_day_care_booking.booking_date as date,hms_day_care_booking.total_amount as amount,hms_doctors.doctor_name as doctor_name,hms_day_care_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_day_care_booking.consultant','left'); 
			$this->db->from('hms_day_care_booking');  
			if(!empty($branch_id))
			{
				$this->db->where('hms_day_care_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_day_care_booking.branch_id',$users_data['parent_id']);
			}
			
			$this->db->where('hms_day_care_booking.patient_id',$patient_id);
			$this->db->where('hms_day_care_booking.is_deleted',0);
			$this->db->order_by('hms_day_care_booking.id','ASC');
		}


    }
    private function _get_patient_history_datatables_query20200124($patient_id="0",$type="1",$branch_id='')
    {

		$users_data = $this->session->userdata('auth_users');
		if(!empty($type) && $type==1) //purchase
		{
				//echo $patient_id;die;
			$this->db->select("hms_opd_booking.id,hms_opd_booking.booking_code as number,hms_opd_booking.booking_date as date,hms_doctors.doctor_name,hms_opd_booking.net_amount as amount,hms_opd_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left'); 
			$this->db->from('hms_opd_booking');  
			if(!empty($branch_id))
			{
				$this->db->where('hms_opd_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
			}
			
			$this->db->where('hms_opd_booking.patient_id',$patient_id);
			$this->db->where('hms_opd_booking.type',2);
			
			$this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
			//echo $this->db->last_query(); 
			
		}
		if(!empty($type) && $type==2) //purchase return
		{
			$this->db->select("hms_opd_booking.id,hms_opd_booking.reciept_code as number,hms_opd_booking.booking_date as date,hms_opd_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name,hms_opd_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_opd_booking.attended_doctor','left'); 
			$this->db->from('hms_opd_booking');  
			//$this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
			if(!empty($branch_id))
			{
				$this->db->where('hms_opd_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
			}
			$this->db->where('hms_opd_booking.patient_id',$patient_id);
			$this->db->where('hms_opd_booking.type',3);
			
			$this->db->where('hms_opd_booking.is_deleted',0);
			$this->db->order_by('hms_opd_booking.id','ASC');
			//echo $this->db->last_query();
		}
		if(!empty($type) && $type==3) //Sale purchase_id  patient_id 	
		{
			$this->db->select("hms_medicine_sale.sale_no as number,hms_medicine_sale.id,hms_medicine_sale.sale_date as date,hms_medicine_sale.net_amount as amount,hms_medicine_sale.branch_id"); 
			$this->db->from('hms_medicine_sale');   
			
			$this->db->join('hms_branch','hms_medicine_sale.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_medicine_sale.patient_id=hms_patient.id','left'); 
			
			
			//$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			//$this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
			if(!empty($branch_id))
			{
				$this->db->where('hms_medicine_sale.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
			}
			$this->db->where('hms_medicine_sale.patient_id',$patient_id);
			
			$this->db->where('hms_medicine_sale.is_deleted',0);
			$this->db->order_by('hms_medicine_sale.id','ASC');
			//echo $this->db->last_query();
		}

		if(!empty($type) && $type==4) //IPD  	
		{
			$this->db->select("hms_ipd_booking.id,hms_ipd_booking.ipd_no as number,hms_ipd_booking.admission_date as date,hms_ipd_booking.advance_payment as amount,hms_doctors.doctor_name as doctor_name,hms_ipd_booking.branch_id");   
			$this->db->join('hms_doctors','hms_doctors.id =hms_ipd_booking.attend_doctor_id','left'); 
			$this->db->from('hms_ipd_booking');  
			if(!empty($branch_id))
			{
				$this->db->where('hms_ipd_booking.branch_id',$branch_id);
			}
			else
			{
				$this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']);
			}
			
			$this->db->where('hms_ipd_booking.patient_id',$patient_id);
			$this->db->where('hms_ipd_booking.is_deleted',0);
			$this->db->order_by('hms_ipd_booking.id','ASC');
			//echo $this->db->last_query();
		}

		if(!empty($type) && $type==5) //pathology
		{
			$this->db->select('path_test_booking.id,path_test_booking.branch_id,path_test_booking.lab_reg_no as number, path_test_booking.booking_date as date,path_test_booking.net_amount as amount,hms_doctors.doctor_name as doctor_name');  
	        
	        $this->db->join('hms_doctors','hms_doctors.id =path_test_booking.attended_doctor','left');
	        $this->db->from('path_test_booking');  
	        if(!empty($patient_id))
	        {
	        	$this->db->where('path_test_booking.patient_id',$patient_id);
	        }
	        $this->db->order_by('path_test_booking.id','desc');
	        
	        $this->db->where('path_test_booking.is_deleted',0);
			$this->db->order_by('path_test_booking.id','ASC');
	        //$result = $query->result(); 
	        //echo $this->db->last_query();
	        
		}

		/*if(!empty($type) && $type==4) //Sale return
		{
			$this->db->select("hms_medicine_sale_return_medicine.*,hms_branch.branch_name,hms_medicine_sale_return.patient_id,hms_medicine_sale_return.patient_id as purchase_order_id,hms_medicine_sale_return.created_date as purchase_date,hms_patient.patient_name as vendor_name,(select company_name from hms_medicine_company as cmpny where cmpny.id = hms_medicine_entry.manuf_company) as company_name,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code"); 
			$this->db->from('hms_medicine_sale_return_medicine');   
			
			$this->db->join('hms_medicine_sale_return','hms_medicine_sale_return.id = hms_medicine_sale_return_medicine.sales_return_id','left'); 
			$this->db->join('hms_branch','hms_medicine_sale_return.branch_id=hms_branch.id','left');
			
			$this->db->join('hms_patient','hms_medicine_sale_return.patient_id=hms_patient.id','left'); 
			
			$this->db->join('hms_medicine_entry','hms_medicine_sale_return_medicine.medicine_id=hms_medicine_entry.id','left');
			
			//$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->where('hms_medicine_sale_return.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_sale_return_medicine.medicine_id',$medicine_id);
			
			$this->db->where('hms_medicine_sale_return.is_deleted',0);
			$this->db->order_by('hms_medicine_sale_return.id','ASC');
		}
		if(!empty($type) && $type==5) //Branch allot
		{
			$this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_stock.debit as quantity,hms_medicine_stock.created_date,hms_medicine_stock.parent_id,hms_branch.branch_name');
			$this->db->from('hms_medicine_entry');
			$this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left');

			$this->db->join('hms_medicine_stock','hms_medicine_stock.m_id=hms_medicine_entry.id');

			$this->db->join('hms_branch','hms_medicine_stock.parent_id=hms_branch.id','left');
			//$this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']);
			$this->db->where('hms_medicine_stock.type',$type);
			$this->db->where('hms_medicine_stock.debit > 0');
			$this->db->where('hms_medicine_stock.m_id',$medicine_id);
			$this->db->order_by('hms_medicine_stock.id','ASC');
			//echo $this->db->last_query();
		}*/
    }


    function get_patient_history_count_filtered($patient_id="",$type='')
	{
		$this->_get_patient_history_datatables_query($patient_id,$type);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_patient_history_count_all($patient_id="",$type='')
	{
		$this->_get_patient_history_datatables_query($patient_id,$type);
		$query = $this->db->get();
		return $query->num_rows();

	}
	
	public function save_all_patient($patient_all_data = array())
	{
		

		//echo "<pre>";print_r($patient_all_data); exit;//$patient_data['relation_type']
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($patient_all_data))
        {
            foreach($patient_all_data as $patient_data)
            {
            	if(!empty($patient_data['patient_name']))
            	{
            		//check simulation start
            		$simulation_id='';
            		if(!empty($patient_data['simulation_id']))
            		{
		            	$this->db->select("hms_simulation.*");
					    $this->db->from('hms_simulation'); 
		                $this->db->where('LOWER(hms_simulation.simulation)',strtolower($patient_data['simulation_id'])); 
					    $this->db->where('hms_simulation.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $simulation_data = $query->result_array();

					    if(!empty($simulation_data))
					    {
						    $simulation_id = $simulation_data[0]['id'];
					    }
					    else
					    {
							$simulation_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'simulation'=>$patient_data['simulation_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_simulation',$simulation_insert_data);
							$simulation_id = $this->db->insert_id();
					    }
					}
					//check simulation end
					//check relation type start
					$relation_type='';
            		if(!empty($patient_data['relation_type']) && $patient_data['relation_type']!='')
            		{
		            	$this->db->select("hms_gardian_relation.*");
					    $this->db->from('hms_gardian_relation'); 
		                $this->db->where('LOWER(hms_gardian_relation.relation)',strtolower($patient_data['relation_type'])); 
					    $this->db->where('hms_gardian_relation.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $relation_data = $query->result_array();

					    if(!empty($relation_data))
					    {
						    $relation_type = $relation_data[0]['id'];
					    }
					    else
					    {
							$garrelation_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'relation'=>$patient_data['relation_type'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_gardian_relation',$garrelation_insert_data);
							$relation_type = $this->db->insert_id();
					    }
					}
					//check relation type end

					//relation simulation start
					$relation_simulation_id='';
            		if(!empty($patient_data['relation_simulation_id']))
            		{
		            	$this->db->select("hms_simulation.*");
					    $this->db->from('hms_simulation'); 
		                $this->db->where('LOWER(hms_simulation.simulation)',strtolower($patient_data['relation_simulation_id'])); 
					    $this->db->where('hms_simulation.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $simulation_data = $query->result_array();

					    if(!empty($simulation_data))
					    {
						    $relation_simulation_id = $simulation_data[0]['id'];
					    }
					    else
					    {
							$simulation_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'simulation'=>$patient_data['simulation_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_simulation',$simulation_insert_data);
							$relation_simulation_id = $this->db->insert_id();
					    }
					}

					//relation simulation end
					//gender
					$gender='';
					if(strtolower($patient_data['gender'])=='male')
					{
						$gender = '1';
					}
					elseif(strtolower($patient_data['gender'])=='female')
					{
						$gender = '0';
					}
					elseif(strtolower($patient_data['gender'])=='others')
					{
						$gender ='2';
					}
					else
					{
						$gender = $patient_data['gender'];
					}
					//gender
					//echo $patient_data['dob'];
					//DOB start
					
				if($patient_data['dob']!='' || $patient_data['dob']!='00-00-0000')
				{
					$date  =  $patient_data['dob'];
					$day   = $this->getAge($date,3);//date('d',$date);
					$month = $this->getAge($date,2);//date('m',$date);
					$year  = $this->getAge($date,1);//date('Y',$date);
				}
				if(empty($year) && !empty($patient_data['age_y']))
				{
					$year = $patient_data['age_y'];
				}
					
				if(empty($month) && !empty($patient_data['age_m']))
				{
					$month = $patient_data['age_m'];
				}
				if(empty($day) &&  !empty($patient_data['age_d']))
				{
					$day = $patient_data['age_d'];
				}
					
					//DOB End

					//country start
					$country='';
            		if(!empty($patient_data['country']))
            		{
		            	$this->db->select("hms_countries.*");
					    $this->db->from('hms_countries'); 
		                $this->db->where('LOWER(hms_countries.country)',strtolower($patient_data['country'])); 
					      
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $country_data = $query->result_array();

					    if(!empty($country_data))
					    {
						    $country = $country_data[0]['id'];
					    }
					    else
					    {
						 	$country_insert_data = array(
							'code'=>'',
							'country'=>$patient_data['country'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_countries',$country_insert_data);
							$country = $this->db->insert_id();
					    }
					}
					//country end
					//state start
					$state='';
            		if(!empty($patient_data['state']) && !empty($country))
            		{
		            	$this->db->select("hms_state.*");
					    $this->db->from('hms_state'); 
		                $this->db->where('LOWER(hms_state.state)',strtolower($patient_data['state']));
		                $this->db->where('country_id',$country); 
					      
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $state_data = $query->result_array();

					    if(!empty($state_data))
					    {
						    $state = $state_data[0]['id'];
					    }
					    else
					    {
						 	$state_insert_data = array(
							'country_id'=>$country,
							'state'=>$patient_data['state'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'modified_date'=>date('Y-m-d H:i:s'),
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_state',$state_insert_data);
							$state = $this->db->insert_id();
					    }
					}

					//state end

					//city start
					$city='';
            		if(!empty($patient_data['city']) && !empty($country) && !empty($state))
            		{
		            	$this->db->select("hms_cities.*");
					    $this->db->from('hms_cities'); 
		                $this->db->where('LOWER(hms_cities.city)',strtolower($patient_data['city']));
		                $this->db->where('country_id',$country);
		                $this->db->where('state_id',$state); 
					      
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $city_data = $query->result_array();

					    if(!empty($city_data))
					    {
						    $city = $city_data[0]['id'];
					    }
					    else
					    {
						 	$city_insert_data = array(
							'country_id'=>$country,
							'state_id'=>$state,
							'city'=>$patient_data['city'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'modified_date'=>date('Y-m-d H:i:s'),
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_cities',$city_insert_data);
							$city = $this->db->insert_id();
					    }
					}

					//city end
					$marital_status='0';
					if($patient_data['marital_status']=='0')
					{
					  $marital_status ='0';	
					}
					elseif($patient_data['marital_status']=='1')
					{
					  $marital_status ='1';	
					}
					elseif($patient_data['marital_status']=='Unmarried')
					{
					  $marital_status ='0';	
					}
					elseif($patient_data['marital_status']=='Married')
					{
					  $marital_status ='1';	
					}


					//religion id
					$religion_id='';
            		if(!empty($patient_data['religion_id']))
            		{
		            	$this->db->select("hms_religion.*");
					    $this->db->from('hms_religion'); 
		                $this->db->where('LOWER(hms_religion.religion)',strtolower($patient_data['religion_id'])); 
					    $this->db->where('hms_religion.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $religion_data = $query->result_array();

					    if(!empty($religion_data))
					    {
						    $religion_id = $religion_data[0]['id'];
					    }
					    else
					    {
							$religion_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'religion'=>$patient_data['religion_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_religion',$religion_insert_data);
							$religion_id = $this->db->insert_id();
					    }
					}
					//religion id end

					//relation id
					$relation_id='';
            		if(!empty($patient_data['relation_id']))
            		{
		            	$this->db->select("hms_relation.*");
					    $this->db->from('hms_relation'); 
		                $this->db->where('LOWER(hms_relation.relation)',strtolower($patient_data['relation_id'])); 
					    $this->db->where('hms_relation.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $relation_data = $query->result_array();

					    if(!empty($relation_data))
					    {
						    $relation_id = $relation_data[0]['id'];
					    }
					    else
					    {
							$relation_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'relation'=>$patient_data['relation_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_relation',$relation_insert_data);
							$relation_id = $this->db->insert_id();
					    }
					}
					//relation id end

					//insurance type id
					$insurance_type_id='';
            		if(!empty($patient_data['insurance_type_id']))
            		{
		            	$this->db->select("hms_insurance_type.*");
					    $this->db->from('hms_insurance_type'); 
		                $this->db->where('LOWER(hms_insurance_type.insurance_type)',strtolower($patient_data['insurance_type_id'])); 
					    $this->db->where('hms_insurance_type.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $insurance_type_data = $query->result_array();

					    if(!empty($insurance_type_data))
					    {
						    $insurance_type_id = $insurance_type_data[0]['id'];
					    }
					    else
					    {
							$insurance_type_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'insurance_type'=>$patient_data['insurance_type_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_insurance_type',$insurance_type_insert_data);
							$insurance_type_id = $this->db->insert_id();
					    }
					}
					//insurance type id end

					//insurance company
					$insurance_company='';
            		if(!empty($patient_data['ins_company_id']))
            		{
		            	$this->db->select("hms_insurance_company.*");
					    $this->db->from('hms_insurance_company'); 
		                $this->db->where('LOWER(hms_insurance_company.insurance_company)',strtolower($patient_data['ins_company_id'])); 
					    $this->db->where('hms_insurance_company.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $insurance_company_data = $query->result_array();

					    if(!empty($insurance_company_data))
					    {
						    $insurance_company = $insurance_company_data[0]['id'];
					    }
					    else
					    {
							$insurance_company_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'insurance_company'=>$patient_data['ins_company_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_insurance_company',$insurance_company_insert_data);
							$insurance_company = $this->db->insert_id();
					    }
					}
					//insurance company
$patient_code = generate_unique_id(4);
				$patient_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'patient_code'=>$patient_code,
	                    'simulation_id'=>$simulation_id,
	                    'patient_name'=>$patient_data['patient_name'],
	                    'relation_type'=>$relation_type,
	                    'relation_simulation_id'=>$relation_simulation_id,
	                    'relation_name'=>$patient_data['relation_name'],
	                    'mobile_no'=>$patient_data['mobile_no'],
	                    'gender'=>$gender,
	                    "adhar_no"=>$patient_data['adhar_no'], 
						"age_y"=>$year,
						"age_m"=>$month,
						"age_d"=>$day,
						"age_h"=>$patient_data['age_h'],
						'dob'=>date('Y-m-d', strtotime($patient_data['dob'])),
						'address'=>$patient_data['address'],
						'address2'=>$patient_data['address2'],
						'address3'=>$patient_data['address3'],
						'adhar_no'=>$patient_data['adhar_no'],
						'country_id'=>$country,
						'state_id'=>$state,
						'city_id'=>$city,
						'pincode'=>$patient_data['pincode'],
						'marital_status'=>$marital_status,
						'anniversary'=>date('Y-m-d', strtotime($patient_data['anniversary'])),
						'religion_id'=>$religion_id,
						'mother'=>$patient_data['mother'],
						'guardian_name'=>$patient_data['guardian_name'],
						'guardian_email'=>$patient_data['guardian_email'],
						'guardian_phone'=>$patient_data['guardian_phone'],
						'relation_id'=>$relation_id,
						'patient_email'=>$patient_data['patient_email'],
						'monthly_income'=>$patient_data['monthly_income'],
						'occupation'=>$patient_data['occupation'],
						'insurance_type'=>$patient_data['insurance_type'],
						'insurance_type_id'=>$insurance_type_id,
						'ins_company_id'=>$insurance_company,
						'polocy_no'=>$patient_data['polocy_no'],
						'tpa_id'=>$patient_data['tpa_id'],
						'ins_amount'=>$patient_data['ins_amount'],
						'ins_authorization_no'=>$patient_data['ins_authorization_no'],

	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );
				    $this->db->insert('hms_patient',$patient_data_array);
				    
				    $data_id = $this->db->insert_id();   

					/*$data = array(     
							"users_role"=>4,
							"parent_id"=>$data_id,
							"username"=>'PAT000'.$data_id,
							"password"=>md5('PASS'.$data_id),
							"email"=>$patient_data['patient_email'], 
							"status"=>'1',
							"ip_address"=>$_SERVER['REMOTE_ADDR'],
							"created_by"=>$users_data['id'],
							"created_date" =>date('Y-m-d H:i:s')
						         ); 
					$this->db->insert('hms_users',$data);	
		            $users_id = $this->db->insert_id();*/ 

					/*$this->db->select('*');
					$this->db->where('users_role','4');
					$query = $this->db->get('hms_permission_to_role');		 
					$permission_list = $query->result();
					if(!empty($permission_list))
					{
						foreach($permission_list as $permission)
						{
							$data = array(
							        'users_role' =>4,
							        'users_id' => $users_id,
							        'master_id' => $data_id,
							        'section_id' => $permission->section_id,
							        'action_id' => $permission->action_id, 
							        'permission_status' => '1',
							        'ip_address' => $_SERVER['REMOTE_ADDR'],
							        'created_by' =>$users_data['id'],
							        'created_date' =>date('Y-m-d H:i:s'),
							     );
							$this->db->insert('hms_permission_to_users',$data);
						}
					}*/
	                //echo $this->db->last_query(); exit;
	            }
            }   	
        }
	}
	
	function getAge($date,$module='')
	{   //$date = date('Y-m-d',$date);
		$dob = new DateTime($date);
		$now = new DateTime();
		if($module==1)
		{
		return $now->diff($dob)->y;
		}
		elseif($module==2)
		{
		return $now->diff($dob)->m;	
		}
		elseif($module==3)
		{
		return $now->diff($dob)->d;
		}
	}
	
	
	
/* consolidate history */


	public function self_ambulance_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		 $this->db->select('hms_patient.patient_name,hms_patient.patient_code,hms_ambulance_booking.id,hms_ambulance_booking.branch_id,hms_ambulance_booking.created_date,hms_ambulance_booking.paid_amount');  
	        $this->db->join('hms_patient','hms_patient.id=hms_ambulance_booking.patient_id','left');
	        $this->db->from('hms_ambulance_booking'); 
	        if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_ambulance_booking.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']); 
		    }
	       
	        $this->db->where('hms_ambulance_booking.patient_id',$get['patient_id']);
	       
	        $this->db->order_by('hms_ambulance_booking.id','desc');
	        
	        $this->db->where('hms_ambulance_booking.is_deleted',0);
			$query = $this->db->get();
			//echo $this->db->last_query();die(); 
		   
		    return $query->result();
	}
	public function self_ot_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.patient_name,hms_patient.patient_code,hms_operation_booking.created_date,hms_operation_booking.paid_amount');  
	        $this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
	        $this->db->from('hms_operation_booking');
	        if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_operation_booking.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']); 
		    }	        
	        $this->db->where('hms_operation_booking.patient_id',$get['patient_id']);
	       
	        $this->db->order_by('hms_operation_booking.id','desc');
	        
	        $this->db->where('hms_operation_booking.is_deleted',0);
			$query = $this->db->get(); 
		   //echo $this->db->last_query();die(); 
		    return $query->result();
	}
	public function self_dialysis_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.patient_name,hms_patient.patient_code,hms_dialysis_booking.id,hms_dialysis_booking.branch_id,hms_dialysis_booking.booking_code as number, hms_dialysis_booking.created_date');  
	        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
	        $this->db->from('hms_dialysis_booking');  
	        if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_dialysis_booking.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_dialysis_booking.branch_id',$users_data['parent_id']); 
		    }
	        $this->db->where('hms_dialysis_booking.patient_id',$get['patient_id']);
	        
	        $this->db->order_by('hms_dialysis_booking.id','desc');
	        
	        $this->db->where('hms_dialysis_booking.is_deleted',0);
			$query = $this->db->get(); 
		   
		    return $query->result();

	}
	public function self_inventory_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
			$this->db->select('hms_patient.patient_name,hms_patient.patient_code, hms_stock_issue_allotment.created_date,hms_stock_issue_allotment.paid_amount');  
			$this->db->join('hms_patient','hms_patient.id=hms_stock_issue_allotment.user_type_id','left');
	        $this->db->from('hms_stock_issue_allotment');  

	        if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_stock_issue_allotment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_stock_issue_allotment.branch_id',$users_data['parent_id']); 
		    }
	        $this->db->where('hms_stock_issue_allotment.user_type_id',$get['patient_id']);
	       
	        $this->db->order_by('hms_stock_issue_allotment.id','desc');
	        
	        $this->db->where('hms_stock_issue_allotment.is_deleted',0);
			$query = $this->db->get(); 
		  // echo $this->db->last_query();die(); 
		    return $query->result();

	}
	public function self_vacci_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.patient_name,hms_patient.patient_code, hms_vaccination_sale.created_date,hms_vaccination_sale.paid_amount');  
	        $this->db->join('hms_patient','hms_patient.id=hms_vaccination_sale.patient_id','left');
	        $this->db->from('hms_vaccination_sale');  
	        if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_vaccination_sale.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']); 
		    }
	        $this->db->where('hms_vaccination_sale.patient_id',$get['patient_id']);	       
	        $this->db->order_by('hms_vaccination_sale.id','desc');
	        
	        $this->db->where('hms_vaccination_sale.is_deleted',0);
			$query = $this->db->get(); 		
			// echo $this->db->last_query();die();    
		    return $query->result();

	}




// consolidate collection add op

	public function self_eye_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		      
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    $this->db->where('hms_opd_booking.booking_type',1);
		    $this->db->where('hms_opd_booking.type',2);
		    $this->db->where('hms_payment.section_id IN (2)'); 
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		   
		    return $query->result();
		} 
	}


public function self_pedit_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		      
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    $this->db->where('hms_opd_booking.booking_type',2);
		    $this->db->where('hms_opd_booking.type',2);
		    $this->db->where('hms_payment.section_id IN (2)'); 
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		   
		    return $query->result();
		} 
	}

public function self_dental_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		      
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    $this->db->where('hms_opd_booking.booking_type',3);
		    $this->db->where('hms_opd_booking.type',2);
		    $this->db->where('hms_payment.section_id IN (2)'); 
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		   
		    return $query->result();
		} 
	}

public function self_gyni_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		    $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.attended_doctor','left');   
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		      
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    $this->db->where('hms_opd_booking.booking_type',4);
		    $this->db->where('hms_opd_booking.type',2);
		    $this->db->where('hms_payment.section_id IN (2)'); 
		    $this->db->from('hms_payment');
		    $query = $this->db->get(); 
		   
		    return $query->result();
		} 
	}
	
	public function self_day_care_consolidate_history_list($get="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		if(!empty($get))
		{  
		     $this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_payment.paid_amount as amount,hms_doctors.doctor_name, hms_payment.debit, hms_payment.created_date, hms_payment.pay_mode"); 
		    $this->db->join('hms_patient','hms_patient.id=hms_payment.patient_id','left'); 
		    $this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id');
		    $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.consultant','left');   
		       
		    if(!empty($get['branch_id']))
		    {
		    	$this->db->where('hms_payment.branch_id',$get['branch_id']); 
		    }
		    else
		    {
		    	$this->db->where('hms_payment.branch_id',$users_data['parent_id']); 
		    }
		    $this->db->where('hms_payment.patient_id',$get['patient_id']);
		    $this->db->where('hms_payment.section_id IN (14)');  
		    $this->db->from('hms_payment');
		    $query = $this->db->get();  
		    //echo $this->db->last_query(); exit;
		    return $query->result();
		} 
	}
	
	public function get_patient_by_id($id)
	{ 
		$this->db->select("hms_patient.patient_name,hms_patient.patient_code,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.gender,hms_patient.dob,hms_patient.marital_status,hms_patient.address,hms_patient.pincode,hms_patient.patient_email,hms_patient.adhar_no,hms_patient.guardian_name,hms_patient.guardian_email,hms_patient.guardian_phone,hms_patient.father_husband,hms_patient.mother,hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation,hms_sim.simulation as rel_simulation, hms_religion.religion, hms_relation.relation as gardian_relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation,hms_patient.modified_date as patient_modified_date,hms_simulation.simulation as f_simulation,hms_religion.religion as religion_name, hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.monthly_income,hms_patient.occupation,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.mobile_no,hms_patient.created_date,hms_patient_category.patient_category as patient_category_name"); 
		$this->db->from('hms_patient'); 
		$this->db->where('hms_patient.id',$id);
		// $this->db->where('hms_patient.is_deleted','0'); 
		$this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4','left');
		$this->db->join('hms_religion','hms_religion.id=hms_patient.religion_id','left');
		$this->db->join('hms_relation','hms_relation.id=hms_patient.relation_id','left');
		$this->db->join('hms_countries','hms_countries.id=hms_patient.country_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_simulation','hms_simulation.id=hms_patient.simulation_id','left');

        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
         $this->db->join('hms_simulation as hms_sim','hms_sim.id=hms_patient.relation_simulation_id','left');
        $this->db->join('hms_insurance_type','hms_insurance_type.id=hms_patient.insurance_type_id','left');
        $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id','left');
        
        $this->db->join('hms_patient_category','hms_patient_category.id = hms_patient.patient_category','left'); 
        
         
		$query = $this->db->get(); 
		$result  = $query->row_array();
		//echo $this->db->last_query(); exit;
		return $result;
	}
	
	function template_format($branch_id='')
	{
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_card_print_setting.*');
    	$this->db->where('hms_card_print_setting.branch_id = "'.$branch_id.'"');
    	$this->db->from('hms_card_print_setting');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query(); exit;
    	return $query;

    }
    
    // Added By Nitin Sharma 04/02/2024
    public function get_patient_by_branch_id_finger($branch_id)
	{ 
		// $this->db->select("hms_patient.id,hms_patient.capture_finger,hms_patient.patient_name,hms_patient.patient_code,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_patient.gender,hms_patient.dob,hms_patient.marital_status,hms_patient.address,hms_patient.pincode,hms_patient.patient_email,hms_patient.adhar_no,hms_patient.guardian_name,hms_patient.guardian_email,hms_patient.guardian_phone,hms_patient.father_husband,hms_patient.mother,hms_cities.city, hms_state.state, hms_countries.country, hms_simulation.simulation,hms_sim.simulation as rel_simulation, hms_religion.religion, hms_relation.relation as gardian_relation, hms_insurance_type.insurance_type as insurance_types, hms_insurance_company.insurance_company, hms_users.username,hms_gardian_relation.relation,hms_patient.modified_date as patient_modified_date,hms_simulation.simulation as f_simulation,hms_religion.religion as religion_name, hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.monthly_income,hms_patient.occupation,hms_patient.polocy_no,hms_patient.tpa_id,hms_patient.ins_amount,hms_patient.ins_authorization_no,hms_patient.mobile_no,hms_patient.created_date,hms_patient_category.patient_category as patient_category_name"); 
		// $this->db->from('hms_patient'); 
		// $this->db->where('hms_patient.branch_id',$branch_id);
        //       $this->db->where('hms_patient.capture_finger !=', '');
		// $this->db->join('hms_patient_category','hms_patient_category.id = hms_patient.patient_category','left');
		// $this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4','left');
		// $this->db->join('hms_religion','hms_religion.id=hms_patient.religion_id','left');
		// $this->db->join('hms_relation','hms_relation.id=hms_patient.relation_id','left');
		// $this->db->join('hms_countries','hms_countries.id=hms_patient.country_id','left');
		// $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
      //       $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
      //       $this->db->join('hms_simulation','hms_simulation.id=hms_patient.simulation_id','left');

      //       $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'left'); 
      //        $this->db->join('hms_simulation as hms_sim','hms_sim.id=hms_patient.relation_simulation_id','left');
      //       $this->db->join('hms_insurance_type','hms_insurance_type.id=hms_patient.insurance_type_id','left');
      //       $this->db->join('hms_insurance_company','hms_insurance_company.id=hms_patient.ins_company_id','left');
		$this->db->select("hms_patient.id,hms_patient.capture_finger,"); 
		$this->db->from('hms_patient'); 
		$this->db->where('hms_patient.branch_id',$branch_id);
        $this->db->where('hms_patient.capture_finger !=', '');
		$query = $this->db->get(); 
		if ($query->num_rows() > 0) {
          return $query->result();
        } else {
          return [];
        }
	}
	// Added By Nitin Sharma 04/02/2024
} 
?>