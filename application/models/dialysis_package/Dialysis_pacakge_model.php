<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_pacakge_model extends CI_Model {

	var $table = 'hms_dialysis_pacakge';
	var $column = array('hms_dialysis_pacakge.id','hms_dialysis_pacakge.name','hms_dialysis_pacakge.type','hms_dialysis_pacakge.days','hms_dialysis_pacakge.amount','hms_dialysis_pacakge.remarks','hms_dialysis_pacakge.status');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dialysis_pacakge.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_dialysis_pacakge.is_deleted','0');
        $this->db->where('hms_dialysis_pacakge.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function dialysis_pacakge_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('name','ASC'); 
    	$query = $this->db->get('hms_dialysis_pacakge');
		return $query->result();
    }

	public function get_by_id($id)
	{  
		$this->db->select("hms_dialysis_pacakge.*, hms_dialysis_type.dialysis_type");
		$this->db->from('hms_dialysis_pacakge'); 
		$this->db->where('hms_dialysis_pacakge.id',$id);
		$this->db->join('hms_dialysis_type','hms_dialysis_type.id=hms_dialysis_pacakge.type','left');
		$this->db->where('hms_dialysis_pacakge.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	/*public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$post['type'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_dialysis_pacakge',$data);  
		}
		else{ 
			if(!empty($post['ot_type_id']))
			{
				//echo "hi";die;
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$post['ot_type_id'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
				//print_r($data);die;
			}
			else
			{
				//echo "hi";die;
				 $operation_array = array( 
					'branch_id'=>$user_data['parent_id'],
					'operation_type'=>$post['type'],
					'is_deleted'=>0,
					'deleted_by'=>$user_data['id'],
					'created_date'=>date('Y-m-d H:i:s'),
					'created_by'=>$user_data['id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		         //print_r($operation_array);die;
				$this->db->insert('hms_dialysis_type',$operation_array);
				$ot_type_id=$this->db->insert_id();
				$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$ot_type_id,
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
			}

			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_pacakge',$data);               
		} 	
	}

*/

	public function save()
	{
		//echo "hi";die;
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		//print_r($post);die;

		if(!empty($post['data_id']) && $post['data_id']>0)
		{   
			//echo "hi";die;
			$type_data= $this->check_dialysis_type($post['dialysis_pacakge_type_id']);
			//print_r($type_data);die;
			if(!empty($type_data))
			{
			 $data = array(

			 	   'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
				   'type'=>$type_data['id'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
			         ); 
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_dialysis_pacakge',$data);  
			}
			else
			{
				$data_op_type = array( 
					'branch_id'=>$user_data['parent_id'],
					'dialysis_type'=>$post['ot_pacakge_type_id'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
					);
				$this->db->insert('hms_dialysis_type',$data_op_type);
				$last_insert_id=$this->db->insert_id();
				 $data = array(

				 	 'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
				    'type'=>$last_insert_id,
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
			         ); 
	            $this->db->set('modified_by',$user_data['id']);
				$this->db->set('modified_date',date('Y-m-d H:i:s'));
	            $this->db->where('id',$post['data_id']);
				$this->db->update('hms_dialysis_pacakge',$data); 	
			}
		}
		else
		{    

			 $type_data= $this->check_dialysis_type($post['dialysis_pacakge_type_id']);
                
			if(!empty($type_data))
			{
				$data = array(

                   'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'type'=>$type_data['id'],
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_dialysis_pacakge',$data); 
			}
			else
			{

				$data_op_type = array( 
				'branch_id'=>$user_data['parent_id'],
				'dialysis_type'=>$post['dialysis_pacakge_type_id'],
				'status'=>$post['status'],
				'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				 $this->db->insert('hms_dialysis_type',$data_op_type);
				 $last_insert_id=$this->db->insert_id();
				 $data = array(
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
				    'type'=>$last_insert_id,
					'days'=>$post['days'],
					'hours'=>$post['hours'],
					'amount'=>$post['amount'],
					'remarks'=>$post['remarks'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				$this->db->set('created_by',$user_data['id']);
				$this->db->set('created_date',date('Y-m-d H:i:s'));
				$this->db->insert('hms_dialysis_pacakge',$data); 
			}
			
			//echo $this->db->last_query();die;              
		} 	
	}

	function check_dialysis_type($dialysis_pacakge_type_id="")
	{
		
		$this->db->select('hms_dialysis_type.dialysis_type,hms_dialysis_type.id');
		$this->db->from('hms_dialysis_type'); 
		$this->db->where('hms_dialysis_type.id',$dialysis_pacakge_type_id);
		$this->db->where('hms_dialysis_type.is_deleted','0');
		$query = $this->db->get(); 
		// echo $this->db->last_query();die;
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
			$this->db->update('hms_dialysis_pacakge');
			//echo $this->db->last_query();die;
    	} 
    }
   public function check_type($type)
     {
       if(!empty($type))
    	{

		$this->db->select('hms_dialysis_type.*');
		$this->db->from('hms_dialysis_type'); 
		$this->db->where('hms_dialysis_pacakge.id',$id);
		$this->db->where('hms_dialysis_pacakge.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
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
			$this->db->update('hms_dialysis_pacakge');
			//echo $this->db->last_query();die;
    	} 
    }

    public function get_vals($vals="")
    { 
    	//echo "hi";die;
    	//echo $vals;die;
    	$response = '';
    	if(!empty($vals))
    	{ //echo "hi";die;
            $users_data = $this->session->userdata('auth_users'); 
	        $this->db->select('*');  
	        $this->db->where('status','1'); 
	        $this->db->order_by('operation_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('operation_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_dialysis_type');
	        $result = $query->result(); 
	      // echo $this->db->last_query();
	      // print_r($result);die;
	        if(!empty($result))
	        { 
	        	foreach($result as $vals)
	        	{
	              // $response[] = $vals->operation_type.'|'.$vals->id;
	               $response[] = $vals->operation_type;
	        	}
	        }
	        return $response; 
    	} 
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
    
    public function save_all_dialysis_pacakge($pacakge_all_data = array())
	{
	
 		$users_data = $this->session->userdata('auth_users');
        if(!empty($pacakge_all_data))
        {
            foreach($pacakge_all_data as $pacakge_data)
            {
            	//print_r($doctor_data);
            	if(!empty($pacakge_data['name']))
            	{

						$type='';
					if(!empty($pacakge_data['type']))
            		{
            			//echo "hello"; print_r($doctor_data['specialization']);
		            	$this->db->select("hms_dialysis_type.*");
					    $this->db->from('hms_dialysis_type'); 
		                $this->db->where('LOWER(hms_dialysis_type.dialysis_type)',strtolower($pacakge_data['type'])); 
		                $this->db->where('hms_dialysis_type.branch_id',$users_data['parent_id']); 
					      
					    $query = $this->db->get(); 
					    //echo $this->db->last_query();die;
					    $pacakge_data_get = $query->result_array();

					    if(!empty($pacakge_data_get))
					    {
						    $type = $pacakge_data_get[0]['id'];
					    }
					    else
					    {
						 	$type_insert_data = array(
							'dialysis_type'=>$pacakge_data['type'],
							'branch_id'=>$users_data['parent_id'],
							'status'=>1,
							'ip_address'=>$_SERVER['REMOTE_ADDR'],
							'created_date'=>date('Y-m-d H:i:s'),
							);
							$this->db->insert('hms_dialysis_type',$type_insert_data);
							$type = $this->db->insert_id();
					    }
					}
				
          
				$pacakge_data_array = array( 
				        'branch_id'=>$users_data['parent_id'],
	                    'name'=>$pacakge_data['name'],
	                    'type'=>$type,
	                    'hours'=>$pacakge_data['hours'],
	                     'days'=>$pacakge_data['days'],
	                    'amount'=>$pacakge_data['amount'],
	                    'remarks'=>$pacakge_data['remarks'],
						'status'=>1,					
	                    'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_date'=>date('Y-m-d H:i:s'),
				        'modified_date'=>date('Y-m-d H:i:s'),
				        'created_by'=>$users_data['parent_id']
				        
				    );

                //print_r($vaccination_data_array);
				    $this->db->insert('hms_dialysis_pacakge',$pacakge_data_array);
	                //echo $this->db->last_query(); exit;
	            }
            }   	
        }
	}

}
?>