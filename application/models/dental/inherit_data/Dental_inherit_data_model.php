<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dental_inherit_data_model extends CI_Model {


     public function dental_inherit_data($branch_id=''){
	 	
          
         $users_data = $this->session->userdata('auth_users');
          if(!empty($branch_id))
          {



            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query = $this->db->get('hms_dental_branch_prescription_setting');
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
                    $this->db->insert('hms_dental_branch_prescription_setting',$prescription_setting_data[$i]);
                }
            }
             /**********get data from hms_branch_prescription_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_dental_branch_prescription_tab_setting');
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
                    $this->db->insert('hms_dental_branch_prescription_tab_setting',$prescription_tab_setting_data[$i]);
                }
            }




            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_dental_branch_prescription_medicine_tab_setting');
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
                        'print_status'=>$result[$i]['print_status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_dental_branch_prescription_medicine_tab_setting',$prescription_tab_medicine_setting_data[$i]);
                }
            }


              $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_dental_teeth_number');
            $result = $query->result_array();
            if(!empty($result)){
                $dental_teeth_number_count = count($result);
                for($i=0;$i<$dental_teeth_number_count;$i++){
                    $dental_teeth_number_count_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'number'=>$result[$i]['number'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_dental_teeth_number',$dental_teeth_number_count_data[$i]);
                }
            }
 

              $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_dental_teeth_chart');
            $result = $query->result_array();
            if(!empty($result)){
                $dental_teeth_chart_count = count($result);
                for($i=0;$i<$dental_teeth_chart_count;$i++){
                    $dental_teeth_chart_count_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'teeth_name'=>$result[$i]['teeth_name'],
                        'teeth_type'=>$result[$i]['teeth_type'],
                    'teeth_number'=>$result[$i]['teeth_number'],
                    'sort_order'=>$result[$i]['sort_order'],
                    'teeth_image'=>$result[$i]['teeth_image'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_dental_teeth_chart',$dental_teeth_chart_count_data[$i]);
                }
            }



                  /**********get data from hms_branch_prescription_tab_setting***********/

}
}
}
?>