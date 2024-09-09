<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_panel_package_charge_model extends CI_Model {

	var $table = 'hms_ipd_packages';
	var $column = array('hms_ipd_packages.id','hms_ipd_packages.name','hms_ipd_packages.panel_company', 'hms_ipd_packages.status','hms_ipd_packages.created_date');  
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
		$this->db->select("hms_ipd_packages.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_ipd_packages.is_deleted','0');
       
            $this->db->where('hms_ipd_packages.branch_id',$users_data['parent_id']);
	
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

	public function get_package_list($panel_id="",$package_id='',$type='')
	{	
		//set value of type for normal 1 and for panel 2
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ipd_packages.*,hms_ipd_package_charge.charge as package_charge,hms_ipd_package_charge.id as charge_id');
		$this->db->from('hms_ipd_packages');
		$this->db->join('hms_ipd_package_charge','hms_ipd_package_charge.package_id=hms_ipd_packages.id','left'); 
		if(!empty($panel_id))
		{
			$this->db->where('hms_ipd_package_charge.panel_company_id',$panel_id);
		}
		if(!empty($package_id))
		{
			$this->db->where('hms_ipd_package_charge.package_id',$package_id);
		}
		if(!empty($type) && $type==1)
		{
			$this->db->where('hms_ipd_package_charge.type',0);
		}
		if(!empty($type) && $type==2)
		{
			$this->db->where('hms_ipd_package_charge.type',1);
		} 
		$this->db->where('hms_ipd_packages.is_deleted','0');
		$this->db->where('hms_ipd_packages.status',1);
		$this->db->where('hms_ipd_packages.branch_id',$user_data['parent_id']); 
		$this->db->order_by('hms_ipd_packages.name','ASC');
		$query = $this->db->get(); 
		$result = $query->result_array();
		//echo $this->db->last_query(); exit;
		return $result;
	}

	
	
	public function save_package_panel_charge($panel_id="",$package_id='',$vals="0",$charge_id='')
	{
		$user_data = $this->session->userdata('auth_users'); 
		if(!empty($panel_id) && !empty($package_id) && !empty($charge_id))
		{
		   	$package_data = array('charge'=>$vals);
			$this->db->where('id',$charge_id);
			$this->db->where('package_id',$package_id);
			$this->db->where('panel_company_id',$panel_id);
			$this->db->update('hms_ipd_package_charge',$package_data);
		    
		}
        return 1;
	}


	public function increase_package_panel_charge($panel_id="",$type="",$vals="0")
	{
		
		$user_data = $this->session->userdata('auth_users'); 
		if(!empty($panel_id))
        {
	        $this->db->select('hms_ipd_package_charge.*');
			$this->db->from('hms_ipd_package_charge'); 
			$this->db->where('hms_ipd_package_charge.panel_company_id',$panel_id);
			$query = $this->db->get();
			$result = $query->result();
		}
		
		if(!empty($result))
		{
			foreach($result as $package_charges)
			{
			if($type==1)
			{
				 $panelcharge =$package_charges->charge+$vals;
			}
			elseif($type==2)
			{
				$panelcharge =$package_charges->charge + ($vals/100)*$package_charges->charge;	
			}
			else
			{
				$panelcharge = $package_charges->charge;
			}	
					$ipd_room_charge = array('charge'=>$panelcharge);
					$this->db->where('id',$package_charges->id);
					$this->db->where('panel_company_id',$panel_id);
					$this->db->update('hms_ipd_package_charge',$ipd_room_charge);
					//echo $this->db->last_query(); exit;
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
                    $this->db->where('package_id',$test_data['test_id']);
                    $this->db->where('type',1);
                    $this->db->where('panel_company_id',$panel);
                    $this->db->delete('hms_ipd_package_charge');
                    
                       $data = array(
                            'branch_id'=>$branch_id,
                            'package_id'=>$test_data['test_id'],
                            'charge'=>$test_data['path_price'],
                            'type'=>1,
                            'panel_type_id'=>0,
                            'panel_company_id'=>$panel
                        );
                    $this->db->insert('hms_ipd_package_charge',$data);
                    //echo $this->db->last_query();die;
                } 
            }
        }
    }
    
     public function save_panel_rate()
    {
        $data = array();
        $post = $this->input->post();
        //
        $panel_id = $post['panel_id'];
        $package_id = $post['package_id'];
        $charge = $post['charge'];
        

        $users_data = $this->session->userdata('auth_users');
        if(isset($post) && !empty($post))
        {
            
            
            if(!empty($package_id))
            {
              
                $result = $this->get_path_panel_charge($package_id,$panel_id);
               //print '<pre>' ;print_r($result);die;  
                if(!empty($result))
                {
                    $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'package_id'=>$package_id,
                        'charge'=>$charge,
                        'type'=>1,
                        'panel_company_id'=>$panel_id
                    );

                    $this->db->where('branch_id',$users_data['parent_id']);
                    $this->db->where('(package_id='.$result[0]->package_id.' and panel_company_id='.$panel_id.' and id='.$result[0]->id.')');
                    $this->db->update('hms_ipd_package_charge',$data);
                    //echo $this->db->last_query();die;
                }
                else
                {
                    $data = array(
                        'branch_id'=>$users_data['parent_id'],
                        'package_id'=>$package_id,
                        'panel_company_id'=>$panel_id,
                        'charge'=>$charge,
                        'type'=>1,
                        
                    );
                    
                    $this->db->insert('hms_ipd_package_charge',$data);
                    //echo $this->db->last_query();die;
                }
            }
         
        }
    }
    
    function get_path_panel_charge($package_id,$panel_id="")
    {
        $users_data = $this->session->userdata('auth_users');
       $this->db->select('*');
       $this->db->where('panel_company_id',$panel_id);
       $this->db->where('package_id',$package_id);
       $this->db->where('branch_id',$users_data['parent_id']);
       $res= $this->db->get('hms_ipd_package_charge')->result();
       //echo $this->db->last_query();die;
       return $res;
    }
    
   
   

}
?>