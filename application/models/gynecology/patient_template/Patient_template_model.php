<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Patient_template_model extends CI_Model 
{
	var $table = 'hms_gynecology_patient_template';
	var $column = array('hms_gynecology_patient_template.id','hms_gynecology_patient_template.template_name','hms_gynecology_patient_template.status', 'hms_gynecology_patient_template.created_date', 'hms_gynecology_patient_template.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_gynecology_patient_template.*");
		$this->db->where('hms_gynecology_patient_template.is_deleted','0'); 
		$this->db->where('hms_gynecology_patient_template.branch_id = "'.$user_data['parent_id'].'"');
		if(isset($search) && !empty($search))
		{
			
            if(!empty($search['start_date']))
			{
				$start_date = date('Y-m-d h:i:s',strtotime($search['start_date']));
				$this->db->where('created_date >= "'.$start_date.'"');
			}

			if(!empty($search['end_date']))
			{
				$end_date = date('Y-m-d h:i:s',strtotime($search['end_date']));
				$this->db->where('created_date >= "'.$end_date.'"');
			}
			if(isset($search['status']) && $search['status']!="")
			{
				$this->db->where('status',$search['status']);
			}


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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	
	public function get_by_id($id)
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_gynecology_patient_template.*');
		$this->db->from('hms_gynecology_patient_template'); 
		$this->db->where('hms_gynecology_patient_template.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_gynecology_patient_template.id',$id);
		$this->db->where('hms_gynecology_patient_template.is_deleted','0');
		$query = $this->db->get(); 
		$result = $query->row_array();
		return $result;
	}
	
	public function save()
	{  

		$user_data = $this->session->userdata('auth_users');
		$patient_history_data = $this->session->userdata('patient_history_data');
		$patient_family_history_data = $this->session->userdata('patient_family_history_data');
		$patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
		$patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data');
		$patient_medical_history_data = $this->session->userdata('patient_medical_history_data');
		$patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data'); 

		$patient_disease_data = $this->session->userdata('patient_disease_data');
		$patient_complaint_data = $this->session->userdata('patient_complaint_data');
		$patient_allergy_data = $this->session->userdata('patient_allergy_data');
		$patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
		$patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
		$patient_investigation_data = $this->session->userdata('patient_investigation_data');
		$patient_advice_data = $this->session->userdata('patient_advice_data');
		$patient_gpla_data = $this->session->userdata('patient_gpla_data');
		

		$post = $this->input->post();
		$next_appointment_date='';
        $check_appointment='';
        if(!empty($post['check_appointment']))
        {
          	$check_appointment=1;
          	if(!empty($post['next_appointment_date']) && $post['next_appointment_date']!='0000-00-00 00:00:00' && date('Y-m-d',strtotime($post['next_appointment_date']))!='1970-01-01')
	        {
	            $next_appointment_date = date('Y-m-d H:i',strtotime($post['next_appointment_date']));
	        } 

        }
        else
        {
          	$check_appointment=0;
           	$next_appointment_date = ''; 
        }
		$data = array(     
						'branch_id'=>$user_data['parent_id'],
						"template_name"=>$post['template_name'],
						'check_appointment'=>$check_appointment,
                        'appointment_date'=>$next_appointment_date,
						"status"=>1
			         ); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			$data_id = $post['data_id'];
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_gynecology_patient_template',$data); 
            
		    $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('template_id',$post['data_id']);
			$this->db->delete('hms_gynecology_prescription_patient_history_template');
			$patient_history_data = $this->session->userdata('patient_history_data');	
			if(!empty($patient_history_data))
			{
				foreach ($patient_history_data as $value) 
				{
					if (strpos($value['married_life_type'], 'Select') !== false) 
	                {
	                    $value['married_life_type'] = "";
	                }
	                else
	                {
	                  $value['married_life_type'] = $value['married_life_type'];
	                }
	                if (strpos($value['previous_delivery'], 'Select') !== false) 
	                {
	                    $value['previous_delivery'] = "";
	                }
	                else
	                {
	                  $value['previous_delivery'] = $value['previous_delivery'];
	                }
	                if (strpos($value['delivery_type'], 'Select') !== false) 
	                {
	                    $value['delivery_type'] = "";
	                }
	                else
	                {
	                  $value['delivery_type'] = $value['delivery_type'];
	                }

					$patient_history_data = array(
						"branch_id"=>$user_data['parent_id'],
						"template_id"=>$data_id,
						'marriage_status'=>$value['marriage_status'],
						"married_life_unit"=>$value['married_life_unit'],
						'married_life_type'=>$value['married_life_type'],
						'marriage_no'=>$value['marriage_no'],
						'marriage_details'=>$value['marriage_details'],
						'previous_delivery'=>$value['previous_delivery'],
						'delivery_type'=>$value['delivery_type'],
						'delivery_details'=>$value['delivery_details']);
					
					$this->db->insert('hms_gynecology_prescription_patient_history_template',$patient_history_data); 
						
				} 
			}
		    $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('template_id',$post['data_id']);
			$this->db->delete('hms_gynecology_patient_family_history_template');
			$patient_family_history_data = $this->session->userdata('patient_family_history_data');	
		    if(!empty($patient_family_history_data))
			{
				foreach ($patient_family_history_data as $value) 
				{
					if (strpos($value['relation'], 'Select') !== false) 
	                {
	                    $value['relation'] = "";
	                }
	                else
	                {
	                  $value['relation'] = $value['relation'];
	                }
	                if (strpos($value['disease'], 'Select') !== false) 
	                {
	                    $value['disease'] = "";
	                }
	                else
	                {
	                  $value['disease'] = $value['disease'];
	                }
	                if (strpos($value['family_duration_type'], 'Select') !== false) 
	                {
	                    $value['family_duration_type'] = "";
	                }
	                else
	                {
	                  $value['family_duration_type'] = $value['family_duration_type'];
	                }

					$family_history_data = array(
						"template_id"=>$data_id,
						"branch_id"=>$user_data['parent_id'],
						'disease_id'=>$value['disease_id'],
						"disease"=>$value['disease'],
						'relation_id'=>$value['relation_id'],
						"relation"=>$value['relation'],
						'family_description'=>$value['family_description'],
						'family_duration_unit'=>$value['family_duration_unit'],
	                    'family_duration_type'=>$value['family_duration_type'],
	                    'status'=>1,
					  );
					
					$this->db->insert('hms_gynecology_patient_family_history_template',$family_history_data); 
				} 
			}
		    $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('template_id',$post['data_id']);
            $this->db->delete('hms_gynecology_patient_personal_history_template');
            $patient_personal_history_data = $this->session->userdata('patient_personal_history_data'); 
            if(!empty($patient_personal_history_data))
			{
				foreach ($patient_personal_history_data as $value) 
				{
					if (strpos($value['br_discharge'], 'Select') !== false) 
	                {
	                  $value['br_discharge'] = "";
	                }
	                else
	                {
	                  $value['br_discharge'] = $value['br_discharge'];
	                }
	                if (strpos($value['side'], 'Select') !== false) 
	                {
	                    $value['side'] = "";
	                }
	                else
	                {
	                  $value['side'] = $value['side'];
	                }
	                if (strpos($value['hirsutism'], 'Select') !== false) 
	                {
	                    $value['hirsutism'] = "";
	                }
	                else
	                {
	                  $value['hirsutism'] = $value['hirsutism'];
	                }
	                if (strpos($value['white_discharge'], 'Select') !== false) 
	                {
	                    $value['white_discharge'] = "";
	                }
	                else
	                {
	                  $value['white_discharge'] = $value['white_discharge'];
	                }
	                if (strpos($value['type'], 'Select') !== false) 
	                {
	                    $value['type'] = "";
	                }
	                else
	                {
	                  $value['type'] = $value['type'];
	                }
	                if (strpos($value['dyspareunia'], 'Select') !== false) 
	                {
	                    $value['dyspareunia'] = "";
	                }
	                else
	                {
	                  $value['dyspareunia'] = $value['dyspareunia'];
	                }
					$patient_personal_history_data = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'br_discharge'=>$value['br_discharge'],
						"side"=>$value['side'],
						'hirsutism'=>$value['hirsutism'],
						'white_discharge'=>$value['white_discharge'],
						'type'=>$value['type'],
						'frequency_personal'=>$value['frequency_personal'],
						'dyspareunia'=>$value['dyspareunia'],
						'personal_details'=>$value['personal_details'],
						'status'=>1,
                      );
                  	
                   	$this->db->insert('hms_gynecology_patient_personal_history_template',$patient_personal_history_data); 
				} 
			}
		    $this->db->where('branch_id',$user_data['parent_id']);
            $this->db->where('template_id',$post['data_id']);
            $this->db->delete('hms_gynecology_patient_menstrual_history_template');
             $patient_menstrual_history_data = $this->session->userdata('patient_menstrual_history_data'); 
            if(!empty($patient_menstrual_history_data))
			{
				foreach ($patient_menstrual_history_data as $value) 
				{
					if (strpos($value['previous_cycle'], 'Select') !== false) 
	                {
	                  $value['previous_cycle'] = "";
	                }
	                else
	                {
	                  $value['previous_cycle'] = $value['previous_cycle'];
	                }
	                if (strpos($value['prev_cycle_type'], 'Select') !== false) 
	                {
	                    $value['prev_cycle_type'] = "";
	                }
	                else
	                {
	                  $value['prev_cycle_type'] = $value['prev_cycle_type'];
	                }
	                if (strpos($value['present_cycle'], 'Select') !== false) 
	                {
	                    $value['present_cycle'] = "";
	                }
	                else
	                {
	                  $value['present_cycle'] = $value['present_cycle'];
	                }
	                if (strpos($value['present_cycle_type'], 'Select') !== false) 
	                {
	                    $value['present_cycle_type'] = "";
	                }
	                else
	                {
	                  $value['present_cycle_type'] = $value['present_cycle_type'];
	                }
	                if (strpos($value['dysmenorrhea'], 'Select') !== false) 
	                {
	                    $value['dysmenorrhea'] = "";
	                }
	                else
	                {
	                  $value['dysmenorrhea'] = $value['dysmenorrhea'];
	                }
	                if (strpos($value['dysmenorrhea_type'], 'Select') !== false) 
	                {
	                    $value['dysmenorrhea_type'] = "";
	                }
	                else
	                {
	                  $value['dysmenorrhea_type'] = $value['dysmenorrhea_type'];
	                }
					$patient_menstrual_history_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'previous_cycle'=>$value['previous_cycle'],
						"prev_cycle_type"=>$value['prev_cycle_type'],
						'present_cycle'=>$value['present_cycle'],
						'present_cycle_type'=>$value['present_cycle_type'],
						"cycle_details"=>$value['cycle_details'],
						'lmp_date'=>date('Y-m-d',strtotime($value['lmp_date'])),
						'dysmenorrhea'=>$value['dysmenorrhea'],
						"dysmenorrhea_type"=>$value['dysmenorrhea_type'],
						'status'=>1
					);
					
                    $this->db->insert('hms_gynecology_patient_menstrual_history_template',$patient_menstrual_history_data_all);
				} 
			}
			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->where('template_id',$post['data_id']);
			$this->db->delete('hms_gynecology_patient_medical_history_template');
           	$patient_medical_history_data = $this->session->userdata('patient_medical_history_data'); 
       
           	if(!empty($patient_medical_history_data))
			{
				foreach ($patient_medical_history_data as $value) 
				{
					if (strpos($value['tb'], 'Select') !== false) 
	                {
	                  $value['tb'] = "";
	                }
	                else
	                {
	                  $value['tb'] = $value['tb'];
	                }
	                if (strpos($value['dm'], 'Select') !== false) 
	                {
	                    $value['dm'] = "";
	                }
	                else
	                {
	                  $value['dm'] = $value['dm'];
	                }
	                if (strpos($value['ht'], 'Select') !== false) 
	                {
	                    $value['ht'] = "";
	                }
	                else
	                {
	                  $value['ht'] = $value['ht'];
	                }
					$patient_medical_history_data_all = array(
                      "template_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      'tb'=>$value['tb'],
                      "tb_rx"=>$value['tb_rx'],
                      'dm'=>$value['dm'],
                      'dm_years'=>$value['dm_years'],
                      'dm_rx'=>$value['dm_rx'],
                      'ht'=>$value['ht'],
                      'medical_details'=>$value['medical_details'],
                      'medical_others'=>$value['medical_others'],
                      'status'=>1
                      );
                 	
                   	$this->db->insert('hms_gynecology_patient_medical_history_template',$patient_medical_history_data_all); 
				} 
			}
			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->where('template_id',$post['data_id']);
			$this->db->delete('hms_gynecology_patient_obestetric_history_template');
          	$patient_obestetric_history_data = $this->session->userdata('patient_obestetric_history_data');
          	if(!empty($patient_obestetric_history_data))
			{
				foreach ($patient_obestetric_history_data as $value) 
				{
					$patient_obestetric_history_data = array(
                      "template_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      'obestetric_g'=>$value['obestetric_g'],
                      "obestetric_p"=>$value['obestetric_p'],
                      'obestetric_l'=>$value['obestetric_l'],
                      'obestetric_mtp'=>$value['obestetric_mtp'],
                      'status'=>1
                      
                      );
                  	$this->db->insert('hms_gynecology_patient_obestetric_history_template',$patient_obestetric_history_data);
				} 
			}
			$this->db->where('branch_id',$user_data['parent_id']);
			$this->db->where('template_id',$post['data_id']);
			$this->db->delete('hms_gynecology_patient_current_medication_history_template');
          	
          	if(isset($post['prescription_history']) && !empty($post['prescription_history']))
          	{
		        $new_array_pres=array_values($post['prescription_history']);
		        $total_prescription = count($new_array_pres);
		         
		        for($i=0;$i<=$total_prescription-1;$i++)
		        {
					if(!empty($new_array_pres[$i]['medicine_brand']))
					{
						$medicine_company[$i]=$new_array_pres[$i]['medicine_brand'];
					}
					else
					{
						$medicine_company[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_salt']))
					{
						$medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
					}
					else
					{
						$medicine_salt[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_type']))
					{
						$medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
					}
					else
					{
						$medicine_type[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_dose']))
					{
						$medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
					}
					else
					{
						$medicine_dose[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_duration']))
					{
						$medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
					}
					else
					{
						$medicine_duration[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_frequency']))
					{
						$medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
					}
					else
					{
						$medicine_frequency[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_advice']))
					{
						$medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
					}
					else
					{
						$medicine_advice[$i]='';
					}
		            
					$prescription_data = array(
					"template_id"=>$data_id,
					"branch_id"=>$user_data['parent_id'],
					"medicine_name"=>$new_array_pres[$i]['medicine_name'],
					"medicine_brand"=>$medicine_company[$i],
					"medicine_salt"=>$medicine_salt[$i],
					"medicine_type"=>$medicine_type[$i],
					"medicine_dose"=>$medicine_dose[$i],
					"medicine_duration"=>$medicine_duration[$i],
					"medicine_frequency"=>$medicine_frequency[$i],
					"medicine_advice"=>$medicine_advice[$i]
					);

					if(!empty($new_array_pres[$i]['medicine_name']))
					{
						$this->db->insert('hms_gynecology_patient_current_medication_history_template',$prescription_data); 
					}
		        }
		    }
		  	$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_patient_disease_template');
          	$patient_disease_data = $this->session->userdata('patient_disease_data');
         	if(!empty($patient_disease_data))
			{
				foreach ($patient_disease_data as $value) 
				{
					$patient_disease_id='';
					$disease_value='';
					$type='';
					if($value['patient_disease_id']=='')
					{
						$patient_disease_id='';
					}
					else
					{
						$patient_disease_id=$value['patient_disease_id'];
					}
					if($value['disease_value']=='Select Disease')
					{
						$disease_value='';
					}
					else
					{
						$disease_value=$value['disease_value'];
					}

					if($value['patient_disease_type']=='Select Duration Type')
					{
						$type='';
					}
					else
					{
						$type=$value['patient_disease_type'];
					}

                    $patient_disease_data_all = array(
					"template_id "=>$data_id,
					'branch_id'=>$user_data['parent_id'],
					'patient_disease_id'=>$patient_disease_id,
					'patient_disease_name'=>$disease_value,
					"patient_disease_unit"=>$value['patient_disease_unit'],
					"patient_disease_type"=>$type,
					'disease_description'=>$value['disease_description'],
					'status'=>1
                      
                    );
                   
                   	$this->db->insert('hms_gynecology_patient_disease_template',$patient_disease_data_all); 
				} 
			}
	      	$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_complaint_template');
          	$patient_complaint_data = $this->session->userdata('patient_complaint_data');
	        if(!empty($patient_complaint_data))
			{
				foreach ($patient_complaint_data as $value) 
				{
					$patient_complaint_id='';
					$complaint_value='';
					$type='';
					if($value['patient_complaint_id']=='')
					{
						$patient_complaint_id='';
					}
					else
					{
						$patient_complaint_id=$value['patient_complaint_id'];
					}
					if($value['complaint_value']=='Select Complaint')
					{
						$complaint_value='';
					}
					else
					{
						$complaint_value=$value['complaint_value'];
					}
					if($value['patient_complaint_type']=='Select Duration Type')
					{
						$type='';
					}
					else
					{
						$type=$value['patient_complaint_type'];
					}
					$patient_complaint_data_all = array(
					"template_id "=>$data_id,
					'branch_id'=>$user_data['parent_id'],
					'patient_complaint_id'=>$patient_complaint_id,
					'patient_complaint_name'=>$complaint_value,
					'patient_complaint_type'=>$type,
					"patient_complaint_unit"=>$value['patient_complaint_unit'],
					'complaint_description'=>$value['complaint_description'],
					'status'=>1

					);
                  	$this->db->insert('hms_gynecology_complaint_template',$patient_complaint_data_all); 
				} 
			}

			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_allergy_template');
          	$patient_allergy_data = $this->session->userdata('patient_allergy_data');
	        if(!empty($patient_allergy_data))
			{
				foreach ($patient_allergy_data as $value) 
				{
					$type='';
					$patient_allergy_id='';
					$allergy_value='';
					if($value['patient_allergy_type']=='Select Duration Type')
					{
						$type='';
					}
					else
					{
						$type=$value['patient_allergy_type'];
					}

					if($value['allergy_value']=='Select Allergy')
					{
						$allergy_value='';
					}
					else
					{
						$allergy_value=$value['allergy_value'];
					}

					if($value['patient_allergy_id']=='')
					{
						$patient_allergy_id='';
					}
					else
					{
						$patient_allergy_id=$value['patient_allergy_id'];
					}
                    $patient_allergy_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_allergy_id'=>$patient_allergy_id,
						'patient_allergy_name'=>$allergy_value,
						'patient_allergy_type'=>$type,
						"patient_allergy_unit"=>$value['patient_allergy_unit'],
						'allergy_description'=>$value['allergy_description'],
						'status'=>1
                      
                      );
                   	
                   	$this->db->insert('hms_gynecology_allergy_template',$patient_allergy_data_all);
				} 
			}

			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_general_examination_template');
          	$patient_general_examination_data = $this->session->userdata('patient_general_examination_data');
	        if(!empty($patient_general_examination_data))
			{
				foreach($patient_general_examination_data as $value) 
				{
					$type='';
                  $patient_general_examination_id='';
                  $general_examination_value='';


                  

                  if($value['general_examination_value']=='Select Exam')
                  {
                    $general_examination_value='';
                  }
                  else
                  {
                    $general_examination_value=$value['general_examination_value'];
                  }

                  if($value['patient_general_examination_id']=='')
                  {
                    $patient_general_examination_id='';
                  }
                  else
                  {
                    $patient_general_examination_id=$value['patient_general_examination_id'];
                  }



                    $patient_general_examination_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_general_examination_id'=>$patient_general_examination_id,
						'patient_general_examination_name'=>$general_examination_value,
						
						'general_examination_description'=>$value['general_examination_description'],
						'status'=>1
                      
                      );
                    
                  	$this->db->insert('hms_gynecology_general_examination_template',$patient_general_examination_data_all);
				} 
			}

			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_clinical_examination_template');
          	$patient_clinical_examination_data = $this->session->userdata('patient_clinical_examination_data');
	        if(!empty($patient_clinical_examination_data))
			{
				foreach ($patient_clinical_examination_data as $value) 
				{
					$type='';
					$patient_clinical_examination_id='';
					$patient_clinical_examination_type='';
					$clinical_examination_value='';
					

					if($value['clinical_examination_value']=='Select Exam')
					{
						$clinical_examination_value='';
					}
					else
					{
						$clinical_examination_value=$value['clinical_examination_value'];
					}

					if($value['patient_clinical_examination_id']=='')
					{
						$patient_clinical_examination_id='';
					}
					else
					{
						$patient_clinical_examination_id=$value['patient_clinical_examination_id'];
					}
					$patient_clinical_examination_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_clinical_examination_id'=>$patient_clinical_examination_id,
						'patient_clinical_examination_name'=>$clinical_examination_value,
						
						'clinical_examination_description'=>$value['clinical_examination_description'],
						'status'=>1
					);
					
					$this->db->insert('hms_gynecology_clinical_examination_template',$patient_clinical_examination_data_all); 
				} 
			}

			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_investigation_template');
          	$patient_investigation_data = $this->session->userdata('patient_investigation_data');
	        if(!empty($patient_investigation_data))
			{
				foreach ($patient_investigation_data as $value) 
				{
					$patient_investigation_id='';
					$investigation_value='';
					if($value['investigation_value']=='Select Investigation')
					{
						$investigation_value='';
					}
					else
					{
						$investigation_value=$value['investigation_value'];
					}

					if($value['patient_investigation_id']=='')
					{
						$patient_investigation_id='';
					}
					else
					{
						$patient_investigation_id=$value['patient_investigation_id'];
					}
					$patient_investigation_data_all = array(
					"template_id "=>$data_id,
					'branch_id'=>$user_data['parent_id'],
					'patient_investigation_id'=>$patient_investigation_id,
					'patient_investigation_name'=>$investigation_value,
					'std_value'=>$value['std_value'],
					'observed_value'=>$value['observed_value'],
					'status'=>1

					);
					$this->db->insert('hms_gynecology_investigation_template',$patient_investigation_data_all); 
				} 
			}
			
			
			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_advice_template');
			if(!empty($patient_advice_data))
            {
                foreach ($patient_advice_data as $value) 
                {

                  	$patient_advice_id='';
                  	$advice_value='';
					if($value['advice_value']=='Select Advice')
					{
						$advice_value='';
					}
					else
					{
						$advice_value=$value['advice_value'];
					}

					if($value['patient_advice_id']=='')
					{
						$patient_advice_id='';
					}
					else
					{
						$patient_advice_id=$value['patient_advice_id'];
					}

					$patient_advice_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_advice_id'=>$patient_advice_id,
						'patient_advice_name'=>$advice_value,
						'status'=>1
						);
					
					$this->db->insert('hms_gynecology_advice_template',$patient_advice_data_all);  
                } 
            }



			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_gpla_template');
			if(!empty($patient_gpla_data))
            {
                foreach ($patient_gpla_data as $value) 
                {

					$patient_gpla_data_all = array(
						"template_id"=>$post['data_id'],
						'branch_id'=>$user_data['parent_id'],
						'dog_value'=>$value['dog_value'],
						'mode_value'=>$value['mode_value'],
						'monthyear_value'=>$value['monthyear_value'],
						'status'=>1
						
						);
					
					  
				  $this->db->insert('hms_gynecology_gpla_template',$patient_gpla_data_all); 
				 
                } 
            }
            
			
			$this->db->where('branch_id',$user_data['parent_id']);
          	$this->db->where('template_id',$post['data_id']);
          	$this->db->delete('hms_gynecology_template_medicine_booking');
	        if(isset($post['prescription']) && !empty($post['prescription']))
			{
				$new_array_pres=array_values($post['prescription']);
				$total_prescription = count($new_array_pres);
				for($i=0;$i<=$total_prescription-1;$i++)
				{
				   	if(!empty($new_array_pres[$i]['medicine_brand']))
				   	{
				   		$medicine_company[$i]=$new_array_pres[$i]['medicine_brand'];
				   	}
				   	else
				   	{
				   		$medicine_company[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_salt']))
				   	{
				   		$medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
				   	}
				   	else
				   	{
				   		$medicine_salt[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_type']))
				   	{
				   		$medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
				   	}
				   	else
				   	{
				   		$medicine_type[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_dose']))
				   	{
				   		$medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
				   	}
				   	else
				   	{
				   		$medicine_dose[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_duration']))
				   	{
				   		$medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
				   	}
				   	else
				   	{
				   		$medicine_duration[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_frequency']))
				   	{
				   		$medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
				   	}
				   	else
				   	{
				   		$medicine_frequency[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_advice']))
				   	{
				   		$medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
				   	}
				   	else
				   	{
				   		$medicine_advice[$i]='';
				   	}

				   	$prescription_data = array(
						"template_id"=>$data_id,
						"branch_id"=>$user_data['parent_id'],
						"medicine_name"=>$new_array_pres[$i]['medicine_name'],
						"medicine_brand"=>$medicine_company[$i],
						"medicine_salt"=>$medicine_salt[$i],
						"medicine_type"=>$medicine_type[$i],
						"medicine_dose"=>$medicine_dose[$i],
						"medicine_duration"=>$medicine_duration[$i],
						"medicine_frequency"=>$medicine_frequency[$i],
						"medicine_advice"=>$medicine_advice[$i]
					);
					if(!empty($new_array_pres[$i]['medicine_name']))
					{
						$this->db->insert('hms_gynecology_template_medicine_booking',$prescription_data);	
					}
					
				}
			}

			//end of if
		}
		else
		{    
           	$post=$this->input->post();
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_gynecology_patient_template',$data);
			$data_id = $this->db->insert_id(); 
			if(!empty($patient_history_data))
			{
				foreach ($patient_history_data as $value) 
				{
					if (strpos($value['married_life_type'], 'Select') !== false) 
	                {
	                    $value['married_life_type'] = "";
	                }
	                else
	                {
	                  $value['married_life_type'] = $value['married_life_type'];
	                }
	                if (strpos($value['previous_delivery'], 'Select') !== false) 
	                {
	                    $value['previous_delivery'] = "";
	                }
	                else
	                {
	                  $value['previous_delivery'] = $value['previous_delivery'];
	                }
	                if (strpos($value['delivery_type'], 'Select') !== false) 
	                {
	                    $value['delivery_type'] = "";
	                }
	                else
	                {
	                  $value['delivery_type'] = $value['delivery_type'];
	                }

					$patient_history_data = array(
						"branch_id"=>$user_data['parent_id'],
						"template_id"=>$data_id,
						'marriage_status'=>$value['marriage_status'],
						"married_life_unit"=>$value['married_life_unit'],
						'married_life_type'=>$value['married_life_type'],
						'marriage_no'=>$value['marriage_no'],
						'marriage_details'=>$value['marriage_details'],
						'previous_delivery'=>$value['previous_delivery'],
						'delivery_type'=>$value['delivery_type'],
						'delivery_details'=>$value['delivery_details']);
					
					$this->db->insert('hms_gynecology_prescription_patient_history_template',$patient_history_data); 
						
				} 
			}
			if(!empty($patient_family_history_data))
			{
				foreach ($patient_family_history_data as $value) 
				{
					if (strpos($value['relation'], 'Select') !== false) 
	                {
	                    $value['relation'] = "";
	                }
	                else
	                {
	                  $value['relation'] = $value['relation'];
	                }
	                if (strpos($value['disease'], 'Select') !== false) 
	                {
	                    $value['disease'] = "";
	                }
	                else
	                {
	                  $value['disease'] = $value['disease'];
	                }
	                if (strpos($value['family_duration_type'], 'Select') !== false) 
	                {
	                    $value['family_duration_type'] = "";
	                }
	                else
	                {
	                  $value['family_duration_type'] = $value['family_duration_type'];
	                }

					$family_history_data = array(
						"template_id"=>$data_id,
						"branch_id"=>$user_data['parent_id'],
						'disease_id'=>$value['disease_id'],
						"disease"=>$value['disease'],
						'relation_id'=>$value['relation_id'],
						"relation"=>$value['relation'],
						'family_description'=>$value['family_description'],
						'family_duration_unit'=>$value['family_duration_unit'],
	                    'family_duration_type'=>$value['family_duration_type'],
	                    'status'=>1,
					  );
					
					$this->db->insert('hms_gynecology_patient_family_history_template',$family_history_data); 
				} 
			}
			if(!empty($patient_personal_history_data))
			{
				foreach ($patient_personal_history_data as $value) 
				{
					if (strpos($value['br_discharge'], 'Select') !== false) 
	                {
	                  $value['br_discharge'] = "";
	                }
	                else
	                {
	                  $value['br_discharge'] = $value['br_discharge'];
	                }
	                if (strpos($value['side'], 'Select') !== false) 
	                {
	                    $value['side'] = "";
	                }
	                else
	                {
	                  $value['side'] = $value['side'];
	                }
	                if (strpos($value['hirsutism'], 'Select') !== false) 
	                {
	                    $value['hirsutism'] = "";
	                }
	                else
	                {
	                  $value['hirsutism'] = $value['hirsutism'];
	                }
	                if (strpos($value['white_discharge'], 'Select') !== false) 
	                {
	                    $value['white_discharge'] = "";
	                }
	                else
	                {
	                  $value['white_discharge'] = $value['white_discharge'];
	                }
	                if (strpos($value['type'], 'Select') !== false) 
	                {
	                    $value['type'] = "";
	                }
	                else
	                {
	                  $value['type'] = $value['type'];
	                }
	                if (strpos($value['dyspareunia'], 'Select') !== false) 
	                {
	                    $value['dyspareunia'] = "";
	                }
	                else
	                {
	                  $value['dyspareunia'] = $value['dyspareunia'];
	                }
					$patient_personal_history_data = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'br_discharge'=>$value['br_discharge'],
						"side"=>$value['side'],
						'hirsutism'=>$value['hirsutism'],
						'white_discharge'=>$value['white_discharge'],
						'type'=>$value['type'],
						'frequency_personal'=>$value['frequency_personal'],
						'dyspareunia'=>$value['dyspareunia'],
						'personal_details'=>$value['personal_details'],
						'status'=>1,
                      );
                  	
                   	$this->db->insert('hms_gynecology_patient_personal_history_template',$patient_personal_history_data); 
				} 
			}
			if(!empty($patient_menstrual_history_data))
			{
				foreach ($patient_menstrual_history_data as $value) 
				{
					if (strpos($value['previous_cycle'], 'Select') !== false) 
	                {
	                  $value['previous_cycle'] = "";
	                }
	                else
	                {
	                  $value['previous_cycle'] = $value['previous_cycle'];
	                }
	                if (strpos($value['prev_cycle_type'], 'Select') !== false) 
	                {
	                    $value['prev_cycle_type'] = "";
	                }
	                else
	                {
	                  $value['prev_cycle_type'] = $value['prev_cycle_type'];
	                }
	                if (strpos($value['present_cycle'], 'Select') !== false) 
	                {
	                    $value['present_cycle'] = "";
	                }
	                else
	                {
	                  $value['present_cycle'] = $value['present_cycle'];
	                }
	                if (strpos($value['present_cycle_type'], 'Select') !== false) 
	                {
	                    $value['present_cycle_type'] = "";
	                }
	                else
	                {
	                  $value['present_cycle_type'] = $value['present_cycle_type'];
	                }
	                if (strpos($value['dysmenorrhea'], 'Select') !== false) 
	                {
	                    $value['dysmenorrhea'] = "";
	                }
	                else
	                {
	                  $value['dysmenorrhea'] = $value['dysmenorrhea'];
	                }
	                if (strpos($value['dysmenorrhea_type'], 'Select') !== false) 
	                {
	                    $value['dysmenorrhea_type'] = "";
	                }
	                else
	                {
	                  $value['dysmenorrhea_type'] = $value['dysmenorrhea_type'];
	                }
					$patient_menstrual_history_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'previous_cycle'=>$value['previous_cycle'],
						"prev_cycle_type"=>$value['prev_cycle_type'],
						'present_cycle'=>$value['present_cycle'],
						'present_cycle_type'=>$value['present_cycle_type'],
						"cycle_details"=>$value['cycle_details'],
						'lmp_date'=>date('Y-m-d',strtotime($value['lmp_date'])),
						'dysmenorrhea'=>$value['dysmenorrhea'],
						"dysmenorrhea_type"=>$value['dysmenorrhea_type'],
						'status'=>1
					);
					
                    $this->db->insert('hms_gynecology_patient_menstrual_history_template',$patient_menstrual_history_data_all);
				} 
			}
			if(!empty($patient_medical_history_data))
			{
				foreach ($patient_medical_history_data as $value) 
				{
					if (strpos($value['tb'], 'Select') !== false) 
	                {
	                  $value['tb'] = "";
	                }
	                else
	                {
	                  $value['tb'] = $value['tb'];
	                }
	                if (strpos($value['dm'], 'Select') !== false) 
	                {
	                    $value['dm'] = "";
	                }
	                else
	                {
	                  $value['dm'] = $value['dm'];
	                }
	                if (strpos($value['ht'], 'Select') !== false) 
	                {
	                    $value['ht'] = "";
	                }
	                else
	                {
	                  $value['ht'] = $value['ht'];
	                }
					$patient_medical_history_data_all = array(
                      "template_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      'tb'=>$value['tb'],
                      "tb_rx"=>$value['tb_rx'],
                      'dm'=>$value['dm'],
                      'dm_years'=>$value['dm_years'],
                      'dm_rx'=>$value['dm_rx'],
                      'ht'=>$value['ht'],
                      'medical_details'=>$value['medical_details'],
                      'medical_others'=>$value['medical_others'],
                      'status'=>1
                      );
                 	
                   	$this->db->insert('hms_gynecology_patient_medical_history_template',$patient_medical_history_data_all); 
				} 
			}
	    	if(!empty($patient_obestetric_history_data))
			{
				foreach ($patient_obestetric_history_data as $value) 
				{
					$patient_obestetric_history_data = array(
                      "template_id "=>$data_id,
                      'branch_id'=>$user_data['parent_id'],
                      'obestetric_g'=>$value['obestetric_g'],
                      "obestetric_p"=>$value['obestetric_p'],
                      'obestetric_l'=>$value['obestetric_l'],
                      'obestetric_mtp'=>$value['obestetric_mtp'],
                      'status'=>1
                      
                      );
                  	$this->db->insert('hms_gynecology_patient_obestetric_history_template',$patient_obestetric_history_data);
				} 
			}
			//print_r($post['prescription_history']);die;
			if(isset($post['prescription_history']) && !empty($post['prescription_history']))
          	{
		        $new_array_pres=array_values($post['prescription_history']);
		        $total_prescription = count($new_array_pres);
		         
		        for($i=0;$i<=$total_prescription-1;$i++)
		        {
					if(!empty($new_array_pres[$i]['medicine_brand']))
					{
						$medicine_company[$i]=$new_array_pres[$i]['medicine_brand'];
					}
					else
					{
						$medicine_company[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_salt']))
					{
						$medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
					}
					else
					{
						$medicine_salt[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_type']))
					{
						$medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
					}
					else
					{
						$medicine_type[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_dose']))
					{
						$medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
					}
					else
					{
						$medicine_dose[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_duration']))
					{
						$medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
					}
					else
					{
						$medicine_duration[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_frequency']))
					{
						$medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
					}
					else
					{
						$medicine_frequency[$i]='';
					}
					if(isset($new_array_pres[$i]['medicine_advice']))
					{
						$medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
					}
					else
					{
						$medicine_advice[$i]='';
					}
		            
					$prescription_data = array(
					"template_id"=>$data_id,
					"branch_id"=>$user_data['parent_id'],
					"medicine_name"=>$new_array_pres[$i]['medicine_name'],
					"medicine_brand"=>$medicine_company[$i],
					"medicine_salt"=>$medicine_salt[$i],
					"medicine_type"=>$medicine_type[$i],
					"medicine_dose"=>$medicine_dose[$i],
					"medicine_duration"=>$medicine_duration[$i],
					"medicine_frequency"=>$medicine_frequency[$i],
					"medicine_advice"=>$medicine_advice[$i]
					);

					if(!empty($new_array_pres[$i]['medicine_name']))
					{
						$this->db->insert('hms_gynecology_patient_current_medication_history_template',$prescription_data); 
					}
		        }
		    }
		    
			if(!empty($patient_disease_data))
			{
				foreach ($patient_disease_data as $value) 
				{
					$patient_disease_id='';
					$disease_value='';
					$type='';
					if($value['patient_disease_id']=='')
					{
						$patient_disease_id='';
					}
					else
					{
						$patient_disease_id=$value['patient_disease_id'];
					}
					if($value['disease_value']=='Select Disease')
					{
						$disease_value='';
					}
					else
					{
						$disease_value=$value['disease_value'];
					}

					if($value['patient_disease_type']=='Select Duration Type')
					{
						$type='';
					}
					else
					{
						$type=$value['patient_disease_type'];
					}

                    $patient_disease_data_all = array(
					"template_id "=>$data_id,
					'branch_id'=>$user_data['parent_id'],
					'patient_disease_id'=>$patient_disease_id,
					'patient_disease_name'=>$disease_value,
					"patient_disease_unit"=>$value['patient_disease_unit'],
					"patient_disease_type"=>$type,
					'disease_description'=>$value['disease_description'],
					'status'=>1
                      
                    );
                   
                   	$this->db->insert('hms_gynecology_patient_disease_template',$patient_disease_data_all); 
				} 
			}

			if(!empty($patient_complaint_data))
			{
				foreach ($patient_complaint_data as $value) 
				{
					$patient_complaint_id='';
					$complaint_value='';
					$type='';
					if($value['patient_complaint_id']=='')
					{
						$patient_complaint_id='';
					}
					else
					{
						$patient_complaint_id=$value['patient_complaint_id'];
					}
					if($value['complaint_value']=='Select Complaint')
					{
						$complaint_value='';
					}
					else
					{
						$complaint_value=$value['complaint_value'];
					}
					if($value['patient_complaint_type']=='Select Duration Type')
					{
						$type='';
					}
					else
					{
						$type=$value['patient_complaint_type'];
					}
					$patient_complaint_data_all = array(
					"template_id "=>$data_id,
					'branch_id'=>$user_data['parent_id'],
					'patient_complaint_id'=>$patient_complaint_id,
					'patient_complaint_name'=>$complaint_value,
					'patient_complaint_type'=>$type,
					"patient_complaint_unit"=>$value['patient_complaint_unit'],
					'complaint_description'=>$value['complaint_description'],
					'status'=>1

					);
                  	$this->db->insert('hms_gynecology_complaint_template',$patient_complaint_data_all); 
				} 
			}

			if(!empty($patient_allergy_data))
			{
				foreach ($patient_allergy_data as $value) 
				{
					$type='';
					$patient_allergy_id='';
					$allergy_value='';
					if($value['patient_allergy_type']=='Select Duration Type')
					{
						$type='';
					}
					else
					{
						$type=$value['patient_allergy_type'];
					}

					if($value['allergy_value']=='Select Allergy')
					{
						$allergy_value='';
					}
					else
					{
						$allergy_value=$value['allergy_value'];
					}

					if($value['patient_allergy_id']=='')
					{
						$patient_allergy_id='';
					}
					else
					{
						$patient_allergy_id=$value['patient_allergy_id'];
					}
                    $patient_allergy_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_allergy_id'=>$patient_allergy_id,
						'patient_allergy_name'=>$allergy_value,
						'patient_allergy_type'=>$type,
						"patient_allergy_unit"=>$value['patient_allergy_unit'],
						'allergy_description'=>$value['allergy_description'],
						'status'=>1
                      
                      );
                   	
                   	$this->db->insert('hms_gynecology_allergy_template',$patient_allergy_data_all);
				} 
			}

			if(!empty($patient_general_examination_data))
			{
				foreach($patient_general_examination_data as $value) 
				{
					$type='';
                  $patient_general_examination_id='';
                  $general_examination_value='';


                 
                  if($value['general_examination_value']=='Select Exam')
                  {
                    $general_examination_value='';
                  }
                  else
                  {
                    $general_examination_value=$value['general_examination_value'];
                  }

                  if($value['patient_general_examination_id']=='')
                  {
                    $patient_general_examination_id='';
                  }
                  else
                  {
                    $patient_general_examination_id=$value['patient_general_examination_id'];
                  }



                    $patient_general_examination_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_general_examination_id'=>$patient_general_examination_id,
						'patient_general_examination_name'=>$general_examination_value,
						
						'general_examination_description'=>$value['general_examination_description'],
						'status'=>1
                      
                      );
                    
                  	$this->db->insert('hms_gynecology_general_examination_template',$patient_general_examination_data_all);
				} 
			}

			if(!empty($patient_clinical_examination_data))
			{
				foreach ($patient_clinical_examination_data as $value) 
				{
					$type='';
					$patient_clinical_examination_id='';
					$patient_clinical_examination_type='';
					$clinical_examination_value='';
					

					if($value['clinical_examination_value']=='Select Exam')
					{
						$clinical_examination_value='';
					}
					else
					{
						$clinical_examination_value=$value['clinical_examination_value'];
					}

					if($value['patient_clinical_examination_id']=='')
					{
						$patient_clinical_examination_id='';
					}
					else
					{
						$patient_clinical_examination_id=$value['patient_clinical_examination_id'];
					}
					$patient_clinical_examination_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_clinical_examination_id'=>$patient_clinical_examination_id,
						'patient_clinical_examination_name'=>$clinical_examination_value,
						
						'clinical_examination_description'=>$value['clinical_examination_description'],
						'status'=>1
					);
					
					$this->db->insert('hms_gynecology_clinical_examination_template',$patient_clinical_examination_data_all); 
				} 
			}

			if(!empty($patient_investigation_data))
			{
				foreach ($patient_investigation_data as $value) 
				{
					$patient_investigation_id='';
					$investigation_value='';
					if($value['investigation_value']=='Select Investigation')
					{
						$investigation_value='';
					}
					else
					{
						$investigation_value=$value['investigation_value'];
					}

					if($value['patient_investigation_id']=='')
					{
						$patient_investigation_id='';
					}
					else
					{
						$patient_investigation_id=$value['patient_investigation_id'];
					}
					$patient_investigation_data_all = array(
					"template_id "=>$data_id,
					'branch_id'=>$user_data['parent_id'],
					'patient_investigation_id'=>$patient_investigation_id,
					'patient_investigation_name'=>$investigation_value,
					'std_value'=>$value['std_value'],
					'observed_value'=>$value['observed_value'],
					'status'=>1

					);
					$this->db->insert('hms_gynecology_investigation_template',$patient_investigation_data_all); 
				} 
			}
			if(!empty($patient_advice_data))
            {
                foreach ($patient_advice_data as $value) 
                {

                  	$patient_advice_id='';
                  	$advice_value='';
					if($value['advice_value']=='Select Advice')
					{
						$advice_value='';
					}
					else
					{
						$advice_value=$value['advice_value'];
					}

					if($value['patient_advice_id']=='')
					{
						$patient_advice_id='';
					}
					else
					{
						$patient_advice_id=$value['patient_advice_id'];
					}

					$patient_advice_data_all = array(
						"template_id "=>$data_id,
						'branch_id'=>$user_data['parent_id'],
						'patient_advice_id'=>$patient_advice_id,
						'patient_advice_name'=>$advice_value,
						'status'=>1
						);
					
					$this->db->insert('hms_gynecology_advice_template',$patient_advice_data_all);  
                } 
            }

			if(isset($post['prescription']) && !empty($post['prescription']))
			{
				$new_array_pres=array_values($post['prescription']);
				$total_prescription = count($new_array_pres);
				for($i=0;$i<=$total_prescription-1;$i++)
				{
				   	if(!empty($new_array_pres[$i]['medicine_brand']))
				   	{
				   		$medicine_company[$i]=$new_array_pres[$i]['medicine_brand'];
				   	}
				   	else
				   	{
				   		$medicine_company[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_salt']))
				   	{
				   		$medicine_salt[$i]=$new_array_pres[$i]['medicine_salt'];
				   	}
				   	else
				   	{
				   		$medicine_salt[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_type']))
				   	{
				   		$medicine_type[$i]=$new_array_pres[$i]['medicine_type'];
				   	}
				   	else
				   	{
				   		$medicine_type[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_dose']))
				   	{
				   		$medicine_dose[$i]=$new_array_pres[$i]['medicine_dose'];
				   	}
				   	else
				   	{
				   		$medicine_dose[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_duration']))
				   	{
				   		$medicine_duration[$i]=$new_array_pres[$i]['medicine_duration'];
				   	}
				   	else
				   	{
				   		$medicine_duration[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_frequency']))
				   	{
				   		$medicine_frequency[$i]=$new_array_pres[$i]['medicine_frequency'];
				   	}
				   	else
				   	{
				   		$medicine_frequency[$i]='';
				   	}
				   	if(isset($new_array_pres[$i]['medicine_advice']))
				   	{
				   		$medicine_advice[$i]=$new_array_pres[$i]['medicine_advice'];
				   	}
				   	else
				   	{
				   		$medicine_advice[$i]='';
				   	}

				   	$prescription_data = array(
						"template_id"=>$data_id,
						"branch_id"=>$user_data['parent_id'],
						"medicine_name"=>$new_array_pres[$i]['medicine_name'],
						"medicine_brand"=>$medicine_company[$i],
						"medicine_salt"=>$medicine_salt[$i],
						"medicine_type"=>$medicine_type[$i],
						"medicine_dose"=>$medicine_dose[$i],
						"medicine_duration"=>$medicine_duration[$i],
						"medicine_frequency"=>$medicine_frequency[$i],
						"medicine_advice"=>$medicine_advice[$i]
					);
					if(!empty($new_array_pres[$i]['medicine_name']))
					{
						$this->db->insert('hms_gynecology_template_medicine_booking',$prescription_data);	
					}
					
				}
			}
		} 	
	}


	function get_eye_prescription_template($template_id='')
	{
		$this->db->select("hms_eye_prescription_patient_pres_template.*"); 
		$this->db->from('hms_eye_prescription_patient_pres_template'); 
		$this->db->where('hms_eye_prescription_patient_pres_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_eye_test_template($template_id='')
	{
		$this->db->select("hms_eye_prescription_patient_test_template.*"); 
		$this->db->from('hms_eye_prescription_patient_test_template'); 
		$this->db->where('hms_eye_prescription_patient_test_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
        return $result;
	}
	function get_eye_cheif_template($template_id='')
	{
		$this->db->select("hms_eye_cheif_complain_template_vals.*"); 
		$this->db->from('hms_eye_cheif_complain_template_vals'); 
		$this->db->where('hms_eye_cheif_complain_template_vals.eye_prescription_template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
        return $result;
	}
	function get_eye_diagnosis_template($template_id='')
	{
		$this->db->select("hms_eye_diagnosis_template_vals.*"); 
		$this->db->from('hms_eye_diagnosis_template_vals'); 
		$this->db->where('hms_eye_diagnosis_template_vals.eye_prescription_template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
		return $result;
	}

	function get_eye_systemic_illness_template($template_id='')
	{
		$this->db->select("hms_eye_systemic_illness_template_vals.*"); 
		$this->db->from('hms_eye_systemic_illness_template_vals'); 
		$this->db->where('hms_eye_systemic_illness_template_vals.eye_prescription_template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
		//print_r($result);die;
		return $result;
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
			$this->db->update('hms_gynecology_patient_template');
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
			$this->db->update('hms_gynecology_patient_template');
    	} 
    }

    public function delete_pres_medicine($medicine_presc_id='',$template_id='')
    {
    	

    	if(!empty($medicine_presc_id) && $medicine_presc_id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->where('id',$medicine_presc_id);
			$this->db->delete('hms_eye_prescription_patient_pres_template'); 
			
			//echo $this->db->last_query();die;
    	}

    	$this->db->select("*"); 
		$this->db->from('hms_eye_prescription_patient_pres_template'); 
		$this->db->where('template_id',$template_id);
		$query = $this->db->get(); 

		$result = $query->result(); 
		$temp =""; 
		if(!empty($result))
		{
		foreach ($result as $prescriptiontemplate) {
			$temp .= '<tr><td><input type="text" name="medicine_name[]" value="'.$prescriptiontemplate->medicine_name.'" class="medicine-name"></td><td><input type="text" name="medicine_type[]" value="'.$prescriptiontemplate->medicine_type.'" class="medicine-name"></td><td><input type="text" name="medicine_dose[]" class="medicine-name" value="'.$prescriptiontemplate->medicine_dose.'"></td><td><input type="text" name="medicine_duration[]" class="medicine-name" value="'.$prescriptiontemplate->medicine_duration.'"></td><td><input type="text" name="medicine_frequency[]" value="'.$prescriptiontemplate->medicine_frequency.'" class="medicine-name"></td><td><input type="text" name="medicine_advice[]" class="medicine-name" value"'.$prescriptiontemplate->medicine_advice.'"></td><td width="80"><a href="javascript:void(0);" onclick="delete_prescription_medicine('.$prescriptiontemplate->id.');" class="btn-w-60 remove_prescription_row">Delete</a></td></tr>';
		}
		}
		else
		{
			$temp .= '<tr><td colspan="7">Prescription Medicine Not Available.</td></tr>';
		}

		return $temp;

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
        $booking_code = generate_unique_id(9);
        $data_patient = array(    
									'simulation_id'=>$post['simulation_id'],
									'patient_name'=>$post['patient_name'],
									'mobile_no'=>$post['mobile_no'],
									'gender'=>$post['gender'],
									'age_y'=>$post['age_y'],
									'age_m'=>$post['age_m'],
									'age_d'=>$post['age_d'], 
									'address'=>$post['address'],
									'city_id'=>$post['city_id'],
									'state_id'=>$post['state_id'],
									'country_id'=>$post['country_id'],
									'status'=>1,
									'ip_address'=>$_SERVER['REMOTE_ADDR']
					            ); 
		$data_test = array(    
						'booking_code'=>$booking_code, 
						'dept_id'=>$post['dept_id'], 
						'referral_doctor'=>$post['referral_doctor'],
						'attended_doctor'=>$post['attended_doctor'],
						'sample_collected_by'=>$post['sample_collected_by'],
						'staff_refrenace_id'=>$post['staff_refrenace_id'],
						'booking_date'=>date('Y-m-d',strtotime($post['booking_date']))
						
		         );
         
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			$booking_id = $post['data_id'];
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data_test);  

			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['patient_id']);
			$this->db->update('hms_patient',$data_patient); 
            
           

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
		    	$this->db->insert('hms_patient',$data_patient);  
		    	$patient_id = $this->db->insert_id(); 
		    }
		    $this->db->set('patient_id',$patient_id);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_booking',$data_test);    
            $booking_id = $this->db->insert_id(); 
			 
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
					'booking_status'=>1
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_opd_booking',$data);  
		}
			
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
			$this->db->select('hms_eye_chief_complaints.chief_complaints');  
			$this->db->where('hms_eye_chief_complaints.id',$value);  
			$this->db->where('hms_eye_chief_complaints.is_deleted',0);  
			$this->db->where('hms_eye_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_chief_complaints.id');  
			$query = $this->db->get('hms_eye_chief_complaints');
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
			$this->db->select('hms_eye_examination.examination');  
			$this->db->where('hms_eye_examination.id',$value);  
			$this->db->where('hms_eye_examination.is_deleted',0);  
			$this->db->where('hms_eye_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_examination.id');  
			$query = $this->db->get('hms_eye_examination');
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
        $query = $this->db->get('hms_eye_examination');
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
			$this->db->select('hms_eye_diagnosis.diagnosis');  
			$this->db->where('hms_eye_diagnosis.id',$value);  
			$this->db->where('hms_eye_diagnosis.is_deleted',0);  
			$this->db->where('hms_eye_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_diagnosis.id');  
			$query = $this->db->get('hms_eye_diagnosis');
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
        $query = $this->db->get('hms_eye_diagnosis');
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
        $query = $this->db->get('hms_eye_suggetion');
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
			$this->db->select('hms_eye_suggetion.medicine_suggetion');  
			$this->db->where('hms_eye_suggetion.id',$value);  
			$this->db->where('hms_eye_suggetion.is_deleted',0);  
			$this->db->where('hms_eye_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_suggetion.id');  
			$query = $this->db->get('hms_eye_suggetion');
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
        $query = $this->db->get('hms_eye_prv_history');
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
			$this->db->select('hms_eye_prv_history.prv_history');  
			$this->db->where('hms_eye_prv_history.id',$value);  
			$this->db->where('hms_eye_prv_history.is_deleted',0);  
			$this->db->where('hms_eye_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_prv_history.id');  
			$query = $this->db->get('hms_eye_prv_history');
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
        $query = $this->db->get('hms_eye_personal_history');
        $result = $query->result(); 
        return $result; 
    }
    //hms_eye_personal_history
   public function personal_history_name($personal_history_id="")
   {
		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_eye_personal_history.personal_history');  
			$this->db->where('hms_eye_personal_history.id',$value);  
			$this->db->where('hms_eye_personal_history.is_deleted',0);  
			$this->db->where('hms_eye_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_eye_personal_history.id');  
			$query = $this->db->get('hms_eye_personal_history');
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
    	//print_r($_POST); exit;
    	$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post(); 
		//print_r($post);die;
		$data = array(     
					"booking_code"=>$post['booking_code'],
					"patient_code"=>$post['patient_code'],
					"patient_id"=>$post['patient_id'],
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
					//"prv_history"=>$post['prv_history'],
					"personal_history"=>$post['personal_history'],
					"chief_complaints"=>$post['chief_complaints'],
					"examination"=>$post['examination'],
					"diagnosis"=>$post['diagnosis'],
					"suggestion"=>$post['suggestion'],
					"remark"=>$post['remark'],
					"attended_doctor"=>$post['attended_doctor'],
					//"next_appointment_date"=>$post['next_appointment_date'],
					"appointment_date"=>$post['appointment_date'],
					"status"=>1
					); 
		    
            
            $this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_opd_prescription',$data); 
			$data_id = $this->db->insert_id(); 

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


			 


			if(!empty($post['appointment_date']))
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
						'booking_date'=>date('Y-m-d',strtotime($post['appointment_date']))
						
		         );

		    	$this->db->set('patient_id',$patient_id);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_opd_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
		 	} 
	
    }

	public function get_eye_diseases_data($branch_id="")
	{
		
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_disease.*');  
		$this->db->where('hms_disease.branch_id',$branch_id);  
		$this->db->where('hms_disease.is_deleted',0); 
		$query = $this->db->get('hms_disease');
		$disease_list = $query->result(); 
		$drop = '<select name="diseases" class="w-150px" id="disease_id">
		         <option value="">Select</option>';
		if(!empty($disease_list))
		{
		    foreach($disease_list as $diseases)
		    {
		        $drop .= '<option value="'.$diseases->id.'">'.$diseases->disease.'</option>';
		    }
		}
		$drop .= '</select>';

		if(in_array('44',$users_data['permission']['action'])) {
                      
          $drop .= '<a href="javascript:void(0)" onclick=" return add_spacialization();"  class="btn-new">New</a>';
           }
		
		return $json_data = $drop;
	}

	public function get_gynecology_dosage_vals($vals="")
  {
    $response = '';
    if(!empty($vals))
    {
          $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('dosage','ASC');
          $this->db->where('is_deleted',0);
           $this->db->where('type',1);
          $this->db->where('medicine_dosage LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_opd_medicine_dosage');
          $result = $query->result(); 
       
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->dosage;
            }
          }
          return $response; 
    } 
  }


   public function get_gynecology_duration_vals($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('hms_opd_medicine_dosage_duration','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('type',1);
          $this->db->where('medicine_dosage_duration  LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_opd_medicine_dosage_duration');
          $result = $query->result(); 
        
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->medicine_dosage_duration ;
            }
          }
          return $response; 
      } 
    }

      public function get_gynecology_frequency_vals($vals="")
        {
          $response = '';
          if(!empty($vals))
          {
                $users_data = $this->session->userdata('auth_users'); 
              $this->db->select('*');  
              $this->db->where('status','1'); 
              $this->db->order_by('medicine_dosage_frequency','ASC');
              $this->db->where('is_deleted',0);
              $this->db->where('type',1);
              $this->db->where('medicine_dosage_frequency LIKE "%'.$vals.'%"');
              $this->db->where('branch_id',$users_data['parent_id']);  
              $query = $this->db->get('hms_opd_medicine_dosage_frequency');
              $result = $query->result(); 
              
              if(!empty($result))
              { 
                foreach($result as $vals)
                {
                     $response[] = $vals->medicine_dosage_frequency;
                }
              }
              return $response; 
          } 
        }


    public function get_gynecology_advice_vals($vals="")
    {
      $response = '';
      if(!empty($vals))
      {
            $users_data = $this->session->userdata('auth_users'); 
          $this->db->select('*');  
          $this->db->where('status','1'); 
          $this->db->order_by('advice','ASC');
          $this->db->where('is_deleted',0);
          $this->db->where('advice LIKE "%'.$vals.'%"');
          $this->db->where('branch_id',$users_data['parent_id']);  
          $query = $this->db->get('hms_gynecology_advice');
          $result = $query->result(); 
          //echo $this->db->last_query();
          if(!empty($result))
          { 
            foreach($result as $vals)
            {
                 $response[] = $vals->advice;
            }
          }
          return $response; 
      } 
    }
	public function get_gynecology_medicine_auto_vals($vals="")
    {   
    	//echo "hi";die;
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
	        $this->db->where('hms_medicine_entry.status','1'); 
	        $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
	        $this->db->where('hms_medicine_entry.is_deleted',0);
	        $this->db->where('hms_medicine_entry.medicine_name LIKE "%'.$vals.'%"');
	        $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);
	        $this->db->where('hms_medicine_entry.type','2');  
	        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
	        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
	        $query = $this->db->get('hms_medicine_entry');
	        //echo $this->db->last_query();die;
	        $result = $query->result(); 
	        //print '<pre>'; print_r($result);die;
	       // echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->medicine_name.'|'.$vals->medicine_unit.'|'.$vals->salt.'|'.$vals->company_name;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }
	        
	        //return $response; 
    	} 
    }
    
  	public function gynecology_realtion_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_relation');
		//echo $this->db->last_query();die;
		$result = $query->result(); 
		return $result; 

   	}
    public function gynecology_disease_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_disease');
		//echo $this->db->last_query();die;
		$result = $query->result(); 
		return $result; 

   	}

   	public function gynecology_complaint_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_chief_complaints');
		$result = $query->result(); 
		return $result; 

   	}

   	public function gynecology_allergy_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_allergy');
		$result = $query->result(); 
		return $result; 

   	}

   	public function gynecology_general_examination_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_general_examination');
		$result = $query->result(); 
		return $result; 

   	}

   	public function gynecology_clinical_examination_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_clinical_examination');
		$result = $query->result(); 
		return $result; 

   	}

   	public function gynecology_investigation_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_investigation');
		$result = $query->result(); 
		return $result; 

   	}

   	public function gynecology_advice_list()
   	{
   	    $users_data= $this->session->userdata('auth_users');
     	$this->db->select('*');  
		$this->db->where('status','1');
		$this->db->where('branch_id',$users_data['parent_id']);
		$this->db->where('is_deleted','0');   
		$query = $this->db->get('hms_gynecology_advice');
		$result = $query->result(); 
		return $result; 

   	}


 	public function get_patient_history_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_prescription_patient_history_template');
		$result = $query->result(); 
		$patient_history_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_history)
			{
				$patient_history_list[$i]['marriage_status'] = $patient_history->marriage_status;
				$patient_history_list[$i]['married_life_unit'] = $patient_history->married_life_unit;
				$patient_history_list[$i]['married_life_type'] = $patient_history->married_life_type;
				$patient_history_list[$i]['marriage_no'] = $patient_history->marriage_no;
				$patient_history_list[$i]['marriage_details'] = $patient_history->marriage_details;
				$patient_history_list[$i]['previous_delivery'] = $patient_history->previous_delivery;
				$patient_history_list[$i]['delivery_type'] = $patient_history->delivery_type;
				$patient_history_list[$i]['delivery_details'] = $patient_history->delivery_details;
				$i++;
			}
		}
		return $patient_history_list; 
 	}

 	public function get_patient_family_history_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_patient_family_history_template');
		$result = $query->result(); 
		$patient_family_history_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_family_history)
			{
				$patient_family_history_list[$i]['disease_id'] = $patient_family_history->disease_id;
				$patient_family_history_list[$i]['relation_id'] = $patient_family_history->relation_id;
				$patient_family_history_list[$i]['relation'] = $patient_family_history->relation;
				$patient_family_history_list[$i]['disease'] = $patient_family_history->disease;
				$patient_family_history_list[$i]['family_description'] = $patient_family_history->family_description;
				$patient_family_history_list[$i]['family_duration_unit'] = $patient_family_history->family_duration_unit;
				$patient_family_history_list[$i]['family_duration_type'] = $patient_family_history->family_duration_type;
				$i++;
			}
		}
		return $patient_family_history_list; 
 	}

 	public function get_patient_advice_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_advice_template');
		$result = $query->result(); 
		$patient_advice_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_advice)
			{
				$patient_advice_list[$i]['patient_advice_id'] = $patient_advice->patient_advice_id;
				$patient_advice_list[$i]['advice_value'] = $patient_advice->patient_advice_name;
				$i++;
			}
		}
		return $patient_advice_list; 
 	}

 	public function get_patient_investigation_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_investigation_template');
		$result = $query->result(); 
		$patient_investigation_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_investigation)
			{
				$patient_investigation_list[$i]['patient_investigation_id'] = $patient_investigation->patient_investigation_id;
				$patient_investigation_list[$i]['investigation_value'] = $patient_investigation->patient_investigation_name;
				$patient_investigation_list[$i]['std_value'] = $patient_investigation->std_value;
				$patient_investigation_list[$i]['observed_value'] = $patient_investigation->observed_value;
				$i++;
			}
		}
		return $patient_investigation_list; 
 	}

 	public function get_patient_clinical_examination_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_clinical_examination_template');
		$result = $query->result(); 
		$patient_clinical_examination_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_clinical_examination)
			{
				$patient_clinical_examination_list[$i]['clinical_examination_value'] = $patient_clinical_examination->patient_clinical_examination_name;
				$patient_clinical_examination_list[$i]['patient_clinical_examination_id'] = $patient_clinical_examination->patient_clinical_examination_id;
				$patient_clinical_examination_list[$i]['patient_clinical_examination_type'] = $patient_clinical_examination->patient_clinical_examination_type;
				$patient_clinical_examination_list[$i]['patient_clinical_examination_unit'] = $patient_clinical_examination->patient_clinical_examination_unit;
				$patient_clinical_examination_list[$i]['clinical_examination_description'] = $patient_clinical_examination->clinical_examination_description;

				$i++;
			}
		}
		return $patient_clinical_examination_list; 
 	}

 	public function get_patient_general_examination_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_general_examination_template');
		$result = $query->result(); 
		$patient_general_examination_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_general_examination)
			{
				$patient_general_examination_list[$i]['general_examination_value'] = $patient_general_examination->patient_general_examination_name;
				$patient_general_examination_list[$i]['patient_general_examination_id'] = $patient_general_examination->patient_general_examination_id;
				
				$patient_general_examination_list[$i]['general_examination_description'] = $patient_general_examination->general_examination_description;

				$i++;
			}
		}
		return $patient_general_examination_list; 
 	}

 	public function get_patient_allergy_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_allergy_template');
		$result = $query->result(); 
		$patient_allergy_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_allergy)
			{
				$patient_allergy_list[$i]['allergy_value'] = $patient_allergy->patient_allergy_name;
				$patient_allergy_list[$i]['patient_allergy_id'] = $patient_allergy->patient_allergy_id;
				$patient_allergy_list[$i]['patient_allergy_type'] = $patient_allergy->patient_allergy_type;
				$patient_allergy_list[$i]['patient_allergy_unit'] = $patient_allergy->patient_allergy_unit;
				$patient_allergy_list[$i]['allergy_description'] = $patient_allergy->allergy_description;

				$i++;
			}
		}
		return $patient_allergy_list; 
 	}

 	public function get_patient_complaint_list($template_id="")
 	{
        $users_data = $this->session->userdata('auth_users'); 
		$this->db->select('*');   
		if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}    
		$query = $this->db->get('hms_gynecology_complaint_template');
		$result = $query->result(); 
		$patient_complaint_list = [];
		if(!empty($result))
		{ 
			$i=0;
			foreach($result as $patient_complaint)
			{
				$patient_complaint_list[$i]['complaint_value'] = $patient_complaint->patient_complaint_name;
				$patient_complaint_list[$i]['patient_complaint_id'] = $patient_complaint->patient_complaint_id;
				$patient_complaint_list[$i]['patient_complaint_type'] = $patient_complaint->patient_complaint_type;
				$patient_complaint_list[$i]['patient_complaint_unit'] = $patient_complaint->patient_complaint_unit;
				$patient_complaint_list[$i]['complaint_description'] = $patient_complaint->complaint_description;

				$i++;
			}
		}
		return $patient_complaint_list; 
 	}

 	public function get_gynecology_personal_history_list($template_id="")
	{
	  $users_data = $this->session->userdata('auth_users'); 
	  $this->db->select('*'); 
	  	if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		} 
	  
	  $query = $this->db->get('hms_gynecology_patient_personal_history_template');

	  $result = $query->result(); 
	  
	  $family_personal_list = [];
	  if(!empty($result))
	  { 
	    $i=0;
	    foreach($result as $personal_history)
	    {
	      $family_personal_list[$i]['br_discharge'] = $personal_history->br_discharge;
	      $family_personal_list[$i]['side'] = $personal_history->side;
	      $family_personal_list[$i]['hirsutism'] = $personal_history->hirsutism;
	      $family_personal_list[$i]['white_discharge'] = $personal_history->white_discharge;
	      $family_personal_list[$i]['type'] = $personal_history->type ;
	      $family_personal_list[$i]['frequency_personal'] = $personal_history->frequency_personal ;
	      $family_personal_list[$i]['dyspareunia'] = $personal_history->dyspareunia ;
	      $family_personal_list[$i]['personal_details'] = $personal_history->personal_details ;

	      $i++;
	    }
	  }

	  return $family_personal_list; 
	}


	public function get_gynecology_menstrual_history_list($template_id="")
	{
	  $users_data = $this->session->userdata('auth_users'); 
	  $this->db->select('*'); 
	  	if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		}  
	  
	  $query = $this->db->get('hms_gynecology_patient_menstrual_history_template');

	  $result = $query->result(); 
	  
	  $menstrual_personal_list = [];
	  if(!empty($result))
	  { 
	    $i=0;
	    foreach($result as $menstrual_history)
	    {
	      $menstrual_personal_list[$i]['previous_cycle'] = $menstrual_history->previous_cycle;
	      $menstrual_personal_list[$i]['prev_cycle_type'] = $menstrual_history->prev_cycle_type;
	      $menstrual_personal_list[$i]['present_cycle'] = $menstrual_history->present_cycle;
	      $menstrual_personal_list[$i]['present_cycle_type'] = $menstrual_history->present_cycle_type;
	      $menstrual_personal_list[$i]['cycle_details'] = $menstrual_history->cycle_details ;
	      $menstrual_personal_list[$i]['lmp_date'] = $menstrual_history->lmp_date ;
	      $menstrual_personal_list[$i]['dysmenorrhea'] = $menstrual_history->dysmenorrhea ;
	      $menstrual_personal_list[$i]['dysmenorrhea_type'] = $menstrual_history->dysmenorrhea_type ;
	      $i++;
	    }
	  }

	  return $menstrual_personal_list; 
	}


	public function get_gynecology_medical_history_list($template_id="")
	{
	  $users_data = $this->session->userdata('auth_users'); 
	  $this->db->select('*'); 
	  if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		} 
	  
	  $query = $this->db->get('hms_gynecology_patient_medical_history_template');

	  $result = $query->result(); 
	  
	  $medical_list = [];
	  if(!empty($result))
	  { 
	    $i=0;
	    foreach($result as $medical_history)
	    {
	      $medical_list[$i]['tb'] = $medical_history->tb;
	      $medical_list[$i]['tb_rx'] = $medical_history->tb_rx;
	      $medical_list[$i]['dm'] = $medical_history->dm;
	      $medical_list[$i]['dm_years'] = $medical_history->dm_years;
	      $medical_list[$i]['dm_rx'] = $medical_history->dm_rx ;
	      $medical_list[$i]['ht'] = $medical_history->ht ;
	      $medical_list[$i]['medical_details'] = $medical_history->medical_details ;
	      $medical_list[$i]['medical_others'] = $medical_history->medical_others ;
	      $i++;
	    }
	  }

	  return $medical_list; 
	}

	public function get_gynecology_obestetric_history_list($template_id="")
	{
	  $users_data = $this->session->userdata('auth_users'); 
	  $this->db->select('*'); 
	  	if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		} 
	  
	  $query = $this->db->get('hms_gynecology_patient_obestetric_history_template');

	  $result = $query->result(); 
	  
	  $obestetric_list = [];
	  if(!empty($result))
	  { 
	    $i=0;
	    foreach($result as $obestetric_history)
	    {
	      $obestetric_list[$i]['obestetric_g'] = $obestetric_history->obestetric_g;
	      $obestetric_list[$i]['obestetric_p'] = $obestetric_history->obestetric_p;
	      $obestetric_list[$i]['obestetric_l'] = $obestetric_history->obestetric_l;
	      $obestetric_list[$i]['obestetric_mtp'] = $obestetric_history->obestetric_mtp;
	      $i++;
	    }
	  }

	  return $obestetric_list; 
	}

	public function get_gynecology_disease_list($template_id="")
	{
	  $users_data = $this->session->userdata('auth_users'); 
	  $this->db->select('*'); 
	  	if(!empty($template_id))
		{
			$this->db->where('template_id',$template_id); 
			$this->db->where('branch_id',$users_data['parent_id']); 
		} 
	  
	  $query = $this->db->get('hms_gynecology_patient_disease_template');

	  $result = $query->result(); 
	  
	  $disease_list = [];
	  if(!empty($result))
	  { 
	    $i=0;
	    foreach($result as $disease_history)
	    {
	      $disease_list[$i]['patient_disease_id'] = $disease_history->patient_disease_id;
	      $disease_list[$i]['disease_value'] = $disease_history->patient_disease_name;
	      $disease_list[$i]['patient_disease_unit'] = $disease_history->patient_disease_unit;
	      $disease_list[$i]['patient_disease_type'] = $disease_history->patient_disease_type;
	      
	      $disease_list[$i]['disease_description'] = $disease_history->disease_description;
	      $i++;
	    }
	  }

	  return $disease_list; 
	}


	public function get_investigation_template_data($id)
	{

	  $user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('template_id',$id);
		$query = $this->db->get('hms_dental_prescription_investigation_template');
		return $query->result();

	}
	public function get_gynecology_medicine_prescription_template($template_id="")
	{
		$this->db->select("hms_gynecology_template_medicine_booking.*"); 
		$this->db->from('hms_gynecology_template_medicine_booking'); 
		$this->db->where('hms_gynecology_template_medicine_booking.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
	    return $result;

	}

	public function get_gynecology_medicine_history_template($template_id="")
	{

		$this->db->select("hms_gynecology_patient_current_medication_history_template.*"); 
		$this->db->from('hms_gynecology_patient_current_medication_history_template'); 
		$this->db->where('hms_gynecology_patient_current_medication_history_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
	    return $result;

	}

	public function get_std_value($investigation_id="")
	{
	  	$user_data = $this->session->userdata('auth_users');
		$this->db->select('std_value');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('id',$investigation_id);
		$query = $this->db->get('hms_gynecology_investigation');
		return $query->result();

	}


} 
?>