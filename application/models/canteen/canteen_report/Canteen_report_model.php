<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_report_model extends CI_Model {
	var $table = 'hms_canteen_purchase';
	var $column = array('hms_canteen_purchase.id','hms_canteen_vendors.name','hms_canteen_purchase.purchase_id','hms_canteen_purchase.invoice_id','hms_canteen_purchase.id','hms_canteen_purchase.total_amount','hms_canteen_purchase.net_amount','hms_canteen_purchase.paid_amount','hms_canteen_purchase.balance','hms_canteen_purchase.created_date', 'hms_canteen_purchase.modified_date');  
    var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_gstr1_query()
    {    
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr1_search');
        $this->db->select("hms_canteen_purchase_to_purchase.*, hms_canteen_purchase.invoice_id,hms_canteen_purchase.net_amount, hms_canteen_purchase.discount, hms_canteen_purchase.purchase_date, hms_canteen_vendors.name as vendor_name, hms_canteen_vendors.vendor_gst, hms_canteen_vendors.vendor_id as v_id,hms_canteen_vendors.mobile, hms_canteen_purchase.id as p_id"); 
        $this->db->join('hms_canteen_purchase','hms_canteen_purchase.id =hms_canteen_purchase_to_purchase.purchase_id');
        $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id','left'); 
        $this->db->where('hms_canteen_vendors.is_deleted','0'); 
        $this->db->where('hms_canteen_purchase.is_deleted','0'); 
        $this->db->where('hms_canteen_purchase.branch_id',$user_data['parent_id']);	
        
        if(isset($search) && !empty($search))
        {
			
			if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_purchase.purchase_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_purchase.purchase_date <= "'.$end_date.'"');
			}
           
        }

        $this->db->from('hms_canteen_purchase_to_purchase'); 
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
        }}

    public function get_purchase_count($purchase_id='')
    {   
	    $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_purchase_to_purchase.*,hms_canteen_purchase_to_purchase.purchase_rate,hms_canteen_stock_item.*,hms_canteen_stock_item.*,hms_canteen_purchase_to_purchase.sgst,hms_canteen_purchase_to_purchase.igst,hms_canteen_purchase_to_purchase.cgst,hms_canteen_purchase_to_purchase.discount');
        $this->db->join('hms_canteen_stock_item','hms_canteen_stock_item.id = hms_canteen_purchase_to_purchase.item_id'); 
        $this->db->where('hms_canteen_purchase_to_purchase.purchase_id = "'.$purchase_id.'"');
        $this->db->where('hms_canteen_stock_item.branch_id',$user_data['parent_id']);	; 
        $this->db->from('hms_canteen_purchase_to_purchase');
        $result_sales=$this->db->get()->result();
        return $total = count($result_sales);
    }
   
    function get_datatables_gstr1()
    {
        $this->_get_datatables_gstr1_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
       // echo $this->db->last_query();die;
        return $query->result();
    }

    function count_filtered_gstr1()
    {
        $this->_get_datatables_gstr1_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_gstr1()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function search_data_gstr1()
    {    
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr1_search');
        $this->db->select("hms_canteen_purchase_to_purchase.*, hms_canteen_purchase.invoice_id,hms_canteen_purchase.net_amount, hms_canteen_purchase.discount, hms_canteen_purchase.purchase_date, hms_canteen_vendors.name as vendor_name, hms_canteen_vendors.vendor_gst, hms_canteen_vendors.vendor_id as v_id,hms_canteen_vendors.mobile, hms_canteen_purchase.id as p_id"); 
        $this->db->join('hms_canteen_purchase','hms_canteen_purchase.id =hms_canteen_purchase_to_purchase.purchase_id');
        $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id','left'); 
        $this->db->where('hms_canteen_vendors.is_deleted','0'); 
        $this->db->where('hms_canteen_purchase.is_deleted','0'); 
        $this->db->where('hms_canteen_purchase.branch_id',$user_data['parent_id']); 
        
        if(isset($search) && !empty($search))
        {
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_canteen_purchase.purchase_date >= "'.$start_date.'"');
            }
            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_canteen_purchase.purchase_date <= "'.$end_date.'"');
            }
        }

        $this->db->from('hms_canteen_purchase_to_purchase'); 
        $this->db->order_by('id','desc');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }



