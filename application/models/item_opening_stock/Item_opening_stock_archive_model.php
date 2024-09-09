<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_opening_stock_archive_model extends CI_Model {

	var $table = 'path_item';
	var $column = array('path_item.item_code','path_item.item','path_item.price','path_stock_category.category','path_item.qty','hms_stock_item_unit.unit','path_item.status','path_item.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $search= $this->session->userdata('stock_item_serach');
		
		$this->db->select("path_item.*,path_stock_category.category,hms_stock_item_unit.unit,sum(path_stock_item.debit-path_stock_item.credit)as stock_qty,hms_inventory_racks.rack_no"); 
		$this->db->from($this->table); 
        $this->db->where('path_item.is_deleted','1');
        $this->db->where('path_stock_item.type',5);
        $this->db->join('path_stock_category','path_stock_category.id=path_item.category_id','left');
        $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
        $this->db->join('path_stock_item','path_stock_item.parent_id=path_item.id','left');

        $this->db->join('hms_inventory_racks','hms_inventory_racks.id = path_item.rack_no','left');
        
        $this->db->where('path_item.branch_id',$users_data['parent_id']);
        $this->db->order_by("path_item.id", "DESC");

        if(!empty($search))
        {

        	if(isset($search['item_code']) && !empty($search['item_code']))
        	{
               $this->db->where('path_item.item_code = "'.$search['item_code'].'"');
        	}
        	if(isset($search['item_name']) && !empty($search['item_name']))
        	{
        		$this->db->where('path_item.item LIKE "%'.$search['item_name'].'%"');
        	}
        	if(isset($search['category']) && !empty($search['category']))
        	{
        		$this->db->where('path_stock_category.category LIKE "%'.$search['category'].'%"');
        	}
			if(isset($search['rack_no']) && !empty($search['rack_no']))
			{
			$this->db->where('hms_inventory_racks.rack_no LIKE "%'.$search['rack_no'].'%"');
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
		elseif(!empty($search["employee"]) && is_numeric($search['employee']))
		{
			$emp_ids=  $search["employee"];
		}


		if(isset($emp_ids) && !empty($emp_ids))
		{ 
			$this->db->where('path_item.created_by IN ('.$emp_ids.')');
		}
        $this->db->group_by('path_item.id');
		
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
			$this->db->update('path_item');
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
			$this->db->update('path_item');
    	} 
    }

    public function trash($id="")
    {
   //  	if(!empty($id) && $id>0)
   //  	{  
			// $this->db->where('id',$id);
			// $this->db->delete('path_stock_category');
   //  	} 
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_item');
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
			// $this->db->delete('path_stock_category');
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
			$this->db->update('path_item');
    	}  
    }
 

}
?>