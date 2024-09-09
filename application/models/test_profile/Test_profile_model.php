<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_profile_model extends CI_Model {

    var $table = 'path_profile';
    var $column = array('path_profile.id','path_profile.profile_name','path_profile.print_name','path_profile.master_rate','path_profile.base_rate',  'path_profile.sort_order','path_profile.status','path_profile.created_date');  
    var $order = array('sort_order'=>'asc');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($branch_id='')
    {
        $users_data = $this->session->userdata('auth_users');
        $company_data = $this->session->userdata('company_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        //print_r($parent_branch_details);die;
        $this->db->select("path_profile.*"); 
        $this->db->from($this->table); 
        if($branch_id!='inherit')
        {
        $this->db->where('path_profile.is_deleted',0);
        }
        //$this->db->where('path_profile.branch_id',$users_data['parent_id']);
        if($users_data['users_role']=='2')
        {
            if(!empty($branch_id))
            {
                if($branch_id=='inherit')
                {
                  
                    if(!empty($parent_branch_details)){
                        $id_list = [];
                        foreach($parent_branch_details as $id){
                            if(!empty($id) && $id>0){
                                $id_list[]  = $id['parent_id'];
                            } 
                        }
                        $branch_ids = implode(',', $id_list);
                        
                        $this->db->where('path_profile.branch_id IN('.$branch_ids.')');
                    }
                    $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'") AND is_deleted!=2)');
                }
                else if($branch_id==$users_data['parent_id'])
                {
                    $this->db->where('path_profile.branch_id',$users_data['parent_id']);
                      $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                
                }
                else{
                    $this->db->where('path_profile.branch_id',$branch_id);
                      $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                }
            }
            else
            {
                $this->db->where('path_profile.branch_id',$users_data['parent_id']);
            }
        }
        else if($users_data['users_role']=='3')
        {
            $this->db->where('path_profile.branch_id',$company_data['id']);
        }
        else if($users_data['users_role']=='1')
        {
            $this->db->where('path_profile.branch_id',$users_data['parent_id']);
        }
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

    function get_datatables($branch_id='')
    {   
        $this->_get_datatables_query($branch_id);
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
    
    public function email_template_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('*');
        $this->db->where('branch_id',$user_data['parent_id']);
        $this->db->where('status',1); 
        $this->db->where('is_deleted',0); 
        $this->db->order_by('subject','ASC'); 
        $query = $this->db->get('path_profile');
        return $query->result();
    }

    public function get_by_id($id)
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('path_profile.*');
        $this->db->where('branch_id',$user_data['parent_id']); 
        $this->db->from('path_profile'); 
        $this->db->where('path_profile.id',$id);
        $this->db->where('path_profile.is_deleted','0');
        $query = $this->db->get(); 
        return $query->row_array();
    }

    public function active_profile_list()
    {
        $user_data = $this->session->userdata('auth_users');
        $this->db->select('path_profile.*');
        $this->db->where('branch_id',$user_data['parent_id']); 
        $this->db->from('path_profile'); 
        $this->db->where('path_profile.status','1');
        $this->db->where('path_profile.is_deleted','0');
        $query = $this->db->get(); 
        return $query->result_array();
    }

    public function profile_price26march2019($id="")
    {
        if(!empty($id))
        {  
            $user_data = $this->session->userdata('auth_users');
            $booking_doctor_type = $this->session->userdata('booking_doctor_type');
            $this->db->select('path_profile.*, path_profile_to_price.master_rate as profile_master_rate, path_profile_to_price.base_rate as profile_base_rate'); 
            if(!empty($booking_doctor_type))
            {
                 $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.branch_id = '".$user_data['parent_id']."' AND path_profile_to_price.doctor_id='".$booking_doctor_type[0]['id']."'",'left');
            }
            else
            {
                $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.branch_id = '".$user_data['parent_id']."' AND path_profile_to_price.doctor_id=0",'left');
            }
            $this->db->where('path_profile.branch_id',$user_data['parent_id']); 
            $this->db->from('path_profile'); 
            $this->db->where('path_profile.id',$id);
            $this->db->where('path_profile.is_deleted','0');
            $query = $this->db->get(); 
            return $query->row_array();
        } 
    }
    
    public function profile_price($id="",$panel_id="")
    {
        
        if(!empty($id))
        {  
            $user_data = $this->session->userdata('auth_users');
            $booking_doctor_type = $this->session->userdata('booking_doctor_type');
            if($panel_id!="")
            {

            $cases= 'path_profile_panel_price.master_rate as profile_master_rate,path_profile_panel_price.panel_id,path_profile_panel_price.profile_id';

            }
            else
            {
                 $cases= 'path_profile_to_price.master_rate as profile_master_rate';
            }
            $this->db->select("path_profile.*,".$cases); 
            if(!empty($booking_doctor_type) && empty($panel_id))
            {
              
                 $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.branch_id = '".$user_data['parent_id']."' AND path_profile_to_price.doctor_id='".$booking_doctor_type[0]['id']."'",'left');
            }
            elseif(!empty($panel_id) )
            {
                
                 $this->db->join("path_profile_panel_price","path_profile_panel_price.profile_id=path_profile.id AND path_profile_panel_price.branch_id = '".$user_data['parent_id']."' AND path_profile_panel_price.panel_id='".$panel_id."'",'left');
            }
            elseif(empty($booking_doctor_type) && empty($panel_id)) 
            {
               
                $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.branch_id = '".$user_data['parent_id']."' AND path_profile_to_price.doctor_id=0",'left');
            }
            $this->db->where('path_profile.branch_id',$user_data['parent_id']); 
            $this->db->from('path_profile'); 
            $this->db->where('path_profile.id',$id);
            $this->db->where('path_profile.is_deleted','0');
            $query = $this->db->get(); 
            //echo $this->db->last_query();die;
            return $query->row_array();
        } 
    }
    
    public function save($ids=array())
    {
        
        $user_data = $this->session->userdata('auth_users');
        $child_test_ids = $this->session->userdata('child_test_ids'); 
        $post = $this->input->post();  
        
        $data = array( 
                    'branch_id'=>$user_data['parent_id'],
                    'profile_name'=>$post['profile_name'],
                    'print_name' =>$post['print_name'],
                    'master_rate'=>$post['master_rate'],
                    'base_rate' =>$post['base_rate'],
                    'interpretation' =>$post['interpretation'],
                    'status'=>$post['status'],
                    'ip_address'=>$_SERVER['REMOTE_ADDR']
                 );
        if(!empty($post['prof_id']) && $post['prof_id']>0)
        {     
            $this->db->set('modified_by',$user_data['id']);
            $this->db->set('modified_date',date('Y-m-d H:i:s'));
            $this->db->where('id',$post['prof_id']);
            $this->db->update('path_profile',$data);  
            $profile_id = $post['prof_id']; 
            
        }
        else
        { 
            $this->db->set('created_by',$user_data['id']);
            $this->db->set('created_date',date('Y-m-d H:i:s'));
            $this->db->insert('path_profile',$data); 
            $profile_id =$this->db->insert_id();  
            $this->session->unset_userdata('interpretation'); 
        } 

        $this->db->where('profile_id',$profile_id); 
        $this->db->delete('path_profile_to_test');

        if(!empty($child_test_ids))  
        {
            $test_data_arr = [];
            foreach($child_test_ids as $test_id)
            {  //print_r($test_id); exit;
                 $this->db->set('profile_id',$profile_id);
                 $this->db->set('test_id',$test_id['id']);
                 $this->db->set('sort_order',$test_id['sort_order']);
                 $this->db->insert('path_profile_to_test',$test_data_arr);  
            }
        }

        $profile_data = $this->session->userdata('set_multi_profile');
        //print_r($profile_data); exit;
        $this->db->where('profile_id',$profile_id); 
        $this->db->delete('path_profile_to_profile');

        if(!empty($profile_data))  
        {
            $test_data_arr = [];
            $profile_data = array_values($profile_data);
            foreach($profile_data as $test_id)
            {  
                 $this->db->set('profile_id',$profile_id);
                 $this->db->set('multi_profile_id',$test_id['id']);
                 $this->db->set('sort_order',$test_id['sort_order']);
                 
                 $this->db->insert('path_profile_to_profile',$test_data_arr);  
            }
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
            $this->db->update('path_profile');
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
            $this->db->update('path_profile');
            //echo $this->db->last_query();die;
        } 
    }
    //fetch parent test
    public function parent_test_list(){
        $user_data = $this->session->userdata('auth_users');
        $this->db->distinct('path_test.test_name');
        $this->db->select("path_test.id,path_test.test_name");
        $this->db->from("path_test");
        $this->db->join("path_test_under","path_test.id=path_test_under.parent_id");
        $this->db->where("path_test.branch_id='0'");
        $this->db->or_where("path_test.branch_id",$user_data['parent_id']);
        // $this->db->group_by("path_test_under.parent_id");
        $query = $this->db->get(); 
        return $query->result();
        
    }
    //fetch the child test according to parent test ids on change
    public function get_child_test_list($id='')
    {
         
        $child_test_ids = $this->session->userdata('child_test_ids');
        $users_data = $this->session->userdata('auth_users');
        $selected_test_child_ids = $this->session->userdata('selected_test_child_ids');
        $sel_child_list = [];
        if(!empty($selected_test_child_ids)){
            foreach ($selected_test_child_ids as $selected_test_ids) {
                if(!empty($selected_test_ids) && $selected_test_ids>=0){
                    $sel_child_list[]  = $selected_test_ids;
                } 
            }
            $selected_test_child_ids = implode(',', $sel_child_list);
        }else{
            $selected_test_child_ids='';
        }

        $id_list = [];
        if(!empty($child_test_ids))
        {
            foreach($child_test_ids as $child_test)
            {
                if(!empty($child_test) && $child_test>=0)
                {
                    
                    $id_list[]  = $child_test['id'];
                } 
            }
            $child_test_ids = implode(',', $id_list);
        }
        else
        {
            $child_test_ids='';
        }
            
           
            $this->db->distinct("path_test.test_name");
            $this->db->select("path_test.test_name,path_test.id");
            $this->db->from("path_test");
            $this->db->join("path_test_under","path_test_under.parent_id=path_test.id",'left');
            $this->db->where("path_test.test_head_id",$id);
            if(!empty($child_test_ids)){
            $this->db->where('path_test.id NOT IN('.$child_test_ids.')');
            } 
            $order = array('path_test.sort_order'=>'asc','path_test.id' => 'desc');
            $this->db->order_by(key($order), $order[key($order)]);
            $this->db->where("path_test.branch_id",$users_data['parent_id']);
            $this->db->where('path_test.is_deleted',0);
            $this->db->group_by('path_test.id');
            $query = $this->db->get();
            //echo $this->db->last_query(); exit;         
            return $query->result_array();
        
    }
    //add the selected child test into selected child test list
    public function addalltest($ids=array())
    {
        //check if array comes in json_encoded form means string in case of empty
        if(!is_array($ids)){
            $ids = json_decode($ids);
        }
        //ends
        

        if(!empty($ids))
        {
            //checks if array comes in json_enocded and not empty 
            if(!is_array($ids)){
                $ids = json_decode($ids);
            }
            //ends

            $id_list = [];
            foreach($ids as $id)
            {
                if(!empty($id) && $id>0)
                {
                  $id_list[]  = $id;
                } 
            }
            $test_child_ids = implode(',', $id_list);
            $this->db->select("test_name,id");
            $this->db->where('id IN ('.$test_child_ids.')');
            $this->db->from('path_test');
           
            // $this->db->group_by("path_test_under.parent_id");
            $query = $this->db->get(); 
            
            // echo $this->db->last_query();die;
            return $query->result_array();
        
        } 
    }
    public function get_rest_child_test_after_delete(){
        $child_test_ids = $this->session->userdata('child_test_ids');
        $id_list = [];
        if(!empty($child_test_ids)){
            foreach($child_test_ids as $child_test){
                if(!empty($child_test) && $child_test>=0){
                    $id_list[]  = $child_test;
                } 
            }
            $child_test_ids = implode(',', $id_list);
        }
        else{
            $child_test_ids='';
        }
        if($child_test_ids){
            $this->db->select("test_name,id");
            $this->db->where('id IN ('.$child_test_ids.')');
            $this->db->from('path_test');
            // $this->db->group_by("path_test_under.parent_id");
           $query = $this->db->get(); 
           return $query->result_array();
       }else{
        $result=array();
        return $result;
       }
    }
    public function departments_list($module_id='')
    {
        /*
        $user_data = $this->session->userdata('auth_users');
        $this->db->distinct('hms_department.department');
        $this->db->select("hms_department.department,hms_department.id");
        $this->db->from('hms_department');
        $this->db->join("path_test","path_test.dept_id=hms_department.id"); 
        // $this->db->where("branch_id= '0' OR branch_id='".$user_data['parent_id']."'");
        // $this->db->group_by("path_test_under.parent_id");
        $query = $this->db->get(); 
        return $query->result();
       */
        $users_data = $this->session->userdata('auth_users');
        //$this->db->select('*');
        $this->db->select("hms_department.id,hms_department.branch_id,hms_department.module,hms_department.department,hms_department.ip_address,hms_department.is_deleted,hms_department.deleted_by,hms_department.deleted_date,hms_department.created_by,hms_department.modified_by,hms_department.modified_date,hms_department.created_date, (CASE WHEN hms_department.branch_id=0 THEN 1 ELSE hms_department_to_department_status.status END) status");
        
        $this->db->where('hms_department.is_deleted',0);
        $this->db->from('hms_department'); 
         $this->db->join('hms_department_to_department_status','hms_department_to_department_status.department_id = hms_department.id AND hms_department_to_department_status.branch_id="'.$users_data['parent_id'].'"','left');

        if(!empty($module_id))
        {
           $this->db->where('hms_department.module',$module_id); 
        }
        $this->db->where('(hms_department.branch_id='.$users_data['parent_id'].' OR hms_department.branch_id=0)'); 
        $this->db->where('hms_department_to_department_status.status','1');   
        $query = $this->db->get();
        $result = $query->result(); 
        return $result;
    }
    public function get_test_heads($id=''){
        $users_data = $this->session->userdata('auth_users');
        $this->db->select("test_heads,id");
        $this->db->where("dept_id",$id);
        $this->db->where('path_test_heads.branch_id',$users_data['parent_id']);
        $this->db->where("path_test_heads.is_deleted",'0');
        $this->db->where("path_test_heads.status",'1');
        $this->db->from('path_test_heads');
         $order = array('sort_order'=>'asc','id' => 'desc');
        $this->db->order_by(key($order), $order[key($order)]);
        $query = $this->db->get(); 
        return $query->result();

    }

    public function profile_sort_order($id='',$sub_profile='')
    {
        
        $this->db->select("sort_order");
        $this->db->where("profile_id",$id);
        $this->db->where('multi_profile_id',$sub_profile);
        $this->db->from('path_profile_to_profile');
        $query = $this->db->get(); 
        //echo $this->db->last_query();die; 
        return $query->result();

    }
    public function selected_test_child($id=''){
        $user_data = $this->session->userdata('auth_users');
     
        if(!empty($id)){
            
            $this->db->select("path_test.id,path_test.test_name, path_profile_to_test.sort_order");
            $this->db->from("path_profile");
            $this->db->join("path_profile_to_test","path_profile.id=path_profile_to_test.profile_id");
            $this->db->where("path_profile_to_test.profile_id='".$id."'");
            $this->db->join("path_test","path_test.id=path_profile_to_test.test_id");
            
            // $this->db->or_where("(path_test.branch_id='0' OR path_test.branch_id='".$user_data['parent_id']."') OR (path_profile.branch_id='0' OR path_profile.branch_id='".$user_data['parent_id']."')");
            $query = $this->db->get();
            // echo $this->db->last_query();die; 
            return $query->result();
           
        }


    }

    public function selected_profile($id='')
    {
        $user_data = $this->session->userdata('auth_users');
     
        if(!empty($id)){
            
            $this->db->select("path_profile.id,path_profile.profile_name");
            $this->db->from("path_profile");
            $this->db->join("path_profile_to_profile","path_profile.id=path_profile_to_profile.multi_profile_id");
            $this->db->where("path_profile_to_profile.profile_id='".$id."'");
            $this->db->join("path_profile as prof","prof.id=path_profile_to_profile.multi_profile_id");
            
            $query = $this->db->get();
            return $query->result();
           
        }


    }
        public function save_sort_order_data($id='',$sort_order_value=''){
        if(!empty($id) && !empty($sort_order_value)){
            $this->db->set('sort_order',$sort_order_value);
            $this->db->where('id',$id);
            $result='';
            if($this->db->update('path_profile')){
                $result='true';
                return $result;
            }

        }
    }


    public function downloadall($ids=array())
    {
        $users_data = $this->session->userdata('auth_users');   
        $company_data = $this->session->userdata('company_data'); 

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
            
            $test_ids = implode(',', $id_list);
            $this->db->select('path_profile.*');     
            $this->db->where('path_profile.id IN ('.$test_ids.')');  
            $query = $this->db->get('path_profile');
            $result = $query->result_array(); 
            $data= array();
            $test_id=array();
            $test_code= array();
            if(!empty($result))
            {
                for($i=0;$i<count($result);$i++)
                {
                    $test_code[$i] = generate_unique_id(5);
                    $rate_plan = get_rate_plan($company_data['rate_id']);
                    $profile_master_price = $result[$i]['master_rate'];  
                    $profile_base_price = $result[$i]['base_rate']; 
                    if(!empty($rate_plan) && $users_data['users_role']==2)
                      {                          
                         ///////// Master Calculation //////////////////
                         if($rate_plan['master_type']==1)
                         {
                            $pos_master = strpos($rate_plan['master_rate'],'-');
                            if ($pos_master === true) 
                               {
                                  $master_rate = str_replace('-', '',$rate_plan['master_rate']);
                                  $master_rate = $profile_master_price-(($profile_master_price/100)*$rate_plan['master_rate']);
                               }
                            else
                               {
                                    $master_rate = str_replace('+', '',$rate_plan['master_rate']);
                                    $master_rate = $profile_master_price+(($profile_master_price/100)*$rate_plan['master_rate']); 
                               }

                         }
                         else
                         {
                              $pos_master = strpos($rate_plan['master_rate'],'-');
                              if ($pos_master === true) 
                                 {
                                    $master_rate = str_replace('-', '',$rate_plan['master_rate']);
                                    $master_rate = $profile_master_price-$rate_plan['master_rate'];
                                 }
                              else
                                 {
                                      $master_rate = str_replace('+', '',$rate_plan['master_rate']);
                                      $master_rate = $profile_master_price+$rate_plan['master_rate']; 
                                 }
                         }
                         $profile_master_price = $master_rate;
                         //////////////////////////////////////////////
                         //////////////// base rate calculation ///////
                         if($rate_plan['base_type']==1)
                         {
                            $pos_base = strpos($rate_plan['base_rate'],'-');
                            if ($pos_base === true) 
                               {
                                  $base_rate = str_replace('-', '',$rate_plan['base_rate']);
                                  $base_rate = $profile_base_price-(($profile_base_price/100)*$rate_plan['base_rate']);
                               }
                            else
                               {
                                    $base_rate = str_replace('+', '',$rate_plan['base_rate']);
                                    $base_rate = $profile_base_price+(($profile_base_price/100)*$rate_plan['base_rate']); 
                               }

                         }
                         else
                         {
                              $pos_base = strpos($rate_plan['base_rate'],'-');
                              if ($pos_base === true) 
                                 {
                                    $base_rate = str_replace('-', '',$rate_plan['base_rate']);
                                    $base_rate = $profile_base_price-$rate_plan['base_rate'];
                                 }
                              else
                                 {
                                      $base_rate = str_replace('+', '',$rate_plan['base_rate']);
                                      $base_rate = $profile_base_price+$rate_plan['base_rate']; 
                                 }
                         }
                         $profile_base_price = $base_rate;
                         /////////////////////////////////////////////  
                         
                      } 

                     $data[$i] = array(
                                'download_id'=>$id_list[$i], 
                                'branch_id'=>$users_data['parent_id'], 
                                'profile_name'=>$result[$i]['profile_name'], 
                                'print_name'=>$result[$i]['print_name'],
                                'master_rate'=>$profile_master_price,
                                'base_rate'=>$profile_base_price,
                                'status'=>1,
                                'sort_order'=>$result[$i]['sort_order'],
                                'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                'created_by'=>$users_data['id'],
                                'created_date'=>date('Y-m-d H:i:s') 
                               ); 
                    $this->db->insert('path_profile',$data[$i]); 
                    $profile_id = $this->db->insert_id();  
                    //// Check  Test ///////////////
                    $this->db->select('path_test.*, path_test_heads.test_heads, path_test_method.test_method as test_method_name, path_unit.unit, path_sample_type.sample_type,path_profile_to_test.sort_order as p_order');    
                    $this->db->join('path_test','path_test.id=path_profile_to_test.test_id');    
                    $this->db->join('path_test_heads','path_test_heads.id=path_test.test_head_id');  
                    $this->db->join('path_test_method','path_test_method.id=path_test.method_id','left'); 
                    $this->db->join('path_unit','path_unit.id=path_test.unit_id','left');     
                    $this->db->join('path_sample_type','path_sample_type.id=path_test.sample_test','left');    
                    $this->db->where('path_profile_to_test.profile_id',$result[$i]['id']);   
                    $this->db->order_by('id','DESC');
                    $this->db->group_by('path_test.id');
                    $query = $this->db->get('path_profile_to_test');
                    $test_list = $query->result_array();
                    
                    //Check test
                    if(!empty($test_list))
                    {
                        foreach($test_list as $test_data)
                        {
                             $this->db->select('path_test.*');
                             $this->db->where('dept_id',$test_data['dept_id']);
                             $this->db->where('test_name',$test_data['test_name']);
                             $this->db->where('branch_id',$users_data['parent_id']);
                             $this->db->where('is_deleted','0');
                             $query_check = $this->db->get('path_test');
                             $check_test = $query_check->result_array();
                             if(!empty($check_test))
                             {
                                $test_id = $check_test[0]['id'];
                             }
                             else
                             {
                                $test_id = donwload_test($test_data['id']);
                             }
                                ////// Test To Profile insert
                                $data_test_profile = array(
                                                         'profile_id'=>$profile_id,
                                                         'test_id'=>$test_id,
                                                         'sort_order'=>$test_data['p_order'],
                                                       );

                                $this->db->insert('path_profile_to_test',$data_test_profile);
                                //// End test to profile_insert///
                        }
                    }
                      
                }
            }
        }
    }


    public function get_profile_list()
    {
        $users_data = $this->session->userdata('auth_users');   
        $post = $this->input->post();
        if((isset($post['branch_id']) && !empty($post['branch_id'])) || (isset($post['doctors_id']) && !empty($post['doctors_id'])))
        {
            $this->db->select("path_profile.id,path_profile.profile_name, path_profile.master_rate, path_profile.base_rate, path_profile_to_price.master_rate as profile_master_rate, path_profile_to_price.base_rate as profile_base_rate");
            $this->db->from("path_profile");
            if(isset($post['doctors_id']) && !empty($post['doctors_id']))
            {
                $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.doctor_id = '".$post['doctors_id']."'",'left');
            } 
            else if(isset($post['branch_id']) && !empty($post['branch_id']))
            {
                $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.branch_id = '".$post['branch_id']."' AND path_profile_to_price.doctor_id=0",'left');
            }   

            if(isset($post['doctors_id']) && !empty($post['doctors_id']))
            {
              $this->db->where('path_profile.branch_id',$users_data['parent_id']);
            }
            else if(isset($post['branch_id']) && !empty($post['branch_id']))
            {
               $this->db->where('path_profile.branch_id',$post['branch_id']);
            }
            else
            {
               $this->db->where('path_profile.branch_id',$users_data['parent_id']);
            } 
            $this->db->where('path_profile.is_deleted','0');
            $this->db->group_by('path_profile.id');
            $query = $this->db->get();
            //echo $this->db->last_query();die; 
            return $query->result();
       }
    }


    public function save_profile_rate($data=[])
    {
       $users_data = $this->session->userdata('auth_users'); 
       if(!empty($data))
       { 
         if(!empty($data['branch_id']) || !empty($data['doctor_id']))
         {
            $this->db->where('branch_id',$data['branch_id']);
            $this->db->where('doctor_id',$data['doctor_id']);
            $this->db->where('profile_id',$data['profile_id']);
            $this->db->delete('path_profile_to_price');
         }

         $this->db->insert('path_profile_to_price',$data);
         if(!empty($data['branch_id']) && $data['branch_id']!=$users_data['parent_id'] && ($data['doctor_id']==0 || empty($data['doctor_id'])))
         {
             $this->db->set('master_rate',$data['master_rate']);
             $this->db->set('base_rate',$data['base_rate']);
             $this->db->where('id',$data['profile_id']);
             $this->db->where('branch_id',$data['branch_id']);
             $this->db->update('path_profile');
         }
       }
    }
    
  public function get_test_child_of_parent($id)
  {
    $this->db->select('path_test_under.child_id as test_id');
    $this->db->from('path_test_under'); 
    $this->db->where('path_test_under.parent_id',$id); 
    $query = $this->db->get(); 
    return $query->result_array();
  }



  public function search_profile_data()
  {
        $users_data = $this->session->userdata('auth_users');
        $company_data = $this->session->userdata('company_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        //print_r($parent_branch_details);die;
        $this->db->select("path_profile.*"); 
        $this->db->from($this->table); 
        if($branch_id!='inherit')
        {
        $this->db->where('path_profile.is_deleted',0);
        }
        //$this->db->where('path_profile.branch_id',$users_data['parent_id']);
        if($users_data['users_role']=='2')
        {
            if(!empty($branch_id))
            {
                if($branch_id=='inherit')
                {
                  
                    if(!empty($parent_branch_details)){
                        $id_list = [];
                        foreach($parent_branch_details as $id){
                            if(!empty($id) && $id>0){
                                $id_list[]  = $id['parent_id'];
                            } 
                        }
                        $branch_ids = implode(',', $id_list);
                        
                        $this->db->where('path_profile.branch_id IN('.$branch_ids.')');
                    }
                    $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'") AND is_deleted!=2)');
                }
                else if($branch_id==$users_data['parent_id'])
                {
                    $this->db->where('path_profile.branch_id',$users_data['parent_id']);
                      $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                
                }
                else{
                    $this->db->where('path_profile.branch_id',$branch_id);
                      $this->db->where('path_profile.id NOT IN (select download_id from path_profile where branch_id IN ("'.$users_data['parent_id'].'"))');
                }
            }
            else
            {
                $this->db->where('path_profile.branch_id',$users_data['parent_id']);
            }
        }
        else if($users_data['users_role']=='3')
        {
            $this->db->where('path_profile.branch_id',$company_data['id']);
        }
        else if($users_data['users_role']=='1')
        {
            $this->db->where('path_profile.branch_id',$users_data['parent_id']);
        }

        $query = $this->db->get(); 
        //echo $this->db->last_query();die;
        return $query->result();
  }




  public function udpate_all_test($test_all_datas= array())
    {
        //echo "<pre>"; print_r($test_all_datas); exit; 
        $users_data = $this->session->userdata('auth_users'); 

        ///////////////// Set department //////////
        if(!empty($test_all_datas))
        {
            foreach($test_all_datas as $test_all_data)
            { 
                  $data_test = array(  
                                    'profile_name'=>$test_all_data['profile_name'], 
                                    'profile_name'=>$test_all_data['print_name'],
                                    'master_rate'=>$test_all_data['master_rate'],  
                                    'base_rate'=>$test_all_data['master_rate'],
                                    'sort_order'=>$test_all_data['sort_order'],
                                    'status'=>$test_all_data['status'],
                                   ); 

                      $this->db->where('id',$test_all_data['id']);  
                      $this->db->where('branch_id',$users_data['parent_id']); 
                      $this->db->update('path_profile',$data_test); 
                      //echo $this->db->last_query();die;
                      $test_id = $test_all_data['id']; 

                }

            }
    }
    
    public function doctor_plan_profile_price($id='')
    {
        if(!empty($id))
        {  
            $user_data = $this->session->userdata('auth_users');
            $doctor_rate_plan_rate = $this->session->userdata('doctor_rate_plan_rate');
            //echo "<pre>"; print_r($doctor_rate_plan_rate); exit;
        //if(!empty($test_rate_plan_rate['doctor_id']) && isset($test_rate_plan_rate['doctor_id']))
          //{
            $this->db->select('path_profile.*, path_rate_plan_for_doctor.charge as profile_master_rate, path_rate_plan_for_doctor.charge as profile_base_rate'); 
            if(!empty($doctor_rate_plan_rate))
            {
                 $this->db->join("path_rate_plan_for_doctor","path_rate_plan_for_doctor.profile_id=path_profile.id AND path_rate_plan_for_doctor.branch_id = '".$user_data['parent_id']."' AND path_rate_plan_for_doctor.type=1  AND path_rate_plan_for_doctor.doctor_id='".$doctor_rate_plan_rate['doctor_id']."'",'left');
            }
            else
            {
                $this->db->join("path_profile_to_price","path_profile_to_price.profile_id=path_profile.id AND path_profile_to_price.branch_id = '".$user_data['parent_id']."' AND path_profile_to_price.doctor_id=0",'left');
            }
            $this->db->where('path_profile.branch_id',$user_data['parent_id']); 
            $this->db->from('path_profile'); 
            $this->db->where('path_profile.id',$id);
            $this->db->where('path_profile.is_deleted','0');
            $query = $this->db->get(); 
            //echo $this->db->last_query();
            return $query->row_array();
        } 
    }
    
}
?>