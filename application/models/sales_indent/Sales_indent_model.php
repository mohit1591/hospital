<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Sales_indent_model extends CI_Model 
{
  var $table = 'hms_indent_sale';
  var $column = array('hms_indent_sale.id','hms_indent.indent','hms_indent_sale.sale_no','hms_indent_sale.id','hms_indent_sale.created_date', 'hms_indent_sale.modified_date');  

  var $order = array('id' => 'desc'); 

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query()
  {
    $search = $this->session->userdata('sale_search');
   
    $user_data = $this->session->userdata('auth_users');
    
    $this->db->select("hms_indent_sale.*, hms_indent.indent, (select count(id) from hms_indent_sale_to_indent where sales_id = hms_indent_sale.id) as total_medicine"); 
    $this->db->join('hms_indent','hms_indent.id = hms_indent_sale.indent_id','left'); 
    $this->db->from($this->table);
    $this->db->where('hms_indent_sale.is_deleted','0'); 
    if($user_data['users_role']==4)
    {
      if(isset($search['branch_id']) && $search['branch_id']!=''){
      $this->db->where('hms_indent_sale.indent_id IN ('.$search['branch_id'].')');
      }else{
      $this->db->where('hms_indent_sale.indent_id = "'.$user_data['parent_id'].'"');
      } 
    }
    else
    {
      if(isset($search['branch_id']) && $search['branch_id']!='')
      {
        $this->db->where('hms_indent_sale.branch_id IN ('.$search['branch_id'].')');
      }
      else
      {
        $this->db->where('hms_indent_sale.branch_id = "'.$user_data['parent_id'].'"');
      }
    }
    if(isset($search) && !empty($search))
    {
      
      if(isset($search['start_date']) && !empty($search['start_date']))
      {
          $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
          $this->db->where('hms_indent_sale.sale_date >= "'.$start_date.'"');
      }

      if(isset($search['end_date']) &&  !empty($search['end_date']))
      {
          $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
          $this->db->where('hms_indent_sale.sale_date <= "'.$end_date.'"');
      }
      if(isset($search['indent_id']) &&  !empty($search['indent_id']))
      {
          $this->db->where('hms_indent.indent',$search['indent_name']);
      }
    

      if(isset($search['sale_no']) &&  !empty($search['sale_no']))
      {
        $this->db->where('hms_indent_sale.sale_no LIKE "'.$search['sale_no'].'%"');
      }

      if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
      {
        $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
      }

      if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
      {
        if(empty($search['medicine_name']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
        
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
        $this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
      }

      if(isset($search['medicine_code']) &&  !empty($search['medicine_code']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
      }
      
      if(isset($search['discount']) &&  !empty($search['discount']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
        {
        $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
        }
        $this->db->where('hms_indent_sale_to_indent.discount = "'.$search['discount'].'"');
      }
      if(isset($search['batch_no']) &&  !empty($search['batch_no']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
        {
        $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
        }
        $this->db->where('hms_indent_sale_to_indent.batch_no = "'.$search['batch_no'].'"');
      }
      
      if(isset($search['packing']) && $search['packing']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['batch_no']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['conversion']) &&  $search['conversion']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['mrp_to']) &&  $search['mrp_to']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
      }
      if(isset($search['mrp_from']) &&  $search['mrp_from']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
      }

      if(isset($search['cgst']) &&  !empty($search['cgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
          $this->db->where('hms_indent_sale_to_indent.cgst', $search['cgst']);
      }
      if(isset($search['igst']) &&  !empty($search['igst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
          {
            $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
          }
          $this->db->where('hms_indent_sale_to_indent.igst', $search['igst']);
      }
      if(isset($search['sgst']) &&  !empty($search['sgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
          {
            $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
          }
          $this->db->where('hms_indent_sale_to_indent.sgst', $search['sgst']);
      }
    }
    else
    {

    }
    
    $emp_ids='';
    if($user_data['emp_id']>0)
    {
      if($user_data['record_access']=='1')
      {
        $emp_ids= $user_data['id'];
      }
    }
    elseif(!empty($search["employee"]) && is_numeric($search['employee']))
    {
      $emp_ids=  $search["employee"];
    }
    if(isset($emp_ids) && !empty($emp_ids))
    { 
      $this->db->where('hms_indent_sale.created_by IN ('.$emp_ids.')');
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

   function search_report_data(){
    $search = $this->session->userdata('sale_search');
    $user_data = $this->session->userdata('auth_users');
    $this->db->select("hms_indent_sale.*, hms_indent.indent"); 
   
    $this->db->join('hms_indent','hms_indent.id = hms_indent_sale.indent_id','left');
  
    $this->db->from($this->table);
    $this->db->where('hms_indent_sale.is_deleted','0'); 
    if($user_data['users_role']==4)
    {
      if(isset($search['branch_id']) && $search['branch_id']!=''){
      $this->db->where('hms_indent_sale.indent_id IN ('.$search['branch_id'].')');
      }else{
      $this->db->where('hms_indent_sale.indent_id = "'.$user_data['parent_id'].'"');
      }
    }
    else
    {
      if(isset($search['branch_id']) && $search['branch_id']!=''){
      $this->db->where('hms_indent_sale.branch_id IN ('.$search['branch_id'].')');
      }else{
      $this->db->where('hms_indent_sale.branch_id = "'.$user_data['parent_id'].'"');
      }
    }
    if(isset($search) && !empty($search))
    {
      
      if(isset($search['start_date']) && !empty($search['start_date']))
      {
        $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
        $this->db->where('hms_indent_sale.sale_date >= "'.$start_date.'"');
      }

      if(isset($search['end_date']) &&  !empty($search['end_date']))
      {
        $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
        $this->db->where('hms_indent_sale.sale_date <= "'.$end_date.'"');
      }


      if(isset($search['indent_name']) &&  !empty($search['indent_name']))
      {

        $this->db->where('hms_indent.indent LIKE "%'.$search['indent_name'].'%"');
      }

      if(isset($search['sale_no']) &&  !empty($search['sale_no']))
      {
        $this->db->where('hms_indent_sale.sale_no LIKE "%'.$search['sale_no'].'%"');
      }

      if(isset($search['medicine_name']) &&  !empty($search['medicine_name']))
      {
        $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
      }

      if(isset($search['medicine_company']) &&  !empty($search['medicine_company']))
      {
        if(empty($search['medicine_name']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
        
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left'); 
        $this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
      }

      if(isset($search['medicine_code']) &&  !empty($search['medicine_code']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$search['medicine_code'].'%"');
      }
      
      if(isset($search['discount']) &&  !empty($search['discount']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']))
        {
        $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
        }
        $this->db->where('hms_indent_sale_to_indent.discount = "'.$search['discount'].'"');
      }
      if(isset($search['batch_no']) &&  !empty($search['batch_no']))
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']))
        {
        $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
        }
        $this->db->where('hms_indent_sale_to_indent.batch_no = "'.$search['batch_no'].'"');
      }
      
      if(isset($search['packing']) && $search['packing']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount'])  && empty($search['batch_no']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['conversion']) &&  $search['conversion']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.conversion LIKE "'.$search['packing'].'%"');
      }

      if(isset($search['mrp_to']) &&  $search['mrp_to']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
                }
        $this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
      }
      if(isset($search['mrp_from']) &&  $search['mrp_from']!="")
      {
        if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
        $this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
      }

      if(isset($search['cgst']) &&  !empty($search['cgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']))
        {
          $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
        }
          $this->db->where('hms_indent_sale_to_indent.cgst', $search['cgst']);
      }
      if(isset($search['igst']) &&  !empty($search['igst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']))
          {
            $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
          }
          $this->db->where('hms_indent_sale_to_indent.igst', $search['igst']);
      }
      if(isset($search['sgst']) &&  !empty($search['sgst']))
      {
          if(empty($search['medicine_name']) && empty($search['medicine_company'])&& empty($search['medicine_code']) && empty($search['discount']) && empty($search['batch_no']) && empty($search['packing'])&& empty($search['conversion']) && empty($search['mrp_to']) && empty($search['mrp_from']) && empty($search['cgst']) && empty($search['igst']))
          {
            $this->db->join('hms_indent_sale_to_indent','hms_indent_sale_to_indent.sales_id = hms_indent_sale.id','left'); 
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id','left'); 
          }
          $this->db->where('hms_indent_sale_to_indent.sgst', $search['sgst']);
      }

    }else{

    }
    $emp_ids='';
    if($user_data['emp_id']>0)
    {
      if($user_data['record_access']=='1')
      {
        $emp_ids= $user_data['id'];
      }
    }
    elseif(!empty($search["employee"]) && is_numeric($search['employee']))
    {
      $emp_ids=  $search["employee"];
    }
    if(isset($emp_ids) && !empty($emp_ids))
    { 
      $this->db->where('hms_indent_sale.created_by IN ('.$emp_ids.')');
    }
    $query = $this->db->get(); 

    $data= $query->result();
    
    return $data;

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

  public function get_by_id($id)
  {
    $this->db->select('hms_indent_sale.*');
    $this->db->from('hms_indent_sale'); 
     $this->db->where('hms_indent_sale.id',$id);
    $this->db->where('hms_indent_sale.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }

  public function get_medicine_by_sales_id($id="",$tamt="")
  { 
    $this->db->select('hms_indent_sale_to_indent.*,');
    $this->db->from('hms_indent_sale_to_indent'); 
    if(!empty($id))
    {
          $this->db->where('hms_indent_sale_to_indent.sales_id',$id);
          $this->db->join('hms_indent_sale','hms_indent_sale.id=hms_indent_sale_to_indent.sales_id','left');
    } 
   $query = $this->db->get()->result(); 
    
    $result = [];
    $total_price_medicine_amount='';

    if(!empty($query))
    {
      foreach($query as $medicine)  
      {   
          $result[$medicine->medicine_id.'.'.$medicine->batch_no] = array('mid'=>$medicine->medicine_id, 'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'hsn_no'=>$medicine->hsn_no,'discount'=>$medicine->discount,'conversion'=>$medicine->conversion,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'batch_no'=>$medicine->batch_no,'cgst'=>$medicine->cgst,'igst'=>$medicine->igst,'sgst'=>$medicine->sgst, 'per_pic_amount'=>$medicine->per_pic_price,'sale_amount'=>$medicine->actual_amount, 'total_amount'=>$medicine->total_amount,'bar_code'=>$medicine->bar_code,'total_pricewith_medicine'=>$tamt); 
          $tamt = 0;
      } 
    } 
    //die;
    //print '<pre>';print_r($result);die;
    return $result;
  }
 public function doctor_list()
    {
      $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (0,2)'); 
    $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
    $query = $this->db->get('hms_doctors');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result;
    }

  public function save()
  { 
      $user_data = $this->session->userdata('auth_users');
      $post = $this->input->post();
    //  print_r( $post );die();
    if(!empty($post['data_id']) && $post['data_id']>0)
    { 
        $created_by= $this->get_by_id($post['data_id']);
        $purchase_detail= $this->get_by_id($post['data_id']);
   
          $data = array(
          "indent_id"=>$post['indent_id'],
          'branch_id'=>$user_data['parent_id'],
          'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
          "remarks"=>$post['remarks'],
          ); 

        $this->db->where('id',$post['data_id']);
        $this->db->set('modified_by',$user_data['id']);
        $this->db->set('modified_date',date('Y-m-d H:i:s'));
        $this->db->update('hms_indent_sale',$data);
        $sess_medicine_list= $this->session->userdata('medicine_id');
        $this->db->where('sales_id',$post['data_id']);
        $this->db->delete('hms_indent_sale_to_indent');
         

        if(!empty($sess_medicine_list))
        { 
 
          foreach($sess_medicine_list as $medicine_list)
          {
            $data_purchase_topurchase = array(
            "sales_id"=>$post['data_id'],
            'medicine_id'=>$medicine_list['mid'],
            'qty'=>$medicine_list['qty'],
            'discount'=>$medicine_list['discount'],
            'sgst'=>$medicine_list['sgst'],
            'igst'=>$medicine_list['igst'],
            'cgst'=>$medicine_list['cgst'],
            'hsn_no'=>$medicine_list['hsn_no'],
            'total_amount'=>$medicine_list['total_amount'],
            'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
            'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
            'batch_no'=>$medicine_list['batch_no'],
            'bar_code'=>$medicine_list['bar_code'],
            'per_pic_price'=>$medicine_list['per_pic_amount'],
            'conversion'=>$medicine_list['conversion'],
            'actual_amount'=>$medicine_list['sale_amount']

            );
            $this->db->insert('hms_indent_sale_to_indent',$data_purchase_topurchase);
              $this->db->where(array('parent_id'=>$post['data_id'],'type'=>3));
              $this->db->delete('hms_medicine_stock');
            $data_new_stock=array("branch_id"=>$user_data['parent_id'],
            "type"=>8,
            "parent_id"=>$post['data_id'],
            "m_id"=>$medicine_list['mid'],
            "credit"=>$medicine_list['qty'],
            "debit"=>0,
            "batch_no"=>$medicine_list['batch_no'],
            "conversion"=>$medicine_list['conversion'],
            "mrp"=>$medicine_list['sale_amount'],
            "discount"=>$medicine_list['discount'],
            'sgst'=>$medicine_list['sgst'],
            'igst'=>$medicine_list['igst'],
            'cgst'=>$medicine_list['cgst'],
            'hsn_no'=>$medicine_list['hsn_no'],
            'bar_code'=>$medicine_list['bar_code'],
            "total_amount"=>$medicine_list['total_amount'],
            "expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
            'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
            'per_pic_rate'=>$medicine_list['per_pic_amount'],
            "created_by"=>$created_by['created_by'],
            
            "created_date"=>date('Y-m-d',strtotime($post['sales_date']))
            );
            $this->db->insert('hms_medicine_stock',$data_new_stock);

         }
        }

         $purchase_id= $post['data_id'];

    }
  else
  {
          $sale_no = generate_unique_id(51);
          $data = array(
                "indent_id"=>$post['indent_id'],
                'branch_id'=>$user_data['parent_id'],
                'sale_no'=>$sale_no,
                'sale_date'=>date('Y-m-d',strtotime($post['sales_date'])),
              ); 
        
        $this->db->set('created_by',$user_data['id']);
        $this->db->set('created_date',date('Y-m-d H:i:s'));
        $this->db->insert('hms_indent_sale',$data);
        $purchase_id= $this->db->insert_id();


    $sess_medicine_list= $this->session->userdata('medicine_id');
    if(!empty($sess_medicine_list))
    { 
       foreach($sess_medicine_list as $medicine_list)
       {
          $m_data= explode('.',$medicine_list['mid']);
          $data_purchase_topurchase = array(
          "sales_id"=>$purchase_id,
          'medicine_id'=>$medicine_list['mid'],
          'qty'=>$medicine_list['qty'],
          'discount'=>$medicine_list['discount'],
          'sgst'=>$medicine_list['sgst'],
          'igst'=>$medicine_list['igst'],
          'cgst'=>$medicine_list['cgst'],
          'hsn_no'=>$medicine_list['hsn_no'],
          'bar_code'=>$medicine_list['bar_code'],
          'total_amount'=>$medicine_list['total_amount'],
          'expiry_date'=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
          'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
          'batch_no'=>$medicine_list['batch_no'],
          'per_pic_price'=>$medicine_list['per_pic_amount'],
          'conversion'=>$medicine_list['conversion'],
          'actual_amount'=>$medicine_list['sale_amount'],
          'created_date'=>date('Y-m-d H:i:s'),
          'created_by'=>$user_data['id']
          );
          $this->db->insert('hms_indent_sale_to_indent',$data_purchase_topurchase);
          $data_new_stock=array("branch_id"=>$user_data['parent_id'],
            "type"=>8,
            "parent_id"=>$purchase_id,
            "m_id"=>$medicine_list['mid'],
            "credit"=>$medicine_list['qty'],
            "debit"=>0,
            "batch_no"=>$medicine_list['batch_no'],
            "conversion"=>$medicine_list['conversion'],
            "mrp"=>$medicine_list['sale_amount'],
            "discount"=>$medicine_list['discount'],
            'sgst'=>$medicine_list['sgst'],
            'hsn_no'=>$medicine_list['hsn_no'],
            'igst'=>$medicine_list['igst'],
            'cgst'=>$medicine_list['cgst'],
            'bar_code'=>$medicine_list['bar_code'],
            "total_amount"=>$medicine_list['total_amount'],
            "expiry_date"=>date('Y-m-d',strtotime($medicine_list['exp_date'])),
            'manuf_date'=>date('Y-m-d',strtotime($medicine_list['manuf_date'])),
            'per_pic_rate'=>$medicine_list['per_pic_amount'],
            "created_by"=>$user_data['id'],
            "created_date"=>date('Y-m-d',strtotime($post['sales_date']))
              );
           $this->db->insert('hms_medicine_stock',$data_new_stock);
          
        }
      } 
     
    } 
    $this->session->unset_userdata('medicine_id');
    return $purchase_id;  
  }

    public function delete($id="")
    {
      if(!empty($id) && $id>0)
      {
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('is_deleted',1);
      $this->db->set('deleted_by',$user_data['id']);
      $this->db->set('deleted_date',date('Y-m-d H:i:s'));
      $this->db->where('id',$id);
      $this->db->update('hms_indent_sale');
      } 
    }

    public function deleteall($ids=array())
    {
      if(!empty($ids))
      {
        $id_list = [];
        foreach($ids as $id)
        {
          if(!empty($id) && $id>0)
          {
                  $id_list[]  = $id;
          } 
        }
        $branch_ids = implode(',', $id_list);
      $user_data = $this->session->userdata('auth_users');
      $this->db->set('is_deleted',1);
      $this->db->set('deleted_by',$user_data['id']);
      $this->db->set('deleted_date',date('Y-m-d H:i:s'));
      $this->db->where('id IN ('.$branch_ids.')');
      $this->db->update('hms_indent_sale');
      } 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_indent_sale');
        $result = $query->result(); 
        return $result; 
    } 

   public function medicine_list($ids="",$batch_no="")
    { 
    $users_data = $this->session->userdata('auth_users'); 
    $medicine_list = $this->session->userdata('medicine_id');
    //print_r($medicine_list);die;
    $keys = '';
    if(!is_array($ids) || !is_array($batch_no))
    {
      $keys = "'".$ids.'.'.$batch_no."'";
    }
    else if(is_array($ids) || is_array($batch_no))
    { 
      $key_arr = [];
      $total_key = count($ids);
      $i=0;
      foreach($ids as $id)
      {
              $key_arr[] = "'".$id.'.'.$batch_no[$i]."'";
             $i++;
      }
      $keys = implode(',', $key_arr);
      //echo $key;
    }
    else //if(is_array($ids) && is_array($batch_no))
    {
           // $keys = implode(',', array_keys($medicine_list));

      $arr_mids = [];
      if(!empty($medicine_list))
      { 
      foreach($medicine_list as $medicine_data)
      {
      $arr_mids[] = "'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
      }
      $keys = implode(',', $arr_mids);
      }
      //$keys = $ids.'.'.$batch_no;
    }
    $this->db->select('hms_medicine_entry.*, hms_medicine_stock.bar_code,hms_medicine_stock.hsn_no as hsn,hms_medicine_stock.mrp as m_rp,hms_medicine_stock.conversion as conv,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2, hms_medicine_stock.batch_no,hms_medicine_stock.expiry_date,hms_medicine_stock.manuf_date,hms_medicine_stock.debit as qty');
   // $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id'); 
   //12feb2019 3.10 PM
   
   $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id AND (hms_medicine_stock.type=1 OR hms_medicine_stock.type=6 OR hms_medicine_stock.type=5) AND hms_medicine_stock.is_deleted=0');
    if(!empty($keys))
    { 
        $this->db->where('CONCAT(hms_medicine_stock.m_id,".",hms_medicine_stock.batch_no) IN ('.$keys.') ');
    } 
  
    $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
    $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

    $this->db->where('hms_medicine_entry.is_deleted','0');  
    $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
    $this->db->group_by('hms_medicine_entry.id, hms_medicine_stock.batch_no');  
    $query = $this->db->get('hms_medicine_stock');
    $result = $query->result(); 
    
    
    //echo $this->db->last_query();die;
    return $result; 
    }

   public function get_batch_med_qty($mid="",$batch_no="")
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('(sum(debit)-sum(credit)) as total_qty');
      $this->db->where('branch_id',$user_data['parent_id']); 
      $this->db->where('m_id',$mid);
      $this->db->where('batch_no',$batch_no);
      $query = $this->db->get('hms_medicine_stock');
      return $query->row_array();
    }

    public function medicine_list_search()
    {

      $users_data = $this->session->userdata('auth_users'); 
      $added_medicine_list = $this->session->userdata('medicine_id');
      //print_r($added_medicine_list);
      $added_medicine_ids = [];
      $added_medicine_batch = [];
    
      $post = $this->input->post();  
    $this->db->select('hms_medicine_stock.*,hms_medicine_stock.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_stock.hsn_no as hsn,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,hms_medicine_stock.batch_no,(sum(hms_medicine_stock.debit)-sum(hms_medicine_stock.credit)) as qty, hms_medicine_entry.min_alrt');  
        //$this->db->join('hms_indent_sale','hms_indent_sale.id = hms_indent_sale_to_indent.sales_id');
      $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');
             

    //  $this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_stock.batch_no = batch_no)>0');

      
      
    if(!empty($post['medicine_name']))
    {
      
      //echo 'fsf';die;
      $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$post['medicine_name'].'%"');
      
    }

    if(!empty($post['medicine_code']))
    {  

      $this->db->where('hms_medicine_entry.medicine_code LIKE "'.$post['medicine_code'].'%"');
      
    }
  
    
    if(!empty($post['qty']))
    {  
      $this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$post['qty'].' as qty');
      
    }
    if(!empty($post['rate']))
    {  
      $this->db->where('hms_medicine_entry.mrp = "'.$post['rate'].'"');
      
    }
    if(!empty($post['discount']))
    {  
      //echo 'fsf';die;
      $this->db->where('hms_medicine_entry.discount = "'.$post['discount'].'"');
      
    }
    if(!empty($post['sgst']))
    {  
      $this->db->where('hms_medicine_entry.sgst = "'.$post['sgst'].'"');
      
    }
    if(!empty($post['cgst']))
    {  
      $this->db->where('hms_medicine_entry.cgst = "'.$post['cgst'].'"');
      
    }
    if(!empty($post['hsn_no']))
    {  
      $this->db->where('hms_medicine_entry.hsn_no = "'.$post['hsn_no'].'"');
      
    }
    if(!empty($post['igst']))
    {  
      $this->db->where('hms_medicine_entry.igst = "'.$post['igst'].'"');
      
    }
    if(!empty($post['batch_number']))
    {  
      $this->db->where('hms_medicine_stock.batch_no = "'.$post['batch_number'].'"');
      
    }
    if(!empty($post['bar_code']))
    {  
      $this->db->where('hms_medicine_stock.bar_code LIKE "'.$post['bar_code'].'%"');
      
    }
    if(!empty($post['stock']))
    {  
      $this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id)<='.$post['stock'].'');
      
    }
  
    if(!empty($post['packing']))
    {  
      $this->db->where('hms_medicine_entry.packing LIKE "'.$post['packing'].'"');
      
    }
    
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
    if(!empty($post['medicine_company']))
    {  
      $this->db->where('hms_medicine_company.company_name LIKE"'.$post['medicine_company'].'%"');

      
    }


    if(isset($added_medicine_list) && !empty($added_medicine_list))
    { 
      $arr_mids = [];
      if(!empty($added_medicine_list))
      { 
      foreach($added_medicine_list as $medicine_data)
      {
      $arr_mids[] = "'".$medicine_data['mid'].".".$medicine_data['batch_no']."'";
      }
            $keys = implode(',', $arr_mids);
      } 
           
      $this->db->where('CONCAT(hms_medicine_stock.m_id,".",hms_medicine_stock.batch_no) NOT IN ('.$keys.') ');
    } 

    $this->db->where('hms_medicine_stock.kit_status',0);
    $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
    $this->db->where('hms_medicine_entry.is_deleted','0'); /* previous comment in it */
    $this->db->group_by('hms_medicine_stock.m_id');
    $this->db->group_by('hms_medicine_stock.batch_no');
        $this->db->from('hms_medicine_stock'); 
        $query = $this->db->get(); 
    
        return  $query->result();
     
    }

    function get_all_detail_print($ids="",$branch_ids="")
    {
        $result_sales=array();
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_indent_sale.*,hms_indent.indent"); 
        $this->db->join('hms_indent','hms_indent_sale.indent_id=hms_indent.id');
        $this->db->where('hms_indent_sale.is_deleted','0'); 
        $this->db->where('hms_indent_sale.branch_id = "'.$branch_ids.'"'); 
        $this->db->where('hms_indent_sale.id = "'.$ids.'"');
        $this->db->from($this->table);
        $result_sales['sales_list']= $this->db->get()->result();

        $this->db->select('hms_indent_sale_to_indent.*,hms_indent_sale_to_indent.discount as m_discount,hms_medicine_entry.*,hms_medicine_entry.discount as m_disc,hms_indent_sale_to_indent.sgst as m_sgst,hms_indent_sale_to_indent.igst as m_igst,hms_indent_sale_to_indent.cgst as m_cgst,hms_indent_sale_to_indent.batch_no as m_batch_no,hms_indent_sale_to_indent.expiry_date as m_expiry_date,hms_indent_sale_to_indent.hsn_no as m_hsn_no');
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_indent_sale_to_indent.medicine_id'); 
        $this->db->where('hms_indent_sale_to_indent.sales_id = "'.$ids.'"');
        $this->db->from('hms_indent_sale_to_indent');
        $result_sales['sales_list']['medicine_list']=$this->db->get()->result();
        return $result_sales;
    
    }

    function template_format($data=""){
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_print_branch_template.*');
      $this->db->where($data);
      //$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
      $this->db->from('hms_print_branch_template');
      $query=$this->db->get()->row();
      //print_r($query);exit;
      return $query;

    }

    public function check_stock_avability($id="",$batch_no=""){
      //if(!empty($batch_no)){
      $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_medicine_stock.*,hms_medicine_entry.*,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id and batch_no="'.$batch_no.'" and m_id="'.$id.'") as avl_qty');   
     $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');
    $this->db->where('hms_medicine_entry.branch_id = "'.$user_data['parent_id'].'"');
    $this->db->where('hms_medicine_stock.batch_no',$batch_no);
    
    $this->db->where('hms_medicine_stock.m_id',$id);
     $this->db->group_by('hms_medicine_stock.batch_no');
    $query = $this->db->get('hms_medicine_stock');
        $result = $query->row(); 
        //echo $this->db->last_query();die;
         return $result->avl_qty;
     // }
    }


    function sms_template_format($data="")
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_sms_branch_template.*');
      $this->db->where($data);
      $this->db->where('hms_sms_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
      $this->db->from('hms_sms_branch_template');
      $query=$this->db->get()->row();
      //echo $this->db->last_query();
      return $query;

    }

    function sms_url()
    {
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_branch_sms_config.*');
      //$this->db->where($data);
      $this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"'); 
      $this->db->from('hms_branch_sms_config');
      $query=$this->db->get()->row();
      //echo $this->db->last_query();
      return $query;

    }

    function check_bar_code($bar_code="",$medicine_id="")
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');
    if(!empty($bar_code))
    {
     $this->db->where('bar_code',$bar_code);  
    }
    $this->db->where('branch_id',$users_data['parent_id']);
    $result= $this->db->get('hms_medicine_stock')->result();
    if(count($result)>0)
    {
      foreach($result as $res)
      {
        $res_new_medicine_array[]= $res->m_id;
      }
        $new_array= array_unique($res_new_medicine_array);
        if(in_array($medicine_id,$new_array))
        {
          return 1;
        }
        else
        {
          return 2;
        } 
    }
  }
  
  public function opd_patient($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_opd_prescription.*,hms_patient.patient_code as p_code,hms_indent.indent as p_name,hms_patient.simulation_id,hms_patient.mobile_no as mobile,hms_patient.gender,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.relation_name,hms_opd_booking.referred_by,hms_opd_booking.referral_doctor,hms_opd_booking.ref_by_other,hms_opd_booking.referral_hospital,hms_opd_booking.remarks,hms_opd_prescription.id as pres_id,hms_patient.patient_email,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.city_id,hms_patient.state_id,hms_patient.id as pa_id"); 
          $this->db->join('hms_opd_booking','hms_opd_booking.id=hms_opd_prescription.booking_id','left');
          $this->db->join('hms_patient','hms_patient.id=hms_opd_prescription.patient_id','left');
          
          $this->db->where('hms_opd_prescription.is_deleted','0'); 
          $this->db->where('hms_opd_prescription.branch_id = "'.$user_data['parent_id'].'"');
          //$this->db->where('hms_patient.patient_code LIKE "'.$vals.'%"');
          $this->db->where('hms_opd_booking.booking_code LIKE "'.$vals.'%"');
          $this->db->from('hms_opd_prescription'); 
          $this->db->group_by('hms_patient.id');
          $query = $this->db->get(); 
          $result = $query->result(); 
          //echo $this->db->last_query(); exit;
          //echo "<pre>"; print_r($result); exit;
          if(!empty($result))
          {  
            $data = array();
            foreach($result as $vals)
            {
                $name = $vals->p_name.' ('.$vals->p_code.')'.'|'.$vals->p_name.'|'.$vals->simulation_id.'|'.$vals->p_code.'|'.$vals->mobile.'|'.$vals->gender.'|'.$vals->relation_type.'|'.$vals->relation_simulation_id.'|'.$vals->relation_name.'|'.$vals->referred_by.'|'.$vals->referral_doctor.'|'.$vals->ref_by_other.'|'.$vals->referral_hospital.'|'.$vals->remarks.'|'.$vals->pres_id.'|'.$vals->patient_email.'|'.$vals->age_y.'|'.$vals->age_m.'|'.$vals->age_d.'|'.$vals->age_h.'|'.$vals->address.'|'.$vals->address2.'|'.$vals->address3.'|'.$vals->city_id.'|'.$vals->state_id.'|'.$vals->pa_id;
                array_push($data, $name);
            }

            echo json_encode($data);
          }
          //return $response; 
      } 
    }


    public function prescription_medicine_list($medicine_id="")
    { 
        //print_r($medicine_id); exit;
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_medicine_entry.*');
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
        $this->db->where('hms_medicine_entry.id',$medicine_id);
        $this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->group_by('hms_medicine_entry.id');  
        $query = $this->db->get('hms_medicine_entry');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function prescription_medicine_array_list($ids="")
    { 
        $users_data = $this->session->userdata('auth_users'); 
        $medicine_list = $this->session->userdata('prescription_medicine_id');
        //print_r($ids);die;
        $keys = '';
        if(is_array($ids))
        { 
          $key_arr = [];
          $total_key = count($ids);
          $i=0;
          foreach($ids as $id)
          {
              $key_arr[] = "'".$id."'";
              $i++;
          }
          $keys = implode(',', $key_arr);
          
        }
        else 
        {
          $arr_mids = [];
          if(!empty($medicine_list))
          { 
          foreach($medicine_list as $medicine_data)
          {
            $arr_mids[] = "'".$medicine_data['mid']."'";
          }
            $keys = implode(',', $arr_mids);
          }
          //$keys = $ids.'.'.$batch_no;
        }
        //print_r($keys);die;
        $this->db->select('hms_medicine_entry.*');
        //$this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id'); 
        if(!empty($keys))
        { 
            $this->db->where('hms_medicine_entry.id IN ('.$keys.') ');
        } 
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
        $this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->group_by('hms_medicine_entry.id');  
        $query = $this->db->get('hms_medicine_entry');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }


/* Estimate data */

    public function estimate_medicine($vals)
  {
    $response = '';
      if(!empty($vals))
      {
          $user_data = $this->session->userdata('auth_users');
              $this->db->select('hms_estimate_sale.*,hms_indent.indent,hms_patient.mobile_no,hms_patient.gender');
              $this->db->join('hms_patient','hms_patient.id=hms_estimate_sale.patient_id');
    $this->db->from('hms_estimate_sale'); 

     $this->db->where('hms_estimate_sale.sale_no LIKE "'.$vals.'%"');
    //$this->db->where('hms_estimate_sale.is_deleted','0');
    $query = $this->db->get(); 
 // print_r($this->db->last_query());die;
  $result= $query->row_array();
   
          if(!empty($result))
          { 
            $data = array($result['sale_no'].'|'.$result['patient_name'].'|'.$result['mobile_no'].'|'.$result['gender']);
            // foreach($result as $vals)
            // {
            //     $name = $vals->purchase_id.'|'.$vals->medicine_code;
            //     array_push($data, $name);
            // }

            echo json_encode($data);
          }
          //return $response; 
      }
  }


  public function get_estimate_medicine_by_id($sales_id)
  {
    $response = '';
      if(!empty($sales_id))
      {
          $user_data = $this->session->userdata('auth_users');
          $this->db->select("hms_estimate_sale.id,hms_estimate_sale.total_amount as grand_total,hms_estimate_sale.discount as total_discount,hms_estimate_sale.sale_no,hms_estimate_sale_to_estimate.medicine_id,hms_estimate_sale_to_estimate.qty,hms_estimate_sale_to_estimate.per_pic_price,hms_estimate_sale_to_estimate.discount ,hms_estimate_sale_to_estimate.bar_code,hms_estimate_sale_to_estimate.hsn_no,hms_estimate_sale_to_estimate.total_amount ,hms_estimate_sale_to_estimate.manuf_date,hms_estimate_sale_to_estimate.expiry_date,hms_estimate_sale_to_estimate.sgst sgst,hms_estimate_sale_to_estimate.cgst a,hms_estimate_sale_to_estimate.igst ,hms_medicine_entry.medicine_name,hms_medicine_entry.medicine_code,hms_medicine_entry.hsn_no,hms_medicine_entry.packing,hms_medicine_entry.conversion"); 
         $this->db->join('hms_estimate_sale_to_estimate','hms_estimate_sale_to_estimate.sales_id=hms_estimate_sale.id','left');
          $this->db->join('hms_medicine_entry','hms_medicine_entry.id=hms_estimate_sale_to_estimate.medicine_id','left'); 
         
          $this->db->where('hms_estimate_sale.sale_no ',$sales_id);
          $this->db->from('hms_estimate_sale'); 
          $query = $this->db->get(); 
          $result = $query->result_array();
            $post_mid_arr = array();
            foreach($result as $vals)
           {

        
               $post_mid_arr[$vals['medicine_id'].'.'.'0'] = array('mid'=>$vals['medicine_id'],'id'=>$vals['medicine_id'], 'batch_no'=>'', 'qty'=>$vals['qty'], 'exp_date'=>$vals['expiry_date'],'manuf_date'=>$vals['manuf_date'],'discount'=>$vals['discount'],'bar_code'=>$vals['bar_code'],'conversion'=>$vals['conversion'],'hsn_no'=>$vals['hsn_no'],'cgst'=>$vals['cgst'],'sgst'=>$vals['sgst'],'igst'=>$vals['igst'], 'per_pic_amount'=>$vals['per_pic_rate'],'mrp'=>$vals['mrp'],'total_amount'=>$vals['total_amount'],'total_pricewith_medicine'=>$vals['total_amount'],'medicine_name'=>$vals['medicine_name']); 
            }
            return $post_mid_arr;
 
      }
  }

    // op 10/10/2019
    public function testing($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('id','DESC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id);
        }
        else
        {
           $this->db->where('branch_id',$users_data['parent_id']); 
        }
         
        $query = $this->db->get('hms_indent');
        $result = $query->result(); 
        return $result; 
    }

    /* Letter head */
      public function letterhead_template_format()
      {
        
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_print_letter_head_template_setting.*');
      $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
      $this->db->where('unique_id',5); 
      $this->db->from('hms_print_letter_head_template_setting');
      $query=$this->db->get()->row();
      return $query;

      }
  /*Letter head */



} 
?>