<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Token_model extends CI_Model 
{
	var $table = 'hms_patient_to_token';
	var $column = array('hms_patient_to_token.id','hms_patient_to_token.token_no', 'hms_opd_booking.booking_code','hms_patient.patient_name','hms_patient_to_token.status'); 

	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$where ='(hms_patient_to_token.status=0 OR hms_patient_to_token.status=1 OR hms_patient_to_token.status=3)';
		$date=date('Y-m-d');
		$user_data = $this->session->userdata('auth_users');
		$type=$this->get_branch_token_type($user_data['parent_id']);
		
		$this->db->select("hms_patient_to_token.*,hms_patient.patient_name, hms_opd_booking.booking_code");
		$this->db->from('hms_patient_to_token');
		$this->db->join('hms_patient','hms_patient.id=hms_patient_to_token.patient_id');
		$this->db->join('hms_opd_booking','hms_patient_to_token.patient_id=hms_opd_booking.patient_id AND hms_patient_to_token.booking_date=hms_opd_booking.booking_date AND hms_opd_booking.type=2');
		$this->db->where('hms_patient_to_token.booking_date',$date); 
		$search = $this->session->userdata('token_search');
		//print_r($search);die();
		if(isset($search) && !empty($search))
		{			
            if(!empty($search['doctor_id']) && $search['doctor_id'] !='' && $type==1 )
			{
				$this->db->where('hms_patient_to_token.doctor_id',$search['doctor_id']);
			}
			else if($type==1){
					$doctor_id=0;
					$this->db->where('hms_patient_to_token.doctor_id',$doctor_id);
			}
            if(!empty($search['specialization_id']) && $search['specialization_id'] !='')
			{
				$this->db->where('hms_patient_to_token.specialization_id',$search['specialization_id']);
			}
			else if($type==2){
					$depart=0;
					$this->db->where('hms_patient_to_token.specialization_id',$depart);
			}
			if(!empty($search['search_type']) && $search['search_type'] !='' || $search['search_type']=='0')
			{
				$this->db->where('hms_patient_to_token.status',$search['search_type']);
			}
			else{
		        $this->db->where($where);
			}
		}
		else{
		    $this->db->where($where);
		}
		$this->db->where('hms_patient_to_token.type',$type);
		$this->db->where('hms_patient_to_token.branch_id',$user_data['parent_id']);
		//$this->session->unset_userdata('token_search');
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

    public function update_token_status($id,$status)
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('status',$status);
			$this->db->where('id',$id);
			$result=$this->db->update('hms_patient_to_token');
			return $result;
    	} 
    }

    public function get_branch_token_type($branch_id)
    {
    	$this->db->select('hms_token_setting.type');
		$this->db->from('hms_token_setting');
		$this->db->where('hms_token_setting.branch_id',$branch_id);
		$result = $this->db->get()->result(); 
		$type=$result[0]->type;
		return $type;
    }
// Please write code above         
} 
?>