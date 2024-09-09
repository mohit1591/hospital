<?php
class Rate_model extends CI_Model 
{
	var $table = 'path_rate_plan';
	var $column = array('path_rate_plan.id','path_rate_plan.title','path_rate_plan.master_rate', 'path_rate_plan.base_rate', 'path_rate_plan.status', 'path_rate_plan.created_date', 'path_rate_plan.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		
		$this->db->select("path_rate_plan.*"); 
		$this->db->where('is_deleted','0'); 
		
            $this->db->where('path_rate_plan.branch_id',$users_data['parent_id']);
	
		$this->db->from($this->table); 
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

	public function get_by_id($id)
	{
		$this->db->select('path_rate_plan.*');
		$this->db->from('path_rate_plan'); 
		$this->db->where('path_rate_plan.id',$id);
		$this->db->where('path_rate_plan.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $data = array(  
                            'title'=>$post['title'],
                            'master_rate'=>$post['master_rate'], 
                            'base_rate'=>$post['base_rate'],
                            'master_type'=>$post['base_type'],
                            'base_type'=>$post['base_type'],
                            'status'=>$post['status'], 
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'modified_by'=>$user_data['id'],
                            'modified_date'=>date('Y-m-d H:i:s')
				         );
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_rate_plan',$data); 
		}
		else{   
			$data = array(
				            'branch_id'=>$user_data['parent_id'],
                            'title'=>$post['title'],
                            'master_rate'=>$post['master_rate'], 
                            'base_rate'=>$post['base_rate'],
                            'master_type'=>$post['base_type'],
                            'base_type'=>$post['base_type'],
                            'status'=>$post['status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'created_by'=>$user_data['id'],
                            'created_date'=>date('Y-m-d H:i:s')
				         );
			$this->db->insert('path_rate_plan',$data);                
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
			$this->db->update('path_rate_plan');
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
			$this->db->update('path_rate_plan');
    	} 
    }

    public function rate_list()
    {
       $user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('title','ASC'); 
    	$query = $this->db->get('path_rate_plan');
		return $query->result();
    } 
     
} 
?>