<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_category extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('expense_category/Expense_category_model','expense_category');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('50','309');
        $data['page_title'] = 'Expense Category List'; 
        $this->load->view('expense_category/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('50','309');
         $users_data = $this->session->userdata('auth_users');
      
        $sub_branch_details = $this->session->userdata('sub_branches_data');
       $parent_branch_details = $this->session->userdata('parent_branches_data');
     
            $list = $this->expense_category->get_datatables();
         
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $expense_category) {
         // print_r($expense_category);die;
            $no++;
            $row = array();
            if($expense_category->status==1)
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
            $checkboxs = "";
            if($users_data['parent_id']==$expense_category->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$expense_category->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            
            $row[] = $expense_category->exp_category;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($expense_category->created_date)); 
            $btnedit='';
            $btndelete='';
         
            if($users_data['parent_id']==$expense_category->branch_id)
            {
              if(in_array('311',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_expense_category('.$expense_category->id.');" class="btn-custom" href="javascript:void(0)" style="'.$expense_category->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('312',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_expense_category('.$expense_category->id.')" href="javascript:void(0)" title="Delete" data-url="550"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
            
           
             $row[] = $btnedit.$btndelete;           
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->expense_category->count_all(),
                        "recordsFiltered" => $this->expense_category->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('50','310');
        $data['page_title'] = "Add Expense Category";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'expense_category'=>"",
                                  'status'=>"1",
                                  'gender' =>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->expense_category->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('expense_category/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('50','311');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->expense_category->get_by_id($id);  
        $data['page_title'] = "Update Expense Category";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'expense_category'=>$result['exp_category'], 
                                  'status'=>$result['status'],
                                  
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->expense_category->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('expense_category/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('expense_category', 'expense category', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'expense_category'=>$post['expense_category'], 
                                        'status'=>$post['status'],
                                        
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('50','312');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->expense_category->delete($id);
           $response = "Expense Category successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('50','312');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->expense_category->deleteall($post['row_id']);
            $response = "Expense Categorys successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->expense_category->get_by_id($id);  
        $data['page_title'] = $data['form_data']['expense_category']." detail";
        $this->load->view('expense_category/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('50','313');
        $data['page_title'] = 'Expense Category Archive List';
        $this->load->helper('url');
        $this->load->view('expense_category/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('50','313');
        $this->load->model('expense_category/Expense_category_archive_model','expense_category_archive'); 
        $users_data = $this->session->userdata('auth_users');
        $branch_id = $this->input->post('branch_id');
          $list='';
        
               $list = $this->expense_category_archive->get_datatables();
              
             
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $expense_category) {
         // print_r($expense_category);die;
            $no++;
            $row = array();
            if($expense_category->status==1)
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
           if($users_data['parent_id']==$expense_category->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$expense_category->id.'">'.$check_script;
           }else{
               $row[]='';
           } 
            $row[] = $expense_category->exp_category;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($expense_category->created_date)); 
            
            $btnrestore='';
            $btndelete='';
            if($users_data['parent_id']==$expense_category->branch_id){
                 if(in_array('70',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_expense_category('.$expense_category->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
                   }
                   if(in_array('69',$users_data['permission']['action'])){
                         $btndelete = ' <a onClick="return trash('.$expense_category->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
                    }
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->expense_category_archive->count_all(),
                        "recordsFiltered" => $this->expense_category_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('50','316');
        $this->load->model('expense_category/Expense_category_archive_model','expense_category_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->expense_category_archive->restore($id);
           $response = "Expense Category successfully restore in Expense Category list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('50','316');
        $this->load->model('expense_category/Expense_category_archive_model','expense_category_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->expense_category_archive->restoreall($post['row_id']);
            $response = "Expense Categorys successfully restore in Expense Category list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('50','314');
        $this->load->model('expense_category/expense_category_archive_model','expense_category_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->expense_category_archive->trash($id);
           $response = "Expense Category successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('50','314');
        $this->load->model('expense_category/Expense_category_archive_model','expense_category_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->expense_category_archive->trashall($post['row_id']);
            $response = "Expense Category successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function expense_category_dropdown()
  {
      $expense_category_list = $this->expense_category->expense_category_list();
      $dropdown = '<option value="">Select expense category</option>'; 
      if(!empty($expense_category_list))
      {
        foreach($expense_category_list as $expense_category)
        {
           $dropdown .= '<option value="'.$expense_category->id.'">'.$expense_category->exp_category.'</option>';
        }
      } 
      echo $dropdown; 
  }

   
    ///// rate Archive end  ///////////////
}
?>