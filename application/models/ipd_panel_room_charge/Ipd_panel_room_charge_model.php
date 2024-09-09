<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_panel_room_charge_model extends CI_Model {

	var $table = 'hms_ipd_panel_company';
	var $column = array('hms_ipd_panel_company.id','hms_ipd_panel_company.panel_company', 'hms_ipd_panel_company.status','hms_ipd_panel_company.created_date');  
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
		$this->db->select("hms_ipd_panel_company.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_ipd_panel_company.is_deleted','0');
       
            $this->db->where('hms_ipd_panel_company.branch_id',$users_data['parent_id']);
	
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
    
    public function get_panel_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('insurance_company','ASC'); 
    	$query = $this->db->get('hms_insurance_company');
		return $query->result();
    }

	public function get_room_list($id="",$room_type_id='')
	{	
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ipd_room_category.*');
		$this->db->from('hms_ipd_room_category'); 
		$this->db->where('hms_ipd_room_category.status',1); 
    	        $this->db->where('hms_ipd_room_category.is_deleted!=2'); 
		$this->db->where('hms_ipd_room_category.branch_id',$user_data['parent_id']);
		$query = $this->db->get();
		$result = $query->result();
		//echo $this->db->last_query(); exit; 
		return $result;
	}

	public function get_all_room_list($room_type_id='')
	{	
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ipd_rooms.*');
		$this->db->from('hms_ipd_rooms'); 
		$this->db->where('hms_ipd_rooms.room_type_id',$room_type_id);
		$this->db->where('hms_ipd_rooms.branch_id',$user_data['parent_id']);
		$query = $this->db->get();
		$result = $query->result(); 
		return $result;
	}
	
	public function save_panel_room_charge($panel_id="",$room_type="",$category_id='',$vals="0")
	{
		$user_data = $this->session->userdata('auth_users'); 

        if(!empty($panel_id) && !empty($room_type))
        {
	        $this->db->where('types','1');
	        $this->db->where('panel_company_id',$panel_id);
			$this->db->where('room_type_id',$category_id);
			$this->db->where('room_charge_type_id',$room_type);
			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->delete('hms_ipd_room_charge');
			
		}
		
		$room_list = $this->get_all_room_list($category_id);
		foreach($room_list as $room_data)
		{
		$ipd_room_charge = array(
		    	'branch_id'=>$user_data['parent_id'],
		    	'types'=>1,
		    	'room_type_id'=>$room_data->room_type_id,
		    	'room_id'=>$room_data->id,
		    	'room_no'=>$room_data->room_no,
		    	'charge_code'=>'',
		    	'room_charge_type_id'=>$room_type,
		    	'room_charge'=>$vals,
		    	'panel_type_id'=>0,
		    	'panel_company_id'=>$panel_id

		    );
		$this->db->insert('hms_ipd_room_charge',$ipd_room_charge);
		$charge_id = $this->db->insert_id(); 

		}	 
        	return 1;
		}


	public function increase_panel_price($panel_id="",$type="",$vals="0")
	{
		
		$user_data = $this->session->userdata('auth_users'); 
		if(!empty($panel_id))
        {
	        $this->db->select('hms_ipd_room_charge.*');
			$this->db->from('hms_ipd_room_charge'); 
			$this->db->where('hms_ipd_room_charge.branch_id',$user_data['parent_id']);
			$this->db->where('hms_ipd_room_charge.panel_company_id',$panel_id);
			$query = $this->db->get();
			$result = $query->result();
		}
		
		if(!empty($result))
		{
			foreach($result as $room_charges)
			{

			if($type==1)
			{
				 $room_charge =$room_charges->room_charge+$vals;
			}
			elseif($type==2)
			{
				$room_charge =$room_charges->room_charge + ($vals/100)*$room_charges->room_charge;	
				
			}
			else
			{
				$room_charge = $room_charges->room_charge;
			}	
			$ipd_room_charge = array(
			        	'branch_id'=>$user_data['parent_id'],
			        	'types'=>$room_charges->types,
			        	'room_type_id'=>$room_charges->room_type_id,
			        	'room_id'=>$room_charges->room_id,
			        	'charge_code'=>$room_charges->charge_code,
			        	'room_no'=>$room_charges->room_no,
			        	'room_charge_type_id'=>$room_charges->room_charge_type_id,
			        	'panel_type_id'=>$room_charges->panel_type_id,
			        	'panel_company_id'=>$room_charges->panel_company_id,
			        	'room_charge'=>$room_charge

			        );
				$this->db->where('id',$room_charges->id);
				$this->db->update('hms_ipd_room_charge',$ipd_room_charge);
				
			}
		}
			 
        	return 1;
		}
		
		
		public function save_panel_all_rate()
    {
        
        $data = array();
        $post = $this->input->post();
        //print '<pre>' ;print_r($post);die; 
        $users_data = $this->session->userdata('auth_users');
       $panel =$post['panel'];
        if(isset($post))
        {
           $branch_id = $users_data['parent_id'];
           
            foreach($post['test_id'] as $test_data)
            {  
                if(isset($test_data['test_id']) && $test_data['test_id']>0)
                {
                    $this->db->where('branch_id',$branch_id);
                    $this->db->where('particular_id',$test_data['test_id']);
                    $this->db->where('type',1);
                    $this->db->where('panel_company_id',$panel);
                    $this->db->delete('hms_ipd_panel_company');
                    
                       $data = array(
                            'branch_id'=>$branch_id,
                            'particular_id'=>$test_data['test_id'],
                            'charge'=>$test_data['path_price'],
                            'type'=>1,
                            'panel_type_id'=>0,
                            'panel_company_id'=>$panel
                        );
                    $this->db->insert('hms_ipd_panel_company',$data);
                    //echo $this->db->last_query();die;
                } 
            }
        }
    }
   

}
?>