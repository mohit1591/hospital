<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_patient_summary_model extends CI_Model {

    var $table = 'hms_dialysis_patient_summery';
    var $column = array('hms_dialysis_patient_summery.id','hms_patient.patient_name','hms_patient.patient_code','hms_patient.mobile_no','hms_dialysis_patient_summery.patient_id', 'hms_dialysis_patient_summery.status','hms_dialysis_patient_summery.created_date');   
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $search = $this->session->userdata('dialysis_summary_serach');
        //echo "<pre>"; print_r($search); exit;
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $this->db->select("hms_dialysis_patient_summery.*,hms_dialysis_booking.booking_code,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code, hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_patient.address");

        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_patient_summery.dialysis_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_booking.patient_id','left');
        $this->db->from($this->table); 
        $this->db->where('hms_dialysis_patient_summery.is_deleted','0');
        $this->db->where('hms_dialysis_patient_summery.branch_id',$users_data['parent_id']);
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }

        elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }
        
        if(!empty($search['patient_id']))
        {
            $this->db->where('hms_dialysis_patient_summery.patient_id',$search['patient_id']);
        }
        if(!empty($search['dialysis_id']))
        {
            $this->db->where('hms_dialysis_patient_summery.dialysis_id',$search['dialysis_id']);
        }
        if(!empty($search['patient_name']))
        {
            $this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
        }

        if(!empty($search['patient_code']))
        {
            $this->db->where('hms_patient.patient_code',$search['patient_code']);
        }

        if(!empty($search['mobile_no']))
        {
            $this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
        }
        
        if(!empty($search['dialysis_no']))
        {
            $this->db->where('hms_dialysis_booking.booking_code LIKE "'.$search['dialysis_no'].'%"');
        }
        
        if(!empty($search['patient_id']))
        {
            $this->db->where('hms_dialysis_patient_summery.patient_id',$search['patient_id']);
        }
        
        if(!empty($search['dialysis_id']))
        {
            $this->db->where('hms_dialysis_patient_summery.dialysis_id',$search['dialysis_id']);
        }
        
        
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_dialysis_patient_summery.created_by IN ('.$emp_ids.')');
        }
        $i = 0;
        foreach ($this->column as $item) // loop column 
        {
            if($_POST['search']['value']) // if datatable send POST for search
            {
                
                if($i===0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND. 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if(count($this->column) - 1 == $i) //last loop+
                    $this->db->group_end(); //close bracket
            }
            $column[$i] = $item; // set column array variable to order processing
            $i++;
        }
        
        if(isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    public function simulation_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('simulation','ASC'); 
        $query = $this->db->get('hms_discharge_summery');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('hms_dialysis_patient_summery.*');
        $this->db->from('hms_dialysis_patient_summery'); 
        $this->db->where('hms_dialysis_patient_summery.id',$id);
        $this->db->where('hms_dialysis_patient_summery.is_deleted','0');
        $query = $this->db->get(); 
        //echo $this->db->last_query(); exit;
        return $query->row_array();
    }
    
    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'patient_id'=>$post['patient_id'],
                    'dialysis_id'=>$post['dialysis_id'],
                    'summery_type'=>$post['summery_type'],
                    'chief_complaints'=>$post['chief_complaints'],
                    'h_o_presenting'=>$post['h_o_presenting'],
                    'on_examination'=>$post['on_examination'],
                    'vitals_pulse'=>$post['vitals_pulse'],
                    'vitals_chest'=>$post['vitals_chest'],
                    'vitals_bp'=>$post['vitals_bp'],
                    'vitals_cvs'=>$post['vitals_cvs'],
                    'vitals_temp'=>$post['vitals_temp'],
                    'vitals_p_a'=>$post['vitals_p_a'],
                    'vitals_cns'=>$post['vitals_cns'],
                    'provisional_diagnosis'=>$post['provisional_diagnosis'],
                    'final_diagnosis'=>$post['final_diagnosis'],
                    'course_in_hospital'=>$post['course_in_hospital'],
                    'investigations'=>$post['investigations'],
                    'discharge_time_condition'=>$post['discharge_time_condition'],
                    'discharge_advice'=>$post['discharge_advice'],
                    
                    'treatment_given'=>$post['treatment_given'],
                    'operation_notes'=>$post['operation_notes'],
                    'surgery_preferred'=>$post['surgery_preferred'],
                    'past_history'=>$post['past_history'],
                    'menstrual_history'=>$post['menstrual_history'],
                    'obstetric_history'=>$post['obstetric_history'],
                    'summary_date'=>date('Y-m-d',strtotime($post['summary_date'])),
                    
                    
                    'review_time_date'=>date('Y-m-d',strtotime($post['review_time_date'])),
                    'review_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['review_time'])),
                    'death_date'=>date('Y-m-d',strtotime($post['death_date'])),
                    'death_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['death_time'])),
                    'status'=>$post['status'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_dialysis_patient_summery',$data);
            $discharge_id = $post['data_id'];  
            
            /////////////////////////////////////////////////////////////////////////////////////
            /*add discharge master data*/
            $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$discharge_id));
            $this->db->delete('hms_patient_dialysis_field_value');
            if(!empty($post['field_name']))
            {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_field_value= array(
                        'field_value'=>$post['field_name'][$i],
                        'field_id'=>$post['field_id'][$i],
                        'branch_id'=>$user_data['parent_id'],
                        'parent_id'=>$discharge_id,
                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                        );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_patient_dialysis_field_value',$data_field_value);

                }
            }
            
            ////////////////////////////////////////////////////////
            
            /*************** Medicine Detail ****************/
            $this->db->where(array('dialysis_id'=>$post['dialysis_id'],'discharge_summary_id'=>$post['data_id']));
            $this->db->delete('hms_dialysis_patient_summery_medicine');
            if($post['medicine_name'][0]!="")
            {
                $post_medicine_name= $post['medicine_name'];

                $counter_name= count($post_medicine_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_medicine= array(
                        'medicine_name'=>$post['medicine_name'][$i],
                        'medicine_dose'=>$post['medicine_dose'][$i],
                        'medicine_duration'=>$post['medicine_duration'][$i],
                        'medicine_frequency'=>$post['medicine_frequency'][$i],
                        'medicine_advice'=>$post['medicine_advice'][$i],
                        'medicine_brand'=>$post['medicine_brand'][$i],
                        'medicine_type'=>$post['medicine_type'][$i],
                        'medicine_salt'=>$post['medicine_salt'][$i],
                        'dialysis_id'=>$post['dialysis_id'],
                        'patient_id'=>$post['patient_id'],
                        'discharge_summary_id'=>$post['data_id']
        
                        );
                
                $this->db->insert('hms_dialysis_patient_summery_medicine',$data_medicine);
                        //print_r($data_medicine);
                }
                

            }
            /*********Medicine Detail ***************/

            /*************** Investigation Test Detail ****************/
            $this->db->where(array('dialysis_id'=>$post['dialysis_id'],'discharge_summary_id'=>$post['data_id']));
            $this->db->delete('hms_dialysis_patient_discharge_summary_test');
            if($post['test_name'][0]!="")
            {
                $post_test_name= $post['test_name'];

                $counter_name= count($post_test_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_test= array(
                        'test_id'=>$post['test_id'][$i],
                        'test_name'=>$post['test_name'][$i],
                        'test_date'=>date('Y-m-d',strtotime($post['test_date'][$i])),
                        'result'=>$post['test_result'][$i],
                        'dialysis_id'=>$post['dialysis_id'],
                        'branch_id'=>$user_data['parent_id'],
                        'discharge_summary_id'=>$post['data_id']
        
                        );
                
                $this->db->insert('hms_dialysis_patient_discharge_summary_test',$data_test);
                
                    //  print_r($data_test);
                }
            

            }
            /*********Investigation Detail ***************/
            
            //death ///

            if($post['summery_type']=='5')
            {/*

                $this->db->select('hms_death_summery.*');
                $this->db->from('hms_death_summery'); 
                $this->db->where('hms_death_summery.branch_id',$user_data['parent_id']);
                $this->db->where('hms_death_summery.ipd_id',$post['ipd_id']);
                $query = $this->db->get();
                $death_result = $query->row_array();
                $id = $death_result['id'];
                if(!empty($id))
                {

                    $death_data_update = array('branch_id'=>$user_data['parent_id'],
                                    'patient_id'=>$post['patient_id'],
                                    'ipd_id'=>$post['ipd_id'],

                                    'discharge_summary_id'=>$post['data_id'],
                                    'death_date'=>date('Y-m-d',strtotime($post['death_date'])),
                                    'death_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['death_time']))
                            );

                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->where('id',$id);
                    $this->db->update('hms_death_summery',$death_data_update);


                    



                }   
                else
                {

                    $death_data = array('branch_id'=>$user_data['parent_id'],
                                    'patient_id'=>$post['patient_id'],
                                    'ipd_id'=>$post['ipd_id'],

                                    'discharge_summary_id'=>$post['data_id'],
                                    'death_date'=>date('Y-m-d',strtotime($post['death_date'])),
                                    'death_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['death_time']))
                            );

                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->insert('hms_death_summery',$death_data); 
                }               
                

            */}    

            //end death//

            
            ////////////////////////////////////////////////////////////////////////////////////
        }
        else
        {    
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_dialysis_patient_summery',$data); 
            $discharge_id =  $this->db->insert_id(); 
            

            /////////////////////////////////////////////////////////////////////////////////////
            /*add discharge master data*/
            $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$discharge_id));
            $this->db->delete('hms_patient_dialysis_field_value');
            if(!empty($post['field_name']))
            {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_field_value= array(
                        'field_value'=>$post['field_name'][$i],
                        'field_id'=>$post['field_id'][$i],
                        'branch_id'=>$user_data['parent_id'],
                        'parent_id'=>$discharge_id,
                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                        );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_patient_dialysis_field_value',$data_field_value);

                }
            }
            ////////////////////////////////////////////////////////////////////////////////////
            
            
            /*************** Medicine Detail ****************/
            $this->db->where(array('dialysis_id'=>$post['dialysis_id'],'discharge_summary_id'=>$discharge_id));
            $this->db->delete('hms_dialysis_patient_summery_medicine');
            if($post['medicine_name'][0]!="")
            {
                $post_medicine_name= $post['medicine_name'];

                $counter_name= count($post_medicine_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_medicine= array(
                        'medicine_name'=>$post['medicine_name'][$i],
                        'medicine_dose'=>$post['medicine_dose'][$i],
                        'medicine_duration'=>$post['medicine_duration'][$i],
                        'medicine_frequency'=>$post['medicine_frequency'][$i],
                        'medicine_advice'=>$post['medicine_advice'][$i],
                        'medicine_brand'=>$post['medicine_brand'][$i],
                        'medicine_type'=>$post['medicine_type'][$i],
                        'medicine_salt'=>$post['medicine_salt'][$i],
                        'dialysis_id'=>$post['dialysis_id'],
                        'patient_id'=>$post['patient_id'],
                        'discharge_summary_id'=>$discharge_id
                        );
                
                $this->db->insert('hms_dialysis_patient_summery_medicine',$data_medicine);
                        //print_r($data_medicine);
                }
                

            }
            /*********Medicine Detail ***************/

                /*************** Investigation Test Detail ****************/
            $this->db->where(array('dialysis_id'=>$post['dialysis_id'],'discharge_summary_id'=>$discharge_id));
            $this->db->delete('hms_dialysis_patient_discharge_summary_test');
            if($post['test_name'][0]!="")
            {
                $post_test_name= $post['test_name'];

                $counter_name= count($post_test_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_test= array(
                        'test_id'=>$post['test_id'][$i],
                        'test_name'=>$post['test_name'][$i],
                        'test_date'=>date('Y-m-d',strtotime($post['test_date'][$i])),
                        'result'=>$post['test_result'][$i],
                        'dialysis_id'=>$post['dialysis_id'],
                        'branch_id'=>$user_data['parent_id'],
                        'discharge_summary_id'=>$discharge_id
        
                        );
                
                $this->db->insert('hms_dialysis_patient_discharge_summary_test',$data_test);
                        //print_r($data_test);
                }
                

            }
            /*********Investigation Detail ***************/
            //////////////////////////////////////////////////////////////
            //death //

            if($post['summery_type']=='5')
            {/*

                $death_data = array('branch_id'=>$user_data['parent_id'],
                                    'patient_id'=>$post['patient_id'],
                                    'ipd_id'=>$post['ipd_id'],

                                    'discharge_summary_id'=>$discharge_id,
                                    'death_date'=>date('Y-m-d',strtotime($post['death_date'])),
                                    'death_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['death_time']))
                            );

                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_death_summery',$death_data);

            */}    

            //end death//


        }
        return $discharge_id;   

    }

    public function discharge_master_detail_by_field($parent_id="")
    {
        

    $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient_dialysis_field_value.*,hms_dialysis_field_master.discharge_lable,hms_dialysis_field_master.type,hms_dialysis_field_master.discharge_short_code'); 
        $this->db->join('hms_patient_dialysis_field_value','hms_patient_dialysis_field_value.field_id = hms_dialysis_field_master.id AND hms_patient_dialysis_field_value.parent_id = "'.$parent_id.'" AND hms_patient_dialysis_field_value.branch_id = "'.$users_data['parent_id'].'"','left'); 
        $this->db->where('hms_dialysis_field_master.branch_id',$users_data['parent_id']);
        $this->db->where('hms_dialysis_field_master.status',1);
        $this->db->where('hms_dialysis_field_master.is_deleted',0);  
        $this->db->order_by('hms_dialysis_field_master.sort_order','DESC');
        $query= $this->db->get('hms_dialysis_field_master')->result();
        //echo $this->db->last_query(); exit;
        return $query;
    }


    public function discharge_master_detail_by_field_print($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient_dialysis_field_value.*,hms_dialysis_field_master.discharge_lable,hms_dialysis_field_master.type,hms_dialysis_field_master.discharge_short_code');
        $this->db->join('hms_dialysis_field_master','hms_dialysis_field_master.id=hms_patient_dialysis_field_value.field_id and hms_dialysis_field_master.status=1');
        $this->db->where('hms_patient_dialysis_field_value.branch_id',$users_data['parent_id']);
        $this->db->where('hms_patient_dialysis_field_value.parent_id',$parent_id);
        //$this->db->order_by('hms_dialysis_field_master.status',1);
        $this->db->order_by('hms_dialysis_field_master.sort_order','DESC');
        $query= $this->db->get('hms_patient_dialysis_field_value')->result();
        //echo $this->db->last_query(); exit;
        return $query;
    }

    public function save_medicine()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        $total_prescription = count(array_filter($post['medicine_name']));  
            //echo $total_prescription; exit;
                        for($i=0;$i<$total_prescription;$i++)
            {   
                    $medicine_name ="";
                    if(!empty($post['medicine_name'][$i]))
                    {
                        $medicine_name = $post['medicine_name'][$i];
                    }

                    $medicine_salt ="";
                    if(!empty($post['medicine_salt'][$i]))
                    {
                        $medicine_salt = $post['medicine_salt'][$i];
                    }

                    $medicine_brand ="";
                    if(!empty($post['medicine_brand'][$i]))
                    {
                        $medicine_brand = $post['medicine_brand'][$i];
                    }

                    $medicine_type ="";
                    if(!empty($post['medicine_type'][$i]))
                    {
                        $medicine_type = $post['medicine_type'][$i];
                    }
                    $medicine_dose ="";
                    if(!empty($post['medicine_dose'][$i]))
                    {
                        $medicine_dose = $post['medicine_dose'][$i];
                    }
                    $medicine_duration ="";
                    if(!empty($post['medicine_duration'][$i]))
                    {
                        $medicine_duration = $post['medicine_duration'][$i];
                    }

                    $medicine_frequency ="";
                    if(!empty($post['medicine_frequency'][$i]))
                    {
                        $medicine_frequency = $post['medicine_frequency'][$i];
                    }
                    $medicine_advice ="";
                    if(!empty($post['medicine_advice'][$i]))
                    {
                        $medicine_advice = $post['medicine_advice'][$i];
                    }
                    

                    $prescription_data = array(
                        "patient_id"=>$post['patient_id'],
                        "dialysis_id"=>$post['dialysis_id'],
                        "discharge_summary_id"=>$post['patient_summary_id'],
                        "medicine_name"=>$medicine_name,
                        "medicine_brand"=>$medicine_brand,
                        "medicine_salt"=>$medicine_salt,
                        "medicine_type"=>$medicine_type,
                        "medicine_dose"=>$medicine_dose,
                        "medicine_duration"=>$medicine_duration,
                        "medicine_frequency"=>$medicine_frequency,
                        "medicine_advice"=>$medicine_advice
                        );
                    $this->db->insert('hms_dialysis_patient_summery_medicine',$prescription_data);
                    $test_data_id = $this->db->insert_id(); 
                    //echo $this->db->last_query(); exit;
                
            }
    }

    public function delete($id="")
    {
        if(!empty($id) && $id>0)
        {

            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('hms_dialysis_patient_summery');
            //echo $this->db->last_query();die;
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
            $this->db->update('hms_dialysis_patient_summery');
            //echo $this->db->last_query();die;
        } 
    }

     public function deleteall_medicine($ids=array())
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
            $medicine_ids = implode(',', $id_list);
            $this->db->where('id IN ('.$medicine_ids.')');
            $this->db->delete('hms_dialysis_patient_summery_medicine');
        } 
    }

    function get_simulation_name($simulation_id)
    {
        $this->db->select('simulation'); 
        $this->db->where('id',$simulation_id);                   
        $query = $this->db->get('hms_discharge_summery');
        $test_list = $query->result(); 
            foreach($test_list as $simulations)
            {
               $simulation = $simulations->simulation;
            } 
        
        return $simulation; 
    }

    public function template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_dialysis_summery');
        $result = $query->result(); 
        return $result; 
    }

   public function template_data($template_id="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_dialysis_summery.*');  
        $this->db->where('hms_dialysis_summery.id',$template_id);  
        $this->db->where('hms_dialysis_summery.is_deleted',0); 
        $this->db->where('hms_dialysis_summery.branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_dialysis_summery');
        $result = $query->row(); 
        return json_encode($result);
    }
    
    public function get_dialysis_value($summary_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $branch_id = $users_data['parent_id'];
        $this->db->select('hms_patient_dialysis_template_field_value.*,hms_dialysis_field_master.field_name');
        $this->db->join('hms_dialysis_field_master','hms_dialysis_field_master.id = hms_patient_dialysis_template_field_value.field_id','left');
        
        
        $this->db->from('hms_patient_dialysis_template_field_value');
        $this->db->where('hms_patient_dialysis_template_field_value.parent_id',$summary_id);
        $this->db->where('hms_patient_dialysis_template_field_value.branch_id',$branch_id);
        $result= $this->db->get()->result();
        //echo $this->db->last_query(); exit;
        return json_encode($result);
        
    }


  public function get_detail_by_summary_id($discharge_id)
  {
    $this->db->select("hms_dialysis_patient_summery.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_dialysis_booking.booking_code,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.dialysis_time,hms_doctors.doctor_name,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name"); 

    $this->db->where('hms_dialysis_patient_summery.id',$discharge_id);
    $this->db->join('hms_patient','hms_patient.id=hms_dialysis_patient_summery.patient_id','left');
    
    $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        
    
    $this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
    $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_patient_summery.dialysis_id','left');
    $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.doctor_id','left');
    //$this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left'); 
    $this->db->from('hms_dialysis_patient_summery'); 
    $result_pre['discharge_list']= $this->db->get()->result();
    //echo $this->db->last_query(); exit;
    
   /* $this->db->select('hms_dialysis_patient_summery_medicine_administered.medicine_name,hms_dialysis_patient_summery_medicine_administered.medicine_dose,hms_dialysis_patient_summery_medicine_administered.medicine_duration,hms_dialysis_patient_summery_medicine_administered.medicine_frequency,hms_dialysis_patient_summery_medicine_administered.medicine_advice');
    $this->db->from('hms_dialysis_patient_summery_medicine_administered');
    $this->db->where('hms_dialysis_patient_summery_medicine_administered.discharge_summary_id',$discharge_id);
    $result_pre['medicine_administered_detail']= $this->db->get()->result();
*/
    $this->db->select('hms_dialysis_patient_summery_medicine.medicine_name,hms_dialysis_patient_summery_medicine.medicine_dose,hms_dialysis_patient_summery_medicine.medicine_duration,hms_dialysis_patient_summery_medicine.medicine_frequency,hms_dialysis_patient_summery_medicine.medicine_advice,hms_dialysis_patient_summery_medicine.medicine_type');
    $this->db->from('hms_dialysis_patient_summery_medicine');
    $this->db->where('hms_dialysis_patient_summery_medicine.discharge_summary_id',$discharge_id);
    $result_pre['medicine_list']= $this->db->get()->result();

    $this->db->select('hms_dialysis_patient_discharge_summary_test.test_name,hms_dialysis_patient_discharge_summary_test.test_date,hms_dialysis_patient_discharge_summary_test.result');
    $this->db->from('hms_dialysis_patient_discharge_summary_test');
    $this->db->where('hms_dialysis_patient_discharge_summary_test.discharge_summary_id',$discharge_id);
    $result_pre['investigation_test_list']= $this->db->get()->result();
    //echo $this->db->last_query(); exit;
    return $result_pre;

  }
  public function get_doctor_signature($doctor_id='')
  {
      $this->db->select('hms_signature.*');
      $this->db->where('hms_signature.doctor_id = "'.$doctor_id.'"');
      $this->db->where('hms_signature.is_deleted','0'); 
      $this->db->where('hms_signature.signature_type','2'); 
      $this->db->where('hms_signature.branch_id = "'.$user_data['parent_id'].'"');
      $this->db->from('hms_signature');
      $result_pre['signature_data']=$this->db->get()->result();
  }

    function medicine_template_format($data="",$branch_id='')
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_ipd_discharge_medicine_print_setting.*');
      $this->db->where($data);
      if(!empty($branch_id))
      {
        $this->db->where('hms_ipd_discharge_medicine_print_setting.branch_id = "'.$branch_id.'"');
      }
      else
      {
        $this->db->where('hms_ipd_discharge_medicine_print_setting.branch_id = "'.$users_data['parent_id'].'"');
      }
      
      $this->db->from('hms_ipd_discharge_medicine_print_setting');
      $result=$this->db->get()->row();
      return $result;

    }

    public function get_medicine_by_id($id)
    {
        $this->db->select('hms_dialysis_patient_summery_medicine.*');
        $this->db->from('hms_dialysis_patient_summery_medicine'); 
        $this->db->where('hms_dialysis_patient_summery_medicine.discharge_summary_id',$id);
        $query = $this->db->get(); 
        return $query->result_array();
    }

    function template_format($data="",$branch_id='',$type='0')
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_branch_dialysis_summary_setting.*');
      $this->db->where($data);
      if(!empty($branch_id))
      {
        $this->db->where('hms_branch_dialysis_summary_setting.branch_id = "'.$branch_id.'"');
      }
      else
      {
        $this->db->where('hms_branch_dialysis_summary_setting.branch_id = "'.$users_data['parent_id'].'"');
      }
       $this->db->where('type',$type);
      $this->db->from('hms_branch_dialysis_summary_setting');
      $result=$this->db->get()->row();
      //echo $this->db->last_query(); exit;
      return $result;

    }


  public function get_medicine_detail_by_summary_id($discharge_id)
  {
    $this->db->select("hms_dialysis_patient_summery_medicine.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_dialysis_booking.booking_code,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.doctor_id,hms_dialysis_booking.dialysis_time,hms_doctors.doctor_name,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 

    $this->db->where('hms_dialysis_patient_summery_medicine.discharge_summary_id',$discharge_id);
    $this->db->join('hms_patient','hms_patient.id=hms_dialysis_patient_summery_medicine.patient_id','left');
    $this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
    $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_patient_summery_medicine.dialysis_id','left');
    $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.doctor_id','left'); 
    $this->db->from('hms_dialysis_patient_summery_medicine'); 
    $result_pre['discharge_list']= $this->db->get()->result();
    //echo $this->db->last_query(); exit;
    return $result_pre;

  }

  public function discharge_field_master_list()
  {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_dialysis_field_master.*');
        $this->db->from('hms_dialysis_field_master');
        $this->db->where('hms_dialysis_field_master.status',1);
        $this->db->where('hms_dialysis_field_master.is_deleted',0);
        $this->db->where('hms_dialysis_field_master.branch_id',$users_data['parent_id']);
        $this->db->order_by('hms_dialysis_field_master.sort_order','ASC');  
        $result = $this->db->get()->result();
        return $result;
  }
  
  
  /********** Medicine List of discharge summary ************/
  public function get_discharge_medicine($id)
    {
        $this->db->select('hms_dialysis_patient_summery_medicine.medicine_name,hms_dialysis_patient_summery_medicine.medicine_dose,hms_dialysis_patient_summery_medicine.medicine_duration,hms_dialysis_patient_summery_medicine.medicine_frequency,hms_dialysis_patient_summery_medicine.medicine_advice,hms_dialysis_patient_summery_medicine.medicine_type');
        $this->db->from('hms_dialysis_patient_summery_medicine'); 
        $this->db->where('hms_dialysis_patient_summery_medicine.discharge_summary_id',$id);
        $query = $this->db->get(); 
        return $query->result();
    }
  /********** Medicine List of discharge summary ************/

  /********** Investigation Test  List of discharge summary ************/
  public function get_discharge_test($id)
    {
        $this->db->select('hms_dialysis_patient_discharge_summary_test.test_name,hms_dialysis_patient_discharge_summary_test.test_date,hms_dialysis_patient_discharge_summary_test.result, hms_dialysis_patient_discharge_summary_test.id');
        $this->db->from('hms_dialysis_patient_discharge_summary_test'); 
        $this->db->where('hms_dialysis_patient_discharge_summary_test.discharge_summary_id',$id);
        $query = $this->db->get(); 
      //echo $this->db->last_query(); exit;
        return $query->result();
    }
  /********** Investigation List of discharge summary ************/
  
  
   public function report_html_template($part="",$branch_id="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_dialysis_summary_print_letter_head_setting.*');
      if(!empty($branch_id))
      {
          $this->db->where('hms_dialysis_summary_print_letter_head_setting.branch_id',$branch_id);
      }
      else
      {
           $this->db->where('hms_dialysis_summary_print_letter_head_setting.branch_id',$users_data['parent_id']);
      } 
        $this->db->from('hms_dialysis_summary_print_letter_head_setting'); 
      $result=$this->db->get()->row(); 
      // echo $this->db->last_query();die;
      return $result;
    }


     function test_report_template_format($data="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $company_data = $this->session->userdata('company_data'); 
      $this->db->select('hms_dialysis_summary_print_letter_head_setting.*');
      $this->db->where($data);
     
            $this->db->where('hms_dialysis_summary_print_letter_head_setting.branch_id',$users_data['parent_id']); 
       
      $this->db->from('hms_dialysis_summary_print_letter_head_setting');
      $result=$this->db->get()->row();
      //echo $this->db->last_query();die;
      return $result;

    } 
 

}
?>