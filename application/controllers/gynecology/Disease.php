<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Disease extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('gynecology/disease/Disease_model','disease');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        
        unauthorise_permission('303','1802');
        $data['page_title'] = ' Disease List'; 
        $this->load->view('gynecology/disease/list',$data);
    }

    public function ajax_list()
    { 
       
        unauthorise_permission('303','1802');
         $users_data = $this->session->userdata('auth_users');
        $list = $this->disease->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $gynecology_list) {
         // print_r($chief_complaints);die;
            $no++;
            $row = array();
            if($gynecology_list->status==1)
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
           
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$gynecology_list->id.'">'.$check_script; 
            $row[] = $gynecology_list->disease_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($gynecology_list->created_date)); 
           
          $btnedit='';
          $btndelete='';
          if(in_array('1804',$users_data['permission']['action']))
          {
               $btnedit = ' <a onClick="return edit_disease('.$gynecology_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$gynecology_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
        }
          if(in_array('1805',$users_data['permission']['action']))
          {
               $btndelete = ' <a class="btn-custom" onClick="return delete_disease('.$gynecology_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
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
       unauthorise_permission('303','1803');
        $data['page_title'] = "Add Disease";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'disease_name'=>"",
                                  'status'=>"1"
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
       $this->load->view('gynecology/disease/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('303','1804');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->disease->get_by_id($id);  
        $data['page_title'] = "Update Disease";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'disease_name'=>$result['disease_name'], 
                                  'status'=>$result['status']
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
       $this->load->view('gynecology/disease/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('disease_name', 'disease name', 'trim|required|callback_disease_name'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'disease_name'=>$post['disease_name'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }


    /* callbackurl */
    /* check validation laready exit */

    public function disease_name($str){
 
          $post = $this->input->post();
          if(!empty($post['disease_name']))
          {
               $this->load->model('gynecology/general/Gynecology_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->disease->get_by_id($post['data_id']);
                      if($data_cat['disease_name']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $check_complain = $this->general->check_disease($str);

                        if(empty($check_complain))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('disease_name', 'The disease already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $cheif_complain = $this->general->check_disease($post['disease_name'], $post['data_id']);
                    if(empty($cheif_complain))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('disease_name', 'The disease already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('disease_name', 'disease field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
 
    public function delete($id="")
    {
       unauthorise_permission('303','1805');
       if(!empty($id) && $id>0)
       {
           $result = $this->disease->delete($id);
           $response = "Disease Successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      unauthorise_permission('303','1805');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->disease->deleteall($post['row_id']);
            $response = "Disease successfully deleted.";
            echo $response;
        }
    }

   


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       unauthorise_permission('303','1806');
        $data['page_title'] = 'Disease Archive List';
        $this->load->helper('url');
        $this->load->view('gynecology/disease/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('303','1806');
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('gynecology/disease/disease_archive_model','disease_archive'); 

        $list = $this->disease_archive->get_datatables(); 
       // print_r($list);die; 
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $disease_list) { 
            $no++;
            $row = array();
            if($disease_list->status==1)
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
           
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$disease_list->id.'">'.$check_script; 
            $row[] = $disease_list->disease_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($disease_list->created_date)); 
            
          $btnrestore='';
          $btndelete='';
          if(in_array('1808',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_disease('.$disease_list->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1807',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$disease_list->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
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
       unauthorise_permission('303','1808');
        $this->load->model('gynecology/disease/disease_archive_model','disease_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->disease_archive->restore($id);
           $response = "Disease successfully restore in Disease list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('303','1808');
        $this->load->model('gynecology/disease/disease_archive_model','disease_archive');
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
        unauthorise_permission('303','1807');
           $this->load->model('gynecology/disease/disease_archive_model','disease_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->disease_archive->trash($id);
           $response = "Disease successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('303','1807');
         $this->load->model('gynecology/disease/disease_archive_model','disease_archive');
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
           $dropdown .= '<option value="'.$disease->id.'">'.$disease->disease_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>