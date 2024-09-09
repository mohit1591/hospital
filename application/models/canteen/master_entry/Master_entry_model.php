<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_entry_model extends CI_Model {

  var $table = 'hms_canteen_master_entry';
  var $column = array('hms_canteen_master_entry.id','hms_canteen_master_entry.unit', 'hms_canteen_master_entry.status','hms_canteen_master_entry.created_date');  
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
	    $this->db->select("hms_canteen_master_entry.*"); 
	    $this->db->from($this->table); 
	    $this->db->where('hms_canteen_master_entry.is_deleted','0');
	    $this->db->where('hms_canteen_master_entry.branch_id',$users_data['parent_id']);
	    
	     if(!empty($search) && $search!="")
        {
             
            if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_canteen_master_entry.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_canteen_master_entry.created_date <= "'.$end_date.'"');
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
      $this->db->where('hms_canteen_master_entry.parent_id!=',''); 
      $this->db->order_by('unit','ASC'); 
      $query = $this->db->get('hms_canteen_master_entry');
    return $query->result();
    }

  public function get_by_id($id)
  {
    $this->db->select('hms_canteen_master_entry.*');
    $this->db->from('hms_canteen_master_entry'); 
    $this->db->where('hms_canteen_master_entry.id',$id);
    $this->db->where('hms_canteen_master_entry.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }
  public function check_product($code)
  {
    $this->db->select('hms_canteen_master_entry.*');
    $this->db->from('hms_canteen_master_entry'); 
    $this->db->where('hms_canteen_master_entry.product_code',$code);
    $this->db->where('hms_canteen_master_entry.is_deleted','0');
    $query = $this->db->get(); 
    return $query->row_array();
  }
  public function get_stock_item_list()
  {
    $user_data = $this->session->userdata('auth_users');
    $this->db->select('hms_canteen_master_entry.*');
    $this->db->from('hms_canteen_master_entry'); 
    $this->db->where('hms_canteen_master_entry.branch_id',$user_data['parent_id']);
    $this->db->where('hms_canteen_master_entry.is_deleted','0');
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
          'type'=>$post['type'],
          'unit_id'=>$post['unit1_id'],
          'unit_second_id'=>$post['unit2'],
          'conversion'=>$post['conversion'],
          'min_qty_alert'=>$post['min_qty_alert'],
          'expiry_days'=>$post['expiry_days'],
          'mrp'=>$post['mrp'],
          'purchase_rate'=>$post['purchase_rate'],
          'description'=>$post['description'],
          'sgst'=>$post['sgst'],
          'cgst'=>$post['cgst'],
          'igst'=>$post['igst'],
          'status'=>$post['status'],
          'ip_address'=>$_SERVER['REMOTE_ADDR']
             );
           
    if(!empty($post['data_id']) && $post['data_id']>0)
    {    
            $this->db->set('modified_by',$user_data['id']);
      $this->db->set('modified_date',date('Y-m-d h:i:s'));
            $this->db->where('id',$post['data_id']);
      $this->db->update('hms_canteen_master_entry',$data);  
    }
    else{    
      $this->db->set('created_by',$user_data['id']);
      $this->db->set('created_date',date('Y-m-d h:i:s'));
      $this->db->insert('hms_canteen_master_entry',$data); 
     
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
      $this->db->update('hms_canteen_master_entry');
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
      $this->db->update('hms_canteen_master_entry');
      //echo $this->db->last_query();die;
      } 
    }
    

}
?>