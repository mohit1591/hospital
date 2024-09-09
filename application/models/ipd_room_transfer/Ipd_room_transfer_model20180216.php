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

						$this->db->select('*');
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
						
                        }
						
						

					//}

				    
				  // }

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