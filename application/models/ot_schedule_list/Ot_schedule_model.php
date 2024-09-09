<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ot_schedule_model extends CI_Model {

    var $table = 'hms_operation_booking';
    var $column = array('hms_operation_booking.id','hms_patient.patient_code','hms_patient.patient_name','hms_operation_booking.booking_code','hms_ipd_rooms.room_no','hms_doctors.doctor_name','hms_operation_booking.operation_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $search = $this->session->userdata('ot_booking_serach');
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_operation_booking.*,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_ot_room.room_no as ot_room_no"); 
        $this->db->from($this->table); 
        $this->db->where('hms_operation_booking.is_deleted','0');
        if($users_data['users_role']==4)
        {
        $this->db->where('hms_operation_booking.patient_id = "'.$users_data['parent_id'].'"');
        }
        elseif($users_data['users_role']==3)
        {
            $this->db->where('hms_operation_booking.referral_doctor = "'.$users_data['parent_id'].'"');
        }
        else
        {
        $this->db->where('hms_operation_booking.branch_id = "'.$users_data['parent_id'].'"');   
        }
        $this->db->join('hms_ot_room','hms_ot_room.id=hms_operation_booking.ot_room_no','left');
        $this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_operation_booking.ipd_id','left'); 
         $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.doctor_id','left');
         $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');
        $i = 0;

        if(isset($search) && !empty($search))
        {

        if(isset($search['start_datetime']) && !empty($search['start_datetime']))
        {
        $start_date = date('Y-m-d',strtotime($search['start_datetime']));
        $start_time= date('H:i:s',strtotime($search['start_datetime']));
        $this->db->where('hms_operation_booking.operation_date >= "'.$start_date.'" AND hms_operation_booking.operation_time >= "'.$start_time.'"');
        
        }

        if(isset($search['end_datetime']) &&  !empty($search['end_datetime']))
        {
        $end_date = date('Y-m-d',strtotime($search['end_datetime']));
        $end_time = date('H:i:s',strtotime($search['end_datetime']));
        $this->db->where('hms_operation_booking.operation_date <= "'.$end_date.'"  AND hms_operation_booking.operation_end_time >= "'.$end_time.'"');
        }
         
     
        

        }

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


        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_operation_booking.created_by IN ('.$emp_ids.')');
        }
    
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

    public function search_report_data()
    {
        $search = $this->session->userdata('ot_booking_serach');
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_operation_booking.*,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_category.room_category as room_type, hms_patient.patient_name as p_name,hms_patient.patient_code as p_code,hms_ot_room.room_no as ot_room_no"); 
        $this->db->from($this->table); 
        $this->db->where('hms_operation_booking.is_deleted','0');
        if($users_data['users_role']==4)
        {
        $this->db->where('hms_operation_booking.patient_id = "'.$users_data['parent_id'].'"');
        }
        elseif($users_data['users_role']==3)
        {
            $this->db->where('hms_operation_booking.referral_doctor = "'.$users_data['parent_id'].'"');
        }
        else
        {
        $this->db->where('hms_operation_booking.branch_id = "'.$users_data['parent_id'].'"');   
        }
         $this->db->join('hms_ot_room','hms_ot_room.id=hms_operation_booking.ot_room_no','left');
        $this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_operation_booking.ipd_id','left'); 
         $this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.doctor_id','left');
         $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');
        $i = 0;

        if(isset($search) && !empty($search))
        {

        if(isset($search['start_datetime']) && !empty($search['start_datetime']))
        {
        $start_date = date('Y-m-d',strtotime($search['start_datetime']));
        $start_time= date('H:i:s',strtotime($search['start_datetime']));
        $this->db->where('hms_operation_booking.operation_date >= "'.$start_date.'" AND hms_operation_booking.operation_time >= "'.$start_time.'"');
        
        }

        if(isset($search['end_datetime']) &&  !empty($search['end_datetime']))
        {
        $end_date = date('Y-m-d',strtotime($search['end_datetime']));
        $end_time = date('H:i:s',strtotime($search['end_datetime']));
        $this->db->where('hms_operation_booking.operation_date <= "'.$end_date.'"  AND hms_operation_booking.operation_end_time >= "'.$end_time.'"');
        }

       }

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


        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_operation_booking.created_by IN ('.$emp_ids.')');
        }
        return $this->db->get()->result();
    
        
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
     //   echo $this->db->last_query();die;
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
    
    /*public function ot_pacakge_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('name','ASC'); 
        $query = $this->db->get('hms_operation_booking');
        return $query->result();
    }*/

    public function get_by_id($id)
    {
        $this->db->select('hms_operation_booking.*,hms_ot_pacakge.name as package_name,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_ipd_rooms.room_no');
        $this->db->from('hms_operation_booking'); 
        $this->db->where('hms_operation_booking.id',$id);
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_operation_booking.ipd_id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ot_pacakge','hms_ot_pacakge.id=hms_operation_booking.package_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
        $this->db->where('hms_operation_booking.is_deleted','0');
        $query = $this->db->get(); 
        $result= $query->row_array();
         return $result;
    }
    public function get_patient_by_id($id)
    {

        $this->db->select('hms_patient.*');
        $this->db->from('hms_patient'); 
        $this->db->where('hms_patient.id',$id);
        //$this->db->where('hms_medicine_vendors.is_deleted','0');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->row_array();
    }
    
    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        $data_patient = array(
                                "patient_code"=>$post['patient_reg_code'],
                                "patient_name"=>$post['name'],
                                'simulation_id'=>$post['simulation_id'],
                                'branch_id'=>$user_data['parent_id'],
                                'gender'=>$post['gender'],
                                "age_y"=>$post['age_y'],
                                "age_m"=>$post['age_m'],
                                "age_d"=>$post['age_d'],
                                "address"=>$post['address'],
                                'mobile_no'=>$post['mobile_no']);
        
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            if(isset($post['pacakage_name'])  && $post['op_type']==2)
            {
                
                $p_hours= $this->get_package_hours($post['pacakage_name']);
                $time_n=date('H:i:s',strtotime($post['operation_time']));
                $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                
                
            }
             if(isset($post['operation_name'])  && $post['op_type']==1)
            {
                
                $p_hours= $this->get_operation_hours($post['operation_name']);  
                $time_n=date('H:i:s',strtotime($post['operation_time']));
                $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
            }
            

            $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'patient_id'=>$post['patient_id'],
                    'booking_code'=>$post['ot_booking_code'],
                    'ipd_id'=>$post['ipd_id'],
                    'operation_name'=>$post['operation_name'],
                    'op_type'=>$post['op_type'],
                    'operation_end_time'=>$time,
                    'ot_room_no'=>$post['operation_room'],
                    'package_id'=>$post['pacakage_name'],
                    'operation_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                    'operation_time'=>$post['operation_time'],
                    'remarks'=>$post['remarks'],
                    'referred_by'=>$post['referred_by'],
                    'referral_doctor'=>$post['referral_doctor'],
                    'referral_hospital'=>$post['referral_hospital'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
             //print_r($data);die;
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id', $post['patient_id']);
            $this->db->update('hms_patient',$data_patient);


            $this->db->where(array('operation_id'=>$post['data_id']));
            $this->db->delete('hms_operation_to_doctors');
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_operation_booking',$data);  
            //echo $this->db->last_query();die;

            //pacakage_name as package id
            if(!empty($post['data_id']) && !empty($post['pacakage_name']) && $post['pacakage_name']!='0.00') 
            {
                
                    $this->db->where('ot_id',$post['data_id']);
                    $this->db->where('ipd_id',$post['ipd_id']);
                    $this->db->where('patient_id',$post['patient_id']);
                    $this->db->delete('hms_ipd_patient_to_charge');
                    
                    $package_charge = get_ot_package_charge($post['pacakage_name']);
                    $amount = $package_charge[0]['amount'];
                    //echo "<pre>";print_r($package_charge); exit;
                    $ot_charge = array(
                        "branch_id"=>$user_data['parent_id'],
                        'ipd_id'=>$post['ipd_id'],
                        'patient_id'=>$post['patient_id'],
                        'ot_id'=>$post['data_id'],
                        'ot_package_id'=>$post['pacakage_name'],
                        'type'=>6,
                        'quantity'=>1,
                        'start_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                        'end_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                        'particular'=>$post['operation_name'].' (OT)',
                        'price'=>$amount,
                        'panel_price'=>$amount,
                        'net_price'=>$amount,
                        'status'=>1,
                        'created_date'=>date('Y-m-d H:i:s')
                );
            $this->db->insert('hms_ipd_patient_to_charge',$ot_charge);
            }   


            $post_doctor= count($post['doctor_names']);
            foreach($post['doctor_names'] as $key=>$value)
            {
                $doctor_array=array('operation_id'=>$post['data_id'],'doctor_id'=>$key,'doctor_name'=>$value[0]);
                $this->db->insert('hms_operation_to_doctors',$doctor_array);
                
            } 
            $ot_book_id=$post['data_id'];

        }
        else
        {    
           
               $patient_data= $this->get_patient_by_id($post['patient_id']);

               if(isset($post['pacakage_name'])  && $post['op_type']==2)
                {
                    $p_hours= $this->get_package_hours($post['pacakage_name']);
                    //print_r($p_hours);die;
                    $time_n=date('H:i:s',strtotime($post['operation_time']));
                    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                    
                }
                 if(isset($post['operation_name'])  && $post['op_type']==1)
                {
                    $p_hours= $this->get_operation_hours($post['operation_name']);  
                    $time_n=date('H:i:s',strtotime($post['operation_time']));
                    $time = date('H:i:s', strtotime($time_n.'+'.$p_hours[0]->hours.' hour'));
                }
                
            if(count($patient_data)>0)
            {
                $this->db->set('modified_by',$user_data['id']);
                $this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->where('id',$post['patient_id']);
                $this->db->update('hms_patient',$data_patient);
                $patient_id= $post['patient_id'];
                $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'patient_id'=>$patient_id,
                    'booking_code'=>$post['ot_booking_code'],
                    'op_type'=>$post['op_type'],
                    'ipd_id'=>$post['ipd_id'],
                    'operation_name'=>$post['operation_name'],
                    'package_id'=>$post['pacakage_name'],
                    'ot_room_no'=>$post['operation_room'],
                    'operation_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                    'operation_end_time'=>$time,
                    'operation_time'=>$post['operation_time'],
                    'remarks'=>$post['remarks'],
                    'referred_by'=>$post['referred_by'],
                    'referral_doctor'=>$post['referral_doctor'],
                    'referral_hospital'=>$post['referral_hospital'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        
            }
            else
            {
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_patient',$data_patient); 
                $patient_id= $this->db->insert_id();
                //echo $this->db->last_query();die;
                $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'patient_id'=>$patient_id,
                    'booking_code'=>$post['ot_booking_code'],
                    'ot_room_no'=>$post['operation_room'],
                    'op_type'=>$post['op_type'],
                    'ipd_id'=>$post['ipd_id'],
                    'operation_end_time'=>$time,
                    'operation_name'=>$post['operation_name'],
                    'package_id'=>$post['pacakage_name'],
                    'operation_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                    'operation_time'=>$post['operation_time'],
                    'remarks'=>$post['remarks'],
                    'referred_by'=>$post['referred_by'],
                    'referral_doctor'=>$post['referral_doctor'],
                    'referral_hospital'=>$post['referral_hospital'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        
            }
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_operation_booking',$data); 
            $last_id= $this->db->insert_id();
            //echo $this->db->last_query();die;
            $ot_book_id=$last_id;
            //pacakage_name as package id
            if(!empty($ot_book_id) && !empty($post['pacakage_name']) && $post['pacakage_name']!='0.00') 
            {
                $package_charge = get_ot_package_charge($post['pacakage_name']);
                $amount = $package_charge[0]['amount'];
                //echo "<pre>";print_r($package_charge); exit;
                $ot_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$post['ipd_id'],
                    'patient_id'=>$patient_id,
                    'ot_id'=>$ot_book_id,
                    'ot_package_id'=>$post['pacakage_name'],
                    'type'=>6,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                    'end_date'=>date('Y-m-d',strtotime($post['operation_date'])),
                    'particular'=>$post['operation_name'].' (OT)',
                    'price'=>$amount,
                    'panel_price'=>$amount,
                    'net_price'=>$amount,
                    'status'=>1,
                    'created_date'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('hms_ipd_patient_to_charge',$ot_charge);
            }

            foreach($post['doctor_names'] as $key=>$value)
            {
                $doctor_array=array('operation_id'=>$last_id,
                    'doctor_id'=>$key,
                    'doctor_name'=>$value[0]
                    );
                
                $this->db->insert('hms_operation_to_doctors',$doctor_array);
                
            }  

                   
        } 
        return $ot_book_id; 
    }

    public function get_package_hours($package_name='')
    {   

        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('id',$package_name); 
        $query = $this->db->get('hms_ot_pacakge')->result();
        //print_r($query);die;
        return $query;
    }

    public function ot_room_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $query = $this->db->get('hms_ot_room')->result();
        //print_r($query);die;
        return $query;
    }

    public function get_operation_hours($op_name='')
    {   

        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('id',$op_name); 
        $query = $this->db->get('hms_ot_management')->result();
        //print_r($query);die;
        return $query;
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
            $this->db->update('hms_operation_booking');
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
            $this->db->update('hms_operation_booking');
            //echo $this->db->last_query();die;
        } 
    }

    public function get_vals($vals="")
    {
        $response = '';
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');  
            $this->db->where('status','1'); 
            $this->db->order_by('medicine_type','ASC');
            $this->db->where('is_deleted',0);
           // $this->db->where('medicine_type LIKE "'.$vals.'%"');
            $this->db->where('branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_operation_booking');
            $result = $query->result(); 
            //echo $this->db->last_query();
            if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response[] = $vals->medicine_type;
                }
            }
            return $response; 
        } 
    }
    public function get_patient_by_id_with_ipd_detail($id)
    {

        $this->db->select('hms_patient.*,hms_patient.id as p_id,hms_ipd_booking.id as ipd_id,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_ipd_rooms.room_no');
        $this->db->from('hms_patient'); 
        $this->db->where('hms_ipd_booking.patient_id',$id);
        $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id=hms_patient.id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->row_array();
    }

    public function pacakage_list(){
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('name','ASC'); 
        $query = $this->db->get('hms_ot_pacakge');
        return $query->result();
    }
    public function remarks_list(){
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('remarks','ASC'); 
        $query = $this->db->get('hms_ot_remarks')->result();
        //print_r($query);die;
        return $query;
    }

    
      public function get_doctor_name($vals="")
    {
        $response = '';
        if(!empty($vals))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('*');   
            $this->db->order_by('hms_doctors.doctor_name','ASC');
            $this->db->where('hms_doctors.is_deleted',0); 
            $this->db->where('hms_doctors.doctor_type IN (0,2)'); 
            $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
            $query = $this->db->get('hms_doctors');
            $result = $query->result(); 

            //echo $this->db->last_query();
            if(!empty($result))
            { 
                $data = array();
                foreach($result as $vals)
                {
                    //$response[] = $vals->medicine_name;
                    $name = $vals->doctor_name.'|'.$vals->id;
                    array_push($data, $name);
                }

                echo json_encode($data);
            }
            //return $response; 
        } 
    }
    function doctor_list_by_otids($id){
        $this->db->select('hms_operation_to_doctors.*');
        $this->db->from('hms_operation_to_doctors'); 
        $this->db->where('hms_operation_to_doctors.operation_id',$id);
        
        $query = $this->db->get()->result();
        $data=array(); 
        foreach($query as $res){
            $data[$res->doctor_id][]=$res->doctor_name;
        }
        return $data;
    
    }

      function get_all_detail_print($ids="",$branch_ids=""){
        $result_operation=array();
        $this->db->select('hms_operation_booking.*,hms_ot_pacakge.remarks as pacakge_remarks,hms_ot_pacakge.type as pacakge_type,hms_ot_pacakge.amount as package_amount,hms_ot_pacakge.days,hms_ot_pacakge.name as package_name,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_ipd_rooms.room_no,hms_simulation.simulation,hms_ipd_room_to_bad.bad_no as bed_no');
        $this->db->from('hms_operation_booking'); 
        $this->db->where('hms_operation_booking.id',$ids);
        $this->db->where('hms_operation_booking.branch_id',$branch_ids);
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_operation_booking.ipd_id','left');

        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
        
        $this->db->join('hms_ot_pacakge','hms_ot_pacakge.id=hms_operation_booking.package_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_operation_booking.patient_id','left');
        $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
        
        $this->db->where('hms_operation_booking.is_deleted','0');
        $query = $this->db->get(); 
        $result_operation['operation_list']= $query->result();
        $this->db->select('hms_operation_to_doctors.*'); 
        $this->db->where('hms_operation_to_doctors.operation_id = "'.$ids.'"');
        $this->db->from('hms_operation_to_doctors');
        $result_operation['operation_list']['doctor_list']=$this->db->get()->result();
        return $result_operation;
        
    }
     function template_format($data=""){
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_branch_template.*');
        $this->db->where($data);
        $this->db->from('hms_print_branch_template');
        $query=$this->db->get()->row();
        //print_r($query);exit;
        return $query;

    }
    public function operation_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_ot_management.*');
        $this->db->where('status',1);
        $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->from('hms_ot_management');
        $query=$this->db->get()->result();
        //print_r($query);exit;
        return $query;  
    }

    public function ot_scheduled_time($operation_date="",$start_datetime="",$end_datetime="",$operation_room="")
    {
        

        // // echo '<br/>';
        // // echo  $end_time;
        // // echo '<br>';
        // //exit;
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_operation_booking.*,CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_time) as ot_start_datetime, CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_end_time) as ot_end_datetime ');
        $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        //$this->db->where('ot_room_no',$operation_room);
        //$this->db->where('ot_start_datetime',$operation_date);
        //$this->db->where('operation_time',$operation_date);
        $this->db->from('hms_operation_booking');
        $query=$this->db->get()->result();
        $msg=array();
       //print '<pre>'; print_r($query);die;
        foreach($query as $res)
        {
          if($res->operation_date == $operation_date  && $res->ot_room_no == $operation_room && ($start_datetime >=$res->ot_start_datetime && $start_datetime <= $res->ot_end_datetime))
          {
              $msg['error']=1;
          }
          else
          {
            $msg['error']=2;
          }
        }
        return $msg;
        
         
    }


    public function ot_scheduled_doctor($operation_date="",$start_datetime="",$end_datetime="",$doctors="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_operation_booking.*,CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_time) as ot_start_datetime, CONCAT(hms_operation_booking.operation_date," ",hms_operation_booking.operation_end_time) as ot_end_datetime ');
        $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        //$this->db->where('ot_room_no',$operation_room);
        //$this->db->where('ot_start_datetime',$operation_date);
        //$this->db->where('operation_time',$operation_date);
        $this->db->from('hms_operation_booking');
        $query=$this->db->get()->result();
        $msg=array();
        $new_val=array();
        $doctor=array();
       //print '<pre>'; print_r($query);die;
        foreach($query as $res)
        {

         if($res->operation_date == $operation_date && ($start_datetime >=$res->ot_start_datetime && $start_datetime <= $res->ot_end_datetime))
          {
           

            foreach($doctors as $key=>$value)
            {
                $new_val[]=$key;
                    
            }

              $this->db->select('hms_operation_to_doctors.*');
              $this->db->where("hms_operation_to_doctors.doctor_id IN (".implode(',',$new_val).")");
              $this->db->where('hms_operation_to_doctors.operation_id',$res->id);
              $result_doctor= $this->db->get('hms_operation_to_doctors')->result();
              
              if(!empty($result_doctor))
              {
                $doctor['error']=4;
              }
              else
              {
                $doctor['error']=5;
              }
           
          }
          else
          {
            $msg['error']=2;
             
          }
        }
         
        return array('doctor_error'=>$doctor['error'],'error'=>$msg['error']);
    }

    

}
?>