<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_entry_archive_model extends CI_Model {

	var $table = 'hms_medicine_entry';
	var $column = array('hms_medicine_entry.id','hms_medicine_entry.medicine_code', 'hms_medicine_entry.medicine_name', 'hms_medicine_entry.unit_id','hms_medicine_entry.unit_second_id','hms_medicine_entry.conversion','hms_medicine_entry.min_alrt','hms_medicine_entry.packing','hms_medicine_entry.rack_no','hms_medicine_entry.salt','hms_medicine_entry.manuf_company','hms_medicine_entry.mrp','hms_medicine_entry.purchase_rate','hms_medicine_entry.vat','hms_medicine_entry.status', 'hms_medicine_entry.created_date', 'hms_medicine_entry.modified_date'); 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_entry.*,hms_medicine_racks.rack_no ,hms_medicine_racks.id as rack_id"); 
		$this->db->from($this->table);
		$this->db->where('hms_medicine_entry.branch_id  IN ('.$user_data['parent_id'].',0)');  
		$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
        $this->db->where('hms_medicine_entry.is_deleted','1');
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
			$this->db->update('hms_medicine_entry');
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
			$this->db->update('hms_medicine_entry');
    	} 
    }

    public function trash($id="")
    {

    	if(!empty($id) && $id>0)
    	{  
			$this->db->where('id',$id);
			$this->db->delete('hms_medicine_entry');
    	} 
    }

    public function trashall($ids=array())
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
			$this->db->where('id IN ('.$branch_ids.')');
			//$this->db->delete('hms_doctors');
    	} 
    }
 

}
?>