<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Radiology_set_model extends CI_Model 
{

		var $table = 'hms_std_radiology_set';
	var $column = array('hms_std_radiology_set.id','hms_std_radiology_set.set_name','hms_std_radiology_set_tests.investig_name','hms_std_radiology_set_tests.investigation_id','hms_std_radiology_set.speciality');  
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
			$this->db->select("hms_std_radiology_set.branch_id,hms_std_radiology_set.id,hms_std_radiology_set.set_name,hms_std_radiology_set_tests.investig_name,hms_std_radiology_set_tests.investigation_id,hms_std_radiology_set.speciality"); 
			$this->db->join('hms_std_radiology_set_tests','hms_std_radiology_set_tests.lab_set_id=hms_std_radiology_set.id');
		//	$this->db->join('hms_std_radiology_set_tests','hms_std_radiology_set.ophthal_set_id=hms_std_radiology_set.id');
			$this->db->from($this->table);
            $this->db->where('hms_std_radiology_set.branch_id',$users_data['parent_id']);
	
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

	
		

			$set_data = array(
					  'branch_id'=>$user_data['parent_id'],
					  'set_name'=>$post['set_name'],
					  'speciality'=>$post['speciality'],
                      'created_date'=>date('Y-m-d')
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $ophthal_set_id=$post['data_id'];
          //  $this->db->set('modified_by',$user_data['id']);
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_std_radiology_set',$set_data); 

			
		 $this->db->where('branch_id',$user_data['parent_id']);
		 $this->db->where('lab_set_id',$post['data_id']); 
		 $this->db->delete('hms_std_radiology_set_tests'); 
		}
		else{  
			$this->db->insert('hms_std_radiology_set',$set_data); 
			$ophthal_set_id=$this->db->insert_id();           
		} 
		$i=0;
		foreach($investigation_id as $invest_id)
		{
			$data = array(
					  'branch_id'=>$user_data['parent_id'],
					  'lab_set_id'=>$ophthal_set_id,
					  'set_name'=>$post['set_name'],
                      'investigation_id'=>$invest_id,
                      'investig_name'=>$investigation_name[$i], 
                      'created_date'=>date('Y-m-d')
		         );
			$this->db->insert('hms_std_radiology_set_tests',$data);  
		$i++;	
	}
	
	}

	
	public function get_by_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		//$this->db->select('hms_std_radiology_set.*');
		$this->db->select("hms_std_radiology_set.branch_id,hms_std_radiology_set.id,hms_std_radiology_set.set_name,hms_std_radiology_set_tests.investig_name,hms_std_radiology_set_tests.investigation_id,hms_std_radiology_set.speciality"); 
			$this->db->join('hms_std_radiology_set_tests','hms_std_radiology_set_tests.lab_set_id=hms_std_radiology_set.id');
	
		$this->db->from('hms_std_radiology_set'); 
		$this->db->where('hms_std_radiology_set.id',$id);
		$this->db->where('hms_std_radiology_set.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->row_array();
	}


 public function get_investigationby_id($id)
	{
		$users_data = $this->session->userdata('auth_users');
		//$this->db->select('hms_std_radiology_set.*');
		$this->db->select("hms_std_radiology_set.branch_id,hms_std_radiology_set.id,hms_std_radiology_set.set_name,hms_std_radiology_set_tests.investig_name,hms_std_radiology_set_tests.investigation_id,hms_std_radiology_set.speciality,hms_std_radiology_set_tests.id as test_id"); 
			$this->db->join('hms_std_radiology_set','hms_std_radiology_set_tests.lab_set_id=hms_std_radiology_set.id');
	
		$this->db->from('hms_std_radiology_set_tests'); 
		$this->db->where('hms_std_radiology_set_tests.lab_set_id',$id);
		$this->db->where('hms_std_radiology_set.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->result();
	}
	public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			
			$this->db->delete('hms_std_radiology_set',array('id'=>$id));
			$this->db->delete('hms_std_radiology_set_tests',array('lab_set_id'=>$id));
			//echo $this->db->last_query();die;
    	} 
    }

    public function deleteall($ids=array())
    {
    	//print_r($ids);die;

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
			//$this->db->set('is_deleted',1);
			//$this->db->set('deleted_by',$user_data['id']);
			//$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$where='id IN ('.$branch_ids.')';
			$this->db->delete('hms_std_radiology_set',$where);
			//echo $this->db->last_query();die;
    	} 
    }

    	function search_report_data(){
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_std_radiology_set.*"); 
		$this->db->from($this->table); 
		$this->db->where('hms_std_radiology_set.is_deleted','0');
		//$this->db->where('hms_std_radiology_set.type','0');
		$this->db->where('hms_std_radiology_set.branch_id',$users_data['parent_id']);
		$query = $this->db->get(); 
		return $query->result();
	}

public function xray_mri_test_search($keyword)
{
    $users_data = $this->session->userdata('auth_users');
    //$head_id = array('1327', '1328');
    $this->db->select('id,test_name,test_code'); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $this->db->where('dept_id',122);
    //$this->db->where_in('test_head_id',$head_id);
   	$this->db->like('test_name',$keyword); 
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}

	
	public function check_unique_value($branch_id, $set_name, $id='')
   {
		$this->db->select('hms_std_radiology_set.*');
		$this->db->from('hms_std_radiology_set'); 
		$this->db->where('hms_std_radiology_set.branch_id',$branch_id);
		$this->db->where('hms_std_radiology_set.set_name',$set_name);
		if(!empty($id))
		$this->db->where('hms_std_radiology_set.id !=',$id);
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