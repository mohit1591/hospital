<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_prescription_model extends CI_Model 
{
  	var $table = 'hms_blood_donor';
    var $column = array('hms_blood_donor.id','hms_blood_donor.donor_name','hms_blood_donor.donor_email','hms_blood_donor.status','hms_blood_donor.created_date','hms_blood_donor.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

     // Function to common Insert
    public function common_insert($table_name, $data_array)
    {
        $this->db->insert($table_name,$data_array);
        return $this->db->insert_id();
    }
 
      // Function to common Insert
      public function insert_all($table_name, $data_array)
        {
            $this->db->insert($table_name,$data_array);
          
        }

         // Function to common update
    public function common_update($table_name,$data_array,$where_condition="")
    {
        if($where_condition!="")
        {
            $this->db->where(''.$where_condition.'');
        }
        $this->db->limit('1');
        $this->db->update($table_name,$data_array);
        return ($this->db->affected_rows() > 0) ? 200 : 0; 
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_donor.*, hms_simulation.simulation,  hms_gardian_relation.relation, sim.simulation as relation_simulation,
            (CASE WHEN hms_blood_donor.gender=1 THEN 'Male' WHEN hms_blood_donor.gender=0 THEN 'Female' ELSE 'Other' END ) as gender, concat_ws(' ',hms_blood_donor.address,hms_blood_donor.address2,hms_blood_donor.address3) as address, hms_cities.city, hms_state.state, hms_blood_group.blood_group, hms_blood_mode_of_donation.mode_of_donation,hms_blood_preferred_reminder_service.preferred_reminder_service"); 
     
        $this->db->join('hms_patient','hms_patient.id=hms_blood_donor.patient_id','Left');
        $this->db->join('hms_simulation', 'hms_simulation.id=hms_patient.simulation_id', 'Left');
        $this->db->join('hms_simulation as sim', 'sim.id=hms_patient.relation_simulation_id', 'Left');
        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'Left');
        $this->db->join('hms_relation', 'hms_relation.id=hms_patient.relation_type', 'Left');
        $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','Left');
        $this->db->join('hms_blood_mode_of_donation','hms_blood_mode_of_donation.id=hms_blood_donor.mode_of_donation','Left');
        $this->db->join('hms_blood_preferred_reminder_service','hms_blood_preferred_reminder_service.id=hms_blood_donor.reminder_service_id','left');
        $this->db->from($this->table); 
        $this->db->where('hms_blood_donor.is_deleted','0');
        $this->db->where('hms_blood_donor.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function get_by_id($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('dnr.* , sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender , bg.blood_group , rs.preferred_reminder_service');
        $this->db->from('hms_blood_donor dnr'); 
        $this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        $this->db->join('hms_blood_group bg', 'bg.id=dnr.blood_group_id','left');
        $this->db->join('hms_blood_preferred_reminder_service rs', 'rs.id=reminder_service_id','left');
        $this->db->where('dnr.branch_id',$branch_id);
        $this->db->where('dnr.id',$id);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function delete_donor($id)
    {
        if($id!="" && !empty($id))
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->set('is_deleted',1);
            $this->db->set('deleted_by',$user_data['id']);
            $this->db->set('deleted_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$id);
            $this->db->update('hms_blood_donor');

        }
    }


    public function delete_donor_all($ids)
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
            $this->db->update('hms_blood_donor');
            //echo $this->db->last_query();die;
        }
    }

      function excel_donor_data()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_donor.*,hms_blood_group.blood_group,hms_cities.city,hms_state.state,hms_countries.country");
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->join('hms_cities','hms_cities.id=hms_blood_donor.city_id','left');
         
         $this->db->join('hms_state','hms_state.id=hms_blood_donor.state_id','left');
         $this->db->join('hms_countries','hms_countries.id=hms_blood_donor.country_id','left');
        $this->db->where('hms_blood_donor.is_deleted','0');

        $search = $this->session->userdata('donor_search');
        //print_r($search); exit;
        
        
        
            if(isset($search) && !empty($search))
            {
            $this->db->where('hms_blood_donor.branch_id = "'.$search['branch_id'].'"');
            }
            else
            {
            $this->db->where('hms_blood_donor.branch_id = "'.$user_data['parent_id'].'"');  
            }
        

        $this->db->from($this->table); 
        $this->db->order_by('hms_blood_donor.id','desc');
        $query = $this->db->get(); 

        $data= $query->result();
       // echo $this->db->last_query();die;
        return $data;
    }

     // function to get donor components extracted
    public function get_examination_hide($donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_stock.*');
        $this->db->from('hms_blood_stock');  
        $this->db->where('hms_blood_stock.donor_id',$donor_id);
         $this->db->where('hms_blood_stock.is_issued',1);
        //$this->db->where('ec.is_deleted!=2');
        $this->db->where('hms_blood_stock.branch_id',$branch_id);
        $res=$this->db->get();
       //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return "200";
        else
            return "empty";
    }


// Please write code above    
}