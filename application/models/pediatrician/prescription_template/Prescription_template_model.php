<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Prescription_template_model extends CI_Model 
{
	var $table = 'hms_pediatrician_prescription_template';
	var $column = array('hms_pediatrician_prescription_template.id','hms_pediatrician_prescription_template.template_name','hms_pediatrician_prescription_template.status', 'hms_pediatrician_prescription_template.created_date', 'hms_pediatrician_prescription_template.modified_date');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select("hms_pediatrician_prescription_template.*");
		$this->db->where('hms_pediatrician_prescription_template.is_deleted','0'); 
		$this->db->where('hms_pediatrician_prescription_template.branch_id = "'.$user_data['parent_id'].'"');

		

		/////// Search query end //////////////
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
		$this->db->select('hms_pediatrician_prescription_template.*');
		$this->db->from('hms_pediatrician_prescription_template'); 
		$this->db->where('hms_pediatrician_prescription_template.branch_id',$user_data['parent_id']); 
		$this->db->where('hms_pediatrician_prescription_template.id',$id);
		$this->db->where('hms_pediatrician_prescription_template.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save($filename="")
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		
		//echo "<pre>";print_r($post); exit;  
		$data = array(     
							'branch_id'=>$user_data['parent_id'],
							"template_name"=>$post['template_name'],
							/*"patient_bp"=>$post['patient_bp'],
							"patient_temp"=>$post['patient_temp'],
							"patient_weight"=>$post['patient_weight'],
							"patient_height"=>$post['patient_height'],
							"patient_spo2"=>$post['patient_spo2'],*/

							"prv_history"=>$post['prv_history'],
							"personal_history"=>$post['personal_history'],
							"chief_complaints"=>$post['chief_complaints'],
							"examination"=>$post['examination'],
							"diagnosis"=>$post['diagnosis'],
							"suggestion"=>$post['suggestion'],
							"remark"=>$post['remark'],
							"appointment_date"=>date('Y-m-d',strtotime($post['next_appointment_date'])),
							"status"=>1
				         ); 
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
			//template

			$total_test = count($post['test_name']);
			if($total_test > 0)
			{
				$this->db->where('template_id',$post['data_id']);
    			$this->db->delete('hms_pediatrician_prescription_patient_test_template');
			}
			
			//echo "<pre>"; print_r($post); exit;
			for($i=0;$i<$total_test;$i++)
			{
					

                              //check and add masters of test 
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
						"template_id"=>$post['data_id'],
						"test_name"=>$post['test_name'][$i],
						"test_id"=>$test_id);
					$this->db->insert('hms_pediatrician_prescription_patient_test_template',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			}


			$total_prescription = count($post['medicine_name']); 
			if($total_prescription > 0)
			{
				$this->db->where('template_id',$post['data_id']);
    			$this->db->delete('hms_pediatrician_prescription_patient_pres_template');
			}
			/*for($i=0;$i<=$total_prescription-1;$i++)
			{
					$prescription_data = array(
						"template_id"=>$post['data_id'],
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_salt"=>$post['medicine_salt'][$i],
						"medicine_brand"=>$post['medicine_brand'][$i],
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_pediatrician_prescription_patient_pres_template',$prescription_data); 
					$test_data_id = $this->db->insert_id(); 
			}*/

			for($i=0;$i<=$total_prescription-1;$i++)
			{
					/* test */
					                  //check and add masters 
					if(!empty($post['medicine_name'][$i]))
					{	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_pediatrician_medicine');
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
								$company_id ='';
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
								$salt ='';
								if(!empty($post['salt'][$i]))
								{
									$salt = $post['salt'][$i];
								}
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$post['medicine_name'][$i],
												'type'=>$unit_id,
												'salt'=>$salt,
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
					/*  */


					$prescription_data = array(
						"template_id"=>$post['data_id'],
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_id"=>$medicine_id,
						"medicine_brand"=>$post['medicine_brand'][$i],
						"medicine_salt"=>$post['medicine_salt'][$i],
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_pediatrician_prescription_patient_pres_template',$prescription_data); 
					$test_data_id = $this->db->insert_id(); 
			}

			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
                        $this->db->where('id',$post['data_id']);
			$this->db->update('hms_pediatrician_prescription_template',$data); 
//echo $this->db->last_query(); exit;
		}
		else
		{    
            
			$this->db->set('ip_address',$_SERVER['REMOTE_ADDR']);
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_pediatrician_prescription_template',$data); 
			$data_id = $this->db->insert_id(); 


			$total_test = count($post['test_name']);
			//echo "<pre>"; print_r($post); exit;
			for($i=0;$i<$total_test;$i++)
			{
					

                              //check and add masters of test 
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
						"template_id"=>$data_id,
						"test_name"=>$post['test_name'][$i],
						"test_id"=>$test_id);
					$this->db->insert('hms_pediatrician_prescription_patient_test_template',$test_data); 
					$test_data_id = $this->db->insert_id(); 
			}

			$total_prescription = count($post['medicine_name']); 
			for($i=0;$i<=$total_prescription-1;$i++)
			{
					/* test */
					                  //check and add masters 
					if(!empty($post['medicine_name'][$i]))
					{	
						$this->db->select('hms_medicine_entry.*');  
						//$this->db->from('hms_pediatrician_medicine');
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
								$company_id ='';
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
								$salt ='';
								if(!empty($post['salt'][$i]))
								{
									$salt = $post['salt'][$i];
								}
								$data = array( 
												'branch_id'=>$user_data['parent_id'],
												'medicine_name'=>$post['medicine_name'][$i],
												'type'=>$unit_id,
												'salt'=>$salt,
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
					/*  */


					$prescription_data = array(
						"template_id"=>$data_id,
						"medicine_name"=>$post['medicine_name'][$i],
						"medicine_id"=>$medicine_id,
						"medicine_brand"=>$post['medicine_brand'][$i],
						"medicine_salt"=>$post['medicine_salt'][$i],
						"medicine_type"=>$post['medicine_type'][$i],
						"medicine_dose"=>$post['medicine_dose'][$i],
						"medicine_duration"=>$post['medicine_duration'][$i],
						"medicine_frequency"=>$post['medicine_frequency'][$i],
						"medicine_advice"=>$post['medicine_advice'][$i]
						);
					$this->db->insert('hms_pediatrician_prescription_patient_pres_template',$prescription_data); 
					$test_data_id = $this->db->insert_id(); 
			}

		} 	
	}


	function get_pediatrician_prescription_template($template_id='')
	{
		$this->db->select("hms_pediatrician_prescription_patient_pres_template.*"); 
		$this->db->from('hms_pediatrician_prescription_patient_pres_template'); 
		$this->db->where('hms_pediatrician_prescription_patient_pres_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
        return $result;
	}

	function get_pediatrician_test_template($template_id='')
	{
		$this->db->select("hms_pediatrician_prescription_patient_test_template.*"); 
		$this->db->from('hms_pediatrician_prescription_patient_test_template'); 
		$this->db->where('hms_pediatrician_prescription_patient_test_template.template_id',$template_id);
		$query = $this->db->get(); 
		$result = $query->result(); 
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
			$this->db->update('hms_pediatrician_prescription_template');
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
			$this->db->update('hms_pediatrician_prescription_template');
    	} 
    }

    public function delete_pres_medicine($medicine_presc_id='',$template_id='')
    {
    	

    	if(!empty($medicine_presc_id) && $medicine_presc_id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->where('id',$medicine_presc_id);
			$this->db->delete('hms_pediatrician_prescription_patient_pres_template'); 
			
			//echo $this->db->last_query();die;
    	}

    	$this->db->select("*"); 
		$this->db->from('hms_pediatrician_prescription_patient_pres_template'); 
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
			$this->db->update('hms_pediatrician_booking',$data_test);  

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
			$this->db->insert('hms_pediatrician_booking',$data_test);    
            $booking_id = $this->db->insert_id(); 
			 
		} 	
	}


	public function pediatrician_doctor_rate($doctor_id="")
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
			$this->db->update('hms_pediatrician_booking',$data);  
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
			$this->db->select('hms_pediatrician_chief_complaints.chief_complaints');  
			$this->db->where('hms_pediatrician_chief_complaints.id',$value);  
			$this->db->where('hms_pediatrician_chief_complaints.is_deleted',0);  
			$this->db->where('hms_pediatrician_chief_complaints.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_chief_complaints.id');  
			$query = $this->db->get('hms_pediatrician_chief_complaints');
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
			$this->db->select('hms_pediatrician_examination.examination');  
			$this->db->where('hms_pediatrician_examination.id',$value);  
			$this->db->where('hms_pediatrician_examination.is_deleted',0);  
			$this->db->where('hms_pediatrician_examination.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_examination.id');  
			$query = $this->db->get('hms_pediatrician_examination');
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
        $query = $this->db->get('hms_pediatrician_examination');
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
			$this->db->select('hms_pediatrician_diagnosis.diagnosis');  
			$this->db->where('hms_pediatrician_diagnosis.id',$value);  
			$this->db->where('hms_pediatrician_diagnosis.is_deleted',0);  
			$this->db->where('hms_pediatrician_diagnosis.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_diagnosis.id');  
			$query = $this->db->get('hms_pediatrician_diagnosis');
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
        $query = $this->db->get('hms_pediatrician_diagnosis');
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
        $query = $this->db->get('hms_pediatrician_suggetion');
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
			$this->db->select('hms_pediatrician_suggetion.medicine_suggetion');  
			$this->db->where('hms_pediatrician_suggetion.id',$value);  
			$this->db->where('hms_pediatrician_suggetion.is_deleted',0);  
			$this->db->where('hms_pediatrician_suggetion.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_suggetion.id');  
			$query = $this->db->get('hms_pediatrician_suggetion');
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
        $query = $this->db->get('hms_pediatrician_prv_history');
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
			$this->db->select('hms_pediatrician_prv_history.prv_history');  
			$this->db->where('hms_pediatrician_prv_history.id',$value);  
			$this->db->where('hms_pediatrician_prv_history.is_deleted',0);  
			$this->db->where('hms_pediatrician_prv_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_prv_history.id');  
			$query = $this->db->get('hms_pediatrician_prv_history');
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
        $query = $this->db->get('hms_pediatrician_personal_history');
        $result = $query->result(); 
        return $result; 
    }
    //hms_pediatrician_personal_history
   public function personal_history_name($personal_history_id="")
   {

		$personal_history_ids = explode(',', $personal_history_id);
		$users_data = $this->session->userdata('auth_users'); 
		$personalname="";
		$i=1;
		$total = count($personal_history_ids);
		foreach ($personal_history_ids as $value) 
		{
			$this->db->select('hms_pediatrician_personal_history.personal_history');  
			$this->db->where('hms_pediatrician_personal_history.id',$value);  
			$this->db->where('hms_pediatrician_personal_history.is_deleted',0);  
			$this->db->where('hms_pediatrician_personal_history.branch_id  IN ('.$users_data['parent_id'].',0)');  
			$this->db->group_by('hms_pediatrician_personal_history.id');  
			$query = $this->db->get('hms_pediatrician_personal_history');
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
			$this->db->insert('hms_pediatrician_prescription',$data); 
			$data_id = $this->db->insert_id(); 

			$total_test = count($post['test_name']);

			foreach ($post['test_name'] as $value) 
			{
					$test_data = array(
						"prescription_id"=>$data_id,
						"test_name"=>$value);
					$this->db->insert('hms_pediatrician_prescription_patient_test',$test_data); 
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
					$this->db->insert('hms_pediatrician_prescription_patient_pres',$prescription_data); 
					$test_data_id = $this->db->insert_id(); 
			}


			 


			if(!empty($post['appointment_date']))
		    {
		       $booking_id = $post['booking_id'];
		       $pediatrician_booking_data = $this->pediatrician_model->get_by_id($booking_id);
		      	
	           if(!empty($pediatrician_booking_data))
	           {
	              $booking_id = $pediatrician_booking_data['id'];
	              $referral_doctor = $pediatrician_booking_data['referral_doctor'];
	              $attended_doctor = $pediatrician_booking_data['attended_doctor'];
	              $patient_id = $pediatrician_booking_data['patient_id'];
	              $simulation_id = $pediatrician_booking_data['simulation_id'];
	              $patient_code = $pediatrician_booking_data['patient_code'];
	              $patient_name = $pediatrician_booking_data['patient_name'];
	              $mobile_no = $pediatrician_booking_data['mobile_no'];
	              $gender = $pediatrician_booking_data['gender'];
	              $age_y = $pediatrician_booking_data['age_y'];
	              $age_m = $pediatrician_booking_data['age_m'];
	              $age_d = $pediatrician_booking_data['age_d'];
	              $address = $pediatrician_booking_data['address'];
	              $city_id = $pediatrician_booking_data['city_id'];
	              $state_id = $pediatrician_booking_data['state_id'];
	              $country_id = $pediatrician_booking_data['country_id']; 
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
				$this->db->insert('hms_pediatrician_booking',$data_test);    
	            $booking_id = $this->db->insert_id(); 
		 	} 
	
    }


     public function get_test_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('test_name','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('test_name LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('path_test');
	        //$query = $this->db->get('hms_pediatrician_test_name');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->test_name.'|'.$vals->id;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }

	        /*if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response['test_id'] = $vals->id;
	               $response['test_name'] = $vals->test_name;
	        	}
	        }
	        return $response; */
    	} 
    }

    
    public function get_dosage_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('dosage','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('dosage LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_dosage');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
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

    public function get_type_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_type');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_type;
	        	}
	        }
	        return $response; 
    	} 
    }

    

    public function get_duration_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_duration','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_duration LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_dosage_duration');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_dosage_duration;
	        	}
	        }
	        return $response; 
    	} 
    }


    public function get_frequency_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_dosage_frequency','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_dosage_frequency LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_medicine_dosage_frequency');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
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

    public function get_advice_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('medicine_advice','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_advice LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_pediatrician_advice');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->medicine_advice;
	        	}
	        }
	        return $response; 
    	} 
    }
  	public function get_medicine_auto_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('hms_medicine_entry.*,hms_medicine_company.company_name,hms_medicine_unit.medicine_unit');  
	        $this->db->where('hms_medicine_entry.status','1'); 
	        $this->db->order_by('hms_medicine_entry.medicine_name','ASC');
	        $this->db->where('hms_medicine_entry.is_deleted',0);
	        $this->db->where('hms_medicine_entry.medicine_name LIKE "'.$vals.'%"');
	        $this->db->where('hms_medicine_entry.branch_id',$users_data['parent_id']);  
	        $this->db->join('hms_medicine_unit','hms_medicine_unit.id=hms_medicine_entry.unit_id','left');
	        $this->db->join('hms_medicine_company','hms_medicine_company.id = hms_medicine_entry.manuf_company','left');
	        $query = $this->db->get('hms_medicine_entry');
	        $result = $query->result(); 
	        //echo $this->db->last_query();
	        if(!empty($result))
	        { 
	        	$data = array();
	        	foreach($result as $vals)
	        	{
	               $name = $vals->medicine_name.'|'.$vals->medicine_unit.'|'.$vals->company_name.'|'.$vals->salt.'|'.$vals->id;
					array_push($data, $name);
	               //$response[] = $vals->medicine;
	        	}

	        	echo json_encode($data);
	        }
	        
	        //return $response; 
    	} 
    }
    





} 
?>