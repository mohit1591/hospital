<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ipd_running_bill_model extends CI_Model 
{
	var $table = 'hms_ipd_booking';
	var $column = array('hms_ipd_booking.id','hms_patient.patient_code','hms_ipd_booking.ipd_no', 'hms_patient.patient_name','hms_ipd_booking.admission_date', 'hms_ipd_booking.admission_time','hms_doctors.doctor_name','hms_ipd_rooms.room_no','hms_ipd_room_to_bad.bad_no','hms_specialization.specialization');  
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$users_data = $this->session->userdata('auth_users');
		$parent_branch_details = $this->session->userdata('parent_branches_data');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
		$search = $this->session->userdata('running_bill_serach');

		$this->db->select("hms_ipd_booking.*,hms_ipd_booking.id as ipd_book_id,hms_ipd_booking.patient_id as ipd_patient_id,hms_ipd_booking.patient_id as p_id,hms_patient.patient_name,hms_patient.patient_code,hms_doctors.doctor_name,hms_specialization.specialization,hms_ipd_rooms.room_no,hms_ipd_room_to_bad.bad_no"); 
		$this->db->join('hms_patient','hms_patient.id=hms_ipd_booking.patient_id');
		$this->db->join('hms_doctors','hms_doctors.id=hms_ipd_booking.attend_doctor_id');
		$this->db->join('hms_ipd_rooms','hms_ipd_rooms.id=hms_ipd_booking.room_id','left');
		$this->db->join('hms_ipd_room_to_bad','hms_ipd_room_to_bad.id=hms_ipd_booking.bad_id','left');
		$this->db->join('hms_specialization','hms_specialization.id=hms_doctors.specilization_id');
		$this->db->where('hms_patient.is_deleted','0');
			$this->db->where('hms_ipd_booking.is_deleted','0'); 

		$this->db->where('hms_ipd_booking.discharge_status','0'); 
	
            
       
        if(isset($search['branch_id']) && $search['branch_id']!=''){
		$this->db->where('hms_patient.branch_id IN ('.$search['branch_id'].')');
		}else{
		$this->db->where('hms_patient.branch_id',$users_data['parent_id']);
		}
		/////// Search query start //////////////

		if(isset($search) && !empty($search))
		{
			//print_r($search['search_criteria']);die;
            /*if(isset($search['start_date']) && !empty($search['start_date']))
			{
				$start_date = date('Y-m-d',strtotime($search['start_date'])).' 00:00:00';
				$this->db->where('hms_patient.created_date >= "'.$start_date.'"');
			}

			if(isset($search['end_date']) && !empty($search['end_date']))
			{
				$end_date = date('Y-m-d',strtotime($search['end_date'])).' 23:59:59';
				$this->db->where('hms_patient.created_date <= "'.$end_date.'"');
			}*/

			if(isset($search['search_criteria']) && !empty($search['search_criteria']) && $search['search_criteria']==3)
			{
				$this->db->where('hms_ipd_booking.patient_type',1);
			}
			if(isset($search['search_criteria']) && !empty($search['search_criteria']) && $search['search_criteria']==2)
			{
				$this->db->where('hms_ipd_booking.patient_type',2);
			}

			if(isset($search['patient_name']) && !empty($search['patient_name']))
			{
				$this->db->where('hms_patient.patient_name',$search['patient_name']);
			}
			if(isset($search['mobile_no']) && !empty($search['mobile_no']))
			{
				$this->db->where('hms_patient.mobile_no',$search['mobile_no']);
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
            $this->db->where('hms_ipd_booking.created_by IN ('.$emp_ids.')');
        }
		/////// Search query end //////////////

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

	function save_running_data(){
			$post =$this->input->post();
			if(!empty($post['data_id']) && isset($post['data_id'])){
			$update_data= array(
				'end_date'=>date('Y-m-d',strtotime($post['end_date']))
				);
			$this->db->where(array('id'=>$post['data_id']));
			$this->db->update('hms_ipd_patient_to_charge',$update_data);
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
	//function get_running_bill_info_datatables($book_id="",$patient_id="")
   // {
    	
	    //$this->_get_running_bill_info_datatables_query($book_id,$patient_id);
	   // if($_POST['length'] != -1)
	    //$this->db->limit($_POST['length'], $_POST['start']);
	    /*if(!empty($medicine_id) && !empty($batch_no))
	    {*/
	    //$query = $this->db->get(); 
	   // echo $this->db->last_query();die;
	   // return $query->result();
	    /*}
	    else
	    {
	    	$result = array();
	    	return $result;
	    }*/
   // }

public  function get_running_bill_info_datatables_bkp_04_12_2017($book_id="0",$patient_id="0")
    {
		
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_patient_to_charge.*");
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.type NOT IN (2,7,8)');
		$this->db->where('hms_ipd_patient_to_charge.type!=',2);
		//$this->db->where('hms_ipd_patient_to_charge.type!=',5);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		//$this->db->group_by('hms_ipd_patient_to_charge.start_date');
		 $res= $this->db->get()->result();
		 $data['CHARGES']='';
		 $data['advance_payment']='';
		 $res = json_decode(json_encode($res), true);
		 foreach($res as $data_new){
		 	
		 	if($data_new['start_date']!='0000-00-00 00:00:00' && $data_new['end_date']!='0000-00-00 00:00:00' && $data_new['type']!=1  && $data_new['type']!=5)
		 	{
				$date1 = new DateTime(date('Y-m-d',strtotime($data_new['start_date'])));
				$date2 = new DateTime(date('Y-m-d',strtotime($data_new['end_date'])));
				$date2->modify("+1 days");
				$interval = $date1->diff($date2);
				$days= $interval->days;
				if($data_new['type']!=1)
				{
				for($i=0;$i<$days;$i++)
				{ //print_r($data_new);die; 
                     
					if(!empty($data_new['end_date']) && $data_new['end_date']!='')
					{
					$data_new['start_date'] = date('Y-m-d', strtotime($data_new['end_date'])-($i*86400)); 
					}
					else
					{
					$data_new['start_date'] = date('Y-m-d'); 
					}
				/* start _date query */
					//$data_new['end_date'] = date('Y-m-d', strtotime($data_new['end_date'])-($i*86400)); 
					// $data_new->end_date = date('Y-m-d', strtotime('-'.$i.' day', strtotime($data_new->start_date))); 
					//echo $data_new->start_date;die;


					//$data_new->end_date = date('Y-m-d', strtotime('-'.$i.' day', $data_new->start_date));

				/* start date query */
					 
					$data['CHARGES'][]=$data_new;
				} 
				
				//(object) $array
				
			}
				
				}
		 	else
		 	{
		 		 
		 	    $date1 = new DateTime(date('Y-m-d',strtotime($data_new['start_date'])));
		 	    
				$date2 = new DateTime(date('Y-m-d'));
				$date2->modify("+1 days");
				$interval = $date1->diff($date2);
				$days= $interval->days;
			     if($data_new['type']!=1  && $data_new['type']!=5)
			     {
					for($i=1;$i<=$days;$i++)
					{
					$data['CHARGES'][]=$data_new;
					}
			     }
			     else
			     {
			     	$data['CHARGES'][]=$data_new;
			     }

			 }

		 }
		$this->db->select("hms_ipd_patient_to_charge.branch_id, hms_ipd_patient_to_charge.ipd_id, hms_ipd_patient_to_charge.id, hms_ipd_patient_to_charge.type, hms_ipd_patient_to_charge.particular, hms_ipd_patient_to_charge.start_date, hms_ipd_patient_to_charge.end_date, (CASE WHEN hms_ipd_patient_to_charge.quantity >= 0 THEN 0 END) as quantity, (CASE WHEN hms_ipd_patient_to_charge.price >= 0 THEN '0.00' END) as price, sum(hms_ipd_patient_to_charge.net_price) as net_price"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',2); 
		$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['advance_payment']= $this->db->get()->result(); 


		// $this->db->select("hms_ipd_patient_to_charge.branch_id, hms_ipd_patient_to_charge.ipd_id, hms_ipd_patient_to_charge.id, hms_ipd_patient_to_charge.type, hms_ipd_patient_to_charge.particular, hms_ipd_patient_to_charge.start_date, hms_ipd_patient_to_charge.end_date, (CASE WHEN hms_ipd_patient_to_charge.quantity >= 0 THEN 0 END) as quantity, (CASE WHEN hms_ipd_patient_to_charge.price >= 0 THEN '0.00' END) as price, sum(hms_ipd_patient_to_charge.net_price) as net_price"); 
		// $this->db->from('hms_ipd_patient_to_charge');
		// $this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		// $this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		// $this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		// $this->db->where('hms_ipd_patient_to_charge.type',5); 
		// $this->db->group_by('hms_ipd_patient_to_charge.type');

		/*$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_patient_to_charge.*");
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.type NOT IN (2,7,8)');
		//$this->db->where('hms_ipd_patient_to_charge.type!=',2);
		$this->db->where('hms_ipd_patient_to_charge.type=',5);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);



		$data['particluar_charge_entry']= $this->db->get()->result(); */

		//print '<pre>';print_r($data['particluar_charge_entry']);die;
		
		$this->db->select("*"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',8); 
		//$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['medicine_payment']= $this->db->get()->result(); 
		//echo $this->db->last_query(); 
		$this->db->select("*"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',7); 
		//$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['pathology_payment']= $this->db->get()->result();
		return $data;
		
    }

	 public  function get_running_bill_info_datatables($book_id="0",$patient_id="0")
    {
		
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_patient_to_charge.*");
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.type NOT IN (2,7,8)');
		$this->db->where('hms_ipd_patient_to_charge.type!=',2);
		//$this->db->where('hms_ipd_patient_to_charge.type!=',5);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->order_by('hms_ipd_patient_to_charge.start_date','ASC');
		//$this->db->group_by('hms_ipd_patient_to_charge.start_date');
		 $res= $this->db->get()->result();
		 $data['CHARGES']='';
		 $data['advance_payment']='';
		 $res = json_decode(json_encode($res), true);
		 foreach($res as $data_new){
		 	
		 	if($data_new['start_date']!='0000-00-00 00:00:00' && $data_new['end_date']!='0000-00-00 00:00:00' && $data_new['type']!=1 && $data_new['type']!=5)
		 	{

				$date1 = new DateTime(date('Y-m-d',strtotime($data_new['start_date'])));
				$date2 = new DateTime(date('Y-m-d',strtotime($data_new['end_date'])));
				$date2->modify("+1 days");
				$interval = $date1->diff($date2);
				$days= $interval->days;

				if($data_new['type']!=1)
				{
				for($i=0;$i<$days;$i++)
				{ //print_r($data_new);die; 
                     
					if(!empty($data_new['end_date']) && $data_new['end_date']!='')
					{
					$data_new['start_date'] = date('Y-m-d', strtotime($data_new['end_date'])-($i*86400)); 
					}
					else
					{
					$data_new['start_date'] = date('Y-m-d'); 
					}
				/* start _date query */
					//$data_new['end_date'] = date('Y-m-d', strtotime($data_new['end_date'])-($i*86400)); 
					// $data_new->end_date = date('Y-m-d', strtotime('-'.$i.' day', strtotime($data_new->start_date))); 
					//echo $data_new->start_date;die;


					//$data_new->end_date = date('Y-m-d', strtotime('-'.$i.' day', $data_new->start_date));

				/* start date query */
					 
					$data['CHARGES'][]=$data_new;
				} 
				
				//(object) $array
				
			}
				
				}
		 	else
		 	{
		 		
		 	    $date1 = new DateTime(date('Y-m-d',strtotime($data_new['start_date'])));
		 	    
				$date2 = new DateTime(date('Y-m-d'));
				$date2->modify("+1 days");
				$interval = $date1->diff($date2);
				$days= $interval->days;

			     if($data_new['type']!=1 && $data_new['type']!=5)
			     {

					for($i=0;$i<$days;$i++)
					{
						
								if(!empty($data_new['start_date']) && $data_new['start_date']!='')
								{
									$data_new['start_date'] = date('Y-m-d', strtotime(date('Y-m-d'))-($i*86400));
									$data_new['end_date'] =date('Y-m-d', strtotime(date('Y-m-d'))-($i*86400));
								}
								else
								{
									$data_new['start_date'] = date('Y-m-d'); 
								} 


					   $data['CHARGES'][]=$data_new;
					}
			     }
			     else
			     {   
			     	$data['CHARGES'][]=$data_new;

				 }

			 }

		 }
		//$this->array_sort_by_column($data['CHARGES'], 'start_date');
	//print '<pre>';  print_r($data['CHARGES']);die;
		$this->db->select("hms_ipd_patient_to_charge.branch_id, hms_ipd_patient_to_charge.ipd_id, hms_ipd_patient_to_charge.id, hms_ipd_patient_to_charge.type, hms_ipd_patient_to_charge.particular, hms_ipd_patient_to_charge.start_date, hms_ipd_patient_to_charge.end_date, (CASE WHEN hms_ipd_patient_to_charge.quantity >= 0 THEN 0 END) as quantity, (CASE WHEN hms_ipd_patient_to_charge.price >= 0 THEN '0.00' END) as price, sum(hms_ipd_patient_to_charge.net_price) as net_price"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',2); 
		$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['advance_payment']= $this->db->get()->result(); 


		// $this->db->select("hms_ipd_patient_to_charge.branch_id, hms_ipd_patient_to_charge.ipd_id, hms_ipd_patient_to_charge.id, hms_ipd_patient_to_charge.type, hms_ipd_patient_to_charge.particular, hms_ipd_patient_to_charge.start_date, hms_ipd_patient_to_charge.end_date, (CASE WHEN hms_ipd_patient_to_charge.quantity >= 0 THEN 0 END) as quantity, (CASE WHEN hms_ipd_patient_to_charge.price >= 0 THEN '0.00' END) as price, sum(hms_ipd_patient_to_charge.net_price) as net_price"); 
		// $this->db->from('hms_ipd_patient_to_charge');
		// $this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		// $this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		// $this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		// $this->db->where('hms_ipd_patient_to_charge.type',5); 
		// $this->db->group_by('hms_ipd_patient_to_charge.type');

	   /*$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_patient_to_charge.*");
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.type NOT IN (2,7,8)');
		//$this->db->where('hms_ipd_patient_to_charge.type!=',2);
		$this->db->where('hms_ipd_patient_to_charge.type=',5);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);

		$data['particluar_charge_entry']= $this->db->get()->result(); 

		*/

		//print '<pre>';print_r($data['particluar_charge_entry']);die;
		
		$this->db->select("*"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',8); 
		//$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['medicine_payment']= $this->db->get()->result(); 
		//echo $this->db->last_query(); 
		$this->db->select("*"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',7); 
		//$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['pathology_payment']= $this->db->get()->result();
		return $data;
		
    }

	 public  function get_running_bill_info_datatables_20171201($book_id="0",$patient_id="0")
    {
		
		$users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_ipd_patient_to_charge.*");
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.type NOT IN (2,7,8)');
		$this->db->where('hms_ipd_patient_to_charge.type!=',2);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.is_deleted',0);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		//$this->db->group_by('hms_ipd_patient_to_charge.start_date');
		 $res= $this->db->get()->result();
		 $data['CHARGES']='';
		 $data['advance_payment']='';
		 $res = json_decode(json_encode($res), true);
		 foreach($res as $data_new){
		 	
		 	if($data_new['start_date']!='0000-00-00 00:00:00' && $data_new['end_date']!='0000-00-00 00:00:00' && $data_new['type']!=1)
		 	{
				$date1 = new DateTime(date('Y-m-d',strtotime($data_new['start_date'])));
				$date2 = new DateTime(date('Y-m-d',strtotime($data_new['end_date'])));
				$date2->modify("+1 days");
				$interval = $date1->diff($date2);
				$days= $interval->days;
				if($data_new['type']!=1)
				{
				for($i=0;$i<$days;$i++)
				{ //print_r($data_new);die; 
                     
					if(!empty($data_new['end_date']) && $data_new['end_date']!='')
					{
					$data_new['start_date'] = date('Y-m-d', strtotime($data_new['end_date'])-($i*86400)); 
					}
					else
					{
					$data_new['start_date'] = date('Y-m-d'); 
					}
				/* start _date query */
					//$data_new['end_date'] = date('Y-m-d', strtotime($data_new['end_date'])-($i*86400)); 
					// $data_new->end_date = date('Y-m-d', strtotime('-'.$i.' day', strtotime($data_new->start_date))); 
					//echo $data_new->start_date;die;


					//$data_new->end_date = date('Y-m-d', strtotime('-'.$i.' day', $data_new->start_date));

				/* start date query */
					 
					$data['CHARGES'][]=$data_new;
				} 
				
				//(object) $array
				
			}
				
				}
		 	else
		 	{
		 		 
		 	    $date1 = new DateTime(date('Y-m-d',strtotime($data_new['start_date'])));
		 	    
				$date2 = new DateTime(date('Y-m-d'));
				$date2->modify("+1 days");
				$interval = $date1->diff($date2);
				$days= $interval->days;
			     if($data_new['type']!=1)
			     {
					for($i=1;$i<=$days;$i++)
					{
					$data['CHARGES'][]=$data_new;
					}
			     }
			     else
			     {
			     	$data['CHARGES'][]=$data_new;
			     }

			 }

		 }
		$this->db->select("hms_ipd_patient_to_charge.branch_id, hms_ipd_patient_to_charge.ipd_id, hms_ipd_patient_to_charge.id, hms_ipd_patient_to_charge.type, hms_ipd_patient_to_charge.particular, hms_ipd_patient_to_charge.start_date, hms_ipd_patient_to_charge.end_date, (CASE WHEN hms_ipd_patient_to_charge.quantity >= 0 THEN 0 END) as quantity, (CASE WHEN hms_ipd_patient_to_charge.price >= 0 THEN '0.00' END) as price, sum(hms_ipd_patient_to_charge.net_price) as net_price"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',2); 
		$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['advance_payment']= $this->db->get()->result(); 
		
		$this->db->select("*"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',8); 
		//$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['medicine_payment']= $this->db->get()->result(); 
		//echo $this->db->last_query(); 
		$this->db->select("*"); 
		$this->db->from('hms_ipd_patient_to_charge');
		$this->db->where('hms_ipd_patient_to_charge.branch_id',$users_data['parent_id']);
		$this->db->where('hms_ipd_patient_to_charge.ipd_id',$book_id);
		$this->db->where('hms_ipd_patient_to_charge.patient_id',$patient_id);
		$this->db->where('hms_ipd_patient_to_charge.type',7); 
		//$this->db->group_by('hms_ipd_patient_to_charge.type');
		$data['pathology_payment']= $this->db->get()->result();
		return $data;
		
    }

    


    /*function get_running_bill_info_count_filtered($book_id="",$patient_id='')
	{
		$this->_get_running_bill_info_datatables_query($book_id,$patient_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_running_bill_info_count_all($book_id="",$patient_id='')
	{
		$this->_get_running_bill_info_datatables_query($book_id,$patient_id);
		$query = $this->db->get();
		return $query->num_rows();

	}*/

	public function delete($id="")
    {
    	if(!empty($id) && $id>0)
    	{
			$user_data = $this->session->userdata('auth_users');
			$this->db->set('is_deleted',1);
			$this->db->set('deleted_by',$user_data['id']);
			$this->db->set('deleted_date',date('Y-m-d H:i:s'));
			$this->db->where('id',$id);
			$this->db->update('hms_ipd_patient_to_charge');
    	} 
    }

     function template_format($data=""){
    	$users_data = $this->session->userdata('auth_users'); 
    	$this->db->select('hms_ipd_branch_running_bill_print_setting.*');
    	$this->db->where($data);
    	//$this->db->where('branch_id  IN ('.$users_data['parent_id'].')'); 
        $this->db->where('branch_id',$users_data['parent_id']); 
    	$this->db->from('hms_ipd_branch_running_bill_print_setting');
    	$query=$this->db->get()->row();
    	//print_r($query);exit;
    	return $query;

    }

} 
?>