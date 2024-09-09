<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document_reports_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    public function document_report_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        if(!empty($get))
        {  
             
            $this->db->select("hms_ambulance_vehicle_documents.*,hms_ambulance_document.document,hms_ambulance_vehicle.vehicle_no"); 
            $this->db->join('hms_ambulance_document',"hms_ambulance_document.id=hms_ambulance_vehicle_documents.document_id",'left'); 
            $this->db->join('hms_ambulance_vehicle',"hms_ambulance_vehicle.id=hms_ambulance_vehicle_documents.vehicle_id",'left'); 

            $this->db->where('hms_ambulance_vehicle_documents.branch_id',$users_data['parent_id']);  

            if(!empty($get['create_from']))
            {
               $create_from=date('Y-m-d',strtotime($get['create_from'])).' 00:00:00';
               $this->db->where('hms_ambulance_vehicle_documents.created_date >= "'.$create_from.'"');
            }
            if(!empty($get['create_to']))
            {
                $create_to=date('Y-m-d',strtotime($get['create_to'])).' 23:59:59';
                $this->db->where('hms_ambulance_vehicle_documents.created_date <= "'.$create_to.'"');
            }
            if(!empty($get['renewal_from']))
            {
               $renewal_from=date('Y-m-d',strtotime($get['renewal_from']));
               $this->db->where('hms_ambulance_vehicle_documents.renewal_date >= "'.$renewal_from.'"');
            }
            if(!empty($get['renewal_to']))
            {
                $renewal_to=date('Y-m-d',strtotime($get['renewal_to']));
                $this->db->where('hms_ambulance_vehicle_documents.renewal_date <= "'.$renewal_to.'"');
            }
                if(!empty($get['exp_from']))
            {
               $exp_from=date('Y-m-d',strtotime($get['exp_from']));
               $this->db->where('hms_ambulance_vehicle_documents.expiry_date >= "'.$exp_from.'"');
            }
            if(!empty($get['exp_to']))
            {
                $exp_to=date('Y-m-d',strtotime($get['exp_to']));
                $this->db->where('hms_ambulance_vehicle_documents.expiry_date <= "'.$exp_to.'"');
            }

            if(!empty($get['vehicle_id']))
            {
               $this->db->where('hms_ambulance_vehicle_documents.vehicle_id',$get['vehicle_id']);
            }
            if(!empty($get['document_id']))
            {
                $this->db->where('hms_ambulance_vehicle_documents.document_id',$get['document_id']);
            }
            if(!empty($get['remark']))
            {
                $this->db->where('hms_ambulance_vehicle_documents.remarks LIKE "%'.$get['remark'].'%"');
            }
            $this->db->from('hms_ambulance_vehicle_documents');
            $query = $this->db->get(); 
            return $query->result_array();
        } 
    }
   
   public function vehicle_list(){
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('id, vehicle_no');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1);  
        $query = $this->db->get('hms_ambulance_vehicle');
        return $query->result();
   }
   public function document_list(){
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('id,document');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->order_by('document','ASC'); 
        $query = $this->db->get('hms_ambulance_document');
        return $query->result();
   }
}
?>