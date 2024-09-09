<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class General extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('general/general_model','general');
		$this->load->model('blood_bank/stock/stock_model','stock_model');
		$this->load->library('form_validation');
	}
    
    public function state_list($country_id="")
    {
    	$data = '<option value="">Select State</option>';
    	if(!empty($country_id))
    	{
    		$state_list = $this->general->state_list($country_id); 
	        if(!empty($state_list))
	        {
	        	foreach($state_list as $state)
	        	{
	               $data .= '<option value="'.$state->id.'">'.ucfirst(strtolower($state->state)).'</option>';
	        	}
	        }
    	} 
        echo $data;
    }

    public function city_list($state_id="")
    {
    	$data = '<option value="">Select City</option>';
    	if(!empty($state_id))
    	{ 
            $city_list = $this->general->city_list($state_id); 
	        if(!empty($city_list))
	        {
	        	foreach($city_list as $city)
	        	{
	               $data .= '<option value="'.$city->id.'">'.ucfirst(strtolower($city->city)).'</option>';
	        	}
	        }
    	} 
        echo $data;
    }



     public function rate_list()
    {
        $data['page_title'] = 'Rate plan list'; 
    	$rate_list = $this->rate->rate_list();  
    }


    public function doctor_specilization_list($specilization_id="",$branch_id='')
    {
        //$referral_doctor_id = $this->session->userdata('referral_doctor_id');
        $data = '<option value="">Select Consultant</option>';
        if(!empty($specilization_id))
        {
            $this->session->set_userdata('specilization_id',$specilization_id);
            $doctors_list = $this->general->doctor_specilization_list($specilization_id,$branch_id); 
            if(!empty($doctors_list))
            {
                foreach($doctors_list as $doctors)
                {
                    //if($doctors->id!==$referral_doctor_id){

                        $data .= '<option value="'.$doctors->id.'">'.ucfirst(strtolower($doctors->doctor_name)).'</option>';
                    //}
                }
            }
        }
        else
        {
            $doctors_list = $this->general->doctor_specilization_list();
            if(!empty($doctors_list))
            {
                foreach($doctors_list as $doctors)
                {
                    if($doctors->id!==$referral_doctor_id){

                        $data .= '<option value="'.$doctors->id.'">'.ucfirst(strtolower($doctors->doctor_name)).'</option>';
                    }
                }
            }
        }

        echo $data;
    }


    public function particulars_list($particulars_id="")
    {
        if(!empty($particulars_id))
        {
            $particulars_list = $this->general->particulars_list($particulars_id); 
            if(!empty($particulars_list))
            {
                $charge = '';
                foreach($particulars_list as $particulars)
                {
                   $charge = $particulars->charge;
                }
            }
        }
            $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1);
            $json = json_encode($pay_arr,true);
            echo $json;
    }

    public function ipd_particulars_list($particulars_id="")
    {
        if(!empty($particulars_id))
        {
            $particulars_list = $this->general->ipd_particulars_list($particulars_id); 
 
            if(!empty($particulars_list))
            {
                $charge = '';
                foreach($particulars_list as $particulars)
                {
                   $charge = $particulars->charge;
                }
            }
        }
            $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1);
            $json = json_encode($pay_arr,true);
            echo $json;
    }

    public function ipd_particulars_panel($particular_id ="", $panel_id = "")
    {
        $users_data = $this->session->userdata('auth_users');
        $charge = 0;
        if(!empty($particular_id))
        {
            // $this->db->where('is_deleted',0);
            $this->db->where('branch_id',$users_data['parent_id']); 
            $this->db->where('particular_id',$particular_id)
            ->where('panel_company_id',$panel_id);
            $particulars_list =$this->db->get('hms_ipd_particular_charge')->result_array(); 
       
            if(!empty($particulars_list))
            {
                foreach($particulars_list as $particulars)
                {
                   $charge = $particulars['charge'];
                }
            }
        }
        $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1);
        $json = json_encode($pay_arr,true);
        echo $json;
    }

    public function get_doctor_available_time()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            $doctor_id = $post['doctor_id'];
            $booking_date = $post['booking_date'];
            $data = '<option value="">Select Time</option>';
            if(!empty($doctor_id))
            {
                $time_list = $this->general->doctor_time($doctor_id,$booking_date);
                //print_r($time_list); exit; 
                if(!empty($time_list))
                {
                    foreach($time_list as $time)
                    {
                        
                        $data .= '<option value="'.$time->id.'">'.date("g:i A", strtotime($time->from_time)).' - '.date("g:i A", strtotime($time->to_time)).'</option>';
                        
                    }
                }
            }
        }
        echo $data;
    }

    public function doctor_slot()
    {
        $post = $this->input->post(); 
        $doctor_id = $post['doctor_id'];
        $time_id = $post['time_id'];
        
        if(!empty($post['appointment_date']))
        {
            $appointment_date = $post['appointment_date'];
        }
        else if($post['booking_date'])
        {
            $appointment_date = $post['booking_date'];
        }
        if(!empty($doctor_id))
        {
            /*Booked slot list*/
            $booked_slot_list = $this->general->get_booked_slot($doctor_id,$time_id,$appointment_date);
            //print_r($booked_slot_list); exit;
            $booked_slot = array();
            foreach ($booked_slot_list as $booked_list) 
            {   
                $booked_slot[] = $booked_list->doctor_slot;
            } 
            /* */

            $time_list = $this->general->doctor_slot($doctor_id,$time_id);
            $per_patient_time = $this->general->per_patient_time($doctor_id);
            
            if(!empty($per_patient_time[0]->per_patient_timing))
            {
                $per_patient_timing = $per_patient_time[0]->per_patient_timing;
            }
            else
            {
               $per_patient_timing =10; 
            }
            
            //echo "<pre>"; print_r($time_list); exit;
            $time1 = strtotime($time_list[0]->time1);
            $time2 = strtotime($time_list[0]->time2);
            
            $total_time = $time2-$time1;
            $time_in_minute = $total_time/60;  
            $total_slot = $time_in_minute/$per_patient_timing;
            //echo date('H:i A', $time1);die; 
            $slot_data = '';  
            $option = "<option value=''>Select Time</option>";
            $start_slot = $time1;
            $end_slot = $start_slot+($per_patient_timing*60);
            for($i=0;$i<$total_slot;$i++)
            { 
                $slot_data = date('H:i A', $start_slot).' To '.date('H:i A', $end_slot);
                 $time_values = date('h:i:s A', $start_slot-60);
                //$slot_data .= ;
                //$slot_data .= ;  
                $start_slot = $end_slot+60;
                $end_slot = ($end_slot+($per_patient_time[0]->per_patient_timing*60));
                //$option .= "<option value='".$slot_data."'>".$slot_data."</option>";
                
                if(!in_array($slot_data, $booked_slot))
                { //".$chek."
                    $option .= "<option  value='".$time_values."'>".$slot_data."</option>";
                }
            } 
           
           /* $datetime1 = new DateTime($time_list[0]->from_timing);//
            $datetime2 = new DateTime($time_list[0]->to_timing);
            $interval = $datetime1->diff($datetime2);
            $elapsed = $interval->format('%i minutes');
            echo $elapsed; exit;*/

           
            
        }
        
        echo $option;
    }

    public function branch_menu($id="")
    {
        
        $users_data = $this->session->userdata('auth_users'); 
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
            
        }

        $this->load->model('menu/menu_model');
        $branch_menu = $this->menu_model->get_sub_master_menu($id);

        if(!empty($branch_menu))
        {
          $branch_menu_item='<ul class="child_ul parent_'.$id.'">';
          foreach($branch_menu as $menu)
          {
            $url_1 = 'javascript:void(0);';
            if(!empty($menu->url))
            {
              $url_1= base_url('/').$menu->url;
            }
            $pop_up_id = '';
            if(!empty($menu->pop_up_id))
            {
              $pop_up_id= 'onclick="return '.$menu->pop_up_id.'()"';
            }
            
            $menu_permission_1 = explode('|', $menu->section_id);
            $active_child_li_1 = array_intersect(array_values($permission_section),$menu_permission_1);
            $total_active_menu_1=1;
            if(count($active_child_li_1)!='0')
            {
              $total_active_menu_1  = count($active_child_li_1);  
            }
            if(count(array_intersect(array_values($permission_section),$menu_permission_1)) == $total_active_menu_1)
            {
                $mouse_over='';
                $arrow_key ='';
                if(empty($pop_up_id) && $url_1=='javascript:void(0);') 
                { 
                    $mouse_over = 'onmouseover="sub_menu('.$menu->id.')"'; 
                    $arrow_key = '<i class="fa fa-angle-right menu_arrow_right"></i>';
                }
                 

                $branch_menu_item .='<li><a '.$pop_up_id.' href="'.$url_1.'"'.$mouse_over.'>'.$menu->name.' '.$arrow_key.'</a><span id="menu_'.$menu->id.'"></span>
            </li>';
             
      

     }

       }
       $branch_menu_item .='</ul>';

    }
        echo $branch_menu_item;
 }

 public function branch_left_menu($id="")
 {
        
        $users_data = $this->session->userdata('auth_users'); 
        if (array_key_exists("permission",$users_data))
        {
             $permission_section = $users_data['permission']['section'];
             $permission_action = $users_data['permission']['action'];
        }
        else
        {
             $permission_section = array();
             $permission_action = array();
            
        }

        $this->load->model('menu/menu_model');
        $branch_menu = $this->menu_model->get_sub_master_menu($id);

        if(!empty($branch_menu))
        {
          $branch_menu_item='<div class="label-'.$id.'-box "> <ul class="d_ul">';//'<div class="label-'.$id.'-box dBox"><ul class="d_ul">';
          foreach($branch_menu as $menu)
          {
            $url_1 = 'javascript:void(0);';
            if(!empty($menu->url))
            {
              $url_1= base_url('/').$menu->url;
            }
            $pop_up_id = '';
            if(!empty($menu->pop_up_id))
            {
              //$pop_up_id= $menu->pop_up_id;
              $pop_up_id= 'onclick="return '.$menu->pop_up_id.'();"';
            }
            
            $menu_permission_1 = explode('|', $menu->section_id);
            $active_child_li_1 = array_intersect(array_values($permission_section),$menu_permission_1);
            $total_active_menu_1=1;
            if(count($active_child_li_1)!='0')
            {
              $total_active_menu_1  = count($active_child_li_1);  
            }
            if(count(array_intersect(array_values($permission_section),$menu_permission_1)) == $total_active_menu_1)
            {
               
                
                $on_click ='';
                $plus ='';
                if(empty($pop_up_id) && $url_1=='javascript:void(0);') 
                { 
                    
                  $on_click = 'onclick="sub_menu_left('.$menu->id.');"'; 
                  $plus = 'class="plus"';  
                    
                }

                //$branch_menu_item .= '<li><a href="">down</a></li>';
                $branch_menu_item .='<li ><a '.$pop_up_id.' '.$plus.' href="'.$url_1.'" '.$on_click.' >'.$menu->name.'</a>';
                 if(empty($pop_up_id) && $url_1=='javascript:void(0);') 
                { 
                    
                   $branch_menu_item .='<span id="left_menu_'.$menu->id.'" class="dBox"></span>
                </li>';
                    
                }
                
            }

           }
           $branch_menu_item .='</ul>
                            </div>';

        }
            echo $branch_menu_item;
     }
     
      public function get_donar_data($donor_id="",$component_id='')
    {
           /* if(!empty($donor_id))
            {
                $option='';
                $donor_list = $this->general->get_donar_comp_data($donor_id);
                $option='<select onchange="return get_comp_details(this.value,'.$donor_id.');" id="comp_id"><option>Select Component</option>';
                  if(!empty($donor_list))
                  {
                    foreach($donor_list as $donars)
                    {
                        $option.='<option value="'.$donars->component_id.'">'.$donars->component_name.'</option>';
                    }
                  }
            }
            $option.'=</select>';*/
            
            //component
            $donor_list = $this->general->get_donar_comp_data_by_id($component_id,$donor_id);
            $donor_bar_code_list = $this->general->get_donar_bar_code_list($component_id,$donor_id);
            
             $option_bar_code='<select name="bar_code" id="bar_code" onchange="return bar_code_data(this.value);"><option>Select Barcode</option>';
                  if(!empty($donor_bar_code_list))
                  {
                    foreach($donor_bar_code_list as $bar_code)
                    {
                        $option_bar_code.='<option value="'.$bar_code->bar_code.'">'.$bar_code->bar_code.'</option>';
                    }
                  }
                  $option_bar_code.'=</select>';
                  
            //component
            
            
           $pay_arr = array('expiry_date'=>date('d-m-Y H:i:s',strtotime($donor_list->expiry_date)),'quantity'=>1,'component_price'=>$donor_list->component_price,'bar_code'=>$option_bar_code);
            $json = json_encode($pay_arr,true);
            echo $json; 
            
            /*$pay_arr = array('option'=>$option,'quantity'=>1,'bar_code'=>$option_bar_code);
            $json = json_encode($pay_arr,true);
            echo $json;*/
    }
      public function get_donar_data_old($donor_id="")
    {
            if(!empty($donor_id))
            {
                $option='';
                $donor_list = $this->general->get_donar_comp_data($donor_id);
                $option='<select onchange="return get_comp_details(this.value,'.$donor_id.');" id="comp_id"><option>Select Component</option>';
                  if(!empty($donor_list))
                  {
                    foreach($donor_list as $donars)
                    {
                        $option.='<option value="'.$donars->component_id.'">'.$donars->component_name.'</option>';
                    }
                  }
            }
            $option.'=</select>';
            $pay_arr = array('option'=>$option,'quantity'=>1);
            $json = json_encode($pay_arr,true);
            echo $json;
    }
    public function get_comp_detail($comp_id="",$donor_id="",$blood_group_id="")
    {
        $stock_data=$this->stock_model->get_stock_quantity('',$comp_id,'','','',$blood_group_id);
        if(!empty($comp_id))
        {
            $donor_list = $this->general->get_donar_comp_data_by_id($comp_id,$donor_id);
            $donor_bar_code_list = $this->general->get_donar_bar_code_list($comp_id,$donor_id);
            
             $option_bar_code='<select name="bar_code" id="bar_code" onchange="return bar_code_data(this.value);"><option>Select Barcode</option>';
                  if(!empty($donor_bar_code_list))
                  {
                    foreach($donor_bar_code_list as $bar_code)
                    {
                        $option_bar_code.='<option value="'.$bar_code->bar_code.'">'.$bar_code->bar_code.'</option>';
                    }
                  }
                  $option_bar_code.'=</select>';

        } 

        $get_stock_quantity=$this->general->get_stock_available($comp_id,$donor_id);
        $pay_arr = array('expiry_date'=>date('d-m-Y H:i:s',strtotime($donor_list->expiry_date)),'quantity'=>1,'component_price'=>$donor_list->component_price,'bar_code'=>$option_bar_code);
            $json = json_encode($pay_arr,true);
            echo $json; 
    }

    public function get_stock_available($comp_id="",$donor_id="",$blood_group_id="",$bar_code="")
    {
         $stock_data=$this->stock_model->get_stock_quantity_new_with_expiry_check('',$comp_id,'','',$bar_code,$blood_group_id);
         if($stock_data['total_qty']<=0)
         {
            $pay_arr = array('message'=>'qty not available','success'=>1,'quantity'=>0);
            $json = json_encode($pay_arr,true);
            echo $json; 
         }
         else
         {
                $pay_arr = array('message'=>'','success'=>0,'quantity'=>$stock_data['total_qty']);
                $json = json_encode($pay_arr,true);
                echo $json;
         }
        
    }
    
      public function get_stock_available_donor_data($component_id="",$blood_group_id='')
    {
        
            if(!empty($component_id))
            {
                $option='';
                $donor_list = $this->general->get_stock_available_donor_data($component_id,$blood_group_id);
                
                  if(!empty($donor_list))
                  {
                      
                       $option.='<option value="">Select Donor</option>';
                    foreach($donor_list as $donarlist)
                    {
                        $result_status = $this->general->check_donation_status($donarlist->id,$component_id,$blood_group_id);
                        //echo "<pre>"; print_r($result_status); exit;
                        if($result_status['is_issued']=='0')
                        {
                            $option.='<option value="'.$donarlist->id.'_'.$donarlist->blood_group_id.'" >'.$donarlist->donor_code.'</option>';
                        }
                        else
                        {
                            
                        }
                        
                    }
                  }
            }
            
            
            
            //$option.'=</select>';
            $pay_arr = array('donor_option'=>$option);
            $json = json_encode($pay_arr,true);
            echo $json;
    }
    
      public function amb_particulars_list($particulars_id="")	
    {	
        if(!empty($particulars_id))	
        {	
            $particulars_list = $this->general->amb_particulars_list($particulars_id); 	
            if(!empty($particulars_list))	
            {	
                $charge = '';	
                foreach($particulars_list as $particulars)	
                {	
                   $charge = $particulars->charge;	
                }	
            }	
        }	
            $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1 ,'particulars_code'=>$particulars_code);	
            $json = json_encode($pay_arr,true);	
            echo $json;	
    }
    
    public function get_schedule_available_time()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            $schedule_id = $post['schedule_id'];
            $booking_date = $post['booking_date'];
            $data = '<option value="">Select Time</option>';
            if(!empty($schedule_id))
            {
                $time_list = $this->general->schedule_time($schedule_id,$booking_date);
                //print_r($time_list); exit; 
                if(!empty($time_list))
                {
                    foreach($time_list as $time)
                    {
                        
                        $data .= '<option value="'.$time->id.'">'.date("g:i A", strtotime($time->from_time)).' - '.date("g:i A", strtotime($time->to_time)).'</option>';
                        
                    }
                }
            }
        }
        echo $data;
    }
    
    public function schedule_slot()
    {
        $post = $this->input->post(); 
        $schedule_id = $post['schedule_id'];
        $time_id = $post['time_id'];
        
        if(!empty($post['appointment_date']))
        {
            $appointment_date = $post['appointment_date'];
        }
        else if($post['booking_date'])
        {
            $appointment_date = $post['booking_date'];
        }
        if(!empty($schedule_id))
        {
            /*Booked slot list*/
            $booked_slot_list = $this->general->get_dialysis_booked_slot($schedule_id,$time_id,$appointment_date);
            //print_r($booked_slot_list); exit;
            $booked_slot = array();
            foreach ($booked_slot_list as $booked_list) 
            {   
                $booked_slot[] = $booked_list->doctor_slot;
            } 
            /* */

            $time_list = $this->general->schedule_slot($schedule_id,$time_id);
            $per_patient_time = $this->general->dialysis_per_patient_time($schedule_id);
            //echo "<pre>"; print_r($time_list); exit;
            $time1 = strtotime($time_list[0]->time1);
            $time2 = strtotime($time_list[0]->time2);
            
            $total_time = $time2-$time1;
            $time_in_minute = $total_time/60;  
            $total_slot = $time_in_minute/$per_patient_time[0]->per_patient_timing;
            //echo date('H:i A', $time1);die; 
            $slot_data = '';  
            $option = "<option value=''>Select Time</option>";
            $start_slot = $time1;
            $end_slot = $start_slot+($per_patient_time[0]->per_patient_timing*60);
            for($i=0;$i<$total_slot;$i++)
            { 
                $slot_data = date('H:i A', $start_slot).' To '.date('H:i A', $end_slot);
                 $time_values = date('h:i:s A', $start_slot-60);
                //$slot_data .= ;
                //$slot_data .= ;  
                $start_slot = $end_slot+60;
                $end_slot = ($end_slot+($per_patient_time[0]->per_patient_timing*60));
                
                if(!in_array($slot_data, $booked_slot))
                { //".$chek."
                    $option .= "<option  value='".$time_values."'>".$slot_data."</option>";
                }
            } 
           
         
           
            
        }
        
        echo $option;
    }
    
     public function dialysis_particulars_list($particulars_id="")
    {
        if(!empty($particulars_id))
        {
            $particulars_list = $this->general->ipd_particulars_list($particulars_id); 
            if(!empty($particulars_list))
            {
                $charge = '';
                foreach($particulars_list as $particulars)
                {
                   $charge = $particulars->charge;
                }
            }
        }
            $pay_arr = array('charges'=>$charge, 'amount'=>$charge,'quantity'=>1);
            $json = json_encode($pay_arr,true);
            echo $json;
    }
}
?>