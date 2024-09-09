<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Path_token_model extends CI_Model 
{
	var $table = 'path_patient_to_token';
	var $column = array('path_patient_to_token.id','path_patient_to_token.token_no', 'path_test_booking.lab_reg_no','hms_patient.patient_name','path_patient_to_token.status'); 

	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$where ='(path_patient_to_token.status=0 OR path_patient_to_token.status=1 OR path_patient_to_token.status=3)';
		$date=date('Y-m-d');
		$user_data = $this->session->userdata('auth_users');
		 $type=$this->path_get_branch_token_type($user_data['parent_id']);
		//print_r($type);die();
		$this->db->select("path_patient_to_token.*,hms_patient.patient_name, path_test_booking.lab_reg_no");
		$this->db->from('path_patient_to_token');
		$this->db->join('hms_patient','hms_patient.id=path_patient_to_token.patient_id');
		$this->db->join('path_test_booking','path_patient_to_token.patient_id=path_test_booking.patient_id AND path_patient_to_token.booking_date=path_test_booking.booking_date');
		$this->db->where('path_patient_to_token.booking_date',$date); 
		$search = $this->session->userdata('path_token_search');

		if(isset($search) && !empty($search))
		{
            if(!empty($search['department_id']) && $search['department_id'] !='')
			{
				$this->db->where('path_patient_to_token.department_id',$search['department_id']);
			}
			//else if($type==1){
			//		$department_id=0;
					//$this->db->where('path_patient_to_token.department_id',0);
			//}
			if(!empty($search['search_type']) && $search['search_type'] !='' || $search['search_type']=='0')
			{
				$this->db->where('path_patient_to_token.status',$search['search_type']);
			}
			else{
		        $this->db->where($where);
			}
		}
		else{
		    $this->db->where($where);
		}
		if(!empty($type))
		{
		    $this->db->where('path_patient_to_token.type',$type);
		}
		$this->db->where('path_patient_to_token.branch_id',$user_data['parent_id']);
		$this->db->group_by('path_patient_to_token.id');
		//$this->session->unset_userdata('path_token_search');
	}



	function path_get_datatables()
	{
		$this->_get_datatables_query();		
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die();
		return $query->result();
	}

	function path_count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function path_count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

    public function path_update_token_status($id,$status)
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('status',$status);
			$this->db->where('id',$id);
			$result=$this->db->update('path_patient_to_token');
			return $result;
    	} 
    }

    public function path_get_branch_token_type($branch_id)
    {
    	$this->db->select('path_token_setting.type');
		$this->db->from('path_token_setting');
		$this->db->where('path_token_setting.branch_id',$branch_id);
		$result = $this->db->get()->result(); 
		$type=$result[0]->type;
		return $type;
    }
// Please write code above         
} 
?>