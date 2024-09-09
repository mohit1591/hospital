<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nurses_note_template_model extends CI_Model {

	var $table = 'hms_nurses_note_template';
	var $column = array('hms_nurses_note_template.id','hms_nurses_note_template.name', 'hms_nurses_note_template.status','hms_nurses_note_template.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_nurses_note_template.*"); 
		$this->db->from($this->table); 
        // $this->db->where('hms_nurses_note_template.is_deleted','0');
        $this->db->where('hms_nurses_note_template.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function nurses_note_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	// $this->db->where('is_deleted',0); 
    	$this->db->order_by('name','ASC'); 
    	$query = $this->db->get('hms_nurses_note_template');
		return $query->result_array();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_nurses_note_template.*');
		$this->db->from('hms_nurses_note_template'); 
		$this->db->where('hms_nurses_note_template.id',$id);
		// $this->db->where('hms_nurses_note_template.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'], 
					'content'=>$post['content'], 
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_nurses_note_template',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_nurses_note_template',$data);               
		} 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			
			$this->db->where('id',$id);
			$this->db->delete('hms_nurses_note_template');
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
			$this->db->delete('hms_nurses_note_template');
			//echo $this->db->last_query();die;
    	} 
    }
    
      public function check_unique_value($branch_id, $name, $id='')
    {
    	$this->db->select('hms_nurses_note_template.*');
		$this->db->from('hms_nurses_note_template'); 
		$this->db->where('hms_nurses_note_template.branch_id',$branch_id);
		$this->db->where('hms_nurses_note_template.name',$name);
		if(!empty($id))
		$this->db->where('hms_nurses_note_template.id !=',$id);
		
		// $this->db->where('hms_nurses_note_template.is_deleted','0');
		$query = $this->db->get(); 
		$result=$query->row_array();
		if(!empty($result))
		{
			return 1;
		}
		else{
			return 0;
		}
    }

}
?>