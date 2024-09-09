<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_item_unit extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('stock_item_unit/stock_item_unit_model','stock_item_unit');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('142','848');
        $data['page_title'] = 'Stock item unit'; 
        $this->load->view('stock_item_unit/list',$data);
    }

    public function ajax_list()
    { 
    $users_data = $this->session->userdata('auth_users');
    unauthorise_permission('142','848');

    $sub_branch_details = $this->session->userdata('sub_branches_data');
    $parent_branch_details = $this->session->userdata('parent_branches_data');
      
            $list = $this->stock_item_unit->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_item_unit) {
         // print_r($stock_item_unit);die;
            $no++;
            $row = array();
            if($stock_item_unit->status==1)
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
            if($users_data['parent_id']==$stock_item_unit->branch_id){
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$stock_item_unit->id.'">'.$check_script;
            } 
            else{
               $row[]='';
            }
            $row[] = $stock_item_unit->stock_item_unit;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($stock_item_unit->created_date)); 
 
       
       $btnedit='';
       $btndelete='';
       
       if($users_data['parent_id']==$stock_item_unit->branch_id){
          if(in_array('850',$users_data['permission']['action'])){
               $btnedit = '<a onClick="return edit_unit('.$stock_item_unit->id.');" class="btn-custom" href="javascript:void(0)" style="'.$stock_item_unit->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('851',$users_data['permission']['action'])){
               $btndelete = '<a class="btn-custom" onClick="return delete_unit('.$stock_item_unit->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a>';   
          }
      }
      
             $row[] = $btnedit.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_item_unit->count_all(),
                        "recordsFiltered" => $this->stock_item_unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('142','849');
        $data['page_title'] = "Add Stock Item Unit";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'stock_item_unit'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->stock_item_unit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('stock_item_unit/add',$data);       
    }
    
    public function edit($id="")
    {
     unauthorise_permission('142','850');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->stock_item_unit->get_by_id($id);  
        $data['page_title'] = "Update Stock Item Unit";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'stock_item_unit'=>$result['stock_item_unit'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->stock_item_unit->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('stock_item_unit/add',$data);       
      }
    }
     
    private function _validate()
    {
    
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('stock_item_unit', 'Stock Item Unit', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'stock_item_unit'=>$post['stock_item_unit'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {  
       unauthorise_permission('142','851');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_item_unit->delete($id);
           $response = "Stock Item Unit successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('142','851');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_item_unit->deleteall($post['row_id']);
            $response = "Stock Item Unit successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->stock_item_unit->get_by_id($id);  
        $data['page_title'] = $data['form_data']['stock_item_unit']." detail";
        $this->load->view('stock_item_unit/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('142','852');
        $data['page_title'] = 'stock_item_unit archive list';
        $this->load->helper('url');
        $this->load->view('stock_item_unit/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('142','852');
        $this->load->model('stock_item_unit/stock_item_unit_archive_model','stock_item_unit_archive'); 
        $list = $this->stock_item_unit_archive->get_datatables();
              
                  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $stock_item_unit) {
         // print_r($stock_item_unit);die;
            $no++;
            $row = array();
            if($stock_item_unit->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$stock_item_unit->id.'">'.$check_script; 
            $row[] = $stock_item_unit->stock_item_unit;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($stock_item_unit->created_date)); 
           $users_data = $this->session->userdata('auth_users');

           $btnrestore='';
           $btndelete='';
          if(in_array('854',$users_data['permission']['action'])){
             $btnrestore = ' <a onClick="return restore_unit('.$stock_item_unit->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';      
          }
          if(in_array('853',$users_data['permission']['action'])){
      $btndelete = '<a onClick="return trash('.$stock_item_unit->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
             
          $row[] = $btndelete.$btnrestore;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->stock_item_unit_archive->count_all(),
                        "recordsFiltered" => $this->stock_item_unit_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('142','854');
        $this->load->model('stock_item_unit/stock_item_unit_archive_model','stock_item_unit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_item_unit_archive->restore($id);
           $response = "Stock Item Unit successfully restore in Stock Item Unit list.";
           echo $response;
       }
    }

    function restoreall()
    { 
     unauthorise_permission('142','854');
        $this->load->model('stock_item_unit/stock_item_unit_archive_model','stock_item_unit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_item_unit_archive->restoreall($post['row_id']);
            $response = "Stock Item Unit successfully restore inStock Item Unit list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('142','853');
        $this->load->model('stock_item_unit/stock_item_unit_archive_model','stock_item_unit_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->stock_item_unit_archive->trash($id);
           $response = "Stock Item Unit successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('142','853');
        $this->load->model('stock_item_unit/stock_item_unit_archive_model','stock_item_unit_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->stock_item_unit_archive->trashall($post['row_id']);
            $response = "Stock Item Unit successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function unit_dropdown()
  {
      $unit_list = $this->stock_item_unit->unit_list();
      $dropdown = '<option value="">Select Stock Item Unit</option>'; 
      if(!empty($unit_list))
      {
        foreach($unit_list as $stock_item_unit)
        {
           $dropdown .= '<option value="'.$stock_item_unit->id.'">'.$stock_item_unit->stock_item_unit.'</option>';
        }
      } 
      echo $dropdown; 
  }
  
  

}
?>