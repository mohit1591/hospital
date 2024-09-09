<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Opd_billing_model extends CI_Model 
{
	var $table = 'hms_opd_booking';
	//var $column = array('hms_opd_booking.booking_code','hms_opd_booking.type','hms_opd_booking.patient_id','hms_opd_booking.attended_doctor', 'hms_opd_booking.confirm_date','hms_opd_booking.total_amount','hms_opd_booking.booking_status','hms_opd_booking.created_date','hms_opd_booking.modified_date','hms_opd_booking.status'); patient_source

	var $column = array('hms_opd_booking.id','hms_opd_booking.booking_code','hms_patient.patient_name','docs.doctor_name', 'hms_opd_booking.appointment_date', 'hms_opd_booking.booking_date', 'hms_opd_booking.booking_status','hms_patient.patient_code', 'hms_patient.mobile_no','hms_patient.gender','hms_patient.address', 'hms_patient.father_husband', 'hms_patient.patient_email', 'ins_type.insurance_type', 'ins_cmpy.insurance_company', 'src.source', 'ds.disease', 'hms_hospital.hospital_name', 'spcl.specialization', 'docs.doctor_name', 'hms_opd_booking.booking_time','hms_opd_booking.validity_date', 'pkg.title', 'hms_opd_booking.next_app_date', 'hms_opd_booking.total_amount', 'hms_opd_booking.net_amount', 'hms_opd_booking.paid_amount', 'hms_opd_booking.discount','hms_opd_booking.policy_no','hms_opd_booking.particulars_charges','hms_opd_booking.kit_amount','hms_payment_mode.payment_mode'); 

	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		//$this->db->select("hms_opd_booking.*,hms_patient.patient_name,hms_patient.mobile_no"); payment_mode

		$this->db->select("hms_opd_booking.*,hms_patient.patient_name,hms_patient.mobile_no, 
			hms_patient.patient_code as patient_reg_no, hms_patient.mobile_no, (CASE WHEN hms_patient.gender=1 THEN  'Male' ELSE 'Female' END ) as gender, concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address, hms_patient.father_husband, sim.simulation as father_husband_simulation,hms_patient.patient_email, ins_type.insurance_type, ins_cmpy.insurance_company, src.source as patient_source, ds.disease , (CASE WHEN hms_opd_booking.referred_by =1 THEN concat(hms_hospital.hospital_name,' (Hospital)')  ELSE (CASE WHEN hms_opd_booking.referral_doctor=0 THEN concat('Other ',hms_opd_booking.ref_by_other) ELSE concat('Dr. ',hms_doctors.doctor_name) END)  END) as doctor_hospital_name, spcl.specialization, docs.doctor_name,pkg.title,hms_payment_mode.payment_mode,hms_patient.relation_name,hms_gardian_relation.relation,rel_sim.simulation as relation_simulation,hms_patient.dob");

		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','left');
        $this->db->join('hms_disease','hms_disease.id=hms_opd_booking.diseases','left');

        $this->db->join('hms_simulation as sim','sim.id=hms_patient.f_h_simulation', 'left');
        $this->db->join('hms_insurance_type as ins_type','ins_type.id=hms_opd_booking.insurance_type_id', 'left');

        $this->db->join('hms_simulation as rel_sim','rel_sim.id=hms_patient.relation_simulation_id', 'left');
        $this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type', 'left');
        
        $this->db->join('hms_insurance_company as ins_cmpy','ins_cmpy.id=hms_opd_booking.ins_company_id', 'left');

        $this->db->join('hms_patient_source as src', 'src.id=hms_opd_booking.source_from', 'Left');
        $this->db->join('hms_disease as ds', 'ds.id=hms_opd_booking.diseases', 'left');
       
    	$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking.referral_doctor','left');
        $this->db->join('hms_hospital','hms_hospital.id = hms_opd_booking.referral_hospital','left');
          $this->db->join('hms_specialization as spcl','spcl.id = hms_opd_booking.specialization_id','left');

        $this->db->join('hms_doctors as docs','docs.id = hms_opd_booking.attended_doctor','left');

		$this->db->join('hms_packages as pkg','pkg.id = hms_opd_booking.package_id','left');

		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_opd_booking.payment_mode', 'left');

		$this->db->where('hms_opd_booking.is_deleted','0'); 
		


		$search = $this->session->userdata('opd_billing_search');
		
		if($user_data['users_role']==4)
		{
			$this->db->where('hms_opd_booking.patient_id = "'.$user_data['parent_id'].'"');
			
		}
		elseif($user_data['users_role']==3)
		{
			$this->db->where('hms_opd_booking.referral_doctor = "'.$user_data['parent_id'].'"');
		}
		else
		{
			if(isset($search['branch_id']) && $search['branch_id']!='')
			{
				$this->db->where('hms_opd_booking.branch_id IN ('.$search['branch_id'].')');
			}
			else
			{
				$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');
			}
		}
	

		
		$this->db->where('hms_opd_booking.type =3');	
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			
            

			if(!empty($search['reciept_code']))
			{
				$this->db->where('hms_opd_booking.reciept_code = "'.$search['reciept_code'].'"');
			}
			if(!empty($search['amount_from']))
			{
				$this->db->where('hms_opd_booking.total_amount >= "'.$search['amount_from'].'"');
			}

			if(!empty($search['amount_to']))
			{
				$this->db->where('hms_opd_booking.total_amount <= "'.$search['amount_to'].'"');
			}

			if(!empty($search['paid_amount_from']))
			{
				$this->db->where('hms_opd_booking.paid_amount >= "'.$search['paid_amount_from'].'"');
			}

			if(!empty($search['paid_amount_to']))
			{
				$this->db->where('hms_opd_booking.paid_amount <= "'.$search['paid_amount_to'].'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['attended_doctor']))
			{
				$this->db->where('hms_opd_booking.attended_doctor',$search['attended_doctor']);
			}

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_opd_booking.referral_doctor',$search['referral_doctor']);
			}

			if(!empty($search['specialization']))
			{
				$this->db->where('hms_opd_booking.specialization',$search['specialization']);
			}

			if(!empty($search['source_from']))
			{
				$this->db->where('hms_opd_booking.source_from',$search['source_from']);
			}

			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

 
			if($search['insurance_type']!='')
			{
				$this->db->where('hms_patient.insurance_type',$search['insurance_type']);
			}

			if(!empty($search['insurance_type_id']))
			{
				$this->db->where('hms_patient.insurance_type_id',$search['insurance_type_id']);
			}

			if(!empty($search['ins_company_id']))
			{
				$this->db->where('hms_patient.ins_company_id',$search['ins_company_id']);
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_opd_booking.status',$search['status']);
			}
			if(isset($search['booking_status']) && $search['booking_status']!="")
			{
				$this->db->where('hms_opd_booking.booking_status',$search['booking_status']);
			}

			if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
			}

                      if(!empty($search['disease']))
			{
				$this->db->where('hms_disease.disease',$search['disease']);
			}
			if(!empty($search['disease_code']))
			{
				$this->db->where('hms_disease.disease_code',$search['disease_code']);
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
			$this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
		}
		$this->db->from($this->table); 
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
		$user_data = $this->session->userdata('auth_users');
	    $search = $this->session->userdata('opd_billing_search');
		$this->db->from('hms_opd_booking');
		$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
		
        if(!empty($search['start_date']))
		{
			$start_date = date('Y-m-d',strtotime($search['start_date']));
			$this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
		}

		if(!empty($search['end_date']))
		{
			$end_date = date('Y-m-d',strtotime($search['end_date']));
			$this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
		}
		$this->db->where('hms_opd_booking.is_deleted','0'); 
		$this->db->where('hms_opd_booking.type =3');
		return $this->db->count_all_results();
	}

	
	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_opd_booking.*,hms_opd_booking.policy_no as polocy_no,hms_opd_booking.insurance_type_id as insurance_type,hms_patient.simulation_id, hms_patient.patient_code, hms_patient.patient_name, hms_patient.mobile_no, hms_patient.gender, hms_patient.age_y, hms_patient.age_m, hms_patient.age_d, hms_patient.address,hms_patient.address2,hms_patient.address3, hms_patient.patient_code,hms_patient.adhar_no,hms_patient.city_id, hms_patient.state_id, hms_patient.country_id, hms_patient.patient_email,hms_patient.relation_type,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.modified_date as patient_modified_date,hms_patient.dob');
		$this->db->from('hms_opd_booking'); 
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id'); 
		$this->db->where('hms_opd_booking.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_opd_booking.id',$id);
		$this->db->where('hms_opd_booking.is_deleted','0');
		$query = $this->db->get(); 
		
		return $query->row_array();
	}
	
	

    public function delete_booking($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_opd_booking');
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
			$this->db->update('hms_opd_booking');
    	} 
    }

    public function patient_list()
    {
        $this->db->select('*');  
        $query = $this->db->get('hms_opd_booking');
        $result = $query->result(); 
        return $result; 
    }


    public function referal_doctor_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_doctors.doctor_name','ASC');
		$this->db->where('hms_doctors.is_deleted',0); 
		$this->db->where('hms_doctors.doctor_type IN (0,2)'); 
		$this->db->where('hms_doctors.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_doctors');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
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
		//echo $this->db->last_query(); 
		return $result; 
    } 

    public function employee_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_employees.name','ASC');
		$this->db->where('is_deleted',0);  
		$this->db->where('branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_employees');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function profile_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_profile.profile_name','ASC');
		$this->db->where('is_deleted',0);  
		$this->db->where('branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_profile');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    public function save_booking()
    {

    	$user_data = $this->session->userdata('auth_users');
    	$post = $this->input->post(); 
    	$dental_opd_particular_billing = $this->session->userdata('dental_opd_particular_billing');
		//echo "<pre>";print_r($post); exit;
        //$reciept_code = generate_unique_id(21);

    	if(!empty($post['branch_id']))
    	{
    		$branch_id = $post['branch_id'];

    	}
    	else
    	{
    		$branch_id = $user_data['parent_id'];
    	}

    	$insurance_type='';
   		if(isset($post['insurance_type']))
   		{
   			$insurance_type = $post['insurance_type'];
   		}
   		$pannel_type='';
   		if(isset($post['pannel_type']))
   		{
   			$pannel_type = $post['pannel_type'];
   		}
   		
   		$insurance_type_id='';
   		if(isset($post['insurance_type_id']))
   		{
   			$insurance_type_id = $post['insurance_type_id'];
   		}
   		$ins_company_id='';
   		if(isset($post['ins_company_id']))
   		{
   			$ins_company_id = $post['ins_company_id'];
   		}
   		
   		$dob = '0000-00-00';
		if(!empty($post['dob']))
		{
		    $dob_new=date('Y-m-d',strtotime($post['dob']));		
    	    if($dob_new=='1970-01-01' || $dob_new=="0000-00-00")
            {
                $dob = '0000-00-00';
            } 
    		else
    		{
    		    $dob=$dob_new;
    		}
		}
		

    	$data_patient = array(    
    		'simulation_id'=>$post['simulation_id'],
    		'branch_id'=>$branch_id,
    		'patient_name'=>$post['patient_name'],
    		'mobile_no'=>$post['mobile_no'],
    		'gender'=>$post['gender'],
    		'age_y'=>$post['age_y'],
    		'dob'=>$dob,
    		'relation_type'=>$post['relation_type'],
    		'relation_name'=>$post['relation_name'],
    		'relation_simulation_id'=>$post['relation_simulation_id'],
    		'age_m'=>$post['age_m'],
    		'age_d'=>$post['age_d'],
    		'patient_email'=>$post['patient_email'], 
    		'address'=>$post['address'],
    		'address2'=>$post['address_second'],
    		'address3'=>$post['address_third'],
    		'city_id'=>$post['city_id'],
    		'state_id'=>$post['state_id'],
    		'country_id'=>$post['country_id'],
    		'status'=>1,
    		'ip_address'=>$_SERVER['REMOTE_ADDR'],
    		'created_by'=>$user_data['id'],
    		'adhar_no'=>$post['adhar_no'],
			"insurance_type"=>$pannel_type,
			"insurance_type_id"=>$insurance_type_id,
			"ins_company_id"=>$ins_company_id,
			"polocy_no"=>$post['polocy_no'],
			"tpa_id"=>$post['tpa_id'],
			"ins_amount"=>$post['ins_amount'],
			"ins_authorization_no"=>$post['ins_authorization_no'],
			'patient_category'=>$post['patient_category'],

    		);
    	$specialization='';
    	if(!empty($post['specialization']))
    	{
    		$specialization=$post['specialization'];
    	}
    	else
    	{
    		$specialization='';
    	}

    	$data_test = array(
			'branch_id'=>$branch_id,
    		'diseases'=>$post['diseases'],	    
			'type'=>3,
    		'attended_doctor'=>$post['attended_doctor'],
    		'referral_doctor'=>$post['referral_doctor'],
    		'ref_by_other'=>$post['ref_by_other'],
    		'booking_date'=>date('Y-m-d',strtotime($post['booking_date'])),
    		'booking_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])),
    		'booking_status'=>1,
    		'payment_mode'=>$post['payment_mode'],
    		'total_amount'=>$post['total_amount'],
    		'package_id'=>$post['package_id'],
    		'package_amount'=>$post['kit_amount'],
    		'particulars_charges'=>$post['particulars_charges'],
    		'kit_amount'=>$post['kit_amount'],
    		'next_app_date'=>date('Y-m-d',strtotime($post['next_app_date'])),
    		'discount'=>$post['discount'],
    		'net_amount'=>$post['net_amount'],
    		'paid_amount'=>$post['paid_amount'],
    		'source_from'=>$post['source_from'],
    		'remarks'=>$post['remarks'],
    		'referred_by'=>$post['referred_by'],
    		'specialization_id'=>$specialization,
    		'referral_hospital'=>$post['referral_hospital'],
			'insurance_type_id'=>$insurance_type_id,
			'ins_company_id'=>$ins_company_id,
			'policy_no'=>$post['polocy_no'],
			'tpa_id'=>$post['tpa_id'],
			'ins_amount'=>$post['ins_amount'],
			'ins_authorization_no'=>$post['ins_authorization_no'],
			'pannel_type'=>$post['pannel_type'],
			'token_no'=>$post['token_no'],
			'patient_category'=>$post['patient_category'],
            'authorize_person'=>$post['authorize_person'],

    		);

    	$data_pay_test = array();

    	$data_testr = array_merge($data_test, $data_pay_test);
    	if(!empty($post['data_id']) && $post['data_id']>0)
    	{    
    	    $created_by_id= $this->get_by_id($post['data_id']);
    	    
    		$booking_id = $post['data_id'];
    		$this->db->set('modified_by',$user_data['id']);
    		$this->db->set('modified_date',date('Y-m-d H:i:s'));
    		$this->db->where('id',$post['data_id']);
    		$this->db->update('hms_opd_booking',$data_testr);  

    		/*add sales banlk detail*/
    		$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>8,'section_id'=>2));
    		$this->db->delete('hms_payment_mode_field_value_acc_section');
    		if(!empty($post['field_name']))
    		{
    			$post_field_value_name= $post['field_name'];
    			$counter_name= count($post_field_value_name); 
    			for($i=0;$i<$counter_name;$i++) 
    			{
    				$data_field_value= array(
    					'field_value'=>$post['field_name'][$i],
    					'field_id'=>$post['field_id'][$i],
    					'type'=>8,
    					'section_id'=>2,
    					'p_mode_id'=>$post['payment_mode'],
    					'branch_id'=>$user_data['parent_id'],
    					'parent_id'=>$booking_id,
    					'ip_address'=>$_SERVER['REMOTE_ADDR']
    					);
    				/*$this->db->set('created_by',$user_data['id']);
    				$this->db->set('created_date',date('Y-m-d H:i:s'));*/
    				$this->db->set('created_by',$created_by_id['created_by']);
    				$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_date'])));
    				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

    			}
    		}
    		/*add sales banlk detail*/ 
			//next appointment date time
    		if(!empty($post['next_app_date']) && $post['next_app_date']!='00-00-0000' && $post['next_app_date']!='01-01-1970')
    		{
            	// delete previous appoint on same booking 
    			$this->db->where('parent_id',$booking_id);
    			$this->db->where('appointment_type','2');
    			$this->db->where('patient_id',$post['patient_id']);
    			$this->db->delete('hms_opd_booking');

    			$appointment_code = generate_unique_id(20);
    			$appointment_data = array(

    				'branch_id'=>$branch_id,
    				'parent_id'=>$booking_id,
    				'appointment_type'=>2, 
    				'appointment_code'=>$appointment_code, 
    				'appointment_date'=>date('Y-m-d',strtotime($post['next_app_date'])),
    				'appointment_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])), 
    				'type'=>1,
    				'specialization_id'=>$post['specialization'],
    				'attended_doctor'=>$post['attended_doctor'],
    				'referral_doctor'=>$post['referral_doctor'],
    				'ref_by_other'=>$post['ref_by_other'],
    				'booking_date'=>date('Y-m-d H:i:s'),
    				'booking_status'=>0
    				);


    			$this->db->set('patient_id',$post['patient_id']);
    			$this->db->set('created_by',$user_data['id']);
    			$this->db->set('created_date',date('Y-m-d H:i:s'));
    			$this->db->insert('hms_opd_booking',$appointment_data);    
    			$appointment_id = $this->db->insert_id(); 
	            //echo $this->db->last_query(); exit;

    		}
            //end of next appointment

    		

			$this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('booking_id',$post['data_id']);
            $this->db->delete('hms_opd_booking_to_particulars'); 
            //echo $this->db->last_query(); exit;
            $opd_particular_billing_list = $this->session->userdata('opd_particular_billing');   
            //echo "<pre>"; print_r($opd_particular_billing_list); exit;
            if(!empty($opd_particular_billing_list) && isset($opd_particular_billing_list) )
            {

            	foreach($opd_particular_billing_list as $particular_billing)
            	{
            		$particular_data = array(
            			'booking_id'=>$booking_id,
            			'branch_id'=>$branch_id,
            			'particular'=>$particular_billing['particular'],
            			'quantity'=>$particular_billing['quantity'],
            			'amount'=>$particular_billing['amount'],
            			'particulars'=>$particular_billing['particulars'],
            			'status'=>1, 
            			);
            		$this->db->insert('hms_opd_booking_to_particulars',$particular_data);

            	}	

            }

 	$dental_opd_particular_billing = $this->session->userdata('dental_opd_particular_billing');
       //echo"hii";
      //echo "<pre>"; print_r($dental_opd_particular_billing); exit;
       if(!empty($dental_opd_particular_billing) && isset($dental_opd_particular_billing))  
       {
            
       	foreach($dental_opd_particular_billing as $dental_billing)
            	{
            		
            		$particular_data = array(
            			'booking_id'=>$booking_id,
            			'branch_id'=>$branch_id,
            			'particular'=>$dental_billing['particular'],
            			'quantity'=>$dental_billing['quantity'],
            			//'specialization'=>$specialization,
            			'amount'=>$dental_billing['amount'],
            			'particulars'=>$dental_billing['particulars'],
            			'tooth_num'=>$dental_billing['tooth_num'],
            			'tooth_num_val'=>$dental_billing['tooth_num_val'],
            			'status'=>1, 
            			);
            		$this->db->insert('hms_opd_booking_to_particulars',$particular_data);

            	}
            }
       		

       		$this->db->where('parent_id',$booking_id);
    		$this->db->where('section_id','4');
    		$this->db->where('balance>0');
    		$this->db->where('patient_id',$post['patient_id']);
    		$query_d_pay = $this->db->get('hms_payment');
    		$row_d_pay = $query_d_pay->result();

           //// Recipet no set  
		    if(!empty($row_d_pay))
		    {
		    	foreach($row_d_pay as $row_d)
		    	{
            		if(strtotime(date('Y-m-d',strtotime($post['booking_date']))) == strtotime(date('Y-m-d',strtotime($row_d->created_date))))
            		{
            			$createdate = $row_d->created_date;
            		}
            		else
            		{
            			$createdate = date('Y-m-d',strtotime($post['booking_date']));
            		}
            		
            		// Set comission //
            		$comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$post['paid_amount'],3,'',$post['discount']);
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
            		// End Set comission //
            		$payment_data = array(
						'parent_id'=>$booking_id,
						'branch_id'=>$branch_id,
						'section_id'=>'4',
						'hospital_id'=>$post['referral_hospital'],
						'doctor_id'=>$post['referral_doctor'],
						'patient_id'=>$post['patient_id'],
						'total_amount'=>$post['total_amount'],
						'discount_amount'=>$post['discount'],
						'net_amount'=>$post['net_amount'],
						'credit'=>$post['net_amount'],
						'debit'=>$post['paid_amount'],
						'pay_mode'=>$post['payment_mode'],
						'paid_amount'=>$post['paid_amount'],
						'doctor_comission'=>$doctor_comission,
						'hospital_comission'=>$hospital_comission,
						'comission_type'=>$comission_type,
						'total_comission'=>$total_comission,
						'balance'=>($post['net_amount']-$post['paid_amount'])+1,
						'created_by'=>$row_d->created_by,//$user_data['id'],
						'created_date'=>$createdate,
						);
					$this->db->where('id',$row_d->id);
					$this->db->update('hms_payment',$payment_data); 

				}
			}



			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>8,'section_id'=>4));
			$this->db->delete('hms_payment_mode_field_value_acc_section');
			if(!empty($post['field_name']))
			{
				$post_field_value_name= $post['field_name'];
				$counter_name= count($post_field_value_name); 
				for($i=0;$i<$counter_name;$i++) 
				{
					$data_field_value= array(
						'field_value'=>$post['field_name'][$i],
						'field_id'=>$post['field_id'][$i],
						'type'=>8,
						'section_id'=>4,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$booking_id,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					/*$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));*/
					$this->db->set('created_by',$created_by_id['created_by']);
					$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($created_by_id['created_date'])));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}
			/*add sales banlk detail*/ 

			if(!empty($post['package_id']))
			{

				$this->db->where('parent_id',$booking_id);
				$this->db->where('section_id','2');
				$this->db->where('patient_id',$post['patient_id']);
				$this->db->delete('hms_medicine_kit_stock'); 

				$medicine_kit_data = array(
					'parent_id'=>$booking_id,
					'branch_id'=>$branch_id,
					'section_id'=>'2',
					'kit_id'=>$post['package_id'],
					'patient_id'=>$post['patient_id'],
					'credit'=>1,
					'debit'=>0,
					'created_by'=>$created_by_id['created_by'],
					'created_date'=>date('Y-m-d H:i:s'),
					);
				$this->db->insert('hms_medicine_kit_stock',$medicine_kit_data); 
			}
			if(!empty($post['patient_id']) && $post['patient_id']>0)
			{
				$this->db->where('id',$post['patient_id']);
				$this->db->update('hms_patient',$data_patient);
		    	//echo $this->db->last_query(); exit;
			} 

		}
		else
		{   
			if(!empty($post['patient_id']) && $post['patient_id']>0)
			{
				$patient_id = $post['patient_id'];
			} 
			else
			{
				$patient_code = generate_unique_id(4);
				$this->db->set('patient_code',$patient_code);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_patient',$data_patient);  
				$patient_id = $this->db->insert_id(); 
		    	//user create
				$data = array(     
					"users_role"=>4,
					"parent_id"=>$patient_id,
					"username"=>'PAT000'.$patient_id,
					"password"=>md5('PASS'.$patient_id),
					"email"=>$post['patient_email'], 
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
					if(!empty($post['mobile_no']))
					{
						send_sms('patient_registration',18,$post['patient_name'],$post['mobile_no'],array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id));  
					}
				}
				//////////////////////////////////////////

				////////// SEND EMAIL ///////////////////
				if(!empty($post['patient_email']))
				{ 
					$this->load->library('general_library'); 
					$this->general_library->email($post['patient_email'],'','','','','','patient_registration','18',array('{patient_name}'=>$post['patient_name'],'{username}'=>'PAT000'.$patient_id,'{password}'=>'PASS'.$patient_id)); 
				}
			}



			$recieptcode = generate_unique_id(21);
			$this->db->set('patient_id',$patient_id);
			$this->db->set('reciept_code',$recieptcode);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_booking',$data_testr);    
			$booking_id = $this->db->insert_id(); 
            //echo $this->db->last_query(); exit;

			/*Generate Token */
		    $data_token=array(
				           'branch_id'=>$user_data['parent_id'],
							'patient_id'=>$patient_id,
							'doctor_id'=>$post['attended_doctor'],
							'booking_date'=>date("Y-m-d", strtotime($post['booking_date']) ),
							'token_no'=>$post['token_no'],
							 'type'=>$post['token_type'],
							'created_date'=>date('Y-m-d H:i:s')
									);
		//print_r($data_token);die;
			
			$this->db->insert('hms_patient_billing_to_token',$data_token); 
			//echo $this->db->last_query(); exit;
			   /*Generate Token */


			/*add sales banlk detail*/
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>8,'section_id'=>2));
			$this->db->delete('hms_payment_mode_field_value_acc_section');
			if(!empty($post['field_name']))
			{
				$post_field_value_name= $post['field_name'];
				$counter_name= count($post_field_value_name); 
				for($i=0;$i<$counter_name;$i++) 
				{
					$data_field_value= array(
						'field_value'=>$post['field_name'][$i],
						'field_id'=>$post['field_id'][$i],
						'type'=>8,
						'section_id'=>2,
						'p_mode_id'=>$post['payment_mode'],
						'branch_id'=>$user_data['parent_id'],
						'parent_id'=>$booking_id,
						'ip_address'=>$_SERVER['REMOTE_ADDR']
						);
					$this->db->set('created_by',$user_data['id']);
					$this->db->set('created_date',date('Y-m-d H:i:s'));
					$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
			}
			/*add sales banlk detail*/ 


            //next appointment date time
			if(!empty($post['next_app_date']) && $post['next_app_date']!='00-00-0000' && $post['next_app_date']!='01-01-1970')
			{
            	// delete previous appoint on same booking 
				$this->db->where('parent_id',$booking_id);
				$this->db->where('appointment_type','2');
				$this->db->where('patient_id',$patient_id);
				$this->db->delete('hms_opd_booking');

				$appointment_code = generate_unique_id(20);
				$appointment_data = array(

					'branch_id'=>$branch_id,
					'parent_id'=>$booking_id,
					'appointment_type'=>2, 
					'appointment_code'=>$appointment_code, 
					'appointment_date'=>date('Y-m-d',strtotime($post['next_app_date'])),
					'appointment_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['booking_time'])), 
					'type'=>1,
					'specialization_id'=>$post['specialization'],
					'attended_doctor'=>$post['attended_doctor'],
					'referral_doctor'=>$post['referral_doctor'],
					'ref_by_other'=>$post['ref_by_other'],
					'booking_date'=>date('Y-m-d H:i:s'),
					'booking_status'=>0
					);


				$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$appointment_data);    
				$appointment_id = $this->db->insert_id();
				//print_r($appointment_data); 
	            //echo $this->db->last_query(); exit;

			}
            //end of next appointment


			$opd_particular_billing = $this->session->userdata('opd_particular_billing');
			$particular_data = array();
			if(!empty($opd_particular_billing))
			{
				foreach ($opd_particular_billing as $value) {

					$particular_data = array(
						'booking_id'=>$booking_id,
						'branch_id'=>$branch_id,
						'particular'=>$value['particular'],
						'quantity'=>$value['quantity'],
						'amount'=>$value['amount'],
						'particulars'=>$value['particulars'],
						'status'=>1,
						'created_by'=>$user_data['id'],
						'created_date'=>date('Y-m-d H:i:s'),
						);
					$this->db->insert('hms_opd_booking_to_particulars',$particular_data);	
				}
			}

			$dental_opd_particular_billing = $this->session->userdata('dental_opd_particular_billing');
       //echo"hii";
      
       if(!empty($dental_opd_particular_billing))  
       {
            
       	foreach($dental_opd_particular_billing as $dental_billing)
            	{
            		
            		$particular_data = array(
            			'booking_id'=>$booking_id,
            			'branch_id'=>$branch_id,
            			'particular'=>$dental_billing['particular'],
            			'quantity'=>$dental_billing['quantity'],
            			//'specialization'=>$specialization,
            			'amount'=>$dental_billing['amount'],
            			'particulars'=>$dental_billing['particulars'],
            			'tooth_num'=>$dental_billing['tooth_num'],
            			'tooth_num_val'=>$dental_billing['tooth_num_val'],
            			'status'=>1, 
            			);
            		$this->db->insert('hms_opd_booking_to_particulars',$particular_data);

            	}	

       }
       
            $comission_arr = get_doc_hos_comission($post['referral_doctor'],$post['referral_hospital'],$post['paid_amount'],3,'',$post['discount']);
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
           
		    $payment_data = array(
		    	'parent_id'=>$booking_id,
		    	'branch_id'=>$branch_id,
		    	'section_id'=>'4',
		    	'hospital_id'=>$post['referral_hospital'],
		    	'doctor_id'=>$post['referral_doctor'],
		    	'patient_id'=>$patient_id,
		    	'total_amount'=>$post['total_amount'],
		    	'discount_amount'=>$post['discount'],
		    	'net_amount'=>$post['net_amount'],
		    	'credit'=>$post['net_amount'],
		    	'debit'=>$post['paid_amount'],
		    	'pay_mode'=>$post['payment_mode'],
		    	'paid_amount'=>$post['paid_amount'],
		    	'doctor_comission'=>$doctor_comission,
				'hospital_comission'=>$hospital_comission,
				'comission_type'=>$comission_type,
				'total_comission'=>$total_comission,
		    	'balance'=>($post['net_amount']-$post['paid_amount'])+1,
		    	'created_by'=>$user_data['id'],
		    	'created_date'=>date('Y-m-d H:i:s',strtotime($post['booking_date'].' '.$post['booking_time'])), //date('Y-m-d H:i:s',strtotime($post['booking_date'])),
		    	);


		    $this->db->insert('hms_payment',$payment_data);
		    $payment_id = $this->db->insert_id();
		    /* genereate receipt number */
		    //if(in_array('218',$user_data['permission']['section']))
		    //{
		    	if($post['paid_amount']>0)
		    	{
		    		$hospital_receipt_no= check_hospital_receipt_no();
		    		$data_receipt_data= array('branch_id'=>$user_data['parent_id'],
		    			'section_id'=>2,
		    			'payment_id'=>$payment_id,
		    			'parent_id'=>$booking_id,
		    			'reciept_prefix'=>$hospital_receipt_no['prefix'],
		    			'reciept_suffix'=>$hospital_receipt_no['suffix'],
		    			'created_by'=>$user_data['id'],
		    			'created_date'=>date('Y-m-d H:i:s')
		    			);
		    		$this->db->insert('hms_branch_hospital_no',$data_receipt_data);
		    	}
		    //}
            //echo $this->db->last_query(); exit;


		    /*add sales banlk detail*/
		    $this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>8,'section_id'=>4));
		    $this->db->delete('hms_payment_mode_field_value_acc_section');
		    if(!empty($post['field_name']))
		    {
		    	$post_field_value_name= $post['field_name'];
		    	$counter_name= count($post_field_value_name); 
		    	for($i=0;$i<$counter_name;$i++) 
		    	{
		    		$data_field_value= array(
		    			'field_value'=>$post['field_name'][$i],
		    			'field_id'=>$post['field_id'][$i],
		    			'type'=>8,
		    			'section_id'=>4,
		    			'p_mode_id'=>$post['payment_mode'],
		    			'branch_id'=>$user_data['parent_id'],
		    			'parent_id'=>$booking_id,
		    			'ip_address'=>$_SERVER['REMOTE_ADDR']
		    			);
		    		$this->db->set('created_by',$user_data['id']);
		    		$this->db->set('created_date',date('Y-m-d H:i:s'));
		    		$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

		    	}
		    }
		    /*add sales banlk detail*/ 

            //medicine kit
		    if(!empty($post['package_id']))
		    {
		    	$medicine_kit_data = array(
		    		'parent_id'=>$booking_id,
		    		'branch_id'=>$branch_id,
		    		'section_id'=>'2',
		    		'kit_id'=>$post['package_id'],
		    		'patient_id'=>$patient_id,
		    		'credit'=>1,
		    		'debit'=>0,
		    		'created_by'=>$user_data['id'],
		    		'created_date'=>date('Y-m-d H:i:s'),
		    		);
		    	$this->db->insert('hms_medicine_kit_stock',$medicine_kit_data); 
		    }

		}
		
		if(!empty($post['next_app_date']) && $post['next_app_date']!='00-00-0000' && $post['next_app_date']!='01-01-1970')
            {    
	            $this->db->where('booking_id',$booking_id);
	            $this->db->where('booking_type','30'); 
	            $this->db->delete('hms_next_appointment');
                
                $this->db->where('id',$booking_id); 
                $query_d_pay = $this->db->get('hms_opd_booking');
                $row_d_pay = $query_d_pay->row_array();
                //print_r($row_d_pay);die;
	            $next_appointment_data = array( 
							'branch_id'=>$branch_id,
							'booking_id'=>$booking_id,
							'booking_type'=>30, 
							'booking_code'=>$row_d_pay['reciept_code'], 
							'patient_id'=>$row_d_pay['patient_id'],
							'next_appointment'=>date('Y-m-d', strtotime($post['next_app_date'])), 
							'created_date'=>date('Y-m-d H:i:s') 
					);
	            $this->db->insert('hms_next_appointment',$next_appointment_data);
	            
	            //echo $this->db->last_query(); exit;

            } 
		$this->session->unset_userdata('opd_particular_billing');
        $this->session->unset_userdata('opd_particular_payment');
        $this->session->unset_userdata('dental_opd_particular_billing');
        
		return $booking_id; 	
	}


	public function check_patient_balance($patient_id="")
    {
    	if(!empty($patient_id))
    	{
    		$this->db->select('(sum(hms_payment.debit)-sum(hms_payment.credit)) as balance');
    		$this->db->where('hms_payment.patient_id',$patient_id);
    		$this->db->where('hms_payment.status',1);
    		$this->db->from('hms_payment');
    		$query = $this->db->get();
    		$result = $query->result();
    	    return $result[0]->balance;
    	}
    }


	public function opd_doctor_rate($doctor_id="")
    {
       $total_amount = 0;
       if(isset($doctor_id) && !empty($doctor_id))
       {
        
         $this->load->model('general/general_model');
         $doctor_data = $this->general_model->doctors_list($doctor_id);
         $doctor_pay_type = $doctor_data[0]->doctor_pay_type; 
         $rate_plan_id    = $doctor_data[0]->rate_plan_id; 
         //1 commision and 2 transaction
         $rate_list = $this->general_model->get_doctor_rate($doctor_pay_type,$rate_plan_id,$doctor_id);
         if(!empty($rate_list->doc_rate))
         {
         	$total_amount = $rate_list->doc_rate;		
         }
         
         
         
       }
       return $total_amount;
    }

   public function confirm_booking()
   {
   		
   		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'total_amount'=>$post['total_amount'],
					'discount'=>$post['discount'],
					'net_amount'=>$post['net_amount'],
					'paid_amount'=>$post['paid_amount'],
					'balance'=>$post['balance'],
					'booking_status'=>1,
					'payment_mode'=>$post['payment_mode'],
					'transaction_no'=>$post['transaction_no'],
					'branch_name'=>$post['branch_name'],
					'confirm_date'=>date('Y-m-d H:i:s'),
					'pay_now'=>1
		         );
		

		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data);  
		}

			$this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','2');
            $this->db->where('patient_id',$post['patient_id']);
            $this->db->delete('hms_payment'); 
            $payment_data = array(
                               'parent_id'=>$post['data_id'],
                               'branch_id'=>$user_data['parent_id'],
                               'section_id'=>'2',
                               'patient_id'=>$post['patient_id'],
                               'total_amount'=>$post['total_amount'],
                               'discount_amount'=>$post['discount'],
                               'net_amount'=>$post['net_amount'],
                               'credit'=>$post['net_amount'],
                               'debit'=>$post['paid_amount'],
                               'created_by'=>$user_data['id'],
                               'created_date'=>date('Y-m-d H:i:s'),
            	             );
            $this->db->insert('hms_payment',$payment_data);
			
   }

   public function compalaints_list($compaints_id="")
   {
		$chief_complaints = explode(',', $compaints_id);
		$users_data = $this->session->userdata('auth_users'); 
		$compalaintsname="";
		$i=1;
		$total = count($chief_complaints);
		foreach ($chief_complaints as $value) 
		{
			$this->db->select('hms_opd_chief_complaints.chief_complaints');  
			$this->db->where('hms_opd_chief_complaints.id',$value);  
			$this->db->where('hms_opd_chief_complaints.is_deleted',0);  
			$this->db->where('hms_opd_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_chief_complaints.id');  
			$query = $this->db->get('hms_opd_chief_complaints');
			$result = $query->row(); 

			$compalaintsname .= $result->chief_complaints;
			if($i!=$total)
			{
				$compalaintsname .=',';
			}
		
		$i++;
			
		}
		return $compalaintsname;
		 
    }

   public function examination_list($examination_id="")
   {
		$examination_ids = explode(',', $examination_id);
		$users_data = $this->session->userdata('auth_users'); 
		$examinationname="";
		$i=1;
		$total = count($examination_ids);
		foreach ($examination_ids as $value) 
		{
			$this->db->select('hms_opd_examination.examination');  
			$this->db->where('hms_opd_examination.id',$value);  
			$this->db->where('hms_opd_examination.is_deleted',0);  
			$this->db->where('hms_opd_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_examination.id');  
			$query = $this->db->get('hms_opd_examination');
			$result = $query->row(); 

			$examinationname .= $result->examination;
			if($i!=$total)
			{
				$examinationname .=',';
			}
		
		$i++;
			
		}
		return $examinationname;
		 
    }


    public function examinations_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_examination');
        $result = $query->result(); 
        return $result; 
    }

    public function diagnosis_name($diagnosis_id="")
   {
		$diagnosis_ids = explode(',', $diagnosis_id);
		$users_data = $this->session->userdata('auth_users'); 
		$diagnosisname="";
		$i=1;
		$total = count($diagnosis_ids);
		foreach ($diagnosis_ids as $value) 
		{
			$this->db->select('hms_opd_diagnosis.diagnosis');  
			$this->db->where('hms_opd_diagnosis.id',$value);  
			$this->db->where('hms_opd_diagnosis.is_deleted',0);  
			$this->db->where('hms_opd_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_diagnosis.id');  
			$query = $this->db->get('hms_opd_diagnosis');
			$result = $query->row(); 

			$diagnosisname .= $result->diagnosis;
			if($i!=$total)
			{
				$diagnosisname .=',';
			}
		
		$i++;
			
		}
		return $diagnosisname;
		 
    }


    public function diagnosis_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_diagnosis');
        $result = $query->result(); 
        return $result; 
    }

    public function suggetion_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_suggetion');
        $result = $query->result(); 
        return $result; 
    }

   public function suggetion_name($suggetion_id="")
   {
		$suggetion_ids = explode(',', $suggetion_id);
		$users_data = $this->session->userdata('auth_users'); 
		$suggetionname="";
		$i=1;
		$total = count($suggetion_ids);
		foreach ($suggetion_ids as $value) 
		{
			$this->db->select('hms_opd_suggetion.medicine_suggetion');  
			$this->db->where('hms_opd_suggetion.id',$value);  
			$this->db->where('hms_opd_suggetion.is_deleted',0);  
			$this->db->where('hms_opd_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_suggetion.id');  
			$query = $this->db->get('hms_opd_suggetion');
			$result = $query->row(); 

			$suggetionname .= $result->medicine_suggetion;
			if($i!=$total)
			{
				$suggetionname .=',';
			}
		
		$i++;
			
		}
		return $suggetionname;
		 
    }


    public function prv_history_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_prv_history');
        $result = $query->result(); 
        return $result; 
    }

   public function prv_history_name($prv_id="")
   {
		$prv_ids = explode(',', $prv_id);
		$users_data = $this->session->userdata('auth_users'); 
		$prvname="";
		$i=1;
		$total = count($prv_ids);
		foreach ($prv_ids as $value) 
		{
			$this->db->select('hms_opd_prv_history.prv_history');  
			$this->db->where('hms_opd_prv_history.id',$value);  
			$this->db->where('hms_opd_prv_history.is_deleted',0);  
			$this->db->where('hms_opd_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_prv_history.id');  
			$query = $this->db->get('hms_opd_prv_history');
			$result = $query->row(); 

			$prvname .= $result->prv_history;
			if($i!=$total)
			{
				$prvname .=',';
			}
		
		$i++;
			
		}
		return $prvname;
		 
    }

    public function personal_history_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_personal_history');
        $result = $query->result(); 
        return $result; 
    }
    
    public function template_list()
    {
        $users_data = $this->session->userdata('auth_users');
        $this->db->select('*');  
        $this->db->where('status','1');
        $this->db->where('branch_id',$users_data['parent_id']);
        $this->db->where('is_deleted','0');   
        $query = $this->db->get('hms_opd_prescription_template');
        $result = $query->result(); 
        return $result; 
    }
    

   public function personal_history_name($personal_history_id="")
   {
		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_opd_personal_history.personal_history');  
			$this->db->where('hms_opd_personal_history.id',$value);  
			$this->db->where('hms_opd_personal_history.is_deleted',0);  
			$this->db->where('hms_opd_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_opd_personal_history.id');  
			$query = $this->db->get('hms_opd_personal_history');
			$result = $query->row(); 

			$personalname .= $result->personal_history;
			if($i!=$total)
			{
				$personalname .=',';
			}
		
		$i++;
			
		}
		return $personalname;
		 
    }

    public function save_prescription()
    {
    	//echo "<pre>";print_r($_POST); exit;
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		
 		$data = array( 
					"branch_id"=> $user_data['parent_id'],      
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
					"booking_id"=>$post['booking_id'],
					"patient_name"=>$post['patient_name'],
					"mobile_no"=>$post['mobile_no'],
					"gender"=>$post['gender'], 
					"age_y"=>$post['age_y'],
					"age_m"=>$post['age_m'],
					"age_d"=>$post['age_d'],
					"patient_bp"=>$post['patient_bp'],
					"patient_temp"=>$post['patient_temp'],
					"patient_weight"=>$post['patient_weight'],
					"patient_height"=>$post['patient_height'],
					"patient_spo2"=>$post['patient_spo2'],
					"patient_rbs"=>$post['patient_rbs'],
					"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					"next_appointment_date"=>$post['next_appointment_date'],
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_prescription',$data); 
			//echo $this->db->last_query(); exit;
			$data_id = $this->db->insert_id(); 
			//echo $this->db->last_query(); exit;
			$total_test = count($post['test_name']);

			foreach ($post['test_name'] as $value) 
			{
					$test_data = array(
						"prescription_id"=>$data_id,
						"test_name"=>$value);
					$this->db->insert('hms_opd_prescription_patient_test',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			}

			$total_prescription = count($post['medicine_name']); 
			for($i=0;$i<=$total_prescription-1;$i++)
			{	
					$prescription_data = array(
						"prescription_id"=>$data_id,
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_opd_prescription_patient_pres',$prescription_data);
					$test_data_id = $this->db->insert_id(); 
				
			}
			;

			 


			if(!empty($post['next_appointment_date']))
		    {
		       $booking_id = $post['booking_id'];
		       $opd_booking_data = $this->opd_model->get_by_id($booking_id);
		      	
	           if(!empty($opd_booking_data))
	           {
	              $booking_id = $opd_booking_data['id'];
	              $referral_doctor = $opd_booking_data['referral_doctor'];
	              $attended_doctor = $opd_booking_data['attended_doctor'];
	              $patient_id = $opd_booking_data['patient_id'];
	              $simulation_id = $opd_booking_data['simulation_id'];
	              $patient_code = $opd_booking_data['patient_code'];
	              $patient_name = $opd_booking_data['patient_name'];
	              $mobile_no = $opd_booking_data['mobile_no'];
	              $gender = $opd_booking_data['gender'];
	              $age_y = $opd_booking_data['age_y'];
	              $age_m = $opd_booking_data['age_m'];
	              $age_d = $opd_booking_data['age_d'];
	              $address = $opd_booking_data['address'];
	              $city_id = $opd_booking_data['city_id'];
	              $state_id = $opd_booking_data['state_id'];
	              $country_id = $opd_booking_data['country_id']; 
	           }



	           $booking_code = generate_unique_id(9);

	           $data_test = array(    
						'booking_code'=>$booking_code, 
						'referral_doctor'=>$referral_doctor,
						'attended_doctor'=>$attended_doctor,
						'sample_collected_by'=>$sample_collected_by,
						'staff_refrenace_id'=>$staff_refrenace_id,
						'booking_date'=>date('Y-m-d',strtotime($post['next_appointment_date']))
						
		         );

		    	$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
		 	} 
	
    }


   public function template_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_prescription_template.*');  
		$this->db->where('hms_opd_prescription_template.id',$template_id);  
		$this->db->where('hms_opd_prescription_template.is_deleted',0); 
		$this->db->where('hms_opd_prescription_template.branch_id',$users_data['parent_id']); 
		$query = $this->db->get('hms_opd_prescription_template');
		$result = $query->row(); 
		return json_encode($result);
		 
    }
  //Other Branch Data for Booking
   public function branch_data($branch_id="")
   {
		$result = array();
		$result['booking_code'] = generate_unique_id(9,$branch_id);
		$result['patient_code'] = generate_unique_id(4,$branch_id);
		return json_encode($result);
	}

	public function get_simulation_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_simulation.*');  
		$this->db->where('hms_simulation.branch_id',$branch_id);  
		$this->db->where('hms_simulation.is_deleted',0); 
		$query = $this->db->get('hms_simulation');
		$simulation_list = $query->result();

		$drop = '<select name="simulation_id" id="simulation_id" onchange="find_gender(this.value)">
		         <option value="">Select</option>';
		if(!empty($simulation_list))
		{
		    foreach($simulation_list as $simulation)
		    {
		        $drop .= '<option value="'.$simulation->id.'">'.$simulation->simulation.'</option>';
		    }
		}
		$drop .= '</select>';
		
		return $json_data = $drop;
	}

	public function get_referral_doctor_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_doctors.*');  
		$this->db->where('hms_doctors.doctor_type IN (0,2)');
		$this->db->where('hms_doctors.branch_id',$branch_id);  
		$this->db->where('hms_doctors.is_deleted',0); 
		$query = $this->db->get('hms_doctors');
		$doctor_list = $query->result(); 
		
		$drop = '<select name="simulation_id" id="simulation_id">
		         <option value="">Select</option>';
		if(!empty($doctor_list))
		{
		    foreach($doctor_list as $doctor)
		    {
		        $drop .= '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
		    }
		}
		$drop .= '</select>';
		
		return $json_data = $drop;
	}
	public function get_specialization_data($branch_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_specialization.*');  
		$this->db->where('hms_specialization.branch_id',$branch_id);  
		$this->db->where('hms_specialization.is_deleted',0); 
		$query = $this->db->get('hms_specialization');
		$specialization_list = $query->result(); 
		$drop = '<select name="specialization" class="w-150px" id="specilization_id"  onchange="return get_doctor_specilization(this.value,'.$branch_id.');">
		         <option value="">Select</option>';
		if(!empty($specialization_list))
		{
		    foreach($specialization_list as $specialization)
		    {
		        $drop .= '<option value="'.$specialization->id.'">'.$specialization->specialization.'</option>';
		    }
		}
		$drop .= '</select>';

		if(in_array('44',$users_data['permission']['action'])) {
                      
          $drop .= '<a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">New</a>';
           }
		
		return $json_data = $drop;
	}

	public function get_attended_doctor_data($branch_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_doctors.*');  
		$this->db->where('hms_doctors.branch_id',$branch_id);
		$this->db->where('hms_doctors.doctor_type IN (1,2)');   
		$this->db->where('hms_doctors.is_deleted',0); 
		$query = $this->db->get('hms_doctors');
		$doctor_list = $query->result(); 
		
		$drop = '<select name="simulation_id" id="simulation_id">
		         <option value="">Select</option>';
		if(!empty($specialization_list))
		{
		    foreach($doctor_list as $doctor)
		    {
		        $drop .= '<option value="'.$doctor->id.'">'.$doctor->doctor_name.'</option>';
		    }
		}
		$drop .= '</select>';
		
		return $json_data = $drop;
	}
