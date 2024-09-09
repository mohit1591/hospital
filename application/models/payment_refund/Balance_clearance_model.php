<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Balance_clearance_model extends CI_Model 
{
var $column = array('hms_payment.credit','hms_payment.debit', 'hms_doctors.doctor_name');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    
	
	public function patient_to_balclearlist($type=1)
	{
		$post = $this->input->post();
		//print'<pre>';print_r($post);die;
		$users_data = $this->session->userdata('auth_users');
		$balance_search_data = $this->session->userdata('balance_search_data');
		$result= array(); 
		if(isset($post) || !empty($post))
		{ 
			 
			$this->db->select('hms_patient.*,hms_payment.branch_id, (CASE WHEN hms_payment.section_id = 1 THEN "Pathology" WHEN hms_payment.section_id = 2 THEN "OPD" WHEN hms_payment.section_id=3 THEN "Medicine" WHEN hms_payment.section_id=4 THEN "Billing" WHEN hms_payment.section_id=5 THEN "IPD" ELSE "" END) as department, hms_payment.created_date as date, hms_payment.discount_amount as discount, hms_payment.parent_id, hms_payment.section_id,hms_payment.type, (select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id) as balance');
			$this->db->from('hms_patient');
			$this->db->where('(select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id) > 0');
			//$this->db->where('(select sum(hms_payment.credit)-sum(hms_payment.debit) from hms_payment as payment where patient_id = hms_patient.id) > 0');
			$this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id'); 
			$this->db->join('hms_doctors','hms_doctors.id=hms_payment.doctor_id'); 
			$this->db->where('hms_doctors.doctor_pay_type',1);
			if(!empty($post['patient_name']))
			{ 
			   $this->db->like('hms_patient.patient_name',$post['patient_name']);
			}
			if(!empty($post['mobile_no']))
			{
			   $this->db->like('hms_patient.mobile_no',$post['mobile_no']);
			}
			

			if(isset($post['sub_branch_id']) && !empty($post['sub_branch_id']))
			{
			$this->db->where('hms_payment.branch_id',$post['sub_branch_id']);
			} 
			else
			{
			$this->db->where('hms_payment.branch_id',$users_data['parent_id']);
			}

			if(isset($balance_search_data) && !empty($balance_search_data))
			{ 
				$imp_sec_id = implode(',', $balance_search_data['section_id']);
				$this->db->where('hms_payment.section_id IN ('.$imp_sec_id.')');
			}

			$this->db->group_by('hms_payment.parent_id, hms_payment.section_id');
			$this->db->order_by('hms_payment.id','DESC');
			$query = $this->db->get();
			//echo $this->db->last_query();
			$result = $query->result_array();

		}
		return $result;

	}

	public function payment_to_branch()
	{
		
		$post = $this->input->post();
		$users_data = $this->session->userdata('auth_users');
		if(isset($post) && !empty($post))
		{
			$time_c= date("H:i:s");
			$paid_date = date('Y-m-d', strtotime($post['paid_date']));
			$data = array(
							'branch_id'=>$post['branch_id'],
							'parent_id'=>$post['parent_id'], 
							'section_id'=>$post['section_id'], 
							'patient_id'=>$post['patient_id'], 
							'debit'=>$post['balance'],
							'pay_mode'=>$post['payment_mode'],
							//'bank_name'=>$post['bank_name'],
							//'cheque_no'=>$post['cheque_no'],
							//'transection_no'=>$post['transection_no'],
							//'card_no'=>$post['card_no'],
							'created_by'=>$users_data['id'],
							'created_date'=>$paid_date.' '.$time_c
							 );
			 
			$this->db->insert('hms_payment',$data);
			$insert_id= $this->db->insert_id();

			/*add sales banlk detail*/
              if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
                $data_field_value= array(
                'field_value'=>$post['field_name'][$i],
                'field_id'=>$post['field_id'][$i],
                'type'=>5,
                'section_id'=>8,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$users_data['parent_id'],
                'parent_id'=>$insert_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$users_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*add sales banlk detail*/  
			//echo $this->db->last_query(); exit;
			return $insert_id;
			
		}

	}

	public function patient_balance_receipt_data($id="",$section_id="")
	{
       
       // echo $this->session->userdata('balance');die;
		if(!empty($id))
		{ 
			$result_booking=array();
			$user_data = $this->session->userdata('auth_users');
			
			if($section_id==1)
			{
				$result_booking=array();
		    	$user_data = $this->session->userdata('auth_users');
				$this->db->select("path_test_booking.lab_reg_no, path_test_booking.booking_date,  path_test_booking.attended_doctor,  path_test_booking.referral_doctor,  hms_patient.*,hms_simulation.simulation,hms_payment_mode.payment_mode,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.parent_id = hms_payment.parent_id) as balance, 
					(select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount, hms_payment.pay_mode as payment_mode"); 
				$this->db->join('hms_patient','hms_patient.id = hms_payment.patient_id','left');
				$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
				$this->db->join('path_test_booking','path_test_booking.patient_id = hms_patient.id','left');     
				$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
				$this->db->where('hms_payment.id = "'.$id.'"'); 
				$this->db->order_by('path_test_booking.id','DESC');  
				$this->db->from('hms_payment');
				$result_booking['booking_list'] = $this->db->get()->result();
				$billing_particuler_arr = array('test_id'=>'','test_name'=>'Balance Clearance', 'amount'=>$result_booking['booking_list'][0]->debit);
				$object = (object) $billing_particuler_arr; 
				$result_booking['booking_list']['test_booking_list'][0] = $object;
				//echo $this->db->last_query(); exit;
				return $result_booking;
		
			}
			if($section_id==3)
			{
			$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_medicine_sale.sale_no as recepit_no,hms_medicine_sale.sale_date as date,hms_medicine_sale.remarks as remk,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date"); 
			$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
			$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
			$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
			
			$this->db->join('hms_medicine_sale','hms_medicine_sale.patient_id = hms_patient.id','left'); 
			$this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
			$this->db->join('hms_users','hms_users.id = hms_payment.created_by');	 

			$this->db->where('hms_payment.id = "'.$id.'"'); 
			$this->db->group_by('hms_patient.id','DESC');  
			$this->db->from('hms_patient');
			$result_patient['sales_list'] = $this->db->get()->result();
			//echo $this->db->last_query(); exit;
			return $result_patient;
			}
			elseif($section_id==2)
			{
				//OPD Booking

				$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_opd_booking.booking_code as recepit_no,hms_opd_booking.booking_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date"); 
				$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
			$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
			$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode');
				
			$this->db->join('hms_opd_booking','hms_opd_booking.patient_id = hms_patient.id','left'); 
			$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left'); 
			$this->db->join('hms_users','hms_users.id = hms_payment.created_by');
			$this->db->where('hms_payment.id = "'.$id.'"'); 
			$this->db->group_by('hms_patient.id','DESC');  
			$this->db->from('hms_patient');
			$result_patient['sales_list'] = $this->db->get()->result();
		    
			return $result_patient;

			}
			elseif($section_id==4)
			{
				//Billing

				$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_opd_booking.reciept_code as recepit_no,hms_opd_booking.booking_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date"); 
			$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
			$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
		    $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
			
			$this->db->join('hms_opd_booking','hms_opd_booking.patient_id = hms_patient.id','left'); 
			$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left'); 
			$this->db->join('hms_users','hms_users.id = hms_payment.created_by');
			$this->db->where('hms_payment.id = "'.$id.'"'); 
			$this->db->group_by('hms_patient.id','DESC');  
			$this->db->from('hms_patient');
			$result_patient['sales_list'] = $this->db->get()->result();
			//echo $this->db->last_query(); exit;
			return $result_patient;
			}

			elseif($section_id==5)
			{
				//IPD Booking

				$user_data = $this->session->userdata('auth_users');
				$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_ipd_booking.ipd_no as recepit_no,hms_ipd_booking.admission_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date,hms_ipd_panel_type.panel_type,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category"); 
				$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
				$this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id = hms_patient.id','left'); 
				$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
				
				//$this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id','left');
				//$this->db->join('hms_ipd_packages','hms_ipd_packages.id = hms_ipd_booking.package_id','left');
				$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
				$this->db->join('hms_ipd_panel_company','hms_ipd_panel_company.id = hms_ipd_booking.panel_name','left');
				$this->db->join('hms_ipd_panel_type','hms_ipd_panel_type.id = hms_ipd_booking.panel_type','left');
				$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
				
				$this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
				$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
				$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
				
				$this->db->join('hms_users','hms_users.id = hms_payment.created_by');
				$this->db->where('hms_payment.id = "'.$id.'"'); 
				//$this->db->where('hms_ipd_booking.discharge_status =1'); 
				$this->db->group_by('hms_patient.id','DESC');  
				$this->db->from('hms_patient');

				
				$result_patient['sales_list']= $this->db->get()->result();
				//echo $this->db->last_query(); exit;
		
			return $result_patient;

			}
		} 
	}

	public function get_balance_previous($id="",$patient_id="",$payment_date="",$section_id=""){
          $date_new= date('Y-m-d',strtotime($payment_date));
		  $new_payment_date=$date_new.' '.date('H:i:s');
		if($section_id==3)
			{
			$this->db->select("sum(hms_payment.credit-hms_payment.debit) as balance"); 
			$this->db->where('hms_payment.created_date < (select created_date from hms_payment as sub_pay where sub_pay.id="'.$id.'")');
			$this->db->where('hms_payment.patient_id',$patient_id);
			$this->db->where('hms_payment.section_id',3);
			$this->db->from('hms_payment');
			$result_patient['balance'] = $this->db->get()->result();
			//echo $this->db->last_query();die;
			return $result_patient;
			
			}
			elseif($section_id==2)
			{
			$this->db->select("sum(hms_payment.credit-hms_payment.debit) as balance"); 
			$this->db->where('hms_payment.created_date < (select created_date from hms_payment as sub_pay where sub_pay.id="'.$id.'")');
			$this->db->where('hms_payment.patient_id',$patient_id);
			$this->db->where('hms_payment.section_id',2);
			$this->db->from('hms_payment');
			$result_patient['balance'] = $this->db->get()->result();

			return $result_patient;
			}
			elseif($section_id==4)
			{
			$this->db->select("sum(hms_payment.credit-hms_payment.debit) as balance"); 
			$this->db->where('hms_payment.created_date < (select created_date from hms_payment as sub_pay where sub_pay.id="'.$id.'")');
			$this->db->where('hms_payment.patient_id',$patient_id);
			$this->db->where('hms_payment.section_id',4);
			$this->db->from('hms_payment');
			$result_patient['balance'] = $this->db->get()->result();

			return $result_patient;
			}
			elseif($section_id==5)
			{
			$this->db->select("sum(hms_payment.credit-hms_payment.debit) as balance"); 
			$this->db->where('hms_payment.created_date < (select created_date from hms_payment as sub_pay where sub_pay.id="'.$id.'")');
			$this->db->where('hms_payment.patient_id',$patient_id);
			$this->db->where('hms_payment.section_id',5);
			$this->db->from('hms_payment');
			$result_patient['balance'] = $this->db->get()->result();

			return $result_patient;
			}
		 
		 
			
	}
	//12 july 2017
	public function patient_balance_receipt_data_old($id="",$type="")
	{
       // echo $this->session->userdata('balance');die;
		if(!empty($id))
		{ 
			 $result_booking=array();
			 $user_data = $this->session->userdata('auth_users');

			if($type==1){

				$this->db->select("hms_patient.*,hms_patient.patient_name as name,hms_medicine_sale.sale_no as recepit_no,hms_medicine_sale.sale_date as date,hms_doctors.doctor_name,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount"); 
			  $this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
			  $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
			   
			    $this->db->join('hms_medicine_sale','hms_medicine_sale.patient_id = hms_patient.id','left'); 
			     $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left'); 
			    
			$this->db->where('hms_payment.doctor_id','0'); 
			$this->db->where('hms_payment.parent_id','0'); 
			$this->db->where('hms_payment.id = "'.$id.'"'); 
			$this->db->group_by('hms_patient.id','DESC');  
			$this->db->from('hms_patient');
			$result_patient['sales_list'] = $this->db->get()->result();
			}
			if($type==2){
				$this->db->select("hms_medicine_vendors.*,hms_medicine_vendors.name as name,hms_medicine_vendors.vendor_id as code,hms_payment.pay_mode,hms_medicine_purchase.purchase_date as date,hms_medicine_purchase.invoice_id as recepit_no,hms_medicine_vendors.mobile as mobile_no,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount"); 
			        $this->db->join('hms_payment','hms_payment.vendor_id = hms_medicine_vendors.id','left');
                $this->db->join('hms_medicine_purchase','hms_medicine_purchase.vendor_id = hms_medicine_vendors.id','left'); 
				$this->db->where('hms_payment.doctor_id','0'); 
				$this->db->where('hms_payment.parent_id','0'); 
				$this->db->where('hms_payment.id = "'.$id.'"'); 
				$this->db->group_by('hms_medicine_vendors.id','DESC');  
				$this->db->from('hms_medicine_vendors');
					$result_patient['sales_list'] = $this->db->get()->result();
			
			}
          return $result_patient;
			
		} 
	}
	function template_format($data="")
	{
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->where('printer_paper_type!=0');
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query();
    	//print_r($query);exit;
    	return $query;

    }
	
} 
?>