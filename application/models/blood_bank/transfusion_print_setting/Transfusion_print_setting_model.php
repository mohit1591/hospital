<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Transfusion_print_setting_model extends CI_Model 
{
	var $table = 'hms_print_transfusion_template';
	var $column = array('hms_print_transfusion_template.id','hms_print_transfusion_template.printer_id', 'hms_print_transfusion_template.section', 'hms_print_transfusion_template.types','hms_print_transfusion_template.template','hms_print_transfusion_template.short_code,hms_print_transfusion_template.printer_paper_type');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

 	public function get_by_id($id)
	{
		$this->db->select("hms_print_transfusion_template.*"); 
		$this->db->from('hms_print_transfusion_template'); 
		$this->db->where('hms_print_transfusion_template.id',$id);
		
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  

		 //echo "<pre>"; print_r($post); exit;
				$data = array(
				"template"=>$post['message'],
				
				); 
        
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            
          
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->where('branch_id',$user_data['parent_id']);
			$this->db->update('hms_print_transfusion_template',$data);  
			//echo $this->db->last_query(); exit;
		}
		/*else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_print_transfusion_template',$data);               
		} 	*/
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
			$this->db->update('hms_print_transfusion_template');
    	} 
    }
  public function template_list($data=""){
  	  $this->db->select("hms_print_transfusion_template.*"); 
		$this->db->from('hms_print_transfusion_template'); 
		$this->db->where($data);
		$query = $this->db->get(); 
		return $query->row_array();
  }

  public function template_format(){

  	    $users_data = $this->session->userdata('auth_users');
  	    $data=array('branch_id'=>$users_data['parent_id']);
  	    $this->db->select("hms_print_transfusion_template.*"); 
		$this->db->from('hms_print_transfusion_template'); 
		$this->db->where($data);
		//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
		$query = $this->db->get(); 
		return $query->row_array();
  }
    
} 
?>