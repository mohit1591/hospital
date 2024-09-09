<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_perticular_model extends CI_Model {

	var $table = 'hms_ipd_perticular';
	var $column = array('hms_ipd_perticular.id','hms_ipd_perticular.particular_code','hms_ipd_perticular.particular','hms_ipd_perticular.charge','hms_ipd_perticular.panel_charge', 'hms_ipd_perticular.status','hms_ipd_perticular.created_date');  
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
			$this->db->select("hms_ipd_perticular.*"); 
			$this->db->from($this->table); 
			$this->db->where('hms_ipd_perticular.is_deleted','0');
       		$this->db->where('hms_ipd_perticular.type','0');
            $this->db->where('hms_ipd_perticular.branch_id',$users_data['parent_id']);
	
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
	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_ipd_perticular.*"); 
		$this->db->from($this->table); 
		$this->db->where('hms_ipd_perticular.is_deleted','0');
		$this->db->where('hms_ipd_perticular.type','0');
		$this->db->where('hms_ipd_perticular.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->result();
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
    
    public function simulation_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('simulation','ASC'); 
    	$query = $this->db->get('hms_ipd_perticular');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		$this->db->select('hms_ipd_perticular.*');
		$this->db->from('hms_ipd_perticular'); 
		$this->db->where('hms_ipd_perticular.id',$id);
		$this->db->where('hms_ipd_perticular.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_perticular.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}

        public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		
		
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'particular'=>$post['perticular'],
					'particular_code'=>$post['particular_code'],
					'charge'=>$post['charge'],
					
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    

				$this->db->where(array('branch_id'=>$user_data['parent_id'],'type'=>0,'particular_id'=>$post['data_id'],'panel_company_id'=>0));
				$this->db->delete('hms_ipd_particular_charge');
			 	$data_ch_new = array( 
					'particular_id'=>$post['data_id'],
					'branch_id'=>$user_data['parent_id'],
					'charge'=>$post['charge'],
					'type'=>0,
					'panel_company_id'=>0
					);
			 	$this->db->insert('hms_ipd_particular_charge',$data_ch_new);
			 

			
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_perticular',$data);  
		}
		else
		{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ipd_perticular',$data);  
			$particular_id =$this->db->insert_id();  

			$data_pert_new = array( 
					'particular_id'=>$particular_id,
					'branch_id'=>$user_data['parent_id'],
					'type'=>0,
					'panel_company_id'=>0,
					'charge'=>$post['charge'],
					);
			$this->db->insert('hms_ipd_particular_charge',$data_pert_new);
            
		} 	
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

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_ipd_perticular');
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
			$this->db->update('hms_ipd_perticular');
			//echo $this->db->last_query();die;
    	} 
    }

    function get_simulation_name($simulation_id)
    {
        $this->db->select('simulation'); 
        $this->db->where('id',$simulation_id);                   
        $query = $this->db->get('hms_ipd_perticular');
        $test_list = $query->result(); 
            foreach($test_list as $simulations)
            {
               $simulation = $simulations->simulation;
            } 
        
        return $simulation; 
    }
    public function get_particulars()
    {
    	$post = $this->input->post();
    	$users_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$users_data['parent_id']);
    	$this->db->where('is_deleted','0');
    	$this->db->where('status','1');
    	if(isset($post) && !empty($post['particular_id']))
    	{
    		$this->db->where('id',$post['particular_id']);
    	}
    	$this->db->where('type',0);
    	$query = $this->db->get('hms_ipd_perticular');
    	$result = $query->result();
    	//echo $this->db->last_query(); exit;
    	if(empty($result))
    	{
    		$result = array();
    	}
    	return $result;
    }
    
    public function save_all_ipdperticular($ipd_all_data = array())
	{
		
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($ipd_all_data))
        {
            foreach($ipd_all_data as $ipd_perticular_data)
            {
            	//print_r($doctor_data);
            	if(!empty($ipd_perticular_data['particular']))
            	{
				$ipd_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'particular'=>$ipd_perticular_data['particular'],
	                    'charge'=>$ipd_perticular_data['charge'],
	                    'particular_code'=>$ipd_perticular_data['particular_code'],
	                    'panel_charge'=>(float) $ipd_perticular_data['panel_charge'],
	                    'type'=>0,
					    'panel_company_id'=>0,
						'status'=>1,					
	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
						'modified_by' => 0,
				        'created_by'=>$users_data['parent_id'],
				        'is_deleted' => 0,
						'deleted_by' => 0,
						'deleted_date' => 0,
				    );

					// echo "<pre>"; print_r($ipd_data_array);die;
                
				    $this->db->insert('hms_ipd_perticular',$ipd_data_array);
	               $particular_id =$this->db->insert_id(); 
                if(!empty($ipd_perticular_data['charge']))
            	{
	               $data_pert_new = array( 
					'particular_id'=>$particular_id,
					'branch_id'=>$users_data['parent_id'],
					'type'=>0,
					'panel_company_id'=>0,
					'charge'=>$ipd_perticular_data['charge'],
					);
			$this->db->insert('hms_ipd_particular_charge',$data_pert_new);
			
             if(!empty($ipd_perticular_data['panel_charge']))
            	{
			$get_all_comapny= $this->get_panel_list();
			 foreach($get_all_comapny as $panel_comapny)
			 {
				$data_new = array( 
					'particular_id'=>$particular_id,
					'branch_id'=>$users_data['parent_id'],
					'type'=>1,
					'panel_company_id'=>$panel_comapny->id,
					'charge'=>$ipd_perticular_data['panel_charge'],
					);
				$this->db->insert('hms_ipd_particular_charge',$data_new);

			} }
			} 
	            }
            }   	
        }
	}
  
    // op 19/08/19 
    public function check_unique_value($branch_id, $particular, $id='')
    {
    	$this->db->select('hms_ipd_perticular.*');
		$this->db->from('hms_ipd_perticular'); 
		$this->db->where('hms_ipd_perticular.branch_id',$branch_id);
		if(!empty($id))
		$this->db->where('hms_ipd_perticular.id !=',$id);
		$this->db->where('hms_ipd_perticular.particular',$particular);
		$this->db->where('hms_ipd_perticular.is_deleted','0');
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