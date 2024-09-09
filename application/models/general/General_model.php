<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General_model extends CI_Model {
  
	public function __construct()
	{
		parent::__construct();  
	}

    public function get_adv_id($paid_date='',$paid_amount='',$ipd_id='')
    {
        $id="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($ipd_id))
        {
            $this->db->select('hms_ipd_patient_to_charge.id'); 
            $this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']); 
            $this->db->where('hms_ipd_patient_to_charge.ipd_id',$ipd_id); 
            $this->db->where('hms_ipd_patient_to_charge.net_price',$paid_amount); 
            $this->db->where('hms_ipd_patient_to_charge.payment_date',date('Y-m-d',strtotime($paid_date)));
            $this->db->where('hms_ipd_patient_to_charge.type','2'); 
            $query = $this->db->get('hms_ipd_patient_to_charge');
            $result = $query->row(); 
            //echo "<pre>"; print_r($result); exit;
            if(!empty($result->id))
            {
                $id = $result->id;    
            }
            
            //echo $this->db->last_query();
        }
        return $id; 
}
    
    public function country_list()
    {
    	$this->db->select('*'); 
		$this->db->where('status','1'); 
		$this->db->order_by('country','ASC'); 
		$query = $this->db->get('hms_countries');
		$result = $query->result(); 
		return $result;
    }

 /* child exits or not in unit */
  function get_checkparent_unit($unit_id="")
  {
       $user_data = $this->session->userdata('auth_users');
        $this->db->select('qty_with_second_unit,unit_id'); 
        if(!empty($unit_id))
        {
        $this->db->where('second_unit',$unit_id); 
        }
        $this->db->where('branch_id',$user_data['parent_id']);  
        $query = $this->db->get('path_item');
        $result = $query->result();
        
        return $result;
  }

  /* child exits or not in unit */

    public function check_room_type($room_type="",$id="")
    {
        $this->db->select('*');  
        if(!empty($room_type))
        {
            $this->db->where('room_category',$room_type);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=',2);
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_room_category');
        $result = $query->result(); 
        return $result; 
    }
    public function check_room_charge_type($room_type_charge="",$id="")
    {
        $this->db->select('*');  
        if(!empty($room_type_charge))
        {
        $this->db->where('charge_type',$room_type_charge);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=',2);
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_room_charge_type');
        $result = $query->result(); 
        // print_r($result);
        //die;
        return $result; 
    }
    public function room_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        $this->db->where('status',1);
        $query = $this->db->get('hms_ipd_room_category');
        $result = $query->result();
        return $result;
    }
    public function room_charge_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        $this->db->where('status',1);
        $query = $this->db->get('hms_ipd_room_charge_type');

        $result = $query->result_array();
        return $result;
    }
    public function check_room_no($room_no="",$room_category_id="",$id="")
    {
        $room_id = 'r_'.$room_no;
        $this->db->select('*');  
        if(!empty($room_no))
        {
            $this->db->where('room_no',$room_id);
        } 
        if(!empty($room_category_id))
        {
            $this->db->where('room_type_id',$room_category_id);
        } 

        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_rooms');
        $result = $query->result(); 
        return $result; 
    }
    public function check_panel_company($panel_company="",$id="")
    {
        $this->db->select('*');  
        if(!empty($panel_company))
        {
        $this->db->where('panel_company',$panel_company);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_panel_company');
        $result = $query->result(); 
        return $result; 
    }

    public function check_panel_type($panel_type="",$id="")
    {
        $this->db->select('*');  
        if(!empty($panel_type))
        {
            $this->db->where('panel_type',$panel_type);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_panel_type');
        $result = $query->result(); 
        return $result; 
    }

    public function get_religion_name($id='')
    {
        $religion_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($id))
        {
            $this->db->select('hms_religion.religion as religion_name');  
            $this->db->where('hms_religion.id',$id); 
            $query = $this->db->get('hms_religion');
            $result = $query->row(); 
            $religion_name = $result->religion_name;
            //echo $this->db->last_query();die;
        }    
            return $religion_name; 
      
    }

    public function get_particular_list($particular_ids =array())
    {
        if(!empty($particular_ids))
        { 
            $id_list = [];
            foreach($particular_ids as $id)
            {
                if(!empty($id) && $id>0)
                {
                  $id_list[]  = $id;
                } 
            }
            $particular_id = implode(',', $id_list);

            $this->db->select('*');
            $this->db->where('id IN ('.$particular_id.')');
            $query = $this->db->get('hms_ipd_perticular'); 
            $result = $query->result();
            return $result; 
        }
    }
    public function check_ipd_package($packagename="",$id="")
    {
        $this->db->select('*');  
        if(!empty($packagename))
        {
          $this->db->where('name',$packagename);
        } 
        if(!empty($id))
        {
          $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_packages');
        $result = $query->result(); 
        return $result; 
    }
    public function check_ot_package($packagename="",$id="")
    {
        $this->db->select('*');  
        if(!empty($packagename))
        {
        $this->db->where('name',$packagename);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted!=2');
        $query = $this->db->get('hms_ot_pacakge');
        $result = $query->result(); 
        return $result; 
    }

    public function check_dialysis_package($packagename="",$id="")
    {
        $this->db->select('*');  
        if(!empty($packagename))
        {
        $this->db->where('name',$packagename);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 

        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
         $this->db->where('is_deleted!=2');
        $query = $this->db->get('hms_dialysis_pacakge');
        $result = $query->result(); 
        return $result; 
    }

    public function room_no_list($room_id)
    {
        $this->db->select('*'); 
        $users_data = $this->session->userdata('auth_users');
        if(!empty($room_id))
        {
        $this->db->where('room_type_id',$room_id); 
        }
        //$this->db->where('status','1'); 
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->order_by('id','ASC'); 
        $query = $this->db->get('hms_ipd_rooms');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    public function number_bed_list($room_id,$room_no_id,$ipd_id='')
    {
        $this->db->select('hms_ipd_room_to_bad.*,hms_ipd_booking.is_deleted as ipd_is_deleted'); 
        
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_room_to_bad.ipd_id','left');
        if(!empty($room_id))
        {
            $this->db->where('hms_ipd_room_to_bad.room_type_id',$room_id); 
            $this->db->where('hms_ipd_room_to_bad.room_id',$room_no_id); 
        }
        //$this->db->where('status','1'); 
        if(empty($ipd_id))
        {
            //$this->db->where('hms_ipd_room_to_bad.status','0');     
        }
        else
        {
            //$where = "(`hms_ipd_room_to_bad.ipd_id` = $ipd_id OR `hms_ipd_room_to_bad.status` = '0')";
           // $this->db->where($where); 
        }
        
        // / AND hms_ipd_booking.is_deleted!=2
        $this->db->order_by('hms_ipd_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_ipd_room_to_bad');
        
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }

     public function number_bed_all_list($room_id,$room_no_id,$ipd_id='')
    {
        $this->db->select('hms_ipd_room_to_bad.*,hms_ipd_booking.is_deleted as ipd_is_deleted'); 
        if(!empty($room_id))
        {
            $this->db->where('hms_ipd_room_to_bad.room_type_id',$room_id); 
            $this->db->where('hms_ipd_room_to_bad.room_id',$room_no_id); 
        }
        //$this->db->where('status','1'); 
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_room_to_bad.ipd_id','left');
        $this->db->order_by('hms_ipd_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_ipd_room_to_bad');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result;
    }
    
    public function all_filter_number_bed_all_list($room_id,$room_no_id,$status='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_room_to_bad.*'); 
        if(!empty($room_id))
        {
            $this->db->where('hms_ipd_room_to_bad.room_type_id',$room_id); 
            $this->db->where('hms_ipd_room_to_bad.room_id',$room_no_id); 
        }
        
        if(!empty($status) && $status==1)
        {
            $this->db->where('hms_ipd_room_to_bad.status',1);
        }
        if(!empty($status) && $status==2)
        {
            $this->db->where('hms_ipd_room_to_bad.status',0);
        }
        $this->db->where('hms_ipd_room_to_bad.branch_id',$users_data['parent_id']); 
        $this->db->order_by('hms_ipd_room_to_bad.id','ASC'); 

        $query = $this->db->get('hms_ipd_room_to_bad');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result;
    }

    

    public function total_number_bed_list_status($room_id,$room_no_id,$status='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_room_to_bad.*'); 
        if(!empty($room_id))
        {
            $this->db->where('hms_ipd_room_to_bad.room_type_id',$room_id); 
        }

        if(!empty($room_no_id))
        {
            $this->db->where('hms_ipd_room_to_bad.room_id',$room_no_id);
        }

        if(!empty($status) && $status==1)
        {
            $this->db->where('hms_ipd_room_to_bad.status',1);
        }
        if(!empty($status) && $status==2)
        {
            $this->db->where('hms_ipd_room_to_bad.status',0);
        }
        $this->db->where('hms_ipd_room_to_bad.branch_id',$users_data['parent_id']);
        $this->db->order_by('hms_ipd_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_ipd_room_to_bad');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result;
    }
    public function panel_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        $query = $this->db->get('hms_insurance_type');

        $result = $query->result();
        return $result;
    }
    public function panel_company_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        $query = $this->db->get('hms_insurance_company');
        $result = $query->result();
        return $result;
    }

    public function panel_company_details($id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        if(!empty($id))
            $this->db->where('id',$id);
        $query = $this->db->get('hms_insurance_company');
        $result = $query->result();
        return $result;
    }
    public function ipd_package_list($package_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        if(!empty($package_id))
        {
        $this->db->where('id',$package_id);  
        } 
        $this->db->order_by('name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_ipd_packages');
        $result = $query->result(); 
        return $result; 
    }

     //function to find gender according to selected simulation
    public function find_gender($id='')
    {
        $this->db->select('gender');
        if(!empty($id))
        {
          $this->db->where('id',$id);  
        } 
        $query = $this->db->get('hms_simulation');
        $result = $query->row_array(); 
        return $result;
    }
    public function state_list($country_id="")
    {
    	$this->db->select('*'); 
    	if(!empty($country_id))
    	{
    		$this->db->where('country_id',$country_id); 
    	}
		$this->db->where('status','1'); 
		$this->db->order_by('state','ASC'); 
		$query = $this->db->get('hms_state');
		$result = $query->result(); 
		return $result;
    }
      public function find_employee_salary($emp_id=""){
        if(!empty($emp_id)){
            $this->db->select('salary');
            $this->db->where('id',$emp_id);
            $query = $this->db->get('hms_employees');
            $result = $query->result(); 
            return $result; 
        }
    }

     public function get_total_adavance_payment($emp_id="",$emp_type="",$first_date="",$last_date="")
     {
        if(!empty($emp_id)){
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_expenses.*,sum(`hms_expenses`.employee_pay_now) as tot_payment');
            $this->db->where('employee_id',$emp_id);
            $this->db->where('emp_type_id',$emp_type);
            $this->db->where('expenses_date>=',date('Y-m-d',strtotime($first_date)));
            $this->db->where('expenses_date<=',date('Y-m-d',strtotime($last_date)));
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('type',1);
            $query = $this->db->get('hms_expenses');
            $result = $query->result();
            
            return $result; 
        }
    }
  public function get_sub_branch_details($branch_ids=array()){

        if(!empty($branch_ids)){ 
            $id_list = [];
            foreach($branch_ids as $sub_branch_ids){
                foreach ($sub_branch_ids as $id) {
                    if(!empty($id) && $id>0){
                        $id_list[]  = $id;
                    }
                } 
            }
            $branch_ids = implode(',', $id_list);
            $this->db->select('id,branch_name');
            $this->db->where('is_deleted','0');
            $this->db->where('id IN ('.$branch_ids.')');
            $query = $this->db->get('hms_branch');
            $result = $query->result();
            return $result; 
        }
    }

     public function get_branch_according_ids($branch_ids=''){
            $this->db->select('id,branch_name');
            if(!empty($branch_ids)){ 
            $this->db->where('id',$branch_ids);
            }
            $query = $this->db->get('hms_branch');
            $result = $query->result();
            return $result; 
        }
    
    public function city_list($state_id="")
    {
    	$this->db->select('*'); 
    	if(!empty($state_id))
    	{
    		$this->db->where('state_id',$state_id); 
    	}
		$this->db->where('status','1'); 
		$this->db->order_by('city','ASC'); 
		$query = $this->db->get('hms_cities');
		$result = $query->result(); 
		return $result;
    }
    
    public function rate_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('title','ASC');
        $this->db->where('is_deleted',0); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('path_rate_plan');
        $result = $query->result(); 
        return $result; 
    }

    public function get_form_name()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(hms_sms_setting_name.var_title),hms_sms_setting_name.id');
        //$this->db->join('hms_sms_branch_template','hms_sms_branch_template.form_name=hms_sms_setting_name.id','outer left');
        //$this->db->where('hms_sms_branch_template.branch_id',$users_data['parent_id']);
        $this->db->where('hms_sms_setting_name.id NOT IN(select form_name from hms_sms_branch_template where branch_id='.$users_data['parent_id'].')');
        //$this->db->where('');
        $query = $this->db->get('hms_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
    public function get_form_name_edit()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(hms_sms_setting_name.var_title),hms_sms_setting_name.id');
        $query = $this->db->get('hms_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
    public function get_email_form_name()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(hms_sms_setting_name.var_title),hms_sms_setting_name.id');
        $this->db->where('hms_sms_setting_name.id NOT IN(select form_name from hms_email_branch_template where branch_id='.$users_data['parent_id'].')');
        $query = $this->db->get('hms_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
    public function get_email_form_name_edit()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(hms_sms_setting_name.var_title),hms_sms_setting_name.id');
        $query = $this->db->get('hms_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
    
    /* get printer type */
    public function get_printer_type()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('parent_id',0);  
        $query = $this->db->get('hms_printer');
        $result = $query->result(); 
        return $result; 
    }
    /* get printer type */
    /* get printer paper type */
    public function get_printer_paper_type($parent_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('parent_id',$parent_id);  
        $query = $this->db->get('hms_printer');
        $result = $query->result(); 
        return $result; 
    }
    /* get printer type */
    public function employee_list($id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('name','ASC');
        $this->db->where('is_deleted',0); 
        if(!empty($id))
        {
            $this->db->where('id',$id);
        }
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_employees');
        $result = $query->result(); 
        return $result; 
    } 

    public function specialization_list($branch_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
       /* if(!empty($branch_id))
        {
             $this->db->where(' ( branch_id='.$branch_id); 
        }
        else
        {
             $this->db->where(' ( branch_id='.$users_data['parent_id']);   
        }*/
          $this->db->where('hms_specialization.is_deleted','0');
         if(!empty($branch_id))
        {
             $this->db->where('branch_id='.$branch_id); 
        }
        else
        {
             $this->db->where('branch_id='.$users_data['parent_id']);   
        }

        if( in_array('248',$users_data['permission']['section']) || in_array('224',$users_data['permission']['section']) || in_array('225',$users_data['permission']['section']) || in_array('226',$users_data['permission']['section']) ||
            in_array('227',$users_data['permission']['section']) || in_array('228',$users_data['permission']['section']) || in_array('229',$users_data['permission']['section']) || in_array('230',$users_data['permission']['section']) || in_array('231',$users_data['permission']['section']) || in_array('232',$users_data['permission']['section']) || in_array('233',$users_data['permission']['section']) || in_array('234',$users_data['permission']['section']) || in_array('235',$users_data['permission']['section']) || in_array('236',$users_data['permission']['section']) || in_array('237',$users_data['permission']['section']) || in_array('238',$users_data['permission']['section']) || in_array('239',$users_data['permission']['section']) || in_array('240',$users_data['permission']['section']) || in_array('241',$users_data['permission']['section']) || in_array('242',$users_data['permission']['section']) || in_array('243',$users_data['permission']['section']) || in_array('244',$users_data['permission']['section']) || in_array('245',$users_data['permission']['section']) || in_array('246',$users_data['permission']['section']) || in_array('247',$users_data['permission']['section']) )
        {
            //$this->db->or_Where(' branch_id in (0) and default_value=1 )');
           /// $this->db->where('status','1'); 
            $this->db->or_Where(' branch_id in (0) and default_value=1');
            //$this->db->where('status','1'); 
        }
         /* pedic specialization */

         if(in_array('271',$users_data['permission']['section'])|| in_array('272',$users_data['permission']['section'])|| in_array('273',$users_data['permission']['section']) || in_array('274',$users_data['permission']['section']) || in_array('275',$users_data['permission']['section']) || in_array('276',$users_data['permission']['section']))
        {
            $this->db->or_Where('hms_specialization.branch_id in (0) and default_value=2');
        }
        /* pedic specialization */
        /* dental specialization */

         if(in_array('277',$users_data['permission']['section'])|| in_array('278',$users_data['permission']['section'])|| in_array('279',$users_data['permission']['section']) || in_array('280',$users_data['permission']['section']) || in_array('281',$users_data['permission']['section']) || in_array('282',$users_data['permission']['section'])||in_array('277',$users_data['permission']['section'])|| in_array('283',$users_data['permission']['section'])|| in_array('284',$users_data['permission']['section']) || in_array('285',$users_data['permission']['section']) || in_array('286',$users_data['permission']['section']) || in_array('287',$users_data['permission']['section']) || in_array('288',$users_data['permission']['section'])|| in_array('289',$users_data['permission']['section'])|| in_array('290',$users_data['permission']['section'])|| in_array('291',$users_data['permission']['section'])|| in_array('292',$users_data['permission']['section'])|| in_array('293',$users_data['permission']['section'])|| in_array('294',$users_data['permission']['section'])|| in_array('295',$users_data['permission']['section']))
        {
            $this->db->or_Where('hms_specialization.branch_id in (0) and default_value=3');
        }
        /* dental specialization */
       
            /* gyne*/
         if( in_array('299',$users_data['permission']['section']) || in_array('300',$users_data['permission']['section']) || in_array('301',$users_data['permission']['section']) 
         ||in_array('302',$users_data['permission']['section']) || in_array('303',$users_data['permission']['section']) || in_array('304',$users_data['permission']['section']) 
         || in_array('305',$users_data['permission']['section']) || in_array('306',$users_data['permission']['section']) || in_array('307',$users_data['permission']['section']) 
         || in_array('308',$users_data['permission']['section']) || in_array('309',$users_data['permission']['section']) || in_array('310',$users_data['permission']['section']) 
         || in_array('311',$users_data['permission']['section']) || in_array('312',$users_data['permission']['section']) || in_array('313',$users_data['permission']['section']) 
         || in_array('314',$users_data['permission']['section']) || in_array('315',$users_data['permission']['section']) || in_array('316',$users_data['permission']['section']) 
         || in_array('317',$users_data['permission']['section']) )
        {
            $this->db->or_Where(' branch_id in (0) and default_value=4');
           // $this->db->where('status','1'); 
        }
        /* end gyne*/ 
        
        
       // $this->db->where('is_deleted',0);
        $this->db->where('status=1'); 
        $this->db->order_by('specialization','ASC');
        $query = $this->db->get('hms_specialization');
        $result = $query->result(); 
       // echo $this->db->last_query(); exit;
        return $result; 
    }

    public function get_rate_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('title','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_rate_plan');
        $result = $query->result(); 
        return $result; 
    }

    public function get_days_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->order_by('id','ASC');
        $query = $this->db->get('hms_days');
        $result = $query->result(); 
        return $result; 
    }

    
    public function total_vendor($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_vendors.*'); 
        $this->db->where('hms_medicine_vendors.is_deleted !=2'); 
        $this->db->where('hms_medicine_vendors.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_medicine_vendors');
        $result = $query->num_rows(); 
        return $result; 
    }
    public function total_purchase($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_purchase.*'); 
        //$this->db->where('hms_medicine_purchase.branch_id',$parent_id); 
        $this->db->where('hms_medicine_purchase.is_deleted !=2');
        $this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_medicine_purchase.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "13" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_medicine_purchase');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function total_sales($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_sale.*'); 
        $this->db->where('hms_medicine_sale.is_deleted !=2');
        //$this->db->where('hms_medicine_sale.branch_id',$parent_id); 
        $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_medicine_sale.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "16" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_medicine_sale');
        $result = $query->num_rows(); 
        return $result; 
    }
    public function total_sales_return($parent_id="",$prefix='')
        {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_medicine_sale_return.*'); 
            $this->db->where('hms_medicine_sale_return.is_deleted !=2');
            //$this->db->where('hms_medicine_sale_return.branch_id',$parent_id); 
            $this->db->where('hms_medicine_sale_return.branch_id',$users_data['parent_id']);
            if(!empty($prefix))
            {
              $this->db->where('hms_medicine_sale_return.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "17" AND  branch_id = "'.$parent_id.'")'); 
            }
            $query = $this->db->get('hms_medicine_sale_return');
            $result = $query->num_rows(); 
            return $result; 
        }
    public function total_opd_billing($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_opd_billing.*'); 
        $this->db->where('hms_opd_billing.is_deleted !=2');
        $this->db->where('hms_opd_billing.type',3);
        //$this->db->where('hms_opd_billing.branch_id',$parent_id); 
        $this->db->where('hms_opd_billing.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_opd_billing.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "18" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_opd_billing');
        $result = $query->num_rows(); 
        return $result; 
    }    
    
    public function total_purchase_return($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_return.*'); 
        $this->db->where('hms_medicine_return.is_deleted !=2');
        //$this->db->where('hms_medicine_return.branch_id',$parent_id); 
        $this->db->where('hms_medicine_return.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_medicine_return.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "14" AND  branch_id = "'.$parent_id.'")'); 
        } 
        $query = $this->db->get('hms_medicine_return');
        $result = $query->num_rows(); 
        return $result; 
    }
   
   

    public function get_doctor_rate($doctor_pay_type="",$rate_plan_id="",$doctor_id='')
    {
        
        $users_data = $this->session->userdata('auth_users');
        if($doctor_pay_type==1)
        {
            //commision
            $this->db->select('hms_doctors_to_comission.rate as doc_rate');   
            $this->db->where('hms_doctors_to_comission.doctor_id',$doctor_id);
            $query = $this->db->get('hms_doctors_to_comission');
            $result = $query->row(); 
            //echo $this->db->last_query();die;
            return $result;

        }
        elseif($doctor_pay_type==2)
        {
            $this->db->select('path_rate_plan.master_rate as doc_rate');  
            $this->db->where('path_rate_plan.status','1'); 
            $this->db->where('path_rate_plan.id',$rate_plan_id); 
            $this->db->where('path_rate_plan.is_deleted',0);
            $query = $this->db->get('path_rate_plan');
            $result = $query->row(); 
            //echo $this->db->last_query();die;
            return $result; 
        }
        
    }

    public function get_consultant_charge($doctor_id='')
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('consultant_charge');   
        $this->db->where('hms_doctors.id',$doctor_id);
        $query = $this->db->get('hms_doctors');
        $result = $query->row(); 
        //echo $this->db->last_query();die;
        return $result;
    }

    public function simulation_list($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('sort_order','ASC'); 
        $this->db->order_by('simulation','ASC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id);
        }
        else
        {
           $this->db->where('branch_id',$users_data['parent_id']); 
        }
         
        $query = $this->db->get('hms_simulation');
        $result = $query->result(); 
        return $result; 
    }
    public function payment_mode($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('sort_order','ASC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
        $this->db->where('branch_id',$branch_id);
        }
        else
        {
        $this->db->where('branch_id',$users_data['parent_id']); 
        }

        $query = $this->db->get('hms_payment_mode');
        $result = $query->result(); 
        return $result; 
    }

    public function vitals_list($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('sort_order','ASC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
        $this->db->where('branch_id',$branch_id);
        }
        else
        {
        $this->db->where('branch_id',$users_data['parent_id']); 
        }

        $query = $this->db->get('hms_opd_vitals');
        $result = $query->result(); 
        return $result; 
    }

    
    public function payment_mode_detail($payment_mode_id="",$branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        if(!empty($branch_id))
        {
        $this->db->where('branch_id',$branch_id);
        $this->db->where('p_mode_id',$payment_mode_id);
        }
        else
        {
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('p_mode_id',$payment_mode_id);
        }

        $query = $this->db->get('hms_payment_mode_to_field');
        $result = $query->result(); 
       // print_r($result);die;
        return $result; 
    }

    public function payment_mode_by_id($branch_id='',$id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('sort_order','ASC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
        $this->db->where('branch_id',$branch_id);
        $this->db->where('id',$id);
        }
        else
        {
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('id',$id);
        }

        $query = $this->db->get('hms_payment_mode');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

  public function vendor_list()
    {
       $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('name','ASC'); 
        $query = $this->db->get('hms_medicine_vendors');
        return $query->result();
    }
    public function package_list($package_id='',$branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_packages.*, (sum(hms_medicine_kit_stock.debit)-sum(hms_medicine_kit_stock.credit)) as total_kit_qty'); 
        $this->db->where('hms_packages.status','1'); 
        
        $this->db->join('hms_medicine_kit_stock','hms_packages.id=hms_medicine_kit_stock.kit_id');
        $this->db->order_by('hms_packages.title','ASC');
        $this->db->where('hms_packages.is_deleted',0);
        
        
        if(!empty($package_id))
        {
            $this->db->where('hms_packages.id',$package_id);  
        } 
        elseif(!empty($branch_id))
        {   
            $this->db->where('hms_medicine_kit_stock.branch_id',$branch_id); 
            $this->db->or_where('(hms_packages.id IN (select package_id from hms_branch_to_package  where branch_to_id = '.$branch_id.'))'); 
        }
        else
        {
            $this->db->where('hms_medicine_kit_stock.branch_id',$users_data['parent_id']); 
            $this->db->or_where('(hms_packages.id IN (select package_id from hms_branch_to_package  where branch_to_id = '.$users_data['parent_id'].'))');
        }
        $this->db->group_by('hms_medicine_kit_stock.kit_id');
        $query = $this->db->get('hms_packages');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function particulars_list($particular_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        if(!empty($particular_id))
        {
            $this->db->where('id',$particular_id); 
        } 
        $this->db->order_by('particular','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_opd_particular');
        $result = $query->result();
        return $result; 
    }

    public function ipd_particulars_list($particular_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        if(!empty($particular_id))
        {
            $this->db->where('id',$particular_id); 
        } 
        $this->db->order_by('particular','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_ipd_perticular');
        $result = $query->result();
        return $result; 
    }

    

    public function religion_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('religion','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_religion');
        $result = $query->result(); 
        return $result; 
    }

    public function relation_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('relation','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_relation');
        $result = $query->result(); 
        return $result; 
    }


    public function gardian_relation_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
       // $this->db->where('status','1'); 
        $this->db->order_by('id','ASC');
        //$this->db->where('is_deleted',0);
        //$this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_gardian_relation');
        $result = $query->result(); 
        //print '<pre>'; print_r($result);die;
        return $result; 
    }

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

    public function get_package_data($branch_id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_packages.*, (sum(hms_medicine_kit_stock.debit)-sum(hms_medicine_kit_stock.credit)) as total_kit_qty'); 
        $this->db->where('hms_packages.status','1'); 
        
        $this->db->join('hms_medicine_kit_stock','hms_packages.id=hms_medicine_kit_stock.kit_id');
        $this->db->order_by('hms_packages.title','ASC');
        $this->db->where('hms_packages.is_deleted',0);
        
        
        if(!empty($package_id))
        {
            $this->db->where('hms_packages.id',$package_id);  
        } 
        elseif(!empty($branch_id))
        {   
            $this->db->where('hms_medicine_kit_stock.branch_id',$branch_id); 
            $this->db->or_where('(hms_packages.id IN (select package_id from hms_branch_to_package  where branch_to_id = '.$branch_id.'))'); 
        }
        else
        {
            $this->db->where('hms_medicine_kit_stock.branch_id',$users_data['parent_id']); 
            $this->db->or_where('(hms_packages.id IN (select package_id from hms_branch_to_package  where branch_to_id = '.$users_data['parent_id'].'))');
        }
        $this->db->group_by('hms_medicine_kit_stock.kit_id');
        $query = $this->db->get('hms_packages');
        $package_list = $query->result(); 
        
        $drop = '<select name="package_id"  id="package_id" onchange="return kit_charge(this.value);">
                 <option value="">Select</option>';
        if(!empty($package_list))
        {
            foreach($package_list as $packages)
            {
                if($packages->total_kit_qty>0)
                {
                $drop .= '<option value="'.$packages->id.'">'.$packages->title.'</option>';
                }
            }
        }
        $drop .= '</select>';
        return $json_data = $drop;
    }

    public function insurance_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('insurance_type','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_insurance_type');
        $result = $query->result(); 
        return $result; 
    }

    public function insurance_company_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('insurance_company','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_insurance_company');
        $result = $query->result(); 
        return $result; 
    }

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

    public function active_department_list($module_id="",$branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        //$this->db->select('*');
        $this->db->select("hms_department.id,hms_department.branch_id,hms_department.module,hms_department.department,hms_department.ip_address,hms_department.is_deleted,hms_department.deleted_by,hms_department.deleted_date,hms_department.created_by,hms_department.modified_by,hms_department.modified_date,hms_department.created_date, (CASE WHEN hms_department.branch_id=0 THEN 1 ELSE hms_department_to_department_status.status END) status");
        //(CASE WHEN hms_department.branch_id=0 THEN hms_department.status ELSE hms_department_to_department_status.status END) as status 
        $this->db->where('hms_department.is_deleted',0);
        $this->db->from('hms_department'); 
         $this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id AND hms_department_to_department_status.branch_id="'.$users_data['parent_id'].'"','left');

        if(!empty($module_id))
        {
           $this->db->where('hms_department.module',$module_id); 
        }
        $this->db->where('(hms_department.branch_id='.$users_data['parent_id'].' OR hms_department.branch_id=0)'); 
        $this->db->where('hms_department_to_department_status.status','1');   
        $query = $this->db->get();
        $result = $query->result(); 
        return $result; 
    }

    //Chief complaints branch_id
    public function chief_complaint_list($doctor_id="", $type = "")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        if(!empty($type)) {
            $this->db->where('hms_opd_chief_complaints.chief_complaints LIKE "'.$type.'%"');
        }
        $query = $this->db->get('hms_opd_chief_complaints');
        $result = $query->result(); 
        return $result; 
    }

    

    public function check_username($username="",$id="")
    {
        $this->db->select('*');  
        if(!empty($username))
        {
        	$this->db->where('username',$username);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $query = $this->db->get('hms_users');
        $result = $query->result(); 
        return $result; 
    } 

    public function check_company($company="",$id="")
    {
        $this->db->select('*');  
        if(!empty($company))
        {
            $this->db->where('company_name',$company);
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
       public function check_vaccination_company($company="",$id="")
    {
        $this->db->select('*');  
        if(!empty($company))
        {
            $this->db->where('company_name',$company);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_vaccination_company');
        $result = $query->result(); 
        return $result; 
    }

    public function check_stock_unit($stock_unit="",$id="")
    {
        $this->db->select('*');  
        if(!empty($stock_unit))
        {
        $this->db->where('unit',$stock_unit);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_stock_item_unit');
        $result = $query->result(); 
        return $result; 
    }
    public function check_ot_room_no($ot_room_no="",$id="")
    {
        $this->db->select('*');  
        if(!empty($ot_room_no))
        {
        $this->db->where('room_no',$ot_room_no);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ot_room');
        $result = $query->result(); 
        return $result; 
    }

    public function check_dialysis_room_no($dialysis_room_no="",$id="")
    {
        $this->db->select('*');  
        if(!empty($dialysis_room_no))
        {
        $this->db->where('room_no',$dialysis_room_no);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dialysis_room');
        $result = $query->result(); 
        return $result; 
    }
    public function check_operation_type($operation_type="",$id="")
    {
        $this->db->select('*');  
        if(!empty($operation_type))
        {
        $this->db->where('operation_type',$operation_type);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
         $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_operation_type');
        $result = $query->result(); 
        return $result; 
    }

    public function check_diabetes_vitals($title="",$id="")
    {
        $this->db->select('*');  
        if(!empty($title))
        {
        $this->db->where('title',$title);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_diabetes_vitals');
        $result = $query->result(); 
        return $result; 
    }

    public function check_diabetes_personal_habit($title="",$id="")
    {
        $this->db->select('*');  
        if(!empty($title))
        {
        $this->db->where('title',$title);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_diabetes_personal_habits');
        $result = $query->result(); 
        return $result; 
    }

  public function check_dialysis_type($dialysis_type="",$id="")
    {
        $this->db->select('*');  
        if(!empty($dialysis_type))
        {
        $this->db->where('dialysis_type',$dialysis_type);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dialysis_type');
        $result = $query->result(); 
        return $result; 
    }
    public function check_operation_name($operation_name="",$id="")
    {
        $this->db->select('*');  
        if(!empty($operation_name))
        {
            $this->db->where('name',$operation_name);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ot_management');
        $result = $query->result(); 
        return $result; 
    }

    public function check_dialysis_name($operation_name="",$id="")
    {
        $this->db->select('*');  
        if(!empty($operation_name))
        {
            $this->db->where('name',$operation_name);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dialysis_management');
        $result = $query->result(); 
        return $result; 
    }


    public function check_item_category($item_category="",$id="")
    {
        $this->db->select('*');  
        if(!empty($item_category))
        {
        $this->db->where('category',$item_category);
        } 
        if(!empty($id))
        {
        $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('path_stock_category');
        $result = $query->result(); 
        return $result; 
    }

     public function check_stockitem_name($item_name="",$category_id="")
    {
        $this->db->select('*');  
        if(!empty($item_name))
        {
        $this->db->where('item',$item_name);

        } 
        if(!empty($category_id))
        {
        $this->db->where('category_id',$category_id);
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('path_item');
        $result = $query->result(); 
        return $result; 
    }
    public function get_medicine_company($medicine_comp_id="",$branch_id=""){
        $result =array();
        if(!empty($medicine_comp_id) && !empty($branch_id)){
            $this->db->select('company_name');
            $this->db->where('id',$medicine_comp_id);
            $this->db->where('branch_id',$branch_id);
            $query = $this->db->get('hms_medicine_company');
            $result = $query->result_array();

        }
        return $result;
    }



    

    public function check_rackno($rackno="",$id="")
    {
        $this->db->select('*');  
        if(!empty($rackno))
        {
            $this->db->where('rack_no',$rackno);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_medicine_racks');
        $result = $query->result(); 
        return $result; 
    }
    public function check_vaccinationrackno($rackno="",$id="")
    {
        $this->db->select('*');  
        if(!empty($rackno))
        {
            $this->db->where('rack_no',$rackno);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_vaccination_racks');
        $result = $query->result(); 
        return $result; 
    }

    public function check_package($packagename="",$id="")
    {
        $this->db->select('*');  
        if(!empty($packagename))
        {
            $this->db->where('title',$packagename);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_packages');
        $result = $query->result(); 
        return $result; 
    }

    public function check_email($email="",$id="")
    {
        $this->db->select('*');  
        if(!empty($email))
        {
        	$this->db->where('email',$email);
        }
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        }  
        $this->db->where('is_deleted!=2');
        $query = $this->db->get('hms_users');
        $result = $query->result(); 
        return $result; 
    } 

    function get_branch_format($type="",$parent_id="")
    {
        if(!empty($type))
        {
            $this->db->select('hms_branch_unique_ids.*');   
            $this->db->join('hms_branch_unique_ids','hms_branch_unique_ids.unique_id = hms_unique_ids.id',$type);
            $this->db->where('hms_unique_ids.id',$type);
            $this->db->where('hms_branch_unique_ids.branch_id',$parent_id);
            $query = $this->db->get('hms_unique_ids');
            $result = $query->row(); 
            //echo $this->db->last_query();die;
            return $result; 
        }        
    }

   /* public function total_branch($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch.*'); 
        $this->db->where('hms_branch.parent_id',$parent_id);  
        $query = $this->db->get('hms_branch');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();die;
        return $result; 
    }*/
     public function total_ipd_booking($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_booking.*'); 
        $this->db->where('branch_id',$parent_id);
        $this->db->where('is_deleted !=2'); 
        if(!empty($prefix))
        { 
           $this->db->where('hms_ipd_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "22" AND  branch_id = "'.$parent_id.'" )');  
        } 
        $query = $this->db->get('hms_ipd_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();die;
        return $result; 
    }

    public function total_ipd_booking_discharged($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_booking.*'); 
        $this->db->where('branch_id',$parent_id);
        $this->db->where('discharge_status',1); 
        $this->db->where('is_deleted !=2'); 
        if(!empty($prefix))
        { 
           $this->db->where('hms_ipd_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "24" AND  branch_id = "'.$parent_id.'" )');  
        }  
        $query = $this->db->get('hms_ipd_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();die;
        return $result; 
    }


    /*public function total_employee($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_employees.*'); 
        //$this->db->where('hms_employees.branch_id',$parent_id); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_employees');
        $result = $query->num_rows(); 
        return $result; 
    }*/

    /*public function total_doctors($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_doctors.*'); 
        //$this->db->where('hms_doctors.branch_id',$parent_id); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_doctors');
        $result = $query->num_rows(); 
        return $result; 
    }*/

    /*public function total_patient($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_patient.*'); 
        $this->db->where('hms_patient.branch_id',$parent_id); 
        //$this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_patient');
        $result = $query->num_rows(); 
        return $result; 
    }*/

    /*public function total_test($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_test.*'); 
        //$this->db->where('hms_test.branch_id',$parent_id); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_test');
        $result = $query->num_rows(); 
        return $result; 
    }*/

    public function user_role_list()
    {
        $this->db->select('hms_users_role.*');  
        $this->db->where('id != "1"');
        $query = $this->db->get('hms_users_role');
        $result = $query->result(); 
        return $result; 
    }

    public function get_permission_attr($section_id="",$action_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('attribute_val');  
        if(!empty($section_id))
        {
            $this->db->where('section_id',$section_id);
        }
        if(!empty($action_id))
        {
            $this->db->where('action_id',$action_id);
        }
        $this->db->where('users_id',$users_data['id']);
        $query = $this->db->get('hms_permission_to_users'); 
        $result = $query->row_array(); 
        return $result; 
    }

    public function total_booking($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_opd_booking.*'); 
        $this->db->where('hms_opd_booking.branch_id',$parent_id); 
        //$this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted !=2');
        $this->db->where('type',2);
        if(!empty($prefix))
        {
          $this->db->where('hms_opd_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "9" AND  branch_id = "'.$parent_id.'")');
        }
        $query = $this->db->get('hms_opd_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 

        
    }
    
    public function total_crm_lead($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('crm_leads.*'); 
        $this->db->where('crm_leads.branch_id',$parent_id); 
        //$this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('crm_leads.is_deleted=0');
        //$this->db->where('type',2);
        if(!empty($prefix))
        {
          $this->db->where('crm_leads.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "72" AND  branch_id = "'.$parent_id.'")');
        }
        $query = $this->db->get('crm_leads');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 

        
    }

    public function total_billing($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_opd_booking.*'); 
        //$this->db->where('hms_opd_booking.branch_id',$parent_id); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('type',3);
        $this->db->where('is_deleted!=2');
        if(!empty($prefix))
        { 
           $this->db->where('hms_opd_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "21" AND  branch_id = "'.$parent_id.'" )');  
        }
        $query = $this->db->get('hms_opd_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 

        
    }




    public function total_appointment($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_opd_booking.*'); 
        $this->db->where('hms_opd_booking.branch_id',$parent_id); 
        //$this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('type',1);
        $this->db->where('is_deleted !=2');
        if(!empty($prefix))
        { 
           $this->db->where('hms_opd_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "20" AND  branch_id = "'.$parent_id.'" )');  
        }
        $query = $this->db->get('hms_opd_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 

        
    }

    

    public function total_medicine_entry($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_entry.*'); 
        //$this->db->where('hms_medicine_entry.branch_id',$parent_id); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted !=2');
        $query = $this->db->get('hms_medicine_entry');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 

        
    }

    public function get_doctor_name($doctor_id='')
    {
        $doctor_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($doctor_id))
        {
            $this->db->select('hms_doctors.doctor_name as doctor_name');  
            $this->db->where('hms_doctors.id',$doctor_id); 
            $this->db->where('is_deleted !=2');
            $query = $this->db->get('hms_doctors');
            $result = $query->row(); 
            if(!empty($result->doctor_name))
            {
                $doctor_name = $result->doctor_name;    
            }
            
            //echo $this->db->last_query();die;
        }    
            return $doctor_name; 
      
    }

    public function get_vitals_value($id='',$booking_id='',$type='')
    {
        $vitals_val="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($id) && !empty($booking_id))
        {
            $this->db->select('hms_branch_vitals.vitals_value');  
            $this->db->where('hms_branch_vitals.vitals_id',$id);
            $this->db->where('hms_branch_vitals.type',$type);
            $this->db->where('hms_branch_vitals.booking_id',$booking_id); 
            $query = $this->db->get('hms_branch_vitals');
            $result = $query->row();
            if(!empty($result))
            {
                $vitals_val = $result->vitals_value;    
            }
            
            //echo $this->db->last_query();die;
        }    
            return $vitals_val; 
      
    }

    public function get_hospital_name($hospital_id='')
    {
        $hospital_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($hospital_id))
        {
            $this->db->select('hms_hospital.hospital_name as hospital_name');  
            $this->db->where('hms_hospital.id',$hospital_id); 
            $query = $this->db->get('hms_hospital');
            $result = $query->row(); 
            $hospital_name = $result->hospital_name;
            //echo $this->db->last_query();die;
        }    
            return $hospital_name; 
      
    }
    public function get_test_name($test_id='')
    {
        $test_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($test_id))
        {
            $this->db->select('path_test.test_name as test_name');  
            $this->db->where('path_test.id',$test_id); 
            $query = $this->db->get('path_test');
            $result = $query->row(); 
            $test_name = $result->test_name;
            //echo $this->db->last_query();die;
        }    
            return $test_name; 
      
    }
    public function get_profile_name($profile_id='')
    {
        $profile_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($profile_id))
        {
            $this->db->select('path_profile.profile_name as profile_name');  
            $this->db->where('path_profile.id',$profile_id); 
            $query = $this->db->get('path_profile');
            $result = $query->row(); 
            $profile_name = $result->profile_name;
            //echo $this->db->last_query();die;
        }    
            return $profile_name; 
      
    }

    public function get_medicine_name($medicine_id='')
    {
        $medicine_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($medicine_id))
        {
            $this->db->select('hms_medicine_entry.medicine_name as medicine_name');  
            $this->db->where('hms_medicine_entry.id',$medicine_id); 
            $query = $this->db->get('hms_medicine_entry');
            $result = $query->row(); 
            $medicine_name = $result->medicine_name;
            //echo $this->db->last_query();die;
        }    
            return $medicine_name; 
      
    }

    
    
    public function employee_type_name($id='')
    {
        $emp_type="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($id))
        {
            $this->db->select('hms_emp_type.emp_type');  
            $this->db->where('hms_emp_type.id',$id); 
            $query = $this->db->get('hms_emp_type');
            $result = $query->row(); 
            $emp_type = $result->emp_type;
           // echo $this->db->last_query();die;
        }    
            return $emp_type; 
      
    }

    

    public function get_specilization_name($specialization_id='')
    {
        $specialization_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($specialization_id))
        {
            $this->db->select('hms_specialization.specialization as specialization');  
            $this->db->where('hms_specialization.id',$specialization_id); 
            $query = $this->db->get('hms_specialization');
            $result = $query->row(); 
            $specialization_name = $result->specialization;
            //echo $this->db->last_query();die;
        }    
            return $specialization_name; 
      
    }
    

    public function doctor_specilization_list($specilization_id="",$branch_id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
        if(!empty($specilization_id))
        {
            $this->db->where('specilization_id',$specilization_id); 
        }
        if(isset($branch_id) && !empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id);
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']);    
        }
        
        $this->db->where('status','1'); 
        $this->db->where('(doctor_type=1 OR doctor_type=2)');
        $this->db->order_by('doctor_name','ASC'); 
        $this->db->where('is_deleted',0);
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result;
    }


    
    public function particular_list($particular_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
        if(!empty($particular_id))
        {
            $this->db->where('id',$particular_id); 
        }
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1'); 
        $this->db->order_by('particular','ASC'); 
        $this->db->where('is_deleted',0);
        $query = $this->db->get('hms_opd_particular');
        $result = $query->result(); 
        return $result;
    }


    function get_prescription_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_prescription_tab_setting.*,hms_prescription_tab_setting.var_title');   
            $this->db->join('hms_branch_prescription_tab_setting','hms_branch_prescription_tab_setting.unique_id = hms_prescription_tab_setting.id');
            $this->db->where('hms_branch_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_prescription_medicine_tab_setting.*,hms_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_branch_prescription_medicine_tab_setting','hms_branch_prescription_medicine_tab_setting.unique_id = hms_prescription_medicine_tab_setting.id');
            $this->db->where('hms_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_branch_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }


    function get_prescription_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_prescription_tab_setting.*,hms_prescription_tab_setting.var_title');   
            $this->db->join('hms_branch_prescription_tab_setting','hms_branch_prescription_tab_setting.unique_id = hms_prescription_tab_setting.id');
            $this->db->where('hms_branch_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_prescription_tab_setting.status',1);
            $this->db->where('hms_branch_prescription_tab_setting.print_status',1);
            $query = $this->db->get('hms_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_prescription_medicine_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_prescription_medicine_tab_setting.*,hms_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_branch_prescription_medicine_tab_setting','hms_branch_prescription_medicine_tab_setting.unique_id = hms_prescription_medicine_tab_setting.id');
            $this->db->where('hms_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_branch_prescription_medicine_tab_setting.print_status',1);
            $this->db->where('hms_branch_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
  public function expense_category_list()
  {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1'); 
        $this->db->where('is_deleted','0'); 
        $this->db->order_by('exp_category','ASC'); 
        $query = $this->db->get('hms_expenses_category');
        $result = $query->result(); 
        return $result;
    }

    public function get_setting_value($var_title='',$parent_id="")
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

       /* */
    
    public function today_booking_list()
    {
            $users_data = $this->session->userdata('auth_users');
            
            if(in_array('93',$users_data['permission']['section']))
            {
                
                $this->db->select('hms_patient.patient_name,hms_opd_booking.appointment_code as appointment_no,hms_opd_booking.appointment_date as booking_date');   
                $this->db->join('hms_opd_booking','hms_opd_booking.patient_id = hms_patient.id');
                $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
                $this->db->where('hms_opd_booking.appointment_date',date('Y-m-d')); 
                $this->db->where('hms_opd_booking.type',1); 
                $this->db->order_by('hms_opd_booking.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['appointment'] = $query->result();  
            }
            elseif(in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            {
                $this->db->select('hms_patient.patient_name,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date as booking_date');   
                $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id = hms_patient.id');
                $this->db->where('hms_ipd_booking.branch_id',$users_data['parent_id']);
                $this->db->where('hms_ipd_booking.admission_date',date('Y-m-d')); 
                $this->db->order_by('hms_ipd_booking.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['ipd_booking'] = $query->result();
                //print_r($data);die; 
            }
           
            elseif(in_array('85',$users_data['permission']['section']) && !in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            {
                $this->db->select('hms_patient.patient_name,hms_opd_booking.booking_code,hms_opd_booking.appointment_date as booking_date');   
                $this->db->join('hms_opd_booking','hms_opd_booking.patient_id = hms_patient.id');
                $this->db->where('hms_opd_booking.branch_id',$users_data['parent_id']);
                $this->db->where('hms_opd_booking.booking_date',date('Y-m-d')); 
                $this->db->where('hms_opd_booking.type',2); 
                $this->db->order_by('hms_opd_booking.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['opd_booking'] = $query->result(); 

            }
            elseif(in_array('60',$users_data['permission']['section']) && !in_array('85',$users_data['permission']['section']) && !in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            {
              $this->db->select('hms_patient.patient_name,hms_medicine_sale.sale_no,hms_medicine_sale.sale_date as booking_date');   
            
            $this->db->join('hms_medicine_sale','hms_medicine_sale.patient_id = hms_patient.id');
            $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']);
            $this->db->where('hms_medicine_sale.sale_date',date('Y-m-d')); 
            $this->db->order_by('hms_medicine_sale.id','DESC');
            //$this->db->group_by('hms_opd_booking.patient_id');
            $this->db->limit(10);
            $query = $this->db->get('hms_patient');
            $data['medicine_sale'] = $query->result(); 

            }
            elseif(in_array('179',$users_data['permission']['section']) && !in_array('60',$users_data['permission']['section']) && !in_array('85',$users_data['permission']['section']) && !in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            {
                $this->db->select('hms_patient.patient_name,hms_vaccination_sale.sale_no,hms_vaccination_sale.sale_date as booking_date');   
                $this->db->join('hms_vaccination_sale','hms_vaccination_sale.patient_id = hms_patient.id');
                $this->db->where('hms_vaccination_sale.branch_id',$users_data['parent_id']);
                $this->db->where('hms_vaccination_sale.sale_date',date('Y-m-d')); 
                $this->db->order_by('hms_vaccination_sale.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['vaccination_sale'] = $query->result(); 
            }
            elseif(in_array('145',$users_data['permission']['section']) && !in_array('179',$users_data['permission']['section']) && !in_array('60',$users_data['permission']['section']) && !in_array('85',$users_data['permission']['section']) && !in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            { 
                
                $this->db->select('hms_patient.patient_name,path_test_booking.lab_reg_no,path_test_booking.booking_date as booking_date');   
                $this->db->join('path_test_booking','path_test_booking.patient_id = hms_patient.id');
                $this->db->where('path_test_booking.branch_id',$users_data['parent_id']);
                $this->db->where('path_test_booking.booking_date',date('Y-m-d')); 
                $this->db->order_by('path_test_booking.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['test_booking'] = $query->result(); 
            }
            elseif(in_array('134',$users_data['permission']['section']) && !in_array('145',$users_data['permission']['section']) && !in_array('179',$users_data['permission']['section']) && !in_array('60',$users_data['permission']['section']) && !in_array('85',$users_data['permission']['section']) && !in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            {

                $this->db->select('hms_patient.patient_name,hms_operation_booking.booking_code,hms_operation_booking.operation_booking_date as booking_date');   
                $this->db->join('hms_operation_booking','hms_operation_booking.patient_id = hms_patient.id');
                $this->db->where('hms_operation_booking.branch_id',$users_data['parent_id']);
                $this->db->where('hms_operation_booking.operation_booking_date',date('Y-m-d')); 
                $this->db->order_by('hms_operation_booking.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['ot_booking'] = $query->result(); 
            }
            elseif(in_array('207',$users_data['permission']['section']) && !in_array('134',$users_data['permission']['section']) && !in_array('145',$users_data['permission']['section']) && !in_array('179',$users_data['permission']['section']) && !in_array('60',$users_data['permission']['section']) && !in_array('85',$users_data['permission']['section']) && !in_array('121',$users_data['permission']['section']) && !in_array('93',$users_data['permission']['section']))
            {

                $this->db->select('hms_patient.patient_name,hms_dialysis_booking.booking_code,hms_dialysis_booking.dialysis_booking_date as booking_date');   
                $this->db->join('hms_dialysis_booking','hms_dialysis_booking.patient_id = hms_patient.id');
                $this->db->where('hms_dialysis_booking.branch_id',$users_data['parent_id']);
                $this->db->where('hms_dialysis_booking.dialysis_booking_date',date('Y-m-d')); 
                $this->db->order_by('hms_dialysis_booking.id','DESC');
                //$this->db->group_by('hms_opd_booking.patient_id');
                $this->db->limit(10);
                $query = $this->db->get('hms_patient');
                $data['dialysis_booking'] = $query->result();
                //print '<pre>'; print_r($data);die;
               
            }
              return $data; 
             

            
    
    }
     public function pending_report(){
        $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_p.debit,hms_p.credit,(hms_p.credit-hms_p.debit) as Difference,hms_patient.patient_name'); 
            $this->db->join('hms_patient','hms_patient.id = hms_p.patient_id');
            $this->db->join('hms_opd_booking','hms_opd_booking.patient_id = hms_p.patient_id');
            $this->db->where('hms_opd_booking.booking_date',date('Y-m-d'));
            $this->db->order_by('hms_p.id','DESC');
            $this->db->where('hms_p.branch_id',$users_data['parent_id']);
            $this->db->group_by('hms_p.patient_id');
            $this->db->limit(10);
            $this->db->from('hms_payment as hms_p');
            $query = $this->db->get();
            $result = $query->result(); 
            //echo $this->db->last_query();die;
            return $result;
    }
	
	 public function remaining_balance()
	 { 
        $users_data = $this->session->userdata('auth_users');
        /*$query = $this->db->query("SELECT `hms_patient`.*, `hms_payment`.`branch_id`,  `hms_payment`.`created_date` as `date`, `hms_payment`.`discount_amount` as `discount`, `hms_payment`.`parent_id`, `hms_payment`.`section_id`, `hms_payment`.`type`, (select sum(credit)-sum(debit) as balance from hms_payment as payment where (CASE WHEN payment.section_id = 1 THEN path_test_booking.is_deleted !=2 AND path_test_booking.ipd_id = 0 WHEN payment.section_id IN (2, 4) THEN hms_opd_booking.is_deleted !=2 WHEN payment.section_id = 3 THEN hms_medicine_sale.is_deleted !=2 AND hms_medicine_sale.ipd_id=0 WHEN payment.section_id = 5 THEN hms_ipd_booking.is_deleted !=2 ELSE TRUE END) AND payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id AND payment.branch_id = '".$users_data['parent_id']."') as total, `path_test_booking`.`is_deleted` as `test_deleted`, `hms_opd_booking`.`is_deleted` as `opd_deleted`, `hms_medicine_sale`.`is_deleted` as `medicine_deleted`, `hms_ipd_booking`.`is_deleted` as `ipd_deleted` FROM `hms_patient` JOIN `hms_payment` ON `hms_payment`.`patient_id`=`hms_patient`.`id` LEFT JOIN `hms_doctors` ON `hms_doctors`.`id`=`hms_payment`.`doctor_id` AND `hms_doctors`.`doctor_pay_type` = 1 LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 1 LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` IN (2,4) LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 3 LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 5 WHERE (select sum(credit)-sum(debit) as balance from hms_payment as payment where payment.section_id = `hms_payment`.`section_id` AND `payment`.`parent_id` = hms_payment.parent_id) > 0 AND `hms_patient`.`is_deleted` != 2 AND `hms_payment`.`branch_id` = '".$users_data['parent_id']."' AND `hms_payment`.`section_id` IN (2,1,3,4,5) GROUP BY `hms_payment`.`parent_id`, `hms_payment`.`section_id` ORDER BY `hms_payment`.`id` DESC limit 10");  */
        $query = $this->db->query("SELECT `hms_patient`.`id`, `hms_patient`.`patient_name`, `hms_payment`.`branch_id`, `hms_payment`.`created_date` as `date`, `hms_payment`.`discount_amount` as `discount`, `hms_payment`.`parent_id`, `hms_payment`.`section_id`, `hms_payment`.`type`, (select sum(credit)-sum(debit) as balance from hms_payment as payment where 
    (CASE WHEN payment.section_id = 1 THEN path_test_booking.is_deleted !=2 AND path_test_booking.ipd_id = 0 
        WHEN payment.section_id IN (2, 4) THEN hms_opd_booking.is_deleted !=2 
        WHEN payment.section_id = 3 THEN hms_medicine_sale.is_deleted !=2 AND hms_medicine_sale.ipd_id=0 
        WHEN payment.section_id = 5 THEN hms_ipd_booking.is_deleted !=2 
        WHEN payment.section_id = 7 THEN hms_vaccination_sale.is_deleted !=2  
        ELSE TRUE END) AND payment.section_id = hms_payment.section_id AND payment.parent_id = hms_payment.parent_id AND payment.branch_id = '".$users_data['parent_id']."'
     ) as total, `path_test_booking`.`is_deleted` as `test_deleted`, `hms_opd_booking`.`is_deleted` as `opd_deleted`, `hms_medicine_sale`.`is_deleted` as `medicine_deleted`, `hms_ipd_booking`.`is_deleted` as `ipd_deleted` FROM `hms_patient` 
JOIN `hms_payment` ON `hms_payment`.`patient_id`=`hms_patient`.`id` 
LEFT JOIN `hms_doctors` ON `hms_doctors`.`id`=`hms_payment`.`doctor_id` AND `hms_doctors`.`doctor_pay_type` = 1 
LEFT JOIN `path_test_booking` ON `path_test_booking`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 1 
LEFT JOIN `hms_opd_booking` ON `hms_opd_booking`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` IN (2,4) 
LEFT JOIN `hms_medicine_sale` ON `hms_medicine_sale`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 3 
LEFT JOIN `hms_ipd_booking` ON `hms_ipd_booking`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 5 
LEFT JOIN `hms_vaccination_sale` ON `hms_vaccination_sale`.`id` = `hms_payment`.`parent_id` AND `hms_payment`.`section_id` = 7 
         
         WHERE (select sum(credit)-sum(debit) as balance from hms_payment as payment where payment.patient_id = `hms_patient`.`id` group by payment.patient_id) > 0 AND `hms_patient`.`is_deleted` != 2 AND `hms_payment`.`branch_id` = '".$users_data['parent_id']."' AND `hms_payment`.`section_id` IN (2,1,3,4,5,7) GROUP BY `hms_payment`.`patient_id` ORDER BY `hms_payment`.`id` DESC limit 10");
        //echo $this->db->last_query();die;   
        $result = $query->result(); 
        return $result;
        /*$result= array();
        $this->db->select('hms_patient.*,(sum(hms_payment.credit)-sum(hms_payment.debit)) as total');  
        $this->db->from('hms_patient');
        $this->db->where('(select sum(payment.credit)-sum(payment.debit) from hms_payment as payment where payment.patient_id = hms_patient.id) > 0');
        $this->db->join('hms_payment','hms_payment.patient_id=hms_patient.id'); 
        $this->db->where('(hms_patient.branch_id='.$users_data['parent_id'].' and hms_payment.branch_id='.$users_data['parent_id'].' and hms_patient.is_deleted!=2)');

        $this->db->group_by('hms_patient.id'); 
        $this->db->order_by('hms_payment.id','DESC'); 
		$this->db->limit(10);
        $query = $this->db->get(); 
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result;*/
    }
 



    

    function get_package_price($package_id)
    {
        $this->db->select('amount'); 
        $this->db->where('id',$package_id);                   
        $query = $this->db->get('hms_packages');
        $package_list = $query->result(); 
            foreach($package_list as $package)
            {
               $amount = $package->amount;
            } 
        
        return $amount; 
    }
    function get_sales_medicine_list(){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_stock.*,hms_medicine_stock.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_stock.batch_no = batch_no) as stock_quantity, hms_medicine_stock.batch_no,hms_medicine_stock.debit as qty');  
            //$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id');
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_stock.m_id');
             

            $this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_stock.batch_no = batch_no)>0');
            $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
            $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
             $this->db->group_by('hms_medicine_stock.batch_no');
            $this->db->from('hms_medicine_stock'); 
            $query = $this->db->get(); 
            return $query->result();
    }

    function get_sales_return_medicine_list(){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_medicine_sale_to_medicine.*,hms_medicine_sale_to_medicine.id as s_id,,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no) as stock_quantity,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no) as qty, hms_medicine_sale_to_medicine.batch_no');  
        //$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id');
        $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_sale_to_medicine.medicine_id');


        $this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_sale_to_medicine.batch_no = batch_no)>0');
        $this->db->where('hms_medicine_entry.is_deleted','0');  
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')');
        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
        $this->db->group_by('hms_medicine_sale_to_medicine.batch_no');
        $this->db->from('hms_medicine_sale_to_medicine');
       
        $query = $this->db->get(); 
        //print'<pre>';print_r($query->result());die;
        return $query->result();

    }


    function get_purchase_medicine_list(){
        $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post();  
        $this->db->select('hms_medicine_entry.*,hms_medicine_company.id as cm_id,hms_medicine_company.company_name,hms_medicine_entry.created_date as create,hms_medicine_unit.medicine_unit, hms_medicine_unit_2.medicine_unit as medicine_unit_2');  
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
        $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');
         $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
         $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')');
        $this->db->where('hms_medicine_entry.is_deleted','0'); 
        $this->db->from('hms_medicine_entry');
        $query = $this->db->get(); 
        $data= $query->result();
        //print_r($data);die;
        return  $query->result();
    }

    function get_purchase_return_medicine_list(){
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_medicine_purchase_to_purchase.*,hms_medicine_purchase_to_purchase.id as p_id,hms_medicine_entry.id,hms_medicine_entry.medicine_name,hms_medicine_entry.mrp,hms_medicine_entry.packing,hms_medicine_entry.medicine_code,hms_medicine_company.company_name,(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_purchase_to_purchase.batch_no = batch_no) as stock_quantity, hms_medicine_purchase_to_purchase.batch_no,hms_medicine_unit.medicine_unit,hms_medicine_unit_2.medicine_unit as medicine_unit_2');  
            //$this->db->join('hms_medicine_sale','hms_medicine_sale.id = hms_medicine_sale_to_medicine.sales_id');
            $this->db->join('hms_medicine_entry','hms_medicine_entry.id = hms_medicine_purchase_to_purchase.medicine_id');
            $this->db->join('hms_medicine_unit','hms_medicine_unit.id = hms_medicine_entry.unit_id','left');
            $this->db->join('hms_medicine_unit as hms_medicine_unit_2','hms_medicine_unit_2.id = hms_medicine_entry.unit_second_id','left');

            $this->db->where('(SELECT (sum(debit)-sum(credit)) from hms_medicine_stock where m_id = hms_medicine_entry.id AND hms_medicine_purchase_to_purchase.batch_no = batch_no)>0');
            $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
            $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')'); 
            $this->db->group_by('hms_medicine_purchase_to_purchase.batch_no');
            $this->db->from('hms_medicine_purchase_to_purchase');
            $query = $this->db->get(); 
            $data= $query->result();
             return  $query->result();
    }

     function all_medicine_list(){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_medicine_entry.*, hms_medicine_company.company_name, hms_medicine_unit.medicine_unit"); 
        $this->db->from('hms_medicine_entry'); 
        $this->db->where('hms_medicine_entry.is_deleted','0'); 
        $this->db->join('hms_medicine_company','hms_medicine_company.id=hms_medicine_entry.manuf_company','left');
        $this->db->join('hms_medicine_racks','hms_medicine_racks.id=hms_medicine_entry.rack_no','left');
        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
        $this->db->where('hms_medicine_entry.branch_id  IN ('.$users_data['parent_id'].')');
        $query = $this->db->get(); 
        return $query->result();
    }
     function all_vaccination_list(){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("hms_vaccination_entry.*, hms_vaccination_company.company_name, hms_vaccination_unit.vaccination_unit"); 
        $this->db->from('hms_vaccination_entry'); 
        $this->db->where('hms_vaccination_entry.is_deleted','0'); 
        $this->db->join('hms_vaccination_company','hms_vaccination_company.id=hms_vaccination_entry.manuf_company','left');
        $this->db->join('hms_vaccination_racks','hms_vaccination_racks.id=hms_vaccination_entry.rack_no','left');
        $this->db->join('hms_vaccination_unit','hms_vaccination_unit.id=hms_vaccination_entry.unit_id','left');
        $this->db->where('hms_vaccination_entry.branch_id  IN ('.$users_data['parent_id'].')');
        $query = $this->db->get(); 
        return $query->result();
    }

    public function type_to_employee($type_id="",$data_id="")
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_employees.*'); 
        $this->db->where('hms_employees.branch_id',$user_data['parent_id']);
        $this->db->where('hms_employees.emp_type_id',$type_id); 
        $this->db->where('hms_employees.status',1); 
        $this->db->where('hms_employees.is_deleted',0); 
        if(!empty($data_id))
        {
           $this->db->where('hms_employees.id NOT IN(select emp_id from hms_users where id != "'.$data_id.'" )'); 
        }
        else
        {
            $this->db->where('hms_employees.id NOT IN(select emp_id from hms_users)'); 
        }
        
        $this->db->order_by('hms_employees.name','ASC'); 
        $query = $this->db->get('hms_employees');
            //echo $this->db->last_query();die;
        return $query->result();
    }

    public function get_sms_setting($var_title='',$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch_sms_setting.sms_status,hms_branch_sms_setting.email_status'); 
        $this->db->where('hms_branch_sms_setting.setting_name',$var_title); 
        $this->db->where('hms_branch_sms_setting.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_branch_sms_setting');
        $result = $query->row(); 
        //echo $this->db->last_query(); 
        return $result; 
    }

    function sms_template_format($template="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_sms_branch_template.*');
        $this->db->where('form_name',$template);
        $this->db->where('hms_sms_branch_template.branch_id = "'.$parent_id.'"'); 
        $this->db->from('hms_sms_branch_template');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;

    }

    function sms_url($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_branch_sms_config.*');
        //$this->db->where($data);
        if(!empty($branch_id))
        {
            $this->db->where('hms_branch_sms_config.branch_id = "'.$branch_id.'"');
        }
        else
        {
            $this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"');
        }
         
        $this->db->from('hms_branch_sms_config');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;
    }
    

    /*public function get_doctor_signature($doctor_id="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('signature,sign_img'); 
        $this->db->where('doctor_id',$doctor_id);  
        $this->db->where('is_deleted',0);  
        $this->db->where('branch_id  IN ('.$users_data['parent_id'].',0)'); 
        $this->db->group_by('id');  
        $query = $this->db->get('hms_signature');
        $result = $query->row(); 
        return $result; 
    }*/
    public function credit_package_quantity_to_stock($branch_id="",$package_id="",$quantity="",$patient_id="0",$section_id=""){
            
        $users_data = $this->session->userdata('auth_users');
        ////////////Get Package branch_id from table////////////////
        $this->db->select('branch_id,id');
        $this->db->where('id',$package_id);
        $query = $this->db->get('hms_packages');
        $medicine_kit_data = $query->result_array();
        if(!empty($medicine_kit_data)){
            if(!empty($medicine_kit_data[0]['branch_id'])){

                ///////////////Fetch Medicine according to packages/////////////

                 /////////////////Fetch Medicine according to packages////////////
                $this->db->select('hms_medicine_entry.id as med_id,hms_medicine_entry.branch_id as med_branch_id,,hms_medicine_entry.medicine_code,hms_medicine_entry.medicine_name,hms_medicine_entry.unit_id,hms_medicine_entry.unit_second_id,hms_medicine_entry.conversion,hms_medicine_entry.min_alrt,hms_medicine_entry.packing,hms_medicine_entry.rack_no,hms_medicine_entry.salt,hms_medicine_entry.manuf_company,hms_medicine_entry.mrp,hms_medicine_entry.purchase_rate,hms_medicine_entry.hsn_no,hms_medicine_entry.vat,hms_medicine_entry.discount,hms_medicine_entry.igst,hms_medicine_entry.cgst,hms_medicine_entry.status as med_status,hms_medicine_entry.is_deleted as med_is_deleted,hms_medicine_entry.deleted_date as med_deleted_date,hms_medicine_unit.id as units_ids,hms_medicine_unit.medicine_unit,hms_medicine_unit.branch_id as unit_branch_id,hms_medicine_unit.status as unit_status,hms_medicine_unit.deleted_by as unit_deleted_by,hms_medicine_unit.deleted_date as unit_deleted_date,hms_medicine_company.id as com_id,hms_medicine_company.company_name,hms_medicine_company.branch_id as com_branch_id,hms_medicine_company.status as com_status,hms_medicine_company.deleted_by as com_deleted_by,hms_medicine_company.deleted_date as com_deleted_date,hms_packages_to_medicine.total_qty,hms_packages_to_medicine.unit1_qty,hms_packages_to_medicine.unit2_qty');
                $this->db->from('hms_medicine_entry');
                $this->db->join('hms_packages_to_medicine','hms_medicine_entry.id=hms_packages_to_medicine.medicine_id and hms_packages_to_medicine.branch_id='.$branch_id);
                 $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
               
                $this->db->join('hms_medicine_company','hms_medicine_entry.manuf_company=hms_medicine_company.id','left'); 
                $this->db->where('hms_packages_to_medicine.package_id',$package_id);
                $query = $this->db->get();
                $medicine_to_medicine_kit_data = $query->result_array();
                $medicine_to_medicine_kit_data_count = count($medicine_to_medicine_kit_data);

                if(!empty($medicine_to_medicine_kit_data)){
                    
                    for($m=0;$m<$medicine_to_medicine_kit_data_count;$m++){
                        ///////medicine allocate to branch //////////
                        $total_med_qty = $medicine_to_medicine_kit_data[$m]['total_qty']*$quantity;

                        $data_allot_to_branch = array( 
                            'branch_id'=>$branch_id,
                            'type'=>1,
                            'm_id'=>$medicine_to_medicine_kit_data[$m]['med_id'],
                            'credit'=>$total_med_qty,
                            'debit'=>'0',
                            'batch_no'=>'',
                            'is_deleted'=>'0',
                            
                            'ip_address'=>$_SERVER['REMOTE_ADDR'],
                            'created_date'=>date('Y-m-d H:i:s'),
                            'modified_date'=>date('Y-m-d H:i:s'),
                            'created_by'=>$users_data['parent_id'],
                            'purchase_rate'=>$medicine_to_medicine_kit_data[$m]['purchase_rate'],
                            'discount'=>$medicine_to_medicine_kit_data[$m]['discount'],
                            'vat'=>$medicine_to_medicine_kit_data[$m]['vat'],
                            'total_amount'=>$medicine_to_medicine_kit_data[$m]['total_qty'],
                            'expiry_date'=>'',
                            'manuf_date'=>'',
                            'per_pic_rate'=>'',
                             'kit_status'=>'1'
                        );
                        if(!empty($medicine_to_medicine_kit_data[$m]['med_deleted_by'])){
                            $data_allot_to_branch['deleted_by'] = $medicine_to_medicine_kit_data[$m]['med_deleted_by'];
                        }
                        else
                        {
                            $data_allot_to_branch['deleted_by'] = '0';
                        }
                        if(!empty($medicine_to_medicine_kit_data[$m]['med_deleted_date'])){
                            $data_allot_to_branch['deleted_date'] = $medicine_to_medicine_kit_data[$m]['med_deleted_date'];
                        }
                        else
                        {
                            $data_allot_to_branch['deleted_date'] = '00:00:0000';
                        }
                        if(empty($medicine_to_medicine_kit_data[$m]['conversion']) || $medicine_to_medicine_kit_data[$m]['conversion']==0){
                            $data_allot_by_branch['conversion'] = '1';
                        }
                        else
                        {
                            $data_allot_by_branch['conversion'] = $medicine_to_medicine_kit_data[$m]['conversion'];
                        }
                        $this->db->insert('hms_medicine_stock',$data_allot_to_branch);
                        ///////////////////////////////////////
                    }

                }


            }
        }


    }

    function get_email_template($template="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_email_branch_template.*');
        $this->db->where('form_name',$template);
        $this->db->where('hms_email_branch_template.branch_id = "'.$parent_id.'"'); 
        $this->db->from('hms_email_branch_template');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;

    }

    function email_setting()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_email_setting.*');
        $this->db->where('hms_email_setting.branch_id = "'.$users_data['parent_id'].'"'); 
        $this->db->from('hms_email_setting');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;
    }
    
    function get_patient_according_to_ipd($ipd_id="",$patient_id='',$section_id='',$type='') 
    {   
        $users_data = $this->session->userdata('auth_users'); 
       $this->db->select("hms_patient.id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.adhar_no,hms_patient.relation_type,hms_patient.relation_simulation_id,relation_name,hms_patient.dob,hms_patient.simulation_id,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN ds.emp_id>0 THEN emp.name ELSE hms_branch.branch_name END) as user_name_disch,concat_ws('',hms_patient.address, hms_patient.address2, hms_patient.address3) as address,hms_patient.id as p_id,hms_ipd_booking.id as ipd_id,hms_ipd_booking.ipd_no,hms_ipd_booking.total_amount_dis_bill,hms_ipd_booking.discount_amount_dis_bill,hms_ipd_booking. advance_payment_dis_bill,hms_ipd_booking.net_amount_dis_bill,hms_ipd_booking.refund_amount_dis_bill,hms_ipd_booking.discharge_date,hms_ipd_booking.bank_name,hms_ipd_booking.cheque_no,hms_ipd_booking.cheque_date,hms_ipd_booking.transaction_no,hms_ipd_booking.payment_mode,hms_ipd_booking.patient_type,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_room_category.room_category,hms_ipd_booking.discharge_bill_no,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_booking.panel_name,hms_doctors.doctor_name,hms_payment_mode.payment_mode,(select sum(credit)-sum(debit) from hms_payment where patient_id = hms_ipd_booking.patient_id AND parent_id = hms_ipd_booking.id AND section_id = '5' AND branch_id = ".$users_data['parent_id'].") as balance_amount_dis_bill, (select sum(debit) from hms_payment where patient_id = hms_ipd_booking.patient_id AND parent_id = hms_ipd_booking.id AND section_id = '5' AND branch_id = ".$users_data['parent_id']." AND type!=4 AND type!=0) as paid_amount_dis_bill,hms_specialization.specialization,hms_ipd_booking.mlc,discharge_payment_mode.payment_mode as disc_payment_mode,hms_payment_mode_field_value_acc_section.field_value as card_no,hms_gardian_relation.relation,hms_ipd_booking.remarks,(CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type, hms_insurance_type.insurance_type as insurance_type_name, hms_insurance_company.insurance_company as insurance_company_name, hms_patient.polocy_no as insurance_policy_no,hms_patient.tpa_id, hms_patient.ins_amount as insurance_amount, hms_patient.ins_authorization_no as auth_no,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_ipd_booking.discharge_remarks");
        $this->db->from('hms_patient');
        if(!empty($patient_id))
        {
            $this->db->where('hms_ipd_booking.patient_id',$patient_id);    
        } 
        if(!empty($ipd_id))
        {
            $this->db->where('hms_ipd_booking.id',$ipd_id);    
        }
        
        $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id=hms_patient.id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
        
         $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_ipd_booking.payment_mode','left');
        
        $this->db->join('hms_payment_mode as discharge_payment_mode','discharge_payment_mode.id=hms_ipd_booking.discharge_payment_mode','left');
        
        
        if(!empty($section_id) && !empty($type))
        {
            $this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_ipd_booking.id AND hms_payment_mode_field_value_acc_section.section_id='.$section_id.' AND hms_payment_mode_field_value_acc_section.type='.$type,'left');
        
        }
        else
        {
            $this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_ipd_booking.id AND hms_payment_mode_field_value_acc_section.section_id=3 AND hms_payment_mode_field_value_acc_section.type=9','left');
            
        }
        
        
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');
        $this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');
        $this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by','left');
        
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->join('hms_users as ds','ds.id = hms_ipd_booking.discharge_created_by','left');
        
         $this->db->join('hms_employees as emp','emp.id=ds.emp_id','left');
        
         
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left'); 
		 
		 $this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_ipd_booking.panel_type','left'); // insurance type name
        $this->db->join('hms_insurance_company','hms_insurance_company.id = hms_ipd_booking.panel_name','left'); // insurance company
        
        $query = $this->db->get(); 
        //echo $this->db->last_query(); //exit;
        return $query->row_array();
    }
    //19novemer2021
    function get_patient_according_to_ipd20211119($ipd_id="",$patient_id='',$section_id='',$type='') 
    {   
        $users_data = $this->session->userdata('auth_users'); 
       $this->db->select("hms_patient.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,(CASE WHEN ds.emp_id>0 THEN emp.name ELSE hms_branch.branch_name END) as user_name_disch,concat_ws('',hms_patient.address, hms_patient.address2, hms_patient.address3) as address,hms_patient.id as p_id,hms_ipd_booking.id as ipd_id,hms_ipd_booking.ipd_no,hms_ipd_booking.total_amount_dis_bill,hms_ipd_booking.discount_amount_dis_bill,hms_ipd_booking. advance_payment_dis_bill,hms_ipd_booking.net_amount_dis_bill,hms_ipd_booking.refund_amount_dis_bill,hms_ipd_booking.discharge_date,hms_ipd_booking.bank_name,hms_ipd_booking.cheque_no,hms_ipd_booking.cheque_date,hms_ipd_booking.transaction_no,hms_ipd_booking.payment_mode,hms_ipd_booking.patient_type,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_room_category.room_category,hms_ipd_booking.discharge_bill_no,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_booking.panel_name,hms_doctors.doctor_name,hms_payment_mode.payment_mode,(select sum(credit)-sum(debit) from hms_payment where patient_id = hms_ipd_booking.patient_id AND parent_id = hms_ipd_booking.id AND section_id = '5' AND branch_id = ".$users_data['parent_id'].") as balance_amount_dis_bill, (select sum(debit) from hms_payment where patient_id = hms_ipd_booking.patient_id AND parent_id = hms_ipd_booking.id AND section_id = '5' AND branch_id = ".$users_data['parent_id']." AND type!=4 AND type!=0) as paid_amount_dis_bill,hms_specialization.specialization,hms_ipd_booking.mlc,discharge_payment_mode.payment_mode as disc_payment_mode,hms_payment_mode_field_value_acc_section.field_value as card_no,hms_gardian_relation.relation,hms_ipd_booking.remarks,(CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type, hms_insurance_type.insurance_type as insurance_type_name, hms_insurance_company.insurance_company as insurance_company_name, hms_patient.polocy_no as insurance_policy_no,hms_patient.tpa_id, hms_patient.ins_amount as insurance_amount, hms_patient.ins_authorization_no as auth_no,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_ipd_booking.discharge_remarks");
        $this->db->from('hms_patient');
        if(!empty($patient_id))
        {
            $this->db->where('hms_ipd_booking.patient_id',$patient_id);    
        } 
        if(!empty($ipd_id))
        {
            $this->db->where('hms_ipd_booking.id',$ipd_id);    
        }
        
        $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id=hms_patient.id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
        
         $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        
        $this->db->join('hms_payment_mode','hms_payment_mode.id=hms_ipd_booking.payment_mode','left');
        
        $this->db->join('hms_payment_mode as discharge_payment_mode','discharge_payment_mode.id=hms_ipd_booking.discharge_payment_mode','left');
        
        
        if(!empty($section_id) && !empty($type))
        {
            $this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_ipd_booking.id AND hms_payment_mode_field_value_acc_section.section_id='.$section_id.' AND hms_payment_mode_field_value_acc_section.type='.$type,'left');
        
        }
        else
        {
            $this->db->join('hms_payment_mode_field_value_acc_section','hms_payment_mode_field_value_acc_section.parent_id = hms_ipd_booking.id AND hms_payment_mode_field_value_acc_section.section_id=3 AND hms_payment_mode_field_value_acc_section.type=9','left');
            
        }
        
        
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');
        $this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');
        $this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by','left');
        
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->join('hms_users as ds','ds.id = hms_ipd_booking.discharge_created_by','left');
        
         $this->db->join('hms_employees as emp','emp.id=ds.emp_id','left');
        
         
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left'); 
		 
		 $this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_ipd_booking.panel_type','left'); // insurance type name
        $this->db->join('hms_insurance_company','hms_insurance_company.id = hms_ipd_booking.panel_name','left'); // insurance company
        
        $query = $this->db->get(); 
        //echo $this->db->last_query(); //exit;
        return $query->row_array();
    }

    function get_particular_details($ipd_id="",$patient_id='')
    {
        //price before 3 july 2020
        $this->db->select('hms_ipd_patient_to_charge.id as charge_id,hms_ipd_patient_to_charge.particular_id as particular,hms_ipd_patient_to_charge.particular as particulars,hms_ipd_patient_to_charge.price as charges,(select sum(net_price) from hms_ipd_patient_to_charge as charges where charges.id = hms_ipd_patient_to_charge.id) as amount,hms_ipd_patient_to_charge.quantity,hms_ipd_patient_to_charge.start_date as s_date,hms_ipd_patient_to_charge.doctor,hms_ipd_patient_to_charge.doctor_id');
        $this->db->from('hms_ipd_patient_to_charge');
        if(!empty($patient_id))
        {
            $this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);    
        } 
        if(!empty($ipd_id))
        {
            $this->db->where('hms_ipd_patient_to_charge.ipd_id',$ipd_id);    
        }
        $this->db->where('hms_ipd_patient_to_charge.type',5);
        $this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
        
        $query = $this->db->get()->result_array(); 
        //echo $this->db->last_query();die;
      // print '<pre>'; print_r( $query);die;
        return $query;
    }

    function get_discharge_summary_print_tab_setting($parent_id="",$order_by='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_branch_discharge_labels_setting.*,hms_ipd_discharge_labels_setting.var_title');   
        $this->db->join('hms_ipd_discharge_labels_setting','hms_ipd_discharge_labels_setting.id = hms_ipd_branch_discharge_labels_setting.unique_id');
        $this->db->where('hms_ipd_branch_discharge_labels_setting.branch_id',$parent_id);
        if(!empty($order_by))
        {
           $this->db->order_by('hms_ipd_branch_discharge_labels_setting.order_by',$order_by); 
        }
        else
        {
            $this->db->order_by('hms_ipd_branch_discharge_labels_setting.order_by','ASC');     
        }
        $this->db->where('hms_ipd_branch_discharge_labels_setting.status',1);
        $this->db->where('hms_ipd_branch_discharge_labels_setting.print_status',1);
        $query = $this->db->get('hms_ipd_branch_discharge_labels_setting');

        $result_pre['discharge_level_list']= $query->result();
        
        $this->db->select('hms_ipd_vital_labels_setting.*, hms_ipd_branch_vital_labels_setting.setting_name, hms_ipd_branch_vital_labels_setting.setting_value,hms_ipd_branch_vital_labels_setting.order_by,hms_ipd_branch_vital_labels_setting.status,hms_ipd_branch_vital_labels_setting.print_status');
        $this->db->join('hms_ipd_branch_vital_labels_setting', 'hms_ipd_branch_vital_labels_setting.unique_id=hms_ipd_vital_labels_setting.id AND hms_ipd_branch_vital_labels_setting.branch_id = "'.$users_data['parent_id'].'"','left');
        $this->db->from('hms_ipd_vital_labels_setting');
        $this->db->order_by('hms_ipd_branch_vital_labels_setting.order_by','ASC');  
        //$query = $this->db->get()->result(); 
        $result_pre['discharge_vital_level_list']= $this->db->get()->result(); 
        //echo $this->db->last_query();exit;
        return $result_pre; 
                
    }


    function get_progress_report_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_progress_report_tab_setting.*,hms_ipd_progress_report_tab_setting.var_title');   
            $this->db->join('hms_ipd_progress_report_tab_setting','hms_ipd_progress_report_tab_setting.id = hms_ipd_branch_progress_report_tab_setting.unique_id','left');
            $this->db->where('hms_ipd_branch_progress_report_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_progress_report_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_progress_report_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_progress_report_tab_setting.status',1);
            $query = $this->db->get('hms_ipd_branch_progress_report_tab_setting');
            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

     public function get_ot_package_charge($ot_id="")
     {
        $users_data = $this->session->userdata('auth_users');
        $result =array();
        if(!empty($ot_id))
        {
            $this->db->select('amount,name');
            $this->db->where('id',$ot_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $query = $this->db->get('hms_ot_pacakge');
            $result = $query->result_array();

        }
        return $result;
    }

     public function get_ot_charge($ot_id="")
     {
        $users_data = $this->session->userdata('auth_users');
        $result =array();
        if(!empty($ot_id))
        {
            $this->db->select('amount,name');
            $this->db->where('id',$ot_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $query = $this->db->get('hms_ot_management');
            $result = $query->result_array();

        }
        return $result;
    }

    public function get_particular_charge($particular_id="",$panel_id='')
     {
        $users_data = $this->session->userdata('auth_users');
        $result =array();
        if(!empty($ot_id))
        {
            $this->db->select('charge');
            $this->db->where('particular_id',$particular_id);
            $this->db->where('panel_company_id',$panel_id);
            $query = $this->db->get('hms_ipd_particular_charge');
            $result = $query->result_array();

        }
        return $result;
    }

    public function get_doctor($doctor_id='')
    {
        $doctor_name="";
        $result = [];
        $users_data = $this->session->userdata('auth_users');
        if(!empty($doctor_id))
        {
            $this->db->select('hms_doctors.*');  
            $this->db->where('hms_doctors.id',$doctor_id); 
            $query = $this->db->get('hms_doctors');
            $result = $query->row();  
            //echo $this->db->last_query();die;
        }    
            return $result; 
      
    }



//Pathology functions//

  
   
   /* public function country_list()
    {
        $this->db->select('*'); 
        $this->db->where('status','1'); 
        $this->db->order_by('country','ASC'); 
        $query = $this->db->get('path_countries');
        $result = $query->result(); 
        return $result;
    }*/
    public function category_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('category','ASC'); 
        $query = $this->db->get('path_stock_category');
        return $query->result();
    }
     /*public function expense_category_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
         $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1'); 
        $this->db->where('is_deleted','0'); 
        $this->db->order_by('exp_category','ASC'); 
        $query = $this->db->get('path_expenses_category');
        $result = $query->result(); 
        return $result;
    }*/
    
   /* public function vendor_list()
    {
       $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('name','ASC'); 
        $query = $this->db->get('path_stock_vendors');
        return $query->result();
    }*/

    /*public function state_list($country_id="")
    {
        $this->db->select('*'); 
        if(!empty($country_id))
        {
            $this->db->where('country_id',$country_id); 
        }
        $this->db->where('status','1'); 
        $this->db->order_by('state','ASC'); 
        $query = $this->db->get('path_state');
        $result = $query->result(); 
        return $result;
    }

    public function city_list($state_id="")
    {
        $this->db->select('*'); 
        if(!empty($state_id))
        {
            $this->db->where('state_id',$state_id); 
        }
        $this->db->where('status','1'); 
        $this->db->order_by('city','ASC'); 
        $query = $this->db->get('path_cities');
        $result = $query->result(); 
        return $result;
    }*/

     public function get_item_list($item_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
        if(!empty($item_id))
        {
            $this->db->where('id',$item_id); 
        }
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('status','1'); 
        $this->db->order_by('item','ASC'); 
        $query = $this->db->get('path_item');
        $result = $query->result(); 
        return $result;
    }
    /*public function find_employee_salary($emp_id=""){
        if(!empty($emp_id)){
            $this->db->select('salary');
            $this->db->where('id',$emp_id);
            $query = $this->db->get('path_employees');
            $result = $query->result(); 
            return $result; 
        }
    }*/
    
    /*public function rate_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('title','ASC');
        $this->db->where('is_deleted',0); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('path_rate_plan');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function employee_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('name','ASC');
        $this->db->where('is_deleted',0); 
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('path_employees');
        $result = $query->result(); 
        return $result; 
    }*/ 

    /*public function specialization_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('specialization','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_specialization');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function get_rate_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('title','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_rate_plan');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function simulation_list($branch_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $booking_set_branch = $this->session->userdata('booking_set_branch');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('simulation','ASC');
        $this->db->where('is_deleted',0);
        if(isset($booking_set_branch) && !empty($booking_set_branch))
        {
          $this->db->where('branch_id',$booking_set_branch); 
        }
        else if(!empty($branch_id))
        {
           $this->db->where('branch_id',$branch_id); 
        }
        else
        {
          $this->db->where('branch_id',$users_data['parent_id']); 
        } 
        $query = $this->db->get('hms_simulation');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function religion_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('religion','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_religion');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function relation_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('relation','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_relation');
        $result = $query->result(); 
        return $result; 
    }*/

   /* public function doctors_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('doctor_name','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        return $result; 
    }*/

   /* public function insurance_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('insurance_type','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_insurance_type');
        $result = $query->result(); 
        return $result; 
    }*/

   /* public function insurance_company_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('insurance_company','ASC');
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_insurance_company');
        $result = $query->result(); 
        return $result; 
    }*/
    
    public function test_heads_list($dept_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('test_heads','ASC');
        if(!empty($dept_id))
        {
            $this->db->where('dept_id',$dept_id);
        }
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_test_heads');
        $result = $query->result(); 
        return $result; 
    }
    /*public function get_sub_branch_details($branch_ids=array()){

        if(!empty($branch_ids)){ 
            $id_list = [];
            foreach($branch_ids as $sub_branch_ids){
                foreach ($sub_branch_ids as $id) {
                    if(!empty($id) && $id>0){
                        $id_list[]  = $id;
                    }
                } 
            }
            $branch_ids = implode(',', $id_list);
            $this->db->select('id,branch_name');
            $this->db->where('id IN ('.$branch_ids.')');
            $query = $this->db->get('hms_branch');
            $result = $query->result();
            return $result; 
        }
    }*/

    /*public function get_specilization_name($specialization_id='')
    {
        $specialization_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($specialization_id))
        {
            $this->db->select('path_specialization.specialization as specialization');  
            $this->db->where('path_specialization.id',$specialization_id); 
            $query = $this->db->get('path_specialization');
            $result = $query->row(); 
            $specialization_name = $result->specialization;
            //echo $this->db->last_query();die;
        }    
            return $specialization_name; 
      
    }
   */

    /*public function department_list($doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');   
        $query = $this->db->get('hms_department');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function check_username($username="",$id="")
    {
        $this->db->select('*');  
        if(!empty($username))
        {
            $this->db->where('username',$username);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $query = $this->db->get('hms_users');
        $result = $query->result(); 
        return $result; 
    } */

   /* public function check_email($email="",$id="")
    {
        $this->db->select('*');  
        if(!empty($email))
        {
            $this->db->where('email',$email);
        }
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        }  
        $query = $this->db->get('hms_users');
        $result = $query->result(); 
        return $result; 
    } */

    /*function get_branch_format($type="",$parent_id="")
    {
        if(!empty($type))
        {
            $this->db->select('hms_branch_unique_ids.*');   
            $this->db->join('hms_branch_unique_ids','hms_branch_unique_ids.unique_id = path_unique_ids.id');
            $this->db->where('path_unique_ids.id',$type);
            $this->db->where('hms_branch_unique_ids.branch_id',$parent_id);
            $query = $this->db->get('path_unique_ids');
            $result = $query->row(); 
            // echo $this->db->last_query();die;
            return $result; 
        }        
    }*/

    public function total_branch($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch.*'); 
        $this->db->where('hms_branch.parent_id',$parent_id);
        $this->db->where('hms_branch.is_deleted != 2');        
        if(!empty($prefix))
        { 
           $this->db->where('hms_branch.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "1" AND  branch_id = "'.$parent_id.'")');  
        }  
        $query = $this->db->get('hms_branch');
        $result = $query->num_rows(); 
        //echo $result;die;
        return $result; 
    }


    public function total_employee($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_employees.*'); 
        $this->db->where('hms_employees.branch_id',$parent_id); 
        $this->db->where('hms_employees.is_deleted != 2');        
        if(!empty($prefix))
        {
          $this->db->where('hms_employees.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "2" AND  branch_id = "'.$parent_id.'")'); 
        }  
        $query = $this->db->get('hms_employees');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function total_doctors($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_doctors.*'); 
        $this->db->where('hms_doctors.branch_id',$parent_id); 
        $this->db->where('hms_doctors.is_deleted != 2');  
        if(!empty($prefix))
        {
           $this->db->where('hms_doctors.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "3" AND  branch_id = "'.$parent_id.'" )');
        }   
        $query = $this->db->get('hms_doctors');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function total_patient($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_patient.*'); 
        $this->db->where('hms_patient.branch_id',$parent_id); 
        $this->db->where('hms_patient.is_deleted != 2');  
        if(!empty($prefix))
        {
          $this->db->where('hms_patient.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "4" AND  branch_id = "'.$parent_id.'")'); 
        } 
        $query = $this->db->get('hms_patient');
        //echo $this->db->last_query();die;
        $result = $query->num_rows(); 
        return $result; 
    }
    //changes
    public function total_test($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test.*'); 
        $this->db->where('path_test.branch_id',$parent_id); 
        $this->db->where('path_test.is_deleted != 2');  
        if(!empty($prefix))
        {
          $this->db->where('path_test.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "25" AND  branch_id = "'.$parent_id.'")'); 
        } 
        $query = $this->db->get('path_test');
        $result = $query->num_rows(); 
        return $result; 
    }
     public function total_item_vendor($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_stock_vendors.*'); 
        $this->db->where('hms_stock_vendors.branch_id',$parent_id);
        $this->db->where('hms_stock_vendors.is_deleted != 2');  

        if(!empty($prefix))
        {
          $this->db->where('hms_stock_vendors.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "6" AND  branch_id = "'.$parent_id.'")'); 
        }  
        
        $query = $this->db->get('hms_stock_vendors');
        $result = $query->num_rows(); 
        return $result; 
    }

     public function total_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_item.*'); 
        $this->db->where('path_item.branch_id',$parent_id); 
        $this->db->where('path_item.is_deleted != 2');
        if(!empty($prefix))
        {
          $this->db->where('path_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "8" AND  branch_id = "'.$parent_id.'")'); 
        }  
        $query = $this->db->get('path_item');
        $result = $query->num_rows(); 
        return $result; 
    }

    
    public function path_total_booking($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking.*'); 
        $this->db->where('path_test_booking.branch_id',$parent_id);  
        $this->db->where('path_test_booking.is_deleted != 2');
        if(!empty($prefix))
        {
          $this->db->where('path_test_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "26" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('path_test_booking');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function total_ot_booking($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_operation_booking.*'); 
        $this->db->where('hms_operation_booking.branch_id',$parent_id);  
        $this->db->where('hms_operation_booking.is_deleted != 2');
        if(!empty($prefix))
        {
          $this->db->where('hms_operation_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "23" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_operation_booking');
        $result = $query->num_rows(); 
        return $result; 
    }

     public function tot_inventory_purchase_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_purchase_item.*'); 
        $this->db->where('path_purchase_item.branch_id',$parent_id);  
        $this->db->where('path_purchase_item.is_deleted != 2');
        if(!empty($prefix))
        {
          $this->db->where('path_purchase_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "27" AND  branch_id = "'.$parent_id.'" )');
        }  
        $query = $this->db->get('path_purchase_item');
        $result = $query->num_rows(); 
        return $result; 
    }
      public function tot_inventory_purchase_return_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_purchase_return_item.*'); 
        $this->db->where('path_purchase_return_item.branch_id',$parent_id); 
        $this->db->where('path_purchase_return_item.is_deleted != 2'); 
        if(!empty($prefix))
        {
          $this->db->where('path_purchase_return_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "28" AND  branch_id = "'.$parent_id.'" )');
        }  
        $query = $this->db->get('path_purchase_return_item');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function tot_inventory_stock_issue_allotment_item($parent_id="", $prefix="0")
    {
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_stock_issue_allotment.*'); 
    $this->db->where('hms_stock_issue_allotment.branch_id',$parent_id); 
    $this->db->where('hms_stock_issue_allotment.is_deleted != 2');  
    if(!empty($prefix))
    {
    $this->db->where('hms_stock_issue_allotment.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "30" AND  branch_id = "'.$parent_id.'" )');
    }  
    $query = $this->db->get('hms_stock_issue_allotment');
    $result = $query->num_rows(); 
    return $result; 
    }

    public function tot_inventory_stock_issue_allotment_return_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_stock_issue_allotment_return_item.*'); 
        $this->db->where('hms_stock_issue_allotment_return_item.branch_id',$parent_id);  
        $this->db->where('hms_stock_issue_allotment_return_item.is_deleted != 2');  
        if(!empty($prefix))
        {
        $this->db->where('hms_stock_issue_allotment_return_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "31" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_stock_issue_allotment_return_item');
        $result = $query->num_rows(); 
        return $result; 
    }
    public function tot_inventory_garbage_stock_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_garbage_stock_item.*'); 
        $this->db->where('hms_garbage_stock_item.branch_id',$parent_id);  
        $this->db->where('hms_garbage_stock_item.is_deleted != 2');  
        if(!empty($prefix))
        {
        $this->db->where('hms_garbage_stock_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "32" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_garbage_stock_item');
        $result = $query->num_rows(); 
        return $result; 
    }
    public function tot_inventory_stock_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_item.*'); 
        $this->db->where('path_item.branch_id',$parent_id); 
        $this->db->where('path_item.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('path_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "33" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('path_item');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function tot_dialysis_book($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_dialysis_booking.*'); 
        $this->db->where('hms_dialysis_booking.branch_id',$parent_id); 
        $this->db->where('hms_dialysis_booking.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_dialysis_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "34" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_dialysis_booking');
        $result = $query->num_rows(); 
        return $result; 
    }
    
     public function tot_dialysis_appointment($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_dialysis_appointment.*'); 
        $this->db->where('hms_dialysis_appointment.branch_id',$parent_id); 
        $this->db->where('hms_dialysis_appointment.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_dialysis_appointment.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "74" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_dialysis_appointment');
        $result = $query->num_rows(); 
        return $result; 
    }

  public function total_vaccination_entry($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_vaccination_entry.*'); 
        $this->db->where('hms_vaccination_entry.branch_id',$parent_id); 
        $this->db->where('hms_vaccination_entry.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_vaccination_entry.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "35" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_vaccination_entry');
        $result = $query->num_rows(); 
        return $result; 
    }
  /*  public function total_vaccination_vendor($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_vaccination_entry.*'); 
        $this->db->where('hms_vaccination_entry.branch_id',$parent_id); 
        $this->db->where('hms_vaccination_entry.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_vaccination_entry.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "36" AND  branch_id = "'.$parent_id.'" AND hms_vaccination_entry.is_deleted != 2)');
        }  
        $query = $this->db->get('hms_dialysis_booking');
        $result = $query->num_rows(); 
        return $result; 
    }*/
  public function total_vaccination_purchase($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_vaccination_purchase.*'); 
        $this->db->where('hms_vaccination_purchase.branch_id',$parent_id); 
        $this->db->where('hms_vaccination_purchase.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_vaccination_purchase.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "37" AND  branch_id = "'.$parent_id.'" )');
        }  
        $query = $this->db->get('hms_vaccination_purchase');
        $result = $query->num_rows(); 
        return $result; 
    }

  public function total_vaccination_purchase_return($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_vaccination_purchase_return.*'); 
        $this->db->where('hms_vaccination_purchase_return.branch_id',$parent_id); 
        $this->db->where('hms_vaccination_purchase_return.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_vaccination_purchase_return.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "38" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_vaccination_purchase_return');
        $result = $query->num_rows(); 
        return $result; 
    }
   public function total_vaccination_billing($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_vaccination_sale.*'); 
        $this->db->where('hms_vaccination_sale.branch_id',$parent_id); 
        $this->db->where('hms_vaccination_sale.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_vaccination_sale.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "39" AND  branch_id = "'.$parent_id.'" )');
        }  
        $query = $this->db->get('hms_vaccination_sale');
        $result = $query->num_rows(); 
        return $result; 
    }

  public function total_vaccination_billing_return($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_vaccination_sale_return.*'); 
        $this->db->where('hms_vaccination_sale_return.branch_id',$parent_id); 
        $this->db->where('hms_vaccination_sale_return.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_vaccination_sale_return.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "40" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_vaccination_sale_return');
        $result = $query->num_rows(); 
        return $result; 
    }



    

    public function tot_hospital($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_hospital.*'); 
        $this->db->where('hms_hospital.branch_id',$parent_id); 
        $this->db->where('hms_hospital.is_deleted != 2'); 
        if(!empty($prefix))
        {
           $this->db->where('hms_hospital.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "28" AND  branch_id = "'.$parent_id.'")');
        }   
        $query = $this->db->get('hms_hospital');
        $result = $query->num_rows(); 
        return $result; 
    }

//few changes
    public function total_bills($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_expenses.*'); 
        $this->db->where('hms_expenses.branch_id',$parent_id); 
        $this->db->where('hms_expenses.is_deleted != 2'); 
        if(!empty($prefix))
        {
          $this->db->where('hms_expenses.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "19" AND  branch_id = "'.$parent_id.'")');
        }  
        //$this->db->where('path_expenses.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_expenses');

        $result = $query->num_rows(); 
        return $result; 
    }

   /* public function user_role_list()
    {
        $this->db->select('hms_users_role.*');  
        $this->db->where('id != "1"');
        $query = $this->db->get('hms_users_role');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function get_permission_attr($section_id="",$action_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('attribute_val');  
        if(!empty($section_id))
        {
            $this->db->where('section_id',$section_id);
        }
        if(!empty($action_id))
        {
            $this->db->where('action_id',$action_id);
        }
        $this->db->where('users_id',$users_data['id']);
        $query = $this->db->get('path_permission_to_users'); 
        $result = $query->row_array(); 
        return $result; 
    }*/
    //function to find gender according to selected simulation
    /*public function find_gender($id='')
    {
        $this->db->select('gender');
        if(!empty($id))
        {
          $this->db->where('id',$id);  
        } 
        $query = $this->db->get('hms_simulation');
        $result = $query->row_array(); 
        return $result;
    }*/
    public function  get_current_branche_details($branch_id=''){
        if(!empty($branch_id)){
            $this->db->select('*');
            $this->db->where('id',$branch_id);
            $res = $this->db->get('hms_branch');
            $result = $res->result_array();
        }
        return $result;
    }
   /* public function get_printer_type()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
        $this->db->where('parent_id',0);   
        $query = $this->db->get('path_printer');
        $result = $query->result(); 
        return $result; 
    }*/

    /*public function get_doctor($doctor_id='')
    {
        $doctor_name="";
        $result = [];
        $users_data = $this->session->userdata('auth_users');
        if(!empty($doctor_id))
        {
            $this->db->select('hms_doctors.*');  
            $this->db->where('hms_doctors.id',$doctor_id); 
            $query = $this->db->get('hms_doctors');
            $result = $query->row();  
            //echo $this->db->last_query();die;
        }    
            return $result; 
      
    }*/

    /*public function get_doctor_name($doctor_id='')
    {
        $doctor_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($doctor_id))
        {
            $this->db->select('hms_doctors.doctor_name as doctor_name');  
            $this->db->where('hms_doctors.id',$doctor_id); 
            $query = $this->db->get('hms_doctors');
            $result = $query->row(); 
            $doctor_name = $result->doctor_name;
            //echo $this->db->last_query();die;
        }    
            return $doctor_name; 
      
    }*/

    /*public function get_religion_name($id='')
    {
        $religion_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($id))
        {
            $this->db->select('path_religion.religion as religion_name');  
            $this->db->where('path_religion.id',$id); 
            $query = $this->db->get('path_religion');
            $result = $query->row(); 
            $religion_name = $result->religion_name;
            //echo $this->db->last_query();die;
        }    
            return $religion_name; 
      
    }*/
    
    public function test_list_name($id="",$field='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $company_data = $this->session->userdata('company_data'); 
        $this->db->select($field); 
        $this->db->where('id',$id);  
        $this->db->where('is_deleted',0); 
        if($users_data['users_role']==4)
        {
          $this->db->where('branch_id',$company_data['id']); 
        }
        else if($users_data['users_role']==3)
        {
          $this->db->where('branch_id',$company_data['id']); 
        }
        else
        {
          $this->db->where('branch_id',$users_data['parent_id']); 
        } 
        
        $this->db->group_by('id');  
        $query = $this->db->get('path_test');
        $result = $query->row(); 
        if(!empty($result))
        {
           $filed_name = $result->$field;
        }
        else
        {
           $filed_name = '';
        } 
        //echo $this->db->last_query(); 
        return $filed_name; 
    }

    public function get_all_test_list20190424($booking_id="",$thead_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking_to_test.*,path_test.test_head_id, path_test.test_name, path_test.interpretation as test_interpretation,path_test.precaution,path_test.range_from,path_test.range_to,path_test.unit_id,path_test.test_type_id, path_test.range_from_pre, path_test.range_from_post, path_test.range_to_pre, path_test.range_to_post, path_test.all_range_show, path_test.dept_id');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id');  
        $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
        $this->db->where('path_test_booking_to_test.print ="1"');
        
        $this->db->where('path_test_booking_to_test.test_type  IN (0)');
        $this->db->where('path_test_heads.id = "'.$thead_id.'"');
        
        $this->db->order_by('path_test_heads.sort_order','ASC');  
        //$this->db->order_by('path_test.test_type_id','DESC'); 
        $this->db->order_by('path_test.sort_order','ASC');
        $this->db->from('path_test_booking_to_test');
        $result =  $this->db->get()->result();
        //echo $this->db->last_query();
        return $result; 
   }

   public function get_all_profile_test_list20190424($booking_id="",$thead_id="",$profile_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking_to_test.*,path_test.test_name, path_test.interpretation as test_interpretation,path_test.range_from,path_test.range_to,path_test.unit_id,path_test.test_type_id, path_test.range_from_pre, path_test.range_from_post, path_test.range_to_pre, path_test.range_to_post, path_test.all_range_show');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left'); 
        $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
        if(!empty($profile_id))
        {
           $this->db->where('path_test_booking_to_test.profile_id = "'.$profile_id.'"');
           $this->db->join('path_profile_to_test','path_profile_to_test.test_id = path_test_booking_to_test.test_id AND path_profile_to_test.profile_id = path_test_booking_to_test.profile_id','left');
        } 
        $this->db->where('path_test_booking_to_test.print ="1"');
        
        $this->db->where('path_test_booking_to_test.test_type  IN (1,2)');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id'); 
        if(!empty($profile_id))
        {
           $this->db->order_by('path_profile_to_test.sort_order','ASC');
        }
        else
        {
            $this->db->order_by('path_test_heads.sort_order','ASC');  
            //$this->db->order_by('path_test.test_type_id','DESC');

            $this->db->order_by('path_test.sort_order','ASC');    
        }
        

       // $this->db->where('path_test_heads.id = "'.$thead_id.'"');
        $this->db->from('path_test_booking_to_test');
        $result =  $this->db->get()->result();
        //echo $this->db->last_query(); exit;
        return $result; 
   }
   
   ////24 march 2019
   
    public function get_all_test_list($booking_id="",$thead_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking_to_test.*,path_test.result_heading,path_test.test_head_id, path_test.test_name, path_test.interpretation as test_interpretation,path_test.precaution,path_test.range_from,path_test.range_to,path_test.unit_id,path_test.test_type_id, path_test.range_from_pre, path_test.range_from_post, path_test.range_to_pre, path_test.range_to_post, path_test.all_range_show, path_test.dept_id,path_test_method.test_method,path_sample_type.sample_type');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id');  
         /*********New Code**************/
        $this->db->join('path_test_method','path_test_method.id = path_test.method_id','left'); 
        $this->db->join('path_sample_type','path_sample_type.id = path_test.sample_test','left'); 
        /**********New Code************/
        $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
        $this->db->where('path_test_booking_to_test.print ="1"');
        
        $this->db->where('path_test_booking_to_test.test_type  IN (0)');
        $this->db->where('path_test_heads.id = "'.$thead_id.'"');
        
        $this->db->order_by('path_test_heads.sort_order','ASC');  
        //$this->db->order_by('path_test.test_type_id','DESC'); 
        $this->db->order_by('path_test.sort_order','ASC');
        $this->db->from('path_test_booking_to_test');
        $result =  $this->db->get()->result();
        return $result; 
   }

   public function get_all_profile_test_list($booking_id="",$thead_id="",$profile_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_test_booking_to_test.*,path_test.test_name, path_test.interpretation as test_interpretation,path_test.range_from,path_test.range_to,path_test.unit_id,path_test.test_type_id, path_test.range_from_pre, path_test.range_from_post, path_test.range_to_pre, path_test.range_to_post, path_test.all_range_show,path_test_method.test_method,path_sample_type.sample_type');
        $this->db->join('path_test','path_test.id = path_test_booking_to_test.test_id','left'); 
        $this->db->where('path_test_booking_to_test.booking_id = "'.$booking_id.'"');
        if(!empty($profile_id))
        {
           $this->db->where('path_test_booking_to_test.profile_id = "'.$profile_id.'"');
           $this->db->join('path_profile_to_test','path_profile_to_test.test_id = path_test_booking_to_test.test_id AND path_profile_to_test.profile_id = path_test_booking_to_test.profile_id','left');
           /*********New Code**************/
        $this->db->join('path_test_method','path_test_method.id = path_test.method_id','left'); 
        $this->db->join('path_sample_type','path_sample_type.id = path_test.sample_test','left'); 
        /**********New Code************/
        } 
        $this->db->where('path_test_booking_to_test.print ="1"');
        
        $this->db->where('path_test_booking_to_test.test_type  IN (1,2)');
        $this->db->join('path_test_heads','path_test_heads.id = path_test.test_head_id'); 
        
        if(!empty($profile_id))
        {
           $this->db->order_by('path_profile_to_test.sort_order','ASC');
        }
        else
        {
            $this->db->order_by('path_test_heads.sort_order','ASC');  
            //$this->db->order_by('path_test.test_type_id','DESC');

            $this->db->order_by('path_test.sort_order','ASC');    
        }
        

       // $this->db->where('path_test_heads.id = "'.$thead_id.'"');
        $this->db->from('path_test_booking_to_test');
        $result =  $this->db->get()->result();
        
        return $result; 
   }
   
   //24 march 2019
   

   

   public function unite_name($id="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $company_data = $this->session->userdata('company_data'); 
        $this->db->select('unit'); 
        $this->db->where('id',$id);  
        $this->db->where('is_deleted',0);  
        if($users_data['users_role']==4)
        {
            $this->db->where('branch_id',$company_data['id']); 
        }
        else if($users_data['users_role']==3)
        {
            $this->db->where('branch_id',$company_data['id']); 
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']); 
        }
        $this->db->group_by('id');  
        $query = $this->db->get('path_unit');
        $result = $query->row(); 
        $filed_name="";
        if(!empty($result))
        {
          $filed_name = $result->unit;  
        }
        return $filed_name; 
    }

    public function get_doctor_signature($doctor_id = "") {
        $users_data = $this->session->userdata('auth_users');
        $company_data = $this->session->userdata('company_data'); 
        
        $this->db->select('hms_doctors.signature, hms_doctors.qualification,hms_doctors.doctor_name,hms_doctors.doc_reg_no');
    
        if ($users_data['users_role'] == 4 || $users_data['users_role'] == 3) {
            $this->db->where('hms_doctors.branch_id', $company_data['id']);
        } else {
            $this->db->where('hms_doctors.branch_id', $users_data['parent_id']);
        }
        $this->db->where('id',$doctor_id);
        
        $query = $this->db->get('hms_doctors');
        $result = $query->row(); 
        return $result;
    }
    

   public function get_default_val($highlight="0",$vals="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $company_data = $this->session->userdata('company_data'); 
        $this->db->select('default_vals'); 
        $this->db->where('is_deleted',0);  
        if($users_data['users_role']==4)
        {
            $this->db->where('branch_id',$company_data['id']); 
        }
        else if($users_data['users_role']==3)
        {
            $this->db->where('branch_id',$company_data['id']); 
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']); 
        }

        if(!empty($highlight) && $highlight>0)
        { 
            $this->db->where('highlight',$highlight); 
        }
        if(!empty($vals))
        { 
            $this->db->where('default_vals',$vals); 
        }
        $query = $this->db->get('path_default_vals');
        $result = $query->result_array();
        $array_test =array();
        if(!empty($result))
        {
           foreach ($result as $value) 
            { 
                $array_test[] = $value['default_vals'];
            }
        } 
        return $array_test; 
       
    }


    public function get_default_val_to_test($vals="",$highlight="0",$test_id="")
   {
        $users_data = $this->session->userdata('auth_users'); 
        $company_data = $this->session->userdata('company_data'); 
        $this->db->select('path_default_vals.default_vals,path_default_val_to_test.test_id,path_default_val_to_test.highlight_type'); 
        $this->db->join('path_default_vals','path_default_vals.id=path_default_val_to_test.default_val_id','left');
        $this->db->where('path_default_vals.is_deleted',0); 
        $this->db->where('path_default_vals.status',1);  
        $this->db->where('path_default_vals.default_vals',$vals);  
        if($users_data['users_role']==4)
        {
            $this->db->where('path_default_vals.branch_id',$company_data['id']); 
        }
        else if($users_data['users_role']==3)
        {
            $this->db->where('path_default_vals.branch_id',$company_data['id']); 
        }
        else
        {
            $this->db->where('path_default_vals.branch_id',$users_data['parent_id']); 
        }

        if(!empty($highlight) && $highlight>0)
        { 
            $this->db->where('path_default_vals.highlight',$highlight); 
        }
        if(!empty($test_id) && $test_id>0)
        { 
            $this->db->where('path_default_val_to_test.test_id',$test_id); 
        }
        $query = $this->db->get('path_default_val_to_test');
        //echo $this->db->last_query(); die;
        $result = $query->result_array();
        $data = [];
        if(!empty($result))
        {
            foreach($result as $defaults)
            {
              $data[$defaults['test_id']] = $defaults;
            }
        }
        return $data; 
       
    }
    
    /* get printer paper type */
   /* public function get_printer_paper_type($parent_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('parent_id',$parent_id);  
        $query = $this->db->get('path_printer');
        $result = $query->result(); 
        return $result; 
    }*/
    /* get printer type */
    
    public function today_booking()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient.patient_name, path_test_booking.lab_reg_no as patient_code');
        $this->db->join('hms_patient','hms_patient.id=path_test_booking.patient_id');
        $this->db->where('DATE_FORMAT(`path_test_booking`.`created_date`,"%Y-%m-%d") >= "'.date("Y-m-d").'"'); 
        $this->db->where('DATE_FORMAT(`path_test_booking`.`created_date`,"%Y-%m-%d") <= "'.date("Y-m-d").'"'); 
        $this->db->where('path_test_booking.branch_id',$users_data['parent_id']); 
        $this->db->limit('10');  
        $this->db->order_by('path_test_booking.id','DESC');  
        $query = $this->db->get('path_test_booking');
        //pecho $this->db->last_query();die;
        $result = $query->result(); 
        return $result; 
    }

    public function letest_pending_report()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient.patient_name, path_test_booking.lab_reg_no as patient_code');
        $this->db->join('hms_patient','hms_patient.id=path_test_booking.patient_id'); 
        $this->db->where('complation_status',0);  
        $this->db->where('path_test_booking.branch_id',$users_data['parent_id']); 
        $this->db->order_by('path_test_booking.id','DESC');  
        $this->db->limit('10');  
        $query = $this->db->get('path_test_booking'); 
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result; 
    }


    public function birthday_list()
    {
        $result_birthday=array();
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_patient.id,hms_patient.dob,hms_patient.patient_name,hms_patient.patient_email,hms_patient.mobile_no,hms_patient.birth_sms_year,hms_patient.birth_email_year"); 
        $this->db->where('hms_patient.branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('month(dob) = month(curdate()) and day(dob) = day(curdate())');
        $this->db->from('hms_patient');
        $result_birthday['patient_list']= $this->db->get()->result();

        $this->db->select("hms_doctors.id,hms_doctors.dob,hms_doctors.doctor_name,hms_doctors.email,hms_doctors.mobile_no,hms_doctors.birth_sms_year,hms_doctors.birth_email_year"); 
        $this->db->where('hms_doctors.branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('month(dob) = month(curdate()) and day(dob) = day(curdate())');//and year(birth_sms_year) != year(curdate())
        $this->db->from('hms_doctors');
        $result_birthday['doctor_list']= $this->db->get()->result();

        $this->db->select("hms_employees.id,hms_employees.dob,hms_employees.name,hms_employees.email,hms_employees.contact_no,hms_employees.birth_sms_year,hms_employees.birth_email_year"); 
        $this->db->where('hms_employees.branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('month(dob) = month(curdate()) and day(dob) = day(curdate())');
        $this->db->from('hms_employees');
        $result_birthday['employees_list']= $this->db->get()->result();
        //echo $this->db->last_query();

        return $result_birthday;
    }

    public function anniversary_list()
    {
        $result_anniversary=array();
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_patient.id,hms_patient.anniversary,hms_patient.patient_name,hms_patient.patient_email,hms_patient.mobile_no,hms_patient.anni_sms_year,hms_patient.anni_email_send_year"); 
        $this->db->where('hms_patient.branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('month(anniversary) = month(curdate()) and day(anniversary) = day(curdate())');
        $this->db->from('hms_patient');
        $result_anniversary['patient_list']= $this->db->get()->result();

        $this->db->select("hms_doctors.id,hms_doctors.anniversary,hms_doctors.doctor_name,hms_doctors.email,hms_doctors.mobile_no,hms_doctors.anni_sms_year,hms_doctors.anni_email_send_year"); 
        $this->db->where('hms_doctors.branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('month(anniversary) = month(curdate()) and day(anniversary) = day(curdate())'); //and year(birth_sms_year) != year(curdate())
        $this->db->from('hms_doctors');
        $result_anniversary['doctor_list']= $this->db->get()->result();

        $this->db->select("hms_employees.id,hms_employees.anniversary,hms_employees.name,hms_employees.email,hms_employees.contact_no,hms_employees.anni_sms_year,hms_employees.anni_email_send_year"); 
        $this->db->where('hms_employees.branch_id = "'.$user_data['parent_id'].'"');
        $this->db->where('month(anniversary) = month(curdate()) and day(anniversary) = day(curdate())');
        $this->db->from('hms_employees');
        $result_anniversary['employees_list']= $this->db->get()->result();
        //echo $this->db->last_query();

        return $result_anniversary;
    }
    public function notice_list()
    {

        $users_data = $this->session->userdata('auth_users');
        //print '<pre>'; print_r($users_data);die;
        $current_date = date('Y-m-d').' 00:00:00';
        $this->db->select('hms_users.*,hms_notice_board_page.msg,hms_notice_board_page.created_by,hms_notice_board_page.created_date,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name'); 
        $this->db->join('hms_users','hms_users.id=hms_notice_board_page.created_by','left');
        $this->db->join('hms_branch','hms_branch.id=hms_notice_board_page.branch_id','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->where('hms_notice_board_page.branch_id',$users_data['parent_id']);
        $this->db->where('hms_users.parent_id',$users_data['parent_id']);
        $this->db->where('hms_notice_board_page.is_deleted',0);
        $this->db->where('hms_notice_board_page.status',1);
        $this->db->where('hms_notice_board_page.created_date >=',$current_date);
        $query = $this->db->get('hms_notice_board_page');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        //print '<pre>'; print_r($result);die;
        return $result;
    }
    
    public function notice_list20210501()
    {

        $users_data = $this->session->userdata('auth_users');
        //print '<pre>'; print_r($users_data);die;
        $current_date = date('Y-m-d').' 00:00:00';
        $this->db->select('hms_users.*,hms_notice_board_page.msg,hms_notice_board_page.created_by,hms_notice_board_page.created_date,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name'); 
        $this->db->join('hms_users','hms_users.id=hms_notice_board_page.created_by','left');
        $this->db->join('hms_branch','hms_branch.id=hms_notice_board_page.branch_id','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->where('hms_notice_board_page.branch_id','0');
        $this->db->where('hms_users.id',1);
        $this->db->where('hms_notice_board_page.is_deleted',0);
        $this->db->where('hms_notice_board_page.status',1);
        $this->db->where('hms_notice_board_page.created_date >=',$current_date);
        $query = $this->db->get('hms_notice_board_page');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        //print '<pre>'; print_r($result);die;
        return $result;
    }


    public function patient_list($patient_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_patient.*'); 
        $this->db->where('hms_patient.branch_id',$users_data['parent_id']);
        if(!empty($patient_id))
        {
            $this->db->where('id',$patient_id);    
        } 
        
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        return $result; 
    }

    public function users_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE "Admin" END) as emp_name, hms_users.id'); 
        $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id AND hms_employees.is_deleted="0"','left');
        $this->db->where('hms_users.parent_id',$users_data['parent_id']);        
        $this->db->where('hms_users.users_role','2');
        $this->db->where('hms_users.is_deleted','0');
        $this->db->order_by('hms_employees.name','ASC');
        $this->db->group_by('hms_users.id');
        $query = $this->db->get('hms_users');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result; 
    }

    public function update_message($type,$id)
    {
        if($type==1 && !empty($id))
        {
            $this->db->set('birth_sms_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_doctors');
        }
        elseif($type==2 && !empty($id))
        {
            $this->db->set('birth_sms_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_patient');
        }
        elseif($type==3 && !empty($id))
        {
            $this->db->set('birth_sms_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_employees');
        }

        elseif($type==4 && !empty($id))
        {
            $this->db->set('anni_sms_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_doctors');
        }
        elseif($type==5 && !empty($id))
        {
            $this->db->set('anni_sms_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_patient');
        }
        elseif($type==6 && !empty($id))
        {
            $this->db->set('anni_sms_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_employees');
        }
    }


    public function update_email($type,$id)
    {
        if($type==1 && !empty($id))
        {
            $this->db->set('birth_email_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_doctors');
        }
        elseif($type==2 && !empty($id))
        {
            $this->db->set('birth_email_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_patient');
        }
        elseif($type==3 && !empty($id))
        {
            $this->db->set('birth_email_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_employees');
        }

        elseif($type==4 && !empty($id))
        {
            $this->db->set('anni_email_send_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_doctors');
        }
        elseif($type==5 && !empty($id))
        {
            $this->db->set('anni_email_send_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_patient');
        }
        elseif($type==6 && !empty($id))
        {
            $this->db->set('anni_email_send_year',date('Y'));
            $this->db->where('id',$id);
            $this->db->update('hms_employees');
        }
    }
    
    
    /*public function type_to_employee($type_id="",$data_id="")
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('path_employees.*'); 
        $this->db->where('path_employees.branch_id',$user_data['parent_id']);
        $this->db->where('path_employees.emp_type_id',$type_id); 
        $this->db->where('path_employees.status',1); 
        $this->db->where('path_employees.is_deleted',0); 
        if(!empty($data_id))
        {
           $this->db->where('path_employees.id NOT IN(select emp_id from hms_users where id != "'.$data_id.'" )'); 
        }
        else
        {
            $this->db->where('path_employees.id NOT IN(select emp_id from hms_users)'); 
        }
        
        $this->db->order_by('path_employees.name','ASC'); 
        $query = $this->db->get('path_employees');
            //echo $this->db->last_query();die;
        return $query->result();
    }*/

    /*public function employee_type_name($id='')
    {
        $emp_type="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($id))
        {
            $this->db->select('path_emp_type.emp_type');  
            $this->db->where('path_emp_type.id',$id); 
            $query = $this->db->get('path_emp_type');
            $result = $query->row(); 
            $emp_type = $result->emp_type;
          
        }    
            return $emp_type; 
      
    }*/

    /*public function get_form_name()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(path_sms_setting_name.var_title),path_sms_setting_name.id');
        //$this->db->join('path_sms_branch_template','path_sms_branch_template.form_name=path_sms_setting_name.id','outer left');
        //$this->db->where('path_sms_branch_template.branch_id',$users_data['parent_id']);
        $this->db->where('path_sms_setting_name.id NOT IN(select form_name from path_sms_branch_template where branch_id='.$users_data['parent_id'].')');
        //$this->db->where('');
        $query = $this->db->get('path_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }*/
    /*public function get_form_name_edit()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(path_sms_setting_name.var_title),path_sms_setting_name.id');
        $query = $this->db->get('path_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
    public function get_email_form_name()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(path_sms_setting_name.var_title),path_sms_setting_name.id');
        $this->db->where('path_sms_setting_name.id NOT IN(select form_name from path_email_branch_template where branch_id='.$users_data['parent_id'].')');
        $query = $this->db->get('path_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }*/
   /* public function get_email_form_name_edit()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('DISTINCT(path_sms_setting_name.var_title),path_sms_setting_name.id');
        $query = $this->db->get('path_sms_setting_name');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
*/

    /*public function get_sms_setting($var_title='',$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch_sms_setting.sms_status,hms_branch_sms_setting.email_status'); 
        $this->db->where('hms_branch_sms_setting.setting_name',$var_title); 
        $this->db->where('hms_branch_sms_setting.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_branch_sms_setting');
        $result = $query->row(); 
        //echo $this->db->last_query(); 
        return $result; 
    }*/

    /*function sms_template_format($template="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_sms_branch_template.*');
        $this->db->where('form_name',$template);
        $this->db->where('path_sms_branch_template.branch_id = "'.$parent_id.'"'); 
        $this->db->from('path_sms_branch_template');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;

    }*/

    /*function sms_url()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_branch_sms_config.*');
        //$this->db->where($data);
        $this->db->where('hms_branch_sms_config.branch_id = "'.$users_data['parent_id'].'"'); 
        $this->db->from('hms_branch_sms_config');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;
    }
*/

    /* function get_email_template($template="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_email_branch_template.*');
        $this->db->where('form_name',$template);
        $this->db->where('path_email_branch_template.branch_id = "'.$parent_id.'"'); 
        $this->db->from('path_email_branch_template');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;

    }*/

    /*function email_setting($branch_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('path_email_setting.*');
        $this->db->where('path_email_setting.branch_id = "'.$branch_id.'"'); 
        $this->db->from('path_email_setting');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;
    }*/


    
    

//ends
    
function get_birthday_sms_template_format($branch_id="")
{
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_birthday_anniversary.*');
    $this->db->where('hms_birthday_anniversary.branch_id = "'.$branch_id.'"'); 
    $this->db->from('hms_birthday_anniversary');
    $query=$this->db->get()->row();
    //echo $this->db->last_query();
    return $query;

}

public function get_user_id($branch_id="")
{
    $this->db->select('users_id'); 
    $this->db->where('id',$branch_id); 
    $query = $this->db->get('hms_branch');
    $result = $query->row(); 
    return $result; 
}

public function get_ipd_referral_doctor($ipd_id='')
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_ipd_booking.referral_doctor'); 
    $this->db->where('hms_ipd_booking.id',$ipd_id);
    $query = $this->db->get('hms_ipd_booking');
    $result = $query->row(); 
    //echo $this->db->last_query(); 
    return $result; 
}

public function get_advance_payment_pay_mode_field($branch_id="",$ipd_id="",$section_id="",$type="")
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_payment_mode_field_value_acc_section.*'); 
    $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$ipd_id);
    if(!empty($branch_id))
    {
     $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$branch_id);   
    }
    else
    {
      $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);    
    }
    
    $this->db->where('hms_payment_mode_field_value_acc_section.section_id',$section_id);
    $this->db->where('hms_payment_mode_field_value_acc_section.type',$type);
    $query = $this->db->get('hms_payment_mode_field_value_acc_section');
    $result = $query->result(); 
   //echo $this->db->last_query(); 
    return $result; 
}

    function barcode_setting($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_branch_barcode.*');
        if(!empty($branch_id))
        {
            $this->db->where('hms_branch_barcode.branch_id = "'.$branch_id.'"'); 
        }
        else
        {
            $this->db->where('hms_branch_barcode.branch_id = "'.$users_data['parent_id'].'"');     
        }
        
        $this->db->from('hms_branch_barcode');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();
        return $query;
    }

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

    public function doctor_time($doctor_id="",$booking_date='')
    {
        $users_data = $this->session->userdata('auth_users');
        
        $this->db->select('hms_doctors_schedule.*,hms_days.day_name');
        $this->db->join('hms_doctors','hms_doctors.id = hms_doctors_schedule.doctor_id');
        $this->db->join('hms_days','hms_days.id = hms_doctors_schedule.available_day');
        $this->db->where('hms_doctors_schedule.doctor_id = "'.$doctor_id.'"');
        if(!empty($booking_date))
        {
            $date = date('Y-m-d',strtotime($booking_date));
            $day_name = date("l", strtotime($date));
            $this->db->where('hms_days.day_name = "'.$day_name.'"'); 
        }
         
        $query = $this->db->get('hms_doctors_schedule');
        //echo $this->db->last_query(); exit;
        $result = $query->result();    
        //echo $this->db->last_query(); 
        return $result;
    }

    public function doctor_slot($doctor_id="",$time_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('from_time as time1 ,to_time as time2');  
        if(!empty($doctor_id))
        {
            $this->db->where('doctor_id',$doctor_id); 
        }
        if(!empty($time_id))
        {
            $this->db->where('id',$time_id); 
        }
        //$this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_doctors_schedule');
        $result = $query->result(); 
        //echo $this->db->last_query(); exit;
        return $result;
    }

    function get_booked_slot($doctor_id='',$time_id='',$appointment_date='',$appointment_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('doctor_slot');  
        if(!empty($doctor_id))
        {
            $this->db->where('attended_doctor',$doctor_id); 
        }
        
        if(!empty($appointment_date))
        {
            $this->db->where('booking_date',date('Y-m-d',strtotime($appointment_date))); 
        }
        if(!empty($time_id))
        {
            $this->db->where('available_time',$time_id); 
        }
        if(!empty($appointment_id))
        {
            $this->db->where('id!=',$appointment_id); 
        }
        $this->db->where('type!=',3);  
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_opd_booking');
        $result = $query->result(); 
        //echo $this->db->last_query(); exit;
        return $result;      
    }

    
    
    function per_patient_time($doctor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('per_patient_timing'); 
        if(!empty($doctor_id))
        {
            $this->db->where('id',$doctor_id); 
        }
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result;
    }

  function get_units_by_id($unit_id="")
  {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('id'); 
        if(!empty($unit_id))
        {
            $this->db->where('id',$unit_id); 
        }
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_stock_item_unit');
        $result = $query->result();
        return $result;
        //echo $this->db->l
  }
    function get_units_by_item($item_id="")
  {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('path_item.*,path_item.id as item_id,hms_stock_item_unit.unit as first_unit,hms_second_unit.unit as second_unit_name,path_stock_category.category');  
            $this->db->join('hms_stock_item_unit','hms_stock_item_unit.id=path_item.unit_id','left');
            $this->db->join('hms_stock_item_unit as hms_second_unit','hms_second_unit.id=path_item.second_unit','left');

            $this->db->where('path_item.status','1'); 
            $this->db->order_by('path_item.item','ASC');
            $this->db->where('path_item.is_deleted',0);
            $this->db->where('path_item.id',$item_id);
            $this->db->where('path_item.branch_id',$users_data['parent_id']);  
            $this->db->join('path_stock_category`','path_stock_category.id=path_item.category_id','left');
            $query = $this->db->get('path_item');
            $result = $query->result();
            if(!empty($result))
            {
                 if(!empty($result[0]->first_unit))
                {
                $unit_first['first_name']= $result[0]->first_unit;
                $unit_first['id']= $result[0]->unit_id;
                }
                if(!empty($result[0]->second_unit_name))
                {
                $unit_second['first_name']=$result[0]->second_unit_name;
                $unit_second['id']=$result[0]->second_unit;
                }
                if(!empty($unit_second))
                {
                $unit_second_last[]=$unit_second;   
                }
                else
                {
                $unit_second_last[]=''; 
                }
                
                if(!empty($unit_first))
                {
                $unit_first_last[]=$unit_first;
                }
                else
                {
                $unit_first_last[]='';  
                }

                 $option[]=array_merge($unit_first_last,$unit_second_last);
              
                $option_value= array_unique($option);
                return  $option_value; 
            }
            
        //echo $this->db->l
  }
//pathology functions//
  function get_doctor_schedule_time($day_id='',$doctor_id='')
  {
        $this->db->select('hms_doctors_schedule.*');
        $this->db->where('hms_doctors_schedule.available_day = "'.$day_id.'"');
        $this->db->where('hms_doctors_schedule.doctor_id = "'.$doctor_id.'"');
        $this->db->from('hms_doctors_schedule');
        $result=$this->db->get()->result();
        return $result;
  }

    public function doctors_panel_charge($doctor_id='',$ins_company_id='',$branch_id='',$opd_type='')
    {
        $users_data = $this->session->userdata('auth_users');
        //print_r($users_data); exit;
        $this->db->select('hms_doctors.doctor_name,hms_doctor_panel_charge.charge,hms_doctor_panel_charge.emergency_charge');
        $this->db->from('hms_doctors');
        $this->db->join('hms_doctor_panel_charge','hms_doctor_panel_charge.doctor_id=hms_doctors.id  and hms_doctor_panel_charge.panel_id = "'.$ins_company_id.'"','left');
        if(!empty($doctor_id))
        {
           $this->db->where('hms_doctors.id',$doctor_id);  
        } 
        if(!empty($ins_company_id))
        {
           $this->db->where('hms_doctor_panel_charge.panel_id',$ins_company_id);  
        } 
        $this->db->where('hms_doctors.status','1'); 
        $this->db->where('hms_doctors.is_deleted',0);
        if(!empty($branch_id))
        {
            $this->db->where('hms_doctors.branch_id',$branch_id); 
        }
        else
        {
            $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
        }
        
        $result=$this->db->get()->result();
        
        if(!empty($result) && $opd_type==1)
        {
            $charges = $result[0]->emergency_charge; 
        }
        elseif(!empty($result))
        {
            $charges = $result[0]->charge;
           
        }
        else
        {
            $charges='0.00';
        }

        return $charges;
//echo $this->db->last_query(); exit;
        



    }


    public function branch_user_list($id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users.id,CONCAT(hms_employees.name,CONCAT(" (",hms_users.username,")")) AS name');
        $this->db->where('hms_users.status','1');
        $this->db->where('hms_users.is_deleted','0');
        
        $this->db->where('hms_users.emp_id>0');  
        $this->db->where('hms_users.users_role',2);
        $this->db->where('hms_users.parent_id',$users_data['parent_id']);
        
        $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id AND hms_employees.is_deleted=0','left');
        $this->db->order_by('hms_employees.name','ASC'); 
        $query = $this->db->get('hms_users');
        $result = $query->result();
        return $result; 
    }
    public function get_access_user($employee_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users_to_reprt_access_users.id,hms_users_to_reprt_access_users.users_id,hms_users_to_reprt_access_users.branch_id,hms_users_to_reprt_access_users.access_id'); 
        $this->db->where('hms_users_to_reprt_access_users.branch_id',$users_data['parent_id']);
        $this->db->where('hms_users_to_reprt_access_users.users_id',$employee_id);
        $query = $this->db->get('hms_users_to_reprt_access_users');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result; 

    }

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

   public function get_patient_address()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->where('is_deleted',0);
        $this->db->order_by('id','desc');
        $this->db->where('hms_patient.branch_id', $users_data['parent_id']);
        $query = $this->db->get('hms_patient');
        $result = $query->result(); 
        $data= array();
        if(!empty($result))
        {
           foreach($result as $res_address)
                {
                if(!empty($res_address->address))
                {
                    $address = trim ($res_address->address);
                    $data['address'][]=$address;    
                }
                if(!empty($res_address->address2))
                {
                    $address2 = trim ($res_address->address2);
                    $data['address'][]=$address2;    
                }
                 if(!empty($res_address->address3))
                {
                    $address3 = trim ($res_address->address2);
                    $data['address'][]=$address3;    
                }
                else
                 {
                    $data['address'][]="";      
                 }   
            }
            $data['new_address']= array_unique($data['address']);
         //echo $this->db->last_query();die;
         return $data['new_address'];  
        }
        
    }


    public function get_branch_address()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->where('is_deleted',0);
        $this->db->order_by('id','desc');
        $query = $this->db->get('hms_branch');
        $result = $query->result();
        //echo $this->db->last_query(); exit;
        //echo "<pre>";print_r($result); die; 
        $data= array();
        if(!empty($result))
        {
           foreach($result as $res_address)
                {
                if(!empty($res_address->address))
                {
                    $address = trim ($res_address->address);
                    
                     $abc1 = preg_replace("/[^0-9a-zA-Z -]/", "", strip_tags($address)); 
                     //print_r($abc); exit;
                     $data['address'][]=$abc1;   
                }
                if(!empty($res_address->address2))
                {
                    $address2 = trim ($res_address->address2);
                    //$data['address'][]=strip_tags($address2);

                    $abc2 = preg_replace("/[^0-9a-zA-Z -]/", "", strip_tags($address2)); 
                     //print_r($abc); exit;
                     $data['address'][]=$abc2;     
                }
                 if(!empty($res_address->address3))
                {
                    $address3 = trim ($res_address->address2);
                    //$data['address'][]=strip_tags($address3);    
                    $abc3 = preg_replace("/[^0-9a-zA-Z -]/", "", strip_tags($address3)); 
                     //print_r($abc); exit;
                     $data['address'][]=$abc3;
                }
            }
            if(!empty($data['address']))
            {
                 $new_address= array_unique($data['address']);
            }
            else
            {
                 $new_address= '';
            }
        
         return $new_address;  
        }
        
    }

    public function get_profile_print_status($module='',$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('path_print_test_name_setting.profile_status,path_print_test_name_setting.print_status'); 
        $this->db->where('path_print_test_name_setting.module',$module); 
        $this->db->where('path_print_test_name_setting.branch_id',$users_data['parent_id']);
        $query = $this->db->get('path_print_test_name_setting');
        $result = $query->row(); 
        //echo $this->db->last_query(); 
        return $result; 
    }
    
    public function check_unique_id($code="",$table_name="",$field_name="",$booking_type='',$opd_type='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where($field_name,$code);
        if(!empty($booking_type) && isset($booking_type))
        {
            $this->db->where($table_name.'.type',$booking_type);    
        }
        if($opd_type=='normal')
        {
            $this->db->where($table_name.'.opd_type',0);    
        }
        
        if($opd_type=='emergency')
        {
            $this->db->where($table_name.'.opd_type',1);    
        }
        
        
        
        if($field_name=='discharge_bill_no')
        {
            $this->db->where($table_name.'.discharge_status',1);
            $this->db->where($table_name.'.discharge_bill_no!=""');   
        } 

        if($table_name=='hms_branch')
        {
            
        }
        else
        {
            $this->db->where($table_name.'.branch_id',$users_data['parent_id']); 
        }


        $this->db->where($table_name.'.is_deleted!=2');
        $query = $this->db->get($table_name);
        $result = $query->result();
        //echo $this->db->last_query(); die;
        return $result;
    }

    public function get_last_unique_id($table_name="",$field_name="",$booking_type='',$opd_type='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        if(!empty($booking_type) && isset($booking_type))
        {   

            $this->db->where($table_name.'.type',$booking_type);    
        }
        if($opd_type=='normal')
        {
            $this->db->where($table_name.'.opd_type',0);    
        }
        if($opd_type=='emergency')
        {
            $this->db->where($table_name.'.opd_type',1);    
        }

        if($field_name=='discharge_bill_no')
        {
            $this->db->where($table_name.'.discharge_status',1);
            $this->db->where($table_name.'.discharge_bill_no!=""');   
            $this->db->order_by($table_name.'.ipd_discharge_created_date','desc');
        }
        else if($field_name=='issue_code')
        {
           $this->db->where($table_name.'.issue_code!=""');
           $this->db->order_by('id','desc');
        }
        else
        {
            $this->db->order_by('id','desc');
        }
        
        if($field_name=='branch_code')
        {
         $this->db->where($table_name.'.parent_id',$users_data['parent_id']); 
        }
        else
        {
         $this->db->where($table_name.'.branch_id',$users_data['parent_id']);     
        }

        //$this->db->where($table_name.'.branch_id',$users_data['parent_id']); 
        $this->db->where($table_name.'.is_deleted!=2'); 
        $this->db->limit(1);
        
        $query = $this->db->get($table_name);
        $result = $query->row_array();
        //echo $this->db->last_query();
        return $result;
    }

    public function check_unique_id20211129($code="",$table_name="",$field_name="",$booking_type='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where($field_name,$code);
        if(!empty($booking_type) && isset($booking_type))
        {
            $this->db->where($table_name.'.type',$booking_type);    
        }
        
        if($field_name=='discharge_bill_no')
        {
            $this->db->where($table_name.'.discharge_status',1);
            $this->db->where($table_name.'.discharge_bill_no!=""');   
        } 

        if($table_name=='hms_branch')
        {
            
        }
        else
        {
            $this->db->where($table_name.'.branch_id',$users_data['parent_id']); 
        }


        $this->db->where($table_name.'.is_deleted!=2');
        $query = $this->db->get($table_name);
        $result = $query->result();
        //echo $this->db->last_query(); die;
        return $result;
    }

    public function get_last_unique_id20211129($table_name="",$field_name="",$booking_type='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        if(!empty($booking_type) && isset($booking_type))
        {   

            $this->db->where($table_name.'.type',$booking_type);    
        }

        if($field_name=='discharge_bill_no')
        {
            $this->db->where($table_name.'.discharge_status',1);
            $this->db->where($table_name.'.discharge_bill_no!=""');   
            //$this->db->order_by($table_name.'.discharge_date','desc');
             $this->db->order_by($table_name.'.ipd_discharge_created_date','desc');
        }
        else if($field_name=='issue_code')
        {
           $this->db->where($table_name.'.issue_code!=""');
           $this->db->order_by('id','desc');
        }
        else
        {
            $this->db->order_by('id','desc');
        }
        
        if($field_name=='branch_code')
        {
         $this->db->where($table_name.'.parent_id',$users_data['parent_id']); 
        }
        else
        {
         $this->db->where($table_name.'.branch_id',$users_data['parent_id']);     
        }

        //$this->db->where($table_name.'.branch_id',$users_data['parent_id']); 
        $this->db->where($table_name.'.is_deleted!=2'); 
        $this->db->limit(1);
        
        $query = $this->db->get($table_name);
        $result = $query->row_array();
        //echo $this->db->last_query();
        return $result;
    }

    public function check_hospital_receipt_no($table_name="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get($table_name);
        $result = $query->result();
        return $result;
    }

    

   public function get_tot_hospital_receipt_no($table_name="",$pre_fix="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('reciept_prefix',$pre_fix);
        $this->db->order_by('id','desc');
        $query = $this->db->get($table_name);
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }

    
    public function get_under_maintenance()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',0); 
        $query = $this->db->get('hms_maintenance_page');
        $result = $query->result();
        return $result;
    }

    public function save_pathology_default()
    {
            $users_data = $this->session->userdata('auth_users');
            $post = $this->input->post();
            if(!empty($users_data['parent_id']))
            {
                $this->db->select('*');
                $this->db->from('path_report_default');
                $this->db->where('branch_id',$users_data['parent_id']);
                $query = $this->db->get();
                $result = $query->num_rows();
                if(!empty($result))
                {
                    $this->db->where('branch_id',$users_data['parent_id']);
                    $this->db->delete('path_report_default');
                }
            } 
            
            $default_search_data = json_encode($post);
                $default_data =  array(
                                'default_search_data'=>$default_search_data,
                                );
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
            $this->db->set('created_by',$users_data['id']);
            $this->db->set('branch_id',$users_data['parent_id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('path_report_default',$default_data);
           return $default_id = $this->db->insert_id();
    }
 public function get_group_name()
 {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('is_deleted!=2');
        $this->db->order_by('sort_order',"ASC");
        $query = $this->db->get('hms_charge_group_master');
        $result = $query->result();
        return $result;
 }
 
 public function get_ipd_particular_code($particular_id="")
 {
    $users_data = $this->session->userdata('auth_users');
    $result =array();
    if(!empty($particular_id))
    {
        $this->db->select('particular_code');
        $this->db->where('id',$particular_id);
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_perticular');
        $result = $query->row();

    }
    return $result;
}

public function check_employee_email($email="",$id="")
    {
        $this->db->select('*');  
        if(!empty($email))
        {
            $this->db->where('email',$email);
        }
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        }  
        $this->db->where('is_deleted!=2');
        $query = $this->db->get('hms_employees');
        $result = $query->result(); 
        return $result; 
    }
    
    public function total_users($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users.*'); 
        $this->db->where('hms_users.parent_id',$parent_id);
         $this->db->where('hms_users.users_role',2);
        $this->db->where('hms_users.is_deleted!= 2');        
        
        $query = $this->db->get('hms_users');

        $result = $query->num_rows(); 
        //echo $this->db->last_query(); exit;
        //echo $result;die;
        return $result; 
    }
    
      public function get_donar_comp_data($donor_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_extract_component.*'); 
        $this->db->where('hms_blood_extract_component.donor_id',$donor_id);
        $query = $this->db->get('hms_blood_extract_component');
        $result = $query->result(); 
        //echo $result;die;
        return $result;
    }
    // public function get_donar_blood_group($donor_id="")
    // {
    //     $users_data = $this->session->userdata('auth_users');
    //     $this->db->select('hms_blood_extract_component.*'); 
    //     $this->db->where('hms_blood_extract_component.donor_id',$donor_id);
    //     $query = $this->db->get('hms_blood_extract_component');
    //     $result = $query->result(); 
    //     return $result;
    // }

    public function get_donar_comp_data_by_id($comp_id="",$donor_id="")
    {
        // echo $comp_id;
        // echo '<br>';
        // echo $donor_id;die;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_extract_component.qty,hms_blood_extract_component.bar_code,hms_blood_extract_component.expiry_date,hms_blood_extract_component.component_price'); 
        $this->db->where('component_id',$comp_id);
        $this->db->where('donor_id',$donor_id);
        $query = $this->db->get('hms_blood_extract_component');
        $result = $query->row(); 
         return $result;
    }
     public function get_donar_bar_code_list($comp_id="",$donor_id="")
    {
        // echo $comp_id;
        // echo '<br>';
        // echo $donor_id;die;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_extract_component_bar_code.bar_code,hms_blood_extract_component_bar_code.id'); 
        $this->db->where('component_id',$comp_id);
        $this->db->where('donor_id',$donor_id);
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_blood_extract_component_bar_code');
        $result = $query->result(); 
         return $result;
    }
    public function get_stock_available($comp_id="",$donor_id="")
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

        // if((!empty($bar_code)) && ($bar_code!=""))           
        // {
        //     $this->db->like('bar_code',$bar_code);

        // }
        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->where('donor_id',$donor_id);

        }
       // $this->db->where('status','1');
        $query = $this->db->get('hms_blood_stock');
        return $query->row_array();
    }
    
    
    
     public function branch_alloted_module_permissionwise($branch_id="",$users_id="")
    {
        $permission_section_ids=array();
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('users_role',2);
        //$this->db->where('branch_id',$branch_id);
        $this->db->where('users_id',$users_id); 
        $query = $this->db->get('hms_permission_to_users');
        $result = $query->result(); 
        foreach($result as $permission_mod)
        {
        $permission_section_ids[]=$permission_mod->section_id;
        }

        $per_ids= array_unique($permission_section_ids);
        return $per_ids; 
    }

    public function branch_alloted_module_permissionwise_total_log($users_id="")
    {
        $permission_section_ids=array();
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('users_role',2);
        //$this->db->where('branch_id',$branch_id);
        $this->db->where('users_id',$users_id); 
        $query = $this->db->get('hms_permission_to_users');
        $result = $query->result(); 
        foreach($result as $permission_mod)
        {
        $permission_section_ids[]=$permission_mod->section_id;
        }

        $per_ids= array_unique($permission_section_ids);
        return $per_ids; 
    }



     

      public function get_total_entry_for_branch($branch_id="",$users_id="",$table="",$type="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*,COUNT(id) as count');  
        $this->db->where('branch_id',$users_id);
        $this->db->where('is_deleted',0); 
        if((!empty($type))&&($type!=0))
        {
            $this->db->where('type',$type);  
        }
        $this->db->order_by('id',"DESC"); 
        $this->db->limit('1'); 
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->result_array(); 
        return $result; 
    }


       public function get_total_entry_for_branch_opd($branch_id="",$users_id="",$type='',$table="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*,COUNT(id) as count');  
        $this->db->where('branch_id',$users_id);
        $this->db->where('is_deleted',0); 
        $this->db->where('type',$type);       
        $this->db->order_by('id',"DESC"); 
        $this->db->limit('1'); 
        $this->db->from($table);
        $query = $this->db->get();
        $result = $query->result_array(); 
        return $result; 
    }


     public function get_total_entry_for_branch_last_login($branch_id="",$users_id="",$table="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms.*,hms_branch.branch_name');  
        $this->db->join('hms_branch','hms_branch.users_id=hms.created_by','left');
        $this->db->where('hms.branch_id',$users_id);
        $this->db->where('hms.is_deleted',0); 
        $this->db->order_by('hms.id',"DESC"); 
        $this->db->limit('1'); 
        $this->db->from($table.' as hms');
        $query = $this->db->get();
        $result = $query->result_array(); 
        return $result; 
    }

        public function get_total_entry_for_branch_users($branch_id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users.*,COUNT(hms_users.id) as count,hms_employees.id');  
        $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id');
        $this->db->where('hms_employees.is_deleted',0);
        $this->db->where('hms_users.parent_id',$branch_id);
        $this->db->where('hms_users.emp_id > 0'); 
        $this->db->where('hms_users.is_deleted',0);  
        $this->db->from('hms_users');
        $query = $this->db->get();
        $result = $query->result_array(); 
        return $result; 
    }

        public function get_total_entry_for_branch_users_last($branch_id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users.*,hms_employees.id');  
        $this->db->join('hms_employees','hms_employees.id = hms_users.emp_id');
        $this->db->where('hms_employees.is_deleted',0);
        $this->db->where('hms_users.parent_id',$branch_id);
        $this->db->where('hms_users.emp_id > 0'); 
        $this->db->where('hms_users.is_deleted',0);  
        $this->db->from('hms_users');
        $this->db->order_by('hms_users.id',"DESC"); 
        $this->db->limit('1'); 

        $query = $this->db->get();
       //echo $this->db->last_query();
        //die;
        $result = $query->result_array(); 
        return $result; 
    }


    public function check_receipt_number($section_id="",$parent_id="")
    { 
        $users_data = $this->session->userdata('auth_users');
        $total='1';
        if($section_id=='1' || $section_id=='8')
        {   //opd
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            $this->db->where('type','2');
            //$this->db->limit(1);
            $this->db->order_by('id','desc');
            $query = $this->db->get('hms_opd_booking');
            $total = $query->num_rows();  
            //echo $this->db->last_query(); die;
        }

        elseif($section_id=='2' || $section_id=='12')
        {   //opd billing
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            $this->db->where('type','3');
            //$this->db->limit(1);
            $this->db->order_by('id','desc');
            $query = $this->db->get('hms_opd_booking');
            $total = $query->num_rows();
            //echo $this->db->last_query(); die;
        }
        elseif($section_id=='3' || $section_id=='7' || $section_id=='9' || $section_id=='14')//3,7,9,14
        {   //opd billing
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            //$this->db->where('type','3');
            //$this->db->limit(1);
            $this->db->order_by('id','desc');
            $query = $this->db->get('hms_ipd_booking');
            $total = $query->num_rows();
            //echo $this->db->last_query(); die;
        }
        elseif($section_id=='4' || $section_id=='11')
        {   //opd billing
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            //$this->db->where('type','3');
            //$this->db->limit(1);
            $this->db->order_by('id','desc');
            $query = $this->db->get('path_test_booking');
            $total = $query->num_rows();
            //echo $this->db->last_query(); die;
        }
         elseif($section_id=='5' || $section_id=='13')
        {   //vaccination
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            $this->db->order_by('id','desc');
            $query = $this->db->get('hms_vaccination_sale');
            $total = $query->num_rows();
            //echo $this->db->last_query(); die;
        }
        elseif($section_id=='6' || $section_id=='10')
        {   //medicine
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            $this->db->order_by('id','desc');
            $query = $this->db->get('hms_medicine_sale');
            $total = $query->num_rows();
            //echo $this->db->last_query(); die;
        }
        elseif($section_id=='15' || $section_id=='16')
        {   //medicine
            $this->db->select('*');
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('id',$parent_id);
            $this->db->where('is_deleted!=2');
            $this->db->order_by('id','desc');
            $query = $this->db->get('hms_operation_booking');
            $total = $query->num_rows();
            //echo $this->db->last_query(); die;
        }
        
        return $total;
    }
    //15,16 ot  17,18,19 blood bank 20 pad
    
    
     public function teeth_number_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->where('is_deleted',0);
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_dental_teeth_chart');
        $result = $query->result(); 
        return $result; 
    }
    
    public function get_last_hospital_receipt_no20180821($table_name="",$pre_fix="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']); 
        //$this->db->where('section_id',1);
        $this->db->where('reciept_prefix',$pre_fix);
        $this->db->limit(1);
        $this->db->order_by('id','desc');
        $query = $this->db->get($table_name);
        $result = $query->row_array();
        //echo $this->db->last_query();
        return $result;
    }
    
    public function get_last_hospital_receipt_no($table_name="",$pre_fix="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch_hospital_no.*');
        $this->db->where('hms_branch_hospital_no.branch_id',$users_data['parent_id']); 
        


        //$this->db->where('section_id',1);
        /* WHEN (hms_branch_hospital_no.section_id=1 OR hms_branch_hospital_no.section_id=8 OR hms_branch_hospital_no.section_id=2 OR hms_branch_hospital_no.section_id=12) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_no.parent_id)*/
        /*WHEN (hms_branch_hospital_no.section_id=1 OR hms_branch_hospital_no.section_id=8) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_no.parent_id AND type=2)

            WHEN (hms_branch_hospital_no.section_id=2 OR hms_branch_hospital_no.section_id=12) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_no.parent_id AND type=3)*/

        $this->db->where('hms_branch_hospital_no.temp_status>(CASE 
            WHEN (hms_branch_hospital_no.section_id=4 OR hms_branch_hospital_no.section_id=11) THEN (select is_deleted from path_test_booking where id=hms_branch_hospital_no.parent_id) 
             

            WHEN (hms_branch_hospital_no.section_id=1 OR hms_branch_hospital_no.section_id=8) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_no.parent_id AND type=2)

            WHEN (hms_branch_hospital_no.section_id=2 OR hms_branch_hospital_no.section_id=12) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_no.parent_id AND type=3)

            WHEN (hms_branch_hospital_no.section_id=3 OR hms_branch_hospital_no.section_id=9 OR hms_branch_hospital_no.section_id=7 OR hms_branch_hospital_no.section_id=14) THEN (select is_deleted from hms_ipd_booking where id=hms_branch_hospital_no.parent_id)  
            WHEN (hms_branch_hospital_no.section_id=6 OR hms_branch_hospital_no.section_id=10) THEN (select is_deleted from hms_medicine_sale where id=hms_branch_hospital_no.parent_id) 
            WHEN (hms_branch_hospital_no.section_id=15 OR hms_branch_hospital_no.section_id=16) THEN (select is_deleted from hms_operation_booking where id=hms_branch_hospital_no.parent_id) 
            WHEN (hms_branch_hospital_no.section_id=5 OR hms_branch_hospital_no.section_id=13) THEN (select is_deleted from hms_vaccination_sale where id=hms_branch_hospital_no.parent_id) 
            ELSE 0 END
            )');
        $this->db->where('hms_branch_hospital_no.reciept_prefix',$pre_fix);
        $this->db->limit(1);
        $this->db->order_by('hms_branch_hospital_no.reciept_suffix','desc');
        $query = $this->db->get('hms_branch_hospital_no');
        //echo $this->db->last_query(); exit;
        $result = $query->row_array();
        
        return $result;
    }
    
    /* ipd discharge time settings */
    public function get_ipd_discharge_time_setting_value()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ipd_branch_discharge_bill_print_setting.discharge_format'); 
        $this->db->where('hms_ipd_branch_discharge_bill_print_setting.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_ipd_branch_discharge_bill_print_setting');
        $result = $query->row(); 
        //echo $this->db->last_query(); 
        return $result; 
    
    }
    
    
    public function get_sample_type_for_test($test_id='',$vals='')
    {
        $response = '';
        if(!empty($test_id))
        {
            $users_data = $this->session->userdata('auth_users'); 
            $this->db->select('path_default_vals.*');  
            
            $this->db->where('path_default_vals.status','1'); 
            $this->db->where('path_default_vals.highlight','1');
            /*$this->db->where('path_default_vals.highlight!=','0'); 
            $this->db->where('path_default_vals.highlight!=','2'); 
            $this->db->where('path_default_vals.highlight!=','3'); */
            $this->db->order_by('path_default_vals.default_vals','ASC');
            $this->db->where('path_default_vals.is_deleted',0);
            //$this->db->where('path_default_vals.default_vals LIKE "'.$vals.'%"');
            $this->db->where('path_default_vals.branch_id',$users_data['parent_id']);  
            $query = $this->db->get('path_default_vals');
            $result = $query->result(); 
            

        $this->db->select("path_default_vals.*"); 
        $this->db->from('path_default_vals');
        $this->db->join('path_default_val_to_test','path_default_val_to_test.default_val_id = path_default_vals.id');

        //$this->db->where('path_default_val_to_test.highlight_type',2);
        //$this->db->where('path_default_val_to_test.highlight_type!=',3);
        $this->db->where('path_default_val_to_test.test_id',$test_id);
        
        $this->db->group_by('path_default_vals.id');
        $result_2= $this->db->get()->result();
        //echo $this->db->last_query();
        //echo "<pre>"; print_r($result_2); exit;
            $response =array();
            if(!empty($result))
            { 
                foreach($result as $vals)
                {
                   $response[] = $vals->default_vals;
                }
            }

            if(!empty($result_2))
            { 
                foreach($result_2 as $vals)
                {
                   $response[] = $vals->default_vals;
                }
            }

            return $response; 
        } 
    }
    
    /* ipd dicharge time settings */
    public function get_doctor_qualifications($doctor_id='')
    {
        $doctor_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($doctor_id))
        {
            $this->db->select('hms_doctors.qualification');  
            $this->db->where('hms_doctors.id',$doctor_id); 
            $this->db->where('is_deleted !=2');
            $query = $this->db->get('hms_doctors');
            $result = $query->row(); 
            if(!empty($result->qualification))
            {
                $qualification = $result->qualification;    
            }
        }    
            return $qualification; 
      
    }
    
    
    
///////////////////////////////////medicince amount sale purchase

    function get_sales_amount($start_date='')
    {
        //echo $start_date; exit;
        $amount=0;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('sum(hms_medicine_sale.paid_amount) as amount');  
        $this->db->where('hms_medicine_sale.branch_id',$users_data['parent_id']); 
        if(!empty($start_date))
        {
            $start_date1 = date('Y-m-d',strtotime($start_date)).' 00:00:00';
            $this->db->where('hms_medicine_sale.sale_date >= "'.$start_date1.'"');
            $end_date = date('Y-m-d',strtotime($start_date)).' 23:59:59';
            $this->db->where('hms_medicine_sale.sale_date <= "'.$end_date.'"');

        }
        $this->db->where('hms_medicine_sale.is_deleted','0');
        $this->db->from('hms_medicine_sale'); 
        $query = $this->db->get(); 
        $result =  $query->row();
        if(!empty($result->amount))
        {
            $amount = $result->amount;    
        }
        return $amount; 
        //echo $this->db->last_query();
        //echo "<pre>"; print_r($result); exit;
    }

    function get_sales_return_amount($start_date='')
    {
        //echo $start_date; exit;
        $amount=0;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('sum(hms_medicine_sale_return.paid_amount) as amount');  
        $this->db->where('hms_medicine_sale_return.branch_id',$users_data['parent_id']); 
        if(!empty($start_date))
        {
            $start_date1 = date('Y-m-d',strtotime($start_date)).' 00:00:00';
            $this->db->where('hms_medicine_sale_return.sale_date >= "'.$start_date1.'"');
            $end_date = date('Y-m-d',strtotime($start_date)).' 23:59:59';
            $this->db->where('hms_medicine_sale_return.sale_date <= "'.$end_date.'"');

        }
        $this->db->where('hms_medicine_sale_return.is_deleted','0');
        $this->db->from('hms_medicine_sale_return'); 
        $query = $this->db->get(); 
        $result =  $query->row();
        if(!empty($result->amount))
        {
            $amount = $result->amount;    
        }
        //echo $this->db->last_query(); exit;
        return $amount; 
        //echo $this->db->last_query();
        //echo "<pre>"; print_r($result); exit;

    }


    function get_purchase_amount($start_date='')
    {
        //echo $start_date; exit;
        $amount=0;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('sum(hms_medicine_purchase.paid_amount) as amount');  
        $this->db->where('hms_medicine_purchase.branch_id',$users_data['parent_id']); 
        if(!empty($start_date))
        {
            $start_date1 = date('Y-m-d',strtotime($start_date)).' 00:00:00';
            $this->db->where('hms_medicine_purchase.purchase_date >= "'.$start_date1.'"');
            $end_date = date('Y-m-d',strtotime($start_date)).' 23:59:59';
            $this->db->where('hms_medicine_purchase.purchase_date <= "'.$end_date.'"');

        }
         $this->db->where('hms_medicine_purchase.is_deleted','0');
        $this->db->from('hms_medicine_purchase'); 
        $query = $this->db->get(); 
        $result =  $query->row();
        if(!empty($result->amount))
        {
            $amount = $result->amount;    
        }
        return $amount; 
        //echo $this->db->last_query();
        //echo "<pre>"; print_r($result); exit;
    }

    function get_purchase_return_amount($start_date='')
    {
        $amount=0;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('sum(hms_medicine_return.paid_amount) as amount');  
        $this->db->where('hms_medicine_return.branch_id',$users_data['parent_id']); 
        if(!empty($start_date))
        {
            $start_date1 = date('Y-m-d',strtotime($start_date)).' 00:00:00';
            $this->db->where('hms_medicine_return.purchase_date >= "'.$start_date1.'"');
            $end_date = date('Y-m-d',strtotime($start_date)).' 23:59:59';
            $this->db->where('hms_medicine_return.purchase_date <= "'.$end_date.'"');

        }
         $this->db->where('hms_medicine_return.is_deleted','0');
        $this->db->from('hms_medicine_return'); 
        $query = $this->db->get(); 
        $result =  $query->row();
        if(!empty($result->amount))
        {
            $amount = $result->amount;    
        }
        return $amount; 
    }

    function get_vendor_payment_amount($start_date='')
    {
        $amount=0;
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('sum(hms_expenses.paid_amount) as amount');  
        $this->db->where('hms_expenses.branch_id',$users_data['parent_id']); 
        if(!empty($start_date))
        {
            $start_date1 = date('Y-m-d',strtotime($start_date)).' 00:00:00';
            $this->db->where('hms_expenses.created_date >= "'.$start_date1.'"');
            $end_date = date('Y-m-d',strtotime($start_date)).' 23:59:59';
            $this->db->where('hms_expenses.created_date <= "'.$end_date.'"');

        }
         $this->db->where('hms_expenses.type IN(2,3)');
         $this->db->where('hms_expenses.is_deleted','0');
        $this->db->from('hms_expenses'); 
        $query = $this->db->get(); 
        $result =  $query->row();
        if(!empty($result->amount))
        {
            $amount = $result->amount;    
        }
        return $amount; 
    }
    
    
    ///ipd prescription

    function get_ipd_prescription_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_prescription_tab_setting.*,hms_ipd_prescription_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_prescription_tab_setting','hms_ipd_branch_prescription_tab_setting.unique_id = hms_ipd_prescription_tab_setting.id');
            $this->db->where('hms_ipd_branch_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_ipd_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_ipd_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_prescription_medicine_tab_setting.*,hms_ipd_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_prescription_medicine_tab_setting','hms_ipd_branch_prescription_medicine_tab_setting.unique_id = hms_ipd_prescription_medicine_tab_setting.id');
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_ipd_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }


    function get_ipd_prescription_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_prescription_tab_setting.*,hms_ipd_prescription_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_prescription_tab_setting','hms_ipd_branch_prescription_tab_setting.unique_id = hms_ipd_prescription_tab_setting.id');
            $this->db->where('hms_ipd_branch_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_prescription_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_prescription_tab_setting.print_status',1);
            $query = $this->db->get('hms_ipd_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_ipd_prescription_medicine_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_prescription_medicine_tab_setting.*,hms_ipd_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_prescription_medicine_tab_setting','hms_ipd_branch_prescription_medicine_tab_setting.unique_id = hms_ipd_prescription_medicine_tab_setting.id');
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.print_status',1);
            $this->db->where('hms_ipd_branch_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_ipd_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
    
    public function total_purchase_estimate($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_estimate_purchase.*'); 
        //$this->db->where('hms_medicine_purchase.branch_id',$parent_id); 
        $this->db->where('hms_estimate_purchase.is_deleted !=2');
        $this->db->where('hms_estimate_purchase.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_estimate_purchase.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "49" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_estimate_purchase');
        $result = $query->num_rows(); 
        return $result; 
    }

    public function total_sale_estimate($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_estimate_sale.*'); 
        $this->db->where('hms_estimate_sale.is_deleted !=2');
        //$this->db->where('hms_medicine_sale.branch_id',$parent_id); 
        $this->db->where('hms_estimate_sale.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_estimate_sale.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "48" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_estimate_sale');
        $result = $query->num_rows(); 
        return $result; 
    }
   
   
   // get department name by pathology 26/07/2019
    public function get_pathology_department($dept_id="",$branch_id='')
    {
        $this->db->select("hms_department.department");
        $this->db->where('hms_department.is_deleted',0);
        $this->db->from('hms_department'); 
         $this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id AND hms_department_to_department_status.branch_id="'.$branch_id.'"','left');
        $this->db->where('(hms_department.branch_id='.$branch_id.' OR hms_department.branch_id=0)'); 
        $this->db->where('hms_department.id',$dept_id);
        $this->db->where('hms_department_to_department_status.status','1');   
        $query = $this->db->get();
       // echo $this->db->last_query();die();
        $result = $query->result(); 
        return $result; 
    }
    
    
    public function get_last_hospital_mlc_no($table_name="",$pre_fix="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_branch_hospital_mlc_no.*');
        $this->db->where('hms_branch_hospital_mlc_no.branch_id',$users_data['parent_id']); 
    
        $this->db->where('hms_branch_hospital_mlc_no.temp_status>(CASE 
            WHEN (hms_branch_hospital_mlc_no.section_id=4 OR hms_branch_hospital_mlc_no.section_id=11) THEN (select is_deleted from path_test_booking where id=hms_branch_hospital_mlc_no.parent_id) 
             

            WHEN (hms_branch_hospital_mlc_no.section_id=1 OR hms_branch_hospital_mlc_no.section_id=8) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_mlc_no.parent_id AND type=2)

            WHEN (hms_branch_hospital_mlc_no.section_id=2 OR hms_branch_hospital_mlc_no.section_id=12) THEN (select is_deleted from hms_opd_booking where id=hms_branch_hospital_mlc_no.parent_id AND type=3)

            WHEN (hms_branch_hospital_mlc_no.section_id=3 OR hms_branch_hospital_mlc_no.section_id=9 OR hms_branch_hospital_mlc_no.section_id=7 OR hms_branch_hospital_mlc_no.section_id=14) THEN (select is_deleted from hms_ipd_booking where id=hms_branch_hospital_mlc_no.parent_id)  
            WHEN (hms_branch_hospital_mlc_no.section_id=6 OR hms_branch_hospital_mlc_no.section_id=10) THEN (select is_deleted from hms_medicine_sale where id=hms_branch_hospital_mlc_no.parent_id) 
            WHEN (hms_branch_hospital_mlc_no.section_id=15 OR hms_branch_hospital_mlc_no.section_id=16) THEN (select is_deleted from hms_operation_booking where id=hms_branch_hospital_mlc_no.parent_id) 
            WHEN (hms_branch_hospital_mlc_no.section_id=5 OR hms_branch_hospital_mlc_no.section_id=13) THEN (select is_deleted from hms_vaccination_sale where id=hms_branch_hospital_mlc_no.parent_id) 
            ELSE 0 END
            )');
        $this->db->where('hms_branch_hospital_mlc_no.reciept_prefix',$pre_fix);
        $this->db->limit(1);
        $this->db->order_by('hms_branch_hospital_mlc_no.reciept_suffix','desc');
        $query = $this->db->get('hms_branch_hospital_mlc_no');
        //echo $this->db->last_query(); exit;
        $result = $query->row_array();
        
        return $result;
    }
     public function check_hospital_mlc_no($table_name="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get($table_name);
        $result = $query->result();
        return $result;
    }
    public function get_tot_hospital_mlc_no($table_name="",$pre_fix="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->where('reciept_prefix',$pre_fix);
        $this->db->order_by('id','desc');
        $query = $this->db->get($table_name);
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }
    
    function payment_ipd_mode_detail_according_to_field($p_mode_id="",$parent_id="",$section='',$type='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

        $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.type',$type);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',$section);
        $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
//echo $this->db->last_query(); exit;
        return $query;
    }
    
    
    /* For Charge type code */
    public function get_charge_type_detail($charge_type_id="",$room_type_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('charge_code');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('room_charge_type_id',$charge_type_id);
        $this->db->where('room_type_id',$room_type_id);
        $this->db->where('types',0);
        $this->db->group_by('room_charge_type_id');
         $this->db->order_by('id','DESC');
        $query = $this->db->get('hms_ipd_room_charge');
        //echo $this->db->last_query();die;
        $result = $query->row();
        return $result;
    }
    //partograph
    function get_partograph_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_partograph_tab_setting.*,hms_partograph_tab_setting.var_title');   
            $this->db->join('hms_branch_partograph_tab_setting','hms_branch_partograph_tab_setting.unique_id = hms_partograph_tab_setting.id');
            $this->db->where('hms_branch_partograph_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_partograph_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_partograph_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_partograph_tab_setting.status',1);
            $query = $this->db->get('hms_partograph_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
    
      //Device id of patient
  public function get_device_detail($users_id='')
  {
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_user_devices.device_token,hms_user_devices.device_type,hms_user_devices.device_id');
    $this->db->where('hms_user_devices.users_id',$users_id);
    $this->db->where('hms_user_devices.branch_id',$users_data['parent_id']);
    //$this->db->where('hms_user_devices.login_status',1);
    $this->db->from('hms_user_devices');
    $query = $this->db->get();
    return $query->result();
  }

  //user id for patient


  public function get_users_details($patient_id='')
  {
        $id='';
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_users.id');
        $this->db->where('hms_users.parent_id',$patient_id);
        $this->db->where('hms_users.users_role',4);
        $this->db->from('hms_users');
        $query = $this->db->get();
        $result = $query->row(); 
        //echo "<pre>"; print_r($result); exit;
        if(!empty($result->id))
        {
            $id = $result->id;    
        }
        return $id;
  }
  
  public function get_medicine_price($medicine_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        if(!empty($medicine_id))
        {
            $this->db->select('hms_medicine_stock.mrp');
            if(!empty($medicine_id))
            { 
            $this->db->where('hms_medicine_stock.m_id',$medicine_id);
            } 
            $this->db->where('hms_medicine_stock.branch_id',$users_data['parent_id']); 
            $this->db->order_by('hms_medicine_stock.id',DESC);
            $this->db->limit(1);
            $query = $this->db->get('hms_medicine_stock');
            $result = $query->result(); 
            return $result[0]->mrp; 
    

        }
        return 0;
    }
    
    
    public function total_ambulance_booking($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_ambulance_booking.*'); 
        $this->db->where('hms_ambulance_booking.branch_id',$parent_id); 
        //$this->db->where('branch_id',$users_data['parent_id']);
        //$this->db->where('is_deleted !=2');
        //$this->db->where('type',2);
        if(!empty($prefix))
        {
          $this->db->where('hms_ambulance_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "53" AND  branch_id = "'.$parent_id.'")');
        }
        $query = $this->db->get('hms_ambulance_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 

        
    }
    
     public function type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('type','ASC');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_ambulance_vehicle_type');
        $result = $query->result(); 
        return $result; 
    }
    
    public function check_inventory_company($company="",$id="")
    {
        $this->db->select('*');  
        if(!empty($company))
        {
            $this->db->where('company_name',$company);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $this->db->where('is_deleted!=2');
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_inventory_company');
        $result = $query->result(); 
        return $result; 
    }
    
    function get_patient_ipd_disc_rec($ipd_id="",$patient_id='',$section_id='',$type='') 
    {   
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select("(select reciept_prefix from hms_branch_hospital_no where parent_id = hms_ipd_booking.id AND section_id = '3' AND branch_id = ".$users_data['parent_id']." limit 1) as bill_reciept_prefix,(select reciept_suffix from hms_branch_hospital_no where parent_id = hms_ipd_booking.id AND section_id = '3' AND branch_id = ".$users_data['parent_id']." limit 1) as bill_reciept_suffix");
        $this->db->from('hms_patient');
        
        if(!empty($ipd_id))
        {
            $this->db->where('hms_ipd_booking.id',$ipd_id);    
        }
        $this->db->join('hms_ipd_booking','hms_ipd_booking.patient_id=hms_patient.id','left');
        $query = $this->db->get(); 
        //echo $this->db->last_query(); exit;
        return $query->row_array();
    }
    
    function get_patient_according_to_day_care($id) 
    {   
       $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_day_care_booking.*,hms_day_care_booking.policy_no as polocy_no,hms_day_care_booking.insurance_type_id as insurance_type, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.dob,hms_patient.modified_date as patient_modified_date');

        $this->db->from('hms_day_care_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_day_care_booking.patient_id'); 
        $this->db->where('hms_day_care_booking.branch_id',$user_data['parent_id']); 
        $this->db->where('hms_day_care_booking.id',$id);
        $this->db->where('hms_day_care_booking.is_deleted','0');
        
        $query = $this->db->get(); 
   //echo $this->db->last_query(); exit;
        return $query->row_array();
    }
    
   public function total_day_care_booking($parent_id="",$prefix='')
  {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_day_care_booking.*'); 
        $this->db->where('hms_day_care_booking.branch_id',$parent_id); 
        $this->db->where('is_deleted !=2');
        $this->db->where('type',5);
        if(!empty($prefix))
        {
          $this->db->where('hms_day_care_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "9" AND  branch_id = "'.$parent_id.'")');
        }
        $query = $this->db->get('hms_day_care_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();
        return $result; 
  }
  
   public function total_opd_booking_discharged($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_day_care_booking.*'); 
        $this->db->where('branch_id',$parent_id);
        $this->db->where('discharge_status',1); 
        $this->db->where('is_deleted !=2'); 
        if(!empty($prefix))
        { 
         $this->db->where('hms_day_care_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "60" AND  branch_id = "'.$parent_id.'" )');  
     }  
     $query = $this->db->get('hms_day_care_booking');
     $result = $query->num_rows(); 
            //echo $this->db->last_query();die;
     return $result; 
    }
  
  function get_particular_details_opd($opd_id="",$patient_id='')
    {
        $this->db->select('hms_opd_charge_entry.id as charge_id,hms_opd_charge_entry.particular_id as particular,hms_opd_charge_entry.particular as particulars,hms_opd_charge_entry.price as charges,(select sum(price) from hms_opd_charge_entry as charges where charges.id = hms_opd_charge_entry.id) as amount,hms_opd_charge_entry.quantity,hms_opd_charge_entry.start_date as s_date');
        $this->db->from('hms_opd_charge_entry');
        if(!empty($patient_id))
        {
            $this->db->where('hms_opd_charge_entry.patient_id',$patient_id);    
        } 
        if(!empty($opd_id))
        {
            $this->db->where('hms_opd_charge_entry.opd_id',$opd_id);    
        }
        $this->db->where('hms_opd_charge_entry.type',5);
        $this->db->where('hms_opd_charge_entry.is_deleted',0);
        
        $query = $this->db->get()->result_array(); 
       // echo $this->db->last_query();die;
      // print '<pre>'; print_r( $query);die;
        return $query;
    }
    
    function get_patient_according_to_opd($id) 
    {   
       $user_data = $this->session->userdata('auth_users');
        $this->db->select('hms_day_care_booking.*,hms_day_care_booking.policy_no as polocy_no,hms_day_care_booking.insurance_type_id as insurance_type, hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id,hms_patient.adhar_no, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.dob,hms_patient.modified_date as patient_modified_date');

        $this->db->from('hms_day_care_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_day_care_booking.patient_id'); 
        $this->db->where('hms_day_care_booking.branch_id',$user_data['parent_id']); 
        $this->db->where('hms_day_care_booking.id',$id);
        $this->db->where('hms_day_care_booking.is_deleted','0');
        
        $query = $this->db->get(); 
   //echo $this->db->last_query(); exit;
        return $query->row_array();
    }
    
    
    function expiry_notice()
    {
         $user_data = $this->session->userdata('auth_users');
         $current_date=date('Y-m-d');
         $to=date('Y-m-d',strtotime('+7 days',strtotime($current_date)));
         $this->db->select('end_date');
         $this->db->where('users_id',$user_data['id']);
         $where="DATE(end_date) BETWEEN '$current_date' AND '$to' ";
         $this->db->where($where);
         $this->db->from('hms_branch');
         $query=$this->db->get();
         return $query->row();

    }
    
    
    
  public function total_opd_emergency_booking($parent_id,$prefix)
  {
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_opd_booking.*'); 
    $this->db->where('hms_opd_booking.branch_id',$parent_id); 
     $this->db->where('hms_opd_booking.opd_type',1);
    $this->db->where('type',2);
    if(!empty($prefix))
    {
      $this->db->where('hms_opd_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "56" AND  branch_id = "'.$parent_id.'")');
     
  }
  $query = $this->db->get('hms_opd_booking');
  //echo $this->db->last_query(); exit;
  $result = $query->num_rows(); 
  return $result;  
  }
  
  ///////////////
   public function get_holiday_month_count_data()
    {   $users_data = $this->session->userdata('auth_users');
        $date_get=date('Y-m-1');
        $to_date_get=date('Y-m-31');
        $this->db->select('prl_holiday_master.*');
        $this->db->from('prl_holiday_master');
       if(isset($date_get) && !empty($date_get))
        {

            $this->db->where('from_date BETWEEN "'.$date_get. '" and "'.$to_date_get.'"');
        }
        if(isset($to_date_get) && !empty($to_date_get))
        {
            $this->db->where('end_date BETWEEN "'.$date_get. '" and "'.$to_date_get.'"');        
        } 
        $this->db->where('prl_holiday_master.status','1');
        $this->db->where("branch_id",$users_data['parent_id']);
        //$this->db->order_by(asc);
        $res= $this->db->get()->result();
      // echo $this->db->last_query();
     //   die;
        return $res;
    }
    
    public function get_emp_present_absent_count($type)
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("prl_attendence.*");
        $this->db->from("prl_attendence");
        $this->db->join("hms_employees", "hms_employees.id=prl_attendence.emp_id", "left");
        $this->db->where('hms_employees.status','1');
        $this->db->where('prl_attendence.is_deleted','0');
        $this->db->where("prl_attendence.branch_id",$users_data['parent_id']);
        if($type==1)
        {
            $this->db->where("prl_attendence.leave_status",$type);
        }
        else if($type==2)
        {
            $this->db->where("prl_attendence.leave_status",$type);
        }
        else if($type==0)
        {
            $this->db->where("prl_attendence.leave_status",$type);
        }
        $this->db->like('prl_attendence.attendance_date', date('Y-m-d'));
        $this->db->group_by("prl_attendence.emp_id");
        $result = $this->db->get();
        //echo $this->db->last_query();
        if($result->num_rows()>0)
        {
            $result = $result->result();
        }
        else
        {
            $result = "empty";
        }
        
        return $result; 
    }
    
     public function get_employee_data_exit()
    {
        $users_data=$this->session->userdata('auth_users');
       // print_r($users_data);die;
        $date_get=date('Y-m-1');
        $to_date_get=date('Y-m-31');
        $date_get_val = explode('-',$date_get);  
        $to_date_get_val = explode('-',$to_date_get); 
        $this->db->select('prl_full_and_final.*');
        $this->db->from('prl_full_and_final');
       if(isset($date_get_val) && !empty($date_get_val))
        {

            
                $this->db->where('MONTH(resignation_date) >= "'.$date_get_val[1].'"');
                $this->db->where('YEAR(resignation_date) >= "'.$date_get_val[0].'"');
            
        }
        if(isset($to_date_get_val) && !empty($to_date_get_val))
        {
            
                $this->db->where('MONTH(resignation_date) <= "'.$to_date_get_val[1].'"');
                $this->db->where('YEAR(resignation_date) >= "'.$date_get_val[0].'"');
            
        } 
        $this->db->where('branch_id',$users_data['parent_id']);
        //$this->db->order_by(asc);
        $res= $this->db->get()->result();
       // echo $this->db->last_query(); die;
        return $res;
    }
    
    public function get_join_current_month_count()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("*");
        $this->db->from("hms_employees");
        $this->db->where("branch_id",$users_data['parent_id']);
        $this->db->like('joining_date', date('Y-m'));
        $this->db->where("status","1");
        $this->db->where('is_deleted','0');
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }
    
    public function get_total_employee_count()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("*");
        $this->db->from("hms_employees");
        $this->db->where("branch_id",$users_data['parent_id']);
        $this->db->where("status","1");
        $this->db->where('is_deleted','0');
        $result = $this->db->get();
        $result = $result->result();
        return $result;
    }
    
    public function referal_doctor_list($branch_id="")
    {
    $users_data = $this->session->userdata('auth_users'); 
    $this->db->select('hms_doctors.id,hms_doctors.doctor_name');   
    $this->db->order_by('hms_doctors.doctor_name','ASC');
    $this->db->where('hms_doctors.is_deleted',0); 
    $this->db->where('hms_doctors.doctor_type IN (0,2)');
    $this->db->where('hms_doctors.branch_id',$users_data['parent_id']); 
    $query = $this->db->get('hms_doctors');
    $result = $query->result(); 
    //echo $this->db->last_query(); 
    return $result; 
    }
    
    
     public function attended_doctor_list($branch_id='',$specialization='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
        if(!empty($specialization))
        {
            $this->db->where('specilization_id',$specialization); 
        }
        if(!empty($branch_id))
        {
            $this->db->where('hms_doctors.branch_id',$branch_id);
        }
        else
        {
            $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);
        }  
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result; 
    } 

public function get_department_name($department_id='')
    {
        $department_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($department_id))
        {
            $this->db->select('hms_department.department as department_name');  
            $this->db->where('hms_department.id',$department_id); 
            
            $query = $this->db->get('hms_department');
            $result = $query->row(); 
            if(!empty($result->department_name))
            {
                $department_name = $result->department_name;    
            }
            
            //echo $this->db->last_query();die;
        }    
            return $department_name; 
      
    }
    
    public function get_opd_particular_name($particular_id="")
     {
        $users_data = $this->session->userdata('auth_users');
        $result ='';
        if(!empty($particular_id))
        {
            $this->db->select('particular');
            $this->db->where('id',$particular_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $query = $this->db->get('hms_opd_particular');
            $result = $query->row();
            //echo $this->db->last_query();die;
    
        }
        return $result;
    }
    
    
    public function get_doc_hos_comission($doctor_id='0',$hospital_id='0',$price='0',$dept_id='0')
     {    
        if(!empty($doctor_id) && !empty($dept_id))
        {
            $this->db->select('*');
            $this->db->where('dept_id',$dept_id);
            $this->db->where('doctor_id',$doctor_id);
            $query = $this->db->get('hms_doctors_to_comission');
            //echo $this->db->last_query();die;
            $result = $query->row_array(); 
            
            return $result;
        }
        if(!empty($hospital_id) && !empty($dept_id))
        {
            $this->db->select('*');
            $this->db->where('dept_id',$dept_id);
            $this->db->where('hospital_id',$hospital_id);
            $query = $this->db->get('hms_hospital_to_comission');
            $result = $query->row_array(); 
            return $result;
        } 
    }


public function test_head_list($test_head_id)
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_name,test_code');  
    $this->db->where('dept_id','119'); 
    $this->db->where('test_head_id',$test_head_id); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}

//eye advance
public function eye_region()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_heads');  
    $this->db->where('dept_id','119'); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('path_test_heads');
    $result = $query->result(); 
    //echo $this->db->last_query(); exit;
    return $result; 
}

public function lab_test_list()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_name,test_code');  
    $this->db->where('dept_id',122); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('path_test');
    $result = $query->result(); 
       // echo $this->db->last_query();die;

    return $result; 
}
public function ophthal_set()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,ophthal_set_name'); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('hms_std_ophthal_set');
    $result = $query->result(); 
    return $result; 
}

public function lab_set()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,set_name'); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('hms_std_lab_set');
    $result = $query->result(); 
    return $result; 
}
public function radiology_set()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,set_name'); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('hms_std_radiology_set');
    $result = $query->result(); 
    return $result; 
}

public function xray_mri_investigation()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_name,test_code');  
    $this->db->where('branch_id',$users_data['parent_id']); 
    $this->db->where('dept_id',122);
    //$this->db->where("(test_head_id='1327' OR test_head_id='1328')", NULL, FALSE);
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}

public function icd_eye_section()
{
 $this->db->select('*');
 $this->db->where('chapter',7); 
 $this->db->from('hms_icd10section');
 $query=$this->db->get();
 return $query->result_array(); 
}

public function custom_made_icd()
{
    $this->db->select('*');
    $this->db->from('hms_custom_icds'); 
    $query=$this->db->get(); 
    return $query->result_array(); 
}

public function procedure_eye_region()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('id,test_heads');  
        $this->db->where('dept_id','118'); 
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_test_heads');
        $result = $query->result(); 
        return $result; 
    }
    
    public function procedure_test_list($test_head_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('id,test_name,test_code');  
        $this->db->where('dept_id','118'); 
        $this->db->where('test_head_id',$test_head_id); 
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('path_test');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        return $result; 
    }
    
    public function icd_dropdownlist($icd_id="")
    {
        $min=6;
        $max=8;
     $this->db->select('*');
     $this->db->where('chapter',7); 
     $where="CHAR_LENGTH(icd_id)< $max  AND CHAR_LENGTH(icd_id) > $min";
     $this->db->where($where);
     $this->db->like('icd_id',$icd_id); 
     $this->db->from('hms_icd10'); 
     $query=$this->db->get(); 
   //  echo $this->db->last_query();die;
     return $query->result_array(); 
    }
    
    public function x_ray_test_list()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_name,test_code');  
    $this->db->where('dept_id',122);
    //$this->db->where('test_head_id',1327);
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}
public function mri_test_list()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_name,test_code');  
    $this->db->where('dept_id',122); 
    //$this->db->where('test_head_id',1328); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}

public function ophthal_alltest_list()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('id,test_name,test_code');  
    $this->db->where('dept_id','119'); 
       // $this->db->where('test_head_id',$test_head_id); 
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('path_test');
    $result = $query->result(); 
    return $result; 
}

//advance eye  


    public function get_transaction_no($booking_id='',$payment_mode_id='')
    {
        $doctor_name="";
        $users_data = $this->session->userdata('auth_users');
        if(!empty($booking_id))
        {
            $this->db->select('hms_payment_mode_field_value_acc_section.field_value');  
            $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$booking_id);
            
            $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$payment_mode_id);
            
             $this->db->limit(1);
            $query = $this->db->get('hms_payment_mode_field_value_acc_section');
            $result = $query->row(); 
            if(!empty($result->field_value))
            {
                $field_value = $result->field_value;    
            }
            
            //echo $this->db->last_query();die;
        }    
            return $field_value; 
      
    }
    
    public function get_transaction_id($booking_id,$section_id,$type)
    {
        
        $user_data=$this->session->userdata('auth_users'); 
            $this->db->select('hms_payment_mode_field_value_acc_section.field_value');
            $this->db->from('hms_payment_mode_field_value_acc_section');
            $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$user_data['parent_id']);
            $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$booking_id);
            
            $this->db->where('hms_payment_mode_field_value_acc_section.section_id',$section_id);
            
            $this->db->where('hms_payment_mode_field_value_acc_section.type',$type);
            $res=$this->db->get();
            //echo $this->db->last_query();die;
            return $res->result();
            
        
    }
    
     public function get_attended_doctor_signature($doctor_id="")
   {
        $users_data = $this->session->userdata('auth_users');
        
        $this->db->select('signature,doctor_name,doc_reg_no,qualification'); 
        $this->db->where('id',$doctor_id);  
        $this->db->where('is_deleted',0);  
        $this->db->where('branch_id',$users_data['parent_id']); 
        $this->db->group_by('id');  
        $query = $this->db->get('hms_doctors'); //hms_test_footer
        $result = $query->row(); 
        return $result; 
    }

    public function get_medicine_freqdata($mid='', $branch_id='', $patient_id='',$book_id='',$pres_id='')
   {
        $this->db->select('*');
        $this->db->from('hms_std_eye_advs_temp');
        $this->db->where('patient_id',$patient_id);
        $this->db->where('branch_id',$branch_id);
        $this->db->where('booking_id',$book_id);
        $this->db->where('med_id',$mid);
        if(!empty($pres_id) && $pres_id !='')
        {
            $this->db->where('pres_id',$pres_id);
        }
        $this->db->order_by('id','ASC');
        $res= $this->db->get();
        return $res->result_array();
   }
   
   public function get_medicine_set_tapperdata($mid='', $branch_id='', $set_id='')
   {
        $this->db->select('*');
        $this->db->from('hms_std_eye_advice_set_tapper_data');
        $this->db->where('branch_id',$branch_id);
        $this->db->where('med_id',$mid);
        if(!empty($set_id) && $set_id !='')
        {
            $this->db->where('set_id',$set_id);
        }
        $this->db->order_by('id','ASC');
        $res= $this->db->get();
        return $res->result_array();
   }
   
   public function get_stock_available_donor_data($comp_id="",$blood_group_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_blood_donor.id,hms_blood_group.blood_group,hms_blood_donor.blood_group_id,hms_blood_donor.donor_code'); 
        
        $this->db->from('hms_blood_extract_component');
        $this->db->join('hms_blood_donor','hms_blood_donor.id = hms_blood_extract_component.donor_id AND hms_blood_donor.branch_id='.$users_data['parent_id'],'left');
        $this->db->join('hms_blood_group','hms_blood_group.id = hms_blood_donor.blood_group_id','left');
        
        if(!empty($blood_group_id))
        {
            $this->db->where('hms_blood_donor.blood_group_id',$blood_group_id);
        }
        
        
        $this->db->where('hms_blood_extract_component.component_id',$comp_id);
        $this->db->where('hms_blood_extract_component.branch_id',$users_data['parent_id']);
        //$this->db->where('donor_id',$donor_id);
        $query = $this->db->get();
        // echo $this->db->last_query();die;
        $result = $query->result(); 
         return $result;
    }
    
    
    public function check_donation_status($donor_id,$component_id,$blood_group_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("is_issued");
        
        $this->db->where('branch_id',$users_data['parent_id']);
        
        if((!empty($component_id)) && ($component_id!=""))           
        {
            $this->db->where('component_id',$component_id);
        }
        if((!empty($blood_group_id)) && ($blood_group_id!=""))           
        {
            $this->db->where('blood_group_id',$blood_group_id);
        }
        if((!empty($donor_id)) && ($donor_id!=""))           
        {
            $this->db->where('donor_id',$donor_id);
        }
        $this->db->where('flag',1);
        
        
        $this->db->where('expiry_date >= "'.date('Y-m-d H:i:s').'"');
        $query = $this->db->get('hms_blood_stock');
        //echo $this->db->last_query();
        return $query->row_array();
    }
    
    //icd 10
    
    public function icd_eye_code($section_id="",$min="",$max="")
    {
     $this->db->select('*');
     $this->db->where('chapter',7); 
     $where="CHAR_LENGTH(icd_id)< $max  AND CHAR_LENGTH(icd_id) > $min";
     $this->db->where($where);
     $this->db->where('section',$section_id); 
     $this->db->from('hms_icd10'); 
     $query=$this->db->get(); 
     return $query->result_array(); 
    }
    
    public function check_eye_code($icd_id="")
    {

        $min=6;
        $max=8;
     $this->db->select('*');
     $this->db->where('chapter',7); 
     $where="CHAR_LENGTH(icd_id)< $max  AND CHAR_LENGTH(icd_id) > $min";
     $this->db->where($where);
     $this->db->like('icd_id',$icd_id); 
     $this->db->from('hms_icd10'); 
     $query=$this->db->get(); 
     return $query->num_rows(); 
    //echo $this->db->last_query();die;
     
    }
     public function check_secondeye_code($icd_id="")
    {

        $min=5;
        $max=7;
     $this->db->select('*');
     $this->db->where('chapter',7); 
     $where="CHAR_LENGTH(icd_id)< $max  AND CHAR_LENGTH(icd_id) > $min";
     $this->db->where($where);
     $this->db->like('icd_id',$icd_id); 
     $this->db->from('hms_icd10'); 
     $query=$this->db->get(); 
     return $query->num_rows(); 
    //echo $this->db->last_query();die;
     
    }
    
    public function icd_eye_code_two($icd_id="",$min="",$max="")
    {
         $this->db->select('*');
         $this->db->where('chapter',7); 
         $where="icd_id LIKE '".$icd_id."'";
         $this->db->where($where);
         $this->db->from('hms_icd10'); 
         $query=$this->db->get(); 
         //echo $this->db->last_query();die; 
         return $query->result_array(); 
        }
        public function icd_eye_code_three($icd_id="",$min="",$max="")
        {
         $this->db->select('*');
         $this->db->where('chapter',7); 
          $where="icd_id LIKE '".$icd_id."_'";
         // $this->db->where('section',$section_id);
          $this->db->where($where);
         $this->db->from('hms_icd10'); 
         $query=$this->db->get();
         //echo $this->db->last_query();die;
         return $query->result_array(); 
        }
        
    public function icd_seconddropdownlist($icd_id="",$max="",$min="")
    {
        
     $this->db->select('*');
     $this->db->where('chapter',7); 
     $where="CHAR_LENGTH(icd_id)< $max  AND CHAR_LENGTH(icd_id) > $min";
     $this->db->where($where);
     $this->db->like('icd_id',$icd_id); 
     $this->db->from('hms_icd10'); 
     $query=$this->db->get(); 
   //  echo $this->db->last_query();die;
     return $query->result_array(); 
    }
    
    public function icd_firstdropdownlist($icd_id="")
    {
        
     $this->db->select('id,descriptions');
     $this->db->where('chapter',7); 
     $this->db->where('icd_id',$icd_id); 
     $this->db->from('hms_icd10'); 
     $query=$this->db->get(); 
   //  echo $this->db->last_query();die;
     return $query->row_array(); 
    }
    
    
    public function total_customer($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_customers.*'); 
        $this->db->where('hms_customers.branch_id',$parent_id); 
        $this->db->where('hms_customers.is_deleted != 2');  
        if(!empty($prefix))
        {
          $this->db->where('hms_customers.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "61" AND  branch_id = "'.$parent_id.'")'); 
        } 
        $query = $this->db->get('hms_customers');
        //echo $this->db->last_query();die;
        $result = $query->num_rows(); 
        return $result; 
    }
    
     public function tot_canteen_garbage_stock_item($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_garbage_stock_item.*'); 
        $this->db->where('hms_canteen_garbage_stock_item.branch_id',$parent_id);  
        $this->db->where('hms_canteen_garbage_stock_item.is_deleted != 2');  
        if(!empty($prefix))
        {
        $this->db->where('hms_canteen_garbage_stock_item.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "62" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_canteen_garbage_stock_item');
        $result = $query->num_rows(); 
        return $result; 
    }
    
     public function total_canteen_vendor($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_vendors.*'); 
        $this->db->where('hms_canteen_vendors.is_deleted !=2'); 
        $this->db->where('hms_canteen_vendors.branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_canteen_vendors');
        $result = $query->num_rows(); 
        return $result; 
    }  
    
     public function total_canteen_purchase($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_purchase.*'); 
        //$this->db->where('hms_canteen_purchase.branch_id',$parent_id); 
        $this->db->where('hms_canteen_purchase.is_deleted !=2');
        $this->db->where('hms_canteen_purchase.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_canteen_purchase.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "64" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_canteen_purchase');
        $result = $query->num_rows(); 
        return $result; 
    }


 public function total_canteen_purchase_return($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_return.*'); 
        $this->db->where('hms_canteen_return.is_deleted !=2');
        //$this->db->where('hms_canteen_return.branch_id',$parent_id); 
        $this->db->where('hms_canteen_return.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_canteen_return.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "65" AND  branch_id = "'.$parent_id.'")'); 
        } 
        $query = $this->db->get('hms_canteen_return');
        $result = $query->num_rows(); 
        return $result; 
    }
 public function total_canteen_purchase_estimate($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_estimate_purchase.*'); 
        //$this->db->where('hms_canteen_estimate_purchase.branch_id',$parent_id); 
        $this->db->where('hms_canteen_estimate_purchase.is_deleted !=2');
        $this->db->where('hms_canteen_estimate_purchase.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_canteen_estimate_purchase.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "66" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_canteen_estimate_purchase');
        $result = $query->num_rows(); 
        return $result; 
    }
    
    public function total_canteen_sale($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_sale.*'); 
        $this->db->where('hms_canteen_sale.is_deleted !=2');
        //$this->db->where('hms_canteen_sale.branch_id',$parent_id); 
        $this->db->where('hms_canteen_sale.branch_id',$users_data['parent_id']);
        if(!empty($prefix))
        {
          $this->db->where('hms_canteen_sale.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "67" AND  branch_id = "'.$parent_id.'")'); 
        }
        $query = $this->db->get('hms_canteen_sale');
        $result = $query->num_rows(); 
        return $result; 
    }
    
     public function total_canteen_master_entry($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_master_entry.*'); 
        $this->db->where('hms_canteen_master_entry.branch_id',$parent_id); 
        $this->db->where('hms_canteen_master_entry.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_canteen_master_entry.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "68" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_canteen_master_entry');
        $result = $query->num_rows(); 
        return $result; 
    }
    
    
     public function total_canteen_products($parent_id="", $prefix="0")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_canteen_products.*'); 
        $this->db->where('hms_canteen_products.branch_id',$parent_id); 
        $this->db->where('hms_canteen_products.is_deleted != 2'); 
        if(!empty($prefix))
        {
        $this->db->where('hms_canteen_products.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "69" AND  branch_id = "'.$parent_id.'")');
        }  
        $query = $this->db->get('hms_canteen_products');
        $result = $query->num_rows(); 
        return $result; 
    }
    
     public function canteen_category_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('category','ASC'); 
        $query = $this->db->get('hms_canteen_stock_category');
        return $query->result();
    }
    
    
    /* add ambulance*/	
public function amb_particulars_list($particular_id='')	
{	
    $users_data = $this->session->userdata('auth_users');	
    $this->db->select('*');  	
    $this->db->where('status','1');	
    if(!empty($particular_id))	
    {	
        $this->db->where('id',$particular_id); 	
    } 	
    $this->db->order_by('particular','ASC');	
    $this->db->where('is_deleted',0);	
    $this->db->where('branch_id',$users_data['parent_id']); 	
    $query = $this->db->get('hms_ambulance_particular');	
    $result = $query->result();	
    return $result; 	
}

/* 24-04-2020*/
public function location_list()
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('*');  
    $this->db->where('status','1'); 
    $this->db->order_by('location_name','DESC');
    $this->db->where('branch_id',$users_data['parent_id']); 
    $query = $this->db->get('hms_ambulance_location');
    $result = $query->result(); 
    return $result; 
}
/* 24-04-2020*/

