<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dental_general_model extends CI_Model 
{
  
	public function __construct()
	{
		parent::__construct();  
	}


    // for checking surgery duplicacy master
    public function check_surgery_type($surgery_type="",$id="")
    {
        $this->db->select('*');  
        if(!empty($surgery_type))
        {
        $this->db->where('surgery_type',$surgery_type);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_eye_surgery_type');
        $result = $query->result(); 
        return $result; 
    }
    // for checking surgery duplicacy master


    
    // for checking cheif compalin duplicacy master
    // function name : check_complain 
    // parameters cheficomplain name,id
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
        $query = $this->db->get('hms_dental_chief_complaints');
        $result = $query->result(); 

        return $result; 
    }

    
    public function check_disease($disease_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($disease_name))
        {
        $this->db->where('disease_name',$disease_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_disease');
        $result = $query->result(); 

        return $result; 
    }

    public function check_allergy($allergy_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($allergy_name))
        {
        $this->db->where('allergy_name',$allergy_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_allergy');
        $result = $query->result(); 

        return $result; 
    }
     public function check_oral_habit($oral_habit_name="",$id="")
    {

        $this->db->select('*');  
        if(!empty($oral_habit_name))
        {
        $this->db->where('oral_habit_name',$oral_habit_name);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_oral_habit');
        $result = $query->result(); 
        //echo $this->db->last_query();
        //die;

        return $result; 
    }
     public function check_treatment($treatment="",$id="")
    {

        $this->db->select('*');  
        if(!empty($treatment))
        {
        $this->db->where('treatment',$treatment);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_treatment');
        $result = $query->result(); 

        return $result; 
    }
    public function check_advice($advice="",$id="")
    {

        $this->db->select('*');  
        if(!empty($advice))
        {
        $this->db->where('advice',$advice);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_advice');
        $result = $query->result(); 

        return $result; 
    }
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
        $query = $this->db->get('hms_dental_diagnosis');
        $result = $query->result(); 

        return $result; 
    }

    public function chief_complaint_list($doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_eye_chief_complaints');
        $result = $query->result(); 
        return $result; 
    }
    // for checking cheif compalin duplicacy master
    
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
        $query = $this->db->get('hms_eye_examination');
        $result = $query->result(); 

        return $result; 
    }

    // for checking examination duplicacy masters

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
        $query = $this->db->get('hms_eye_prv_history');
        $result = $query->result(); 

        return $result; 
    }
    // for checking previous history duplicacy masters



     // for checking diagnosis history duplicacy masters
   /* public function check_diagnosis($diagnosis="",$id="")
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
        $query = $this->db->get('hms_eye_diagnosis');
        $result = $query->result(); 

        return $result; 
    }*/
    // for checking diagnosis history duplicacy masters

    // for checking systemic illness duplicacy masters
    // function name : check_systemic_illness 
    // parameters systemic illness ,id
    public function check_systemic_illness($systemic_illness="",$id="")
    {

        $this->db->select('*');  
        if(!empty($systemic_illness))
        {
        $this->db->where('systemic_illness',$systemic_illness);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_eye_systemic_illness');
        $result = $query->result(); 

        return $result; 
    }
    // for checking systemic illness duplicacy masters

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
        $query = $this->db->get('hms_dental_medicine_dosage');
        $result = $query->result(); 

        return $result; 
    }
    // for checking dosage duplicacy masters

    // for checking personal history duplicacy masters
    // function name : check_personal_history 
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
        $query = $this->db->get('hms_eye_personal_history');
        $result = $query->result(); 

        return $result; 
    }
    // for checking personal history duplicacy masters


    // for checking medicine advice duplicacy masters
    // function name : check_medicine_advice 
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
        $query = $this->db->get('hms_eye_advice');
        $result = $query->result(); 

        return $result; 
    }
    // for checking medicine advice duplicacy masters


    // for check_frequency duplicacy masters
    // function name : check_frequency 
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
        $query = $this->db->get('hms_dental_medicine_dosage_frequency');
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
        $query = $this->db->get('hms_dental_medicine_dosage_duration');
        $result = $query->result(); 

        return $result; 
    }
    // for checking frequency duplicacy masters

     // for iol_section duplicacy masters
    // function name : check_iol_section
    // parameters iolsection ,id
    public function check_iol_section($iol_section="",$id="")
    {

        $this->db->select('*');  
        if(!empty($iol_section))
        {
        $this->db->where('iol_section',$iol_section);
        } 
        if(!empty($id))
        {
        $this->db->where('id!= "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_eye_iol_section');
        $result = $query->result(); 

        return $result; 
    }
    // for checking iol_section duplicacy masters

    // for keratometer duplicacy masters
    // function name : check_keratometer
    // parameters keratometer ,id
    public function check_keratometer($keratometer="",$id="")
    {

        $this->db->select('*');  
        if(!empty($keratometer))
        {
        $this->db->where('keratometer',$keratometer);
        } 
        if(!empty($id))
        {
        $this->db->where('id!= "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_eye_keratometer');
        $result = $query->result(); 

        return $result; 
    }
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
        $query = $this->db->get('hms_eye_suggetion');
        $result = $query->result(); 

        return $result; 
    }
    // for checking suggestion duplicacy masters


/* prescription setting for eye */

    function get_dental_prescription_tab_setting($parent_id="",$order_by='')
    {

            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_dental_branch_prescription_tab_setting.*,hms_dental_prescription_tab_setting.var_title');   
            $this->db->join('hms_dental_branch_prescription_tab_setting','hms_dental_branch_prescription_tab_setting.unique_id = hms_dental_prescription_tab_setting.id');
            $this->db->where('hms_dental_branch_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_dental_branch_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_dental_branch_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_dental_branch_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_dental_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_dental_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_dental_branch_prescription_medicine_tab_setting.*,hms_dental_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_dental_branch_prescription_medicine_tab_setting','hms_dental_branch_prescription_medicine_tab_setting.unique_id = hms_dental_prescription_medicine_tab_setting.id');
            $this->db->where('hms_dental_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_dental_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_dental_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_dental_branch_prescription_medicine_tab_setting.status',1);
            $query = $this->db->get('hms_dental_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    /* prescription setting for eye */


    // for OT Procedure duplicacy master
    public function check_ot_procedure($ot_procedure="",$id="")
    {
        $this->db->select('*');  
        if(!empty($ot_procedure))
        {
        $this->db->where('ot_procedure',$ot_procedure);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_eye_ot_procedure');
        $result = $query->result(); 
        return $result; 
    }
    // for OT Procedure duplicacy master


    // for OT Procedure duplicacy master
    public function check_ot_observations($post_observation="",$id="")
    {
        $this->db->select('*');  
        if(!empty($post_observation))
        {
        $this->db->where('post_observations',$post_observation);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_eye_ot_post_observations');
        $result = $query->result(); 
        return $result; 
    }

     public function branch_user_list($id='')
    {
        $users_data = $this->session->userdata('auth_users');
      $this->db->select('hms_users.id,CONCAT(hms_employees.name,CONCAT(" (",hms_users.username,")")) AS name');
        $this->db->where('hms_users.status','1');
        $this->db->where('hms_users.emp_id>0');  
        $this->db->where('hms_users.users_role',2);
        $this->db->where('hms_users.parent_id',$users_data['parent_id']);
        
        $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id','left');
        $this->db->order_by('hms_employees.name','ASC'); 
        $query = $this->db->get('hms_users');
        $result = $query->result();
        return $result; 
    }
    // for OT Procedure duplicacy master

    //referal_hospital_list
     public function referal_hospital_list($branch_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_hospital.hospital_name','ASC');
        $this->db->where('hms_hospital.is_deleted',0); 
        if(!empty($branch_id))
        {
            $this->db->where('hms_hospital.branch_id',$branch_id);
        }
        else
        {
            $this->db->where('hms_hospital.branch_id',$users_data['parent_id']);
        }
          
        $query = $this->db->get('hms_hospital');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    }
     //referal_hospital_list

    //doctors_list
    public function doctors_list($doctor_id='',$branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');

        $this->db->select('*'); 
        if(!empty($doctor_id))
        {
           $this->db->where('id',$doctor_id);  
        } 
        $this->db->where('status','1'); 
        $this->db->order_by('doctor_name','ASC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id); 
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']);  
        }
        
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    }
    //doctors_list

     //department_list

    public function department_list($module_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        if(!empty($module_id))
        {
            $this->db->where('module',$module_id); 
        }
        $this->db->where('(hms_department.branch_id='.$users_data['parent_id'].' OR hms_department.branch_id=0)');   
        $query = $this->db->get('hms_department');
        $result = $query->result(); 
        return $result; 
    }
     //department_list

    //get_date_time_setting

    public function get_date_time_setting($var_title='',$branch_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch_date_time_setting.date_time_status'); 
        $this->db->where('hms_branch_date_time_setting.setting_name',$var_title);
        if(!empty($branch_id))
        {
            $this->db->where('hms_branch_date_time_setting.branch_id',$branch_id); 
        }
        else
        {
            $this->db->where('hms_branch_date_time_setting.branch_id',$users_data['parent_id']);  
        } 
        $query = $this->db->get('hms_branch_date_time_setting');
        $result = $query->row(); 
        //echo $this->db->last_query(); 
        return $result; 
    } 
    //get_date_time_setting

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



     // parameters cheficomplain name,id
    public function teeth_numbers($number="",$id="")
    {

        $this->db->select('*');  
        if(!empty($number))
        {
        $this->db->where('number',$number);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_teeth_number');
        
        $result = $query->result(); 

        return $result; 
    }

     public function get_cat_name($id="")
    {

        $this->db->select('investigation_sub');   
        if(!empty($id))
        {
       $this->db->where('id',$id);
       //$this->db->where('investigation_cat!=0');
        } 
         //$this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        //$this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dental_investigation');
        //echo $this->db->last_query();die;
        $result = $query->result(); 

        return $result; 
    }
    public function get_investigation_sub_category_name($id="")
    {

        $this->db->select('*');  
        $this->db->where('is_deleted',0);
         $this->db->where('status',1);
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('investigation_cat',$id);
        $query = $this->db->get('hms_dental_investigation'); 
       // echo $this->db->last_query();die;
        $result = $query->result();
        return $result; 
    }
    // for check_medicine_unit duplicacy masters



// Please write code above this
}
