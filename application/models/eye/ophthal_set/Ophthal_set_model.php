<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ophthal_set_model extends CI_Model 
{

		var $table = 'hms_std_ophthal_set';
	var $column = array('hms_std_ophthal_set.id','hms_std_ophthal_set.ophthal_set_name','hms_std_ophthal_set_tests.investig_name','hms_std_ophthal_set_tests.investigation_id','hms_std_ophthal_set_tests.eye_region_test_head','hms_std_ophthal_set_tests.eye_side');  
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
			$this->db->select("hms_std_ophthal_set.branch_id,hms_std_ophthal_set.id,hms_std_ophthal_set.ophthal_set_name,hms_std_ophthal_set_tests.investig_name,hms_std_ophthal_set_tests.investigation_id,hms_std_ophthal_set_tests.eye_side"); 
			$this->db->join('hms_std_ophthal_set_tests','hms_std_ophthal_set_tests.ophthal_set_id=hms_std_ophthal_set.id');
		//	$this->db->join('hms_std_ophthal_set_tests','hms_std_ophthal_set.ophthal_set_id=hms_std_ophthal_set.id');
			$this->db->from($this->table);
            $this->db->where('hms_std_ophthal_set.branch_id',$users_data['parent_id']);
	
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
		$this->db->group_by('id','DESC');
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
		//$user_data = $this->session->userdata('auth_users');
		$this->db->from($this->table);
		//$this->db->where('branch_id',$user_data['parent_id']);
		return $this->db->count_all_results();
	}

	public function save()
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//print_r($post);die;
		$test_id=$post['test_id'];
		$investigation_id=$post['investig_id'];
		$investigation_name=$post['investig_name'];

		$eye_side=$post['eye_side'];
		

			$set_data = array(
					  'branch_id'=>$user_data['parent_id'],
					  'ophthal_set_name'=>$post['ophthal_set_name'],
                      'created_date'=>date('Y-m-d')
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $ophthal_set_id=$post['data_id'];
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_std_ophthal_set',$set_data);  

			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->where('ophthal_set_id',$post['data_id']);
			$this->db->delete('hms_std_ophthal_set_tests');  
		}
		else{   
			
			
			$this->db->insert('hms_std_ophthal_set',$set_data); 
			$ophthal_set_id=$this->db->insert_id();           
		} 

		//echo $this->db->last_query();die;
		$i=0;
	//print_r($investigation_id);die;
		foreach($investigation_id as $invest_id)
		{
					$data = array(
					  'branch_id'=>$user_data['parent_id'],
					   'ophthal_set_id'=>$ophthal_set_id,
					  'ophthal_set_name'=>$post['ophthal_set_name'],
                      'eye_region_test_head'=>$post['eye_region_test_head'],
                      'investigation_id'=>$invest_id,
                      'investig_name'=>$investigation_name[$i],
                      'eye_side'=>$eye_side[$i],
                      'created_date'=>date('Y-m-d')
		         );
			
			$this->db->insert('hms_std_ophthal_set_tests',$data);            
			$i++;	
		}

	}

	
	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		//$this->db->select('hms_std_ophthal_set.*');
		$this->db->select("hms_std_ophthal_set.branch_id,hms_std_ophthal_set.id,hms_std_ophthal_set.ophthal_set_name,hms_std_ophthal_set_tests.investig_name,hms_std_ophthal_set_tests.investigation_id,hms_std_ophthal_set_tests.eye_side,hms_std_ophthal_set_tests.eye_region_test_head"); 
			$this->db->join('hms_std_ophthal_set_tests','hms_std_ophthal_set_tests.ophthal_set_id=hms_std_ophthal_set.id');
	
		$this->db->from('hms_std_ophthal_set'); 
		$this->db->where('hms_std_ophthal_set.id',$id);
		$this->db->where('hms_std_ophthal_set.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->row_array();
	}


	public function get_investigationby_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		//$this->db->select('hms_std_ophthal_set.*');
		$this->db->select("hms_std_ophthal_set.branch_id,hms_std_ophthal_set.id,hms_std_ophthal_set.ophthal_set_name,hms_std_ophthal_set_tests.investig_name,hms_std_ophthal_set_tests.investigation_id,hms_std_ophthal_set_tests.eye_side,hms_std_ophthal_set_tests.eye_region_test_head,hms_std_ophthal_set_tests.id as test_id"); 
			$this->db->join('hms_std_ophthal_set','hms_std_ophthal_set_tests.ophthal_set_id=hms_std_ophthal_set.id');
	
		$this->db->from('hms_std_ophthal_set_tests'); 
		$this->db->where('hms_std_ophthal_set_tests.ophthal_set_id',$id);
		$this->db->where('hms_std_ophthal_set.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->result_array();
	}
	public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$this->db->delete('hms_std_ophthal_set',array('id'=>$id));
			$this->db->delete('hms_std_ophthal_set_tests',array('ophthal_set_id'=>$id));
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
			$where='id IN ('.$branch_ids.')';
			$this->db->delete('hms_std_ophthal_set',$where);
    	} 
    }

    	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_std_ophthal_set.*"); 
		$this->db->from($this->table); 
		$this->db->where('hms_std_ophthal_set.is_deleted','0');
		$this->db->where('hms_std_ophthal_set.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->result();
	}

	public function save_type()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'type'=>$post['type'],
					'status'=>$post['status'],
					'created_date'=>date('Y-m-d')
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ambulance_vehicle_type',$data);  
		}
		else{    
			
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ambulance_vehicle_type',$data);               
		} 	
	}


	/*Search investigation  */
			public function investigation_list($keyword)
		{
		    $users_data = $this->session->userdata('auth_users');
		    $this->db->select('id,test_name,test_code,test_head_id');  
		    $this->db->where('dept_id','30'); 
		    $this->db->like('test_name',$keyword); 
		    $this->db->where('branch_id',$users_data['parent_id']); 
		    $query = $this->db->get('path_test');
		    $result = $query->result(); 
		    return $result; 
		}

	/* Search Investigation*/

	public function check_unique_value($branch_id, $set_name, $id='')
   {
		$this->db->select('hms_std_ophthal_set.*');
		$this->db->from('hms_std_ophthal_set'); 
		$this->db->where('hms_std_ophthal_set.branch_id',$branch_id);
		$this->db->where('hms_std_ophthal_set.ophthal_set_name',$set_name);
		if(!empty($id))
		$this->db->where('hms_std_ophthal_set.id !=',$id);
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