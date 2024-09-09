<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_category extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('canteen/category/Category_model','category');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        //unauthorise_permission('164','946');
        $data['page_title'] = 'Categories List'; 
        $this->load->view('canteen/category/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('164','946');
       $users_data = $this->session->userdata('auth_users');
       
            $list = $this->category->get_datatables();
         
       
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Category) {
         // print_r($Category);die;
            $no++;
            $row = array();
            if($Category->status==1)
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
          if($users_data['parent_id']==$Category->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$Category->id.'">'.$check_script;
          }else{
               $row[]='';
          } 
            $row[] = $Category->category;  
            $row[] = $status;
           // $row[] = date('d-M-Y H:i A',strtotime($Category->created_date)); 
           
          $btnedit='';
          $btndelete='';
        
          if($users_data['parent_id']==$Category->branch_id){
               //if(in_array('948',$users_data['permission']['action'])){
                    $btnedit = ' <a onClick="return edit_Category('.$Category->id.');" class="btn-custom" href="javascript:void(0)" style="'.$Category->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               //}
                //if(in_array('949',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_Category('.$Category->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               //}
          }
      
             $row[] = $btnedit.$btndelete; 
             
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->category->count_all(),
                        "recordsFiltered" => $this->category->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission('164','947');
        $data['page_title'] = "Add Category";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'category'=>"",
                                  'status'=>""
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->category->save();

                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->load->view('canteen/category/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('164','948');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->category->get_by_id($id);  
        $data['page_title'] = "Update Category";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'category'=>$result['category'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->category->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('canteen/category/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('category', 'category', 'trim|required|callback_check_item_category'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  

            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'category'=>$post['category'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
    public function check_item_category($str)
    {

    $post = $this->input->post();

    if(!empty($str))
    {
        $this->load->model('canteen/general/common_model','general'); 
              if(!empty($post['data_id']) && $post['data_id']>0)
              {
                 $data_cat= $this->category->get_by_id($post['data_id']);
                if($data_cat['category']==$str && $post['data_id']==$data_cat['id'])
                {
                 return true;  
                }
                else
                {
                   $check_item_category = $this->general->check_item_category($str);
                   
                    if(empty($check_item_category))
                    {
                    return true;
                    }
                    else
                    {
                    $this->form_validation->set_message('check_item_category', 'The category already exists.');
                    return false;
                    }
                }
                    
              }
              else
              {
                $check_item_category = $this->general->check_item_category($str);
                if(empty($check_item_category))
                {
                   return true;
                }
                else
                {
            $this->form_validation->set_message('check_item_category', 'The category already exists.');
            return false;
                }
        }  
    }
    else
    {
      $this->form_validation->set_message('check_item_category', 'The category field is required.');
            return false; 
    } 
    }
 
    public function delete($id="")
    {
      // unauthorise_permission('164','949');
       if(!empty($id) && $id>0)
       {
           $result = $this->category->delete($id);
           $response = "Category successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('164','949');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->category->deleteall($post['row_id']);
            $response = "Categorys successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->category->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Category']." detail";
        $this->load->view('canteen/category/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('164','950');
        $data['page_title'] = 'Categories Archive List';
        $this->load->helper('url');
        $this->load->view('canteen/category/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('164','950');
        $this->load->model('canteen/category/Category_archive_model','category_archive'); 

    
            $list = $this->category_archive->get_datatables();
           
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $Category) { 
            $no++;
            $row = array();
            if($Category->status==1)
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
         

                    $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$Category->id.'">'.$check_script; 
             
            $row[] = $Category->category;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($Category->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
         
               //if(in_array('950',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_Category('.$Category->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               //}
               //if(in_array('950',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$Category->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               //}
     
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->category_archive->count_all(),
                        "recordsFiltered" => $this->category_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('164','950');
        $this->load->model('canteen/category/Category_archive_model','category_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->category_archive->restore($id);
           $response = "Category successfully restore in Categorys list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        //unauthorise_permission('164','950');
        $this->load->model('canteen/category/Category_archive_model','category_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->category_archive->restoreall($post['row_id']);
            $response = "Category successfully restore in Categorys list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('164','950');
        $this->load->model('canteen/category/Category_archive_model','category_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->category_archive->trash($id);
           $response = "Category successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('164','950');
        $this->load->model('canteen/category/Category_archive_model','category_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->category_archive->trashall($post['row_id']);
            $response = "Category successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

     public function Category_dropdown(){

          $Category_list = $this->category->Category_list();
          $dropdown = '<option value="">Select Category</option>'; 
          if(!empty($Category_list)){
               foreach($Category_list as $Category){
                    $dropdown .= '<option value="'.$Category->id.'">'.$Category->category.'</option>';
               }
          } 
          echo $dropdown; 
     }
    
}
?>