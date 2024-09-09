<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_reportr2_qty_model extends CI_Model {
var $table = 'hms_medicine_sale'; //hms_medicine_sale_to_medicine //hms_medicine_sale
    var $column = array('hms_medicine_sale.id','hms_patient.patient_name','hms_patient.mobile_no','hms_medicine_sale.sale_no','hms_medicine_sale_to_medicine.sale_date','hms_medicine_sale_to_medicine.sgst','hms_medicine_sale_to_medicine.cgst','hms_medicine_sale_to_medicine.igst','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no','hms_patient.mobile_no');  
    var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr2_qty_search');
        $this->db->select("hms_medicine_sale_to_medicine.*,hms_medicine_sale.sale_no,hms_medicine_sale.net_amount,hms_medicine_sale.sale_date,hms_patient.patient_name as patient_name, hms_patient.mobile_no,hms_patient.id as patient_id,hms_patient.mobile_no,hms_medicine_sale.id as p_id"); 

        
        $this->db->join('hms_medicine_sale','hms_medicine_sale.id =hms_medicine_sale_to_medicine.sales_id');
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
        $this->db->where('hms_patient.is_deleted','0'); 
        $this->db->where('hms_medicine_sale.is_deleted','0'); 
        $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']); 
        
        if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
            }
        }

        $this->db->from('hms_medicine_sale_to_medicine'); 
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

    public function get_medicine_count($sales_id='')
    {
        $this->db->select('hms_medicine_sale_to_medicine.*,hms_medicine_sale_to_medicine.per_pic_price as p_r,hms_medicine_entry.*,hms_medicine_entry.*,hms_medicine_sale_to_medicine.sgst as m_sgst,hms_medicine_sale_to_medicine.igst as m_igst,hms_medicine_sale_to_medicine.cgst as m_cgst,hms_medicine_sale_to_medicine.discount as m_disc');
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id'); 
        $this->db->where('hms_medicine_sale_to_medicine.sales_id = "'.$sales_id.'"');
        $this->db->from('hms_medicine_sale_to_medicine');
        $result_sales=$this->db->get()->result();
        //echo $this->db->last_query(); exit;
        return $total = count($result_sales);
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
    

    function search_medicine_data()
    {
       
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr2_qty_search');
        $this->db->select("hms_medicine_sale_to_medicine.*,hms_medicine_sale.sale_no,hms_medicine_sale.net_amount,hms_medicine_sale.sale_date,hms_patient.patient_name as patient_name,hms_patient.id as patient_id,hms_patient.mobile_no,hms_medicine_sale.id as p_id"); 

        $this->db->join('hms_medicine_sale','hms_medicine_sale.id =hms_medicine_sale_to_medicine.sales_id');
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
        $this->db->where('hms_patient.is_deleted','0'); 
        $this->db->where('hms_medicine_sale.is_deleted','0'); 
        $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']); 
        
        if(isset($search) && !empty($search))
        {
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
            }
            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
            }
        }

        $this->db->from('hms_medicine_sale_to_medicine'); 
        $this->db->order_by('id','desc');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }







////////////////////old work ////////////////

}
?>