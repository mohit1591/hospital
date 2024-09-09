<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses_model extends CI_Model {

	var $table = 'hms_expenses';
	var $column = array('hms_expenses.id','hms_expenses.vouchar_no','hms_expenses.expenses_date', 'hms_expenses.paid_to_id', 'hms_expenses.paid_amount','hms_expenses.payment_mode','hms_expenses.created_date','hms_expenses.modified_date');  
	var $order = array('hms_expenses.id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$expenses_search = $this->session->userdata('expenses_search');
		

		$this->db->select("hms_expenses.*,hms_expenses_category.exp_category, (CASE WHEN hms_expenses.type=0 THEN 'Expenses'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing' WHEN hms_expenses.type=15 THEN 'Day Care Refund' END) as expenses_type,hms_payment_mode.payment_mode as p_mode,(CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=7 THEN (SELECT `hms_opd_booking`.`booking_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) WHEN hms_expenses.type=8 THEN (SELECT `path_test_booking`.`lab_reg_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) 
		
		WHEN hms_expenses.type=9 THEN 


(SELECT `hms_medicine_sale`.`sale_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)

WHEN hms_expenses.type=10 THEN 

(SELECT `hms_ipd_booking`.`ipd_no` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`refund_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10)


WHEN hms_expenses.type=11 THEN 

(SELECT `hms_ot_booking_ot_details`.`code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11)

WHEN hms_expenses.type=13 THEN 

(SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13)

WHEN hms_expenses.type=14 THEN

(SELECT `hms_opd_booking`.`reciept_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14)

WHEN hms_expenses.type=2 THEN 


(SELECT `hms_medicine_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2)
WHEN hms_expenses.type=5 THEN 

(SELECT `hms_vaccination_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5)


		END) as book_code,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`patient_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,hms_medicine_vendors.name as paid_to_name"); 
		$this->db->from($this->table); 
		$this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
		//$this->db->where('hms_expenses.type','0');
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
		
		$this->db->join("hms_medicine_vendors","hms_expenses.vendor_id =hms_medicine_vendors.id",'left');
		
		$this->db->where('hms_expenses.is_deleted="0"');
		//$this->db->where('hms_expenses_category.is_deleted="0" and (select * from )');

		//$this->db->where('hms_expenses.branch_id',$users_data['parent_id']);
		
        if(isset($expenses_search['branch_id']) && $expenses_search['branch_id']!=''){
		$this->db->where('hms_expenses.branch_id IN ('.$expenses_search['branch_id'].')');
		}else{
		$this->db->where('hms_expenses.branch_id = "'.$users_data['parent_id'].'"');
		}

        if(isset($expenses_search) && !empty($expenses_search))
		{
			
            if(isset($expenses_search['start_date']) && !empty($expenses_search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($expenses_search['start_date'])).' 00:00:00';
				$this->db->where('hms_expenses.expenses_date >= "'.$start_date.'"');
			}

			if(isset($expenses_search['end_date']) && !empty($expenses_search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($expenses_search['end_date'])).' 23:59:59';
				$this->db->where('hms_expenses.expenses_date <= "'.$end_date.'"');
			}
			
			if(isset($expenses_search['expense_type']) && !empty($expenses_search['expense_type']))
		{
			$this->db->where('hms_expenses_category.exp_category LIKE "'.$expenses_search['expense_type'].'%"');
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
		elseif(!empty($expenses_search["employee"]) && is_numeric($expenses_search['employee']))
        {
            $emp_ids=  $expenses_search["employee"];
        }
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_expenses.created_by IN ('.$emp_ids.')');
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
			// print_r($column[$_POST['order']['0']['column']]);
			// die;
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		}
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function search_report_data()
        {
           
		$users_data = $this->session->userdata('auth_users');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$expenses_search = $this->session->userdata('expenses_search');
		

		$this->db->select("hms_expenses.*,hms_expenses_category.exp_category, (CASE WHEN hms_expenses.type=0 THEN 'Expenses'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Vendor Payment' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing' WHEN hms_expenses.type=15 THEN 'Day Care Refund' END) as expenses_type,hms_payment_mode.payment_mode as p_mode,
		
		(CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=7 THEN (SELECT `hms_opd_booking`.`booking_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) WHEN hms_expenses.type=8 THEN (SELECT `path_test_booking`.`lab_reg_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) 
		
		WHEN hms_expenses.type=9 THEN 


(SELECT `hms_medicine_sale`.`sale_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)

WHEN hms_expenses.type=10 THEN 

(SELECT `hms_ipd_booking`.`ipd_no` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`refund_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10)


WHEN hms_expenses.type=11 THEN 

(SELECT `hms_ot_booking_ot_details`.`code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11)

WHEN hms_expenses.type=13 THEN 

(SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13)

WHEN hms_expenses.type=14 THEN

(SELECT `hms_opd_booking`.`reciept_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14)

WHEN hms_expenses.type=2 THEN 


(SELECT `hms_medicine_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2)
WHEN hms_expenses.type=5 THEN 

(SELECT `hms_vaccination_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5)


		END) as book_code,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = hms_expenses.id AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,hms_medicine_vendors.name as paid_to_name
		"); 
		
		$this->db->from($this->table); 
		$this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
		
		$this->db->join("hms_medicine_vendors","hms_expenses.vendor_id =hms_medicine_vendors.id",'left');
		
		
		//$this->db->where('hms_expenses.type','0');
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
                
                $this->db->where('hms_expenses.is_deleted','0');
		//$this->db->where('hms_expenses_category.is_deleted="0" and (select * from )');

		//$this->db->where('hms_expenses.branch_id',$users_data['parent_id']);

		if(isset($expenses_search['branch_id']) && $expenses_search['branch_id']!=''){
		$this->db->where('hms_expenses.branch_id IN ('.$expenses_search['branch_id'].')');
		}else{
		$this->db->where('hms_expenses.branch_id = "'.$users_data['parent_id'].'"');
		}
		

		$i = 0;
		if(isset($expenses_search) && !empty($expenses_search))
		{
		if(!empty($expenses_search['start_date']))
		{
		$start_date = date('Y-m-d',strtotime($expenses_search['start_date'])).' 00:00:00';
		$this->db->where('hms_expenses.expenses_date >= "'.$start_date.'"');
		}

		if(!empty($expenses_search['end_date']))
		{
		$end_date = date('Y-m-d',strtotime($expenses_search['end_date'])).' 23:59:59';
		$this->db->where('hms_expenses.expenses_date <= "'.$end_date.'"');
		}
		
		if(isset($expenses_search['expense_type']) && !empty($expenses_search['expense_type']))
		{
			$this->db->where('hms_expenses_category.exp_category LIKE "'.$expenses_search['expense_type'].'%"');
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
		elseif(!empty($expenses_search["employee"]) && is_numeric($expenses_search['employee']))
        {
            $emp_ids=  $expenses_search["employee"];
        }
		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('hms_expenses.created_by IN ('.$emp_ids.')');
		}
	    $query = $this->db->get(); 
        //echo $this->db->last_query();die;
		$data= $query->result();
		//print_r($data);die;
		return $data;
	
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

	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_expenses.*,hms_expenses_category.exp_category,hms_employees.name"); 
        $this->db->from($this->table); 
        $this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id and hms_expenses_category.is_deleted='0'",'left');
        $this->db->join("hms_employees","hms_expenses.employee_id=hms_employees.id",'left');
        $this->db->where('hms_expenses.id',$id);
        $this->db->where('hms_expenses.is_deleted="0"');
        // $this->db->where('(hms_expenses.is_deleted="0" AND hms_expenses_category.is_deleted="0")');
        //$this->db->where('(hms_expenses.branch_id = "'.$users_data['parent_id'].'" OR hms_expenses.branch_id = "0")');
		$query = $this->db->get();
		// echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//echo "<pre>";print_r($post); exit;
		$voucher_no = generate_unique_id(19);
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $data = array( 
                            //'vouchar_no'=>$post['voucher_no'],
                             'expenses_date'=>date('Y-m-d', strtotime($post['expenses_date'])),
                            'paid_to_id'=>$post['expense_category_id'], 
                            'vendor_id'=>$post['vendor_id'], 
                            'paid_amount'=>$post['paid_amount'], 
                            'payment_mode'=>$post['payment_mode'], 
                            'remarks'=>$post['remark'], 

                            //'transaction_no'=>$post['transaction_no'], 
                            //'branch_name'=>$post['branch_name'], 
                            //'cheque_no'=>$post['cheque_no'],
                            //'cheque_date'=>date('Y-m-d', strtotime($post['cheque_date'])),
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'modified_by'=>$user_data['id'],
                            'modified_date'=>date('Y-m-d H:i:s')
				         );

            	if(!empty($post['expense_category_id'])){
			    $result = get_exp_category($post['expense_category_id']);
			    if($result['exp_category']=='advance' || $result['exp_category']=='Advance'){
			     	 $data['employee_advance'] = $post['paid_amount'];
			     	 $data['employee_id'] = $post['employee_id'];
			     	 $data['emp_type_id'] = $post['emp_type_id'];
			  
			    }
		    }
		    
			$this->db->where('id',$post['data_id']);
			$this->db->update('hms_expenses',$data); 
			$expense_id= $post['data_id'];

			/*add sales banlk detail*/
                $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>6,'section_id'=>6));
                $this->db->delete('hms_payment_mode_field_value_acc_section');
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
                'section_id'=>6,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$user_data['parent_id'],
                'parent_id'=>$post['data_id'],
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*add sales banlk detail*/  
          
		}
		else{   
			// $branch_code = generate_unique_id(1);
			$data = array( 
                            'vouchar_no'=>$voucher_no,
                            'expenses_date'=>date('Y-m-d', strtotime($post['expenses_date'])), 
                            'paid_to_id'=>$post['expense_category_id'], 
                            'vendor_id'=>$post['vendor_id'], 
                            'paid_amount'=>$post['paid_amount'], 
                            'payment_mode'=>$post['payment_mode'], 
                            'remarks'=>$post['remark'], 
                            //'transaction_no'=>$post['transaction_no'], 
                            //'branch_name'=>$post['branch_name'], 
                            'branch_id' =>$user_data['parent_id'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'modified_by'=>$user_data['id'],
                            'created_date'=>date('Y-m-d H:i:s'),
                            'modified_date'=>date('Y-m-d H:i:s')
                         );
		

			if(!empty($post['expense_category_id'])){
			    $result = get_exp_category($post['expense_category_id']);
			    if($result['exp_category']=='advance' || $result['exp_category']=='Advance'){
			     	 $data['employee_advance'] = $post['paid_amount'];
			     	 $data['employee_id'] = $post['employee_id'];
			     	 $data['emp_type_id'] = $post['emp_type_id'];
			  
			    }
		    }
		     
			 $this->db->insert('hms_expenses',$data);

			 $expense_id= $this->db->insert_id();

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
                'section_id'=>6,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$user_data['parent_id'],
                'parent_id'=>$expense_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

            /*add sales banlk detail*/  

			// echo $this->db->last_query();die;

          
		} 	
		return $expense_id;
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
			$this->db->update('hms_expenses');
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
			$this->db->update('hms_expenses');
			//echo $this->db->last_query();die;
    	} 
    }

    public function permission_section_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('hms_permission_section.*');
    	
    	if($user_data['users_role']==1)
    	{
            $this->db->join('hms_permission_section','hms_permission_section.id = hms_permission_to_role.section_id');
			$this->db->where('hms_permission_section.status','1');
			$this->db->group_by('hms_permission_section.id');
			$this->db->from('hms_permission_to_role'); 
    	}
    	else if($user_data['users_role']==2)
    	{
    		$this->db->join('hms_permission_section','hms_permission_section.id = hms_permission_to_users.section_id');
    		if($user_data['parent_id']>0)
	    	{
	           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
	    	}
	    	else
	    	{
	           $this->db->where('hms_permission_to_users.master_id',$user_data['parent_id']);
	    	}
			$this->db->where('hms_permission_section.status','1');
			$this->db->group_by('hms_permission_section.id');
			$this->db->from('hms_permission_to_users'); 
    	}
    	  
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }


    public function branch_permission_action_list($section_id="",$branch_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	 

    	if($user_data['users_role']==1)
    	{
           $this->db->select('hms_permission_action.*,hms_permission_to_users.permission_status as user_permission_status, hms_permission_to_users.attribute_val');

           $this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_role.action_id');

           $this->db->join('hms_permission_to_users','hms_permission_to_users.action_id = hms_permission_to_role.action_id AND hms_permission_to_users.users_role = "2" AND hms_permission_to_users.users_id = "'.$branch_id.'"','left');

           if(!empty($section_id))
	    	{
	    	   $this->db->where('hms_permission_action.section_id',$section_id); 
	    	} 
 
	    	$this->db->where('hms_permission_action.status','1'); 
	    	$this->db->group_by('hms_permission_action.id');
	    	$this->db->from('hms_permission_to_role'); 
    	}
    	else if($user_data['users_role']==2)
    	{
    		$this->db->select('hms_permission_action.*,hms_permission_to_users.permission_status as user_permission_status, hms_permission_to_users.attribute_val');

           $this->db->join('hms_permission_action','hms_permission_action.id = hms_permission_to_users.action_id'); 

           if($user_data['parent_id']>0)
	    	{
	           $this->db->where('hms_permission_to_users.users_id',$user_data['id']);
	    	}
	    	else
	    	{
	           $this->db->where('hms_permission_to_users.master_id',$user_data['parent_id']);
	    	}

           if(!empty($section_id))
	    	{
	    	   $this->db->where('hms_permission_action.section_id',$section_id); 
	    	} 
 
	    	$this->db->where('hms_permission_action.status','1'); 
	    	$this->db->group_by('hms_permission_action.id');
	    	$this->db->from('hms_permission_to_users');  
    	}

    	
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
    	$result = $query->result();
    	return $result; 
    }


    public function save_branch_permission($branch_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	$post = $this->input->post();
    	if(!empty($post))
    	{
    		$this->db->where('users_id',$post['bid']);
    		$this->db->delete('hms_permission_to_users');

    		foreach($post['active'] as $active)
    		{
    			$explode = explode('-', $active);
    			$attr_val = "";
    			if(isset($post['attribute_val-'.$explode[1]]))
    			{
    				$attr_val = $post['attribute_val-'.$explode[1]];
    			}

    			if(isset($explode[2]) && !empty($explode[2]) && $explode[2]==1)
    			{
					$data = array(
					   'users_role'=>2,
					   'users_id'=>$post['bid'],
					   'master_id'=>$user_data['parent_id'],
					   'section_id'=>$explode[0],
					   'action_id'=>$explode[1],
					   'attribute_val'=>$attr_val,
					   'permission_status'=>1, 
					   'created_by'=>$user_data['id'],
					   'created_date'=>date('Y-m-d H:i:s')
					 );
					$this->db->insert('hms_permission_to_users',$data); 
    			} 
    		}
    	}
    }
    //get the branch_type and its start date and from date according to their Id
    public function get_branch_details($id=''){
        $this->db->select('branch_type,start_date,end_date');
        $this->db->where('id',$id);
        $this->db->from('hms_expenses');
        $query = $this->db->get();
        //echo $this->db->last_query();die;
        $result = $query->result();
        return $result; 

    }
    
    function get_all_detail_print($ids="",$branch_ids=""){
		$result_sales=array();
		$user_data = $this->session->userdata('auth_users');
		$users_data = $this->session->userdata('auth_users');
		//$this->db->select("hms_expenses.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name, (CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Vendor Payment' WHEN hms_expenses.type=10 THEN 'IPD Payment Refund' END) as expenses_type,hms_payment_mode.payment_mode"); 
		
		$this->db->select("hms_expenses.*, (CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN hms_expenses.type=0 THEN 'Expenses'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' END) as expenses_type,hms_payment_mode.payment_mode,(CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=7 THEN (SELECT `hms_opd_booking`.`booking_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) WHEN hms_expenses.type=8 THEN (SELECT `path_test_booking`.`lab_reg_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) 
		
		WHEN hms_expenses.type=9 THEN 


(SELECT `hms_medicine_sale`.`sale_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)

WHEN hms_expenses.type=10 THEN 

(SELECT `hms_ipd_booking`.`ipd_no` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`refund_id`  WHERE `exp`.`id` =".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10)


WHEN hms_expenses.type=11 THEN 

(SELECT `hms_ot_booking_ot_details`.`code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11)

WHEN hms_expenses.type=13 THEN 

(SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13)

WHEN hms_expenses.type=14 THEN

(SELECT `hms_opd_booking`.`reciept_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14)

WHEN hms_expenses.type=2 THEN 


(SELECT `hms_medicine_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2)
WHEN hms_expenses.type=5 THEN 

(SELECT `hms_vaccination_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5)


		END) as book_code,hms_medicine_vendors.name as vendor_names,hms_medicine_vendors.name as paid_to_name,
		
		
		(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`patient_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,
		
		
		(SELECT `hms_patient`.`patient_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_code,
		
		(SELECT `hms_patient`.`mobile_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as mobile_no,

			(SELECT `hms_patient`.`gender` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as gender,

			(SELECT `hms_patient`.`age_y` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as age_y,

			(SELECT `hms_patient`.`age_m` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as age_m,

			(SELECT `hms_patient`.`age_d` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as age_d,

			(CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=7 THEN (SELECT `hms_opd_booking`.`booking_date` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) WHEN hms_expenses.type=8 THEN (SELECT `path_test_booking`.`booking_date` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) 
		
		WHEN hms_expenses.type=9 THEN 


(SELECT `hms_medicine_sale`.`sale_date` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)

WHEN hms_expenses.type=10 THEN 

(SELECT `hms_ipd_booking`.`admission_date` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`parent_id`  WHERE `exp`.`id` =".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10)


WHEN hms_expenses.type=11 THEN 

(SELECT `hms_ot_booking_ot_details`.`created_date` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11)

WHEN hms_expenses.type=13 THEN 

(SELECT `hms_ambulance_booking`.`booking_date` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13)

WHEN hms_expenses.type=14 THEN

(SELECT `hms_opd_booking`.`booking_date` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14)

WHEN hms_expenses.type=2 THEN 


(SELECT `hms_medicine_purchase`.`purchase_date` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2)
WHEN hms_expenses.type=5 THEN 

(SELECT `hms_vaccination_purchase`.`purchase_date` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5) END) as book_date");
		
		$this->db->where('hms_expenses.is_deleted','0'); 
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
		$this->db->join('hms_users','hms_users.id = hms_expenses.created_by','left');
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_expenses.vendor_id','left');
		
		
		
		$this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		$this->db->where('hms_expenses.branch_id = "'.$branch_ids.'"'); 
		$this->db->where('hms_expenses.id = "'.$ids.'"');
		$this->db->from($this->table);
		$query = $this->db->get();
        //echo $this->db->last_query();die;
        $result_expenses['expense_list']= $query->result();
		return $result_expenses;

	}

	function get_all_detail_print23092020($ids="",$branch_ids=""){
		$result_sales=array();
		$user_data = $this->session->userdata('auth_users');
		$users_data = $this->session->userdata('auth_users');
		//$this->db->select("hms_expenses.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name, (CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Vendor Payment' WHEN hms_expenses.type=10 THEN 'IPD Payment Refund' END) as expenses_type,hms_payment_mode.payment_mode"); 
		
		$this->db->select("hms_expenses.*, (CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN hms_expenses.type=0 THEN 'Expenses'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' WHEN hms_expenses.type=11 THEN 'OT Refund' WHEN hms_expenses.type=12 THEN 'Blood Bank Refund' WHEN hms_expenses.type=14 THEN 'OPD Billing Refund' WHEN hms_expenses.type=13 THEN 'Ambulance Refund' END) as expenses_type,hms_payment_mode.payment_mode,(CASE WHEN hms_expenses.type=0 THEN 'Expenses' WHEN hms_expenses.type=7 THEN (SELECT `hms_opd_booking`.`booking_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id` AND section_id=2 WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 7) WHEN hms_expenses.type=8 THEN (SELECT `path_test_booking`.`lab_reg_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 8) 
		
		WHEN hms_expenses.type=9 THEN 


(SELECT `hms_medicine_sale`.`sale_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 9)

WHEN hms_expenses.type=10 THEN 

(SELECT `hms_ipd_booking`.`ipd_no` FROM `hms_expenses` as exp LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `exp`.`parent_id`  WHERE `exp`.`id` =".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 10)


WHEN hms_expenses.type=11 THEN 

(SELECT `hms_ot_booking_ot_details`.`code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ot_booking_ot_details` ON `hms_ot_booking_ot_details`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 11)

WHEN hms_expenses.type=13 THEN 

(SELECT `hms_ambulance_booking`.`booking_no` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_ambulance_booking` ON `hms_ambulance_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 13)

WHEN hms_expenses.type=14 THEN

(SELECT `hms_opd_booking`.`reciept_code` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 14)

WHEN hms_expenses.type=2 THEN 


(SELECT `hms_medicine_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_medicine_purchase` ON `hms_medicine_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 2)
WHEN hms_expenses.type=5 THEN 

(SELECT `hms_vaccination_purchase`.`purchase_id` FROM `hms_expenses` as exp  LEFT JOIN `hms_vaccination_purchase` ON `hms_vaccination_purchase`.`id`  = `exp`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id']." AND `exp`.`type` = 5)


		END) as book_code,(SELECT `hms_patient`.`patient_name` FROM `hms_expenses` as exp left JOIN `hms_refund_payment` ON `hms_refund_payment`.`id` = `exp`.`parent_id`  LEFT JOIN `hms_patient` ON `hms_patient`.`id` = `hms_refund_payment`.`parent_id`  WHERE `exp`.`id` = ".$ids." AND `exp`.`branch_id` = ".$users_data['parent_id'].") as patient_name,hms_medicine_vendors.name as vendor_names");
		
		$this->db->where('hms_expenses.is_deleted','0'); 
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
		$this->db->join('hms_users','hms_users.id = hms_expenses.created_by','left');
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=hms_expenses.vendor_id','left');
		
	 	$this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		$this->db->where('hms_expenses.branch_id = "'.$branch_ids.'"'); 
		$this->db->where('hms_expenses.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_expenses['expense_list']= $this->db->get()->result();
		return $result_expenses;

	}

	function template_format($ids="")
	{
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_branch_print_expense_setting.*');
    	$this->db->where('branch_id  IN ('.$ids.')'); 
    	$this->db->from('hms_branch_print_expense_setting');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

        $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.type',6);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',6);
        $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
		return $query;
    }

    public function vendor_list()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');  
		$this->db->where('branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('vendor_type',2);
		$query = $this->db->get('hms_medicine_vendors');

		$result = $query->result(); 
		return $result; 
	} 

}
?>