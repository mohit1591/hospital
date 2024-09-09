<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Teeth_chart_model extends CI_Model {

	var $table = 'hms_dental_teeth_chart';
	var $column = array('hms_dental_teeth_chart.id','hms_dental_teeth_chart.teeth_name','hms_dental_teeth_chart.teeth_type','hms_dental_teeth_chart.teeth_name', 'hms_dental_teeth_chart.status','hms_dental_teeth_chart.created_date','hms_dental_teeth_number.number','hms_dental_teeth_chart.modified_date');  
	var $order = array('id' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
        $users_data = $this->session->userdata('auth_users');
		$this->db->select("hms_dental_teeth_chart.*,hms_dental_teeth_number.number"); 
		$this->db->from($this->table); 
		 $this->db->join('hms_dental_teeth_number','hms_dental_teeth_number.id=hms_dental_teeth_chart.teeth_number','Left');
        $this->db->where('hms_dental_teeth_chart.is_deleted','0');
        $this->db->where('hms_dental_teeth_chart.branch_id = "'.$users_data['parent_id'].'"');
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
    
    public function teeth_number_list()
    {
    	$user_data = $this->session->userdata('auth_users');
    	$this->db->select('*');
    	$this->db->where('branch_id',$user_data['parent_id']);
    	$this->db->where('status',1); 
    	$this->db->where('is_deleted',0); 
    	//$this->db->order_by('chief_complaints','ASC'); 
    	$query = $this->db->get('hms_dental_teeth_chart');
    	//echo $this->db->last_query();
		return $query->result();
    }

	public function get_by_id($id)
	{
		$this->db->select('hms_dental_teeth_chart.*');
		$this->db->from('hms_dental_teeth_chart'); 
		$this->db->where('hms_dental_teeth_chart.id',$id);
		$this->db->where('hms_dental_teeth_chart.is_deleted','0');
		$query = $this->db->get(); 
		//echo $this->db->last_query();
		return $query->row_array();
	}
	public function get_list_teeth_number()
	{
		$user_data = $this->session->userdata('auth_users');
		$this->db->select('hms_dental_teeth_number.*');
		$this->db->from('hms_dental_teeth_number'); 
		$this->db->where('hms_dental_teeth_number.is_deleted',0);
		$this->db->where('hms_dental_teeth_number.status','1');
		$this->db->where('branch_id',$user_data['parent_id']);
		$query = $this->db->get(); 
		//echo $this->db->last_query();
		return $query->result();
	}
	
	public function save()
	{
		$user_data = $this->session->userdata('auth_users');
		$post = $this->input->post();
		//print_r($post);
		//die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{ 
			 if(!empty($_FILES['photo']['name']))
                { 

                    $config['upload_path']   = DIR_UPLOAD_PATH.'dental/teeth_chart/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    //print_r($config);die;
                    if($this->upload->do_upload('photo')) 
                    {
                     
                    $file_data = $this->upload->data(); 
                    $file_name1= $file_data['file_name'];
                    }
              
                }
                else
                {
                  $file_name1=$this->input->post('teeth_image');
                }
		}
		else
		{
		if(!empty($_FILES['photo']['name']))
                { 

                    $config['upload_path']   = DIR_UPLOAD_PATH.'dental/teeth_chart/'; 
                    $config['allowed_types'] = 'jpg|png|jpeg|jpg'; 
                    $config['max_size']      = 1000; 
                    $config['encrypt_name'] = TRUE; 
                    $this->load->library('upload', $config);
                    //print_r($config);die;
                    if($this->upload->do_upload('photo')) 
                    {
                     
                    $file_data = $this->upload->data(); 
                    $file_name1= $file_data['file_name'];
                    }
              
                }
                else
                {
                  $file_name1=''; 
                }
            }
 
		$data = array( 
					'branch_id'=>$user_data['parent_id'],
					'teeth_name'=>$post['teeth_name'],
					'teeth_type'=>$post['teeth_type'],
					'teeth_number'=>$post['teeth_number'],
					'sort_order'=>$post['sort_order'],
					'teeth_image'=>$file_name1,
					'status'=>$post['status'],
					'ip_address'=>$_SERVER['REMOTE_ADDR']
		         );
		//print_r($data);die;
		if(!empty($post['data_id']) && $post['data_id']>0)
		{    
            $this->db->set('modified_by',$user_data['id']);
			$this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['data_id']);
			$this->db->update('hms_dental_teeth_chart',$data);

		}
		else{    
			$this->db->set('created_by',$user_data['id']);
			$this->db->set('created_date',date('Y-m-d H:i:s'));
			$this->db->insert('hms_dental_teeth_chart',$data);               
		} 	
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
			$this->db->update('hms_dental_teeth_chart');
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
			$this->db->update('hms_dental_teeth_chart');
			//echo $this->db->last_query();die;
    	} 
    }

}
?>