<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_schedule_management_model extends CI_Model {

	var $table = 'hms_dialysis_schedule';
	var $column = array('hms_dialysis_schedule.id','hms_dialysis_schedule.name','hms_dialysis_schedule.type','hms_dialysis_schedule.hours','hms_dialysis_schedule.amount','hms_dialysis_schedule.status','hms_dialysis_schedule.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	public function save($filename="")
	{  
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		
		$data = array(  
							
							"schedule_name"=>$post['schedule_name'],
							"status"=>$post['status'],
							'per_patient_timing'=>$post['per_patient_timing'], 
							); 
         
		if(!empty($post['data_id']) && $post['data_id']>0)
		{     
		
		
			$this->db->where('id',$post['data_id']);
			$this->db->update('hms_dialysis_schedule',$data); 
			$schedule_id = $post['data_id'];
			

            if(!empty($post['data_id']))
			{
				$this->db->where('doctor_id',$post['data_id']);
				$this->db->where("branch_id",$user_data['parent_id']);
		        $this->db->delete('hms_dialysis_schedule_time');
		        //echo "dsd"; exit;
		    }
		    //echo count($post['time']);    
			if(!empty($post['time']))
			{
				
				//echo $this->db->last_query(); exit;
				$n=0;
				foreach($post['time'] as $key=>$val)
	            {
	            	//echo '<pre>'; print_r($val); exit; 
	            	//$to = $val['to'][$n]; 
					//$from = $val['from'][$n];
						$k=0;
	            		foreach ($val['from'] as $value) 
	            		{
	            			$to = $val['to'][$k]; 
							$from = $value;
	            			$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "schedule_id"=>$post['data_id'],
	                               "available_day"=>$key,
	                               "from_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$from)),
	                               "to_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$to))
	                             );
	            			$this->db->insert('hms_dialysis_schedule_time',$data);	
	            			$k++;
	            		}	
	            	
		            
	            	$n++;
	            	//echo $this->db->last_query(); 
	            }
	        }


		}
		else
		{    
			
			$this->db->set('branch_id',$user_data['parent_id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_schedule',$data); 
			$data_id = $this->db->insert_id();
			$schedule_id = $data_id;

			
			if(!empty($post['time']))
			{
				
				//echo $this->db->last_query(); exit;
				$n=0;
				foreach($post['time'] as $key=>$val)
	            {
	            		$k=0;
	            		foreach ($val['from'] as $value) 
	            		{
	            			$to = $val['to'][$k]; 
							$from = $value;
	            			$data = array(
	                               "branch_id"=>$user_data['parent_id'],
	                               "schedule_id"=>$schedule_id,
	                               "available_day"=>$key,
	                               "from_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$from)),
	                               "to_time"=>date('H:i:s', strtotime(date('d-m-Y').' '.$to))
	                             );
	            			$this->db->insert('hms_dialysis_schedule_time',$data);	
	            		}	
	            	
		            
	            	$n++;
	            	//echo $this->db->last_query(); 
	            }
	        } 

		}
		return $schedule_id;	 	
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dialysis_schedule.*,"); 
		$this->db->from($this->table); 
		$this->db->where('hms_dialysis_schedule.is_deleted','0');
        $this->db->where('hms_dialysis_schedule.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function Dialysis_pacakge_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('name','ASC'); 
    	$query = $this->db->get('hms_dialysis_schedule');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_dialysis_schedule.*');
		$this->db->from('hms_dialysis_schedule'); 
		$this->db->where('hms_dialysis_schedule.id',$id);
		$this->db->where('hms_dialysis_schedule.is_deleted','0');
		$query = $this->db->get(); 
		$result = $query->row_array();
		
		$this->db->select('hms_dialysis_schedule_time.*,hms_days.day_name');
		$this->db->join('hms_dialysis_schedule','hms_dialysis_schedule.id = hms_dialysis_schedule_time.schedule_id');
		$this->db->join('hms_days','hms_days.id = hms_dialysis_schedule_time.available_day'); 
		$this->db->where('hms_dialysis_schedule_time.schedule_id = "'.$id.'"');
		$this->db->from('hms_dialysis_schedule_time');
		$result['schedule_availablity']=$this->db->get()->result();

		return $result;
		
	}
	
	
	public function type_list()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('*');
		$this->db->where('branch_id',$user_data['parent_id']);
		$this->db->where('status',1); 
		$this->db->where('is_deleted',0); 
		$this->db->order_by('dialysis_type','ASC'); 
		$query = $this->db->get('hms_dialysis_type');
		$result = $query->result(); 
		return $result;
	}
	function check_dialysis_type($ot_pacakge_type_id="")
	{
		$this->db->select('hms_dialysis_type.dialysis_type,hms_dialysis_type.id');
		$this->db->from('hms_dialysis_type'); 
		$this->db->where('hms_dialysis_type.id',$ot_pacakge_type_id);
		$this->db->where('hms_dialysis_type.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
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
			$this->db->update('hms_dialysis_schedule');
			//echo $this->db->last_query();die;
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
			$this->db->update('hms_dialysis_schedule');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_vals($vals="")
    {
    	$response = '';
    	if(!empty($vals))
    	{
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('dialysis_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('dialysis_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_dialysis_type');
	        $result = $query->result(); 
	       // echo $this->db->last_query();die;
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	               $response[] = $vals->dialysis_type.'|'.$vals->id;
	        	}
	        }
	        return $response; 
    	} 
    }
    
     public function save_all_dialysis_management($management_all_data = array())
	{
	
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($management_all_data))
        {
            foreach($management_all_data as $management_data)
            {
            	//print_r($doctor_data);
            	if(!empty($management_data['name']))
            	{

						$type='';
					if(!empty($management_data['type']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_dialysis_type.*");
					    $this->db->from('hms_dialysis_type'); 
		                $this->db->where('LOWER(hms_dialysis_type.dialysis_type)',strtolower($management_data['type'])); 
		                $this->db->where('hms_dialysis_type.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $management_data_get = $query->result_array();

					    if(!empty($management_data_get))
					    {
						    $type = $management_data_get[0]['id'];
					    }
					    else
					    {
						 	$type_insert_data = array(
							'dialysis_type'=>$management_data['type'],
							'branch_id'=>$users_data['parent_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_dialysis_type',$type_insert_data);
							$type = $this->db->insert_id();
					    }
					}
				
          
				$insurance_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'name'=>$management_data['name'],
	                    'type'=>$type,
	                    'hours'=>$management_data['hours'],
	                    'amount'=>$management_data['amount'],
						'status'=>1,					
	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );

                //print_r($vaccination_data_array);
				    $this->db->insert('hms_dialysis_schedule',$insurance_data_array);
	                //echo $this->db->last_query(); exit;
	            }
            }   	
        }
	}

}
?>