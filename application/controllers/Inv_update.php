<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inv_update extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
    }

    public function index()
    { 
     
      $user_data = $this->session->userdata('auth_users');
      $this->db->select("path_item.id,path_item.price,path_item.mrp"); 
	   $this->db->from('path_item');
	   $this->db->where('path_item.branch_id',113);
	   $query = $this->db->get(); 
	   $result =  $query->result_array();
	   
	   foreach($result as $row)
	   {
	       //echo $row['price'];
	       if($row['price']!=0)
	       {
	       $this->db->set('mrp',$row['price']);
			
            $this->db->where('id',$row['id']);
            $this->db->where('branch_id',113);
			$this->db->update('path_item'); 
	       }
	       
	       
	   }
	   
	   //echo "<pre>"; print_r($result);
    }
    
}
?>