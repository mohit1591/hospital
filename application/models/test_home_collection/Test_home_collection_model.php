<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_home_collection_model extends CI_Model 
{

	var $table = 'hms_test_home_collection';
	var $column = array('hms_test_home_collection.id','hms_test_home_collection.status','hms_test_home_collection.charge','hms_test_home_collection.created_date','hms_test_home_collection.modified_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
	    $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_test_home_collection.*"); 
		$this->db->from($this->table); 
		//$this->db->where('hms_test_home_collection.is_deleted','0'); 
        $this->db->where('hms_test_home_collection.branch_id', $users_data['parent_id']); 
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
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_test_home_collection.*');
		$this->db->from('hms_test_home_collection'); 
		$this->db->where('hms_test_home_collection.branch_id',$user_data['parent_id'] );
        $this->db->where('id',$id);
		$query = $this->db->get(); 
		return $query->row_array();
	}


    public function update_home_collection($data_array,$rec_id)
    {
        $this->db->where('id',$rec_id);
        $this->db->update('hms_test_home_collection',$data_array);
        return "200";
    }
	
    

}
?>