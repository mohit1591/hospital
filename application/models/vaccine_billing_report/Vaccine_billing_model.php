<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccine_billing_model extends CI_Model {

	var $table = 'hms_vaccination_entry';
    var $column = array('hms_vaccination_entry.id','hms_vaccination_entry.vaccination_name', 'hms_vaccination_sale.sale_date', 'hms_vaccination_sale.net_amount','hms_vaccination_sale.paid_amount','hms_vaccination_sale.balance','hms_patient.patient_name');  
    var $order = array('hms_vaccination_entry.vaccination_name'); 

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

    function vaccine_bill_list($get="")
    {
        $user_data = $this->session->userdata('auth_users');
        $branch_id=$user_data['parent_id'];
        $this->db->select("hms_vaccination_entry.vaccination_name,hms_vaccination_sale.sale_date, hms_vaccination_sale.net_amount,hms_vaccination_sale.paid_amount,hms_vaccination_sale.balance,hms_patient.patient_name");  
        $this->db->from('hms_vaccination_sale');      
        $this->db->join('hms_vaccination_sale_to_vaccination','hms_vaccination_sale.id = hms_vaccination_sale_to_vaccination.sales_id','left');
        $this->db->join('hms_vaccination_entry','hms_vaccination_sale_to_vaccination.vaccine_id = hms_vaccination_entry.id','left');
        $this->db->join('hms_patient','hms_vaccination_sale.patient_id = hms_patient.id','left');
        $this->db->where('hms_vaccination_sale.branch_id',$branch_id);
        $this->db->where('hms_vaccination_sale.is_deleted',0);
        $this->db->where('hms_vaccination_sale.sale_date >=',date('Y-m-d',strtotime($get['start_date'])));
        $this->db->where('hms_vaccination_sale.sale_date <=',date('Y-m-d',strtotime($get['end_date'])));
        $this->db->where('hms_vaccination_entry.is_deleted',0);
        $query=$this->db->get()->result_array();
        return $query;  
    }
	

}
?>