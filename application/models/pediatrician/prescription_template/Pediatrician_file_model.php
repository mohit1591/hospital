<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_file_model extends CI_Model {

	var $table = 'hms_pediatrician_prescription_files';
	var $column = array('hms_pediatrician_prescription_files.id','hms_pediatrician_prescription_files.prescription_files','hms_pediatrician_prescription_files.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_pediatrician_prescription_files.*, hms_pediatrician_prescription_files.prescription_files"); 
		$this->db->from($this->table);  
        $this->db->where('hms_pediatrician_prescription_files.branch_id = "'.$users_data['parent_id'].'"');
       
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

	function get_datatables($prescription_id='')
	{
		$this->_get_datatables_query();
		$this->db->where('prescription_id',$prescription_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function count_filtered($prescription_id='')
	{
		$this->_get_datatables_query();
		$this->db->where('prescription_id',$prescription_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($prescription_id='')
	{
		$this->db->from($this->table);
		$this->db->where('prescription_id',$prescription_id);
		return $this->db->count_all_results();
	}
    
    public function sign_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('doctor_id','ASC'); 
    	$query = $this->db->get('hms_pediatrician_prescription_files');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_signature.*');
		$this->db->from('hms_signature'); 
		$this->db->where('hms_signature.id',$id); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($filename="")
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'doctor_id'=>$post['doctor_id'],
					'dept_id'=>$post['dept_id'],
					'signature'=>$post['signature'],
					//'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
			   $this->db->set('sign_img',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_signature',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('sign_img',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_signature',$data);               
		} 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{ 
    		$this->db->select('*');  
			$this->db->where('id',$id);
			$query = $this->db->get('hms_pediatrician_prescription_files');
			$result = $query->result();
			if(!empty($result))
			{
			  if(!empty($result[0]->prescription_files))
			  {
			  	unlink(DIR_UPLOAD_PATH.'pediatrician/prescription/'.$result[0]->prescription_files);
			  }	 
			}  

			$this->db->where('id',$id);
			$this->db->delete('hms_pediatrician_prescription_files'); 
    	} 
    }

    public function deleteall($ids=array())
    {
    	if(!empty($ids))
    	{ 

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			$this->db->select('*');  
				$this->db->where('id',$id);
				$query = $this->db->get('hms_pediatrician_prescription_files');
				$result = $query->result();
				if(!empty($result))
				{
				  if(!empty($result[0]->prescription_files))
				  {
				  	unlink(DIR_UPLOAD_PATH.'pediatrician/prescription/'.$result[0]->prescription_files);
				  }	 
				}

    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$banch_ids = implode(',', $id_list); 
			$this->db->where('id IN ('.$banch_ids.')');
			$this->db->delete('hms_pediatrician_prescription_files'); 
    	} 
    }

    public function doctors_list($id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $id_set = "";
        if(!empty($id) && $id>0)
        {
          $id_set = ' WHERE id != "'.$id.'"';
        }
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('doctor_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('id NOT IN (select id from hms_signature '.$id_set.')');
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query();
        return $result; 
    }



    public function get_by_ids($id)
	{
		$this->db->select("hms_pediatrician_prescription.*,hms_opd_booking.booking_code,hms_opd_booking.booking_time,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 
		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_pediatrician_prescription.booking_id','left');
		
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id'); 
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->where('hms_pediatrician_prescription.id',$id);
		$this->db->where('hms_pediatrician_prescription.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
		
	}
	public function get_pediatrician_prescription($prescription_id='')
	{
		$this->db->select("hms_pediatrician_prescription_patient_pres.*"); 
		$this->db->from('hms_pediatrician_prescription_patient_pres'); 
		$this->db->where('hms_pediatrician_prescription_patient_pres.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	public function get_pediatrician_test($prescription_id='')
	{
		$this->db->select("hms_pediatrician_prescription_patient_test.*"); 
		$this->db->from('hms_pediatrician_prescription_patient_test'); 
		$this->db->where('hms_pediatrician_prescription_patient_test.prescription_id',$prescription_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
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
public function get_detail_by_prescription_id($prescription_id)
	{
		$this->db->select("hms_pediatrician_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_opd_booking.booking_code,hms_opd_booking.booking_date,hms_opd_booking.specialization_id,hms_opd_booking.referral_doctor,hms_opd_booking.attended_doctor,hms_opd_booking.booking_date,hms_opd_booking.booking_time,hms_pediatrician_prescription.patient_bp as patientbp,hms_pediatrician_prescription.patient_temp as patienttemp,hms_pediatrician_prescription.patient_weight as patientweight,hms_pediatrician_prescription.patient_height as patientpr,hms_pediatrician_prescription.patient_spo2 as patientspo,hms_pediatrician_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 



		$this->db->from('hms_pediatrician_prescription'); 
		$this->db->where('hms_pediatrician_prescription.id',$prescription_id);
		$this->db->join('hms_patient','hms_patient.id=hms_pediatrician_prescription.patient_id','left');
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_pediatrician_prescription.booking_id','left'); 
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

}
?>