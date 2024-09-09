<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_footer_model extends CI_Model {

	var $table = 'path_test_footer';
	var $column = array('path_test_footer.id','hms_employees.name', 'hms_department.department','path_test_footer.signature','path_test_footer.sign_img','path_test_footer.created_date','path_test_footer.modified_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
            
		$this->db->select("path_test_footer.*, hms_employees.name, hms_department.department"); 
		$this->db->where('path_test_footer.is_deleted','0');
		$this->db->from($this->table);  
	
        $this->db->where('path_test_footer.branch_id',$users_data['parent_id']);
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
    
    public function sign_footer_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('doctor_id','ASC'); 
    	$query = $this->db->get('path_test_footer');
		return $query->result();
    }

	public function footer_get_by_id($id)
	{
		$this->db->select('path_test_footer.*');
		$this->db->from('path_test_footer'); 
		$this->db->where('path_test_footer.id',$id); 
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function footer_save($filename="")
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
                                        'employee_id'=>$post['employee_id'],
					//'doctor_id'=>$post['doctor_id'],
					'dept_id'=>$post['dept_id'],
					'signature'=>$post['signature'],
					//'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			if(!empty($filename))
			{
			   $this->db->set('sign_img',$filename);
			}
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_test_footer',$data);  
		}
		else{    
			if(!empty($filename))
			{
			   $this->db->set('sign_img',$filename);
			}
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('path_test_footer',$data);               
		} 	
	}

    public function delete($id="")
    {
   //  	if(!empty($id) && $id>0)
   //  	{ 
   //  		$this->db->select('*');  
			// $this->db->where('id',$id);
			// $query = $this->db->get('path_test_footer');
			// $result = $query->result();
			// if(!empty($result))
			// {
			//   if(!empty($result[0]->sign_img))
			//   {
			//   	unlink(DIR_UPLOAD_PATH.'doctor_signature/'.$result[0]->sign_img);
			//   }	 
			// }  

			// $this->db->where('id',$id);
			// $this->db->delete('path_test_footer'); 
   //  	} 
    		if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('path_test_footer');
        }
    }

    public function deleteall($ids=array())
    {
   //  	if(!empty($ids))
   //  	{ 

   //  		$id_list = [];
   //  		foreach($ids as $id)
   //  		{
   //  			$this->db->select('*');  
			// 	$this->db->where('id',$id);
			// 	$query = $this->db->get('path_test_footer');
			// 	$result = $query->result();
			// 	if(!empty($result))
			// 	{
			// 	  if(!empty($result[0]->sign_img))
			// 	  {
			// 	  	unlink(DIR_UPLOAD_PATH.'doctor_signature/'.$result[0]->sign_img);
			// 	  }	 
			// 	}

   //  			if(!empty($id) && $id>0)
   //  			{
   //                $id_list[]  = $id;
   //  			} 
   //  		}
   //  		$banch_ids = implode(',', $id_list); 
   //  		$this->db->set('is_deleted','1');
			// $this->db->where('id IN ('.$banch_ids.')');
			// $this->db->update('path_test_footer'); 
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
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id IN ('.$branch_ids.')');
			$this->db->update('path_test_footer');
        }
    }

    public function doctors_list($id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $id_set = "";
        if(!empty($id) && $id>0)
        {
          $id_set = ' WHERE id != "'.$id.'"';
        }
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('doctor_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('id NOT IN (select id from path_test_footer '.$id_set.')');
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    }

}
?>