<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Banner extends CI_Controller {
 
	function __construct() 
	{
	  parent::__construct();

      $this->load->model('banner/banner_model','banner');
      $this->load->library('form_validation');
    }

    public function index()
	{
		$data['page_title'] = 'Banner List';
		$this->load->view('banner/list', $data);
	}

	public function ajax_list()
    { 
        $list = $this->banner->get_datatables();
        $data = array();
        $no = $_POST['start'];
     
        $i = 1;
        $total_num = count($list);
        foreach ($list as $logo) {
         // print_r($simulation);die;
            $no++;
            $row = array();
            if($logo->status==1)
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
            
            $row[] = '<input type="checkbox" name="employee[]" class="checklist" value="'.$logo->id.'">'.$check_script;
            $row[] = $logo->title; 
            $row[] = '<img src="https://www.hospitalms.in/assets/uploads/banner/'.$logo->banner_image.'" width="80" height="80">';  
            $row[] = $logo->url;
            $row[] = $status;
           
            // $btnedit='';
            $btndelete='';
          
           
          $btnedit =' <a onClick="return edit_logo('.$logo->id.');" class="btn-custom" href="javascript:void(0)" style="'.$logo->id.'" title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</a>';
          $btndelete = ' <a class="btn-custom" onClick="return delete_logo('.$logo->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';   
              
            $row[] = $btnedit.$btndelete;
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->banner->count_all(),
                        "recordsFiltered" => $this->banner->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
 
    public function add()
    {
        
        $data['page_title'] = "Add Banner";  
        $post = $this->input->post();
        $data['form_error'] = []; 
        $data['form_data'] = array(
                                  'data_id'=>"", 
                                  'title'=>"",
                                  'banner_image'=>"",
                                  'url'=>"",
                                  'status'=>'0',
                                  );    
 //print_r($data['form_data']);die;
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
            if($this->form_validation->run() == TRUE)
            {
           
                $this->banner->save();
                echo 1;
                return false;
                
            }
            else
            {
              
                $data['form_error'] = validation_errors();  

            }     
        }
       $this->load->view('banner/add',$data);       
    }

    public function edit($id="")
    {
     
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $result = $this->banner->get_by_id($id);  
        $data['page_title'] = "Update Banner";  
        $post = $this->input->post();

        $data['form_error'] = ''; 
        $data['form_data'] = array(
                                  'data_id'=>$result['id'],
                                  'title'=>$result['title'], 
                                  'banner_image'=>$result['banner_image'],
                                  'url'=>$result['url'],
                                 'status'=>$result['status'],
                                  );  
       
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate();
           
            if($this->form_validation->run() == TRUE)
            {
              
                $this->banner->save();
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }
       $this->load->view('banner/add',$data);       
      }
    }
     
    private function _validate()
    {
        $post = $this->input->post();    
        //$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        //$this->form_validation->set_rules('title', 'title', 'trim|required'); 
        
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>'); 
        
        $this->form_validation->set_rules('title', 'title', 'trim|required'); 
        if ($this->form_validation->run() == FALSE) 
        {  
            $data['form_data'] = array(
                                        'data_id'=>$post['data_id'],
                                        'title'=>$post['title'], 
                                        'banner_image'=>$post['banner_image'],
                                        'url'=>$post['url'],
                                       ); 
            return $data['form_data'];
        }   
    }
    public function delete($id="")
    {
       
       if(!empty($id) && $id>0)
       {
           
           $result = $this->banner->delete($id);
           $response = "Banner successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
       
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->banner->deleteall($post['row_id']);
            $response = "Banner successfully deleted.";
            echo $response;
        }
    }

    public function change_status($id="")
    {
      if(!empty($id) && $id>0)
       {
           
           $result = $this->banner->change_status($id);
           $response = "Banner status changed successfully.";
           echo $response;
       }

    }
}


 ?>