/* 24-04-2020*/
public function amb_vendor_list($type='')
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_medicine_vendors.id,hms_medicine_vendors.name');  
    if($type==2){
         $this->db->join('hms_ambulance_vehicle','hms_ambulance_vehicle.vendor_id=hms_medicine_vendors.id AND  hms_ambulance_vehicle.vehicle_type=2');
    }
    $this->db->from('hms_medicine_vendors');
    $this->db->where('hms_medicine_vendors.status','1'); 
    $this->db->where('hms_medicine_vendors.is_deleted',0);
    $this->db->where('hms_medicine_vendors.vendor_type',5);
    $this->db->group_by('hms_medicine_vendors.id');
    $this->db->order_by('hms_medicine_vendors.name','DESC');
    $this->db->where('hms_medicine_vendors.branch_id',$users_data['parent_id']); 
    $query = $this->db->get();
    //echo $this->db->last_query();die;
    $result = $query->result(); 
    return $result; 
}

/*29-04-2020*/
 public function driving_license_expiry($days='')
    {
        $users_data = $this->session->userdata('auth_users');
        $current_date = date('Y-m-d');
        $expiry_date =  date('Y-m-d', strtotime($current_date. ' + '.$days.' days'));
        $this->db->select('hms_ambulance_driver.*'); 
        $this->db->where('hms_ambulance_driver.branch_id',$users_data['parent_id']);
        $this->db->where('hms_ambulance_driver.is_deleted',0);
        $this->db->where('hms_ambulance_driver.status',1);
        $this->db->where('hms_ambulance_driver.dl_expiry_date >=',$current_date);
        $this->db->where('hms_ambulance_driver.dl_expiry_date <=',$expiry_date);
        $query = $this->db->get('hms_ambulance_driver');
        $result = $query->result(); 
        return $result;
    }
    
    /* ambulance */
 public function total_ambulance_gda_staff($parent_id="", $prefix="0")
 {
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_ambulance_gda_staff.*'); 
    $this->db->where('hms_ambulance_gda_staff.branch_id',$parent_id); 
    $this->db->where('hms_ambulance_gda_staff.is_deleted != 2');        
    if(!empty($prefix))
    {
      $this->db->where('hms_ambulance_gda_staff.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "61" AND  branch_id = "'.$parent_id.'")'); 
  }  
  $query = $this->db->get('hms_ambulance_gda_staff');
  $result = $query->num_rows(); 
  return $result; 
}

