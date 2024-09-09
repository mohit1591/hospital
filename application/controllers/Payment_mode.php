<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_mode extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('payment_mode/payment_mode_model','payment_mode');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('161','933');
        $data['page_title'] = 'Payment Mode List'; 
        $this->load->view('payment_mode/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('161','933');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->payment_mode->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $payment_mode) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($payment_mode->status==1)
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
            if($users_data['parent_id']==$payment_mode->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$payment_mode->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $payment_mode->payment_mode;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($payment_mode->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$payment_mode->branch_id)
            {
              $btnedit =' <a onClick="return edit_payment_mode('.$payment_mode->id.');" class="btn-custom" href="javascript:void(0)" style="'.$payment_mode->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
            
                   $btndelete = ' <a class="btn-custom" onClick="return delete_payment_mode('.$payment_mode->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
             
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

         $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->payment_mode->count_all(),
                        "recordsFiltered" => $this->payment_mode->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('161','934');
        $data['page_title'] = "Add Payment Mode";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'payment_mode'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->payment_mode->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('payment_mode/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('161','935');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->payment_mode->get_by_id($id);  
        $data['page_title'] = "Update Payment Mode";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'payment_mode'=>$result['payment_mode'], 
                                  'status'=>$result['status']
                                 );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->payment_mode->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('payment_mode/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('payment_mode', 'payment mode', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'payment_mode'=>$post['payment_mode'], 
                                        'status'=>$post['status']
                                       
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('161','936');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->payment_mode->delete($id);
           $response = "Payment mode successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('161','936');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->payment_mode->deleteall($post['row_id']);
            $response = "Payment mode successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->payment_mode->get_by_id($id);  
        $data['page_title'] = $data['form_data']['payment_mode']." detail";
        $this->load->view('payment_mode/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('161','937');
        $data['page_title'] = 'Payment mode Archive List';
        $this->load->helper('url');
        $this->load->view('payment_mode/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('161','937');
        $this->load->model('payment_mode/payment_mode_archive_model','payment_mode_archive'); 

      
               $list = $this->payment_mode_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $payment_mode) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($payment_mode->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$payment_mode->id.'">'.$check_script; 
            $row[] = $payment_mode->payment_mode;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($payment_mode->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('935',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_payment_mode('.$payment_mode->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('936',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$payment_mode->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->payment_mode_archive->count_all(),
                        "recordsFiltered" => $this->payment_mode_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('161','935');
        $this->load->model('payment_mode/payment_mode_archive_model','payment_mode_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->payment_mode_archive->restore($id);
           $response = "Payment mode successfully restore in payment mode list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('161','935');
        $this->load->model('payment_mode/payment_mode_archive_model','payment_mode_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->payment_mode_archive->restoreall($post['row_id']);
            $response = "Payment mode successfully restore in Simulation list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('161','936');
        $this->load->model('payment_mode/payment_mode_archive_model','payment_mode_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->payment_mode_archive->trash($id);
           $response = "Payment mode successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('161','936');
        $this->load->model('payment_mode/payment_mode_archive_model','payment_mode_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->payment_mode_archive->trashall($post['row_id']);
            $response = "Payment Mode successfully deleted parmanently.";
            echo $response;
        }
    }


}
?>