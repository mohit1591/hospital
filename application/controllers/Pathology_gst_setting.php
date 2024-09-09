<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pathology_gst_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pathology_gst_setting/pathology_gst_setting_model','gst_model');
        $this->load->library('form_validation');
    }

    public function add()
    {
        unauthorise_permission('141','842');
        $post = $this->input->post();
        $users_data = $this->session->userdata('auth_users');
        $data['page_title'] = "Pathology GST Setting";  
        $this->load->model('general/general_model'); 
        $data['dept_list'] = $this->general_model->active_department_list(5);
        $this->load->model('test_profile/Test_profile_model','test_profile');
        $data['profile_list'] = $this->test_profile->active_profile_list();
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
               /* 
                $user_data = $this->session->userdata('auth_users');
		$test_list = $this->session->userdata('child_test_ids');
		echo "<pre>"; print_r($test_list); exit;*/
		
                $this->gst_model->save();
                $this->session->set_flashdata('success','GST updated successfully.');
                //echo 1;
                //return false;
                redirect('pathology_gst_setting/add');
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

            }     
        }
        
        
        
        //modify
        $gst_result = $this->gst_model->get_by_id($users_data['parent_id']);
        //print_r($gst_result);die;    
        $id= $gst_result['id'];
        $gst_vals= $gst_result['gst_vals'];
        $highlight = $gst_result['highlight'];
        if(empty($post))
        {
            $child_test_ids = $this->session->unset_userdata('child_test_ids'); 
            
        }
        //print_r($child_test_ids);die;
        $data['form_error'] = ''; 
        $test_arr = [];
        $table='';
        if(!isset($child_test_ids) && !empty($id))
         {
              $selected_test_child_result = $this->gst_model->selected_test_child($id);
              //print_r($selected_test_child_result);die;   

              //print_r($selected_test_child_result);die;
              $selected_test_child_ids = array();
              if(!empty($selected_test_child_result))
              {             
                foreach($selected_test_child_result as $test_data)
                   {  
                        $test_arr[] = $test_data->id; 
                   }  
                $this->session->set_userdata('child_test_ids',$test_arr); 
               }
         }
        $child_test_ids = $this->session->userdata('child_test_ids');   
        //print_r($child_test_ids);die;    
        
         
         /////////// Test Row ///////////////// 
               $child_test_ids = array_unique($child_test_ids);

                  if(!empty($child_test_ids))
                  {                     
                      $i = 1; 
                      foreach($child_test_ids as $test_id)
                      {
                              $test_datas = get_test($test_id);
                              //echo '<pre>';print_r($test_datas);die;
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

                              $table  .= '<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$test_datas->id.'"></td><td>'.$test_datas->test_name.'</td></tr>'.$check_script; 
                              $i++;
                         }   
                          
                     } 
       $data['added_test_child'] = $table;
        //modify end
        
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>$id,
                                  'gst_vals'=>$gst_vals, 
                                  'highlight'=>$highlight,
                                  );     

        
        
        $child_test_ids = $this->session->userdata('child_test_ids');

        $table = "";
        if(isset($child_test_ids) && !empty($child_test_ids))
        { 
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
              $test_data = get_test($test_id);
              if(!empty($test_data))
              {
                 $table  .= '<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$test_data->id.'"></td><td>'.$test_data->test_name.'</td></tr>'.$check_script;
              }
              //echo $table;die;
           }
           //echo $table;die;   
           
           
           
           
        }
        
        $profile_data = $this->session->userdata('set_gst_profile'); 
        //echo "<pre>"; print_r($profile_data); exit;
        $profile_row = "";
        
        if(isset($profile_data) && !empty($profile_data))
        {  
        
            foreach($profile_data as $profile)
            {
                $profile_row .= '<tr><td width="40" align="center"><input type="checkbox" name="test_id[]" class="childtestchecklist" value="p_'.$profile['id'].'" ></td><td>'.$profile['name'].'</td></tr>';
            } 
        }
        if(isset($profile_data) && !empty($profile_data))
        {
            $table .= $profile_row;
        }
        
        
        $data['added_test_child'] = $table;
        
       $this->load->view('pathology_gst_setting/add',$data);       
    }
    
   
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('gst_vals', 'GST vals', 'trim'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'gst_vals'=>$post['gst_vals'],
                                        'departments_id'=>$post['departments_id'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('141','844');
       if(!empty($id) && $id>0)
       {
           $result = $this->gst_model->delete($id);
           $response = "Default Value successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('141','844');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->gst_model->deleteall($post['row_id']);
            $response = "Default Value successfully deleted.";
            echo $response;
        }
    }

   


    
    

    public function listaddallchildtest()
    {
          $post = $this->input->post();  
          //profile added
          
       /*$profile_data = $this->session->userdata('set_gst_profile'); 
       //echo "<pre>"; print_r($profile_data); exit;
       $total_test = count($booked_test);  
       $profile_row = "";
       
       if(isset($profile_data) && !empty($profile_data))
        {  

          foreach($profile_data as $profile)
          {
             
             
             $profile_row .= '<tr><td width="40" align="center"><input type="checkbox" name="test_id[]" class="childtestchecklist booked_checkbox" value="p_'.$profile['id'].'" ></td><td>'.$profile['name'].'</td></tr>';
          } 
        }*/
         
          
          //end of profile
          
          $child_test_ids = $this->session->userdata('child_test_ids');
          if(!empty($child_test_ids))
          {
            $child_test_ids = array_unique($child_test_ids);
          }
          if(empty($child_test_ids))
          {
               $child_test_ids = array();
          }
          if(!empty($post))
          {
               $result = $this->gst_model->addalltest($post['row_id']);
               $data = '';
               $total_num = count($result); 

               if($total_num<1)
               {   
                    $data  = '<tr id="nodata"><td colspan="2" class="text-danger"><div class="text-center">No Records Founds</div></td></tr>'; 
               }
               else
               {
                    for($i=0;$i<count($result);$i++) 
                    {
                         // print_r($Vendor);die;
                         $check_script='';
                         if($i==$total_num-1){
                              $check_script = "<script>$('#addtestchildselectAll').on('click', function () { 
                              if ($(this).hasClass('allChecked')) {
                                   $('.childtestchecklist').prop('checked', false);
                              } else {
                              $('.childtestchecklist').prop('checked', true);
                              }
                              $(this).toggleClass('allChecked');
                              })</script>";
                         }
                         array_push($child_test_ids,$result[$i]['id']);
                       $data  = $data.'<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$result[$i]['id'].'"></td><td>'.$result[$i]['test_name'].'</td></tr>'.$check_script;
                        
                    }
               }
            $this->session->set_userdata('child_test_ids',$child_test_ids);
          
           /* if(isset($profile_data) && !empty($profile_data))
            {
                $data .= $profile_row;
            }*/
                
               echo $data;
          }
     }


     public function deletealllistedchildtest()
     { 
          $post = $this->input->post();
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
          /*$child_test_ids = $this->session->userdata('child_test_ids');
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
               $data  = '<tr id="nodata"><td class="text-danger"></td><div class="text-center">No Records Found</div></td></tr>'; 
          }*/
          
          
          
          ////////////profile
          if(!empty($profile_data))
           {
              $profile_data = $this->session->userdata('set_gst_profile');
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
              $this->session->set_userdata('set_gst_profile',$profile_list); 
           }
          
          

          
       $this->list_gst_profile_test();
         // echo $data;


     }
     
     
     public function set_profile($profile_id="",$prof_id='')
    {
        
       if(!empty($profile_id) && is_numeric($profile_id) && $profile_id>0)
       {
          $profile_booked = $this->session->userdata('set_gst_profile'); 
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
           if(isset($all_profiles) && !empty($all_profiles))
           { 
              
              foreach($all_profiles as $profile)
              {
                  $result = $this->test_profile_model->profile_price($profile);
                 // echo "<pre>"; print_r($result); //exit;
                if(!empty($result))
                {
                  
                  $book_profile_arr[$result['id']] = array('id'=>$result['id'], 'name'=>$result['profile_name']);
                }

                 
              }

             

           }
          //echo "<pre>"; print_r($book_profile_arr); die;
          $this->session->set_userdata('set_gst_profile',$book_profile_arr);
          $this->list_gst_profile_test();
       }
  }
  
  public function list_gst_profile_test()
    {
        
       $booked_test = $this->session->userdata('child_test_ids');
       $profile_data = $this->session->userdata('set_gst_profile'); 
       //echo "<pre>"; print_r($booked_test); exit;
       
       $profile_row = "";
       //set profiles
       if(isset($profile_data) && !empty($profile_data))
        {  

          foreach($profile_data as $profile)
          {
             
             
             $profile_row .= '<tr><td width="40" align="center"><input type="checkbox" name="test_id[]" class="childtestchecklist booked_checkbox" value="p_'.$profile['id'].'" ></td><td>'.$profile['name'].'</td></tr>';
          } 
        }
        //end of profile
        //test started
        $rows = '';  
         
         if(isset($booked_test) && !empty($booked_test))
         { 
            foreach ($booked_test as $tids) 
            {
                $result = $this->gst_model->test_details($tids);
                //echo "<pre>"; print_r($result); 
                $rows .='<tr><td align="center"><input type="checkbox" name="employee[]" class="childtestchecklist" value="'.$result[0]['id'].'"></td><td>'.$result[0]['test_name'].'</td></tr>'.$check_script;
                       
              
            } 
         }

          if(isset($profile_data) && !empty($profile_data))
          {
            
               $rows .= $profile_row;
            
          }

        //echo "<pre>"; print_r($rows); die;
         echo $rows;
    }
    
    
    public function remove_test()
    {
       $post =  $this->input->post();
       
       if(isset($post['row_id']) && !empty($post['row_id']))
       {
           $booked_test = $this->session->userdata('child_test_ids');
           //echo "<pre>"; print_r($booked_test); exit;
           $booked_session_test = $booked_test;
           $profile_data = $this->session->userdata('set_gst_profile');
           $test_data = [];

           if(!empty($booked_test))
           {
              /*foreach($post['row_id'] as $tid)
              {
                 
                 //end of child check
                 if(is_numeric($tid) && array_key_exists($tid,$booked_test)) //
                 {
                     //echo "<pre>"; print_r($tid); die;
                    unset($booked_test[$tid]);
                 } 
              }*/
              
              foreach($booked_test as $key=>$test_id)
               {
                    if(in_array($test_id,$post['row_id']))
                    {
                         unset($booked_test[$key]);
                    }
               }
               
               $this->session->set_userdata('child_test_ids',$booked_test);
              
           }

           if(!empty($profile_data))
           {
              $profile_data = $this->session->userdata('set_gst_profile');
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
              $this->session->set_userdata('set_gst_profile',$profile_list); 
           }
           
           if(!empty($booked_session_test))
           {
             $test_ids_arr = array_keys($booked_test);
             $this->session->set_userdata('child_test_ids',$booked_test);
             $test_ids = implode(',', $test_ids_arr);
           } 
           
           $this->list_gst_profile_test();
       }
    }
   
    
    
}
?>