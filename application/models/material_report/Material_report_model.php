<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material_report_model extends CI_Model {

	var $table = 'hms_dental_prescription_treatment_booking';
	var $column = array('hms_dental_prescription_treatment_booking.id','hms_opd_booking.booking_code','hms_dental_prescription_treatment_booking.treatment_name','hms_dental_prescription_treatment_booking.teeth_name','hms_dental_prescription_treatment_booking.tooth_number','hms_dental_prescription_treatment_booking.treatment_type_id','hms_dental_prescription_treatment_booking.treatment_remarks','hms_opd_booking.created_date');   
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
        $material_summary_search = $this->session->userdata('material_summary_search');
        $this->db->select("hms_dental_prescription_treatment_booking.*,hms_opd_booking.booking_code,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code,hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_opd_booking.booking_date");

		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_dental_prescription_treatment_booking.booking_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
		$this->db->from($this->table); 
		$this->db->where('hms_dental_prescription_treatment_booking.branch_id',$users_data['parent_id']);

		if(isset($material_summary_search) && !empty($material_summary_search))
        {
            
             if(isset($material_summary_search['start_date']) && !empty($material_summary_search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($material_summary_search['start_date'])).' 00:00:00';
                $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
            }

            if(isset($material_summary_search['end_date']) && !empty($material_summary_search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($material_summary_search['end_date'])).' 23:59:59';
                $this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
            }

            if(isset($material_summary_search['treatment_name']) && !empty($material_summary_search['treatment_name']))
			{
				$this->db->where('hms_dental_prescription_treatment_booking.treatment_name LIKE "'.$material_summary_search['treatment_name'].'%"');
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


	function search_report_data()
    {
        $users_data = $this->session->userdata('auth_users');
		$sub_branch_details = $this->session->userdata('sub_branches_data');
		$parent_branch_details = $this->session->userdata('parent_branches_data');

		$material_summary_search = $this->session->userdata('material_summary_search');


		$this->db->select("hms_dental_prescription_treatment_booking.*,hms_opd_booking.booking_code,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code,hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_opd_booking.booking_date");

		$this->db->join('hms_opd_booking','hms_opd_booking.id=hms_dental_prescription_treatment_booking.booking_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
		$this->db->from($this->table); 
		$this->db->where('hms_dental_prescription_treatment_booking.branch_id',$users_data['parent_id']);

		if(isset($material_summary_search) && !empty($material_summary_search))
        {
            
             if(isset($material_summary_search['start_date']) && !empty($material_summary_search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($material_summary_search['start_date'])).' 00:00:00';
                $this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
            }

            if(isset($material_summary_search['end_date']) && !empty($material_summary_search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($material_summary_search['end_date'])).' 23:59:59';
                $this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
            }

            if(isset($material_summary_search['treatment_name']) && !empty($material_summary_search['treatment_name']))
			{
				$this->db->where('hms_dental_prescription_treatment_booking.treatment_name LIKE "'.$material_summary_search['treatment_name'].'%"');
			}


		}
        $this->db->order_by('hms_dental_prescription_treatment_booking.id','desc');
        $query = $this->db->get(); 
        $data= $query->result();
        //echo $this->db->last_query();die;
        return $data;
    }
    
   
	
 
}
?>