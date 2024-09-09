<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model {

  var $table = 'hms_canteen_products';
  var $column = array('hms_canteen_products.id','hms_canteen_products.product_name','hms_canteen_products.product_code', 'hms_canteen_products.status','hms_canteen_products.created_date');  
  var $order = array('id' => 'desc');

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  private function _get_datatables_query($branch_id='')
  {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('search_data');
         $parent_branch_details = $this->session->userdata('parent_branches_data');
         $sub_branch_details = $this->session->userdata('sub_branches_data');
	    $this->db->select("hms_canteen_products.*"); 
	    $this->db->from($this->table); 
	    $this->db->where('hms_canteen_products.is_deleted','0');
	    $this->db->where('hms_canteen_products.branch_id',$users_data['parent_id']);
  
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
   // echo $this->db->last_query();die;
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
    
    public function stock_item_unit_list()
    {
      $user_data = $this->session->userdata('auth_users');
      $this->db->select('*');
      $this->db->where('branch_id',$user_data['parent_id']);
      $this->db->where('status',1); 
      $this->db->where('is_deleted',0); 
      $this->db->where('hms_canteen_products.parent_id!=',''); 
      $this->db->order_by('unit','ASC'); 
      $query = $this->db->get('hms_canteen_products');
    return $query->result();
    }

  public function get_by_id($id)
  {
    $this->db->select('hms_canteen_products.*');
    $this->db->from('hms_canteen_products'); 
    $this->db->where('hms_canteen_products.id',$id);
    $this->db->where('hms_canteen_products.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }
  public function check_product($code)
  {
    $this->db->select('hms_canteen_products.*');
    $this->db->from('hms_canteen_products'); 
    $this->db->where('hms_canteen_products.product_code',$code);
    $this->db->where('hms_canteen_products.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }
  public function get_stock_item_list()
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_canteen_products.*');
    $this->db->from('hms_canteen_products'); 
    $this->db->where('hms_canteen_products.branch_id',$user_data['parent_id']);
    $this->db->where('hms_canteen_products.is_deleted','0');
    $query = $this->db->get()->result(); 
    return $query;
  }
  
  public function save()
  {
    $user_data = $this->session->userdata('auth_users');
    $post = $this->input->post();  
  
    $data = array( 
          'branch_id'=>$user_data['parent_id'],
          'product_code'=>$post['product_code'],
          'product_name'=>$post['product_name'],
          'pro_cat_id'=>$post['product_category'],
          'quantity'=>$post['quantity'],
          'product_type'=>$post['product_type'],
          'unit_id'=>$post['unit_id'],
          'min_qty_alert'=>$post['min_qty_alert'],
          'product_cost'=>$post['product_cost'],
          'product_price'=>$post['product_price'],
          'description'=>$post['product_detail'],
          'status'=>1,
          'ip_address'=>$_SERVER['REMOTE_ADDR']
             );
        
    if(!empty($post['data_id']) && $post['data_id']>0)
    {    
            $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d h:i:s'));
            $this->db->where('id',$post['data_id']);
      $this->db->update('hms_canteen_products',$data);  
    }
    else{    
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d h:i:s'));
      $this->db->insert('hms_canteen_products',$data); 
      //echo $this->db->last_query();die;
     $prod_id= $this->db->insert_id();
     $prod_code=$post['comb_prod_code'];
     $i=0;
     foreach($prod_code as $prod_c)
     {
      $data1 = array( 
          'branch_id'=>$user_data['parent_id'],
            'product_id'=>$prod_id,
          'comb_prod_code'=>$prod_c,
          'comb_prod_name'=>$post['comb_prod_name'][$i],
          'comb_prod_qty'=>$post['comb_prod_qty'][$i],
          'comb_prod_price'=>$post['comb_prod_price'][$i],
          'status'=>1,
          'ip_address'=>$_SERVER['REMOTE_ADDR']
             );
             
             $this->db->insert('hms_canteen_products_combo',$data1);
            // echo $this->db->last_query();
    $i++; }
     
    }   
  }

    public function delete($id="")
    {
      if(!empty($id) && $id>0)
      {

      $user_data = $this->session->userdata('auth_users');
      $this->db->set('is_deleted',2);
      $this->db->set('deleted_by',$user_data['id']);
      $this->db->set('deleted_date',date('Y-m-d h:i:s'));
      $this->db->where('id',$id);
      $this->db->update('hms_canteen_products');
      
      	$this->db->set('is_deleted',2);
    	$this->db->set('deleted_by',$user_data['id']);
    	$this->db->set('deleted_date',date('Y-m-d H:i:s'));
    	$this->db->where('product_id',$id);
    	$this->db->update('hms_canteen_products_combo'); 
      //echo $this->db->last_query();die;
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
      $this->db->set('is_deleted',2); 
      $this->db->set('deleted_by',$user_data['id']);
      $this->db->set('deleted_date',date('Y-m-d h:i:s'));
      $this->db->where('id IN ('.$branch_ids.')');
      $this->db->update('hms_canteen_products');
      //echo $this->db->last_query();die;
      } 
    }
    
   public function get_product_values($vals="")
  {
      $response = '';
      if(!empty($vals))
      {
        $users_data = $this->session->userdata('auth_users'); 
      $this->db->select('hms_canteen_master_entry.*,hms_canteen_stock_item_unit.unit,hms_canteen_stock_category.category');  
      $this->db->join('hms_canteen_stock_item_unit','hms_canteen_stock_item_unit.id=hms_canteen_master_entry.unit_id','left');
      $this->db->join('hms_canteen_stock_category`','hms_canteen_stock_category.id=hms_canteen_master_entry.pro_cat_id','left');
      $this->db->where('hms_canteen_master_entry.product_name LIKE "'.$vals.'%"');
      $this->db->where('hms_canteen_master_entry.branch_id',$users_data['parent_id']); 
      $this->db->where('hms_canteen_master_entry.is_deleted',0); 
       $this->db->where('hms_canteen_master_entry.status',1); 
      $this->db->group_by('hms_canteen_master_entry.product_code');
      $this->db->from('hms_canteen_master_entry');  
      $query = $this->db->get(); 

          $result = $query->result(); 
          
          if(!empty($result))
          { 
            $data = array();
            foreach($result as $vals)
            {
              //$response[] = $vals->medicine_name;
         // $name = $vals->item.'-'.$vals->category.'|'.$vals->product_code.'|'.$vals->unit.'|'.$vals->mrp.'|'.$vals->purchase_rate.'|'.$vals->cat_id.'|'.$vals->remainingquantity;
        $name = $vals->product_name.'|'.$vals->product_code.'|'.$vals->mrp.'|'.$vals->purchase_rate.'|'.$vals->id.'|'.$vals->min_qty_alert.'|'.$vals->unit_id.'|'.$vals->pro_cat_id;
          array_push($data, $name);
            }
              //print_r($data);die;
            echo json_encode($data);
          }
          //return $response; 
      } 
    }
    
    
    public function combo_prod_list($id)
  {
    $this->db->select('hms_canteen_products_combo.id as combo_id,hms_canteen_products_combo.comb_prod_code,hms_canteen_products_combo.comb_prod_name,hms_canteen_products_combo.product_id,hms_canteen_products_combo.comb_prod_price,hms_canteen_products_combo.comb_prod_code,hms_canteen_products_combo.comb_prod_qty,hms_canteen_master_entry.product_name');
    $this->db->from('hms_canteen_products_combo'); 
    $this->db->join('hms_canteen_master_entry','hms_canteen_master_entry.product_code=hms_canteen_products_combo.comb_prod_code');
    $this->db->where('hms_canteen_products_combo.product_id',$id);
    $this->db->where('hms_canteen_products_combo.is_deleted','0');
    $query = $this->db->get(); 
    //echo $this->db->last_query();die;
    return $query->result_array();
  }
   

}
?>