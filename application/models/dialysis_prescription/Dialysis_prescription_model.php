<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dialysis_prescription_model extends CI_Model 
{
    var $table = 'hms_dialysis_prescription';
    var $column = array('hms_dialysis_prescription.id','hms_dialysis_prescription.booking_code','hms_dialysis_prescription.patient_name', 'hms_dialysis_prescription.mobile_no','hms_dialysis_prescription.patient_code', 'hms_dialysis_prescription.status', 'hms_dialysis_prescription.created_date', 'hms_dialysis_prescription.modified_date');  
    var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('prescription_search');
        $this->db->select("hms_dialysis_prescription.*,hms_dialysis_prescription_patient_test.test_name,hms_dialysis_prescription_patient_pres.medicine_name,hms_dialysis_prescription_patient_pres.medicine_salt,hms_dialysis_prescription_patient_pres.medicine_brand,hms_dialysis_prescription_patient_pres.medicine_type,hms_dialysis_prescription_patient_pres.medicine_dose,hms_dialysis_prescription_patient_pres.medicine_duration,hms_dialysis_prescription_patient_pres.medicine_frequency,hms_dialysis_prescription_patient_pres.medicine_advice"); 
        
        
        
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_prescription.booking_id','left');
        
        
        $this->db->join('hms_dialysis_prescription_patient_test','hms_dialysis_prescription_patient_test.prescription_id=hms_dialysis_prescription.id','left');
        $this->db->join('hms_dialysis_prescription_patient_pres','hms_dialysis_prescription_patient_pres.prescription_id=hms_dialysis_prescription.id','left');
        
        $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
        //$this->db->where('hms_dialysis_prescription.branch_id = "'.$user_data['parent_id'].'"'); 

        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('hms_dialysis_prescription.branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('hms_dialysis_prescription.branch_id = "'.$user_data['parent_id'].'"');
        }

        /////// Search query start //////////////

        if(isset($search) && !empty($search))
        {
            
            if(!empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_dialysis_prescription.created_date >= "'.$start_date.'"');
            }

            if(!empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_dialysis_prescription.created_date <= "'.$end_date.'"');
            }
            
            
            if(!empty($search['patient_name']))
            {
                $this->db->where('hms_dialysis_prescription.patient_name LIKE "'.$search['patient_name'].'%"');
            }

            if(!empty($search['patient_code']))
            {
                $this->db->where('hms_dialysis_prescription.patient_code',$search['patient_code']);
            }

            if(!empty($search['mobile_no']))
            {
                $this->db->where('hms_dialysis_prescription.mobile_no LIKE "'.$search['mobile_no'].'%"');
            }
            
            if(!empty($search['patient_id']))
            {
                $this->db->where('hms_dialysis_prescription.patient_id',$search['patient_id']);
            }
            if(!empty($search['dialysis_id']))
            {
                $this->db->where('hms_dialysis_prescription.booking_id',$search['dialysis_id']);
            }
            
            if(!empty($search['dialysis_no']))
            {
                $this->db->where('hms_dialysis_booking.booking_code LIKE "'.$search['dialysis_no'].'%"');
            }
            
            


        }

        $emp_ids='';
        if($user_data['emp_id']>0)
        {
            if($user_data['record_access']=='1')
            {
                $emp_ids= $user_data['id'];
            }
        }
        elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }
          

        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_dialysis_prescription.created_by IN ('.$emp_ids.')');
        }
        /////// Search query end //////////////

        $this->db->from($this->table); 
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
         $this->db->group_by('hms_dialysis_prescription.id');
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

    public function get_by_id($id)
    {
        $this->db->select("hms_dialysis_prescription.*,hms_dialysis_prescription_patient_test.test_name,hms_dialysis_prescription_patient_pres.medicine_name,hms_dialysis_prescription_patient_pres.medicine_salt,hms_dialysis_prescription_patient_pres.medicine_brand,hms_dialysis_prescription_patient_pres.medicine_type,hms_dialysis_prescription_patient_pres.medicine_dose,hms_dialysis_prescription_patient_pres.medicine_duration,hms_dialysis_prescription_patient_pres.medicine_frequency,hms_dialysis_prescription_patient_pres.medicine_advice"); 
        $this->db->from('hms_dialysis_prescription'); 
        $this->db->where('hms_dialysis_prescription.id',$id);
        $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
        $this->db->join('hms_dialysis_prescription_patient_test','hms_dialysis_prescription_patient_test.prescription_id=hms_dialysis_prescription.id','left');
        $this->db->join('hms_dialysis_prescription_patient_pres','hms_dialysis_prescription_patient_pres.prescription_id=hms_dialysis_prescription.id','left');
        
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function get_by_prescription_id($prescription_id)
    {
        $this->db->select("hms_dialysis_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob"); 
        $this->db->from('hms_dialysis_prescription'); 
        $this->db->where('hms_dialysis_prescription.id',$prescription_id);
        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_prescription.patient_id','left');
        //$this->db->join('hms_dialysis_prescription_patient_test','hms_dialysis_prescription_patient_test.prescription_id=hms_dialysis_prescription.id','left');
        //$this->db->join('hms_dialysis_prescription_patient_pres','hms_dialysis_prescription_patient_pres.prescription_id=hms_dialysis_prescription.id','left');
        
        //$query = $this->db->get(); 
        $result_pre['prescription_list']= $this->db->get()->result();
        //echo $this->db->last_query(); exit;
        
        $this->db->select('hms_dialysis_prescription_patient_test.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_test.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_dialysis_prescription_patient_test');
        $result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();
        //echo $this->db->last_query(); exit;

        $this->db->select('hms_dialysis_prescription_patient_pres.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_pres.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_dialysis_prescription_patient_pres');
        $result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();
        //echo $this->db->last_query(); exit;

        return $result_pre;

    }


    public function get_detail_by_prescription_id($prescription_id)
    {
        $this->db->select("hms_dialysis_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_dialysis_booking.booking_code,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.doctor_id,hms_dialysis_booking.dialysis_time,hms_dialysis_prescription.patient_bp as patientbp,hms_dialysis_prescription.patient_temp as patienttemp,hms_dialysis_prescription.patient_weight as patientweight,hms_dialysis_prescription.patient_height as patientpr,hms_dialysis_prescription.patient_spo2 as patientspo,hms_dialysis_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation,hms_doctors.header_content,hms_doctors.seprate_header,hms_doctors.opd_header,hms_doctors.billing_header,hms_doctors.prescription_header"); 



        $this->db->from('hms_dialysis_prescription'); 
        $this->db->where('hms_dialysis_prescription.id',$prescription_id);
        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_prescription.patient_id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_prescription.booking_id','left'); 
        
        $this->db->join('hms_doctors','hms_doctors.id = hms_dialysis_booking. doctor_id','left');
        
        $result_pre['prescription_list']= $this->db->get()->result();
        //echo $this->db->last_query(); exit;
        
        $this->db->select('hms_dialysis_prescription_patient_test.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_test.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_test.prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_dialysis_prescription_patient_test');
        $result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

        $this->db->select('hms_dialysis_prescription_patient_pres.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_pres.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_dialysis_prescription_patient_pres');
        $result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();

        return $result_pre;

    }


    public function get_detail_by_booking_id($id,$branch_id='')
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_dialysis_booking.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_dialysis_booking.booking_code,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.doctor_id,hms_dialysis_booking.dialysis_time,hms_dialysis_booking.patient_bp as patientbp,hms_dialysis_booking.patient_temp as patienttemp,hms_dialysis_booking.patient_weight as patientweight,hms_dialysis_booking.patient_height as patientpr,hms_dialysis_booking.patient_spo2 as patientspo,hms_dialysis_booking.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation,hms_doctors.header_content,hms_doctors.seprate_header,hms_doctors.opd_header,hms_doctors.billing_header,hms_doctors.prescription_header');
        $this->db->from('hms_dialysis_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_dialysis_booking.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_dialysis_booking. doctor_id','left');
        
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        if(!empty($branch_id))
        {
            $this->db->where('hms_dialysis_booking.branch_id',$branch_id); 
        }
        else
        {
            $this->db->where('hms_dialysis_booking.branch_id',$user_data['parent_id']);  
        }
        
        $this->db->where('hms_dialysis_booking.id',$id);
        $this->db->where('hms_dialysis_booking.is_deleted','0');
         
        $result_pre['prescription_list']= $this->db->get()->result();
        //echo "<pre>";print_r($result_pre); exit;
        return $result_pre;
    }


    public function get_by_ids($id)
    {
        $this->db->select("hms_dialysis_prescription.*,hms_dialysis_booking.booking_code,hms_dialysis_booking.dialysis_time,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.dialysis_time,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation");  //,hms_dialysis_prescription_patient_test.*,hms_dialysis_prescription_patient_pres.*
        $this->db->from('hms_dialysis_prescription'); 
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_prescription.booking_id','left');
        $this->db->join('hms_patient','hms_patient.id = hms_dialysis_booking.patient_id'); 
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->where('hms_dialysis_prescription.id',$id);
        $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
        $query = $this->db->get(); 
        return $query->row_array();
        
    }

    public function get_by_ids20171212($id)
    {
        $this->db->select("hms_dialysis_prescription.*");  //,hms_dialysis_prescription_patient_test.*,hms_dialysis_prescription_patient_pres.*
        $this->db->from('hms_dialysis_prescription'); 
        $this->db->where('hms_dialysis_prescription.id',$id);
        $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
        $query = $this->db->get(); 
        return $query->row_array();
        
    }

    public function get_prescription_by_ids($id)
    {
        $this->db->select("hms_dialysis_prescription.*");  //,hms_dialysis_prescription_patient_test.*,hms_dialysis_prescription_patient_pres.*patient_id
        $this->db->from('hms_dialysis_prescription'); 
        $this->db->where('hms_dialysis_prescription.id',$id);
        $this->db->where('hms_dialysis_prescription.is_deleted','0'); 
        $query = $this->db->get(); 
        return $query->row_array();
        
    }
    
    function get_opd_prescription($prescription_id='')
    {
        $this->db->select("hms_dialysis_prescription_patient_pres.*"); 
        $this->db->from('hms_dialysis_prescription_patient_pres'); 
        $this->db->where('hms_dialysis_prescription_patient_pres.prescription_id',$prescription_id);
        $query = $this->db->get(); 
        $result = $query->result(); 
        return $result;
    }

    function get_opd_test($prescription_id='')
    {
        $this->db->select("hms_dialysis_prescription_patient_test.*"); 
        $this->db->from('hms_dialysis_prescription_patient_test'); 
        $this->db->where('hms_dialysis_prescription_patient_test.prescription_id',$prescription_id);
        $query = $this->db->get(); 
        $result = $query->result(); 
        return $result;
    }

    public function save($filename="")
    {  
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $reg_no = generate_unique_id(3);  
        $data = array(
                    "branch_id"=> $user_data['parent_id'],    
                    "patient_name"=>$post['patient_name'],
                    "simulation_id"=>$post['simulation_id'],
                    "mobile_no"=>$post['mobile_no'],
                    "gender"=>$post['gender'], 
                    "age_y"=>$post['age_y'],
                    "age_m"=>$post['age_m'],
                    "age_d"=>$post['age_d'],
                    "dob"=>$post['dob'],
                    "address"=>$post['address'],
                    "city_id"=>$post['city_id'],
                    "state_id"=>$post['state_id'],
                    "country_id"=>$post['country_id'],
                    "pincode"=>$post['pincode'],
                    "marital_status"=>$post['marital_status'],
                    "religion_id"=>$post['religion_id'],
                    "father_husband"=>$post['father_husband'],
                    "mother"=>$post['mother'],
                    "guardian_name"=>$post['guardian_name'],
                    "guardian_email"=>$post['guardian_email'],
                    "guardian_phone"=>$post['guardian_phone'],
                    "relation_id"=>$post['relation_id'],
                    "patient_email"=>$post['patient_email'],
                    "monthly_income"=>$post['monthly_income'],
                    "occupation"=>$post['occupation'],
                    "insurance_type"=>$post['insurance_type'],
                    "insurance_type_id"=>$post['insurance_type_id'],
                    "ins_company_id"=>$post['ins_company_id'],
                    "polocy_no"=>$post['polocy_no'],
                    "tpa_id"=>$post['tpa_id'],
                    "ins_amount"=>$post['ins_amount'],
                    "ins_authorization_no"=>$post['ins_authorization_no'], 
                    "status"=>$post['status'],
                    "remark"=>$post['remark'] 
                         ); 
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            if(!empty($filename))
            {
                $this->db->set('photo',$filename);
            }
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_patient',$data); 
        }
        else
        {    
            if(!empty($filename))
            {
                $this->db->set('photo',$filename);
            }
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_patient',$data); 
            $data_id = $this->db->insert_id();               
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
            $this->db->update('hms_dialysis_prescription');
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
            $this->db->update('hms_dialysis_prescription');
        } 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        return $result; 
    }


    public function save_file($filename="")
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        $data = array( 
                    'prescription_id'=>$post['prescription_id'],
                    'doc_name'=>$post['doc_name'],
                    'branch_id' => $user_data['parent_id'],
                    'status' =>1,
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            if(!empty($filename))
            {
               $this->db->set('prescription_files',$filename);
            }
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_dialysis_prescription_files',$data);  
        }
        else{    
            if(!empty($filename))
            {
               $this->db->set('prescription_files',$filename);
            }
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_dialysis_prescription_files',$data);               
        }   
    }

    public function get_prescription_files($prescription_id='')
    {
        if(!empty($prescription_id) && $prescription_id>0)
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->select('*');
            $this->db->where('is_deleted',0);
            $this->db->where('status',1);
            $this->db->where('prescription_id',$prescription_id);
            $query = $this->db->get('hms_dialysis_prescription_files'); 
            $result = $query->result(); 
            //echo $this->db->last_query(); exit;
            return $result;
            
        } 
    }

    public function save_prescription()
    {
        
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $prescriptionid = $post['data_id'];
        //echo "<pre>"; print_r($post); exit;
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
        if($post['next_appointment_date']!='00-00-0000 00:00:00' && $post['next_appointment_date']!='01-01-1970')
        {
            $next_appointment_date = date('Y-m-d H:i:s',strtotime($post['next_appointment_date']));
        }   
        else
        {
            $next_appointment_date = ''; 
        }
        
        $relation_sim_id='';
		if(!empty($post['relation_simulation_id']))
		{
		    $relation_sim_id=$post['relation_simulation_id'];
		}
		$relation_name='';
		if(!empty($post['relation_name']))
		{
		    $relation_name=$post['relation_name'];
		}
		$relation_type='';
		if(!empty($post['relation_type']))
		{
		    $relation_type=$post['relation_type'];
		}
        
        $data_patient = array(
                                "patient_name"=>$post['patient_name'],
                                "mobile_no"=>$post['mobile_no'],
                                "gender"=>$post['gender'], 
                                "age_y"=>$post['age_y'],
                                "age_m"=>$post['age_m'],
                                "age_d"=>$post['age_d'],
                                //'adhar_no'=>$post['aadhaar_no'],
                                'relation_type'=>$relation_type,
								'relation_name'=>$relation_name,
								'relation_simulation_id'=>$relation_sim_id,
                            );

        if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
            $patient_id = $post['patient_id'];
            $this->db->where('id',$post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
            //echo $this->db->last_query(); exit;
        } 
        else
        {
            $patient_code = generate_unique_id(4);
            $this->db->set('patient_code',$patient_code);
            $this->db->insert('hms_patient',$data_patient);  
            $patient_id = $this->db->insert_id();
            //echo $this->db->last_query(); exit; 
        }
        $data = array( 
                    "branch_id"=> $user_data['parent_id'],      
                    "booking_code"=>$post['booking_code'],
                    "patient_code"=>$post['patient_code'],
                    "patient_id"=>$post['patient_id'],
                    "booking_id"=>$post['booking_id'],
                    "patient_name"=>$post['patient_name'],
                    "mobile_no"=>$post['mobile_no'],
                    "gender"=>$post['gender'], 
                    "age_y"=>$post['age_y'],
                    "age_m"=>$post['age_m'],
                    "age_d"=>$post['age_d'],
                    /*"patient_bp"=>$post['patient_bp'],
                    "patient_temp"=>$post['patient_temp'],
                    "patient_weight"=>$post['patient_weight'],
                    "patient_height"=>$post['patient_height'],
                    "patient_spo2"=>$post['patient_spo2'],
                    "patient_rbs"=>$post['patient_rbs'],*/
                    "prv_history"=>$post['prv_history'],
                    "personal_history"=>$post['personal_history'],
                    "chief_complaints"=>$post['chief_complaints'],
                    "examination"=>$post['examination'],
                    "diagnosis"=>$post['diagnosis'],
                    "suggestion"=>$post['suggestion'],
                    "remark"=>$post['remark'],
                    "attended_doctor"=>$post['attended_doctor'],
                    "next_appointment_date"=>$next_appointment_date,
                    "appointment_date"=>$post['appointment_date'],
                    "status"=>1,
                    'prescription_date'=>date('Y-m-d H:i',strtotime($post['prescription_date'])),
                    ); 
            
            
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_dialysis_prescription',$data); 

            //echo $this->db->last_query(); exit;
            $this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_dialysis_prescription_patient_test'); 

            //$total_test = count($post['test_name']);
            $total_test = count(array_filter($post['test_name'])); 
            //foreach ($post['test_name'] as $value) 
            //{
            for($i=0;$i<$total_test;$i++)
            {
                    

                              //check and add masters of test 
                    //check and add masters of test 
                    if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
                    {   
                        $this->db->select('path_test.*');  
                        $this->db->where('path_test.test_name',$post['test_name'][$i]);  
                        $this->db->where('path_test.is_deleted!=2'); 
                        $this->db->where('path_test.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('path_test');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                                        $path_test_data = $query->result_array();
                                        if(!empty($path_test_data))
                                        {
                                            $test_id = $path_test_data[0]['id'];
                                        }
                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['test_name'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('path_test',$data);
                                $test_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    else
                    {
                        $test_id = $post['test_id'][$i];
                    }


                    
                  $test_data = array(
                        "prescription_id"=>$post['data_id'],
                        "test_name"=>$post['test_name'][$i],
                        "test_id"=>$test_id);
                    $this->db->insert('hms_dialysis_prescription_patient_test',$test_data); 
                    $test_data_id = $this->db->insert_id(); 
            }

            $this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_dialysis_prescription_patient_pres'); 

            //$total_prescription = count($post['medicine_name']);
            $total_prescription = count(array_filter($post['medicine_name']));  
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
                    
                                       //check and add masters 
                    if(!empty($post['medicine_name'][$i]))
                    {   
                        $this->db->select('hms_medicine_entry.*');  
                        //$this->db->from('hms_opd_medicine');
                        $this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
                        $this->db->where('hms_medicine_entry.is_deleted=0'); 
                        $this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_medicine_entry');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $company_data = $query->result_array();
                            if(!empty($company_data))
                            {
                                $medicine_id = $company_data[0]['id'];
                            }
                        }
                        else
                        {       
                                $unit_id ='';
                                //check medicine type
                                if(!empty($post['medicine_type'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_unit.*');  
                                    $this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
                                    $this->db->where('hms_medicine_unit.is_deleted=0'); 
                                    $this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_unit');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $unit_data = $query->result_array();
                                        if(!empty($unit_data))
                                        {
                                            $unit_id = $unit_data[0]['id'];
                                        }   
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_unit',$data);
                                        $unit_id = $this->db->insert_id(); 
                                    }
                                }

                                ////check medicine type end

                                //check medicine company
                                $company_id ='';
                                if(!empty($post['brand'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_company.*');  
                                    $this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
                                    $this->db->where('hms_medicine_company.is_deleted=0'); 
                                    $this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_company');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $company_data = $query->result_array();
                                        if(!empty($company_data))
                                        {
                                            $company_id = $company_data[0]['id'];
                                        }   
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'company_name'=>$post['brand'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_company',$data);
                                        $company_id = $this->db->insert_id(); 
                                    }
                                }


                                //medicine company end
                                $salt ='';
                                if(!empty($post['salt'][$i]))
                                {
                                    $salt = $post['salt'][$i];
                                }
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_name'=>$post['medicine_name'][$i],
                                                'type'=>$unit_id,
                                                'salt'=>$salt,
                                                'manuf_company'=>$company_id,
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_medicine_entry',$data);
                                $medicine_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    //medicne type
                    if(!empty($post['medicine_type'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_type.*');
                        //$this->db->from('hms_opd_medicine_type');
                        $this->db->where('hms_opd_medicine_type.medicine_type',$post['medicine_type'][$i]); 
                        $this->db->where('hms_opd_medicine_type.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_type.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_type');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_type'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_type',$data);
                        }
                    }
                    //medicine dose
                    if(!empty($post['medicine_dose'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage.*');
                        //$this->db->from('hms_opd_medicine_dosage');
                        $this->db->where('hms_opd_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_dosage',$data);
                        }
                    }

                    //medicine duration
                    if(!empty($post['medicine_duration'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage_duration.*'); 
                        //$this->db->from('hms_opd_medicine_dosage_duration');
                        $this->db->where('hms_opd_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage_duration.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage_duration');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_dosage_duration',$data);
                        }
                    }

                    //medicine frequency
                    if(!empty($post['medicine_frequency'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage_frequency.*');
                        //$this->db->from('hms_opd_medicine_dosage_frequency');
                        $this->db->where('hms_opd_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage_frequency.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage_frequency');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_opd_medicine_dosage_frequency',$data);
                        }
                    }

                    //medicine advice
                    if(!empty($post['medicine_advice'][$i]))
                    {
                        $this->db->select('*');
                        //$this->db->from('hms_opd_advice');
                        $this->db->where('hms_opd_advice.medicine_advice',$post['medicine_advice'][$i]); 
                        $this->db->where('hms_opd_advice.is_deleted!=2'); 
                        $this->db->where('hms_opd_advice.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_advice');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_advice'=>$post['medicine_advice'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_opd_advice',$data);
                        }
                    }

                    if(!empty($post['medicine_id'][$i]))
                    {
                        $medicine_id = $post['medicine_id'][$i];
                    }
                    else
                    {
                        $medicine_id = $medicine_id;
                    }

                    $prescription_data = array(
                        "prescription_id"=>$post['data_id'],
                        'medicine_id'=>$medicine_id,
                        "medicine_name"=>$medicine_name,
                        "medicine_brand"=>$medicine_brand,
                        "medicine_salt"=>$medicine_salt,
                        "medicine_type"=>$medicine_type,
                        "medicine_dose"=>$medicine_dose,
                        "medicine_duration"=>$medicine_duration,
                        "medicine_frequency"=>$medicine_frequency,
                        "medicine_advice"=>$medicine_advice
                        );
                    $this->db->insert('hms_dialysis_prescription_patient_pres',$prescription_data);
                    $test_data_id = $this->db->insert_id(); 
                    //echo $this->db->last_query(); exit;
                
            }
            ;

            if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00' && $post['next_appointment_date']!='30-11-0')
            {
               $booking_id = $post['booking_id'];
               $this->load->model('opd/opd_model');
               $opd_booking_data = $this->opd_model->get_by_id($booking_id);
                
               if(!empty($opd_booking_data))
               {
                  $booking_id = $opd_booking_data['id'];
                  $referral_doctor = $opd_booking_data['referral_doctor'];
                  $attended_doctor = $opd_booking_data['attended_doctor'];
                  $patient_id = $opd_booking_data['patient_id'];
                  $simulation_id = $opd_booking_data['simulation_id'];
                  $patient_code = $opd_booking_data['patient_code'];
                  $patient_name = $opd_booking_data['patient_name'];
                  $mobile_no = $opd_booking_data['mobile_no'];
                  $gender = $opd_booking_data['gender'];
                  $age_y = $opd_booking_data['age_y'];
                  $age_m = $opd_booking_data['age_m'];
                  $age_d = $opd_booking_data['age_d'];
                  $address = $opd_booking_data['address'];
                  $city_id = $opd_booking_data['city_id'];
                  $state_id = $opd_booking_data['state_id'];
                  $country_id = $opd_booking_data['country_id']; 
               }



                $appointment_code = generate_unique_id(20);
                $timestamp = $post['next_appointment_date'];
                $splitTimeStamp = explode(" ",$timestamp);
                $booking_date = $splitTimeStamp[0];
                $booking_time = $splitTimeStamp[1];

               $data_test = array(    
                        'branch_id'=> $user_data['parent_id'],
                        'parent_id'=>0,
                        'type'=>1,
                        'patient_id'=>$patient_id,
                        'referral_doctor'=>$referral_doctor,
                        'attended_doctor'=>$attended_doctor,
                        'booking_status'=>'0', 
                        'appointment_code'=>$appointment_code, 
                        'appointment_date'=>date('Y-m-d',strtotime($booking_date)),
                        'appointment_time'=>date('H:i:s',strtotime($booking_time)), 
                        'created_by'=>$user_data['id'],
                        'created_date'=>date('Y-m-d H:i:s')
                );

              


               //'sample_collected_by'=>$sample_collected_by,
               //'staff_refrenace_id'=>$staff_refrenace_id,
               //'booking_date'=>date('Y-m-d',strtotime($post['next_appointment_date']))
                /*$this->db->set('patient_id',$patient_id);
                $this->db->set('type',1);
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_dialysis_booking',$data_test);    
                $booking_id = $this->db->insert_id(); 
                */
                //echo $this->db->last_query(); exit;
            }



            if(!empty($post['data']))
            {  

                        $this->db->where('booking_id',$prescriptionid);
            $this->db->where('branch_id',$user_data['parent_id']);
                        $this->db->delete('hms_branch_vitals');
  
                $current_date = date('Y-m-d H:i:s');
                foreach($post['data'] as $key=>$val)
                {
                    
                    $data = array(
                                   "branch_id"=>$user_data['parent_id'],
                                   "type"=>2,
                                   "booking_id"=>$prescriptionid,
                                   "vitals_id"=>$key,
                                   "vitals_value"=>$val['name'],
                                   
                                  );
                  
                  $this->db->insert('hms_branch_vitals',$data);
                  $id = $this->db->insert_id();
                } 
            }


            return $prescriptionid;

        }
        else
        {

        } 
    
    }
    public function save_prescription_old()
    {
        
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $prescriptionid = $post['data_id'];
        //echo "<pre>"; print_r($post); exit;
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
        if($post['next_appointment_date']!='00-00-0000 00:00:00' && $post['next_appointment_date']!='01-01-1970')
        {
            $next_appointment_date = date('Y-m-d H:i:s',strtotime($post['next_appointment_date']));
        }   
        else
        {
            $next_appointment_date = ''; 
        }
        $data = array( 
                    "branch_id"=> $user_data['parent_id'],      
                    "booking_code"=>$post['booking_code'],
                    "patient_code"=>$post['patient_code'],
                    "patient_id"=>$post['patient_id'],
                    "booking_id"=>$post['booking_id'],
                    "patient_name"=>$post['patient_name'],
                    "mobile_no"=>$post['mobile_no'],
                    "gender"=>$post['gender'], 
                    "age_y"=>$post['age_y'],
                    "age_m"=>$post['age_m'],
                    "age_d"=>$post['age_d'],
                    /*"patient_bp"=>$post['patient_bp'],
                    "patient_temp"=>$post['patient_temp'],
                    "patient_weight"=>$post['patient_weight'],
                    "patient_height"=>$post['patient_height'],
                    "patient_spo2"=>$post['patient_spo2'],
                    "patient_rbs"=>$post['patient_rbs'],*/
                    "prv_history"=>$post['prv_history'],
                    "personal_history"=>$post['personal_history'],
                    "chief_complaints"=>$post['chief_complaints'],
                    "examination"=>$post['examination'],
                    "diagnosis"=>$post['diagnosis'],
                    "suggestion"=>$post['suggestion'],
                    "remark"=>$post['remark'],
                    "attended_doctor"=>$post['attended_doctor'],
                    "next_appointment_date"=>$next_appointment_date,
                    "appointment_date"=>$post['appointment_date'],
                    "status"=>1
                    ); 
            
            
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_dialysis_prescription',$data); 

            //echo $this->db->last_query(); exit;
            $this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_dialysis_prescription_patient_test'); 

            //$total_test = count($post['test_name']);
            $total_test = count(array_filter($post['test_name'])); 
            //foreach ($post['test_name'] as $value) 
            //{
            for($i=0;$i<$total_test;$i++)
            {
                    

                              //check and add masters of test 
                    if(!empty($post['test_name'][$i]))
                    {   
                        $this->db->select('hms_opd_test_name.*');  
                        $this->db->where('hms_opd_test_name.test_name',$post['test_name'][$i]);  
                        $this->db->where('hms_opd_test_name.is_deleted!=2'); 
                        $this->db->where('hms_opd_test_name.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_test_name');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['test_name'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_test_name',$data);
                                //echo $this->db->last_query(); exit;
                        }

                    }
                              $test_data = array(
                        "prescription_id"=>$post['data_id'],
                        "test_name"=>$post['test_name'][$i]);
                    $this->db->insert('hms_dialysis_prescription_patient_test',$test_data); 
                    $test_data_id = $this->db->insert_id(); 
            }

            $this->db->where('prescription_id',$post['data_id']);
            $this->db->delete('hms_dialysis_prescription_patient_pres'); 

            //$total_prescription = count($post['medicine_name']);
            $total_prescription = count(array_filter($post['medicine_name']));  
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
                    
                                       //check and add masters 
                    if(!empty($post['medicine_name'][$i]))
                    {   
                        $this->db->select('hms_opd_medicine.*');  
                        //$this->db->from('hms_opd_medicine');
                        $this->db->where('hms_opd_medicine.medicine',$post['medicine_name'][$i]);  
                        $this->db->where('hms_opd_medicine.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine'=>$post['medicine_name'][$i],
                                                'type'=>$post['medicine_type'][$i],
                                                'salt'=>$post['salt'][$i],
                                                'brand'=>$post['brand'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine',$data);
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    //medicne type
                    if(!empty($post['medicine_type'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_type.*');
                        //$this->db->from('hms_opd_medicine_type');
                        $this->db->where('hms_opd_medicine_type.medicine_type',$post['medicine_type'][$i]); 
                        $this->db->where('hms_opd_medicine_type.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_type.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_type');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_type'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_type',$data);
                        }
                    }
                    //medicine dose
                    if(!empty($post['medicine_dose'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage.*');
                        //$this->db->from('hms_opd_medicine_dosage');
                        $this->db->where('hms_opd_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_dosage',$data);
                        }
                    }

                    //medicine duration
                    if(!empty($post['medicine_duration'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage_duration.*'); 
                        //$this->db->from('hms_opd_medicine_dosage_duration');
                        $this->db->where('hms_opd_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage_duration.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage_duration');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_dosage_duration',$data);
                        }
                    }

                    //medicine frequency
                    if(!empty($post['medicine_frequency'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage_frequency.*');
                        //$this->db->from('hms_opd_medicine_dosage_frequency');
                        $this->db->where('hms_opd_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage_frequency.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage_frequency');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_opd_medicine_dosage_frequency',$data);
                        }
                    }

                    //medicine advice
                    if(!empty($post['medicine_advice'][$i]))
                    {
                        $this->db->select('*');
                        //$this->db->from('hms_opd_advice');
                        $this->db->where('hms_opd_advice.medicine_advice',$post['medicine_advice'][$i]); 
                        $this->db->where('hms_opd_advice.is_deleted!=2'); 
                        $this->db->where('hms_opd_advice.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_advice');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_advice'=>$post['medicine_advice'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_opd_advice',$data);
                        }
                    }

                    $prescription_data = array(
                        "prescription_id"=>$post['data_id'],
                        "medicine_name"=>$medicine_name,
                        "medicine_brand"=>$medicine_brand,
                        "medicine_salt"=>$medicine_salt,
                        "medicine_type"=>$medicine_type,
                        "medicine_dose"=>$medicine_dose,
                        "medicine_duration"=>$medicine_duration,
                        "medicine_frequency"=>$medicine_frequency,
                        "medicine_advice"=>$medicine_advice
                        );
                    $this->db->insert('hms_dialysis_prescription_patient_pres',$prescription_data);
                    $test_data_id = $this->db->insert_id(); 
                    //echo $this->db->last_query(); exit;
                
            }
            ;

            if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00' && $post['next_appointment_date']!='30-11-0')
            {
               $booking_id = $post['booking_id'];
               $this->load->model('opd/opd_model');
               $opd_booking_data = $this->opd_model->get_by_id($booking_id);
                
               if(!empty($opd_booking_data))
               {
                  $booking_id = $opd_booking_data['id'];
                  $referral_doctor = $opd_booking_data['referral_doctor'];
                  $attended_doctor = $opd_booking_data['attended_doctor'];
                  $patient_id = $opd_booking_data['patient_id'];
                  $simulation_id = $opd_booking_data['simulation_id'];
                  $patient_code = $opd_booking_data['patient_code'];
                  $patient_name = $opd_booking_data['patient_name'];
                  $mobile_no = $opd_booking_data['mobile_no'];
                  $gender = $opd_booking_data['gender'];
                  $age_y = $opd_booking_data['age_y'];
                  $age_m = $opd_booking_data['age_m'];
                  $age_d = $opd_booking_data['age_d'];
                  $address = $opd_booking_data['address'];
                  $city_id = $opd_booking_data['city_id'];
                  $state_id = $opd_booking_data['state_id'];
                  $country_id = $opd_booking_data['country_id']; 
               }



                $appointment_code = generate_unique_id(20);
                $timestamp = $post['next_appointment_date'];
                $splitTimeStamp = explode(" ",$timestamp);
                $booking_date = $splitTimeStamp[0];
                $booking_time = $splitTimeStamp[1];

               $data_test = array(    
                        'branch_id'=> $user_data['parent_id'],
                        'parent_id'=>0,
                        'type'=>1,
                        'patient_id'=>$patient_id,
                        'referral_doctor'=>$referral_doctor,
                        'attended_doctor'=>$attended_doctor,
                        'booking_status'=>'0', 
                        'appointment_code'=>$appointment_code, 
                        'appointment_date'=>date('Y-m-d',strtotime($booking_date)),
                        'appointment_time'=>date('H:i:s',strtotime($booking_time)), 
                        'created_by'=>$user_data['id'],
                        'created_date'=>date('Y-m-d H:i:s')
                );

              


               //'sample_collected_by'=>$sample_collected_by,
               //'staff_refrenace_id'=>$staff_refrenace_id,
               //'booking_date'=>date('Y-m-d',strtotime($post['next_appointment_date']))
                /*$this->db->set('patient_id',$patient_id);
                $this->db->set('type',1);
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_opd_booking',$data_test);    
                $booking_id = $this->db->insert_id(); 
                */
                //echo $this->db->last_query(); exit;
            }



            if(!empty($post['data']))
            {  

                        $this->db->where('booking_id',$prescriptionid);
            $this->db->where('branch_id',$user_data['parent_id']);
                        $this->db->delete('hms_branch_vitals');
  
                $current_date = date('Y-m-d H:i:s');
                foreach($post['data'] as $key=>$val)
                {
                    
                    $data = array(
                                   "branch_id"=>$user_data['parent_id'],
                                   "type"=>2,
                                   "booking_id"=>$prescriptionid,
                                   "vitals_id"=>$key,
                                   "vitals_value"=>$val['name'],
                                   
                                  );
                  
                  $this->db->insert('hms_branch_vitals',$data);
                  $id = $this->db->insert_id();
                } 
            }


            return $prescriptionid;

        }
        else
        {

        } 
    
    }


    function template_format($data="",$branch_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_branch_dialysis_prescription_setting.*');
        $this->db->where($data);
        if(!empty($branch_id))
        {
            $this->db->where('hms_branch_dialysis_prescription_setting.branch_id = "'.$branch_id.'"');
        }
        else
        {
            $this->db->where('hms_branch_dialysis_prescription_setting.branch_id = "'.$users_data['parent_id'].'"');
        }
         
        $this->db->from('hms_branch_dialysis_prescription_setting');
        $result=$this->db->get()->row();
        return $result;

    }

     public function get_test_vals_old($vals="")
    {
        $response = array();
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');  
            $this->db->where('status','1'); 
            $this->db->order_by('test_name','ASC');
            $this->db->where('is_deleted',0);
            $this->db->where('test_name LIKE "'.$vals.'%"');
            $this->db->where('branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_opd_test_name');
            $result = $query->result(); 
            //echo $this->db->last_query();
            if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response[] = $vals->test_name;
                }
            }
            return $response; 
        } 
    }
    
    public function get_test_vals($vals="")
    {
        $response = array();
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');  
            $this->db->where('status','1'); 
            $this->db->order_by('test_name','ASC');
            $this->db->where('is_deleted',0);
            $this->db->where('test_name LIKE "'.$vals.'%"');
            $this->db->where('branch_id',$users_data['parent_id']);  
            $query = $this->db->get('path_test');
            $result = $query->result(); 
            //echo $this->db->last_query();
           /* if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response['test_id'] = $vals->id;
                   $response['test_name'] = $vals->test_name;
                }
            }
            return $response; */

            if(!empty($result))
            { 
                $data = array();
                foreach($result as $vals)
                {
                   $name = $vals->test_name.'|'.$vals->id;
                    array_push($data, $name);
                   //$response[] = $vals->medicine;
                }

                echo json_encode($data);
            }
        } 
    }

    public function get_dosage_vals($vals="")
    {
        $response = array();
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');  
            $this->db->where('status','1'); 
            $this->db->order_by('medicine_dosage','ASC');
            $this->db->where('is_deleted',0);
            $this->db->where('medicine_dosage LIKE "'.$vals.'%"');
            $this->db->where('branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_opd_medicine_dosage');
            $result = $query->result(); 
            //echo $this->db->last_query();
            if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response[] = $vals->medicine_dosage;
                }
            }
            return $response; 
        } 
    }

    public function get_duration_vals($vals="")
    {
        $response = array();
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');  
            $this->db->where('status','1'); 
            $this->db->order_by('medicine_dosage_duration','ASC');
            $this->db->where('is_deleted',0);
            $this->db->where('medicine_dosage_duration LIKE "'.$vals.'%"');
            $this->db->where('branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_opd_medicine_dosage_duration');
            $result = $query->result(); 
            //echo $this->db->last_query();
            if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response[] = $vals->medicine_dosage_duration;
                }
            }
            return $response; 
        } 
    }

    public function get_frequency_vals($vals="")
    {
        $response = array();
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');  
            $this->db->where('status','1'); 
            $this->db->order_by('medicine_dosage_frequency','ASC');
            $this->db->where('is_deleted',0);
            $this->db->where('medicine_dosage_frequency LIKE "'.$vals.'%"');
            $this->db->where('branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_opd_medicine_dosage_frequency');
            $result = $query->result(); 
            //echo $this->db->last_query();
            if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response[] = $vals->medicine_dosage_frequency;
                }
            }
            return $response; 
        } 
    }
 
 
    public function save_video()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->load->library('healthcloud');
        $post = $this->input->post();  
        //echo "<pre>"; print_r($post); exit;
        $doctor_mobile_no = $post['doctor_mobile_no'];
              $milliseconds = round(microtime(true) * 1000);
              $fields = array("patient_name"=>$post['patient_name'], "patient_unique_id"=>$post['patient_code'], "patient_phone"=>$post['patient_phone'],
"patient_country_code"=> "91", "patient_email"=>$post['patient_email'], "doctor_name"=>$post['doctor_name'],
"doctor_display_info"=>$post['qualification'],"hospital"=>$post['branch_name'], "appointment_utc_time"=>$milliseconds);

         $data_video = $this->healthcloud->get_video_url($fields);
         $data_video_obj = json_decode($data_video);  
         
         $serialId = $data_video_obj->serialId;
         $conversation_id = $data_video_obj->conversation_id;
         $patient_url = $data_video_obj->patient_url;
         $doctor_url = $data_video_obj->doctor_url;
         $passcode = $data_video_obj->passcode;
         $start_valid = $data_video_obj->start_valid;
         $finish_valid = $data_video_obj->finish_valid;
         $data_pay_test = array( 
                        'serialId'=>$serialId,
                        'conversation_id'=>$conversation_id,
                        'patient_url'=>$patient_url,
                        'doctor_url'=>$doctor_url,
                        'passcode'=>$passcode,
                        'start_valid'=>$start_valid,
                        'finish_valid'=>$finish_valid,
                        'booking_id'=>$post['booking_id'],
                        'prescription_id'=>$post['prescription_id'],
                        'prescription_id'=>$post['prescription_id'],
                        'branch_id' => $user_data['parent_id'],
                        );
         $data_testr = array_merge($fields, $data_pay_test);
             
        //echo "<pre>"; print_r($data_video); exit;
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_opd_video_consultation',$data_testr);  
            
            $doctor_msg = 'Hello '.$post['doctor_name'].', Your online video prescription with patient '.$post['patient_name'].' please click on this link to start the conversation '.$patient_url;
            
            $patient_msg = 'Hello '.$post['patient_name'].', Your online video prescription with Dr. '.$post['doctor_name'].' please click on this link to start the conversation '.$doctor_url;
            
            send_video_link_sms($doctor_mobile_no,$doctor_msg); 
            send_video_link_sms($post['patient_phone'],$patient_msg); 
            //exit;
            //echo $this->db->last_query(); die;
    }
    
    public function get_doctor_signature($doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $company_data = $this->session->userdata('company_data'); 
        $this->db->select('signature,sign_img'); 
        $this->db->where('doctor_id',$doctor_id);  
        $this->db->where('is_deleted',0);  
        if($users_data['users_role']==4)
        {
            $this->db->where('branch_id',$company_data['id']); 
        }
        else if($users_data['users_role']==3)
        {
            $this->db->where('branch_id',$company_data['id']); 
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']); 
        }
        $this->db->group_by('id');  
        $query = $this->db->get('hms_signature'); //hms_test_footer
        $result = $query->row(); 
        return $result; 
    }
    
    function template_format_letterhead($data="",$branch_id=''){
 
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_letter_head_template_setting.*');
       // $this->db->where($data);
        if(!empty($branch_id))
        {
        $this->db->where('hms_print_letter_head_template_setting.branch_id = "'.$branch_id.'"');
        }
        else
        {
        $this->db->where('hms_print_letter_head_template_setting.branch_id = "'.$users_data['parent_id'].'"');
        }
        $this->db->where('hms_print_letter_head_template_setting.unique_id =3'); 
        $this->db->from('hms_print_letter_head_template_setting');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();die();
        return $query;

    }
    
    public function get_detail_by_prescription_letterhead($booking_id="",$branch_id="")
    { //echo "dss"; die;
        $this->db->select("hms_dialysis_prescription.*,hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address,hms_patient.city_id,hms_patient.state_id,hms_patient.country_id,hms_patient.address,hms_patient.pincode,hms_dialysis_booking.booking_code,hms_dialysis_booking.doctor_id,hms_dialysis_booking.doctor_id,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.dialysis_time,hms_dialysis_prescription.patient_bp as patientbp,hms_dialysis_prescription.patient_temp as patienttemp,hms_dialysis_prescription.patient_weight as patientweight,hms_dialysis_prescription.patient_height as patientpr,hms_dialysis_prescription.patient_spo2 as patientspo,hms_dialysis_prescription.patient_rbs as patientrbs,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_gardian_relation.relation"); 



        $this->db->from('hms_dialysis_prescription'); 
        $this->db->where('hms_dialysis_prescription.booking_id',$booking_id);
        $this->db->where('hms_dialysis_prescription.branch_id',$branch_id);
        $this->db->join('hms_patient','hms_patient.id=hms_dialysis_prescription.patient_id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id=hms_dialysis_prescription.booking_id','left'); 
        $result_pre['prescription_list']= $this->db->get()->result();
        //echo $this->db->last_query(); exit;
        if(isset($result_pre['prescription_list']) && !empty($result_pre['prescription_list']))
        {
             $this->db->select('hms_dialysis_prescription_patient_test.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_test.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_test.prescription_id = "'.$result_pre['prescription_list'][0]->id.'"');
        $this->db->from('hms_dialysis_prescription_patient_test');
        $result_pre['prescription_list']['prescription_test_list']=$this->db->get()->result();

        $this->db->select('hms_dialysis_prescription_patient_pres.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_pres.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_pres.prescription_id = "'.$result_pre['prescription_list'][0]->id.'"');
        $this->db->from('hms_dialysis_prescription_patient_pres');
        $result_pre['prescription_list']['prescription_presc_list']=$this->db->get()->result();
        //echo $this->db->last_query(); exit;
        $this->db->select('simulation'); 
        $this->db->where('id',$result_pre['prescription_list'][0]->simulation_id);                   
        $query = $this->db->get('hms_simulation');
        $test_list = $query->result(); 
        $simulation='';
        foreach($test_list as $simulations)
            {
            $simulation = $simulations->simulation;
            } 

            $result_pre['prescription_list']['simulation_name']= $simulation; 

        /* attended doctor list */
            $doctor_name="";
            $users_data = $this->session->userdata('auth_users');
        if(!empty($result_pre['prescription_list'][0]->attended_doctor))
            {
                $this->db->select('hms_doctors.doctor_name as doctor_name');  
                $this->db->where('hms_doctors.id',$result_pre['prescription_list'][0]->attended_doctor); 
                $this->db->where('is_deleted !=2');
                $query = $this->db->get('hms_doctors');
                $result = $query->row(); 
            if(!empty($result->doctor_name))
                {
                    $doctor_name = $result->doctor_name;    
                }

            //echo $this->db->last_query();die;
            }    
            $result_pre['prescription_list']['attended_doctor']= $doctor_name; 


            if(!empty($result_pre['prescription_list'][0]->referral_doctor))
            {
                $this->db->select('hms_doctors.doctor_name as doctor_name');  
                $this->db->where('hms_doctors.id',$result_pre['prescription_list'][0]->referral_doctor); 
                $this->db->where('is_deleted !=2');
                $query = $this->db->get('hms_doctors');
                $result = $query->row(); 
            if(!empty($result->doctor_name))
                {
                    $doctor_name = $result->doctor_name;    
                }

            //echo $this->db->last_query();die;
            }    
            $result_pre['prescription_list']['referral_doctor']= $doctor_name; 


            $specialization_name="";
            $users_data = $this->session->userdata('auth_users');
            if(!empty($result_pre['prescription_list'][0]->specialization_id))
                {
                $this->db->select('hms_specialization.specialization as specialization');  
                $this->db->where('hms_specialization.id',$result_pre['prescription_list'][0]->specialization_id); 
                $query = $this->db->get('hms_specialization');
                $result = $query->row(); 
                $specialization_name = $result->specialization;
                //echo $this->db->last_query();die;
                } 
            $result_pre['prescription_list']['specialization_name']= $specialization_name;  
        }
        return $result_pre;

    }
    
   /* public function get_by_prescription_with_medicine_id($prescription_id)
    {
        

        $this->db->select('hms_dialysis_prescription_patient_pres.*');
        $this->db->join('hms_dialysis_prescription','hms_dialysis_prescription.id = hms_dialysis_prescription_patient_pres.prescription_id'); 
        $this->db->where('hms_dialysis_prescription_patient_pres.prescription_id = "'.$prescription_id.'"');
        $this->db->from('hms_dialysis_prescription_patient_pres');
        $result_pre=$this->db->get()->result();
        //echo $this->db->last_query(); exit;

        return $result_pre;

    }
    */
    
  public function get_by_prescription_with_medicine_id($prescription_id)
  { 
    $this->db->select('hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code, hms_dialysis_prescription_patient_pres.*, hms_medicine_entry.packing,hms_medicine_entry.mrp,(select hms_medicine_batch_stock.batch_no from hms_medicine_batch_stock where hms_medicine_batch_stock.medicine_id=hms_dialysis_prescription_patient_pres.medicine_id AND  hms_medicine_batch_stock.quantity>0 limit 1) as batchno,hms_medicine_entry.*,(select hms_medicine_batch_stock.quantity from hms_medicine_batch_stock where hms_medicine_batch_stock.medicine_id=hms_dialysis_prescription_patient_pres.medicine_id AND  hms_medicine_batch_stock.quantity>0 limit 1) as qty,(select hms_medicine_batch_stock.expiry_date from hms_medicine_batch_stock where hms_medicine_batch_stock.medicine_id=hms_dialysis_prescription_patient_pres.medicine_id AND  hms_medicine_batch_stock.quantity>0 limit 1) as expiry_date,(select hms_medicine_batch_stock.manuf_date from hms_medicine_batch_stock where hms_medicine_batch_stock.medicine_id=hms_dialysis_prescription_patient_pres.medicine_id AND  hms_medicine_batch_stock.quantity>0 limit 1) as  manuf_date,(select hms_medicine_batch_stock.per_pic_rate from hms_medicine_batch_stock where hms_medicine_batch_stock.medicine_id=hms_dialysis_prescription_patient_pres.medicine_id AND  hms_medicine_batch_stock.quantity>0 limit 1) as per_pic_price');
    $this->db->from('hms_dialysis_prescription_patient_pres'); 
    if(!empty($prescription_id))
    {
          $this->db->where('hms_dialysis_prescription_patient_pres.prescription_id',$prescription_id);
        
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_dialysis_prescription_patient_pres.medicine_id','left');
    }  
    $query1 = $this->db->get();
    //echo $this->db->last_query();die;
    $query = $query1->result(); 
    
    //echo '<pre>'; print_r($query);die; packing mrp 
    $result = []; 
    $total_price_medicine_amount='';

    if(!empty($query))
    {
      foreach($query as $medicine)  
      {   
          $result[$medicine->medicine_id.'.'.$medicine->batchno] = array('medicine_name'=>$medicine->medicine_name, 'medicine_code'=>$medicine->medicine_code, 'packing'=>$medicine->packing, 'mrp'=>$medicine->mrp, 'mid'=>$medicine->medicine_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batchno,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->per_pic_price, 'total_amount'=>$medicine->per_pic_price,'bar_code'=>'','total_pricewith_medicine'=>0); 
          $tamt = 0;
      } 
    } 
    //die;
    //print '<pre>';print_r($result);die;
    return $result;
  }
         

    
} 
?>