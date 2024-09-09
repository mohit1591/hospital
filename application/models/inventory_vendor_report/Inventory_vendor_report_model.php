<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory_vendor_report_model extends CI_Model {
var $table = 'path_item';
    var $column = array('path_stock_item.id','path_item.item_code','path_item.item','path_item.item','path_item.item','path_stock_item.created_date');  
    var $order = array('path_stock_item.id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
	{
		$search = $this->session->userdata('inventory_allot_items_search');
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("path_item.id,path_item.item as item_name,path_item.item_code,hms_inventory_company.company_name,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name,(SELECT GROUP_CONCAT(DISTINCT serial_no SEPARATOR ', ') FROM inv_stock_serial_no where inv_stock_serial_no.item_id=path_stock_item.item_id AND inv_stock_serial_no.module_id=1 AND inv_stock_serial_no.issue_status=1) as serial_numbers,(SELECT stock_id from inv_stock_serial_no where inv_stock_serial_no.item_id=path_stock_item.item_id AND inv_stock_serial_no.module_id=1 limit 1) as purchase_id");
		$this->db->from('path_item');
		$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');

		$this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
			}
			if(!empty($search['branch_id']))
			{
			$this->db->where('path_stock_item.parent_id',$search['branch_id']);
			}
			
			
		}
		
		//$this->db->order_by('path_stock_item.id','DESC');
		
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
    

    function search_medicine_data()
    {
       
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('inventory_allot_items_search');
        
        $this->db->select("path_item.id,path_item.item as item_name,path_item.item_code,hms_inventory_company.company_name,path_stock_item.credit as quantity,path_stock_item.created_date,path_stock_item.parent_id,hms_branch.branch_name,(SELECT GROUP_CONCAT(DISTINCT serial_no SEPARATOR ', ') FROM inv_stock_serial_no where inv_stock_serial_no.item_id=path_stock_item.item_id AND inv_stock_serial_no.module_id=1 AND inv_stock_serial_no.issue_status=1) as serial_numbers,(SELECT stock_id from inv_stock_serial_no where inv_stock_serial_no.item_id=path_stock_item.item_id AND inv_stock_serial_no.module_id=1 limit 1) as purchase_id");
		$this->db->from('path_item');
		$this->db->join('hms_inventory_company','path_item.manuf_company=hms_inventory_company.id','left');

		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');

		$this->db->join('hms_branch','path_stock_item.parent_id=hms_branch.id','left');
		$this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_stock_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_stock_item.created_date <= "'.$end_date.'"');
			}
			if(!empty($search['branch_id']))
			{
			$this->db->where('path_stock_item.parent_id',$search['branch_id']);
			}
		}
		//$this->db->where('path_stock_item.item_id',$item_id);
		$this->db->order_by('path_stock_item.id','DESC');
		$query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }
    
      public function get_medicine_count($purchase_id='')
    {
        $this->db->select("path_purchase_item_to_purchase.qty,hms_medicine_vendors.name,path_item.item as item_name,path_item.item_code,hms_medicine_vendors.name as vendor_name,path_purchase_item.purchase_date,(SELECT GROUP_CONCAT(DISTINCT serial_no SEPARATOR ', ') FROM inv_stock_serial_no where inv_stock_serial_no.stock_id=path_purchase_item.id AND inv_stock_serial_no.module_id=1) as serial_numbers,path_stock_item.price as purchase_amount,path_purchase_item_to_purchase.mrp,path_purchase_item_to_purchase.discount,path_purchase_item_to_purchase.total_amount,path_purchase_item_to_purchase.per_pic_price");
        $this->db->join('path_purchase_item','path_purchase_item.id = path_purchase_item_to_purchase.purchase_id','left');
        
        $this->db->join('path_stock_item','path_stock_item.item_id = path_purchase_item_to_purchase.item_id AND path_stock_item.parent_id=path_purchase_item_to_purchase.purchase_id AND path_stock_item.type=1','left');
        
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
        $this->db->join('path_item','path_item.id = path_purchase_item_to_purchase.item_id','left');
        $this->db->where('path_purchase_item.is_deleted','0'); 
		$this->db->where('path_purchase_item.branch_id',$users_data['parent_id']);
        $result_sales=$this->db->get()->result();
        return $total = count($result_sales);
    }

    public function get_items_allot($parent_id)
	{
	    
	   $users_data = $this->session->userdata('auth_users');
		$this->db->select('path_stock_item.credit as quantity');
		$this->db->from('path_item');
		$this->db->join('path_stock_item','path_stock_item.item_id=path_item.id');
        $this->db->where('path_stock_item.branch_id',$users_data['parent_id']);
		$this->db->where('path_stock_item.type',5);
		$this->db->where('path_stock_item.credit > 0');  
		
	
		$this->db->where('path_stock_item.item_id',$parent_id);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$this->db->limit(1);
		$result = $query->result();
		//echo "<pre>"; print_r($result); die;
	    $name = $result[0]->quantity;
		return $name;
	}
	
	public function vendor_details($purchase_id='')
	{
	    
	   $users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_medicine_vendors.name');
		$this->db->from('path_purchase_item');
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id=path_purchase_item.vendor_id');
        $this->db->where('path_purchase_item.branch_id',$users_data['parent_id']);
	    $this->db->where('path_purchase_item.id',$purchase_id);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		$this->db->limit(1);
		$result = $query->result();
		//echo "<pre>"; print_r($result); die;
	    $vendor_name = $result[0]->name;
		return $vendor_name;
	}
}
?>