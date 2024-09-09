<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_room_type_model extends CI_Model {

    var $table = 'hms_dialysis_room_category';
    var $column = array('hms_dialysis_room_category.id','hms_dialysis_room_category.room_category', 'hms_dialysis_room_category.status','hms_dialysis_room_category.created_date','hms_dialysis_room_category.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
        $this->db->select("hms_dialysis_room_category.*"); 
        $this->db->from($this->table); 
        $this->db->where('hms_dialysis_room_category.is_deleted','0');
       
            $this->db->where('hms_dialysis_room_category.branch_id',$users_data['parent_id']);
    
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

    function get_datatables()
    {
        $this->_get_datatables_query();
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
    
    public function simulation_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('simulation','ASC'); 
        $query = $this->db->get('hms_dialysis_room_category');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('hms_dialysis_room_category.*');
        $this->db->from('hms_dialysis_room_category'); 
        $this->db->where('hms_dialysis_room_category.id',$id);
        $this->db->where('hms_dialysis_room_category.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }
    
    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'room_category'=>$post['room_type'],
                    'status'=>$post['status'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                    
                 );
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_dialysis_room_category',$data);  
        }
        else{    
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));

            $this->db->insert('hms_dialysis_room_category',$data);               
        }   
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
            $this->db->update('hms_dialysis_room_category');
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
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id IN ('.$branch_ids.')');
            $this->db->update('hms_dialysis_room_category');
            //echo $this->db->last_query();die;
        } 
    }

    public function get_simulation_name($simulation_id)
    {
        $this->db->select('simulation'); 
        $this->db->where('id',$simulation_id);                   
        $query = $this->db->get('hms_dialysis_room_category');
        $test_list = $query->result(); 
            foreach($test_list as $simulations)
            {
               $simulation = $simulations->simulation;
            } 
        
        return $simulation; 
    }
    public function get_room_type_charges()
    {
        $users_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        $result = array();
        if(isset($post['room_category_id']) && !empty($post['room_category_id']))
        {
            $this->db->select('*');
            $this->db->where('room_cat_type',$post['room_category_id']);
            $this->db->where('branch_id',$users_data['parent_id']);
            $query = $this->db->get('hms_rooms');
            $result = $query->result_array();
        }
        return $result;

    }
    

  

}
?>