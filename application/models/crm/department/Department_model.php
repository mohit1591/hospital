<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Department_model extends CI_Model {

	var $table = 'crm_department';
	var $column = array('crm_department.id','crm_department.department','crm_department.created_date','crm_department.modified_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		//parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select("crm_department.*");   
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
    
    public function crm_department_list($type="")
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');  
    	$this->db->order_by('crm_department.id','DESC'); 
    	$query = $this->db->get('crm_department');
		return $query->result();
    }
 

	public function get_by_id($id)
	{
		$this->db->select('crm_department.*');
		$this->db->from('crm_department'); 
		$this->db->where('crm_department.id',$id); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array(  
					'department'=>$post['department'], 
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('crm_department',$data);  
		}
		else{     
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('crm_department',$data);               
		} 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users'); 
			$this->db->where('id',$id);
			$this->db->delete('crm_department');
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
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->delete('crm_department');
			//echo $this->db->last_query();die;
    	} 
    }


    public function check_department($department="",$id="")
    {
        $this->db->select('*');  
        if(!empty($department))
        {
        	$this->db->where('department',$department);
        }  
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        }  
        $query = $this->db->get('crm_department');
        $result = $query->result(); 
        return $result; 
    }
  

}
?>