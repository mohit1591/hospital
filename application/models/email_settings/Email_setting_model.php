<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_setting_model extends CI_Model {

	var $table = 'hms_email_setting';
	var $column = array('hms_email_setting.id','hms_email_setting.network_email_address','hms_email_setting.email_password','hms_email_setting.port','hms_email_setting.smtp_ssl','hms_email_setting.status','hms_email_setting.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_email_setting.*"); 
		$this->db->from($this->table); 
       
            $this->db->where('hms_email_setting.branch_id',$users_data['parent_id']);
		
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

	function get_datatables($branch_id='')
	{
		$this->_get_datatables_query($branch_id);
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
    	$query = $this->db->get('hms_email_setting');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_email_setting.*');
		$this->db->from('hms_email_setting'); 
		$this->db->where('hms_email_setting.id',$id);
		// $this->db->where('hms_email_setting.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'smtp_address'=>$post['smtp_address'],
					'network_email_address' =>$post['network_email_id'],
					'email_password'=>$post['network_email_pass'],
					'port'=>$post['port'],
					'smtp_ssl'=>$post['ssl_status'],
					'cc_email'=>$post['cc_email'],
					

					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		// if(!empty($user_data['parent_id']))
		// {    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('branch_id',$user_data['parent_id']);
			$this->db->update('hms_email_setting',$data);  
			
		// }
		// else{    
		// 	$this->db->set('created_by',$user_data['id']);
		// 	$this->db->set('created_date',date('Y-m-d H:i:s'));
		// 	$this->db->insert('hms_email_setting',$data);               
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
			$this->db->update('hms_email_setting');
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
			$this->db->update('hms_email_setting');
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
        $this->db->from('hms_email_setting');
        $query = $this->db->get(); 
        if($query){
		    return $query->row_array();
		}else{
			return false;
		}
    }

}
?>