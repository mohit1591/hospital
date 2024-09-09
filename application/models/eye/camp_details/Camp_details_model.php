<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Camp_details_model extends CI_Model {

	var $table = 'hms_eye_camp_details';
	var $column = array('hms_eye_camp_details.id','hms_eye_camp_details.camp_name', 'hms_eye_camp_details.status','hms_eye_camp_details.camp_address','hms_eye_camp_details.camp_involved','hms_eye_camp_details.camp_date','hms_eye_camp_details.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_eye_camp_details.*"); 
		$this->db->from($this->table); 
		// $this->db->join('hms_employees','hms_employees.id=hms_eye_camp_details.camp_involved','left');
        $this->db->where('hms_eye_camp_details.is_deleted','0');
        $this->db->where('hms_eye_camp_details.branch_id = "'.$users_data['parent_id'].'"');
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

	  public function get_users_name($id='')
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->from('hms_employees'); 

    	$this->db->where('id',$id);
        $this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	// $this->db->order_by('preferred_reminder_service','ASC'); 
    	$query = $this->db->get();
    	//echo $this->db->last_query();die;
		return $query->row_array();
		
    }
        public function get_inuser_name($id='')
    {
    	$emp_name=array();
    	$new_emp_name='';
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->from('hms_employees'); 

    	$this->db->where('id IN('.$id.')');
        $this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$query = $this->db->get();

    	//echo $this->db->last_query();die;
		$data= $query->result_array();
		if(!empty($data))
		{
			foreach($data as $data_arr)
			{
				$emp_name[]= $data_arr['name'];
			}
			$new_emp_name= implode(',',$emp_name);
		}
		return $new_emp_name;
		
    }
    
    public function preferred_reminder_service_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('preferred_reminder_service','ASC'); 
    	$query = $this->db->get('hms_blood_deferral_reason');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_eye_camp_details.*');
		$this->db->from('hms_eye_camp_details'); 
		$this->db->where('hms_eye_camp_details.id',$id);
		$this->db->where('hms_eye_camp_details.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//print_r($post);
		if(isset($post['camp_involved']))
		{
		$camp_involved= $post['camp_involved'];
		if(!empty($camp_involved))
		{
         $involved=  implode(",", $camp_involved);
		}
		else
		{
			$involved='';
		}
	}
	else
	{
		$involved='';
	}
		
       if(!empty($post['start_date']))
		{
			$start_date=date('Y-m-d',strtotime($post['start_date']));
		}
		else
		{
			$start_date='';
		}
		if(!empty($post['end_date']))
		{
			$end_date=date('Y-m-d',strtotime($post['end_date']));
		}
		else
		{
			$end_date='';
		}


		if(!empty($post['camp_date']))
		{
			$camp_date=date('Y-m-d',strtotime($post['camp_date']));
		}
		else
		{
			$camp_date='';
		}

		//$involved = array();
        
            
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'camp_name'=>$post['camp_name'],
					'camp_address'=>$post['camp_address'],
					'camp_involved'=>$involved,
					'camp_date'=>$camp_date,
					'start_date'=>$start_date,
					'end_date'=>$end_date,
					'country_id'=>$post['country_id'],
					'state_id'=>$post['state_id'],
					'citys_id'=>$post['citys_id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_eye_camp_details',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_eye_camp_details',$data); 
			//echo $this->db->last_query();die;              
		} 	
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
			$this->db->update('hms_eye_camp_details`');
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
			$this->db->update('hms_eye_camp_details');
			//echo $this->db->last_query();die;
    	} 
    }
    
    public function camp_dropdown()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('camp_name','ASC'); 
    	$query = $this->db->get('hms_eye_camp_details');
		return $query->result();
    }

}
?>