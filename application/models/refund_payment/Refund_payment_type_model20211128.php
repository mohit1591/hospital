<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Refund_payment_type_model extends CI_Model 
{


	var $table = 'hms_patient';
	var $column = array('hms_patient.id','hms_patient.patient_name', 'hms_patient.status','hms_patient.created_date');  
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
		$refund_search_data = $this->session->userdata('refund_search_data');
		$result= array(); 
		
			 
			$this->db->select('hms_patient.*,hms_payment.branch_id, (CASE WHEN hms_payment.section_id = 1 THEN "Pathology" WHEN hms_payment.section_id = 2 THEN "OPD" WHEN hms_payment.section_id=3 THEN "Medicine" WHEN hms_payment.section_id=4 THEN "Billing" WHEN hms_payment.section_id=5 THEN "IPD" WHEN hms_payment.section_id=8 THEN "OT" WHEN hms_payment.section_id = 10 THEN "Blood Bank" WHEN hms_payment.section_id = 13 THEN "Ambulance" ELSE "" END) as department, hms_payment.created_date as date, hms_payment.discount_amount as discount, hms_payment.parent_id, hms_payment.section_id,hms_payment.type, (select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id) as balance');
			$this->db->from('hms_patient');
			//$this->db->where('(select sum(credit)-sum(debit) as balance from hms_payment as  payment where payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id) > 0');
			//$this->db->where('(select sum(hms_payment.credit)-sum(hms_payment.debit) from hms_payment as payment where patient_id = hms_patient.id) > 0');
			$this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id'); 
			//$this->db->join('hms_doctors','hms_doctors.id=hms_payment.doctor_id'); 
			//$this->db->where('hms_doctors.doctor_pay_type',1);
			if(!empty($refund_search_data['patient_name']))
			{ 
			   $this->db->like('hms_patient.patient_name',$refund_search_data['patient_name']);
			}
			if(!empty($refund_search_data['mobile_no']))
			{
			   $this->db->like('hms_patient.mobile_no',$refund_search_data['mobile_no']);
			}
			

			/* if(isset($post['sub_branch_id']) && !empty($post['sub_branch_id']))
			{
			$this->db->where('hms_payment.branch_id',$post['sub_branch_id']);
			} 
			else
			{
			$this->db->where('hms_payment.branch_id',$users_data['parent_id']);
			} */

			if(isset($refund_search_data) && !empty($refund_search_data) && !empty($refund_search_data['section_id']))
			{ 
				$imp_sec_id = implode(',', $refund_search_data['section_id']);
				$this->db->where('hms_payment.section_id IN ('.$imp_sec_id.')');
			}
			else
			{
				$this->db->where('hms_payment.section_id',2);
			}
                        $this->db->where('hms_patient.is_deleted',0);
$this->db->where('hms_payment.branch_id',$users_data['parent_id']);

			//$this->db->group_by('hms_payment.parent_id, hms_payment.section_id');
			$this->db->group_by('hms_patient.id');
			$this->db->order_by('hms_payment.id','DESC');
			$query = $this->db->get();
			//echo $this->db->last_query(); 
			$result = $query->result_array();

		
		return $result;

	}
	public function get_refund_data()
	{
		//echo "hi";die;
      $user_data = $this->session->userdata('auth_users');
      $this->db->select("hms_refund_payment.*,hms_patient.patient_name"); 
	  $this->db->from('hms_refund_payment');
	  $this->db->join('hms_patient','hms_patient.id = hms_refund_payment.patient_id','left');
	  $this->db->where('hms_refund_payment.branch_id',$user_data['parent_id']);
	  $this->db->order_by('hms_refund_payment.id','DESC');  
	  $query = $this->db->get(); 
	  $result = $query->result_array(); 
   //echo $this->db->last_query();
	   return $result; 
	}

	public function get_by_id($id)
	{
		 $user_data = $this->session->userdata('auth_users');
      $this->db->select("hms_medicine_sale.*,hms_patient.patient_name"); 
	  $this->db->from('hms_medicine_sale');
	  $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left');
	  $this->db->where('hms_medicine_sale.branch_id',$user_data['parent_id']);
	  $query = $this->db->get(); 
	 // echo $this->db->last_query();die;
	  $result = $query->result_array(); 
	}
	public function insert_refund_data($insert_array="")
	{
		//print_r($insert_array);die;
         $this->db->insert('hms_refund_payment',$insert_array);
         $id= $this->db->insert_id();
    // echo $id;die;
          $post = $this->input->post();
       //print_r($post);die;
		  $users_data = $this->session->userdata('auth_users');
		if(isset($post) && !empty($post))
		{
			if($post['section_id']==1)
			{
				$type=8;
			}
			elseif ($post['section_id']==2)
			{
				$type=7;
			}
				elseif ($post['section_id']==5)
			{
				$type=10;
			}
			elseif ($post['section_id']==3)
			{
				$type=9;
			}
			elseif ($post['section_id']==8)
			{
				$type=11;
			}
			elseif ($post['section_id']==10)
			{
				$type=12;
			}
			elseif ($post['section_id']==13)
			{
				$type=13;
			}
			elseif ($post['section_id']==4)
			{
				$type=14;
			}
			elseif ($post['section_id']==6)
			{
				$type=6;
			}
			 elseif ($post['section_id']==14)
			{
				$type=15;
			}
			
		
			$data_expenses= array(
                    'branch_id'=>$users_data['parent_id'],
                    'type'=>$type,
                    'vouchar_no'=>generate_unique_id(19),
                    'parent_id'=>$id,
                    'expenses_date'=>date('Y-m-d',strtotime($post['refund_date'])),
                    'paid_to_id'=>$post['patient_id'],
                    'paid_amount'=>$post['refund_amount'],
                    'payment_mode'=>$post['pay_mode'],
                   // 'cheque_no'=>$cheque_no,
                    //'branch_name'=>$bank_name,
                    //'cheque_date'=>date('Y-m-d',strtotime($post['purchase_date'])),
                    //'transaction_no'=>$transaction_no,
                    'created_date'=>date('Y-m-d H:i:s'),
                    'modified_date'=>date('Y-m-d H:i:s'),
                    'modified_by'=>$users_data['id']
                    );
			//print_r($data_expenses);die;
                $this->db->insert('hms_expenses',$data_expenses);
			//echo $insert_id;die;

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
                'type'=>16,
                'section_id'=>10,
                'p_mode_id'=>$post['pay_mode'],
                'branch_id'=>$users_data['parent_id'],
                'parent_id'=>$insert_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$users_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }
                
                /*for ot payment refund*/ 
              if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
                $data_field_value= array(
                'field_value'=>$post['field_name'][$i],
                'field_id'=>$post['field_id'][$i],
                'type'=>18,
                'section_id'=>17,
                'p_mode_id'=>$post['pay_mode'],
                'branch_id'=>$users_data['parent_id'],
                'parent_id'=>$insert_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$users_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*for ot payment refund*/  
            /*add sales banlk detail*/  
			//echo $this->db->last_query(); exit;
			
			
		}
     return $id;
   
	}
	public function get_refund_data_of_patient($pid="",$parent_id="",$section_id="",$branch_id="")
	{
		
	   $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_refund_payment.*"); 
	   $this->db->from('hms_refund_payment');
	    $this->db->where('hms_refund_payment.branch_id',$user_data['parent_id']);
	 
	   $this->db->where('hms_refund_payment.patient_id',$pid);
	  
	 $query = $this->db->get(); 
	   $result = $query->result_array(); 
    // echo $this->db->last_query();
	   return $result; 


	}
	
	public function get_booking_details_for_patient($id="")
    {
    	//echo $id;die;
      
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_opd_booking.*"); 
	   $this->db->from('hms_opd_booking');
	   $this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']);
	   $this->db->where('hms_opd_booking.patient_id',$id);
	   $query = $this->db->get(); 
	   $result = $query->result_array(); 
		//echo $this->db->last_query(); 
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
	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $refund_search_data = $this->session->userdata('refund_search_data');
        //print_r($refund_search_data['section_id']);die;
		$this->db->select('hms_patient.*,hms_payment.branch_id, (CASE WHEN hms_payment.section_id = 1 THEN "Pathology" WHEN hms_payment.section_id = 2 THEN "OPD" WHEN hms_payment.section_id=3 THEN "Medicine" WHEN hms_payment.section_id=4 THEN "Billing" WHEN hms_payment.section_id=5 THEN "IPD" WHEN hms_payment.section_id=8 THEN "OT" WHEN hms_payment.section_id = 10 THEN "Blood Bank" WHEN hms_payment.section_id = 13 THEN "Ambulance" WHEN hms_payment.section_id = 7 THEN "Vaccination" WHEN hms_payment.section_id = 14 THEN "Day Care" ELSE "" END) as department, hms_payment.created_date as date, hms_payment.discount_amount as discount, hms_payment.parent_id, hms_payment.section_id,hms_payment.type'); 
		$this->db->from($this->table); 
		$this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id');

		
		if(isset($refund_search_data) && !empty($refund_search_data) && !empty($refund_search_data['section_id']))
		{ 
			$imp_sec_id = implode(',', $refund_search_data['section_id']);
			$this->db->where('hms_payment.section_id IN ('.$imp_sec_id.')');
		}
		else
		{
			$this->db->where('hms_payment.section_id','');
			//$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id AND hms_opd_booking.is_deleted=0 AND hms_opd_booking.type=2','left');
		}
		if(!empty($refund_search_data['section_id']))
		{
			foreach ($refund_search_data['section_id'] as $value) 
			{
				if($value==1)
				{
					$this->db->join('path_test_booking','path_test_booking.id=hms_payment.parent_id AND path_test_booking.is_deleted=0 and path_test_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}

				if($value==2)
				{
					$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id AND hms_opd_booking.is_deleted=0 AND hms_opd_booking.type=2 and hms_opd_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}

				if($value==3)
				{
					$this->db->join('hms_medicine_sale','hms_medicine_sale.id=hms_payment.parent_id AND hms_medicine_sale.is_deleted=0 and hms_medicine_sale.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				if($value==4)
				{
					$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_payment.parent_id AND hms_opd_booking.is_deleted=0 AND hms_opd_booking.type=3 and hms_opd_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				if($value==5)
				{
					$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_payment.parent_id AND hms_payment.section_id=5 AND hms_payment.type=4 AND hms_ipd_booking.is_deleted=0 AND hms_ipd_booking.discharge_status=0 AND hms_ipd_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				if($value==6)
				{
					$this->db->join('hms_vaccination_sale','hms_vaccination_sale.id=hms_payment.parent_id AND hms_vaccination_sale.is_deleted=0 AND hms_vaccination_sale.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				if($value==8)
				{
					$this->db->join('hms_operation_booking','hms_operation_booking.id=hms_payment.parent_id AND hms_operation_booking.is_deleted=0 and hms_operation_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				
				if($value==10)
				{
					$this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_payment.parent_id AND hms_blood_patient_to_recipient.is_deleted=0 and hms_blood_patient_to_recipient.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				
				if($value==13)
				{
					$this->db->join('hms_ambulance_booking','hms_ambulance_booking.id=hms_payment.parent_id AND hms_ambulance_booking.is_deleted!=2  and hms_ambulance_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}
				if($value==14)
				{
					$this->db->join('hms_day_care_booking','hms_day_care_booking.id=hms_payment.parent_id AND hms_day_care_booking.is_deleted=0 AND hms_day_care_booking.type=5 and hms_day_care_booking.branch_id="'.$users_data['parent_id'].'"','inner');
				}
			}
		}




   		if(!empty($refund_search_data['patient_name']))
		{ 
		   $this->db->like('hms_patient.patient_name',$refund_search_data['patient_name']);
		}
		if(!empty($refund_search_data['mobile_no']))
		{
		   $this->db->like('hms_patient.mobile_no',$refund_search_data['mobile_no']);
		}
		

		/*if(isset($post['sub_branch_id']) && !empty($post['sub_branch_id']))
		{
		$this->db->where('hms_payment.branch_id',$post['sub_branch_id']);
		} 
		else
		{
		
		}*/

		$emp_ids='';
		if($users_data['emp_id']>0)
		{
			if($users_data['record_access']=='1')
			{
				$emp_ids= $users_data['id'];
			}
		}
		elseif(!empty($refund_search_data["employee"]) && is_numeric($refund_search_data['employee']))
        {
            $emp_ids=  $refund_search_data["employee"];
        }
		
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_patient.created_by IN ('.$emp_ids.')');
		}

		$this->db->where('hms_payment.branch_id',$users_data['parent_id']);
		$this->db->where('hms_patient.is_deleted',0);

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

		//$this->db->group_by('hms_patient.id'); //on 27 oct
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
	function get_datatables_for_refund_list()
	{

       $this->_get_datatables_query_for_refund();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result_array();

	}

	private function _get_datatables_query_for_refund()
	{
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        //$this->db->select("hms_refund_payment.*,hms_patient.patient_name,(select id from hms_expenses where hms_expenses.parent_id=hms_refund_payment.id AND hms_refund_payment.section_id=hms_expenses.type AND hms_expenses.branch_id='".$users_data['parent_id']."') as expense_id"); 
        
        $this->db->select("hms_refund_payment.*,hms_patient.patient_name,(select id from hms_expenses where hms_expenses.parent_id=hms_refund_payment.id AND hms_refund_payment.patient_id=hms_expenses.paid_to_id AND hms_expenses.branch_id='".$users_data['parent_id']."') as expense_id"); 

		$this->db->from('hms_refund_payment'); 
		$this->db->join('hms_patient','hms_patient.id = hms_refund_payment.patient_id','left');
        $this->db->where('hms_refund_payment.branch_id',$users_data['parent_id']);
        $this->db->order_by('hms_refund_payment.id','DESC');  
	
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

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		//echo $query->num_rows(); die;
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return  $this->db->count_all_results();
		
	}

	function count_filtered_for_refund()
	{
		$this->_get_datatables_query_for_refund();
		$query = $this->db->get();
		//echo $query->num_rows(); die;
		return $query->num_rows();
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
			
			elseif($section_id==8)
			{
				//Billing

				$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_operation_booking.reciept_code as recepit_no,hms_operation_booking.operation_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date"); 
			$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
			$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
		    $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
			
			$this->db->join('hms_operation_booking','hms_operation_booking.patient_id = hms_patient.id','left'); 
			$this->db->join('hms_doctors','hms_doctors.id = hms_operation_booking.referral_doctor','left'); 
			$this->db->join('hms_users','hms_users.id = hms_payment.created_by');
			$this->db->where('hms_payment.id = "'.$id.'"'); 
			$this->db->group_by('hms_patient.id','DESC');  
			$this->db->from('hms_patient');
			$result_patient['sales_list'] = $this->db->get()->result();
			//echo $this->db->last_query(); exit;
			return $result_patient;
			}
			
			/* blood bank refund */
			elseif($section_id==10)
			{
			//Billing

				$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_blood_patient_to_recipient.issue_code as recepit_no,hms_blood_patient_to_recipient.requirement_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date"); 
				$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
				$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
				$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');

				$this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.patient_id = hms_patient.id','left'); 
				$this->db->join('hms_doctors','hms_doctors.id = hms_operation_booking.referral_doctor','left'); 
				$this->db->join('hms_users','hms_users.id = hms_payment.created_by');
				$this->db->where('hms_payment.id = "'.$id.'"'); 
				$this->db->group_by('hms_patient.id','DESC');  
				$this->db->from('hms_patient');
				$result_patient['sales_list'] = $this->db->get()->result();
				//echo $this->db->last_query(); exit;
				return $result_patient;
			}
			
			elseif($section_id==13)
			{
				//ambulance

				$this->db->select("hms_patient.*,hms_users.*,hms_patient.patient_name as name,hms_ambulance_booking.booking_code as recepit_no,hms_ambulance_booking.booking_date as date,hms_doctors.doctor_name,hms_payment_mode.payment_mode,hms_payment.pay_mode,hms_patient.patient_code as code,hms_payment.id as p_id,hms_simulation.simulation,hms_payment.debit, (select sum(credit)-sum(debit) from hms_payment as sub_pay where sub_pay.patient_id = hms_payment.patient_id AND sub_pay.created_date <= hms_payment.created_date) as balance, (select sum(credit)-sum(debit) from hms_payment as total_pay where total_pay.patient_id = hms_payment.patient_id AND total_pay.created_date < hms_payment.created_date) as total_amount,hms_payment.created_date as c_date"); 
				$this->db->join('hms_payment','hms_payment.patient_id = hms_patient.id');
			$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left'); 
			$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode');
				
			$this->db->join('hms_ambulance_booking','hms_ambulance_booking.patient_id = hms_patient.id','left'); 
			$this->db->join('hms_doctors','hms_doctors.id = hms_ambulance_booking.reffered','left'); 
			$this->db->join('hms_users','hms_users.id = hms_payment.created_by');
			$this->db->where('hms_payment.id = "'.$id.'"'); 
			$this->db->group_by('hms_patient.id','DESC');  
			$this->db->from('hms_patient');
			$result_patient['sales_list'] = $this->db->get()->result();
		    
			return $result_patient;

			} 
			elseif($section_id==14)
			{
				//Day Care Booking

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
			/* blood bank refund */    
			
		} 
	}

	
	//12 july 2017
	
	
	
} 
?>