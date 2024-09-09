<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Under_maintenance_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

    
  

	public function get_by_id($id)
	{
		$this->db->select('hms_maintenance_page.*');
		$this->db->from('hms_maintenance_page'); 
		$this->db->where('hms_maintenance_page.id',$id);
		$this->db->where('hms_maintenance_page.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}

    public function get_all_result()
    {
        $this->db->select('hms_maintenance_page.*');
        $this->db->from('hms_maintenance_page'); 
        $query = $this->db->get(); 
        return $query->result();
    } 
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
        //print '<pre>';print_r($post);die;
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'status'=>$post['status'],
                    'msg'=>$post['message']
				 );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_maintenance_page',$data);  
           // echo $this->db->last_query();die;
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_maintenance_page',$data);               
		} 	
	}


}
?>