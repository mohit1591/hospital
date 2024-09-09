<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company_setting_model extends CI_Model {

	var $table = 'hms_branch';
	var $column = array('hms_branch.id','hms_branch.branch_name','hms_branch.email','hms_branch.address','hms_branch.country_id','hms_branch.city_id','hms_branch.state_id','hms_branch.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        	$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_branch.*, hms_cities.city, hms_state.state,hms_countries.country"); 
		$this->db->from($this->table); 
        $this->db->join('hms_cities','hms_cities.id=hms_branch.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_branch.state_id','left');
        $this->db->join('hms_countries','hms_countries.id=hms_branch.country_id','left');
        $this->db->where('hms_branch.is_deleted','0');
        $this->db->where('hms_branch.id',$users_data['parent_id']); 
      
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
    	$query = $this->db->get('hms_branch');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_branch.*');
		$this->db->from('hms_branch'); 
		$this->db->where('hms_branch.id',$id);
		// $this->db->where('hms_branch.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($filename="",$filename1="")
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					//'parent_id'=>$user_data['parent_id'],
					'branch_name'=>$post['company_name'],
					'email'=>$post['email'],
					'address' =>$post['address'],
					'country_id'=>$post['country_id'],
					'city_id'=>$post['city_id'],
					'state_id'=>$post['state_id'],
					'punch_line'=>$post['punch_line'],
					'theme'=>$post['theme'],
					'logo_url'=>$post['logo_url'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		// if(!empty($user_data['parent_id']))
		// {    
		    if(!empty($filename))
			{
				$this->db->set('photo',$filename);
			}
			if(!empty($filename1))
			{
				$this->db->set('photo_banner',$filename1);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$user_data['parent_id']);
			$this->db->update('hms_branch',$data);  
			
		// }
		// else{    
		// 	$this->db->set('created_by',$user_data['id']);
		// 	$this->db->set('created_date',date('Y-m-d H:i:s'));
		// 	$this->db->insert('hms_branch',$data);               
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
			$this->db->update('hms_branch');
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
			$this->db->update('hms_branch');
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
        $this->db->from('hms_branch');
        $query = $this->db->get(); 
        if($query){
		    return $query->row_array();
		}else{
			return false;
		}
    }
     public function country_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_countries');
        $result = $query->result(); 
        return $result; 
    } 
     public function state_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_state');
        $result = $query->result(); 
        return $result; 
    } 
     public function city_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_cities');
        $result = $query->result(); 
        return $result; 
    } 

}
?>