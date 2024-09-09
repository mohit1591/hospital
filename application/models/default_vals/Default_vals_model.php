<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Default_vals_model extends CI_Model {

	var $table = 'path_default_vals';
	var $column = array('path_default_vals.id','path_default_vals.default_vals', 'path_default_vals.status','path_default_vals.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
       
		$this->db->select("path_default_vals.*"); 
		$this->db->from($this->table); 
        $this->db->where('path_default_vals.is_deleted','0');
           //if the user is branch
         
            $this->db->where('path_default_vals.branch_id',$users_data['parent_id']);
		
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
    
    public function default_vals_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('default_vals','ASC'); 
    	$query = $this->db->get('path_default_vals');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('path_default_vals.*');
		$this->db->from('path_default_vals'); 
		$this->db->where('path_default_vals.id',$id);
		$this->db->where('path_default_vals.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$test_list = $this->session->userdata('child_test_ids');

		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'default_vals'=>$post['default_vals'],
					'highlight'=>$post['highlight'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_default_vals',$data); 
			$last_id =  $post['data_id'];

			$this->db->where('default_val_id',$post['data_id']);
			$this->db->delete('path_default_val_to_test');
		}
		else
		{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('path_default_vals',$data);      
			$last_id = $this->db->insert_id();         
		} 	

		///// Child Test ///////////
        if($post['highlight']==2 || $post['highlight']==3)
        { 
        	foreach($test_list as $test_id)
        	{
        		$this->db->set('default_val_id',$last_id);
        		$this->db->set('highlight_type',$post['highlight']);
        		$this->db->set('test_id',$test_id);
        		$this->db->insert('path_default_val_to_test');
        	}
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
			$this->db->update('path_default_vals');
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
			$this->db->update('path_default_vals');
			//echo $this->db->last_query();die;
    	} 
    }

    public function addalltest($ids=array())
    {
        //check if array comes in json_encoded form means string in case of empty
        if(!is_array($ids)){
            $ids = json_decode($ids);
        }
        //ends
    	

        if(!empty($ids))
    	{
            //checks if array comes in json_enocded and not empty 
            if(!is_array($ids)){
                $ids = json_decode($ids);
            }
            //ends

    		$id_list = [];
    		foreach($ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
    		$test_child_ids = implode(',', $id_list);
			$this->db->select("test_name,id");
            $this->db->where('id IN ('.$test_child_ids.')');
            $this->db->from('path_test');
           
            // $this->db->group_by("path_test_under.parent_id");
            $query = $this->db->get(); 
            
            // echo $this->db->last_query();die;
            return $query->result_array();
    	
    	} 
    } 

    public function selected_test_child($id=''){
        $user_data = $this->session->userdata('auth_users');
     
        if(!empty($id)){
            
            $this->db->select("path_test.id,path_test.test_name");
            $this->db->from("path_default_val_to_test");
            $this->db->join("path_test","path_test.id=path_default_val_to_test.test_id");
            $this->db->where("path_default_val_to_test.default_val_id='".$id."'"); 
             
            $query = $this->db->get();
            // echo $this->db->last_query();die; 
            return $query->result();
           
        }


    }
  
}
?>