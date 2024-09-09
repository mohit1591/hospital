<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pediatrician_general_model extends CI_Model 
{
  
	public function __construct()
	{
		parent::__construct();  
	}


   

     //department_list

    public function get_recommended_age_according_to_vaccine($vaccine_id="",$branch_id="")
    {
        
        $this->db->select('hms_pediatrician_age_vaccination_to_age.*');  
        $this->db->where('hms_pediatrician_age_vaccination_to_age.type',1);  
        $this->db->where('hms_pediatrician_age_vaccination_to_age.vaccine_id',$vaccine_id);   
        $this->db->where('hms_pediatrician_age_vaccination_to_age.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_age_vaccination_to_age.is_deleted=',0);
       
         $this->db->join('hms_pediatrician_age_vaccination','hms_pediatrician_age_vaccination_to_age.age_vaccination_id=hms_pediatrician_age_vaccination.id','left');
           $this->db->where('hms_pediatrician_age_vaccination.status',1);
        $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function get_catchup_immuniation_age_according_to_vaccine($vaccine_id="",$branch_id="")
    {
        
        $this->db->select('hms_pediatrician_age_vaccination_to_age.*');  
        $this->db->where('hms_pediatrician_age_vaccination_to_age.type',2);  
        $this->db->where('hms_pediatrician_age_vaccination_to_age.vaccine_id',$vaccine_id);   
        $this->db->where('hms_pediatrician_age_vaccination_to_age.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_age_vaccination_to_age.is_deleted=',0);
      
          $this->db->join('hms_pediatrician_age_vaccination','hms_pediatrician_age_vaccination_to_age.age_vaccination_id=hms_pediatrician_age_vaccination.id','left');
           $this->db->where('hms_pediatrician_age_vaccination.status',1);
        $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
        $result = $query->result(); 
        return $result; 
    }

    public function get_catchup_risk_age_according_to_vaccine($vaccine_id="",$branch_id="")
    {
        
        $this->db->select('hms_pediatrician_age_vaccination_to_age.*');  
        $this->db->where('hms_pediatrician_age_vaccination_to_age.type',3);  
        $this->db->where('hms_pediatrician_age_vaccination_to_age.vaccine_id',$vaccine_id);   
        $this->db->where('hms_pediatrician_age_vaccination_to_age.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_age_vaccination_to_age.is_deleted=',0);
        $this->db->join('hms_pediatrician_age_vaccination','hms_pediatrician_age_vaccination_to_age.age_vaccination_id=hms_pediatrician_age_vaccination.id','left');
           $this->db->where('hms_pediatrician_age_vaccination.status',1);
        $query = $this->db->get('hms_pediatrician_age_vaccination_to_age');
        $result = $query->result(); 
        return $result; 
    }

    public function get_vaccine_already_exits($age_id="",$vaccine_id="",$booking_id="",$branch_id="")
    {
        //echo $vaccine_id;die;
        $this->db->select('hms_pediatrician_vaccine_prescription_to_vaccine.*');  
        $this->db->where('hms_pediatrician_vaccine_prescription_to_vaccine.age_id',$age_id);  
        $this->db->where('hms_pediatrician_vaccine_prescription_to_vaccine.v_id',$vaccine_id); 
        $this->db->join('hms_pediatrician_vaccine_prescription','hms_pediatrician_vaccine_prescription.id=hms_pediatrician_vaccine_prescription_to_vaccine.vaccine_pres_id');  
        $this->db->where('hms_pediatrician_vaccine_prescription.branch_id',$branch_id);
        $this->db->where('hms_pediatrician_vaccine_prescription.booking_id',$booking_id);
        $query = $this->db->get('hms_pediatrician_vaccine_prescription_to_vaccine');
        $result = $query->result(); 
       //echo $this->db->last_query();
        return $result;
    }

   public function get_age_according_to_limit($start_age_type="",$end_age_type="",$start_age="",$end_age="",$title="",$date_new="")
    {

           $end_date_interval=array();
            $start_date_interval=array(); 
           if($start_age_type==1 && $end_age_type==1)  //1 for days
           {
                 if($start_age!='' && $title!=0)
                {

                  $end_date_interval['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$start_age.' day'));
                  
                }
                else
                {
                  $end_date_interval['start_age']='';  
                }
                if($end_age_type!='' && $title=='birth')
                {

                  $end_date_interval['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$end_age.' day'));
                }
                else
                {
                    $end_date_interval['end_age']='';
                }
               
           }

           //2 for week
           if($start_age_type==2 && $end_age_type==2)
           {
                if($start_age!=0 && $start_age!='')
                {
                  $end_date_interval['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$start_age.' week'));
                  
                }
                else
                {
                    $end_date_interval['start_age']='';
                }
                if($end_age!='' && $end_age!=0)
                {

                  $end_date_interval['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$end_age.' week'));
                }
                else
                {
                  $end_date_interval['end_age']='';
                }
               
            }
          
            if($start_age_type==3 && $end_age_type==3)
           {
                if($start_age!=0 && $start_age!='')
                {

                  $end_date_interval['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$start_age.' month'));
                  
                }
                else
                {
                  $end_date_interval['start_age']='';  
                }
                if($end_age!='' && $end_age!=0)
                {

                  $end_date_interval['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$end_age.' month'));
                }
                else
                {
                  $end_date_interval['end_age']='';
                }
               
            }//3 months
           

            if($start_age_type==4 && $end_age_type==4)
            {
                if($start_age!=0 && $start_age!='')
                {
                  $end_date_interval['start_age']= date('Y-m-d', strtotime($date_new. ' + '.$start_age.' year'));
                  
                }
                else
                {
                  $end_date_interval['start_age']='';  
                }
                if($end_age!='' && $end_age!=0)
                {

                  $end_date_interval['end_age']= date('Y-m-d', strtotime($date_new. ' + '.$end_age.' year'));
                }
                else
                {
                  $end_date_interval['end_age']='';
                }
               
             }//4 week

    
       // $arr= array_merge($start_date_interval,$end_date_interval);
      
        return $end_date_interval;

    }

    // for checking previous history duplicacy masters
    public function check_prv_history($previous_history="",$id="")
    {

        $this->db->select('*');  
        if(!empty($previous_history))
        {
        $this->db->where('prv_history',$previous_history);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_prv_history');
        $result = $query->result(); 

        return $result; 
    }
    // for checking previous history duplicacy masters

         // for checking diagnosis history duplicacy masters
    public function check_diagnosis($diagnosis="",$id="")
    {

        $this->db->select('*');  
        if(!empty($diagnosis))
        {
        $this->db->where('diagnosis',$diagnosis);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_diagnosis');
        $result = $query->result(); 

        return $result; 
    }
    // for checking diagnosis history duplicacy masters

        // for checking dosage duplicacy masters
    public function check_dosage($dosage="",$id="")
    {

        $this->db->select('*');  
        if(!empty($dosage))
        {
        $this->db->where('dosage',$dosage);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_medicine_dosage');
        $result = $query->result(); 

        return $result; 
    }
    // for checking dosage duplicacy masters

    // parameters personal_history ,id
    public function check_personal_history($personal_history="",$id="")
    {

        $this->db->select('*');  
        if(!empty($personal_history))
        {
        $this->db->where('personal_history',$personal_history);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_personal_history');
        $result = $query->result(); 

        return $result; 
    }
    // for checking personal history duplicacy masters

     // parameters medicineadvice ,id
    public function check_medicine_advice($medicine_advice="",$id="")
    {

        $this->db->select('*');  
        if(!empty($medicine_advice))
        {
        $this->db->where('medicine_advice',$medicine_advice);
        } 
        if(!empty($id))
        {
        $this->db->where('id!= "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_advice');
        $result = $query->result(); 

        return $result; 
    }
    // for checking medicine advice duplicacy masters

    // parameters frequency ,id
    public function check_frequency($frequency="",$id="")
    {

        $this->db->select('*');  
        if(!empty($frequency))
        {
        $this->db->where('medicine_dosage_frequency',$frequency);
        } 
        if(!empty($id))
        {
        $this->db->where('id!= "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_medicine_dosage_frequency');
        $result = $query->result(); 

        return $result; 
    }
    // for checking frequency duplicacy masters


     // for check_duration duplicacy masters
    // function name : check_duration
    // parameters frequency ,id
    public function check_duration($duration="",$id="")
    {

        $this->db->select('*');  
        if(!empty($duration))
        {
        $this->db->where('medicine_dosage_duration',$duration);
        } 
        if(!empty($id))
        {
        $this->db->where('id!= "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_medicine_dosage_duration');
        $result = $query->result(); 

        return $result; 
    }
    // for checking frequency duplicacy masters

      // for checking suggestion duplicacy masters

        // for suggestion duplicacy masters
    // function name : check_keratometer
    // parameters suggetion ,id
    public function check_medicine_suggetion($medicine_suggetion="",$id="")
    {

        $this->db->select('*');  
        if(!empty($medicine_suggetion))
        {
        $this->db->where('medicine_suggetion',$medicine_suggetion);
        } 
        if(!empty($id))
        {
        $this->db->where('id!= "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_suggetion');
        $result = $query->result(); 

        return $result; 
    }
    // for checking suggestion duplicacy masters

     // for check_medicine_company duplicacy masters
    public function check_medicine_company($company_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($company_name))
        {
        $this->db->where('company_name',$company_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_medicine_company');
        $result = $query->result(); 

        return $result; 
    }
    // for check_medicine_company duplicacy masters

        // for check_medicine_unit duplicacy masters
    public function check_medicine_unit($medicine_unit="",$id="")
    {

        $this->db->select('*');  
        if(!empty($medicine_unit))
        {
        $this->db->where('medicine_unit',$medicine_unit);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_medicine_unit');
        $result = $query->result(); 

        return $result; 
    }
    // for check_medicine_unit duplicacy masters

      // for checking examination duplicacy masters
    public function check_examination($examination="",$id="")
    {

        $this->db->select('*');  
        if(!empty($examination))
        {
        $this->db->where('examination',$examination);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_examination');
        $result = $query->result(); 

        return $result; 
    }

    // for checking examination duplicacy masters

     public function chief_complaint_list($doctor_id="", $type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');  
        if(!empty($type)) {
          $this->db->where('hms_pediatrician_chief_complaints.chief_complaints LIKE "'.$type.'%"');
      }  
        $query = $this->db->get('hms_pediatrician_chief_complaints');
        $result = $query->result(); 
        return $result; 
    }
    // for checking cheif compalin duplicacy master

      public function check_complain($chief_complaints="",$id="")
    {

        $this->db->select('*');  
        if(!empty($chief_complaints))
        {
        $this->db->where('chief_complaints',$chief_complaints);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_pediatrician_chief_complaints');
        $result = $query->result(); 

        return $result; 
    }

    /* prescription setting for pediatrician */

    function get_pediatrician_prescription_tab_setting($parent_id="",$order_by='')
    {

            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_pediatrician_branch_prescription_tab_setting.*,hms_pediatrician_prescription_tab_setting.var_title');   
            $this->db->join('hms_pediatrician_branch_prescription_tab_setting','hms_pediatrician_branch_prescription_tab_setting.unique_id = hms_pediatrician_prescription_tab_setting.id');
            $this->db->where('hms_pediatrician_branch_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_pediatrician_branch_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_pediatrician_branch_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_pediatrician_branch_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_pediatrician_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_pediatrician_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_pediatrician_branch_prescription_medicine_tab_setting.*,hms_pediatrician_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_pediatrician_branch_prescription_medicine_tab_setting','hms_pediatrician_branch_prescription_medicine_tab_setting.unique_id = hms_pediatrician_prescription_medicine_tab_setting.id');
            $this->db->where('hms_pediatrician_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_pediatrician_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_pediatrician_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_pediatrician_branch_prescription_medicine_tab_setting.status',1);
            $query = $this->db->get('hms_pediatrician_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    /* prescription setting for pediatrician */

}
