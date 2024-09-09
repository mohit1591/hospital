<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Age_vaccine_master extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/age_vaccine_master/Age_vaccine_master_model','age_vaccine');
        $this->load->library('form_validation');
    }


    public function index()
    { 
        unauthorise_permission('272','1596');
        $data['page_title'] = 'Age Vaccine Master List'; 
        $this->load->view('pediatrician/age_vaccine_master/list',$data);
    }


   //age vaccine list
    public function ajax_list()
    { 
        unauthorise_permission('272','1596');
        $list = $this->age_vaccine->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $age_vaccine) {
            $no++;
            $row = array();
            if($age_vaccine->status==1)
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
            if(!empty($age_vaccine->start_age_type))
            {
                if($age_vaccine->start_age_type=='1')
                {
                  $start_age_type='Day';
                }
                 if($age_vaccine->start_age_type=='3')
                {
                  $start_age_type='Month';
                }
                 if($age_vaccine->start_age_type=='4')
                {
                  $start_age_type='Year';
                }
                if($age_vaccine->start_age_type=='2')
                {
                  $start_age_type='Week';
                }
            }  
            else
            {
              $start_age_type='';
            }
             if(!empty($age_vaccine->end_age_type))
             {
                if($age_vaccine->end_age_type=='1')
                {
                  $end_age_type='Day';
                }
                 if($age_vaccine->end_age_type=='3')
                {
                  $end_age_type='Month';
                }
                 if($age_vaccine->end_age_type=='4')
                {
                  $end_age_type='Year';
                }
                if($age_vaccine->end_age_type=='2')
                {
                  $end_age_type='Week';
                }
             }
             else{
               $end_age_type='';
             }              
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$age_vaccine->id.'">'.$check_script; 
            $row[] = $age_vaccine->title; 
            $row[] = $age_vaccine->start_age; 
            $row[] = $start_age_type; 
            $row[] = $age_vaccine->end_age; 
            $row[] = $end_age_type; 
            //$row[] = $age_vaccine->sort_order;
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($age_vaccine->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1598',$users_data['permission']['action']))
          {
                $btnedit = ' <a onClick="return edit_age_vaccine('.$age_vaccine->id.');" class="btn-custom" href="javascript:void(0)" style="'.$age_vaccine->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1599',$users_data['permission']['action']))
          {
               $btndelete = ' <a class="btn-custom" onClick="return delete_age_vaccine('.$age_vaccine->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->age_vaccine->count_all(),
                        "recordsFiltered" => $this->age_vaccine->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    //age vaccine add
    public function add()
    {
          unauthorise_permission('272','1597');
          $data['page_title'] = "Add Age Vaccine Master Field";  
          $post = $this->input->post();
          $data['form_error'] = []; 
          $data['form_data'] = array(
                                    'data_id'=>"", 
                                    'title'=>"",
                                    'start_age'=>"", 
                                    'end_age'=>"", 
                                    'start_age_type'=>"", 
                                    'end_age_type'=>"",
                                    //'sort_order'=>"",                                     
                                    'status'=>"1"
                                    );    

          if(isset($post) && !empty($post))
          {   
              $data['form_data'] = $this->_validate();
              if($this->form_validation->run() == TRUE)
              {
                  $this->age_vaccine->save();
                  echo 1;
                  return false;
                  
              }
              else
              {
                  $data['form_error'] = validation_errors();  
              }     
          }
         $this->load->view('pediatrician/age_vaccine_master/add',$data);       
    }
    
      //age vaccine edit
    public function edit($id="")
    {
     unauthorise_permission('272','1598');

     if(isset($id) && !empty($id) && is_numeric($id))
      {      
          $result = $this->age_vaccine->get_by_id($id);  

          $data['page_title'] = "Update Age Vaccine Master Field";  
          $post = $this->input->post();
          $data['form_error'] = ''; 
          $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'title'=>$result['title'],
                                  'start_age'=>$result['start_age'],
                                  'end_age'=>$result['end_age'],
                                  //'sort_order'=>$result['sort_order'],
                                  'start_age_type'=>$result['start_age_type'],
                                  'end_age_type'=>$result['end_age_type'],                                
                                  'status'=>$result['status']
                                    );  
          
          if(isset($post) && !empty($post))
          {   
                $data['form_data'] = $this->_validate();
                if($this->form_validation->run() == TRUE)
                {
                    $this->age_vaccine->save();
                    echo 1;
                    return false;
                    
                }
                else
                {
                    $data['form_error'] = validation_errors();  
                }     
          }
         $this->load->view('pediatrician/age_vaccine_master/add',$data);       
      }
    }
     
       //age vaccine validate
    private function _validate()
    {
          $post = $this->input->post();    
          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
          $this->form_validation->set_rules('title', 'title', 'trim|required');
          $this->form_validation->set_rules('start_age', 'start age', 'trim|required');
          $this->form_validation->set_rules('start_age_type', 'start age type', 'trim|required');
          if(!empty($post['end_age']))
          {
          $this->form_validation->set_rules('end_age_type', 'end age type', 'trim|required');
           }
         
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                  'data_id'=>$post['data_id'],
                                  'title'=>$post['title'],
                                  'start_age'=>$post['start_age'],
                                  'start_age_type'=>$post['start_age_type'],
                                  'end_age'=>$post['end_age'],
                                  'end_age_type'=>$post['end_age_type'],
                                //  'sort_order'=>$post['sort_order'],                        
                                  'status'=>$post['status']
            ); 
          return $data['form_data'];
          }   
    }
    
 
 //age vaccine delete
    public function delete($id="")
    {
       unauthorise_permission('272','1599');
       if(!empty($id) && $id>0)
       {
           $result = $this->age_vaccine->delete($id);
           $response = "Age Vaccine Master successfully deleted.";
           echo $response;
       }
    }

     //age vaccine deleteall
    function deleteall()
    {
           unauthorise_permission('272','1599');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->age_vaccine->deleteall($post['row_id']);
            $response = "Age Vaccine Master  successfully deleted.";
            echo $response;
        }
    }

  


    ///// age vaccine Archive Start  ///////////////
    public function archive()
    {
            unauthorise_permission('272','1600');
        $data['page_title'] = 'Age Vaccine Master archive list';
        $this->load->helper('url');
        $this->load->view('pediatrician/age_vaccine_master/archive',$data);
    }

    ///// age vaccine Archive list Start  ///////////////
    public function archive_ajax_list()
    {
      unauthorise_permission('272','1600');
      $this->load->model('pediatrician/age_vaccine_master/Age_vaccine_master_archive_model','age_vaccine_archive'); 

        $list = $this->age_vaccine_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $age_vaccine_archive) { 
            $no++;
            $row = array();
            if($age_vaccine_archive->status==1)
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
            if(!empty($age_vaccine_archive->start_age_type))
            {
                if($age_vaccine_archive->start_age_type=='1')
                {
                  $start_age_type='Day';
                }
                if($age_vaccine_archive->start_age_type=='2')
                {
                  $start_age_type='Week';
                }
                 if($age_vaccine_archive->start_age_type=='3')
                {
                  $start_age_type='Month';
                }
                 if($age_vaccine_archive->start_age_type=='4')
                {
                  $start_age_type='Year';
                }
                
            } 
            else
            {
            $start_age_type='';
            } 
            if(!empty($age_vaccine_archive->end_age_type))
            {
                if($age_vaccine_archive->end_age_type=='1')
                {
                  $end_age_type='Day';
                }
                if($age_vaccine_archive->end_age_type=='2')
                {
                  $end_age_type='Week';
                }
                 if($age_vaccine_archive->end_age_type=='3')
                {
                  $end_age_type='Month';
                }
                 if($age_vaccine_archive->end_age_type=='4')
                {
                  $end_age_type='Year';
                }
                
            } 
            else
            {
            $end_age_type='';
            }               
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$age_vaccine_archive->id.'">'.$check_script; 
         
            $row[] = $age_vaccine_archive->title; 
            $row[] = $age_vaccine_archive->start_age; 
            $row[] = $start_age_type; 
            $row[] = $age_vaccine_archive->end_age; 
          
            $row[] = $end_age_type; 
            //$row[] = $age_vaccine_archive->sort_order;             
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($age_vaccine_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
          if(in_array('1604',$users_data['permission']['action']))
          {
               $btnrestore = ' <a onClick="return restore_age_vaccine('.$age_vaccine_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1601',$users_data['permission']['action']))
          {
               $btndelete = ' <a onClick="return trash('.$age_vaccine_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->age_vaccine_archive->count_all(),
                        "recordsFiltered" => $this->age_vaccine_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    ///// age vaccine restore   ///////////////
    public function restore($id="")
    {
      unauthorise_permission('272','1604');
      $this->load->model('pediatrician/age_vaccine_master/Age_vaccine_master_archive_model','age_vaccine_archive');  
         if(!empty($id) && $id>0)
         {
             $result = $this->age_vaccine_archive->restore($id);
             $response = "Age Vaccine Master successfully restore in Age Vaccine list.";
             echo $response;
         }
    }

      ///// age vaccine restoreall   ///////////////
    function restoreall()
    { 
      unauthorise_permission('272','1604');
      $this->load->model('pediatrician/age_vaccine_master/Age_vaccine_master_archive_model','age_vaccine_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->age_vaccine_archive->restoreall($post['row_id']);
            $response = "Age Vaccine Master successfully restore in Age Vaccine list.";
            echo $response;
        }
    }


   ///// age vaccine trash   ///////////////
    public function trash($id="")
    {
      unauthorise_permission('272','1601');
      $this->load->model('pediatrician/age_vaccine_master/Age_vaccine_master_archive_model','age_vaccine_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->age_vaccine_archive->trash($id);
           $response = "Age Vaccine Master successfully deleted parmanently.";
           echo $response;
       }
    }


   ///// age vaccine trashall   ///////////////
    function trashall()
    {
       unauthorise_permission('272','1601');
       $this->load->model('pediatrician/age_vaccine_master/Age_vaccine_master_archive_model','age_vaccine_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->age_vaccine_archive->trashall($post['row_id']);
            $response = "Age Vaccine Master successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// age vaccine Archive end  ///////////////

 

}
?>