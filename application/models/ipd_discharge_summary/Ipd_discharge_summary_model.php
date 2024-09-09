<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ipd_discharge_summary_model extends CI_Model {

	var $table = 'hms_discharge_summery';
	var $column = array('hms_discharge_summery.id','hms_discharge_summery.name', 'hms_discharge_summery.status','hms_discharge_summery.created_date');   
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
		$this->db->select("hms_discharge_summery.*"); 
		$this->db->from($this->table); 
        $this->db->where('hms_discharge_summery.is_deleted','0');
       
            $this->db->where('hms_discharge_summery.branch_id',$users_data['parent_id']);
	
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
		// echo $this->db->last_query();die;
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
    
    public function simulation_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('simulation','ASC'); 
    	$query = $this->db->get('hms_discharge_summery');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_discharge_summery.*');
		$this->db->from('hms_discharge_summery'); 
		$this->db->where('hms_discharge_summery.id',$id);
		$this->db->where('hms_discharge_summery.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		//echo "<pre>";print_r($this->input->post());
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
                    'summery_type'=>$post['summery_type'],
                    'chief_complaints'=>$post['chief_complaints'],
                    'h_o_presenting'=>$post['h_o_presenting'],
                    'on_examination'=>$post['on_examination'],
                    'vitals_pulse'=>$post['vitals_pulse'],
                    'vitals_chest'=>$post['vitals_chest'],
                    'vitals_bp'=>$post['vitals_bp'],
                    'vitals_cvs'=>$post['vitals_cvs'],
                    'vitals_temp'=>$post['vitals_temp'],
                    'vitals_p_a'=>$post['vitals_p_a'],
                    'vitals_cns'=>$post['vitals_cns'],
                    'provisional_diagnosis'=>$post['provisional_diagnosis'],
                    'final_diagnosis'=>$post['final_diagnosis'],
                    'course_in_hospital'=>$post['course_in_hospital'],
                    'investigations'=>$post['investigations'],
                    'discharge_time_condition'=>$post['discharge_time_condition'],
                    'discharge_advice'=>$post['discharge_advice'],
                    'review_time_date'=>date('Y-m-d',strtotime($post['review_time_date'])),
                    'review_time'=>date('H:i:s', strtotime(date('d-m-Y').' '.$post['review_time'])),
                    'status'=>$post['status'],
					
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_discharge_summery',$data);  
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_discharge_summery',$data);               
		} 	


		/*************** Medicine Detail ****************/
		$this->db->where(array('discharge_summary_id'=>$post['data_id']));
		$this->db->delete('hms_ipd_discharge_summery_medicine');
		if($post['medicine_name'][0]!="")
		{
			$post_medicine_name= $post['medicine_name'];

			$counter_name= count($post_medicine_name); 
		
			for($i=0;$i<$counter_name;$i++) 
				{
					$data_medicine= array(
					'medicine_name'=>$post['medicine_name'][$i],
					'medicine_dose'=>$post['medicine_dose'][$i],
					'medicine_duration'=>$post['medicine_duration'][$i],
					'medicine_frequency'=>$post['medicine_frequency'][$i],
					'medicine_advice'=>$post['medicine_advice'][$i],
					'medicine_brand'=>$post['medicine_brand'][$i],
					'medicine_type'=>$post['medicine_type'][$i],
					'medicine_salt'=>$post['medicine_salt'][$i],
					'discharge_summary_id'=>$post['data_id']
					);
			
			$this->db->insert('hms_ipd_discharge_summery_medicine',$data_medicine);
					//print_r($data_medicine);
			}
			

		}
		/*********Medicine Detail ***************/

		/*************** Investigation Test Detail ****************/
		$this->db->where(array('discharge_summary_id'=>$post['data_id']));
		$this->db->delete('hms_ipd_discharge_summary_test');
		if($post['test_name'][0]!="")
		{
			$post_test_name= $post['test_name'];

			$counter_name= count($post_test_name); 
		
			for($i=0;$i<$counter_name;$i++) 
				{
					$data_test= array(
					'test_id'=>$post['test_id'][$i],
					'test_name'=>$post['test_name'][$i],
					'test_date'=>date('Y-m-d',strtotime($post['test_date'][$i])),
					'result'=>$post['test_result'][$i],
					'branch_id'=>$user_data['parent_id'],
					'discharge_summary_id'=>$post['data_id']
					);
			
			$this->db->insert('hms_ipd_discharge_summary_test',$data_test);
			
				//	print_r($data_test);
			}
		

		}
		/*********Investigation Detail ***************/
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
			$this->db->update('hms_discharge_summery');
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
			$this->db->update('hms_discharge_summery');
			//echo $this->db->last_query();die;
    	} 
    }

    function get_simulation_name($simulation_id)
    {
        $this->db->select('simulation'); 
        $this->db->where('id',$simulation_id);                   
        $query = $this->db->get('hms_discharge_summery');
        $test_list = $query->result(); 
            foreach($test_list as $simulations)
            {
               $simulation = $simulations->simulation;
            } 
        
        return $simulation; 
    }
  

}
?>