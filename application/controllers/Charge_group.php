<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Charge_group extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('charge_group/charge_group_model','charge_group');
        $this->load->library('form_validation');
    }

    public function index()
    { 
         //unauthorise_permission('84','551');
        $data['page_title'] = 'Group Charge List'; 
        $this->load->view('charge_group/list',$data);
    }

    public function ajax_list()
    { 
        //unauthorise_permission('84','551');
        $list = $this->charge_group->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $particular_name='';
        $total_num = count($list);
       
        foreach ($list as $charge_group) {
           
         // print_r($relation);die;
            $no++;
            $row = array();
            if($charge_group->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
             $module_name='';
             $particular_name='';
            if($charge_group->module=='1')
            {
              $module_name="Pathalogy";
                $particular_name='';
            }
            if($charge_group->module=='2')
            {
              $module_name="Medicine";
                $particular_name='';
            }
            if($charge_group->module=='3')
            {
              $module_name="OT";
                $particular_name='';
            }
            if($charge_group->module=='4')
            {
              $module_name="Particular";
              if($charge_group->particular_id==0)
              {

                $particular_name='All';
              }
              else
              {
                  $particular_name=$charge_group->particular;
              }
            }
            if($charge_group->module=='5')
            {
              $module_name="Registration Charge";
            }
            if($charge_group->module=='6')
            {
              $module_name="Room Charge";
            }
             if($charge_group->module=='7')
            {
              $module_name="Pacakage Charge";
            }
            if($charge_group->module=='8')
            {
              $module_name="Advance Payment";
            }
            if($charge_group->module=='9')
            {
              $module_name="OT Package";
              $particular_name='';
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$charge_group->id.'">'.$check_script; 
            $row[] = $charge_group->group_code;
            $row[] = $charge_group->group_name;
            $row[] = $module_name; 
            $row[]=$particular_name;    
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($charge_group->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          if(in_array('546',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_charge_group('.$charge_group->id.');" class="btn-custom" href="javascript:void(0)" style="'.$charge_group->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          }
           if(in_array('547',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_charge_group('.$charge_group->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          }
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->charge_group->count_all(),
                        "recordsFiltered" => $this->charge_group->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    
    
    public function add()
    {
        //unauthorise_permission('84','545');
        $this->load->model('general/general_model');
        $data['page_title'] = "Add Group Charge";  
        $post = $this->input->post();
        //print '<pre>';print_r($post);die;
        $data['particulars_list'] = $this->general_model->ipd_particulars_list();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'group_name'=>"",
                                  'group_code'=>"",
                                  'sort_order'=>'',
                                  'module'=>"",
                                  'particular_id'=>0,
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->charge_group->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('charge_group/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('84','546');
       $this->load->model('general/general_model');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->charge_group->get_by_id($id);  
        $data['page_title'] = "Update Group Charge";  
        $data['particulars_list'] = $this->general_model->ipd_particulars_list();
        $post = $this->input->post();
        if($result['particular_id']!=0)
        {
           $particular_id = $result['particular_id'];
        }
        else
        {
           $particular_id  = '0';
        }
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'group_name'=>$result['group_name'],
                                  'group_code'=>$result['group_code'],
                                  'sort_order'=>$result['sort_order'],
                                  'module'=>$result['module'],
                                  'particular_id'=>$particular_id,
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->charge_group->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('charge_group/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('group_name', 'group name', 'trim|required');
        $this->form_validation->set_rules('group_code', 'group code', 'trim|required');
        $this->form_validation->set_rules('module', 'module', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'group_name'=>$post['group_name'],
                                        'group_code'=>$post['group_code'],
                                        'sort_order'=>$post['sort_order'],
                                        'module'=>$post['module'],
                                        'particular_id'=>$post['particular_id'],
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('84','547');
       if(!empty($id) && $id>0)
       {
           $result = $this->charge_group->delete($id);
           $response = "Group Charge successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       //unauthorise_permission('84','547');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->charge_group->deleteall($post['row_id']);
            $response = "Charge Group successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->charge_group->get_by_id($id);  
        $data['page_title'] = $data['form_data']['charge group']." detail";
        $this->load->view('charge_group/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        //unauthorise_permission('84','548');
        $data['page_title'] = 'Charge Group Archive List';
        $this->load->helper('url');
        $this->load->view('charge_group/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('84','548');
        $this->load->model('charge_group/charge_group_archive_model','charge_group_archive'); 

        $list = $this->charge_group_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        
        $total_num = count($list);
      
        foreach ($list as $charge_group_archive) { 
            $no++;
            $row = array();
            
            if($charge_group_archive->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 
              $module_name='';
             $particular_name='';
            if($charge_group_archive->module=='1')
            {
              $module_name="Pathalogy";
                $particular_name='';
            }
            if($charge_group_archive->module=='2')
            {
              $module_name="Medicine";
                $particular_name='';
            }
            if($charge_group_archive->module=='3')
            {
              $module_name="OT";
                $particular_name='';
            }

            if($charge_group_archive->module=='4')
            {
              $module_name="Particular";
              if($charge_group_archive->particular_id==0)
              {

                $particular_name='All';
              }
              else
              {
                  $particular_name=$charge_group_archive->particular;
              }
            }
             if($charge_group_archive->module=='5')
            {
              $module_name="Registration Charge";
            }
            if($charge_group_archive->module=='6')
            {
              $module_name="Room Charge";
            }
            if($charge_group_archive->module=='7')
            {
              $module_name="Pacakage Charge";
            }
             if($charge_group_archive->module=='8')
            {
              $module_name="Advance Payment";
            }
            if($charge_group_archive->module=='9')
            {
              $module_name="OT Package";
              $particular_name='';
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$charge_group_archive->id.'">'.$check_script; 
            $row[] = $charge_group_archive->group_code;  
            $row[] = $charge_group_archive->group_name;  
            $row[] = $module_name;
            $row[]=$particular_name;     
            $row[] = $status;
            //$row[] = date('d-M-Y H:i A',strtotime($charge_group_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
         // if(in_array('550',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_charge_group('.$charge_group_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
          //}
          //if(in_array('549',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$charge_group_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
         // }
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->charge_group_archive->count_all(),
                        "recordsFiltered" => $this->charge_group_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('84','550');
        $this->load->model('charge_group/charge_group_archive_model','charge_group_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->charge_group_archive->restore($id);
           $response = "Charge Group successfully restore in Charge Group list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       // unauthorise_permission('84','550');
        $this->load->model('charge_group/charge_group_archive_model','charge_group_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->charge_group_archive->restoreall($post['row_id']);
            $response = "Charge Group successfully restore in Charge Group list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
       // unauthorise_permission('84','549');
       $this->load->model('charge_group/charge_group_archive_model','charge_group_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->charge_group_archive->trash($id);
           $response = "Charge Group successfully successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('84','549');
        $this->load->model('charge_group/charge_group_archive_model','charge_group_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->charge_group_archive->trashall($post['row_id']);
            $response = "Charge Group successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function particular_dropdown()
  {

      $charge_group_list = $this->charge_group->charge_group_list();
      $dropdown = '<option value="">Select Particular</option>'; 
      if(!empty($charge_group_list))
      {
        foreach($charge_group_list as $charge_group)
        {
           $dropdown .= '<option value="'.$charge_group->id.'">'.$charge_group->group_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

}
?>