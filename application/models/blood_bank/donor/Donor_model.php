<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Donor_model extends CI_Model 
{
  	var $table = 'hms_blood_donor';
    var $column = array('hms_blood_donor.id','hms_blood_donor.donor_name','hms_blood_donor.donor_email','hms_blood_donor.remark','hms_blood_donor.status','hms_blood_donor.created_date','hms_blood_donor.modified_date');  
    var $order = array('id' => 'desc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $users_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('donor_search');
       //die;
        $this->db->select("hms_blood_donor.*, hms_simulation.simulation,  hms_gardian_relation.relation, sim.simulation as relation_simulation,
            (CASE WHEN hms_blood_donor.gender=1 THEN 'Male' WHEN hms_blood_donor.gender=0 THEN 'Female' ELSE 'Other' END ) as gender, concat_ws(' ',hms_blood_donor.address,hms_blood_donor.address2,hms_blood_donor.address3) as address, hms_cities.city, hms_state.state, hms_blood_group.blood_group, hms_blood_mode_of_donation.mode_of_donation,hms_blood_preferred_reminder_service.preferred_reminder_service,hms_blood_examination.outcome,hms_blood_examination.id as exam_id"); 
     
        $this->db->join('hms_patient','hms_patient.id=hms_blood_donor.patient_id','Left');
        $this->db->join('hms_simulation', 'hms_simulation.id=hms_patient.simulation_id', 'Left');
        $this->db->join('hms_simulation as sim', 'sim.id=hms_patient.relation_simulation_id', 'Left');
        $this->db->join("hms_gardian_relation", "hms_gardian_relation.id=hms_patient.relation_type",'Left');
        $this->db->join('hms_relation', 'hms_relation.id=hms_patient.relation_type', 'Left');
        $this->db->join('hms_cities','hms_cities.id=hms_patient.city_id','left');
        $this->db->join('hms_state','hms_state.id=hms_patient.state_id','left');
        $this->db->join('hms_blood_group','hms_blood_group.id=hms_blood_donor.blood_group_id','Left');
         $this->db->join('hms_blood_examination','hms_blood_examination.donor_id=hms_blood_donor.id','Left');
         
        $this->db->join('hms_blood_mode_of_donation','hms_blood_mode_of_donation.id=hms_blood_donor.mode_of_donation','Left');
        $this->db->join('hms_blood_preferred_reminder_service','hms_blood_preferred_reminder_service.id=hms_blood_donor.reminder_service_id','left');
        $this->db->from($this->table); 
        $this->db->where('hms_blood_donor.is_deleted','0');
        $this->db->where('hms_blood_donor.branch_id = "'.$users_data['parent_id'].'"');
        $i = 0;
        if(isset($search) && !empty($search))
        {
            
            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_blood_donor.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_blood_donor.created_date <= "'.$end_date.'"');
            }

            if(isset($search['simulation_id']) && !empty($search['simulation_id']))
            {
                $this->db->where('hms_blood_donor.simulation_id',$search['simulation_id']);
            }

            if(isset($search['donor_name']) && !empty($search['donor_name']))
            {
                $this->db->where('hms_blood_donor.donor_name LIKE "%'.trim($search['donor_name']).'%"');
            }

            if(isset($search['donor_id']) && !empty($search['donor_id']))
            {
                $this->db->where('hms_blood_donor.donor_code',$search['donor_id']);
            }
             if(isset($search['blood_group']) && !empty($search['blood_group']))
            {
                $this->db->where('hms_blood_donor.blood_group_id',$search['blood_group']);
            }

             if(isset($search['qc_result']) && !empty($search['qc_result']))
            {
               $this->db->join('hms_blood_qc_examination','hms_blood_qc_examination.donor_id=hms_blood_donor.id');
               $this->db->where('hms_blood_qc_examination.blood_condition',$search['qc_result']);
            }
           



            if(isset($search['outcome']) && !empty($search['outcome']))
            {

                $this->db->where('hms_blood_examination.outcome',$search['outcome']);
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
        
        
        
            if(isset($search['branch_id']) && !empty($search['branch_id']))
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
       //echo $this->db->last_query();die;
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
    
    
    public function save_all_blood_donor($patient_all_data = array())
	{
		//echo "<pre>"; print_r($patient_all_data); die;
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($patient_all_data))
        {
            foreach($patient_all_data as $patient_data)
            {
                    
                    if($patient_data['blood_group_id']=='B+Ve')
			        {
			          $blood_group_id=4;  
			        }
			        else if($patient_data['blood_group_id']=='B+VE')
			        {
			             $blood_group_id=4;   
			        }
			        else if($patient_data['blood_group_id']=='B+ve')
			        {
			             $blood_group_id=4;   
			        }
			        else if($patient_data['blood_group_id']=='O+Ve')
			        {
			             $blood_group_id=8;   
			        }
			        
			        else if($patient_data['blood_group_id']=='O+ve')
			        {
			             $blood_group_id=8;   
			        }
			        else if($patient_data['blood_group_id']=='O+VE')
			        {
			            $blood_group_id=8;  
			        }
			        else if($patient_data['blood_group_id']=='A+ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['blood_group_id']=='A+Ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['blood_group_id']=='A+VE')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['blood_group_id']=='AB+ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['blood_group_id']=='AB+Ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['blood_group_id']=='AB+VE')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['blood_group_id']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group_id']=='O-Ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group_id']=='O-VE')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group_id']=='O-Ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group_id']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group_id']=='O-VE')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['blood_group_id']=='B-VE')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['blood_group_id']=='B-Ve')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['blood_group_id']=='B-ve')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['blood_group_id']=='NT')
			        {
			            $blood_group_id=4;  
			        }
			        else if($patient_data['blood_group_id']=='')
			        {
			            $blood_group_id=4;  
			        }
			        $mode_of_donation=1;
			        if(strtolower($patient_data['mode_of_donation'])=='center')
			        {
			         $mode_of_donation=2;   
			        }
			        if(strtolower($patient_data['gender'])=='m')
			        {
			            $gender =1;
			        }
			        else if(strtolower($patient_data['gender'])=='f')
			        {
			            $gender=0;
			        }
			        
			        $marital_status =0;
			        if($patient_data['marital_status']=='1')
			        {
			         $marital_status=1;   
			        }
			        
			        if(is_numeric($patient_data['reg_date']))
					{
					    $collDates = $patient_data['reg_date'];
                        $timestampd = $collDates * 60 * 60 * 24;
                        $reg_dates = date('Y-m-d', $timestampd);
                        
                        $time = strtotime($reg_dates.' -70 years');
                        $reg_date = date("Y-m-d", $time);
					    
					}
					else
					{
					    $reg_date = date('Y-m-d',strtotime($patient_data['reg_date']));
					}
					
					if(is_numeric($patient_data['dob']))
					{
					    $collDates = $patient_data['dob'];
                        $timestampd = $collDates * 60 * 60 * 24;
                        $dobs = date('Y-m-d', $timestampd);
					    $time = strtotime($dobs.' -70 years');
                        $dob = date("Y-m-d", $time);
					}
					else
					{
					    $dob = date('Y-m-d',strtotime($patient_data['dob']));
					}
					
					
			        
				 //$date_of_reg=date('2021-08-01');
				 $donor_code = generate_unique_id(41);
			     $donor_data_array=array(
                           'donor_name'=>$patient_data['donor_name'],
                           'data_reg_no'=>$patient_data['reg_no'],
                           'unit_no'=>$patient_data['unit_no'],
                           'gender'=>$gender,
                            'dob'=>$dob,
                           'age_y'=>$patient_data['age_y'],
                           'mobile_no'=>$patient_data['mobile_no'],
                           'address'=>$patient_data['address'],
                           'marital_status'=>$marital_status,
                           'mode_of_donation'=>$mode_of_donation,
                           'height'=>$patient_data['height'],
                            'weight'=>$patient_data['weight'],
                           'blood_group_id'=>$blood_group_id,
                           'registration_date'=>$reg_date,
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );

                          // $reg_no = generate_unique_id(41);
                        $donor_data_array['donor_code']=$donor_code;
                        $donor_data_array["branch_id"]=$users_data['parent_id'];
                        $donor_data_array["created_by"]=$users_data['id'];
                        $donor_data_array["created_date"]=$reg_date;
            
					    $this->db->insert('hms_blood_donor',$donor_data_array);
					    $donor_id = $this->db->insert_id(); 
					    //echo $this->db->last_query(); exit;
			        
			        
                }
               	
        }
	}




// Please write code above    
}