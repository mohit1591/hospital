<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
    
    public function unit_list()
    {
        $this->db->select('*');
         $this->db->where('hms_canteen_stock_item_unit.is_deleted!=',2);
        $this->db->from('hms_canteen_stock_item_unit');
        $query=$this->db->get();
        return $query->result();
        
        
    }
    
     public function category_list()
    {
        $this->db->select('*');
         $this->db->where('hms_canteen_stock_category.is_deleted!=',2);
        $this->db->from('hms_canteen_stock_category');
        $query=$this->db->get();
        return $query->result();
    }
      public function check_product_code($product_code="")
    {
        $post=$this->input->post();
        $this->db->select('*');  
        if(!empty($product_code))
        {
        $this->db->where('product_code',$product_code);
        } 
        /*if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } */
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_canteen_master_entry');
        if(!empty($post['data_id']))
        {
             if($query->num_rows() > 1)
            {
                return 1;
            }
        }
        else{
             if($query->num_rows() > 0)
            {
                return 1;
            }
        }
       
        
       
    }
    
      public function check_product_name($product_name="")
    {
        $post=$this->input->post();
        $this->db->select('*');  
        if(!empty($product_name))
        {
        $this->db->where('product_name',$product_name);
        } 
       
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_canteen_master_entry');
         if(!empty($post['data_id']))
        {
             if($query->num_rows() > 1)
            {
                return 1;
            }
        }
        else{
             if($query->num_rows() > 0)
            {
                return 1;
            }
        }
       /* $result = $query->result(); 
        return $result; */
    }
    
    public function check_item_category($item_category="",$id="")
    {
        $this->db->select('*');  
        if(!empty($item_category))
        {
        $this->db->where('category',$item_category);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_canteen_stock_category');
        $result = $query->result(); 
        return $result; 
    }
    public function canteen_category_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('category','ASC'); 
        $query = $this->db->get('hms_canteen_stock_category');
        return $query->result();
    }

      public function check_main_product_code($product_code="")
    {
        $post=$this->input->post();
        $this->db->select('*');  
        if(!empty($product_code))
        {
        $this->db->where('product_code',$product_code);
        } 
        /*if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } */
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_canteen_products');
        if(!empty($post['data_id']))
        {
             if($query->num_rows() > 1)
            {
                return 1;
            }
        }
        else{
             if($query->num_rows() > 0)
            {
                return 1;
            }
        }
       
        
       
    }
    
      public function check_main_product_name($product_name="")
    {
        $post=$this->input->post();
        $this->db->select('*');  
        if(!empty($product_name))
        {
        $this->db->where('product_name',$product_name);
        } 
       
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_canteen_products');
         if(!empty($post['data_id']))
        {
             if($query->num_rows() > 1)
            {
                return 1;
            }
        }
        else{
             if($query->num_rows() > 0)
            {
                return 1;
            }
        }
       /* $result = $query->result(); 
        return $result; */
    }
}
?>