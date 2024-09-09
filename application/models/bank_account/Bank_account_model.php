<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_account_model extends CI_Model {

	var $table = 'hms_bank_account';
	var $column = array('hms_bank_account.id','hms_bank_account.account_holder','hms_bank_account.bank_name','hms_bank_account.account_no','hms_bank_account.type', 'hms_bank_account.status','hms_bank_account.created_date','hms_bank_account.modified_date');  
	var $order = array('hms_bank_account.id' => 'desc');

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
			$this->db->select("hms_bank_account.*,hms_bank.bank_name"); 
			$this->db->from($this->table); 
			$this->db->where('hms_bank_account.is_deleted','0');
			$this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
  
            $this->db->where('hms_bank_account.branch_id',$users_data['parent_id']);
		
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
    
    public function bank_account_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('account_holder','ASC'); 
    	$query = $this->db->get('hms_bank_account');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_bank_account.*');
		$this->db->from('hms_bank_account'); 
		$this->db->where('hms_bank_account.id',$id);
		$this->db->where('hms_bank_account.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}

	public function get_bank_namewith_account()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_bank_account.*,hms_bank.bank_name');
		$this->db->from('hms_bank_account'); 
		$this->db->where('hms_bank_account.branch_id',$user_data['parent_id']);
		$this->db->join('hms_bank','hms_bank.id=hms_bank_account.bank_name','left');
		$this->db->where('hms_bank_account.is_deleted','0');
		$query = $this->db->get(); 
		return $query->result();
	}

	public function get_bank_name($bid=""){
			$user_data = $this->session->userdata('auth_users');
			$this->db->select('hms_bank.*');
			$this->db->where('branch_id',$user_data['parent_id']); 
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
					'account_holder'=>$post['account_holder'],
					'bank_name'=>$post['bank_name'],
					'account_no'=>$post['account_no'],
					'type'=>$post['type'],
					'ifsc_code'=>$post['ifsc_code'],
					'micr_code'=>$post['micr_code'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_bank_account',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_bank_account',$data);               
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
			$this->db->update('hms_bank_account');
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
			$this->db->update('hms_bank_account');
			//echo $this->db->last_query();die;
    	} 
    }
  

}
?>