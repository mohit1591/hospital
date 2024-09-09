<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Age_vaccination extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/age_vaccination/age_vaccination_model','age_vaccination');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('273','1605');
        $data['page_title'] = 'Age Vaccination'; 
        $this->load->view('pediatrician/age_vaccination/list',$data);
    }


    public function ajax_list()
    { 
        unauthorise_permission('273','1605');
        $list = $this->age_vaccination->get_datatables();  
        

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $vaccination_ids=array();
       
        foreach ($list as $age_vaccination) {

         // print_r($relation);die;
            $no++;
            $row = array();
            if($age_vaccination->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
            
            ////////// Check  List /////////////////medicine_preferred_reminder_service
            $check_script = "";
            if($i==$total_num)
            {
           
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$age_vaccination->id.'">'.$check_script; 
            $row[] = $age_vaccination->vaccination_name; 
           
            $ages_li= $this->age_vaccination->get_age_list_according_vaccine($age_vaccination->id);  
            $row[]=implode(' ',$ages_li);
            
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($age_vaccination->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1607',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_age_vaccination('.$age_vaccination->id.');" class="btn-custom" href="javascript:void(0)" style="'.$age_vaccination->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1608',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_age_vaccination('.$age_vaccination->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->age_vaccination->count_all(),
                        "recordsFiltered" => $this->age_vaccination->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {

        unauthorise_permission('273','1606');
        $data['page_title'] = "Add Age Vaccination";  
        $post = $this->input->post();
        $data['vaccination_list']= $this->age_vaccination->vaccination_list();
        $data['age_list']= $this->age_vaccination->age_list();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'vaccine'=>"", 
                                   'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
          
              $data['form_data'] = $this->_validate(); 
                $this->age_vaccination->save();
                echo 1;
                return false;
        }
        //print_r($data['form_data']);die;
       $this->load->view('pediatrician/age_vaccination/add',$data);       
    }

    
    public function edit($id="")
    {

     unauthorise_permission('273','1606');
     $data['vaccination_list']= $this->age_vaccination->vaccination_list();
     $data['age_list']= $this->age_vaccination->age_list();
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->age_vaccination->get_by_id($id);  
        $data['page_title'] = "Update Age Vaccination";  
        $data['recommended_age']= $this->age_vaccination->recommended_age_by_id($id);
        $data['catchup_age']= $this->age_vaccination->catchup_age_by_id($id);
        $data['risk_age']= $this->age_vaccination->risk_age_by_id($id);
        $data['age_list']= $this->age_vaccination->age_list();
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'vaccine'=>$result['vaccination'],      
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
          //print_r($post);
                $this->age_vaccination->save();
                echo 1;
                return false;
               
        }
       $this->load->view('pediatrician/age_vaccination/add',$data);       
      }
    }
     

    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('title', 'title', 'trim|required');
          
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                    'data_id'=>$post['data_id'],
                                    'vaccine'=>$post['vaccine'], 
                                        
                                    'status'=>$post['status']
                                    ); 
          return $data['form_data'];
          }   
    }
    
 
    public function delete($id="")
    {
       unauthorise_permission('273','1608');
       if(!empty($id) && $id>0)
       {
           $result = $this->age_vaccination->delete($id);
           $response = "Age Vaccination  successfully deleted.";
           echo $response;
       }
    }


    function deleteall()
    {
        unauthorise_permission('273','1608');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->age_vaccination->deleteall($post['row_id']);
            $response = "Age Vaccination  successfully deleted.";
            echo $response;
        }
    }


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('273','1609');
        $data['page_title'] = 'Age Vaccination archive list';
        $this->load->helper('url');
        $this->load->view('pediatrician/age_vaccination/archive',$data);
    }


    public function archive_ajax_list()
    {
        unauthorise_permission('273','1609');
        $this->load->model('pediatrician/age_vaccination/Age_vaccination_archive_model','age_vaccination_archive'); 

        $list = $this->age_vaccination_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $vaccination_ids=array();

        foreach ($list as $age_vaccination_archive) { 
           //print_r($age_vaccination_archive);die;
            $no++;
            $row = array();
            if($age_vaccination_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$age_vaccination_archive->id.'">'.$check_script; 
         
            $row[] =$age_vaccination_archive->vaccination_name; 
           
            $ages_li= $this->age_vaccination_archive->get_age_list_according_vaccine($age_vaccination_archive->id);  
            $row[]=implode(' ',$ages_li);
            
            $row[] = $status;
           
            //$row[] = date('d-M-Y H:i A',strtotime($age_vaccination_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('1613',$users_data['permission']['action']))
          {
               $btnrestore = ' <a onClick="return restore_age_vaccination('.$age_vaccination_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1610',$users_data['permission']['action']))
          {
               $btndelete = ' <a onClick="return trash('.$age_vaccination_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->age_vaccination_archive->count_all(),
                        "recordsFiltered" => $this->age_vaccination_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('273','1613');
        $this->load->model('pediatrician/age_vaccination/Age_vaccination_archive_model','age_vaccination_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->age_vaccination_archive->restore($id);
           $response = "Age Vaccination successfully restore in Age Vaccination list.";
           echo $response;
       }
    }

    function restoreall()
    { 
      unauthorise_permission('273','1613');
       $this->load->model('pediatrician/age_vaccination/Age_vaccination_archive_model','age_vaccination_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->age_vaccination_archive->restoreall($post['row_id']);
            $response = "Age Vaccination successfully restore in Age Vaccination list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('273','1610');
        $this->load->model('pediatrician/age_vaccination/Age_vaccination_archive_model','age_vaccination_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->age_vaccination_archive->trash($id);
           $response = "Age Vaccination successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
       unauthorise_permission('273','1610');
       $this->load->model('pediatrician/age_vaccination/Age_vaccination_archive_model','age_vaccination_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->age_vaccination_archive->trashall($post['row_id']);
            $response = "Age Vaccination successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

 

}
?>