/* ambulance */
 public function total_ambulance_enquiry($parent_id="", $prefix="0")
 {
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_ambulance_enquiry.*'); 
    $this->db->where('hms_ambulance_enquiry.branch_id',$parent_id); 
    $this->db->where('hms_ambulance_enquiry.is_deleted != 2');        
    if(!empty($prefix))
    {
      $this->db->where('hms_ambulance_enquiry.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "62" AND  branch_id = "'.$parent_id.'")'); 
  }  
  $query = $this->db->get('hms_ambulance_enquiry');
  $result = $query->num_rows(); 
  return $result; 
}

public function total_indent_sale($parent_id="",$prefix='')
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_indent_sale.*'); 
    $this->db->where('hms_indent_sale.is_deleted !=2');
    $this->db->where('hms_indent_sale.branch_id',$users_data['parent_id']);
    if(!empty($prefix))
    {
      $this->db->where('hms_indent_sale.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "51" AND  branch_id = "'.$parent_id.'")'); 
  }
  $query = $this->db->get('hms_indent_sale');
  $result = $query->num_rows(); 
  return $result; 
}

public function total_indent_sale_return($parent_id="",$prefix='')
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_indent_sale_return.*'); 
    $this->db->where('hms_indent_sale_return.is_deleted !=2');
    $this->db->where('hms_indent_sale_return.branch_id',$users_data['parent_id']);
    if(!empty($prefix))
    {
      $this->db->where('hms_indent_sale_return.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "52" AND  branch_id = "'.$parent_id.'")'); 
  }
  $query = $this->db->get('hms_indent_sale_return');
  $result = $query->num_rows(); 
  return $result; 
}
    
