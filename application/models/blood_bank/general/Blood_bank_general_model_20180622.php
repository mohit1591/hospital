<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blood_bank_general_model extends CI_Model 
{
  
	public function __construct()
	{
		parent::__construct();  
	}


    // for checking check_preferred_reminder_service duplicacy master
    public function check_preferred_reminder_service($preferred_reminder_service="",$id="")
    {

        $this->db->select('*');  
        if(!empty($preferred_reminder_service))
        {
        $this->db->where('preferred_reminder_service',$preferred_reminder_service);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_preferred_reminder_service');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    
    // for checking check_preferred_reminder_service duplicacy master

    public function check_deferral_reason($deferral_reason="",$id="")
    {

        $this->db->select('*');  
        if(!empty($deferral_reason))
        {
        $this->db->where('deferral_reason',$deferral_reason);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_deferral_reason');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    // for checking check_beg_type duplicacy master

    public function check_bag_type($bag_type="",$id="")
    {

        $this->db->select('*');  
        if(!empty($bag_type))
        {
        $this->db->where('bag_type',$bag_type);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_bag_type');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

     public function check_profile_name($profile_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($profile_name))
        {
        $this->db->where('profile_name',$profile_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_employee_profile_type');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }
     public function check_bag_type_id($bag_type_id="",$id="")
    {

        $this->db->select('*');  
        if(!empty($bag_type_id))
        {
        $this->db->where('bag_type_id',$bag_type_id);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_bag_type_to_component');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

     public function check_component_name($component="",$id="")
    {

        $this->db->select('*');  
        if(!empty($component))
        {
        $this->db->where('component',$component);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_component_master');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function get_component_list()
    {

        $this->db->select('*');
        //$this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
         $this->db->where('is_deleted','0');
        $query = $this->db->get('hms_blood_component_master');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function check_qc_field($qc_field="",$id="")
    {

        $this->db->select('*');  
        if(!empty($qc_field))
        {
        $this->db->where('qc_field',$qc_field);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_qc_fields');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }
      // for checking check_beg_type duplicacy master


    // Function to common Insert
    public function common_insert($table_name, $data_array)
    {
        $this->db->insert($table_name,$data_array);
        //echo $this->db->last_query();die;
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
    // Function to Common Update


    // Function to get list of Reminder Sevices
    public function get_reminder_services_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_preferred_reminder_service');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('hms_blood_preferred_reminder_service.is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
    // Function to get list of Reminder Sevices
 // Function to get list of Reminder Sevices
    public function get_post_complication_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_post_complication');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('hms_blood_post_complication.is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

    // Function to get list of Blood Groups
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

    public function get_blood_group_by_id($id="")
    {
        $this->db->select('*');
        $this->db->from('hms_blood_group');
        $this->db->where('status','1');
        $this->db->where('id',$id);
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }


     // Function to get list of Hospitals
    public function get_hospital_list()
    {
        $this->db->select('*');
        $this->db->from('hms_hospital');
        $this->db->where('status','1');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

      // Function to get list of bag
    public function get_bag_list()
    {
         $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_bag_type');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

     // Function to get list of doctors
    public function get_doctors_list()
    {
        $this->db->select('*');
        $this->db->from('hms_doctors');
        $this->db->where('status','1');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
    // Function to get list of Blood groups


    // Function to get list of Blood Donation modes
    public function get_mode_of_donation_list()
    {
        $this->db->select('*');
        $this->db->from('hms_blood_mode_of_donation');
        $this->db->where('status','1');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }
    // Function to get list of Blood Donation modes

    // Function to get Blood camp list
    public function get_blood_camp_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_camp_details');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";   
    }
    // Function to get Blood camp list


    // Function to get general settings
    public function get_common_settings($title)
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_bank_setting');
        $this->db->where('var_title',$title);
        $this->db->where('branch_id',$users_data['parent_id']);
        //$this->db->where('status','1');
        $res=$this->db->get();
        //echo $this->db->last_query();die;
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";   
    }
    // Function to get general settings


    // Funciton to generate Donor Code
    public function total_donors($parent_id="", $prefix="0")
    {

        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_donor.*'); 
        $this->db->where('hms_blood_donor.branch_id',$parent_id); 
        $this->db->where('hms_blood_donor.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_blood_donor.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "41" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_blood_donor');
        //echo $this->db->last_query();die;
        $result = $query->num_rows(); 
        return $result; 
    }
    // Function to generate Donor code

    // Funciton to generate Donor Code
    public function total_issued($parent_id="", $prefix="0")
    {

        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_patient_to_recipient.*'); 
        $this->db->where('hms_blood_patient_to_recipient.branch_id',$parent_id); 
        $this->db->where('hms_blood_patient_to_recipient.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_blood_patient_to_recipient.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "43" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_blood_patient_to_recipient');
       // echo $this->db->last_query();die;
        $result = $query->num_rows(); 
        return $result; 
    }
    // Function to check duplicate email for donor
    public function check_email($email="")
    {
        $this->db->select('*');  
        if(!empty($email))
        {
            $this->db->where('donor_email',$email);
        }
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        }  
        $this->db->where('is_deleted!=2');
        $query = $this->db->get('hms_blood_donor');
        $result = $query->result(); 
        return $result; 
    } 
    // Function to check duplicate email for donor



    // Funtion to get deferral reasons list
    public function get_deferral_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_deferral_reason');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";   
    }
    // funtion to get deferral reasons list


    // function to get Blood Bag List
    public function get_blood_bag_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_bag_type');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty"; 
    }
    // Function to get Blood bag list

    //  Function to get Qc fields list
    public function get_qc_fields()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_qc_fields');
        $this->db->where('branch_id',$users_data['parent_id']);
         $this->db->where('parent_qc_field','0');
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

    public function get_subcat_qc_fields($id='')
    {
          
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->from('hms_blood_qc_fields');
        $this->db->where('branch_id',$users_data['parent_id']);
         $this->db->where('parent_qc_field',$id);
        $this->db->where('status','1');
        $this->db->where('is_deleted','0');
        $res=$this->db->get();
        if($res->num_rows() > 0)
            return $res->result();
        else
            return "empty";
    }

      public function get_cat_name($id="")
    {

        $this->db->select('qc_field');   
        if(!empty($id))
        {
       $this->db->where('id',$id);
       //$this->db->where('investigation_cat!=0');
        } 
        $users_data = $this->session->userdata('auth_users');
        $query = $this->db->get('hms_blood_qc_fields');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        //print_r($result);die;
        return $result; 
    }
    // Function to get qc field lists
    
 public function get_setting_value_for_donor($var_title='',$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_website_setting.var_name,hms_website_setting.setting_value'); 
        $this->db->where('hms_website_setting.var_title',$var_title); 
        $this->db->where('hms_website_setting.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_website_setting');
        $result = $query->row(); 
        //echo $this->db->last_query(); 
        return $result; 
    }


    
     public function bag_qc_subcategory_name_field($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('qc_field');
        $this->db->from('hms_blood_qc_fields');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('id',$parent_id);
        $query = $this->db->get();
         //echo $this->db->last_query();die; 
        $result = $query->row();
          if($result!='')
            return $result;
        else
            return "empty"; 
       
        
    }

    public function get_stock_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="")
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

        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('donor_id',$donor_id);

        }
        $query = $this->db->get('hms_blood_stock');
        return $query->row_array();
       
    }

    public function get_bar_code_details($blood_detail_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_details_to_barcode.*');
        $this->db->from('hms_blood_details_to_barcode');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('blood_detail_id',$blood_detail_id);
        $query = $this->db->get();
        $result = $query->result();
          if($result!='')
            return $result;
        else
            return "empty"; 
    }

    public function get_bar_code_data($blood_detail_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_details_to_barcode.*');
        $this->db->from('hms_blood_details_to_barcode');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('blood_detail_id',$blood_detail_id);
        $query = $this->db->get();
        $result = $query->result();

        $barcode=array();
          if(!empty($result))
          {
            foreach($result as $results_data)
            {
                $barcode[]= $results_data->bar_code;
            }
          }
           $b_ids= implode(',',$barcode);
           
          return $b_ids;
    }
    
   public function get_issued_component_quantity($bag_type_id="",$component_id="",$exist_ids='',$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(credit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->like('component_id',$component_id);

        }

        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('donor_id',$donor_id);

        }
        $this->db->where('is_issued',2);
        $this->db->where('expiry_date>=',date('Y-m-d H:i:s'));
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();
        return $query->row_array();
       
    }
    public function get_stock_quantity_tested_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(hms_blood_stock.debit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('hms_blood_stock.branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('hms_blood_stock.branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->like('hms_blood_stock.component_id',$component_id);

        }

        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('hms_blood_stock.donor_id',$donor_id);

        }
        $this->db->join('hms_blood_qc_examination_to_fields','hms_blood_qc_examination_to_fields.donor_id=hms_blood_stock.donor_id');
        $this->db->where('hms_blood_stock.expiry_date>=',date('Y-m-d H:i:s'));
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();die;
        return $query->row_array();
       
    }
     public function get_stock_quantity_untested_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(hms_blood_stock.debit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('hms_blood_stock.branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('hms_blood_stock.branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->like('hms_blood_stock.component_id',$component_id);

        }

        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('hms_blood_stock.donor_id',$donor_id);

        }
        $this->db->join('hms_blood_qc_examination_to_fields','hms_blood_qc_examination_to_fields.donor_id=hms_blood_stock.donor_id');
        $this->db->where('hms_blood_qc_examination_to_fields.donor_id!=',$donor_id);
        $this->db->where('hms_blood_stock.expiry_date',date('Y-m-d H:i:s'));
        $query = $this->db->get('hms_blood_stock');
        return $query->row_array();
       
    }
    public function get_stock_quantity_expired_data($bag_type_id="",$component_id="",$exist_ids='',$donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("(sum(debit)) as total_qty");
        if(isset($search['branch_id']) && $search['branch_id']!=''){
        $this->db->where('branch_id IN ('.$search['branch_id'].')');
        }else{
        $this->db->where('branch_id',$users_data['parent_id']);
        }
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->like('component_id',$component_id);

        }

        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->like('donor_id',$donor_id);

        }
        $this->db->where('hms_blood_stock.expiry_date < "'.date('Y-m-d H:i:s').'"');
        $this->db->where('flag',1);
        $this->db->where('expiry_date<',date('Y-m-d H:i:s'));
        $query = $this->db->get('hms_blood_stock');
        return $query->row_array();
       
    }
// Please write code above this
}
