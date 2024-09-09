<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_print_test_name_setting_model extends CI_Model {

	var $table = 'path_print_test_name_setting';
	var $column = array('path_print_test_name_setting.id','path_print_test_name_setting.module_name', 'path_print_test_name_setting.module','path_print_test_name_setting.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("path_print_test_name_setting.id,path_print_test_name_setting.branch_id,path_print_test_name_setting.module_name,path_print_test_name_setting.module,path_print_test_name_setting.profile_status,path_print_test_name_setting.print_status,path_print_test_name_setting.created_date");
		
		$this->db->from('path_print_test_name_setting'); 
		$this->db->where('path_print_test_name_setting.branch_id = "'.$users_data['parent_id'].'"');
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
    
    

	
	 public function update_profile_status($branch_id='',$module='',$status='')
     {
        if(!empty($module))
        {
			if(!empty($status))
        	{
        		$status = $status;
        	}
        	else
        	{
        		$status=0;
        	}
	    		$data = array( 
								'profile_status'=>$status,
							 );

        	$this->db->where('branch_id',$branch_id);
        	$this->db->where('module',$module);
        	$this->db->update('path_print_test_name_setting',$data);
        	//echo $this->db->last_query(); exit;
			return true;
        	
        }
    }

    public function update_print_status($branch_id='',$module='',$status='')
     {
        if(!empty($module))
        {
			if(!empty($status))
        	{
        		$status = $status;
        	}
        	else
        	{
        		$status=0;
        	}
	    		$data = array( 
								'print_status'=>$status,
							 );

        	$this->db->where('branch_id',$branch_id);
        	$this->db->where('module',$module);
        	$this->db->update('path_print_test_name_setting',$data);
			return true;
        	//echo $this->db->last_query();
        }
    }

}
?>