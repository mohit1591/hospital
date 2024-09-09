<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_collection_report_model extends CI_Model {

	var $table = 'hms_payment';
	var $column = array('hms_ipd_booking.ipd_no','hms_ipd_booking.admission_date', 'hms_patient.patient_name','hms_doctors.doctor_name','hms_ipd_booking.advance_payment');  
	var $order = array('hms_payment.id' => 'desc');
    //,'hms_department.department'
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$search_data = $this->session->userdata('ipd_collection_resport_search_data');
		
        $this->db->select("hms_ipd_booking.id,hms_payment.type, hms_payment.debit as paid_amount, hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.balance as blnce,hms_payment.section_id,hms_payment.paid_amount as paid_amount_payment, hms_payment.id as pay_id,hms_ipd_booking.ipd_no, hms_ipd_booking.admission_date,hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name");  
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_payment.parent_id AND hms_ipd_booking.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id');
		//$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');

$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');
		$this->db->from($this->table);
        $this->db->where('hms_payment.section_id',5);
        $this->db->where('hms_payment.debit > 0');
        $this->db->where('hms_payment.branch_id',$users_data['parent_id']);
        $this->db->where('hms_ipd_booking.is_deleted!=',2);
        //$this->db->where('hms_ipd_booking.is_deleted',0);  
            
        
        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_ipd_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']);
        }
         

        if(isset($search_data) && !empty($search_data))
        {
        	
            
            if(isset($search_data['start_date']) && !empty($search_data['start_date'])
        		)
        	{
        		$start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
        		$this->db->where('hms_payment.created_date >= "'.$start_date.'"');
        	}

        	if(isset($search_data['end_date']) && !empty($search_data['end_date'])
        		)
        	{
        		$end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
        		$this->db->where('hms_payment.created_date <= "'.$end_date.'"');
        	}

        	if(isset($search_data['attended_doctor']) && !empty($search_data['attended_doctor'])
        		)
        	{ 
        		$this->db->where('hms_ipd_booking.attend_doctor_id = "'.$search_data["attended_doctor"].'"');
            }
			
if(isset($search_data['referral_hospital']) && $search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_ipd_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif( isset($search_data['refered_id']) && $search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_ipd_booking.referral_doctor' ,$search_data['refered_id']);
            }
			

if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
        		)
        	{ 
        		$this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
        	}
			
			if(isset($search_data['patient_code']) && !empty($search_data['patient_code'])
        		)
        	{ 
        		$this->db->where('hms_patient.patient_code LIKE "'.$search_data["patient_code"].'%"');
        	}
			
			if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
        		)
        	{ 
        		$this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
        	}

            
			
			
			
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
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
		// echo $this->db->last_query();die;
		return $query->result();
	}

	function search_report_data()
	{
		$users_data = $this->session->userdata('auth_users');
		$search_data = $this->session->userdata('ipd_collection_resport_search_data'); 
	

        $this->db->select("hms_ipd_booking.id, hms_payment.debit as paid_amount, hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.balance as blnce,hms_payment.section_id,hms_payment.id as pay_id,hms_ipd_booking.ipd_no, hms_ipd_booking.admission_date,hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.id as patient_new_id,(CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name) END) as doctor_hospital_name");  
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_payment.parent_id'); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id');
        //$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');

        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');

        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',5);
        $this->db->where('hms_payment.debit > 0');
        $this->db->where('hms_ipd_booking.is_deleted',0); 

		if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_ipd_booking.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']);
        }

        if(isset($search_data) && !empty($search_data))
        {
            
            
            if(isset($search_data['start_date']) && !empty($search_data['start_date'])
                )
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
               // $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])). ' 23:59:59';
                //$this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

         if(isset($search_data['referral_hospital']) && $search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_ipd_booking.referral_hospital' ,$search_data['referral_hospital']);
            }
            elseif( isset($search_data['refered_id']) && $search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_ipd_booking.referral_doctor' ,$search_data['refered_id']);
            }

            if(isset($search_data['attended_doctor']) && !empty($search_data['attended_doctor'])
                )
            { 
                $this->db->where('hms_ipd_booking.attend_doctor_id = "'.$search_data["attended_doctor"].'"');
            }
            
            if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }
            
            if(isset($search_data['patient_code']) && !empty($search_data['patient_code'])
                )
            { 
                $this->db->where('hms_patient.patient_code LIKE "'.$search_data["patient_code"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }

           
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
        }
       // $this->db->group_by('hms_ipd_booking.id');
        //$this->db->limit($_POST['length'], $_POST['start']);
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