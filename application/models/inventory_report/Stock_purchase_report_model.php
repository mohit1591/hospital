<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Stock_purchase_report_model extends CI_Model 
{
	var $table = 'path_purchase_item';

	var $column = array('path_purchase_item.id','path_purchase_item.purchase_no','path_purchase_item.purchase_date','	hms_medicine_vendors.name','path_purchase_item.net_amount','path_purchase_item.balance');  
    var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('stock_purchase_report_search_gstr1');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("path_purchase_item_to_purchase.*,path_purchase_item.net_amount, path_purchase_item.purchase_no, path_purchase_item.purchase_date, hms_medicine_vendors.name"); 
        $this->db->join('path_purchase_item','path_purchase_item.id =path_purchase_item_to_purchase.purchase_id');
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left'); 
        $this->db->where('path_purchase_item.is_deleted','0'); 
        $this->db->where('path_purchase_item.branch_id',$users_data['parent_id']); 
		$this->db->from('path_purchase_item_to_purchase');  
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_item.purchase_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_item.purchase_date <= "'.$end_date.'"');
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
				$this->db->where('path_purchase_item.created_by IN ('.$emp_ids.')');
			}
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
		$search = $this->session->userdata('stock_purchase_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->where('path_purchase_item.is_deleted','0'); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left');
		$this->db->where('path_purchase_item.branch_id',$users_data['parent_id']); 
	
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_item.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_item.created_date <= "'.$end_date.'"');
			}
		}

		if(!empty($search['purchase_no']))
		{
          $this->db->where('path_purchase_item.purchase_no LIKE"'.$search['purchase_no'].'%"');
		}

		if(!empty($search['vendor_code']))
		{
		  $this->db->where('hms_medicine_vendors.name LIKE "'.$search['vendor_code'].'%"');
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
				$this->db->where('path_purchase_item.created_by IN ('.$emp_ids.')');
			}
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function search_report_data()
	{
		$search = $this->session->userdata('stock_purchase_report_search_gstr1');

		$this->db->select("path_purchase_item_to_purchase.*,path_purchase_item.net_amount, path_purchase_item.purchase_no, path_purchase_item.purchase_date, hms_medicine_vendors.name"); 
        $this->db->join('path_purchase_item','path_purchase_item.id =path_purchase_item_to_purchase.purchase_id');
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = path_purchase_item.vendor_id','left'); 
        $this->db->where('path_purchase_item.is_deleted','0'); 
        $this->db->where('path_purchase_item.branch_id',$users_data['parent_id']); 
		$this->db->from('path_purchase_item_to_purchase'); 
	
		$this->db->from($this->table); 
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('path_purchase_item.purchase_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('path_purchase_item.purchase_date <= "'.$end_date.'"');
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
				$this->db->where('path_purchase_item.created_by IN ('.$emp_ids.')');
			}
		 $result= $this->db->get()->result();
		 return $result;
	}

  

}
 
?>