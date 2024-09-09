<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Canteen_purchase_collection_report_model extends CI_Model {

    var $table = 'hms_payment';
    var $column = array('hms_canteen_purchase.purchase_id','hms_canteen_purchase.purchase_date', 'hms_canteen_vendors.name','hms_canteen_purchase.referred_by','hms_canteen_purchase.total_amount','hms_canteen_purchase.discount','hms_canteen_purchase.net_amount','hms_canteen_purchase.paid_amount','hms_canteen_purchase.balance');  
    var $order = array('hms_payment.id' => 'desc');
    //,'hms_department.department'
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('medicine_purchase_collection_resport_search_data');
        //print_r($search_data);die();
        $this->db->select("hms_canteen_purchase.id,hms_canteen_purchase.invoice_id, hms_canteen_purchase.purchase_id,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_canteen_purchase.purchase_date,  (CASE WHEN hms_payment.balance=0 and hms_payment.credit=0  THEN '0.00' ELSE hms_canteen_purchase.net_amount END) total_amount, (CASE when hms_payment.balance=0 and hms_payment.credit=0  THEN '0.00' ELSE (hms_canteen_purchase.discount) END) as discount, hms_canteen_vendors.name,hms_canteen_vendors.id as patient_new_id, (CASE WHEN hms_payment.balance=0 and hms_payment.credit=0  THEN '0.00' ELSE hms_canteen_purchase.balance END) as balance, hms_payment.pay_mode");  

        $this->db->join('hms_canteen_purchase','hms_canteen_purchase.id = hms_payment.parent_id AND hms_canteen_purchase.is_deleted !=2'); 
        $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',15);
        $this->db->where('hms_canteen_purchase.is_deleted',0);

        
        

        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_canteen_purchase.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_canteen_purchase.branch_id = "'.$users_data['parent_id'].'"');
        }

        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_canteen_purchase.purchase_date >= "'.$start_date.'"');
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                //$this->db->where('hms_canteen_purchase.purchase_date <= "'.$end_date.'"');
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

 
            
            if(isset($search_data['name']) && !empty($search_data['name'])
                )
            { 
                $this->db->where('hms_canteen_vendors.name LIKE "%'.$search_data["name"].'%"');
            }
            
            if(isset($search_data['purchase_id']) && !empty($search_data['purchase_id'])
                )
            { 
                $this->db->where('hms_canteen_purchase.purchase_id LIKE "%'.$search_data["purchase_id"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_canteen_vendors.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }
            
            
            if(isset($search_data['medicine_company']) &&  !empty($search_data['medicine_company']))
            {
                if(empty($search_data['medicine_name']))
                {
                    $this->db->join('hms_canteen_purchase_to_purchase','hms_canteen_purchase_to_purchase.sales_id = hms_canteen_purchase.id','left');
                    $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_purchase_to_purchase.purchase_id','left'); 
                }

                    $this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_master_entry.manuf_company','left'); 
                    $this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$search_data['medicine_company'].'%"');
            }
            
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
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

   
    function get_datatables($branch_id='')
    {
        $this->_get_datatables_query($branch_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->result();
    }

        function search_report_data()
    {
       $users_data = $this->session->userdata('auth_users');
        $search_data = $this->session->userdata('medicine_purchase_collection_resport_search_data');
        $this->db->select("hms_canteen_purchase.id,hms_canteen_purchase.invoice_id, hms_canteen_purchase.purchase_id,hms_payment.debit as paid_amount,hms_payment.parent_id,hms_payment.created_date as c_date,hms_payment.section_id,hms_payment.balance as blnce,hms_payment.id as pay_id,hms_canteen_purchase.purchase_date,  (CASE WHEN hms_payment.balance=0 and hms_payment.credit=0  THEN '0.00' ELSE hms_canteen_purchase.net_amount END) total_amount, (CASE when hms_payment.balance=0 and hms_payment.credit=0  THEN '0.00' ELSE (hms_canteen_purchase.discount) END) as discount, hms_canteen_vendors.name,hms_canteen_vendors.id as patient_new_id, (CASE WHEN hms_payment.balance=0 and hms_payment.credit=0  THEN '0.00' ELSE hms_canteen_purchase.balance END) as balance");  

        $this->db->join('hms_canteen_purchase','hms_canteen_purchase.id = hms_payment.parent_id AND hms_canteen_purchase.is_deleted !=2'); 
        $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id');
        $this->db->from($this->table);
        $this->db->where('hms_payment.section_id',15);
        $this->db->where('hms_canteen_purchase.is_deleted',0);

        
        

        if(isset($search_data['branch_id']) && $search_data['branch_id']!=''){
        $this->db->where('hms_canteen_purchase.branch_id IN ('.$search_data['branch_id'].')');
        }else{
        $this->db->where('hms_canteen_purchase.branch_id = "'.$users_data['parent_id'].'"');
        }

        if(isset($search_data) && !empty($search_data))
        {
            

            if(isset($search_data['from_date']) && !empty($search_data['from_date']))
            {
                $start_date = date('Y-m-d',strtotime($search_data['from_date'])).' 00:00:00';
                //$this->db->where('hms_canteen_purchase.purchase_date >= "'.$start_date.'"');
                $this->db->where('hms_payment.created_date >= "'.$start_date.'"');
            }

            if(isset($search_data['end_date']) &&  !empty($search_data['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search_data['end_date'])).' 23:59:59';
                $this->db->where('hms_payment.created_date <= "'.$end_date.'"');
            }

            
            if(isset($search_data['name']) && !empty($search_data['name'])
                )
            { 
                $this->db->where('hms_canteen_vendors.name LIKE "%'.$search_data["name"].'%"');
            }
            
            if(isset($search_data['purchase_id']) && !empty($search_data['purchase_id'])
                )
            { 
                $this->db->where('hms_canteen_purchase.purchase_id LIKE "'.$search_data["purchase_id"].'%"');
            }
            
            if(isset($search_data['mobile_no']) && !empty($search_data['mobile_no'])
                )
            { 
                $this->db->where('hms_canteen_vendors.mobile_no LIKE "'.$search_data["mobile_no"].'%"');
            }
            
            
            if(isset($search_data['medicine_company']) &&  !empty($search_data['medicine_company']))
            {
                if(empty($search_data['medicine_name']))
                {
                    $this->db->join('hms_canteen_purchase_to_purchase','hms_canteen_purchase_to_purchase.sales_id = hms_canteen_purchase.id','left');
                    $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_purchase_to_purchase.purchase_id','left'); 
                }

                    $this->db->join('hms_canteen_manuf_company','hms_canteen_manuf_company.id = hms_canteen_master_entry.manuf_company','left'); 
                    $this->db->where('hms_canteen_manuf_company.company_name LIKE"'.$search_data['medicine_company'].'%"');
            }
            
            
        }
        
        $emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search_data["employee"]))
        {
            $emp_ids=  $search_data["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_payment.created_by IN ('.$emp_ids.')');
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
        //$this->db->limit($_POST['length'], $_POST['start']);
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
  

    public function get_medicine_collection_list_details($get=array())
    {
        

        if(!empty($get))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $sub_branch_details = $this->session->userdata('sub_branches_data');
            if(!empty($sub_branch_details))
            {
                $child_ids_arr = array_column($sub_branch_details,'id');
                $child_ids = implode(',',$child_ids_arr);
            } 
            $this->db->select("path_expenses.*,path_expenses_category.exp_category"); 
            $this->db->from('path_expenses'); 
            $this->db->join("path_expenses_category","path_expenses.paid_to_id=path_expenses_category.id",'left');
            if(!empty($get['from_date']))
            {
              $this->db->where('path_expenses.expenses_date >= "'.$get['from_date'].'"');
            }

            if(!empty($get['end_date']))
            {
              $this->db->where('path_expenses.expenses_date<= "'.$get['end_date'].'"');   
            }

            if(!empty($get['branch_id']))
            {
              if(is_numeric($get['branch_id']) && $get['branch_id']>0)
              {
                 $this->db->where('path_expenses.branch_id',$get['branch_id']);  
              } 
              else if($get['branch_id']=='all') 
              {
                 $this->db->where('path_expenses.branch_id IN ('.$child_ids.')');  
              }  
            } 
            $query = $this->db->get();
            $result = $query->result();  
            return $result;
        } 
    }

    public function get_by_id($purchase_id='' ,$id='')
    {
        $this->db->select('hms_canteen_purchase.*');
        $this->db->from('hms_canteen_purchase'); 
         $this->db->join('hms_payment','hms_payment.parent_id=hms_canteen_purchase.id');
         $this->db->where('hms_canteen_purchase.id',$purchase_id);
         $this->db->where('hms_payment.id',$id);
        $this->db->where('hms_canteen_purchase.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }

        function get_all_detail_print($ids="",$branch_id="")
    {
        $result_sales=array();
        //$user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_canteen_purchase.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_canteen_vendors.name as name,hms_canteen_vendors.email as v_email,hms_canteen_vendors.mobile as mobile,hms_canteen_vendors.address as v_address,hms_canteen_vendors.address2 as v_address2,hms_canteen_vendors.address3 as v_address3,hms_users.*,hms_canteen_vendors.vendor_gst"); 
        $this->db->join('hms_canteen_vendors','hms_canteen_vendors.id = hms_canteen_purchase.vendor_id','left'); 
        $this->db->join('hms_users','hms_users.id = hms_canteen_purchase.created_by'); 
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
         $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
         
        $this->db->where('hms_canteen_purchase.is_deleted','0'); 
        $this->db->where('hms_canteen_purchase.branch_id = "'.$branch_id.'"'); 
        $this->db->where('hms_canteen_purchase.id = "'.$ids.'"');
        $this->db->from('hms_canteen_purchase');
        $result_sales['purchase_list']= $this->db->get()->result();

    
        $this->db->select('hms_canteen_purchase_to_purchase.*,hms_canteen_purchase_to_purchase.purchase_rate as p_r,hms_canteen_master_entry.*,hms_canteen_master_entry.*,hms_canteen_purchase_to_purchase.sgst as m_sgst,hms_canteen_purchase_to_purchase.igst as m_igst,hms_canteen_purchase_to_purchase.cgst as m_cgst,hms_canteen_purchase_to_purchase.discount as m_disc');
        $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.id = hms_canteen_purchase_to_purchase.purchase_id'); 
        $this->db->where('hms_canteen_purchase_to_purchase.purchase_id = "'.$ids.'"');
        $this->db->from('hms_canteen_purchase_to_purchase');
        $result_sales['purchase_list']['medicine_list']=$this->db->get()->result();
        //echo $this->db->last_query();die;
        return $result_sales;
        
    }

}
?>