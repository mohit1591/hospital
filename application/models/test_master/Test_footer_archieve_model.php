<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_footer_archieve_model extends CI_Model {

	var $table = 'path_test_footer';
	var $column = array('path_test_footer.id','hms_employees.name', 'hms_department.department','path_test_footer.signature','path_test_footer.sign_img','path_test_footer.created_date','path_test_footer.modified_date');  
		var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($branch_id='')
	{
	 $users_data = $this->session->userdata('auth_users');
               $parent_branch_details = $this->session->userdata('parent_branches_data');
         $sub_branch_details = $this->session->userdata('sub_branches_data');
		$this->db->select("path_test_footer.*, hms_employees.name, hms_department.department"); 
		$this->db->where('path_test_footer.is_deleted','1');
		$this->db->from($this->table);  
	if($users_data['users_role']=='2'){
            if(!empty($branch_id)){
                if($branch_id=='inherit'){
            
                    if(!empty($parent_branch_details)){
                        $id_list = [];
                        foreach($parent_branch_details as $id){
                            if(!empty($id) && $id>0){
                                $id_list[]  = $id;
                            } 
                        }
                        $branch_ids = implode(',', $id_list);
                        $this->db->where('path_test_footer.branch_id IN('.$branch_ids.',0)');
                    }else{
                        $this->db->where('path_test_footer.branch_id IN(0)');
                    }
                   
                }else if($branch_id==$users_data['parent_id']){
                    $this->db->where('path_test_footer.branch_id',$users_data['parent_id']);
                    
                }else if($branch_id=='all'){
                    if(!empty($sub_branch_details)){
                        $i=0;
                        $sub_branches = array();
                        foreach($sub_branch_details as $key=>$value){
                            $sub_branches[$i] = $sub_branch_details[$i]['id'];
                            $i= $i+1;
                        }
                        if(!empty($parent_branch_details)){
                            $sub_branches = array_merge($sub_branches,$parent_branch_details);
                        }else{
                            $sub_branches = array_merge($sub_branches);
                        }
                        $sub_branches = array_unique($sub_branches);
                        $id_list = [];
                        foreach($sub_branches as $id){
                            if(!empty($id) && $id>0){
                                $id_list[]  = $id;
                            } 
                        }
                        $branch_ids = implode(',', $id_list);
                        $this->db->where('path_test_footer.branch_id IN('.$branch_ids.','.$users_data['parent_id'].',0)');
                    }else{
                        $this->db->where('path_test_footer.branch_id IN('.$users_data['parent_id'].',0)');
                    }
                 
                    
                }else{
                    $this->db->where('path_test_footer.branch_id',$branch_id);
                  
                }
            }
        }else if($users_data['users_role']=='1'){
            $this->db->where('path_test_footer.branch_id',$users_data['parent_id']);
        }
$this->db->join('hms_users','hms_users.id = path_test_footer.employee_id','left');
         $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id','left');
        //$this->db->join('hms_doctors','hms_doctors.id = path_test_footer.doctor_id','left');
        $this->db->join('hms_department','hms_department.id = path_test_footer.dept_id','left');
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

	public function restore($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_test_footer');
    	} 
    }

    public function restoreall($ids=array())
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
			$this->db->set('is_deleted',0);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$emp_ids.')');
			$this->db->update('path_test_footer');
    	} 
    }

    public function trash($id="")
    {
   //  	if(!empty($id) && $id>0)
   //  	{  
			// $this->db->where('id',$id);
			// $this->db->delete('path_test');

			// // Test Under
			// $this->db->where('parent_id',$id);
			// $this->db->delete('path_test_under');
			// // End Test Under

			// // Test Formula
			// $this->db->where('test_id',$id);
			// $this->db->delete('path_test_formula');
			// // End Test Formula   

			// // Test Range
			// $this->db->where('test_id',$id);
			// $this->db->delete('path_test_range');
			// // End Test Range      
   //  	} 
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_test_footer');
        }
    }

    public function trashall($ids=array())
    {
   //  	if(!empty($ids))
   //  	{
   //  		$id_list = [];
   //  		foreach($ids as $id)
   //  		{
   //  			if(!empty($id) && $id>0)
   //  			{
   //                $id_list[]  = $id;
   //  			} 
   //  		}
   //  		$branch_ids = implode(',', $id_list); 
			// $this->db->where('id IN ('.$branch_ids.')');
			// $this->db->delete('path_test'); 

			// // Test Under
			// $this->db->where('parent_id IN ('.$branch_ids.')');
			// $this->db->delete('path_test_under');
			// // End Test Under

			// // Test Formula
			// $this->db->where('test_id IN ('.$branch_ids.')');
			// $this->db->delete('path_test_formula');
			// // End Test Formula   

			// // Test Range
			// $this->db->where('test_id IN ('.$branch_ids.')');
			// $this->db->delete('path_test_range');
			// // End Test Range 
   //  	} 
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
			$this->db->set('is_deleted',2);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('path_test_footer');
    	}  
    }
 

}
?>