<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Renewal_mail extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('renewal_mail/renewal_mail_model','renewal_mail');
		$this->load->library('form_validation');
	}

	public function index()
	{
		$data['page_title'] = 'Renewal Mail List';
		$this->load->view('renewal_mail/list',$data);
	}


	public function ajax_list()
  { 
      //unauthorise_permission('1','1');
      $list = $this->renewal_mail->get_datatables();  
      $data = array();
      $no = $_POST['start'];
      $i = 1;
      $total_num = count($list);
      $section='';
      foreach ($list as $mail_list) {
       // print_r($relation);die;
          $no++;
          $row = array();
          if($mail_list->status==1)
          {
              $status = '<font color="green">Active</font>';
          }   
          else{
              $status = '<font color="red">Inactive</font>';
          } 

          ////////// Check  List /////////////////medicine_advice
          $check_script = "";
          if($i==$total_num)
          {
          
          }                 
          ////////// Check list end ///////////// 
          $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$mail_list->id.'">'; 
          $row[] = $mail_list->days;  
          $row[] = $status;
          $row[] = date('d-M-Y H:i A',strtotime($mail_list->created_date)); 
          $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
        
          $btnedit = ' <a onClick="return edit_renewal_mail('.$mail_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$mail_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          $btndelete = ' <a class="btn-custom" onClick="return delete_renewal_mail('.$mail_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
        
          $row[] = $btnedit.$btndelete;
          $data[] = $row;
          $i++;
      }

            $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->renewal_mail->count_all(),
                    "recordsFiltered" => $this->renewal_mail->count_filtered(),
                    "data" => $data,
            );
      //output to json format
      echo json_encode($output);
  }
	

	 public function add()
   {
        //unauthorise_permission('1','1');
        $data['page_title'] = "Add Renewal Template";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'days'=>"",
                                  'template'=>"",
                                  'sms_template'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->renewal_mail->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('renewal_mail/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('1','1');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->renewal_mail->get_by_id($id); 

        $data['page_title'] = "Update Renewal Template";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                      'data_id'=>$result['id'],
                      'days'=>$result['days'], 
                      'template'=>$result['template'], 
                      'sms_template'=>$result['sms_template'], 
                      'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->renewal_mail->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('renewal_mail/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('days', 'days', 'trim|required'); 
        $this->form_validation->set_rules('template', 'template', 'trim|required'); 
        
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'days'=>$post['days'],
                                        'template'=>$post['template'],
                                        'sms_template'=>$post['sms_template'],
                                        'status'=>$post['status']
                                       ); 
            echo "<pre>"; print_r($data['form_data']);
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('1','1');
       if(!empty($id) && $id>0)
       {
           $result = $this->renewal_mail->delete($id);
           $response = "Renewal mail template successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      // unauthorise_permission('1','1');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->renewal_mail->deleteall($post['row_id']);
            $response = "Renewal mail template successfully deleted.";
            echo $response;
        }
    }

   


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       // unauthorise_permission('1','1');
        $data['page_title'] = 'Renewal mail archive list';
        $this->load->helper('url');
        $this->load->view('renewal_mail/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('1','1');
        $this->load->model('renewal_mail/renewal_mail_archive_model','renewal_mail_archive'); 

        $list = $this->renewal_mail_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $section='';
        foreach ($list as $renewal_mail_archive) { 
            $no++;
            $row = array();
            if($renewal_mail_archive->status==1)
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$renewal_mail_archive->id.'">'; 
            $row[] = $renewal_mail_archive->days;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($renewal_mail_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('508',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_renewal_mail('.$renewal_mail_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
         // }
         // if(in_array('507',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$renewal_mail_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          //}
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->renewal_mail_archive->count_all(),
                        "recordsFiltered" => $this->renewal_mail_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('1','1');
       $this->load->model('renewal_mail/renewal_mail_archive_model','renewal_mail_archive'); 

       if(!empty($id) && $id>0)
       {
           $result = $this->renewal_mail_archive->restore($id);
           $response = "Renewal mail template restore in add template list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       // unauthorise_permission('79','508');
         $this->load->model('renewal_mail/renewal_mail_archive_model','renewal_mail_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->renewal_mail_archive->restoreall($post['row_id']);
            $response = "Renewal mail template restore in template list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('1','1');
        $this->load->model('renewal_mail/renewal_mail_archive_model','renewal_mail_archive');       if(!empty($id) && $id>0)
       {
           $result = $this->renewal_mail_archive->trash($id);
           $response = "Renewal template successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('1','1');
       $this->load->model('renewal_mail/renewal_mail_archive_model','renewal_mail_archive');        
       $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->renewal_mail_archive->trashall($post['row_id']);
            $response = "Renewal mail template successfully deleted parmanently.";
            echo $response;
        }
    }
 
}
?>