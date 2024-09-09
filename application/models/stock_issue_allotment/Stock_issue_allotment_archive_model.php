<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_issue_allotment_archive_model extends CI_Model {
	var $table = 'hms_stock_issue_allotment';
	var $column = array('hms_stock_issue_allotment.id','hms_stock_issue_allotment.issue_no','hms_stock_issue_allotment.user_type_id','hms_stock_issue_allotment.total_amount','hms_stock_issue_allotment.issue_date','hms_stock_issue_allotment.created_date', 'hms_employees.name', 'hms_patient.patient_name', 'hms_doctors.doctor_name');  
    var $order = array('id' => 'desc');   
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$search = $this->session->userdata('stock_issue_allotment_search');

		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_stock_issue_allotment.*,hms_employees.name, hms_patient.patient_name, hms_doctors.doctor_name,(CASE WHEN hms_stock_issue_allotment.user_type=1 THEN (select hms_employees.name from hms_employees where id=hms_stock_issue_allotment.user_type_id)  WHEN hms_stock_issue_allotment.user_type=2 THEN (select hms_patient.patient_name from hms_patient where id=hms_stock_issue_allotment.user_type_id) WHEN hms_stock_issue_allotment.user_type=3 THEN (select hms_doctors.doctor_name from hms_doctors where id=hms_stock_issue_allotment.user_type_id) ELSE 'N/A' END) as member_name"); 
		$this->db->where('hms_stock_issue_allotment.is_deleted','1'); 
		
		$this->db->join('hms_employees','hms_employees.id = hms_stock_issue_allotment.user_type_id','left'); 
		$this->db->join('hms_patient','hms_patient.id = hms_stock_issue_allotment.user_type_id','left'); 
		$this->db->join('hms_doctors','hms_doctors.id = hms_stock_issue_allotment.user_type_id','left'); 
		
		$this->db->where('hms_stock_issue_allotment.branch_id',$users_data['parent_id']); 
	
		$this->db->from($this->table); 
		$i = 0;
		if(isset($search) && !empty($search))
		{
			if(!empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_stock_issue_allotment.created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_stock_issue_allotment.created_date <= "'.$end_date.'"');
			}
			
			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
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
			$this->db->where('hms_stock_issue_allotment.created_by IN ('.$emp_ids.')');
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
			$this->db->update('hms_stock_issue_allotment');
    	} 
		
		if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('parent_id',$id);
			$this->db->update('path_stock_item');
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
			$this->db->update('hms_stock_issue_allotment');
    	}

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
			$this->db->where('parent_id IN ('.$branch_ids.')');
			$this->db->update('path_stock_item');
    	}

		
    }

    public function trash($id="")
    {

    	if(!empty($id) && $id>0)
    	{  
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d h:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_stock_issue_allotment');
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
			$this->db->update('hms_stock_issue_allotment');
    	} 
    }
 

}
?>