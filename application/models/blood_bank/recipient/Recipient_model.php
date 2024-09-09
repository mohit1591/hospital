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
    
    public function crm_get_by_id($id='') 
    {
        
      $this->db->select("crm_leads.name as patient_name,crm_leads.email as patient_email,crm_leads.phone as mobile_no,crm_leads.age_y,crm_leads.age_m,crm_leads.age_d,crm_leads.address,crm_leads.address2,crm_leads.address3, crm_department.department, crm_lead_type.lead_type, crm_source.source, crm_users.name as uname");
      $this->db->join("crm_department","crm_department.id=crm_leads.department_id","left");
      $this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
      $this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
      $this->db->join("crm_users","crm_users.id=crm_leads.created_by","left"); 
      $this->db->where('crm_leads.id', $id);
      $query = $this->db->get('crm_leads');
      //echo $this->db->last_query(); 
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
        $this->db->select("hms_blood_patient_to_recipient.*,hms_patient.*,hms_gardian_relation.relation,hms_simulation.simulation,hms_sms.simulation as rel_simulation,(CASE WHEN hms_blood_patient_to_recipient.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',hms_doctors.doctor_name)END) as doctor_hospital_name,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_blood_group.blood_group,hms_hospital.hospital_name,hms_doctors.doctor_name"); 
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

    public function get_stock_available($bag_type_id="",$component_name="",$exist_ids='',$bar_code="",$donor_id="",$blood_group_id="",$ini_type='0')
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
        $this->db->select('hms_blood_stock.*,hms_blood_donor.blood_group_id,hms_blood_group.blood_group, bbt.bag_type,hms_blood_donor.donor_code,hms_blood_donor.donor_status,hms_blood_donor.id as donors_id');
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
        if((!empty($component_name)) && ($component_name!="") && $ini_type='0')           
        {
            $this->db->like('hms_blood_stock.component_name',$component_name);

        }
        
        if((!empty($component_name)) && ($component_name!="") && $ini_type='1')           
        {
            $component_name_list    =   explode(',',$component_name);
            $l=1;
            foreach($component_name_list as $comps)
            {
                if($l==1)
                {
                $this->db->like('hms_blood_stock.component_name',$comps);
                }
                else
                {
                    $this->db->or_like('hms_blood_stock.component_name',$comps);
                }
            
                $l++;
            }
            
            

        }

        if((!empty($bar_code)) && ($bar_code!=""))           
        {
            $this->db->like('hms_blood_stock.bar_code',$bar_code);

        }
        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('hms_blood_stock.donor_id',$donor_id);

        }
       
        $this->db->where('hms_blood_stock.donation_status',1);
         //$this->db->where('hms_blood_stock.is_issued',0);
        //$this->db->group_by('hms_blood_donor.id');
        $this->db->group_by('hms_blood_stock.component_id');
        //$this->db->group_by('hms_blood_stock.donor_id');
        $res=$this->db->get();
      // echo $this->db->last_query();die;
      if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
    public function get_stock_available20($bag_type_id="",$component_name="",$exist_ids='',$bar_code="",$donor_id="",$blood_group_id="")
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
       echo $this->db->last_query();die;
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
       $this->db->where('donation_status','1');
       $this->db->where('is_issued','0');
        $query = $this->db->get('hms_blood_stock');
        // echo $this->db->last_query();
        return $query->row_array();
       
    }
    public function get_stock_quantity_old($bag_type_id="",$component_id="",$exist_ids='',$donor_id="",$bar_code="",$blood_group_id="")
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
    
     function report_template_format($data=""){
        
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_bank_report_branch_template.*');
        $this->db->where($data);
        //$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->from('hms_bank_report_branch_template');
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
    public function donar_list_old($b_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_donor.*,hms_blood_group.blood_group,hms_blood_group.id as blood_group_id');
        $this->db->from('hms_blood_donor');
        $this->db->where('hms_blood_donor.branch_id',$users_data['parent_id']); 
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->where('hms_blood_donor.is_deleted!=2'); 
        $this->db->where('hms_blood_donor.status',1);
        if((!empty($b_id)) && ($b_id!=""))           
        {
              $this->db->where('hms_blood_donor.blood_group_id',$b_id);

         }
        //$this->db->where('hms_blood_donor.status',1);
        $query = $this->db->get(); 
        //echo $this->db->last_query();//die;
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
       // echo $this->db->last_query();//die;
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
                                  'expiry_date'=>date('Y-m-d H:i:s',strtotime($comps['expiry_date']))
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
            //echo $this->db->last_query()
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
                                  'expiry_date'=>date('Y-m-d H:i:s',strtotime($comps['expiry_date']))
                                  );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_blood_cross_match_to_component',$array_insert);
               // echo $this->db->last_query();

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
                //echo $this->db->last_query();
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
        //echo $this->db->last_query();
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

    public function get_donor_list_component($component_name="",$blood_group_id='',$bar_code="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_stock.*,hms_blood_donor.donor_code');
        $this->db->from('hms_blood_stock');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_stock.blood_group_id','left');
        $this->db->join('hms_blood_donor','hms_blood_donor.id=hms_blood_stock.donor_id', 'Left');
        $this->db->like('hms_blood_stock.component_name',$component_name);
        // if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        // {
        //     $this->db->like('hms_blood_stock.blood_group_id',$blood_group_id);

        //  }

        // if((!empty($bar_code)) && ($bar_code!=""))           
        // {
        //     $this->db->like('hms_blood_stock.bar_code',$bar_code);

        // }

        $this->db->group_by('hms_blood_stock.donor_id');
        $query = $this->db->get(); 
       //echo $this->db->last_query(); die;
        //die;
        return  $query->result(); 
    }

    public function check_beg_qc($donor_id="")
    {   
        $this->db->select('hms_blood_qc_examination.*,hms_blood_qc_examination.blood_condition');
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
    
     public function get_component_unite($id='',$component='')
    {
        $this->db->select('hms_blood_patient_to_recipient_components.comp_unite');
        $this->db->from('hms_blood_patient_to_recipient_components'); 
        $this->db->where('hms_blood_patient_to_recipient_components.receipent_id',$id);
        $this->db->where('hms_blood_patient_to_recipient_components.component_id',$component);
        $query = $this->db->get(); 
        return $query->result();
    }

    public function get_recipient_component_selected_detail($recipient_id="")
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
               $component_name[]=$res->component;
            }   
        }
        $rec_component= implode(',',$component_name);
        return $rec_component;
        
    }
    public function donar_list($b_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_donor.*,hms_blood_group.blood_group,hms_blood_group.id as blood_group_id');
        $this->db->from('hms_blood_donor');
        $this->db->where('hms_blood_donor.branch_id',$users_data['parent_id']); 
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','left');
        $this->db->where('hms_blood_donor.is_deleted!=2'); 
        $this->db->where('hms_blood_donor.status',1);
        if((!empty($b_id)) && ($b_id!=""))           
        {
              $this->db->where('hms_blood_donor.blood_group_id',$b_id);

         }
        //$this->db->where('hms_blood_donor.status',1);
        $query = $this->db->get(); 
        //echo $this->db->last_query();//die;
        return $query->result();  
    }

    public function get_recipient_particular($recipient_id)
{
    $user_data = $this->session->userdata('auth_users');
    $branch_id=$user_data['parent_id'];
    $this->db->select('hms_reciepient_patient_to_charge.*, hms_ipd_perticular.particular');
  
    $this->db->join('hms_ipd_perticular','hms_ipd_perticular.id=hms_reciepient_patient_to_charge.particular_id', 'Left');
    $this->db->from('hms_reciepient_patient_to_charge');
    $this->db->where('hms_reciepient_patient_to_charge.branch_id',$branch_id);
    $this->db->where('hms_reciepient_patient_to_charge.recipient_id',$recipient_id);
    $result=$this->db->get();
 // echo $this->db->last_query();die;
    if($result->num_rows() > 0)
            return $result->result();
        else
            return "empty";
}

    public function delete_charges($id="",$recipient_id="")
    {
        if(!empty($id) && $id>0)
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->where('id',$id);
            $this->db->where('recipient_id',$recipient_id);
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->delete('hms_reciepient_patient_to_charge');
            return $recipient_id;
            //echo $this->db->last_query();die;
        } 
    }




    public function get_particular_data($vals="")
    {
            $response = '';
            if(!empty($vals))
            {
                $users_data = $this->session->userdata('auth_users'); 
                $this->db->select('*');  
                $this->db->where('status','1'); 
                $this->db->order_by('particular','ASC');
                $this->db->where('is_deleted',0);
                $this->db->where('particular LIKE "'.$vals.'%"');
                $this->db->where('branch_id',$users_data['parent_id']);  
                $query = $this->db->get('hms_ipd_perticular');
                $result = $query->result(); 
                //echo $this->db->last_query();
                if(!empty($result))
                { 
                    $data = array();
                    foreach($result as $vals)
                    {
                        //$response[] = $vals->medicine_name;
                        $name = $vals->particular.'|'.$vals->charge.'|'.$vals->id;
                        array_push($data, $name);
                    }

                    echo json_encode($data);
                }
                //return $response; 
            } 
    }

  public function save_particulars($recipient_id)
  {
    $post= $this->input->post();
    $this->db->select('*');
    $users_data = $this->session->userdata('auth_users');
    $particular_data = array(
                            'branch_id'=>$users_data['parent_id'],
                            'recipient_id'=>$recipient_id,
                            //'type'=>2,
                            'particular_id'=>$post['particular_id'],
                            'payment_date'=>date('Y-m-d',strtotime($post['date'])),
                            'particular'=>$post['particular'],
                            'quantity'=>$post['qty'],
                            'price'=>$post['charge'],
                            'net_price'=>$post['qty']*$post['charge'],
                            'status'=>1,
                            );
    $this->db->insert('hms_reciepient_patient_to_charge',$particular_data);
   //echo $this->db->last_query(); exit;
  }

  /*public function delete_charges($id="",$recipient_id="")
    {
        if(!empty($id) && $id>0)
        {
            $user_data = $this->session->userdata('auth_users');
            $this->db->where('id',$id);
            $this->db->where('recipient_id',$recipient_id);
            $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->delete('hms_reciepient_patient_to_charge');
            return $recipient_id;
            //echo $this->db->last_query();die;
        } 
    }*/


    function transfusion_template_format($data=""){
        
        $this->db->select('hms_print_transfusion_template.*');
        $this->db->where($data);
        $this->db->from('hms_print_transfusion_template');
        $query=$this->db->get()->row();
         //echo $this->db->last_query();die;
        return $query;

    }
    
      function compatilbilty_template_format($data=""){
        
        $this->db->select('hms_print_compatilbilty_template.*');
        $this->db->where($data);
        $this->db->from('hms_print_compatilbilty_template');
        $query=$this->db->get()->row();
         //echo $this->db->last_query();die;
        return $query;

    }
    
    public function get_by_id_patient_details($id)
    {
        $users_data = $this->session->userdata('auth_users');
        $branch_id=$users_data['parent_id'];
       
        $this->db->select('dnr.*,hms_blood_patient_to_recipient.issue_by_mode,hms_blood_patient_to_recipient.require_time,hms_blood_patient_to_recipient.id as recipient_id,hms_blood_patient_to_recipient.branch_id,hms_blood_patient_to_recipient.patient_id,hms_blood_patient_to_recipient.reference_id,hms_blood_patient_to_recipient.referred_by,hms_blood_patient_to_recipient.hospital_id,hms_blood_patient_to_recipient.doctor_id,hms_blood_patient_to_recipient.blood_group_id,hms_blood_patient_to_recipient.clinical_diagnosis,hms_blood_patient_to_recipient.bag_id,hms_blood_patient_to_recipient.volume,hms_blood_patient_to_recipient.specimen_recived_by,hms_blood_patient_to_recipient.requirement_date,hms_blood_patient_to_recipient.recipient_source,sim.simulation as simulation_donor, (CASE WHEN dnr.gender=0 THEN "Female" WHEN dnr.gender=1 THEN "Male" ELSE "Other" END) as donor_gender,hms_blood_patient_to_recipient.ward_bed_no,hms_blood_patient_to_recipient.document_name,dpc.doctor_name as doctors_name');
        $this->db->from('hms_patient dnr');
        $this->db->join('hms_blood_patient_to_recipient','hms_blood_patient_to_recipient.patient_id=dnr.id','Left'); 
        $this->db->join('hms_simulation sim','sim.id=dnr.simulation_id','Left');
        
         $this->db->join('hms_doctors as dpc','dpc.id=hms_blood_patient_to_recipient.doctor_id', 'Left');
         
         
        //$this->db->join('hms_blood_group bg', 'bg.id=dnr.blood_group_id','left');
        //$this->db->join('hms_blood_preferred_reminder_service rs', 'rs.id=reminder_service_id','left');
        $this->db->where('dnr.branch_id',$branch_id);
        $this->db->where('hms_blood_patient_to_recipient.patient_id',$id);
        $query = $this->db->get(); 
       //echo $this->db->last_query();die;
        return $query->row_array();
    }
    
    public function report_html_template($part="",$branch_id="")
    {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('hms_print_letter_head_template_setting.*');
          if(!empty($branch_id))
          {
              $this->db->where('hms_print_letter_head_template_setting.branch_id',$branch_id);
          }
          else
          {
               $this->db->where('hms_print_letter_head_template_setting.branch_id',$users_data['parent_id']);
          } 
            $this->db->where('hms_print_letter_head_template_setting.unique_id',7);
            $this->db->from('hms_print_letter_head_template_setting'); 
            $result=$this->db->get()->row(); 
            // echo $this->db->last_query();die;
            return $result;
    }
    
    
     public function get_blood_group_name($vals="")
    {
        $response = '';
        if(!empty($vals))
        {
            
            $this->db->select('*');
            $this->db->from('hms_blood_group');
            $this->db->where('blood_group LIKE "'.$vals.'%"');
            $this->db->where('status','1');
            $query=$this->db->get();
            $result = $query->result(); 
            //echo $this->db->last_query();
            if(!empty($result))
            { 
                $data = array();
                foreach($result as $vals)
                {
                    //$response[] = $vals->medicine_name;
                    $name = $vals->blood_group.'|'.$vals->id;
                    array_push($data, $name);
                }

                echo json_encode($data);
            }
            //return $response; 
        } 
    }
    
    
    public function save_all_blood_recipient($patient_all_data = array())
	{
		$this->load->model('blood_bank/general/blood_bank_general_model','blood_general_model');

		//echo "<pre>";print_r($patient_all_data); exit;//$patient_data['relation_type']
 		$users_data = $this->session->userdata('auth_users');
 		$branch_id = $users_data['parent_id'];
        if(!empty($patient_all_data))
        {
            foreach($patient_all_data as $patient_data)
            {
                
                /*
                 $excelDate = $patient_data['date_of_collection'];
                    $timestamp = $excelDate * 60 * 60 * 24;
                    $date_of_collection = date('Y-m-d', $timestamp);
                    
                    
                    $excelDates = $patient_data['date_of_expiry'];
                    $timestampd = $excelDates * 60 * 60 * 24;
                    $date_of_expiry = date('Y-m-d', $timestampd);*/
                    if($patient_data['BLOODGROUP']=='B+Ve')
			        {
			          $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='O+ve')
			        {
			            $blood_group_id=8;  
			        }
			        else if($patient_data['BLOODGROUP']=='A+ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['BLOODGROUP']=='AB+ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='B-VE')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['BLOODGROUP']=='NT')
			        {
			            $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='')
			        {
			            $blood_group_id=4;  
			        }
			        
			       
			        $simulation_id='';
            		if(!empty($patient_data['simulation_id']))
            		{
		            	$this->db->select("hms_simulation.*");
					    $this->db->from('hms_simulation'); 
		                $this->db->where('LOWER(hms_simulation.simulation)',strtolower($patient_data['simulation_id'])); 
					    $this->db->where('hms_simulation.branch_id='.$users_data['parent_id']);  
					    $query = $this->db->get(); 
					    // echo $this->db->last_query();die;
					    $simulation_data = $query->result_array();

					    if(!empty($simulation_data))
					    {
						    $simulation_id = $simulation_data[0]['id'];
					    }
					    else
					    {
							$simulation_insert_data = array(
							'branch_id'=>$users_data['parent_id'],
							'simulation'=>$patient_data['simulation_id'],
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_by'=>$users_data['parent_id'],
							'created_date'=>date('Y-m-d H:i:s'),
							'status'=>'1'
							);
							$this->db->insert('hms_simulation',$simulation_insert_data);
							$simulation_id = $this->db->insert_id();
					    }
					}
			        
				/* $array_keys = array('simulation_id','patient_name','mobile_no','pincode','age_y','age_m','age_d','patient_email','address','address_second','address_third','gender','BLOODGROUP');*/
			        	
					$patient_data_array=array(
                           'simulation_id'=>$simulation_id,
                           'branch_id'=>$branch_id,
                           'patient_name'=>$patient_data['patient_name'],
                           'mobile_no'=>$patient_data['mobile_no'],
                           'pincode'=>$patient_data['pincode'],
                           'age_y'=>$patient_data['age_y'],
                           'age_m'=>$patient_data['age_m'],
                           'age_d'=>$patient_data['age_d'],
                           'patient_email'=>$patient_data['patient_email'],
                           'address'=>$patient_data['address'],
                           'address2'=>$patient_data['address_second'],
                           'address3'=>$patient_data['address_third'],
                           'gender'=>$patient_data['gender'],
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );
					
					
					
					$patient_code = generate_unique_id(4);
                    $patient_data_array["patient_code"]=$patient_code;
                    $patient_data_array["branch_id"]=$branch_id;
                    $patient_data_array["created_by"]=$users_data['id'];
                    $patient_data_array["created_date"]=date('Y-m-d H:i:s');
                    $patient_rec_id = $this->blood_general_model->common_insert('hms_patient',$patient_data_array);
       
                    $patient_data_array_recipient=array(
                                       'patient_id'=>$patient_rec_id,
                                       'branch_id'=>$branch_id,
                                        'reference_id'=>0, 
                                       'referred_by'=>0,
                                       'hospital_id'=>0,
                                       'document_name'=>0,
                                       'doctor_id'=>0,
                                       'ward_bed_no'=>0,
                                       'blood_group_id'=>$blood_group_id,
                                       'clinical_diagnosis'=>0,
                                       'volume'=>0,
                                       'specimen_recived_by '=>0,
                                       'bag_id'=>0,
                                       'recipient_source'=>0,
                                        'issue_by_mode'=>0,
                                       'requirement_date'=>date('Y-m-d'),
                                       'require_time'=>date('H:i:s'),
                                        'created_date'=>date('Y-m-d H:i:s'),
                                       'status'=>'1',
                                       'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                       );
                    $insert_data = $this->blood_general_model->common_insert('hms_blood_patient_to_recipient',$patient_data_array_recipient);
					//echo $this->db->last_query(); exit;
					
					
					
					
		


					
	            }
               	
        }
	}

// Please write code above    
}