// End purchase gstr1_repor

// Start sale gstr2_report	

 private function _get_datatables_gstr2_query()
    {
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr2_search');
        $this->db->select("hms_canteen_sale_to_sale.*,hms_canteen_sale.sale_no, hms_canteen_sale.net_amount, hms_canteen_sale.sale_date, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.id as patient_id, hms_customers.customer_code, hms_canteen_sale.id as s_id, hms_customers.customer_name, hms_patient.patient_code"); 
        $this->db->join('hms_canteen_sale','hms_canteen_sale.id =hms_canteen_sale_to_sale.sale_id');
        $this->db->join('hms_patient','hms_patient.id = hms_canteen_sale.patient_id','left'); 
		$this->db->join('hms_customers','hms_customers.id = hms_canteen_sale.customer_id','left');
        $this->db->where('hms_canteen_sale.is_deleted','0'); 
        $this->db->where('hms_canteen_sale.branch_id',$user_data['parent_id']); 
        
        if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_canteen_sale.sale_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_canteen_sale.sale_date <= "'.$end_date.'"');
            }
        }

        $this->db->from('hms_canteen_sale_to_sale'); 
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

  public function get_sale_count($sale_id='')
    {   
	    $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_sale_to_sale.*,hms_canteen_sale_to_sale.sale_rate,hms_canteen_stock_item.*,hms_canteen_stock_item.*,hms_canteen_sale_to_sale.sgst,hms_canteen_sale_to_sale.igst,hms_canteen_sale_to_sale.cgst,hms_canteen_sale_to_sale.discount');
        $this->db->join('hms_canteen_stock_item','hms_canteen_stock_item.id = hms_canteen_sale_to_sale.item_id'); 
        $this->db->where('hms_canteen_sale_to_sale.sale_id = "'.$sale_id.'"');
        $this->db->where('hms_canteen_stock_item.branch_id',$user_data['parent_id']);	; 
        $this->db->from('hms_canteen_sale_to_sale');
        $result_sale=$this->db->get()->result();
//echo $this->db->last_query();die;		
		
        return $total = count($result_sale);
		
    }
   
    function get_datatables_gstr2()
    {
        $this->_get_datatables_gstr2_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
     //echo $this->db->last_query();die;
        return $query->result();
    }

    function count_filtered_gstr2()
    {
        $this->_get_datatables_gstr2_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_gstr2()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    

    function search_data_gstr2()
    {
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('gstr2_search');
        $this->db->select("hms_canteen_sale_to_sale.*,hms_canteen_sale.sale_no, hms_canteen_sale.net_amount, hms_canteen_sale.sale_date, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.id as patient_id, hms_customers.customer_code, hms_canteen_sale.id as s_id, hms_customers.customer_name, hms_patient.patient_code"); 
        $this->db->join('hms_canteen_sale','hms_canteen_sale.id =hms_canteen_sale_to_sale.sale_id');
        $this->db->join('hms_patient','hms_patient.id = hms_canteen_sale.patient_id','left'); 
		$this->db->join('hms_customers','hms_customers.id = hms_canteen_sale.customer_id','left');
        $this->db->where('hms_canteen_sale.is_deleted','0'); 
        $this->db->where('hms_canteen_sale.branch_id',$user_data['parent_id']); 
        
        if(isset($search) && !empty($search))
        {
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_canteen_sale.sale_date >= "'.$start_date.'"');
            }
            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_canteen_sale.sale_date <= "'.$end_date.'"');
            }
        }

        $this->db->from('hms_canteen_sale_to_sale'); 
        $this->db->order_by('id','desc');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

   // End sale gstr2_report

}
?>