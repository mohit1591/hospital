<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Recipient_model extends CI_Model 
{
  	var $table = 'hms_blood_patient_to_recipient';
    var $column = array('hms_blood_patient_to_recipient.id','hms_blood_patient_to_recipient.branch_id','hms_blood_patient_to_recipient.patient_id','hms_blood_patient_to_recipient.referred_by','hms_blood_patient_to_recipient.hospital_id','hms_blood_patient_to_recipient.doctor_id','hms_blood_patient_to_recipient.blood_group_id','hms_blood_patient_to_recipient.clinical_diagnosis','hms_blood_patient_to_recipient.bag_id','hms_blood_patient_to_recipient.volume','hms_blood_patient_to_recipient.specimen_recived_by','hms_blood_patient_to_recipient.requirement_date','hms_patient.patient_name','hms_patient.mobile_no','hms_patient.gender','hms_blood_patient_to_recipient.status','hms_blood_patient_to_recipient.created_date','hms_blood_patient_to_recipient.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('recipient_search');
        //print_r($search);
        //die;
         $this->db->select("hms_blood_patient_to_recipient.*,hms_patient.id as pat_id,hms_patient.branch_id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no, hms_simulation.simulation,  hms_gardian_relation.relation, sim.simulation as relation_simulation,(CASE WHEN hms_patient.gender=1 THEN 'Male' WHEN hms_patient.gender=0 THEN 'Female' ELSE 'Other' END ) as gender, concat_ws(' ',hms_patient.address,hms_patient.address2,hms_patient.address3) as address, hms_cities.city, hms_state.state,hms_blood_bag_type.bag_type, hms_blood_group.blood_group,hms_patient.mobile_no");
     
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
        $this->db->where('hms_patient.is_deleted','0');
        $this->db->where('hms_blood_patient_to_recipient.is_deleted','0');
        $this->db->where('hms_blood_patient_to_recipient.branch_id = "'.$users_data['parent_id'].'"');
        $i = 0;
          if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_patient_to_recipient.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_patient_to_recipient.created_date <= "'.$end_date.'"');
            }
            if(isset($search['requirement_date']) && !empty($search['requirement_date']))
            {
                $requirement_date = date('Y-m-d',strtotime($search['requirement_date']));
                $this->db->where('hms_blood_patient_to_recipient.requirement_date',$requirement_date);
            }
            if(isset($search['mobile']) && !empty($search['mobile']))
            {
                $this->db->where('hms_patient.mobile_no',$search['mobile']);
            }
           

            if(isset($search['recipient_id']) && !empty($search['recipient_id']))
            {
                $this->db->where('hms_patient.patient_code',$search['recipient_id']);
            }

             if(isset($search['blood_group']) && !empty($search['blood_group']))
            {
                $this->db->where('hms_blood_patient_to_recipient.blood_group_id',$search['blood_group']);
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

    function save_change_price($id,$component_price)
    {
      $user_data = $this->session->userdata('auth_users');
      $post=$this->input->post();
      print_r($post);
      die;
      $component_price=$post['component_price']; 
        $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'qc_field'=>$post['qc_field'],
                    'status'=>$post['status'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
          
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_blood_qc_fields',$data);               
            
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
        
        $this->db->select('dnr.*,hms_blood_patient_to_recipient.id as recipient_id,hms_blood_patient_to_recipient.require_time,hms_blood_patient_to_recipient.branch_id,hms_blood_patient_to_recipient.patient_id,hms_blood_patient_to_recipient.referred_by,hms_blood_patient_to_recipient.hospital_id,hms_blood_patient_to_recipient.doctor_id,hms_blood_patient_to_recipient.blood_group_id,hms_blood_patient_to_recipient.clinical_diagnosis,hms_blood_patient_to_recipient.bag_id,hms_blood_patient_to_recipient.volume,hms_blood_patient_to_recipient.specimen_recived_by,hms_blood_patient_to_recipient.requirement_date,hms_blood_patient_to_recipient.recipient_source,sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender,hms_blood_patient_to_recipient.net_amount,hms_blood_patient_to_recipient.ward_bed_no');
        $this->db->from('hms_patient dnr');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.patient_id=dnr.id','Left'); 
        $this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        //$this->db->join('hms_blood_group bg', 'bg.id=dnr.blood_group_id','left');
        //$this->db->join('hms_blood_preferred_reminder_service rs', 'rs.id=reminder_service_id','left');
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
      

      //get recipient data for issue component
     public function get_by_id_recipient($recipient_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_patient_to_recipient.*,hms_patient.address,hms_patient.address2,hms_patient.address3,hms_patient.pincode,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no,hms_patient.gender,hms_blood_patient_to_recipient.id as recipient_id,hms_blood_bag_type.bag_type,hms_patient.age_y,hms_patient.patient_email,hms_blood_group.blood_group,hms_blood_group.id as b_id');
        $this->db->from('hms_blood_patient_to_recipient');
         $this->db->join('hms_patient','hms_patient.id=hms_blood_patient_to_recipient.patient_id', 'Left');
         $this->db->join('hms_blood_group','hms_blood_group.id = hms_blood_patient_to_recipient.blood_group_id','left');
         $this->db->join('hms_blood_bag_type','hms_blood_bag_type.id=hms_blood_patient_to_recipient.bag_id', 'Left');
        $this->db->where('hms_blood_patient_to_recipient.branch_id',$branch_id);
        $this->db->where('hms_blood_patient_to_recipient.id',$recipient_id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
    }

     //get recipient data for issue component
     public function get_by_id_print_recipient($ids="",$branch_ids="")
    {
        
        $result_sales=array();
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_blood_patient_to_recipient.*,hms_patient.*,hms_gardian_relation.relation,hms_simulation.simulation,hms_sms.simulation as rel_simulation,(CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name)END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_blood_group.blood_group"); 
        $this->db->join('hms_patient','hms_patient.id = hms_blood_patient_to_recipient.patient_id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
        $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
        $this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
        $this->db->join('hms_doctors','hms_doctors.id = hms_blood_patient_to_recipient.doctor_id','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_blood_patient_to_recipient.hospital_id','left');
         $this->db->join('hms_blood_group','hms_blood_group.id = hms_blood_patient_to_recipient.blood_group_id','left');
        $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_blood_patient_to_recipient.id AND hms_branch_hospital_no.section_id=17','left');
        $this->db->where('hms_blood_patient_to_recipient.is_deleted','0'); 
        $this->db->where('hms_blood_patient_to_recipient.branch_id = "'.$branch_ids.'"'); 
        $this->db->where('hms_blood_patient_to_recipient.id = "'.$ids.'"');
        $this->db->from('hms_blood_patient_to_recipient');
        $result_sales['blood_print_list']= $this->db->get()->result();
        //$data= $this->db->get()->result();
         return $result_sales;
       
       //return $result_sales;
    }

      //get recipient data for issue component
    public function get_stock_available($bag_type_id="",$component_name="",$exist_ids='',$bar_code="",$donor_id="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_stock.*,hms_blood_donor.blood_group_id,hms_blood_group.blood_group, bbt.bag_type,hms_blood_donor.donor_code');
        $this->db->from('hms_blood_stock');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id', 'Left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id', 'Left');
     
        $this->db->join('hms_blood_bag_type bbt','bbt.id=hms_blood_stock.bag_type_id','left');
        $this->db->where('hms_blood_stock.status','1');
        //$this->db->where('hms_blood_stock.is_issued','0');
        $this->db->where('hms_blood_stock.flag',1);
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('hms_blood_stock.branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('hms_blood_stock.branch_id',$users_data['parent_id']);
        }
        $this->db->where('hms_blood_group.id',$blood_group_id);
        if((!empty($exist_ids))&&($exist_ids!=''))
        {
            $exist_ids=implode(',', $exist_ids);
             $this->db->where('hms_blood_stock.id NOT IN ('.$exist_ids.')'); 
        }
        if((!empty($component_name)) && ($component_name!=""))           
        {
            $this->db->like('hms_blood_stock.component_name',$component_name);

        }

        if((!empty($bar_code)) && ($bar_code!=""))           
        {
            $this->db->like('hms_blood_stock.bar_code',$bar_code);

        }
        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('hms_blood_stock.donor_id',$donor_id);

        }
        //$this->db->group_by('hms_blood_stock.donor_id');
        $res=$this->db->get();
      // echo $this->db->last_query();die;
      if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

    public function get_stock_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
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
            $this->db->like('component_id',$component_id);

        }

        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('blood_group_id',$blood_group_id);

        }
        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('donor_id',$donor_id);

        }
        if((!empty($bar_code)) && ($bar_code!=""))           
        {
            $this->db->like('bar_code',$bar_code);

        }
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
         //echo $this->db->last_query();
        return $query->row_array();
       
    }
    //get recipient data for issue component

    public function get_by_id_issue_bag($ids)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_stock.*,hms_blood_donor.blood_group_id,hms_blood_group.blood_group, bbt.bag_type,hms_blood_donor.donor_code');
        $this->db->from('hms_blood_stock');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id', 'Left');
         $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id', 'Left');
         $this->db->join('hms_blood_bag_type bbt','bbt.id=hms_blood_stock.bag_type_id','Left');
        $this->db->where('hms_blood_stock.id IN ('.$ids.')');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

    public function get_cross_match_data($ids)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_cross_match_to_component.*,hms_blood_donor.blood_group_id,hms_blood_group.blood_group,hms_blood_donor.donor_code,hms_blood_component_master.component as component_name,hms_blood_cross_match_to_component.barcode as bar_code,hms_blood_cross_match_to_component.donor_actual_id as donor_id');
        $this->db->from('hms_blood_cross_match_to_component');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_cross_match_to_component.donor_actual_id', 'Left');
         $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_cross_match_to_component.blood_group_id', 'Left');
         $this->db->join('hms_blood_component_master','hms_blood_component_master.id=hms_blood_cross_match_to_component.component_id', 'Left');

        // $this->db->join('hms_blood_bag_type bbt','bbt.id=hms_blood_stock.bag_type_id','Left');
        $this->db->where('hms_blood_cross_match_to_component.id IN ('.$ids.')');
        $res=$this->db->get();
        //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }


     public function get_by_id_edit($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
       
        $this->db->select('dnr.*,hms_blood_patient_to_recipient.issue_by_mode,hms_blood_patient_to_recipient.require_time,hms_blood_patient_to_recipient.id as recipient_id,hms_blood_patient_to_recipient.branch_id,hms_blood_patient_to_recipient.patient_id,hms_blood_patient_to_recipient.reference_id,hms_blood_patient_to_recipient.referred_by,hms_blood_patient_to_recipient.hospital_id,hms_blood_patient_to_recipient.doctor_id,hms_blood_patient_to_recipient.blood_group_id,hms_blood_patient_to_recipient.clinical_diagnosis,hms_blood_patient_to_recipient.bag_id,hms_blood_patient_to_recipient.volume,hms_blood_patient_to_recipient.specimen_recived_by,hms_blood_patient_to_recipient.requirement_date,hms_blood_patient_to_recipient.recipient_source,sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender,hms_blood_patient_to_recipient.ward_bed_no,hms_blood_patient_to_recipient.document_name');
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

    public function get_recipient_issued_components($recipient_id)
    {
        $user_data = $this->session->userdata('auth_users');
        $branch_id=$user_data['parent_id'];
        $this->db->select('hms_blood_stock.*, hms_blood_donor.blood_group_id,hms_blood_group.blood_group, bbt.bag_type,hms_blood_donor.donor_code');
      
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id', 'Left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id', 'Left');
        $this->db->join('hms_blood_bag_type bbt','bbt.id=hms_blood_stock.bag_type_id','left');
        $this->db->from('hms_blood_stock');
        $this->db->where('hms_blood_stock.branch_id',$branch_id);
        $this->db->where('hms_blood_stock.recipient_id',$recipient_id);
        $result=$this->db->get();
     // echo $this->db->last_query();die;
        if($result->num_rows() > 0)
                return $result->result();
            else
                return "empty";
    }

    public function get_recipient_payment_details($recipient_id)
    {
        $user_data = $this->session->userdata('auth_users');
        $branch_id=$user_data['parent_id'];
        $this->db->select('*');
        $this->db->from('hms_payment');
        $this->db->where('parent_id',$recipient_id);
        $this->db->where('branch_id',$branch_id);
        $this->db->where('balance > 0');
        $this->db->where('section_id',10);
        $res=$this->db->get();
        if($res->num_rows() > 0)
                return $res->row_array();
            else
                return "empty";
    }

    public function get_recipient_payment_details_print($recipient_id)
    {
        $user_data = $this->session->userdata('auth_users');
        $branch_id=$user_data['parent_id'];
        $this->db->select('*');
        $this->db->from('hms_payment');
        $this->db->where('parent_id',$recipient_id);
        $this->db->where('branch_id',$branch_id);
        //$this->db->where('balance > 0');
        $this->db->where('section_id',10);
        $res=$this->db->get();
        if($res->num_rows() > 0)
                return $res->row_array();
            else
                return "empty";
    }


    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="", $type_id, $section_id)
    {
        //12,13//
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
    $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

    $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
    $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
    $this->db->where('hms_payment_mode_field_value_acc_section.type',$type_id);
    $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
    $this->db->where('hms_payment_mode_field_value_acc_section.section_id',$section_id);
    $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();

    return $query;
    }   


     function template_format($data=""){
        
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_branch_template.*');
        $this->db->where($data);
        //$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->from('hms_print_branch_template');
        $query=$this->db->get()->row();
         //echo $this->db->last_query();die;
        return $query;

    }


    public function check_data_avail_in_stock($key,$branch_id)
    {
        //$recipient_id,$donor_id,$component_id
        $users_data = $this->session->userdata('auth_users');       
        $branch_id=$users_data['parent_id'];    
        $this->db->select('id');
        $this->db->from('hms_blood_stock');
        $this->db->where('branch_id',$branch_id);
        //$this->db->where('donor_id',$donor_id);
        $this->db->where('id',$key);
        $this->db->where('flag',2);
        $res=$this->db->get();
        //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
                return $res->row_array();
            else
                return "empty";
    }

    public function remove_inactive_entries_stock($recipient_id,$donor_id,$branch_id)
    {

        $delete_ids=array();    
        $this->db->select('component_id,bar_code,bag_type_id,donor_id,id');
        $this->db->from('hms_blood_stock');
        $this->db->where('status',0);
        $this->db->where('recipient_id',$recipient_id);
        $this->db->where('branch_id',$branch_id);
        $res=$this->db->get();

        if($res->num_rows() > 0)
        {
            $result=$res->result();


            foreach($result as $data)
            {
                array_push($delete_ids, $data->id);
               //print_r($delete_ids);die;
                 $update_array=array('is_issued'=>0);
                $this->db->where('branch_id',$branch_id);
                $this->db->where('component_id',$data->component_id);
                $this->db->where('donor_id',$data->donor_id);
                $this->db->update('hms_blood_stock',$update_array);
                
            }
            $delete_ids=implode(',', $delete_ids);
            $this->db->where('id in ('.$delete_ids.') ');
            $this->db->delete('hms_blood_stock');
        }
    }

    public function get_patient_by_id($id)
    {

        $this->db->select('hms_patient.*');
        $this->db->from('hms_patient'); 
        $this->db->where('hms_patient.id',$id);
        $query = $this->db->get(); 
        return $query->row_array();
    }
    public function get_component_detail_by_id($id)
    {
        $this->db->select('hms_blood_patient_to_recipient_components.*');
        $this->db->from('hms_blood_patient_to_recipient_components'); 
        $this->db->where('hms_blood_patient_to_recipient_components.receipent_id',$id);
        $query = $this->db->get(); 
        return $query->result();
    }
    public function delete_record_component($receipent_id="",$branch_id="")
    {
      $this->db->where('branch_id',$branch_id);  
      $this->db->where('receipent_id',$receipent_id);  
      $this->db->delete('hms_blood_patient_to_recipient_components');
    }
    public function get_recipient_component_detail($recipient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_patient_to_recipient_components.*,hms_blood_component_master.component');
        $this->db->join('hms_blood_component_master','hms_blood_component_master.id=hms_blood_patient_to_recipient_components.component_id');
        $this->db->where('hms_blood_patient_to_recipient_components.branch_id',$users_data['parent_id']);  
        $this->db->where('hms_blood_patient_to_recipient_components.receipent_id',$recipient_id);  
        
        $result_data= $this->db->get('hms_blood_patient_to_recipient_components')->result();
        $component_name=array();
        if(!empty($result_data))
        {
            foreach($result_data as $res)
            {
                if($res->lc_check_status==1)
                {
                    $lc_check_st='(Leuco Depleted)';
                }
                else
                {
                  $lc_check_st='';  
                }
               $component_name[]=$res->component.'-'.$res->qty.$lc_check_st;
            }   
        }
        $rec_component= implode(',',$component_name);
        return $rec_component;
        
    }
    public function donar_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_donor.*,hms_blood_group.blood_group,hms_blood_group.id as blood_group_id');
        $this->db->from('hms_blood_donor');
        $this->db->where('hms_blood_donor.branch_id',$users_data['parent_id']); 
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->where('hms_blood_donor.is_deleted!=2'); 
        $this->db->where('hms_blood_donor.status',1);
        $query = $this->db->get(); 
        return $query->result();  
    }
    public function vitals_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_vitals.*,hms_blood_vitals.id as vitals_id');
        $this->db->where('hms_blood_vitals.branch_id',$users_data['parent_id']); 
        $this->db->from('hms_blood_vitals');
        $this->db->where('is_deleted!=2'); 
        $this->db->where('status',1);
        $query = $this->db->get(); 
        return $query->result();    
    }
    public function save_cross_match_data($recipient_id,$file_name)
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();
        //print_r($post);die;
        $lc_ststus='';  

        
                $data = array( 
                'branch_id'=>$user_data['parent_id'],
                "starttime_transfusion_date"=>date('Y-m-d',strtotime($post['transfustion_date'])),
                "starttime_transfusion_time"=>date('H:i:s',strtotime(date('d-m-Y').' '.$post['transfustion_time'])),
                "receipent_id"=>$recipient_id,
                "attachement"=>$file_name
                
            ); 
        if(!empty($post['data_id']) && $post['data_id']>0)
        {    
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
            $this->db->update('hms_blood_cross_match',$data);  

            $comp_detail = $this->input->post('donar_detail');

           $comp_detail= array_values($this->input->post('donar_detail'));
            $this->db->where(array('cross_match_id'=>$post['data_id'],'branch_id'=>$user_data['parent_id']));
                $this->db->delete('hms_blood_cross_match_to_component');
            
            if(!empty($comp_detail))
            {
                foreach($comp_detail as $comps)
              {
                if(isset($comps['lc_status']) && $comps['lc_status']==1)
                {
                    $lc_ststus=1;
                }
                else
                {
                    $lc_ststus=0;
                }
                $array_insert= array('branch_id'=>$user_data['parent_id'],
                                  'cross_match_id'=>$post['data_id'],
                                  'donor_actual_id'=>$comps['donar_actual_id'],
                                  'blood_group_id'=>$comps['blood_group_id'],
                                  'component_id'=>$comps['camp_id'],
                                  'leuco_deplecated'=>$lc_ststus,
                                  'qty'=>$comps['quantity'],
                                  'barcode'=>$comps['bar_code'],
                                  'component_price'=>$comps['component_price'],
                                  'expiry_date'=>date('Y-m-d',strtotime($comps['expiry_date']))
                                  );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_blood_cross_match_to_component',$array_insert);
               //echo $this->db->last_query();die;

              }

            }  

            /* insert vital data */
           
            $vitals_ids= array_values($this->input->post('vitals_ids'));
            $this->db->where(array('cross_match_id'=>$post['data_id'],'branch_id'=>$user_data['parent_id']));
                $this->db->delete('hms_blood_cross_match_to_vitals');
            if(!empty($vitals_ids))
            {
                
              foreach($vitals_ids as $vitals_ids_data)
              {
                $array_insert= array('branch_id'=>$user_data['parent_id'],
                                    'cross_match_id'=>$post['data_id'],
                                    'vitals_id'=>$vitals_ids_data['vital_id'],
                                    'value_first'=>$vitals_ids_data['first_val'],
                                    'value_second'=>$vitals_ids_data['second_val'],
                                    'value_third'=>$vitals_ids_data['third_val'],
                                    'value_fourth'=>$vitals_ids_data['fourth_val']

                                  );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_blood_cross_match_to_vitals',$array_insert);
              }

            } 

            /* insert vital data */ 

        }
        else
        {
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_blood_cross_match',$data);
            $last_id = $this->db->insert_id();  
            $comp_detail = $this->input->post('donar_detail');

            $comp_detail= array_values($this->input->post('donar_detail'));
            
            if(!empty($comp_detail))
            {
              foreach($comp_detail as $comps)
              {

                $array_insert= array('branch_id'=>$user_data['parent_id'],
                                  'cross_match_id'=>$last_id,
                                  'donor_actual_id'=>$comps['donar_actual_id'],
                                  'blood_group_id'=>$comps['blood_group_id'],
                                  'component_id'=>$comps['camp_id'],
                                  'leuco_deplecated'=>$comps['lc_status'],
                                  'qty'=>$comps['quantity'],
                                  'barcode'=>$comps['bar_code'],
                                  'component_price'=>$comps['component_price'],
                                  'expiry_date'=>date('Y-m-d',strtotime($comps['expiry_date']))
                                  );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_blood_cross_match_to_component',$array_insert);

              }

            }  

            /* insert vital data */

            $vitals_ids= array_values($this->input->post('vitals_ids'));
            if(!empty($vitals_ids))
            {
              foreach($vitals_ids as $vitals_ids_data)
              {
                $array_insert= array('branch_id'=>$user_data['parent_id'],
                                    'cross_match_id'=>$last_id,
                                    'vitals_id'=>$vitals_ids_data['vital_id'],
                                    'value_first'=>$vitals_ids_data['first_val'],
                                    'value_second'=>$vitals_ids_data['second_val'],
                                    'value_third'=>$vitals_ids_data['third_val'],
                                    'value_fourth'=>$vitals_ids_data['fourth_val']
                                  );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_blood_cross_match_to_vitals',$array_insert);
              }

            } 

            /* insert vital data */            
        }    
    }

    public function cross_match_detail($recipient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_cross_match.*');
        $this->db->from('hms_blood_cross_match');
        $this->db->where('receipent_id',$recipient_id);
        $this->db->where('is_deleted!=2'); 
        $query = $this->db->get(); 
        return $query->row();    
    }

    public function component_detail_by_cross_match_id($cross_match_id="",$recipient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_cross_match_to_component.*,hms_blood_donor.donor_code as donor_id,hms_blood_donor.id as d_id,hms_blood_cross_match_to_component.component_id as camp_id,hms_blood_component_master.component as option,hms_blood_group.blood_group');
        $this->db->from('hms_blood_cross_match_to_component');
        $this->db->where('hms_blood_cross_match_to_component.cross_match_id',$cross_match_id);
        
        $this->db->where('hms_blood_cross_match_to_component.branch_id',$users_data['parent_id']);
        $this->db->where('hms_blood_cross_match_to_component.is_deleted!=2'); 
        $this->db->join('hms_blood_donor','hms_blood_donor.id= hms_blood_cross_match_to_component.donor_actual_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id= hms_blood_cross_match_to_component.blood_group_id','left');
        $this->db->join('hms_blood_component_master','hms_blood_component_master.id= hms_blood_cross_match_to_component.component_id','left');
        $query = $this->db->get(); 
        return  $query->result_array(); 

       
       
    }

     public function vitals_detail_by_cross_match_id($cross_match_id="",$recipient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_cross_match_to_vitals.*,hms_blood_vitals.vitals_name,hms_blood_vitals.id as vitals_id,hms_blood_vitals.vitals_unit');
        $this->db->from('hms_blood_cross_match_to_vitals');
         $this->db->where('hms_blood_cross_match_to_vitals.cross_match_id',$cross_match_id);
        $this->db->join('hms_blood_vitals','hms_blood_vitals.id= hms_blood_cross_match_to_vitals.vitals_id','left');
      
        $query = $this->db->get(); 
        return  $query->result(); 
    }

    public function get_donor_list_component($component_name="",$bar_code="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_stock.*,hms_blood_donor.donor_code');
        $this->db->from('hms_blood_stock');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id', 'Left');
        $this->db->like('hms_blood_stock.component_name',$component_name);
        // if((!empty($component_name)) && ($component_name!=""))           
        // {
        //     $this->db->like('hms_blood_stock.component_name',$component_name);

        // }

        // if((!empty($bar_code)) && ($bar_code!=""))           
        // {
        //     $this->db->like('hms_blood_stock.bar_code',$bar_code);

        // }

        $this->db->group_by('hms_blood_stock.donor_id');
        $query = $this->db->get(); 
        return  $query->result(); 
    }
    public function check_beg_qc($donor_id="")
    {   
        $this->db->select('hms_blood_qc_examination.*');
        $this->db->from('hms_blood_qc_examination');
        $this->db->where('hms_blood_qc_examination.donor_id',$donor_id);
        return $this->db->get()->result(); 
       
    }
    public function get_blood_grp_detail($donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_donor.*');
        $this->db->from('hms_blood_donor');
        $this->db->where('hms_blood_donor.id',$donor_id);
        $res=$this->db->get();
        // echo $this->db->last_query();die;
        if($res->num_rows() > 0)
        return $res->result();
        else
        return "empty";
    }
// Please write code above    
}