<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Advance_payment_model extends CI_Model {

	var $table = 'hms_ipd_patient_to_charge';
	var $column = array('hms_ipd_patient_to_charge.id','hms_patient.patient_code','hms_ipd_booking.ipd_no','hms_patient.patient_name','hms_ipd_booking.admission_date','hms_ipd_patient_to_charge.net_price','hms_ipd_patient_to_charge.created_date');
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
			$search = $this->session->userdata('advance_payment_serach');
			$users_data = $this->session->userdata('auth_users');
			$this->db->select("hms_ipd_patient_to_charge.*,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date,hms_patient.patient_name,hms_ipd_patient_to_charge.patient_id,hms_patient.patient_name,hms_patient.patient_code");
			$this->db->from($this->table); 
			$this->db->where('hms_ipd_patient_to_charge.is_deleted','0');
                        $this->db->where('hms_ipd_booking.is_deleted!=','2');
			$this->db->where('hms_ipd_patient_to_charge.type','2');
			$this->db->where('hms_ipd_patient_to_charge.branch_id = "'.$users_data['parent_id'].'"');
			$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_to_charge.ipd_id','left');
			$this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id','left');

			$i = 0;

			if(isset($search) && !empty($search))
			{

			if(isset($search['start_date']) && !empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_ipd_patient_to_charge.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_ipd_patient_to_charge.created_date <= "'.$end_date.'"');
			}
	         
         
			if(isset($search['ipd_no']) &&  !empty($search['ipd_no']))
			{
			$this->db->where('hms_ipd_booking.ipd_no = "'.$search['ipd_no'].'"');
			}
			if(isset($search['patient_code']) &&  !empty($search['patient_code']))
			{
			$this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}
			if(isset($search['patient_name']) &&  !empty($search['patient_name']))
			{
			$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}
			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
			$this->db->where('hms_patient.mobile_no = "'.$search['mobile_no'].'"');
			}
		}
		
		$emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search["employee"]) && is_numeric($search['employee']))
        {
            $emp_ids=  $search["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_patient_to_charge.created_by IN ('.$emp_ids.')');
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
    
    public function search_report_data()
	{
			$search = $this->session->userdata('advance_payment_serach');
			$users_data = $this->session->userdata('auth_users');
			$this->db->select("hms_ipd_patient_to_charge.*,hms_ipd_booking.ipd_no,hms_ipd_booking.admission_date,hms_patient.patient_name,hms_ipd_patient_to_charge.patient_id,hms_patient.patient_name,hms_patient.patient_code");
			$this->db->from($this->table); 
			$this->db->where('hms_ipd_patient_to_charge.is_deleted','0');
                        $this->db->where('hms_ipd_booking.is_deleted!=','2');
			$this->db->where('hms_ipd_patient_to_charge.type','2');
			$this->db->where('hms_ipd_patient_to_charge.branch_id = "'.$users_data['parent_id'].'"');
			$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_to_charge.ipd_id','left');
			$this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id','left');

			$i = 0;

			if(isset($search) && !empty($search))
			{

			if(isset($search['start_date']) && !empty($search['start_date']))
			{
			$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
			$this->db->where('hms_ipd_patient_to_charge.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
			$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
			$this->db->where('hms_ipd_patient_to_charge.created_date <= "'.$end_date.'"');
			}
	         
         
			if(isset($search['ipd_no']) &&  !empty($search['ipd_no']))
			{
			$this->db->where('hms_ipd_booking.ipd_no = "'.$search['ipd_no'].'"');
			}
			if(isset($search['patient_code']) &&  !empty($search['patient_code']))
			{
			$this->db->where('hms_patient.patient_code LIKE "'.$search['patient_code'].'%"');
			}
			if(isset($search['patient_name']) &&  !empty($search['patient_name']))
			{
			$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}
			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
			$this->db->where('hms_patient.mobile_no = "'.$search['mobile_no'].'"');
			}
		}
		
		$emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search["employee"]) && is_numeric($search['employee']))
        {
            $emp_ids=  $search["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_patient_to_charge.created_by IN ('.$emp_ids.')');
        }
	    
	    $query = $this->db->get(); 
		//echo $this->db->last_query();die;
		return $query->result();
		
	}
	public function search_report_data_old()
	{
		$search = $this->session->userdata('ot_booking_serach');
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_operation_booking.*,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_id,hms_patient.patient_name,hms_patient.patient_code,hms_doctors.doctor_name,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_ipd_room_category.room_category as room_type"); 
		$this->db->from($this->table); 
        $this->db->where('hms_operation_booking.is_deleted','0');
        $this->db->where('hms_operation_booking.branch_id = "'.$users_data['parent_id'].'"');
        $this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_operation_booking.ipd_id','left');
        $this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id','left');
       	$this->db->join('hms_doctors','hms_doctors.id=hms_operation_booking.doctor_id','left');
        $this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id=hms_ipd_booking.room_type_id','left');
		$i = 0;

		if(isset($search) && !empty($search))
		{

			if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date']));
				$this->db->where('hms_operation_booking.operation_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) &&  !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date']));
				$this->db->where('hms_operation_booking.operation_date <= "'.$end_date.'"');
			}
         
         
			if(isset($search['ipd_no']) &&  !empty($search['ipd_no']))
			{
			$this->db->where('hms_ipd_booking.ipd_no = "'.$search['ipd_no'].'"');
			}
			if(isset($search['patient_code']) &&  !empty($search['patient_code']))
			{
			$this->db->where('hms_patient.patient_code = "'.$search['patient_code'].'"');
			}
			if(isset($search['patient_name']) &&  !empty($search['patient_name']))
			{
			$this->db->where('hms_patient.patient_name LIKE "'.$search['patient_name'].'%"');
			}
			if(isset($search['mobile_no']) &&  !empty($search['mobile_no']))
			{
			$this->db->where('hms_patient.mobile_no = "'.$search['mobile_no'].'"');
			}

			if(isset($search['operation_time']) &&  !empty($search['operation_time']))
			{
			$this->db->where('hms_operation_booking.operation_time = "'.$search['operation_time'].'"');
			}
			if(isset($search['operation_date']) &&  !empty($search['operation_date']))
			{
			$this->db->where('hms_operation_booking.operation_date = "'.date('Y-m-d',strtotime($search['operation_date'])).'"');
			}	
        
			}
			$emp_ids='';
        if($users_data['emp_id']>0)
        {
            if($users_data['record_access']=='1')
            {
                $emp_ids= $users_data['id'];
            }
        }
        elseif(!empty($search["employee"]) && is_numeric($search['employee']))
        {
            $emp_ids=  $search["employee"];
        }
        if(isset($emp_ids) && !empty($emp_ids))
        { 
            $this->db->where('hms_ipd_patient_to_charge.created_by IN ('.$emp_ids.')');
        }
			$result=$this->db->get()->result();
			//print_r($result);die;
			return $result;
	
		
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
    
   

	public function get_by_id($id="")
	{
		$this->db->select('hms_ipd_patient_to_charge.*'); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.id',$id);
		$query = $this->db->get(); 
		$result= $query->row_array();
	     return $result;
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		//print '<pre>'; print_r($post);die;
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'patient_id'=>$post['patient_id'],
					'ipd_id'=>$post['ipd_id'],
					'type'=>2,
					'particular'=>$post['particular'],//'Advance Payment',
					'payment_date'=>date('Y-m-d',strtotime($post['payment_date'])),
					'start_date'=>date('Y-m-d',strtotime($post['payment_date'])),
					'quantity'=>1,
					'price'=>$post['amount'],
					'panel_price'=>$post['amount'],
					'net_price'=>$post['amount'],
					'payment_mode'=>$post['payment_mode']
					//'bank_name'=>$bank_name,
					//'card_no'=>$transaction_no,
					//'cheque_no'=>$cheque_no,
					//'cheque_date'=>$cheque_date,
					//'transaction_no'=>$transaction_no
				 );
		
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    

			$this->db->where(array('id'=>$post['data_id']));
			$this->db->delete('hms_operation_to_doctors');
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_ipd_patient_to_charge',$data);  
			$advance_payment_id=$post['data_id'];



			   /*add sales banlk detail*/
				$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>10));
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
				'type'=>10,
				'section_id'=>3,
				'p_mode_id'=>$post['payment_mode'],
				'branch_id'=>$user_data['parent_id'],
				'parent_id'=>$post['data_id'],
				'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
				}

			/*add sales banlk detail*/
            $this->db->where('advance_payment_id',$post['data_id']);
            $this->db->where('parent_id',$post['ipd_id']);
			//$this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','5');
            //$this->db->where('balance>0');
            $this->db->where('patient_id',$post['patient_id']);
            $query_d_pay = $this->db->get('hms_payment');
            $row_d_pay = $query_d_pay->result();
            //  echo $this->db->last_query();die;
	
			/*$this->db->where('parent_id',$post['ipd_id']);
			$this->db->where('advance_payment_id',$post['data_id']);
			$this->db->where('section_id','5');
			$this->db->where('patient_id',$post['patient_id']);
			$this->db->delete('hms_payment'); 
			*/
			$comission_arr = get_doc_hos_comission($row_d_pay[0]->doctor_id,$row_d_pay[0]->hospital_id,$post['amount'],5);
            //print_r($row_d_pay);die;
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
								'parent_id'=>$post['ipd_id'],
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'5',  //'section_id'=>'3',
								'patient_id'=>$post['patient_id'], 
								'credit'=>'',
								'debit'=>$post['amount'],
								'type'=>4,
								'pay_mode'=>$post['payment_mode'],
								'advance_payment_id'=>$post['data_id'],
								'doctor_comission'=>$doctor_comission,
								'hospital_comission'=>$hospital_comission,
								'comission_type'=>$comission_type,
								'total_comission'=>$total_comission,
								//'bank_name'=>$bank_name,
								//'card_no'=>$transaction_no,
								//'cheque_no'=>$cheque_no,
								//'cheque_date'=>$cheque_date,
								//'transection_no'=>$transaction_no,
								'created_date'=>date('Y-m-d',strtotime($post['payment_date'])), //$row_d->created_date,//date('Y-m-d H:i:s'),
								'created_by'=>$user_data['id']
            	             );

			$this->db->where('parent_id',$post['ipd_id']);
			$this->db->where('advance_payment_id',$post['data_id']);
			$this->db->where('section_id','5');
			$this->db->where('patient_id',$post['patient_id']);

			$this->db->update('hms_payment',$payment_data); 
            //$this->db->insert('hms_payment',$payment_data);	
            /*$payment_id= $this->db->insert_id();
			//no need to update reciept
            if(!empty($row_d_pay))
            {
            	foreach($row_d_pay as $row_d)
            	{
            		$this->db->set('payment_id',$payment_id);
					$this->db->where('parent_id',$post['data_id']);
					$this->db->where('payment_id',$row_d->id);  
					$this->db->where('section_id',7);
					$this->db->update('hms_branch_hospital_no'); 
            	}
            }*/
            /*add sales banlk detail*/
				$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id'],'type'=>10,'section_id'=>4));
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
				'type'=>10,
				'section_id'=>4,
				'p_mode_id'=>$post['payment_mode'],
				'branch_id'=>$user_data['parent_id'],
				'parent_id'=>$post['data_id'],
				'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
				}

			/*add sales banlk detail*/	

		}
		else
		{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($post['payment_date'])));
			$this->db->insert('hms_ipd_patient_to_charge',$data); 
			$last_id= $this->db->insert_id();
			$advance_payment_id=$last_id;


        

		 if(!empty($post['field_name']))
			{  
			$counter_name= count($post_field_value_name);
		   $i = 0;
		 /* if(!empty($post['field_name']))
		  {

		  } */	
		  $f_i = 0;
          foreach($post['field_name'] as $fieldname) 
			{
			$data_field_value= array(
			'field_value'=>$fieldname,
			'field_id'=>$post['field_id'][$f_i],
			'type'=>10,
			'section_id'=>3,
			'p_mode_id'=>$post['payment_mode'],
			'branch_id'=>$user_data['parent_id'],
			'parent_id'=>$last_id,
			
			'ip_address'=>$_SERVER['REMOTE_ADDR']
			);

			
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($post['payment_date'])));
			$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);
	        }

	        }
			
		    //if(isset($post['transaction_no'])){ $transaction_no=$post['transaction_no']; }else{ $transaction_no=''; }  
            $this->db->where('parent_id',$post['ipd_id']);
			//$this->db->where('parent_id',$post['data_id']);
            $this->db->where('section_id','5');
            //$this->db->where('balance>0');
            $this->db->where('patient_id',$post['patient_id']);
            $this->db->where('(doctor_id>0 OR hospital_id>0)');
            $query_d_pay = $this->db->get('hms_payment');
            $row_d_pay = $query_d_pay->row_array();

	
			/*$this->db->where('parent_id',$post['ipd_id']);
			$this->db->where('advance_payment_id',$post['data_id']);
			$this->db->where('section_id','5');
			$this->db->where('patient_id',$post['patient_id']);
			$this->db->delete('hms_payment'); 
			*/
			$comission_arr = get_doc_hos_comission($row_d_pay['doctor_id'],$row_d_pay['hospital_id'],$post['amount'],5);
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
								'parent_id'=>$post['ipd_id'],
								'branch_id'=>$user_data['parent_id'],
								'section_id'=>'5',  //'section_id'=>'3',
								'patient_id'=>$post['patient_id'],
								'hospital_id'=>$row_d_pay['hospital_id'],
								'doctor_id'=>$row_d_pay['doctor_id'],
								'credit'=>'',
								'debit'=>$post['amount'],
								'type'=>4,
								'pay_mode'=>$post['payment_mode'],
								'advance_payment_id'=>$advance_payment_id,
								'doctor_comission'=>$doctor_comission,
								'hospital_comission'=>$hospital_comission,
								'comission_type'=>$comission_type,
								'total_comission'=>$total_comission,
								//'bank_name'=>$bank_name,
								//'card_no'=>$transaction_no,
								//'cheque_no'=>$cheque_no,
								//'cheque_date'=>$cheque_date,
								//'transection_no'=>$transaction_no,
								'created_date'=>date('Y-m-d H:i:s',strtotime($post['payment_date'])),
								'created_by'=>$user_data['id']
            	             );

			   $this->db->insert('hms_payment',$payment_data);
			   $payment_id = $this->db->insert_id();
			   if(in_array('218',$user_data['permission']['section']))
				{
		                      if($post['amount']>0)
		                       {
		           	 $hospital_receipt_no= check_hospital_receipt_no();
		           	 $data_receipt_data= array('branch_id'=>$user_data['parent_id'],
											'section_id'=>7,
											'parent_id'=>$post['ipd_id'],
											'payment_id'=>$payment_id,
											'reciept_prefix'=>$hospital_receipt_no['prefix'],
											'reciept_suffix'=>$hospital_receipt_no['suffix'],
											'created_by'=>$user_data['id'],
											'created_date'=>date('Y-m-d H:i:s',strtotime($post['payment_date']))
											);
			        $this->db->insert('hms_branch_hospital_no',$data_receipt_data);
		           }
		       	}


			   if(!empty($post['field_name']))
				{
				$post_field_value_name= $post['field_name'];
				$counter_name= count($post_field_value_name); 
				$f_i = 0;
				foreach($post['field_name'] as $fieldname) 
				{
				$data_field_value= array(
				'field_value'=>$fieldname,
				'field_id'=>$post['field_id'][$f_i],
				'type'=>10,
				'section_id'=>4,
				'p_mode_id'=>$post['payment_mode'],
				'branch_id'=>$user_data['parent_id'],
				'parent_id'=>$last_id,
				'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s',strtotime($post['payment_date'])));
				$this->db->insert('hms_payment_mode_field_value_acc_section',$data_field_value);

				}
				}

				$f_i++;
			     
		}


	return $advance_payment_id;	
   }

    public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->select('hms_ipd_patient_to_charge.id');
			$this->db->from('hms_ipd_patient_to_charge'); 
			$this->db->where('id',$id);
			$this->db->where('branch_id',$user_data['parent_id']);
			$query = $this->db->get(); 
            //echo $this->db->last_query();die;
			$result= $query->result();
			
			//delete payment
			if(!empty($result))
			{
				foreach ($result as  $value) 
				{
					
					//echo "<pre>"; print_r($value); exit;
					$this->db->select('hms_payment.id');
					$this->db->from('hms_payment'); 
					$this->db->where('advance_payment_id',$value->id);
					$this->db->where('branch_id',$user_data['parent_id']);
					$query_payment = $this->db->get(); 
		            //echo $this->db->last_query();die;
					$result_payment= $query_payment->result();
					if(!empty($result_payment))
					{
						foreach ($result_payment as  $pay_value) 
						{
							$this->db->where('payment_id',$pay_value->id);
							$this->db->where('branch_id',$user_data['parent_id']);
							$this->db->delete('hms_branch_hospital_no');
							//delete payment
							$this->db->where('id',$pay_value->id);
							$this->db->where('branch_id',$user_data['parent_id']);
							$this->db->delete('hms_payment');
				            
						}
					}

					$this->db->where('id',$value->id);
					$this->db->where('branch_id',$user_data['parent_id']);
					$this->db->delete('hms_ipd_patient_to_charge');

				}
			}
			//end payment

			/*$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_ipd_patient_to_charge');*/
			//echo $this->db->last_query();die;
    	}
    	/*if(!empty($id) && $id>0)
    	{

			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_ipd_patient_to_charge');
			//echo $this->db->last_query();die;
    	} */
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
			$this->db->update('hms_ipd_patient_to_charge');
			//echo $this->db->last_query();die;
    	} 
    }


      function get_all_detail_print($ids="",$branch_ids="")
      {
          //reciept_suffix
      	$result_operation=array();
    	$this->db->select('hms_ipd_patient_to_charge.*,hms_ipd_patient_to_charge.net_price as advance_payment,hms_ipd_booking.ipd_no,hms_ipd_booking.ipd_no,hms_ipd_booking.patient_type,hms_ipd_booking.admission_date,hms_patient.patient_code,hms_patient.simulation_id,hms_patient.patient_name,hms_patient.mobile_no,hms_patient.gender,hms_patient.age_m,hms_patient.address,hms_patient.pincode,hms_patient.age_y,hms_patient.age_d,hms_patient.address,hms_ipd_rooms.room_no,hms_simulation.simulation,hms_ipd_room_to_bad.bad_no,hms_ipd_room_to_bad.bad_name,hms_doctors.doctor_name,hms_ipd_room_category.room_category,hms_payment_mode.payment_mode,hms_payment.reciept_prefix,hms_payment.reciept_suffix,(CASE WHEN hms_users.emp_id>0 THEN hms_employees.name ELSE hms_branch.branch_name END) as user_name,hms_gardian_relation.relation,hms_patient.relation_name,hms_patient.relation_simulation_id,hms_patient.relation_name');
		$this->db->from('hms_ipd_patient_to_charge'); 

		

        $this->db->join('hms_payment','hms_payment.advance_payment_id = hms_ipd_patient_to_charge.id AND hms_payment.type=4','left');

        //$this->db->join('hms_branch_hospital_no','hms_branch_hospital_no.payment_id = hms_payment.id AND hms_branch_hospital_no.section_id=7 AND hms_branch_hospital_no.branch_id='.$branch_ids,'left');
		$this->db->where('hms_ipd_patient_to_charge.id',$ids);
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$branch_ids);
		$this->db->join('hms_ipd_booking','hms_ipd_booking.id=hms_ipd_patient_to_charge.ipd_id','left');
		$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
		$this->db->join('hms_payment_mode','hms_payment_mode.id=hms_ipd_patient_to_charge.payment_mode','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_patient_to_charge.patient_id','left');
		
		$this->db->join('hms_gardian_relation','hms_gardian_relation.id=hms_patient.relation_type','left');
		
		$this->db->join('hms_simulation','hms_simulation.id = hms_patient.simulation_id','left');
		$this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id','left');
		$this->db->join('hms_ipd_room_category','hms_ipd_room_category.id = hms_ipd_booking.room_type_id','left');
		$this->db->join('hms_users','hms_users.id = hms_ipd_booking.created_by'); 
		$this->db->join('hms_employees','hms_employees.id=hms_users.emp_id','left');
		 $this->db->join('hms_branch','hms_branch.users_id=hms_users.id','left');
		//$this->db->where('hms_ipd_patient_to_charge.is_deleted','0');
		$query = $this->db->get(); 
        //echo $this->db->last_query();die;
		$result_operation['ipd_list']= $query->result();
		return $result_operation;
		
    }
     function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_print_branch_template.*');
    	$this->db->where($data);
    	//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
    	$this->db->from('hms_print_branch_template');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }

    function payment_mode_detail_according_to_field($p_mode_id="",$parent_id="")
	{
		//echo $p_mode_id;
		$users_data = $this->session->userdata('auth_users'); 
		$this->db->select('hms_payment_mode_field_value_acc_section.*,hms_payment_mode_to_field.field_name');
		$this->db->join('hms_payment_mode_to_field','hms_payment_mode_to_field.id=hms_payment_mode_field_value_acc_section.field_id');

		$this->db->where('hms_payment_mode_field_value_acc_section.p_mode_id',$p_mode_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.branch_id',$users_data['parent_id']);
		$this->db->where('hms_payment_mode_field_value_acc_section.type',10);
		$this->db->where('hms_payment_mode_field_value_acc_section.parent_id',$parent_id);
		$this->db->where('hms_payment_mode_field_value_acc_section.section_id',3);
		$query= $this->db->get('hms_payment_mode_field_value_acc_section')->result();
       // echo $this->db->last_query(); 
		return $query;
	}
	
	function get_payment_reciept_no($ids='')
    {
        $r_num='';
        if(!empty($ids))
        {
            
        
    	$sql = "SELECT  h_no.reciept_prefix,h_no.reciept_suffix FROM `hms_ipd_patient_to_charge` as p_charge join hms_payment on hms_payment.advance_payment_id = p_charge.id AND hms_payment.section_id=5 AND hms_payment.type=4 JOIN hms_branch_hospital_no as h_no on h_no.payment_id=hms_payment.id AND h_no.section_id=7 WHERE p_charge.id=".$ids;

    	 $query = $this->db->query($sql);
		 $data = $query->result();
		 
		 if(!empty($data))
		 {
		   //  echo "<pre>"; print_r($data); exit;
		 	$reciept_prefix = $data[0]->reciept_prefix; 
		 	$reciept_suffix = $data[0]->reciept_suffix; 
		 	$r_num = $reciept_prefix.$reciept_suffix;
		 }
        }
		 
		  return $r_num;

    }

}
?>