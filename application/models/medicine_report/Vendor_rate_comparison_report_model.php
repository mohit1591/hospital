<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor_rate_comparison_report_model extends CI_Model {
var $table = 'hms_medicine_purchase';
    var $column = array('hms_medicine_purchase.id','hms_medicine_vendors.name','hms_medicine_entry.medicine_name','hms_medicine_purchase.purchase_date','hms_medicine_purchase.net_amount');  
    var $order = array('hms_medicine_purchase.id' => 'desc'); 
    
    
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('vendor_rate_comparison_search');
        $this->db->select("hms_medicine_purchase_to_purchase.*,hms_medicine_purchase.invoice_id, hms_medicine_entry.medicine_code, hms_medicine_entry.medicine_name, hms_medicine_entry.branch_id, hms_medicine_purchase.net_amount,hms_medicine_purchase.purchase_date,hms_medicine_vendors.name as vendor_name, hms_medicine_vendors.vendor_gst,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile,hms_medicine_purchase.id as p_id"); 

        //hms_medicine_purchase.id as p_id,
        
        $this->db->join('hms_medicine_purchase','hms_medicine_purchase.id =hms_medicine_purchase_to_purchase.purchase_id');
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 

        //$this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id'); 
                                 
        $this->db->where('hms_medicine_vendors.is_deleted','0'); 
        $this->db->where('hms_medicine_purchase.is_deleted','0'); 
        $this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']); 
        
        if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
            }
            if(isset($search['vendor_id']) && !empty($search['vendor_id']))
            {
                $this->db->where('hms_medicine_purchase.vendor_id',$search['vendor_id']);
            }
        }

        $this->db->from('hms_medicine_purchase_to_purchase'); 
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

    public function get_medicine_count($purchase_id='')
    {
        $this->db->select('hms_medicine_purchase_to_purchase.*,hms_medicine_purchase_to_purchase.purchase_rate as p_r,hms_medicine_entry.*,hms_medicine_entry.*,hms_medicine_purchase_to_purchase.sgst as m_sgst,hms_medicine_purchase_to_purchase.igst as m_igst,hms_medicine_purchase_to_purchase.cgst as m_cgst,hms_medicine_purchase_to_purchase.discount as m_disc');
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id'); 
        $this->db->where('hms_medicine_purchase_to_purchase.purchase_id = "'.$purchase_id.'"');
        //$this->db->where('hms_medicine_purchase_to_purchase.branch_id = "'.$branch_id.'"'); 
        $this->db->from('hms_medicine_purchase_to_purchase');
        $result_sales=$this->db->get()->result();
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
        $search = $this->session->userdata('vendor_rate_comparison_search');

        $this->db->select("hms_medicine_purchase_to_purchase.*,hms_medicine_purchase.invoice_id, hms_medicine_entry.medicine_code, hms_medicine_entry.medicine_name, hms_medicine_entry.branch_id, hms_medicine_purchase.net_amount,hms_medicine_purchase.purchase_date,hms_medicine_vendors.name as vendor_name, hms_medicine_vendors.vendor_gst,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile,hms_medicine_purchase.id as p_id"); 
        $this->db->join('hms_medicine_purchase','hms_medicine_purchase.id =hms_medicine_purchase_to_purchase.purchase_id');
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 

        //$this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id'); 
                                 
        $this->db->where('hms_medicine_vendors.is_deleted','0'); 
        $this->db->where('hms_medicine_purchase.is_deleted','0'); 
        $this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']);  
        
        if(isset($search) && !empty($search))
        {
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
            }
            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
            }
            if(isset($search['vendor_id']) && !empty($search['vendor_id']))
            {
                $this->db->where('hms_medicine_purchase.vendor_id',$search['vendor_id']);
            }
        }

        $this->db->from('hms_medicine_purchase_to_purchase'); 
        $this->db->order_by('id','desc');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }







