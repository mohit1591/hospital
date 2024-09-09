<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_profile_archive_model extends CI_Model {

	var $table = 'path_profile';
var $column = array('path_profile.id','path_profile.profile_name','path_profile.print_name','path_profile.master_rate','path_profile.base_rate',  'path_profile.sort_order','path_profile.status','path_profile.created_date');  
	var $order = array('sort_order'=>'asc','id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata("auth_users");
		$this->db->select("path_profile.*"); 
		$this->db->from($this->table);
        $this->db->where('path_profile.is_deleted','1');
     

       
        //$this->db->where('path_profile.branch_id',$users_data['parent_id']);
        if($users_data['users_role']=='2')
        {
            if(!empty($branch_id))
            {
                if($branch_id=='inherit')
                {
                  
                    if(!empty($parent_branch_details)){
                        $id_list = [];
                        foreach($parent_branch_details as $id){
                            if(!empty($id) && $id>0){
                                $id_list[]  = $id['parent_id'];
                            } 
                        }
                        $branch_ids = implode(',', $id_list);
                        
                        $this->db->where('path_profile.branch_id IN('.$branch_ids.')');
                    }
                    $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'") AND is_deleted!=2)');
                }
                else if($branch_id==$users_data['parent_id'])
                {
                    $this->db->where('path_profile.branch_id',$users_data['parent_id']);
                      $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                
                }
                else{
                    $this->db->where('path_profile.branch_id',$branch_id);
                      $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                }
            }
            else
            {
                $this->db->where('path_profile.branch_id',$users_data['parent_id']);
            }
        }
        else if($users_data['users_role']=='3')
        {
            $this->db->where('path_profile.branch_id',$company_data['id']);
        }
        else if($users_data['users_role']=='1')
        {
            $this->db->where('path_profile.branch_id',$users_data['parent_id']);
        }
		//$this->db->where('path_profile.branch_id = "'.$users_data['parent_id'].'"');
		
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

	public function restore($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_profile');
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
    		$emp_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$emp_ids.')');
			$this->db->update('path_profile');
    	} 
    }

    public function trash($id="")
    {
   //  	if(!empty($id) && $id>0)
   //  	{  
			// $this->db->where('id',$id);
			// $this->db->delete('path_profile');
   //  	} 
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_profile');
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
			// $this->db->delete('path_profile');
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
			$this->db->update('path_profile');
    	}  
    }
 

}
?>