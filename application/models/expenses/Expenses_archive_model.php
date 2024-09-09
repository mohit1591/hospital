<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expenses_archive_model extends CI_Model {

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
        $this->db->select("hms_expenses.*,hms_expenses_category.exp_category, (CASE WHEN hms_expenses.type=0 THEN 'Expenses'  WHEN hms_expenses.type=4 THEN 'Inventory Purchase' WHEN hms_expenses.type=1 THEN 'Employee Salary' WHEN hms_expenses.type=2 THEN 'Medicine Purchase' WHEN hms_expenses.type=3 THEN 'Medicine Sale Return' WHEN hms_expenses.type=5 THEN  'Vaccine Purchase' WHEN hms_expenses.type=6 THEN  'Vaccine Billing Return' WHEN hms_expenses.type=7 THEN 'OPD Payment Refund'  WHEN hms_expenses.type=8 THEN 'Pathology Payment Refund'  WHEN hms_expenses.type=9 THEN 'Medicine Payment Refund' WHEN hms_expenses.type=10 THEN 'IPD Refund' END) as expenses_type,hms_payment_mode.payment_mode as p_mode"); 
		$this->db->from($this->table); 
		$this->db->join("hms_expenses_category","hms_expenses.paid_to_id=hms_expenses_category.id",'left');
		//$this->db->where('hms_expenses.type','0');
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_expenses.payment_mode');
		$this->db->where('hms_expenses.is_deleted="1"');
        $this->db->where('hms_expenses.branch_id = "'.$users_data['parent_id'].'"');
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
			$this->db->update('hms_expenses');
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
    		$branch_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_expenses');
    	} 
    }

    public function trash($id="")
    {
   //  	if(!empty($id) && $id>0)
   //  	{  
			// $this->db->where('id',$id);
			// $this->db->delete('hms_expenses');
   //  	} 
    		if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_expenses');
			//echo $this->db->last_query();die;
    	} 
    }

    public function trashall($ids=array())
    {
   //  	if(!empty($ids))
   //  	{
   //  		$id_list = [];
   //  		foreach($ids as $id)
   //  		{
   //  			if(!empty($id) && $id>0)
   //  			{
   //                $id_list[]  = $id;
   //  			} 
   //  		}
   //  		$branch_ids = implode(',', $id_list); 
			// $this->db->where('id IN ('.$branch_ids.')');
			// $this->db->delete('hms_expenses');
   //  	} 
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
			$this->db->update('hms_expenses');
		}
    }
 

}
?>