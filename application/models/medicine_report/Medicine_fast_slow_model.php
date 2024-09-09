<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_fast_slow_model extends CI_Model {
var $table = 'hms_medicine_purchase';
    var $column = array('hms_medicine_purchase.id','hms_medicine_vendors.name','hms_medicine_vendors.vendor_gst','hms_medicine_purchase.invoice_id','hms_medicine_purchase_to_purchase.purchase_date','hms_medicine_purchase_to_purchase.sgst','hms_medicine_purchase_to_purchase.cgst','hms_medicine_purchase_to_purchase.igst','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile','hms_medicine_vendors.mobile');  
    var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('medicine_fast_slow_search');
        $this->db->select("hms_medicine_purchase_to_purchase.*,hms_medicine_purchase.invoice_id, hms_medicine_entry.medicine_code, hms_medicine_entry.medicine_name, hms_medicine_entry.branch_id, hms_medicine_purchase.net_amount,hms_medicine_purchase.purchase_date,hms_medicine_vendors.name as vendor_name, hms_medicine_vendors.vendor_gst,hms_medicine_vendors.vendor_id as v_id,hms_medicine_vendors.mobile,hms_medicine_purchase.id as p_id, hms_medicine_sale_to_medicine.sales_id, hms_medicine_sale_to_medicine.qty as sale_qty"); 
        
        $this->db->join('hms_medicine_purchase','hms_medicine_purchase.id =hms_medicine_purchase_to_purchase.purchase_id');
        $this->db->join('hms_medicine_vendors','hms_medicine_vendors.id = hms_medicine_purchase.vendor_id','left'); 

        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id','left'); 

        $this->db->join('hms_medicine_sale_to_medicine','hms_medicine_sale_to_medicine.medicine_id = hms_medicine_entry.id'); 
                                
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


   function medicine_fast_slow_report_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('medicine_fast_slow_search');
         if(!empty($search['start_date']))
        {
            $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
        }
        if(!empty($search['end_date']))
        {
            $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        }
        else{
            $start_date ='0000-00-00 00:00:00';
            $end_date = date('Y-m-d H:i:s');
        }
        $this->db->select('hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code,hms_medicine_stock.created_date as stock_created_date,hms_medicine_stock.batch_no,(select SUM(qtk.credit) from hms_medicine_stock as qtk where qtk.m_id=hms_medicine_entry.id AND qtk.created_date >= "'.$start_date.'" AND qtk.created_date <= "'.$end_date.'" AND qtk.branch_id='.$user_data['parent_id'].' AND qtk.is_deleted=0 AND qtk.type=3) as sale_qty,(select SUM(ptk.debit) from hms_medicine_stock as ptk where ptk.m_id=hms_medicine_entry.id AND ptk.created_date >= "'.$start_date.'" AND ptk.created_date <= "'.$end_date.'" AND ptk.branch_id='.$user_data['parent_id'].' AND ptk.type=1 AND ptk.is_deleted=0) as purchase_qty');
         $this->db->from('hms_medicine_stock');
         $this->db->JOIN('hms_medicine_entry','hms_medicine_stock.m_id = hms_medicine_entry.id AND hms_medicine_stock.credit IS NOT NULL');
         $this->db->where('hms_medicine_stock.created_date >= "'.$start_date.'"');
         $this->db->where('hms_medicine_stock.created_date <= "'.$end_date.'"');
         $this->db->where('hms_medicine_stock.branch_id='.$user_data['parent_id']);
         $this->db->order_by('sale_qty','DESC');
         $this->db->group_by('hms_medicine_stock.m_id');
         $query=$this->db->get();
       //echo $this->db->last_query();die();
        return $query->result();

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

         $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('medicine_fast_slow_search');
         if(!empty($search['start_date']))
        {
            $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
        }
        if(!empty($search['end_date']))
        {
            $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        }
        else{
            $start_date ='';
            $end_date = date('Y-m-d H:i:s');
        }
        $this->db->select('hms_medicine_entry.medicine_name, hms_medicine_entry.medicine_code,hms_medicine_stock.created_date as stock_created_date,hms_medicine_stock.batch_no,(select SUM(qtk.credit) from hms_medicine_stock as qtk where qtk.m_id=hms_medicine_entry.id AND qtk.created_date >= "'.$start_date.'" OR qtk.created_date <= "'.$end_date.'" AND qtk.branch_id='.$user_data['parent_id'].' AND qtk.is_deleted=0 AND qtk.type=3) as sale_qty,(select SUM(ptk.debit) from hms_medicine_stock as ptk where ptk.m_id=hms_medicine_entry.id AND ptk.created_date >= "'.$start_date.'" AND ptk.created_date <= "'.$end_date.'" AND ptk.branch_id='.$user_data['parent_id'].' AND ptk.type=1 AND ptk.is_deleted=0) as purchase_qty');
         $this->db->from('hms_medicine_stock');
         $this->db->JOIN('hms_medicine_entry','hms_medicine_stock.m_id = hms_medicine_entry.id');
         $this->db->where('hms_medicine_stock.created_date >= "'.$start_date.'"');
         $this->db->where('hms_medicine_stock.created_date <= "'.$end_date.'"');
         $this->db->where('hms_medicine_stock.branch_id='.$user_data['parent_id']);
         $this->db->order_by('hms_medicine_stock.id');
         $this->db->group_by('hms_medicine_stock.m_id');
         $query=$this->db->get()->num_rows();
        return $query;
    }

    public function count_all()
    {
        $this->medicine_fast_slow_report_list();
        
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }



    function search_medicine_data()
    {

        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('medicine_fast_slow_search');
        $purchase_where = '';
        $sale_where='';
        if(!empty($search['start_date']))
        {
            $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
            $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        }

        $sql1 = 'SELECT  `hms_medicine_entry`.`medicine_name`, `hms_medicine_entry`.`medicine_code`,`hms_medicine_stock`.`created_date` as `stock_created_date`,`hms_medicine_stock`.`batch_no`,

        (select SUM(qtk.credit) from hms_medicine_stock as qtk where qtk.m_id=hms_medicine_entry.id AND `qtk`.`created_date` >= "'.$start_date.'" AND `qtk`.`created_date` <= "'.$end_date.'" AND qtk.branch_id='.$user_data['parent_id'].' AND `qtk`.is_deleted=0 AND qtk.type=3) as sale_qty,

        (select SUM(ptk.debit) from hms_medicine_stock as ptk where ptk.m_id=hms_medicine_entry.id AND `ptk`.`created_date` >= "'.$start_date.'" AND `ptk`.`created_date` <= "'.$end_date.'" AND ptk.branch_id='.$user_data['parent_id'].' AND ptk.type=1 AND `ptk`.`is_deleted`=0) as purchase_qty

        FROM `hms_medicine_entry` LEFT JOIN `hms_medicine_stock` ON `hms_medicine_stock`.`m_id` = `hms_medicine_entry`.`id` AND `hms_medicine_stock`.`is_deleted`=0

         WHERE `hms_medicine_entry`.`is_deleted` = "0" AND `hms_medicine_entry`.`branch_id` ='.$user_data['parent_id'].' AND `hms_medicine_stock`.`created_date` >= "'.$start_date.'" AND `hms_medicine_stock`.`created_date` <= "'.$end_date.'" AND `hms_medicine_stock`.`is_deleted`=0  GROUP BY  `hms_medicine_entry`.`id`  ORDER BY `hms_medicine_stock`.`id` DESC, `hms_medicine_entry`.`medicine_name` DESC';

         $query =  $this->db->query($sql1);

        //echo $this->db->last_query();die;
        return $query->result();
    }


    
}
?>