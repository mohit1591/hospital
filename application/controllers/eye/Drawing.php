<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Drawing extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        auth_users();
        $this->load->model('eye/drawing/drawing_model','drawing');
        $this->load->library('form_validation');
    }

    public function index()
    { 
        unauthorise_permission('396','2423');
        $data['page_title'] = 'Drawing List'; 
        $this->load->view('eye/drawing/list',$data);
    }

    public function ajax_list()
    { 
        unauthorise_permission('396','2424');
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->drawing->get_datatables();
        //print '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $drawing) {
         // print_r($drawing);die;
            $no++;
            $row = array();
            if($drawing->status==1)
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
            $check_script = "";
            }                 
           
            ////////// Check list end ///////////// 
            $checkboxs = "";
            if($users_data['parent_id']==$drawing->branch_id)
            {
               $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$drawing->id.'">'.$check_script;
            }else{
               $row[]='';
            }
            $img_path = '';
            if(!empty($drawing->image))
            {
                $img_path = '<img src="'.ROOT_UPLOADS_PATH.'eye/drawing_master/'.$drawing->image.'" width="100px" />';
            }
            $row[] = $drawing->title;
            $row[] = $img_path;  
            $row[] = date('d-M-Y H:i A',strtotime($drawing->created_date)); 
            $btnedit='';
            $btndelete='';
          
            if($users_data['parent_id']==$drawing->branch_id)
            {
              if(in_array('1393',$users_data['permission']['action'])){
               $btnedit =' <a  class="btn-custom" href="'.base_url('eye/drawing/edit/'.$drawing->id).'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
             }
              if(in_array('1394',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_drawing('.$drawing->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              }
            }
          
             $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->drawing->count_all(),
                        "recordsFiltered" => $this->drawing->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

     
    
    public function add()
    {
      //echo json_encode("hi");die;
        unauthorise_permission('396','2424');
        $data['page_title'] = "Add Drawing";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'title'=>"",
                                  'image'=>"", 
                                   ); 
        if(isset($post) && !empty($post))
        {    
            $upload_dir = DIR_UPLOAD_PATH."eye/drawing_master/";
            $img = $_POST['image'];
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file_name =  mktime() . ".jpeg";
            $file = $upload_dir .$file_name;
            $success = file_put_contents($file, $data); 
            $this->drawing->save($file_name);
            echo '1';
            exit;
        }
       $this->load->view('eye/drawing/add',$data);       
    }

    public function edit($id="")
    {
     unauthorise_permission('396','2425');
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->drawing->get_by_id($id);  
        $data['page_title'] = "Update Drawing";  
        $post = $this->input->post();
        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'title'=>$result['title'],
                                  'image'=>$result['image']
                                   );  
        
        if(isset($post) && !empty($post))
        {   
            $upload_dir = DIR_UPLOAD_PATH."eye/drawing_master/";
            $img = $_POST['image'];
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file_name =  mktime() . ".jpeg";
            $file = $upload_dir .$file_name;
            $success = file_put_contents($file, $data); 
            $this->drawing->save($file_name);
            echo '1';
            exit;    
        }
       $this->load->view('eye/drawing/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('title', 'title', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'title'=>$post['title'], 
                                        'image'=>$post['image'], 
                                        'status'=>$post['status']
                                         ); 
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
       unauthorise_permission('396','2426');
       if(!empty($id) && $id>0)
       {
           
           $result = $this->drawing->delete($id);
           $response = "Drawing successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission('396','2426');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->drawing->deleteall($post['row_id']);
            $response = "Drawing successfully deleted.";
            echo $response;
        }
    }
 


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission('396','2427');
        $data['page_title'] = 'Drawing Archive List';
        $this->load->helper('url');
        $this->load->view('eye/drawing/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission('396','2427');
        $this->load->model('eye/drawing/drawing_archive_model','drawing_archive'); 

      
               $list = $this->drawing_archive->get_datatables();
              
                
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $drawing) {
         // print_r($drawing);die;
            $no++;
            $row = array();
            if($drawing->status==1)
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
            $check_script = "";
            }                 
            ////////// Check list end ///////////// 
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$drawing->id.'">'.$check_script; 
            $row[] = $drawing->drawing;  
            $row[] = $status;
            $row[] = date('d-M-Y H:i A',strtotime($drawing->created_date)); 
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
            if(in_array('1397',$users_data['permission']['action'])){
               $btnrestore = ' <a onClick="return restore_drawing('.$drawing->id.');" class="btn-custom" href="javascript:void(0)"  title="Drawing"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
              }
              if(in_array('1396',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$drawing->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>'; 
               }
               $row[] = $btnrestore.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->drawing_archive->count_all(),
                        "recordsFiltered" => $this->drawing_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission('396','2428');
        $this->load->model('eye/drawing/drawing_archive_model','drawing_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->drawing_archive->restore($id);
           $response = "Drawing successfully restore in Drawing list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission('396','2428');
        $this->load->model('eye/drawing/drawing_archive_model','drawing_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->drawing_archive->restoreall($post['row_id']);
            $response = "Drawing successfully restore in Drawing list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission('396','2428');
        $this->load->model('eye/drawing/drawing_archive_model','drawing_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->drawing_archive->trash($id);
           $response = "Drawing successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission('396','2428');
        $this->load->model('eye/drawing/drawing_archive_model','drawing_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->drawing_archive->trashall($post['row_id']);
            $response = "Drawing successfully deleted parmanently.";
            echo $response;
        }
    } 


   public function drawing_dropdown()
  {
      $ot_type_list = $this->drawing->drawing_list();
      //print_r($ot_type_list);die;
      $dropdown = '<option value="">Select Drawing</option>'; 
      if(!empty($ot_type_list))
      {
        foreach($ot_type_list as $ot_type)
        {
           $dropdown .= '<option value="'.$ot_type->id.'">'.$ot_type->drawing.'</option>';
        }
      } 
      echo $dropdown; 
  }
  

}
?>