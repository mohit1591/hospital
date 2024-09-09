<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bloodbank_collection_report_model extends CI_Model {

    //var $table = 'hms_medicine_sale';
    var $table = 'hms_payment';
    var $column = array('hms_patient.patient_name');  
    var $order = array('hms_payment.id' => 'desc');
    

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');

        $search_data = $this->session->userdata('bloodbank_collection_search_data');
           /* for user code */
        $this->load->model('general/general_model');


        $this->db->select("hms_blood_patient_to_recipient.id, hms_blood_patient_to_recipient.issue_code,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_blood_patient_to_recipient.requirement_date,  (CASE WHEN hms_payment.balance>0 THEN hms_blood_patient_to_recipient.net_amount ELSE '0.00' END) total_amount,(CASE WHEN hms_payment.balance > 0 THEN (hms_blood_patient_to_recipient.discount_amount) ELSE '0.00' END) as discount,(CASE WHEN hms_payment.balance > 0 THEN (hms_blood_patient_to_recipient.discount_amount) ELSE '0.00' END) as discount,  
            (CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_blood_patient_to_recipient.referred_by=0 THEN concat('Other ',hms_blood_patient_to_recipient.doctor_id) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name
            , hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 10 AND payment.parent_id = hms_blood_patient_to_recipient.id) ELSE '0.00' END) as balance");  
     
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id = hms_payment.parent_id AND hms_blood_patient_to_recipient.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_blood_patient_to_recipient.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_blood_patient_to_recipient.doctor_id','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',10);
        $this->db->where('hms_blood_patient_to_recipient.is_deleted',0);




        if(isset($search_data['branch_id']) && $search_data['branch_id']!='')
        {
        $this->db->where('hms_payment.branch_id IN ('.$search_data['branch_id'].')');
        }
        else
        {
        $this->db->where('hms_payment.branch_id = "'.$users_data['parent_id'].'"');
        }
        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }

             if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }

             if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }

            if($search_data['referred_by']=='1' && !empty($search_data['referral_hospital']))
            {
                $this->db->where('hms_blood_patient_to_recipient.hospital_id' ,$search_data['referral_hospital']);
            }
            elseif($search_data['referred_by']=='0' && !empty($search_data['refered_id']))
            {
                $this->db->where('hms_blood_patient_to_recipient.doctor_id' ,$search_data['refered_id']);
            }
            
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


    function get_datatables($branch_id='')
    {
        $this->_get_datatables_query($branch_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
     
        return $query->result();
    }

    function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('bloodbank_collection_search_data'); 
              /* for user code */
        $this->load->model('general/general_model');
        $emp_ids='';
        $access_users_label=array();
        if($users_data['emp_id']>0)
        {
         
            $get_access_user_with_user= $this->general_model->get_access_user($users_data['id']);
            //print '<pre>'; print_r($get_access_user_with_user);die;
            if(isset($get_access_user_with_user) && !empty($get_access_user_with_user) && count($get_access_user_with_user)>0)
             {
                    foreach($get_access_user_with_user as $access_users)
                    {
                       $access_users_label[]=  $access_users->access_id;
                    }
                    $emp_ids= implode(',',$access_users_label).','.$users_data['id'];
              } 
              else
              {
                    $emp_ids= $users_data['id'];
              }


             //print '<pre>'; echo $emp_ids;die;
        }
        else
        {

              $emp_ids=  $search_data["employee"];
        }
      
         $this->db->select("hms_blood_patient_to_recipient.id, hms_blood_patient_to_recipient.issue_code,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_blood_patient_to_recipient.requirement_date,  (CASE WHEN hms_payment.balance>0 THEN hms_blood_patient_to_recipient.net_amount ELSE '0.00' END) total_amount,(CASE WHEN hms_payment.balance > 0 THEN (hms_blood_patient_to_recipient.discount_amount) ELSE '0.00' END) as discount,(CASE WHEN hms_payment.balance > 0 THEN (hms_blood_patient_to_recipient.discount_amount) ELSE '0.00' END) as discount,  
            (CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_blood_patient_to_recipient.referred_by=0 THEN concat('Other ',hms_blood_patient_to_recipient.doctor_id) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name
            , hms_patient.patient_name,hms_patient.id as patient_new_id, (CASE WHEN hms_payment.balance > 0 THEN (select sum(credit)-sum(debit) from hms_payment as payment where payment.section_id = 10 AND payment.parent_id = hms_blood_patient_to_recipient.id) ELSE '0.00' END) as balance");  
     
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id = hms_payment.parent_id AND hms_blood_patient_to_recipient.is_deleted !=2'); 
        $this->db->join('hms_patient','hms_patient.id = hms_blood_patient_to_recipient.patient_id');
        $this->db->join('hms_doctors','hms_doctors.id = hms_blood_patient_to_recipient.doctor_id','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');
        $this->db->from($this->table); 
        $this->db->where('hms_payment.section_id','10');
        if(isset($search_data['branch_id']) && $search_data['branch_id']!='')
        {
        $this->db->where('hms_payment.branch_id IN ('.$search_data['branch_id'].')');
        }
        else
        {
        $this->db->where('hms_payment.branch_id = "'.$users_data['parent_id'].'"');
        }
        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }
            if(isset($emp_ids) && !empty($emp_ids))
            { 
                $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
            }

             if(isset($search_data['patient_name']) && !empty($search_data['patient_name'])
                )
            { 
                $this->db->where('hms_patient.patient_name LIKE "'.$search_data["patient_name"].'%"');
            }

             if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_patient.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }

        }
        //$this->db->limit($_POST['length'], $_POST['start']);
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
  

   

}
?>