//Other Branch Data for Booking
	
   public function get_template_test_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_prescription_patient_test_template.*');  
		$this->db->where('hms_opd_prescription_patient_test_template.template_id',$template_id);  
		$query = $this->db->get('hms_opd_prescription_patient_test_template');
		$result = $query->result(); 
		//echo $this->db->last_query(); exit;
		return json_encode($result);
		 
    }

   public function get_template_prescription_data($template_id="")
   {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_prescription_patient_pres_template.*');  
		$this->db->where('hms_opd_prescription_patient_pres_template.template_id',$template_id);  
		$query = $this->db->get('hms_opd_prescription_patient_pres_template');
		$result = $query->result(); 
		return json_encode($result);
		 
    }

	public function get_billed_particular_list($booking_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('particular,particulars,amount,quantity');   
		if(!empty($booking_id))
		{
          $this->db->where('booking_id',$booking_id); 
		}    
		  
		$query = $this->db->get('hms_opd_booking_to_particulars');
		$result = $query->result(); 
		
		$particular_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $particular)
			{
               $particular_list[$particular->particular]['particular'] = $particular->particular;
               $particular_list[$particular->particular]['particulars'] = $particular->particulars;
               $particular_list[$particular->particular]['amount'] = $particular->amount;
                $particular_list[$particular->particular]['normal_amount'] = $particular->amount;
               $particular_list[$particular->particular]['quantity'] = $particular->quantity;
              // $particular_list[$i]['kit_amount'] = 0;
			$i++;
			}
		}
		return $particular_list; 
    }

    public function get_billed_dental_particular_list($booking_id="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('particular,particulars,amount,quantity,tooth_num_val,tooth_num');   
		if(!empty($booking_id))
		{
          $this->db->where('booking_id',$booking_id); 
		}    
		  
		$query = $this->db->get('hms_opd_booking_to_particulars');
		$result = $query->result(); 
		
		$particular_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $particular)
			{
               $particular_list[$i]['particular'] = $particular->particular;
               $particular_list[$i]['particulars'] = $particular->particulars;
               $particular_list[$i]['amount'] = $particular->amount;
               $particular_list[$i]['quantity'] = $particular->quantity;
               $particular_list[$i]['tooth_num_val'] = $particular->tooth_num_val;
               $particular_list[$i]['tooth_num'] = $particular->tooth_num;
               $particular_list[$i]['normal_amount'] = $particular->amount;
               $particular_list[$i]['kit_amount'] = 0;
               //$particular_list[$i]['specialization'] = $particular->specialization;
			$i++;
			}
		}
		return $particular_list; 
    }

    public function particular_list($ids="")
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_particular.*'); 
		if(!empty($ids))
		{
			$this->db->where('hms_opd_particular.id  IN ('.$ids.')'); 
		}
		$this->db->where('hms_opd_particular.is_deleted',0);  
		$this->db->where('hms_opd_particular.branch_id  IN ('.$users_data['parent_id'].',0)'); 
		$this->db->group_by('hms_opd_particular.id');  
		$query = $this->db->get('hms_opd_particular');
		$result = $query->result(); 
		return $result; 
    }



    function get_all_detail_print($ids="",$branch_id=''){
    	$result_opd=array();
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_opd_booking.*,hms_patient.*,hms_users.*,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_packages.title as package_name,hms_doctors.consultant_charge,hms_payment_mode.payment_mode,hms_branch_hospital_no.reciept_prefix,hms_branch_hospital_no.reciept_suffix,hms_gardian_relation.relation,(CASE WHEN hms_opd_booking.referred_by=1 THEN ref_hospital.hospital_name ELSE ref_doctor.doctor_name END) as referral_doctor_name,concat_ws(' ',hms_patient.address, hms_patient.address2, hms_patient.address3) as address,(CASE WHEN hms_patient.insurance_type=1 THEN 'TPA' ELSE 'Normal' END ) as insurance_type_name, hms_insurance_type.insurance_type as insurance_name, hms_insurance_company.insurance_company,hms_doctors.header_content,hms_doctors.seprate_header,hms_doctors.opd_header,hms_doctors.billing_header,hms_doctors.doc_reg_no,hms_countries.country as country_name, hms_state.state as state_name, hms_cities.city as city_name,hms_patient.address as address1,hms_patient_category.patient_category as patient_category_name,hms_authorize_person.authorize_person as authorize_person_name"); 
		$this->db->join('hms_patient','hms_patient.id = hms_opd_booking.patient_id','left');
		$this->db->join('hms_patient_category','hms_patient_category.id = hms_opd_booking.patient_category','left'); 
         $this->db->join('hms_authorize_person','hms_authorize_person.id = hms_opd_booking.authorize_person','left');
		$this->db->join('hms_countries','hms_countries.id = hms_patient.country_id','left'); // country name
		$this->db->join('hms_state','hms_state.id = hms_patient.state_id','left'); // state name
		$this->db->join('hms_cities','hms_cities.id = hms_patient.city_id','left'); // city name
		
		
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type');
		$this->db->join('hms_packages','hms_packages.id = hms_opd_booking.package_id','left');
		
		$this->db->join('hms_doctors','hms_doctors.id = hms_opd_booking. attended_doctor','left');
		$this->db->join('hms_doctors as ref_doctor','ref_doctor.id = hms_opd_booking. referral_doctor','left');
		$this->db->join('hms_hospital as ref_hospital','ref_hospital.id = hms_opd_booking. referral_hospital','left');

		$this->db->join('hms_users','hms_users.id = hms_opd_booking.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left'); 
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left'); 
		$this->db->join('hms_payment_mode','hms_payment_mode.id = hms_opd_booking.payment_mode'); 
                $this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.parent_id = hms_opd_booking.id AND hms_branch_hospital_no.section_id=2','left');
        $this->db->join('hms_insurance_type',' hms_insurance_type.id = hms_opd_booking.insurance_type_id','left'); // insurance type
		$this->db->join('hms_insurance_company',' hms_insurance_company.id = hms_opd_booking.ins_company_id','left'); // insurance type 
		
		$this->db->where('hms_opd_booking.is_deleted','0'); 
		if(!empty($branch_id))
		{
			$this->db->where('hms_opd_booking.branch_id = "'.$branch_id.'"');
		}
		else
		{
			$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');
		}
		 
		$this->db->where('hms_opd_booking.id = "'.$ids.'"');
		$this->db->from($this->table);
		$result_opd['opd_list']= $this->db->get()->result();
		
		$this->db->select('hms_opd_booking_to_particulars.*');
		$this->db->join('hms_opd_booking','hms_opd_booking.id = hms_opd_booking_to_particulars.booking_id'); 
		$this->db->where('hms_opd_booking_to_particulars.booking_id = "'.$ids.'"');
                if(!empty($branch_id))
		{
			$this->db->where('hms_opd_booking_to_particulars.branch_id = "'.$branch_id.'"'); 	
		}
		else
		{
			$this->db->where('hms_opd_booking_to_particulars.branch_id = "'.$user_data['parent_id'].'"'); 
		}
		$this->db->from('hms_opd_booking_to_particulars');
		$result_opd['opd_list']['particular_list']=$this->db->get()->result();

		return $result_opd;
		
    }

    function template_format($data="",$branch_id='')
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	
    	if(!empty($branch_id))
		{
			$this->db->where('hms_print_branch_template.branch_id = "'.$branch_id.'"');
		}
		else
		{
			$this->db->where('hms_print_branch_template.branch_id = "'.$users_data['parent_id'].'"');
		} 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	return $query;

    }

    public function diseases_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_disease.disease','ASC');
		$this->db->where('hms_disease.is_deleted',0);
                $this->db->where('hms_disease.status',1); 
		$this->db->where('hms_disease.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_disease');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }
    


    function search_opd_data()
    {
    	$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_opd_booking.*,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.age_y,hms_patient.age_m,hms_patient.age_d,hms_patient.gender,hms_patient.patient_code");
		$this->db->join('hms_patient','hms_patient.id=hms_opd_booking.patient_id','inner');
$this->db->join('hms_disease','hms_disease.id=hms_opd_booking.diseases','left');
		$this->db->where('hms_opd_booking.is_deleted','0'); 
		
		$this->db->where('hms_opd_booking.type','3'); 

		$search = $this->session->userdata('opd_billing_search');
		/*if(isset($search) && !empty($search))
		{
			$this->db->where('hms_opd_booking.branch_id = "'.$search['branch_id'].'"');
		}
		else
		{
			$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');	
		}*/

		if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_opd_booking.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_opd_booking.branch_id = "'.$user_data['parent_id'].'"');
		}

		//$this->db->from($this->table); 
		/////// Search query end //////////////
		if(isset($search) && !empty($search))
		{
			if(!empty($search['reciept_code']))
			{
				$this->db->where('hms_opd_booking.reciept_code = "'.$search['reciept_code'].'"');
			}

                        if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_opd_booking.booking_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_opd_booking.booking_date <= "'.$end_date.'"');
			}
			

			

			if(!empty($search['amount_from']))
			{
				$this->db->where('hms_opd_booking.total_amount >= "'.$search['amount_from'].'"');
			}

			if(!empty($search['amount_to']))
			{
				$this->db->where('hms_opd_booking.total_amount <= "'.$search['amount_to'].'"');
			}

			if(!empty($search['paid_amount_from']))
			{
				$this->db->where('hms_opd_booking.paid_amount >= "'.$search['paid_amount_from'].'"');
			}

			if(!empty($search['paid_amount_to']))
			{
				$this->db->where('hms_opd_booking.paid_amount <= "'.$search['paid_amount_to'].'"');
			}

			if(!empty($search['simulation_id']))
			{
				$this->db->where('hms_patient.simulation_id',$search['simulation_id']);
			}

			if(!empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}

			if($search['insurance_type']!='')
			{
				$this->db->where('hms_opd_booking.pannel_type',$search['insurance_type']);
			}

			if(!empty($search['insurance_type_id']))
			{
				$this->db->where('hms_opd_booking.insurance_type_id',$search['insurance_type_id']);
			}

			if(!empty($search['ins_company_id']))
			{
				$this->db->where('hms_opd_booking.ins_company_id',$search['ins_company_id']);
			}

			if(!empty($search['patient_code']))
			{
				$this->db->where('hms_patient.patient_code',$search['patient_code']);
			}

			if(!empty($search['attended_doctor']))
			{
				$this->db->where('hms_opd_booking.attended_doctor',$search['attended_doctor']);
			}

			if(!empty($search['referral_doctor']))
			{
				$this->db->where('hms_opd_booking.referral_doctor',$search['referral_doctor']);
			}

			if(!empty($search['specialization']))
			{
				$this->db->where('hms_opd_booking.specialization',$search['specialization']);
			}


			if(!empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no LIKE "'.$search['mobile_no'].'%"');
			}

			if(isset($search['gender']) && $search['gender']!="")
			{
				$this->db->where('hms_patient.gender',$search['gender']);
			}
			if(isset($search['adhar_no']) && $search['adhar_no']!="")
			{
				$this->db->where('hms_patient.adhar_no',$search['adhar_no']);
			}

			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('hms_opd_booking.status',$search['status']);
			}
			if(isset($search['booking_status']) && $search['booking_status']!="")
			{
				$this->db->where('hms_opd_booking.booking_status',$search['booking_status']);
			}

                        if(!empty($search['disease']))
			{
				$this->db->where('hms_disease.disease',$search['disease']);
			}
			if(!empty($search['disease_code']))
			{
				$this->db->where('hms_disease.disease_code',$search['disease_code']);
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
			$this->db->where('hms_opd_booking.created_by IN ('.$emp_ids.')');
		}
	    $query = $this->db->get('hms_opd_booking'); 

		$data= $query->result();
		
		return $data;
	}


	public function source_list()
    {
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		$this->db->order_by('hms_patient_source.source','ASC');
		$this->db->where('hms_patient_source.is_deleted',0);
                $this->db->where('hms_patient_source.status',1); 
		$this->db->where('hms_patient_source.branch_id',$users_data['parent_id']);  
		$query = $this->db->get('hms_patient_source');
		$result = $query->result(); 
		//echo $this->db->last_query(); 
		return $result; 
    }

    function sms_template_format($data="")
    {
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_sms_branch_template.*');
    	$this->db->where($data);
    	$this->db->where('hms_sms_branch_template.branch_id = "'.$users_data['parent_id'].'"'); 
    	$this->db->from('hms_sms_branch_template');
    	$query=$this->db->get()->row();
    	//echo $this->db->last_query();
    	return $query;

    }

    function sms_url()
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

	function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
		$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');
		$this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
		$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.type',8);
		$this->db->where('hms_payment_mode_field_value_acc_section.section_id',4);
		$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
		return $query;
	}

	public function get_panel_price($branch_id="",$particulars="",$ins_company_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_opd_particular_charge.charge');
		$this->db->where('hms_opd_particular_charge.panel_company_id',$ins_company_id);
		$this->db->where('hms_opd_particular_charge.particular_id',$particulars);
		$this->db->where('hms_opd_particular_charge.type',1);
		$this->db->where('hms_opd_particular_charge.branch_id',$users_data['parent_id']);
		$result= $this->db->get('hms_opd_particular_charge')->result();

		if(count($result)>0)
		{
			return $result;
		}
		else
		{

			$this->db->select('hms_opd_particular.charge');
			$this->db->where('hms_opd_particular.id',$particulars);
			$this->db->where('hms_opd_particular.branch_id',$users_data['parent_id']);
			$result= $this->db->get('hms_opd_particular')->result();
			//echo $this->db->last_query();die;
			return $result;
		}
        
	}

	public function get_token_setting()
    {
    	//echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_billing_token_setting.*"); 
	   $this->db->from('hms_billing_token_setting');
	   $this->db->where('hms_billing_token_setting.branch_id',$user_data['parent_id']);
	   $query = $this->db->get(); 
	   return $query->row_array();
    }


    public function payment_list($booking_id="",$branch_id="")
    {
    	//echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_payment.*, hms_payment_mode.payment_mode"); 
	   $this->db->from('hms_payment');
	   $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
	   $this->db->where('hms_payment.branch_id',$branch_id);
	   $this->db->where('hms_payment.parent_id',$booking_id);
	   $this->db->where('hms_payment.section_id','4');
	   $this->db->order_by('hms_payment.id', 'DESC');
	   $query = $this->db->get(); 
	   return $query->result_array();
    }


    public function total_payment($booking_id="",$branch_id="",$section_id="")
    {
    	//echo "hi";die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("
       	                 sum(hms_payment.credit) total_credit, 
       	                 sum(hms_payment.debit) total_debit, 
       	                 (sum(hms_payment.credit)-sum(hms_payment.debit)) as total_balance
       	                 "); 
	   $this->db->from('hms_payment');
	   $this->db->join('hms_payment_mode','hms_payment_mode.id = hms_payment.pay_mode','left');
	   $this->db->where('hms_payment.branch_id',$branch_id);
	   $this->db->where('hms_payment.parent_id',$booking_id);
	   $this->db->where('hms_payment.section_id',$section_id);
	   $query = $this->db->get(); 
	   //echo $this->db->last_query(); 
	   return $query->row_array();
    }

    public function get_patient_token_details($id='')
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_billing_to_token.*"); 
	   $this->db->from('hms_patient_billing_to_token');
	   $this->db->where('hms_patient_billing_to_token.doctor_id',$id);
	   $this->db->where('hms_patient_billing_to_token.branch_id',$user_data['parent_id']);
	   $query = $this->db->get(); 
	   return $query->row_array();

    }
     public function get_patient_token_details_for_type_doctor($id='',$booking_date='',$type='')
    {
    	//echo $type;die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_billing_to_token.*"); 
	   $this->db->from('hms_patient_billing_to_token');
	   $this->db->where('hms_patient_billing_to_token.doctor_id',$id);
	   $this->db->where('hms_patient_billing_to_token.booking_date',$booking_date);
	   $this->db->where('hms_patient_billing_to_token.type',$type);
	   $this->db->where('hms_patient_billing_to_token.branch_id',$user_data['parent_id']);
	   $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
	   $query = $this->db->get(); 
	   return $query->row_array();

    }
    public function get_patient_token_details_for_type_hospital($booking_date='',$type='')
    {
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_billing_to_token.*"); 
	   $this->db->from('hms_patient_billing_to_token');
	   $this->db->where('hms_patient_billing_to_token.booking_date',$booking_date);
	   $this->db->where('hms_patient_billing_to_token.type',$type);
	   $this->db->where('hms_patient_billing_to_token.branch_id',$user_data['parent_id']);
	   $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
	   $query = $this->db->get(); 
	  // $count=$query->num_rows();
	   return $query->row_array();

    }

    public function get_patient_token_details_for_type_specialization($specialization_id='',$booking_date='',$type='')
    {
    	//echo $type;die;
       $user_data = $this->session->userdata('auth_users');
       $this->db->select("hms_patient_billing_to_token.*"); 
	   $this->db->from('hms_patient_billing_to_token');
	   $this->db->where('hms_patient_billing_to_token.specialization_id',$specialization_id);
	   $this->db->where('hms_patient_billing_to_token.booking_date',$booking_date);
	   $this->db->where('hms_patient_billing_to_token.type',$type);
	   $this->db->where('hms_patient_billing_to_token.branch_id',$user_data['parent_id']);
	   $this->db->order_by("id", "DESC");
       $this->db->limit(1, 0);
	   $query = $this->db->get(); 
	   return $query->row_array();

    }
    
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
} 
?>