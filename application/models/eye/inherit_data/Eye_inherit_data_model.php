<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eye_inherit_data_model extends CI_Model {


     public function eye_inherit_data($branch_id=''){
	 	
          
         $users_data = $this->session->userdata('auth_users');
          if(!empty($branch_id))
          {

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_prv_history');
            $result = $query->result_array();
            if(!empty($result)){
                $prv_history_count = count($result);
                for($i=0;$i<$prv_history_count;$i++){
                    $prv_history_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'prv_history'=>$result[$i]['prv_history'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_prv_history***********/
                    $this->db->insert('hms_eye_prv_history',$prv_history_data[$i]);
                }
            }
            /**********get data from eye_chief_complaints***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_chief_complaints');
            $result = $query->result_array();
            if(!empty($result)){
                $chief_complaints_count = count($result);
                for($i=0;$i<$chief_complaints_count;$i++){
                    $chief_complaints_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'chief_complaints'=>$result[$i]['chief_complaints'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_chief_complaints***********/
                    $this->db->insert('hms_eye_chief_complaints',$chief_complaints_data[$i]);
                }
            }
            /**********get data from eye_examination***********/

            //Section-16

             $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_systemic_illness');
            $result = $query->result_array();
            if(!empty($result)){
                $eye_chief_complaints_count = count($result);
                for($i=0;$i<$eye_chief_complaints_count;$i++){
                    $eye_chief_complaints_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'chief_complaints'=>$result[$i]['chief_complaints'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_chief_complaints***********/
                    $this->db->insert('hms_eye_systemic_illness',$eye_chief_complaints_data[$i]);
                }
            }
            /**********get data from eye_examination***********/


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_examination');
            $result = $query->result_array();
            if(!empty($result)){
                $examination_count = count($result);
                for($i=0;$i<$examination_count;$i++){
                    $examination_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'examination'=>$result[$i]['examination'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_examination***********/
                    $this->db->insert('hms_eye_examination',$examination_data[$i]);
                }
            }
            /**********get data from eye_diagnosis***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_diagnosis');
            $result = $query->result_array();
            if(!empty($result)){
                $diagnosis_count = count($result);
                for($i=0;$i<$diagnosis_count;$i++){
                    $diagnosis_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'diagnosis'=>$result[$i]['diagnosis'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_diagnosis***********/
                    $this->db->insert('hms_eye_diagnosis',$diagnosis_data[$i]);
                }
            }
            /**********get data from eye_test_name***********/

            //Section-16


            // $this->db->select('*');
            // $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            // $this->db->where('is_deleted','0');
            // $query = $this->db->get('hms_eye_test_name');
            // $result = $query->result_array();
            // if(!empty($result)){
            //     $test_name_count = count($result);
            //     for($i=0;$i<$test_name_count;$i++){
            //         $test_name_data[$i] = array(
            //             'branch_id'=>$branch_id,
            //             'test_name'=>$result[$i]['test_name'],
            //             'ip_address'=>$_SERVER['REMOTE_ADDR'],
            //             'created_by'=>$result[$i]['created_by'],
            //             'modified_by'=>$result[$i]['modified_by'],
            //             'is_deleted'=>$result[$i]['is_deleted'],
            //             'deleted_date'=>$result[$i]['deleted_date'],
            //             'status'=>$result[$i]['status'],
            //             'created_date'=>$result[$i]['created_date']
            //         );
            //         /**********insert data into eye_test_name***********/
            //         $this->db->insert('hms_eye_test_name',$test_name_data[$i]);
            //     }
            // }
            //   /**********get data from hms_eye_medicine***********/

          

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_personal_history');
            $result = $query->result_array();
            if(!empty($result)){
                $eye_personal_history_count = count($result);
                for($i=0;$i<$eye_personal_history_count;$i++){
                    $eye_personal_history_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'personal_history'=>$result[$i]['personal_history'],
                        
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_personal_history***********/
                    $this->db->insert('hms_eye_personal_history',$eye_personal_history_data[$i]);
                }
            }

             /**********get data from eye_medicine_type***********/


            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_medicine_dosage');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_dosage_count = count($result);
                for($i=0;$i<$medicine_dosage_count;$i++){
                    $medicine_dosage_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_dosage'=>$result[$i]['medicine_dosage'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_medicine_type***********/
                    $this->db->insert('hms_eye_medicine_dosage',$medicine_dosage_data[$i]);
                }
            }
             /**********get data from eye_medicine_dosage_duration***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_medicine_dosage_duration');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_dosage_duration_count = count($result);
                for($i=0;$i<$medicine_dosage_duration_count;$i++){
                    $medicine_dosage_duration_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_dosage_duration'=>$result[$i]['medicine_dosage_duration'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_medicine_dosage_duration***********/
                    $this->db->insert('hms_eye_medicine_dosage_duration',$medicine_dosage_duration_data[$i]);
                }
            }
             /**********get data from eye_medicine_dosage_frequency***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_medicine_dosage_frequency');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_dosage_frequency_count = count($result);
                for($i=0;$i<$medicine_dosage_frequency_count;$i++){
                    $medicine_dosage_frequency_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_dosage_frequency'=>$result[$i]['medicine_dosage_frequency'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_medicine_dosage_frequency***********/
                    $this->db->insert('hms_eye_medicine_dosage_frequency',$medicine_dosage_frequency_data[$i]);
                }
            }
             /**********get data from eye_advice***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_advice');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_advice_count = count($result);
                for($i=0;$i<$medicine_advice_count;$i++){
                    $medicine_advice_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_advice'=>$result[$i]['medicine_advice'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_advice***********/
                    $this->db->insert('hms_eye_advice',$medicine_advice_data[$i]);
                }
            }
             /**********get data from eye_suggetion***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_eye_suggetion');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_suggetion_count = count($result);
                for($i=0;$i<$medicine_suggetion_count;$i++){
                    $medicine_suggetion_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_suggetion'=>$result[$i]['medicine_suggetion'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into eye_suggetion***********/
                    $this->db->insert('hms_eye_suggetion',$medicine_suggetion_data[$i]);
                }
            }
            

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
           
            $query = $this->db->get('hms_eye_branch_prescription_setting');
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
                    $this->db->insert('hms_eye_branch_prescription_setting',$prescription_setting_data[$i]);
                }
            }
             /**********get data from hms_branch_prescription_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_eye_branch_prescription_tab_setting');
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
                    $this->db->insert('hms_eye_branch_prescription_tab_setting',$prescription_tab_setting_data[$i]);
                }
            }
                  /**********get data from hms_branch_prescription_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_eye_branch_prescription_medicine_tab_setting');
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
                    $this->db->insert('hms_eye_branch_prescription_medicine_tab_setting',$prescription_tab_medicine_setting_data[$i]);
                }
            }
 }
}
}

?>