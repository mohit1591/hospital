<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blood_bank_inherit_data_model extends CI_Model {


     public function blood_bank_inherit_data($branch_id=''){
	 	
          
         $users_data = $this->session->userdata('auth_users');
          if(!empty($branch_id))
          {

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_blood_bank_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $blood_bank_setting_count = count($result);
                for($i=0;$i<$blood_bank_setting_count;$i++){
                    $blood_bank_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'var_name'=>$result[$i]['var_name'],
                        'var_title'=>$result[$i]['var_title'],
                        'setting_value1'=>$result[$i]['setting_value1'],
                        'setting_value2'=>$result[$i]['setting_value2'],
                        'type'=>$result[$i]['type'],
                        //'old_setting_value'=>$result[$i]['old_setting_value'],

                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into website_setting***********/
                    //$this->db->insert('hms_blood_bank_setting',$blood_bank_setting_data[$i]);
                }
            }
            /**********get data from eye_chief_complaints***********/

         
 }
}
}

?>