<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_profile extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('test_profile/Test_profile_model','test_profile');
        $this->load->library('form_validation');
    }

    public function index()
    { 
         unauthorise_permission('144','863');
         $this->session->unset_userdata('departement_id');
         $this->session->unset_userdata('child_test_ids');
         $this->session->unset_userdata('set_multi_profile');
         
        $data['page_title'] = 'Profile Master List'; 
        $this->load->view('test_profile/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('144','863'); 
        $users_data = $this->session->userdata('auth_users'); 
        $company_data = $this->session->userdata('company_data');  
        $post = $this->input->post();
        if(isset($post['branch_id']) && !empty($post) && !empty($post['branch_id']) && $post['branch_id']=='inherit')
        {
            $list = $this->test_profile->get_datatables($post['branch_id']); 
        }
        else
        {
           $list = $this->test_profile->get_datatables($users_data['parent_id']); 
        }
        $this->session->unset_userdata('child_test_ids'); 
        $this->session->unset_userdata('set_multi_profile');
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
         
        $this->session->unset_userdata('child_test_ids');  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Profile_Management) {
         // print_r($Vendor);die;
            $no++;
            $row = array();
            if($Profile_Management->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            $users_data = $this->session->userdata('auth_users');  
            ////////// Check list end ///////////// 
             if($users_data['users_role']!=3)
             {
               $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$Profile_Management->id.'">'.$check_script;
             } 
            $row[] = $Profile_Management->profile_name; 
            $row[] = $Profile_Management->print_name;
            $profile_master_rate = $Profile_Management->master_rate;
            if(isset($post['branch_id']) && !empty($post) && !empty($post['branch_id']) && $post['branch_id']=='inherit')
            {
              $rate_plan = get_rate_plan($company_data['rate_id']);
              if(!empty($rate_plan))
              {  
                 if($rate_plan['master_type']==1)
                 {
                    $pos_master = strpos($rate_plan['master_rate'],'-');
                    if ($pos_master === true) 
                       {
                          $master_rate = str_replace('-', '',$rate_plan['master_rate']);
                          $master_rate = $Profile_Management->master_rate-(($Profile_Management->master_rate/100)*$rate_plan['master_rate']);
                       }
                    else
                       {
                            $master_rate = str_replace('+', '',$rate_plan['master_rate']);
                            $master_rate = $Profile_Management->master_rate+(($Profile_Management->master_rate/100)*$rate_plan['master_rate']); 
                       }
                 }
                 else
                 {
                      $pos_master = strpos($rate_plan['master_rate'],'-');
                      if ($pos_master === true) 
                         {
                            $master_rate = str_replace('-', '',$rate_plan['master_rate']);
                            $master_rate = $Profile_Management->master_rate-$rate_plan['master_rate'];
                         }
                      else
                         {
                              $master_rate = str_replace('+', '',$rate_plan['master_rate']);
                              $master_rate = $Profile_Management->master_rate+$rate_plan['master_rate']; 
                         }
                 }
                 $profile_master_rate = $master_rate;
              }
            }
            
            if($users_data['users_role']=='3')
            {
              $base_rate = $this->doctor_base_rate($Profile_Management->id, $users_data['parent_id']);
              $row[] = $base_rate;  
            } 
            else
            {
              $row[] = $profile_master_rate;  
            } 

            if($users_data['parent_id']==$Profile_Management->branch_id && $users_data['users_role']!=3)
            {
               $row[] = '<input type="text" width="30" class="input-tiny" value='.$Profile_Management->sort_order.' name="sortorder" onkeyup="sort_test_master('.$Profile_Management->id.',this.value)"/>';
            }
            else
            {
               $row[] = $Profile_Management->sort_order;
            } 
            $row[] = $status;
           // $row[] = date('d-M-Y h:i A',strtotime($Profile_Management->created_date)); 
            
          $btnedit='';
          $btndelete='';
          if($users_data['parent_id']==$Profile_Management->branch_id){
               if(in_array('865',$users_data['permission']['action'])){
                    $btnedit = '<a onClick="return edit_test_profile('.$Profile_Management->id.')" class="btn-custom" href="javascript:void(0)" style="'.$Profile_Management->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
               if(in_array('866',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_test_profile('.$Profile_Management->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }

          if($users_data['users_role']!=3)
          {
            $row[] = $btnedit.$btndelete;
          }
          
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_profile->count_all(),
                        "recordsFiltered" => $this->test_profile->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('144','864');
        
        $departement_id = $this->session->userdata('departement_id');
        $test_head_id =  $this->session->userdata('test_head_ids');
        $users_data = $this->session->userdata('auth_users');
        $data['departments_list'] = $this->test_profile->departments_list(5);
        $data['profile_list'] = $this->test_profile->active_profile_list();
        $data['page_title'] = "Add Profile";  
        $post = $this->input->post();
        $vendor_code = generate_unique_id(6);
        $data['form_error'] = []; 
        $data['added_test_child'] = '';
        $data['test_head_list']='';
        $data['child_test_list']='';

         $data['form_data'] = array(
                                  'prof_id'=>"",
                                  'profile_name'=>'',
                                  'print_name' =>'',
                                  'parent_test_id'=>'',
                                  'departement_id'=>'',
                                  'master_rate'=>'',
                                  'base_rate' =>'',
                                  'interpretation' =>'',
                                  'status'=>'1',
                                  'parent_test_id'=>''
                              );    


        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                $this->test_profile->save($post['row_id']);
               $this->session->set_flashdata('success','Profile successfully added.');
                echo 1;
                return false;
                die;
            }
            else
            {
                $child_test_ids = $this->session->userdata('child_test_ids');
                if(isset($child_test_ids) && !empty($child_test_ids))
                {
                   $table = "";
                   foreach($child_test_ids as $test_id)
                   {
                      $check_script = "<script>$('#addtestchildselectAll').on('click', function () { 
                                   if ($(this).hasClass('allChecked')) {
                                                  $('.childtestchecklist').prop('checked', false);
                                   } else {
                                   $('.childtestchecklist').prop('checked', true);
                                   }
                                   $(this).toggleClass('allChecked');
                              })</script>";
                      $test_data = get_test($test_id['id']);
                      if(!empty($test_data)){
                         $table  .= '<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$test_data->id.'"></td><td>'.$test_data->test_name.'</td><td>'.$test_id['sort_order'].'</td></tr>'.$check_script;
                      }
                   }
                   $data['added_test_child'] = $table;
                }
               
                $data['form_data']['departement_id']=$departement_id;
                $data['test_head_list'] = $this->get_test_heads($departement_id);

                $data['child_test_list'] = $this->get_child_test_list($test_head_id);
                
               
                $data['form_error'] = validation_errors();  
                $result = $this->load->view('test_profile/profile_master',$data,true);
                echo $result;
                die;
            }     
        }
         
        $this->load->view('test_profile/profile_master',$data);  
    }
     
    public function edit($id="")
    {
          unauthorise_permission('144','865');
          if(isset($id) && !empty($id) && is_numeric($id))
          {  
              $test_head_id =  $this->session->userdata('test_head_ids');
              $data['added_test_child'] = '';
              $data['test_head_list']='';
              $data['child_test_list']='';
              $departement_id = $this->session->userdata('departement_id');
              $child_test_ids = $this->session->userdata('child_test_ids');
              $multi_profile = $this->session->userdata('set_multi_profile');
              
              
              $test_arr = [];
              $table='';
              $test_ids=[];
              if(!isset($child_test_ids))
               {
                    $selected_test_child_result = $this->test_profile->selected_test_child($id);
                    //echo "<pre>";print_r($selected_test_child_result);die;
                    $selected_test_child_ids = array();
                    if(!empty($selected_test_child_result))
                    {             
                      foreach($selected_test_child_result as $test_data)
                         {   
                              //$test_arr[] = $test_data->id;
                              $test_list = get_test($test_data->id);
                              if(!empty($test_list))
                              {
                                $test_arr[$test_data->id] = array('id'=>$test_data->id,'name'=>str_replace("'", "`", $test_list->test_name),'sort_order'=>$test_data->sort_order);
                              }
 
                         } 
                      //  
                       
                     }
                     $this->session->set_userdata('child_test_ids',$test_arr);
               }
//print_r($test_arr); exit;
               
               $child_test_ids = $this->session->userdata('child_test_ids');

               //profile

              $profile_arr = [];
              $profile_ids=[];
              $book_profile_arr = [];
              if(!isset($multi_profile))
               {
                    $selected_profile = $this->test_profile->selected_profile($id);
                    //print_r($selected_profile); exit;
                    if(!empty($selected_profile) && isset($selected_profile))
                    { 
                      
                          
                          if(!empty($selected_profile))
                          {
                            foreach ($selected_profile as $value) 
                            {
                                $profile_ids[] = $value->id;
                            }
                            
                          }
                    }
                   //print_r($test_ids); exit;
                   $all_profile_id = array_unique($profile_ids);
                   $this->load->model('test_profile/test_profile_model');

                    //print_r($selected_test_child_result);die;
                    
                    if(!empty($all_profile_id))
                    {             
                      foreach($all_profile_id as $profile_data)
                      {  
                          //$test_arr[] = $test_data->id;
                          $result = $this->test_profile_model->profile_price($profile_data);
                          $result_order = $this->test_profile_model->profile_sort_order($id,$profile_data);
                          //echo "<pre>"; print_r($result_order); exit;
                          $total_test = 1;
                          if(!empty($child_test_ids))
                          {
                            $total_test = count($child_test_ids);
                          } 
                          if(!empty($result))
                          {
                            $profilename = $result['profile_name'];
                            $book_profile_arr[$result['id']] = array('id'=>$result['id'], 'name'=>$result['profile_name'], 'sort_order'=>$result_order[0]->sort_order, 'order'=>$total_test);
                          }
 
                      } 
                      //  
                     $this->session->set_userdata('set_multi_profile',$book_profile_arr);
                   }

                   
               }

               $multi_profile = $this->session->userdata('set_multi_profile');
               //print_r($multi_profile); die;
               
               $post = $this->input->post();
               $data['departments_list'] = $this->test_profile->departments_list(5);
               $data['profile_list'] = $this->test_profile->active_profile_list();   
               $result = $this->test_profile->get_by_id($id);   
             
               $data['added_test_child'] = $table;
               $data['page_title'] = "Update Profile ";  
               $data['form_error'] = ''; 
               $data['form_data'] = array(
                    'prof_id'=>$result['id'],
                    'profile_name'=>$result['profile_name'],
                    'print_name' =>$result['print_name'],
                    'master_rate'=>$result['master_rate'],
                    'base_rate' =>$result['base_rate'],
                    'interpretation' =>$result['interpretation'],
                    'status'=>$result['status'],
                    'departement_id'=>$departement_id
               ); 

               if(isset($post) && !empty($post))
               {   
                    $data['form_data'] = $this->_validate();
                    if($this->form_validation->run() == TRUE){
                         $this->test_profile->save($post['row_id']);
                         $this->session->set_flashdata('success','Profile successfully updated.');
                         echo 1;
                         return false;
                         die;
                    }
                    else
                    {
                         $child_test_ids = $this->session->userdata('child_test_ids');

                         if(isset($child_test_ids) && !empty($child_test_ids)){
                              $table = "";
                              $child_test_ids = array_unique($child_test_ids);
                              foreach($child_test_ids as $test_id){
                                   $check_script = "<script>$('#addtestchildselectAll').on('click', function () { 
                                        if ($(this).hasClass('allChecked')) {
                                        $('.childtestchecklist').prop('checked', false);
                                   } else {
                                    $('.childtestchecklist').prop('checked', true);
                                   }
                                   $(this).toggleClass('allChecked');
                                   })</script>";
                                   $test_data = get_test($test_id);
                                   $table  .= '<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$test_data->id.'"></td><td>'.$test_data->test_name.'</td></tr>'.$check_script;
                              }
                              $data['added_test_child'] = $table;
                              //print_r($data['added_test_child']);

                         }
                         $data['form_data']['departement_id']=$departement_id;
                         $data['test_head_list'] = $this->get_test_heads($departement_id);
                         $data['child_test_list'] = $this->get_child_test_list($test_head_id);
                         $data['form_error'] = validation_errors(); 
                        
                    }     
               }

               
               $result = $this->load->view('test_profile/profile_master',$data,true);    
               echo $result;
               die;
          }
     }


    public function set_multi_profile($profile_id="",$prof_id='')
    {
        
       if(!empty($profile_id) && is_numeric($profile_id) && $profile_id>0)
       {
          $profile_booked = $this->session->userdata('set_multi_profile'); 
          //echo "<pre>";print_r($profile_booked); die;
          $profile_ids = array();
          if(!empty($profile_booked) && isset($profile_booked))
          {
              
              if(!empty($profile_booked))
              {
                foreach ($profile_booked as $value) 
                {
                    $profile_ids[] = $value['id'];
                }
                
              }
          }
          
          if(!empty($profile_id) && isset($profile_id))
          { 
             $profile_ids[] = $profile_id;
          }

          $this->load->model('test_profile/test_profile_model');
          $all_profiles = array_unique($profile_ids);
          //echo "<pre>";print_r($all_profiles); exit;
           //echo "<pre>";print_r($all_profile);die;
           if(isset($all_profiles) && !empty($all_profiles))
           { 
              
              foreach($all_profiles as $profile)
              {
                $result = $this->test_profile_model->profile_price($profile);

                $result_order = $this->test_profile_model->profile_sort_order($prof_id,$profile);

                if(!empty($result_order[0]->sort_order))
                {
                   $p_order = $result_order[0]->sort_order; 
                }
                else
                {
                  $p_order = $result['sort_order'];
                }
                
                $booking_list = $this->session->userdata('child_test_ids');
                usort($booking_list, 'compareByName');
                $total_test = 1;
                if(!empty($booking_list))
                {
                  $total_test = count($booking_list); 
                }

                $total_test = 1;
                if(!empty($booking_list))
                {
                  $total_test = count($booking_list);
                } 
                //echo "<pre>";print_r($result); exit;
                if(!empty($result))
                {
                  
                  $profilename = $result['profile_name'];
                  $book_profile_arr[$result['id']] = array('id'=>$result['id'], 'name'=>$result['profile_name'], 'order'=>$total_test, 'sort_order'=>$p_order);
                }

                 
              }

             

           }
          
          $this->session->set_userdata('set_multi_profile',$book_profile_arr);
          $this->list_booked_profile_test();
       }
  }

public function set_multi_test() //listaddallchildtest
   {

       $post =  $this->input->post();

       
       if(isset($post['row_id']) && !empty($post['row_id']))
       {
          $test_ids = array();
          $child_test_ids = $this->session->userdata('child_test_ids');
          usort($child_test_ids, 'compareByName');
          if(!empty($child_test_ids))
          {
            foreach ($child_test_ids as $value) 
            {
                $test_ids[] = $value['id'];
            }
            
          }
          //print_r($child_test_ids); exit;
           if(!empty($post['row_id']) && isset($post['row_id']) && isset($post))
           { 
             foreach($post['row_id'] as $test_data)
             {
               if(!empty($test_data))
               {
                  $test_ids[] = $test_data;
               }
               
             }
           }
           //print_r($test_ids); exit;
           $all_test_id = array_unique($test_ids);
           $book_test_arr =[];
           if(isset($all_test_id) && !empty($all_test_id))
           { 
              
              foreach($all_test_id as $test)
              {
                $test_list = get_test($test);
               // echo "<pre>"; print_r($test_list); exit;
                //child test start//
                if($test_list->test_type_id==1)
                {
                  $child_test_list = $this->test_profile->get_test_child_of_parent($test);
                  //echo "<pre>"; print_r($child_test_list); exit;
                  if(!empty($child_test_list))
                  {
                    foreach($child_test_list as $child_tests) 
                    {
                      //echo "<pre>"; print_r($child_tests['test_id']); exit;
                      $child_details = get_test($child_tests['test_id']);

                      $book_test_arr[$child_tests['test_id']] = array('id'=>$child_tests['test_id'],'name'=>$child_details->test_name, 'sort_order'=>$child_details->sort_order);
                
                    }
                  }

                  //echo "<pre>"; print_r($book_test_arr); exit;
                }
                //child test end//
                if(!empty($test_list))
                {
                  $book_test_arr[$test] = array('id'=>$test,'name'=>$test_list->test_name, 'sort_order'=>$test_list->sort_order);
                }

                 
              }

             

           } 
          $this->session->set_userdata('child_test_ids',$book_test_arr);
          $this->list_booked_profile_test();
       }
   }
   

   public function set_ptest_order($test_id="",$order="")
   {
     if(!empty($test_id))
     {
       $booked_test_list = $this->session->userdata('child_test_ids');
       if (array_key_exists($test_id, $booked_test_list))
       {
          $booked_test_list[$test_id] = array('id'=>$booked_test_list[$test_id]['id'], 'name'=>$booked_test_list[$test_id]['name'], 'sort_order'=>$order);
          $this->session->set_userdata('child_test_ids',$booked_test_list);
       }
     }
   }

   public function set_profiletest_order($profile_id="",$order="")
   {
     if(!empty($profile_id))
     {
       $booked_profile_list = $this->session->userdata('set_multi_profile');
       //echo $booked_profile_list[$profile_id]['name'];
       //echo "<pre>"; print_r($booked_profile_list); exit;
       if (array_key_exists($profile_id, $booked_profile_list))
       {
          $booked_profile_list[$booked_profile_list[$profile_id]['id']] = array('id'=>$booked_profile_list[$profile_id]['id'], 'name'=>$booked_profile_list[$profile_id]['name'], 'order'=>$total_test, 'sort_order'=>$order);
          $this->session->set_userdata('set_multi_profile',$booked_profile_list);
       }
     }

     $booked_profile_list = $this->session->userdata('set_multi_profile');
       echo "<pre>"; print_r($booked_profile_list); exit;

   }

    

    public function list_booked_profile_test()
    {
       $booked_test = $this->session->userdata('child_test_ids');
       $profile_data = $this->session->userdata('set_multi_profile'); 
       //echo "<pre>"; print_r($profile_data); exit;
       $total_test = count($booked_test);  
       $profile_row = "";
       $p_order = 1;
       $profile_order = 1;
       $profile_order_cell = 0;

       if(isset($profile_data) && !empty($profile_data))
        {  

          foreach($profile_data as $profile)
          {
             
             $profile_order_cell = $profile['order'];
             $profile_row .= '<tr><td width="40" align="center"><input type="checkbox" name="test_id[]" class="booked_checkbox" value="p_'.$profile['id'].'" ></td><td>'.$profile['name'].'</td><td><input type="text" size="20" class="numeric" value="'.$profile['sort_order'].'" onkeyup="return set_profiletest_order('.$profile['id'].',this.value);" /></td></tr>';
          } 
        }

       $rows = '';  
         
         if(isset($booked_test) && !empty($booked_test))
         { 

            $i = 1;
            if($total_test>1)
            {
              $profile_order = $total_test-$profile_order_cell;
              if($profile_order==0)
              {
                $profile_order = 1;
              }
            }
            
            foreach ($booked_test as $test_ids) 
            {
                $id = "'".$test_ids['id']."'";
                $rows .= '<tr><td width="60" align="center"><input type="checkbox" name="test_id[]" class="booked_checkbox" value="'.$test_ids['id'].'" ></td><td>'.$test_ids['name'].'</td><td width="50px"><input type="text" size="20" class="numeric" value="'.$test_ids['sort_order'].'" onkeyup="return set_profiletest_order('.$id.',this.value);" /></td></tr>';
                             
            $i++;
            } 
         }

          if(isset($profile_data) && !empty($profile_data))
          {
            
               $rows .= $profile_row;
            
          }


         echo $rows;
    }

   
    function test_list($head_id="",$profile_id="",$search="",$dept_id="")
    {
        $post=$this->input->post();
      $this->load->model('test/test_model','test');
      $users_data = $this->session->userdata('auth_users');
      if(isset($post) && !empty($post))
      {
        $head_id=$this->input->post('test_head_id');
        $profile_id=$this->input->post('profile_id');
        $search=$this->input->post('search_text');
        $dept_id=$this->input->post('dept_id');
      }
      $rows = '';
      if($head_id>0 OR $profile_id>0 OR !empty($search)  OR $dept_id>0)
      { 
         $child_list = $this->test->head_test_list($head_id,$profile_id,$search,$dept_id);
         $branch_id = $users_data['parent_id'];
         if(!empty($child_list))
         {  
            
            foreach($child_list as $child)
            {  
              
              $rows .='<tr><td align="center"><input type="checkbox" name="employee[]" class="checklist" value="'.$child->id.'"></td>
                <td>'.$child->test_name.'</td></tr>';
            } 
       }
       else
       {
           $rows .= '<tr>  
                      <td colspan="2"><div class="text-danger">Test not available.</div></td>
                    </tr>';
       }
         echo $rows;
      }
    }



     
    private function _validate()
    {
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users');    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('profile_name', 'profile name', 'trim|required');
        //$this->form_validation->set_rules('print_name', 'print name', 'trim|required');
        $this->form_validation->set_rules('master_rate', 'patient rate', 'trim|required');
        $branch_attribute = get_permission_attr(1,2); 
        if(in_array('1',$users_data['permission']['section']) && in_array('2',$users_data['permission']['action']) && $branch_attribute>0)
        {
          $this->form_validation->set_rules('base_rate', 'branch rate', 'trim|required');
        } 
        $this->form_validation->set_rules('test', 'test', 'trim|callback_check_booked_test');

         $this->form_validation->set_rules('status', 'status', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(6); 
             $data['form_data'] = array(
                                  'prof_id'=>$post['prof_id'],
                                  'profile_name'=>$post['profile_name'],
                                  'print_name' =>$post['print_name'],
                                  'master_rate'=>$post['master_rate'],
                                  'base_rate' =>$post['base_rate'],
                                  'interpretation' =>$post['interpretation'],
                                  'status'=>$post['status']
                              ); 
                              //print_r($post);die;   
            return $data['form_data'];
        }   
    }
 
    public function check_booked_test()
    {
       $booking_list = $this->session->userdata('child_test_ids');
       $profile_list = $this->session->userdata('set_multi_profile');
       //print_r($booking_list);
       //print_r($profile_list); exit;
       if((isset($booking_list) && !empty($booking_list)) || (isset($profile_list) && !empty($profile_list)) )
       {
          return true;
       }
       else
       { 
          $this->form_validation->set_message('check_booked_test', 'Please add atleast one test or profile.');
          return false;
       }
    }

    public function delete($id="")
    {
       unauthorise_permission('144','866');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_profile->delete($id);
           $response = "Profile successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
         unauthorise_permission('144','866');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_profile->deleteall($post['row_id']);
            $response = "Profile  successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->test_profile->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Vendor']." detail";
        $this->load->view('test_profile/view',$data);     
      }
    }  
     public function add_interpretation()
    {
        ini_set('memory_limit', '-1');
      $data['page_title'] = 'Add Interpretation';      
      $data['form_error'] = [];
      $interpretation = ""; 
      $interpretation_data = $this->session->userdata('interpretation');
       
      if(isset($interpretation_data))
      {  
        $interpretation = $interpretation_data;
      }
      $data['form_data'] = array(
                                   'data_id'=>'',
                                   'interpretation'=>$interpretation
                                ); 
      $post = $this->input->post();   

      if(!empty($post))
      {
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('interpretation', 'interpretation', 'trim|required');
        if($this->form_validation->run() == TRUE) 
        { 
           $this->session->set_userdata('interpretation',$post['interpretation']);
           echo 1; return false;
        }
        else
        {
          $data['form_error'] = validation_errors();  
          $data['form_data'] = array(
                                   'data_id'=>$post['data_id'],
                                   'interpretation'=>$post['interpretation']
                                ); 
        }
      } 
      $this->load->view('test_profile/add_interpretation',$data);
    }


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('144','867');
        $data['page_title'] = 'Profile Archieve List';
        $this->load->helper('url');
        $this->load->view('test_profile/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission('144','867');
        $this->load->model('test_profile/Test_profile_archive_model','test_profile_archieve'); 
        $users_data = $this->session->userdata('auth_users');
     

            $list = $this->test_profile_archieve->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Profile_Management) { 
            $no++;
            $row = array();
            if($Profile_Management->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {
            $check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";
            }                 
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$Profile_Management->branch_id){
            
                     $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$Profile_Management->id.'">'.$check_script; 
              
            }else{
               $row[]='';
            }
              $row[] = $Profile_Management->profile_name; 
            $row[] = $Profile_Management->print_name;
            $profile_master_rate = $Profile_Management->master_rate;
            if(isset($post['branch_id']) && !empty($post) && !empty($post['branch_id']) && $post['branch_id']=='inherit')
            {
              $rate_plan = get_rate_plan($company_data['rate_id']);
              if(!empty($rate_plan))
              {  
                 if($rate_plan['master_type']==1)
                 {
                    $pos_master = strpos($rate_plan['master_rate'],'-');
                    if ($pos_master === true) 
                       {
                          $master_rate = str_replace('-', '',$rate_plan['master_rate']);
                          $master_rate = $Profile_Management->master_rate-(($Profile_Management->master_rate/100)*$rate_plan['master_rate']);
                       }
                    else
                       {
                            $master_rate = str_replace('+', '',$rate_plan['master_rate']);
                            $master_rate = $Profile_Management->master_rate+(($Profile_Management->master_rate/100)*$rate_plan['master_rate']); 
                       }
                 }
                 else
                 {
                      $pos_master = strpos($rate_plan['master_rate'],'-');
                      if ($pos_master === true) 
                         {
                            $master_rate = str_replace('-', '',$rate_plan['master_rate']);
                            $master_rate = $Profile_Management->master_rate-$rate_plan['master_rate'];
                         }
                      else
                         {
                              $master_rate = str_replace('+', '',$rate_plan['master_rate']);
                              $master_rate = $Profile_Management->master_rate+$rate_plan['master_rate']; 
                         }
                 }
                 $profile_master_rate = $master_rate;
              }
            }
            
            if($users_data['users_role']=='3')
            {
              $base_rate = $this->doctor_base_rate($Profile_Management->id, $users_data['parent_id']);
              $row[] = $base_rate;  
            } 
            else
            {
              $row[] = $profile_master_rate;  
            } 

            if($users_data['parent_id']==$Profile_Management->branch_id && $users_data['users_role']!=3)
            {
               $row[] = '<input type="text" width="30" class="input-tiny" value='.$Profile_Management->sort_order.' name="sortorder" onkeyup="sort_test_master('.$Profile_Management->id.',this.value)"/>';
            }
            else
            {
               $row[] = $Profile_Management->sort_order;
            } 
            $row[] = $status;
            //$row[] = date('d-M-Y h:i A',strtotime($Profile_Management->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if($users_data['parent_id']==$Profile_Management->branch_id){
               if(in_array('869',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_test_profile('.$Profile_Management->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('868',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$Profile_Management->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->test_profile_archieve->count_all(),
                        "recordsFiltered" => $this->test_profile_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission('144','869');
        $this->load->model('test_profile/Test_profile_archive_model','test_profile_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_profile_archieve->restore($id);
           $response = "Profile successfully restore in Profile list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('144','869');
       $this->load->model('test_profile/Test_profile_archive_model','test_profile_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_profile_archieve->restoreall($post['row_id']);
            $response = "Profile successfully restore in Profile list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('144','868');
       $this->load->model('test_profile/Test_profile_archive_model','test_profile_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->test_profile_archieve->trash($id);
           $response = "Profile successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         unauthorise_permission('144','868');
       $this->load->model('test_profile/Test_profile_archive_model','test_profile_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_profile_archieve->trashall($post['row_id']);
            $response = "Profile successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

     public function Element_Template_dropdown(){
          $profile_management_list = $this->test_profile->profile_management_list();
          $dropdown = '<option value="">Select Element Template</option>'; 
          if(!empty($profile_management_list)){
               foreach($profile_management_list as $profile_management){
                    $dropdown .= '<option value="'.$profile_management->id.'">'.$profile_management->profile_name.'</option>';
               }
          } 
          echo $dropdown; 
     }
     public function reset_all_session_data($id='')
     {
          if(empty($id))
          {
            
               $this->session->unset_userdata('departement_id');
               $this->session->unset_userdata('interpretation');
               $this->session->unset_userdata('test_head_ids');
               //$this->session->unset_userdata('set_multi_profile');
          }
          else
          {
                
          }
     }
     //get child test to be added from test_head_id
     public function get_child_test_list($test_head_id='')
     {
          $post = $this->input->post();
          $list = array();
          //echo "<pre>"; print_r($post); exit;
          if(array_key_exists('parent_test_id',$post))
          {
               
               $parent_test_id = $post['parent_test_id'];
               
               $this->session->set_userdata('test_head_ids',$parent_test_id);
               $list = $this->test_profile->get_child_test_list($parent_test_id);


          }
          else if(!empty($test_head_id))
          {
               //print_r($parent_test_id); exit;
               $this->session->set_userdata('test_head_ids',$test_head_id);
               $list = $this->test_profile->get_child_test_list($test_head_id);
          }
          
          $child_test_ids =  $this->session->userdata('child_test_ids');
          $data = '';
         
          $total_num = count($list);
          if($total_num<1){
               $data  = '<tr><td colspan="2" class="text-danger"><div class="text-center">No Records Founds</div></td></tr>'; 
          }
          else{
               for($i=0;$i<count($list);$i++) {
                    // print_r($Vendor);die;
                    $check_script='';
                    if($i==$total_num-1){
                         $check_script = "";
                    }
             
                    $data  = $data.'<tr><td align="center"><input type="checkbox" name="employee[]" class="checklist" value="'.$list[$i]['id'].'"></td><td>'.$list[$i]['test_name'].'</td></tr>'.$check_script; 
                    
               }
          }
 
        if(array_key_exists('parent_test_id',$post)){
               echo $data;
          }
          else{
               return $data;
          }
     }
     //get child test after adding 
    
      public function remove_test()
    {
       $post =  $this->input->post();
       
       if(isset($post['row_id']) && !empty($post['row_id']))
       {
           $booked_test = $this->session->userdata('child_test_ids');
           //echo "<pre>"; print_r($booked_test); exit;
           $booked_session_test = $booked_test;
           $profile_data = $this->session->userdata('set_multi_profile');
           $test_data = [];

           if(!empty($booked_test))
           {
              foreach($post['row_id'] as $tid)
              {
                 //check if test is of head type then delete its sub test also
                 $test_details = get_test($tid);
                 if($test_details->test_type_id==1)
                 {
                  $child_test_list = $this->test_profile->get_test_child_of_parent($tid);

                  if(!empty($child_test_list))
                  {
                    foreach($child_test_list as $child_tests) 
                    {
                      //echo "<pre>"; print_r($child_tests['test_id']); exit;
                       if(is_numeric($child_tests['test_id']) && array_key_exists($child_tests['test_id'],$booked_test))
                       {
                          unset($booked_test[$child_tests['test_id']]);
                       }
                
                    }
                  }


                 }
                 //end of child check
                 if(is_numeric($tid) && array_key_exists($tid,$booked_test))
                 {
                    unset($booked_test[$tid]);
                 } 
              }
              //$profile_data = $this->session->userdata('set_profile');
              //$test_ids_arr = array_keys($booked_test);
              //$test_data = array_diff($test_ids_arr,$post['test_id']);  
              //print_r($profile_data);die;
           }

           if(!empty($profile_data))
           {
              $profile_data = $this->session->userdata('set_multi_profile');
              $profile_list = $profile_data;
              foreach($post['row_id'] as $tid)
              { 
                 if(isset($profile_data) && !empty($profile_data) && !is_numeric($tid))
                 {

                    foreach($profile_data as $p_key=>$profile)
                    {
                      if($tid == 'p_'.$p_key)
                      {  
                         unset($profile_list[$p_key]); 
                      }
                    } 
                    
                 }
              }
              $this->session->set_userdata('set_multi_profile',$profile_list); 
           }
           
           if(!empty($booked_session_test))
           {
             $test_ids_arr = array_keys($booked_test);
             $this->session->set_userdata('child_test_ids',$booked_test);
             $test_ids = implode(',', $test_ids_arr);
           } 
           
           $this->list_booked_profile_test();
       }
    }
    

     //delete all added child test list
     public function deletealllistedchildtest()
     { 
       $post =  $this->input->post();

       if(isset($post['test_id']) && !empty($post['test_id']))
       {
           $booked_test = $this->session->userdata('child_test_ids');
           $booked_session_test = $booked_test;
           $profile_data = $this->session->userdata('set_multi_profile');
           $test_data = [];

           if(!empty($booked_test))
           {
              foreach($post['test_id'] as $tid)
              {
                 if(is_numeric($tid) && array_key_exists($tid,$booked_test))
                 {
                    unset($booked_test[$tid]);
                 } 
              }
              
           }

           if(!empty($profile_data))
           {
              //echo "<pre>"; print_r($profile_data); exit;
              $profile_data = $this->session->userdata('set_multi_profile');
              $profile_list = $profile_data;
              foreach($post['test_id'] as $tid)
              { 
                 if(isset($profile_data) && !empty($profile_data) && !is_numeric($tid))
                 {

                    foreach($profile_data as $p_key=>$profile)
                    {
                      if($tid == 'p_'.$p_key)
                      {  
                         unset($profile_list[$p_key]); 
                      }
                    } 
                    
                 }
              }
              $this->session->set_userdata('set_multi_profile',$profile_list); 
           }
           
           if(!empty($booked_session_test))
           {
             $test_ids_arr = array_keys($booked_test);
             $this->session->set_userdata('child_test_ids',$booked_test);
             $test_ids = implode(',', $test_ids_arr);
           } 
           
           $this->list_booked_profile_test();
       }
    











          /*$post = $this->input->post();
          $child_test_ids = $this->session->userdata('child_test_ids');
          if(isset($post) && !empty($post))
          {
               foreach($child_test_ids as $key=>$test_id)
               {
                    if(in_array($test_id,$post['row_id']))
                    {
                         unset($child_test_ids[$key]);
                    }
               }
               $child_test_ids = array_unique($child_test_ids);
               $this->session->set_userdata('child_test_ids',$child_test_ids); 
          }
          $child_test_ids = $this->session->userdata('child_test_ids');
          $data = '';
          if(!empty($child_test_ids))
          {
               $i = 1;
               foreach($child_test_ids as $test_id)
               {
                    $test_data = get_test($test_id);
                    $check_script='';
                    if($i==1)
                    {
                         $check_script = "<script>$('#addtestchildselectAll').on('click', function () { 
                         if ($(this).hasClass('allChecked')) {
                                   $('.childtestchecklist').prop('checked', false);
                         } else {
                         $('.childtestchecklist').prop('checked', true);
                         }
                         $(this).toggleClass('allChecked');
                         })</script>";
                    }
                   
                    $data  .= '<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$test_data->id.'"></td><td>'.$test_data->test_name.'</td></tr>'.$check_script;
               }
          }else{
               $data  = '<tr id="nodata"><td class="text-danger"></td><div class="text-center">No Records Founds</div></td></tr>'; 
          }
          

          
       
          echo $data;*/


     }
     //get all test heads according to selected departement
     public function get_test_heads($dept_id=''){
          $post = $this->input->post();
          $test_id = $this->session->userdata('test_head_ids');
          $result= array();
          if(array_key_exists('departments_id',$post)){
              
               $this->session->set_userdata('departement_id',$post['departments_id']);
               $result = $this->test_profile->get_test_heads($post['departments_id']);
          }
          elseif(!empty($dept_id)){
             
               $this->session->set_userdata('departement_id',$dept_id);
               $result = $this->test_profile->get_test_heads($dept_id);
          }
        
          $data='';
          for($i=0;$i<count($result);$i++){
               $selected = '';
               if($result[$i]->id==$test_id){
                    $selected = 'selected="selected"';
               }
               $data = $data.'<option value="'.$result[$i]->id.'" '.$selected.'>'.$result[$i]->test_heads.'</option>';
          }
          if(array_key_exists('departments_id',$post)){
                print_r($data);
          }
          else{
               return $data;
          }
          
     }
     public function save_sort_order_data(){
          $post = $this->input->post();
          $id = $post['test_id'];
          $sort_order_value = $post['sort_order_value'];
          if(!empty($id) && !empty($sort_order_value)){
               $result = $this->test_profile->save_sort_order_data($id,$sort_order_value);
               echo $result;
               die;
          }

     }

     public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
            $dropdown = '<label class="patient_sub_branch_label">Branches</label> <select id="sub_branch_id" onchange="get_selected_branch_test_profile_list(this.value);"><option value="">Select</option></option><option value="inherit">Inherited</option><option  selected="selected" value='.$users_data['parent_id'].'>Self</option>';
            // if(!empty($sub_branch_details)){
            //     $i=0;
            //     foreach($sub_branch_details as $key=>$value){
            //         $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
            //         $i = $i+1;
            //     }
               
            // }
            $dropdown.='</select>';
            echo $dropdown; 
        }
         
       
    }

    public function downloadall()
    {       
        unauthorise_permission('144','870'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->test_profile->downloadall($post['row_id']);
            $response = "Test successfully Downloaded successfully.";
            echo $response;
        }
    }

    public function price()
    {
        $data['page_title'] = 'Profile Price'; 
        $this->load->view('test_profile/price_list',$data);
    } 

  public function get_profile_list()
  {
    $post = $this->input->post();
    $this->load->model('test_price/test_price_model','test_price');
    if(isset($post) && !empty($post))
    {  
       $result = $this->test_profile->get_profile_list();
       $row = '';
       if(!empty($result))
       {
        $i=1;
         foreach($result as $profile)
         {
           if(!empty($profile->profile_master_rate) && $profile->profile_master_rate>0)
           {
             $profile_rate = $profile->profile_master_rate;
           }
           else
           {
              $profile_rate = $profile->master_rate; 
              if(isset($post['doctors_id']) && !empty($post['doctors_id']))
              {
                $rate_plan_data = $this->test_price->doctor_rate_plan($post['doctors_id']);
                if(!empty($rate_plan_data))
                {
                    /* Doctor Master rate */
                    if($rate_plan_data[0]->master_type==1)
                    {
                       $pos_mstr = strpos($rate_plan_data[0]->master_rate,'-');
                       if ($pos_mstr === true) 
                       {
                          $master_rate = str_replace('-', '',$rate_plan_data[0]->master_rate);
                          $master_rate = $profile_rate-(($profile_rate/100)*$rate_plan_data[0]->master_rate);
                       }
                       else
                       {
                            $master_rate = str_replace('+', '',$rate_plan_data[0]->master_rate);
                            $master_rate = $profile_rate+(($profile_rate/100)*$rate_plan_data[0]->master_rate);
                       }
                    }
                    else
                    {
                        $pos_mstr = strpos($rate_plan_data[0]->master_rate,'-');
                         if ($pos_mstr === true) 
                         {
                            $master_rate = str_replace('-', '',$rate_plan_data[0]->master_rate);
                            $master_rate = $profile_rate-$rate_plan_data[0]->master_rate;
                         }
                         else
                         {
                              $master_rate = str_replace('+', '',$rate_plan_data[0]->master_rate);
                              $master_rate = $profile_rate+$rate_plan_data[0]->master_rate;
                         }
                    }
                    //////////////////////////
                }
                else
                {
                   $master_rate = $profile_rate;
                }
                $profile_rate = $master_rate;
              }
           }

           if(!empty($profile->profile_base_rate) && $profile->profile_base_rate>0)
           {
             $profile_base_rate = $profile->profile_base_rate;
           }
           else
           {
             $profile_base_rate = $profile->base_rate;
             if(isset($post['doctors_id']) && !empty($post['doctors_id']))
              {
                $rate_plan_data = $this->test_price->doctor_rate_plan($post['doctors_id']);
                if(!empty($rate_plan_data))
                {
                    /* Doctor Base rate */
                    if($rate_plan_data[0]->base_type==1)
                    {
                       $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                       if ($pos_base === true) 
                       {
                          $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                          $base_rate = $profile_base_rate-(($profile_base_rate/100)*$rate_plan_data[0]->base_rate);
                       }
                       else
                       {
                            $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                            $base_rate = $profile_base_rate+(($profile_base_rate/100)*$rate_plan_data[0]->base_rate); 
                       }

                    }
                    else
                    {
                        $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                         if ($pos_base === true) 
                         {
                            $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                            $base_rate = $profile_base_rate-$rate_plan_data[0]->base_rate;
                         }
                         else
                         {
                              $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                              $base_rate = $profile_base_rate+$rate_plan_data[0]->base_rate;
                         }
                    }
                    //////////////////////////
                }
                else
                {
                  $base_rate = $profile_base_rate;
                }
                $profile_base_rate = $base_rate;
              }  
           }
           $profile_rate = number_format($profile_rate,2,'.','');
           $profile_base_rate = number_format($profile_base_rate,2,'.','');
           $row .= '<tr>
                    <td><input type="checkbox" class="checklist" name="test_id['.$i.'][id]" value="'.$profile->id.'" /></td>
                    <td>'.$profile->profile_name.'</td>
                    <td><input type="text" name="test_id['.$i.'][test_rate]" id="rate_'.$i.'"   value="'.$profile_rate.'"/></td>
                    <td><input type="text" name="test_id['.$i.'][test_base_rate]" id="base_rate_'.$i.'"   value="'.$profile_base_rate.'"/></td>
                    <td><button data-rateid="rate_'.$i.'" data-baserate="base_rate_'.$i.'" data-testid="'.$profile->id.'" data-docid="" type="button" class="btn-custom" id="save_test" onclick="save_profile_rate(this);">Save</button></td>
                 </tr>   
                    ';
          $i++;
         }
         
       }
       else
       {
         $row = '<tr><td colspan="5" align="center"><font color="#b64a30">Profile not available.</font></td></tr>';
       }
       echo $row;
    }
  }

  public function save_profile_rate()
  {
     $post = $this->input->post();
     $users_data = $this->session->userdata('auth_users'); 
     if(!empty($post))
     {
        $branch_id = $users_data['parent_id'];
        $doc_id = '';
        if(!empty($post['branch_id']))
        {
          $branch_id = $post['branch_id'];
        }
        if(!empty($post['doc_id']))
        {
          $doc_id = $post['doc_id'];
        }
        $data = array(
                       'branch_id'=>$branch_id,
                       'doctor_id'=>$doc_id,
                       'profile_id'=>$post['test_id'],
                       'master_rate'=>$post['rate'],
                       'base_rate'=>$post['base_rate']
                     );
        $this->test_profile->save_profile_rate($data);
     }
  }

  public function save_all_price_list()
  {
     $users_data = $this->session->userdata('auth_users'); 
     $post = $this->input->post();
     if(!empty($post['test_id']))
     {
       foreach($post['test_id'] as $profile)
       {  
          $branch_id = $users_data['parent_id'];
          $doc_id = '';
          if(!empty($post['branch_id']) && $post['type']==0)
          {
            $branch_id = $post['branch_id'];
          }
          if(!empty($post['doctors_id']) && $post['type']==1)
          {
            $doc_id = $post['doctors_id'];
          }

          if(!empty($profile['id']) && $profile['id']>0)
          { 
            $data = array(
               'branch_id'=>$branch_id,
               'doctor_id'=>$doc_id,
               'profile_id'=>$profile['id'],
               'master_rate'=>$profile['test_rate'],
               'base_rate'=>$profile['test_base_rate']
             );
            $this->test_profile->save_profile_rate($data);
          } 
       }
     }
  }

  public function doctor_base_rate($profile_id="", $doctor_id="")
    {  
      $this->load->model('test_price/test_price_model','test_price');
      if(isset($doctor_id) && !empty($doctor_id))
          {
            $check_doctor_rate = $this->test_price->doctor_profile_price($profile_id, $doctor_id);  
            if(empty($check_doctor_rate['doctor_base_rate']))
            {
              $test_base_rate = $check_doctor_rate['test_base_rate'];
              $rate_plan_data = $this->test_price->doctor_rate_plan($doctor_id);
              if(!empty($rate_plan_data))
                {
                /* Doctor Base rate */
                if($rate_plan_data[0]->base_type==1)
                  {
                    $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                    if ($pos_base === true) 
                       {
                          $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                          $base_rate = $test_base_rate-(($test_base_rate/100)*$rate_plan_data[0]->base_rate);
                       }
                    else
                       {
                            $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                            $base_rate = $test_base_rate+(($test_base_rate/100)*$rate_plan_data[0]->base_rate); 
                       }

                    }
                    else
                    {
                        $pos_base = strpos($rate_plan_data[0]->base_rate,'-');
                         if ($pos_base === true) 
                         {
                            $base_rate = str_replace('-', '',$rate_plan_data[0]->base_rate);
                            $base_rate = $test_base_rate-$rate_plan_data[0]->base_rate;
                         }
                         else
                         {
                              $base_rate = str_replace('+', '',$rate_plan_data[0]->base_rate);
                              $base_rate = $test_base_rate+$rate_plan_data[0]->base_rate;
                         }
                    }
                    //////////////////////////
                }
                else
                {
                  $base_rate = $test_base_rate;
                }
            }
            else
            {
               $base_rate = $check_doctor_rate['test_base_rate'];
            }
            
                
          }  
        return  number_format($base_rate, 2, '.', ''); 
    }



    public function update_test_excel()
    {
        $this->load->model('general/general_model');
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Profile ID','Profile Name','Profile Print Name','Patient Rate','Sort Order','Status');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->test_profile->search_profile_data();
        //echo '<pre>'; print_r($list);die;
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0; 
            foreach($list as $test_master)
            {       
                array_push($rowData,$test_master->id,$test_master->profile_name,$test_master->print_name,$test_master->master_rate,$test_master->sort_order, $test_master->status);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $doctors_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $doctors_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=export_profile_master_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
    }


    public function import_update_test_master_excel()
    {
        //unauthorise_permission(97,628);
        $this->load->library('excel');
        $data['page_title'] = 'Import Updated Profile Master excel';
        $arr_data = array();
        $header = array();
        $path='';
        //print_r($_FILES); die;
        if(isset($_FILES) && !empty($_FILES))
        {
          
            $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
            if(!isset($_FILES['test_list']) || $_FILES['test_list']['error']>0)
            {
               
               $this->form_validation->set_rules('test_list', 'file', 'trim|required');  
            } 
            $this->form_validation->set_rules('name', 'name', 'trim|required');  
            if($this->form_validation->run() == TRUE)
            {
               //echo DIR_UPLOAD_PATH.'import_master/'; die;
                $config['upload_path']   = DIR_UPLOAD_PATH.'temp/'; 
                $config['allowed_types'] = '*'; 
                $this->load->library('upload', $config);
                if(!$this->upload->do_upload('test_list')) 
                {
                    $error = array('error' => $this->upload->display_errors()); 
                    $data['file_upload_eror'] = $error; 
                }
                else 
                { 
                    $data = $this->upload->data();
                    $path = $config['upload_path'].$data['file_name'];
                    //read file from path
                    $objPHPExcel = PHPExcel_IOFactory::load($path);
                    //get only the Cell Collection
                    $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
                    //extract to a PHP readable array format

                    foreach ($cell_collection as $cell) 
                    {
                        $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
                        $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
                        $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
                        //header will/should be in row 1 only. of course this can be modified to suit your need.
                        if ($row == 1) 
                        {
                            $header[$row][$column] = $data_value;
                        } 
                        else 
                        {
                            $arr_data[$row][$column] = $data_value;
                        }
                    }
                    //send the data in an array format
                    $data['header'] = $header;
                    $data['values'] = $arr_data;
                   
                }

                //echo "<pre>";print_r($arr_data); exit;
                if(!empty($arr_data))
                { 
                    $arrs_data = array_values($arr_data);
                    $total_test = count($arrs_data);
                    
                   $array_keys = array('id','profile_name','print_name','master_rate','sort_order','status');

                    $count_array_keys = count($array_keys);
                    $array_values_keys = array('A','B','C','D','E','F');
                    $patient_data = array();
                    
                    $j=0;
                    $m=0;
                    $data='';
                    $test_all_data= array();
                    for($i=0;$i<$total_test;$i++)
                    {
                        $med_data_count_values[$i] = count($arrs_data[$i]);
                        for($p=0;$p<$count_array_keys;$p++)
                        {
                            if(array_key_exists($array_values_keys[$p],$arrs_data[$i]))
                            {
                                $test_all_data[$i][$array_keys[$p]] = $arrs_data[$i][$array_values_keys[$p]];
                            }
                            else
                            {
                                $test_all_data[$i][$array_keys[$p]] ="";
                            }
                        }
                    } 
                    //echo "<pre>";print_r($test_all_data); exit;
                    $this->test_profile->udpate_all_test($test_all_data);
                }

                 
                if(!empty($path))
                {
                    unlink($path);
                }
               
                echo 1;
                return false;
            }
            else
            {

                $data['form_error'] = validation_errors();
                

            }
        }

        $this->load->view('test_profile/import_update_test_master_excel',$data);
    }
 
}
