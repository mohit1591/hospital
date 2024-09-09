<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bag_qc extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('blood_bank/bag_qc/Bag_qc_model','bag_qc');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('259','1482');
        $data['page_title'] = 'Bag QC List'; 
        $this->load->view('blood_bank/bag_qc/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('259','1482');
        $list = $this->bag_qc->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
      
        foreach ($list as $bag_qc) 
        {
         //print_r($bag_qc);die;
            $no++;
            $row = array();
           
            if($bag_qc->status==1)
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
            /*$check_script = "<script>$('#selectAll').on('click', function () { 
                                  if ($(this).hasClass('allChecked')) {
                                      $('.checklist').prop('checked', false);
                                  } else {
                                      $('.checklist').prop('checked', true);
                                  }
                                  $(this).toggleClass('allChecked');
                              })</script>";*/
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bag_qc->id.'">'.$check_script; 
            $row[] = $bag_qc->second_unit;   
            $row[] = $bag_qc->first_unit;  
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($bag_qc->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnedit='';
            $btndelete='';
          if(in_array('1484',$users_data['permission']['action']))
          {
               $btnedit = ' <a onClick="return edit_bag_qc('.$bag_qc->id.');" class="btn-custom" href="javascript:void(0)" style="'.$bag_qc->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
          if(in_array('1485',$users_data['permission']['action']))
          {
               $btndelete = ' <a class="btn-custom" onClick="return delete_bag_qc('.$bag_qc->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        

            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bag_qc->count_all(),
                        "recordsFiltered" => $this->bag_qc->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        unauthorise_permission('259','1483');
        $data['page_title'] = "Add Bag QC"; 
        $data['qc_cat_list'] =$this->bag_qc->get_qc_cat_list();
        //print_r($data['qc_cat_list']);
        //die;
        $post = $this->input->post();
       // print_r($post);
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'qc_field'=>"", 
                                  'parent_qc_field'=>"",                                 
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bag_qc->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/bag_qc/add',$data);       
    }
    

    public function edit($id="")
    {
      unauthorise_permission('259','1484');
        $data['qc_cat_list'] =$this->bag_qc->get_qc_cat_list(); 
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->bag_qc->get_by_id($id);  
        $data['page_title'] = "Update Bag QC";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'qc_field'=>$result['qc_field'],
                              'parent_qc_field'=>$result['parent_qc_field'],                                   
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->bag_qc->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('blood_bank/bag_qc/add',$data);       
      }
    }
     

    private function _validate()
    {
          $post = $this->input->post();   

          $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
          $this->form_validation->set_rules('qc_field', 'qc field', 'trim|required|callback_qc_field');
  
          //callback_deferral_reason
          if ($this->form_validation->run() == FALSE) 
          {  
          //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'qc_field'=>$post['qc_field'],                                
                                        'status'=>$post['status']
            ); 
          return $data['form_data'];
          }   
    }

     /* callbackurl */
    /* check validation laready exit */
     public function qc_field($str)
      {
        $post = $this->input->post();
        //print_r($post);
        $parent_qc_field='';
         if(!empty($post['qc_field']))
          {
               $this->load->model('blood_bank/general/blood_bank_general_model','general'); 
               if(!empty($post['data_id']) && $post['data_id']>0)
               {
                if(!empty($post['parent_qc_field']))
                {
                 $parent_qc_field=$post['parent_qc_field'];
                }
                else
                {
                  $parent_qc_field=0;
                }

                    $data_cat= $this->bag_qc->get_by_id($post['data_id']);
                    //print_r($data_cat);
                      if($data_cat['qc_field']==$str && $post['data_id']==$data_cat['id'])
                      {
                          return true;  
                      }
                      else
                      {
                        $qc_field = $this->general->check_qc_field($str,$parent_qc_field );

                        if(empty($qc_field))
                        {
                        return true;
                        }
                        else
                        {
                        $this->form_validation->set_message('qc_field', 'The Bag QC already exists.');
                        return false;
                        }
                      }
               }
               else
               {
                    $check_component_name = $this->general->check_qc_field($post['qc_field'],$post['parent_qc_field'] ,$post['data_id']);
                    if(empty($check_component_name))
                    {
                         return true;
                    }
                    else
                    {
                         $this->form_validation->set_message('qc_field', 'The Bag QC already exists.');
                         return false;
                    }
               }  
          }
          else
          {
               $this->form_validation->set_message('qc_field', 'The Bag QC is required.');
               return false; 
          } 
      }
     /* check validation laready exit */
 
    public function delete($id="")
    {
      unauthorise_permission('259','1485');
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_qc->delete($id);
           $response = "Bag QC successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       unauthorise_permission('259','1485');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_qc->deleteall($post['row_id']);
            $response = "Bag QC successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      { 
           
        $data['form_data'] = $this->component_master->get_by_id($id);  
        $data['page_title'] = $data['form_data']['Bag Type']." detail";
        $this->load->view('blood_bank/bag_type/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('259','1486');
        $data['page_title'] = 'Bag QC archive list';
        $this->load->helper('url');
        $this->load->view('blood_bank/bag_qc/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('259','1486');
      $this->load->model('blood_bank/bag_qc/Bag_qc_archive_model','bag_qc_archive'); 

        $list = $this->bag_qc_archive->get_datatables();  
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        
        foreach ($list as $bag_qc_archive) { 
            $no++;
            $row = array();
            if($bag_qc_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$bag_qc_archive->id.'">'.$check_script; 
            $row[] = $bag_qc_archive->second_unit;   
            $row[] = $bag_qc_archive->first_unit;  
    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($bag_qc_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
          if(in_array('1489',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_component('.$bag_qc_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          }
          if(in_array('1487',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$bag_qc_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->bag_qc_archive->count_all(),
                        "recordsFiltered" => $this->bag_qc_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }



    public function restore($id="")
    {
        unauthorise_permission('259','1489');
    $this->load->model('blood_bank/bag_qc/Bag_qc_archive_model','bag_qc_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_qc_archive->restore($id);
           $response = "Bag QC successfully restore in Bag QC list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       unauthorise_permission('259','1489');
    $this->load->model('blood_bank/bag_qc/Bag_qc_archive_model','bag_qc_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_qc_archive->restoreall($post['row_id']);
            $response = "Bag QC successfully restore in Bag QC list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('259','1487');
       $this->load->model('blood_bank/bag_qc/Bag_qc_archive_model','bag_qc_archive'); 
       if(!empty($id) && $id>0)
       {
           $result = $this->bag_qc_archive->trash($id);
           $response = "Bag QC  successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('259','1487');
        $this->load->model('blood_bank/bag_qc/Bag_qc_archive_model','bag_qc_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->bag_qc_archive->trashall($post['row_id']);
            $response = "Bag QC successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function bag_type_archive_dropdown()
  {

      $deferral_reason_archive_list = $this->bag_type_archive->bag_type_archive_list();
      $dropdown = '<option value="">Select Bag Type</option>'; 
      if(!empty($deferral_reason_archive_list))
      {
        foreach($deferral_reason_archive_list as $deferral_reason_archive)
        {
           $dropdown .= '<option value="'.$deferral_reason_archive->id.'">'.$deferral_reason_archive->deferral_reason_archive.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>