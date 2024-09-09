<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vaccination_packages extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users(); 
        $this->load->model('vaccination_packages/vaccination_packages_model','vaccination_packages');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission(187,1060);
        $this->session->unset_userdata('vaccination_kit_data');
        $data['page_title'] = 'Vaccination Kit List'; 
        $this->load->view('vaccination_packages/list',$data);
    }
    

    public function ajax_list()
    { 
          unauthorise_permission(187,1060);
          $users_data = $this->session->userdata('auth_users');
          $this->session->unset_userdata('vaccination_kit_data');


          $list = $this->vaccination_packages->get_datatables();
          $data = array();
          $no = $_POST['start'];
          $i = 1;
          $total_num = count($list);
          foreach ($list as $vaccination_packages) {
          // print_r($Vendor);die;
            $no++;
            $row = array();
            if($vaccination_packages->status==1)
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
           if($users_data['parent_id']==$vaccination_packages->branch_id){
         
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vaccination_packages->id.'">'.$check_script;
              
            }
            else{
               $row[]='';
            }
            $row[] = $vaccination_packages->title; 
            $row[] = $vaccination_packages->amount;
            
            //$row[] = $vaccination_packages->quantity;
            
          
            $row[] = $status;
          
         
        
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_packages->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if($users_data['parent_id']==$vaccination_packages->branch_id){
               if(in_array('1062',$users_data['permission']['action'])){
                    $btnedit = '<a onClick="return edit_packages('.$vaccination_packages->id.')" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_packages->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
               if(in_array('1063',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_packages('.$vaccination_packages->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_packages->count_all(),
                        "recordsFiltered" => $this->vaccination_packages->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function vaccination_stock_list()
    {
        unauthorise_permission(187,1067);
        $data['page_title'] = 'Vaccination Kit Stock List';
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
 
        $data['form_data'] = array(
           "start_date"=>$start_date,
           "end_date"=>$end_date
          );
        $this->load->view('vaccination_packages/vaccination_kit_stock_list',$data);
    }
    public function medicine_kit_history_ajax_list()
    { 
          unauthorise_permission(187,1067);
          $users_data = $this->session->userdata('auth_users');
          // $this->session->unset_userdata('vaccination_kit_data');
          $list = $this->vaccination_packages->get_vaccination_kit_history_datatables();
          $data = array();
          $no = $_POST['start'];
          $i = 1;
          $total_num = count($list);
          foreach ($list as $vaccination_packages) {
          // print_r($Vendor);die;
            $no++;
            $row = array();
            if($vaccination_packages->status==1)
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
           if($users_data['parent_id']==$vaccination_packages->branch_id)
           {
            $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vaccination_packages->id.'">'.$check_script;
           }
            else{
               $row[]='';
            }
            $row[] = $vaccination_packages->title; 
            $row[] = $vaccination_packages->amount;
            $row[] = $vaccination_packages->quantity;
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_packages->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if($users_data['parent_id']==$vaccination_packages->branch_id){
               if(in_array('1062',$users_data['permission']['action'])){
                    $btnedit = '<a onClick="return edit_packages('.$vaccination_packages->id.')" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_packages->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
               }
               if(in_array('1063',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_packages('.$vaccination_packages->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
               }
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_packages->count_all(),
                        "recordsFiltered" => $this->vaccination_packages->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
     public function vaccination_kit_ajax_list()
    { 
          unauthorise_permission(187,1060);
         $users_data = $this->session->userdata('auth_users');
      
       
           
            $list = $this->vaccination_packages->get_vaccination_kit_datatables();
           

      
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_packages) {
         // print_r($Vendor);die;
            $no++;
            $row = array();
            if($vaccination_packages->status==1)
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
            $qty_data = $this->vaccination_packages->get_vec_kit_qty($vaccination_packages->id,$vaccination_packages->vaccination_kit_stock_branch_id);
            // if(empty($vaccination_packages->qty_kit)){
            //    $branch_id= $vaccination_packages->branch_id;
            // }
            // else
            // {
            //    $branch_id= $vaccination_packages->vaccination_kit_stock_branch_id;
            // }
              if(empty($qty_data['total_qty'])){
               $branch_id= $vaccination_packages->branch_id;
            }
            else
            {
               $branch_id= $vaccination_packages->vaccination_kit_stock_branch_id;
            }
           if($users_data['parent_id']==$branch_id && $qty_data['total_qty']>0){
         
                    $row[] = '<input type="checkbox" name="vendor[]" class="checklist" value="'.$vaccination_packages->id.'">'.$check_script;
              
            }

            else{
               $row[]='';
            }
            $row[] = $vaccination_packages->title; 
            $row[] = $vaccination_packages->amount;
              
          // if(empty($vaccination_packages->qty_kit))
          // {
          //      $qty_data=0;
          // }
          // else
          // {
          //      $qty_data = $vaccination_packages->qty_kit;
          // }
              if(empty($qty_data['total_qty']))
          {
               $qty_data=0;
          }
          else
          {
               $qty_data = $qty_data['total_qty'];
          }
             
                 $row[] = $qty_data;
           
            $row[] = $status;
          
         
        
           // $row[] = date('d-M-Y H:i A',strtotime($vaccination_packages->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnadd='';
          $btnhistory='';
          $btnmanage='';

          if($users_data['parent_id']==$branch_id && $qty_data>0){
               // if(in_array('573',$users_data['permission']['action'])){
                    $btnadd = '<a onClick="return package_quantity_add('.$vaccination_packages->id.')" class="btn-custom" href="javascript:void(0)" style="'.$vaccination_packages->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Add</a>';
                  //$history_url = base_url('vaccination_kit_history/index/').$vaccination_packages->id.'/0';
                    //$btnhistory = ' <a class="btn-custom"   href="'.$history_url.'" title="History" data-url="512"><i class="fa fa-history"></i> History </a> '; 
                     // $btnmanage = ' <a class="btn-custom" onClick="return packages_manage('.$vaccination_packages->id.')" href="javascript:void(0)" title="Manage" data-url="512"><i class="fa fa-trash"></i> Manage</a> '; 
               // }
          }
          $row[] = $btnadd.$btnhistory.$btnmanage;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->vaccination_packages->count_all(),
                        "recordsFiltered" => $this->vaccination_packages->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    public function add()
    {
         unauthorise_permission(187,1061);
         $vaccination_kit_data = $this->session->userdata('vaccination_kit_data');

         // $data['departments_list'] = $this->vaccination_packages->vaccination_list();
         $data['page_title'] = "Add Vaccination Kit";  
         $post = $this->input->post();
         $vendor_code = generate_unique_id(6);
         $data['form_error'] = []; 
         $data['added_vaccination'] = '';
         $data['test_head_list']='';
         $data['vaccination_list']='';
         $data['form_data'] = array(
                                  'pack_id'=>"",
                                  'package_title'=>'',
                                  'package_quantity'=>'',
                                  'amount' =>'',
                                  'status'=>'1',
                              );    


         if(isset($post) && !empty($post))
          {   
               $data['form_data'] = $this->_validate();
               if($this->form_validation->run() == TRUE)
               {
                
                   $this->vaccination_packages->save($post['row_id']);
                   $this->session->set_flashdata('success','Vaccination Kit successfully added.');
                   echo 1;
                   return false;
                   die;
               }
               else
               {
                   $check=1;
                   $table = $this->set_row_vaccination_kit($check);
                   $data['added_vaccination'] = $table;
                   $data['form_error'] = validation_errors();  
                   $result = $this->load->view('vaccination_packages/add_packages',$data,true);
                   echo $result;
                   die;
               }     
          }
          $this->load->view('vaccination_packages/add_packages',$data);  
    }
     
     public function edit($id=""){
          unauthorise_permission(187,1062);
          if(isset($id) && !empty($id) && is_numeric($id)){ 
               $data['added_vaccination'] = '';
               $data['vaccination_list']='';
               $vaccination_kit_data = $this->session->userdata('vaccination_kit_data');

               $vaccination_arr = array();
               $table='';
               if(!isset($vaccination_kit_data))
               {
                    $selected_vaccination_result = $this->vaccination_packages->selected_medicine($id);
                   
                    $selected_medicine_kit_data = array();
                    if(!empty($selected_vaccination_result))
                    {             
                         foreach($selected_vaccination_result as $vaccination_data)
                         {  
                              if(empty($vaccination_data['conversion']) || $vaccination_data['conversion']=='0'){
                                   $conversion=1;
                              }
                              else
                              {
                                   $conversion = $vaccination_data['conversion'];
                              }
                              $vaccination_arr[$vaccination_data['id']] = array('id'=>$vaccination_data['id'], 'vaccination_code'=>$vaccination_data['vaccination_code'], 'vaccination_name'=>$vaccination_data['vaccination_name'],'company_name'=>$vaccination_data['company_name'] ,'qty1'=>$vaccination_data['unit1_qty'], 'qty2'=>$vaccination_data['unit2_qty'], 'total_qty'=>$vaccination_data['total_qty'],'conversion'=>$conversion); 
                         }
                         
                         $this->session->set_userdata('vaccination_kit_data',$vaccination_arr);   
                          
                    }
               }
               $check=1;
               $table = $this->set_row_vaccination_kit($check);
               /////////////////////////////////////  
               $post = $this->input->post();
               $result = $this->vaccination_packages->get_by_id($id);   
               $data['added_vaccination'] = $table;
               $data['page_title'] = "Update Vaccination Kit";  
               $data['form_error'] = ''; 
               $data['form_data'] = array(
                    'pack_id'=>$result['id'],
                    'package_title'=>$result['title'],
                    'amount' =>$result['amount'],
                    'package_quantity'=>$result['quantity'],
                    'status'=>$result['status'],
               ); 
               if(isset($post) && !empty($post)){   

                    $data['form_data'] = $this->_validate();
                    if($this->form_validation->run() == TRUE){
                         $this->vaccination_packages->save($post['row_id']);
                         $this->session->set_flashdata('success','Vaccination Kit successfully updated.');
                         echo 1;
                         return false;
                         die;
                    }else{
                         $check=1;
                         $table = $this->set_row_vaccination_kit($check);   
                         $data['added_vaccination'] = $table;
                         $pack=1;
                         $data['vaccination_list'] = $this->get_added_vaccination_list($pack);
                         $data['form_error'] = validation_errors(); 
                         $result = $this->load->view('vaccination_packages/add_packages',$data,true);  
                         echo $result;
                         die;
                    }     
               }
              
               $this->load->view('vaccination_packages/add_packages',$data); 
            
          }

     }
 public function add_vaccination_kit_quantity($kit_id="",$opt="",$row_id=""){
    
     $data['page_title'] = 'Add Vaccination Kit Quantity';
     $post = $this->input->post();
     $result = $this->vaccination_packages->get_by_id($kit_id); 
    
     $data['form_data'] = array(
          'data_id'=>$result['id'],
          'vaccination_kit_name'=>$result['title'],
          'opt'=>$opt,
          'row_id'=>$row_id
     ); 
     if(isset($post) && !empty($post)){
          $data['form_data'] = $this->validate_vaccination_kit_data();
          if($this->form_validation->run() == TRUE){
               $this->vaccination_packages->add_vaccination_kit_quantity($kit_id,$opt,$row_id);
               echo 1;
               return false;
                        
          }else{
                         
               $data['form_error'] = validation_errors(); 
          }
     }


          $this->load->view('vaccination_packages/vaccination_kit_quantity_add',$data);
    }
   public function validate_vaccination_kit_data(){
       $post = $this->input->post();
       $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('quantity', 'quantity', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
               
               $data['form_data'] = array(
                   'data_id'=>$post['data_id'],
                   'vaccination_kit_name'=>$post['vaccination_kit_name'],
                   'quantity'=>$post['quantity'],
                   
               );    
               return $data['form_data'];
        }   


   }
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('package_title', 'Vaccination Kit Name', 'trim|required|callback_check_package');
        $this->form_validation->set_rules('amount', 'amount', 'trim|required');
        $this->form_validation->set_rules('package_quantity', 'package quantity', 'trim|required');
      
        $this->form_validation->set_rules('status', 'status', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(6); 
             $data['form_data'] = array(
                                  'pack_id'=>$post['pack_id'],
                                  'package_title'=>$post['package_title'],
                                  'amount' =>$post['amount'],
                                   'package_quantity'=>$post['package_quantity'],
                                  'status'=>$post['status']
                              );    
            return $data['form_data'];
        }   
    }
 
    // public function check_added_medicine()
    // {
    //    $booking_list = $this->session->userdata('vaccination_kit_data');
    //    if(isset($booking_list) && !empty($booking_list))
    //    {
    //       return true;
    //    }
    //    else
    //    { 
    //       $this->form_validation->set_message('check_added_medicine', 'Please add atleast one vaccination.');
    //       return false;
    //    }
    // }

    public function check_package($str){
 
        $post = $this->input->post();
 if(!empty($post['package_title']))
    {
        $this->load->model('general/general_model','general'); 
        if(!empty($post['pack_id']) && $post['pack_id']>0)
        {
                return true;
        }
        else
        {
                $packagedata = $this->general->check_package($post['package_title']);
                if(empty($packagedata))
                {
                   return true;
                }
                else
                {
            $this->form_validation->set_message('check_package', 'The vaccination kit name already exists.');
            return false;
                }
        }  
    }
    else
    {
      $this->form_validation->set_message('check_package', 'The vaccination kit name field is required.');
            return false; 
    } 
    }

    public function delete($id="")
    {
       unauthorise_permission(187,1063);
       if(!empty($id) && $id>0)
       {
           $result = $this->vaccination_packages->delete($id);
           $response = "Vaccination Kit successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
         unauthorise_permission(187,1063);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->vaccination_packages->deleteall($post['row_id']);
            $response = "Vaccination Kit successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->vaccination_packages->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Vendor']." detail";
        $this->load->view('vaccination_packages/view',$data);     
      }
    }  
     public function add_interpretation()
    {
      $data['page_title'] = 'Add interpretation';      
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
      $this->load->view('vaccination_packages/add_interpretation',$data);
    }


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(187,1066);
        $data['page_title'] = 'Vaccination Packages  list';
        $this->load->helper('url');
        $this->load->view('vaccination_packages/archive',$data);
    }

    public function archive_ajax_list()
    {
         unauthorise_permission(187,1066);
        $this->load->model('vaccination_packages/packages_archive_model','packages_archieve'); 
        $users_data = $this->session->userdata('auth_users');
     

            $list = $this->packages_archieve->get_datatables();
          
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $vaccination_packages) { 
            $no++;
            $row = array();
            if($vaccination_packages->status==1)
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
            ////////// Check list end ///////////// 
            if($users_data['parent_id']==$vaccination_packages->branch_id){
            
                     $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination_packages->id.'">'.$check_script; 
              
            }else{
               $row[]='';
            }
            $row[] = $vaccination_packages->title; 
            $row[] = $vaccination_packages->amount;
            
          
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($vaccination_packages->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if($users_data['parent_id']==$vaccination_packages->branch_id){
               if(in_array('1065',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_packages('.$vaccination_packages->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
               }
               if(in_array('1064',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$vaccination_packages->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->packages_archieve->count_all(),
                        "recordsFiltered" => $this->packages_archieve->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
         unauthorise_permission(187,1065);
        $this->load->model('vaccination_packages/packages_archive_model','packages_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->packages_archieve->restore($id);
           $response = "Packages successfully restore in Packages list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(187,1065);
       $this->load->model('vaccination_packages/packages_archive_model','packages_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->packages_archieve->restoreall($post['row_id']);
            $response = "Packages successfully restore in Packages list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(187,1064);
       $this->load->model('vaccination_packages/packages_archive_model','packages_archieve');
       if(!empty($id) && $id>0)
       {
           $result = $this->packages_archieve->trash($id);
           $response = "Packages successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
         unauthorise_permission(187,1064);
       $this->load->model('vaccination_packages/packages_archive_model','packages_archieve');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->packages_archieve->trashall($post['row_id']);
            $response = "Packages successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

     public function Element_Template_dropdown(){
          $packages_list = $this->vaccination_packages->packages_list();
          $dropdown = '<option value="">Select Element Template</option>'; 
          if(!empty($packages_list)){
               foreach($packages_list as $vaccination_packages){
                    $dropdown .= '<option value="'.$vaccination_packages->id.'">'.$vaccination_packages->Packages_name.'</option>';
               }
          } 
          echo $dropdown; 
     }
     public function reset_all_session_data($id=''){
          // if(empty($id)){
            
          //      $this->session->unset_userdata('departement_id');
          //      $this->session->unset_userdata('interpretation');
          //      $this->session->unset_userdata('test_head_ids');
          // }else{
                
          // }
     }
     //get child test to be added from test_head_id
     public function get_added_vaccination_list($pack='')
     {
          $post = $this->input->post();
          $list = array();
          $list = $this->vaccination_packages->get_added_vaccination_list(); 
          $vaccination_kit_data =  $this->session->userdata('vaccination_kit_data'); 
          $data  = ''; 
          if(!empty($list))
          {
               foreach($list as $vaccination)
               {
                    $data  .= '<tr><td align="center"><input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination['id'].'"></td><td>'.$vaccination['vaccination_code'].'</td><td>'.$vaccination['vaccination_name'].'</td><td>'.$vaccination['company_name'].'</td></tr>';
               }
          }
          else
          {
            $data  = '<tr><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>';    
          }  
           
          echo $data; 
     }  
     //get child test after adding 
     public function listalladdedvaccination()
     {
          $post = $this->input->post();   
          $vaccination_kit_data = $this->session->userdata('vaccination_kit_data'); 
         
           $table="";
          if(!isset($vaccination_kit_data) || empty($vaccination_kit_data))
          {
             $vaccination_kit_data = array();
          }
 
          if(!empty($post))
          {    
               $result = $this->vaccination_packages->addallvaccination($post['row_id']); 
               $total_num = count($result);
               if(!empty($result))
               {
                    foreach($result as $data)
                    { 
                         if(empty($data['conversion']) || $data['conversion']==0){
                              $conversion=1;
                         }else
                         {
                              $conversion =$data['conversion'];
                         }
                         $total_qty = (1*$data['conversion'])+1;
                         $vaccination_kit_data[$data['id']] = array('id'=>$data['id'], 'vaccination_code'=>$data['vaccination_code'], 'vaccination_name'=>$data['vaccination_name'],'company_name'=>$data['company_name'] ,'qty1'=>1,'qty2'=>1,'total_qty'=>$total_qty,'conversion'=>$conversion);
                    } 

                    $this->session->set_userdata('vaccination_kit_data',$vaccination_kit_data);
                    $check=1;
                   $table = $this->set_row_vaccination_kit($check);
               }
               
          }
          echo $table;
     }
     
     public function set_qty_mkit($vid="", $type="", $qty="")
     {
        
          if(isset($vid) && !empty($vid) && isset($type) && !empty($type) && isset($qty) && !empty($qty) || $qty=='0')
          {
               
               $vaccination_kit_data = $this->session->userdata('vaccination_kit_data');


               if(array_key_exists($vid,$vaccination_kit_data))
               {
                    $data = $vaccination_kit_data[$vid];

                 
                     
                    if($type==1)
                    {
                      
                         $qty1 = $qty;
                         $qty2 = $vaccination_kit_data[$vid]['qty2'];
                         $data['total_qty'] = ($qty1*$data['conversion'])+$qty2;
                    }
                    else if($type==2)
                    {
                         
                         $qty1 = $vaccination_kit_data[$vid]['qty1'];
                         $qty2 = $qty;
                         $data['total_qty'] = ($qty1*$data['conversion'])+$qty2;
                    }
                    $vaccination_kit_data[$data['id']] = array('id'=>$data['id'], 'vaccination_code'=>$data['vaccination_code'], 'vaccination_name'=>$data['vaccination_name'],'company_name'=>$data['company_name'] ,'qty1'=>$qty1, 'qty2'=>$qty2, 'total_qty'=>$data['total_qty'],'conversion'=>$data['conversion']); 
                    //print_r($vaccination_kit_data);die;
                    $this->session->set_userdata('vaccination_kit_data',$vaccination_kit_data);
                    //$rsult = $this->session->userdata('vaccination_kit_data'); 
                    //print_r($rsult);die; 
                    $check=1;
                   $table = $this->set_row_vaccination_kit();
               }

            
          }
     }

     public function set_row_vaccination_kit($check="")
     {
          
          $result = $this->session->userdata('vaccination_kit_data');  
     
          $table_row = "";
          if(!empty($result))
          { 
                     
                    foreach($result as $data)
                    { 
                         if(!empty($check))
                         {
                         //      $table_row .= '<tr>
                         //                <td align="center">
                         //                   <input type="checkbox" name="employee[]" class="medicinechecklist" value="'.$data['id'].'">
                         //                </td>
                         //                <td>'.$data['vaccination_code'].'</td>
                         //                <td>'.$data['vaccination_name'].'</td>
                         //                <td>'.$data['company_name'].'</td>
                         //                <td><input onblur="checkValue(this);" type="text" value="1" class="input-tiny numeric" name="unit1qty" id="'.$data['id'].'-unit1qty" onkeyup="set_qty_mkit('.$data['id'].',1,this.value);" />
                         //                </td>
                         //                <td>
                         //                    <input onblur="checkValue(this);" type="text" value="1" name="unit2qty" class="input-tiny numeric" id="'.$data['id'].'-unit2qty"  onkeyup="set_qty_mkit('.$data['id'].',2,this.value);" /> 
                         //                </td>
                         //             </tr>'; 
                         // }
                         // else
                         // {
                         //      $table_row .= '<tr>
                         //                <td align="center">
                         //                   <input type="checkbox" name="employee[]" class="medicinechecklist" value="'.$data['id'].'">
                         //                </td>
                         //                <td>'.$data['vaccination_code'].'</td>
                         //                <td>'.$data['vaccination_name'].'</td>
                         //                <td>'.$data['company_name'].'</td>
                         //                <td><input onblur="checkValue(this);" type="text" value="'.$data['qty1'].'" class="input-tiny numeric" name="unit1qty" id="'.$data['id'].'-unit1qty" onkeyup="set_qty_mkit('.$data['id'].',1,this.value);" />
                         //                </td>
                         //                <td>
                         //                    <input onblur="checkValue(this);" type="text" value="'.$data['qty2'].'" name="unit2qty" class="input-tiny numeric" id="'.$data['id'].'-unit2qty"  onkeyup="set_qty_mkit('.$data['id'].',2,this.value);" /> 
                         //                </td>
                         //             </tr>'; 
                         // }
                              $table_row .= '<tr>
                                              <td align="center">
                                              <input type="checkbox" name="employee[]" class="medicinechecklist" value="'.$data['id'].'">
                                              </td>
                                              <td>'.$data['vaccination_code'].'</td>
                                              <td>'.$data['vaccination_name'].'</td>
                                              <td>'.$data['company_name'].'</td>
                                              <td>'.$data['total_qty'].'</td>
                                              <td>'.$data['conversion'].'</td>
                                     
                                        <td>
                                            <input onblur="checkValue(this);" type="text" value="1" name="unit2qty" class="input-tiny numeric" id="'.$data['id'].'-unit2qty"  onkeyup="set_qty_mkit('.$data['id'].',2,this.value);" /> 
                                        </td>
                                     </tr>'; 
                         }
                         else
                         {
                              $table_row .= '<tr>
                                              <td align="center">
                                              <input type="checkbox" name="employee[]" class="medicinechecklist" value="'.$data['id'].'">
                                              </td>
                                              <td>'.$data['vaccination_code'].'</td>
                                              <td>'.$data['vaccination_name'].'</td>
                                              <td>'.$data['company_name'].'</td>
                                              <td>'.$data['total_qty'].'</td>
                                              <td>'.$data['conversion'].'</td>
                                     
                                        <td>
                                            <input onblur="checkValue(this);" type="text" value="'.$data['qty2'].'" name="unit2qty" class="input-tiny numeric" id="'.$data['id'].'-unit2qty"  onkeyup="set_qty_mkit('.$data['id'].',2,this.value);" /> 
                                        </td>
                                     </tr>'; 
                         }
                    }
               }
               else
               { 
                    $table_row  = '<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 
               }
               if(!empty($check)){
                  
                    return $table_row;
               }
               else
               {
                
                  echo $table_row;  
               }
               
     }
     
     //delete all added child test list
     public function deletealllistedmedicine()
     { 
          $post = $this->input->post();
          $vaccination_kit_data = $this->session->userdata('vaccination_kit_data');
          if(isset($post['row_id']) && !empty($post['row_id']))
          {
               foreach($post['row_id'] as $vid)
               { 
                    if(isset($vaccination_kit_data[$vid]))
                    {
                         unset($vaccination_kit_data[$vid]);
                    }
               }

               $this->session->set_userdata('vaccination_kit_data',$vaccination_kit_data); 
          }
          $this->set_row_vaccination_kit();    
     }
     //get all test heads according to selected departement
     public function get_test_heads($dept_id=''){
          $post = $this->input->post();
          $test_id = $this->session->userdata('test_head_ids');
          $result= array();
          if(array_key_exists('departments_id',$post)){
              
               $this->session->set_userdata('departement_id',$post['departments_id']);
               $result = $this->vaccination_packages->get_test_heads($post['departments_id']);
          }
          elseif(!empty($dept_id)){
             
               $this->session->set_userdata('departement_id',$dept_id);
               $result = $this->vaccination_packages->get_test_heads($dept_id);
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
               $result = $this->vaccination_packages->save_sort_order_data($id,$sort_order_value);
               echo $result;
               die;
          }

     }
     public function kit_allot_to_branch()
     {
      
         $post = $this->input->post();
         $vaccination_data = $this->session->userdata('alloted_vaccination_kit_ids');
         if(!empty($vaccination_data))
         {
            $vaccination_data = array();
            $this->session->set_userdata('alloted_vaccination_kit_ids',$vaccination_data);
         }
         if(isset($post) && !empty($post))
         {

              $this->session->set_userdata('alloted_vaccination_kit_ids',$post['vaccination_kit_ids']);
         } 
        
         $data['page_title'] = 'Vaccination Kit Allotment';
         
         $vaccination_kit_list = $this->vaccination_packages->get_vaccination_kit_list();
         // echo "<pre>";
         // print_r($vaccination_kit_list);
         // die;
         $row='';
         $i=1;
         $total_num = count($vaccination_kit_list);
         if(!empty($vaccination_kit_list))
         {
               foreach($vaccination_kit_list as $vaccination_kit_data)
               {
                   $qty_data = $this->vaccination_packages->get_vec_kit_qty($vaccination_kit_data['id']);
                   // if(empty($vaccination_kit_data['qty_kit']))
                   //  {
                   //       $qty_data=0;
                   //  }
                   //  else
                   //  {
                   //       $qty_data = $vaccination_kit_data['qty_kit'];
                   //  }
                     if(empty($qty_data['total_qty']))
                     {
                         $qty_data=0;
                      }
                      else
                      {
                          $qty_data = $qty_data['total_qty'];
                       }
             
                   
                     $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="vaccination['.$vaccination_kit_data['id'].'][mkit_id]" class="medicinechecklist" value="'.$vaccination_kit_data['id'].'"></td><td>'.$vaccination_kit_data['title'].'</td><td>'.$vaccination_kit_data['amount'].'</td><td><input type="text" id="qty_'.$vaccination_kit_data['id'].'" name="vaccination['.$vaccination_kit_data['id'].'][qty]" data-qty="'.$qty_data.'" data-id="'.$vaccination_kit_data['id'].'" value = "'.$qty_data.'" onblur="check_max_qty(this);"/><p id="msg_'.$vaccination_kit_data['id'].'" style="color:red;"></p></td></tr>';
                    

                    $i++;
               }
              
          }else{
               $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
          }
          $data['vaccination_kit_list'] = $row;
       

        
         $this->load->view('vaccination_packages/kit_allot_to_branch',$data);
     }
      public function allot_vaccination_kit_to_branch()
     {

         unauthorise_permission(63,582);
          $post = $this->input->post();

          $users_data = $this->session->userdata('auth_users');
          $update_qty = $this->session->userdata('update_qty');
          //print_r($update_qty);
          //die;
          $data['page_title'] = 'Vaccination Kit Allotment';
           $data['vaccination_list'] = '';
          if(isset($post) && !empty($post))
          {
              if($users_data['users_role']=='2')
              {
                    
                     $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
                     $this->form_validation->set_rules('sub_branch_id', 'branch', 'trim|required');
                    
                     if($this->form_validation->run() == TRUE)
                     {
                         
                          
                            $this->vaccination_packages->allot_vaccination_kit_to_branch();
                            echo 1;
                            return false;
                     }
                    
                    else
                    {
                       
                         
                         // print_r($this->session->userdata('alloted_medicine_ids'));
                         // die;
                         $vaccination_kit_list = $this->vaccination_packages->get_vaccination_kit_list();
                         $row='';
                         $i=1;
                         $total_num = count($vaccination_kit_list);

                         if(!empty($vaccination_kit_list))
                         {
                              foreach($vaccination_kit_list as $vaccination_kit_data)
                              {
                                  $qty_data = $this->vaccination_packages->get_vec_kit_qty($vaccination_kit_data['id']);
                                  // if(empty($vaccination_kit_data['qty_kit']))
                                  // {
                                  //       $qty_data=0;
                                  //  }
                                  //  else
                                  //  {
                                  //       $qty_data = $vaccination_kit_data['qty_kit'];
                                  //  }
                                    if(empty($qty_data['total_qty']))
                                    {
                                         $qty_data=0;
                                     }
                                     else
                                    {
                                        $qty_data = $qty_data['total_qty'];
                                     }
             
                                   $check_script='';
                                   if($i==$total_num)
                                   {
                                        // $check_script = 
                                        //      "<script>$('#getmedicineselectAll').on('click', function () { 
                                        //           if ($(this).hasClass('allChecked')) {
                                        //                          $('.medicinechecklist').prop('checked', false);
                                        //           } else {
                                        //           $('.medicinechecklist').prop('checked', true);
                                        //           }
                                        //           $(this).toggleClass('allChecked');
                                        //      })</script>";
                                   }
                                    $row.= '<tr><td align="center">'.$i.'<input type="hidden" name="vaccination['.$vaccination_kit_data['id'].'][mkit_id]" class="medicinechecklist" value="'.$vaccination_kit_data['id'].'"></td><td>'.$vaccination_kit_data['title'].'</td><td>'.$vaccination_kit_data['amount'].'</td><td><input type="text" id="qty_'.$vaccination_kit_data['id'].'" name="vaccination['.$vaccination_kit_data['id'].'][qty]" data-qty="'.$qty_data.'" data-id="'.$vaccination_kit_data['id'].'" value = "'.$qty_data.'" onblur="check_max_qty(this);"/><p id="msg_'.$vaccination_kit_data['id'].'" style="color:red;"></p></td></tr>';
                                   $i++;
                              }
                         }else{

                              $row  = '<tr id="nodata"><td class="text-danger text-center" colspan="5">No Records Founds</td></tr>'; 
                         }
                         $data['vaccination_kit_list'] = $row;
                         $data['form_error'] = validation_errors(); 
                      
                    }

               }
          }



          
          $this->load->view('vaccination_packages/kit_allot_to_branch',$data);
     }
      public function vaccination_kit_stock_excel()
    {
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccination Kit Name','Amount','Quantity','Created Date');
          $col = 0;
           $objPHPExcel->getActiveSheet()->getStyle('A')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('B')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('C')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           $objPHPExcel->getActiveSheet()->getStyle('D')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
           

          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);


               $col++;
          }
          $list = $this->vaccination_packages->get_vaccination_kit_stock_csv_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
               
               $i=0;
               foreach($list as $vaccination_kit_stock_report)
               {
                    $qty_data = $this->vaccination_packages->get_vec_kit_qty($vaccination_kit_stock_report->id,$vaccination_kit_stock_report->vaccination_kit_stock_branch_id);
                    // if(empty($vaccination_kit_stock_report->qty_kit))
                    // {
                    //      $qty_data=0;
                    // }
                    // else
                    // {
                    //     $qty_data = $vaccination_kit_stock_report->qty_kit;
                    // }
                      if(empty($qty_data['total_qty']))
                      {
                         $qty_data=0;
                    }
                    else
                    {
                            $qty_data = $qty_data['total_qty'];
                    }
             
             
                    
                    array_push($rowData,$vaccination_kit_stock_report->title,$vaccination_kit_stock_report->amount,$qty_data,date('d-m-Y',strtotime($vaccination_kit_stock_report->created_date)));
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
               foreach($data as $vaccination_kit_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $vaccination_kit_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          }
          
          // Sending headers to force the user to download the file
          //header('Content-Type: application/octet-stream');
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=vaccination_kit_stock_".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    }
    public function vaccination_kit_stock_csv()
    {
          // Starting the PHPExcel library
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $fields = array('Vaccination Kit Name','Amount','Quantity','Created Date');
          $col = 0;
          foreach ($fields as $field)
          {
               $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
               $col++;
          }
          $list = $this->vaccination_packages->get_vaccination_kit_stock_csv_data();
          $rowData = array();
          $data= array();
          if(!empty($list))
          {
              
               $i=0;
               foreach($list as $vaccination_kit_stock_report)
               {
                    $qty_data = $this->vaccination_packages->get_vec_kit_qty($vaccination_kit_stock_report->id,$vaccination_kit_stock_report->vaccination_kit_stock_branch_id);
                    // if(empty($vaccination_kit_stock_report->qty_kit))
                    // {
                    //      $qty_data=0;
                    // }
                    // else
                    // {
                    //      $qty_data = $vaccination_kit_stock_report->qty_kit;
                    // }
                    if(empty($qty_data['total_qty']))
                    {
                        $qty_data=0;
                    }
                    else
                    {
                         $qty_data = $qty_data['total_qty'];
                    }
             
             
                    
                    array_push($rowData,$vaccination_kit_stock_report->title,$vaccination_kit_stock_report->amount,$qty_data,date('d-m-Y',strtotime($vaccination_kit_stock_report->created_date)));
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
               foreach($data as $vaccination_kit_data)
               {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $vaccination_kit_data[$field]);
                         $col++;
                    }
                    $row++;
               }
               $objPHPExcel->setActiveSheetIndex(0);
               $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=vaccination_kit_stock_".time().".csv");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($data))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
    
    }

    public function pdf_vaccination_kit_stock()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->vaccination_packages->get_vaccination_kit_stock_pdf_data();
        $this->load->view('vaccination_packages/vaccination_kit_stock_report_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        //echo $html; exit;
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("medicine_kit_stock_report_".time().".pdf");
    }
     public function print_vaccination_kit_stock()
    {    
     $data['data_list'] = $this->vaccination_packages->get_medicine_kit_stock_data();
     $this->load->view('vaccination_packages/vaccination_kit_stock_report_html',$data); 
    }
    public function advance_search()
      {
          $this->load->model('general/general_model'); 
          $data['page_title'] = "Advance Search";
          $post = $this->input->post();
          // $data['unit_list'] = $this->medicine_stock->unit_list();
          $data['form_data'] = array(
                                    "start_date"=>'',
                                    "end_date"=>"",
                                    "expiry_to"=>"",
                                    "expiry_from"=>"",
                                     "branch_id"=>"",
                                  
                                   
                                    );
          if(isset($post) && !empty($post))
          {
              $marge_post = array_merge($data['form_data'],$post);
              $this->session->set_userdata('stock_search', $marge_post);
          }
          $purchase_search = $this->session->userdata('stock_search');
          if(isset($purchase_search) && !empty($purchase_search))
          {
              $data['form_data'] = $purchase_search;
          }
          $this->load->view('vaccination_packages/advance_search',$data);
   }

    public function reset_search()
    {
        $this->session->unset_userdata('vaccination_kit_stock_search');
       

    }
  
    public function get_allsub_branch_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
            $dropdown = '<label class="">Branches</label> <select id="sub_branch_id" onchange="form_submit();" name="sub_branch_id"></option><option value="'.$users_data['parent_id'].'">Self</option></option>';
            if(!empty($sub_branch_details)){
                $i=0;
                foreach($sub_branch_details as $key=>$value){
                    $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                    $i = $i+1;
                }
               
            }
            $dropdown.='</select>';
            echo $dropdown; 
        }
         
       
    }
      public function get_allsub_branch_medicine_kit_list(){
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
         $users_data = $this->session->userdata('auth_users');
        if($users_data['users_role']==2){
            $dropdown = '<label class="">Branches</label> <select id="sub_branch_ids" onchange="medicine_kit_branch_report();" name="sub_branch_ids" style="margin-left:7.7em;"><option  value="" >Select</option></option><option selected="selected" value="'.$users_data['parent_id'].'">Self</option></option>';
            if(!empty($sub_branch_details)){
                $i=0;
                foreach($sub_branch_details as $key=>$value){
                    $dropdown .= '<option value="'.$sub_branch_details[$i]['id'].'">'.$sub_branch_details[$i]['branch_name'].'</option>';
                    $i = $i+1;
                }
               
            }
            $dropdown.='</select>';
            echo $dropdown; 
        }
         
       
    }
     public function search_vaccination_kit_stock_data()
    {
      $post = $this->input->post();
      if(isset($post) && !empty($post))
      {
            $this->session->set_userdata('vaccination_kit_stock_search',$post);
      }
    
    }
    public function vaccination_kit_to_branch()
    {
       // $data['employee_list'] = $this->reports->employee_list();
       $data['page_title'] = 'Vaccination Kit Branch Reports';
       $data['from_c_date'] = date('d-m-Y');
       $data['to_c_date'] = date('d-m-Y'); 
       $this->load->view('vaccination_packages/vaccination_kit_branch',$data);
    }
    public function vaccination_kit_to_branch_reports_excel()
    { 
          $this->load->library('excel');
          $this->excel->IO_factory();
          $objPHPExcel = new PHPExcel();
          $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
          $objPHPExcel->setActiveSheetIndex(0);
          // Field names in the first row
          $get = $this->input->get();
         
          // if(!empty($get['branch_id']))
          // {
             $list = $this->vaccination_packages->get_vaccination_kit_branch_details($get);
            
          // }
          $first_row_field_pre = array('Date');
          $rowData = array();
          $row_data_pre = array();
          $row_data_mid = array();
          $row_data_pos = array();
          $first_row_field_mid = array();
          $first_row_field_count_mid = count($list);
          $total = 0;
          if(!empty($list)){
               for($i=0;$i<$first_row_field_count_mid;$i++)
               {    
                    $qty_data = $this->vaccination_packages->get_vec_kit_qty($list[$i]->id,$list[$i]->vaccination_kit_stock_branch_id);
                    // if(empty($list[$i]->qty_kit))
                    // {
                    //      $qty_data=0;
                    // }
                    // else
                    // {
                    //      $qty_data = $list[$i]->qty_kit;
                    // }
                      if(empty($qty_data['total_qty']))
                      {
                           $qty_data=0;
                      }
                      else
                      { 
                         $qty_data = $qty_data['total_qty'];
                      } 
             
                    if(empty($row_data_pre)){
                    array_push($row_data_pre,date('d-m-Y',strtotime($list[$i]->created_date)));
                    }
                    array_push($row_data_mid,$qty_data);

                    array_push($first_row_field_mid,$list[$i]->title);
                    $total+=$qty_data;

               }

               array_push($row_data_pos,$total);
               $first_row_field_pos = array('Total');
               $fields = array_merge($first_row_field_pre,$first_row_field_mid,$first_row_field_pos);
               $rowData = array_merge($row_data_pre,$row_data_mid,$row_data_pos);
          }
        
        
      
          // $fields = array('Vaccination Kit Name','Amount','Quantity','Created Date');
          if(!empty($fields))
          {
               $col = 0;

               foreach ($fields as $field)
               {

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
                    $col++;
               }
          }
         
         
          // Fetching the table data
          $row = 2;
          if(!empty($rowData))
          {
               // foreach($rowData as $vaccination_kit_data)
               // {
                    $col = 0;
                    foreach ($fields as $field)
                    { 
                         $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $rowData[$col]);
                         $col++;
                    }
                    // $row++;
               // }
               
          }
          $objPHPExcel->setActiveSheetIndex(0);
          $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
          
          // Sending headers to force the user to download the file
     
          header('Content-Type: application/vnd.ms-excel charset=UTF-8');
          header("Content-Disposition: attachment; filename=medicine_kit_stock_branch_report".time().".xls");
          header("Pragma: no-cache"); 
          header("Expires: 0");
         if(!empty($rowData))
         {
          ob_end_clean();
          $objWriter->save('php://output');
         }
   

    }
    public function set_session_updateqty(){
       $post = $this->input->post();
     
       if(isset($post) && !empty($post)){
          $update_qty = $this->session->userdata('update_qty');
          if(!empty($update_qty)){
               array_push($update_qty,$post['update_qty']);
          }
          else
          {
                $update_qty = array($post['update_qty']);
          }
       }
       $this->session->set_userdata('update_qty',$update_qty);
    }
    public function  get_vaccination_search_data(){
         $result = $this->vaccination_packages->get_added_vaccination_list();
       
         $data = $this->format_get_vaccination_search_data($result);
         echo $data;
    }
    public function format_get_vaccination_search_data($vaccination_search_data=""){
          $table ='';
          $vaccination_kit_data = $this->session->userdata('vaccination_kit_data');
          if(!empty($vaccination_kit_data)){
               $selected_vec_id = implode(',',array_keys($vaccination_kit_data));
               $selected_vec_ids = explode(',',$selected_vec_id);
          }
          else
          {
               $selected_vec_ids = array();
          }
      
         
          if(!empty($vaccination_search_data)){
              $vaccination_search_data_count = count($vaccination_search_data);
              for($i=0;$i<$vaccination_search_data_count;$i++)
              {
                    if(!empty($vaccination_search_data[$i]['id'])){
                         if($vaccination_search_data[$i]['total_qty']>0){
                              if(!in_array($vaccination_search_data[$i]['id'],$selected_vec_ids)){
                                   $table.= '<tr><td align="center"><input type="checkbox" name="employee[]" class="checklist" value="'.$vaccination_search_data[$i]['id'].'"></td><td>'.$vaccination_search_data[$i]['vaccination_code'].'</td><td>'.$vaccination_search_data[$i]['vaccination_name'].'</td><td>'.$vaccination_search_data[$i]['company_name'].'</td></tr>'; 
                              }
                              else if($i==$vaccination_search_data_count-1){
                                   $table.='<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>';
                              }
                         }
                         else if($i==$vaccination_search_data_count-1){
                            $table.='<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>';
                         }
                        
                    }
                    else
                    {
                         $table.='<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 

                         
                    }
               }
              
          }
          else
          {
              $table.='<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 
          }
          return $table;
    }
    public function add_vaccination_kit_qty_manage($id=""){
        $data['page_title'] = "Vaccination Kit Added Quanitity List";
        $result = $this->vaccination_packages->add_vaccination_kit_qty_manage($id);
       
        $data['added_medicine_kit_qty_list'] = $this->format_added_vaccination_kit_qty($result);
        $this->load->view('vaccination_packages/add_vaccination_kit_qty_mng',$data);
    }
    public function format_added_vaccination_kit_qty($vaccination_kit_qty_data=array()){
        $table="";
        if(!empty($vaccination_kit_qty_data)){

              $vaccination_kit_qty_data_count = count($vaccination_kit_qty_data);
              for($i=0;$i<$vaccination_kit_qty_data_count;$i++){
                 $table.='<tr><td>'.$vaccination_kit_qty_data[$i]['title'].'</td><td>'.$vaccination_kit_qty_data[$i]['amount'].'</td><td>'.$vaccination_kit_qty_data[$i]['debit'].'</td><td>'.date('d-M-Y H:i A',strtotime($vaccination_kit_qty_data[$i]['created_date'])).'</td><td><button type="button" class="btn_custom" id="edit_qty" onclick="package_quantity_edit('.$vaccination_kit_qty_data[$i]['kit_id'].','.$vaccination_kit_qty_data[$i]['id'].');">Edit</button></td></tr>';
              }
        }
        else
        {
           $table.='<tr id="nodata"><td class="text-danger" colspan="4"><div class="text-center">No Records Founds</div></td></tr>'; 
        }
        return $table;
    }
 
}