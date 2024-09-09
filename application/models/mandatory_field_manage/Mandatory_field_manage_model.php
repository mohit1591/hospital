<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mandatory_field_manage_model extends CI_Model {

	public function get_mandatory_fields()
	{
	  	$this->db->select('*');
		$query = $this->db->get('hms_mandatory_field_to_mandatory');

		$result = $query->result_array();
		return $result;

	  
	}
	public function get_all_fields()
	{
		$this->db->select('*');
		$query = $this->db->get('hms_mandatory_field');
		$result = $query->result_array();
		return $result;
	}

	public function  mandatory_section_field_list($id='')
	{   $users_data = $this->session->userdata('auth_users');
		if(!empty($id))
		{
			$this->db->select("hms_mandatory_field.section_id as non_mandatory_section_id,hms_mandatory_field.branch_id as non_mandatory_branch_id,hms_mandatory_field.required_field_name,hms_mandatory_field.id as none_mandatory_field_id,hms_mandatory_field_to_mandatory.section_id as mandatory_section_id,hms_mandatory_field_to_mandatory.field_id as mandatory_field_id,hms_mandatory_field_to_mandatory.branch_id as mandatory_branch_id");
			$this->db->from('hms_mandatory_field');
			$this->db->join('hms_mandatory_field_to_mandatory','hms_mandatory_field_to_mandatory.field_id=hms_mandatory_field.id and hms_mandatory_field_to_mandatory.branch_id='.$users_data['parent_id'],'left');
			
			$this->db->where('hms_mandatory_field.section_id',$id);
		
			$this->db->order_by('hms_mandatory_field.id','ASC');
			$query = $this->db->get();
			 //echo $this->db->last_query();//die;
			$result = $query->result_array();
			//echo "<pre>";print_r($result); 
			return $result;
		}
	}
	public function save_mandatory_fields($mandatory_field_ids = array()){

        $users_data = $this->session->userdata('auth_users');
        $id_list = [];
		if(!empty($mandatory_field_ids))
    	{
    		
    		foreach($mandatory_field_ids as $id)
    		{
    			if(!empty($id) && $id>0)
    			{
                  $id_list[]  = $id;
    			} 
    		}
        }
		$mandatory_field_id = implode(',', $id_list);
		
           
		
		//get the data according to mandatory fields id
        $result= array();
        if(!empty($mandatory_field_id)){
		    $this->db->select('*');
            $this->db->where('id IN('.$mandatory_field_id.')');
            $query = $this->db->get('hms_mandatory_field');
		    $result = $query->result_array();
        }

        //print_r($result); exit;
		//ends
        
		//delete the data according to mandattory field ids
		
		$this->db->where("branch_id IN('".$users_data['parent_id']."')");
		$this->db->delete('hms_mandatory_field_to_mandatory');
		
     
	
		
		//ends

		if(!empty($result)){
		    for($i=0;$i<count($result);$i++){
		    	$data[$i] = array(
		    		   'section_id'=>$result[$i]['section_id'],
		    		   'field_id'=>$result[$i]['id'],
		    		   'status'=>'1',
                       'branch_id'=>$users_data['parent_id']

		        );
               

		        //insert data
              

		        $this->db->insert('hms_mandatory_field_to_mandatory',$data[$i]);


            }
        }
        


	}

}
?>