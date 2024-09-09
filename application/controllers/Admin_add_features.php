<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Admin_add_features extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		auth_users();
		$this->load->model('admin_add_features/admin_add_features_model','admin_add_features');
		$this->load->library('form_validation');
	}

	public function index()
	{
		
		//unauthorise_permission('1','1');
		$data['page_title'] = 'Add Features List';
		//$data['form_data'] = array('branch_type'=>1);
		//$this->session->unset_userdata('branch_search'); 
		$this->load->view('admin_add_features/list',$data);
	}


	public function ajax_list()
    { 
        //unauthorise_permission('1','1');
        $list = $this->admin_add_features->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $section='';
        foreach ($list as $features_list) {
         // print_r($relation);die;
            $no++;
            $row = array();
            if($features_list->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 

             if(!empty($features_list->section))
             {
             	if($features_list->section==1)
             	{
                    $section='OPD';
             	}
             	if($features_list->section==2)
             	{
             		$section='IPD';

             	}
             	if($features_list->section==3)
             	{
             		$section='Pathology';
             	}
             	if($features_list->section==4)
             	{
             		$section='Medicine';
             	}
             	if($features_list->section==5)
             	{
             		$section='Vaccination';
             	}
             	if($features_list->section==6)
             	{
             		$section='Dialysis';
             	}
             	if($features_list->section==7)
             	{
             		$section='OT';
             	}
             	if($features_list->section==8)
             	{
             		$section='Inventory';
             	}
             	if($features_list->section==9)
             	{
             		$section='Common';
             	}
             }
             else
             {
             	$section='';
             }
            
            ////////// Check  List /////////////////medicine_advice
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$features_list->id.'">'.$check_script; 
            $row[] = $features_list->features;  
            $row[] = date('d-M-Y',strtotime($features_list->start_date));
            $row[] = date('d-M-Y',strtotime($features_list->end_date));
            $row[] = $section;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($features_list->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnedit='';
          $btndelete='';
          //if(in_array('1',$users_data['permission']['action'])){
               $btnedit = ' <a onClick="return edit_add_features('.$features_list->id.');" class="btn-custom" href="javascript:void(0)" style="'.$features_list->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          //}
          // if(in_array('1',$users_data['permission']['action'])){
               $btndelete = ' <a class="btn-custom" onClick="return delete_features('.$features_list->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> '; 
          //}
          $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->admin_add_features->count_all(),
                        "recordsFiltered" => $this->admin_add_features->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
	

	 public function add()
    {
        //unauthorise_permission('1','1');
        $data['page_title'] = "Add Features";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'features'=>"",
                                  'start_date'=>"",
                                  'end_date'=>"",
                                   'section'=>"",
                                  'status'=>"1"
                                  );    

        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->admin_add_features->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admin_add_features/add',$data);       
    }
    
    public function edit($id="")
    {
      //unauthorise_permission('1','1');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->admin_add_features->get_by_id($id); 

        $data['page_title'] = "Update Features";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                   'features'=>$result['features'], 
                                   'start_date'=>date('d-m-Y', strtotime($result['start_date'])), 
                                   'end_date'=>date('d-m-Y', strtotime($result['end_date'])), 
                                  'section'=>$result['section'], 
                                  'status'=>$result['status']
                                  );  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
                $this->admin_add_features->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('admin_add_features/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('features', 'features', 'trim|required'); 
        $this->form_validation->set_rules('start_date', 'start date', 'trim|required'); 
        $this->form_validation->set_rules('end_date', 'end date', 'trim|required'); 
         $this->form_validation->set_rules('section', 'section', 'trim|required');
        if ($this->form_validation->run() == FALSE) 
        {  
            //$reg_no = generate_unique_id(2); 
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'features'=>$post['features'],
                                        'start_date'=>$post['start_date'],
                                        'end_date'=>$post['end_date'],
                                        'section'=>$post['section'], 
                                        'status'=>$post['status']
                                       ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       //unauthorise_permission('1','1');
       if(!empty($id) && $id>0)
       {
           $result = $this->admin_add_features->delete($id);
           $response = "Add features successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
      // unauthorise_permission('1','1');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->admin_add_features->deleteall($post['row_id']);
            $response = "Add features successfully deleted.";
            echo $response;
        }
    }

   


    ///// employee Archive Start  ///////////////
    public function archive()
    {
       // unauthorise_permission('1','1');
        $data['page_title'] = 'Add Features archive list';
        $this->load->helper('url');
        $this->load->view('admin_add_features/archive',$data);
    }

    public function archive_ajax_list()
    {
        //unauthorise_permission('1','1');
        $this->load->model('admin_add_features/admin_add_features_archive_model','add_features_archive'); 

        $list = $this->add_features_archive->get_datatables();  
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        $section='';
        foreach ($list as $add_features_archive) { 
            $no++;
            $row = array();
            if($add_features_archive->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            } 

            if(!empty($add_features_archive->section))
             {
             	if($add_features_archive->section==1)
             	{
                    $section='OPD';
             	}
             	if($add_features_archive->section==2)
             	{
             		$section='IPD';

             	}
             	if($add_features_archive->section==3)
             	{
             		$section='Pathology';
             	}
             	if($add_features_archive->section==4)
             	{
             		$section='Medicine';
             	}
             	if($add_features_archive->section==5)
             	{
             		$section='Vaccination';
             	}
             	if($add_features_archive->section==6)
             	{
             		$section='Dialysis';
             	}
             	if($add_features_archive->section==7)
             	{
             		$section='OT';
             	}
             	if($add_features_archive->section==8)
             	{
             		$section='Inventory';
             	}
             	if($add_features_archive->section==9)
             	{
             		$section='Common';
             	}
             }
             else
             {
             	$section='';
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
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$add_features_archive->id.'">'.$check_script; 
           $row[] = $add_features_archive->features;  
            $row[] = date('d-M-Y',strtotime($add_features_archive->start_date));
            $row[] = date('d-M-Y',strtotime($add_features_archive->end_date));
            $row[] = $section;
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($add_features_archive->created_date)); 
            $users_data = $this->session->userdata('auth_users');
          $btnrestore='';
          $btndelete='';
          //if(in_array('508',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_features('.$add_features_archive->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
         // }
         // if(in_array('507',$users_data['permission']['action'])){
               $btndelete = ' <a onClick="return trash('.$add_features_archive->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
          //}
          $row[] = $btnrestore.$btndelete;
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->add_features_archive->count_all(),
                        "recordsFiltered" => $this->add_features_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        //unauthorise_permission('1','1');
       $this->load->model('admin_add_features/admin_add_features_archive_model','add_features_archive'); 

       if(!empty($id) && $id>0)
       {
           $result = $this->add_features_archive->restore($id);
           $response = "Add features successfully restore in add features list.";
           echo $response;
       }
    }

    function restoreall()
    { 
       // unauthorise_permission('79','508');
         $this->load->model('admin_add_features/admin_add_features_archive_model','add_features_archive'); 
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->add_features_archive->restoreall($post['row_id']);
            $response = "Add features successfully restore in add features list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        //unauthorise_permission('1','1');
        $this->load->model('admin_add_features/admin_add_features_archive_model','add_features_archive');       if(!empty($id) && $id>0)
       {
           $result = $this->add_features_archive->trash($id);
           $response = "Add features successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        //unauthorise_permission('1','1');
       $this->load->model('admin_add_features/admin_add_features_archive_model','add_features_archive');        
       $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->add_features_archive->trashall($post['row_id']);
            $response = "Add features successfully deleted parmanently.";
            echo $response;
        }
    }
 
}
?>