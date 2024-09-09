<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Website_setting extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('website_setting/website_setting_model','website_setting');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('150','905');
        $data['page_title'] = 'Configuration setting list'; 
        $this->load->view('website_setting/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission('150','905');
        $list = $this->website_setting->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $website_setting) 
        {
            // print_r($relation);die;
            $no++;
            $row = array();
           
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
          $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$website_setting->id.'">'.$check_script; 
          //$row[] = $website_setting->var_title;
          $row[] = $website_setting->var_name;
          $row[] = nl2br($website_setting->setting_value);  

          $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          $btnrestore='';
          if(in_array('907',$users_data['permission']['action']))
          {
          $btnedit = ' <a onClick="return edit_website_setting('.$website_setting->id.');" class="btn-custom" href="javascript:void(0)" style="'.$website_setting->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('908',$users_data['permission']['action']))
          {
          $btnrestore = ' <a class="btn-custom" onClick="return restore_website_setting('.$website_setting->id.')" href="javascript:void(0)" title="Restore" data-url="512"><i class="fa fa-refresh"></i> Restore Value </a> '; 
          }
          $row[] = $btnedit.$btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->website_setting->count_all(),
                        "recordsFiltered" => $this->website_setting->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        // unauthorise_permission('92','58');
        $data['page_title'] = "Add Configuration Setting";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'var_title'=>"",
                                  'var_name'=>'',
                                  'setting_value'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->website_setting->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('website_setting/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('150','907');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->website_setting->get_by_id($id);  
        $data['page_title'] = "Update Configuration Setting";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'var_title'=>$result['var_title'], 
                                  'var_name'=>$result['var_name'], 
                                  'setting_value'=>$result['setting_value']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->website_setting->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('website_setting/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('var_title', 'Var Title', 'trim|required'); 
        $this->form_validation->set_rules('var_name', 'Var Name', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'var_title'=>$post['var_title'],
                                        'var_name'=>$post['var_name'],
                                        'setting_value'=>$post['setting_value']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       // unauthorise_permission('92','60');
       if(!empty($id) && $id>0)
       {
           $result = $this->website_setting->delete($id);
           $response = "Configuration setting successfully deleted.";
           echo $response;
       }
    }

    public function restore_setting($id="")
    {
       unauthorise_permission('150','908');
       if(!empty($id) && $id>0)
       {
           $result = $this->website_setting->restore_setting($id);
           $response = "Configuration setting restore successfully.";
           echo $response;
       }
    }

    function deleteall()
    {
       // unauthorise_permission('92','60');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->website_setting->deleteall($post['row_id']);
            $response = "Configuration setting successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->website_setting->get_by_id($id);  
        $data['page_title'] = $data['form_data']['website_setting']." detail";
        $this->load->view('website_setting/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        // unauthorise_permission('92','61');
        $data['page_title'] = 'Configuration setting archive list';
        $this->load->helper('url');
        $this->load->view('website_setting/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('15','61');
        $this->load->model('website_setting/website_setting_archive_model','website_setting_archive'); 

        $list = $this->website_setting_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $website_setting) { 
            $no++;
            $row = array();
            
            
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$website_setting->id.'">'.$check_script; 
            $row[] = $website_setting->var_title;
            $row[] = $website_setting->var_name;
            $row[] = $website_setting->setting_value;  
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('63',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_website_setting('.$website_setting->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('62',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$website_setting->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->website_setting_archive->count_all(),
                        "recordsFiltered" => $this->website_setting_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        // unauthorise_permission('92','63');
        $this->load->model('website_setting/website_setting_archive_model','website_setting_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->website_setting_archive->restore($id);
           $response = "Configuration setting successfully restore in website setting list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        // unauthorise_permission('92','63');
        $this->load->model('website_setting/website_setting_archive_model','website_setting_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->website_setting_archive->restoreall($post['row_id']);
            $response = "Configuration setting successfully restore in website setting list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        // unauthorise_permission('92','62');
        $this->load->model('website_setting/website_setting_archive_model','website_setting_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->website_setting_archive->trash($id);
           $response = "Configuration setting successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        // unauthorise_permission('92','62');
        $this->load->model('website_setting/website_setting_archive_model','website_setting_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->website_setting_archive->trashall($post['row_id']);
            $response = "Configuration setting successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function website_setting_dropdown()
  {

      $website_setting_list = $this->website_setting->website_setting_list();
      $dropdown = '<option value="">Select Website Setting</option>'; 
      if(!empty($website_setting_list))
      {
        foreach($website_setting_list as $website_setting)
        {
           $dropdown .= '<option value="'.$website_setting->id.'">'.$website_setting->setting_value.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>