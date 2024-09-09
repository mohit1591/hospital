<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sms_setting_model extends CI_Model {

	var $table = 'hms_sms_setting';
	var $column = array('hms_sms_setting.id','hms_sms_setting.sms_url','hms_sms_setting.username','hms_sms_setting.sender_id','hms_sms_setting.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
          $parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("hms_sms_setting.*"); 
		$this->db->from($this->table); 
           
            $this->db->where('hms_sms_setting.branch_id',$users_data['parent_id']);
	
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
    
    public function email_template_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('subject','ASC'); 
    	$query = $this->db->get('hms_sms_setting');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_sms_setting.*');
		$this->db->from('hms_sms_setting'); 
		$this->db->where('hms_sms_setting.id',$id);
		// $this->db->where('hms_sms_setting.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'sms_url'=>$post['url_of_sms'],
					'username' =>$post['username'],
					'password'=>$post['password'],
					'sender_id'=>$post['sender_id'],
				
					

					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		// if(!empty($user_data['parent_id']))
		// {    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('branch_id',$user_data['parent_id']);
			$this->db->update('hms_sms_setting',$data);  
			
		// }
		// else{    
		// 	$this->db->set('created_by',$user_data['id']);
		// 	$this->db->set('created_date',date('Y-m-d H:i:s'));
		// 	$this->db->insert('hms_sms_setting',$data);               
		// } 	
	}

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_sms_setting');
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
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('hms_sms_setting');
			//echo $this->db->last_query();die;
    	} 
    }
    //this function made by mahesh for counting total no of active email setting. only one setting should be active a time
    public function totalcount_active_setting(){
    	$users_data = $this->session->userdata('auth_users');
        $this->db->select('count(*) as active_settings');
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->from('hms_sms_setting');
        $query = $this->db->get(); 
        if($query){
		    return $query->row_array();
		}else{
			return false;
		}
    }

}
?>