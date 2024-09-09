<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Discharge_medicine_print_setting_model extends CI_Model 
{
	var $table = 'hms_ipd_discharge_medicine_print_setting';
	var $column = array('hms_ipd_discharge_medicine_print_setting.id','hms_ipd_discharge_medicine_print_setting.printer_id', 'hms_ipd_discharge_medicine_print_setting.section', 'hms_ipd_discharge_medicine_print_setting.types','hms_ipd_discharge_medicine_print_setting.template','hms_ipd_discharge_medicine_print_setting.short_code,hms_ipd_discharge_medicine_print_setting.printer_paper_type');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

 	public function get_by_id($id)
	{
		$this->db->select("hms_ipd_discharge_medicine_print_setting.*"); 
		$this->db->from('hms_ipd_discharge_medicine_print_setting'); 
		$this->db->where('hms_ipd_discharge_medicine_print_setting.id',$id);
		
		$query = $this->db->get(); 
		return $query->row_array();
	}
	


	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  

		 
				$data = array(
				'branch_id'=>$user_data['parent_id'],
				"setting_name"=>'DISCHARGE_MEDICINE_PRINT_SETTING',
				"setting_value"=>$post['message'],
				'ip_address'=>$_SERVER['REMOTE_ADDR']
				); 
        
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            
          
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->where('branch_id',$user_data['parent_id']);
			$this->db->update('hms_ipd_discharge_medicine_print_setting',$data);  
			//echo $this->db->last_query(); exit;
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_ipd_discharge_medicine_print_setting',$data);               
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
			$this->db->update('hms_ipd_discharge_medicine_print_setting');
    	} 
    }
  public function template_list($data=""){
  	  $this->db->select("hms_print_template.*"); 
		$this->db->from('hms_ipd_discharge_medicine_print_setting'); 
		$this->db->where($data);
		$query = $this->db->get(); 
		return $query->row_array();
  }

  public function template_format(){

  	    $users_data = $this->session->userdata('auth_users');
  	    //echo $users_data['parent_id'];die;
  	    $data=array('branch_id'=>$users_data['parent_id']);
  	    $this->db->select("hms_ipd_discharge_medicine_print_setting.*"); 
		$this->db->from('hms_ipd_discharge_medicine_print_setting'); 
		$this->db->where($data);
		//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
		$query = $this->db->get(); 
		return $query->row_array();
  }
    
} 
?>