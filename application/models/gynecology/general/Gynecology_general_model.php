<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gynecology_general_model extends CI_Model 
{
  
	public function __construct()
	{
		parent::__construct();  
	}


   
    public function complaints($complaints="",$id="")
    {

        $this->db->select('*');  
        if(!empty($complaints))
        {
        $this->db->where('complaints',$complaints);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_gynecology_chief_complaints');
        $result = $query->result(); 

        return $result; 
    }

    
    public function check_disease($disease_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($disease_name))
        {
        $this->db->where('disease_name',$disease_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_gynecology_disease');
        $result = $query->result(); 

        return $result; 
    }

    public function check_allergy($allergy_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($allergy_name))
        {
        $this->db->where('allergy_name',$allergy_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_gynecology_allergy');
        $result = $query->result(); 

        return $result; 
    }

      public function check_advice($advice="",$id="")
    {

        $this->db->select('*');  
        if(!empty($advice))
        {
        $this->db->where('advice',$advice);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_gynecology_advice');
        $result = $query->result(); 

        return $result; 
    }

    public function check_investigation($inves='' ,$id='')
    {
        $this->db->select('*');
        if(!empty($inves))
        {
            $this->db->where('investigation',$inves);
        }
        if(!empty($id))
        {
             $this->db->where('id != "'.$id.'"');
        }
       $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_gynecology_investigation');
        $result = $query->result(); 

        return $result; 

    }

    function get_gynecology_patient_tab_setting($parent_id="",$order_by='')
    {

            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_gynecology_branch_patient_tab_setting.*,hms_gynecology_patient_tab_setting.var_title');   
            $this->db->join('hms_gynecology_branch_patient_tab_setting','hms_gynecology_branch_patient_tab_setting.unique_id = hms_gynecology_patient_tab_setting.id');
            $this->db->where('hms_gynecology_branch_patient_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_gynecology_branch_patient_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_gynecology_branch_patient_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_gynecology_branch_patient_tab_setting.status',1);
            $query = $this->db->get('hms_gynecology_patient_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_gynecology_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_prescription_medicine_tab_setting.*,hms_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_branch_prescription_medicine_tab_setting','hms_branch_prescription_medicine_tab_setting.unique_id = hms_prescription_medicine_tab_setting.id');
            $this->db->where('hms_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_branch_prescription_medicine_tab_setting.type',2);
            $query = $this->db->get('hms_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
}
