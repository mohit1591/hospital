<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_company extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('pediatrician/medicine_company/medicine_company_model','medicine_company');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('327','2038');
        $data['page_title'] = 'Manufacturing Company List'; 
        $this->load->view('pediatrician/medicine_company/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('327','2038');
        $list = $this->medicine_company->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $type) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$type->id.'">'.$check_script; 
            $row[] = $type->company_name;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('2040',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_medicine_company('.$type->id.');" class="btn-custom" href="javascript:void(0)" style="'.$type->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
         }
           if(in_array('2041',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_medicine_company('.$type->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_company->count_all(),
                        "recordsFiltered" => $this->medicine_company->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('327','2039');
        $data['page_title'] = "Add Manufacturing Company";  
        $post = $this->input->post();
        //print_r($_POST); exit;
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'medicine_company'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                
                $this->medicine_company->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/medicine_company/add',$data);       
    }
    
    public function edit($id="")
    {
      unauthorise_permission('327','2040');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->medicine_company->get_by_id($id);  
        $data['page_title'] = "Update Manufacturing Company";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'medicine_company'=>$result['company_name'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->medicine_company->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('pediatrician/medicine_company/add',$data);       
      }
    }

    private function _validate()
    {
        $post = $this->input->post();
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('medicine_company', 'manufacturing company', 'trim|required|callback_medicine_company'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'medicine_company'=>$post['medicine_company'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }

        /* check validation laready exit */
   public function medicine_company($str){
          $post = $this->input->post();
          if(!empty($post['medicine_company']))
          {
               $this->load->model('pediatrician/general/pediatrician_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                    $data_cat= $this->medicine_company->get_by_id($post['data_id']);
                      if($data_cat['company_name']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $medicine_company = $this->general->check_medicine_company($str);

                        if(empty($medicine_company))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('medicine_company', 'The manufacturing Company already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $medicine_company = $this->general->check_medicine_company($post['medicine_company'], $post['data_id']);
                    if(empty($medicine_company))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('medicine_company', 'The manufacturing Company already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('medicine_company', 'The manufacturing Company field is required.');
               return false; 
          } 
     }
     /* check validation laready exit */
     
   
 
    public function delete($id="")
    {
       unauthorise_permission('327','2041');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_company->delete($id);
           $response = "Manufacturing company successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('327','2041');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_company->deleteall($post['row_id']);
            $response = "Manufacturing company successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->medicine_company->get_by_id($id);  
        $data['page_title'] = $data['form_data']['medicine_company']." detail";
        $this->load->view('pediatrician/medicine_company/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('327','2042');
        $data['page_title'] = 'Manufacturing company Archive List';
        $this->load->helper('url');
        $this->load->view('pediatrician/medicine_company/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('327','2042');
        $this->load->model('pediatrician/medicine_company/medicine_company_archive_model','medicine_company_archive'); 

        $list = $this->medicine_company_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $type) { 
            $no++;
            $row = array();
            if($type->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$type->id.'">'.$check_script; 
            $row[] = $type->company_name;  
            $row[] = $status;
           //$row[] = date('d-M-Y H:i A',strtotime($type->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          if(in_array('2044',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_type('.$type->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('2043',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$type->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->medicine_company_archive->count_all(),
                        "recordsFiltered" => $this->medicine_company_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('327','2044');
        $this->load->model('pediatrician/medicine_company/medicine_company_archive_model','medicine_company_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_company_archive->restore($id);
           $response = "Manufacturing company successfully restore in Manufacturing company list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('327','2044');
        $this->load->model('pediatrician/medicine_company/medicine_company_archive_model','medicine_company_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_company_archive->restoreall($post['row_id']);
            $response = "Manufacturing company successfully restore in Manufacturing company list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('327','2043');
        $this->load->model('pediatrician/medicine_company/medicine_company_archive_model','medicine_company_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->medicine_company_archive->trash($id);
           $response = "Manufacturing company successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('327','2043');
        $this->load->model('pediatrician/medicine_company/medicine_company_archive_model','medicine_company_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->medicine_company_archive->trashall($post['row_id']);
            $response = "Manufacturing company successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function medicine_company_dropdown()
  {

      /*$medicine_company_list = $this->medicine_company->medicine_company_list();
      $dropdown = '<option value="">Select Medicine Company</option>'; 
      if(!empty($medicine_company_list))
      {
        foreach($medicine_company_list as $medicine_company)
        {
           $dropdown .= '<option value="'.$medicine_company->id.'">'.$medicine_company->medicine_company.'</option>';
        }
      } 
      echo $dropdown; */
  }

}
?>