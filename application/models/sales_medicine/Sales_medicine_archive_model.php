<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_medicine_archive_model extends CI_Model {

	var $table = 'hms_medicine_sale';
	var $column = array('hms_medicine_sale.id','hms_patient.patient_name','hms_medicine_sale.sale_no','hms_medicine_sale.id','hms_medicine_sale.total_amount','hms_medicine_sale.net_amount','hms_medicine_sale.paid_amount','hms_medicine_sale.balance','hms_medicine_sale.created_date', 'hms_medicine_sale.modified_date'); 
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_medicine_sale.*, hms_patient.patient_name,hms_doctors.doctor_name,, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine"); 
		$this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
		$this->db->where('hms_medicine_sale.is_deleted','1'); 
		$this->db->where('hms_medicine_sale.branch_id = "'.$user_data['parent_id'].'"');
		$this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left'); 
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
			$this->db->update('hms_medicine_sale');
			
			
			// Update batch stock
            $this->db->select('hms_medicine_sale_to_medicine.medicine_id, hms_medicine_sale_to_medicine.batch_no, hms_medicine_sale_to_medicine.qty'); 
            $this->db->where('hms_medicine_sale_to_medicine.sales_id',$id); 
            $query = $this->db->get('hms_medicine_sale_to_medicine');
            $sale_to_medicine_data =  $query->result_array();
            if(!empty($sale_to_medicine_data))
            {
                foreach($sale_to_medicine_data as $sale_to_medicine)
                {
                     $this->db->query("UPDATE `hms_medicine_batch_stock` SET `hms_medicine_batch_stock`.`quantity` = (`hms_medicine_batch_stock`.`quantity`-'".$sale_to_medicine['qty']."') WHERE `hms_medicine_batch_stock`.`batch_no` = '".$sale_to_medicine['batch_no']."' AND `hms_medicine_batch_stock`.`medicine_id` = '".$sale_to_medicine['medicine_id']."' "); 
                }
            }
          // End update batch stock
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
			$this->db->update('hms_medicine_sale');
    	} 
    }
 public function trash($id="")
    {
    	if(!empty($id) && $id>0)
    	{  
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_medicine_sale');
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
		    $user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_medicine_sale');
			//$this->db->delete('hms_doctors');
    	} 
    }
 

}
?>