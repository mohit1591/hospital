<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_prescription_video_model extends CI_Model {

    var $table = 'hms_dialysis_video_consultation';
    var $column = array('hms_dialysis_video_consultation.id','hms_dialysis_video_consultation.patient_name','hms_dialysis_video_consultation.doctor_name','hms_dialysis_video_consultation.patient_email','hms_dialysis_video_consultation.hospital','hms_dialysis_video_consultation.appointment_utc_time','hms_dialysis_video_consultation.patient_unique_id','hms_dialysis_video_consultation.serialId','hms_dialysis_video_consultation.conversation_id','hms_dialysis_video_consultation.patient_url','hms_dialysis_video_consultation.doctor_url','hms_dialysis_video_consultation.start_valid','hms_dialysis_video_consultation.finish_valid','hms_dialysis_video_consultation.passcode','hms_dialysis_video_consultation.created_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_dialysis_video_consultation.*"); 
        $this->db->from($this->table);  
        $this->db->where('hms_dialysis_video_consultation.branch_id = "'.$users_data['parent_id'].'"');
       
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

    function get_datatables($prescription_id='')
    {
        $this->_get_datatables_query();
        $this->db->where('prescription_id',$prescription_id);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
    }

    function count_filtered($prescription_id='')
    {
        $this->_get_datatables_query();
        $this->db->where('prescription_id',$prescription_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($prescription_id='')
    {
        $this->db->from($this->table);
        $this->db->where('prescription_id',$prescription_id);
        return $this->db->count_all_results();
    }
    
    public function delete($id="")
    {
        if(!empty($id) && $id>0)
        { 
            $this->db->where('id',$id);
            $this->db->delete('hms_dialysis_video_consultation'); 
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
            $banch_ids = implode(',', $id_list); 
            $this->db->where('id IN ('.$banch_ids.')');
            $this->db->delete('hms_dialysis_video_consultation'); 
        } 
    }

    public function doctors_list($id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $id_set = "";
        if(!empty($id) && $id>0)
        {
          $id_set = ' WHERE id != "'.$id.'"';
        }
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('doctor_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('id NOT IN (select id from hms_signature '.$id_set.')');
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query();
        return $result; 
    }

}
?>