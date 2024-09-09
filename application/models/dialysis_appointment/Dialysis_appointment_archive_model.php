<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_appointment_archive_model extends CI_Model {



	var $table = 'hms_dialysis_appointment';
	var $column = array('hms_dialysis_appointment.id','hms_dialysis_appointment.ipd_id','hms_patient.patient_code','hms_patient.patient_name','hms_dialysis_appointment.dialysis_date','hms_dialysis_appointment.dialysis_time','hms_doctors.doctor_name','hms_ipd_room_category.room_category','hms_ipd_rooms.room_no','hms_ipd_room_to_bad.bad_no');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('dialysis_booking_serach');
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dialysis_appointment.*,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code"); 
		$this->db->from($this->table); 
        $this->db->where('hms_dialysis_appointment.is_deleted','1');
        if($users_data['users_role']==4)
		{
		$this->db->where('hms_dialysis_appointment.patient_id = "'.$users_data['parent_id'].'"');
		}
		elseif($users_data['users_role']==3)
		{
			$this->db->where('hms_dialysis_appointment.referral_doctor = "'.$users_data['parent_id'].'"');
		}
		else
		{
		$this->db->where('hms_dialysis_appointment.branch_id = "'.$users_data['parent_id'].'"');	
		}
        
        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_appointment.patient_id','left');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_dialysis_appointment.ipd_id','left'); 
         $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_appointment.doctor_id','left');
         $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');
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

	public function restore($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_dialysis_appointment');
    	} 
    }

    public function restoreall($ids=array())
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
    		$emp_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$emp_ids.')');
			$this->db->update('hms_dialysis_appointment');
    	} 
    }

    public function trash($id="")
    {
    	if(!empty($id) && $id>0)
    	{  
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_dialysis_appointment');

    	} 
    }

    public function trashall($ids=array())
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
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_dialysis_appointment');
    	} 
    }
 

}
?>