public function path_total_invoice_booking($parent_id="", $prefix="0")
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('path_test_booking_invoice.*'); 
    $this->db->where('path_test_booking_invoice.branch_id',$parent_id); 
    $this->db->where('path_test_booking_invoice.is_deleted != 2');
    if(!empty($prefix))
    {
      $this->db->where('path_test_booking_invoice.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "73" AND  branch_id = "'.$parent_id.'")');
    }  
    $query = $this->db->get('path_test_booking_invoice');
    
    $result = $query->num_rows(); 
    return $result; 
} 

//dialysis booking
function get_dialysis_schedule_time($day_id='',$shedule_id='')
  {
        $this->db->select('hms_dialysis_schedule_time.*');
        $this->db->where('hms_dialysis_schedule_time.available_day = "'.$day_id.'"');
        $this->db->where('hms_dialysis_schedule_time.schedule_id = "'.$shedule_id.'"');
        $this->db->from('hms_dialysis_schedule_time');
        $result=$this->db->get()->result();
        return $result;
  }
  
  public function schedule_time($schedule_id="",$booking_date='')
    {
        $users_data = $this->session->userdata('auth_users');
        
        $this->db->select('hms_dialysis_schedule_time.*,hms_days.day_name');
        $this->db->join('hms_dialysis_schedule','hms_dialysis_schedule.id = hms_dialysis_schedule_time.schedule_id');
        $this->db->join('hms_days','hms_days.id = hms_dialysis_schedule_time.available_day');
        if(!empty($schedule_id))
        {
            $this->db->where('hms_dialysis_schedule_time.schedule_id = "'.$schedule_id.'"');
            
        }
        if(!empty($booking_date))
        {
            $date = date('Y-m-d',strtotime($booking_date));
            $day_name = date("l", strtotime($date));
            $this->db->where('hms_days.day_name = "'.$day_name.'"'); 
        }
         
        $query = $this->db->get('hms_dialysis_schedule_time');
        //echo $this->db->last_query(); exit;
        $result = $query->result();    
        //echo $this->db->last_query(); 
        return $result;
    }
    
    
    
    function dialysis_per_patient_time($schedule_id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('per_patient_timing'); 
        if(!empty($schedule_id))
        {
            $this->db->where('id',$schedule_id); 
        }
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_dialysis_schedule');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result;
    }
    
    public function dialysis_schedule_list($branch_id="")
    {
        
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*'); 
       
        if(isset($branch_id) && !empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id);
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']);    
        }
        
        $this->db->where('status','1'); 
        $this->db->order_by('schedule_name','ASC'); 
        $this->db->where('is_deleted',0);
        $query = $this->db->get('hms_dialysis_schedule');
        $result = $query->result(); 
        //echo $this->db->last_query(); 
        return $result;
    }
    
    public function schedule_slot($schedule_id="",$time_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('from_time as time1 ,to_time as time2');  
        if(!empty($schedule_id))
        {
            $this->db->where('schedule_id',$schedule_id); 
        }
        if(!empty($time_id))
        {
            $this->db->where('id',$time_id); 
        }
        //$this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_dialysis_schedule_time');
        $result = $query->result(); 
        //echo $this->db->last_query(); exit;
        return $result;
    }
    
    function get_dialysis_booked_slot($schedule_id='',$time_id='',$appointment_date='',$appointment_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('doctor_slot');  
        if(!empty($schedule_id))
        {
            $this->db->where('schedule_id',$schedule_id); 
        }
        
        if(!empty($appointment_date))
        {
            $this->db->where('dialysis_booking_date',date('Y-m-d',strtotime($appointment_date))); 
        }
        if(!empty($time_id))
        {
            $this->db->where('available_time',$time_id); 
        }
        if(!empty($appointment_id))
        {
            $this->db->where('id!=',$appointment_id); 
        }
        $this->db->where('branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_dialysis_appointment');
        //echo $this->db->last_query(); exit;
        $result = $query->result(); 
        
        return $result;      
    }

    
    public function check_dialysis_room_type($room_type="",$id="")
    {
        $this->db->select('*');  
        if(!empty($room_type))
        {
            $this->db->where('room_category',$room_type);
        } 
        if(!empty($id))
        {
            $this->db->where('id != "'.$id.'"');
        } 
        $users_data = $this->session->userdata('auth_users');
        $this->db->where('is_deleted!=',2);
        $this->db->where('branch_id',$users_data['parent_id']);
        $query = $this->db->get('hms_dialysis_room_category');
        $result = $query->result(); 
        return $result; 
    }
    
    public function dialysis_room_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        $this->db->where('status',1);
        $query = $this->db->get('hms_dialysis_room_category');
        $result = $query->result();
        return $result;
    }
    public function dialysis_room_charge_type_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted',0);
        $this->db->where('status',1);
        $query = $this->db->get('hms_dialysis_room_charge_type');

        $result = $query->result_array();
        return $result;
    }
    
    public function dialysis_room_no_list($room_id)
    {
        $this->db->select('*'); 
        $users_data = $this->session->userdata('auth_users');
        if(!empty($room_id))
        {
        $this->db->where('room_type_id',$room_id); 
        }
        //$this->db->where('status','1'); 
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->order_by('id','ASC'); 
        $query = $this->db->get('hms_dialysis_rooms');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    
    public function dialysis_number_bed_list($room_id,$room_no_id,$dialysis_id='')
    {
        $this->db->select('hms_dialysis_room_to_bad.*,hms_dialysis_booking.is_deleted as ipd_is_deleted'); 
        
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id = hms_dialysis_room_to_bad.dialysis_id','left');
        if(!empty($room_id))
        {
            $this->db->where('hms_dialysis_room_to_bad.room_type_id',$room_id); 
            $this->db->where('hms_dialysis_room_to_bad.room_id',$room_no_id); 
        }
        //$this->db->where('status','1'); 
        if(empty($dialysis_id))
        {
            $this->db->where('hms_dialysis_room_to_bad.status','0');     
        }
        else
        {
            $where = "(`hms_dialysis_room_to_bad.dialysis_id` = $dialysis_id OR `hms_dialysis_room_to_bad.status` = '0')";
            $this->db->where($where); 
        }
        
        // / AND hms_ipd_booking.is_deleted!=2
        $this->db->order_by('hms_dialysis_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_dialysis_room_to_bad');
        
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    
    
    function get_patient_according_to_dialysis($dialysis_id="",$patient_id='',$section_id='',$type='') 
    {   
        $users_data = $this->session->userdata('auth_users'); 
       $this->db->select("hms_patient.*,hms_patient.id as p_id,hms_dialysis_booking.id as dialysis_id,hms_dialysis_booking.booking_code,hms_dialysis_rooms.room_no,hms_dialysis_room_to_bad.bad_no,hms_dialysis_room_to_bad.bad_name,hms_dialysis_room_category.room_category,hms_dialysis_booking.dialysis_date,hms_dialysis_booking.dialysis_time");
        $this->db->from('hms_patient');
        if(!empty($patient_id))
        {
            $this->db->where('hms_dialysis_booking.patient_id',$patient_id);    
        } 
        if(!empty($dialysis_id))
        {
            $this->db->where('hms_dialysis_booking.id',$dialysis_id);    
        }
        
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.patient_id=hms_patient.id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
        
         $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        
        
        $this->db->join('hms_dialysis_rooms','hms_dialysis_rooms.id=hms_dialysis_booking.room_id','left');
        $this->db->join('hms_dialysis_room_to_bad','hms_dialysis_room_to_bad.id=hms_dialysis_booking.bad_id','left');
        $this->db->join('hms_doctors','hms_doctors.id=hms_dialysis_booking.doctor_id','left');
        $this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left');
        $this->db->join('hms_dialysis_room_category','hms_dialysis_room_category.id=hms_dialysis_booking.room_type_id','left');
        $this->db->join('hms_users','hms_users.id = hms_dialysis_booking.created_by','left');
        
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        
         
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left'); 
		
        
        $query = $this->db->get(); 
        //echo $this->db->last_query(); exit;
        return $query->row_array();
    }

    
   function get_dialysis_particular_details($dialysis_id="",$patient_id='')
    {
        //price before 3 july 2020
        $this->db->select('hms_dialysis_patient_to_charge.id as charge_id,hms_dialysis_patient_to_charge.particular_id as particular,hms_dialysis_patient_to_charge.particular as particulars,hms_dialysis_patient_to_charge.price as charges,(select sum(net_price) from hms_dialysis_patient_to_charge as charges where charges.id = hms_dialysis_patient_to_charge.id) as amount,hms_dialysis_patient_to_charge.quantity,hms_dialysis_patient_to_charge.start_date as s_date,hms_dialysis_patient_to_charge.doctor,hms_dialysis_patient_to_charge.doctor_id');
        $this->db->from('hms_dialysis_patient_to_charge');
        if(!empty($patient_id))
        {
            $this->db->where('hms_dialysis_patient_to_charge.patient_id',$patient_id);    
        } 
        if(!empty($dialysis_id))
        {
            $this->db->where('hms_dialysis_patient_to_charge.dialysis_id',$dialysis_id);    
        }
        $this->db->where('hms_dialysis_patient_to_charge.type',5);
        $this->db->where('hms_dialysis_patient_to_charge.is_deleted',0);
        
        $query = $this->db->get()->result_array(); 
        //echo $this->db->last_query();die;
      // print '<pre>'; print_r( $query);die;
        return $query;
    }
    
    
    function get_dialysis_prescription_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_dialysis_prescription_tab_setting.*,hms_dialysis_prescription_tab_setting.var_title');   
            $this->db->join('hms_branch_dialysis_prescription_tab_setting','hms_branch_dialysis_prescription_tab_setting.unique_id = hms_dialysis_prescription_tab_setting.id');
            $this->db->where('hms_branch_dialysis_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_dialysis_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_dialysis_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_dialysis_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_dialysis_prescription_tab_setting');
//echo $this->db->last_query();exit;
            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_dialysis_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_dialysis_prescription_medicine_tab_setting.*,hms_dialysis_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_branch_dialysis_prescription_medicine_tab_setting','hms_branch_dialysis_prescription_medicine_tab_setting.unique_id = hms_dialysis_prescription_medicine_tab_setting.id');
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_dialysis_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_dialysis_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_dialysis_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }


    function get_dialysis_prescription_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_dialysis_prescription_tab_setting.*,hms_dialysis_prescription_tab_setting.var_title');   
            $this->db->join('hms_branch_dialysis_prescription_tab_setting','hms_branch_dialysis_prescription_tab_setting.unique_id = hms_dialysis_prescription_tab_setting.id');
            $this->db->where('hms_branch_dialysis_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_dialysis_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_dialysis_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_dialysis_prescription_tab_setting.status',1);
            $this->db->where('hms_branch_dialysis_prescription_tab_setting.print_status',1);
            $query = $this->db->get('hms_dialysis_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_dialysis_prescription_medicine_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_branch_dialysis_prescription_medicine_tab_setting.*,hms_dialysis_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_branch_dialysis_prescription_medicine_tab_setting','hms_branch_dialysis_prescription_medicine_tab_setting.unique_id = hms_dialysis_prescription_medicine_tab_setting.id');
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_branch_dialysis_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_branch_dialysis_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.print_status',1);
            $this->db->where('hms_branch_dialysis_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_dialysis_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
    public function total_dialysis_booking_procedure($parent_id="",$prefix='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_dialysis_booking.*'); 
        $this->db->where('branch_id',$parent_id);
        $this->db->where('is_deleted=0'); 
        if(!empty($prefix))
        { 
           $this->db->where('hms_dialysis_booking.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "75" AND  branch_id = "'.$parent_id.'" )');  
        }  
        $query = $this->db->get('hms_dialysis_booking');
        $result = $query->num_rows(); 
        //echo $this->db->last_query();die;
        return $result; 
    }
    
    public function get_ipd_particular_name($particular_id="")
     {
        $users_data = $this->session->userdata('auth_users');
        $result ='';
        if(!empty($particular_id))
        {
            $this->db->select('particular');
            $this->db->where('id',$particular_id);
            $this->db->where('branch_id',$users_data['parent_id']);
            $query = $this->db->get('hms_ipd_perticular');
            $result = $query->row();
            //echo $this->db->last_query();die;
    
        }
        return $result;
    }
    
     public function number_dialysis_bed_all_list($room_id,$room_no_id,$ipd_id='')
    {
        $this->db->select('hms_dialysis_room_to_bad.*,hms_dialysis_booking.is_deleted as ipd_is_deleted'); 
        if(!empty($room_id))
        {
            $this->db->where('hms_dialysis_room_to_bad.room_type_id',$room_id); 
            $this->db->where('hms_dialysis_room_to_bad.room_id',$room_no_id); 
        }
        //$this->db->where('status','1'); 
        $this->db->join('hms_dialysis_booking','hms_dialysis_booking.id = hms_dialysis_room_to_bad.dialysis_id','left');
        $this->db->order_by('hms_dialysis_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_dialysis_room_to_bad');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result;
    }
    
    public function all_filter_number_dialysis_bed_all_list($room_id,$room_no_id,$status='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('hms_dialysis_room_to_bad.*'); 
        if(!empty($room_id))
        {
            $this->db->where('hms_dialysis_room_to_bad.room_type_id',$room_id); 
            $this->db->where('hms_dialysis_room_to_bad.room_id',$room_no_id); 
        }
        
        if(!empty($status) && $status==1)
        {
            $this->db->where('hms_dialysis_room_to_bad.status',1);
        }
        if(!empty($status) && $status==2)
        {
            $this->db->where('hms_dialysis_room_to_bad.status',0);
        }
        $this->db->where('hms_dialysis_room_to_bad.branch_id',$users_data['parent_id']); 
        $this->db->order_by('hms_dialysis_room_to_bad.id','ASC'); 

        $query = $this->db->get('hms_dialysis_room_to_bad');
        $result = $query->result(); 
        //echo $this->db->last_query();die;
        return $result;
    }
    
  function get_dialysis_schedule_time1($day_id='',$schedule_id='')
  {
        $this->db->select('hms_doctors_schedule.*');
        $this->db->where('hms_doctors_schedule.available_day = "'.$day_id.'"');
        $this->db->where('hms_doctors_schedule.doctor_id = "'.$doctor_id.'"');
        $this->db->from('hms_doctors_schedule');
        $result=$this->db->get()->result();
        return $result;
  }


//dialysis booking end

public function patient_category_list($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        
        if(!empty($branch_id))
        {
          $this->db->where('branch_id',$branch_id);  
        }
        else
        {
          $this->db->where('branch_id',$users_data['parent_id']);  
        }
        $this->db->where('status','1'); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('patient_category','ASC');
        $query = $this->db->get('hms_patient_category');
        $result = $query->result(); 
        return $result; 
    }
    
    public function authrize_person_list($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1'); 
        $this->db->order_by('authorize_person','ASC');
        $this->db->where('is_deleted',0);
        if(!empty($branch_id))
        {
            $this->db->where('branch_id',$branch_id); 
        }
        else
        {
            $this->db->where('branch_id',$users_data['parent_id']); 
        }
        
        $query = $this->db->get('hms_authorize_person');
        $result = $query->result(); 
        return $result; 
    }
    
    public function get_discount_setting($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $status=0;
        if(!empty($branch_id))
        {
            $this->db->select('hms_commission_setting.status');  
            $this->db->where('hms_commission_setting.branch_id',$branch_id); 
            $query = $this->db->get('hms_commission_setting');
            $result = $query->row(); 
            if(!empty($result->status))
            {
                $status = $result->status;    
            }
        }    
            return $status; 
      
    }

public function total_eye_new_icd($parent_id="",$prefix='')
{
    $users_data = $this->session->userdata('auth_users');
    $this->db->select('hms_custom_icds.*'); 
    $this->db->where('hms_custom_icds.branch_id',$parent_id); 
    if(!empty($prefix))
    {
      $this->db->where('hms_custom_icds.created_date >= (select created_date from hms_branch_unique_ids where unique_id = "54" AND  branch_id = "'.$parent_id.'")');
      $this->db->where('hms_custom_icds.custom_type',1);
  }
  $query = $this->db->get('hms_custom_icds');
  $result = $query->num_rows(); 
  return $result; 

}

//Day care start
    
    function get_patient_according_to_daycare($daycare_id="",$patient_id='',$section_id='',$type='') 
    {   
       $users_data = $this->session->userdata('auth_users'); 
       $this->db->select("hms_patient.id,hms_patient.patient_name,hms_patient.patient_code,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.age_h,hms_patient.adhar_no,hms_patient.relation_type,hms_patient.relation_simulation_id,relation_name,hms_patient.dob,hms_patient.simulation_id,hms_patient.id as p_id,hms_day_care_booking.id as daycare_id,hms_day_care_booking.booking_code,hms_day_care_booking.daycare_discharge_date,hms_day_care_booking.cheque_no,hms_day_care_booking.cheque_date,hms_day_care_booking.transaction_no,hms_day_care_booking.booking_date,hms_day_care_booking.booking_time,hms_doctors.doctor_name,hms_specialization.specialization,hms_day_care_booking.mlc,hms_gardian_relation.relation,hms_day_care_booking.remarks,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1");
        $this->db->from('hms_patient');
        if(!empty($patient_id))
        {
            $this->db->where('hms_day_care_booking.patient_id',$patient_id);    
        } 
        if(!empty($daycare_id))
        {
            $this->db->where('hms_day_care_booking.id',$daycare_id);    
        }
        
        $this->db->join('hms_day_care_booking','hms_day_care_booking.patient_id=hms_patient.id','left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id = hms_patient.relation_type','left'); 
        $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        $this->db->join('hms_doctors','hms_doctors.id=hms_day_care_booking.attended_doctor','left');
        $this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id','left');
        $query = $this->db->get(); 
        //echo $this->db->last_query(); //exit;
        return $query->row_array();
    }
    
    function get_daycare_medicine_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_daycare_branch_summary_medicine_tab_setting.*,hms_daycare_summary_medicine_tab_setting.var_title');   
            $this->db->join('hms_daycare_branch_summary_medicine_tab_setting','hms_daycare_branch_summary_medicine_tab_setting.unique_id = hms_daycare_summary_medicine_tab_setting.id');
            $this->db->where('hms_daycare_branch_summary_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_daycare_branch_summary_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_daycare_branch_summary_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_daycare_branch_summary_medicine_tab_setting.status',1);
            $this->db->where('hms_daycare_branch_summary_medicine_tab_setting.print_status',1);
            $this->db->where('hms_daycare_branch_summary_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_daycare_summary_medicine_tab_setting');
            //echo $this->db->last_query();exit;
            $result = $query->result(); 
            
            return $result; 
                
    }
    
    public function get_treatment_type_name($treat_ids =array())
    {
        if(!empty($treat_ids))
        { 
            $this->db->select('hms_dental_treatment_type.treatment');
            $this->db->where('hms_dental_treatment_type.id IN ('.$treat_ids.')');
            $query = $this->db->get('hms_dental_treatment_type'); 
            $result = $query->result();
            $id_list = [];
            if(!empty($result))
            {
                foreach($result as $part)
                {
                      $id_list[]  = $part->treatment;
                }
            }
            $name_list = implode(',', $id_list);
            
            return $name_list; 
        }
    }
 //admission  nursing    
  function get_ipd_admission_prescription_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_admission_prescription_tab_setting.*,hms_ipd_admission_prescription_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_admission_prescription_tab_setting','hms_ipd_branch_admission_prescription_tab_setting.unique_id = hms_ipd_admission_prescription_tab_setting.id');
            $this->db->where('hms_ipd_branch_admission_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_admission_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_admission_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_admission_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_ipd_admission_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_ipd_admission_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_admission_prescription_medicine_tab_setting.*,hms_ipd_admission_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_admission_prescription_medicine_tab_setting','hms_ipd_branch_admission_prescription_medicine_tab_setting.unique_id = hms_ipd_admission_prescription_medicine_tab_setting.id');
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_admission_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_admission_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_ipd_admission_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
    function get_ipd_nursing_prescription_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_nursing_prescription_tab_setting.*,hms_ipd_nursing_prescription_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_nursing_prescription_tab_setting','hms_ipd_branch_nursing_prescription_tab_setting.unique_id = hms_ipd_nursing_prescription_tab_setting.id');
            $this->db->where('hms_ipd_branch_nursing_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_nursing_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_nursing_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_nursing_prescription_tab_setting.status',1);
            $query = $this->db->get('hms_ipd_nursing_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

    function get_ipd_nursing_prescription_medicine_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_nursing_prescription_medicine_tab_setting.*,hms_ipd_nursing_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_nursing_prescription_medicine_tab_setting','hms_ipd_branch_nursing_prescription_medicine_tab_setting.unique_id = hms_ipd_nursing_prescription_medicine_tab_setting.id');
            $this->db->where('hms_ipd_branch_nursing_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_nursing_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_nursing_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_nursing_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_nursing_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_ipd_nursing_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }
    
    public function nursing_chief_complaint_list($doctor_id="",$type="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        if(!empty($type)) {
            $this->db->where('hms_nursing_chief_complaints.chief_complaints LIKE "'.$type.'%"');
        } 
        $query = $this->db->get('hms_nursing_chief_complaints');
        $result = $query->result(); 
        return $result; 
    }
    public function admission_chief_complaint_list($doctor_id="", $type="",$id="")
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0'); 
        if(!empty($type)) {
            $this->db->where('hms_admission_chief_complaints.chief_complaints LIKE "'.$type.'%"');
        } 
        if(!empty($id)) {
            $this->db->where('hms_admission_chief_complaints.id = "'.$id.'"');
        }  
        $query = $this->db->get('hms_admission_chief_complaints');
        $result = $query->result(); 
        return $result; 
    }
    
    function get_ipd_admission_prescription_print_tab_setting($parent_id="",$order_by='')
    {
            
            $this->db->select('hms_ipd_branch_admission_prescription_tab_setting.*,hms_ipd_admission_prescription_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_admission_prescription_tab_setting','hms_ipd_branch_admission_prescription_tab_setting.unique_id = hms_ipd_admission_prescription_tab_setting.id');
            $this->db->where('hms_ipd_branch_admission_prescription_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_admission_prescription_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_admission_prescription_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_admission_prescription_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_admission_prescription_tab_setting.print_status',1);
            $query = $this->db->get('hms_ipd_admission_prescription_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
             
                
    }
    
    function get_ipd_admission_prescription_medicine_print_tab_setting($parent_id="",$order_by='')
    {
            $users_data = $this->session->userdata('auth_users');
            $this->db->select('hms_ipd_branch_admission_prescription_medicine_tab_setting.*,hms_ipd_admission_prescription_medicine_tab_setting.var_title');   
            $this->db->join('hms_ipd_branch_admission_prescription_medicine_tab_setting','hms_ipd_branch_admission_prescription_medicine_tab_setting.unique_id = hms_ipd_admission_prescription_medicine_tab_setting.id');
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.branch_id',$parent_id);
            if(!empty($order_by))
            {
               $this->db->order_by('hms_ipd_branch_admission_prescription_medicine_tab_setting.order_by',$order_by); 
            }
            else
            {
                $this->db->order_by('hms_ipd_branch_admission_prescription_medicine_tab_setting.order_by','ASC');     
            }
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.status',1);
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.print_status',1);
            $this->db->where('hms_ipd_branch_admission_prescription_medicine_tab_setting.type',0);
            $query = $this->db->get('hms_ipd_admission_prescription_medicine_tab_setting');

            $result = $query->result(); 
            //echo $this->db->last_query();exit;
            return $result; 
                
    }

///write above


}
