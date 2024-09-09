<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipd_charge_entry_model extends CI_Model 
{
	var $table = 'hms_ipd_booking';
	 

	var $column = array('hms_ipd_booking.id','hms_ipd_booking.ipd_no','hms_ipd_booking.patient_id','hms_patient.patient_name','hms_patient.mobile_no','hms_ipd_booking.admission_date','hms_doctor.doctor_name','hms_doctor.room_id','hms_doctor.bed_id','hms_patient.address', 'hms_ipd_booking.remark');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	

	public function save($ipd_id="",$patient_id="")
	{
	    $users_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>"; print_r($post); exit;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{
			//edit
		}
		else
		{
			//add
			$ipd_particular_billing_list = $this->session->userdata('ipd_particular_charge_billing');
			//print '<pre>'; print_r($ipd_particular_billing_list);die;
			if(!empty($ipd_particular_billing_list))
			{
				 
				 //$this->db->delete('hms_ipd_patient_to_charge');
				
				$data_charge = array("is_deleted"=>1);
				
				$this->db->set('modified_by',$users_data['id']);
                $this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>5));
                $this->db->update('hms_ipd_patient_to_charge',$data_charge);
				 

				foreach($ipd_particular_billing_list as $particular_billing)
				{
					if($post['patient_type']=='1')
					{
						$total_price = $particular_billing['amount'];
						$panel_price = $particular_billing['amount'];
					}
					elseif($post['patient_type']=='2')
					{
						$panel_charge = get_particular_charge($particular_billing['particular'],$post['panel_name']);
						if(empty($panel_charge[0]['charge']))
						{
						   $panel_amount = $particular_billing['amount'];
						}
						else
						{
						     $panel_amount = $panel_charge[0]['charge'];
						}
						$panel_price = $panel_amount; //$panel_charge[0]['charge'];
						$total_price = $panel_price*$particular_billing['quantity'];
					}
					$particular_data = array(
						'branch_id'=>$users_data['parent_id'],
						'ipd_id'=>$post['ipd_id'],
						'patient_id'=>$post['patient_id'],
						'type'=>5,
						'doctor'=>$particular_billing['doctor'],
						'doctor_id'=>$particular_billing['doctor_id'],
						'particular_id'=>$particular_billing['particular'],
						'start_date'=>date('Y-m-d H:i:s',strtotime($particular_billing['s_date'])),//date('Y-m-d H:i:s'),
						'particular'=>$particular_billing['particulars'],
						'quantity'=>$particular_billing['quantity'],
						//'price'=>$particular_billing['amount'],
                                                'price'=>$particular_billing['charges'],
						'panel_price'=>$panel_price,
						'net_price'=>$total_price,
						'status'=>1,
					);
					$this->db->insert('hms_ipd_patient_to_charge',$particular_data);
					//echo $this->db->last_query(); exit;
				}	

			}

			$this->session->unset_userdata('ipd_particular_charge_billing');
        	$this->session->unset_userdata('ipd_particular_payment');
        	
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
			$this->db->update('hms_ipd_booking');
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
			$this->db->update('hms_ipd_booking');
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