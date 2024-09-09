<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Default_vals extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('default_vals/default_vals_model','default_vals');
        $this->load->library('form_validation');
    }

    public function index()
    {   
        unauthorise_permission('141','841');
        $this->session->unset_userdata('child_test_ids');
        $data['page_title'] = 'Default Value List'; 
        $this->load->view('default_vals/list',$data);
    }

    public function ajax_list()
    { 
        $users_data = $this->session->userdata('auth_users');
        unauthorise_permission('141','841');
        $list = $this->default_vals->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $default_vals) {
         // print_r($default_vals);die;
            $no++;
            $row = array();
            if($default_vals->status==1)
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
            if($users_data['parent_id']==$default_vals->branch_id)
            {
                 $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$default_vals->id.'">'.$check_script; 
             }
            else
            {
               $row[]='';
            }

            $highlight = array('1'=>'Yes for all', '0'=>'No for all', '2'=>'Yes for selected test', '3'=>'No for selected test');
            $row[] = $default_vals->default_vals;
            $row[] = $highlight[$default_vals->highlight];  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($default_vals->created_date)); 
 
          
          $btnedit='';
          $btndelete='';
        
          if($users_data['parent_id']==$default_vals->branch_id){
               if(in_array('843',$users_data['permission']['action'])){
                    $btnedit = ' <a  class="btn-custom" href="'.base_url('default_vals/edit/').$default_vals->id.'" style="'.$default_vals->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }

               if(in_array('844',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_default_vals('.$default_vals->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
          }

          
             $row[] = $btnedit.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->default_vals->count_all(),
                        "recordsFiltered" => $this->default_vals->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('141','842');
        $data['page_title'] = "Add Default Value";  
        $this->load->model('general/general_model'); 
        $data['dept_list'] = $this->general_model->active_department_list(5); 
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'default_vals'=>"",
                                  'highlight'=>1,
                                  'status'=>"1",
                                  'departments_id'=>'',
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->default_vals->save();
                //echo 1;
                //return false;
                redirect('default_vals');
                
            }
            else
            {
                $data['form_error'] = validation_errors();  

            }     
        }
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
           $data['added_test_child'] = $table;
        }
       $this->load->view('default_vals/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('141','843');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->default_vals->get_by_id($id); 
        $this->load->model('general/general_model'); 
        $data['dept_list'] = $this->general_model->active_department_list(5);  
        $data['page_title'] = "Update Default Value";  
        $post = $this->input->post();
        $data['added_test_child'] = '';
        $child_test_ids = $this->session->userdata('child_test_ids'); 
        //print_r($child_test_ids);die;
        $data['form_error'] = ''; 
        $test_arr = [];
        $table='';
        if(!isset($child_test_ids))
         {
              $selected_test_child_result = $this->default_vals->selected_test_child($id);

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
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'default_vals'=>$result['default_vals'], 
                                  'highlight'=>$result['highlight'], 
                                  'status'=>$result['status']
                                  ); 
         
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
                    // echo $table;die;
               /////////////////////////////////////   
          
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->default_vals->save();
                //echo 1;
                //return false;
                redirect('default_vals');
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
                $child_test_ids = $this->session->userdata('child_test_ids');

                 if(isset($child_test_ids) && !empty($child_test_ids))
                 {
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
                 
            }     
        }
        $data['added_test_child'] = $table;     
       $this->load->view('default_vals/add',$data);       
      }
    }
     
    private function _validate()
    {

        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('default_vals', 'default vals', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'default_vals'=>$post['default_vals'],
                                        'highlight'=>$post['highlight'], 
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
           $result = $this->default_vals->delete($id);
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
            $result = $this->default_vals->deleteall($post['row_id']);
            $response = "Default Value successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->default_vals->get_by_id($id);  
        $data['page_title'] = $data['form_data']['default_vals']." detail";
        $this->load->view('default_vals/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('141','845');
        $data['page_title'] = 'Default Value Archive List';
        $this->load->helper('url');
        $this->load->view('default_vals/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('141','845');
        $this->load->model('default_vals/default_vals_archive_model','default_vals_archive'); 
        $list = $this->default_vals_archive->get_datatables();
              
                   
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $default_vals) {
         // print_r($default_vals);die;
            $no++;
            $row = array();
            if($default_vals->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$default_vals->id.'">'.$check_script;  
            $highlight = array('0'=>'Yes for all', '1'=>'No for all', '2'=>'Yes for selected test', '3'=>'No for selected test');
            $row[] = $default_vals->default_vals;
            $row[] = $highlight[$default_vals->highlight];  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($default_vals->created_date)); 
 

          $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('847 ',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_default_vals('.$default_vals->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('846',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$default_vals->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->default_vals_archive->count_all(),
                        "recordsFiltered" => $this->default_vals_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('141','847');
        $this->load->model('default_vals/default_vals_archive_model','default_vals_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->default_vals_archive->restore($id);
           $response = "Default Value successfully restore in Default Values list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('141','847');
        $this->load->model('default_vals/default_vals_archive_model','default_vals_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->default_vals_archive->restoreall($post['row_id']);
            $response = "Default Value successfully restore in Default Values list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('141','846');
        $this->load->model('default_vals/default_vals_archive_model','default_vals_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->default_vals_archive->trash($id);
           $response = "Default Value successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('141','846');
        $this->load->model('default_vals/default_vals_archive_model','default_vals_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->default_vals_archive->trashall($post['row_id']);
            $response = "Default Value successfully deleted parmanently.";
            echo $response;
        }
    } 


    public function listaddallchildtest()
    {
          $post = $this->input->post();  
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
               $result = $this->default_vals->addalltest($post['row_id']);
               $data = '';
               $total_num = count($result); 

               if($total_num<1)
               {   
                    $data  = '<tr id="nodata"><td colspan="2" class="text-danger"><div class="text-center">No Records Founds</div></td></tr>'; 
               }

               else{
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
               $data  = '<tr id="nodata"><td class="text-danger"></td><div class="text-center">No Records Found</div></td></tr>'; 
          }
          

          
       
          echo $data;


     }
   
    
    
}
?>