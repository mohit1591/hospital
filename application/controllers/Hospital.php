<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hospital extends CI_Controller {
 
	function __construct() 
	{
		parent::__construct();	
		auth_users();  
		$this->load->model('hospital/hospital_model','hospital');
        $this->load->model('general/general_model');
		$this->load->library('form_validation');
    }

    
	public function index()
    {
        unauthorise_permission(20,121);
        $this->session->unset_userdata('hospital_search');
        $data['form_data'] = array('hospital_code'=>'','hospital_name'=>'','mobile_no'=>'','start_date'=>'', 'end_date'=>'','branch_id'=>'');
        $data['page_title'] = 'Hospital List'; 
        $this->load->view('hospital/list',$data);
    }

    public function ajax_list()
    {   
        unauthorise_permission(20,121);
        $users_data = $this->session->userdata('auth_users');
        $sub_branch_details = $this->session->userdata('sub_branches_data');
        $parent_branch_details = $this->session->userdata('parent_branches_data');
        $list = $this->hospital->get_datatables();

        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $hospital) { 
            $no++;
            $row = array();
            if($hospital->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($hospital->state))
            {
                $state = " ( ".ucfirst(strtolower($hospital->state))." )";
            }
            //////////////////////// 
            
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
           
            if($users_data['parent_id']== $hospital->branch_id){  
            
                    $row[] = '<input type="checkbox" name="hospital[]" class="checklist" value="'.$hospital->id.'">'.$check_script;
                
            }else{
                $row[]='';
            }
           
            $row[] = $hospital->hospital_code;             
            $row[] = $hospital->hospital_name;
            $row[] = $hospital->mobile_no; 
            $row[] = $hospital->email; 
            $row[] = $status; 
            
            $btnedit='';
            $btndelete='';
            $btnview = '';
            if($users_data['parent_id']==$hospital->branch_id){ 
                if(in_array('123',$users_data['permission']['action'])){
                     $btnedit = ' <a onClick="return edit_hospital('.$hospital->id.');" class="btn-custom" href="javascript:void(0)" style="'.$hospital->id.'" title="Edit"><i class="fa fa-pencil"></i> Edit</a>';
                }
            }
                if(in_array('125',$users_data['permission']['action'])){
                    $btnview=' <a class="btn-custom" onclick="return view_hospital('.$hospital->id.')" href="javascript:void(0)" title="View"><i class="fa fa-info-circle"></i> View </a>';
                }
            if($users_data['parent_id']==$hospital->branch_id){     
                if(in_array('124',$users_data['permission']['action'])){
                    $btndelete = ' <a class="btn-custom" onClick="return delete_hospital('.$hospital->id.')" href="javascript:void(0)" title="Delete" data-url="512"><i class="fa fa-trash"></i> Delete</a> ';
                }
             }      
           
         
            $row[] = $btnedit.$btnview.$btndelete;
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->hospital->count_all(),
                        "recordsFiltered" => $this->hospital->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function advance_search()
    {
        $post = $this->input->post();
        if(isset($post) && !empty($post))
        {
            $this->session->set_userdata('hospital_search', $post);
        }
    }

    public function reset_search()
    {
        $this->session->unset_userdata('hospital_search');
    }

	public function add()
	{
         unauthorise_permission(20,122);
		$this->load->model('general/general_model'); 
        $users_data = $this->session->userdata('auth_users');
		$data['page_title'] = "Add Hospital";
		$data['form_error'] = [];
		$data['country_list'] = $this->general_model->country_list();
		$data['person_list'] = $this->general_model->employee_list();
        $data['rate_list'] = $this->general_model->get_rate_list(); 
		$reg_no = generate_unique_id(29);
		$post = $this->input->post();
		$data['form_data'] = array(
                                    "data_id"=>"",
                                    "branch_id"=>"",
                                    "hospital_code"=>$reg_no,
                                    "hospital_name"=>"",
                                    "specilization_id"=>"",
                                    "mobile_no"=>"",
                                    "address"=>"",
                                    "city_id"=>"",
                                    "state_id"=>"",
                                    "country_id"=>"99",
                                    "pincode"=>"",
                                    "email"=>"",
                                    "alt_mobile_no"=>"",
                                    "landline_no"=>"",
                                    
                                    "status"=>"1",
                                    "country_code"=>"+91",
                                   
                                  );
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate('');
            if($this->form_validation->run() == TRUE)
            {
                $hospital_id = $this->hospital->save();
                echo 1;
                return false; 
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }       
        }
        $this->session->unset_userdata('hospital_comission_data');
		$this->load->view('hospital/add',$data);
	}

	public function edit($id="")
    {
         unauthorise_permission(20,123);
     if(isset($id) && !empty($id) && is_numeric($id))
      {       
        $result = $this->hospital->get_by_id($id); 
        // $reg_no = generate_unique_id(3);
        $this->load->model('general/general_model');
        $data['country_list'] = $this->general_model->country_list();
        $data['specialization_list'] = $this->general_model->specialization_list();
        $data['person_list'] = $this->general_model->employee_list(); 
        $data['rate_list'] = $this->general_model->get_rate_list($id);
        $data['page_title'] = "Update Doctor";  
        $post = $this->input->post();
        $data['form_error'] = '';
        
        $data['form_data'] = array(
                                    "data_id"=>$result['id'], 
                                    "hospital_code"=>$result['hospital_code'],
                                    "hospital_name"=>$result['hospital_name'],
                                    "mobile_no"=>$result['mobile_no'],
                                    "address"=>$result['address'],
                                    "city_id"=>$result['city_id'],
                                    "state_id"=>$result['state_id'],
                                    "country_id"=>$result['country_id'],
                                    "rate_plan_id"=>$result['rate_plan_id'],
                                    "pincode"=>$result['pincode'],
                                    "email"=>$result['email'],
                                    "alt_mobile_no"=>$result['alt_mobile_no'],
                                    "landline_no"=>$result['landline_no'],
                                    "marketing_person_id"=>$result['marketing_person_id'],
                                    
                                    "status"=>$result['status'],
                                    "country_code"=>"+91",
                                    
                                  );
                                  
        
        if(isset($post) && !empty($post))
        {   
            $data['form_data'] = $this->_validate($id);
            if($this->form_validation->run() == TRUE)
            {
                $this->hospital->save();
                
                echo 1;
                return false;
                
            }
            else
            {
                $data['form_error'] = validation_errors();  
            }     
        }

       $this->session->unset_userdata('hospital_comission_data'); 
       $this->load->view('hospital/add',$data);       
      }
    }

    public function check_email($str)
    {
        if(!empty($str))
        {
            $this->load->model('general/general_model','general');
            $post = $this->input->post();
            if(!empty($post['data_id']) && $post['data_id']>0)
            {
                return true;
            }
            else
            {
                $userdata = $this->general->check_email($str); 
                if(empty($userdata))
                {
                   return true;
                }
                else
                { 
                    $this->form_validation->set_message('check_email', 'Email already exists.');
                    return false;
                }
            }
        } 
    }
     
    private function _validate($id='')
    {
        $field_list = mandatory_section_field_list(1);
         $users_data = $this->session->userdata('auth_users'); 
        $post = $this->input->post();    
        $this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');  
        $this->form_validation->set_rules('hospital_name', 'hospital name', 'trim|required|callback_check_unique_value['.$id.']');
        $this->form_validation->set_rules('mobile_no', 'mobile no.', 'trim|required|numeric|min_length[10]|max_length[10]');
        
        $this->form_validation->set_rules('pincode', 'pincode', 'trim|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email');//|callback_check_email
        $this->form_validation->set_rules('alt_mobile_no', 'alternate mobile no.', 'trim|numeric|min_length[10]|max_length[10]'); 
        $this->form_validation->set_rules('landline_no', 'landline no.', 'trim|numeric|min_length[9]|max_length[13]'); 

        if ($this->form_validation->run() == FALSE) 
        {  
            
            $reg_no = generate_unique_id(29); 
            $data['form_data'] = array(
										"data_id"=>$post['data_id'], 
										"hospital_code"=>$reg_no,
										"hospital_name"=>$post['hospital_name'],
										
										"mobile_no"=>$post['mobile_no'],
										"address"=>$post['address'],
										"city_id"=>$post['city_id'],
										"state_id"=>$post['state_id'],
										"country_id"=>$post['country_id'],
										"pincode"=>$post['pincode'],
										"email"=>$post['email'],
										"alt_mobile_no"=>$post['alt_mobile_no'],
										"landline_no"=>$post['landline_no'],
										"status"=>$post['status'],
                                        "country_code"=>"+91"
                                        
                                       );
                                
            return $data['form_data'];
        }   
    }
 
    public function delete($id="")
    {
        unauthorise_permission(20,124);
       if(!empty($id) && $id>0)
       {
           $result = $this->hospital->delete($id);
           $response = "Hospital successfully deleted.";
           echo $response;
       }
    }

    function deleteall()
    {
        unauthorise_permission(20,124);
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->hospital->deleteall($post['row_id']);
            $response = "Hospital successfully deleted.";
            echo $response;
        }
    }

    public function view($id="")
    {  
        unauthorise_permission(20,125);
     if(isset($id) && !empty($id) && is_numeric($id))
      {      
        $data['form_data'] = $this->hospital->get_by_id($id);  
        $data['page_title'] = $data['form_data']['hospital_name']." details";
        $this->load->view('hospital/view',$data);     
      }
    }  


    ///// employee Archive Start  ///////////////
    public function archive()
    {
        unauthorise_permission(20,126);
        $data['page_title'] = 'Hospital Archive List';
        $this->load->helper('url');
        $this->load->view('hospital/archive',$data);
    }

    public function archive_ajax_list()
    {
        unauthorise_permission(20,126);
        $users_data = $this->session->userdata('auth_users');
        $this->load->model('hospital/hospital_archive_model','hospital_archive'); 
        $list = $this->hospital_archive->get_datatables();
    
        $data = array();
        $no = $_POST['start'];
        $i = 1;
        $total_num = count($list);
        foreach ($list as $hospital) { 
            $no++;
            $row = array();
            if($hospital->status==1)
            {
                $status = '<font color="green">Active</font>';
            }   
            else{
                $status = '<font color="red">Inactive</font>';
            }
            ///// State name ////////
            $state = "";
            if(!empty($hospital->state))
            {
                $state = " ( ".ucfirst(strtolower($hospital->state))." )";
            }
            //////////////////////// 
            
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
            if($users_data['parent_id']==$hospital->branch_id){  
               
                    $row[] = '<input type="checkbox" name="hospital[]" class="checklist" value="'.$hospital->id.'">'.$check_script;
                
            }else{
                $row[]='';
            }
           $specilization_name ="";
          
            $row[] = $hospital->hospital_code;             
            $row[] = $hospital->hospital_name;
            $row[] = $hospital->mobile_no; 
            $row[] = $hospital->email;
            $row[] = $status;
            $users_data = $this->session->userdata('auth_users');
            $btnrestore='';
            $btndelete='';
             if($users_data['parent_id']==$hospital->branch_id){  
                if(in_array('128',$users_data['permission']['action'])){
                    $btnrestore = ' <a onClick="return restore_hospital('.$hospital->id.');" class="btn-custom" href="javascript:void(0)"  title="Restore"><i class="fa fa-window-restore" aria-hidden="true"></i> Restore </a>';
                }
                if(in_array('127',$users_data['permission']['action'])){
                    $btndelete = ' <a onClick="return trash('.$hospital->id.');" class="btn-custom" href="javascript:void(0)" title="Delete"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
                }
            }
            $row[] = $btnrestore.$btndelete; 
             
        
            $data[] = $row;
            $i++;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->hospital_archive->count_all(),
                        "recordsFiltered" => $this->hospital_archive->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function restore($id="")
    {
        unauthorise_permission(20,128);
        $this->load->model('hospital/hospital_archive_model','hospital_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->hospital_archive->restore($id);
           $response = "Hospital successfully restore in hospital list.";
           echo $response;
       }
    }

    function restoreall()
    { 
        unauthorise_permission(20,128);
        $this->load->model('hospital/hospital_archive_model','hospital_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->hospital_archive->restoreall($post['row_id']);
            $response = "Hospital successfully restore in hospital list.";
            echo $response;
        }
    }

    public function trash($id="")
    {
        unauthorise_permission(20,127);
        $this->load->model('hospital/hospital_archive_model','hospital_archive');
       if(!empty($id) && $id>0)
       {
           $result = $this->hospital_archive->trash($id);
           $response = "Hospital successfully deleted parmanently.";
           echo $response;
       }
    }

    function trashall()
    {
        unauthorise_permission(20,127);
        $this->load->model('hospital/hospital_archive_model','hospital_archive');
        $post = $this->input->post();  
        if(!empty($post))
        {
            $result = $this->hospital_archive->trashall($post['row_id']);
            $response = "Hospital successfully deleted parmanently.";
            echo $response;
        }
    }
    ///// employee Archive end  ///////////////

  public function hospital_dropdown()
  {
      $hospital_list = $this->hospital->hospital_list();
      $dropdown = '<option value="">Select Hospital</option>'; 
      if(!empty($hospital_list))
      {
        foreach($hospital_list as $hospital)
        {
           $dropdown .= '<option value="'.$hospital->id.'">'.$hospital->hospital_name.'</option>';
        }
      } 
      echo $dropdown; 
  }

 

  

  

  function get_comission($id="")
  {
    unauthorise_permission(38,224);
    $users_data = $this->session->userdata('auth_users');
    $json_data = [];
     if(!empty($id) && $id>0)
     {
        if($id==1)
        {
            $arr = array('lable'=>'Share Details', 'inputs'=>'<a href="javascript:void(0)" class="btn-commission"  onclick="comission();"><i class="fa fa-cog"></i> Commission</a>');
            $json_data = json_encode($arr);
        }
        else if($id==2)
        {
            $this->load->model('general/general_model');
            $rate_list = $this->general_model->get_rate_list();
            $drop = '<select name="rate_plan_id" id="rate_plan_id">
                     <option value="">Select Rate Plan</option>';
            if(!empty($rate_list))
            {
                foreach($rate_list as $rate)
                {
                    $drop .= '<option value="'.$rate->id.'">'.$rate->title.'</option>';
                }
            }
            $drop .= '</select>';
            //if(in_array('16',$users_data['permission']['action'])) {
                $drop.='<a href="javascript:void(0)" onclick="rate_modal()" class="btn-new"><i class="fa fa-plus"></i> New</a>';
            //}
            $arr = array('lable'=>'Rate list', 'inputs'=>$drop);
            $json_data = json_encode($arr);
        }
        echo $json_data; 
     }
  }

  public function add_hospital_comission()
  {
     $data['page_title'] = "Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     if(isset($post['id']) && !empty($post['id']))
     {
        $comission_data = $this->hospital->hospital_comission_data($post['id']);
        $this->session->set_userdata('hospital_comission_data',$comission_data);
     } 
     //$data['dept_list'] = $this->general_model->department_list(); 
$data['dept_list'] = $this->general_model->department_list();    
     $path_dept_list = $this->general_model->active_department_list('5'); 
     $path_dept = [];
     if(!empty($path_dept_list))
     {
        foreach($path_dept_list as $path_department)
        {
           $path_dept[] = $path_department->department;
        } 
     }

     $data['path_dept'] = $path_dept;         
     if(isset($post['data']) && !empty($post['data']))
     { 
        $this->session->set_userdata('hospital_comission_data',$post);
        echo '1'; return false;
     }
     $this->load->view('hospital/add_comission',$data);
  }

  public function view_hospital_comission()
  {
     $data['page_title'] = "Share Details";
     $this->load->model('general/general_model');
     $post = $this->input->post();
     //$data['dept_list'] = $this->general_model->department_list();
      $data['dept_list'] = $this->general_model->department_list();    
     $path_dept_list = $this->general_model->active_department_list('5'); 
     $path_dept = [];
     if(!empty($path_dept_list))
     {
        foreach($path_dept_list as $path_department)
        {
           $path_dept[] = $path_department->department;
        } 
     }

     $data['path_dept'] = $path_dept; 
     if(isset($post['id']) && !empty($post['id']))
     {
        $data['comission'] = $this->hospital->hospital_comission_data($post['id']); 
     }  
     $this->load->view('hospital/view_comission',$data);
  }

    public function hospital_excel()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
        $fields = array('Hospital Code','Hospital Name','Mobile','Email');
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
          $col = 0;
          $row_heading =1;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(22);
              
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
              $objPHPExcel->getActiveSheet()->getStyle($row_heading)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $col++;
            $row_heading++;
        }
        $list = $this->hospital->search_hospital_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            foreach($list as $hospital)
            {
                $state = "";
                if(!empty($hospital->state))
                {
                    $state = " ( ".ucfirst(strtolower($hospital->state))." )";
                }
                
                array_push($rowData,$hospital->hospital_code,$hospital->hospital_name,$hospital->mobile_no,$hospital->email);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $hospital_data)
              {
                   $col = 0;
                   $row_val =1;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hospital_data[$field]);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle($row_val)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                        $col++;
                        $row_val++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel5');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=hospital_list_".time().".xls");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
        
    
    }

    public function hospital_csv()
    {
        // Starting the PHPExcel library
        $this->load->library('excel');
        $this->excel->IO_factory();
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setTitle("export")->setDescription("none");
        $objPHPExcel->setActiveSheetIndex(0);
        // Field names in the first row
       $fields = array('Hospital Code','Hospital Name','Mobile','Email');
          $col = 0;
        foreach ($fields as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $list = $this->hospital->search_hospital_data();
        $rowData = array();
        $data= array();
        if(!empty($list))
        {
           
            $i=0;
            foreach($list as $hospital)
            {
                $state = "";
                if(!empty($hospital->state))
                {
                    $state = " ( ".ucfirst(strtolower($hospital->state))." )";
                }
               
                
                array_push($rowData,$hospital->hospital_code,$hospital->hospital_name,$hospital->mobile_no,$hospital->email);
                $count = count($rowData);
                for($j=0;$j<$count;$j++)
                {
                    $data[$i][$fields[$j]] = $rowData[$j];
                }
                unset($rowData);
                $rowData = array();
                $i++;  
            }
             
        }

          // Fetching the table data
          $row = 2;
          if(!empty($data))
          {
              foreach($data as $hospital_data)
              {
                   $col = 0;
                   foreach ($fields as $field)
                   { 
                        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $hospital_data[$field]);
                        $col++;
                   }
                   $row++;
              }
              $objPHPExcel->setActiveSheetIndex(0);
              $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
          }
          
          // Sending headers to force the user to download the file
          header('Content-Type: application/octet-stream charset=UTF-8');
          header("Content-Disposition: attachment; filename=hospital_list_".time().".csv");
         header("Pragma: no-cache"); 
         header("Expires: 0");
         if(!empty($data))
         {
            ob_end_clean();
            $objWriter->save('php://output');
        }
       
    }

    public function hospital_pdf()
    {    
        $data['print_status']="";
        $data['data_list'] = $this->hospital->search_hospital_data();
        $this->load->view('hospital/hospital_html',$data);
        $html = $this->output->get_output();
        // Load library
        $this->load->library('pdf');
        // Convert to PDF
        $this->pdf->load_html($html);
        $this->pdf->render();
        $this->pdf->stream("hospital_list_".time().".pdf");
    }
    public function hospital_print()
    {    
      $data['print_status']="1";
      $data['data_list'] = $this->hospital->search_hospital_data();
      $this->load->view('hospital/hospital_html',$data); 
    }

     function check_unique_value($hospital, $id='') 
      {     
        $users_data = $this->session->userdata('auth_users');
        $result = $this->hospital->check_unique_value($users_data['parent_id'], $hospital,$id);
        if($result == 0)
            $response = true;
        else {
            $this->form_validation->set_message('check_unique_value', 'Hospital name already exist.');
            $response = false;
        }
        return $response;
      }


}
