<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_opening_stock_archive_model extends CI_Model {

		var $table = 'hms_vaccination_stock';
	var $column = array('hms_vaccination_entry.id','hms_vaccination_entry.vaccination_code', 'hms_vaccination_entry.vaccination_name', 'hms_vaccination_entry.unit_id','hms_vaccination_entry.unit_second_id','hms_vaccination_entry.conversion','hms_vaccination_entry.min_alrt','hms_vaccination_entry.packing','hms_vaccination_entry.rack_no','hms_vaccination_entry.salt','hms_vaccination_entry.manuf_company','hms_vaccination_entry.mrp','hms_vaccination_entry.purchase_rate','hms_vaccination_entry.discount','hms_vaccination_entry.vat','hms_vaccination_entry.status', 'hms_vaccination_entry.created_date', 'hms_vaccination_entry.modified_date');   
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$search=$this->session->userdata('stock_search');
		$this->db->select('hms_vaccination_stock.*, hms_vaccination_racks.rack_no as rack_nu, hms_vaccination_entry.*,hms_vaccination_entry.id as m_eid,hms_vaccination_entry.created_date as create,hms_vaccination_company.company_name');
		$this->db->join('hms_vaccination_entry','hms_vaccination_entry.id = hms_vaccination_stock.m_id');   
		
		//$this->db->join('hms_medicine_purchase_to_purchase','hms_medicine_purchase_to_purchase.medicine_id = hms_vaccination_stock.m_id','left'); 

		$this->db->join('hms_vaccination_racks','hms_vaccination_racks.id = hms_vaccination_entry.rack_no','left');
		$this->db->join('hms_vaccination_company','hms_vaccination_company.id = hms_vaccination_entry.manuf_company','left');
		//$this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_vaccination_stock where m_id = hms_vaccination_entry.id)>0');
		$this->db->where('hms_vaccination_entry.is_deleted','1'); 
		//$this->db->join('hms_vaccination_unit','hms_vaccination_unit.id = hms_vaccination_entry.unit_id','left');
		//$this->db->join('hms_vaccination_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_vaccination_entry.unit_second_id','left');
		$this->db->where('hms_vaccination_entry.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->where('hms_vaccination_stock.type','6');
		$this->db->from($this->table); 
		$this->db->group_by('hms_vaccination_stock.batch_no');
		$this->db->group_by('hms_vaccination_stock.m_id');
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
			$this->db->update('hms_vaccination_stock');
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
			$this->db->update('hms_vaccination_stock');
    	} 
    }

    public function trash($id="")
    {
    	if(!empty($id) && $id>0)
    	{  
			$this->db->where('id',$id);
			$this->db->delete('hms_vaccination_stock');
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