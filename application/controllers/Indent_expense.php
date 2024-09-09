<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Indent_expense extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('indent/indent_model','indent');
        $this->load->library('form_validation');
    }

    public function index()
    { 
     
       unauthorise_permission('54','354');
        $data['page_title'] = 'Indent List'; 
        $this->load->view('indent/list',$data);
    }

    public function ajax_list()
    { 
       unauthorise_permission('54','354');
        $list = $this->indent->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $indent) {
         // print_r($unit);die;
            $no++;
            $row = array();
            if($indent->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$indent->id.'">'.$check_script; 
            $row[] = $indent->indent;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($indent->created_date)); 
 
       $users_data = $this->session->userdata('auth_users');
       $btnedit='';
       $btndelete='';
      if(in_array('356',$users_data['permission']['action'])){
          $btnedit = '<a onClick="return edit_indent('.$indent->id.');" class="btn-custom" href="javascript:void(0)" style="'.$indent->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
       }
        
        if(in_array('357',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_indent('.$indent->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
            }
          $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->indent->count_all(),
                        "recordsFiltered" => $this->indent->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
       unauthorise_permission('54','355');
        $data['page_title'] = "Add Indent";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'indent'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->indent->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('indent/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('54','356');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->indent->get_by_id($id);  
        $data['page_title'] = "Update Indent";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'indent'=>$result['indent'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->indent->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('indent/add',$data);       
      }
    }
     
    private function _validate()
    {
    
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('indent', 'indent', 'trim|required|callback_check_indent'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'indent'=>$post['indent'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
   public function check_indent($str)
  {
    $post = $this->input->post();
    if(!empty($str ))
    {
        if(!empty($post['data_id']) && $post['data_id']>0)
        {
                return true;
        }
        else
        {
                $companydata = $this->indent->check_indent($str);
                if(empty($companydata))
                {
                   return true;
                }
                else
                {
            $this->form_validation->set_message('check_indent', 'The indent already exists.');
            return false;
                }
        }  
    }
    else
    {
      $this->form_validation->set_message('check_indent', 'The indent field is required.');
            return false; 
    } 
  }
    public function delete($id="")
    {  
       unauthorise_permission('54','357');
       if(!empty($id) && $id>0)
       {
           $result = $this->indent->delete($id);
           $response = "Indent successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('54','357');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->indent->deleteall($post['row_id']);
            $response = "Indent successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->indent->get_by_id($id);  
        $data['page_title'] = $data['form_data']['indent']." detail";
        $this->load->view('indent/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('54','417');
        $data['page_title'] = 'Indent Archive List';
        $this->load->helper('url');
        $this->load->view('indent/archive',$data);
    }

    public function archive_ajax_list()
    {
       unauthorise_permission('54','417');
        $this->load->model('indent/indent_archive_model','indent_archive'); 

        $list = $this->indent_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $indent) {
            $no++;
            $row = array();
            if($indent->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$indent->id.'">'.$check_script; 
            $row[] = $indent->indent;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($indent->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
         if(in_array('359',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_indent('.$indent->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
         }
         if(in_array('358',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$indent->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->indent_archive->count_all(),
                        "recordsFiltered" => $this->indent_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('54','359');
        $this->load->model('indent/indent_archive_model','indent_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->indent_archive->restore($id);
           $response = "Indent successfully restore in Indent list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('54','359');
        $this->load->model('indent/indent_archive_model','indent_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->indent_archive->restoreall($post['row_id']);
            $response = "Indent successfully restore in Indent list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('54','358');
        $this->load->model('indent/indent_archive_model','indent_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->indent_archive->trash($id);
           $response = "Indent successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('54','358');
        $this->load->model('indent/indent_archive_model','indent_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->indent_archive->trashall($post['row_id']);
            $response = "Indent successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function indent_dropdown()
  {
      $indents_list = $this->indent->indent_list();
      $dropdown = '<option value="">Select Indent</option>'; 
      if(!empty($indents_list))
      {
        foreach($indents_list as $indent_list)
        {
           $dropdown .= '<option value="'.$indent_list->id.'">'.$indent_list->indent.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>