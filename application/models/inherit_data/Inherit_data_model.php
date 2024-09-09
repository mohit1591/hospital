<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inherit_data_model extends CI_Model {


     public function inherit_data($branch_id=''){
	 	
          
         $users_data = $this->session->userdata('auth_users');
          if(!empty($branch_id))
          {
            //department status
            $this->db->select('*');
            $this->db->where('branch_id','0');
            $this->db->where('module','5');
            $query = $this->db->get('hms_department');
            $result = $query->result_array();
            if(!empty($result)){
                 $department_count = count($result);
                 for($i=0;$i<$department_count;$i++)
                 {
                      $department_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'status'=>1,
                              'department_id'=>$result[$i]['id'],
                              'updated_date'=>date('Y-m-d H:i:s')
                        );
                         

                   $this->db->insert('hms_department_to_department_status',$department_data[$i]);
                   //echo $this->db->last_query(); die;
                 }
            }
        //department status


               $this->db->select('*');
               $this->db->where('branch_id IN('.$users_data['parent_id'].')');
             
               $query = $this->db->get('hospital_reciept_setting');
               $result = $query->result_array();
               //print_r( $result);die;
              // rray ( [0] => Array ( [id] => 5 [branch_id] => 0 [prefix] => sara123 [suffix] => 6565 [ip_address] => 192.168.1.211 [created_by] => 160 [created_date] => 2018-01-18 14:40:04 ) ) 
               if(!empty($result)){
                    $receipt_no_setting_count = count($result);
                    for($i=0;$i<$receipt_no_setting_count;$i++){
                        $receipt_no_setting[$i] = array(
                              "branch_id"=>$branch_id,
                               "prefix"=>$result[$i]['prefix'],
                               "suffix"=>$result[$i]['suffix'],
                               "ip_address"=>$_SERVER['REMOTE_ADDR'],
                               "created_by"=>$result[$i]['created_by'], 
                               "created_date"=>$result[$i]['created_date']
                        );
                         /********insert data into email setting************/

                        $this->db->insert('hospital_reciept_setting',$receipt_no_setting[$i]);
                        //echo $this->db->last_query();
                    }
               } 
            
               /********get data from email template***************/

             /* Inherit profile name and print setting*/
               $this->db->select('*');
               $this->db->where('branch_id IN('.$users_data['parent_id'].')');
               $query = $this->db->get('path_print_test_name_setting');
               $result = $query->result_array();
               //print_r( $result);die;
              if(!empty($result))
              {
                    $print_test_name_setting_count = count($result);
                    for($i=0;$i<$print_test_name_setting_count;$i++){
                        $print_test_setting[$i] = array(
                              "branch_id"=>$branch_id,
                               "module_name"=>$result[$i]['module_name'],
                               "module"=>$result[$i]['module'],
                               "profile_status"=>$result[$i]['profile_status'],
                               "print_status"=>$result[$i]['print_status'],
                               "created_date"=>$result[$i]['created_date']
                        );
                         /********insert data into email setting************/

                        $this->db->insert('path_print_test_name_setting',$print_test_setting[$i]);
                        //echo $this->db->last_query();
                    }
               } 

              
               //Section-2

               $this->db->select('*');
               $this->db->where('branch_id IN('.$users_data['parent_id'].')');
             
               $query = $this->db->get('hms_email_setting');
               $result = $query->result_array();
               if(!empty($result)){
                    $email_setting_count = count($result);
                    for($i=0;$i<$email_setting_count;$i++){
                      	$email_setting_data[$i] = array(
                            'branch_id'=>$branch_id,
                            'cc_email'=>'',//$result[$i]['cc_email'],
                            'smtp_address'=>'',//$result[$i]['smtp_address'],
                            'network_email_address'=>'',//$result[$i]['network_email_address'],
                            'email_password'=>'',//$result[$i]['email_password'],
                            'port'=>'',//$result[$i]['port'],
                            'smtp_ssl'=>'',//$result[$i]['smtp_ssl'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'created_by'=>$result[$i]['created_by'],
                            'modified_by'=>$result[$i]['modified_by'],
                            'created_date'=>$result[$i]['created_date']
                      	);
                         /********insert data into email setting************/

                      	$this->db->insert('hms_email_setting',$email_setting_data[$i]);
                    }
               } 
                  
            

            /********get data from expense category***********/

            //Section-5

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_expenses_category');
            $result = $query->result_array();
            if(!empty($result)){
                 $expenses_category_count = count($result);
                 for($i=0;$i<$expenses_category_count;$i++){
                      $expenses_category_data[$i] = array(
                              'branch_id'=>$branch_id,
                               'exp_category'=>$result[$i]['exp_category'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                        /********insert data into expense category********/

                         $this->db->insert('hms_expenses_category',$expenses_category_data[$i]);
                 }
            }
            /********get data from employee type**************/

             //Section-6


           $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_emp_type');
            $result = $query->result_array();
            if(!empty($result)){
                 $emp_type_count = count($result);
                 for($i=0;$i<$emp_type_count;$i++){
                      $emp_type_data[$i] = array(
                              'branch_id'=>$branch_id,
                               'emp_type'=>$result[$i]['emp_type'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                        /********insert data into employee type***********/

                         $this->db->insert('hms_emp_type',$emp_type_data[$i]);
                 }
            }
            /********get data from sample type****************/

            
            //Section-8


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $this->db->where('default_value','0');
            $query = $this->db->get('hms_specialization');
            $result = $query->result_array();
            if(!empty($result)){
                 $specialization_count = count($result);
                 for($i=0;$i<$specialization_count;$i++){
                      $specialization_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'specialization'=>$result[$i]['specialization'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into specialization***********/

                         $this->db->insert('hms_specialization',$specialization_data[$i]);
                 }
            }
            /**********get data from religion***********/

            //Section-9


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_religion');
            $result = $query->result_array();
            if(!empty($result)){
                 $religion_count = count($result);
                 for($i=0;$i<$religion_count;$i++){
                      $religion_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'religion'=>$result[$i]['religion'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into religion***********/

                         $this->db->insert('hms_religion',$religion_data[$i]);
                 }
            }
            /**********get data from relation***********/

            //Section-10


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_relation');
            $result = $query->result_array();
            if(!empty($result)){
                 $relation_count = count($result);
                 for($i=0;$i<$relation_count;$i++){
                      $relation_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'relation'=>$result[$i]['relation'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into relation***********/

                         $this->db->insert('hms_relation',$relation_data[$i]);
                 }
            }
            /**********get data from simulation***********/

            //Section-11


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_simulation');
            $result = $query->result_array();
            if(!empty($result)){
                 $simulation_count = count($result);
                 for($i=0;$i<$simulation_count;$i++){
                      $simulation_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'simulation'=>$result[$i]['simulation'],
							  'gender'=>$result[$i]['gender'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into simulation***********/

                         $this->db->insert('hms_simulation',$simulation_data[$i]);
                 }
            }
            /**********get data from insurance_type***********/

            //Section-12


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_insurance_type');
            $result = $query->result_array();
            if(!empty($result)){
                 $insurance_type_count = count($result);
                 for($i=0;$i<$insurance_type_count;$i++){
                      $insurance_type_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'insurance_type'=>$result[$i]['insurance_type'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into insurance_type***********/

                         $this->db->insert('hms_insurance_type',$insurance_type_data[$i]);
                 }
            }
            /**********get data from insurance_company***********/

            //Section-13


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_insurance_company');
            $result = $query->result_array();
            if(!empty($result)){
                 $insurance_company_count = count($result);
                 for($i=0;$i<$insurance_company_count;$i++){
                      $insurance_company_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'insurance_company'=>$result[$i]['insurance_company'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into insurance_company***********/

                         $this->db->insert('hms_insurance_company',$insurance_company_data[$i]);
                 }
            }
      
            /**********get data from default_vals***********/

            //Section-14


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_default_vals');
            $result = $query->result_array();
            if(!empty($result)){
                 $default_vals_count = count($result);
                 for($i=0;$i<$default_vals_count;$i++){
                      $default_vals_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'default_vals'=>$result[$i]['default_vals'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into default_vals***********/

                         $this->db->insert('hms_default_vals',$default_vals_data[$i]);
                 }
            }
            /**********get data from unit***********/

            //Section-15


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_unit');
            $result = $query->result_array();
            if(!empty($result)){
                 $unit_count = count($result);
                 for($i=0;$i<$unit_count;$i++){
                      $unit_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'unit'=>$result[$i]['unit'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_by'=>$result[$i]['modified_by'],
                              'is_deleted'=>$result[$i]['is_deleted'],
                              'deleted_date'=>$result[$i]['deleted_date'],
                              'status'=>$result[$i]['status'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into unit***********/

                         $this->db->insert('hms_unit',$unit_data[$i]);
                 }
            }
             /**********get data from medicine_unit***********/

            //Section-16


             /* comment code inherit medicine unit
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_medicine_unit');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_unit_count = count($result);
                for($i=0;$i<$medicine_unit_count;$i++){
                    $medicine_unit_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_unit'=>$result[$i]['medicine_unit'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into medicine_company***********/
                   /*  $this->db->insert('hms_medicine_unit',$medicine_unit_data[$i]);
                }
            } comment code inherit medicine unit*/
             /**********get data from hms_medicine_racks***********/

            //Section-16


            /* comment code inherit hms_medicine_racks 
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_medicine_racks');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_rack_count = count($result);
                for($i=0;$i<$medicine_rack_count;$i++){
                    $medicine_rack_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'rack_no'=>$result[$i]['rack_no'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_medicine_racks***********/
                    /* $this->db->insert('hms_medicine_racks',$medicine_rack_data[$i]);
                }
            } comment code inherit hms_medicine_racks */
            /**********get data from medicine_company***********/

            //Section-16


            /* comment code inherit hms_medicine_company 
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_medicine_company');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_company_count = count($result);
                for($i=0;$i<$medicine_company_count;$i++){
                    $medicine_company_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'company_name'=>$result[$i]['company_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into medicine_company***********/
                   /* $this->db->insert('hms_medicine_company',$medicine_company_data[$i]);
                }
            } comment code inherit hms_medicine_company  */
             /**********get data from medicine_entry***********/

            //Section-16


            /* comment code inherit hms_medicine_entry
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_medicine_entry');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_entry_count = count($result);
                for($i=0;$i<$medicine_entry_count;$i++){
                   
                    $medicine_entry_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_code'=>$i+1,
                        'medicine_name'=>$result[$i]['medicine_name'],
                        'unit_id'=>$result[$i]['unit_id'],
                        'unit_second_id'=>$result[$i]['unit_second_id'],
                        'conversion'=>$result[$i]['conversion'],
                        'min_alrt'=>$result[$i]['min_alrt'],
                        'packing'=>$result[$i]['packing'],
                        'rack_no'=>$result[$i]['rack_no'],
                        'salt'=>$result[$i]['salt'],
                        'manuf_company'=>$result[$i]['manuf_company'],
                        'mrp'=>$result[$i]['mrp'],
                        'purchase_rate'=>$result[$i]['purchase_rate'],
                        'vat'=>$result[$i]['vat'],
                        'discount'=>$result[$i]['discount'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into medicine_entry***********/
                   /* $this->db->insert('hms_medicine_entry',$medicine_entry_data[$i]);
                }
            } comment code inherit hms_medicine_entry*/
            //  /**********get data from medicine_vendors***********/

            // //Section-16


            // $this->db->select('*');
            // $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            // $this->db->where('is_deleted','0');
            // $query = $this->db->get('hms_medicine_vendors');
            // $result = $query->result_array();
            // if(!empty($result)){
            //     $medicine_vendors_count = count($result);
            //     for($i=0;$i<$medicine_vendors_count;$i++){
            //         $medicine_vendors_data[$i] = array(
            //             'branch_id'=>$branch_id,
            //             'vendor_id'=>$result[$i]['vendor_id'],
            //             'name'=>$result[$i]['name'],
            //             'email'=>$result[$i]['email'],
            //             'mobile'=>$result[$i]['mobile'],
            //             'address'=>$result[$i]['address'],
            //             'ip_address'=>$_SERVER['REMOTE_ADDR'],
            //             'created_by'=>$result[$i]['created_by'],
            //             'modified_by'=>$result[$i]['modified_by'],
            //             'is_deleted'=>$result[$i]['is_deleted'],
            //             'deleted_date'=>$result[$i]['deleted_date'],
            //             'status'=>$result[$i]['status'],
            //             'created_date'=>$result[$i]['created_date']
            //         );
            //         /**********insert data into medicine_company***********/
            //         $this->db->insert('hms_medicine_vendors',$medicine_vendors_data[$i]);
            //     }
            // }
            /**********get data from opd_prv_history***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_prv_history');
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
                    /**********insert data into opd_prv_history***********/
                    $this->db->insert('hms_opd_prv_history',$prv_history_data[$i]);
                }
            }
            /**********get data from opd_chief_complaints***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_chief_complaints');
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
                    /**********insert data into opd_chief_complaints***********/
                    $this->db->insert('hms_opd_chief_complaints',$chief_complaints_data[$i]);
                }
            }
            /**********get data from opd_examination***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_examination');
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
                    /**********insert data into opd_examination***********/
                    $this->db->insert('hms_opd_examination',$examination_data[$i]);
                }
            }
            /**********get data from opd_diagnosis***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_diagnosis');
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
                    /**********insert data into opd_diagnosis***********/
                    $this->db->insert('hms_opd_diagnosis',$diagnosis_data[$i]);
                }
            }
            /**********get data from opd_test_name***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_test_name');
            $result = $query->result_array();
            if(!empty($result)){
                $test_name_count = count($result);
                for($i=0;$i<$test_name_count;$i++){
                    $test_name_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'test_name'=>$result[$i]['test_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into opd_test_name***********/
                    $this->db->insert('hms_opd_test_name',$test_name_data[$i]);
                }
            }
              /**********get data from hms_opd_medicine***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_medicine');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_count = count($result);
                for($i=0;$i<$medicine_count;$i++){
                    $medicine_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine'=>$result[$i]['medicine'],
                        'type'=>$result[$i]['type'],
                        'salt'=>$result[$i]['salt'],
                        'brand'=>$result[$i]['brand'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_opd_medicine***********/
                    $this->db->insert('hms_opd_medicine',$medicine_data[$i]);
                }
            }
             /**********get data from opd_personal_history***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_personal_history');
            $result = $query->result_array();
            if(!empty($result)){
                $opd_personal_history_count = count($result);
                for($i=0;$i<$opd_personal_history_count;$i++){
                    $opd_personal_history_data[$i] = array(
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
                    /**********insert data into opd_personal_history***********/
                    $this->db->insert('hms_opd_personal_history',$opd_personal_history_data[$i]);
                }
            }

             /**********get data from opd_medicine_type***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_medicine_type');
            $result = $query->result_array();
            if(!empty($result)){
                $medicine_type_count = count($result);
                for($i=0;$i<$medicine_type_count;$i++){
                    $medicine_type_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'medicine_type'=>$result[$i]['medicine_type'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into opd_medicine_type***********/
                    $this->db->insert('hms_opd_medicine_type',$medicine_type_data[$i]);
                }
            }
             /**********get data from opd_medicine_dosage***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_medicine_dosage');
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
                    /**********insert data into opd_medicine_type***********/
                    $this->db->insert('hms_opd_medicine_dosage',$medicine_dosage_data[$i]);
                }
            }
             /**********get data from opd_medicine_dosage_duration***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_medicine_dosage_duration');
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
                    /**********insert data into opd_medicine_dosage_duration***********/
                    $this->db->insert('hms_opd_medicine_dosage_duration',$medicine_dosage_duration_data[$i]);
                }
            }
             /**********get data from opd_medicine_dosage_frequency***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_medicine_dosage_frequency');
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
                    /**********insert data into opd_medicine_dosage_frequency***********/
                    $this->db->insert('hms_opd_medicine_dosage_frequency',$medicine_dosage_frequency_data[$i]);
                }
            }
             /**********get data from opd_advice***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_advice');
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
                    /**********insert data into opd_advice***********/
                    $this->db->insert('hms_opd_advice',$medicine_advice_data[$i]);
                }
            }
             /**********get data from opd_suggetion***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_suggetion');
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
                    /**********insert data into opd_suggetion***********/
                    $this->db->insert('hms_opd_suggetion',$medicine_suggetion_data[$i]);
                }
            }
              /**********get data from hms_opd_prescription_template***********/

            //Section-16
            //hms_ipd_room_charge_type

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_ipd_room_charge_type');
            $result = $query->result_array();
            if(!empty($result)){
                $charge_type_count = count($result);
                for($i=0;$i<$charge_type_count;$i++){
                    $charge_type_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'charge_type'=>$result[$i]['charge_type'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into opd_suggetion***********/
                    $this->db->insert('hms_ipd_room_charge_type',$charge_type_data[$i]);
                }
            }

            //hms_ipd_room_category

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_ipd_room_category');
            $result = $query->result_array();
            if(!empty($result)){
                $room_category_count = count($result);
                for($i=0;$i<$room_category_count;$i++){
                    $room_category_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'room_category'=>$result[$i]['room_category'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into opd_suggetion***********/
                    $this->db->insert('hms_ipd_room_category',$room_category_data[$i]);
                }
            }
              
            //hms_ipd_room_category

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_prescription_template');
            $result = $query->result_array();
            if(!empty($result)){
                $prescription_template_count = count($result);
                for($i=0;$i<$prescription_template_count;$i++){
                    $prescription_template_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'template_name'=>$result[$i]['template_name'],
                        'patient_bp'=>$result[$i]['patient_bp'],
                        'patient_temp'=>$result[$i]['patient_temp'],
                        'patient_weight'=>$result[$i]['patient_weight'],
                        'patient_height'=>$result[$i]['patient_height'],
                        'patient_spo2'=>$result[$i]['patient_spo2'],
                        'prescription_medicine'=>$result[$i]['prescription_medicine'],
                        'prv_history'=>$result[$i]['prv_history'],
                         'personal_history'=>$result[$i]['personal_history'],
                         'chief_complaints'=>$result[$i]['chief_complaints'],
                         'examination'=>$result[$i]['examination'],
                         'diagnosis'=>$result[$i]['diagnosis'],
                         'suggestion'=>$result[$i]['suggestion'],
                         'remark'=>$result[$i]['remark'],
                         'appointment_date'=>$result[$i]['appointment_date'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                         'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_opd_prescription_template***********/
                    $this->db->insert('hms_opd_prescription_template',$prescription_template_data[$i]);
                }
            }
            /**********get data from hms_branch_prescription_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
           
            $query = $this->db->get('hms_branch_prescription_setting');
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
                    $this->db->insert('hms_branch_prescription_setting',$prescription_setting_data[$i]);
                }
            }
             /**********get data from hms_branch_prescription_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_prescription_tab_setting');
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
                    $this->db->insert('hms_branch_prescription_tab_setting',$prescription_tab_setting_data[$i]);
                }
            }
                  /**********get data from hms_branch_prescription_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('type','0');
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
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_prescription_medicine_tab_setting',$prescription_tab_medicine_setting_data[$i]);
                }
            }
             /**********get data from opd_perticular***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_particular');
            $result = $query->result_array();
            if(!empty($result)){
                $perticular_count = count($result);
                for($i=0;$i<$perticular_count;$i++){
                    $perticular_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'particular'=>$result[$i]['particular'],
                        'charge'=>$result[$i]['charge'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into opd_perticular***********/
                    $this->db->insert('hms_opd_particular',$perticular_data[$i]);
                }
            }
            //   /**********get data from opd_prescription***********/

            // //Section-16


            // $this->db->select('*');
            // $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            // $this->db->where('is_deleted','0');
            // $query = $this->db->get('hms_opd_prescription');
            // $result = $query->result_array();
            // if(!empty($result)){
            //     $prescription_count = count($result);
            //     for($i=0;$i<$prescription_count;$i++){
            //         $prescription_data[$i] = array(
            //             'branch_id'=>$branch_id,
            //             'booking_code'=>$result[$i]['booking_code'],
            //             'patient_id'=>$result[$i]['patient_id'],
            //             'attended_doctor'=>$result[$i]['attended_doctor'],
            //             'patient_code'=>$result[$i]['patient_code'],
            //             'patient_name'=>$result[$i]['patient_name'],
            //             'mobile_no'=>$result[$i]['mobile_no'],
            //             'gender'=>$result[$i]['gender'],
            //             'age_y'=>$result[$i]['age_y'],
            //             'age_m'=>$result[$i]['age_m'],
            //             'age_d'=>$result[$i]['age_d'],
            //             'bp'=>$result[$i]['bp'],
            //             'patient_bp'=>$result[$i]['patient_bp'],
            //             'patient_temp'=>$result[$i]['patient_temp'],
            //             'patient_weight'=>$result[$i]['patient_weight'],
            //             'patient_height'=>$result[$i]['patient_height'],
            //             'patient_spo2'=>$result[$i]['patient_spo2'],
            //             'patient_rbs'=>$result[$i]['patient_rbs'],
            //             'next_appointment_date'=>$result[$i]['next_appointment_date'],
            //             'prv_history'=>$result[$i]['prv_history'],
            //             'personal_history'=>$result[$i]['personal_history'],
            //             'chief_complaints'=>$result[$i]['chief_complaints'],
            //             'examination'=>$result[$i]['examination'],
            //             'diagnosis'=>$result[$i]['diagnosis'],
            //             'suggestion'=>$result[$i]['suggestion'],
            //             'remark'=>$result[$i]['remark'],
            //             'appointment_date'=>$result[$i]['appointment_date'],
            //             'ip_address'=>$_SERVER['REMOTE_ADDR'],
            //             'created_by'=>$result[$i]['created_by'],
            //             'modified_by'=>$result[$i]['modified_by'],
            //             'is_deleted'=>$result[$i]['is_deleted'],
            //             'deleted_date'=>$result[$i]['deleted_date'],
            //             'status'=>$result[$i]['status'],
            //             'created_date'=>$result[$i]['created_date']
            //         );
            //         /**********insert data into opd_prescription***********/
            //         $this->db->insert('hms_opd_prescription',$prescription_data[$i]);
            //     }
            // }
             /**********get data from hms_print_branch_template***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query = $this->db->get('hms_print_branch_template');
            $result = $query->result_array();
            if(!empty($result)){
                $print_branch_template_count = count($result);
                for($i=0;$i<$print_branch_template_count;$i++){
                    $print_branch_template_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'printer_id'=>$result[$i]['printer_id'],
                        'printer_paper_type'=>$result[$i]['printer_paper_type'],
                        'types'=>$result[$i]['types'],
                        'section_id'=>$result[$i]['section_id'],
                        'template'=>$result[$i]['template'],
                        'short_code'=>$result[$i]['short_code'],
                        'sub_section'=>$result[$i]['sub_section'],                        
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_print_branch_template***********/
                    $this->db->insert('hms_print_branch_template',$print_branch_template_data[$i]);
                }
            }
            
             //path_print_branch_template
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query = $this->db->get('path_print_branch_template');
            $result = $query->result_array();
            if(!empty($result)){
                $print_branch_template_count = count($result);
                for($i=0;$i<$print_branch_template_count;$i++){
                    $print_branch_template_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'printer_id'=>$result[$i]['printer_id'],
                        'printer_paper_type'=>$result[$i]['printer_paper_type'],
                        'types'=>$result[$i]['types'],
                        'section_id'=>$result[$i]['section_id'],
                        'template'=>$result[$i]['template'],
                        'short_code'=>$result[$i]['short_code'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_print_branch_template***********/
                    $this->db->insert('path_print_branch_template',$print_branch_template_data[$i]);
                    //echo $this->db->last_query(); exit;
                }
            }
            //path_print_branch_template
            /*if($users_data['users_role']==2)
            {/* need to dicuss with radhey sir */

               /**********get data from hms_branch_unique_ids***********/
               $this->db->select('*');
               $this->db->where('branch_id IN('.$users_data['parent_id'].')');
               
               $query = $this->db->get('hms_branch_unique_ids');
               $result = $query->result_array();
               if(!empty($result)){
                    $branch_unique_ids_count = count($result);
                    for($i=0;$i<$branch_unique_ids_count;$i++){
                         $branch_unique_ids_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'unique_id'=>$result[$i]['unique_id'],
                              'prefix'=>$result[$i]['prefix'],
                              'start_num'=>$result[$i]['start_num'],
                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                              'created_by'=>$result[$i]['created_by'],
                              'modified_date'=>$result[$i]['modified_date'],
                              'created_date'=>$result[$i]['created_date']
                         );
                         /**********insert data into opd_perticular***********/
                         $this->db->insert('hms_branch_unique_ids',$branch_unique_ids_data[$i]);
                    }
               }
           /* } /* need to dicuss with radhey sir */
             /**********get data from website_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_website_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $website_setting_count = count($result);
                for($i=0;$i<$website_setting_count;$i++){
                    $website_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'var_name'=>$result[$i]['var_name'],
                        'var_title'=>$result[$i]['var_title'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'old_setting_value'=>$result[$i]['old_setting_value'],

                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into website_setting***********/
                    $this->db->insert('hms_website_setting',$website_setting_data[$i]);
                }
            }
                 /**********get data from Bank Master***********/
          
           /*hms_birthday_anniversary*/ 

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_birthday_anniversary');
            $result = $query->result_array();
            if(!empty($result)){
                $birthday_anniversary_count = count($result);
                for($i=0;$i<$birthday_anniversary_count;$i++){
                    $birthday_anniversary_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'sms_birthday'=>$result[$i]['sms_birthday'],
                        'birthday_sms_template'=>$result[$i]['birthday_sms_template'],
                        'sms_anniversary'=>$result[$i]['sms_anniversary'],
                        'anniversary_sms_template'=>$result[$i]['anniversary_sms_template'],
                        'email_bithday'=>$result[$i]['email_bithday'],
                        'email_birthday_template'=>$result[$i]['email_birthday_template'],
                        'email_anniversary'=>$result[$i]['email_anniversary'],
                        'anniversary_email_template'=>$result[$i]['anniversary_email_template'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into website_setting***********/
                    $this->db->insert('hms_birthday_anniversary',$birthday_anniversary_data[$i]);
                }
            }

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_bank');
            $result = $query->result_array();
            if(!empty($result)){
                $bank_count = count($result);
                for($i=0;$i<$bank_count;$i++){
                    $bank_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'bank_name'=>$result[$i]['bank_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        'status'=>$result[$i]['status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into website_setting***********/
                    $this->db->insert('hms_bank',$bank_data[$i]);
                }
            }



            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_sms_config');
            $result = $query->result_array();
            if(!empty($result)){
                $prescription_tab_setting_count = count($result);
                for($i=0;$i<$prescription_tab_setting_count;$i++){
                    $sms_config_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'url'=>'', //$result[$i]['url']
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    
                    $this->db->insert('hms_branch_sms_config',$sms_config_data[$i]);
                }
            }
                  /**********get data from hms_branch_prescription_tab_setting***********/


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_sms_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $sms_setting_setting_count = count($result);
                for($i=0;$i<$sms_setting_setting_count;$i++){
                    $sms_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'sms_status'=>$result[$i]['sms_status'],
                        'email_status'=>$result[$i]['email_status'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_sms_setting',$sms_setting_data[$i]);
                }
            }
                  /**********get data from hms_branch_prescription_tab_setting***********/

 /**********get data from hms_sms_branch_template***********/
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_sms_branch_template');
            $result = $query->result_array();
            if(!empty($result)){
                $template_setting_count = count($result);
                for($i=0;$i<$template_setting_count;$i++){
                    $template_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'form_name'=>$result[$i]['form_name'],  //form id
                        'template'=>$result[$i]['template'],
                        'short_code'=>$result[$i]['short_code'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'status'=>$result[$i]['status'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_sms_branch_template',$template_setting_data[$i]);
                }
            }
  /**********get data from hms_sms_branch_template***********/



 

 /**********get data from hms_email_branch_template***********/
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_email_branch_template');
            $result = $query->result_array();
            if(!empty($result)){
                $template_email_setting_count = count($result);
                for($i=0;$i<$template_email_setting_count;$i++){
                    $email_template_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'type'=>$result[$i]['type'],
                        'form_name'=>$result[$i]['form_name'],  //form id
                        'subject'=>$result[$i]['subject'],
                        'template'=>$result[$i]['template'],
                        'short_code'=>$result[$i]['short_code'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'status'=>$result[$i]['status'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_email_branch_template',$email_template_setting_data[$i]);
                }
            }
  /**********get data from hms_sms_branch_template***********/




                  /* hms_expense_print_setting */

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_print_expense_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $sms_setting_setting_count = count($result);
                for($i=0;$i<$sms_setting_setting_count;$i++){
                    $sms_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_print_expense_setting',$sms_setting_data[$i]);
                }
            }

                     /* hms_expense_print_setting */

                        /* hms_expense_print_setting */

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_discharge_medicine_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $discharge_setting_count = count($result);
                for($i=0;$i<$discharge_setting_count;$i++){
                    $discharge_setting[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );

                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_ipd_discharge_medicine_print_setting',$discharge_setting[$i]);
                }
            }

                     /* hms_expense_print_setting */
           

            //hms_payment_mode 
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_payment_mode');
            $result = $query->result_array();
            //echo "<pre>";print_r($result); exit;
            if(!empty($result)){
                $mode_setting_count = count($result);
                for($i=0;$i<$mode_setting_count;$i++){
                    $mode_setting1[$i] = array(
                        'branch_id'=>$branch_id,
                        'payment_mode'=>$result[$i]['payment_mode'],
                        'sort_order'=>$result[$i]['sort_order'],
                        
                        'status'=>$result[$i]['status'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );

                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_payment_mode',$mode_setting1[$i]);
                    $payment_mode_id =  $this->db->insert_id();

                   
                      $this->db->select('*');
                      $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                      $this->db->where('p_mode_id',$result[$i]['id']);
                      $query = $this->db->get('hms_payment_mode_to_field');
                      $result1 = $query->result_array();
                      //echo $this->db->last_query(); exit();
                      if(!empty($result1))
                      {
                          $mode_filed_setting_count = count($result1);
                          for($k=0;$k<$mode_filed_setting_count;$k++)
                          {
                              $mode_filed_setting[$k] = array(
                                  'branch_id'=>$branch_id,
                                  'p_mode_id'=>$payment_mode_id,
                                  'field_name'=>$result1[$k]['field_name'],
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'created_by'=>$result1[$k]['created_by'],
                                  'modified_date'=>$result1[$k]['modified_date'],
                                  'created_date'=>$result1[$k]['created_date']
                              );

                              /**********insert data into hms_branch_prescription_tab_setting***********/
                              $this->db->insert('hms_payment_mode_to_field',$mode_filed_setting[$k]);
                              //echo $this->db->last_query(); exit();
                              
                          }
                        }   

                    
                }
            }

            //menu inherit

            //hms_branch_menu 
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('parent_id','0');
            $query = $this->db->get('hms_branch_menu');
            $result = $query->result_array();
            //echo "<pre>";print_r($result); exit;
            if(!empty($result)){
                $mode_setting_count = count($result);
                for($i=0;$i<$mode_setting_count;$i++){
                    $mode_setting1[$i] = array(
                        'branch_id'=>$branch_id,
                        'parent_id'=>0,
                        'section_id'=>$result[$i]['section_id'],
                        'name'=>$result[$i]['name'],
                        'url'=>$result[$i]['url'],
                        'pop_up_id'=>$result[$i]['pop_up_id'],
                        'status'=>$result[$i]['status'],
                        'permission_status'=>$result[$i]['permission_status'],
                        'sort_order'=>$result[$i]['sort_order'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );

                    
                    $this->db->insert('hms_branch_menu',$mode_setting1[$i]);
                    $menu_p_id1 =  $this->db->insert_id();

                   
                      $this->db->select('*');
                      $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                      $this->db->where('parent_id',$result[$i]['id']);
                      $query = $this->db->get('hms_branch_menu');
                      $result1 = $query->result_array();
                      //echo $this->db->last_query(); exit();
                      if(!empty($result1))
                      {
                          $mode_filed_setting_count = count($result1);
                          for($k=0;$k<$mode_filed_setting_count;$k++)
                          {
                              $mode_filed_setting[$k] = array(
                                  'branch_id'=>$branch_id,
                                  'parent_id'=>$menu_p_id1,
                                  'section_id'=>$result1[$k]['section_id'],
                                  'name'=>$result1[$k]['name'],
                                  'url'=>$result1[$k]['url'],
                                  'pop_up_id'=>$result1[$k]['pop_up_id'],
                                  'status'=>$result1[$k]['status'],
                                  'permission_status'=>$result1[$k]['permission_status'],
                                  'sort_order'=>$result1[$k]['sort_order'],
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'created_by'=>$result1[$k]['created_by'],
                                  'modified_date'=>$result1[$k]['modified_date'],
                                  'created_date'=>$result1[$k]['created_date']
                              );

                              /**********insert data into hms_branch_prescription_tab_setting***********/
                              $this->db->insert('hms_branch_menu',$mode_filed_setting[$k]);
                              $menu_p_id2 =  $this->db->insert_id();
                              //echo $this->db->last_query(); exit();
                              
                          

                              $this->db->select('*');
                              $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                              $this->db->where('parent_id',$result1[$k]['id']);
                              $query = $this->db->get('hms_branch_menu');
                              $result2 = $query->result_array();
                              //echo $this->db->last_query(); exit();
                              if(!empty($result2))
                              {
                                  $mode_filed_setting_count2 = count($result2);
                                  for($m=0;$m<$mode_filed_setting_count2;$m++)
                                  {
                                      $mode_filed_setting2[$m] = array(
                                          'branch_id'=>$branch_id,
                                          'parent_id'=>$menu_p_id2,
                                          'section_id'=>$result2[$m]['section_id'],
                                          'name'=>$result2[$m]['name'],
                                          'url'=>$result2[$m]['url'],
                                          'pop_up_id'=>$result2[$m]['pop_up_id'],
                                          'status'=>$result2[$m]['status'],
                                          'permission_status'=>$result2[$m]['permission_status'],
                                          'sort_order'=>$result2[$m]['sort_order'],
                                          'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                          'created_by'=>$result2[$m]['created_by'],
                                          'modified_date'=>$result2[$m]['modified_date'],
                                          'created_date'=>$result2[$m]['created_date']
                                      );

                                      /**********insert data into hms_branch_prescription_tab_setting***********/
                                  $this->db->insert('hms_branch_menu',$mode_filed_setting2[$m]);
                                  $menu_p_id3 =  $this->db->insert_id();
                                      //echo $this->db->last_query(); exit();
                                  

                                  $this->db->select('*');
                                  $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                                  $this->db->where('parent_id',$result2[$m]['id']);
                                  $query = $this->db->get('hms_branch_menu');
                                  $result3 = $query->result_array();
                                  //echo $this->db->last_query(); exit();
                                  if(!empty($result3))
                                  {
                                      $mode_filed_setting_count3 = count($result3);
                                      for($n=0;$n<$mode_filed_setting_count3;$n++)
                                      {
                                          $mode_filed_setting3[$n] = array(
                                              'branch_id'=>$branch_id,
                                              'parent_id'=>$menu_p_id3,
                                              'section_id'=>$result3[$n]['section_id'],
                                              'name'=>$result3[$n]['name'],
                                              'url'=>$result3[$n]['url'],
                                              'pop_up_id'=>$result3[$n]['pop_up_id'],
                                              'status'=>$result3[$n]['status'],
                                              'permission_status'=>$result3[$n]['permission_status'],
                                              'sort_order'=>$result3[$n]['sort_order'],
                                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                              'created_by'=>$result3[$n]['created_by'],
                                              'modified_date'=>$result3[$n]['modified_date'],
                                              'created_date'=>$result3[$n]['created_date']
                                          );

                                          /**********insert data into hms_branch_prescription_tab_setting***********/
                                  $this->db->insert('hms_branch_menu',$mode_filed_setting3[$n]);
                                  $menu_p_id4 =  $this->db->insert_id();
                                  
                                  
                                  
                                  ////final level ///

                                  $this->db->select('*');
                                  $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                                  $this->db->where('parent_id',$result3[$n]['id']);
                                  $query = $this->db->get('hms_branch_menu');
                                  $result4 = $query->result_array();
                                  //echo $this->db->last_query(); exit();
                                  if(!empty($result4))
                                  {
                                      $mode_filed_setting_count4 = count($result4);
                                      for($z=0;$z<$mode_filed_setting_count4;$z++)
                                      {
                                          $mode_filed_setting4[$z] = array(
                                              'branch_id'=>$branch_id,
                                              'parent_id'=>$menu_p_id4,
                                              'section_id'=>$result4[$z]['section_id'],
                                              'name'=>$result4[$z]['name'],
                                              'url'=>$result4[$z]['url'],
                                              'pop_up_id'=>$result4[$z]['pop_up_id'],
                                              'status'=>$result4[$z]['status'],
                                              'permission_status'=>$result4[$z]['permission_status'],
                                              'sort_order'=>$result4[$z]['sort_order'],
                                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                              'created_by'=>$result4[$z]['created_by'],
                                              'modified_date'=>$result4[$z]['modified_date'],
                                              'created_date'=>$result4[$z]['created_date']
                                          );

                                          /**********insert data into hms_branch_prescription_tab_setting***********/
                                  $this->db->insert('hms_branch_menu',$mode_filed_setting4[$z]);
                                  $menu_p_id5 =  $this->db->insert_id();
                                        
                                          
                                      }
                                    }

                                  //final end///
                                        
                                          
                                      }
                                    }


                                  }
                                } 
                          }
                        }   

                    
                }
            }

            //menu inherit




           /* inherit discharge print seeting */
             $this->db->select('*');
                        $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                     
                        $query = $this->db->get('hms_ipd_branch_discharge_labels_setting');
                        $result = $query->result_array();
                        if(!empty($result)){
                            $discharge_print = count($result);
                            for($i=0;$i<$discharge_print;$i++){
                                $discharge_setting_data[$i] = array(
                                    'branch_id'=>$branch_id,
                                    'unique_id'=>$result[$i]['unique_id'],
                                    'gipsa_type'=>$result[$i]['gipsa_type'],
                                    'setting_value'=>$result[$i]['setting_value'],
                                    'type'=>$result[$i]['type'],
                                    'setting_name'=>$result[$i]['setting_name'],
                                    'order_by'=>$result[$i]['order_by'],
                                    'status'=>$result[$i]['status'],
                                    'print_status'=>$result[$i]['print_status'],
                                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                    'created_by'=>$result[$i]['created_by'],
                                    'modified_date'=>$result[$i]['modified_date'],
                                    'created_date'=>$result[$i]['created_date']
                                );
                                /**********insert data into hms_branch_prescription_tab_setting***********/
                                $this->db->insert('hms_ipd_branch_discharge_labels_setting',$discharge_setting_data[$i]);
                            }
                        }

                      $this->db->select('*');
                      $this->db->where('branch_id IN('.$users_data['parent_id'].')');

                      $query = $this->db->get('hms_ipd_branch_vital_labels_setting');
                      $result = $query->result_array();
                      if(!empty($result)){
                          $discharge_print_vital = count($result);
                          for($i=0;$i<$discharge_print_vital;$i++){
                          $vital_setting_data[$i] = array(
                            'branch_id'=>$branch_id,
                            'unique_id'=>$result[$i]['unique_id'],
                            'setting_value'=>$result[$i]['setting_value'],
                            'vital_type'=>$result[$i]['vital_type'],
                            'type'=>$result[$i]['type'],
                            'setting_name'=>$result[$i]['setting_name'],
                            'order_by'=>$result[$i]['order_by'],
                            'status'=>$result[$i]['status'],
                            'print_status'=>$result[$i]['print_status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'created_by'=>$result[$i]['created_by'],
                            'modified_date'=>$result[$i]['modified_date'],
                            'created_date'=>$result[$i]['created_date']
                          );
                          /**********insert data into hms_branch_prescription_tab_setting***********/
                          $this->db->insert('hms_ipd_branch_vital_labels_setting',$vital_setting_data[$i]);
                          }
                      }
           /* inherit discharge print seeting */
           
           
                  /**********get data from hms_branch_prescription_tab_setting***********/      
            
         

           /**********get data from hms_ipd_branch_progress_report_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_branch_progress_report_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $branch_progress_report_setting_count = count($result);
                for($i=0;$i<$branch_progress_report_setting_count;$i++){
                    $progress_report_setting_data[$i] = array(
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
                    $this->db->insert('hms_ipd_branch_progress_report_tab_setting',$progress_report_setting_data[$i]);
                }
            }
                  /**********get data from hms_branch_prescription_tab_setting***********/



            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_branch_admission_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $admission_print_setting_count = count($result);
                for($i=0;$i<$admission_print_setting_count;$i++){
                    $admission_print_setting_data[$i] = array(
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
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_ipd_branch_admission_print_setting',$admission_print_setting_data[$i]);
                }
            }



            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_branch_running_bill_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $running_bill_setting_count = count($result);
                for($i=0;$i<$running_bill_setting_count;$i++){
                    $running_bill_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_ipd_branch_running_bill_print_setting',$running_bill_setting_data[$i]);
                }
            }

            


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_branch_discharge_bill_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $discharge_bill_setting_count = count($result);
                for($i=0;$i<$discharge_bill_setting_count;$i++){
                    $discharge_bill_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_ipd_branch_discharge_bill_print_setting',$discharge_bill_setting_data[$i]);
                }
            }


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_ot_detail_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $discharge_bill_setting_count = count($result);
                for($i=0;$i<$discharge_bill_setting_count;$i++){
                    $ot_detail_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_ot_detail_print_setting',$ot_detail_setting_data[$i]);
                }
            }

            //hms_branch_barcode inherit
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_barcode');
            $result = $query->result_array();
            if(!empty($result)){
                $barcode_setting_count = count($result);
                for($i=0;$i<$barcode_setting_count;$i++){
                    $barcode_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'total_receipt'=>$result[$i]['total_receipt'],
                        'type'=>$result[$i]['type'],
                        'size'=>$result[$i]['size'],

                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    
                    $this->db->insert('hms_branch_barcode',$barcode_setting_data[$i]);
                }
            } 

            //hms_branch_barcode inherit


            //inherit no opd validity days

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_opd_validity_setting');
            $result = $query->result_array();
            if(!empty($result)){
                 $days_count = count($result);
                 for($i=0;$i<$days_count;$i++){
                      $days_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'days'=>$result[$i]['days'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into religion***********/

                         $this->db->insert('hms_opd_validity_setting',$days_data[$i]);
                 }
            }

            //end validity days

            // inherit token no 

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query = $this->db->get('hms_token_setting');
            $result = $query->result_array();
            if(!empty($result)){
                 $token_count = count($result);
                 for($i=0;$i<$token_count;$i++){
                      $token_data[$i] = array(
                              'branch_id'=>$branch_id,
                              'type'=>$result[$i]['type'],
                              'created_date'=>$result[$i]['created_date']
                        );
                         /**********insert data into religion***********/

                         $this->db->insert('hms_token_setting',$token_data[$i]);
                 }
            }

            //end of token no


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_progress_note_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $progress_note_setting_count = count($result);
                for($i=0;$i<$progress_note_setting_count;$i++){
                    $progress_note_setting_data[$i] = array(
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
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_progress_note_print_setting',$progress_note_setting_data[$i]);
                }
            }

                  /**********get data from hms_ipd_branch_progress_report_tab_setting***********/

            //Section-16


            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_discharge_summary_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $branch_discharge_summary_setting_count = count($result);
                for($i=0;$i<$branch_discharge_summary_setting_count;$i++){
                    $discharge_summary_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'type'=>$result[$i]['type'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_discharge_summary_setting',$discharge_summary_setting_data[$i]);
                }
            }
                  /**********get data from hms_branch_prescription_tab_setting***********/

                  //hms_opd_vitals 
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_opd_vitals');
            $result = $query->result_array();
            if(!empty($result))
            {
                 $vitals_count = count($result);
                 for($i=0;$i<$vitals_count;$i++)
                 {
                      $vitals_data[$i] = array(
								'branch_id'=>$branch_id,
								'vitals_name'=>$result[$i]['vitals_name'],
								'vitals_unit'=>$result[$i]['vitals_unit'],
								'sort_order'=>$result[$i]['sort_order'],
								'status'=>$result[$i]['status'],
								'ip_address'=>$_SERVER['REMOTE_ADDR'],
								'created_by'=>$result[$i]['created_by'],
								'modified_by'=>$result[$i]['modified_by'],
								'is_deleted'=>$result[$i]['is_deleted'],
								'deleted_date'=>$result[$i]['deleted_date'],
								'status'=>$result[$i]['status'],
								'created_date'=>$result[$i]['created_date']
                        );
                        /********insert data into expense category********/
						$this->db->insert('hms_opd_vitals',$vitals_data[$i]);
                 }
            }

         }


    

         $this->db->select('*');
            $this->db->where('branch_id','0');
            $query = $this->db->get('path_test_report_print_setting');
            $result = $query->result_array();
            if(empty($result)){
                $result=array();
            }
            if(!empty($result)){
                $test_report_setting_count = count($result);
                for($i=0;$i<$test_report_setting_count;$i++){
                    $test_report_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'page_header'=>$result[$i]['page_header'],
                        'page_details'=>$result[$i]['page_details'],
                        'page_middle'=>$result[$i]['page_middle'],
                        'page_footer'=>$result[$i]['page_footer'],
                        'header_print'=>$result[$i]['header_print'],
                        'header_pdf'=>$result[$i]['header_pdf'],
                        'details_print'=>$result[$i]['details_print'],
                        'details_pdf'=>$result[$i]['details_pdf'],
                        'middle_print'=>$result[$i]['middle_print'],
                        'middle_pdf'=>$result[$i]['middle_pdf'],
                        'footer_print'=>$result[$i]['footer_print'],
                        'footer_pdf'=>$result[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$users_data['id'],
                        'created_date'=>date('Y-m-d H:i:s')
                    );
                    /**********insert data into path_print_branch_template***********/
                    $this->db->insert('path_test_report_print_setting',$test_report_setting_data[$i]);
                }
            }

             $this->db->select('*');
            $this->db->where('branch_id','0');
            $query = $this->db->get('hms_doctor_certificate');
            $result = $query->result_array();
            if(empty($result)){
                $result=array();
            }
            if(!empty($result)){
                $certificate_count = count($result);
                for($i=0;$i<$certificate_count;$i++){
                    $certificate_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'title'=>$result[$i]['title'],
                        'template'=>$result[$i]['template'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$users_data['id'],
                        'created_date'=>date('Y-m-d H:i:s')
                    );
                    /**********insert data into path_print_branch_template***********/
                    $this->db->insert('hms_doctor_certificate',$certificate_data[$i]);
                }
            }
       


             // added on 05-02-2018 for inherit test home collection
          /********************get data from test home collection*********************/
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_test_home_collection');
            $result = $query->result_array();
            if(!empty($result))
            {
              $collection_ct = count($result);
              for($i=0;$i<$collection_ct;$i++)
              {
                $collection_data[$i] = array(
                                              'branch_id'=>$branch_id,
                                              'charge'=>$result[$i]['charge'],
                                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                              'created_by'=>$result[$i]['created_by'],
                                              'modified_by'=>$result[$i]['modified_by'],
                                              'is_deleted'=>$result[$i]['is_deleted'],
                                              'status'=>$result[$i]['status'],
                                              'created_date'=>$result[$i]['created_date']
                                            );
                /******************insert data into test home collection table***************/
                $this->db->insert('hms_test_home_collection',$collection_data[$i]);
              }
            }
          // added on 05-02-2018 for inherit test home collection 
       /* get data from collection tab setting */
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_collection_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $collection_tab_setting_count = count($result);
                for($i=0;$i<$collection_tab_setting_count;$i++){
                    $collection_tab_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'var_title'=>$result[$i]['var_title'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'order_by'=>$result[$i]['order_by'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'print_status'=>$result[$i]['print_status'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_collection_tab_setting',$collection_tab_setting_data[$i]);
                }
            }
          /**********get data from collection tab setting***********/

   //ipd prescription tab setting

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
           
            $query = $this->db->get('hms_ipd_branch_prescription_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $ipd_prescription_setting_count = count($result);
                for($i=0;$i<$ipd_prescription_setting_count;$i++){
                    $ipd_prescription_setting_data[$i] = array(
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
                    /**********insert data into hms_ipd_branch_prescription_setting***********/
                    $this->db->insert('hms_ipd_branch_prescription_setting',$ipd_prescription_setting_data[$i]);
                }
            }
             

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_branch_prescription_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $ipd_prescription_tab_setting_count = count($result);
                for($i=0;$i<$ipd_prescription_tab_setting_count;$i++){
                    $ipd_prescription_tab_setting_data[$i] = array(
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
                    $this->db->insert('hms_ipd_branch_prescription_tab_setting',$ipd_prescription_tab_setting_data[$i]);
                }
            }
                 

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('type','0');
            $query = $this->db->get('hms_ipd_branch_prescription_medicine_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $ipd_prescription_tab_medicine_setting_count = count($result);
                for($i=0;$i<$ipd_prescription_tab_medicine_setting_count;$i++){
                    $ipd_prescription_tab_medicine_setting_data[$i] = array(
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
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_ipd_branch_prescription_medicine_tab_setting',$ipd_prescription_tab_medicine_setting_data[$i]);
                }
            }
          
          //ipd prescription tab setting end
          
          
           //  op 02/08/2019

            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_ipd_branch_nabh_print_form');
            $result = $query->result_array();
            if(!empty($result)){
                $ipd_nabh_form_count = count($result);
                for($i=0;$i<$ipd_nabh_form_count;$i++){
                    $ipd_nabh_form_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'form_type'=>$result[$i]['form_type'],
                        'form_name'=>$result[$i]['form_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_ipd_branch_nabh_print_form',$ipd_nabh_form_print[$i]);
                }
            }
            
            
            
            //letter head inherit data
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_print_letter_head_template_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $letter_head_template_setting_count = count($result);
                for($i=0;$i<$letter_head_template_setting_count;$i++){
                    $letter_head_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'page_header'=>$result[$i]['page_header'],

                        'pixel_value'=>$result[$i]['pixel_value'],
                        'page_details'=>$result[$i]['page_details'],
                        'page_middle'=>$result[$i]['page_middle'],
                        'page_footer'=>$result[$i]['page_footer'],
                        'header_print'=>$result[$i]['header_print'],
                        'header_pdf'=>$result[$i]['header_pdf'],
                        'details_print'=>$result[$i]['details_print'],
                        'details_pdf'=>$result[$i]['details_pdf'],
                        'middle_print'=>$result[$i]['middle_print'],
                        'middle_pdf'=>$result[$i]['middle_pdf'],


                        'footer_print'=>$result[$i]['footer_print'],
                        'footer_pdf'=>$result[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_print_letter_head_template_setting',$letter_head_print[$i]);
                }
            }
            //letter head inherit data
            
            
            //commission print setting 1 july 2021
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_billing_doctor_commission_letterhead_setting');
            $result_commission = $query->result_array();
            if(!empty($result_commission)){
                $letter_head_template_setting_count = count($result_commission);
                for($i=0;$i<$letter_head_template_setting_count;$i++){
                    $letter_head_commis_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result_commission[$i]['unique_id'],
                        'setting_name'=>$result_commission[$i]['setting_name'],
                        'setting_value'=>$result_commission[$i]['setting_value'],
                        'page_header'=>$result_commission[$i]['page_header'],

                        'pixel_value'=>$result_commission[$i]['pixel_value'],
                        //'page_details'=>$result_commission[$i]['page_details'],
                        'page_middle'=>$result_commission[$i]['page_middle'],
                        'page_footer'=>$result_commission[$i]['page_footer'],
                        'header_print'=>$result_commission[$i]['header_print'],
                        'header_pdf'=>$result_commission[$i]['header_pdf'],
                        'details_print'=>$result_commission[$i]['details_print'],
                        'details_pdf'=>$result_commission[$i]['details_pdf'],
                        'middle_print'=>$result_commission[$i]['middle_print'],
                        'middle_pdf'=>$result_commission[$i]['middle_pdf'],


                        'footer_print'=>$result_commission[$i]['footer_print'],
                        'footer_pdf'=>$result_commission[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result_commission[$i]['created_by'],
                        'modified_date'=>$result_commission[$i]['modified_date'],
                        'created_date'=>$result_commission[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_billing_doctor_commission_letterhead_setting',$letter_head_commis_print[$i]);
                }
            }
            
            
            //end of commission print setting
            
            
            //ot summary print setting
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_ot_summary_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $summary_print_template_setting_count = count($result);
                for($i=0;$i<$summary_print_template_setting_count;$i++){
                    $summaryletter_head_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'short_codes '=>$result[$i]['short_codes '],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_ot_summary_print_setting',$summaryletter_head_print[$i]);
                }
            }

           //ot summary print setting

            //discharge bill print setting with letter head
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_ipd_discharge_bill_print_letter_head_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $discharge_bill_letter_template_setting_count = count($result);
                for($i=0;$i<$discharge_bill_letter_template_setting_count;$i++){
                    $discharge_letter_head_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'page_header'=>$result[$i]['page_header'],

                        'pixel_value'=>$result[$i]['pixel_value'],
                        'page_details'=>$result[$i]['page_details'],
                        'page_middle'=>$result[$i]['page_middle'],
                        'page_footer'=>$result[$i]['page_footer'],
                        'header_print'=>$result[$i]['header_print'],
                        'header_pdf'=>$result[$i]['header_pdf'],
                        'details_print'=>$result[$i]['details_print'],
                        'details_pdf'=>$result[$i]['details_pdf'],
                        'middle_print'=>$result[$i]['middle_print'],
                        'middle_pdf'=>$result[$i]['middle_pdf'],


                        'footer_print'=>$result[$i]['footer_print'],
                        'footer_pdf'=>$result[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_ipd_discharge_bill_print_letter_head_setting',$discharge_letter_head_print[$i]);
                }
            }

           //discharge bill print setting with letter head

            //discharge bill print setting with letter head
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_ipd_discharge_report_print_letter_head_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $discharge_bill_letter_template_setting_count = count($result);
                for($i=0;$i<$discharge_bill_letter_template_setting_count;$i++){
                    $discharge_letter_head_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'page_header'=>$result[$i]['page_header'],

                        'pixel_value'=>$result[$i]['pixel_value'],
                        'page_details'=>$result[$i]['page_details'],
                        'page_middle'=>$result[$i]['page_middle'],
                        'page_footer'=>$result[$i]['page_footer'],
                        'header_print'=>$result[$i]['header_print'],
                        'header_pdf'=>$result[$i]['header_pdf'],
                        'details_print'=>$result[$i]['details_print'],
                        'details_pdf'=>$result[$i]['details_pdf'],
                        'middle_print'=>$result[$i]['middle_print'],
                        'middle_pdf'=>$result[$i]['middle_pdf'],


                        'footer_print'=>$result[$i]['footer_print'],
                        'footer_pdf'=>$result[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_ipd_discharge_report_print_letter_head_setting',$discharge_letter_head_print[$i]);
                }
            }

           //discharge report bill print setting with letter head

            //discharge bill print setting with letter head
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_ipd_running_bill_print_letterhead_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $running_bill_letter_template_setting_count = count($result);
                for($i=0;$i<$running_bill_letter_template_setting_count;$i++){
                    $running_letter_head_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'page_header'=>$result[$i]['page_header'],

                        'pixel_value'=>$result[$i]['pixel_value'],
                        'page_details'=>$result[$i]['page_details'],
                        'page_middle'=>$result[$i]['page_middle'],
                        'page_footer'=>$result[$i]['page_footer'],
                        'header_print'=>$result[$i]['header_print'],
                        'header_pdf'=>$result[$i]['header_pdf'],
                        'details_print'=>$result[$i]['details_print'],
                        'details_pdf'=>$result[$i]['details_pdf'],
                        'middle_print'=>$result[$i]['middle_print'],
                        'middle_pdf'=>$result[$i]['middle_pdf'],


                        'footer_print'=>$result[$i]['footer_print'],
                        'footer_pdf'=>$result[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_ipd_branch_nabh_print_form***********/
                    $this->db->insert('hms_ipd_running_bill_print_letterhead_setting',$running_letter_head_print[$i]);
                }
            }

           //running report bill print setting with letter head
           
           
           //transfusion print inherit
           
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query_trans = $this->db->get('hms_print_transfusion_template');
            $result_trans = $query_trans->result_array();
            if(!empty($result_trans)){
                $print_branch_template_count = count($result_trans);
                for($i=0;$i<$print_branch_template_count;$i++){
                    
            $print_trans_template_data[$i] = array(
                                'branch_id'=>$branch_id,
                                'printer_id'=>$result[$i]['printer_id'],
                                'printer_paper_type'=>$result[$i]['printer_paper_type'],
                                'types'=>$result[$i]['types'],
                                'section_id'=>$result[$i]['section_id'],
                                'template'=>$result[$i]['template'],
                                'header_content'=>$result[$i]['header_content'],
                                'short_code'=>$result[$i]['short_code'],
                                'sub_section'=>$result[$i]['sub_section'],                        
                                'created_by'=>$result[$i]['created_by'],
                                'modified_by'=>$result[$i]['modified_by'],
                                'modified_date'=>$result[$i]['modified_date'],
                                'created_date'=>$result[$i]['created_date']
                            );
                    /**********insert data into hms_print_branch_template***********/
                    $this->db->insert('hms_print_transfusion_template',$print_trans_template_data[$i]);
                }
            }
           
           //end of transfusion print
           
           //compatibility print 
           $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query_compat = $this->db->get('hms_print_compatilbilty_template');
            $result_comp = $query_compat->result_array();
            if(!empty($result_comp)){
                $print_comp_template_count = count($result_comp);
                for($i=0;$i<$print_comp_template_count;$i++){
                    
            $print_comp_template_data[$i] = array(
                                'branch_id'=>$branch_id,
                                'printer_id'=>$result[$i]['printer_id'],
                                'printer_paper_type'=>$result[$i]['printer_paper_type'],
                                'types'=>$result[$i]['types'],
                                'section_id'=>$result[$i]['section_id'],
                                'template'=>$result[$i]['template'],
                                'header_content'=>$result[$i]['header_content'],
                                'short_code'=>$result[$i]['short_code'],
                                'sub_section'=>$result[$i]['sub_section'],                        
                                'created_by'=>$result[$i]['created_by'],
                                'modified_by'=>$result[$i]['modified_by'],
                                'modified_date'=>$result[$i]['modified_date'],
                                'created_date'=>$result[$i]['created_date']
                            );
                    /**********insert data into hms_print_branch_template***********/
                    $this->db->insert('hms_print_compatilbilty_template',$print_comp_template_data[$i]);
                }
            }
           
           
           //end of compatibility
           
           //Blood Bank setting
           
           $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('is_deleted','0');
            $query = $this->db->get('hms_blood_bank_setting');
            $result_blood = $query->result_array();
            if(!empty($result_blood)){
                $blood_setting_count = count($result_blood);
                for($i=0;$i<$blood_setting_count;$i++){
                    $blood_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'var_name'=>$result[$i]['var_name'],
                        'var_title'=>$result[$i]['var_title'],
                        'setting_value1'=>$result[$i]['setting_value1'],
                        'setting_value2'=>$result[$i]['setting_value2'],
                        'type'=>$result[$i]['type'],
                        'status'=>$result[$i]['status'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'is_deleted'=>$result[$i]['is_deleted'],
                        'deleted_date'=>$result[$i]['deleted_date'],
                        
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into website_setting***********/
                    $this->db->insert('hms_blood_bank_setting',$blood_setting_data[$i]);
                }
            }
            
           //End of blood bank setting
           
           
           //sub_section
           $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            
            $query = $this->db->get('hms_bank_report_branch_template');
            $result_report = $query->result_array();
            if(!empty($result_report)){
                $print_report_template_count = count($result_report);
                for($i=0;$i<$print_report_template_count;$i++){
                    $print_report_template_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'printer_id'=>$result[$i]['printer_id'],
                        'component_id'=>$result[$i]['component_id'],
                        'sub_section'=>$result[$i]['sub_section'],
                        'printer_paper_type'=>$result[$i]['printer_paper_type'],
                        'types'=>$result[$i]['types'],
                        'section_id'=>$result[$i]['section_id'],
                        'template'=>$result[$i]['template'],
                        'short_code'=>$result[$i]['short_code'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_by'=>$result[$i]['modified_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_print_branch_template***********/
                    $this->db->insert('hms_bank_report_branch_template',$print_report_template_data[$i]);
                }
            }
            
            //inherit dialysis prescription
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
           
            $query = $this->db->get('hms_branch_dialysis_prescription_setting');
            $result_dialysis = $query->result_array();
            if(!empty($result_dialysis)){
                $prescription_setting_count = count($result_dialysis);
                for($i=0;$i<$prescription_setting_count;$i++){
                    $prescription_dia_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'type'=>$result_dialysis[$i]['type'],
                        'unique_id'=>$result_dialysis[$i]['unique_id'],
                        'setting_name'=>$result_dialysis[$i]['setting_name'],
                        'setting_value'=>$result_dialysis[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result_dialysis[$i]['created_by'],
                        'modified_date'=>$result_dialysis[$i]['modified_date'],
                        
                        'created_date'=>$result_dialysis[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_setting***********/
                    $this->db->insert('hms_branch_dialysis_prescription_setting',$prescription_dia_setting_data[$i]);
                }
            }
            
            
            //dialysis admission
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_dialysis_branch_admission_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $dia_admission_print_setting_count = count($result);
                for($i=0;$i<$dia_admission_print_setting_count;$i++){
                    $dia_admission_print_setting_data[$i] = array(
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
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_dialysis_branch_admission_print_setting',$dia_admission_print_setting_data[$i]);
                }
            }
            
            //day care inherit 
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_branch_daycare_discharge_summary_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $branch_daycare_summary_setting_count = count($result);
                for($i=0;$i<$branch_daycare_summary_setting_count;$i++){
                    $day_discharge_summary_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'type'=>$result[$i]['type'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_branch_daycare_discharge_summary_setting',$day_discharge_summary_setting_data[$i]);
                }
            }
            
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $query = $this->db->get('hms_daycare_discharge_report_print_letter_head_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $daycare_discharge_bill_letter_template_setting_count = count($result);
                for($i=0;$i<$daycare_discharge_bill_letter_template_setting_count;$i++){
                    $day_discharge_letter_head_print[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'page_header'=>$result[$i]['page_header'],

                        'pixel_value'=>$result[$i]['pixel_value'],
                        'page_details'=>$result[$i]['page_details'],
                        'page_middle'=>$result[$i]['page_middle'],
                        'page_footer'=>$result[$i]['page_footer'],
                        'header_print'=>$result[$i]['header_print'],
                        'header_pdf'=>$result[$i]['header_pdf'],
                        'details_print'=>$result[$i]['details_print'],
                        'details_pdf'=>$result[$i]['details_pdf'],
                        'middle_print'=>$result[$i]['middle_print'],
                        'middle_pdf'=>$result[$i]['middle_pdf'],


                        'footer_print'=>$result[$i]['footer_print'],
                        'footer_pdf'=>$result[$i]['footer_pdf'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    
                    $this->db->insert('hms_daycare_discharge_report_print_letter_head_setting',$day_discharge_letter_head_print[$i]);
                }
            }
            
             $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_daycare_branch_discharge_labels_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $daycare_discharge_print = count($result);
                for($i=0;$i<$daycare_discharge_print;$i++){
                    $daycare_discharge_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'gipsa_type'=>$result[$i]['gipsa_type'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'type'=>$result[$i]['type'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'order_by'=>$result[$i]['order_by'],
                        'status'=>$result[$i]['status'],
                        'print_status'=>$result[$i]['print_status'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_daycare_branch_discharge_labels_setting',$daycare_discharge_setting_data[$i]);
                }
            }
            
             $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('type','0');
            $query = $this->db->get('hms_daycare_branch_summary_medicine_tab_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $daycare_tab_medicine_setting_count = count($result);
                for($i=0;$i<$daycare_tab_medicine_setting_count;$i++){
                    $daycare_tab_medicine_setting_data[$i] = array(
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
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_daycare_branch_summary_medicine_tab_setting',$daycare_tab_medicine_setting_data[$i]);
                }
            }
            
            $this->db->select('*');
                      $this->db->where('branch_id IN('.$users_data['parent_id'].')');

                      $query = $this->db->get('hms_daycare_branch_vital_labels_setting');
                      $result = $query->result_array();
                      if(!empty($result)){
                          $daycare_discharge_print_vital = count($result);
                          for($i=0;$i<$daycare_discharge_print_vital;$i++){
                          $daycare_vital_setting_data[$i] = array(
                            'branch_id'=>$branch_id,
                            'unique_id'=>$result[$i]['unique_id'],
                            'setting_value'=>$result[$i]['setting_value'],
                            'vital_type'=>$result[$i]['vital_type'],
                            'type'=>$result[$i]['type'],
                            'setting_name'=>$result[$i]['setting_name'],
                            'order_by'=>$result[$i]['order_by'],
                            'status'=>$result[$i]['status'],
                            'print_status'=>$result[$i]['print_status'],
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'created_by'=>$result[$i]['created_by'],
                            'modified_date'=>$result[$i]['modified_date'],
                            'created_date'=>$result[$i]['created_date']
                          );
                          /**********insert data into hms_branch_prescription_tab_setting***********/
                          $this->db->insert('hms_daycare_branch_vital_labels_setting',$daycare_vital_setting_data[$i]);
                          }
                      }
                      
            $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_daycare_discharge_medicine_print_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $daycare_discharge_setting_count = count($result);
                for($i=0;$i<$daycare_discharge_setting_count;$i++){
                    $daycare_discharge_setting[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );

                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_daycare_discharge_medicine_print_setting',$daycare_discharge_setting[$i]);
                }
            }
            
             $this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
         
            $query = $this->db->get('hms_ipd_advance_branch_discharge_labels_setting');
            $result = $query->result_array();
            if(!empty($result)){
                $adavnce_discharge_print = count($result);
                for($i=0;$i<$adavnce_discharge_print;$i++){
                    $advance_discharge_setting_data[$i] = array(
                        'branch_id'=>$branch_id,
                        'unique_id'=>$result[$i]['unique_id'],
                        'gipsa_type'=>$result[$i]['gipsa_type'],
                        'setting_value'=>$result[$i]['setting_value'],
                        'type'=>$result[$i]['type'],
                        'setting_name'=>$result[$i]['setting_name'],
                        'order_by'=>$result[$i]['order_by'],
                        'status'=>$result[$i]['status'],
                        'print_status'=>$result[$i]['print_status'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );
                    /**********insert data into hms_branch_prescription_tab_setting***********/
                    $this->db->insert('hms_ipd_advance_branch_discharge_labels_setting',$advance_discharge_setting_data[$i]);
                }
            }
            
            
           //end of day care inherit
         
// Please write code above       
	}
}

?>