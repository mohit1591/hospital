<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blood_group_model extends CI_Model 
{
  
	var $table = 'hms_blood_group';
    var $column = array('hms_blood_group.id','hms_blood_group.blood_group', 'hms_blood_group.status','hms_blood_group.created_date','hms_blood_group.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_group.*"); 
        $this->db->from($this->table); 
        $this->db->where('hms_blood_group.is_deleted','0');
        $this->db->where('hms_blood_group.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function chief_complaints_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('blood_group','ASC'); 
        $query = $this->db->get('hms_blood_group');
        //echo $this->db->last_query();
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('hms_blood_group.*');
        $this->db->from('hms_blood_group'); 
        $this->db->where('hms_blood_group.id',$id);
        $this->db->where('hms_blood_group.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }
// Please write code above    
}