<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model 
{
  	var $table = 'hms_blood_stock';
    var $column = array('hms_blood_donor.donor_name','hms_patient.patient_name','hms_blood_donor.donor_code','hms_blood_donor.blood_group_id','hms_blood_bag_type.bag_type','hms_blood_stock.component_name','hms_blood_stock.bar_code');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    

    private function _get_datatables_query()
    {
        $search = $this->session->userdata('stock_search');
        //echo "<pre>"; print_r($search); exit;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_stock.*,hms_blood_donor.id as donors_id,hms_blood_donor.donor_name,hms_blood_donor.donor_code,hms_blood_donor.blood_group_id ,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood,hms_blood_group.id as blood_grp_id,hms_blood_donor.donor_status"); 
        //$this->db->from($this->table); 
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id AND hms_blood_donor.is_deleted=0','left');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        
        
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        
        $this->db->from($this->table); 
        //$this->db->group_by('hms_blood_stock.component_id');
        $this->db->group_by('hms_blood_donor.id');
        $this->db->group_by('hms_blood_stock.component_id');
        
        
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"');
        $i = 0;
         if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
            }

            if(isset($search['expiry_from_date']) && !empty($search['expiry_from_date']))
            {
                $expiry_from_date = date('Y-m-d',strtotime($search['expiry_from_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.expiry_date >= "'.$expiry_from_date.'"');
            }

            if(isset($search['expiry_end_date']) && !empty($search['expiry_end_date']))
            {
                $expiry_end_date = date('Y-m-d',strtotime($search['expiry_end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.expiry_date <= "'.$expiry_end_date.'"');
            }
            
            if(isset($search['bar_code']) && !empty($search['bar_code']))
            {
                $this->db->where('hms_blood_stock.bar_code',$search['bar_code']);
            }
            if(isset($search['status']) && !empty($search['status']))
            {
                $this->db->where('hms_blood_donor.donor_status',$search['status']);
            }

            if(isset($search['donor_id']) && !empty($search['donor_id']))
            {
                $this->db->where('hms_blood_donor.donor_code',$search['donor_id']);
            }
            if(isset($search['component_id']) && !empty($search['component_id']))
            {
                $this->db->where('hms_blood_stock.component_id',$search['component_id']);
            }

             if(isset($search['blood_group']) && !empty($search['blood_group']))
            {
                $this->db->where('hms_blood_donor.blood_group_id',$search['blood_group']);
            }

             


        }
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

    private function _get_datatables_query_old()
    {
         $search = $this->session->userdata('stock_search');
        $users_data = $this->session->userdata('auth_users');
         $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.donor_code,hms_blood_donor.blood_group_id ,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood,hms_blood_group.id as blood_grp_id"); 
        //$this->db->from($this->table); 
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ','left');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        
        $this->db->from($this->table); 
        $this->db->group_by('hms_blood_stock.component_id');
        $this->db->group_by('hms_blood_stock.blood_group_id');
          //$this->db->where('hms_blood_stock.flag = 1');
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"');
        $i = 0;
         if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
            }
            if(isset($search['bar_code']) && !empty($search['bar_code']))
            {
                $this->db->where('hms_blood_stock.bar_code',$search['bar_code']);
            }
            if(isset($search['status']) && !empty($search['status']))
            {
                $this->db->where('hms_blood_stock.status',$search['status']);
            }

            if(isset($search['donor_id']) && !empty($search['donor_id']))
            {
                $this->db->where('hms_blood_donor.donor_code',$search['donor_id']);
            }
            if(isset($search['component_id']) && !empty($search['component_id']))
            {
                $this->db->where('hms_blood_stock.component_id',$search['component_id']);
            }

             if(isset($search['blood_group']) && !empty($search['blood_group']))
            {
                $this->db->where('hms_blood_donor.blood_group_id',$search['blood_group']);
            }

             


        }
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

    

 // public function stock_view_list($id='')
 //    {
 //        $users_data = $this->session->userdata('auth_users');
 //        $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.blood_group_id ,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood"); 
 //        //$this->db->from($this->table); 
 //        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ','left');
 //        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
 //        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
 //        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
 //        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
 //        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
 //        $this->db->from($this->table);
 //        //$this->db->where('hms_blood_stock.id',$id);
 //        //$this->db->where('CASE WHEN hms_blood_stock.falg = 1');
 //        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"'); 
 //        $query = $this->db->get();
 //        //echo $this->db->last_query();die;
 //        return $query->row_array();
        
 //    }

    public function stock_view_list($id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.blood_group_id ,hms_blood_donor.donor_code,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood"); 
        //$this->db->from($this->table); 
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ','left');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        $this->db->from($this->table);
        $this->db->where('hms_blood_stock.id',$id);
        //$this->db->where('CASE WHEN hms_blood_stock.falg = 1');
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"'); 
        $query = $this->db->get();
        return $query->row_array();  
    }
    public function _stock_view_detail_list($id='',$blood_grp_id='',$component_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.blood_group_id ,hms_blood_donor.donor_code,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_code,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood"); 
        //$this->db->from($this->table); 
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ','left');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        $this->db->from($this->table);
        $this->db->where('hms_blood_stock.component_id',$component_id);
        $this->db->where('hms_blood_donor.blood_group_id',$blood_grp_id);
        //$this->db->where('hms_blood_stock.id',$id);
        //$this->db->where('CASE WHEN hms_blood_stock.falg = 1');
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"'); 
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

    function get_datatables_stock_history($id="",$blood_grp_id="",$component_id="")
    {
        $this->_stock_view_detail_list($id,$blood_grp_id,$component_id);
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

     function count_filtered_stock_history($id="",$blood_grp_id="",$component_id="")
    {
        $this->_stock_view_detail_list($id,$blood_grp_id,$component_id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all_stock_history($id="",$blood_grp_id="",$component_id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_stock.*,hms_blood_donor.donor_name,hms_blood_donor.blood_group_id ,hms_blood_donor.donor_code,hms_blood_patient_to_recipient.blood_group_id,hms_patient.patient_code,hms_patient.patient_name,hms_blood_patient_to_recipient.patient_id,hms_blood_bag_type.bag_type ,hms_blood_group.blood_group, ( CASE when hms_blood_stock.flag=1 then hms_blood_donor.donor_name else hms_patient.patient_name END) as name,(CASE when hms_blood_stock.flag=1 then hms_blood_group.blood_group else bg.blood_group END) as blood"); 
        //$this->db->from($this->table); 
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id ','left');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.id=hms_blood_stock.recipient_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_stock.bag_type_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_blood_group as bg','bg.id=hms_blood_patient_to_recipient.blood_group_id','left');
        $this->db->from($this->table);
        $this->db->where('hms_blood_stock.component_id',$component_id);
        $this->db->where('hms_blood_donor.blood_group_id',$blood_grp_id);
        //$this->db->where('hms_blood_stock.id',$id);
        //$this->db->where('CASE WHEN hms_blood_stock.falg = 1');
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"'); 
        
        
        $result = $this->db->count_all_results();
        //echo $this->db->last_query(); exit;
        
        return $result;
        
         
         /*$users_data = $this->session->userdata('auth_users');
        $this->db->from($this->table);
        $this->db->where('hms_blood_stock.branch_id = "'.$users_data['parent_id'].'"'); 
        return $this->db->count_all_results();*/
    }
    
    public function get_by_id($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        //$this->db->select('dnr.* ,hms_blood_patient_to_recipient.*, sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender , bg.blood_group , rs.preferred_reminder_service');
        $this->db->select('dnr.*,hms_blood_patient_to_recipient.id as recipient_id,hms_blood_patient_to_recipient.branch_id,hms_blood_patient_to_recipient.patient_id,hms_blood_patient_to_recipient.referred_by,hms_blood_patient_to_recipient.hospital_id,hms_blood_patient_to_recipient.doctor_id,hms_blood_patient_to_recipient.blood_group_id,hms_blood_patient_to_recipient.clinical_diagnosis,hms_blood_patient_to_recipient.bag_id,hms_blood_patient_to_recipient.volume,hms_blood_patient_to_recipient.specimen_recived_by,hms_blood_patient_to_recipient.requirement_date,hms_blood_patient_to_recipient.recipient_source,sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender');
        $this->db->from('hms_patient dnr');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.patient_id=dnr.id','Left'); 
        $this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        $this->db->where('dnr.branch_id',$branch_id);
        $this->db->where('dnr.id',$id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
    }

     public function get_by_id_ipd($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('*');
        $this->db->from('hms_ipd_booking');
        $this->db->where('hms_ipd_booking.branch_id',$branch_id);
        $this->db->where('hms_ipd_booking.id',$id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
    }

     public function get_by_id_ot($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('*');
        $this->db->from('hms_operation_booking');
        $this->db->where('hms_operation_booking.branch_id',$branch_id);
        $this->db->where('hms_operation_booking.id',$id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
    }


     public function get_by_id_edit($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
      
        $this->db->select('dnr.*,hms_blood_patient_to_recipient.id as recipient_id,hms_blood_patient_to_recipient.branch_id,hms_blood_patient_to_recipient.patient_id,hms_blood_patient_to_recipient.reference_id,hms_blood_patient_to_recipient.referred_by,hms_blood_patient_to_recipient.hospital_id,hms_blood_patient_to_recipient.doctor_id,hms_blood_patient_to_recipient.blood_group_id,hms_blood_patient_to_recipient.clinical_diagnosis,hms_blood_patient_to_recipient.bag_id,hms_blood_patient_to_recipient.volume,hms_blood_patient_to_recipient.specimen_recived_by,hms_blood_patient_to_recipient.requirement_date,hms_blood_patient_to_recipient.recipient_source,sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender');
        $this->db->from('hms_patient dnr');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.patient_id=dnr.id','Left'); 
        $this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        //$this->db->join('hms_blood_group bg', 'bg.id=dnr.blood_group_id','left');
        //$this->db->join('hms_blood_preferred_reminder_service rs', 'rs.id=reminder_service_id','left');
        $this->db->where('dnr.branch_id',$branch_id);
        $this->db->where('hms_blood_patient_to_recipient.id',$id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
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
            $this->db->update('hms_blood_patient_to_recipient');
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
            $this->db->update('hms_blood_patient_to_recipient');
            //echo $this->db->last_query();die;
        } 
    }


    public function all_data_list()
    {
            $search= $this->session->userdata('stock_dashboard_search');
            $users_data = $this->session->userdata('auth_users');
            $branch_id=$users_data['parent_id'];
            if($search['blood_group']=='all')
            {
            $blood_group_list=$this->get_blood_group_list();
           
            $i=0;
            $data=array();
            $rk=0;
            foreach($blood_group_list as $blood_li)
            {
            $data['all_blood_group'][$rk]['blood_group']= $blood_li->blood_group;
            $data['all_blood_group'][$rk]['blood_group_id']= $blood_li->id;
            
            $user_data = $this->session->userdata('auth_users');
            $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
            $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id AND hms_blood_qc_examination.blood_condition!=2');
                
           //$this->db->join('hms_blood_qc_examination_to_fields','hms_blood_qc_examination_to_fields.donor_id=hms_blood_stock.donor_id AND hms_blood_qc_examination_to_fields.result!=1');
            $this->db->where('hms_blood_stock.branch_id',$branch_id);
           if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                 $this->db->where('hms_blood_donor.blood_group_id IN('.$blood_li->id.')');
                }
                $this->db->where('hms_blood_stock.expiry_date > "'.date('Y-m-d H:i:s').'"');
                $this->db->group_by('hms_blood_stock.component_id');
            
            //$data['tested_data']

          

           }
            $tested= $this->db->get()->result();
            $a=0;
             foreach($tested as $tested_data)
              {

                    $data['all_blood_group'][$rk]['tested_data'][$a]['component_name'][]=$tested_data->component;
                    $data['all_blood_group'][$rk]['tested_data'][$a]['donor_id'][]=$tested_data->d_id;
                    $data['all_blood_group'][$rk]['tested_data'][$a]['component_id'][]=$tested_data->id;
                $a++;
              }
            
                
            /* tested data when data go in bagqc*/


            /* untested data */

            $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
           
           if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                  $this->db->where('hms_blood_donor.blood_group_id IN('.$blood_li->id.')');
                }
                //$this->db->group_by('hms_blood_stock.component_id');
                $this->db->where('hms_blood_stock.expiry_date > "'.date('Y-m-d H:i:s').'"');
            
            //$data['tested_data']
               $result =$this->db->get()->result();
               $r=0;
              foreach($result as $untested_data)
              {
               $this->db->select('hms_blood_qc_examination.*');
                $this->db->where('hms_blood_qc_examination.donor_id',$untested_data->d_id);
                $res=$this->db->get('hms_blood_qc_examination')->result();
                if(count($res)>0)
                {
                    
                    
                }
                else
                {
                    $data['all_blood_group'][$rk]['untested_data'][$r]['component_name'][]=$untested_data->component;
                    $data['all_blood_group'][$rk]['untested_data'][$r]['donor_id'][]=$untested_data->d_id;
                    $data['all_blood_group'][$rk]['untested_data'][$r]['component_id'][]=$untested_data->id;
                }
                $r++;
              }
          

           }
        

            /* untested data */


            /* donation */
             $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
            if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                 $this->db->where('hms_blood_donor.blood_group_id IN('.$blood_li->id.')');
                }
                $this->db->group_by('hms_blood_stock.component_id');
           }
             $donation= $this->db->get()->result();
             $k=0;
             foreach($donation as $donation_data)
              {
                    $data['all_blood_group'][$rk]['donated_data'][$k]['component_name'][]=$donation_data->component;
                    $data['all_blood_group'][$rk]['donated_data'][$k]['donor_id'][]=$donation_data->d_id;
                    $data['all_blood_group'][$rk]['donated_data'][$k]['component_id'][]=$donation_data->id;
                    $k++;
                
              }
            /* donation */

            /* issued */
            $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
            if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                  $this->db->where('hms_blood_donor.blood_group_id IN('.$blood_li->id.')');
                }
                $this->db->group_by('hms_blood_stock.component_id');
                $this->db->where('hms_blood_stock.is_issued',2);
           }
            $issued= $this->db->get()->result();
            $j=0;
            foreach($issued as $issued_data)
              {
                    $data['all_blood_group'][$rk]['issued_data'][$j]['component_name'][]=$issued_data->component;
                    $data['all_blood_group'][$rk]['issued_data'][$j]['donor_id'][]=$issued_data->d_id;
                    $data['all_blood_group'][$rk]['issued_data'][$j]['component_id'][]=$issued_data->id;
                   // print_r($data);
                    $j++;
                
              }

            /* issued */


            /* expired data */

              $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_stock.expiry_date as expiry,hms_blood_donor.id as d_id,hms_blood_stock.is_issued');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
            //$this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id AND hms_blood_qc_examination.blood_condition!=2');
                
            $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
                if(isset($search) && !empty($search))
                {
                    if(!empty($search['start_date']))
                    {
                    $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                    $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                    }

                    if(!empty($search['end_date']))
                    {
                    $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                    $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                    }

                    if(!empty($search['blood_group']))
                    {
                      $this->db->where('hms_blood_donor.blood_group_id IN('.$blood_li->id.') AND (hms_blood_stock.expiry_date < "'.date('Y-m-d H:i:s').'" OR(hms_blood_qc_examination.blood_condition=2))');
                    }
                    //$this->db->group_by('hms_blood_stock.component_id');
                    //$this->db->where('hms_blood_donor.blood_group_id IN('.$search['blood_group'].') AND (hms_blood_stock.expiry_date < "'.date('Y-m-d H:i:s').'" OR (hms_blood_qc_examination_to_fields.result=1))');
                   
                    //$this->db->where('hms_blood_stock.expiry_date < "'.date('Y-m-d H:i:s').'"');
               }
                $expiry= $this->db->get()->result();
                $i=0;
                foreach($expiry as $expired_data)
                {
                $res=0;
                if($expired_data->is_issued==2)
                {
                // $this->db->select('hms_blood_stock.*');
                // $this->db->where('hms_blood_stock.is_issued',2);
                // $this->db->where('hms_blood_stock.expiry_date',date('Y-m-d H:i:s',strtotime($expired_data->expiry)));
                // $res=$this->db->get('hms_blood_stock')->result();
               
                
                }
                else
                {
                $data['all_blood_group'][$rk]['expired'][$i]['component_name'][]=$expired_data->component;
                $data['all_blood_group'][$rk]['expired'][$i]['donor_id'][]=$expired_data->d_id;
                $data['all_blood_group'][$rk]['expired'][$i]['component_id'][]=$expired_data->id;
                $data['all_blood_group'][$rk]['expired'][$i]['expiry_time'][]=$expired_data->expiry;
                $i++; 
                }
               

                }
                $rk++;
                //print '<pre>'; print_r($data);
               
                } //die; 
               // print '<pre>'; print_r($data);  
               return $data;    
            }
            else if(!empty($search['blood_group']) && isset($search['blood_group']))
            {
            $data=array();
            $user_data = $this->session->userdata('auth_users');
            $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           //$this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id AND hms_blood_qc_examination.blood_condition!=2');
           
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
           if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                 $this->db->where('hms_blood_donor.blood_group_id IN('.$search['blood_group'].')');
                }
                $this->db->where('flag',1);
                $this->db->where('hms_blood_stock.expiry_date > "'.date('Y-m-d H:i:s').'"');
                $this->db->group_by('hms_blood_stock.component_id');
               
            
            //$data['tested_data']

          

           }
            $tested= $this->db->get()->result();
            $a=0;
             foreach($tested as $tested_data)
              {

                    $data['tested_data'][$a]['component_name'][]=$tested_data->component;
                    $data['tested_data'][$a]['donor_id'][]=$tested_data->d_id;
                    $data['tested_data'][$a]['component_id'][]=$tested_data->id;
                $a++;
              }
            
                
            /* tested data when data go in bagqc*/


            /* untested data */

            $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id,hms_blood_stock.expiry_date');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
           
           if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                 $this->db->where('hms_blood_donor.blood_group_id IN('.$search['blood_group'].')');
                }
               // $this->db->group_by('hms_blood_stock.component_id');
                $this->db->where('hms_blood_stock.expiry_date > "'.date('Y-m-d H:i:s').'"');
            
            //$data['tested_data']
               $result =$this->db->get()->result();
              //print '<pre>'; print_r( $result);
             
               $r=0;
              foreach($result as $untested_data)
              {
                 // $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id','left');
                $this->db->select('hms_blood_qc_examination.*');
                $this->db->where('hms_blood_qc_examination.donor_id',$untested_data->d_id);
               
                $res=$this->db->get('hms_blood_qc_examination')->result();
                if(count($res)>0)
                {
                    
                    
                }
                else
                {
                   
                    $data['untested_data'][$r]['component_name'][]=$untested_data->component;
                    $data['untested_data'][$r]['donor_id'][]=$untested_data->d_id;
                    $data['untested_data'][$r]['component_id'][]=$untested_data->id;
                  
                }
                $r++;
              }
          

           }
          
               
            /* untested data */


            /* donation */
             $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
            if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                $this->db->where('hms_blood_donor.blood_group_id IN('.$search['blood_group'].')');
                }
                $this->db->group_by('hms_blood_stock.component_id');
           }
             $donation= $this->db->get()->result();
             $k=0;
             foreach($donation as $donation_data)
              {
                    $data['donated_data'][$k]['component_name'][]=$donation_data->component;
                    $data['donated_data'][$k]['donor_id'][]=$donation_data->d_id;
                    $data['donated_data'][$k]['component_id'][]=$donation_data->id;
                    $k++;
                
              }
            /* donation */

            /* issued */
            $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_donor.id as d_id');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
            if(isset($search) && !empty($search))
            {
                if(!empty($search['start_date']))
                {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                }

                if(!empty($search['end_date']))
                {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                }

                if(!empty($search['blood_group']))
                {
                 $this->db->where('hms_blood_donor.blood_group_id IN('.$search['blood_group'].')');
                }
                $this->db->group_by('hms_blood_stock.component_id');
                $this->db->where('hms_blood_stock.is_issued',2);
           }
            $issued= $this->db->get()->result();
           //echo $this->db->last_query();die;
            $rt=0;
            foreach($issued as $issued_data)
              {
                    $data['issued_data'][$rt]['component_name'][]=$issued_data->component;
                    $data['issued_data'][$rt]['donor_id'][]=$issued_data->d_id;
                    $data['issued_data'][$rt]['component_id'][]=$issued_data->id;
                    //print_r($data);die;
                    $rt++;
                
              }
                //print_r($data);die;
            /* issued */


            /* expired data */

              $this->db->select('hms_blood_component_master.*,hms_blood_donor.blood_group_id,hms_blood_stock.expiry_date as expiry,hms_blood_donor.id as d_id,hms_blood_stock.is_issued');
            $this->db->from('hms_blood_component_master');
            $this->db->where('hms_blood_component_master.branch_id',$user_data['parent_id']);
            $this->db->join('hms_blood_stock','hms_blood_stock.component_id=hms_blood_component_master.id','left');

             $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id','left');
           $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
           $this->db->where('hms_blood_stock.branch_id',$branch_id);
           $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id','left');
           
                if(isset($search) && !empty($search))
                {
                    if(!empty($search['start_date']))
                    {
                    $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                    $this->db->where('hms_blood_stock.created_date >= "'.$start_date.'"');
                    }

                    if(!empty($search['end_date']))
                    {
                    $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                    $this->db->where('hms_blood_stock.created_date <= "'.$end_date.'"');
                    }

                    if(!empty($search['blood_group']))
                    {
                     $this->db->where('hms_blood_donor.blood_group_id IN('.$search['blood_group'].') AND (hms_blood_stock.expiry_date < "'.date('Y-m-d H:i:s').'" OR hms_blood_qc_examination.blood_condition=2)');
                    }
                    //$this->db->group_by('hms_blood_stock.component_id');
                    //$this->db->group_by('hms_blood_stock.bar_code');
                    //$this->db->where('hms_blood_stock.expiry_date < "'.date('Y-m-d H:i:s').'" OR (hms_blood_qc_examination_to_fields.result=1)');
                   // $this->db->where('hms_blood_qc_examination_to_fields.result=1');
               }
                $expiry= $this->db->get()->result();
               // echo $this->db->last_query();
                $i=0;
                foreach($expiry as $expired_data)
                {
                    $res=0;
                    if($expired_data->is_issued==2)
                    {
                       /* $this->db->select('hms_blood_stock.*');
                        $this->db->where('hms_blood_stock.is_issued',2);
                        $this->db->where('hms_blood_stock.expiry_date',date('Y-m-d H:i:s',strtotime($expired_data->expiry)));
                        $res=$this->db->get('hms_blood_stock')->result();*/
                 
                    }
                 else
                {
                   
                    $data['expired'][$i]['component_name'][]=$expired_data->component;
                    $data['expired'][$i]['donor_id'][]=$expired_data->d_id;
                    $data['expired'][$i]['component_id'][]=$expired_data->id;
                    $data['expired'][$i]['expiry_time'][]=$expired_data->expiry;
                    $i++;   
                }
              

                }
                //print '<pre>'; print_r($data);die;
               return $data; 
            }
            else
            {
               return '';  
            }
            /* tested data when data go in bagqc*/
            
            /* expired data */

           
           
    }
    public function get_blood_group_list()
    {
        $this->db->select('*');
        $this->db->from('hms_blood_group');
        $this->db->where('status','1');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }


    public function get_stock_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(hms_blood_stock.debit)-sum(hms_blood_stock.credit)) as total_qty");
        
        $this->db->where('hms_blood_stock.branch_id',$users_data['parent_id']);
        
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->where('hms_blood_stock.component_id',$component_id);

        }
         if((!empty($donor_id)) && ($donor_id!=""))           
         {
             $this->db->where('hms_blood_stock.donor_id',$donor_id);

         }
        // if((!empty($bar_code)) && ($bar_code!=""))           
        // {
        //     $this->db->like('bar_code',$bar_code);

        // }
        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('hms_blood_stock.blood_group_id',$blood_group_id);

        }
        $stock_search = $this->session->userdata('stock_search');
        if($stock_search['stock_value']=='3' && !empty($stock_search))
        {
            
        }
        /*else
        {
          $this->db->where('hms_blood_stock.expiry_date >=',date('Y-m-d'));   
        }*/
        
        
       
        $this->db->group_by('hms_blood_stock.component_id');
         $this->db->group_by('hms_blood_stock.blood_group_id');
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query(); die;
        return $query->row_array();
       
    }

     public function get_stock_quantity_new202005($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(debit)-sum(credit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->where('component_id',$component_id);

        }
        // if((!empty($donor_id)) && ($donor_id!=""))           
        // {
        //     $this->db->like('donor_id',$donor_id);

        // }
        if((!empty($bar_code)) && ($bar_code!=""))           
        {
            $this->db->where('bar_code',$bar_code);

        }
        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('blood_group_id',$blood_group_id);

        }
        //$this->db->where('expiry_date>=',date('Y-m-d H:i:s'));
        $this->db->group_by('component_id');
         $this->db->group_by('blood_group_id');
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();
        return $query->row_array();
       
    }
     public function get_stock_quantity20200504($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(hms_blood_stock.debit)-sum(hms_blood_stock.credit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('hms_blood_stock.branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('hms_blood_stock.branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->where('hms_blood_stock.component_id',$component_id);

        }
        // if((!empty($donor_id)) && ($donor_id!=""))           
        // {
        //     $this->db->like('donor_id',$donor_id);

        // }
        // if((!empty($bar_code)) && ($bar_code!=""))           
        // {
        //     $this->db->like('bar_code',$bar_code);

        // }
        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('hms_blood_stock.blood_group_id',$blood_group_id);

        }
        
        $this->db->where('hms_blood_stock.expiry_date>=',date('Y-m-d H:i:s'));
       // $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_stock.donor_id','left');
       // $this->db->where('hms_blood_qc_examination.blood_condition!=',2);
        $this->db->group_by('hms_blood_stock.component_id');
         $this->db->group_by('hms_blood_stock.blood_group_id');
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();
        return $query->row_array();
       
    }

     public function get_stock_quantity_new($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(debit)-sum(credit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->where('component_id',$component_id);

        }
        // if((!empty($donor_id)) && ($donor_id!=""))           
        // {
        //     $this->db->like('donor_id',$donor_id);

        // }
        if((!empty($bar_code)) && ($bar_code!=""))           
        {
            $this->db->where('bar_code',$bar_code);

        }
        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('blood_group_id',$blood_group_id);

        }
        //$this->db->where('expiry_date>=',date('Y-m-d H:i:s'));
        $this->db->group_by('component_id');
         $this->db->group_by('blood_group_id');
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();
        return $query->row_array();
       
    }
    public function get_stock_quantity_new_with_expiry_check($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(debit)-sum(credit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->where('component_id',$component_id);

        }
        // if((!empty($donor_id)) && ($donor_id!=""))           
        // {
        //     $this->db->like('donor_id',$donor_id);

        // }
        if((!empty($bar_code)) && ($bar_code!=""))           
        {
            $this->db->where('bar_code',$bar_code);

        }
        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('blood_group_id',$blood_group_id);

        }
        //$this->db->where('expiry_date>=',date('Y-m-d H:i:s'));
        $this->db->group_by('component_id');
         $this->db->group_by('blood_group_id');
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();
        return $query->row_array();
    }

    public function get_component_list()
    {
        $search= $this->session->userdata('stock_dashboard_search');
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        if(!empty($search['component']))
        {
            $this->db->where('id',$search['component']);    
        }
        $query = $this->db->get('hms_blood_component_master');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function get_components_name($blood_group_id='')
    {
        $this->db->select('hms_blood_component_master.component');
        //$this->db->from('hms_blood_group');
        $this->db->where('hms_blood_component_master.id',$blood_group_id);
        $query = $this->db->get('hms_blood_component_master');
        $result = $query->result(); 
        if(!empty($result))
        {
           $component =  $result[0]->component;
        }
        else
        {
            $component='';
        }
        
        return $component;
        
    }

    public function get_blood_group_name($blood_group_id='')
    {
        $this->db->select('hms_blood_group.blood_group');
        //$this->db->from('hms_blood_group');
        $this->db->where('hms_blood_group.id',$blood_group_id);
        $query = $this->db->get('hms_blood_group');
        $result = $query->result(); 
        if(!empty($result))
        {
           $blood_group =  $result[0]->blood_group;
        }
        else
        {
            $blood_group='';
        }
        
        return $blood_group;
        
    }
    
    public function save_all_blood_stock($patient_all_data = array())
	{
		

		//echo "<pre>";print_r($patient_all_data); exit;//$patient_data['relation_type']
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($patient_all_data))
        {
            foreach($patient_all_data as $patient_data)
            {
                
                    if($patient_data['blood_group']=='B+Ve')
			        {
			          $blood_group_id=4;  
			        }
			        else if($patient_data['blood_group']=='B+VE')
			        {
			             $blood_group_id=4;   
			        }
			        else if($patient_data['blood_group']=='B+ve')
			        {
			             $blood_group_id=4;   
			        }
			        else if($patient_data['blood_group']=='O+ve')
			        {
			             $blood_group_id=8;   
			        }
			        else if($patient_data['blood_group']=='O+VE')
			        {
			            $blood_group_id=8;  
			        }
			        else if($patient_data['blood_group']=='A+ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['blood_group']=='A+VE')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['blood_group']=='AB+ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['blood_group']=='AB+VE')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['blood_group']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group']=='O-VE')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group']=='O-VE')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group']=='B-VE')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['blood_group']=='B-ve')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['blood_group']=='NT')
			        {
			            $blood_group_id=4;  
			        }
			        else if($patient_data['blood_group']=='')
			        {
			            $blood_group_id=4;  
			        }
			        
			        	if(is_numeric($patient_data['date_of_collection']))
					{
					    $collDates = $patient_data['date_of_collection'];
                        $timestampd = $collDates * 60 * 60 * 24;
                        $date_of_collections = date('Y-m-d', $timestampd);
                        $time = strtotime($date_of_collections.' -70 years');
                        $date_of_collection = date("Y-m-d", $time);
					    
					}
					else
					{
					    $date_of_collection = date('Y-m-d',strtotime($patient_data['date_of_collection']));
					}
					
					
					if(is_numeric($patient_data['date_of_expiry']))
					{
					    $excelDates = $patient_data['date_of_expiry'];
                        $timestampd = $excelDates * 60 * 60 * 24;
                        $date_of_expirys = date('Y-m-d', $timestampd);
                        $time = strtotime($date_of_expirys.' -70 years');
                        $date_of_expiry = date("Y-m-d", $time);
					    
					}
					else
					{
					    	$date_of_expiry = date('Y-m-d',strtotime($patient_data['date_of_expiry']));
					}
			        
					if(!empty($patient_data['donor_code']))
					{
					    $branch_id=$users_data['parent_id'];
                        $this->db->select('hms_blood_donor.id');
                        $this->db->from('hms_blood_donor');
                        $this->db->where('hms_blood_donor.branch_id',$branch_id);
                        $this->db->where('hms_blood_donor.donor_code',$patient_data['donor_code']);
                        $query = $this->db->get(); 
                       //echo $this->db->last_query();die;
                        $result =  $query->row_array();   
					}
					else if($patient_data['unit_no'])
					{
					    $branch_id=$users_data['parent_id'];
                        $this->db->select('hms_blood_donor.id');
                        $this->db->from('hms_blood_donor');
                        $this->db->where('hms_blood_donor.branch_id',$branch_id);
                        $this->db->where('hms_blood_donor.unit_no',$patient_data['unit_no']);
                        $query = $this->db->get(); 
                       //echo $this->db->last_query();die;
                        $result =  $query->row_array();  
                        if(!empty($result))
    					{
    					    $donor_id = $result['id'];    
    					}
					}
					else
					{
					     $donor_data_array=array(
                           'donor_name'=>$patient_data['donor_name'],
                           'blood_group_id'=>$blood_group_id,
                           'registration_date'=>$date_of_collection,
                           'status'=>'1',
                           'donor_status'=>1,
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );

                        $donor_code = generate_unique_id(41);
                        $donor_data_array['donor_code']=$donor_code;
                        $donor_data_array["branch_id"]=$users_data['parent_id'];
                        $donor_data_array["created_by"]=$users_data['id'];
                        $donor_data_array["created_date"]=$date_of_collection;
            
					    $this->db->insert('hms_blood_donor',$donor_data_array);
					    //echo $this->db->last_query(); 
					    $donor_id = $this->db->insert_id(); 
					    
					}
					 //echo $donor_id; die;
					//echo "<pre>"; print_r($result); exit;
				
			       
                   if(!empty($donor_id))
                   {
                       
					if($patient_data['blood_qc']=='PASSED')
					{
					   $blood_qc = 1; 
					}
					else if($patient_data['blood_qc']=='FAILED')
					{
					    $blood_qc = 0;
					}
					
				  
                   if(!empty($patient_data['blood_bag_type']))
                   {
                        $this->db->select('hms_blood_bag_type.id');
                        $this->db->from('hms_blood_bag_type');
                        $this->db->where('hms_blood_bag_type.branch_id',$users_data['parent_id']);
                       $this->db->where('LOWER(hms_blood_bag_type.bag_type)',strtolower($patient_data['blood_bag_type'])); 
                        $query = $this->db->get(); 
                       //echo $this->db->last_query();die;
                        $result =  $query->row_array();    
    					//echo "<pre>"; print_r($result); exit;
    					if(!empty($result))
    					{
    					    $bag_type = $result['id'];    
    					}
                   }
                   else if($patient_data['blood_bag_type']=='TRIPLE BAG')
					{
					    $bag_type= '19'; //bag type
					}
					
					
				
					
				
					
				
					if(!empty($patient_data['platelet']))
					{
					    
					    if(!empty($patient_data['platelet']))
                        {
                            $this->db->select('hms_blood_component_master.id');
                            $this->db->from('hms_blood_component_master');
                            $this->db->where('hms_blood_component_master.branch_id',$users_data['parent_id']);
                           $this->db->where('LOWER(hms_blood_component_master.component)',strtolower('Platelets')); 
                            $query = $this->db->get(); 
                           //echo $this->db->last_query();die;
                            $result =  $query->row_array();    
        					//echo "<pre>"; print_r($result); exit;
        					if(!empty($result))
        					{
        					    $component_id = $result['id'];    
        					}
                        }
                        else
    					{
    					    $component_id= '28'; //bag type
    					}
					    
					    
                        $stock_array=array(
                                  'donor_id'=>$donor_id,
                                  'branch_id'=>$users_data['parent_id'],
                                  'bag_type_id' =>$bag_type,
                                  'component_id' => $component_id,
                                  'component_name'=>'Platelets',
                                  'component_price'=>$patient_data['component_price'],
                                  'qty'=>$patient_data['platelet'],
                                  'volumn'=>$patient_data['platelet'],
                                   'expiry_date' =>$date_of_expiry,
                                  'debit'=>$patient_data['platelet'],
                                  'blood_group_id'=>$blood_group_id,
                                  'bar_code' => '',
                                  'created_date'=>$date_of_collection,
                                  'created_by'=>$users_data['id'],
                                  'status'=>1,
                                  'qc_status'=>$blood_qc,
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'flag'=>1,
                                );
				    $this->db->insert('hms_blood_stock',$stock_array);
				    //echo $this->db->last_query();
				    $data_id = $this->db->insert_id();  
				    //die;
				    //echo "<pre>"; echo $this->db->last_query(); //exit;
				    
					}
					if(!empty($patient_data['prbc']))
					{
					    
					    //$component_id =16;
					    if(!empty($patient_data['prbc']))
                        {
                            $this->db->select('hms_blood_component_master.id');
                            $this->db->from('hms_blood_component_master');
                            $this->db->where('hms_blood_component_master.branch_id',$users_data['parent_id']);
                           $this->db->where('LOWER(hms_blood_component_master.component)',strtolower('PRBC')); 
                            $query = $this->db->get(); 
                           //echo $this->db->last_query();die;
                            $result =  $query->row_array();    
        					//echo "<pre>"; print_r($result); exit;
        					if(!empty($result))
        					{
        					    $component_id = $result['id'];    
        					}
                        }
                        else
    					{
    					    $component_id= '16'; //bag type
    					}
					    $stock_array=array(
                                  'donor_id'=>$donor_id,
                                  'branch_id'=>$users_data['parent_id'],
                                  'bag_type_id' =>$bag_type,
                                  'component_id' => $component_id,
                                  'component_name'=>'PRBC',
                                  'component_price'=>$patient_data['component_price'],
                                  'qty'=>$patient_data['prbc'],
                                  'volumn'=>$patient_data['prbc'],
                                   'expiry_date' =>$date_of_expiry,
                                  'debit'=>$patient_data['prbc'],
                                  'blood_group_id'=>$blood_group_id,
                                  'bar_code' => '',
                                  'created_date'=>$date_of_collection,
                                  'created_by'=>$users_data['id'],
                                  'status'=>1,
                                  'qc_status'=>$blood_qc,
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'flag'=>1,
                                );
				        $this->db->insert('hms_blood_stock',$stock_array);
				        //echo $this->db->last_query();
				        $data_id = $this->db->insert_id();    
					    
					}
					if(!empty($patient_data['ffp']))
					{
					    
					    if(!empty($patient_data['ffp']))
                        {
                            $this->db->select('hms_blood_component_master.id');
                            $this->db->from('hms_blood_component_master');
                            $this->db->where('hms_blood_component_master.branch_id',$users_data['parent_id']);
                           $this->db->where('LOWER(hms_blood_component_master.component)',strtolower('Fresh Frozen Plasma')); 
                            $query = $this->db->get(); 
                           //echo $this->db->last_query();die;
                            $result =  $query->row_array();    
        					//echo "<pre>"; print_r($result); exit;
        					if(!empty($result))
        					{
        					    $component_id = $result['id'];    
        					}
                        }
                        else
    					{
    					    $component_id= '18'; //bag type
    					}
					    $stock_array=array(
                                  'donor_id'=>$donor_id,
                                  'branch_id'=>$users_data['parent_id'],
                                  'bag_type_id' =>$bag_type,
                                  'component_id' => $component_id,
                                  'component_name'=>'FFP',
                                  'component_price'=>$patient_data['component_price'],
                                  'qty'=>$patient_data['ffp'],
                                  'volumn'=>$patient_data['ffp'],
                                   'expiry_date' =>$date_of_expiry,
                                  'debit'=>$patient_data['ffp'],
                                  'blood_group_id'=>$blood_group_id,
                                  'bar_code' => '',
                                  'created_date'=>$date_of_collection,
                                  'created_by'=>$users_data['id'],
                                  'status'=>1,
                                  'qc_status'=>$blood_qc,
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'flag'=>1,
                                  
                                );
				        $this->db->insert('hms_blood_stock',$stock_array);
				        //echo $this->db->last_query();
				        $data_id = $this->db->insert_id();    
					    
					}
				   
                   }


				
	            }
               	
        }
	}
// Please write code above    
}