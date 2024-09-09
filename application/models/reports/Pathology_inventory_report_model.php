<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_inventory_report_model extends CI_Model {

    var $table = 'path_test';
    var $column = array('path_test_booking.lab_reg_no','path_test_booking.booking_date', 'hms_patient.patient_name','hms_doctors.doctor_name','hms_department.department','path_test_booking.total_amount','path_test_booking.discount','path_test_booking.net_amount','path_test_booking.paid_amount','path_test_booking.balance');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('search_data'); 
        //echo '<pre>'; print_r($search_data);die;
        $this->db->select("path_test_to_inventory_item.*, path_test_booking.lab_reg_no, path_test_booking.booking_date, hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.patient_code,
            (CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,path_item.item");
        $this->db->from('path_test_to_inventory_item');
        $this->db->join('path_test_booking','path_test_booking.id = path_test_to_inventory_item.booking_id','left');
     
        $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 
        $this->db->join('hms_department','hms_department.id = path_test_booking.dept_id','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
        $this->db->join('path_item','path_item.id=path_test_to_inventory_item.item_id','left');
      
       $this->db->where('path_test_to_inventory_item.booking_id!=',0);
        if(isset($search_data) && !empty($search_data))
        {
          if(isset($search_data['start_date']) && !empty($search_data['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                $this->db->where('path_test_booking.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                )
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                $this->db->where('path_test_booking.created_date <= "'.$end_date.'"');
            }
            if(isset($search_data['item_name']) && !empty($search_data['item_name']))
            { 
                $this->db->where('path_item.id',$search_data["item_name"]);
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
            $column[$i] = $item;  // set column array variable to order processing
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
       //echo $this->db->last_query();die;
        return $query->result();
    }

    function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('search_data'); 
        //echo '<pre>'; print_r($search_data);die;
        $this->db->select("path_test_to_inventory_item.*, path_test_booking.lab_reg_no, path_test_booking.booking_date, hms_doctors.doctor_name, hms_patient.patient_name,hms_patient.patient_code,
            (CASE WHEN path_test_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN path_test_booking.referral_doctor=0 THEN concat('Other ',path_test_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END) END) as doctor_hospital_name,path_item.item");
        $this->db->from('path_test_to_inventory_item');
        $this->db->join('path_test_booking','path_test_booking.id = path_test_to_inventory_item.booking_id','left');
     
        $this->db->join('hms_patient','hms_patient.id = path_test_booking.patient_id'); 
        $this->db->join('hms_department','hms_department.id = path_test_booking.dept_id','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = path_test_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = path_test_booking.referral_hospital','left');
        $this->db->join('path_item','path_item.id=path_test_to_inventory_item.item_id','left');
      
       $this->db->where('path_test_to_inventory_item.booking_id!=',0);
            if(isset($search_data) && !empty($search_data))
            {
              if(isset($search_data['start_date']) && !empty($search_data['start_date']))
                {
                    $start_date = date('Y-m-d',strtotime($search_data['start_date'])).' 00:00:00';
                    $this->db->where('path_test_booking.created_date >= "'.$start_date.'"');
                }

                if(isset($search_data['end_date']) && !empty($search_data['end_date'])
                    )
                {
                    $end_date = date('Y-m-d',strtotime($search_data['end_date']))." 23:59:59";
                    $this->db->where('path_test_booking.created_date <= "'.$end_date.'"');
                }
                if(isset($search_data['item_name']) && !empty($search_data['item_name']))
                { 
                    $this->db->where('path_item.id',$search_data["item_name"]);
                }
            }
        
        //echo $this->db->last_query();die;
        $query = $this->db->get(); 
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