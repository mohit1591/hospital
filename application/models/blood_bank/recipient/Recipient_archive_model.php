<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recipient_archive_model extends CI_Model 
{
  	var $table = 'hms_blood_patient_to_recipient';
    var $column = array('hms_blood_patient_to_recipient.id','hms_blood_patient_to_recipient.branch_id','hms_blood_patient_to_recipient.patient_id','hms_blood_patient_to_recipient.referred_by','hms_blood_patient_to_recipient.hospital_id','hms_blood_patient_to_recipient.doctor_id','hms_blood_patient_to_recipient.blood_group_id','hms_blood_patient_to_recipient.clinical_diagnosis','hms_blood_patient_to_recipient.bag_id','hms_blood_patient_to_recipient.volume','hms_blood_patient_to_recipient.specimen_recived_by','hms_blood_patient_to_recipient.requirement_date','hms_patient.patient_name','hms_patient.mobile_no','hms_patient.gender','hms_blood_patient_to_recipient.status','hms_blood_patient_to_recipient.created_date','hms_blood_patient_to_recipient.modified_date'); 
    var $order = array('id' => 'desc');

     private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
       
         $this->db->select("hms_blood_patient_to_recipient.*,hms_patient.id as pat_id,hms_patient.branch_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no, hms_simulation.simulation,  hms_gardian_relation.relation, sim.simulation as relation_simulation,(CASE WHEN hms_patient.gender=1 THEN 'Male' WHEN hms_patient.gender=0 THEN 'Female' ELSE 'Other' END ) as gender, concat_ws(' ',hms_patient.address,hms_patient.address2,hms_patient.address3) as address, hms_cities.city, hms_state.state,hms_blood_bag_type.bag_type, hms_blood_group.blood_group");
     
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','Left');
        $this->db->join('hms_simulation', 'hms_simulation.id=hms_patient.simulation_id', 'Left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_patient_to_recipient.bag_id', 'Left');
        $this->db->join('hms_simulation as sim', 'sim.id=hms_patient.relation_simulation_id', 'Left');
        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'Left');
        $this->db->join('hms_relation', 'hms_relation.id=hms_patient.relation_type', 'Left');
        $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_patient_to_recipient.blood_group_id','Left');
        $this->db->from($this->table);
        //$this->db->where('hms_patient.status','1');
        //$this->db->where('hms_blood_patient_to_recipient.status','1');
        //$this->db->where('hms_patient.is_deleted','1');
        $this->db->where('hms_blood_patient_to_recipient.is_deleted','1');
        $this->db->where('hms_blood_patient_to_recipient.branch_id = "'.$users_data['parent_id'].'"');
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

    public function restore($id="")
    {
        if(!empty($id) && $id>0)
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',0);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('hms_blood_patient_to_recipient');
        } 
    }

    public function restoreall($ids=array())
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
            $emp_ids = implode(',', $id_list);
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',0);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id IN ('.$emp_ids.')');
            $this->db->update('hms_blood_patient_to_recipient');
        } 
    }

    public function trash($id="")
    {
        if(!empty($id) && $id>0)
        {  
            //$this->db->where('id',$id);
            //$this->db->delete('hms_blood_deferral_reason');
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',2);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('hms_blood_patient_to_recipient');
        } 
    }

    public function trashall($ids=array())
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
            //$this->db->where('id IN ('.$branch_ids.')');
            //$this->db->delete('hms_blood_deferral_reason');

            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',2);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id IN ('.$branch_ids.')');
            $this->db->update('hms_blood_patient_to_recipient');
        } 
    }
 

    // Function to get blood data examination fields


// Please write code above    
}