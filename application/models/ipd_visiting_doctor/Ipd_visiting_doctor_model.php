<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_visiting_doctor_model extends CI_Model {

	var $table = 'hms_ipd_patient_to_charge';
	var $column = array('hms_ipd_booking.ipd_no','hms_ipd_booking.admission_date', 'hms_patient.patient_name','hms_doctors.doctor_name','hms_ipd_booking.advance_payment');  
	var $order = array('hms_ipd_patient_to_charge.id' => 'desc');
    //,'hms_department.department'
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$search_data = $this->session->userdata('ipd_visiting_search_data');
		
        $this->db->select("hms_ipd_booking.id,hms_ipd_booking.ipd_no, hms_ipd_booking.admission_date,hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id,hms_ipd_patient_to_charge.start_date,hms_ipd_patient_to_charge.net_price");  
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_to_charge.ipd_id AND hms_ipd_booking.is_deleted=0'); 
		
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_patient_to_charge.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_patient_to_charge.doctor_id','left');
        $this->db->from('hms_ipd_patient_to_charge');
        $this->db->where('hms_ipd_patient_to_charge.type',5);
        $this->db->where('hms_ipd_patient_to_charge.doctor_id!=""');
        $this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
        $this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
        if(isset($search_data) && !empty($search_data))
        {
            if(isset($search_data['from_date']) && !empty($search_data['from_date'])
        		)
        	{
        		$start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
        		$this->db->where('hms_ipd_patient_to_charge.start_date >= "'.$start_date.'"');
        	}

        	if(isset($search_data['end_date']) && !empty($search_data['end_date'])
        		)
        	{
        		$end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
        		$this->db->where('hms_ipd_patient_to_charge.start_date <= "'.$end_date.'"');
        	}
        	if(isset($search_data['doctor_id']) && !empty($search_data['doctor_id'])
        		)
        	{
        		$this->db->where('hms_ipd_patient_to_charge.doctor_id',$search_data['doctor_id']);
        	}
        	else
        	{
        	    $this->db->where('hms_ipd_patient_to_charge.doctor_id!=""');
        	}

			
        }
        
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

	function get_datatables($branch_id='')
	{
		$this->_get_datatables_query($branch_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
	}

	function search_report_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$search_data = $this->session->userdata('ipd_visiting_search_data');
		
        $this->db->select("hms_ipd_booking.id,hms_ipd_booking.ipd_no, hms_ipd_booking.admission_date,hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id,hms_ipd_patient_to_charge.start_date,hms_ipd_patient_to_charge.net_price");  
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_patient_to_charge.ipd_id AND hms_ipd_booking.is_deleted=0'); 
		
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_patient_to_charge.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_patient_to_charge.doctor_id','left');
        $this->db->from('hms_ipd_patient_to_charge');
        $this->db->where('hms_ipd_patient_to_charge.type',5);
        $this->db->where('hms_ipd_patient_to_charge.doctor_id!=""');
        $this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
        $this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
        if(isset($search_data) && !empty($search_data))
        {
            if(isset($search_data['from_date']) && !empty($search_data['from_date'])
        		)
        	{
        		$start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
        		$this->db->where('hms_ipd_patient_to_charge.start_date >= "'.$start_date.'"');
        	}

        	if(isset($search_data['end_date']) && !empty($search_data['end_date'])
        		)
        	{
        		$end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
        		$this->db->where('hms_ipd_patient_to_charge.start_date <= "'.$end_date.'"');
        	}
        	if(isset($search_data['doctor_id']) && !empty($search_data['doctor_id'])
        		)
        	{
        		$this->db->where('hms_ipd_patient_to_charge.doctor_id',$search_data['doctor_id']);
        	}
        	else
        	{
        	    $this->db->where('hms_ipd_patient_to_charge.doctor_id!=""');
        	}

			
        }
        $this->db->order_by('hms_ipd_patient_to_charge.id','desc');
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

public function get_all_detail_print($id="",$section_id="")
    {
       if(!empty($id))
        { 
            $result_booking=array();
            $user_data = $this->session->userdata('auth_users');
            
                //IPD Booking

                $user_data = $this->session->userdata('auth_users');
                $this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_ipd_booking.ipd_no as recepit_no,hms_ipd_booking.admission_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.section_id = hms_payment.section_id AND sub_pay.parent_id = hms_payment.parent_id AND  sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.section_id = hms_payment.section_id AND total_pay.parent_id = hms_payment.parent_id AND  total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date,hms_ipd_panel_type.panel_type,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category"); 
                $this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
                $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id = hms_patient.id','left'); 
                $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
                $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
                $this->db->join('hms_ipd_panel_company','hms_ipd_panel_company.id = hms_ipd_booking.panel_name','left');
                $this->db->join('hms_ipd_panel_type','hms_ipd_panel_type.id = hms_ipd_booking.panel_type','left');
                $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
                $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
                $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
                $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
                
                $this->db->join('hms_users','hms_users.id = hms_payment.created_by');
                $this->db->where('hms_payment.id = "'.$id.'"'); 
                $this->db->where('hms_payment.section_id',5); 
                //$this->db->where('hms_ipd_booking.discharge_status =1'); 
                $this->db->group_by('hms_patient.id','DESC');  
                $this->db->from('hms_patient');
                $result_patient['sales_list']= $this->db->get()->result();
                //echo $this->db->last_query(); exit;
        
            return $result_patient;

            
        } 
    }

    function get_balance_previous($id="",$patient_id="",$payment_date="",$section_id="")
    {
        $this->db->select("sum(hms_payment.credit-hms_payment.debit) as balance"); 
            $this->db->where('hms_payment.created_date <= (select created_date from hms_payment as sub_pay where sub_pay.id="'.$id.'")');
            $this->db->where('hms_payment.patient_id',$patient_id);
            $this->db->where('hms_payment.section_id',5);
            $this->db->from('hms_payment');
            $result_patient['balance'] = $this->db->get()->result();
            //echo $this->db->last_query();die;
            return $result_patient;
    }

    function template_format($data="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_branch_template.*');
        $this->db->where($data);
        //$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->from('hms_print_branch_template');
        $query=$this->db->get()->row();
        //print_r($query);exit;
        return $query;

    }

}
?>