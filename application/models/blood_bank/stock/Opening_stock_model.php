<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Opening_stock_model extends CI_Model 
{

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	
	public function save_all_blood_stock($patient_all_data = array())
	{
		

		//echo "<pre>";print_r($patient_all_data); exit;//$patient_data['relation_type']
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($patient_all_data))
        {
            foreach($patient_all_data as $patient_data)
            {
                
                
                /* $excelDate = $patient_data['date_of_collection'];
                    $timestamp = $excelDate * 60 * 60 * 24;
                    $date_of_collection = date('Y-m-d', $timestamp);*/
                    
                    if(is_numeric($patient_data['date_of_collection']))
					{
					    $collDates = $patient_data['date_of_collection'];
                        $timestampd = $collDates * 60 * 60 * 24;
                        $date_of_collections = date('Y-m-d', $timestampd);
                        $time = strtotime($date_of_collections.' -70 years');
                        $date_of_collection = date("Y-m-d", $time);
					    
					}
					else
					{
					    $date_of_collection = date('Y-m-d',strtotime($patient_data['date_of_collection']));
					}
					
                    
                    
                    if(is_numeric($patient_data['date_of_expiry']))
					{
					    $excelDates = $patient_data['date_of_expiry'];
                        $timestampd = $excelDates * 60 * 60 * 24;
                        $date_of_expirys = date('Y-m-d', $timestampd);
                        $time = strtotime($date_of_expirys.' -70 years');
                        $date_of_expiry = date("Y-m-d", $time);
					    
					}
					else
					{
					    	$date_of_expiry = date('Y-m-d',strtotime($patient_data['date_of_expiry']));
					}
                    
                    /*$excelDates = $patient_data['date_of_expiry'];
                    $timestampd = $excelDates * 60 * 60 * 24;
                    $date_of_expiry = date('Y-m-d', $timestampd);*/
                    /*if($patient_data['BLOODGROUP']=='B+Ve')
			        {
			          $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='O+ve')
			        {
			            $blood_group_id=8;  
			        }
			        else if($patient_data['BLOODGROUP']=='A+ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['BLOODGROUP']=='AB+ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='B-VE')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['BLOODGROUP']=='NT')
			        {
			            $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='')
			        {
			            $blood_group_id=4;  
			        }*/
			        
			        if($patient_data['BLOODGROUP']=='B+Ve')
			        {
			          $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='B+VE')
			        {
			             $blood_group_id=4;   
			        }
			        else if($patient_data['BLOODGROUP']=='B+ve')
			        {
			             $blood_group_id=4;   
			        }
			        else if($patient_data['BLOODGROUP']=='O+ve')
			        {
			             $blood_group_id=8;   
			        }
			        else if($patient_data['BLOODGROUP']=='O+VE')
			        {
			            $blood_group_id=8;  
			        }
			        else if($patient_data['BLOODGROUP']=='A+ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['BLOODGROUP']=='A+VE')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['BLOODGROUP']=='AB+ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['BLOODGROUP']=='AB+VE')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-VE')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-VE')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='B-VE')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['BLOODGROUP']=='B-ve')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['BLOODGROUP']=='NT')
			        {
			            $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='')
			        {
			            $blood_group_id=4;  
			        }
			        
					    
					$branch_id=$users_data['parent_id'];
                    $this->db->select('hms_blood_donor.id');
                    $this->db->from('hms_blood_donor');
                    $this->db->where('hms_blood_donor.branch_id',$branch_id);
                    $this->db->where('hms_blood_donor.donor_code',$patient_data['donor']);
                    $query = $this->db->get(); 
                   //echo $this->db->last_query();die;
                    $result =  $query->row_array();    
					//echo "<pre>"; print_r($result); exit;
					if(!empty($result))
					{
					    $donor_id = $result['id'];    
					}
			        else
			        {
			            $donor_data_array=array(
                           'donor_name'=>$patient_data['donor'],
                           'blood_group_id'=>$blood_group_id,
                           'registration_date'=>$date_of_collection,
                           'status'=>'1',
                           'donor_status'=>1,
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );

                        $donor_code = generate_unique_id(41);
                        $donor_data_array['donor_code']=$donor_code;
                        $donor_data_array["branch_id"]=$users_data['parent_id'];
                        $donor_data_array["created_by"]=$users_data['id'];
                        $donor_data_array["created_date"]=date('Y-m-d H:i:s',strtotime($patient_data['date_of_collection']));
            
					    $this->db->insert('hms_blood_donor',$donor_data_array);
					    //echo $this->db->last_query(); exit;
					    $donor_id = $this->db->insert_id(); 
			        }
			       $compn_id=0; 
                   if(!empty($patient_data['COMPONANT']))
                   {
                        $this->db->select('hms_blood_component_master.id');
                        $this->db->from('hms_blood_component_master');
                        $this->db->where('hms_blood_component_master.branch_id',$users_data['parent_id']);
                       $this->db->where('LOWER(hms_blood_component_master.component)',strtolower($patient_data['COMPONANT'])); 
                        $query = $this->db->get(); 
                       //echo $this->db->last_query();die;
                        $result =  $query->row_array();    
    					//echo "<pre>"; print_r($result); exit;
    					if(!empty($result))
    					{
    					    $compn_id = $result['id'];    
    					}
                   }
                   else if(is_numeric($patient_data['component_id']))
                   {
                       $compn_id = $patient_data['component_id'];
                   }
                   
                   if(!empty($patient_data['bag_type_id']))
                   {
                        $this->db->select('hms_blood_bag_type.id');
                        $this->db->from('hms_blood_bag_type');
                        $this->db->where('hms_blood_bag_type.branch_id',$users_data['parent_id']);
                       $this->db->where('LOWER(hms_blood_bag_type.bag_type)',strtolower($patient_data['bag_type_id'])); 
                        $query = $this->db->get(); 
                       //echo $this->db->last_query();die;
                        $result =  $query->row_array();    
    					//echo "<pre>"; print_r($result); exit;
    					if(!empty($result))
    					{
    					    $bag_type = $result['id'];    
    					}
                   }
                   else if(is_numeric($patient_data['bag_type_id']))
                   {
                       $bag_type = $patient_data['bag_type_id'];
                   }
                   
                   
					
                    $stock_array=array(
                                  'donor_id'=>$donor_id,
                                  'branch_id'=>$users_data['parent_id'],
                                  'bag_type_id' =>$bag_type,
                                  'component_id' => $compn_id,
                                  'component_name'=>$patient_data['COMPONANT'],
                                  'component_price'=>$patient_data['Component_price'],
                                  'qty'=>1,
                                  'volumn'=>$details['volumn'],
                                   'expiry_date' =>$date_of_expiry,//date('Y-m-d H:i:s',strtotime($patient_data['date_of_expiry'])), /* new field */
                                  'debit'=>1,/* change vale 1 */
                                  'blood_group_id'=>$blood_group_id,
                                  'bar_code' => '',
                                  'created_date'=>$date_of_collection,//date('Y-m-d H:i:s',strtotime($patient_data['date_of_collection'])),
                                  'created_by'=>$users_data['id'],
                                  'status'=>1,
                                  
                                  'qc_status'=>1,
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'flag'=>1,
                                );
				    $this->db->insert('hms_blood_stock',$stock_array);
				    $data_id = $this->db->insert_id();  
				   //echo "<pre>"; echo $this->db->last_query(); exit;
                }
               	
        }
	}
	
	
	
	public function save_all_donor($patient_all_data = array())
	{
		
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($patient_all_data))
        {
            foreach($patient_all_data as $patient_data)
            {
                
                
                 $excelDate = $patient_data['date_of_collection'];
                    $timestamp = $excelDate * 60 * 60 * 24;
                    $date_of_collection = date('Y-m-d', $timestamp);
                    
                    
                    $excelDates = $patient_data['date_of_expiry'];
                    $timestampd = $excelDates * 60 * 60 * 24;
                    $date_of_expiry = date('Y-m-d', $timestampd);
                    if($patient_data['BLOODGROUP']=='B+Ve')
			        {
			          $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='O+ve')
			        {
			            $blood_group_id=8;  
			        }
			        else if($patient_data['BLOODGROUP']=='A+ve')
			        {
			            $blood_group_id=2;  
			        }
			        else if($patient_data['BLOODGROUP']=='AB+ve')
			        {
			            $blood_group_id=5;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='O-ve')
			        {
			            $blood_group_id=7;  
			        }
			        else if($patient_data['BLOODGROUP']=='B-VE')
			        {
			            $blood_group_id=3;  
			        }
			        else if($patient_data['BLOODGROUP']=='NT')
			        {
			            $blood_group_id=4;  
			        }
			        else if($patient_data['BLOODGROUP']=='')
			        {
			            $blood_group_id=4;  
			        }
			        
					    
					$branch_id=$users_data['parent_id'];
                    $this->db->select('hms_blood_donor.id');
                    $this->db->from('hms_blood_donor');
                    $this->db->where('hms_blood_donor.branch_id',$branch_id);
                    $this->db->where('hms_blood_donor.donor_code',$patient_data['donor']);
                    $query = $this->db->get(); 
                   //echo $this->db->last_query();die;
                    $result =  $query->row_array();    
					//echo "<pre>"; print_r($result); exit;
					if(!empty($result))
					{
					    $donor_id = $result['id'];    
					}
			        else
			        {
			            $donor_data_array=array(
                           'donor_name'=>$patient_data['donor'],
                           'blood_group_id'=>$blood_group_id,
                           'registration_date'=>$date_of_collection,
                           'status'=>'1',
                           'ip_address'=>$_SERVER['REMOTE_ADDR'],
                           );

                          // $reg_no = generate_unique_id(41);
                        $donor_data_array['donor_code']=$patient_data['donor'];
                        $donor_data_array["branch_id"]=$users_data['parent_id'];
                        $donor_data_array["created_by"]=$users_data['id'];
                        $donor_data_array["created_date"]=date('Y-m-d H:i:s',strtotime($patient_data['date_of_collection']));
            
					    $this->db->insert('hms_blood_donor',$donor_data_array);
					    $donor_id = $this->db->insert_id(); 
			        }
			        
                   
					
                    $stock_array=array(
                                  'donor_id'=>$donor_id,
                                  'branch_id'=>$users_data['parent_id'],
                                  'bag_type_id' =>$patient_data['bag_type_id'],
                                  'component_id' => $patient_data['component_id'],
                                  'component_name'=>$patient_data['COMPONANT'],
                                  'component_price'=>$patient_data['Component_price'],
                                  'qty'=>1,
                                  'volumn'=>$details['volumn'],
                                   'expiry_date' =>$date_of_expiry,//date('Y-m-d H:i:s',strtotime($patient_data['date_of_expiry'])), /* new field */
                                  'debit'=>1,/* change vale 1 */
                                  'blood_group_id'=>$blood_group_id,
                                  'bar_code' => '',
                                  'created_date'=>$date_of_collection,//date('Y-m-d H:i:s',strtotime($patient_data['date_of_collection'])),
                                  'created_by'=>$users_data['id'],
                                  'status'=>1,
                                  'qc_status'=>1,
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'flag'=>1,
                                );
				    $this->db->insert('hms_blood_stock',$stock_array);
				    $data_id = $this->db->insert_id();  
				    
				   //echo "<pre>"; echo $this->db->last_query(); //exit;


					
	            }
               	
        }
	}
	



} 
?>