////////////////////old work ////////////////

    public function branch_gst_list($get="")
    { 
            if($get['section_type']==1){
               $this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 

                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

                $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company');
                $this->db->where('hms_medicine_purchase.is_deleted','0'); 
                $this->db->where('hms_medicine_purchase.branch_id',$get['branch_id']); 
                  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_medicine_purchase');
             $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();

            }
            if($get['section_type']==2){
              
                $this->db->select("hms_medicine_return.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_return_to_return where purchase_return_id = hms_medicine_return.id) as total_medicine"); 
                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left'); 
                $this->db->where('hms_medicine_return.is_deleted','0'); 
                $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                 $this->db->where('hms_medicine_return.branch_id',$get['branch_id']);  
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_return.purchase_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_return.purchase_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_medicine_return');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            }
            if($get['section_type']==3){
               
                $this->db->select("hms_medicine_sale.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
                $this->db->where('hms_medicine_sale.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
                 $this->db->where('hms_medicine_sale.branch_id',$get['branch_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_medicine_sale');
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            }
            if($get['section_type']==4){
               
                $this->db->select("hms_medicine_sale_return.*, hms_patient.patient_name,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left'); 
                $this->db->where('hms_medicine_sale_return.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
                $this->db->where('hms_medicine_sale_return.branch_id',$get['branch_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_sale_return.sale_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_sale_return.sale_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_medicine_sale_return');
             $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
                
            }

            if($get['section_type']=='all'){
                   /* purchase report */

                    $this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 

                    $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

                    $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                    //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company');
                    $this->db->where('hms_medicine_purchase.is_deleted','0'); 
                    $this->db->where('hms_medicine_purchase.branch_id',$get['branch_id']); 

                    if(!empty($get['start_date']))
                    {
                    $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                    $this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date.'"');
                    }

                    if(!empty($get['end_date']))
                    {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');
                    }
                    $this->db->from('hms_medicine_purchase');
                    $query = $this->db->get(); 
                    //echo $this->db->last_query(); exit; 
                   $data['purchasedata']=$query->result();

                   /* purchase report */

                /* purchase return report */

                    $this->db->select("hms_medicine_return.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_return_to_return where purchase_return_id = hms_medicine_return.id) as total_medicine"); 
                    $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left'); 
                    $this->db->where('hms_medicine_return.is_deleted','0'); 
                    $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                    $this->db->where('hms_medicine_return.branch_id',$get['branch_id']);  
                    //$this->db->where('hms_payment.vendor_id',0); 
                    //$this->db->where('hms_payment.parent_id',0); 
                    if(!empty($get['start_date']))
                    {
                    $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                    $this->db->where('hms_medicine_return.purchase_date >= "'.$start_date.'"');
                    }

                    if(!empty($get['end_date']))
                    {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_medicine_return.purchase_date <= "'.$end_date.'"');
                    }
                    $this->db->from('hms_medicine_return');
                    $query = $this->db->get(); 
                    //echo $this->db->last_query(); exit; 
                     $data['purchase_return'] =$query->result();

                /* purchase return report */

                /* sale report */

                    $this->db->select("hms_medicine_sale.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine,hms_doctors.doctor_name"); 
                    $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
                    $this->db->where('hms_medicine_sale.is_deleted','0'); 
                    $this->db->where('hms_patient.is_deleted','0'); 
                    $this->db->where('hms_doctors.is_deleted','0'); 
                    $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
                    $this->db->where('hms_medicine_sale.branch_id',$get['branch_id']);   
                    //$this->db->where('hms_payment.vendor_id',0); 
                    //$this->db->where('hms_payment.parent_id',0); 
                    if(!empty($get['start_date']))
                    {
                    $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                    $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date.'"');
                    }

                    if(!empty($get['end_date']))
                    {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');
                    }
                    $this->db->from('hms_medicine_sale');
                    $query = $this->db->get(); 
                    $data['sale_report']= $query->result();

                /* sale report */


                    /* sale_return report */
                    $this->db->select("hms_medicine_sale_return.*, hms_patient.patient_name,hms_doctors.doctor_name"); 
                    $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left'); 
                    $this->db->where('hms_medicine_sale_return.is_deleted','0'); 
                    $this->db->where('hms_patient.is_deleted','0'); 
                    $this->db->where('hms_doctors.is_deleted','0'); 
                    $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
                    $this->db->where('hms_medicine_sale_return.branch_id',$get['branch_id']);   
                    //$this->db->where('hms_payment.vendor_id',0); 
                    //$this->db->where('hms_payment.parent_id',0); 
                    if(!empty($get['start_date']))
                    {
                    $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

                    $this->db->where('hms_medicine_sale_return.sale_date >= "'.$start_date.'"');
                    }

                    if(!empty($get['end_date']))
                    {
                    $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                    $this->db->where('hms_medicine_sale_return.sale_date <= "'.$end_date.'"');
                    }
                    $this->db->from('hms_medicine_sale_return');
                    $query = $this->db->get(); 
                    $data['sale_return_report']=$query->result();
                       /* sale_return report */
                       return $data;
                  }

       }

    public function self_gst_list($get="")
    {
        $users_data = $this->session->userdata('auth_users'); 

        if($get['section_type']==1){
                
                $this->db->select("hms_medicine_purchase.*,hms_medicine_vendors.name as vendor_name,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile, (select count(id) from hms_medicine_purchase_to_purchase where purchase_id = hms_medicine_purchase.id) as total_medicine"); 

                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

                $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                //$this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company');
                $this->db->where('hms_medicine_purchase.is_deleted','0'); 
                 $this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']); 
                  
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_purchase.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_purchase.created_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_medicine_purchase');

            }
            if($get['section_type']==2){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_medicine_return.*, hms_medicine_vendors.name as vendor_name, (select count(id) from hms_medicine_return_to_return where purchase_return_id = hms_medicine_return.id) as total_medicine"); 
                $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_return.vendor_id','left'); 
                $this->db->where('hms_medicine_return.is_deleted','0'); 
                $this->db->where('hms_medicine_vendors.is_deleted','0'); 
                 $this->db->where('hms_medicine_return.branch_id',$users_data['parent_id']);  
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_return.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_return.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_medicine_return');
                
            }
            if($get['section_type']==3){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_medicine_sale.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_to_medicine where sales_id = hms_medicine_sale.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale.patient_id','left'); 
                $this->db->where('hms_medicine_sale.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale.refered_id','left');
                $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_sale.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_sale.created_date <= "'.$end_date.'"');
            }
            $this->db->from('hms_medicine_sale');
                
            }
            if($get['section_type']==4){
                $branch_id = implode(',', $ids); 
                $this->db->select("hms_medicine_sale_return.*, hms_patient.patient_name, (select count(id) from hms_medicine_sale_return_medicine where sales_return_id = hms_medicine_sale_return.id) as total_medicine,hms_doctors.doctor_name"); 
                $this->db->join('hms_patient','hms_patient.id = hms_medicine_sale_return.patient_id','left'); 
                $this->db->where('hms_medicine_sale_return.is_deleted','0'); 
                $this->db->where('hms_patient.is_deleted','0'); 
                $this->db->where('hms_doctors.is_deleted','0'); 
                $this->db->join('hms_doctors','hms_doctors.id = hms_medicine_sale_return.refered_id','left');
                $this->db->where('hms_medicine_sale_return.branch_id',$users_data['parent_id']);   
            //$this->db->where('hms_payment.vendor_id',0); 
            //$this->db->where('hms_payment.parent_id',0); 
            if(!empty($get['start_date']))
            {
               $start_date=date('Y-m-d',strtotime($get['start_date']))." 00:00:00";

               $this->db->where('hms_medicine_sale_return.created_date >= "'.$start_date.'"');
            }

            if(!empty($get['end_date']))
            {
                $end_date=date('Y-m-d',strtotime($get['end_date']))." 23:59:59";
                $this->db->where('hms_medicine_sale_return.created_date <= "'.$end_date.'"');
            }
             $this->db->from('hms_medicine_sale_return');
                
            }
            
            $query = $this->db->get(); 
            //echo $this->db->last_query(); exit; 
            return $query->result();
          
    }
}
?>