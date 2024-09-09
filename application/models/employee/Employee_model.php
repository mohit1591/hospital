<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

	var $table = 'hms_employees';
	var $column = array('hms_employees.reg_no','hms_employees.name', 'hms_employees.contact_no', 'hms_employees.email','hms_employees.status','hms_employees.created_date','hms_employees.modified_date');  
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
		$this->db->select("hms_employees.*, hms_cities.city, hms_state.state"); 
		$this->db->from($this->table);
        $this->db->join('hms_cities','hms_cities.id=hms_employees.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_employees.state_id','left');
        $this->db->where('hms_employees.is_deleted','0');
    	
            $this->db->where('hms_employees.branch_id',$users_data['parent_id']);
        
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

	public function get_by_id($id)
	{
		$this->db->select('hms_employees.*, hms_simulation.simulation,hms_countries.country, hms_cities.city, hms_state.state, hms_emp_type.emp_type');
		$this->db->from('hms_employees'); 
		$this->db->join('hms_emp_type','hms_emp_type.id=hms_employees.emp_type_id','left');
		$this->db->join('hms_countries','hms_countries.id=hms_employees.country_id','left');
		$this->db->join('hms_cities','hms_cities.id=hms_employees.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_employees.state_id','left');
          $this->db->join('hms_simulation','hms_simulation.id=hms_employees.simulation_id','left'); 
		$this->db->where('hms_employees.id',$id); 
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->row_array();
	}
	
	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//$reg_no = generate_unique_id(2); 
		$data = array(
					'branch_id'=>$user_data['parent_id'],
					'reg_no'=>$post['reg_no'],
					'emp_type_id'=>$post['emp_type_id'],
					'name'=>$post['name'],
					'contact_no'=>$post['contact_no'],
					'dob'=>date('Y-m-d', strtotime($post['dob'])),
                                        'anniversary'=>date('Y-m-d', strtotime($post['anniversary'])),
					'age'=>$post['age'],
					'sex'=>$post['gender'],
					'salary'=>$post['salary'],
					'merital_status'=>$post['merital_status'],
					'qualification'=>$post['qualification'],
					'email'=>$post['email'],
					'address'=>$post['address'],
					'city_id'=>$post['city_id'],
					'state_id'=>$post['state_id'],
					'country_id'=>$post['country_id'],
					'postal_code'=>$post['postal_code'], 
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR'],
					'simulation_id'=>$post['simulation_id']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            
            $this->db->set('modified_by',$user_data['id']);
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_employees',$data);  
		}
		else{   
			
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_employees',$data);            
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
			$this->db->update('hms_employees');
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
    		$emp_ids = implode(',', $id_list);
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$emp_ids.')');
			$this->db->update('hms_employees');
			//echo $this->db->last_query();die;
    	} 
    }
    

    public function employee_type_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('emp_type','ASC'); 
    	$query = $this->db->get('hms_emp_type');
		return $query->result();
    }

    

    public function type_to_employee($type_id="")
    {
    	$user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_employees.*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('emp_type_id',$type_id); 
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('hms_employees.name','ASC'); 
    	$query = $this->db->get('hms_employees');
		return $query->result();
    }
    
    public function employee_list()
    {
    	$user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_employees.*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('hms_employees.name','ASC'); 
    	$query = $this->db->get('hms_employees');
    	//echo $this->db->last_query();die;
		return $query->result();
    }



}
?>