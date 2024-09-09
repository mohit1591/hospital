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
}
