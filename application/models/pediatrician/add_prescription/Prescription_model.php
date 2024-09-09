<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prescription_model extends CI_Model 
{
	var $table = 'hms_pediatrician_prescription';
	var $column = array('hms_pediatrician_prescription.id','hms_pediatrician_prescription.booking_code','hms_pediatrician_prescription.patient_name', 'hms_pediatrician_prescription.mobile_no','hms_pediatrician_prescription.patient_code', 'hms_pediatrician_prescription.status', 'hms_pediatrician_prescription.created_date', 'hms_pediatrician_prescription.modified_date');  
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
		$this->db->select("hms_pediatrician_prescription.*,hms_pediatrician_prescription_patient_test.test_name,hms_pediatrician_prescription_patient_pres.medicine_name,hms_pediatrician_prescription_patient_pres.medicine_salt,hms_pediatrician_prescription_patient_pres.medicine_brand,hms_pediatrician_prescription_patient_pres.medicine_type,hms_pediatrician_prescription_patient_pres.medicine_dose,hms_pediatrician_prescription_patient_pres.medicine_duration,hms_pediatrician_prescription_patient_pres.medicine_frequency,hms_pediatrician_prescription_patient_pres.medicine_advice"); 
		$this->db->join('hms_pediatrician_prescription_patient_test','hms_pediatrician_prescription_patient_test.prescription_id=hms_pediatrician_prescription.id','left');
        $this->db->join('hms_pediatrician_prescription_patient_pres','hms_pediatrician_prescription_patient_pres.prescription_id=hms_pediatrician_prescription.id','left');
		
		$this->db->where('hms_pediatrician_prescription.is_deleted','0'); 
		//$this->db->where('hms_pediatrician_prescription.branch_id = "'.$user_data['parent_id'].'"'); 

		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_pediatrician_prescription.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_pediatrician_prescription.branch_id = "'.$user_data['parent_id'].'"');
		}

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_pediatrician_prescription.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_pediatrician_prescription.created_date <= "'.$end_date.'"');
			}

			
			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_pediatrician_prescription.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_pediatrician_prescription.patient_code',$search['patient_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_pediatrician_prescription.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			
			


		}

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
			$this->db->where('hms_pediatrician_prescription.created_by IN ('.$emp_ids.')');
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
		 $this->db->group_by('hms_pediatrician_prescription.id');
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

	public function get_by_id($id)
	{
		$this->db->select("hms_pediatrician_prescription.*,hms_pediatrician_prescription_patient_test.test_name,hms_pediatrician_prescription_patient_pres.medicine_name,hms_pediatrician_prescription_patient_pres.medicine_salt,hms_pediatrician_prescription_patient_pres.medicine_brand,hms_pediatrician_prescription_patient_pres.medicine_type,hms_pediatrician_prescription_patient_pres.medicine_dose,hms_pediatrician_prescription_patient_pres.medicine_duration,hms_pediatrician_prescription_patient_pres.medicine_frequency,hms_pediatrician_prescription_patient_pres.medicine_advice"); 
		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->where('hms_pediatrician_prescription.id',$id);
		$this->db->where('hms_pediatrician_prescription.is_deleted','0'); 
		$this->db->join('hms_pediatrician_prescription_patient_test','hms_pediatrician_prescription_patient_test.prescription_id=hms_pediatrician_prescription.id','left');
		$this->db->join('hms_pediatrician_prescription_patient_pres','hms_pediatrician_prescription_patient_pres.prescription_id=hms_pediatrician_prescription.id','left');
		
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function get_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_pediatrician_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob,hms_patient.adhar_no"); 
		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->where('hms_pediatrician_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_pediatrician_prescription.patient_id','left');
		//$this->db->join('hms_pediatrician_prescription_patient_test','hms_pediatrician_prescription_patient_test.prescription_id=hms_pediatrician_prescription.id','left');
		//$this->db->join('hms_pediatrician_prescription_patient_pres','hms_pediatrician_prescription_patient_pres.prescription_id=hms_pediatrician_prescription.id','left');
		
		//$query = $this->db->get(); 
		$result_pre['prescription_list']= $this->db->get()->result();
		
		$this->db->select('hms_pediatrician_prescription_patient_test.*');
		$this->db->join('hms_pediatrician_prescription','hms_pediatrician_prescription.id = hms_pediatrician_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_pediatrician_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_pediatrician_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_pediatrician_prescription_patient_pres.*');
		$this->db->join('hms_pediatrician_prescription','hms_pediatrician_prescription.id = hms_pediatrician_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_pediatrician_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_pediatrician_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();
       
		return $result_pre;

	}


	public function get_detail_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_pediatrician_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.adhar_no,hms_pediatrician_booking.booking_code,hms_pediatrician_booking.booking_date,hms_pediatrician_booking.specialization_id,hms_pediatrician_booking.referral_doctor,hms_pediatrician_booking.attended_doctor,hms_pediatrician_booking.booking_date,hms_pediatrician_booking.booking_time,hms_pediatrician_prescription.patient_bp as patientbp,hms_pediatrician_prescription.patient_temp as patienttemp,hms_pediatrician_prescription.patient_weight as patientweight,hms_pediatrician_prescription.patient_height as patientpr,hms_pediatrician_prescription.patient_spo2 as patientspo,hms_pediatrician_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 



		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->where('hms_pediatrician_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_pediatrician_prescription.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_pediatrician_booking','hms_pediatrician_booking.id=hms_pediatrician_prescription.booking_id','left'); 
		$result_pre['prescription_list']= $this->db->get()->result();
		
		$this->db->select('hms_pediatrician_prescription_patient_test.*');
		$this->db->join('hms_pediatrician_prescription','hms_pediatrician_prescription.id = hms_pediatrician_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_pediatrician_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_pediatrician_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_pediatrician_prescription_patient_pres.*');
		$this->db->join('hms_pediatrician_prescription','hms_pediatrician_prescription.id = hms_pediatrician_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_pediatrician_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_pediatrician_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_pediatrician_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_pediatrician_booking.booking_code,hms_pediatrician_booking.booking_date,hms_pediatrician_booking.specialization_id,hms_pediatrician_booking.referral_doctor,hms_pediatrician_booking.attended_doctor,hms_pediatrician_booking.booking_date,hms_pediatrician_booking.booking_time,hms_pediatrician_booking.patient_bp as patientbp,hms_pediatrician_booking.patient_temp as patienttemp,hms_pediatrician_booking.patient_weight as patientweight,hms_pediatrician_booking.patient_height as patientpr,hms_pediatrician_booking.patient_spo2 as patientspo,hms_pediatrician_booking.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation');
		$this->db->from('hms_pediatrician_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_pediatrician_booking.patient_id');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		if(!empty($branch_id))
		{
			$this->db->where('hms_pediatrician_booking.branch_id',$branch_id); 
		}
		else
		{
			$this->db->where('hms_pediatrician_booking.branch_id',$user_data['parent_id']); 	
		}
		
		$this->db->where('hms_pediatrician_booking.id',$id);
		$this->db->where('hms_pediatrician_booking.is_deleted','0');
		 
		$result_pre['prescription_list']= $this->db->get()->result();
		//echo "<pre>";print_r($result_pre); exit;
		return $result_pre;
	}


        public function get_by_ids($id)
	{
		$this->db->select("hms_pediatrician_prescription.*,hms_pediatrician_booking.booking_code,hms_pediatrician_booking.booking_time,hms_pediatrician_booking.booking_date,hms_pediatrician_booking.specialization_id,hms_pediatrician_booking.referral_doctor,hms_pediatrician_booking.attended_doctor,hms_pediatrician_booking.booking_date,hms_pediatrician_booking.booking_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation");  //,hms_pediatrician_prescription_patient_test.*,hms_pediatrician_prescription_patient_pres.*
		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->join('hms_pediatrician_booking','hms_pediatrician_booking.id=hms_pediatrician_prescription.booking_id','left');
		$this->db->join('hms_patient','hms_patient.id = hms_pediatrician_booking.patient_id'); 
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->where('hms_pediatrician_prescription.id',$id);
		$this->db->where('hms_pediatrician_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}

	public function get_by_ids20171212($id)
	{
		$this->db->select("hms_pediatrician_prescription.*");  //,hms_pediatrician_prescription_patient_test.*,hms_pediatrician_prescription_patient_pres.*
		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->where('hms_pediatrician_prescription.id',$id);
		$this->db->where('hms_pediatrician_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}

	public function get_prescription_by_ids($id)
	{
		$this->db->select("hms_pediatrician_prescription.*");  //,hms_pediatrician_prescription_patient_test.*,hms_pediatrician_prescription_patient_pres.*patient_id
		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->where('hms_pediatrician_prescription.id',$id);
		$this->db->where('hms_pediatrician_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}
  	
	function get_pediatrician_prescription($prescription_id='')
	{
		$this->db->select("hms_pediatrician_prescription_patient_pres.*"); 
		$this->db->from('hms_pediatrician_prescription_patient_pres'); 
		$this->db->where('hms_pediatrician_prescription_patient_pres.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_pediatrician_test($prescription_id='')
	{
		$this->db->select("hms_pediatrician_prescription_patient_test.*"); 
		$this->db->from('hms_pediatrician_prescription_patient_test'); 
		$this->db->where('hms_pediatrician_prescription_patient_test.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
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
			$this->db->update('hms_pediatrician_prescription');
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
			$this->db->update('hms_pediatrician_prescription');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        return $result; 
    }



	public function get_prescription_files($prescription_id='')
	{
		if(!empty($prescription_id) && $prescription_id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('*');
			$this->db->where('is_deleted',0);
			$this->db->where('status',1);
			$this->db->where('prescription_id',$prescription_id);
			$query = $this->db->get('hms_prescription_files'); 
			$result = $query->result(); 
			//echo $this->db->last_query(); exit;
			return $result;
			
    	} 
	}
	function template_format($data="",$branch_id='')
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_branch_prescription_setting.*');
    	$this->db->where($data);
    	if(!empty($branch_id))
    	{
    		$this->db->where('hms_branch_prescription_setting.branch_id = "'.$branch_id.'"');
    	}
    	else
    	{
    		$this->db->where('hms_branch_prescription_setting.branch_id = "'.$users_data['parent_id'].'"');
    	}
    	 
    	$this->db->from('hms_branch_prescription_setting');
    	$result=$this->db->get()->row();
    	return $result;

    }

     public function get_test_vals_old($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('test_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('test_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_test_name');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->test_name;
	        	}
	        }
	        return $response; 
    	} 
    }
    
    public function get_test_vals($vals="")
    {
		$vals = urldecode($vals);
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('test_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('test_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('path_test');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	       /* if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response['test_id'] = $vals->id;
	               $response['test_name'] = $vals->test_name;
	        	}
	        }
	        return $response; */

	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->test_name.'|'.$vals->id;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }
    	} 
    }
        public function get_medicine_auto_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
	        $this->db->where('hms_medicine_entry.status','1'); 
	        $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
	        $this->db->where('hms_medicine_entry.is_deleted',0);
	        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$vals.'%"');
	        $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);  
	        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
	        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
	        $query = $this->db->get('hms_medicine_entry');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->medicine_name.'|'.$vals->medicine_unit.'|'.$vals->company_name.'|'.$vals->salt.'|'.$vals->id;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }
	        
	        //return $response; 
    	} 
    }

    public function get_dosage_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('dosage','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('dosage LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_dosage');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->dosage;
	        	}
	        }
	        return $response; 
    	} 
    }
  	public function get_type_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_type');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_type;
	        	}
	        }
	        return $response; 
    	} 
    }
    public function get_duration_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_duration','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_duration LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_dosage_duration');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage_duration;
	        	}
	        }
	        return $response; 
    	} 
    }

    public function get_frequency_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_frequency','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_frequency LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_dosage_frequency');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage_frequency;
	        	}
	        }
	        return $response; 
    	} 
    }
	public function template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_pediatrician_prescription_template');
        $result = $query->result(); 
        return $result; 
    }
    	 
	public function template_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_pediatrician_prescription_template.*');  
		$this->db->where('hms_pediatrician_prescription_template.id',$template_id);  
		$this->db->where('hms_pediatrician_prescription_template.is_deleted',0); 
		$this->db->where('hms_pediatrician_prescription_template.branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_pediatrician_prescription_template');
		$result = $query->row(); 
		return json_encode($result);
		 
    }
	public function get_template_test_data($template_id="")
   	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_pediatrician_prescription_patient_test_template.*');  
		$this->db->where('hms_pediatrician_prescription_patient_test_template.template_id',$template_id);  
		$query = $this->db->get('hms_pediatrician_prescription_patient_test_template');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }
    public function get_template_prescription_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_pediatrician_prescription_patient_pres_template.*');  
		$this->db->where('hms_pediatrician_prescription_patient_pres_template.template_id',$template_id);  
		$query = $this->db->get('hms_pediatrician_prescription_patient_pres_template');
		$result = $query->result(); 
		return json_encode($result);
		 
    }
     public function compalaints_list($compaints_id="")
   {
		$chief_complaints = explode(',', $compaints_id);
		$users_data = $this->session->userdata('auth_users'); 
		$compalaintsname="";
		$i=1;
		$total = count($chief_complaints);
		foreach ($chief_complaints as $value) 
		{
			$this->db->select('hms_pediatrician_chief_complaints.chief_complaints');  
			$this->db->where('hms_pediatrician_chief_complaints.id',$value);  
			$this->db->where('hms_pediatrician_chief_complaints.is_deleted',0);  
			$this->db->where('hms_pediatrician_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_chief_complaints.id');  
			$query = $this->db->get('hms_pediatrician_chief_complaints');
			$result = $query->row(); 

			$compalaintsname .= $result->chief_complaints;
			if($i!=$total)
			{
				$compalaintsname .=',';
			}
		
		$i++;
			
		}
		return $compalaintsname;
		 
    }
    public function examination_list($examination_id="")
   {
		$examination_ids = explode(',', $examination_id);
		$users_data = $this->session->userdata('auth_users'); 
		$examinationname="";
		$i=1;
		$total = count($examination_ids);
		foreach ($examination_ids as $value) 
		{
			$this->db->select('hms_pediatrician_examination.examination');  
			$this->db->where('hms_pediatrician_examination.id',$value);  
			$this->db->where('hms_pediatrician_examination.is_deleted',0);  
			$this->db->where('hms_pediatrician_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_examination.id');  
			$query = $this->db->get('hms_pediatrician_examination');
			$result = $query->row(); 

			$examinationname .= $result->examination;
			if($i!=$total)
			{
				$examinationname .=',';
			}
		
		$i++;
			
		}
		return $examinationname;
		 
    }
    public function examinations_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0'); 
		if(!empty($type)) {
            $this->db->where('hms_pediatrician_examination.examination LIKE "'.$type.'%"');
        }   
        $query = $this->db->get('hms_pediatrician_examination');
        $result = $query->result(); 
        return $result; 
    }

    public function diagnosis_name($diagnosis_id="")
   {
		$diagnosis_ids = explode(',', $diagnosis_id);
		$users_data = $this->session->userdata('auth_users'); 
		$diagnosisname="";
		$i=1;
		$total = count($diagnosis_ids);
		foreach ($diagnosis_ids as $value) 
		{
			$this->db->select('hms_pediatrician_diagnosis.diagnosis');  
			$this->db->where('hms_pediatrician_diagnosis.id',$value);  
			$this->db->where('hms_pediatrician_diagnosis.is_deleted',0);  
			$this->db->where('hms_pediatrician_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_diagnosis.id');  
			$query = $this->db->get('hms_pediatrician_diagnosis');
			$result = $query->row(); 

			$diagnosisname .= $result->diagnosis;
			if($i!=$total)
			{
				$diagnosisname .=',';
			}
		
		$i++;
			
		}
		return $diagnosisname;
		 
    }


    public function diagnosis_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');  
		if(!empty($type)) {
            $this->db->where('hms_pediatrician_diagnosis.diagnosis LIKE "'.$type.'%"');
        }  
        $query = $this->db->get('hms_pediatrician_diagnosis');
        $result = $query->result(); 
        return $result; 
    }

    public function suggetion_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');  
		if(!empty($type)) {
            $this->db->where('hms_pediatrician_suggetion.medicine_suggetion LIKE "'.$type.'%"');
        }  
        $query = $this->db->get('hms_pediatrician_suggetion');
        $result = $query->result(); 
        return $result; 
    }

   public function suggetion_name($suggetion_id="")
   {
		$suggetion_ids = explode(',', $suggetion_id);
		$users_data = $this->session->userdata('auth_users'); 
		$suggetionname="";
		$i=1;
		$total = count($suggetion_ids);
		foreach ($suggetion_ids as $value) 
		{
			$this->db->select('hms_pediatrician_suggetion.medicine_suggetion');  
			$this->db->where('hms_pediatrician_suggetion.id',$value);  
			$this->db->where('hms_pediatrician_suggetion.is_deleted',0);  
			$this->db->where('hms_pediatrician_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_suggetion.id');  
			$query = $this->db->get('hms_pediatrician_suggetion');
			$result = $query->row(); 

			$suggetionname .= $result->medicine_suggetion;
			if($i!=$total)
			{
				$suggetionname .=',';
			}
		
		$i++;
			
		}
		return $suggetionname;
		 
    }


    public function prv_history_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
		if(!empty($type)) {
            $this->db->where('hms_pediatrician_prv_history.prv_history LIKE "'.$type.'%"');
        } 
        $query = $this->db->get('hms_pediatrician_prv_history');
        $result = $query->result(); 
        return $result; 
    }

   public function prv_history_name($prv_id="")
   {
		$prv_ids = explode(',', $prv_id);
		$users_data = $this->session->userdata('auth_users'); 
		$prvname="";
		$i=1;
		$total = count($prv_ids);
		foreach ($prv_ids as $value) 
		{
			$this->db->select('hms_pediatrician_prv_history.prv_history');  
			$this->db->where('hms_pediatrician_prv_history.id',$value);  
			$this->db->where('hms_pediatrician_prv_history.is_deleted',0);  
			$this->db->where('hms_pediatrician_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_prv_history.id');  
			$query = $this->db->get('hms_pediatrician_prv_history');
			$result = $query->row(); 

			$prvname .= $result->prv_history;
			if($i!=$total)
			{
				$prvname .=',';
			}
		
		$i++;
			
		}
		return $prvname;
		 
    }

    public function personal_history_list($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
		if(!empty($type)) {
            $this->db->where('hms_pediatrician_personal_history.personal_history LIKE "'.$type.'%"');
        } 
        $query = $this->db->get('hms_pediatrician_personal_history');
        $result = $query->result(); 
        return $result; 
    }
    

    

   public function personal_history_name($personal_history_id="")
   {
		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_pediatrician_personal_history.personal_history');  
			$this->db->where('hms_pediatrician_personal_history.id',$value);  
			$this->db->where('hms_pediatrician_personal_history.is_deleted',0);  
			$this->db->where('hms_pediatrician_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_personal_history.id');  
			$query = $this->db->get('hms_pediatrician_personal_history');
			$result = $query->row(); 

			$personalname .= $result->personal_history;
			if($i!=$total)
			{
				$personalname .=',';
			}
		
		$i++;
			
		}
		return $personalname;
		 
    }
      public function get_advice_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_advice','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_advice LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_advice');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_advice;
	        	}
	        }
	        return $response; 
    	} 
    }
    public function save_prescription()
    {
    	//echo "<pre>";print_r($_POST); exit;
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$prv_history="";
		if(!empty($post['prv_history']))
		{
			$prv_history = $post['prv_history'];
		}
		$personal_history="";
		if(!empty($post['personal_history']))
		{
			$personal_history = $post['personal_history'];
		}
		$data_patient = array(
							"patient_name"=>$post['patient_name'],
							"mobile_no"=>$post['mobile_no'],
							"gender"=>$post['gender'], 
							"age_y"=>$post['age_y'],
							"age_m"=>$post['age_m'],
							"age_d"=>$post['age_d'],
							'adhar_no'=>$post['aadhaar_no'],
							'relation_type'=>$post['relation_type'],
							'relation_name'=>$post['relation_name'],
							'relation_simulation_id'=>$post['relation_simulation_id'],
							);

		if(!empty($post['patient_id']) && $post['patient_id']>0)
	    {
	    	$patient_id = $post['patient_id'];
	    	$this->db->where('id',$post['patient_id']);
    	    $this->db->update('hms_patient',$data_patient);
	    } 
	    else
	    {
	    	$patient_code = generate_unique_id(4);
	    	$this->db->set('patient_code',$patient_code);
	    	$this->db->insert('hms_patient',$data_patient);  
	    	$patient_id = $this->db->insert_id();
	    	//echo $this->db->last_query(); exit; 
	    }

	    if(!empty($post['data_id']) && $post['data_id']>0)
		{
			$prescriptionid=$post['data_id'];
			/* edit comment here */


		if($post['next_appointment_date']!='00-00-0000 00:00:00' && $post['next_appointment_date']!='01-01-1970')
		{
			$next_appointment_date = date('Y-m-d H:i:s',strtotime($post['next_appointment_date']));
		}	
		else
		{
			$next_appointment_date = ''; 
		}
		
		
 		$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					/*"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"patient_rbs"=>$post['patient_rbs'],*/
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>$next_appointment_date,
					"appointment_date"=>$post['appointment_date'],
					"date_time_new" =>$post['date_time_new'],
					"next_reason"=>$post['next_reason'],
					"status"=>1
					); 
		    
            
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_pediatrician_prescription',$data); 


			$data_opd = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					'flag'=>4,
					'flag_id'=>$post['data_id'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					/*"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"patient_rbs"=>$post['patient_rbs'],*/
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					//"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					//"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>$next_appointment_date,
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('flag_id',$post['data_id']);
			$this->db->update('hms_opd_prescription',$data_opd);




			//echo $this->db->last_query(); exit;
			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_pediatrician_prescription_patient_test'); 

			//$total_test = count($post['test_name']);
			$total_test = count(array_filter($post['test_name'])); 
			//foreach ($post['test_name'] as $value) 
			//{
			for($i=0;$i<$total_test;$i++)
			{
					

                              //check and add masters of test 
					//check and add masters of test 
					if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
					{	
						$this->db->select('path_test.*');  
						$this->db->where('path_test.test_name',$post['test_name'][$i]);  
						$this->db->where('path_test.is_deleted!=2'); 
						$this->db->where('path_test.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('path_test');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
										$path_test_data = $query->result_array();
										if(!empty($path_test_data))
									    {
										    $test_id = $path_test_data[0]['id'];
									    }
						}
						else
						{
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'test_name'=>$post['test_name'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('path_test',$data);
								$test_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					}
					else
					{
						$test_id = $post['test_id'][$i];
					}


					
                  $test_data = array(
						"prescription_id"=>$post['data_id'],
						"test_name"=>$post['test_name'][$i],
						"test_id"=>$test_id);
					$this->db->insert('hms_pediatrician_prescription_patient_test',$test_data); 
					
					$test_data_id = $this->db->insert_id(); 
			} 

			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_pediatrician_prescription_patient_pres'); 

			//$total_prescription = count($post['medicine_name']);
			$total_prescription = count(array_filter($post['medicine_name']));  
			for($i=0;$i<$total_prescription;$i++)
			{	
					$medicine_name ="";
					if(!empty($post['medicine_name'][$i]))
					{
						$medicine_name = $post['medicine_name'][$i];
					}


					$medicine_salt ="";
					if(!empty($post['medicine_salt'][$i]))
					{
						$medicine_salt = $post['medicine_salt'][$i];
					}

					$medicine_brand ="";
					if(!empty($post['medicine_brand'][$i]))
					{
						$medicine_brand = $post['medicine_brand'][$i];
					}

					$medicine_type ="";
					if(!empty($post['medicine_type'][$i]))
					{
						$medicine_type = $post['medicine_type'][$i];
					}
					$medicine_dose ="";
					if(!empty($post['medicine_dose'][$i]))
					{
						$medicine_dose = $post['medicine_dose'][$i];
					}
					$medicine_duration ="";
					if(!empty($post['medicine_duration'][$i]))
					{
						$medicine_duration = $post['medicine_duration'][$i];
					}

					$medicine_frequency ="";
					if(!empty($post['medicine_frequency'][$i]))
					{
						$medicine_frequency = $post['medicine_frequency'][$i];
					}
					$medicine_advice ="";
					if(!empty($post['medicine_advice'][$i]))
					{
						$medicine_advice = $post['medicine_advice'][$i];
					}
					
                                       //check and add masters 
					if(!empty($post['medicine_name'][$i]))
					{	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_opd_medicine');
						$this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
						$this->db->where('hms_medicine_entry.is_deleted=0'); 
						$this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_entry');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$company_data = $query->result_array();
							if(!empty($company_data))
						    {
							    $medicine_id = $company_data[0]['id'];
						    }
						}
						else
						{		
								$unit_id ='';
								//check medicine type
								if(!empty($post['medicine_type'][$i]))
								{
									 
									$this->db->select('hms_medicine_unit.*');  
									$this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
									$this->db->where('hms_medicine_unit.is_deleted=0'); 
									$this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
									$query = $this->db->get('hms_medicine_unit');
									$num = $query->num_rows();
									//echo $this->db->last_query();
									//echo $num; exit;
									if($num>0)
									{
										$unit_data = $query->result_array();
										if(!empty($unit_data))
									    {
										    $unit_id = $unit_data[0]['id'];
									    }	
									}
									else
									{
										$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'test_name'=>$post['medicine_type'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

										$this->db->set('created_by',$user_data['id']);
										$this->db->set('created_date',date('Y-m-d H:i:s'));
										$this->db->insert('hms_medicine_unit',$data);
										$unit_id = $this->db->insert_id(); 
									}
								}

								////check medicine type end

								//check medicine company
								$company_id ='';
								if(!empty($post['brand'][$i]))
								{
									 
									$this->db->select('hms_medicine_company.*');  
									$this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
									$this->db->where('hms_medicine_company.is_deleted=0'); 
									$this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
									$query = $this->db->get('hms_medicine_company');
									$num = $query->num_rows();
									//echo $this->db->last_query();
									//echo $num; exit;
									if($num>0)
									{
										$company_data = $query->result_array();
										if(!empty($company_data))
									    {
										    $company_id = $company_data[0]['id'];
									    }	
									}
									else
									{
										$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'company_name'=>$post['brand'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

										$this->db->set('created_by',$user_data['id']);
										$this->db->set('created_date',date('Y-m-d H:i:s'));
										$this->db->insert('hms_medicine_company',$data);
										$company_id = $this->db->insert_id(); 
									}
								}


								//medicine company end
								$salt ='';
								if(!empty($post['salt'][$i]))
								{
									$salt = $post['salt'][$i];
								}
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$post['medicine_name'][$i],
												'type'=>$unit_id,
												'salt'=>$salt,
												'manuf_company'=>$company_id,
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_medicine_entry',$data);
								$medicine_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					}
					//medicne type
					if(!empty($post['medicine_type'][$i]))
					{
						$this->db->select('hms_medicine_unit.*');
						//$this->db->from('hms_opd_medicine_type');
						$this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]); 
						$this->db->where('hms_medicine_unit.is_deleted!=2'); 
						$this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_unit');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_type'=>$post['medicine_type'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
								         );

							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_medicine_unit',$data);
						}
					}
					//medicine dose
					if(!empty($post['medicine_dose'][$i]))
					{
						$this->db->select('hms_pediatrician_medicine_dosage.*');
						//$this->db->from('hms_opd_medicine_dosage');
						$this->db->where('hms_pediatrician_medicine_dosage.dosage',$post['medicine_dose'][$i]); 
						$this->db->where('hms_pediatrician_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_medicine_dosage');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'dosage'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_pediatrician_medicine_dosage',$data);
						}
					}

					//medicine duration
					if(!empty($post['medicine_duration'][$i]))
					{
						$this->db->select('hms_pediatrician_medicine_dosage_duration.*'); 
						//$this->db->from('hms_opd_medicine_dosage_duration');
						$this->db->where('hms_pediatrician_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
						$this->db->where('hms_pediatrician_medicine_dosage_duration.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_medicine_dosage_duration');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_pediatrician_medicine_dosage_duration',$data);
						}
					}

					//medicine frequency
					if(!empty($post['medicine_frequency'][$i]))
					{
						$this->db->select('hms_pediatrician_medicine_dosage_frequency.*');
						//$this->db->from('hms_opd_medicine_dosage_frequency');
						$this->db->where('hms_pediatrician_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
						$this->db->where('hms_pediatrician_medicine_dosage_frequency.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_medicine_dosage_frequency');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_pediatrician_medicine_dosage_frequency',$data);
						}
					}

					//medicine advice
					if(!empty($post['medicine_advice'][$i]))
					{
						$this->db->select('*');
						//$this->db->from('hms_opd_advice');
						$this->db->where('hms_pediatrician_advice.medicine_advice',$post['medicine_advice'][$i]); 
						$this->db->where('hms_pediatrician_advice.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_advice.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_advice');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_advice'=>$post['medicine_advice'][$i],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_pediatrician_advice',$data);
						}
					}

					if(!empty($post['medicine_id'][$i]))
					{
						$medicine_id = $post['medicine_id'][$i];
					}
					else
					{
						$medicine_id = $medicine_id;
					}

					$prescription_data = array(
						"prescription_id"=>$post['data_id'],
						'medicine_id'=>$medicine_id,
						"medicine_name"=>$medicine_name,
						"medicine_brand"=>$medicine_brand,
						"medicine_salt"=>$medicine_salt,
						"medicine_type"=>$medicine_type,
						"medicine_dose"=>$medicine_dose,
						"medicine_duration"=>$medicine_duration,
						"medicine_frequency"=>$medicine_frequency,
						"medicine_advice"=>$medicine_advice
						);
					$this->db->insert('hms_pediatrician_prescription_patient_pres',$prescription_data);
					$test_data_id = $this->db->insert_id(); 
					//echo $this->db->last_query();
				
			}
			//die ;

			if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00' && $post['next_appointment_date']!='30-11-0')
		    {
		       $booking_id = $post['booking_id'];
		       $this->load->model('opd/opd_model');
		       $opd_booking_data = $this->opd_model->get_by_id($booking_id);
		      	
	           if(!empty($opd_booking_data))
	           {
	              $booking_id = $opd_booking_data['id'];
	              $referral_doctor = $opd_booking_data['referral_doctor'];
	              $attended_doctor = $opd_booking_data['attended_doctor'];
	              $patient_id = $opd_booking_data['patient_id'];
	              $simulation_id = $opd_booking_data['simulation_id'];
	              $patient_code = $opd_booking_data['patient_code'];
	              $patient_name = $opd_booking_data['patient_name'];
	              $mobile_no = $opd_booking_data['mobile_no'];
	              $gender = $opd_booking_data['gender'];
	              $age_y = $opd_booking_data['age_y'];
	              $age_m = $opd_booking_data['age_m'];
	              $age_d = $opd_booking_data['age_d'];
	              $address = $opd_booking_data['address'];
	              $city_id = $opd_booking_data['city_id'];
	              $state_id = $opd_booking_data['state_id'];
	              $country_id = $opd_booking_data['country_id']; 
	           }



	           	$appointment_code = generate_unique_id(20);
				$timestamp = $post['next_appointment_date'];
				$splitTimeStamp = explode(" ",$timestamp);
				$booking_date = $splitTimeStamp[0];
				$booking_time = $splitTimeStamp[1];

	           $data_test = array(    
						'branch_id'=> $user_data['parent_id'],
						'parent_id'=>0,
						'type'=>1,
						'patient_id'=>$patient_id,
						'referral_doctor'=>$referral_doctor,
						'attended_doctor'=>$attended_doctor,
						'booking_status'=>'0', 
						'appointment_code'=>$appointment_code, 
						'appointment_date'=>date('Y-m-d',strtotime($booking_date)),
						'appointment_time'=>date('H:i:s',strtotime($booking_time)), 
						'created_by'=>$user_data['id'],
						'created_date'=>date('Y-m-d H:i:s')
				);

	          


	           //'sample_collected_by'=>$sample_collected_by,
			   //'staff_refrenace_id'=>$staff_refrenace_id,
	           //'booking_date'=>date('Y-m-d',strtotime($post['next_appointment_date']))
		    	/*$this->db->set('patient_id',$patient_id);
		    	$this->db->set('type',1);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));*/
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
	            
	            //echo $this->db->last_query(); exit;
		 	}



		 	if(!empty($post['data']))
			{  

                        $this->db->where('booking_id',$prescriptionid);
			$this->db->where('branch_id',$user_data['parent_id']);
              	        $this->db->delete('hms_branch_vitals');
  
				$current_date = date('Y-m-d H:i:s');
	            foreach($post['data'] as $key=>$val)
	            {
	            	
 	            	$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "type"=>2,
	                               "booking_id"=>$prescriptionid,
	                               "vitals_id"=>$key,
	                               "vitals_value"=>$val['name'],
	                               
	                              );
	              
	              $this->db->insert('hms_branch_vitals',$data);
	      		  $id = $this->db->insert_id();
	            } 
			}

			return $prescriptionid;

		

			/* edit comment here */
		}
		else
		{

			$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"prv_history"=>$prv_history,
					"personal_history"=>$personal_history,
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>date('Y-m-d H:i',strtotime($post['next_appointment_date'])),
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_pediatrician_prescription',$data);
			$data_id = $this->db->insert_id(); 
			
			$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					'flag'=>4,
					'flag_id'=>$data_id,
					
					"prv_history"=>$prv_history,
					"personal_history"=>$personal_history,
					//"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					//"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>date('Y-m-d H:i',strtotime($post['next_appointment_date'])),
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_prescription',$data); 


			
			$total_test = count(array_filter($post['test_name']));
			for($i=0;$i<$total_test;$i++)
			{
				
					//check and add masters of test 
					if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
					{	
						$this->db->select('path_test.*');  
						$this->db->where('path_test.test_name',$post['test_name'][$i]);  
						$this->db->where('path_test.is_deleted!=2'); 
						$this->db->where('path_test.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('path_test');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$path_test_data = $query->result_array();
							if(!empty($path_test_data))
						    {
							    $test_id = $path_test_data[0]['id'];
						    }
						}
						else
						{
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'test_name'=>$post['test_name'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('path_test',$data);
								$test_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					}
					else
					{
						$test_id = $post['test_id'][$i];
					}

					

				$test_data = array(
							"prescription_id"=>$data_id,
							"test_name"=>$post['test_name'][$i],
							"test_id"=>$test_id);
						$this->db->insert('hms_pediatrician_prescription_patient_test',$test_data); 
						$test_data_id = $this->db->insert_id(); 
			}

			$total_prescription = count(array_filter($post['medicine_name'])); 
			for($i=0;$i<$total_prescription;$i++)
			{	
					//echo $post['medicine_name'][$i];
					//check and add masters 
					if(!empty($post['medicine_name'][$i]))
					{	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_pediatrician_medicine');
						$this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
						$this->db->where('hms_medicine_entry.is_deleted=0'); 
						$this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_entry');
						$num = $query->num_rows();
						//echo $this->db->last_query();
						//echo $num; exit;
						if($num>0)
						{
							$company_data = $query->result_array();
							if(!empty($company_data))
						    {
							    $medicine_id = $company_data[0]['id'];
						    }
						}
						else
						{		
									$unit_id ='';
								//check medicine type
								if(!empty($post['medicine_type'][$i]))
								{
									 
									$this->db->select('hms_medicine_unit.*');  
									$this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
									$this->db->where('hms_medicine_unit.is_deleted=0'); 
									$this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
									$query = $this->db->get('hms_medicine_unit');
									$num = $query->num_rows();
									//echo $this->db->last_query();
									//echo $num; exit;
									if($num>0)
									{
										$unit_data = $query->result_array();
										if(!empty($unit_data))
									    {
										    $unit_id = $unit_data[0]['id'];
									    }	
									}
									else
									{
										$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'test_name'=>$post['medicine_type'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

										$this->db->set('created_by',$user_data['id']);
										$this->db->set('created_date',date('Y-m-d H:i:s'));
										$this->db->insert('hms_medicine_unit',$data);
										$unit_id = $this->db->insert_id(); 
									}
								}

								////check medicine type end

								//check medicine company

								if(!empty($post['brand'][$i]))
								{
									 
									$this->db->select('hms_medicine_company.*');  
									$this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
									$this->db->where('hms_medicine_company.is_deleted=0'); 
									$this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
									$query = $this->db->get('hms_medicine_company');
									$num = $query->num_rows();
									//echo $this->db->last_query();
									//echo $num; exit;
									if($num>0)
									{
										$company_data = $query->result_array();
										if(!empty($company_data))
									    {
										    $company_id = $company_data[0]['id'];
									    }
									}
									else
									{
										$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'company_name'=>$post['brand'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

										$this->db->set('created_by',$user_data['id']);
										$this->db->set('created_date',date('Y-m-d H:i:s'));
										$this->db->insert('hms_medicine_company',$data);
										$company_id = $this->db->insert_id(); 
									}
								}


								//medicine company end
								
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$post['medicine_name'][$i],
												'type'=>$unit_id,
												'salt'=>$post['salt'][$i],
												'manuf_company'=>$company_id,
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_medicine_entry',$data);
								$medicine_id = $this->db->insert_id(); 
								//echo $this->db->last_query(); exit;
						}

					}
					//medicne type
					if(!empty($post['medicine_type'][$i]))
					{
						$this->db->select('hms_medicine_unit.*');
						//$this->db->from('hms_pediatrician_medicine_type');
						$this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]); 
						$this->db->where('hms_medicine_unit.is_deleted!=2'); 
						$this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_medicine_unit');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_unit'=>$post['medicine_type'][$i],
												'status'=>1,
												'ip_address'=>$_SERVER['REMOTE_ADDR']
									         );

								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_medicine_unit',$data);
						}
					}
					//medicine dose
					if(!empty($post['medicine_dose'][$i]))
					{
						$this->db->select('hms_pediatrician_medicine_dosage.*');
						//$this->db->from('hms_pediatrician_medicine_dosage');
						$this->db->where('hms_pediatrician_medicine_dosage.dosage',$post['medicine_dose'][$i]); 
						$this->db->where('hms_pediatrician_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_medicine_dosage');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'dosage'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_pediatrician_medicine_dosage',$data);
						}
					}

					//medicine duration
					if(!empty($post['medicine_duration'][$i]))
					{
						$this->db->select('hms_pediatrician_medicine_dosage_duration.*'); 
						//$this->db->from('hms_pediatrician_medicine_dosage_duration');
						$this->db->where('hms_pediatrician_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
						$this->db->where('hms_pediatrician_medicine_dosage_duration.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_medicine_dosage_duration');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_pediatrician_medicine_dosage_duration',$data);
						}
					}

					//medicine frequency
					if(!empty($post['medicine_frequency'][$i]))
					{
						$this->db->select('hms_pediatrician_medicine_dosage_frequency.*');
						//$this->db->from('hms_pediatrician_medicine_dosage_frequency');
						$this->db->where('hms_pediatrician_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
						$this->db->where('hms_pediatrician_medicine_dosage_frequency.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_medicine_dosage_frequency');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_pediatrician_medicine_dosage_frequency',$data);
						}
					}

					//medicine advice
					if(!empty($post['medicine_advice'][$i]))
					{
						$this->db->select('*');
						//$this->db->from('hms_pediatrician_advice');
						$this->db->where('hms_pediatrician_advice.medicine_advice',$post['medicine_advice'][$i]); 
						$this->db->where('hms_pediatrician_advice.is_deleted!=2'); 
						$this->db->where('hms_pediatrician_advice.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_pediatrician_advice');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
							$data = array( 
										'branch_id'=>$user_data['parent_id'],
										'medicine_advice'=>$post['medicine_advice'][$i],
										'status'=>1,
										'ip_address'=>$_SERVER['REMOTE_ADDR']
								        );
							$this->db->set('created_by',$user_data['id']);
							$this->db->set('created_date',date('Y-m-d H:i:s'));
							$this->db->insert('hms_pediatrician_advice',$data);
						}
					}
					//medicne id 
					//echo $data_id; exit;
					if(!empty($post['medicine_id'][$i]))
					{
						$medicine_id = $post['medicine_id'][$i];
					}
					else
					{
						$medicine_id = $medicine_id;
					}
					if(!empty($post['salt'][$i]))
					{
						$salt=$post['salt'][$i];
					}
					else
					{
						$salt='';
					}
					if(!empty($post['brand'][$i]))
					{
						$brand=$post['brand'][$i];
					}
					else
					{
						$brand='';
					}
					$prescription_data = array(
						"prescription_id"=>$data_id,
						'medicine_id'=>$medicine_id,
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_salt"=>$salt,
						"medicine_brand"=>$brand,
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_pediatrician_prescription_patient_pres',$prescription_data);
					//echo $this->db->last_query(); 
					$test_data_id = $this->db->insert_id(); 
				
			}
			

			 
			

			if(!empty($post['next_appointment_date']))
		    {
		       $booking_id = $post['booking_id'];
		       $this->load->model('opd/opd_model'); 
		       $pediatrician_booking_data = $this->opd_model->get_by_id($booking_id);
		      	
	           if(!empty($pediatrician_booking_data))
	           {
	              $booking_id = $pediatrician_booking_data['id'];
	              $referral_doctor = $pediatrician_booking_data['referral_doctor'];
	              $attended_doctor = $pediatrician_booking_data['attended_doctor'];
	              $patient_id = $pediatrician_booking_data['patient_id'];
	              $simulation_id = $pediatrician_booking_data['simulation_id'];
	              $patient_code = $pediatrician_booking_data['patient_code'];
	              $patient_name = $pediatrician_booking_data['patient_name'];
	              $mobile_no = $pediatrician_booking_data['mobile_no'];
	              $gender = $pediatrician_booking_data['gender'];
	              $age_y = $pediatrician_booking_data['age_y'];
	              $age_m = $pediatrician_booking_data['age_m'];
	              $age_d = $pediatrician_booking_data['age_d'];
	              $address = $pediatrician_booking_data['address'];
	              $city_id = $pediatrician_booking_data['city_id'];
	              $state_id = $pediatrician_booking_data['state_id'];
	              $country_id = $pediatrician_booking_data['country_id']; 
	           }

	            $timestamp = $post['next_appointment_date'];
				$splitTimeStamp = explode(" ",$timestamp);
				$booking_date = $splitTimeStamp[0];
				$booking_time = $splitTimeStamp[1];

	           $appointment_code = generate_unique_id(20);

	           $data_test = array(    
						'type'=>1,
						'appointment_date'=>date('Y-m-d',strtotime($booking_date)),
						'appointment_time'=>date('H:i:s',strtotime($booking_time)), 
						'appointment_code'=>$appointment_code,
						'booking_status'=>'0', 
						'status'=>1,
						'confirm_date'=>'0000-00-00',
						'next_app_date'=>'0000-00-00',
						'source_from'=>0,
						'modified_by'=>0,
						'referral_doctor'=>$referral_doctor,
						'attended_doctor'=>$attended_doctor,
						"branch_id"=> $user_data['parent_id'],
						'booking_date'=>date('Y-m-d H:i:s'),
						'ip_address'=>$_SERVER['REMOTE_ADDR'],
						'is_deleted'=>0,
						'deleted_by'=>0,
						'deleted_date'=>'0000-00-00',
				  );

		    	$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;
		 	} 


		 	if(!empty($post['data']))
			{    
				$current_date = date('Y-m-d H:i:s');
	            foreach($post['data'] as $key=>$val)
	            {
	            	
 	            	$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "type"=>2,
	                               "booking_id"=>$data_id,
	                               "vitals_id"=>$key,
	                               "vitals_value"=>$val['name'],
	                               
	                              );
	              
	              $this->db->insert('hms_branch_vitals',$data);
	      		  $id = $this->db->insert_id();
	            } 
			}

		 	return $data_id;
	
		}
    }

    
	public function save_file($filename="")
	{
		//print_r($filename);die;
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'prescription_id'=>$post['prescription_id'],
					'branch_id' => $user_data['parent_id'],
					'status' =>1,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_pediatrician_prescription_files',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_pediatrician_prescription_files',$data);               
		} 	
	}

    
} 
?>