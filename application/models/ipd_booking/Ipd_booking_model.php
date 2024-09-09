<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipd_booking_model extends CI_Model 
{
    var $table = 'hms_ipd_booking';
    var $column = array('hms_ipd_booking.id','hms_ipd_booking.ipd_no',  'hms_patient.patient_code','hms_patient.patient_name','hms_patient.mobile_no', 'hms_ipd_booking.admission_date', 'hms_doctors.doctor_name', 'hms_ipd_rooms.room_no', 'hms_ipd_room_to_bad.bad_no' , 'hms_patient.address', 'hms_ipd_booking.remarks'
    ,'hms_patient.father_husband', 'hms_patient.gender', 'hms_patient.patient_email', 'ins_type.insurance_type', 'ins_cmpy.insurance_company', 'hms_ipd_booking.mlc', 'pkg.name', 'rm_cat.room_category', 'hms_hospital.hospital_name', 'hms_ipd_booking.advance_payment', 'hms_ipd_patient_to_charge.price', 'hms_ipd_booking.panel_polocy_no',  'hms_ipd_booking.created_date', 'hms_ipd_booking.diagnosis' );
   var $order = array('id' => 'desc'); 

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $user_data = $this->session->userdata('auth_users');
        $search = $this->session->userdata('ipd_booking_search');
        //echo "<pre>";print_r($search); exit; 
        $this->db->select("hms_ipd_booking.diagnosis, hms_ipd_booking.id,hms_ipd_booking.branch_id,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_ipd_booking.admission_date,hms_ipd_booking.admission_time,hms_ipd_booking.remarks,hms_ipd_booking.mlc,hms_ipd_booking.advance_payment,hms_ipd_booking.reg_charge,hms_ipd_booking.panel_polocy_no,hms_patient.patient_name,hms_patient.patient_code,concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d, hms_doctors.doctor_name,hms_patient.mobile_no,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_booking.created_date as createdate,hms_ipd_booking.discharge_status,     

            hms_patient.father_husband, sim.simulation as father_husband_simulation,
            (CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender,
            hms_patient.patient_email, ins_type.insurance_type, ins_cmpy.insurance_company,
            pkg.name as package_name, rm_cat.room_category as room_type, 
            (CASE WHEN hms_ipd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE concat('Dr. ',doc.doctor_name) END) as doctor_hospital_name,hms_ipd_patient_to_charge.price as reg_charge,hms_ipd_booking.discharge_date"); 


        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id AND hms_patient.is_deleted!=2','left'); 
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left'); 
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');

        $this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');
        $this->db->join('hms_insurance_type as ins_type','ins_type.id=hms_ipd_booking.panel_type', 'left');
        $this->db->join('hms_insurance_company as ins_cmpy','ins_cmpy.id=hms_ipd_booking.panel_name', 'left');

        $this->db->join('hms_ipd_packages as pkg','pkg.id = hms_ipd_booking.package_id','left');
        $this->db->join('hms_ipd_room_category as rm_cat','rm_cat.id = hms_ipd_booking.room_type_id','left');
        $this->db->join('hms_doctors as doc','doc.id = hms_ipd_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_ipd_booking.referral_hospital','left');

        $this->db->join('hms_ipd_patient_to_charge', 'hms_ipd_patient_to_charge.ipd_id=hms_ipd_booking.id and hms_ipd_patient_to_charge.type=1','left');

        $this->db->where('hms_ipd_booking.is_deleted','0'); 
        $this->db->group_by('hms_ipd_booking.id');

        if($user_data['users_role']==4)
        {
        $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"'); 
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
        $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
        
        }
        $this->db->from($this->table); 

        
        /////// Search query start //////////////
        if($user_data['users_role']==3)
        {
        }
        else
        {
                if(isset($search['running']) && !empty($search['running']))
                {
                    if($search['running']=='1')
                    {
                        $this->db->where('hms_ipd_booking.discharge_status = "'.$search['running'].'"');  
                    }
                    
                }
                else
                {
                    $this->db->where('hms_ipd_booking.discharge_status =0');
                }
        }
        
        if(isset($search) && !empty($search))
        {
            
             if((isset($search['mlc']) && $search['mlc'] == "0") || $search['mlc'] == "1")
            {
                $mlc = $search['mlc'];
                $this->db->where('hms_ipd_booking.mlc_status = "'.$mlc.'"');
            }

            if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_ipd_booking.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_ipd_booking.created_date <= "'.$end_date.'"');
            }

                        if(isset($search['room_no']) && !empty($search['room_no']))
			{
				$this->db->where('hms_ipd_rooms.room_no',$search['room_no']);
			}


            if(isset($search['attended_doctor']) && !empty($search['attended_doctor']))
            {

                $this->db->where('hms_ipd_booking.attend_doctor_id',$search['attended_doctor']);
            }
                if(isset($search['ipd_no']) && !empty($search['ipd_no']))
            {

                $this->db->where('hms_ipd_booking.ipd_no',$search['ipd_no']);
            }

            if(isset($search['patient_name']) && !empty($search['patient_name']))
            {

               // $this->db->where('hms_patient.patient_name',$search['patient_name']);
               $this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
            }

            if(isset($search['patient_code']) && !empty($search['patient_code']))

            {
                
              $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
            }

            if(isset($search['mobile_no']) && !empty($search['mobile_no']))
            {
               $this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
            }
            if(isset($search['adhar_no']) && !empty($search['adhar_no']))

            {
                
              $this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
            }

            if($search['insurance_type']!='')
              {
                $this->db->where('hms_ipd_booking.patient_type',$search['insurance_type']);
              }

              if(!empty($search['insurance_type_id']))
              {
                $this->db->where('hms_ipd_booking.panel_type',$search['insurance_type_id']);
              }

              if(!empty($search['ins_company_id']))
              {
                $this->db->where('hms_ipd_booking.panel_name',$search['ins_company_id']);
              }

        }

        $emp_ids='';
        if($user_data['emp_id']>0)
        {
            if($user_data['record_access']=='1')
            {
                $emp_ids= $user_data['id'];
            }
        }
        elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }


        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_booking.created_by IN ('.$emp_ids.')');
        }

        /////// Search query end //////////////
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
        // echo $this->db->last_query();die;
        return $query->result();
    }


    function get_all_detail_print($ids="")
    {
        $result_ipd=array(); //insurance_company_name
        $user_data = $this->session->userdata('auth_users'); //*,hms_patient.*,hms_users.*,
        $this->db->select("hms_ipd_booking.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_patient.patient_email, hms_patient.patient_code,hms_patient.id as patient_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.dob,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.simulation_id,hms_patient.address as address1,hms_patient.address2, hms_patient.address3,hms_patient.pincode,hms_ipd_packages.name as package_name,hms_doctors.doctor_name as attented_doctor,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_room_category.room_category,hms_payment_mode.payment_mode,hms_ipd_booking.created_date as createdate,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,  concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.adhar_no,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name, (CASE WHEN hms_patient.marital_status=1 THEN 'Married' ELSE 'Single' END ) as marital_status, hms_patient.anniversary, hms_religion.religion as religion_name, hms_patient.dob, hms_patient.relation_type, hms_patient.relation_name, hms_patient.mother, hms_patient.guardian_name, hms_patient.guardian_email, hms_patient.guardian_phone, hms_relation.relation, hms_patient.patient_email, hms_patient.monthly_income,hms_patient.occupation,  (CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type, hms_insurance_type.insurance_type as insurance_type_name, hms_insurance_company.insurance_company as insurance_company_name, hms_patient.polocy_no as insurance_policy_no,hms_patient.tpa_id, hms_patient.ins_amount as insurance_amount, hms_patient.ins_authorization_no as auth_no, (CASE WHEN hms_ipd_booking.referred_by=1 THEN 'Hospital' ELSE 'Doctor' END ) as referrered_by , (CASE WHEN hms_ipd_booking.referred_by=1 THEN ref_hospital.hospital_name ELSE ref_doctor.doctor_name END) as referrer_name, hms_ipd_booking.admission_date, hms_ipd_booking.admission_time,hms_sms.simulation as rel_simulation,hms_gardian_relation.relation,hms_patient.relation_simulation_id,hms_patient.relation_type,hms_patient.relation_name");
            $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id','left');
            $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
            $this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
            $this->db->join('hms_simulation as hms_sms','hms_sms.id = hms_patient.relation_simulation_id','left');
            $this->db->join('hms_ipd_packages','hms_ipd_packages.id = hms_ipd_booking.package_id','left');
           // $this->db->join('hms_insurance_company','hms_insurance_company.id = hms_ipd_booking.panel_name','left');
            $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_ipd_booking.payment_mode','left');
            //$this->db->join('hms_insurance_type','hms_insurance_type.id = hms_ipd_booking.panel_type','left');
            $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
            $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
            $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
            $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
            $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_ipd_booking.id AND hms_branch_hospital_no.section_id=3','left'); 
            $this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by');  
            $this->db->where('hms_ipd_booking.is_deleted','0'); 
        // added on 08-Feb-2018
        $this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
        $this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
        $this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
        $this->db->join('hms_religion','hms_religion.id = hms_patient.religion_id','left'); // Religion name
        $this->db->join('hms_relation',' hms_relation.id = hms_patient.relation_id','left'); // Relation name
        $this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_ipd_booking.panel_type','left'); // insurance type name
        $this->db->join('hms_insurance_company','hms_insurance_company.id = hms_ipd_booking.panel_name','left'); // insurance company
        $this->db->join('hms_doctors as ref_doctor','ref_doctor.id = hms_ipd_booking. referral_doctor','left');
        $this->db->join('hms_hospital as ref_hospital','ref_hospital.id = hms_ipd_booking. referral_hospital','left');
        $this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
        $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
        if($user_data['users_role']==4)
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$branch_id.'"');  
            }
            else
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"');
            }
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$branch_id.'"');   
            }
            else
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
            }
        }   
        $this->db->where('hms_ipd_booking.id = "'.$ids.'"');
        $this->db->from($this->table);
       
        $result_ipd['ipd_list']= $this->db->get()->result();
        //echo $this->db->last_query(); //exit;
        $this->db->select('hms_signature.*');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.attend_doctor_id = hms_signature.doctor_id');  
        $this->db->where('hms_ipd_booking.id = "'.$ids.'"');
        $this->db->where('hms_signature.is_deleted','0'); 
        $this->db->where('hms_signature.signature_type','2'); 
        if($user_data['users_role']==3)
        {
            $this->db->where('hms_signature.doctor_id = "'.$user_data['parent_id'].'"'); 
        }
        else
        {
            $this->db->where('hms_signature.branch_id = "'.$user_data['parent_id'].'"');
        }   
        $this->db->from('hms_signature');
        $result_ipd['signature_data']=$this->db->get()->result();
        //echo $this->db->last_query();
        return $result_ipd;
        
    }


    public function get_ipd_detail_by_id($id)
    {
        $this->db->select('hms_ipd_booking.*,hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_room_category.room_category');
        $this->db->from('hms_ipd_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id'); 
        
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');

        $this->db->where('hms_ipd_booking.id',$id);
        $this->db->where('hms_ipd_booking.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }


    function search_report_data()
    {
        $search = $this->session->userdata('ipd_booking_search');
        $user_data = $this->session->userdata('auth_users');
        $this->db->select("hms_ipd_booking.*,hms_patient.*,hms_users.*,hms_ipd_packages.name as package_name,hms_ipd_panel_company.panel_company,hms_ipd_panel_type.panel_type,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_room_category.room_category,hms_ipd_booking.created_date as createdate, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address,hms_patient.age,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,ins_type.insurance_type, ins_cmpy.insurance_company"); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id','left');
        
        $this->db->join('hms_insurance_type as ins_type','ins_type.id=hms_ipd_booking.panel_type', 'left');
        $this->db->join('hms_insurance_company as ins_cmpy','ins_cmpy.id=hms_ipd_booking.panel_name', 'left');
        
        $this->db->join('hms_ipd_packages','hms_ipd_packages.id = hms_ipd_booking.package_id','left');
        $this->db->join('hms_ipd_panel_company','hms_ipd_panel_company.id = hms_ipd_booking.panel_name','left');
        $this->db->join('hms_ipd_panel_type','hms_ipd_panel_type.id = hms_ipd_booking.panel_type','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
        
        $this->db->join('hms_doctors','hms_doctors.id = hms_ipd_booking.attend_doctor_id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
        
        
        $this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by','left');  
        $this->db->where('hms_ipd_booking.is_deleted','0'); 

        if($user_data['users_role']==4)
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$branch_id.'"');  
            }
            else
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"'); 
            }
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }   
        else
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$branch_id.'"');   
            }
            else
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
            }
        
        }
             
            if(isset($search['running']) && !empty($search['running']))
                {
                    if($search['running']=='1')
                    {
                    $this->db->where('hms_ipd_booking.discharge_status = "'.$search['running'].'"');
                    }
                }
                else
                {
                    $this->db->where('hms_ipd_booking.discharge_status =0');
                }
        if(isset($search['attended_doctor']) && !empty($search['attended_doctor']))
            {

                $this->db->where('hms_ipd_booking.attend_doctor_id',$search['attended_doctor']);
            }
                if(isset($search['ipd_no']) && !empty($search['ipd_no']))
            {

                $this->db->where('hms_ipd_booking.ipd_no',$search['ipd_no']);
            }

        
        $this->db->from($this->table);
        


        /////// Search query start //////////////

        if(isset($search) && !empty($search))
        {
            
             if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_ipd_booking.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_ipd_booking.created_date <= "'.$end_date.'"');
            }
            if(isset($search['mlc']) || $search['mlc'] == "0" || $search['mlc'] == "1")
            {
                $mlc = $search['mlc'];
                $this->db->where('hms_ipd_booking.mlc_status = "'.$mlc.'"');
            }

            if(isset($search['patient_name']) && !empty($search['patient_name']))
            {

                $this->db->where('hms_patient.patient_name',$search['patient_name']);
            }
            if(isset($search['adhar_no']) && !empty($search['adhar_no']))

            {
                
              $this->db->where('hms_patient.adhar_no LIKE "'.$search['adhar_no'].'%"');
            }

            if(isset($search['room_no']) && !empty($search['room_no']))
	    {
		$this->db->where('hms_ipd_rooms.room_no',$search['room_no']);
	    }



            if(isset($search['patient_code']) && !empty($search['patient_code']))

            {
                
              $this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
            }

            if(isset($search['mobile_no']) && !empty($search['mobile_no']))
            {
                $this->db->where('hms_patient.mobile LIKE "'.$search['mobile_no'].'%"');
            }

           

            if($search['insurance_type']!="")
              {
                $this->db->where('hms_ipd_booking.patient_type',$search['insurance_type']);
              }

              if(!empty($search['insurance_type_id']))
              {
                $this->db->where('hms_ipd_booking.panel_type',$search['insurance_type_id']);
              }

              if(!empty($search['ins_company_id']))
              {
                $this->db->where('hms_ipd_booking.panel_name',$search['ins_company_id']);
              }

            

        }
        $emp_ids='';
        if($user_data['emp_id']>0)
        {
            if($user_data['record_access']=='1')
            {
                $emp_ids= $user_data['id'];
            }
        }
        elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }


        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_booking.created_by IN ('.$emp_ids.')');
        }
        $this->db->order_by('hms_ipd_booking.id','desc');
        $query = $this->db->get(); 
        $data= $query->result();
        //echo $this->db->last_query();die;
        return $data;
    }
    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $search = $this->session->userdata('ipd_booking_search');
        $user_data = $this->session->userdata('auth_users');
        $this->db->from('hms_ipd_booking');

         if($user_data['users_role']==4)
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$branch_id.'"');  
            }
            else
            {
                $this->db->where('hms_ipd_booking.patient_id = "'.$user_data['parent_id'].'"'); 
            }
        }
        elseif($user_data['users_role']==3)
        {
            $this->db->where('hms_ipd_booking.referral_doctor = "'.$user_data['parent_id'].'"'); 
        }   
        else
        {
            if(!empty($branch_id))
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$branch_id.'"');   
            }
            else
            {
                $this->db->where('hms_ipd_booking.branch_id = "'.$user_data['parent_id'].'"'); 
            }
        
        }
             
            if(isset($search['running']) && !empty($search['running']))
                {
                    if($search['running']=='1')
                    {
                    $this->db->where('hms_ipd_booking.discharge_status = "'.$search['running'].'"');
                    }
                }
                else
                {
                    $this->db->where('hms_ipd_booking.discharge_status =0');
                }
        $this->db->where('hms_ipd_booking.is_deleted','0'); 

        $search = $this->session->userdata('ipd_booking_search');
        $user_data = $this->session->userdata('auth_users');

        if(isset($search) && !empty($search))
        {
            
             if(isset($search['start_date']) && !empty($search['start_date']))
            {
                $start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
                $this->db->where('hms_ipd_booking.created_date >= "'.$start_date.'"');
            }

            if(isset($search['end_date']) && !empty($search['end_date']))
            {
                $end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
                $this->db->where('hms_ipd_booking.created_date <= "'.$end_date.'"');
            }


            
          

            

        }
        $emp_ids='';
        if($user_data['emp_id']>0)
        {
            if($user_data['record_access']=='1')
            {
                $emp_ids= $user_data['id'];
            }
        }
        elseif(!empty($get["employee"]) && is_numeric($get['employee']))
        {
            $emp_ids=  $get["employee"];
        }


        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_booking.created_by IN ('.$emp_ids.')');
        }

        $query = $this->db->get(); 
        $data= $query->result();
        //echo $this->db->last_query(); exit;
        return count($data);
        /*$this->db->from($this->table);
        
        return $this->db->count_all_results();*/
    }

    public function get_by_id($id="",$ipd_no="")
    {
        $this->db->select('hms_ipd_booking.*,hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_ipd_rooms.room_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.mobile_no, hms_ipd_room_to_bad.bad_no as bad_name');
        $this->db->from('hms_ipd_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id'); 
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id = hms_ipd_booking.bad_id','left');
        if(!empty($id))
            $this->db->where('hms_ipd_booking.id',$id);
        if(!empty($ipd_no))
            $this->db->where('hms_ipd_booking.ipd_no',$ipd_no);
        $this->db->where('hms_ipd_booking.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }
    public function get_vendor_by_id($id)
    {

        $this->db->select('hms_medicine_vendors.*');
        $this->db->from('hms_medicine_vendors'); 
        $this->db->where('hms_medicine_vendors.id',$id);
        //$this->db->where('hms_medicine_vendors.is_deleted','0');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->row_array();
    }

    public function get_medicine_by_purchase_id($id="")
    {
        $this->db->select('hms_medicine_purchase_to_purchase.*');
        $this->db->from('hms_medicine_purchase_to_purchase'); 
        if(!empty($id))
        {
          $this->db->where('hms_medicine_purchase_to_purchase.purchase_id',$id);
        } 
        $query = $this->db->get()->result(); 
        $result = [];
        if(!empty($query))
        {
          foreach($query as $medicine)  
          {
                $ratewithunit1= $medicine->purchase_rate*$medicine->unit1;
                $perpic_rate= 0;
                $ratewithunit2=$perpic_rate*$medicine->unit1;
                $tot_qty_with_rate=$ratewithunit1+ $ratewithunit2;
                $vatToPay = ($tot_qty_with_rate / 100) * $medicine->vat;
                $totalPrice = $tot_qty_with_rate + $vatToPay;
                $total_discount = ($medicine->discount/100)*$totalPrice;
                $total_amount= $totalPrice-$total_discount;
                
                $result[$medicine->medicine_id] = array('mid'=>$medicine->medicine_id,'conversion'=>$medicine->conversion,'unit1'=>$medicine->unit1,'unit2'=>$medicine->unit2,'perpic_rate'=>$perpic_rate,'batch_no'=>$medicine->batch_no,'manuf_date'=>date('d-m-Y',strtotime($medicine->manuf_date)),'mrp'=>$medicine->mrp,'qty'=>$medicine->qty, 'exp_date'=>date('d-m-Y',strtotime($medicine->expiry_date)),'discount'=>$medicine->discount,'vat'=>$medicine->vat, 'purchase_amount'=>$medicine->purchase_rate, 'total_amount'=>$medicine->total_amount); 
          } 
        } 
        
        return $result;
    }


    public function save()
    {
      

        $user_data = $this->session->userdata('auth_users');
        $type_value= get_ipd_discharge_time_setting_value();

        $post = $this->input->post();
       // echo "<pre>"; print_r($post); exit;
            $panel_type = '';
            if(!empty($post['panel_type']))
            {
                $panel_type = $post['panel_type'];
            }

            $ins_company_id = '';
            if(!empty($post['company_name']))
            {
                $ins_company_id = $post['company_name'];
            }
            $policy_no = '';
            if(!empty($post['policy_number']))
            {
                $policy_no = $post['policy_number'];
            }
            $id_number = '';
            if(!empty($post['id_number']))
            {
                $id_number = $post['id_number'];
            }
            $authorization_amount = '';
            if(!empty($post['authorization_amount']))
            {
                $authorization_amount = $post['authorization_amount'];
            }

            $patient_type = '';
            if(!empty($post['patient_type']))
            {
                $patient_type = $post['patient_type'];
            }
           

        $data_patient = array( 
                "patient_name"=>$post['name'],
                'simulation_id'=>$post['simulation_id'],
                'branch_id'=>$user_data['parent_id'],
                'relation_type'=>$post['relation_type'],
                'relation_name'=>$post['relation_name'],
                'relation_simulation_id'=>$post['relation_simulation_id'],
                'gender'=>$post['gender'],
                'age_m'=>$post['age_m'],
                'age_y'=>$post['age_y'],
                'age_d'=>$post['age_d'],
                "adhar_no"=>$post['adhar_no'],
                "address"=>$post['address'],
                "address2"=>$post['address_second'],
                "address3"=>$post['address_third'],
                'mobile_no'=>$post['mobile'],
                'insurance_type'=>$patient_type,
                'insurance_type_id'=>$panel_type,
                'ins_company_id'=>$ins_company_id,
                'polocy_no'=>$policy_no,
                'tpa_id'=>$id_number,
                'ins_authorization_no'=>$authorization_amount,
                'patient_category'=>$post['patient_category'],
               
             );
             $diagnosis = "";
             if(count($post['diagnosis'])) {
                $diagnosis_ids = $post['diagnosis'];// Array of diagnosis IDs
                $this->db->where_in('id', $diagnosis_ids);
                $result = $this->db->get('hms_opd_diagnosis')->result_array();
                $d = [];
                foreach($result as $dig) {
                    $d[] = $dig['diagnosis'];
                }
                $diagnosis = implode(';', $d);
             }
             
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
            /* start edit booking */
             
            //Update Start
            $booking_detail= $this->get_by_id($post['data_id']);
            $patient_id_new= $this->get_patient_by_id($booking_detail['patient_id']);
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id', $post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
          
            
            if(isset($post['id_number'])){ $panel_id_no=$post['id_number']; }else{ $panel_id_no=''; }
            if(isset($post['company_name'])){ $panel_name=$post['company_name']; }else{ $panel_name=''; }
            if(isset($post['panel_type'])){ $panel_type=$post['panel_type']; }else{ $panel_type=''; }
            if(isset($post['policy_number'])){ $policy_number=$post['policy_number']; }else{ $policy_number=''; }
            if(isset($post['authorization_amount'])){ $authorization_amount=$post['authorization_amount']; }else{ $authorization_amount=''; }
            $packageID ='';
            if(isset($post['package_id']))
            {
                $packageID = $post['package_id'];
            }
             
                $data = array(
                    "patient_id"=>$post['patient_id'],
                    'branch_id'=>$user_data['parent_id'],
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'mlc'=>$post['mlc'],
                    'mlc_status'=>$post['mlc_status'],
                    'payment_mode'=>$post['payment_mode'],
                    'remarks'=>$post['remarks'],
                    'panel_id_no'=>$panel_id_no,
                    'room_type_id'=>$post['room_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'referral_doctor'=>$post['referral_doctor'],
                    'referred_by'=>$post['referred_by'],
                    'referral_hospital'=>$post['referral_hospital'],
                    'reg_charge'=>$post['reg_charge'],
                    'patient_type'=>$post['patient_type'],
                    'panel_name'=>$panel_name,
                    'panel_type'=>$panel_type,
                    'panel_polocy_no'=>$policy_number,
                    'admission_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['admission_time'])),
                    "package_type"=>$post['package'],
                    "package_id"=>$packageID,
                    'authrization_amount'=>$authorization_amount,
                    'admission_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'advance_payment'=>$post['advance_deposite'],
                    'patient_category'=>$post['patient_category'],
                    'authorize_person'=>$post['authorize_person'],
                    'diagnosis' => $diagnosis,
                    //'transaction_no'=>$transaction_no
                ); 
 
                $this->db->where('id',$post['data_id']);
                $this->db->set('modified_by',$user_data['id']);
                $this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->update('hms_ipd_booking',$data);
               
                /*add sales banlk detail*/

                //Bed Change on update
                if(!empty($post['data_id']))
                {   
                    $this->db->select('*');
                    $this->db->from('hms_ipd_room_to_bad');
                    $this->db->where('ipd_id',$post['data_id']);
                    $query = $this->db->get(); 
                    $result = $query->row_array();
                    $bed_id = $result['id'];
                        if(!empty($bed_id))
                        {
                            $this->db->set('status','0');
                            $this->db->set('ipd_id','0');
                            $this->db->where('id',$bed_id);
                            $this->db->update('hms_ipd_room_to_bad');
                        }
                        $this->db->set('status','1');
                        $this->db->set('ipd_id',$post['data_id']);
                        $this->db->where('id',$post['bed_no_id']);
                        $this->db->update('hms_ipd_room_to_bad');
                }


                
                $this->db->where('ipd_booking_id',$post['data_id']);
                $this->db->delete('hms_ipd_assign_doctor');

                
                
                $count_assigned_doctor= count($post['assigned_doctor_list']);
                for($i=0;$i< $count_assigned_doctor;$i++)
                {
                        $assigned_doctor = array(
                            "branch_id"=>$user_data['parent_id'],
                            'ipd_booking_id'=>$post['data_id'],
                            'doctor_id'=>$post['assigned_doctor_list'][$i],
                            'created_date'=>date('Y-m-d H:i:s')
                         );
                        $this->db->insert('hms_ipd_assign_doctor',$assigned_doctor);
                }
                

            
            //registration charge
            if(!empty($post['data_id']) && !empty($post['reg_charge']) && $post['reg_charge']!='0.00') 
            {
                $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>1));
                    $this->db->delete('hms_ipd_patient_to_charge');

                $registration_patient_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$post['data_id'],
                    'patient_id'=>$post['patient_id'],
                    'type'=>1,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                    'particular'=>'Registration Charge',
                    'price'=>$post['reg_charge'],
                    'panel_price'=>$post['reg_charge'],
                    'net_price'=>$post['reg_charge'],
                    'status'=>1,
                    'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                );
                $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
            }

            if(!empty($post['data_id']) && empty($post['package_id']) && $post['patient_type']=='1') 
            {
                     /* new ipd charge entry code */
                 
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>3));
                    $this->db->delete('hms_ipd_patient_to_charge');

                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                    $charges='';
                    //echo "<pre>";print_r($room_charge_type_list); exit;
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],'',$post['patient_type']);
                         

                         $charges_n= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))];  //['charge']
                         if(!empty($charges_n))
                         {
                            $charges = $charges_n['charge'];
                         }
                         else
                         {
                            $charges = '';
                         }
                         
                         
                         /* For Particular Code */
                        $group_name='';
                        $group_code='';
                       $detail= $this->general_model->get_charge_type_detail($room_charge_type_list[$i]['id'],$post['room_id']);
                       if(!empty($detail))
                       {
                           if(substr($detail->charge_code,0,2)==10)
                           {
                            $group_name='Room & Nursing Charges';
                            $group_code=100000;
                           } 
                       }
                       

                         /* For Particular Code ,'particular_code'=>$data_query['particular_code']*/
                         
                         
                         
                        $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>3,
                                  'room_id'=>$post['room_id'],   
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'group_name'=>$group_name,
                                'group_code'=>$group_code,
                                'particular'=>$charge_type,
                                'particular_code'=>$detail->charge_code,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>date('Y-m-d H:i:s')
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            }
            elseif(!empty($post['data_id']) && empty($post['package_id']) && $post['patient_type']=='2') 
            {
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>3));
                    $this->db->delete('hms_ipd_patient_to_charge');

                    //panel charges
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                    
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],$post['company_name'],$post['patient_type']);
                        $charges_n= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))];
                        $charges='';
                        if(!empty($charges_n))
                        {
                            $charges = $charges_n['charge'];
                        }
                        
                        /* For Particular Code ,'particular_code'=>$data_query['particular_code'] */
                        $group_name='';
                        $group_code='';
                       $detail= $this->general_model->get_charge_type_detail($room_charge_type_list[$i]['id'],$post['room_id']);
                       if(!empty($detail))
                       {
                           if(substr($detail->charge_code,0,2)==10)
                           {
                            $group_name='Room & Nursing Charges';
                            $group_code=100000;
                           } 
                       }
                       
                        $patient_charge_type = array(
                                                    "branch_id"=>$user_data['parent_id'],
                                                    'ipd_id'=>$post['data_id'],
                                                    'patient_id'=>$post['patient_id'],
                                                    'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                                    'type'=>3,
                                                    'room_id'=>$post['room_id'],   
                                                    'group_name'=>$group_name,
                                                    'group_code'=>$group_code,
                                                    'particular_code'=>$detail>charge_code,
                                                    'quantity'=>1,
                                                    'transfer_status'=>1,
                                                    'particular'=>$charge_type,
                                                    'price'=>$charges,
                                                    'panel_price'=>$charges,
                                                    'net_price'=>$charges,
                                                    'status'=>1,
                                                    'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                                                );
                        $this->db->insert('hms_ipd_patient_to_charge',$patient_charge_type);
                        
                    } 
                   }
            }
            elseif(!empty($post['data_id']) && !empty($post['package_id']) && $post['patient_type']=='1')
            {
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>4));
                    $this->db->delete('hms_ipd_patient_to_charge');
                    //for package without panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list('',$post['package_id'],1);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }
            elseif(!empty($post['data_id']) && !empty($post['package_id']) && $post['patient_type']=='2')
            {
                    $this->db->where(array('ipd_id'=>$post['data_id'],'patient_id'=>$post['patient_id'],'type'=>4));
                    $this->db->delete('hms_ipd_patient_to_charge');
                    
                    //for package with panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list($post['company_name'],$post['package_id'],2);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$post['data_id'],
                                'patient_id'=>$post['patient_id'],
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>date('Y-m-d H:i:s',strtotime($post['admission_date']))
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }

               
                /*add sales banlk detail*/
                              //Update End

