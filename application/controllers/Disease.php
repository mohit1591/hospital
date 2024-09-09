<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disease extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('disease/disease_model','disease');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('100','631');
        $data['page_title'] = 'Diseases List'; 
        $this->load->view('disease/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('100','631');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->disease->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $disease) {
         // print_r($disease);die;
            $no++;
            $row = array();
            if($disease->status==1)
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
            if($users_data['parent_id']==$disease->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$disease->id.'">'.$check_script;
            }else{
               $row[]='';
            }
         
            $row[] = $disease->disease; 
            $row[] = $disease->disease_days; 
            $row[] = $disease->disease_code; 
            
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($disease->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$disease->branch_id)
            {
              if(in_array('633',$users_data['permission']['action'])){
               $btnedit =' <a onClick="return edit_disease('.$disease->id.');" class="btn-custom" href="javascript:void(0)" style="'.$disease->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
              }
              if(in_array('634',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_disease('.$disease->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
               }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->disease->count_all(),
                        "recordsFiltered" => $this->disease->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('100','632');
        $data['page_title'] = "Add Disease";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'disease'=>"",
                                  'disease_code'=>"",
                                  'status'=>"1",
                                  "disease_days"=>"",
                                
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->disease->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('disease/add',$data);       
    }
    

    public function edit($id="")
    {
     unauthorise_permission('100','633');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->disease->get_by_id($id);  
        $data['page_title'] = "Update Disease";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'disease'=>$result['disease'],
                                  'disease_code'=>$result['disease_code'], 
                                  'status'=>$result['status'],
                                  "disease_days"=>$result['disease_days'],
                                
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->disease->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('disease/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('disease', 'disease', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            $reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'disease'=>$post['disease'], 
                                        'disease_code'=>$post['disease_code'], 
                                        'status'=>$post['status'],
                                        'disease_days'=>$post['disease_days'],
                                       
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('12','67');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->disease->delete($id);
           $response = "Disease successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('100','634');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->disease->deleteall($post['row_id']);
            $response = "Diseases successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->disease->get_by_id($id);  
        $data['page_title'] = $data['form_data']['disease']." detail";
        $this->load->view('disease/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('100','635');
        $data['page_title'] = 'Disease Archive List';
        $this->load->helper('url');
        $this->load->view('disease/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('100','635');
        $this->load->model('disease/disease_archive_model','disease_archive'); 

      
               $list = $this->disease_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $disease) {
         // print_r($disease);die;
            $no++;
            $row = array();
            if($disease->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$disease->id.'">'.$check_script; 
            $row[] = $disease->disease;
            $row[] = $disease->disease_code;    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($disease->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('638',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_disease('.$disease->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('634',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$disease->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->disease_archive->count_all(),
                        "recordsFiltered" => $this->disease_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('100','638');
        $this->load->model('disease/disease_archive_model','disease_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->disease_archive->restore($id);
           $response = "Disease successfully restore in Disease list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('100','638');
        $this->load->model('disease/disease_archive_model','disease_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->disease_archive->restoreall($post['row_id']);
            $response = "Disease successfully restore in Disease list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('100','636');
        $this->load->model('disease/disease_archive_model','disease_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->disease_archive->trash($id);
           $response = "Disease successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('100','636');
        $this->load->model('disease/disease_archive_model','disease_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->disease_archive->trashall($post['row_id']);
            $response = "Disease successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function disease_dropdown()
  {
      $disease_list = $this->disease->disease_list();
      $dropdown = '<option value="">Select disease</option>'; 
      if(!empty($disease_list))
      {
        foreach($disease_list as $disease)
        {
           $dropdown .= '<option value="'.$disease->id.'">'.$disease->disease.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>