<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipd_progress_report_model extends CI_Model 
{
	var $table = 'hms_ipd_patient_progress_report';
	var $column = array('hms_ipd_patient_progress_report.id','hms_ipd_patient_progress_report.ipd_no','hms_ipd_patient_progress_report.patient_name', 'hms_ipd_patient_progress_report.mobile_no','hms_ipd_patient_progress_report.patient_code', 'hms_ipd_patient_progress_report.status', 'hms_ipd_patient_progress_report.created_date', 'hms_ipd_patient_progress_report.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('ipd_progress_report_search');
		$this->db->select("hms_ipd_patient_progress_report.*"); 
		$this->db->where('hms_ipd_patient_progress_report.is_deleted','0'); 
		if(isset($search['branch_id']) && $search['branch_id']!='')
		{
			$this->db->where('hms_ipd_patient_progress_report.branch_id IN ('.$search['branch_id'].')');
		}
		else
		{
			$this->db->where('hms_ipd_patient_progress_report.branch_id = "'.$user_data['parent_id'].'"');
		}

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_ipd_patient_progress_report.report_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_ipd_patient_progress_report.report_date <= "'.$end_date.'"');
			}

			
			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_ipd_patient_progress_report.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_ipd_patient_progress_report.patient_code',$search['patient_code']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_ipd_patient_progress_report.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}
		}

		/////// Search query end //////////////
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
			$this->db->where('hms_ipd_patient_progress_report.created_by IN ('.$emp_ids.')');
		}
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
		 $this->db->group_by('hms_ipd_patient_progress_report.id');
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
		$this->db->select("hms_ipd_patient_progress_report.*"); 
		$this->db->from('hms_ipd_patient_progress_report'); 
		$this->db->where('hms_ipd_patient_progress_report.id',$id);
		$this->db->where('hms_ipd_patient_progress_report.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function get_by_progress_report_id($prescription_id)
	{ 
		 $this->db->select("hms_ipd_patient_progress_report.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category,hms_ipd_booking.bad_id"); 
		$this->db->from('hms_ipd_patient_progress_report'); 
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_progress_report.patient_id','left');
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_progress_report.ipd_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_patient_progress_report.bed_id','left');
		$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
		$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_patient_progress_report.room_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_patient_progress_report.room_type_id','left');
		$this->db->where('hms_ipd_patient_progress_report.id',$prescription_id);
		$result_pre['progress_report_list']= $this->db->get()->result();
		//echo $this->db->last_query(); 
		return $result_pre;

	}


	public function get_detail_by_progress_report_id($ipd_id='')
	{	
		
		$this->db->select("hms_ipd_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category"); 
		$this->db->from('hms_ipd_booking'); 
		$this->db->where('hms_ipd_booking.id',$ipd_id);
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
		
		$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
		$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
		$result_pre['progress_list']= $this->db->get()->result();
		//echo $this->db->last_query();
		$this->db->select('hms_ipd_patient_progress_report.*');
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_progress_report.ipd_id','left');

		$this->db->where('hms_ipd_patient_progress_report.ipd_id = "'.$ipd_id.'"');
		$this->db->from('hms_ipd_patient_progress_report');
		$result_pre['progress_report_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_progress_id($progress_report_id='')
	{	
		/* 
		,hms_ipd_booking.*,
		*/
		$this->db->select("hms_ipd_patient_progress_report.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category,hms_ipd_booking.admission_date,hms_doctors.doctor_name"); 
		$this->db->from('hms_ipd_patient_progress_report'); 
		$this->db->where('hms_ipd_patient_progress_report.id',$progress_report_id);
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_progress_report.ipd_id','left');

		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_progress_report.patient_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_patient_progress_report.bed_id','left');
		
		$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
		$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_patient_progress_report.room_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_patient_progress_report.room_type_id','left');
		$result= $this->db->get()->result();
		//echo $this->db->last_query();
		

/*$this->db->select('hms_ipd_patient_progress_report.*');
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_progress_report.ipd_id','left');

		$this->db->where('hms_ipd_patient_progress_report.ipd_id = "'.$ipd_id.'"');
		$this->db->from('hms_ipd_patient_progress_report');
		$result_pre['progress_report_list']=$this->db->get()->result();
*/
		return $result;

	}


	public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.patient_bp as patientbp,hms_opd_booking.patient_temp as patienttemp,hms_opd_booking.patient_weight as patientweight,hms_opd_booking.patient_height as patientpr,hms_opd_booking.patient_spo2 as patientspo,hms_opd_booking.patient_rbs as patientrbs');
		$this->db->from('hms_opd_booking'); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
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
		//echo "<pre>";print_r($result_pre); exit;
		return $result_pre;
	}




	public function get_by_ids($id)
	{
		$this->db->select("hms_ipd_patient_progress_report.*,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_booking.discharge_date,hms_ipd_booking.referral_doctor");  
		$this->db->from('hms_ipd_patient_progress_report'); 
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_progress_report.ipd_id','left');
		$this->db->where('hms_ipd_patient_progress_report.id',$id);
		$this->db->where('hms_ipd_patient_progress_report.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
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
			$this->db->update('hms_ipd_patient_progress_report');
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
			$this->db->update('hms_ipd_patient_progress_report');
    	} 
    }

    public function save()
    {
    	
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 

		$progress_report_id = $post['data_id'];
		if($post['report_date']!='00-00-0000' && $post['report_date']!='01-01-1970')
		{
			$report_date = date('Y-m-d',strtotime($post['report_date']));
		}	
		else
		{
			$report_date = ''; 
		}

		$report_time = date('Y-m-d',strtotime($post['report_time']));
		$data = array( 
					"branch_id"=> $user_data['parent_id'],
					'ipd_no'=>$post['ipd_no'],    
					"ipd_id"=>$post['ipd_id'],
					'room_type_id'=>$post['room_type_id'],
					'bed_id'=>$post['bad_id'],
					'room_id'=>$post['room_id'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"patient_rbs"=>$post['patient_rbs'],
					"prescription"=>$post['prescription'],
					"dressing"=>$post['dressing'],
					"suggestion"=>$post['suggestion'],
					"remarks"=>$post['remarks'],
					'attend_doctor_id'=>$post['attend_doctor_id'],
					"report_date"=>$report_date,
					"report_time"=>$report_time,
					"status"=>1
					); 
		    
        if(!empty($post['data_id']) && $post['data_id']>0)
		{   
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_patient_progress_report',$data); 

			//echo $this->db->last_query(); exit;
			return $progress_report_id;

		}
		else
		{
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ipd_patient_progress_report',$data); 
			//echo $this->db->last_query(); exit;
			$progress_report_id = $this->db->insert_id(); 

			//echo $this->db->last_query(); exit;
			return $progress_report_id;
		} 
	
    }


    function template_format($data="",$branch_id='')
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_branch_progress_note_print_setting.*');
    	$this->db->where($data);
    	if(!empty($branch_id))
    	{
    		$this->db->where('hms_branch_progress_note_print_setting.branch_id = "'.$branch_id.'"');
    	}
    	else
    	{
    		$this->db->where('hms_branch_progress_note_print_setting.branch_id = "'.$users_data['parent_id'].'"');
    	}
    	 
    	$this->db->from('hms_branch_progress_note_print_setting');
    	$result=$this->db->get()->row();
    	return $result;

    }

     
    
   

   
    public function prescription_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_ipd_prescription');
        $result = $query->result(); 
        return $result; 
    }

    public function prescription_name($diagnosis_id="")
   {
		$diagnosis_ids = explode(',', $diagnosis_id);
		$users_data = $this->session->userdata('auth_users'); 
		$prescriptionname="";
		$i=1;
		$total = count($diagnosis_ids);
		foreach ($diagnosis_ids as $value) 
		{
			$this->db->select('hms_ipd_prescription.prescription');  
			$this->db->where('hms_ipd_prescription.id',$value);  
			$this->db->where('hms_ipd_prescription.is_deleted',0);  
			$this->db->where('hms_ipd_prescription.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_ipd_prescription.id');  
			$query = $this->db->get('hms_ipd_prescription');
			$result = $query->row(); 

			$prescriptionname .= $result->prescription;
			if($i!=$total)
			{
				$prescriptionname .=',';
			}
		
		$i++;
			
		}
		return $prescriptionname;
		 
    }


    

    public function suggetion_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_ipd_suggestion');
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
			$this->db->select('hms_ipd_suggestion.suggestion');  
			$this->db->where('hms_ipd_suggestion.id',$value);  
			$this->db->where('hms_ipd_suggestion.is_deleted',0);  
			$this->db->where('hms_ipd_suggestion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_ipd_suggestion.id');  
			$query = $this->db->get('hms_ipd_suggestion');
			$result = $query->row(); 

			$suggetionname .= $result->suggestion;
			if($i!=$total)
			{
				$suggetionname .=',';
			}
		
		$i++;
			
		}
		return $suggetionname;
		 
    }

    public function dressing_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_ipd_dressing');
        $result = $query->result(); 
        return $result; 
    }

    public function dressing_name($prv_id="")
   {
		$prv_ids = explode(',', $prv_id);
		$users_data = $this->session->userdata('auth_users'); 
		$dressingname="";
		$i=1;
		$total = count($prv_ids);
		foreach ($prv_ids as $value) 
		{
			$this->db->select('hms_ipd_dressing.dressing');  
			$this->db->where('hms_ipd_dressing.id',$value);  
			$this->db->where('hms_ipd_dressing.is_deleted',0);  
			$this->db->where('hms_ipd_dressing.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_ipd_dressing.id');  
			$query = $this->db->get('hms_ipd_dressing');
			$result = $query->row(); 

			$dressingname .= $result->dressing;
			if($i!=$total)
			{
				$dressingname .=',';
			}
		
		$i++;
			
		}
		return $dressingname;
		 
    }

    public function remarks_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_ipd_remarks');
        $result = $query->result(); 
        return $result; 
    }

   public function remarks_name($prv_id="")
   {
		$prv_ids = explode(',', $prv_id);
		$users_data = $this->session->userdata('auth_users'); 
		$remarksname="";
		$i=1;
		$total = count($prv_ids);
		foreach ($prv_ids as $value) 
		{
			$this->db->select('hms_ipd_remarks.remarks');  
			$this->db->where('hms_ipd_remarks.id',$value);  
			$this->db->where('hms_ipd_remarks.is_deleted',0);  
			$this->db->where('hms_ipd_remarks.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_ipd_remarks.id');  
			$query = $this->db->get('hms_ipd_remarks');
			$result = $query->row(); 

			$remarksname .= $result->remarks;
			if($i!=$total)
			{
				$remarksname .=',';
			}
		
		$i++;
			
		}
		return $remarksname;
		 
    }

   
    
    public function template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_prescription_template');
        $result = $query->result(); 
        return $result; 
    }

    	 

    
} 
?>