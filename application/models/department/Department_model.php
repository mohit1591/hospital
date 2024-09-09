<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends CI_Model {

	var $table = 'hms_department';
	var $column = array('hms_department.id','hms_department.department', 'hms_department.result_heading','hms_department.created_date','hms_department.modified_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_department_to_result_heading_status.status as result_heading, hms_department.id,hms_department.branch_id,hms_department.module,hms_department.department,hms_department.ip_address,hms_department.is_deleted,hms_department.deleted_by,hms_department.deleted_date,hms_department.created_by,hms_department.modified_by,hms_department.modified_date,hms_department.created_date,(select status from hms_department_to_department_status where branch_id = '".$users_data['parent_id']."' AND department_id = hms_department.id) as dept_status");
		//(CASE WHEN hms_department.branch_id=0 THEN hms_department.status ELSE hms_department_to_department_status.status END) as status 
		$this->db->from('hms_department'); 
		//$this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id AND hms_department_to_department_status.department_id = "'.$users_data['parent_id'].'"','left');
		$this->db->join('hms_department_to_result_heading_status','hms_department_to_result_heading_status.department_id = hms_department.id AND hms_department_to_result_heading_status.branch_id = "'.$users_data['parent_id'].'"','left');
        $this->db->where('hms_department.is_deleted','0');
        $this->db->where('hms_department.module','5');
        $this->db->where('hms_department.branch_id IN('.$users_data['parent_id'].',0)');
        //$this->db->where('(hms_department.branch_id = "'.$users_data['parent_id'].'" OR hms_department.branch_id =0)');

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
    
    public function department_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select("hms_department.id,hms_department.branch_id,hms_department.module,hms_department.department,hms_department.ip_address,hms_department.is_deleted,hms_department.deleted_by,hms_department.deleted_date,hms_department.created_by,hms_department.modified_by,hms_department.modified_date,hms_department.created_date,hms_department_to_department_status.status as status");
		//(CASE WHEN hms_department.branch_id=0 THEN hms_department.status ELSE hms_department_to_department_status.status END) as status 
		$this->db->from('hms_department'); 
		$this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id','left');
        $this->db->where('hms_department.is_deleted','0');
        $this->db->where('hms_department.module','5');
        $this->db->where('(hms_department.branch_id = "'.$users_data['parent_id'].'" OR hms_department.branch_id =0)');

    	//$this->db->select('*');
    	//$this->db->where('module','5');
        //$this->db->where('branch_id = "'.$users_data['parent_id'].'" OR branch_id =0');
    	//$this->db->where('status',1);
    	$this->db->where('hms_department_to_department_status.status','1');    
    	$this->db->where('hms_department.is_deleted',0); 
    	$this->db->order_by('hms_department.department','ASC'); 
    	$query = $this->db->get();


    	//echo $this->db->last_query();
		return $query->result();
    }

	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_department.*');
		$this->db->from('hms_department'); 
		$this->db->where('hms_department.id',$id);
		$this->db->where('hms_department.is_deleted','0');
		$this->db->where('hms_department.module','5');
        $this->db->where('hms_department.branch_id = "'.$user_data['parent_id'].'"');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'department'=>$post['department'],
					'module'=>5,
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_department',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_department',$data);               
		} 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->where('module','5');
        	$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_department');
			//echo $this->db->last_query();die;
    	} 
    }

    public function deleteall($ids=array())
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
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->where('module','5');
        	$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_department');
			//echo $this->db->last_query();die;
    	} 
    }

     public function save_department_status($department_id,$status='')
     {
       $user_data = $this->session->userdata('auth_users');
       if(!empty($department_id))
        {
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'department_id'=>$department_id));
			$this->db->delete('hms_department_to_department_status');

        	if(!empty($status))
        	{
        		$status = $status;
        	}
        	else
        	{
        		$status=0;
        	}
        	$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'department_id'=>$department_id,
					'status'=>$status,
				);

        	$this->db->insert('hms_department_to_department_status',$data);
        	return true;
        	//echo $this->db->last_query();
        }
    }
    
    
    
    public function save_department_result_heading_status($department_id,$status='')
     {
       $user_data = $this->session->userdata('auth_users');
       if(!empty($department_id))
        {  
            	$this->db->where(array('branch_id'=>$user_data['parent_id'],'department_id'=>$department_id));
			$this->db->delete('hms_department_to_result_heading_status');

        	if(!empty($status))
        	{
        		$status = $status;
        	}
        	else
        	{
        		$status=0;
        	}
        	$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'department_id'=>$department_id,
					'status'=>$status,
				);

        	$this->db->insert('hms_department_to_result_heading_status',$data);
        	return true;
        	//echo $this->db->last_query();die;
        	return true;
        	//echo $this->db->last_query();
        }
    }

}
?>