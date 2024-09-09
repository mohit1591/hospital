<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_room_transfer_model extends CI_Model {

	var $table = 'hms_ipd_patient_to_charge';
	var $column = array('hms_ipd_patient_to_charge.id','hms_ipd_patient_to_charge.ipd_id','hms_ipd_patient_to_charge.patient_id','hms_ipd_patient_to_charge.patient_id','hms_ipd_patient_to_charge.payment_date','hms_ipd_patient_to_charge.created_date','hms_ipd_patient_to_charge.price');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}



	
	public function save()
	{
	    
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		//echo "<pre>"; print_r($post); //exit;
		/* end charges */
		
		        $users_data = $this->session->userdata('auth_users');
			   $type_value= get_ipd_discharge_time_setting_value();
               $patient_id = $post['patient_id'];
               $ipd_id = $post['ipd_id'];
               $this->db->select('*');
               $this->db->from('hms_ipd_patient_to_charge');
               $this->db->where('hms_ipd_patient_to_charge.type',3);
               $this->db->where(array('ipd_id'=>$ipd_id,'patient_id'=>$patient_id));
               $query= $this->db->get()->result();
               //echo $this->db->last_query(); exit;
                //echo "<pre>"; print_r($query); exit;
                foreach($query as $data)
                {
                      
                    if($data->end_date=='0000-00-00 00:00:00')
                    {
                        //date('Y-m-d H:i:s',strtotime($post['transfer_date'])),

                         $update_data=  array('end_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])));
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
                                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        ,'transfer_status'=>2
                                        );

                                        //print '<pre>'; print_r($insert_charge_entry);
                                        $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);

                                       
                                    }
                                    else
                                    {
                                        $data_query['start_date'] = date('Y-m-d H:i:s'); 
                                        $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                        'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                        'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        ,'transfer_status'=>2
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
                                    'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                    'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by']
                                        ,'transfer_status'=>2
                                    );

                                    //print '<pre>'; print_r($insert_charge_entry);
                                    $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                                }
                                else
                                {

                                $data_query['start_date'] = date('Y-m-d H:i:s'); 
                                $insert_charge_entry= array('branch_id'=>$users_data['parent_id'],
                                'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by'],
                                    'transfer_status'=>2
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
                                'ipd_id'=>$ipd_id,'patient_id'=>$patient_id,'type'=>3,'particular_id'=>$data_query['particular_id'],'particular'=>$data_query['particular'],
                                'start_date'=>$data_query['start_date'],'end_date'=>$data_query['start_date'],'quantity'=>$data_query['quantity'],'price'=>$data_query['price'],'panel_price'=>$data_query['panel_price'],'panel_price'=>$data_query['panel_price'],'net_price'=>$data_query['net_price'],'created_date'=>$data_query['created_date'],'is_deleted'=>$data_query['is_deleted'],'status'=>$data_query['status'],'created_by'=>$data_query['created_by'],'transfer_status'=>2
                                );

                             //print '<pre>'; print_r($insert_charge_entry);
                            $this->db->insert('hms_ipd_patient_to_charge',$insert_charge_entry);
                           
                        }
                }
    
                        $this->db->where('id',$data_query['id']);
                        $this->db->delete('hms_ipd_patient_to_charge');
        }
		/* end charges */
		
		///New charges insert
		$this->load->model('general/general_model'); 
		$room_type_list = $this->general_model->room_type_list(); 
		$room_charge_type_list = $this->general_model->room_charge_type_list();
		//print_r($room_charge_type_list);
		
		//echo "<pre>";print_r($room_charge_type_list); exit;

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
								'transfer_status'=>1,
								'particular'=>$charge_type,
								'price'=>$charges,
								'panel_price'=>$charges,
								'net_price'=>$charges,
								'status'=>1,
								'created_date'=>date('Y-m-d H:i:s')
							);
    //print_r($registration_patient_charge); exit;
						$this->db->insert('hms_ipd_patient_to_charge',$registration_patient_charge);
						//echo $this->db->last_query(); exit;
				        
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
						if(!empty($bed_id))
						{
							$this->db->set('status','0');
							$this->db->set('ipd_id','0');
							$this->db->where('id',$bed_id);
							$this->db->update('hms_ipd_room_to_bad');
						}
						$this->db->set('status','1');
						$this->db->set('ipd_id',$post['ipd_id']);
						$this->db->where('id',$post['bed_no_id']);
						$this->db->update('hms_ipd_room_to_bad');

		
	}
	public function save28july2020()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$this->load->model('general/general_model'); 
					$room_type_list = $this->general_model->room_type_list(); 
					$room_charge_type_list = $this->general_model->room_charge_type_list();
					//if(!empty($room_charge_type_list))
					//{
					//$room_charge_type_list_count = count($room_charge_type_list);
					//for($i=0;$i<$room_charge_type_list_count;$i++)
					//{
						//$charge_type = ucfirst($room_charge_type_list[$i]['charge_type']);
						//$form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))] = get_charges_according($post['room_no_id'],$room_charge_type_list[$i]['id']);
						//$charges= $form_data[strtolower(str_replace(' ','_',$room_charge_type_list[$i]['charge_type']))]['charge'];

						/*$this->db->select('*');
						$this->db->from('hms_ipd_patient_to_charge');
						$this->db->where(array('ipd_id'=>$post['ipd_id'],'patient_id'=>$post['patient_id']));
						$result_room= $this->db->get()->result();

					
                        foreach($result_room as $rooms_transfer){
                        	
                        	    $registration_patient_charge_update = array(
								"branch_id"=>$user_data['parent_id'],
								'ipd_id'=>$post['ipd_id'],
								'patient_id'=>$post['patient_id'],
								'end_date'=>date('Y-m-d H:i:s',strtotime($post['transfer_date'])),
								'type'=>3,
							    'transfer_status'=>2,
								'particular'=>$rooms_transfer->particular,
								'price'=>$rooms_transfer->price,
								'panel_price'=>$rooms_transfer->panel_price,
								'net_price'=>$rooms_transfer->net_price,
								'status'=>1,
								'created_date'=>date('Y-m-d H:i:s')
							);
                        	$this->db->where('hms_ipd_patient_to_charge.end_date','0000-00-00 00:00:00');
							$this->db->where(array('id'=>$rooms_transfer->id));
						    $this->db->update('hms_ipd_patient_to_charge',$registration_patient_charge_update);
						
                        }*/
						
						

					//}

				    
				  // }
				  
				  $this->db->from('hms_ipd_patient_to_charge');
						$this->db->where(array('ipd_id'=>$post['ipd_id'],'patient_id'=>$post['patient_id'],'type'=>3));
						$result_room_old= $this->db->get()->result();
						if(!empty($result_room_old))
						{
						foreach($result_room_old as $rooms_transfer)
						{
						 
						 $old_romm_array = array('transfer_status'=>2);
						 
							$this->db->where(array('id'=>$rooms_transfer->id,'type'=>3));
						    $this->db->update('hms_ipd_patient_to_charge',$old_romm_array);
						    
						}
						}

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
						if(!empty($bed_id))
						{
							$this->db->set('status','0');
							$this->db->set('ipd_id','0');
							$this->db->where('id',$bed_id);
							$this->db->update('hms_ipd_room_to_bad');
						}
						$this->db->set('status','1');
						$this->db->set('ipd_id',$post['ipd_id']);
						$this->db->where('id',$post['bed_no_id']);
						$this->db->update('hms_ipd_room_to_bad');

		}


}
?>