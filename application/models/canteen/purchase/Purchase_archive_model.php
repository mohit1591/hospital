<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase_archive_model extends CI_Model {

	var $table = 'hms_medicine_purchase';
	var $column = array('hms_medicine_purchase.id','hms_medicine_vendors.name','hms_medicine_purchase.purchase_id','hms_medicine_purchase.invoice_id','hms_medicine_purchase.id','hms_medicine_purchase.total_amount','hms_medicine_purchase.net_amount','hms_medicine_purchase.paid_amount','hms_medicine_purchase.balance','hms_medicine_purchase.created_date', 'hms_medicine_purchase.modified_date'); 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_purchase.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 
		$this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 
		$this->db->where('hms_medicine_purchase.is_deleted','1'); 
		$this->db->where('hms_medicine_purchase.branch_id = "'.$user_data['parent_id'].'"'); 
		$this->db->from($this->table); 
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
			$this->db->update('hms_medicine_purchase');
			
			//update status on stock
			$this->db->where('parent_id',$id);
			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','1');
            $query_d_pay = $this->db->get('hms_medicine_stock');

            $row_d_pay = $query_d_pay->result();

            if(!empty($row_d_pay))
            {
                  foreach($row_d_pay as $row_d)
                  {
                  	$stock_data = array(
                        'is_deleted'=>0);
                      
                  	 $this->db->where('id',$row_d->id);
                  	 $this->db->where('branch_id',$user_data['parent_id']);
                  	 $this->db->where('parent_id',$id);
                  	 $this->db->where('type',1);
                    $this->db->update('hms_medicine_stock',$stock_data);
                    //echo $this->db->last_query(); exit;
                }
            }
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
			$this->db->update('hms_medicine_purchase');
			
			//update status on stock
			$this->db->where('parent_id IN('.$branch_ids.')');
			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('type','1');
            $query_d_pay = $this->db->get('hms_medicine_stock');
            $row_d_pay = $query_d_pay->result();

            //echo "<pre>"; print_r($row_d_pay); exit;
            if(!empty($row_d_pay))
            {
                  foreach($row_d_pay as $row_d)
                  {
                  	$stock_data = array(
                        'is_deleted'=>0);
                      
                  	 $this->db->where('id',$row_d->id);
                  	 $this->db->where('branch_id',$user_data['parent_id']);
                  	 $this->db->where('parent_id IN('.$branch_ids.')');
                  	 $this->db->where('type',1);
                    $this->db->update('hms_medicine_stock',$stock_data);
                    //echo $this->db->last_query(); 
                }
            }
    	} 
    }

   public function trash($id="")
    {
        
		$user_data = $this->session->userdata('auth_users');
		$this->db->set('is_deleted',2);
		$this->db->set('deleted_by',$user_data['id']);
		$this->db->set('deleted_date',date('Y-m-d H:i:s'));
		$this->db->where('id',$id);
		$this->db->update('hms_medicine_purchase');
		//echo $this->db->last_query();die;
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
		    $user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_medicine_purchase');
			//echo $this->db->last_query();die;
    	} 
    }
 

}
?>