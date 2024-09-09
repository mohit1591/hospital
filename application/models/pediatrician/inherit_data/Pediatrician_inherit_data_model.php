<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_inherit_data_model extends CI_Model {


     public function pediatrician_inherit_data($branch_id=''){
	 	
          
         $users_data = $this->session->userdata('auth_users');
          if(!empty($branch_id))
          {
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_pedic_age_vaccine_master');
            $result = $query->result_array();
            if(!empty($result))
            {
              $vaccine_master = count($result);

              for($i=0;$i<$vaccine_master;$i++)
              {
                $vaccine_master_data[$i] = array(
                                              'branch_id'=>$branch_id,
                                              'title'=>$result[$i]['title'],
                                              'start_age'=>$result[$i]['start_age'],
                                              'end_age'=>$result[$i]['end_age'],
                                            'start_age_type'=>$result[$i]['start_age_type'],
                                              'end_age_type'=>$result[$i]['end_age_type'],
                                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                              'created_by'=>$result[$i]['created_by'],
                                              'modified_by'=>$result[$i]['modified_by'],
                                              'is_deleted'=>$result[$i]['is_deleted'],
                                              'status'=>$result[$i]['status'],
                                              'created_date'=>$result[$i]['created_date']
                                            );
                /******************added on 15-03-2018 for inherit BCVA***************/
                $this->db->insert('hms_pedic_age_vaccine_master',$vaccine_master_data[$i]);
              }
            } 


            
     
	}
}
}
?>