<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prescription_model extends CI_Model 
{
	var $table = 'hms_ipd_patient_prescription';
	var $column = array('hms_ipd_patient_prescription.id','hms_ipd_patient_prescription.ipd_no','hms_ipd_patient_prescription.patient_name', 'hms_ipd_patient_prescription.mobile_no','hms_ipd_patient_prescription.patient_code', 'hms_ipd_patient_prescription.status', 'hms_ipd_patient_prescription.created_date', 'hms_ipd_patient_prescription.modified_date');  
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
		$this->db->select("hms_ipd_patient_prescription.*,hms_ipd_prescription_patient_test.test_name,hms_ipd_prescription_patient_pres.medicine_name,hms_ipd_prescription_patient_pres.medicine_salt,hms_ipd_prescription_patient_pres.medicine_brand,hms_ipd_prescription_patient_pres.medicine_type,hms_ipd_prescription_patient_pres.medicine_dose,hms_ipd_prescription_patient_pres.medicine_duration,hms_ipd_prescription_patient_pres.medicine_frequency,hms_ipd_prescription_patient_pres.medicine_advice"); 
		$this->db->join('hms_ipd_prescription_patient_test','hms_ipd_prescription_patient_test.prescription_id=hms_ipd_patient_prescription.id','left');
        $this->db->join('hms_ipd_prescription_patient_pres','hms_ipd_prescription_patient_pres.prescription_id=hms_ipd_patient_prescription.id','left');
		
		$this->db->where('hms_ipd_patient_prescription.is_deleted','0'); 
		

		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_ipd_patient_prescription.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_ipd_patient_prescription.branch_id = "'.$user_data['parent_id'].'"');
		}

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_ipd_patient_prescription.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_ipd_patient_prescription.created_date <= "'.$end_date.'"');
			}

			
			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_ipd_patient_prescription.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_ipd_patient_prescription.patient_code',$search['patient_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_ipd_patient_prescription.mobile_no LIKE "'.$search['mobile_no'].'%"');
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
			$this->db->where('hms_ipd_patient_prescription.created_by IN ('.$emp_ids.')');
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
		 $this->db->group_by('hms_ipd_patient_prescription.id');
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
		$this->db->select("hms_ipd_patient_prescription.*,hms_ipd_prescription_patient_test.test_name,hms_ipd_prescription_patient_pres.medicine_name,hms_ipd_prescription_patient_pres.medicine_salt,hms_ipd_prescription_patient_pres.medicine_brand,hms_ipd_prescription_patient_pres.medicine_type,hms_ipd_prescription_patient_pres.medicine_dose,hms_ipd_prescription_patient_pres.medicine_duration,hms_ipd_prescription_patient_pres.medicine_frequency,hms_ipd_prescription_patient_pres.medicine_advice"); 
		$this->db->from('hms_ipd_patient_prescription'); 
		$this->db->where('hms_ipd_patient_prescription.id',$id);
		$this->db->where('hms_ipd_patient_prescription.is_deleted','0'); 
		$this->db->join('hms_ipd_prescription_patient_test','hms_ipd_prescription_patient_test.prescription_id=hms_ipd_patient_prescription.id','left');
		$this->db->join('hms_ipd_prescription_patient_pres','hms_ipd_prescription_patient_pres.prescription_id=hms_ipd_patient_prescription.id','left');
		
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function get_by_prescription_id($prescription_id)
	{ 
		$this->db->select("hms_ipd_patient_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob"); 
		$this->db->from('hms_ipd_patient_prescription'); 
		$this->db->where('hms_ipd_patient_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left');
		
		$result_pre['prescription_list']= $this->db->get()->result();
		
		$this->db->select('hms_ipd_prescription_patient_test.*');
		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_ipd_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_ipd_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_ipd_prescription_patient_pres.*');
		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_ipd_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_ipd_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_ipd_patient_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date,hms_ipd_booking.referral_doctor,hms_ipd_booking.attend_doctor_id,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_patient_prescription.patient_bp as patientbp,hms_ipd_patient_prescription.patient_temp as patienttemp,hms_ipd_patient_prescription.patient_weight as patientweight,hms_ipd_patient_prescription.patient_height as patientpr,hms_ipd_patient_prescription.patient_spo2 as patientspo,hms_ipd_patient_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 



		$this->db->from('hms_ipd_patient_prescription'); 
		$this->db->where('hms_ipd_patient_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_prescription.booking_id','left'); 
		$result_pre['prescription_list']= $this->db->get()->result();
		
		$this->db->select('hms_ipd_prescription_patient_test.*');
		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_ipd_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_ipd_prescription_patient_test');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_ipd_prescription_patient_pres.*');
		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_ipd_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_ipd_prescription_patient_pres');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ipd_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date,hms_ipd_booking.referral_doctor,hms_ipd_booking.attend_doctor_id,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_booking.patient_bp as patientbp,hms_ipd_booking.patient_temp as patienttemp,hms_ipd_booking.patient_weight as patientweight,hms_ipd_booking.patient_height as patientpr,hms_ipd_booking.patient_spo2 as patientspo,hms_ipd_booking.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation');
		$this->db->from('hms_ipd_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		if(!empty($branch_id))
		{
			$this->db->where('hms_ipd_booking.branch_id',$branch_id); 
		}
		else
		{
			$this->db->where('hms_ipd_booking.branch_id',$user_data['parent_id']); 	
		}
		
		$this->db->where('hms_ipd_booking.id',$id);
		$this->db->where('hms_ipd_booking.is_deleted','0');
		 
		$result_pre['prescription_list']= $this->db->get()->result();
		//echo "<pre>";print_r($result_pre); exit;
		return $result_pre;
	}


    public function get_by_ids($id)
	{
		$this->db->select("hms_ipd_patient_prescription.*,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_time,hms_ipd_booking.admission_date,hms_ipd_booking.referral_doctor,hms_ipd_booking.attend_doctor_id,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 
		$this->db->from('hms_ipd_patient_prescription'); 
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_prescription.booking_id','left');
		$this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id'); 
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->where('hms_ipd_patient_prescription.id',$id);
		$this->db->where('hms_ipd_patient_prescription.is_deleted','0'); 
		$query = $this->db->get(); 

		//echo $this->db->last_query(); exit;
		return $query->row_array();
		
	}



	public function get_prescription_by_ids($id)
	{
		$this->db->select("hms_ipd_patient_prescription.*"); 
		$this->db->from('hms_ipd_patient_prescription'); 
		$this->db->where('hms_ipd_patient_prescription.id',$id);
		$this->db->where('hms_ipd_patient_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}
  	
	function get_ipd_prescription($prescription_id='')
	{
		$this->db->select("hms_ipd_prescription_patient_pres.*"); 
		$this->db->from('hms_ipd_prescription_patient_pres'); 
		$this->db->where('hms_ipd_prescription_patient_pres.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_ipd_test($prescription_id='')
	{
		$this->db->select("hms_ipd_prescription_patient_test.*"); 
		$this->db->from('hms_ipd_prescription_patient_test'); 
		$this->db->where('hms_ipd_prescription_patient_test.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		//echo $this->db->last_query(); exit;
		$result = $query->result(); 
        return $result;
	}

	public function save($filename="")
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$reg_no = generate_unique_id(3);  
		$data = array(
					"branch_id"=> $user_data['parent_id'],    
					"patient_name"=>$post['patient_name'],
					"simulation_id"=>$post['simulation_id'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"dob"=>$post['dob'],
					"address"=>$post['address'],
					"city_id"=>$post['city_id'],
					"state_id"=>$post['state_id'],
					"country_id"=>$post['country_id'],
					"pincode"=>$post['pincode'],
					"marital_status"=>$post['marital_status'],
					"religion_id"=>$post['religion_id'],
					"father_husband"=>$post['father_husband'],
					"mother"=>$post['mother'],
					"guardian_name"=>$post['guardian_name'],
					"guardian_email"=>$post['guardian_email'],
					"guardian_phone"=>$post['guardian_phone'],
					"relation_id"=>$post['relation_id'],
					"patient_email"=>$post['patient_email'],
					"monthly_income"=>$post['monthly_income'],
					"occupation"=>$post['occupation'],
					"insurance_type"=>$post['insurance_type'],
					"insurance_type_id"=>$post['insurance_type_id'],
					"ins_company_id"=>$post['ins_company_id'],
					"polocy_no"=>$post['polocy_no'],
					"tpa_id"=>$post['tpa_id'],
					"ins_amount"=>$post['ins_amount'],
					"ins_authorization_no"=>$post['ins_authorization_no'], 
					"status"=>$post['status'],
					"remark"=>$post['remark'] 
				         ); 
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
		}
		else
		{    
            if(!empty($filename))
			{
				$this->db->set('photo',$filename);
			}
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_patient',$data); 
			$data_id = $this->db->insert_id();               
		} 	
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
			$this->db->update('hms_ipd_patient_prescription');
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
			$this->db->update('hms_ipd_patient_prescription');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        return $result; 
    }


    public function save_file($filename="")
	{
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
			$this->db->update('hms_ipd_prescription_files',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('prescription_files',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ipd_prescription_files',$data);               
		} 	
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
			$query = $this->db->get('hms_ipd_prescription_files'); 
			$result = $query->result(); 
			//echo $this->db->last_query(); exit;
			return $result;
			
    	} 
	}

    public function save_prescription()
    {
    	
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		$prescriptionid = $post['data_id'];
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
		
		
		$data_patient = array(
								"patient_name"=>$post['patient_name'],
								"mobile_no"=>$post['mobile_no'],
								"gender"=>$post['gender'], 
								"age_y"=>$post['age_y'],
								"age_m"=>$post['age_m'],
								"age_d"=>$post['age_d'],
								
								'relation_type'=>$post['relation_type'],
								'relation_name'=>$post['relation_name'],
								'relation_simulation_id'=>$post['relation_simulation_id'],
					    	);

		if(!empty($post['patient_id']) && $post['patient_id']>0)
	    {
	    	$patient_id = $post['patient_id'];
	    	$this->db->where('id',$post['patient_id']);
    	    $this->db->update('hms_patient',$data_patient);
    	    //echo $this->db->last_query(); exit;
	    } 
	//    dd(date('Y-m-d H:i:s A', strtotime($post['date_time_new'])));
 		$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"ipd_no"=>$post['ipd_no'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"date_time_new" =>$post['date_time_new'],
					"status"=>1
					); 
		    
            
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_patient_prescription',$data); 

			//echo $this->db->last_query(); exit;
			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_ipd_prescription_patient_test'); 

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
					$this->db->insert('hms_ipd_prescription_patient_test',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			}

			$this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_ipd_prescription_patient_pres'); 

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
						$this->db->select('hms_opd_medicine_type.*');
						//$this->db->from('hms_opd_medicine_type');
						$this->db->where('hms_opd_medicine_type.medicine_type',$post['medicine_type'][$i]); 
						$this->db->where('hms_opd_medicine_type.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_type.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_type');
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
								$this->db->insert('hms_opd_medicine_type',$data);
						}
					}
					//medicine dose
					if(!empty($post['medicine_dose'][$i]))
					{
						$this->db->select('hms_opd_medicine_dosage.*');
						//$this->db->from('hms_opd_medicine_dosage');
						$this->db->where('hms_opd_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
						$this->db->where('hms_opd_medicine_dosage.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_dosage.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_dosage');
						$num = $query->num_rows();
						if($num>0)
						{

						}
						else
						{
								$data = array( 
											'branch_id'=>$user_data['parent_id'],
											'medicine_dosage'=>$post['medicine_dosage'][$i],
											'status'=>1,
											'ip_address'=>$_SERVER['REMOTE_ADDR']
									        );
								$this->db->set('created_by',$user_data['id']);
								$this->db->set('created_date',date('Y-m-d H:i:s'));
								$this->db->insert('hms_opd_medicine_dosage',$data);
						}
					}

					//medicine duration
					if(!empty($post['medicine_duration'][$i]))
					{
						$this->db->select('hms_opd_medicine_dosage_duration.*'); 
						//$this->db->from('hms_opd_medicine_dosage_duration');
						$this->db->where('hms_opd_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
						$this->db->where('hms_opd_medicine_dosage_duration.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_dosage_duration');
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
								$this->db->insert('hms_opd_medicine_dosage_duration',$data);
						}
					}

					//medicine frequency
					if(!empty($post['medicine_frequency'][$i]))
					{
						$this->db->select('hms_opd_medicine_dosage_frequency.*');
						//$this->db->from('hms_opd_medicine_dosage_frequency');
						$this->db->where('hms_opd_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
						$this->db->where('hms_opd_medicine_dosage_frequency.is_deleted!=2'); 
						$this->db->where('hms_opd_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_medicine_dosage_frequency');
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
							$this->db->insert('hms_opd_medicine_dosage_frequency',$data);
						}
					}

					//medicine advice
					if(!empty($post['medicine_advice'][$i]))
					{
						$this->db->select('*');
						//$this->db->from('hms_opd_advice');
						$this->db->where('hms_opd_advice.medicine_advice',$post['medicine_advice'][$i]); 
						$this->db->where('hms_opd_advice.is_deleted!=2'); 
						$this->db->where('hms_opd_advice.branch_id',$user_data['parent_id']); 
						$query = $this->db->get('hms_opd_advice');
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
							$this->db->insert('hms_opd_advice',$data);
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
					$this->db->insert('hms_ipd_prescription_patient_pres',$prescription_data);
					$test_data_id = $this->db->insert_id(); 
					//echo $this->db->last_query(); exit;
				
			}
			;

			


		 	if(!empty($post['data']))
			{  

				$this->db->where('booking_id',$prescriptionid);
				$this->db->where('type',6);
				$this->db->where('branch_id',$user_data['parent_id']);
				$this->db->delete('hms_branch_vitals');
  
				$current_date = date('Y-m-d H:i:s');
	            foreach($post['data'] as $key=>$val)
	            {
	            	
 	            	$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "type"=>6,
	                               "booking_id"=>$prescriptionid,
	                               "vitals_id"=>$key,
	                               "vitals_value"=>$val['name'],
	                               
	                              );
	              
	              $this->db->insert('hms_branch_vitals',$data);
	      		  $id = $this->db->insert_id();
	            } 
			}


		 	return $prescriptionid;

		}
		else
		{

		} 
	
    }
	

    function template_format($data="",$branch_id='')
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_ipd_branch_prescription_setting.*');
    	$this->db->where($data);
    	if(!empty($branch_id))
    	{
    		$this->db->where('hms_ipd_branch_prescription_setting.branch_id = "'.$branch_id.'"');
    	}
    	else
    	{
    		$this->db->where('hms_ipd_branch_prescription_setting.branch_id = "'.$users_data['parent_id'].'"');
    	}
    	 
    	$this->db->from('hms_ipd_branch_prescription_setting');
    	$result=$this->db->get()->row();
    	return $result;

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

    public function get_dosage_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_opd_medicine_dosage');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage;
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
	        $query = $this->db->get('hms_opd_medicine_dosage_duration');
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
	        $query = $this->db->get('hms_opd_medicine_dosage_frequency');
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
    
    public function check_consolidate_prescription($all_prescription_ids='')
	{
	    $user_data = $this->session->userdata('auth_users');
	    $this->db->select("hms_ipd_patient_prescription.patient_id"); 
        $this->db->from('hms_ipd_patient_prescription'); 
		if(!empty($all_prescription_ids))
		{
		    $this->db->where('hms_ipd_patient_prescription.id IN ('.$all_prescription_ids.')');
		}
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left');
		$result_pre= $this->db->get()->result();
		if(!empty($result_pre))
		{
		    $id_list = [];
    		foreach($result_pre as $res)
    		{
    		    //echo "<pre>"; print_r($res); die;
    			if(!empty($res) && $res>0)
    			{
    			      if(!empty($id_list) && !in_array($res->patient_id, $id_list))
                      {
                        return 0;
                      }
                  $id_list[]  = $res->patient_id;
    			} 
    		}
    		
    		return 1;
		}
		
		//echo $this->db->last_query(); die;
		return $result_pre;
	}
	
	public function get_consolidate_prescription($all_prescription_ids='')
	{
	    $user_data = $this->session->userdata('auth_users');
	    $this->db->select("hms_ipd_patient_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_booking.ipd_no as booking_code,hms_ipd_booking.admission_date as booking_date,'' as specialization_id,hms_ipd_booking.referral_doctor,hms_ipd_booking.attend_doctor_id as attended_doctor,hms_ipd_booking.admission_time as booking_time,hms_ipd_patient_prescription.patient_bp as patientbp,hms_ipd_patient_prescription.patient_temp as patienttemp,hms_ipd_patient_prescription.patient_weight as patientweight,hms_ipd_patient_prescription.patient_height as patientpr,hms_ipd_patient_prescription.patient_spo2 as patientspo,hms_ipd_patient_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation,hms_doctors.header_content,hms_doctors.seprate_header,hms_doctors.opd_header,hms_doctors.billing_header,hms_doctors.prescription_header"); 



		$this->db->from('hms_ipd_patient_prescription'); 
		if(!empty($all_prescription_ids))
		{
		    $this->db->where('hms_ipd_patient_prescription.id IN ('.$all_prescription_ids.')');
		}
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_prescription.booking_id','left'); 
		
		$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking. attend_doctor_id','left');
		$this->db->order_by('hms_ipd_booking.admission_date','ASC');
		$result_pre= $this->db->get()->result();
		//echo $this->db->last_query(); die;
		return $result_pre;
	}
	
	public function get_prescription_test($prescription_id='')
	{
	    $this->db->select('hms_ipd_prescription_patient_test.*');
		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_test.prescription_id'); 
		$this->db->where('hms_ipd_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_ipd_prescription_patient_test');
		$result_pre=$this->db->get()->result();
		//echo $this->db->last_query(); die;
        return $result_pre;
		
	}
	public function get_prescription_medicine($prescription_id='')
	{
	    $this->db->select('hms_ipd_prescription_patient_pres.*');
		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_pres.prescription_id'); 
		$this->db->where('hms_ipd_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
		$this->db->from('hms_ipd_prescription_patient_pres');
		$result_pre=$this->db->get()->result();
		//echo $this->db->last_query(); die;
		return $result_pre;
	}
	
	public function get_detail_by_ipd_id($ipdId)
	{
	    $this->db->select('hms_ipd_patient_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date,hms_ipd_booking.referral_doctor,hms_ipd_booking.attend_doctor_id,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_patient_prescription.patient_bp as patientbp,hms_ipd_patient_prescription.patient_temp as patienttemp,hms_ipd_patient_prescription.patient_weight as patientweight,hms_ipd_patient_prescription.patient_height as patientpr,hms_ipd_patient_prescription.patient_spo2 as patientspo,hms_ipd_patient_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation');
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left'); 
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_prescription.booking_id','left'); 
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->where('hms_ipd_patient_prescription.booking_id = "'.$ipdId.'"');
		$this->db->where('hms_ipd_patient_prescription.is_deleted = 0');
		$this->db->from('hms_ipd_patient_prescription');
		$this->db->order_by('hms_ipd_patient_prescription.created_date','asc');
		//$this->db->limit(2);
		$result_prescrption=$this->db->get()->result();
		//echo $this->db->last_query(); die;
	    //echo "<pre>"; print_r($result_prescrption); die;
	    $result_pre = array();
	    if(!empty($result_prescrption)){
	        $i=0;
	        foreach($result_prescrption as $val) {
	            $prescription_id = $val->id;
	            $this->db->select("hms_ipd_patient_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date,hms_ipd_booking.referral_doctor,hms_ipd_booking.attend_doctor_id,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_patient_prescription.patient_bp as patientbp,hms_ipd_patient_prescription.patient_temp as patienttemp,hms_ipd_patient_prescription.patient_weight as patientweight,hms_ipd_patient_prescription.patient_height as patientpr,hms_ipd_patient_prescription.patient_spo2 as patientspo,hms_ipd_patient_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 
        		$this->db->from('hms_ipd_patient_prescription'); 
        		$this->db->where('hms_ipd_patient_prescription.id',$prescription_id);
        		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_prescription.patient_id','left');
        		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        		$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_prescription.booking_id','left'); 
        		$result_pre[$i]= $this->db->get()->result();
        		
        		$this->db->select('hms_ipd_prescription_patient_test.*');
        		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_test.prescription_id'); 
        		$this->db->where('hms_ipd_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
        		$this->db->from('hms_ipd_prescription_patient_test');
        		$result_pre[$i]['prescription_test_list']=$this->db->get()->result();
        
        		$this->db->select('hms_ipd_prescription_patient_pres.*');
        		$this->db->join('hms_ipd_patient_prescription','hms_ipd_patient_prescription.id = hms_ipd_prescription_patient_pres.prescription_id'); 
        		$this->db->where('hms_ipd_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
        		$this->db->from('hms_ipd_prescription_patient_pres');
        		$result_pre[$i]['prescription_presc_list']=$this->db->get()->result();
	        
	            $i++;
	        }
	    }
        return $result_pre;

	}
	

    	 

    
} 
?>