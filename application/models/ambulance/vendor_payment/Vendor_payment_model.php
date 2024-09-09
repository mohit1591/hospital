<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vendor_payment_model extends CI_Model 
{
var $column = array('hms_payment.credit','hms_payment.debit', 'hms_doctors.doctor_name');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    
	
public function vendor_to_balclearlist($type=1)
	{
		$post = $this->input->post();
		//print'<pre>';print_r($post);die;
		$users_data = $this->session->userdata('auth_users');
		//$search_ven = $this->session->userdata('vendor_pay');
		//print_r($search);die();

		$result= array(); 
		if(isset($post) || !empty($post))
		{
		  
		$this->db->select('hms_medicine_vendors.*, (sum(hms_ambulance_booking.vendor_charge)) as balance, hms_medicine_vendors.vendor_id as code');   
		    
    	//$this->db->select('hms_medicine_vendors.*, (sum(hms_ambulance_booking.vendor_charge)-sum(0.00)) as balance, hms_medicine_vendors.vendor_id as code');    
	
		$this->db->from('hms_medicine_vendors');
		$this->db->join('hms_ambulance_booking','hms_ambulance_booking.vendor_id=hms_medicine_vendors.id');
		
	   //$this->db->join('hms_payment','hms_payment.vendor_id=hms_medicine_vendors.id');
		//$this->db->where('hms_payment.type',12);
		
		$this->db->where('hms_medicine_vendors.vendor_type',5);
		$this->db->group_by('hms_ambulance_booking.vendor_id');
		$this->db->order_by('hms_ambulance_booking.id','DESC');
		$this->db->where('hms_ambulance_booking.is_deleted','0'); 

			if(!empty($post['vendor_name']))
			{ 
			$this->db->like('hms_medicine_vendors.name',$post['vendor_name']);
			}
			if(!empty($post['vendor_mobile_no']))
			{
			$this->db->like('hms_medicine_vendors.mobile',$post['vendor_mobile_no']);
			}
			
			if(isset($post['sub_branch_id']) && !empty($post['sub_branch_id']))
		    {
               $this->db->where('hms_ambulance_booking.branch_id',$post['sub_branch_id']);
		    } 
		    else
            {
            	$this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
            }

            $emp_ids='';
            if($users_data['emp_id']>0)
            {
                if($users_data['record_access']=='1')
                {
                    $emp_ids= $users_data['id'];
                }
            }
            elseif(!empty($get["employee"]) && is_numeric($get['employee']))
            {
                $emp_ids=  $get["employee"];
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_ambulance_booking.created_by IN ('.$emp_ids.')');
            }
            
            
		$query = $this->db->get();
  //	echo $this->db->last_query();die;
		$result = $query->result_array();

		}
		return $result;

 }

	public function payment_to_branch($id="",$type="",$branch_id="")
	{		
		$post = $this->input->post();
//	print_r($post);die();
		$ex_type=0;
		$type=12; //Ambulance Vendor Payment
		
		$post['section_id'] = 13; //for payment section id 13
		
		if($post['section_id']==13)
		{
			$ex_type=16;//13; //for ambulance the section id for expense should be 16 
		}
		
		
		//print_r($post);die();
		$users_data = $this->session->userdata('auth_users');
		if(isset($post) && !empty($post))
		{
		  //if($type==2)
		//	{
				if(!empty($post['payment_id'])){					
					$this->db->where('id',$post['payment_id']);
					$this->db->delete('hms_payment');
				}
				if(!empty($post['expense_id'])){					
					$this->db->where('id',$post['expense_id']);
					$this->db->delete('hms_expenses');
				}
				if(!empty($post['expense_id'])){
					$this->db->where('parent_id',$post['expense_id']);
					$this->db->delete('hms_payment_mode_field_value_acc_section');
				}
				

				$paid_date = date('Y-m-d H:i:s', strtotime($post['paid_date'].' '.date('H:i:s')));
				$data = array(
								'branch_id'=>$users_data['parent_id'],
								'vendor_id'=>$id,
								'total_amount'=>$post['balance'],
								'net_amount'=>$post['balance'],
								'debit'=>$post['balance'],
								'pay_mode'=>$post['payment_mode'],
								'created_by'=>$users_data['id'],
								'created_date'=>$paid_date,
								'parent_id'=>$post['parent_id'],
								'section_id'=>$post['section_id'],
								'type'=>$type
							 );
			$this->db->insert('hms_payment',$data);
			
// echo $this->db->last_query();
  
			$insert_id= $this->db->insert_id();		 
					 $data_expenses= array(
						'branch_id'=>$users_data['parent_id'],
						'type'=>$ex_type,
						'vouchar_no'=>generate_unique_id(19),
						'parent_id'=>$insert_id,
	                    'expenses_date'=>$paid_date,
	                    'paid_to_id'=>$id,
	                    'paid_amount'=>$post['balance'],
	                    'payment_mode'=>$post['payment_mode'],
	                    'created_date'=>date('Y-m-d H:i:s'),
	                    'created_by'=>$users_data['id'],
					);
			$this->db->insert('hms_expenses',$data_expenses);
// echo $this->db->last_query();die();			
			
			
				$expenses_id= $this->db->insert_id();		
           //print_r($post);die;
				

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
		                'type'=>6,
		                'section_id'=>7,
		                'p_mode_id'=>$post['payment_mode'],
		                'branch_id'=>$users_data['parent_id'],
		                'parent_id'=>$expenses_id,
		                'ip_address'=>$_SERVER['REMOTE_ADDR']
		                );
		                $this->db->set('created_by',$users_data['id']);
		                $this->db->set('created_date',date('Y-m-d H:i:s'));
		                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

	                }
                }
            /*add sales banlk detail*/  
		//	}
			 
			

			/*add sales banlk detail*/
              if(!empty($post['field_name']))
                {
	                if(!empty($post['payment_id'])){
						$this->db->where('parent_id',$post['payment_id']);
						$this->db->delete('hms_payment_mode_field_value_acc_section');
					}
	                $post_field_value_name= $post['field_name'];
	                $counter_name= count($post_field_value_name); 
	                for($i=0;$i<$counter_name;$i++) 
	                {
		                $data_field_value= array(
		                'field_value'=>$post['field_name'][$i],
		                'field_id'=>$post['field_id'][$i],
		                'type'=>5,
		                'section_id'=>6,
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

			return $insert_id;
			
		}

	}

	public function patient_balance_receipt_data($id="",$type="")
	{
       // echo $this->session->userdata('balance');die;
		if(!empty($id))
		{ 
			 $result_booking=array();
			 $user_data = $this->session->userdata('auth_users');

			if($type==1){

				$this->db->select("hms_patient.*,hms_patient.patient_name as name,hms_medicine_sale.sale_no as recepit_no,hms_payment_mode.payment_mode,hms_medicine_sale.sale_date as date,hms_doctors.doctor_name,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount"); 
					$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
					$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
					$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left'); 

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
				$this->db->select("hms_medicine_vendors.*,hms_medicine_vendors.name as name,hms_medicine_vendors.vendor_id as code,hms_medicine_vendors.email,hms_medicine_vendors.address,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_medicine_purchase.cgst,hms_medicine_purchase.sgst,hms_medicine_purchase.igst,hms_medicine_purchase.remarks as remk,hms_medicine_purchase.purchase_date as date,hms_medicine_purchase.purchase_id,hms_medicine_purchase.invoice_id as recepit_no,hms_medicine_vendors.mobile as mobile_no,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name"); 
			        $this->db->join('hms_payment','hms_payment.vendor_id = hms_medicine_vendors.id','left');
					$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
					$this->db->join('hms_medicine_purchase','hms_medicine_purchase.vendor_id = hms_medicine_vendors.id','left'); 
					
					$this->db->join('hms_users','hms_users.id = hms_medicine_purchase.created_by'); 
					$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
					 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
					 
					 
					 
				$this->db->where('hms_payment.doctor_id','0'); 
				$this->db->where('hms_payment.parent_id','0'); 
				$this->db->where('hms_payment.id = "'.$id.'"'); 
				//$this->db->where('hms_payment.parent_id = "'.$id.'"'); 
				$this->db->group_by('hms_medicine_vendors.id','DESC');  
				$this->db->from('hms_medicine_vendors');
					$result_patient['sales_list'] = $this->db->get()->result();
			
			}
          return $result_patient;
			
		} 
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
    //get vendor details
    public function get_by_id($id)
	{
		$this->db->select("hms_medicine_vendors.*"); 
		$this->db->from('hms_medicine_vendors'); 
		$this->db->where('hms_medicine_vendors.id',$id);
		$this->db->where('hms_medicine_vendors.is_deleted','0'); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	function deleteRecords($auto_id,$balance,$type){
		
		      $data = array(
								'id'=>$auto_id,
								'balance'=>$balance,
								'type'=>$type,
								'branch_id'=>$branch_id,
								'status'=>2
							 );
			 $this->db->insert('hms_payment',$data); 
			 
			 $this->db->query("delete from hms_payment where id='".$auto_id."'");
			  return true;
			
		

	}
	
	
	public function get_history_by_id($id,$parent_id='')
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_medicine_vendors.name, hms_expenses.paid_amount, hms_expenses.id, hms_expenses.parent_id as pay_id,hms_expenses.paid_to_id, 
		hms_expenses.expenses_date, hms_expenses.branch_id,hms_expenses.parent_id,hms_payment.section_id,hms_payment.type,hms_payment.parent_id');  
			$this->db->from('hms_expenses');
			$this->db->join('hms_payment','hms_payment.id=hms_expenses.parent_id');
			$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_expenses.paid_to_id');
			$this->db->where('hms_expenses.type IN(16)');
			$this->db->where('hms_expenses.paid_to_id',$id);
			if(!empty($parent_id)){				
			$this->db->where('hms_payment.parent_id',$parent_id);
			}
			$this->db->where('hms_expenses.branch_id',$users_data['parent_id']);
			$this->db->order_by('hms_expenses.id','DESC');
			$query = $this->db->get();
//	echo $this->db->last_query();die;
			$result = $query->result_array();
			return $result;
	}	
	

	// op ledger

	public function get_ledger_history($get='')
	{
	   $users_data = $this->session->userdata('auth_users');
	   if(!empty($get['start_date']))
	   {
      $start_date=date('Y-m-d H:i:s',strtotime($get['start_date'].' 00:00:00'));
	   }
	   if($get['end_date'])
	   {
      $end_date=date('Y-m-d H:i:s',strtotime($get['end_date'].' 23:59:00'));
	   }
	  if($get['type']==1)
	  {
        $this->db->select("hms_payment.debit as paid_amount,hms_payment.created_date as c_date,hms_payment.balance as blnce, (CASE WHEN hms_payment.balance>0 THEN hms_ambulance_booking.net_amount ELSE '0.00' END) 
        total_amount, (CASE WHEN hms_payment.balance > 0 THEN (hms_ambulance_booking.discount) ELSE '0.00' END) as discount, 
        CONCAT('Ambulance/',hms_ambulance_booking.booking_no,'/',hms_medicine_vendors.name) as bill_no,(CASE WHEN hms_payment.balance > 0 THEN
        (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 13 AND payment.parent_id = hms_ambulance_booking.id) 
        ELSE '0.00' END) as balance,hms_payment_mode.payment_mode"); 
        $this->db->join('hms_ambulance_booking','hms_ambulance_booking.id = hms_payment.parent_id AND hms_ambulance_booking.is_deleted !=2'); 
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_payment.vendor_id AND hms_medicine_vendors.is_deleted !=2'); 
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_payment.pay_mode','left');
        $this->db->from('hms_payment');
        if(!empty($get['start_date']))
        {
        $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
        }
        if(!empty($get['end_date']))
        {
        $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
        }
        $this->db->where('hms_payment.section_id',13); 
        $this->db->where('hms_payment.type',12);
        $this->db->where('hms_ambulance_booking.is_deleted',0); 
        $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
         if(!empty($get['v_id']))
        {
			$this->db->where('hms_medicine_vendors.id',$get['v_id']);        	
        }
        $this->db->order_by('hms_payment.id','DESC');
        $query = $this->db->get();
		$result['debit'] = $query->result();
		
		
        $this->db->select("'0.00' as paid_amount,hms_ambulance_booking.created_date as c_date,hms_ambulance_booking.vendor_charge as blnce,
        (CASE WHEN hms_ambulance_booking.vendor_id >0 THEN hms_ambulance_booking.vendor_charge ELSE '0.00' END) 
        total_amount, '0.00' as discount, 
        CONCAT('Ambulance/',hms_ambulance_booking.booking_no,'/',hms_medicine_vendors.name) as bill_no, '0.00' as balance, hms_payment_mode.payment_mode"); 
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_ambulance_booking.vendor_id AND hms_medicine_vendors.is_deleted !=2'); 
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_ambulance_booking.payment_mode','left');
        $this->db->from('hms_ambulance_booking');
        if(!empty($start_date))
        {
            $this->db->where('hms_ambulance_booking.created_date >= "'.$start_date.'"');
        }
        if(!empty($end_date))
        {
           $this->db->where('hms_ambulance_booking.created_date <= "'.$end_date.'"'); 
        }
        
        
        $this->db->where('hms_ambulance_booking.is_deleted',0); 
        $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
         if(!empty($get['v_id']))
        {
			$this->db->where('hms_medicine_vendors.id',$get['v_id']);        	
        }
        $query = $this->db->get();
        //echo $this->db->last_query();
		$result['credit'] = $query->result();
		//echo "<pre>"; print_r($result);die;
		return $result;
	}
}


	public function get_purchase_history_by_id($vendor_id='')
	{
	    $search_ven = $this->input->post();
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_medicine_vendors.*, hms_ambulance_booking.ven_charge_type, hms_ambulance_booking.id as parent_id,hms_ambulance_booking.booking_date,
	    hms_ambulance_booking.vendor_charge as totbalance, SUM(hms_payment.debit) as debit, hms_medicine_vendors.id as vendor_id');  
		$this->db->from('hms_medicine_vendors');
		$this->db->join('hms_ambulance_booking','hms_ambulance_booking.vendor_id=hms_medicine_vendors.id');
		$this->db->join('hms_payment','hms_payment.parent_id=hms_ambulance_booking.id AND hms_payment.type=12','LEFT');
		$this->db->where('hms_medicine_vendors.vendor_type',5);
		$this->db->where('hms_ambulance_booking.vendor_id',$vendor_id);
           if(!empty($search_ven))
            {
	            	if(!empty($search_ven['start_date']))
	            	{
	            	    $this->db->where('hms_ambulance_booking.created_date >="'.date('Y-m-d H:i:s',strtotime($search_ven['start_date'].' 00:00:00')).'"');
	            	}
	            	if(!empty($search_ven['end_date']))
	            	{
	            	    $this->db->where('hms_ambulance_booking.created_date <="'.date('Y-m-d H:i:s',strtotime($search_ven['end_date'].' 23:59:00')).'"');
	            	}
            }
            $this->db->where('hms_ambulance_booking.branch_id',$users_data['parent_id']);
            $this->db->group_by('hms_ambulance_booking.id');
			$query = $this->db->get();
           // echo $this->db->last_query();die();
			$result = $query->result_array();
		     return $result;	
	}
	
    function delete_paid_records($expense_id='',$payment_id='',$type='12'){
				if(!empty($payment_id)){					
					$this->db->where('id',$payment_id);
					$this->db->where('type',12);
					$this->db->delete('hms_payment');
				}
				if(!empty($expense_id)){					
					$this->db->where('id',$expense_id);
					$this->db->delete('hms_expenses');
				}
			/*	if(!empty($expense_id)){
					$this->db->where('parent_id',$expense_id);
					$this->db->delete('hms_payment_mode_field_value_acc_section');
				}
				if(!empty($payment_id)){
					$this->db->where('parent_id',$payment_id);
					$this->db->delete('hms_payment_mode_field_value_acc_section');
				}*/
			  return true;
	}
} 
?>