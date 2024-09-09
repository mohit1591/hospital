<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_room_charge_type_model extends CI_Model {

	var $table = 'hms_ipd_room_charge_type';
	var $column = array('hms_ipd_room_charge_type.id','hms_ipd_room_charge_type.charge_type', 'hms_ipd_room_charge_type.status','hms_ipd_room_charge_type.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_ipd_room_charge_type.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_ipd_room_charge_type.is_deleted','0');
       
            $this->db->where('hms_ipd_room_charge_type.branch_id',$users_data['parent_id']);
	
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
		// echo $this->db->last_query();die;
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
    
    public function ipd_panel_company_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('panel_company','ASC'); 
    	$query = $this->db->get('hms_ipd_room_charge_type');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_ipd_room_charge_type.*');
		$this->db->from('hms_ipd_room_charge_type'); 
		$this->db->where('hms_ipd_room_charge_type.id',$id);
		$this->db->where('hms_ipd_room_charge_type.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'charge_type'=>$post['charge_type'],
					'status'=>$post['status'],
					
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_room_charge_type',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ipd_room_charge_type',$data);               
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
			$this->db->update('hms_ipd_room_charge_type');
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
			$this->db->update('hms_ipd_room_charge_type');
			//echo $this->db->last_query();die;
    	} 
    }

    function get_simulation_name($panel_type_id)
    {
        $this->db->select('panel_company'); 
        $this->db->where('id',$panel_type_id);                   
        $query = $this->db->get('hms_ipd_room_charge_type');
        $test_list = $query->result(); 
            foreach($test_list as $panel_company)
            {
               $panel_company = $panel_company->panel_company;
            } 
        
        return $panel_company; 
    }

    function get_room_charge_according_to_branch($branch_id){
    	if(!empty($branch_id) && $branch_id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->where('branch_id',$branch_id);
			$this->db->where('status',1);
			$this->db->where('is_deleted',0); 
			$result= $this->db->get('hms_ipd_room_charge_type')->result();
			return $result;
    	} 
    }

      function get_room_charge_accordint_to_id($branch_id="",$charge_id="",$room_type="",$room_id=""){
    	 
			$user_data = $this->session->userdata('auth_users');
			if(!empty($branch_id) && $branch_id>0){
				$this->db->where('branch_id',$branch_id);
			}
			if(!empty($charge_id) && $charge_id>0){
				$this->db->where('room_charge_type_id',$charge_id);
			
			}
			if(!empty($room_id) && $room_id>0){
				$this->db->where('room_id',$room_id);
			}
			if(!empty($room_type) && $room_type>0){
				$this->db->where('room_type_id',$room_type);
			}
			$result= $this->db->get('hms_ipd_room_charge')->result(); 
			//echo $this->db->last_query();
			//print_r($result);
			return $result;
    }

    function get_panel_room_charge_accordint_to_id($branch_id="",$charge_id="",$room_type="",$room_id="",$panel_id='')
    { 
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('(CASE WHEN hms_ipd_room_charge.room_charge>0 THEN hms_ipd_room_charge.room_charge ELSE 0 END) as room_charge');
		$this->db->join('hms_ipd_room_charge', 'hms_ipd_room_charge.room_type_id = hms_ipd_room_category.id AND hms_ipd_room_charge.panel_company_id = "'.$panel_id.'" AND hms_ipd_room_charge.room_charge_type_id = "'.$charge_id.'" AND hms_ipd_room_category.id = "'.$room_type.'"','right');
		$this->db->where('hms_ipd_room_category.branch_id',$branch_id);
		//$this->db->where('hms_ipd_room_category.branch_id',$branch_id);
		  		
		$this->db->group_by('hms_ipd_room_category.id');
		$result= $this->db->get('hms_ipd_room_category')->result_array(); 
		//echo $this->db->last_query();die; 
		return $result;
    }
  

}
?>