<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dialysis_summary_model extends CI_Model {

	var $table = 'hms_dialysis_summery';
	var $column = array('hms_dialysis_summery.id','hms_dialysis_summery.name','hms_dialysis_summery.diagnosis','hms_dialysis_summery.dialysis','hms_dialysis_summery.dialysis_findings','hms_dialysis_summery.procedures','hms_dialysis_summery.pos_dialysis_orders', 'hms_dialysis_summery.status','hms_dialysis_summery.created_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dialysis_summery.*,hms_dialysis_management.name as dialysis_name"); 
		$this->db->from($this->table); 
        $this->db->where('hms_dialysis_summery.is_deleted','0');
        $this->db->where('hms_dialysis_summery.branch_id = "'.$users_data['parent_id'].'"');
          $this->db->join('hms_dialysis_management','hms_dialysis_management.id=hms_dialysis_summery.dialysis','left');

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
    
    public function ot_summary_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	$this->db->order_by('medicine','ASC'); 
    	$query = $this->db->get('hms_dialysis_summery');
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_dialysis_summery.*');
		$this->db->from('hms_dialysis_summery'); 
		$this->db->where('hms_dialysis_summery.id',$id);
		$this->db->where('hms_dialysis_summery.is_deleted','0');
		$query = $this->db->get(); 
		return $query->row_array();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();  
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'name'=>$post['name'],
					'diagnosis'=>$post['diagnosis'],
					'dialysis'=>$post['dialysis_name_id'],
					'dialysis_findings'=>$post['dialysis_findings'],
					'procedures'=>$post['procedures'],
					'pos_dialysis_orders'=>$post['pos_dialysis_orders'],
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_dialysis_summery',$data); 
			
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$post['data_id']));
            $this->db->delete('hms_patient_dialysis_template_field_value');
            if(!empty($post['field_name']))
            {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_field_value= array(
                        'field_value'=>$post['field_name'][$i],
                        'field_id'=>$post['field_id'][$i],
                        'branch_id'=>$user_data['parent_id'],
                        'parent_id'=>$post['data_id'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                        );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_patient_dialysis_template_field_value',$data_field_value);

                }
            }
            
		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dialysis_summery',$data);  
			$discharge_id =  $this->db->insert_id(); 
			//echo $this->db->last_query(); exit;
			$this->db->where(array('branch_id'=>$user_data['parent_id'],'parent_id'=>$discharge_id));
            $this->db->delete('hms_patient_dialysis_template_field_value');
            if(!empty($post['field_name']))
            {
                $post_field_value_name= $post['field_name'];
                $counter_name= count($post_field_value_name); 
                for($i=0;$i<$counter_name;$i++) 
                    {
                        $data_field_value= array(
                        'field_value'=>$post['field_name'][$i],
                        'field_id'=>$post['field_id'][$i],
                        'branch_id'=>$user_data['parent_id'],
                        'parent_id'=>$discharge_id,
                        'ip_address'=>$_SERVER['REMOTE_ADDR']
                        );
                $this->db->set('created_by',$user_data['id']);
                $this->db->set('created_date',date('Y-m-d H:i:s'));
                $this->db->insert('hms_patient_dialysis_template_field_value',$data_field_value);

                }
            }
			
		} 	
	}

	public function management_list()
    {
     	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	//$this->db->order_by('dialysis_type','ASC'); 
    	$query = $this->db->get('hms_dialysis_management');
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
			$this->db->update('hms_dialysis_summery');
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
			$this->db->update('hms_dialysis_summery');
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
	        $this->db->order_by('medicine_type','ASC');
	        $this->db->where('is_deleted',0);
	        $this->db->where('medicine_type LIKE "'.$vals.'%"');
	        $this->db->where('branch_id',$users_data['parent_id']);  
	        $query = $this->db->get('hms_dialysis_summery_type');
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
    
    public function discharge_master_detail_by_field($parent_id="")
    {
        $users_data = $this->session->userdata('auth_users'); 
        $this->db->select('hms_patient_dialysis_template_field_value.*,hms_dialysis_field_master.discharge_lable,hms_dialysis_field_master.type,hms_dialysis_field_master.discharge_short_code'); 
        $this->db->join('hms_patient_dialysis_template_field_value','hms_patient_dialysis_template_field_value.field_id = hms_dialysis_field_master.id AND hms_patient_dialysis_template_field_value.parent_id = "'.$parent_id.'" AND hms_patient_dialysis_template_field_value.branch_id = "'.$users_data['parent_id'].'"','left'); 
        $this->db->where('hms_dialysis_field_master.branch_id',$users_data['parent_id']);
        $this->db->where('hms_dialysis_field_master.status',1);
        $this->db->where('hms_dialysis_field_master.is_deleted',0);  
        $this->db->order_by('hms_dialysis_field_master.sort_order','DESC');
        $query= $this->db->get('hms_dialysis_field_master')->result();
        //echo $this->db->last_query(); exit;
        return $query;
    }

}
?>