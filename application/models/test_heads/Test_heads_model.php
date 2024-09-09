<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_heads_model extends CI_Model {

	var $table = 'path_test_heads';
	var $column = array('path_test_heads.id','hms_department.department', 'path_test_heads.test_heads','path_test_heads.sort_order', 'path_test_heads.status','path_test_heads.created_date');  
	var $order = array('sort_order'=>'asc','id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
       
		$this->db->select("path_test_heads.*, hms_department.department"); 
		$this->db->join("hms_department", "hms_department.id = path_test_heads.dept_id","left"); 
		$this->db->from($this->table);  
		 $this->db->where('path_test_heads.is_deleted','0');
          
            $this->db->where('path_test_heads.branch_id',$users_data['parent_id']);
		
      
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
		// echo $this->db->last_query();die;
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
    
    public function test_heads_list($dept_id="",$branch_id="")
    {
    	$users_data = $this->session->userdata('auth_users');
    	
		if($branch_id!='' || $branch_id!='0')
		{
		   $branch_id = $branch_id;
		}
		else
		{
		   $branch_id = $users_data['parent_id'];
		   if($users_data['users_role']!=1 && $users_data['users_role']!=2)
			{
			  $company_data = $this->session->userdata('company_data');
			  $branch_id = $company_data['id'];
			} 
		}  
    	$this->db->select('*');
    	$this->db->where('branch_id',$branch_id);
    	if(!empty($dept_id) && $dept_id>0)
    	{
    		$this->db->where('dept_id',$dept_id);  
    	}
    	$this->db->where('status',1);  
        $this->db->where('is_deleted',0);  
    	$this->db->order_by('sort_order','ASC'); 
    	$query = $this->db->get('path_test_heads'); 
		//echo $this->db->last_query();die;
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('path_test_heads.*');
		$this->db->from('path_test_heads'); 
		$this->db->where('path_test_heads.id',$id); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'dept_id'=>$post['dept_id'],
					'test_heads'=>$post['test_heads'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_test_heads',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('path_test_heads',$data);               
		} 	
	}

    public function delete($id="")
    {
   //  	if(!empty($id) && $id>0)
   //  	{

			// $user_data = $this->session->userdata('auth_users');  
			// $this->db->where('id',$id);
			// $this->db->delete('path_test_heads'); 
   //  	} 

    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_test_heads');
		}
    }

    public function deleteall($ids=array())
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
			// $user_data = $this->session->userdata('auth_users'); 
			// $this->db->where('id IN ('.$branch_ids.')');
			// $this->db->delete('path_test_heads'); 
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
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('path_test_heads');
		}
    }
     public function save_sort_order_data($id='',$sort_order_value=''){
        if(!empty($id) && !empty($sort_order_value)){
            $this->db->set('sort_order',$sort_order_value);
            $this->db->where('id',$id);
            $result='';
            if($this->db->update('path_test_heads')){
                $result='true';
                return $result;
            }

        }
    }

    public function check_duplicate($str="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('path_test_heads.*');
		$this->db->from('path_test_heads'); 
		if(!empty($str))
		{
          $this->db->where('path_test_heads.test_heads',trim($str));
		} 
		$this->db->where('path_test_heads.branch_id',$user_data['parent_id']); 
		$query = $this->db->get(); 
		return $query->row_array();
    }
   

}
?>