<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Opd_charge_entry_model extends CI_Model 
{
	var $table = 'hms_day_care_booking';
	 
	var $column = array('hms_day_care_booking.id','hms_day_care_booking.booking_code','hms_patient.patient_name','docs.doctor_name', 'hms_day_care_booking.appointment_date', 'hms_day_care_booking.booking_date', 'hms_day_care_booking.booking_status','hms_patient.patient_code', 'hms_patient.mobile_no','hms_patient.gender','hms_patient.address', 'hms_patient.father_husband', 'hms_patient.patient_email', 'ins_type.insurance_type', 'ins_cmpy.insurance_company', 'src.source', 'ds.disease', 'hms_hospital.hospital_name', 'spcl.specialization', 'docs.doctor_name', 'hms_day_care_booking.booking_time','hms_day_care_booking.validity_date', 'pkg.title', 'hms_day_care_booking.next_app_date', 'hms_day_care_booking.total_amount', 'hms_day_care_booking.net_amount', 'hms_day_care_booking.paid_amount', 'hms_day_care_booking.discount','hms_day_care_booking.policy_no'  ); 

	//var $column = array('hms_ipd_booking.id','hms_ipd_booking.ipd_no','hms_ipd_booking.patient_id','hms_patient.patient_name','hms_patient.mobile_no','hms_ipd_booking.admission_date','hms_doctor.doctor_name','hms_doctor.room_id','hms_doctor.bed_id','hms_patient.address', 'hms_ipd_booking.remark');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	

	public function save($opd_id="",$patient_id="")
	{
	    $users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			//edit
		}
		else
		{
			//add
			$opd_particular_billing_list = $this->session->userdata('opd_particular_charge_billing');
			//print '<pre>'; print_r($opd_particular_billing_list);die;
			if(!empty($opd_particular_billing_list))
			{
				 $this->db->where(array('opd_id'=>$opd_id,'patient_id'=>$patient_id,'type'=>5));
				 $this->db->delete('hms_opd_charge_entry');

				foreach($opd_particular_billing_list as $particular_billing)
				{
					if($post['opd_type']=='0') // normal
					{
						$total_price = $particular_billing['amount'];
						$panel_price = $particular_billing['amount'];
					}
					elseif($post['opd_type']=='1') // Emergency
					{
						$panel_charge = get_particular_charge($particular_billing['particular'],$post['pannel_type']);
						$panel_price = $panel_charge[0]['charge'];
						$total_price = $panel_price*$particular_billing['quantity'];
						
					}
					
					$net_price = $particular_billing['charges']*$particular_billing['quantity'];

					$particular_data = array(
						'branch_id'=>$users_data['parent_id'],
						'opd_id'=>$post['opd_id'],
						'patient_id'=>$post['patient_id'],
						'type'=>5,
						'particular_id'=>$particular_billing['particular'],
						'start_date'=>date('Y-m-d H:i:s',strtotime($particular_billing['s_date'])),//date('Y-m-d H:i:s'),
						'particular'=>$particular_billing['particulars'],
						'quantity'=>$particular_billing['quantity'],
						//'price'=>$particular_billing['amount'],
                        'price'=>$particular_billing['charges'],
						'panel_price'=>$panel_price,
						'net_price'=>$net_price,
						'status'=>1,
					);
					$this->db->insert('hms_opd_charge_entry',$particular_data);

	//	echo $this->db->last_query(); exit;

				}	

			}

			$this->session->unset_userdata('opd_particular_charge_billing');
        	$this->session->unset_userdata('opd_particular_payment');
        	
		}
      return true;	
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
			$this->db->update('hms_day_care_booking');
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
			$this->db->update('hms_day_care_booking');
    	} 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_purchase');
        $result = $query->result(); 
        return $result; 
    } 

   
    function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }

    public function get_patient_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_patient.*');
		$this->db->from('hms_patient'); 
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
		$this->db->where('hms_patient.id',$id);
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function attended_doctor_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (1,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		// print '<pre>'; print_r($result);
		return $result; 
	}

	public function assigned_doctor_list()
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		// print '<pre>'; print_r($result);
		return $result; 
	}

	public function aasigned_doctor_by_id($id=''){
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		if(!empty($id)){
		$this->db->where('ipd_booking_id',$id);
		}
		$this->db->order_by('id','ASC');
		$this->db->group_by('doctor_id');
		$this->db->where('branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_ipd_assign_doctor');
		$result = $query->result(); 
		// echo $this->db->last_query();die;
		return $result; 
	}

} 
?>