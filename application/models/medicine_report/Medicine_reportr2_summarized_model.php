<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_reportr2_summarized_model extends CI_Model {

  var $table = 'hms_medicine_sale_to_medicine';
  var $column = array('hms_medicine_sale_to_medicine.id','hms_medicine_sale.sale_date', 'hms_medicine_sale.modified_date');  

  var $order = array('hms_medicine_sale_to_medicine.id' => 'desc'); 
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {
      $user_data = $this->session->userdata('auth_users');
      $search = $this->session->userdata('gstr2_search');
      $this->db->select("hms_medicine_sale_to_medicine.id, (CASE WHEN hms_medicine_sale_to_medicine.sgst=9 and hms_medicine_sale_to_medicine.cgst=9  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as egst, (CASE WHEN hms_medicine_sale_to_medicine.sgst=6 and hms_medicine_sale_to_medicine.cgst=6  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as tgst,(CASE WHEN hms_medicine_sale_to_medicine.sgst=2.50 and hms_medicine_sale_to_medicine.cgst=2.50  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as fgst, (CASE WHEN hms_medicine_sale_to_medicine.sgst=0.00 and hms_medicine_sale_to_medicine.cgst=0.00  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as zgst, hms_medicine_sale.sale_date,hms_medicine_sale.id as sale_id"); 
    $this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id','left'); 
    $this->db->from($this->table);
    $this->db->where('hms_medicine_sale.is_deleted','0'); 
    $this->db->where('hms_medicine_sale.branch_id',$user_data['parent_id']); 
 
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
       
    $user_data = $this->session->userdata('auth_users');
      $search = $this->session->userdata('gstr2_search');
      $this->db->select("hms_medicine_sale_to_medicine.id, (CASE WHEN hms_medicine_sale_to_medicine.sgst=9 and hms_medicine_sale_to_medicine.cgst=9  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as egst, (CASE WHEN hms_medicine_sale_to_medicine.sgst=6 and hms_medicine_sale_to_medicine.cgst=6  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as tgst,(CASE WHEN hms_medicine_sale_to_medicine.sgst=2.50 and hms_medicine_sale_to_medicine.cgst=2.50  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as fgst, (CASE WHEN hms_medicine_sale_to_medicine.sgst=0.00 and hms_medicine_sale_to_medicine.cgst=0.00  THEN hms_medicine_sale_to_medicine.total_amount  ELSE 0 END ) as zgst, hms_medicine_sale.sale_date,hms_medicine_sale.id as sale_id"); 
    $this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id','left'); 
    $this->db->from($this->table);
    $this->db->where('hms_medicine_sale.is_deleted','0'); 
    $this->db->where('hms_medicine_sale.branch_id',$user_data['parent_id']); 
    $this->db->order_by('hms_medicine_sale.id','desc'); 
        
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

        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }



function medicine_report_list()
    {
        $user_data = $this->session->userdata('auth_users');
          $search = $this->session->userdata('gstr2_search');
        $purchase_where = '';
        $sale_where='';
        if(!empty($search['start_date']))
        {
            $from_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
            $to_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        }
        
    

$sql1 = 'SELECT  `hms_medicine_entry`.`medicine_name` ,`hms_medicine_stock`.`created_date` as `stock_created_date`,`hms_medicine_stock`.`batch_no`, 


(select (SUM(stk.mrp-((stk.total_amount*stk.sgst/100)+(stk.total_amount*stk.cgst/100)+(stk.total_amount*stk.igst/100)))) from hms_medicine_stock as stk where stk.m_id=hms_medicine_entry.id AND `stk`.`created_date` >= "'.$from_date.'" AND `stk`.`created_date` <= "'.$to_date.'" AND stk.branch_id='.$user_data['parent_id'].' AND stk.type=3  AND `stk`.`is_deleted`=0) as mrp_total_price, 


(select (SUM(stk.total_amount-((stk.total_amount*stk.sgst/100)+(stk.total_amount*stk.cgst/100)+(stk.total_amount*stk.igst/100))))            from hms_medicine_stock as stk where stk.m_id=hms_medicine_entry.id AND `stk`.`created_date` >= "'.$from_date.'" AND `stk`.`created_date` <= "'.$to_date.'" AND stk.branch_id='.$user_data['parent_id'].' AND stk.type=3  AND `stk`.`is_deleted`=0) as sale_total_price,


(select SUM(stk.total_amount*stk.sgst/100) from hms_medicine_stock as stk where stk.m_id=hms_medicine_entry.id AND `stk`.`created_date` >= "'.$from_date.'" AND `stk`.`created_date` <= "'.$to_date.'" AND stk.branch_id='.$user_data['parent_id'].' AND stk.type=3  AND `stk`.`is_deleted`=0) as sale_total_sgst, 

(select SUM(stk.total_amount*stk.cgst/100) from hms_medicine_stock as stk where stk.m_id=hms_medicine_entry.id AND `stk`.`created_date` >= "'.$from_date.'" AND `stk`.`created_date` <= "'.$to_date.'" AND stk.branch_id='.$user_data['parent_id'].' AND stk.type=3  AND `stk`.`is_deleted`=0) as sale_total_cgst 


FROM `hms_medicine_entry` LEFT JOIN `hms_medicine_stock` ON `hms_medicine_stock`.`m_id` = `hms_medicine_entry`.`id` AND `hms_medicine_stock`.`is_deleted`=0


 WHERE `hms_medicine_entry`.`is_deleted` = "0" AND `hms_medicine_entry`.`branch_id` ='.$user_data['parent_id'].' AND `hms_medicine_stock`.`created_date` >= "'.$from_date.'" AND `hms_medicine_stock`.`created_date` <= "'.$to_date.'" AND `hms_medicine_stock`.`is_deleted`=0  AND `hms_medicine_stock`.`type`=3 GROUP BY `hms_medicine_stock`.`batch_no`, `hms_medicine_entry`.`id` ORDER BY `hms_medicine_stock`.`id` DESC, `hms_medicine_entry`.`medicine_name` DESC';

 $sql =  $this->db->query($sql1); 
return $sql->result_array();

}



}
?>