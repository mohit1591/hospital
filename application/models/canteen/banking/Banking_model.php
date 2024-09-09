<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Banking_model extends CI_Model {

	var $table = 'hms_banking';
	var $column = array('hms_banking.id','hms_banking.account_id','hms_banking.deposite_date','hms_banking.amount', 'hms_bank_account.status','hms_bank_account.created_date','hms_bank_account.modified_date');  
	var $order = array('hms_banking.id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
			$users_data = $this->session->userdata('auth_users');

			$search=$this->session->userdata('bank_search');


			$parent_branch_details = $this->session->userdata('parent_branches_data');
			$sub_branch_details = $this->session->userdata('sub_branches_data');
			$this->db->select("hms_banking.*,hms_bank_account.account_holder,hms_bank_account.account_no,hms_bank.bank_name"); 
			$this->db->from($this->table); 
			$this->db->where('hms_banking.is_deleted','0');
			$this->db->join('hms_bank_account','hms_bank_account.id=hms_banking.account_id','left');
			$this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
         
            $this->db->where('hms_banking.branch_id',$users_data['parent_id']);

			if(isset($search) && !empty($search))
			{
			if(isset($search['start_date']) && !empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_banking.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_banking.created_date <= "'.$end_date.'"');
			}

			if(isset($search['amount']) && !empty($search['amount']))
			{
			$this->db->where('hms_banking.amount LIKE "'.$search['amount'].'%"');
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

	function search_report_data(){

		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('bank_search');

		$this->db->select("hms_banking.*,hms_bank_account.account_holder,hms_bank_account.account_no,hms_bank.bank_name"); 
		$this->db->from($this->table); 
		$this->db->where('hms_banking.is_deleted','0');
		$this->db->join('hms_bank_account','hms_bank_account.id=hms_banking.account_id','left');
		$this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');

		$this->db->where('hms_banking.branch_id',$user_data['parent_id']);
		
		if(isset($search) && !empty($search))
		{
			if(isset($search['start_date']) && !empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_banking.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_banking.created_date <= "'.$end_date.'"');
			}

			if(isset($search['amount']) && !empty($search['amount']))
			{
			$this->db->where('hms_banking.amount LIKE "'.$search['amount'].'%"');
			}

		}
		 $query = $this->db->get(); 

		$data= $query->result();
		
		return $data;
        //$this->db->group_by('hms_medicine_stock.id');
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
    
    public function banking_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('id','ASC'); 
    	$query = $this->db->get('hms_banking');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_banking.*');
		$this->db->from('hms_banking'); 
		$this->db->where('hms_banking.id',$id);
		$this->db->where('hms_banking.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function get_bank_name($bid=""){
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('hms_bank.*');
			//$this->db->where('branch_id',$user_data['parent_id']); 
			if(!empty($bid)){
				$this->db->where('id',$bid);
			}
			$query = $this->db->get('hms_bank');
			//echo $this->db->last_query();
			return $query->result();
	
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'account_id'=>$post['account_id'],
					'amount'=>$post['amount'],
					'deposite_date'=>date('Y-m-d',strtotime($post['deposite_date'])),
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_banking',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_banking',$data);               
		} 	
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
			$this->db->update('hms_banking');
			//echo $this->db->last_query();die;
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
			$this->db->update('hms_banking');
			//echo $this->db->last_query();die;
    	} 
    }
  

}
?>