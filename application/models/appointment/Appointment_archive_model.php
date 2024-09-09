<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Appointment_archive_model extends CI_Model 
{
	var $table = 'hms_opd_booking';
	var $column = array('hms_opd_booking.appointment_code','hms_opd_booking.appointment_date','hms_opd_booking.type','hms_opd_booking.patient_id','hms_opd_booking.attended_doctor', 'hms_opd_booking.confirm_date','hms_opd_booking.total_amount','hms_opd_booking.booking_status','hms_opd_booking.created_date','hms_opd_booking.modified_date','hms_opd_booking.status');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_opd_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.patient_code as patient_reg_no, hms_patient.mobile_no, (CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband, sim.simulation as father_husband_simulation,hms_patient.patient_email, ins_type.insurance_type, ins_cmpy.insurance_company, src.source as patient_source, ds.disease , (CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name, spcl.specialization,docs.doctor_name");
			$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
			$this->db->join('hms_disease','hms_disease.id= hms_opd_booking.diseases','left');
			$this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');
			$this->db->join('hms_insurance_type as ins_type','ins_type.id=hms_opd_booking.insurance_type_id', 'left');
			$this->db->join('hms_insurance_company as ins_cmpy','ins_cmpy.id=hms_opd_booking.ins_company_id', 'left');

			$this->db->join('hms_patient_source as src', 'src.id=hms_opd_booking.source_from', 'Left');
			$this->db->join('hms_disease as ds', 'ds.id=hms_opd_booking.diseases', 'left');

			$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
			$this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
			$this->db->join('hms_specialization as spcl','spcl.id = hms_opd_booking.specialization_id','left');

			$this->db->join('hms_doctors as docs','docs.id = hms_opd_booking.attended_doctor','left');

			$this->db->where('hms_opd_booking.is_deleted','1');
			$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');

		

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
			$this->db->update('hms_opd_booking');
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
			$this->db->update('hms_opd_booking');
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
			$this->db->update('hms_opd_booking');
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
			$this->db->update('hms_opd_booking');

    	} 
    }

    
} 
?>