<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prescription_model extends CI_Model 
{
	var $table = 'hms_std_eye_prescription';
	var $column = array('hms_std_eye_prescription.id','hms_std_eye_prescription.booking_code','hms_std_eye_prescription.status', 'hms_std_eye_prescription.created_date', 'hms_std_eye_prescription.modified_date','hms_patient.patient_name','hms_patient.patient_code','hms_patient.mobile_no');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('prescription_search');

		$this->db->select("hms_std_eye_prescription.*,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_opd_booking.dilate_status,hms_opd_booking.app_type"); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_std_eye_prescription.booking_id');
		$this->db->join('hms_patient','hms_patient.id=hms_std_eye_prescription.patient_id','left');
       
		$this->db->where('hms_std_eye_prescription.is_deleted','0'); 
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_std_eye_prescription.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_std_eye_prescription.branch_id = "'.$user_data['parent_id'].'"');
		}

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_std_eye_prescription.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_std_eye_prescription.created_date <= "'.$end_date.'"');
			}

			
			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
		}
		$this->db->where('hms_std_eye_prescription.id !=1');
		$emp_ids='';
		if($user_data['emp_id']>0)
		{
			if($user_data['record_access']=='1')
			{
				$emp_ids= $user_data['id'];
			}
		}
		elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }
          

		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_std_eye_prescription.created_by IN ('.$emp_ids.')');
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
		 $this->db->group_by('hms_std_eye_prescription.id');
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
		//echo $this->db->last_query();die();
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

	public function get_by_id($id)
	{
		$this->db->select("hms_std_eye_prescription.*,hms_std_eye_prescription_patient_test.test_name,hms_std_eye_prescription_patient_pres.medicine_name,hms_std_eye_prescription_patient_pres.medicine_salt,hms_std_eye_prescription_patient_pres.medicine_brand,hms_std_eye_prescription_patient_pres.medicine_type,hms_std_eye_prescription_patient_pres.medicine_dose,hms_std_eye_prescription_patient_pres.medicine_duration,hms_std_eye_prescription_patient_pres.medicine_frequency,hms_std_eye_prescription_patient_pres.medicine_advice"); 
		$this->db->from('hms_std_eye_prescription'); 
		$this->db->where('hms_std_eye_prescription.id',$id);
		$this->db->where('hms_std_eye_prescription.is_deleted','0'); 
		$this->db->join('hms_std_eye_prescription_patient_test','hms_std_eye_prescription_patient_test.prescription_id=hms_std_eye_prescription.id','left');
		$this->db->join('hms_std_eye_prescription_patient_pres','hms_std_eye_prescription_patient_pres.prescription_id=hms_std_eye_prescription.id','left');
		
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function get_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_std_eye_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob"); 
		$this->db->from('hms_std_eye_prescription'); 
		$this->db->where('hms_std_eye_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_std_eye_prescription.patient_id','left');
		$result_pre['prescription_list']= $this->db->get()->result();
		
		$this->db->select('hms_std_eye_prescription_patient_test.*');
		$this->db->join('hms_std_eye_prescription','hms_std_eye_prescription.id = hms_std_eye_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_std_eye_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_std_eye_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_std_eye_prescription_patient_pres.*');
		$this->db->join('hms_std_eye_prescription','hms_std_eye_prescription.id = hms_std_eye_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_std_eye_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_std_eye_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_std_eye_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_std_eye_prescription.patient_bp as patientbp,hms_std_eye_prescription.patient_temp as patienttemp,hms_std_eye_prescription.patient_weight as patientweight,hms_std_eye_prescription.patient_height as patientpr,hms_std_eye_prescription.patient_spo2 as patientspo,hms_std_eye_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 



		$this->db->from('hms_std_eye_prescription'); 
		$this->db->where('hms_std_eye_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_std_eye_prescription.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_std_eye_prescription.booking_id','left'); 
		$result_pre['prescription_list']= $this->db->get()->result();
		
		$this->db->select('hms_std_eye_prescription_patient_test.*');
		$this->db->join('hms_std_eye_prescription','hms_std_eye_prescription.id = hms_std_eye_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_std_eye_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_std_eye_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_std_eye_prescription_patient_pres.*');
		$this->db->join('hms_std_eye_prescription','hms_std_eye_prescription.id = hms_std_eye_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_std_eye_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_std_eye_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.patient_bp as patientbp,hms_opd_booking.patient_temp as patienttemp,hms_opd_booking.patient_weight as patientweight,hms_opd_booking.patient_height as patientpr,hms_opd_booking.patient_spo2 as patientspo,hms_opd_booking.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation');
		$this->db->from('hms_opd_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		if(!empty($branch_id))
		{
			$this->db->where('hms_opd_booking.branch_id',$branch_id); 
		}
		else
		{
			$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 	
		}
		
		$this->db->where('hms_opd_booking.id',$id);
		$this->db->where('hms_opd_booking.is_deleted','0');
		 
		$result_pre['prescription_list']= $this->db->get()->result();
		return $result_pre;
	}


     public function get_by_ids($id)
	{
		$this->db->select("hms_std_eye_prescription.*,hms_opd_booking.booking_code,hms_opd_booking.booking_time,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.created_date,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.patient_code,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.gender,hms_doctors.doctor_name as ref_doctor_name, hms_doctors.mobile_no as ref_mobile_no, hms_doctors.address, hms_doctors.address2, hms_doctors.address3,hms_patient.address as paddress,hms_patient.address2 as paddress1,hms_patient.address3 as paddress2");
		$this->db->from('hms_std_eye_prescription'); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_std_eye_prescription.booking_id','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_opd_booking.referral_doctor','left');
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->where('hms_std_eye_prescription.id',$id);
		$this->db->where('hms_std_eye_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}
	 

	public function get_patient_pres_history($pateint_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_std_eye_prescription.*,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d"); 
		$this->db->from('hms_std_eye_prescription'); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_std_eye_prescription.booking_id');
		$this->db->join('hms_patient','hms_patient.id=hms_std_eye_prescription.patient_id','left');
		$this->db->where('hms_std_eye_prescription.is_deleted','0'); 
		$this->db->where('hms_std_eye_prescription.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('hms_std_eye_prescription.patient_id',$pateint_id);		
		 $this->db->group_by('hms_std_eye_prescription.id');	
	     $this->db->order_by('hms_std_eye_prescription.id','DESC');
	     $query = $this->db->get();
         return $query->result();
	}

    
} 
?>