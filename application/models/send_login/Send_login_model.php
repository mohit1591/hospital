<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Send_login_model extends CI_Model {

	
	public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        //echo "<pre>";print_r($post); exit;
        $this->db->select('hms_users.id as u_id,hms_users.username,hms_patient.patient_name,hms_patient.patient_email,hms_patient.mobile_no,hms_patient.id as p_id');
        $this->db->from('hms_patient');
        $this->db->join('hms_users','hms_users.parent_id=hms_patient.id AND hms_users.users_role=4');
        $this->db->where('hms_patient.branch_id',$user_data['parent_id']);
        $this->db->where('hms_patient.is_deleted',0);
        $query = $this->db->get();
        $patient_list =  $query->result();
        //echo $this->db->last_query(); exit;

        if(!empty($patient_list))
        {
            foreach($patient_list as $patient)
            {
                 if(!empty($patient->mobile_no))
                  {
                    $parameter = array('{patient_name}'=>$patient->patient_name,'{username}'=>$patient->username,'{password}'=>$post['password']);
                    if(!empty($patient->u_id) && !empty($post['password']))
                    {
                        $this->db->set('password',md5($post['password']));
                        $this->db->where('users_role',4);
                        $this->db->where('parent_id',$patient->p_id);
                        $this->db->where('id',$patient->u_id);
                        $this->db->update('hms_users');
                    }
                    send_username_sms($post['sms_template'],$patient->patient_name,$patient->mobile_no,$parameter); 
                  }
                
            }
        }
    }

}
?>