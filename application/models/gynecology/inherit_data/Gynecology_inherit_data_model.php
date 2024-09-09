<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gynecology_inherit_data_model extends CI_Model {


     public function gynecology_inherit_data($branch_id=''){
	 	
          
         $users_data = $this->session->userdata('auth_users');
          if(!empty($branch_id))
          {



            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query = $this->db->get('hms_branch_gynecology_prescription_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $prescription_setting_count = count($result);
                for($i=0;$i<$prescription_setting_count;$i++){
                    $prescription_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'type'=>$result[$i]['type'],
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_setting***********/
                    $this->db->insert('hms_branch_gynecology_prescription_setting',$prescription_setting_data[$i]);
                }
            }
             /**********get data from hms_branch_prescription_tab_setting***********/

            


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_gynecology_branch_patient_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $prescription_tab_setting_count = count($result);
                for($i=0;$i<$prescription_tab_setting_count;$i++){
                    $prescription_tab_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'order_by'=>$result[$i]['order_by'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'status'=>$result[$i]['status'],
                        'print_status'=>$result[$i]['print_status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_gynecology_branch_patient_tab_setting',$prescription_tab_setting_data[$i]);
                }
            }




            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('type','2');
         
            $query = $this->db->get('hms_branch_prescription_medicine_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $prescription_tab_medicine_setting_count = count($result);
                for($i=0;$i<$prescription_tab_medicine_setting_count;$i++){
                    $prescription_tab_medicine_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'order_by'=>$result[$i]['order_by'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'status'=>$result[$i]['status'],
                        'type'=>$result[$i]['type'],
                        'print_status'=>$result[$i]['print_status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_medicine_tab_setting***********/
                    $this->db->insert('hms_branch_prescription_medicine_tab_setting',$prescription_tab_medicine_setting_data[$i]);
                }
            }






                  /**********get data from hms_branch_prescription_tab_setting***********/

}
}
}
?>