<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Branch_menu_model extends CI_Model {

	
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}



     public function create_menu($branch_id='')
     {
	 	
	 	 $users_data = $this->session->userdata('auth_users');
	 	if(!empty($branch_id))
        {

			$this->db->select('*');
            $this->db->where('branch_id IN('.$users_data['parent_id'].')');
            $this->db->where('parent_id','0');
            $query = $this->db->get('hms_branch_menu_new');
            $result = $query->result_array();
            //echo "<pre>";print_r($result); exit;
            if(!empty($result)){
                $mode_setting_count = count($result);
                for($i=0;$i<$mode_setting_count;$i++){
                    $mode_setting1[$i] = array(
                        'branch_id'=>$branch_id,
                        'parent_id'=>0,
                        'section_id'=>$result[$i]['section_id'],
                        'name'=>$result[$i]['name'],
                        'url'=>$result[$i]['url'],
                        'pop_up_id'=>$result[$i]['pop_up_id'],
                        'status'=>$result[$i]['status'],
                        'permission_status'=>$result[$i]['permission_status'],
                        'sort_order'=>$result[$i]['sort_order'],
                        'ip_address'=>$_SERVER['REMOTE_ADDR'],
                        'created_by'=>$result[$i]['created_by'],
                        'modified_date'=>$result[$i]['modified_date'],
                        'created_date'=>$result[$i]['created_date']
                    );

                    
                    $this->db->insert('hms_branch_menu_new',$mode_setting1[$i]);
                    $menu_p_id1 =  $this->db->insert_id();

                   
                      $this->db->select('*');
                      $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                      $this->db->where('parent_id',$result[$i]['id']);
                      $query = $this->db->get('hms_branch_menu_new');
                      $result1 = $query->result_array();
                      //echo $this->db->last_query(); exit();
                      if(!empty($result1))
                      {
                          $mode_filed_setting_count = count($result1);
                          for($k=0;$k<$mode_filed_setting_count;$k++)
                          {
                              $mode_filed_setting[$k] = array(
                                  'branch_id'=>$branch_id,
                                  'parent_id'=>$menu_p_id1,
                                  'section_id'=>$result1[$k]['section_id'],
                                  'name'=>$result1[$k]['name'],
                                  'url'=>$result1[$k]['url'],
                                  'pop_up_id'=>$result1[$k]['pop_up_id'],
                                  'status'=>$result1[$k]['status'],
                                  'permission_status'=>$result1[$k]['permission_status'],
                                  'sort_order'=>$result1[$k]['sort_order'],
                                  'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                  'created_by'=>$result1[$k]['created_by'],
                                  'modified_date'=>$result1[$k]['modified_date'],
                                  'created_date'=>$result1[$k]['created_date']
                              );

                              /**********insert data into hms_branch_prescription_tab_setting***********/
                              $this->db->insert('hms_branch_menu_new',$mode_filed_setting[$k]);
                              $menu_p_id2 =  $this->db->insert_id();
                              //echo $this->db->last_query(); exit();
                              
                          

                              $this->db->select('*');
                              $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                              $this->db->where('parent_id',$result1[$k]['id']);
                              $query = $this->db->get('hms_branch_menu_new');
                              $result2 = $query->result_array();
                              //echo $this->db->last_query(); exit();
                              if(!empty($result2))
                              {
                                  $mode_filed_setting_count2 = count($result2);
                                  for($m=0;$m<$mode_filed_setting_count2;$m++)
                                  {
                                      $mode_filed_setting2[$m] = array(
                                          'branch_id'=>$branch_id,
                                          'parent_id'=>$menu_p_id2,
                                          'section_id'=>$result2[$m]['section_id'],
                                          'name'=>$result2[$m]['name'],
                                          'url'=>$result2[$m]['url'],
                                          'pop_up_id'=>$result2[$m]['pop_up_id'],
                                          'status'=>$result2[$m]['status'],
                                          'permission_status'=>$result2[$m]['permission_status'],
                                          'sort_order'=>$result2[$m]['sort_order'],
                                          'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                          'created_by'=>$result2[$m]['created_by'],
                                          'modified_date'=>$result2[$m]['modified_date'],
                                          'created_date'=>$result2[$m]['created_date']
                                      );

                                      /**********insert data into hms_branch_prescription_tab_setting***********/
                                  $this->db->insert('hms_branch_menu_new',$mode_filed_setting2[$m]);
                                  $menu_p_id3 =  $this->db->insert_id();
                                      //echo $this->db->last_query(); exit();
                                  

                                  $this->db->select('*');
                                  $this->db->where('branch_id IN('.$users_data['parent_id'].')');
                                  $this->db->where('parent_id',$result2[$m]['id']);
                                  $query = $this->db->get('hms_branch_menu_new');
                                  $result3 = $query->result_array();
                                  //echo $this->db->last_query(); exit();
                                  if(!empty($result3))
                                  {
                                      $mode_filed_setting_count3 = count($result3);
                                      for($n=0;$n<$mode_filed_setting_count3;$n++)
                                      {
                                          $mode_filed_setting3[$n] = array(
                                              'branch_id'=>$branch_id,
                                              'parent_id'=>$menu_p_id3,
                                              'section_id'=>$result3[$n]['section_id'],
                                              'name'=>$result3[$n]['name'],
                                              'url'=>$result3[$n]['url'],
                                              'pop_up_id'=>$result3[$n]['pop_up_id'],
                                              'status'=>$result3[$n]['status'],
                                              'permission_status'=>$result3[$n]['permission_status'],
                                              'sort_order'=>$result3[$n]['sort_order'],
                                              'ip_address'=>$_SERVER['REMOTE_ADDR'],
                                              'created_by'=>$result3[$n]['created_by'],
                                              'modified_date'=>$result3[$n]['modified_date'],
                                              'created_date'=>$result3[$n]['created_date']
                                          );

                                          /**********insert data into hms_branch_prescription_tab_setting***********/
                                  $this->db->insert('hms_branch_menu_new',$mode_filed_setting3[$n]);
                                  $menu_p_id4 =  $this->db->insert_id();
                                        
                                          
                                      }
                                    }


                                  }
                                } 
                          }
                        }   

                    
                }
            }

      }
      
      }
}
      ?>      