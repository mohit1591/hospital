<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Test_print_setting_model extends CI_Model 
{
	var $table = 'path_print_template';
	var $column = array('path_print_template.id','path_print_template.printer_id', 'path_print_template.section', 'path_print_template.types','path_print_template.template','path_print_template.short_code,path_print_template.printer_paper_type');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

 	public function get_by_id($id)
	{
		$this->db->select("path_print_template.*"); 
		$this->db->from('path_print_template'); 
		$this->db->where('path_print_template.id',$id);
		
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
				$data = array(
				'branch_id'=>$user_data['parent_id'],
				"printer_id"=>$post['printer_type'],
				"printer_paper_type"=>$post['printer_paper_type'],
				"types"=>2,
				"section_id"=>3,
				"template"=>$post['message'],
				"short_code"=>$post['short_code']
			); 
        
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('path_print_branch_template',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('path_print_branch_template',$data);               
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
			$this->db->update('path_print_template');
    	} 
    }
  public function template_list($data=""){
  	  $this->db->select("path_print_template.*"); 
		$this->db->from('path_print_template'); 
		$this->db->where($data);
		$query = $this->db->get(); 
		return $query->row_array();
  }

  public function template_format(){
  	    $users_data = $this->session->userdata('auth_users');
  	    $data=array('types'=>2,'section_id'=>3);
  	    $this->db->select("path_print_branch_template.*"); 
		$this->db->from('path_print_branch_template'); 
		$this->db->where($data);
		$this->db->where('branch_id  IN ('.$users_data['parent_id'].',0)'); 
		$query = $this->db->get(); 
		return $query->row_array();
  }
    
} 
?>