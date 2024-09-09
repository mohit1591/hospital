<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Garbage_stock_item_model extends CI_Model 
{
  var $table = 'hms_canteen_garbage_stock_item';
   

  var $column = array('hms_canteen_garbage_stock_item.garbage_no','hms_canteen_garbage_stock_item.remarks','hms_canteen_garbage_stock_item.total_amount','hms_canteen_garbage_stock_item.garbage_date','hms_canteen_garbage_stock_item.created_date');  
    var $order = array('id' => 'desc'); 


  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query()
  {
    $search = $this->session->userdata('garbage_stock_item_search');
         
    $users_data = $this->session->userdata('auth_users');
    $this->db->select("hms_canteen_garbage_stock_item.*"); 
    $this->db->where('hms_canteen_garbage_stock_item.is_deleted','0'); 
    
    $this->db->where('hms_canteen_garbage_stock_item.branch_id',$users_data['parent_id']); 
  
    $this->db->from($this->table); 
    $i = 0;
    if(isset($search) && !empty($search))
    {
      if(!empty($search['start_date']))
      {
      $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
      $this->db->where('hms_canteen_garbage_stock_item.created_date >= "'.$start_date.'"');
      }

      if(!empty($search['end_date']))
      {
      $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
      $this->db->where('hms_canteen_garbage_stock_item.created_date <= "'.$end_date.'"');
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
    elseif(!empty($search["employee"]) && is_numeric($search['employee']))
    {
      $emp_ids=  $search["employee"];
    }


    if(isset($emp_ids) && !empty($emp_ids))
    { 
      $this->db->where('hms_canteen_garbage_stock_item.created_by IN ('.$emp_ids.')');
    }

    // if(!empty($search['medicine_name']))
    // {
    //  $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$search['medicine_name'].'%"');
    // }

    // if(!empty($search['medicine_company']))
    // {
    //  // $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
    //  $this->db->where('hms_medicine_company.company_name LIKE"'.$search['medicine_company'].'%"');
    // }

    // if(!empty($search['medicine_code']))
    // {
    
    //  $this->db->where('hms_medicine_entry.medicine_code',$search['medicine_code']);
    // }
    
    
    // if(isset($search['unit1']) && $search['unit1']!="")
    // {
    //  $this->db->where('hms_medicine_unit.id = "'.$search['unit1'].'"');
    // }

    // if(isset($search['unit2']) && $search['unit2']!="")
    // {
    //  $this->db->where('hms_medicine_unit_2.id ="'.$search['unit2'].'"');
    // }

    // if(isset($search['packing']) && $search['packing']!="")
    // {
    
    // //$this->db->where('packing',$search['packing']);
    //  $this->db->where('hms_medicine_entry.packing LIKE "'.$search['packing'].'%"');
    // }
    // if(isset($search['hsn_no']) && $search['hsn_no']!="")
    // {

    // //$this->db->where('packing',$search['packing']);
    // $this->db->where('hms_medicine_entry.hsn_no LIKE "'.$search['hsn_no'].'%"');
    // }
    // if(isset($search['cgst']) && $search['cgst']!="")
    // {

    // //$this->db->where('packing',$search['packing']);
    // $this->db->where('hms_medicine_entry.cgst LIKE "'.$search['cgst'].'%"');
    // }
    // if(isset($search['sgst']) && $search['sgst']!="")
    // {

    // //$this->db->where('packing',$search['packing']);
    // $this->db->where('hms_medicine_entry.sgst LIKE "'.$search['sgst'].'%"');
    // }
    // if(isset($search['igst']) && $search['igst']!="")
    // {

    // //$this->db->where('packing',$search['packing']);
    // $this->db->where('hms_medicine_entry.igst LIKE "'.$search['igst'].'%"');
    // }
    

    // if(isset($search['mrp_to']) && $search['mrp_to']!="")
    // {
    //  $this->db->where('hms_medicine_entry.mrp >="'.$search['mrp_to'].'"');
    // }

    // if(isset($search['mrp_from']) &&$search['mrp_from']!="")
    // {
    //  $this->db->where('hms_medicine_entry.mrp <="'.$search['mrp_from'].'"');
    // }

    // if(isset($search['purchase_to']) &&$search['purchase_to']!="")
    // {
    //  $this->db->where('hms_medicine_entry.purchase_rate >= "'.$search['purchase_to'].'"');
    // }

    // if(isset($search['purchase_from']) &&$search['purchase_from']!="")
    // {
    //  $this->db->where('hms_medicine_entry.purchase_rate <="'.$search['purchase_from'].'"');
    // }

    // if(isset($search['rack_no']) &&$search['rack_no']!="")
    // {
    //  //$this->db->join('hms_medicine_racks','hms_medicine_racks.id = hms_medicine_entry.rack_no','left');
    //  $this->db->where('hms_medicine_racks.rack_no',$search['rack_no']);
      
    // }
    // if(isset($search['min_alert']) &&$search['min_alert']!="")
    // {
    //  $this->db->where('hms_medicine_entry.min_alrt',$search['min_alert']);
    // }
  //       if(isset($search['discount']) &&$search['discount']!="")
    // {
    //  $this->db->where('hms_medicine_entry.discount',$search['discount']);
    // }     

    // }
  
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

  public function search_report_data()
  {
    $search = $this->session->userdata('garbage_stock_item_search');
    $users_data = $this->session->userdata('auth_users');
    $this->db->select("hms_canteen_garbage_stock_item.*"); 
    $this->db->where('hms_canteen_garbage_stock_item.is_deleted','0'); 
    
    $this->db->where('hms_canteen_garbage_stock_item.branch_id',$users_data['parent_id']); 
  
    $this->db->from($this->table); 
    $i = 0;
    if(isset($search) && !empty($search))
    {
      if(!empty($search['start_date']))
      {
      $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
      $this->db->where('hms_canteen_garbage_stock_item.created_date >= "'.$start_date.'"');
      }

      if(!empty($search['end_date']))
      {
      $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
      $this->db->where('hms_canteen_garbage_stock_item.created_date <= "'.$end_date.'"');
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
    elseif(!empty($search["employee"]) && is_numeric($search['employee']))
    {
      $emp_ids=  $search["employee"];
    }


    if(isset($emp_ids) && !empty($emp_ids))
    { 
      $this->db->where('hms_canteen_garbage_stock_item.created_by IN ('.$emp_ids.')');
    }
     $result= $this->db->get()->result();
     return $result;
  }

  public function get_by_id($id)
  {
    $this->db->select("hms_canteen_garbage_stock_item.*");
    $this->db->from('hms_canteen_garbage_stock_item'); 
    $this->db->where('hms_canteen_garbage_stock_item.id',$id);
    
    $this->db->where('hms_canteen_garbage_stock_item.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }
  function get_purchase_to_purchase_by_id($id)
  {
     $users_data = $this->session->userdata('auth_users');
     $this->db->select('hms_canteen_garbage_stock_item_to_garbage_stock_item.garbage_id,hms_canteen_garbage_stock_item_to_garbage_stock_item.category_id,hms_canteen_garbage_stock_item_to_garbage_stock_item.total_amount as amount,path_item.item as item_name,path_item.item_code,hms_canteen_garbage_stock_item_to_garbage_stock_item.qty as quantity,hms_stock_item_unit.unit,hms_canteen_garbage_stock_item_to_garbage_stock_item.per_pic_price as item_price,path_item.id as item_id,path_stock_category.category');
    $this->db->from('hms_canteen_garbage_stock_item_to_garbage_stock_item'); 
    $this->db->where('hms_canteen_garbage_stock_item_to_garbage_stock_item.garbage_id',$id);
    $this->db->join('path_item','path_item.id=hms_canteen_garbage_stock_item_to_garbage_stock_item.item_id','left');
    $this->db->where('hms_canteen_garbage_stock_item_to_garbage_stock_item.branch_id',$users_data['parent_id']);
    $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=hms_canteen_garbage_stock_item_to_garbage_stock_item.unit_id','left');
    $this->db->join('path_stock_category`','path_stock_category.id=hms_canteen_garbage_stock_item_to_garbage_stock_item.category_id','left');
  
    $query = $this->db->get()->result_array(); 
    $result = [];
    if(!empty($query))
    {
      foreach($query as $item_list) 
      {
            $result[$item_list['item_id']] =  array('item_id'=>$item_list['item_id'],'category_id'=>$item_list['category_id'],'total_price'=>$item_list['amount'],'item_code'=>$item_list['item_code'],'item_name'=>$item_list['item_name'].'-'.$item_list['category'],'amount'=>$item_list['item_price']*$item_list['quantity'],'unit'=>$item_list['unit'],'item_price'=>$item_list['item_price'],'quantity'=>$item_list['quantity'],'total_amount'=>''); 

       } 
    } 
    
    return $result;
    //return $query->result_array();
  }
    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
  {
  $users_data = $this->session->userdata('auth_users'); 
  $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
  $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
  $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
  $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
  $this->db->where('hms_payment_mode_field_value_acc_section.type',3);
  $this->db->where('hms_payment_mode_field_value_acc_section.section_id',14);
  $this->db->where('hms_payment_mode_field_value_acc_section.branch_id = "'.$users_data['parent_id'].'"');
  $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
  //echo $this->db->last_query();die;
  return $query;
  }

  public function save()
  {
      $users_data = $this->session->userdata('auth_users');
    $post = $this->input->post();
    $emp_type='';
    if(isset($post['employee_type']) && !empty($post['employee_type']))
    {
       $emp_type= $post['employee_type'];
    }
    $data_purchase= array(
      'branch_id'=>$users_data['parent_id'],
      //'garbage_no'=>$post['garbage_code'],
      //'user_type'=>$post['user_type'],
      'garbage_date'=>date('Y-m-d',strtotime($post['garbage_date'])),
      //'net_amount'=>$post['net_amount'],
      //'user_type_id'=>$post['user_type_id'],
      'total_amount'=>$post['total_amount'],
      //'employee_type'=>$emp_type,
      'remarks'=>$post['remarks']
      //'paid_amount'=>$post['pay_amount'],
      //'balance'=>$post['balance_due'],
      //'discount'=>$post['discount_amount'],
      //'payment_mode'=>$post['payment_mode'],
    //  'discount_percent'=>$post['discount_percent'],
      );

    if(!empty($post['data_id']) && $post['data_id']>0)
    {
      
      //$blance= str_replace(',', '', $post['net_amount'])-$post['pay_amount'];
      $this->db->set('modified_by',$users_data['id']);
      $this->db->set('modified_date',date('Y-m-d H:i:s'));
      $this->db->where('id', $post['data_id']);
      $this->db->update('hms_canteen_garbage_stock_item',$data_purchase);
                
      /*add sales banlk detail*/
      $this->db->where(array('branch_id'=>$users_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>3,'section_id'=>14));
      $this->db->delete('hms_payment_mode_field_value_acc_section');

      if(!empty($post['field_name']))
      {
      $post_field_value_name= $post['field_name'];
      $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
            $data_field_value= array(
            'field_value'=>$post['field_name'][$i],
            'field_id'=>$post['field_id'][$i],
            'type'=>3,
            'section_id'=>14,
            'p_mode_id'=>$post['payment_mode'],
            'branch_id'=>$users_data['parent_id'],
            'parent_id'=>$post['data_id'],
            'ip_address'=>$_SERVER['REMOTE_ADDR']
            );
          $this->db->set('created_by',$users_data['id']);
          $this->db->set('created_date',date('Y-m-d H:i:s'));
          $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

          }
      }

      /*add sales banlk detail*/


      
      

            $garbage_stock_item_list=$this->session->userdata('garbage_stock_item_list');
      
      if(!empty($garbage_stock_item_list))
      {
        $where_purchase_stock=array('garbage_id'=>$post['data_id']);
        $this->db->where($where_purchase_stock);
        $this->db->delete('hms_canteen_garbage_stock_item_to_garbage_stock_item');
        
        $where_path_stock=array('parent_id'=>$post['data_id'],'type'=>3);
        $this->db->where($where_path_stock);
        $this->db->delete('path_stock_item');

        foreach($garbage_stock_item_list as $stock_item_list)
        {
        
           $stock_purchase_item = array(
            'branch_id'=>$users_data['parent_id'],
            'garbage_id'=>$post['data_id'],
            'item_id'=>$stock_item_list['item_id'],
            'per_pic_price'=>$stock_item_list['item_price'],
            'unit_id'=>$stock_item_list['unit'],
            'category_id'=>$stock_item_list['category_id'],
            'total_amount'=>$stock_item_list['quantity']*$stock_item_list['item_price'],
            'qty'=>$stock_item_list['quantity']
          );
          $this->db->insert('hms_canteen_garbage_stock_item_to_garbage_stock_item',$stock_purchase_item);
          //echo $this->db->last_query(); 
          $data_new_stock=array("branch_id"=>$users_data['parent_id'],
              "type"=>3,
              "parent_id"=>$post['data_id'],
              "item_id"=>$stock_item_list['item_id'],
              "credit"=>$stock_item_list['quantity'],
              "debit"=>0,
              "qty"=>$stock_item_list['quantity'],
              "price"=>$stock_item_list['item_price'],
              'cat_type_id'=>$stock_item_list['category_id'],
              "item_name"=>$stock_item_list['item_name'],
              //"vat"=>$medicine_list['vat'],
              'unit_id'=>$stock_item_list['unit'],
              //'item_code'=>$stock_item_list['item_code'],
            "total_amount"=>$stock_item_list['total_price'],
              'per_pic_price'=>$stock_item_list['item_price'],
              "created_by"=>$users_data['id'],
              "created_date"=>date('Y-m-d H:i:s'),
              );
           $this->db->insert('path_stock_item',$data_new_stock);
          //echo $this->db->last_query(); exit;
        }


                      
      }
               

        /* insert data in payment table  */

        // $payment_data = array(
        //        'parent_id'=>$post['data_id'],
        //        'branch_id'=>$users_data['parent_id'],
        //        'section_id'=>'6',
        //        'vendor_id'=>$post['user_type_id'],
        //        'total_amount'=>str_replace(',', '', $post['total_amount']),
        //        //'discount_amount'=>$post['discount_amount'],
        //        //'net_amount'=>str_replace(',', '', $post['net_amount']),
        //        'credit'=>str_replace(',', '', $post['net_amount']),
        //        'debit'=>$post['pay_amount'],
        //        'pay_mode'=>$post['payment_mode'],
        //        // 'bank_name'=>$bank_name,
        //        // 'card_no'=>$card_no,
        //        // 'cheque_no'=>$cheque_no,
        //        // 'cheque_date'=>$payment_date,
        //        'balance'=>$blance,
        //        'paid_amount'=>$post['pay_amount'],
        //        'created_date'=>date('Y-m-d H:i:s'),
        //        'created_by'=>$users_data['id']
    //                       );
    //          $this->db->insert('hms_payment',$payment_data);


        /* insert data in payment table  */

            /*add sales banlk detail*/
      $this->db->where(array('branch_id'=>$users_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>5,'section_id'=>14));
      $this->db->delete('hms_payment_mode_field_value_acc_section');

      if(!empty($post['field_name']))
      {
      $post_field_value_name= $post['field_name'];
      $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
            $data_field_value= array(
            'field_value'=>$post['field_name'][$i],
            'field_id'=>$post['field_id'][$i],
            'type'=>5,
            'section_id'=>14,
            'p_mode_id'=>$post['payment_mode'],
            'branch_id'=>$users_data['parent_id'],
            'parent_id'=>$post['data_id'],
            'ip_address'=>$_SERVER['REMOTE_ADDR']
            );
          $this->db->set('created_by',$users_data['id']);
          $this->db->set('created_date',date('Y-m-d H:i:s'));
          $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

          }
      }

      /*add sales banlk detail*/


      $this->session->unset_userdata('garbage_stock_item_list');
          $this->session->unset_userdata('garbage_stock_item_payment_array');
          $purchase_id= $post['data_id'];
    }
    else
    {

      //add
      $garbage_code = generate_unique_id(62);
      $this->db->set('created_by',$users_data['id']);
      $this->db->set('created_date',date('Y-m-d H:i:s'));
      $this->db->set('garbage_no',$garbage_code);
      $this->db->insert('hms_canteen_garbage_stock_item',$data_purchase);
      //echo $this->db->last_query();die;
      $purchase_id=$this->db->insert_id();
        /*add sales banlk detail*/

      if(!empty($post['field_name']))
      {
      $post_field_value_name= $post['field_name'];
      $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
            $data_field_value= array(
            'field_value'=>$post['field_name'][$i],
            'field_id'=>$post['field_id'][$i],
            'type'=>3,
            'section_id'=>14,
            'p_mode_id'=>$post['payment_mode'],
            'branch_id'=>$users_data['parent_id'],
            'parent_id'=>$purchase_id,
            'ip_address'=>$_SERVER['REMOTE_ADDR']
            );
          $this->db->set('created_by',$users_data['id']);
          $this->db->set('created_date',date('Y-m-d H:i:s'));
          $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
                     //echo $this->db->last_query();
          }
         }
            //die;
      /*add sales banlk detail*/
      $garbage_stock_item_list = $this->session->userdata('garbage_stock_item_list');
    
      //print '<pre>'; print_r($ipd_particular_billing_list);die;
      if(!empty($garbage_stock_item_list))
      {
        foreach($garbage_stock_item_list as $stock_item_list)
        {
        
          $stock_purchase_item = array(
            'branch_id'=>$users_data['parent_id'],
            'garbage_id'=>$purchase_id,
            'item_id'=>$stock_item_list['item_id'],
            'per_pic_price'=>$stock_item_list['item_price'],
            'category_id'=>$stock_item_list['category_id'],
            'total_amount'=>$stock_item_list['quantity']*$stock_item_list['item_price'],
            'qty'=>$stock_item_list['quantity']
          );
          $this->db->insert('hms_canteen_garbage_stock_item_to_garbage_stock_item',$stock_purchase_item);
                    //echo $this->db->last_query();die;
                    $data_new_stock=array("branch_id"=>$users_data['parent_id'],
              "type"=>3,
              "parent_id"=>$purchase_id,
              "item_id"=>$stock_item_list['item_id'],
              "credit"=>$stock_item_list['quantity'],
              "debit"=>0,
              "qty"=>$stock_item_list['quantity'],
              "price"=>$stock_item_list['item_price'],
                'cat_type_id'=>$stock_item_list['category_id'],
              "item_name"=>$stock_item_list['item_name'],
              //"vat"=>$medicine_list['vat'],
              'unit_id'=>$stock_item_list['unit'],
              'item_code'=>$stock_item_list['item_code'],
            "total_amount"=>$stock_item_list['total_price'],
              'per_pic_price'=>$stock_item_list['item_price'],
              "created_by"=>$users_data['id'],
              "created_date"=>date('Y-m-d H:i:s'),
              );
           $this->db->insert('path_stock_item',$data_new_stock);
          
        } 
        
      }

      

      /* insert data in payment table  */

        /*$payment_data = array(
                'parent_id'=>$purchase_id,
                'branch_id'=>$users_data['parent_id'],
                'section_id'=>'6',
                'type'=>'3',
                'vendor_id'=>$post['vendor_id'],
                'total_amount'=>str_replace(',', '', $post['total_amount']),
                'discount_amount'=>$post['discount_amount'],
                'net_amount'=>str_replace(',', '', $post['net_amount']),
                'credit'=>str_replace(',', '', $post['net_amount']),
                'debit'=>$post['pay_amount'],
                'pay_mode'=>$post['payment_mode'],
                // 'bank_name'=>$bank_name,
                // 'card_no'=>$card_no,
                // 'cheque_no'=>$cheque_no,
                // 'cheque_date'=>$payment_date,
                'balance'=>$blance,
                'paid_amount'=>$post['pay_amount'],
                'created_date'=>date('Y-m-d H:i:s'),
                'created_by'=>$users_data['id']
                           );
             $this->db->insert('hms_payment',$payment_data);*/
      /*add sales banlk detail*/
      /*if(!empty($post['field_name']))
      {
      $post_field_value_name= $post['field_name'];
      $counter_name= count($post_field_value_name); 
          for($i=0;$i<$counter_name;$i++) 
          {
            $data_field_value= array(
            'field_value'=>$post['field_name'][$i],
            'field_id'=>$post['field_id'][$i],
            'type'=>5,
            'section_id'=>14,
            'p_mode_id'=>$post['payment_mode'],
            'branch_id'=>$users_data['parent_id'],
            'parent_id'=>$purchase_id,
            'ip_address'=>$_SERVER['REMOTE_ADDR']
            );
          $this->db->set('created_by',$users_data['id']);
          $this->db->set('created_date',date('Y-m-d H:i:s'));
          $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

          }
      }*/

      /*add sales banlk detail*/

      /* insert data in payment table  */
      $this->session->unset_userdata('garbage_stock_item_list');
      $this->session->unset_userdata('garbage_stock_item_payment_array');
          
    }
      return $purchase_id;  
  }



    public function delete($id="")
    {
      if(!empty($id) && $id>0)
      {
      $users_data = $this->session->userdata('auth_users');
      $this->db->set('is_deleted',2);
      $this->db->set('deleted_by',$users_data['id']);
      $this->db->set('deleted_date',date('Y-m-d H:i:s'));
      $this->db->where('id',$id);
      $this->db->update('hms_canteen_garbage_stock_item');
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
      $users_data = $this->session->userdata('auth_users');
      $this->db->set('is_deleted',2);
      $this->db->set('deleted_by',$users_data['id']);
      $this->db->set('deleted_date',date('Y-m-d H:i:s'));
      $this->db->where('id IN ('.$branch_ids.')');
      $this->db->update('hms_canteen_garbage_stock_item');
      } 
    }

   
   
    function template_format($data=""){
      $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_print_branch_template.*');
      $this->db->where($data);
      $this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
      $this->db->from('hms_print_branch_template');
      $query=$this->db->get()->row();
      //print_r($query);exit;
      return $query;

    }

    public function get_patient_by_id($id)
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_patient.*');
    $this->db->from('hms_patient'); 
    $this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
    $this->db->where('hms_patient.id',$id);
    $query = $this->db->get(); 
    return $query->row_array();
  }

  public function attended_doctor_list()
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
    $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
    $query = $this->db->get('hms_doctors');
    $result = $query->result(); 
    // print '<pre>'; print_r($result);
    return $result; 
  }

  public function assigned_doctor_list()
  {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('*');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (0,2)'); 
    $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
    $query = $this->db->get('hms_doctors');
    $result = $query->result(); 
    // print '<pre>'; print_r($result);
    return $result; 
  }

  

  public function vendor_list($vendor_id="",$user_type="")
  {
    $users_data = $this->session->userdata('auth_users');
    
    if(!empty($user_type))
    {
      if($user_type==2)
      {
        if(!empty($vendor_id))
        {
        $this->db->where('id',$vendor_id);  
        }
        $this->db->select('hms_patient.id,hms_patient.patient_name as name,hms_patient.address');  
        $this->db->where('branch_id = "'.$users_data['parent_id'].'"');
        $result = $this->db->get('hms_patient')->result();
        //echo $this->db->last_query();
      }
      if($user_type==1)
      {
        if(!empty($vendor_id))
        {
        $this->db->where('id',$vendor_id);  
        }
        $this->db->select('hms_employees.id,hms_employees.name,hms_employees.address');  
        $this->db->where('branch_id = "'.$users_data['parent_id'].'"');
        $result = $this->db->get('hms_employees')->result();
      }
      if($user_type==3)
      {
        if(!empty($vendor_id))
        {
        $this->db->where('id',$vendor_id);  
        }
        $this->db->select('hms_doctors.id,hms_doctors.doctor_name as name,hms_doctors.address');  
        $this->db->where('branch_id = "'.$users_data['parent_id'].'"');
        $result = $this->db->get('hms_doctors')->result();
      }
      return $result; 
    }
    
  } 

  public function get_item_values($vals="")
  {
      $response = '';
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_canteen_stock_item.*,hms_canteen_stock_item.cat_type_id as cat_id,hms_canteen_stock_item.per_pic_price as price,hms_canteen_stock_item.cat_type_id as cat_id,hms_canteen_stock_item.id as p_id,hms_canteen_item.item,hms_canteen_item.item_code ,hms_canteen_item.price as price,(sum(hms_canteen_stock_item.debit)-sum(hms_canteen_stock_item.credit)) as remainingquantity,hms_canteen_stock_item_unit.unit,hms_canteen_stock_category.category');  
      $this->db->join('hms_canteen_item','hms_canteen_item.id = hms_canteen_stock_item.item_id');
            $this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id=hms_canteen_item.unit_id','left');
      $this->db->join('hms_canteen_stock_category`','hms_canteen_stock_category.id=hms_canteen_item.category_id','left');
      $this->db->where('hms_canteen_item.item LIKE "'.$vals.'%"');
      $this->db->where('hms_canteen_item.branch_id',$users_data['parent_id']); 
      $this->db->group_by('hms_canteen_stock_item.item_id');
      $this->db->from('hms_canteen_stock_item'); 
      $query = $this->db->get(); 
          $result = $query->result();  
          //echo $this->db->last_query();die;
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
              //$response[] = $vals->medicine_name;
          $name = $vals->item.'-'.$vals->category.'|'.$vals->item_code.'|'.$vals->unit.'|'.$vals->price.'|'.$vals->item_id.'|'.$vals->cat_id.'|'.$vals->remainingquantity;
          array_push($data, $name);
            }
              //print_r($data);die;
            echo json_encode($data);
          }
          //return $response; 
      } 
    }

    function get_employee($employee_type=""){
      $users_data = $this->session->userdata('auth_users');
    $this->db->select('*');  
    $this->db->where('branch_id = "'.$users_data['parent_id'].'"');
    if(!empty($employee_type))
    {
     $this->db->where('emp_type_id',$employee_type);  
    }
    $query = $this->db->get('hms_employees');

    $result = $query->result(); 
    return $result; 
    }

    function get_data_according_user($user_type="")
    {
      $users_data = $this->session->userdata('auth_users');
    if($user_type==2)
    {
    $this->db->select("hms_ipd_booking.*, hms_ipd_booking.patient_id as ids,hms_patient.patient_name,hms_patient.age_m,hms_patient.age_y,hms_patient.age_d,hms_patient.patient_code,hms_patient.status, hms_patient.age_y,hms_patient.gender,hms_patient.mobile_no,hms_patient.address"); 
    $this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id');
    $this->db->where('hms_patient.is_deleted','0'); 
    $this->db->where('hms_patient.branch_id',$users_data['parent_id']);
    $result= $this->db->get('hms_ipd_booking')->result();
        }
    if($user_type==3)
    {
    $this->db->select("hms_doctors.*, hms_doctors.id as ids,hms_cities.city, hms_state.state"); 
    $this->db->join('hms_cities','hms_cities.id=hms_doctors.city_id','left');
    $this->db->join('hms_state','hms_state.id=hms_doctors.state_id','left');
    $this->db->where('hms_doctors.is_deleted','0'); 
    $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
    $result= $this->db->get('hms_doctors')->result();
    }
    if($user_type==1)
    {
    $this->db->select("hms_emp_type.*,hms_emp_type.id as ids,"); 
    $this->db->from('hms_emp_type'); 
    $this->db->where('hms_emp_type.is_deleted','0');
    $this->db->where('hms_emp_type.branch_id',$users_data['parent_id']);
    $result= $this->db->get()->result();
    }
    return $result; 
    }

    function get_all_detail_print($id="",$branch_id="")
  {
    $users_data = $this->session->userdata('auth_users');
    $result_sales=array();
      $this->db->select("hms_canteen_garbage_stock_item.*,hms_users.username");
    $this->db->from('hms_canteen_garbage_stock_item'); 
    $this->db->join('hms_users','hms_users.id = hms_canteen_garbage_stock_item.created_by');
    $this->db->where('hms_canteen_garbage_stock_item.id',$id);
    
    $this->db->where('hms_canteen_garbage_stock_item.is_deleted','0');; 
    $result_sales['garbage_list']= $this->db->get()->result();

    /// hms_stock_issue_allotment_return_to_issue_allotment_return
    $this->db->select('hms_canteen_garbage_stock_item_to_garbage_stock_item.garbage_id,hms_canteen_garbage_stock_item_to_garbage_stock_item.category_id,hms_canteen_garbage_stock_item_to_garbage_stock_item.total_amount as amount,path_item.item as item_name,path_item.item_code,hms_canteen_garbage_stock_item_to_garbage_stock_item.qty as quantity,hms_stock_item_unit.unit,hms_canteen_garbage_stock_item_to_garbage_stock_item.per_pic_price as item_price,path_item.id as item_id,path_stock_category.category,hms_canteen_garbage_stock_item_to_garbage_stock_item.total_amount'); 
    $this->db->from('hms_canteen_garbage_stock_item_to_garbage_stock_item'); 
    $this->db->where('hms_canteen_garbage_stock_item_to_garbage_stock_item.garbage_id',$id);
    $this->db->where('hms_canteen_garbage_stock_item_to_garbage_stock_item.branch_id',$users_data['parent_id']);
    $this->db->join('path_item','path_item.id=hms_canteen_garbage_stock_item_to_garbage_stock_item.item_id','left');
    $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=hms_canteen_garbage_stock_item_to_garbage_stock_item.unit_id','left');
    $this->db->join('path_stock_category`','path_stock_category.id=hms_canteen_garbage_stock_item_to_garbage_stock_item.category_id','left');
    $result_sales['item_list']=$this->db->get()->result();
    //echo $this->db->last_query(); die;
    return $result_sales;
    
    }

} 
?>