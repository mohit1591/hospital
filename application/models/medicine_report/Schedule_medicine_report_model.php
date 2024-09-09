<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule_medicine_report_model extends CI_Model {
var $table = 'hms_medicine_sale'; //hms_medicine_sale_to_medicine //hms_medicine_sale
    var $column = array(
                        'hms_medicine_sale.id',
                        'hms_medicine_sale.sale_no',
                        'hms_doctors.doctor_name',
                        'hms_patient.patient_name',
                        'hms_medicine_entry.medicine_name',
                        'hms_medicine_sale_to_medicine.qty',
                        'hms_medicine_company.company_name',
                        'hms_medicine_sale_to_medicine.batch_no',
                        'hms_medicine_sale_to_medicine.expiry_date',
                        'hms_medicine_sale.created_date' 
                        );  
    var $order = array('hms_medicine_sale.id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query($type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('schmed_qty_search');
    

        $this->db->select("
                           hms_medicine_sale_to_medicine.*,
                           hms_medicine_sale.sale_no,
                           hms_medicine_sale.referred_by,
                           hms_medicine_sale.refered_id,
                           hms_medicine_sale.net_amount,
                           hms_medicine_sale.sale_date, 
                           hms_medicine_sale.id as sale_id,
                           hms_medicine_entry.medicine_name,  
                           hms_patient.patient_name as patient_name, 
                           hms_patient.mobile_no,
                           hms_patient.id as patient_id,
                           hms_patient.mobile_no, 
                           hms_medicine_sale.id as p_id,
                           hms_hospital.hospital_name,
                           hms_doctors.doctor_name,
                           hms_medicine_company.company_name,
                           hms_medicine_sale.created_date as created
                         "); 
        
        $this->db->join('hms_medicine_sale','hms_medicine_sale.id =hms_medicine_sale_to_medicine.sales_id', 'left');
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left');  
        
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id', 'left');
        
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company', 'left');
         
        //$this->db->join('hms_medicine_type','hms_medicine_type.id = hms_medicine_entry.medicine_type','');
        
        $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital', 'left'); 
        
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id', 'left');
        
        $this->db->where('hms_medicine_entry.medicine_type>0');
       // $this->db->where('hms_medicine_entry.is_deleted','0');
        $this->db->where('hms_medicine_sale.is_deleted','0');
        //$this->db->where('hms_patient.is_deleted','0');
        $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);  
        $this->db->group_by('hms_medicine_sale_to_medicine.id');
 


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
   
    function get_datatables($type='')
    {
        $this->_get_datatables_query($type);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function count_filtered($type='')
    {
        $this->_get_datatables_query($type);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    

    function search_medicine_data($search_query="")
    {
       
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('schmed_qty_search');

        $this->db->select("
                           hms_medicine_sale_to_medicine.*,
                           hms_medicine_sale.sale_no,
                           hms_medicine_sale.referred_by,
                           hms_medicine_sale.refered_id,
                           hms_medicine_sale.net_amount,
                           hms_medicine_sale.sale_date, 
                           hms_medicine_sale.id as sale_id,
                           hms_medicine_entry.medicine_name,  
                           hms_patient.patient_name as patient_name, 
                           hms_patient.mobile_no,
                           hms_patient.id as patient_id,
                           hms_patient.mobile_no, 
                           hms_medicine_sale.id as p_id,
                           hms_hospital.hospital_name,
                           hms_doctors.doctor_name,
                           hms_medicine_company.company_name,
                           hms_medicine_sale.created_date as created
                         "); 
        
        $this->db->join('hms_medicine_sale','hms_medicine_sale.id =hms_medicine_sale_to_medicine.sales_id', 'left');
        $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left');  
        
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id', 'left');
        
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company', 'left');
         
        //$this->db->join('hms_medicine_type','hms_medicine_type.id = hms_medicine_entry.medicine_type','');
        
        $this->db->join('hms_hospital','hms_hospital.id = hms_medicine_sale.referral_hospital', 'left'); 
        
        $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id', 'left');
        
        $this->db->where('hms_medicine_entry.medicine_type>0');
        //$this->db->where('hms_medicine_entry.is_deleted','0');
        $this->db->where('hms_medicine_sale.is_deleted','0');
        //$this->db->where('hms_patient.is_deleted','0'); 
        $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']); 
        if(!empty($search_query))
            $this->db->like('hms_medicine_entry.medicine_name',$search_query);
        $this->db->group_by('hms_medicine_sale_to_medicine.id');
 


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
        $this->db->order_by('hms_medicine_sale.id', 'DESC');

        $this->db->from('hms_medicine_sale_to_medicine');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }



//////////////////// Old work ////////////////

   public function get_batch_med_qty($m_id="",$batch_no="")
    {
      $user_data = $this->session->userdata('auth_users');
      $search = $this->session->userdata('schmed_qty_search');
      $this->db->select('(sum(debit)-sum(credit)) as total_qty');
      $this->db->where('branch_id',$user_data['parent_id']); 
      $this->db->where('batch_no',$batch_no);
      $this->db->where('m_id',$m_id);
     
      $query = $this->db->get('hms_medicine_stock');

 // echo $this->db->last_query();die;

      return $query->row_array();
    }

}
?>