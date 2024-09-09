<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_add_features_model extends CI_Model {

	var $table = 'hms_admin_add_features';
	var $column = array('hms_admin_add_features.features','hms_admin_add_features.start_date', 'hms_admin_add_features.end_date', 'hms_admin_add_features.section','hms_admin_add_features.status','hms_admin_add_features.created_date','hms_admin_add_features.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_admin_add_features.*"); 
        $this->db->from($this->table); 
        $this->db->where('hms_admin_add_features.is_deleted','0');
        $this->db->where('hms_admin_add_features.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function advice_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('medicine_advice','ASC'); 
        $query = $this->db->get('hms_opd_advice');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->select('hms_admin_add_features.*');
        $this->db->from('hms_admin_add_features'); 
        $this->db->where('hms_admin_add_features.id',$id);
        $this->db->where('hms_admin_add_features.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }
    
    public function save()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $end_date='';
        $start_date=''; 
        if(!empty($post['start_date']))
        {
            $start_date=date('Y-m-d',strtotime($post['start_date']));
        }
        else
        {
            $start_date='';
        }
        if(!empty($post['end_date']))
        {
            $end_date=date('Y-m-d',strtotime($post['end_date']));
        }
        else
        {
            $end_date='';
        }
        $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'features'=>$post['features'],
                    'start_date'=>$start_date,
                    'end_date'=>$end_date,
                    'section'=>$post['section'],
                    'status'=>$post['status'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_admin_add_features',$data);  
        }
        else{    
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_admin_add_features',$data);               
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
            $this->db->update('hms_admin_add_features');
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
            $this->db->update('hms_admin_add_features');
            //echo $this->db->last_query();die;
        } 
    }

}
?>