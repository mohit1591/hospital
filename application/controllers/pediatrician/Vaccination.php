<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination extends CI_Controller {
 
  function __construct() 
  {
    parent::__construct();  
    auth_users();  
    $this->load->model('pediatrician/vaccination/vaccination_entry_model','vaccination_entry');
    $this->load->library('form_validation');
    }

    
    public function index()
    {
        $data['page_title'] = 'Vaccination Master List'; 
        $data['manuf_company_list'] = $this->vaccination_entry->manuf_company_list();
        $this->session->unset_userdata('vaccination_entry_search');

        // Default Search Setting
        $this->load->model('default_search_setting/default_search_setting_model'); 
        $default_search_data = $this->default_search_setting_model->get_default_setting();
        if(isset($default_search_data[1]) && !empty($default_search_data) && $default_search_data[1]==1)
        {
            $start_date = '';
            $end_date = '';
        }
        else
        {
            $start_date = date('d-m-Y');
            $end_date = date('d-m-Y');
        }
        // End Defaul Search
        $data['form_data']=array('start_date'=>$start_date,'vaccination_name'=>'','end_date'=>$end_date,'vaccination_company'=>'');
        $this->load->view('pediatrician/vaccination/list',$data);
    }

    public function ajax_list()
    {  
        $list = $this->vaccination_entry->get_datatables();  
        //print_r( $list);
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        //$row='';
        foreach ($list as $vaccination_entry) { 
            $no++;
            $row = array();
            if($vaccination_entry->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($vaccination_entry->state))
            {
                $state = " ( ".ucfirst(strtolower($vaccination_entry->state))." )";
            }
            //////////////////////// 
            
            ////////// Check  List /////////////////
            $check_script = "";
            if($i==$total_num)
            {

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                  
           // $doctor_type = array('0'=>'Referral','1'=>'Attended','2'=>'Referral/Attended');
            $row[] = '<input type="checkbox" name="vaccination_entry[]" class="checklist" value="'.$vaccination_entry->id.'">';
            $row[] = $vaccination_entry->vaccination_code;
            $row[] = $vaccination_entry->vaccination_name;
            $row[] = $vaccination_entry->company_name;
            
            $row[] = $status; 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
            $btnview = '';
           if(in_array('1054',$users_data['permission']['action'])){
                 $btnedit = ' <a onClick="return edit_vaccination_entry('.$vaccination_entry->id.');" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_entry->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
            }
            /*if(in_array('1059',$users_data['permission']['action'])){
                $btnview=' <a class="btn-custom" onclick="return view_vaccination_entry('.$vaccination_entry->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
           }*/
            if(in_array('1055',$users_data['permission']['action'])){
                $btndelete = ' <a class="btn-custom" onClick="return delete_vaccination_entry('.$vaccination_entry->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
            }       
            $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_entry->count_all(),
                        "recordsFiltered" => $this->vaccination_entry->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);
        //output to json format
        echo json_encode($output);
    }


  public function add()
  {
    $data['page_title'] = "Add Vaccination";
    $data['form_error'] = [];
    $data['manuf_company_list'] = $this->vaccination_entry->manuf_company_list(); 
    $reg_no = generate_unique_id(35);
  
        //echo $reg_no;die;
    $post = $this->input->post();
    $data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "vaccination_code"=>$reg_no,
                                    "vaccination_name"=>"",
                                    "manuf_company"=>"",
                                    'salt'=>'',
                                    "status"=>"1", 
                            );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_entry->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        
    $this->load->view('pediatrician/vaccination/add',$data);
  }
        public function reset_search()
        {
            $this->session->unset_userdata('vaccination_entry_search');
        }
     public function advance_search()
     {
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
         
          $data['unit_list'] = $this->vaccination_entry->unit_list();
          $data['form_data'] = array(
                                      
                                      "vaccination_name"=>"",
                                      "vaccination_company"=>"",
                                      "vaccination_code"=>"",
                                      );
          if(isset($post) && !empty($post))
          {
            //print_r($post);die;
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('vaccination_entry_search', $marge_post);
          }
          $vaccination_entry_search = $this->session->userdata('vaccination_entry_search');
          if(isset($vaccination_entry_search) && !empty($vaccination_entry_search))
          {
              $data['form_data'] = $vaccination_entry_search;
          }
          $this->load->view('pediatrician/vaccination/advance_search',$data);
   }

  public function edit($id="")
  {
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->vaccination_entry->get_by_id($id); 
        
        $data['manuf_company_list'] = $this->vaccination_entry->manuf_company_list();
        $data['page_title'] = "Update Vaccination";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "vaccination_code"=>$result['vaccination_code'],
                                    "vaccination_name"=>$result['vaccination_name'],
                                    "manuf_company"=>$result['manuf_company'],
                                    "salt"=>$result['salt'],
                                    "status"=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->vaccination_entry->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('comission_data'); 
       $this->load->view('pediatrician/vaccination/add',$data);       
      }
    }
     
    private function _validate()
    {
        $users_data = $this->session->userdata('auth_users');  
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('vaccination_name', 'vaccination name', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) 
        {  
             $reg_no = generate_unique_id(10); 
            $data['form_data'] = array(
                                    "data_id"=>$_POST['data_id'], 
                                    "vaccination_code"=>$reg_no,
                                    "vaccination_name"=>$_POST['vaccination_name'],
                                    "manuf_company"=>$_POST['manuf_company'],
                                    'salt'=>$_POST['salt'],
                                    "status"=>1
                                  );  
            return $data['form_data'];
        }   
    }

 
    public function delete($id="")
    {
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_entry->delete($id);
           $response = "Vaccination successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_entry->deleteall($post['row_id']);
            $response = "Vaccination successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
      if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vaccination_entry->get_by_id($id);  
        $data['page_title'] = $data['form_data']['vaccination_name']." detail";
        $this->load->view('pediatrician/vaccination/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        $data['page_title'] = 'Vaccination archive list';
        $this->load->helper('url');
        $this->load->view('pediatrician/vaccination/archive',$data);
    }

    public function archive_ajax_list()
    {
        $this->load->model('pediatrician/vaccination/vaccination_entry_archive_model','vaccination_entry_archive'); 

        $list = $this->vaccination_entry_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_entry) { 
            $no++;
            $row = array();
            if($vaccination_entry->status==1)
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

            // $check_script = "<script>$('#selectAll').on('click', function () { 
            //                       if ($(this).hasClass('allChecked')) {
            //                           $('.checklist').prop('checked', false);
            //                       } else {
            //                           $('.checklist').prop('checked', true);
            //                       }
            //                       $(this).toggleClass('allChecked');
            //                   })</script>";
            }                  

            $row[] = '<input type="checkbox" name="vaccination_entry[]" class="checklist" value="'.$vaccination_entry->id.'">';
            $row[] = $vaccination_entry->vaccination_code;
            $row[] = $vaccination_entry->vaccination_name;
            $row[] = $vaccination_entry->company_name;
            $row[] = $status; 
            $row[] = date('d-M-Y H:i A',strtotime($vaccination_entry->created_date));  
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            
           if(in_array('373',$users_data['permission']['action'])){
                $btnrestore = ' <a onClick="return restore_vaccination_entry('.$vaccination_entry->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
           }
           if(in_array('372',$users_data['permission']['action'])){
                $btndelete = ' <a onClick="return trash('.$vaccination_entry->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
           }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_entry_archive->count_all(),
                        "recordsFiltered" => $this->vaccination_entry_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
       $this->load->model('pediatrician/vaccination/vaccination_entry_archive_model','vaccination_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_entry_archive->restore($id);
           $response = "Vaccination successfully restore in vaccination entry list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission(183,1057);
        $this->load->model('pediatrician/vaccination/vaccination_entry_archive_model','vaccination_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_entry_archive->restoreall($post['row_id']);
            $response = "Vaccination successfully restore in vaccination entry list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       $this->load->model('pediatrician/vaccination/vaccination_entry_archive_model','vaccination_entry_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_entry_archive->trash($id);
           $response = "Vaccination successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        $this->load->model('pediatrician/vaccination/vaccination_entry_archive_model','vaccination_entry_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_entry_archive->trashall($post['row_id']);
            $response = "Vaccination successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function vaccination_dropdown()
  {
      $medicine_entry_list = $this->vaccination_entry->employee_type_list();
      $dropdown = '<option value="">Select Vaccination</option>'; 
      if(!empty($medicine_entry_list))
      {
        foreach($medicine_entry_list as $vaccination_entry)
        {
           $dropdown .= '<option value="'.$vaccination_entry->id.'">'.$vaccination_entry->vaccination_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}