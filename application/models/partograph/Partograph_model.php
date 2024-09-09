<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Partograph_model extends CI_Model 
{
	var $table = 'hms_opd_partograph';
	var $column = array('hms_opd_partograph.id','hms_opd_partograph.booking_code','hms_opd_partograph.patient_name', 'hms_opd_partograph.mobile_no','hms_opd_partograph.patient_code', 'hms_opd_partograph.status', 'hms_opd_partograph.created_date', 'hms_opd_partograph.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search = $this->session->userdata('partograph_search');
		$this->db->select("hms_opd_partograph.*"); 
		$this->db->join('hms_opd_partograph_patient_amniotic_fluid','hms_opd_partograph_patient_amniotic_fluid.parto_id=hms_opd_partograph.id','left');
        $this->db->join('hms_opd_partograph_patient_temp','hms_opd_partograph_patient_temp.parto_id=hms_opd_partograph.id','left');
		
		$this->db->where('hms_opd_partograph.is_deleted','0'); 
		
		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_opd_partograph.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_opd_partograph.branch_id = "'.$user_data['parent_id'].'"');
		}

		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_opd_partograph.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_opd_partograph.created_date <= "'.$end_date.'"');
			}

			
			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_opd_partograph.patient_name LIKE "%'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_opd_partograph.patient_code LIKE "%'.$search['patient_code'].'%"');
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_opd_partograph.mobile_no LIKE "%'.$search['mobile_no'].'%"');
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
			$this->db->where('hms_opd_partograph.created_by IN ('.$emp_ids.')');
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
		 $this->db->group_by('hms_opd_partograph.id');
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
		$this->db->select("hms_opd_partograph.*,hms_opd_partograph_patient_temp.value,hms_opd_partograph_patient_temp.value,hms_opd_partograph_patient_temp.time,hms_opd_partograph_patient_temp.hours"); 
		$this->db->from('hms_opd_partograph'); 
		$this->db->where('hms_opd_partograph.id',$id);
		$this->db->where('hms_opd_partograph.is_deleted','0'); 
		$this->db->join('hms_opd_partograph_patient_temp','hms_opd_partograph_patient_temp.parto_id=hms_opd_partograph.id','left');
		$this->db->join('hms_opd_partograph_patient_pulse_bp','hms_opd_partograph_patient_temp.parto_id=hms_opd_partograph.id','left');
		
		$query = $this->db->get(); 
		//echo $this->db->last_query();die();
		return $query->row_array();
	}

	public function get_by_parto_id($parto_id)
	{
		$this->db->select("hms_opd_partograph.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.simulation_id,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob"); 
		$this->db->from('hms_opd_partograph'); 
		$this->db->where('hms_opd_partograph.id',$parto_id);
		$this->db->join('hms_patient','hms_patient.id=hms_opd_partograph.patient_id','left');
		$result_pre['partograph_list']= $this->db->get()->result();
		
		// get foetal_heart 
		$this->db->select('hms_opd_partograph_patient_foetal_heart.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_foetal_heart.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_foetal_heart.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_foetal_heart');

		$result_pre['partograph_list']['foetal_heart_list']=$this->db->get()->result();
		// get temp list
		$this->db->select('hms_opd_partograph_patient_temp.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_temp.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_temp.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_temp');
		$result_pre['partograph_list']['temp_list']=$this->db->get()->result();

		// get amniotic_fluid list

		$this->db->select('hms_opd_partograph_patient_amniotic_fluid.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_amniotic_fluid.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_amniotic_fluid.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_amniotic_fluid');
		$result_pre['partograph_list']['amniotic_fluid_list']=$this->db->get()->result();

		// get cervic list

		$this->db->select('hms_opd_partograph_patient_cervic.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_cervic.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_cervic.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_cervic');
		$result_pre['partograph_list']['cervic_list']=$this->db->get()->result();

		// get contraction list

		$this->db->select('hms_opd_partograph_patient_contraction.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_contraction.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_contraction.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_contraction');
		$result_pre['partograph_list']['contraction_list']=$this->db->get()->result();

		// get drugs_fluid list


		$this->db->select('hms_opd_partograph_patient_drugs_fluid.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_drugs_fluid.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_drugs_fluid.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_drugs_fluid');
		$result_pre['partograph_list']['drugs_fluid_list']=$this->db->get()->result();
		// get pulse_bp list
		$this->db->select('hms_opd_partograph_patient_pulse_bp.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_pulse_bp.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_pulse_bp.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_pulse_bp');

		$result_pre['partograph_list']['pulse_bp_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_parto_id($parto_id)
	{
		$this->db->select("hms_opd_partograph.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.simulation_id,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 
		$this->db->from('hms_opd_partograph'); 
		$this->db->where('hms_opd_partograph.id',$parto_id);
		$this->db->join('hms_patient','hms_patient.id=hms_opd_partograph.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_opd_partograph.booking_id','left'); 
		$result_pre['partograph_list']= $this->db->get()->result();
		
		/*$this->db->select('hms_opd_partograph_patient_foetal_heart.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_foetal_heart.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_foetal_heart.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_foetal_heart');
		$result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

		$this->db->select('hms_opd_partograph_patient_temp.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_temp.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_temp.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_temp');
		$result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();*/

		// get foetal_heart 
		$this->db->select('hms_opd_partograph_patient_foetal_heart.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_foetal_heart.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_foetal_heart.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_foetal_heart');

		$result_pre['partograph_list']['foetal_heart_list']=$this->db->get()->result();
		// get temp list
		$this->db->select('hms_opd_partograph_patient_temp.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_temp.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_temp.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_temp');
		$result_pre['partograph_list']['temp_list']=$this->db->get()->result();

		// get amniotic_fluid list

		$this->db->select('hms_opd_partograph_patient_amniotic_fluid.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_amniotic_fluid.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_amniotic_fluid.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_amniotic_fluid');
		$result_pre['partograph_list']['amniotic_fluid_list']=$this->db->get()->result();

		// get cervic list

		$this->db->select('hms_opd_partograph_patient_cervic.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_cervic.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_cervic.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_cervic');
		$result_pre['partograph_list']['cervic_list']=$this->db->get()->result();

		// get contraction list

		$this->db->select('hms_opd_partograph_patient_contraction.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_contraction.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_contraction.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_contraction');
		$result_pre['partograph_list']['contraction_list']=$this->db->get()->result();

		// get drugs_fluid list


		$this->db->select('hms_opd_partograph_patient_drugs_fluid.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_drugs_fluid.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_drugs_fluid.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_drugs_fluid');
		$result_pre['partograph_list']['drugs_fluid_list']=$this->db->get()->result();
		// get pulse_bp list
		$this->db->select('hms_opd_partograph_patient_pulse_bp.*');
		$this->db->join('hms_opd_partograph','hms_opd_partograph.id = hms_opd_partograph_patient_pulse_bp.parto_id'); 
		$this->db->where('hms_opd_partograph_patient_pulse_bp.parto_id = "'.$parto_id.'"');
		$this->db->from('hms_opd_partograph_patient_pulse_bp');
		$result_pre['partograph_list']['pulse_bp_list']=$this->db->get()->result();

		return $result_pre;

	}


	public function get_detail_by_booking_id($id,$branch_id='')
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.gravida,hms_patient.para,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_opd_booking.patient_bp as patientbp,hms_opd_booking.patient_temp as patienttemp,hms_opd_booking.patient_weight as patientweight,hms_opd_booking.patient_height as patientpr,hms_opd_booking.patient_spo2 as patientspo,hms_opd_booking.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation');
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
		//echo "<pre>";print_r($result_pre); exit;
		return $result_pre;
	}


        public function get_by_ids($id)
	{
		$this->db->select("hms_opd_partograph.*,hms_opd_booking.booking_code,hms_opd_booking.booking_time,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation");  //,hms_opd_partograph_patient_foetal_heart.*,hms_opd_partograph_patient_temp.*
		$this->db->from('hms_opd_partograph'); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_opd_partograph.booking_id','left');
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->where('hms_opd_partograph.id',$id);
		$this->db->where('hms_opd_partograph.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}

	public function get_partograph_by_ids($id)
	{
		$this->db->select("hms_opd_partograph.*");  //,hms_opd_partograph_patient_foetal_heart.*,hms_opd_partograph_patient_temp.*patient_id
		$this->db->from('hms_opd_partograph'); 
		$this->db->where('hms_opd_partograph.id',$id);
		$this->db->where('hms_opd_partograph.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}
  	
	function get_opd_partograph($parto_id='')
	{
		$this->db->select("hms_opd_partograph.*"); 
		$this->db->from('hms_opd_partograph'); 
		$this->db->where('hms_opd_partograph.id',$parto_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_opd_test($parto_id='')
	{
		$this->db->select("hms_opd_partograph_patient_foetal_heart.*"); 
		$this->db->from('hms_opd_partograph_patient_foetal_heart'); 
		$this->db->where('hms_opd_partograph_patient_foetal_heart.parto_id',$parto_id);
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
			$this->db->update('hms_opd_partograph');
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
			$this->db->update('hms_opd_partograph');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        return $result; 
    }

    public function save_partograph()
    {
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//echo "<pre>"; print_r($post);die();
 		$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					"booking_date"=>date('Y-m-d',strtotime($post['booking_date'])),
					"booking_time"=>date('H:i:s',strtotime($post['booking_time'])),
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"gravida"=>$post['gravida'],
					"para"=>$post['para'],
					"attended_doctor"=>$post['attended_doctor'],
					"appointment_date"=>$post['appointment_date'],
					"status"=>1,
					"ip_address"=>$_SERVER['REMOTE_ADDR'],
					"created_by"=>$user_data['id'],
					"created_date"=>date('Y-m-d H:i:s'),
					"modified_by"=>$user_data['id'],
					"modified_date"=>date('Y-m-d H:i:s')
					); 

 			if(!empty($post['data_id']))
 			{ 
 				$this->db->where('booking_id',$post['booking_id']);
 				$this->db->where('id',$post['data_id']);
 				$this->db->update('hms_opd_partograph',$data); 
				$data_id = $post['data_id']; 

 				$this->db->where('hms_opd_partograph_patient_foetal_heart.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_foetal_heart');

 				$this->db->where('hms_opd_partograph_patient_amniotic_fluid.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_amniotic_fluid');
				
 				$this->db->where('hms_opd_partograph_patient_cervic.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_cervic');
				
 				$this->db->where('hms_opd_partograph_patient_contraction.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_contraction');
			
 				$this->db->where('hms_opd_partograph_patient_drugs_fluid.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_drugs_fluid');

 				$this->db->where('hms_opd_partograph_patient_pulse_bp.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_pulse_bp');

 				$this->db->where('hms_opd_partograph_patient_temp.parto_id',$post['data_id']);
				$this->db->delete('hms_opd_partograph_patient_temp');
 			}
 			else{
 				$this->db->insert('hms_opd_partograph',$data); 
				$data_id = $this->db->insert_id(); 
 			}
			

			$total_test = count(array_filter($post['fhr_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['fhr_test_value'][$i]))
					{	
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['fhr_test_value'][$i],
												'time'=>date('H:i:s',strtotime($post['fhr_test_time'][$i])),
											
									         );
								$this->db->insert('hms_opd_partograph_patient_foetal_heart',$data);
						
					}
			}
			$total_test = count(array_filter($post['af_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['af_test_value'][$i]))
					{	
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['af_test_value'][$i],
												'time'=>date('H:i:s',strtotime($post['af_test_time'][$i])),
												
									         );
								$this->db->insert('hms_opd_partograph_patient_amniotic_fluid',$data);
						
					}
			}
			$total_test = count(array_filter($post['cervic_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['cervic_test_value'][$i]))
					{	
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['cervic_test_value'][$i],
												'value_x'=>$post['cervic_test_value_x'][$i],
												'time'=>date('H:i:s',strtotime($post['cervic_test_time'][$i])),
												
									         );
								$this->db->insert('hms_opd_partograph_patient_cervic',$data);
						
					}
			}
			$total_test = count(array_filter($post['cpm_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['cpm_test_value'][$i]))
					{	
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['cpm_test_value'][$i],
												'time'=>date('H:i:s',strtotime($post['cpm_test_time'][$i])),
												
									         );
								$this->db->insert('hms_opd_partograph_patient_contraction',$data);
						
					}
			}
			$total_test = count(array_filter($post['dlf_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['dlf_test_value'][$i]))
					{	
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['dlf_test_value'][$i],
												'time'=>date('H:i:s',strtotime($post['dlf_test_time'][$i])),
												
									         );
								$this->db->insert('hms_opd_partograph_patient_drugs_fluid',$data);
						
					}
			}
			$total_test = count(array_filter($post['pb_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['pb_test_value'][$i]))
					{	
						if($post['pb_test_value_low'][$i]==''){
							$post['pb_test_value_low'][$i]='';
						}
						if($post['pb_test_value_high'][$i]==''){
							$post['pb_test_value_high'][$i]='';
						}
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['pb_test_value'][$i],
												'value_x'=>$post['pb_test_value_x'][$i],
												'value_low_bp'=>$post['pb_test_value_low'][$i],
												'value_high_bp'=>$post['pb_test_value_high'][$i],
												'time'=>date('H:i:s',strtotime($post['pb_test_time'][$i])),
												
									         );
								$this->db->insert('hms_opd_partograph_patient_pulse_bp',$data);
						
					}
			}
			$total_test = count(array_filter($post['tm_test_value']));
			for($i=0;$i<$total_test;$i++)
			{
					if(!empty($post['tm_test_value'][$i]))
					{	
								$data = array( 
												'parto_id'=>$data_id,
												'branch_id'=>$user_data['parent_id'],
												'value'=>$post['tm_test_value'][$i],
												'time'=>date('H:i:s',strtotime($post['tm_test_time'][$i])),
												
									         );
								$this->db->insert('hms_opd_partograph_patient_temp',$data);
						
					}
			}
		 	return $data_id;	
    }

    function template_format($data="",$branch_id='')
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_branch_partograph_setting.*');
    	$this->db->where($data);
    	if(!empty($branch_id))
    	{
    		$this->db->where('hms_branch_partograph_setting.branch_id = "'.$branch_id.'"');
    	}
    	else
    	{
    		$this->db->where('hms_branch_partograph_setting.branch_id = "'.$users_data['parent_id'].'"');
    	}
    	 
    	$this->db->from('hms_branch_partograph_setting');
    	$result=$this->db->get()->row();
    	return $result;

    }

    public function chart_one($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_foetal_heart');
		$query=$this->db->get();
		return $query->result();
	}

	public function chart_two($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_amniotic_fluid');
		$query=$this->db->get();
		return $query->result();
	}
	public function chart_three($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, value_x, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_cervic');
		$query=$this->db->get();
		return $query->result();
	}
	public function chart_four($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_contraction');
		$query=$this->db->get();
		return $query->result();
	}
	public function chart_five($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_drugs_fluid');
		$query=$this->db->get();
		return $query->result();
	}
	public function chart_six($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, value_x, value_low_bp, value_high_bp, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_pulse_bp');
		$query=$this->db->get();
		return $query->result();
	}
	public function chart_seven($parto_id, $branch_id)
    {// get foetal_heart 
		$this->db->select('value, time, created_date');
		$this->db->where('parto_id = "'.$parto_id.'"');
		$this->db->where('branch_id = "'.$branch_id.'"');
		$this->db->from('hms_opd_partograph_patient_temp');
		$query=$this->db->get();
		return $query->result();
	}
	
} 
?>