/* if patient discharge then add dischrage data to */ 
        if(!empty($post['discharge_date']) && $post['discharge_date']!='1970-01-01' && $post['discharge_date']!='1970-01-01 00:00:00')
        {       
                   $users_data = $this->session->userdata('auth_users');
                   $patient_id = $post['patient_id'];
                   $ipd_id = $post['data_id'];
                   $this->db->select('*');
                   $this->db->from('hms_ipd_patient_to_charge');
                   $this->db->where('hms_ipd_patient_to_charge.type',3);
                   $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
                   $query= $this->db->get()->result();

                    foreach($query as $data)
                    {
                          
                        if($data->end_date=='0000-00-00 00:00:00')
                        {

                             $update_data=  array('end_date'=>date('Y-m-d H:i:s',strtotime($post['discharge_date'])));
                             $this->db->where('id',$data->id);
                             $this->db->update('hms_ipd_patient_to_charge',$update_data);
                        }
                    }

                
                $this->db->select('*');
                $this->db->from('hms_ipd_patient_to_charge');
                $this->db->where('hms_ipd_patient_to_charge.type',3);
                $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
                $res= $this->db->get()->result();
                //echo "<pre>";print_r($res);die;
                $res = json_decode(json_encode($res), true);
                
                $new_array=array();
                foreach($res as $data_query)
                {
                    //echo "<pre>";print_r($data_query);die;
                        $date1 = new DateTime(date('Y-m-d',strtotime($data_query['start_date'])));
                        $date2 = new DateTime(date('Y-m-d',strtotime($data_query['end_date'])));
                        $date2->modify("+1 days");
                        $interval = $date1->diff($date2);
                        $days= $interval->days;
                        
                        /* code for 24 hours */
                if(isset($type_value) && $type_value!='' && $type_value==1)
                {
                    ## hours ##

                      $time_duration= date('H:i:s',strtotime($data_query['start_date']));
                        $current_time= date('H:i:s');
                        if($interval->h<=23 && $interval->h >0 && ($current_time < $time_duration))
                        {
                           
                            $i=1;
                            while($i <= $days) 
                            {
                                    if(!empty($data_query['end_date']) && $data_query['end_date']!='')
                                    {
                                         //ECHO $data_query['end_date'];
                                        $data_query['start_date'] = date('Y-m-d H:i:s', strtotime($data_query['end_date'])-($i*86400)); 

                                        $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$post['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        );

                                        //print '<pre>'; print_r($insert_charge_entry);
                                        $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);

                                       
                                    }
                                    else
                                    {
                                        $data_query['start_date'] = date('Y-m-d H:i:s'); 
                                        $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$post['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        );

                                        //print '<pre>'; print_r($insert_charge_entry);
                                        $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                    }
                                    // $data['CHARGES'][]=$data_query;
                                    // PRINT_R($data_query);
                                    $i++;
                            }
                        }
                        else
                        {
                            
                            //echo $days;
                            for($i=0;$i<$days;$i++)
                            { 
                                if(!empty($data_query['end_date']) && $data_query['end_date']!='')
                                {
                                    
                                    $data_query['start_date'] = date('Y-m-d H:i:s', strtotime($data_query['end_date'])-($i*86400));
                                    $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                    'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$post['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                    'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                    );

                                    //print '<pre>'; print_r($insert_charge_entry);
                                    $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                }
                                else
                                {

                                $data_query['start_date'] = date('Y-m-d H:i:s'); 
                                $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$post['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                );




                                //print '<pre>'; print_r($insert_charge_entry);
                                $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                }
                               
                            } 
                            //echo '<pre>'; print_r($data['CHARGES']);die;

                        }

                         

                    ## hours ## 
                         /* code for 24 hours */

                }
                else
                {
                        
                        
                        
                        for($i=0;$i<$days;$i++)
                        { //print
                           $data_query['start_date'] = date('Y-m-d', strtotime($data_query['end_date'])-($i*86400)); 

                             $insert_charge_entry= array('branch_id'=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$post['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                );

                             //print '<pre>'; print_r($insert_charge_entry);
                            $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                           
                        }
                }
    
                        $this->db->where('id',$data_query['id']);
                        $this->db->delete('hms_ipd_patient_to_charge');
        }
        }


        $ipd_booking_id = $post['data_id'];
        
            /* end edit booking*/
        }
        else
        {

        $created_date = date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s', strtotime(date('d-m-Y').' '.$post['admission_time']));
            //New Booking start users_data
            //echo $post['package_id']; 
            //print '<pre>'; print_r($_POST);
            $patient_data= $this->get_patient_by_id($post['patient_id']);
            if(count($patient_data)>0)
            {
                $this->db->set('modified_by',$user_data['id']);
                $this->db->set('modified_date',date('Y-m-d H:i:s'));
                $this->db->where('id',$post['patient_id']);
                $this->db->update('hms_patient',$data_patient);
                $patient_id= $post['patient_id'];
            }
            else
            {
                $reg_no = generate_unique_id(4);
                $this->db->set('patient_code',$reg_no);
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',$created_date);
                $this->db->insert('hms_patient',$data_patient); 
                $patient_id= $this->db->insert_id();
                
                //user details
                $data = array(     
                            "users_role"=>4,
                            "parent_id"=>$patient_id,
                            "username"=>'PAT000'.$patient_id,
                            "password"=>md5('PASS'.$patient_id),
                            "email"=>'', //$post['patient_email'] 
                            "status"=>'1',
                            "ip_address"=>$_SERVER['REMOTE_ADDR'],
                            "created_by"=>$user_data['id'],
                            "created_date" =>date('Y-m-d H:i:s')
                            ); 
                $this->db->insert('hms_users',$data); 
                $users_id = $this->db->insert_id();    
                /*$this->db->select('*');             
                $this->db->where('users_role','4');
                $query = $this->db->get('hms_permission_to_role');     
                $permission_list = $query->result();
                if(!empty($permission_list))
                {
                  foreach($permission_list as $permission)
                  {
                    $data = array(
                            'users_role' =>4,
                            'users_id' => $users_id,
                            'master_id' => $patient_id,
                            'section_id' => $permission->section_id,
                            'action_id' => $permission->action_id, 
                            'permission_status' => '1',
                            'ip_address' => $_SERVER['REMOTE_ADDR'],
                            'created_by' =>$user_data['id'],
                            'created_date' =>date('Y-m-d H:i:s'),
                         );
                    $this->db->insert('hms_permission_to_users',$data);
                  }
                }*/

                    ////////// Send SMS /////////////////////
                if(in_array('640',$user_data['permission']['action']))
                {
                  if(!empty($post['mobile']))
                  {
                    send_sms('patient_registration',18,$post['name'],$post['mobile'],array('{patient_name}'=>$post['name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));

                  }
                }
                //////////////////////////////////////////

                ////////// SEND EMAIL ///////////////////
                /*if(!empty($post['patient_email']))
                { 
                    $this->load->library('general_library'); 
                    $this->general_library->email($post['patient_email'],'','','','','','patient_registration','18',array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
                }*/
            }
           

            if(isset($post['id_number'])){ $panel_id_no=$post['id_number']; }else{ $panel_id_no=''; }
            if(isset($post['company_name'])){ $panel_name=$post['company_name']; }else{ $panel_name=''; }
            if(isset($post['panel_type'])){ $panel_type=$post['panel_type']; }else{ $panel_type=''; }
            if(isset($post['policy_number'])){ $policy_number=$post['policy_number']; }else{ $policy_number=''; }
            if(isset($post['authorization_amount'])){ $authorization_amount=$post['authorization_amount']; }else{ $authorization_amount=''; }
            if(isset($post['package_id'])){ $package_id=$post['package_id']; }else{ $package_id=''; }
            
            $ipd_no = generate_unique_id(22);
                        $reciept_date = date('Y-m-d h:i:s',strtotime($post['admission_date']));
            $data = array(
                    "patient_id"=>$patient_id,
                    'branch_id'=>$user_data['parent_id'],
                    'ipd_no'=>$ipd_no,
                    'attend_doctor_id'=>$post['attended_doctor'],
                    'mlc'=>$post['mlc'],
                    'mlc_status'=>$post['mlc_status'],
                    'payment_mode'=>$post['payment_mode'],
                    //'bank_name'=>$bank_name,
                    //'card_no'=>$card_no,
                    //'cheque_no'=>$cheque_no,
                    //'cheque_date'=>$cheque_date,
                    'remarks'=>$post['remarks'],
                    'panel_id_no'=>$panel_id_no,
                    'room_type_id'=>$post['room_id'],
                    'room_id'=>$post['room_no_id'],
                    'bad_id'=>$post['bed_no_id'],
                    'attend_doctor_id'=>$post['attended_doctor'],
                    //'referral_doctor'=>$post['referral_doctor'],
                    'referral_doctor'=>$post['referral_doctor'],
                    'referred_by'=>$post['referred_by'],
                    'referral_hospital'=>$post['referral_hospital'],
                    'patient_type'=>$post['patient_type'],
                    'panel_name'=>$panel_name,
                    'panel_type'=>$panel_type,
                    "package_type"=>$post['package'],
                    "package_id"=>$package_id,
                    'remarks'=>$post['remarks'],
                    'panel_polocy_no'=>$policy_number,
                    'admission_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['admission_time'])),
                    'authrization_amount'=>$authorization_amount,
                    'admission_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'advance_payment'=>$post['advance_deposite'],
                    'patient_category'=>$post['patient_category'],
                    'authorize_person'=>$post['authorize_person'],
                    'diagnosis' => $diagnosis,
                    //'transaction_no'=>$transaction_no
            ); 
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',$created_date);
            $this->db->insert('hms_ipd_booking',$data);
            $ipd_booking_id= $this->db->insert_id();


        /* genereate mlc 30/07/2019 number */
            if($post['mlc_status']==1)
            {
                if($post['mlc_status']==1)
                {
                $hospital_mlc_no= check_hospital_mlc_no();
                $data_mlc_data= array('branch_id'=>$user_data['parent_id'],
                                    'section_id'=>3,
                                    'parent_id'=>$ipd_booking_id,
                                    'reciept_prefix'=>$hospital_mlc_no['prefix'],
                                    'reciept_suffix'=>$hospital_mlc_no['suffix'],
                                    'created_by'=>$user_data['id'],
                                    'created_date'=>date('Y-m-d H:i:s')
                                    );
                $this->db->insert('hms_branch_hospital_mlc_no',$data_mlc_data); 
                }
            }
                        
            //Update bed status 
                        
            //Update bed status 

            /*add sales banlk detail*/
                if(!empty($post['field_name']))
                {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                {
                $data_field_value= array(
                'field_value'=>$post['field_name'][$i],
                'field_id'=>$post['field_id'][$i],
                'type'=>9,
                'section_id'=>3,
                'p_mode_id'=>$post['payment_mode'],
                'branch_id'=>$user_data['parent_id'],
                'parent_id'=>$ipd_booking_id,
                'ip_address'=>$_SERVER['REMOTE_ADDR']
                );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',$created_date);
                $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                }
                }

                /*add sales banlk detail*/
            if(!empty($ipd_booking_id))
            {
                $this->db->set('status','1');
                $this->db->set('ipd_id',$ipd_booking_id);
                $this->db->where('id',$post['bed_no_id']);
                $this->db->update('hms_ipd_room_to_bad');
            }
            $count_assigned_doctor= count($post['assigned_doctor_list']);
            for($i=0;$i< $count_assigned_doctor;$i++)
            {
                $assigned_doctor = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_booking_id'=>$ipd_booking_id,
                    'doctor_id'=>$post['assigned_doctor_list'][$i],
                    'created_date'=>$created_date
                );
                $this->db->insert('hms_ipd_assign_doctor',$assigned_doctor);
            }


            //insert particular
            //advance amount paid
            if(!empty($ipd_booking_id) && !empty($post['advance_deposite']) && $post['advance_deposite']!='0.00') 
            {
                $advance_patient_charge = array(
                    "branch_id"=>$user_data['parent_id'],
                    'ipd_id'=>$ipd_booking_id,
                    'patient_id'=>$patient_id,
                    'type'=>2,
                    'quantity'=>1,
                    'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                    'payment_date'=>date('Y-m-d',strtotime($post['admission_date'])),
                    'particular'=>'Advance Payment',
                    'price'=>$post['advance_deposite'],
                    'panel_price'=>$post['advance_deposite'],
                    'net_price'=>$post['advance_deposite'],
                    'status'=>1,
                    'created_date'=>$created_date
                );
                $this->db->insert('hms_ipd_patient_to_charge',$advance_patient_charge);
                $adva_id =$this->db->insert_id();
                
                $comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$post['advance_deposite'],5);
                //print_r($comission_arr);die;
                $doctor_comission = 0;
                $hospital_comission=0;
                $comission_type='';
                $total_comission=0;
                if(!empty($comission_arr))
                {
                    $doctor_comission = $comission_arr['doctor_comission'];
                    $hospital_comission= $comission_arr['hospital_comission'];
                    $comission_type= $comission_arr['comission_type'];
                    $total_comission= $comission_arr['total_comission'];
                }
                //Payment Details
                $payment_data = array(
                                    'parent_id'=>$ipd_booking_id,
                                    'branch_id'=>$user_data['parent_id'],
                                    'section_id'=>'5',  
                                    'type'=>'4',  
                                    'total_amount'=>$post['advance_deposite'],
                                    'net_amount'=>$post['advance_deposite'],
                                    'paid_amount'=>$post['advance_deposite'],
                                    'hospital_id'=>$post['referral_hospital'],
                                    'doctor_id'=>$post['referral_doctor'],
                                    'patient_id'=>$patient_id,
                                    'credit'=>'',//$post['advance_deposite'],
                                    'debit'=>$post['advance_deposite'],//'',
                                    'pay_mode'=>$post['payment_mode'],
                                    'advance_payment_id'=>$adva_id,
                                    'doctor_comission'=>$doctor_comission,
    								'hospital_comission'=>$hospital_comission,
    								'comission_type'=>$comission_type,
    								'total_comission'=>$total_comission,
                                    //'bank_name'=>$bank_name,
                                    //'card_no'=>$card_no,
                                    //'cheque_no'=>$cheque_no,
                                    //'cheque_date'=>$cheque_date,
                                    //'transection_no'=>$transaction_no,
                                    'created_date'=>$created_date,
                                    'created_by'=>$user_data['id']
                                 );
                $this->db->insert('hms_payment',$payment_data);
                $payment_id= $this->db->insert_id();


                /* genereate receipt number */
            if(in_array('218',$user_data['permission']['section']))
            {
                if($post['advance_deposite']>0)
                {
                $hospital_receipt_no= check_hospital_receipt_no();
                $data_receipt_data= array('branch_id'=>$user_data['parent_id'],
                                    'section_id'=>3,
                                    'payment_id'=>$payment_id,
                                    'parent_id'=>$ipd_booking_id,
                                    'reciept_prefix'=>$hospital_receipt_no['prefix'],
                                    'reciept_suffix'=>$hospital_receipt_no['suffix'],
                                    'created_by'=>$user_data['id'],
                                    'created_date'=>$created_date
                                    );
                $this->db->insert('hms_branch_hospital_no',$data_receipt_data); 
                }
            }


                       /*add sales banlk detail*/
                if(!empty($post['field_name']))
                {
                    $post_field_value_name= $post['field_name'];
                    $counter_name= count($post_field_value_name); 
                    for($i=0;$i<$counter_name;$i++) 
                        {
                            $data_field_value= array(
                            'field_value'=>$post['field_name'][$i],
                            'field_id'=>$post['field_id'][$i],
                            'type'=>9,
                            'section_id'=>4,
                            'p_mode_id'=>$post['payment_mode'],
                            'branch_id'=>$user_data['parent_id'],
                            'parent_id'=>$ipd_booking_id,
                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                            );
                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',$created_date);
                    $this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

                    }
                }
            }
            //registration charge
            if(!empty($ipd_booking_id) && !empty($post['reg_charge']) && $post['reg_charge']!='0.00') 
            {
            $registration_patient_charge = array(
                "branch_id"=>$user_data['parent_id'],
                'ipd_id'=>$ipd_booking_id,
                'patient_id'=>$patient_id,
                'type'=>1,
                'quantity'=>1,
                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                'particular'=>'Registration Charge',
                'price'=>$post['reg_charge'],
                'panel_price'=>$post['reg_charge'],
                'net_price'=>$post['reg_charge'],
                'status'=>1,
                'created_date'=>$created_date
            );
            $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
            }

            if(!empty($ipd_booking_id) && empty($post['package_id']) && $post['patient_type']=='1') 
            {
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                     $charges='';
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],'',$post['patient_type']);
                         $charges_te= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]; 
                         $charges='';
                         if(!empty($charges_te))
                         {
                            $charges = $charges_te['charge'];   
                         }
                         
                         
                         /* For Particular Code */

                       $detail= $this->general_model->get_charge_type_detail($room_charge_type_list[$i]['id'],$post['room_id']);
                      // print_r($detail);
                        $group_name='';
                        $group_code='';
                        if(!empty($detail))
                        {
                           if(substr($detail->charge_code,0,2)==10)
                           {
                            $group_name='Room & Nursing Charges';
                            $group_code=100000;
                           }     
                        }
                       

                         /* For Particular Code */

                        $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>3,
                                'room_id'=>$post['room_id'],
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'group_name'=>$group_name,
                                'group_code'=>$group_code,
                                'particular_code'=>$detail->charge_code,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            }
            elseif(!empty($ipd_booking_id) && empty($post['package_id']) && $post['patient_type']=='2') 
            {
                    //panel charges
                    $this->load->model('general/general_model'); 
                    $room_type_list = $this->general_model->room_type_list(); 
                    $room_charge_type_list = $this->general_model->room_charge_type_list();
                    
                    if(!empty($room_charge_type_list))
                    {
                    $room_charge_type_list_count = count($room_charge_type_list);
                    for($i=0;$i<$room_charge_type_list_count;$i++)
                    {
                        $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                        $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id'],$post['company_name'],$post['patient_type']);
                        $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];
                        //echo "<pre>";print_r($form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]); exit;
                        
                        $detail= $this->general_model->get_charge_type_detail($room_charge_type_list[$i]['id'],$post['room_id']);
                      // print_r($detail);
                        $group_name='';
                        $group_code='';
                        if(!empty($detail))
                        {
                           if(substr($detail->charge_code,0,2)==10)
                           {
                            $group_name='Room & Nursing Charges';
                            $group_code=100000;
                           }     
                        }
                        
                        
                        $registration_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>3,
                                'room_id'=>$post['room_id'],
                                'group_name'=>$group_name,
                                'group_code'=>$group_code,
                                'particular_code'=>$detail->charge_code,
                                'quantity'=>1,
                                'transfer_status'=>1,
                                'particular'=>$charge_type,
                                'price'=>$charges,
                                'panel_price'=>$charges,
                                'net_price'=>$charges,
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                        
                    } 
                   }
            }
            elseif(!empty($ipd_booking_id) && !empty($post['package_id']) && $post['patient_type']=='1')
            {
                    //for package without panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list('',$post['package_id'],1);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }
            elseif(!empty($ipd_booking_id) && !empty($post['package_id']) && $post['patient_type']=='2')
            {
                    //for package with panel
                    $this->load->model('ipd_panel_package_charge/ipd_panel_package_charge_model','package_panel_charge');
                    $panel_list = $this->package_panel_charge->get_panel_list();
                    $package_list = $this->package_panel_charge->get_package_list($post['company_name'],$post['package_id'],2);
                    if(!empty($package_list[0]))
                    {
                        $package_patient_charge = array(
                                "branch_id"=>$user_data['parent_id'],
                                'ipd_id'=>$ipd_booking_id,
                                'patient_id'=>$patient_id,
                                'start_date'=>date('Y-m-d',strtotime($post['admission_date'])).' '.date('H:i:s',strtotime($post['admission_time'])),
                                'type'=>4,
                                'transfer_status'=>1,
                                'quantity'=>1,
                                'particular'=>$package_list[0]['name'],
                                'price'=>$package_list[0]['package_charge'],
                                'panel_price'=>$package_list[0]['package_charge'],
                                'net_price'=>$package_list[0]['package_charge'],
                                'status'=>1,
                                'created_date'=>$created_date
                            );
                        $this->db->insert('hms_ipd_patient_to_charge',$package_patient_charge);
                        
                   
                   }
            }

            
            
         /*add sales banlk detail*/ 


        }

        //echo "<pre>"; print_r($post['assigned_doctor_list']); exit;
        $users_data = $this->session->userdata('auth_users');
        if(!empty($post['assigned_doctor_list']) && in_array('121',$users_data['permission']['section']))
        {
            $this->send_email_sms_to_assign_doctor($post['assigned_doctor_list'],$ipd_booking_id);  
        }
        
        return $ipd_booking_id; 
    }

    public function send_email_sms_to_assign_doctor($assigned_doctor_list,$booking_id)
    {
        /* Start Send Sms/Email */
        $users_data = $this->session->userdata('auth_users');
        $count_assigned_doctor= count($assigned_doctor_list);
        for($i=0;$i< $count_assigned_doctor;$i++)
        {
          if(!empty($booking_id))
          {
             $this->load->model('doctors/doctors_model','doctors');
             $doctor_data = $this->doctors->get_by_id($assigned_doctor_list[$i]);
             
             $doctor_name = $doctor_data['doctor_name'];
             $doctor_mobile = $doctor_data['mobile_no'];
             $doctor_email = $doctor_data['email'];   
             
             $get_by_id_data = $this->get_ipd_detail_by_id($booking_id);
             //booking_date
             //echo "<pre>"; print_r($get_by_id_data); exit;
             $booking_date='';
             if(!empty($get_by_id_data['admission_date']))
             {  
                $booking_date = date('d-m-Y',strtotime($get_by_id_data['admission_date'])); // ;
             }
             
             $room_no = $get_by_id_data['room_no'];
             $bed_no = $get_by_id_data['bad_no'];
             $room_type = $get_by_id_data['room_category'];
             $patient_name = $get_by_id_data['patient_name'];
             $patient_code = $get_by_id_data['patient_code'];
             $ipd_no = $get_by_id_data['ipd_no'];
             $mobile_no = $get_by_id_data['mobile_no'];
             $patient_email = $get_by_id_data['patient_email'];
                //check permission
                if(in_array('640',$users_data['permission']['action']))
                {
                    if(!empty($doctor_mobile))
                    {
                      send_sms('ipd_assigned_doctor',26,$doctor_name,$doctor_mobile,array('{ipd_no}'=>$ipd_no,'{booking_date}'=>$booking_date,'{doctor_name}'=>$doctor_name,'{patient_name}'=>$patient_name,'{patient_code}'=>$patient_code,'{room_type}'=>$room_type,'{bed_no}'=>$bed_no,'{room_no}'=>$room_no));  
                    }

                }

                if(in_array('641',$users_data['permission']['action']))
                {
                  if(!empty($doctor_email))
                  {
                    $this->load->library('general_functions');
                    $this->general_functions->email($doctor_email,'','','','','1','ipd_assigned_doctor','26',array('{ipd_no}'=>$ipd_no,'{booking_date}'=>$booking_date,'{doctor_name}'=>$doctor_name,'{patient_name}'=>$patient_name,'{patient_code}'=>$patient_code,'{room_type}'=>$room_type,'{bed_no}'=>$bed_no,'{room_no}'=>$room_no));
                     
                  }
                } 
          }

        }  

        /* End Send Sms/Email  */
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
            $this->db->update('hms_ipd_booking');
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
            $this->db->update('hms_ipd_booking');
        } 
    }

    public function purchase_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_medicine_purchase');
        $result = $query->result(); 
        return $result; 
    } 

   
    function template_format($data="",$branch_id=''){
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_print_branch_template.*');
        $this->db->where($data);
        if($users_data['users_role']==3 && !empty($branch_id))
        {
            $this->db->where('branch_id  IN ('.$branch_id.')');
        }
        else
        {
            $this->db->where('branch_id  IN ('.$users_data['parent_id'].')');   
        }
        
        $this->db->from('hms_print_branch_template');
        $query=$this->db->get()->row();
        //echo $this->db->last_query();die;
        //print_r($query);exit;
        return $query;

    }

    public function get_patient_by_id($id)
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient.*,hms_patient.modified_date  as patient_modified_date');
        $this->db->from('hms_patient'); 
        $this->db->where('branch_id  IN ('.$users_data['parent_id'].')');
        $this->db->where('hms_patient.id',$id);
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function attended_doctor_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        // print '<pre>'; print_r($result);
        return $result; 
    }

    public function referal_doctor_list($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (0,2)'); 
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

    public function assigned_doctor_list()
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('*');   
        $this->db->order_by('hms_doctors.doctor_name','ASC');
        $this->db->where('hms_doctors.is_deleted',0); 
        $this->db->where('hms_doctors.doctor_type IN (1,2)'); 
        $this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
        $query = $this->db->get('hms_doctors');
        $result = $query->result(); 
        // print '<pre>'; print_r($result);
        return $result; 
    }

    public function aasigned_doctor_by_id($id=''){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        if(!empty($id)){
        $this->db->where('ipd_booking_id',$id);
        }
        $this->db->order_by('id','ASC');
        $this->db->group_by('doctor_id');
        $this->db->where('branch_id',$users_data['parent_id']); 
        $query = $this->db->get('hms_ipd_assign_doctor');
        $result = $query->result(); 
        // echo $this->db->last_query();die;
        return $result; 
    }

    public function re_admit_patient($ipd_id,$patient_id)
    {
        $users_data = $this->session->userdata('auth_users');
        $update_booking_date= array('discharge_status'=>0);
        $this->db->where(array('id'=>$ipd_id,'patient_id'=>$patient_id));
        $this->db->update('hms_ipd_booking',$update_booking_date);
        if(!empty($ipd_id) && !empty($patient_id))
        {
            $insert_re_admit = array('branch_id'=>$users_data['parent_id'],'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'admit_status'=>0,'created_date'=>date('Y-m-d H:i:s'),'created_by'=>$users_data['id']);
            $this->db->insert('hms_ipd_patient_readmit',$insert_re_admit);
            return 1;
        }   
    }

    public function update_discharge_data($ipd_id,$patient_id,$discharge_date='')
    {
       $users_data = $this->session->userdata('auth_users');
       $type_value= get_ipd_discharge_time_setting_value();
       $data['CHARGES']=array();
       $this->db->select('*');
       $this->db->from('hms_ipd_patient_to_charge');
       $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
       $query= $this->db->get()->result();

        foreach($query as $data)
        {
              
            if($data->end_date=='0000-00-00 00:00:00')
            {
                
                 //$update_data=  array('end_date'=>date('Y-m-d H:i:s'));
                 $update_data=  array('end_date'=>date('Y-m-d H:i:s', strtotime($discharge_date)));
                 $this->db->where('id',$data->id);
                 $this->db->update('hms_ipd_patient_to_charge',$update_data);
            }
        }
        $update_booking_date= array('discharge_status'=>1);
        $this->db->where(array('id'=>$ipd_id,'patient_id'=>$patient_id));
        $this->db->update('hms_ipd_booking',$update_booking_date);
        /* get discharge table data  */


        /* update room to bed */
            $this->db->select('*');
            $this->db->from('hms_ipd_room_to_bad');
            $this->db->where('ipd_id',$ipd_id);
            $query = $this->db->get(); 
            $result = $query->row_array();

            $bed_id = $result['id'];
            $this->db->set('status','0');
            $this->db->set('ipd_id','0');
            $this->db->where('id',$result['id']);
            $this->db->update('hms_ipd_room_to_bad');

            /* update room to bed */

        $this->db->select('*');
        $this->db->from('hms_ipd_patient_to_charge');
        $this->db->where('hms_ipd_patient_to_charge.type',3);
        $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
        $res= $this->db->get()->result();
        //echo "<pre>";print_r($res);die;
        $res = json_decode(json_encode($res), true);
        
        $new_array=array();
        foreach($res as $data_query)
        {
            //echo "<pre>";print_r($data_query);die;
               

                if(isset($type_value) && $type_value!='' && $type_value==1)
                {
                    ## hours ##
                    $date1 = new DateTime(date('Y-m-d H:i:s',strtotime($data_query['start_date'])));
                    $date2 = new DateTime(date('Y-m-d H:i:s',strtotime($data_query['end_date'])));
                    $date2->modify("+1 days");
                    $interval = $date1->diff($date2);
                    $days= $interval->days;

                      $time_duration= date('H:i:s',strtotime($data_query['start_date']));
                        $current_time= date('H:i:s');
                        if($interval->h<=23 && $interval->h >0 && ($current_time < $time_duration))
                        {
                           
                            $i=1;
                            while($i <= $days) 
                            {
                                    if(!empty($data_query['end_date']) && $data_query['end_date']!='')
                                    {
                                         //ECHO $data_query['end_date'];
                                        $data_query['start_date'] = date('Y-m-d H:i:s', strtotime($data_query['end_date'])-($i*86400)); 

                                        $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$data_query['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        );

                                        //print '<pre>'; print_r($insert_charge_entry);
                                        $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);

                                       
                                    }
                                    else
                                    {
                                        $data_query['start_date'] = date('Y-m-d H:i:s'); 
                                        $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$data_query['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        );

                                        //print '<pre>'; print_r($insert_charge_entry);
                                        $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                    }
                                    // $data['CHARGES'][]=$data_query;
                                    // PRINT_R($data_query);
                                    $i++;
                            }
                        }
                        else
                        {
                            
                            //echo $days;
                            for($i=0;$i<$days;$i++)
                            { 
                                if(!empty($data_query['end_date']) && $data_query['end_date']!='')
                                {
                                    
                                    $data_query['start_date'] = date('Y-m-d H:i:s', strtotime($data_query['end_date'])-($i*86400));
                                    $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                    'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$data_query['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                    'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                    );

                                    //print '<pre>'; print_r($insert_charge_entry);
                                    $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                }
                                else
                                {

                                $data_query['start_date'] = date('Y-m-d H:i:s'); 
                                $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$data_query['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                );

                                //print '<pre>'; print_r($insert_charge_entry);
                                $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                }
                               
                            } 
                            //echo '<pre>'; print_r($data['CHARGES']);die;

                        }

                         

                    ## hours ## 

                }
                else
                {
                    
                $date1 = new DateTime(date('Y-m-d',strtotime($data_query['start_date'])));
                $date2 = new DateTime(date('Y-m-d',strtotime($data_query['end_date'])));
                $date2->modify("+1 days");
                $interval = $date1->diff($date2);
                $days= $interval->days;
                for($i=0;$i<$days;$i++)
                { //print
                   $data_query['start_date'] = date('Y-m-d H:i:s', strtotime($data_query['end_date'])-($i*86400)); 

                     $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'room_id'=>$data_query['room_id'],'particular_code'=>$data_query['particular_code'],'group_code'=>$data_query['group_code'],'group_name'=>$data_query['group_name'],'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                        );

                     //print '<pre>'; print_r($insert_charge_entry);
                    $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                   
                }

                }

                $this->db->where('id',$data_query['id']);
                $this->db->delete('hms_ipd_patient_to_charge');
                //echo $this->db->last_query();die;
                

        }


        
        return 1;exit;
        
    }


    public function save_readmit()
    {
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post();  
        

        if(!empty($post['ipd_id']) && !empty($post['patient_id']))
        {
            $this->db->select('hms_payment.*');
            $this->db->from('hms_payment'); 
            $this->db->where('hms_payment.branch_id',$user_data['parent_id']);
            $this->db->where('hms_payment.patient_id',$post['patient_id']);
            $this->db->where('hms_payment.parent_id',$post['ipd_id']);
            $this->db->where('hms_payment.section_id',5);
            $this->db->where('hms_payment.type',5);
            
            $query = $this->db->get(); 
            $result = $query->row_array();
            $balance =  $result['balance'];
            if($balance > 0)
            {
                //echo "<pre>";print_r($result); exit;
                $registration_patient_charge = array(
                            "branch_id"=>$user_data['parent_id'],
                            'ipd_id'=>$post['ipd_id'],
                            'patient_id'=>$post['patient_id'],
                            'start_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'end_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'type'=>9,
                            'quantity'=>1,
                            'particular'=>'Discharge Payment',
                            'price'=>$balance,
                            'panel_price'=>$balance,
                            'net_price'=>$balance,
                            'status'=>1,
                            'created_date'=>date('Y-m-d H:i:s')
                        );

                    $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
            }
            else
            {
                //echo "<pre>";print_r($result); exit;
                $registration_patient_charge = array(
                            "branch_id"=>$user_data['parent_id'],
                            'ipd_id'=>$post['ipd_id'],
                            'patient_id'=>$post['patient_id'],
                            'start_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'end_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                            'type'=>9,
                            'quantity'=>1,
                            'particular'=>'Discharge Refund',
                            'price'=>$balance,
                            'panel_price'=>$balance,
                            'net_price'=>$balance,
                            'status'=>1,
                            'created_date'=>date('Y-m-d H:i:s')
                        );

                    $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);    
            }

            
                $this->db->where(array('branch_id'=>$user_data['parent_id'],'patient_id'=>$post['patient_id'],'parent_id'=>$post['ipd_id'],'section_id'=>5));
                $this->db->where('type!=',4);
                $this->db->delete('hms_payment');
                
            
            
        }
        //payment
        $this->load->model('general/general_model'); 
        $room_type_list = $this->general_model->room_type_list(); 
        $room_charge_type_list = $this->general_model->room_charge_type_list();
        if(!empty($room_charge_type_list))
        {
            $room_charge_type_list_count = count($room_charge_type_list);
            for($i=0;$i<$room_charge_type_list_count;$i++)
            {
                $charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
                $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id']);
                $charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];

                $registration_patient_charge = array(
                        "branch_id"=>$user_data['parent_id'],
                        'ipd_id'=>$post['ipd_id'],
                        'patient_id'=>$post['patient_id'],
                        'start_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
                        'type'=>3,
                        'room_id'=>$post['room_id'],
                        'quantity'=>1,
                        'transfer_status'=>1,
                        'particular'=>$charge_type,
                        'price'=>$charges,
                        'panel_price'=>$charges,
                        'net_price'=>$charges,
                        'status'=>1,
                        'created_date'=>date('Y-m-d H:i:s')
                    );

                $this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
                
            } 


       }

        $update_booked_data= array('room_type_id'=>$post['room_id'],'room_id'=>$post['room_no_id'],'bad_id'=>$post['bed_no_id']);
        $this->db->where(array('id'=>$post['ipd_id'],'patient_id'=>$post['patient_id']));
        $this->db->update('hms_ipd_booking',$update_booked_data);
        $this->db->select('*');
        $this->db->from('hms_ipd_room_to_bad');
        $this->db->where('ipd_id',$post['ipd_id']);
        $query = $this->db->get(); 
        $result = $query->row_array();

        $bed_id = $result['id'];
        $this->db->set('status','1');
        $this->db->set('ipd_id',$post['ipd_id']);
        $this->db->where('id',$result['id']);
        $this->db->update('hms_ipd_room_to_bad');


        

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
        $this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

        $this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
        $this->db->where('hms_payment_mode_field_value_acc_section.type',9);
        $this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
        $this->db->where('hms_payment_mode_field_value_acc_section.section_id',3);
        $query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();

        return $query;
    }

    // function to get attented doctors name for IPD booking added on 09-02-2018
    public function get_ipd_assigned_doctors_name($ipd_bkng_id)
    {
        $this->db->select('group_concat(doctor_id) as assigned_doc_ids');
        $this->db->from('hms_ipd_assign_doctor');
        $this->db->where('ipd_booking_id', $ipd_bkng_id);
        $res=$this->db->get();
        if($res->num_rows() > 0)
        {
            $result = $res->result();
            $assigned_doc_ids=$result[0]->assigned_doc_ids;
            $this->db->select('group_concat(doctor_name) as assigned_doctor');
            $this->db->from('hms_doctors');
            $this->db->where('id in ('.$assigned_doc_ids.')');
            $res1=$this->db->get();
            if($res->num_rows() > 0)
            // if(count($res) > 0)
            {
                return $res1->result();
            }
            else
            {
                return "empty";
            }
        }
        else
        {
            return "empty";
        }
    }
    // function to get attented doctors name for IPD booking added on 09-02-2018

    
    public function get_room_no()
    {
        $this->db->select('*'); 
        $users_data = $this->session->userdata('auth_users');
        //$this->db->where('status','1'); 
        $this->db->where('is_deleted!=2');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->order_by('id','ASC'); 
        $query = $this->db->get('hms_ipd_rooms');
        //echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    public function get_bed_no()
    {
      
        $this->db->select('hms_ipd_room_to_bad.*,hms_ipd_booking.is_deleted as ipd_is_deleted'); 
        
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id = hms_ipd_room_to_bad.ipd_id','left');
       
        // / AND hms_ipd_booking.is_deleted!=2
        $this->db->order_by('hms_ipd_room_to_bad.id','ASC'); 
        $query = $this->db->get('hms_ipd_room_to_bad');
        
       // echo $this->db->last_query();die;
        $result = $query->result(); 
        return $result;
    }
    
    
    //ipd prescription save

    public function save_prescription()
    {
        //echo "<pre>";print_r($_POST); exit;
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $prv_history="";
        if(!empty($post['prv_history']))
        {
            $prv_history = $post['prv_history'];
        }
        $personal_history="";
        if(!empty($post['personal_history']))
        {
            $personal_history = $post['personal_history'];
        }

        $data_patient = array(
                                "patient_name"=>$post['patient_name'],
                                "mobile_no"=>$post['mobile_no'],
                                "gender"=>$post['gender'], 
                                "age_y"=>$post['age_y'],
                                "age_m"=>$post['age_m'],
                                "age_d"=>$post['age_d'],
                                'adhar_no'=>$post['aadhaar_no'],
                                'relation_type'=>$post['relation_type'],
                                'relation_name'=>$post['relation_name'],
                                'relation_simulation_id'=>$post['relation_simulation_id'],
                            );

        if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
            $patient_id = $post['patient_id'];
            $this->db->where('id',$post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
        } 
        
        
        $data = array( 
                    "branch_id"=> $user_data['parent_id'],      
                    "ipd_no"=>$post['ipd_no'],
                    "patient_code"=>$post['patient_code'],
                    "patient_id"=>$post['patient_id'],
                    "booking_id"=>$post['booking_id'],
                    "patient_name"=>$post['patient_name'],
                    "mobile_no"=>$post['mobile_no'],
                    "gender"=>$post['gender'], 
                    "age_y"=>$post['age_y'],
                    "age_m"=>$post['age_m'],
                    "age_d"=>$post['age_d'],
                    "prv_history"=>$prv_history,
                    "personal_history"=>$personal_history,
                    "chief_complaints"=>$post['chief_complaints'],
                    "examination"=>$post['examination'],
                    "diagnosis"=>$post['diagnosis'],
                    "suggestion"=>$post['suggestion'],
                    "remark"=>$post['remark'],
                    "attended_doctor"=>$post['attended_doctor'],
                  
                    "status"=>1
                    ); 
            
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_ipd_patient_prescription',$data); 
            //echo $this->db->last_query(); exit;
            $data_id = $this->db->insert_id(); 
            /* update doctor status in opd */
            /*$data_update= array('doctor_checked_status'=>1);
            $this->db->where('id',$post['booking_id']);
            $this->db->update('hms_opd_booking',$data_update);*/
            /* update doctor status in opd */


            
            $total_test = count(array_filter($post['test_name']));
            for($i=0;$i<$total_test;$i++)
            {
                
                    //check and add masters of test 
                    if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
                    {   
                        $this->db->select('path_test.*');  
                        $this->db->where('path_test.test_name',$post['test_name'][$i]);  
                        $this->db->where('path_test.is_deleted!=2'); 
                        $this->db->where('path_test.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('path_test');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $path_test_data = $query->result_array();
                            if(!empty($path_test_data))
                            {
                                $test_id = $path_test_data[0]['id'];
                            }
                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['test_name'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('path_test',$data);
                                $test_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    else
                    {
                        $test_id = $post['test_id'][$i];
                    }

                    

                $test_data = array(
                            "prescription_id"=>$data_id,
                            "test_name"=>$post['test_name'][$i],
                            "test_id"=>$test_id);
                        $this->db->insert('hms_ipd_prescription_patient_test',$test_data); 
                        $test_data_id = $this->db->insert_id(); 
            }

            $total_prescription = count(array_filter($post['medicine_name'])); 
            for($i=0;$i<$total_prescription;$i++)
            {   
                    //echo $post['medicine_name'][$i];
                    //check and add masters 
                    if(!empty($post['medicine_name'][$i]))
                    {   
                        $this->db->select('hms_medicine_entry.*');  
                        //$this->db->from('hms_opd_medicine');
                        $this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
                        $this->db->where('hms_medicine_entry.is_deleted=0'); 
                        $this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_medicine_entry');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $company_data = $query->result_array();
                            if(!empty($company_data))
                            {
                                $medicine_id = $company_data[0]['id'];
                            }
                        }
                        else
                        {       
                                    $unit_id ='';
                                //check medicine type
                                if(!empty($post['medicine_type'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_unit.*');  
                                    $this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
                                    $this->db->where('hms_medicine_unit.is_deleted=0'); 
                                    $this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_unit');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $unit_data = $query->result_array();
                                        if(!empty($unit_data))
                                        {
                                            $unit_id = $unit_data[0]['id'];
                                        }   
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_unit',$data);
                                        $unit_id = $this->db->insert_id(); 
                                    }
                                }

                                ////check medicine type end

                                //check medicine company

                                if(!empty($post['brand'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_company.*');  
                                    $this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
                                    $this->db->where('hms_medicine_company.is_deleted=0'); 
                                    $this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_company');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $company_data = $query->result_array();
                                        if(!empty($company_data))
                                        {
                                            $company_id = $company_data[0]['id'];
                                        }
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'company_name'=>$post['brand'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_company',$data);
                                        $company_id = $this->db->insert_id(); 
                                    }
                                }


                                //medicine company end
                                
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_name'=>$post['medicine_name'][$i],
                                                'type'=>$unit_id,
                                                'salt'=>$post['salt'][$i],
                                                'manuf_company'=>$company_id,
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_medicine_entry',$data);
                                $medicine_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    //medicne type
                    if(!empty($post['medicine_type'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_type.*');
                        //$this->db->from('hms_opd_medicine_type');
                        $this->db->where('hms_opd_medicine_type.medicine_type',$post['medicine_type'][$i]); 
                        $this->db->where('hms_opd_medicine_type.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_type.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_type');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_type'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_type',$data);
                        }
                    }
                    //medicine dose
                    if(!empty($post['medicine_dose'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage.*');
                        //$this->db->from('hms_opd_medicine_dosage');
                        $this->db->where('hms_opd_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_dosage',$data);
                        }
                    }

                    //medicine duration
                    if(!empty($post['medicine_duration'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage_duration.*'); 
                        //$this->db->from('hms_opd_medicine_dosage_duration');
                        $this->db->where('hms_opd_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage_duration.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage_duration');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_opd_medicine_dosage_duration',$data);
                        }
                    }

                    //medicine frequency
                    if(!empty($post['medicine_frequency'][$i]))
                    {
                        $this->db->select('hms_opd_medicine_dosage_frequency.*');
                        //$this->db->from('hms_opd_medicine_dosage_frequency');
                        $this->db->where('hms_opd_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
                        $this->db->where('hms_opd_medicine_dosage_frequency.is_deleted!=2'); 
                        $this->db->where('hms_opd_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_medicine_dosage_frequency');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_opd_medicine_dosage_frequency',$data);
                        }
                    }

                    //medicine advice
                    if(!empty($post['medicine_advice'][$i]))
                    {
                        $this->db->select('*');
                        //$this->db->from('hms_opd_advice');
                        $this->db->where('hms_opd_advice.medicine_advice',$post['medicine_advice'][$i]); 
                        $this->db->where('hms_opd_advice.is_deleted!=2'); 
                        $this->db->where('hms_opd_advice.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_opd_advice');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_advice'=>$post['medicine_advice'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_opd_advice',$data);
                        }
                    }
                    //medicne id 
                    //echo $data_id; exit;
                    if(!empty($post['medicine_id'][$i]))
                    {
                        $medicine_id = $post['medicine_id'][$i];
                    }
                    else
                    {
                        $medicine_id = $medicine_id;
                    }
                    $prescription_data = array(
                        "prescription_id"=>$data_id,
                        'medicine_id'=>$medicine_id,
                        "medicine_name"=>$post['medicine_name'][$i],
                        "medicine_salt"=>$post['salt'][$i],
                        "medicine_brand"=>$post['brand'][$i],
                        "medicine_type"=>$post['medicine_type'][$i],
                        "medicine_dose"=>$post['medicine_dose'][$i],
                        "medicine_duration"=>$post['medicine_duration'][$i],
                        "medicine_frequency"=>$post['medicine_frequency'][$i],
                        "medicine_advice"=>$post['medicine_advice'][$i]
                        );
                    $this->db->insert('hms_ipd_prescription_patient_pres',$prescription_data);
                    //echo $this->db->last_query(); exit;
                    $test_data_id = $this->db->insert_id(); 
                
            }
            
                $current_date = date('Y-m-d H:i:s');
                foreach($post['data'] as $key=>$val)
                {
                    
                    $data = array(
                                   "branch_id"=>$user_data['parent_id'],
                                   "type"=>6,
                                   "booking_id"=>$data_id,
                                   "vitals_id"=>$key,
                                   "vitals_value"=>$val['name'],
                                   
                                  );
                  
                  $this->db->insert('hms_branch_vitals',$data);
                  $id = $this->db->insert_id();
                } 
            

            return $data_id;
    
    }

    public function save_medication_chart()
    {
        // echo "<pre>";print_r($_POST); exit;
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
       
        $this->load->model('ipd_booking/ipd_booking_model');
        $this->load->model('patient/patient_model');
        $ipd_booking_data = $this->ipd_booking_model->get_by_id("",$post['ipd_no']);
		$data = array(
			"ipd_no" => $ipd_booking_data['ipd_no'],
			"booking_id" => $ipd_booking_data['id'],
			'ipd_id' => $ipd_booking_data['id'],
			'branch_id' => $user_data['parent_id'],
			'patient_id' => $ipd_booking_data['patient_id'],
			'created_date' => date('Y-m-d H:i:s'),
			'created_by' => $user_data['id'],
            // 'diagnosis_id' => $post['diagnosis_id']
		); 
        if(!empty($post['diagnosis_id'])) {
			$diagnosis_ids = $post['diagnosis_id'];// Array of diagnosis IDs
			$this->db->where_in('id', $diagnosis_ids);
			$result = $this->db->get('hms_opd_diagnosis')->result_array();
			$d = [];
			foreach($result as $dig) {
				$d[] = $dig['diagnosis'];
			}
			$data['diagnosis_id'] = implode('/', $d);
		}

		$this->db->insert('hms_ipd_medication_chart_table',$data);
		$insert_id = $this->db->insert_id();
            if($post['medicine_name'][0]!="")
            {

                
                //echo $this->db->last_query(); exit;
            
                $post_medicine_name= $post['medicine_name'];

                $counter_name= count($post_medicine_name); 
            
                for($i=0;$i<$counter_name;$i++) 
                {
                    if(!empty($post['medicine_name'][$i])){
                        $data_medicine= array(
                        'medicine_name'=>$post['medicine_name'][$i],
                        'medicine_dose'=>$post['medicine_dose'][$i],
                        'medicine_duration'=>$post['medicine_duration'][$i],
                        'medicine_frequency'=>$post['medicine_frequency'][$i],
                        'ipd_id'=>$post['booking_id'],
                        'patient_id'=>$post['patient_id'],
                        'ipd_no' => $post['ipd_no'],
                        'booking_id' => $post['booking_id'],
                        'date' => $post['date'][$i],
                        'medication_chart_table_id' => $insert_id,
                        );
                        $this->db->insert('hms_ipd_medication_chart',$data_medicine);
                    }
                }
            }

          
    
    }
    //end ipd prescription
    
     public function save_new_born()
    {
        $post = $this->input->post();
        $user_data = $this->session->userdata('auth_users');
        echo "save born data::<pre>";print_r($post); die;

                $this->db->select('hms_born_summery.*');
                $this->db->from('hms_born_summery'); 
                $this->db->where('hms_born_summery.branch_id',$user_data['parent_id']);
                $this->db->where('hms_born_summery.ipd_id',$post['ipd_id']);
                $query = $this->db->get();
                $death_result = $query->row_array();
                $id = $death_result['id'];
                if(!empty($id))
                {

                    $death_data_update = array(
                                    'branch_id'=>$user_data['parent_id'],
                                    'patient_id'=>$post['patient_id'],
                                    'ipd_id'=>$post['ipd_id'],
                                    'weight'=>$post['weight'],
                                    'gender'=>$post['gender'],
                                    'baby_of'=>$post['baby_of'],
                                    'born_date'=>date('Y-m-d',strtotime($post['born_date'])),
                                    'born_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['born_time'])),
                                    'type_of_delivery' => $post['type_of_delivery'],
                                    'caste' => $post['caste'],
                                    'religion' => $post['religion'],
                                    'para' => $post['para'],
                                    'remarks' => $post['remarks']
                            );

                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->where('id',$id); 
                    $this->db->update('hms_born_summery',$death_data_update);
                    $born_id = $id;
                }   
                else
                {

                    $death_data = array('branch_id'=>$user_data['parent_id'],
                                    'patient_id'=>$post['patient_id'],
                                    'ipd_id'=>$post['ipd_id'],
                                    'weight'=>$post['weight'],
                                    'gender'=>$post['gender'],
                                    'baby_of'=>$post['baby_of'],
                                    'born_date'=>date('Y-m-d',strtotime($post['born_date'])),
                                    'born_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['born_time'])),
                                    'type_of_delivery' => $post['type_of_delivery'],
                                    'caste' => $post['caste'],
                                    'religion' => $post['religion'],
                                    'para' => $post['para'],
                                    'remarks' => $post['remarks']
                            );

                    $this->db->set('created_by',$user_data['id']);
                    $this->db->set('created_date',date('Y-m-d H:i:s'));
                    $this->db->insert('hms_born_summery',$death_data);
                    $born_id= $this->db->insert_id(); 
                   // echo $this->db->last_query(); exit;
                }

                return $born_id;
    }
    
    
    //crm
    
    public function crm_get_by_id($id='')
    {
      $this->db->select("crm_leads.*, crm_department.department, crm_lead_type.lead_type, crm_source.source, crm_users.name as uname");
      $this->db->join("crm_department","crm_department.id=crm_leads.department_id","left");
      $this->db->join("crm_lead_type","crm_lead_type.id=crm_leads.lead_type_id","left");
      $this->db->join("crm_source","crm_source.id=crm_leads.lead_source_id","left"); 
      $this->db->join("crm_users","crm_users.id=crm_leads.created_by","left"); 
      $this->db->where('crm_leads.id', $id);
      $query = $this->db->get('crm_leads');
      //echo $this->db->last_query(); 
      return $query->row_array();
    }
    
    public function get_ipd_by_id($id)
    {
        $this->db->select('hms_ipd_booking.*,hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d,hms_patient.adhar_no,hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code, hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_ipd_rooms.room_no,hms_patient.relation_name,hms_patient.relation_type,hms_patient.relation_simulation_id,hms_patient.mobile_no,hms_payment.debit as net_amount');
        $this->db->from('hms_ipd_booking'); 
        $this->db->join('hms_patient','hms_patient.id = hms_ipd_booking.patient_id'); 
        $this->db->join('hms_payment','hms_payment.parent_id = hms_ipd_booking.id AND hms_payment.type=4 AND hms_payment.section_id=5','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id = hms_ipd_booking.room_id','left');
        $this->db->where('hms_ipd_booking.id',$id);
        $this->db->where('hms_ipd_booking.is_deleted','0');
        $query = $this->db->get(); 
        //echo $this->db->last_query(); 
        return $query->row_array();
    }
    
    public function save_admission_prescription()
    {
        //echo "<pre>";print_r($_POST); exit;
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $prv_history="";
        if(!empty($post['prv_history']))
        {
            $prv_history = $post['prv_history'];
        }
        $personal_history="";
        if(!empty($post['personal_history']))
        {
            $personal_history = $post['personal_history'];
        }

        $data_patient = array(
                                "patient_name"=>$post['patient_name'],
                                "mobile_no"=>$post['mobile_no'],
                                "gender"=>$post['gender'], 
                                "age_y"=>$post['age_y'],
                                "age_m"=>$post['age_m'],
                                "age_d"=>$post['age_d'],
                                'adhar_no'=>$post['aadhaar_no'],
                                'relation_type'=>$post['relation_type'],
                                'relation_name'=>$post['relation_name'],
                                'relation_simulation_id'=>$post['relation_simulation_id'],
                            );

        if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
            $patient_id = $post['patient_id'];
            $this->db->where('id',$post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
        } 
        
        
        $data = array( 
                    "branch_id"=> $user_data['parent_id'],      
                    "ipd_no"=>$post['ipd_no'],
                    "patient_code"=>$post['patient_code'],
                    "patient_id"=>$post['patient_id'],
                    "booking_id"=>$post['booking_id'],
                    "patient_name"=>$post['patient_name'],
                    "mobile_no"=>$post['mobile_no'],
                    "gender"=>$post['gender'], 
                    "age_y"=>$post['age_y'],
                    "age_m"=>$post['age_m'],
                    "age_d"=>$post['age_d'],
                    "prv_history"=>$prv_history,
                    "personal_history"=>$personal_history,
                    "chief_complaints"=>$post['chief_complaints'],
                    "examination"=>$post['examination'],
                    "diagnosis"=>$post['diagnosis'],
                    "suggestion"=>$post['suggestion'],
                    "remark"=>$post['remark'],
                    "attended_doctor"=>$post['attended_doctor'],
                    "history_presenting_illness"=>$post['history_presenting_illness'], // added by nitin sharma 28th Jan 2024
                    "obstetrics_menstrual_history"=>$post['obstetrics_menstrual_history'], // added by nitin sharma 28th Jan 2024
                    "family_history_disease"=>$post['family_history_disease'], // added by nitin sharma 28th Jan 2024
                    "remark1"=>$post['remark1'], // added by nitin sharma 28th Jan 2024
                    "remark2"=>$post['remark2'], // added by nitin sharma 28th Jan 2024
                    "remark3"=>$post['remark3'], // added by nitin sharma 28th Jan 2024
                    "remark4"=>$post['remark4'], // added by nitin sharma 28th Jan 2024
                    "remark5"=>$post['remark5'], // added by nitin sharma 28th Jan 2024
                    
                    "examination_type"=>$post['optradio'], // added by nitin sharma 28th Jan 2024
                    "cvs"=>$post['cvs'], // added by nitin sharma 28th Jan 2024
                    "cns"=>$post['cns'], // added by nitin sharma 28th Jan 2024
                    "respiratory_system"=>$post['respiratory_system'], // added by nitin sharma 28th Jan 2024
                    "per_abdomen"=>$post['per_abdomen'], // added by nitin sharma 28th Jan 2024
                    "per_vaginal"=>$post['per_vaginal'], // added by nitin sharma 28th Jan 2024
                    "local_examination"=>$post['local_examination'] != NULL ? $post['local_examination'] : "", // added by nitin sharma 28th Jan 2024
                  
                    "status"=>1
                    ); 
            
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_ipd_patient_admission_prescription',$data); 
            // echo $this->db->last_query(); exit;
            $data_id = $this->db->insert_id(); 
            /* update doctor status in admission */
            /*$data_update= array('doctor_checked_status'=>1);
            $this->db->where('id',$post['booking_id']);
            $this->db->update('hms_admission_booking',$data_update);*/
            /* update doctor status in admission */


            
            $total_test = count(array_filter($post['test_name']));
            for($i=0;$i<$total_test;$i++)
            {
                
                    //check and add masters of test 
                    if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
                    {   
                        $this->db->select('path_test.*');  
                        $this->db->where('path_test.test_name',$post['test_name'][$i]);  
                        $this->db->where('path_test.is_deleted!=2'); 
                        $this->db->where('path_test.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('path_test');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $path_test_data = $query->result_array();
                            if(!empty($path_test_data))
                            {
                                $test_id = $path_test_data[0]['id'];
                            }
                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['test_name'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('path_test',$data);
                                $test_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    else
                    {
                        $test_id = $post['test_id'][$i];
                    }

                    

                $test_data = array(
                            "prescription_id"=>$data_id,
                            "test_name"=>$post['test_name'][$i],
                            "test_id"=>$test_id);
                        $this->db->insert('hms_ipd_admission_prescription_patient_test',$test_data); 
                        $test_data_id = $this->db->insert_id(); 
            }

            $total_prescription = count(array_filter($post['medicine_name'])); 
            for($i=0;$i<$total_prescription;$i++)
            {   
                    //echo $post['medicine_name'][$i];
                    //check and add masters 
                    if(!empty($post['medicine_name'][$i]))
                    {   
                        $this->db->select('hms_medicine_entry.*');  
                        //$this->db->from('hms_admission_medicine');
                        $this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
                        $this->db->where('hms_medicine_entry.is_deleted=0'); 
                        $this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_medicine_entry');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $company_data = $query->result_array();
                            if(!empty($company_data))
                            {
                                $medicine_id = $company_data[0]['id'];
                            }
                        }
                        else
                        {       
                                    $unit_id ='';
                                //check medicine type
                                if(!empty($post['medicine_type'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_unit.*');  
                                    $this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
                                    $this->db->where('hms_medicine_unit.is_deleted=0'); 
                                    $this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_unit');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $unit_data = $query->result_array();
                                        if(!empty($unit_data))
                                        {
                                            $unit_id = $unit_data[0]['id'];
                                        }   
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_unit',$data);
                                        $unit_id = $this->db->insert_id(); 
                                    }
                                }

                                ////check medicine type end

                                //check medicine company

                                if(!empty($post['brand'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_company.*');  
                                    $this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
                                    $this->db->where('hms_medicine_company.is_deleted=0'); 
                                    $this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_company');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $company_data = $query->result_array();
                                        if(!empty($company_data))
                                        {
                                            $company_id = $company_data[0]['id'];
                                        }
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'company_name'=>$post['brand'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_company',$data);
                                        $company_id = $this->db->insert_id(); 
                                    }
                                }


                                //medicine company end
                                
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_name'=>$post['medicine_name'][$i],
                                                'type'=>$unit_id,
                                                'salt'=>$post['salt'][$i],
                                                'manuf_company'=>$company_id,
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_medicine_entry',$data);
                                $medicine_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    //medicne type
                    if(!empty($post['medicine_type'][$i]))
                    {
                        $this->db->select('hms_admission_medicine_type.*');
                        //$this->db->from('hms_admission_medicine_type');
                        $this->db->where('hms_admission_medicine_type.medicine_type',$post['medicine_type'][$i]); 
                        $this->db->where('hms_admission_medicine_type.is_deleted!=2'); 
                        $this->db->where('hms_admission_medicine_type.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_admission_medicine_type');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_type'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_admission_medicine_type',$data);
                        }
                    }
                    //medicine dose
                    if(!empty($post['medicine_dose'][$i]))
                    {
                        $this->db->select('hms_admission_medicine_dosage.*');
                        //$this->db->from('hms_admission_medicine_dosage');
                        $this->db->where('hms_admission_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
                        $this->db->where('hms_admission_medicine_dosage.is_deleted!=2'); 
                        $this->db->where('hms_admission_medicine_dosage.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_admission_medicine_dosage');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_admission_medicine_dosage',$data);
                        }
                    }

                    //medicine duration
                    if(!empty($post['medicine_duration'][$i]))
                    {
                        $this->db->select('hms_admission_medicine_dosage_duration.*'); 
                        //$this->db->from('hms_admission_medicine_dosage_duration');
                        $this->db->where('hms_admission_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
                        $this->db->where('hms_admission_medicine_dosage_duration.is_deleted!=2'); 
                        $this->db->where('hms_admission_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_admission_medicine_dosage_duration');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_admission_medicine_dosage_duration',$data);
                        }
                    }

                    //medicine frequency
                    if(!empty($post['medicine_frequency'][$i]))
                    {
                        $this->db->select('hms_admission_medicine_dosage_frequency.*');
                        //$this->db->from('hms_admission_medicine_dosage_frequency');
                        $this->db->where('hms_admission_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
                        $this->db->where('hms_admission_medicine_dosage_frequency.is_deleted!=2'); 
                        $this->db->where('hms_admission_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_admission_medicine_dosage_frequency');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_admission_medicine_dosage_frequency',$data);
                        }
                    }

                    //medicine advice
                    if(!empty($post['medicine_advice'][$i]))
                    {
                        $this->db->select('*');
                        //$this->db->from('hms_admission_advice');
                        $this->db->where('hms_admission_advice.medicine_advice',$post['medicine_advice'][$i]); 
                        $this->db->where('hms_admission_advice.is_deleted!=2'); 
                        $this->db->where('hms_admission_advice.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_admission_advice');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_advice'=>$post['medicine_advice'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_admission_advice',$data);
                        }
                    }
                    //medicne id 
                    //echo $data_id; exit;
                    if(!empty($post['medicine_id'][$i]))
                    {
                        $medicine_id = $post['medicine_id'][$i];
                    }
                    else
                    {
                        $medicine_id = $medicine_id;
                    }
                    $prescription_data = array(
                        "prescription_id"=>$data_id,
                        'medicine_id'=>$medicine_id,
                        "medicine_name"=>$post['medicine_name'][$i],
                        "medicine_salt"=>$post['salt'][$i],
                        "medicine_brand"=>$post['brand'][$i],
                        "medicine_type"=>$post['medicine_type'][$i],
                        "medicine_dose"=>$post['medicine_dose'][$i],
                        "medicine_duration"=>$post['medicine_duration'][$i],
                        "medicine_frequency"=>$post['medicine_frequency'][$i],
                        "medicine_advice"=>$post['medicine_advice'][$i]
                        );
                    $this->db->insert('hms_ipd_admission_prescription_patient_pres',$prescription_data);
                    //echo $this->db->last_query(); exit;
                    $test_data_id = $this->db->insert_id(); 
                
            }
            
                $current_date = date('Y-m-d H:i:s');
                foreach($post['data'] as $key=>$val)
                {
                    
                    $data = array(
                                   "branch_id"=>$user_data['parent_id'],
                                   "type"=>6,
                                   "booking_id"=>$data_id,
                                   "vitals_id"=>$key,
                                   "vitals_value"=>$val['name'],
                                   
                                  );
                  
                  $this->db->insert('hms_branch_vitals',$data);
                  $id = $this->db->insert_id();
                } 
            

            return $data_id;
    
    }
    
    public function save_nursing_prescription()
    {
        //echo "<pre>";print_r($_POST); exit;
        $user_data = $this->session->userdata('auth_users');
        $post = $this->input->post(); 
        $prv_history="";
        if(!empty($post['prv_history']))
        {
            $prv_history = $post['prv_history'];
        }
        $personal_history="";
        if(!empty($post['personal_history']))
        {
            $personal_history = $post['personal_history'];
        }

        $data_patient = array(
                                "patient_name"=>$post['patient_name'],
                                "mobile_no"=>$post['mobile_no'],
                                "gender"=>$post['gender'], 
                                "age_y"=>$post['age_y'],
                                "age_m"=>$post['age_m'],
                                "age_d"=>$post['age_d'],
                                'adhar_no'=>$post['aadhaar_no'],
                                'relation_type'=>$post['relation_type'],
                                'relation_name'=>$post['relation_name'],
                                'relation_simulation_id'=>$post['relation_simulation_id'],
                            );

        if(!empty($post['patient_id']) && $post['patient_id']>0)
        {
            $patient_id = $post['patient_id'];
            $this->db->where('id',$post['patient_id']);
            $this->db->update('hms_patient',$data_patient);
        } 
        
        
        $data = array( 
                    "branch_id"=> $user_data['parent_id'],      
                    "ipd_no"=>$post['ipd_no'],
                    "patient_code"=>$post['patient_code'],
                    "patient_id"=>$post['patient_id'],
                    "booking_id"=>$post['booking_id'],
                    "patient_name"=>$post['patient_name'],
                    "mobile_no"=>$post['mobile_no'],
                    "gender"=>$post['gender'], 
                    "age_y"=>$post['age_y'],
                    "age_m"=>$post['age_m'],
                    "age_d"=>$post['age_d'],
                    "prv_history"=>$prv_history,
                    "personal_history"=>$personal_history,
                    "chief_complaints"=>$post['chief_complaints'],
                    "examination"=>$post['examination'],
                    "diagnosis"=>$post['diagnosis'],
                    "suggestion"=>$post['suggestion'],
                    "remark"=>$post['remark'],
                    "attended_doctor"=>$post['attended_doctor'],
                    "shift" => $post['shift'],
                    "status"=>1
                    ); 
            
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('hms_ipd_patient_nursing_prescription',$data); 
            //echo $this->db->last_query(); exit;
            $data_id = $this->db->insert_id(); 
            /* update doctor status in nursing */
            /*$data_update= array('doctor_checked_status'=>1);
            $this->db->where('id',$post['booking_id']);
            $this->db->update('hms_nursing_booking',$data_update);*/
            /* update doctor status in nursing */


            
            $total_test = count(array_filter($post['test_name']));
            for($i=0;$i<$total_test;$i++)
            {
                
                    //check and add masters of test 
                    if(!empty($post['test_name'][$i]) && empty($post['test_id'][$i]))
                    {   
                        $this->db->select('path_test.*');  
                        $this->db->where('path_test.test_name',$post['test_name'][$i]);  
                        $this->db->where('path_test.is_deleted!=2'); 
                        $this->db->where('path_test.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('path_test');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $path_test_data = $query->result_array();
                            if(!empty($path_test_data))
                            {
                                $test_id = $path_test_data[0]['id'];
                            }
                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['test_name'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('path_test',$data);
                                $test_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    else
                    {
                        $test_id = $post['test_id'][$i];
                    }

                    

                $test_data = array(
                            "prescription_id"=>$data_id,
                            "test_name"=>$post['test_name'][$i],
                            "test_id"=>$test_id);
                        $this->db->insert('hms_ipd_nursing_prescription_patient_test',$test_data); 
                        $test_data_id = $this->db->insert_id(); 
            }

            $total_prescription = count(array_filter($post['medicine_name'])); 
            for($i=0;$i<$total_prescription;$i++)
            {   
                    //echo $post['medicine_name'][$i];
                    //check and add masters 
                    if(!empty($post['medicine_name'][$i]))
                    {   
                        $this->db->select('hms_medicine_entry.*');  
                        //$this->db->from('hms_nursing_medicine');
                        $this->db->where('hms_medicine_entry.medicine_name',$post['medicine_name'][$i]);  
                        $this->db->where('hms_medicine_entry.is_deleted=0'); 
                        $this->db->where('hms_medicine_entry.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_medicine_entry');
                        $num = $query->num_rows();
                        //echo $this->db->last_query();
                        //echo $num; exit;
                        if($num>0)
                        {
                            $company_data = $query->result_array();
                            if(!empty($company_data))
                            {
                                $medicine_id = $company_data[0]['id'];
                            }
                        }
                        else
                        {       
                                    $unit_id ='';
                                //check medicine type
                                if(!empty($post['medicine_type'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_unit.*');  
                                    $this->db->where('hms_medicine_unit.medicine_unit',$post['medicine_type'][$i]);  
                                    $this->db->where('hms_medicine_unit.is_deleted=0'); 
                                    $this->db->where('hms_medicine_unit.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_unit');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $unit_data = $query->result_array();
                                        if(!empty($unit_data))
                                        {
                                            $unit_id = $unit_data[0]['id'];
                                        }   
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'test_name'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_unit',$data);
                                        $unit_id = $this->db->insert_id(); 
                                    }
                                }

                                ////check medicine type end

                                //check medicine company

                                if(!empty($post['brand'][$i]))
                                {
                                     
                                    $this->db->select('hms_medicine_company.*');  
                                    $this->db->where('hms_medicine_company.medicine_unit',$post['brand'][$i]);  
                                    $this->db->where('hms_medicine_company.is_deleted=0'); 
                                    $this->db->where('hms_medicine_company.branch_id',$user_data['parent_id']); 
                                    $query = $this->db->get('hms_medicine_company');
                                    $num = $query->num_rows();
                                    //echo $this->db->last_query();
                                    //echo $num; exit;
                                    if($num>0)
                                    {
                                        $company_data = $query->result_array();
                                        if(!empty($company_data))
                                        {
                                            $company_id = $company_data[0]['id'];
                                        }
                                    }
                                    else
                                    {
                                        $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'company_name'=>$post['brand'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                        $this->db->set('created_by',$user_data['id']);
                                        $this->db->set('created_date',date('Y-m-d H:i:s'));
                                        $this->db->insert('hms_medicine_company',$data);
                                        $company_id = $this->db->insert_id(); 
                                    }
                                }


                                //medicine company end
                                
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_name'=>$post['medicine_name'][$i],
                                                'type'=>$unit_id,
                                                'salt'=>$post['salt'][$i],
                                                'manuf_company'=>$company_id,
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_medicine_entry',$data);
                                $medicine_id = $this->db->insert_id(); 
                                //echo $this->db->last_query(); exit;
                        }

                    }
                    //medicne type
                    if(!empty($post['medicine_type'][$i]))
                    {
                        $this->db->select('hms_nursing_medicine_type.*');
                        //$this->db->from('hms_nursing_medicine_type');
                        $this->db->where('hms_nursing_medicine_type.medicine_type',$post['medicine_type'][$i]); 
                        $this->db->where('hms_nursing_medicine_type.is_deleted!=2'); 
                        $this->db->where('hms_nursing_medicine_type.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_nursing_medicine_type');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                                'branch_id'=>$user_data['parent_id'],
                                                'medicine_type'=>$post['medicine_type'][$i],
                                                'status'=>1,
                                                'ip_address'=>$_SERVER['REMOTE_ADDR']
                                             );

                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_nursing_medicine_type',$data);
                        }
                    }
                    //medicine dose
                    if(!empty($post['medicine_dose'][$i]))
                    {
                        $this->db->select('hms_nursing_medicine_dosage.*');
                        //$this->db->from('hms_nursing_medicine_dosage');
                        $this->db->where('hms_nursing_medicine_dosage.medicine_dosage',$post['medicine_dose'][$i]); 
                        $this->db->where('hms_nursing_medicine_dosage.is_deleted!=2'); 
                        $this->db->where('hms_nursing_medicine_dosage.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_nursing_medicine_dosage');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_nursing_medicine_dosage',$data);
                        }
                    }

                    //medicine duration
                    if(!empty($post['medicine_duration'][$i]))
                    {
                        $this->db->select('hms_nursing_medicine_dosage_duration.*'); 
                        //$this->db->from('hms_nursing_medicine_dosage_duration');
                        $this->db->where('hms_nursing_medicine_dosage_duration.medicine_dosage_duration',$post['medicine_duration'][$i]); 
                        $this->db->where('hms_nursing_medicine_dosage_duration.is_deleted!=2'); 
                        $this->db->where('hms_nursing_medicine_dosage_duration.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_nursing_medicine_dosage_duration');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                                $data = array( 
                                            'branch_id'=>$user_data['parent_id'],
                                            'medicine_dosage_duration'=>$post['medicine_dosage'][$i],
                                            'status'=>1,
                                            'ip_address'=>$_SERVER['REMOTE_ADDR']
                                            );
                                $this->db->set('created_by',$user_data['id']);
                                $this->db->set('created_date',date('Y-m-d H:i:s'));
                                $this->db->insert('hms_nursing_medicine_dosage_duration',$data);
                        }
                    }

                    //medicine frequency
                    if(!empty($post['medicine_frequency'][$i]))
                    {
                        $this->db->select('hms_nursing_medicine_dosage_frequency.*');
                        //$this->db->from('hms_nursing_medicine_dosage_frequency');
                        $this->db->where('hms_nursing_medicine_dosage_frequency.medicine_dosage_frequency',$post['medicine_frequency'][$i]); 
                        $this->db->where('hms_nursing_medicine_dosage_frequency.is_deleted!=2'); 
                        $this->db->where('hms_nursing_medicine_dosage_frequency.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_nursing_medicine_dosage_frequency');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_dosage_frequency'=>$post['medicine_frequency'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_nursing_medicine_dosage_frequency',$data);
                        }
                    }

                    //medicine advice
                    if(!empty($post['medicine_advice'][$i]))
                    {
                        $this->db->select('*');
                        //$this->db->from('hms_nursing_advice');
                        $this->db->where('hms_nursing_advice.medicine_advice',$post['medicine_advice'][$i]); 
                        $this->db->where('hms_nursing_advice.is_deleted!=2'); 
                        $this->db->where('hms_nursing_advice.branch_id',$user_data['parent_id']); 
                        $query = $this->db->get('hms_nursing_advice');
                        $num = $query->num_rows();
                        if($num>0)
                        {

                        }
                        else
                        {
                            $data = array( 
                                        'branch_id'=>$user_data['parent_id'],
                                        'medicine_advice'=>$post['medicine_advice'][$i],
                                        'status'=>1,
                                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                                        );
                            $this->db->set('created_by',$user_data['id']);
                            $this->db->set('created_date',date('Y-m-d H:i:s'));
                            $this->db->insert('hms_nursing_advice',$data);
                        }
                    }
                    //medicne id 
                    //echo $data_id; exit;
                    if(!empty($post['medicine_id'][$i]))
                    {
                        $medicine_id = $post['medicine_id'][$i];
                    }
                    else
                    {
                        $medicine_id = $medicine_id;
                    }
                    $prescription_data = array(
                        "prescription_id"=>$data_id,
                        'medicine_id'=>$medicine_id,
                        "medicine_name"=>$post['medicine_name'][$i],
                        "medicine_salt"=>$post['salt'][$i],
                        "medicine_brand"=>$post['brand'][$i],
                        "medicine_type"=>$post['medicine_type'][$i],
                        "medicine_dose"=>$post['medicine_dose'][$i],
                        "medicine_duration"=>$post['medicine_duration'][$i],
                        "medicine_frequency"=>$post['medicine_frequency'][$i],
                        "medicine_advice"=>$post['medicine_advice'][$i]
                        );
                    $this->db->insert('hms_ipd_nursing_prescription_patient_pres',$prescription_data);
                    //echo $this->db->last_query(); exit;
                    $test_data_id = $this->db->insert_id(); 
                
            }
            
                $current_date = date('Y-m-d H:i:s');
                foreach($post['data'] as $key=>$val)
                {
                    
                    $data = array(
                                   "branch_id"=>$user_data['parent_id'],
                                   "type"=>6,
                                   "booking_id"=>$data_id,
                                   "vitals_id"=>$key,
                                   "vitals_value"=>$val['name'],
                                   
                                  );
                  
                  $this->db->insert('hms_branch_vitals',$data);
                  $id = $this->db->insert_id();
                } 
            

            return $data_id;
    
    }

// Please write code above 
} 
?>