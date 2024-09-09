<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donor_report_model extends CI_Model {

    //var $table = 'hms_medicine_sale';
    var $table = 'hms_payment';
    var $column = array('hms_patient.patient_name','hms_blood_donor.donor_name','hms_blood_donor.donor_code','hms_blood_donor.mobile_no','hms_blood_donor.registration_date','hms_blood_donor.blood_group');  
    //var $order = array('hms_payment.id' => 'desc');
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('donor_report_search_data');
        
        $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.blood_group_id ,hms_blood_donor.donor_code,hms_blood_donor.mobile_no,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_code,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood"); 
        $this->db->from('hms_blood_stock');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        
        //$this->db->where('hms_blood_stock.donor_id',$donor_id);
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"');
        
        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                $this->db->where('hms_blood_donor.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_donor.created_date <= "'.$end_date.'"');
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
             if(!empty($search_data['component_id']))
            {
                $this->db->where('hms_blood_stock.component_id' ,$search_data['component_id']);
            }

            
            
        }
        
        $this->db->where('hms_blood_stock.flag' ,1);
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
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('donor_report_search_data'); 
        
        $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.blood_group_id ,hms_blood_donor.donor_code,hms_blood_donor.mobile_no,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_code,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood"); 
        $this->db->from('hms_blood_stock');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ','left');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        
        //$this->db->where('hms_blood_stock.donor_id',$donor_id);
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"');
        
         if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                $this->db->where('hms_blood_donor.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_donor.created_date <= "'.$end_date.'"');
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
            
             if(!empty($search_data['component_id']))
            {
                $this->db->where('hms_blood_stock.component_id' ,$search_data['component_id']);
            }
            
            
            
        }
        
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